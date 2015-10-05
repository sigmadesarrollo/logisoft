<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ClaseTabsDivs.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script src="../../javascript/funciones.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
</link>
<script>
	var u = document.all;
	var tabs = new ClaseTabs();
	var mens = new ClaseMensajes();
	var tabla1 	= new ClaseTabla();
	var tabla2 	= new ClaseTabla();
	var tabla3 	= new ClaseTabla();
	var tabla4 	= new ClaseTabla();	
	var tabla5 	= new ClaseTabla();
	var tabla6 	= new ClaseTabla();
	var tabla7 	= new ClaseTabla();
	var tabla8 	= new ClaseTabla();
	var inicio		= 30;
	var sepaso		= 0;
	var cont		= 0;
	var totalDatos	= 0;
	var inicio4		= 30;
	var sepaso4		= 0;
	var cont4		= 0;
	var totalDatos4	= 0;
	mens.iniciar('../../javascript');
	
	jQuery(function($){	   
	   $('#fecha').mask("99/99/9999");
	   $('#fecha2').mask("99/99/9999");
	});
	
	tabla1.setAttributes({
		nombre:"detalle0",
		campos:[						
			{nombre:"FECHA", medida:70, alineacion:"left", datos:"fechar"},
			{nombre:"RUTA", medida:70, onDblClick:"obtenerRuta",alineacion:"center",  datos:"ruta"},
			{nombre:"UNIDAD", medida:100, onDblClick:"obtenerUnidad", alineacion:"center",  datos:"unidad"},
			{nombre:"IDOPERADOR", medida:4,tipo:"oculto", alineacion:"left",  datos:"idoperador1"},
			{nombre:"OPERADOR1", medida:135, onDblClick:"obtenerOperador",alineacion:"left", datos:"operador1"},
			{nombre:"IDOPERADOR2", medida:4,tipo:"oculto", alineacion:"left",  datos:"idoperador2"},
			{nombre:"OPERADOR2", medida:135, onDblClick:"obtenerOperador2",alineacion:"left", datos:"operador2"},
			{nombre:"IDOPERADOR", medida:4,tipo:"oculto", alineacion:"left",  datos:"idoperador3"},
			{nombre:"OPERADOR3", medida:135, onDblClick:"obtenerOperador3",alineacion:"left", datos:"operador3"},
			{nombre:"GUIAS", medida:70, onDblClick:"obtenerGuias",alineacion:"center",  datos:"guias"},
			{nombre:"RECORRIDO", medida:70, alineacion:"center", datos:"trecorrido"},
			{nombre:"ESTADO", medida:70, alineacion:"left", datos:"estado", onDblClick:"obtenerBitacora"},
			{nombre:"INCIDENCIAS", medida:70, onDblClick:"obtenerIncidencias", alineacion:"center", datos:"reporteincidencias"},
			{nombre:"BITACORA", medida:4, tipo:"oculto", alineacion:"center", datos:"bitacora"},
			{nombre:"ID", medida:4, tipo:"oculto", alineacion:"center", datos:"id"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"detalle1",
		campos:[
			{nombre:"RUTA", medida:200, alineacion:"center",  datos:"ruta"},
			{nombre:"TIEMPO RECORRIDO", medida:200, alineacion:"center", datos:"trecorrido"},
			{nombre:"TIEMPO CARGA/DESCARGA", medida:150, alineacion:"center", datos:"tiempocd"},
			{nombre:"RECORRIDO", medida:110, alineacion:"center", datos:"recorrido"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({
		nombre:"detalle2",
		campos:[
			{nombre:"NO. ECONOMICO", medida:105, alineacion:"center", datos:"unidad"},
			{nombre:"PRECINTOS ASIGNADOS", medida:250, alineacion:"center", datos:"precintoasignado"},
			{nombre:"CAPACIDAD PESO VOLUMETRICO", medida:200, alineacion:"center", datos:"cvolumen"},
			{nombre:"CAPACIDAD REAL", medida:105, alineacion:"center", datos:"ckilos"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla3"
	});
	
	tabla4.setAttributes({
		nombre:"detalle3",
		campos:[
			{nombre:"NOMBRE", medida:250, alineacion:"center",  datos:"nombre"},
			{nombre:"DIAS TRABAJADOS", medida:120, alineacion:"center", datos:"diastrabajados"},
			{nombre:"VIAJES", medida:150, alineacion:"center",  datos:"viajes"},
			{nombre:"KM RECORRIDOS", medida:140, alineacion:"center", datos:"kmrecorrido"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla4"
	});
	
	tabla5.setAttributes({
		nombre:"detalle4",
		campos:[
			{nombre:"FECHA", medida:100, alineacion:"center",  datos:"fecha"},
			{nombre:"GUIA", medida:100, alineacion:"center", datos:"guia"},
			{nombre:"DESTINO", medida:190, alineacion:"center",  datos:"destino"},					
			{nombre:"DESTINATARIO", medida:170, alineacion:"left", datos:"destinatario"},
			{nombre:"NO. PAQUETES", medida:100, alineacion:"center", datos:"nopaquetes"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla5"
	});
	
	tabla6.setAttributes({
		nombre:"detalle5",
		campos:[
			{nombre:"FECHA", medida:90, alineacion:"center", datos:"fecha"},
			{nombre:"TIPO INCIDENTE", medida:565, onDblClick:"obtenerDanoFaltante" ,alineacion:"center", datos:"incidencia"},
			{nombre:"BITACORA", medida:4, tipo:"oculto", alineacion:"center", datos:"bitacora"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla6"
	});
	
	tabla7.setAttributes({
		nombre:"detalle6",
		campos:[
			{nombre:"No_GUIA", medida:70, alineacion:"left", datos:"guia"},
			{nombre:"ESTADO_GUIA", medida:70, alineacion:"left", datos:"estado"},
			{nombre:"DESTINATARIO", medida:150, alineacion:"left", datos:"destinatario"},
			{nombre:"DESTINO", medida:50, alineacion:"center", datos:"destino"},
			{nombre:"ORIGEN", medida:50, alineacion:"center", datos:"origen"},
			{nombre:"FECHA_RECEPCION", medida:90, alineacion:"center", datos:"fecharecepcion"},
			{nombre:"FOLIO", medida:60, alineacion:"left", datos:"recepcion"},
			{nombre:"COMENTARIOS", medida:90, alineacion:"left", datos:"comentarios"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla7"
	});	
	
	tabla8.setAttributes({
		nombre:"detalle7",
		campos:[
			{nombre:"GUIA", medida:150,onDblClick:"Danosyfaltantes", alineacion:"center",  datos:"guia"},
			{nombre:"FECHA", medida:100, onDblClick:"Danosyfaltantes",alineacion:"center",  datos:"fecha"},
			{nombre:"UNIDAD ", medida:290, onDblClick:"Danosyfaltantes",alineacion:"center", datos:"unidad"},
			{nombre:"ESTADO", medida:120, onDblClick:"Danosyfaltantes",alineacion:"center", datos:"estado"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla8"
	});
	
	window.onload = function(){		
		tabla1.create();		
		tabs.iniciar({
			nombre:"tab", largo:710, alto:280, ajustex:11,
			ajustey:12, imagenes:"../../img"
		});
		tabs.agregarTabs('Descripcion de ruta',1,null);		
		u.tab_contenedor_id1.disabled=true;
		tabs.agregarTabs('Unidades',2,null);
		u.tab_contenedor_id2.disabled=true;
		tabs.agregarTabs('Estadisticas del operador',3,null);
		u.tab_contenedor_id3.disabled=true;
		tabs.agregarTabs('Relacion embarque consolidado',4,null);
		u.tab_contenedor_id4.disabled=true;
		tabs.agregarTabs('Incidentes en ruta',5,null);
		u.tab_contenedor_id5.disabled=true;
		tabs.agregarTabs('Reporte daños faltantes',6,null);
		u.tab_contenedor_id6.disabled=true;
		/*tabs.agregarTabs('Reporte de daños',7,null);
		u.tab_contenedor_id7.disabled=true;*/
		tabs.seleccionar(0);
	}
	
	function obtenerDetalle(){
		if(u.fecha.value == "" || u.fecha.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha inicial","¡Atención!","fecha");
			return false;
		}
		
		if(u.fecha2.value == "" || u.fecha2.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha final","¡Atención!","fecha2");
			return false;
		}
		
		var f1 = u.fecha.value.split("/");
		var f2 = u.fecha2.value.split("/");
		
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
		
		if(f1 > f2){
			mens.show("A","La fecha final debe ser mayor a la fecha inicial","¡Atención!","fecha2");
			return false;
		}		
		consultaTexto("obtenerTotal","consultas.php?accion=1&tipo=0&fechainicio="+u.fecha.value
		+"&fechafin="+u.fecha2.value+"&s="+Math.random());
	}
	
	function obtenerTotal(datos){
		u.contadordes.value = datos;
		u.mostrardes2.value = datos;
		u.totaldes.value = "00";
		if(u.contadordes.value > 30){
			u.paginado.style.visibility = "visible";
			u.d_atrasdes.style.visibility = "hidden";
			u.primero.style.visibility = "hidden";
			totalDatos = parseInt(u.contadordes.value / 30);
		}else{
			u.paginado.style.visibility = "hidden";
		}
		consultaTexto("mostrarDetalle","consultas.php?accion=1&tipo=1&inicio=0&fechainicio="+u.fecha.value
		+"&fechafin="+u.fecha2.value+"&s="+Math.random());
	}
	
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro") < 0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
		}else{
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!","fecha");
			tabla1.clear();
		}
	}
	
	function paginacion(tipo){		
		if(tipo == "atras"){
			u.d_sigdes.style.visibility = "visible";
			u.d_ultimo.style.visibility = "visible";
			u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
			if(parseFloat(u.totaldes.value) <= "1"){
				u.totaldes.value = "00";
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes.value / 30) - 1;
					u.totaldes.value = con * 30;
					u.ultimo.value = "";
					consultaTexto("mostrarDetalle","consultas.php?accion=1&tipo=1&inicio="+u.totaldes.value
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				}else{
					u.d_atrasdes.style.visibility = "hidden";
					u.primero.style.visibility = "hidden";					
					consultaTexto("mostrarDetalle","consultas.php?accion=1&tipo=1&inicio=0&fechainicio="+u.fecha.value
					+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				}
			}else{
				if(sepaso!=0){
					u.mostrardes.value = sepaso;
					sepaso = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes.value / 30) - 1;
					u.totaldes.value = con * 30;
					u.ultimo.value = "";
				}				
				consultaTexto("mostrarDetalle","consultas.php?accion=1&tipo=1&inicio="+u.totaldes.value
				+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
			}
		}else{
			cont++;
			u.d_atrasdes.style.visibility = "visible";
			u.primero.style.visibility = "visible";
			u.totaldes.value = inicio + parseFloat(u.totaldes.value);
			if(parseFloat(u.totaldes.value) > parseFloat(u.contadordes.value)){
				u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					u.mostrardes.value = u.contadordes.value;
				}
				u.d_sigdes.style.visibility = "hidden";
				u.d_ultimo.style.visibility = "hidden";
			}else{
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;				
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){					
					sepaso	=	u.mostrardes.value;
					u.mostrardes.value = u.contadordes.value;
				}
				if(cont>=totalDatos){
					u.d_sigdes.style.visibility = "hidden";
					u.d_ultimo.style.visibility = "hidden";
					cont = 0;
				}
				consultaTexto("mostrarDetalle","consultas.php?accion=1&tipo=1&inicio="+u.totaldes.value
				+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
			}
		}
	}
	
	function obtenerPrimero(){
		u.totaldes.value = "00";
		u.d_sigdes.style.visibility = "visible";
		u.d_ultimo.style.visibility = "visible";
		consultaTexto("mostrarDetalle","consultas.php?accion=1&tipo=1&inicio="+u.totaldes.value
		+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
	}
	function obtenerUltimo(){
		u.ultimo.value = "SI";
		u.d_sigdes.style.visibility = "hidden";
		u.d_ultimo.style.visibility = "hidden";
		consultaTexto("mostrarDetalle","consultas.php?accion=ultimoprincipal&fechainicio="+u.fecha.value
		+"&fechafin="+u.fecha2.value+"&s="+Math.random());
	}
	
	function obtenerRuta(){
		var obj = tabla1.getSelectedRow();
		v_bitacoraRuta = obj.bitacora;
		if(!tabla2.creada()){
			tabla2.create();	
		}		
		consultaTexto("mostrarRuta","consultas.php?accion=2&bitacora="+obj.bitacora);
	}
	
	function mostrarRuta(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla2.setJsonData(obj);
			u.tab_contenedor_id1.disabled=false;
			tabs.seleccionar(1);			
		}
	}
	
	function obtenerUnidad(){
		var obj = tabla1.getSelectedRow();
		v_bitacoraUnidad = obj.bitacora;
		if(!tabla3.creada()){
			tabla3.create();	
		}
		consultaTexto("mostrarUnidad","consultas.php?accion=3&bitacora="+obj.bitacora);
	}
	
	function mostrarUnidad(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla3.setJsonData(obj);
			u.tab_contenedor_id2.disabled=false;
			tabs.seleccionar(2);
		}
	}
	
	function obtenerOperador(){
		var obj = tabla1.getSelectedRow();
		v_operador1 = obj.idoperador1;
		v_operador2 = "";
		v_operador3 = "";
		if(!tabla4.creada()){
			tabla4.create();	
		}
		consultaTexto("mostrarOperador","consultas.php?accion=4&operador="+obj.idoperador1
		+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value);
	}
	
	function mostrarOperador(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla4.setJsonData(obj);
			u.tab_contenedor_id3.disabled=false;
			tabs.seleccionar(3);
		}
	}
	
	function obtenerOperador2(){
		var obj = tabla1.getSelectedRow();
		v_operador2 = obj.idoperador2;
		v_operador3 = "";
		v_operador1 = "";
		if(!tabla4.creada()){
			tabla4.create();	
		}
		consultaTexto("mostrarOperador2","consultas.php?accion=4&operador="+obj.idoperador2
		+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value);
	}
	
	function mostrarOperador2(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla4.setJsonData(obj);
			u.tab_contenedor_id3.disabled=false;
			tabs.seleccionar(3);
		}
	}
	
	function obtenerOperador3(){
		var obj = tabla1.getSelectedRow();
		v_operador3 = obj.idoperador3;
		v_operador1 = "";
		v_operador2 = "";
		if(!tabla4.creada()){
			tabla4.create();	
		}
		consultaTexto("mostrarOperador3","consultas.php?accion=4&operador="+obj.idoperador3
		+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value);
	}
	
	function mostrarOperador3(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla4.setJsonData(obj);
			u.tab_contenedor_id3.disabled=false;
			tabs.seleccionar(3);
		}
	}
	
	function obtenerGuias(){
		var obj = tabla1.getSelectedRow();
		v_idtabla = obj.id;
		if(!tabla5.creada()){
			tabla5.create();	
		}
		idtabla = obj.id;
		consultaTexto("obtenerTotalGuias","consultas.php?accion=5&inicio=0&idtabla="+obj.id
		+"&s="+Math.random());
	}
	
	function obtenerTotalGuias(datos){
		u.contadordes4.value = datos;
		u.mostrardes42.value = datos;
		u.totaldes4.value = "00";
		if(u.contadordes4.value > 30){
			u.paginado4.style.visibility = "visible";
			u.d_atrasdes4.style.visibility = "hidden";
			u.primero4.style.visibility = "hidden";
			totalDatos4 = parseInt(u.contadordes4.value / 30);
		}else{
			u.paginado4.style.visibility = "hidden";
		}
		consultaTexto("mostrarGuias","consultas.php?accion=5&tipo=1&inicio=0&idtabla="+idtabla
		+"&s="+Math.random());
	}
	
	function mostrarGuias(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla5.setJsonData(obj);
			u.tab_contenedor_id4.disabled=false;
			tabs.seleccionar(4);
		}
	}
	
	function paginacion4(tipo){		
		if(tipo == "atras"){
			u.d_sigdes4.style.visibility = "visible";
			u.d_ultimo4.style.visibility = "visible";
			u.totaldes4.value = parseFloat(u.totaldes4.value) - inicio4;
			if(parseFloat(u.totaldes4.value) <= "1"){
				u.totaldes4.value = "00";
				u.mostrardes4.value = parseFloat(u.mostrardes4.value) - inicio4;
				if(parseFloat(u.mostrardes4.value) < inicio4){
					u.mostrardes4.value = inicio4;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes4.value / 30) - 1;
					u.totaldes4.value = con * 30;
					u.ultimo4.value = "";
					consultaTexto("mostrarGuias","consultas.php?accion=1&tipo=1&inicio="+u.totaldes4.value
					+"&idtabla="+idtabla+"&s="+Math.random());
				}else{
					u.d_atrasdes4.style.visibility = "hidden";
					u.primero4.style.visibility = "hidden";					
					consultaTexto("mostrarGuias","consultas.php?accion=1&tipo=1&inicio=0&idtabla="+idtabla
					+"&s="+Math.random());
				}
			}else{
				if(sepaso4!=0){
					u.mostrardes4.value = sepaso4;
					sepaso4 = 0;
				}
				u.mostrardes4.value = parseFloat(u.mostrardes4.value) - inicio4;
				if(parseFloat(u.mostrardes4.value) < inicio4){
					u.mostrardes4.value = inicio4;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes4.value / 30) - 1;
					u.totaldes4.value = con * 30;
					u.ultimo4.value = "";
				}				
				consultaTexto("mostrarGuias","consultas.php?accion=1&tipo=1&inicio="+u.totaldes.value
				+"&idtabla="+idtabla+"&s="+Math.random());
			}
		}else{
			cont4++;
			u.d_atrasdes4.style.visibility = "visible";
			u.primero4.style.visibility = "visible";
			u.totaldes4.value = inicio4 + parseFloat(u.totaldes4.value);
			if(parseFloat(u.totaldes4.value) > parseFloat(u.contadordes4.value)){
				u.totaldes4.value = parseFloat(u.totaldes4.value) - inicio4;
				u.mostrardes4.value = parseFloat(u.mostrardes4.value) + inicio4;
				if(parseFloat(u.mostrardes4.value)>parseFloat(u.contadordes4.value)){
					u.mostrardes4.value = u.contadordes4.value;
				}
				u.d_sigdes4.style.visibility = "hidden";
				u.d_ultimo4.style.visibility = "hidden";
			}else{
				u.mostrardes4.value = parseFloat(u.mostrardes4.value) + inicio4;				
				if(parseFloat(u.mostrardes4.value)>parseFloat(u.contadordes4.value)){					
					sepaso4	=	u.mostrardes4.value;
					u.mostrardes4.value = u.contadordes4.value;
				}
				if(cont>=totalDatos){
					u.d_sigdes4.style.visibility = "hidden";
					u.d_ultimo4.style.visibility = "hidden";
					cont4 = 0;
				}
				consultaTexto("mostrarGuias","consultas.php?accion=1&tipo=1&inicio="+u.totaldes.value
				+"&idtabla="+idtabla+"&s="+Math.random());
			}
		}
	}
	
	function obtenerPrimero4(){
		u.totaldes4.value = "00";
		u.d_sigdes4.style.visibility = "visible";
		u.d_ultimo4.style.visibility = "visible";
		consultaTexto("mostrarGuias","consultas.php?accion=1&tipo=1&inicio="+u.totaldes.value
		+"&idtabla="+idtabla+"&s="+Math.random());
	}
	function obtenerUltimo4(){
		u.ultimo4.value = "SI";
		u.d_sigdes4.style.visibility = "hidden";
		u.d_ultimo4.style.visibility = "hidden";
		consultaTexto("mostrarGuias","consultas.php?accion=ultimoprincipal&idtabla="+idtabla+"&s="+Math.random());
	}
	
	function obtenerIncidencias(){
		var obj = tabla1.getSelectedRow();
		v_bitacoraIncidencia = obj.bitacora;
		if(!tabla6.creada()){
			tabla6.create();	
		}
		consultaTexto("mostrarIncidencias","consultas.php?accion=6&bitacora="+obj.bitacora);
	}

	function mostrarIncidencias(datos){		
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla6.setJsonData(obj);
			u.tab_contenedor_id5.disabled=false;			
			tabs.seleccionar(5);
			tabs.moverManual(-100);
		}
	}
	
	function obtenerDanoFaltante(){
		var obj = tabla1.getSelectedRow();
		if(!tabla7.creada()){
			tabla7.create();	
		}
		consultaTexto("mostrarDanoFaltante","consultas.php?accion=7&bitacora="+obj.bitacora);
	}
	
	function mostrarDanoFaltante(datos){	
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla7.setJsonData(obj);
			u.tab_contenedor_id6.disabled=false;			
			tabs.seleccionar(6);
			tabs.moverManual(-205);
		}
	}
	
	function obtenerBitacora(){
		var obj = tabla1.getSelectedRow();
		abrirVentanaFija('../../corm/bitacoraSalida.php?bitacora='+obj.bitacora+'&tipo=embarques', 600, 500, 'ventana', 'Bitacora de Salida');
	}
	
	function limpiar(){
		u.fecha.value = "<?=date('d/m/Y') ?>";
		u.fecha2.value = "<?=date('d/m/Y') ?>";
		inicio		= 30;
		sepaso		= 0;
		cont		= 0;
		totalDatos	= 0;
		inicio4		= 30;
		sepaso4		= 0;
		cont4		= 0;
		totalDatos4	= 0;
		if(tabla1.creada()){
			tabla1.clear();
		}
		if(tabla2.creada()){
			tabla2.clear();
		}
		if(tabla3.creada()){
			tabla3.clear();
		}
		if(tabla4.creada()){
			tabla4.clear();
		}
		if(tabla5.creada()){
			tabla5.clear();
		}
		if(tabla6.creada()){
			tabla6.clear();
		}
		if(tabla7.creada()){
			tabla7.clear();
		}
		if(tabla8.creada()){
			tabla8.clear();
		}		
		u.tab_contenedor_id1.disabled=true;		
		u.tab_contenedor_id2.disabled=true;
		u.tab_contenedor_id3.disabled=true;
		u.tab_contenedor_id4.disabled=true;
		u.tab_contenedor_id5.disabled=true;
		u.tab_contenedor_id6.disabled=true;
		//u.tab_contenedor_id7.disabled=true;
		tabs.seleccionar(0);
	}
	
	function imprimirReporte(tipo){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/logistica/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/logistica/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/logistica/";
		}
		switch (tipo){
			case 1:
				window.open(v_dir+"generarExcelLogistica.php?accion=1&titulo=RUTAS&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&val="+Math.random());
			break;
			
			case 2:
				window.open(v_dir+"generarExcelLogistica.php?accion=2&titulo=DESCRIPCION DE RUTA&bitacora="+v_bitacoraRuta
				+"&val="+Math.random());
			break;
			
			case 3:
				window.open(v_dir+"generarExcelLogistica.php?accion=3&titulo=UNIDADES&bitacora="+v_bitacoraUnidad
				+"&val="+Math.random());
			break;
			
			case 4:
				var v_operador = "";
				if(v_operador1!=""){
					v_operador = v_operador1;
				}
				
				if(v_operador2!=""){
					v_operador = v_operador2;
				}
				
				if(v_operador3!=""){
					v_operador = v_operador3;
				}
				window.open(v_dir+"generarExcelLogistica.php?accion=4&titulo=ESTADISTICAS DEL OPERADOR&operador="+v_operador
				+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&val="+Math.random());
			break;
			
			case 5:
				window.open(v_dir+"generarExcelLogistica.php?accion=5&titulo=RELACION EMBARQUE CONSOLIDADO&idtabla="+v_idtabla
				+"&val="+Math.random());
			break;
			
			case 6:
				window.open(v_dir+"generarExcelLogistica.php?accion=6&titulo=INCIDENTES EN RUTA&bitacora="+v_bitacoraIncidencia
				+"&val="+Math.random());
			break;			
		}
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id0">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="43">De:</td>
    <td width="148"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y') ?>" onkeypress="if(event.keyCode==13){document.all.fecha2.focus()}" onkeydown="if(event.keyCode==9){validarFecha(this.value,'fecha');}" onblur="if(this.value!='' || this.value!='__/__/____'){validarFecha(this.value,'fecha');}" />
    <img src="../../img/calendario.gif" width="20" height="20" align="absbottom" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"/></td>
    <td width="104">&nbsp;</td>
    <td width="48">Al:</td>
    <td width="136"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y') ?>"  onkeydown="if(event.keyCode==9){validarFecha(this.value,'fecha2');}" onblur="if(this.value!='' || this.value!='__/__/____'){validarFecha(this.value,'fecha');}"/>
    <img src="../../img/calendario.gif" width="20" height="20" align="absbottom" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"/></td>
    <td width="110"><img src="../../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onclick="obtenerDetalle()" /></td>
    <td width="111"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')" style="cursor:pointer" /></td>
  </tr>
  <tr>
    <td colspan="7">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle0">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="7" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(1)"></div></td>
  </tr>
  <tr>
    <td colspan="7"><div id="paginado" align="center" style="visibility:hidden">
              <input name="totaldes" type="hidden" id="totaldes" value="00" />
              <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="obtenerPrimero()" /> <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('siguiente')" /> <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="obtenerUltimo()" />
              <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" />
              <input name="mostrardes2" class="Tablas" type="hidden" id="mostrardes2" value="<?=$tdes; ?>" />
              <input name="ultimo" class="Tablas" type="hidden" id="ultimo" />
          </div></td>	
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id1">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle1">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(2)"></div></td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado1" align="center" style="visibility:hidden">
              <input name="totaldes1" type="hidden" id="totaldes1" value="00" />
              <input name="contadordes1" type="hidden" id="contadordes1"/>
              <img src="../../img/first.gif" width="16" height="16" id="primero1" style="cursor:pointer"  onclick="obtenerPrimero1()" /> <img src="../../img/previous.gif" width="16" height="16" id="d_atrasdes1" style="cursor:pointer" onclick="paginacion1('atras')" /> <img src="../../img/next.gif" width="16" height="16" id="d_sigdes1" style="cursor:pointer" onclick="paginacion1('siguiente')" /> <img src="../../img/last.gif" width="16" height="16" id="d_ultimo1" style="cursor:pointer" onclick="obtenerUltimo1()" />
              <input name="mostrardes1" class="Tablas" type="hidden" id="mostrardes1" />
              <input name="mostrardes12" class="Tablas" type="hidden" id="mostrardes12" />
              <input name="ultimo1" class="Tablas" type="hidden" id="ultimo1" />
          </div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id2">
<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle2">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(3)"></div></td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado2" align="center" style="visibility:hidden">
              <input name="totaldes2" type="hidden" id="totaldes2" value="00" />
              <input name="contadordes2" type="hidden" id="contadordes2"/>
              <img src="../../img/first.gif" width="16" height="16" id="primero2" style="cursor:pointer" onclick="obtenerPrimero2()" /> <img src="../../img/previous.gif" width="16" height="16" id="d_atrasdes2" style="cursor:pointer" onclick="paginacion2('atras')" /> <img src="../../img/next.gif" width="16" height="16" id="d_sigdes2" style="cursor:pointer" onclick="paginacion2('siguiente')" /> <img src="../../img/last.gif" width="16" height="16" id="d_ultimo2" style="cursor:pointer" onclick="obtenerUltimo2()" />
              <input name="mostrardes2" class="Tablas" type="hidden" id="mostrardes2" />
              <input name="mostrardes22" class="Tablas" type="hidden" id="mostrardes22" />
              <input name="ultimo2" class="Tablas" type="hidden" id="ultimo2" />
          </div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id3">
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle3">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(4)"></div></td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado3" align="center" style="visibility:hidden">
              <input name="totaldes3" type="hidden" id="totaldes3" value="00" />
              <input name="contadordes3" type="hidden" id="contadordes3"/>
              <img src="../../img/first.gif" width="16" height="16" id="primero3" style="cursor:pointer" onclick="obtenerPrimero3()" /> <img src="../../img/previous.gif" width="16" height="16" id="d_atrasdes3" style="cursor:pointer" onclick="paginacion3('atras')" /> <img src="../../img/next.gif" width="16" height="16" id="d_sigdes3" style="cursor:pointer" onclick="paginacion3('siguiente')" /> <img src="../../img/last.gif" width="16" height="16" id="d_ultimo3" style="cursor:pointer" onclick="obtenerUltimo3()" />
              <input name="mostrardes3" class="Tablas" type="hidden" id="mostrardes3" />
              <input name="mostrardes32" class="Tablas" type="hidden" id="mostrardes32" />
              <input name="ultimo3" class="Tablas" type="hidden" id="ultimo3" />
          </div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id4">
<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle4">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(5)"></div></td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado4" align="center" style="visibility:hidden">
              <input name="totaldes4" type="hidden" id="totaldes4" value="00" />
              <input name="contadordes4" type="hidden" id="contadordes4"/>
              <img src="../../img/first.gif" width="16" height="16" id="primero4" style="cursor:pointer" onclick="obtenerPrimero4()" /> <img src="../../img/previous.gif" width="16" height="16" id="d_atrasdes4" style="cursor:pointer" onclick="paginacion4('atras')" /> <img src="../../img/next.gif" width="16" height="16" id="d_sigdes4" style="cursor:pointer" onclick="paginacion4('siguiente')" /> <img src="../../img/last.gif" width="16" height="16" id="d_ultimo4" style="cursor:pointer" onclick="obtenerUltimo4()" />
              <input name="mostrardes4" class="Tablas" type="hidden" id="mostrardes4" />
              <input name="mostrardes42" class="Tablas" type="hidden" id="mostrardes42" />
              <input name="ultimo4" class="Tablas" type="hidden" id="ultimo4" />
          </div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id5">
<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle5">
		</table>
	</div>	</td>
  </tr>
   <tr>
  	<td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(6)"></div></td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado5" align="center" style="visibility:hidden">
              <input name="totaldes5" type="hidden" id="totaldes5" value="00" />
              <input name="contadordes5" type="hidden" id="contadordes5"/>
              <img src="../../img/first.gif" width="16" height="16" id="primero5" style="cursor:pointer" onclick="obtenerPrimero5()" /> <img src="../../img/previous.gif" width="16" height="16" id="d_atrasdes5" style="cursor:pointer" onclick="paginacion5('atras')" /> <img src="../../img/next.gif" width="16" height="16" id="d_sigdes5" style="cursor:pointer" onclick="paginacion5('siguiente')" /> <img src="../../img/last.gif" width="16" height="16" id="d_ultimo5" style="cursor:pointer" onclick="obtenerUltimo4()" />
              <input name="mostrardes5" class="Tablas" type="hidden" id="mostrardes5" />
              <input name="mostrardes52" class="Tablas" type="hidden" id="mostrardes52" />
              <input name="ultimo5" class="Tablas" type="hidden" id="ultimo5" />
          </div></td>
  </tr>
</table>
</div>

<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id6">
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle6">
		</table>
	</div></td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado6" align="center" style="visibility:hidden">
              <input name="totaldes6" type="hidden" id="totaldes6" value="00" />
              <input name="contadordes6" type="hidden" id="contadordes6"/>
              <img src="../../img/first.gif" width="16" height="16" id="primero6" style="cursor:pointer" onclick="obtenerPrimero6()" /> <img src="../../img/previous.gif" width="16" height="16" id="d_atrasdes6" style="cursor:pointer" onclick="paginacion6('atras')" /> <img src="../../img/next.gif" width="16" height="16" id="d_sigdes6" style="cursor:pointer" onclick="paginacion6('siguiente')" /> <img src="../../img/last.gif" width="16" height="16" id="d_ultimo6" style="cursor:pointer" onclick="obtenerUltimo4()" />
              <input name="mostrardes6" class="Tablas" type="hidden" id="mostrardes6" />
              <input name="mostrardes62" class="Tablas" type="hidden" id="mostrardes62" />
              <input name="ultimo6" class="Tablas" type="hidden" id="ultimo6" />
          </div></td>
  </tr>
</table>
</div>
<table width="600" height="66" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla Estilo4">Reporte Principal de Logistica </td>
  </tr>
  <tr>
    <td height="400px" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table id="tab" cellpadding="0" cellspacing="0" border="0">
        </table></td>
      </tr>
    </table></td>	
  </tr>
</table>
</form>
</body>
</html>
