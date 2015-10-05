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
<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../../javascript/moautocomplete.js"></script>
<script>
	var u = document.all;
	var tabs = new ClaseTabs();
	var mens = new ClaseMensajes();
	var tabla1 	= new ClaseTabla();
	var tabla2 	= new ClaseTabla();
	var tabla3 	= new ClaseTabla();
	var tabla4 	= new ClaseTabla();	
	var tabla5 	= new ClaseTabla();
	mens.iniciar('../../javascript');
	
	//para paginado
	var pag1_cantidadporpagina = 30;
	
	jQuery(function($){	   
	   $('#fecha1').mask("99/99/9999");
	   $('#fecha2').mask("99/99/9999");
	});
	
	tabla1.setAttributes({
		nombre:"detalle0",
		campos:[						
			{nombre:"SUCURSAL", medida:30, alineacion:"left", datos:"nombresucursal"},
			{nombre:"EFECTIVO", medida:80, tipo:"moneda",alineacion:"right",  datos:"efectivo"},
			{nombre:"CHEQUES BANCOMER", medida:90, tipo:"moneda", alineacion:"right",  datos:"cheques"},
			{nombre:"CHEQUES OTROS", medida:90, tipo:"moneda",alineacion:"right",  datos:"otros"},
			{nombre:"TRANSF. ELECTR", medida:90, tipo:"moneda",alineacion:"right",  datos:"transferencia"},
			{nombre:"PAGO TARJETA", medida:90, tipo:"moneda",alineacion:"right",  datos:"tarjeta"},
			{nombre:"NOTAS CREDITO", medida:90, tipo:"moneda",alineacion:"right",  datos:"nc"},			
			{nombre:"TOTAL REGISTRADO", medida:90, tipo:"moneda",alineacion:"right", datos:"total"},
			{nombre:"TOTAL SISTEMA", medida:90, tipo:"moneda",alineacion:"right", datos:"totalsistema"}
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
			{nombre:"IDSUCURSAL", medida:40, tipo:"oculto", alineacion:"left", datos:"sucursal"},
			{nombre:"SUCURSAL", medida:50, alineacion:"left", datos:"nombresucursal"},
			{nombre:"CONTADO", medida:100, tipo:"moneda", onDblClick:"agregacontado",alineacion:"right",  datos:"contado"},
			{nombre:"COBRANZA", medida:100, tipo:"moneda", onDblClick:"agregacobranza",alineacion:"right",  datos:"cobranza"},
			{nombre:"ENTREGADAS", medida:100, tipo:"moneda",onDblClick:"agregaentregadas",alineacion:"right",  datos:"entregadas"},			
			{nombre:"TOTAL", medida:100, tipo:"moneda", alineacion:"right", datos:"total"},
			{nombre:"DEPOSITADO", medida:100, tipo:"moneda",alineacion:"right",  datos:"depositado"},
			{nombre:"SALDO", medida:150, tipo:"moneda" ,alineacion:"right",  datos:"saldo"}
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
			{nombre:"SUCURSAL", medida:60, alineacion:"left", datos:"nombresucursal"},
			{nombre:"FECHA", medida:80, alineacion:"left",  datos:"fecha"},
			{nombre:"GUIA", medida:100, alineacion:"left",  datos:"guia"},
			{nombre:"CLIENTE", medida:300, alineacion:"left",  datos:"cliente"},			
			{nombre:"IMPORTE", medida:150, tipo:"moneda",alineacion:"right", datos:"importe"},
			{nombre:"CAJA", medida:50, alineacion:"center",  datos:"caja"}
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
			{nombre:"SUCURSAL", medida:60, alineacion:"left", datos:"nombresucursal"},
			{nombre:"FECHA", medida:80, alineacion:"center",  datos:"fecha"},
			{nombre:"GUIA", medida:100, alineacion:"left",  datos:"guia"},
			{nombre:"CLIENTE", medida:300, alineacion:"left",  datos:"cliente"},			
			{nombre:"IMPORTE", medida:150, tipo:"moneda",alineacion:"right", datos:"importe"},
			{nombre:"CAJA", medida:50, alineacion:"center",  datos:"caja"}
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
			{nombre:"SUCURSAL", medida:60, alineacion:"left", datos:"nombresucursal"},
			{nombre:"FECHA", medida:80, alineacion:"left",  datos:"fecha"},
			{nombre:"GUIA", medida:100, alineacion:"left",  datos:"guia"},
			{nombre:"CLIENTE", medida:300, alineacion:"left",  datos:"cliente"},			
			{nombre:"IMPORTE", medida:150, tipo:"moneda" ,alineacion:"right", datos:"importe"},
			{nombre:"CAJA", medida:50, alineacion:"center",  datos:"caja"}
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
			nombre:"tab", largo:810, alto:350, ajustex:11,ajustey:12, imagenes:"../../img"
		});
		tabs.agregarTabs('CONCILIACION DE INGRESOS',1,null);		
		u.tab_contenedor_id1.disabled=true;
		tabs.agregarTabs('INGRESOS POR GUIAS DE CONTADO',2,null);
		u.tab_contenedor_id2.disabled=true;
		tabs.agregarTabs('INGRESOS POR COBRANZA',3,null);
		u.tab_contenedor_id3.disabled=true;
		tabs.agregarTabs('INGRESOS POR GUIAS ENTREGADAS',4,null);
		u.tab_contenedor_id4.disabled=true;
		tabs.seleccionar(0);
	}
	
	function ObtenerDetalle(){
		
		if(u.pag1_fecha.value=="" || u.pag1_fecha2.value==""){
			mens.show("A","Debe capturar "+((u.pag1_fecha.value=="")? " fecha inicio" : "fecha fin"),"¡Atención!",((u.pag1_fecha.value=="")? "" : "" ));	 	
		}else{ 
			var fec1 = (u.pag1_fecha.value.substr(6,4)+u.pag1_fecha.value.substr(3,2)+u.pag1_fecha.value.substr(0,2))*1;
			var fec2 = (u.pag1_fecha2.value.substr(6,4)+u.pag1_fecha2.value.substr(3,2)+u.pag1_fecha2.value.substr(0,2))*1;
			alert(fec2);
			alert(fec1);
			if (fec2 < fec1){
				mens.show("A","La fecha final debe ser mayor a la fecha de inicial","¡Atención!","");
			}else{
				consultaTexto("resTabla1","principal_con.php?accion=1&fecha="+u.pag1_fecha.value
			+"&fecha2="+u.pag1_fecha2.value+"&contador="+u.pag1_contador.value+"&s="+Math.random());
			}
		}
	}
	function resTabla1(datos){
		try{ 
		var obj = eval(convertirValoresJson(datos));
		}
		catch(e){ mens.show("A",datos,"¡Atención!",""); }
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		u.pag1_efectivo.value		= "$ "+obj.totales.efectivo; 
		u.pag1_transferencia.value	= "$ "+obj.totales.transferencia; 
		u.pag1_cheques.value		= "$ "+obj.totales.cheques; 
		u.pag1_otros.value			= "$ "+obj.totales.otros; 
		u.pag1_tarjeta.value		= "$ "+obj.totales.tarjeta; 
		u.pag1_nc.value				= "$ "+obj.totales.nc; 
		u.pag1_totalg.value			= "$ "+obj.totales.total;
		u.pag1_totalgs.value		= "$ "+obj.totales.totalsistema;
		if(obj.registros.length==0){
			mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");
			tabla1.clear();
		}else{			
			tabla1.setJsonData(obj.registros);
		}
		if(obj.paginado==1){
			document.getElementById('div_paginado1').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado1').style.visibility = 'hidden';
		}
	}
	function paginacion1(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","principal_con.php?accion=1&fecha="+u.pag1_fecha.value
		+"&fecha2="+u.pag1_fecha2.value+"&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","principal_con.php?accion=1&fecha="+u.pag1_fecha.value
		+"&fecha2="+u.pag1_fecha2.value+"&contador="+(parseFloat(u.pag1_contador.value)+1)+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","principal_con.php?accion=1&fecha="+u.pag1_fecha.value
		+"&fecha2="+u.pag1_fecha2.value+"&contador="+(parseFloat(u.pag1_contador.value)-1)+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","principal_con.php?accion=1&fecha="+u.pag1_fecha.value+"&fecha2="+u.pag1_fecha2.value+"&contador="+contador+"&s="+Math.random());
				break;
		}
	}
	
	function agregatotal(){
			consultaTexto("resTabla2","total_con.php?accion=1&fecha="+u.pag1_fecha.value+"&fecha2="+u.pag1_fecha2.value
			+"&contador="+u.pag2_contador.value+"&s="+Math.random());
	}
	function resTabla2(datos){
		if(!tabla2.creada())
			tabla2.create();
		tabs.seleccionar(1);
		u.tab_contenedor_id1.disabled=false;
		
		var obj = eval(datos);
		u.pag2_total.value = obj.total;
		u.pag2_contador.value = obj.contador;
		u.pag2_adelante.value = obj.adelante;
		u.pag2_atras.value = obj.atras;
		tabla2.setJsonData(obj.registros);
		u.pag2_contado.value	= "$ "+obj.totales.contado; 
		u.pag2_cobranza.value	= "$ "+obj.totales.cobranza; 
		u.pag2_entregadas.value	= "$ "+obj.totales.entregadas; 
		u.pag2_totalg.value		= "$ "+obj.totales.total; 
		u.pag2_depositado.value	= "$ "+obj.totales.depositado; 
		u.pag2_saldo.value		= "$ "+obj.totales.saldo; 
		u.pag2_fecha.value		= u.pag1_fecha.value
		u.pag2_fecha2.value		= u.pag1_fecha2.value
		if(obj.paginado==1){
			document.getElementById('div_paginado2').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado2').style.visibility = 'hidden';
		}
	}
	function paginacion2(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla2","total_con.php?accion=1&fecha="+u.pag2_fecha.value+"&fecha2="+u.pag2_fecha2.value
				+"&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("resTabla2","total_con.php?accion=1&fecha="+u.pag2_fecha.value+"&fecha2="+u.pag2_fecha2.value
					+"&contador="+(parseFloat(u.pag2_contador.value)+1)+"&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("resTabla2","total_con.php?accion=1&fecha="+u.pag2_fecha.value+"&fecha2="+u.pag2_fecha2.value
					+"&contador="+(parseFloat(u.pag2_contador.value)-1)+"&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag2_total.value)-1)/parseFloat(pag2_cantidadporpagina));
				consultaTexto("resTabla2","total_con.php?accion=1&fecha="+u.pag2_fecha.value+"&fecha2="+u.pag2_fecha2.value
				+"&contador="+contador+"&s="+Math.random());
				break;
		}
	}
	
	function agregacontado(){
		var obj = tabla2.getSelectedRow();
		b_sucursal = obj.sucursal;
		u.sucursal.value = obj.sucursal;
		consultaTexto("resTabla3","contado.php?accion=1&fecha="+u.pag2_fecha.value+"&fecha2="+u.pag2_fecha2.value
		+"&sucursal="+b_sucursal+"&contador="+u.pag3_contador.value+"&s="+Math.random());
	}
	function resTabla3(datos){
		if(!tabla3.creada())
			tabla3.create();
		tabs.seleccionar(2);
		u.tab_contenedor_id2.disabled=false;
		var obj = eval(datos);
		u.pag3_total.value 	  = obj.total;
		u.pag3_contador.value = obj.contador;
		u.pag3_adelante.value = obj.adelante;
		u.pag3_atras.value 	  = obj.atras;
		u.pag3_fecha.value	  = u.pag2_fecha.value
		u.pag3_fecha2.value	  = u.pag2_fecha2.value
		u.pag3_importe.value  = "$ "+obj.totales.importe; 
		tabla3.setJsonData(obj.registros);
		if(obj.paginado==1){
			document.getElementById('div_paginado3').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado3').style.visibility = 'hidden';
		}
	}
	function paginacion3(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla3","contado.php?accion=1&fecha="+u.pag3_fecha.value+"&fecha2="+u.pag3_fecha2.value
				+"&sucursal="+b_sucursal+"&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag3_adelante.value==1){
					consultaTexto("resTabla3","contado.php?accion=1&fecha="+u.pag3_fecha.value+"&fecha2="+u.pag3_fecha2.value
					+"&sucursal="+b_sucursal+"&contador="+(parseFloat(u.pag3_contador.value)+1)+"&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag3_atras.value==1){
					consultaTexto("resTabla3","contado.php?accion=1&fecha="+u.pag3_fecha.value+"&fecha2="+u.pag3_fecha2.value
					+"&sucursal="+b_sucursal+"&contador="+(parseFloat(u.pag3_contador.value)-1)+"&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag3_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla3","contado.php?accion=1&fecha="+u.pag3_fecha.value+"&fecha2="+u.pag3_fecha2.value
				+"&sucursal="+b_sucursal+"&contador="+contador+"&s="+Math.random());
				break;
		}
	}
	
	function agregacobranza(){
		var obj = tabla2.getSelectedRow();
		b_sucursal = obj.sucursal;
		u.sucursal.value = obj.sucursal;
		consultaTexto("resTabla4","cobranza.php?accion=1&fecha="+u.pag2_fecha.value+"&fecha2="+u.pag2_fecha2.value
		+"&sucursal="+b_sucursal+"&contador="+u.pag4_contador.value+"&s="+Math.random());
	}
	function resTabla4(datos){
		if(!tabla4.creada())
			tabla4.create();
		tabs.seleccionar(3);
		u.tab_contenedor_id3.disabled=false;
		var obj = eval(datos);
		u.pag4_total.value	  = obj.total;
		u.pag4_contador.value = obj.contador;
		u.pag4_adelante.value = obj.adelante;
		u.pag4_atras.value	  = obj.atras;
		u.pag4_fecha.value	  = u.pag2_fecha.value
		u.pag4_fecha2.value	  = u.pag2_fecha2.value
		u.pag4_importe.value  = "$ "+obj.totales.importe; 		
		tabla4.setJsonData(obj.registros);
		if(obj.paginado==1){
			document.getElementById('div_paginado4').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado4').style.visibility = 'hidden';
		}
	}
	function paginacion4(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla4","cobranza.php?accion=1&fecha="+u.pag4_fecha.value+"&fecha2="+u.pag4_fecha2.value
				+"&sucursal="+b_sucursal+"&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag4_adelante.value==1){
					consultaTexto("resTabla4","cobranza.php?accion=1&fecha="+u.pag4_fecha.value+"&fecha2="+u.pag4_fecha2.value
					+"&sucursal="+b_sucursal+"&contador="+(parseFloat(u.pag4_contador.value)+1)+"&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag4_atras.value==1){
					consultaTexto("resTabla4","cobranza.php?accion=1&fecha="+u.pag4_fecha.value+"&fecha2="+u.pag4_fecha2.value
					+"&sucursal="+b_sucursal+"&contador="+(parseFloat(u.pag4_contador.value)-1)+"&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag4_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla4","cobranza.php?accion=1&fecha="+u.pag4_fecha.value+"&fecha2="+u.pag4_fecha2.value
		+"&sucursal="+b_sucursal+"&contador="+contador+"&s="+Math.random());
				break;
		}
	}
	
	function agregaentregadas(){
		var obj = tabla2.getSelectedRow();
		b_sucursal = obj.sucursal;
		u.sucursal.value = obj.sucursal;
		consultaTexto("resTabla5","entregadas.php?accion=1&fecha="+u.pag2_fecha.value+"&fecha2="+u.pag2_fecha2.value
		+"&sucursal="+b_sucursal+"&contador="+u.pag5_contador.value+"&s="+Math.random());
	}
	function resTabla5(datos){
		if(!tabla5.creada())
			tabla5.create();
		tabs.seleccionar(4);
		u.tab_contenedor_id4.disabled=false;
		
		var obj = eval(datos);
		u.pag5_total.value = obj.total;
		u.pag5_contador.value = obj.contador;
		u.pag5_adelante.value = obj.adelante;
		u.pag5_atras.value = obj.atras;
		u.pag5_fecha.value	  = u.pag2_fecha.value
		u.pag5_fecha2.value	  = u.pag2_fecha2.value
		u.pag5_importe.value  = "$ "+obj.totales.importe; 		
		tabla5.setJsonData(obj.registros);		
		if(obj.paginado==1){
			document.getElementById('div_paginado5').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado5').style.visibility = 'hidden';
		}
	}
	function paginacion5(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla5","entregadas.php?accion=1&fecha="+u.pag5_fecha.value+"&fecha2="+u.pag5_fecha2.value
				+"&sucursal="+b_sucursal+"&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("resTabla5","entregadas.php?accion=1&fecha="+u.pag5_fecha.value+"&fecha2="+u.pag5_fecha2.value
					+"&sucursal="+b_sucursal+"&contador="+(parseFloat(u.pag5_contador.value)+1)+"&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){
					consultaTexto("resTabla5","entregadas.php?accion=1&fecha="+u.pag5_fecha.value+"&fecha2="+u.pag5_fecha2.value
					+"&sucursal="+b_sucursal+"&contador="+(parseFloat(u.pag5_contador.value)-1)+"&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla5","entregadas.php?accion=1&fecha="+u.pag5_fecha.value+"&fecha2="+u.pag5_fecha2.value
				+"&sucursal="+b_sucursal+"&contador="+contador+"&s="+Math.random());
				break;
		}
	}
	
	function imprimirReporte(tipo){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/ingresos/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/ingresos/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/ingresos/";
		}
		switch (tipo){
			case 1:
				window.open(v_dir+"generarExcelIngresos.php?accion=1&titulo=REPORTE DE INGRESOS&fecha="+u.pag1_fecha.value
				+"&fecha2="+u.pag1_fecha2.value+"&val="+Math.random());
			break;
			case 2:
				window.open(v_dir+"generarExcelIngresos.php?accion=2&titulo=CONCILIACION DE INGRESOS&fecha="+u.pag2_fecha.value
				+"&fecha2="+u.pag2_fecha2.value+"&val="+Math.random());
			break;
			case 3:
				window.open(v_dir+"generarExcelIngresos.php?accion=3&titulo=INGRESOS POR GUIAS DE CONTADO&fecha="+u.pag3_fecha.value
				+"&fecha2="+u.pag3_fecha2.value+"&sucursal="+u.sucursal.value+"&val="+Math.random());
			break;
			
			case 4:
				window.open(v_dir+"generarExcelIngresos.php?accion=4&titulo=INGRESOS POR COBRANZA&fecha="+u.pag4_fecha.value
				+"&fecha2="+u.pag4_fecha2.value+"&sucursal="+u.sucursal.value+"&val="+Math.random());
			break;
			
			case 5:
				window.open(v_dir+"generarExcelIngresos.php?accion=5&titulo=INGRESOS POR GUIAS ENTREGADAS&fecha="+u.pag5_fecha.value
		+"&fecha2="+u.pag5_fecha2.value+"&sucursal="+u.sucursal.value+"&val="+Math.random());
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
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td width="150" align="right">Fecha Inicio: </td>
	<td align="left"><input name="pag1_fecha" type="text" class="Tablas" id="pag1_fecha" style="width:100px" value="<?=date('d/m/Y') ?>"  /></td>
	<td align="left"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag1_fecha,'dd/mm/yyyy',this)"></div></td>
	<td width="80" align="right">Fecha Final:</td>
	<td align="left"><input name="pag1_fecha2" type="text" class="Tablas" id="pag1_fecha2" style="width:100px" value="<?=date('d/m/Y') ?>" /></td>
	<td align="left"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag1_fecha2,'dd/mm/yyyy',this)"></div></td>
	<td width="100" align="center"><div align="center"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></div></td>
  </tr>
  <tr><td width="100">&nbsp;</td></tr>
  <tr>
    <td colspan="7">
	<div style="height:280px; width:800px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle0">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="7" height="10"><div id="div_paginado1" align="center" style="visibility:hidden">
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
  	<td colspan="7" align="right">
    	<table align="right">
        	<tr>
			  <td width="97"><div align="left">Total Efect: </div></td>
			  <td width="97" align="center"><div align="left"> Total Cheques:</div></td>
			  <td width="97" align="center"><div align="left">Total Otros: </div></td>
			  <td width="97" align="center"><div align="left">Trasf.Elect:</div></td>
			  <td width="90" align="center"><div align="left">Total Tarjeta: </div></td>
			  <td width="90" align="center"><div align="left">Total NC:</div></td>
			  <td width="118" align="center"><div align="left">Total Registrado: </div></td>
			  <td width="118" align="center"><div align="left">Total Sistema: </div></td>
			</tr>
			<tr>
			  <td><input name="pag1_efectivo" type="text" class="Tablas" id="pag1_efectivo" style="text-align:right;width:90px;background:#FFFF99" readonly="" align="right" /></td>
			  <td><input name="pag1_cheques" type="text" class="Tablas" id="pag1_cheques" style="text-align:right;width:90px;background:#FFFF99" readonly="" align="right" /></td>
			  <td align="center"><input name="pag1_otros" type="text" class="Tablas" id="pag1_otros" style="text-align:right;width:90px;background:#FFFF99" readonly="" align="right" /></td>
			  <td align="center"><input name="pag1_transferencia" type="text" class="Tablas" id="pag1_transferencia" style="text-align:right;width:90px;background:#FFFF99" readonly="" align="right" /></td>
			  <td align="center"><input name="pag1_tarjeta" type="text" class="Tablas" id="pag1_tarjeta" style="text-align:right;width:90px;background:#FFFF99" readonly="" align="right" /></td>
			  <td align="center"><input name="pag1_nc" type="text" class="Tablas" id="pag1_nc" style="text-align:right;width:90px;background:#FFFF99" readonly="" align="right" /></td>
			  <td align="center"><input name="pag1_totalg" type="text" class="Tablas" id="pag1_totalg" style="text-align:right;width:90px;background:#FFFF99; cursor:pointer" readonly="" align="right" ondblclick="agregatotal()" title="Conciliación de Ingresos"/></td>
			  <td align="center"><input name="pag1_totalgs" type="text" class="Tablas" id="pag1_totalgs" style="text-align:right;width:90px;background:#FFFF99; cursor:pointer" readonly="" align="right" ondblclick="agregatotal()" title="Total Sistema"/></td>
			</tr>
			 <tr>
			  <td width="14"><div align="right"></div></td>
			  <td width="97"><div align="left"></div></td>
			  <td width="97" align="center"><div align="left"></div></td>
			  <td width="97" align="center"><div align="left"></div></td>
			  <td width="97" align="center"><div align="left"></div></td>
			  <td width="90" align="center"><div align="left"></div></td>
			  <td width="90" align="center"><div align="left"></div></td>
			  <td width="118" align="center"><div align="center"><a onclick="agregatotal()" href="#">Ver Conciliaci&oacute;n</a href></div></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
  				<td align="center" colspan="8"><div class="ebtn_imprimir" onclick="imprimirReporte(1)"></div></td>
		  	</tr>
		  <tr><td>&nbsp;</td></tr>
        </table>
    </td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id1">
