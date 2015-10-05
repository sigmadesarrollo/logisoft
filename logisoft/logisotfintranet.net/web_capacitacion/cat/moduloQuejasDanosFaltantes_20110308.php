<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
var u = document.all;
	
	window.onload = function(){
		<? if($_GET[mostrarvalores]==1){?>
			obtenerModulosQuejas(<?=$_GET[id]?>);
		<? }else{?>
			obtenerGenerales();
		<? } ?>
		
		if('<?=$_GET[indice] ?>' > 0){
			info('Se han capturado los datos de la guia faltante<br>Se continua con la siguiente', '');
		}
		
		if('<?=$_GET[guia] ?>'!=""){
			obtenerGuiaBusqueda('<?=$_GET[guia] ?>');
		}
	}
	function obtenerGenerales(){
		consultaTexto("mostrarGenerales","moduloQuejasDanosFaltantes_Con.php?accion=1&sid="+Math.random());
	}

	function mostrarGenerales(datos){
		var obj 				= eval(convertirValoresJson(datos));
		u.folio.value 			= obj.principal.folio;
		u.fecha.value 			= obj.principal.fecha;
		u.responsable.value 	= obj.responsable.id;
		u.responsableb.value	= obj.responsable.nombre;
		u.idsucursal.value		= '<?=$_SESSION[IDSUCURSAL] ?>';
		u.sucursal.value		= obj.sucursal.descripcion;
	}

	function obtenerGuiaBusqueda(guia){
		consultaTexto("mostrarGuiaBusqueda","moduloQuejasDanosFaltantes_Con.php?accion=2&guia="+guia+"&sid="+Math.random());
	}
	
	function mostrarGuiaBusqueda(datos){
		if(datos.indexOf("ya existe")>-1){
			alerta('La Guía capturada ya fue reportada','¡Atención!','guia');
			u.guia.value = "";
			u.guiad.value = "";
			return false;
		}
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.guia.value = obj[0].id;
			u.guiad.value = obj[0].estado;
		}else{
			alerta('El # Guía no existe','¡Atención!','guia');
			u.guia.value = "";
			u.guiad.value = "";
		}
	}

function Validar(){
	<?=$cpermiso->verificarPermiso(303,$_SESSION[IDUSUARIO]);?>
	if(u.sucursal.value=="" || u.idsucursal.value==""){
		alerta('Debe capturar Sucursal','¡Atención!','sucursal');
		return false;
	}
	if(u.guia.value==""){
		alerta('Debe capturar Guia','¡Atención!','guia');
		return false;
	}
	if(u.nombre.value==""){
		alerta('Debe capturar Nombre','¡Atención!','nombre');
		return false;
	}
	if(u.observaciones.value==""){
		alerta('Debe capturar Observaciones','¡Atención!','observaciones');
		return false;
	}
			if(u.accion.value==''){
				u.accion.value = "grabar";
			}else if(document.getElementById('accion').value!=""){
				u.accion.value="modificar";
			}
		consultaTexto("resultado","moduloQuejasDanosFaltantes_Con.php?accion=3&folio="+u.folio.value
		+"&estado="+u.estado.innerHTML
		+"&guia="+u.guia.value
		+"&cliente="+u.cliente.value
		+"&remitente_cliente="+u.remitente_cliente.value
		+"&nombre="+u.nombre.value
		+"&observaciones="+u.observaciones.value
		+"&relacionembarque="+u.relacionembarque.value
		+"&copiafactura="+u.copiafactura.value
		+"&confirmacopiafactura="+u.confirmacopiafactura.value
		+"&comentariogerente="+u.comentariogerente.value
		+"&confirmacomentariogerente="+u.confirmacomentariogerente.value
		+"&cartareclamacion="+u.cartareclamacion.value
		+"&reportedanosyfaltantes="+u.reportedanosyfaltantes.value
		+"&confirmareportedanosyfaltantes="+u.confirmareportedanosyfaltantes.value
		+"&responsable="+u.responsable.value
		+"&tipo="+u.accion.value
		+"&sid="+Math.random());	
}

