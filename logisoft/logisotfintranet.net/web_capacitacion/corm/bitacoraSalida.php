<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script> 
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../javascript/DataSet.js"></script>
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var u = document.all;
	var tabla1 	= new ClaseTabla();
	var btn_Modificar = '<img src="../img/Boton_Modificar.gif" alt="Agregar" style="cursor:pointer" onclick="agregar()" />';
	var btn_Agregar = '<img src="../img/Boton_Agregari.gif" alt="Agregar" name="img_agregar" width="70" height="20" id="d_agregar" style="cursor:pointer" title="Agregar" onclick="agregar();"/>';
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"PRECINTO", medida:110, alineacion:"left", datos:"precinto"},
			{nombre:"FECHA ASIGNADO", medida:110, alineacion:"left", datos:"fechaasignado"},
			{nombre:"FECHA2", medida:4, tipo:"oculto",  alineacion:"left", datos:"fecha"}
		],
		filasInicial:5,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.conductor1.focus();
		u.d_cancelar.style.visibility = "hidden";
		u.d_eliminar.style.visibility = "hidden";
		if(u.folioLogistica.value==""){
			obtenerGeneral();
		}else{
			u.botones.style.display = 'none';
			obtener(u.folioLogistica.value);
		}
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","bitacorasalida_con.php?accion=1");
	}
	
	function mostrarGeneral(datos){
		var row = datos.split(",");
		u.fechabitacora.value = row[1];
		u.folio.value = row[0];
	}
	
	function validar(){
		<?=$cpermiso->verificarPermiso("309",$_SESSION[IDUSUARIO]);?>
		if(u.conductor1.value==""){
			alerta('Debe Capturar conductor','!Atención!','conductor1');
		}else if(u.unidad.value==""){
			alerta('Debe Capturar Unidad','!Atención!','unidad');
		}else if(u.ruta.value==""){
			alerta('Debe Capturar Ruta','!Atención!','ruta');			
		}else
		{
			var v_fecha = "";
			if(u.accion.value==""){
				v_fecha = fechahora(v_fecha);
				u.h_unidad.value = u.unidad.value;
				u.h_remolque1.value = u.remolque1.value;
				u.h_remolque2.value = u.remolque2.value;				
				u.h_conductor1.value = u.conductor1.value;
				u.h_conductor2.value = u.conductor2.value;
				u.h_conductor3.value = u.conductor3.value;				
				u.d_guardar.style.visibility = "hidden";
				consultaTexto("registro","bitacorasalida_con.php?accion=2&conductor1="+u.conductor1.value
				+"&licencia1="+((u.licencia_conductor1.checked == true)?1:0)
				+"&conductor2="+u.conductor2.value+"&licencia2="+((u.licencia_conductor2.checked == true)?1:0)
				+"&conductor3="+u.conductor3.value+"&licencia3="+((u.licencia_conductor3.checked == true)?1:0)
				+"&unidad="+u.unidad.value+"&tarjeta_unidad="+((u.tarjeta_unidad.checked == true)?1:0)
				+"&poliza_unidad="+((u.poliza_unidad.checked == true)?1:0)
				+"&vrf_unidad="+((u.vrf_unidad.checked == true)?1:0)
				+"&pcd_unidad="+((u.pcd_unidad.checked == true)?1:0)+"&remolque1="+u.remolque1.value
				+"&tarjeta_remolque1="+((u.tarjeta_remolque1.checked == true)?1:0)
				+"&poliza_remolque1="+((u.poliza_remolque1.checked == true)?1:0)
				+"&pcd_remolque1="+((u.pcd_remolque1.checked == true)?1:0)
				+"&remolque2="+u.remolque2.value+"&tarjeta_remolque2="+((u.tarjeta_remolque2.checked == true)?1:0)
				+"&poliza_remolque2="+((u.poliza_remolque2.checked == true)?1:0)
				+"&pcd_remolque2="+((u.pcd_remolque2.checked == true)?1:0)
				+"&ruta="+u.ruta.value+"&gastos="+u.gastos.value.replace("$ ","").replace(/,/g,"")
				+"&fechahora="+v_fecha
				+"&Nombre_Cliente="+u.nombre.value+"&id_cliente="+u.codigo.value
				+"&fecha_Bodega="+u.fecha.value+"&Hora_Bodega="+u.fondo.value);	
				
			}else{
				v_fecha = fechahora(v_fecha);
				u.d_guardar.style.visibility = "hidden";
				consultaTexto("modifico","bitacorasalida_con.php?accion=3&conductor1="+u.conductor1.value
				+"&licencia1="+((u.licencia_conductor1.checked == true)?1:0)
				+"&conductor2="+u.conductor2.value+"&licencia2="+((u.licencia_conductor2.checked == true)?1:0)
				+"&conductor3="+u.conductor3.value+"&licencia3="+((u.licencia_conductor3.checked == true)?1:0)
				+"&unidad="+u.unidad.value+"&tarjeta_unidad="+((u.tarjeta_unidad.checked == true)?1:0)
				+"&poliza_unidad="+((u.poliza_unidad.checked == true)?1:0)
				+"&vrf_unidad="+((u.vrf_unidad.checked == true)?1:0)
				+"&pcd_unidad="+((u.pcd_unidad.checked == true)?1:0)+"&remolque1="+u.remolque1.value
				+"&tarjeta_remolque1="+((u.tarjeta_remolque1.checked == true)?1:0)
				+"&poliza_remolque1="+((u.poliza_remolque1.checked == true)?1:0)
				+"&pcd_remolque1="+((u.pcd_remolque1.checked == true)?1:0)
				+"&remolque2="+u.remolque2.value+"&tarjeta_remolque2="+((u.tarjeta_remolque2.checked == true)?1:0)
				+"&poliza_remolque2="+((u.poliza_remolque2.checked == true)?1:0)
				+"&pcd_remolque2="+((u.pcd_remolque2.checked == true)?1:0)
				+"&ruta="+u.ruta.value+"&gastos="+u.gastos.value.replace("$ ","").replace(/,/g,"")
				+"&h_unidad="+u.h_unidad.value
				+"&h_remolque1="+u.remolque1.value+"&h_remolque2="+u.h_remolque2.value
				+"&h_conductor1="+u.h_conductor1.value+"&h_conductor2="+u.h_conductor2.value
				+"&h_conductor3="+u.h_conductor3.value+"&folio="+u.folio.value
				+"&fechabitacora="+u.fechabitacora.value+"&fechahora="+v_fecha
				+"&Nombre_Cliente="+u.nombre.value+"&id_cliente="+u.codigo.value
				+"&fecha_Bodega="+u.fecha.value+"&Hora_Bodega="+u.fondo.value
				);
			}
		}
	}
	
	function registro(datos){
		if(datos.indexOf("guardo")>-1){
			var row = datos.split(",");
			u.folio.value = row[1];
			u.accion.value= "modificar";
			u.d_guardar.style.visibility = "visible";
			u.d_cancelar.style.visibility = "visible";
			u.btnImprimir.style.visibility = "visible";
			info('Los datos han sido guardados correctamente', 'Operación realizada correctamente');
		}else if(datos.indexOf("esta registrada en la bitacora")>-1){
			alerta3(datos,"!Atenci&oacute;n!");
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3("Hubo un error al guardar los datos "+datos,"!Atención!");
		}
	}
	
	function modifico(datos){
		if(datos.indexOf("embarcada")>-1){
			alerta3("La bitacora de salida no se puede modificar por que ya fue utilizada en un Embarque","!Atención!");
			return false;
		}
		if(datos.indexOf("guardo")>-1){
			u.h_unidad.value = u.unidad.value;
			u.h_remolque1.value = u.remolque1.value;
			u.h_remolque2.value = u.remolque2.value;
			u.d_guardar.style.visibility = "visible";
			u.btnImprimir.style.visibility = "visible";
			info('Los cambios han sido guardados correctamente', 'Operación realizada correctamente');
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3("Hubo un error al guardar los datos "+datos,"�Atenci�n!");
		}
	}
	
	function obtener(folio,nombre){	
		u.nombre.value=nombre;
		if (u.nombre.value !="undefined"){
		u.codigo.value=folio;
		u.nombre.value=nombre;
		ocultarBuscador();
		}else{
		u.folio.value = folio;
		consulta("mostrarBitacora","consultaCORM.php?accion=1&folio="+folio);
		u.nombre.value="";}
		
			
	}
	function mostrarBitacora(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		limpiar(0);
		if(con > 0){
			u.fechabitacora.value = datos.getElementsByTagName('fechabitacora').item(0).firstChild.data;
			u.conductor1.value = datos.getElementsByTagName('conductor1').item(0).firstChild.data;
			u.h_conductor1.value = u.conductor1.value;
			
			if(datos.getElementsByTagName('licencia_conductor1').item(0).firstChild.data!=0){
				u.licencia_conductor1.checked = true;	
			}else{
				u.licencia_conductor1.checked = false;
			}
			var conductor2 = datos.getElementsByTagName('conductor2').item(0).firstChild.data;
			
			if(conductor2!=0 && conductor2!=""){
				u.conductor2.value = datos.getElementsByTagName('conductor2').item(0).firstChild.data;
				u.nombre_conductor2.value = datos.getElementsByTagName('nombre2').item(0).firstChild.data;
				u.h_conductor2.value = conductor2;
			}
			if(datos.getElementsByTagName('licencia_conductor2').item(0).firstChild.data!=0){
				u.licencia_conductor2.checked = true;			
			}else{
				u.licencia_conductor2.checked = false;
			}
			var conductor3 = datos.getElementsByTagName('conductor3').item(0).firstChild.data;		
			if(conductor3!=0 && conductor3!=""){
				u.conductor3.value = datos.getElementsByTagName('conductor3').item(0).firstChild.data;			
				u.nombre_conductor3.value = datos.getElementsByTagName('nombre3').item(0).firstChild.data;
				u.h_conductor3.value = conductor3;
			}
			
			if(datos.getElementsByTagName('licencia_conductor3').item(0).firstChild.data!=0){
				u.licencia_conductor3.checked = true;			
			}else{
				u.licencia_conductor3.checked = false;
			}
			u.unidad.value = datos.getElementsByTagName('unidad').item(0).firstChild.data;
			u.h_unidad.value = u.unidad.value;
				
			if (datos.getElementsByTagName('tarjeta_unidad').item(0).firstChild.data!=0){
				u.tarjeta_unidad.checked = true;
			}else{
				u.tarjeta_unidad.checked = false;
			}
			if (datos.getElementsByTagName('poliza_unidad').item(0).firstChild.data!=0){
				u.poliza_unidad.checked = true;
			}else{
				u.poliza_unidad.checked = false;
			}
			if (datos.getElementsByTagName('vrf_unidad').item(0).firstChild.data!=0){
				u.vrf_unidad.checked = true;
			}else{
				u.vrf_unidad.checked = false;
			}
			if (datos.getElementsByTagName('pcd_unidad').item(0).firstChild.data!=0){
				u.pcd_unidad.checked = true;
			}else{
				u.pcd_unidad.checked = false;
			}
			u.remolque1.value = datos.getElementsByTagName('remolque1').item(0).firstChild.data;
			u.h_remolque1.value = u.remolque1.value;			
			if (datos.getElementsByTagName('tarjeta_remolque1').item(0).firstChild.data!=0){
				u.tarjeta_remolque1.checked = true;
			}else{
				u.tarjeta_remolque1.checked = false;
			}
			if (datos.getElementsByTagName('poliza_remolque1').item(0).firstChild.data!=0){
				u.poliza_remolque1.checked = true;
			}else{
				u.poliza_remolque1.checked = false;
			}
			
			if (datos.getElementsByTagName('pcd_remolque1').item(0).firstChild.data!=0){
				u.pcd_remolque1.checked = true;
			}else{
				u.pcd_remolque1.checked = false;
			}
			u.remolque2.value = datos.getElementsByTagName('remolque2').item(0).firstChild.data;
			u.h_remolque2.value = u.remolque2.value;
			if (datos.getElementsByTagName('tarjeta_remolque2').item(0).firstChild.data!=0){		
				u.tarjeta_remolque2.checked = true;
			}else{
				u.tarjeta_remolque2.checked = false;
			}
			
			if (datos.getElementsByTagName('poliza_remolque2').item(0).firstChild.data!=0){
				u.poliza_remolque2.checked = true;			
			}else{
				u.poliza_remolque2.checked = false;
			}
			
			if (datos.getElementsByTagName('pcd_remolque2').item(0).firstChild.data!=0){
				u.pcd_remolque2.checked = true;			
			}else{
				u.pcd_remolque2.checked = false;
			}
			u.ruta.value = datos.getElementsByTagName('ruta').item(0).firstChild.data;
			u.codigo.value = datos.getElementsByTagName('id_cliente').item(0).firstChild.data;
			u.nombre.value = datos.getElementsByTagName('Nombre_Cliente').item(0).firstChild.data;
			u.fecha.value = datos.getElementsByTagName('fecha_Bodega').item(0).firstChild.data;
			u.fondo.value = datos.getElementsByTagName('Hora_Bodega').item(0).firstChild.data;
			u.gastos.value = datos.getElementsByTagName('gastos').item(0).firstChild.data;
			u.gastos.value = "$ "+numcredvar(u.gastos.value);
			u.nombre_conductor1.value = datos.getElementsByTagName('nombre1').item(0).firstChild.data;		
			u.descripcionruta.value = datos.getElementsByTagName('rdescripcion').item(0).firstChild.data;
			u.accion.value = "modificar";
			u.d_cancelar.style.visibility = "visible";
			u.btnImprimir.style.visibility = "visible";
			consultaTexto("mostrarDetalle","bitacorasalida_con.php?accion=6&folio="+u.folio.value);
		}else{
			alerta3("No se encontro el folio","Busqueda de Folio");
			limpiar(1);
		}
	}
	
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
			u.d_eliminar.style.visibility = "visible";
		}
	}
	
	function obtenerConductorBusqueda(id,caja){
		if(id!=""){
			switch(caja){
				case "1":
					u.conductor1.value = id;
				break;
				case "2":		
					u.conductor2.value = id;
				break;
				case "3":
					u.conductor3.value = id;
				break;
			}
				consulta("mostrarConductor","consultaCORM.php?accion=2&empleado="+id+"&caja="+caja);
		}
	}
	function obtenerConductor(e,id,caja){
		tecla = (u) ? e.keyCode : e.which;
		if((tecla == 13 || tecla ==9)&& id!=""){
				consulta("mostrarConductor","consultaCORM.php?accion=2&empleado="+id+"&caja="+caja);
		}
	}
	function mostrarConductor(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	var caja = datos.getElementsByTagName('caja').item(0).firstChild.data;
		if(con>0){
		switch(caja){
		case "1":
				u.nombre_conductor1.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
		break;
		case "2":		
				u.nombre_conductor2.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
		break;
		case "3":
				u.nombre_conductor3.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
		break;
		}
		
		}else{
			alerta('El conductor no existe o ya fue asignado a una unidad','�Atenci�n!','conductor'+caja);
			switch(caja){
				case "1":
					u.nombre_conductor1.value = "";
				break;
				case "2":		
					u.nombre_conductor2.value = "";
				break;
				case "3":
					u.nombre_conductor3.value = "";
				break;
			}
		}
	}
	function obtenerRutaBusqueda(id){
		if(id!=""){
			u.ruta.value = id
			consulta("mostrarRuta","consultaCORM.php?accion=4&ruta="+id);
		}
	}
	function obtenerRuta(e,id){
		tecla = (u) ? e.keyCode : e.which;
		if((tecla == 13 || tecla == 9) && id!=""){
	consulta("mostrarRuta","consultaCORM.php?accion=4&ruta="+id);
		}
	}
	function mostrarRuta(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			u.descripcionruta.value = datos.getElementsByTagName('descripcion').item(0).firstChild.data;
		}else{
			alerta('La Ruta no existe','�Atenci�n!','ruta');
			u.ruta.value = "";
		}
	}
	
	function obtenerUnidadBusqueda(id,caja){
		if(id!=""){
			switch(caja){
				case "1":
					u.unidad.value = id;
				break;
				case "2":		
					u.remolque1.value = id;
				break;
				case "3":
					u.remolque2.value = id;
				break;
			}
			consulta("mostrarUnidad","consultaCORM.php?accion=3&unidad="+id+"&caja="+caja);
		}
	}
	function obtenerUnidad(e,id,caja){
		tecla = (u) ? e.keyCode : e.which;
		if((tecla == 13 || tecla == 9) && id!=""){
			//consulta("consultaCORM.php?accion=3&unidad="+id+"&caja="+caja,"");
			consulta("mostrarUnidad","consultaCORM.php?accion=3&unidad="+id+"&caja="+caja);
		}
	}
	function mostrarUnidad(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	var caja = datos.getElementsByTagName('caja').item(0).firstChild.data;
		if(con>0){
		switch(caja){
		case "1":
u.unidad.value = datos.getElementsByTagName('numeroeconomico').item(0).firstChild.data;
		break;
		case "2":		
u.remolque1.value = datos.getElementsByTagName('numeroeconomico').item(0).firstChild.data;
		break;
		case "3":
u.remolque2.value = datos.getElementsByTagName('numeroeconomico').item(0).firstChild.data;
		break;
		}
		
		}else{
			if(caja==1){
			alerta('La Unidad no existe','!Atención!','unidad');				
			}else{
			alerta('El Remolque no existe','!Atención!','remolque'+(caja-1));	
			}
			switch(caja){
				case "1":
					u.unidad.value = "";
				break;
				case "2":		
					u.remolque1.value = "";
				break;
				case "3":
					u.remolque2.value = "";
				break;
			}
		}
	}
	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}
	function tabular(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
		if(tecla!=13) return;
		frm=obj.form;
		for(i=0;i<frm.elements.length;i++) 
			if(frm.elements[i]==obj) 
			{ 
				if (i==frm.elements.length-1) 
					i=-1;
				break
			}
		if (frm.elements[i+1].disabled ==true )    
			tabular(e,frm.elements[i+1]);
		else if(frm.elements[i+1].readOnly ==true )
			tabular(e,frm.elements[i+1]);
		else frm.elements[i+1].focus();
		return false;
	}
	function limpiar(tipo){		
		u.conductor1.value = "";
		u.licencia_conductor1.checked = true;
		u.conductor2.value = "";
		u.licencia_conductor2.checked = true;
		u.conductor3.value = "";
		u.licencia_conductor3.checked = false;
		u.unidad.value = "";
		u.tarjeta_unidad.checked = true;
		u.poliza_unidad.checked = true;
		u.vrf_unidad.checked = true;
		u.remolque1.value = "";
		u.tarjeta_remolque1.checked = true;
		u.poliza_remolque1.checked = true;
		u.remolque2.value = "";
		u.tarjeta_remolque2.checked = true;
		u.poliza_remolque2.checked = true;
		u.pcd_remolque2.checked = true;
		u.pcd_remolque1.checked = true;
		u.pcd_unidad.checked = true;
		u.ruta.value = "";
		u.gastos.value = "";
		u.nombre_conductor1.value = "";
		u.nombre_conductor2.value = "";
		u.nombre_conductor3.value = "";
		u.descripcionruta.value = "";
		u.h_unidad.value = "";
		u.h_remolque1.value = "";
		u.codigo.value = "";
		u.nombre.value = "";
		u.fecha.value = "yyyy-mm-dd";
		u.fondo.value = "";
		u.td_agregar.innerHTML = btn_Agregar;
		u.precinto.value = "";
		if(tipo==1){ 
			u.d_cancelar.style.visibility = "hidden";
			u.d_eliminar.style.visibility = "hidden";
			u.btnImprimir.style.visibility = "hidden";
			u.d_guardar.style.visibility = "visible";
			u.accion.value = "";
			tabla1.clear();
			obtenerGeneral();
			
		}
	}
	function foco(nombrecaja){
		if(nombrecaja=="conductor1"){
			u.oculto.value="1";
		}else if(nombrecaja=="conductor2"){
			u.oculto.value="2";
		}else if(nombrecaja=="conductor3"){
			u.oculto.value="3";
		}else if(nombrecaja=="unidad"){
			u.oculto.value="4";
		}else if(nombrecaja=="remolque1"){
			u.oculto.value="5";
		}else if(nombrecaja=="remolque2"){
			u.oculto.value="6";
		}else if(nombrecaja=="ruta"){
			u.oculto.value="7";
		}
	}
