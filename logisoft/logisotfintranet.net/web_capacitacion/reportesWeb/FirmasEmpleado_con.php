<? 
	session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if(!empty($_POST[todosempleados])){
		$andemp = "";
	}else{
		$andemp = " AND ptf.usuario = $_POST[idempleado] ";
	}
	
	if(!empty($_POST[todostemas])){
		$andtem = "";
	}else{
		$andtem = " AND ptf.idtema = $_POST[idtema] ";
	}
	
	$s = "SELECT CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno) empleado,
		pt.tema, DATE_FORMAT(DATE(ptf.fechayhora),'%d/%m/%Y') fecha, 
		TIME(ptf.fechayhora) hora
		FROM pmmnews_temas_firmados ptf
		INNER JOIN pmmnews_temas pt ON ptf.idtema = pt.id
		INNER JOIN catalogoempleado ce ON ptf.usuario = ce.id
		$andemp $andtem";
	$r = mysql_query($s,$l) or die($s);
	$arre = array();
	while($f = mysql_fetch_object($r)){
		$f->nombreempleado = utf8_encode($f->nombreempleado);
		$f->tema = utf8_encode($f->tema);
		$arre[] = $f;
	}
	echo json_encode($arre);
?>
