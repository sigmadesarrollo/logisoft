<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$cantidad=$_GET[cantidad];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ajax.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script>
	var u = document.all;

	var botonesnuevo = '<table width="174" align="center"><tr>'
			+'<td><div class="ebtn_imprimir" id="btnimprimir" style="visibility:hidden" onclick="imprimirSolicitud()"></div></td>'
			+'<td><div class="ebtn_guardar" id="btnguardar" onclick="guardarSolicitud();"></div></td>'
			+'<td></td>'
            +'</tr>'
            +'</table>';
	var botonescargar = '<table width="174" align="center"><tr>'
			+'<td><div class="ebtn_imprimir" id="btnimprimir" style="visibility:hidden" onclick="imprimirSolicitud()"></div></td>'
			+'<td></td>'
            +'</tr>'
            +'</table>';
	var botonescancelar = '<table width="174" align="center"><tr>'
			+'<td><div class="ebtn_imprimir" id="btnimprimir" style="visibility:hidden" onclick="imprimirSolicitud()"></div></td>'
			+'<td><div class="ebtn_cancelar" id="btncancelar" onclick="confirmar(\'�Desea cancelar la solicitud?\',\'�Atencion!\',\'cancelar()\');"></div></td>'
			+'<td></td>'
            +'</tr>'
            +'</table>';


	window.onload= function(){
		if(u.essolicitud.value!=""){
			pedirCliente('<?=$_GET[ncliente]?>');
		}
		
			
			<?
				$_GET[funcion] = str_replace("\'","'",$_GET[funcion]);
				if($_GET[funcion]!=""){
					echo 'setTimeout("'.$_GET[funcion].'",500);';
					}
			?>
		
	}

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
	
	function bloquearTodo(valor){
		u.img_buscarCliente.style.visibility = (valor)?"hidden":"visible";
		u.seleccionradio[0].disabled = valor;
		u.seleccionradio[1].disabled = valor;
		u.lstpago.disabled = valor;
		u.nocliente.readOnly = valor;
		u.cantidadfolios.readOnly = valor;
		u.nocliente.style.backgroundColor = (valor)?"#FFFF99":"";
		u.cantidadfolios.style.backgroundColor = (valor)?"#FFFF99":"";
	}
	
	function limpiar(){
		document.getElementById('estado').innerHTML = "";
		u.seleccionradio[0].checked = false;
		u.seleccionradio[1].checked = false;
		u.facturada.value = "";
		bloquearTodo(false);
		u.factura.value     =   "";
		u.nocliente.value	=	"";
		u.nombre.value		=	"";
		u.appaterno.value	=	"";
		u.apmaterno.value	=	"";
		u.convenio_radios.style.display				="none";
		u.convenio_pesokg.style.display				="none";
		u.convenio_preciocaja.style.display			="none";
		u.convenio_serviciosucursal.style.display	="none";
		u.costoguia.value							="";
		u.preciokgexcedente.value 					= "";
		u.serviciosr2_sel.options.length 			= 0;
		u.sucursalesead3_sel.options.length 		= 0;
		u.idconvenio.value							= "";
		u.idsolicitud.value							= "";
		u.calculos_finicio.value					= "";
		u.calculos_ffinal.value						= "";
		u.calculos_cantidad.value					= "";
		u.subtotal.value							= "";
		u.combustible.value							= "";
		u.iva.value									= "";
		u.ivar.value								= "";
		u.total.value								= "";
		u.guardada.value							= "";
		u.ventas.value								= "";
		u.cantidadfolios.value						= "";
		u.personamoral.value						= "";
		consultaTexto("resPedirFolio","solicitudguiasempresariales_con.php?accion=7");
		u.celdabotones.innerHTML					= botonesnuevo;
		u.radiobutton[0].checked	= false;
		u.radiobutton[1].checked	= false;
		u.radiobutton[2].checked	= false;
		u.chkprepagada.checked	= false;
		u.seleccionradio[0].disabled = false;
		u.seleccionradio[1].disabled = false;	
		u.btnimprimir.style.visibility = "hidden";
	}
	
	function limpiarCliente(){
		u.seleccionradio[0].checked = false;
		u.seleccionradio[1].checked = false;
		bloquearTodo(false);
		u.nocliente.value	=	"";
		u.nombre.value		=	"";
		u.appaterno.value	=	"";
		u.apmaterno.value	=	"";
		u.convenio_radios.style.display				="none";
		u.convenio_pesokg.style.display				="none";
		u.convenio_preciocaja.style.display			="none";
		u.convenio_serviciosucursal.style.display	="none";
		u.costoguia.value							="";
		u.preciokgexcedente.value					="";
		u.serviciosr2_sel.options.length 			= 0;
		u.sucursalesead3_sel.options.length 		= 0;
		u.idconvenio.value							= "";
		
		u.calculos_finicio.value					= "";
		u.calculos_ffinal.value						= "";
		u.calculos_cantidad.value					= "";
		u.subtotal.value							= "";
		u.combustible.value							= "";
		u.iva.value									= "";
		u.ivar.value								= "";
		u.total.value								= "";
		u.guardada.value							= "";
		u.ventas.value								= "";
		u.cantidadfolios.value						= "";
		u.personamoral.value						= "";
		u.celdabotones.innerHTML					= botonesnuevo;
		u.radiobutton[0].checked	= false;
		u.radiobutton[1].checked	= false;
		u.radiobutton[2].checked	= false;
		u.chkprepagada.checked	= false;
	}
	function agregarValores(combo,objeto,combo2){
		combo.options.length = 0;
		var opcion;
		
		if(objeto[0]!=undefined){
			if(objeto[0].nombre=="TODOS"){
				for(var i=0; i<combo2.options.length; i++){
					opcion = new Option(combo2.options[i].text,combo2.options[i].value);
					combo.options[combo.options.length] = opcion;
				}
			}else{		
				for(var i=0; i<objeto.length; i++){
					opcion = new Option(objeto[i].nombre,objeto[i].clave);
					combo.options[combo.options.length] = opcion;
				}
			}
		}
	}
	function pedirCliente(id,solicitud){
		if(id==""){
			alerta("Por favor ingrese el numero de cliente", "�Atencion!","nocliente");
		}else{
			if(solicitud!=undefined){
				u.idsolicitud.value = solicitud;
			}
			limpiarCliente();
			u.nocliente.value = id;
			consultaTexto("mostrarCliente","solicitudguiasempresariales_con.php?accion=1&idcliente="+id
						  +((u.seleccionradio[0].checked)?'&tiposol=PREPAGADA':'&tiposol=CONSIGNACION')
						  +"&ranm="+Math.random()+"&solicitud="+((solicitud!=undefined)?solicitud:0));
		}
	}
	function mostrarCliente(datos){
		var objeto = eval(convertirValoresJson(datos));
		if(objeto[0].datoscliente[0].id!=undefined){
			u.nocliente.value 	= objeto[0].datoscliente[0].id;
			u.nombre.value 		= objeto[0].datoscliente[0].nombre;
			u.appaterno.value 	= objeto[0].datoscliente[0].paterno;
			u.apmaterno.value 	= objeto[0].datoscliente[0].materno;
			u.personamoral.value= objeto[0].datoscliente[0].personamoral;
			u.creditodisponible.value	= objeto[0].datoscliente[0].disponible;
			
			if(objeto[0].creditable=="NO"){
				u.lstpago.value = "0";
				u.lstpago.disabled = true;
			}else{ 
				u.lstpago.value = "1";
				u.lstpago.disabled = false;
			}
			if(objeto[0].datoscliente[0].desactivado=="SI"){
				alerta3("El cr�dito del Cliente esta desactivado","�Atenci�n!");
				u.creditodesactivado.value = "SI";
				u.lstpago.value = "0";
			}
			
			
			
			if(objeto[0].datoscliente[0].folio != undefined && objeto[0].datoscliente[0].folio != ""){
				u.idconvenio.value		= objeto[0].datoscliente[0].folio;
				u.limitekg.value		= objeto[0].datoscliente[0].limitekg;
				u.costoguia.value		= objeto[0].datoscliente[0].costo;
				u.preciokgexcedente.value	= objeto[0].datoscliente[0].preciokgexcedente;
				u.consignaciondes.value = objeto[0].datoscliente[0].consignaciondescantidad;
				u.por_combustible.value	= objeto[0].datoscliente[0].cargocombustible;
				u.por_iva.value			= objeto[0].datoscliente[0].iva;
				u.por_ivaretenido.value	= objeto[0].datoscliente[0].ivaretenido;				
				
				if(u.personamoral.value=="SI")
					var ivar		 	= parseFloat(objeto.importe)*(parseFloat(u.por_ivaretenido.value)/100);
				else
					var ivar		 	= 0;				
				
				if(objeto[0].datoscliente[0].prepagadas==1 && (objeto[0].datoscliente[0].consignacionkg==1 
					|| objeto[0].datoscliente[0].consignacioncaja==1 || objeto[0].datoscliente[0].consignaciondescuento==1)){
					u.seleccionradio[0].checked = false;
					u.seleccionradio[1].checked = false;
				}else if(objeto[0].datoscliente[0].prepagadas==1){
					u.seleccionradio[0].checked = true;
					pedirFolios();
				}else{
					u.seleccionradio[1].checked = true;
					pedirFolios();
				}
				u.convenio_radios.style.display="";
				if(objeto[0].datoscliente[0].prepagadas==1){
					u.chkprepagada.checked = true;
				}
				if(objeto[0].datoscliente[0].consignacionkg==1){
					u.radiobutton[0].checked = true;
					u.convenio_pesokg.style.display="";
					consultaTexto("mostrarSGridKg", "solicitudguiasempresariales_con.php?accion=2&valor=1&idconvenio="+objeto[0].datoscliente[0].folio)
				}
				if(objeto[0].datoscliente[0].consignacioncaja==1){					
					u.radiobutton[1].checked = true;
					u.convenio_preciocaja.style.display="";					
					consultaTexto("mostrarSGridPeso", "solicitudguiasempresariales_con.php?accion=2&valor=2&idconvenio="+objeto[0].datoscliente[0].folio)
				}
				if(objeto[0].datoscliente[0].consignaciondescuento==1){
					u.radiobutton[2].checked = true;
				}
				if(objeto[0].servicios != "" || objeto[0].sucursales != ""){
					u.convenio_serviciosucursal.style.display="";
					agregarValores(u.serviciosr2_sel,objeto[0].servicios,u.serviciosr1);
					agregarValores(u.sucursalesead3_sel,objeto[0].sucursales,u.sucursalesead1);
				}
			}
			
			u.ivar.value		 		= "$ "+numcredvar(ivar.toLocaleString());
			esNan('ivar');
			
			if('<?=$_GET[ncliente]?>'!=""){
				pedirFolios2('<?=$_GET[preocon]?>');
				u.cantidadfolios.value='<?=$_GET[cantidad]?>';
				pedirCantidadFolios('<?=$_GET[cantidad]?>');
			}else{
				if(objeto[0].solicitudes[0].preocon=="PREPAGADA"){
				pedirFolios2("SI");
				}else{
					pedirFolios2("NO");				
				}
				u.cantidadfolios.value 	= objeto[0].solicitudes[0].cantidad;
				pedirCantidadFolios(objeto[0].solicitudes[0].cantidad);
			}
		}else{
			alerta("No se encontro el cliente", "�Atencion!","nocliente");
		}
	}
	
	function mostrarSGridKg(datos){
		u.detallekgc.innerHTML = datos;
	}
	function mostrarSGridPeso(datos){		
		u.detalledescripcionc.innerHTML = datos;
	}
	
	function pedirCantidadFolios(cantidad){
		if(cantidad=="" || cantidad=="0"){
			alerta("Introdusca una cantidad para los folios", "�Atencion!","cantidadfolios");
		}else{
			if(u.idconvenio.value!=""){
				if(u.costoguia.value=="")
					costoguia = 0;
				else
					costoguia = u.costoguia.value;
				consultaTexto("respuestaCantidad", "solicitudguiasempresariales_con.php?accion=3&cantidad="+cantidad+"&costo="+costoguia);
			}else{
				alerta("El cliente seleccionado no tiene convenio","�Atencion!","cantidadfolios");
			}
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
	function respuestaCantidad(datos){
		var objeto = eval("("+convertirValoresJson(datos)+")");
		u.calculos_cantidad.value 	= objeto.importe;
		u.calculos_finicio.value 	= objeto.folioinicio;
		u.calculos_ffinal.value 	= objeto.foliofinal;
		
		
		var combustible 		= parseFloat(objeto.importe)*(parseFloat(u.por_combustible.value)/100);		
		var subtotal 			= parseFloat(objeto.importe)+combustible;
		var iva 				= parseFloat(subtotal)*(parseFloat(u.por_iva.value)/100);
		if(u.personamoral.value=="SI")
			var ivar		 	= parseFloat(subtotal)*(parseFloat(u.por_ivaretenido.value)/100);
		else
			var ivar		 	= 0;
		var total 				= parseFloat(subtotal) + parseFloat(iva) - parseFloat(ivar);
		
		
		u.importe.value				= "$ "+numcredvar(objeto.importe.toLocaleString());
		u.subtotal.value 			= "$ "+numcredvar(subtotal.toLocaleString());
		esNan('subtotal');
		u.combustible.value 		= "$ "+numcredvar(combustible.toLocaleString());
		esNan('combustible');
		u.iva.value 				= "$ "+numcredvar(iva.toLocaleString());
		esNan('iva');
		u.ivar.value		 		= "$ "+numcredvar(ivar.toLocaleString());
		esNan('ivar');
		u.total.value 				= "$ "+numcredvar(total.toLocaleString());
		esNan('total');
		if(u.seleccionradio[1].checked==true){
			u.importe.value				= "$ 0.00";
			u.subtotal.value 			= "$ 0.00";
			u.combustible.value 		= "$ 0.00";
			u.iva.value 				= "$ 0.00";
			u.ivar.value		 		= "$ 0.00";
			u.total.value 				= "$ 0.00";
			u.calculos_cantidad.value	= "";
		}
	}
	
	function guardarSolicitud(){
		if(u.comboSucursal.value==0){
			alerta3("Seleccione la sucursal a cobrar","�Atencion!");
			return false;
		}
		if(u.nocliente.value==""){
			alerta3("Debe seleccionar un folio de asignacion para poder guardarlo","�Atencion!");
			return false;
		}
		if(u.guardada.value==""){				
			if(!u.seleccionradio[0].checked && !u.seleccionradio[1].checked){
				alerta("Seleccione si la solicitud es prepagada o consignacion","�Atencion!","seleccionradio[0]");
				return false;
			}
			if(u.nombre.value==""){
				alerta("Seleccione el cliente de la solicitud","�Atencion!","nocliente");
				return false;
			}
			if(u.cantidadfolios.value==""){
				alerta("Proporcione la cantidad de guias solicitadas","�Atencion!","cantidadfolios");
				return false;
			}
			
			if(u.creditodesactivado.value == "SI" && u.lstpago.value=="1"){
				u.lstpago.value = "0";
				alerta("El cr�dito del cliente #"+u.nocliente.value+" esta Bloqueado y no se le puede vender a Cr�dito","�Atenci�n!","lstpago");				
				return false;
			}else if(u.lstpago.value=="1" && parseFloat(u.creditodisponible.value) < parseFloat(u.total.value.replace("$ ","").replace(/,/g,""))){
				alerta("El cliente #"+u.nocliente.value+" no cuenta con suficiente cr�dito para realizar la compra","�Atenci�n!","lstpago");				
				return false;
			}
			var condicionpago	= (u.lstpago.value=="0")?"CONTADO":"CREDITO";			
			var idcliente		= u.nocliente.value;
			var idconvenio		= u.idconvenio.value;
			var nombre			= u.nombre.value;
			var apepat			= u.appaterno.value;
			var apemat			= u.apmaterno.value;
			var prepagada		= (u.seleccionradio[0].checked==true)?"SI":"NO";
			var cantidad		= u.cantidadfolios.value;
			var desdefolio		= u.calculos_finicio.value;
			var hastafolio		= u.calculos_ffinal.value;
			var subtotal		= u.subtotal.value.replace("$ ","").replace(/,/g,"");
			var combustible		= u.combustible.value.replace("$ ","").replace(/,/g,"");
			var iva				= u.iva.value.replace("$ ","").replace(/,/g,"");
			var ivar			= u.ivar.value.replace("$ ","").replace(/,/g,"");
			var total			= u.total.value.replace("$ ","").replace(/,/g,"");			
			var sucursal		= u.comboSucursal.value;
			
			consultaTexto("resGuardar","solicitudguiasempresariales_con.php?accion=4&idcliente="+idcliente+"&condicionpago="+condicionpago+
						  "&nombre="+nombre+"&apepat="+apepat+"&apemat="+apemat+"&idconvenio="+idconvenio+"&prepagada="+prepagada+
						  "&cantidad="+cantidad+"&desdefolio="+desdefolio+"&hastafolio="+hastafolio+"&subtotal="+subtotal+
						  "&combustible="+combustible+"&iva="+iva+"&ivar="+ivar+"&total="+total+'&folio='+u.idsolicitud.value+
						  "&sucursalacobrar="+sucursal);
			if(u.idsolicitud.value!=""){
				window.parent.document.all.btnAutorizar.style.visibility="hidden";
			}						  
		}else{
			alerta3("La solicitud ya ha sido guardada","�Atencion!");
		}
	}
	function resGuardar(datos){
		if(datos.indexOf("guardo")>-1){
			document.getElementById('btnguardar').style.display = 'none';
			info("La solicitud de guias ha sido guardada", "�Atencion!");
			document.getElementById('estado').innerHTML = 'GUARDADA';
			bloquearTodo(true);
			var arre = datos.split(",");
			u.folio.value = arre[1];
			u.guardada.value = 1;
			u.btnimprimir.style.visibility = "visible";
		}else{
			alerta3("error al guardar<br>"+datos, "�Atencion!");
		}
	}
	
	function pedirSolicitud(datos){
		limpiarCliente();
		u.folio.value = datos;
		consultaTexto("mostrarSolicitud","solicitudguiasempresariales_con.php?accion=5&idsolicitud="+datos);
	}
	
	function mostrarSolicitud(datos){
		
		var objeto = eval(convertirValoresJson(datos));
		
		u.folio.value		= objeto.datoscliente.id;
		u.nocliente.value 	= objeto.datoscliente.idcliente;
		u.nombre.value 		= objeto.datoscliente.nombre;
		u.appaterno.value 	= objeto.datoscliente.apepat;
		u.apmaterno.value 	= objeto.datoscliente.apemat;
		u.factura.value		= objeto.datoscliente.foliofactura;
		document.getElementById('estado').innerHTML = objeto.datoscliente.estado;
		u.facturada.value 	= objeto.datoscliente.factura;
		u.comboSucursal.value =  objeto.datoscliente.sucursalacobrar;
		if(objeto.datoscliente.prepagada=="NO"){
			u.seleccionradio[1].checked = true;
			u.seleccionradio[0].disabled = true;
		}else{
			u.seleccionradio[1].disabled = true;
			u.seleccionradio[0].checked = true;
		}
		
		u.lstpago.value				= (objeto.datoscliente.condicionpago=="CONTADO")?0:1;
		u.idconvenio.value			= objeto.datoscliente.folio;
		u.ventas.value				= objeto.datoscliente.foliotipo;
		u.limitekg.value			= objeto.datoscliente.limitekg;
		u.costoguia.value			= objeto.datoscliente.costo;
		u.preciokgexcedente.value	= objeto.datoscliente.preciokgexcedente;
		u.consignaciondes.value 	= objeto.datoscliente.consignaciondescantidad;
		u.por_combustible.value	= objeto.datoscliente.cargocombustible;
		u.por_iva.value			= objeto.datoscliente.iva;
		u.por_ivaretenido.value	= objeto.datoscliente.ivaretenido;
		
		u.cantidadfolios.value  	= objeto.datoscliente.cantidad;
		u.calculos_finicio.value  	= objeto.datoscliente.desdefolio;
		u.calculos_ffinal.value  	= objeto.datoscliente.hastafolio;
		u.calculos_cantidad.value  	= parseFloat(objeto.datoscliente.subtotal)-parseFloat(objeto.datoscliente.combustible);
		
		u.importe.value				= "$ " + numcredvar((parseFloat(objeto.datoscliente.subtotal)-parseFloat(objeto.datoscliente.combustible)).toString());
		u.subtotal.value  			= "$ " + numcredvar(objeto.datoscliente.subtotal);
		u.combustible.value  		= "$ " + numcredvar(objeto.datoscliente.combustible);
		u.iva.value  				= "$ " + numcredvar(objeto.datoscliente.iva);
		u.ivar.value  				= "$ " + numcredvar(objeto.datoscliente.ivar);
		u.total.value  				= "$ " + numcredvar(objeto.datoscliente.total);
		esNan('subtotal');
		esNan('combustible');
		esNan('iva');
		esNan('ivar');
		esNan('total');
		
		u.convenio_radios.style.display="";
		if(objeto.datoscliente.prepagadas==1){
			u.chkprepagada.checked = true;
		}
		if(objeto.datoscliente.consignacionkg==1){
			u.radiobutton[0].checked = true;
			u.convenio_pesokg.style.display="";
			consultaTexto("mostrarSGridKg", "solicitudguiasempresariales_con.php?accion=2&valor=1&idconvenio="+objeto.datoscliente.folio)
		}
		if(objeto.datoscliente.consignacioncaja==1){
			u.radiobutton[1].checked = true;
			u.convenio_preciocaja.style.display="";
			consultaTexto("mostrarSGridPeso", "solicitudguiasempresariales_con.php?accion=2&valor=2&idconvenio="+objeto.datoscliente.folio)
		}
		if(objeto.datoscliente.consignaciondescuento==1){
			u.radiobutton[2].checked = true;
		}
		if(objeto.servicios != "" || objeto.sucursales != ""){
			u.convenio_serviciosucursal.style.display="";
			agregarValores(u.serviciosr2_sel,objeto.servicios,u.serviciosr1);
			agregarValores(u.sucursalesead3_sel,objeto.sucursales,u.sucursalesead1);
		}
		bloquearTodo(true);
		if(objeto.datoscliente.estado=="GUARDADA"){
			u.celdabotones.innerHTML					= botonescancelar;
			u.btnimprimir.style.visibility				= "visible";
		}else{
			u.celdabotones.innerHTML					= botonescargar;
			u.btnimprimir.style.visibility				= "visible";
		}
		u.btnimprimir.style.visibility = "visible";
	}
	
	function cancelar(){
		if(document.all.facturada.value=='SI'){
			alerta3("No puede cancelar una solicitud que ya esta facturada","�ATENCION!");
		}else{
			consultaTexto("resCancelar","solicitudguiasempresariales_con.php?accion=15&folio="+document.all.folio.value);
		}
	}
	
	function resCancelar(datos){
		if(datos.indexOf("cancelo")>-1){
			info("La solicitud de guias ha sido Cancelada", "�Atencion!");
			document.getElementById('estado').innerHTML	='CANCELADA';
			u.celdabotones.innerHTML					= botonescargar;
		}else{
			alerta3("error al guardar<br>"+datos, "�Atencion!");
		}
	}
	
	function pedirFolios(){
		var prepagada		= (u.seleccionradio[0].checked)?"SI":"NO";
		consultaTexto("resPedirFolios","solicitudguiasempresariales_con.php?accion=6&prepagada="+prepagada);
	}
	function resPedirFolios(valor){
		u.ventas.value = valor;
	}
	
	function resPedirFolio(datos){
		u.folio.value = datos;
	}
	
 	function pedirFolios2(prepagada){//Obtener  prepagada solicitudguiaempresariales.php  		
		 if(prepagada=='SI'){
			u.seleccionradio[0].checked=true;
			u.seleccionradio[1].disabled = true;
		 }else if(prepagada=='NO'){
		    u.seleccionradio[1].checked=true;
			u.seleccionradio[0].disabled=true;
		 }
		u.nocliente.disabled=true;
		consultaTexto("resPedirFolios","solicitudguiasempresariales_con.php?accion=6&prepagada="+prepagada);
 	}
 
	 function esNan(caja){
		if(document.getElementById(caja).value.replace("$ ","").replace(/,/g,"")=="NaN"){
			document.getElementById(caja).value = "$ 0.00";
		}
	 }

	function mostrarClienteSolicitud(tipo){		
		abrirVentanaFija('../buscadores_generales/BuscarSolicitudGuiasEmpPendAutorizar.php?funcion=pedirCliente&con=1', 625, 430, 'ventana', 'Buscar Solicitud');			
	}
	
	function imprimirSolicitud(){
		if(document.URL.indexOf("web/")>-1){		
				window.open("https://www.pmmintranet.net/web/fpdf/reportes/acuseRecibo.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&venta="+u.folio.value);
			
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("https://www.pmmintranet.net/web_capacitacion/fpdf/reportes/acuseRecibo.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&venta="+u.folio.value);
						
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("https://www.pmmintranet.net/web_pruebas/fpdf/reportes/acuseRecibo.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&venta="+u.folio.value);
			}
	}
	
</script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="545" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="562" class="FondoTabla Estilo4">ASIGNACI&Oacute;N DE GU&Iacute;AS EMPRESARIALES</td>
  </tr>
  <tr>
    <td><table width="515" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="5"><table width="542" border="0">
          <tr>
            <td>&nbsp;</td>
            <td align="right"></td>
          </tr>
          <tr>
            <td width="231">
            <?
				$s = "SELECT IFNULL(MAX(id),0)+1 as folio, date_format(current_date, '%d/%m/%Y') as fecha 
				FROM solicitudguiasempresariales";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
            <table width="225" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td width="68">Folio</td>
                    <td width="130"><input name="folio" type="text" class="Tablas" id="folio" style="width:80px;background:#FFFF99" value="<?=$f->folio ?>" /></td>
                    <td width="27"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarSolicitudGuiasGen.php?funcion=pedirSolicitud', 625, 550, 'ventana', 'Buscar Solicitud')"></div></td>
                </tr>
            	<tr>
            	  <td>Fecha</td>
            	  <td><span class="Tablas">
            	    <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$f->fecha ?>" readonly=""/>
            	  </span></td>
            	  <td>&nbsp;</td>
          	  </tr>
            	<tr>
            	  <td>Factura</td>
            	  <td>
            	    <input name="factura" type="text" class="Tablas" id="fecha2" style="width:100px;background:#FFFF99; text-align:right" value="" readonly=""/>            	  </td>
            	  <td>&nbsp;</td>
          	  </tr>
            	<tr>
            	  <td>Sucursal a Cobrar</td>
            	  <td><select name="comboSucursal" style="width:120px">
                    <option value="0">.::Seleccionar::.</option>
                    <?
							$s = "select * from catalogosucursal";
							$r = mysql_query($s,$l) or die($s);
							while($f = mysql_fetch_object($r)){
						?>
                    <option value="<?=$f->id?>">
                      <?=$f->descripcion?>
                      </option>
                    <?
							}
						?>
                  </select></td>
            	  <td>&nbsp;</td>
          	  </tr>
            </table>
            <div align="left"></div>
<input type="hidden" name="guardada" /><input name="essolicitud" type="hidden" id="essolicitud" value="<?=$_GET[essolicitud] ?>" />
			<input name="facturada" type="hidden" id="facturada" value="" />
			</td>
            <td width="301"><table width="263" align="right">
              <tr>
                <td width="42">&nbsp;</td>
                <td width="209" ><div style="font-size:16px; font-weight:bold; text-align:right" id="estado"></div></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="97"><label>
          <input name="seleccionradio" type="radio" value="radiobutton" onclick="pedirFolios()" />
          Pre-Pagadas</label>
          <label></label></td>
        <td width="100"><input name="seleccionradio" type="radio" value="radiobutton" onclick="pedirFolios()"  />
Consignaci�n</td>
        <td width="163">No.Ventas<span class="Tablas">
        <input name="ventas" type="text" class="Tablas" id="ventas" style="width:100px;background:#FFFF99" value="<?=$ventas ?>" readonly=""/>
        </span></td>
        <td width="79">Condicion Pago</td>
        <td width="104"><select name="lstpago" class="Tablas" id="lstpago" style="width:90px; font-size:9px; text-transform:uppercase">
          <option value="0">Contado</option>
          <option value="1">Credito</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" class="FondoTabla Estilo4">Cliente</td>
      </tr>
      <tr>
        <td colspan="5"><table width="496" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="50"># Cliente</td>
            <td width="104"><span class="Tablas">
              <input name="nocliente" type="text" class="Tablas" style="width:100px" disabled="disabled" value="<?=$nombre ?>" onkeypress="if(event.keyCode==13){pedirCliente(this.value);}else{return solonumeros(event)}" />
            </span></td>
            <td width="25"><div class="ebtn_buscar" style="visibility:hidden" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=pedirCliente' 
            + ((u.seleccionradio[0].checked)?'&tiposol=PREPAGADA':'&tiposol=CONSIGNACION'), 625, 418, 'ventana', 'Buscar Cliente')" id="img_buscarCliente"></div></td>
            <td width="347"><span class="Tablas">
              <input name="nombre" type="text" class="Tablas" id="nombre" style="width:185px;background:#FFFF99" value="<?=$nombreb ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="5"><table width="496" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="65">Ap. Paterno </td>
            <td width="104"><span class="Tablas">
              <input name="appaterno" type="text" class="Tablas" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
            </span></td>
            <td width="68">Ap. Materno </td>
            <td width="211"><span class="Tablas">
              <input name="apmaterno" type="text" class="Tablas" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="5"><table width="543" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3" class="FondoTabla Estilo4">Datos Convenio <input type="hidden" name="idconvenio" /><input type="hidden" name="personamoral" /></td>
</tr>
          <tr  id="convenio_radios" style="display:none">
            <td colspan="3">
            <table width="500" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="2"><table width="534" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="20"><label>
                        <input name="chkprepagada" type="checkbox" disabled/>
                    </label></td>
                    <td width="71">Pre-Pagadas</td>
                    <td width="149">Limite KG <span class="Tablas">
                      <input name="limitekg" type="text" class="Tablas" style="width:90px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly"  />
                    </span></td>
                    <td width="31">Costo </td>
                    <td width="101"><span class="Tablas">
                      <input name="costoguia" type="text" class="Tablas" style="width:90px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" readonly="readonly" value="<?=$Costo ?>"/>
                    </span></td>
                    <td width="57">Excedente</td>
                    <td width="105"><input name="preciokgexcedente" type="text" class="Tablas" style="width:90px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" readonly="readonly" value="<?=$excedente ?>"/></td>
                  </tr>
                </table>
                  <table width="534" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="20"><input name="radiobutton" type="radio" value="1" disabled/></td>
                      <td width="87">Consignaci&oacute;n KG</td>
                      <td width="80">&nbsp;</td>
                      <td width="155">&nbsp;</td>
                      <td width="103">&nbsp;</td>
                      <td width="89">&nbsp;</td>
                    </tr>
                  </table>
                  <table width="277" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="21"><input name="radiobutton" type="radio" value="2" disabled /></td>
                      <td width="256"> Consignaci&oacute;n Paquete </td>
                    </tr>
                  </table>
                  <table width="257" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="20"><input name="radiobutton" type="radio" value="3" disabled/></td>
                      <td width="124">Consignaci&oacute;n Descuento </td>
                      <td width="113"><span class="Tablas">
                        <?
				$s = "SELECT desmaximopermitido FROM configuradorgeneral";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
                        <input name="consignaciondes" type="text" class="Tablas" id="consignaciondes" style="width:100px;background:#FFFF99" onkeypress="return solonumeros(event)" readonly="readonly" value="<?=$consignaciondes ?>" onblur="if(this.value&gt;<?=$f->desmaximopermitido?>){alerta('El descuento maximo permitido es de <?=$f->desmaximopermitido?>','&iexcl;Atencion!', 'descuentoflete');}" />
                      </span></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr id="convenio_pesokg" style="display:none">
                <td colspan="2">
                <table width="532" border="0" align="left" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td width="810" align="right"><div id="detallekgc" style="width:535px; height:80px; overflow:auto" align="left">
                          <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
                          <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                            <tr>
                              <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                              <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 0;
						while($f = mysql_fetch_object($r)){
					?>
                              <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                                <?=$zona?>
                                  <br />
                                  <?=$f->zoi?>
                                -
                                <?=$f->zof?></td>
                              <?
							$zona++;
						}
					?>
                            </tr>
                            <tr>
                              <td  class="formato_columnasg" height="16" >Precio KG</td>
                              <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($r)){
					?>
                              <td height="16" >&nbsp;</td>
                              <?
						}
					?>
                            </tr>
                          </table>
                      </div></td>
                    </tr>
                </table></td>
              </tr>
				<tr id="convenio_preciocaja" style="display:none">
                <td colspan="2"><table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="810" align="right"><div id="detalledescripcionc" name="detalle" style="width:535px; height:80px; overflow:auto" align="left">
                          <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";

						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
                          <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                            <tr>
                              <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                              <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 0;
						while($f = mysql_fetch_object($r)){
					?>
                              <td height="16" class="formato_columnasg" width="55px" align="center" >Zona
                                <?=$zona?>
                                  <br />
                                  <?=$f->zoi?>
                                -
                                <?=$f->zof?></td>
                              <?
							$zona++;
						}
					?>
                            </tr>
                            <tr>
                              <td  class="formato_columnasg" height="16" >Descripcion</td>
                              <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($r)){
					?>
                              <td height="16" >&nbsp;</td>
                              <?
						}
					?>
                            </tr>
                          </table>
                      </div></td>
                    </tr>
                </table></td>
              </tr>
				<tr  id="convenio_serviciosucursal" style="display:none">
                <td width="259" >
                <table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="9" height="16"   class="formato_columnas_izq"></td>
                      <td width="250"class="formato_columnas" align="center">SERVICIOS RESTRINGIDOS POR EL CLIENTE </td>
                      <td width="9"class="formato_columnas_der"></td>
                    </tr>
                    <tr>
                      <td colspan="12"><div align="center">
                          <select name="serviciosr2_sel" size="8" style="width:265px">
                          </select>
                      </div></td>
                    </tr>
                </table>
                <select name="sucursalesead1" style="width:200px; display:none">
              	<? 
					$s = "select * from catalogosucursal where id > 1";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
				<option value="<?=$f->id?>"><?=$f->descripcion?></option>
				<?
					}
				?>
                </select>
        	<select name="serviciosr1" style="width:200px; display:none">
              	<? 
					$s = "select * from catalogoservicio where restringir = 'SI'";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
				<option value="<?=$f->id?>"><?=$f->descripcion?></option>
				<?
					}
				?>
              </select>
                </td>
                <td width="276" ><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="9" height="16"   class="formato_columnas_izq"></td>
                      <td width="250"class="formato_columnas" align="center">SUCURSALES QUE APLICA EAD </td>
                      <td width="9"class="formato_columnas_der"></td>
                    </tr>
                    <tr>
                      <td colspan="12"></td>
                    </tr>
                    <tr>
                      <td colspan="12"><div align="center">
                          <select name="sucursalesead3_sel" size="8" style="width:265px">
                          </select>
                      </div></td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
