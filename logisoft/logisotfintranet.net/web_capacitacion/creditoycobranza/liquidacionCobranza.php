<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<SCRIPT type="text/javascript" src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var tabla2 	= new ClaseTabla();
	var	u		= document.all;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[		
			{nombre:"OC", medida:4, alineacion:"left", tipo:"oculto",datos:"oculto"},
			{nombre:"MOTIVO", medida:4, alineacion:"left", tipo:"oculto",datos:"motivo"},
			{nombre:"CLIENTE", medida:40, alineacion:"left", datos:"cliente"},			
			{nombre:"GUIA", medida:80, alineacion:"left", datos:"guia"},
			{nombre:"FECHA", medida:60, alineacion:"left", datos:"fecha"},
			{nombre:"FECHA_VTO", medida:60, alineacion:"left",  datos:"fechavencimiento"},
			{nombre:"FACTURA", medida:60, onDblClick:"muestralosmotivos" ,alineacion:"left", datos:"factura"},
			{nombre:"IMPORTE", medida:60, tipo:"moneda", alineacion:"left", datos:"importe"},
			{nombre:"SALDO_ACTUAL", medida:60, tipo:"moneda", alineacion:"left", datos:"saldoactual"},
			{nombre:"REVISION", medida:60, onDblClick:"agregarContraRecibo", alineacion:"left", datos:"revision"},
			{nombre:"COBRAR", medida:60, alineacion:"left", datos:"cobrar", tipo:"oculto"},
			{nombre:"CONTRA_RECIBO", medida:60,  alineacion:"left", datos:"contrarecibo"},
			{nombre:"COMPROMISO", medida:60,  alineacion:"left", datos:"compromiso"}			
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"detalle2",
		campos:[		
			{nombre:"OC", medida:4, alineacion:"left", tipo:"oculto",datos:"oculto"},
			{nombre:"MOTIVO", medida:4, alineacion:"left", tipo:"oculto",datos:"motivo"},
			{nombre:"CLIENTE", medida:40, alineacion:"left", datos:"cliente"},			
			{nombre:"GUIA", medida:120, alineacion:"left", datos:"guia"},
			{nombre:"FECHA", medida:60, alineacion:"left", datos:"fecha"},
			{nombre:"FECHA_VTO", medida:60, alineacion:"left",  datos:"fechavencimiento"},
			{nombre:"FACTURA", medida:60, alineacion:"left", datos:"factura"},
			{nombre:"IMPORTE", medida:60, tipo:"moneda", alineacion:"left", datos:"importe"},
			{nombre:"SALDO_ACTUAL", medida:60, tipo:"moneda", alineacion:"left", datos:"saldoactual"},
			{nombre:"COBRAR", medida:60, alineacion:"left", datos:"cobrar"},
			{nombre:"CONTRA_RECIBO", medida:60, alineacion:"left", datos:"contrarecibo"},
			{nombre:"COMPROMISO", medida:60, alineacion:"left", datos:"compromiso"},
			{nombre:"SEL", medida:20, alineacion:"left", tipo:"checkbox", datos:"seleccion"}
		],
		filasInicial:10,
		alto:150,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		tabla2.create();
		obtenerGenerales();		
		u.entregado.value = convertirMoneda(u.entregado.value);
		u.importe.value = convertirMoneda(u.importe.value);
	}
	
	function mostrar(){
		if(tabla1.getSelectedRow()!=null){
			var arr = tabla1.getSelectedRow();
		consultaTexto("mostrarCliente","liquidacionCobranza_con.php?accion=5&cliente="+arr.cliente+"&factura="+arr.factura+'&rdnm='+Math.random());
		}
	}
	
	function mostrarCliente(datos){
		var obj = eval(convertirValoresJson(datos));
		u.cliente.value	= obj[0].cliente;		
	}
	function agregarContraRecibo(){
		var arr = tabla1.getSelectedRow();
		if (arr.oculto!="1"){
			if (arr.revision!="SI"){
				if (u.estados.value==""){
					confirmar("¿Se Realizo la Revision?", "", "pantallacontrarecibo()", "motivos()");	
				}else if(u.estados.value!="LIQUIDADO"){
					confirmar("¿Se Realizo la Revision?", "", "pantallacontrarecibo()", "motivos()");	
				}	
			}
		}
	}
	
	function muestralosmotivos(){
		var arr = tabla1.getSelectedRow();
		abrirVentanaFija("motivos.php?factura="+arr.factura,600,200,"ventana","MOTIVOS","")
	}
	
	function pantallacontrarecibo(){
		var arr = tabla1.getSelectedRow();
		var factura=arr.factura;
		var cliente=arr.cliente;
		var contrarecibo=arr.contrarecibo;
		abrirVentanaFija("registrodecontrarecibos.php?funcion=modificarRevision&cliente="+cliente+
	((contrarecibo!="")? "&contrare="+contrarecibo : "" )+((factura!="")? "&factura="+factura : ""), 525, 370, "ventana", "REGISTRO DE CONTRARECIBOS");
	}
	
	function motivos(){
		abrirVentanaFija("motivos.php?folio="+tabla1.getSelectedIdRow(),600,400,"ventana","MOTIVOS","")
	}
	
	function agregarCompromiso(){
		var cantsel = tabla2.getSelCountField("SEL");
		if(cantsel>1){
			alerta3("Para asignar un pago solo puede seleccionar un registro","¡ATENCION!");
			return false;
		}
		if(cantsel<1){
			alerta3("No ha seleccionado facturas a pagar","¡ATENCION!");
			return false;
		}
		if(u.estados.value!="LIQUIDADO"){
			confirmar('¿Se realizo el cobro?', '', 'ventanaFormaPago()', 'ventanaCompromiso()');	
		}
	}
	function ventanaCompromiso(){
		var arr = new Object();
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(document.all["detalle2_SEL"][i].checked){
				arr.factura = document.all["detalle2_FACTURA"][i].value;
				arr.cliente = document.all["detalle2_CLIENTE"][i].value;
				arr.compromiso = document.all["detalle2_COMPROMISO"][i].value;
				break;
			}
		}
		abrirVentanaFija("registrodecompromiso.php?funcion=modificarCompromiso&cliente="+arr.cliente+
		((arr.compromiso!="")? "&compromiso="+arr.compromiso : "" )+((arr.factura!="")? "&factura="+arr.factura : ""), 
		525, 418, "ventana", "REGISTRO DE COMPROMISO");
	}
	function ventanaFormaPago(){
		var arr = new Object();
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(document.all["detalle2_SEL"][i].checked){
				arr.factura = document.all["detalle2_FACTURA"][i].value;
				arr.cliente = document.all["detalle2_CLIENTE"][i].value;
				break;
			}
		}
		abrirVentanaFija("formapago.php?funcion=modificarCobrar&factura="+arr.factura+'&cliente='+arr.cliente, 525, 418, "ventana", "FORMA DE PAGO");
	}
	function obtenerGenerales(){
		consultaTexto("mostrarGenerales","liquidacionCobranza_con.php?accion=1&sucursal="+'<?=$_SESSION[IDSUCURSAL]?>&rdnm='+Math.random());
	}
	function mostrarGenerales(datos){
		var obj = eval(convertirValoresJson(datos));
		u.fecha.value = obj[0].fecha;
		u.sucursal.value = obj[0].sucursal;
		u.folio.value	= obj[0].folio;
		u.sucursal_hidden.value = '<?=$_SESSION[IDSUCURSAL]?>';
	}
	function obtenerFolioCobranzaBusqueda(folio){
		consultaTexto("obtenerFolioCobranzax","liquidacionCobranza_con.php?accion=0&folio="+u.cobranza_hidden.value+'&rdnm='+Math.random());
		u.foliocobranza.value  = folio;
		u.cobranza_hidden.value=folio;		
	}
	function obtenerFolioCobranzax(datos){
		if(datos.indexOf("ok")>-1){
			consultaTexto("mostrarFolioCobranza","liquidacionCobranza_con.php?accion=2&folio="+u.foliocobranza.value+"&sucursal="+u.sucursal_hidden.value+
						  '&rdnm='+Math.random());
		}else{
			alerta3("Hubo un error al traer el detalle "+datos,"¡Atención!");
		}
	}
	function obtenerFolioCobranza(e,folio){
		tecla = (u) ? e.keyCode : e.which ;
		if(tecla == 13 && folio!=""){
			consultaTexto("mostrarFolioCobranza","liquidacionCobranza_con.php?accion=2&folio="+folio+"&sucursal="+u.sucursal_hidden.value+'&rdnm='+Math.random());
		}
	}
	function mostrarFolioCobranza(datos){
	
		if(datos!=0){
			var obj = eval(convertirValoresJson(datos));
			u.fechaal.value = obj[0].fecharelacion;
			u.sector.value	= obj[0].sector;
			u.cobrador.value= obj[0].cobrador;
			u.cobrador.focus();
			mostrarDia(obj[0].dia);
			consultaTexto("mostrarDetalle","liquidacionCobranza_con.php?accion=3&folio="+u.foliocobranza.value+"&sucursal="+u.sucursal_hidden.value+
						  '&rdnm='+Math.random());
		}else{
			alerta3("El folio de cobranza no existe o ya fue aplicado","¡Atención!");
		}
	}
	function mostrarDetalle(datos){
		if (datos!=0) {
			var obj = eval(datos);
			tabla1.clear();
			tabla1.setJsonData(obj.registrosr);
			tabla2.setJsonData(obj.registrosc);
			
			var total = 0;
			for(var i=0;i<tabla2.getRecordCount();i++){			
					total += parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
			}
			//u.importe.value = total;
			ponerenRojolasrevisadas();
		}		
	}
	function validarFecha(e,param,name){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,2),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					alerta('La fecha no es valida', '¡Atención!',name);
					u.dia.value = "";
					return false;
				}
				if (dia>"31" || dia=="0" ){
					alerta('La fecha no es valida, capture correctamente el Dia', '¡Atención!',name);
					u.dia.value = "";
					return false;	
				}
				if (mes>"12" || mes=="0" ){
					alerta('La fecha no es valida, capture correctamente el Mes', '¡Atención!',name);
					u.dia.value = "";
					return false;	
				}
				consultaTexto("mostrarDia","liquidacionCobranza_con.php?accion=4&fecha="+param+'&rdnm='+Math.random());
			}	
		}
	}
	function obtenerDia(fecha){	
		consultaTexto("mostrarDia","liquidacionCobranza_con.php?accion=4&fecha="+fecha+'&rdnm='+Math.random());
	}
	function mostrarDia(datos){
		switch(datos){			
			case "1":
				u.dia.value = "DOMINGO";
			break;
			case "2":
				u.dia.value = "LUNES";
			break;
			case "3":				
				u.dia.value = "MARTES";
			break;	
			case "4":
				u.dia.value = "MIERCOLES";
			break;
			case "5":
				u.dia.value = "JUEVES";
			break;
			case "6":
				u.dia.value = "VIERNES";
			break;
			case "7":
				u.dia.value = "SABADO";
			break;
		}
	}
	function modificarRevision(contrarecibo,factura){
		var obj = Object();
		var total = 0;
		for(var i=0;i<tabla1.getRecordCount();i++){			
			if(u["detalle_FACTURA"][i].value==factura){
				obj.cliente 			= u["detalle_CLIENTE"][i].value;
				obj.guia				= u["detalle_GUIA"][i].value;
				obj.fecha				= u["detalle_FECHA"][i].value;
				obj.fechavencimiento	= u["detalle_FECHA_VTO"][i].value;
				obj.factura				= u["detalle_FACTURA"][i].value;
				obj.importe				= parseFloat(u["detalle_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
				obj.saldoactual			= parseFloat(u["detalle_SALDO_ACTUAL"][i].value.replace("$ ","").replace(/,/,""));
				obj.revision			= "SI";
				obj.cobrar				= u["detalle_COBRAR"][i].value;
				obj.contrarecibo		= contrarecibo;
				obj.compromiso			= u["detalle_COMPROMISO"][i].value;	
				obj.motivo				= u["detalle_MOTIVO"][i].value;	
				obj.oculto				="1";
				if (obj.cobrar=="SI"){
					total += parseFloat(u["detalle_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
				}
				tabla1.updateRowById("detalle_id"+i, obj);
				tabla1.setColorById('#FF0000','detalle_id'+i);
			}
		}
		
		u.importe.value = convertirMoneda(total);
		
		consultaTexto("registrarModificacion","liquidacionCobranza_con.php?accion=8&contrarecibo="+contrarecibo+"&factura="+factura+'&rdnm='+Math.random());
	}
	function modificarCompromiso(fecha,factura){
		var obj = new Object();
		var total = 0;			
		var entregado = 0;			
		for(var i=0;i<tabla2.getRecordCount();i++){		
			total += parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));	
			if(u["detalle2_SEL"][i].checked){
				obj.cliente 			= u["detalle2_CLIENTE"][i].value;
				obj.guia				= u["detalle2_GUIA"][i].value;
				obj.fecha				= u["detalle2_FECHA"][i].value;
				obj.fechavencimiento	= u["detalle2_FECHA_VTO"][i].value;
				obj.factura				= u["detalle2_FACTURA"][i].value;
				obj.importe				= parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
				obj.saldoactual			= parseFloat(u["detalle2_SALDO_ACTUAL"][i].value.replace("$ ","").replace(/,/,""));
				obj.cobrar				= "NO";
				obj.contrarecibo		= u["detalle2_CONTRA_RECIBO"][i].value;
				obj.compromiso			= fecha;	
				obj.motivo				= u["detalle2_MOTIVO"][i].value;
				obj.oculto				="1";	
				obj.seleccion			= '';
				if (obj.cobrar=="SI"){
					entregado += parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
				}
				tabla2.updateRowById("detalle2_id"+i, obj);
			}			
		}
		u.entregado.value = convertirMoneda(entregado);
		u.importe.value= convertirMoneda(entregado);//TOTAL
		consultaTexto("registrarModificacion","liquidacionCobranza_con.php?accion=9&compromiso="+fecha+"&factura="+factura+'&rdnm='+Math.random());
	}
	function modificarCobrar(factura){
		var obj = Object();		
		var total=0;		
		var entregado=0;
		for(var i=0;i<tabla2.getRecordCount();i++){
			total += parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
			
			if(u["detalle2_SEL"][i].checked){
				obj.cliente 			= u["detalle2_CLIENTE"][i].value;
				obj.guia				= u["detalle2_GUIA"][i].value;
				obj.fecha				= u["detalle2_FECHA"][i].value;
				obj.fechavencimiento	= u["detalle2_FECHA_VTO"][i].value;
				obj.factura				= u["detalle2_FACTURA"][i].value;
				obj.importe				= parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
				obj.saldoactual			= parseFloat(u["detalle2_SALDO_ACTUAL"][i].value.replace("$ ","").replace(/,/,""));
				obj.cobrar				= "SI";
				obj.contrarecibo		= '';
				obj.compromiso			= '';	
				obj.motivo				= '';	
				obj.seleccion			= '';
				tabla2.updateRowById("detalle2_id"+i, obj);
			}
			if (u["detalle2_COBRAR"][i].value=="SI"){
				entregado += parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
				u["detalle2_SEL"][i]
			}
		}
		u.entregado.value = convertirMoneda(entregado);
		u.importe.value= convertirMoneda(entregado);//total
		
		var arr = new Array();
		arr[0] = (u.efectivo.value != "")? u.efectivo.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[1] = (u.cheque.value != "")? u.cheque.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[2] = (u.banco.value != "")? u.banco.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[3] = (u.ncheque.value != "")? u.ncheque.value : "";
		arr[4] = (u.tarjeta.value != "")? u.tarjeta.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[5] = (u.transferencia.value !="")? u.transferencia.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[6] = (u.nc.value != "")? u.nc.value.replace("$ ","").replace(/,/g,"") : "0";
		arr[7] = (u.nc_folio.value !="")? u.nc_folio.value.replace("$ ","").replace(/,/g,"") : "0";	
		arr[8] = (u.importe.value !="")? u.importe.value.replace("$ ","").replace(/,/g,"") : "0";	
		consultaTexto("registrarModificacion","liquidacionCobranza_con.php?accion=10&arre="+arr+"&factura="+factura+'&rdnm='+Math.random());
	}
	
	function registrarModificacion(datos){
		if(datos.indexOf("ok")<=-1){
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
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

	function guardar(){
		if(u.foliocobranza.value == ""){
			alerta("Debe capturar Folio Relación Cobranza","¡Atención!","foliocobranza");
			return false;
		}else if(u.cobrador.value == 0 || u.cobrador.options[u.cobrador.options.selectedIndex].text==""){
			alerta("Debe capturar Cobrador","¡Atención!","cobrador");
			return false;
		}else{
			u.d_guardar.style.visibility = "hidden";
			var arr = new Array();
			arr[0] = u.fecha.value;
			arr[1] = u.sucursal_hidden.value;
			arr[2] = u.foliocobranza.value;
			arr[3] = u.cobrador.value;
			if(u.accion.value == ""){
				consultaTexto("registrarLiquidacion","liquidacionCobranza_con.php?accion=6&arre="+arr+'&rdnm='+Math.random());			
			}else if(u.accion.value == "modificar"){
				consultaTexto("registrarLiquidacion","liquidacionCobranza_con.php?accion=13&arre="+
				arr+"&folio="+u.folio.value+'&rdnm='+Math.random());	
			}
		}
	}
	
	function registrarLiquidacion(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			u.d_guardar.style.visibility = "visible";
			u.folio.value = row[1];
			u.accion.value = "modificar";
			info('Los datos han sido guardados correctamente','');
			u.btnImprimir.style.visibility = "visible";
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
		}
	}
	function liquidarFactura(){
		var fact = tabla1.getSelectedRow();
		u.d_liquidar.style.visibility = "hidden";	
		consultaTexto("confirmarLiquidacion","liquidacionCobranza_con.php?accion=7&factura="+fact.factura+'&rdnm='+Math.random());
	}
	function confirmarLiquidacion(datos){
		if(datos.indexOf("ok")>-1){
			u.d_liquidar.style.visibility = "visible";		
			info('La factura a sido aplicada correctamente','');
		}else{
			alerta3("Hubo un error al aplicar "+datos,"¡Atención!");
		}
	}
	function obtenerFolioLiquidacionBusqueda(folio){	
		u.folio.value = folio;
		consultaTexto("mostrarFolioLiquidacion","liquidacionCobranza_con.php?accion=11&folio="+folio+"&sucursal="+u.sucursal_hidden.value+'&rdnm='+Math.random());
	}
	function mostrarFolioLiquidacion(datos){
		var obj					 = eval(convertirValoresJson(datos));
		u.fecha.value			 = obj[0].fechaliquidacion;
		u.sucursal.value		 = obj[0].sucursal;
		u.sucursal_hidden.value	 = obj[0].idsucursal;
		u.foliocobranza.value	 = obj[0].foliocobranza;
		u.fechaal.value			 = obj[0].fechaal;
		mostrarDia(obj[0].dia);
		u.sector.value			 = obj[0].sector;
		u.cobrador.value		 = obj[0].cobrador;
		u.accion.value			 = "modificar";
		u.estados.value			 = obj[0].estado;
		u.entregado.value			= convertirMoneda(obj[0].cantidadentregada);
		if(obj[0].estado == "LIQUIDADO"){
			u.d_guardar.style.visibility = "hidden";
			u.d_liquidar.style.visibility = "hidden";
		}else{
			u.d_guardar.style.visibility = "visible";
			u.d_liquidar.style.visibility = "visible";
		}
		consultaTexto("mostrarDetallebusqueda","liquidacionCobranza_con.php?accion=12&folio="+u.folio.value+
					  "&sucursal="+u.sucursal_hidden.value+'&rdnm='+Math.random());
	}
	
	function mostrarDetallebusqueda(datos){
		var obj = eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj.registrosr);
		tabla2.setJsonData(obj.registrosc);
		var total=0;		
		var entregado=0;
		for(var i=0;i<tabla2.getRecordCount();i++){
			total += parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));	
			if (u["detalle2_COBRAR"][i].value=="SI"){
				entregado += parseFloat(u["detalle2_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));
			}
		}
		u.entregado.value = convertirMoneda(entregado);
		u.importe.value= convertirMoneda(entregado);//total
	}
	
	function ponerenRojolasrevisadas(){
		for(var i=0; i<tabla1.getRecordCount();i++){	
			if(u["detalle_REVISION"][i].value=="SI" && u["detalle_COBRAR"][i].value=="NO"){
				tabla1.setColorById('#FF0000','detalle_id'+i);
			}
		}
	}
	
	function limpiar(){
		u.foliocobranza.value	= "";
		u.cobranza_hidden.value	= "";
		u.fechaal.value			= "";
		u.dia.value				= "";
		u.sector.value			= "";
		u.cobrador.value		= 0;
		u.factura.value			= "";
		u.efectivo.value		= "";
		u.cheque.value			= "";
		u.banco.value			= "";
		u.ncheque.value			= "";
		u.tarjeta.value			= "";
		u.transferencia.value	= "";
		u.cliente.value			= "";
		u.accion.value			= "";
		u.importe.value			= 0;
		u.entregado.value		= 0;
		u.entregado.value = convertirMoneda(u.entregado.value);
		u.importe.value = convertirMoneda(u.importe.value);
		u.estados.value			= "";
		u.d_guardar.style.visibility = "visible";
		u.d_liquidar.style.visibility = "visible";
		tabla1.clear();
		tabla2.clear();
		obtenerGenerales();		
	}
	function validarlaformadepago(){
			consultaTexto("mostrarlaformadepago", "liquidacionCobranza_con.php?accion=15&and="+Math.random());
	}
	
	function validarsitienemovimiento(){
		for(var i=0; i<tabla1.getRecordCount();i++){	
			if (u["detalle_REVISION"][i].value=="NO" && u["detalle_COBRAR"][i].value=="NO" && u["detalle_MOTIVO"][i].value=="0"){
				alerta3("No se ha registrado ningun movimiento a la factura en revision: " +u["detalle_FACTURA"][i].value+ "","¡Atención!");
				return false;
			}
		}
		
		for(var i=0; i<tabla2.getRecordCount();i++){	
			if (u["detalle2_COBRAR"][i].value=="NO" && u["detalle2_COMPROMISO"][i].value==""){
				alerta3("No se ha registrado ningun movimiento a la factura en cobranza: " +u["detalle2_FACTURA"][i].value+ "","¡Atención!");
				return false;
			}
		}
		
		if(u.entregado.value.replace("$ ","").replace(/,/g,"") < 0){
			alerta("El Total entregado debe ser mayor a Cero","¡Atencion!","entregado");
			return false;
		}
		if(parseFloat(u.importe.value.replace("$ ","").replace(/,/g,"")) != parseFloat(u.entregado.value.replace("$ ","").replace(/,/g,""))){
			u.h_importe.value = parseFloat(u.importe.value.replace("$ ","").replace(/,/g,"")) - parseFloat(u.entregado.value.replace("$ ","").replace(/,/g,""));
			confirmar('Existen diferencias entre el total a pagar y el total entregado, ¿Desea continuar?', '', 'validarlaformadepago()', '');			
		}else{		
			validarlaformadepago();
		}
	}
	
	
	function mostrarlaformadepago(datos){
		if (datos!=0 && datos!=""){
			var objeto = eval(convertirValoresJson(datos));
			
			for(var i=0;i<objeto.length;i++){
				if (objeto[i].revision=='NO'){
					if (objeto[i].total==0){
						alerta3("Debe capturar la forma de pago de la factura: " +objeto[i].factura+ "","¡Atención!");
						return false;
					}
				}
			}
			
			liquidar();
		}
	}
	function liquidar(){
		if(u.accion.value == ""){
			if(u.foliocobranza.value == ""){
				alerta("Debe capturar Folio Relación Cobranza","¡Atención!","foliocobranza");
				return false;
			}else if(u.cobrador.value == 0 || u.cobrador.options[u.cobrador.options.selectedIndex].text==""){
				alerta("Debe capturar Cobrador","¡Atención!","cobrador");
				return false;
			}else{
				u.d_guardar.style.visibility = "hidden";
				u.d_liquidar.style.visibility = "hidden";
				u.estado.value	= "LIQUIDADO";
				var arr = new Array();
				arr[0] = u.fecha.value;
				arr[1] = u.sucursal_hidden.value;
				arr[2] = u.foliocobranza.value;
				arr[3] = u.cobrador.value;
				consultaTexto("registrarLiquidacionLiquidar","liquidacionCobranza_con.php?accion=6&estado=LIQUIDADO&arre="+arr+
							  "&diferencia="+u.h_importe.value+"&cantidadentregada="+u.entregado.value.replace("$ ","").replace(/,/g,"")+
							  '&rdnm='+Math.random());
			}
		}else if(u.accion.value == "modificar"){
			u.d_guardar.style.visibility = "hidden";
			u.d_liquidar.style.visibility = "hidden";
			u.estado.value	= "LIQUIDADO";
			u.estados.value = "LIQUIDADO";
			var arr = new Array();
			arr[0] = u.fecha.value;
			arr[1] = u.sucursal_hidden.value;
			arr[2] = u.foliocobranza.value;
			arr[3] = u.cobrador.value;
			consultaTexto("registrarLiquidacionLiquidar","liquidacionCobranza_con.php?accion=7&folio="+u.folio.value+"&arre="+arr+
						  "&estado="+u.estado.value+"&diferencia="+u.h_importe.value+"&cantidadentregada="+
						  u.entregado.value.replace("$ ","").replace(/,/g,"")+'&rdnm='+Math.random());
		}
	}
	
	function registrarLiquidacionLiquidar(datos){
		if(datos.indexOf("ok")>-1){		
			info('El folio liquidacion ha sido aplicado correctamente','');
		}else{
			u.d_liquidar.style.visibility = "visible";
			alerta3('Hubo un error al aplicar '+datos,'¡Atención!');
		}
	}
	
	function obtenerFacturaBusqueda(factura){
		u.factura.value = factura;
		consultaTexto("mostrarFactura","liquidacionCobranza_con.php?accion=14&factura="+factura+'&rdnm='+Math.random());
	}
	
	function obtenerFactura(e,factura){
		tecla = (u) ? e.keyCode : e.which;
		if (tecla == 13){
			if(tabla1.getRecordCount()>0 && factura!=""){
				consultaTexto("mostrarFactura","liquidacionCobranza_con.php?accion=14&factura="+factura+'&rdnm='+Math.random());
			}else{
				if(factura!=""){
					alerta("No existen datos en el detalle","¡Atención!","foliocobranza");
				}
			}
		}
	}
	
	function mostrarFactura(datos){
		var obj = eval(datos);
		tabla1.clear();
		tabla1.setJsonData(obj.registrosr);
		tabla2.clear();
		tabla2.setJsonData(obj.registrosc);
	}
	function validarFactura(e,obj){
		tecla = (u) ? e.keyCode : e.which;
	    if((tecla == 8 || tecla == 46) && document.getElementById(obj).value==""){
			tabla1.clear();
			consultaTexto("mostrarDetalle","liquidacionCobranza_con.php?accion=14&folio="+u.foliocobranza.value+'&rdnm='+Math.random());
		}
	}
	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57));
	}
	function devolverSucursal(){
		if(u.sucursal_hidden.value==""){
			setTimeout("devolverSucursal()",500);
		}
	}
	
	function actualizarFila(datos){
		//alert(datos);
		var fila = tabla1.getSelectedRow();
		fila.clasificacion = datos.clasificacion
		tabla1.updateRowById(tabla1.getSelectedIdRow(),fila);
		actualizarMotivos(fila.clasificacion,fila.factura);
		info("Los motivos ha sido guardados","");
	}
	function actualizarMotivos(clasificacion,factura){
		//alert("liquidaciondemercancia_con.php?accion=16&usuario="+usuario+"&motivo="+clasificacion+"&factura="+factura);
		var fila = tabla1.getSelectedRow();
		var usuario='<?=$_SESSION[IDUSUARIO] ?>';
		fila.motivo = clasificacion
		tabla1.updateRowById(tabla1.getSelectedIdRow(),fila);
		//alerta3("liquidaciondemercancia_con.php?accion=16&usuario="+usuario+"&motivo="+clasificacion+"&factura="+factura,"¡Atencion!");
		consultaTexto("mostrarMotivos","liquidacionCobranza_con.php?accion=16&usuario="+usuario+"&motivo="+clasificacion+"&factura="+factura+'&rdnm='+Math.random());
	}
	
	function mostrarMotivos(datos){
		if (datos.indexOf("ok")>-1){
			
		}else{
			alerta3(datos,"¡Atencion!");
		}
	}
	
	function obtenerSucursal(id,descripcion){
		u.sucursal_hidden.value	= id;
		u.sucursal.value = descripcion;
		limpiarcambiosucursal();
	}
	
	function limpiarcambiosucursal(){
		u.foliocobranza.value	= "";
		u.cobranza_hidden.value	= "";
		u.fechaal.value			= "";
		u.dia.value				= "";
		u.sector.value			= "";
		u.cobrador.value		= 0;
		u.factura.value			= "";
		u.efectivo.value		= "";
		u.cheque.value			= "";
		u.banco.value			= "";
		u.ncheque.value			= "";
		u.tarjeta.value			= "";
		u.transferencia.value	= "";
		u.cliente.value			= "";
		u.accion.value			= "";
		u.importe.value			= "";
		u.estados.value			= "";
		u.d_guardar.style.visibility = "visible";
		u.d_liquidar.style.visibility = "visible";
		tabla1.clear();
	}
	
	function BuscarSucursal(){
		abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 550, 450, 'ventana', 'Busqueda');
	}
	
	function permitirbuscarsucursal(){
		BuscarSucursal();
	}
	
	function diferencia(valor){
		u.h_importe.value = parseFloat(u.importe.value.replace("$ ","").replace(/,/g,"")) - parseFloat(valor.replace("$ ","").replace(/,/g,""));
		u.entregado.value = convertirMoneda(u.entregado.value);
	}
	
	function agrupar(){
		var facturas = String();
		
		if(tabla2.getSelCountField("SEL")<2){
			alerta3("Seleccione mas de una factura para agrupar","¡ATENCION!");
			return false;
		}
		
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(document.all["detalle2_SEL"][i].checked){
				facturas += ((facturas!="")?",":"")+document.all["detalle2_FACTURA"][i].value;
			}
		}
		consultaTexto("resAgrupar","liquidacionCobranza_con.php?accion=18&facturas="+facturas+"&rand="+Math.random());
	}
	function resAgrupar(datos){
		var obj = eval(datos);
		tabla2.setJsonData(obj.registrosc);
	}
	function desagrupar(){
		var facturas = String();
		
		if(tabla2.getSelCountField("SEL")<1){
			alerta3("Seleccione un registro para desagrupar","¡ATENCION!");
			return false;
		}
		
		if(tabla2.getSelCountField("SEL")>1){
			alerta3("Seleccione un registro a la vez","¡ATENCION!");
			return false;
		}
		
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(document.all["detalle2_SEL"][i].checked){
				if(document.all["detalle2_FACTURA"][i].value.indexOf(",")<0){
					alerta3("Seleccione facturas agrupadas","¡ATENCION!");
					return false;
				}
				facturas += ((facturas!="")?",":"")+document.all["detalle2_FACTURA"][i].value;
			}
		}
		consultaTexto("resDesagrupar","liquidacionCobranza_con.php?accion=19&facturas="+facturas+"&rand="+Math.random());
	}
	function resDesagrupar(datos){
		var obj = eval(datos);
		tabla2.setJsonData(obj.registrosc);
	}
