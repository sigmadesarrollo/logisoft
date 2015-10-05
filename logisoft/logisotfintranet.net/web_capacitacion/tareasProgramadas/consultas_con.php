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

		if($_GET[accion]==1){ // guiasporrecibir.php
			$s = "(SELECT gv.id AS guia, IFNULL(gu.unidad,'') AS unidad, '' AS ruta, gv.estado,
			DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fechaemision,
			gv.idsucursalorigen ,cs.prefijo AS sucursalorigen,
			gv.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gv.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gv.total AS importetotal,
			IF(gv.ocurre=1,'OCURRE','EAD') AS tipodeentrega,
			IF(gv.ocurre=1,DATE_FORMAT(DATE_ADD(gv.fecha,INTERVAL gv.entregaocurre DAY_HOUR),'%d/%m/%Y'),
			IF(gv.ocurre=0,DATE_FORMAT(DATE_ADD(gv.fecha,INTERVAL gv.entregaead DAY_HOUR),'%d/%m/%Y'),'')) AS fechallegada  
			FROM guiasventanilla gv
			INNER JOIN catalogocliente csr ON csr.id=gv.idremitente
			INNER JOIN catalogocliente csd ON csd.id=gv.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gv.idsucursalorigen
			INNER JOIN guiaventanilla_unidades gu ON gv.id = gu.idguia			
			WHERE gv.estado NOT IN('ALMACEN DESTINO','CANCELADO','ENTREGADA','AUTORIZACION PARA CANCELAR',
			'EN REPARTO EAD','POR ENTREGAR','AUTORIZACION PARA SUSTITUIR')
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."
			GROUP BY gv.id)
			UNION
			(SELECT gm.id AS guia, IFNULL(gu.unidad,'') AS unidad, '' AS ruta, gm.estado,
			DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fechaemision,
			gm.idsucursalorigen ,cs.prefijo AS sucursalorigen,
			gm.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gm.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gm.total AS importetotal,
			IF(gm.ocurre=1,'OCURRE','EAD') AS tipodeentrega,
			IF(gm.ocurre=1,DATE_FORMAT(DATE_ADD(gm.fecha,INTERVAL gm.entregaocurre DAY_HOUR),'%d/%m/%Y'),
			IF(gm.ocurre=0,DATE_FORMAT(DATE_ADD(gm.fecha,INTERVAL gm.entregaead DAY_HOUR),'%d/%m/%Y'),'')) AS fechallegada 
			FROM guiasempresariales gm			
			INNER JOIN catalogocliente csr ON csr.id=gm.idremitente
			INNER JOIN catalogocliente csd ON csd.id=gm.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gm.idsucursalorigen
			INNER JOIN guiasempresariales_unidades gu ON gm.id = gu.idguia
			WHERE gm.estado NOT IN('ALMACEN DESTINO','CANCELADO','ENTREGADA','AUTORIZACION PARA CANCELAR',
			'EN REPARTO EAD','POR ENTREGAR','AUTORIZACION PARA SUSTITUIR')
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gm.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."
			GROUP BY gm.id)";
			$r = mysql_query($s,$l) or die($s);
			$totalregistros = mysql_num_rows($r);		
		
			$totales = 0;
			
			$s = "SELECT * FROM(
			(SELECT gv.id AS guia, IFNULL(gu.unidad,'') AS unidad, '' AS ruta, gv.estado,
			DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fechaemision,
			gv.idsucursalorigen ,cs.prefijo AS sucursalorigen,
			gv.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gv.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gv.total AS importetotal,
			IF(gv.ocurre=1,'OCURRE','EAD') AS tipodeentrega,
			IF(gv.ocurre=1,DATE_FORMAT(DATE_ADD(gv.fecha,INTERVAL gv.entregaocurre DAY_HOUR),'%d/%m/%Y'),
			IF(gv.ocurre=0,DATE_FORMAT(DATE_ADD(gv.fecha,INTERVAL gv.entregaead DAY_HOUR),'%d/%m/%Y'),'')) AS fechallegada  
			FROM guiasventanilla gv
			INNER JOIN catalogocliente csr ON csr.id=gv.idremitente
			INNER JOIN catalogocliente csd ON csd.id=gv.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gv.idsucursalorigen
			INNER JOIN guiaventanilla_unidades gu ON gv.id = gu.idguia			
			WHERE gv.estado NOT IN('ALMACEN DESTINO','CANCELADO','ENTREGADA','AUTORIZACION PARA CANCELAR',
			'EN REPARTO EAD','POR ENTREGAR','AUTORIZACION PARA SUSTITUIR')
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."
			GROUP BY gv.id)
			UNION
			(SELECT gm.id AS guia, IFNULL(gu.unidad,'') AS unidad, '' AS ruta, gm.estado,
			DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fechaemision,
			gm.idsucursalorigen ,cs.prefijo AS sucursalorigen,
			gm.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gm.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gm.total AS importetotal,
			IF(gm.ocurre=1,'OCURRE','EAD') AS tipodeentrega,
			IF(gm.ocurre=1,DATE_FORMAT(DATE_ADD(gm.fecha,INTERVAL gm.entregaocurre DAY_HOUR),'%d/%m/%Y'),
			IF(gm.ocurre=0,DATE_FORMAT(DATE_ADD(gm.fecha,INTERVAL gm.entregaead DAY_HOUR),'%d/%m/%Y'),'')) AS fechallegada 
			FROM guiasempresariales gm			
			INNER JOIN catalogocliente csr ON csr.id=gm.idremitente
			INNER JOIN catalogocliente csd ON csd.id=gm.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gm.idsucursalorigen
			INNER JOIN guiasempresariales_unidades gu ON gm.id = gu.idguia
			WHERE gm.estado NOT IN('ALMACEN DESTINO','CANCELADO','ENTREGADA','AUTORIZACION PARA CANCELAR',
			'EN REPARTO EAD','POR ENTREGAR','AUTORIZACION PARA SUSTITUIR')
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gm.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."
			GROUP BY gm.id))t $limite";
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->guia = cambio_texto($f->guia);
				$f->sucursaldestino = cambio_texto($f->sucursaldestino);
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
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
			
		}else if($_GET[accion]==2){ //guiasparaentregasocurre.php
			$s = "SELECT id FROM guiasventanilla gv
			WHERE estado<>'ENTREGADA' AND estado<>'CANCELADO' AND ocurre=1
			".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."
			UNION
			SELECT id FROM guiasempresariales gm
			WHERE estado<>'ENTREGADA' AND ocurre=1
			".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."";
			$r = mysql_query($s,$l) or die($s);
			$totalregistros = mysql_num_rows($r);		
		
			$totales = 0;
			
			$s = "SELECT * FROM(
			(SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fechaemision,
			gv.idsucursaldestino ,cs.prefijo AS sucursaldestino,
			gv.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gv.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gv.total AS importetotal
			FROM guiasventanilla gv
			LEFT  JOIN catalogocliente csr ON gv.idremitente=csr.id
			LEFT  JOIN catalogocliente csd ON gv.iddestinatario=csd.id
			INNER JOIN catalogosucursal cs ON gv.idsucursaldestino=cs.id
			WHERE gv.estado<>'ENTREGADA' AND gv.estado<>'CANCELADO' AND gv.ocurre=1 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"").")
			UNION
			(SELECT gm.id AS guia,DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fechaemision,
			gm.idsucursaldestino ,cs.prefijo AS sucursaldestino,
			gm.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gm.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gm.total AS importetotal
			FROM guiasempresariales gm
			LEFT  JOIN catalogocliente csr ON gm.idremitente=csr.id
			LEFT  JOIN catalogocliente csd ON gm.iddestinatario=csd.id
			INNER JOIN catalogosucursal cs ON gm.idsucursaldestino=cs.id
			WHERE gm.estado<>'ENTREGADA' AND gm.ocurre=1 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gm.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."))t $limite";
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->guia = cambio_texto($f->guia);
				$f->sucursaldestino = cambio_texto($f->sucursaldestino);
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
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
			
		}else if($_GET[accion]==3){ //guiasparaentregasadomicilio.php
			$s = "SELECT id FROM guiasventanilla gv
			WHERE estado<>'ENTREGADA' AND ocurre=0
			".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."
			UNION
			SELECT id FROM guiasempresariales gm
			WHERE estado<>'ENTREGADA' AND ocurre=0
			".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."";
			$r = mysql_query($s,$l) or die($s);
			$totalregistros = mysql_num_rows($r);		
		
			$totales = 0;
			
			$s = "SELECT * FROM (
			(SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fechaemision,
			gv.idsucursaldestino ,cs.prefijo AS sucursaldestino,
			gv.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gv.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gv.iddirecciondestinatario,CONCAT_WS(' ',d.calle,'#',d.numero,'CP',d.cp,d.municipio,d.estado) AS direcciondest,
			gv.sector, IF(gv.tipoflete=0,'PAGADA','POR COBRAR') AS tipoflete, gv.total AS importetotal
			FROM guiasventanilla gv
			INNER JOIN catalogocliente csr ON csr.id=gv.idremitente
			INNER JOIN catalogocliente csd ON csd.id=gv.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gv.idsucursaldestino
			INNER JOIN direccion d ON gv.iddirecciondestinatario=d.id
			WHERE gv.estado<>'ENTREGADA' AND gv.ocurre=0 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"").")
			UNION
			(SELECT gm.id AS guia,DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fechaemision,
			gm.idsucursaldestino ,cs.prefijo AS sucursaldestino,
			gm.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gm.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gm.iddirecciondestinatario,CONCAT_WS(' ',d.calle,'#',d.numero,'CP',d.cp,d.municipio,d.estado) AS direcciondest,
			gm.sector, gm.tipoflete AS tipoflete, gm.total AS importetotal
			FROM guiasempresariales gm
			INNER JOIN catalogocliente csr ON gm.idremitente=csr.id
			INNER JOIN catalogocliente csd ON gm.iddestinatario=csd.id
			INNER JOIN catalogosucursal cs ON gm.idsucursaldestino=cs.id
			INNER JOIN direccion d ON gm.iddirecciondestinatario=d.id
			WHERE  gm.estado<>'ENTREGADA' AND gm.ocurre=0 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gm.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."))t
			$limite";
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->guia = cambio_texto($f->guia);
				$f->sucursaldestino = cambio_texto($f->sucursaldestino);
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->direcciondest = cambio_texto($f->direcciondest);
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
			
		}else if($_GET[accion]==4){ //entregas.php
			$s = "(SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fechaemision,
			gv.idsucursaldestino ,cs.prefijo AS sucursaldestino,
			gv.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gv.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gv.iddirecciondestinatario,CONCAT_WS(' ',d.calle,'#',d.numero,'CP',d.cp,d.municipio,d.estado) AS direcciondest,
			gv.sector,
			IF(gv.tipoflete=0,'PAGADA','POR COBRAR') AS tipoflete ,
			gv.total AS importetotal
			FROM guiasventanilla gv
			LEFT  JOIN catalogocliente csr ON csr.id=gv.idremitente
			LEFT  JOIN catalogocliente csd ON csd.id=gv.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gv.idsucursaldestino
			INNER JOIN direccion d ON gv.iddirecciondestinatario=d.id
			WHERE gv.estado='ALMACEN DESTINO' AND gv.ocurre=0 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"").")
			UNION
			(SELECT gm.id AS guia,DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fechaemision,
			gm.idsucursaldestino ,cs.prefijo AS sucursaldestino,
			gm.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gm.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gm.iddirecciondestinatario,CONCAT_WS(' ',d.calle,'#',d.numero,'CP',d.cp,d.municipio,d.estado) AS direcciondest,
			gm.sector,
			gm.tipoflete AS tipoflete ,
			gm.total AS importetotal
			FROM guiasempresariales gm
			LEFT  JOIN catalogocliente csr ON gm.idremitente=csr.id
			LEFT  JOIN catalogocliente csd ON gm.iddestinatario=csd.id
			INNER JOIN catalogosucursal cs ON gm.idsucursaldestino=cs.id
			INNER JOIN direccion d ON gm.iddirecciondestinatario=d.id
			WHERE  gm.estado='ALMACEN DESTINO' AND gm.ocurre=0 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gm.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"").")";
			$r = mysql_query($s, $l) or die($s);
			$registros = array();
			while($f = mysql_fetch_object($r)){
				$registros[]=$f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else if($_GET[accion]==5){  //guiasporembarcar.php
			$s = "(SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fechaemision,
			gv.idsucursaldestino ,cs.prefijo AS sucursaldestino,
			gv.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gv.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gv.total AS importetotal,
			IF(gv.ocurre=1,'OCURRE','EAD') AS tipodeentrega  
			FROM guiasventanilla gv
			LEFT  JOIN catalogocliente csr ON csr.id=gv.idremitente
			LEFT  JOIN catalogocliente csd ON csd.id=gv.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gv.idsucursaldestino
			WHERE gv.estado='ALMACEN ORIGEN' 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen='".$_SESSION[IDSUCURSAL]."'":"").")
			UNION
			(SELECT gm.id AS guia,DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fechaemision,
			gm.idsucursaldestino,cs.prefijo AS sucursaldestino,
			gm.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
			gm.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
			gm.total AS importetotal,
			IF(gm.ocurre=1,'OCURRE','EAD') AS tipodeentrega  
			FROM guiasempresariales gm
			LEFT  JOIN catalogocliente csr ON csr.id=gm.idremitente
			LEFT  JOIN catalogocliente csd ON csd.id=gm.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gm.idsucursaldestino
			WHERE gm.estado='ALMACEN ORIGEN' 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen='".$_SESSION[IDSUCURSAL]."'":"").")";
			$r = mysql_query($s, $l) or die($s);
			$registros = array();
			while($f = mysql_fetch_object($r)){
				$registros[]=$f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else if($_GET[accion]==6){//recoleccionesprogramadas.php
			$s = "SELECT r.folio AS norecoleccion,DATE_FORMAT(r.fecharegistro,'%d/%m/%Y') AS fechasolicitud ,
			rd.contenido AS contenido,
			rd.descripcion AS descripcion,
			CONCAT(c.nombre,' ',c.paterno,' ',c.materno) AS cliente,
			CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion,
			r.destino,cs.descripcion AS destino
			FROM recoleccion r
			INNER JOIN recolecciondetalle rd ON r.folio=rd.recoleccion
			INNER JOIN catalogocliente c ON r.cliente = c.id
			INNER JOIN catalogosucursal cs ON r.destino=cs.id
			WHERE r.fecharegistro<=CURRENT_DATE() AND r.estado<>'REALIZADO' AND r.origen='".$_SESSION[IDSUCURSAL]."'";
			$r = mysql_query($s, $l) or die($s);
			$registros = array();
			while($f = mysql_fetch_object($r)){
				$registros[]=$f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else if($_GET[accion]==7){// guiasportrasbordar.php
				$s = "(SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fechaemision,
				gv.idsucursaldestino ,cs.prefijo AS sucursaldestino,cso.prefijo AS sucursalorigen,
				gv.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
				gv.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
				gv.total AS importetotal,
				IF(gv.ocurre=1,'OCURRE','EAD') AS tipodeentrega  
				FROM guiasventanilla gv
				INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
				LEFT  JOIN catalogocliente csr ON csr.id=gv.idremitente
				LEFT  JOIN catalogocliente csd ON csd.id=gv.iddestinatario
				INNER JOIN catalogosucursal cs ON cs.id=gv.idsucursaldestino
				INNER JOIN catalogosucursal cso ON cso.id=gv.idsucursalorigen
				WHERE gv.estado='ALMACEN TRASBORDO' 
				".(($_SESSION[IDSUCURSAL]!=1)? " AND gvu.ubicacion='".$_SESSION[IDSUCURSAL]."'":"")." GROUP BY gv.id)
				UNION
				(SELECT gm.id AS guia,DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fechaemision,
				gm.idsucursaldestino,cs.prefijo AS sucursaldestino,cso.prefijo AS sucursalorigen,
				gm.idremitente,CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) AS remitente,
				gm.iddestinatario,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) AS destinatario,
				gm.total AS importetotal,
				IF(gm.ocurre=1,'OCURRE','EAD') AS tipodeentrega  
				FROM guiasempresariales gm
				INNER JOIN guiasempresariales_unidades geu ON gm.id = geu.idguia
				LEFT  JOIN catalogocliente csr ON csr.id=gm.idremitente
				LEFT  JOIN catalogocliente csd ON csd.id=gm.iddestinatario
				INNER JOIN catalogosucursal cs ON cs.id=gm.idsucursaldestino
				INNER JOIN catalogosucursal cso ON cso.id=gm.idsucursalorigen
				WHERE gm.estado='ALMACEN TRASBORDO' 
				".(($_SESSION[IDSUCURSAL]!=1)? " AND geu.ubicacion='".$_SESSION[IDSUCURSAL]."'":"")." GROUP BY gm.id)";
				$r = mysql_query($s, $l) or die($s);
				$registros = array();
				while($f = mysql_fetch_object($r)){
					$registros[]=$f;
				}
				echo str_replace('null','""',json_encode($registros));
		
		}else if($_GET[accion]==8){ //recoleccion
			$s = "SELECT r.folio, r.sucursal, r.estado, r.horario, CONCAT(c.nombre,' ',c.paterno,' ',c.materno) AS cliente,
			 CONCAT(r.calle,' #',r.numero,' ',r.colonia) AS direccion, r.telefono,
			 DATE_FORMAT(r.fecharecoleccion,'%d/%m/%Y') AS fecha, r.unidad,
			 r.transmitida, r.realizo
			 FROM recoleccion r
			 INNER JOIN catalogocliente c ON r.cliente = c.id
			 WHERE r.fecharegistro <= 
			 CURRENT_DATE()
			 AND r.sucursal='".$_SESSION[IDSUCURSAL]."' 
			 ORDER BY r.fecharecoleccion ASC ";
			 //echo $s;
				
				$registros = array();
				$r = mysql_query($s,$l) or die($s);
				
					while($f = mysql_fetch_object($r)){
						
						$f->cliente = cambio_texto($f->cliente);
						$f->direccion = cambio_texto($f->direccion);					
						$f->colorcan = "";
						$f->colorrep = "";					
						$sc = mysql_query("SELECT r.motivo, m.descripcion AS desmotivo, m.color FROM recoleccionmotivocancelacion r
						INNER JOIN catalogomotivos m ON r.motivo = m.id
						WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_SESSION[IDSUCURSAL]." AND 
						r.fecharegistro<=CURRENT_DATE()",$l) or die(mysql_error($l).$sc);
						$can = mysql_fetch_object($sc);
						$f->colorcan = $can->color;
						$f->motivos = "";
						if($f->estado=="CANCELADO"){
							$f->motivos = cambio_texto($can->desmotivo);
						}
						$sr = mysql_query("SELECT r.motivo, m.descripcion AS desmotivoreprogramar,
						m.color FROM recoleccionmotivoreprogramacion r
						INNER JOIN catalogomotivos m ON r.motivo = m.id
						WHERE r.recoleccion='".$f->folio."' AND r.sucursal=".$_SESSION[IDSUCURSAL]." AND 
						r.fecharegistro<=CURRENT_DATE()",$l);
	
						$rep = mysql_fetch_object($sr);
						$f->colorrep = $rep->color;
	
						if($rep->desmotivoreprogramar!=""){
							$f->motivos = cambio_texto($rep->desmotivoreprogramar);
						}
	
						if($rep->desmotivoreprogramar=="" && $f->estado!="CANCELADO"){
							$f->motivos = cambio_texto($f->motivos);
						}
	
						if($f->estado=="REALIZADO"){
						$sr = mysql_query("SELECT foliosrecolecciones FROM recolecciondetallefoliorecoleccion
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_SESSION[IDSUCURSAL]."",$l) or die($sr);
						$recolecciones = ""; $empresariales = ""; 
	
						if(mysql_num_rows($sr)>0){
							while($row=mysql_fetch_array($sr)){
								$recolecciones .=$row[0].",";
							}
							$recolecciones = substr($recolecciones,0,strlen($recolecciones)-1);
						}
	
	
						$se = mysql_query("SELECT foliosempresariales FROM recolecciondetallefolioempresariales
						WHERE recoleccion='".$f->folio."' AND sucursal=".$_GET['sucursal']."",$l) or die($se);
	
						if(mysql_num_rows($se)>0){
							while($rowd=mysql_fetch_array($se)){
								$empresariales .=$rowd[0].",";
							}
							$empresariales = substr($empresariales,0,strlen($empresariales)-1);
						}
	
						if($recolecciones!="" && $empresariales!=""){
							$f->folios = $recolecciones."--".$empresariales;	
						}
	
						}					
						$f->folios = cambio_texto($f->folios);
						$registros[] = $f;
	
					}
				echo str_replace("null",'""',json_encode($registros));
						
		}else if($_GET[accion]==9){// facturasarevision.php
			$s = "SELECT CONCAT_WS(' ',f.nombrecliente,f.apellidopaternocliente, f.apellidomaternocliente) AS cliente,
			CONCAT(f.calle,' #',f.numero,' COL. ',f.colonia) AS direccion, IFNULL(sc.diascredito,0) AS diasdecredito, 
			(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS importe FROM facturacion f
			INNER JOIN catalogocliente cc ON f.cliente = cc.id
			INNER JOIN solicitudcredito sc ON cc.id = sc.cliente
			WHERE f.estadocobranza = 'N' AND f.idsucursal=".$_SESSION[IDSUCURSAL]." AND 
						(CASE DAYOFWEEK(CURRENT_DATE)
							WHEN 2 THEN sc.lunesrevision=1
							WHEN 3 THEN sc.martesrevision=1
							WHEN 4 THEN sc.miercolesrevision=1
							WHEN 5 THEN sc.juevesrevision=1
							WHEN 6 THEN sc.viernesrevision=1
							WHEN 7 THEN sc.sabadorevision=1
						END OR sc.semanarevision = 1)";
			$r = mysql_query($s,$l) or die($s);
			$totalregistros = mysql_num_rows($r);		
		
			$totales = 0;
			
			$s = "SELECT CONCAT_WS(' ',f.nombrecliente,f.apellidopaternocliente, f.apellidomaternocliente) AS cliente,
			UCASE(CONCAT(f.calle,' #',f.numero,' COL. ',f.colonia)) AS direccion, IFNULL(sc.diascredito,0) AS diasdecredito, 
			(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS importe, f.folio as factura, date_format(f.fecha,'%d/%m/%Y') as fecha FROM facturacion f
			INNER JOIN catalogocliente cc ON f.cliente = cc.id
			INNER JOIN solicitudcredito sc ON cc.id = sc.cliente
			WHERE f.estadocobranza = 'N' AND f.idsucursal=".$_SESSION[IDSUCURSAL]." AND 
						(CASE DAYOFWEEK(CURRENT_DATE)
							WHEN 2 THEN sc.lunesrevision=1
							WHEN 3 THEN sc.martesrevision=1
							WHEN 4 THEN sc.miercolesrevision=1
							WHEN 5 THEN sc.juevesrevision=1
							WHEN 6 THEN sc.viernesrevision=1
							WHEN 7 THEN sc.sabadorevision=1
						END OR sc.semanarevision = 1) $limite";
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->guia = cambio_texto($f->guia);
				$f->sucursaldestino = cambio_texto($f->sucursaldestino);
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->direcciondest = cambio_texto($f->direcciondest);
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
			
		}else if($_GET[accion]==10){//FACTURACION
			$s = "SELECT origen, guia, cliente, destino, fecha, tipoguia, estado FROM 
			(SELECT cs.prefijo AS origen, gv.id AS guia, de.prefijo AS destino,
			DATE_FORMAT(gv.fecha_registro,'%d/%m/%Y') AS fecha,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
			'NORMAL' AS tipoguia, gv.estado
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			INNER JOIN catalogosucursal de ON gv.idsucursaldestino = de.id
			".(($_SESSION[IDSUCURSAL]!=1)? "INNER JOIN catalogocliente cc ON 
			IF(gv.idsucursalorigen<>'".$_SESSION[IDSUCURSAL]."',gv.iddestinatario,gv.idremitente)=cc.id" :"")."			
			UNION
			SELECT cs.prefijo AS origen, ge.id AS guia, de.prefijo AS destino,
			DATE_FORMAT(ge.fecha_registro,'%d/%m/%Y') AS fecha,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
			ge.tipoguia, ge.estado
			FROM guiasempresariales ge
			INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
			INNER JOIN catalogosucursal de ON ge.idsucursaldestino = de.id
			".(($_SESSION[IDSUCURSAL]!=1)? "INNER JOIN catalogocliente cc ON 
			IF(gv.idsucursalorigen<>'".$_SESSION[IDSUCURSAL]."',gv.iddestinatario,gv.idremitente)=cc.id" :"").") t";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($f));
			}else{
				echo "0";
			}
							
		}else if($_GET[accion]==11){ //solicitudescat.php
				$s = "SELECT st.folio, DATE_FORMAT(st.fechaqueja,'%d/%m/%Y') AS fecha,
				cs.descripcion AS sucursal, st.queja, st.observaciones,
				DATE_FORMAT(st.fechaposible,'%d/%m/%Y') AS solucion,
				DATE_FORMAT(st.fechaposible,'%m/%d/%Y') AS comparar,
				st.observacionesposible AS comentarios,
				CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
				CASE st.queja WHEN 'RECOLECCION' THEN st.folioatencion
				WHEN 'EAD MAL EFECTUADAS' THEN IF(st.guia IS NULL,st.recoleccion,st.guia)
				WHEN 'CONVENIOS NO APLICADOS' THEN st.guia
				WHEN 'OTROS SERVICIOS' THEN '' END AS foliodoc
				FROM solicitudtelefonica st
				INNER JOIN catalogosucursal cs ON st.sucursal = cs.id
				INNER JOIN catalogoempleado ce ON st.responsable=ce.id
				LEFT JOIN recoleccion r ON st.folioatencion = r.folio AND r.sucursal
				LEFT JOIN (SELECT id AS guia, estado FROM guiasventanilla 
				UNION
				SELECT id AS guia, estado FROM guiasempresariales) g ON st.guia=g.guia
				WHERE st.estado='POR SOLUCIONAR' AND (ISNULL(r.realizo) OR r.realizo='NO')
				AND (ISNULL(g.estado) OR g.estado <>'ENTREGADA')";
				$r = mysql_query($s,$l) or die($s);
				$registros = array();
				while($f = mysql_fetch_object($r)){
					$f->solucion = (($f->solucion=="00/00/0000")? "" : $f->solucion);
					$f->sucursal = cambio_texto($f->sucursal);
					$f->observaciones = cambio_texto($f->observaciones);
					$registros[] = $f;
				}
				
				echo str_replace('null','""',json_encode($registros));
		
		}else if($_GET[accion]==12){// liquidaciondecobranza.php
			$s = "SELECT lqc.folio,lqcd.cliente,lqcd.guia,DATE_FORMAT(lqcd.fechaguia,'%d/%m/%Y') AS fecha,
			DATE_FORMAT(lqcd.fechavencimiento,'%d/%m/%Y') AS fechavencimiento,
			lqcd.factura,lqcd.importe, lqcd.saldoactual, lqcd.revision, lqcd.cobrar, 
			IF(lqcd.contrarecibo=0,'',lqcd.contrarecibo) AS contrarecibo,
			IF(lqcd.compromiso='0000-00-00','',lqcd.compromiso) AS compromiso
			FROM liquidacioncobranza lqc
			INNER JOIN liquidacioncobranzadetalle lqcd ON lqcd.folioliquidacion=lqc.folio";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;		
				}
			echo str_replace('null','""',json_encode($registros));		
		
		}else if($_GET[accion]==13){// liquidacionesead.php
			$s = "(SELECT re.folio folioead, rd.guia, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno) cliente, gv.fecha, 
			IFNULL(gv.factura,'') factura, gv.total
			FROM repartomercanciaead re
			INNER JOIN repartomercanciadetalle rd ON re.folio = rd.idreparto AND rd.sucursal = re.sucursal
			INNER JOIN guiasventanilla gv ON rd.guia = gv.id
			INNER JOIN catalogocliente cc ON IF(gv.tipoflete=1,gv.iddestinatario, gv.idremitente) = cc.id
			WHERE liquidado = 0 ".(($_SESSION[IDSUCURSAL]!=1)?" AND re.sucursal = ".$_SESSION[IDSUCURSAL]."":"").")
			UNION
			(SELECT re.folio folioead, rd.guia, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno) cliente, ge.fecha, 
			IFNULL(ge.factura,'') factura, ge.total
			FROM repartomercanciaead re
			INNER JOIN repartomercanciadetalle rd ON re.folio = rd.idreparto AND rd.sucursal = re.sucursal
			INNER JOIN guiasempresariales ge ON rd.guia = ge.id
			INNER JOIN catalogocliente cc ON IF(ge.tipoflete='POR COBRAR',ge.iddestinatario, ge.idremitente) = cc.id
			WHERE liquidado = 0 ".(($_SESSION[IDSUCURSAL]!=1)?" AND re.sucursal = ".$_SESSION[IDSUCURSAL]."":"").")";
			$r = mysql_query($s,$l) or die($s);
			$totalregistros = mysql_num_rows($r);		
		
			$totales = 0;			
			
			/* antes estaba asi
			$s = "SELECT folio AS factura, DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, CONCAT_WS(' ',nombrecliente,apellidopaternocliente,apellidomaternocliente) AS cliente,
			(total + sobmontoafacturar + otrosmontofacturar) AS importe
			FROM facturacion 
			WHERE estadocobranza = 'C' AND fecha = CURDATE() $limite";*/
			$s = "select t1.* from (
			(SELECT re.folio folioead, rd.guia, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno) cliente, date_format(gv.fecha, '%d/%m/%Y') fecha, 
			IFNULL(gv.factura,'') factura, gv.total
			FROM repartomercanciaead re
			INNER JOIN repartomercanciadetalle rd ON re.folio = rd.idreparto AND rd.sucursal = re.sucursal
			INNER JOIN guiasventanilla gv ON rd.guia = gv.id
			INNER JOIN catalogocliente cc ON IF(gv.tipoflete=1,gv.iddestinatario, gv.idremitente) = cc.id
			WHERE liquidado = 0 ".(($_SESSION[IDSUCURSAL]!=1)?" AND re.sucursal = ".$_SESSION[IDSUCURSAL]."":"")." )
			UNION
			(SELECT re.folio folioead, rd.guia, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno) cliente, date_format(ge.fecha, '%d/%m/%Y') fecha, 
			IFNULL(ge.factura,'') factura, ge.total
			FROM repartomercanciaead re
			INNER JOIN repartomercanciadetalle rd ON re.folio = rd.idreparto AND rd.sucursal = re.sucursal
			INNER JOIN guiasempresariales ge ON rd.guia = ge.id
			INNER JOIN catalogocliente cc ON IF(ge.tipoflete='POR COBRAR',ge.iddestinatario, ge.idremitente) = cc.id
			WHERE liquidado = 0 ".(($_SESSION[IDSUCURSAL]!=1)?" AND re.sucursal = ".$_SESSION[IDSUCURSAL]."":"").")
			) as t1
			$limite";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			while($f = mysql_fetch_object($r)){
				$f->cliente = cambio_texto($f->cliente);
				$registros[] = $f;		
			}
			
			$registros = str_replace('null','""',json_encode($registros));
			
			echo '({"total":"'.$totalregistros.'",
			"totales":'.$totales.',
			"registros":'.$registros.',
			"contador":"'.$contador.'",
			"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
			"atras":"'.f_atras($contador).'",
			"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		}
?>
