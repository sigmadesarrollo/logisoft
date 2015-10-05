<?	session_start();	
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS responsable, email FROM catalogoempleado 
	WHERE id = ".$_SESSION[IDUSUARIO]."";
	$r = mysql_query($s,$l) or die($s); $fr = mysql_fetch_object($r);
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
	FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";
	$r = mysql_query($s,$l) or die($s); $fs = mysql_fetch_object($r);	
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion,':',id) AS descripcion
	FROM catalogosucursal ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}		
		$desc = substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/moautocomplete.js"></script>
<script>
	var u = document.all;
	var v_suc	= "<?=$_SESSION[IDSUCURSAL] ?>";
	
	window.onload = function(){
		u.sucursal.focus();
		u.img_cliente.style.visibility = "hidden";
		obtenerGenerales();
	}
	function obtenerGenerales(){
		consultaTexto("mostrarGenerales","centroAtencionTelefonica_con.php?accion=1");
	}
	function mostrarGenerales(datos){
		var row = datos.split(",");
		u.fecha.value = row[0];
		u.folio.value = row[1];		
	}
	function devolverSucursal(){
		if(u.sucursal_hidden.value==""){
			setTimeout("devolverSucursal()",500);
		}
		u.queja.focus();
	}
	function validarCliente(e,obj){
		tecla = (u) ? e.keyCode : e.which;
		if((tecla == 8 || tecla == 46) && document.getElementById(obj).value==""){		
			limpiarCliente();
		}
	}
	function obtenerClienteBusqueda(id){
		u.cliente.value = id;
		//guia_consultajson.php?accion=2&idpagina="+u.idpagina.value+"&idcliente="+valor+"&valrandom="+Math.random()
		consultaTexto("mostrarCliente","../guias/guia_consultajson.php?accion=2&idcliente="+id+"&valrandom="+Math.random());
	}
	function obtenerCliente(e,id){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
		//consulta("mostrarCliente","../recoleccion/recoleccion_con.php?accion=1&cliente="+id+"&valor="+Math.random());
		consultaTexto("mostrarCliente","../guias/guia_consultajson.php?accion=2&idcliente="+id+"&valrandom="+Math.random());
		}
	}
	function mostrarCliente(datos){
		limpiarCliente();
		try{
			var dcliente = eval(datos);
		}catch(e){
			alerta3(datos);
		}
		var u = document.all;
		if(dcliente.cliente!="0"){
			u.nombre.value	= dcliente.cliente.ncliente;
			var endir = dcliente.direcciones.length;
			if(endir>0){
				u.iddireccion.value		= dcliente.direcciones[0].iddireccion;
				u.calle.value 		= dcliente.direcciones[0].calle;
				u.numero.value 		= dcliente.direcciones[0].numero;
				u.cp.value 			= dcliente.direcciones[0].codigopostal;
				u.colonia.value 	= dcliente.direcciones[0].colonia;
				u.poblacion.value 	= dcliente.direcciones[0].poblacion;
				u.telefono.value 	= dcliente.direcciones[0].telefono;
			}
			if(endir>1){
				dirRemi = dcliente.direcciones;
				mostrarDirecciones(dirRemi,u.cliente.value);
				consultaTexto("mostrarHorario","../recoleccion/recoleccion_conj.php?accion=12&cliente="+u.cliente.value+"&valor="+Math.random());
			}			
		}else{			
			alerta('El numero de cliente no existe','¡Atención!','cliente');			
		}
	}
	function mostrarHorario(datos){
		if(datos.indexOf("no encontro")<0){
			var objeto = eval(convertirValoresJson(datos));
			u.hrecoleccion.value = "de "+objeto[0].horario +" a "+objeto[0].horario2;
		}
	}
	function limpiarCliente(){
		u.numero.value 		= ""; u.cp.value 			= "";
		u.colonia.value 	= ""; u.poblacion.value 	= "";
		//u.municipio.value 	= ""; u.telefono.value	 	= ""; 	
		u.hrecoleccion.value= ""; u.nombre.value		= "";
		u.calle.value = "";
	}
	function obtenerEmpleadoBusqueda(id,caja){
		if(caja=="1"){
			u.responsable.value = id;
		}else{
			u.supervisor.value	= id;
		}
		consultaTexto("mostrarEmpleado","centroAtencionTelefonica_con.php?accion=2&caja="+caja
		+"&empleado="+id+"&valor="+Math.random());		
	}
	function obtenerEmpleado(e,id,caja){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
		consultaTexto("mostrarEmpleado","centroAtencionTelefonica_con.php?accion=2&caja="+caja
		+"&empleado="+id+"&valor="+Math.random());
		}
	}
	function mostrarEmpleado(datos){
		var obj = eval(datos);
		if(obj[0].caja == "1"){
			u.nombreresponsable.value	= obj[0].empleado;
			u.emailresponsable.value	= obj[0].email;
			u.supervisor.select();
		}else{
			u.nombresupervisor.value	= obj[0].empleado;
			u.emailsupervisor.value		= obj[0].email;
		}
	}
	function validaEmpleado(e,obj,caja){
		tecla = (u) ? e.keyCode : e.which;
    	if((tecla==8 || tecla==46) && document.getElementById(obj).value==""){
			if(caja == 1){
				u.nombreresponsable.value = "";
				u.emailresponsable.value = "";				
			}else{
				u.nombresupervisor.value = "";
				u.emailsupervisor.value = "";
			}
		}
	}
	function habilitarQuejas(queja){
		if(queja=="0"){
			u.guia.readOnly = true;
			u.guia.style.backgroundColor	='#FFFF99';
			u.recoleccion.readOnly = true;
			u.recoleccion.style.backgroundColor	='#FFFF99';
			u.folioatencion.readOnly = true;
			u.folioatencion.style.backgroundColor	='#FFFF99';
			u.cliente.readOnly = true;
			u.cliente.style.backgroundColor	='#FFFF99';
			u.nombrequeja.readOnly = true;
			u.nombrequeja.style.backgroundColor	='#FFFF99';
			u.telefonoqueja.readOnly = true;
			u.telefonoqueja.style.backgroundColor	='#FFFF99';
			u.emailqueja.readOnly = true;
			u.emailqueja.style.backgroundColor	='#FFFF99';
			u.empresaqueja.readOnly = true;
			u.empresaqueja.style.backgroundColor	='#FFFF99';
			u.observaciones.readOnly = true;
			u.observaciones.style.backgroundColor	='#FFFF99';
			u.img_cliente.style.visibility = "hidden";
			u.foliodano.readOnly = true;
			u.foliodano.style.backgroundColor	='#FFFF99';
			u.cliente.value			= ""; u.nombre.value		= "";
			u.hrecoleccion.value	= ""; u.nombrequeja.value	= "";
			u.telefonoqueja.value	= ""; u.emailqueja.value	= "";
			u.empresaqueja.value	= ""; u.observaciones.value	= "";			
			u.folioatencion.value	= ""; u.guia.value			= "";
			u.recoleccion.value		= ""; u.foliodano.value		= "";
			u.estadoguia.value		= "";
			limpiarCliente();
		
		}else if(queja=="RECOLECCION"){
			u.folioatencion.readOnly = false;
			u.folioatencion.style.backgroundColor	='';
			u.cliente.readOnly = true;
			u.cliente.style.backgroundColor	='#FFFF99';
			u.nombrequeja.readOnly = false;
			u.nombrequeja.style.backgroundColor	='';
			u.telefonoqueja.readOnly = false;
			u.telefonoqueja.style.backgroundColor	='';
			u.emailqueja.readOnly = false;
			u.emailqueja.style.backgroundColor	='';
			u.empresaqueja.readOnly = false;
			u.empresaqueja.style.backgroundColor	='';
			u.observaciones.readOnly = false;
			u.observaciones.style.backgroundColor	='';
			u.guia.readOnly = true;
			u.guia.style.backgroundColor	='#FFFF99';
			u.recoleccion.readOnly = true;
			u.recoleccion.style.backgroundColor	='#FFFF99';
			u.img_cliente.style.visibility = "hidden";
			u.foliodano.readOnly = true;
			u.foliodano.style.backgroundColor	='#FFFF99';
			u.cliente.value			= ""; u.nombre.value		= "";
			u.hrecoleccion.value	= ""; u.nombrequeja.value	= "";
			u.telefonoqueja.value	= ""; u.emailqueja.value	= "";
			u.empresaqueja.value	= ""; u.observaciones.value	= "";			
			u.folioatencion.value	= ""; u.guia.value			= "";
			u.recoleccion.value		= ""; u.foliodano.value		= "";
			u.estadoguia.value		= "";
			limpiarCliente();
			
		}else if(queja=="EAD MAL EFECTUADAS"){
			u.guia.readOnly = false;
			u.guia.style.backgroundColor	='';
			u.recoleccion.readOnly = false;
			u.recoleccion.style.backgroundColor	='';
			u.nombrequeja.readOnly = false;
			u.nombrequeja.style.backgroundColor	='';
			u.telefonoqueja.readOnly = false;
			u.telefonoqueja.style.backgroundColor	='';
			u.emailqueja.readOnly = false;
			u.emailqueja.style.backgroundColor	='';
			u.empresaqueja.readOnly = false;
			u.empresaqueja.style.backgroundColor	='';
			u.observaciones.readOnly = false;
			u.observaciones.style.backgroundColor	='';
			u.folioatencion.readOnly = true;
			u.folioatencion.style.backgroundColor	='#FFFF99';
			u.cliente.readOnly = true;
			u.cliente.style.backgroundColor	='#FFFF99';
			u.img_cliente.style.visibility = "hidden";
			u.foliodano.readOnly = true;
			u.foliodano.style.backgroundColor	='#FFFF99';
			u.cliente.value			= ""; u.nombre.value		= "";
			u.hrecoleccion.value	= ""; u.nombrequeja.value	= "";
			u.telefonoqueja.value	= ""; u.emailqueja.value	= "";
			u.empresaqueja.value	= ""; u.observaciones.value	= "";			
			u.folioatencion.value	= ""; u.guia.value			= "";
			u.recoleccion.value		= ""; u.foliodano.value		= "";
			u.estadoguia.value		= "";
			limpiarCliente();
		}else if(queja=="CONVENIOS NO APLICADOS"){
			u.foliodano.readOnly = true;
			u.foliodano.style.backgroundColor	='#FFFF99';
			u.guia.readOnly = false;
			u.guia.style.backgroundColor	='';
			u.cliente.readOnly = true;
			u.cliente.style.backgroundColor	='#FFFF99';
			u.nombrequeja.readOnly = false;
			u.nombrequeja.style.backgroundColor	='';
			u.telefonoqueja.readOnly = false;
			u.telefonoqueja.style.backgroundColor	='';
			u.emailqueja.readOnly = false;
			u.emailqueja.style.backgroundColor	='';
			u.empresaqueja.readOnly = false;
			u.empresaqueja.style.backgroundColor	='';
			u.observaciones.readOnly = false;
			u.observaciones.style.backgroundColor	='';
			u.recoleccion.readOnly = true;
			u.recoleccion.style.backgroundColor	='#FFFF99';
			u.img_cliente.style.visibility = "hidden";
			u.cliente.value			= ""; u.nombre.value		= "";
			u.hrecoleccion.value	= ""; u.nombrequeja.value	= "";
			u.telefonoqueja.value	= ""; u.emailqueja.value	= "";
			u.empresaqueja.value	= ""; u.observaciones.value	= "";			
			u.folioatencion.value	= ""; u.guia.value			= "";
			u.recoleccion.value		= ""; u.foliodano.value		= "";
			u.estadoguia.value		= "";
			limpiarCliente();
		}else if(queja=="OTROS SERVICIOS"){
			u.foliodano.readOnly = true;
			u.foliodano.style.backgroundColor	='#FFFF99';
			u.guia.readOnly = true;
			u.guia.style.backgroundColor ='#FFFF99';
			u.cliente.readOnly = false;
			u.cliente.style.backgroundColor	='';
			u.nombrequeja.readOnly = false;
			u.nombrequeja.style.backgroundColor	='';
			u.telefonoqueja.readOnly = false;
			u.telefonoqueja.style.backgroundColor	='';
			u.emailqueja.readOnly = false;
			u.emailqueja.style.backgroundColor	='';
			u.empresaqueja.readOnly = false;
			u.empresaqueja.style.backgroundColor	='';
			u.observaciones.readOnly = false;
			u.observaciones.style.backgroundColor	='';
			u.recoleccion.readOnly = true;
			u.recoleccion.style.backgroundColor	='#FFFF99';
			u.img_cliente.style.visibility = "visible";
			u.cliente.value			= ""; u.nombre.value		= "";
			u.hrecoleccion.value	= ""; u.nombrequeja.value	= "";
			u.telefonoqueja.value	= ""; u.emailqueja.value	= "";
			u.empresaqueja.value	= ""; u.observaciones.value	= "";			
			u.folioatencion.value	= ""; u.guia.value			= "";
			u.recoleccion.value		= ""; u.foliodano.value		= "";
			u.estadoguia.value		= "";
			limpiarCliente();
		}else if(queja=="QUEJAS DANOS Y FALTANTES"){
			u.foliodano.readOnly = false;
			u.foliodano.style.backgroundColor	='';
			u.guia.readOnly = true;
			u.guia.style.backgroundColor ='#FFFF99';
			u.cliente.readOnly = true;
			u.cliente.style.backgroundColor	='#FFFF99';
			u.nombrequeja.readOnly = false;
			u.nombrequeja.style.backgroundColor	='';
			u.telefonoqueja.readOnly = false;
			u.telefonoqueja.style.backgroundColor	='';
			u.emailqueja.readOnly = false;
			u.emailqueja.style.backgroundColor	='';
			u.empresaqueja.readOnly = false;
			u.empresaqueja.style.backgroundColor	='';
			u.observaciones.readOnly = false;
			u.observaciones.style.backgroundColor	='';
			u.recoleccion.readOnly = true;
			u.recoleccion.style.backgroundColor	='#FFFF99';
			u.img_cliente.style.visibility = "hidden";
			u.folioatencion.readOnly = true;
			u.folioatencion.style.backgroundColor = '#FFFF99';
			u.cliente.value			= ""; u.nombre.value		= "";
			u.hrecoleccion.value	= ""; u.nombrequeja.value	= "";
			u.telefonoqueja.value	= ""; u.emailqueja.value	= "";
			u.empresaqueja.value	= ""; u.observaciones.value	= "";			
			u.folioatencion.value	= ""; u.guia.value			= "";
			u.recoleccion.value		= ""; u.foliodano.value		= "";
			u.estadoguia.value		= "";
			limpiarCliente();	
		}
	}
	function limpiar(){
		u.estado.value			= ""; u.sucursal.value		= "";
		u.sucursal_hidden.value	= ""; u.queja.value			= 0;
		u.folioatencion.value	= ""; u.guia.value			= "";
		u.recoleccion.value		= ""; u.cliente.value		= "";
		u.nombre.value			= ""; u.calle.value			= "";
		u.numero.value			= ""; u.colonia.value		= "";
		u.cp.value				= ""; u.poblacion.value		= "";
		//u.municipio.value		= ""; 
		u.telefono.value		= "";
		u.hrecoleccion.value	= ""; u.nombrequeja.value	= "";
		u.telefonoqueja.value	= ""; u.emailqueja.value	= "";
		u.empresaqueja.value	= ""; u.observaciones.value	= "";
		u.responsable.value		= ""; u.nombreresponsable.value	= "";
		u.emailresponsable.value= ""; u.supervisor.value		= "";
		u.nombresupervisor.value= ""; u.emailsupervisor.value	= "";
		u.accion.value			= ""; u.img_cliente.style.visibility = "hidden";
		u.foliodano.value		= ""; u.estadoguia.value = "";
		obtenerGenerales();
		habilitarQuejas(0);
	}
	function validar(){
		<?=$cpermiso->verificarPermiso(294,$_SESSION[IDUSUARIO]);?>
		if(u.sucursal_hidden.value == undefined || u.sucursal.value == ""){
			alerta('Debe capturar Sucursal','¡Atención!','sucursal');
			return false;		
		}
		if(u.queja.value==0){
			alerta('Debe capturar Queja','¡Atención!','queja');
				return false;
		}
		if(u.queja.value=="RECOLECCION"){
			if(u.folioatencion.value==""){
				alerta('Debe capturar Folio de atención telefonica','¡Atención!','folioatencion');
				return false;
			} 
		}else if(u.queja.value=="EAD MAL EFECTUADAS"){
			if(u.guia.readOnly==false){
				if(u.guia.value==""){
					alerta('Debe capturar No. Guia','¡Atención!','guia');
					return false;
				}
			}else if(u.recoleccion.readOnly==false){
				if(u.recoleccion.value==""){
					alerta('Debe capturar No. Recolección','¡Atención!','recoleccion');
					return false;
				}
			}
		}else if(u.queja.value=="CONVENIOS NO APLICADOS"){
			if(u.guia.value==""){
				alerta('Debe capturar No. Guia','¡Atención!','guia');
				return false;
			}
		}else if(u.queja.value=="OTROS SERVICIOS"){
			if(u.cliente.value==""){
				alerta('Debe capturar Cliente','¡Atención!','cliente');
				return false;
			}
		}
		
		if(u.nombrequeja.value == ""){
			alerta('Debe capturar Nombre de quien levanta la queja','¡Atención!','nombrequeja');
		}else if(u.telefonoqueja.value == ""){
			alerta('Debe capturar Telefono de quien levanta la queja','¡Atención!','telefonoqueja');
		}else if(u.responsable.value == ""){
			alerta('Debe capturar Responsable','¡Atención!','responsable');	
		}else if(u.supervisor.value == ""){
			alerta('Debe capturar Supervisor','¡Atención!','supervisor');
		}else if(u.emailqueja.value!="" && !isEmailAddress(u.emailqueja)){
			alerta('Debe capturar Email valido de quien levanta la queja', '¡Atención!','emailqueja');
			return false;
		}else{
			u.d_guardar.style.visibility = "hidden";
			
			if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no"){
				u.sucursal_hidden.value = v_suc;
			}
			
			var arr = new Array();
			arr[0] = u.fecha.value;
			arr[1] = u.sucursal_hidden.value;
			arr[2] = u.queja.value;
			arr[3] = u.folioatencion.value;
			arr[4] = u.guia.value;
			arr[5] = u.recoleccion.value;
			arr[6] = u.nombrequeja.value;
			arr[7] = u.telefonoqueja.value;
			arr[8] = u.emailqueja.value;
			arr[9] = u.empresaqueja.value;
			arr[10] = u.observaciones.value;
			arr[11] = u.responsable.value;
			arr[12] = u.supervisor.value;
			arr[13] = u.cliente.value;
			arr[14] = ((u.foliodano.value=="")?0:u.foliodano.value);
			arr[15] = u.iddireccion.value;
			if(u.accion.value==""){
				consultaTexto("registroQueja","centroAtencionTelefonica_con.php?accion=5&arre="+arr
				+"&direccion2="+u.emailresponsable.value+"&direccion3="+u.emailsupervisor.value
				+"&queja="+u.queja.value+"&val="+Math.random());
			}else{
				consultaTexto("cambiosQueja","centroAtencionTelefonica_con.php?accion=6&arre="+arr
				+"&folio="+u.folio.value
				+"&actividad="+u.folioactividad.value
				+"&responsable="+u.h_responsable.value
				+"&queja="+u.queja.value+"&val="+Math.random());
			}
		}
	}
	function registroQueja(datos){
		if(datos.indexOf("ok")>-1){
			var folio = datos.split(",");
			u.estado.value = "POR SOLUCIONAR";
			u.folio.value = folio[1];
			u.folioactividad.value = folio[3];
			u.accion.value = "modificar";
			u.d_guardar.style.visibility = "visible";
			info('Los datos han sido guardados correctamente','');
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3('Hubo un error al guardar '+datos,'¡Atención!');
		}
	}
	function cambiosQueja(datos){
		if(datos.indexOf("ok")>-1){
			u.d_guardar.style.visibility = "visible";
			info('Los cambios han sido guardados correctamente','');
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3('Hubo un error al guardar '+datos,'¡Atención!');
		}
	}
	function obtenerFolioAtencionBusqueda(folio){
		u.folioatencion.value = folio;
		consultaTexto("mostrarFolioAtencion","centroAtencionTelefonica_con.php?accion=3&folio="+folio+"&sucursal="+u.sucursal_hidden.value);
	}
	function obtenerFolioAtencion(e,folio){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && u.folioatencion.value != ""){
			if(u.sucursal.value!=""){
				u.folioatencion.value = folio;
				u.nombrequeja.select();
				consultaTexto("mostrarFolioAtencion","centroAtencionTelefonica_con.php?accion=3&folio="+folio
				+"&sucursal="+u.sucursal_hidden.value);
			}else{
				alerta('Debe capturar Sucursal','¡Atención!','sucursal');
			}
		}
	}
	function mostrarFolioAtencion(datos){
		if(datos.indexOf("ya existe")>-1){
			var r = datos.split(",");
			alerta('El Folio de atención a cliente ya fue registrado en otra solicitud con el folio #'+r[1],'¡Atención!','folioatencion');
			return false;
		}
		
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.cliente.value		= obj.principal.cliente;
			u.nombre.value 		= obj.principal.nombre;
			u.numero.value 		= obj.principal.numero;
			u.cp.value 			= obj.principal.cp;
			u.colonia.value 	= obj.principal.colonia;
			u.poblacion.value 	= obj.principal.poblacion;
			//u.municipio.value 	= obj.principal.municipio;
			u.telefono.value 	= obj.principal.telefono2;
			u.calle.value 		= obj.principal.calle;
			u.estadoguia.value	= obj.principal.estador;
			u.nombrequeja.select();			
			consultaTexto("mostrarHorario","../recoleccion/recoleccion_conj.php?accion=12&cliente="+u.cliente.value
			+"&valor="+Math.random());
		}else{
			u.folioatencion.value = "";
			alerta('El Folio de atención a cliente no existe en la sucursal seleccionada','¡Atención!','folioatencion');
		}
	}
	function obtenerRecoleccionEvaluacionBusqueda(folio){
		u.recoleccion.value = folio;
		u.guia.readOnly = true;
		consultaTexto("mostrarRecoleccionEvaluacion","centroAtencionTelefonica_con.php?accion=4&folio="+folio+"&sucursal="+u.sucursal_hidden.value);
	}
	function obtenerRecoleccionEvaluacion(e,folio){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && u.recoleccion.value != ""){
			u.recoleccion.value = folio;
			consultaTexto("mostrarRecoleccionEvaluacion","centroAtencionTelefonica_con.php?accion=4&folio="+folio+"&sucursal="+u.sucursal_hidden.value);
		}
	}
	function mostrarRecoleccionEvaluacion(datos){
		if(datos.indexOf("ya existe")>-1){
			var r = datos.split(",");
			alerta('El Folio de Recolección ya fue registrado en otra solicitud con el folio #'+r[1],'¡Atención!','recoleccion');
			return false;
		}
		if(datos.indexOf("ok")>-1){
			var cliente = datos.split(",");
			u.cliente.value = cliente[1];
			u.nombrequeja.select();
			consulta("mostrarCliente","../recoleccion/recoleccion_con.php?accion=1&cliente="+cliente[1]+"&valor="+Math.random());
		}else{
			u.recoleccion.value = "";
			u.recoleccion.select();
			alerta('El Folio de recoleccion no existe en la sucursal seleccionada','¡Atención!','recoleccion');
		}
	}
	function habilitar(e,nombre){
		tecla = (u) ? e.keyCode : e.which;
		if(u.queja.value=="EAD MAL EFECTUADAS"){
			if(nombre=="recoleccion"){
				if((tecla==8 || tecla==46) && document.getElementById(nombre).value==""){
					document.getElementById('guia').style.backgroundColor='';
					document.getElementById('guia').disabled=false;
					document.getElementById('guia').readOnly=false;
				}else if(document.getElementById(nombre).value!=""){
					document.getElementById('guia').style.backgroundColor='#FFFF99';
					document.getElementById('guia').disabled=true;
				}
			}else if(nombre=="guia"){
				if((tecla==8 || tecla==46) && document.getElementById(nombre).value==""){
					document.getElementById('recoleccion').style.backgroundColor='';		
					document.getElementById('recoleccion').disabled=false;
					document.getElementById('recoleccion').readOnly=false;
				}else if(document.getElementById(nombre).value!=""){
					document.getElementById('recoleccion').style.backgroundColor='#FFFF99';
					document.getElementById('recoleccion').disabled=true;
				}
			}
		}
	}	
	function obtenerFolioQuejaTel(folio){
		u.folio.value = folio;
		consultaTexto("mostrarDatosQueja","centroAtencionTelefonica_con.php?accion=9&folio="+folio);		
	}
	function mostrarDatosQueja(datos){
		var obj = eval(convertirValoresJson(datos));
		u.fecha.value			= obj[0].fechaqueja;
		u.estado.value			= obj[0].estado; 
		u.sucursal.value		= obj[0].descripcionsucursal;
		u.sucursal_hidden.value	= obj[0].sucursal; 
		u.queja.value			= obj[0].queja;
		habilitarQuejas(u.queja.value);
		u.folioatencion.value	= obj[0].folioatencion; 
		u.guia.value			= obj[0].guia;
		u.recoleccion.value		= obj[0].recoleccion; 
		u.cliente.value			= obj[0].cliente;		
		u.nombrequeja.value		= obj[0].nombre;
		u.telefonoqueja.value	= obj[0].telefono; 
		u.emailqueja.value		= obj[0].email;
		u.empresaqueja.value	= obj[0].empresa; 
		u.observaciones.value	= obj[0].observaciones;
		u.responsable.value		= obj[0].responsable; 
		u.nombreresponsable.value	= obj[0].nombreresponsable;
		u.emailresponsable.value= obj[0].emailresponsable; 
		u.supervisor.value		= obj[0].supervisor;
		u.nombresupervisor.value= obj[0].nombresupervisor; 
		u.emailsupervisor.value	= obj[0].emailsupervisor;
		u.accion.value			= "modificar";
		v_suc					= obj[0].sucursal;
		u.folioactividad.value		= obj[0].folioactividad;
		u.h_responsable.value	= obj[0].responsable;
		consultaTexto("mostrarCliente","centroAtencionTelefonica_con.php?accion=15&folio="+u.folio.value+"&valor="+Math.random());
	}
	function obtenerFolioQueja(folio){
		u.foliodano.value = folio;
		if(u.sucursal_hidden.value!=""){
			consultaTexto("mostrarDatosModuloQueja","centroAtencionTelefonica_con.php?accion=10&sucursal="+u.sucursal_hidden.value
			+"&folio="+folio);
		}else{
			alerta("Debe capturar Sucursal","¡Atención!","sucursal");
		}
	}
	function mostrarDatosModuloQueja(datos){
		var obj = eval(convertirValoresJson(datos));
		u.fecha.value			= obj[0].fecha;
		u.estado.value			= obj[0].estado; 
		u.sucursal.value		= obj[0].sucursal;
		u.sucursal_hidden.value	= obj[0].idsucursal;		
		u.guia.value			= obj[0].nguia;		
		u.cliente.value			= obj[0].cliente;		
		u.nombrequeja.value		= obj[0].nombre;		
		u.observaciones.value	= obj[0].observaciones;
		u.responsable.value		= obj[0].idresponsable; 
		u.nombreresponsable.value	= obj[0].nombreresponsable;
		u.emailresponsable.value= obj[0].emailresponsable;
		v_suc					= obj[0].idsucursal; 
		u.h_responsable.value	= obj[0].idresponsable;
		consulta("mostrarCliente","../recoleccion/recoleccion_con.php?accion=1&cliente="+obj[0].cliente+"&valor="+Math.random());
	}
	function obtenerGuiaBusqueda(guia){
		u.guia.value = guia;
		consultaTexto("mostrarGuia","centroAtencionTelefonica_con.php?accion=11&guia="+guia);
	}
	function obtenerGuia(guia){
		//tecla  = (u) ? e.keyCode : e.which;
		consultaTexto("mostrarGuia","centroAtencionTelefonica_con.php?accion=11&guia="+guia);
	}
	function mostrarGuia(datos){		
		if(datos.indexOf("ya existe")>-1){
			var r = datos.split(",");
			alerta('El No. de Guia ya fue registrado en otra solicitud con el folio #'+r[1],'¡Atención!','guia');
			return false;
		}
		
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.cliente.value = obj[0].idremitente;
			u.estadoguia.value = obj[0].estado;
			u.nombrequeja.select();
			consultaTexto("mostrarCliente","../guias/guia_consultajson.php?accion=2&idcliente="+obj[0].idremitente+"&valrandom="+Math.random())
			//consulta("mostrarCliente","../recoleccion/recoleccion_con.php?accion=1&cliente="+obj[0].idremitente
			//+"&cat="+obj[0].iddireccionremitente
			//+"&valor="+Math.random());
		}else{
			u.guia.value = "";
			u.guia.select();
			alerta('El Folio de Guia no existe','¡Atención!','guia');
		}
	}
	function obtenerInformacionExtra(){
		if(u.queja.value == "QUEJAS DANOS Y FALTANTES"){
		abrirVentanaFija('moduloQuejasDanosFaltantes.php?mostrarvalores=1&id='+u.foliodano.value+'&solicitud=1', 625, 500, 'ventana', 'Modulo de Quejas Daños y Faltantes');
		}else{
		abrirVentanaFija('informacionExtra.php?folio='+u.folio.value, 450, 400, 'ventana', 'Información Extra');
		}
	}
	function isEmailAddress(theElement, nombre_del_elemento){
		var s = theElement.value;
		var filter=/^[A-Za-z_.][A-Za-z0-9_.]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
		if (s.length == 0 ) return true;
		if (filter.test(s))
		return true;
		else
		return false;
	}
	
	function traerAlcliente(valor){
		ocultarBuscador();
		obtenerClienteBusqueda(valor)
	}
	
	function ponerDireccion(obj){		
		u.iddireccion.value		= obj.iddireccion;
		u.calle.value 		= obj.calle;
		u.numero.value 		= obj.numero;
		u.cp.value 			= obj.codigopostal;
		u.colonia.value 	= obj.colonia;
		u.poblacion.value 	= obj.poblacion;
		u.telefono.value 	= obj.telefono;
		ocultarDirecciones();
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>
<script src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../javascript/DataSet.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
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
.Estilo4 {font-size: 12px}
-->
</style>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="529" class="FondoTabla Estilo4">CENTRO DE ATENCI&Oacute;N TELEF&Oacute;NICA</td>
  </tr>
  <tr>
    <td height="13"><div align="center">
      <table width="259" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="352"><table width="338" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="400" height="11"><table width="599" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="79">Folio:</td>
                  <td width="168"><input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
                    <span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarFolioSolicitudTel.php?funcion=obtenerFolioQuejaTel', 600, 550, 'ventana', 'Busqueda');"></span></td>
                  <td width="44">Fecha:</td>
                  <td width="134"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/></td>
                  <td colspan="2">Estado:&nbsp;&nbsp;
                    <input name="estado" type="text" class="Tablas" id="estado" style="width:120px;background:#FFFF99" readonly=""/></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td width="67">&nbsp;</td>
                  <td width="109">&nbsp;</td>
                </tr>
                <tr>
                  <td>Sucursal:
                    <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$sucursal_hidden ?>"></td>
                  <td><span class="Tablas">
<input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:150px" value="<?=$sucursal ?>" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onBlur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}"/>
					
                  </span></td>
                  <td>Queja:</td>
                  <td colspan="3"><select name="queja" class="Tablas" id="queja" onChange="habilitarQuejas(this.value)" style="width:200px">
				  <option value="0">SELECCIONAR QUEJA</option>
				  <option value="RECOLECCION">RECOLECCION</option>
				  <option value="EAD MAL EFECTUADAS">EAD MAL EFECTUADAS</option>
				  <option value="CONVENIOS NO APLICADOS">CONVENIOS NO APLICADOS</option>
				  <option value="OTROS SERVICIOS">OTROS SERVICIOS</option>
				  <option value="QUEJAS DANOS Y FALTANTES">QUEJAS DA&Ntilde;OS Y FALTANTES</option>
                  </select></td>
                  </tr>
                <tr>
                  <td colspan="2">Folio Aten. Telefonica:<span class="Tablas">
                    <input name="folioatencion" readonly="" type="text" class="Tablas" id="folioatencion" onKeyPress="obtenerFolioAtencion(event,this.value);" style="background:#FFFF99;width:60px" value="<?=$folioatencion ?>" />
                  </span></td>
                  <td> Gu&iacute;a:</td>
                  <td><span class="Tablas">
                    <input name="guia" type="text" readonly="" class="Tablas" id="guia" style="background:#FFFF99;width:100px" value="<?=$noguia ?>" onKeyUp="return habilitar(event,this.name)" onKeyPress="if(event.keyCode==13){obtenerGuia(this.value);}" />
                  </span></td>
                  <td>Recolecci&oacute;n:</td>
                  <td><span class="Tablas">
                    <input name="recoleccion" type="text" readonly="" class="Tablas" id="recoleccion" style="background:#FFFF99;width:80px" onKeyPress="obtenerRecoleccionEvaluacion(event,this.value)" onKeyUp="return habilitar(event,this.name)" value="<?=$norec ?>" />
                  </span></td>
                </tr>
                <tr>
                  <td colspan="2">Folio Da&ntilde;os Faltantes:<span class="Tablas">
                  <input name="foliodano" readonly="" type="text" class="Tablas" id="foliodano" onKeyPress="if(event.keyCode==13){obtenerFolioQueja(this.value);}" style="background:#FFFF99;width:60px" value="<?=$foliodano ?>" />
                  <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="if(document.all.foliodano.readOnly==false){if(document.all.sucursal.value!=''){abrirVentanaFija('../buscadores_generales/buscarFolioModuloQuejas.php?funcion=obtenerFolioQueja&sucursal='+document.all.sucursal_hidden.value, 600, 550, 'ventana', 'Busqueda Modulo Quejas');}else{alerta('Debe capturar Sucursal','&iexcl;Atenci&oacute;n!','sucursal');}}"></span></td>
                  <td>Estado:</td>
                  <td colspan="3"><span class="Tablas">
                    <input name="estadoguia" type="text" readonly="" class="Tablas" id="estadoguia" style="background:#FFFF99;width:200px" value="<?=$estadoguia ?>" onKeyUp="return habilitar(event,this.name)" />
                  </span></td>
                  </tr>
                <tr>
                  <td colspan="6" class="FondoTabla">Cliente</td>
                  </tr>				  
                  <tr>
                    <td colspan="6"><table width="599" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="76"># Cliente:</td>
                        <td width="190"><span class="Tablas">
                          <input name="cliente" type="text" class="Tablas" id="cliente" style="background:#FFFF99;width:60px" readonly="" value="<?=$_POST[cliente] ?>" maxlength="5" onKeyPress="if(document.all.queja.value=='OTROS SERVICIOS'){obtenerCliente(event,this.value);}" onKeyUp="return validarCliente(event,this.name)" />
                          &nbsp;&nbsp;&nbsp;&nbsp;<img src="../img/Buscar_24.gif" name="img_cliente" width="24" height="23" align="absbottom" id="img_cliente" style="cursor:pointer" onClick="mostrarBuscador();"></span></td>
                        <td colspan="4"><span class="Tablas">
                          <input name="nombre" type="text" class="Tablas" id="nombre" style="width:285px;background:#FFFF99" value="<?=$_POST[nombre] ?>" readonly=""/>
                        </span></td>
                      </tr>
                      <tr>
                        <td>Calle: </td>
                        <td colspan="3" id="celda_des_calle"><span class="Tablas">
                          <input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$_POST[calle] ?>" readonly=""/>
                          <input type="hidden" name="iddireccion" />
                        </span></td>
                        <td width="204"><span class="Tablas"> Numero:
                          <input name="numero" type="text" class="Tablas" id="numero" style="width:120px;background:#FFFF99" value="<?=$_POST[numero] ?>" readonly=""/>
                        </span></td>
                        <td width="8">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>Colonia:</td>
                        <td><span class="Tablas">
                          <input name="colonia" type="text" class="Tablas" id="colonia" style="width:165px;background:#FFFF99" value="<?=$_POST[colonia] ?>" readonly=""/>
                        </span></td>
                        <td width="21">&nbsp;</td>
                        <td width="100">C.P.:</td>
                        <td><span class="Tablas">
                          <input name="cp" type="text" class="Tablas" id="cp" style="width:165px;background:#FFFF99" value="<?=$_POST[cp] ?>" readonly=""/>
                        </span></td>
                        <td>&nbsp;</td>
                      </tr>
                      
                      <tr>
                        <td>Poblacion:</td>
                        <td><span class="Tablas">
                          <input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:165px;background:#FFFF99" value="<?=$_POST[poblacion] ?>" readonly=""/>
                        </span></td>
                        <td>&nbsp;</td>
                        <td>Telefono:</td>
                        <td><span class="Tablas">
                          <input name="telefono" type="text" class="Tablas" id="telefono" style="width:165px;background:#FFFF99" value="<?=$_POST[telefono] ?>" readonly=""/>
                        </span></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>Hrio. Recolecci&oacute;n:</td>
                        <td><input name="hrecoleccion" type="text" class="Tablas" id="hrecoleccion" style="width:165px;background:#FFFF99" value="<?=$hrecoleccion ?>" readonly=""/></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      
                    </table></td>
                  </tr>
                  <tr>
                  <td colspan="6"><p>&nbsp;</p>                  </td>
                  </tr>
                
                <tr>
                  <td colspan="6" class="FondoTabla">Datos Persona Levanta Queja </td>
                </tr>
                <tr>
                  <td>Nombre:</td>
                  <td colspan="2"><span class="Tablas">
                    <input name="nombrequeja" readonly="" type="text" class="Tablas" id="nombrequeja" style="background:#FFFF99;width:200px" value="<?=$cliente ?>" onKeyPress="if(event.keyCode==13){document.all.telefonoqueja.select();}" />
                  </span></td>
                  <td colspan="3">Telefono:<span class="Tablas">&nbsp;
                    <input name="telefonoqueja" readonly="" onKeyPress="if(event.keyCode==13){document.all.emailqueja.select();}" type="text" class="Tablas" id="telefonoqueja" style="background:#FFFF99;width:100px" value="<?=$telefono2 ?>" />
                  </span></td>
                  </tr>
                <tr>
                  <td><span class="Tablas">Email:</span></td>
                  <td colspan="2"><span class="Tablas">
                    <input name="emailqueja" readonly="" onKeyPress="if(event.keyCode==13){document.all.empresaqueja.select();}" type="text" class="Tablas" id="emailqueja" style="background:#FFFF99;width:200px; text-transform:lowercase" value="<?=$email ?>" />
                  </span></td>
                  <td colspan="3">Empresa:<span class="Tablas">&nbsp;
                    <input name="empresaqueja" readonly="" type="text" onKeyPress="if(event.keyCode==13){document.all.observaciones.select();}" class="Tablas" id="empresaqueja" style="background:#FFFF99;width:200px" />
                  </span></td>
                  </tr>
                <tr>
                  <td valign="top">Observaciones:
                    <label></label></td>
                  <td colspan="4" valign="top"><textarea name="observaciones"  readonly="readonly" id="observaciones" class="Tablas" style="background:#FFFF99;width:350px; text-transform:uppercase"></textarea></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                
                
                <tr>
                  <td colspan="6" class="FondoTabla">Seguimiento</td>
                </tr>
                <tr>
                  <td colspan="6"><table width="599" border="00" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="77">Responsable:</td>
                      <td width="60"><span class="Tablas">
                        <input name="responsable" type="text"  class="Tablas" id="responsable" style="width:50px" onKeyPress="obtenerEmpleado(event,this.value,1)" onKeyUp="return validaEmpleado(event,this.name,1)" value="<?=$_SESSION[IDUSUARIO] ?>" />
                      </span></td>
                      <td width="33"><div class="ebtn_buscar" onclick=            "abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=obtenerEmpleadoBusqueda&caja=1', 625, 418, 'ventana', 'Busqueda')"></div></td>
                      <td width="223"><input name="nombreresponsable" type="text" class="Tablas" id="nombreresponsable" style="width:200px;background:#FFFF99" value="<?=cambio_texto($fr->responsable); ?>" readonly=""/></td>
                      <td width="40"><div align="right">Email:</div></td>
                      <td width="166"><input name="emailresponsable" type="text" class="Tablas" id="emailresponsable" style="width:150px;background:#FFFF99" value="<?=cambio_texto($fr->email); ?>" readonly=""/></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="6"><table width="599" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="77">Supervisor:<br></td>
                      <td width="60"><span class="Tablas">
                        <input name="supervisor" type="text" class="Tablas" id="supervisor" style="width:50px" onKeyPress="obtenerEmpleado(event,this.value,2)" onKeyUp="return validaEmpleado(event,this.name,2)" value="<?=$supervisor ?>" />
                      </span></td>
                      <td width="33"><div class="ebtn_buscar" onclick=            "abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=obtenerEmpleadoBusqueda&caja=2', 625, 418, 'ventana', 'Busqueda')"></div></td>
                      <td width="223"><input name="nombresupervisor" type="text" class="Tablas" id="nombresupervisor" style="width:200px;background:#FFFF99" value="<?=$supervisorb ?>" readonly=""/></td>
                      <td width="40"><div align="right">Email:</div></td>
                      <td width="166"><input name="emailsupervisor" type="text" class="Tablas" id="emailsupervisor" style="width:150px;background:#FFFF99" value="<?=$emailb2 ?>" readonly=""/></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="6"><table width="298" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="139"><img src="../img/Boton_Info_Extra.gif" width="130" height="20" style="cursor:pointer" onClick="obtenerInformacionExtra()"></td>
                      <td width="80"><div class="ebtn_guardar" id="d_guardar" onClick="validar()"></div></td>
                      <td width="79"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')"></div></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="6"><div align="center">
                    <input name="accion" type="hidden" id="accion">
                    <input name="folioactividad" type="hidden" id="folioactividad">
                    <input name="h_responsable" type="hidden" id="h_responsable">
                  </div></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
</form>
<?
	$raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
	
	$raiz = "../";
	$funcion = "ponerDireccion";
	$nombreBuscador = "catDirecciones";
	$funcionMostrar = "mostrarDirecciones";
	$funcionOcultar = "ocultarDirecciones";
	include("../buscadores_generales/direccionesIncrustadas.php");
?>
</body>
</html>