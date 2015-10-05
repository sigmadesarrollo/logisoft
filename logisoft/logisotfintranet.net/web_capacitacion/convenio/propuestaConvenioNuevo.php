<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "delete from convenio_configurador_caja where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
	mysql_query($s,$l) or die($s);
	$s = "delete from convenio_configurador_preciokg where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
	mysql_query($s,$l) or die($s);
	$s = "delete from convenio_servicios where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
	mysql_query($s,$l) or die($s);
	$s = "delete from convenio_servicios_sucursales where isnull(idconvenio) and idusuario = $_SESSION[IDUSUARIO]";
	mysql_query($s,$l) or die($s);
	
	$s = "SELECT tarifaminimakgexcedente FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$tarminkgexc = $f->tarifaminimakgexcedente;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/reseter.css" rel="stylesheet" type="text/css" />
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/funciones.js"></script>
<script>
	var v_pestanas = Array(true,false,false,false);
	var tablaservicios 			= new ClaseTabla();
	var tablaservicios2			= new ClaseTabla();
	var ubi = document.all;
	var conveniopesokg 			= "";
	var conveniodescripcion 	= "";
	var consignacionpesokg 		= "";
	var consignaciondescripcion = "";
	var folioanteriorrenovar = "";
	
	var accionesnuevo = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="85%" style="text-align:right" ><input name="btnguardar" type="button" class="button" id="btnguardar"  onclick="guardarPropuesta();" value="Guardar Convenio" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo();pedirNuevo()\', \'\')" value="Nuevo"/></td></tr></table>';
				
	var accionesautorizar = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="55%" style="text-align:right"><input name="boton_autorizar" type="button" class="button" id="boton_autorizar"  onclick="darEstadoPropuesta(\'AUTORIZADA\');" value="Autorizar Convenio" /></td><td width="30%" style="text-align:right"><input name="boton_noautorizar" type="button" class="button" id="boton_noautorizar"  onclick="darEstadoPropuesta(\'NO AUTORIZADA\');" value="No Autorizar" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo();pedirNuevo()\', \'\')" value="Nuevo"/></td></tr></table>';
		
	var accionesrenovacion = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="85%" style="text-align:right" ><input name="boton_renovar" type="button" class="button" id="boton_renovar"  onclick="renovarPropuesta();" value="Renovar Propuesta" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo();pedirNuevo()\', \'\')" value="Nuevo"/></td></tr></table>';
		
	var accionesimpresion  = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="55%" style="text-align:right"><input name="boton_renovar" type="button" class="button" id="boton_renovar"  onclick="renovarPropuesta();" value="Renovar Propuesta" /></td><td width="30%" style="text-align:right"><input name="boton_imprimir" type="button" class="button" id="boton_imprimir" value="Imprimir" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo();pedirNuevo()\', \'\')" value="Nuevo"/></td></tr></table>';
		
	var accionespropuesta = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="85%" style="text-align:right" ><input name="btnguardar" type="button" class="button" id="btnguardar"  onclick="guardarPropuesta2();" value="Guardar Convenio" /></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiarTodo();pedirNuevo()\', \'\')" value="Nuevo"/></td></tr></table>';
	
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
	
	window.onload = function(){
		tablaservicios.create();
		tablaservicios2.create();
		conveniopesokg 			= ubi.div_preciokg.innerHTML;
		conveniodescripcion 	= ubi.div_descripcion.innerHTML;
		consignacionpesokg 		= ubi.detallekgc.innerHTML;
		consignaciondescripcion = ubi.detalledescripcionc.innerHTML;	
		pedirNuevo();		
		<?
			$_GET[funcion] = str_replace("\'","'",$_GET[funcion]);
			if($_GET[funcion]!=""){
				echo 'setTimeout("'.$_GET[funcion].'",1500);';
			}
		?>
	}
	
	function pedirNuevo(){
		consultaTexto("nuevosFolios","propuestaconvenio_con.php?accion=12");
	}
	
	function nuevosFolios(datos){
		var objeto = eval(datos);
		
		document.getElementById('folio').value=objeto.folio;
		ubi.fecha.value=objeto.fecha;
		ubi.vigencia.value=objeto.fechalimite;
	}
	
	function mostrarPropuestasPendientesAceptar(){
		abrirVentanaFija('../buscadores_generales/buscarPropuestasPorEstado.php?pestado=EN AUTORIZACION&funcion=solicitarPropuestaConvenio', 650, 450, 'ventana', 'Propuestas pendientes de Aceptacion');
	}
	function solicitarPropuestaConvenio(valor){
		pedirPropuesta(valor)
	}
	
	function bloquearObjeto(objeto,valor){
		if(objeto.type){
			if(objeto.type=="text"){
				objeto.style.backgroundColor = (valor)?"#FFFF99":"";
				objeto.readOnly = valor;
			}else if(objeto.type=="select-one" || objeto.type == "checkbox"){
				objeto.disabled = valor;
			}
		}else{
			if(objeto.length){
				for(var i=0; i<objeto.length; i++)
					objeto[i].disabled = valor;
			}else{
				objeto.style.visibility = (valor)?"hidden":"visible";
			}
		}
	}
	
	function limpiarTodo(){
		ubi.esrenovacion.value = "";
		ubi.provienedefolio.value = "";
		ubi.celdaacciones.innerHTML = accionesnuevo;
		ubi.estadopropuesta.innerHTML = "";
		ubi.vendedor.value = "";
		ubi.vendedorb.innerHTML = "";
		bloquearObjeto(ubi.vendedor,false);	
		bloquearObjeto(ubi.buscarvendedor,false);
		bloquearObjeto(ubi.personamoral,false);
		bloquearObjeto(ubi.prospecto,false);
		bloquearObjeto(ubi.buscarprospecto,false);
		bloquearObjeto(ubi.nombresucursal,false);
		limpiarCliente();
		
		todos(ubi.serviciosr1, ubi.serviciosr1_sel, false, 'SRCONVENIO');
		ubi.chk_servrest1.checked = false;
		todos(ubi.sucursalesead1, ubi.sucursalesead1_sel, false, 'SUCONVENIO');
		ubi.chk_sucead1.checked = false;
		todos(ubi.serviciosr2, ubi.serviciosr2_sel, false, 'SRCONSIGNACION');
		ubi.chk_servrest2.checked = false;
		todos(ubi.sucursalesead3, ubi.sucursalesead3_sel, false, 'SUCONSIGNACION2');
		ubi.chk_sucead2.checked = false;
		bloquearObjeto(ubi.clienterdo,false);
		
		ubi.chk_servrest1.disabled = false;
		ubi.chk_sucead1.disabled = false;
		ubi.chk_servrest2.disabled = false;
		ubi.chk_sucead2.disabled = false;
		
		ubi.nombresucursal.value = ".::SELECCIONE::.";
		ubi.clienterdo[0].checked = true;
		ubi.personamoral[0].checked = true;
		ubi.personamoral_valor.value = "SI";
		
		bloquearObjeto(ubi.boton_agregarpreciokg,true);
		bloquearObjeto(ubi.boton_agregarcajapaquete,true);
		bloquearObjeto(ubi.agregarpreciokg,true);
		bloquearObjeto(ubi.agregardescripcion,true);
		
		ubi.radiogrupo[0].checked = false;
		ubi.radiogrupo[1].checked = false;
		ubi.radiogrupo[2].checked = false;
		
		ubi.checkprepagadas.checked = false;
		ubi.radiobutton[0].checked = false;
		ubi.radiobutton[1].checked = false;
		ubi.radiobutton[2].checked = false;
		
		bloquearObjeto(ubi.radiogrupo,false);
		ubi.descuentoflete.value = "";
		
		ubi.div_preciokg.innerHTML			= conveniopesokg;
		ubi.div_descripcion.innerHTML		= conveniodescripcion;
		ubi.detallekgc.innerHTML			= consignacionpesokg;
		ubi.detalledescripcionc.innerHTML	= consignaciondescripcion;
		
		bloquearObjeto(ubi.botonagregarservicio,false);
		tablaservicios.clear();
		
		bloquearObjeto(ubi.radiobutton,false);
		bloquearObjeto(ubi.checkprepagadas,false);
		
		bloquearObjeto(ubi.limitekg,true);
		bloquearObjeto(ubi.costoguia,true);
		bloquearObjeto(ubi.preciokgexcedente,true);
		ubi.limitekg.value="";
		ubi.costoguia.value="";
		ubi.preciokgexcedente.value = "";
		
		bloquearObjeto(ubi.serviciosr1,false);
		bloquearObjeto(ubi.sucursalesead1,false);
		bloquearObjeto(ubi.serviciosr2,false);
		bloquearObjeto(ubi.sucursalesead3,false);
		ubi.serviciosr1_sel.options.length = 0;
		ubi.sucursalesead1_sel.options.length = 0;
		ubi.serviciosr2_sel.options.length = 0;
		ubi.sucursalesead3_sel.options.length = 0;
		
		bloquearObjeto(ubi.consignaciondes,true);
		ubi.consignaciondes.value = "";
		
		bloquearObjeto(ubi.botonagregarservicio2,false);
		tablaservicios2.clear();
		
		ubi.tienedatoskg.value = 0;
		ubi.tienedatosprecio.value = 0;
		ubi.servicios1.value = 1;
		ubi.servrestring.value = 1;
		ubi.tienedatoskgc.value = 0;
		ubi.tienedatosprecioc.value = 0;
		ubi.servicios2.value = 0;
		ubi.celdatexto.innerHTML = "&nbsp;";
		ubi.valordeclarado.value = "";
		ubi.limite.value = "";
		ubi.porcada.value = "";
		ubi.costoextra.value = "";
		ubi.legal.value = "";
		
		ubi.personamoral[0].disabled = false;
		ubi.personamoral[1].disabled = false;
		ubi.clienterdo[0].disabled = false;
		ubi.clienterdo[1].disabled = false;
		//ubi.seleccionar(0);
		seleccionarTab(0);
	}
	
	function pedirNuevo(){
		consultaTexto("nuevosFolios","propuestaconvenio_con.php?accion=12");
	}
	
	function nuevosFolios(datos){
		var objeto = eval(datos);
		
		document.getElementById('folio').value=objeto.folio;
		ubi.fecha.value=objeto.fecha;
		ubi.vigencia.value=objeto.fechalimite;
	}
	
	function bloquearTodo(){
		bloquearObjeto(ubi.vendedor,true);
		bloquearObjeto(ubi.buscarvendedor,true);
		bloquearObjeto(ubi.clienterdo,true);
		bloquearObjeto(ubi.personamoral,true);
		bloquearObjeto(ubi.nombresucursal,true);
		bloquearObjeto(ubi.prospecto,true);
		bloquearObjeto(ubi.buscarprospecto,true);
		bloquearObjeto(ubi.radiogrupo,true);
		bloquearObjeto(ubi.boton_agregarpreciokg,true);
		bloquearObjeto(ubi.boton_agregarcajapaquete,true);
		bloquearObjeto(ubi.botonagregarservicio,true);
		bloquearObjeto(ubi.serviciosr1,true);
		bloquearObjeto(ubi.sucursalesead1,true);
		bloquearObjeto(ubi.serviciosr1_sel,true);
		bloquearObjeto(ubi.sucursalesead1_sel,true);
		bloquearObjeto(ubi.radiobutton,true);
		bloquearObjeto(ubi.checkprepagadas,true);
		bloquearObjeto(ubi.limitekg,true);
		bloquearObjeto(ubi.costoguia,true);
		bloquearObjeto(ubi.preciokgexcedente,true);
		bloquearObjeto(ubi.serviciosr2,true);
		bloquearObjeto(ubi.serviciosr2_sel,true);
		bloquearObjeto(ubi.consignaciondes,true);
		bloquearObjeto(ubi.agregarpreciokg,true);
		bloquearObjeto(ubi.agregardescripcion,true);
		bloquearObjeto(ubi.botonagregarservicio2,true);
		bloquearObjeto(ubi.sucursalesead3,true);
		bloquearObjeto(ubi.sucursalesead3_sel,true);
		bloquearObjeto(ubi.valordeclarado,true);
		
		bloquearObjeto(ubi.limite, true);
		bloquearObjeto(ubi.porcada,true);
		bloquearObjeto(ubi.costoextra, true);
		bloquearObjeto(ubi.legal, true);
		
		bloquearObjeto(ubi.personamoral[0],true);
		bloquearObjeto(ubi.personamoral[1],true);
		
		bloquearObjeto(ubi.clienterdo[0],true);
		bloquearObjeto(ubi.clienterdo[1],true);
		
		ubi.chk_servrest1.disabled = true;
		ubi.chk_sucead1.disabled = true;
		ubi.chk_servrest2.disabled = true;
		ubi.chk_sucead2.disabled = true;
	}
	
	function pedirPropuesta(valor){
		//alerta3("respPedirPropuesta","propuestaconvenio_con.php?accion=6&valor="+valor+"&folio="+valor+"&ranm="+Math.random());
		consultaTexto("respPedirPropuesta","propuestaconvenio_con.php?accion=6&valor="+valor+"&folio="+valor+"&ranm="+Math.random());
	}
	function respPedirPropuesta(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		limpiarTodo();
		
		var epropuesta 					= objeto[0].propuesta[0].estadopropuesta
		ubi.estadopropuesta.innerHTML	= epropuesta;
		
		if(objeto[0].propuesta[0].renovacionde != "")
			ubi.celdatexto.innerHTML	= "Esta propuesta es renovacion de " + objeto[0].propuesta[0].renovacionde;
		
		ubi.folio.value 				= objeto[0].propuesta[0].folio;
		ubi.fecha.value 				= objeto[0].propuesta[0].fecha;
		ubi.vigencia.value				= objeto[0].propuesta[0].vigencia ;
		ubi.nombresucursal.value 		= objeto[0].propuesta[0].sucursal ;
		ubi.vendedor.value 				= objeto[0].propuesta[0].vendedor ;
		ubi.vendedorb.innerHTML 			= objeto[0].propuesta[0].nvendedor ;
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
		
		ubi.radiogrupo[0].checked 		= (objeto[0].propuesta[0].precioporkg==1)?true:false;
		ubi.radiogrupo[1].checked 		= (objeto[0].propuesta[0].precioporcaja==1)?true:false;
		ubi.radiogrupo[2].checked 		= (objeto[0].propuesta[0].descuentosobreflete==1)?true:false;
		ubi.descuentoflete.value 		= objeto[0].propuesta[0].cantidaddescuento;
		ubi.limitekg.value 				= objeto[0].propuesta[0].limitekg;
		ubi.costoguia.value 			= objeto[0].propuesta[0].costo;
		ubi.costoguia.value				= convertirMoneda(ubi.costoguia.value);
		ubi.preciokgexcedente.value		= objeto[0].propuesta[0].preciokgexcedente;
		ubi.preciokgexcedente.value		= convertirMoneda(ubi.preciokgexcedente.value);
		ubi.consignaciondes.value		= objeto[0].propuesta[0].consignaciondescantidad;
		
		ubi.checkprepagadas.checked		= (objeto[0].propuesta[0].prepagadas==1)?true:false;
		ubi.radiobutton[0].checked		= (objeto[0].propuesta[0].consignacionkg==1)?true:false;
		ubi.radiobutton[1].checked		= (objeto[0].propuesta[0].consignacioncaja==1)?true:false;
		ubi.radiobutton[2].checked		= (objeto[0].propuesta[0].consignaciondescuento==1)?true:false;
		ubi.valordeclarado.value		= objeto[0].propuesta[0].valordeclarado;
		ubi.valordeclarado.value		= convertirMoneda(ubi.valordeclarado.value);
		ubi.limite.value 				= objeto[0].propuesta[0].limite;
		ubi.porcada.value 				= objeto[0].propuesta[0].porcada;
		ubi.costoextra.value 			= objeto[0].propuesta[0].costoextra;
		ubi.costoextra.value			= convertirMoneda(ubi.costoextra.value);
		ubi.legal.value 				= objeto[0].propuesta[0].legal;
		
		if(objeto[0].serviciocombo1[0] != undefined && objeto[0].serviciocombo1[0].nombre!="TODOS"){			
			agregarValores(ubi.serviciosr1_sel,objeto[0].serviciocombo1);
			
		}else if(objeto[0].serviciocombo1[0] != undefined && objeto[0].serviciocombo1[0].nombre=="TODOS"){
			todos(ubi.serviciosr1, ubi.serviciosr1_sel, true, 'SRCONVENIO');
			ubi.chk_servrest1.checked = true;
		}
		if(objeto[0].serviciocombo2[0] != undefined && objeto[0].serviciocombo2[0].nombre!="TODOS"){
			agregarValores(ubi.sucursalesead1_sel,objeto[0].serviciocombo2);
			
		}else if(objeto[0].serviciocombo2[0] != undefined && objeto[0].serviciocombo2[0].nombre=="TODOS"){
			todos(ubi.sucursalesead1, ubi.sucursalesead1_sel, true, 'SUCONVENIO');
			ubi.chk_sucead1.checked = true;
		}
		
		if(objeto[0].serviciocombo3[0] != undefined && objeto[0].serviciocombo3[0].nombre!="TODOS"){
			agregarValores(ubi.serviciosr2_sel,objeto[0].serviciocombo3);
			
		}else if(objeto[0].serviciocombo3[0] != undefined && objeto[0].serviciocombo3[0].nombre=="TODOS"){
			todos(ubi.serviciosr2, ubi.serviciosr2_sel, true, 'SRCONSIGNACION');
			ubi.chk_servrest2.checked = true;
		}
		
		if(objeto[0].serviciocombo5[0] != undefined && objeto[0].serviciocombo5[0].nombre!="TODOS"){
			agregarValores(ubi.sucursalesead3_sel,objeto[0].serviciocombo5);
			
		}else if(objeto[0].serviciocombo5[0] != undefined && objeto[0].serviciocombo5[0].nombre=="TODOS"){
			todos(ubi.sucursalesead3, ubi.sucursalesead3_sel, true, 'SUCONSIGNACION2');
			ubi.chk_sucead2.checked = true;
		}
		
		agregarGrid(tablaservicios,objeto[0].serviciogrid1);
		agregarGrid(tablaservicios2,objeto[0].serviciogrid2);
		
		if(objeto[0].propuesta[0].precioporkg==1){
			consultaTexto("mostrarCGridKg", "propuestaconvenio_con.php?accion=7&valor=1&idconvenio="+objeto[0].propuesta[0].folio);
			ubi.tienedatoskg.value = 1;
			ubi.boton_agregarpreciokg.style.visibility = 'visible';
		}
		if(objeto[0].propuesta[0].precioporcaja==1){
			consultaTexto("mostrarCGridPeso", "propuestaconvenio_con.php?accion=7&valor=2&idconvenio="+objeto[0].propuesta[0].folio)
			ubi.tienedatosprecio.value = 1;
			ubi.boton_agregarcajapaquete.style.visibility = 'visible';
		}
		if(objeto[0].propuesta[0].consignacionkg==1){
			consultaTexto("mostrarSGridKg", "propuestaconvenio_con.php?accion=7&valor=3&idconvenio="+objeto[0].propuesta[0].folio)
			ubi.tienedatoskg.value = 1;
			ubi.agregarpreciokg.style.visibility = 'visible';
		}
		if(objeto[0].propuesta[0].consignacioncaja==1){
			consultaTexto("mostrarSGridPeso", "propuestaconvenio_con.php?accion=7&valor=4&idconvenio="+objeto[0].propuesta[0].folio)
			ubi.tienedatosprecioc.value = 1;
			ubi.agregardescripcion.style.visibility = 'visible';
		}
		

			if(epropuesta=="EN AUTORIZACION (ok)" || epropuesta=="EN AUTORIZACION (x)"){
				ubi.celdaacciones.innerHTML = accionesautorizar;
				bloquearTodo();
			}
			if(epropuesta=="AUTORIZADA"){
				ubi.celdaacciones.innerHTML = accionesimpresion;
				bloquearTodo();
			}
			if(epropuesta=="NO AUTORIZADA"){
				ubi.celdaacciones.innerHTML = accionesrenovacion;
				bloquearTodo();
			}
			if(epropuesta=="PROPUESTA"){
				ubi.celdaacciones.innerHTML = accionespropuesta;
			}				
	}
	
	function mostrarCGridKg(datos){
		ubi.div_preciokg.innerHTML = datos;
	}
	function mostrarCGridPeso(datos){
		ubi.div_descripcion.innerHTML = datos;
	}
	function mostrarSGridKg(datos){
		ubi.detallekgc.innerHTML = datos;
	}
	function mostrarSGridPeso(datos){
		ubi.detalledescripcionc.innerHTML = datos;
	}
	
	function pedirEmpleado(valor){
		document.all.vendedor.value = valor;
		consultaTexto("mostrarEmpleado", "propuestaconvenio_con.php?accion=1&valor="+valor+"&sucursal="+document.all.nombresucursal.value+"&valram="+Math.random());
	}
	function mostrarEmpleado(valor){
		document.all.vendedorb.innerHTML = valor;
	}
	function pedirProspecto(valor){
		consultaTexto("mostrarProspecto", "propuestaconvenio_con.php?accion=2&personamoral="+document.all.personamoral_valor.value+"&valor="+valor+"&valram="+Math.random());
	}
	function mostrarProspecto(datos){
		var objeto = eval(datos);
		limpiarCliente();
		if(objeto.length){
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
	function pedirCliente(valor){
		consultaTexto("mostrarCliente", "propuestaconvenio_con.php?accion=11&personamoral="+document.all.personamoral_valor.value+"&valor="+valor+"&valram="+Math.random());
	}
	function mostrarCliente(datos){
		var objeto = eval(datos);
		limpiarCliente();
		if(objeto.length){
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
			alerta3("No se encontraron datos del cliente", "¡Atención!");
		}
	}
	function limpiarCliente(){
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
	
	//trabajar con los servicios
	function agregarServicio(objeto){
		if(tablaservicios.getValuesFromField("servicio").indexOf(objeto.servicio)<0)
			tablaservicios.add(objeto);
		ubi.servicios1.value = 0;
	}
	function modificarServicios(objeto){
		tablaservicios.deleteById(tablaservicios.getSelectedIdRow());
		tablaservicios.add(objeto);
		info("El servicio fue modificado","¡Atención!");
	}
	function borrarServicios(){
		tablaservicios.deleteById(tablaservicios.getSelectedIdRow());
		info("El servicio ha sido borrado","¡Atención!");
	}
	
	function agregarServicio2(objeto){
		if(tablaservicios2.getValuesFromField("servicio").indexOf(objeto.servicio)<0)
			tablaservicios2.add(objeto);
		ubi.servicios2.value = 0;
		
		var serv = tablaservicios2.getValuesFromField('idservicio',',');
		if(serv.indexOf("7")>-1 || serv.indexOf("8")>-1){
			if(ubi.checkprepagadas.checked==true){
				todos(ubi.sucursalesead3, ubi.sucursalesead3_sel, true, 'SUCONSIGNACION2');
			}
		}
	}
	function modificarServicios2(objeto){
		tablaservicios2.deleteById(tablaservicios2.getSelectedIdRow());
		tablaservicios2.add(objeto);
		info("El servicio fue modificado","¡Atención!");
	}
	function borrarServicios2(){
		tablaservicios2.deleteById(tablaservicios2.getSelectedIdRow());
		
		var serv = tablaservicios2.getValuesFromField('idservicio',',');
		if(serv.indexOf("7")<0 && serv.indexOf("8")<0){ 			
			todos(ubi.sucursalesead3, ubi.sucursalesead3_sel, false, 'SUCONSIGNACION2');
		}
		
		info("El servicio ha sido borrado","¡Atención!");
	}
	
	function mostrarServicios(){
		if(ubi.prospecto.readOnly==false){
			if(tablaservicios.getSelectedRow()){
				var obj = tablaservicios.getSelectedRow();
				
				abrirVentanaFija('servicios.php?tipo=CONVENIO&fagregar=agregarServicio&fborrar=borrarServicios&fmodificar=modificarServicios&servicio='
				+obj.servicio+'&cobro='+obj.cobro+'&precio='+obj.precio+'&limpiar='+ubi.servicios1.value, 500, 400, 'ventana', 'Servicios');
			}
		}
	}
	function mostrarServicios2(){
		if(ubi.prospecto.readOnly==false){
			if(tablaservicios2.getSelectedRow()){
				var obj = tablaservicios2.getSelectedRow();
				
				abrirVentanaFija('servicios.php?tipo=CONSIGNACION&fagregar=agregarServicio2&fborrar=borrarServicios2&fmodificar=modificarServicios2&servicio='
				+obj.servicio+'&cobro='+obj.cobro+'&precio='+obj.precio+'&limpiar='+ubi.servicios2.value, 500, 400, 'ventana', 'Servicios');
			}
		}
	}
	
	function agregarGrid(tabla,objeto){
		for(var i=0; i<objeto.length; i++)
			tabla.add(objeto[i]);
	}
	
	//funciones para los combos
	function insertarServicio(combo, valor, va, nombre, tipo){
		if(combo.value!=""){
			
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].value==valor){
					alerta3(nombre+" seleccionado ya fue agregado","¡Atención!");
					combo.value="";
					return false;
				}
			}
			
			
			consultaTexto("respServicio","propuestaconvenio_con.php?accion=4&valor=1&idservicio="+combo.value
						  +"&servicio="+combo.options[combo.selectedIndex].text+"&tipo="+tipo+"&limpiar="+ubi.servrestring.value);
			ubi.servrestring.value = 0;
			var opcion = new Option(combo.options[combo.selectedIndex].text,combo.value);
			va.options[va.options.length] = opcion;
			combo.value="";
		}
	}
	
	function borrarServicio(va,tipo){
		if(va.options.selectedIndex>-1){
			consultaTexto("respServicio","propuestaconvenio_con.php?accion=4&valor=2&idservicio="+va.value
						  +"&tipo="+tipo);
			va.options[va.options.selectedIndex] = null;
			va.value = "";
		}
	}
	function respServicio(res){
		if(res.indexOf("bien")<0)
			alerta3(res,"Error");
			//ubi.celdatexto.innerHTML = res;
	}
	function todos(combo1, combo2, boleano, tipo){
		combo2.options.length=0;
		if(boleano){
			combo1.disabled = true;
			combo2.disabled = true;
			for(var i=0; i<combo1.options.length; i++)
				combo2.options[i] = new Option(combo1.options[i].text, combo1.options[i].value);
			
			consultaTexto("respServicio","propuestaconvenio_con.php?accion=4&valor=3&tipo="+tipo);
		}else{
			combo1.disabled = false;
			combo2.disabled = false;
			consultaTexto("respServicio","propuestaconvenio_con.php?accion=4&valor=4&tipo="+tipo);
		}
	}
	function agregarValores(combo,objeto){
		combo.options.length = 0;
		var opcion;
		for(var i=0; i<objeto.length; i++){
			opcion = new Option(objeto[i].nombre,objeto[i].clave);
			
			combo.options[combo.options.length] = opcion;
		}
	}
	
	function desactivarPrepagadas(valor){
		ubi.limitekg.style.backgroundColor = (valor)?"#FFFF99":"";
		ubi.costoguia.style.backgroundColor = (valor)?"#FFFF99":"";
		ubi.preciokgexcedente.style.backgroundColor = (valor)?"#FFFF99":"";
		ubi.preciokgexcedente.readOnly = valor;
		ubi.limitekg.readOnly = valor;
		ubi.costoguia.readOnly = valor;
		ubi.limitekg.value = "";
		ubi.costoguia.value = "";
		ubi.preciokgexcedente.value = "";
		
		var serv = tablaservicios2.getValuesFromField('idservicio',',');
		if(serv.indexOf("7")>-1 || serv.indexOf("8")>-1){
			if(ubi.checkprepagadas.checked==false){
				todos(ubi.sucursalesead3, ubi.sucursalesead3_sel, false, 'SUCONSIGNACION2');
			}
		}		
	}
	
	function desactivarPesokg(valor){
		ubi.agregarpreciokg.style.visibility = (valor)?"hidden":"visible";
		ubi.detallekgc.innerHTML = consignacionpesokg;
		ubi.tienedatoskgc.value = 0;
	}
	function desactivarDescripcion(valor){
		ubi.agregardescripcion.style.visibility = (valor)?"hidden":"visible";
		ubi.detalledescripcionc.innerHTML = consignaciondescripcion;
		ubi.tienedatosprecioc.value = 0;
	}
	function desactivarDescuento(valor){
		ubi.consignaciondes.style.backgroundColor = (valor)?"#FFFF99":"";
		ubi.consignaciondes.readOnly = valor;
		ubi.consignaciondes.value = "";
	}
	
	function desactivarConsignacion(valor){
		ubi.consignaciondes.value = "";
		desactivarPesokg(true);
		desactivarDescripcion(true);
		desactivarDescuento(true);
		switch (valor){
			case 2:
				desactivarPesokg(false);
				break;
			case 3:
				desactivarDescripcion(false);
				desactivarDescuento(false);
				break;
			case 4:
				desactivarDescuento(false);
				break;
		}
	}
	function seleccionar(){	}
	function obtenerIndice(){ }
	
	function guardarPropuesta(){		
		<?=$cpermiso->verificarPermiso("289",$_SESSION[IDUSUARIO]);?>
		if(ubi.vendedor.value==""){
			alerta("Por favor ingrese el vendedor", "¡Atención!","vendedor");
			return false;
		}
		if(ubi.radiogrupo[0].checked==true && ubi.tienedatoskg.value=="0"){
			alerta3("Debe capturar precios por Kilogramo para guia normal", "¡Atención!");
			return false;
		}
		if(ubi.radiogrupo[1].checked==true && ubi.tienedatosprecio.value=="0"){
			alerta3("Debe capturar precios por Caja / Paquete para guia normal", "¡Atención!");
			return false;
		}
		if(ubi.radiogrupo[2].checked==true && ubi.descuentoflete.value==""){
			alerta3("Debe capturar Descuento sobre flete para guia normal", "¡Atención!");
			tabs.seleccionar(0);
			return false;
		}
		if(ubi.checkprepagadas.checked==true){
			if(ubi.limitekg.value==""){
				alerta("Si va a seleccionar prepagadas por favor agregue Limite de Peso", "¡Atención!","limitekg");
				return false;
			}
			if(ubi.costoguia.value==""){
				alerta("Si va a seleccionar prepagadas por favor agregue Costo de la Guia", "¡Atención!","costoguia");
				return false;
			} 
			if(ubi.preciokgexcedente.value==""){
				alerta("Si va a seleccionar prepagadas por favor agregue Precio Kg Excedente", "¡Atención!","preciokgexcedente");	
				return false;
			}
		}
		
		if(ubi.radiobutton[0].checked==true && ubi.tienedatoskgc.value=="0"){
			alerta3("Debe capturar precios por Kilogramo para guia empresarial", "¡Atención!");
			return false;
		}
		if(ubi.radiobutton[1].checked==true && ubi.tienedatosprecioc.value=="0"){
			alerta3("Debe capturar precios por Caja / Paquete para guia empresarial", "¡Atención!");
			return false;
		}
		if(ubi.radiobutton[2].checked==true && ubi.consignaciondes.value==""){
			alerta3("Debe capturar Descuento sobre flete para guia empresarial", "¡Atención!");
			tabs.seleccionar(2);
			return false;
		}
		
		if(ubi.nombre.value==""){
			alerta("Por favor ingrese el prospecto", "¡Atención!","prospecto");
		}else if((ubi.radiogrupo[1].checked && ubi.descuentoflete.value=="") || (ubi.radiogrupo[2].checked && ubi.descuentoflete.value=="")){
			tabs.seleccionar(0);
			alerta("Por favor ingrese el descuento sobre flete", "¡Atención!","descuentoflete");
		}else{
			
			
			var servicio1 = "no";
			var servicio2 = "no";
			
			for(var i=0; i<tablaservicios.getRecordCount();i++){
				if(document.all['tabladeservicios_Servicio'][i].value=="E.A.D." || document.all['tabladeservicios_Servicio'][i].value=="RECOLECCION"){
					servicio1 = "si";
				}
			}
			
			for(var i=0; i<tablaservicios2.getRecordCount();i++){
				if(document.all['tabladeservicios2_Servicio'][i].value=="E.A.D." || document.all['tabladeservicios2_Servicio'][i].value=="RECOLECCION"){
					servicio2 = "si";
				}
			}
			
			if(servicio1=='si'){
				if(document.all.sucursalesead1_sel.options.length==0){
					alerta("Si selecciona servicios gratuitos de E.A.D. o Recoleccion tiene que agregar las sucursales donde se aplicara gratuitamente para las Guias de Ventanilla","¡Atención!","sucursalesead1_sel");
					return false;
				}
			}
			
			if(servicio2=='si'){
				if(document.all.sucursalesead3_sel.options.length==0){
					alerta("Si selecciona servicios gratuitos de E.A.D. o Recoleccion tiene que agregar las sucursales donde se aplicara gratuitamente para las Guias Empresariales","¡Atención!","sucursalesead3_sel");
					return false;
				}
			}
			
			
			var folio 					= ubi.folio.value;
			var fecha 					= ubi.fecha.value;
			var vigencia 				= ubi.vigencia.value;
			var sucursal 				= ubi.nombresucursal.value;
			var vendedor 				= ubi.vendedor.value;
			var nvendedor 				= ubi.vendedorb.innerHTML;
			var personamoral 			= (ubi.personamoral[0].checked)?"1":"0";
			var idprospecto 			= ubi.prospecto.value;
			var precioporkg 			= (ubi.radiogrupo[0].checked)?"1":"0";
			var precioporcaja 			= (ubi.radiogrupo[1].checked)?"1":"0";
			var descuentosobreflete 	= (ubi.radiogrupo[2].checked)?"1":"0";
			var cantidaddescuento 		= ubi.descuentoflete.value;
			var limitekg 				= ubi.limitekg.value;
			var costo 					= ubi.costoguia.value.replace("$ ","").replace(/,/g,"");
			var preciokgexcedente		= ubi.preciokgexcedente.value.replace("$ ","").replace(/,/g,"");
			var valordeclarado			= ubi.valordeclarado.value.replace("$ ","").replace(/,/g,"");
			var limite					= ubi.limite.value;
			var porcada					= ubi.porcada.value;
			var costoextra				= ubi.costoextra.value.replace("$ ","").replace(/,/g,"");
			var legal					= ubi.legal.value;
			var pestado 				= "";
			var esrenovacion			= ubi.esrenovacion.value;
			var provienedefolio 		= ubi.provienedefolio.value;
			
			if(parseFloat(ubi.descmaxpermitido.value)<parseFloat(ubi.descuentoflete.value) 
				|| parseFloat(ubi.descmaxpermitido.value)<parseFloat(ubi.consignaciondes.value)
				|| ubi.tienedatosprecioc_excedio.value == 1
				|| ubi.tienedatoskgc_excedio.value == 1
				|| ubi.tienedatosprecio_excedio.value == 1
				|| ubi.tienedatoskg_excedio.value == 1
				|| (ubi.tarminkgexc.value>0 && ubi.tarminkgexc.value!="" && parseFloat(ubi.preciokgexcedente.value) < parseFloat(ubi.tarminkgexc.value) )){
				pestado = "EN AUTORIZACION (x)";
			}else{
				pestado = "EN AUTORIZACION (ok)";
			}
			
			var prepagadas				= (ubi.checkprepagadas.checked)?"1":"0";
			var consignacionkg 			= (ubi.radiobutton[0].checked)?"1":"0";
			var consignacioncaja 		= (ubi.radiobutton[1].checked)?"1":"0";
			var consignaciondescuento 	= (ubi.radiobutton[2].checked)?"1":"0";
			var consignaciondescantidad = ubi.consignaciondes.value;
			
			var tipoc 					= (ubi.clienterdo[0].checked)?"PRO":"CLI";
					
			document.all.boton_guardar.style.visibility = 'hidden';
			
			consultaTexto("resGuardarPropuesta", "propuestaconvenio_con.php?accion=5&vigencia="+vigencia
			+"&esrenovacion="+esrenovacion+"&provienedefolio="+provienedefolio
			+"&sucursal="+sucursal+"&vendedor="+vendedor+"&nvendedor="+nvendedor+"&estado="+pestado+"&tipoc="+tipoc
			+"&personamoral="+personamoral+"&idprospecto="+idprospecto+"&precioporkg="+precioporkg
			+"&precioporcaja="+precioporcaja+"&descuentosobreflete="+descuentosobreflete+"&cantidaddescuento="+cantidaddescuento
			+"&limitekg="+limitekg+"&costo="+costo+"&prepagadas="+prepagadas+"&consignacionkg="+consignacionkg
			+"&consignacioncaja="+consignacioncaja+"&consignaciondescuento="+consignaciondescuento
			+"&consignaciondescantidad="+consignaciondescantidad+"&preciokgexcedente="+preciokgexcedente
			+"&valordeclarado="+valordeclarado+"&limite="+limite
			+"&porcada="+porcada+"&costoextra="+costoextra+"&legal="+legal+"&random="+Math.random());
		}
	}
	function resGuardarPropuesta(res){
		//ubi.celdatexto.innerHTML = res;
		if(res.indexOf("guardo")>-1){
			info("La propuesta de convenio ha sido guardada con estado "+res.split(",")[2], "¡Atención!");
			ubi.estadopropuesta.innerHTML = res.split(",")[2];
			ubi.folio.value = res.split(",")[1];
			ubi.celdaacciones.innerHTML = accionesautorizar;
			bloquearTodo();
		}else{
			alerta3("Error al guardar",res);
			if(document.all.boton_guardar){
				document.all.boton_guardar.style.visibility = 'visible';
			}
		}
	}
	
	function darEstadoPropuesta(estado){
		<?=$cpermiso->verificarPermiso("288",$_SESSION[IDUSUARIO]);?>
		document.all.boton_autorizar.style.visibility = 'hidden';
		document.all.boton_noautorizar.style.visibility = 'hidden';
		consultaTexto("resDarEstadoP","propuestaconvenio_con.php?accion=8&estado="+estado+"&folio="+ubi.folio.value);
	}
	function resDarEstadoP(res){
		if(res.indexOf("modificada")>-1){
			info("La propuesta ha sido "+res.split(",")[1], "¡Atención!");
			ubi.estadopropuesta.innerHTML = res.split(",")[1];
			if(res.split(",")[1]=="AUTORIZADA")
			ubi.celdaacciones.innerHTML = accionesimpresion;
			else
			ubi.celdaacciones.innerHTML = accionespropuesta;
		}else{
			alerta3("Error al guardar",res);
		}
	}
	function renovarPropuesta(){
		confirmar('¿Desea renovar la propuesta?', '¡Atención!','confirmarRenovacion()','');		
	}
	function confirmarRenovacion(){
		var valor = ubi.folio.value;
		limpiarTodo();
		pedirNuevo();
		ubi.celdaacciones.innerHTML = accionesnuevo;
		//alerta3("propuestaconvenio_con.php?accion=6&valor="+valor+"&folio="+valor+"&ranm="+Math.random());
		folioanteriorrenovar = valor;
		ubi.provienedefolio.value = valor;
		consultaTexto("respPedirPropuestaRe","propuestaconvenio_con.php?accion=6&valor="+valor+"&folio="+valor+"&ranm="+Math.random());
	}
	function respPedirPropuestaRe(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
				
		var epropuesta 					= objeto[0].propuesta[0].estadopropuesta;
		ubi.estadopropuesta.innerHTML	= "";
		
		if(objeto[0].propuesta[0].renovacionde != "")
			ubi.celdatexto.innerHTML	= "Esta propuesta es renovacion de " + objeto[0].propuesta[0].renovacionde;
		
		//ubi.folio.value 				= objeto[0].propuesta[0].folio;
		ubi.fecha.value 				= objeto[0].propuesta[0].fecha;
		ubi.vigencia.value				= objeto[0].propuesta[0].vigencia ;
		ubi.nombresucursal.value 		= objeto[0].propuesta[0].sucursal ;
		ubi.vendedor.value 				= objeto[0].propuesta[0].vendedor ;
		ubi.vendedorb.innerHTML 			= objeto[0].propuesta[0].nvendedor ;
		if(objeto[0].propuesta[0].personamoral!=0){
			ubi.personamoral[0].checked = true;
		}else{
			ubi.personamoral[1].checked = true;
		}
		if(objeto[0].propuesta[0].tipo=='PRO'){
			ubi.clienterdo[0].checked = true;
		}else{
			ubi.clienterdo[1].checked = true;
		}
		ubi.esrenovacion.value			= "SI";
		ubi.provienedefolio.value 		= folioanteriorrenovar;
		
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
		
		ubi.radiogrupo[0].checked 		= (objeto[0].propuesta[0].precioporkg==1)?true:false;
		ubi.radiogrupo[1].checked 		= (objeto[0].propuesta[0].precioporcaja==1)?true:false;
		ubi.radiogrupo[2].checked 		= (objeto[0].propuesta[0].descuentosobreflete==1)?true:false;

		ubi.descuentoflete.value 		= objeto[0].propuesta[0].cantidaddescuento;
		ubi.limitekg.value 				= objeto[0].propuesta[0].limitekg;
		ubi.costoguia.value 			= objeto[0].propuesta[0].costo;
		ubi.costoguia.value 			= convertirMoneda(ubi.costoguia.value);
		ubi.preciokgexcedente.value		= objeto[0].propuesta[0].preciokgexcedente;
		ubi.preciokgexcedente.value 	= convertirMoneda(ubi.preciokgexcedente.value);
		ubi.consignaciondes.value		= objeto[0].propuesta[0].consignaciondescantidad;
		
		ubi.checkprepagadas.checked		= (objeto[0].propuesta[0].prepagadas==1)?true:false;
		ubi.radiobutton[0].checked		= (objeto[0].propuesta[0].consignacionkg==1)?true:false;
		ubi.radiobutton[1].checked		= (objeto[0].propuesta[0].consignacioncaja==1)?true:false;
		ubi.radiobutton[2].checked		= (objeto[0].propuesta[0].consignaciondescuento==1)?true:false;
		ubi.valordeclarado.value		= objeto[0].propuesta[0].valordeclarado;
		ubi.valordeclarado.value		= convertirMoneda(ubi.valordeclarado.value);
		ubi.limite.value				= objeto[0].propuesta[0].limite;
		ubi.porcada.value				= objeto[0].propuesta[0].porcada;
		ubi.costoextra.value			= objeto[0].propuesta[0].costoextra;
		ubi.costoextra.value			= convertirMoneda(ubi.costoextra.value);
		ubi.legal.value					= objeto[0].propuesta[0].legal;
		
		if(objeto[0].serviciocombo1[0] != undefined && objeto[0].serviciocombo1[0].nombre!="TODOS"){			
			agregarValores(ubi.serviciosr1_sel,objeto[0].serviciocombo1);
			
		}else if(objeto[0].serviciocombo1[0] != undefined && objeto[0].serviciocombo1[0].nombre=="TODOS"){
			todos(ubi.serviciosr1, ubi.serviciosr1_sel, true, 'SRCONVENIO');
			ubi.chk_servrest1.checked = true;
		}
		if(objeto[0].serviciocombo2[0] != undefined && objeto[0].serviciocombo2[0].nombre!="TODOS"){
			agregarValores(ubi.sucursalesead1_sel,objeto[0].serviciocombo2);
			
		}else if(objeto[0].serviciocombo2[0] != undefined && objeto[0].serviciocombo2[0].nombre=="TODOS"){
			todos(ubi.sucursalesead1, ubi.sucursalesead1_sel, true, 'SUCONVENIO');
			ubi.chk_sucead1.checked = true;
		}
		
		if(objeto[0].serviciocombo3[0] != undefined && objeto[0].serviciocombo3[0].nombre!="TODOS"){
			agregarValores(ubi.serviciosr2_sel,objeto[0].serviciocombo3);
			
		}else if(objeto[0].serviciocombo3[0] != undefined && objeto[0].serviciocombo3[0].nombre=="TODOS"){
			todos(ubi.serviciosr2, ubi.serviciosr2_sel, true, 'SRCONSIGNACION');
			ubi.chk_servrest2.checked = true;
		}
		
		if(objeto[0].serviciocombo5[0] != undefined && objeto[0].serviciocombo5[0].nombre!="TODOS"){
			agregarValores(ubi.sucursalesead3_sel,objeto[0].serviciocombo5);
			
		}else if(objeto[0].serviciocombo5[0] != undefined && objeto[0].serviciocombo5[0].nombre=="TODOS"){
			todos(ubi.sucursalesead3, ubi.sucursalesead3_sel, true, 'SUCONSIGNACION2');
			ubi.chk_sucead2.checked = true;
		}
		
		agregarGrid(tablaservicios,objeto[0].serviciogrid1);
		agregarGrid(tablaservicios2,objeto[0].serviciogrid2);
		
		if(objeto[0].propuesta[0].precioporkg==1){
			consultaTexto("mostrarCGridKg", "propuestaconvenio_con.php?accion=7&valor=1&idconvenio="+objeto[0].propuesta[0].folio);
			ubi.tienedatoskg.value = 1;
			ubi.boton_agregarpreciokg.style.visibility = 'visible';
		}
		if(objeto[0].propuesta[0].precioporcaja==1){
			consultaTexto("mostrarCGridPeso", "propuestaconvenio_con.php?accion=7&valor=2&idconvenio="+objeto[0].propuesta[0].folio)
			ubi.tienedatosprecio.value = 1;
			ubi.boton_agregarcajapaquete.style.visibility = 'visible';
		}
		if(objeto[0].propuesta[0].consignacionkg==1){
			consultaTexto("mostrarSGridKg", "propuestaconvenio_con.php?accion=7&valor=3&idconvenio="+objeto[0].propuesta[0].folio)
			ubi.tienedatoskgc.value = 1;
			ubi.agregarpreciokg.style.visibility = 'visible';
		}
		if(objeto[0].propuesta[0].consignacioncaja==1){
			consultaTexto("mostrarSGridPeso", "propuestaconvenio_con.php?accion=7&valor=4&idconvenio="+objeto[0].propuesta[0].folio)
			ubi.tienedatosprecioc.value = 1;
			ubi.agregardescripcion.style.visibility = 'visible';
		}	
	}
	
	function resRenovarPropuesta(res){
		if(res.indexOf("renovada")>-1){
			info("La propuesta ha sido renovada "+res.split(",")[1], "¡Atención!");
			ubi.celdatexto.innerHTML = "Esta propuesta es renovacion del folio "+res.split(",")[2];
			ubi.estadopropuesta.innerHTML = res.split(",")[1];
			ubi.celdaacciones.innerHTML = accionesautorizar;
		}else{
			alerta3("Error al guardar",res);
		}
	}
	
	function guardarPropuesta2(){
		<?=$cpermiso->verificarPermiso("289",$_SESSION[IDUSUARIO]);?>
		var folio 					= ubi.folio.value;
		var fecha 					= ubi.fecha.value;
		var vigencia 				= ubi.vigencia.value;
		var sucursal 				= ubi.nombresucursal.value;
		var vendedor 				= ubi.vendedor.value;
		var nvendedor 				= ubi.vendedorb.innerHTML;
		var personamoral 			= (ubi.personamoral.value)?"1":"0";
		var idprospecto 			= ubi.prospecto.value;
		var precioporkg 			= (ubi.radiogrupo[0].checked)?"1":"0";
		var precioporcaja 			= (ubi.radiogrupo[1].checked)?"1":"0";
		var descuentosobreflete 	= (ubi.radiogrupo[2].checked)?"1":"0";
		var cantidaddescuento 		= ubi.descuentoflete.value;
		var limitekg 				= ubi.limitekg.value;
		var costo 					= ubi.costoguia.value.replace("$ ","").replace(/,/g,"");
		var preciokgexcedente		= ubi.preciokgexcedente.value.replace("$ ","").replace(/,/g,"");
		
		var valordeclarado 			= ubi.valordeclarado.value.replace("$ ","").replace(/,/g,"");
		var limite		 			= ubi.limite.value;
		var porcada 				= ubi.porcada.value;
		var costoextra 				= ubi.costoextra.value.replace("$ ","").replace(/,/g,"");
		var legal 					= ubi.legal.value;
		
		var pestado = "";
		
		if(parseFloat(ubi.descmaxpermitido.value)<parseFloat(ubi.descuentoflete.value) 
			|| parseFloat(ubi.descmaxpermitido.value)<parseFloat(ubi.consignaciondes.value)
			|| ubi.tienedatosprecioc_excedio.value == 1
			|| ubi.tienedatoskgc_excedio.value == 1
			|| ubi.tienedatosprecio_excedio.value == 1
			|| ubi.tienedatoskg_excedio.value == 1){
			pestado = "EN AUTORIZACION";
		}else{
			pestado = "AUTORIZADA";
		}
		
		var prepagadas				= (ubi.rcheckprepagadas.checked)?"1":"0";
		var consignacionkg 			= (ubi.radiobutton[0].checked)?"1":"0";
		var consignacioncaja 		= (ubi.radiobutton[1].checked)?"1":"0";
		var consignaciondescuento 	= (ubi.radiobutton[2].checked)?"1":"0";
		var consignaciondescantidad = ubi.consignaciondes.value;
		consultaTexto("resGuardarPropuesta2", "propuestaconvenio_con.php?accion=10&vigencia="+vigencia+"&folio="+ubi.folio.value
		+"&sucursal="+sucursal+"&vendedor="+vendedor+"&nvendedor="+nvendedor+"&estado="+pestado
		+"&personamoral="+personamoral+"&idprospecto="+idprospecto+"&precioporkg="+precioporkg
		+"&precioporcaja="+precioporcaja+"&descuentosobreflete="+descuentosobreflete+"&cantidaddescuento="+cantidaddescuento
		+"&limitekg="+limitekg+"&costo="+costo+"&prepagadas="+prepagadas+"&consignacionkg="+consignacionkg
		+"&consignacioncaja="+consignacioncaja+"&consignaciondescuento="+consignaciondescuento
		+"&consignaciondescantidad="+consignaciondescantidad+"&preciokgexcedente="+preciokgexcedente
		+"&valordeclarado="+valordeclarado+"&limite="+limite
		+"&porcada="+porcada+"&costoextra="+costoextra+"&legal="+legal+"&random="+Math.random());
		
	}
	function resGuardarPropuesta2(res){
		//ubi.celdatexto.innerHTML = res;
		if(res.indexOf("guardo")>-1){
			info("La propuesta de convenio ha sido guardada con estado "+res.split(",")[1], "¡Atención!");
			ubi.estadopropuesta.innerHTML = res.split(",")[1];
			ubi.celdaacciones.innerHTML = "";
		}else{
			alerta3("Error al guardar",res);
		}
	}
	
	function desahabilitarNormal(tipo){
		switch(tipo){
			case "1":
				ubi.radiogrupo[0].checked = false;
				ubi.div_preciokg.innerHTML = conveniopesokg; 
				ubi.tienedatoskg.value=0
				bloquearObjeto(ubi.boton_agregarcajapaquete,true); 
				ubi.tienedatoskg.value = 0;  
				ubi.tienedatoskg_excedio.value = 0; 
				ubi.tienedatosprecio.value = 0; 
				ubi.tienedatosprecio_excedio.value = 0; 
				ubi.boton_agregarpreciokg.style.visibility = "hidden";
			break;
			
			case "2":
				ubi.radiogrupo[1].checked = false;
				ubi.div_descripcion.innerHTML = conveniodescripcion; 
				ubi.tienedatosprecio.value=0;
				ubi.descuentoflete.style.backgroundColor = ''; 
				ubi.descuentoflete.readOnly=false;
				ubi.boton_agregarcajapaquete.style.visibility = "hidden";
				bloquearObjeto(ubi.boton_agregarpreciokg,true);
			break;
						
			case "3":
				ubi.radiogrupo[2].checked = false;
				ubi.descuentoflete.style.backgroundColor = '#FFFF99';
				ubi.descuentoflete.value = "";
			break;	
			
			case "4":				
				ubi.radiobutton[0].checked = false;
				ubi.agregarpreciokg.style.visibility = "hidden";
				desactivarConsignacion(2);
			break;
			
			case "5":
				ubi.radiobutton[1].checked = false;
				ubi.agregardescripcion.style.visibility = "hidden";
				desactivarConsignacion(3);
			break;
			
			case "6":
				ubi.radiobutton[2].checked = false;
				desactivarConsignacion(4);
			break;
		}		
	}
	
	function tiposMoneda(evnt,valor){
		caja = valor;
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
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

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
	<div class="canvas">     
     <div class="content">
        <div class="det-guia" style="height:230px">
          <div class="dvTable clearfix" style="width:250px; color:#FFFFFF;">
            <div class="c1">Folio</div>
              <div class="c2"><input name="folio" type="text" class="text" id="folio" style="width:120px;" onkeypress="if(event.keyCode==13){solicitarPropuestaConvenio(this.value)};"/>
              <input type="button" id="search3" value=" " class="srch-btn" title="Buscar" style="float:right" onClick="abrirVentanaFija('../buscadores_generales/buscarPropuestaConvenioGen.php?pestado=TODOS&funcion=solicitarPropuestaConvenio', 630, 450, 'ventana', 'Propuestas pendientes de Aceptacion');"/></div>
                <div class="c1">Fecha</div>
                <div class="c2" id="fecha"><?=date('d/m/Y') ?></div>
                <div class="c1">Estado</div>
                <div class="c2" id="estadopropuesta"></div>
                <div class="c1">Vigencia</div>
                <div class="c2" id="vigencia"></div>
                <div class="c1">Sucursal</div>
                <div class="c2">
                  <select name="nombresucursal" id="nombresucursal" style="width:145px">
                    <option value="0">.::Seleccione::.</option>
				<?
                    $s = "select cs.descripcion as sdesc, cs.id as idsuc
                from catalogosucursal as cs where id <> 1 order by descripcion asc";
                $r = mysql_query($s,$l) or die($s);
                while($f = mysql_fetch_object($r)){
                
                ?>
                <option value="<?=$f->idsuc?>"><?=cambio_texto($f->sdesc)?></option>
                <?
                    }
                ?>
                  </select>
                </div>
                <div class="c1">Vendedor</div>
            <div class="c2"><input name="vendedor" type="text" class="text" id="vendedor" style="width:120px;" onkeypress="if(event.keyCode==13){pedirEmpleado(this.value)}else{return solonumeros(event)}"/>
              <input name="buscarvendedor" type="button" class="srch-btn" id="search3" style="float:right" title="Buscar" value=" " onClick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=pedirEmpleado&empleadodefault=1&tipo=29&sucursal='+ubi.nombresucursal.value, 625, 550, 'ventana', 'Busqueda')"/>
                </div>
                <div class="c2" style="width:200px; padding-left:50px; font-size:12px" id="vendedorb"></div>
		  </div>

       </div>
        <div class="datos-cliente" style="font-size:11px; height:230px;">
          <div class="dvTable clearfix" style="width:540px; margin-left:5px;">
		  	<table width="526" height="182"  border="0" cellpadding="0" cellspacing="0" style="margin:0px 5px;">
				<tbody>
					<tr>
					  <th colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="25%"><div class="c1" style="font-weight:bold; font-size:12px; width:120px; margin-bottom:5px">Datos Prospecto</div></td>
                          <td width="34%">
                            <input name="clienterdo" type="radio" checked onClick="limpiarCliente()" />
Prospecto
<input name="clienterdo" type="radio" onClick="limpiarCliente()" />
Cliente</span></td>
                          <td width="41%"><input name="personamoral" type="radio" value="SI" checked onClick="document.all.personamoral_valor.value = 'SI'; limpiarCliente()" />
Persona Moral
  <input name="personamoral" type="radio" value="NO" onClick="document.all.personamoral_valor.value = 'NO'; limpiarCliente()" /> <input type="hidden" name="personamoral_valor" value="SI">
Persona F&iacute;sica</td>
                        </tr>
                      </table></th>
				  </tr>
					<tr>
					  <th width="99">No. Cliente</th>
					  <th width="166"><input name="prospecto" type="text" class="text" id="prospecto" style="width:120px;" onKeyPress="if(event.keyCode==13){if(ubi.clienterdo[0].checked){pedirProspecto(this.value)}else{pedirCliente(this.value)}}else{return solonumeros(event)}"/><input name="buscarprospecto" type="button" class="srch-btn" id="search" title="Buscar" value=" " onClick="if(ubi.clienterdo[0].checked){abrirVentanaFija('../buscadores_generales/buscarProspectoGen.php?funcion=pedirProspecto&amp;personamoral='+document.all.personamoral_valor.value, 625, 418, 'ventana', 'Buscar Prospecto')}else{abrirVentanaFija('../buscadores_generales/buscarClienteGen2.php?funcion=pedirCliente&amp;tipo='+((document.all.personamoral_valor.value=='SI')?'moral':'fisica'), 625, 418, 'ventana', 'Buscar Cliente')}"/></th>
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
					  <th><input name="valordeclarado" id="valordeclarado" type="text" class="text" style="width:120px; height:9px; font-size:9px; margin:0px;" onKeyPress="if(event.keyCode==13){ this.value = convertirMoneda(this.value);document.all.limite.focus();}else{return tiposMoneda(event,this.value)}" /></th>
					  <th>L&iacute;mite</th>
					  <th><input name="limite" id="limite" class="text" style="width:120px; height:9px; font-size:9px; margin:0px;" onKeyPress="if(event.keyCode==13){document.all.porcada.focus();}else{return solonumeros2(event)}"/></th>
				  </tr>
					<tr>
					  <th>Por cada</th>
					  <th><input name="porcada" id="porcada" class="text" style="width:120px; height:9px; font-size:9px; margin:0px;" onKeyPress="if(event.keyCode==13){document.all.costoextra.focus();}else{return solonumeros2(event)}" /></th>
					  <th>Costo Extra</th>
					  <th><input name="costoextra" id="costoextra" type="text" class="text" style="width:120px; height:9px; font-size:9px; margin:0px;" onKeyPress="if(event.keyCode==13){this.value = convertirMoneda(this.value);document.all.radiogrupo[0].focus();}else{return tiposMoneda(event,this.value)}"/></th>
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
            <td width="160"><input type="radio" name="radiogrupo" value="checkbox" 
            onClick="ubi.div_descripcion.innerHTML = conveniodescripcion; ubi.descuentoflete.value=''; ubi.descuentoflete.readOnly=true; ubi.descuentoflete.style.backgroundColor = '#FFFF99'; document.all.boton_agregarpreciokg.style.visibility = 'hidden'; if(!this.checked){ubi.div_preciokg.innerHTML = conveniopesokg; ubi.tienedatoskg.value=0} bloquearObjeto(ubi.boton_agregarcajapaquete,true); ubi.tienedatoskg.value = 0;  ubi.tienedatoskg_excedio.value = 0; ubi.tienedatosprecio.value = 0; ubi.tienedatosprecio_excedio.value = 0; " onDblClick="desahabilitarNormal('1')" />
            Precio por KG</td>
            <td colspan="2"><input type="radio" name="radiogrupo" value="checkbox" 
             onclick=" ubi.div_preciokg.innerHTML = conveniopesokg; ubi.descuentoflete.value=''; document.all.boton_agregarcajapaquete.style.visibility = 'hidden'; if(!this.checked){ubi.div_descripcion.innerHTML = conveniodescripcion; ubi.tienedatosprecio.value=0}else{ubi.descuentoflete.style.backgroundColor = ''; ubi.descuentoflete.readOnly=false;}bloquearObjeto(ubi.boton_agregarpreciokg,true);" ondblclick="desahabilitarNormal('2')"/>
            Precio por Caja/Paquete </td>
            <td width="208"><input type="radio" name="radiogrupo" onclick="ubi.descuentoflete.readOnly=false; ubi.descuentoflete.style.backgroundColor = ''; ubi.div_preciokg.innerHTML = conveniopesokg; ubi.div_descripcion.innerHTML = conveniodescripcion; bloquearObjeto(ubi.boton_agregarpreciokg,true);	bloquearObjeto(ubi.boton_agregarcajapaquete,true);" ondblclick="desahabilitarNormal('3')" />
            <?
				$s = "SELECT desmaximopermitido FROM configuradorgeneral";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
            Descuento Sobre Flete
            <input type="hidden" name="descmaxpermitido" value="<?=$f->desmaximopermitido?>" /></td>
            <td width="121"><input name="descuentoflete" type="text" class="text" id="descuentoflete" onkeypress="return solonumeros(event)" style="width:100px;font-size:11px; margin:0px;" readonly="readonly" onblur="if(!document.all[radiogrupo][0].checked){if(this.value = '<?=$f->desmaximopermitido?>'){alerta3('El descuento maximo permitido es de <?=$f->desmaximopermitido?>','&iexcl;Atenci&oacute;n!');} }" /></td>
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
					</div></td>
				</tr>
				 <tr>
					  		<td>
	  <input name="boton_agregarpreciokg" id="boton_agregarpreciokg" style="visibility:hidden" type="button" class="button" onclick="abrirVentanaFija('conveniopreciokg.php?tienedatoskg='+document.all.tienedatoskg.value, 620, 418, 'ventana', 'Precio por KG')" value="Agregar" />
        <input type="hidden" name="tienedatoskg" value="0">
        <input type="hidden" name="tienedatoskg_excedio" value="0"></td>
					  </tr>			
			</table>			</td>
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
            </div></td>
				</tr>
			</table>			</td>
      </tr>
		  <tr>
		  		<td colspan="5">
		<input name="boton_agregarcajapaquete" id="boton_agregarcajapaquete" style="visibility:hidden" type="button" class="button" onclick="abrirVentanaFija('conveniopreciocaja.php?tienedatosprecio='+document.all.tienedatosprecio.value, 620, 418, 'ventana', 'Precio por Caja/Paquete')" value="Agregar" />
		
		<input type="hidden" name="tienedatosprecio" value="0">
        <input type="hidden" name="tienedatosprecio_excedio" value="0"></td>
		  </tr>
		 </table>
			  </div>
			
			
		<div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; width:800px; height:320px;visibility:hidden" class="content-table" id="div1">
			<table width="100%" height="300" style="margin-top:20px; margin-left:10px;">
		<tr>
		    <td width="534">
			<div style="background:#282828; width:720px; height:95px">
			<table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios">
   			</table>
			</div>
			</td>
        </tr>
		<tr>
		    <td>
			<input name="botonagregarservicio" id="botonagregarservicio" type="button" class="button" onclick="abrirVentanaFija('servicios.php?tipo=CONVENIO&fagregar=agregarServicio&fborrar=borrarServicios&fmodificar=modificarServicios&tienedatosprecio='+document.all.tienedatoskg.value+'&limpiar='+ubi.servicios1.value, 400, 350, 'ventana', 'Servicios')" value="Agregar" />
          <input type="hidden" name="servicios1" value="1"></td>
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
            <td colspan="11" style="text-align:center; color:#FFFFFF"><input type="checkbox" name="chk_servrest1" onClick="todos(ubi.serviciosr1, ubi.serviciosr1_sel, this.checked, 'SRCONVENIO')">Todos
              <select name="serviciosr1" style="width:200px" class="Tablas" onChange="insertarServicio(this, this.value, document.all.serviciosr1_sel, 'El Servicio', 'SRCONVENIO')">
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
                <select name="serviciosr1_sel" size="7" style="width:265px" onDblClick="borrarServicio(this, 'SRCONVENIO')">
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
                  <input type="checkbox" name="chk_sucead1" onClick="todos(ubi.sucursalesead1, ubi.sucursalesead1_sel, this.checked, 'SUCONVENIO')">Todos
            	 <select name="sucursalesead1" style="width:200px" class="Tablas" onChange="insertarServicio(this, this.value, document.all.sucursalesead1_sel, 'La Sucursal', 'SUCONVENIO')")>
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
                 <select name="sucursalesead1_sel" size="7" style="width:265px" onDblClick="borrarServicio(this, 'SUCONVENIO')">
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
                <input name="checkprepagadas" type="checkbox" value="0" onclick="desactivarPrepagadas(!this.checked)" />
              </label></td>
              <td width="110">Pre-Pagadas</td>
              <td width="184"><div class="etiqueta" style="width:50px;">Limite KG:</div>
                <input name="limitekg" type="text" class="text" style="width:100px; text-align:right;font-size:11px; margin:0px;" onkeypress="if(event.keyCode==13){document.all.costoguia.focus();}else{return tiposMoneda(event,this.value)}" value="<?=$limitekg ?>" readonly="readonly">
              </td>
              <td width="48">Costo:</td>
              <td width="121"><input name="costoguia" type="text" class="text" style="width:100px; text-align:right;font-size:11px; margin:0px;" onkeypress="if(event.keyCode==13){this.value = convertirMoneda(this.value); document.all.preciokgexcedente.focus();}else{return tiposMoneda(event,this.value)}" readonly="readonly" value="<?=$Costo ?>"/>
              </td>
              <td width="146" align="right">Precio KG Excedente:</td>
              <td width="172" style="text-align:left"><input name="preciokgexcedente" type="text" class="text" style="width:70px; text-align:right;font-size:11px; margin:0px;" onkeypress="if(event.keyCode==13){this.value = convertirMoneda(this.value);}else{return tiposMoneda(event,this.value)}" readonly="readonly" value="<?=$Costo ?>"/></td>
            </tr>
          </table></td>
	  </tr>
		<tr>
		  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="30"><input name="radiobutton" type="radio" value="1" onclick="desactivarConsignacion(2)" ondblclick="desahabilitarNormal('4')"/></td>
              <td width="146">Consignaci&oacute;n KG</td>
              <td width="211"><input name="radiobutton" type="radio" value="2" onclick="desactivarConsignacion(3)" ondblclick="desahabilitarNormal('5')" />
              Consignaci&oacute;n Paquete </td>
              <td width="180"><input name="radiobutton" type="radio" value="3" onclick="desactivarConsignacion(4)" ondblclick="desahabilitarNormal('6')" />
              Consignaci&oacute;n Descuento</td>
              <td width="181"><span class="Tablas">
                <?
				$s = "SELECT desmaximopermitido FROM configuradorgeneral";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
                <input name="consignaciondes" type="text" class="text" id="consignaciondes" style="width:100px;font-size:11px; margin:0px;" onkeypress="return solonumeros(event)" readonly="readonly" value="<?=$consignaciondes ?>" onblur="if(this.value=<?=$f->desmaximopermitido?>){alerta('El descuento maximo permitido es de <?=$f->desmaximopermitido?>','&iexcl;Atenci&oacute;n!', 'descuentoflete');}" />
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
            </div></td>
          </tr>
        </table></td>
	  </tr>
		<tr>
		  <td>
	  <input name="agregarpreciokg" id="agregarpreciokg" style="visibility:hidden" type="button" class="button" onclick="abrirVentanaFija('consignacionpreciokg.php?tienedatoskgc='+document.all.tienedatoskgc.value, 620, 418, 'ventana', 'Precio por KG')" value="Agregar" />
	  
      <input type="hidden" name="tienedatoskgc" value="0">
      <input type="hidden" name="tienedatoskgc_excedio" value="0"></td>
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
            </div></td>
          </tr>
        </table></td>
	  </tr>
		<tr>
		  <td>
		  <input name="agregardescripcion" id="agregardescripcion" style="visibility:hidden" type="button" class="button" onclick="abrirVentanaFija('consignacionpaquete.php?tienedatosprecioc='+document.all.tienedatosprecioc.value, 620, 418, 'ventana', 'Precio por Caja Paquete')" value="Agregar" />
        <input type="hidden" name="tienedatosprecioc" value="0">
        <input type="hidden" name="tienedatosprecioc_excedio" value="0"></td>
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
			</div>
			</td>
        </tr>
		<tr>
		    <td>
			<input name="botonagregarservicio2" id="botonagregarservicio2" type="button" class="button" style="visibility:hidden" onclick="abrirVentanaFija('servicios.php?tipo=CONSIGNACION&fagregar=agregarServicio2&fborrar=borrarServicios2&fmodificar=modificarServicios2&limpiar='+ubi.servicios2.value, 400, 350, 'ventana', 'Servicios')" value="Agregar" />
          <input type="hidden" name="servicios2" value="1"></td>
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
            <td colspan="11" style="text-align:center; color:#FFFFFF"><input type="checkbox" name="chk_servrest2" onClick="todos(ubi.serviciosr2, ubi.serviciosr2_sel, this.checked, 'SRCONSIGNACION')">Todos
                <select name="serviciosr2" style="width:200px" class="Tablas" onChange="insertarServicio(this, this.value, document.all.serviciosr2_sel, 'El Servicio', 'SRCONSIGNACION')">
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
                <select name="serviciosr2_sel" size="7" style="width:265px" onDblClick="borrarServicio(this, 'SRCONSIGNACION')">
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
                  <input type="checkbox" name="chk_sucead2" onClick="todos(ubi.sucursalesead3, ubi.sucursalesead3_sel, this.checked, 'SUCONSIGNACION2');">Todos
                  <select name="sucursalesead3" style="width:200px" class="Tablas" onChange="insertarServicio(this, this.value, document.all.sucursalesead3_sel, 'La Sucursal', 'SUCONSIGNACION2')")>
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
                <select name="sucursalesead3_sel" size="7" style="width:265px" onDblClick="borrarServicio(this, 'SUCONSIGNACION2')">
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
			</table></td>				
				</tr>				
			</table>
		</div>
    </div>
</form>
</body>
</html>