</script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
<!--
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
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="600" class="FondoTabla Estilo4">LIQUIDACI&Oacute;N DE COBRANZA</td>
  </tr>
  <tr>
    <td height="98"><div align="center">
      <table width="690" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="6"><table width="685" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="68">Fecha:</td>
              <td width="153"><span class="Tablas">
                <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
              </span></td>
              <td width="61">Sucursal:</td>
              <td width="238"><span class="Tablas">
                <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>
                <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$sucursal_hidden ?>" />
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=RelacionCobranza&usuario=Admin&funcion=permitirbuscarsucursal', 370, 500, 'ventana', 'Inicio de Sesi&oacute;n Secundaria')" /></span></td>
              <td width="30">Folio:</td>
              <td width="135"><span class="Tablas">
                <input name="folio" type="text" class="Tablas" id="folio" style="width:80px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
               <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('../buscadores_generales/buscarFolioLiquidacionCobranzaGen.php?sucursal='+u.sucursal_hidden.value+'&funcion=obtenerFolioLiquidacionBusqueda', 610, 550, 'ventana', 'Busqueda')">
               <input name="estados" type="hidden" class="Tablas" id="estados" style="width:100px;background:#FFFF99" value="<?=$estados ?>" readonly=""/>
              </span></td>
            </tr>
          </table></td>
        </tr>
        
        
        <tr>
          <td colspan="6" class="FondoTabla">Datos del Reporte </td>
        </tr>
        <tr>
          <td colspan="4"><span style="width:120px">Folio Relaci&oacute;n Cobranza</span>:<span class="Tablas">
            <input name="foliocobranza" type="text" class="Tablas" id="foliocobranza" style="width:100px;"
			onKeyPress="return Numeros(event)" onKeyDown="if(document.all.accion.value==''){obtenerFolioCobranza(event,this.value);}" maxlength="15"/>
          </span><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="if(document.all.accion.value ==''){abrirVentanaFija('../buscadores_generales/buscarFolioCobranzaGen.php?funcion=obtenerFolioCobranzaBusqueda&criterio=1&sucursal='+u.sucursal_hidden.value, 625, 550, 'ventana', 'Busqueda')}">
          <input name="cobranza_hidden" type="hidden" id="cobranza_hidden"></td>
          <td width="32">&nbsp;</td>
          <td width="156">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6"><table width="599" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="60">Fecha al: </td>
              <td width="138"><span class="Tablas">
                <input name="fechaal" type="text" class="Tablas" id="fechaal" style="width:100px; background:#FFFF99" onChange="obtenerDia(this.value)" />
              </span></td>
              <td width="32">D&iacute;a:</td>
              <td width="119"><span class="Tablas">
                <input name="dia" type="text" class="Tablas" id="dia" style="width:100px; background:#FFFF99"/>
              </span></td>
              <td width="43">Sector:</td>
              <td width="207"><span class="Tablas">
                <input name="sector" type="text" class="Tablas" id="sector" style="width:100px; background:#FFFF99"/>
              </span></td>
            </tr>
          </table></td>
          </tr>
        
        <tr>
          <td colspan="6"><table width="683" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="60"  style="width:60px">Cobrador:</td>
              <td width="200" style="width:200px"><label>
                <select name="cobrador" class="Tablas" style="text-transform:uppercase">
					<option selected="selected" value="0">SELECCIONAR COBRADOR</option>
				<? 
					$s = "SELECT id, CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno)
					AS cobrador FROM catalogoempleado WHERE puesto=22";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
					<option value="<?=$f->id; ?>"><?=$f->cobrador; ?></option>
				<? } ?>
                </select>
              </label></td>
              <td width="123" >&nbsp;</td>
              <td width="44" >Factura:</td>
              <td width="102" ><span class="Tablas">
                <input name="factura" type="text" class="Tablas" id="factura" style="width:100px;"
			onKeyPress="return Numeros(event)" onKeyDown="obtenerFactura(event,this.value);" onKeyUp="return validarFactura(event,this.name)" maxlength="15"/>
              </span></td>
              <td width="154" ><span class="Tablas"><span style="width:200px"><img src="../img/Buscar_24.gif" alt="" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="if(tabla1.getRecordCount()>0){abrirVentanaFija('../buscadores_generales/buscarFacturaLiquidacionCobranzaGen.php?funcion=obtenerFacturaBusqueda', 610, 450, 'ventana', 'Busqueda');}else{alerta('No existen datos en el detalle','&iexcl;Atenci&oacute;n!','foliocobranza');}"></span></span></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td width="60">&nbsp;</td>
          <td width="193" colspan="2">&nbsp;</td>
          <td width="179"><input name="efectivo" type="hidden" id="efectivo">
            <input name="cheque" type="hidden" id="cheque">
            <input name="banco" type="hidden" id="banco">
            <input name="ncheque" type="hidden" id="ncheque">
            <input name="tarjeta" type="hidden" id="tarjeta">
            <input name="transferencia" type="hidden" id="transferencia">
            <input type="hidden" name="nc_folio">
            <input type="hidden" name="nc">            </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6">Facturas para revisión</td>
        </tr>
		<tr>
          <td colspan="6"><div id="txtDir" style=" height:150px; width:700px; overflow:auto" align=left>
            <table width="380" id="detalle" border="0" cellpadding="0" cellspacing="0">
            </table>
          </div></td>
        </tr>
        <tr>
          <td colspan="6">Facturas para pago</td>
        </tr>
        <tr>
          <td colspan="6"><div id="txtDir2" style=" height:150px; width:700px; overflow:auto" align=left>
            <table width="380" id="detalle2" border="0" cellpadding="0" cellspacing="0">
            </table>
          </div></td>
        </tr>
        <tr>
          <td colspan="6">
            <table width="300" align="center" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td align="center"><img src="../img/Boton_Pagar.gif" onClick="agregarCompromiso()" style="cursor:pointer"></td>
                    <td align="center"><img src="../img/Boton_Desagrupar.gif" onClick="desagrupar()" style="cursor:pointer"></td>
                    <td align="center"><img src="../img/Boton_Agrupar.gif" onClick="agrupar()" style="cursor:pointer"></td>
                </tr>
            </table>
          </td>
        </tr>