function resultado(datos){
	if(datos.indexOf("ok")>-1){
		if(u.tipo.value=="faltante" || u.tipo.value=="FALTANTE"){
			parent.mostrarQueja('<?=$_GET[valor] ?>');			
		}else{
			var x = datos.split(",");
			u.estado.innerHTML=x[1];
			if(u.estado.innerHTML=="REVISION COMITE"){
				document.all.botones_tabla.innerHTML="<table width='313' border='0' align='right' cellpadding='0' cellspacing='0'><tr><td width='1'></td> <td width='70'><div class='ebtn_guardar' onClick='Validar()'> </div></td> <td width='76'><div class='ebtn_Procede' onClick='ValidarEstado(6)'> </div></td><td width='94'><div class='ebtn_No_Procede' onClick='ValidarEstado(7)'></div></td><? if($_GET[mostrarvalores]!=1){?> <td width='72'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td><? } ?></tr></table>";
			}else if(u.estado.innerHTML=="ESPERA FACTURA"){
				document.all.botones_tabla.innerHTML="<table width='150' border='0' align='right' cellpadding='0' cellspacing='0'> <tr><td width='76'><div class='ebtn_Programar_Pagos' onClick='ValidarEstado(8)' ></div></td> <? if($_GET[mostrarvalores]!=1){?> <td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div> </td><? } ?>  </tr></table>";
			}else if(u.estado.innerHTML=="VIA LEGAL"){
			document.all.botones_tabla.innerHTML="<table width='159' height='30' border='0' align='right' cellpadding='0' cellspacing='0'><tr><td width='85'><div class='ebtn_cerrar' onClick='ValidarEstado(10)'></div></td><? if($_GET[mostrarvalores]!=1){?><td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td><? }?></tr></table>";
			}else{
				document.all.botones_tabla.innerHTML="<? if($_GET[mostrarvalores]!=1){?><table width='150' border='0' align='right' cellpadding='0' cellspacing='0'> <tr> <td width='76'>&nbsp;</td>  <td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div> </td> </tr><? }?></table>";
			}
		}
	}else{
		alerta3('Hubo un error al guardar '+datos,'¡Atención!');
	}
}


function obtenerCliente(id){
	consultaTexto("mostrarCliente","moduloQuejasDanosFaltantes_Con.php?accion=4&id="+id+"&sid="+Math.random());
}

function mostrarCliente(datos){
	if(datos!=0){
		var obj = eval(convertirValoresJson(datos));
		u.cliente.value 	= obj[0].id;
		u.cliented.value 	= obj[0].nombre;
	}
}

function obtenerResponsable(id){
	consultaTexto("mostrarResponsable","moduloQuejasDanosFaltantes_Con.php?accion=5&id="+id+"&sid="+Math.random());
}

function mostrarResponsable(datos){
	if(datos!=0){
		var obj = eval(datos);
		u.responsable.value 	= obj[0].id;
		u.responsableb.value 	= obj[0].nombre;
	}
}

function ValidarEstado(accion){		
	if(accion==6){
		//ESTADO PROCEDE
		<?=$cpermiso->verificarPermiso("384",$_SESSION[IDUSUARIO]);?>
		document.all.botones_tabla.innerHTML="<table width='290' height='30' border='0' align='right' cellpadding='0' cellspacing='0'>  <tr><td width='85'><div class='ebtn_cerrar' onClick='ValidarEstado(10)'></div></td><td width='130'><div class='ebtn_Programar_Pagos' onClick='ValidarEstado(8)' ></div></td><? if($_GET[mostrarvalores]!=1){?> <td width='75'><div class='ebtn_nuevo' onClick='Limpiar()'></div> </td><? } ?></tr></table>";
	}else if(accion==7){
		//ESTADO NO PROCEDE
		<?=$cpermiso->verificarPermiso("385",$_SESSION[IDUSUARIO]);?>
		document.all.botones_tabla.innerHTML="<table width='159' height='30' border='0' align='right' cellpadding='0' cellspacing='0'><tr><td width='85'><div class='ebtn_cerrar' onClick='ValidarEstado(10)'></div></td><? if($_GET[mostrarvalores]!=1){?><td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td><? }?></tr></table>";
	}else if(accion==8){
		//ESTADO ROGRAMAR PAGO
		<?=$cpermiso->verificarPermiso("386",$_SESSION[IDUSUARIO]);?>
		document.all.botones_tabla.innerHTML="<table width='159' height='30' border='0' align='right' cellpadding='0' cellspacing='0'><tr><td width='85'><div class='ebtn_cerrar' onClick='ValidarEstado(10)'></div></td><? if($_GET[mostrarvalores]!=1){?><td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td><? } ?></tr></table>";
	}else if(accion==10){		
		//ESTADO CERRADO
		<?=$cpermiso->verificarPermiso("387",$_SESSION[IDUSUARIO]);?>
		document.all.botones_tabla.innerHTML="<? if($_GET[mostrarvalores]!=1){?> <table width='150' border='0' align='right' cellpadding='0' cellspacing='0'> <tr> <td width='76'>&nbsp;</td> <td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div> </td>  </tr></table> <? }?>";
	}
	
	consultaTexto("mostrarProcede","moduloQuejasDanosFaltantes_Con.php?accion="+accion+"&folio="+u.folio.value+"&guia="+u.guia.value+"&sid="+Math.random());
	
}

