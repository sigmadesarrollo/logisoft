<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion] == 1){
		$s = "SELECT * FROM entregasocurrealmacen 
		WHERE folioentregasocurre=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)==0){
			$s = "DELETE FROM entregasocurrealmacen_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]'";
			mysql_query($s,$l) or die($s);
			$s = "INSERT INTO entregasocurrealmacen_tmp
			SELECT gv.id, 'NORMAL' AS tipoguia, gv.fecha, CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno),
			CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno), gv.total, IF(gv.estado='ENTREGADA',0,1), 
			'$_SESSION[IDUSUARIO]',".$_SESSION[IDSUCURSAL].",gv.totalpaquetes, 
			IF(rdf.guia IS NOT NULL,'SI','NO') AS endanofaltante
			FROM guiasventanilla AS gv
			INNER JOIN catalogocliente AS cc1 ON gv.idremitente = cc1.id
			INNER JOIN catalogocliente AS cc2 ON gv.iddestinatario = cc2.id
			INNER JOIN entregasocurre_detalle AS eod ON gv.id = eod.guia
			LEFT JOIN reportedanosfaltante rdf ON gv.id = rdf.guia AND rdf.faltante = 1
			WHERE eod.entregaocurre = $_GET[folio] AND eod.sucursal = ".$_SESSION[IDSUCURSAL]."
			UNION
			SELECT ge.id, ge.tipoguia, ge.fecha, CONCAT_WS(' ',cc1.nombre,cc1.paterno,cc1.materno),
			CONCAT_WS(' ',cc2.nombre,cc2.paterno,cc2.materno), ge.total, IF(ge.estado='ENTREGADA',0,1), 
			'$_SESSION[IDUSUARIO]',".$_SESSION[IDSUCURSAL].", ge.totalpaquetes,
			IF(rdf.guia IS NOT NULL,'SI','NO') AS endanofaltante
			FROM guiasempresariales AS ge
			INNER JOIN catalogocliente AS cc1 ON ge.idremitente = cc1.id
			INNER JOIN catalogocliente AS cc2 ON ge.iddestinatario = cc2.id
			INNER JOIN entregasocurre_detalle AS eod ON ge.id = eod.guia
			LEFT JOIN reportedanosfaltante rdf ON ge.id = rdf.guia AND rdf.faltante = 1
			WHERE eod.entregaocurre = $_GET[folio] AND eod.sucursal = ".$_SESSION[IDSUCURSAL]."";			
			$r = mysql_query($s,$l) or die($s);		
			
			$s = "SELECT * FROM entregasocurrealmacen_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]'";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			if(mysql_num_rows($r)>0){			
				while($f = mysql_fetch_object($r)){
					$f->noguia 		= cambio_texto($f->noguia);
					$f->tipoguia	= cambio_texto($f->tipoguia);
					$f->fecha 		= cambiaf_a_normal($f->fecha);
					$f->remitente	= cambio_texto($f->remitente);
					$f->destinatario= cambio_texto($f->destinatario);
					$f->importe		= cambio_texto($f->importe);
					$f->entregada	= $f->entregada;
					$registros[] 	= $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}else{
			echo "ya existe";
		}
	}else if($_GET[accion]==2){
		$principal = "";
		$s = "SELECT cs.descripcion as sucursal, DATE_FORMAT(e.fecha,'%d/%m/%Y') AS fecha,
		e.folioentregasocurre FROM entregasocurrealmacen e
		INNER JOIN catalogosucursal cs ON e.sucursal = cs.id
		WHERE folio=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->sucursal = cambio_texto($f->sucursal);
			$principal = str_replace('null','""',json_encode($f));
			
			$s = "SELECT noguia, tipoguia, DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,
			remitente, destinatario, importe, entregada FROM entregasocurrealmacen_detalle
			WHERE identrega=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			$detalle = "";		
				while($f = mysql_fetch_object($r)){
					$f->noguia = cambio_texto($f->noguia);
					$f->tipoguia = cambio_texto($f->tipoguia);
					$f->remitente = cambio_texto($f->remitente);
					$f->destinatario = cambio_texto($f->destinatario);
					$registros[] = $f;
				}
				$detalle = str_replace('null','""',json_encode($registros));
			echo "({principal:$principal,detalle:$detalle})";
		}else{
			echo "no encontro";
		}
	}
?>