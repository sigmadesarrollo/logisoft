<?
	include('../Conectar.php');
	$link=Conectarse('webpmm');
if(isset($_GET['getCountriesByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT id, descripcion FROM catalogosucursal WHERE descripcion like '".$letters."%'",$link) or die(mysql_error());	
	while($inf = mysql_fetch_array($res)){
		echo $inf[0]."###".$inf[1]."|";
	}
}		
?>
