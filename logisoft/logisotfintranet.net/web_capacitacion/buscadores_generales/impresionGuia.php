<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	if($_GET[tipo]=="V")
		$s = "SELECT COUNT(*) AS total FROM guiaventanilla_unidades WHERE idguia = '$_GET[folio]'";
	else
		$s = "SELECT COUNT(*) AS total FROM guiasempresariales_unidades WHERE idguia = '$_GET[folio]'";
	
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
?>
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
<table width="242" height="99"  border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td class="FondoTabla">Elija la impresión</td>
</tr>
<tr>
    <td height="81">
      <table width="238" height="75" border="0" align="left" cellpadding="0" cellspacing="0" id="tab">
        <tr>
          <td width="104" hei class="Tablas"></td>
          <td width="82" class="Tablas"></td>
          <td width="36" class="Tablas"></td>
          <td width="16" class="Tablas"></td>
        </tr>
        <tr>
          <td class="Tablas">Guia</td>
          <td class="Tablas" align="center"><img src="../img/AdobeReader.gif" width="28" height="29" /></td>
          <td class="Tablas"><input type="checkbox" checked="checked" id="imprimirGuia" value="Pantalla" /></td>
          <td class="Tablas"></td>
        </tr>
        <tr>
          <td class="Tablas">Todas las Etiquetas</td>
          <td class="Tablas" align="center"><img src="../img/AdobeReader.gif" width="28" height="29" /></td>
          <td class="Tablas"><input type="radio" name="etiquetas" ondblclick="this.checked=false" /></td>
          <td class="Tablas">&nbsp;</td>
        </tr>
        <tr>
          <td class="Tablas" align="center">Etiquetas</td>
          <td class="Tablas" align="center">No Etiqueta</td>
          <td class="Tablas"><input type="radio" name="etiquetas" readonly="readonly" ondblclick="this.checked=false" /></td>
          <td class="Tablas">&nbsp;</td>
        </tr>
        <tr>
          <td class="Tablas" align="center"><input type="text" style="width:50px;background:#FFFF99" value="<?=$f->total?>" /></td>
          <td class="Tablas" align="center"><input type="text" name="noetiqueta" style="width:50px" onkeypress="return solonumeros(event)" /></td>
          <td class="Tablas">&nbsp;</td>
          <td class="Tablas">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" class="Tablas" align="center"><div class="ebtn_imprimir" onclick="elegirImpresion()"></div></td>
</tr>
</table></td>
  </tr>
</table>
	<p>
	  <script>
		function elegirImpresion(){
			var valor = 0;
			var etiqueta = "";
			if(document.getElementById('imprimirGuia').checked)
				valor += 1;
			if(document.all['etiquetas'][0].checked)
				valor += 2;
			if(document.all['etiquetas'][1].checked){
				valor += 4;
				etiqueta = document.all.noetiqueta.value;
			}
			parent.imprimirDocumentos(valor,etiqueta);	
			parent.VentanaModal.cerrar();
		}
		
		function solonumeros(evnt){
			evnt = (evnt) ? evnt : event;
			var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
			if (!elem.readOnly){
				var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
				if (charCode > 31 && (charCode < 48 || charCode > 57)) {
					return false;
				}
				return true;
			}
		}
	</script>
</p>
</body>
</html>
