<?
	header( "Content-type: image/png");
	require_once("../ConectarSolo.php");
	$l = Conectarse("webpmm");
	if($_GET[tipo]==1){
		$s = "SELECT estado,firma FROM guiasventanilla WHERE id = '$_GET[guia]'";
	}else{
		$s = "SELECT estado,firma FROM guiasempresariales WHERE id = '$_GET[guia]'";
	}
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	if($f->estado=='EN REPARTO EAD'){
		$s = "SELECT firma FROM devyliqautomatica WHERE guia='$_GET[guia]' AND estado='E'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
		}
	}
	
	echo $f->firma;
?>