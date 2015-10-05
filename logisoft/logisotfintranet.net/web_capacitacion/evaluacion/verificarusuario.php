<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	include('../Conectar.php');

	$link=Conectarse('webpmm');	

	$usuario=$_GET['usuario']; $password=$_GET['pass']; $modulo=$_GET['modulo']; $idusuario=$_GET['idusuario'];	$cancelar=$_GET['cancelar'];

	

	$sql=mysql_query("SELECT ce.user, ce.password 

					 FROM catalogoempleado ce 

					 WHERE ce.password='$password' And ce.user='$usuario' and '$_SESSION[NOMBREUSUARIO]'='$usuario'",$link);

	if(@mysql_num_rows($sql)>0){


				$autorizar="SI";

	}else{

		$autorizar="NO";

	}

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>

<body>

<input name="autorizar" type="hidden" id="autorizar"  value="<?=$autorizar ?>" >

<input name="cancelar" type="hidden" id="cancelar" onChange="Autorizar()" value="<?=$cancelar ?>">

</body>

</html>