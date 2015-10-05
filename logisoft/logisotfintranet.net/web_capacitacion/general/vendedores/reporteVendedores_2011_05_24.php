<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
	var tabla1 = new ClaseTabla();
	var tabla2 = new ClaseTabla();
	var tabla3 = new ClaseTabla();
	var tabla4 = new ClaseTabla();
	var tabla5 = new ClaseTabla();
	var mens = new ClaseMensajes();
	var pag1_cantidadporpagina = 30;	
	var v_mes1 = "";
	var v_mes2 = "";
	var v_mes3 = "";
		
	mens.iniciar('../../javascript');
	
	jQuery(function($){	   
	   $('#fechainicio').mask("99/99/9999");
	   $('#fechafin').mask("99/99/9999");
	});
	
	tabla1.setAttributes({
		nombre:"tabla1",
		campos:[
			{nombre:"SUCURSAL", medida:100, alineacion:"left", datos:"prefijoorigen"},
			{nombre:"IDVENDEDOR", tipo:"oculto",medida:4, alineacion:"center", datos:"idvendedor"},
			{nombre:"VENDEDOR", medida:250, onDblClick:"obtenerMeses",alineacion:"left",  datos:"vendedor"},
			{nombre:"VENTAS", medida:150, onDblClick:"verVentas" ,alineacion:"right",tipo:"moneda",  datos:"flete"},			
			{nombre:"VENTAS COBRADAS", onDblClick:"verVentasCobradas",medida:150, alineacion:"right",tipo:"moneda", datos:"vtascobradas"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla3.setAttributes({
		nombre:"tabla3",
		campos:[
			{nombre:"FECHA", medida:70, alineacion:"left", datos:"fecha"},
			{nombre:"GUIA", medida:80, alineacion:"left", datos:"guia"},
			{nombre:"DESTINO", medida:30, alineacion:"center", datos:"prefijodestino"},
			{nombre:"# CLIENTE", medida:70, alineacion:"right", datos:"idcliente"},
			{nombre:"CLIENTE", medida:210, alineacion:"left", datos:"cliente"},
			{nombre:"VALOR FLETE NETO", medida:100, alineacion:"right",tipo:"moneda", datos:"flete"},
			{nombre:"STATUS", medida:100, alineacion:"left", datos:"estado"}
			
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla3"
	});
	
	tabla4.setAttributes({
		nombre:"tabla4",
		campos:[
			{nombre:"FECHA", medida:70, alineacion:"left", datos:"fecha"},
			{nombre:"GUIA", medida:75, alineacion:"left", datos:"guia"},
			{nombre:"# CLIENTE", medida:70, alineacion:"right", datos:"idcliente"},
			{nombre:"CLIENTE", medida:240, alineacion:"left", datos:"cliente"},			
			{nombre:"VALOR FLETE NETO", medida:100, alineacion:"right",tipo:"moneda", datos:"flete"},
			{nombre:"COMISION", medida:70, alineacion:"right",tipo:"moneda", datos:"comision"},
			{nombre:"% C", medida:40, alineacion:"center", datos:"porcentaje"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla4"
	});	
	
	tabla5.setAttributes({
		nombre:"tabla5",
		campos:[
			{nombre:"SUCURSAL", medida:70, alineacion:"left", datos:"prefijosucursal"},
			{nombre:"VENDEDOR", medida:350, alineacion:"left", datos:"vendedor"},
			{nombre:"VTAS. COBRADAS", medida:120, alineacion:"right", tipo:"moneda", datos:"flete"},
			{nombre:"COMISION", medida:120, alineacion:"right",tipo:"moneda", datos:"comision"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla5"
	});	
	
	window.onload = function(){
		tabla1.create();
		tabs.iniciar({
			nombre:"tab", largo:710, alto:280, ajustex:11,ajustey:12, imagenes:"../../img", titulo:"Vendedores"
		});
		u.btnMovIzq.style.visibility = "hidden";
		u.btnMovDer.style.visibility = "hidden";
		tabs.agregarTabs('Detallado Por Vendedor',1,null);		
		tabs.agregarTabs('Ventas Por Vendedor',2,null);		
		tabs.agregarTabs('Comisión Por Vendedor',3,null);		
		tabs.agregarTabs('Generados Por Convenio',4,null);		
		u.tab_contenedor_id1.disabled = true;
		u.tab_contenedor_id2.disabled = true;
		u.tab_contenedor_id3.disabled = true;
		u.tab_contenedor_id4.disabled = true;
		tabs.seleccionar(0);
		u.fechainicio.focus();
	}
	
	function obtenerDetalle(){
		if(u.fechainicio.value == "" || u.fechainicio.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha inicial","¡Atención!","fechainicio");
			return false;
		}
		if(u.fechafin.value == "" || u.fechafin.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha final","¡Atención!","fechafin");
			return false;
		}
		
		var f1 = u.fechainicio.value.split("/");
		var f2 = u.fechafin.value.split("/");
		
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
			mens.show("A","La fecha final debe ser mayor a la fecha inicial","¡Atención!","fechafin");
			return false;
		}
		consultaTexto("mostrarPrincipal","reporteVendedores_con.php?accion=1&fechainicio="+u.fechainicio.value
		+"&fechafin="+u.fechafin.value+"&contador="+u.pag1_contador.value+"&s="+Math.random());
	}

	function mostrarPrincipal(datos){
		//mens.show("I",datos);
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		u.ptotalvendedores.value= obj.totales.tvendedor;
		u.ptotalventas.value	= "$ "+obj.totales.flete;
		u.ptotalcobradas.value	= "$ "+obj.totales.vtascobradas;
		if(obj.registros.length==0){
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla1.clear();
		}else{			
			tabla1.setJsonData(obj.registros);
		}
		if(obj.paginado==1){
			document.getElementById('paginado1').style.visibility = 'visible';
		}else{
			document.getElementById('paginado1').style.visibility = 'hidden';
		}
	}
	
	function paginacion1(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarPrincipal","reporteVendedores_con.php?accion=1&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("mostrarPrincipal","reporteVendedores_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("mostrarPrincipal","reporteVendedores_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarPrincipal","reporteVendedores_con.php?accion=1&contador="+contador
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerMeses(){
		if(tabla1.getRecordCount()>0){
			consultaTexto("verVentasVendedor","reporteVendedores_con.php?accion=6&fecha="+u.fechafin.value+"&val="+Math.random());
		}
	}
	
	function verVentasVendedor(datos){
		var obj = eval(convertirValoresJson(datos));
		v_mes1 = obj[0].mes1;
		v_mes2 = obj[0].mes2;
		v_mes3 = obj[0].mes3;
		tabla2.setAttributes({
			nombre:"tabla2",
			campos:[
				{nombre:"FECHA CONVENIO", medida:80, alineacion:"left", datos:"fechaconvenio"},
				{nombre:"# CLIENTE", medida:50, alineacion:"center",  datos:"idcliente"},
				{nombre:"NOMBRE DEL CLIENTE", medida:200, alineacion:"left", datos:"cliente"},
				{nombre:"CONVENIO", medida:40, alineacion:"center",  datos:"convenio"},
				{nombre:obj[0].mes1, medida:95, tipo:"moneda", alineacion:"right", datos:"tot3"},
				{nombre:obj[0].mes2, medida:95, tipo:"moneda", alineacion:"right", datos:"tot2"},
				{nombre:obj[0].mes3, medida:95, tipo:"moneda", alineacion:"right", datos:"tot1"}
			],
			filasInicial:30,
			alto:250,
			seleccion:true,
			ordenable:false,		
			nombrevar:"tabla2"
		});
		
		if(!tabla2.creada()){
			tabla2.create();
		}
		var obj = tabla1.getSelectedRow();
		u.t2vendedor.value = obj.vendedor;
		u.h_t2vendedor.value = obj.idvendedor;
		var row = u.fechafin.value.split("/");
		consultaTexto("mostrarVentasVendedor","reporteVendedores_con.php?accion=2&fechainicio="+u.fechainicio.value
		+"&fechafin="+u.fechafin.value
		+"&vendedor="+obj.idvendedor
		+"&ano="+row[2]
		+"&mes="+row[1]);
	}
	
	function mostrarVentasVendedor(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag2_total.value 		= obj.total;
		u.pag2_contador.value 	= obj.contador;
		u.pag2_adelante.value 	= obj.adelante;
		u.pag2_atras.value 		= obj.atras;
		u.totalmes1.value		= "$ "+obj.totales.tot3;
		u.totalmes2.value		= "$ "+obj.totales.tot2;
		u.totalmes3.value		= "$ "+obj.totales.tot1;
		if(obj.registros.length==0){
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla2.clear();
		}else{			
			tabla2.setJsonData(obj.registros);
			u.tab_contenedor_id1.disabled = false;		
			tabs.seleccionar(1);
		}
		if(obj.paginado==1){
			document.getElementById('paginado2').style.visibility = 'visible';
		}else{
			document.getElementById('paginado2').style.visibility = 'hidden';
		}
	}
	
	function paginacion2(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarVentasVendedor","reporteVendedores_con.php?accion=2&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&vendedor="+tabla1.getSelectedRow().idvendedor+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("mostrarVentasVendedor","reporteVendedores_con.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)+1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&vendedor="+tabla1.getSelectedRow().idvendedor+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("mostrarVentasVendedor","reporteVendedores_con.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)-1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&vendedor="+tabla1.getSelectedRow().idvendedor+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag2_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarVentasVendedor","reporteVendedores_con.php?accion=2&contador="+contador+"&vendedor="+tabla1.getSelectedRow().idvendedor
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				break;
		}
	}
	
	function verVentas(){	
		if(tabla1.getRecordCount()>0){
			if(!tabla3.creada()){
				tabla3.create();
			}
			var obj = tabla1.getSelectedRow();
			u.t3vendedor.value = obj.vendedor;
			u.h_t3vendedor.value = obj.idvendedor;
			var row = u.fechafin.value.split("/");
			consultaTexto("mostrarVentas","reporteVendedores_con.php?accion=3&fechainicio="+u.fechainicio.value
			+"&fechafin="+u.fechafin.value+"&vendedor="+obj.idvendedor+"&ano="+row[2]+"&mes="+row[1]
			+"&sucursal="+obj.prefijoorigen+"&val="+Math.random());
		}
	}
	
	function mostrarVentas(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag3_total.value 		= obj.total;
		u.pag3_contador.value 	= obj.contador;
		u.pag3_adelante.value 	= obj.adelante;
		u.pag3_atras.value 		= obj.atras;
		u.tflete2.value			= "$ "+obj.totales.flete;
		
		if(obj.registros.length==0){
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla3.clear();
		}else{			
			tabla3.setJsonData(obj.registros);
			u.tab_contenedor_id2.disabled = false;		
			tabs.seleccionar(2);
		}
		if(obj.paginado==1){
			document.getElementById('paginado3').style.visibility = 'visible';
		}else{
			document.getElementById('paginado3').style.visibility = 'hidden';
		}
	}
	
	function paginacion3(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarVentas","reporteVendedores_con.php?accion=3&contador=0&fechainicio="+u.fechainicio.value
				+"&sucursal="+tabla1.getSelectedRow().prefijoorigen+"&vendedor="+tabla1.getSelectedRow().idvendedor
				+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag3_adelante.value==1){
					consultaTexto("mostrarVentas","reporteVendedores_con.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)+1)
					+"&sucursal="+tabla1.getSelectedRow().prefijoorigen+"&vendedor="+tabla1.getSelectedRow().idvendedor
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag3_atras.value==1){
					consultaTexto("mostrarVentas","reporteVendedores_con.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)-1)
					+"&sucursal="+tabla1.getSelectedRow().prefijoorigen+"&vendedor="+tabla1.getSelectedRow().idvendedor
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag3_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarVentas","reporteVendedores_con.php?accion=3&contador="+contador
				+"&sucursal="+tabla1.getSelectedRow().prefijoorigen+"&vendedor="+tabla1.getSelectedRow().idvendedor
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				break;
		}
	}
	
	function verVentasCobradas(){
		if(tabla1.getRecordCount()>0){
			if(!tabla4.creada()){
				tabla4.create();
			}
			var obj = tabla1.getSelectedRow();
			u.t4vendedor.value = obj.vendedor;
			u.h_t4vendedor.value = obj.idvendedor;
			var row = u.fechafin.value.split("/");
			consultaTexto("mostrarVentasCobradas","reporteVendedores_con.php?accion=4&fechainicio="+u.fechainicio.value
			+"&fechafin="+u.fechafin.value
			+"&vendedor="+obj.idvendedor
			+"&ano="+row[2]
			+"&mes="+row[1]
			+"&val="+Math.random());
		}
	}
	
	function mostrarVentasCobradas(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag4_total.value 		= obj.total;
		u.pag4_contador.value 	= obj.contador;
		u.pag4_adelante.value 	= obj.adelante;
		u.pag4_atras.value 		= obj.atras;
		u.totalflete.value		= "$ "+obj.totales.flete;
		u.totalcomision.value	= "$ "+obj.totales.comision;
		if(obj.registros.length==0){
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla4.clear();
		}else{
			tabla4.setJsonData(obj.registros);
			u.tab_contenedor_id3.disabled = false;		
			tabs.seleccionar(3);
		}
		if(obj.paginado==1){
			document.getElementById('paginado4').style.visibility = 'visible';
		}else{
			document.getElementById('paginado4').style.visibility = 'hidden';
		}
	}
	
	function paginacion4(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarVentasCobradas","reporteVendedores_con.php?accion=4&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&vendedor="+u.h_t4vendedor.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag4_adelante.value==1){
					consultaTexto("mostrarVentasCobradas","reporteVendedores_con.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)+1)
					+"&fechainicio="+u.fechainicio.value+"&vendedor="+u.h_t4vendedor.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag4_atras.value==1){
					consultaTexto("mostrarVentasCobradas","reporteVendedores_con.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)-1)
					+"&fechainicio="+u.fechainicio.value+"&vendedor="+u.h_t4vendedor.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag4_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarVentasCobradas","reporteVendedores_con.php?accion=4&contador="+contador
				+"&fechainicio="+u.fechainicio.value+"&vendedor="+u.h_t4vendedor.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				break;
		}
	}		
	
	
	function verTotalVentasCobradas(){
		if(tabla1.getRecordCount()>0){
			if(!tabla5.creada()){
				tabla5.create();
			}		
			consultaTexto("mostrarTotalVentasCobradas","reporteVendedores_con.php?accion=5&fechainicio="+u.fechainicio.value
			+"&fechafin="+u.fechafin.value+"&val="+Math.random());
		}
	}
	
	function mostrarTotalVentasCobradas(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag5_total.value 		= obj.total;
		u.pag5_contador.value 	= obj.contador;
		u.pag5_adelante.value 	= obj.adelante;
		u.pag5_atras.value 		= obj.atras;
		u.tventascobradas.value	= "$ "+obj.totales.flete;
		u.tcomision.value		= "$ "+obj.totales.comision;
		if(obj.registros.length==0){
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla5.clear();
		}else{			
			tabla5.setJsonData(obj.registros);
			u.tab_contenedor_id4.disabled = false;		
			tabs.seleccionar(4);
		}
		if(obj.paginado==1){
			document.getElementById('paginado5').style.visibility = 'visible';
		}else{
			document.getElementById('paginado5').style.visibility = 'hidden';
		}
	}
	
	function paginacion5(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarTotalVentasCobradas","reporteVendedores_con.php?accion=5&contador=0&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("mostrarTotalVentasCobradas","reporteVendedores_con.php?accion=5&contador="+(parseFloat(u.pag5_contador.value)+1)+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){
					consultaTexto("mostrarTotalVentasCobradas","reporteVendedores_con.php?accion=5&contador="+(parseFloat(u.pag5_contador.value)-1)+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarTotalVentasCobradas","reporteVendedores_con.php?accion=5&contador="+contador
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&s="+Math.random());
				break;
		}
	}
	
	function imprimirReporte(tipo){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/vendedores/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/vendedores/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/vendedores/";
		}
		
		switch(tipo){
			case 1:
				window.open(v_dir+"generarExcelVendedor.php?accion=1&titulo=VENDEDORES&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&val="+Math.random());
			break;
			
			case 2:
				window.open(v_dir+"generarExcelMeses.php?vendedor="+u.h_t2vendedor.value+"&titulo=DETALLADO POR VENDEDOR&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&mes1="+v_mes1+"&mes2="+v_mes2+"&mes3="+v_mes3+"&val="+Math.random());
			break;
			
			case 3:
				window.open(v_dir+"generarExcelPorVendedor.php?accion=2&titulo=COBRADAS POR VENDEDOR&fechainicio="+u.fechainicio.value
				+"&sucursal="+tabla1.getSelectedRow().prefijoorigen
				+"&fechafin="+u.fechafin.value+"&vendedor="+u.h_t3vendedor.value+"&val="+Math.random());
			break;
			
			case 4:
				window.open(v_dir+"generarExcelPorVendedor.php?accion=1&titulo=COMISION POR VENDEDOR&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&vendedor="+u.h_t4vendedor.value+"&val="+Math.random());
			break;
			
			case 5:
				window.open(v_dir+"generarExcelVendedor.php?accion=2&titulo=GENERADOS POR CONVENIO&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&val="+Math.random());
			break;
		}
	}
	
	function limpiar(){
		v_mes1 = "";
		v_mes2 = "";
		v_mes3 = "";
		
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
	}
	
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id0">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="503" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="84">F. Inicial: </td>
            <td width="100"><input name="fechainicio" type="text" class="Tablas" id="fechainicio" style="width:80px" value="<?=date('d/m/Y')?>" /></td>
            <td width="74"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fechainicio,'dd/mm/yyyy',this)"></div></td>
            <td width="68">F. Final: </td>
            <td width="100"><input name="fechafin" type="text" class="Tablas" id="fechafin" style="width:80px" value="<?=date('d/m/Y') ?>" /></td>
            <td width="78"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)"></div></td>
          </tr>
      </table></td>
      <td width="91"><span class="Estilo6 Tablas"><img src="../../img/Boton_Generar.gif" width="74" height="20" style="cursor:pointer" onclick="obtenerDetalle();" /></span></td>
      <td width="105"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
    </tr>
    <tr>
      <td colspan="3">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla1">
		</table>
	</div>       </td>
    </tr> 
	<tr>
      <td colspan="3" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(1)"></div></td>
    </tr>
    <tr>
      <td colspan="3" align="right"><table width="100%" height="16" border="0" align="left" cellpadding="0" cellspacing="0">
        
		<tr>
          <td width="3">&nbsp;</td>
          <td width="66"> Total Gral:</td>
          <td width="103">
            <input name="ptotalvendedores" class="Tablas" type="text" id="ptotalvendedores" style="width:100px; text-align:right; background-color:#FFFF99;" /></td>
          <td width="126" align="center">
                <input name="ptotalventas" type="text" class="Tablas" id="ptotalventas" style="text-align:right; width:100px; background:#FFFF99" readonly="" align="right" />          </td>
          <td width="122" align="center">		  		
                <input name="ptotalcobradas" type="text" class="Tablas" id="ptotalcobradas" style="text-align:right;width:100px;background:#FFFF99;"  readonly="" align="right" />
				<span class="Tablas" style="cursor:pointer; text-decoration:underline" onclick="verTotalVentasCobradas()">Mostrar Total Cobradas</span></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><div id="paginado1" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion1('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion1('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion1('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion1('ultimo')" />
		  <input type="hidden" name="pag1_total" />
          <input type="hidden" name="pag1_contador" value="0" />
          <input type="hidden" name="pag1_adelante" value="" />
          <input type="hidden" name="pag1_atras" value="" />
          </div></td>
		  <td align="right">
		  		
		  </td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id1">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
      <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>Vendedor:</td>
          <td><input name="t2vendedor" type="text" class="Tablas" id="t2vendedor" style="width:300px;background:#FFFF99" readonly=""/><input type="hidden" name="h_t2vendedor" id="h_t2vendedor" /></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla2">
		</table>
	</div>       </td>
    </tr>
	<tr>
      <td colspan="2" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(2)"></div></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
	  
	  <table width="100%" height="16" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="3">&nbsp;</td>
          <td width="195"><div align="right">Total General:</div></td>
          <td width="106" align="center"><input name="totalmes1" type="text" class="Tablas" id="totalmes1" style="text-align:center;width:100px;background:#FFFF99" readonly=""/></td>
          <td width="108" align="center"><input name="totalmes2" type="text" class="Tablas" id="totalmes2" style="text-align:center;width:100px;background:#FFFF99" readonly=""/></td>
          <td width="89" align="center"><input name="totalmes3" type="text" class="Tablas" id="totalmes3" style="text-align:right;width:100px;background:#FFFF99" readonly="" /></td>
        </tr>
      </table>
	 </td>
      </tr>
    <tr>
      <td width="606" align="center"><div id="paginado2" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" />
		  <input type="hidden" name="pag2_total" />
          <input type="hidden" name="pag2_contador" value="0" />
          <input type="hidden" name="pag2_adelante" value="" />
          <input type="hidden" name="pag2_atras" value="" />
          </div>  		  </td>
		  
	  <td width="94" align="right">&nbsp;</td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id2">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
      <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>Vendedor:</td>
          <td><input name="t3vendedor" type="text" class="Tablas" id="t3vendedor" style="width:300px;background:#FFFF99" readonly=""/><input type="hidden" name="h_t3vendedor" id="h_t3vendedor" /></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla3">
		</table>
	</div>       </td>
    </tr>
	<tr>
      <td colspan="2" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(3)"></div></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><table width="100%" height="16" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="3">&nbsp;</td>
          <td width="195">&nbsp;</td>
          <td width="106" align="center"><div align="right">Total General:</div></td>
          <td width="108" align="center"><input name="tflete2" type="text" class="Tablas" id="tflete2" style="text-align:center;width:100px;background:#FFFF99" readonly=""/></td>
          <td width="89" align="center">&nbsp;</td>
        </tr>
      </table></td>
      </tr>
    <tr>
      <td width="606" align="center"><div id="paginado3" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion3('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion3('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion3('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion3('ultimo')" />
		  <input type="hidden" name="pag3_total" />
          <input type="hidden" name="pag3_contador" value="0" />
          <input type="hidden" name="pag3_adelante" value="" />
          <input type="hidden" name="pag3_atras" value="" />
          </div>  		  </td>
		  
	  <td width="94" align="right">&nbsp;</td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id3">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
      <td colspan="2" align="right"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>Vendedor:</td>
          <td><input name="t4vendedor" type="text" class="Tablas" id="t4vendedor" style="width:300px;background:#FFFF99" value="<?=$vendedor ?>" readonly=""/><input type="hidden" name="h_t4vendedor" id="h_t4vendedor" /></td>
          <td>&nbsp;</td>
        </tr>        
      </table></td>
    </tr> 
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla4">
		</table>
	</div>       </td>
    </tr> 
<tr>
      <td colspan="2" align="right">
      	<div class="ebtn_imprimir" onclick="imprimirReporte(4)"></div></td>
   
    </tr>   
    <tr>
      <td colspan="2" align="center">
	   <table width="100%" height="16" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="3">&nbsp;</td>
          <td width="195">&nbsp;</td>
          <td width="106" align="center"><div align="right">Total General:</div></td>
          <td width="108" align="center"><input name="totalflete" type="text" class="Tablas" id="totalflete" style="text-align:center;width:100px;background:#FFFF99" readonly=""/></td>
          <td width="89" align="center"><input name="totalcomision" type="text" class="Tablas" id="totalcomision" style="text-align:right;width:100px;background:#FFFF99" readonly="" /></td>
        </tr>
      </table>
	  </td>
      </tr>
    <tr>
      <td width="606" align="center"><div id="paginado4" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion4('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion4('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion4('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion4('ultimo')" />
		  <input type="hidden" name="pag4_total" />
          <input type="hidden" name="pag4_contador" value="0" />
          <input type="hidden" name="pag4_adelante" value="" />
          <input type="hidden" name="pag4_atras" value="" />
          </div>  		  </td>
		  
	  <td width="94" align="right">&nbsp;</td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id4">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">	
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla5">
		</table>
	</div>       </td>
    </tr>   
	<tr>
      <td colspan="2" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(5)"></div></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
	 		<table width="100%" height="16" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="3">&nbsp;</td>
          <td width="195">&nbsp;</td>
          <td width="106" align="center"><div align="right">Total General:</div></td>
          <td width="108" align="center"><input name="tventascobradas" type="text" class="Tablas" id="tventascobradas" style="text-align:center;width:100px;background:#FFFF99" readonly=""/></td>
          <td width="89" align="center"><input name="tcomision" type="text" class="Tablas" id="tcomision" style="text-align:right;width:100px;background:#FFFF99" readonly="" /></td>
        </tr>
      </table>
	  </td>
      </tr>
    <tr>
      <td width="606" align="center"><div id="paginado5" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion5('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion5('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion5('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion5('ultimo')" />
		  <input type="hidden" name="pag5_total" />
          <input type="hidden" name="pag5_contador" value="0" />
          <input type="hidden" name="pag5_adelante" value="" />
          <input type="hidden" name="pag5_atras" value="" />
          </div>  		  </td>
		  
	  <td width="94" align="right">&nbsp;</td>
    </tr>
  </table>
</div>
<table width="600" height="66" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla">Reporte Principal de Vendedores</td>
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