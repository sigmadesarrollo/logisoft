<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Principal</title>
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
<script src="../javascript/moautocomplete.js"></script>
<!-- funciones para ajax -->
<script type="text/javascript" src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../convenio/validacionesConvenio.js"></script>
<script>
	//declaracion de tablas
	var sucursalorigen 	= 0;
	var valCon = new validacionesConvenio();
	var tabla1 = new ClaseTabla();
	var paraCambios = 0;
	var desc = new Array(<?php echo $desc; ?>);
	var mensajescambios;
	
	tabla1.setAttributes({
		nombre:"tablaconteva",
		campos:[
			{nombre:"IDM", medida:4, alineacion:"left", tipo:"oculto", datos:"idmercancia"},
			{nombre:"Cant", medida:39, alineacion:"left", datos:"cantidad"},
			{nombre:"Descripcion", medida:104, alineacion:"left", datos:"descripcion", onDblClick:"mostrarDetallePaquete"},
			{nombre:"Contenido", medida:104, alineacion:"left", datos:"contenido"},
			{nombre:"Peso", medida:39, alineacion:"right", datos:"peso"},
			{nombre:"Vol", medida:42, alineacion:"right", datos:"volumen"},
			{nombre:"Importe", medida:68, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"M", medida:14, alineacion:"right", datos:"modificable", onDblClick:"paracambiarvalor(tabla1.getSelectedRow().modificable);"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:true,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		//parent.frames[1].document.getElementById('titulo').innerHTML = 'CANCELACION DE GUIAS FORANEAS';
		paraCambios = 0;
	<?
		if($_GET[funcion]!=""){
			echo 'setTimeout("'.str_replace(chr(92), "",$_GET[funcion]).'",500);';
		}
	?>
	}
	
	function mostrarDetallePaquete(valor){
		if(tabla1.getSelectedRow()!=undefined){
			abrirVentanaFija('ModificarDetalle.php?funcion=agregarDatos&funcion=modificado&eliminar=0&iddetalle='+
			tabla1.getSelectedRow().idmercancia+"&folioguia="+document.all.folioSeleccionado.innerHTML, 400, 350, 'ventana', 'Datos Producto')
		}
	}
	
	var botonnuevo	 = '<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesnuevo = '<img src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="if(validarDatos() && valcreditodisponible()){ ejecutarSubmit(); };">&nbsp;&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesdesguardar = '<img src="../img/Boton_Imprimir.gif" onclick="imprimir()" style="cursor:hand">&nbsp;&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesconsulta = '<img src="../img/Boton_Imprimir.gif" onclick="imprimir()" style="cursor:hand">&nbsp;<img src="../img/Boton_Cancela_Guia.gif" style="cursor:hand" onClick="cancelarGuia()">&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();bloquear(false);">';
	var botonesguardar = '<img src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="if(validarDatos() && valcreditodisponible()){ ejecutarSubmit(); };">';
	
	function paracelda(valor, tamano, alineacion){
		return '<input type="text" readonly="true" style=" width:'+tamano+'px;font:tahoma; font-size:9px; text-align:'+alineacion+'; font-weight:bold; border:none;background:none" value="'+valor+'" />';
	}
	
	function imprimir(){
		window.open("imprimiretiquetaguia.php?codigo="+document.all.folioSeleccionado.innerHTML,"1","");
		window.open("imprimiretiquetapaquete.php?codigo="+document.all.folioSeleccionado.innerHTML,"2","");
	}
	
	function bloquear(valor){
		u = document.all;
		u.lstflete.disabled 		= valor;
		u.chocurre.disabled 		= valor;
		u.lstpago.disabled 			= valor;
		u.idremitente.readOnly 		= valor;
		u.iddestinatario.readOnly	= valor;
		
		u.idremitente.style.backgroundColor		= (valor)?"#FFFF99":"";
		u.iddestinatario.style.backgroundColor	= (valor)?"#FFFF99":"";
		
		u.chkemplaye.disabled 			= valor;
		u.chkbolsaempaque.disabled 	= valor;
		u.chkavisocelular.disabled 	= valor;
		u.chkvalordeclarado.disabled 	= valor;
		u.chkacuserecibo.disabled 		= valor;
		u.chkcod.disabled 				= valor;
		u.chkrecoleccion.disabled 		= valor;
		
		u.b_remitente.style.visibility = (valor)?"hidden":"visible";
		u.b_destinatario.style.visibility = (valor)?"hidden":"visible";
		u.b_remitente_dir.style.visibility = (valor)?"hidden":"visible";
		u.b_destinatario_dir.style.visibility = (valor)?"hidden":"visible";
		u.img_descuento.style.visibility = (valor)?"hidden":"visible";
		
	}
	
	
	//para cambiar descripciones convenios
	function solicitarDatosConv(){
		if(document.all.sucdestino_hidden.value!=""){
			//alert("../convenio/validaconvenio.php?accion=1&idremitente="+u.idremitente.value
			//+"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&valran="+Math.random());
			consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idremitente="+u.idremitente.value
			+"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&idsucdestino="+u.sucdestino_hidden.value
			+"&valran="+Math.random());
		}
	}
	function paraConvenio(datos){
		var u = document.all;
		valCon.setDatos(datos);
		
		if((u.idremitente.value!="" && u.lstflete.value==0) || (u.iddestinatario.value!="" && u.lstflete.value==1) || (u.idremitente.value!="" && u.iddestinatario.value != "")){
				consultaTexto('obtenerMecanciaConvenio',"guia_consulta_conv.php?accion=1&idsucdestino="+u.sucdestino_hidden.value+
							  "&fevaluacion="+u.folioevaluacion.value+'&folioguia='+document.all.folioSeleccionado.innerHTML
				   +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(u.lstflete.value)+"&rd="+Math.random());
		}
	}
	function obtenerMecanciaConvenio(datos){
		mensajescambios = new Array();
		//alert(datos);
		var u = document.all;
		u.creditodisponible.value = "";
		u.t_txtexcedente.value = "$ 0.00";
		datos = datos.replace(/&#209;/g,"Ñ");
		try{
			var objeto = eval(datos);
		}catch(e){
			alerta3(datos);
		}
		u.t_txtexcedente.value = "$ "+numcredvar(objeto[0].excedente.toLocaleString());
		
		u.txtrestrinccion.value = u.txtrestrinccionh.value;
		u.txtead.value			= u.txteadh.value;
		
		var filas = objeto[0].mercancia;
		tabla1.setJsonData(filas);
		
		var importetotal = 0;
		for(var i=0; i<filas.length; i++){
			importetotal += parseFloat(filas[i].importe);
		}
		if(valCon.validaDescuentoSobreFlete(u.lstflete.value)>0){
			var totalmasdesc = importetotal*100/(100-valCon.validaDescuentoSobreFlete());
			u.flete.value = "$ "+numcredvar(totalmasdesc.toLocaleString());
			u.t_txtdescuento1.value = valCon.validaDescuentoSobreFlete();
			u.t_txtdescuento2.value = (valCon.validaDescuentoSobreFlete()/100)*totalmasdesc;
			u.img_descuento.style.visibility="hidden";
		}else{
			u.flete.value = "$ "+numcredvar(importetotal.toLocaleString());
			u.t_txtdescuento1.value = "0 %";
			u.t_txtdescuento2.value = "$ 0.00";
			u.img_descuento.style.visibility="visible";
		}
		
		if(valCon.aplicaTarifaMinima(u.lstflete.value)>0){
			if(parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))<parseFloat(valCon.aplicaTarifaMinima(u.lstflete.value))){
				u.flete.value = "$ "+numcredvar(valCon.aplicaTarifaMinima(u.lstflete.value).toLocaleString());
			}
		}
		
		//if(u.desporcobrar.value!="1")
			//u.lstflete.disabled 		= false;
		if(u.desrrecoleccion.value!="1")
			u.t_txtrecoleccionh.value	= "0";
			
		var msg="";
		//del destinatario
		if(u.lstflete.value==1){
			if(u.desead.value!="1" && u.desconvenio.value!="1" && valCon.restringEADDestinatario()){ 
				mensajescambios[mensajescambios.length] = Array("El destinatario tiene restringido el servicio E.A.D.<br>", "chocurre.value=1;",0);
			}
			if(u.desconvenio.value!="1" && valCon.restringVDDestinatario()){
				mensajescambios[mensajescambios.length] = Array("El destinatario tiene restringido el servicio VALOR DECLARADO<br>", "chkvalordeclarado.disabled",1);
			}
			if(u.desporcobrar.value!="1" && u.desconvenio.value!="1" && valCon.restringRXCDestinatario()){
				mensajescambios[mensajescambios.length] = Array("El destinatario tiene restringido el servicio RECIBO POR COBRAR<br>", "lstflete.value=0",2);
			}
		}
		
		//de la sucursal
		if(valCon.restringirDestinoEAD()){
			mensajescambios[mensajescambios.length] = Array("El destino seleccionado tiene restringida la entrega a domicilio<br>", "chocurre.value=1;",3);			
		}
		if(valCon.checarCredito(u.lstflete.value)){
			mensajescambios[mensajescambios.length] = Array("El cliente tiene Credito, se cambiara la condicion de pago<br>", "lstpago.value=1;",4);
		}
		if(u.desconvenio.value!="1" && valCon.validaEADsucursal(valCon.validarConvenioAUsar(u.lstflete.value),u.sucdestino_hidden.value)){
			u.t_txteadh2.value = "1";
		}
		if(u.desconvenio.value!="1" && u.desconvenio.value!="1" && valCon.validaRecsucursal(valCon.validarConvenioAUsar(u.lstflete.value),u.sucdestino_hidden.value))
			u.t_txtrecoleccionh.value = "1";
		if(u.iddestinatario.value){
			if(valCon.validarDestRestEADF(((u.chocurre.value==1)?true:false))){
				mensajescambios[mensajescambios.length] = Array("El destino seleccionado no acepta EAD a personas fisicas sin convenio<br>", "chocurre.value=1;",5);
			}
		}
		//if(msg!="")
			//alerta3(msg,"¡Atencion!");
		
		var convenio 	= valCon.validarConvenioAUsar(u.lstflete.value);
		var cliconvenio = valCon.validarClienteConvenio(u.lstflete.value);
		var oridest 	= valCon.validarOrigenDestino(u.lstflete.value);
		var vendedor 	= valCon.validarVendedorConvenio(u.lstflete.value);
		
		u.convenioaplicado.value = (convenio>0)?convenio:0;
		u.clientedelconvenio.value = (cliconvenio>-1) ? cliconvenio : 0 ;
		u.sucursaldelconvenio.value = ((oridest>-1)? ((oridest==0)?<?=$_SESSION[IDSUCURSAL]?>:u.sucdestino_hidden.value) : 0 );
		u.nombrevendedor.value = (vendedor != -1) ? vendedor.split(",")[0] : "" ;
		u.idvendedor.value = (vendedor != -1) ? vendedor.split(",")[1] : "0" ;
		
		calculartotales();
		<?
		if($_GET[funcion]!=""){ ?>
			document.all.idsguardar.innerHTML = botonesguardar;
		<? }else{ ?>
			document.all.idsguardar.innerHTML = botonesnuevo;
		<? } ?>
		
		msg = "";
		for(var i=0; i<mensajescambios.length;i++){
			msg += "»"+mensajescambios[i][0];
		}
		if(msg!=""){
			confirmar("Las modificaciones exigen los siguientes cambios:<br>"+msg+"<br>¿Desea realizarlos?","¡Atencion!","pedirElLogueo()","noCambiarDatos()");
		}
	}
	
	function pedirElLogueo(){
		abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=cambiarDatos', 370, 500, 'ventana', 'Inicio de Sesión Secundaria');
	}
	
	function noCambiarDatos(){
		if(paraCambios==1){
			<?
				if($_GET[funcion]!=""){
					echo 'setTimeout("'.str_replace(chr(92), "",$_GET[funcion]).'",500);';
				}
			?>
		}
		paraCambios=0;
	}
	
	function cambiarDatos(){
		paraCambios = 1;
		for(var i=0; i<mensajescambios.length;i++){
			switch(mensajescambios[i][2]){
				case 0:
					u.txtead.value = 0;
					u.txtrestrinccion.value = "";
					u.t_txtead.value = "$ 0.00";
					break;
				case 3:
					u.txtead.value = 0;
					u.txtrestrinccion.value = "";
					break;
				case 4:
					consultaTexto("respuestaCredito", "guia_consulta_conv.php?accion=2&idcliente="+((u.lstflete.value==0)?u.idremitente.value:u.iddestinatario.value));
					break;
				case 5:
					u.txtead.value = 0;
					u.txtrestrinccion.value = "";
					u.t_txtead.value = "$ 0.00";
					u.t_txtrecoleccionh.value = "1";
					break;
			}
			eval("document.all."+mensajescambios[i][1]);
		}
	}
	
	function respuestaCredito(datos){
		var valor = datos.replace("\r\n","");
		
		u.creditodisponible.value = valor;
	}
	
	function paracambiarvalor(valor){
		if(valor=="X"){
			abrirVentanaFija('../convenio/descripcionesconvenio.php?idconvenio='+valCon.validarConvenioAUsar(u.lstflete.value), 500, 400, 'ventana', 'Busqueda');
		}
	}
	function cambiarValor(descripcion){
		window.open("../guias/guia_consulta_conv.php?accion=1&idsucdestino="+u.sucdestino_hidden.value
						  +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(u.lstflete.value)
						  +"&idmercancia="+tabla1.getSelectedRow().idmercancia+"&descripcion="+tabla1.getSelectedRow().descripcion
						  +"&rd="+Math.random());
		consultaTexto('obtenerMecanciaConvenio',"../guias/guia_consulta_conv.php?accion=1&idsucdestino="+u.sucdestino_hidden.value
						  +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(u.lstflete.value)
						  +"&idmercancia="+tabla1.getSelectedRow().idmercancia+"&descripcion="+tabla1.getSelectedRow().descripcion
						  +"&rd="+Math.random());
	}
	
	//funciones para cancelar
	function mostrarGuiasPendientesCancelar(){
		//abrirVentanaFija('../buscadores_generales/buscarGuiasGen.php?funcion=solicitarGuia&estado=AUTORIZACION PARA CANCELAR', 650, 450, 'ventana', 'Guias pendientes para cancelacion')
	}
	
	function buscarUnaGuia(folioguia){
		//solicitarGuia(folioguia);
	}
	
	function solicitarGuiaCan(folio){
		consulta("respuestaGuia","guiacs_consulta.php?accion=5&folio="+folio+"&rand="+Math.random());
	}
	
	function respuestaGuia(datos){
		u = document.all;
		limpiar_evaluacion();
		limpiar_remitente();
		limpiar_destinatario();
		bloquear(true);
		
		document.getElementById('destino').readOnly=false;
		document.getElementById('destino').style.backgroundColor="";
		document.getElementById('iddestinatario').readOnly=false;
		document.getElementById('iddestinatario').style.backgroundColor="";
		u.guiaguardadav.value = "1";
		
		var encon = datos.getElementsByTagName('encontrados').item(0).firstChild.data;
		if(encon>0){
			var id						= datos.getElementsByTagName('id').item(0).firstChild.data;
			var evaluacion				= datos.getElementsByTagName('evaluacion').item(0).firstChild.data;
			var fecha					= datos.getElementsByTagName('fecha').item(0).firstChild.data;
			var fechaactual				= datos.getElementsByTagName('fechaactual').item(0).firstChild.data;
			var fechaentrega			= datos.getElementsByTagName('fechaentrega').item(0).firstChild.data;
			var factura					= datos.getElementsByTagName('factura').item(0).firstChild.data;
			var idsucursaldestino		= datos.getElementsByTagName('idsucursaldestino').item(0).firstChild.data;
			var iddestino				= datos.getElementsByTagName('iddestino').item(0).firstChild.data;
			var estado					= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var tipoflete				= datos.getElementsByTagName('tipoflete').item(0).firstChild.data;
			var ocurre					= datos.getElementsByTagName('ocurre').item(0).firstChild.data;
			var idsucursalorigen		= datos.getElementsByTagName('idsucursalorigen').item(0).firstChild.data;
			var ndestino				= datos.getElementsByTagName('ndestino').item(0).firstChild.data;
			var nsucdestino				= datos.getElementsByTagName('nsucdestino').item(0).firstChild.data;
			var condicionpago			= datos.getElementsByTagName('condicionpago').item(0).firstChild.data;
			
			var idremitente				= datos.getElementsByTagName('idremitente').item(0).firstChild.data;
			var iddirremitente			= datos.getElementsByTagName('iddireccionremitente').item(0).firstChild.data;
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
			
			document.all.folioSeleccionado.innerHTML = id;
			
			u.folioevaluacion.value		= evaluacion;
			u.destino_hidden.value 		= iddestino;
			u.destino.codigo			= iddestino;
			sucursalorigen 				= idsucursalorigen;
			u.sucdestino_hidden.value	= idsucursaldestino;
			u.fechaactual.value			= fechaactual;
			u.fecha.value				= fecha;
			u.estado.innerHTML			= estado;
			u.lstflete.value			= tipoflete;
			u.idsucursalorigen.value	= idsucursalorigen;
			u.chocurre.value			= ocurre;
			
			document.getElementById('destino').value			= ndestino.split(":")[0];
			
			u.sucdestino.value	= nsucdestino;
			
			u.lstpago.value	= condicionpago;
			u.idremitente.value	= idremitente;
			u.rem_rfc.value	= rrfc;
			u.rem_cliente.value	= rncliente;
			u.rem_direcciones.value = iddirremitente;
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
					volumen		= datos.getElementsByTagName('volumen').item(m).firstChild.data;
					importe		= datos.getElementsByTagName('importe').item(m).firstChild.data;
					
					var objetox = new Object();
					objetox.idmercancia	= idmercancia;
					objetox.cantidad 	= cantidad;
					objetox.descripcion = descripcion;
					objetox.contenido 	= contenido;
					objetox.peso 		= peso;
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
			pedirDatosEvaluacion(evaluacion,id);
			/*if(estado=='CANCELADO'){
				document.all.idsguardar.innerHTML = botonnuevo;
			}else{
				document.all.idsguardar.innerHTML = botonesconsulta;
			}*/
		}else{
			alerta("No se encontro la guia buscada", "¡Atencion!","fecha");
		}
	}
	function cancelarGuia(){
		u = document.all;
		if(u.estado.innerHTML=='AUTORIZACION PARA CANCELAR'){
			abrirVentanaFija('cancelarfinal.php?folioguia='+document.all.folioSeleccionado.innerHTML, 450, 250, 'ventana', 'Motivo de Cancelacion');
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
		consulta("respuestaCancelacion","guiacs_consulta.php?accion=6&folio="+document.all.folioSeleccionado.innerHTML
		+"&motivo="+document.all.motivocancelacion.value+"&rand="+Math.random());
	}
	function respuestaCancelacion(){
		document.all.estado.innerHTML = 'AUTORIZACION PARA CANCELAR';
		alerta("La guia ha sido enviada a pendientes por cancelar", "¡Atencion!", "fecha");
	}
	function cancelarFinal(){
		consulta("respuestaCancelarFinal","guiacs_consulta.php?accion=7&folio="+document.all.folioSeleccionado.innerHTML
		+"&rand="+Math.random());
	}
	function respuestaCancelarFinal(datos){
		document.all.estado.innerHTML = 'CANCELADO';
		alerta("La guia se ha Cancelado", "¡Atencion!", "fecha");
		document.all.idsguardar.innerHTML = botonnuevo;
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
		//so 	= '<?=$_SESSION[IDSUCURSAL]?>';
		//abrirVentanaFija('../buscadores_generales/buscarEvaluacionGen.php?funcion=pedirDatosEvaluacion&tipo=evaluacion&sucorigen='+so, 650, 450, 'Evaluaciones', 'Busqueda');
	}
	//funciones limpiar  
	function limpiar_remitente(){
		u = document.all;
		u.idremitente.value 	= "";
		u.rem_rfc.value 		= "";
		u.rem_cliente.value 	= "";
		u.rem_numero.value		= "";
		u.celda_rem_calle.innerHTML = '<input name="rem_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="rem_direcciones">';
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
		u.celda_des_calle.innerHTML = '<input name="des_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="des_direcciones">'
		
		u.des_colonia.value		= "";
		u.des_poblacion.value	= "";
		u.des_telefono.value	= "";
		u.des_personamoral.value= "";
	}
	function limpiar_evaluacion(){
			u = document.all;
			
			document.all.folioSeleccionado.innerHTML = "&nbsp;";			
			tabla1.clear();
			
			u.convenioaplicado.value	= "";
			u.folioevaluacion.value		= "";
			u.paraconveniotxt.value		= "0";
			u.guiaguardadav.value		= "";
			u.estado.innerHTML 			= "";
			u.chocurre.value			= 0;
			document.getElementById('destino').value 			= "";
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
			u.lstflete.disabled 			= false;
			
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
			
	}
	function limpiar_cajas(){

		document.all.folioSeleccionado.innerHTML = "&nbsp;";
	}
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
			
			if(u.chocurre.value!=1){
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
			if((u.lstflete.value==0 && u.rem_personamoral.value=="SI") ||(u.lstflete.value==1 && u.des_personamoral.value=="SI") ){
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
	
	//funciones para ajax	
	function pedirDatosEvaluacion(idevaluacion,idguia){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			sucursalorigen 	= u.idsucursalorigen.value;
			/*alerta3("guiacs_consulta.php?accion=1&folio="+idevaluacion+
					 "&idsucorigen="+sucursalorigen+"&idsucdestino="+u.sucdestino_hidden.value+
					 "&iddestino="+u.destino_hidden.value+"&valrandom="+Math.random());*/
			consulta("devolverDatosEvaluacion", "guiacs_consulta.php?accion=1&folio="+idevaluacion+
					 "&folioguia="+idguia+
					 "&idsucorigen="+sucursalorigen+"&idsucdestino="+u.sucdestino_hidden.value+
					 "&iddestino="+u.destino_hidden.value+"&valrandom="+Math.random());
		}else{
			alerta("Seleccione una sucursal de Origen","¡Atencion!","fecha");
		}
	}
	function devolverDatosEvaluacion(datos){
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			//para totales
			ocu					= datos.getElementsByTagName('ocu').item(0).firstChild.data;
			ead					= datos.getElementsByTagName('ead').item(0).firstChild.data;
			pagominimocheque	= datos.getElementsByTagName('pfp_pagominimocheques').item(0).firstChild.data;
			pt_ead				= datos.getElementsByTagName('pt_ead').item(0).firstChild.data;
			pt_recoleccion		= datos.getElementsByTagName('pt_recoleccion').item(0).firstChild.data;
			pt_iva				= datos.getElementsByTagName('pt_iva').item(0).firstChild.data;
			pt_ivaretenido		= datos.getElementsByTagName('pt_ivaretenido').item(0).firstChild.data;
			por_combustible		= datos.getElementsByTagName('por_combustible').item(0).firstChild.data;
			max_descuento		= datos.getElementsByTagName('max_des').item(0).firstChild.data;
			vporcada			= datos.getElementsByTagName('por_cada').item(0).firstChild.data;
			vscosto				= datos.getElementsByTagName('scosto').item(0).firstChild.data;
			//erecoleccion		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;
			pesominimodesc		= datos.getElementsByTagName('pesominimodesc').item(0).firstChild.data;
			//restringiread		= datos.getElementsByTagName('restringiread').item(0).firstChild.data;
			desead				= datos.getElementsByTagName('desead').item(0).firstChild.data;
			desrrecoleccion		= datos.getElementsByTagName('desrrecoleccion').item(0).firstChild.data;
			desporcobrar		= datos.getElementsByTagName('desporcobrar').item(0).firstChild.data;
			desconvenio			= datos.getElementsByTagName('desconvenio').item(0).firstChild.data;
			
			u.txtocu.value 					= ocu;
			u.txtead.value 					= ead;
			u.pagominimocheque.value 		= pagominimocheque;
			u.txteadh.value 				= ead;
			u.desead.value					= desead;
			u.desrrecoleccion.value			= desrrecoleccion;
			u.desporcobrar.value			= desporcobrar;
			u.desconvenio.value				= desconvenio;
			u.pc_ead.value					= pt_ead;
			u.pc_recoleccion.value			= pt_recoleccion;
			u.pc_tarifacombustible.value	= por_combustible;
			u.pc_maximodescuento.value		= max_descuento;
			u.pc_porcada.value				= vporcada;
			u.pc_costo.value				= vscosto;
			u.pc_iva.value					= pt_iva;
			u.pc_ivaretenido.value			= pt_ivaretenido;
			u.pc_pesominimodesc.value		= pesominimodesc;
			//u.restringiread.value 			= restringiread;
			u.t_txtexcedente.value			= "$ 0.00";
		}else{
			alerta("Evaluación no encontrada","¡Alerta!","idremitente");
		}
		u.idremitente.focus();
		solicitarDatosConv();
	}
	
	function devolverRemitente(valor){
		var u = document.all;
		if(u.folioevaluacion.value==""){
			alerta3("Porfavor seleccione la evaluacion para poder agregar el remitente", "¡Atencion!");
			u.idremitente.value = "";
		}else{
			limpiar_remitente();
			document.all.idremitente.value = valor;
			consulta("mostrarRemitente", "guiacs_consulta.php?accion=2&idcliente="+valor+"&valrandom="+Math.random());
		}
	}
	
	function mostrarRemitente(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;
			u.rem_rfc.value 			= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			u.rem_cliente.value 		= datos.getElementsByTagName('ncliente').item(0).firstChild.data;
			u.rem_personamoral.value	= datos.getElementsByTagName('personamoral').item(0).firstChild.data;
			v_celular 					= datos.getElementsByTagName('celular').item(0).firstChild.data;
			
			u.txtavisocelular2h.value 	= (v_celular!="")?v_celular:"";
			if(endir==1){
				document.all.celda_rem_calle.innerHTML ='<input name="rem_calle" readonly="true" type="text" '
				+'style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="rem_direcciones">';
				u.rem_direcciones.value	= datos.getElementsByTagName('idcalle').item(0).firstChild.data;
				u.rem_calle.value 		= datos.getElementsByTagName('calle').item(0).firstChild.data;
				u.rem_numero.value 		= datos.getElementsByTagName('numero').item(0).firstChild.data;
				u.rem_cp.value 			= datos.getElementsByTagName('cp').item(0).firstChild.data;
				u.rem_colonia.value 	= datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.rem_poblacion.value 	= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				u.rem_telefono.value 	= datos.getElementsByTagName('telefono').item(0).firstChild.data;
				u.iddestinatario.focus();
			}else if(endir>1){
				var comb = "<select name='rem_direcciones' style='width:165px;font:tahoma; font-size:9px' onchange='"
				+"document.all.rem_numero.value=this.options[this.selectedIndex].numero;"
				+"document.all.rem_cp.value=this.options[this.selectedIndex].cp;"
				+"document.all.rem_colonia.value=this.options[this.selectedIndex].colonia;"
				+"document.all.rem_poblacion.value=this.options[this.selectedIndex].poblacion;"
				+"document.all.rem_telefono.value=this.options[this.selectedIndex].telefono;"
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
						u.rem_numero.value 		= v_numero;
						u.rem_cp.value 			= v_cp;
						u.rem_colonia.value 	= v_colonia;
						u.rem_poblacion.value 	= v_poblacion;
						u.rem_telefono.value 	= v_telefono;	
					}
					
					comb += "<option value='"+v_idcalle+"' numero='"+v_numero+"' cp='"+v_cp+"' colonia='"+v_colonia+"'"
					+"poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'>"
					+v_calle+", "+v_numero+", "+v_colonia+"</option>";
				}
				comb += "</select>";
				document.all.celda_rem_calle.innerHTML = comb;
				u.rem_direcciones.focus();
			}else{
				alerta("El Cliente no tiene direccion","","idremitente");
			}
			if(u.desconvenio.value!="1"){
				consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idremitente="+u.idremitente.value
						  +"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&fevaluacion="+u.folioevaluacion.value
						  +"&idsucdestino="+u.sucdestino_hidden.value+"&valran="+Math.random());
			}
		}else{
			alerta("El Cliente no existe","","idremitente");
		}
		calculartotales();
	}
	function devolverDestinatario(valor){
		var u = document.all;
	
		limpiar_destinatario();
		document.all.iddestinatario.value = valor;
		consulta("mostrarDestinatario", "guiacs_consulta.php?accion=2&idcliente="+valor+"&poblacion="+u.npobdes.value+"&valrandom="+Math.random());
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
				+'style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="des_direcciones">';
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
						  +"&idsucdestino="+u.sucdestino_hidden.value+"&valran="+Math.random());
			}
		}else{
			alerta("El Cliente no existe","","iddestinatario");
		}
		if(u.des_poblacion.value != u.npobdes.value){
			alerta("Direccion del destinatario no concuerda con el destino, debe corregir dirección, destino, o enviar ocurre","¡Alerta!","iddestinatario");	
		}
		calculartotales();
	}
	function devolverDestino(){
		u = document.all;
		if(u.destino_hidden.value==""){
			setTimeout("devolverDestino()",500);
		}else{
			consulta("mostrarDestino", "guiacs_consulta.php?accion=3&iddestino="+u.destino_hidden.value);
		}
	}
	
	function mostrarDestino(datos){
		var encon 		= datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var iddestino	= datos.getElementsByTagName('iddestino').item(0).firstChild.data;
			var descripcion	= datos.getElementsByTagName('descripcion').item(0).firstChild.data;
			var poblacion	= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			var npoblacion	= datos.getElementsByTagName('npoblacion').item(0).firstChild.data;
		}else{
			var iddestino	= "";
			var descripcion	= "";
			var poblacion	= "";
			alerta("No se encontro la sucursal destino","¡Atencion!","destino");
		}
		u.sucdestino.value 			= descripcion;
		document.getElementById('destino').poblacion 		= poblacion;
		u.sucdestino_hidden.value 	= iddestino;
		u.npobdes.value = npoblacion;
		pedirDatosEvaluacion(u.folioevaluacion.value,document.all.folioSeleccionado.innerHTML);
		devolverDestinatario(u.iddestinatario.value)
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
		if(u.folioevaluacion.value==""){
			alerta('Debe seleccionar una guia','¡Atención!',"idremitente");
			return false;
		}else if(u.idremitente.value==""){
			alerta('Debe capturar el remitente','¡Atención!',"idremitente");
			return false;
		}else if(u.iddestinatario.value==""){
			alerta('Debe capturar el destinatario','¡Atención!',"iddestinatario");
			return false;
		}else if(u.des_poblacion.value != u.npobdes.value){
			alerta("Direccion del destinatario no concuerda con el destino, debe corregir dirección, destino, o enviar ocurre","¡Alerta!","iddestinatario");	
		}
		return true;
	}
	
	function ejecutarSubmit(){
			//confirmar("¿Desea guardar la Guia?","¡Atencion!","registrarGuia()","");
			abrirVentanaFija("guiacs_motivo.php", 480, 400, 'ventana', 'Datos Producto')
	}
	
	function preguntaFactura(){
		//confirmar("¿Desea Facturar?","¡Atencion!","","registrarGuia()");
	}
	
	function registrarGuia(){
		//valores agregados para reportesmaestros
		/*		
		var convenioaplicado	= u.convenioaplicado.value;
		var clienteconvenio		= u.clientedelconvenio.value;
		var sucursaldelconvenio	= u.sucursaldelconvenio.value;
		var nombrevendedor		= u.nombrevendedor.value;
		var idvendedor			= u.idvendedor.value;
		
		//alerta3("guiacs_consulta.php?accion=4&folioguia="+folioguia+"&nvendedorconvenio="+nombrevendedor
			//	 +"&convenioaplicado="+convenioaplicado+"&totalpaquetes="+u.totalpaquetes.value+"&ran="+Math.random());
		consulta("resGuiaGuardada","guiacs_consulta.php?accion=4&folioguia="+folioguia+"&nvendedorconvenio="+nombrevendedor
				 +"&convenioaplicado="+convenioaplicado+"&totalpaquetes="+u.totalpaquetes.value+"&ran="+Math.random());*/
		
		var u = document.all;
		var folioguia 				= document.all.folioSeleccionado.innerHTML;
		var evaluacion 				= u.folioevaluacion.value;
		var fecha					= u.fecha.value;
		var tipoflete				= u.lstflete.value;
		var ocurre					= u.chocurre.value;
		var idsucursalorigen		= sucursalorigen;
		var iddestino				= u.destino_hidden.value;
		var idsucursaldestino		= u.sucdestino_hidden.value;
		var condicionpago			= u.lstpago.value;
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
		var motivocancelacion 		= u.motivocancelacion.value;
		
		//valores agregados para reportesmaestros
		var convenioaplicado	= u.convenioaplicado.value;
		var clienteconvenio		= u.clientedelconvenio.value;
		var sucursaldelconvenio	= u.sucursaldelconvenio.value;
		var nombrevendedor		= u.nombrevendedor.value;
		var idvendedor			= u.idvendedor.value;
		
		var pagina = "guiacs_consulta.php?accion=4&evaluacion="+evaluacion+"&fecha="+fecha+"&tipoflete="+tipoflete+"&ocurre="+ocurre
				 +"&idsucursalorigen="+idsucursalorigen+"&iddestino="+iddestino+"&idsucursaldestino="+idsucursaldestino
				 +"&condicionpago="+condicionpago+"&idremitente="+idremitente+"&iddireccionremitente="+iddireccionremitente
				 +"&iddestinatario="+iddestinatario+"&iddirecciondestinatario="+iddirecciondestinatario+"&entregaocurre="+entregaocurre
				 +"&entregaead="+entregaead+"&restrinccion="+restrinccion+"&totalpaquetes="+totalpaquetes+"&totalpeso="+totalpeso
				 +"&totalvolumen="+totalvolumen+"&emplaye="+emplaye+"&bolsaempaque="+bolsaempaque+"&totalbolsaempaque="+totalbolsaempaque
				 +"&avisocelular="+avisocelular+"&celular="+celular+"&valordeclarado="+valordeclarado+"&acuserecibo="+acuserecibo
				 +"&cod="+cod+"&recoleccion="+recoleccion+"&observaciones="+observaciones+"&tflete="+tflete+"&tdescuento="+tdescuento
				 +"&ttotaldescuento="+ttotaldescuento+"&tcostoead="+tcostoead+"&trecoleccion="+trecoleccion+"&tseguro="+tseguro
				 +"&totros="+totros+"&texcedente="+texcedente+"&tcombustible="+tcombustible+"&subtotal="+subtotal+"&tiva="+tiva
				 +"&ivaretenido="+ivaretenido+"&total="+total+"&efectivo="+efectivo+"&cheque="+cheque+"&banco="+banco
				 +"&ncheque="+ncheque+"&tarjeta="+tarjeta+"&trasferencia="+trasferencia+"&clienteconvenio="+clienteconvenio
				 +"&sucursalconvenio="+sucursaldelconvenio+"&idvendedorconvenio="+idvendedor+"&nvendedorconvenio="+nombrevendedor
				 +"&convenioaplicado="+convenioaplicado+"&folioguia="+folioguia+"&motivocancelacion="+motivocancelacion+"&ran="+Math.random();

		
		pagina = pagina.replace(/=&/g,"=0&");
		//alerta3(pagina,"");
		consulta("resGuiaGuardada",pagina);
	}
	
	function resGuiaGuardada(datos){
		var guardado= datos.getElementsByTagName('guardado').item(0).firstChild.data;
		if(guardado==1){
			info("La solicitud de cancelacion y sustitucion ha sido guardada","¡Atencion!");
			document.getElementById('estado').innerHTML="AUTORIZACION PARA SUSTITUIR";

			document.all.idsguardar.style.display = "none";
		}else{
			cons = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al guardar //" + cons,"¡Atencion!","idremitente");
		}
			//document.all.idsguardar.innerHTML = botonesdesguardar;
	}
	
	function valcreditodisponible(){
		if(u.lstpago.value==1){
			if(parseFloat(u.creditodisponible.value)<parseFloat(u.t_txttotal.value.replace("$ ","").replace(",",""))){
				alerta3("Credito insuficiente","¡Atencion!");
				return false;
			}else{
				return true;
			}
		}
		return true;
	}
	
	
	function mostrarformapago(){
		var u=document.all;
	abrirVentanaFija('formapago.php?total='+u.t_txttotal.value+'&cliente='+u.clientedelconvenio.value, 600, 400, 'ventana', 'Forma de Pago');
	}
