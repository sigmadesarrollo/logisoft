<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/reseter.css" rel="stylesheet" type="text/css" />
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" >
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/jquery-1.4.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script>
	var v_pestanas = Array(true,false,false,false);
	var tablaservicios 			= new ClaseTabla();
	var tablaservicios2			= new ClaseTabla();
	var conveniopesokg 			= "";
	var conveniodescripcion 	= "";
	var consignacionpesokg 		= "";
	var consignaciondescripcion = "";
	var dconveniopesokg 			= "";
	var dconveniodescripcion 		= "";
	var dconsignacionpesokg 		= "";
	var dconsignaciondescripcion 	= "";
	var ubi = document.all;
	
	var accionesNuevo = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="85%" style="text-align:right" ><input name="boton_guardar" type="button" class="button" id="boton_guardar"  onclick="guardarConvenio();" value="Guardar Convenio" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo(); valoresIniciales()\', \'\')" value="Nuevo"/></td></tr></table>';
	
	var accionesImpreso  = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="55%" style="text-align:right"><input name="boton_activar" type="button" class="button" id="boton_activar"  onclick="activarConvenio();" value="Activar Convenio" /></td><td width="30%" style="text-align:right"><input name="boton_noactivar" type="button" class="button" id="boton_noactivar" value="No Activar" onClick="noActivarConvenio();" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo(); valoresIniciales()\', \'\')" value="Nuevo"/></td></tr></table>';
			   
	var accionesAutorizado = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="85%" style="text-align:right" ><input name="boton_imprimir" type="button" class="button" id="boton_renovar"  onclick="imprimirActivado();" value="Imprimir Convenio" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo(); valoresIniciales()\', \'\')" value="Nuevo"/></td></tr></table>';
	
	var accionesActivado = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="55%" style="text-align:right"><input name="boton_imprimir" type="button" class="button" id="boton_imprimir"  onclick="imprimirActivado();" value="Imprimir Convenio" /></td><td width="30%" style="text-align:right"><input name="boton_cancelar" type="button" class="button" id="boton_cancelar" value="Cancelar" onClick="cancelarConvenio();" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo(); valoresIniciales()\', \'\')" value="Nuevo"/></td></tr></table>';
	
	var accionesActivado2 = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="21%" style="text-align:right"><input name="boton_cancelar" type="button" class="button" id="boton_cancelar"  onclick="cancelarConvenio();" value="Cancelar" /></td><td width="32%" style="text-align:right"><input name="boton_imprimir" type="button" class="button" id="boton_imprimir"  onclick="imprimirActivado();" value="Imprimir Convenio" /></td><td width="31%" style="text-align:right"><input name="boton_renovar" type="button" class="button" id="boton_renovar" value="Renovar Convenio" onClick="renovarConvenio();" /></td><td width="16%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo(); valoresIniciales()\', \'\')" value="Nuevo"/></td></tr>			</table>';
	
	var accionesCancelado = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="85%" style="text-align:right" >&nbsp;</td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo(); valoresIniciales()\', \'\')" value="Nuevo"/></td></tr></table>';
	
	tablaservicios.setAttributes({
		nombre:"tabladeservicios",
		campos:[
			{nombre:"IDSERVICIO", medida:4, alineacion:"left", tipo:"oculto", datos:"idservicio"},
			{nombre:"Servicio", medida:400, alineacion:"left", datos:"servicio"},
			{nombre:"Cobro", medida:135, alineacion:"right", datos:"cobro"},
			{nombre:"Precio", medida:135, alineacion:"center", tipo:"moneda", datos:"precio"}
		],
		filasInicial:5,
		alto:80,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"mostrarServicios()",
		nombrevar:"tablaservicios"
	});
		
	tablaservicios2.setAttributes({
		nombre:"tabladeservicios2",
		campos:[
			{nombre:"IDSERVICIO", medida:4, alineacion:"left", tipo:"oculto", datos:"idservicio"},
			{nombre:"Servicio", medida:400, alineacion:"left", datos:"servicio"},
			{nombre:"Cobro", medida:135, alineacion:"right", datos:"cobro"},
			{nombre:"Precio", medida:135, alineacion:"center", tipo:"moneda", datos:"precio"}
		],
		filasInicial:5,
		alto:80,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"mostrarServicios2()",
		nombrevar:"tablaservicios2"
	});
	
	jQuery(function($){
	   $('#vencimiento').mask("99/99/9999");
	});

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
	
	window.onload = function(){
		tablaservicios.create();
		tablaservicios2.create();
		
		conveniopesokg 				= ubi.div_preciokg.innerHTML;
		conveniodescripcion 		= ubi.div_descripcion.innerHTML;
		consignacionpesokg 			= ubi.detallekgc.innerHTML;
		consignaciondescripcion 	= ubi.detalledescripcionc.innerHTML;
		dconveniopesokg 			= conveniopesokg;
		dconveniodescripcion 		= conveniodescripcion;
		dconsignacionpesokg 		= consignacionpesokg;
		dconsignaciondescripcion 	= consignaciondescripcion;
		
		valoresIniciales();
		
		<?
		$_GET[funcion] = str_replace("\'","'",$_GET[funcion]);
		if($_GET[funcion]!=""){
			echo 'setTimeout("'.$_GET[funcion].'",1500);';
			}
		?>
	}
	
	function limpiarTodo(){
		ubi.foliop.value 			= "";
		ubi.vendedor.value			= "";
		ubi.vendedorb.innerHTML			= "";
		ubi.estadoc.innerHTML		= "";
		ubi.compromiso.value		= "";
		limpiarCliente();
		dconveniopesokg 			= conveniopesokg;
		dconveniodescripcion 		= conveniodescripcion;
		dconsignacionpesokg 		= consignacionpesokg;
		dconsignaciondescripcion 	= consignaciondescripcion;
		
		ubi.descuentoflete.value = "";
		
		ubi.div_preciokg.innerHTML			= conveniopesokg;
		ubi.div_descripcion.innerHTML		= conveniodescripcion;
		ubi.detallekgc.innerHTML			= consignacionpesokg;
		ubi.detalledescripcionc.innerHTML	= consignaciondescripcion;
		
		tablaservicios.clear();
		ubi.limitekg.value="";
		ubi.costoguia.value="";
		ubi.preciokgexcedente.value="";
		
		ubi.consignaciondes.value = "";
		tablaservicios2.clear();
		
		ubi.serviciosr1_sel.options.length = 0;
		ubi.sucursalesead1_sel.options.length = 0;
		ubi.serviciosr2_sel.options.length = 0;
		ubi.sucursalesead3_sel.options.length = 0;
		ubi.valordeclarado.value = "";
		ubi.limite.value = "";
		ubi.porcada.value = "";
		ubi.costoextra.value = "";
		ubi.legal.value = "";
		ubi.celdaacciones.innerHTML = accionesNuevo;
		seleccionarTab(0);
	}
	
	function mostrarPropuestasPendientes(){
		abrirVentanaFija('../buscadores_generales/buscarPropuestasPorEstado.php?funcion=pedirPropuesta&pestado=AUTORIZADA', 650, 500, 'ventana', 'Busqueda');
	}
	
	function mostrarConveniosPendientesImp(){
		abrirVentanaFija('../buscadores_generales/buscarConveniosPorEstado.php?funcion=pedirConvenio&cestado=AUTORIZADO', 650, 500, 'ventana', 'Busqueda');
	}
	
	function mostrarConveniosPendientesAct(){
		abrirVentanaFija('../buscadores_generales/buscarConveniosPorEstado.php?funcion=pedirConvenio&cestado=IMPRESO', 650, 500, 'ventana', 'Busqueda');
	}
	
	function mostrarConveniosPendientesVen(){
		abrirVentanaFija('../buscadores_generales/buscarConvenioGen.php?funcion=pedirConvenio', 650, 500, 'ventana', 'Busqueda');
	}
	
	function valoresIniciales(){
		consultaTexto("resValoresIniciales","generacionconvenio_con.php?accion=11");
	}
	
	function resValoresIniciales(datos){
		var cosa = eval(datos);
		
		ubi.folio.value = cosa.folio;
		ubi.fecha.innerHTML = cosa.fecha.replace(/-/g,"/");
		ubi.vencimiento.value = cosa.fechalimite.replace(/-/g,"/");
	}
	
	function agregarGrid(tabla,objeto){
		for(var i=0; i<objeto.length; i++)
			tabla.add(objeto[i]);
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
	
	//funciones para propuesta
	function pedirPropuesta(valor){
		ubi.foliop.value = valor;
		consultaTexto("respPedirPropuesta","generacionconvenio_con.php?accion=1&valor="+valor+"&folio="+valor+"&ranm="+Math.random());
	}
	function respPedirPropuesta(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		limpiarTodo();
		if(objeto[0].propuesta[0].estadopropuesta){
			var epropuesta 					= objeto[0].propuesta[0].estadopropuesta;
			//ubi.folio.value 				= objeto[0].propuesta[0].folio;
			ubi.foliop.value 				= objeto[0].propuesta[0].folio;
			ubi.fecha.innerHTML 				= objeto[0].propuesta[0].factual;
			ubi.vencimiento.value			= objeto[0].propuesta[0].fvencimiento ;
			ubi.vendedor.value 				= objeto[0].propuesta[0].vendedor ;
			ubi.vendedorb.innerHTML 			= objeto[0].propuesta[0].nvendedor ;
			ubi.h_vencido.value				= objeto[0].propuesta[0].vencido;
			ubi.h_yatiene.value				= objeto[0].propuesta[0].yatiene;
			
			
			if(objeto[0].propuesta[0].personamoral==0){
				ubi.personamoral[1].checked = true;
			}else{
				ubi.personamoral[0].checked = true;
			}
			if(objeto[0].propuesta[0].tipo=='PRO'){
				ubi.clienterdo[0].checked = true;
			}else{
				ubi.clienterdo[1].checked = true;
			}
			
			
			ubi.prospecto.value 			= objeto[0].propuesta[0].idprospecto ;
			ubi.nick.value 					= objeto[0].propuesta[0].nick;
			ubi.rfc.value 					= objeto[0].propuesta[0].rfc;
			ubi.nombre.value 				= objeto[0].propuesta[0].nombre;
			ubi.paterno.value 				= objeto[0].propuesta[0].apaterno;
			ubi.materno.value 				= objeto[0].propuesta[0].amaterno;
			ubi.calle.value 				= objeto[0].propuesta[0].calle;
			ubi.numero.value 				= objeto[0].propuesta[0].numero;
			ubi.colonia.value 				= objeto[0].propuesta[0].colonia;
			ubi.cp.value 					= objeto[0].propuesta[0].cp;
			ubi.poblacion.value 			= objeto[0].propuesta[0].poblacion;
			ubi.municipio.value 			= objeto[0].propuesta[0].municipio;
			ubi.estado.value 				= objeto[0].propuesta[0].estado;
			ubi.pais.value 					= objeto[0].propuesta[0].pais;
			ubi.celular.value 				= objeto[0].propuesta[0].celular;
			ubi.telefono.value 				= objeto[0].propuesta[0].telefono;
			ubi.email.value 				= objeto[0].propuesta[0].email;
			ubi.valordeclarado.value		= objeto[0].propuesta[0].valordeclarado;
			ubi.valordeclarado.value		= convertirMoneda(ubi.valordeclarado.value);
			ubi.limite.value				= objeto[0].propuesta[0].limite;
			ubi.porcada.value				= objeto[0].propuesta[0].porcada;
			ubi.costoextra.value			= objeto[0].propuesta[0].costoextra;
			ubi.legal.value			= objeto[0].propuesta[0].legal;
			ubi.costoextra.value			= convertirMoneda(ubi.costoextra.value);
					
	if(ubi.h_vencido.value=="vencido"){
		alerta3("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio y actualmente se encuentra vencido", "¡Atención!");			
	}else if(ubi.h_yatiene.value=="ya tiene"){
		alerta3("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio", "¡Atención!");
	}
			
			ubi.radiogrupo[0].checked 		= (objeto[0].propuesta[0].precioporkg==1)?true:false;
			ubi.radiogrupo[1].checked 		= (objeto[0].propuesta[0].precioporcaja==1)?true:false;
			ubi.radiogrupo[2].checked = (objeto[0].propuesta[0].descuentosobreflete==1)?true:false;
			ubi.descuentoflete.value 		= objeto[0].propuesta[0].cantidaddescuento;
			ubi.limitekg.value 				= objeto[0].propuesta[0].limitekg;
			ubi.costoguia.value 			= objeto[0].propuesta[0].costo;
			ubi.costoguia.value 			= convertirMoneda(ubi.costoguia.value);
			ubi.preciokgexcedente.value		= objeto[0].propuesta[0].preciokgexcedente;
			ubi.preciokgexcedente.value		= convertirMoneda(ubi.preciokgexcedente.value);
			ubi.consignaciondes.value		= objeto[0].propuesta[0].consignaciondescantidad;
			
			ubi.checkprepagadas.checked		= (objeto[0].propuesta[0].prepagadas==1)?true:false;
			ubi.radiobutton[0].checked		= (objeto[0].propuesta[0].consignacionkg==1)?true:false;
			ubi.radiobutton[1].checked		= (objeto[0].propuesta[0].consignacioncaja==1)?true:false;
			ubi.radiobutton[2].checked		= (objeto[0].propuesta[0].consignaciondescuento==1)?true:false;
			
			agregarValores(ubi.serviciosr1_sel,objeto[0].serviciocombo1,ubi.serviciosr1, ubi.serviciosr1);
			agregarValores(ubi.sucursalesead1_sel,objeto[0].serviciocombo2,ubi.sucursalesead1, ubi.sucursalesead1);
			agregarValores(ubi.serviciosr2_sel,objeto[0].serviciocombo3,ubi.serviciosr1, ubi.serviciosr1);
			agregarValores(ubi.sucursalesead3_sel,objeto[0].serviciocombo5,ubi.sucursalesead1, ubi.sucursalesead1);
			
			agregarGrid(tablaservicios,objeto[0].serviciogrid1);
			agregarGrid(tablaservicios2,objeto[0].serviciogrid2);
			if(objeto[0].propuesta[0].precioporkg==1){
				consultaTexto("mostrarCGridKg", "propuestaconvenio_con.php?accion=7&valor=1&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].precioporcaja==1){
				consultaTexto("mostrarCGridPeso", "propuestaconvenio_con.php?accion=7&valor=2&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].consignacionkg==1){
				consultaTexto("mostrarSGridKg", "propuestaconvenio_con.php?accion=7&valor=3&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].consignacioncaja==1){
				consultaTexto("mostrarSGridPeso", "propuestaconvenio_con.php?accion=7&valor=4&idconvenio="+objeto[0].propuesta[0].folio)
			}
		}
	}
	function mostrarCGridKg(datos){
		ubi.div_preciokg.innerHTML = datos;
		dconveniopesokg 			= datos;
	}
	function mostrarCGridPeso(datos){
		ubi.div_descripcion.innerHTML = datos;
		dconveniodescripcion 		= datos;
	}
	function mostrarSGridKg(datos){
		ubi.detallekgc.innerHTML = datos;
		dconsignacionpesokg 		= datos;
	}
	function mostrarSGridPeso(datos){
		ubi.detalledescripcionc.innerHTML = datos;
		dconsignaciondescripcion 	= datos;
	}
	
	function pedirConvenio(valor){
		consultaTexto("respPedirConvenio","generacionconvenio_con.php?accion=5&valor="+valor+"&folio="+valor+"&ranm="+Math.random());
	}
	function respPedirConvenio(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		limpiarTodo();
		if(objeto.length){
			var econvenio 					= objeto[0].propuesta[0].estadoconvenio;
			
			if(econvenio == "AUTORIZADO"){
				ubi.celdaacciones.innerHTML = accionesAutorizado;
			}
			if(econvenio == "IMPRESO"){
				ubi.celdaacciones.innerHTML = accionesImpreso;
			}
			if(econvenio == "CANCELADO"){
			ubi.celdaacciones.innerHTML = accionesCancelado;
			}
			if(econvenio == "ACTIVADO" || econvenio == "NO ACTIVADO"){
				if(objeto[0].propuesta[0].botonrenovar == 'RENOVAR'){
					ubi.celdaacciones.innerHTML = accionesActivado2;
				}else{
					ubi.celdaacciones.innerHTML = accionesActivado;
				}
			}
			
			if(econvenio == "EXPIRADO"){
				ubi.celdaacciones.innerHTML = accionesActivado2;
			}
			
			ubi.clienterdo[1].checked = true;
			
			ubi.compromiso.value			= objeto[0].propuesta[0].consumomensual;
			
			ubi.folio.value 				= objeto[0].propuesta[0].folio;
			ubi.credito.innerHTML				= objeto[0].propuesta[0].foliocredito;
			ubi.fecha.innerHTML 				= objeto[0].propuesta[0].factual;
			ubi.estadoc.innerHTML			= objeto[0].propuesta[0].estadoconvenio;
			ubi.vencimiento.value			= objeto[0].propuesta[0].fvencimiento;
			ubi.vendedor.value 				= objeto[0].propuesta[0].vendedor ;
			ubi.vendedorb.innerHTML 			= objeto[0].propuesta[0].nvendedor ;
			ubi.consignaciondes.value		= objeto[0].propuesta[0].consignaciondescantidad;
			if(objeto[0].propuesta[0].personamoral==1)
				ubi.personamoral[0].checked = true;
			else
				ubi.personamoral[1].checked = true;
			
			ubi.prospecto.value 			= objeto[0].propuesta[0].idcliente;
			ubi.nick.value 					= objeto[0].propuesta[0].nick;
			ubi.rfc.value 					= objeto[0].propuesta[0].rfc;
			ubi.nombre.value 				= objeto[0].propuesta[0].nombre;
			ubi.paterno.value 				= objeto[0].propuesta[0].apaterno;
			ubi.materno.value 				= objeto[0].propuesta[0].amaterno;
			ubi.calle.value 				= objeto[0].propuesta[0].calle;
			ubi.numero.value 				= objeto[0].propuesta[0].numero;
			ubi.colonia.value 				= objeto[0].propuesta[0].colonia;
			ubi.cp.value 					= objeto[0].propuesta[0].cp;
			ubi.poblacion.value 			= objeto[0].propuesta[0].poblacion;
			ubi.municipio.value 			= objeto[0].propuesta[0].municipio;
			ubi.estado.value 				= objeto[0].propuesta[0].estado;
			ubi.pais.value 					= objeto[0].propuesta[0].pais;
			ubi.celular.value 				= objeto[0].propuesta[0].celular;
			ubi.telefono.value 				= objeto[0].propuesta[0].telefono;
			ubi.email.value 				= objeto[0].propuesta[0].email;
			ubi.valordeclarado.value		= objeto[0].propuesta[0].valordeclarado;
			ubi.valordeclarado.value		= convertirMoneda(ubi.valordeclarado.value);
			ubi.limite.value				= objeto[0].propuesta[0].limite;
			ubi.porcada.value				= objeto[0].propuesta[0].porcada;
			ubi.costoextra.value			= objeto[0].propuesta[0].costoextra;
			ubi.legal.value			= objeto[0].propuesta[0].legal;
			ubi.costoextra.value			= convertirMoneda(ubi.costoextra.value);
			
			ubi.radiogrupo[0].checked 		= (objeto[0].propuesta[0].precioporkg==1)?true:false;
			if(objeto[0].propuesta[0].precioporcaja==1){
				ubi.radiogrupo[1].checked 		= (objeto[0].propuesta[0].precioporcaja==1)?true:false;
			}else{
				ubi.radiogrupo[2].checked 		= (objeto[0].propuesta[0].descuentosobreflete==1)?true:false;
			}
			
			ubi.descuentoflete.value 		= objeto[0].propuesta[0].cantidaddescuento;
			ubi.limitekg.value 				= objeto[0].propuesta[0].limitekg;
			ubi.costoguia.value 			= objeto[0].propuesta[0].costo;
			ubi.preciokgexcedente.value		= objeto[0].propuesta[0].preciokgexcedente;
			
			ubi.checkprepagadas.checked		= (objeto[0].propuesta[0].prepagadas==1)?true:false;
			ubi.radiobutton[0].checked		= (objeto[0].propuesta[0].consignacionkg==1)?true:false;
			ubi.radiobutton[1].checked		= (objeto[0].propuesta[0].consignacioncaja==1)?true:false;
			ubi.radiobutton[2].checked		= (objeto[0].propuesta[0].consignaciondescuento==1)?true:false;
			
			agregarValores(ubi.serviciosr1_sel,objeto[0].serviciocombo1,ubi.serviciosr1);
			agregarValores(ubi.sucursalesead1_sel,objeto[0].serviciocombo2,ubi.sucursalesead1);
			agregarValores(ubi.serviciosr2_sel,objeto[0].serviciocombo3,ubi.serviciosr1);
			agregarValores(ubi.sucursalesead3_sel,objeto[0].serviciocombo5,ubi.sucursalesead1);
			
			agregarGrid(tablaservicios,objeto[0].serviciogrid1);
			agregarGrid(tablaservicios2,objeto[0].serviciogrid2);
			if(objeto[0].propuesta[0].precioporkg==1){
				consultaTexto("mostrarCGridKg", "generacionconvenio_con.php?accion=8&valor=1&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].precioporcaja==1){
				consultaTexto("mostrarCGridPeso", "generacionconvenio_con.php?accion=8&valor=2&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].consignacionkg==1){
				consultaTexto("mostrarSGridKg", "generacionconvenio_con.php?accion=8&valor=3&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].consignacioncaja==1){
				consultaTexto("mostrarSGridPeso", "generacionconvenio_con.php?accion=8&valor=4&idconvenio="+objeto[0].propuesta[0].folio)
			}
		}
	}
	
	//EMPLEADO
	function pedirEmpleado(valor){
		document.all.vendedor.value = valor;
		consultaTexto("mostrarEmpleado", "propuestaconvenio_con.php?accion=1&valor="+valor+"&valram="+Math.random());
	}
	function mostrarEmpleado(valor){
		document.all.vendedorb.value = valor;
	}
	function pedirCliente(valor){
		consultaTexto("mostrarCliente", "generacionconvenio_con.php?accion=2&personamoral="+document.all.personamoral_valor.value+"&valor="+valor+"&valram="+Math.random());
	}
	function mostrarCliente(datos){
		
		if(datos.indexOf("vencido")>-1){
		alerta("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio y actualmente se encuentra vencido", "¡Atención!","prospecto");
		return false;
		}
		
		if(datos.indexOf("ya tiene")>-1){
			alerta("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio", "¡Atención!","prospecto");
			return false;
		}
		
		var objeto = eval(datos);
		limpiarCliente();
		if(objeto.length){
			ubi.credito.innerHTML	= objeto[0].folio;
			ubi.prospecto.value	= objeto[0].id;
			ubi.nick.value 		= objeto[0].nick;
			ubi.rfc.value 		= objeto[0].rfc;
			ubi.nombre.value 	= objeto[0].nombre;
			ubi.paterno.value 	= objeto[0].paterno;
			ubi.materno.value 	= objeto[0].materno;
			ubi.calle.value 	= objeto[0].calle;
			ubi.numero.value 	= objeto[0].numero;
			ubi.colonia.value 	= objeto[0].colonia;
			ubi.cp.value 		= objeto[0].cp;
			ubi.poblacion.value = objeto[0].poblacion;
			ubi.municipio.value = objeto[0].municipio;
			ubi.estado.value 	= objeto[0].estado;
			ubi.pais.value 		= objeto[0].pais;
			ubi.celular.value 	= objeto[0].celular;
			ubi.telefono.value 	= objeto[0].telefono;
			ubi.email.value 	= objeto[0].email;
		}else{
			alerta3("No se encontraron datos del prospecto", "¡Atención!");
		}
	}
	function limpiarCliente(){
			ubi.credito.innerHTML	= "";
			ubi.prospecto.value	= "";
			ubi.nick.value 		= "";
			ubi.rfc.value 		= "";
			ubi.nombre.value 	= "";
			ubi.paterno.value 	= "";
			ubi.materno.value 	= "";
			ubi.calle.value 	= "";
			ubi.numero.value 	= "";
			ubi.colonia.value 	= "";
			ubi.cp.value 		= "";
			ubi.poblacion.value = "";
			ubi.municipio.value = "";
			ubi.estado.value 	= "";
			ubi.pais.value 		= "";
			ubi.celular.value 	= "";
			ubi.telefono.value 	= "";
			ubi.email.value 	= "";
	}
	
	//paraconsignacion
	function desactivarConsignacion(valor){
		ubi.detallekgc.innerHTML 		  = consignacionpesokg;
		ubi.detalledescripcionc.innerHTML = consignaciondescripcion;
		switch (valor){
			case 2:
				ubi.detallekgc.innerHTML 		  = dconsignacionpesokg;
				break;
			case 3:
				ubi.detalledescripcionc.innerHTML = dconsignaciondescripcion;
				break;
		}
	}
	
	function guardarConvenio(){		
		<?=$cpermiso->verificarPermiso("393,396",$_SESSION[IDUSUARIO]);?>
		if(ubi.h_vencido.value=="vencido"){
		alerta3("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio y actualmente se encuentra vencido", "¡Atención!");
			return false;
		}else if(ubi.h_yatiene.value=="ya tiene"){
			alerta3("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio", "¡Atención!");
			return false;
		}		
		
		var precioporkg 			= (ubi.radiogrupo[0].checked)?"1":"0";
		var precioporcaja 			= (ubi.radiogrupo[1].checked)?"1":"0";
		
		var consignacionkg 			= (ubi.radiobutton[0].checked)?"1":"0";
		var consignacioncaja 		= (ubi.radiobutton[1].checked)?"1":"0";
		
		var vigencia				= document.all.vencimiento.value;
		
		if(ubi.clienterdo[0].checked){
			var tipoc = "PRO";
		}else{
			var tipoc = "CLI";
		}
		
		if(ubi.compromiso.value == ""){
			alerta("Porfavor ingrese el compromiso mensual","¡Atención!","compromiso");
		}else{
			
			document.all.boton_guardar.style.visibility='hidden';
			/*ubi.celdaerrores.innerHTML = "generacionconvenio_con.php?accion=3&idpropuesta="+ubi.foliop.value+"&tipoc="+tipoc
					  +"&idcliente="+ubi.prospecto.value+"&crecito="+ubi.credito.innerHTML+"&consumomensual="+ubi.compromiso.value
					  +"&precioporkg="+precioporkg+"&precioporcaja="+precioporcaja+"&consignacionkg="+consignacionkg
					  +"&consignacioncaja="+consignacionkg+"&random="+Math.random();*/
			consultaTexto("resGuardarConvenio","generacionconvenio_con.php?accion=3&idpropuesta="+ubi.foliop.value+"&tipoc="+tipoc
					  +"&idcliente="+ubi.prospecto.value+"&crecito="+ubi.credito.innerHTML+"&consumomensual="+ubi.compromiso.value
					  +"&precioporkg="+precioporkg+"&precioporcaja="+precioporcaja+"&consignacionkg="+consignacionkg
					  +"&consignacioncaja="+consignacionkg+"&vigencia="+vigencia+"&random="+Math.random());
		}
	}
	
	function resGuardarConvenio(datosx){
		if(datosx.indexOf("guardo")>-1){
			ubi.estadoc.innerHTML = "AUTORIZADO";	
			ubi.folio.value = datosx.split(",")[1];
			ubi.clienterdo[1].checked = true;
			ubi.prospecto.value = datosx.split(",")[3];
			info("El convenio a cambiado su estado a autorizado","¡Atención!");
			ubi.celdaacciones.innerHTML = accionesAutorizado;
		}else{
			alerta3("Error al guardar",datosx);
		}
	}
	
	function imprimirActivado(){
		<?=$cpermiso->verificarPermiso("394,397",$_SESSION[IDUSUARIO]);?>
		if(ubi.estadoc.innerHTML == "ACTIVADO"){
			//window.open("../clasePDF/imprimirConvenio.php?folio="+ubi.folio.value);
			window.open("../fpdf/reportes/convenio.php?folio="+ubi.folio.value);
		}else{
			if(ubi.folio.value==""){
				alerta3("Seleccione el folio para activar","¡Atención!");
			}else{
				consultaTexto("resImprimir","generacionconvenio_con.php?accion=4&folio="+ubi.folio.value
						  +"&random="+Math.random());
			}
		}
	}
	function resImprimir(datos){
		if(datos.indexOf("impreso")>-1){
			ubi.estadoc.innerHTML = "IMPRESO";
			window.open("../fpdf/reportes/convenio.php?folio="+ubi.folio.value);
			info("El convenio a cambiado su estado a IMPRESO", "¡Atención!");
			ubi.celdaacciones.innerHTML = accionesImpreso;
		}else{
			alerta3("Error al cambiar estado", datos);
		}
	}
	
	function activarConvenio(){
		<?=$cpermiso->verificarPermiso("395,398",$_SESSION[IDUSUARIO]);?>
		consultaTexto("resConsultarSiActivado","generacionconvenio_con.php?accion=12&cliente="+ubi.prospecto.value+"&random="+Math.random());
	}
	
	function resConsultarSiActivado(datos){
		if(datos.indexOf("SI TIENE")>-1){
			alerta3("El cliente ya tiene convenio","¡Atención!");
		}else{
			document.all.boton_activar.style.visibility='hidden';
			consultaTexto("resActivarConvenio","generacionconvenio_con.php?accion=6&folio="+ubi.folio.value
					  +"&random="+Math.random());
		}
	}
	
	function resActivarConvenio(datos){
		if(datos.indexOf("impreso")>-1){
			ubi.estadoc.innerHTML = "ACTIVADO";
			info("El convenio a cambiado su estado a ACTIVADO", "¡Atención!");
			ubi.celdaacciones.innerHTML = accionesActivado;
		}else{
			alerta3("Error al cambiar estado", datos);
		}
	}
	
	function noActivarConvenio(){
		<?=$cpermiso->verificarPermiso("387,388",$_SESSION[IDUSUARIO]);?>
		document.all.boton_noactivar.style.visibility='hidden';
		consultaTexto("resNoActivarConvenio","generacionconvenio_con.php?accion=7&folio="+ubi.folio.value
					  +"&random="+Math.random());
	}
	function resNoActivarConvenio(datos){
		if(datos.indexOf("impreso")>-1){
			ubi.estadoc.innerHTML = "NO ACTIVADO";
			info("El convenio a cambiado su estado a NO ACTIVADO", "¡Atención!");
			ubi.celdaacciones.innerHTML = accionesActivado;
		}else{
			alerta3("Error al cambiar estado", datos);
		}
	}
	
	function renovarConvenio(){
		document.all.boton_renovar.style.visibility='hidden';
		consultaTexto("resRenovarConvenio","generacionconvenio_con.php?accion=9&folio="+ubi.folio.value
					  +"&random="+Math.random());
	}
	function resRenovarConvenio(datos){
		if(datos.indexOf("actualizo")>-1){
			info("El convenio se ha renovado, <br>cambiado su estado a AUTORIZADO", "¡Atención!");
			ubi.estadoc.innerHTML = "AUTORIZADO";
			ubi.celdaacciones.innerHTML = accionesAutorizado;
			ubi.vencimiento.value = datos.split(',')[1];
		}else{
			alerta3("Error al renovar<bR>"+datos, "");
		}
	}
	
	function cancelarConvenio(){
		confirmar("¿Seguro desea cancelar el convenio?","ATENCION","siCancelar()")
	}
	
	function siCancelar(){
		document.all.boton_cancelar.style.visibility='hidden';
		consultaTexto("resCancelarConvenio","generacionconvenio_con.php?accion=10&folio="+ubi.folio.value
					  +"&random="+Math.random());
	}
	
	function resCancelarConvenio(datos){
		if(datos.indexOf("cancelado")>-1){
			alerta3("El Convenio ha sido Cancelado","¡Atención!");
			ubi.estadoc.innerHTML = "CANCELADO";
			ubi.celdaacciones.innerHTML = accionesCancelado;
		}else{
			alerta3(datos,"Error al cancelar");
		}
	}
	
	function validarFecha(e,param,name){
		tecla = (ubi) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,3),10);
				var year = 	parseInt(param.substring(6,10),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					alerta('La fecha no es valida', '¡Atención!',name);
					return false;
				}				
				if(dia > 29 && (mes=="02" || mes==2)){
					if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){
						alerta3('La fecha de vencimiento no es valida, por que el año '+year+' es bisiesto su maximo dia es 29', '¡Atención!');
						return false;
					}else{
						alerta3('La fecha de vencimiento no es valida, por que el año '+year+' no es bisiesto su maximo dia es 28', '¡Atención!');
						return false;
					}
				}
				
				if(dia >= 29 && (mes=="02" || mes=="2")){
					if(!((year % 4 == 0 && year % 100 != 0) || year % 400 == 0)){
						alerta3('La fecha de vencimiento no es valida, por que el año '+year+' no es bisiesto su maximo dia es 28', '¡Atención!');
							return false;
					}
				}
				if(dia > "31" || dia=="0"){
					alerta('La fecha no es valida, capture correctamente el Dia', '¡Atención!',name);
					return false;
				}
				if(mes > "12" || mes=="0"){
					alerta('La fecha no es valida, capture correctamente el Mes', '¡Atención!',name);
					return false;	
				}
			}	

		}
	}
	function convertirMoneda(valor){		
		valor = (valor=="")?"0.00":valor;
		valor = Math.round(parseFloat(valor)*100)/100;
		valor = "$ "+numcredvar(valor.toLocaleString());
		return valor;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	function seleccionarTab(index){
			var npagina = "div";
			var ntab	= "label";
			var pestanas= 4;
			
			if(v_pestanas[index]==false){			
				v_pestanas[index] = true;
				document.all[npagina+index].style.visibility = "visible";
			}
			
			for(var i=0; i<pestanas; i++){
				if(index!=i){
					document.all[npagina+i].style.display='none';
					document.all[ntab+i].className='';
				}
			}
			
			document.all[npagina+index].style.display='';
			document.all[ntab+index].className='active';			
	}
	
</script>
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
	<div class="canvas">     
     <div class="content">
        <div class="det-guia3">
          <div class="dvTable clearfix" style="width:250px; color:#FFFFFF;">				  
			<div class="c1">Folio Propuesta</div>
			<div class="c2"><input name="foliop" type="text" class="text" id="foliop" style="width:120px;" onkeypress="if(event.keyCode==13){pedirPropuesta(this.value);}"/>
              <input type="button" id="search3" value=" " class="srch-btn" title="Buscar" style="float:right" onclick="abrirVentanaFija('../buscadores_generales/buscarPropuestaConvenioGen.php?funcion=pedirPropuesta&amp;pestado=AUTORIZADA', 600, 500, 'ventana', 'Busqueda')" /></div>            
			
			<div class="c1">Folio</div>
              <div class="c2"><input name="folio" type="text" class="text" id="folio" style="width:120px;" onkeypress="if(event.keyCode==13){pedirConvenio(this.value);}"/>
              <input type="button" id="search3" value=" " class="srch-btn" title="Buscar" style="float:right" onclick="abrirVentanaFija('../buscadores_generales/buscarConvenioGen.php?funcion=pedirConvenio', 600, 500, 'ventana', 'Busqueda')"/></div>
                <div class="c1">Fecha</div>
                <div class="c2" id="fecha"><?=date('d/m/Y') ?></div>
				<div class="c1">Estado</div>
                <div class="c2" id="estadoc"></div>
				<div class="c1">Vigencia</div>
                <div class="c2"><input name="vencimiento" type="text" class="text" id="vencimiento" style="width:120px;"  onkeypress="validarFecha(event,this.value,this.name);"/>
				<input name="button" type="button" class="cal-btn" id="button" title="Agregar" value="&nbsp;" onclick="displayCalendar(document.all.vencimiento,'dd/mm/yyyy',this)"/></div>
                <div class="c1">Credito</div>
				<div class="c2" id="credito" style=" font-family:Tahoma, Geneva, sans-serif;font-size:20px;"></div>
				<div class="c1">Consumo Mensual </div>
                <div class="c2">
                  <input name="compromiso" type="text" class="text" id="compromiso" style="width:120px;" onkeypress="return tiposMoneda(event,this.value)"/>
                </div>
                <div class="c1">Vendedor</div>
            <div class="c2"><input name="vendedor" type="text" class="text" id="vendedor" style="width:120px;" onkeypress="if(event.keyCode==13){pedirEmpleado(this.value)}else{return solonumeros(event)}"/>
              <input name="buscarvendedor" type="button" class="srch-btn" id="search3" style="float:right" title="Buscar" value=" "/>
            </div>
                <div class="c2" style="width:200px; padding-left:50px; font-size:12px" id="vendedorb"></div>
				<div class="c2" style="width:200px; padding-left:50px; font-size:12px"></div>
		  </div>

       </div>
        <div class="datos-cliente2" style="font-size:11px; ">
          <div class="dvTable clearfix" style="width:540px; margin-left:5px;">
		  	<table width="526" height="182"  border="0" cellpadding="0" cellspacing="0" style="margin:0px 5px;">
				<tbody>
					<tr>
					  <th colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="25%"><div class="c1" style="font-weight:bold; font-size:12px; width:120px; margin-bottom:5px">Datos Prospecto</div></td>
                          <td width="34%">
                            <input name="clienterdo" type="radio" checked disabled="disabled" />
Prospecto
<input name="clienterdo" type="radio" disabled="disabled"/>
Cliente</span></td>
                          <td width="41%"><input name="personamoral" type="radio" value="SI" checked disabled="disabled" />
Persona Moral
  <input name="personamoral" type="radio" value="NO" disabled="disabled"/> <input type="hidden" name="personamoral_valor" value="SI" disabled="disabled">
Persona F&iacute;sica</td>
                        </tr>
                      </table></th>
				  </tr>
					<tr>
					  <th width="99">No. Cliente</th>
					  <th width="166"><input name="prospecto" type="text" class="text" id="prospecto" style="width:120px;" onKeyPress="if(event.keyCode==13){if(ubi.clienterdo[0].checked){pedirProspecto(this.value)}else{pedirCliente(this.value)}}else{return solonumeros(event)}"/></th>
					  <th width="76">&nbsp;</th>
					  <th width="185"><input name="esrenovar" type="hidden" id="esrenovar"><input type="hidden" name="esrenovacion" value="" />
        <input type="hidden" name="provienedefolio" value="" /></th>
				  </tr>
					<tr>
					  <th>Nick</th>
					  <th><input name="nick" type="text" class="text" id="nick" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>RFC</th>
					  <th><input name="rfc" type="text" class="text" id="rfc" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
					<tr>
					  <th>Nombre</th>
					  <th colspan="3"><input name="nombre" type="text" class="text" id="nombre" style="width:362px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					</tr>
					<tr>
					  <th>Ap. Paterno</th>
					  <th><input name="paterno" type="text" class="text" id="paterno" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>Ap. Materno</th>
					  <th><input name="materno" type="text" class="text" id="materno" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
					<tr>
					  <th>Calle</th>
					  <th id="celda_des_calle"><input name="calle" type="text" class="text" id="calle" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>Número</th>
					  <th><input name="numero" type="text" class="text" id="numero" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
					<tr>
					  <th>CP</th>
					  <th><input name="cp" type="text" class="text" id="cp" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>Colonia</th>
					  <th><input name="colonia" type="text" class="text" id="colonia" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
					<tr>
					  <th>Población</th>
					  <th><input name="poblacion" type="text" class="text" id="poblacion" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>Mun./Deleg</th>
					  <th><input name="municipio" type="text" class="text" id="municipio" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
					<tr>
					  <th>Estado</th>
					  <th><input name="estado" type="text" class="text" id="estado" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>País</th>
					  <th><input name="pais" type="text" class="text" id="pais" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
					<tr>
					  <th>Celular</th>
					  <th><input name="celular" type="text" class="text" id="celular" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>Teléfono</th>
					  <th><input name="telefono" type="text" class="text" id="telefono" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
					<tr>
					  <th>Email</th>
					  <th><input name="email" type="text" class="text" id="email" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>Repte. Legal</th>
					  <th><input name="legal" type="text" class="text" id="legal" style="width:120px; height:9px; font-size:9px; margin:0px; text-transform:uppercase" onkeypress="if(event.keyCode==13){document.getElementById('valordeclarado').focus()}"/></th>
				  </tr>
					
					<tr>
					  <th colspan="2" style="font-size:14px; font-weight:bold">Valor Declarado </th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
				  </tr>
					<tr>
					  <th>Costo</th>
					  <th><input name="valordeclarado" id="valordeclarado" type="text" class="text" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
					  <th>L&iacute;mite</th>
					  <th><input name="limite" id="limite" class="text" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
					<tr>
					  <th>Por cada</th>
					  <th><input name="porcada" id="porcada" class="text" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly="" /></th>
					  <th>Costo Extra</th>
					  <th><input name="costoextra" id="costoextra" type="text" class="text" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
				  </tr>
				</tbody>
				</table>
          </div>
        </div>
        </div>
         		<div class="doc-req2">
			    <div class="menu">
					<ul>
						<li class="active" id="pest0" onclick="seleccionarTab(0)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label0" class="active">Guias Normales</a></li>
						<li id="pest1" onclick="seleccionarTab(1)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label1">Servicios G. Normales</a></li>
						<li id="pest2" onclick="seleccionarTab(2)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label2">Guias Empresariales</a></li>
						<li id="pest3" onclick="seleccionarTab(3)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label3">Servicios G. Empresariales</a></li>
					</ul>
				</div>
			  <div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; width:800px; height:320px" class="content-table" id="div0">
			  <table width="100%" height="300" style="margin-top:20px; margin-left:10px;">
          <tr>
            <td width="160"><input type="radio" name="radiogrupo" value="checkbox" />
            Precio por KG</td>
            <td colspan="2"><input type="radio" name="radiogrupo" value="checkbox" />
            Precio por Caja/Paquete </td>
            <td width="208"><input type="radio" name="radiogrupo" />
            Descuento Sobre Flete
            <input type="hidden" name="descmaxpermitido" value="<?=$f->desmaximopermitido?>" /></td>
            <td width="121"><input name="descuentoflete" type="text" class="text" id="descuentoflete" style="width:100px;font-size:11px; margin:0px;" readonly="readonly" /></td>
      </tr>
		  <tr>
            <td colspan="5">
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td><div id="div_preciokg" name="detalle" style="width:750px; height:80px; overflow:auto" align="left">
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
                    <td height="16" class="formato_columnasg" width="55px" align="center" >Zona <?=$zona?><br><?=$f->zoi?>-<?=$f->zof?></td>
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
					</div><input type="hidden" name="tienedatoskg" value="0">
        <input type="hidden" name="tienedatoskg_excedio" value="0"></td>
				</tr>				 		
			</table></td>
          </tr>		 
		  <tr>
		    <td colspan="5">
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="5"><div id="div_descripcion" style="width:750px; height:100px; overflow:auto" align="left">
            <?
						$s = "SELECT * FROM configuraciondetalles GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
                <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                	<tr>
                     <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 0;
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" class="formato_columnasg" width="55px" align="center" >Zona <?=$zona?><br><?=$f->zoi?>-<?=$f->zof?></td>
                    <?
							$zona++;
						}
					?>
                  </tr>
                 <tr>
                 	 <td  class="formato_columnasg" height="16" >Descripcion</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" >&nbsp;</td>
                    <?
						}
					?>
                  </tr>
                </table>
            </div><input type="hidden" name="tienedatosprecio" value="0">
        <input type="hidden" name="tienedatosprecio_excedio" value="0"></td>
				</tr>
			</table>			</td>
      </tr>		  
		 </table>
			  </div>
			
			
		<div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; width:800px; height:320px;visibility:hidden" class="content-table" id="div1">
			<table width="100%" height="300" style="margin-top:20px; margin-left:10px;">
		<tr>
		    <td width="534"><input type="hidden" name="servicios1" value="1">
			<div style="background:#282828; width:720px; height:95px">
			<table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios">
   			</table>
			</div>
			</td>
        </tr>
		<tr>
		  <td><table width="90%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td style="background-color:#282828" height="150px"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
        <td width="384" align="center" style="font-size:20;"><table width="322" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9" height="16"   class="formato_columnas_izq"></td>
            <td align="center"class="formato_columnas" style="text-align:center">SERVICIOS RESTRINGIDOS POR EL CLIENTE <input type="hidden" name="servrestring" value="1"></td>
            </tr>
          <tr>
            <td colspan="11" style="text-align:center; color:#FFFFFF"><input type="checkbox" name="chk_servrest1" >Todos
              <select name="serviciosr1" style="width:200px" class="Tablas" >
              	<option value=""></option>
              	<? 
					$s = "select * from catalogoservicio where restringir = 'SI'";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
				<option value="<?=$f->id?>"><?=$f->descripcion?></option>
				<?
					}
				?>
              </select></td>
          </tr>
          <tr>
            <td colspan="11"><div align="center">
                <select name="serviciosr1_sel" size="7" style="width:265px" >
              </select>
            </div></td>
          </tr>
        </table></td>
        <td width="320" align="center" style="font-size:20"><table width="283" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9" height="16"   class="formato_columnas_izq"></td>
            <td align="center" class="formato_columnas" style="text-align:center">SUCURSALES QUE APLICA EAD Y RECOLECCIONES</td>
            </tr>
          <tr>
            <td colspan="11"><table width="266" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="12" style="text-align:center; color:#FFFFFF">
                  <input type="checkbox" name="chk_sucead1" >Todos
            	 <select name="sucursalesead1" style="width:200px" class="Tablas" )>
                 <option value=""></option>
              	<? 
					$s = "select * from catalogosucursal where id > 1 order by descripcion asc";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
				<option value="<?=$f->id?>"><?=$f->descripcion?></option>
				<?
					}
				?>
                </select></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="11"><div align="center">
                 <select name="sucursalesead1_sel" size="7" style="width:265px" >
              </select>
            </div></td>
          </tr>
        </table></td>
      </tr>
        </table></td>
			  </tr>

			</table>
