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

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

</style>

</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

  <table width="507" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="503" class="FondoTabla Estilo4">ABONOS A CLIENTE</td>

    </tr>

    <tr>

      <td><table width="503" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="58">Sucursal</td>

              <td width="107"><select name="select5" style="width:100px;">

              </select></td>

              <td width="30">Fecha</td>

              <td width="109"><span class="Tablas">

                <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>

              </span></td>

              <td width="26">Folio</td>

              <td width="100"><span class="Tablas">

                <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>

              </span></td>

              <td width="73"><div class="ebtn_buscar"></div></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2" class="estilo_relleno Estilo4"> Datos del Abono</td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="61">Cliente</td>

              <td width="50"><span class="Tablas">

                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:50px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>

              </span></td>

              <td width="27"><div class="ebtn_buscar"></div></td>

              <td width="365"><span class="Tablas">

                <input name="cliente2" type="text" class="Tablas" id="cliente2" style="width:330px" value="<?=$cliente2 ?>" />

              </span></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="61">Descripcion</td>

              <td width="442"><span class="Tablas">

                <input name="descripcion" type="text" class="Tablas" id="descripcion" style="width:434px" value="<?=$descripcion ?>" />

              </span></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="253" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="61">Cobrador</td>

              <td width="192"><select name="select2" style="width:175px;">

              </select></td>

              </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="250"><table width="502" border="0" cellspacing="0" cellpadding="0">

                

                <tr>

                  <td width="61">Factura</td>

                  <td colspan="3"><span class="Tablas">

                    <input name="factura" type="text" class="Tablas" id="factura" style="width:150px" value="<?=$factura ?>" />

                  </span></td>

                  <td width="292"><div class="ebtn_buscar"></div></td>

                </tr>

                

              </table></td>

              <td width="253">&nbsp;</td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2" class="estilo_relleno Estilo4">Aplcaci&oacute;n del Abono a Cargos </td>

        </tr>

        <tr>

          <td colspan="2"><table width="497" border="0" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td width="9" height="16" class="formato_columnas_izq"></td>

              <td width="47" align="center"  class="formato_columnas">Guia</td>

              <td width="68" class="formato_columnas"align="center">Fecha</td>

              <td width="56" class="formato_columnas"align="center">Fecha Venc. </td>

              <td width="61" class="formato_columnas"align="center">Factura </td>

              <td width="61" class="formato_columnas"align="center">Importe </td>

              <td width="80" class="formato_columnas"align="center">Saldo </td>

              <td width="101" class="formato_columnas"align="center">Aplicación </td>

              <td width="14" class="formato_columnas_der">&nbsp;</td>

            </tr>

            <tr>

              <td colspan="11" ><div id="detalle" name="detalle" style=" width:495px; height:50px; overflow:auto" align="left">

                  <? $line = 0; ?>

                  <table width="485" border="0" cellspacing="0" cellpadding="0">

                    <?		

			while($line<=200){?>

                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >

                      <td height="16" width="21" ><input name="id" type="hidden" value="<?=$row[id] ?>" /></td>

                      <td width="40" align="center" class="style31"  ><input name="guia" type="text" class="style2" id="guia" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>

                      <td width="66" align="center" class="style31"><input name="fecha" type="text" class="style2" id="fecha" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="44" class="style31" align="center"><input name="fvenc" type="text" class="style2" id="fvenc" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" /></td>

                      <td width="70" align="center" class="style31"><input name="factura" type="text" class="style2" id="factura" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="62" align="center" class="style31"><input name="importe" type="text" class="style2" id="importe" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="76" align="center" class="style31"><input name="saldo" type="text" class="style2" id="saldo" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="106" align="center" class="style31"><input type="checkbox"/> </td>

                    </tr>

                    <?

		$line ++ ; }			

	?>

                  </table>

              </div></td>

            </tr>

          </table></td>

        </tr>

        

        

        <tr>

          <td colspan="2" class="FondoTabla">Informaci&oacute;n Adicional</td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="59">Salda Con:<br /></td>

              <td width="105"><span class="Tablas">

                <input name="saldo" type="text" class="Tablas" id="saldo" style="width:100px;background:#FFFF99" value="<?=$saldo ?>" readonly=""/>

              </span></td>

              <td width="116">Saldo antes de aplicar:</td>

              <td width="223"><span class="Tablas">

                <input name="saldo2" type="text" class="Tablas" id="saldo2" style="width:100px;background:#FFFF99" value="<?=$saldo2 ?>" readonly=""/>

              </span></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td align="right">&nbsp;</td>

          <td>&nbsp;</td>

        </tr>

        <tr>

          <td width="435" align="right"><div class="ebtn_guardar"></div></td>

          <td width="70"><div class="ebtn_imprimir"></div></td>

        </tr>

        <tr>

          <td colspan="2">&nbsp;</td>

        </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'ABONOS A CLIENTES';

</script>

</html>