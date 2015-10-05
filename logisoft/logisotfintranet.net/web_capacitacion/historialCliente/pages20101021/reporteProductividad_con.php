<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmintranet.net';</script>");
	}*/
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
		
	$paginado = 10;
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
	
	$s = "SELECT nombrecliente, SUM(undiaead) AS undiaead, SUM(dosdiaead) AS dosdiaead, SUM(faltanteead) AS faltanteead,
	SUM(undiarec) AS undiarec, SUM(dosdiasrec) AS dosdiasrec, SUM(faltanterec) AS faltanterec FROM(
	SELECT r1.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente, 
	IFNULL(SUM(IF(diasead = 0,1,0)),0) AS undiaead, IFNULL(SUM(IF(diasead >= 1,1,0)),0) AS dosdiaead,
	IFNULL((SELECT SUM(t.total) FROM(
	SELECT COUNT(*) AS total FROM guiasventanilla 
	WHERE idremitente = ".$_GET[cliente]." AND estado = 'ALMACEN DESTINO' AND ocurre = 0
	UNION
	SELECT COUNT(*) AS total FROM guiasempresariales 
	WHERE idremitente = ".$_GET[cliente]." AND estado = 'ALMACEN DESTINO' AND ocurre = 0)t),0) AS faltanteead,
	0 AS undiarec, 0 AS dosdiasrec, 0 AS faltanterec FROM reporteproductividad_cliente1 r1
	INNER JOIN catalogocliente cc ON r1.cliente = cc.id
	WHERE r1.cliente = ".$_GET[cliente]." 
	UNION
	SELECT r2.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente,
	0 AS undiaead, 0 AS dosdiasead, 0 AS totalead, SUM(IF(diasrecoleccion = 0,1,0)) AS undiarec,
	SUM(IF(diasrecoleccion >= 1,1,0)) AS dosdiasrec,
	(SELECT COUNT(*) FROM recoleccion WHERE (realizo IS NULL OR realizo = 'NO')) AS faltanterec
	FROM reporteproductividad_cliente2 r2
	INNER JOIN catalogocliente cc ON r2.cliente = cc.id
	WHERE r2.cliente = ".$_GET[cliente]." AND diasrecoleccion IS NOT NULL) t GROUP BY cliente HAVING nombrecliente <>''";
	$r = mysql_query($s,$l) or die($s);
	$totalregistros = mysql_num_rows($r);
		
	$totales = '""';
	
	$s = "SELECT nombrecliente, SUM(undiaead) AS undiaead, SUM(dosdiaead) AS dosdiaead, SUM(faltanteead) AS faltanteead,
	SUM(undiarec) AS undiarec, SUM(dosdiasrec) AS dosdiasrec, SUM(faltanterec) AS faltanterec FROM(
	SELECT r1.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente, 
	IFNULL(SUM(IF(diasead = 0,1,0)),0) AS undiaead, IFNULL(SUM(IF(diasead >= 1,1,0)),0) AS dosdiaead,
	IFNULL((SELECT SUM(t.total) FROM(
	SELECT COUNT(*) AS total FROM guiasventanilla 
	WHERE idremitente = ".$_GET[cliente]." AND estado = 'ALMACEN DESTINO' AND ocurre = 0
	UNION
	SELECT COUNT(*) AS total FROM guiasempresariales 
	WHERE idremitente = ".$_GET[cliente]." AND estado = 'ALMACEN DESTINO' AND ocurre = 0)t),0) AS faltanteead,
	0 AS undiarec, 0 AS dosdiasrec, 0 AS faltanterec FROM reporteproductividad_cliente1 r1
	INNER JOIN catalogocliente cc ON r1.cliente = cc.id
	WHERE r1.cliente = ".$_GET[cliente]." 
	UNION
	SELECT r2.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente,
	0 AS undiaead, 0 AS dosdiasead, 0 AS totalead, SUM(IF(diasrecoleccion = 0,1,0)) AS undiarec,
	SUM(IF(diasrecoleccion >= 1,1,0)) AS dosdiasrec,
	(SELECT COUNT(*) FROM recoleccion WHERE (realizo IS NULL OR realizo = 'NO')) AS faltanterec
	FROM reporteproductividad_cliente2 r2
	INNER JOIN catalogocliente cc ON r2.cliente = cc.id
	WHERE r2.cliente = ".$_GET[cliente]." AND diasrecoleccion IS NOT NULL) t GROUP BY cliente HAVING nombrecliente <>''";
	$r = mysql_query($s,$l) or die($s);
	$ar = array();
	while($f = mysql_fetch_object($r)){
		$f->nombrecliente = cambio_texto($f->nombrecliente);
		$ar[] = $f;
	}
		
	$registros = json_encode($ar);
	echo '({"total":"'.$totalregistros.'",
	"totales":'.$totales.',
	"registros":'.$registros.',
	"contador":"'.$contador.'",
	"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
	"atras":"'.f_atras($contador).'",
	"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	
?>