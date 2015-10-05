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
<script src="../../javascript/moautocomplete.js"></script>
<script src="../../javascript/ClaseTabsDivs.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/funciones.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
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
	var tabla6 	= new ClaseTabla();
	var tabla7 	= new ClaseTabla();
	var tabla8 	= new ClaseTabla();	
	var tabla9 	= new ClaseTabla();	
	var tabla10	= new ClaseTabla();	
	var tabla11	= new ClaseTabla();
	var tabla12	= new ClaseTabla();	
	var tabla13	= new ClaseTabla();	
	//para paginado
	var pag1_cantidadporpagina = 30;
	var v_suc		= "<?=$_SESSION[IDSUCURSAL] ?>";
	mens.iniciar('../../javascript');
	
	tabla1.setAttributes({
		nombre:"detalle0",
		campos:[						
			{nombre:"SUCURSAL", medida:120, alineacion:"left", datos:"sucursal"},
			{nombre:"CONVENIOS_VIGENTES", medida:130, onDblClick:"obtenerVigentes1", alineacion:"center",  datos:"vigentes"},
			{nombre:"CONVENIOS_VENCIDOS", medida:130, onDblClick:"obtenerVencidos1", alineacion:"center",  datos:"vencidos"},
			{nombre:"TOTALES_CONVENIOS", medida:130, onDblClick:"obtenerTotalConvenios1", alineacion:"center", datos:"total"},			
			{nombre:"IMPORTE", medida:130,  tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"IDSUCURSAL", medida:4,  tipo:"oculto", alineacion:"right", datos:"idsucursal"}
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
			{nombre:"SUCURSAL", medida:165, alineacion:"left", datos:"prefijosucursal"},
			{nombre:"FACTURADO", medida:165, tipo:"moneda", alineacion:"right", datos:"facturado"},
			{nombre:"NO FACTURADO", medida:165, tipo:"moneda", alineacion:"right",  datos:"nofacturado"},
			{nombre:"TOTALES", medida:165, tipo:"moneda", alineacion:"right", datos:"totales"},
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"left", datos:"idsucursal"}
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
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucursal"},
			{nombre:"SUCURSAL", medida:50, alineacion:"center", datos:"prefijosucursal"},
			{nombre:"NORMALES", medida:120, tipo:"moneda", alineacion:"right",  datos:"normales"},
			{nombre:"PREPAGADAS", medida:120, tipo:"moneda", alineacion:"right",  datos:"prepagadas"},
			{nombre:"CONSIGNACION", medida:120, tipo:"moneda", alineacion:"right", datos:"consignacion"},
			{nombre:"OTROS", medida:120, tipo:"moneda", alineacion:"right", datos:"otros"},
			{nombre:"TOTAL", medida:130, tipo:"moneda", onDblClick:"obtenerTotalFacturado", alineacion:"right", datos:"total"}
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
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucorigen"},
			{nombre:"SUCURSAL", medida:50, alineacion:"center", datos:"prefijosucursal"},
			{nombre:"# CLIENTE", medida:50, alineacion:"center",datos:"idcliente"},
			{nombre:"CLIENTE", medida:130, alineacion:"center",datos:"cliente"},
			{nombre:"TIPO CONVENIO", medida:70, alineacion:"center",  datos:"tipoconvenio"},
			{nombre:"NORMALES", medida:70, tipo:"moneda", alineacion:"right", datos:"normales"},
			{nombre:"PREPAGADAS", medida:70, tipo:"moneda", alineacion:"right", datos:"prepagadas"},
			{nombre:"CONSIGNACION", medida:70, tipo:"moneda", alineacion:"right", datos:"consignacion"},
			{nombre:"OTROS", medida:70, tipo:"moneda", alineacion:"right", datos:"otros"},
			{nombre:"TOTAL", medida:70, tipo:"moneda", onDblClick:"obtenerTotalTabla4", alineacion:"right", datos:"total"}
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
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucursal"},
			{nombre:"FECHA", medida:80, alineacion:"center", datos:"fecha"},
			{nombre:"GUIA", medida:120, alineacion:"center",  datos:"guia"},
			{nombre:"REM/DEST", medida:170, alineacion:"left",  datos:"destinatario"},
			{nombre:"DESTINO", medida:90, alineacion:"center", datos:"prefijodestino"},
			{nombre:"IMPORTE", medida:100, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"FACTURA", medida:80, alineacion:"center", datos:"factura"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla5"
	});
	
	tabla6.setAttributes({//NO FACTURADO
		nombre:"detalle5",
		campos:[
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucursal"},
			{nombre:"SUCURSAL", medida:120, alineacion:"center", datos:"prefijosucursal"},
			{nombre:"NORMALES", medida:135, tipo:"moneda", alineacion:"right", datos:"normales"},
			{nombre:"PREPAGADAS", medida:135, tipo:"moneda", alineacion:"right", datos:"prepagadas"},
			{nombre:"CONSIGNACION", medida:135, tipo:"moneda", alineacion:"right", datos:"consignacion"},
			{nombre:"TOTAL", medida:135, tipo:"moneda", onDblClick:"obtenerTotalFacturadoSucursal", alineacion:"right", datos:"total"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla6"
	});
	
	tabla7.setAttributes({//PREPAGADAS SIN FACTURAR
		nombre:"detalle6",
		campos:[
			{nombre:"SUCURSAL", medida:50, alineacion:"center", datos:"prefijosucursal"},
			{nombre:"# CLIENTE", medida:30, alineacion:"center", datos:"idcliente"},
			{nombre:"CLIENTE", medida:110, alineacion:"center", datos:"cliente"},
			{nombre:"# VENTA", medida:40, alineacion:"center", datos:"nventa"},
			{nombre:"Q GUIAS", medida:30, alineacion:"center", datos:"cantidad"},
			{nombre:"GUIAS", medida:100, alineacion:"center", datos:"pfolios"},
			{nombre:"FECHA", medida:50, alineacion:"center", datos:"fecha"},
			{nombre:"FLETE", medida:50, tipo:"moneda", alineacion:"center", datos:"flete"},
			{nombre:"SOBREPESO", medida:50, tipo:"moneda", alineacion:"center", datos:"sobrepeso"},
			{nombre:"COSTO SEGURO", medida:50, tipo:"moneda", alineacion:"center", datos:"costoseguro"},
			{nombre:"EAD", medida:50, tipo:"moneda", alineacion:"center", datos:"costoead"},
			{nombre:"TOTAL", medida:60, tipo:"moneda", alineacion:"center", datos:"total"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla7"
	});	
	
	tabla8.setAttributes({//CONSIGNACION SIN FACTURAR
		nombre:"detalle7",
		campos:[
			{nombre:"SUCURSAL", medida:50, alineacion:"center", datos:"prefijosucursal"},
			{nombre:"# CLIENTE", medida:60, alineacion:"center",datos:"idcliente"},
			{nombre:"CLIENTE", medida:140, alineacion:"center",datos:"cliente"},
			{nombre:"FLETE", tipo:"moneda", medida:90, alineacion:"right",  datos:"porfacturar"},
			{nombre:"SOBREPESO", medida:60, tipo:"moneda", alineacion:"right", datos:"sobrepeso"},
			{nombre:"VALOR DECLARADO", medida:100, tipo:"moneda", alineacion:"right", datos:"valordeclarado"},
			{nombre:"SUBDESTINOS", medida:70, tipo:"moneda", alineacion:"right", datos:"costoead"},
			{nombre:"IMPORTE POR FACTURAR", medida:85, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla8"
	});
	
	tabla9.setAttributes({//VENTAS SIN CONVENIO SIN FACTURAR
		nombre:"detalle9",
		campos:[
			{nombre:"IDSUCURSAL", medida:5, tipo:"oculto", alineacion:"center", datos:"idsucursal"},
			{nombre:"SUCURSAL", medida:50, alineacion:"center", datos:"prefijosucursal"},
			{nombre:"# CLIENTE", medida:50, alineacion:"center",datos:"idcliente"},
			{nombre:"CLIENTE", medida:130, alineacion:"center",datos:"cliente"},
			{nombre:"TIPO CONVENIO", medida:70, alineacion:"center",  datos:"tipoconvenio"},
			{nombre:"NORMALES", medida:90, tipo:"moneda", alineacion:"right", datos:"normales"},
			{nombre:"PREPAGADAS", medida:90, tipo:"moneda", alineacion:"right", datos:"prepagadas"},
			{nombre:"CONSIGNACION", medida:90, tipo:"moneda", alineacion:"right", datos:"consignacion"},
			{nombre:"TOTAL", medida:90, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla9"
	});
	
	tabla10.setAttributes({//CONVENIOS VIGENTES
		nombre:"detalle10",
		campos:[
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucursal"},
			{nombre:"SUCURSAL", medida:80, alineacion:"center", datos:"prefijosucursal"},
			{nombre:"# CLIENTE", medida:50, onDblClick:"obtenerHistorialCliente10", alineacion:"left",  datos:"idcliente"},
			{nombre:"CLIENTE", medida:250, alineacion:"left", datos:"cliente"},
			{nombre:"TIPO CONVENIO", medida:100, alineacion:"center", datos:"tipo"},	
			{nombre:"PRECIO", medida:80, alineacion:"center", onDblClick:"mostrarDesgloze10",  datos:"precio"},
			{nombre:"VENCIMIENTO", medida:80, alineacion:"center",  datos:"vencimiento"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla10"
	});
	
	tabla11.setAttributes({//CONVENIOS VENCIDOS
		nombre:"detalle11",
		campos:[
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucursal"},
			{nombre:"SUCURSAL", medida:80, alineacion:"center", datos:"sucursal"},
			{nombre:"# CLIENTE", medida:50, onDblClick:"obtenerHistorialCliente11", alineacion:"left",  datos:"idcliente"},
			{nombre:"CLIENTE", medida:250, alineacion:"left", datos:"cliente"},
			{nombre:"TIPO CONVENIO", medida:100, alineacion:"center", datos:"tipo"},	
			{nombre:"PRECIO", medida:80, alineacion:"center", onDblClick:"mostrarDesgloze11",  datos:"precio"},
			{nombre:"VENCIMIENTO", medida:80, alineacion:"center",  datos:"vencimiento"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla11"
	});
	
	tabla12.setAttributes({//TIPO CONVENIOS
		nombre:"detalle12",
		campos:[
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"idsucursal"},
			{nombre:"SUCURSAL", medida:80, alineacion:"center", datos:"prefijosucursal"},
			{nombre:"# CLIENTE", medida:50, alineacion:"left",  datos:"idcliente"},
			{nombre:"CLIENTE", medida:250, alineacion:"left", datos:"cliente"},
			{nombre:"TIPO CONVENIO", medida:100, alineacion:"center", datos:"tipo"},	
			{nombre:"PRECIO", medida:80, alineacion:"center", onDblClick:"mostrarDesgloze12", datos:"precio"},
			{nombre:"STATUS", medida:80, alineacion:"center",  datos:"estatus"},
			{nombre:"VENCIMIENTO", medida:80, alineacion:"center",  datos:"vencimiento"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla12"
	});
	
	tabla13.setAttributes({
		nombre:"detalle13",
		campos:[
			{nombre:"F. ALTA", medida:80, alineacion:"center", datos:"fechaalta"},
			{nombre:"F. RENOVACION", medida:80, alineacion:"center",datos:"fechamodificacion"},
			{nombre:"F. VENCIMIENTO", medida:80, alineacion:"center",datos:"fechavencimiento"},
			{nombre:"ESTADO CREDITO", medida:100, alineacion:"center",  datos:"estadocredito"},
			{nombre:"LIMITE CREDITO", medida:100, tipo:"moneda", alineacion:"right", datos:"limitecredito"},
			{nombre:"TIPO CONVENIO", medida:120, alineacion:"center", datos:"tipoconvenio"},
			{nombre:"VALOR CONVENIO", medida:100, alineacion:"center", onDblClick:"mostrarDesgloze13", datos:"valorconvenio"},
			{nombre:"PESO MAXIMO", medida:80, alineacion:"center", datos:"pesomaximo"},
			{nombre:"PRECIO SOBREPESO", medida:100, alineacion:"center", datos:"preciosobrepeso"},
			{nombre:"IDCLIENTE", medida:4, tipo:"oculto", alineacion:"center", datos:"idcliente"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla13"
	});	
	
	window.onload = function(){
		tabla1.create();
		tabs.iniciar({
			nombre:"tab", largo:710, alto:280, ajustex:11,ajustey:12, imagenes:"../../img"
		});
		tabs.agregarTabs('Ventas por convenios',1,null);		
		tabs.agregarTabs('Ventas convenio facturadas sucursal',2,null);			
		tabs.agregarTabs('Ventas convenio facturadas clientes',3,null);		
		tabs.agregarTabs('Relación envios facturados cliente',4,null);		
		tabs.agregarTabs('Ventas convenio sin facturar',5,null);
		tabs.agregarTabs('Prepagadas sin facturar',6,null);			
		tabs.agregarTabs('Consignacion sin facturar',7,null);
		tabs.agregarTabs('No facturado',8,null);
		tabs.agregarTabs('Convenios vigentes',9,null);
		tabs.agregarTabs('Convenios vencidos',10,null);
		tabs.agregarTabs('Tipos de convenios',11,null);
		tabs.agregarTabs('Historial de cliente',12,null);
		u.tab_contenedor_id1.disabled = true;	
		u.tab_contenedor_id2.disabled = true;
		u.tab_contenedor_id3.disabled = true;
		u.tab_contenedor_id4.disabled = true;
		u.tab_contenedor_id5.disabled = true;
		u.tab_contenedor_id6.disabled = true;
		u.tab_contenedor_id7.disabled = true;
		u.tab_contenedor_id8.disabled = true;
		u.tab_contenedor_id9.disabled = true;
		u.tab_contenedor_id10.disabled = true;
		u.tab_contenedor_id11.disabled = true;
		u.tab_contenedor_id12.disabled = true;
		tabs.seleccionar(0);
	}
	
	function obtenerDetalle(){
		if(u.sucursal.value == ""){
			alerta("Debe capturar Sucursal","¡Atención!","sucursal");
		}else{
			if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no"){
				u.sucursal_hidden.value = v_suc;
			}
			consultaTexto("resTabla1","consultas.php?accion=1&sucursal="+u.sucursal_hidden.value
			+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
			+"&contador="+u.pag1_contador.value+"&s="+Math.random());	
		}
	}
	
	function resTabla1(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		u.vigentes.value		= obj.totales.vigentes;
		u.vencidos.value		= obj.totales.vencidos;
		u.totalcon.value		= obj.totales.total;
		u.importes.value		= "$ "+obj.totales.importe;
		if(obj.registros.length==0){
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla1.clear();
		}else{			
			tabla1.setJsonData(obj.registros);
		}
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","consultas.php?accion=1&contador=0&sucursal="+u.sucursal_hidden.value				
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)
					+"&sucursal="+u.sucursal_hidden.value
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)
					+"&sucursal="+u.sucursal_hidden.value
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","consultas.php?accion=1&contador="+contador
				+"&sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	function obtenerImporte(){		
		if(!tabla2.creada()){
			tabla2.create();
		}		
		consultaTexto("resTabla2","consultas.php?accion=2&sucursal="+u.sucursal_hidden.value
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag1_contador.value+"&s="+Math.random());
	}
	
	function resTabla2(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag2_total.value 		= obj.total;
		u.pag2_contador.value 	= obj.contador;
		u.pag2_adelante.value 	= obj.adelante;
		u.pag2_atras.value 		= obj.atras;
		u.facturados.value		= "$ "+obj.totales.facturado;
		u.nofacturados.value	= "$ "+obj.totales.nofacturado;
		u.totalesfact.value		= "$ "+obj.totales.total;
		if(obj.registros.length>0){
			tabla2.setJsonData(obj.registros);
		}
		u.tab_contenedor_id1.disabled=false;
		tabs.seleccionar(1);
		
		if(obj.paginado==1){
			document.getElementById('paginado1').style.visibility = 'visible';
		}else{
			document.getElementById('paginado1').style.visibility = 'hidden';
		}
	}
	
	function paginacion1(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla2","consultas.php?accion=2&contador=0&sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("resTabla2","consultas.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)+1)
					+"&sucursal="+u.sucursal_hidden.value
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("resTabla2","consultas.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)-1)
					+"&sucursal="+u.sucursal_hidden.value
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag2_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla2","consultas.php?accion=2&contador="+contador
				+"&sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerFacturado(){
		if(!tabla3.creada()){
			tabla3.create();
		}
		consultaTexto("resTabla3","consultas.php?accion=3&sucursal="+u.sucursal_hidden.value
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag3_contador.value+"&s="+Math.random());
	}
	
	function resTabla3(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag3_total.value 		= obj.total;
		u.pag3_contador.value 	= obj.contador;
		u.pag3_adelante.value 	= obj.adelante;
		u.pag3_atras.value 		= obj.atras;		
		u.normales1.value 		= "$ "+obj.totales.normales;
		u.prepagadas1.value 	= "$ "+obj.totales.prepagadas;
		u.consignacion1.value 	= "$ "+obj.totales.consignacion;
		u.otros1.value		 	= "$ "+obj.totales.otros;
		u.totales1.value		= "$ "+obj.totales.total;
		if(obj.registros.length>0){
			tabla3.setJsonData(obj.registros);
		}
		u.tab_contenedor_id2.disabled=false;
		tabs.seleccionar(2);
		
		if(obj.paginado==1){
			document.getElementById('paginado2').style.visibility = 'visible';
		}else{
			document.getElementById('paginado2').style.visibility = 'hidden';
		}
	}
	
	function paginacion2(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla3","consultas.php?accion=3&contador=0&s="+Math.random()
				+"&sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text);
				break;
			case 'adelante':
				if(u.pag3_adelante.value==1){
					consultaTexto("resTabla3","consultas.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)+1)
					+"&sucursal="+u.sucursal_hidden.value
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag3_atras.value==1){
					consultaTexto("resTabla3","consultas.php?accion=3&contador="+(parseFloat(u.pag3_contador.value)-1)
					+"&sucursal="+u.sucursal_hidden.value
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag3_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla3","consultas.php?accion=3&contador="+contador
				+"&sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	function obtenerTotalFacturado(){
		var obj = tabla3.getSelectedRow();
		v_suc1 = obj.idsucursal;
		if(!tabla4.creada()){
			tabla4.create();
		}
		consultaTexto("resTabla4","consultas.php?accion=4&sucursal="+obj.idsucursal
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag4_contador.value+"&s="+Math.random());
	}
		
	function resTabla4(datos){
		//mens.show("A",datos,"");
		var obj = eval(convertirValoresJson(datos));
		u.pag4_total.value 		= obj.total;
		u.pag4_contador.value 	= obj.contador;
		u.pag4_adelante.value 	= obj.adelante;
		u.pag4_atras.value 		= obj.atras;		
		u.normales2.value 		= "$ "+obj.totales.normales;
		u.prepagadas2.value 	= "$ "+obj.totales.prepagadas;
		u.consignacion2.value 	= "$ "+obj.totales.consignacion;
		u.otros2.value		 	= "$ "+obj.totales.otros;
		u.totales2.value		= "$ "+obj.totales.total;
		if(obj.registros.length>0){
			tabla4.setJsonData(obj.registros);
		}
		u.tab_contenedor_id3.disabled=false;
		tabs.seleccionar(3);
		
		if(obj.paginado==1){
			document.getElementById('paginado3').style.visibility = 'visible';
		}else{
			document.getElementById('paginado3').style.visibility = 'hidden';
		}
	}
	
	function paginacion3(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla4","consultas.php?accion=4&contador=0&s="+Math.random()
				+"&sucursal="+v_suc1
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text);
				break;
			case 'adelante':
				if(u.pag4_adelante.value==1){
					consultaTexto("resTabla4","consultas.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)+1)
					+"&sucursal="+v_suc1
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag4_atras.value==1){
					consultaTexto("resTabla4","consultas.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)-1)
					+"&sucursal="+v_suc1
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag4_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla4","consultas.php?accion=4&contador="+contador
				+"&sucursal="+v_suc1
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerTotalTabla4(){
		var obj = tabla4.getSelectedRow();
		v_cliente = obj.idcliente;
		if(!tabla5.creada()){
			tabla5.create();
		}
		consultaTexto("resTabla5","consultas.php?accion=5&sucursal="+obj.idsucorigen
		+"&cliente="+v_cliente
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag5_contador.value+"&s="+Math.random());
	}
	
	function resTabla5(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag5_total.value 		= obj.total;
		u.pag5_contador.value 	= obj.contador;
		u.pag5_adelante.value 	= obj.adelante;
		u.pag5_atras.value 		= obj.atras;		
		u.totalgeneral.value 	= "$ "+obj.totales.importe;
		u.cliente.value			= v_cliente;
		u.nombre.value			= obj.cliente;
		u.sucursal2.value		= obj.sucursal;
		if(obj.registros.length>0){
			tabla5.setJsonData(obj.registros);
		}
		u.tab_contenedor_id4.disabled=false;
		tabs.seleccionar(4);
		
		if(obj.paginado==1){
			document.getElementById('paginado4').style.visibility = 'visible';
		}else{
			document.getElementById('paginado4').style.visibility = 'hidden';
		}
	}
	
	function paginacion4(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla5","consultas.php?accion=5&contador=0&s="+Math.random()
				+"&cliente="+v_cliente
				+"&sucursal="+v_suc1
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text);
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("resTabla5","consultas.php?accion=5&contador="+(parseFloat(u.pag5_contador.value)+1)
					+"&cliente="+v_cliente
					+"&sucursal="+v_suc1
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){
					consultaTexto("resTabla5","consultas.php?accion=5&contador="+(parseFloat(u.pag5_contador.value)-1)
					+"&cliente="+v_cliente
					+"&sucursal="+v_suc1
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla5","consultas.php?accion=5&contador="+contador
				+"&cliente="+v_cliente
				+"&sucursal="+v_suc1
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerNFacturado(){
		if(!tabla6.creada()){
			tabla6.create();
		}
		consultaTexto("resTabla6","consultas.php?accion=6&sucursal="+u.sucursal_hidden.value
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag6_contador.value+"&s="+Math.random());
	}
	
	function resTabla6(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag6_total.value 		= obj.total;
		u.pag6_contador.value 	= obj.contador;
		u.pag6_adelante.value 	= obj.adelante;
		u.pag6_atras.value 		= obj.atras;		
		u.normales6.value	 	= "$ "+obj.totales.normales;
		u.prepagadas6.value	 	= "$ "+obj.totales.prepagadas;
		u.consignacion6.value 	= "$ "+obj.totales.consignacion;
		u.totales6.value	 	= "$ "+obj.totales.total;
		
		if(obj.registros.length>0){
			tabla6.setJsonData(obj.registros);
		}
		u.tab_contenedor_id5.disabled=false;
		tabs.seleccionar(5);
		tabs.moverManual(-100);
		if(obj.paginado==1){
			document.getElementById('paginado5').style.visibility = 'visible';
		}else{
			document.getElementById('paginado5').style.visibility = 'hidden';
		}
	}
	
	function paginacion5(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla6","consultas.php?accion=6&contador=0&s="+Math.random()
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				+"&sucursal="+u.sucursal_hidden.value);
				break;
			case 'adelante':
				if(u.pag6_adelante.value==1){
					consultaTexto("resTabla6","consultas.php?accion=6&contador="+(parseFloat(u.pag6_contador.value)+1)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&sucursal="+u.sucursal_hidden.value
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag6_atras.value==1){
					consultaTexto("resTabla6","consultas.php?accion=6&contador="+(parseFloat(u.pag6_contador.value)-1)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&sucursal="+u.sucursal_hidden.value
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag6_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla6","consultas.php?accion=6&contador="+contador
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&sucursal="+u.sucursal_hidden.value
				+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerPrepagadas(){
		if(!tabla7.creada()){
			tabla7.create();
		}
		consultaTexto("resTabla7","consultas.php?accion=7&sucursal="+u.sucursal_hidden.value
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag7_contador.value+"&s="+Math.random());
	}
	
	function resTabla7(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag7_total.value 		= obj.total;
		u.pag7_contador.value 	= obj.contador;
		u.pag7_adelante.value 	= obj.adelante;
		u.pag7_atras.value 		= obj.atras;
		u.totales7.value	 	= "$ "+obj.totales.total;
		
		if(obj.registros.length>0){
			tabla7.setJsonData(obj.registros);
		}
		u.tab_contenedor_id6.disabled = false;
		tabs.seleccionar(6);
		tabs.moverManual(-200);
		if(obj.paginado==1){
			document.getElementById('paginado6').style.visibility = 'visible';
		}else{
			document.getElementById('paginado6').style.visibility = 'hidden';
		}
	}
	
	function paginacion6(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla7","consultas.php?accion=7&contador=0&s="+Math.random()
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				+"&sucursal="+u.sucursal_hidden.value);
				break;
			case 'adelante':
				if(u.pag7_adelante.value==1){
					consultaTexto("resTabla7","consultas.php?accion=7&contador="+(parseFloat(u.pag7_contador.value)+1)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&sucursal="+u.sucursal_hidden.value
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag7_atras.value==1){
					consultaTexto("resTabla7","consultas.php?accion=7&contador="+(parseFloat(u.pag7_contador.value)-1)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&sucursal="+u.sucursal_hidden.value
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag7_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla7","consultas.php?accion=7&contador="+contador
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&sucursal="+u.sucursal_hidden.value
				+"&s="+Math.random());
				break;
		}
	}
	
    function obtenerConsignacion(){
		if(!tabla8.creada()){
			tabla8.create();
		}
		consultaTexto("resTabla8","consultas.php?accion=8&sucursal="+u.sucursal_hidden.value
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag8_contador.value+"&s="+Math.random());
	}
	
	function resTabla8(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag8_total.value 		= obj.total;
		u.pag8_contador.value 	= obj.contador;
		u.pag8_adelante.value 	= obj.adelante;
		u.pag8_atras.value 		= obj.atras;
		u.totales8.value	 	= "$ "+obj.totales.total;
		
		if(obj.registros.length>0){
			tabla8.setJsonData(obj.registros);
		}
		u.tab_contenedor_id7.disabled = false;
		tabs.seleccionar(7);
		tabs.moverManual(-305);
		if(obj.paginado==1){
			document.getElementById('paginado7').style.visibility = 'visible';
		}else{
			document.getElementById('paginado7').style.visibility = 'hidden';
		}
	}
	
	function paginacion7(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla8","consultas.php?accion=8&contador=0&s="+Math.random()				
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				+"&sucursal="+u.sucursal_hidden.value);
				break;
			case 'adelante':
				if(u.pag8_adelante.value==1){
					consultaTexto("resTabla8","consultas.php?accion=8&contador="+(parseFloat(u.pag8_contador.value)+1)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&sucursal="+u.sucursal_hidden.value
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag8_atras.value==1){
					consultaTexto("resTabla8","consultas.php?accion=8&contador="+(parseFloat(u.pag8_contador.value)-1)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&sucursal="+u.sucursal_hidden.value
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag8_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla8","consultas.php?accion=8&contador="+contador
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				+"&sucursal="+u.sucursal_hidden.value
				+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerTotalFacturadoSucursal(){
		var obj = tabla6.getSelectedRow();
		v_suc2 = obj.idsucursal;
		if(!tabla9.creada()){
			tabla9.create();
		}
		consultaTexto("resTabla9","consultas.php?accion=9&sucursal="+obj.idsucursal
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag9_contador.value+"&s="+Math.random());
	}
	
	function resTabla9(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag9_total.value 		= obj.total;
		u.pag9_contador.value 	= obj.contador;
		u.pag9_adelante.value 	= obj.adelante;
		u.pag9_atras.value 		= obj.atras;		
		u.normales9.value 		= "$ "+obj.totales.normales;
		u.prepagadas9.value 	= "$ "+obj.totales.prepagadas;
		u.consignacion9.value 	= "$ "+obj.totales.consignacion;
		u.totales9.value		= "$ "+obj.totales.total;
		if(obj.registros.length>0){
			tabla9.setJsonData(obj.registros);
		}
		u.tab_contenedor_id8.disabled=false;
		tabs.seleccionar(8);
		tabs.moverManual(-410);
		if(obj.paginado==1){
			document.getElementById('paginado9').style.visibility = 'visible';
		}else{
			document.getElementById('paginado9').style.visibility = 'hidden';
		}
	}
	
	function paginacion9(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla9","consultas.php?accion=9&contador=0&s="+Math.random()
				+"&sucursal="+v_suc2
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text);
				break;
			case 'adelante':
				if(u.pag9_adelante.value==1){
					consultaTexto("resTabla9","consultas.php?accion=9&contador="+(parseFloat(u.pag9_contador.value)+1)
					+"&sucursal="+v_suc2
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag9_atras.value==1){
					consultaTexto("resTabla9","consultas.php?accion=9&contador="+(parseFloat(u.pag9_contador.value)-1)
					+"&sucursal="+v_suc2
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag9_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla9","consultas.php?accion=9&contador="+contador
				+"&sucursal="+v_suc2
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerVigentes1(){
		v_suc3 = "";
		var obj = tabla1.getSelectedRow();
		v_suc3 = obj.idsucursal;
	
		if(!tabla10.creada()){
			tabla10.create();
		}
		consultaTexto("resTabla10","consultas.php?accion=10&sucursal="+obj.idsucursal
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag10_contador.value+"&s="+Math.random());
	}
	
	function obtenerVigentes(){
		v_suc3 = "";		
		if(!tabla10.creada()){
			tabla10.create();
		}
		consultaTexto("resTabla10","consultas.php?accion=10"
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag10_contador.value+"&s="+Math.random());
	}
	
	function resTabla10(datos){
		var obj = eval(convertirValoresJson(datos));		
		u.pag10_total.value 	= obj.total;
		u.pag10_contador.value 	= obj.contador;
		u.pag10_adelante.value 	= obj.adelante;
		u.pag10_atras.value 	= obj.atras;
		u.totales10.value		= obj.totales.total;
		
		if(obj.registros.length>0){			
			tabla10.setJsonData(obj.registros);
		}
		
		u.tab_contenedor_id9.disabled=false;
		tabs.seleccionar(9);
		tabs.moverManual(-550);
		if(obj.paginado==1){
			document.getElementById('paginado10').style.visibility = 'visible';
		}else{
			document.getElementById('paginado10').style.visibility = 'hidden';
		}
	}
	
	function paginacion10(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla10","consultas.php?accion=10&contador=0&s="+Math.random()
				+"&sucursal="+((v_suc3!="")?v_suc3:u.sucursal_hidden.value)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text);
				break;
			case 'adelante':
				if(u.pag10_adelante.value==1){
					consultaTexto("resTabla10","consultas.php?accion=10&contador="+(parseFloat(u.pag10_contador.value)+1)
					+"&sucursal="+((v_suc3!="")?v_suc3:u.sucursal_hidden.value)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag10_atras.value==1){
					consultaTexto("resTabla10","consultas.php?accion=10&contador="+(parseFloat(u.pag10_contador.value)-1)
					+"&sucursal="+((v_suc3!="")?v_suc3:u.sucursal_hidden.value)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':

				var contador = Math.floor((parseFloat(u.pag10_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				
				consultaTexto("resTabla10","consultas.php?accion=10&contador="+contador
				+"&sucursal="+((v_suc3!="")?v_suc3:u.sucursal_hidden.value)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
    function obtenerVencidos1(){
		v_suc4 = "";
		var obj = tabla1.getSelectedRow();
		v_suc4 = obj.idsucursal;
		
		if(!tabla11.creada()){
			tabla11.create();
		}
		consultaTexto("resTabla11","consultas.php?accion=11&sucursal="+obj.idsucursal
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag11_contador.value+"&s="+Math.random());
	}
	
	function obtenerVencidos(){
		v_suc4 = "";		
		if(!tabla11.creada()){
			tabla11.create();
		}
		consultaTexto("resTabla11","consultas.php?accion=11"
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag11_contador.value+"&s="+Math.random());
	}
	
	function resTabla11(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag11_total.value 	= obj.total;
		u.pag11_contador.value 	= obj.contador;
		u.pag11_adelante.value 	= obj.adelante;
		u.pag11_atras.value 	= obj.atras;		
		u.totales11.value		= obj.totales.total;
		if(obj.registros.length>0){
			tabla11.setJsonData(obj.registros);
		}
		u.tab_contenedor_id10.disabled=false;
		tabs.seleccionar(10);
		tabs.moverManual(-670);
		if(obj.paginado==1){
			document.getElementById('paginado11').style.visibility = 'visible';
		}else{
			document.getElementById('paginado11').style.visibility = 'hidden';
		}
	}
	
	function paginacion11(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla11","consultas.php?accion=11&contador=0&s="+Math.random()
				+"&sucursal="+((v_suc4!="")?v_suc4:u.sucursal_hidden.value)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				);
				break;
			case 'adelante':
				if(u.pag11_adelante.value==1){
					consultaTexto("resTabla11","consultas.php?accion=11&contador="+(parseFloat(u.pag11_contador.value)+1)
					+"&sucursal="+((v_suc4!="")?v_suc4:u.sucursal_hidden.value)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag11_atras.value==1){
					consultaTexto("resTabla11","consultas.php?accion=11&contador="+(parseFloat(u.pag11_contador.value)-1)
					+"&sucursal="+((v_suc4!="")?v_suc4:u.sucursal_hidden.value)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag11_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla11","consultas.php?accion=11&contador="+contador
				+"&sucursal="+((v_suc4!="")?v_suc4:u.sucursal_hidden.value)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
    function obtenerTotalConvenios1(){
		v_suc5 = "";
		var obj = tabla1.getSelectedRow();
		v_suc5 = obj.idsucursal;
		
		if(!tabla12.creada()){
			tabla12.create();
		}
		consultaTexto("resTabla12","consultas.php?accion=12&sucursal="+((v_suc5!="")?v_suc5:u.sucursal_hidden.value)
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag12_contador.value+"&s="+Math.random());
	}
	
	function obtenerTotalConvenios(){
		v_suc5 = "";		
		if(!tabla12.creada()){
			tabla12.create();
		}
		consultaTexto("resTabla12","consultas.php?accion=12"
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag12_contador.value+"&s="+Math.random());
	}
	function resTabla12(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag12_total.value 	= obj.total;
		u.pag12_contador.value 	= obj.contador;
		u.pag12_adelante.value 	= obj.adelante;
		u.pag12_atras.value 	= obj.atras;		
		u.totales12.value		= obj.totales.total;
		if(obj.registros.length>0){
			tabla12.setJsonData(obj.registros);
		}
		u.tab_contenedor_id11.disabled=false;
		tabs.seleccionar(11);
		tabs.moverManual(-790);
		
		if(obj.paginado==1){
			document.getElementById('paginado12').style.visibility = 'visible';
		}else{
			document.getElementById('paginado12').style.visibility = 'hidden';
		}
	}
	
	function paginacion12(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla12","consultas.php?accion=12&contador=0&s="+Math.random()
				+"&sucursal="+((v_suc5!="")?v_suc5:u.sucursal_hidden.value)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				);
				break;
			case 'adelante':
				if(u.pag12_adelante.value==1){
					consultaTexto("resTabla12","consultas.php?accion=12&contador="+(parseFloat(u.pag12_contador.value)+1)
					+"&sucursal="+((v_suc5!="")?v_suc5:u.sucursal_hidden.value)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag12_atras.value==1){
					consultaTexto("resTabla12","consultas.php?accion=12&contador="+(parseFloat(u.pag12_contador.value)-1)
					+"&sucursal="+((v_suc5!="")?v_suc5:u.sucursal_hidden.value)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag12_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla12","consultas.php?accion=12&contador="+contador
				+"&sucursal="+((v_suc5!="")?v_suc5:u.sucursal_hidden.value)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerHistorialCliente10(){
		var obj = tabla10.getSelectedRow();
		v_clien10 = obj.idcliente;
		v_clien11 = "";
		if(!tabla13.creada()){
			tabla13.create();
		}
		consultaTexto("resTabla13","consultas.php?accion=13&cliente="+obj.idcliente
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag13_contador.value+"&s="+Math.random());	
	}
	
	function obtenerHistorialCliente11(){
		var obj = tabla11.getSelectedRow();
		v_clien11 = obj.idcliente;
		v_clien10 = "";
		if(!tabla13.creada()){
			tabla13.create();
		}
		consultaTexto("resTabla13","consultas.php?accion=13&cliente="+obj.idcliente
		+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
		+"&contador="+u.pag13_contador.value+"&s="+Math.random());
	}
	
	function resTabla13(datos){	
		var obj = eval(convertirValoresJson(datos));
		u.pag13_total.value 	= obj.total;
		u.pag13_contador.value 	= obj.contador;
		u.pag13_adelante.value 	= obj.adelante;
		u.pag13_atras.value 	= obj.atras;
		if(obj.registros.length>0){
			tabla13.setJsonData(obj.registros);
		}
		u.tab_contenedor_id12.disabled=false;
		tabs.seleccionar(12);
		tabs.moverManual(-910);
		
		if(obj.paginado==1){
			document.getElementById('paginado13').style.visibility = 'visible';
		}else{
			document.getElementById('paginado13').style.visibility = 'hidden';
		}
	}
	
	function paginacion13(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla13","consultas.php?accion=13&contador=0&s="+Math.random()
				+"&cliente="+((v_clien10!="")?v_clien10:v_clien11)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				);
				break;
			case 'adelante':
				if(u.pag13_adelante.value==1){
					consultaTexto("resTabla13","consultas.php?accion=13&contador="+(parseFloat(u.pag13_contador.value)+1)
					+"&cliente="+((v_clien10!="")?v_clien10:v_clien11)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag13_atras.value==1){
					consultaTexto("resTabla13","consultas.php?accion=13&contador="+(parseFloat(u.pag13_contador.value)-1)
					+"&cliente="+((v_clien10!="")?v_clien10:v_clien11)
					+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag13_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla13","consultas.php?accion=13&contador="+contador
				+"&cliente="+((v_clien10!="")?v_clien10:v_clien11)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text				
				+"&s="+Math.random());
				break;
		}
	}
	
	function mostrarDesgloze10(){
		var obj = tabla10.getSelectedRow();
		abrirVentanaFija('informacionextra.php?cliente='+obj.idcliente, 625, 418, 'ventana', 'Detalle Convenio')
	}
	
	function mostrarDesgloze11(){
		var obj = tabla11.getSelectedRow();
		abrirVentanaFija('informacionextra.php?cliente='+obj.idcliente, 625, 418, 'ventana', 'Detalle Convenio')
	}
	function mostrarDesgloze12(){
		var obj = tabla12.getSelectedRow();
		abrirVentanaFija('informacionextra.php?cliente='+obj.idcliente, 625, 418, 'ventana', 'Detalle Convenio')
	}
	function mostrarDesgloze13(){
		var obj = tabla13.getSelectedRow();
		abrirVentanaFija('informacionextra.php?cliente='+obj.idcliente, 625, 418, 'ventana', 'Detalle Convenio')
	}
	
	function limpiar(){
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
		if(tabla9.creada()){
			tabla9.clear();
		}
		if(tabla10.creada()){
			tabla10.clear();
		}
		if(tabla11.creada()){
			tabla11.clear();
		}
		if(tabla12.creada()){
			tabla12.clear();
		}
		if(tabla13.creada()){
			tabla13.clear();
		}
		u.tab_contenedor_id1.disabled=true;		
		u.tab_contenedor_id2.disabled=true;
		u.tab_contenedor_id3.disabled=true;
		u.tab_contenedor_id4.disabled=true;
		u.tab_contenedor_id5.disabled=true;
		u.tab_contenedor_id6.disabled=true;
		u.tab_contenedor_id7.disabled=true;		
		u.tab_contenedor_id8.disabled=true;		
		u.tab_contenedor_id9.disabled=true;
		u.tab_contenedor_id10.disabled=true;
		u.tab_contenedor_id11.disabled=true;
		u.tab_contenedor_id12.disabled=true;
		tabs.seleccionar(0);
	}
	
	function imprimirReporte(tipo){
		if(document.URL.indexOf("web/")>-1){		
			var v_dir = "http://www.pmmintranet.net/web/general/clientes/";
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_capacitacion/general/clientes/";
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			var v_dir = "http://www.pmmintranet.net/web_pruebas/general/clientes/";
		}
		switch (tipo){
			case 1:
				window.open(v_dir+"generarExcel.php?accion=1&titulo=CONVENIOS POR SUCURSAL&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				+"&sucursal="+u.sucursal_hidden.value+"&val="+Math.random());
			break;
			case 2:
				window.open(v_dir+"generarExcel.php?accion=2&titulo=VENTAS POR CONVENIOS&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				+"&sucursal="+u.sucursal_hidden.value+"&val="+Math.random());
			break;
			case 3:
				window.open(v_dir+"generarExcel.php?accion=3&titulo=VENTAS CON COVENIO FACTURADAS SUCURSAL&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				+"&sucursal="+u.sucursal_hidden.value+"&val="+Math.random());
			break;
			
			case 4:
				window.open(v_dir+"generarExcel.php?accion=7&titulo=VENTAS CON COVENIO FACTURADAS CLIENTE&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text
				+"&sucursal="+v_suc1+"&val="+Math.random());
			break;
			
			case 5:
				window.open(v_dir+"generarExcel.php?accion=8&titulo=RELACION DE ENVIOS FACTURADOS POR CLIENTE&sucursal="+v_suc1
				+"&cliente="+v_cliente+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
			break;
			
			case 6:
				window.open(v_dir+"generarExcel.php?accion=4&titulo=VENTAS CON CONVENIO SIN FACTURAR&sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
			break;
			
			case 7:
				window.open(v_dir+"generarExcel.php?accion=5&titulo=PREPAGADAS SIN FACTURAR&sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
			break;
			
			case 8:
				window.open(v_dir+"generarExcel.php?accion=6&titulo=CONSIGNACION SIN FACTURAR&sucursal="+u.sucursal_hidden.value
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
			break;
			
			case 9:
				window.open(v_dir+"generarExcel.php?accion=9&titulo=VENTAS SIN CONVENIO SIN FACTURAR&sucursal="+v_suc2
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
			break;
			
			case 10:
				window.open(v_dir+"convenioExcel.php?accion=1&s=ACTIVADO&titulo=CONVENIOS VIGENTES&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
			break;
			
			case 11:
				window.open(v_dir+"convenioExcel.php?accion=1&s=EXPIRADO&titulo=CONVENIOS VENCIDOS&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
			break;
			
			case 12:
				window.open(v_dir+"convenioExcel.php?accion=2&titulo=TIPO DE CONVENIOS&sucursal="+((v_suc5!="")?v_suc5:u.sucursal_hidden.value)
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
			break;
			
			case 13:
				window.open(v_dir+"historialExcel.php?cliente="+v_clien10
				+"&fecha="+u.cmbfecha.options[u.cmbfecha.selectedIndex].text+"&val="+Math.random());
				
			break;
		}
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
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
    <td width="43">A&ntilde;o:</td>
    <td width="148"><select name="cmbfecha" class="Tablas" id="cmbfecha" style="width:100px">
      <?	$s = "SELECT MIN(YEAR(fecha)) AS primera, YEAR(CURDATE())AS actual FROM generacionconvenio";
						$ss = mysql_query($s,$l) or die($s);
						$fs = mysql_fetch_object($ss);
					
						for($i=$fs->primera;$i<=$fs->actual;$i++){
							?>
      <option value="<?=$i ?>"<? if($fecha==$i){ echo 'selected';} ?>>
      <?=$i ?>
      </option>
      <?	} ?>
    </select></td>
    <td width="26"><input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_SESSION[IDSUCURSAL] ?>" /></td>
    <td width="72">Sucursal:</td>
    <td width="208"><span class="Tablas">
      <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:170px" value="<?=$sucdescripcion; ?>" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onblur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}"/>
    </span></td>
    <td width="110"><img src="../../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onclick="obtenerDetalle()" /></td>
    <td width="93"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')" style="cursor:pointer" /></td>
  </tr>
  <tr>
    <td colspan="7">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle0">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="7"><div id="paginado" align="center" style="visibility:hidden">              
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion('ultimo')" />
		  <input type="hidden" name="pag1_total" />
          <input type="hidden" name="pag1_contador" value="0" />
          <input type="hidden" name="pag1_adelante" value="" />
          <input type="hidden" name="pag1_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="7"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center">Total Con. Vigentes </td>
        <td align="center">Total Con.Vencidos </td>
        <td align="center">Totales Convenios </td>
        <td align="center">Total Importe </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right">Totales</div></td>
        <td align="center"><input name="vigentes" type="text" class="Tablas" style="cursor:pointer;  text-align:center;background-color:#FFFF99; width:90px" id="vigentes" readonly="" title="Convenios Vigentes"/></td>
        <td align="center"><input name="vencidos" type="text" class="Tablas" style="cursor:pointer; background-color:#FFFF99; width:90px; text-align:center;" id="vencidos" readonly="" title="Convenios Vencidos" /></td>
        <td align="center"><input name="totalcon" type="text" class="Tablas" style="cursor:pointer; background-color:#FFFF99; width:90px; text-align:center;" id="totalcon" readonly="" title="Tipo de Convenio" /></td>
        <td align="center"><input name="importes" style="cursor:pointer; background-color:#FFFF99; width:90px; text-align:right;" type="text" class="Tablas" id="importes" readonly="" title="Ventas Por Convenio" /></td>
      </tr>
      <tr>
        <td width="11">&nbsp;</td>
        <td width="53">&nbsp;</td>
        <td width="157" align="center"><a onclick="obtenerVigentes()" href="#">Ver Convenios Vigentes</a href></td>
        <td width="154" align="center"><a onclick="obtenerVencidos()" href="#">Ver Convenios Vencidos</a href></td>
        <td width="136" align="center"><a onclick="obtenerTotalConvenios()" href="#">Ver Tipo de Convenios</a href></td>
        <td width="138" align="center"><a onclick="obtenerImporte()" href="#">Ver Ventas Por Convenio</a href></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="7"><table width="74" align="right">
      <tr>
        <td width="66" ><div class="ebtn_imprimir" onclick="imprimirReporte(1)" ></div></td>
      </tr>
    </table></td>	
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
    <td colspan="6"><div id="paginado1" align="center" style="visibility:hidden">      
      <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion1('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion1('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion1('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion1('ultimo')" />
      	  <input type="hidden" name="pag2_total" />
          <input type="hidden" name="pag2_contador" value="0" />
          <input type="hidden" name="pag2_adelante" value="" />
          <input type="hidden" name="pag2_atras" value="" />
    </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="680" height="16" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center">Total Facturado </td>
        <td align="center">Total N. Facturado </td>
        <td align="center">Total</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right">Total:</td>
        <td align="center"><input class="Tablas" ondblclick="" style="width:100px; background-color:#FFFF99;text-align:right; cursor:pointer " name="facturados" type="text"  id="facturados" readonly="" title="Ventas Convenio Facturadas" /></td>
        <td align="center"><input class="Tablas" ondblclick="" style="width:100px;  background-color:#FFFF99;text-align:right; cursor:pointer" name="nofacturados" type="text"  id="nofacturados" readonly=""  title="Ventas Convenio Sin Facturar"/></td>
        <td align="center"><input class="Tablas" style="width:100px; background-color:#FFFF99;text-align:right;" name="totalesfact" type="text" id="totalesfact" readonly=""></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="151" align="right">&nbsp;</td>
        <td  width="186" align="center"><p><a onclick="obtenerFacturado()" href="#">Ver Ventas Convenio Facturadas</a href></p></td>
        <td align="center" width="170"><a onclick="obtenerNFacturado()" href="#">Ver Ventas Convenio Sin Facturar</a href></td>
        <td align="center" width="161">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5"><div align="center">
            <table width="80" align="right">
              <tr>
                <td width="72" ><div class="ebtn_imprimir" onclick="imprimirReporte(2)"></div></td>
              </tr>
            </table>
        </div></td>
      </tr>
    </table></td>
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
    <td colspan="6"><div id="paginado2" align="center" style="visibility:hidden">     
      <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" />
      	  <input type="hidden" name="pag3_total" />
          <input type="hidden" name="pag3_contador" value="0" />
          <input type="hidden" name="pag3_adelante" value="" />
          <input type="hidden" name="pag3_atras" value="" />
    </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="100%" height="16" border="0" cellpadding="0" cellspacing="0" class="Tablas">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="Tablas">&nbsp;Normales</td>
        <td align="center" class="Tablas">Prepagadas </td>
        <td align="center" class="Tablas">Consignaci&oacute;n </td>
		<td align="center" class="Tablas">Otros </td>
        <td align="center" class="Tablas">Total
          <input name="inicio" type="hidden" class="Tablas" id="inicio" style="text-align:right;width:100px;background:#FFFF99" value="<?=$inicio ?>" readonly="" align="right" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>Total Gral:</td>
        <td align="center"><span class="style31">
          <input name="normales1" type="text" class="Tablas" id="normales1" readonly="" style="background-color:#FFFF99; width:100px;text-align:right;" />
        </span></td>
        <td align="center"><span class="style31">
          <input name="prepagadas1" type="text" class="Tablas" id="prepagadas1" readonly="" style="background-color:#FFFF99; width:100px;text-align:right;" />
        </span></td>
        <td align="center"><span class="style31">
          <input name="consignacion1" type="text" class="Tablas" id="consignacion1" readonly="" style="background-color:#FFFF99; width:100px;text-align:right;" />
        </span></td>
		<td align="center"><span class="style31">
          <input name="otros1" type="text" class="Tablas" id="otros1" readonly="" style="background-color:#FFFF99; width:100px;text-align:right;" />
        </span></td>
        <td align="center"><span class="style31">
          <input name="totales1" type="text" class="Tablas" id="totales1" readonly="" style=" background-color:#FFFF99; width:100px;text-align:right;" />
        </span></td>
      </tr>
      <tr>
        <td width="16">&nbsp;</td>
        <td width="119"><div align="center"></div></td>
        <td width="125" align="center">&nbsp;</td>
        <td width="143" align="center">&nbsp;</td>
        <td width="139" align="center">&nbsp;</td>
		<td width="139" align="center">&nbsp;</td>
        <td width="117" align="center"><div class="ebtn_imprimir" onclick="imprimirReporte(3)"></div></td>
      </tr>
    </table></td>
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
    <td colspan="6"><div id="paginado3" align="center" style="visibility:hidden">
     
<img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion3('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion3('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion3('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion3('ultimo')" />
      	  <input type="hidden" name="pag4_total" />
          <input type="hidden" name="pag4_contador" value="0" />
          <input type="hidden" name="pag4_adelante" value="" />
          <input type="hidden" name="pag4_atras" value="" />
    </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="687" height="28" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
        <td><div align="center">Total Normales </div></td>
        <td><div align="center">Total Prepagadas </div></td>
        <td align="center"><div align="center">Total Consignacion </div></td>
        <td align="center">Otros</td>
		<td align="center">Total</td>
      </tr>
      <tr>
        <td width="206"><div align="right">Total</div></td>
        <td width="140"><div align="center"><span class="style31">
            <input name="normales2" type="text" class="Tablas" id="normales2" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;" />
        </span></div></td>
        <td width="113"><div align="center"><span class="style31">
            <input name="prepagadas2" type="text" class="Tablas" id="prepagadas2" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;">
        </span></div></td>
        <td width="130" align="center"><span class="style31">
          <input name="consignacion2" type="text" class="Tablas" id="consignacion2" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;">
        </span></td>
		<td width="130" align="center"><span class="style31">
          <input name="otros2" type="text" class="Tablas" id="otros2" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;">
        </span></td>
        <td width="98" align="center"><span class="style31">
          <input name="totales2" type="text" class="Tablas" id="totales2" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;">
        </span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(4)"></div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id4">
<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="70">Cliente:</td>
    <td width="104"><input name="cliente" type="text" class="Tablas" id="cliente" style="width:80px;" readonly="" value="<?=$_GET[cliente] ?>"/></td>
    <td width="351"><input name="nombre" type="text" class="Tablas" id="nombre" style="width:300px" readonly="" value="<?=$f->nombre ?>"/></td>
    <td width="175">&nbsp;</td>
  </tr>
  <tr>
  		<td>Sucursal:</td>
        <td colspan="2"><input name="sucursal2" type="text" class="Tablas" id="sucursal2" style="width:150px" readonly="" value="<?=$nombresucursal ?>"/></td>
        <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="9">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle4">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="9"><div id="paginado4" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion4('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion4('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion4('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion4('ultimo')" />
      	  <input type="hidden" name="pag5_total" />
          <input type="hidden" name="pag5_contador" value="0" />
          <input type="hidden" name="pag5_adelante" value="" />
          <input type="hidden" name="pag5_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="9"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15">&nbsp;</td>
        <td width="104">&nbsp;</td>
        <td width="99" align="center">&nbsp;</td>
        <td width="99" align="center">Total Gral:</td>
        <td width="104" align="center"><span class="style31">
          <input name="totalgeneral" type="text" class="Tablas" id="totalgeneral" readonly="" style="text-align:right;background-color:#FFFF99; width:100px;" />
        </span></td>
        <td width="94" align="center"><div class="ebtn_imprimir" onclick="imprimirReporte(5)"></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="9">&nbsp;</td>
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
    <td colspan="6"><div id="paginado5" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion5('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion5('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion5('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion5('ultimo')" />
      	  <input type="hidden" name="pag6_total" />
          <input type="hidden" name="pag6_contador" value="0" />
          <input type="hidden" name="pag6_adelante" value="" />
          <input type="hidden" name="pag6_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="100%" height="16" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center">Total Normales </td>
        <td align="center">Total Prepagadas </td>
        <td align="center">Total Consignacion </td>
        <td align="center">Total</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="center">Total Gral:</div></td>
        <td align="center"><span class="style31">
          <input name="normales6" type="text" class="Tablas" id="normales6" style="background-color:#FFFF99; width:100px;text-align:right;" readonly="">
        </span></td>
        <td align="center"><span class="style31">
          <input name="prepagadas6" type="text" ondblclick="" class="Tablas" id="prepagadas6" readonly="" style="background-color:#FFFF99; width:100px;text-align:right; cursor:pointer" title="Prepagadas Sin Facturar">
        </span></td>
        <td align="center"><span class="style31">
          <input name="consignacion6" type="text" class="Tablas" ondblclick="" id="consignacion6" readonly="" style="background-color:#FFFF99; width:100px;text-align:right; cursor:pointer" title="Concignaci&oacute;n Sin Facturar">
        </span></td>
        <td align="center"><span class="style31">
          <input name="totales6" type="text" class="Tablas" id="totales6" readonly="" style="background-color:#FFFF99; width:100px;text-align:right;">
        </span></td>
      </tr>
      <tr>
        <td width="17">&nbsp;</td>
        <td width="118">&nbsp;</td>
        <td width="135" align="center">&nbsp;</td>
        <td width="148" align="center"><a onclick="obtenerPrepagadas()" href="#">Ver Prepagadas Sin Facturar</a href></td>
        <td width="125" align="center"><a onclick="obtenerConsignacion()" href="#">Ver Consignaci&oacute;n Sin Facturar</a href></td>
        <td width="129" align="center"><span class="Estilo4">
          <input name="fecha" type="hidden" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha ?>" />
        </span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(6)"></div></td>
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
	</div>	</td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado6" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion6('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion6('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion6('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion6('ultimo')" />
      	  <input type="hidden" name="pag7_total" />
          <input type="hidden" name="pag7_contador" value="0" />
          <input type="hidden" name="pag7_adelante" value="" />
          <input type="hidden" name="pag7_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="84%" align="right">Total:</td>
        <td width="16%"><span class="style31">
          <input name="totales7" type="text" class="Tablas" id="totales7" readonly="" style="background-color:#FFFF99; width:90px; text-align:right;">
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(7)"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</div>	
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id7">
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle7">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado7" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion7('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion7('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion7('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion7('ultimo')" />
      	  <input type="hidden" name="pag8_total" />
          <input type="hidden" name="pag8_contador" value="0" />
          <input type="hidden" name="pag8_adelante" value="" />
          <input type="hidden" name="pag8_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="84%" align="right">Total:</td>
        <td width="16%"><span class="style31">
          <input name="totales8" type="text" class="Tablas" id="totales8" readonly="" style="background-color:#FFFF99; width:90px; text-align:right;">
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(8)"></div></td>
      </tr>
    </table></td>
  </tr>  
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id8">
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle9">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado9" align="center" style="visibility:hidden">
     
<img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion9('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion9('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion9('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion9('ultimo')" />
      	  <input type="hidden" name="pag9_total" />
          <input type="hidden" name="pag9_contador" value="0" />
          <input type="hidden" name="pag9_adelante" value="" />
          <input type="hidden" name="pag9_atras" value="" />
    </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="687" height="28" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
        <td><div align="center">Total Normales </div></td>
        <td><div align="center">Total Prepagadas </div></td>
        <td align="center"><div align="center">Total Consignacion </div></td>
        <td align="center">Total</td>
      </tr>
      <tr>
        <td width="206"><div align="right">Total</div></td>
        <td width="140"><div align="center"><span class="style31">
            <input name="normales9" type="text" class="Tablas" id="normales9" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;" />
        </span></div></td>
        <td width="113"><div align="center"><span class="style31">
            <input name="prepagadas9" type="text" class="Tablas" id="prepagadas9" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;">
        </span></div></td>
        <td width="130" align="center"><span class="style31">
          <input name="consignacion9" type="text" class="Tablas" id="consignacion9" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;">
        </span></td>
        <td width="98" align="center"><span class="style31">
          <input name="totales9" type="text" class="Tablas" id="totales9" readonly="" style="background-color:#FFFF99; width:90px;text-align:right;">
        </span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(9)"></div></td>
  </tr>
</table>
</div>

<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id9">
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle10">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado10" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion10('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion10('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion10('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion10('ultimo')" />
      	  <input type="hidden" name="pag10_total" />
          <input type="hidden" name="pag10_contador" value="0" />
          <input type="hidden" name="pag10_adelante" value="" />
          <input type="hidden" name="pag10_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="84%" align="right">Total:</td>
        <td width="16%"><span class="style31">
          <input name="totales10" type="text" class="Tablas" id="totales10" readonly="" style="background-color:#FFFF99; width:90px; text-align:right;">
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(10)"></div></td>
      </tr>
    </table></td>
  </tr>  
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id10">
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle11">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado11" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion11('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion11('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion11('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion11('ultimo')" />
      	  <input type="hidden" name="pag11_total" />
          <input type="hidden" name="pag11_contador" value="0" />
          <input type="hidden" name="pag11_adelante" value="" />
          <input type="hidden" name="pag11_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="84%" align="right">Total:</td>
        <td width="16%"><span class="style31">
          <input name="totales11" type="text" class="Tablas" id="totales11" readonly="" style="background-color:#FFFF99; width:90px; text-align:right;">
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(11)"></div></td>
      </tr>
    </table></td>
  </tr>  
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id11">
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle12">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado12" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion12('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion12('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion12('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion12('ultimo')" />
      	  <input type="hidden" name="pag12_total" />
          <input type="hidden" name="pag12_contador" value="0" />
          <input type="hidden" name="pag12_adelante" value="" />
          <input type="hidden" name="pag12_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="84%" align="right">Total:</td>
        <td width="16%"><span class="style31">
          <input name="totales12" type="text" class="Tablas" id="totales12" readonly="" style="background-color:#FFFF99; width:90px; text-align:right;">
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(12)"></div></td>
      </tr>
    </table></td>
  </tr>  
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id12">
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle13">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="6"><div id="paginado13" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion13('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion13('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion13('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion13('ultimo')" />
      	  <input type="hidden" name="pag13_total" />
          <input type="hidden" name="pag13_contador" value="0" />
          <input type="hidden" name="pag13_adelante" value="" />
          <input type="hidden" name="pag13_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      
      <tr>
        <td width="84%">&nbsp;</td>
        <td width="16%" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte(13)"></div></td>
      </tr>
    </table></td>
  </tr>  
</table>
</div>
<table width="600" height="66" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla Estilo4">Reporte Principal de Clientes </td>
  </tr>
  <tr>
    <td height="450px" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
