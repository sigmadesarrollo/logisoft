<?
	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "select * from iniciodia where sucursal = $_SESSION[IDSUCURSAL] and date(fecha) = current_date";
	$r = mysql_query($s,$l) or die($s);
	
	$s = "select * from iniciocaja where sucursal = $_SESSION[IDSUCURSAL] and date(fecha) = current_date";
	$rx = mysql_query($s,$l) or die($s);
	
		echo "({'iniciodia':".((mysql_num_rows($r)>0)?"'1'":"'0'").",'iniciocaja':".((mysql_num_rows($rx)>0)?"'1'":"'0'")."})";

	
	
?>