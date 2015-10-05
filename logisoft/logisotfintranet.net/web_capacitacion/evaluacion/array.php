<?

	include('../Conectar.php');

	$link=Conectarse('webpmm');

	$result=mysql_query("SELECT id,descripcion FROM catalogodescripcion",$link);

	while($row=mysql_fetch_array($result)){

	$cadena= "'".$row[1]."'".','.$cadena; 	

	}	

	$cadena=substr($cadena, 0, -1);

	echo $cadena;	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<SCRIPT language="JavaScript" src="moautocomplete.js"></SCRIPT>

<script>

var desc = new Array(<?php echo $cadena; ?>);

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>



<body>

<INPUT id="emails" type="text" name="emails" autocomplete="array:desc">

</body>

</html>

