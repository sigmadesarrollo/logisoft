<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />

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

<table width="419" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="554" class="FondoTabla Estilo4">SALDO POR SUCURSAL</td>

  </tr>

  <tr>

    <td height="13"><div align="center">

      <table width="417" border="0" cellpadding="0" cellspacing="0">

        <tr>

          <td width="417" colspan="3"><table width="417" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="304"><div align="right">Sucursal<br>

              </div></td>

              <td width="113"><label>

                <select name="select" style=" width:100px">

                </select>

              </label></td>

            </tr>

          </table></td>

        </tr>

		<tr><td>&nbsp;</td>

		  <td><p>NOTA:<br><em>Este Reporte puede variar debido a los cortes diarios, Fichas de dep&oacute;sito en tr&aacute;nsito.

		      

		      As&iacute; como tambi&eacute;n a las gu&iacute;as canceladas y Facturas canceladas por Otros Conceptos, las cuales se aplican contablemente hasta que en Matriz se reciben los documentos originales. </em></p>

		    </td>

		  <td>&nbsp;</td>

		</tr>

        <tr>

          <td colspan="3"><table width="417" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td>&nbsp;</td>

              <td>&nbsp;</td>

              <td>&nbsp;</td>

              <td>&nbsp;</td>

            </tr>

            <tr>

              <td width="59">Del Periodo</td>

              <td width="107"><select name="select2" style=" width:100px">

              </select></td>

              <td width="56">Al Periodo</td>

              <td width="195"><select name="select3" style=" width:100px">

              </select></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="3"><table width="417" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="42">Corte</td>

              <td width="576"><select name="select4" style=" width:100px">

              </select></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="3"><table width="417" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="20"><label>

                <input name="radiobutton" type="radio" value="radiobutton">

              </label></td>

              <td width="56">Impresora</td>

              <td width="20"><label>

                <input name="radiobutton" type="radio" value="radiobutton">

              </label></td>

              <td width="65">Pantalla<br></td>

              <td width="33">Copias</td>

              <td width="223"><label>

                <input name="copias" type="text" id="copias" style="width:100px" value="<?=copias ?>">

              </label></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td colspan="3">&nbsp;</td>

        </tr>

      </table>

</div></td>

  </tr>

</table>

<p>&nbsp;</p>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'SALDO POR SUCURSAL';

</script>

</html>