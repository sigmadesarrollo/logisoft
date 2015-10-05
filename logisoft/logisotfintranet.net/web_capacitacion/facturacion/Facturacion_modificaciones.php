<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$s = "select descripcion from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$nsucursal = $f->descripcion;
	
	//die("<img src='mpreven.jpg'><br>Realizando pruebas disculpen las molestias...");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
.Estilo2 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
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
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/funciones_tablas.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/jquery-1.4.js"></script>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style></head>
<script>
	
	//botones para las acciones
	var condicionpuesta = false;
	var botonesnuevo = "<table><tr><td><div class=\"ebtn_guardar\" onclick=\"if(validarGuardar()){ confirmar('¿Desea guardar la factura?','¡Atencion!','guardarFacturar()',''); }\"/></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio(); bloquear(false);\"/></td></tr></table>";
	var botonescargar = "<table><tr><td><div class=\"ebtn_Cancelar_Factura\" onclick=\"if(document.all.estadofactura.innerHTML=='GUARDADO' || document.all.estadofactura.innerHTML=='PAGADA'){confirmar('¿Desea Cancelar la factura?','¡Atencion!','cancelarFactura()','')}else{alerta('La factura no se ha guardado','¡Atencion!','folio')}\"/></td><td><div class=\"ebtn_imprimir\" onClick=\"preguntarImprimir();\" /></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio(); bloquear(false);\"/></td></tr></table>";
	var botonescancelado = "<table><tr><td><div class=\"ebtn_Sustituir_Factura\" onclick=\"if(document.all.estadofactura.innerHTML=='CANCELADO'){confirmar('¿Desea Sustituir la factura?','¡Atencion!','sustituirFactura()','')}else{alerta('La factura no se ha guardado','¡Atencion!','folio')}\"/></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio(); bloquear(false);\"/></td></tr></table>";
	var u = document.all;
	
	window.onload = function(){
		u.modificar.value='<?=$_GET[modificar] ?>';
	}
	
	
	function preguntarImprimir(){
		<?=$cpermiso->verificarPermiso("293,322",$_SESSION[IDUSUARIO]);?>;
		abrirVentanaFija('../buscadores_generales/impresionFactura.php', 300, 230, 'ventana', 'Busqueda');
	}
		
	function bloquear(valor){
		var u = document.all;
		
		u.idcliente.readOnly = valor;
		u.idcliente.style.backgroundColor = (valor)?"#FFFF99":"";
		u.tipoguias[0].disabled = valor;
		u.tipoguias[1].disabled = valor;
		u.guia[0].disabled = valor;
		u.guia[1].disabled = valor;
	}
	function paracelda(valor, tamano, alineacion, idcaja){
		elid = "";
		if(idcaja!=undefined){
			elid = ' name="'+idcaja+'xxNOFILAxx"';
		}
		return '<input type="text" readonly="true" style=" width:'+tamano+'px;font:tahoma; font-size:9px; text-align:'+alineacion+'; font-weight:bold; border:none; background:none" value="'+valor+'" '+elid+' />';
	}
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		if( isNaN(numcredvar(valor1.toLocaleString()).replace(/,/g,'') )){
			valor2 = "$ 0.00";
		}else{
			valor2 = "$ "+numcredvar(valor1.toLocaleString());
		}
		return valor2;
	}
	function desconvertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = valorx.toLocaleString().replace("$ ","").replace(/,/g,"");
		return valor1;
	}
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
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
	function numerosydecimal(evnt,valor){
		caja = valor;
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
				//alert("Solo Numeros, Por favor...");
				return false;
			}else{
				if(charCode==46){
					if(caja.indexOf(".")>-1){
						return false;
					}
				}
			}
			return true;
		}
	}
	//funciones para limpiar
	function limpiarSustitucion(){
		u.folioasustituir.value = "0";
	}
	function limpiarCliente(){
		with(document.all){
			idcliente.value = "";
			nombre.value = "";
			paterno.value = "";
			materno.value = "";
			calle.value = "";
			numero.value = "";
			cp.value = "";
			colonia.value = "";
			ccalles.value = "";
			poblacion.value = "";
			municipio.value = "";
			estado.value = "";
			pais.value = "";
			telefono.value = "";
			fax.value = "";
			rfc.value = "";
		}
		document.getElementById('facturacancelada').style.display='none';
	}
	function limpiarGuias(){
		tabla1.clear();
		tabla2.clear();
		document.all.valorcontado.value = "0";
		document.all.solicitudesdecontado.value = "0";
		document.all.excedentesdecontado.value = "0";
	}
	function limpiarOtros(){
		u = document.all;
		u.cantidad.value 			= "";
		u.descripcion.value			= "";
		u.importe.value				= "";
		u.subtotalotros.value		= "";
		u.ivaotros.value			= "";
		u.ivarotros.value			= "";
		u.montootros.value			= "";
		u.guiase.value				= "";
		u.guiasn.value				= "";
		u.tflete.value 				= "";
		u.ttotaldescuento.value		= "";
		u.texcedente.value			= "";
		u.tead.value 				= "";
		u.trecoleccion.value		= "";
		u.tseguro.value 			= "";
		u.tcombustible.value		= "";
		u.totros.value 				= "";
		u.tsubtotal.value			= "";
		u.tiva.value 				= "";
		u.tivar.value				= "";
		u.ttotal.value 				= "";
	}
	
	function limpiarSobre(){
		u = document.all;
		
		u.sseguro.value 		= "";
		//u.sexcedente.value 		= "";
		u.ssubtotal.value 		= "";
		u.siva.value 			= "";
		u.sivar.value 			= "";
		u.smonto.value 			= "";
	}
	
	function limpiarTodo(){
		document.getElementById('estadofactura').innerHTML = "";
		document.getElementById('fechapago').innerHTML = "";
		document.getElementById('fechacancelacion').innerHTML = "";
		document.all.estadocobranza.value = "";
		document.all.personamoral.value = "";
		document.all.activado.value = "";
		document.getElementById('lasucursal').innerHTML = "<?=$nsucursal?>";
		limpiarCliente();
		document.all.bonotesAccion.innerHTML = botonesnuevo;
	}
	
	function limpiarCambioClientes(){
		document.all.valorcontado.value = "";
		document.all.conformapago.value = "";
		limpiarGuias();
		limpiarSobre();
		limpiarOtros();
	}
	
	function pedirFolio(){
		consulta("mostrarFolio", "Facturacion_consulta.php?accion=5&ran="+Math.random());
	}
	
	//funciones para ajax
	function mostrarFolio(datos){
		maximo = datos.getElementsByTagName('maximo').item(0).firstChild.data;
		fecha  = datos.getElementsByTagName('fecha').item(0).firstChild.data;
		document.all.folio.innerHTML = maximo;
		document.all.fecha.value = fecha;
	}
	
	function pedirCliente(valor){
		limpiarTodo();
		document.all.idcliente.value = valor;
		consulta("mostrarCliente", "Facturacion_consulta.php?accion=1&idcliente="+valor+"&valrandom="+Math.random());
	}
	
	function mostrarCliente(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;
			u.nombre.value 				= datos.getElementsByTagName('nombre').item(0).firstChild.data;
			u.paterno.value				= datos.getElementsByTagName('paterno').item(0).firstChild.data;
			u.materno.value				= datos.getElementsByTagName('materno').item(0).firstChild.data;
			u.rfc.value					= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			u.personamoral.value		= datos.getElementsByTagName('personamoral').item(0).firstChild.data;
			u.creditodisponible.value	= datos.getElementsByTagName('disponible').item(0).firstChild.data;
			u.activado.value			= datos.getElementsByTagName('creditoactivado').item(0).firstChild.data;
			if(condicionpuesta==false && '<?=$_GET[condicionpago]?>'!=""){
				var indice = '<?=$_GET[condicionpago]?>';
				if(indice=='0'){
					document.all.tipoguias[1].checked = true;
				}else{
					document.all.tipoguias[0].checked = true;
				}
			}else{
				if(datos.getElementsByTagName('foliocredito').item(0).firstChild.data=="NO"){
					document.all.tipoguias[1].checked = true;
				}else{
					document.all.tipoguias[0].checked = true;
				}
			}
			
			document.all.celdacalle.innerHTML = '<input name="calle" type="text" class="Tablas" id="calle" style="width:250px;" />';			
			if(endir==1){			
				direccionesf			= datos.getElementsByTagName('direccionesf').item(0).firstChild.data;
				u.calle.value			= datos.getElementsByTagName('calle').item(0).firstChild.data;
				u.ccalles.value 		= datos.getElementsByTagName('crucecalles').item(0).firstChild.data;
				u.numero.value 			= datos.getElementsByTagName('numero').item(0).firstChild.data;
				u.cp.value 				= datos.getElementsByTagName('cp').item(0).firstChild.data;
				u.colonia.value 		= datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.poblacion.value 		= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				u.telefono.value 		= datos.getElementsByTagName('telefono').item(0).firstChild.data;
				u.fax.value 			= datos.getElementsByTagName('fax').item(0).firstChild.data;
				u.pais.value 			= datos.getElementsByTagName('pais').item(0).firstChild.data;
				u.estado.value 			= datos.getElementsByTagName('estado').item(0).firstChild.data;
				u.municipio.value		= datos.getElementsByTagName('municipio').item(0).firstChild.data;
				u.facturacion.value		= datos.getElementsByTagName('facturacion').item(0).firstChild.data;
			if(direccionesf==0)
					alerta("El Cliente no tiene direccion para facturar","¡Atencion!","idcliente");				
				cargarGuias(document.all.idcliente.value);
			}else if(endir>1){
				direccionesf			= datos.getElementsByTagName('direccionesf').item(0).firstChild.data;
				
				var comb = "<select name='calle' style='width:250px;font:tahoma; font-size:9px' onchange='"
				+"document.all.ccalles.value=this.options[this.selectedIndex].ccalles;"
				+"document.all.numero.value=this.options[this.selectedIndex].numero;"
				+"document.all.cp.value=this.options[this.selectedIndex].cp;"
				+"document.all.poblacion.value=this.options[this.selectedIndex].poblacion;"
				+"document.all.telefono.value=this.options[this.selectedIndex].telefono;"
				+"document.all.fax.value=this.options[this.selectedIndex].fax;"
				+"document.all.pais.value=this.options[this.selectedIndex].pais;"
				+"document.all.ccalles.value=this.options[this.selectedIndex].numero;"
				+"document.all.estado.value=this.options[this.selectedIndex].estado;"
				+"document.all.municipio.value=this.options[this.selectedIndex].municipio;"
				+"document.all.facturacion.value=this.options[this.selectedIndex].facturacion;"
				+"'>";
				
				for(var i=0; i<endir; i++){
					calle			= datos.getElementsByTagName('calle').item(i).firstChild.data;
					ccalles 		= datos.getElementsByTagName('crucecalles').item(i).firstChild.data;
					numero 			= datos.getElementsByTagName('numero').item(i).firstChild.data;
					cp 				= datos.getElementsByTagName('cp').item(i).firstChild.data;
					colonia 		= datos.getElementsByTagName('colonia').item(i).firstChild.data;
					poblacion 		= datos.getElementsByTagName('poblacion').item(i).firstChild.data;
					telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;
					fax 			= datos.getElementsByTagName('fax').item(i).firstChild.data;
					pais 			= datos.getElementsByTagName('pais').item(i).firstChild.data;
					estado 			= datos.getElementsByTagName('estado').item(i).firstChild.data;
					municipio		= datos.getElementsByTagName('municipio').item(i).firstChild.data;
					facturacion		= datos.getElementsByTagName('facturacion').item(i).firstChild.data;
					if(i==0){
						u.ccalles.value 		= ccalles;
						u.numero.value 			= numero;
						u.cp.value 				= cp;
						u.colonia.value 		= colonia;
						u.poblacion.value 		= poblacion;
						u.telefono.value 		= telefono;
						u.fax.value 			= fax;
						u.pais.value 			= pais;
						u.estado.value 			= estado;
						u.municipio.value		= municipio;
						u.facturacion.value		= facturacion;
					}
					
					comb += "<option value='"+calle+"' calle='"+calle+"' numero='"+numero+"' cp='"+cp+"' colonia='"+colonia+"'"
					+"poblacion='"+poblacion+"' telefono='"+telefono+"' fax='"+fax+"' pais='"+pais+"' estado='"+estado+"' municipio='"+municipio+"' facturacion='"+facturacion+"'>"
					+calle+", "+numero+", "+colonia+"</option>";
				}
				comb += "</select>";
				document.all.celdacalle.innerHTML = comb;
				u.calle.focus();
				
				if(direccionesf==0)
					alerta2("El Cliente no tiene direccion para facturar","¡Atencion!","idcliente");
				
				cargarGuias(document.all.idcliente.value);
			}else{
				alerta("El Cliente no tiene direccion","¡Atencion!","idcliente");
			}
		}else{
			alerta("El Cliente no existe","¡Atencion!","idcliente");
		}
	}
	
	function cargarGuias(valor){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			sucursalorigen 	= '<?=$_SESSION[IDSUCURSAL]?>';
			if(document.all.tipoguias[0].checked==true){
				tipoguia = document.all.tipoguias[0].value;
			}else{
				tipoguia = document.all.tipoguias[1].value;
			}
			var guia = ((document.all.guia[0].checked == true)?"vent":"emp");
			consulta("mostrarGuias", "Facturacion_consulta.php?accion=2&idcliente="+valor
			+"&tipoguia="+tipoguia
			+"&sucorigen="+sucursalorigen+"&guia="+guia
			+"&valrandom="+Math.random());
			
			if(document.all.guia[1].checked == true){
				consulta("mostrarGuias2", "Facturacion_consulta.php?accion=7&idcliente="+valor
				+"&tipoguia="+tipoguia
				+"&sucorigen="+sucursalorigen+"&guia="+guia
				+"&valrandom="+Math.random());
			}
		}else{
			alerta("Seleccione una sucursal de Origen","¡Atencion!","fecha");
		}
	}
	
	function mostrarGuias(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('id').length;
		if(encon>0){
			var tflete 			= 0;
			var ttotaldescuento = 0;
			var texcedente 		= 0;
			var tead 			= 0;
			var trecoleccion	= 0;
			var tseguro 		= 0;
			var tcomb 			= 0;
			var totros 			= 0;
			var tsubtotal		= 0;
			var tiva 			= 0;
			var tivaret 		= 0;
			var ttotal 			= 0;
			var guiase			= 0;
			var guiasn			= 0;
			u.cantidadregistros.value = encon;
			var obj = new Object();
			//alert(datos.getElementsByTagName('tflete').item(0).firstChild.data);
			//for(var i=0; i<encon; i++){
				
			//}
			tabla1.setXML(datos);
			var decontado = 0;
			var utotal = 0;
			for(m=0; m<encon; m++){	
				tipoguia = datos.getElementsByTagName('tipoguia').item(m).firstChild.data;
				
				if(tipoguia=="NORMAL"){
					guiasn += 1;
				}else{
					guiase += 1;
				}
				if(tipoguia=="PREPAGADA" || tipoguia=="CONSIGNACION"){
					utotal = datos.getElementsByTagName('total').item(m).firstChild.data;
					decontado += parseFloat(utotal);
				}
			}
			u.solicitudesdecontado.value = decontado;
			u.guiase.value = guiase;
			u.guiasn.value = guiasn;
			if(condicionpuesta==false && '<?=$_GET[condicionpago]?>'!=""){
				condicionpuesta = true;
				seleccionarUnaGuia('<?=$_GET[folio]?>');	
			}else{
				seleccionarGuias(true);
			}
		}else{
			alerta("El Cliente no tiene guias " + ((u.tipoguias[0].checked)?"credito":"contado") + " para facturar","¡Atencion!","idcliente")
		}
	}
	function mostrarGuias2(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('id').length;
		if(encon>0){
			u.cantidadregistros.value = encon;
			tabla2.setXML(datos);
			
			seleccionarGuias2(true);
		}else{
			alerta("El Cliente no tiene guias Valor Declarado de " + ((u.tipoguias[0].checked)?"credito":"contado") + " para facturar","¡Atencion!","idcliente")
		}
	}
	
	function guardarFacturar(){
		
		var u = document.all;
		var cliente 					= u.idcliente.value;
		var nombrecliente 				= u.nombre.value;
		var apellidopaternocliente 		= u.paterno.value;
		var apellidomaternocliente 		= u.materno.value;
		var rfc							= u.rfc.value;
		var calle 						= u.calle.value;
		var numero 						= u.numero.value;
		var codigopostal 				= u.cp.value;
		var colonia 					= u.colonia.value;
		var crucecalles 				= u.ccalles.value;
		var poblacion 					= u.poblacion.value;
		var municipio 					= u.municipio.value;
		var estado 						= u.estado.value;
		var pais 						= u.pais.value;
		var telefono 					= u.telefono.value;
		var fax 						= u.fax.value;
		
		nombrecliente = nombrecliente.replace(/&/g,"%26");
		apellidopaternocliente = apellidopaternocliente.replace(/&/g,"%26");
		apellidomaternocliente = apellidomaternocliente.replace(/&/g,"%26");
		calle = calle.replace(/&/g,"%26");
		colonia = colonia.replace(/&/g,"%26");
		rfc = rfc.replace(/&/g,"%26");
		
		var envioinformacion = "accion=3&nombrecliente=" + nombrecliente + 
		"&apellidopaternocliente=" + apellidopaternocliente + 
		"&apellidomaternocliente=" + apellidomaternocliente + 
		"&rfc=" + rfc + 
		"&calle=" + calle + 
		"&numero=" + numero + 
		"&codigopostal=" + codigopostal + 
		"&colonia=" + colonia + 
		"&crucecalles=" + crucecalles + 
		"&poblacion=" + poblacion + 
		"&municipio=" + municipio + 
		"&estado=" + estado + 
		"&pais=" + pais + 
		"&telefono=" + telefono + 
		"&fax=" + fax + 
		"&foliofactura=" + u.folio.innerHTML +
		"&ranms="+Math.random();
		document.getElementById('bonotesAccion').innerHTML = "";
		
		envioinformacion;
		$.ajax({
		   type: "POST",
		   url: "Facturacion_consultajson_ref2.php",
		   data: envioinformacion,
		   success: respuestaGuardar
		 });
		return false;		
	}
	
	function respuestaGuardar(datos){
		ocultarLoading();
		if(datos.indexOf('ok')>-1){
			info("Se ha guardado la factura","¡Atencion!");
		}else{
			consulta = datos;
			alerta("Error al guardar "+consulta,"¡Atencion!","folio");
		}
	}
	
	function cancelarFactura(){
		<?=$cpermiso->verificarPermiso("292,321",$_SESSION[IDUSUARIO]);?>;
		
		/*if(u.estadocobranza.value == "C" && u.guia[1].checked==true && u.tipoguias[1].checked==true && "<?=date("d/m/Y")?>"!=u.fechapago.innerHTML){
			alerta3("No se puede cancelar una fatura empresarial que ya esta pagada en dias anteriores","¡ATENCION!");
			return false;
		}*/
		
		if(u.estadocobranza.value == "C" && u.tipoguias[0].checked==true){
			alerta3("No se puede cancelar una fatura de credito que ya esta pagada","¡ATENCION!");
			return false;
		}
		
		if(u.estadocobranza.value == "C" && u.tipoguias[1].checked==true && "<?=date("d/m/Y")?>"!=u.fechapago.innerHTML){
			alerta3("No se puede cancelar una fatura que ya esta pagada en dias anteriores","¡ATENCION!");
			return false;
		}
		
		var esventanilla=false;
		if(tabla1.getRecordCount()>0 && tabla2.getRecordCount()<1 && u.montootros.value=="$ 0.00")
			esventanilla = true;
		
		/*if(u.estadocobranza.value == "C" && u.fechapago.innerHTML!='<?=date('d/m/Y')?>' && esventanilla==false){
			alerta3("No puede cancelar una factura que ya esta pagada","¡ATENCION!");
			return false;
		}*/
			
		if(u.sepuede.value=='NO'){
			alerta3("No puede cancelar una factura de cargo EAD si la guía no esta en estado ALMACEN DESTINO","¡ATENCION!");
			return false;
		}
		var f1 = u.fecha.value.split("/");
		var f2 = u.confirFecha.value.split("/");
		
		if(f1[0].substr(0,1)=="0"){
			f1[0] = f1[0].substr(1,1);
		}
		if(f1[1].substr(0,1)=="0"){
			f1[1] = f1[1].substr(1,1);
		}
		
		if(f2[0].substr(0,1)=="0"){
			f2[0] = f2[0].substr(1,1);
		}
		if(f2[1].substr(0,1)=="0"){
			f2[1] = f2[1].substr(1,1);
		}
		
		var initDate = new Date(f1[2],f1[1],f1[0]);
		var endDate = new Date(f2[2],f2[1],f2[0]);
		
		/*if(u.tipoguias[1].checked==true && initDate < endDate){
			alerta3("Solo se pueden cancelar Facturas de contado del dia actual","¡Atención!");
			return false;
		}*/
		
		u = document.all;
		consulta("respuestaCancelar","Facturacion_consulta.php?accion=4&foliofactura="+u.folio.innerHTML);
	}
	
	function respuestaCancelar(datos){
		cancelada = datos.getElementsByTagName('cancelada').item(0).firstChild.data;
		if(cancelada==1){
			alerta("Se ha Cancelado la factura","¡Atencion!","folio");
			document.getElementById('facturacancelada').style.display='';
			u.estadofactura.innerHTML = "CANCELADO";
		}else{
			consulta = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al cancelar "+consulta,"¡Atencion!","folio");
		}
		document.all.bonotesAccion.innerHTML = botonescancelado;
	}
	
	function cargarFactura(folio){
		u = document.all;
		consulta("mostrarFactura","Facturacion_consulta.php?accion=6&folio="+folio+"&ran="+Math.random());
	}
	function mostrarFactura(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		
		bloquear(true);
		limpiarTodo();
		if(encon>0){
			var folio				= datos.getElementsByTagName('folio').item(0).firstChild.data;
			var tipodefactura		= datos.getElementsByTagName('tipodefactura').item(0).firstChild.data;
			var facturaestado 		= datos.getElementsByTagName('facturaestado').item(0).firstChild.data;
			var fechacancelacion	= datos.getElementsByTagName('fechacancelacion').item(0).firstChild.data;
			var sepuede 			= datos.getElementsByTagName('sepuede').item(0).firstChild.data;
			var fechapago			= datos.getElementsByTagName('fechapago').item(0).firstChild.data;
			var nsucursal			= datos.getElementsByTagName('nsucursal').item(0).firstChild.data;
			var cliente 			= datos.getElementsByTagName('cliente').item(0).firstChild.data;
			var nombrecliente 		= datos.getElementsByTagName('nombrecliente').item(0).firstChild.data;
			var credito		 		= datos.getElementsByTagName('credito').item(0).firstChild.data;
			var apepat 				= datos.getElementsByTagName('apepat').item(0).firstChild.data;
			var apemat 				= datos.getElementsByTagName('apemat').item(0).firstChild.data;
			var sustituto			= datos.getElementsByTagName('sustitucion').item(0).firstChild.data;
			var rfc 				= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			var calle 				= datos.getElementsByTagName('calle').item(0).firstChild.data;
			var numero 				= datos.getElementsByTagName('numero').item(0).firstChild.data;
			var codigopostal 		= datos.getElementsByTagName('codigopostal').item(0).firstChild.data;
			var colonia 			= datos.getElementsByTagName('colonia').item(0).firstChild.data;
			var crucecalles			= datos.getElementsByTagName('crucecalles').item(0).firstChild.data;
			var poblacion 			= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			var municipio 			= datos.getElementsByTagName('municipio').item(0).firstChild.data;
			var estado 				= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var pais 				= datos.getElementsByTagName('pais').item(0).firstChild.data;
			var telefono 			= datos.getElementsByTagName('telefono').item(0).firstChild.data;
			var fax 				= datos.getElementsByTagName('fax').item(0).firstChild.data;
			var guiasempresa 		= datos.getElementsByTagName('guiasempresa').item(0).firstChild.data;
			var guiasnormales 		= datos.getElementsByTagName('guiasnormales').item(0).firstChild.data;
			var flete 				= datos.getElementsByTagName('flete').item(0).firstChild.data;
			var totaldescuento		= datos.getElementsByTagName('totaldescuento').item(0).firstChild.data;
			var excedente 			= datos.getElementsByTagName('excedente').item(0).firstChild.data;
			var ead 				= datos.getElementsByTagName('ead').item(0).firstChild.data;
			var recoleccion 		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;
			var seguro 				= datos.getElementsByTagName('seguro').item(0).firstChild.data;
			var estadocobranza		= datos.getElementsByTagName('estadocobranza').item(0).firstChild.data;
			var combustible 		= datos.getElementsByTagName('combustible').item(0).firstChild.data;
			var otros				= datos.getElementsByTagName('otros').item(0).firstChild.data;
			var subtotal 			= datos.getElementsByTagName('subtotal').item(0).firstChild.data;
			var iva 				= datos.getElementsByTagName('iva').item(0).firstChild.data;
			var ivaretenido 		= datos.getElementsByTagName('ivaretenido').item(0).firstChild.data;
			var total 				= datos.getElementsByTagName('total').item(0).firstChild.data;
			var sobseguro 			= datos.getElementsByTagName('sobseguro').item(0).firstChild.data;
			var sobexcedente		= datos.getElementsByTagName('sobexcedente').item(0).firstChild.data;
			var sobsubtotal 		= datos.getElementsByTagName('sobsubtotal').item(0).firstChild.data;
			var sobivaretenido 		= datos.getElementsByTagName('sobivaretenido').item(0).firstChild.data;
			var sobiva 				= datos.getElementsByTagName('sobiva').item(0).firstChild.data;
			var sobmontoafacturar 	= datos.getElementsByTagName('sobmontoafacturar').item(0).firstChild.data;
			var otroscantidad 		= datos.getElementsByTagName('otroscantidad').item(0).firstChild.data;
			var otrosdescripcion 	= datos.getElementsByTagName('otrosdescripcion').item(0).firstChild.data;
			var otrosimporte 		= datos.getElementsByTagName('otrosimporte').item(0).firstChild.data;
			var otrossubtotal 		= datos.getElementsByTagName('otrossubtotal').item(0).firstChild.data;
			var otrosiva 			= datos.getElementsByTagName('otrosiva').item(0).firstChild.data;
			var otrosivaretenido 	= datos.getElementsByTagName('otrosivaretenido').item(0).firstChild.data;
			var otrosmontofacturar 	= datos.getElementsByTagName('otrosmontofacturar').item(0).firstChild.data;
			var fecha 				= datos.getElementsByTagName('fecha').item(0).firstChild.data;
			
			
			if(tipodefactura=='empresarial'){
				u.guia[1].checked=true;
			}else{
				u.guia[0].checked=true;
			}
			
			if(credito=='SI'){
				u.tipoguias[0].checked=true;
			}else{
				u.tipoguias[1].checked=true;
			}
			
			u.folio.innerHTML 			= folio;
			u.estadofactura.innerHTML	= facturaestado;
			u.fecha.value 				= fecha;
			document.getElementById('lasucursal').innerHTML = nsucursal;
			document.getElementById('fechapago').innerHTML = fechapago;
			document.getElementById('fechacancelacion').innerHTML = fechacancelacion;
			u.sepuede.value			= sepuede;
			u.idcliente.value 		= cliente;
			u.nombre.value 			= nombrecliente;
			u.paterno.value			= apepat;
			u.materno.value			= apemat;
			u.calle.value 			= calle;
			u.numero.value 			= numero;
			u.cp.value 				= codigopostal;
			u.colonia.value 		= colonia;
			u.ccalles.value 		= crucecalles;
			u.poblacion.value		= poblacion;
			u.municipio.value		= municipio;
			u.estado.value 			= estado;
			u.pais.value 			= pais;
			u.telefono.value 		= telefono;
			u.fax.value 			= fax;
			u.rfc.value 			= rfc;
			
			//consulta("mostrarGuias2", "Facturacion_consulta.php?accion=11&idfactura="+folio+"&valrandom="+Math.random());
		}else{
			alerta("No se encontro la factura","¡Atencion!","folio");
			
			document.all.bonotesAccion.innerHTML = botonesnuevo;
		}
	}
	
	
	function validarGuardar(){
		<?=$cpermiso->verificarPermiso("291,320",$_SESSION[IDUSUARIO]);?>;
		if(u.idcliente.value==""){
			alerta("Proporcione el cliente","¡ATENCION!","idcliente")
			return false;
		}
		
		if(u.rfc.value.replace(/ /g,"")==""){
			alerta("Proporcione el rfc, debe ser un rfc válido","¡ATENCION!","rfc")
			return false;
		}	
		
		return true;
	}
