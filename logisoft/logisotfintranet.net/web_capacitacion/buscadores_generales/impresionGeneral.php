<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Tablas {	font-family: tahoma;
	font-size: 9px;
	font-style: normal;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="242" height="99"  border="1" cellpadding="0" cellspacing="0" bordercolor="#016193" align="center">
  <tr>
    <td class="FondoTabla">Elija el formato de impresión</td>
</tr>
<tr>
    <td height="81">
      <table width="238" height="75" border="0" align="left" cellpadding="0" cellspacing="0" id="tab">
        <tr>
          <td width="97" hei class="Tablas"></td>
          <td width="59" class="Tablas"></td>
          <td width="56" class="Tablas"></td>
          <td width="26" class="Tablas"></td>
        </tr>
        <tr>
          <td class="Tablas">PDF</td>
          <td class="Tablas" align="center"><img src="../img/AdobeReader.gif" width="28" height="29" /></td>
          <td class="Tablas"><input type="checkbox" checked="checked" id="pdf" /></td>
          <td class="Tablas"></td>
        </tr>
        <tr>
          <td class="Tablas">EXCEL</td>
          <td class="Tablas" align="center"><img src="../img/excel.gif" width="28" height="29" /></td>
          <td class="Tablas"><input type="checkbox" id="excel" /></td>
          <td class="Tablas">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" class="Tablas" align="center"><div class="ebtn_imprimir" onclick="elegirImpresion()"></div></td>
</tr>
</table></td>
  </tr>
</table>
<script>
	function elegirImpresion(){
		valor = ((document.getElementById('pdf').checked)?"PDF":"")+((document.getElementById('excel').checked)?"EXCEL":"")
		parent.<?=$_GET[funcion]?>(valor);
	}
</script>
</body>
</html>
