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

<style type="text/css">

<!--

.Estilo4 {font-size: 12px}

-->

</style>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">

<link href="../FondoTabla.css" rel="stylesheet" type="text/css">

</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="619" class="FondoTabla Estilo4">Datos Generales  </td>

  </tr>

  <tr>

    <td><table width="615" border="0" cellpadding="0" cellspacing="0">

      <tr>

        <td><table width="618" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="201"><div align="right">Folio</div></td>

              <td width="100"><span class="Tablas">

                <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>

              </span></td>

              <td width="29">Fecha</td>

              <td width="100"><span class="Tablas">

                <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>

              </span></td>

              <td width="42">Sucursal</td>

              <td width="106"><span class="Tablas">

                <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>

              </span></td>

            </tr>

        </table></td>

      </tr>

      <tr>

        <td><table width="618" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="34">Unidad</td>

              <td width="184"><label>

                <select name="select2" style="width:100px">

                </select>

              </label></td>

              <td width="51"><label>Conductor</label></td>

              <td width="96"><input name="conductor" type="text" id="conductor" value="<?=$conductor ?>" size="10" /></td>

              <td width="24"><div class="ebtn_buscar"></div></td>

              <td width="229"><span class="Tablas">

                <input name="conductorb" type="text" class="Tablas" id="conductorb" style="width:200px;background:#FFFF99" value="<?=$conductorb ?>" readonly=""/>

              </span></td>

            </tr>

        </table></td>

      </tr>

      <tr>

        <td><table width="618" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="32">Sector</td>

              <td width="187"><span class="Tablas">

                <input name="sector" type="text" class="Tablas" id="sector" style="width:100px;background:#FFFF99" value="<?=$sector ?>" readonly=""/>

              </span></td>

              <td width="51"><label>Conductor</label></td>

              <td width="96"><input name="conductor2" type="text" id="conductor2" value="<?=$conductor ?>" /></td>

              <td width="24"><div class="ebtn_buscar"></div></td>

              <td width="218"><span class="Tablas">

                <input name="conductorb" type="text" class="Tablas" id="conductorb" style="width:200px;background:#FFFF99" value="<?=$conductorb ?>" readonly=""/>

              </span></td>

              <td width="10">&nbsp;</td>

            </tr>

        </table></td>

      </tr>

      <tr>

        <td><table width="618" border="0" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td>&nbsp;</td>

              <td align="center">&nbsp;</td>

              <td>&nbsp;</td>

            </tr>

            <tr>

              <td width="298"><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">

                  <tr>

                    <td width="2" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                    <td width="16"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>

                    <td width="44"  background="../img/borde1_2.jpg" class="style5" align="center">No. Gu&iacute;a </td>

                    <td width="55" background="../img/borde1_2.jpg" class="style5" align="center">Origen</td>

                    <td width="63" background="../img/borde1_2.jpg" class="style5" align="center">Fecha</td>

                    <td width="47" background="../img/borde1_2.jpg" class="style5" align="center">C&oacute;digo de Barra </td>

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

                            <td width="100" align="center" class="style31"  ><input name="Nguia" type="text" class="style2" id="Nguia" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>

                            <td width="130" align="center" class="style31"><input name="Origen" type="text" class="style2" id="Origen" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                            <td width="150" class="style31" align="center"><input name="fecha2" type="text" class="style2" id="fecha2" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" /></td>

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

              <td width="24" align="center"><div class="ebtn_adelante"></div>

                  <div class="ebtn_atraz"></div></td>

              <td width="296"><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr>

                  <td width="2" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                  <td width="16"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>

                  <td width="44"  background="../img/borde1_2.jpg" class="style5" align="center">No. Gu&iacute;a </td>

                  <td width="55" background="../img/borde1_2.jpg" class="style5" align="center">Origen</td>

                  <td width="63" background="../img/borde1_2.jpg" class="style5" align="center">Fecha</td>

                  <td width="47" background="../img/borde1_2.jpg" class="style5" align="center">C&oacute;digo de Barra </td>

                  <td width="22" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                  <td width="1"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                </tr>

                <tr>

                  <td colspan="8" align="right"><div id="div" name="detalle" style=" width:250px; height:150px; overflow:auto" align="left">

                      <? $line = 0; ?>

                      <table width="574" border="0" cellspacing="0" cellpadding="0">

                        <?		

			while($line<=200){?>

                        <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >

                          <td height="16" width="26" ><input name="id2" type="hidden" value="<?=$row[id] ?>" /></td>

                          <td width="100" align="center" class="style31"  ><input name="Nguia2" type="text" class="style2" id="Nguia2" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>

                          <td width="130" align="center" class="style31"><input name="Origen2" type="text" class="style2" id="Origen2" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                          <td width="150" class="style31" align="center"><input name="fecha3" type="text" class="style2" id="fecha3" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" /></td>

                          <td width="72" align="center" class="style31"><input name="Barra2" type="text" class="style2" id="Barra2" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

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

        <td><table width="618" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td>&nbsp;</td>

              <td>&nbsp;</td>

              <td>&nbsp;</td>

            </tr>

            <tr>

              <td width="298"><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">

                  <tr>

                    <td width="2" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                    <td width="16"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>

                    <td width="44"  background="../img/borde1_2.jpg" class="style5" align="center">Registro</td>

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

              <td width="24">&nbsp;</td>

              <td width="296"><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">

                  <tr>

                    <td width="2" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                    <td width="16"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>

                    <td width="44"  background="../img/borde1_2.jpg" class="style5" align="center">Registro</td>

                    <td width="55" background="../img/borde1_2.jpg" class="style5" align="center">Paquete</td>

                    <td width="63" background="../img/borde1_2.jpg" class="style5" align="center">C&oacute;digo de Barra </td>

                    <td width="47" background="../img/borde1_2.jpg" class="style5" align="center">Estado</td>

                    <td width="22" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                    <td width="1"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                  </tr>

                  <tr>

                    <td colspan="8" align="right"><div id="div3" name="detalle" style=" width:250px; height:50px; overflow:auto" align="left">

                        <? $line = 0; ?>

                        <table width="574" border="0" cellspacing="0" cellpadding="0">

                          <?		

			while($line<=200){?>

                          <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >

                            <td height="16" width="26" ><input name="id32" type="hidden" value="<?=$row[id] ?>" /></td>

                            <td width="100" align="center" class="style31"  ><input name="Registro2" type="text" class="style2" id="Registro2" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>

                            <td width="130" align="center" class="style31"><input name="Paquete2" type="text" class="style2" id="Paquete2" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                            <td width="150" class="style31" align="center"><input name="Barra4" type="text" class="style2" id="Barra4" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" /></td>

                            <td width="72" align="center" class="style31"><input name="Estado2" type="text" class="style2" id="Estado2" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

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

      <tr>

        <td><label>

            <div align="center">

              Gu&iacute;a<span class="Tablas">

                <input name="guia" type="text" class="Tablas" id="guia" style="width:100px" value="<?=guia ?>" />

                </span>

              <select name="select" class="Tablas" style="width:100px" />

              </select>

              </select>

            </div>

          </label></td>

      </tr>

      <tr>

        <td>&nbsp;</td>

      </tr>

      

    </table></td>

  </tr>

</table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'REPARTO DE MERCANCÍA EAD';

</script>

</html>