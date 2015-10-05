<?
	header('Content-Disposition: attachment; filename="cheques.txt"');
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT cc.*, date_format(cc.fecha, '%d%m%Y') as fechax
	FROM cajachica_cheques as cc
	WHERE id IN($_GET[ids])";
	
	$r = mysql_query($s,$l) or die($s);
	while($f=mysql_fetch_object($r)){
		echo $f->nocuenta."¦".$f->fechax."¦".$f->fechax."¦1¦".$f->nocheque."¦".$f->gerente."¦".$f->concepto."¦RepCajChic ".$f->idcheque."¦".number_format($f->totalcheque,2,"","")."¦3¦¦\r\n";									
	}
?>