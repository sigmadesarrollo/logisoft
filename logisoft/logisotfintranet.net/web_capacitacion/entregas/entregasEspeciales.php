<?	session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript");
	jQuery(function($){	   
	   $('#fechaead').mask("99/99/9999");
	});
	
	window.onload = function(){
		u.guia.focus();
		obtenerGeneral();		
	}

	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","entregasEspeciales_con.php?accion=1&val="+Math.random());
	}
	
	function mostrarGeneral(datos){
		var row = datos.split(",");
		u.folio.value = row[0];
		u.fecha.value = row[1];
		u.sucursal.value = row[2];
	}
	
	function validar(){
		var documento = "";
		var opcion	= 0;		
		
		if(u.opcion1[0].checked == true){		
			if(u.guia.value == ""){
				mens.show("A","Debe capturar Guia","¡Atención!","guia");
				return false;
			}
			
			if(u.h_devuelto.value == "ya existe"){
				mens.show("A","El numero de guia ya fue registrada en una entraga especial EAD","¡Atención!","guia");
				return false;
			}
			
			if(u.h_devuelto.value == "no encontro"){
				mens.show("A","El numero de Guia no existe","¡Atención!","guia");
				return false;
			}
			
			documento = 0;
		}
		
		if(u.opcion1[1].checked == true){
			if(u.guia.value == ""){
				mens.show("A","Debe capturar No.Rastreo","¡Atención!","guia");
				return false;
			}
			
			if(u.h_devuelto.value == "ya existe"){
				mens.show("A","El numero de rastreo ya fue registrado en una entraga especial EAD","¡Atención!","guia");
				return false;
			}
			
			if(u.h_devuelto.value == "no encontro"){
				mens.show("A","El numero de Rastreo no existe","¡Atención!","guia");
				return false;
			}
			
			documento = 1;
		}
		
		if(u.opcion2[0].checked == true){
			opcion = 0;
		}
		
		if(u.opcion2[1].checked == true){
			opcion = 1;
		}
		
		if(u.opcion2[0].checked == false && u.opcion2[1].checked == false && u.persona.value == ""){
			mens.show("A","Debe capturar Persona que requiere EAD","¡Atención!","persona");
			return false;
		}
		
		if(u.fechaead.value == "" || u.fechaead.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha para EAD","¡Atención!","fechaead");
			return false;
		}
		
		if(validarFecha(u.fechaead.value,'fechaead')==false){
			return false;
		}
		
		var f1 = u.fecha.value.split("/");
		var f2 = u.fechaead.value.split("/");
		
		if(f1[0].substr(0,1)=="0"){
			f1[0] = f1[0].substr(1,1);
		}
		if(f1[1].substr(0,1)=="0"){
			f1[1] = f1[1].substr(1,1);
		}
		
		if(f2[0].substr(0,1)=="0"){
			f2[0] = f2[0].substr(1,1);
		}
		if(f2[1].substr(0,1)=="0"){
			f2[1] = f2[1].substr(1,1);
		}
		
		f1 = new Date(f1[2],f1[1],f1[0]);
		f2 = new Date(f2[2],f2[1],f2[0]);
		
		if(f1 >= f2){
			mens.show("A","La fecha para EAD debe ser mayor a la fecha Actual","¡Atención!","fechaead");
			return false;
		}
		u.btnGuardar.style.visibility = "hidden";
		consultaTexto("registro","entregasEspeciales_con.php?accion=2&guia="+u.h_guia.value
		+"&remitente="+u.idremitente.value
		+"&destinatario="+u.iddestinatario.value
		+"&persona="+u.persona.value
		+"&telefono="+u.telefono.value
		+"&fechaead="+u.fechaead.value
		+"&observaciones="+u.observaciones.value
		+"&documento="+documento
		+"&opcion="+opcion
		+"&val="+Math.random());
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			u.folio.value = row[1];
			mens.show("I","La información se guardo satisfactoriamente","¡Atención!");
			u.btnCancelar.style.visibility = "visible";			
		}else{
			u.btnGuardar.style.visibility = "visible";
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
		}
	}
	
	function cancelarEntrega(){
		mens.show('C','¿Esta seguro de cancelar la entrega especial EAD?', '', '', 'confirmarCancelacion()');
	}
	
	function confirmarCancelacion(){
		consultaTexto("registroCancelacion","entregasEspeciales_con.php?accion=3&folio="+u.folio.value
		+"&val="+Math.random());
	}
	
	function registroCancelacion(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","La entrega especial EAD se cancelo satisfactoriamente","¡Atención!");
		}else{
			u.btnCancelar.style.visibility = "visible";
			mens.show("A","Hubo un error al cancelar "+datos,"¡Atención!");
		}
	}
	
	function obtenerFolio(id){
		u.folio.value = id;
		consultaTexto("mostrarDatos","entregasEspeciales_con.php?accion=5&folio="+id+"&val="+Math.random());
	}
	
	function mostrarDatos(datos){		
		if(datos.indexOf("no encontro")<0){
			u.btnGuardar.style.visibility = "hidden";
			var obj 			= eval(convertirValoresJson(datos));
			u.fecha.value 		= obj.principal.fecha;
			u.sucursal.value 	= obj.principal.sucursal;
			u.guia.value		= ((obj.principal.guia != "")? obj.principal.guia : obj.principal.rastreo);			
			u.remitente.value	= obj.principal.nombreremitente;
			u.idremitente.value = obj.principal.remitente;
			u.iddestinatario.value = obj.principal.destinatario;
			u.destinatario.value	= obj.principal.nombredestinatario;
			u.persona.value		= obj.principal.personarequireead;
			u.fechaead.value	= obj.principal.fechaead;
			u.telefono.value	= obj.principal.telefono;
			u.observaciones.value	= obj.principal.observaciones;
			
			if(obj.principal.guia != ""){
				u.opcion1[0].checked = true;
			}else{
				u.opcion1[1].checked = true;
			}
			u.opcion1[0].disabled = true;
			u.opcion1[1].disabled = true;
			if(obj.principal.nombre != ""){
				if(obj.principal.opcion2 == 0){
					u.opcion2[0].checked = true;
				}else{
					u.opcion2[0].checked = true;
				}
			}
			u.opcion2[0].disabled = true;
			u.opcion2[1].disabled = true;
			
			if(obj.principal.estado == 1){
				u.btnCancelar.style.visibility = "visible";
			}
		}else{
			mens.show("A","El folio #"+u.folio.value+" no existe.","¡Atención!","folio");
		}
	}
	
	function obtenerGuiaBlur(noguia){
		consultaTexto("mostrarGuiaBlur","entregasEspeciales_con.php?accion=6&guia="+noguia+"&val="+Math.random());
	}
	
	function mostrarGuiaBlur(datos){
		if(datos.indexOf("ya existe")>-1){
			u.h_devuelto.value = "ya existe";
		}else{
			u.h_devuelto.value = "";
		}
		
		if(datos.indexOf("no encontro")>-1){
			u.h_devuelto.value = "no encontro";
		}else{
			u.h_devuelto.value = "";
		}
	}
	
	function obtenerGuia(noguia){		
		consultaTexto("mostrarGuia","entregasEspeciales_con.php?accion=6&guia="+noguia+"&val="+Math.random());
	}
	
	function mostrarGuia(datos){	
		if(datos.indexOf("ya existe")>-1){
			if(u.opcion1[0].checked == true){
				mens.show("A","El numero de guia ya fue registrada en una entraga especial EAD","¡Atención!","guia");
				return false;
			}else{
				mens.show("A","El numero de rastreo ya fue registrado en una entraga especial EAD","¡Atención!","guia");
				return false;
			}
		}
		
		if(datos.indexOf("no encontro")>-1){
			if(u.opcion1[0].checked == true){
				mens.show("A","El numero de guia no existe","¡Atención!","guia");
			}else{
				mens.show("A","El numero de rastreo no existe","¡Atención!","guia");
			}
		}else{
			var row = datos.split(",");			
			u.h_guia.value 				= row[1];
			u.idremitente.value 		= row[2];
			u.iddestinatario.value 		= row[3];
			u.remitente.value 			= row[4];
			u.destinatario.value 		= row[5];
			u.persona.select();
		}
	}
	
	function limpiar(){
		u.opcion1[0].checked = true;		
		u.opcion1[0].disabled = false;
		u.opcion1[1].disabled = false;
		
		u.opcion2[0].checked = true;
		u.opcion2[0].disabled = false;
		u.opcion2[1].disabled = false;
		u.guia.value = "";
		u.remitente.value = "";
		u.idremitente.value = "";
		u.destinatario.value = "";
		u.iddestinatario.value = "";
		u.persona.value = "";
		u.telefono.value = "";
		u.fechaead.value = "<?=date('d/m/Y') ?>";
		u.observaciones.value = "";
		u.h_devuelto.value = "";
		u.h_guia.value = "";
		u.btnCancelar.style.visibility = "hidden";
		u.btnGuardar.style.visibility = "visible";
		u.guia.focus();
		obtenerGeneral();
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="580" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">ENTREGAS ESPECIALES EAD </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="8%">Folio:</td>
              <td width="19%"><input name="folio" type="text" id="folio" class="Tablas" style="width:70px" onkeypress="if(event.keyCode==13){obtenerFolio(this.value);}"  />
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarEntregaEspecial.php?funcion=obtenerFolio', 600, 550, 'ventana', 'Busqueda');" /></td>
              <td width="7%">Fecha:</td>
              <td width="17%"><input name="fecha" type="text" id="fecha" class="Tablas" style="width:80px; background-color:#FFFF99" readonly="" /></td>
              <td width="49%">Sucursal:
                <input name="sucursal" type="text" id="sucursal" class="Tablas" style="width:192px; background-color:#FFFF99" readonly="" /></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td width="17%" rowspan="3"><p>
            <label>
              <input name="opcion1" type="radio" style="width:13px" value="0" checked="checked" onclick="document.all.guia.value = ''; document.all.guia.focus();" />
              Guia</label>
            <br />
            <label>
              <input type="radio" name="opcion1" value="1" style="width:13px" onclick="document.all.guia.value = ''; document.all.guia.focus();" />
              No. Rastreo</label>
            <br />
          </p></td>
          <td width="23%">&nbsp;</td>
          <td width="19%" rowspan="3"><p>
            <label>
              <input name="opcion2" type="radio" style="width:12px" value="0" checked="checked" />
              Remitente</label>
            <br />
            <label>
              <input type="radio" name="opcion2" value="1" style="width:12px" />
              Destinatario</label>
            <br />
          </p></td>
          <td width="41%">&nbsp;</td>
          </tr>
        <tr>
          <td><label>
            <input name="guia" type="text" id="guia" class="Tablas" style="width:110px" onkeypress="if(event.keyCode==13){obtenerGuia(this.value);}" onkeydown="if(event.keyCode==9){obtenerGuia(this.value);}" onblur="if(this.value!=''){obtenerGuiaBlur(this.value);}"  />
          </label></td>
          <td><input name="remitente" type="text" id="remitente" class="Tablas" style="width:195px; background-color:#FFFF99" readonly="" /></td>
          </tr>
        <tr>
          <td><input name="h_guia" type="hidden" id="h_guia" />
            <input name="h_devuelto" type="hidden" id="h_devuelto" /></td>
          <td><input name="destinatario" type="text" id="destinatario" class="Tablas" style="width:195px; background-color:#FFFF99" readonly="" /></td>
          </tr>
        <tr>
          <td colspan="4">Persona que require EAD: 
            <input name="persona" type="text" id="persona" class="Tablas" style="width:403px" onkeypress="if(event.keyCode==13){document.all.telefono.select();}" /></td>
          </tr>
        <tr>
          <td>Tel&eacute;fono:</td>
          <td><input name="telefono" type="text" id="telefono" class="Tablas" style="width:100px" onkeypress="if(event.keyCode==13){document.all.fechaead.select();}"/></td>
          <td align="right">Fecha:</td>
          <td><label><span class="Estilo6 Tablas">
            <input name="fechaead" type="text" class="Tablas" id="fechaead" style="width:80px" value="<?=date('d/m/Y') ?>" onkeypress="if(event.keyCode==13){validarFecha(this.value, this.name);document.all.observaciones.select();}" onchange="document.all.observaciones.select()" onkeydown="if(event.keyCode==9){validarFecha(this.value, this.name);}" />
            <img src="../img/calendario.gif" alt="Calendario" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fechaead,'dd/mm/yyyy',this)" /></span></label></td>
          </tr>
        
        <tr>
          <td valign="top">Observaciones:            
            <label></label></td>
          <td colspan="3" valign="top"><textarea name="observaciones" style="width:440px; height:70px; text-transform:uppercase" class="Tablas"></textarea></td>
          </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="68%" align="right"><div id="btnCancelar" style="visibility:hidden" class="ebtn_cancelar" onclick="cancelarEntrega()"></div></td>
                <td width="16%" align="right"><div id="btnGuardar" class="ebtn_guardar" onclick="validar()"></div></td>
                <td width="16%" align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
              </tr>
                    </table></td>
          </tr>
      </table>
      <input name="idremitente" type="hidden" id="idremitente" />
      <input name="iddestinatario" type="hidden" id="iddestinatario" /></td>
    </tr>
  </table>
</form>
</body>
</html>
