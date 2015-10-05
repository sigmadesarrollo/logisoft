<?	session_start();

	require_once('../Conectar.php');

	$l = Conectarse('webpmm');



	$s = "SELECT date_format(st.fechaposible, '%d/%m/%Y') fechaposible, st.observacionesposible,

	CONCAT(ct.nombre,' ',ct.apellidopaterno,' ',ct.apellidomaterno) AS responsable

	FROM solicitudtelefonica st

	INNER JOIN catalogoempleado ct ON st.responsable = ct.id

	WHERE folio=".$_GET[folio]."";



	$r = mysql_query($s,$l) or die($s);

	$f = mysql_fetch_object($r);

?>

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

.Estilo5 {

	font-size: 9px;

	font-family: tahoma;

	font-style: italic;

}

-->

</style>

<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">

</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

  <table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="338" class="FondoTabla Estilo4">INFORMACI&Oacute;N EXTRA</td>

  </tr>

  <tr>

    <td height="13"><div align="center">

      <p>&nbsp;</p>

      <table width="259" border="0" cellpadding="0" cellspacing="0">

        <tr>

          <td width="352"><table width="338" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="400" height="28"><table width="350" border="0" cellpadding="0" cellspacing="0">

                

                <tr>

                  <td width="89" height="11">Posible Fecha Solucion:</td>

                  <td width="261"><label><span class="Tablas">

                    <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px; background:#FFFF99" readonly="" value="<?=$f->fechaposible ?>" />

                  </span></label></td>

                </tr>

                <tr>

                  <td height="11">Responsable:</td>

                  <td height="11"><span class="Tablas">

                    <input name="responsable" type="text" class="Tablas" id="responsable" style="width:250px; background:#FFFF99" readonly="" value="<?=$f->responsable ?>" />

                  </span></td>

                </tr>

                <tr>

                  <td height="11" valign="top">Comentarios:</td>

                  <td height="11"><textarea class="Tablas" name="comentarios" cols="30" id="comentarios" style="background:#FFFF99; text-transform:uppercase; height:100px; width:250px"><?=$f->observacionesposible ?>

                  </textarea></td>

                </tr>

                <tr>

                  <td height="11" valign="top">&nbsp;</td>

                  <td height="11" align="right">&nbsp;</td>

                </tr>

                <tr>

                  <td height="11" valign="top">&nbsp;</td>

                  <td height="11" align="right"><div class="ebtn_cerrarventana" onClick="parent.VentanaModal.cerrar()"></div></td>

                </tr>

              </table></td>

            </tr>

          </table></td>

        </tr>

      </table>

      <p>&nbsp;</p>

    </div></td>

  </tr>

</table>

<p>&nbsp;</p>

</form>

</body>

</html>