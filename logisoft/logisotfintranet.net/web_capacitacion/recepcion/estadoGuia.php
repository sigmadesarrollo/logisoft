<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var u	= document.all;
	function limpiarDatos(){
		u.estado.value			= "BUEN ESTADO";
		u.observaciones.value	= "";
	}
	function guardar(){
		if(u.estado.value == 0 || u.estado.value == ""){
			alerta2('Debe capturar Estado','¡Atención!','estado');
		}else if(u.observaciones.value	== ""){
			alerta2('Debe capturar Observaciones','¡Atención!','observaciones');
		}else{
			parent.estadoGuia(u.estado.value,u.observaciones.value);
			parent.VentanaModal.cerrar();
		}
	}
</script>
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
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<br>
<table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="285" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="340" border="0" align="center" cellpadding="0" cellspacing="0">
      
      <tr>
        <td>Estado:</td>
        <td>
          <label>
          <select name="estado" class="Tablas" id="estado" style="width:200px">
		  <option value="BUEN ESTADO">BUEN ESTADO</option>
		   <option value="DAÑADA">DAÑADA</option>		    
          </select>
          </label>
        </td>
        </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Observaciones:</td>
        <td><textarea name="observaciones" class="Tablas" style="width:220px; text-transform:uppercase" id="observaciones"></textarea></td>
      </tr>
      
      <tr>
        <td width="76" valign="top">&nbsp;</td>
        <td width="169"><table border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="84"><div class="ebtn_guardar" onclick="guardar()"></div></td>
            <td width="79"><div class="ebtn_nuevo" onclick="confirmar('&iquest;Desea limpiar los datos?','&iexcl;Atencion!','limpiarDatos()','')"></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table></td></tr>
</table>
</form>
</body>
<script>
	//parent.frames[1].document.getElementById('titulo').innerHTML = 'ESTADOS GUÍA';
</script>
</html>