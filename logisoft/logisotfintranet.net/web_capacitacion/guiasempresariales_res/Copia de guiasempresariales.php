<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
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
<link href="puntovta.css" rel="stylesheet" type="text/css">
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="estilo_guia.css" rel="stylesheet" type="text/css" />
<!-- estilos y funciones para ventana modal -->
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<link href="../javascript/estiloclasetablas.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<!-- funciones para ajax -->
<script type="text/javascript" src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../convenio/validacionesConvenio.js"></script>
<script>
	var u = document.all;
	var sucursalorigen 	= 0;
	
	var valCon = new validacionesConvenio();
	
	var tabla1 = new ClaseTabla();
	
	tabla1.setAttributes({
		nombre:"tablaconteva",
		campos:[
			{nombre:"IDM", medida:4, alineacion:"left", tipo:"oculto", datos:"idmercancia"},
			{nombre:"Cant", medida:42, alineacion:"left", datos:"cantidad"},
			{nombre:"Descripcion", medida:115, alineacion:"left", datos:"descripcion"},
			{nombre:"Contenido", medida:115, alineacion:"left", datos:"contenido"},
			{nombre:"Peso", medida:42, alineacion:"right", datos:"peso"},
			{nombre:"Largo", medida:42, alineacion:"right", datos:"largo"},
			{nombre:"Ancho", medida:42, alineacion:"right", datos:"ancho"},
			{nombre:"Alto", medida:42, alineacion:"right", datos:"alto"},
			{nombre:"Vol", medida:42, alineacion:"right", datos:"volumen"},
			{nombre:"Importe", medida:75, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"M", medida:14, alineacion:"right", datos:"modificable"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		eventoDblClickFila:"paracambiarvalor(tabla1.getSelectedRow().modificable);",
		ordenable:true,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	var botonnuevo	 = '<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();parent.frames[4].document.all.foliobusqueda.value = \'\';">';
	var botonesnuevo = '<img src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="if(validarDatos()){ejecutarSubmit();};">&nbsp;&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();parent.frames[4].document.all.foliobusqueda.value = \'\';">';
	var botonesdesguardar = '<img src="../img/Boton_Imprimir.gif">&nbsp;&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();parent.frames[4].document.all.foliobusqueda.value = \'\';">';
	var botonesconsulta = '<img src="../img/Boton_Imprimir.gif">&nbsp;<img src="../img/Boton_Cancela_Guia.gif" style="cursor:hand" onClick="cancelarGuia()">&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();bloquear(false); parent.frames[4].document.all.foliobusqueda.value = \'\';">';
	
	//para cambiar descripciones convenios
	function solicitarDatosConv(){
		if(document.all.sucdestino_hidden.value!=""){
			consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idremitente="+u.idremitente.value
			+"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&valran="+Math.random());
		}
	}
	function paraConvenio(datos){
		var u = document.all;
		valCon.setDatos(datos);
		consultaTexto('obtenerMecanciaConvenio',"guiasempresariales_consulta_conv.php?accion=1&idsucdestino="+u.sucdestino_hidden.value+"&fevaluacion="+u.folioevaluacion.value
				   +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(0)+"&rd="+Math.random());
	}
	function obtenerMecanciaConvenio(datos){
		var u = document.all;
		u.t_txtexcedente.value = "$ 0.00";
		datos = datos.replace(/&#209;/g,"Ñ");
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		u.t_txtexcedente.value = "$ "+numcredvar(objeto[0].excedente.toLocaleString());
		
		u.txtrestrinccion.value = u.txtrestrinccionh.value;
		u.txtead.value			= u.txteadh.value;
		
		var filas = objeto[0].mercancia;
		tabla1.setJsonData(filas);
		
		var importetotal = 0;
		for(var i=0; i<filas.length; i++){
			importetotal += parseFloat(filas[i].importe);
		}
		u.flete.value = "$ "+numcredvar(importetotal.toLocaleString());
				
		u.chkvalordeclarado.disabled	= false;
		if(u.desead.value!="1"){
			u.chocurre.disabled 		= false;
			u.t_txteadh2.value 				= "0";
		}
		if(u.desrrecoleccion.value!="1")
			u.t_txtrecoleccionh.value	= "0";
			
		
		var msg="";
			if(u.desead.value!="1" && u.desconvenio.value!="1" && valCon.restringEADDestinatarioE()){
				msg += "El destinatario tiene restringido el servicio E.A.D.<br>";
				u.txtead.value = 0;
				u.txtrestrinccion.value = "";
				u.chocurre.checked=true;
				u.t_txtead.value = "$ 0.00";
				u.chocurre.disabled=true;
			}
			if(u.desconvenio.value!="1" && valCon.restringVDDestinatarioE()){
				msg += "El destinatario tiene restringido el servicio VALOR DECLARADO<br>";
				u.chkvalordeclarado.disabled=true;
			}
		if(valCon.restringirDestinoEAD()){
			msg += "El destino seleccionado tiene restringida la entrega a domicilio<br>";
			u.txtead.value = 0;
			u.txtrestrinccion.value = "";
			u.chocurre.checked = 1;
		}
		if(u.desconvenio.value!="1" && valCon.validaEADsucursalE(valCon.validarConvenioAUsar(0),u.sucdestino_hidden.value)){
			u.t_txteadh2.value = "1";
		}
		if(u.desconvenio.value!="1" && u.desconvenio.value!="1" && valCon.validaRecsucursalE(valCon.validarConvenioAUsar(0),u.sucdestino_hidden.value))
			u.t_txtrecoleccionh.value = "1";
		if(u.iddestinatario.value){
			if(valCon.validarDestRestEADF(u.chocurre)){
				msg += "El destino seleccionado no acepta EAD a personas fisicas sin convenio<br>";
				u.txtead.value = 0;
				u.txtrestrinccion.value = "";
				u.chocurre.checked=true;
				u.t_txtead.value = "$ 0.00";
				u.chocurre.disabled=true;
				u.t_txtrecoleccionh.value = "1";
			}
		}
		if(msg!="")
			alerta3(msg,"¡Atencion!");
		
		if(u.tipoguia.value=="PREPAGADA"){
			bloquearServicios(true);
			u.t_txtseguro.value = "$ 0.00";
		}
		calculartotales();
		document.all.idsguardar.innerHTML = botonesnuevo;
	}
	function paracambiarvalor(valor){
		if(valor=="X"){
			abrirVentanaFija('../convenio/descripcionesconvenio.php?idconvenio='+valCon.validarConvenioAUsar(0), 500, 400, 'ventana', 'Busqueda');
		}
	}
	function cambiarValor(descripcion){
		consultaTexto('obtenerMecanciaConvenio',"guiasempresariales_consulta_conv.php?accion=1&idsucdestino="+u.sucdestino_hidden.value
						  +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(0)
						  +"&idmercancia="+tabla1.getSelectedRow().idmercancia+"&descripcion="+descripcion
						  +"&rd="+Math.random());
	}
	
	// funciones generales
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
	function mostrarEvaluaciones(){
		so 	= '<?=$_SESSION[IDSUCURSAL]?>';
		abrirVentanaFija('../buscadores_generales/buscarEvaluacionGen.php?funcion=pedirDatosEvaluacion&ands=1&tipo=evaluacion&sucorigen='+so, 650, 450, 'Evaluaciones', 'Busqueda');
	}
	function bloquear(valor){
		u = document.all;
		u.chocurre.disabled 		= valor;
		u.iddestinatario.readOnly	= valor;
		
		u.iddestinatario.style.backgroundColor		= (valor)?"#FFFF99":"";
		
		u.chkemplaye.disabled 			= valor;
		u.chkbolsaempaque.disabled 	= valor;
		u.chkavisocelular.disabled 	= valor;
		u.chkvalordeclarado.disabled 	= valor;
		u.chkacuserecibo.disabled 		= valor;
		u.chkcod.disabled 				= valor;
		u.chkrecoleccion.disabled 		= valor;
		
		u.b_destinatario.style.visibility = (valor)?"hidden":"visible";
		u.b_destinatario_dir.style.visibility = (valor)?"hidden":"visible";
		u.img_descuento.style.visibility = (valor)?"hidden":"visible";
		
	}
	function bloquearServicios(valor){
		u.chkemplaye.disabled 			= valor;
		u.chkbolsaempaque.disabled 		= valor;
		u.chkavisocelular.disabled 		= valor;
		u.chkvalordeclarado.disabled 	= valor;
		u.chkacuserecibo.disabled 		= valor;
		u.chkcod.disabled 				= valor;
		u.chkrecoleccion.disabled 		= valor;
		u.img_descuento.style.visibility= (!valor)?"visible":"hidden";
	}
	
	//funciones limpiar
	function limpiar_remitente(){
		u = document.all;
		u.idremitente.value 	= "";
		u.rem_rfc.value 		= "";
		u.rem_cliente.value 	= "";
		u.rem_numero.value		= "";
		u.rem_calle.value 		= "";
		u.rem_cp.value			= "";
		u.rem_colonia.value		= "";
		u.rem_poblacion.value	= "";
		u.rem_telefono.value	= "";
		u.rem_personamoral.value= "";
	}
	function limpiar_destinatario(){
		u = document.all;
		u.iddestinatario.value 	= "";
		u.des_rfc.value 		= "";
		u.des_cliente.value 	= "";
		u.des_numero.value		= "";
		u.des_cp.value			= "";
		u.celda_des_calle.innerHTML = '<input name="des_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; width:130px" value=""/><input type="hidden" name="des_direcciones">'
		
		u.des_colonia.value		= "";
		u.des_poblacion.value	= "";
		u.des_telefono.value	= "";
		u.des_personamoral.value= "";
	}
	function limpiar_evaluacion(){
			bloquearServicios(false);
			u = document.all;
			
			parent.frames[4].document.all.folioSeleccionado.innerHTML = "&nbsp;";			
			tabla1.clear();
			
			u.folioguiaempresarial.value= "";
			u.folioevaluacion.value		= "";
			u.paraconvenio.value		= "0";
			u.guiaguardadav.value		= "";
			u.estado.innerHTML 				= "";
			u.chocurre.checked			= false;
			u.destino.value 			= "";
			u.destino_hidden.value		= "";
			u.sucdestino.value 			= "";
			u.sucdestino_hidden.value 	= "";
			u.txtocu.value 				= "";
			u.txtead.value 				= "";
			
			u.totalpaquetes.value		= "";
			u.totalpeso.value			= "";
			u.totalvolumen.value		= "";
			
			u.t_txteadh2.value 				= "0";
			u.t_txtrecoleccionh.value		= "0";
			u.chkvalordeclarado.disabled	= false;
			u.chocurre.disabled 			= false;
			u.tipoflete.disabled 			= false;
			
			u.chkemplaye.checked 		= false;
			u.chkbolsaempaque.checked 	= false;
			u.chkavisocelular.checked 	= false;
			u.chkvalordeclarado.checked = false;
			u.chkacuserecibo.checked 	= false;
			u.chkcod.checked 			= false;
			u.chkrecoleccion.checked 	= false;
			u.chkemplaye.readOnly 		= false;
			u.chkbolsaempaque.readOnly 	= false;
			u.chkavisocelular.readOnly 	= false;
			u.chkvalordeclarado.readOnly= false;
			u.chkacuserecibo.readOnly 	= false;
			u.chkcod.readOnly 			= false;
			u.chkrecoleccion.readOnly 	= false;
			
			u.txtavisocelular1.value 	= "";
			u.txtavisocelular2.value 	= "";
			u.txtacuserecibo.value 		= "";
			u.txtcod.value 				= "";
			u.txtemplaye.value 			= "";
			u.txtbolsaempaque1.value 	= "";
			u.txtbolsaempaque2.value 	= "";
			u.txtdeclarado.value 		= "";
			u.txtobservaciones.value	= "";
			u.txtrestrinccion.value		= "";
			
			u.txtavisocelular1h.value 	= "";
			u.txtavisocelular2h.value 	= "";
			u.txtacusereciboh.value 	= "";
			u.txtcodh.value 			= "";
			u.txtemplayeh.value 		= "";
			u.txtbolsaempaque1h.value 	= "";
			u.txtbolsaempaque2h.value 	= "";
			u.txtbolsaempaque3h.value 	= "";
			
			u.txtrecoleccion.value		= "";
			u.txtrecoleccionh.value		= "";
			
			u.flete.value				= "";
			u.t_txtdescuento1.value		= "";
			u.t_txtdescuento2.value		= "";
			u.t_txtead.value			= "";
			u.t_txteadh.value			= "";
			u.t_txtrecoleccion.value	= "";
			u.t_txtseguro.value			= "";
			u.t_txtotros.value			= "";
			u.t_txtexcedente.value		= "";
			u.t_txtcombustible.value	= "";
			u.t_txtsubtotal.value		= "";
			u.t_txtiva.value			= "";
			u.t_txtivaretenido.value	= "";
			u.t_txttotal.value			= "";
			u.pagoregistrado.value		= 0;
			u.efectivo.value			= "";
			u.cheque.value				= "";
			u.ncheque.value				= "";
			u.banco.value				= "";
			u.tarjeta.value				= "";
			u.transferencia.value		= "";
			
			u.desead.value				= "";
			u.desrrecoleccion.value		= "";
			u.desporcobrar.value		= "";
			u.desconvenio.value			= "";
			
			document.all.idsguardar.innerHTML = botonesnuevo;
			
	}
	function limpiar_cajas(){
		parent.frames[4].document.all.foliobusqueda.value = "";
		parent.frames[4].document.all.folioSeleccionado.innerHTML = "&nbsp;";
	}
	
	//funciones calcular
	function calcularservicios(){
		u = document.all;
		
		if(u.t_txteadh2.value=="0"){
			if((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))*0.10)<parseFloat(u.pc_ead.value)){
					u.t_txteadh.value = "$ "+numcredvar(u.pc_ead.value);
			}else{
				valoread = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,"")))*.10)*100)/100;
				u.t_txteadh.value = "$ "+numcredvar(valoread.toLocaleString());
			}
		}else{
			u.t_txteadh.value = "$ 0.00";
		}
			
			u.t_txteadh.value = (u.t_txteadh.value=="")?"$ 0.00":u.t_txteadh.value;
			
			if(u.chocurre.checked==false){
				document.all.t_txtead.value = document.all.t_txteadh.value;
			}else{
				document.all.t_txtead.value = '$ 0.00';
			} 
			if(u.chkrecoleccion.checked && u.t_txtrecoleccionh.value=="0"){
				if((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))*0.10)<parseFloat(u.pc_recoleccion.value)){
					u.t_txtrecoleccion.value = "$ "+numcredvar(u.pc_recoleccion.value);
				}else{
					valorrecoleccion = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,"")))*.10)*100)/100;
					u.t_txtrecoleccion.value = "$ "+numcredvar(valorrecoleccion.toLocaleString());
				}
			}else{
				u.t_txtrecoleccion.value = "$ 0.00";
			}
			
			
			u.t_txtrecoleccion.value = (u.t_txtrecoleccion.value=="")?"$ 0.00":u.t_txtrecoleccion.value;
			
			//u.t_txtrecoleccion.value = "";
			if(u.tipoguia.value=="PREPAGADA"){
				u.t_txtseguro.value = "$ 0.00";
			}else{
				if(u.txtdeclarado.value!="" && u.txtdeclarado.value!="0"){
					if(parseFloat(u.txtdeclarado.value.replace("$ ","").replace(/,/g,""))<parseFloat(u.pc_porcada.value)){
						//alert("1_"+u.pc_porcada.value);
						u.t_txtseguro.value = "$ "+numcredvar(u.pc_costo.value);
					}else{
						//alert("2_"+u.pc_porcada.value);
						//eldeclarado
						valorseguro = Math.round(((parseFloat(((u.txtdeclarado.value=="")?"0":u.txtdeclarado.value.toLocaleString()).replace("$ ","").replace(/,/g,""))/parseFloat(u.pc_porcada.value))*parseFloat(u.pc_costo.value))*100)/100;
						//alert(u.txtdeclarado.value+"/"+u.pc_porcada.value+"*"+u.pc_costo.value);
						//alert(valorseguro);
						u.t_txtseguro.value = "$ "+numcredvar(valorseguro.toLocaleString());
					}
				}else{
					u.t_txtseguro.value = "$ "+numcredvar(u.pc_costo.value);
				}
				u.t_txtseguro.value = (u.t_txtseguro.value=="")?"$ 0.00":u.t_txtseguro.value;
			}
			
			valorcombustible = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,""))+parseFloat(u.t_txtexcedente.value.replace("$ ","").replace(/,/g,"")))*(parseFloat(u.pc_tarifacombustible.value)/100))*100)/100;
			//alert(u.flete.value+"-"+u.t_txtdescuento2.value+"*"+u.pc_tarifacombustible.value);
			//alert(valorcombustible);
			u.t_txtcombustible.value = "$ "+numcredvar(valorcombustible.toLocaleString());
		
			u.t_txtcombustible.value = (u.t_txtcombustible.value=="")?"$ 0.00":u.t_txtcombustible.value;
			
			u.t_txtrecoleccion.value = (u.t_txtrecoleccion.value=="")?"$ 0.00":u.t_txtrecoleccion.value;
			
			u.t_txtdescuento1.value = (u.t_txtdescuento1.value=="")?"0 %":u.t_txtdescuento1.value;
			u.t_txtdescuento2.value = (u.t_txtdescuento2.value=="")?"$ 0.00":u.t_txtdescuento2.value;
			
		var templaye 		= parseFloat((u.txtemplaye.value=="")?0:u.txtemplaye.value.replace("$ ","").replace(/,/g,""));
		var tbolsaempaque	= parseFloat((u.txtbolsaempaque2.value=="")?0:u.txtbolsaempaque2.value.replace("$ ","").replace(/,/g,""));
		var tavisocelular	= parseFloat((u.txtavisocelular1.value=="")?0:u.txtavisocelular1.value.replace("$ ","").replace(/,/g,""));
		var tdeclarado		= parseFloat((u.txtdeclarado.value=="")?0:u.txtdeclarado.value.replace("$ ","").replace(/,/g,""));
		var tacuserecibo	= parseFloat((u.txtacuserecibo.value=="")?0:u.txtacuserecibo.value.replace("$ ","").replace(/,/g,""));
		var tcod			= parseFloat((u.txtcod.value=="")?0:u.txtcod.value.replace("$ ","").replace(/,/g,""));
		
		u.t_txtotros.value 	= templaye+tbolsaempaque+tavisocelular+tacuserecibo+tcod;
		
		u.t_txtotros.value 	= "$ "+numcredvar(u.t_txtotros.value);
	}
	function calculartotales(){
		var u = document.all;
		if(u.folioevaluacion.value!=""){
			calcularservicios();
			
			var ptflete 			= parseFloat((u.flete.value=="")?0:u.flete.value.replace("$ ","").replace(/,/g,""));
			var ptdescuento 		= parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,""));
			var ptead 				= parseFloat((u.t_txtead.value=="")?0:u.t_txtead.value.replace("$ ","").replace(/,/g,""));
			var ptrecoleccion 		= parseFloat((u.t_txtrecoleccion.value=="")?0:u.t_txtrecoleccion.value.replace("$ ","").replace(/,/g,""));
			var ptseguro	 		= parseFloat((u.t_txtseguro.value=="")?0:u.t_txtseguro.value.replace("$ ","").replace(/,/g,""));
			var ptotros		 		= parseFloat((u.t_txtotros.value=="")?0:u.t_txtotros.value.replace("$ ","").replace(/,/g,""));
			var ptexcedente			= parseFloat((u.t_txtexcedente.value=="")?0:u.t_txtexcedente.value.replace("$ ","").replace(/,/g,""));
			var ptcombustible		= parseFloat((u.t_txtcombustible.value=="")?0:u.t_txtcombustible.value.replace("$ ","").replace(/,/g,""));
			var ptsubtotal			= ptflete-ptdescuento+ptead+ptrecoleccion+ptseguro+ptotros+ptexcedente+ptcombustible;
			u.t_txtsubtotal.value	= "$ "+numcredvar((Math.round(ptsubtotal*100)/100).toLocaleString());
			u.t_txtiva.value		= Math.round((ptsubtotal*(parseFloat(u.pc_iva.value)/100))*100)/100;
			if((u.tipoflete.value=="PAGADA" && u.rem_personamoral.value=="SI") ||(u.tipoflete.value=="PARA COBRO" && u.des_personamoral.value=="SI") ){
				u.t_txtivaretenido.value = Math.round( (ptsubtotal*(parseFloat(u.pc_ivaretenido.value)/100))*100) /100;
			}else{
				u.t_txtivaretenido.value = "0.00";
			}
			u.t_txttotal.value		= Math.round( (ptsubtotal-parseFloat(u.t_txtivaretenido.value.replace("$ ","").replace(/,/g,""))+parseFloat(u.t_txtiva.value.replace("$ ","").replace(/,/g,"")) ) *100)/100;
			u.t_txtiva.value		= "$ "+numcredvar(u.t_txtiva.value.toLocaleString());
			u.t_txtivaretenido.value= "$ "+numcredvar(((u.t_txtivaretenido.value=="")?"0":u.t_txtivaretenido.value).toLocaleString());
			u.t_txttotal.value		= "$ "+numcredvar(u.t_txttotal.value.toLocaleString());
		}
	}
	
	//datos de la evaluacion
	function pedirDatosEvaluacion(idevaluacion){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			sucursalorigen 	= '<?=$_SESSION[IDSUCURSAL]?>';
			consulta("devolverDatosEvaluacion", "guiasempresariales_consulta.php?accion=1&folio="+idevaluacion+"&idsucorigen="+sucursalorigen+"&valrandom="+Math.random());
		}else{
			alerta("Seleccione una sucursal de Origen","¡Atencion!","fecha");
		}
	}
	function devolverDatosEvaluacion(datos){
		bloquear(false);
		limpiar_evaluacion();
		limpiar_remitente();
		limpiar_destinatario();
		//u.guiaguardadav.value = "0";
		
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			folioevaluacion		= datos.getElementsByTagName('folioevaluacion').item(0).firstChild.data;
			nuevofolioguia		= datos.getElementsByTagName('nuevofolioguia').item(0).firstChild.data;
			tipopago			= datos.getElementsByTagName('tipopago').item(0).firstChild.data;
			tipoguia			= datos.getElementsByTagName('tipoguia').item(0).firstChild.data;
			tipoflete			= datos.getElementsByTagName('tipoflete').item(0).firstChild.data;
			
			vestado 			= datos.getElementsByTagName('estado').item(0).firstChild.data;
			destino 			= datos.getElementsByTagName('ndestino').item(0).firstChild.data;
			npobdes				= datos.getElementsByTagName('npobdes').item(0).firstChild.data;
			iddestino 			= datos.getElementsByTagName('iddestino').item(0).firstChild.data;
			sucursal			= datos.getElementsByTagName('nsucursal').item(0).firstChild.data;
			idsucursal 			= datos.getElementsByTagName('idsucursal').item(0).firstChild.data;
			npobdestino 		= datos.getElementsByTagName('npobdestino').item(0).firstChild.data;
			cantidadbolsa		= datos.getElementsByTagName('cantidadbolsa').item(0).firstChild.data;
			bolsaempaque		= datos.getElementsByTagName('bolsaempaque').item(0).firstChild.data;
			emplaye				= datos.getElementsByTagName('emplaye').item(0).firstChild.data;
			sbolsaempaque		= datos.getElementsByTagName('sbolsaempaque').item(0).firstChild.data;
			semplaye			= datos.getElementsByTagName('semplaye').item(0).firstChild.data;
			totalbolsaempaque	= datos.getElementsByTagName('totalbolsaempaque').item(0).firstChild.data;
			totalemplaye		= datos.getElementsByTagName('totalemplaye').item(0).firstChild.data;
			ocu					= datos.getElementsByTagName('ocu').item(0).firstChild.data;
			ead					= datos.getElementsByTagName('ead').item(0).firstChild.data;
			pagominimocheque	= datos.getElementsByTagName('pfp_pagominimocheques').item(0).firstChild.data;
			
			avisocelular		= datos.getElementsByTagName('avisocelular').item(0).firstChild.data;
			acuserecibo			= datos.getElementsByTagName('acuserecibo').item(0).firstChild.data;
			cod					= datos.getElementsByTagName('cod').item(0).firstChild.data;
			restrinccion		= datos.getElementsByTagName('restrincciones').item(0).firstChild.data;
			restrinccion2		= datos.getElementsByTagName('restr2').item(0).firstChild.data;
			restringirporcobrar = datos.getElementsByTagName('restringirporcobrar').item(0).firstChild.data;
			//para totales
			pt_ead				= datos.getElementsByTagName('pt_ead').item(0).firstChild.data;
			pt_recoleccion		= datos.getElementsByTagName('pt_recoleccion').item(0).firstChild.data;
			pt_iva				= datos.getElementsByTagName('pt_iva').item(0).firstChild.data;
			pt_ivaretenido		= datos.getElementsByTagName('pt_ivaretenido').item(0).firstChild.data;
			por_combustible		= datos.getElementsByTagName('por_combustible').item(0).firstChild.data;
			max_descuento		= datos.getElementsByTagName('max_des').item(0).firstChild.data;
			vporcada			= datos.getElementsByTagName('por_cada').item(0).firstChild.data;
			vscosto				= datos.getElementsByTagName('scosto').item(0).firstChild.data;
			erecoleccion		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;
			pesominimodesc		= datos.getElementsByTagName('pesominimodesc').item(0).firstChild.data;
			restringiread		= datos.getElementsByTagName('restringiread').item(0).firstChild.data;
			
			desead				= datos.getElementsByTagName('desead').item(0).firstChild.data;
			desrrecoleccion		= datos.getElementsByTagName('desrrecoleccion').item(0).firstChild.data;
			desporcobrar		= datos.getElementsByTagName('desporcobrar').item(0).firstChild.data;
			desconvenio			= datos.getElementsByTagName('desconvenio').item(0).firstChild.data;
			total_excedente		= datos.getElementsByTagName('total_excedente').item(0).firstChild.data;
			
			u.desead.value			= desead;
			u.desrrecoleccion.value	= desrrecoleccion;
			u.desporcobrar.value	= desporcobrar;
			u.desconvenio.value		= desconvenio;
			
			var msg = "";
			if(desead==1){
				msg += "El destino no cuenta con servicio de EAD<br>";
				u.chocurre.checked 	= true;
				u.chocurre.disabled = true;
			}
			if(desrrecoleccion==1){
				msg += "El destino no cuenta con servicio de recoleccion<br>";
				u.chkrecoleccion.checked  = false;
				u.chkrecoleccion.disabled = true;
			}
			if(msg!="")
			alerta3(msg,"¡Atencion!");
			
			
			//para datagrid importe
			importe_tipo		= datos.getElementsByTagName('tipototales').item(0).firstChild.data;
			valor_totalimporte	= datos.getElementsByTagName('valor_totalimporte').item(0).firstChild.data;
			u.folioguiaempresarial.value = nuevofolioguia;
			parent.frames[4].document.all.folioSeleccionado.innerHTML = nuevofolioguia;
			
			u.folioevaluacion.value	= folioevaluacion;
			u.tipopago.value = tipopago;
			u.tipoguia.value = tipoguia;
			u.tipoflete.value = tipoflete;
			u.t_txtexcedente.value = "$ "+numcredvar(total_excedente);
			//datos cliente
			u.idremitente.value 		= datos.getElementsByTagName('rem_id').item(0).firstChild.data;
			u.rem_rfc.value 			= datos.getElementsByTagName('rem_rfc').item(0).firstChild.data;
			u.rem_cliente.value 		= datos.getElementsByTagName('rem_nombre').item(0).firstChild.data;
			u.rem_personamoral.value	= datos.getElementsByTagName('rem_personamoral').item(0).firstChild.data;
			v_celular 					= datos.getElementsByTagName('rem_celular').item(0).firstChild.data;
			u.txtavisocelular2h.value 	= (v_celular!="")?v_celular:"";
			u.rem_calle.value 			= datos.getElementsByTagName('rem_calle').item(0).firstChild.data;
			u.rem_numero.value 			= datos.getElementsByTagName('rem_numero').item(0).firstChild.data;
			u.rem_cp.value 				= datos.getElementsByTagName('rem_cp').item(0).firstChild.data;
			u.rem_colonia.value 		= datos.getElementsByTagName('rem_colonia').item(0).firstChild.data;
			u.rem_poblacion.value 		= datos.getElementsByTagName('rem_poblacion').item(0).firstChild.data;
			u.rem_telefono.value 		= datos.getElementsByTagName('rem_telefono').item(0).firstChild.data;
			
			
			//final datos cliente
			
			u.estado.innerHTML = vestado;
			u.destino.value = destino;
			u.destino_hidden.value = iddestino;
			u.npobdes.value = npobdes;
			u.destino.poblacion = npobdestino;
			u.sucdestino_hidden.value = idsucursal;
			u.sucdestino.value = sucursal;
			u.txtemplayeh.value = "$ "+numcredvar((totalemplaye==0 || totalemplaye=="")?semplaye:totalemplaye);
			u.txtbolsaempaque1h.value = cantidadbolsa;
			u.txtbolsaempaque2h.value = "$ "+numcredvar(totalbolsaempaque);
			u.txtbolsaempaque3h.value = "$ "+numcredvar(sbolsaempaque);
			u.txtocu.value = ocu;
			u.txtead.value = ead;
			u.txteadh.value = ead;
			u.pagominimocheque.value = pagominimocheque;
			u.restringiread.value = restringiread;
			
			if(restringiread==1){
				u.chocurre.checked=true;
			}
			
			u.pc_ead.value					= pt_ead;
			u.pc_recoleccion.value			= pt_recoleccion;
			u.pc_tarifacombustible.value	= por_combustible;
			u.pc_maximodescuento.value		= max_descuento;
			u.pc_porcada.value				= vporcada;
			u.pc_costo.value				= vscosto;
			u.pc_iva.value					= pt_iva;
			u.pc_ivaretenido.value			= pt_ivaretenido;
			u.pc_pesominimodesc.value		= pesominimodesc;
			//u.t_txtexcedente.value			= "$ 0.00";
			
			u.txtrestrinccion.value = (restrinccion==0)?"":restrinccion;
			if(restrinccion2!=0){
				u.txtrestrinccion.value += "La entrega se hara hasta el dia "+restrinccion2;
				u.txtrestrinccionh.value += "La entrega se hara hasta el dia "+restrinccion2;
			}
			if(emplaye==1){
				u.chkemplaye.checked = true;
				u.chkemplaye.disabled = true;
				u.txtemplaye.value = "$ "+numcredvar(totalemplaye);
			}else{
				u.chkemplaye.checked = false;
			}
			if(bolsaempaque==1){
				u.chkbolsaempaque.checked = true;
				u.chkbolsaempaque.disabled = true;
				u.txtbolsaempaque1.value = cantidadbolsa;
				u.txtbolsaempaque2.value = "$ "+numcredvar(totalbolsaempaque);
			}else{
				u.chkbolsaempaque.checked = false;
			}
			if(erecoleccion!=0){
				u.chkrecoleccion.checked = true;
				u.chkrecoleccion.disabled = true;
				u.txtrecoleccionh.value		= "$ "+numcredvar(erecoleccion);	
				u.txtrecoleccion.value		= "$ "+numcredvar(erecoleccion);	
			}else{
				u.chkrecoleccion.checked = false;
				u.chkrecoleccion.disabled = false;
				u.txtrecoleccionh.value		= "";	
			}
			
			u.txtavisocelular1h.value 	= "$ "+numcredvar(avisocelular);
			u.txtacusereciboh.value 	= "$ "+numcredvar(acuserecibo);
			u.txtcodh.value 			= "$ "+numcredvar(cod);
			
			//total de evaluacion
			var enconeva = datos.getElementsByTagName('encontroevaluacion').item(0).firstChild.data;
			if(enconeva>0){
				tpaquetes	= 0;
				tpeso		= 0;
				tvolumen	= 0;
				timporte	= 0;
				for(m=0;m<enconeva;m++){	
					var objetox = new Object();
					
					idmercancia	= datos.getElementsByTagName('idmercancia').item(m).firstChild.data;
					cantidad	= datos.getElementsByTagName('cantidad').item(m).firstChild.data;
					descripcion	= datos.getElementsByTagName('descripcion').item(m).firstChild.data;
					contenido	= datos.getElementsByTagName('contenido').item(m).firstChild.data;
					peso		= datos.getElementsByTagName('peso').item(m).firstChild.data;
					volumen		= datos.getElementsByTagName('volumen').item(m).firstChild.data;
					importe		= datos.getElementsByTagName('importe').item(m).firstChild.data;
					largo		= datos.getElementsByTagName('largo').item(m).firstChild.data;
					ancho		= datos.getElementsByTagName('ancho').item(m).firstChild.data;
					alto		= datos.getElementsByTagName('alto').item(m).firstChild.data;
					modificable	= datos.getElementsByTagName('modificable').item(m).firstChild.data;
					
					objetox.idmercancia 	= idmercancia;
					objetox.cantidad 		= cantidad;
					objetox.descripcion 	= descripcion;
					objetox.contenido 		= contenido;
					objetox.peso 			= peso;
					objetox.largo 			= largo;
					objetox.ancho 			= ancho;
					objetox.alto 			= alto;
					objetox.volumen 		= volumen;
					objetox.importe 		= importe;
					objetox.modificable 	= modificable;
					tabla1.add(objetox);
					
					tpaquetes	+= parseFloat(cantidad);
					tpeso		+= parseFloat(peso);
					tvolumen	+= parseFloat(volumen);
					timporte	+= parseFloat(importe);
				}
				u.totalpaquetes.value 	= tpaquetes;
				u.totalpeso.value 		= tpeso;
				u.totalvolumen.value 	= tvolumen;
				if(importe_tipo==1){
					u.flete.value	= "$ "+numcredvar(valor_totalimporte.toLocaleString());
				}else{
					u.flete.value	= "$ "+numcredvar(timporte.toLocaleString());
				}
				
			}
			if(tipoguia=="PREPAGADA"){
				bloquearServicios(true);
				u.t_txtseguro.value = "$ 0.00";
			}
			//calculo de totales			
			calculartotales();
			document.all.idsguardar.innerHTML = botonesnuevo;
		}else{
			alerta("Evaluación no encontrada","¡Alerta!","idremitente");
		}
		u.idremitente.focus();
	}
	function devolverDestinatario(valor){
		var u = document.all;
		if(u.folioevaluacion.value==""){
			alerta3("Porfavor seleccione la evaluacion para poder agregar el destinatario", "¡Atencion!");
			u.iddestinatario.value = "";
		}else{
			limpiar_destinatario();
			document.all.iddestinatario.value = valor;
			consulta("mostrarDestinatario", "guiasempresariales_consulta.php?accion=2&idcliente="+valor+"&poblacion="+u.npobdes.value+"&valrandom="+Math.random());
		}
	}
	function mostrarDestinatario(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;
			u.des_rfc.value 			= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			u.des_cliente.value 		= datos.getElementsByTagName('ncliente').item(0).firstChild.data;
			u.des_personamoral.value	= datos.getElementsByTagName('personamoral').item(0).firstChild.data;
			if(endir==1){
				document.all.celda_des_calle.innerHTML ='<input name="des_calle" readonly="true" type="text" '
				+'style="background:#FFFF99;font:tahoma; font-size:9px; width:130px" value="" /><input type="hidden" name="des_direcciones">';
				u.des_direcciones.value	= datos.getElementsByTagName('idcalle').item(i).firstChild.data;
				u.des_calle.value 		= datos.getElementsByTagName('calle').item(0).firstChild.data;
				u.des_numero.value 		= datos.getElementsByTagName('numero').item(0).firstChild.data;
				u.des_cp.value 			= datos.getElementsByTagName('cp').item(0).firstChild.data;
				u.des_colonia.value 	= datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.des_poblacion.value 	= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				u.des_telefono.value 	= datos.getElementsByTagName('telefono').item(0).firstChild.data;
			}else if(endir>1){
				var comb = "<select name='des_direcciones' style='width:165px;font:tahoma; font-size:9px' onchange='"
				+"document.all.des_numero.value=this.options[this.selectedIndex].numero;"
				+"document.all.des_cp.value=this.options[this.selectedIndex].cp;"
				+"document.all.des_colonia.value=this.options[this.selectedIndex].colonia;"
				+"document.all.des_poblacion.value=this.options[this.selectedIndex].poblacion;"
				+"document.all.des_telefono.value=this.options[this.selectedIndex].telefono;"
				+"'>";
				
				for(var i=0; i<endir; i++){
					v_idcalle		= datos.getElementsByTagName('idcalle').item(i).firstChild.data;
					v_calle 		= datos.getElementsByTagName('calle').item(i).firstChild.data;
					v_numero 		= datos.getElementsByTagName('numero').item(i).firstChild.data;
					v_cp 			= datos.getElementsByTagName('cp').item(i).firstChild.data;
					v_colonia	 	= datos.getElementsByTagName('colonia').item(i).firstChild.data;
					v_poblacion 	= datos.getElementsByTagName('poblacion').item(i).firstChild.data;
					v_telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;
					if(i==0){
						u.des_numero.value 		= v_numero;
						u.des_cp.value 			= v_cp;
						u.des_colonia.value 	= v_colonia;
						u.des_poblacion.value 	= v_poblacion;
						u.des_telefono.value 	= v_telefono;	
					}
					
					comb += "<option value='"+v_idcalle+"' numero='"+v_numero+"' cp='"+v_cp+"' colonia='"+v_colonia+"'"
					+"poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'>"
					+v_calle+", "+v_numero+", "+v_colonia+"</option>";
				}
				comb += "</select>";
				document.all.celda_des_calle.innerHTML = comb;
			}else{
				alerta("El Cliente no tiene direccion","","iddestinatario");
			}
			if(u.desconvenio.value!="1"){
			consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idremitente="+u.idremitente.value
						  +"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&fevaluacion="+u.folioevaluacion.value
						  +"&valran="+Math.random());
			}
		}else{
			alerta("El Cliente no existe","","iddestinatario");
		}
		if(u.des_poblacion.value != u.npobdes.value){
			alerta("Direccion del destinatario no concuerda con el destino, debe corregir dirección, destino, o enviar ocure","¡Alerta!","iddestinatario");	
		}
		if(u.tipoguia.value=="PREPAGADA"){
			bloquearServicios(true);
			u.t_txtseguro.value = "$ 0.00";
		}
		calculartotales();
	}
	
	//registrar guia
	function registrarGuia(){
		var u = document.all;
		
		var newfolio				= u.folioguiaempresarial.value;
		var evaluacion 				= u.folioevaluacion.value;
		var fecha					= u.fecha.value;
		var tipoflete				= u.tipoflete.value;
		var tipopago				= u.tipopago.value;
		var tipoguia				= u.tipoguia.value;
		var ocurre					= (u.chocurre.checked)?"1":"0";
		var idsucursalorigen		= sucursalorigen;
		var iddestino				= u.destino_hidden.value;
		var idsucursaldestino		= u.sucdestino_hidden.value;
		var idremitente				= u.idremitente.value;
		var iddireccionremitente	= u.rem_direcciones.value;
		var iddestinatario			= u.iddestinatario.value;
		var iddirecciondestinatario = u.des_direcciones.value;
		var entregaocurre			= (u.txtocu.value=="")?"0":u.txtocu.value;
		var entregaead				= (u.txtead.value=="")?"0":u.txtead.value;
		var restrinccion			= u.txtrestrinccion.value;
		var totalpaquetes			= u.totalpaquetes.value;
		var totalpeso				= u.totalpeso.value;
		var totalvolumen			= u.totalvolumen.value;
		var emplaye					= u.txtemplaye.value.replace("$ ","").replace(/,/g,"");
		var bolsaempaque			= u.txtbolsaempaque1.value;
		var totalbolsaempaque		= u.txtbolsaempaque2.value.replace("$ ","").replace(/,/g,"");
		var avisocelular			= u.txtavisocelular1.value.replace("$ ","").replace(/,/g,"");
		var celular					= u.txtavisocelular2.value;
		var valordeclarado			= u.txtdeclarado.value.replace("$ ","").replace(/,/g,"");
		var acuserecibo				= u.txtacuserecibo.value.replace("$ ","").replace(/,/g,"");
		var cod						= u.txtcod.value.replace("$ ","").replace(/,/g,"");
		var recoleccion				= u.txtrecoleccion.value.replace("$ ","").replace(/,/g,"");
		var observaciones			= u.txtobservaciones.value;
		var tflete					= u.flete.value.replace("$ ","").replace(/,/g,"");
		var tdescuento				= u.t_txtdescuento1.value.replace(" %","");
		var ttotaldescuento			= u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,"");
		var tcostoead				= u.t_txtead.value.replace("$ ","").replace(/,/g,"");
		var trecoleccion			= u.t_txtrecoleccion.value.replace("$ ","").replace(/,/g,"");
		var tseguro					= u.t_txtseguro.value.replace("$ ","").replace(/,/g,"");
		var totros					= u.t_txtotros.value.replace("$ ","").replace(/,/g,"");
		var texcedente				= u.t_txtexcedente.value.replace("$ ","").replace(/,/g,"");
		var tcombustible			= u.t_txtcombustible.value.replace("$ ","").replace(/,/g,"");
		var subtotal				= u.t_txtsubtotal.value.replace("$ ","").replace(/,/g,"");
		var tiva					= u.t_txtiva.value.replace("$ ","").replace(/,/g,"");
		var ivaretenido				= u.t_txtivaretenido.value.replace("$ ","").replace(/,/g,"");
		var total					= u.t_txttotal.value.replace("$ ","").replace(/,/g,"");
		var efectivo				= u.efectivo.value.replace("$ ","").replace(/,/g,"");
		var cheque					= u.cheque.value.replace("$ ","").replace(/,/g,"");
		var banco					= u.banco.value.replace("$ ","").replace(/,/g,"");
		var ncheque					= u.ncheque.value;
		var tarjeta					= u.tarjeta.value.replace("$ ","").replace(/,/g,"");
		var trasferencia			= u.transferencia.value.replace("$ ","").replace(/,/g,"");
		
		consulta("guiaGuardada","guiasempresariales_consulta.php?accion=4&evaluacion="+evaluacion+"&newfolio="+newfolio
				 +"&fecha="+fecha+"&tipoflete="+tipoflete+"&ocurre="+ocurre+"&tipoguia="+tipoguia
				 +"&idsucursalorigen="+idsucursalorigen+"&iddestino="+iddestino+"&idsucursaldestino="+idsucursaldestino
				 +"&tipopago="+tipopago+"&idremitente="+idremitente+"&iddireccionremitente="+iddireccionremitente
				 +"&iddestinatario="+iddestinatario+"&iddirecciondestinatario="+iddirecciondestinatario+"&entregaocurre="+entregaocurre
				 +"&entregaead="+entregaead+"&restrinccion="+restrinccion+"&totalpaquetes="+totalpaquetes+"&totalpeso="+totalpeso
				 +"&totalvolumen="+totalvolumen+"&emplaye="+emplaye+"&bolsaempaque="+bolsaempaque+"&totalbolsaempaque="+totalbolsaempaque
				 +"&avisocelular="+avisocelular+"&celular="+celular+"&valordeclarado="+valordeclarado+"&acuserecibo="+acuserecibo
				 +"&cod="+cod+"&recoleccion="+recoleccion+"&observaciones="+observaciones+"&tflete="+tflete+"&tdescuento="+tdescuento
				 +"&ttotaldescuento="+ttotaldescuento+"&tcostoead="+tcostoead+"&trecoleccion="+trecoleccion+"&tseguro="+tseguro
				 +"&totros="+totros+"&texcedente="+texcedente+"&tcombustible="+tcombustible+"&subtotal="+subtotal+"&tiva="+tiva
				 +"&ivaretenido="+ivaretenido+"&total="+total+"&efectivo="+efectivo+"&cheque="+cheque+"&banco="+banco
				 +"&ncheque="+ncheque+"&tarjeta="+tarjeta+"&trasferencia="+trasferencia+"&ran="+Math.random());
	}
	function guiaGuardada(datos){
		var guardado= datos.getElementsByTagName('guardado').item(0).firstChild.data;
		if(guardado==1){
			confirmar("La guia ha sido Guardada ¿Desea limpiar los datos?","¡Atencion!","limpiar_evaluacion();limpiar_remitente();limpiar_destinatario();","");
			document.all.guiaguardadav.value = 1;
			document.all.estado.innerHTML = "GUARDADO";
		}else{
			cons = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al guardar //" + cons,"¡Atencion!","idremitente");
		}
			document.all.idsguardar.innerHTML = botonesdesguardar;
	}
	
	//validacion 
	function ejecutarSubmit(){
			confirmar("¿Desea guardar la Guia?","¡Atencion!","preguntaFactura()","");
	}
	function preguntaFactura(){
		confirmar("¿Desea Facturar?","¡Atencion!","","registrarGuia()");
	}
	
	//funciones para cancelar
	function mostrarGuiasPendientesCancelar(){
		abrirVentanaFija('../buscadores_generales/buscarGuiasEmpresarialesGen.php?funcion=solicitarGuia&estado=AUTORIZACION PARA CANCELAR', 650, 450, 'ventana', 'Guias pendientes para cancelacion')
	}
	function buscarUnaGuia(folioguia){
		solicitarGuia(folioguia);
	}
	function solicitarGuia(folio){
		consulta("respuestaGuia","guiasempresariales_consulta.php?accion=5&folio="+folio+"&rand="+Math.random());
	}
	function respuestaGuia(datos){
		u = document.all;
		limpiar_evaluacion();
		limpiar_remitente();
		limpiar_destinatario();
		bloquear(true);
		u.guiaguardadav.value = "1";
		
		var encon = datos.getElementsByTagName('encontrados').item(0).firstChild.data;
		if(encon>0){
			var id						= datos.getElementsByTagName('id').item(0).firstChild.data;
			var evaluacion				= datos.getElementsByTagName('evaluacion').item(0).firstChild.data;
			var fecha					= datos.getElementsByTagName('fecha').item(0).firstChild.data;
			var fechaactual				= datos.getElementsByTagName('fechaactual').item(0).firstChild.data;
			var fechaentrega			= datos.getElementsByTagName('fechaentrega').item(0).firstChild.data;
			var factura					= datos.getElementsByTagName('factura').item(0).firstChild.data;
			var estado					= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var tipoflete				= datos.getElementsByTagName('tipoflete').item(0).firstChild.data;
			var tipopago				= datos.getElementsByTagName('tipopago').item(0).firstChild.data;
			var tipoguia				= datos.getElementsByTagName('tipoguia').item(0).firstChild.data;
			var ocurre					= datos.getElementsByTagName('ocurre').item(0).firstChild.data;
			var idsucursalorigen		= datos.getElementsByTagName('idsucursalorigen').item(0).firstChild.data;
			var ndestino				= datos.getElementsByTagName('ndestino').item(0).firstChild.data;
			var nsucdestino				= datos.getElementsByTagName('nsucdestino').item(0).firstChild.data;
			
			var idremitente				= datos.getElementsByTagName('idremitente').item(0).firstChild.data;
			var rncliente				= datos.getElementsByTagName('rncliente').item(0).firstChild.data;
			var rrfc					= datos.getElementsByTagName('rrfc').item(0).firstChild.data;
			var rcelular				= datos.getElementsByTagName('rcelular').item(0).firstChild.data;
			var rcalle					= datos.getElementsByTagName('rcalle').item(0).firstChild.data;
			var rnumero					= datos.getElementsByTagName('rnumero').item(0).firstChild.data;
			var rcp						= datos.getElementsByTagName('rcp').item(0).firstChild.data;
			var rpoblacion				= datos.getElementsByTagName('rpoblacion').item(0).firstChild.data;
			var rtelefono				= datos.getElementsByTagName('rtelefono').item(0).firstChild.data;
			var rcolonia				= datos.getElementsByTagName('rcolonia').item(0).firstChild.data;
			
			var iddestinatario			= datos.getElementsByTagName('iddestinatario').item(0).firstChild.data;
			var dncliente				= datos.getElementsByTagName('dncliente').item(0).firstChild.data;
			var drfc					= datos.getElementsByTagName('drfc').item(0).firstChild.data;
			var dcelular				= datos.getElementsByTagName('dcelular').item(0).firstChild.data;
			var dcalle					= datos.getElementsByTagName('dcalle').item(0).firstChild.data;
			var dnumero					= datos.getElementsByTagName('dnumero').item(0).firstChild.data;
			var dcp						= datos.getElementsByTagName('dcp').item(0).firstChild.data;
			var dpoblacion				= datos.getElementsByTagName('dpoblacion').item(0).firstChild.data;
			var dtelefono				= datos.getElementsByTagName('dtelefono').item(0).firstChild.data;
			var dcolonia				= datos.getElementsByTagName('dcolonia').item(0).firstChild.data;
			
			var entregaocurre			= datos.getElementsByTagName('entregaocurre').item(0).firstChild.data;
			var entregaead				= datos.getElementsByTagName('entregaead').item(0).firstChild.data;
			var restrinccion			= datos.getElementsByTagName('restrinccion').item(0).firstChild.data;
			var totalpaquetes			= datos.getElementsByTagName('totalpaquetes').item(0).firstChild.data;
			var totalpeso				= datos.getElementsByTagName('totalpeso').item(0).firstChild.data;
			var totalvolumen			= datos.getElementsByTagName('totalvolumen').item(0).firstChild.data;
			var emplaye					= datos.getElementsByTagName('emplaye').item(0).firstChild.data;
			var bolsaempaque			= datos.getElementsByTagName('bolsaempaque').item(0).firstChild.data;
			var totalbolsaempaque		= datos.getElementsByTagName('totalbolsaempaque').item(0).firstChild.data;
			var avisocelular			= datos.getElementsByTagName('avisocelular').item(0).firstChild.data;
			var celular					= datos.getElementsByTagName('celular').item(0).firstChild.data;
			var valordeclarado			= datos.getElementsByTagName('valordeclarado').item(0).firstChild.data;
			var acuserecibo				= datos.getElementsByTagName('acuserecibo').item(0).firstChild.data;
			var cod						= datos.getElementsByTagName('cod').item(0).firstChild.data;
			var recoleccion				= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;
			var observaciones			= datos.getElementsByTagName('observaciones').item(0).firstChild.data;
			var tflete					= datos.getElementsByTagName('tflete').item(0).firstChild.data;
			var tdescuento				= datos.getElementsByTagName('tdescuento').item(0).firstChild.data;
			var ttotaldescuento			= datos.getElementsByTagName('ttotaldescuento').item(0).firstChild.data;
			var tcostoead				= datos.getElementsByTagName('tcostoead').item(0).firstChild.data;
			var trecoleccion			= datos.getElementsByTagName('trecoleccion').item(0).firstChild.data;
			var tseguro					= datos.getElementsByTagName('tseguro').item(0).firstChild.data;
			var totros					= datos.getElementsByTagName('totros').item(0).firstChild.data;
			var texcedente				= datos.getElementsByTagName('texcedente').item(0).firstChild.data;
			var tcombustible			= datos.getElementsByTagName('tcombustible').item(0).firstChild.data;
			var subtotal				= datos.getElementsByTagName('subtotal').item(0).firstChild.data;
			var tiva					= datos.getElementsByTagName('tiva').item(0).firstChild.data;
			var ivaretenido				= datos.getElementsByTagName('ivaretenido').item(0).firstChild.data;
			var total					= datos.getElementsByTagName('total').item(0).firstChild.data;
			var efectivo				= datos.getElementsByTagName('efectivo').item(0).firstChild.data;
			var cheque					= datos.getElementsByTagName('cheque').item(0).firstChild.data;
			var banco					= datos.getElementsByTagName('banco').item(0).firstChild.data;
			var ncheque					= datos.getElementsByTagName('ncheque').item(0).firstChild.data;
			var tarjeta					= datos.getElementsByTagName('tarjeta').item(0).firstChild.data;
			var trasferencia			= datos.getElementsByTagName('trasferencia').item(0).firstChild.data;
			var importe_tipo			= datos.getElementsByTagName('tipototales').item(0).firstChild.data;
			
			parent.frames[4].document.all.folioSeleccionado.innerHTML = id;
			u.folioguiaempresarial.value= id;
			u.fecha.value				= fecha;
			u.estado.innerHTML			= estado;
			u.tipoflete.value			= tipoflete;
			u.idsucursalorigen.value	= idsucursalorigen;
			if(ocurre==1)
				u.chocurre.checked		= true;
			else
				u.chocurre.checked		= false;
			
			u.destino.value			= ndestino;
			u.sucdestino.value	= nsucdestino;
			
			u.tipopago.value	= tipopago;
			u.tipoguia.value	= tipoguia;
			u.idremitente.value	= idremitente;
			u.rem_rfc.value	= rrfc;
			u.rem_cliente.value	= rncliente;
			u.rem_calle.value	= rcalle;
			u.rem_numero.value	= rnumero;
			u.rem_cp.value	= rcp;
			u.rem_colonia.value	= rcolonia;
			u.rem_poblacion.value	= rpoblacion;
			u.rem_telefono.value	= rtelefono;
			u.des_rfc.value	= drfc;
			u.iddestinatario.value	= iddestinatario;
			u.des_cliente.value	= dncliente;
			u.des_calle.value	= dcalle;
			u.des_numero.value	= dnumero;
			u.des_cp.value	= dcp;
			u.des_colonia.value	= dcolonia;
			u.des_poblacion.value	= dpoblacion;
			u.des_telefono.value	= dtelefono;
			
			u.txtocu.value = entregaocurre;
			u.txtead.value = entregaead;
			u.txtrestrinccion.value = restrinccion;
			
			u.totalpeso.value = totalpeso;
			u.totalpaquetes.value = totalpaquetes;
			u.totalvolumen.value = totalvolumen;
			
			u.txtemplaye.value = (emplaye=="0")?"":"$ "+numcredvar(emplaye);
			u.txtacuserecibo.value = (acuserecibo=="0")?"":"$ "+numcredvar(acuserecibo);
			u.txtbolsaempaque1.value = (bolsaempaque=="0")?"":"$ "+numcredvar(bolsaempaque);
			u.txtbolsaempaque2.value = (totalbolsaempaque=="0")?"":"$ "+numcredvar(totalbolsaempaque);
			u.txtcod.value = (cod=="0")?"":"$ "+numcredvar(cod);
			u.txtavisocelular1.value = (avisocelular=="0")?"":"$ "+numcredvar(avisocelular);
			u.txtavisocelular2.value = celular;
			u.txtrecoleccion.value = (recoleccion=="0")?"":"$ "+numcredvar(recoleccion);
			u.txtdeclarado.value = (valordeclarado=="0")?"":"$ "+numcredvar(valordeclarado);
			
			u.flete.value = "$ "+numcredvar(tflete);
			u.t_txtdescuento1.value = tdescuento+" %";
			u.t_txtdescuento2.value = "$ "+numcredvar(ttotaldescuento);
			u.t_txtead.value = "$ "+numcredvar(tcostoead);
			u.t_txtrecoleccion.value = "$ "+numcredvar(trecoleccion);
			u.t_txtseguro.value = "$ "+numcredvar(tseguro);
			u.t_txtotros.value = "$ "+numcredvar(totros);
			u.t_txttotal.value = "$ "+numcredvar(total);
			u.txtobservaciones.value = observaciones;
			
			u.t_txtexcedente.value = "$ "+numcredvar(texcedente);
			u.t_txtcombustible.value = "$ "+numcredvar(tcombustible);
			u.t_txtsubtotal.value = "$ "+numcredvar(subtotal);
			u.t_txtiva.value = "$ "+numcredvar(tiva);
			u.t_txtivaretenido.value = "$ "+numcredvar(ivaretenido);
			
			var enconeva = datos.getElementsByTagName('encontroevaluacion').item(0).firstChild.data;
			if(enconeva>0){
				tpaquetes	= 0;
				tpeso		= 0;
				tvolumen	= 0;
				timporte	= 0;
				for(m=0;m<enconeva;m++){	
					idmercancia	= datos.getElementsByTagName('idmercancia').item(m).firstChild.data;
					cantidad	= datos.getElementsByTagName('cantidad').item(m).firstChild.data;
					descripcion	= datos.getElementsByTagName('descripcion').item(m).firstChild.data;
					contenido	= datos.getElementsByTagName('contenido').item(m).firstChild.data;
					peso		= datos.getElementsByTagName('peso').item(m).firstChild.data;
					alto		= datos.getElementsByTagName('alto').item(m).firstChild.data;
					largo		= datos.getElementsByTagName('largo').item(m).firstChild.data;
					ancho		= datos.getElementsByTagName('ancho').item(m).firstChild.data;
					volumen		= datos.getElementsByTagName('volumen').item(m).firstChild.data;
					importe		= datos.getElementsByTagName('importe').item(m).firstChild.data;
					
					var objetox = new Object();
					objetox.idmercancia	= idmercancia;
					objetox.cantidad 	= cantidad;
					objetox.descripcion = descripcion;
					objetox.contenido 	= contenido;
					objetox.peso 		= peso;
					objetox.alto 		= alto;
					objetox.largo 		= largo;
					objetox.ancho 		= ancho;
					objetox.volumen 	= volumen;
					objetox.importe 	= importe;
					objetox.modificable = " ";
					tabla1.add(objetox);
					
					tpaquetes	+= parseFloat(cantidad);
					tpeso		+= parseFloat(peso);
					tvolumen	+= parseFloat(volumen);
					timporte	+= parseFloat(importe);
				}				
			}
			if(estado=='CANCELADO'){
				document.all.idsguardar.innerHTML = botonnuevo;
			}else{
				document.all.idsguardar.innerHTML = botonesconsulta;
			}
		}else{
			alerta("No se encontro la guia buscada", "¡Atencion!","fecha");
		}
	}
	function cancelarGuia(){
		u = document.all;
		if(u.estado.innerHTML=='AUTORIZACION PARA CANCELAR'){
			abrirVentanaFija('cancelarfinal.php?folioguia='+parent.frames[4].document.all.folioSeleccionado.innerHTML, 450, 250, 'ventana', 'Motivo de Cancelacion');
		}else{
			if(u.fechaactual.value != u.fecha.value){
				alerta("Imposible cancelar Guias con fecha posterior a la fecha de Emisión","¡Atención!","fecha");
			}else if(u.fechaactual.value == u.fecha.value){
				//confirmar("Realmente desea cancelar la guia","¡Atención!","","");
				abrirVentanaFija('motivoscancelacion.php', 400, 220, 'ventana', 'Motivos de Cancelación')
			}
		}
	}
	function mensajeCancelarFinal(){
		confirmar("¿Desea cancelar la guia?","¡Atencion!","cancelarFinal()","");
	}
	function preguntarSiCancelar(){
		confirmar("¿Seguro desea enviar la guia a Pendientes por Cancelar?","¡Atencion!","guardarCancelacion()","");
	}
	function guardarCancelacion(){
		consulta("respuestaCancelacion","guia_consulta.php?accion=6&folio="+parent.frames[4].document.all.folioSeleccionado.innerHTML
		+"&motivo="+document.all.motivocancelacion.value+"&rand="+Math.random());
	}
	function respuestaCancelacion(){
		document.all.estado.innerHTML = 'AUTORIZACION PARA CANCELAR';
		alerta("La guia ha sido enviada a pendientes por cancelar", "¡Atencion!", "fecha");
	}
	function cancelarFinal(){
		consulta("respuestaCancelarFinal","guia_consulta.php?accion=7&folio="+parent.frames[4].document.all.folioSeleccionado.innerHTML
		+"&rand="+Math.random());
	}
	function respuestaCancelarFinal(datos){
		document.all.estado.innerHTML = 'CANCELADO';
		alerta("La guia se ha Cancelado", "¡Atencion!", "fecha");
		document.all.idsguardar.innerHTML = botonnuevo;
	}
	
	function permitirDescuento(){
		u = document.all;
		u.t_txtdescuento1.readOnly = false;
		u.t_txtdescuento1.style.backgroundColor = "#FFFFFF";
		u.t_txtdescuento1.focus();
		u.t_txtdescuento1.select();
		u.t_txtdescuento1.value = u.t_txtdescuento1.value.replace(" %","");
		
	}
	function validarDescuento(){		
		u = document.all;
		if(( (parseFloat(u.totalpeso.value)>parseFloat(u.totalvolumen.value))?parseFloat(u.totalpeso.value):parseFloat(u.totalvolumen.value))<=parseFloat(u.pc_pesominimodesc.value)){
			alerta("Para aplicar descuento, el peso total debe ser mayor a "+u.pc_pesominimodesc.value+" kg","¡Atencion!","flete");
			return false;
		}else if(u.flete.value=="" || u.flete.value=="$ 0.00"){
			alerta("No puede aplicar descuento si el flete no se ha calculado el flete","¡Atencion!","flete");
			return false;
		}else{
			return true;
		}
	}
	function calcularDescuento(){
		var u = document.all;
		if(parseFloat(u.t_txtdescuento1.value)==0 || u.t_txtdescuento1.value==""){
			u.t_txtdescuento1.value	= "";
			u.t_txtdescuento2.value = "";
		}else{
			var flete_pd			= parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""));
			u.t_txtdescuento2.value = "$ "+numcredvar((flete_pd*(parseFloat(u.t_txtdescuento1.value)/100)).toLocaleString());
			u.t_txtdescuento1.value = u.t_txtdescuento1.value + " %";	
		}
		u.t_txtdescuento1.readOnly = true;
		u.t_txtdescuento1.style.backgroundColor = "#FFFF99";
		calculartotales();
	}
	function validarDatos(){
		var u = document.all;
		if(u.guiaguardadav.value=="1"){
			alerta('Esta guia ya ha sido guardada','¡Atención!',"iddestinatario");
			return false;
		}else if(u.folioevaluacion.value==""){
			alerta('Debe seleccionar una guia','¡Atención!',"idremitente");
			return false;
		}else if(u.idremitente.value==""){
			alerta('Debe capturar el remitente','¡Atención!',"idremitente");
			return false;
		}else if(u.iddestinatario.value==""){
			alerta('Debe capturar el destinatario','¡Atención!',"iddestinatario");
			return false;
		}else if(u.des_poblacion.value != u.npobdes.value){
			alerta("Direccion del destinatario no concuerda con el destino, debe corregir dirección, destino, o enviar ocure","¡Alerta!","iddestinatario");	
		}
		return true;
	}
	
	//funciones para obtener el emplaye
	function ObtenerPrecioEmplaye(){ 
		consulta("ObtenerCostoEmplaye","../evaluacion/evaluacionmercanciaresult.php?accion=2&id=2");
	}
	function ObtenerCostoEmplaye(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;		
		if(con>0){
			var costoemplaye 		= datos.getElementsByTagName('costo').item(0).firstChild.data;	
			var costoextra			= datos.getElementsByTagName('costoextra').item(0).firstChild.data;
			var limite				= datos.getElementsByTagName('limite').item(0).firstChild.data;
			var porcada				= datos.getElementsByTagName('porcada').item(0).firstChild.data;
			
			if(parseFloat(u.totalpeso.value) > parseFloat(u.totalvolumen.value)){
				if(parseFloat(u.totalpeso.value) <= parseFloat(limite)){
					u.txtemplaye.value=u.costoemplaye.value;
				}else{
					var kgextra=parseFloat(u.totalpeso.value) - parseFloat(limite);
					u.txtemplaye.value=parseFloat(costoemplaye) + parseFloat(((kgextra / parseFloat(porcada)) * parseFloat(costoextra)));
					}
					if(u.txtemplaye.value=='NaN'){
						u.txtemplaye.value="";
					}			
				}else{ 
					if(parseFloat(u.totalvolumen.value)<=parseFloat(limite)){
						u.txtemplaye.value=parseFloat(costoemplaye);
					}else{
					var kgextra=parseFloat(u.totalvolumen.value)-parseFloat(limite);
						u.txtemplaye.value=parseFloat(costoemplaye) + parseFloat(((kgextra / parseFloat(porcada)) * parseFloat(costoextra)));
					}
					if(u.txtemplaye.value=='NaN'){
						u.txtemplaye.value="";
					}
				}	
				u.txtemplaye.value = "$ "+numcredvar(u.txtemplaye.value);
		}else{
			alerta3("El Servicio no esta configurado",'Atención!');
		}
	}
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
    <td align="center">
    
 <table width="609" border="0" cellpadding="0" cellspacing="0">
 <tr>
 	<td height="24" colspan="4" align="left">
    <table width="604" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td width="40" align="left">Estado</td>
        	<td width="512" id="estado" style="font:tahoma; font-size:15px; font-weight:bold" align="left"></td>
        	<td width="36"><a onClick="limpiar_cajas()" href="../menu/webministator.php" ><img src="../img/inicio_30.gif" name="IMG0"  border="0"  id="IMG0" /></a></td>
        </tr>
    </table>    </td>
