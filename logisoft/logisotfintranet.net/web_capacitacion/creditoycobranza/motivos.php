<? 

	session_start();

	require_once('../Conectar.php');

	$conexion = Conectarse('webpmm');

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Documento sin t&iacute;tulo</title>



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



<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />



<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />



<style type="text/css">



<!--



.Estilo4 {font-size: 12px}



-->



</style>



<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>



<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>



<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>



<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>



<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>



<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">



<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">



<script>



	var u = document.all;



	function guardarDatos(){

		var datos = Object();

		if(u.clasi.value == "0"){

			alerta("Proporcione la clasificación","¡Atencion!","clasi");

			return false;

		}

		datos.clasificacion = 	document.all.clasi.options[document.all.clasi.options.selectedIndex].text;

		parent.actualizarFila(datos);

	}



</script>



</head>



<body>



<form id="form1" name="form1" method="post" action="">



<br>



<table width="250" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">



  <tr>



    <td width="375" class="FondoTabla Estilo4">Motivos No Revision</td>



  </tr>



  <tr>



    <td><table width="333" border="0" cellpadding="0" cellspacing="0">

<tr>



        <td width="413"><div align="left">Clasificaci&oacute;n: 



     <select name="clasi" id="clasi" style="width:250px">



                  <option value="0" style="text-transform:none" >SELECCIONAR</option>



                  <?            



                    $s = "SELECT id,descripcion FROM catalogomotivos WHERE clasificacion='NO REVISION FACTURAS'";

                    $sq = mysql_query($s,$conexion) or die($s);



					while($row = mysql_fetch_row($sq))



					{ 



					?>



                  <option value="<?=$row[0]?>" <?=$row[descripcion] == $row[0] ? "selected" : "" ?>>



                  <?=$row[1]?>



                  </option>



                  <?



					}



					?>



                </select>

        </div></td>

      </tr>



      <tr>



        <td>&nbsp;</td>

      </tr>



      <tr>

        <td align="center"><table width="74" align="center">

          <tr>

            <td width="66" ><div class="ebtn_agregar" onclick="guardarDatos()"></div></td>

          </tr>

        </table></td>

      </tr>

</table></td>



  </tr>



</table>



</form>



</body>



</html>