<tr>
          <td colspan="6"><table width="700" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="46">&nbsp;</td>
              <td width="297"><span class="Tablas">
                <input name="cliente" type="hidden" class="Tablas" id="cliente" style="width:250px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>
                <input name="accion" type="hidden" id="accion">
                <input name="estado" type="hidden" id="estado">
              </span></td>
              <td width="60">T. a Pagar: </td>
              <td width="113"><span class="Tablas">
                <input name="importe" type="text" class="Tablas" id="importe" style="text-align:right;width:100px;background:#FFFF99" value="0" readonly=""/>
              </span></td>
              <td width="73">T. Entregado: </td>
              <td width="111"><span class="Tablas">
               <input name="entregado" type="text" class="Tablas" id="entregado" onFocus="this.select()" style="text-align:right;width:100px" value="0" onKeyPress="solonumeros(event); if(event.keyCode==13){diferencia(this.value);}" onKeyDown="if(event.keyCode==9){diferencia(this.value);}" />
              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="6"><span class="Tablas">
            <input name="h_importe" type="hidden" class="Tablas" id="h_importe"/>
          </span></td>
        </tr>
        
        <tr>
          <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6"><div align="right">
            <table width="250" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div id="btnImprimir" class="ebtn_imprimir" style="visibility:hidden"></div></td>
                <td>&nbsp;</td>
                <td><div class="ebtn_Aplicar" id="d_liquidar" onClick="confirmar('¿Desea Aplicar el folio de liquidación '+ document.all.folio.value+' ?', '', 'validarsitienemovimiento()', '')"></div></td>
                <td>&nbsp;</td>
                <td><div class="ebtn_guardar" id="d_guardar" onClick="guardar();" ></div></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')"></div></td>
              </tr>
            </table>
            </div></td>
        </tr>
        <tr>
          <td colspan="6"></td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
</form>
</body>
</html>