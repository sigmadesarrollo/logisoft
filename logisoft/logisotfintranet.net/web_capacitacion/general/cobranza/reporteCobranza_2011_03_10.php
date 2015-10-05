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
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" />
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

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
			{nombre:"Sucursal", medida:135, alineacion:"left", datos:"sucursal"},
			{nombre:"Clientes", medida:135, onDblClick:"cf_t1clientes",alineacion:"right", datos:"clientes"},
			{nombre:"Cartera Vigente", medida:135, tipo:"moneda", alineacion:"right",  datos:"carteravigente"},
			{nombre:"Cartera Morosa", medida:135,tipo:"moneda", alineacion:"right",  datos:"carteramorosa"},
			{nombre:"Cartera Total", medida:135, onDblClick:"cf_t1total", tipo:"moneda",alineacion:"right", datos:"carteratotal"}			
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
			{nombre:"Cliente", medida:70, alineacion:"center",  datos:"idcliente"},
			{nombre:"Nombre", medida:150, onDblClick:"cf_t2ncliente", alineacion:"center", datos:"cliente"},
			{nombre:"Monto Autorizado", tipo:"moneda", onDblClick:"cf_t2clientes", medida:90, alineacion:"center", datos:"montoautorizado"},
			{nombre:"Dias Credito", medida:90, alineacion:"center", datos:"diascredito"},
			{nombre:"Fecha Revision", medida:90, alineacion:"center", datos:"fecharevision"},
			{nombre:"Fecha Pago", medida:90, alineacion:"center", datos:"fechapago"},
			{nombre:"Rotacion Cartera", medida:90, alineacion:"center", datos:"rotacioncobranza"}
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
			{nombre:"Fecha Credito", medida:168, alineacion:"center", datos:"fecha"},
			{nombre:"Importe", medida:168, alineacion:"right", tipo:"moneda", datos:"montoautorizado"},
			{nombre:"Modifico", medida:168, alineacion:"center", datos:"usuario"},
			{nombre:"Solicitud", medida:168, alineacion:"center", datos:"solicitud"}
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
			{nombre:"Sucursal", medida:80, alineacion:"center",  datos:"prefijosucursal"},
			{nombre:"Cliente", medida:120, alineacion:"center", datos:"cliente"},
			{nombre:"Folio", medida:90, alineacion:"center",  datos:"folio"},
			{nombre:"Fecha", medida:80, alineacion:"center", datos:"fecha"},
			{nombre:"fechavenc", medida:80, alineacion:"center",  datos:"fechavenc"},
			{nombre:"Dias vencidos", medida:100, alineacion:"center", datos:"diasvencidos"},
			{nombre:"alcorriente", medida:100, alineacion:"right", tipo:"moneda",  datos:"alcorriente"},
			{nombre:"1_15_Dias", medida:90, alineacion:"right", tipo:"moneda", datos:"c1a15dias"},
			{nombre:"16_30_Dias", medida:90, alineacion:"right", tipo:"moneda",  datos:"c16a30dias"},
			{nombre:"31_60_Dias", medida:90, alineacion:"right", tipo:"moneda", datos:"c31a60dias"},
			{nombre:"Mas_60_Dias", medida:90, alineacion:"right", tipo:"moneda",  datos:"may60dias"},
			{nombre:"Saldo", medida:90, alineacion:"right", tipo:"moneda", datos:"saldo"},
			{nombre:"Factura", medida:90, alineacion:"center",  datos:"factura"},
			{nombre:"Contrarecibo", medida:90, alineacion:"center", datos:"contrarecibo"}
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
			{nombre:"Fecha", medida:80, alineacion:"center",  datos:"fecha"},
			{nombre:"Sucursal", medida:80, alineacion:"center", datos:"sucursal"},
			{nombre:"Ref Cargo", medida:100, alineacion:"center",  datos:"referenciacargo"},					
			{nombre:"Ref Abono", medida:100, alineacion:"left", datos:"referenciaabono"},
			{nombre:"Cargo", medida:100, tipo:"moneda", alineacion:"right", datos:"cargos"},
			{nombre:"Abono", medida:100, tipo:"moneda", alineacion:"right", datos:"abonos"},
			{nombre:"Saldo", medida:100, tipo:"moneda", alineacion:"right", datos:"saldo"},
			{nombre:"Descripcion", medida:100, alineacion:"center", datos:"descripcion"}
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
			nombre:"tab", largo:710, alto:280, ajustex:11,
			ajustey:12, imagenes:"../../img"
		});
		tabs.agregarTabs('Clientes con Credito',1,null);		
		u.tab_contenedor_id1.disabled=true;
		tabs.agregarTabs('Historial Linea Credito',2,null);
		u.tab_contenedor_id2.disabled=true;
		tabs.agregarTabs('Antigüedad Saldos',3,null);
		u.tab_contenedor_id3.disabled=true;
		tabs.agregarTabs('Movimientos Credito',4,null);
		u.tab_contenedor_id4.disabled=true;
		tabs.seleccionar(0);
	}
	
	
	function obtenerDetalle(){
		consultaTexto("resTabla1","reporteCobranza_con.php?accion=1&contador="+u.pag1_contador.value+"&s="+Math.random());
	}
	function resTabla1(datos){
		var obj = eval(datos);
		u.pag1_total.value = obj.total;
		u.pag1_contador.value = obj.contador;
		u.pag1_adelante.value = obj.adelante;
		u.pag1_atras.value = obj.atras;
		tabla1.setJsonData(obj.registros);
		
		//totales
		u.t1_clientes.value = obj.totales.clientes;
		u.t1_carteramorosa.value = "$ "+obj.totales.carteramorosa;
		u.t1_carteravigente.value = "$ "+obj.totales.carteravigente;
		u.t1_carteratotal.value = "$ "+obj.totales.carteratotal;
		if(obj.paginado==1){
			document.getElementById('div_paginado1').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado1').style.visibility = 'hidden';
		}
	}
	function paginacion1(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","reporteCobranza_con.php?accion=1&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","reporteCobranza_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","reporteCobranza_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","reporteCobranza_con.php?accion=1&contador="+contador+"&s="+Math.random());
				break;
		}
	}
	
	function cf_t1clientes(valor){
		u.pag2_sucursal.value = tabla1.getSelectedRow().sucursal;
		consultaTexto("resTabla2","reporteCobranza_con.php?accion=2&contador="+u.pag2_contador.value+
					  "&prefijosucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
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
		if(obj.paginado==1){
			document.getElementById('div_paginado2').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado2').style.visibility = 'hidden';
		}
	}
	function paginacion2(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla2","reporteCobranza_con.php?accion=2&contador=0&prefijosucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("resTabla2","reporteCobranza_con.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)+1)+
						  "&prefijosucursal="+u.pag2_sucursal.value+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("resTabla2","reporteCobranza_con.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)-1)+
						  "&prefijosucursal="+u.pag2_sucursal.value+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag2_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla2","reporteCobranza_con.php?accion=2&contador="+contador+
					  "&prefijosucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
				break;
		}
	}
	
	function cf_t2clientes(valor){
		u.pag3_idcliente.value = tabla2.getSelectedRow().idcliente;
		consultaTexto("resTabla3","reporteCobranza_con.php?accion=3&contador="+u.pag3_contador.value+
					  "&idcliente="+u.pag3_idcliente.value+
					  "&s="+Math.random());
	}
	function resTabla3(datos){
		
		if(!tabla3.creada())
			tabla3.create();
		tabs.seleccionar(2);
		u.tab_contenedor_id2.disabled=false;
		
		var obj = eval(datos);
		u.pag3_total.value = obj.total;
		u.pag3_contador.value = obj.contador;
		u.pag3_adelante.value = obj.adelante;
		u.pag3_atras.value = obj.atras;
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
				consultaTexto("resTabla3","reporteCobranza_con.php?accion=3&contador=0&idcliente="+u.pag3_idcliente.value+
					  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag3_adelante.value==1){
					consultaTexto("resTabla3","reporteCobranza_con.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)+1)+
						  "&idcliente="+u.pag3_sucursal.value+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag3_atras.value==1){
					consultaTexto("resTabla3","reporteCobranza_con.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)-1)+
						  "&idcliente="+u.pag3_sucursal.value+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag3_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla3","reporteCobranza_con.php?accion=3&contador="+contador+
					  "&idcliente="+u.pag3_sucursal.value+
					  "&s="+Math.random());
				break;
		}
	}
	
	function cf_t1total(valor){
		u.pag4_sucursal.value = tabla1.getSelectedRow().sucursal;
		consultaTexto("resTabla4","reporteCobranza_con.php?accion=4&contador="+u.pag3_contador.value+
					  "&sucursalprefijo="+u.pag4_sucursal.value+
					  "&s="+Math.random());
	}
	function resTabla4(datos){
		
		if(!tabla4.creada())
			tabla4.create();
		tabs.seleccionar(3);
		u.tab_contenedor_id3.disabled=false;
		
		var obj = eval(datos);
		u.pag4_total.value = obj.total;
		u.pag4_contador.value = obj.contador;
		u.pag4_adelante.value = obj.adelante;
		u.pag4_atras.value = obj.atras;
		tabla4.setJsonData(obj.registros);
		
		//totales
		u.t4_vencido.value = "$ "+obj.totales.vencido;
		u.t4_alcorriente.value = "$ "+obj.totales.alcorriente;
		u.t4_total.value = "$ "+obj.totales.total;
		
		if(obj.paginado==1){
			document.getElementById('div_paginado4').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado4').style.visibility = 'hidden';
		}
	}
	function paginacion4(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla4","reporteCobranza_con.php?accion=4&contador=0&sucursalprefijo="+u.pag4_sucursal.value+
					  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag4_adelante.value==1){
					consultaTexto("resTabla4","reporteCobranza_con.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)+1)+
						  "&sucursalprefijo="+u.pag4_sucursal.value+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag4_atras.value==1){
					consultaTexto("resTabla4","reporteCobranza_con.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)-1)+
						  "&sucursalprefijo="+u.pag4_sucursal.value+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag4_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla4","reporteCobranza_con.php?accion=4&contador="+contador+
					  "&sucursalprefijo="+u.pag4_sucursal.value+
					  "&s="+Math.random());
				break;
		}
	}
	
	function cf_t2ncliente(valor){
		u.pag5_idcliente.value = tabla2.getSelectedRow().idcliente;
		consultaTexto("resTabla5","reporteCobranza_con.php?accion=5&contador="+u.pag5_contador.value+
					  "&idcliente="+u.pag5_idcliente.value+"&fecha1="+u.t5_fecha1.value+"&fecha2="+u.t5_fecha2.value+
					  "&prefijosucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
	}
	function generarT5(){
		consultaTexto("resTabla5","reporteCobranza_con.php?accion=5&contador="+u.pag5_contador.value+
					  "&idcliente="+u.pag5_idcliente.value+"&fecha1="+u.t5_fecha1.value+"&fecha2="+u.t5_fecha2.value+
					  "&prefijosucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
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
		tabla5.setJsonData(obj.registros);
		
		//totales
		u.t5_cargos.value = "$ "+obj.totales.cargos;
		u.t5_abonos.value = "$ "+obj.totales.abonos;
		
		if(obj.paginado==1){
			document.getElementById('div_paginado5').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado5').style.visibility = 'hidden';
		}
	}
	function paginacion5(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla5","reporteCobranza_con.php?accion=5&contador=0"+
					  "&idcliente="+u.pag5_idcliente.value+"&fecha1="+u.t5_fecha1.value+"&fecha2="+u.t5_fecha2.value+
					  "&prefijosucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("resTabla5","reporteCobranza_con.php?accion=5&contador="+(parseFloat(u.pag5_contador.value)+1)+
						"&idcliente="+u.pag5_idcliente.value+"&fecha1="+u.t5_fecha1.value+"&fecha2="+u.t5_fecha2.value+
						"&prefijosucursal="+u.pag2_sucursal.value+
						"&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){
					consultaTexto("resTabla5","reporteCobranza_con.php?accion=5&contador="+(parseFloat(u.pag5_contador.value)-1)+
						"&idcliente="+u.pag5_idcliente.value+"&fecha1="+u.t5_fecha1.value+"&fecha2="+u.t5_fecha2.value+
					  	"&prefijosucursal="+u.pag2_sucursal.value+
						"&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla5","reporteCobranza_con.php?accion=5&contador="+contador+
					  "&idcliente="+u.pag5_idcliente.value+"&fecha1="+u.t5_fecha1.value+"&fecha2="+u.t5_fecha2.value+
					  "&prefijosucursal="+u.pag2_sucursal.value+
					  "&s="+Math.random());
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
		
		u.t1_clientes.value			= "";
		u.t1_carteravigente.value	= "";
		u.t1_carteramorosa.value	= "";
		u.t1_carteratotal.value		= "";
		u.t4_vencido.value			= "";
		u.t4_alcorriente.value		= "";
		u.t4_total.value			= "";
		
		u.tab_contenedor_id1.disabled=true;		
		u.tab_contenedor_id2.disabled=true;
		u.tab_contenedor_id3.disabled=true;
		u.tab_contenedor_id4.disabled=true;
		tabs.seleccionar(0);
	}
	
	function imprimirReporte(tipo){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/cobranza/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/cobranza/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/cobranza/";
		}
		switch (tipo){
			case 1:
				window.open(v_dir+"generarExcelCobranza.php?accion=1&titulo=ESTADO DE CUENTAS POR COBRAR&val="+Math.random());
			break;
			case 2:
				window.open(v_dir+"generarExcelCobranza.php?accion=2&titulo=CLIENTES CON CREDITO&prefijosucursal="+u.pag2_sucursal.value+"&val="+Math.random());
			break;
			case 3:
				window.open(v_dir+"generarExcelCobranza.php?accion=3&titulo=HISTORIAL DE LINEA DE CREDITO&idcliente="+u.pag3_idcliente.value+"&val="+Math.random());
			break;
			
			case 4:
				window.open(v_dir+"generacionCobranzaCliente.php?idcliente="+u.pag5_idcliente.value+"&fecha1="+u.t5_fecha1.value+"&fecha2="+u.t5_fecha2.value+
				"&prefijosucursal="+u.pag2_sucursal.value+"&val="+Math.random());
			break;
			
			case 5:
				window.open(v_dir+"generarExcelAntiguedadSaldos.php?titulo=ANTIGÜEDAD DE SALDOS&sucursalprefijo="+u.pag4_sucursal.value
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
    <td width="43"></td>
    <td width="148">&nbsp;</td>
    <td width="104">&nbsp;</td>
    <td width="48"></td>
    <td width="136"></td>
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
  	<td colspan="7" align="right">
    	<table align="right">
        	<tr>
                <td></td>
            	<td>Clientes</td>
                <td>Cartera Vigente</td>
                <td>Cartera Morosa</td>
                <td>Total Cartera</td>
            </tr>
        	<tr>
                <td></td>
            	<td><input type="text" value="" class="Tablas" style="text-align:right"  name="t1_clientes" readonly="" /></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t1_carteravigente" readonly="" /></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t1_carteramorosa" readonly="" /></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t1_carteratotal" readonly="" /></td>
            </tr>
			 <tr>
  				<td align="right" colspan="5"><div class="ebtn_imprimir" onclick="imprimirReporte(1)"></div></td>
		  </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td colspan="7"><div id="div_paginado1" align="center" style="visibility:hidden">
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
    <td colspan="7"><div id="div_paginado2" align="center" style="visibility:hidden">
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
  	<td align="right" colspan="7"><div class="ebtn_imprimir" onclick="imprimirReporte(2)"></div></td>
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
  	<td align="right" colspan="7"><div class="ebtn_imprimir" onclick="imprimirReporte(3)"></div></td>
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
  	<td colspan="7" align="right">
    	<table align="right">
        	<tr>
            	<td>Vencido</td>
                <td>Al Corriente</td>
                <td>Total</td>
            </tr>
        	<tr>
            	<td><input type="text" value="" class="Tablas" style="text-align:right"  name="t4_vencido" readonly=""/></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t4_alcorriente" readonly=""/></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t4_total" readonly=""/></td>
            </tr>
        </table>
    </td>
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
          <input type="hidden" name="pag4_sucursal" value="" />
          </td>	
  </tr>
  <tr>
  	<td align="right" colspan="7"><div class="ebtn_imprimir" onclick="imprimirReporte(5)"></div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id4">
<?
	$fec = date('d/m/Y');
	$f1 = split("/",$fec);
	$fecha1 = "01/".$f1[1]."/".$f1[2];
	$fecha2 = date('d/m/Y');
?>
<table width="550" border="0" cellspacing="0" cellpadding="0"> 
  <tr>
    <td width="51">De:</td>
    <td width="145"><input name="t5_fecha1" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha1 ?>" onkeypress="if(event.keyCode==13){document.all.t5_fecha2.focus()}" onkeydown="if(event.keyCode==9){validarFecha(this.value,'t5_fecha1');}" onblur="if(this.value!='' || this.value!='__/__/____'){validarFecha(this.value,'t5_fecha1');}" />
    <img src="../../img/calendario.gif" id="calendarioInicio" alt="Alta" width="20" height="20" 
    align="absbottom" style="cursor:pointer;" title="Calendario" onclick="displayCalendar(document.all.t5_fecha1,'dd/mm/yyyy',this)" />
    </td>
    <td width="63">&nbsp;</td>
    <td width="29">Al:</td>
    <td width="142"><input name="t5_fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=$fecha2 ?>"  onkeydown="if(event.keyCode==9){validarFecha(this.value,'t5_fecha2');}" onblur="if(this.value!='' || this.value!='__/__/____'){validarFecha(this.value,'t5_fecha2');}"/>
    <img src="../../img/calendario.gif" id="calendarioInicio" alt="Alta" width="20" height="20" 
    align="absbottom" style="cursor:pointer;" title="Calendario" onclick="displayCalendar(document.all.t5_fecha2,'dd/mm/yyyy',this)" />
    </td>
    <td width="270"><img src="../../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onclick="generarT5()" /></td>
    <td width="1"></td>
  </tr> 
  <tr>
    <td colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle4">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="7" align="right">
    	<table align="right">
        	<tr>
            	<td>Cargos</td>
                <td>Abonos</td>
            </tr>
        	<tr>
                <td><input type="text" value="" class="Tablas" readonly="" style="text-align:right"  name="t5_cargos" /></td>
                <td><input type="text" value="" class="Tablas" readonly="" style="text-align:right"  name="t5_abonos" /></td>
            </tr>
        </table>
    </td>
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
  	<td align="right" colspan="7"><div class="ebtn_imprimir" onclick="imprimirReporte(4)"></div></td>
  </tr>
</table>
</div>

<table width="600" height="66" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla Estilo4">Reporte Principal de Cobranza </td>
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
