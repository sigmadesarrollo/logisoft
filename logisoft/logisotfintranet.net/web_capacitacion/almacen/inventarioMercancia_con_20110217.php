<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');	

	$paginado = 30;
	$contador = ($_GET[contador]!="")?$_GET[contador]:0;
	$desde	  = ($paginado*$contador);
	$limite = " limit $desde, $paginado ";
	
	function f_adelante($vdesde,$vpaginado,$total){
		if($vdesde+$vpaginado>($total-1))
			return false;
		else
			return true;
	}
	function f_atras($vdesde){
		if($vdesde==0)
			return false;
		else
			return true;
	}
	function f_paginado($vpaginado,$vtotal){
		if($vpaginado>=$vtotal)
			return false;
		else
			return true;
	}

	if($_GET[accion]==1){//OBTENER DETALLE
		//$row[0]=sucursal, $row[1]=fechainicio, $row[2]=fechafin, $row[3]=estado
		$row = split(",",$_GET[arre]);
		if($row[3]=="EN REPARTO EAD"){
			$condicionV=" g.estado='EN REPARTO EAD'";
			$condicionE=" ge.estado='EN REPARTO EAD'";
		}else if($row[3]=="EAD"){
			$condicionV=" (g.estado='ALMACEN DESTINO' OR g.estado='AUTORIZACION PARA SUSTITUIR' OR g.estado='POR RECIBIR') AND g.ocurre=0 and g.id not like '%Z'";
			$condicionE=" (ge.estado='ALMACEN DESTINO' OR ge.estado='AUTORIZACION PARA SUSTITUIR' OR ge.estado='POR RECIBIR') AND ge.ocurre=0 and ge.id not like '%Z'";
		}else if($row[3]=="OCURRE"){
			$condicionV=" (g.estado='ALMACEN DESTINO' OR g.estado='AUTORIZACION PARA SUSTITUIR' OR g.estado='POR RECIBIR') AND g.ocurre=1 and g.id not like '%Z'";
			$condicionE=" (ge.estado='ALMACEN DESTINO' OR ge.estado='AUTORIZACION PARA SUSTITUIR' OR ge.estado='POR RECIBIR') AND ge.ocurre=1 and ge.id not like '%Z'";
		}else if($row[3] == "ALMACEN TRANSBORDO"){		
			$condicionV = " g.estado = 'ALMACEN TRASBORDO' and g.id not like '%Z'";
			$condicionE = " ge.estado = 'ALMACEN TRASBORDO' and ge.id not like '%Z'";
		}else if($row[3]=="TODOS"){
			/*$condicionV=" (g.estado='EN REPARTO EAD' OR g.estado='ALMACEN DESTINO' OR 
						   g.estado='AUTORIZACION PARA SUSTITUIR' OR g.estado='ALMACEN TRANSBORDO' 
						   OR g.estado='POR RECIBIR')";
			$condicionE=" (ge.estado='EN REPARTO EAD' OR ge.estado='ALMACEN DESTINO' OR 
						   ge.estado='AUTORIZACION PARA SUSTITUIR' OR ge.estado='ALMACEN TRANSBORDO'
						   OR ge.estado='POR RECIBIR')";*/
			
			$condicionV=" (g.estado<>'CANCELADA' AND g.estado <>'CANCELADO' and g.estado <>'ENTREGADA' AND g.estado <> 'ENTREGADO' and g.estado <> 'POR RECIBIR' and g.id not like '%Z')";
			$condicionE=" (ge.estado<>'CANCELADA' AND ge.estado <>'CANCELADO' and ge.estado <>'ENTREGADA' AND ge.estado <> 'ENTREGADO' and ge.estado <> 'POR RECIBIR' and ge.id not like '%Z')";
		}
		
		$fechaV = " AND g.fecha <= '".cambiaf_a_mysql($row[1])."' ";
		$fechaG = " AND ge.fecha <= '".cambiaf_a_mysql($row[1])."' ";
		
		
		$s = "SELECT id,estado, idsucursaldestino,fecha,ocurre FROM guiasventanilla g
		where ".(($row[0]!=1)? "g.idsucursaldestino='".$row[0]."' AND ": "")." $condicionV $fechaV
		UNION
		SELECT id,estado, idsucursaldestino,fecha,ocurre FROM guiasempresariales ge
		where ".(($row[0]!=1)? "ge.idsucursaldestino='".$row[0]."' AND ": "")." $condicionE $fechaG";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		
		$s = "SELECT COUNT(id) cantidad, format(SUM(importe),2) total FROM (
			SELECT g.id, IF(g.tipoflete='1' AND g.condicionpago=0,g.total,0) AS importe
			FROM guiasventanilla g 
			WHERE ".(($row[0]!=1)? "g.idsucursaldestino='".$row[0]."' AND ": "")." $condicionV $fechaV
			GROUP BY g.id
			UNION 
			SELECT ge.id, IF(ge.tipoflete='POR COBRAR' AND ge.tipopago=0,ge.total,0) AS importe
			FROM guiasempresariales ge
			WHERE ".(($row[0]!=1)? "ge.idsucursaldestino='".$row[0]."' AND ": "")." $condicionE $fechaE
			GROUP BY ge.id
		) t";		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "select * from (
			SELECT g.idsucursaldestino, cs.prefijo AS sucursal, g.id AS guia, 
			g.iddestinatario AS nocliente, CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
			DATE_FORMAT(g.fecha,'%d/%m/%Y') fecha, g.estado,g.ocurre, gd.descripcion, gd.contenido, 
			IF(g.tipoflete='1' AND g.condicionpago=0,g.total,0) AS importe,
			IF(g.estado='ALMACEN DESTINO' AND g.ocurre=1,'OCURRE',IF(g.estado='ALMACEN DESTINO' AND g.ocurre=0,'EAD',
			IF(g.estado='EN TRANSITO' AND g.ocurre=1,'EN TRANSITO',IF(g.estado='EN TRANSITO' AND g.ocurre=0,
			'EN TRANSITO','')))) AS almacen, IF(g.tipoflete=0,'PAGADA','POR COBRAR') AS flete, IF(g.condicionpago=0,'CONTADO','CREDITO') AS pago
			FROM guiasventanilla g 
			LEFT JOIN catalogosucursal cs ON g.idsucursaldestino = cs.id 
			LEFT JOIN catalogocliente cc ON g.iddestinatario = cc.id 
			LEFT JOIN guiaventanilla_detalle gd ON g.id = gd.idguia
			WHERE ".(($row[0]!=1)? "g.idsucursaldestino='".$row[0]."' AND ": "")." $condicionV $fechaV
			GROUP BY g.id
			UNION 
			SELECT ge.idsucursaldestino, cs.prefijo AS sucursal, ge.id AS guia, 
			ge.iddestinatario AS nocliente, CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
			DATE_FORMAT(ge.fecha,'%d/%m/%Y') fecha, ge.estado,ge.ocurre, gd.descripcion, gd.contenido, 
			IF(ge.tipoflete='POR COBRAR' AND ge.tipopago=0,ge.total,0) AS importe,
			IF(ge.estado='ALMACEN DESTINO' AND ge.ocurre=1,'OCURRE',IF(ge.estado='ALMACEN DESTINO' AND ge.ocurre=0,'EAD',
			IF(ge.estado='EN TRANSITO' AND ge.ocurre=1,'EN TRANSITO',IF(ge.estado='EN TRANSITO' AND ge.ocurre=0,
			'EN TRANSITO','')))) AS almacen, ge.tipoflete AS flete, ge.tipopago AS pago
			FROM guiasempresariales ge
			LEFT JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id 
			LEFT JOIN catalogocliente cc ON ge.iddestinatario = cc.id 
			LEFT JOIN guiasempresariales_detalle gd ON ge.id = gd.id
			WHERE ".(($row[0]!=1)? "ge.idsucursaldestino='".$row[0]."' AND ": "")." $condicionE $fechaE
			GROUP BY ge.id
		) t $limite";	
		
//		die($s);	
		$r = mysql_query($s,$l) or die($s);
		$arr = array();			
		while($f = mysql_fetch_object($r)){				
			$f->nocliente = cambio_texto($f->nocliente);
			$f->cliente = cambio_texto($f->cliente);
			$f->descripcion = cambio_texto($f->descripcion);
			$f->contenido = cambio_texto($f->contenido);
			$f->guia = cambio_texto($f->guia);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	}

?>

