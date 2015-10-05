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
		if(u.precibe.value == ""){
			alerta("Proporcione la persona que recibe","¡Atencion!","precibe");
			return false;
		}
		if(u.tipoid.value == ""){
			alerta("Proporcione la identificacion","¡Atencion!","tipoid");
			return false;
		}
		if(u.nidentificacion.value == ""){
			alerta("Proporcione el numero de identificacion","¡Atencion!","nidentificacion");
			return false;
		}
		datos.nombre = 			document.all.precibe.value;
		datos.identificacion = 	document.all.tipoid.options[document.all.tipoid.options.selectedIndex].text;
		datos.numero_id = 		document.all.nidentificacion.value;
		parent.actualizarFila(datos);
		
	}
</script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<br>
<table width="379" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="375" class="FondoTabla Estilo4">DATOS ENTREGA</td>
  </tr>
  <tr>
    <td><table width="413" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td width="111">Persona que Recibe:</td>
        <td width="302"><input name="precibe" style="width:287px" class="Tablas" type="text" id="precibe" value="<?=$precive ?>" /></td>
      </tr>
      <tr>
        <td>Tipo Identificaci&oacute;n:</td>
        <td><select name="tipoid" class="Tablas" style="width:200px" >
          <option value="">.:: SELECCIONE ::.</option>
          <option value="0">CREDENCIAL DE ELECTOR</option>
          <option value="2">PASAPORTE</option>
          <option value="3">CARTILLA MILITAR</option>
          <option value="4">CEDULA PROFESIONAL</option>
        </select></td>
      </tr>
      <tr>
        <td> No. Identificaci&oacute;n:</td>
        <td><input name="nidentificacion" style="width:100px" class="Tablas" type="text" id="nidentificacion" value="<?=$nidentificacion ?>" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="right"><div class="ebtn_agregar" onclick="guardarDatos()"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>