function mostrarProcede(datos){
	if(datos.indexOf("ok")>-1){
		 var x = datos.split(",");
		 u.estado.innerHTML=x[1];
		info('Los datos han sido guardados correctamente','');
	}else{
		alerta3('Hubo un error al guardar '+datos,'¡Atención!');
	}
}

function obtenerModulosQuejas(folio){
	consultaTexto("mostrarModulosQuejas","moduloQuejasDanosFaltantes_Con.php?accion=9&folio="+folio+"&sid="+Math.random());
}
function mostrarModulosQuejas(datos){
	var obj = eval(datos);
	u.folio.value= obj[0].folio;
	//u.fecha.value= obj[0].id;
	u.estado.innerHTML	= obj[0].estado;
	if(u.solicitud.value==""){
		if(u.estado.innerHTML=="REVISION COMITE"){		
			document.all.botones_tabla.innerHTML="<table width='313' border='0' align='right' cellpadding='0' cellspacing='0'><tr><td width='1'></td> <td width='70'><div class='ebtn_guardar' onClick='Validar()'> </div></td> <td width='76'><div class='ebtn_Procede' onClick='ValidarEstado(6)'> </div></td><td width='94'><div class='ebtn_No_Procede' onClick='ValidarEstado(7)'></div></td> <? if($_GET[mostrarvalores]!=1){?> <td width='72'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td><? } ?> </tr></table>";
		}else if(u.estado.innerHTML=="ESPERA FACTURA"){
			document.all.botones_tabla.innerHTML="<table width='290' height='30' border='0' align='right' cellpadding='0' cellspacing='0'>  <tr><td width='85'><div class='ebtn_cerrar' onClick='ValidarEstado(10)'></div></td><td width='130'><div class='ebtn_Programar_Pagos' onClick='ValidarEstado(8)' ></div></td><? if($_GET[mostrarvalores]!=1){?> <td width='75'><div class='ebtn_nuevo' onClick='Limpiar()'></div> </td><? } ?></tr></table>";
		}else if(u.estado.innerHTML=="VIA LEGAL"){
			document.all.botones_tabla.innerHTML="<table width='159' height='30' border='0' align='right' cellpadding='0' cellspacing='0'><tr><td width='85'><div class='ebtn_cerrar' onClick='ValidarEstado(10)'></div></td><? if($_GET[mostrarvalores]!=1){?><td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td><? }?></tr></table>";
		}else if(u.estado.innerHTML=="SOLUCIONADO"){
			document.all.botones_tabla.innerHTML="<table width='159' height='30' border='0' align='right' cellpadding='0' cellspacing='0'><tr><td width='85'></td><? if($_GET[mostrarvalores]!=1){?><td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td><? }?></tr></table>";
		}else{
			document.all.botones_tabla.innerHTML="<table width='159' height='30' border='0' align='right' cellpadding='0' cellspacing='0'><tr><td width='85'><div class='ebtn_cerrar' onClick='ValidarEstado(10)'></div></td><? if($_GET[mostrarvalores]!=1){?><td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td><? }?></tr></table>";
		}
	}
	u.sucursal.value	= obj[0].sucursal;
	u.idsucursal.value	= obj[0].idsucursal;
	u.fecha.value		= obj[0].fecharegistro; 
	u.guia.value		= obj[0].nguia;
	u.guiad.value		= obj[0].estadoguia;
	u.cliente.value		= obj[0].idcliente;
	u.cliented.value	= obj[0].clientedescripcion;
	u.remitente_cliente.value= obj[0].remitentecliente;
	if(u.remitente_cliente.value==1){u.remitente_cliente.checked=true;}else{u.remitente_cliente.checked=false;}
	u.nombre.value	= obj[0].nombre;
	u.observaciones.value	= obj[0].observaciones;
	u.relacionembarque.value= obj[0].relacionembarque;
	if(u.relacionembarque.value==1){u.relacionembarque.checked=true}else{u.relacionembarque.checked=false;}
	u.copiafactura.value	= obj[0].copiafactura;
	if(u.copiafactura.value==1){u.copiafactura.checked=true;<? if($_GET[mostrarvalores]==1){?>u.confirmar1.style.visibility="visible";<? } ?>}else{u.copiafactura.checked=false;<? if($_GET[mostrarvalores]==1){?>u.confirmar1.style.visibility="hidden";<? }?>}
	u.confirmacopiafactura.value	= obj[0].confirmacopiafactura;
	if(u.confirmacopiafactura.value==1){u.confirmacopiafactura.checked=true;}else{u.confirmacopiafactura.checked=false;}
	u.comentariogerente.value	= obj[0].comentariogerente;
	if(u.comentariogerente.value==1){u.comentariogerente.checked=true;<? if($_GET[mostrarvalores]==1){?>u.confirmar2.style.visibility="visible";<? } ?>}else{u.comentariogerente.checked=false;<? if($_GET[mostrarvalores]==1){?>u.confirmar2.style.visibility="hidden";<? }?>}
	u.confirmacomentariogerente.value	= obj[0].confirmacomentariogerente;
	if(u.confirmacomentariogerente.value==1){u.confirmacomentariogerente.checked=true;}else{u.confirmacomentariogerente.checked=false;}
	u.cartareclamacion.value	= obj[0].cartareclamacion;
	if(u.cartareclamacion.value==1){u.cartareclamacion.checked=true}else{u.cartareclamacion.checked=false;}
	u.reportedanosyfaltantes.value= obj[0].reportedanosyfaltantes;
	if(u.reportedanosyfaltantes.value!=""){u.reportedanosyfaltantes.checked=true;<? if($_GET[mostrarvalores]==1){?>u.confirmar3.style.visibility="visible";<? } ?>}else{u.reportedanosyfaltantes.checked=false;<? if($_GET[mostrarvalores]==1){?>u.confirmar3.style.visibility="visible";<? } ?>}
	u.confirmareportedanosyfaltantes.value= obj[0].confirmareportedanosyfaltantes;
	if(u.confirmareportedanosyfaltantes.value==1){u.confirmareportedanosyfaltantes.checked=true;}else{u.confirmareportedanosyfaltantes.checked=false;}
	u.responsable.value		= obj[0].idresponsable;
	u.responsableb.value	= obj[0].responsable;
	u.accion.value="modificar";
}