shortcut.add("Ctrl+b",function() {
	if(u.oculto.value=="1"){
abrirVentanaFija('buscarConductor.php?caja=1', 550, 450, 'ventana', 'Busqueda')
	}else if(u.oculto.value=="2"){
abrirVentanaFija('buscarConductor.php?caja=2', 550, 450, 'ventana', 'Busqueda')
	}else if(u.oculto.value=="3"){
abrirVentanaFija('buscarConductor.php?caja=3', 550, 450, 'ventana', 'Busqueda')
	}else if(u.oculto.value=="4"){
abrirVentanaFija('buscarUnidad.php?caja=1', 550, 450, 'ventana', 'Busqueda')
	}else if(u.oculto.value=="5"){
abrirVentanaFija('buscarUnidad.php?caja=2', 550, 450, 'ventana', 'Busqueda')
	}else if(u.oculto.value=="6"){
abrirVentanaFija('buscarUnidad.php?caja=3', 550, 450, 'ventana', 'Busqueda')
	}else if(u.oculto.value=="7"){
abrirVentanaFija('buscarRuta.php', 550, 450, 'ventana', 'Busqueda')
	}
});
	
	function borrarDescripciones(nombrecaja){
		if(nombrecaja =="conductor1" && u.conductor1.value ==""){
			u.nombre_conductor1.value = "";
			u.licencia_conductor1.checked = false;
			
		}else if(nombrecaja =="conductor2" && u.conductor2.value ==""){
			u.nombre_conductor2.value = "";
			u.licencia_conductor2.checked = false;
			
		}else if(nombrecaja =="conductor3" && u.conductor3.value ==""){
			u.nombre_conductor3.value = "";
			u.licencia_conductor3.checked = false;
			
		}else if(nombrecaja =="ruta" && u.ruta.value ==""){
			u.descripcionruta.value = "";
			
		}else if(nombrecaja =="unidad" && u.unidad.value =="" ){
			u.tarjeta_unidad.checked = false; u.poliza_unidad.checked = false;
			u.vrf_unidad.checked = false;
		}else if(nombrecaja =="remolque1" && u.remolque1.value =="" ){
		u.tarjeta_remolque1.checked = false; u.poliza_remolque1.checked = false;
		}else if(nombrecaja =="remolque2" && u.remolque2.value ==""){
		u.tarjeta_remolque2.checked = false; u.poliza_remolque2.checked = false;
		}
	}
	function validarLicencia(nombre){
		if(nombre=="licencia_conductor1" && u.conductor1.value ==""){
			u.licencia_conductor1.checked = false;
			alerta('Debe Capturar conductor','�Atenci�n!','conductor1');
		}else if(nombre=="licencia_conductor2" && u.conductor2.value ==""){
			u.licencia_conductor2.checked = false;
			alerta('Debe Capturar conductor','�Atenci�n!','conductor2');
		}else if(nombre=="licencia_conductor3" && u.conductor3.value ==""){
			u.licencia_conductor3.checked = false;
			alerta('Debe Capturar conductor','�Atenci�n!','conductor3');
		}else if(nombre=="tarjeta_unidad" && u.unidad.value ==""){
			u.tarjeta_unidad.checked = false;
			alerta('Debe Capturar unidad','�Atenci�n!','unidad');
		}else if(nombre=="poliza_unidad" && u.unidad.value ==""){
			u.poliza_unidad.checked = false;
			alerta('Debe Capturar unidad','�Atenci�n!','unidad');
		}else if(nombre=="pcd_unidad" && u.unidad.value ==""){
			u.pcd_unidad.checked = false;
			alerta('Debe Capturar unidad','�Atenci�n!','unidad');
		}else if(nombre=="vrf_unidad" && u.unidad.value ==""){
			u.vrf_unidad.checked = false;
			alerta('Debe Capturar unidad','�Atenci�n!','unidad');
		}else if(nombre=="tarjeta_remolque1" && u.remolque1.value ==""){
			u.tarjeta_remolque1.checked = false;
			alerta('Debe Capturar remolque','�Atenci�n!','remolque1');
		}else if(nombre=="poliza_remolque1" && u.remolque1.value ==""){
			u.poliza_remolque1.checked = false;
			alerta('Debe Capturar remolque','�Atenci�n!','remolque1');
		}else if(nombre=="pcd_remolque1" && u.remolque1.value ==""){
			u.pcd_remolque1.checked = false;
			alerta('Debe Capturar remolque','�Atenci�n!','remolque1');
		}else if(nombre=="tarjeta_remolque2" && u.remolque2.value ==""){
			u.tarjeta_remolque2.checked = false;
			alerta('Debe Capturar remolque','�Atenci�n!','remolque2');
		}else if(nombre=="poliza_remolque2" && u.remolque2.value ==""){
			u.poliza_remolque2.checked = false;
			alerta('Debe Capturar remolque','�Atenci�n!','remolque2');
		}else if(nombre=="pcd_remolque2" && u.remolque2.value ==""){
			u.pcd_remolque2.checked = false;
			alerta('Debe Capturar remolque','�Atenci�n!','remolque2');
		}
	}
	function cancelar(){
		<?=$cpermiso->verificarPermiso("388",$_SESSION[IDUSUARIO]);?>
		u.d_cancelar.style.visibility = "hidden";
		consultaTexto("confirmarCancelacion","bitacorasalida_con.php?accion=4&bitacora="+u.folio.value);
	}
	function confirmarCancelacion(datos){
		if(datos.indexOf("embarcada")>-1){
			alerta3("La bitacora de salida no se puede Cancelar por que ya fue utilizada en un Embarque","�Atenci�n!");
			return false;
		}
		if(datos.indexOf("guardo")>-1){			
			info('La bitacora ha sido cancelada correctamente', 'Operaci�n realizada correctamente');
			u.btnImprimir.style.visibility = "hidden";
			u.d_guardar.style.visibility = "hidden";
			u.d_cancelar.style.visibility = "hidden";
		}else{
			u.d_cancelar.style.visibility = "visible";
			alerta3("Hubo un error al cancelar "+datos,"�Atenci�n!");			
		}
	}
	function obtenerPrecintosBusqueda(precinto){
		u.precinto.value = precinto;
	}
	
	function agregar(){
		var precinto = tabla1.getValuesFromField('precinto',',');		
		if(u.precinto.value ==""){
			alerta('Debe capturar Precinto','�Atenci�n!','precinto');
			return false;
			
		}else if(precinto.indexOf(u.precinto.value)>-1){
			alerta('El Precinto #'+u.precinto.value+' ya fue asignado','�Atenci�n!','precinto');
			return false;
			
		}else if(u.unidad.value == ""){
			alerta('Debe capturar Unidad','�Atenci�n!','unidad');
			return false;
		}
		
		if(u.esmodificar.value == ""){
			u.fechahora.value = fechahora(u.fechahora.value);
			u.asignado.value = '<?=date('d/m/Y') ?>';
			consultaTexto("registroAgregar","bitacorasalida_con.php?accion=5&precinto="+u.precinto.value
		+"&fecha="+u.fechahora.value+"&fechaasignado="+u.asignado.value
		+"&unidad="+u.unidad.value+"&tipo=grabar");
		}else{
			consultaTexto("registroAgregar","bitacorasalida_con.php?accion=5&precinto="+u.precinto.value
		+"&fecha="+u.fechahora.value+"&fechaasignado="+u.asignado.value
		+"&unidad="+u.unidad.value+"&tipo=modificar");
		}
	}
	function registroAgregar(datos){
		if(datos.indexOf("ok")<0){
			alerta3('Hubo un error al agregar '+datos,'!Atención!');
		}else{
			if(u.esmodificar.value == ""){
				var obj = new Object();
				obj.precinto 	= u.precinto.value;
				obj.fecha		= u.fechahora.value;
				obj.fechaasignado	= u.asignado.value;
				tabla1.add(obj);
			}else{
				var obj = new Object();
				obj.precinto 	= u.precinto.value;
				obj.fecha		= u.fechahora.value;
				obj.fechaasignado	= u.asignado.value;
				tabla1.updateRowById(u.fila.value, obj);
				u.td_agregar.innerHTML = btn_Agregar;
			}
			u.d_eliminar.style.visibility = "visible";			
			u.precinto.value = "";
			u.fechahora.value= "";
			u.asignado.value = "";
			u.fila.value	= "";
			u.esmodificar.value = "";
		}
	}
	function ModificarFila(){		
		if(tabla1.getValSelFromField('precinto','PRECINTO')!=""){
			var obj = tabla1.getSelectedRow();
			u.fila.value = tabla1.getSelectedIdRow();
			u.esmodificar.value = "si";
			u.fechahora.value = obj.fecha;
			u.precinto.value = obj.precinto;
			u.asignado.value = obj.fechaasignado;
			u.td_agregar.innerHTML = btn_Modificar;
		}
	}
	function eliminarFila(){
		if(tabla1.getValSelFromField('precinto','PRECINTO')!=""){
			var obj = tabla1.getSelectedRow();
			u.fila.value = tabla1.getSelectedIdRow();
			u.fechahora.value = obj.fecha;
			confirmar('�Esta seguro de eliminar el precinto?','','borrarFila()','');
		}
	}
	
	function borrarFila(){
		var obj = tabla1.getSelectedRow();
		consultaTexto("eliminoFila","bitacorasalida_con.php?accion=5&tipo=eliminar&fecha="+u.fechahora.value
		+"&d="+Math.random());	 	
	}
	
	function eliminoFila(datos){
		if(datos.indexOf("ok")>-1){
			tabla1.deleteById(u.fila.value);
			if(tabla1.getRecordCount()==0){
		  		u.d_eliminar.style.visibility = "hidden";
	  		}
			u.fila.value = "";
			u.fechahora.value = "";
		}else{
			alerta3("Hubo un error al eliminar "+datos,"�Atenci�n!");
		}
	}	
	
	function imprimirBitacora(){
		<?=$cpermiso->verificarPermiso("310",$_SESSION[IDUSUARIO]);?>
		if(u.accion.value != ""){
			if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/fpdf/reportes/bitacoraSalida.php?bitacora="+u.folio.value);
						
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/bitacoraSalida.php?bitacora="+u.folio.value);
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/bitacoraSalida.php?bitacora="+u.folio.value);
			}
		}else{
			alerta3("Debe guardar los datos para poder imprimir la bitacora de salida","�Atenci�n!");
		}
	}
	
	function buscarPrecinto(){
		if (u.precinto.value!=''){
			consultaTexto("cargaPrecinto","bitacorasalida_con.php?accion=7&sucursal="+<?=$_SESSION[IDSUCURSAL] ?>+"&tipo=bitacora&precinto="+u.precinto.value);
		}else{
			alerta3("Debe escribir el folio del precinto a buscar","�Atenci�n!");
		}
	}
	
	function cargaPrecinto(datos){
		if(datos=='no encontro'){
			u.precinto.value="";
			alerta3('Este precinto no es valido seleccione otro');			
		}else{
			agregar();
		}
	}

