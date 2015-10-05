<?

session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if (isset($_SESSION['gvalidar'])!=100){

echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";

	}else{*/

	include('../../Conectar.php');

	$link=Conectarse('webpmm');

	$usuario=$_SESSION[NOMBREUSUARIO];





?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

</head>



<body>

<table width="262" border="0" align="center" cellpadding="0" cellspacing="0" >

  <tr class="FondoTabla" >

    <td width="52">ID</td>

    <td width="448">CODIGO POSTAL</td>

  </tr>

  <tr class="FondoTabla" >

    <td colspan="2"><label>

      <input name="textfield" type="text" class="Tablas" id="textfield" />

    </label></td>

</tr>

  <tr>

    <td colspan="2" >

	<div id="DivCodigoPostal" style="overflow:scroll; width:500px; height:150px" >

    <table width="95%" border="0" cellpadding="0" cellspacing="0" class="Tablas">



      <? 

	  $sql="select 	* from catalogocodigopostal ";

	  $result=mysql_query($sql,$link);

	  while($row=mysql_fetch_array($result)){

	  ?>

      <tr>

        <td width="53"><?=$row[0]?></td>

        <td width="422"><?=$row[1]?></td>

      </tr>

      <? } ?>

    </table>

    </div></td>

</tr>

</table>

</body>

</html>

<? //} ?>