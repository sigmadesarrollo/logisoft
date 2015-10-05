<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
.Estilo2 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="puntovta.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo3 {	font-size: 8px;
	font-weight: bold;
}
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">

  <br/>
<table width="508" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="504" class="FondoTabla">DATOS DE COTIZACI&Oacute;N</td>
  </tr>
  <tr>
    <td><table width="504" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="71" class="Tablas"><label>Atención a</label></td>
        <td width="433"><input name="Atencion" type="text" class="Tablas" id="Atencion" style="width:400px;background:#FFFF99" value="<?=$Atencion ?>"  readonly=""/></td>
      </tr>
      <tr>
        <td class="Tablas"><label>Email</label></td>
        <td><input name="Email" type="text" class="Tablas" id="Email" style="width:300px;background:#FFFF99" value="<?=$Email ?>"  readonly=""/></td>
      </tr>
      <tr>
        <td colspan="2">
          <div align="center">
            <input name="Btnaceptar" type="image" id="Btnaceptar" src="../img/Boton_Aceptar.gif"/>
            <input name="Btnvistaprevia" type="image" id="Btnvistaprevia" src="../img/Boton_Previa.gif"/>
            </div></td></tr>
    </table></td>
  </tr>
</table>
</form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'DATOS COTIZACIÓN';
</script>
</html>