<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, cs.prefijo AS origen,
		cd.prefijo AS destino, gv.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal cd ON gv.idsucursaldestino = cd.id
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogocliente cde ON gv.iddestinatario = cde.id
		WHERE gv.id = '".$_GET[guia]."'
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, cs.prefijo AS origen, 
		cd.prefijo AS destino, ge.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasempresariales ge
		INNER JOIN guiasempresariales_unidades gvu ON ge.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal cd ON ge.idsucursaldestino = cd.id
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogocliente cde ON ge.iddestinatario = cde.id
		WHERE ge.id = '".$_GET[guia]."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->guia = utf8_encode($f->guia);
			$f->origen = utf8_encode($f->origen);
			$f->destino = utf8_encode($f->destino);
			$f->remitente = utf8_encode($f->remitente);
			$f->destinatario = utf8_encode($f->destinatario);
			
			$s = "SELECT REPLACE(GROUP_CONCAT(CONCAT(descripcion,' CON ',contenido)),'                            ','') contenidos
			FROM guiaventanilla_detalle WHERE idguia = '".$_GET[guia]."'";
			$rx = mysql_query($s,$l) or die($s);
			$fx = mysql_fetch_object($rx);
			$f->contenido = utf8_encode($fx->contenidos);
			$principal = str_replace('null','""',json_encode($f));
			
			
			
			$s = "SELECT b.unidad, cr.descripcion AS ruta,
			CONCAT_WS(' ',e1.nombre,e1.apellidopaterno,e1.apellidomaterno) AS empleado1,
			IFNULL(CONCAT_WS(' ',e2.nombre,e2.apellidopaterno,e2.apellidomaterno),'') AS empleado2
			FROM bitacorasalida b
			INNER JOIN catalogoruta cr ON b.ruta = cr.id
			INNER JOIN catalogoempleado e1 ON b.conductor1 = e1.id
			LEFT JOIN catalogoempleado e2 ON b.conductor2 = e2.id
			WHERE b.folio = ".$_GET[bitacora];
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$f->ruta = utf8_encode($f->ruta);
			$f->empleado1 = utf8_encode($f->empleado1);
			$f->empleado2 = utf8_encode($f->empleado2);			
			
			$s = "SELECT descripcion FROM catalogosucursal WHERE id =".$_SESSION[IDSUCURSAL];
			$r = mysql_query($s,$l) or die($s);
			$su = mysql_fetch_object($r);
			$f->sucursal = utf8_encode($su->descripcion);
			
			$bitacora = str_replace('null','""',json_encode($f));
			
			echo "({principal:$principal,bitacora:$bitacora})";
			
		}else{
			echo "noencontro";
		}
	
	}else if($_GET[accion]==2){
		$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado FROM catalogoempleado WHERE id = ".$_GET[empleado]."";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			echo str_replace('null','""',json_encode($f));
		}else{
			echo "noencontro";
		}
	}else if($_GET[accion]==3){
		$s = "INSERT INTO embarquedemercancia_faltante SET
		guia = UCASE('".$_GET[guia]."'), faltante = 1, empleado = ".$_GET[autorizo].", observaciones = UCASE('".$_GET[observaciones]."'),
		sucursal = ".$_SESSION[IDSUCURSAL].", bitacora = ".$_GET[bitacora].", idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reportedanosfaltante_detallado 
		SELECT NULL,'FALTANTE' AS tipo,t.enqueja,t.folioqueja,'$obj[0]' AS guia,t.estado,'$obj[5]' AS destinatario,
		t.destino,t.origen,CURRENT_DATE,t.recepcion, UCASE('".trim($obj[4])."') AS comentarios, 
		'EMBARQUE' AS segenero,".$_SESSION[IDSUCURSAL]."
		FROM (
		SELECT IF(m.nguia IS NOT NULL,'SI','NO') enqueja,IFNULL(m.folio,'') folioqueja,gv.estado,sd.prefijo AS destino,
		so.prefijo AS origen,em.folio AS recepcion
		FROM embarquedemercancia_faltante emf
		INNER JOIN guiasventanilla gv ON emf.guia=gv.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		LEFT JOIN embarquedemercancia em ON emf.embarque = em.folio
		LEFT JOIN moduloquejasdanosfaltantes m ON emf.guia = m.nguia
		WHERE emf.guia='$obj[0]' AND gv.id='$obj[0]'
		UNION
		SELECT IF(m.nguia IS NOT NULL,'SI','NO') enqueja,IFNULL(m.folio,'') folioqueja,ge.estado,sd.prefijo AS destino,
		so.prefijo AS origen,em.folio AS recepcion
		FROM embarquedemercancia_faltante emf
		INNER JOIN guiasempresariales ge ON emf.guia=ge.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id
		LEFT JOIN embarquedemercancia em ON emf.embarque = em.folio
		LEFT JOIN moduloquejasdanosfaltantes m ON emf.guia = m.nguia
		WHERE emf.guia='$obj[0]' AND ge.id='$obj[0]' )t";
		mysql_query($s,$l) or die($s);
		
		echo "ok,".mysql_insert_id($l);
	}
?>