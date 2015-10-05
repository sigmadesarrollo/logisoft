<?
	session_start();
	require_once('Conectar.php');
	$link=Conectarse('webpmm'); 
	
	$s = "delete from sesiones where idusuario = '$_SESSION[IDUSUARIO]'";
	//echo $s;
	mysql_query($s,$link) or die($s);
	
	$s = "delete from sesiones where idusuario = '$_SESSION[IDUSUARIO]'";
	//echo $s;
	mysql_query($s,$link) or die($s);
?>
<script>
	alert("<?=$s?>");
	window.close();
</script>
Cerrando...