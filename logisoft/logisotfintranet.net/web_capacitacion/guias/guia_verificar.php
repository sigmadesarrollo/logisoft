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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Guía ventanilla | PMM</title>
<link href="../css_ne/reseter.css" rel="stylesheet" type="text/css" />
<link href="../css_ne/style.css" rel="stylesheet" type="text/css" />

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<!-- funciones para ajax -->
<script type="text/javascript" src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../convenio/validacionesConvenio.js"></script>
<link href="../sobreImagenes.css" rel="stylesheet" type="text/css" />
<OBJECT ID="Metodos" style="visibility:hidden"
CLASSID="CLSID:21B8DA59-7F02-40B9-A5E9-FC848C3DB134"
CODEBASE="http://www.pmmintranet.net/web/activexs/Impresion.CAB#version=1,1,0,0">
</OBJECT>


<!--[if lte IE 6]><link href="css/styleie6.css" rel="stylesheet" type="text/css" /><![endif]--> 
<script>
	//declaracion de tablas
	var sucursalorigen 	= 0;
	var var_facturar = false;
	var valCon = new validacionesConvenio();
	var cantidadVencida = 0;
	var u = document.all;
	
	var tabla1 = new ClaseTabla();
	
	tabla1.setAttributes({
		nombre:"tablaconteva",
		campos:[
			{nombre:"IDM", medida:4, alineacion:"left", tipo:"oculto", datos:"idmercancia"},
			{nombre:"Cant", medida:69, alineacion:"left", datos:"cantidad"},
			{nombre:"Descripcion", medida:144, alineacion:"left", datos:"descripcion"},
			{nombre:"Contenido", medida:144, alineacion:"left", datos:"contenido"},
			{nombre:"Peso", medida:69, alineacion:"right", datos:"peso"},
			{nombre:"Vol", medida:72, alineacion:"right", datos:"volumen"},
			{nombre:"Importe", medida:98, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"M", medida:14, alineacion:"right", datos:"modificable"}
		],
		filasInicial:6,
		alto:100,
		seleccion:true,
		eventoDblClickFila:"paracambiarvalor(tabla1.getSelectedRow().modificable);",
		ordenable:true,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		<?
		$_GET[funcion2] = str_replace("\'","'",$_GET[funcion2]);
		if($_GET[funcion2]!=""){
			echo 'setTimeout("'.$_GET[funcion2].'",1500);';
			}
		?>
	}
	
	var botonnuevo	 = '<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesnuevo = '<img src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="if(validarDatos() && valcreditodisponible()){ if(document.all.lstflete.value==0 && document.all.lstpago.value==0){ mostrarformapago(); }else{ ejecutarSubmit(); } };">&nbsp;&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesdesguardar = '<img src="../img/Boton_Cancela_Guia.gif" style="cursor:hand" onClick="cancelarGuia()">&nbsp;<img src="../img/Boton_Imprimir.gif" onclick="imprimir()" style="cursor:hand">&nbsp;&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesconsulta = '<img src="../img/Boton_Imprimir.gif" onclick="imprimir()" style="cursor:hand">&nbsp;<img src="../img/Boton_Cancela_Guia.gif" style="cursor:hand" onClick="cancelarGuia()">&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();bloquear(false); ">';
	
	function paracelda(valor, tamano, alineacion){
		return '<input type="text" readonly="true" style=" width:'+tamano+'px;font:tahoma; font-size:9px; text-align:'+alineacion+'; font-weight:bold; border:none;background:none" value="'+valor+'" />';
	}
	
	function imprimir(){
		<?=$cpermiso->verificarPermiso(339,$_SESSION[IDUSUARIO]);?>;
		if(document.all.estado.innerHTML=='ENTREGADA'){
			window.open("imprimiretiquetaguia_acuse.php?tipo=1&codigo="+document.all.folioSeleccionado.innerHTML,"1","width=500,height=500");
		}else{
			<? 
				$s = "SELECT impetiquetasguias FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetasguias)."');";
				}else{
					$s = "SELECT impetiquetasguias FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
					$rxy = mysql_query($s,$l) or die($s);
					if(mysql_num_rows($rxy)>0){
						$fxy = mysql_fetch_object($rxy);
						echo "var met = new ActiveXObject('Impresion.Metodos');
						met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetasguias)."');";
					}
				}
			?>
			window.open("imprimiretiquetaguia.php?tipo=1&codigo="+document.all.folioSeleccionado.innerHTML,"1","width=500,height=500");
		}
	}
	function cambiarImpresora1(){
		<? 
			$s = "SELECT impetiquetaspaquetes FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)."');";
			}else{
				$s = "SELECT impetiquetaspaquetes FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)."');";
				}
			}
		?>
		window.open("imprimiretiquetapaquete.php?tipo=1&codigo="+document.all.folioSeleccionado.innerHTML,"2","width=500,height=500");
	}
	function cambiarImpresora2(){
		<? 
			$s = "SELECT impdefault FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impdefault)."');";
			}else{
				$s = "SELECT impdefault FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impdefault)."');";
				}
			}
		?>
	}
	
	function bloquear(valor){
		u = document.all;
		u.lstflete.disabled 		= valor;
		u.chocurre.disabled 		= valor;
		u.lstpago.disabled 			= valor;
		u.idremitente.readOnly 		= valor;
		u.iddestinatario.readOnly	= valor;
		
		u.idremitente.style.backgroundColor		= (valor)?"#d9e6f0":"";
		u.iddestinatario.style.backgroundColor	= (valor)?"#d9e6f0":"";
		
		u.chkemplaye.disabled 			= valor;
		u.chkbolsaempaque.disabled 	= valor;
		u.chkavisocelular.disabled 	= valor;
		u.chkvalordeclarado.disabled 	= valor;
		u.chkacuserecibo.disabled 		= valor;
		u.chkcod.disabled 				= valor;
		
		u.b_remitente.style.visibility = (valor)?"hidden":"visible";
		u.b_destinatario.style.visibility = (valor)?"hidden":"visible";
		u.b_remitente_dir.style.visibility = (valor)?"hidden":"visible";
		u.b_destinatario_dir.style.visibility = (valor)?"hidden":"visible";
		u.img_descuento.style.visibility = (valor)?"hidden":"visible";
		
	}
	
	
	//para cambiar descripciones convenios
	function solicitarDatosConv(){
		if(document.all.sucdestino_hidden.value!=""){
			consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idremitente="+u.idremitente.value
			+"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&idsucdestino="+u.sucdestino_hidden.value+
			"&valran="+Math.random());
		}
	}
	
	function paraConvenio(datos){
		var u = document.all;
		//alert(datos);
		valCon.setDatos(datos);
		
		if((u.idremitente.value!="" && u.lstflete.value==0) || (u.iddestinatario.value!="" && u.lstflete.value==1) || (u.idremitente.value!="" && u.iddestinatario.value != "")){
				/*alerta3("guia_consulta_conv.php?accion=1&idsucdestino="+u.sucdestino_hidden.value+"&fevaluacion="+u.folioevaluacion.value
				   +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(u.lstflete.value),"");*/
				consultaTexto('obtenerMecanciaConvenio',"guia_consulta_conv.php?accion=1&idsucdestino="+u.sucdestino_hidden.value+"&fevaluacion="+
                u.folioevaluacion.value+"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(u.lstflete.value)+"&rd="+Math.random());
		}
	}
	
	function obtenerMecanciaConvenio(datos){
		var u = document.all;
		u.creditodisponible.value = "";
		u.t_txtexcedente.value = "$ 0.00";
		datos = datos.replace(/&#209;/g,"Ñ");
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		u.t_txtexcedente.value = "$ "+numcredvar(objeto[0].excedente.toLocaleString());
		
		u.txtrestrinccion.value = u.txtrestrinccionh.value;
		u.txtead.value			= u.txteadh.value;
		
		if(valCon.getValorDeclarado(u.lstflete.value)!=null){
			var vdec = valCon.getValorDeclarado(u.lstflete.value);
			u.pc_porcada.value 		= (vdec.porcada!=null && vdec.porcada!="")?vdec.porcada:u.pc_porcadah.value;
			u.pc_costo.value 		= (vdec.costo!=null && vdec.costo!="")?vdec.costo:u.pc_costoh.value;
			u.pc_vdcostoextra.value = (vdec.costoextra!=null && vdec.costoextra!="")?vdec.costoextra:u.pc_vdcostoextrah.value;
			u.pc_vdlimite.value 	= (vdec.limite!=null && vdec.limite!="")?vdec.limite:u.pc_vdlimiteh.value;
		}else{
			u.pc_porcada.value 		= u.pc_porcadah.value;
			u.pc_costo.value 		= u.pc_costoh.value;
			u.pc_vdcostoextra.value = u.pc_vdcostoextrah.value;
			u.pc_vdlimite.value 	= u.pc_vdlimiteh.value;
		}
		
		var filas = objeto[0].mercancia;
		tabla1.setJsonData(filas);
		
		var importetotal = 0;
		//para convenio precio caja. x son modificables
		var cantidad_con_x = 0;
		var cantidad_sin_x = 0;
		for(var i=0; i<filas.length; i++){
			importetotal += parseFloat(filas[i].importe);
			if(filas[i].modificable=="X"){
				cantidad_con_x += parseFloat(filas[i].importe);
			}else{
				cantidad_sin_x += parseFloat(filas[i].importe);
			}
		}
		
		if(valCon.validaPrecioPorCaja(u.lstflete.value)>0){
			if(valCon.validaDescuentoSobreFlete(u.lstflete.value)>0 && cantidad_con_x>0){
				var totalmasdesc = cantidad_con_x*100/(100-valCon.validaDescuentoSobreFlete(u.lstflete.value));
				u.flete.value = "$ "+numcredvar((totalmasdesc + cantidad_sin_x).toLocaleString());
				u.t_txtdescuento1.value = valCon.validaDescuentoSobreFlete(u.lstflete.value);
				u.t_txtdescuento2.value = ((valCon.validaDescuentoSobreFlete(u.lstflete.value)/100)*totalmasdesc).toFixed(2);
				u.img_descuento.style.visibility="hidden";
			}else{
				u.flete.value = "$ "+numcredvar(importetotal.toLocaleString());
				u.t_txtdescuento1.value = "0 %";
				u.t_txtdescuento2.value = "$ 0.00";
				u.img_descuento.style.visibility="visible";
			}
		}else{
			if(valCon.validaDescuentoSobreFlete(u.lstflete.value)>0){
				var totalmasdesc = importetotal*100/(100-valCon.validaDescuentoSobreFlete(u.lstflete.value));
				u.flete.value = "$ "+numcredvar(totalmasdesc.toLocaleString());
				u.t_txtdescuento1.value = valCon.validaDescuentoSobreFlete(u.lstflete.value);
				u.t_txtdescuento2.value = ((valCon.validaDescuentoSobreFlete(u.lstflete.value)/100)*totalmasdesc).toFixed(2);
				u.img_descuento.style.visibility="hidden";
			}else{
				u.flete.value = "$ "+numcredvar(importetotal.toLocaleString());
				u.t_txtdescuento1.value = "0 %";
				u.t_txtdescuento2.value = "$ 0.00";
				u.img_descuento.style.visibility="visible";
			}
		}
		
		
		if(valCon.aplicaTarifaMinima(u.lstflete.value)>0){
			if(parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))<=parseFloat(valCon.aplicaTarifaMinima(u.lstflete.value))){
				u.flete.value = "$ "+numcredvar(valCon.aplicaTarifaMinima(u.lstflete.value).toString());
				u.t_txtdescuento1.value = "0 %";
				u.t_txtdescuento2.value = "$ 0.00";
				u.img_descuento.style.visibility="hidden";
			}
		}
		
		u.chkvalordeclarado.disabled	= false;
		if(u.desead.value!="1"){
			u.chocurre.disabled 		= false;
			u.t_txteadh2.value 				= "0";
		}
		if(u.desporcobrar.value!="1")
			u.lstflete.disabled 		= false;
		if(u.desrrecoleccion.value!="1")
			u.t_txtrecoleccionh.value	= "0";
			
		var msg="";
		
		if(cantidadVencida != 0){
			msg += "El cliente tiene <br>$ "+numcredvar(cantidadVencida.toString()) + " vencido.<br>";
			cantidadVencida=0;
		}
		
		if(u.lstflete.value==1){
			if(u.desead.value!="1" && u.desconvenio.value!="1" && valCon.restringEADDestinatario()){
				msg += "El destinatario tiene restringido el servicio E.A.D.<br>";
				u.txtead.value = 0;
				u.txtrestrinccion.value = "";
				u.chocurre.value=1;
				u.t_txtead.value = "$ 0.00";
				u.chocurre.disabled=true;
			}
			if(u.desconvenio.value!="1" && valCon.restringVDDestinatario()){
				msg += "El destinatario tiene restringido el servicio VALOR DECLARADO<br>";
				u.chkvalordeclarado.disabled=true;
				u.chkvalordeclarado.checked = false;
                u.txtdeclarado.value = "";
				u.txtdeclarado.style.backgroundColor = '#d9e6f0'
				u.txtdeclarado.readOnly = true;
			}
			if(u.desporcobrar.value!="1" && u.desconvenio.value!="1" && valCon.restringRXCDestinatario()){
				msg += "El destinatario tiene restringido el servicio RECIBO POR COBRAR<br>";
				u.lstflete.value = 0;
				u.lstflete.disabled = true;
			}
		}
		if(valCon.restringirDestinoEAD()){
			msg += "El destino seleccionado tiene restringida la entrega a domicilio<br>";
			u.txtead.value = 0;
			u.txtrestrinccion.value = "";
			u.chocurre.value = 1;
		}
		//alert(valCon.checarCredito(u.lstflete.value));
		if(valCon.checarCredito(u.lstflete.value) && valCon.validaCredito(u.lstflete.value,((u.lstflete.value==0)?<?=$_SESSION[IDSUCURSAL]?>:u.sucdestino_hidden.value))!='NO CREDITO'
			&& valCon.validaCredito(u.lstflete.value,((u.lstflete.value==0)?<?=$_SESSION[IDSUCURSAL]?>:u.sucdestino_hidden.value))==true){
			if(u.lstpago.value == 0){
				msg += "El cliente tiene Credito, se cambiara la condicion de pago<br>";
			}
			u.lstpago.value = 1;
			
			consultaTexto("respuestaCredito", "guia_consulta_conv.php?accion=2&idcliente="+((u.lstflete.value==0)?u.idremitente.value:u.iddestinatario.value));
		}else{
			u.creditodisponible.value = 0;
		}
		//alert(u.desconvenio.value+"-"+valCon.validaEADsucursal(valCon.validarConvenioAUsar(u.lstflete.value),u.sucdestino_hidden.value));
		//se agrego la validacion que tiene que ser un destino como sucursal
		if(u.subdestinos.value=='1' && u.desconvenio.value!="1" && valCon.validaEADsucursal(valCon.validarConvenioAUsar(u.lstflete.value),u.sucdestino_hidden.value)){
			u.t_txteadh2.value = "1";
		}
		
		//se agrego la validacion que tiene que ser un destino como sucursal
		if(u.origensubdestinos.value=='1' && u.desconvenio.value!="1" && u.desconvenio.value!="1" 
		   && valCon.validaRecsucursal(valCon.validarConvenioAUsar(u.lstflete.value),u.sucdestino_hidden.value))
			u.t_txtrecoleccionh.value = "1";
			
		if(u.iddestinatario.value!=""){
			if(valCon.validarDestRestEADF(((u.chocurre.value==1)?true:false))){
				msg += "El destino seleccionado no acepta EAD a personas fisicas sin convenio<br>";
				u.txtead.value = 0;
				u.txtrestrinccion.value = "";
				u.chocurre.value=1;
				u.t_txtead.value = "$ 0.00";
				u.chocurre.disabled=true;
				u.t_txtrecoleccionh.value = "1";
			}
		}
		if(msg!="")
			alerta3(msg,"¡Atencion!");
		
		var convenio 	= valCon.validarConvenioAUsar(u.lstflete.value);
		var cliconvenio = valCon.validarClienteConvenio(u.lstflete.value);
		var oridest 	= valCon.validarOrigenDestino(u.lstflete.value);
		var vendedor 	= valCon.validarVendedorConvenio(u.lstflete.value);
		
		u.convenioaplicado.value = (convenio>0)?convenio:0;
		u.clientedelconvenio.value = (cliconvenio>-1) ? cliconvenio : 0 ;
		u.sucursaldelconvenio.value = ((oridest>-1)? ((oridest==0)?<?=$_SESSION[IDSUCURSAL]?>:u.sucdestino_hidden.value) : 0 );
		u.nombrevendedor.value = (vendedor != -1) ? vendedor.split(",")[0] : "" ;
		u.idvendedor.value = (vendedor != -1) ? vendedor.split(",")[1] : "0" ;
		
		if(u.subdestinos.value=='1' && valCon.validarServiciosNoCobroGV(u.lstflete.value, 7,u.sucdestino_hidden.value)){
			u.t_txtead.value = "$ 0.00";
			u.t_txteadh.value = "$ 0.00";
			u.t_txteadh2.value = "$ 0.00";
		}
		
		if(u.origensubdestinos.value=='1' && valCon.validarServiciosNoCobroGV(u.lstflete.value, 8,u.idsucursalrecoleccion.value)){
			u.t_txtrecoleccion.value = "$ 0.00";
		}else{
			u.t_txtrecoleccion.value = "$ "+numcredvar(u.pc_recoleccion.value.toString());
		}
		
		calculartotales();
		document.all.idsguardar.innerHTML = botonesnuevo;
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
		consultaTexto('obtenerMecanciaConvenio',"guia_consulta_conv.php?accion=1&idsucdestino="+u.sucdestino_hidden.value
						  +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(u.lstflete.value)
						  +"&idmercancia="+tabla1.getSelectedRow().idmercancia+"&descripcion="+descripcion
						  +"&rd="+Math.random());
	}
	
	//funciones para cancelar
	function mostrarGuiasPCS(){
		abrirVentanaFija('../buscadores_generales/buscarGuiasDCS.php?funcion=cambiarPaginaGYS&estado=SUSTITUCION', 650, 450, 'ventana', 'Guias pendientes por sustituir')
	}
	
	function mostrarGuiasAPS(){
		abrirVentanaFija('../buscadores_generales/buscarGuiasDCS.php?funcion=cambiarPaginaGYS2&estado=AUTORIZADA PARA SUSTITUIR', 650, 450, 'ventana', 'Guias autorizadas para sustituir')
	}
	
	function cambiarPaginaGYS(guia){//GUIA Y SUSTITUCION
		document.location.href = "../guias/guia_vcs.php?function=cargarGuias&folioguia="+guia;
	}
	
	function cambiarPaginaGYS2(guia){//GUIA Y SUSTITUCION
		document.location.href = "../guias/guia_cargar_sustitucion.php?funcion=solicitarParaActivar('"+guia+"')&desde=1";
	}
	
	function mostrarGuiasPendientesCancelar(){
		abrirVentanaFija('../buscadores_generales/buscarGuiasGen.php?funcion=solicitarGuia&estado=AUTORIZACION PARA CANCELAR', 650, 450, 'ventana', 'Guias pendientes para cancelacion')
	}
	function buscarUnaGuia(folioguia){
		solicitarGuia(folioguia);
	}
	function solicitarGuia(folio){
		consulta("respuestaGuia","guia_consulta.php?accion=5&folio="+folio+"&rand="+Math.random());
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
			
			if(datos.getElementsByTagName('sustituciondeguia').item(0)!=null){
				var sustituciondeguia	= datos.getElementsByTagName('sustituciondeguia').item(0).firstChild.data;
				document.getElementById('sustitucionguia').innerHTML = sustituciondeguia;
			}
			if(datos.getElementsByTagName('unidadguia').item(0)!=null){
				var unidadguia			= datos.getElementsByTagName('unidadguia').item(0).firstChild.data;
				document.getElementById('unidadSubida').innerHTML = "Unidad: " + unidadguia;
			}
			if(datos.getElementsByTagName('danosfaltantes').item(0)!=null){
				var danosfaltantes			= datos.getElementsByTagName('danosfaltantes').item(0).firstChild.data;
				document.getElementById('danosfaltantes').innerHTML = danosfaltantes;
			}
			if(datos.getElementsByTagName('entregafaltantes').item(0)!=null){
				var entregafaltantes		= datos.getElementsByTagName('entregafaltantes').item(0).firstChild.data;
				document.getElementById('entregafaltantes').innerHTML = entregafaltantes;
			}
			if(datos.getElementsByTagName('fechacancelacion').item(0)!=null){
				var fechacancelacion		= datos.getElementsByTagName('fechacancelacion').item(0).firstChild.data;
				document.all.fechacancelacion.value = fechacancelacion;
			}
			
			var fecha					= datos.getElementsByTagName('fecha').item(0).firstChild.data;
			var fechaactual				= datos.getElementsByTagName('fechaactual').item(0).firstChild.data;
			var fechaentrega			= datos.getElementsByTagName('fechaentrega').item(0).firstChild.data;
			var factura					= datos.getElementsByTagName('factura').item(0).firstChild.data;
			var recibio					= datos.getElementsByTagName('recibio').item(0).firstChild.data;
			var estado					= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var tipoflete				= datos.getElementsByTagName('tipoflete').item(0).firstChild.data;
			var ocurre					= datos.getElementsByTagName('ocurre').item(0).firstChild.data;
			var idsucursalorigen		= datos.getElementsByTagName('idsucursalorigen').item(0).firstChild.data;
			var ndestino				= datos.getElementsByTagName('ndestino').item(0).firstChild.data;
			var nsucdestino				= datos.getElementsByTagName('nsucdestino').item(0).firstChild.data;
			var condicionpago			= datos.getElementsByTagName('condicionpago').item(0).firstChild.data;
			var idsucursaldestino		= datos.getElementsByTagName('idsucursaldestino').item(0).firstChild.data;
			var sucursalorigen		    = datos.getElementsByTagName('sucursalorigen').item(0).firstChild.data;
			
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
			
			document.all.folioSeleccionado.innerHTML = id;
			u.fecha.value				= fecha;
			u.estado.innerHTML			= estado;
			u.lstflete.value			= tipoflete;
			u.idsucursalorigen.value	= idsucursalorigen;
			u.chocurre.value			= ocurre;
			
			document.getElementById('destino').value			= ndestino;
			
			u.sucdestino_hidden.value = idsucursaldestino;
			
			u.fechaentrega.value 	= fechaentrega;

			u.factura.value 		= factura;
			u.recibio.value 		= recibio;
			
			u.nombresucursalorigen.innerHTML = sucursalorigen;
			
			u.sucdestino.value	= nsucdestino;
			u.lstpago.value	= condicionpago;
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
			u.txtrecoleccion.value = recoleccion;
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
		<?=$cpermiso->verificarPermiso(338,$_SESSION[IDUSUARIO]);?>;
		if(u.factura.value!="" && u.factura.value!=" "){
			alerta3("No se puede cancelar la guia por que ya esta facturada","¡ATENCION!");
			return false;
		}
		u = document.all;
		if((u.estado.innerHTML=='ALMACEN DESTINO' && u.lstflete.value == 1) || u.sustitucionguia.innerHTML.indexOf('SUSTITUCION')>-1){
			if("<?=$_SESSION[IDSUCURSAL]?>"!=u.sucdestino_hidden.value){
				alerta3("Para hacer una cancelación foranea debe estar en la sucursal destino de la guia","¡Atención!");
			}else{
				confirmar("¿Desea hacer una cancelacion Foranea?","¡ATENCION!","cancelarForanea()");
			}
		}else if(u.estado.innerHTML=='AUTORIZACION PARA CANCELAR'){
			if("<?=$_SESSION[IDSUCURSAL]?>"!=u.idsucursalorigen.value){
				alerta3("Para hacer una cancelación local debe estar en la sucursal donde se registro la guia","¡Atención!");
			}else{
				abrirVentanaFija('cancelarfinal.php?folioguia='+document.all.folioSeleccionado.innerHTML, 450, 250, 'ventana', 'Motivo de Cancelacion');
			}
		}else{
			if(u.fechaactual.value == u.fecha.value && u.estado.innerHTML=='ALMACEN ORIGEN' && u.lstflete.value){
				if("<?=$_SESSION[IDSUCURSAL]?>"!=u.idsucursalorigen.value){
					alerta3("Para hacer una cancelación local debe estar en la sucursal donde se registro la guia","¡Atención!");
				}else{
					abrirVentanaFija('motivoscancelacion.php', 400, 220, 'ventana', 'Motivos de Cancelación')
				}
			}else if(u.fechaactual.value != u.fecha.value){
				alerta("Imposible cancelar Guias con fecha posterior a la fecha de Emisión","¡Atención!","fecha");
			}
		}
	}
	
	function cancelarForanea(){
		document.location.href = "guia_cancsust.php?funcion=solicitarGuiaCan('"+document.all.folioSeleccionado.innerHTML+"');"
	}
	
	function mensajeCancelarFinal(){
		confirmar("¿Desea cancelar la guia?","¡Atencion!","cancelarFinal()","");
	}
	function preguntarSiCancelar(){
		confirmar("¿Seguro desea enviar la guia a Pendientes por Cancelar?","¡Atencion!","guardarCancelacion()","");
	}
	function guardarCancelacion(){
		consulta("respuestaCancelacion","guia_consulta.php?accion=6&folio="+document.all.folioSeleccionado.innerHTML
		+"&motivo="+document.all.motivocancelacion.value+"&rand="+Math.random());
	}
	function respuestaCancelacion(){
		document.all.estado.innerHTML = 'AUTORIZACION PARA CANCELAR';
		alerta("La guia ha sido enviada a pendientes por cancelar", "¡Atencion!", "fecha");
	}
	function cancelarFinal(){
		consulta("respuestaCancelarFinal","guia_consulta.php?accion=7&folio="+document.all.folioSeleccionado.innerHTML
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
		so 	= <?=$_SESSION[IDSUCURSAL]?>;
		abrirVentanaFija('../buscadores_generales/buscarEvaluacionGen.php?funcion=pedirDatosEvaluacion&tipo=evaluacion&sucorigen='+so, 650, 450, 'Evaluaciones', 'Busqueda');
	}
	//funciones limpiar  
	function limpiar_remitente(){
		u = document.all;
		u.idremitente.value 	= "";
		u.rem_rfc.value 		= "";
		u.rem_cliente.value 	= "";
		u.rem_numero.value		= "";
		u.celda_rem_calle.innerHTML = '<input name="rem_calle" readonly="true" type="text" style="width:170px" class="textoSB" /><input type="hidden" name="rem_direcciones">';
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
		u.celda_des_calle.innerHTML = '<input name="des_calle" readonly="true" type="text" style="width:170px;" class="textoSB" /><input type="hidden" name="des_direcciones">'
		
		u.des_colonia.value		= "";
		u.des_poblacion.value	= "";
		u.des_telefono.value	= "";
		u.des_personamoral.value= "";
	}
	function limpiar_evaluacion(){
			u = document.all;
			
			document.getElementById('folioSeleccionado').innerHTML = "&nbsp;";	
			document.getElementById('unidadSubida').innerHTML = "&nbsp;";
			document.getElementById('sustitucionguia').innerHTML = "&nbsp;";
			document.getElementById('danosfaltantes').innerHTML = "";
			document.getElementById('entregafaltantes').innerHTML = "";
			document.all.fechacancelacion.value = "";
			document.getElementById('idsguardar').innerHTML = botonesnuevo;
			document.all.idsucursalorigen.value="<?=$_SESSION[IDSUCURSAL]?>";
            document.all.fechaactual.value="<?=date("d/m/Y")?>";
			document.getElementById('nombresucursalorigen').innerHTML = "&nbsp;";
			tabla1.clear();
			
			u.lstpago.value				= 0;
			u.pc_porcada.value			= "";
			u.pc_costo.value			= "";
			u.pc_porcadah.value			= "";
			u.pc_costoh.value			= "";
			u.factura.value				= "";
			u.fechaentrega.value		= "";
			u.recibio.value				= "";
			u.convenioaplicado.value	= "";
			u.folioevaluacion.value		= "";
			u.paraconveniotxt.value		= "0";
			u.frontera.value			= "";
			u.subdestinos.value			= "";
			u.origensubdestinos.value	= "";
			u.idsucursalrecoleccion.value= "";
			
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
			u.chkemplaye.readOnly 		= false;
			u.chkbolsaempaque.readOnly 	= false;
			u.chkavisocelular.readOnly 	= false;
			u.chkvalordeclarado.readOnly= false;
			u.chkacuserecibo.readOnly 	= false;
			u.chkcod.readOnly 			= false;
			
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
			
			var excedente = parseFloat(u.t_txtexcedente.value.replace("$ ","").replace(/,/g,""));
		
			if(u.t_txteadh2.value=="0"){
				if( ( (parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))+excedente) *0.10)<parseFloat(u.pc_ead.value)){
					
						u.t_txteadh.value = "$ "+numcredvar(u.pc_ead.value);
				}else{
					valoread = ( ( parseFloat(u.flete.value.replace("$ ","").replace(/,/g,"") ) + excedente)*0.10)
								 
					u.t_txteadh.value = "$ "+numcredvar(valoread.toFixed(2).toLocaleString());
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
			
			if(u.pc_recoleccion.value != "" && u.pc_recoleccion.value != " " && u.t_txtrecoleccion.value != "$ 0.00" && u.t_txtrecoleccionh.value=="0"){
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
			
			if(u.txtdeclarado.value!="" && u.txtdeclarado.value!="$ 0.00"){
				var lodeclarado 	= parseFloat(u.txtdeclarado.value.replace("$ ","").replace(/,/,""));
				var pcvdporcada 	= parseFloat(u.pc_porcada.value);
				var pcvdcosto 		= parseFloat(u.pc_costo.value);
				var pcvdcostoextra 	= parseFloat(u.pc_vdcostoextra.value);
				var pcvdlimite 		= parseFloat(u.pc_vdlimite.value);
				var pcvddentro		= 0;
				var pcvdrestante 	= 0;
				var pcvdacumulado 	= 0;
				var pcvdexcedido	= 0;
				if(lodeclarado>pcvdlimite){
					pcvdacumulado  += Math.ceil(pcvdlimite/pcvdporcada)*pcvdcosto;
					pcvdrestante 	= lodeclarado-pcvdlimite;
					pcvdacumulado  += Math.ceil(pcvdrestante/pcvdporcada)*pcvdcostoextra;
				}else{
					pcvdacumulado  += Math.ceil(lodeclarado/pcvdporcada)*pcvdcosto;
				}
				u.t_txtseguro.value = "$ "+numcredvar(pcvdacumulado.toLocaleString());
			}else{
				<?
				//obtener el costo del seguro configurado
				$s = "SELECT costo FROM configuradorservicios WHERE servicio = 6";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);ç
				
				?>
				u.t_txtseguro.value = "$ <?=number_format($f->costo,2)?>";
			}
			
			/*if(u.txtdeclarado.value!="" && u.txtdeclarado.value!="0"){
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
			*/
			valorcombustible = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,""))+parseFloat(u.t_txtexcedente.value.replace("$ ","").replace(/,/g,"")))*(parseFloat(u.pc_tarifacombustible.value)/100))*100)/100;
			//alert(u.flete.value+"-"+u.t_txtdescuento2.value+"*"+u.pc_tarifacombustible.value);
			//alert(valorcombustible);
			u.t_txtcombustible.value = "$ "+numcredvar(valorcombustible.toLocaleString());
		
			u.t_txtcombustible.value = (u.t_txtcombustible.value=="")?"$ 0.00":u.t_txtcombustible.value;
			
			u.t_txtrecoleccion.value = (u.t_txtrecoleccion.value=="")?"$ 0.00":u.t_txtrecoleccion.value;
			
			u.t_txtdescuento1.value = (u.t_txtdescuento1.value=="")?"0 %":u.t_txtdescuento1.value;
			u.t_txtdescuento2.value = (u.t_txtdescuento2.value=="")?"$ 0.00":u.t_txtdescuento2.value;
		
		if(u.chkemplaye.disabled == true && document.all.txtemplaye.value != "$ 0.00"){
			document.all.txtemplayeh.value = document.all.txtemplaye.value;
		}
		if(u.chkbolsaempaque.disabled == true && document.all.txtbolsaempaque2.value != "$ 0.00"){
			document.all.txtbolsaempaque3h.value = parseFloat(document.all.txtbolsaempaque2.value.replace('$ ', '').replace(/,/g,''))/
			parseFloat(document.all.txtbolsaempaque1.value.replace('$ ', '').replace(/,/g,''));
		}
		
		if(valCon!=undefined && valCon.validarServiciosNoCobroGV(u.lstflete.value, 2,'<?=$_SESSION[IDSUCURSAL]?>')){
			if(u.txtemplaye.value!=""){
				u.txtemplaye.value = "$ 0.00";
			}
		}else{
			if(u.txtemplaye.value!=""){
				document.all.txtemplaye.value = document.all.txtemplayeh.value;
			}
		}
		
		if(valCon!=undefined && valCon.validarServiciosNoCobroGV(u.lstflete.value, 1,'<?=$_SESSION[IDSUCURSAL]?>')){
			if(u.txtbolsaempaque2.value!=""){
				u.txtbolsaempaque2.value = "$ 0.00";
			}
		}else{
			if(u.txtbolsaempaque2.value!=""){
				document.all.txtbolsaempaque2.value= '$ '+
				numcredvar(
					(parseFloat((document.all.txtbolsaempaque3h.value=='')?'0':document.all.txtbolsaempaque3h.value.replace('$ ', '').replace(/,/g,''))*
					parseFloat(u.txtbolsaempaque1.value)).toLocaleString()
				);
			}
		}
		
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
	function pedirDatosEvaluacion(idevaluacion){
		if(<?=$_SESSION[IDSUCURSAL]?>!=""){
			sucursalorigen 	= <?=$_SESSION[IDSUCURSAL]?>;
			//alerta3("guia_consulta.php?accion=1&folio="+idevaluacion+"&idsucorigen="+sucursalorigen+"&valrandom="+Math.random());
			consulta("devolverDatosEvaluacion", "guia_consulta.php?accion=1&folio="+idevaluacion+"&idsucorigen="+sucursalorigen+"&valrandom="+Math.random());
		}else{
			alerta("Seleccione una sucursal de Origen","¡Atencion!","fecha");
		}
	}
	function devolverDatosEvaluacion(datos){
		//alert(datos);
		//datos.responseXML();
		//alert(datos);
		bloquear(false);
		limpiar_evaluacion();
		limpiar_remitente();
		limpiar_destinatario();
		u.guiaguardadav.value = "0";
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var folioevaluacion		= datos.getElementsByTagName('folioevaluacion').item(0).firstChild.data;
			var nuevofolioguia		= datos.getElementsByTagName('nuevofolioguia').item(0).firstChild.data;
			var bloquearocurre		= datos.getElementsByTagName('bloquearocurre').item(0).firstChild.data;
			var frontera			= datos.getElementsByTagName('frontera').item(0).firstChild.data;
			var subdestinos			= datos.getElementsByTagName('subdestinos').item(0).firstChild.data;
			var idsucursalrecoleccion = datos.getElementsByTagName('idsucursalrecoleccion').item(0).firstChild.data;
			var origensubdestinos	= datos.getElementsByTagName('origensubdestinos').item(0).firstChild.data;
			var nombreorigen		= datos.getElementsByTagName('nombreorigen').item(0).firstChild.data;
			
			var vestado 			= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var destino 			= datos.getElementsByTagName('ndestino').item(0).firstChild.data;
			var npobdes				= datos.getElementsByTagName('npobdes').item(0).firstChild.data;
			var iddestino 			= datos.getElementsByTagName('iddestino').item(0).firstChild.data;
			var sucursal			= datos.getElementsByTagName('nsucursal').item(0).firstChild.data;
			var idsucursal 			= datos.getElementsByTagName('idsucursal').item(0).firstChild.data;
			var npobdestino 		= datos.getElementsByTagName('npobdestino').item(0).firstChild.data;
			var cantidadbolsa		= datos.getElementsByTagName('cantidadbolsa').item(0).firstChild.data;
			var bolsaempaque		= datos.getElementsByTagName('bolsaempaque').item(0).firstChild.data;
			var emplaye				= datos.getElementsByTagName('emplaye').item(0).firstChild.data;
			var sbolsaempaque		= datos.getElementsByTagName('sbolsaempaque').item(0).firstChild.data;
			var semplaye			= datos.getElementsByTagName('semplaye').item(0).firstChild.data;
			var totalbolsaempaque	= datos.getElementsByTagName('totalbolsaempaque').item(0).firstChild.data;
			var totalemplaye		= datos.getElementsByTagName('totalemplaye').item(0).firstChild.data;
			var ocu					= datos.getElementsByTagName('ocu').item(0).firstChild.data;
			var ead					= datos.getElementsByTagName('ead').item(0).firstChild.data;
			var pagominimocheque	= datos.getElementsByTagName('pfp_pagominimocheques').item(0).firstChild.data;
			
			var avisocelular		= datos.getElementsByTagName('avisocelular').item(0).firstChild.data;
			var acuserecibo			= datos.getElementsByTagName('acuserecibo').item(0).firstChild.data;
			var cod					= datos.getElementsByTagName('cod').item(0).firstChild.data;
			var restrinccion		= datos.getElementsByTagName('restrincciones').item(0).firstChild.data;
			var restrinccion2		= datos.getElementsByTagName('restr2').item(0).firstChild.data;
			var restringirporcobrar = datos.getElementsByTagName('restringirporcobrar').item(0).firstChild.data;
			//para totales
			var pt_ead				= datos.getElementsByTagName('pt_ead').item(0).firstChild.data;
			var pt_recoleccion		= datos.getElementsByTagName('pt_recoleccion').item(0).firstChild.data;
			var pt_iva				= datos.getElementsByTagName('pt_iva').item(0).firstChild.data;
			var pt_ivaretenido		= datos.getElementsByTagName('pt_ivaretenido').item(0).firstChild.data;
			var por_combustible		= datos.getElementsByTagName('por_combustible').item(0).firstChild.data;
			var max_descuento		= datos.getElementsByTagName('max_des').item(0).firstChild.data;
			var vporcada			= datos.getElementsByTagName('por_cada').item(0).firstChild.data;
			var vscosto				= datos.getElementsByTagName('scosto').item(0).firstChild.data;
			var vdlimite			= datos.getElementsByTagName('vd_limite').item(0).firstChild.data;
			var vdcostoextra		= datos.getElementsByTagName('vd_costoextra').item(0).firstChild.data;
			var erecoleccion		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;
			var pesominimodesc		= datos.getElementsByTagName('pesominimodesc').item(0).firstChild.data;
			var restringiread		= datos.getElementsByTagName('restringiread').item(0).firstChild.data;
			
			var desead				= datos.getElementsByTagName('desead').item(0).firstChild.data;
			var desrrecoleccion		= datos.getElementsByTagName('desrrecoleccion').item(0).firstChild.data;
			var desporcobrar		= datos.getElementsByTagName('desporcobrar').item(0).firstChild.data;
			var desconvenio			= datos.getElementsByTagName('desconvenio').item(0).firstChild.data;
			
			u.desead.value			= desead;
			u.desrrecoleccion.value	= desrrecoleccion;
			u.desporcobrar.value	= desporcobrar;
			u.desconvenio.value		= desconvenio;
			u.frontera.value		= frontera;
			u.subdestinos.value		= subdestinos;
			u.idsucursalrecoleccion.value = idsucursalrecoleccion;
			u.origensubdestinos.value=origensubdestinos;
			u.nombresucursalorigen.innerHTML=nombreorigen;
			
			u.bloquearocurre.value = bloquearocurre;
			
			if(bloquearocurre==1){
				u.chocurre.value=1;
				u.chocurre.disabled=true;
			}else{
				u.chocurre.disabled=false;
			}
			
			var msg = "";
			if(desead==1){
				msg += "El destino no cuenta con servicio de EAD<br>";
				u.chocurre.value 	= 1;
				u.chocurre.disabled = true;
			}
			//if(desrrecoleccion==1){
			//	msg += "El destino no cuenta con servicio de recoleccion<br>";
			//}
			if(desporcobrar==1){
				msg += "El destino no cuenta con servicio por cobrar<br>";
				u.lstflete.value 	= 0;
				u.lstflete.disabled = true;
			}
			if(msg!="")
			alerta3(msg,"¡Atencion!");
			
			
			//para datagrid importe
			importe_tipo		= datos.getElementsByTagName('tipototales').item(0).firstChild.data;
			valor_totalimporte	= datos.getElementsByTagName('valor_totalimporte').item(0).firstChild.data;
			document.all.folioSeleccionado.innerHTML = nuevofolioguia;
			
			u.folioevaluacion.value	= folioevaluacion;
			u.estado.innerHTML = vestado;
			
			document.getElementById('destino').value = destino;
			u.destino_hidden.value = iddestino;
			u.npobdes.value = npobdes;
			document.getElementById('destino').poblacion = npobdestino;
			u.sucdestino_hidden.value = idsucursal;
			u.sucdestino.value = sucursal;
			u.txtemplayeh.value = "$ "+numcredvar((totalemplaye==0 || totalemplaye=="")?semplaye:totalemplaye);
			u.txtbolsaempaque1h.value = cantidadbolsa;
			u.txtbolsaempaque2h.value = "$ "+numcredvar(totalbolsaempaque);
			u.txtbolsaempaque3h.value = "$ "+numcredvar(semplaye);
			u.txtocu.value = ocu;
			u.txtead.value = ead;
			u.txteadh.value = ead;
			u.pagominimocheque.value = pagominimocheque;
			u.restringiread.value = restringiread;
			
			if(restringiread==1){
				u.chocurre.value=1;
			}
			/*if(restringirporcobrar==1){
				alerta("El destino no cuenta con servicio por cobrar","¡Atencion!","idremitente");
				u.lstflete.value = 0;
				u.lstflete.onchange = Function('solicitarDatosConv() if(this.value==1){ alerta("El destino no cuenta con servicio por cobrar","¡Atencion!","idremitente");  u.lstflete.value = 0;} calculartotales();');
			}else{
				u.lstflete.onclick = '';
				u.lstflete.onchange = Function('solicitarDatosConv(); calculartotales()');
			}*/
			
			u.pc_ead.value					= pt_ead;
			u.pc_recoleccion.value			= pt_recoleccion;
			u.pc_tarifacombustible.value	= por_combustible;
			u.pc_maximodescuento.value		= max_descuento;
			u.pc_porcada.value				= vporcada;
			u.pc_costo.value				= vscosto;
			u.pc_porcadah.value				= vporcada;
			u.pc_costoh.value				= vscosto;
			u.pc_vdlimite.value				= vdlimite;
			u.pc_vdcostoextra.value			= vdcostoextra;
			u.pc_vdlimiteh.value			= vdlimite;
			u.pc_vdcostoextrah.value		= vdcostoextra;
			u.pc_iva.value					= pt_iva;
			u.pc_ivaretenido.value			= pt_ivaretenido;
			u.pc_pesominimodesc.value		= pesominimodesc;
			u.t_txtexcedente.value			= "$ 0.00";
			
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
				u.txtrecoleccionh.value		= erecoleccion;	
				u.txtrecoleccion.value		= erecoleccion;	
			}else{
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
					
					objetox.idmercancia 	= idmercancia;
					objetox.cantidad 		= cantidad;
					objetox.descripcion 	= descripcion;
					objetox.contenido 		= contenido;
					objetox.peso 			= peso;
					objetox.volumen 		= volumen;
					objetox.importe 		= importe;
					objetox.modificable 	= " ";
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
			//calculo de totales			
			calculartotales();
			document.all.idsguardar.innerHTML = botonesnuevo;
		}else{
			alerta("Evaluación no encontrada","¡Alerta!","idremitente");
		}
		u.idremitente.focus();
	}
	function devolverRemitente(valor){
		var u = document.all;
		if(u.folioevaluacion.value==""){
			alerta3("Porfavor seleccione la evaluacion para poder agregar el remitente", "¡Atencion!");
			u.idremitente.value = "";
		}else{
			limpiar_remitente();
			document.all.idremitente.value = valor;
			consulta("mostrarRemitente", "guia_consulta.php?accion=2&idcliente="+valor+"&valrandom="+Math.random());
		}
	}
	function mostrarRemitente(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;
			cantidadVencida = datos.getElementsByTagName('vencido').item(0).firstChild.data;
			
			u.rem_rfc.value 			= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			u.rem_cliente.value 		= datos.getElementsByTagName('ncliente').item(0).firstChild.data;
			u.rem_personamoral.value	= datos.getElementsByTagName('personamoral').item(0).firstChild.data;
			v_celular 					= datos.getElementsByTagName('celular').item(0).firstChild.data;
			
			u.txtavisocelular2h.value 	= (v_celular!="")?v_celular:"";
			if(endir==1){
				document.all.celda_rem_calle.innerHTML ='<input name="rem_calle" readonly="true" type="text" '
				+' style="width:170px" class="textoSB" /><input type="hidden" name="rem_direcciones">';
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
			/*if(u.desconvenio.value!="1"){
				consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idremitente="+u.idremitente.value
						  +"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&fevaluacion="+u.folioevaluacion.value
						  +"&idsucdestino="+u.sucdestino_hidden.value+"&valran="+Math.random());
			}*/
			solicitarDatosConv();
		}else{
			alerta("El Cliente no existe","","idremitente");
		}
		calculartotales();
	}
	function devolverDestinatario(valor){
		var u = document.all;
		if(u.folioevaluacion.value==""){
			alerta3("Porfavor seleccione la evaluacion para poder agregar el destinatario", "¡Atencion!");
			u.iddestinatario.value = "";
		}else{
			limpiar_destinatario();
			document.all.iddestinatario.value = valor;
			consulta("mostrarDestinatario", "guia_consulta.php?accion=2&idcliente="+valor+"&poblacion="+u.npobdes.value+"&valrandom="+Math.random());
		}
	}
	function mostrarDestinatario(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;
			cantidadVencida = datos.getElementsByTagName('vencido').item(0).firstChild.data;
			u.des_rfc.value 			= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			u.des_cliente.value 		= datos.getElementsByTagName('ncliente').item(0).firstChild.data;
			u.des_personamoral.value	= datos.getElementsByTagName('personamoral').item(0).firstChild.data;
			if(endir==1){
				document.all.celda_des_calle.innerHTML ='<input name="des_calle" readonly="true" type="text" '
				+' style="width:170px" class="textoSB" /><input type="hidden" name="des_direcciones">';
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
			/*if(u.desconvenio.value!="1"){
			consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idremitente="+u.idremitente.value
						  +"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&fevaluacion="+u.folioevaluacion.value
						  +"&idsucdestino="+u.sucdestino_hidden.value+"&valran="+Math.random());
			}*/
			solicitarDatosConv();
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
			consulta("mostrarDestino", "guia_consulta.php?accion=3&iddestino="+u.destino_hidden.value);
		}
	}
	
	function mostrarDestino(datos){
		var encon 		= datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var iddestino	= datos.getElementsByTagName('iddestino').item(0).firstChild.data;
			var descripcion	= datos.getElementsByTagName('descripcion').item(0).firstChild.data;
			var poblacion	= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		}else{
			var iddestino	= "";
			var descripcion	= "";
			var poblacion	= "";
			alerta("No se encontro la sucursal destino","¡Atencion!","destino");
		}
		u.sucdestino.value 			= descripcion;
		document.getElementById('destino').poblacion 		= poblacion;
		u.sucdestino_hidden.value 	= iddestino;
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
		u.t_txtdescuento1.style.backgroundColor = "#d9e6f0";
		calculartotales();
	}
	
	function validarDatos(){
		<?=$cpermiso->verificarPermiso(337,$_SESSION[IDUSUARIO]);?>;
		var u = document.all;
		if(u.bloquearocurre.value==1 && u.chocurre.value==1){
			alerta('El destino seleccionado no puede hacer entregas ocurre','¡Atención!',"chocurre");
			return false;
		}else if(u.guiaguardadav.value=="1"){
			alerta('Esta guia ya ha sido guardada','¡Atención!',"iddestinatario");
			return false;
		}else if(u.folioevaluacion.value==""){
			alerta('Debe seleccionar una guia','¡Atención!',"idremitente");
			return false;
		}else if(u.idremitente.value==""){
			alerta('Debe capturar el remitente','¡Atención!',"idremitente");
			return false;
		}else if(u.rem_cliente.value==""){
			alerta('Proporcione un remitente valido','¡Atención!',"idremitente");
			return false;
		}else if(u.rem_direcciones.value==""){
			alerta('El remitente no tiene dirección','¡Atención!',"idremitente");
			return false;
		}else if(u.iddestinatario.value==""){
			alerta('Debe capturar el destinatario','¡Atención!',"iddestinatario");
			return false;
		}else if(u.des_cliente.value==""){
			alerta('Proporcione un destinatario valido','¡Atención!',"idremitente");
			return false;
		}else if(u.des_direcciones.value==""){
			alerta('El destinatario no tiene dirección','¡Atención!',"idremitente");
			return false;
		}else if(u.frontera.value=="1" && u.txtobservaciones.value==""){
			abrirVentanaFija('datosFronteras.php', 450, 318, 'ventana', 'Ingrese los Datos de Frontera');
			return false;
		}else if(u.subdestinos.value=="0" && u.chocurre.value=="1"){
			alerta('No puede enviar una guia ocurre a un destino que no es sucursal','¡Atención!',"chocurre");
			return false;
		}else if(u.des_poblacion.value != u.npobdes.value){
			alerta("Direccion del destinatario no concuerda con el destino, debe corregir dirección, destino, o enviar ocurre","¡Alerta!","iddestinatario");	
		}
		return true;
	}
	
	function ejecutarSubmit(){
		var u = document.all;
		
		if(u.txtdeclarado.value!="" && u.txtdeclarado.value!="$ 0.00"){
			confirmar("¿Deséa declarar: <br>"+u.txtdeclarado.value+"?","¡Atencion!","despuesEjecutar()","");	
		}else{
			despuesEjecutar();
		}
	}
	
	function despuesEjecutar(){
		if(document.all.lstflete.value == 0 && document.all.lstpago.value == 0)
			confirmar("¿Desea guardar la Guia?","¡Atencion!","preguntaFactura()","");	
		else
			confirmar("¿Desea guardar la Guia?","¡Atencion!","registrarGuia()","");	
	}
	
	function preguntaFactura(){
		confirmar("¿Desea Facturar?","¡Atencion!","facturar()","registrarGuia()");
	}
	
	function facturar(){
		var_facturar = true;
		registrarGuia();
	}
	
	function registrarGuia(){
		var u = document.all;
		
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
		var recoleccion				= u.txtrecoleccion.value;
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
		var nc 						= u.nc.value.replace("$ ","").replace(/,/g,"");
		var nc_folio				= u.nc_folio.value.replace("$ ","").replace(/,/g,"");
		var trasferencia			= u.transferencia.value.replace("$ ","").replace(/,/g,"");
		
		//valores agregados para reportesmaestros
		var convenioaplicado	= u.convenioaplicado.value;
		var clienteconvenio		= u.clientedelconvenio.value;
		var sucursaldelconvenio	= u.sucursaldelconvenio.value;
		var nombrevendedor		= u.nombrevendedor.value;
		var idvendedor			= u.idvendedor.value;
		
		consulta("guiaGuardada","guia_consulta.php?accion=4&evaluacion="+evaluacion+"&fecha="+fecha+"&tipoflete="+tipoflete+"&ocurre="+ocurre
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
				 +"&convenioaplicado="+convenioaplicado+"&nc="+nc+"&nc_folio="+nc_folio+"&ran="+Math.random());
	}
	
	function guiaGuardada(datos){
		var guardado= datos.getElementsByTagName('guardado').item(0).firstChild.data;
		if(guardado==1){
			folio = datos.getElementsByTagName('folioguia').item(0).firstChild.data;
			if(var_facturar){
				info("La guia ha sido guardada, se procedera con la facturacion","¡Atencion!");
				setTimeout("mostrarFacturacion()","1000");
			}else{
				confirmar("La guia ha sido Guardada ¿Desea limpiar los datos?","¡Atencion!","limpiar_evaluacion();limpiar_remitente();limpiar_destinatario();mostrarEvaluaciones();","");
				document.all.guiaguardadav.value = 1;
				document.all.folioSeleccionado.innerHTML = folio;
				document.all.estado.innerHTML = "ALMACEN ORIGEN";
			}
				document.all.idsguardar.innerHTML = botonesconsulta;
		}else{
			cons = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al guardar //" + cons,"¡Atencion!","idremitente");
		}
	}
	
	function mostrarFacturacion(){
		document.location.href = "../facturacion/Facturacion.php?folio="+document.all.folioSeleccionado.innerHTML+"&condicionpago="+document.all.lstpago.value+"&funcion=pedirCliente("+((u.lstflete.value==0)?u.idremitente.value:u.iddestinatario.value)+")";
	}
	
	function valcreditodisponible(){
		if(u.lstpago.value==1){
			if(valCon.validaCredito(u.lstflete.value,((u.lstflete.value==0)?<?=$_SESSION[IDSUCURSAL]?>:u.sucdestino_hidden.value))=='NO CREDITO'){
				alerta3("No tiene un crédito dado de alta","¡ATENCION!");
				u.lstpago.value = 0;
				return false;
			}else if(valCon.validaCredito(u.lstflete.value,((u.lstflete.value==0)?<?=$_SESSION[IDSUCURSAL]?>:u.sucdestino_hidden.value))==false){
				alerta3("No cuenta con servicio de crédito en esta sucursal","¡ATENCION!");
				u.lstpago.value = 0;
				return false;
			}
			//alert(u.creditodisponible.value+"-"+parseFloat(u.t_txttotal.value.replace("$ ","").replace(",","")));
			if(parseFloat(u.creditodisponible.value)<parseFloat(u.t_txttotal.value.replace("$ ","").replace(",",""))){
				alerta3("Credito insuficiente","¡Atencion!");
				return false;
			}else{
				return true;
			}
		}
		return true;
	}
	
	function ventanaDiv(pagina){
		abrirVentanaFija(pagina, 644, 600, 'ventana', 'Busqueda')
	}
	
	function mostrarformapago(){
		var u=document.all;
	abrirVentanaFija('formapago.php?total='+u.t_txttotal.value+'&cliente='+u.clientedelconvenio.value, 600, 400, 'ventana', 'Forma de Pago');
	}
	
	function mostrarInformacionExtra(){
		<?=$cpermiso->verificarPermiso(336,$_SESSION[IDUSUARIO]);?>;
		if(document.all.folioSeleccionado.innerHTML==""){
			alerta3("No ha seleccionado una guia para mostrar el detalle","¡ATENCION!");
		}else{
			abrirVentanaFija('informacionextra.php?tipo=1&folio='+document.all.folioSeleccionado.innerHTML, 660, 418, 'ventana', 'Detalle');
		}
	}
	