</script>
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
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../convenio/Tablas.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../sobreImagenes.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>
    <td width="612" class="FondoTabla Estilo4">BIT&Aacute;CORA SALIDA</td>
  </tr>
    <td><div align="center">
      <table width="490" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="3">&nbsp;</td>
          <td colspan="6"><div align="center"></div></td>
        </tr>
        <tr>
                <!-- -->
        <tr>                                                    
          <td>&nbsp;</td>                                       
          <td colspan="6" class="FondoTabla">Datos Clientes</td>        
        </tr>
      <!-- -->                                                     
        <tr>
           <td>&nbsp;</td>   
           <td class="Tablas">Cliente:</td>
           <td colspan="6" class="Tablas"><input class="Tablas" name="codigo" type="text" id="codigo" style="font-size:9px; font:tahoma" value="<?=$codigo; ?>" size="10" onKeyPress="if(event.keyCode==13){obtenerCliente(this.value);}" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onKeyUp="return validarCliente(event,this.name)" /> 
           <img src="../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Cliente" onClick="mostrarBuscador()"/></td>
        </tr>               
        <tr>
        	<td>&nbsp;</td>     
            <td class="Tablas">Nombre:</td>
            <td colspan="6" class="Tablas"><input class="Tablas" name="nombre" type="text" id="nombre" size="64" onBlur="trim(document.getElementById('nombre').value,'nombre');" value="<?=$nombre; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/></td>
        </tr>
            <td>&nbsp;</td>   
            <td>Fecha:</td>
            <td width="107">
                       <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;" value="" readonly="" onchange="obtenerFolioxFecha(this.value)" />
                      <img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this); " />                      

           <!-- <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="yyyy-mm-dd"/>       
