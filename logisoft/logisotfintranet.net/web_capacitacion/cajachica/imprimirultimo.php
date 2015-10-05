<?
	session_start();
	$archivo = file_get_contents("cheques/cheques".$_SESSION[IDSUCURSAL].".txt");
	
	//header('Content-Disposition: attachment; filename="cheques.txt"');
	echo $archivo;
?>