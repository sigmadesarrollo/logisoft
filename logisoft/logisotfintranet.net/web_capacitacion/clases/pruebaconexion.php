<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>

</head>



<body>

	<?

		/*require("Conectar.php");

		$va = new Conectar("webpmm");

		$l = $va->iniciar();

		

		$s = "select * from catalogousuario";

		$r = mysql_query($s,$l) or die($s);

		while($f = mysql_fetch_object($r)){

			echo $f->Nombre;

		}*/

		

		include_once("ValidaConvenio.php");

		$vc = new ValidaConvenio(50,80,0);

		echo $vc->getJsonDataVentanilla();

	?>



</body>

</html>