-->          </td>  
			 <td width="38">Hora:</td>
            <td width="157">
            <input name="fondo" type="text" class="Tablas" id="fondo" style="width:80px" value="00:00" size="10" maxlength="10">       </td>
         </tr>
        <tr>                                                    
          <td>&nbsp;</td>                                       
          <td colspan="6" class="FondoTabla">Datos Generales</td>        
        </tr>
          <td>&nbsp;</td>
        </tr>
          <td>&nbsp;</td>
          <td colspan="6"><table width="448" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="448"><div align="right">Fecha:<span class="Tablas">
                <input name="fechabitacora" type="text" class="Tablas" id="fechabitacora" style="width:100px;background:#FFFF99;text-align:center" value="<?=$fecha ?>" 
				 readonly="" />
                </span>Folio:<span class="Tablas">
                  <input name="folio" type="text" class="Tablas" id="folio" style="width:50px; text-align:right" value="<?=$folio ?>"
				  onkeypress="if(event.keyCode=='13'){obtener(this.value);}"/>
                  <img src="../img/Buscar_24.gif" alt="buscar" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Prospecto" onClick="abrirVentanaFija('buscarBitacora.php', 600, 500, 'ventana', 'Busqueda')"/>
                </span></div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
          <td height="25">&nbsp;</td>
          <td width="61">Conductor:</td>
          <td width="107"><span class="Tablas">
            <input name="conductor1" type="text" class="Tablas" id="conductor1" style="width:100px" onKeyDown="obtenerConductor(event,this.value,1); return tabular(event,this)" value="<?=$conductor1 ?>" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; " />
          </span></td>
          <td width="38"><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarConductor.php?caja=1', 600, 500, 'ventana', 'Busqueda')"></div></td>
          <td colspan="2"><span class="Tablas">
            <input name="nombre_conductor1" type="text" class="Tablas" id="nombre_conductor1" style="width:200px;background:#FFFF99" value="<?=$nombre_conductor1 ?>" readonly=""/>
          </span></td>
          <td width="79"><input name="licencia_conductor1" type="checkbox" id="licencia_conductor1"  onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($licencia_conductor1==1){echo "checked";} ?>>
