<?
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
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
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
</head>
<script>
	function cancelar(){
		parent.document.all.motivocancelacion.value = document.all.motivocancelacion.value;
		parent.preguntarSiCancelar();
	}
</script>
<body>
<form id="form1" name="form1" method="post" action="">
<br>
<table width="309" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="305" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="305" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="305"><div align="left">Motivo
          <select name="motivocancelacion" class="Tablas" style="width:250px" />
          <?
		  $s = "select * from catalogomotivos";
		  $r = mysql_query($s,$l) or die($s);
		  while($f = mysql_fetch_object($r)){
			 ?>
			<option value="<?=$f->id?>"><?=$f->descripcion?></option>
			 <?
		  }
		  ?>
          </select>
        </div></td>
      </tr>
      <tr>
        <td align="right"><DIV class="ebtn_guardar" onclick="cancelar()"></DIV></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>