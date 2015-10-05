<? 	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	$s = "DELETE FROM embarquedemercancia_faltante WHERE embarque IS NULL AND idusuario = ".$_SESSION[IDUSUARIO]."";
	mysql_query($s,$link) or die($s);
	
	$fecha = date('d/m/Y');
	//Sucursal
	$suc = @mysql_query("SELECT descripcion,prefijo FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."",$link); 
		$rsuc = @mysql_fetch_array($suc); 
		$sucursal = $rsuc[0];
		$prefijo  =	$rsuc['prefijo'];
	//Folio
	$sql=mysql_query("SELECT CONCAT('$prefijo','-',LPAD((SELECT ifnull(MAX(folio),0)+1 FROM embarquedemercancia WHERE idsucursal = ".$_SESSION[IDSUCURSAL]."),5,'0'))",$link);
		$row=mysql_fetch_array($sql);
		$folio = $row[0];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/ClaseBarraProgreso.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>

<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->

</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<script>
	var u 	   = 	 document.all;
	var tabla1 = new ClaseTabla();	
	var tabla2 = new ClaseTabla();
	var tabla3 = new ClaseTabla();
	var tabla4 = new ClaseTabla();
	var v_incompletas = "";
	var v_index = 0;
	var indice = 0;
	var indice2 = 0;
	var indice3 = 0;
	var indice4 = 0;
	var losdatos = "";
	var losdatos2 = "";
	var losdatos3 = "";
	var losdatos4 = "";
	
	var barra = new ClaseBarraProgreso();
	
		barra.setAttributes({
			nombre:"barraProgreso",
			largo:150,
			inicio:0,
			fin:5000
		});
	
	tabla1.setAttributes({
	nombre:"tabladetalleizq",
	campos:[
			{nombre:"SECTOR", medida:50, alineacion:"center", datos:"sector"},
			{nombre:"No_GUIA", medida:75, alineacion:"center", datos:"guia"},
			{nombre:"ORIGEN", medida:75, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:75, alineacion:"center", datos:"destino"},
			{nombre:"PESO", medida:4, tipo:"oculto", alineacion:"center", datos:"peso"}
		],
		filasInicial:14,
		alto:180,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ObtDetalleIzq()",
		nombrevar:"tabla1"
	});
	tabla2.setAttributes({
	nombre:"tabladetalleder",
	campos:[
			{nombre:"SECTOR", medida:50, alineacion:"center", datos:"sector"},
			{nombre:"No_GUIA", medida:75, alineacion:"center", datos:"guia"},
			{nombre:"ORIGEN", medida:75, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:75, alineacion:"center", datos:"destino"},
			{nombre:"PESO", medida:4, tipo:"oculto", alineacion:"center", datos:"peso"},
			{nombre:"PAGINA", medida:40, tipo:"oculto", alineacion:"center", datos:"pagina"}
		],
		filasInicial:14,
		alto:180,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ObtDetalleDer()",
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({
	nombre:"tabladetalleizqabajo",
	campos:[
			{nombre:"REGISTRO", medida:70, alineacion:"center", datos:"registro"},
			{nombre:"PAQUETE", medida:40, alineacion:"center", datos:"paquete"},
			{nombre:"CODIGO_DE_BARRAS", medida:70, alineacion:"center", datos:"codigobarras"},
			{nombre:"ESTADO", medida:86, alineacion:"center", datos:"estado"},
			{nombre:"GUIA", medida:4, tipo:"oculto", alineacion:"center", datos:"guia"},
			{nombre:"PESO", medida:4, tipo:"oculto", alineacion:"center", datos:"peso"}
		],
		filasInicial:7,
		alto:80,
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
			{nombre:"CODIGO_DE_BARRAS", medida:70, alineacion:"center", datos:"codigobarras"},
			{nombre:"ESTADO", medida:86, alineacion:"center", datos:"estado"},
			{nombre:"GUIA", medida:4, tipo:"oculto", alineacion:"center", datos:"guia"},
			{nombre:"PESO", medida:4, tipo:"oculto", alineacion:"center", datos:"peso"}
		],
		filasInicial:7,
		alto:80,
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
		barra.create();
		//obtenerDetalles();
	}
	
	function moverAlaDerecha(){
		if(tabla1.getRecordCount()==""){
			alerta3('No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			if(tabla1.getSelectedIdRow()!=""){
				document.getElementById('aderecha').style.visibility='hidden';
				consultaTexto("resMovADerC","embarquedemercancia_con30.php?accion=4&folio="+tabla1.getSelectedRow().guia+"&sid="+Math.random());
			}else if(tabla3.getSelectedIdRow()!=""){
				document.getElementById('aderecha').style.visibility='hidden';
				consultaTexto("resMovADerU","embarquedemercancia_con30.php?accion=5&folio="+tabla3.getSelectedRow().guia
					+"&registro="+tabla3.getSelectedRow().registro+"&random="+Math.random());
			}else{
				alerta3('Debe seleccionar la fila que desea mover a la derecha','¡Atención!');	
			}
		}
	}
	
	function resMovADerC(datos){
		barra.setValue(parseFloat(datos.split(",")[1]));
		if(datos.indexOf("guardado")>-1){
			var folios = tabla2.getValuesFromField("guia");
			var folio  = tabla1.getValSelFromField("guia","No_GUIA");
			var nPagina = ((document.getElementById('pagina1').innerHTML.split("-")[0]=="")? 1 : document.getElementById('pagina1').innerHTML.split("-")[0]);
			
			var v_obj = tabla1.getSelectedRow();
			var obj = Object();
			obj.sector = v_obj.sector;
			obj.guia = v_obj.guia;
			obj.origen = v_obj.origen;
			obj.destino = v_obj.destino
			obj.peso = v_obj.peso;
			obj.pagina = nPagina;
			
			if(folios.indexOf(folio)<0){
				//tabla2.add(tabla1.getSelectedRow());
				tabla2.add(obj);
			}
			tabla1.deleteById(tabla1.getSelectedIdRow());
			tabla3.clear();
			if(tabla1.getRecordCount()>0){
				tabla1.setSelectedById("tabladetalleizq_id0");
			}
			
			var filas = tabla1.getSelectedIndex();
			delete losdatos[nPagina-1].izquierda[filas];
			losdatos[nPagina-1].izquierda.sort(function(a,b){return a - b});
			
			var t = losdatos[nPagina-1]['izquierda'].slice(0,losdatos[nPagina-1]['izquierda'].length - 1);
			losdatos[nPagina-1]['izquierda'] = t;
			
			//buscarEnDer(folio);
		}
		document.getElementById('aderecha').style.visibility='visible';
	}
	function resMovADerU(datos){
		barra.setValue(parseFloat(datos.split(",")[1]));
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
		
		document.getElementById('aderecha').style.visibility='visible';
	}
	
	function moverAlaizquierda(){
		if(tabla2.getRecordCount()==""){
			alerta3('No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			if(tabla2.getSelectedIdRow()!=""){
				document.getElementById('aizquierda').style.visibility='hidden';
				consultaTexto("resMovAIzqC","embarquedemercancia_con30.php?accion=6&folio="+tabla2.getSelectedRow().guia+"&sid="+Math.random());
			}else if(tabla4.getSelectedIdRow()!=""){
				document.getElementById('aizquierda').style.visibility='hidden';
				consultaTexto("resMovAIzqU","embarquedemercancia_con30.php?accion=7&folio="+tabla4.getSelectedRow().guia
					+"&registro="+tabla4.getSelectedRow().registro+"&random="+Math.random());
			}else{
				alerta3('Debe seleccionar la fila que desea mover a la derecha','¡Atención!');	
			}
		}
	}
	
	function resMovAIzqC(datos){
		barra.setValue(parseFloat(datos.split(",")[1]));
		if(datos.indexOf("guardado")>-1){
			var folios = tabla1.getValuesFromField("guia");
			var folio  = tabla2.getValSelFromField("guia","No_GUIA");
			var v_obj = tabla2.getSelectedRow();
			var obj = Object();
			obj.sector = v_obj.sector;
			obj.guia = v_obj.guia;
			obj.origen = v_obj.origen;
			obj.destino = v_obj.destino;
			obj.peso = v_obj.peso;
			//obj.pagina = v_obj.pagina;
			document.getElementById('aizquierda').style.visibility='visible';
			
			tabla2.deleteById(tabla2.getSelectedIdRow());
			tabla4.clear();

			/*if(v_obj.pagina > 1){
				v_obj.pagina = v_obj.pagina - 1;
				//siguiente1();
				tabla1.setJsonData(losdatos[v_obj.pagina]['izquierda']);
			}else{
				v_obj.pagina = v_obj.pagina - 1;
				//anterior1();
				tabla1.setJsonData(losdatos[v_obj.pagina]['izquierda']);
			}*/
			if(v_obj.pagina == 1){
				losdatos[v_obj.pagina-1]['izquierda'][losdatos[v_obj.pagina-1].izquierda.length] = obj;
			}else{
				losdatos[v_obj.pagina-1]['izquierda'][losdatos[v_obj.pagina-1].izquierda.length - 1] = obj;
			}
			losdatos[v_obj.pagina-1].izquierda.sort(function(a,b){return a - b});
			if(folios.indexOf(folio)<0){
				tabla1.add(obj);
			}
			//alert(losdatos[v_obj.pagina-1]['izquierda']);
			//buscarEnIzq(folio);
		}
	}
	
	function resMovAIzqU(datos){
		barra.setValue(parseFloat(datos.split(",")[1]));
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
			document.getElementById('aizquierda').style.visibility='visible';			
			buscarEnIzq(folio);
		}
	}
	

/*******************/
	function ObtDetalleIzq(){
		tabla3.setSelectedById("");
		if(tabla1.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarIzqAbajo","embarquedemercancia_con30.php?accion=2&folio="+tabla1.getValSelFromField('guia','No_GUIA')+"&sid="+Math.random());
		}
	}
	function mostrarIzqAbajo(datos){
		losdatos3 = eval(datos);
		tabla3.setJsonData(losdatos3[indice3]['datos']);
		if(losdatos3.length>1){
			document.getElementById('paginado_grid3').style.display='';
			ponerPagina3();
		}
	}
/****************/
	function ObtDetalleDer(){
		tabla4.setSelectedById("");
		if(tabla2.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarDerAbajo","embarquedemercancia_con30.php?accion=3&folio="+tabla2.getValSelFromField('guia','No_GUIA')+"&sid="+Math.random());
		}
	}
	function mostrarDerAbajo(datos){
			losdatos4 = eval(datos);
			tabla4.setJsonData(losdatos4[indice4]['datos']);
			if(losdatos4.length>1){
				document.getElementById('paginado_grid4').style.display='';
				ponerPagina4();
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
		consulta("mostrarUnidadBusqueda","consultasembarque.php?unidad="+unidad+"&accion=1&sid="+Math.random());
	}
	function mostrarUnidadBusqueda(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			var u = document.all;
			if(con>0){
				u.unidad.value		=datos.getElementsByTagName('unidad').item(0).firstChild.data;
				u.ruta.value		=datos.getElementsByTagName('ruta').item(0).firstChild.data;
				u.recorrido.value	=datos.getElementsByTagName('descripcion').item(0).firstChild.data;
				u.foliobitacora.value=datos.getElementsByTagName('folio').item(0).firstChild.data;
				u.destino.value		=datos.getElementsByTagName('destino').item(0).firstChild.data;
				barra.setEnd(datos.getElementsByTagName('capacidad').item(0).firstChild.data);
				//tabla1.setXML(datos);
				if(datos.getElementsByTagName('total').item(0).firstChild.data>0){
					alerta3('No ha recepcionado todos los paquetes de esta unidad.<br>Para poder embarcar recepcione todos los paquetes ','¡Atención!');
				}else{
					CargarGuias();
					tabla2.clear();
					tabla3.clear();
					tabla4.clear();
				}
			}else{
				alerta3("La unidad seleccionada no ha sido registrada en una Bitácora de Salida",'¡Atención!');
					u.unidad.value	="";
					u.ruta.value	="";
					u.recorrido.value="";
					tabla1.clear();
					tabla2.clear();
					tabla3.clear();
					tabla4.clear();
			}
	}
/********************/
	function CargarGuias(){
		
		consultaTexto("mostrarCargarGuias","embarquedemercancia_con30.php?accion=1&unidad="+document.all.unidad.value+"&sid="+Math.random());
	}
	function mostrarCargarGuias(datos){
		try{		
			losdatos = eval(convertirValoresJson(datos));
			barra.setValue(0);
			tabla1.setJsonData(losdatos[indice]['izquierda']);
			//tabla2.setJsonData(losdatos[indice]['derecha']);			
			if(losdatos.length>1){
				document.getElementById('paginado_grid1').style.display='';
				ponerPagina1();
			}			
		}catch(e){
			alerta3(datos,"");
		}
	}
/********************/
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

/*****/
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
/********************/
	function foco(nombrecaja){
		if(nombrecaja=="unidad"){
			u.oculto.value="1";
		}
	}
	shortcut.add("Ctrl+b",function() {
		if(u.oculto.value=="1"){
		abrirVentanaFija('../buscadores_generales/buscarUnidadGen.php?funcion=obtenerUnidadBusqueda', 550, 450, 'ventana', 'Busqueda');
		}
	});

	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57));
	}
	function validar(){
		document.all.registros.value = tabla1.getRecordCount();
		document.all.accion.value="grabar";
		document.form1.submit();
	}
	
	function limpiarDatos(){
		var u = document.all;
		u.unidad.value	="";
		u.ruta.value	="";
		u.recorrido.value="";
		u.foliobitacora.value="";
		tabla1.clear();
		tabla2.clear();
		tabla3.clear();
		tabla4.clear();
		u.guia.value	="";
		u.guia.diseable	=true;
		u.d_guardar.style.visibility = "visible";
		u.d_imprimir.style.visibility = "hidden";
		document.form1.submit();
		//document.getElementById('celdaGuardar').style.display="";
		//consultaTexto("ponerIniciado","embarquedemercancia_con30.php?accion=11");
	}
	
	function ponerIniciado(datos){
		var objeto = eval(datos)
		document.getElementById("folio").value = objeto.folio;
		document.getElementById("fecha").value = objeto.fecha;
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
		alerta3("No se encontro "+((u.buscar.value==0)?"la guia buscada":"el codigo buscado"));
	}

	function guardarValores(){
		<?=$cpermiso->verificarPermiso(308,$_SESSION[IDUSUARIO]);?>;
		if(u.ruta.value==""){
			alerta("Por favor proporcione unidad","¡Atencion!","unidad");
			return false;
		}
		/*if(barra.getPorcent()>100){
			alerta3("Ha excedido la capacidad del transporte","¡Atencion!");
			//return false;
		}*/

		if(tabla2.getRecordCount()>0 && u.guardado.value == 0){
			u.d_guardar.style.visibility = "hidden";
			consultaTexto("resValidar","embarquedemercancia_con30.php?accion=8&mathrand="+Math.random());
		}else{
			alerta3("No hay ningun registro a guardar");
			return false;
		}
	}
	function resValidar(datos){
		if(datos.indexOf("ok")>-1){
			guardarFinal();			
		}else{
			var objeto = eval(datos);
			v_index = 0;
			var mensaje = "";
			for(var i=0; i<objeto.length; i++){
				mensaje += objeto[i].guia+",";
			}
			v_incompletas = mensaje.substr(0,mensaje.length-1);
			v_incompletas = v_incompletas.split(",");
			u.d_guardar.style.visibility = "visible";
			mensaje = mensaje.substring(0,mensaje.length-1);
			confirmar("Existen guias incompletas:<br>"+mensaje+"<br> se hara un reporte de faltantes¿Desea continuar?","¡Atencion!","mostrarLogueo()");
		}
	}
	
	function mostrarLogueo(){
		abrirVentanaFija("../buscadores_generales/logueo_permisos.php?funcion=mostrarGuiaArreglo&modulo=GuiaVentanilla&usuario=Admin",500,400,"ventana","DATOS PERSONALES")	
	}
	
	function guardarFinal(){
		var unidad		= u.unidad.value;
		var ruta		= u.ruta.value;
		var recorrido	= u.recorrido.value;
		var foliobitacora=u.foliobitacora.value;
		var v_fecha		= fechahora(v_fecha);
		consultaTexto("resGuardar","embarquedemercancia_con30.php?accion=9&unidad="+unidad
		+"&ruta="+ruta+"&tipoembarque=NORMAL&foliobitacora="+foliobitacora
		+"&mathrand="+Math.random()+"&fechahora="+v_fecha);
	}
	
	function resGuardar(datos){
		if(datos.indexOf("Las siguientes guias")>-1){
			alerta3(datos,"¡Atencion!");
			//u.d_imprimir.style.visibility = "visible";
		}else if(datos.indexOf("guardado")>-1){
			var row = datos.split(",");
			info("La informacion ha sido guardada","");
			document.getElementById('celdaGuardar').style.display="none";
			u.d_imprimir.style.visibility = "visible";
			u.guardado.value = 1;
			u.folio.value = row[1];
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3("Hubo un error "+datos,"¡Atencion!");
		}
	}
	
	function mostrarGuiaArreglo(){
		abrirVentanaFija("reporteFaltante.php?guia="+v_incompletas[v_index]+"&bitacora="+u.foliobitacora.value
		+"&indice="+v_index, 600, 480, 'ventana', 'REPORTE DE FALTANTES');
			if(v_incompletas[v_index]==undefined){
				VentanaModal.cerrar();
				info("Se han registrado las guias con faltantes","¡Atencion!");
				guardarFinal();
			}
			v_index++;
	}	
	
	function imprimirEmbarque(){
		if(u.guardado.value == 1){		
			if(document.URL.indexOf("dbserver:8080/")>-1){			
				window.open("http://dbserver:8080/web_capacitacion/fpdf/reportes/relacionEmbarque.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&unidad="+u.unidad.value+"&fechaembarque="+u.fecha.value+"&destino="+u.destino.value);
			}else if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/fpdf/reportes/relacionEmbarque.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&unidad="+u.unidad.value+"&fechaembarque="+u.fecha.value+"&destino="+u.destino.value);
			
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/relacionEmbarque.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&unidad="+u.unidad.value+"&fechaembarque="+u.fecha.value+"&destino="+u.destino.value);
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/relacionEmbarque.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&unidad="+u.unidad.value+"&fechaembarque="+u.fecha.value+"&destino="+u.destino.value);
			}
			/*consultaTexto("resFaltantes","embarquedemercancia_con30.php?accion=13&folio="+
						  u.folio.value+"&random="+Math.random());*/
		}else{
			alerta3("Debe guardar el embarque para poder imprimir el reporte","¡Atencion!");
		}
	}
	
	function resFaltantes(valor){
		var objeto = eval(valor);
		/*if(objeto.folio != ""){
			if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/tcpdf/reportes/faltantesEnEmbarque.php?folioembarque="+
						objeto.folio+"&sucursal="+objeto.sucursal);
			
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
				window.open("http://www.pmmintranet.net/web_capacitacion/tcpdf/reportes/faltantesEnEmbarque.php?folioembarque="+
						objeto.folio+"&sucursal="+objeto.sucursal);
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("http://pmmintranet.net/web_pruebas/tcpdf/reportes/faltantesEnEmbarque.php?folioembarque="+
						objeto.folio+"&sucursal="+objeto.sucursal);
			}
		}*/
	}
	
	function cargarEmbarque(folio){
		consultaTexto("resCargarEmbarque","embarquedemercancia_con30.php?accion=10&folio="+folio+"&random="+Math.random());
	}
	
	function resCargarEmbarque(datos){
		//alert(datos);
		try{
			var objeto = eval(datos);
		}catch(e){
			alerta3("A",datos);
		}
		
		tabla2.setJsonData(objeto.derecha);
		
		/*losdatos2 = eval(convertirValoresJson(datos));
			barra.setValue(0);
			tabla1.setJsonData(losdatos[indice]['izquierda']);
			//tabla2.setJsonData(losdatos[indice]['derecha']);			
			if(losdatos.length>1){
				document.getElementById('paginado_grid1').style.display='';
				ponerPagina1();
			}*/
		
		document.getElementById('celdaGuardar').style.display="none";
		document.getElementById('unidad').value=objeto.datosembarque.unidad;
		document.getElementById('ruta').value=objeto.datosembarque.id;
		document.getElementById('recorrido').value=objeto.datosembarque.descripcion;
		document.getElementById('fecha').value=objeto.datosembarque.fecha;
		document.getElementById('folio').value=objeto.datosembarque.folio;
		document.getElementById('destino').value=objeto.datosembarque.destino;
		document.getElementById('aderecha').style.display="none";
		document.getElementById('aizquierda').style.display="none";
		//u.d_imprimir.style.visibility = "visible";
		//u.guardado.value=1;
	}
	
	function obtenerFaltante(){
		var v_guia = "";
		var v_faltante = "";
		for(var i=0;i<tabla1.getRecordCount();i++){
			v_guia += u["tabladetalleizq_No_GUIA"][i].value+",";
		}
		
		v_guia = v_guia.substring(0,v_guia.length-1);
		v_guia = v_guia.split(",");
		for(var i=0;i<v_guia.length;i++){
			for(var j=0;j<tabla2.getRecordCount();j++){
				if(u["tabladetalleder_No_GUIA"][j].value==v_guia[i]){
					v_faltante += v_guia[i]+",";
				}
			}
		}
		v_faltante = ((v_faltante!="") ? v_faltante.substring(0,v_faltante.length-1) : v_faltante);
		return v_faltante;
	}
	
	/*********************TABLA IZQUIERDA ARRIBA***********************/
	function siguiente1(){	
		if(losdatos[indice+1]!=null){
			tabla1.setJsonData(losdatos[indice+1]['izquierda']);
			indice++;
		}else
			alerta3("Esta en el ultimo registro","¡Atención!");
			ponerPagina1();
	}
	
	function anterior1(){
		if(indice-1>-1){
			indice--;			
			tabla1.setJsonData(losdatos[indice]['izquierda']);
		}else
			alerta3("Esta en el primer registro","¡Atención!");
			ponerPagina1();
	}
	
	function ultimo1(){
		indice = losdatos.length-1;
		tabla1.setJsonData(losdatos[indice]['izquierda']);
		ponerPagina1();
	}
	
	function primero1(){
		indice = 0;
		tabla1.setJsonData(losdatos[indice]['izquierda']);
		ponerPagina1();
	}
	
	function ponerPagina1(){
		document.getElementById('pagina1').innerHTML=(indice+1)+"-"+(losdatos.length);
	}
	
	/*********************TABLA DERECHA ARRIBA***********************/
	function siguiente2(){
		if(losdatos2[indice2+1]!=null){			
			tabla2.setJsonData(losdatos2[indice2+1]['derecha']);
			indice2++;
		}else
			alerta3("Esta en el ultimo registro","¡Atención!");
			ponerPagina2();
	}
	
	function anterior2(){
		if(indice2-1>-1){
			indice2--;
			tabla2.setJsonData(losdatos2[indice2]['derecha']);			
		}else
			alerta3("Esta en el primer registro","¡Atención!");
			ponerPagina2();
	}
	
	function ultimo2(){
		indice2 = losdatos2.length-1;
		tabla2.setJsonData(losdatos2[indice2]['derecha']);
		ponerPagina2();
	}
	
	function primero2(){
		indice2 = 0;
		tabla2.setJsonData(losdatos2[indice2]['derecha']);
		ponerPagina2();
	}
	
	function ponerPagina2(){
		document.getElementById('pagina2').innerHTML=(indice2+1)+"-"+(losdatos2.length);
	}
	
	/****************TABLA IZQUIERDA ABAJO***********************/
	function siguiente3(){
		if(losdatos3[indice3+1]!=null){
			tabla3.setJsonData(losdatos3[indice3+1]['datos']);
			indice3++;
		}else
			alerta3("Esta en el ultimo registro","¡Atención!");
			ponerPagina3();
	}
	
	function anterior3(){
		if(indice3-1>-1){
			indice3--;
			tabla3.setJsonData(losdatos3[indice3]['datos']);			
		}else
			alerta3("Esta en el primer registro","¡Atención!");
			ponerPagina3();
	}
	
	function ultimo3(){
		indice3 = losdatos3.length-1;
		tabla3.setJsonData(losdatos3[indice3]['datos']);
		ponerPagina3();
	}
	
	function primero3(){
		indice3 = 0;
		tabla3.setJsonData(losdatos3[indice3]['datos']);
		ponerPagina3();
	}
	
	function ponerPagina3(){
		document.getElementById('pagina3').innerHTML=(indice3+1)+"-"+(losdatos3.length);
	}
	
	/****************TABLA DERECHA ABAJO***********************/
	function siguiente4(){
		if(losdatos4[indice4+1]!=null){			
			tabla4.setJsonData(losdatos4[indice4+1]['datos']);
			indice4++;
		}else
			alerta3("Esta en el ultimo registro","¡Atención!");
			ponerPagina4();
	}
	
	function anterior4(){
		if(indice4-1>-1){
			indice4--;
			tabla4.setJsonData(losdatos4[indice4]['datos']);			
		}else
			alerta3("Esta en el primer registro","¡Atención!");
			ponerPagina4();
	}
	
	function ultimo4(){
		indice4 = losdatos4.length-1;
		tabla4.setJsonData(losdatos4[indice4]['datos']);
		ponerPagina4();
	}
	
	function primero4(){
		indice4 = 0;
		tabla4.setJsonData(losdatos4[indice4]['datos']);
		ponerPagina4();
	}
	
	function ponerPagina4(){
		document.getElementById('pagina4').innerHTML=(indice4+1)+"-"+(losdatos4.length);
	}
