<?


	include('../Conectar.php');


	$link=Conectarse('webpmm');


if(isset($_GET['getCountriesByLetters']) && isset($_GET['letters'])){


	$letters = $_GET['letters'];


	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);


	$res = mysql_query("SELECT id, CONCAT(nombre,' ',paterno,' ',materno) as nombre FROM catalogocliente 
										  WHERE CONCAT(nombre,' ',paterno,' ',materno) like '".$letters."%'",$link) or die(mysql_error());	


	while($inf = mysql_fetch_array($res)){


		echo $inf[0]."###".cambio_texto($inf[1])."|";


	}


}		


?>