Licencia </td>
        </tr>
        <tr>
          <td height="26">&nbsp;</td>
          <td>Conductor:</td>
          <td><span class="Tablas">
            <input name="conductor2" type="text" class="Tablas" id="conductor2" style="width:100px" onKeyDown="obtenerConductor(event,this.value,2); return tabular(event,this)" value="<?=$conductor2 ?>" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value='';"/>
          </span></td>
          <td><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarConductor.php?caja=2', 600, 500, 'ventana', 'Busqueda')"></div></td>
          <td colspan="2"><span class="Tablas">
            <input name="nombre_conductor2" type="text" class="Tablas" id="nombre_conductor2" style="width:200px;background:#FFFF99" value="<?=$nombre_conductor2 ?>" readonly=""/>
          </span></td>
          <td><input name="licencia_conductor2" type="checkbox" id="licencia_conductor2"  onKeyPress="return tabular(event,this)" value="1" checked="CHECKED" <? if($licencia_conductor2==1){echo "checked";} ?>>
Licencia</td>
        </tr>
        <tr>
          <td height="24">&nbsp;</td>
          <td>Conductor:</td>
          <td><span class="Tablas">
            <input name="conductor3" type="text" class="Tablas" id="conductor3" style="width:100px" onKeyDown="obtenerConductor(event,this.value,3); return tabular(event,this)" value="<?=$conductor3 ?>" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; "/>
          </span></td>
          <td><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarConductor.php?caja=3', 600, 500, 'ventana', 'Busqueda')"></div></td>
          <td colspan="2"><span class="Tablas">
            <input name="nombre_conductor3" type="text" class="Tablas" id="nombre_conductor3" style="width:200px;background:#FFFF99" value="<?=$nombre_conductor3 ?>" readonly=""/>
          </span></td>
          <td><input name="licencia_conductor3" type="checkbox" id="licencia_conductor3" onKeyPress="return tabular(event,this)" value="1" <? if($licencia_conductor3==1){echo "checked";} ?>>
