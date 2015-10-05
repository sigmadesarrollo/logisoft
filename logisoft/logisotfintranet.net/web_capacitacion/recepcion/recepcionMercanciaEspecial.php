<? 	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	$s = "DELETE FROM reportedanosfaltante WHERE recepcion IS NULL AND idusuario='".$_SESSION[IDUSUARIO]."'";
	mysql_query($s,$link) or die($s);
	
	$s = "DELETE FROM recepcion_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
	mysql_query($s,$link) or die($s);
	
	$fecha = date('d/m/Y');
	//Sucursal
	$suc = @mysql_query("SELECT descripcion, prefijo FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."",$link);
		$rsuc = @mysql_fetch_array($suc); 
		$sucursal = $rsuc[0];
		$prefijo  =	$rsuc['prefijo'];

		$s = "SELECT obtenerFolio('recepcionmercancia',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$link) or die($s); $f = mysql_fetch_object($r);
		$folio = $f->folio;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script>
	var u = document.all;	
	var tabla1 = new ClaseTabla();	
	var tabla2 = new ClaseTabla();
	var tabla3 = new ClaseTabla();
	var tabla4 = new ClaseTabla();
	var v_guia = "";
	var v_incompletas = "";
	var v_index = 0;
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript");
	//para paginado
	var pag1_cantidadporpagina = 30;
	
	tabla1.setAttributes({
	nombre:"tabladetalleizq",
	campos:[
			{nombre:"SECTOR", medida:50, alineacion:"center", datos:"sector"},
			{nombre:"No_GUIA", medida:75, alineacion:"center", datos:"guia"},
			{nombre:"ORIGEN", medida:75, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:75, alineacion:"center", datos:"destino"},
			{nombre:"FALTANTE", medida:4, tipo:"oculto", alineacion:"center", datos:"faltante"}
		],
		filasInicial:14,
		alto:200,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"ObtDetalleIzq()",
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
	nombre:"tabladetalleder",
	campos:[
			{nombre:"SECTOR", medida:50, alineacion:"center", datos:"sector"},
			{nombre:"No_GUIA", medida:75, alineacion:"center", datos:"guia"},
			{nombre:"ORIGEN", medida:75, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:75, alineacion:"center", datos:"destino"},
			{nombre:"ESTADO", medida:4, alineacion:"center", tipo:"oculto", datos:"estado"}
		],
		filasInicial:14,
		alto:200,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"mostrarEstadoGuia()",
		eventoClickFila:"ObtDetalleDer()",
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({
	nombre:"tabladetalleizqabajo",
	campos:[
		{nombre:"REGISTRO", medida:70, alineacion:"center", datos:"registro"},
		{nombre:"PAQUETE", medida:40, alineacion:"center", datos:"paquete"},
		{nombre:"CODIGO_DE_BARRAS", medida:70, alineacion:"center", datos:"codigobarra"},
		{nombre:"ESTADO", medida:90, alineacion:"center", datos:"estado"},
		{nombre:"GUIA", medida:4, tipo:"oculto", alineacion:"center", datos:"guia"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"limpiarSelIzq()",
		nombrevar:"tabla3"
	});
	
	tabla4.setAttributes({
	nombre:"tabladetallederabajo",
	campos:[
			{nombre:"REGISTRO", medida:70, alineacion:"center", datos:"registro"},
			{nombre:"PAQUETE", medida:40, alineacion:"center", datos:"paquete"},
			{nombre:"CODIGO_DE_BARRAS", medida:70, alineacion:"center", datos:"codigobarra"},
			{nombre:"ESTADO", medida:90, alineacion:"center", datos:"estado"},
			{nombre:"GUIA", medida:4, tipo:"oculto", alineacion:"center", datos:"guia"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"limpiarSelDer()",
		nombrevar:"tabla4"
	});
	
	window.onload = function(){
		u.unidad.focus();
		tabla1.create();
		tabla2.create();
		tabla3.create();
		tabla4.create();	
	}
	
	function moverAlaDerecha(){
		if(tabla1.getRecordCount()==""){
			mens.show("A",'No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			if(tabla1.getSelectedIdRow()!=""){
				document.getElementById('aderecha').style.visibility = 'hidden';
				consultaTexto("resMovADerC","recepcionMercanciaEspecial_con.php?accion=4&folio="+tabla1.getSelectedRow().guia);
			}else if(tabla3.getSelectedIdRow()!=""){
				document.getElementById('aderecha').style.visibility = 'hidden';
				consultaTexto("resMovADerU","recepcionMercanciaEspecial_con.php?accion=5&folio="+tabla3.getSelectedRow().guia
					+"&registro="+tabla3.getSelectedRow().registro+"&random="+Math.random());
			}else{
				mens.show("A",'Debe seleccionar la fila que desea mover a la derecha','¡Atención!');
			}
		}
	}
	
	function resMovADerC(datos){
		if(datos.indexOf("guardado")>-1){
			var folios = tabla2.getValuesFromField("guia");
			var folio  = tabla1.getValSelFromField("guia","No_GUIA");
			if(folios.indexOf(folio)<0)
				tabla2.add(tabla1.getSelectedRow());
			tabla1.deleteById(tabla1.getSelectedIdRow());
			tabla3.clear();
			if(tabla1.getRecordCount()>0){
				tabla1.setSelectedById("tabladetalleizq_id0");
			}
			//buscarEnDer(folio);
			document.getElementById('aderecha').style.visibility = 'visible';
		}
	}
	function resMovADerU(datos){
		if(datos.indexOf("guardado")>-1){
			var folio = tabla3.getValSelFromField("guia","GUIA");
			var folios = tabla2.getValuesFromField("guia");
			if(folios.indexOf(folio)<0){
				folios = tabla1.getValuesFromField("guia");
				var folioarre = folios.split(",");
				for(var i=0; i<tabla1.getRecordCount();i++){
					if(folioarre[i]==folio){
						tabla1.setSelectedById("tabladetalleizq_id"+i);
						tabla2.add(tabla1.getSelectedRow());
					}
				}
			}
			if(tabla3.getRecordCount()==1){
				folios = tabla1.getValuesFromField("guia");
				var folioarre = folios.split(",");
				for(var i=0; i<tabla1.getRecordCount();i++){
					if(folioarre[i]==folio){
						tabla1.setSelectedById("tabladetalleizq_id"+i);
						tabla1.deleteById(tabla1.getSelectedIdRow());
					}
				}
			}
			tabla4.add(tabla3.getSelectedRow());
			tabla3.deleteById(tabla3.getSelectedIdRow());			
			buscarEnDer(folio);			
		}
		document.getElementById('aderecha').style.visibility = 'visible';
	}
	
	function moverAlaizquierda(){
		if(tabla2.getRecordCount()==""){
			mens.show("A",'No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			if(tabla2.getSelectedIdRow()!=""){
				consultaTexto("resMovAIzqC","recepcionMercanciaEspecial_con.php?accion=6&folio="+tabla2.getSelectedRow().guia);
			}else if(tabla4.getSelectedIdRow()!=""){
				consultaTexto("resMovAIzqU","recepcionMercanciaEspecial_con.php?accion=7&folio="+tabla4.getSelectedRow().guia
					+"&registro="+tabla4.getSelectedRow().registro+"&random="+Math.random());
			}else{
				mens.show("A",'Debe seleccionar la fila que desea mover a la derecha','¡Atención!');	
			}
		}
	}
	function resMovAIzqC(datos){
		if(datos.indexOf("guardado")>-1){
			var folios = tabla1.getValuesFromField("guia");
			var folio  = tabla2.getValSelFromField("guia","No_GUIA");
			if(folios.indexOf(folio)<0)
				tabla1.add(tabla2.getSelectedRow());
			tabla2.deleteById(tabla2.getSelectedIdRow());
			tabla4.clear();
			//buscarEnIzq(folio);
		}
	}
	function resMovAIzqU(datos){		
		if(datos.indexOf("guardado")>-1){			
			var folio = tabla4.getValSelFromField("guia","GUIA");
			var folios = tabla1.getValuesFromField("guia");
			if(folios.indexOf(folio)<0){
				folios = tabla2.getValuesFromField("guia");
				var folioarre = folios.split(",");
				for(var i=0; i<tabla2.getRecordCount();i++){
					if(folioarre[i]==folio){
						tabla2.setSelectedById("tabladetalleder_id"+i);
						tabla1.add(tabla2.getSelectedRow());
					}
				}
			}
			if(tabla4.getRecordCount()==1){
				folios = tabla2.getValuesFromField("guia");
				var folioarre = folios.split(",");
				for(var i=0; i<tabla2.getRecordCount();i++){
					if(folioarre[i]==folio){
						tabla2.setSelectedById("tabladetalleder_id"+i);
						tabla2.deleteById(tabla2.getSelectedIdRow());
					}
				}
			}
			tabla3.add(tabla4.getSelectedRow());
			tabla4.deleteById(tabla4.getSelectedIdRow());			
			buscarEnIzq(folio);
		}
	}	

/*******************/
	function ObtDetalleIzq(){
		tabla3.setSelectedById("");
		if(tabla1.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarIzqAbajo","recepcionMercancia_paginado.php?accion=1&folio="+tabla1.getValSelFromField('guia','No_GUIA'));
		}
	}
	function mostrarIzqAbajo(datos){
			try{
				var obj = eval(datos);
			}catch(e){
				alerta3(datos);
			}
			u.pag1_total.value = obj.total;
			u.pag1_contador.value = obj.contador;
			u.pag1_adelante.value = obj.adelante;
			u.pag1_atras.value = obj.atras;
			tabla3.setJsonData(obj.registros);
			
			if(obj.paginado==1){
				document.getElementById('div_paginado1').style.visibility = 'visible';
			}else{
				document.getElementById('div_paginado1').style.visibility = 'hidden';
			}
		
			//var objeto = eval(convertirValoresJson(datos));
			//tabla3.setJsonData(objeto);
	}
	
	function paginacion1(movimiento){
		if(tabla1.getSelectedRow()==null){
			mens.show("A","Seleccione la guia para moverse entre el detallado","¡ATENCION!");
			return false;
		}
			
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarIzqAbajo","recepcionMercancia_paginado.php?accion=1&contador=0"+
							  "&folio="+tabla1.getValSelFromField('guia','No_GUIA')+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("mostrarIzqAbajo","recepcionMercancia_paginado.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)+
						  "&folio="+tabla1.getValSelFromField('guia','No_GUIA')+"&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("mostrarIzqAbajo","recepcionMercancia_paginado.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)+
						  "&folio="+tabla1.getValSelFromField('guia','No_GUIA')+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarIzqAbajo","recepcionMercancia_paginado.php?accion=1&contador="+contador+
					  "&folio="+tabla1.getValSelFromField('guia','No_GUIA')+
					  "&s="+Math.random());
				break;
		}
	}
/****************/
	function ObtDetalleDer(){
		tabla4.setSelectedById("");
		if(tabla2.getValSelFromField('guia','No_GUIA')!=""){
			//consultaTexto("mostrarDerAbajo","recepcionMercanciaEspecial_con.php?accion=3&folio="+tabla2.getValSelFromField('guia','No_GUIA'));
			consultaTexto("mostrarDerAbajo","recepcionMercancia_paginado.php?accion=2&folio="+tabla2.getValSelFromField('guia','No_GUIA'));
		}
	}
	function mostrarDerAbajo(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			alerta3(datos);
		}
		u.pag2_total.value = obj.total;
		u.pag2_contador.value = obj.contador;
		u.pag2_adelante.value = obj.adelante;
		u.pag2_atras.value = obj.atras;
		tabla4.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('div_paginado2').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado2').style.visibility = 'hidden';
		}
		
		//var objeto = eval(convertirValoresJson(datos));
		//tabla4.setJsonData(objeto);
	}
	
	function paginacion2(movimiento){
		if(tabla2.getSelectedRow()==null){
			mens.show("A","Seleccione la guia para moverse entre el detallado","¡ATENCION!");
			return false;
		}
			
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarDerAbajo","recepcionMercancia_paginado.php?accion=2&contador=0"+
							  "&folio="+tabla2.getValSelFromField('guia','No_GUIA')+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("mostrarDerAbajo","recepcionMercancia_paginado.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)+1)+
						  "&folio="+tabla2.getValSelFromField('guia','No_GUIA')+"&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("mostrarDerAbajo","recepcionMercancia_paginado.php?accion=2&contador="+(parseFloat(u.pag2_contador.value)-1)+
						  "&folio="+tabla2.getValSelFromField('guia','No_GUIA')+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag2_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarDerAbajo","recepcionMercancia_paginado.php?accion=2&contador="+contador+
					  "&folio="+tabla2.getValSelFromField('guia','No_GUIA')+
					  "&s="+Math.random());
				break;
		}
	}
/******* Funcion limpiar seleccion detalles **********/
	function limpiarSelIzq(){
		tabla1.setSelectedById("");
	}
	function limpiarSelDer(){
		tabla2.setSelectedById("");
	}

/********************/
	function obtenerUnidadBusqueda(unidad){
		u.unidad.value = unidad;		
		consultaTexto("mostrarUnidadBusqueda","recepcionMercanciaEspecial_con.php?accion=14&unidad="+unidad+"&sid="+Math.random());
	}
	function mostrarUnidadBusqueda(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval("("+datos+")");		
			u.ruta.value			= obj.ruta;
			u.recorrido.value		= obj.desruta;
			u.foliobitacora.value	= obj.bitacora;			
			tabla2.clear();
			tabla3.clear();
			tabla4.clear();
			CargarGuias();	
		}else{
			mens.show("A","La unidad seleccionada no ha sido registrada en una Bitácora de Salida",'¡Atención!');
			u.unidad.value	="";
			u.ruta.value	="";
			u.recorrido.value="";
			tabla1.clear();
			tabla2.clear();
			tabla3.clear();
			tabla4.clear();		
		}
	}

	function CargarGuias(){
		consultaTexto("mostrarCargarGuias","recepcionMercanciaEspecial_con.php?accion=1&unidad="+u.unidad.value
		+"&sucursal=<?=$_SESSION[IDSUCURSAL]?>&sid="+Math.random());
	}
	function mostrarCargarGuias(datos){
		var objeto = eval(convertirValoresJson(datos));
		tabla1.setJsonData(objeto.izquierda);
		tabla2.setJsonData(objeto.derecha);
	}

	function BuscarGuia(valor){
		var idguia =tabla1.getValuesFromField('guia',':');
		idguia = idguia.split(":");
		for(i=0;i<idguia.length;i++){
			if(valor==idguia[i]){
				tabla1.setSelectedById("tabladetalleizq_id"+i);
				break;
			}
		}
	}

	function buscarEnDer(folio){
		var campos = tabla2.getValuesFromField("guia");
		camposarreglo = campos.split(",");
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(camposarreglo[i] == folio){
				tabla2.setSelectedById("tabladetalleder_id"+i);
				ObtDetalleDer();
				return true;
			}
		}
	}
	function buscarEnIzq(folio){
		var campos = tabla1.getValuesFromField("guia");
		camposarreglo = campos.split(",");
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(camposarreglo[i] == folio){
				tabla1.setSelectedById("tabladetalleizq_id"+i);
				ObtDetalleIzq();
				return true;
			}
		}
	}
	
	function limpiarDatos(){	
		u.unidad.value	= "";
		u.ruta.value	= "";
		u.recorrido.value= "";
		tabla1.clear();
		tabla2.clear();
		tabla3.clear();
		tabla4.clear();
		u.guia.value	= "";
		u.btn_guardar.style.visibility = "visible";
		u.d_imprimir.style.visibility = "hidden";
		document.form1.submit();
	}
	
	function buscarFolio(){
		if(u.buscar.value==0){
			var campo = "guia";
		}else{
			var campo = "codigobarra";
		}
		if(tabla1.getSelectedIdRow()!=""){
			var campos = tabla1.getValuesFromField(campo);
			camposarreglo = campos.split(",");
			for(var i=0; i<tabla1.getRecordCount(); i++){
				if(camposarreglo[i] == u.guia.value){
					tabla1.setSelectedById("tabladetalleizq_id"+i);
					obtenerGuiaIzq();
					return true;
				}
			}
		}
		if(tabla2.getSelectedIdRow()!=""){
			var campos = tabla2.getValuesFromField(campo);
			camposarreglo = campos.split(",");
			for(var i=0; i<tabla2.getRecordCount(); i++){
				if(camposarreglo[i] == u.guia.value){
					tabla2.setSelectedById("tabladetalleder_id"+i);
					obtenerGuiaDer();
					return true;
				}
			}
		}
		mens.show("A","No se encontro "+((u.buscar.value==0)?"la guia buscada":"el codigo buscado"));
	}

	function guardarValores(){		
		if(u.filtro[0].checked==true){
			if(u.unidad.value==""){
				mens.show("A","Debe Capturar unidad","¡Atencion!","unidad");
				return false;
			}
		}
		
		if(tabla2.getRecordCount()>0 && u.guardado.value == ""){
			guardarFinal();
			// se kito la validacion de faltanes en especiales
			//consultaTexto("resValidar","recepcionMercanciaEspecial_con.php?accion=8&mathrand="+Math.random());
		}else{
			mens.show("A","La informacion ya ha sido guardada","¡Atención!");
			return false;
		}
	}
	function resValidar(datos){
		if(datos.indexOf("ok")>-1){
			guardarFinal();			
		}else{
			v_index = 0;
			var mensaje = "";
			for(var i=0;i<tabla1.getRecordCount();i++){				
				if(u["tabladetalleizq_FALTANTE"][i].value!="SI"){
					mensaje += u["tabladetalleizq_No_GUIA"][i].value+",";
				}
			}
			v_incompletas = mensaje.substr(0,mensaje.length-1);
			v_incompletas = v_incompletas.split(",");
			mostrarGuiaArreglo();
		}
	}
	
	function mostrarGuiaArreglo(){			
	abrirVentanaFija('reporteDanoFaltante.php?guia='+v_incompletas[v_index]
	+'&ruta='+((u.filtro[0].checked==true) ? u.ruta.value : u.h_ruta.value)
	+'&unidad='+((u.filtro[0].checked==true) ? u.unidad.value : u.h_unidad.value)
	+"&tipo=faltante&sucursal=<?=$_SESSION[IDSUCURSAL]?>&indice="+v_index, 600, 480, 'ventana', 'REPORTE DE DAÑOS Y FALTANTES');
		if(v_incompletas[v_index]==undefined){
			VentanaModal.cerrar();
			mens.show("I","Se han registrado las guias con faltantes","¡Atencion!");
			guardarFinal();
		}
		v_index++;
	}	
	function mostrarLogueo(){
		abrirVentanaFija("../buscadores_generales/logueo_permisos.php?funcion=guardarFinal&modulo=GuiaVentanilla&usuario=Admin",500,400,"ventana","DATOS PERSONALES");
	}
	
	function guardarFinal(){
		var unidad		= ((u.filtro[0].checked==true) ? u.unidad.value : u.h_unidad.value);
		var ruta		= ((u.filtro[0].checked==true) ? u.ruta.value : u.h_ruta.value);
		var recorrido	= u.recorrido.value;
		var foliobitacora=u.foliobitacora.value;
		var v_fecha = fechahora(v_fecha);
		u.btn_guardar.style.visibility = "hidden";
		consultaTexto("resGuardar","recepcionMercanciaEspecial_con.php?accion=9&unidad="+unidad
		+"&ruta="+ruta+"&tiporecepcion=ESPECIAL&folios="+tabla2.getValuesFromField('guia')
		+"&folio="+u.folio.value+"&sucursal="+'<?=$_SESSION[IDSUCURSAL]?>'+"&foliobitacora="+foliobitacora
		+"&mathrand="+Math.random()+"&fechahora="+v_fecha);
	}
	function resGuardar(datos){
		if(datos.indexOf("guardado")>-1){
			mens.show("I","La informacion ha sido guardada","");
			u.guardado.value = 1;
			u.d_imprimir.style.visibility = "visible";
		}else if(datos.indexOf("noserecepcionaron")>-1){
			var obj = eval(datos);
			mens.show("A","No se han recepcionado las siguientes guias o no se les ha registrado faltante "+obj.noserecepcionaron,"¡Atención!");
		}else{
			mens.show("A","Hubo un error "+datos,"¡Atención!");
			u.btn_guardar.style.visibility = "visible";
		}
	}
	
	function mostrarFaltante(){
		if(u.guardado.value == ""){
			var arr = tabla1.getSelectedRow();	
			if(tabla1.getRecordCount()>0){
			abrirVentanaFija('reporteDanoFaltante.php?guia='+arr.guia
			+'&ruta='+((u.filtro[0].checked==true) ? u.ruta.value : u.h_ruta.value)
			+'&unidad='+((u.filtro[0].checked==true) ? u.unidad.value : u.h_unidad.value)
			+'&dblClick=SI&tipo=faltantedbl&sucursal=<?=$_SESSION[IDSUCURSAL]?>', 600, 480, 'ventana', 'REPORTE DE DAÑOS Y FALTANTES');
			}
		}
	}
	
	function dobleClickFaltante(guia){
		for(var i=0;i<tabla1.getRecordCount();i++){
			if(u["tabladetalleizq_No_GUIA"][i].value==guia){
				u["tabladetalleizq_FALTANTE"][i].value == "SI";
				break;
			}
		}
	}
	
	function mostrarEstadoGuia(){
		if(u.guardado.value == ""){
			if(tabla2.getRecordCount()>0){	
				abrirVentanaFija('estadoGuia.php?estado=BUEN ESTADO', 450, 350, 'ventana', 'ESTADO GUIA');
			}
		}
	}
	function estadoGuia(estado,observaciones){
		var arr 	= tabla2.getSelectedRow();
		var obj		= Object();
		obj.sector	= arr.sector;
		obj.guia	= arr.guia;
		obj.origen	= arr.origen;
		obj.destino	= arr.destino;
		obj.estado	= ((estado=="BUEN ESTADO")?"BUEN ESTADO":"DAÑADA");
		tabla2.updateRowById(tabla2.getSelectedIdRow(), obj);		
		u.estado_hidden.value = estado;
		u.observaciones_hidden.value = observaciones;
		
		if(estado != "BUEN ESTADO"){
			consultaTexto("mostrarDerAbajo","recepcionMercanciaEspecial_con.php?accion=11&estado="+estado+"&folio="+arr.guia);
			abrirVentanaFija('reporteDanoFaltante.php?guia='+arr.guia
			+'&ruta='+((u.filtro[0].checked==true) ? u.ruta.value : u.h_ruta.value)
			+'&unidad='+((u.filtro[0].checked==true) ? u.unidad.value : u.h_unidad.value)
			+"&tipo=dano&sucursal=<?=$_SESSION[IDSUCURSAL]?>&indice=0", 600, 480, 'ventana', 'REPORTE DE DAÑOS Y FALTANTES');
		}
		tabla2.setColorById('#FF0000',tabla2.getSelectedIdRow());
	}	
	
	function cargarRecepcion(folio){
		u.folio.value = folio;
		consultaTexto("resCargarRecepcion","recepcionMercanciaEspecial_con.php?accion=12&folio="+folio)
	}
	
	function resCargarRecepcion(datos){
		var objeto = eval(convertirValoresJson(datos));
		tabla2.setJsonData(objeto.derecha);
		document.getElementById('celdaGuardar').style.display="none";
		document.getElementById('unidad').value=objeto.datosembarque.unidad;
		document.getElementById('ruta').value=objeto.datosembarque.id;
		document.getElementById('recorrido').value=objeto.datosembarque.descripcion;
		document.getElementById('fecha').value=objeto.datosembarque.fecha;
		document.getElementById('folio').value=objeto.datosembarque.folio;
		document.getElementById('aderecha').style.display="none";
		document.getElementById('aizquierda').style.display="none";
	}
	
	function imprimirRecepcion(){
		if(u.guardado.value == 1){		
			if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/fpdf/reportes/recepcionMercancia.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&fecharecepcion="+u.fecha.value);
			
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/recepcionMercancia.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&fecharecepcion="+u.fecha.value);
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/recepcionMercancia.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&fecharecepcion="+u.fecha.value);
			}		
		}else{
			mens.show("A","Debe guardar la recepción para poder imprimir el reporte","¡Atención!");
		}
	}
	
	function obtenerPaquete(){
		var guia = u.paquete.value;		
		consultaTexto("mostrarPaquete","recepcionMercanciaEspecial_con.php?accion=13&guia="+(guia.substring(0,guia.length-8))
		+"&paquete="+guia+"&val="+Math.random());		
	}

	function mostrarPaquete(datos){
		if(datos.indexOf("no existe")>-1){
			mens.show("A","El numero de paquete no existe","¡Atención!","paquete");
			return false;	
		}
		
		var obj = eval(convertirValoresJson(datos));
				
		if(obj.unidad!="" && u.h_unidad.value == ""){
			u.h_unidad.value = obj.unidad;
			u.foliobitacora.value = obj.bitacora;
			u.h_ruta.value = obj.ruta;
			var guia = u.paquete.value;
			u.paquete.value = "";
			consultaTexto("mostrarPaquetes","recepcionMercanciaEspecial_con.php?accion=15&guia="+(guia.substring(0,guia.length-8))
			+"&paquete="+guia+"&val="+Math.random());
			return false;
		}
		
		if(obj.unidad != u.h_unidad.value){
			mens.show("A","El paquete leido no pertenece a la unidad de los paquetes leidos anteriormente","paquete");
			u.paquete.value = "";
			return false;
		}
		
		var guia = u.paquete.value;
		u.paquete.value = "";
		consultaTexto("mostrarPaquetes","recepcionMercanciaEspecial_con.php?accion=15&guia="+(guia.substring(0,guia.length-8))
		+"&paquete="+guia+"&val="+Math.random());
	}
	
	function mostrarPaquetes(datos){
		var objeto = eval(datos);
		tabla1.setJsonData(objeto.izquierda);
		tabla2.setJsonData(objeto.derecha);
	}
	
	function habilitarFiltro(datos){
		if(u.filtro[0].checked==true){
			document.getElementById('paquete').disabled = true;
			document.getElementById('paquete').style.backgroundColor='#FFFF99';
			
			document.getElementById('unidad').disabled = false;
			document.getElementById('unidad').style.backgroundColor='';
			
			document.getElementById('btn_ruta').style.visibility = 'visible';
			
			document.getElementById('unidad').focus();
			
			document.getElementById('aderecha').style.visibility = 'visible';
			document.getElementById('aizquierda').style.visibility = 'visible'
			
			document.getElementById('h_unidad').value = "";
			document.getElementById('h_ruta').value = "";
			document.getElementById('foliobitacora').value = "";
			
			tabla1.clear();
			tabla2.clear();
			tabla3.clear();
			tabla4.clear();
			
			if(datos.indexOf("ok")<0){
				consultaTexto("habilitarFiltro","recepcionMercanciaEspecial_con.php?accion=16&val="+Math.random());
			}
			
		}else if(u.filtro[1].checked==true){
			document.getElementById('paquete').disabled = false;
			document.getElementById('paquete').style.backgroundColor='';
			
			document.getElementById('unidad').disabled = true;
			document.getElementById('unidad').style.backgroundColor='#FFFF99';
			
			document.getElementById('btn_ruta').style.visibility = 'hidden';
			
			document.getElementById('paquete').focus();
			document.getElementById('aderecha').style.visibility = 'hidden';
			document.getElementById('aizquierda').style.visibility = 'hidden';
			
			document.getElementById('h_unidad').value = "";
			document.getElementById('h_ruta').value = "";
			document.getElementById('foliobitacora').value = "";
			
			tabla1.clear();
			tabla2.clear();
			tabla3.clear();
			tabla4.clear();
		
			if(datos.indexOf("ok") < 0){
				consultaTexto("habilitarFiltro","recepcionMercanciaEspecial_con.php?accion=16&val="+Math.random());
			}
		}
	}
	