function Limpiar(){
	u.estado.innerHTML	="";
	u.sucursal.value="";
	u.guia.value	="";
	u.guiad.value	="";
	u.cliente.value	="";
	u.cliented.value="";
	u.remitente_cliente.checked	=false;
	u.nombre.value	="";
	u.observaciones.value		="";
	u.relacionembarque.checked	=false;
	u.copiafactura.checked		=false;
	u.comentariogerente.checked	=false;
	u.cartareclamacion.checked	=false;
	u.reportedanosyfaltantes.checked=false;
	u.responsable.value		="";
	u.responsableb.value	="";
	u.accion.value			="";
	u.confirmacopiafactura.checked			=false;
	u.confirmacomentariogerente.checked		=false;
	u.confirmareportedanosyfaltantes.checked=false;
	obtenerGenerales();
	
	document.all.botones_tabla.innerHTML="<table width='150' border='0' align='right' cellpadding='0' cellspacing='0'>             <tr><td width='76'><div class='ebtn_guardar' onClick='Validar()'> </div></td><td width='74'><div class='ebtn_nuevo' onClick='Limpiar()'></div></td></tr></table>";
}



function tabular(e,obj) 
        {
            tecla=(document.all) ? e.keyCode : e.which;
            if(tecla!=13) return;
            frm=obj.form;
            for(i=0;i<frm.elements.length;i++) 
                if(frm.elements[i]==obj) 
                { 
                    if (i==frm.elements.length-1) 
                        i=-1;
                    break 
                }
            if (frm.elements[i+1].disabled ==true )    
                tabular(e,frm.elements[i+1]);
            else if (frm.elements[i+1].readOnly ==true )    
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus();
            return false;
}  
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../javascript/ajax.js"></script>
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

