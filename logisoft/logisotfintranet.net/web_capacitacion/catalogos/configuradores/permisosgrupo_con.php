<?
	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "INSERT INTO permisos_grupos SET nombre = '$_GET[grupo]'";
		mysql_query($s,$l) or die($s);
		$idgp = mysql_insert_id($l);
		
		$permisos = split(",",$_GET[permisos]);
		for($i=0; $i<count($permisos); $i++){
			$s = "INSERT INTO permisos_grupospermisos SET idpermiso = '$permisos[$i]', idgrupo = $idgp";
			mysql_query($s,$l) or die($s);
		}
		echo "guardo,$idgp";
	}
	
	if($_GET[accion]==2){
		$s = "SELECT * FROM permisos_grupos WHERE id = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ngrupo = $f->nombre;
		$idgrupo = $f->id;
		$s = "SELECT * FROM permisos_grupospermisos WHERE idgrupo=$_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$arre = "[";
		while($f = mysql_fetch_object($r)){
			if($arre!="[")
				$arre .= ",";
			$arre .= "{idpermiso:'$f->idpermiso'}";
		}	
		$arre .= "]";
			echo "({grupo:'$ngrupo', id:'$idgrupo', permisos:$arre})";
	}
	
	if($_GET[accion]==3){
		$s = "SELECT ifnull(max(id),0)+1 as id FROM permisos_grupos";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo $f->id;
	}
	
	if($_GET[accion]==4){
		$s = "DELETE FROM permisos_grupospermisos WHERE idgrupo = $_GET[folio]";
		mysql_query($s,$l) or die($s);		
		
		$permisos = split(",",$_GET[permisos]);
		for($i=0; $i<count($permisos); $i++){
			$s = "INSERT INTO permisos_grupospermisos SET idpermiso = '$permisos[$i]', idgrupo = $_GET[folio]";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "CREATE TEMPORARY TABLE empleadosKitar
		SELECT id empleado FROM catalogoempleado WHERE grupo = $_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		if(!empty($_GET[quitados])){
			$s = "DELETE FROM permisos_empleadospermisos 
			WHERE idempleado IN(SELECT empleado FROM empleadosKitar)
			AND idpermiso IN($_GET[quitados]);";
			mysql_query($s,$l) or die($s);
		}
		
		if(!empty($_GET[agregados])){
			$s = "CREATE TEMPORARY TABLE `permisos` (
			  `permiso` DOUBLE NOT NULL DEFAULT '0'
			) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
			mysql_query($s,$l) or die($s);
			
			$s = "insert into permisos
			values (".str_replace(",","),(",$_GET[agregados]).")";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO permisos_empleadospermisos
			SELECT permiso, empleado
			FROM empleadosKitar
			INNER JOIN permisos";
			mysql_query($s,$l) or die($s);
		}
			
		echo "guardo";
	}
?>