Licencia</td>
        </tr>
        <tr>
          <td height="25">&nbsp;</td>
          <td>Unidad:</td>
          <td><span class="Tablas">
            <input name="unidad" type="text" class="Tablas" id="unidad" style="width:100px;" value="<?=$unidad ?>" onKeyPress="obtenerUnidad(event,this.value,1);return tabular(event,this)" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; "/>
          </span></td>
          <td><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarUnidad.php?caja=1&validarconbitacora=1', 600, 500, 'ventana', 'Busqueda')"></div></td>
          <td colspan="3"><input name="tarjeta_unidad" type="checkbox" id="tarjeta_unidad"  onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($tarjeta_unidad==1){echo "checked";} ?>>
Tarjeta
  <input name="poliza_unidad" type="checkbox" id="poliza_unidad" onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($poliza_unidad==1){echo "checked";} ?>>
P&oacute;liza
<input name="vrf_unidad" type="checkbox" id="vrf_unidad"  onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($vrf_unidad==1){echo "checked";} ?>>
VV
<input name="pcd_unidad" type="checkbox" id="pcd_unidad"  onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($pcd_unidad==1){echo "checked";} ?>>
PCD </td>
          </tr>
        <tr>
          <td height="25">&nbsp;</td>
          <td>Remolque:</td>
          <td><span class="Tablas">
            <input name="remolque1" type="text" class="Tablas" id="remolque1" style="width:100px;" value="<?=$remolque1 ?>" onKeyPress="obtenerUnidad(event,this.value,2); return tabular(event,this)" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; "/>
          </span></td>
          <td><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarUnidad.php?caja=2&validarconbitacora=1&nunidad='+document.all.remolque2.value, 600, 500, 'ventana', 'Busqueda')"></div></td>
          <td width="157"><input name="tarjeta_remolque1" type="checkbox" id="tarjeta_remolque1" onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($tarjeta_remolque1==1){echo "checked";} ?>>
