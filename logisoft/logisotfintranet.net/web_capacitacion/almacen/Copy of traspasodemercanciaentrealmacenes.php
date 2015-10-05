<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="361" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="357" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="365" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="71">&nbsp;</td>
        <td width="29"><span class="Tablas">Fecha</span></td>
        <td width="112"><span class="Tablas">
          <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
        </span></td>
      </tr>
      <tr>
        <td colspan="5"><table width="360" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="97">Almac&eacute;n Origen </td>
            <td width="263"><span class="Tablas">
              <select name="select2" style="width:100px; font-size:9px">
              </select>
            </span></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td colspan="5"><table width="360" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="96">Almac&eacute;n Destino </td>
            <td width="194"><span class="Tablas">
              <select name="select" style="width:100px; font-size:9px">
              </select>
            </span></td>
            <td width="70"><div class="ebtn_agregar"></div></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td width="53">No. Gu&iacute;a</td>
        <td width="100"><span class="Tablas">
          <input name="guia" type="text" class="Tablas" id="guia" style="width:100px" value="<?=$guia ?>"/>
        </span></td>
        <td colspan="3"><div class="ebtn_buscar"></div></td>
        </tr>
      <tr>
        <td colspan="8"><table width="299" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="2" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
            <td width="17"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>
            <td width="58"  background="../img/borde1_2.jpg" class="style5" align="center">No. Gu&iacute;a </td>
            <td width="94" background="../img/borde1_2.jpg" class="style5" align="center">Almacen Origen </td>
            <td width="94" background="../img/borde1_2.jpg" class="style5" align="center">Almacen Destino </td>
                       <td width="5" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
            <td width="1"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
          </tr>
          <tr>
            <td colspan="12" align="center"><div id="detalle" name="detalle" style=" width:300px;height:150px; overflow:auto" align="left">
                <? $line = 0; ?>
                <table width="570" border="0" cellspacing="0" cellpadding="0">
                  <?		
			while($line<=200){?>
                  <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                    <td height="16" width="19" ><input name="id" type="hidden" value="<?=$row[id] ?>" /></td>
                    <td width="3" align="center" class="style31"  >&nbsp;</td>
                    <td width="53" align="center" class="style31"  ><input name="cantidad" type="text" class="style2" id="cantidad" readonly="" style="font-size:8px; font:tahoma; font-weight:bold; width:40px"/></td>
                    <td width="96" align="center" class="style31"><input name="descripcion" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold; width:80px" readonly=""/></td>
                    <td width="96" align="center" class="style31"><input name="concepto" type="text" class="style2" id="concepto" style="font-size:8px; font:tahoma;font-weight:bold; width:80px" readonly="" /></td>

                  <?
		$line ++ ; }			
	?>
                </table>
            </div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
    </table>
  </tr>
</table>
</form>
</body>

<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'TRASPASO DE MERCANCÍA ENTRE ALMACENES';
</script>
</html>