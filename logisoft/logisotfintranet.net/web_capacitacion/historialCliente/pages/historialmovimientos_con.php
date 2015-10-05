<?
	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	$s = "SELECT gv.id guia, cso.prefijo origen, csd.prefijo destino, 
	CONCAT(' ',cco.nombre,cco.paterno,cco.materno) remitente,
	CONCAT(' ',ccd.nombre,ccd.paterno,ccd.materno) destinatario,
	gv.subtotal, gv.tiva, gv.ivaretenido, gv.total
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cso ON gv.idsucursalorigen = cso.id
	INNER JOIN catalogosucursal csd ON gv.idsucursalorigen = csd.id
	INNER JOIN catalogocliente cco ON gv.idremitente = cco.id
	INNER JOIN catalogocliente ccd ON gv.iddestinatario = ccd.id
	WHERE  if(gv.tipoflete=0,idremitente = $_GET[cliente],iddestinatario = $_GET[cliente])";
	$r = mysql_query($s,$l) or die($s);
	$arre = array();
	while($f=mysql_fetch_object($r)){
		$f->origen = utf8_encode($f->origen);
		$f->destino = utf8_encode($f->destino);
		$f->remitente = utf8_encode($f->remitente);
		$f->destinatario = utf8_encode($f->destinatario);
		$arre[] = $f;
	}
	echo json_encode($arre);
?>