</tr>
<tr>
        <td colspan="4" align="left">
        <?
			$s = "select date_format(current_date, '%d/%m/%Y') as fecha";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
		?>
        <table width="597" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="168">Folio
            <input name="folioguiaempresarial" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; width:110px" value="<?=$folio ?>"/></td>
                <td width="74">Tipo de Guia</td>
                <td width="176"><input name="tipoguia" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; width:150px" value="<?=$guia ?>" /></td>
                <td width="55">Fecha</td>
                <td width="124"><input name="fecha" type="text" id="fecha" style="background:#FFFF99;font:tahoma; font-size:9px; width:90px" value="<?=$f->fecha ?>"/></td>
</tr>
        </table>
        <input type="hidden" name="folioevaluacion" value="">
          <input type="hidden" name="guiaguardadav" value="">
          <input type="hidden" name="idsucursalorigen" value="">
          <input type="hidden" name="fechaactual" value="">
          <input type="hidden" name="restringiread" value="">        </td>
</tr>
<tr>
        <td colspan="2" align="left"></td>
        <td width="153" align="right"></td>
        <td width="137" align="right"></td>
      </tr>
      <tr>
        <td colspan="4"><table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="41"  class="Tablas">T. Flete:</td>
            <td width="50"><input type="text" name="tipoflete" readonly="true" style="background:#FFFF99;width:50px; font-size:9px"/></td>
            <td width="14" ><input name="chocurre" onclick="solicitarDatosConv(); if(document.all.restringiread.value==1){alerta('El destino tiene restringida la Entrega a Domicilio','&iexcl;Atencion!','chocurre'); this.checked=true;}else{if(this.checked==false){document.all.t_txtead.value = document.all.t_txteadh.value}else{document.all.t_txtead.value = '$ 0.00';}} calculartotales();" type="checkbox" id="chocurre" style="width:8px; height:8px" value="SI" /></td>
            <td width="54" ><span class="Tablas">Ocurre </span></td>
            <td width="40" class="Tablas">Destino:</td>
            <td width="105"><input type="text" name="destino" readonly="true" id="destino" style="background:#FFFF99;width:80px; font-size:9px" />
             
