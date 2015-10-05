<?
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	window.onload = function(){
		mens.iniciar('../javascript',false);
		obtenerGeneral();
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","traspasarCreditoConCre_con.php?accion=1"+"&random="+Math.random());
	}
	
	function mostrarGeneral(datos){
		var row = datos.split(",");
		u.folio.value = row[0];
		u.fecha.value = row[1];
	}
	
	function obtenerGuia(guia){
		u.guia.value = guia;
		consultaTexto("mostrarGuia","traspasarCreditoConCre_con.php?accion=4&guia="+guia+"&random="+Math.random());
	}
	
	function obtenerGuiaEnter(guia){
		consultaTexto("mostrarGuia","traspasarCreditoConCre_con.php?accion=4&guia="+guia+"&random="+Math.random());
	}
	
	function mostrarGuia(datos){
		if(datos.indexOf("no tiene credito")>-1){
			var r = datos.split(",");
			mens.show("A","El cliente "+r[1]+" no cuenta con crédito","¡Atención!","guia");
			return false;
		}
		var obj = eval(datos);
		if(obj.encontro=='NO'){
			mens.show("A","El folio de la Guia no fue encontrado","¡Atención!","guia");
			u.importe.value			= "";
			u.remitente.value		= "";
			u.destinatario.value	= "";
			u.origen.value			= "";
			u.destino.value			= "";
		}else{
			if(obj.cambiable=='NO'){
				mens.show("A","El cliente de la guia no tiene suficiente credito disponible para realizar el cambio","¡Atención!","guia");
				u.importe.value			= "";
				u.remitente.value		= "";
				u.destinatario.value	= "";
				u.origen.value			= "";
				u.destino.value			= "";
			}else{
				var obj = eval(convertirValoresJson(datos));
				u.importe.value			= obj.datos.importe;
				u.remitente.value		= obj.datos.remitente;
				u.destinatario.value	= obj.datos.destinatario;
				u.iddestinatario.value	= obj.datos.iddestinatario;
				u.origen.value			= obj.datos.origen;
				u.destino.value			= obj.datos.destino;
			}
		}		
	}
	
	function guardar(){
		if(u.guia.value == ""){
			mens.show("A","Debe capturar Guia","¡Atención!","guia");
		}else{
			if(u.importe.value == ""){
				mens.show("A","Debe capturar una Guia valida para realizar el cambio","¡Atención!","guia");
				return false;
			}
			
			if(u.iddestinatario.value == ""){
				mens.show("A","Debe seleccionar un destinatario","¡Atención!","iddestinatario");
				return false;
			}
			
			u.btnguardar.style.visibility = "hidden";
			var arr = new Array();
			arr[0] = u.fecha.value;
			arr[1] = u.guia.value;			
			arr[2] = u.importe.value;
			arr[3] = "";/*u.remitente.value*/
			arr[4] = "";/*u.destinatario.value*/
			arr[5] = u.origen.value;
			arr[6] = u.destino.value;
			arr[7] = u.iddestinatario.value;
			consultaTexto("registro","traspasarCreditoConCre_con.php?accion=2&arre="+arr+"&random="+Math.random());
		}
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			u.folio.value = row[1];
			mens.show("I","Los datos han sido guardados correctamente","");
			u.btnguardar.style.visibility = "visible";
		}else if(datos.indexOf('El cliente no tiene')>-1){
			mens.show("A",datos,"¡Atención!");
			u.btnguardar.style.visibility = "visible";
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
			u.btnguardar.style.visibility = "visible";
		}
	}
	
	function obtenerTraspaso(folio){
		consultaTexto("mostrarTraspaso","traspasarCreditoConCre_con.php?accion=3&traspaso="+folio+"&random="+Math.random());
	}
	function obtenerTraspasoEnter(folio){
		consultaTexto("mostrarTraspaso","traspasarCreditoConCre_con.php?accion=3&traspaso="+folio+"&random="+Math.random());
	}
	function mostrarTraspaso(datos){
		if(datos.indexOf("no encontro")>-1){
			mens.show("A","El folio de Traspaso de crédito no existe","¡Atención!");
			limpiar();
		}else{
			var obj = eval(convertirValoresJson(datos));
			u.fecha.value 			= obj[0].fecha;
			u.guia.value			= obj[0].guia;
			u.importe.value			= obj[0].importe;
			u.remitente.value		= obj[0].remitente;
			u.destinatario.value	= obj[0].destinatario;
			u.origen.value			= obj[0].origen;
			u.destino.value			= obj[0].destino;
		}		
	}
	
	function limpiar(){
		u.guia.value		= "";
		u.importe.value		= "";
		u.remitente.value	= "";
		u.destinatario.value= "";
		u.origen.value		= "";
		u.destino.value		= "";		
		obtenerGeneral();
	}
	
	function validarCaja(e,obj,tipo){
		tecla = (u) ? e.keyCode : e.which;
    	if((tecla==8 || tecla==46) && document.getElementById(obj).value==""){			
			if(tipo=="2"){
				u.importe.value			= "";
				u.remitente.value		= "";
				u.destinatario.value	= "";
				u.origen.value			= "";
				u.destino.value			= "";
			}
		}
	}
	
	function traerAlcliente(cliente){
		//alert(cliente);
		consultaTexto("cargarCliente","traspasarCreditoConCre_con.php?accion=5&cliente="+cliente+"&random="+Math.random());
		ocultarBuscador();
	}
	
	function cargarCliente(datos){
		var obj = eval(datos);
		u.destinatario.value	= obj.ncliente;
		u.iddestinatario.value	= obj.id;
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:205px;	/* Width of box */
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
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="614" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">TRASPASAR CREDITO</td>
    </tr>
    <tr>
      <td><table width="601" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>Folio:</td>
          <td><input name="folio" type="text" class="Tablas" id="folio" maxlength="10" style="width:60px" onkeypress="if(event.keyCode==13){ obtenerTraspasoEnter(this.value);}else{return solonumeros(event);}" />
            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarTraspasoCreditoConCre.php?funcion=obtenerTraspaso', 600, 450, 'ventana', 'Busqueda')" /></td>
          <td>&nbsp;</td>
          <td>Fecha:</td>
          <td><input name="fecha" type="text" id="fecha" class="Tablas" style="width:100px; background-color:#FFFF99" readonly="" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="60">Guia:</td>
          <td width="202"><label>
            <input name="guia" type="text" class="Tablas" id="guia" maxlength="13" style="width:100px" onkeypress="if(event.keyCode==13){obtenerGuiaEnter(this.value);}" onkeyup="validarCaja(event,this.name,2)" />
            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('buscarGuiasTraspasoConCre.php?funcion=obtenerGuia', 600, 450, 'ventana', 'Busqueda')" /></label></td>
          <td width="7">&nbsp;</td>
          <td width="73">Importe:</td>
          <td width="227"><input name="importe" type="text" id="importe" class="Tablas" style="width:100px; background-color:#FFFF99;" readonly="" /></td>
          <td width="32">&nbsp;</td>
        </tr>
        <tr>
          <td>Remitente:</td>
          <td><input name="remitente" type="text" id="remitente" class="Tablas" style="width:200px; background-color:#FFFF99"  readonly="" /></td>
          <td>&nbsp;</td>
          <td>Destinatario:</td>
          <td><input name="iddestinatario" type="text" id="iddestinatario" class="Tablas" style="width:50px;" onkeypress="if(event.keyCode==13){ traerAlcliente(this.value); }" />
          <input name="destinatario" type="text" id="destinatario" class="Tablas" style="width:150px; background-color:#FFFF99"  readonly=""/></td>
          <td>
         <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" 
         style="cursor:pointer" onclick="mostrarBuscador();  tipoClienteBuscado='D';" />
          </td>
        </tr>
        <tr>
          <td>Origen:</td>
          <td><input name="origen" type="text" id="origen" class="Tablas" style="width:200px; background-color:#FFFF99"  readonly="" /></td>
          <td>&nbsp;</td>
          <td>Destino:</td>
          <td><input name="destino" type="text" id="destino" class="Tablas" style="width:200px; background-color:#FFFF99"  readonly="" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6">
          	<input type="hidden" name="nuevodestinatario" />
          </td>
          </tr>
        <tr>
          <td></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><table width="150" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td><div id="btnguardar" class="ebtn_Traspasar" onclick="guardar()"></div></td>
              <td align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
<?
	$raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
?>