<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT *
		FROM guia_temporaldetalle WHERE id = $_GET[iddetalle]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$f->descripcion = cambio_texto($f->descripcion);
		$f->contenido = cambio_texto($f->contenido);
		
		echo "(".str_replace("null", '""', json_encode($f)).")";
	}
	
	if($_GET[accion]==2){
		$s = "UPDATE guia_temporaldetalle SET peso=$_GET[peso], volumen=$_GET[volumen],
		pesou=$_GET[pesou], largo=$_GET[largo],
		ancho=$_GET[ancho], alto=$_GET[alto]
		WHERE id = $_GET[idmercancia]";
		$r = mysql_query($s,$l) or die($s);
		
		echo "guardado";
	}
	
?>
