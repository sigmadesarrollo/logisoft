<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {
	font-family: tahoma;
	color: #FFFFFF;
	font-weight: bold;
	font-size: 14px;
}
.Estilo16 {
	font-family: tahoma;
	color: #FFFFFF;
	font-weight: bold;
	font-size: 14px;
}
body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
-->
</style>
</head>

<body>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="842" height="69">
  <param name="movie" value="pestanas2.swf" />
  <param name="quality" value="high" />
  <embed src="pestanas2.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="842" height="69"></embed>
</object>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td background="../img/bazul1.jpg" width=5 height=54></td>
        <td width=150 background="../img/bazul2.jpg" class="Estilo1" align="center" id="titulo">Men&uacute; Principal </td>
        <td background="../img/bazul3_v.jpg" width=59></td>
        <td background="../img/bazul4_v.gif" align="right" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" height="52">
		  <tr>
					<td width="114" height="29" class="Estilo16">Punto de Venta</td>
					<td width="12" bgcolor="#FFFFFF" background="imagen/bazul5_v.gif"></td>
			  </tr>
				<tr>
				  <td height="23" valign="bottom" align="center" style="color:#FFFFFF;font-family: tahoma; font-size:12px ">ver. 1.5</td>
					<td></td>
				</tr>
		  </table>
		</td>
      </tr>
</table>
</body>
<script language="javascript">
	function enviarTab(valor){
		var izquierda = "";
		var centro = "";
		var derecha = "";
		
		switch(valor){
			case 0:
				izquierda = "sucursali.php";
				centro = "sucursalc.php";
				derecha = "sucursald.php";
				break;
			case 1:
				izquierda = "ventasi.php";
				centro = "webministator.php";
				derecha = "ventasd.php";
				break;
			case 2:
				izquierda = "creditoi.php";
				centro = "webministator.php";
				derecha = "creditod.php";
				break;
			case 3:
				izquierda = "operacioni.php";
				centro = "webministator.php";
				derecha = "operaciond.php";
				break;
		}
		parent.frames[0].document.getElementById('frameizquierda').src=izquierda;
		parent.frames[2].document.location.href=centro;
		parent.frames[4].document.location.href=derecha;
	}
</script>
</html>