<input type="hidden" name="destino_hidden" />
                <input type="hidden" name="npobdes" /></td>
            <td width="63" ><span class="Tablas">Suc. Destino:</span></td>
            <td width="94" ><input name="sucdestino" type="text" id="sucdestino" style="background:#FFFF99;font:tahoma; font-size:9px; width:70px" readonly="readonly" value="<?=$destino?>" poblacion="" />
                <input type="hidden" name="sucdestino_hidden" /></td>
            <td width="63" valign="middle" ><span class="Tablas">Cond. Pago:</span></td>
            <td width="70" valign="middle">&nbsp;
                <input type="text" name="tipopago" readonly="readonly" style="background:#FFFF99; width:70px; font-size:9px" ></td>
            <td width="15" >&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align="left"><table width="610" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">
          <tr>
            <td width="316" class="FondoTabla">Remitente</td>
            <td width="288" class="FondoTabla">Destinatario</td>
          </tr>
          <tr>
            <td><table width="96%" border="0" cellpadding="0" cellspacing="1">
                <tr>
                  <td width="16%"><span class="Tablas"># Cliente: </span></td>
                  <td><input name="idremitente" readonly="readonly" type="text" onkeypress="if(event.keyCode==13 &amp;&amp; this.readOnly==false){ devolverRemitente(this.value)}" style="width:30px; font:tahoma; font-size:9px; background:#FFFF99;" value="<?=$remitente ?>" />
                    &nbsp;&nbsp;
                    <input type="hidden" name="rem_personamoral" /></td>
                  <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>
                      <input name="rem_rfc" readonly="true" type="text" style="width:70px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" /></td>
                </tr>
                <tr>
                  <td><span class="Tablas">Cliente:</span></td>
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="200"><input name="rem_cliente" readonly="true" type="text" style="width:200px; background:#FFFF99;font:tahoma; font-size:9px" 
                    value="<?=$rcliente ?>" /></td>
                        <td width="52" align="right" valign="middle">&nbsp;</td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><span class="Tablas">Calle:</span></td>
                  <td colspan="4"><table width="262" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="153" height="16" id="celda_rem_calle"><input name="rem_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; width:130px;" value="" />
                            <input type="hidden" name="rem_direcciones" /></td>
                        <td width="117"><span class="Tablas">Numero: </span><span class="Tablas">
                          <input name="rem_numero" type="text" readonly="true" style=" width:40px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />
                        </span></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><span class="Tablas">CP:</span></td>
                  <td width="29%"><input name="rem_cp" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; width:65px;" value="<?=$rcp ?>"/></td>
                  <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;
                        <input name="rem_colonia" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:85px;" value="<?=$rcolonia ?>" />
                  </span></td>
                </tr>
                <tr>
                  <td><span class="Tablas">Poblaci&oacute;n:</span></td>
                  <td colspan="4"><input name="rem_poblacion" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px;width:90px;" value="<?=$rpoblacion ?>" />
                      <span class="Tablas">&nbsp;Tel&eacute;fono:
                        <input name="rem_telefono" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:60px;" value="<?=$rtelefono ?>" />
                    </span></td>
                </tr>
            </table></td>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
                <tr>
                  <td><input name="iddestinatario" onkeypress="if(event.keyCode==13 &amp;&amp; this.readOnly==false){devolverDestinatario(this.value)}" type="text" style="font:tahoma; font-size:9px; width:30px;" value="<?=$remitente ?>" />
                    &nbsp;&nbsp;<img id="b_destinatario" src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverDestinatario', 625, 418, 'ventana', 'Busqueda')"/>
                    <input type="hidden" name="des_personamoral" />
                    <input type="hidden" name="paraconvenio" value="0" /></td>
                  <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>
                      <input name="des_rfc" type="text" readonly="true" id="rrfc22" style="width:70px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" /></td>
                </tr>
                <tr>
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td><input name="des_cliente" readonly="true" type="text" 
                  style="background:#FFFF99;font:tahoma; font-size:9px;width:200px;" value="<?=$rcliente ?>" /></td>
                        <td align="right" valign="middle"><img id="b_destinatario_dir" src="../img/Boton_Agregarchico.gif" alt="Agregar Direcci&oacute;n" style="cursor:hand" onclick="if(document.all.iddestinatario.value==''){ alerta('Proporcione el id del remitente','&iexcl;Atencion!','iddestinatario') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverDestinatario('+document.all.iddestinatario.value+')&amp;idcliente='+document.all.iddestinatario.value, 460, 395, 'ventana', 'DATOS DIRECCION')}" /></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="153" height="16" id="celda_des_calle"><input name="des_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; width:130px;" value="" />
                            <input type="hidden" name="des_direcciones" /></td>
                        <td width="119"><span class="Tablas">Numero: </span><span class="Tablas">
                          <input name="des_numero" type="text" readonly="true" style=" width:40px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />
                        </span></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td width="29%"><input name="des_cp" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:65px;" value="<?=$rcp ?>" /></td>
                  <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;
                        <input name="des_colonia" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:85px;" value="<?=$rcolonia ?>" />
                  </span></td>
                </tr>
                <tr>
                  <td colspan="4"><input name="des_poblacion" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px;width:90px;" value="<?=$rpoblacion ?>"/>
                      <span class="Tablas">&nbsp;Tel&eacute;fono:
                        <input name="des_telefono" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:60px" value="<?=$rtelefono ?>" />
                    </span></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4" id="paraconsultas">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">
        <table border="0" cellpadding="0" cellspacing="0" id="tablaconteva"></table>        </td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align="center"><table width="488" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="92" class="Tablas">T. Paquetes: </td>
            <td width="76" class="Tablas"><input name="totalpaquetes" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:40px" value="<?=$rcp ?>"/></td>
            <td width="81" class="Tablas">T. Peso Kg: </td>
            <td width="80" class="Tablas"><input name="totalpeso" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:40px" value="<?=$rcp ?>" /></td>
            <td width="85" class="Tablas">T. Volumen: </td>
            <td width="74" class="Tablas"><input name="totalvolumen" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:60px" value="<?=$rcp ?>" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