</tr>
          <tr>
            <td height="11" colspan="3">&nbsp;</td>
</tr>
          <tr>
            <td colspan="3" class="FondoTabla Estilo4">Asignaci&oacute;n</td>
</tr>
          <tr>
            <td width="346">Cantidad
              <input name="cantidadfolios" type="text" style="font-size:8px; font:tahoma; font-weight:bold" 
              value="<?=$cantidad?>" size="20" onkeypress="if(event.keyCode==13){pedirCantidadFolios(this.value)}" />             </td>
            <td width="197">&nbsp;</td>
            <td width="3">&nbsp;</td>
          </tr>
          <tr>
            <td height="170" rowspan="2" valign="top"><table width="309" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="6" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                <td width="51"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>
                <td width="56"  background="../img/borde1_2.jpg" class="style5" align="center">INICIAL</td>
                <td width="96" background="../img/borde1_2.jpg" class="style5" align="center">FINAL</td>
                <td width="89" background="../img/borde1_2.jpg" class="style5" align="center">IMPORTE</td>
                <td width="7" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
                <td width="4"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>
              </tr>
              <tr>
                <td height="16" colspan="12" align="center">
                  <table width="298" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="16" width="17" >&nbsp;</td>
                        <td width="10" align="center" class="style31"  >&nbsp;</td>
                        <td width="88" align="center" class="style31"  ><input name="calculos_finicio" type="text" class="style2" id="cantidad" readonly="" style="font-size:8px; font:tahoma; font-weight:bold; width:70px; text-align:center" /></td>
                        <td width="94" align="center" class="style31"><input name="calculos_ffinal" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold; width:70px; text-align:center" readonly=""  /></td>
                        <td width="89" align="center" class="style31"><input name="calculos_cantidad" type="text" class="style2" id="concepto" style="font-size:8px; font:tahoma;font-weight:bold; width:70px; text-align:center" readonly="" /></td>
                      </tr>
                    </table>               </td>
              </tr>
            </table></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="62" colspan="2" valign="top"><table width="200" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="63">Importe</td>
                <td width="120"><span class="Tablas">
                  <input name="importe" type="text" class="Tablas" id="importe" style="width:100px;background:#FFFF99; text-align:right" value="<?=$importe ?>"/> 
                </span></td>
              </tr>
              <tr>
                <td width="63">Combustible</td>
                <td width="120"><span class="Tablas">
                  <input name="combustible" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right" value="<?=$conbustible ?>" readonly=""/>
                </span></td>
              </tr>
              <tr>
                <td>Subtotal</td>
                <td><span class="Tablas">
                  <input name="subtotal" type="text" class="Tablas" id="subtotal2" style="width:100px;background:#FFFF99; text-align:right" value="<?=$subtotal ?>"/>
                  <input type="hidden" name="por_combustible" />
                </span></td>
              </tr>
              <tr>
                <td>IVA</td>
                <td><span class="Tablas">
                  <input name="iva" type="text" class="Tablas" id="iva" style="width:100px;background:#FFFF99; text-align:right" value="<?=$iva ?>" readonly=""/>
                  <input type="hidden" name="por_iva" />
                </span></td>
              </tr>
              <tr>
                <td>IVARetenido</td>
                <td><span class="Tablas">
                  <input name="ivar" type="text" class="Tablas" id="ivar" style="width:100px;background:#FFFF99; text-align:right" value="<?=$ivar ?>" readonly=""/>
                  <input type="hidden" name="por_ivaretenido" />
                </span></td>
              </tr>
              <tr>
                <td>Total</td>
                <td><span class="Tablas">
                  <input name="total" type="text" class="Tablas" id="total" style="width:100px;background:#FFFF99; text-align:right" value="<?=$total ?>" readonly=""/>
                </span></td>
              </tr>
            </table></td>
</tr>
        </table></td>
      </tr>
      
      <tr>
        <td colspan="5"><span class="Tablas">
          <input name="idsolicitud" type="hidden" id="idsolicitud" value="<?=$_GET[folio]?>" />
          <input name="creditodesactivado" type="hidden" id="creditodesactivado" />
         <input name="creditodisponible" type="hidden" id="creditodisponible" />
        </span></td>
      </tr>
      <tr>
        <td colspan="5" align="center" id="celdabotones">
        	<table width="283" align="center">
       	    <tr>
                	<td><div class="ebtn_imprimir" id="btnimprimir" style="visibility:hidden" onclick="imprimirSolicitud()"></div></td>
                   <? if($_GET[ncliente]==""){?> 
                   <td><div class="ebtn_guardar" id="btnguardar" onclick="guardarSolicitud();"></div></td>
                   <? } ?>
                    <td>
					
                    </td>
                </tr>
            </table>        </td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>