<?	
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/DataSetSinFiltro.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
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
	
	var nav4   = window.Event ? true : false;
	
	function Numeros(evt){ 
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}
	
	function limpiarDatos(){
		u.guardado.value = 0;
		u.conductor1.value="";
		u.conductor2.value="";
		u.nconductor1.value="";
		u.nconductor2.value="";
		u.unidad.value = "";
		u.sector.value = "";
		DS1.limpiar();
		DS2.limpiar();
		DS3.limpiar();
		DS4.limpiar();
		u.guia.value="";
		u.botonguardar.style.visibility = "visible";
		u.moverderecha.style.visibility = "visible";
		u.moverizquierda.style.visibility = "visible";
		u.sector.disabled=false;
		u.unidad.disabled=false;
		u.buscar1.style.visibility="visible";
		u.buscar2.style.visibility="visible";
		u.btn_imprimir.style.visibility = "hidden";
		consultaTexto("ponerId","repartoMercanciaEad_con.php?accion=12&idpagina="+u.idpagina.value);
	}
	
	function limpiarGrids(){
		tabla1.clear();
		tabla2.clear();
		tabla3.clear();
		tabla4.clear();
	}
	
	function ponerId(datos){
		var dts = datos.split(",");
		u.folio.value = dts[0];
		u.fecha.value = dts[1];
		u.sucursal.value = dts[2];
	}
	
	tabla1.setAttributes({
		nombre:"tablaArribaIzq",
		campos:[
			{nombre:"No_GUIA", medida:75, alineacion:"left", datos:"guia"},
			{nombre:"ORIGEN", medida:39, alineacion:"left", datos:"origen"},
			{nombre:"FECHA", medida:69, alineacion:"left", datos:"fecha"},
			{nombre:"CODIGO BARRAS", medida:79, alineacion:"left", datos:"codigobarra"}
		],
		filasInicial:15,
		alto:200,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"obtenerGuiaIzq()",
		nombrevar:"tabla1"
	});
	tabla2.setAttributes({
		nombre:"tablaAbajoIzq",
		campos:[
			{nombre:"REGISTRO", medida:50, alineacion:"left", datos:"registro"},
			{nombre:"PAQUETE", medida:60, alineacion:"left", datos:"paquete"},
			{nombre:"CODIGO DE BARRAS", medida:90, alineacion:"left", datos:"codigobarra"},
			{nombre:"ESTADO", medida:70, alineacion:"left", datos:"estado"},
			{nombre:"GUIA", medida:4, tipo:"oculto", alineacion:"left", datos:"guia"}
		],
		filasInicial:5,
		alto:80,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"limpiarSelIzq()",
		nombrevar:"tabla2"
	});
	tabla3.setAttributes({
		nombre:"tablaArribaDer",
		campos:[
			{nombre:"No_GUIA", medida:75, alineacion:"left", datos:"guia"},
			{nombre:"ORIGEN", medida:40, alineacion:"left", datos:"origen"},
			{nombre:"FECHA", medida:70, alineacion:"left", datos:"fecha"},
			{nombre:"CODIGO DE BARRAS", medida:90, alineacion:"left", datos:"codigobarra"}
		],
		filasInicial:15,
		alto:200,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"obtenerGuiaDer()",
		nombrevar:"tabla3"
	});
	tabla4.setAttributes({
		nombre:"tablaAbajoDer",
		campos:[
			{nombre:"REGISTRO", medida:50, alineacion:"left", datos:"registro"},
			{nombre:"PAQUETE", medida:60, alineacion:"left", datos:"paquete"},
			{nombre:"CODIGO DE BARRAS", medida:90, alineacion:"left", datos:"codigobarra"},
			{nombre:"ESTADO", medida:70, alineacion:"left", datos:"estado"},
			{nombre:"GUIA", medida:4, tipo:"oculto", alineacion:"left", datos:"guia"}
		],
		filasInicial:5,
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
		obtenerInicio();
		
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
	
	function obtenerInicio(){
		consultaTexto("mostrarInicio", "repartoMercanciaEad_con.php?accion=12&idpagina="+u.idpagina.value+"&and="+Math.random());
	}
	
	function mostrarInicio(datos){
		row = datos.split(",");
		u.folio.value 		= row[0];
		u.fecha.value 		= row[1];
		u.sucursal.value 	= row[2];
	}
	
	function obtenerConductorBusqueda(idconductor,valor){
		if(valor=="1"){
			var respuesta = "mostrarConductor1";
			u.conductor1.value = idconductor;
		}else{
			var respuesta = "mostrarConductor2";
			u.conductor2.value = idconductor;
		}

		consultaTexto(respuesta,"repartoMercanciaEad_con.php?accion=1&idpagina="+u.idpagina.value+"&idempleado="+idconductor+"&and="+Math.random());
	}
	
	function mostrarConductor1(datos){

		if(datos.indexOf("noencontrado")<0){
			try{
				var objeto = eval(convertirValoresJson(datos));				
			}catch(e){
				alerta3(datos);
				return false;
			}
				u.nconductor1.value = objeto[0].conductor;
		}else{
			alerta("No se encontro el conductor","¡Atencion!","conductor1");
			u.conductor1.value = "";
			u.nconductor1.value = "";
		}		
	}
	function mostrarConductor2(datos){
		if(datos.indexOf("noencontrado")<0){
			try{
				var objeto = eval(convertirValoresJson(datos));				
			}catch(e){
				alerta3(datos);
				return false;
			}
				u.nconductor2.value = objeto[0].conductor;
		}else{
			alerta("No se encontro el conductor","¡Atencion!","conductor2");
			u.conductor2.value = "";
			u.nconductor2.value = "";
		}		
	}
	
	function obtenerGuiaIzq(){
		tabla3.setSelectedById("");
		if(tabla1.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarDatosGuiaIzq","repartoMercanciaEad_con.php?accion=3&idpagina="+u.idpagina.value+"&folioguia="+tabla1.getValSelFromField('guia','No_GUIA')+"&cosA="+Math.random());
		}else{
			tabla2.clear();
		}
	}
	function obtenerGuiaDer(){
		tabla1.setSelectedById("");
		if(tabla3.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarDatosGuiaDer","repartoMercanciaEad_con.php?accion=4&idpagina="+u.idpagina.value+"&folioguia="+tabla3.getValSelFromField('guia','No_GUIA')+"&cosA="+Math.random());
			
		}else{
			tabla4.clear();
		}
	}
	function mostrarDatosGuiaIzq(datos){
		var objeto = eval(datos);
		DS2.setJsonData(objeto);
	}
	function mostrarDatosGuiaDer(datos){
		
		var objeto = eval(datos);
		DS4.setJsonData(objeto);
	}
	
	function pedirGuiasSector(valor){
		consultaTexto("mostrarGuiasSector","repartoMercanciaEad_con.php?accion=2&idpagina="+u.idpagina.value+"&sector="+valor+"&and="+Math.random());	
	}
	
	function mostrarGuiasSector(datos){
		try{
			var objeto = eval(datos);
		}catch(e){
			alerta3(datos);
			return false;
		}
		DS1.setJsonData(objeto);
	}
	
	function guardarValores(){
		<?=$cpermiso->verificarPermiso("326",$_SESSION[IDUSUARIO]);?>
		if(u.nconductor1.value==""){
			alerta("Por favor proporcione el primer conductor","¡Atencion!","conductor1");
			return false;
		}
		if(u.nconductor2.value==""){
			alerta("Por favor proporcione el segundo conductor","¡Atencion!","conductor2");
			return false;
		}
		if(u.unidad.value==""){
			alerta("Por favor proporcione la unidad","¡Atencion!","unidad");
			return false;
		}
		if(u.sector.value==""){
			alerta("Por favor proporcione el sector","¡Atencion!","sector");
			return false;
		}
		if(tabla3.getRecordCount()>0 && u.guardado.value == 0){
			consultaTexto("resValidar","repartoMercanciaEad_con.php?accion=9&idpagina="+u.idpagina.value+"&mathrand="+Math.random());
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
			var mensaje = "";
			for(var i=0; i<objeto.length; i++){
				mensaje += objeto[i].folioguia+",";
			}
			confirmar("Existen guias incompletas:<br>"+mensaje+"<br>¿Desea continuar?","¡Atencion!","mostrarLogueo()");
		}
	}
	function mostrarLogueo(){
		abrirVentanaFija("../buscadores_generales/logueo_permisos.php?funcion=guardarFinal&modulo=GuiaVentanilla&usuario=Admin",500,400,"ventana","DATOS PERSONALES","guardardatos_detalle();")	
	}
	
	function guardarFinal(){
		var conductor1  = u.conductor1.value;
		var conductor2  = u.conductor2.value;
		var unidad		= u.unidad.value;
		var sector		= u.sector.value;
		u.botonguardar.style.visibility = "hidden";
		consultaTexto("resGuardar","repartoMercanciaEad_con.php?accion=10&idpagina="+u.idpagina.value+"&unidad="+unidad
						  +"&sector="+sector+"&conductor1="+conductor1
						  +"&conductor2="+conductor2+"&folio="+u.folio.value+"&mathrand="+Math.random());
	}
	
	function resGuardar(datos){
		if(datos.indexOf("Las siguientes guias")>-1){
			alerta3(datos,"¡Atencion!");
		}else if(datos.indexOf("guardado")>-1){
			var row = datos.split(",");
			info("La informacion ha sido guardada","");
			u.folio.value = row[1];
			u.guardado.value = 1;
			u.btn_imprimir.style.visibility = "visible";
			
		}else{
			alerta3("Hubo un error "+datos,"¡Atencion!");
			u.botonguardar.style.visibility = "visible";
		}
	}
	
	function buscarFolio(){
		if(u.buscar.value==0){
			if(DS1.buscarYMostrar(u.guia.value,'guia')){
				moverAlaDerecha();
				u.guia.value = "";
			}else{
				alerta3("No se encontró la guia buscada en el almacen","¡Atencion!");
			}
		}else{
			if(DS3.buscarYMostrar(u.guia.value,'guia')){
				moverAlaizquierda();
				u.guia.value = "";
			}else{
				alerta3("No se encontró la guia buscada en la unidad","¡Atencion!");
			}
		}
		
	}
	
	function moverAlaDerecha(){
		if(tabla1.getRecordCount()==""){
			alerta3('No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			u.sector.disabled = true;
			if(tabla1.getSelectedIdRow()!=""){
				consultaTexto("resMovADerC","repartoMercanciaEad_con.php?accion=5&idpagina="+u.idpagina.value+"&folio="+tabla1.getSelectedRow().guia);
			}else if(tabla2.getSelectedIdRow()!=""){
				consultaTexto("resMovADerU","repartoMercanciaEad_con.php?accion=6&idpagina="+u.idpagina.value+"&folio="+tabla2.getSelectedRow().guia
					+"&registro="+tabla2.getSelectedRow().registro+"&random="+Math.random());
			}else{
				alerta3('Debe seleccionar la fila que desea mover a la derecha','¡Atención!');	
			}
		}
	}
	
	function resMovADerC(datos){
		if(datos.indexOf("guardado")>-1){
			if(!DS3.buscar(tabla1.getSelectedRow().guia,'guia'))
				DS3.agregarRegistro(tabla1.getSelectedRow());
			DS1.borrarRegistro(null,tabla1.getSelectedIndex(),'guia');
			DS2.limpiar();
			if(tabla1.getRecordCount()>0){
				tabla1.setSelectedById("tablaArribaIzq_id0");
			}
		}
	}
	
	function resMovADerU(datos){
		if(datos.indexOf("guardado")>-1){
			if(!DS3.buscar(tabla2.getSelectedRow().guia,'guia')){
				DS1.buscarYMostrar(tabla2.getSelectedRow().guia,'guia');
				DS3.agregarRegistro(tabla1.getSelectedRow());
			}
			DS4.agregarRegistro(tabla2.getSelectedRow());
			DS2.borrarRegistro(null,tabla2.getSelectedIndex(),'guia');
		}
	}
	
	function moverAlaizquierda(){
		if(tabla3.getRecordCount()==""){
			alerta3('No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			if(tabla3.getSelectedIdRow()!=""){
				consultaTexto("resMovAIzqC","repartoMercanciaEad_con.php?accion=7&idpagina="+u.idpagina.value+"&folio="+tabla3.getSelectedRow().guia);
			}else if(tabla4.getSelectedIdRow()!=""){
				consultaTexto("resMovAIzqU","repartoMercanciaEad_con.php?accion=8&idpagina="+u.idpagina.value+"&folio="+tabla4.getSelectedRow().guia
					+"&registro="+tabla4.getSelectedRow().registro+"&random="+Math.random());
			}else{
				alerta3('Debe seleccionar la fila que desea mover a la derecha','¡Atención!');	
			}
		}
	}
	function resMovAIzqC(datos){
		if(datos.indexOf("guardado")>-1){
			
			if(!DS1.buscar(tabla3.getSelectedRow().guia,'guia'))
				DS1.agregarRegistro(tabla3.getSelectedRow());
			DS3.borrarRegistro(null,tabla3.getSelectedIndex(),'guia');
			DS4.limpiar();
			if(tabla3.getRecordCount()>0){
				tabla3.setSelectedById("tablaArribaDer_id0");
			}
			//buscarEnIzq(folio);
		}
	}
	function resMovAIzqU(datos){
		if(datos.indexOf("guardado")>-1){
			if(!DS1.buscar(tabla4.getSelectedRow().guia,'guia')){
				DS3.buscarYMostrar(tabla4.getSelectedRow().guia,'guia');
				DS1.agregarRegistro(tabla3.getSelectedRow());
			}
			DS2.agregarRegistro(tabla4.getSelectedRow());
			DS4.borrarRegistro(null,tabla4.getSelectedIndex(),'guia');
			
			//buscarEnIzq(folio);
		}
	}

	/******* Funcion limpiar seleccion detalles **********/
	function limpiarSelIzq(){
		tabla1.setSelectedById("");
	}
	function limpiarSelDer(){
		tabla3.setSelectedById("");
	}

/*****/
	function buscarEnDer(folio){
		var campos = tabla3.getValuesFromField("guia");
		camposarreglo = campos.split(",");
		for(var i=0; i<tabla3.getRecordCount(); i++){
			if(camposarreglo[i] == folio){
				tabla3.setSelectedById("tablaArribaDer_id"+i);
				obtenerGuiaDer();
				return true;
			}
		}
	}
	
	function buscarEnIzq(folio){
		var campos = tabla1.getValuesFromField("guia");
		camposarreglo = campos.split(",");
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(camposarreglo[i] == folio){
				tabla1.setSelectedById("tablaArribaIzq_id"+i);
				obtenerGuiaIzq();
				return true;
			}
		}
	}
/********************/
	function obtenerFolioManual(valor){
		consultaTexto("showObtenerFolioManual","repartoMercanciaEad_con.php?accion=11&idpagina="+u.idpagina.value+"&folio="+valor);
	}
	function showObtenerFolioManual(valor){
		var datos = eval("(" + valor + ")");
		u.folio.value = datos.principal.id;
		//u.fecha.value = datos.principal.fecha;
		u.unidad.value = datos.principal.unidad;
		u.sector.value = datos.principal.sector;
		u.conductor1.value = datos.principal.conductor1;
		u.conductor2.value = datos.principal.conductor2;
		u.nconductor1.value = datos.principal.nombre1;
		u.nconductor2.value = datos.principal.nombre2;
		DS3.setJsonData(datos.detalle);
		u.botonguardar.style.visibility = "hidden";
		u.moverderecha.style.visibility = "hidden";
		u.moverizquierda.style.visibility = "hidden";
		u.sector.disabled=true;
		u.unidad.disabled=true;
		u.buscar1.style.visibility="hidden";
		u.buscar2.style.visibility="hidden";
		u.btn_imprimir.style.visibility = "visible";
	}
	
	function imprimirReporte(){
		if(document.URL.indexOf("web/")>-1){		
			window.open("https://www.pmmintranet.net/web/fpdf/reportes/relacionRepartoEad.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value);
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("https://www.pmmintranet.net/web_capacitacion/fpdf/reportes/relacionRepartoEad.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value);
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("https://www.pmmintranet.net/web_pruebas/fpdf/reportes/relacionRepartoEad.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value);
		}
	}
	
	</script>	
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reparto de Mercancia EAD</title>
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
.Estilo2 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>

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
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>

<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {font-size: 9px;
	color: #464442;
}
.style51 {color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<?
	list($Mili, $bot) = explode(" ", microtime());
	$DM=substr(strval($Mili),2,3);
?>
<input type="hidden" name="idpagina" id="idpagina" value="<?=$_SESSION[IDUSUARIO].".".date("ymdHis").$DM?>" />
<input type="hidden" name="guardado" value="0">
  <br>
<table width="621" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">REPARTO DE MERCANC&Iacute;A EAD</td>
  </tr>
  <tr>
    <td><table width="620" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="64">&nbsp;</td>
        <td width="56">&nbsp;</td>
        <td width="129">&nbsp;</td>
      </tr>
      <tr>
        <td>Folio:</td>
        <?
			$s = "select ifnull(max(rm.id),0)+1 as newfolio, date_format(current_date,'%d/%m/%Y') as fecha, 
			cs.descripcion as sucursal
			from repartomercanciaead as rm
			inner join catalogosucursal as cs on $_SESSION[IDSUCURSAL] = cs.id";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
		?>
        <td ><span class="Tablas">
          <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
        </span><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarFolioRepartos.php?funcion=obtenerFolioManual&tipo=normal', 600, 500, 'ventana', 'Busqueda')" style="cursor:pointer"></td>
        <td width="114">Fecha:<span class="Tablas">
        <input name="fecha" type="text" class="Tablas" id="fecha" style="background:#FFFF99" value="<?=$fecha ?>" size="10" readonly=""/>
        </span></td>
        <td width="45">Sucursal:</td>
        <td colspan="3"><span class="Tablas">
          <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:240px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>
        </span></td>
        </tr>
      <tr>
        <td width="52" height="31">Unidad:</td>
        <td width="158"><label>
          <select name="unidad" id="unidad" style="width:130px" >
          <option value=""></option>
          <?
		  	$s = "select * from catalogounidad where sucursal = $_SESSION[IDSUCURSAL] and tiporuta='LOCAL'  AND fueradeservicio=0";
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
		  ?>
		  	<option value="<?=$f->id?>"><?=$f->numeroeconomico?></option>
		  <?
			}
		  ?>
          </select>
        </label></td>
        <td>Conductor:
          <input name="conductor1" class="Tablas" type="text" id="conductor1" style="width:50px" value="<?=$conductor1 ?>"
          onKeyPress="if(event.keyCode==13){obtenerConductorBusqueda(this.value,1)}else{return Numeros(event);} " /></td>
        <td colspan="4"><span class="Tablas"><img id="buscar1" src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarConductor.php?caja=1&tipo=local', 600, 500, 'ventana', 'Busqueda')" style="cursor:pointer"></span><span class="Tablas">
          <input name="nconductor1" type="text" class="Tablas" id="nconductor1" style="width:260px;background:#FFFF99" value="<?=$nconductor1 ?>" readonly=""/>
        </span></td>
        </tr>
      <tr>
        <td>Sector:</td>
        <td><select name="sector" id="select" style="width:130px" onChange="if(this.value!=''){pedirGuiasSector(this.value);}else{limpiarGrids()}">
	        <option value="" selected></option>
			<option value="0">SIN SECTOR</option>
        	<?
		  	$s = "select * from catalogosector WHERE idsucursal = $_SESSION[IDSUCURSAL]";
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
		  ?>
		  	<option value="<?=$f->id?>"><?=$f->descripcion?></option>
		  <?
			}
		  ?>
                </select></td>
        <td>Conductor:
          <input name="conductor2" class="Tablas" type="text" id="conductor2" style="width:50px" 
           onKeyPress="if(event.keyCode==13){obtenerConductorBusqueda(this.value,2)}else{return Numeros(event);} " /></td>
        <td colspan="4"><span class="Tablas"><img id="buscar2" src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarConductor.php?caja=2&tipo=local', 600, 500, 'ventana', 'Busqueda')" style="cursor:pointer">
            <input name="nconductor2" type="text" class="Tablas" id="nconductor2" style="width:260px;background:#FFFF99" value="<?=$nconductor2 ?>" readonly=""/>
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="7">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="7"><table width="618" cellpadding="0" cellspacing="0">
          <tr>
            <td width="290">
            	<table border="0" cellpadding="0" cellspacing="0" id="tablaArribaIzq"></table>
            	<table width="290" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td id="tablaArribaIzq_pag">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td width="36" align="center"><div id="moverderecha" class="ebtn_adelante" onClick="moverAlaDerecha();"></div><br><br><br><br><div id="moverizquierda" class="ebtn_atraz" onClick="moverAlaizquierda();"></div></td>
            <td width="290">
            	<table border="0" cellpadding="0" cellspacing="0" id="tablaArribaDer"></table>
                <table width="290" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td id="tablaArribaDer_pag">&nbsp;</td>
                    </tr>
                </table>
            </td>
          </tr>
        </table>
        
        </td>
        </tr>
      <tr>
        <td colspan="7">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="7"><table width="618" cellpadding="0" cellspacing="0">
          <tr>
            <td width="290">
            	<table border="0" cellpadding="0" cellspacing="0" id="tablaAbajoIzq"></table>
                <table width="290" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td id="tablaAbajoIzq_pag">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td width="36">&nbsp;</td>
            <td width="290">
            	<table border="0" cellpadding="0" cellspacing="0" id="tablaAbajoDer"></table>
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
        <td colspan="7">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="7"><table width="315" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55">
Buscar
</td>
            <td width="112"><span class="Tablas">
              <input name="guia" type="text" class="Tablas" id="guia" style="width:100px" value="<?=$guia ?>" onKeyPress="if(event.keyCode==13){buscarFolio();}" />
            </span></td>
            <td width="132">
            <select name="buscar" class="Tablas" style="width:140px" >
            <option value="0">ALMACEN</option>
            <option value="1">UNIDAD</option>
            </select>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="7" align="center"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
          <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>">
          <input name="registros" type="hidden" id="registros" value="<?=$registros ?>">
          <table border="0" cellpadding="0" cellspacing="0">
          <tr>
          <td width="84"><div  class="ebtn_nuevo" onClick="confirmar('¿Desea limpiar los datos?','¡Atencion!','limpiarDatos()','')"></div></td>
          <td width="87"><div id="botonguardar" class="ebtn_guardar" onClick="guardarValores()"></div></td>
          <td width="94"><div id="btn_imprimir" class="ebtn_imprimir" onclick="imprimirReporte()" style="visibility:hidden"></div></td>
          </tr>
          </table>
          </td>
      </tr>
      <tr>
        <td colspan="7" align="center"></td>
      </tr>
      <tr>
        <td colspan="7">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>