<tr>
        <td align="left"><table width="177" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">
          <tr>
            <td width="172" class="FondoTabla">Tiempo de Entrega </td>
          </tr>
          <tr>
            <td><table width="163" height="0" align="center" bordercolor="#016193">
                <tr>
                  <td width="41" class="Tablas">Ocurre:</td>
                  <td width="40"><input name="txtocu" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$drfc ?>" size="5" /></td>
                  <td width="28" class="Tablas">EAD:</td>
                  <td width="34"><input name="txtead" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$drfc ?>" size="5" />
                      <input name="txteadh" type="hidden" /></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
        <td colspan="2" align="left"><table width="176" height="0" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">
          <tr>
            <td width="200" class="FondoTabla">Restricciones</td>
          </tr>
          <tr>
            <td><label>
                <textarea name="txtrestrinccion" readonly="readonly" style="width:170px; font-size:9px; background-color:#FFFF99; text-transform:uppercase"></textarea>
                <input name="txtrestrinccionh" type="hidden" />
            </label></td>
          </tr>
        </table></td>
</tr>
<tr>
        <td width="317" class="FondoTabla Estilo4">Servicios</td>
        <td width="3">&nbsp;</td>
        <td colspan="2" class="FondoTabla Estilo4">Totales
         <input type="hidden" name="pc_ead">
        <input type="hidden" name="pc_recoleccion">
        <input type="hidden" name="pc_porcada">
        <input type="hidden" name="pc_costo">
        <input type="hidden" name="pc_tarifacombustible">
        <input type="hidden" name="pc_iva">
        <input type="hidden" name="pc_ivaretenido">
        <input type="hidden" name="pc_maximodescuento">
        <input type="hidden" name="pc_pesominimodesc">
        
        <input type="hidden" name="desead">
        <input type="hidden" name="desrrecoleccion">
        <input type="hidden" name="desporcobrar">
        <input type="hidden" name="desconvenio">        </td>
    </tr>
      <tr>
        <td align="left" valign="top"><table width="99%" height="76" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="6%"><input name="chkemplaye" type="checkbox" style="width:8px; height:8px" value="SI" onclick="if(!this.checked){document.all.txtemplaye.value='';}else{ObtenerPrecioEmplaye(); /*document.all.txtemplaye.value = document.all.txtemplayeh.value*/} calculartotales();" /></td>
              <td class="Tablas">Emplaye
                <input name="txtemplaye" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />
                  <input name="txtemplayeh" type="hidden" /></td>
              <td class="Tablas"><input name="chkacuserecibo" onclick="if(!this.checked){document.all.txtacuserecibo.value='';}else{document.all.txtacuserecibo.value=document.all.txtacusereciboh.value;} calculartotales();" type="checkbox" style="width:8px; height:9px" value="SI" /></td>
              <td class="Tablas">Acuse Recibo</td>
              <td class="Tablas" align="right"><input readonly="true" name="txtacuserecibo" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
                  <input name="txtacusereciboh" type="hidden" /></td>
            </tr>
            <tr>
              <td><input name="chkbolsaempaque" type="checkbox" style="width:8px; height:8px" value="SI" onclick="if(!this.checked){document.all.txtbolsaempaque1.value = ''; document.all.txtbolsaempaque2.value = ''; document.all.txtbolsaempaque1.readOnly=true; document.all.txtbolsaempaque1.style.backgroundColor='#FFFF99';}else{ if(document.all.txtbolsaempaque1h.value=='' || document.all.txtbolsaempaque1h.value=='0'){document.all.txtbolsaempaque1.readOnly=false; document.all.txtbolsaempaque1.style.backgroundColor='#FFFFFF';}else{document.all.txtbolsaempaque1.value = document.all.txtbolsaempaque1h.value; document.all.txtbolsaempaque2.value = document.all.txtbolsaempaque2h.value;}} calculartotales();" /></td>
              <td width="52%" class="Tablas">Bolsa Empaque
                <input name="txtbolsaempaque1" readonly="true" onblur="if(this.readOnly==false){calculartotales();}" onkeypress="if(this.readOnly==false &amp;&amp; event.keyCode==13){document.all.txtbolsaempaque2.value='$ '+numcredvar((parseFloat((document.all.txtbolsaempaque3h.value=='')?'0':document.all.txtbolsaempaque3h.value.replace('$ ', '').replace(/,/g,''))*parseFloat(this.value)).toLocaleString());calculartotales();}else{return solonumeros(event);}" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="1" />
                  <input name="txtbolsaempaque2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="4" />
                  <input name="txtbolsaempaque1h" type="hidden" />
                <input name="txtbolsaempaque2h" type="hidden" />
                <input name="txtbolsaempaque3h" type="hidden" /></td>
              <td width="5%" class="Tablas"><input name="chkcod" onclick="if(!this.checked){document.all.txtcod.value='';}else{document.all.txtcod.value=document.all.txtcodh.value;} calculartotales();" type="checkbox" style="width:8px; height:8px" value="SI" /></td>
              <td width="22%" class="Tablas">COD</td>
              <td width="15%" class="Tablas" align="right"><input readonly="true" name="txtcod" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
                  <input name="txtcodh" type="hidden" /></td>
            </tr>
            <tr>
              <td><input name="chkavisocelular" type="checkbox" style="width:8px; height:8px" value="SI" onclick="if(!this.checked){document.all.txtavisocelular2.readOnly=true; document.all.txtavisocelular2.style.backgroundColor='#FFFF99'; document.all.txtavisocelular2.value='';document.all.txtavisocelular1.value=''; }else{document.all.txtavisocelular1.value=document.all.txtavisocelular1h.value;document.all.txtavisocelular2.readOnly=false; document.all.txtavisocelular2.style.backgroundColor='#FFFFFF'; document.all.txtavisocelular2.value=document.all.txtavisocelular2h.value;document.all.txtavisocelular2.focus();}  calculartotales();" /></td>
              <td colspan="4" class="Tablas">Aviso Celular
                <input name="txtavisocelular1" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />
                  <input name="txtavisocelular1h" type="hidden" />
                  <input name="txtavisocelular2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="10" />
                <input name="txtavisocelular2h" type="hidden" /></td>
            </tr>
            <tr>
              <td><input name="chkvalordeclarado" type="checkbox" style="width:8px; height:8px" value="SI"
               onclick="if(!this.checked){document.all.txtdeclarado.value='';document.all.txtdeclarado.readOnly=true; document.all.txtdeclarado.style.backgroundColor='#FFFF99'; document.all.txtdeclarado.readOnly=true;}else{document.all.txtdeclarado.readOnly=false; document.all.txtdeclarado.style.backgroundColor='#FFFFFF'; document.all.txtdeclarado.readOnly=false;document.all.txtdeclarado.focus();} calculartotales();" /></td>
              <td class="Tablas">Valor Declarado
                <?
			  	$s = "SELECT maxvalordeclaradoguia FROM configuradorgeneral";
				$rmvd = mysql_query($s,$l) or die($s);
				$fmvd = mysql_fetch_object($rmvd);
			  ?>
                  <input name="txtdeclarado" type="text" readonly="true" onblur="if(this.readOnly==false){this.value=this.value.replace('$ ','').replace(/,/,'');  if(this.value==''){this.value='$ 0.00';}else{ if(parseFloat(this.value) &gt; <?=$fmvd->maxvalordeclaradoguia?>){this.value = <?=$fmvd->maxvalordeclaradoguia?>; alerta3('El maximo valor declarado permitido es <?=$fmvd->maxvalordeclaradoguia?>', '&iexcl;Atencion!');} this.value='$ '+numcredvar(this.value); calculartotales(); }}" onkeypress="if(this.readOnly==false){ if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/,'')); calculartotales();}else{return solonumeros(event);}} " style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
              <td class="Tablas"><input name="chkrecoleccion" type="checkbox" onclick="if(!this.checked){document.all.txtrecoleccion.value=''; document.all.t_txtrecoleccion.value=''; }else{document.all.txtrecoleccion.value=document.all.txtrecoleccionh.value; } calculartotales();" id="chocurre24" style="width:8px; height:8px" value="SI" /></td>
              <td class="Tablas">Recolecci&oacute;n</td>
              <td class="Tablas" align="right"><input readonly="true" name="txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; text-align:right" value="<?=$rrfc ?>" size="8" />
                  <input name="txtrecoleccionh" type="hidden" /></td>
            </tr>
          </table>
          <table width="312" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">
            <tr>
              <td width="312" class="FondoTabla">Observaciones</td>
            </tr>
            <tr>
              <td><textarea name="txtobservaciones" style="width:300px; font-size:9px; font:tahoma; text-transform:uppercase"></textarea></td>
            </tr>
          </table></td>
        <td>&nbsp;</td>
        <td colspan="2" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="Tablas">&nbsp;&nbsp;Flete:</td>
            <td width="38%" class="Tablas"><input name="flete" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            <td class="Tablas">Excedente:</td>
            <td class="Tablas"><input name="t_txtexcedente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
          </tr>
          <tr>
            <td class="Tablas">&nbsp;&nbsp;Descuento:</td>
            <td class="Tablas"><input readonly="true" name="t_txtdescuento1" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; width:20px" value="<?=$rrfc ?>" onkeypress="if(event.keyCode==13 &amp;&amp; this.readOnly==false){ if(parseFloat(this.value)&gt;parseFloat(document.all.pc_maximodescuento.value)){ this.value=document.all.pc_maximodescuento.value; alerta('El maximo descuento permitido es '+document.all.pc_maximodescuento.value+' %','&iexcl;Atencion!','t_txtdescuento1')} calcularDescuento()}else{return solonumeros(event);}" />
                <input name="t_txtdescuento2" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; width:35px" value="<?=$rrfc ?>" />
                <img id="img_descuento" src="../img/update.gif" onclick="if(validarDescuento()){ abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&amp;usuario=Admin&amp;funcion=permitirDescuento', 370, 500, 'ventana', 'Inicio de Sesi&oacute;n Secundaria');}" style="cursor:hand" /></td>
            <td class="Tablas">Combustible:</td>
            <td class="Tablas"><input name="t_txtcombustible" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
          </tr>
          <tr>
            <td class="Tablas">&nbsp;&nbsp;EAD:</td>
            <td class="Tablas"><input readonly="true" name="t_txtead" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" />
                <input name="t_txteadh" type="hidden" />
              <input name="t_txteadh2" type="hidden" /></td>
            <td class="Tablas">Subtotal:</td>
            <td class="Tablas"><input name="t_txtsubtotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
          </tr>
          <tr>
            <td class="Tablas">&nbsp;&nbsp;Recolecci&oacute;n:</td>
            <td class="Tablas"><input readonly="true" name="t_txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" />
                <input name="t_txtrecoleccionh" type="hidden" /></td>
            <td class="Tablas">IVA:</td>
            <td class="Tablas"><input name="t_txtiva" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
          </tr>
          <tr>
            <td width="24%" class="Tablas">&nbsp;&nbsp;Seguro:</td>
            <td class="Tablas"><input readonly="true" name="t_txtseguro" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            <td width="23%" class="Tablas">IVA Retenido: </td>
            <td width="15%" class="Tablas"><input name="t_txtivaretenido" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
          </tr>
          <tr>
            <td class="Tablas">&nbsp;&nbsp;Otros:</td>
            <td class="Tablas"><input name="t_txtotros" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            <td class="Tablas">Total:</td>
            <td class="Tablas"><input name="t_txttotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
          </tr>
          <tr>
            <td colspan="2" class="Tablas" valign="middle"><input type="hidden" value="0" name="pagoregistrado" />
                <input type="hidden" value="" name="efectivo" />
                <input type="hidden" value="" name="cheque" />
                <input type="hidden" value="" name="ncheque" />
                <input type="hidden" value="" name="banco" />
                <input type="hidden" value="" name="tarjeta" />
                <input type="hidden" value="" name="transferencia" />
                <input type="hidden" value="" name="pagominimocheque" /></td>
            <td class="Tablas"><input type="hidden" name="motivocancelacion" /></td>
            <td class="Tablas">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td colspan="4"  id="idsguardar" align="center"></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
  </table></td>
</tr>
</table>
</form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'APLICACIÓN GUÍAS EMPRESARIALES';
</script>
</html>
