<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
    <link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
	<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>
<title>Documento sin título</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background: url(../img/fondopestana.gif) repeat-x top;
}
#estiloTitulo{
	font-family:Verdana, Geneva, sans-serif;
	color:#333;
	font-size:14px;
	font-weight:bolder;
}
#estiloSubTitulo{
	font-family:Verdana, Geneva, sans-serif;
	color:#333;
	font-size:12px;
	font-weight:bolder;
}
#estilotexto{
	font-family:Verdana, Geneva, sans-serif;
	color:#666;
	font-size:12px;
	font-weight:bolder;
}
a:link {
	color: #2885B7;
}
a:visited {
	color: #2885B7;
}
a:hover {
	color: #2885B7;
}
a:active {
	color: #2885B7;
}
-->
</style></head>
<body>
<script>
	function guardarPass(){
		if(document.all.passant.value == ""){
			alerta("Proporcione la contraseña anterior","¡ATENCION!", "passant");
			return false;
		}
		if(document.all.newpass1.value == ""){
			alerta("Proporcione la nueva contraseña","¡ATENCION!", "newpass1");
			return false;
		}
		if(document.all.newpass2.value == ""){
			alerta("vuelva a escribir la nueva contraseña","¡ATENCION!", "newpass2");
			return false;
		}
		if(document.all.newpass1.value != document.all.newpass2.value){
			alerta3("La nueva contraseña es diferente");
			return false;
		}
		consultaTexto("respuestaPass","configuracion_con.php?accion=1&oldpass="+document.all.passant.value+"&newpass="+document.all.newpass1.value+"&ran="+Math.random());
	}
	
	function respuestaPass(datos){
		if(datos.indexOf("Error")>-1){
			alerta3(datos,"¡ATENCION!");
		}else{
			info(datos,"¡ATENCION!");
			document.all.passant.value = "";
			document.all.newpass1.value = "";
			document.all.newpass2.value ="";
		}
	}
	
	function guardarPestanas(){
		consultaTexto("respuestaPestanas","configuracion_con.php?accion=2&pestana1="+document.all.pestana1.value
		+"&pestana2="+document.all.pestana2.value
		+"&pestana3="+document.all.pestana3.value
		+"&pestana4="+document.all.pestana4.value
		+"&pestana5="+document.all.pestana5.value
		+"&ran="+Math.random());
	}
	
	function respuestaPestanas(datos){
		if(datos.indexOf("ok")>-1){
			alerta3("La configuración de las pestañas se guardo satisfactoriamente","¡ATENCION!");
		}else{
			info("Hubo un error al guardar la configuracion "+datos,"¡ATENCION!");
		}
	}
	
