<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT obtenerFolio('entregasespecialesead',".$_SESSION[IDSUCURSAL].") AS folio, 
		DATE_FORMAT(CURDATE(),'%d/%m/%Y') AS fecha,
		(SELECT descripcion FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL].") AS sucursal";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		echo $f->folio.",".$f->fecha.",".cambio_texto($f->sucursal);
		
	}else if($_GET[accion]==2){
		$s = "INSERT INTO entregasespecialesead SET
		folio = obtenerFolio('entregasespecialesead',".$_SESSION[IDSUCURSAL]."),
		sucursal = ".$_SESSION[IDSUCURSAL].",
		fechaespecial = CURDATE(),
		guia = '".(($_GET[documento]==0)? "$_GET[guia]" : "")."',
		rastreo = '".(($_GET[documento]==1)? "$_GET[guia]" : "")."',
		opcion2 = ".$_GET[opcion].",
		remitente = ".$_GET[remitente].",
		destinatario = ".$_GET[destinatario].",
		personarequireead = UCASE('".$_GET[persona]."'),
		telefono = '".$_GET[telefono]."',
		fechaead = '".cambiaf_a_mysql($_GET[fechaead])."',
		observaciones = UCASE('".$_GET[observaciones]."'),
		idusuario = ".$_SESSION[IDUSUARIO].",
		fecha = CURRENT_TIMESTAMP";
		mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
		
		$s = "SELECT folio FROM entregasespecialesead WHERE id = ".$folio."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		$s = "UPDATE lasalertas SET entesp=entesp+1 WHERE sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		echo "ok,".$f->folio;
	
	}else if($_GET[accion]==3){	
		$s = "UPDATE entregasespecialesead SET estado = 0 
		WHERE folio = ".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	
	}else if($_GET[accion]==4){	
		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre FROM catalogocliente WHERE id = ".$_GET[cliente]."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$f->nombre = cambio_texto($f->nombre);
		
		$principal = str_replace('null','""',json_encode($f));
		
		echo "({principal:$principal})";
	
	}else if($_GET[accion]==5){	
		$s = "SELECT DATE_FORMAT(e.fechaespecial,'%d/%m/%Y') AS fecha,
		e.guia,e.rastreo,e.opcion2,e.remitente,e.destinatario,e.personarequireead,e.telefono,
		DATE_FORMAT(e.fechaead,'%d/%m/%Y') AS fechaead,e.observaciones,e.estado,
		CONCAT_WS(' ',c.nombre,c.paterno,c.materno) AS nombreremitente,
		CONCAT_WS(' ',cd.nombre,cd.paterno,cd.materno) AS nombredestinatario,
		cs.descripcion AS sucursal
		FROM entregasespecialesead e
		INNER JOIN catalogocliente c ON e.remitente = c.id
		INNER JOIN catalogocliente cd ON e.destinatario = cd.id
		INNER JOIN catalogosucursal cs ON e.sucursal = cs.id
		WHERE e.folio = ".$_GET[folio]." AND e.sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$f->nombreremitente = cambio_texto($f->nombreremitente);
		$f->nombredestinatario = cambio_texto($f->nombredestinatario);
		$f->guia = cambio_texto($f->guia);
		$f->rastreo = cambio_texto($f->rastreo);
		$f->personarequireead = cambio_texto($f->personarequireead);
		$f->observaciones = cambio_texto($f->observaciones);
		
		$principal = str_replace('null','""',json_encode($f));
		
		echo "({principal:$principal})";
		
	}else if($_GET[accion]==6){		
		$s = "SELECT * FROM entregasespecialesead 
		WHERE (guia = '".$_GET[guia]."' OR rastreo = '".$_GET[guia]."') AND estado = 1";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die("ya existe");
		}
		
		$s = "SELECT gv.id AS guia,gv.idremitente,gv.iddestinatario,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',d.nombre,d.paterno,d.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente d ON gv.iddestinatario = d.id
		WHERE gv.id = '".$_GET[guia]."' AND gv.estado = 'ALMACEN DESTINO'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."" : "")."
		UNION
		SELECT ge.id AS guia,ge.idremitente,ge.iddestinatario,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',d.nombre,d.paterno,d.materno) AS destinatario 
		FROM guiasempresariales ge
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente d ON ge.iddestinatario = d.id
		WHERE ge.id = '".$_GET[guia]."' AND ge.estado = 'ALMACEN DESTINO'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."" : "")."
		UNION
		SELECT r.noguia AS guia, t.idremitente, t.iddestinatario, 
		t.remitente, t.destinatario FROM guia_rastreo r
		INNER JOIN (
		SELECT gv.id AS guia, gv.estado ,gv.idremitente,gv.iddestinatario,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',d.nombre,d.paterno,d.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente d ON gv.iddestinatario = d.id
		UNION
		SELECT ge.id AS guia, ge.estado,ge.idremitente,ge.iddestinatario,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',d.nombre,d.paterno,d.materno) AS destinatario 
		FROM guiasempresariales ge
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente d ON ge.iddestinatario = d.id
		) t ON r.noguia = t.guia
		WHERE r.numerorastreo = '".$_GET[guia]."' AND t.estado = 'ALMACEN DESTINO'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND r.destino = ".$_SESSION[IDSUCURSAL]."" : "")."";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			
			$f = mysql_fetch_object($r);
			echo "ok,".$f->guia.",".$f->idremitente.",".$f->iddestinatario.",".$f->remitente.",".$f->destinatario;
		}else{
			echo "no encontro";
		}
	}
?>