Tarjeta
  <input name="poliza_remolque1" type="checkbox" id="poliza_remolque1" onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($poliza_remolque1==1){echo "checked";} ?>>
P&oacute;liza</td>
          <td width="45"><input name="pcd_remolque1" type="checkbox" id="pcd_remolque1" onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($pcd_remolque1==1){echo "checked";} ?>>
PCD</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="25">&nbsp;</td>
          <td>Remolque:</td>
          <td><span class="Tablas">
            <input name="remolque2" type="text" class="Tablas" id="remolque2" style="width:100px;" value="<?=$remolque2 ?>" onKeyPress="obtenerUnidad(event,this.value,3); return tabular(event,this)" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; "/>
          </span></td>
          <td><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarUnidad.php?caja=3&validarconbitacora=1&nunidad='+document.all.remolque1.value, 600, 500, 'ventana', 'Busqueda')"></div></td>
          <td><input name="tarjeta_remolque2" type="checkbox" id="tarjeta_remolque2" onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($tarjeta_remolque2==1){echo "checked";} ?>>
Tarjeta
  <input name="poliza_remolque2" type="checkbox" id="poliza_remolque2" onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($poliza_remolque2==1){echo "checked";} ?>>
P&oacute;liza </td>
          <td><input name="pcd_remolque2" type="checkbox" id="pcd_remolque2"  onKeyPress="return tabular(event,this)" value="1" checked="checked" <? if($pcd_remolque2==1){echo "checked";} ?>>