</script>
</head>

<body>

<form id="form1" name="form1" method="post" action="">
<table width="631" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" class="FondoTabla" style="font-size:12px">CANCELACIÓN Y SUSTITUCIÓN DE GUÍA</td>
  </tr> 
  <tr>
  	<td colspan="2">
    	<table width="626" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td width="626" align="right" id="folioSeleccionado" style="color:#F00000; font-size:15px; font-weight:bold">&nbsp;</td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td colspan="2"><input name=DetalleGrip type=hidden id=DetalleGrip value="<?=$DetalleGrip ?>"></td>
  </tr> 
  <tr>
    <td colspan="2">
      <table width="615" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="5%" class="Tablas">Fecha:</td>
        <td width="15%">
		<?
			$s = "select date_format(current_date, '%d/%m/%Y') as fecha";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
		?>
            &nbsp;&nbsp;
            <input name="fecha" readonly="true" type="text" id="fecha" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$f->fecha ?>" size="13" align="top" />
            <a onClick="limpiar_cajas()" href="../menu/webministator.php" >
            <input type="hidden" name="folioevaluacion" value="">
            <input type="hidden" name="guiaguardadav" value="">
            <input type="hidden" name="idsucursalorigen" value="">
            <input type="hidden" name="fechaactual" value="">
            <input type="hidden" name="restringiread" value="">
            <input type="hidden" style="width:15px" name="convenioaplicado" value="">
            <input type="hidden" style="width:15px" name="clientedelconvenio" value="">
            <input type="hidden" style="width:15px" name="sucursaldelconvenio" value="">
            <input type="hidden" style="width:15px" name="nombrevendedor" value="">
            <input type="hidden" style="width:15px" name="idvendedor" value="">
            <input type="hidden" name="creditodisponible" value="">
            </a></td>
        <td width="8%" class="Tablas">Estado:</td>
        <td width="55%" id="estado" style="font:tahoma; font-size:15px; font-weight:bold">&nbsp;</td>
        <td width="12%" ><img src="../img/Boton_Detalle.gif" style="cursor:pointer"
        onClick="abrirVentanaFija('informacionextra.php?tipo=1&folio='+document.all.folioSeleccionado.innerHTML, 625, 418, 'ventana', 'Detalle')"></td>
