<?	session_start();



	require_once('../Conectar.php');



	$l = Conectarse('webpmm');



?>



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>



<SCRIPT type="text/javascript" src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>



<script src="../javascript/ClaseTabla.js"></script>



<script src="../javascript/ajax.js"></script>



<script>



	var tabla1 	= new ClaseTabla();



	var	u		= document.all;



	tabla1.setAttributes({



		nombre:"detalle",



		campos:[

		//onClick:"obtenerCliente"

			{nombre:"OC", medida:4, alineacion:"left", tipo:"oculto",datos:"oculto"},

			{nombre:"MOTIVO", medida:4, alineacion:"left", tipo:"oculto",datos:"motivo"},

			{nombre:"CLIENTE", medida:40, alineacion:"left", datos:"cliente"},			

			{nombre:"GUIA", medida:80, alineacion:"left", datos:"guia"},

			{nombre:"FECHA", medida:60, alineacion:"left", datos:"fecha"},

			{nombre:"FECHA_VTO", medida:60, alineacion:"left",  datos:"fechavencimiento"},

			{nombre:"FACTURA", medida:60, alineacion:"left", datos:"factura"},

			{nombre:"IMPORTE", medida:60, tipo:"moneda", alineacion:"left", datos:"importe"},

			{nombre:"SALDO_ACTUAL", medida:60, tipo:"moneda", alineacion:"left", datos:"saldoactual"},

			{nombre:"REVISION", medida:60, onDblClick:"agregarContraRecibo", alineacion:"left", datos:"revision"},

			{nombre:"COBRAR", medida:60, onDblClick:"agregarCompromiso", alineacion:"left", datos:"cobrar"},

			{nombre:"CONTRA_RECIBO", medida:60, onClick:"obtenerCliente",  alineacion:"left", datos:"contrarecibo"},

			{nombre:"COMPROMISO", medida:60, onClick:"obtenerCliente",  alineacion:"left", datos:"compromiso"}

			

		],

		filasInicial:30,

		alto:250,

		seleccion:true,

		ordenable:false,

		eventoClickFila:"mostrar()",

		nombrevar:"tabla1"

	});



	window.onload = function(){

		tabla1.create();

		obtenerGenerales();		

	}

	

	function mostrar(){

		if(tabla1.getSelectedRow()!=null){

			var arr = tabla1.getSelectedRow();

		consultaTexto("mostrarCliente","liquidacionCobranza_con.php?accion=5&cliente="+arr.cliente+"&factura="+arr.factura);

		}

	}

	

	function mostrarCliente(datos){

		var obj = eval(convertirValoresJson(datos));

		u.cliente.value			= obj[0].cliente;

		

	}



	function agregarContraRecibo(){

		var arr = tabla1.getSelectedRow();

		if (arr.oculto!="1"){

			if (arr.revision!="SI"){

				if (u.estados.value==""){

					confirmar("�Se Realizo la Revision?", "", "pantallacontrarecibo()", "motivos()");	

				}else if(u.estados.value!="LIQUIDADO"){

					confirmar("�Se Realizo la Revision?", "", "pantallacontrarecibo()", "motivos()");	

				}	

			}

		}

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

		abrirVentanaFija("motivos.php?folio="+tabla1.getSelectedIdRow(),600,200,"ventana","MOTIVOS","")

	}

	

	function agregarCompromiso(){

		var arr = tabla1.getSelectedRow();

		if (arr.oculto!="1"){

			if (arr.revision=="SI"){

				if (u.estados.value==""){

					confirmar('�Se realizo el cobro?', '', 'ventanaFormaPago()', 'ventanaCompromiso()');

				}else if(u.estados.value!="LIQUIDADO"){

					confirmar('�Se realizo el cobro?', '', 'ventanaFormaPago()', 'ventanaCompromiso()');	

				}

			}

		}

	}



	function ventanaCompromiso(){

		var arr = tabla1.getSelectedRow();

		abrirVentanaFija("registrodecompromiso.php?funcion=modificarCompromiso&cliente="+arr.cliente+

	((arr.compromiso!="")? "&compromiso="+arr.compromiso : "" )+((arr.factura!="")? "&factura="+arr.factura : ""), 525, 418, "ventana", "REGISTRO DE COMPROMISO");

	}



	function ventanaFormaPago(){

		var arr = tabla1.getSelectedRow();		

		abrirVentanaFija("formapago.php?funcion=modificarCobrar&factura="+arr.factura+'&cliente='+arr.cliente, 525, 418, "ventana", "FORMA DE PAGO");

	}



	function obtenerGenerales(){

		consultaTexto("mostrarGenerales","liquidacionCobranza_con.php?accion=1&sucursal="+'<?=$_SESSION[IDSUCURSAL]?>');

	}



	function mostrarGenerales(datos){

		var obj = eval(convertirValoresJson(datos));

		u.fecha.value = obj[0].fecha;

		u.sucursal.value = obj[0].sucursal;

		u.folio.value	= obj[0].folio;

		u.sucursal_hidden.value = '<?=$_SESSION[IDSUCURSAL]?>';

	}



	function obtenerFolioCobranzaBusqueda(folio){

		consultaTexto("obtenerFolioCobranzax","liquidacionCobranza_con.php?accion=0&folio="+u.cobranza_hidden.value);

		u.foliocobranza.value  = folio;

		u.cobranza_hidden.value=folio;		

	}



	function obtenerFolioCobranzax(datos){

		if(datos.indexOf("ok")>-1){

			consultaTexto("mostrarFolioCobranza","liquidacionCobranza_con.php?accion=2&folio="+u.foliocobranza.value+"&sucursal="+u.sucursal_hidden.value);

		}else{

			alerta3("Hubo un error al traer el detalle "+datos,"�Atenci�n!");

		}

	}



	function obtenerFolioCobranza(e,folio){

		tecla = (u) ? e.keyCode : e.which ;

		if(tecla == 13 && folio!=""){

			consultaTexto("mostrarFolioCobranza","liquidacionCobranza_con.php?accion=2&folio="+folio);

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

			consultaTexto("mostrarDetalle","liquidacionCobranza_con.php?accion=3&folio="+u.foliocobranza.value+"&sucursal="+u.sucursal_hidden.value);

		}else{

			alerta3("El folio de cobranza no existe o ya fue aplicado","�Atenci�n!");

		}

	}



	function mostrarDetalle(datos){

		if (datos!=0) {

			var obj = eval(datos);

			tabla1.clear();

			tabla1.setJsonData(obj);

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

					alerta('La fecha no es valida', '�Atenci�n!',name);

					u.dia.value = "";

					return false;

				}



				if (dia>"31" || dia=="0" ){

					alerta('La fecha no es valida, capture correctamente el Dia', '�Atenci�n!',name);

					u.dia.value = "";

					return false;	

				}

				if (mes>"12" || mes=="0" ){

					alerta('La fecha no es valida, capture correctamente el Mes', '�Atenci�n!',name);

					u.dia.value = "";

					return false;	

				}

				consultaTexto("mostrarDia","liquidacionCobranza_con.php?accion=4&fecha="+param);

			}	

		}

	}



	function obtenerDia(fecha){	

		consultaTexto("mostrarDia","liquidacionCobranza_con.php?accion=4&fecha="+fecha);

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

				tabla1.updateRowById("detalle_id"+i, obj);

				tabla1.setColorById('#FF0000','detalle_id'+i);



			}



		}



		consultaTexto("registrarModificacion","liquidacionCobranza_con.php?accion=8&contrarecibo="+contrarecibo+"&factura="+factura);



	}



	function modificarCompromiso(fecha,factura){

		var obj = Object();			

		for(var i=0;i<tabla1.getRecordCount();i++){			

			if(u["detalle_FACTURA"][i].value==factura){

				obj.cliente 			= u["detalle_CLIENTE"][i].value;

				obj.guia				= u["detalle_GUIA"][i].value;

				obj.fecha				= u["detalle_FECHA"][i].value;

				obj.fechavencimiento	= u["detalle_FECHA_VTO"][i].value;

				obj.factura				= u["detalle_FACTURA"][i].value;

				obj.importe				= parseFloat(u["detalle_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));

				obj.saldoactual			= parseFloat(u["detalle_SALDO_ACTUAL"][i].value.replace("$ ","").replace(/,/,""));

				obj.revision			= u["detalle_REVISION"][i].value;

				obj.cobrar				= "NO";

				obj.contrarecibo		= u["detalle_CONTRA_RECIBO"][i].value;

				obj.compromiso			= fecha;	

				obj.motivo				= u["detalle_MOTIVO"][i].value;

				obj.oculto				="1";

				tabla1.updateRowById("detalle_id"+i, obj);

			}			

		}

		consultaTexto("registrarModificacion","liquidacionCobranza_con.php?accion=9&compromiso="+fecha+"&factura="+factura);



	}



	



	function modificarCobrar(factura){

		var obj = Object();		

		var total=0;

		for(var i=0;i<tabla1.getRecordCount();i++){			

			if(u["detalle_FACTURA"][i].value==factura){

				obj.cliente 			= u["detalle_CLIENTE"][i].value;

				obj.guia				= u["detalle_GUIA"][i].value;

				obj.fecha				= u["detalle_FECHA"][i].value;

				obj.fechavencimiento	= u["detalle_FECHA_VTO"][i].value;

				obj.factura				= u["detalle_FACTURA"][i].value;

				obj.importe				= parseFloat(u["detalle_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));

				obj.saldoactual			= parseFloat(u["detalle_SALDO_ACTUAL"][i].value.replace("$ ","").replace(/,/,""));

				obj.revision			= u["detalle_REVISION"][i].value;

				obj.cobrar				= "SI";

				obj.contrarecibo		= u["detalle_CONTRA_RECIBO"][i].value;

				obj.compromiso			= u["detalle_COMPROMISO"][i].value;	

				obj.motivo				= u["detalle_MOTIVO"][i].value;	

				if (obj.cobrar="SI"){

					total += parseFloat(u["detalle_IMPORTE"][i].value.replace("$ ","").replace(/,/,""));

				}

				tabla1.updateRowById("detalle_id"+i, obj);			

			}

		}

		

		u.importe.value= convertirMoneda(total); 

		

		var arr = new Array();

		arr[0] = (u.efectivo.value != "")? u.efectivo.value.replace("$ ","").replace(/,/g,"") : "0";

		arr[1] = (u.cheque.value != "")? u.cheque.value.replace("$ ","").replace(/,/g,"") : "0";

		arr[2] = (u.banco.value != "")? u.banco.value.replace("$ ","").replace(/,/g,"") : "0";

		arr[3] = (u.ncheque.value != "")? u.ncheque.value : "";

		arr[4] = (u.tarjeta.value != "")? u.tarjeta.value.replace("$ ","").replace(/,/g,"") : "0";

		arr[5] = (u.transferencia.value !="")? u.transferencia.value.replace("$ ","").replace(/,/g,"") : "0";

		arr[6] = (u.nc.value != "")? u.nc.value.replace("$ ","").replace(/,/g,"") : "0";

		arr[7] = (u.nc_folio.value !="")? u.nc_folio.value.replace("$ ","").replace(/,/g,"") : "0";	

		//arr[8] = (u.importe.value !="")? u.importe.value.replace("$ ","").replace(/,/g,"") : "0";	

		consultaTexto("registrarModificacion","liquidacionCobranza_con.php?accion=10&arre="+arr+"&factura="+factura);

	}

	

	function registrarModificacion(datos){

		if(datos.indexOf("ok")<=-1){

			alerta3("Hubo un error al guardar "+datos,"�Atenci�n!");

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

			alerta("Debe capturar Folio Relaci�n Cobranza","�Atenci�n!","foliocobranza");

			return false;

		}else if(u.cobrador.value == 0 || u.cobrador.options[u.cobrador.options.selectedIndex].text==""){

			alerta("Debe capturar Cobrador","�Atenci�n!","cobrador");

			return false;

		}else{

			u.d_guardar.style.visibility = "hidden";

			var arr = new Array();

			arr[0] = u.fecha.value;

			arr[1] = u.sucursal_hidden.value;

			arr[2] = u.foliocobranza.value;

			arr[3] = u.cobrador.value;

			if(u.accion.value == ""){

				

				consultaTexto("registrarLiquidacion","liquidacionCobranza_con.php?accion=6&arre="+arr);			

			}else if(u.accion.value == "modificar"){

				

				consultaTexto("registrarLiquidacion","liquidacionCobranza_con.php?accion=13&arre="

				+arr+"&folio="+u.folio.value);	

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

		}else{

			u.d_guardar.style.visibility = "visible";

			alerta3("Hubo un error al guardar "+datos,"�Atenci�n!");

		}

	}



	function liquidarFactura(){

		var fact = tabla1.getSelectedRow();

		u.d_liquidar.style.visibility = "hidden";	

		consultaTexto("confirmarLiquidacion","liquidacionCobranza_con.php?accion=7&factura="+fact.factura);

	}



	function confirmarLiquidacion(datos){

		if(datos.indexOf("ok")>-1){

			u.d_liquidar.style.visibility = "visible";		

			info('La factura a sido aplicada correctamente','');

		}else{

			alerta3("Hubo un error al aplicar "+datos,"�Atenci�n!");

		}

	}



	function obtenerFolioLiquidacionBusqueda(folio){	

		u.folio.value = folio;

		consultaTexto("mostrarFolioLiquidacion","liquidacionCobranza_con.php?accion=11&folio="+folio);

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

		

		if(obj[0].estado == "LIQUIDADO"){

			u.d_guardar.style.visibility = "hidden";

			u.d_liquidar.style.visibility = "hidden";

		}

		consultaTexto("mostrarDetallebusqueda","liquidacionCobranza_con.php?accion=12&folio="+u.folio.value);

	}



	



	function mostrarDetallebusqueda(datos){

		var obj = eval(convertirValoresJson(datos));

		tabla1.setJsonData(obj);

		ponerenRojolasrevisadas();

	}

	

	function ponerenRojolasrevisadas(){

		for(var i=0; i<tabla1.getRecordCount();i++){	

				if (u["detalle_REVISION"][i].value=="SI" && u["detalle_COBRAR"][i].value=="NO"){

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

		u.importe.value			= "";

		u.estados.value			="";

		u.d_guardar.style.visibility = "visible";

		u.d_liquidar.style.visibility = "visible";

		tabla1.clear();

		obtenerGenerales();		

	}



	function validarlaformadepago(){

			consultaTexto("mostrarlaformadepago", "liquidacionCobranza_con.php?accion=15&and="+Math.random());

	}

	

	function validarsitienemovimiento(){

		for(var i=0; i<tabla1.getRecordCount();i++){	

			if (u["detalle_REVISION"][i].value=="NO" && u["detalle_COBRAR"][i].value=="NO" && u["detalle_MOTIVO"][i].value=="0"){

				alerta3("No se ha registrado ningun movimiento a la factura: " +u["detalle_FACTURA"][i].value+ "","�Atenci�n!");

				return false;

			}

		}			

		validarlaformadepago();

	}

	

	

	function mostrarlaformadepago(datos){

		if (datos!=0 && datos!=""){

			var objeto = eval(convertirValoresJson(datos));

			

			for(var i=0;i<objeto.length;i++){

				if (objeto[i].revision=='NO'){

					if (objeto[i].total==0){

						alerta3("Debe capturar la forma de pago de la factura: " +objeto[i].factura+ "","�Atenci�n!");

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

			alerta("Debe capturar Folio Relaci�n Cobranza","�Atenci�n!","foliocobranza");

			return false;

		}else if(u.cobrador.value == 0 || u.cobrador.options[u.cobrador.options.selectedIndex].text==""){

			alerta("Debe capturar Cobrador","�Atenci�n!","cobrador");

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

			consultaTexto("registrarLiquidacionLiquidar","liquidacionCobranza_con.php?accion=6&estado=LIQUIDADO&arre="+arr);

		}

	 }else if(u.accion.value == "modificar"){

		u.d_guardar.style.visibility = "hidden";

		u.d_liquidar.style.visibility = "hidden";

	 	u.estado.value	= "LIQUIDADO";

		u.estados.value = "LIQUIDADO";

	 	consultaTexto("registrarLiquidacionLiquidar","liquidacionCobranza_con.php?accion=7&folio="+u.folio.value+"&estado="+u.estado.value);

	 }

	}



	



	function registrarLiquidacionLiquidar(datos){

		if(datos.indexOf("ok")>-1){		

			info('El folio liquidacion ha sido aplicado correctamente','');

		}else{

			u.d_liquidar.style.visibility = "visible";

			alerta3('Hubo un error al aplicar '+datos,'�Atenci�n!');

		}

	}



	function obtenerFacturaBusqueda(factura){

		u.factura.value = factura;

		consultaTexto("mostrarFactura","liquidacionCobranza_con.php?accion=14&factura="+factura);

	}



	function obtenerFactura(e,factura){

		tecla = (u) ? e.keyCode : e.which;

		if (tecla == 13){

			if(tabla1.getRecordCount()>0 && factura!=""){

				consultaTexto("mostrarFactura","liquidacionCobranza_con.php?accion=14&factura="+factura);

			}else{

				if(factura!=""){

					alerta("No existen datos en el detalle","�Atenci�n!","foliocobranza");

				}

			}

		}

	}



	function mostrarFactura(datos){		

		var obj = eval(datos);

		tabla1.clear();

		tabla1.setJsonData(obj);

	}



	function validarFactura(e,obj){

		tecla = (u) ? e.keyCode : e.which;

	    if((tecla == 8 || tecla == 46) && document.getElementById(obj).value==""){

			tabla1.clear();

			consultaTexto("mostrarDetalle","liquidacionCobranza_con.php?accion=14&folio="+u.foliocobranza.value);

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

		var fila = tabla1.getSelectedRow();

		fila.clasificacion = datos.clasificacion

		tabla1.updateRowById(tabla1.getSelectedIdRow(),fila);

		actualizarMotivos(fila.clasificacion,fila.factura);

		info("Los motivos ha sido guardados","");

	}



	function actualizarMotivos(clasificacion,factura){

		var fila = tabla1.getSelectedRow();

		var usuario=<?=$_SESSION[IDUSUARIO] ?>;



		fila.motivo = clasificacion

		tabla1.updateRowById(tabla1.getSelectedIdRow(),fila);

		

		consultaTexto("mostrarMotivos","liquidaciondemercancia_con.php?accion=16&usuario="+usuario+"&motivo="+clasificacion+"&factura="+factura);

	}

	

	function mostrarMotivos(datos){

		if (datos!=0 && datos!=""){

		

		}else{

			alerta("Hubo un error","�Atencion!","foliocobranza");

		}

	}

	

	function obtenerSucursal(id,descripcion){

		u.sucursal_hidden.value	= id;

		u.sucursal.value = descripcion;

	}

	

	function BuscarSucursal(){

		abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 550, 450, 'ventana', 'Busqueda');

	}

	

	function permitirbuscarsucursal(){

		BuscarSucursal();

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



-->



</style>



<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />



<style type="text/css">



<!--



.Estilo4 {font-size: 12px}



.Estilo5 {



	font-size: 9px;



	font-family: tahoma;



	font-style: italic;



}



-->



</style>



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



          <td width="60">&nbsp;</td>



          <td width="137">&nbsp;</td>



          <td width="56">&nbsp;</td>



          <td width="179">&nbsp;</td>



          <td width="32">&nbsp;</td>



          <td width="156">&nbsp;</td>

        </tr>



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

                <input name="sucursal_hidden" type="text" id="sucursal_hidden" value="<?=$sucursal_hidden ?>" />

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



          <td>&nbsp;</td>



          <td>&nbsp;</td>

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



          <td>&nbsp;</td>



          <td colspan="2">&nbsp;</td>



          <td><input name="efectivo" type="hidden" id="efectivo">



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



          <td colspan="6"><div id="txtDir" style=" height:250px; width:700px; overflow:auto" align=left>



            <table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">

            </table>



          </div></td>

        </tr>

<tr>



          <td colspan="6"><table width="600" border="0" cellpadding="0" cellspacing="0">



            <tr>



              <td width="49">Cliente:</td>



              <td width="318"><span class="Tablas">



                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:250px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>



                <input name="accion" type="hidden" id="accion">



                <input name="estado" type="hidden" id="estado">



              </span></td>



              <td width="81">Total a Pagar: </td>



              <td width="133"><span class="Tablas">



                <input name="importe" type="text" class="Tablas" id="importe" style="text-align:right;width:100px;background:#FFFF99" value="<?=$tpagar ?>" readonly=""/>



              </span></td>



              <td width="19">&nbsp;</td>

            </tr>



          </table></td>

        </tr>



        <tr>



          <td colspan="6"><div align="right">



            <table width="250" border="0" cellspacing="0" cellpadding="0">



              <tr>



                <td><div class="ebtn_guardar" id="d_guardar" onClick="guardar();" ></div></td>



                <td>&nbsp;</td>



                <td><div class="ebtn_Aplicar" id="d_liquidar" onClick="confirmar('�Desea Aplicar el folio de liquidaci�n '+ document.all.folio.value+' ?', '', 'validarsitienemovimiento()', '')"></div></td>



                <td>&nbsp;</td>



                <td><div class="ebtn_imprimir"></div></td>



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