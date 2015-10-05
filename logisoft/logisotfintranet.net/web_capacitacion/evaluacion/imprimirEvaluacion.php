<?	session_start();


	require_once("../Conectar.php");


	$l = Conectarse("webpmm");


	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS elaboro


	FROM catalogoempleado WHERE id=".$_GET[empleado]."";


	$t = mysql_query($s,$l) or die($s);


	$emp = mysql_fetch_object($t);


	


	$s = "SELECT descripcion FROM catalogosucursal WHERE id=".$_GET[sucursal]."";


	$r = mysql_query($s,$l) or die($s); $suc = mysql_fetch_object($r);


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />


<title>Documento sin t&iacute;tulo</title>


<style type="text/css" media="print">


	.fuente{


		font-family:"Courier New", Courier, monospace;


		font-size:5px;


	}


	h6 { font: 5pt Courier New; padding-top:5px; letter-spacing: 0.4em; }


	h5 { font: 5pt Courier New; padding-top:5px; letter-spacing: 0.3em; }





</style>


<style type="text/css">


<!--


body {


	margin-left: 0px;


	margin-top: 0px;


	margin-right: 0px;


	margin-bottom: 0px;


}


-->


</style></head>


<object id=factory viewastext style="display:none" classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"


  codebase="ScriptX.cab#Version=6,5,439,30">


</object>


<script> 


	window.onload = function (){


		enviarImpresion();


	}


	function enviarImpresion(){


		factory.printing.header = "";


		factory.printing.footer = "";


		factory.printing.portrait = true;


		factory.printing.leftMargin = 0.5;


		factory.printing.topMargin = 0;


		factory.printing.rightMargin = 1;


		factory.printing.bottomMargin = 0;


	  	factory.printing.Print(false);


		window.close();


	}


</script>


<body>


<table width="200" border="0" cellpadding="0" cellspacing="0">


<tr>


 	<td width="266" height="382" valign="top">


	<table width="200" border="0" cellpadding="0" cellspacing="0">


	  <tr>


        <td colspan="2" align="center" height="10px" ><h6>Paquetería &nbsp;y <br /><br />Mensajería&nbsp;En<br /><br />Movimiento</h6></td>


      </tr>	 	 


	   <tr>


        <td colspan="2" align="left" ><h6>Sucursal:<?=$suc->descripcion; ?><br /><br />


		Orden&nbsp;de &nbsp;Embarque:<?=$_GET[evaluacion] ?><br /><br />
		
		Tipo de Entrega:<?=$_GET[entrega] ?><br /><br />


		Elaboró:<br /><br /><?=cambio_texto($emp->elaboro); ?></h6></td>


      </tr>      	   	  


	 <tr>


	    <td colspan="2" >&nbsp;</td>


	    </tr>


	  <tr>


	    <td colspan="2" >&nbsp;</td>


	    </tr>


	  <tr>


        <td colspan="2" >&nbsp;</td>


      </tr> 


    </table>


	</td>


</tr>


</table>


</body>


</html>