<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr><td>&nbsp;</td></tr>
  <tr>
	<td width="66" align="right">Fecha Inicio: </td>
	<td width="104" align="left"><input name="pag2_fecha" type="text" class="Tablas" id="pag2_fecha" style="width:100px" value=""  /></td>
	<td width="37"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag2_fecha,'dd/mm/yyyy',this)"></div></td>
	<td width="62">Fecha Final:</td>
	<td width="108"><input name="pag2_fecha2" type="text" class="Tablas" id="pag2_fecha2" style="width:100px" value="" /></td>
	<td width="59"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag2_fecha2,'dd/mm/yyyy',this)"></div></td>
	<td width="108"><div align="center"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></div></td>
	<td></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td width="700" colspan="8">
	<div style="height:280px; width:800px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle1">
	</table>
	</div></td>
  </tr>
  <tr>
    <td colspan="8"><div id="div_paginado2" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" />
          </div>
          <input type="hidden" name="pag2_total" />
          <input type="hidden" name="pag2_contador" value="0" />
          <input type="hidden" name="pag2_adelante" value="" />
          <input type="hidden" name="pag2_atras" value="" />
          <input type="hidden" name="pag2_sucursal" value="" />
          </td>	
  </tr>
  <tr>
  	<td colspan="8" align="right">
    	<table align="right">
        	<tr>
              <td align="center">&nbsp;</td>
              <td align="right">Contado</td>
              <td align="right">Cobranza</td>
              <td align="right">Entregada</td>
              <td align="right">Total</td>
              <td align="right">Depositado</td>
              <td align="right">Total</td>
			  <td align="center">&nbsp;</td>
			  <td align="center">&nbsp;</td>
            </tr>
            <tr>
			  <td>Totales:</td>
              <td width="80"><input name="pag2_contado" type="text" class="Tablas" id="pag2_contado" style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></td>
              <td width="80" align="center"><div align="left"><input name="pag2_cobranza" type="text" class="Tablas" id="pag2_cobranza" style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></div></td>
              <td width="80" align="center"><div align="left"><input name="pag2_entregadas" type="text" class="Tablas" id="pag2_entregadas" style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></div></td>
              <td width="80" align="center"><div align="left"><input name="pag2_totalg" type="text" class="Tablas" id="pag2_totalg" 
			  style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></div></td>
              <td width="80" align="center"><div align="left"><input name="pag2_depositado" type="text" class="Tablas" id="pag2_depositado" style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></div></td>
              <td width="80" align="center"><div align="left"><input name="pag2_saldo" type="text" class="Tablas" id="pag2_saldo" 
			  style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></div></td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
            </tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
  				<td align="center" colspan="8"><div class="ebtn_imprimir" onclick="imprimirReporte(2)"></div></td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
        </table>
    </td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id2">
