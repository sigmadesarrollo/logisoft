<?
	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT * FROM catalogoempleado WHERE id = '$_SESSION[IDUSUARIO]' AND password='$_GET[oldpass]'";
		$r = mysql_query($s,$l) or die("Error $s");
		if(mysql_num_rows($r)>0){
			$s = "update catalogoempleado set password='$_GET[newpass]' WHERE id = '$_SESSION[IDUSUARIO]'";
			mysql_query($s,$l) or die("Error $s");
			echo "La contraseña ha sido guardada";
		}else{
			echo "Error: La contraseña anterior es incorrecta";
		}
	}
	
	if($_GET[accion]==2){
		$s = "SELECT id FROM configuradorpestanas";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$s = "UPDATE configuradorpestanas SET 
			pestana1 = '".$_GET[pestana1]."',
			pestana2 = '".$_GET[pestana2]."',
			pestana3 = '".$_GET[pestana3]."',
			pestana4 = '".$_GET[pestana4]."',
			pestana5 = '".$_GET[pestana5]."',
			idusuario = ".$_SESSION[IDUSUARIO]."
			WHERE id = ".$f->id."";
			mysql_query($s,$l) or die($s);
		}else{
			$s = "INSERT INTO configuradorpestanas SET 
			pestana1 = '".$_GET[pestana1]."',
			pestana2 = '".$_GET[pestana2]."',
			pestana3 = '".$_GET[pestana3]."',
			pestana4 = '".$_GET[pestana4]."',
			pestana5 = '".$_GET[pestana5]."',
			idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
		}
		
		echo "ok";
	}
?>