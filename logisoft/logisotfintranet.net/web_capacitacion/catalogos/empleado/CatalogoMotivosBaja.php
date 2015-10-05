<?	session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	$usuario=$_SESSION[NOMBREUSUARIO];

	$motivos=$_GET['motivos'];

?>











<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Motivos Baja</title>



<link href="FondoTabla.css" rel="stylesheet" type="text/css">



<link href="Tablas.css" rel="stylesheet" type="text/css">



<link href="puntovta.css" rel="stylesheet" type="text/css">











<style type="text/css">



<!--



.Button {margin: 0;



padding: 0;



border: 0;



background-color: transparent;



width:70px;



height:20px;



}



-->



</style>











<script language="javascript">



function validar(){



	if(document.getElementById().value==""){



			alert('Debe de capturar motivos baja');



		}



}



</script>















</head>







<body onload="document.all.motivos.focus();">



<form id="form1" name="form1" method="post">



  <p>&nbsp;</p>



  <table width="251" height="0" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">



    <tr>



      <td height="13" class="FondoTabla">CAT&Aacute;LOGO DE MOTIVOS BAJA</td>



    </tr>



    <tr>



      <td height="106"><table width="250" height="71" border="0" cellpadding="0">



          <tr>



            <th width="392" height="35" scope="row"><label>



              <textarea name="motivos" class="Tablas" style="width:250px; height:100px; text-transform:uppercase; font:tahoma" id="motivos"><?=$motivos ?></textarea>



            </label></th>



          </tr>



          <tr>



            <th scope="row"><table width="0" border="0" align="right" cellpadding="1">



                <tr>



                  <td><button type="button" class="Button" onclick="window.parent.OptenerMotivos(document.getElementById('motivos').value);parent.VentanaModal.cerrar();" ><img src="../../img/Boton_Agregari.gif" alt="enviar" width="70" height="20" /></button></td>



                  <td></td>



                </tr>



            </table></th>



          </tr>



      </table></td>



    </tr>



  </table>



</form>



</body>



</html>



<? //} ?>