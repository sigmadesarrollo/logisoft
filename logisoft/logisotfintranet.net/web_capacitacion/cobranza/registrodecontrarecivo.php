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

  <table width="495" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="491" class="FondoTabla Estilo4">REGISTRO DE CONTRARECIBO</td>

    </tr>

    <tr>

      <td><table width="483" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td colspan="2"><table width="491" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="491"><table width="486" border="0" cellspacing="0" cellpadding="0">

                <tr>

                  <td width="41">Cliente:<br /></td>

                  <td width="82"><span class="Tablas">

                    <input name="cliente" type="text" class="Tablas" id="cliente" style="width:80px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>

                  </span></td>

                  <td width="361"><span class="Tablas">

                    <input name="cliente2" type="text" class="Tablas" id="cliente2" style="width:360px;background:#FFFF99" value="<?=$cliente2 ?>" readonly=""/>

                  </span></td>

                </tr>

              </table></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td width="71"><table width="71" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="71">Contrarecibo:</td>

              </tr>

          </table>            </td>

          <td width="412"><span class="Tablas">

            <input name="contrarecibo" type="text" class="Tablas" id="contrarecibo" style="width:412px" value="<?=$contrarecibo ?>" />

          </span></td>

        </tr>

        <tr>

          <td colspan="2" class="FondoTabla">Observaciones</td>

        </tr>

        <tr>

          <td colspan="2"><label>

            <textarea name="textarea" cols="78"></textarea>

          </label></td>

        </tr>

        <tr>

          <td colspan="2">&nbsp;</td>

        </tr>

        <tr>

          <td colspan="2" align="right"><div class="ebtn_guardar"></div></td>

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

	parent.frames[1].document.getElementById('titulo').innerHTML = 'REGISTRO DE CONTRARECIVO';

</script>

</html>