<td width="5%" align="right">&nbsp;</td>
</tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2" ><table width="618" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="7%" class="Tablas">T. Flete:</td>
        <td width="14%"><select name="lstflete" style="width:77px; font-size:9px" onChange="solicitarDatosConv(); calculartotales()">
            <option value="0">Pagado</option>
            <option value="1">Por Cobrar</option>
        </select></td>
        <td>
        	<select name="chocurre" id="chocurre" onChange="solicitarDatosConv(); if(document.all.restringiread.value==1){alerta('El destino tiene restringida la Entrega a Domicilio','¡Atencion!','chocurre'); this.value=1;}else{if(this.value==0){document.all.t_txtead.value = document.all.t_txteadh.value}else{document.all.t_txtead.value = '$ 0.00';}} calculartotales();" style="width:77px; font-size:9px">
            	<option value="0">EAD</option>
                <option value="1">Ocurre</option>
            </select>        </td>
<td width="7%" class="Tablas">Destino:</td>
        <td width="16%">
        <input name="destino" type="text" class="Tablas" id="origen" style="width:100px; text-transform:uppercase;" value="<?=$_POST[origen] ?>" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.destino_hidden.value=this.codigo;devolverDestino();devolverDestino();}" onBlur="if(this.value!=''){document.all.destino_hidden.value = this.codigo; devolverDestino();}"  >
        
        <input type="hidden" name="destino_hidden">
        <input type="hidden" name="npobdes">               </td>
        <td width="10%"><span class="Tablas">Suc. Destino:</span></td>
        <td width="13%"><input name="sucdestino" type="text" id="sucdestino" style="background:#FFFF99;font:tahoma; font-size:9px" readonly value="<?=$destino?>" poblacion="" size="20" /><input type="hidden" name="sucdestino_hidden"></td>
        <td width="9%"><span class="Tablas">Cond. Pago:</span></td>
        <td width="13%">&nbsp;
            <select name="lstpago" id="lstpago" style="width:70px; font-size:9px">
              <option value="0">Contado</option>
              <option value="1">Credito</option>
          </select></td>
        <td width="2%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><table width="621" border="0" align="center">
      <tr>
        <td width="630"><table width="610" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">
            <tr>
              <td width="324" class="FondoTabla">Remitente</td>
              <td width="280" class="FondoTabla">Destinatario</td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
                  <tr>
                    <td width="16%"><span class="Tablas"># Cliente: </span></td>
                    <td><input name="idremitente" type="text" onKeyPress="if(event.keyCode==13 && this.readOnly==false){ devolverRemitente(this.value)}else{return solonumeros(event)}" style="font:tahoma; font-size:9px" value="<?=$remitente ?>" size="4" />
                      &nbsp;&nbsp;<img id="b_remitente" src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverRemitente', 625, 418, 'ventana', 'Busqueda')" />
                      <input type="hidden" name="rem_personamoral">                      </td>
                    <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>
                        <input name="rem_rfc" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="24" /></td>
                  </tr>
                  <tr>
                    <td><span class="Tablas">Cliente:</span></td>
                    <td colspan="4">
                    <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td>
                    <input name="rem_cliente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" 
                    value="<?=$rcliente ?>" size="54" />                    </td>
                    <td align="right" valign="middle">
                    <img id="b_remitente_dir" src="../img/Boton_Agregarchico.gif" alt="Agregar Dirección" style="cursor:hand" onClick="if(document.all.idremitente.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','idremitente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverRemitente('+document.all.idremitente.value+')&idcliente='+document.all.idremitente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}">                    </td>
                    </table>                    </td>
                  </tr>
                  <tr>
                    <td><span class="Tablas">Calle:</span></td>
                    <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="153" height="16" id="celda_rem_calle"><input name="rem_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" /><input type="hidden" name="rem_direcciones"></td>
                        <td width="97"><span class="Tablas">Numero: </span><span class="Tablas">
                          <input name="rem_numero" type="text" readonly="true" style=" width:50px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />
                        </span></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><span class="Tablas">CP:</span></td>
                    <td width="29%"><input name="rem_cp" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcp ?>" size="15" /></td>
                    <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;
                          <input name="rem_colonia" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcolonia ?>" size="24" />
                    </span></td>
                  </tr>
                  <tr>
                    <td><span class="Tablas">Poblaci&oacute;n:</span></td>
                    <td colspan="4"><input name="rem_poblacion" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rpoblacion ?>" size="25" />
                        <span class="Tablas">&nbsp;Tel&eacute;fono:
                          <input name="rem_telefono" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rtelefono ?>" size="14" />
                      </span></td>
                  </tr>
              </table></td>
              <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
                <tr>
                  <td><input name="iddestinatario" onKeyPress="if(event.keyCode==13 && this.readOnly==false){devolverDestinatario(this.value)}else{return solonumeros(event)}" type="text" style="font:tahoma; font-size:9px" value="<?=$remitente ?>" size="4" />
                    &nbsp;&nbsp;<img id="b_destinatario" src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverDestinatario', 625, 418, 'ventana', 'Busqueda')"/>
                    <input type="hidden" name="des_personamoral">
                    <input type="hidden" name="paraconveniotxt" value="0">                    </td>
                  <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>
                      <input name="des_rfc" type="text" readonly="true" id="rrfc22" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="24" /></td>
                </tr>
                <tr>
                  <td colspan="4">
                  <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                  <td>
                  <input name="des_cliente" readonly="true" type="text" 
                  style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcliente ?>" size="54" />                  </td>
                    <td align="right" valign="middle">
                    <img id="b_destinatario_dir" src="../img/Boton_Agregarchico.gif" alt="Agregar Dirección" style="cursor:hand" onClick="if(document.all.iddestinatario.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','iddestinatario') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverDestinatario('+document.all.iddestinatario.value+')&idcliente='+document.all.iddestinatario.value, 460, 395, 'ventana', 'DATOS DIRECCION')}">                    </td>
                    </table>                  </td>
                </tr>
                <tr>
                  <td colspan="4">
                  <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="153" height="16" id="celda_des_calle"><input name="des_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" />
                          <input type="hidden" name="des_direcciones"></td>
                        <td width="97"><span class="Tablas">Numero: </span><span class="Tablas">
                          <input name="des_numero" type="text" readonly="true" style=" width:50px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />
                        </span></td>
                      </tr>
                    </table>                  </td>
                </tr>
                <tr>
                  <td width="29%"><input name="des_cp" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcp ?>" size="15" /></td>
                  <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;
                        <input name="des_colonia" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcolonia ?>" size="24" />
                  </span></td>
                </tr>
                <tr>
                  <td colspan="4"><input name="des_poblacion" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rpoblacion ?>" size="25" />
                      <span class="Tablas">&nbsp;Tel&eacute;fono:
                        <input name="des_telefono" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rtelefono ?>" size="14" />
                    </span></td>
                </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><table width="618" border="0" align="center">
      <tr>
        <td width="436">
         <table border="0" cellpadding="0" cellspacing="0" id="tablaconteva"></table>
        </td>
        <td width="188"><table width="177" height="90" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="194">
              
              <table width="177" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">
                  <tr>
                    <td width="172" class="FondoTabla">Tiempo de Entrega </td>
                  </tr>
                  <tr>
                    <td><table width="163" height="0" align="center" bordercolor="#016193">
                        <tr>
                          <td width="41" class="Tablas">Ocurre:</td>
                          <td width="40">
                          	<input name="txtocu" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$drfc ?>" size="5" />
                          </td>
                          <td width="28" class="Tablas">EAD:</td>
                          <td width="34">
                          	<input name="txtead" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$drfc ?>" size="5" />
                            <input name="txteadh" type="hidden" /></td>
                        </tr>
                    </table></td>
                  </tr>
              </table>
              
              </td>
            </tr>
            <tr>
              <td><table width="176" height="0" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">
                  <tr>
                    <td width="200" class="FondoTabla">Restricciones</td>
                  </tr>
                  <tr>
                    <td><label>
                      <textarea name="txtrestrinccion" readonly style="width:170px; font-size:9px; background-color:#FFFF99; text-transform:uppercase"></textarea>
                      <input name="txtrestrinccionh" type="hidden" />