</script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">EMBARQUE DE MERCANC&Iacute;A</td>
  </tr>
  <tr>
    <td><table width="618" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><table width="618" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="119"></td>
            <td width="33">Folio</td>
            <td width="111"><span class="Tablas">
              <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
            </span></td>
            <td width="58"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarFoliosEmbarque.php?funcion=cargarEmbarque&tipo=1', 600, 450, 'ventana', 'Busqueda');"></div></td>
            <td width="30">Fecha</td>
            <td width="105"><span class="Tablas">
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
            </span></td>
            <td width="43">Sucursal</td>
            <td width="119"><span class="Tablas">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
        <table width="618" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="35" onclick="obtenerFaltante()">Unidad</td>
            <td width="100"><span class="Tablas">
              <input name="unidad" type="text" class="Tablas" id="unidad" style="width:100px" value="<?=$unidad ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onkeydown="if(event.keyCode==8){document.all.ruta.value='';document.all.recorrido.value='';}"  onkeypress="if(event.keyCode==13){/*obtenerUnidadBusqueda(this.value)*/}" />
            </span></td>
            <td width="483"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarUnidadGenRecEmb.php?funcion=obtenerUnidadBusqueda&tipo=embarques', 600, 500, 'ventana', 'Busqueda');"></div><input type="hidden" name="guardado" />
              <input name="foliobitacora" type="hidden" id="foliobitacora" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="618" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="34">Ruta</td>
            <td width="100"><span class="Tablas">
              <input name="ruta" type="text" class="Tablas" id="ruta" style="width:100px;background:#FFFF99" value="<?=$ruta ?>" readonly=""/>
            </span></td>
            <td width="49">Recorrido</td>
            <td width="407"><span class="Tablas">
              <input name="recorrido" type="text" class="Tablas" id="recorrido" style="width:250px;background:#FFFF99" value="<?=$recorrido ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="618" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td></td>
              <td align="center">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
                  <td width="298" height="66"><table border="0" cellpadding="0" cellspacing="0" id="tabladetalleizq" >
                            </table></td>
              <td width="24" align="center"><div id="aderecha" class="ebtn_adelante" onclick="moverAlaDerecha()"></div><BR /><BR /><div id="aizquierda" class="ebtn_atraz" onclick="moverAlaizquierda()"></div></td>
                  <td width="296">
                    <table border="0" cellpadding="0" cellspacing="0"  id="tabladetalleder">
                    </table>
                  </td>
            </tr>
			<tr>
				<td>
				<div id="paginado_grid1" align="center" style="display:; width:100%; height:15px;">
					<table width="162" border="0" cellpadding="0" cellspacing="0">
						<tr>
						<td>
							  <img src="../img/first.gif" name="d_primero" width="16" height="16" style="cursor:pointer"  onclick="primero1()" /> 
							  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" style="cursor:pointer" onclick="anterior1()" /> 
					 	 </td>
					  <td>
					  <div style="position:relative; width:50px; height:15px; text-align:center" id="pagina1"></div>
					  </td>
					 	 <td>
							  <img src="../img/next.gif" name="d_sigdes" width="16" height="16" style="cursor:pointer" onclick="siguiente1()" /> 
							  <img src="../img/last.gif" name="d_ultimo" width="16" height="16" style="cursor:pointer" onclick="ultimo1()" />
					 	 </td>
					  </tr>
				  </table>
				</div>
                
				</td>
				<td>&nbsp;</td>
				<td>
					<div id="paginado_grid2" align="center" style="visibility:hidden; width:100%; height:15px;">
					<table width="162" border="0" cellpadding="0" cellspacing="0">
						<tr>
						<td>
							  <img src="../img/first.gif" name="d_primero" width="16" height="16" style="cursor:pointer"  onclick="primero2()" /> 
							  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" style="cursor:pointer" onclick="anterior2()" /> 
					 	 </td>
					  <td>
					  <div style="position:relative; width:50px; height:15px; text-align:center" id="pagina2"></div>
					  </td>
					 	 <td>
							  <img src="../img/next.gif" name="d_sigdes" width="16" height="16" style="cursor:pointer" onclick="siguiente2()" /> 
							  <img src="../img/last.gif" name="d_ultimo" width="16" height="16" style="cursor:pointer" onclick="ultimo2()" />
					 	 </td>
					  </tr>
				  </table>
				</div>
				</td>
			</tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="618" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3" align="right">
            <table width="265" border="0" cellpadding="0" cellspacing="0"><tr><td align="left">Capacidad:<div id="barraProgreso"></div></td></tr></table>
            <br /></td>
            </tr>
          <tr>
                  <td width="298"><table border="0" cellpadding="0" cellspacing="0" id="tabladetalleizqabajo">
                  </table></td>
            <td width="24">&nbsp;</td>      <td width="296"><table border="0" cellpadding="0" cellspacing="0" id="tabladetallederabajo"></table></td>
          </tr>
		  <tr>
		  	<td><div id="paginado_grid3" align="center" style="display:; width:100%; height:15px;">
					<table width="162" border="0" cellpadding="0" cellspacing="0">
						<tr>
						<td>
							  <img src="../img/first.gif" name="d_primero" width="16" height="16" style="cursor:pointer"  onclick="primero3()" /> 
							  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" style="cursor:pointer" onclick="anterior3()" /> 
					 	 </td>
					  <td>
					  <div style="position:relative; width:50px; height:15px; text-align:center" id="pagina3"></div>
					  </td>
					 	 <td>
							  <img src="../img/next.gif" name="d_sigdes" width="16" height="16" style="cursor:pointer" onclick="siguiente3()" /> 
							  <img src="../img/last.gif" name="d_ultimo" width="16" height="16" style="cursor:pointer" onclick="ultimo3()" />
					 	 </td>
					  </tr>
				  </table>
				</div></td>
			<td>&nbsp;</td>
			<td><div id="paginado_grid4" align="center" style="display:; width:100%; height:15px;">
					<table width="162" border="0" cellpadding="0" cellspacing="0">
						<tr>
						<td>
							  <img src="../img/first.gif" name="d_primero" width="16" height="16" style="cursor:pointer"  onclick="primero4()" /> 
							  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" style="cursor:pointer" onclick="anterior4()" /> 
					 	 </td>
					  <td>
					  <div style="position:relative; width:50px; height:15px; text-align:center" id="pagina4"></div>
					  </td>
					 	 <td>
							  <img src="../img/next.gif" name="d_sigdes" width="16" height="16" style="cursor:pointer" onclick="siguiente4()" /> 
							  <img src="../img/last.gif" name="d_ultimo" width="16" height="16" style="cursor:pointer" onclick="ultimo4()" />
					 	 </td>
					  </tr>
				  </table>
				</div></td>
		  </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="315" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55">
            Buscar
            </td>
            <td width="112"><span class="Tablas">
              <input name="guia" type="text" class="Tablas" id="guia" style="width:100px" value="<?=$guia ?>" onKeyPress="if(event.keyCode==13){buscarFolio();}" />
            </span></td>
            <td width="132"><select name="buscar" class="Tablas" style="width:140px" >
            <option value="0">GUIA</option>
            <option value="1">CODIGO DE BARRAS</option>
            </select>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center"><input name="accion" type="hidden" id="accion" />
              <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />
              <input name="registros" type="hidden" id="registros" />
              <input name="prefijo" type="hidden" id="prefijo" />
              <input name="destino" type="hidden" id="destino" />
              <table border="0" cellpadding="0" cellspacing="0">
          <tr>
          <td width="76" id="celdaImprimir"><div id="d_imprimir" class="ebtn_imprimir" onClick="imprimirEmbarque()" style="visibility:hidden"></div></td>
          <td width="78" id="celdaGuardar"><div id="d_guardar" class="ebtn_guardar" onClick="guardarValores()"></div></td>
          <td width="70" ><div class="ebtn_nuevo" onClick="confirmar('¿Desea limpiar los datos?','¡Atencion!','limpiarDatos()','')"></div></td>
          </tr>
          </table>
            </td>
      </tr>
    </table>
    </tr>
</table>
</form>
</body>
</html>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="http://pmmintranet.net/web/activexs/smsx.cab#Version=6,5,439,30">
</object>
<script> 
	function enviarImpresion(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = false;
		factory.printing.leftMargin = 2.0;
		factory.printing.topMargin = 5.0;
		factory.printing.rightMargin = 1.0;
		factory.printing.bottomMargin = 1.0;
	  	factory.printing.Print(false);
		opener.cambiarImpresora1();
		window.close();
	}
	
</script>