<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="510" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="432" class="FondoTabla Estilo4">MODULO DE QUEJAS, DA&Ntilde;OS Y FALTANTES</td>
  </tr>
  <tr>
    <td height="13"><div align="center">
      <table width="510" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="76">Folio:</td>
          <td width="118"><input name="folio" type="text" class="Tablas" id="folio" style="width:90px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
            <span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick=            "abrirVentanaFija('../buscadores_generales/buscarFolioModuloQuejas.php?funcion=obtenerModulosQuejas', 625, 450, 'ventana', 'Busqueda')"></span></td>
          <td width="32">Fecha:</td>
          <td width="120"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/></td>
          <td width="40">Estado:</td>
          <td width="114" id="estado"></td>
          </tr>
        <tr>
          <td>Sucursal:</td>
          <td colspan="2"><span class="Tablas">
            <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:115px;background:#FFFF99"  onKeyPress="if(event.keyCode=='13'){obtenerGuiaBusqueda(this.value)};return tabular(event,this)" onKeyDown="" readonly=""/>
            <input name="idsucursal" type="hidden" id="idsucursal">
          </span></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td># Guia: </td>
          <td><span class="Tablas">
            <input name="guia" type="text" class="Tablas" id="guia" style="width:115px" value="<?=$_GET[guia] ?>"  onKeyPress="if(event.keyCode=='13'){obtenerGuiaBusqueda(this.value)};return tabular(event,this)" onKeyDown=""/>
          </span></td>
          <td><span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick=            "abrirVentanaFija('../buscadores_generales/buscarGuiasEmpresariales_VentanillaGen.php?funcion=obtenerGuiaBusqueda', 625, 450, 'ventana', 'Busqueda')"></span></td>
          <td colspan="3"><input name="guiad" type="text" class="Tablas" id="guiad" style="width:250px;background:#FFFF99" value="<?=$guiad ?>" readonly="" onKeyDown="return tabular(event,this)"/></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td colspan="6" class="FondoTabla">Cliente</td>
          </tr>
        <tr>
          <td># Cliente<br></td>
          <td><span class="Tablas">
            <input name="cliente" type="text" class="Tablas" id="cliente" style="width:115px" value="<?=$cliente ?>"  onKeyPress="if(event.keyCode=='13'){obtenerCliente(this.value)};return tabular(event,this)" />
          </span></td>
          <td><span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick=            "abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerCliente', 625, 450, 'ventana', 'Busqueda')"></span></td>
          <td colspan="3"><input name="cliented" type="text" class="Tablas" id="cliented" style="width:190px;background:#FFFF99" value="<?=$cliented?>" readonly="" onKeyDown="return tabular(event,this)"/>
            <input name="remitente_cliente" type="checkbox" id="remitente_cliente" value="" onClick="if(document.all.remitente_cliente.checked==true){document.all.remitente_cliente.value=1;}else{document.all.remitente_cliente.value=0;}" onKeyDown="return tabular(event,this)">
            Remitente</td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td colspan="6" class="FondoTabla">Datos Persona Levanta Queja </td>
          </tr>
        <tr>
          <td>Nombre:</td>
          <td colspan="5"><span class="Tablas">
            <input name="nombre" type="text" class="Tablas" id="nombre" style="width:390px" value="<?=$nombre ?>"  onKeyDown="return tabular(event,this)"/>
          </span></td>
          </tr>
        <tr>
          <td valign="top">Observaciones:</td>
          <td colspan="5" valign="top"><textarea class="Tablas" name="observaciones" id="observaciones" style="width:350px;text-transform:uppercase" onKeyDown="return tabular(event,this)" ></textarea></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td colspan="6" class="FondoTabla">Documentaci&oacute;n Entregada</td>
          </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td  width="120px"><input name="relacionembarque" type="checkbox" id="relacionembarque" value="" onClick="if(document.all.relacionembarque.checked==true){document.all.relacionembarque.value=1; }else{document.all.relacionembarque.value=0;}" onKeyDown="return tabular(event,this)" >
Relacion Embarque</td>
              <td width="95px"><input name="copiafactura" type="checkbox" id="copiafactura" value="" onClick="if(document.all.copiafactura.checked==true){document.all.copiafactura.value=1;<? if($_GET[mostrarvalores]==1){?>u.confirmar1.style.visibility='visible';<? } ?> }else{document.all.copiafactura.value=0;u.confirmar1.style.visibility='hidden';<? if($_GET[mostrarvalores]==1){?>document.all.confirmacopiafactura.value=0;document.all.confirmacopiafactura.checked=false;<? }?> }" onKeyDown="return tabular(event,this)">
