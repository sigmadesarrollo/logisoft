<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var u 	   	= 	 document.all;
	var tabla1 	= new ClaseTabla();	
	var mens	= new ClaseMensajes();
	mens.iniciar('../javascript');
	var cerrooguardo = 0;
	
	tabla1.setAttributes({
		nombre:"tablalista",
		campos:[
			{nombre:"SECTOR", medida:69, alineacion:"left", datos:"sector"},
			{nombre:"No_GUIA", medida:75, alineacion:"left", datos:"guia"},
			{nombre:"ORIGEN", medida:70, alineacion:"left", datos:"origen"},
			{nombre:"DESTINATARIO", medida:70, alineacion:"left", datos:"destinatario"},
			{nombre:"TIPO_FLETE", medida:79, alineacion:"left", datos:"tipoflete"},
			{nombre:"CONDICION", medida:1, tipo:"oculto", alineacion:"left", datos:"condicionpago"},
			{nombre:"IMPORTE", medida:70, tipo:"moneda",alineacion:"right", datos:"importe"},
			{nombre:"ESTADO", medida:79, alineacion:"center", datos:"estado"},
			{nombre:"CHECK", medida:50, alineacion:"center", datos:"seleccion"},
			{nombre:"MOTIVO", medida:4, tipo:"oculto", alineacion:"left", datos:"motivo"},
			{nombre:"PAQUETES", medida:4, tipo:"oculto", alineacion:"left", datos:"paquetes"}
		],
		filasInicial:15,
		alto:200,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"mostrarDetalle()",
		nombrevar:"tabla1"
	});
	
	//funciones generales
	function solonumeros(evnt){
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
	}
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	//*****
	
	function mostrarDetalle(){
		var fila = tabla1.getSelectedRow();
		if(fila != undefined){
			if(u.guardar.style.visibility!="hidden" && tabla1.getRecordCount()>0){
				abrirVentanaFija("motivosdevolucion.php",500,400,"ventana","Motivos","ponerMotivos('');");
			}
		}
		
	}
	function obtenerGuias(idreparto){
		consultaTexto("Validar","devoluciondemercanciaaalmacen_con.php?accion=6&folio="+u.folio.value);
	}
	
	function Validar(datos){
		if(datos!=0){
			alerta("El folio de reparto #"+u.folio.value+" ya fue utilizado en otra devolución","¡Atención!","folio");			
			u.folio.value = "";
		}else{
			consultaTexto("mostrarGuia","devoluciondemercanciaaalmacen_con.php?accion=1&idreparto="+u.folio.value
			+"&and="+Math.random());
			u.accion.value = "";			
		}
	}
	
	function mostrarGuia(datos){
		var objeto = eval(convertirValoresJson(datos));
		if(objeto.idreparto==undefined){
			alerta("No se encontro el Folio de Reparto","¡Atencion!","folio");
			limpiarTodo();
		}else{
			u.folio.value = objeto.idreparto;
			u.unidad.value = objeto.numeroeconomico;
			u.conductor.value = objeto.conductorn1;
			u.conductor2.value = objeto.conductorn2;
			consultaTexto("mostrarGuiasTabla","devoluciondemercanciaaalmacen_con.php?accion=2&folio="+objeto.idreparto+"&and="+Math.random());
		}
	}
	
	function mostrarGuiasTabla(datos){
		var objeto = eval(convertirValoresJson(datos));
		tabla1.setJsonData(objeto);
		var pagCredit = 0;
		var pagContad = 0;
		var cobContad = 0;
		var cobCredit = 0;
		var tpagCredit = 0;
		var tpagContad = 0;
		var tcobCredit = 0;
		var tcobContad = 0;
		for(var i=0; i<objeto.length; i++){
			pagCredit += parseFloat((objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CREDITO")?1:0);
			pagContad += parseFloat((objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CONTADO")?1:0);
			cobCredit += parseFloat((objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CREDITO")?1:0);
			cobContad += parseFloat((objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CONTADO")?1:0);
			
			tpagCredit += parseFloat((objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CREDITO")?objeto[i].importe:0);
			tpagContad += parseFloat((objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CONTADO")?objeto[i].importe:0);
			tcobCredit += parseFloat((objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CREDITO")?objeto[i].importe:0);
			tcobContad += parseFloat((objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CONTADO")?objeto[i].importe:0);
		}
			
		
		u.entregadas.value =objeto.length;
		u.devueltas.value = 0;
				
		u.pcredito.value = pagCredit;
		u.pcontado.value = pagContad;
		u.ccredito.value = cobCredit;
		u.ccontado.value = cobContad;
		
		u.pcredito2.value = "$ "+numcredvar(tpagCredit.toString());
		u.pcontado2.value = "$ "+numcredvar(tpagContad.toString());
		u.ccredito2.value = "$ "+numcredvar(tcobCredit.toString());
		u.ccontado2.value = "$ "+numcredvar(tcobContad.toString());
	}
	
	window.onload = function(){
		u.folio.focus();
		tabla1.create();
		u.cerrar.style.visibility="hidden";
		u.limpiar.style.visibility="visible";
		obtenerGeneral();	
	}
	
	function obtenerGeneral(){
		consultaTexto("nuevosDatos","devoluciondemercanciaaalmacen_con.php?accion=5&suerte="+Math.random());
	}
	
	function ponerMotivos(valor){
		var indice = tabla1.getSelectedIndex();
		
		if(valor!=""){
			document.all["tablalista_CHECK"][indice].value = "X";
			document.all["tablalista_MOTIVO"][indice].value = valor;
		}else{
			document.all["tablalista_CHECK"][indice].value = "";
			document.all["tablalista_MOTIVO"][indice].value = "";
		}
		var folios			= 0;
		var folios2			= 0;
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(document.all["tablalista_CHECK"][i].value=="X"){
				folios += 1
			}else{
				folios2 += 1;
			}
		}
		u.entregadas.value = folios2;
		u.devueltas.value = folios;
	}
	
	function validarfolioreparto(tipo){
		cerrooguardo=tipo;
		<?=$cpermiso->verificarPermiso(327,$_SESSION[IDUSUARIO]);?>
		if(tipo==0){
			u.accion.value="grabar";
		}else if(tipo==1){
			u.accion.value="cerrar";
		}
		consultaTexto("mostrarGuiavalidacion","devoluciondemercanciaaalmacen_con.php?accion=1&idreparto="+u.folio.value
		+"&and="+Math.random());
	}
	
	function mostrarGuiavalidacion(datos){
		var objeto = eval(convertirValoresJson(datos));
		if(objeto.idreparto==undefined){
			alerta("No se encontro el Folio de Reparto","¡Atencion!","folio");
			limpiarTodo();
		}else{
			if(u.accion.value=="grabar"){
				guardar();
			}else if(u.accion.value=="cerrar"){
				guardar();
			}
		}
	}
	
	function guardar(){
		if(u.folio.value==""){
			alerta3("Seleccione el folio de reparto para poder continuar", "¡Atencion!");
			return false;
		}
		
		if(tabla1.getRecordCount()<1){
			alerta3("No hay guias en el reparto para devolver", "Atencion");
			return false;
		}
		folios	= "";
		folios2	= "";
		v_guia 	= "";
		v_index = 0;
		
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(u["tablalista_CHECK"][i].value=="X"){
				folios += u["tablalista_No_GUIA"][i].value + "#" + u["tablalista_MOTIVO"][i].value + ",";
			}else{
				folios2 += u["tablalista_No_GUIA"][i].value + ",";
			}
			
			if(u["tablalista_PAQUETES"][i].value!="0"){
				v_guia += u["tablalista_No_GUIA"][i].value + ",";
			}
		}
		folios = folios.substring(0,folios.length -1);
		folios2 = folios2.substring(0,folios2.length -1);
		
		guardarFinal();
		
		/*if(u.accion.value=="grabar"){
			guardarFinal();
		}else if(u.accion.value=="cerrar"){
			if(v_guia!=""){
				v_guia = v_guia.substring(0,v_guia.length -1);
				v_incompletas = v_guia.split(",");
				mens.show('C','Existen Guias incompletas, se generara una queja por cada uno de los faltantes ¿Desea continuar?', '', '', 'mostrarQueja(1)');
			}
		}*/
	}
	
	function mostrarQueja(tipo){
		abrirVentanaFija('../cat/moduloQuejasDanosFaltantes.php?guia='+v_incompletas[v_index]
		+"&tipo=faltante&indice="+v_index+"&valor="+tipo, 600, 480, 'ventana', 'QUEJA DAÑOS Y FALTANTES');
		if(v_incompletas[v_index]==undefined){
			VentanaModal.cerrar();
			info("Se han registrado las guias con faltantes","¡Atención!");
			if(tipo==1){
				guardarFinal();
			}else{
				consultaTexto("Datosmodificar","devoluciondemercanciaaalmacen_con.php?accion=7&folio="+u.folio.value
				+"&devolucion="+u.fdevolucion.value+"&guias="+v_guia+"&fecha="+u.fecha.value);
			}
		}
		v_index++;
	}
	
	function guardarFinal(){
		if(u.accion.value=="grabar"){
			var tipo = "0";
		}else if(u.accion.value=="cerrar"){
			var tipo = "1";
		}
		cerrooguardo = tipo;
		consultaTexto("resGuardar","devoluciondemercanciaaalmacen_con.php?accion=3&folio="+u.folio.value
		+"&sucursal="+u.sucursal.value+"&unidad="+u.unidad.value+"&conductor1="+u.conductor.value
		+"&conductor2="+u.conductor2.value+"&entregadas="+u.entregadas.value
		+"&devueltas="+u.devueltas.value+"&pagcre="+u.pcredito.value+"&pagcon="+u.pcontado.value
		+"&tpagcre="+u.pcredito2.value.replace("$ ","").replace(/,/g,"")
		+"&tpagcon="+u.pcontado2.value.replace("$ ","").replace(/,/g,"")
		+"&porcobrarcre="+u.ccredito.value+"&porcobrarcont="+u.ccontado.value
		+"&tporcobrarcre="+u.ccredito2.value.replace("$ ","").replace(/,/g,"")
		+"&tporcobrarcont="+u.ccontado2.value.replace("$ ","").replace(/,/g,"")
		+"&folios="+folios+"&folios2="+folios2+"&id="+u.fdevolucion.value
		+"&cerrar="+tipo+"&guias="+v_guia+"&and="+Math.random()+"&fecha="+u.fecha.value);
	}
	
	function resGuardar(datos){
		if(datos.indexOf("guardado")>-1){
			var row = datos.split(",");
			u.fdevolucion.value = row[1];
			u.cerrar.style.visibility="visible";
			info("Los datos han sido guardados","¡Atencion!");
			if(cerrooguardo==1){
				u.accion.value ="";
				u.guardar.style.visibility="hidden";
				u.cerrar.style.visibility="hidden";
				u.limpiar.style.visibility="visible";
				u.folio.readOnly=true;
				u.folio.style.backgroundColor="#FFFF99";
			}
			//limpiarTodo();
		}else{
			alerta3("Error al guardar "+datos,"¡Atencion!");
		}
	}
	
	function limpiarTodo(){
		u.folio.value="";
		u.sucursal.value="";
		u.unidad.value="";
		u.conductor.value="";
		u.conductor2.value="";
		u.entregadas.value="";
		u.devueltas.value="";
		u.pcredito.value="";
		u.pcontado.value="";
		u.pcredito2.value="";
		u.pcontado2.value="";
		u.ccredito.value="";
		u.ccontado.value="";
		u.ccredito2.value="";
		u.ccontado2.value="";
		u.folio.readOnly=false;
		u.folio.style.backgroundColor="";
		tabla1.clear();
		u.accion.value="";
		u.guardar.style.visibility="visible";
		u.cerrar.style.visibility="hidden";
		u.limpiar.style.visibility="visible";
		obtenerGeneral();	
	}
	
	function nuevosDatos(datos){
		row = datos.split(",");
		u.fdevolucion.value = row[0];
		u.fecha.value 		= row[1];
		u.sucursal.value 	= row[2];
	}
	
	function buscarFolio(){
		if(tabla1.getRecordCount()<1){
			alerta3("No hay guias para buscar","¡Atencion!");
			return false;
		}
		if(u.guia.value==""){
			alerta3("Proporcione un folio de guia para buscar","¡Atencion!");
			return false;
		}
			
		var campo = "guia";
		var campos = tabla1.getValuesFromField(campo);
		camposarreglo = campos.split(",");
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(camposarreglo[i] == u.guia.value){
				tabla1.setSelectedById("tablalista_id"+i);
				return true;
			}
		}
		alerta3("No se encontro la guia buscada","¡Atencion!");
	}
	
	function obtenerFolioDev(id){
		u.fdevolucion.value = id;
		consultaTexto("resObtenerDatosDevolucion","devoluciondemercanciaaalmacen_con.php?accion=4&folio="+id
		+"&suerte="+Math.random());	
	}
	
	function obtenerDatosDevolucion(id){
		u.fdevolucion.value = id;
		consultaTexto("resObtenerDatosDevolucion","devoluciondemercanciaaalmacen_con.php?accion=4&folio="+id
		+"&suerte="+Math.random());
	}
	
	function resObtenerDatosDevolucion(datos){
		if (datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos))	
			
			tabla1.setJsonData(obj.detalle);			
			u.folio.value 		= obj.principal.idreparto;
			//u.fecha.value 		= obj.principal.fechadevolucion;
			if (obj.principal.cerro==0){
				u.guardar.style.visibility="visible";
				u.cerrar.style.visibility="visible";
				u.limpiar.style.visibility="visible";
			}else{
				u.accion.value ="";
				u.guardar.style.visibility="hidden";
				u.cerrar.style.visibility="hidden";
				u.limpiar.style.visibility="visible";
				u.folio.readOnly=true;
				u.folio.style.backgroundColor="#FFFF99";	
			}
			u.h_fecha.value		= obj.principal.fecha;
			u.sucursal.value 	= obj.principal.nsucursal;
			u.unidad.value		= obj.principal.unidad;
			u.conductor.value	= obj.principal.conductor1;
			u.conductor2.value	= obj.principal.conductor2;
			u.entregadas.value	= obj.principal.entregadas;
			u.devueltas.value	= obj.principal.devueltas;
			u.pcredito.value	= obj.principal.pagadas_credito;
			u.pcontado.value	= obj.principal.pagadas_contado;
			u.pcredito2.value	= obj.principal.tpagadas_credito;
			u.pcontado2.value	= obj.principal.tpagadas_contado;
			u.ccredito.value	= obj.principal.porcobrar_credito;
			u.ccontado.value	= obj.principal.porcobrar_contado;
			u.ccredito2.value	= obj.principal.tporcobrar_credito;
			u.ccontado2.value	= obj.principal.tporcobrar_contado;
			
			u.pcredito2.value = "$ "+numcredvar(u.pcredito2.value.toString());
			u.pcontado2.value = "$ "+numcredvar(u.pcontado2.value.toString());
			u.ccredito2.value = "$ "+numcredvar(u.ccredito2.value.toString());
			u.ccontado2.value = "$ "+numcredvar(u.ccontado2.value.toString());
		}else{
			alerta3("El folio de devolución no fue encontrado","¡ATENCION!");
			limpiarTodo();
		}
	}
	
	function cerrardevolucion(){
		<?=$cpermiso->verificarPermiso(328,$_SESSION[IDUSUARIO]);?>
		/*confirmar('¿Desea cerrar la devolución del folio de reparto ead: '+ u.folio.value +'?', '', 'ventanamodificar()', 'validarfolioreparto(1)');*/
		confirmar('¿Desea cerrar la devolución del folio de reparto ead: '+ u.folio.value +'?', '', 'validarfolioreparto(1)', '');
	}
	
	function ventanamodificar(){	
		v_guia 	= "";
		v_index = 0;		
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(u["tablalista_PAQUETES"][i].value!="0" || u["tablalista_PAQUETES"][i].value!=undefined){
				v_guia += u["tablalista_No_GUIA"][i].value + ",";
			}
		}
		if(v_guia!=""){
			v_guia = v_guia.substring(0,v_guia.length -1);
			v_incompletas = v_guia.split(",");
			mens.show('C','Existen Guias incompletas, se generara una queja por cada uno de los faltantes ¿Desea continuar?', '', '', 'mostrarQueja(2)');
		}
	}
	
	function Datosmodificar(datos){
		if(datos.indexOf("ok")>-1){
			limpiarTodo();
		}else{
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
		}
	}
	function obtenerFolioLiquidacion(id){
		u.folio.value = id;
		consultaTexto("Validar","devoluciondemercanciaaalmacen_con.php?accion=6&folio="+id);
	}
</script>
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

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<br>
<table width="620" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">DEVOLUCI&Oacute;N DE MERCANCIA ALMAC&Eacute;N</td>
  </tr>
  <tr>
    <td><table width="618" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="102">Folio Devoluci&oacute;n:</td>
        <td width="118"><input name="fdevolucion" type="text" class="Tablas"  style="width:100px;text-align:right"  onkeypress="if(event.keyCode!=13){return solonumeros(event);}else{obtenerFolioDev(this.value);}" maxlength="7" /></td>
        <td width="369"><div class="ebtn_buscar" 
                onclick="abrirVentanaFija('../buscadores_generales/buscarFolioDevolucion.php?funcion=obtenerDatosDevolucion',600,500,'ventana','BUSCAR FOLIO DEVOLUCION','');"> </div></td>
        <td width="29">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><table width="618" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="101">Folio Reparto EAD:</td>
            <td width="131"><input name="folio" type="text" class="Tablas" id="folio" style="width:80px" onkeypress="if(this.readOnly==false){if(event.keyCode!=13){return solonumeros(event);}else{obtenerGuias(this.value);}}" value="" maxlength="7"/>
              <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarFolioRepartos.php?funcion=obtenerFolioLiquidacion&tipo=ambos',600,475,'ventana','BUSCAR LIQUIDACION MERCANCIA','');" /></td>
            <td width="57">Fecha:</td>
            <td width="105"><span class="Tablas">
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:85px;background:#FFFF99" value="" readonly=""/>
            </span></td>
            <td width="57">Sucursal:</td>
            <td width="167"><span class="Tablas">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:152px;background:#FFFF99" value="" readonly=""/>
            </span></td>
          </tr>
          <tr>
            <td>Unidad:</td>
            <td><span class="Tablas">
              <input name="unidad" type="text" class="Tablas" id="unidad" style="background:#FFFF99"  readonly=""/>
            </span></td>
            <td>Conductor:</td>
            <td colspan="3"><span class="Tablas">
              <input name="conductor" type="text" class="Tablas" id="conductor" style="width:315px;background:#FFFF99"  readonly=""/>
            </span></td>
          </tr>
          <tr>
            <td># Gu&iacute;a:</td>
            <td><input name="guia" type="text" class="Tablas" id="guia"  onkeypress="if(event.keyCode==13){buscarFolio();}" maxlength="13" /></td>
            <td>Conductor:</td>
            <td colspan="3"><span class="Tablas">
              <input name="conductor2" type="text" class="Tablas" id="conductor2" style="width:315px;background:#FFFF99"  readonly=""/>
            </span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="6" align="center"><table border="0" cellpadding="0" cellspacing="0" id="tablalista"></table></td>
            </tr>
          <tr>
            <td colspan="6"><table width="615" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="59">Entregadas:</td>
                <td width="46"><span class="Tablas">
                  <input name="entregadas" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
                </span></td>
                <td width="80">Pag. Cr&eacute;dito: </td>
                <td width="169"><span class="Tablas">
                  <input name="pcredito" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
                  <input name="pcredito2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
                </span></td>
                <td width="109">Por Cobrar Contado: </td>
                <td width="31"><span class="Tablas">
                  <input name="ccontado" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
                </span></td>
                <td width="121"><span class="Tablas">
                  <input name="ccontado2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
                </span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="6"><table width="615" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="59">Devueltas:</td>
                <td width="44"><span class="Tablas">
                  <input name="devueltas" type="text" class="Tablas" id="devueltas" style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
                </span></td>
                <td width="82">Pag. Contado:</td>
                <td width="169"><span class="Tablas">
                  <input name="pcontado" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
                  <input name="pcontado2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
                </span></td>
                <td width="109">Por Cobrar Cr&eacute;dito: </td>
                <td width="31"><span class="Tablas">
                  <input name="ccredito" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
                </span></td>
                <td width="121"><span class="Tablas">
                  <input name="ccredito2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
                </span></td>
              </tr>
            </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="4" align="center">
        <table width="276">
        	<tr>
            	<td >
        			<div id="guardar" style=":<? if($_POST[accion]=='grabar'){?>visibility:hidden<? }?>" class="ebtn_guardar" onclick="validarfolioreparto(0)"></div>        		</td>
            	<td><div id="cerrar" class="ebtn_cerradevolucion" onclick="cerrardevolucion()"></div></td>
            	<td>
                	<div id="limpiar" class="ebtn_nuevo" onclick="confirmar('&iquest;Desea limpiar los datos?','&iexcl;Atencion!','limpiarTodo()','')"></div>                </td>
            </tr>
        </table>
        <input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />
        <!-- <div class="Bonton_Cerrar_Devolucion.gif" > </div>	!-->	<input name="h_fecha" type="hidden" id="h_fecha" /></td>
      </tr>
      <tr>
        <td colspan="4"></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>