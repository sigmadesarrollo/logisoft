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

      <td width="503" class="FondoTabla Estilo4">RELACI&Oacute;N DE COBRANZA</td>

    </tr>

    <tr>

      <td><table width="503" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="237"><div align="right">Sucursal</div></td>

              <td width="100"><select name="select4" style="width:100px;">

              </select></td>

              <td width="27">Folio</td>

              <td width="105"><span class="Tablas">

                <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" readonly="" value="<?=$folio ?>" />

              </span></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            

            <tr>

              <td width="51">Fecha al </td>

              <td width="103"><span class="Tablas">

                <select name="select" style="width:100px;">

                </select>

              </span></td>

              <td width="20">Dia</td>

              <td width="108"><span class="Tablas">

                <input name="dia" type="text" class="Tablas" id="dia" style="width:100px;background:#FFFF99" value="<?=$dia ?>" readonly=""/>

              </span></td>

              <td width="51"><label>Sector</label></td>

              <td width="170"><span class="Tablas">

                <select name="select6" style="width:100px;">

                </select>

              </span></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="51" height="20">Cobrador</td>

              <td width="226"><span class="Tablas">

                <input name="cobrador" type="text" class="Tablas" id="cobrador" style="width:226px" value="<?=$cobrador ?>" />

              </span></td>

              <td width="226"><div class="ebtn_buscar"></div></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="250"><table width="502" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="51">Factura</td>

                  <td width="226"><span class="Tablas">

                    <input name="factura" type="text" class="Tablas" id="factura" style="width:226px" value="<?=$factura ?>" />

                  </span></td>

                  <td width="225"><div class="ebtn_buscar"></div></td>

                </tr>

                

              </table></td>

            </tr>

          </table></td>

        </tr>

        

        <tr>

          <td colspan="2"><table width="503" border="0" align="center" cellpadding="0" cellspacing="0">

            <tr>

              <td width="6" height="16" class="formato_columnas_izq"></td>

              <td width="58" align="center"  class="formato_columnas">Cliente</td>

              <td width="56" class="formato_columnas"align="center">Nombre</td>

              <td width="66" class="formato_columnas"align="center">Guia</td>

              <td width="41" class="formato_columnas"align="center">Fecha</td>

              <td width="49" class="formato_columnas"align="center">Fecha Vto</td>

              <td width="45" class="formato_columnas"align="center">Factura</td>

              <td width="64" class="formato_columnas"align="center">importe</td>

              <td width="72" class="formato_columnas"align="center">Saldo Actual</td>

			  <td width="33" class="formato_columnas"align="center"></td>

			  <td width="13" class="formato_columnas_der">&nbsp;</td>

            </tr>

            <tr>

              <td colspan="11" ><div id="detalle" name="detalle" style=" width:495px; height:50px; overflow:auto" align="left">

                  <? $line = 0; ?>

                  <table width="495" border="0" cellspacing="0" cellpadding="0">

                    <?		

			while($line<=200){?>

                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >

                      <td height="16" width="18" ><input name="id" type="hidden" value="<?=$row[id] ?>" /></td>

                      <td width="47" align="center" class="style31"  ><input name="cliente" type="text" class="style2" id="cliente" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>

                      <td width="57" align="center" class="style31"><input name="nombre" type="text" class="style2" id="nombre" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

					  <td width="63" align="center" class="style31"><input name="compromiso" type="text" class="style2" id="compromiso" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="44" class="style31" align="center"><input name="fecha" type="text" class="style2" id="fecha" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="11" /></td>

                      <td width="50" align="center" class="style31"><input name="vto" type="text" class="style2" id="vto" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="44" align="center" class="style31"><input name="factura" type="text" class="style2" id="factura" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="62" align="center" class="style31"><input name="importe" type="text" class="style2" id="importe" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

                      <td width="71" align="center" class="style31"><input name="saldoactual" type="text" class="style2" id="saldoactual" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

					  <td width="39" align="center" class="style31"><input name="cobrar" type="checkbox" class="style2" id="cobrar" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>

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

          <td colspan="2"><table width="503" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="51">Cliente</td>

              <td><span class="Tablas">

                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:214px;background:#FFFF99" readonly="" value="<?=$cliente ?>" />

              </span></td>

              <td width="122">Importe Total a Cobrar </td>

              <td width="89"><span class="Tablas">

                <input name="importe" type="text" class="Tablas" id="importe" style="width:80px;background:#FFFF99" readonly="" value="<?=importe ?>" />

              </span></td>

            </tr>

          </table></td>

        </tr>

        

        <tr>

          <td align="right">&nbsp;</td>

          <td>&nbsp;</td>

        </tr>

        <tr>

          <td width="433" align="right"><div class="ebtn_guardar"></div></td>

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