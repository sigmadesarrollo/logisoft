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
	
	if($_GET[accion]==1){//Atrasos Embarques
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==2){//Cancelaciones Pendiente Autorizar
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, o.descripcion AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		d.descripcion AS destino, CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS flete, gv.total,
		DATE_FORMAT(can.fecha,'%d/%m/%Y') AS fechacancelacion, cm.descripcion AS motivo,
		CONCAT_WS(' ', ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS usuario
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.idremitente = de.id
		LEFT JOIN cancelacionguiasventanilla can ON gv.id = can.guia
		INNER JOIN catalogomotivos cm ON can.motivocancelacion = cm.id
		INNER JOIN catalogoempleado ce ON can.usuario = ce.id
		WHERE gv.estado = 'AUTORIZACION PARA CANCELAR' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen=".$_SESSION[IDSUCURSAL]."" : "")."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){				
					$f->origen = cambio_texto($f->origen);
					$f->remitente = cambio_texto($f->remitente);
					$f->destino = cambio_texto($f->destino);
					$f->destinatario = cambio_texto($f->destinatario);
					$f->motivo = cambio_texto($f->motivo);
					$f->usuario = cambio_texto($f->usuario);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==3){//Cobranza 30 dias	
		$s = "SELECT * FROM cobranza30dias_tmp		
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT factura, date_format(fechaemision,'%d/%m/%Y') as fechaemision,
		cliente, nombre, importe, tipofactura, date_format(fechavencimiento,'%d/%m/%Y') as fechavencimiento,
		diasatraso, contrarecibo, diapago FROM cobranza30dias_tmp
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->nombre);
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
		
	}else if($_GET[accion]==4){//Credito Linea Saturada
		$s = "SELECT sc.folio AS credito,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
		CONCAT(sc.calle,' ',sc.numero, ' ',sc.colonia,' ',sc.poblacion) AS direccion,
		IFNULL(sc.montoautorizado,0) AS limitecredito,
		IFNULL(mc.montoconsumido,0) AS montoconsumido,
		IFNULL(mv.montovencido,0) AS montovencido,
		IF (IFNULL(mc.montoconsumido,0)<>0,mc.vendedor,IF(IFNULL(mv.montovencido,0)<>0,mv.vendedor,'')) AS vendedorasignado 
		FROM solicitudcredito sc
		INNER JOIN catalogocliente cc ON sc.cliente=cc.id
		LEFT JOIN 
			(SELECT cliente,SUM(montovencido) AS montovencido,vendedor FROM (
			SELECT cc.id AS cliente,SUM(gv.total)AS montovencido,gv.nvendedorconvenio AS vendedor FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON cc.id=IF(gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
			WHERE DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>0 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sc.idsucursal='" .$_GET[sucursal]."' " :"")."			
			AND gv.estado<>'CANCELADA'GROUP BY cc.id
			UNION
			SELECT cc.id AS cliente,SUM(gv.total)AS montovencido,gv.nvendedorconvenio FROM guiasempresariales gv
			INNER JOIN catalogocliente cc ON cc.id=IF(gv.tipoflete='PAGADO',gv.idremitente,gv.iddestinatario)
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
			WHERE DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>0 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sc.idsucursal='" .$_GET[sucursal]."' " :"")."
			AND gv.estado<>'CANCELADA' GROUP BY cc.id
			)Tabla GROUP BY cliente)mv ON cc.id=mv.cliente
		LEFT JOIN
			(SELECT cliente,SUM(montoconsumido) AS montoconsumido,vendedor FROM (
			SELECT cc.id AS cliente,SUM(gv.total)AS montoconsumido,gv.nvendedorconvenio AS vendedor FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON cc.id=IF(gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
			WHERE DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))<=0
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sc.idsucursal='" .$_GET[sucursal]."' " :"")."
			AND gv.estado<>'CANCELADA' GROUP BY cc.id
			UNION
			SELECT cc.id AS cliente,SUM(gv.total)AS montoconsumido,gv.nvendedorconvenio AS vendedor FROM guiasempresariales gv
			INNER JOIN catalogocliente cc ON cc.id=IF(gv.tipoflete='PAGADO',gv.idremitente,gv.iddestinatario)
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
			WHERE DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))<=0			
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sc.idsucursal='".$_GET[sucursal]."' " :"")."
			AND gv.estado<>'CANCELADA' GROUP BY cc.id
			)Tabla GROUP BY cliente)mc ON cc.id=mc.cliente
		WHERE sc.idsucursal='" .$_GET[sucursal]."' GROUP BY sc.folio 
		ORDER BY cc.nombre,cc.paterno,cc.materno LIMIT ".$_GET[inicio].",30";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
					$f->cliente = cambio_texto($f->cliente);
					$f->direccion = cambio_texto($f->direccion);
					$f->vendedorasignado = cambio_texto($f->vendedorasignado);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==5){//Entregas Atrasadas
			$s = "SELECT guia,DATE_FORMAT(fechaemision,'%d/%m/%Y')AS fechaemision,remitente,destinatario,direccion, 
			sector,tipoflete,importe,tipoentrega 
		FROM (  	
			SELECT gv.id AS guia,gv.fecha AS fechaemision,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS remitente,
			CONCAT_WS(' ',ccc.nombre,ccc.paterno,ccc.materno) AS destinatario,
			CONCAT(d.calle,' #',d.numero,' ',d.colonia,' ',d.poblacion) AS direccion, 
			cs.descripcion AS sector,IF (gv.tipoflete=0,'PAGADA','POR COBRAR') AS tipoflete, 
			gv.total AS importe,IF(gv.ocurre=1,'OCURRE','EAD') AS tipoentrega  
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON gv.idremitente=cc.id
			INNER JOIN catalogocliente ccc ON gv.iddestinatario=ccc.id
			INNER JOIN direccion d ON gv.iddirecciondestinatario=d.id
			LEFT JOIN catalogosector cs ON gv.sector=cs.id
			WHERE 
			".(($_SESSION[IDSUCURSAL]!=1)? " gv.idsucursalorigen = '" .$_SESSION[IDSUCURSAL]."' AND " :"")."
			gv.ocurre=".(($_GET[tipo]=="")?0:1)."
			GROUP BY gv.id 
		UNION
			SELECT ge.id AS guia,ge.fecha AS fechaemision,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS remitente,
			CONCAT_WS(' ',ccc.nombre,ccc.paterno,ccc.materno) AS destinatario,
			CONCAT(d.calle,' #',d.numero,' ',d.colonia,' ',d.poblacion) AS direccion, 
			cs.descripcion AS sector, ge.tipoflete, 
			ge.total AS importe,IF(ge.ocurre=1,'OCURRE','EAD') AS tipoentrega  
			FROM guiasempresariales ge
			INNER JOIN catalogocliente cc ON ge.idremitente=cc.id
			INNER JOIN catalogocliente ccc ON ge.iddestinatario=ccc.id
			INNER JOIN direccion d ON ge.iddirecciondestinatario=d.id
			LEFT JOIN catalogosector cs ON ge.sector=cs.id
			WHERE ".(($_SESSION[IDSUCURSAL]!=1)? " ge.idsucursalorigen = '" .$_SESSION[IDSUCURSAL]."' AND " :"")."
			ge.ocurre=".(($_GET[tipo]=="")?0:1)."
			GROUP BY ge.id 
		)Tabla ORDER BY remitente,destinatario,direccion LIMIT ".$_GET[inicio].",30";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->sector = cambio_texto($f->sector);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==6){//Entregas Por Vencer.php
		$s = "SELECT folio AS convenio,idcliente,CONCAT(nombre,' ',apaterno,' ',amaterno)AS cliente,
		CONCAT(calle,' ',numero,' ',colonia,' ',poblacion) AS direccion,
		DATE_FORMAT(vigencia,'%d/%m/%Y') AS fechavencimiento,
		0 AS tipoconvenio,0 AS precios,ifnull(nvendedor,'') AS vendedorasignado 
		FROM generacionconvenio 
		WHERE ".(($_SESSION[IDSUCURSAL]!=1)? " sucursal = '" .$_GET[sucursal]."'":"")."
		ORDER BY nombre,apaterno,amaterno LIMIT ".$_GET[inicio].",30";
		
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				$f->cliente = cambio_texto($f->cliente);
				$f->direccion = cambio_texto($f->direccion);
				$f->vendedorasignado = cambio_texto($f->vendedorasignado);
				
				$sql="SELECT precioporkg,precioporcaja,descuentosobreflete,
							prepagadas,consignacionkg,consignacioncaja,
							consignaciondescuento FROM generacionconvenio WHERE folio='$f->convenio'";
						$d = mysql_query($sql,$l) or die($sql);
						if(mysql_num_rows($d)>0){
							$t = mysql_fetch_object($d);
							$conbinacion=0;
							if ($t->precioporkg!=0){
								$conbinacion1='KILOGRAMO'; 
							}
							
							if ($t->precioporcaja!=0){
								$conbinacion2='PAQUETE'; 
							}
							
							if ($t->descuentosobreflete!=0){
								$conbinacion3='DESCUENTO'; 
							}
							
							if ($t->prepagadas!=0){
								$conbinacion4='PREPAGADAS'; 
							}
							
							if ($t->consignacionkg!=0){
								$conbinacion5='KILOGRAMO'; 
							}
							
							if ($t->consignacioncaja!=0){
								$conbinacion6='PAQUETE'; 
							}
							
							if ($t->consignaciondescuento!=0){
								$conbinacion7='DESCUENTO'; 
							}
							if ($conbinacion1!="" or $conbinacion2!="" or $conbinacion3!=""){
								$conbinacion8='GUIA NORMAL-'.$conbinacion1.$conbinacion2.$conbinacion3;	
							}
							
							if ($conbinacion4!=""  or $conbinacion5!="" or $conbinacion6!="" or $conbinacion7!=""){
								$conbinacion9='GUIA EMPRESARIAL-'.$conbinacion4.$conbinacion5.$conbinacion6.$conbinacion7;		
							}
							
							$f->tipoconvenio=$conbinacion8.$conbinacion9;
						}
				
				$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
				
	}else if($_GET[accion]==7){//Evaluacion Pendiente Generar Guia
		$s = "SELECT fechaevaluacion, folio, recoleccion, guiaempresarial FROM evaluacionmercancia
		WHERE estado = 'GUARDADO' ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal=".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){				
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==8){//Facturas Canceladas
		$s = "SELECT f.folio, f.fecha, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		'' AS tipofactura, (f.total + f.sobmontoafacturar + f.otrosmontofacturar) AS importe 
		FROM facturacion f
		INNER JOIN catalogocliente cc ON f.cliente = cc.id
		WHERE f.facturaestado='CANCELADO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal=".$_SESSION[IDSUCURSAL]."":"")."		
		GROUP BY f.folio";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){				
					$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==9){//Guias Canceladas
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==10){//Guias Con danos
		$s = "SELECT * FROM reportedanosfaltante
		WHERE dano = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT t.guia, t.fecha, t.remitente, t.destinatario,t.destino, t.fechaembarque,
		t.fecharecepcion, rd.id AS foliodano, 
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable
		FROM reportedanosfaltante rd
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		INNER JOIN (SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, DATE_FORMAT(emb.fecha,'%d/%m/%Y') AS fechaembarque,
		DATE_FORMAT(rec.fecha,'%d/%m/%Y') AS fecharecepcion
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.idremitente = de.id
		INNER JOIN (SELECT e.fecha, ed.guia FROM embarquedemercancia e
		INNER JOIN embarquedemercanciadetalle ed ON e.folio = ed.idembarque AND e.idsucursal = ed.sucursal) emb 
		ON emb.guia = gv.id
		INNER JOIN (SELECT re.fecha, rd.guia FROM recepcionmercancia re
		INNER JOIN recepcionmercanciadetalle rd ON re.folio = rd.recepcion AND re.idsucursal = rd.sucursal) rec 
		ON gv.id = rec.guia
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, DATE_FORMAT(emb.fecha,'%d/%m/%Y') AS fechaembarque,
		DATE_FORMAT(rec.fecha,'%d/%m/%Y') AS fecharecepcion
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.idremitente = de.id
		INNER JOIN (SELECT e.fecha, ed.guia FROM embarquedemercancia e
		INNER JOIN embarquedemercanciadetalle ed ON e.folio = ed.idembarque AND e.idsucursal = ed.sucursal) emb 
		ON emb.guia = ge.id
		INNER JOIN (SELECT re.fecha, rd.guia FROM recepcionmercancia re
		INNER JOIN recepcionmercanciadetalle rd ON re.folio = rd.recepcion AND re.idsucursal = rd.sucursal) rec 
		ON ge.id = rec.guia) t ON rd.guia = t.guia
		WHERE rd.dano = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND rd.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->cliente);
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
		
	}else if($_GET[accion]==11){//Guias Extraviadas
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==12){//Guias Faltantes
		$s = "SELECT * FROM reportedanosfaltante
		WHERE faltante = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT t.guia, t.fecha, t.remitente, t.destinatario,t.destino, t.fechaembarque,
		t.fecharecepcion, rd.id AS foliodano, 
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable
		FROM reportedanosfaltante rd
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		INNER JOIN (SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, DATE_FORMAT(emb.fecha,'%d/%m/%Y') AS fechaembarque,
		DATE_FORMAT(rec.fecha,'%d/%m/%Y') AS fecharecepcion
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.idremitente = de.id
		INNER JOIN (SELECT e.fecha, ed.guia FROM embarquedemercancia e
		INNER JOIN embarquedemercanciadetalle ed ON e.folio = ed.idembarque AND e.idsucursal = ed.sucursal) emb 
		ON emb.guia = gv.id
		INNER JOIN (SELECT re.fecha, rd.guia FROM recepcionmercancia re
		INNER JOIN recepcionmercanciadetalle rd ON re.folio = rd.recepcion AND re.idsucursal = rd.sucursal) rec 
		ON gv.id = rec.guia
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, DATE_FORMAT(emb.fecha,'%d/%m/%Y') AS fechaembarque,
		DATE_FORMAT(rec.fecha,'%d/%m/%Y') AS fecharecepcion
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.idremitente = de.id
		INNER JOIN (SELECT e.fecha, ed.guia FROM embarquedemercancia e
		INNER JOIN embarquedemercanciadetalle ed ON e.folio = ed.idembarque AND e.idsucursal = ed.sucursal) emb 
		ON emb.guia = ge.id
		INNER JOIN (SELECT re.fecha, rd.guia FROM recepcionmercancia re
		INNER JOIN recepcionmercanciadetalle rd ON re.folio = rd.recepcion AND re.idsucursal = rd.sucursal) rec 
		ON ge.id = rec.guia) t ON rd.guia = t.guia
		WHERE rd.faltante = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND rd.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->cliente);
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
		
	}else if($_GET[accion]==13){//Guias Faltantes de Recoleccion Ead
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==14){//Guias No Embarcadas
		$s = "SELECT * FROM
		(SELECT gv.id FROM guiasventanilla gv		
		WHERE gv.estado = 'ALMACEN ORIGEN' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id FROM guiasempresariales ge
		WHERE ge.estado = 'ALMACEN ORIGEN' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") tb";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * FROM
		(SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, o.prefijo AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		d.prefijo AS destino, CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		cs.descripcion AS sector, IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total,
		IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		LEFT JOIN catalogosector cs ON gv.sector = cs.id
		WHERE gv.estado = 'ALMACEN ORIGEN' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, o.prefijo AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		d.prefijo AS destino, CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		cs.descripcion AS sector, IF(ge.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, ge.total,
		IF(ge.ocurre=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		LEFT JOIN catalogosector cs ON ge.sector = cs.id
		WHERE ge.estado = 'ALMACEN ORIGEN' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") tb
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->remitente = cambio_texto($f->remitente);
			$f->destino = cambio_texto($f->destino);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->sector = cambio_texto($f->sector);
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
		
	}else if($_GET[accion]==15){//Guias Sin Ruta Clientes Corporativo
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==16){//Guias Sin Ruta Clientes Premium
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==17){//Guias Sobrantes
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==18){//Recolecciones Atrasadas
		$s = "SELECT DISTINCT r.folio, DATE_FORMAT(r.fecharegistro,'%d/%m/%Y')AS fecharegistro,
		rd.contenido, rd.descripcion,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' Col. ',r.colonia,', ',r.poblacion) AS direccion,
		d.descripcion AS destino FROM recoleccion r
		INNER JOIN recolecciondetalle rd ON r.folio = rd.recoleccion
		INNER JOIN catalogocliente cc ON r.cliente = cc.id
		INNER JOIN catalogodestino d ON r.destino = d.id
		WHERE r.fecharegistro < CURDATE() AND r.estado<>'REALIZADO' AND r.estado<>'CANCELADO'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND r.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."
		GROUP BY r.folio LIMIT ".$_GET[inicio].",30";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){				
					$f->cliente = cambio_texto($f->cliente);
					$f->direccion = cambio_texto($f->direccion);
					$f->destino = cambio_texto($f->destino);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==19){//Retraso De Almacen
		$s = "SELECT g.guia, g.fecha, g.remitente, g.destinatario,
		g.direccion, g.tipoflete, g.total, DATEDIFF(g.fecha, rm.fecha) AS diasalmacen
		FROM recepcionmercancia rm
		INNER JOIN recepcionmercanciadetalle rd ON rm.folio = rd.recepcion
		INNER JOIN (SELECT gv.id AS guia, gv.estado, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',d.nombre,d.paterno,d.materno) AS destinatario,
		CONCAT(dir.calle,' #',dir.numero,' Col.',dir.colonia,', ',dir.poblacion) AS direccion,
		IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total
		FROM guiasventanilla gv 
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente d ON gv.iddestinatario = d.id
		INNER JOIN direccion dir ON gv.iddirecciondestinatario = dir.id
		WHERE gv.estado='ALMACEN DESTINO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id AS guia, ge.estado, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',d.nombre,d.paterno,d.materno) AS destinatario,
		CONCAT(dir.calle,' #',dir.numero,' Col.',dir.colonia,', ',dir.poblacion) AS direccion,
		ge.tipoflete, ge.total FROM guiasempresariales ge
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente d ON ge.iddestinatario = d.id
		INNER JOIN direccion dir ON ge.iddirecciondestinatario = dir.id
		WHERE ge.estado='ALMACEN DESTINO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") g ON rd.guia = g.guia";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){				
					$f->remitente = cambio_texto($f->remitente);
					$f->destinatario = cambio_texto($f->destinatario);
					$f->direccion = cambio_texto($f->direccion);					
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
			
	}else if($_GET[accion] == 20){
		$s = "SELECT c.id, CONCAT_WS(' ',c.nombre,c.paterno,c.materno) AS nombre,
		CONCAT(d.calle,' #',d.numero,', ',d.colonia) AS direccion,
		d.poblacion, d.estado, IFNULL(s.montoautorizado,0) AS credito
		FROM catalogocliente c 
		INNER JOIN direccion d ON c.id = d.codigo AND d.origen = 'cl'
		LEFT JOIN generacionconvenio co ON c.id = co.idcliente
		LEFT JOIN solicitudcredito s ON c.id = s.cliente
		WHERE c.tipocliente = 2 AND d.facturacion = 'SI'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND c.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT c.id, CONCAT_WS(' ',c.nombre,c.paterno,c.materno) AS nombre,
		CONCAT(d.calle,' #',d.numero,', ',d.colonia) AS direccion,
		d.poblacion, d.estado, IFNULL(s.montoautorizado,0) AS credito,
		IFNULL(co.folio,'') AS convenio, 
		CONCAT(IF(co.descuentosobreflete=1 OR co.consignaciondescuento=1,'DESCUENTO,', ''), 
		IF(co.precioporkg=1 OR co.consignacionkg=1,'KILOGRAMO,',''), IF(co.precioporcaja=1 
		OR co.consignacioncaja=1,'PAQUETE,',''), IF(co.prepagadas=1,'PREPAGADA,','')) AS tipoconvenio 
		FROM catalogocliente c 
		INNER JOIN direccion d ON c.id = d.codigo AND d.origen = 'cl'
		LEFT JOIN generacionconvenio co ON c.id = co.idcliente
		LEFT JOIN solicitudcredito s ON c.id = s.cliente
		WHERE c.tipocliente = 2 AND d.facturacion = 'SI'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND c.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$f->direccion = cambio_texto($f->direccion);
			$f->poblacion = cambio_texto($f->poblacion);
			$f->estado = cambio_texto($f->estado);
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
			
	}else if($_GET[accion] == 21){
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',rm.nombre,rm.paterno,rm.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(gv.sector,0) AS sector FROM guiasventanilla gv
		INNER JOIN catalogocliente rm ON gv.idremitente = rm.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.estado = 'ALMACEN DESTINO' AND gv.entradasalida = 'SALIDA'
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',rm.nombre,rm.paterno,rm.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(ge.sector,0) AS sector FROM guiasempresariales ge
		INNER JOIN catalogocliente rm ON ge.idremitente = rm.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.estado = 'ALMACEN DESTINO' AND ge.entradasalida = 'SALIDA'";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){				
					$f->remitente = cambio_texto($f->remitente);
					$f->destinatario = cambio_texto($f->destinatario);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
			
	}else if($_GET[accion]==22){
		$s = "SELECT porcentajelimitecredito AS porcentaje FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$c = mysql_fetch_object($r);
		
		$s = "SELECT sc.folio as credito, sc.montoautorizado as limitecredito,
		cc.id, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		CONCAT(sc.calle,' #',sc.numero,' ',sc.colonia,' ',sc.poblacion) AS direccion,
		IFNULL(pg.consumido,0) AS consumido,IFNULL(pgs.vencido,0) AS montovencido,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS vendedorasigando
		FROM solicitudcredito sc
		INNER JOIN catalogocliente cc ON sc.cliente = cc.id
		INNER JOIN catalogoempleado ce ON sc.idusuario = ce.id
		INNER JOIN (SELECT cliente, IFNULL(SUM(total),0) AS consumido FROM pagoguias
		WHERE pagado='S' GROUP BY cliente) pg ON cc.id = pg.cliente
		LEFT JOIN (SELECT pg.cliente, IFNULL(SUM(total),0) AS vencido FROM pagoguias pg
		INNER JOIN solicitudcredito sc ON pg.cliente = sc.cliente
		WHERE DATEDIFF(fechacreo,CURDATE()) > sc.diascredito GROUP BY pg.cliente) pgs ON cc.id = pgs.cliente
		WHERE ".(($_SESSION[IDSUCURSAL]!=1)? " cc.sucursal = ".$_SESSION[IDSUCURSAL]." AND ":"")."
		sc.estado='ACTIVADO' AND cc.activado='SI'
		LIMIT ".$_GET[inicio].",30";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$s = "SELECT limitecredito * ".$c->porcentaje."/100.00 AS limite FROM catalogocliente
			WHERE id = ".$f->id."";
			$cl = mysql_query($s,$l) or die($s);
			$cli = mysql_fetch_object($cl);
			
			$s = "SELECT $f->limitecredito - IFNULL(SUM(IF(pagado='N', total,0)),0) AS disponible
			FROM pagoguias WHERE cliente = '".$f->id."'";
			$rx = mysql_query($s,$l) or die($s);
			$fx = mysql_fetch_object($rx);			
			if($fx->disponible <= $cli->limite){
				$f->cliente = cambio_texto($f->cliente);
				$f->direccion = cambio_texto($f->direccion);
				$f->limitecredito = $cli->limite;
				$registros[] = $f;
			}
		}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==23){//HISTORIAL GUIAS EN TRANSITO
		$s = "SELECT * FROM 
		(SELECT gv.id, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, cs.descripcion AS destino, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(gv.total,0) AS total, IF(gv.ocurre=1,'OCURRE','EAD') AS tipoentrega
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.estado = 'EN TRANSITO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, cs.descripcion AS destino, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(ge.total,0) AS total, IF(ge.ocurre=1,'OCURRE','EAD') AS tipoentrega
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.estado = 'EN TRANSITO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") t
		LIMIT ".$_GET[inicio].",30";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$f->destino = cambio_texto($f->destino);
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->tipoentrega = cambio_texto($f->tipoentrega);
			$registros[] = $f;
		}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==24){
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		CONCAT(dir.calle,' #',dir.numero,' COL.',dir.colonia) AS direccion,
		IFNULL(gv.sector,0) AS sector, IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete,
		gv.total AS importe, IF(gv.ocurre=0,'EAD','OCURRE') AS entrega 
		FROM guiasventanilla gv
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN direccion dir ON gv.iddirecciondestinatario = dir.id
		WHERE gv.estado = 'ALMACEN DESTINO' AND re.tipocliente=2 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		LIMIT ".$_GET[inicio].",30";		
		$r = mysql_query($s,$l) or die($s);
		$registros = array();		
			while($f = mysql_fetch_object($r)){
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->direccion = cambio_texto($f->direccion);
				$regitros[] = $f;
			}
		$datos = str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==25){//GUIAS PENDIENTES DE FACTURAR
		$s = "SELECT * FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE (gv.factura IS NULL OR gv.factura=0) 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND pg.sucursalacobrar = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS remitente,
		ori.prefijo AS origen, des.prefijo AS destino, gv.total AS importe
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE (gv.factura IS NULL OR gv.factura=0) 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND pg.sucursalacobrar = ".$_SESSION[IDSUCURSAL]."":"")."
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->cliente);
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
		
	}else if($_GET[accion]==26){//Cobranza 60 dias	
		$s = "SELECT * FROM cobranza60dias_tmp		
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT factura, date_format(fechaemision,'%d/%m/%Y') as fechaemision,
		cliente, nombre, importe, tipofactura, date_format(fechavencimiento,'%d/%m/%Y') as fechavencimiento,
		diasatraso, contrarecibo, diapago FROM cobranza60dias_tmp
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->nombre);
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
			
	}else if($_GET[accion] == 27){
		$s = "SELECT DISTINCT r.folio, ".(($_SESSION[IDSUCURSAL]==1)? "cs.prefijo AS sucursal," :"")."
		DATE_FORMAT(r.fecharegistro,'%d/%m/%Y')AS fecharegistro, rd.contenido, rd.descripcion,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' Col. ',r.colonia,', ',r.poblacion) AS direccion,
		d.descripcion AS destino FROM recoleccion r
		INNER JOIN recolecciondetalle rd ON r.folio = rd.recoleccion
		INNER JOIN catalogocliente cc ON r.cliente = cc.id
		INNER JOIN catalogodestino d ON r.destino = d.id
		INNER JOIN catalogosucursal cs ON r.sucursal = cs.id
		WHERE r.fecharegistro = CURDATE() AND ".(($_SESSION[IDSUCURSAL]!=1)? " r.sucursal = $_SESSION[IDSUCURSAL] AND " :"")."
		r.realizo = 'NO' AND r.estado<>'CANCELADO'
		GROUP BY r.folio, r.sucursal";
		if($_GET[tipo]==0){
			$r = mysql_query($s, $l) or die($s);
			echo mysql_num_rows($r);
		}else if($_GET[tipo]==1){
			$registros = array();
			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30", $l) or die($s);
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}
	}else if($_GET[accion] == "obtenerUltimoReprogramar"){
		$s = "SELECT COUNT(*) AS total FROM recoleccion		
		WHERE fecharegistro = CURDATE() AND ".(($_SESSION[IDSUCURSAL]!=1)? " sucursal = $_SESSION[IDSUCURSAL] AND " :"")."
		realizo = 'NO' AND estado<>'CANCELADO'";
		
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;
	
		$s = "SELECT DISTINCT r.folio, ".(($_SESSION[IDSUCURSAL]==1)? "cs.prefijo AS sucursal," :"")."
		DATE_FORMAT(r.fecharegistro,'%d/%m/%Y')AS fecharegistro, rd.contenido, rd.descripcion,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' Col. ',r.colonia,', ',r.poblacion) AS direccion,
		d.descripcion AS destino FROM recoleccion r
		INNER JOIN recolecciondetalle rd ON r.folio = rd.recoleccion
		INNER JOIN catalogocliente cc ON r.cliente = cc.id
		INNER JOIN catalogodestino d ON r.destino = d.id
		INNER JOIN catalogosucursal cs ON r.sucursal = cs.id
		WHERE r.fecharegistro = CURDATE() AND ".(($_SESSION[IDSUCURSAL]!=1)? " r.sucursal = $_SESSION[IDSUCURSAL] AND " :"")."
		r.realizo = 'NO' AND r.estado<>'CANCELADO'
		GROUP BY r.folio LIMIT ".$limit."";
		$registros = array();
		$r = mysql_query($s." LIMIT ".$_GET[inicio].",30", $l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->cliente = cambio_texto($f->cliente);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==28){
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, o.descripcion AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total,
		IF(gv.tipoflete=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.idremitente = de.id
		WHERE gv.estado = 'ALMACEN ORIGEN' AND gv.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, o.descripcion AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IF(ge.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, ge.total,
		IF(ge.tipoflete=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN 