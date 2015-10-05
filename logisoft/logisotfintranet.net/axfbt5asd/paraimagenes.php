<?
	header( "Content-type: image/png");
	require_once("../web/Conectar.php");
	$l = Conectarse("webpmm");
	if($_GET[tipo]==1){
		$s = "SELECT firma FROM guiasventanilla WHERE id = '$_GET[guia]'";
	}else{
		$s = "SELECT firma FROM guiasempresariales WHERE id = '$_GET[guia]'";
	}
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	echo $f->firma;
?>