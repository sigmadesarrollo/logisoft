<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<?
	$result=mysql_query("SELECT descripcion FROM contenidos",$link);
	if(mysql_num_rows($result)>0){
		while($con=mysql_fetch_array($result)){
			$cadena= "'".utf8_decode($con[0])."'".','.$cadena;			
		}
		$cadena=substr($cadena, 0, -1);
	}
?>
<script src="moautocomplete.js"></script>
<script>
	var concep = new Array(<?php echo $cadena; ?>);
</script>
</head>

<body>
<input name="descripcion" type="text" class="Tablas" id="descripcion" style="text-transform:uppercase" autocomplete="array:desc"/>
</body>
</html>