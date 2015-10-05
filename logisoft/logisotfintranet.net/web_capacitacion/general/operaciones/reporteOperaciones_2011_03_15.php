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
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script>
	var u = document.all;
	var tabs = new ClaseTabs();
	var mens = new ClaseMensajes();
	var tabla1 	= new ClaseTabla();
	var tabla2 	= new ClaseTabla();
	var tabla3 	= new ClaseTabla();
	var tabla4 	= new ClaseTabla();
	var tabla5 	= new ClaseTabla();
	var tabla4_1= new ClaseTabla();
	var pag1_cantidadporpagina = 30;
	mens.iniciar('../../javascript');
	
	jQuery(function($){	   
	   $('#fecha').mask("99/99/9999");
	   $('#fecha2').mask("99/99/9999");
	});
	
	tabla1.setAttributes({//PRINCIPAL
		nombre:"tabla1",
		campos:[						
			{nombre:"FECHA", medida:120, alineacion:"left", datos:"fecharuta"},
			{nombre:"RUTA", medida:180, onDblClick:"obtenerRuta",alineacion:"center", datos:"ruta"},
			{nombre:"T. EMBARCADO", medida:120, tipo:"moneda", onDblClick:"obtenerViaticos", alineacion:"right", datos:"gastoruta"},
			{nombre:"GASTOS", medida:120, tipo:"moneda", onDblClick:"obtenerGastos", alineacion:"right", datos:"gastotranscurso"},
			{nombre:"UTILIDAD", medida:120,tipo:"moneda", alineacion:"right", datos:"utilidad"},			
			{nombre:"BITACORA", medida:4, tipo:"oculto", alineacion:"center", datos:"bitacora"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({//INFORME DE RUTA
		nombre:"tabla2",
		campos:[						
			{nombre:"UNIDAD", medida:80, alineacion:"left", datos:"unidad"},
			{nombre:"OPERADOR1", medida:170, alineacion:"left", datos:"operador1"},
			{nombre:"OPERADOR2", medida:170, alineacion:"left", datos:"operador2"},
			{nombre:"OPERADOR3", medida:170, alineacion:"left", datos:"operador3"},
			{nombre:"INCIDENTES", medida:70, onDblClick:"obtenerIncidencias", alineacion:"right", datos:"incidentes"},			
			{nombre:"BITACORA", medida:4, tipo:"oculto", alineacion:"center", datos:"bitacora"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({//PRODUCTIVIDAD POR RUTA
		nombre:"tabla3",
		campos:[						
			{nombre:"RUTA", medida:90, alineacion:"left", datos:"ruta"},
			{nombre:"NOMBRE SUCURSAL", medida:170, alineacion:"center", datos:"descripcionsucursal"},
			{nombre:"EMBARCADAS", medida:140, onDblClick:"obtenerRelacionEmbarque", alineacion:"left", datos:"guiasembarcadas"},
			{nombre:"RECIBIDAS", medida:140, alineacion:"left", datos:"guiasrecibidas"},
			{nombre:"IMPORTE", medida:120, tipo:"moneda", alineacion:"right", datos:"importeembarcadas"},			
			{nombre:"BITACORA", medida:4, tipo:"oculto", alineacion:"center", datos:"bitacora"},
			{nombre:"EMBARQUE", medida:4, tipo:"oculto", alineacion:"center", datos:"folioembarque"},
			{nombre:"SUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucursal"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla3"
	});
	
	tabla4.setAttributes({//GASTOS POR RUTA
		nombre:"tabla4",
		campos:[						
			{nombre:"UNIDAD", medida:180, alineacion:"left", datos:"unidad"},
			{nombre:"RUTA", medida:250, alineacion:"left", datos:"ruta"},
			{nombre:"BITACORA", medida:100, alineacion:"left", datos:"bitacora"},
			{nombre:"VIATICOS", medida:120, alineacion:"left", tipo:"moneda", datos:"viaticos"}
		],
		filasInicial:5,
		alto:100,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla4"
	});
	
	tabla5.setAttributes({//REPORTE INCIDENTES
		nombre:"tabla5",
		campos:[						
			{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecha"},
			{nombre:"SUCURSAL", medida:170, alineacion:"left", datos:"sucursal"},
			{nombre:"TIPO INCIDENTE", medida:170, alineacion:"left", datos:"tipoincidente"},
			{nombre:"OPERADOR", medida:230, alineacion:"left", datos:"operador"},
			{nombre:"BITACORA", medida:4, tipo:"oculto", alineacion:"left", datos:"bitacora"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla5"
	});
	
	tabla4_1.setAttributes({//CONCEPTO GASTOS
		nombre:"tabla4_1",
		campos:[
			{nombre:"CONCEPTO", medida:300, alineacion:"left", datos:"concepto"},
			{nombre:"CANTIDAD", medida:200, tipo:"moneda", alineacion:"right", datos:"cantidad"}
		],
		filasInicial:15,
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla4_1"
	});
	
	window.onload = function(){
		tabla1.create();		
		tabs.iniciar({
			nombre:"tab", largo:710, alto:280, ajustex:11,
			ajustey:12, imagenes:"../../img", titulo:"Rentabilidad por Ruta"
		});
		tabs.agregarTabs('Informe de Ruta',1,null);		
		tabs.agregarTabs('Reporte de Incidentes',2,null);		
		tabs.agregarTabs('Productividad por Ruta',3,null);		
		tabs.agregarTabs('Gastos por Ruta',4,null);
		
		u.tab_contenedor_id1.disabled = true;		
		u.tab_contenedor_id2.disabled = true;
		u.tab_contenedor_id3.disabled = true;
		u.tab_contenedor_id4.disabled = true;
		tabs.seleccionar(0);
		u.fecha.focus();
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
		consultaTexto("mostrarPrincipal","consultas.php?accion=1&fechainicio="+u.fecha.value
		+"&fechafin="+u.fecha2.value+"&contador="+u.pag1_contador.value+"&s="+Math.random());
	}
	
	function mostrarPrincipal(datos){
		//mens.show("I",datos);
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		u.ptotalviaticos.value	= "$ "+obj.totales.viaticos;
		u.ptotalgastos.value	= "$ "+obj.totales.gastos;
		u.putilidad.value		= "$ "+obj.totales.utilidad;
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
				consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador=0&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+contador
				+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerRuta(){
		var obj = tabla1.getSelectedRow();
		if(!tabla2.creada()){
			tabla2.create();
		}
		bitacora1 = obj.bitacora;
		consultaTexto("mostrarRuta","consultas.php?accion=2&bitacora="+obj.bitacora
		+"&contador="+u.pag2_contador.value+"&s="+Math.random());
	}
	
	function mostrarRuta(datos){
		//mens.show("I",datos,"")
		var obj = eval(convertirValoresJson(datos));					
		tabla2.setJsonData(obj);
		u.tab_contenedor_id1.disabled = false;		
		tabs.seleccionar(1);		
	}
	
	function obtenerIncidencias(){
		var obj = tabla2.getSelectedRow();
		if(!tabla5.creada()){
			tabla5.create();
		}
		bitacora2 = obj.bitacora;
		consultaTexto("mostrarIncidencias","consultas.php?accion=3&bitacora="+obj.bitacora
		+"&contador="+u.pag3_contador.value+"&s="+Math.random());
	}
	
	function mostrarIncidencias(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag3_total.value 		= obj.total;
		u.pag3_contador.value 	= obj.contador;
		u.pag3_adelante.value 	= obj.adelante;
		u.pag3_atras.value 		= obj.atras;
				
		if(obj.registros.length==0){
			mens.show("I","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla5.clear();
		}else{			
			tabla5.setJsonData(obj.registros);
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
				consultaTexto("mostrarIncidencias","consultas.php?accion=3&contador=0&bitacora="+bitacora2+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag3_adelante.value==1){
					consultaTexto("mostrarIncidencias","consultas.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)+1)
					+"&bitacora="+bitacora2+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag3_atras.value==1){
					consultaTexto("mostrarIncidencias","consultas.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)-1)
					+"&bitacora="+bitacora2+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag3_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarIncidencias","consultas.php?accion=3&contador="+contador
				+"&bitacora="+bitacora2+"&s="+Math.random());
				break;
		}
	}	
	
	function obtenerViaticos(){
		var obj = tabla1.getSelectedRow();
		if(!tabla3.creada()){
			tabla3.create();
		}
		
		bitacora4 = obj.bitacora;		
		consultaTexto("mostrarViaticos","consultas.php?accion=4&bitacora="+obj.bitacora+"&s="+Math.random());
	}
	
	function mostrarViaticos(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag4_total.value 		= obj.total;
		u.pag4_contador.value 	= obj.contador;
		u.pag4_adelante.value 	= obj.adelante;
		u.pag4_atras.value 		= obj.atras;
		u.prtotal1.value		= obj.totales.total1;
		u.prtotal2.value		= obj.totales.total2;
		u.prtotal3.value		= "$ "+obj.totales.total3;
		
		if(obj.registros.length==0){
			mens.show("I","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla3.clear();
		}else{			
			tabla3.setJsonData(obj.registros);			
			u.tab_contenedor_id3.disabled = false;		
			tabs.seleccionar(3);
		}
		if(obj.paginado==1){
			document.getElementById('paginado4').style.visibility = 'visible';
		}else{
			document.getElementById('paginado4').style.visibility = 'hidden';
		}
	}
	
	function paginacion3(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarViaticos","consultas.php?accion=4&contador=0&bitacora="+bitacora4+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag4_adelante.value==1){
					consultaTexto("mostrarViaticos","consultas.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)+1)
					+"&bitacora="+bitacora4+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag4_atras.value==1){
					consultaTexto("mostrarViaticos","consultas.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)-1)
					+"&bitacora="+bitacora4+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag4_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarViaticos","consultas.php?accion=4&contador="+contador
				+"&bitacora="+bitacora4+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerGastos(){
		var obj = tabla1.getSelectedRow();
		if(!tabla4.creada()){
			tabla4.create();
			tabla4_1.create();			
		}
		bitacora5 = obj.bitacora;
		consultaTexto("mostrarGastos","consultas.php?accion=5&bitacora="+obj.bitacora+"&s="+Math.random());
	}
	
	function mostrarGastos(datos){
		var obj = eval(convertirValoresJson(datos));		
		if(obj.registros.length==0){
			mens.show("I","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla4.clear();
			tabla4_1.clear();
		}else{			
			tabla4.setJsonData(obj.registros);
			tabla4_1.setJsonData(obj.registros2);
			u.tab_contenedor_id4.disabled = false;		
			tabs.seleccionar(4);
		}
	}
	
	function obtenerRelacionEmbarque(){
		var obj = tabla3.getSelectedRow();		
		if(obj.guiasembarcadas==0){
			mens.show("I","En la sucursal seleccionada no hubo embarque de mercancia","");
		}else{		
			if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/fpdf/reportes/relacionEmbarque.php?folio="+obj.folioembarque
				+"&sucursal="+obj.idsucursal+"&bitacora="+obj.bitacora+"&rmoperaciones=dfg&val="+Math.random());
			
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
				window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/relacionEmbarque.php?folio="+obj.folioembarque
				+"&sucursal="+obj.idsucursal+"&bitacora="+obj.bitacora+"&rmoperaciones=dfg&val="+Math.random());
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/relacionEmbarque.php?folio="+obj.folioembarque
				+"&sucursal="+obj.idsucursal+"&bitacora="+obj.bitacora+"&rmoperaciones=dfg&val="+Math.random());
			}
		}
	}
	
	function imprimirReporte(tipo){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/operaciones/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/operaciones/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/operaciones/";
		}
		
		switch(tipo){
			case 1:
				window.open(v_dir+"generarExcelOperaciones.php?accion=1&titulo=RENTABILIDAD POR RUTA&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&val="+Math.random());
			break;
			
			case 2:
				window.open(v_dir+"generarExcelOperaciones.php?accion=2&titulo=INFORME DE RUTA&bitacora="+bitacora1
				+"&val="+Math.random());
			break;
			
			case 3:
				window.open(v_dir+"generarExcelOperaciones.php?accion=3&titulo=REPORTE DE INCIDENTES&bitacora="+bitacora2
				+"&val="+Math.random());
			break;
			
			case 4:
				window.open(v_dir+"generarExcelOperaciones.php?accion=4&titulo=PRODUCTIVIDAD POR RUTA&bitacora="+bitacora4
				+"&val="+Math.random());
			break;
			
			case 5:
				window.open(v_dir+"generarExcelConceptos.php?bitacora="+bitacora5+"&val="+Math.random());
			break;
		}
	}
	
	function limpiar(){
		u.fecha.value = "<?=date('d/m/Y') ?>";
		u.fecha2.value = "<?=date('d/m/Y') ?>";		
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
		if(tabla4_1.creada()){
			tabla4_1.clear();
		}	
		u.tab_contenedor_id1.disabled=true;		
		u.tab_contenedor_id2.disabled=true;
		u.tab_contenedor_id3.disabled=true;
		u.tab_contenedor_id4.disabled=true;
		tabs.seleccionar(0);
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id0">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="503" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="82">Fecha Inicial: </td>
            <td width="118"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>" /></td>
            <td width="100"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
            <td width="67">Fecha Final: </td>
            <td width="111"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y') ?>" /></td>
            <td width="101"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
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
            <input name="ptotalviaticos" class="Tablas" type="text" id="ptotalviaticos" style="width:100px; text-align:right; background-color:#FFFF99;" />          </td>
          <td width="126" align="center">
              Total Importe:
                <input name="ptotalgastos" type="text" class="Tablas" id="ptotalgastos" style="text-align:right; width:100px; background:#FFFF99" readonly="" align="right" />          </td>
          <td width="122" align="center">              
                <input name="putilidad" type="text" class="Tablas" id="putilidad" style="text-align:right;width:100px;background:#FFFF99; visibility:hidden"  readonly="" align="right" />          </td>
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
		  <td align="right">&nbsp;</td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id1">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="3">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla2">
		</table>
	</div>       </td>
    </tr> 
<tr>
      <td colspan="3" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(2)"></div></td>
    </tr>   
    <tr>
      <td colspan="2" align="center"><div id="paginado2" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" />
		  <input type="hidden" name="pag2_total" />
          <input type="hidden" name="pag2_contador" value="0" />
          <input type="hidden" name="pag2_adelante" value="" />
          <input type="hidden" name="pag2_atras" value="" />
          </div></td>
		  <td align="right">&nbsp;</td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id2">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="3">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla5">
		</table>
	</div>       </td>
    </tr> 
<tr>
      <td colspan="3" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(3)"></div></td>
    </tr>   
    <tr>
      <td colspan="2" align="center"><div id="paginado3" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion3('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion3('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion3('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion3('ultimo')" />
		  <input type="hidden" name="pag3_total" />
          <input type="hidden" name="pag3_contador" value="0" />
          <input type="hidden" name="pag3_adelante" value="" />
          <input type="hidden" name="pag3_atras" value="" />
          </div></td>
		  <td align="right">&nbsp;</td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id3">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla3">
		</table>
	</div>       </td>
    </tr> 
<tr>
      <td colspan="2" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(4)"></div></td>
    </tr>   
    <tr>
      <td colspan="2" ><table width="100%" height="16" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="3">&nbsp;</td>
          <td width="195"><div align="right">Total Gral:</div></td>
          <td width="106" align="center"><input name="prtotal1" type="text" class="Tablas" id="prtotal1" style="text-align:center;width:100px;background:#FFFF99" readonly="" align="right" /></td>
          <td width="108" align="center"><input name="prtotal2" type="text" class="Tablas" id="prtotal2" style="text-align:center;width:100px;background:#FFFF99" readonly=""/></td>
          <td width="89" align="center"><input name="prtotal3" type="text" class="Tablas" id="prtotal3" style="text-align:right;width:100px;background:#FFFF99" readonly="" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td align="center"><div id="paginado4" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion4('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion4('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion4('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion4('ultimo')" />
		  <input type="hidden" name="pag4_total" />
          <input type="hidden" name="pag4_contador" value="0" />
          <input type="hidden" name="pag4_adelante" value="" />
          <input type="hidden" name="pag4_atras" value="" />
          </div></td>
		  <td align="right">
		  		&nbsp;	  </td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id4">
	<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla4">
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla4_1">
		</table>
	</div>       </td>
    </tr>
    <tr>
       <td align="right">
		  		<div class="ebtn_imprimir" onclick="imprimirReporte(5)"></div>  
	   </td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id5">
	
</div>
<table width="600" height="66" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla Estilo4">Reporte Principal de Operaciones</td>
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