</label></td>
                  </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="325" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td width="56" class="Tablas">T. Paquetes: </td>
        <td width="43" class="Tablas"><input name="totalpaquetes" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:40px" value="<?=$rcp ?>"/></td>
        <td width="51" class="Tablas">T. Peso Kg: </td>
        <td width="48" class="Tablas"><input name="totalpeso" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:40px" value="<?=$rcp ?>" /></td>
        <td width="61" class="Tablas">T. Volumen: </td>
        <td width="66" class="Tablas"><input name="totalvolumen" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:60px" value="<?=$rcp ?>" /></td>
      </tr>
    </table></td>
    <td width="49%" rowspan="2"><table width="302" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#74B051">
      <tr>
        <td width="257" bgcolor="#74B051"><span class="Estilo2">TOTALES</span>
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
        <input type="hidden" name="desconvenio">
        </td>
      </tr>
      <tr>
        <td height="140"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="Tablas">&nbsp;&nbsp;Flete:</td>
              <td class="Tablas"><input name="flete" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
              <td class="Tablas">Excedente:</td>
              <td class="Tablas"><input name="t_txtexcedente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            </tr>
            <tr>
              <td class="Tablas">&nbsp;&nbsp;Descuento:</td>
              <td class="Tablas"><input readonly="true" name="t_txtdescuento1" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="2" onKeyPress="if(event.keyCode==13 && this.readOnly==false){ if(parseFloat(this.value)>parseFloat(document.all.pc_maximodescuento.value)){ this.value=document.all.pc_maximodescuento.value; alerta('El maximo descuento permitido es '+document.all.pc_maximodescuento.value+' %','¡Atencion!','t_txtdescuento1')} calcularDescuento()}else{return solonumeros(event);}" />
                  <input name="t_txtdescuento2" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="4" />                  <img id="img_descuento" src="../img/update.gif" onClick="if(validarDescuento()){ abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=permitirDescuento', 370, 500, 'ventana', 'Inicio de Sesión Secundaria');}" style="cursor:hand"></td>
