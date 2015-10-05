<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');	
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
	FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";	
	$r = mysql_query($s,$l) or die($s); $fs = mysql_fetch_object($r);
	$sucdescripcion = cambio_texto($fs->descripcion);
	
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
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ClaseTabsDivs.js"></script>
<script src="../../javascript/moautocomplete.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var tabla2 = new ClaseTabla();
	var tabla3 = new ClaseTabla();
	var mens = new ClaseMensajes();
	var tabs = new ClaseTabs();
	var pag1_cantidadporpagina = 30;
	
	mens.iniciar('../../javascript');
	
	tabla1.setAttributes({//PRINCIPAL
		nombre:"tabla1",
		campos:[
			{nombre:"MOVIMIENTO", medida:80, alineacion:"left", datos:"movimiento"},
			{nombre:"PAGADA-CONTADO", medida:115, tipo:"moneda", alineacion:"right", datos:"pagcontado"},
			{nombre:"PAGADA-CREDITO", medida:115, tipo:"moneda", alineacion:"right", datos:"pagcredito"},
			{nombre:"COBRAR-CONTADO", medida:115, tipo:"moneda", alineacion:"right", datos:"cobcontado"},
			{nombre:"COBRAR-CREDITO", medida:115, tipo:"moneda", alineacion:"right", datos:"cobcredito"},
			{nombre:"TOTAL", medida:120, tipo:"moneda", alineacion:"right", datos:"total", onDblClick:"t1_verTotales"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({//VENTAS REALIZADAS
		nombre:"tabla2",
		campos:[						
			{nombre:"GUIA", 		medida:80, alineacion:"left", datos:"guia"},
			{nombre:"FECHA", 		medida:80, alineacion:"left", datos:"fechaguia"},
			{nombre:"FLETE", 		medida:90, tipo:"moneda", alineacion:"right",  datos:"flete"},
			{nombre:"DESCUENTO", 	medida:90, tipo:"moneda", alineacion:"right", datos:"descuento"},
			{nombre:"FLETE NETO", 	medida:90, tipo:"moneda", alineacion:"right", datos:"fleteneto"},			
			{nombre:"COMISION", 	medida:90, tipo:"moneda", alineacion:"right", datos:"comision"},
			{nombre:"RECOLECCION", 	medida:90, tipo:"moneda", alineacion:"right", datos:"recoleccion"},
			{nombre:"COMISION RAD", medida:90, tipo:"moneda", alineacion:"right", datos:"comisionrad"},			
			{nombre:"ENTREGA", 		medida:90, tipo:"moneda", alineacion:"right", datos:"entrega"},
			{nombre:"COMISION EAD", medida:90, tipo:"moneda", alineacion:"right", datos:"comisionead"},			
			{nombre:"COM. SOBREPESO", medida:90, tipo:"moneda", alineacion:"right", datos:"sobrepeso"},
			{nombre:"TOTAL COM", 	medida:90, tipo:"moneda", alineacion:"right", datos:"total"},
			{nombre:"TOTAL GRAL", 	medida:90, tipo:"moneda", alineacion:"right", datos:"tgral"},
			{nombre:"CONDICION", 	medida:100, alineacion:"left", datos:"condicion"},
			{nombre:"STATUS", 		medida:100, alineacion:"left", datos:"estado"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({//ENVIOS RECIBIDOS
		nombre:"tabla3",
		campos:[						
			{nombre:"GUIA", 		medida:80, alineacion:"left", datos:"guia"},
			{nombre:"FECHA", 		medida:80, alineacion:"left", datos:"fechaguia"},
			{nombre:"FLETE", 		medida:80, tipo:"moneda", alineacion:"left",  datos:"flete"},
			{nombre:"DESCUENTO", 	medida:80, tipo:"moneda", alineacion:"right", datos:"descuento"},
			{nombre:"FLETE NETO", 	medida:80, tipo:"moneda", alineacion:"right", datos:"fleteneto"},			
			{nombre:"COMISION", 	medida:80, tipo:"moneda", alineacion:"right", datos:"comision"},
			{nombre:"RECOLECCION", 	medida:80, tipo:"moneda", alineacion:"right", datos:"recoleccion"},
			{nombre:"COMISION RAD", medida:80, tipo:"moneda", alineacion:"right", datos:"comisionrad"},
			{nombre:"ENTREGA", 		medida:80, tipo:"moneda", alineacion:"right", datos:"entrega"},
			{nombre:"COMISION EAD", medida:80, tipo:"moneda", alineacion:"right", datos:"comisionead"},
			{nombre:"TOTAL COM", 	medida:80, tipo:"moneda", alineacion:"right", datos:"total"},
			{nombre:"TOTAL GRAL", 	medida:80, tipo:"moneda", alineacion:"right", datos:"tgral"},
			{nombre:"CONDICION", 	medida:100, alineacion:"left", datos:"condicion"},
			{nombre:"STATUS", 		medida:100, alineacion:"left", datos:"estado"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla3"
	});
	
	window.onload = function(){
		tabla1.create();		
		tabs.iniciar({
			nombre:"tab", largo:710, alto:280, ajustex:11, ajustey:12, imagenes:"../../img", titulo:"Ventas y Recibido"
		});
		u.btnMovIzq.style.visibility = "hidden";
		u.btnMovDer.style.visibility = "hidden";
		tabs.agregarTabs('Ventas Realizadas Por La Franquicia',1,null);
		tabs.agregarTabs('Envíos Recibidos por la Franquicia',2,null);
		u.tab_contenedor_id1.disabled = true;
		u.tab_contenedor_id2.disabled = true;
		tabs.seleccionar(0);
		u.sucursal.focus();
		obtenerGeneral();
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","consultas.php?accion=0&sucursal="+u.sucursal_hidden.value);
	}
	
	function mostrarGeneral(datos){
		var obj = eval(convertirValoresJson(datos));
		u.folio.value = obj.folio;
		u.fechainicio.value = obj.fechainicio;
	}
	
	function obtenerDetalle(){
		if(u.sucursal_hidden.value==undefined || u.sucursal.value == ""){
			mens.show("A","Debe capturar Franquicia","¡Atención!","sucursal");
			return false;
		}
		consultaTexto("mostrarPrincipal","consultas.php?accion=1&fechainicio="+u.fechainicio.value
		+"&fechafin="+u.fechafin.value+"&contador="+u.pag1_contador.value+"&sucursal="+u.sucursal_hidden.value+"&folio="+u.folio_oculto.value
		+"&s="+Math.random());
	}
	
	function mostrarPrincipal(datos){
		//mens.show("I",datos);
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		u.pagcont.value 		= obj.totales.pagcont;
		u.pagcred.value 		= obj.totales.pagcred;
		u.cobcont.value 		= obj.totales.cobcont;
		u.cobcred.value 		= obj.totales.cobcred;
		u.totales1.value 		= obj.totales.totalgral;
		if(obj.registros.length==0){
			mens.show("I","No se encontrarón datos con los criterios seleccionados","¡Atención!");
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
				consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+contador
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
		}
	}
	
	function t1_verTotales(){		
		var obj = tabla1.getSelectedRow();		
		switch(obj.movimiento){
			case 'VENTA':
				if(!tabla2.creada()){
					tabla2.create();
				}
				consultaTexto("mostrarVentas","consultas.php?accion=2&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
				
			case 'RECIBIDO':
				if(!tabla3.creada()){
					tabla3.create();
				}
				consultaTexto("mostrarRecibido","consultas.php?accion=3&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
				
			case 'INGRESO':
				if(!tabla4.creada()){
					tabla4.create();
				}
				consultaTexto("mostrarIngreso","consultas.php?accion=4&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
		}
	}
	
	function mostrarVentas(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag2_total.value 		= obj.total;
		u.pag2_contador.value 	= obj.contador;
		u.pag2_adelante.value 	= obj.adelante;
		u.pag2_atras.value 		= obj.atras;
		u.flete2.value			= obj.totales.flete;
		u.desc2.value			= obj.totales.descuento;
		u.fleteneto2.value		= obj.totales.fleteneto;
		u.com2.value			= obj.totales.comision;
		u.recol2.value			= obj.totales.recoleccion;
		u.crad2.value			= obj.totales.comisionrad;
		u.entrega2.value		= obj.totales.entrega;
		u.comead2.value			= obj.totales.flete;
		u.sobrepeso2.value		= obj.totales.flete;
		u.total2.value 			= obj.totales.total;
		u.totales2.value 		= obj.totales.totalgral;		
		if(obj.registros.length==0){
			tabla2.clear();
		}else{
			tabs.seleccionar(1);
			u.tab_contenedor_id1.disabled = false;
			tabla2.setJsonData(obj.registros);
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
				consultaTexto("mostrarVentas","consultas.php?accion=2&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("mostrarVentas","consultas.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)+1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("mostrarVentas","consultas.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)-1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag2_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarVentas","consultas.php?accion=2&contador="+contador
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
		}
	}
	
	function mostrarRecibido(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag3_total.value 		= obj.total;
		u.pag3_contador.value 	= obj.contador;
		u.pag3_adelante.value 	= obj.adelante;
		u.pag3_atras.value 		= obj.atras;
		u.flete3.value			= obj.totales.flete;
		u.desc3.value			= obj.totales.descuento;
		u.fleteneto3.value		= obj.totales.fleteneto;
		u.com3.value			= obj.totales.comision;
		u.recol3.value			= obj.totales.recoleccion;
		u.crad3.value			= obj.totales.comisionrad;
		u.entrega3.value		= obj.totales.entrega;
		u.comead3.value			= obj.totales.flete;
		u.total3.value 			= obj.totales.total;
		u.totales3.value 		= obj.totales.totalgral;
		if(obj.registros.length==0){
			tabla3.clear();
		}else{
			tabs.seleccionar(2);
			u.tab_contenedor_id2.disabled = false;
			tabla3.setJsonData(obj.registros);
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
				consultaTexto("mostrarRecibido","consultas.php?accion=3&contador=0&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag3_adelante.value==1){
					consultaTexto("mostrarRecibido","consultas.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)+1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag3_atras.value==1){
					consultaTexto("mostrarRecibido","consultas.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)-1)
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag3_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarRecibido","consultas.php?accion=3&contador="+contador
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
		}
	}
	
	function mostrarIngreso(datos){
		
	}
	
	function guardar(){
		if(u.sucursal_hidden.value==null || u.sucursal_hidden.value==undefined){
			mens.show("A","Debe capturar Concesion","¡Atención!","sucursal");
			return false;
		}
		
		if(tabla1.getRecordCount()==0){
			mens.show("A","No existen datos en el detallado del reporte","¡Atención!");
			return false;
		}
		
		mens.show('C','Se guardara la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'confirmaGuardar()');
	}
	
	function confirmaGuardar(){
		u.btnGuardar.style.visibility = "hidden";
		consultaTexto("registro","consultas.php?accion=5&sucursal="+u.sucursal_hidden.value
		+"&fechainicio="+u.fechainicio.value
		+"&fechafin="+u.fechafin.value+"&folio="+u.folio.value
		+"&val="+Math.random());
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			var r = datos.split(",");
			u.folio.value = r[1];
			u.btnGuardar.style.visibility = "visible";
			mens.show("I","Los datos han sido guardados correctamente","");
			u.imprimir1.style.visibility = 'visible';
			u.imprimir2.style.visibility = 'visible';
			u.imprimir3.style.visibility = 'visible';
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
		}
	}
	
	function obtenerFolio(folio,id){
		u.folio.value = folio;
		u.folio_oculto.value = folio;
		consultaTexto("mostrarFolio","consultas.php?accion=6&folio="+folio+"&sucursal="+id);
	}
	
	function mostrarFolio(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.fecha.value 			= obj.principal.fechaconcesion;
			u.fechainicio.value 	= obj.principal.fechainicio;
			u.fechafin.value 		= obj.principal.fechafin;
			u.sucursal.value 		= obj.principal.sucursal;
			u.sucursal_hidden.value = obj.principal.idsucursal;
			tabla1.setJsonData(obj.tabla1);
			
			u.imprimir1.style.visibility = 'visible';
			u.imprimir2.style.visibility = 'visible';
			u.imprimir3.style.visibility = 'visible';
			u.sucursal.disabled = "disabled";
			document.getElementById('buscar_sucursal').style.visibility = 'hidden';
			
		}else{
			mens.show("A","El Folio de Reporte de Concesiones no existe","¡Atención!","folio");
			u.folio_oculto.value = '';
		}
	}
	
	function limpiar(){
		tabs.seleccionar(0);
		try{
			tabla1.clear();
		}catch(e){
			e = null;
		}
		try{
			if(!tabla2.creada())
				tabla2.clear();
		}catch(e){
			e = null;
		}
		try{
			if(!tabla3.creada())
				tabla3.clear();
		}catch(e){
			e = null;
		}
		u.totales1.value="";
		u.sucursal_hidden.value = "";
		u.sucursal.value = "";
		u.pag1_contador.value = 0;
		u.pag2_contador.value = 0;
		u.pag3_contador.value = 0;
		u.folio_oculto.value = '';
		u.fechainicio.value = '';
		u.fechafin.value = '';		
		u.imprimir1.style.visibility = 'hidden';
		u.imprimir2.style.visibility = 'hidden';
		u.imprimir3.style.visibility = 'hidden';	
		u.sucursal.disabled = false;
		document.getElementById('buscar_sucursal').style.visibility = 'visible';	
		obtenerGeneral();
	}
	
	function obtenerSucursal(sucursal){
		u.sucursal_hidden.value = sucursal;
		consultaTexto("mostrarSucursal","consultas.php?accion=7&sucursal="+sucursal);
	}
	
	function mostrarSucursal(datos){
		var obj = eval(convertirValoresJson(datos));
		u.sucursal.value = obj.descripcion;
		obtenerGeneral();
	}
	
	function imprimirReporte(tipo){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/fpdf/reportes/concesion/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/concesion/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/fpdf/reportes/concesion/";
		}
		switch(tipo){
			
			case 1:
				window.open(v_dir+"ventasRecibido.php?usuario=<?=$_SESSION[IDUSUARIO] ?>&folio="+u.folio.value+"&val="+Math.random());
			break;
		
			case 2:
				window.open(v_dir+"ventasRealizadas.php?folio="+u.folio.value+"&val="+Math.random());
			break;
			
			case 3:
				window.open(v_dir+"enviosRecibidos.php?folio="+u.folio.value+"&val="+Math.random());
			break;
		}
	}
	
	function imprimir(){
		mens.popup("../../buscadores_generales/impresionGeneral.php?funcion=mandarImpresion", 300,160,'ninguno','ELIGE LA IMPRESIÓN');
	}
	
	function mandarImpresion(datos){
		var reportee="";
		var reportep="";
		if(document.getElementById('tab_tab_id0').style.display!='none'){
			reportee = "con_ventasyrecibido_excel.php";
			reportep = "ventasRecibido.php";
		}else if(document.getElementById('tab_tab_id1').style.display!='none'){
			reportee = "con_ventasrealizadas_excel.php";
			reportep = "ventasRealizadas.php";
		}else{
			reportee = "con_ventasrecibido_excel.php";
			reportep = "enviosRecibidos.php";
		}
		
		if(datos.indexOf('EXCEL')>-1){
			window.open("../general/concesiones/"+reportee+"?fechainicio="+u.fechainicio.value+
			"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&usuario=<?=$_SESSION[IDUSUARIO];?>");
			if (u.folio_oculto.value!=''){
			window.open("../general/concesiones/acuserecibo.php?sucursal="+u.sucursal_hidden.value+"&folio="+u.folio_oculto.value
			+"&usuario=<?=$_SESSION[IDUSUARIO];?>");
			}
		}
		
		var laUrl = document.URL;
		if(laUrl.indexOf('pmmintranet.net')>-1){
			var direccion = laUrl.substr(0,
				laUrl.indexOf('pmmintranet.net')+15+
				((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacion')>-1)?18:1))
			);
		}else{
			var direccion = laUrl.substr(0,
				laUrl.indexOf('pmmintranet.com')+15+
				((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacion')>-1)?18:1))
			);
		}
		
		if(datos.indexOf('PDF')>-1){
			if (u.folio_oculto.value!=''){
			window.open(direccion+"fpdf/reportes/acusereciboconcesion.php?sucursal="+u.sucursal_hidden.value
			+"&folio="+u.folio_oculto.value+"&usuario=<?=$_SESSION[IDUSUARIO];?>");
			}
			window.open(direccion+"fpdf/reportes/concesion/"+reportep+"?fechainicio="+u.fechainicio.value+
			"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value+"&usuario=<?=$_SESSION[IDUSUARIO];?>");
		}
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id0">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">            
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla1">
		</table>
	</div>       </td>
    </tr> 
	<tr>
      <td width="503" align="center"><div id="paginado1" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion1('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion1('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion1('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion1('ultimo')" />
		  <input type="hidden" name="pag1_total" />
          <input type="hidden" name="pag1_contador" value="0" />
          <input type="hidden" name="pag1_adelante" value="" />
          <input type="hidden" name="pag1_atras" value="" />
          </div></td>
		  <td width="503" align="right"></td>
    </tr>
	<tr>
      <td colspan="2" align="right"><table border="0" cellpadding="0" cellspacing="0">
	  <tr><td width="120px" align="center">Pagado Contado </td>
		<td width="120px" align="center">Pagado Credito </td>
		<td width="120px" align="center">Cobrar Contado </td>
		<td width="120px" align="center">Cobrar Credito </td>
		<td width="120px" align="center">Total</td></tr></table>
	  </td>
    </tr>
	<tr>
      <td colspan="2" align="right"><table border="0" cellpadding="0" cellspacing="0">
	  <tr><td><input name="pagcont" type="text" class="Tablas" id="pagcont" readonly style="background-color:#FFFF99; text-align:right;"></td>
	  <td><input name="pagcred" type="text" class="Tablas" id="pagcred" readonly style="background-color:#FFFF99; text-align:right;"></td>
	  <td><input name="cobcont" type="text" class="Tablas" id="cobcont" readonly style="background-color:#FFFF99; text-align:right;"></td>
	  <td><input name="cobcred" type="text" class="Tablas" id="cobcred" readonly style="background-color:#FFFF99; text-align:right;"></td>
	  <td><input name="totales1" type="text" class="Tablas" id="totales1" readonly style="background-color:#FFFF99; text-align:right;"></td></tr></table>
	  </td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id1">
<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">    
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla2">
		</table>
	</div>       </td>
    </tr> 
<tr>
      <td colspan="2" align="right"></td>
    </tr>
    <tr>
      <td colspan="2" width="503" align="center"><div id="paginado2" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" />
		  <input type="hidden" name="pag2_total" />
          <input type="hidden" name="pag2_contador" value="0" />
          <input type="hidden" name="pag2_adelante" value="" />
          <input type="hidden" name="pag2_atras" value="" />
          </div></td>
    </tr>
	<tr>
      <td colspan="2" align="right"><table border="0" cellpadding="0" cellspacing="0">
	  <tr><td width="60px" align="center">Flete</td>
		<td width="60px" align="center">Desc</td>
		<td width="70px" align="center">FleteNeto</td>
		<td width="60px" align="center">Comision</td>
		<td width="50px" align="center">Recolec</td>
		<td width="40px" align="center">ComRAD</td>
		<td width="70px" align="center">Entrega</td>
		<td width="60px" align="center">ComEAD</td>
		<td width="70px" align="center">Sobrepeso</td>
		<td width="70px" align="center">TotalCom</td>
		<td width="70px" align="center">TotalGral</td>
		</tr></table>
	  </td>
    </tr>
	<tr>
      <td colspan="2" align="right">
	  	<input name="flete2" type="text" class="Tablas" id="flete2" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
	  	<input name="desc2" type="text" class="Tablas" id="desc2" readonly style="background-color:#FFFF99; text-align:right; width:40px;">
		<input name="fleteneto2" type="text" class="Tablas" id="fleteneto2" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
	  	<input name="com2" type="text" class="Tablas" id="com2" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
		<input name="recol2" type="text" class="Tablas" id="recol2" readonly style="background-color:#FFFF99; text-align:right; width:40px;">
	  	<input name="crad2" type="text" class="Tablas" id="crad2" readonly style="background-color:#FFFF99; text-align:right; width:40px;">
		<input name="entrega2" type="text" class="Tablas" id="entrega2" readonly style="background-color:#FFFF99; text-align:right; width:50px;">
	  	<input name="comead2" type="text" class="Tablas" id="comead2" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
		<input name="sobrepeso2" type="text" class="Tablas" id="sobrepeso2" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
		<input name="total2" type="text" class="Tablas" id="total2" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
	  	<input name="totales2" type="text" class="Tablas" id="totales2" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
	  </td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id2">
<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">    
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla3">
		</table>
	</div>       </td>
    </tr> 
<tr>
      <td colspan="2" align="right"></td>
    </tr>
   <tr>
      <td width="503" align="center"><div id="paginado3" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion3('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion3('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion3('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion3('ultimo')" />
		  <input type="hidden" name="pag3_total" />
          <input type="hidden" name="pag3_contador" value="0" />
          <input type="hidden" name="pag3_adelante" value="" />
          <input type="hidden" name="pag3_atras" value="" />
          </div></td>
		  <td width="503" align="right"></td>
    </tr>
	<tr>
      <td colspan="2" align="right"><table border="0" cellpadding="0" cellspacing="0">
	  <tr><td width="60px" align="center">Flete</td>
		<td width="70px" align="center">Desc</td>
		<td width="80px" align="center">FleteNeto</td>
		<td width="80px" align="center">Comision</td>
		<td width="50px" align="center">Recolec</td>
		<td width="60px" align="center">ComRAD</td>
		<td width="70px" align="center">Entrega</td>
		<td width="70px" align="center">ComEAD</td>
		<td width="60px" align="center">TotalCom</td>
		<td width="90px" align="center">TotalGral</td>
		</tr></table>
	  </td>
    </tr>
	<tr>
	  <td colspan="2" align="right">
	  	<input name="flete3" type="text" class="Tablas" id="flete3" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
	  	<input name="desc3" type="text" class="Tablas" id="desc3" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
		<input name="fleteneto3" type="text" class="Tablas" id="fleteneto3" readonly style="background-color:#FFFF99; text-align:right; width:70px;">
	  	<input name="com3" type="text" class="Tablas" id="com3" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
		<input name="recol3" type="text" class="Tablas" id="recol3" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
	  	<input name="crad3" type="text" class="Tablas" id="crad3" readonly style="background-color:#FFFF99; text-align:right; width:40px;">
		<input name="entrega3" type="text" class="Tablas" id="entrega3" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
	  	<input name="comead3" type="text" class="Tablas" id="comead3" readonly style="background-color:#FFFF99; text-align:right; width:70px;">
		<input name="total3" type="text" class="Tablas" id="total3" readonly style="background-color:#FFFF99; text-align:right; width:60px;">
	  	<input name="totales3" type="text" class="Tablas" id="totales3" readonly style="background-color:#FFFF99; text-align:right; width:70px;">
	  </td>
    </tr>
  </table>
</div>
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193" height="500px">
    <tr>
      <td colspan="5" class="FondoTabla">REPORTE DE FRANQUICIAS O CONCESIONES </td>
    </tr>		
    <tr>
      <td colspan="5" height="450px" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	  	<tr>
			<td>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="94">Folio:</td>
                    <td width="99"><label>
                      <input name="folio" type="text" id="folio" class="Tablas" style="width:80px" onkeypress="if(event.keyCode==13){obtenerFolio(this.value);}" />
                    </label></td>
                    <td width="109"><div class="ebtn_buscar" onclick="abrirVentanaFija('../../buscadores_generales/buscarFolioConcesiones.php', 600, 500, 'ventana', 'Busqueda Folio')" ></div></td>
                    <td width="78">Fecha:</td>
                    <td><input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px; background-color:#FFFF99" value="<?=date('d/m/Y') ?>" readonly="" /></td>
                    <td>
                    	<div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>Fecha Inicio: </td>
                    <td><input name="fechainicio" type="text" class="Tablas" id="fechainicio" style="width:80px; background-color:#FFFF99" readonly="" /></td>
                    <td>&nbsp;</td>
                    <td>Fecha Fin: </td>
                    <td><input name="fechafin" type="text" class="Tablas" id="fechafin" style="width:80px; background-color:#FFFF99" value="<?=date('d/m/Y') ?>" readonly="" /></td>
                    <td>
                    	<div class="ebtn_calendario" onclick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>Concesion:
                        <input name="sucursal_hidden" type="hidden" id="sucursal_hidden"/></td>
                    <td colspan="2"><label>
                      <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:200px" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}else{obtenerGeneral();}}" onblur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}else{obtenerGeneral();}}"/>
                    </label></td>
                    <td><div id='buscar_sucursal' class="ebtn_buscar" onclick="abrirVentanaFija('../../buscadores_generales/buscarFranquicias.php', 600, 500, 'ventana', 'Busqueda Concesiones')" ></div></td>
                    <td width="89"><div class="ebtn_Generar" onclick="obtenerDetalle()"></div></td>
					
                    <td width="127"><input name="folio_oculto" type="hidden" id="folio_oculto"/></td>
                  </tr>
              </table>			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
				<tr>
			<td>&nbsp;</td>
		</tr>
        <tr>		
          <td><table id="tab" cellpadding="0" cellspacing="0" border="0">
        </table></td>
        </tr>
		<tr>
		  <td align="right">&nbsp;</td>
		</tr>
		<tr>
			<td height="350px" valign="bottom" align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="72%" align="right"><div class="ebtn_guardar" id="btnGuardar" onclick="guardar()"></div></td>
    <td width="13%" align="right"><div class="ebtn_imprimir" id="btnImprimir" onclick="imprimir()"></div></td>
    <td width="15%" align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
  </tr>
</table>
</td>
		</tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
