<?
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	if(isset($_GET['getCountriesByLetters']) && isset($_GET['letters'])){
		$letters = $_GET['letters'];
		$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
		$res = mysql_query("SELECT cc.id, CONCAT_WS('-',cc.descripcion,cp.descripcion) AS descripcion FROM catalogocolonia cc
		INNER JOIN catalogopoblacion cp ON cc.poblacion = cp.id
		WHERE cc.descripcion like '".$letters."%'",$link) or die(mysql_error());	
		while($inf = mysql_fetch_array($res)){
			echo $inf[0]."###".$inf[1]."|";
		}	
	}
?>