<td class="Tablas">Combustible:</td>
              <td class="Tablas"><input name="t_txtcombustible" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            </tr>
            <tr>
              <td class="Tablas">&nbsp;&nbsp;EAD:</td>
              <td class="Tablas"><input readonly="true" name="t_txtead" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /><input name="t_txteadh" type="hidden" /><input name="t_txteadh2" type="hidden" /></td>
              <td class="Tablas">Subtotal:</td>
              <td class="Tablas"><input name="t_txtsubtotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            </tr>
            <tr>
              <td class="Tablas">&nbsp;&nbsp;Recolecci&oacute;n:</td>
              <td class="Tablas"><input readonly="true" name="t_txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /><input name="t_txtrecoleccionh" type="hidden" /></td>
              <td class="Tablas">IVA:</td>
              <td class="Tablas"><input name="t_txtiva" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            </tr>
            <tr>
              <td width="24%" class="Tablas">&nbsp;&nbsp;Seguro:</td>
              <td class="Tablas"><input readonly="true" name="t_txtseguro" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
              <td width="23%" class="Tablas">IVA Retenido: </td>
              <td width="17%" class="Tablas"><input name="t_txtivaretenido" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            </tr>
            <tr>
              <td class="Tablas">&nbsp;&nbsp;Otros:</td>
              <td class="Tablas"><input name="t_txtotros" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
              <td class="Tablas">Total:</td>
              <td class="Tablas"><input name="t_txttotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
            </tr>
            <tr>
              <td colspan="2" class="Tablas" valign="middle">
              <input type="hidden" value="0" name="pagoregistrado">
              <input type="hidden" value="" name="efectivo">
              <input type="hidden" value="" name="cheque">
              <input type="hidden" value="" name="ncheque">
              <input type="hidden" value="" name="banco">
              <input type="hidden" value="" name="tarjeta">
              <input type="hidden" value="" name="transferencia">
              <input type="hidden" value="" name="pagominimocheque"></td>