Copia Factura</td>
              <td id="confirmar1"  style="width:75px;visibility:hidden" >
                <input name="confirmacopiafactura" type="checkbox" id="confirmacopiafactura" style="width:10px"  onClick="if(document.all.confirmacopiafactura.checked==true){document.all.confirmacopiafactura.value=1;}else{document.all.confirmacopiafactura.value=0;}" onKeyDown="return tabular(event,this)" value="0"  >
<label style="font-size:8px">Confirmar</label>
</td>
              <td style="width:130px"><input name="comentariogerente" type="checkbox" id="comentariogerente" value="" onClick="if(document.all.comentariogerente.checked==true){document.all.comentariogerente.value=1;<? if($_GET[mostrarvalores]==1){?>u.confirmar2.style.visibility='visible';<? }?>}else{document.all.comentariogerente.value=0;u.confirmar2.style.visibility='hidden';<? if($_GET[mostrarvalores]==1){?>document.all.confirmacomentariogerente.value=0;document.all.confirmacomentariogerente.checked=false;<? }?>}" onKeyDown="return tabular(event,this)" >
Comentarios Gerente</td>
              <td id="confirmar2" style="visibility:hidden"  ><input name="confirmacomentariogerente" type="checkbox" id="confirmacomentariogerente"  style="width:10px"  onClick="if(document.all.confirmacomentariogerente.checked==true){document.all.confirmacomentariogerente.value=1;}else{document.all.confirmacomentariogerente.value=0;}" onKeyDown="return tabular(event,this)" value="0" >
<label style="font-size:8px">Confirmar</label></td>
            </tr>

          </table></td>
          </tr>
        <tr>
          <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">

            <tr>
              <td style="width:120px"><input name="cartareclamacion" type="checkbox" id="cartareclamacion" value="" onClick="if(document.all.cartareclamacion.checked==true){document.all.cartareclamacion.value=1;}else{document.all.cartareclamacion.value=0;}" onKeyDown="return tabular(event,this)" >
Carta Reclamaci&oacute;n</td>
              <td style="width:174px" >
             
                  <input name="reportedanosyfaltantes" type="checkbox" id="reportedanosyfaltantes" value="" onClick="if(document.all.reportedanosyfaltantes.checked==true){document.all.reportedanosyfaltantes.value=1;<? if($_GET[mostrarvalores]==1){?>u.confirmar3.style.visibility='visible';<? } ?>}else{document.all.reportedanosyfaltantes.value=0;u.confirmar3.style.visibility='hidden';<? if($_GET[mostrarvalores]==1){?>document.all.confirmareportedanosyfaltantes.value=0;document.all.confirmareportedanosyfaltantes.checked=false;<? } ?>}" onKeyDown="return tabular(event,this)" >
                  Reporte Da&ntilde;os y 
                  Faltantes</td>
              <td id="confirmar3"   style="visibility:hidden" ><input name="confirmareportedanosyfaltantes" type="checkbox" id="confirmareportedanosyfaltantes" style="width:10px"  onClick="if(document.all.confirmareportedanosyfaltantes.checked==true){document.all.confirmareportedanosyfaltantes.value=1;}else{document.all.confirmareportedanosyfaltantes.value=0;}" onKeyDown="return tabular(event,this)" value="0">
<label style="font-size:8px">Confirmar</label></td>
              </tr>

          </table></td>
          </tr>
        <tr>
          <td>Responsable:</td>
          <td><span class="Tablas">
            <input name="responsable" type="text" class="Tablas" id="responsable" style="width:80px" value="<?=$responsable ?>" onKeyPress="if(event.keyCode=='13'){obtenerResponsable(this.value)};return tabular(event,this)" />
            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick=            "abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=obtenerResponsable&tipo=1', 625, 418, 'ventana', 'Busqueda')">          </span></td>
          <td colspan="4"><input name="responsableb" type="text" class="Tablas" id="responsableb" style="width:237px;background:#FFFF99" value="<?=$responsableb ?>" readonly=""/></td>
          </tr>
        
        
        <tr>
          <td colspan="6"><input name="accion" type="hidden" id="accion">
            <input name="solicitud" type="hidden" id="solicitud" value="<?=$_GET[solicitud] ?>">
            <input name="tipo" type="hidden" id="tipo" value="<?=$_GET[tipo] ?>"></td>
        </tr>
        <tr>
          <td colspan="6" id="botones_tabla"><? if($_GET[solicitud]==""){ ?><table width="150" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="76"><div class="ebtn_guardar" onClick="Validar()"> </div></td>
                <td width="74"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'Limpiar();', '')"></div></td>
              </tr>
            </table><? }?></td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
</form>
</body>
</html>