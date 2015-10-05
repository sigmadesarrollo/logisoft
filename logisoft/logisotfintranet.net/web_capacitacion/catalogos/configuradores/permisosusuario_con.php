<?	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT id, CONCAT_WS(' ',nombre, apellidopaterno, apellidomaterno) AS nombre, user
		FROM catalogoempleado WHERE id = $_GET[idempleado]";
		$r = mysql_query($s,$l) or die($s);
		$fx = mysql_fetch_object($r);
		
		/*$s = "SELECT * FROM permisos_empleadospermisos WHERE idempleado=$_GET[idempleado]";
		$r = mysql_query($s,$l) or die($s);
		$arre = "[";
		while($f = mysql_fetch_object($r)){
			if($arre!="[")
				$arre .= ",";
			$arre .= "{idpermiso:'$f->idpermiso'}";
		}
		$arre .= "]";*/
		
		$f->nombre = cambio_texto($f->nombre);
		
		//echo "({id:'$fx->id', nombre:'$fx->nombre', user:'$fx->user', permisos:$arre})";
		echo "({id:'$fx->id', nombre:'$fx->nombre', user:'$fx->user'})";
	}	
	
	if($_GET[accion]==2){
		$s = "DELETE FROM permisos_empleadospermisos WHERE idempleado = $_GET[idempleado]";
		mysql_query($s,$l) or die($s);		
		
		$permisos = split(",",$_GET[permisos]);
		for($i=0; $i<count($permisos); $i++){
			$s = "INSERT INTO permisos_empleadospermisos SET idpermiso = '$permisos[$i]', idempleado = $_GET[idempleado]";
			mysql_query($s,$l) or die($s);
		}
		echo "guardo";
	}
?>