<td class="Tablas"><input type="hidden" name="motivocancelacion"></td>
<td class="Tablas">&nbsp;</td>
</tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  
  
  <tr>
    <td width="51%" >
    <table border="0" align="right" cellpadding="0" cellspacing="0">
    	<tr>
        	<td width="318">
    <table width="315" height="140" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">
      <tr>
        <td width="434" class="FondoTabla">Servicios</td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" height="76" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="6%"><input name="chkemplaye" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtemplaye.value='';}else{document.all.txtemplaye.value = document.all.txtemplayeh.value} calculartotales();" /></td>
              <td class="Tablas">Emplaye
                <input name="txtemplaye" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />
                <input name="txtemplayeh" type="hidden" />
                </td>
              <td class="Tablas"><input name="chkacuserecibo" onClick="if(!this.checked){document.all.txtacuserecibo.value='';}else{document.all.txtacuserecibo.value=document.all.txtacusereciboh.value;} calculartotales();" type="checkbox" style="width:8px; height:9px" value="SI" /></td>
              <td class="Tablas">Acuse Recibo</td>
              <td class="Tablas" align="right"><input readonly="true" name="txtacuserecibo" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
              <input name="txtacusereciboh" type="hidden" />
              </td>
            </tr>
            <tr>
              <td><input name="chkbolsaempaque" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtbolsaempaque1.value = ''; document.all.txtbolsaempaque2.value = ''; document.all.txtbolsaempaque1.readOnly=true; document.all.txtbolsaempaque1.style.backgroundColor='#FFFF99';}else{ if(document.all.txtbolsaempaque1h.value=='' || document.all.txtbolsaempaque1h.value=='0'){document.all.txtbolsaempaque1.readOnly=false; document.all.txtbolsaempaque1.style.backgroundColor='#FFFFFF';}else{document.all.txtbolsaempaque1.value = document.all.txtbolsaempaque1h.value; document.all.txtbolsaempaque2.value = document.all.txtbolsaempaque2h.value;}} calculartotales();" /></td>
              <td width="49%" class="Tablas">Bolsa Empaque
                <input name="txtbolsaempaque1" readonly="true" onBlur="if(this.readOnly==false){calculartotales();}" onKeyPress="if(this.readOnly==false && event.keyCode==13){document.all.txtbolsaempaque2.value='$ '+numcredvar((parseFloat((document.all.txtbolsaempaque3h.value=='')?'0':document.all.txtbolsaempaque3h.value.replace('$ ', '').replace(/,/g,''))*parseFloat(this.value)).toLocaleString());calculartotales();}else{return solonumeros(event);}" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="1" />
                  <input name="txtbolsaempaque2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="6" />
                  <input name="txtbolsaempaque1h" type="hidden" /><input name="txtbolsaempaque2h" type="hidden" /><input name="txtbolsaempaque3h" type="hidden" />
                  </td>
              <td width="5%" class="Tablas"><input name="chkcod" onClick="if(!this.checked){document.all.txtcod.value='';}else{document.all.txtcod.value=document.all.txtcodh.value;} calculartotales();" type="checkbox" style="width:8px; height:8px" value="SI" />
              </td>
              <td width="19%" class="Tablas">COD</td>
              <td width="21%" class="Tablas" align="right"><input readonly="true" name="txtcod" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
              <input name="txtcodh" type="hidden" />
              </td>
            </tr>
            <tr>
              <td><input name="chkavisocelular" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtavisocelular2.readOnly=true; document.all.txtavisocelular2.style.backgroundColor='#FFFF99'; document.all.txtavisocelular2.value='';document.all.txtavisocelular1.value=''; }else{document.all.txtavisocelular1.value=document.all.txtavisocelular1h.value;document.all.txtavisocelular2.readOnly=false; document.all.txtavisocelular2.style.backgroundColor='#FFFFFF'; document.all.txtavisocelular2.value=document.all.txtavisocelular2h.value;document.all.txtavisocelular2.focus();}  calculartotales();" /></td>
              <td colspan="4" class="Tablas">Aviso Celular
                <input name="txtavisocelular1" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />
                <input name="txtavisocelular1h" type="hidden" />
                  <input name="txtavisocelular2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="10" /><input name="txtavisocelular2h" type="hidden" /></td>
            </tr>
            <tr>
              <td><input name="chkvalordeclarado" type="checkbox" style="width:8px; height:8px" value="SI"
               onClick="if(!this.checked){document.all.txtdeclarado.value='';document.all.txtdeclarado.readOnly=true; document.all.txtdeclarado.style.backgroundColor='#FFFF99'; document.all.txtdeclarado.readOnly=true;}else{document.all.txtdeclarado.readOnly=false; document.all.txtdeclarado.style.backgroundColor='#FFFFFF'; document.all.txtdeclarado.readOnly=false;document.all.txtdeclarado.focus();} calculartotales();" /></td>
              <td class="Tablas">Valor Declarado
              <?
			  	$s = "SELECT maxvalordeclaradoguia FROM configuradorgeneral";
				$rmvd = mysql_query($s,$l) or die($s);
				$fmvd = mysql_fetch_object($rmvd);
			  ?>
                <input name="txtdeclarado" type="text" readonly="true" onBlur="if(this.readOnly==false){this.value=this.value.replace('$ ','').replace(/,/,'');  if(this.value==''){this.value='$ 0.00';}else{ if(parseFloat(this.value) > <?=$fmvd->maxvalordeclaradoguia?>){this.value = <?=$fmvd->maxvalordeclaradoguia?>; alerta3('El maximo valor declarado permitido es <?=$fmvd->maxvalordeclaradoguia?>', '¡Atencion!');} this.value='$ '+numcredvar(this.value); calculartotales(); }}" onKeyPress="if(this.readOnly==false){ if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/,'')); calculartotales();}else{return solonumeros(event);}} " style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
              <td class="Tablas"><input name="chkrecoleccion" type="checkbox" onClick="if(!this.checked){document.all.txtrecoleccion.value=''; document.all.t_txtrecoleccion.value=''; }else{document.all.txtrecoleccion.value=document.all.txtrecoleccionh.value; } calculartotales();" id="chocurre24" style="width:8px; height:8px" value="SI" /></td>
              <td class="Tablas">Recolecci&oacute;n</td>
              <td class="Tablas" align="right"><input readonly="true" name="txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; text-align:right" value="<?=$rrfc ?>" size="8" />
              <input name="txtrecoleccionh" type="hidden" />
              </td>
            </tr>
</table></td>
      </tr>
    </table>
    		</td>
        	<td width="5"></td>
        </tr>
        </table>
    </td>
    </tr>
  
  <tr>
    <td colspan="2"><table width="623" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="339"><table width="328" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">
            <tr>
              <td width="377" class="FondoTabla">Observaciones</td>
            </tr>
            <tr>
              <td><textarea name="txtobservaciones" style="width:320px; font-size:9px; font:tahoma; text-transform:uppercase"></textarea></td>
            </tr>
        </table></td>
        <td width="284"><table width="282" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
              <td width="274" align="center" valign="middle" id="idsguardar"><img src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="if(validarDatos() && valcreditodisponible()){ ejecutarSubmit(); };"></td>
</tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2">
        <input type="hidden" name="motivocancelacion">
        </td>
</tr>
    </table></td>
  </tr>
  
  <tr>
    <td colspan="2"><table width="624" border="0" align="center">
      <tr>
        <td width="618" align="center">
          </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2">
    </td>
  </tr>
  <tr>
    <td colspan="2" ></td>
  </tr>
</table>
</form>
</body>
</html>
