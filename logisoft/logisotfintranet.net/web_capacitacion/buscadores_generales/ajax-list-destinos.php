<?
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
if(isset($_GET['getCountriesByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$res = mysql_query("SELECT cd.id, concat(cd.descripcion,' - ',cs.prefijo) As descripcion, cd.sucursal FROM catalogodestino cd INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id where cd.descripcion like '".$letters."%'",$link) or die(mysql_error());	
	while($inf = mysql_fetch_array($res)){
		echo $inf[0]."###".cambio_texto($inf[1])."|";
	}	
}
?>
