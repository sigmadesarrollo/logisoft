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

      <td width="503" class="FondoTabla Estilo4">LIQUIDACI&Oacute;N DE COBRANZA</td>

    </tr>

    <tr>

      <td><table width="503" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="188" align="right">fecha<span class="Tablas">

                <input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px;background:#FFFF99" readonly="" value="<?=$fecha ?>" />

              </span></td>

              <td width="48"><div align="right">Sucursal</div></td>

              <td width="80"><span class="Tablas">

                <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:80px;background:#FFFF99" readonly="" value="<?=$sucursal ?>" />

              </span></td>

              <td width="37"><div class="ebtn_buscar"></div></td>

              <td width="28">Folio</td>

              <td width="80"><span class="Tablas">

                <input name="folio" type="text" class="Tablas" id="folio" style="width:80px;background:#FFFF99" readonly="" value="<?=$folio ?>" />

              </span></td>

              <td width="42"><div class="ebtn_buscar"></div></td>

            </tr>

            <tr>

              <td colspan="7"><table width="503" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="99">Folio Liq Cobranza</td>

                  <td width="150"><span class="Tablas">

                    <input name="liquidacion" type="text" class="Tablas" id="liquidacion" style="width:150px" value="<?=$liquidacion ?>" />

                  </span></td>

                  <td width="254"><div class="ebtn_buscar"></div></td>

                </tr>

              </table></td>

              </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="52">Fecha al </td>

              <td width="106"><span class="Tablas">

                <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>

              </span></td>

              <td width="19">Dia</td>

              <td width="109"><span class="Tablas">

                <input name="dia" type="text" class="Tablas" id="dia" style="width:100px;background:#FFFF99" value="<?=$dia ?>" readonly=""/>

              </span></td>

              <td width="36"><label>Sector</label></td>

              <td width="181"><span class="Tablas">

                <input name="sector" type="text" class="Tablas" id="sector" style="width:100px;background:#FFFF99" value="<?=$sector ?>" readonly=""/>

              </span></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="51">Cobrador</td>

              <td width="249"><span class="Tablas">

                <input name="cobrador" type="text" class="Tablas" id="cobrador" style="width:249px;background:#FFFF99" readonly="" value="<?=$cobrador ?>" />

              </span></td>

              <td width="203"><div class="ebtn_buscar"></div></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="250"><table width="502" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="51">Factura</td>

                  <td width="146"><span class="Tablas">

                    <input name="factura" type="text" class="Tablas" id="factura" style="width:150px" value="<?=$factura ?>" />

                  </span></td>

                  <td width="305"><div class="ebtn_buscar"></div></td>

                </tr>

                

              </table></td>

              <td width="253">&nbsp;</td>

            </tr>

          </table></td>

        </tr>

        

        <tr>

          <td colspan="2"><table width="503" border="0" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td width="6" height="16" class="formato_columnas_izq"></td>

              <td width="56" align="center"  class="formato_columnas">Cliente</td>

              <td width="55" class="formato_columnas"align="center">Referencia</td>

              <td width="40" class="formato_columnas"align="center">Fecha</td>

              <td width="48" class="formato_columnas"align="center">Fecha Vto</td>

              <td width="44" class="formato_columnas"align="center">Factura</td>

              <td width="40" class="formato_columnas"align="center">importe</td>

              <td width="61" class="formato_columnas"align="center">Saldo Actual</td>

              <td width="34" class="formato_columnas"align="center">Pago</td>

              <td width="48" class="formato_columnas"align="center">Cobrada</td>

              <td width="63" class="formato_columnas"align="center">Compromiso</td>

              <td width="8" class="formato_columnas_der">&nbsp;</td>

            </tr>

            <tr>

              <td colspan="11" ><div id="detalle" name="detalle" style=" width:495px; height:50px; overflow:auto" align="left">

                  <? $line = 0; ?>

                  <table width="495" border="0" cellspacing="0" cellpadding="0">

                    <?		

			while($line<=200){?>

                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >

                      <td height="16" width="17" ><input name="id" type="hidden" value="<?=$row[id] ?>" /></td>

                      <td width="32" align="center" class="style31"  ><input name="cliente" type="text" class="style2" id="cliente" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>

                      <td width="63" align="center" class="style31"><input name="referencia" type="text" class="style2" id="referencia" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="44" class="style31" align="center"><input name="fecha" type="text" class="style2" id="fecha" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" /></td>

                      <td width="48" align="center" class="style31"><input name="vto" type="text" class="style2" id="vto" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="45" align="center" class="style31"><input name="factura" type="text" class="style2" id="factura" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="40" align="center" class="style31"><input name="importe" type="text" class="style2" id="importe" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="56" align="center" class="style31"><input name="saldoactual" type="text" class="style2" id="saldoactual" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

					  <td width="40" align="center" class="style31"><input name="pago" type="text" class="style2" id="pago" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

					  <td width="46" align="center" class="style31"><input name="cobrada" type="text" class="style2" id="cobrada" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

					  <td width="64" align="center" class="style31"><input name="compromiso" type="text" class="style2" id="compromiso" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

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

          <td colspan="2"><table width="362" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="51">Cliente</td>

              <td width="111"><span class="Tablas">

                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:100px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>

              </span></td>

              <td width="77"> Total a Pagado </td>

              <td width="123"><span class="Tablas">

                <input name="total" type="text" class="Tablas" id="total" style="width:80px;background:#FFFF99" value="<?=$total ?>" readonly=""/>

              </span></td>

            </tr>

          </table></td>

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

	parent.frames[1].document.getElementById('titulo').innerHTML = 'LIQUIDACIÓN DE COBRANZA';

</script>

</html>