PCD</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="23">&nbsp;</td>
          <td>Ruta:</td>
          <td><span class="Tablas">
            <input name="ruta" type="text" class="Tablas" id="ruta" style="width:100px;" value="<?=$ruta ?>" onKeyDown="obtenerRuta(event,this.value); return tabular(event,this)" onKeyPress="return Numeros(event); " onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''; borrarDescripciones(this.name)"/>
            </span></td>
          <td><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarRuta.php', 600, 500, 'ventana', 'Busqueda')"></div></td>
          <td colspan="3"><span class="Tablas">
            <input name="descripcionruta" type="text" class="Tablas" id="descripcionruta" style="width:250px;background:#FFFF99" value="<?=$descripcionruta ?>" readonly=""/>
            </span></td>
          </tr>
               </tr>
         <tr>
          <td>&nbsp;</td>
          <td colspan="6" class="FondoTabla">Precinto</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Precinto:</td>
          <td><span class="Tablas">
        <input name="precinto" type="text" class="Tablas" id="precinto" style="width:70px;" onkeypress="if(event.keyCode==13){buscarPrecinto(this.value)};"/>
            <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onclick="abrirVentanaFija('../buscadores_generales/buscarPrecintosGen.php?funcion=obtenerPrecintosBusqueda&sucursal=<?=$_SESSION[IDSUCURSAL] ?>&tipo=bitacora', 550, 450, 'ventana', 'Busqueda');" /></span></td>
          <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="43%" id="td_agregar"><img src="../img/Boton_Agregari.gif" width="70" height="20" id="d_agregar" align="absbottom" style="cursor:pointer" onclick="buscarPrecinto()" /></td>
              <td width="57%"><img src="../img/Boton_Eliminar.gif" width="70" height="20" id="d_eliminar" style="cursor:pointer" onclick="eliminarFila()"/></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">           
          </table></td>
        </tr>
         <tr>
          <td>&nbsp;</td>
          <td colspan="6" class="FondoTabla">GASTOS</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Gastos:</td>
          <td><span class="Tablas">
            <input name="gastos" type="text" class="Tablas" id="gastos" style="width:100px;" value="<?=$gastos ?>" onKeyPress="if(event.keyCode==13){this.value = '$ '+numcredvar(this.value);}else{return tiposMoneda(event,this.value)}; "/>
          </span></td>
          <td>&nbsp;</td>
          <td colspan="2"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
            <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>"></td>
          <td>&nbsp;</td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="6"><table width="376" align="right" cellpadding="0" cellspacing="0" id="botones">
            <tr>
              <td width="126"><div id="btnImprimir" class="ebtn_Imprimirbitacora" onclick="imprimirBitacora()" style="visibility:hidden"></div></td>
              <td width="83" align="right"><div id="d_cancelar" class="ebtn_cancelar" onclick="confirmar('!Esta seguro de Cancelar la bitacora de salida?', '', 'cancelar();', '');"></div></td>
              <td width="79" align="right"><div id="d_guardar" class="ebtn_guardar" onClick="validar();"></div></td>
              <td width="86" align="right"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar(1);', '');"></div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="6"><label>
            <input type="hidden" name="textfield" >
            <input name="h_unidad" type="hidden" id="h_unidad"  />
            <input name="h_remolque1" type="hidden" id="h_remolque1"  />
            <input name="h_remolque2" type="hidden" id="h_remolque2" />
            <input name="fechahora" type="hidden" id="fechahora" />
            <input name="asignado" type="hidden" id="asignado" />
            <input name="fila" type="hidden" id="fila" />
            <input name="esmodificar" type="hidden" id="esmodificar" />
            <input name="folioLogistica" type="hidden" id="folioLogistica" value="<?=$_GET[bitacora] ?>" />
            <input name="h_conductor1" type="hidden" id="h_conductor1">
            <input name="h_conductor2" type="hidden" id="h_conductor2">
            <input name="h_conductor3" type="hidden" id="h_conductor3" />
          </label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="6" align="center"></td>
        </tr>
        </table>
    </div></td>
  </tr>
</table>
</form>
</body>                                                        
               <? $raiz = "../"; 
			   $funcion = "obtener"; 
			   $nombreBuscador = "buscadorClientes"; 
			   $funcionMostrar = "mostrarBuscador";
			   $funcionOcultar = "ocultarBuscador"; 
			   include("../buscadores_generales/buscadorIncrustado.php"); 
			   ?>                                                              
</html>