</td>
	  </tr>	
     </table>
		</div>
		
		<div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; width:800px; height:320px;visibility:hidden" class="content-table" id="div2">
			<table width="100%" height="300" style="margin-top:20px; margin-left:10px;" >
		<tr>
		  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="29"><label>
                <input name="checkprepagadas" type="checkbox" value="0" />
              </label></td>
              <td width="110">Pre-Pagadas</td>
              <td width="184"><div class="etiqueta" style="width:50px;">Limite KG:</div>
                <input name="limitekg" type="text" class="text" style="width:100px; text-align:right;font-size:11px; margin:0px;" value="<?=$limitekg ?>" readonly="readonly">
              </td>
              <td width="48">Costo:</td>
              <td width="121"><input name="costoguia" type="text" class="text" style="width:100px; text-align:right;font-size:11px; margin:0px;"  readonly="readonly" value="<?=$Costo ?>"/>
              </td>
              <td width="146" align="right">Precio KG Excedente:</td>
              <td width="172" style="text-align:left"><input name="preciokgexcedente" type="text" class="text" style="width:70px; text-align:right;font-size:11px; margin:0px;" readonly="readonly" value="<?=$Costo ?>"/></td>
            </tr>
          </table></td>
	  </tr>
		<tr>
		  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="30"><input name="radiobutton" type="radio" value="1" /></td>
              <td width="146">Consignaci&oacute;n KG</td>
              <td width="211"><input name="radiobutton" type="radio" value="2"  />
              Consignaci&oacute;n Paquete </td>
              <td width="180"><input name="radiobutton" type="radio" value="3"  />
              Consignaci&oacute;n Descuento</td>
              <td width="181"><span class="Tablas">
                <?
				$s = "SELECT desmaximopermitido FROM configuradorgeneral";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
                <input name="consignaciondes" type="text" class="text" id="consignaciondes" style="width:100px;font-size:11px; margin:0px;" />
              </span></td>
              <td width="62"><input type="hidden" value="<?=$tarminkgexc?>" name="tarminkgexc" /></td>
            </tr>
          </table></td>
	  </tr>
		
		<tr>
		  <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
            <td width="810" align="right"><div id="detallekgc" style="width:750px; height:80px; overflow:auto" align="left">
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
                    <td height="16" class="formato_columnasg" width="55px" align="center" >Zona <?=$zona?><br><?=$f->zoi?>-<?=$f->zof?></td>
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
            </div> <input type="hidden" name="tienedatoskgc" value="0">
      <input type="hidden" name="tienedatoskgc_excedio" value="0"></td>
          </tr>
        </table></td>
	  </tr>
		<tr>
		  <td><table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
            <td width="810" align="right"><div id="detalledescripcionc" name="detalle" style="width:750px; height:100px; overflow:auto" align="left">
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
                    <td height="16" class="formato_columnasg" width="55px" align="center" >Zona <?=$zona?><br><?=$f->zoi?>-<?=$f->zof?></td>
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
            </div> <input type="hidden" name="tienedatosprecioc" value="0">
        <input type="hidden" name="tienedatosprecioc_excedio" value="0"></td>
          </tr>
        </table></td>
	  </tr>
     </table>
		</div>
		
		
		<!-- 4  -->
		<div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; width:800px; height:320px;visibility:hidden" class="content-table" id="div3">
			<table width="100%" height="300" style="margin-top:20px; margin-left:10px;">
		<tr>
		    <td width="534">
			<div style="background:#282828; width:720px; height:95px">
			<table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios2">
   			</table>
			</div><input type="hidden" name="servicios2" value="1">
			</td>
        </tr>
		<tr>
		  <td><table width="90%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td style="background-color:#282828" height="150px"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
        <td width="384" align="center" style="font-size:20;"><table width="322" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9" height="16"   class="formato_columnas_izq"></td>
            <td align="center"class="formato_columnas" style="text-align:center">SERVICIOS RESTRINGIDOS POR EL CLIENTE </td>
            </tr>
          <tr>
            <td colspan="11" style="text-align:center; color:#FFFFFF"><input type="checkbox" name="chk_servrest2" >Todos
                <select name="serviciosr2" style="width:200px" class="Tablas" >
                  <option value=""></option>
                  <? 
					$s = "select * from catalogoservicio where restringir = 'SI'";
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
          </tr>
          <tr>
            <td colspan="11"><div align="center">
                <select name="serviciosr2_sel" size="7" style="width:265px" >
                </select>
            </div></td>
          </tr>
        </table></td>
        <td width="320" align="center" style="font-size:20"><table width="283" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9" height="16"   class="formato_columnas_izq"></td>
            <td align="center" class="formato_columnas" style="text-align:center">SUCURSALES QUE APLICA EAD Y RECOLECCIONES</td>
            </tr>
          <tr>
            <td colspan="11"><table width="266" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="12" style="text-align:center; color:#FFFFFF">
                  <input type="checkbox" name="chk_sucead2" >Todos
                  <select name="sucursalesead3" style="width:200px" class="Tablas" >
                      <option value=""></option>
                      <? 
					$s = "select * from catalogosucursal where id > 1 order by descripcion asc";
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
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="11"><div align="center">
                <select name="sucursalesead3_sel" size="7" style="width:265px" >
                </select>
            </div></td>
          </tr>
        </table></td>
      </tr>
        </table></td>
			  </tr>

			</table>
</td>
	  </tr>	
     </table>
		</div>
		</div>
		<div class="extra-data3">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">			  
			  <tr>
				<td colspan="2" id="celdatexto" style="font-size:18px">&nbsp;</td>
			  </tr>
			  <tr>
				<td width="53%">&nbsp;</td>
				<td width="47%" id="celdaacciones"><table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="85%" style="text-align:right" ><input name="btnguardar" type="button" class="button" id="btnguardar"  onclick="guardarPropuesta();" value="Guardar Convenio" /></td>
				<td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiarTodo();pedirNuevo()', '')" value="Nuevo"/></td>
			  </tr>
			</table>
			</td>				
				</tr>				
			</table>
		</div>
    </div>
</form>
</body>
</html>