</script>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="812" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="598" class="FondoTabla Estilo4" style="font-size:12px">FACTURACIÓN</td>
  </tr>
  <tr>
    <td><table width="811" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2" align="center">
        	
        	<?
				$s = "SELECT IF(ISNULL(MAX(folio)),0,MAX(folio))+1 AS foliofactura, 
				DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual FROM facturacion where folio > 0";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
            <table width="809" border="0" cellpadding="0" cellspacing="0">
   	      <tr>
               	  <td width="56" height="23"><input name="confirFecha" type="hidden" id="confirFecha" value="<?=date('d/m/Y') ?>" /></td>
               	  <td width="74" align="right">
                  <input type="hidden" value="" name="estadocobranza" id="estadocobranza" />
                  <input name="personamoral" type="hidden" id="personamoral" />
                  <input name="folioliquidacionmercancia" type="hidden" id="folioliquidacionmercancia" />
               	    <input name="modificar" type="hidden" id="modificar" value="0" />
              Folio <input type="hidden" name="sepuede" /><input type="hidden" name="creditodisponible" />
   	          <input type="hidden" name="folioasustituir" />   	          <input type="hidden" name="activado" />
                        	      </td>
                	<td width="179" align="right" id="folio" style="font:tahoma; font-size:15px; font-weight:bold"><?=$f->foliofactura?></td>
                	<td width="41" align="right"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarFacturasGenExtra.php?funcion=cargarFactura&todas=SI', 570, 470, 'ventana', 'Busqueda')"></div></td>
                	<td width="65" align="right">Fecha</td>
                	<td width="113" align="right"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:70px;background:#FFFF99" value="<?=$f->fechaactual ?>" readonly=""/></td>
                	<td width="95" align="right">Estado:</td>
                	<td width="182" align="right" id="estadofactura" style="font:tahoma; font-size:15px; font-weight:bold"></td>
                	<td width="4" align="right" ></td>
                </tr>
   	      <tr>
   	        <td><span style="text-align:left">Sucursal:</span></td>
   	        <td colspan="2" align="right"><div id="lasucursal" style="text-align:left"> <?=$nsucursal?></div></td>
   	        <td align="right">&nbsp;</td>
   	        <td align="right">Fecha Pago:</td>
   	        <td align="right" id="fechapago">
   	         
              
   	        </td>
   	        <td align="right">Fecha Cancelacion</td>
   	        <td align="right" id="fechacancelacion"></td>
