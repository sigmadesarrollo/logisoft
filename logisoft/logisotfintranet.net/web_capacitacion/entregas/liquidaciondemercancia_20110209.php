<?	
	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha= date("d/m/Y");
	$idsucursal=$_SESSION[IDSUCURSAL];
	$usuario=$_SESSION[IDUSUARIO];
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
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/ClaseMensajes.js" language="javascript"></script>
<script src="../javascript/jquery-1.4.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script>
	var u 	   = 	 document.all;
	var tabla1 = new ClaseTabla();	
	var nav4   = window.Event ? true : false;
	var guiasFacturar = Array();
	var clientesFacturar = Array();
	var indFac = 0;
	var mens = new ClaseMensajes();
	var cerrooguardo = 0;
	mens.iniciar("../javascript");
	
	tabla1.setAttributes({
		nombre:"tablalista",
		campos:[
			{nombre:"SECTOR", medida:40, alineacion:"left", datos:"sector"},
			{nombre:"GUIA", medida:80, alineacion:"center", datos:"guia",onDblClick:"mostrarDetalle"},
			{nombre:"ORIGEN", medida:50, alineacion:"center", datos:"origen"},
			{nombre:"IDDESTINATARIO", medida:4, alineacion:"left", datos:"iddestinatario", tipo:"oculto"},
			{nombre:"DESTINATARIO", medida:200, alineacion:"left", datos:"destinatario"},
			{nombre:"TIPO_FLETE", medida:50, alineacion:"center", datos:"tipoflete"},
			{nombre:"PAGO", medida:50, alineacion:"left", datos:"condicionpago"},
			{nombre:"IMPORTE", medida:70, tipo:"moneda", alineacion:"right", datos:"importe",onDblClick:"agregarFormaPago"},
			{nombre:"ESTADO", medida:110, alineacion:"center", datos:"estado"},
			{nombre:"CHECK", medida:50, alineacion:"center", datos:"seleccion", tipo:"checkbox", onClick:"validacheck"},
			{nombre:"PAGADA", medida:4, alineacion:"center", datos:"pagada", tipo:"oculto"},
			{nombre:"FACTURA", medida:4, alineacion:"center", datos:"factura", tipo:"oculto"},
			{nombre:"NOMBRE", medida:50, alineacion:"left", datos:"nombre"},
			{nombre:"IDENTIFICACION", medida:4, tipo:"oculto", alineacion:"left", datos:"identificacion"},
			{nombre:"NUMERO_ID", medida:4, tipo:"oculto", alineacion:"left", datos:"numero_id"}
		],
		filasInicial:15,
		alto:200,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerInicio();
		u.cerrar.style.visibility = "hidden";
		u.entregado.value = convertirMoneda(u.entregado.value);
	}

	function obtenerInicio(){
		consultaTexto("mostrarInicio", "liquidaciondemercancia_con.php?accion=1&idpagina="+u.idpagina.value+"&and="+Math.random());
	}

	function mostrarInicio(datos){
		row = datos.split(",");
		u.folio2.value = row[0];
		u.fecha.value 		= row[1];
		u.idsucursal.value	= row[2];
		u.sucursal.value 	= row[3];
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
	
	function esNan(caja){
		if (document.getElementById(caja).value.replace('$ ','').replace(/,/g,'')=="NaN"){
				document.getElementById(caja).value	= 0;
		}
	}

	//obteniendo el folio de reparto
	function obtenerGuia(idreparto){
		u.folio.value = idreparto;
		consultaTexto("resObtenerGuia","liquidaciondemercancia_con.php?accion=2&idpagina="+u.idpagina.value+"&folio="+u.folio.value);
	}
	
	function resObtenerGuia(datos){
		var obj = eval(datos);
		if(obj.enliquidacion=='1'){
			alerta("El folio de reparto ya fue agregado a una liquidación","¡Atención!","folio");
			return false;
		}
		if(obj.cerrodevolucion=='0' || obj.cerrodevolucion==''){
			alerta("La devolucion mercancia seleccionada no ha sido cerrada","¡Atencion!","folio");
			return false;
		}
		
		u.folio.value = obj.dc.idreparto;
		u.unidad.value = obj.dc.numeroeconomico;
		u.conductor.value = obj.dc.conductorn1;
		u.conductor2.value = obj.dc.conductorn2;
		u.idconductor1.value = obj.dc.conductor1;
		u.idconductor2.value = obj.dc.conductor2;
		
		var pagCredit = 0;
		var pagContad = 0;
		var cobContad = 0;
		var cobCredit = 0;
		var tpagCredit = 0;
		var tpagContad = 0;
		var tcobCredit = 0;
		var tcobContad = 0;
		var sumaentregadas=0;
		var sumadevuletas=0;
		
		tabla1.setJsonData(obj.registros);
		
		for(var i=0;i<obj.registros.length;i++){
			if (obj.registros[i].estado=="ENTREGADA POR LIQUIDAR"){
				sumaentregadas+=1;
			}else{
				sumadevuletas+=1;
			}
			if(obj.registros[i].seleccionada == 'S')
				document.all['tablalista_CHECK'][i].checked = true;
			
			pagContad += parseFloat((obj.registros[i].tipoflete == "PAGADO" && obj.registros[i].condicionpago == "CONTADO")?1:0);
			cobCredit += parseFloat((obj.registros[i].tipoflete != "PAGADO" && obj.registros[i].condicionpago == "CREDITO")?1:0);
			cobContad += parseFloat((obj.registros[i].tipoflete != "PAGADO" && obj.registros[i].condicionpago == "CONTADO" && obj.registros[i].estado == "ENTREGADA POR LIQUIDAR")?1:0);
					
			tpagCredit += parseFloat((obj.registros[i].tipoflete == "PAGADO" && obj.registros[i].condicionpago == "CREDITO")?obj.registros[i].importe:0);
			tpagContad += parseFloat((obj.registros[i].tipoflete == "PAGADO" && obj.registros[i].condicionpago == "CONTADO")?obj.registros[i].importe:0);
			tcobCredit += parseFloat((obj.registros[i].tipoflete != "PAGADO" && obj.registros[i].condicionpago == "CREDITO")?obj.registros[i].importe:0);
			tcobContad += parseFloat((obj.registros[i].tipoflete != "PAGADO" && obj.registros[i].condicionpago == "CONTADO" 
																	&& obj.registros[i].estado == "ENTREGADA POR LIQUIDAR")?obj.registros[i].importe:0);
			
		}
		
		u.entregadas.value = sumaentregadas;
		u.devueltas.value = sumadevuletas;
		u.pcredito.value = pagCredit;
		u.pcontado.value = pagContad;
		u.ccredito.value = cobCredit;
		u.ccontado.value = cobContad;
		u.pcredito2.value = convertirMoneda(tpagCredit);
		u.pcontado2.value = convertirMoneda(tpagContad);
		u.ccredito2.value = convertirMoneda(tcobCredit);
		u.ccontado2.value = convertirMoneda(tcobContad);
		u.total.value = convertirMoneda(tcobContad);
		
		validacheck();
		
		/*for(var i=0; i<tabla1.getRecordCount(); i++){			
			if(document.all['tablalista_TIPO_FLETE'][i].value=="POR COBRAR" 
			&& document.all['tablalista_PAGO'][i].value=="CONTADO" 
			&& document.all['tablalista_ESTADO'][i].value != "ALMACEN DESTINO"){
				document.all['tablalista_CHECK'][i].checked=true;
				document.all['tablalista_CHECK'][i].disabled=true;
			}
		}*/
	}
	
	function agregarFormaPago(){
		setTimeout("agregarMFormaPago()","1000");
	}
	
	function agregarMFormaPago(){
		var arr = tabla1.getSelectedRow();
		
		if(arr.tipoflete=='PAGADO'){
			alerta3("Estas guias no pueden ser liquidadas aqui","¡Atención!");
			return false;
		}else{
			if(arr.factura=="0" && arr.tipoflete=='POR COBRAR' && arr.condicionpago == 'CREDITO'){
				alerta3("Para poder realizar este pago debe facturar la guia","¡Atención!");
				return false;
			}
			if (u.estado.value==""){
				var importe=0;
				importe=convertirMoneda(arr.importe);
				if (arr.estado=="ENTREGADA POR LIQUIDAR" && arr.tipoflete=="POR COBRAR" && (arr.condicionpago=="CONTADO" || arr.condicionpago=="CREDITO")){
					abrirVentanaFija("liquidaciondemercancia_formapago.php?importe="+importe, 525, 418, "ventana", "FORMA DE PAGO");
				}
			}	
		}
	}
	
	function validacheck(){
		var total = 0;
		var guias = "";
		for(var i=0; i<tabla1.getRecordCount();i++){
		  	tabla1.setSelectedById("tablalista_id"+i);
		  	var objeto = tabla1.getSelectedRow();
			
			//alert(objeto.tipoflete+"->"+objeto.condicionpago + "->" + objeto.guia);
			if(u["tablalista_CHECK"][i].checked==true){
				 guias += ((guias!="")?",":"")+objeto.guia;
			}
			
			if(u["tablalista_CHECK"][i].checked==true && objeto.tipoflete=='POR COBRAR' && 
						((objeto.condicionpago == 'CONTADO') || (objeto.condicionpago == 'CREDITO' && objeto.factura!='0'))){
				total += parseFloat(objeto.importe);
			}
		}
		consultaTexto('respuestaCambioSeleccionado',"liquidaciondemercancia_con.php?accion=5&idpagina="+u.idpagina.value+"&guiasseleccionadas="+guias);
		u.entregado.value = convertirMoneda(total);
		diferencia_f(total)
	}
	
	function respuestaCambioSeleccionado(datos){
	}
	
	//facturacion de credito
	function facturar(){
		indFac = 0;
		var clientes = "";
		var guias = "";
		for(var i=0; i<tabla1.getRecordCount();i++){
			if(u["tablalista_CHECK"][i].checked==true && u["tablalista_PAGO"][i].value == 'CREDITO' && u["tablalista_FACTURA"][i].value=='0'){
				clientes += ((clientes!="")?",":"")+u["tablalista_IDDESTINATARIO"][i].value;
				guias += ((guias!="")?",":"")+u["tablalista_GUIA"][i].value;
			}
		}
		if(guias==""){
			alerta3("Seleccione las guias que desea facturar<br>Solo pueden ser guias por cobrar crédito","¡Atención!");
			return false;
		}
		clientesFacturar = clientes.split(",");
		guiasFacturar = guias.split(",");
		mostrarGuiaArreglo("0");
	}
	
	function mostrarGuiaArreglo(guia){
		setTimeout("mostrarGuiaArreglo2()",1500);
	}
	
	function mostrarGuiaArreglo2(){	
		if(guiasFacturar[indFac]==null || guiasFacturar[indFac]==""){
			obtenerGuia(u.folio.value);
			info("Se han completado las facturas","¡Atencion!");
		}else{
			mens.popup("../facturacion/Facturacion.php?&modificar=1&cliente="+clientesFacturar[indFac]+
					"&folio="+guiasFacturar[indFac]+"&indice="+
					indFac, 720, 480, 'vex', 'FACTURACION');
			indFac++;
		}
	}
	
	function shfact(){
		abrirVentanaFija("", 770, 480, 'vex', 'FACTURACION');
	}
	
	//obtener Conductor
	function obtenerConductor(idconductor){
		consultaTexto("mostrarConductor","liquidaciondemercancia_con.php?accion=3&idpagina="+u.idpagina.value+"&idempleado="+idconductor+"&and="+Math.random());
	}
	
	function mostrarConductor(datos){
		if (datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.entrego.value = obj[0].id;
			u.entregob.value = obj[0].conductor;
		}
	}
	
	function buscarFolio(){
		var campos = tabla1.getValuesFromField("guia");
		camposarreglo = campos.split(",");
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(camposarreglo[i] == u.guia.value){
				tabla1.setSelectedById("tablalista_id"+i);
				u["tablalista_CHECK"][i].checked=true;
			}
		}
		validacheck();
	}
	
	//guardar liquidacion
	function ValidarGuardar(tipo){
		var guias = "";
		var recibidos = "";
		cerrooguardo = tipo;
		for(var i=0;i<tabla1.getRecordCount();i++){
			if(u["tablalista_CHECK"][i].checked==true && u["tablalista_FACTURA"][i].value=='0' 
				&& u["tablalista_PAGO"][i].value=='CREDITO' && u["tablalista_TIPO_FLETE"][i].value=='PAGADO'){
				 guias += ((guias!="")?",":"")+u["tablalista_GUIA"][i].value;
			}
		}
		if(guias!=""){
			alerta3("No puede seleccionar a pagar guias a crédito que no estan facturadas.<br>"+guias,"¡Atención!");
			return false;
		}
		for(var i=0;i<tabla1.getRecordCount();i++){
			if(u["tablalista_NOMBRE"][i].value=="" && u["tablalista_ESTADO"][i].value=="ENTREGADA POR LIQUIDAR"){
				 recibidos += ((recibidos!="")?",":"")+u["tablalista_GUIA"][i].value;
			}
		}
		if(recibidos!=""){
			alerta3("No ha seleccionado quien recibio en las siguientes guias.<br>"+recibidos,"¡Atención!");
			return false;
		}
		if(u.folio.value==""){
			alerta("Por favor proporcione el folio reparto ead","¡Atencion!","folio");
		}else if(u.entrego.value==""){
			alerta("Por favor proporcione quien entrego","¡Atencion!","entrego");
		}else if(u.total.value==""){
			alerta("Por favor proporcione el folio reparto ead","¡Atencion!","folio");
		}else{
			var datosenviados = "accion=4&idreparto="+u.folio.value+"&entregadas="+u.entregadas.value+"&idpagina="+u.idpagina.value+
				  "&devueltas="+u.devueltas.value+"&pagadas_credito="+u.pcredito.value+"&pagadas_contado="+u.pcontado.value+
				  "&tpagadas_credito="+parseFloat(u.pcredito2.value.replace("$ ","").replace(/,/,""))+
				  "&tpagadas_contado="+parseFloat(u.pcontado2.value.replace("$ ","").replace(/,/,""))+"&porcobrar_contado="+u.ccontado.value+
				  "&porcobrar_credito="+u.ccredito.value+"&tporcobrar_contado="+parseFloat(u.ccontado2.value.replace("$ ","").replace(/,/,""))+
				  "&tporcobrar_credito="+parseFloat(u.ccredito2.value.replace("$ ","").replace(/,/,""))+"&sucursal="+u.idsucursal.value+
				  "&entrego="+u.entrego.value+"&fecha="+u.fecha.value+"&total="+parseFloat(u.total.value.replace("$ ","").replace(/,/,""))+
				  "&idusuario="+u.usuario.value+"&folio="+u.folio2.value+"&cerrar="+tipo+"&valram="+Math.random()+
				  "&cantidadentregada="+u.entregado.value.replace("$ ","").replace(/,/,"")+"&diferencia="+u.diferencia.value.replace("$ ","").replace(/,/,"")+
				  "&fechahora="+fechahora();
				  
			crearLoading();
			$.ajax({
			   type: "POST",
			   url: "liquidaciondemercancia_conjson.php",
			   data: datosenviados,
			   success: MostrarGuardar
			 });
			
			return false;
		}
	}
	
	function MostrarGuardar(datos){
		ocultarLoading();
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			u.folio2.value = row[1];
			info("La informacion ha sido guardada","");
			if(cerrooguardo==1){
				u.accion.value ="";
				u.guardar.style.visibility="hidden";
				u.cerrar.style.visibility="hidden";
				u.limpiar.style.visibility="visible";
				u.folio.readOnly=true;
				u.folio.style.backgroundColor="#FFFF99";	
				u.guia.focus();	
			}else{	
				u.cerrar.style.visibility = "visible";
			}
		}else{
			alerta("Hubo un error "+datos,"¡Atencion!","folio");
		}
	}
	
	function obtenerFolio(folio){
		u.folio2.value = folio;
		consultaTexto("mostrarDatosEncabezados","liquidaciondemercancia_con.php?accion=6&idpagina="+u.idpagina.value+"&folio="+folio+"&enc="+Math.random());		
	}
	
	function mostrarDatosEncabezados(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.folio.value			= obj.principal.idreparto;
			u.fecha.value			= obj.principal.fecha;
			u.idsucursal.value		= obj.principal.clave; 
			u.conductor.value		= obj.principal.conductor1;
			u.conductor2.value		= obj.principal.conductor2;
			u.unidad.value			= obj.principal.unidad;
			u.entregadas.value = obj.principal.entregadas;
			u.devueltas.value =  obj.principal.devueltas;
			u.pcredito.value = 	 obj.principal.pagadas_credito;
			u.pcontado.value =   obj.principal.pagadas_contado;
			u.pcredito2.value =  convertirMoneda(obj.principal.tpagadas_credito);
			u.pcontado2.value =  convertirMoneda(obj.principal.tpagadas_contado);
			u.ccontado.value =   obj.principal.porcobrar_contado;
			u.ccredito.value =   obj.principal.porcobrar_credito;
			u.ccontado2.value =  convertirMoneda(obj.principal.tporcobrar_contado);
			u.ccredito2.value =  convertirMoneda(obj.principal.tporcobrar_credito);
			u.total.value =      convertirMoneda(obj.principal.total);
			u.entregado.value =      convertirMoneda(obj.principal.cantidadentregada);
			u.diferencia.value =      obj.principal.diferencia;
			u.entrego.value =    obj.principal.entrego;
			u.entregob.value = obj.principal.empleado;
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
				u.guia.focus();	
			}
			
				var pagCredit = 0;
				var pagContad = 0;
				var cobContad = 0;
				var cobCredit = 0;
				var tpagCredit = 0;
				var tpagContad = 0;
				var tcobCredit = 0;
				var tcobContad = 0;
				tabla1.setJsonData(obj.detalle);
				
				for(var i=0;i<obj.detalle.length;i++){
					if(obj.detalle[i].seleccionada=='S'){
						u["tablalista_CHECK"][i].checked=true;
					}
pagCredit += parseFloat((obj.detalle[i].tipoflete == "PAGADO" && obj.detalle[i].condicionpago == "CREDITO")?1:0);
pagContad += parseFloat((obj.detalle[i].tipoflete == "PAGADO" && obj.detalle[i].condicionpago == "CONTADO")?1:0);
cobCredit += parseFloat((obj.detalle[i].tipoflete != "PAGADO" && obj.detalle[i].condicionpago == "CREDITO")?1:0);
cobContad += parseFloat((obj.detalle[i].tipoflete != "PAGADO" && obj.detalle[i].condicionpago == "CONTADO" && obj.detalle[i].estado == "ENTREGADA POR LIQUIDAR")?1:0);
					
					tpagCredit += parseFloat((obj.detalle[i].tipoflete == "PAGADO" && obj.detalle[i].condicionpago == "CREDITO")?obj.detalle[i].importe:0);
					tpagContad += parseFloat((obj.detalle[i].tipoflete == "PAGADO" && obj.detalle[i].condicionpago == "CONTADO")?obj.detalle[i].importe:0);
					tcobCredit += parseFloat((obj.detalle[i].tipoflete != "PAGADO" && obj.detalle[i].condicionpago == "CREDITO")?obj.detalle[i].importe:0);
					tcobContad += parseFloat((obj.detalle[i].tipoflete != "PAGADO" && obj.detalle[i].condicionpago == "CONTADO" && obj.detalle[i].estado == "ENTREGADA POR LIQUIDAR")?obj.detalle[i].importe:0);
				}	
				
				u.pcredito.value = pagCredit;
				u.pcontado.value = pagContad;
				u.ccredito.value = cobCredit;
				u.ccontado.value = cobContad;
				u.pcredito2.value = convertirMoneda(tpagCredit);
				u.pcontado2.value = convertirMoneda(tpagContad);
				u.ccredito2.value = convertirMoneda(tcobCredit);
				u.ccontado2.value = convertirMoneda(tcobContad);
				u.total.value = convertirMoneda(tcobContad);
		}
	}
	
	function limpiarTodo(){
			u.folio.value 		= "";
			u.folio2.value 		= "";
			u.fecha.value		= "";
			u.folio.value 		= "";
			u.unidad.value 		= "";
			u.conductor.value 	= "";
			u.conductor2.value 	= "";
			u.idsucursal.value	= "";
			u.entregadas.value 	= "";
			u.devueltas.value 	= "";
			u.pcredito.value 	= "";
			u.pcontado.value 	= "";
			u.pcredito2.value 	= "";
			u.pcontado2.value 	= "";
			u.ccontado.value 	= "";
			u.ccredito.value 	= "";
			u.ccontado2.value 	= "";
			u.ccredito2.value 	= "";
			u.total.value 		= "";
			u.entrego.value 	= "";
			u.entregob.value 	= "";
			u.estado.value		= "";
			u.entregado.value	= convertirMoneda(0);
			u.diferencia.value	= "";
			tabla1.clear();
			u.folio.readOnly=false;
			u.folio.style.backgroundColor="";
			tabla1.clear();
			u.accion.value="";
			u.cerrar.style.visibility="hidden";
			u.limpiar.style.visibility="visible";
			u.guardar.style.visibility="visible";
			obtenerInicio();
	}
	
	function mostrarDetalle(){
		var arr = tabla1.getSelectedRow();
		if(arr.estado=="ENTREGADA POR LIQUIDAR"){
			abrirVentanaFija("datosentrega.php?folio="+tabla1.getSelectedIdRow(),600,300,"ventana","DATOS PERSONALES","")
		}
	}
	
	function actualizarFila(datos){
		var fila = tabla1.getSelectedRow();
		var index = tabla1.getSelectedIdRow().split('id')[1];
		var checado = u["tablalista_CHECK"][index].checked;
		
		fila.nombre = datos.nombre
		fila.identificacion = datos.identificacion
		fila.numero_id = datos.numero_id
		if(fila.tipoflete=='POR COBRAR')
			fila.seleccion = 1;
		tabla1.updateRowById(tabla1.getSelectedIdRow(),fila);
		consultaTexto("MostrarGuardarDatosPersonales","liquidaciondemercancia_con.php?accion=7&idpagina="+u.idpagina.value+"&nombre="+
					  fila.nombre+"&identificacion="+fila.identificacion+"&numero="+
					  fila.numero_id+"&guia="+fila.guia+"&valram="+Math.random());
		u["tablalista_CHECK"][index].checked = checado;
	}
	function MostrarGuardarDatosPersonales(datos){
		if (datos.indexOf("ok")<0) {
			alerta("Hubo un error","¡Atencion!","folio");
		}else{
			info("Los datos de entrega ha sido guardada","");
		}
	}
	
	function actualizarFormaPago(datos){
		var fila = tabla1.getSelectedRow();
		actualizarDatosFormaPago(datos.efectivo,datos.cheque,datos.ncheque,datos.banco,datos.nnotacredito,datos.notacredito,fila.guia);
		info("Los datos de formas de pago ha sido guardada","");
	}
	
	function actualizarDatosFormaPago(efectivo,cheque,ncheque,banco,nnotacredito,notacredito,guia){
		consultaTexto("MostrarGuardarDatosFormasPago","liquidaciondemercancia_con.php?accion=8&idpagina="+u.idpagina.value+"&efectivo="+efectivo+"&cheque="+cheque+"&ncheque="+ncheque+"&banco="+banco+"&nnotacredito="+nnotacredito+"&notacredito="+notacredito+"&guia="+guia+"&valram="+Math.random());
	}
	
	function MostrarGuardarDatosFormasPago(datos){
		if (datos.indexOf("ok")>-1) {
		}else{
			alerta("Hubo un error","¡Atencion!","folio");
		}
	}
	
	function diferencia_f(valor){
		//u.h_importe.value = parseFloat(u.importe.value.replace("$ ","").replace(/,/g,"")) - parseFloat(valor.replace("$ ","").replace(/,/g,""));
		//u.entregado.value = convertirMoneda(u.entregado.value);
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
  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />
  <span class="Tablas">
<input name="usuario" type="hidden" class="Tablas" id="usuario" style="width:100px;background:#FFFF99" value="<?=$usuario?>" readonly=""/>
<input type="hidden" name="idpagina" id="idpagina" value="<?=$_SESSION[IDUSUARIO].date("ymdHis").$DM?>" />

</span><br>
<table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">LIQUIDACI&Oacute;N DE MERCANC&Iacute;A</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="615"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="130">F. Reparto EAD </td>
            <td width="93"><input name="folio" type="text" class="Tablas" id="folio" value="<?=$folio ?>" style="width:70px" onKeyPress="if(event.keyCode==13){obtenerGuia(this.value)}"/></td>
            <td width="46">
            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarFolioRepartos.php?funcion=obtenerGuia&tipo=preliquidacion',600,475,'ventana','BUSCAR LIQUIDACION MERCANCIA','');" />            </td>
            <td width="38">Fecha</td>
            <td width="106"><span class="Tablas">
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
            </span></td>
            <td width="55">Sucursal</td>
            <td width="170"><span class="Tablas">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal?>" readonly=""/>
              <input name="estado" type="hidden" class="Tablas" id="estado" style="width:100px;background:#FFFF99" value="<?=$estado?>" readonly=""/>
              <input name="facturadas" type="hidden" class="Tablas" id="facturadas" style="width:100px;background:#FFFF99" value="<?=$facturadas?>" readonly=""/>
              <input name="nofacturadas" type="hidden" class="Tablas" id="nofacturadas" style="width:100px;background:#FFFF99" value="<?=$nofacturadas?>" readonly=""/>
            </span></td>
            <td width="35">Folio</td>
            <td width="88"><input name="folio2" type="text" class="Tablas" id="folio2" style="width:80px" value="<?=$folio2 ?>" onkeypress="if(event.keyCode==13){obtenerFolio(this.value);}"/></td>
            <td width="38"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarliquidacionmercancia.php?funcion=obtenerFolio&tipo=M',600,475,'ventana','BUSCAR LIQUIDACION MERCANCIA','');"></div></td>
            </tr>
        </table></td>
        </tr>
      
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>Unidad</td>
            <td colspan="2"><span class="Tablas">
              <input name="unidad" type="text" class="Tablas" id="unidad" style="width:150px;background:#FFFF99"  readonly=""/>
            </span></td>
            <td>Conductor</td>
            <td><span class="Tablas">
              <input name="conductor" type="text" class="Tablas" id="conductor" style="width:305px;background:#FFFF99"  readonly=""/>
              <input name="idsucursal" type="hidden" class="Tablas" id="idsucursal" style="width:100px;background:#FFFF99" value="<?=$idsucursal?>" readonly=""/>
            </span></td>
          </tr>
          <tr>
            <td width="54"># Gu&iacute;a</td>
            <td width="124"><input name="guia" type="text" id="guia" onKeyPress="if(event.keyCode==13){buscarFolio();}" /></td>
            <td width="74"><input name="idconductor1" type="hidden" id="idconductor1" />
              <input name="idconductor2" type="hidden" id="idconductor2" /></td>
            <td width="121">Conductor</td>
            <td width="423"><span class="Tablas">
              <input name="conductor2" type="text" class="Tablas" id="conductor2" style="width:305px;background:#FFFF99"  readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><div id="txtDir" style=" height:200px; width:799px; overflow:auto" align="left">
			<table border="0" cellpadding="0" cellspacing="0" id="tablalista"></table>
			</div>		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="66">Entregadas</td>
            <td width="51"><span class="Tablas">
              <input name="entregadas" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
            </span></td>
            <td width="111">Por Cobrar Contado</td>
            <td width="151"><span class="Tablas">
              <input name="ccontado" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
              <input name="ccontado2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
            </span></td>
            <td>T. a Liquidar:</td>
            <td><span class="Tablas">
              <input name="total" type="text" class="Tablas" id="total" style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
            </span></td>
            <td>
            	<input type="hidden" name="pcredito" />
                <input type="hidden" name="pcredito2" />
            </td>
          </tr>
          <tr>
            <td>Devueltas</td>
            <td><span class="Tablas">
              <input name="devueltas" type="text" class="Tablas" id="devueltas" style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
            </span></td>
            <td>Pagadas Contado</td>
            <td><span class="Tablas">
              <input name="pcontado" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
              <input name="pcontado2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
            </span></td>
            <td width="121">Total Entregado:</td>
            <td width="182"><span class="Tablas">
              <input name="ccredito" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right; display:none"  readonly=""/>
            </span><span class="Tablas">
            <input name="entregado" type="text" class="Tablas" id="entregado" onfocus="this.select()" style="text-align:right;width:100px;" value="0" onkeypress="solonumeros(event); if(event.keyCode==13){diferencia_f(this.value);}" onkeydown="if(event.keyCode==9){diferencia_f(this.value);}" />
            </span></td>
            <td width="114"><span class="Tablas">
              <input name="ccredito2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right; display:none"  readonly=""/>
            </span></td>
          </tr>
          <tr>
            <td colspan="7"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="54"><div align="left">Entrego </div></td>
                <td width="107"><input name="entrego" type="text" id="entrego"  onkeypress="if(event.keyCode==13){obtenerConductor(this.value)} " /></td>
                <td width="39"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerConductor&amp;entrego=4750&amp;conductor1='+document.all.idconductor1.value+'&amp;conductor2='+document.all.idconductor2.value,500,500,'ventana','BUSCAR EMPLEADO','');"></div></td>
                <td width="596"><span class="Tablas">
                  <input name="entregob" type="text" class="Tablas" id="entregob" style="width:360px;background:#FFFF99"  readonly=""/>
                  <input name="h_importe" type="hidden" class="Tablas" id="h_importe"/>
                </span></td>
              </tr>
              <tr>
                <td colspan="4" align="center"><span class="Tablas">
                  <input name="diferencia" type="hidden" class="Tablas" id="diferencia"/>
                </span></td>
              </tr>
              <tr>
                <td colspan="4" align="center"><table width="401">
                    <tr>
                      <td width="82" ><img src="../img/Boton_Facturar.gif" onclick="facturar()" /></td>
                      <td width="131"><div id="cerrar" class="ebtn_cerrarliquidacion" onclick="confirmar('&iquest;Desea cerrar la liquidaci&oacute;n del folio de reparto EAD: '+ u.folio.value +'?', '', 'ValidarGuardar(1)', '');"></div></td>
                      <td width="84"><div id="guardar" style=":<? if($_POST[accion]=='grabar'){?>visibility:hidden<? }?>" class="ebtn_guardar" onclick="ValidarGuardar(0)"></div></td>
                      <td width="84"><div id="limpiar" class="ebtn_nuevo" onclick="confirmar('&iquest;Desea limpiar los datos?','&iexcl;Atencion!','limpiarTodo()','')"></div></td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
            </tr>
          
          
        </table>          </td>
      </tr>
      
      
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>

</html>