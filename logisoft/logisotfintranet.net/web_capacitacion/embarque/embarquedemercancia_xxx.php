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
<script src="../javascript/jquery-1.4.js" language="javascript"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/DataSetSinFiltro.js"></script>
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
	
	var DS1 = new DataSet();	
	var DS2 = new DataSet();
	var DS3 = new DataSet();
	var DS4 = new DataSet();
	
	var v_incompletas = "";
	var v_index = 0;
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
			{nombre:"PESO", medida:4, tipo:"oculto", alineacion:"center", datos:"peso"}
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
		
		DS1.crear({
			'paginasDe':30,
			'objetoTabla':tabla1,
			'objetoPaginador':document.getElementById('tablaArribaIzq_pag'),
			'nombreVariable':'DS1',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
				parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
			}
		});
		DS2.crear({
			'paginasDe':30,
			'objetoTabla':tabla2,
			'objetoPaginador':document.getElementById('tablaAbajoIzq_pag'),
			'nombreVariable':'DS2',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
				parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
			}
		});
		DS3.crear({
			'paginasDe':30,
			'objetoTabla':tabla3,
			'objetoPaginador':document.getElementById('tablaArribaDer_pag'),
			'nombreVariable':'DS3',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
				parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
			}
		});
		DS4.crear({
			'paginasDe':30,
			'objetoTabla':tabla4,
			'objetoPaginador':document.getElementById('tablaAbajoDer_pag'),
			'nombreVariable':'DS4',
			'ubicacion':'../',
			'funcionOrdenar':function(a,b){
				return parseInt(a.guia.toString().substring(0,12)+a.guia.toString().charCodeAt(12))-
				parseInt(b.guia.toString().substring(0,12)+b.guia.toString().charCodeAt(12))
			}
		});
	}
	
	function obtenerDetalles(){
		var datosTabla1 = <? if($detalle1!=""){echo "[".$detalle1."]";}else{echo "0";} ?>;
			if(datosTabla1!=0){			
				for(var i=0; i<datosTabla1.length;i++){
					tabla1.add(datosTabla1[i]);
				}
			}
		var datosTabla2 = <? if($detalle2!=""){echo "[".$detalle2."]";}else{echo "0";} ?>;
			if(datosTabla2!=0){			
				for(var i=0; i<datosTabla2.length;i++){
					tabla2.add(datosTabla2[i]);
				}
			}
	
	}
	
	function moverAlaDerecha(){
		if(tabla1.getRecordCount()==""){
			alerta3('No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			if(tabla1.getSelectedIdRow()!=""){
				document.getElementById('aderecha').style.visibility='hidden';
				consultaTexto("resMovADerC","embarquedemercancia_con.php?accion=4&idpagina="+u.idpagina.value+
							  "&folio="+tabla1.getSelectedRow().guia+"&sid="+Math.random());
			}else if(tabla3.getSelectedIdRow()!=""){
				document.getElementById('aderecha').style.visibility='hidden';
				consultaTexto("resMovADerU","embarquedemercancia_con.php?accion=5&idpagina="+u.idpagina.value+
							  "&folio="+tabla3.getSelectedRow().guia
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
			if(folios.indexOf(folio)<0){
				tabla2.add(tabla1.getSelectedRow());
			}
			tabla1.deleteById(tabla1.getSelectedIdRow());
			tabla3.clear();
			if(tabla1.getRecordCount()>0){
				tabla1.setSelectedById("tabladetalleizq_id0");
			}
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
				consultaTexto("resMovAIzqC","embarquedemercancia_con.php?accion=6&idpagina="+u.idpagina.value+
							  "&folio="+tabla2.getSelectedRow().guia+"&sid="+Math.random());
			}else if(tabla4.getSelectedIdRow()!=""){
				consultaTexto("resMovAIzqU","embarquedemercancia_con.php?accion=7&idpagina="+u.idpagina.value+
							  "&folio="+tabla4.getSelectedRow().guia
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
			if(folios.indexOf(folio)<0)
				tabla1.add(tabla2.getSelectedRow());
			tabla2.deleteById(tabla2.getSelectedIdRow());
			tabla4.clear();
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
			
			buscarEnIzq(folio);
		}
	}
	

/*******************/
	function ObtDetalleIzq(){
		tabla3.setSelectedById("");
		if(tabla1.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarIzqAbajo","embarquedemercancia_con.php?accion=2&idpagina="+u.idpagina.value+
							  "&folio="+tabla1.getValSelFromField('guia','No_GUIA')+"&sid="+Math.random());
		}
	}
	function mostrarIzqAbajo(datos){
			var objeto = eval(datos);
			tabla3.setJsonData(objeto);
	}
/****************/
	function ObtDetalleDer(){
		tabla4.setSelectedById("");
		if(tabla2.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarDerAbajo","embarquedemercancia_con.php?accion=3&idpagina="+u.idpagina.value+
							  "&folio="+tabla2.getValSelFromField('guia','No_GUIA')+"&sid="+Math.random());
		}
	}
	function mostrarDerAbajo(datos){
			var objeto = eval(datos);
			tabla4.setJsonData(objeto);
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
		consultaTexto("mostrarCargarGuias","embarquedemercancia_con.php?accion=1&idpagina="+u.idpagina.value+
							  "&unidad="+document.all.unidad.value+"&sid="+Math.random());
	}
	function mostrarCargarGuias(datos){
		try{
			var objeto = eval(convertirValoresJson(datos));
			barra.setValue(0);
			DS1.setJsonData(objeto.izquierda);
			DS2.setJsonData(objeto.derecha);
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
		//consultaTexto("ponerIniciado","embarquedemercancia_con.php?accion=11");
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
			consultaTexto("resValidar","embarquedemercancia_con.php?accion=8&idpagina="+u.idpagina.value+
							  "&mathrand="+Math.random());
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
			//u.d_guardar.style.visibility = "visible";
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
		/*$.ajax({
		   type: "GET",
		   url: "embarquedemercancia_con.php",
		   data: "accion=9&unidad="+unidad
		+"&ruta="+ruta+"&tipoembarque=NORMAL&folios="+tabla2.getValuesFromField('guia')+"&foliobitacora="+foliobitacora
		+"&mathrand="+Math.random()+"&fechahora="+v_fecha,
		   success: resGuardar
		 });*/
		
		consultaTexto("resGuardar","embarquedemercancia_con.php?accion=9&idpagina="+u.idpagina.value+"&unidad="+unidad
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
			if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/fpdf/reportes/relacionEmbarque.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&unidad="+u.unidad.value+"&fechaembarque="+u.fecha.value+"&destino="+u.destino.value);
			
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/relacionEmbarque.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&unidad="+u.unidad.value+"&fechaembarque="+u.fecha.value+"&destino="+u.destino.value);
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/relacionEmbarque.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&unidad="+u.unidad.value+"&fechaembarque="+u.fecha.value+"&destino="+u.destino.value);
			}
			/*consultaTexto("resFaltantes","embarquedemercancia_con.php?accion=13&folio="+
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
		consultaTexto("resCargarEmbarque","embarquedemercancia_con.php?accion=10&idpagina="+u.idpagina.value+
							  "&folio="+folio+"&random="+Math.random());
	}
	
	function resCargarEmbarque(datos){
		try{
			var objeto = eval(datos);
		}catch(e){
			alerta3("A",datos);
		}
		tabla2.setJsonData(objeto.derecha);
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
</script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<input type="hidden" name="idpagina" id="idpagina" value="<?=date("ymdHis")?>" />
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
            <td width="33">Ruta</td>
            <td width="100"><span class="Tablas">
              <input name="ruta" type="text" class="Tablas" id="ruta" style="width:100px;background:#FFFF99" value="<?=$ruta ?>" readonly=""/>
            </span></td>
            <td width="49">Recorrido</td>
            <td width="258"><span class="Tablas">
              <input name="recorrido" type="text" class="Tablas" id="recorrido" style="width:250px;background:#FFFF99" value="<?=$recorrido ?>" readonly=""/>
            </span></td>
            <td width="22"> </td>
            <td width="156">&nbsp;</td>
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
                  <td width="298" height="66">
                  			<table border="0" cellpadding="0" cellspacing="0" id="tabladetalleizq" >
                            </table>
                            <table width="298" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td id="tablaArribaIzq_pag">&nbsp;</td>
                                </tr>
                            </table>
                            </td>
              <td width="24" align="center"><div id="aderecha" class="ebtn_adelante" onclick="moverAlaDerecha()"></div><BR /><BR /><div id="aizquierda" class="ebtn_atraz" onclick="moverAlaizquierda()"></div></td>
                  <td width="296">
                    <table border="0" cellpadding="0" cellspacing="0"  id="tabladetalleder">
                    </table>
                    <table width="296" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td id="tablaArribaDer_pag">&nbsp;</td>
                        </tr>
                    </table>
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
                  </table>
                  <table width="290" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td id="tablaAbajoIzq_pag">&nbsp;</td>
                        </tr>
                    </table>
                  </td>
            <td width="24">&nbsp;</td>      <td width="296">
            		<table border="0" cellpadding="0" cellspacing="0" id="tabladetallederabajo"></table>
            		<table width="290" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td id="tablaAbajoDer_pag">&nbsp;</td>
                        </tr>
                    </table>
            </td>
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