<td align="right" ></td>
 	        </tr>
          </table>      </tr>
      <tr>
        <td width="616" height="5px;"></td>
      </tr>
      <tr>
        <td class="FondoTabla Estilo4">Datos Facturaci&oacute;n de Cliente </td>
        </tr>
      <tr>
        <td colspan="49"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="50" height="24" class="Tablas"><label>Cliente</label></td>
<td width="34" class="Tablas"><input name="idcliente" type="text" onkeypress="if(event.keyCode==13){pedirCliente(this.value)}" class="Tablas" id="nick" style="width:25px;" value="<?=$nick ?>" /></td>
              <td width="61" class="Tablas"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=pedirCliente', 625, 418, 'ventana', 'Busqueda')"></div></td>
              <td width="111" class="Tablas">Mostrar Guias de</td>
              <td width="102" class="Tablas"><input type="radio" name="guia" value="1" checked="checked" onclick="if(document.all.idcliente.value != ''){limpiarCambioClientes();cargarGuias(document.all.idcliente.value)}" />
Ventanilla</td>
              <td width="120" class="Tablas"><input type="radio" name="guia" value="1" onclick="if(document.all.idcliente.value != ''){limpiarCambioClientes();cargarGuias(document.all.idcliente.value)}" /> 
                Empresariales