</script>
<table width="378" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td width="6" height="23">&nbsp;</td>
            <td width="367" id="estiloTitulo"><a href="#x" onclick="cambiopass.style.display='';pestanas.style.display='none';" >Cambiar contraseña</a> &nbsp;&nbsp;&nbsp;&nbsp;<a href="#x" onclick="cambiopass.style.display='none';pestanas.style.display='';" >Configurar Pestañas</a></td>
            <td width="5"></td>
        </tr>
    	<tr>
    	  <td height="215">&nbsp;</td>
    	  <td valign="top">
          	<table id="cambiopass" width="363" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td height="9" colspan="4"></td>
                </tr>
            	<tr>
            	  <td colspan="4" id="estiloSubTitulo">&nbsp;&nbsp;&nbsp;Cambio de Contraseña</td>
           	  </tr>
            	<tr>
            	  <td width="18" height="9px"></td>
            	  <td colspan="2"></td>
            	  <td width="15"></td>
          	  </tr>
            	<tr id="estilotexto">
            	  <td>&nbsp;</td>
            	  <td width="166">Contraseña anterior:</td>
            	  <td width="164"><input type="password" name="passant" width="150px" /></td>
            	  <td></td>
          	  </tr>
            	<tr>
            	  <td>&nbsp;</td>
            	  <td></td>
            	  <td></td>
            	  <td></td>
          	  </tr>
            	<tr id="estilotexto">
            	  <td>&nbsp;</td>
            	  <td>Contraseña nueva:</td>
            	  <td><input type="password" name="newpass1" width="150px" /></td>
            	  <td></td>
          	  </tr>
            	<tr id="estilotexto">
            	  <td>&nbsp;</td>
            	  <td>Repita la Contraseña:</td>
            	  <td><input type="password" name="newpass2" width="150px" /></td>
            	  <td></td>
          	  </tr>
            	<tr>
            	  <td>&nbsp;</td>
            	  <td colspan="2" align="center">&nbsp;</td>
            	  <td></td>
          	  </tr>
            	<tr>
            	  <td>&nbsp;</td>
            	  <td colspan="2" align="center"><img src="../../img/Boton_Guardar.gif" style="cursor:hand" onclick="guardarPass()" /></td>
            	  <td></td>
          	  </tr>
            </table>
          	<table style="display:none" id="pestanas" width="363" cellpadding="0" cellspacing="0" border="0">
          	  <tr>
          	    <td height="9" colspan="4"></td>
       	      </tr>
          	  <tr>
          	    <td colspan="4" id="estiloSubTitulo">&nbsp;&nbsp;&nbsp;Configuración de las pestañas</td>
       	      </tr>
            	<tr>
            	  <td width="18" height="9px"></td>
            	  <td colspan="2"></td>
            	  <td width="15"></td>
          	  </tr>
          	  <tr id="estilotexto">
          	    <td>&nbsp;</td>
          	    <td colspan="2">Seleccione los modulos que desea ver al entrar al sistema.</td>
          	    <td></td>
       	      </tr>
            	<tr>
            	  <td width="18" height="9px"></td>
            	  <td colspan="2"></td>
            	  <td width="15"></td>
          	  </tr>
              <tr id="estilotexto">
          	    <td>&nbsp;</td>
          	    <td width="91">Pestaña 1</td>
          	    <td width="239">
                	<select style="width:230px" id="pestana1" name="pestana1">
                    	<option value="pestana.php">Default</option>
                    	<option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
                        <option value="../../catalogos/cliente/client.php">Clientes</option>
                        <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
						<option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
						<option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
                	</select>
                </td>
          	    <td></td>
       	      </tr>
          	  <tr id="estilotexto">
          	    <td>&nbsp;</td>
          	    <td>Pestaña 2</td>
          	    <td>
                	<select style="width:230px" id="pestana2" name="pestana2">
                    	<option value="pestana.php">Default</option>
                    	<option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
                        <option value="../../catalogos/cliente/client.php">Clientes</option>
                        <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
						<option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
						<option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
                	</select>
                </td>
          	    <td></td>
       	      </tr>
          	  <tr id="estilotexto">
          	    <td>&nbsp;</td>
          	    <td>Pestaña 3</td>
          	    <td>
                	<select style="width:230px" id="pestana3" name="pestana3">
                    	<option value="pestana.php">Default</option>
                    	<option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
                        <option value="../../catalogos/cliente/client.php">Clientes</option>
                        <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
						<option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
						<option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
                	</select></td>
          	    <td></td>
       	      </tr>
          	  <tr id="estilotexto">
          	    <td>&nbsp;</td>
          	    <td>Pestaña 4</td>
          	    <td><select style="width:230px" id="pestana4" name="pestana4">
                    	<option value="pestana.php">Default</option>
                    	<option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
                        <option value="../../catalogos/cliente/client.php">Clientes</option>
                        <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
						<option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
						<option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
                	</select></td>
          	    <td></td>
       	      </tr>
			  <tr id="estilotexto">
          	    <td>&nbsp;</td>
          	    <td>Pestaña 5</td>
          	    <td><select style="width:230px" id="pestana5" name="pestana5">
                    	<option value="pestana.php">Default</option>
                    	<option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
                        <option value="../../catalogos/cliente/client.php">Clientes</option>
                        <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
						<option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
						<option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
                	</select></td>
          	    <td></td>
       	      </tr>
          	  <tr>
          	    <td>&nbsp;</td>
          	    <td colspan="2" align="center"><img src="../../img/Boton_Guardar.gif" style="cursor:hand" onclick="guardarPestanas()" /></td>
          	    <td></td>
       	      </tr>
          </table></td>
    	  <td></td>
  	  </tr>
    </table>
</body>
</html>