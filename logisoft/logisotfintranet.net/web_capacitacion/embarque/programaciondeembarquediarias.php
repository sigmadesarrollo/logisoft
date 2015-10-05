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
<table width="329" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="325" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="325" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="325"><table width="323" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="323"><div align="right"></div>
              <div align="right">Fecha<span class="Tablas">
                <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
                  </span>Sucursal<span class="Tablas">
                    <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>
                    </span></div></td>
            </tr>
        </table></td>
      </tr>
      
      <tr>
        <td><table width="325" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
              <td width="298">&nbsp;</td>
              <td width="24" align="center">&nbsp;</td>
              <td width="3">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3"><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="2" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                    <td width="16"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>
                    <td width="44"  background="../img/borde1_2.jpg" class="style5" align="center">Unidad</td>
                    <td width="55" background="../img/borde1_2.jpg" class="style5" align="center">Ruta</td>
                    <td width="63" background="../img/borde1_2.jpg" class="style5" align="center">H. Llegada </td>
                    <td width="47" background="../img/borde1_2.jpg" class="style5" align="center">H. Salida </td>
                    <td width="22" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                    <td width="1"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                  </tr>
                <tr>
                  <td colspan="8" align="right"><div id="detalle" name="detalle" style=" width:250px; height:150px; overflow:auto" align="left">
                    <? $line = 0; ?>
                    <table width="574" border="0" cellspacing="0" cellpadding="0">
                      <?		
			while($line<=200){?>
                      <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                        <td height="16" width="26" ><input name="id" type="hidden" value="<?=$row[id] ?>" /></td>
                            <td width="100" align="center" class="style31"  ><input name="Sector" type="text" class="style2" id="Sector" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
                            <td width="130" align="center" class="style31"><input name="Nguia" type="text" class="style2" id="Nguia" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
                            <td width="150" class="style31" align="center"><input name="Origen" type="text" class="style2" id="Origen" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" /></td>
                            <td width="72" align="center" class="style31"><input name="Barra" type="text" class="style2" id="Barra" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
                            <td width="96" align="center" class="style31">&nbsp;</td>
                          </tr>
                      <?
		$line ++ ; }			
	?>
                      </table>
                    </div></td>
                  </tr>
              </table></td>
              </tr>
            
        </table></td>
      </tr>
      <tr>
        <td><table width="325" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="298">&nbsp;</td>
            <td width="24">&nbsp;</td>
            <td width="3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="2" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                <td width="16"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>
                <td width="44"  background="../img/borde1_2.jpg" class="style5" align="center">No. Gu&iacute;a </td>
                <td width="55" background="../img/borde1_2.jpg" class="style5" align="center">Paquete</td>
                <td width="63" background="../img/borde1_2.jpg" class="style5" align="center">C&oacute;digo de Barra </td>
                <td width="47" background="../img/borde1_2.jpg" class="style5" align="center">Estado</td>
                <td width="22" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                <td width="1"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
              </tr>
              <tr>
                <td colspan="8" align="right"><div id="div2" name="detalle" style=" width:250px; height:50px; overflow:auto" align="left">
                    <? $line = 0; ?>
                    <table width="574" border="0" cellspacing="0" cellpadding="0">
                      <?		
			while($line<=200){?>
                      <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                        <td height="16" width="26" ><input name="id3" type="hidden" value="<?=$row[id] ?>" /></td>
                        <td width="100" align="center" class="style31"  ><input name="Registro" type="text" class="style2" id="Registro" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
                        <td width="130" align="center" class="style31"><input name="Paquete" type="text" class="style2" id="Paquete" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
                        <td width="150" class="style31" align="center"><input name="Barra3" type="text" class="style2" id="Barra3" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" /></td>
                        <td width="72" align="center" class="style31"><input name="Estado" type="text" class="style2" id="Estado" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
                        <td width="96" align="center" class="style31">&nbsp;</td>
                      </tr>
                      <?
		$line ++ ; }			
	?>
                    </table>
                </div></td>
              </tr>
            </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      
    </table>
    </tr>
</table>
</form>
</body>

</html>