</td>
              <td width="80" class="Tablas"><input type="radio" name="tipoguias" value="1" checked="checked" onclick="if(document.all.idcliente.value != ''){limpiarCambioClientes();cargarGuias(document.all.idcliente.value)}" />
                Credito</td>
              <td width="93" class="Tablas"><input type="radio" name="tipoguias" value="0" onclick="if(document.all.idcliente.value != ''){limpiarCambioClientes();cargarGuias(document.all.idcliente.value)}" />
Contado</td>
              </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" class="Tablas">Nombre&nbsp;
            &nbsp;<input name="nombre" type="text" class="Tablas" id="nombre" style="width:125px" value="<?=$nombre ?>" /> 
            Apellido Pat
            <input name="paterno" type="text" class="Tablas" id="paterno" style="width:125px" value="<?=$paterno ?>" />
          Apellido Mat
          <input name="materno" type="text" class="Tablas" id="materno" style="width:125px" value="<?=$materno ?>" /></td>
      </tr>
      <tr>
        <td colspan="3" class="Tablas">
        <table width="650" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="50"> 
                
                Calle</td>
            	<td width="252" id="celdacalle"><input name="calle" type="text" class="Tablas" id="calle" style="width:250px"/></td>
            	<td width="34">
                <img id="cliente_dir" src="../img/Boton_Agregarchico.gif" alt="Agregar Dirección" style="cursor:hand" onClick="if(document.all.idcliente.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','idcliente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=pedirCliente('+document.all.idcliente.value+')&idcliente='+document.all.idcliente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}" />                </td>
            	<td width="82">N&uacute;mero</td>
            	<td width="95"><input name="numero" type="text" class="Tablas" id="numero" style="width:80px;" value="<?=$numero ?>" /></td>
            	<td width="30">CP</td>
            	<td width="107"><input name="cp" type="text" class="Tablas" id="cp" style="width:80px;" value="<?=$cp ?>" /></td>
            </tr>
        </table>        </td>
      </tr>
      <tr>
        <td colspan="49"><table width="810" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><label>Colonia</label></td>
              <td colspan="3"><input name="colonia" type="text" class="Tablas" id="colonia" style="width:220px;" value="<?=$colonia ?>" />
                  <label></label></td>
              <td>Cruce de Calles </td>
              <td colspan="3"><input name="ccalles" type="text" class="Tablas" id="ccalles" style="width:220px;" value="<?=$ccalles ?>" /></td>
            </tr>
            <tr>
              <td><label>Poblacion</label></td>
              <td><input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:80px;" value="<?=$poblacion ?>" /></td>
              <td><label>Municipio/ Delegacion</label></td>
              <td><input name="municipio" type="text" class="Tablas" id="municipio" style="width:80px;" value="<?=$municipio ?>" /></td>
              <td width="103"><label>Estado </label></td>
              <td width="113"><input name="estado" type="text" class="Tablas" id="estado" style="width:80px;" value="<?=$estado ?>" /></td>
              <td width="30"><label>País</label></td>
              <td width="146"><input name="pais" type="text" class="Tablas" id="pais" style="width:80px;" value="<?=$pais ?>" /></td>
            </tr>
            <tr>
              <td width="62"><label>Télefono</label></td>
              <td width="105"><input name="telefono" type="text" class="Tablas" id="telefono" style="width:80px;" value="<?=$telefono ?>" /></td>
              <td width="140"><label>Fax</label></td>
              <td width="111"><input name="fax" type="text" class="Tablas" id="fax" style="width:80px;" value="<?=$fax ?>" /></td>
              <td><input type="hidden" name="facturacion" />
                Rfc</td>
              <td colspan="3"><input name="rfc" type="text" class="Tablas" id="rfc" style="width:95px;" value="<?=$rfc ?>" /></td>
</tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
          </table>
            <label></label>
            <label></label></td>
      </tr>
      <tr>
        <td colspan="49"></td>
      </tr>
      <tr>
        <td colspan="49"></td>
      </tr>
      <tr>
        <td align="center" id="bonotesAccion">
          <table><tr><td><div class="ebtn_guardar" onclick="if(validarGuardar()){ confirmar('¿Desea guardar la factura?','¡Atencion!','guardarFacturar()',''); }"/></td><td><div class="ebtn_nuevo" onclick="limpiarTodo();pedirFolio(); bloquear(false);"/></td></tr></table>
          </td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
<div id="facturacancelada" style="display:none; position:absolute; left: 100px; top: 237px;">
	<img src="../img/evaluacion cancelada.gif" />
</div>
</body>
</html>