<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
	<td width="66" align="right">Fecha Inicio: </td>
	<td width="104" align="left"><input name="pag3_fecha" type="text" class="Tablas" id="pag3_fecha" style="width:100px" value=""  /></td>
	<td width="37"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag3_fecha,'dd/mm/yyyy',this)"></div></td>
	<td width="62">Fecha Final:</td>
	<td width="108"><input name="pag3_fecha2" type="text" class="Tablas" id="pag3_fecha2" style="width:100px" value="" /></td>
	<td width="59"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag3_fecha2,'dd/mm/yyyy',this)"></div></td>
	<td width="108"><div align="center"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></div></td>
  </tr>
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
    <td width="700" colspan="7">
	<div style="height:280px; width:800px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle2">
	</table>
	</div></td>
  </tr>
  <tr>
    <td colspan="7"><div id="div_paginado3" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion3('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion3('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion3('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion3('ultimo')" />
          </div>
          <input type="hidden" name="pag3_total" />
          <input type="hidden" name="pag3_contador" value="0" />
          <input type="hidden" name="pag3_adelante" value="" />
          <input type="hidden" name="pag3_atras" value="" />
          <input type="hidden" name="pag3_idcliente" value="" />
          </td>	
  </tr>
  <tr>
  	<td colspan="7" align="right">
    	<table align="right">
        	<tr>
              <td colspan="6" align="right">Total</td>
			  <td></td>
            </tr>
            <tr>
			  <td colspan="6" align="right"><div align="left"><input name="pag3_importe" type="text" class="Tablas" id="pag3_importe" 
			  style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></div></td>
			  <td></td>
            </tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
  				<td align="center" colspan="8"><div class="ebtn_imprimir" onclick="imprimirReporte(3)"></div></td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
        </table>
    </td>	
  </tr>
  <tr><td colspan="7">&nbsp;</td></tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id3">
<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
	<td width="66" align="right">Fecha Inicio: </td>
	<td width="104" align="left"><input name="pag4_fecha" type="text" class="Tablas" id="pag4_fecha" style="width:100px" value=""  /></td>
	<td width="37"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag4_fecha,'dd/mm/yyyy',this)"></div></td>
	<td width="62">Fecha Final:</td>
	<td width="108"><input name="pag4_fecha2" type="text" class="Tablas" id="pag4_fecha2" style="width:100px" value="" /></td>
	<td width="59"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag4_fecha2,'dd/mm/yyyy',this)"></div></td>
	<td width="108"><div align="center"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></div></td>
  </tr>
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
    <td width="700" colspan="7">
	<div style="height:280px; width:800px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle3">
	</table>
	</div></td>
  </tr>
  <tr>
    <td colspan="7"><div id="div_paginado4" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion4('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion4('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion4('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion4('ultimo')" />
          </div>
          <input type="hidden" name="pag4_total" />
          <input type="hidden" name="pag4_contador" value="0" />
          <input type="hidden" name="pag4_adelante" value="" />
          <input type="hidden" name="pag4_atras" value="" />
          <input type="hidden" name="pag4_idcliente" value="" />
          </td>	
  </tr>
  <tr>
  	<td colspan="7" align="right">
    	<table align="right">
        	<tr>
              <td colspan="6" align="right">Total</td>
			  <td></td>
            </tr>
            <tr>
			  <td colspan="6" align="right"><div align="left"><input name="pag4_importe" type="text" class="Tablas" id="pag4_importe" 
			  style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></div></td>
			  <td></td>
            </tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
  				<td align="center" colspan="8"><div class="ebtn_imprimir" onclick="imprimirReporte(4)"></div></td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
        </table>
    </td>	
  </tr>
  <tr><td colspan="7">&nbsp;</td></tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id4">
<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
	<td width="66" align="right">Fecha Inicio: </td>
	<td width="104" align="left"><input name="pag5_fecha" type="text" class="Tablas" id="pag5_fecha" style="width:100px" value=""  /></td>
	<td width="37"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag5_fecha,'dd/mm/yyyy',this)"></div></td>
	<td width="62">Fecha Final:</td>
	<td width="108"><input name="pag5_fecha2" type="text" class="Tablas" id="pag5_fecha2" style="width:100px" value="" /></td>
	<td width="59"><div class="ebtn_calendario" onclick="displayCalendar(document.all.pag5_fecha2,'dd/mm/yyyy',this)"></div></td>
	<td width="108"><div align="center"><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></div></td>
  </tr>
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
    <td width="700" colspan="7">
	<div style="height:280px; width:800px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle4">
	</table>
	</div></td>
  </tr>
  <tr>
    <td colspan="7"><div id="div_paginado5" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion5('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion5('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion5('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion5('ultimo')" />
          </div>
          <input type="hidden" name="pag5_total" />
          <input type="hidden" name="pag5_contador" value="0" />
          <input type="hidden" name="pag5_adelante" value="" />
          <input type="hidden" name="pag5_atras" value="" />
          <input type="hidden" name="pag5_idcliente" value="" />
          </td>	
  </tr>
  <tr>
  	<td colspan="7" align="right">
    	<table align="right">
        	<tr>
              <td colspan="6" align="right">Total</td>
			  <td></td>
            </tr>
            <tr>
			  <td colspan="6" align="right"><div align="left"><input name="pag5_importe" type="text" class="Tablas" id="pag5_importe" 
			  style="text-align:right;width:90px;background:#FFFF99" value="" readonly="" align="right" /></div></td>
			  <td></td>
            </tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
  				<td align="center" colspan="8"><div class="ebtn_imprimir" onclick="imprimirReporte(5)"></div></td>
		  </tr>
		  <tr><td>&nbsp;</td></tr>
        </table>
    </td>	
  </tr>
  <tr><td colspan="7">&nbsp;</td></tr>
</table>
</div>
<table width="600" border="1" height="500" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla Estilo4">Reporte Principal de Ingresos </td>
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
  <tr><td><input name="sucursal" type="hidden" value="" /></td></tr>
</table>
</form>
</body>
</html>
