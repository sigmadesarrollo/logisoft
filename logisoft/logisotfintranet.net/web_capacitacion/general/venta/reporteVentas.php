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
	var tabla9	= new ClaseTabla();
	var tabla10	= new ClaseTabla();
	var tabla11	= new ClaseTabla();
	mens.iniciar('../../javascript');
	
	//para paginado
	var pag1_cantidadporpagina = 30;
	
	jQuery(function($){	   
	   $('#fecha').mask("99/99/9999");
	   $('#fecha2').mask("99/99/9999");
	});
	
	tabla1.setAttributes({
		nombre:"detalle0",
		campos:[
			{nombre:"SUCURSAL", medida:140, alineacion:"left", datos:"sucursal"},
			{nombre:"CONVENIO", medida:170, onDblClick:'obtenerConvenio', tipo:"moneda", alineacion:"center",  datos:"convenio"},
			{nombre:"SIN CONVENIO", medida:170, tipo:"moneda", alineacion:"center",  datos:"sinconvenio"},
			{nombre:"TOTAL", medida:170, tipo:"moneda", alineacion:"center", datos:"total"}
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"detalle1",
		campos:[
			{nombre:"SUCURSAL", medida:180, alineacion:"left", datos:"sucursal"},
			{nombre:"NORMALES", medida:120, onDblClick:"t2_obtenerNormales", tipo:"moneda", alineacion:"center",  datos:"normales"},
			{nombre:"PREPAGADAS", medida:120, onDblClick:"t2_obtenerPrepagadas", tipo:"moneda", alineacion:"center",  datos:"prepagadas"},
			{nombre:"CONSIGNACION", medida:120, onDblClick:"t2_obtenerConsignacion", tipo:"moneda", alineacion:"center",  datos:"consignacion"},
			{nombre:"TOTAL", medida:120, tipo:"moneda", alineacion:"center", datos:"total"}
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({
		nombre:"detalle2",
		campos:[
			{nombre:"CONTADO", medida:130, tipo:"moneda", alineacion:"right", datos:"contado", onDblClick:"t3_obtenerContado"},
			{nombre:"CREDITO", medida:130, tipo:"moneda", alineacion:"right",  datos:"credito"},
			{nombre:"COBCONTADO", medida:130, tipo:"moneda", alineacion:"right",  datos:"cobcontado"},
			{nombre:"COBCREDITO", medida:130, tipo:"moneda", alineacion:"right",  datos:"cobcredito"},
			{nombre:"TOTAL", medida:130, tipo:"moneda", alineacion:"right", datos:"total", onDblClick:"t3_obtenerTotal"}
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla3"
	});
	
	tabla4.setAttributes({
		nombre:"detalle3",
		campos:[
			{nombre:"# CLIENTE", medida:100, alineacion:"left", datos:"idcliente"},
			{nombre:"CLIENTE", medida:250, alineacion:"left",datos:"cliente"},
			{nombre:"DESTINO", medida:150, alineacion:"left",  datos:"destino"},
			{nombre:"GUIA", medida:130, alineacion:"right", datos:"folio"},
			{nombre:"IMPORTE", medida:120, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla4"
	});
	
	tabla5.setAttributes({
		nombre:"detalle4",
		campos:[
			{nombre:"# CLIENTE", onDblClick:"t5_obtenerCliente", medida:50, alineacion:"left", datos:"idcliente"},
			{nombre:"CLIENTE", medida:200, alineacion:"left",datos:"cliente"},
			{nombre:"DESTINO", medida:110, alineacion:"left",  datos:"destino"},
			{nombre:"GUIA", medida:100, alineacion:"center", datos:"folio"},
			{nombre:"CONDICION DE PAGO", medida:100, alineacion:"center", datos:"tipopago"},
			{nombre:"IMPORTE", medida:100, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla5"
	});
	
	tabla6.setAttributes({//ENVIADOS
		nombre:"detalle5",	
		campos:[	
			{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecharealizacion"},	
			{nombre:"GUIA", medida:80, alineacion:"center",  datos:"folio"},	
			{nombre:"DESTINO", medida:50, alineacion:"center",  datos:"destino"},	
			{nombre:"CLIENTE ORIGEN/DESTINO", medida:150, alineacion:"left", datos:"origendestino"},				
			{nombre:"FLETE", medida:50, alineacion:"center",  datos:"flete"},	
			{nombre:"ENVIO", medida:50, alineacion:"center",  datos:"tipoentrega"},	
			{nombre:"PAQUETES", medida:50, alineacion:"center",  datos:"paquetes"},	
			{nombre:"KILOGRAMOS", medida:50, alineacion:"center",  datos:"totalkilogramos"},	
			{nombre:"TOTAL", medida:50, tipo:"moneda", alineacion:"right",  datos:"total"},	
			{nombre:"ESTADO", medida:100, alineacion:"center",  datos:"estado"},	
			{nombre:"QUIEN RECIBIO", medida:150, alineacion:"center",  datos:"recibio"}	
		],	
		filasInicial:18,	
		alto:100,	
		seleccion:true,	
		ordenable:false,
		nombrevar:"tabla6"	
	});
	
	tabla7.setAttributes({//RECIBIDOS
		nombre:"detalle6",	
		campos:[	
			{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecharealizacion"},	
			{nombre:"GUIA", medida:80, alineacion:"center",  datos:"folio"},	
			{nombre:"DESTINO", medida:50, alineacion:"center",  datos:"destino"},	
			{nombre:"CLIENTE ORIGEN/DESTINO", medida:150, alineacion:"left", datos:"origendestino"},				
			{nombre:"FLETE", medida:50, alineacion:"center",  datos:"flete"},	
			{nombre:"ENVIO", medida:50, alineacion:"center",  datos:"tipoentrega"},	
			{nombre:"PAQUETES", medida:50, alineacion:"center",  datos:"paquetes"},	
			{nombre:"KILOGRAMOS", medida:50, alineacion:"center",  datos:"totalkilogramos"},	
			{nombre:"TOTAL", medida:50, tipo:"moneda", alineacion:"right",  datos:"total"},	
			{nombre:"ESTADO", medida:100, alineacion:"center",  datos:"estado"},	
			{nombre:"QUIEN RECIBIO", medida:150, alineacion:"center",  datos:"recibio"}
		],	
		filasInicial:18,	
		alto:100,	
		seleccion:true,	
		ordenable:false,
		nombrevar:"tabla7"	
	});
	
	tabla8.setAttributes({//VENTAS POR GUIAS PREPAGADAS
		nombre:"detalle7",
		campos:[
			{nombre:"SUCURSAL", medida:60, alineacion:"left", datos:"sucursal"},
			{nombre:"# CLIENTE", medida:60, alineacion:"left", datos:"cliente"},
			{nombre:"CLIENTE", medida:100, alineacion:"left",datos:"nombrecliente"},
			{nombre:"VENTA", medida:60, alineacion:"center",  datos:"venta"},
			{nombre:"FOLIOS", medida:120, alineacion:"left", datos:"folios"},
			{nombre:"FACTURA", medida:60, alineacion:"right", datos:"factura"},
			{nombre:"IMPORTE", medida:80, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"SIN FA OTROS", medida:120, tipo:"moneda", onDblClick:"t8_obtenerPendientes", alineacion:"right", datos:"porfacturar"}
		],

		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla8"
	});
	
	tabla9.setAttributes({//(PREPAGADAS) SERVICIOS PENDIENTES DE FACTURAR
		nombre:"detalle8",
		campos:[
			{nombre:"FECHA", medida:50, alineacion:"left", datos:"fecha"},
			{nombre:"ORIGEN", medida:40, alineacion:"center",  datos:"prefijoorigen"},
			{nombre:"DESTINO", medida:40, alineacion:"center",  datos:"prefijodestino"},
			{nombre:"# GUIA", medida:100, alineacion:"center", datos:"folio"},
			{nombre:"# PAQUETE", medida:60, alineacion:"center",  datos:"paquetes"},
			{nombre:"KILOGRAMOS", medida:60, alineacion:"center",  datos:"totalkilogramos"},
			{nombre:"VALOR DECLARADO", medida:90, tipo:"moneda", alineacion:"right",  datos:"valordeclarado"},
			{nombre:"FLETE", medida:60, alineacion:"right", tipo:"moneda",  datos:"flete"},
			{nombre:"EXC. KILOGRAMOS", medida:90, alineacion:"center",  datos:"kgexcedente"},
			{nombre:"COSTO SEGURO", medida:120, alineacion:"right", tipo:"moneda",  datos:"seguro"},
			{nombre:"CARGO COMBUSTIBLE", medida:100, alineacion:"right", tipo:"moneda",  datos:"combustible"},
			{nombre:"SUBTOTAL", medida:60, alineacion:"right", tipo:"moneda",  datos:"subtotal"},
			{nombre:"IVA", medida:60, alineacion:"right", tipo:"moneda", datos:"iva"},
			{nombre:"IVA RETENIDO", medida:90, alineacion:"right", tipo:"moneda",  datos:"ivaretenido"},
			{nombre:"TOTAL", medida:60, alineacion:"right", tipo:"moneda",  datos:"total"}
		],
		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla9"
	});
	
	tabla10.setAttributes({//CONSIGNACION
		nombre:"detalle9",
		campos:[
			{nombre:"SUC", medida:40, alineacion:"left", datos:"sucursal"},			
			{nombre:"CLIENTE", medida:60, alineacion:"left", datos:"idcliente"},
			{nombre:"NOMBRE", medida:180, alineacion:"left",datos:"nombrecliente"},
			{nombre:"CANT. FOLIOS", medida:70, alineacion:"right",  datos:"cantidad"},
			{nombre:"FACTURA", medida:50, alineacion:"right", datos:"factura"},
			{nombre:"IMPORTE FACTURA", medida:100, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"SERV. ADICIONALES", medida:90, tipo:"moneda", alineacion:"right", datos:"servicios"},
			{nombre:"TOTAL", medida:80, onDblClick:"t10_obtenerTotal", alineacion:"right", tipo:"moneda", datos:"total"},
			{nombre:"", medida:4, alineacion:"right", datos:"folios", tipo:"oculto"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla10"
	});	
	
	tabla11.setAttributes({//GUIAS Y SERVICIOS
		nombre:"detalle10",
		campos:[
			{nombre:"FECHA", medida:40, alineacion:"left", datos:"fecha"},
			{nombre:"ORIGEN", medida:40, alineacion:"center",  datos:"prefijoorigen"},
			{nombre:"DESTINO", medida:40, alineacion:"center",  datos:"prefijodestino"},
			{nombre:"# GUIA", medida:90, alineacion:"center", datos:"folio"},
			{nombre:"# PAQUETE", medida:60, alineacion:"center",  datos:"paquetes"},
			{nombre:"KILOGRAMOS", medida:60, alineacion:"center",  datos:"totalkilogramos"},
			{nombre:"VALOR DECLARADO", medida:90, tipo:"moneda", alineacion:"right",  datos:"valordeclarado"},
			{nombre:"FLETE", medida:60, alineacion:"right", tipo:"moneda",  datos:"flete"},
			{nombre:"COSTO SEGURO", medida:120, alineacion:"right", tipo:"moneda",  datos:"seguro"},
			{nombre:"CARGO COMBUSTIBLE", medida:100, alineacion:"right", tipo:"moneda",  datos:"combustible"},
			{nombre:"SUBTOTAL", medida:60, alineacion:"right", tipo:"moneda",  datos:"subtotal"},
			{nombre:"IVA", medida:60, alineacion:"right", tipo:"moneda", datos:"iva"},
			{nombre:"IVA RETENIDO", medida:90, alineacion:"right", tipo:"moneda",  datos:"ivaretenido"},
			{nombre:"TOTAL", medida:60, alineacion:"right", tipo:"moneda",  datos:"total"},
			{nombre:"FACTURA", medida:60, alineacion:"center",  datos:"factura"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla11"
	});
	
	window.onload = function(){		
		tabla1.create();
		tabs.iniciar({
			nombre:"tab", largo:710, alto:280, ajustex:11,
			ajustey:12, imagenes:"../../img", titulo:"Reporte de Ventas"
		});
		tabs.agregarTabs('Tipo de venta',1,null);
		tabs.agregarTabs('Condicion Pago',2,null);
		tabs.agregarTabs('Venta Contado (Créd, Cob-Cont, Cob-Créd)',3,null);
		tabs.agregarTabs('Ventas Con Convenio Por Cliente',4,null);
		tabs.agregarTabs('Reporte De Envíos Por Cliente',5,null);		
		tabs.agregarTabs('Ventas Por Guías Prepagadas',6,null);
		tabs.agregarTabs('(Prepagadas) Servicios Pendientes De Facturar',7,null);
		tabs.agregarTabs('Ventas Por Guías A Consignación',8,null);
		tabs.agregarTabs('Guías Y Servicios',9,null);
		
		u.tab_contenedor_id1.disabled = true;
		u.tab_contenedor_id2.disabled = true;
		u.tab_contenedor_id3.disabled = true;
		u.tab_contenedor_id4.disabled = true;
		u.tab_contenedor_id5.disabled = true;
		u.tab_contenedor_id6.disabled = true;
		u.tab_contenedor_id7.disabled = true;
		u.tab_contenedor_id8.disabled = true;
		u.tab_contenedor_id9.disabled = true;

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
	
		consultaTexto("resTabla1","reporteVentas_con.php?accion=1&contador="+u.pag1_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
					  "&s="+Math.random());
	}
	function resTabla1(datos){
		var obj = eval(datos);
		u.pag1_total.value = obj.total;
		u.pag1_contador.value = obj.contador;
		u.pag1_adelante.value = obj.adelante;
		u.pag1_atras.value = obj.atras;
		tabla1.setJsonData(obj.registros);
		
		//totales
		u.t1_convenio.value = "$ "+obj.totales.convenio;
		u.t1_sinconvenio.value = "$ "+obj.totales.sinconvenio;
		u.t1_total.value = "$ "+obj.totales.total;
		if(obj.paginado==1){
			document.getElementById('div_paginado1').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado1').style.visibility = 'hidden';
		}
	}
	function paginacion1(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","reporteVentas_con.php?accion=1&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","reporteVentas_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","reporteVentas_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","reporteVentas_con.php?accion=1&contador="+contador+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				break;
		}
	}	
	
	function obtenerConvenio(){
		if(!tabla2.creada()){
			tabla2.create();
		}
		u.pag2_sucursal.value = tabla1.getSelectedRow().sucursal;
		consultaTexto("resTabla2","reporteVentas_con.php?accion=2&contador="+u.pag2_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
	}
	
	function resTabla2(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag2_total.value = obj.total;
		u.pag2_contador.value = obj.contador;
		u.pag2_adelante.value = obj.adelante;
		u.pag2_atras.value = obj.atras;
		tabla2.setJsonData(obj.registros);
		tabs.seleccionar(1);
		u.tab_contenedor_id1.disabled = false
		//totales
		if(obj.paginado==1){
			document.getElementById('div_paginado2').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado2').style.visibility = 'hidden';
		}
	}
	function paginacion2(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla2","reporteVentas_con.php?accion=2&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("resTabla2","reporteVentas_con.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("resTabla2","reporteVentas_con.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag2_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla2","reporteVentas_con.php?accion=2&contador="+contador+"&sucursal="+u.pag2_sucursal.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&s="+Math.random());
				break;
		}
	}
	
	function t2_obtenerNormales(){
		if(!tabla3.creada()){
			tabla3.create();
		}
		consultaTexto("resTabla3","reporteVentas_con.php?accion=3&contador="+u.pag3_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
	}
	function resTabla3(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag3_total.value = obj.total;
		u.pag3_contador.value = obj.contador;
		u.pag3_adelante.value = obj.adelante;
		u.pag3_atras.value = obj.atras;
		tabla3.setJsonData(obj.registros);
		tabs.seleccionar(2);
		u.tab_contenedor_id2.disabled = false
		//totales
		if(obj.paginado==1){
			document.getElementById('div_paginado3').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado3').style.visibility = 'hidden';
		}
	}
	function paginacion3(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla3","reporteVentas_con.php?accion=3&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag3_adelante.value==1){
					consultaTexto("resTabla3","reporteVentas_con.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag3_atras.value==1){
					consultaTexto("resTabla3","reporteVentas_con.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag3_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla3","reporteVentas_con.php?accion=3&contador="+contador+"&sucursal="+u.pag2_sucursal.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&s="+Math.random());
				break;
		}
	}
	
	function t3_obtenerContado(){
		if(!tabla4.creada()){
			tabla4.create();
		}
		consultaTexto("resTabla4","reporteVentas_con.php?accion=4&contador="+u.pag4_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
	}
	function resTabla4(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag4_total.value = obj.total;
		u.pag4_contador.value = obj.contador;
		u.pag4_adelante.value = obj.adelante;
		u.pag4_atras.value = obj.atras;
		tabla4.setJsonData(obj.registros);
		tabs.seleccionar(3);
		u.tab_contenedor_id3.disabled = false;
		//totales
		u.t3total.value = "$ "+obj.totales.total;
		
		if(obj.paginado==1){
			document.getElementById('div_paginado4').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado4').style.visibility = 'hidden';
		}
	}
	function paginacion4(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla4","reporteVentas_con.php?accion=4&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag4_adelante.value==1){
					consultaTexto("resTabla4","reporteVentas_con.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag4_atras.value==1){
					consultaTexto("resTabla4","reporteVentas_con.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag4_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla4","reporteVentas_con.php?accion=4&contador="+contador+"&sucursal="+u.pag2_sucursal.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&s="+Math.random());
				break;
		}
	}
	
	function t3_obtenerTotal(){
		if(!tabla5.creada()){
			tabla5.create();
		}
		consultaTexto("resTabla5","reporteVentas_con.php?accion=5&contador="+u.pag5_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
	}
	function resTabla5(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag5_total.value = obj.total;
		u.pag5_contador.value = obj.contador;
		u.pag5_adelante.value = obj.adelante;
		u.pag5_atras.value = obj.atras;
		tabla5.setJsonData(obj.registros);
		tabs.seleccionar(4);
		u.tab_contenedor_id4.disabled = false
		//totales
		u.t4total.value = "$ "+obj.totales.total;
		if(obj.paginado==1){
			document.getElementById('div_paginado5').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado5').style.visibility = 'hidden';
		}
	}
	function paginacion5(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla5","reporteVentas_con.php?accion=5&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("resTabla5","reporteVentas_con.php?accion=5&contador="+(parseFloat(u.pag5_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){
					consultaTexto("resTabla5","reporteVentas_con.php?accion=5&contador="+(parseFloat(u.pag5_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla5","reporteVentas_con.php?accion=5&contador="+contador+"&sucursal="+u.pag2_sucursal.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&s="+Math.random());
				break;
		}
	}
	
	function t5_obtenerCliente(){
		if(!tabla6.creada()){
			tabla6.create();
		}
		consultaTexto("resTabla6","reporteVentas_con.php?accion=6&contador="+u.pag6_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  "&idcliente="+tabla5.getSelectedRow().idcliente+
					  "&tipo=idenvia&s="+Math.random());
		if(!tabla7.creada()){
			tabla7.create();
		}		  
		consultaTexto("resTabla7","reporteVentas_con.php?accion=6&contador="+u.pag7_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  "&idcliente="+tabla5.getSelectedRow().idcliente+
					  "&tipo=idrecibe&s="+Math.random());
	}
	function resTabla6(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag6_total.value = obj.total;
		u.pag6_contador.value = obj.contador;
		u.pag6_adelante.value = obj.adelante;
		u.pag6_atras.value = obj.atras;
		tabla6.setJsonData(obj.registros);
		tabs.seleccionar(5);
		u.tab_contenedor_id5.disabled = false;
		tabs.moverManual(-80);
		//totales
		u.t5_guias.value = obj.totales.totalguias;
		u.t5_paquetes.value = obj.totales.totalpaquetes;
		u.t5_kg.value = obj.totales.totalkilogramos;
		u.t5_total.value = "$ "+obj.totales.total;
		totalizar();
		if(obj.paginado==1){
			document.getElementById('div_paginado6').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado6').style.visibility = 'hidden';
		}
	}
	function paginacion6(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla6","reporteVentas_con.php?accion=6&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  		  "&idcliente="+tabla5.getSelectedRow().idcliente+
							  "&tipo=idenvia&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag6_adelante.value==1){
					consultaTexto("resTabla6","reporteVentas_con.php?accion=6&contador="+(parseFloat(u.pag6_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&idcliente="+tabla5.getSelectedRow().idcliente+
							  "&tipo=idenvia&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag6_atras.value==1){
					consultaTexto("resTabla6","reporteVentas_con.php?accion=6&contador="+(parseFloat(u.pag6_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&idcliente="+tabla5.getSelectedRow().idcliente+
							  "&tipo=idenvia&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag6_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla6","reporteVentas_con.php?accion=6&contador="+contador+"&sucursal="+u.pag2_sucursal.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&idcliente="+tabla5.getSelectedRow().idcliente+
							  "&tipo=idenvia&s="+Math.random());
				break;
		}
	}
	function resTabla7(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag7_total.value = obj.total;
		u.pag7_contador.value = obj.contador;
		u.pag7_adelante.value = obj.adelante;
		u.pag7_atras.value = obj.atras;
		tabla7.setJsonData(obj.registros);
		//totales
		u.t6_guias.value = obj.totales.totalguias;
		u.t6_paquetes.value = obj.totales.totalpaquetes;
		u.t6_kg.value = obj.totales.totalkilogramos;
		u.t6_total.value = "$ "+obj.totales.total;
		totalizar();
		if(obj.paginado==1){
			document.getElementById('div_paginado7').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado7').style.visibility = 'hidden';
		}
	}
	function paginacion7(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla7","reporteVentas_con.php?accion=6&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&idcliente="+tabla5.getSelectedRow().idcliente+
							  "&tipo=idrecibe&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag7_adelante.value==1){
					consultaTexto("resTabla7","reporteVentas_con.php?accion=6&contador="+(parseFloat(u.pag7_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&idcliente="+tabla5.getSelectedRow().idcliente+
							  "&tipo=idrecibe&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag7_atras.value==1){
					consultaTexto("resTabla7","reporteVentas_con.php?accion=6&contador="+(parseFloat(u.pag7_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&idcliente="+tabla5.getSelectedRow().idcliente+
							  "&tipo=idrecibe&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag7_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla7","reporteVentas_con.php?accion=6&contador="+contador+"&sucursal="+u.pag2_sucursal.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&idcliente="+tabla5.getSelectedRow().idcliente+
							  "&tipo=idrecibe&s="+Math.random());
				break;
		}
	}
	function totalizar(){
		u.t56_guias.value = parseFloat(u.t5_guias.value)+parseFloat(u.t6_guias.value);
		u.t56_paquetes.value = parseFloat(u.t5_paquetes.value)+parseFloat(u.t6_paquetes.value);
		u.t56_kg.value = parseFloat(u.t5_kg.value)+parseFloat(u.t6_kg.value);
		u.t56_total.value = "$ "+(parseFloat(u.t5_total.value.replace("$ ",""))+parseFloat(u.t6_total.value.replace("$ ","")));
	}
	
	function t2_obtenerPrepagadas(){
		if(!tabla8.creada()){
			tabla8.create();
		}
		consultaTexto("resTabla8","reporteVentas_con.php?accion=7&contador="+u.pag8_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
	}
	function resTabla8(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag8_total.value = obj.total;
		u.pag8_contador.value = obj.contador;
		u.pag8_adelante.value = obj.adelante;
		u.pag8_atras.value = obj.atras;
		tabla8.setJsonData(obj.registros);
		tabs.seleccionar(6);
		u.tab_contenedor_id6.disabled = false;
		//totales
		u.t7_total.value = "$ "+obj.totales.totalimporte;
		if(obj.paginado==1){
			document.getElementById('div_paginado8').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado8').style.visibility = 'hidden';
		}
	}
	function paginacion8(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla8","reporteVentas_con.php?accion=7&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag8_adelante.value==1){
					consultaTexto("resTabla8","reporteVentas_con.php?accion=7&contador="+(parseFloat(u.pag8_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag8_atras.value==1){
					consultaTexto("resTabla8","reporteVentas_con.php?accion=7&contador="+(parseFloat(u.pag8_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag8_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla8","reporteVentas_con.php?accion=7&contador="+contador+"&sucursal="+u.pag2_sucursal.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				break;
		}
	}
	
	function t8_obtenerPendientes(){
		if(!tabla9.creada()){
			tabla9.create();
		}
		u.pag9_folios.value = tabla8.getSelectedRow().folios;
		consultaTexto("resTabla9","reporteVentas_con.php?accion=8&contador="+u.pag9_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&folios="+u.pag9_folios.value+
					  "&s="+Math.random());
	}
	function resTabla9(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag9_total.value = obj.total;
		u.pag9_contador.value = obj.contador;
		u.pag9_adelante.value = obj.adelante;
		u.pag9_atras.value = obj.atras;
		tabla9.setJsonData(obj.registros);
		tabs.seleccionar(7);
		u.tab_contenedor_id7.disabled = false;
		//totales
		u.t9_total.value = "$ "+obj.totales.total;
		if(obj.paginado==1){
			document.getElementById('div_paginado9').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado9').style.visibility = 'hidden';
		}
	}
	function paginacion9(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla9","reporteVentas_con.php?accion=8&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&folios="+u.pag9_folios.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag9_adelante.value==1){
					consultaTexto("resTabla9","reporteVentas_con.php?accion=8&contador="+(parseFloat(u.pag9_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&folios="+u.pag9_folios.value+
							  "&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag9_atras.value==1){
					consultaTexto("resTabla9","reporteVentas_con.php?accion=8&contador="+(parseFloat(u.pag9_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&folios="+u.pag9_folios.value+
							  "&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag9_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla9","reporteVentas_con.php?accion=8&contador="+contador+"&folios="+u.pag9_folios.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				break;
		}
	}
	
	function t2_obtenerConsignacion(){
		if(!tabla10.creada()){
			tabla10.create();
		}
		
		consultaTexto("resTabla10","reporteVentas_con.php?accion=9&contador="+u.pag10_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
	}
	function resTabla10(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag10_total.value = obj.total;
		u.pag10_contador.value = obj.contador;
		u.pag10_adelante.value = obj.adelante;
		u.pag10_atras.value = obj.atras;
		tabla10.setJsonData(obj.registros);
		tabs.seleccionar(8);
		u.tab_contenedor_id8.disabled = false
		//totales
		u.t10_total.value = "$ "+obj.totales.total;
		if(obj.paginado==1){
			document.getElementById('div_paginado10').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado10').style.visibility = 'hidden';
		}
	}
	function paginacion10(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla10","reporteVentas_con.php?accion=9&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag10_adelante.value==1){
					consultaTexto("resTabla10","reporteVentas_con.php?accion=9&contador="+(parseFloat(u.pag10_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag10_atras.value==1){
					consultaTexto("resTabla10","reporteVentas_con.php?accion=9&contador="+(parseFloat(u.pag10_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+
							  "&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag10_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla10","reporteVentas_con.php?accion=9&contador="+contador+"&sucursal="+u.pag2_sucursal.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				break;
		}
	}
	
	function t10_obtenerTotal(){
		if(!tabla11.creada()){
			tabla11.create();
		}
		u.pag11_folios.value = tabla10.getSelectedRow().folios;
		consultaTexto("resTabla11","reporteVentas_con.php?accion=10&contador="+u.pag11_contador.value+"&factura="+tabla10.getSelectedRow().factura+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&folios="+u.pag11_folios.value+
					  "&s="+Math.random());
	}
	function resTabla11(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag11_total.value = obj.total;
		u.pag11_contador.value = obj.contador;
		u.pag11_adelante.value = obj.adelante;
		u.pag11_atras.value = obj.atras;
		tabla11.setJsonData(obj.registros);
		tabs.seleccionar(9);
		u.tab_contenedor_id9.disabled = false
		//totales
		u.t11_total.value = obj.totales.total;
		if(obj.paginado==1){
			document.getElementById('div_paginado11').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado11').style.visibility = 'hidden';
		}
	}
	function paginacion11(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla11","reporteVentas_con.php?accion=10&contador=0"+"&factura="+tabla10.getSelectedRow().factura+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&folios="+u.pag11_folios.value+
							  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag11_adelante.value==1){
					consultaTexto("resTabla11","reporteVentas_con.php?accion=10&contador="+(parseFloat(u.pag11_contador.value)+1)+"&factura="+tabla10.getSelectedRow().factura+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&folios="+u.pag11_folios.value+
							  "&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag11_atras.value==1){
					consultaTexto("resTabla11","reporteVentas_con.php?accion=10&contador="+(parseFloat(u.pag11_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&folios="+u.pag11_folios.value+
							  "&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag11_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla11","reporteVentas_con.php?accion=10&contador="+contador+"&sucursal="+u.pag2_sucursal.value+"&factura="+tabla10.getSelectedRow().factura+
							  "&folios="+u.pag11_folios.value+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				break;
		}
	}
	
	function imprimirReporte(tipo){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/venta/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/venta/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/venta/";
		}
		
		switch(tipo){
			case 1:
				window.open(v_dir+"generarExcelVenta.php?accion=1&titulo=REPORTE DE VENTAS&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&val="+Math.random());
			break;
			
			case 2:
				window.open(v_dir+"generarExcelVenta.php?accion=2&titulo=REPORTE POR TIPO DE VENTA&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&val="+Math.random());
			break;
			
			case 3:
				window.open(v_dir+"generarExcelVenta.php?accion=3&titulo=REPORTE POR CONDICION DE PAGO&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&val="+Math.random());
			break;
			
			case 4:
				window.open(v_dir+"generarExcelVenta.php?accion=4&titulo=DESGLOSE DE VENTA CONTADO (CREDITO-COB-CONTADO, COB-CREDITO)&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&val="+Math.random());
			break;
			
			case 5:
				window.open(v_dir+"generarExcelVenta.php?accion=5&titulo=VENTAS CON CONVENIO POR CLIENTE&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&val="+Math.random());
			break;
			
			case 6:
				window.open(v_dir+"ventaTotalExcel.php?fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&idcliente="+tabla5.getSelectedRow().idcliente+"&val="+Math.random());
				
			break;
			
			case 7:
				window.open(v_dir+"generarExcelVenta.php?accion=6&titulo=VENTAS POR GUIAS A CONSIGNACION&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&val="+Math.random());
			break;
			
			case 8:
				window.open(v_dir+"ventaConsignacionTotalExcel.php?fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value
				+"&factura="+tabla10.getSelectedRow().factura+"&cliente="+tabla10.getSelectedRow().idcliente
				+"&sucursal="+u.pag2_sucursal.value+"&folios="+u.pag11_folios.value+"&val="+Math.random());
			break;
			
			case 9:
				window.open(v_dir+"generarExcelVenta.php?accion=7&titulo=VENTAS POR GUIAS PREPAGADAS&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.pag2_sucursal.value+"&val="+Math.random());
			break;
			
			case 10:
				window.open(v_dir+"ventaPrepagadasPendienteExcel.php?&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value
				+"&folios="+u.pag9_folios.value+"&val="+Math.random());
			break;			
		}
	}
	
	function limpiar(){
		u.fecha.value = "<?=date('d/m/Y') ?>";
		u.fecha2.value = "<?=date('d/m/Y') ?>";
		if(tabla1.creada()){
			tabla1.clear();
		}
		
		u.tab_contenedor_id1.disabled=true;	
		tabs.seleccionar(0);
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
    <td width="8">&nbsp;</td>
    <td width="501">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="71">Fecha Inicial: </td>
            <td width="113"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>" onkeypress="if(event.keyCode==13){document.all.fecha2.focus();}"/></td>
            <td width="82"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
            <td width="71">Fecha Final: </td>
            <td width="113"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y') ?>" onkeypress="if(event.keyCode==13){obtenerDetalle();}"/></td>
            <td width="67"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
          </tr>
      </table>
    </td>
    <td width="99"><img src="../../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onclick="obtenerDetalle()" /></td>
    <td width="92"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')" style="cursor:pointer" /></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle0">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="4" align="right">
    	<table align="right">
        	<tr>
                <td></td>
            	<td>&nbsp;</td>
                <td>Total Convenio</td>
                <td>Total Sin Convenio</td>
                <td>Total</td>
            </tr>
        	<tr>
                <td></td>
            	<td>&nbsp;</td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t1_convenio" readonly="" /></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t1_sinconvenio" readonly="" /></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t1_total" readonly="" /></td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td colspan="4"><div id="div_paginado1" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion1('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion1('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion1('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion1('ultimo')" />
          </div>
          <input type="hidden" name="pag1_total" />
          <input type="hidden" name="pag1_contador" value="0" />
          <input type="hidden" name="pag1_adelante" value="" />
          <input type="hidden" name="pag1_atras" value="" />
          
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(1)"></div></td>
  </tr>
	
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id1">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="501">
    </td>
    <td width="99"></td>
    <td width="92"></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle1">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="4" align="right">
    	
    </td>
  </tr>
  <tr>
    <td colspan="4"><div id="div_paginado2" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" />
          </div>
          <input type="hidden" name="pag2_total" />
          <input type="hidden" name="pag2_contador" value="0" />
          <input type="hidden" name="pag2_adelante" value="" />
          <input type="hidden" name="pag2_atras" value="" />
          <input type="hidden" name="pag2_sucursal" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(2)"></div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id2">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="501">
    </td>
    <td width="99"></td>
    <td width="92"></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle2">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="4" align="right">
    	
    </td>
  </tr>
  <tr>
    <td colspan="4"><div id="div_paginado3" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion3('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion3('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion3('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion3('ultimo')" />
          </div>
          <input type="hidden" name="pag3_total" />
          <input type="hidden" name="pag3_contador" value="0" />
          <input type="hidden" name="pag3_adelante" value="" />
          <input type="hidden" name="pag3_atras" value="" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(3)"></div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id3">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="501">
    </td>
    <td width="99"></td>
    <td width="92"></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle3">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="4" align="right">
    	Total:&nbsp;<input type="text" name="t3total" id="t3total" style="background:#FFFF99" readonly="" />
    </td>
  </tr>
  <tr>
    <td colspan="4"><div id="div_paginado4" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion4('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion4('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion4('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion4('ultimo')" />
          </div>
          <input type="hidden" name="pag4_total" />
          <input type="hidden" name="pag4_contador" value="0" />
          <input type="hidden" name="pag4_adelante" value="" />
          <input type="hidden" name="pag4_atras" value="" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(4)"></div></td>
  </tr>
</table>
</div>

<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id4">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="501">
    </td>
    <td width="99"></td>
    <td width="92"></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle4">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="4" align="right">
    	Total:&nbsp;<input type="text" name="t4total" id="t4total" style="background:#FFFF99" readonly="" />
    </td>
  </tr>
  <tr>
    <td colspan="4"><div id="div_paginado5" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion5('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion5('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion5('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion5('ultimo')" />
          </div>
          <input type="hidden" name="pag5_total" />
          <input type="hidden" name="pag5_contador" value="0" />
          <input type="hidden" name="pag5_adelante" value="" />
          <input type="hidden" name="pag5_atras" value="" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(5)"></div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id5">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;&nbsp;<table width="550" border="0" cellspacing="0" cellpadding="0" style="padding-left:10px">  
  <tr>
    <td colspan="4">
	<div style="height:120px; width:680px; overflow:auto; ">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle5" >
		</table>
	</div>
    <table border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="80px">Totales</td>
                <td width="80px">Guias</td>
                <td width="50px"></td>
                <td width="150px"></td>
                <td width="50px"></td>
                <td width="50px"></td>
                <td width="50px">Paquetes</td>
                <td width="50px">Kgs</td>
                <td width="50px">Total</td>
                <td width="100px"></td>
                <td width="150px"></td>
            </tr>
            <tr>
            	<td width="80px"></td>
                <td width="80px"><input type="text" name="t5_guias" id="t5_guias" style="background:#FFFF99; width:80px;" readonly="" /></td>
                <td width="50px"></td>
                <td width="150px"></td>
                <td width="50px"></td>
                <td width="50px"></td>
                <td width="50px"><input type="text" name="t5_paquetes" id="t5_paquetes" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="50px"><input type="text" name="t5_kg" id="t5_kg" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="50px"><input type="text" name="t5_total" id="t5_total" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="100px"></td>
                <td width="150px"></td>
            </tr>
        </table>
    	</td>
  </tr>
  <tr>
  	<td colspan="4" align="right">
    	
    </td>
  </tr>
  <tr>
    <td colspan="4"><div id="div_paginado6" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion6('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion6('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion6('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion6('ultimo')" />
          </div>
          <input type="hidden" name="pag6_total" />
          <input type="hidden" name="pag6_contador" value="0" />
          <input type="hidden" name="pag6_adelante" value="" />
          <input type="hidden" name="pag6_atras" value="" />
          </td>	
  </tr>
  
</table></td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;<table width="550" border="0" cellspacing="0" cellpadding="0" style="padding-left:10px">  
  <tr>
    <td colspan="4">
	<div style="height:120px; width:680px; overflow:auto; ">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle6">
		</table>
	</div>	
    <table border="0" cellpadding="0" cellspacing="0">
    		<tr>
            	<td width="80px">Totales</td>
                <td width="80px">Guias</td>
                <td width="50px"></td>
                <td width="150px"></td>
                <td width="50px"></td>
                <td width="50px"></td>
                <td width="50px">Paquetes</td>
                <td width="50px">Kgs</td>
                <td width="50px">Total</td>
                <td width="100px"></td>
                <td width="150px"></td>
            </tr>
        	<tr>
            	<td width="80px"></td>
                <td width="80px"><input type="text" name="t6_guias" id="t6_guias" style="background:#FFFF99; width:80px;" readonly="" /></td>
                <td width="50px"></td>
                <td width="150px"></td>
                <td width="50px"></td>
                <td width="50px"></td>
                <td width="50px"><input type="text" name="t6_paquetes" id="t6_paquetes" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="50px"><input type="text" name="t6_kg" id="t6_kg" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="50px"><input type="text" name="t6_total" id="t6_total" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="100px"></td>
                <td width="150px"></td>
            </tr>
            <tr>
            	<td width="80px">Generales</td>
                <td width="80px"><input type="text" name="t56_guias" id="t56_guias" style="background:#FFFF99; width:80px;" readonly="" /></td>
                <td width="50px"></td>
                <td width="150px"></td>
                <td width="50px"></td>
                <td width="50px"></td>
                <td width="50px"><input type="text" name="t56_paquetes" id="t56_paquetes" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="50px"><input type="text" name="t56_kg" id="t56_kg" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="50px"><input type="text" name="t56_total" id="t56_total" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="100px"></td>
                <td width="150px"></td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
  	<td colspan="4" align="right">
    	
    </td>
  </tr>
  <tr>
    <td colspan="4"><div id="div_paginado7" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion7('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion7('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion7('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion7('ultimo')" />
          </div>
          <input type="hidden" name="pag7_total" />
          <input type="hidden" name="pag7_contador" value="0" />
          <input type="hidden" name="pag7_adelante" value="" />
          <input type="hidden" name="pag7_atras" value="" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(6)"></div></td>
  </tr>
</table></td>
  </tr>
</table>
</div>

<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id6">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="501">
    </td>
    <td width="99"></td>
    <td width="92"></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto; ">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle7">
		</table>
	</div>	
        <table width="700px">
        	<tr>
                <td align="right">
                Total
                </td>
            </tr>
            <tr>
                <td align="right">
                <input type="text" name="t7_total" id="t7_total" />
                </td>
            </tr>
        </table>
    </td>
  </tr> 
  <tr>
    <td colspan="4"><div id="div_paginado8" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion8('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion8('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion8('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion8('ultimo')" />
          </div>
          <input type="hidden" name="pag8_total" />
          <input type="hidden" name="pag8_contador" value="0" />
          <input type="hidden" name="pag8_adelante" value="" />
          <input type="hidden" name="pag8_atras" value="" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(9)"></div></td>
  </tr>
</table>
</div>

<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id7">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="501">
    </td>
    <td width="99"></td>
    <td width="92"></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto; ">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle8">
		</table>
	</div>	
    <table width="700px">
    	<tr>
        	<td align="right">Total</td>
        </tr>
        <tr>
        	<td align="right"><input type="text" name="t9_total" id="t9_total" /></td>
        </tr>
    </table>
    </td>
  </tr> 
  <tr>
    <td colspan="4"><div id="div_paginado9" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion9('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion9('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion9('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion9('ultimo')" />
          </div>
          <input type="hidden" name="pag9_total" />
          <input type="hidden" name="pag9_contador" value="0" />
          <input type="hidden" name="pag9_adelante" value="" />
          <input type="hidden" name="pag9_atras" value="" />
          <input type="hidden" name="pag9_folios" value="" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(10)"></div></td>
  </tr>
</table>
</div>

<div style="position:absolute; left: 40px; top: 90px; width: 621px; visibility:visible;" id="tab_tab_id8">
<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="501">
    </td>
    <td width="99"></td>
    <td width="92"></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto; ">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle9">
		</table>
	</div>	
    <table width="700px">
    	<tr>
        	<td align="right">Total</td>
        </tr>
        <tr>
        	<td align="right"><input type="text" name="t10_total" id="t10_total" /></td>
        </tr>
    </table>
    </td>
  </tr> 
  <tr>
    <td colspan="4"><div id="div_paginado10" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion10('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion10('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion10('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion10('ultimo')" />
          </div>
          <input type="hidden" name="pag10_total" />
          <input type="hidden" name="pag10_contador" value="0" />
          <input type="hidden" name="pag10_adelante" value="" />
          <input type="hidden" name="pag10_atras" value="" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(7)"></div></td>
  </tr>
</table>
</div>

 <div style="position:absolute; left: 40px; top: 90px; width: 621px; visibility:visible;" id="tab_tab_id9">
	<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="8">&nbsp;</td>
    <td width="501">
    </td>
    <td width="99"></td>
    <td width="92"></td>
  </tr>
  <tr>
    <td colspan="4">
	<div style="height:280px; width:700px; overflow:auto;" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle10">
		</table>
	</div>		
    <table width="700px">
    	<tr>
        	<td align="right">Total</td>
        </tr>
        <tr>
        	<td align="right"><input type="text" name="t11_total" id="t11_total" /></td>
        </tr>
    </table>
    </td>
  </tr> 
  <tr>
    <td colspan="4"><div id="div_paginado11" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion11('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion11('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion11('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion11('ultimo')" />
          </div>
          <input type="hidden" name="pag11_total" />
          <input type="hidden" name="pag11_contador" value="0" />
          <input type="hidden" name="pag11_adelante" value="" />
          <input type="hidden" name="pag11_atras" value="" />
          <input type="hidden" name="pag11_folios" value="" />
          </td>	
  </tr>
  <tr>
    <td colspan="4" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(8)"></div></td>
  </tr>
</table>
</div> 
<table width="600" height="66" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla Estilo4">Reporte Principal de Ventas </td>
  </tr>
  <tr>
    <td height="500px" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
