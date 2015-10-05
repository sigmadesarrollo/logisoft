<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<title>Untitled Document</title>


<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css">


<style type="text/css">


<!--


.Tablas {	font-family: tahoma;


	font-size: 9px;


	font-style: normal;


	font-weight: bold;


}


-->


</style>


</head>





<body>


<table width="242" height="99"  border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">


  <tr>


    <td class="FondoTabla">Elija el formato de impresión</td>


</tr>


<tr>


    <td height="81">


      <table width="238" height="75" border="0" align="left" cellpadding="0" cellspacing="0" id="tab">


        <tr>


          <td width="112" hei class="Tablas"></td>


          <td width="31" class="Tablas"></td>


          <td width="56" class="Tablas"></td>


          <td width="54" class="Tablas"></td>


          <td width="93" class="Tablas"></td>


          <td width="34"></td>


        </tr>


        <tr>


          <td class="Tablas">Pantalla</td>


          <td class="Tablas" align="center"><img src="../img/AdobeReader.gif" width="28" height="29" /></td>


          <td class="Tablas"><input type="radio" checked="checked" name="imprimir" value="Pantalla" /></td>


          <td class="Tablas"></td>


          <td class="Tablas"></td>


          <td></td>


        </tr>


        <tr>


          <td class="Tablas">Impreso</td>


          <td class="Tablas" align="center"><img src="../img/impresora.gif" width="29" height="29" /></td>


          <td class="Tablas"><input type="radio" name="imprimir" value="Impreso" /></td>


          <td class="Tablas">&nbsp;</td>


          <td class="Tablas">&nbsp;</td>


          <td></td>


        </tr>


        <tr>


          <td class="Tablas">Archivo</td>


          <td class="Tablas" align="center"><img src="../img/excel.gif" width="22" height="22" /></td>


          <td class="Tablas"><input type="radio" name="imprimir" value="Archivo" /></td>


          <td class="Tablas">&nbsp;</td>


          <td class="Tablas">&nbsp;</td>


          <td></td>


        </tr>


        <tr>


          <td colspan="6" class="Tablas" align="center"><div class="ebtn_imprimir" onclick="elegirImpresion()"></div></td>


</tr>


</table></td>


  </tr>


</table>


	<p>


	  <script>


		function elegirImpresion(){


			var valor = "";


			for(var i=0; i<3; i++){


				if(document.all.imprimir[i].checked)


					valor= document.all.imprimir[i].value;


			}


			parent.<?=$_GET[funcion]?>(valor);


			parent.VentanaModal.cerrar();


		}


	</script>


</p>


	<p>&nbsp;</p>


	<p>&nbsp;</p>


	<p>&nbsp;</p>


</body>


</html>