</script>

</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="650" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="619" class="FondoTabla Estilo4">RECEPCI&Oacute;N DE MERCANCIA ESPECIALES </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="7" align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="3%">&nbsp;</td>
                <td width="12%">Folio:</td>
                <td width="21%"><span class="Tablas">
                  <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
                  <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onclick="abrirVentanaFija('../buscadores_generales/buscarRecepciones.php?funcion=cargarRecepcion&amp;tipo=1', 600, 450, 'ventana', 'Busqueda');" style="cursor:pointer" /></span></td>
                <td width="8%">Fecha:</td>
                <td width="14%"><span class="Tablas">
                  <input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
                </span></td>
                <td width="8%">Sucursal:</td>
                <td width="34%"><span class="Tablas">
                  <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:200px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>
                </span></td>
              </tr>
              <tr>
                <td align="center"><label>
                  <input name="filtro" type="radio" value="0" checked="checked" onclick="habilitarFiltro('1')" />
                </label></td>
                <td>Unidad:</td>
                <td><input name="unidad" class="Tablas" type="text" id="unidad" style="width:100px" value="<?=$unidad ?>" />
                  <span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onclick="abrirVentanaFija('../buscadores_generales/buscarunidadrecepcionespecial.php?funcion=obtenerUnidadBusqueda&amp;tipo=especial', 550, 450, 'ventana', 'Busqueda');" style="cursor:pointer" id="btn_ruta" /></span></td>
                <td>Ruta:</td>
                <td><span class="Tablas">
                  <input name="ruta" type="text" class="Tablas" id="ruta" style="width:80px;background:#FFFF99" value="<?=$ruta ?>" readonly=""/>
                </span></td>
                <td colspan="2"><span class="Tablas">
                  <input name="recorrido" type="text" class="Tablas" id="recorrido" style="width:250px;background:#FFFF99" value="<?=$recorrido ?>" readonly=""/>
                </span></td>
              </tr>
              <tr>
                <td align="center"><input name="filtro" type="radio" value="1" onclick="habilitarFiltro('2')" /></td>
                <td>Paquete:</td>
                <td colspan="5"><span class="Tablas">
                  <input name="paquete" type="text" class="Tablas" id="paquete" style="width:250px; background:#FFFF99" disabled="disabled" onkeypress="if(event.keyCode==13){setTimeout('obtenerPaquete()',500)}"/>
                </span></td>
                </tr>
            </table></td>
          </tr>
          
          
          <tr>
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7"><table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="290"><table border="0" cellpadding="0" cellspacing="0" id="tabladetalleizq">
                  </table></td>
                  <td width="36" align="center"><div class="ebtn_adelante" id="aderecha" onclick="moverAlaDerecha()"></div>
                      <br />
                    <br />
                    <br />
                    <br />
                    <div class="ebtn_atraz" id="aizquierda" onclick="moverAlaizquierda()"></div></td>
                  <td width="290"><table border="0" cellpadding="0" cellspacing="0" id="tabladetalleder">
                  </table></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7"><table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="290"><table border="0" cellpadding="0" cellspacing="0" id="tabladetalleizqabajo">
                  </table></td>
                  <td width="36">&nbsp;</td>
                  <td width="290"><table border="0" cellpadding="0" cellspacing="0" id="tabladetallederabajo">
                  </table></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="7"><table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="290"><div id="div_paginado1" align="center" style="visibility:hidden"> <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion1('primero')" /> <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion1('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion1('adelante')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion1('ultimo')" /> </div>
                      <input type="hidden" name="pag1_total" />
                      <input type="hidden" name="pag1_contador" value="0" />
                      <input type="hidden" name="pag1_adelante" value="" />
                      <input type="hidden" name="pag1_atras" value="" />
                      <input type="hidden" name="pag1_sucursal" value="" />                  </td>
                  <td width="36">&nbsp;</td>
                  <td width="290"><div id="div_paginado2" align="center" style="visibility:hidden"> <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" /> </div>
                      <input type="hidden" name="pag2_total" />
                      <input type="hidden" name="pag2_contador" value="0" />
                      <input type="hidden" name="pag2_adelante" value="" />
                      <input type="hidden" name="pag2_atras" value="" />
                      <input type="hidden" name="pag2_sucursal" value="" />                  </td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7"><table width="315" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="55"><label>
                    <input name="radiobutton" type="radio" value="radiobutton" />
                    </label>
                    Gu&iacute;a
                    </select>
                    </select>
                    </select></td>
                  <td width="112"><span class="Tablas">
                    <input name="guia" type="text" class="Tablas" id="guia" style="width:100px" value="<?=$guia ?>" />
                  </span></td>
                  <td width="132"><select name="select" class="Tablas" style="width:100px" />
                  </select>                  </td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="7"><table width="251" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="87" id="celdimprimir"><div id="d_imprimir" class="ebtn_imprimir" onclick="imprimirRecepcion()" style="visibility:hidden"></div></td>
                  <td width="85" id="celdaguardar"><div id="btn_guardar" class="ebtn_guardar" onclick="guardarValores()"></div></td>
                  <td width="79" ><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiarDatos()')"></div></td>
                </tr>
              </table>
                <input name="estado_hidden" type="hidden" id="estado_hidden" value="<?=$estado ?>" />
                <input name="observaciones_hidden" type="hidden" id="observaciones_hidden" value="<?=$observaciones ?>" />
                <input type="hidden" name="guardado" />
				<input type="hidden" name="h_unidad" id="h_unidad" />
				<input type="hidden" name="h_ruta" id="h_ruta" />
            <input name="foliobitacora" type="hidden" id="foliobitacora" /></td>
          </tr>
          <tr>
            <td colspan="7" align="center"></td>
          </tr>
          <tr>
            <td colspan="7" ></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