</script>
<style type="text/css">
	.extra-data ul li{ display:block; position:relative; margin:5px; color:#000;}
</style>
</head>

<body>
<input name=DetalleGrip type=hidden id=DetalleGrip value="<?=$DetalleGrip ?>">
<input type="hidden" name="folioevaluacion" value="">
                  <input type="hidden" name="guiaguardadav" value="">
                  <input type="hidden" name="idsucursalorigen" value="<?=$_SESSION[IDSUCURSAL]?>">
                  <input type="hidden" name="fechaactual" value="<?=date("d/m/Y")?>">
                  <input type="hidden" name="restringiread" value="">
                  <input type="hidden" style="width:15px" name="convenioaplicado" value="">
                  <input type="hidden" style="width:15px" name="clientedelconvenio" value="">
                  <input type="hidden" style="width:15px" name="sucursaldelconvenio" value="">
                  <input type="hidden" style="width:15px" name="nombrevendedor" value="">
                  <input type="hidden" style="width:15px" name="idvendedor" value="">
                  <input type="hidden" name="creditodisponible" value="">
                  <input type="hidden" name="bloquearocurre" value="">
                <input type="hidden" style="width:15px" name="frontera" value="">
                <input type="hidden" style="width:15px" name="idsucursalrecoleccion" value="">
                <input type="hidden" style="width:15px" name="subdestinos" value="">
                <input type="hidden" style="width:15px" name="origensubdestinos" value="">
                
<div id="folioSeleccionado" style="width:800px; margin:10px; color:#F00000; font-size:15px; font-weight:bold">&nbsp;
</div>
<div class="content" style="width:830px">
  <div class="det-guia"> <span class="date">Fecha:<strong>
  		<?
			$s = "select date_format(current_date, '%d/%m/%Y') as fecha";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
		?>
  <input name="fecha" class="textoSB" readonly="true" type="text" id="fecha" value="<?=$f->fecha ?>" style="width:70px; color:#FFF" />
  </strong>
  </span>
    <div>
      <table width="254">
        <tbody>
          <tr>
            <th width="92">Tipo Flete:</th>
            <th width="150"><select name="lstflete" class="text" onChange="solicitarDatosConv(); calculartotales()">
                  <option value="0">Pagado</option>
                  <option value="1">Por Cobrar</option>
              </select></th>
          </tr>
          <tr>
            <th width="92">Entrega:</th>
            <th width="150"><select name="chocurre" id="chocurre" class="text" onchange="solicitarDatosConv(); if(document.all.restringiread.value==1){alerta('El destino tiene restringida la Entrega a Domicilio','¡Atencion!','chocurre'); this.value=1;}else{if(this.value==0){document.all.t_txtead.value = document.all.t_txteadh.value}else{document.all.t_txtead.value = '$ 0.00';}} calculartotales();">
                <option value="0">EAD</option>
                <option value="1">Ocurre</option>
              </select></th>
          </tr>
          <tr>
            <th width="92">Cond. Pago:</th width="150">
            <th><select name="lstpago" id="lstpago" class="text">
                <option value="0">Contado</option>
                <option value="1">Credito</option>
            </select></th>
          </tr>
          <tr>
            <th width="92">Origen:</th>
            <th width="150"><strong id="nombresucursalorigen"></strong></th>
          </tr>
          <tr>
            <th width="92">Destino:
            <input type="hidden" name="destino_hidden" />
       	    <input type="hidden" name="npobdes" /></th>
            <th width="150"><strong><input type="text" name="destino" readonly="true" id="destino" style="width:150px; color:#FFF" class="textoSB" /></strong></th>
          </tr>
          <tr>
            <th width="92">Suc. Destino:<input type="hidden" name="sucdestino_hidden" /></th width="150">
            <th><strong><input name="sucdestino" type="text" id="sucdestino" style="width:150px; color:#FFF" readonly value="" poblacion="" class="textoSB" /></strong></th>
          </tr>
        </tbody>
      </table>
    <a href="#" class="detalles-guia" onClick="mostrarInformacionExtra()">Detalles Guia</a> </div>
  </div>
  <div class="remi-desti">
    <div class="remi"> <img src="../css_ne/style/arrow.jpg" style="position:absolute; left:260px;"/>
      <div class="title">Remitente</div>
      <span>No. Cliente:<input type="hidden" name="rem_personamoral"></span>
      <input name="idremitente" type="text" onKeyPress="if(event.keyCode==13 && this.readOnly==false){ devolverRemitente(this.value)}else{return solonumeros(event)}" class="text" value="" />
      <input type="button" id="b_remitente" value=" " class="srch-btn" title="Buscar" 
      onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverRemitente', 625, 418, 'ventana', 'Busqueda')"/>
      <input type="button" id="b_remitente_dir" value=" " class="add-btn" title="Agregar" onClick="if(document.all.idremitente.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','idremitente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverRemitente('+document.all.idremitente.value+')&idcliente='+document.all.idremitente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}"/>
      <br />
      <span>R.F.C.:</span><strong><input name="rem_rfc" readonly="true" type="text" style=" width:170px;" class="textoSB"/>
       </strong><br />
      <span>Cliente:</span><strong><input name="rem_cliente" readonly="true" type="text" style="width:170px;" class="textoSB" /></strong><br />
      <span>Calle:</span><strong id="celda_rem_calle">
      <input name="rem_calle" readonly="true" type="text" style="width:170px;" class="textoSB" /><input type="hidden" name="rem_direcciones">
      </strong><br />
      <div style="float:left; width:270px">
      <span>N&uacute;mero:</span><strong style="width:80px;"><input name="rem_numero" type="text" readonly="true" style=" width:70px;" class="textoSB" /></strong>
      <span>CP:</span><strong style="width:60px;"><input name="rem_cp" readonly="true" type="text" style="width:55px;" class="textoSB" /></strong><br />
      </div>
      <span>Colonia:<!--<input name="rem_poblacion" readonly="true" type="hidden" />-->
      </span><strong><input name="rem_colonia" type="text" readonly="true" style="width:170px" class="textoSB"/></strong><br />
	  <span>Población:</span><strong><input name="rem_poblacion" type="text" readonly="true" style="width:170px;" class="textoSB" /></strong>
      <span>Tel&eacute;fono:</span><strong><input name="rem_telefono" type="text" readonly="true" style="width:170px;" class="textoSB" /></strong>
      <br />
    </div>
    <div class="desti">
      <div class="title">Destinatario</div>
      <span>No. Cliente:<input type="hidden" name="des_personamoral">
      <input type="hidden" name="paraconveniotxt" value="0">
      </span>
      <input name="iddestinatario" onKeyPress="if(event.keyCode==13 && this.readOnly==false){devolverDestinatario(this.value)}else{return solonumeros(event)}" type="text" class="text" />
      <input type="button" id="b_destinatario" value=" " class="srch-btn" title="Buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverDestinatario', 625, 418, 'ventana', 'Busqueda')"/>
      <input type="button" id="b_destinatario_dir" value=" " class="add-btn" title="Agregar" onClick="if(document.all.iddestinatario.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','iddestinatario') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverDestinatario('+document.all.iddestinatario.value+')&idcliente='+document.all.iddestinatario.value, 460, 395, 'ventana', 'DATOS DIRECCION')}"/>
      <br />
      <span>R.F.C.:</span><strong> <input name="des_rfc" type="text" readonly="true" id="rrfc22" style="width:170px;" class="textoSB" /></strong><br />
      <span>Cliente:</span><strong><input name="des_cliente" readonly="true" type="text" style="width:170px" class="textoSB"/>
      </strong><br />
      <span>Calle:</span><strong id="celda_des_calle"><input name="des_calle" readonly="true" type="text" style="width:170px" class="textoSB"/>
                                    <input type="hidden" name="des_direcciones">
      </strong><br />
      
      <div style="float:left; width:270px">
      <span>N&uacute;mero:</span><strong style="width:80px;">
      <input name="des_numero" type="text" readonly="true" style="width:70px;" class="textoSB" />
      </strong>
      <span>CP:</span><strong style="width:60px;"><input name="des_cp" type="text" readonly="true" style="width:55px;" class="textoSB" /></strong><br />
      </div>
      <span>Colonia:
      <!--<input name="des_poblacion" readonly="true" type="hidden" />-->
      </span><strong><input name="des_colonia" type="text" readonly="true" style="width:170px;" class="textoSB" /></strong><br />
	  <span>Población:</span><strong><input name="des_poblacion" type="text" readonly="true" style="width:170px" class="textoSB" /></strong>
      <span>Tel&eacute;fono:</span><strong><input name="des_telefono" type="text" readonly="true" style="width:170px" class="textoSB" /></strong><br />
    </div>
  </div>
  <div class="description">
  	<div class="grid-detallado" style="text-align:right">
    <table border="0" cellpadding="0" cellspacing="0" id="tablaconteva">
      </table>
    </div>
    <div class="tiempo-entrega">
      <div class="title">Tiempo de Entrega</div>
      <span>Ocurre:</span><strong><input name="txtocu" readonly="true" type="text" style="width:20px; color:#FFF" class="textoSB" />
      </strong> <span>EAD:</span><strong>
      <input name="txtead" readonly="true" type="text" style="width:20px; color:#FFF" class="textoSB" />
      <input name="txteadh" type="hidden" />
      </strong> 
      <span style="width:100px">Restricciones:</span><strong style="width:150px; margin-left:10px;">
      <textarea name="txtrestrinccion" readonly style="width:140px; color:#FFF" class="textoSB"></textarea>
                            <input name="txtrestrinccionh" type="hidden" />
    </strong> </div>
  </div>
  <div class="extra-data">
    <ul>
      <li>Total de Paquetes: <input name="totalpaquetes" type="text" readonly="true" style="font-size:9px; width:40px" class="textoSB"/></li>
      <li>Total de Peso: 
      <input name="totalpeso" type="text" readonly="true" style="font-size:9px; width:40px" class="textoSB" /> Kg</li>
      <li>Total de Volumen: <input name="totalvolumen" type="text" readonly="true" style="font-size:9px; width:60px" class="textoSB" /></li>
      <li>
      
        <input name="chkemplaye" type="checkbox" style="width:8px; height:8px" value="SI" onclick="if(!this.checked){document.all.txtemplaye.value='';}else{
        if(valCon!=undefined && valCon.validarServiciosNoCobroGV(u.lstflete.value, 2,'<?=$_SESSION[IDSUCURSAL]?>')){document.all.txtemplaye.value='$ 0.00';}else{document.all.txtemplaye.value = document.all.txtemplayeh.value}} calculartotales();" />
        Emplaye
        <input name="txtemplaye" readonly="true" type="text" style="font-size:9px; text-align:right; width:30px" class="textoSB" />
                              <input name="txtemplayeh" type="hidden" />
      </li>
      <li>
        <input name="chkbolsaempaque" type="checkbox" style="width:8px; height:8px" value="SI" onclick="if(!this.checked){document.all.txtbolsaempaque1.value = ''; document.all.txtbolsaempaque2.value = ''; document.all.txtbolsaempaque1.readOnly=true; document.all.txtbolsaempaque1.style.backgroundColor='#d9e6f0';}else{ if(document.all.txtbolsaempaque1h.value=='' || document.all.txtbolsaempaque1h.value=='0'){document.all.txtbolsaempaque1.readOnly=false; document.all.txtbolsaempaque1.style.backgroundColor='#FFFFFF';}else{document.all.txtbolsaempaque1.value = document.all.txtbolsaempaque1h.value; document.all.txtbolsaempaque2.value = document.all.txtbolsaempaque2h.value;}} calculartotales();" />
      Envase: <strong style="width:60px;">
      <input name="txtbolsaempaque1" readonly="true" onBlur="if(this.readOnly==false){calculartotales();}" onKeyPress="if(this.readOnly==false && event.keyCode==13){ if(valCon!=undefined && valCon.validarServiciosNoCobroGV(u.lstflete.value, 1,'<?=$_SESSION[IDSUCURSAL]?>')){ document.all.txtbolsaempaque2.value='$ 0.00'; }else{ document.all.txtbolsaempaque2.value='$ '+numcredvar((parseFloat((document.all.txtbolsaempaque3h.value=='')?'0':document.all.txtbolsaempaque3h.value.replace('$ ', '').replace(/,/g,''))*parseFloat(this.value)).toLocaleString());calculartotales();}}else{return solonumeros(event);}" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="1" />
      <input name="txtbolsaempaque2" readonly="true" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" size="6" />
      <input name="txtbolsaempaque1h" type="hidden" />
    <input name="txtbolsaempaque2h" type="hidden" />
    <input name="txtbolsaempaque3h" type="hidden" />
      </strong></li>
      <li><input name="chkavisocelular" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtavisocelular2.readOnly=true; document.all.txtavisocelular2.style.backgroundColor='#d9e6f0'; document.all.txtavisocelular2.value='';document.all.txtavisocelular1.value=''; }else{document.all.txtavisocelular1.value=document.all.txtavisocelular1h.value;document.all.txtavisocelular2.readOnly=false; document.all.txtavisocelular2.style.backgroundColor='#FFFFFF'; document.all.txtavisocelular2.value=document.all.txtavisocelular2h.value;document.all.txtavisocelular2.focus();}  calculartotales();" /> 
        Aviso Celular: <strong>
        <input name="txtavisocelular1" readonly="true" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right; width:40px" />
        <input name="txtavisocelular2" readonly="true" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; width:40px" value="<?=$rrfc ?>" />
        <input name="txtavisocelular1h" type="hidden" />
                            <input name="txtavisocelular2h" type="hidden" />
      </strong></li>
      <li><input name="chkvalordeclarado" type="checkbox" style="width:8px; height:8px" value="SI"
               onClick="if(!this.checked){document.all.txtdeclarado.value='';document.all.txtdeclarado.readOnly=true; document.all.txtdeclarado.style.backgroundColor='#d9e6f0'; document.all.txtdeclarado.readOnly=true;}else{document.all.txtdeclarado.readOnly=false; document.all.txtdeclarado.style.backgroundColor='#FFFFFF'; document.all.txtdeclarado.readOnly=false;document.all.txtdeclarado.focus();} calculartotales();" /> 
        Valor Declarado: <strong>
               <?
			  	$s = "SELECT maxvalordeclaradoguia FROM configuradorgeneral";
				$rmvd = mysql_query($s,$l) or die($s);
				$fmvd = mysql_fetch_object($rmvd);
			  ?>
                              <input name="txtdeclarado" type="text" readonly="true" onBlur="if(this.readOnly==false){this.value=this.value.replace('$ ','').replace(/,/,'');  if(this.value==''){this.value='$ 0.00';}else{ if(parseFloat(this.value) > <?=$fmvd->maxvalordeclaradoguia?>){this.value = <?=$fmvd->maxvalordeclaradoguia?>; alerta3('El maximo valor declarado permitido es <?=$fmvd->maxvalordeclaradoguia?>', '¡Atencion!');} this.value='$ '+numcredvar(this.value); calculartotales(); }}" onKeyPress="if(this.readOnly==false){ if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/,'')); calculartotales();}else{return solonumeros(event);}} " style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" onfocus="this.value = this.value.replace('$ ','').replace(/,/,''); this.select();" value="<?=$rrfc ?>" size="10" />
      </strong></li>
      <li><input name="chkacuserecibo" onClick="if(!this.checked){document.all.txtacuserecibo.value='';}else{document.all.txtacuserecibo.value=document.all.txtacusereciboh.value;} calculartotales();" type="checkbox" style="width:8px; height:9px" value="SI" />Acuse Recibo: <strong>
      <input readonly="true" name="txtacuserecibo" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtacusereciboh" type="hidden" />
      </strong></li>
      <li><input name="chkcod" onClick="if(!this.checked){document.all.txtcod.value='';}else{document.all.txtcod.value=document.all.txtcodh.value;} calculartotales();" type="checkbox" style="width:8px; height:8px" value="SI" />COD: <strong>
      <input readonly="true" name="txtcod" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtcodh" type="hidden" />
      </strong></li>
      <li>Recolecci&oacute;n: <strong>
      <input readonly="true" name="txtrecoleccion" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtrecoleccionh" type="hidden" />
      </strong></li>
    </ul>
  </div>
  <div class="totales">
    <table style="width:200px; margin-left:10px; margin-top:2px; font-size:11px;">
      <thead>
      </thead>
      <tbody>
        <tr>
          <th width="79">Flete:</th>
          <th width="109">
          	<input name="flete" readonly="true" type="text" style="width:72px; font-size:9px; text-align:right" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>Desc:</th>
          <th>
          	<input readonly="true" name="t_txtdescuento1" type="text" style="font-size:9px; text-align:right; width:20px;" class="textoSB" value="<?=$rrfc ?>" onKeyPress="if(event.keyCode==13 && this.readOnly==false){ if(parseFloat(this.value)>parseFloat(document.all.pc_maximodescuento.value)){ this.value=document.all.pc_maximodescuento.value; alerta('El maximo descuento permitido es '+document.all.pc_maximodescuento.value+' %','¡Atencion!','t_txtdescuento1')} calcularDescuento()}else{return solonumeros(event);}" />
            <input name="t_txtdescuento2" type="text" readonly="true" style="font-size:9px; text-align:right; width:50px" class="textoSB" />
                        <img id="img_descuento" src="../img/update.gif" onClick="if(validarDescuento()){ abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=permitirDescuento', 370, 500, 'ventana', 'Inicio de Sesión Secundaria');}" style="cursor:hand">
          </th>
        </tr>
        <tr>
          <th>EAD:<input name="t_txteadh" type="hidden" />
                      <input name="t_txteadh2" type="hidden" /></th>
          <th><input readonly="true" name="t_txtead" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB" /></th>
        </tr>
        <tr>
          <th>Recolecci&oacute;n:<input name="t_txtrecoleccionh" type="hidden" /></th>
          <th>
          <input readonly="true" name="t_txtrecoleccion" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>Seguro:</th>
          <th>
          <input readonly="true" name="t_txtseguro" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB"/>
          </th>
        </tr>
        <tr>
          <th>Otros:</th>
          <th>
          <input name="t_txtotros" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>Excedente:</th>
          <th><input name="t_txtexcedente" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB"/></th>
        </tr>
        <tr>
          <th>Combustible:</th>
          <th>
          <input name="t_txtcombustible" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>Subtotal:</th>
          <th><input name="t_txtsubtotal" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px" class="textoSB" /></th>
        </tr>
        <tr>
          <th>IVA:</th>
          <th>
          <input name="t_txtiva" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>IVA Ret.:</th>
          <th><input name="t_txtivaretenido" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px" class="textoSB" /></th>
        </tr>
        <tr>
          <th>Total:</th>
          <th><strong style="color:#F00"><input name="t_txttotal" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px" class="textoSB" /></strong></th>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="pc_ead">
                  <input type="hidden" name="pc_recoleccion">
                  
                  <input type="hidden" name="pc_porcada">
                  <input type="hidden" name="pc_costo">
                  <input type="hidden" name="pc_porcadah">
                  <input type="hidden" name="pc_costoh">
                  
                  <input type="hidden" name="pc_vdlimite">
                  <input type="hidden" name="pc_vdcostoextra">
                  <input type="hidden" name="pc_vdlimiteh">
                  <input type="hidden" name="pc_vdcostoextrah">
                  
                  <input type="hidden" name="pc_tarifacombustible">
                  <input type="hidden" name="pc_iva">
                  <input type="hidden" name="pc_ivaretenido">
                  <input type="hidden" name="pc_maximodescuento">
                  <input type="hidden" name="pc_pesominimodesc">
                  <input type="hidden" name="desead">
                  <input type="hidden" name="desrrecoleccion">
                  <input type="hidden" name="desporcobrar">
                  <input type="hidden" name="desconvenio">    
                  
                  <!-- otra parte -->
                  <input type="hidden" value="0" name="pagoregistrado">
                        <input type="hidden" value="" name="efectivo">
                        <input type="hidden" value="" name="cheque">
                        <input type="hidden" value="" name="ncheque">
                        <input type="hidden" value="" name="banco">
                        <input type="hidden" value="" name="tarjeta">
                        <input type="hidden" value="" name="transferencia">
                        <input type="hidden" value="" name="pagominimocheque">
                        <input type="hidden" value="" name="nc">
                        <input type="hidden" value="" name="nc_folio">
                        <input type="hidden" name="motivocancelacion" />          
  </div>
  <div class="observaciones"> 
  	<strong style="width:400px"><span id="estado" style="width:250px;"></span><span id="unidadSubida" style="width:140px"></span></strong>
    <strong style="width:400px"><span id="sustitucionguia" style="width:395px;"></span></strong>
    <strong style="width:400px; margin-left:5px;" id="danosfaltantes"></strong> 
    <strong style="width:400px; margin-left:5px;" id="entregafaltantes"></strong> 
    <span style="width:390px">Observaciones: <strong style="color:#555; float:none; display:inline;">
    <textarea name="txtobservaciones" class="textoSB" readonly="readonly" rows="3" style="width:390px; font-size:9px; font:tahoma; text-transform:uppercase" onDblClick="if(document.all.folioevaluacion.value==''){ alerta3('Porfavor seleccione la evaluacion para agregar observaciones', '¡Atencion!'); }else{ abrirVentanaFija('datosFronteras.php?datos='+this.value, 450, 318, 'ventana', 'Datos Frontera') }"></textarea>
    </strong></span> <span>Recibi&oacute;:</span>
    <strong><input type="text" name="recibio" readonly="true" style="text-transform:uppercase; width:290px" class="textoSB" ></strong>
    <span>Fecha entrega:</span>
    <strong><input type="text" name="fechaentrega" readonly="true" style="text-transform:uppercase;width:290px " class="textoSB" ></strong>
    <span>Factura:</span><strong>
    <input type="text" name="factura" readonly="true" style="text-transform:uppercase; width:290px;" class="textoSB" />
    </strong>
    <span>Fecha Cancelacion</span><strong>
    <input type="text" name="fechacancelacion" id="fechacancelacion" style="text-transform:uppercase; width:290px;" class="textoSB" />
    </strong> </div>
    
    <div id="idsguardar" style="text-align:center; margin:10px; float:left; width:820px;">
    <img src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="if(validarDatos() && valcreditodisponible()){ if(document.all.lstflete.value==0 && document.all.lstpago.value==0){ mostrarformapago(); }else{ ejecutarSubmit(); } };">&nbsp;&nbsp; <img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">
    </div>
    
</div>
</body>
</html>