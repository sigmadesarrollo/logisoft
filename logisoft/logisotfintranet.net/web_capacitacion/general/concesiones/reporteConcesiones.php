<? 	session_start(); 
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../../javascript/ClaseTabsDivs.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script src="../../javascript/funciones.js"></script>
<script src="../../javascript/moautocomplete.js"></script>
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
	var pag1_cantidadporpagina = 30;
	
	mens.iniciar('../../javascript');
	
	jQuery(function($){	   
	   $('#fecha').mask("99/99/9999");
	   $('#fecha2').mask("99/99/9999");
	});
	
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
			{nombre:"FLETE", 		medida:90, tipo:"moneda", alineacion:"left",  datos:"flete"},
			{nombre:"DESCUENTO", 	medida:90, tipo:"moneda", alineacion:"right", datos:"descuento"},
			{nombre:"FLETE NETO", 	medida:90, tipo:"moneda", alineacion:"right", datos:"fleteneto"},			
			{nombre:"COMISION", 	medida:90, tipo:"moneda", alineacion:"right", datos:"comision"},
			{nombre:"RECOLECCION", 	medida:90, tipo:"moneda", alineacion:"right", datos:"recoleccion"},
			{nombre:"COMISION EAD", medida:90, tipo:"moneda", alineacion:"right", datos:"comisionead"},
			{nombre:"ENTREGA", 		medida:90, tipo:"moneda", alineacion:"right", datos:"entrega"},
			{nombre:"COMISION RAD", medida:90, tipo:"moneda", alineacion:"right", datos:"comisionrad"},
			{nombre:"TOTAL", 		medida:90, tipo:"moneda", alineacion:"right", datos:"total"},
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
			{nombre:"FLETE", 		medida:90, tipo:"moneda", alineacion:"left",  datos:"flete"},
			{nombre:"DESCUENTO", 	medida:90, tipo:"moneda", alineacion:"right", datos:"descuento"},
			{nombre:"FLETE NETO", 	medida:90, tipo:"moneda", alineacion:"right", datos:"fleteneto"},			
			{nombre:"COMISION", 	medida:90, tipo:"moneda", alineacion:"right", datos:"comision"},
			{nombre:"RECOLECCION", 	medida:90, tipo:"moneda", alineacion:"right", datos:"recoleccion"},
			{nombre:"COMISION EAD", medida:90, tipo:"moneda", alineacion:"right", datos:"comisionead"},
			{nombre:"ENTREGA", 		medida:90, tipo:"moneda", alineacion:"right", datos:"entrega"},
			{nombre:"COMISION RAD", medida:90, tipo:"moneda", alineacion:"right", datos:"comisionrad"},
			{nombre:"TOTAL", 		medida:90, tipo:"moneda", alineacion:"right", datos:"total"},
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
			nombre:"tab", largo:710, alto:280, ajustex:11, ajustey:12, imagenes:"../../img"
		});
		u.btnMovIzq.style.visibility = "hidden";
		u.btnMovDer.style.visibility = "hidden";
		tabs.agregarTabs('Ventas Realizadas Por La Franquicia',1,null);		
		tabs.agregarTabs('Envíos Recibidos por la Franquicia',2,null);		
		tabs.agregarTabs('Ingresos por Servicios en la Franquicia',3,null);
		u.tab_contenedor_id1.disabled = true;
		u.tab_contenedor_id2.disabled = true;
		u.tab_contenedor_id3.disabled = true;
		tabs.seleccionar(0);
		u.sucursal.focus();
	}
	
	function obtenerDetalle(){
		if(u.sucursal_hidden.value==undefined || u.sucursal.value == ""){
			mens.show("A","Debe capturar Franquicia","¡Atención!","sucursal");
			return false;
		}
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
		+"&fechafin="+u.fecha2.value+"&contador="+u.pag1_contador.value+"&sucursal="+u.sucursal_hidden.value
		+"&s="+Math.random());
	}
	
	function mostrarPrincipal(datos){
		//mens.show("I",datos);
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;		
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
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+contador
				+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
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
				consultaTexto("mostrarVentas","consultas.php?accion=2&contador=0&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
				
			case 'RECIBIDO':
				if(!tabla3.creada()){
					tabla3.create();
				}
				consultaTexto("mostrarRecibido","consultas.php?accion=3&contador=0&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
				
			case 'INGRESO':
				if(!tabla4.creada()){
					tabla4.create();
				}
				consultaTexto("mostrarIngreso","consultas.php?accion=4&contador=0&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
		}
	}
	
	function mostrarVentas(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag2_total.value 		= obj.total;
		u.pag2_contador.value 	= obj.contador;
		u.pag2_adelante.value 	= obj.adelante;
		u.pag2_atras.value 		= obj.atras;		
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
				consultaTexto("mostrarVentas","consultas.php?accion=2&contador=0&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("mostrarVentas","consultas.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)+1)
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("mostrarVentas","consultas.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)-1)
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag2_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarVentas","consultas.php?accion=1&contador="+contador
				+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
		}
	}
	
	function mostrarRecibido(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag3_total.value 		= obj.total;
		u.pag3_contador.value 	= obj.contador;
		u.pag3_adelante.value 	= obj.adelante;
		u.pag3_atras.value 		= obj.atras;		
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
				consultaTexto("mostrarRecibido","consultas.php?accion=3&contador=0&fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag3_adelante.value==1){
					consultaTexto("mostrarRecibido","consultas.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)+1)
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag3_atras.value==1){
					consultaTexto("mostrarRecibido","consultas.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)-1)
					+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
					
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag3_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarRecibido","consultas.php?accion=3&contador="+contador
				+"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
				break;
		}
	}
	
	function mostrarIngreso(datos){
		
	}
	
	function limpiar(){
		tabla1.clear();
		if(!tabla2.creada())
			tabla2.clear();
			
		if(!tabla3.creada())
			tabla3.clear();
			
		if(!tabla4.creada())
			tabla4.clear();
			
		u.fecha.value = '<?=date('d/m/Y') ?>';
		u.fecha2.value = '<?=date('d/m/Y') ?>';
		u.sucursal_hidden.value = "";
		u.sucursal.value = "";
		u.pag1_contador.value = 0;
		u.pag2_contador.value = 0;
		u.pag3_contador.value = 0;
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
</script>
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
      <td height="26" colspan="2" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>Franquicia:</td>
          <td colspan="3"><input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:170px" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onblur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}"/>
            &nbsp;&nbsp;&nbsp;<img src="../../img/Buscar_24.gif"  align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('buscarDestinoEvaluacion.php', 600, 500, 'ventana', 'Busqueda')" /> </td>
          <td></td>
          <td><input name="sucursal_hidden" type="hidden" id="sucursal_hidden"/></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="82">Fecha Inicial: </td>
          <td width="118"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>" /></td>
          <td width="100"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
          <td width="82">Fecha Final: </td>
          <td width="111"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y') ?>" /></td>
          <td width="45"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
          <td width="22"><span class="Estilo6 Tablas"><img src="../../img/Boton_Generar.gif" width="74" height="20" style="cursor:pointer" onclick="obtenerDetalle();" align="absbottom"/></span></td>
          <td width="23"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
        </tr>
      </table></td>
      </tr>
    
    <tr>
      <td colspan="2">
        <div style="height:280px; width:700px; overflow:auto">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabla1">
		</table>
	</div>       </td>
    </tr> 
<tr>
      <td colspan="2" align="right"></td>
    </tr>
    <tr>
      <td colspan="2" align="right">&nbsp;</td>
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
		  <td width="503" align="right">
		  		<div class="ebtn_imprimir"></div>		  </td>
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
      <td colspan="2" align="right">&nbsp;</td>
    </tr>
    <tr>
      <td width="503" align="center"><div id="paginado2" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" />
		  <input type="hidden" name="pag2_total" />
          <input type="hidden" name="pag2_contador" value="0" />
          <input type="hidden" name="pag2_adelante" value="" />
          <input type="hidden" name="pag2_atras" value="" />
          </div></td>
		  <td width="503" align="right">
		  		<div class="ebtn_imprimir"></div>		  </td>
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
      <td colspan="2" align="right">&nbsp;</td>
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
		  <td width="503" align="right">
		  		<div class="ebtn_imprimir"></div>		  </td>
    </tr>
  </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id3">

</div>
<table width="600" height="66" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla Estilo4">Reporte de Franquicias o Concesiones</td>
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
