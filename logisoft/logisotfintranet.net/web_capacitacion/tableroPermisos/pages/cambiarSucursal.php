<?
	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[sucursal]!=""){
		$_SESSION[IDSUCURSAL]=$_GET[sucursal];
		echo "ok";
	}
?>