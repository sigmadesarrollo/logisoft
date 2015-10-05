<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = "SELECT * FROM(
		SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, cs.prefijo AS origen,
		cd.prefijo AS destino, gv.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal cd ON gv.iddestino = cd.id
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogocliente cde ON gv.iddestinatario = cde.id
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, cs.prefijo AS origen, 
		cd.prefijo AS destino, ge.total AS importe,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS remitente,
		CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario
		FROM guiasempresariales ge
		INNER JOIN guiasempresariales_unidades gvu ON ge.id = gvu.idguia
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal cd ON ge.iddestino = cd.id
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogocliente cde ON ge.iddestinatario = cde.id) t
		WHERE guia = '".$_GET[guia]."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->guia = utf8_encode($f->guia);
			$f->origen = utf8_encode($f->origen);
			$f->destino = utf8_encode($f->destino);
			$f->remitente = utf8_encode($f->remitente);
			$f->destinatario = utf8_encode($f->destinatario);
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
		
		echo "ok";
	}
?>