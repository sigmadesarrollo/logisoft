<? session_start();
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../../javascript/ClaseMensajes.js"></script>
<script>

	var mens = new ClaseMensajes();
	mens.iniciar('../../javascript');
	
	window.onload = function(){
		document.getElementById('colonia').focus();
	}
	
	function obtenerColonia(){		
		if(document.getElementById('colonia').value != "" || document.getElementById('ciudad').value != "")
			ConsultaColoniaClientes(document.getElementById('colonia').value, document.getElementById('ciudad').value);
		else
			mens.show("A","Debe capturar colonia o ciudad","¡Atención!");
	}	
	
	function LimpiarMensaje(){
		window.opener.document.getElementById('abierto').value = "";
	}
	
</script>
<script src="selectClientes.js"></script> 
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css">

<title>Buscar Colonias</title>
</head>

<body onUnload="window.opener.document.getElementById('abierto').value =''">
<form id="form1" name="form1" method="post" action="">
  <table width="500" height="100"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td colspan="5" class="FondoTabla">Colonia:<input class="Tablas" name="colonia" type="text" id="colonia" style="font-size:9px; font:tahoma; text-transform:uppercase; width:150px " />
        &nbsp;&nbsp;Ciudad:
      <input type="text" name="ciudad" id="ciudad" class="Tablas" style="width:100px; font-size:9px; font:tahoma; text-transform:uppercase;">&nbsp;&nbsp;&nbsp;<img src="../../img/Boton_Generar.gif" style="cursor:pointer" onClick="obtenerColonia()" align="absbottom"> </td>
    </tr>
    <tr>
      <td colspan="5"><div id="txtDir" style="width:100%; height:300px; overflow:scroll;">
          <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="Tablas" id="tab">
          </table>
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>
