<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabsDivs.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery-1.4.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script>
	var tabs = new ClaseTabs();
	var tabla1 = new ClaseTabla();
	var tabla2 = new ClaseTabla();
	var tabla3 = new ClaseTabla();
	var tabla4 = new ClaseTabla();
	var u = document.all;
	var nav4   = window.Event ? true : false;
	var Input = '<input  class="Tablas" name="colonia" type="text" id="colonia" size="32" readonly="" value="<?= $colonia; ?>" style="font:tahoma;font-size:9px; background:#FFFF99; text-transform:uppercase" onDblClick="javascript:popUp(\'buscarcolonia2.php\')" />';
	var combo1 = "<select class='Tablas' name='colonia' id='colonia'  style='width:185px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";
	var div = "<div id='btnAutorizacion' class='ebtn_autorizarcredito' onClick='enviarAutorizacion();'></div>";
	var divNo = "<div id='btnNoAutorizacion' class='ebtn_noautorizarcredito' onClick='enviarNoAutorizacion();'></div>";
	var divAc = "<div id='btnActivar' class='ebtn_activarcredito' onClick='activarCredito();'></div>";		
	var divEnviar = '<div id="btn_Enviarp" class="ebtn_Enviarp"  onClick="validar();"></div>';
	
	tabla1.setAttributes({
		nombre:"tablaBanco", 
		campos:[
			{nombre:"BANCO", medida:126, alineacion:"left", datos:"banco"},
			{nombre:"SUCURSAL", medida:126, alineacion:"left", datos:"sucursal"},
			{nombre:"CUENTA", medida:126, alineacion:"left", datos:"cuenta"},
			{nombre:"TELEFONO", medida:126, alineacion:"left", datos:"telefono"},
			{nombre:"FECHA", medida:4, tipo:"oculto", alineacion:"left", datos:"fecha"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"modificarBanco()",
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"tablaComer",
		campos:[
			{nombre:"EMPRESA", medida:168, alineacion:"left", datos:"empresa"},
			{nombre:"CONTACTO", medida:168, alineacion:"left", datos:"contacto"},
			{nombre:"TELEFONO", medida:168, alineacion:"left", datos:"telefono"},
			{nombre:"FECHA", medida:4, tipo:"oculto", alineacion:"left", datos:"fecha"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:true,
		eventoDblClickFila:"modificarComercial()",
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({
		nombre:"tablaPersonas",
		campos:[
			{nombre:"PERSONA", medida:310, alineacion:"left", datos:"persona"},
			{nombre:"FECHA", medida:40, tipo:"oculto", alineacion:"left", datos:"fecha"}			
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:true,	
		eventoDblClickFila:"modificarPersona()",
		nombrevar:"tabla3"
	});	
	
	tabla4.setAttributes({
		nombre:"detalleSucursal",
		campos:[
			{nombre:"SUCURSAL", medida:170, alineacion:"left", datos:"sucursal"},
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto", alineacion:"left", datos:"idsucursal"},
			{nombre:"FECHA", medida:4, tipo:"oculto", alineacion:"left", datos:"fecha"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:true,	
		eventoDblClickFila:"borrarSucursal()",
		nombrevar:"tabla4"
	});	
	
	window.onload = function(){
		tabla1.create();
		tabla2.create();
		tabla3.create();
		tabla4.create();
		tabs.iniciar({
			nombre:"tab", largo:605, alto:130, ajustex:11,
			ajustey:12, imagenes:"../img", titulo:"Documentacion Requerida"
		});
		tabs.agregarTabs('Revisión y Pago, Suc. Aplica Credito',1,null);		
		tabs.agregarTabs('Dias Revisión y Pago',2,null);
		tabs.agregarTabs('Referencias Bancarias',3,null);
		tabs.agregarTabs('Referencias Comerciales',4,null);
		tabs.seleccionar(0);
		u.estado.innerHTML = "SOLICITUD";
		obtenerGeneral();
		<?
			$_GET[funcion2] = str_replace("\'","'",$_GET[funcion2]);
			if($_GET[funcion2]!=""){
				echo 'setTimeout("'.$_GET[funcion2].'",1500);';
			}
		?>
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","solicitudContratoAperturaCredito_con.php?accion=4");
	}
	
	function mostrarGeneral(datos){
		var obj = eval(datos);
		u.folio.value = obj.folio;
		u.fecha.value = obj.fecha;
	}
	
	function validar(){
		if(u.estado.innerHTML=="SOLICITUD"){
			if(u.notieneconvenio.value == ""){
				if(u.folioconvenio.value!=""){
					if(u.folioconvenio.value!=" "){
						if(u.cliente.value != u.clienteconvenio.value){
							alerta("El folio de convenio no coincide con el cliente capturado","¡Atención!","folioconvenio");
							return false;
						}
					}
				}
			}
			
			if(u.cliente.value == ""){			
				alerta('Debe capturar Cliente','¡Atención!','cliente');
				return false;
			}
			if(u.rdmoral[1].checked == true){
				if(u.actaconstitutiva.checked == true){
					if(u.nacta.value == ""){			
					alerta('Debe capturar No. Acta','¡Atención!','nacta');
					return false;
					}else if(u.fechaescritura.value == ""){			
					alerta('Debe capturar Fecha Escritura','¡Atención!','fechaescritura');
					return false;
					}else if(u.fechainscripcion.value == ""){				
					alerta('Debe capturar Fecha de Inscripción','¡Atención!','fechainscripcion');
					return false;
					}
				}
			}
			if(u.identificacion.checked == true){
				if(u.nidentificacion.value == ""){			
			  alerta('Debe capturar No. Identificación','¡Atención!','nidentificacion');
				  return false;
				}
			}
			if(u.hacienda.checked == true){
				if(u.fechainiciooperaciones.value == ""){			
			  alerta('Debe capturar Fecha Inicio Operaciones','¡Atención!','fechainiciooperaciones');
				return false;
				}else if(!ValidaRfc(u.rfc2.value)){			
				alerta('Debe capturar un R.F.C valido','¡Atención!','rfc2');
				return false;
				}
			}
			if(u.comprobante.checked == true){
				if(u.comprobanteluz[0].checked == false && u.comprobanteluz[1].checked == false){			
					alerta('Debe capturar un Comprobante ','¡Atención!','comprobanteluz[0]');
					return false;
				}
			}
			if(u.estadocuenta.checked == true){
				if(u.banco.value == ""){				
					alerta('Debe capturar Banco','¡Atención!','banco');
					return false;
				}else if(u.cuenta.value == ""){				
					alerta('Debe capturar Cuenta','¡Atención!','cuenta');
					return false;
				}
			}
			
			if(u.rdmoral[0].checked == true){
				/*if(u.actaconstitutiva.checked == false || u.identificacion.checked == false
				   || u.hacienda.checked == false || u.comprobante.checked == false
				   || u.estadocuenta.checked == false || u.solicitud.checked == false){
					alerta3('Debe capturar toda la Documentación Requerida para poder enviar la solicitud','¡Atención!');
					return false;
				}*/
			}else{
				/*if(u.identificacion.checked == false || u.hacienda.checked == false 
					|| u.comprobante.checked == false || u.estadocuenta.checked == false 
					|| u.solicitud.checked == false){
					alerta3('Debe capturar toda la Documentación Requerida para poder enviar la solicitud','¡Atención!');
					return false;
				}*/
			}
			if(tabla4.getRecordCount() == 0){
				 alerta("Debe capturar Sucursal donde aplicara el Crédito","¡Atención!","sucursalesead1");
				 return false;
			}
			if(u.semanapago.checked == false){
				if(u.lunespago.checked == false && u.martespago.checked == false
				   && u.miercolespago.checked == false && u.juevespago.checked == false
				   && u.viernespago.checked == false && u.sabadopago.checked == false){
					alerta3('Debe capturar día de pago','¡Atención!');
					return false;
				}
			}
			
			if(u.semanarevision.checked == false){
				if(u.lunesrevision.checked == false && u.martesrevision.checked == false
				   && u.miercolesrevision.checked == false && u.juevesrevision.checked == false
				   && u.viernesrevision.checked == false && u.sabadorevision.checked == false){
					alerta3('Debe capturar día de revisión','¡Atención!');
					return false;
				}
			}
			
			if(u.msolicitado.value.replace("$ ","").replace(/,/g,"")==""){
				alerta("Debe capturar Monto solicitado","¡Atención!","msolicitado");
				return false;
			}
			
			if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
				u.sucursalorigen.value = '<?=$_SESSION[IDSUCURSAL]?>';
			}
			
			if(u.accion.value == ""){
				u.btn_Enviarp.style.visibility = "hidden";
				u.horariopago.value  = u.shpago.value +":"+ u.smpago.value;
				u.apago.value  = u.ahpago.value +":"+ u.ampago.value;
				u.horariorevision.value  = u.shrevision.value +":"+ u.smrevision.value;
				u.arevision.value  = u.ahrevision.value +":"+ u.amrevision.value;
				u.accion.value = "grabar";
				consultaTexto("registro","solicitudContratoAperturaCredito_con.php?accion=3&grabar=grabar&folioconvenio="+((u.folioconvenio.value=="")?0:u.folioconvenio.value)
				+"&fechaautorizacion="+((u.fechaautorizacion.value=="")?"00/00/0000":u.fechaautorizacion.value)
				+"&rdmoral="+((u.rdmoral[0].checked==true)?1:0)
				+"&cliente="+u.cliente.value+"&nick="+u.nick.value
				+"&rfc="+u.rfc.value+"&nombre="+u.nombre.value+"&paterno="+u.paterno.value
				+"&materno="+u.materno.value+"&calle="+u.calle.value+"&numero="+u.numero.value
				+"&cp="+u.cp.value+"&colonia="+u.colonia.value+"&poblacion="+u.poblacion.value
				+"&municipio="+u.municipio.value+"&pais="+u.pais.value+"&celular="+u.celular.value
				+"&telefono="+u.telefono.value+"&email="+u.email.value+"&estado2="+u.estado2.value
				+"&giro="+u.giro.value+"&antiguedad="+u.antiguedad.value+"&representantelegal="+u.representantelegal.value
				+"&actaconstitutiva="+((u.actaconstitutiva.checked==true)?1:0)+"&nacta="+u.nacta.value
				+"&fechaescritura="+u.fechaescritura.value+"&fechainscripcion="+u.fechainscripcion.value
				+"&identificacion="+((u.identificacion.checked==true)?1:0)+"&nidentificacion="+u.nidentificacion.value
				+"&hacienda="+((u.hacienda.checked==true)?1:0)+"&fechainiciooperaciones="+u.fechainiciooperaciones.value
				+"&rfc2="+u.rfc2.value+"&comprobante="+((u.comprobante.checked==true)?1:0)
				+"&comprobanteluz="+((u.comprobanteluz[0].checked==true)?1:0)
				+"&estadocuenta="+((u.estadocuenta.checked==true)?1:0)+"&banco="+u.banco.value
				+"&cuenta="+u.cuenta.value+"&solicitud="+((u.solicitud.checked==true)?1:0)
				+"&semanapago="+((u.semanapago.checked==true)?1:0)
				+"&lunespago="+((u.lunespago.checked==true)?1:0)
				+"&martespago="+((u.martespago.checked==true)?1:0)
				+"&miercolespago="+((u.miercolespago.checked==true)?1:0)
				+"&juevespago="+((u.juevespago.checked==true)?1:0)
				+"&viernespago="+((u.viernespago.checked==true)?1:0)
				+"&sabadopago="+((u.sabadopago.checked==true)?1:0)
				+"&horariopago="+u.horariopago.value+"&apago="+u.apago.value
				+"&responsablepago="+u.responsablepago.value
				+"&celularpago="+u.celularpago.value+"&telefonopago="+u.telefonopago.value
				+"&faxpago="+u.faxpago.value
				+"&semanarevision="+((u.semanarevision.checked==true)?1:0)
				+"&lunesrevision="+((u.lunesrevision.checked==true)?1:0)
				+"&martesrevision="+((u.martesrevision.checked==true)?1:0)
				+"&miercolesrevision="+((u.miercolesrevision.checked==true)?1:0)
				+"&juevesrevision="+((u.juevesrevision.checked==true)?1:0)
				+"&viernesrevision="+((u.viernesrevision.checked==true)?1:0)
				+"&sabadorevision="+((u.sabadorevision.checked==true)?1:0)
				+"&horariorevision="+u.horariorevision.value
				+"&arevision="+u.arevision.value
				+"&msolicitado="+u.msolicitado.value.replace("$ ","").replace(/,/g,"")
				+"&mautorizado="+u.mautorizado.value.replace("$ ","").replace(/,/g,"")
				+"&diacredito="+u.diacredito.value
				+"&observaciones="+u.observaciones.value);
			}else if(u.accion.value == "modificar"){
				u.btn_Enviarp.style.visibility = "hidden";
				u.horariopago.value  = u.shpago.value +":"+ u.smpago.value;
				u.apago.value  = u.ahpago.value +":"+ u.ampago.value;
				u.horariorevision.value  = u.shrevision.value +":"+ u.smrevision.value;
				u.arevision.value  = u.ahrevision.value +":"+ u.amrevision.value;				
				u.accion.value = "modificar";
				consultaTexto("registro","solicitudContratoAperturaCredito_con.php?accion=3&grabar=modificar&folioconvenio="+((u.folioconvenio.value=="")?0:u.folioconvenio.value)
				+"&fechaautorizacion="+((u.fechaautorizacion.value=="")?"00/00/0000":u.fechaautorizacion.value)
				+"&rdmoral="+((u.rdmoral[0].checked==true)?1:0)
				+"&cliente="+u.cliente.value+"&nick="+u.nick.value
				+"&rfc="+u.rfc.value+"&nombre="+u.nombre.value+"&paterno="+u.paterno.value
				+"&materno="+u.materno.value+"&calle="+u.calle.value+"&numero="+u.numero.value
				+"&cp="+u.cp.value+"&colonia="+u.colonia.value+"&poblacion="+u.poblacion.value
				+"&municipio="+u.municipio.value+"&pais="+u.pais.value+"&celular="+u.celular.value
				+"&telefono="+u.telefono.value+"&email="+u.email.value+"&estado2="+u.estado2.value
				+"&giro="+u.giro.value+"&antiguedad="+u.antiguedad.value+"&representantelegal="+u.representantelegal.value
				+"&actaconstitutiva="+((u.actaconstitutiva.checked==true)?1:0)+"&nacta="+u.nacta.value
				+"&fechaescritura="+u.fechaescritura.value+"&fechainscripcion="+u.fechainscripcion.value
				+"&identificacion="+((u.identificacion.checked==true)?1:0)+"&nidentificacion="+u.nidentificacion.value
				+"&hacienda="+((u.hacienda.checked==true)?1:0)+"&fechainiciooperaciones="+u.fechainiciooperaciones.value
				+"&rfc2="+u.rfc2.value+"&comprobante="+((u.comprobante.checked==true)?1:0)
				+"&comprobanteluz="+((u.comprobanteluz[0].checked==true)?1:0)
				+"&estadocuenta="+((u.estadocuenta.checked==true)?1:0)+"&banco="+u.banco.value
				+"&cuenta="+u.cuenta.value+"&solicitud="+((u.solicitud.checked==true)?1:0)
				+"&semanapago="+((u.semanapago.checked==true)?1:0)
				+"&lunespago="+((u.lunespago.checked==true)?1:0)
				+"&martespago="+((u.martespago.checked==true)?1:0)
				+"&miercolespago="+((u.miercolespago.checked==true)?1:0)
				+"&juevespago="+((u.juevespago.checked==true)?1:0)
				+"&viernespago="+((u.viernespago.checked==true)?1:0)
				+"&sabadopago="+((u.sabadopago.checked==true)?1:0)
				+"&horariopago="+u.horariopago.value+"&apago="+u.apago.value
				+"&responsablepago="+u.responsablepago.value
				+"&celularpago="+u.celularpago.value+"&telefonopago="+u.telefonopago.value
				+"&faxpago="+u.faxpago.value
				+"&semanarevision="+((u.semanarevision.checked==true)?1:0)
				+"&lunesrevision="+((u.lunesrevision.checked==true)?1:0)
				+"&martesrevision="+((u.martesrevision.checked==true)?1:0)
				+"&miercolesrevision="+((u.miercolesrevision.checked==true)?1:0)
				+"&juevesrevision="+((u.juevesrevision.checked==true)?1:0)
				+"&viernesrevision="+((u.viernesrevision.checked==true)?1:0)
				+"&sabadorevision="+((u.sabadorevision.checked==true)?1:0)
				+"&horariorevision="+u.horariorevision.value
				+"&arevision="+u.arevision.value
				+"&msolicitado="+u.msolicitado.value.replace("$ ","").replace(/,/g,"")
				+"&mautorizado="+u.mautorizado.value.replace("$ ","").replace(/,/g,"")
				+"&diacredito="+u.diacredito.value
				+"&observaciones="+u.observaciones.value
				+"folio="+u.folio.value);
			}
		}else if(u.estado.innerHTML=="EN AUTORIZACION"){
				alerta3('La Solicitud de credito ya fue enviada','¡Atención!');	
		}else if(u.estado.innerHTML=="AUTORIZADO"){
				alerta3('La Solicitud de credito ya fue Autorizada','¡Atención!');	
		}else if(u.estado.innerHTML=="ACTIVADO"){
				alerta3('La Solicitud de credito ya fue Activado','¡Atención!');	
		}else if(u.estado.innerHTML=="NO AUTORIZADO"){
				alerta3('La Solicitud de credito No fue Autorizado','¡Atención!');	
		}
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			info("","Los datos han sido guardados correctamente");
			u.btn_Enviarp.style.visibility = "visible";
			if(row[1]=="grabar"){
				u.folio.value = row[2];
			}			
		}else{
			alerta3("Hubo un error al autorizar "+datos,"¡Atención!");
			u.btn_Enviarp.style.visibility = "visible";
		}
	}
	
	function obtenerClienteBusqueda(id){
		u.cliente.value = id;
		consulta("mostrarCliente","consultasCredito.php?accion=2&cliente="+id);
	}
	function obtenerCliente(e,id){
		var persona;
		tecla = (u) ? e.keyCode : e.which;
		((u.rdmoral[0].checked == true) ? persona="SI" : persona="NO");
		if(tecla == 13 && id!=""){
			u.cliente.value = id;
			consulta("mostrarCliente","consultasCredito.php?accion=2&cliente="+id
			+"&persona="+persona+"&val="+Math.random());
		}
	}
	function mostrarCliente(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		limpiarCliente();
		if(con>0){
		if(u.folioconvenio.value!=""){
			if(u.folioconvenio.value!=" "){
				if(u.cliente.value != u.clienteconvenio.value){
					u.folioconvenio.value = "";
					u.clienteconvenio.value = "";
					alerta("El folio de convenio no coincide con el cliente capturado","¡Atención!","folioconvenio");
					return false;
				}
			}
		}			
			if(datos.getElementsByTagName('estadocredito').item(0).firstChild.data.replace(" ","")!=""){
				alerta3("El Cliente "+datos.getElementsByTagName('nombre').item(0).firstChild.data
				+" ya se le realizo una solicitud de crédito, la cual se encuentra en estado "+datos.getElementsByTagName('estadocredito').item(0).firstChild.data+".","¡Atención!");
				return false;
			}
			if(datos.getElementsByTagName('foliocredito').item(0).firstChild.data!="0"){
			alerta3("El Cliente "+datos.getElementsByTagName('nombre').item(0).firstChild.data
			+" ya cuenta con crédito.","¡Atención!");
				return false;
			}
		u.nick.value 		= datos.getElementsByTagName('nick').item(0).firstChild.data;
		u.rfc.value 		= datos.getElementsByTagName('rfc').item(0).firstChild.data;
		u.rfc2.value 		= datos.getElementsByTagName('rfc').item(0).firstChild.data;
		u.nombre.value		= datos.getElementsByTagName('nombre').item(0).firstChild.data;
		u.paterno.value 	= datos.getElementsByTagName('paterno').item(0).firstChild.data;	
		u.materno.value 	= datos.getElementsByTagName('materno').item(0).firstChild.data;
		u.celular.value 	= datos.getElementsByTagName('celular').item(0).firstChild.data;
		u.email.value 		= datos.getElementsByTagName('email').item(0).firstChild.data;
		u.folioconvenio.value = datos.getElementsByTagName('convenio').item(0).firstChild.data;			
		
		if(datos.getElementsByTagName('idcliente').item(0).firstChild.data=="0"){
			u.notieneconvenio.value = "no tiene";
		}else{
			u.clienteconvenio.value = datos.getElementsByTagName('idcliente').item(0).firstChild.data;
		}
		
		
			var endir = datos.getElementsByTagName('dir').item(0).firstChild.data;
			if(endir==1){
				document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:180px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>';
				u.numero.value =datos.getElementsByTagName('numero').item(0).firstChild.data;
				u.cp.value =datos.getElementsByTagName('cp').item(0).firstChild.data;
				u.colonia.value =datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.poblacion.value =datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
				u.pais.value =datos.getElementsByTagName('pais').item(0).firstChild.data;
				u.telefono.value =datos.getElementsByTagName('telefono').item(0).firstChild.data;
				u.estado2.value =datos.getElementsByTagName('estado').item(0).firstChild.data;
				u.calle.value =datos.getElementsByTagName('calle').item(0).firstChild.data;
			}else if(endir>1){
			var comb = "<select name='calle' style='width:165px;font:tahoma; font-size:9px' onchange='"
			+"document.all.numero.value=this.options[this.selectedIndex].numero;"
			+"document.all.cp.value=this.options[this.selectedIndex].cp;"
			+"document.all.colonia.value=this.options[this.selectedIndex].colonia;"
			+"document.all.poblacion.value=this.options[this.selectedIndex].poblacion;"
			+"document.all.municipio.value=this.options[this.selectedIndex].municipio;"
			+"document.all.estado2.value=this.options[this.selectedIndex].estado2;"
			+"document.all.pais.value=this.options[this.selectedIndex].pais;"
			+"document.all.telefono.value=this.options[this.selectedIndex].telefono;"
			+"'>";
				
				for(var i=0; i<endir; i++){
					
					v_calle 		= datos.getElementsByTagName('calle').item(i).firstChild.data;
					v_numero		= datos.getElementsByTagName('numero').item(i).firstChild.data;
					v_cp 			= datos.getElementsByTagName('cp').item(i).firstChild.data;
					v_colonia		= datos.getElementsByTagName('colonia').item(i).firstChild.data;
					v_poblacion 	= datos.getElementsByTagName('poblacion').item(i).firstChild.data;
					v_municipio 	= datos.getElementsByTagName('municipio').item(i).firstChild.data;
					v_pais 			= datos.getElementsByTagName('pais').item(i).firstChild.data;
					v_telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;
					v_estado2 		= datos.getElementsByTagName('estado').item(i).firstChild.data;
					v_fact			= datos.getElementsByTagName('facturacion').item(i).firstChild.data;
		
					if(i==0){					
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;
						u.telefono.value 	= v_telefono;
						u.municipio.value 	= v_municipio;
						u.pais.value 		= v_pais;
						u.telefono.value 	= v_telefono;
						u.estado2.value 	= v_estado2;						
					}else if(v_fact=="SI"){
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;
						u.telefono.value 	= v_telefono;
						u.municipio.value 	= v_municipio;
						u.pais.value 		= v_pais;
						u.telefono.value 	= v_telefono;
						u.estado2.value 	= v_estado2;
					}
					
					comb += "<option "+ ((v_fact=="SI")? "selected " : "" ) +" value='"+v_calle+"' numero='"+v_numero+"'" 
					+"cp='"+v_cp+"' colonia='"+v_colonia+"'"
					+" poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'"
					+" municipio='"+v_municipio+"' pais='"+v_pais+"'"
					+" telefono='"+v_telefono+"' estado='"+v_estado2+"'>"
					+v_calle+"</option>";					
				}
				comb += "</select>";
				document.all.celda_des_calle.innerHTML = comb;
			}
			u.giro.focus();
		}else{
			if(u.rdmoral[0].checked == true){
			alerta('El numero del cliente no existe o no es una persona moral','¡Atención!','cliente');
			}else{
			alerta('El numero del cliente no existe o no es una persona fisica','¡Atención!','cliente');
			}
		}	
		
}
	function limpiarCliente(){
	 	u.nick.value = ""; 		u.rfc.value = ""; 
		u.nombre.value = ""; 	u.paterno.value = ""; 
		u.materno.value = ""; 	u.calle.value = ""; 
		u.numero.value = ""; 	u.cp.value = ""; 
		u.colonia.value = ""; 	u.poblacion.value=""; 
		u.municipio.value=""; 	u.estado2.value = ""; 
		u.pais.value = ""; 		u.celular.value = ""; 
		u.telefono.value=""; 	u.email.value = "";
		u.clienteconvenio.value = ""; u.notieneconvenio.value = "";		
	}
	
	jQuery(function($){
	   $('#fechaescritura').mask("99/99/9999");	   
	   $('#fechainscripcion').mask("99/99/9999");
	   $('#fechainiciooperaciones').mask("99/99/9999");
	});
	
	function validarDocumentacion(nombre){
		if(nombre == "actaconstitutiva" && u.actaconstitutiva.checked == false){
			u.nacta.value 			 = "";
			u.fechaescritura.value 	 = "";
			u.fechainscripcion.value = "";
			u.nacta.readOnly		 	 			= true;
			u.fechaescritura.disabled 	 			= true;
			u.fechainscripcion.disabled 			= true;
			u.nacta.style.backgroundColor			= "#FFFF99";
			u.fechaescritura.style.backgroundColor	= "#FFFF99";
			u.fechainscripcion.style.backgroundColor = "#FFFF99";	
			try{
				closeCalendar();				
			}catch(e){
					e = null;
			}
			
		}else if(nombre == "actaconstitutiva" && u.actaconstitutiva.checked == true){
			u.nacta.readOnly		 	 			= false;
			u.fechaescritura.disabled 	 			= false;
			u.fechainscripcion.disabled 			= false;
			u.nacta.style.backgroundColor			= "";
			u.fechaescritura.style.backgroundColor	= "";
			u.fechainscripcion.style.backgroundColor = "";			
			u.nacta.focus();
		}else if(nombre == "identificacion" && u.identificacion.checked == false){
			u.nidentificacion.value	 				= "";
			u.nidentificacion.readOnly  			= true;
			u.nidentificacion.style.backgroundColor	= "#FFFF99";
		}else if(nombre == "identificacion" && u.identificacion.checked == true){
			u.nidentificacion.value	 				= "";
			u.nidentificacion.readOnly  			= false;
			u.nidentificacion.style.backgroundColor	= "";
			u.nidentificacion.focus();
		}else if(nombre == "hacienda" && u.hacienda.checked == false){
			u.fechainiciooperaciones.value					= "";			
			u.fechainiciooperaciones.disabled  				= true;
			u.fechainiciooperaciones.style.backgroundColor	= "#FFFF99";
			u.rfc2.readOnly  								= true;
			u.rfc2.style.backgroundColor					= "#FFFF99";
			try{
				closeCalendar();				
			}catch(e){
					e = null;
			}
		}else if(nombre == "hacienda" && u.hacienda.checked == true){
			u.fechainiciooperaciones.value					= "";			
			u.fechainiciooperaciones.disabled  				= false;
			u.fechainiciooperaciones.style.backgroundColor	= "";
			u.rfc2.readOnly  								= false;
			u.rfc2.style.backgroundColor					= "";			
			u.fechainiciooperaciones.focus();
		}else if(nombre == "comprobante" && u.comprobante.checked == false){
			u.comprobanteluz[0].checked = false;
			u.comprobanteluz[1].checked = false;
			u.comprobanteluz[0].disabled = true;
			u.comprobanteluz[1].disabled = true;
		}else if(nombre == "comprobante" && u.comprobante.checked == true){
			u.comprobanteluz[0].disabled = false;
			u.comprobanteluz[1].disabled = false;
			u.comprobanteluz[0].focus();
		}else if(nombre == "estadocuenta" && u.estadocuenta.checked == false){
			u.banco.value			 = "";
			u.cuenta.value			 = "";
			u.banco.readOnly 		 = true;
			u.cuenta.readOnly 		 = true;
			u.cuenta.style.backgroundColor = "#FFFF99";
			u.banco.style.backgroundColor  = "#FFFF99";
		}else if(nombre == "estadocuenta" && u.estadocuenta.checked == true){
			u.banco.value			 = "";
			u.cuenta.value			 = "";
			u.cuenta.style.backgroundColor = "";
			u.banco.style.backgroundColor  = "";
			u.banco.readOnly 		 = false;
			u.cuenta.readOnly 		 = false;
			u.banco.focus();
		}
	}
	
	function agregarBanco(){
		if(u.rbanco.value!="" && u.rsucursal.value!="" && u.rcuenta.value!="" && u.rtelefono.value!=""){
			if(u.index.value!=""){
				var obj 		= new Object();
				obj.banco 		= u.rbanco.value;
				obj.sucursal 	= u.rsucursal.value;
				obj.cuenta 		= u.rcuenta.value;
				obj.telefono 	= u.rtelefono.value;
				obj.fecha	 	= u.rfecha.value;
				tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
				consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=8&banco="+u.rbanco.value
				+"&sucursal="+u.rsucursal.value+"&cuenta="+u.rcuenta.value+"&telefono="+u.rtelefono.value
				+"&fecha="+u.rfecha.value+"&tipo=modificar");
				u.rbanco.value		= "";
				u.rsucursal.value	= "";
				u.rcuenta.value		= "";
				u.rtelefono.value	= "";
				u.index.value		= "";
				u.rbanco.focus();
			}else{
				var obj 		= new Object();
				obj.banco 		= u.rbanco.value;
				obj.sucursal 	= u.rsucursal.value;
				obj.cuenta 		= u.rcuenta.value;
				obj.telefono 	= u.rtelefono.value;
				obj.fecha		= fechahora(u.rfecha.value);
				tabla1.add(obj);
				consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=8&banco="+u.rbanco.value
				+"&sucursal="+u.rsucursal.value+"&cuenta="+u.rcuenta.value+"&telefono="+u.rtelefono.value
				+"&fecha="+obj.fecha+"&tipo=alta");
				u.rbanco.value		= "";
				u.rsucursal.value	= "";
				u.rcuenta.value		= "";
				u.rtelefono.value	= "";
				u.rfecha.value		= "";
				u.rbanco.focus();
				
			}
		}else{
			if(u.rbanco.value == ""){
				alerta('Debe capturar Banco','¡Atención!','rbanco');
			}else if(u.rsucursal.value == ""){
				alerta('Debe capturar Sucursal','¡Atención!','rsucursal');				
			}else if(u.rcuenta.value == ""){
				alerta('Debe capturar Cuenta','¡Atención!','rcuenta');				
			}else if(u.rtelefono.value == ""){
				alerta('Debe capturar Teléfono','¡Atención!','rtelefono');				
			}
		}
	}
	
	function borrarBanco(){
		if(tabla1.getValSelFromField('banco','BANCO')!=""){
			confirmar('¿Esta seguro de Eliminar el Banco?','','eliminarBanco()','');	
		}
	}
	function eliminarBanco(){
		var obj = tabla1.getSelectedRow();
		consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=8&fecha="+obj.fecha+"&tipo=borrar");
		tabla1.deleteById(tabla1.getSelectedIdRow());
	}
	function modificarBanco(){
		if(tabla1.getValSelFromField('banco','BANCO')!=""){
			var obj 			= tabla1.getSelectedRow();
			u.rbanco.value 		= obj.banco;
			u.rsucursal.value 	= obj.sucursal;
			u.rcuenta.value 	= obj.cuenta;
			u.rtelefono.value 	= obj.telefono;
			u.rfecha.value 		= obj.fecha;
			u.index.value 		= tabla1.getSelectedIndex();
		}
	}
	
	function registroTablas(datos){
		if(datos.indexOf("ok")<0){
			var row = datos.split(",");
			if(row[0]=="alta"){
				alerta3("Hubo un error al agregar "+datos,"¡Atención!");
			}else if(row[0]=="modificar"){
				alerta3("Hubo un error al modificar "+datos,"¡Atención!");
			}else if(row[0]=="borrar"){
				alerta3("Hubo un error al eliminar "+datos,"¡Atención!");
			}
		}
	}
	
	function agregarComercial(){
		if(u.cempresa.value!="" && u.ccontacto.value!="" && u.ctelefono.value!=""){
			if(u.index.value!=""){
				var obj 		= new Object();
				obj.empresa 	= u.cempresa.value;
				obj.contacto	= u.ccontacto.value;
				obj.telefono  	= u.ctelefono.value;
				obj.fecha	  	= u.cfecha.value;
				tabla2.updateRowById(tabla2.getSelectedIdRow(), obj);
				consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=9&empresa="+u.cempresa.value
				+"&contacto="+u.ccontacto.value+"&telefono="+u.ctelefono.value+"&fecha="+u.cfecha.value+"&tipo=modificar");
				u.cempresa.value 	= "";
				u.ccontacto.value	= "";
				u.ctelefono.value	= "";
				u.cfecha.value		= "";
				u.index.value		= "";
				u.cempresa.focus();
			}else{
				var obj 		= new Object();
				obj.empresa 	= u.cempresa.value;
				obj.contacto	= u.ccontacto.value;
				obj.telefono  	= u.ctelefono.value;
				obj.fecha	  	= fechahora(u.cfecha.value);
				tabla2.add(obj);
				consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=9&empresa="+u.cempresa.value
				+"&contacto="+u.ccontacto.value+"&telefono="+u.ctelefono.value+"&fecha="+obj.fecha+"&tipo=alta");
				u.cempresa.value 	= "";
				u.ccontacto.value	= "";
				u.ctelefono.value	= "";
				u.cfecha.value		= "";
				u.cempresa.focus();
			}
		}else{
			if(u.cempresa.value == ""){
				alerta('Debe capturar Empresa','¡Atención!','cempresa');
			}else if(u.ccontacto.value == ""){
				alerta('Debe capturar Contacto','¡Atención!','ccontacto');				
			}else if(u.ctelefono.value == ""){
				alerta('Debe capturar Teléfono','¡Atención!','ctelefono');				
			}
		}
	}	
	
	function borrarComercial(){
		if(tabla2.getValSelFromField('empresa','EMPRESA')!=""){
			confirmar('¿Esta seguro de Eliminar la Empresa?','','eliminarComercial()','');	
		}
	}
	function eliminarComercial(){
		var obj = tabla2.getSelectedRow();
		consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=9&fecha="+obj.fecha+"&tipo=borrar");
		tabla2.deleteById(tabla2.getSelectedIdRow());
	}
	function modificarComercial(){
		if(tabla2.getValSelFromField('empresa','EMPRESA')!=""){
			var obj = tabla2.getSelectedRow();
			u.cempresa.value = obj.empresa;
			u.ccontacto.value = obj.contacto;
			u.ctelefono.value = obj.telefono;
			u.cfecha.value = obj.fecha;
			u.index.value = tabla2.getSelectedIndex();
		}
	}
	
	function agregarPersona(){
		if(u.persona.value!=""){
			if(u.index.value!=""){				
				var obj 	= new Object();
				obj.persona	= u.persona.value;
				obj.fecha	= u.fechapersona.value;
				tabla3.updateRowById(tabla3.getSelectedIdRow(), obj);
				consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=5&persona="+u.persona.value
				+"&fecha="+u.fechapersona.value+"&tipo=modificar");
				u.persona.value 	= "";
				u.index.value 		= "";
				u.fechapersona.value = "";
				u.persona.focus();
			}else{
				var obj		= new Object();
				obj.persona	= u.persona.value;
				obj.fecha	= fechahora(u.fechapersona.value);
				tabla3.add(obj);
				consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=5&persona="+u.persona.value
				+"&fecha="+obj.fecha+"&tipo=alta");
				u.persona.value 	= "";
				u.fechapersona.value = "";
				u.persona.focus();
			}
		}else{
			alerta('Debe capturar Persona Autorizada','¡Atención!','persona');
		}
	}
	function borrarPersona(){
		if(tabla3.getValSelFromField('persona','PERSONA')!=""){
			confirmar('¿Esta seguro de Eliminar a la persona?','','eliminarPersona()','');	
		}
	}
	function eliminarPersona(){
		var obj = tabla3.getSelectedRow();		
		consultaTexto("registroTablas","solicitudContratoAperturaCredito_con.php?accion=5&fecha="+obj.fecha+"&tipo=borrar");
		tabla3.deleteById(tabla3.getSelectedIdRow());
	}
	function modificarPersona(){		
		if(tabla3.getValSelFromField('persona','PERSONA')!=""){
			var obj = tabla3.getSelectedRow();
			u.persona.value = obj.persona;
			u.fechapersona.value = obj.fecha;
			u.index.value = tabla3.getSelectedIndex();
		}
	}	
	
	function borrarIndex(e,obj){
		tecla= (u) ? e.keyCode : e.which;
    	if((tecla==8 || tecla==46) && document.getElementById(obj).value==""){
			u.index.value = "";
		}
	}
	function obtener(id){
		u.folio.value = id;
		consultaTexto("mostrarDatos","solicitudContratoAperturaCredito_con.php?accion=1&credito="+id);
	}
	function mostrarDatos(datos){
		if(datos.indexOf("no encontro")<0){
			limpiarDatos();
			var obj = eval(convertirValoresJson(datos));			
			u.fecha.value 			= obj.principal.fechasolicitud;
			u.estado.innerHTML 		= obj.principal.estado;
			u.folioconvenio.value   = ((obj.principal.folioconvenio!="0")?obj.principal.folioconvenio:"");
			u.fechaautorizacion.value = obj.principal.fechaautorizacion;
			if(obj.principal.personamoral==1){
				u.rdmoral[0].checked = true;
			}else{
				u.rdmoral[1].checked = true;
			}
			u.cliente.value = obj.principal.cliente;	
			u.giro.value= obj.principal.giro;
			u.antiguedad.value = obj.principal.antiguedad;
			u.representantelegal.value = obj.principal.representantelegal;
			u.actaconstitutiva.checked = ((obj.principal.actaconstitutiva==1)?true:false)
			u.nacta.value = obj.principal.numeroacta;	
			u.fechaescritura.value = ((obj.principal.fechaescritura!="00/00/0000")?obj.principal.fechaescritura:"");
			u.fechainscripcion.value = ((obj.principal.fechainscripcion!="00/00/0000")?obj.principal.fechainscripcion:"");	
			u.identificacion.checked = ((obj.principal.identificacionlegal==1)?true:false);
			u.nidentificacion.value = obj.principal.numeroidentificacion;
			u.hacienda.checked = ((obj.principal.hacienda==1)?true:false);
u.fechainiciooperaciones.value = ((obj.principal.fechainiciooperaciones!="00/00/0000")?obj.principal.fechainiciooperaciones:"");
			u.rfc2.value = obj.principal.rfc2;
			u.comprobante.checked = ((obj.principal.comprobante==1)?true:false);			
			if(obj.principal.comprobanteluz==1){
				u.comprobanteluz[0].checked = true ;
			}else{
				u.comprobanteluz[1].checked = true ;
			}
			u.estadocuenta.checked = ((obj.principal.estadocuenta==1)?true:false);	
			u.banco.value = obj.principal.banco;
			u.cuenta.value = obj.principal.cuenta;
			u.solicitud.checked = ((obj.principal.solicitud==1)?true:false);
			u.semanapago.checked =((obj.principal.semanapago==1)?true:false);
			u.lunespago.checked = ((obj.principal.lunespago==1)?true:false);
			u.martespago.checked = ((obj.principal.martespago==1)?true:false);
			u.miercolespago.checked = ((obj.principal.miercolespago==1)?true:false);
			u.juevespago.checked = ((obj.principal.juevespago==1)?true:false);
			u.viernespago.checked = ((obj.principal.viernespago==1)?true:false);
			u.sabadopago.checked = ((obj.principal.sabadopago==1)?true:false);
			activarPago();
			desactivarSemanaPago();
			u.horariopago.value = obj.principal.horariopago;
			u.apago.value = obj.principal.apago;
			var hpago = u.horariopago.value.split(":");
				u.shpago.value = hpago[0];
				u.smpago.value = hpago[1];				
			var apa = u.apago.value.split(":");
				u.ahpago.value = apa[0];
				u.ampago.value = apa[1];
			u.responsablepago.value=obj.principal.responsablepago;
			u.celularpago.value= obj.principal.celularpago;
			u.telefonopago.value= obj.principal.telefonopago;
			u.faxpago.value= obj.principal.faxpago;						
			if(obj.principal.idcliente=="0"){
				u.notieneconvenio.value = "no tiene";
			}else{
				u.clienteconvenio.value = obj.principal.idcliente;
			}			
			u.semanarevision.checked 	= ((obj.principal.semanarevision==1)?true:false);
			u.lunesrevision.checked 	= ((obj.principal.lunesrevision==1)?true:false);
			u.martesrevision.checked 	= ((obj.principal.martesrevision==1)?true:false);
			u.miercolesrevision.checked = ((obj.principal.miercolesrevision==1)?true:false);
			u.juevesrevision.checked 	= ((obj.principal.juevesrevision==1)?true:false);
			u.viernesrevision.checked 	= ((obj.principal.viernesrevision==1)?true:false);
			u.sabadorevision.checked 	= ((obj.principal.sabadorevision==1)?true:false);
			activarRevision();
			desactivarSemanaRevision();
			u.horariorevision.value=obj.principal.horariorevision;
			u.arevision.value = obj.principal.arevision;
			var hrevision = u.horariorevision.value.split(":");
				u.shrevision.value = hrevision[0];
				u.smrevision.value = hrevision[1];
			var arev = u.arevision.value.split(":");
				u.ahrevision.value = arev[0];
				u.amrevision.value = arev[1];			
			u.msolicitado.value = '$ '+numcredvar(obj.principal.montosolicitado);
			u.mautorizado.value = '$ '+numcredvar(obj.principal.montoautorizado);			
			u.observaciones.value= obj.principal.observaciones;
			u.diacredito.value = obj.principal.diascredito;
			u.nick.value = obj.principal.nick;
			u.rfc.value = obj.principal.rfc;
			u.nombre.value = obj.principal.nombre;
			u.paterno.value = obj.principal.paterno;
			u.materno.value = obj.principal.materno;
			u.celular.value = obj.principal.celular;
			u.email.value = obj.principal.email;			
			u.calle.value = obj.principal.calle;
			u.numero.value = obj.principal.numero;
			u.cp.value = obj.principal.cp;			
			u.colonia.value =obj.principal.colonia;
			u.poblacion.value =obj.principal.poblacion;
			u.municipio.value =obj.principal.municipio;			
			u.estado2.value =obj.principal.estadoc;
			u.pais.value =obj.principal.pais;
			u.telefono.value =obj.principal.telefono;
			habilitarCajas();
			
			if(u.estado.innerHTML == "EN AUTORIZACION" ){
				u.accion.value ="autorizar";
				u.mautorizado.disabled = false;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				u.col.innerHTML = div; u.colno.innerHTML = divNo;
			}
			if(u.estado.innerHTML == "AUTORIZADO"){
				u.accion.value ="activar";
				u.mautorizado.disabled = false;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				u.col.innerHTML = divAc;
			}
			if(u.estado.innerHTML == "ACTIVADO" || u.estado.innerHTML == "BLOQUEADO"){
				u.btn_Enviarp.style.visibility = "hidden";
				u.mautorizado.disabled = false;
				u.mautorizado.readOnly = true;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				u.diacredito.readOnly = true;
				u.sucursalesead1_sel.disabled = true;
			}			
			u.todas.checked = ((obj.idsucursal == "0")?true:false);	
			
			tabla1.setJsonData(obj.banco);
			tabla2.setJsonData(obj.comerciales);
			tabla3.setJsonData(obj.persona);
			tabla4.setJsonData(obj.sucursal);
		}
	}	
	
	function limpiar(tipo){		
			u.fecha.value = ""; u.estado.innerHTML = "SOLICITUD"; u.folioconvenio.value = "";
			u.fechaautorizacion.value = ""; 
			u.rdmoral[0].checked = false;
			u.rdmoral[1].checked = false; u.cliente.value = "";	u.giro.value= "";
			u.antiguedad.value = ""; u.representantelegal.value = ""; 
			u.actaconstitutiva.checked = false; u.nacta.value= ""; 	u.fechaescritura.value = "";	
			u.fechainscripcion.value = ""; 	u.identificacion.checked = false;
			u.nidentificacion.value = ""; u.hacienda.checked = false; 
			u.fechainiciooperaciones.value = ""; u.rfc2.value = "";	u.comprobante.checked = false ;
			u.comprobanteluz[0].checked = false ; u.comprobanteluz[1].checked = false ;
			u.estadocuenta.checked = false ; u.banco.value = ""; u.cuenta.value = "";	
			u.solicitud.checked = false ; u.semanapago.checked = false ; u.lunespago.checked = false ;
			u.martespago.checked = false ; u.miercolespago.checked = false ;
			u.juevespago.checked = false ; u.viernespago.checked = false ;
			u.sabadopago.checked = false ; u.horariopago.value = ""; u.apago.value = "";		
			u.responsablepago.value=""; u.celularpago.value= ""; u.telefonopago.value= "";
			u.faxpago.value= ""; u.semanarevision.checked = false ; u.lunesrevision.checked = false ;
			u.martesrevision.checked = false ;	u.miercolesrevision.checked = false;
			u.juevesrevision.checked = false; u.viernesrevision.checked = false;
			u.sabadorevision.checked = false; u.horariorevision.value="";
			u.arevision.value = "";	u.msolicitado.value=""; u.mautorizado.value= "";
			u.observaciones.value= ""; u.diacredito.value = ""; u.nick.value = "";
			u.rfc.value =""; u.nombre.value =""; u.paterno.value =""; u.materno.value ="";
			u.calle.value =""; 	u.numero.value =""; u.cp.value =""; u.colonia.value ="";
			u.poblacion.value =""; u.municipio.value = ""; 	u.pais.value ="";
			u.celular.value =""; u.telefono.value =""; u.email.value ="";
			u.estado2.value =""; u.accion.value =""; u.mautorizado.disabled = true;
			u.mautorizado.style.backgroundColor	= "#FFFF99";
			u.diacredito.style.backgroundColor	= "#FFFF99"; u.diacredito.disabled = true;
			//u.btn_Enviarp.style.visibility = "visible";
			u.col.innerHTML = divEnviar;
			tabla1.clear(); tabla2.clear(); tabla3.clear(); tabla4.clear();
				if(tipo == 1){
					u.accion.value = "limpiar";
					document.form1.submit();
				}
	}
	function limpiarDatos(){
		u.fecha.value = ""; u.estado.innerHTML = "SOLICITUD"; u.folioconvenio.value = "";
	u.fechaautorizacion.value = ""; 
	 u.rdmoral[0].checked = false;
	u.rdmoral[1].checked = false; u.cliente.value = "";	u.giro.value= "";
	u.antiguedad.value = ""; u.representantelegal.value = ""; 
	u.actaconstitutiva.checked = false; u.nacta.value= ""; 	u.fechaescritura.value = "";	
	u.fechainscripcion.value = ""; 	u.identificacion.checked = false;
	u.nidentificacion.value = ""; u.hacienda.checked = false; 
	u.fechainiciooperaciones.value = ""; u.rfc2.value = "";	u.comprobante.checked = false ;
	u.comprobanteluz[0].checked = false ; u.comprobanteluz[1].checked = false ;
	u.estadocuenta.checked = false ; u.banco.value = ""; u.cuenta.value = "";	
	u.solicitud.checked = false ; u.semanapago.checked = false ; u.lunespago.checked = false ;
	u.martespago.checked = false ; u.miercolespago.checked = false ;
	u.juevespago.checked = false ; u.viernespago.checked = false ;
	u.sabadopago.checked = false ; u.horariopago.value = ""; u.apago.value = "";		
	u.responsablepago.value=""; u.celularpago.value= ""; u.telefonopago.value= "";
	u.faxpago.value= ""; u.semanarevision.checked = false ; u.lunesrevision.checked = false ;
	u.martesrevision.checked = false ;	u.miercolesrevision.checked = false;
	u.juevesrevision.checked = false; u.viernesrevision.checked = false;
	u.sabadorevision.checked = false; u.horariorevision.value="";
	u.arevision.value = "";	u.msolicitado.value=""; u.mautorizado.value= "";
	u.observaciones.value= ""; u.diacredito.value = ""; u.nick.value = "";
	u.rfc.value =""; u.nombre.value =""; u.paterno.value =""; u.materno.value ="";
	u.calle.value =""; 	u.numero.value =""; u.cp.value =""; u.colonia.value ="";
	u.poblacion.value =""; u.municipio.value = ""; 	u.pais.value ="";
	u.celular.value =""; u.telefono.value =""; u.email.value ="";
	u.estado2.value =""; u.accion.value =""; u.mautorizado.disabled = true;
	u.mautorizado.style.backgroundColor	= "#FFFF99";
	u.diacredito.style.backgroundColor	= "#FFFF99"; u.diacredito.disabled = true;
	u.col.innerHTML = divEnviar;
	
	tabla1.clear(); tabla2.clear(); tabla3.clear(); tabla4.clear();
	}	
	function enviarAutorizacion(){
		if(u.folioconvenio.value!=""){
			if(u.folioconvenio.value!=" "){
				if(u.cliente.value != u.clienteconvenio.value){
					alerta("El folio de convenio no coincide con el cliente capturado","¡Atención!","folioconvenio");
					return false;
				}
			}
		}
		if(tabla4.getRecordCount() == 0){
			 alerta("Debe capturar Sucursal donde aplicara el Crédito","¡Atención!","sucursalesead1");
			 return false;
		}
		if(u.mautorizado.value.replace("$ ","").replace(/,/g,"")=="0.00"){
			alerta('Debe capturar Monto Autorizado','¡Atención!','mautorizado');			
		}else if(u.mautorizado.value.replace("$ ","").replace(/,/g,"") <= "0"){
			alerta('Debe capturar Monto Autorizado','¡Atención!','mautorizado');
		}else if(u.diacredito.value==""){
			alerta('Debe capturar Dias de Credito','¡Atención!','diacredito');
		}else if(u.diacredito.value <= "0"){
			alerta('Debe capturar Dias de Credito','¡Atención!','diacredito');
		}else{
			confirmar('¿Esta seguro de Autorizar el Credito?','','autorizarCredito()','');
		}
	}
	function autorizarCredito(){
		u.btnAutorizacion.style.visibility = "hidden"; 
		u.accion.value = "autorizar";
		u.horariopago.value  = u.shpago.value +":"+ u.smpago.value;
		u.apago.value  = u.ahpago.value +":"+ u.ampago.value;
		u.horariorevision.value  = u.shrevision.value +":"+ u.smrevision.value;
		u.arevision.value  = u.ahrevision.value +":"+ u.amrevision.value;
		u.accion.value = "autorizar";
		consultaTexto("registroAutorizacion","solicitudContratoAperturaCredito_con.php?accion=10&folioconvenio="+((u.folioconvenio.value=="")?0:u.folioconvenio.value)
		+"&fechaautorizacion="+((u.fechaautorizacion.value=="")?"00/00/0000":u.fechaautorizacion.value)
		+"&rdmoral="+((u.rdmoral[0].checked==true)?1:0)
		+"&cliente="+u.cliente.value+"&nick="+u.nick.value
		+"&rfc="+u.rfc.value+"&nombre="+u.nombre.value+"&paterno="+u.paterno.value
		+"&materno="+u.materno.value+"&calle="+u.calle.value+"&numero="+u.numero.value
		+"&cp="+u.cp.value+"&colonia="+u.colonia.value+"&poblacion="+u.poblacion.value
		+"&municipio="+u.municipio.value+"&pais="+u.pais.value+"&celular="+u.celular.value
		+"&telefono="+u.telefono.value+"&email="+u.email.value+"&estado2="+u.estado2.value
		+"&giro="+u.giro.value+"&antiguedad="+u.antiguedad.value+"&representantelegal="+u.representantelegal.value
		+"&actaconstitutiva="+((u.actaconstitutiva.checked==true)?1:0)+"&nacta="+u.nacta.value
		+"&fechaescritura="+u.fechaescritura.value+"&fechainscripcion="+u.fechainscripcion.value
		+"&identificacion="+((u.identificacion.checked==true)?1:0)+"&nidentificacion="+u.nidentificacion.value
		+"&hacienda="+((u.hacienda.checked==true)?1:0)+"&fechainiciooperaciones="+u.fechainiciooperaciones.value
		+"&rfc2="+u.rfc2.value+"&comprobante="+((u.comprobante.checked==true)?1:0)
		+"&comprobanteluz="+((u.comprobanteluz[0].checked==true)?1:0)
		+"&estadocuenta="+((u.estadocuenta.checked==true)?1:0)+"&banco="+u.banco.value
		+"&cuenta="+u.cuenta.value+"&solicitud="+((u.solicitud.checked==true)?1:0)
		+"&semanapago="+((u.semanapago.checked==true)?1:0)
		+"&lunespago="+((u.lunespago.checked==true)?1:0)
		+"&martespago="+((u.martespago.checked==true)?1:0)
		+"&miercolespago="+((u.miercolespago.checked==true)?1:0)
		+"&juevespago="+((u.juevespago.checked==true)?1:0)
		+"&viernespago="+((u.viernespago.checked==true)?1:0)
		+"&sabadopago="+((u.sabadopago.checked==true)?1:0)
		+"&horariopago="+u.horariopago.value+"&apago="+u.apago.value
		+"&responsablepago="+u.responsablepago.value
		+"&celularpago="+u.celularpago.value+"&telefonopago="+u.telefonopago.value
		+"&faxpago="+u.faxpago.value
		+"&semanarevision="+((u.semanarevision.checked==true)?1:0)
		+"&lunesrevision="+((u.lunesrevision.checked==true)?1:0)
		+"&martesrevision="+((u.martesrevision.checked==true)?1:0)
		+"&miercolesrevision="+((u.miercolesrevision.checked==true)?1:0)
		+"&juevesrevision="+((u.juevesrevision.checked==true)?1:0)
		+"&viernesrevision="+((u.viernesrevision.checked==true)?1:0)
		+"&sabadorevision="+((u.sabadorevision.checked==true)?1:0)
		+"&horariorevision="+u.horariorevision.value
		+"&arevision="+u.arevision.value
		+"&msolicitado="+u.msolicitado.value.replace("$ ","").replace(/,/g,"")
		+"&mautorizado="+u.mautorizado.value.replace("$ ","").replace(/,/g,"")
		+"&diacredito="+u.diacredito.value
		+"&observaciones="+u.observaciones.value
		+"&folio="+u.folio.value);
	}
	
	function registroAutorizacion(datos){
		if(datos.indexOf("ok")>-1){
			info("","La solicitud de credito a sido Autorizada correctamente");
			u.estado.innerHTML="AUTORIZADO";			
			u.btnNoAutorizacion.style.visibility = "hidden"; 
		}else{
			alerta3("Hubo un error al autorizar "+datos,"¡Atención!");
			u.btnAutorizacion.style.visibility = "visible"; 
		}
	}
	
	function enviarNoAutorizacion(){
		confirmar('¿Esta seguro de NO Autorizar el Credito?','','noautorizarCredito()','');
	}
	function noautorizarCredito(){		
		u.accion.value = "noautorizar";
		u.btnNoAutorizacion.style.visibility = "hidden";
		consultaTexto("registroNoAutorizado","solicitudContratoAperturaCredito_con.php?accion=12&folio="+u.folio.value);
	}
	
	function registroNoAutorizado(datos){
		if(datos.indexOf("ok")>-1){
			info("","La solicitud de credito NO fue Autorizada");
			u.estado.innerHTML = "NO AUTORIZADA";
			u.btnAutorizacion.style.visibility = "hidden"; 			
		}else{
			alerta3("Hubo un error al no autorizar "+datos,"¡Atención!");
			u.btnNoAutorizacion.style.visibility = "hidden";
		}
	}
	
	function activarCredito(){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			u.sucursalorigen.value = '<?=$_SESSION[IDSUCURSAL]?>';
		}
			confirmar('¿Esta seguro de Activar el Credito?','','actCredito()','');
	}
	function actCredito(){
		var pago = "";
		var revision = "";
		if(u.semanapago.checked==true){
			pago = "TODA LA SEMANA";
		}else{
			pago +=((u.lunespago.checked==true)?"L,":"");
			pago +=((u.martespago.checked==true)?"M,":"");
			pago +=((u.miercolespago.checked==true)?"MI,":"");
			pago +=((u.juevespago.checked==true)?"J,":"");
			pago +=((u.viernespago.checked==true)?"V,":"");
			pago +=((u.sabadopago.checked==true)?"S,":"");
			pago = pago.substring(0,pago.length-1);
		}
		
		if(u.semanarevision.checked==true){
			revision = "TODA LA SEMANA";
		}else{
			revision +=((u.lunesrevision.checked==true)?"L,":"");
			revision +=((u.martesrevision.checked==true)?"M,":"");
			revision +=((u.miercolesrevision.checked==true)?"MI,":"");
			revision +=((u.juevesrevision.checked==true)?"J,":"");
			revision +=((u.viernesrevision.checked==true)?"V,":"");
			revision +=((u.sabadorevision.checked==true)?"S,":"");
			revision = revision.substring(0,revision.length-1);			
		}
		u.accion.value = "activar";
		u.btnActivar.style.visibility = "hidden";
		consultaTexto("registroActivado","solicitudContratoAperturaCredito_con.php?accion=11&folio="+u.folio.value
		+"&autorizado="+u.mautorizado.value.replace("$ ","").replace(/,/g,"")
		+"&diascredito="+u.diacredito.value
		+"&revision="+revision
		+"&pago="+pago
		+"&cliente="+u.cliente.value);
	}
	
	function registroActivado(datos){
		if(datos.indexOf("ok")>-1){
			info("","La solicitud de credito fue Activado satisfactoriamente");
			u.estado.innerHTML = "ACTIVADO";
		}else{
			alerta3("Hubo un error al activar "+datos,"¡Atención!");
			u.btnActivar.style.visibility = "visible";
		}
	}
	
	function foco(nombrecaja){
	if(nombrecaja=="cliente"){
		u.oculto.value="1";
	}else if(nombrecaja=="folioconvenio"){
		u.oculto.value="2";
	}
}
	shortcut.add("Ctrl+b",function() {
		if(u.oculto.value=="1"){
				if(u.rdmoral[0].checked==true){
	abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&tipo=moral', 550, 450, 'ventana', 'Busqueda');
				   }else{
	abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&tipo=fisica', 550, 450, 'ventana', 'Busqueda');
				   }
		}else if(u.oculto.value=="2"){
	abrirVentanaFija('buscarConvenio.php',550, 450, 'ventana', 'Busqueda')
		}
	});
	function tabular(e,obj){
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
	function mostrarSolicitudCreditoPendientes(){
		abrirVentanaFija('buscarCredito.php?accion=1', 550, 450, 'ventana', 'Busqueda');
	}
	function mostrarSolicitudCreditoPendientesActivar(){
		abrirVentanaFija('buscarCredito.php?accion=2', 550, 450, 'ventana', 'Busqueda');		
	}
	function ValidaRfc(rfcStr) {
	var strCorrecta;
	strCorrecta = rfcStr;
	
	if (u.rfc2.value.length == "12"){
		var valid = '^(([A-Z]|[a-z]|[&]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	}else if(u.rfc2.value.length == "13"){
		var valid = '^(([A-Z]|[a-z]|[&]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	}else{
		return false;	
	}
	var validRfc=new RegExp(valid);
	var matchArray=strCorrecta.match(validRfc);
	if (matchArray==null) {	
		return false;
	}else{
	return true;
	}
	
}
	function validarFecha(e,param,name){
		tecla = (document.all) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,2),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					alerta('La fecha no es valida', '¡Atención!',name);
					return false;
				}
				if (dia>"31" || dia=="0" ){
	alerta('La fecha no es valida, capture correctamente el Dia', '¡Atención!',name);
					return false;	
				}
				if (mes>"12" || mes=="0" ){
	alerta('La fecha no es valida, capture correctamente el Mes', '¡Atención!',name);
					return false;	
				}	
			}	
		}
	}	
	function habilitarCajas(){
		if(u.actaconstitutiva.checked == true){
			u.nacta.readOnly		 	 			= false;
			u.fechaescritura.disabled 	 			= false;
			u.fechainscripcion.disabled  			= false;
			u.nacta.style.backgroundColor			= "";
			u.fechaescritura.style.backgroundColor	= "";
			u.fechainscripcion.style.backgroundColor = "";			
		}
		if(u.identificacion.checked == true){		
			u.nidentificacion.readOnly  			= false;
			u.nidentificacion.style.backgroundColor	= "";			
		}
		if(u.hacienda.checked == true){			
			u.fechainiciooperaciones.disabled  				= false;
			u.fechainiciooperaciones.style.backgroundColor	= "";
			u.rfc2.readOnly  								= false;
			u.rfc2.style.backgroundColor					= "";			
		}
		if(u.comprobante.checked == true){
			u.comprobanteluz[0].disabled = false;
			u.comprobanteluz[1].disabled = false;		
		}
		if(u.estadocuenta.checked == true){			
			u.cuenta.style.backgroundColor = "";
			u.banco.style.backgroundColor  = "";
			u.banco.readOnly 		 = false;
			u.cuenta.readOnly 		 = false;			
		}
	}
	function Numeros(evt){ 
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
	}	
	
	function agregarSucursal(){
		var sucursal = u.sucursalesead1_sel.options[u.sucursalesead1_sel.selectedIndex].text;
		if(tabla4.getRecordCount()>0){
			for(var i=0; i<tabla4.getRecordCount(); i++){
				if(u["detalleSucursal_SUCURSAL"].value == sucursal){
					alerta3("La sucursal de "+sucursal+" seleccionada ya fue agregado","¡Atencion!");
					u.sucursalesead1_sel.value = "";
					return false;
				}
			}
		}
		var obj = new Object();
		obj.sucursal = sucursal;
		obj.idsucursal = u.sucursalesead1_sel.value;
		obj.fecha		 = fechahora(u.fechasucursal.value);
		tabla4.add(obj);
		consultaTexto("registroSucursal","solicitudContratoAperturaCredito_con.php?accion=6&sucursal="+sucursal
		+"&idsucursal="+u.sucursalesead1_sel.value+"&fecha="+obj.fecha);
		u.sucursalesead1_sel.value = "";
		u.fechasucursal.value = "";
	}
	
	function borrarSucursal(){
		if(tabla4.getValSelFromField('sucursal','SUCURSAL')!=""){
			confirmar('¿Esta seguro de Eliminar la sucursal?','','eliminarSucursal()','');	
		}
	}
	
	function eliminarSucursal(){
		var obj = tabla4.getSelectedRow();
		consultaTexto("registroSucursal","solicitudContratoAperturaCredito_con.php?accion=7&sucursal=TODAS&fecha="+obj.fecha
		+"&idsucursal="+obj.idsucursal);
		tabla4.deleteById(tabla4.getSelectedIdRow());
	}
	
	function agregarTodasSucursales(){
		if(tabla4.getRecordCount()>0){
			tabla4.clear();
			consultaTexto("registroSucursal","solicitudContratoAperturaCredito_con.php?accion=7&sucursal=TODAS&tenia=si");		 
		}else{	
			if(u.todas.checked==true){
				tabla4.clear();
				var obj = new Object();
				obj.sucursal = "TODAS";
				obj.idsucursal = 0;
				obj.fecha = fechahora(u.fechasucursal.value);
				tabla4.add(obj);
				u.sucursalesead1_sel.disabled=true;
				consultaTexto("registroSucursal","solicitudContratoAperturaCredito_con.php?accion=6&sucursal=TODAS&fecha="+obj.fecha);
			}else{
				for(var i=0; i < tabla4.getRecordCount(); i++){
					if(u["detalleSucursal_SUCURSAL"][i].value == "TODAS"){
						u.fechasucursal.value = u["detalleSucursal_FECHA"][i].value;
					}
				}
				u.sucursalesead1_sel.disabled=false;
				consultaTexto("registroSucursal","solicitudContratoAperturaCredito_con.php?accion=7&sucursal=TODAS&fecha="+u.fechasucursal.value);
				tabla4.clear();
			}
		}
	}	
	
	function registroSucursal(datos){
		if(datos.indexOf("ok")<0){
			alerta3("Hubo un error al eliminar la sucursal "+datos,"¡Atención!");		
		}else{
			if(datos.indexOf("si")>-1){
				agregarTodasSucursales();
			}
		}		
	}
	
	function obtenerConvenio(id){
		u.folioconvenio.value = id;
		consultaTexto("mostrarConvenio","solicitudContratoAperturaCredito_con.php?accion=2&folio="+id);
	}
	function mostrarConvenio(datos){
		if(datos!="" && datos!=undefined){
			u.clienteconvenio.value = datos;
		}
		
		if(u.notieneconvenio.value == "no tiene"){
			alerta("El folio de convenio no coincide con el cliente capturado","¡Atención!","folioconvenio");
			u.folioconvenio.value = "";
			return false;
		}
	}
	function activarPago(){
		if(u.semanapago.checked == true){
			u.lunespago.disabled = true; u.martespago.disabled = true;
			u.miercolespago.disabled = true; u.juevespago.disabled = true;
			u.viernespago.disabled = true; u.sabadopago.disabled = true;
		}else{
			u.lunespago.disabled = false; u.martespago.disabled = false;
			u.miercolespago.disabled = false; u.juevespago.disabled = false;
			u.viernespago.disabled = false; u.sabadopago.disabled = false;
		}
	}
	function activarRevision(){
		if(u.semanarevision.checked == true){
			u.lunesrevision.disabled = true; u.martesrevision.disabled = true;
			u.miercolesrevision.disabled = true; u.juevesrevision.disabled = true;
			u.viernesrevision.disabled = true; u.sabadorevision.disabled = true;
		}else{
			u.lunesrevision.disabled = false; u.martesrevision.disabled = false;
			u.miercolesrevision.disabled = false; u.juevesrevision.disabled = false;
			u.viernesrevision.disabled = false; u.sabadorevision.disabled = false;
		}
	}
	function desactivarSemanaPago(){
		if(u.lunespago.checked==true){u.semanapago.disabled = true;}
		if(u.martespago.checked==true){u.semanapago.disabled = true;}
		if(u.miercolespago.checked==true){u.semanapago.disabled = true;}
		if(u.juevespago.checked==true){u.semanapago.disabled = true;}
		if(u.viernespago.checked==true){u.semanapago.disabled = true;}
		if(u.sabadopago.checked==true){u.semanapago.disabled = true;}
		if(u.lunespago.checked==false && u.martespago.checked==false
		   && u.miercolespago.checked==false && u.juevespago.checked==false
		   && u.viernespago.checked==false && u.sabadopago.checked==false){
			u.semanapago.disabled = false;
		}
	}
	function desactivarSemanaRevision(){
		if(u.lunesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.martesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.miercolesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.juevesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.viernesrevision.checked==true){u.semanarevision.disabled = true;}
		if(u.sabadorevision.checked==true){u.semanarevision.disabled = true;}
		if(u.lunesrevision.checked==false && u.martesrevision.checked==false
		   && u.miercolesrevision.checked==false && u.juevesrevision.checked==false
		   && u.viernesrevision.checked==false && u.sabadorevision.checked==false){
			u.semanarevision.disabled = false;
		}
	}
	function mostrarEstado(estado){
		u.estado.innerHTML = estado;
	}
	function validarPersona(){
		if(u.rdmoral[0].checked == true){			
			u.cliente.value = "";
			u.folioconvenio.value = "";
			limpiarCliente();
		}else if(u.rdmoral[1].checked == true){
			u.cliente.value = "";
			u.folioconvenio.value = "";
			limpiarCliente();
		}
	}
	function numcredvar(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString(); 
		if(flag) cad += '.'; 
		return cad;
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div style="position:absolute; left: 2px; top: 24px; visibility:visible;" id="tab_tab_id0">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="21"><input type="checkbox" name="actaconstitutiva" style="width:13px" value="1" id="actaconstitutiva"<? if($actaconstitutiva==1){echo "checked";} ?> onclick="validarDocumentacion(this.name);" onkeypress="return tabular(event,this)" /></td>
        <td width="95">Acta Constitutiva</td>
        <td width="49">No. Acta:</td>
        <td width="89"><span class="Tablas">
          <input name="nacta" type="text" class="Tablas" id="nacta" style="background:#FF9;width:80px" value="<?=$nacta ?>" readonly="readonly" onkeypress="return tabular(event,this)" />
        </span></td>
        <td width="66">F. Escritura:</td>
        <td width="103"><span class="Tablas">
          <input name="fechaescritura" type="text" class="Tablas" id="fechaescritura" style="background:#FF9;width:75px" value="<?=$fechaescritura ?>" disabled="disabled" onkeypress="validarFecha(event,this.value,this.name); return tabular(event,this)" />
          <img src="../img/calendario.gif" width="16" height="16" style="cursor:pointer" onclick="if(document.all.fechaescritura.readOnly==false){displayCalendar(document.all.fechaescritura,'dd/mm/yyyy',this)}" /> </span></td>
        <td width="75">F. Inscripcion:</td>
        <td width="101"><span class="Tablas">
          <input name="fechainscripcion" type="text" class="Tablas" id="fechainscripcion" style="background:#FF9;width:75px" value="<?=$fechainscripcion ?>" onkeypress="validarFecha(event,this.value,this.name); return tabular(event,this)">
          <img src="../img/calendario.gif" width="16" height="16" style="cursor:pointer" onclick="if(document.all.fechainscripcion.readOnly==false){displayCalendar(document.all.fechainscripcion,'dd/mm/yyyy',this)}" /></span></td>
      </tr>
      <tr>
        <td><input type="checkbox" name="identificacion" style="width:13px" value="1" id="identificacion" <? if($identificacion==1){echo "checked";} ?> onclick="validarDocumentacion(this.name);" onkeypress="return tabular(event,this)" /></td>
        <td colspan="3">Identificaci&oacute;n Representante Legal</td>
        <td colspan="3">No. Identificaci&oacute;n:<span class="Tablas">
          <input name="nidentificacion" type="text" class="Tablas" id="nidentificacion" style="background:#FF9;width:100px" value="<?=$nidentificacion ?>" readonly="readonly" onkeypress="return tabular(event,this)" />
        </span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="checkbox" name="hacienda" style="width:13px" value="1" id="hacienda" <? if($hacienda==1){echo "checked";} ?> onclick="validarDocumentacion(this.name);" onkeypress="return tabular(event,this)" /></td>
        <td>Alta Hacienda</td>
        <td>&nbsp;</td>
        <td colspan="3">F. Inicio Operaciones:<span class="Tablas">
          <input name="fechainiciooperaciones" type="text" class="Tablas" id="fechainiciooperaciones" style="background:#FF9;width:75px" value="<?=$fechainiciooperaciones ?>" disabled="disabled" onkeypress="validarFecha(event,this.value,this.name); return tabular(event,this)" />
          <img src="../img/calendario.gif" width="16" height="16" style="cursor:pointer" onclick="if(document.all.fechainiciooperaciones.readOnly==false){displayCalendar(document.all.fechainiciooperaciones,'dd/mm/yyyy',this)}" /></span></td>
        <td colspan="2">R.F.C.:<span class="Tablas">
          <input name="rfc2" type="text" class="Tablas" id="rfc2" style="background:#FF9;width:100px" value="<?=$rfc2 ?>" readonly="readonly" onkeypress="return tabular(event,this)" />
        </span></td>
      </tr>
      <tr>
        <td><input type="checkbox" name="comprobante" style="width:13px" value="1" id="comprobante" <? if($comprobante==1){echo "checked";} ?> onclick="validarDocumentacion(this.name);" onkeypress="return tabular(event,this)" /></td>
        <td colspan="2">Comprobante Domicilio</td>
        <td colspan="2"><input name="comprobanteluz" type="radio" style="width:13px" value="1"<? if($comprobanteluz=="1"){echo "checked";} ?> disabled="disabled" onkeypress="return tabular(event,this)" />
          Luz
          <input name="comprobanteluz" type="radio" style="width:13px" value="0" <? if($comprobanteluz=="0"){echo "checked";} ?> disabled="disabled" onkeypress="return tabular(event,this)" />
          Tel&eacute;fono</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="checkbox" name="estadocuenta" style="width:13px" value="1" id="estadocuenta" <? if($estadocuenta==1){echo "checked";} ?> onclick="validarDocumentacion(this.name);" onkeypress="return tabular(event,this)" /></td>
        <td>Estado de Cuenta</td>
        <td>Banco:</td>
        <td colspan="2"><span class="Tablas">
          <input name="banco" type="text" class="Tablas" id="banco" style="background:#FF9;width:100px" value="<?=$banco ?>" readonly="readonly" onkeypress="return tabular(event,this)" />
        </span></td>
        <td colspan="2">Cuenta:<span class="Tablas">
          <input name="cuenta" type="text" class="Tablas" id="cuenta" style="background:#FF9;width:100px" value="<?=$cuenta ?>" readonly="readonly" onkeypress="return tabular(event,this)" />
        </span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="checkbox" name="solicitud" style="width:13px" value="1" id="solicitud" <? if($solicitud==1){echo "checked";} ?> onkeypress="return tabular(event,this)" /></td>
        <td>Solicitud</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
</div>	
<div style="position:absolute; left: 2px; top: 24px; visibility:visible;" id="tab_tab_id1">
	<table width="100%" cellpadding="0" cellspacing="0">          
          <tr>
            <td><table width="340" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>Persona autorizada(s)  revisi&oacute;n:<input name="fechapersona" type="hidden" id="fechapersona" />
                  <input name="persona" class="Tablas" onKeyUp="return borrarIndex(event,this.name)" type="text" id="persona" onKeyPress="if(event.keyCode==13){agregarPersona();}" style="width:100px" value="<?=$persona ?>">
                  <img src="../img/Boton_Agregari.gif" width="70" height="20" align="absbottom" style="cursor:pointer" onClick="agregarPersona();"></td>
              </tr>
              <tr>
                <td><table width="290" border="0" cellpadding="0" cellspacing="0" id="tablaPersonas" name="tablaPersonas" >
                </table></td>
              </tr>
              <tr>
                <td><img src="../img/Boton_Eliminar.gif"  width="70" height="20" align="middle" style="cursor:pointer" onClick="borrarPersona();"></td>
              </tr>
            </table></td>
			<td width="300px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="fechasucursal" type="hidden" id="fechasucursal" /></td>
            <td><table width="255" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="206" class="Tablas"><input type="checkbox" name="todas" style="width:13px" value="1" id="todas" <? if($todas==1){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="agregarTodasSucursales();">Todas
                    <select class="Tablas" name="sucursalesead1_sel" style="width:150px" onChange="agregarSucursal()")>
                      <option value=""></option>
                      <? 	
					$s = "select id, descripcion from catalogosucursal where id > 1 ORDER BY descripcion ASC";
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
                <td><table id="detalleSucursal" width="100%" border="0" cellpadding="0" cellspacing="0">                 
                </table></td>
              </tr>
              </table></td>
          </tr>
    </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; visibility:visible;" id="tab_tab_id2">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2" class="FondoTabla">Dias de Revision </td>
        <td colspan="13">&nbsp;</td>
        </tr>
      <tr>
        <td width="20"><input type="checkbox" name="semanapago" style="width:13px" value="1" id="semanapago" <? if($semanapago=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="activarPago();" /></td>
        <td width="80"><label>Toda la Semana</label>
          <label> </label></td>
        <td width="20"><input type="checkbox" style="width:13px" name="lunespago" value="1" id="lunespago" <? if($lunespago=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaPago()" /></td>
        <td width="10">L</td>
        <td width="20"><input type="checkbox" style="width:13px" name="martespago" value="1" id="martespago" <? if($martespago=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaPago()" /></td>
        <td width="10">M</td>
        <td width="20"><input type="checkbox" name="miercolespago" style="width:13px" value="1" id="miercolespago" <? if($miercolespago=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaPago()" /></td>
        <td width="14">MI</td>
        <td width="20"><input type="checkbox" name="juevespago" style="width:13px" value="1" id="juevespago" <? if($juevespago=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaPago()" /></td>
        <td width="10">J</td>
        <td width="20"><input type="checkbox" name="viernespago" style="width:13px" value="1" id="viernespago" <? if($viernespago=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaPago()" /></td>
        <td width="10">V</td>
        <td width="20"><input type="checkbox" name="sabadopago" style="width:13px" value="1" id="sabadopago" <? if($sabadopago=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaPago()" /></td>
        <td width="38">S</td>
        <td width="272"><label>Horario:<span class="Tablas">
        <select name="shpago" size="1" onkeypress="return tabular(event,this)" class="Tablas" id="shpago">
          <? for($h=0;$h<24;$h++){ ?>
          <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
          <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
          </option>
          <? }?>
        </select>
        <select name="smpago" size="1" onkeypress="return tabular(event,this)" class="Tablas" id="smpago">
          <? for($m=0;$m<60;$m++){ ?>
          <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
          <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
          </option>
          <? }?>
        </select>
        </span></label>
          <label>a<span class="Tablas">
          <select name="ahpago" size="1" onkeypress="return tabular(event,this)" class="Tablas" id="ahpago">
            <? for($h=0;$h<24;$h++){ ?>
            <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
            <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
            </option>
            <? }?>
          </select>
          <select name="ampago" size="1" onkeypress="return tabular(event,this)" class="Tablas" id="ampago">
            <? for($m=0;$m<60;$m++){ ?>
            <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
            <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
            </option>
            <? }?>
          </select>
          </span></label></td>
      </tr>
      <tr>
        <td colspan="15">Personas Responsable de Pagos: <span class="Tablas">
        <input name="responsablepago" type="text" class="Tablas" id="responsablepago" style="width:350px" value="<?=$responsablepago ?>" onkeypress="return tabular(event,this)" />
        </span></td>
        </tr>
      <tr>
        <td colspan="15">Celular:<span class="Tablas">
        <input name="celularpago" type="text" class="Tablas" id="celularpago" style="width:120px" value="<?=$celularpago ?>" onkeypress="return tabular(event,this)" />
        </span>Tel&eacute;fono:<span class="Tablas">
        <input name="telefonopago" type="text" class="Tablas" id="telefonopago" style="width:120px" value="<?=$telefonopago ?>" onkeypress="return tabular(event,this)" />
        </span>Fax:<span class="Tablas">
        <input name="faxpago" type="text" class="Tablas" id="faxpago" style="width:120px" value="<?=$faxpago ?>" onkeypress="return tabular(event,this)" />
        </span></td>
      </tr>
      <tr>
        <td colspan="15">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="15">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="15">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="2" class="FondoTabla">Dias de Pago </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="20"><label>
                <input type="checkbox" name="semanarevision" value="1" id="semanarevision" <? if($semanarevision=="1"){echo "checked";} ?> style="width:13px" onkeypress="return tabular(event,this)" onclick="activarRevision();" />
                </label>
                  <label></label></td>
              <td width="80"><label>Toda la Semana</label>
                  <label> </label></td>
              <td width="20"><input type="checkbox" name="lunesrevision" style="width:13px" value="1" id="lunesrevision" <? if($lunesrevision=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaRevision();" /></td>
              <td width="10">L</td>
              <td width="20"><input type="checkbox" name="martesrevision" style="width:13px" value="1" id="martesrevision" <? if($martesrevision=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaRevision();" /></td>
              <td width="10">M</td>
              <td width="20"><input type="checkbox" name="miercolesrevision" style="width:13px" value="1" id="miercolesrevision" <? if($miercolesrevision=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaRevision();" /></td>
              <td width="14">MI</td>
              <td width="20"><input type="checkbox" name="juevesrevision" style="width:13px" value="1" id="juevesrevision" <? if($juevesrevision=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaRevision();" /></td>
              <td width="10">J</td>
              <td width="20"><input type="checkbox" name="viernesrevision" style="width:13px" value="1" id="viernesrevision" <? if($viernesrevision=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaRevision();" /></td>
              <td width="10">V</td>
              <td width="20"><input type="checkbox" name="sabadorevision" style="width:13px" value="1" id="sabadorevision" <? if($sabadorevision=="1"){echo "checked";} ?> onkeypress="return tabular(event,this)" onclick="desactivarSemanaRevision();" /></td>
              <td width="38">S</td>
              <td width="274"><label>Horario:</label>
                  <label><span class="Tablas">
                  <select name="shrevision" size="1" onkeypress="return tabular(event,this)" class="Tablas" id="shrevision">
                    <? for($h=0;$h<24;$h++){ ?>
                    <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? }?>
                  </select>
                  <select name="smrevision" size="1" onkeypress="return tabular(event,this)" class="Tablas" id="smrevision">
                    <? for($m=0;$m<60;$m++){ ?>
                    <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? }?>
                  </select>
                    a
                    <select name="ahrevision" size="1" onkeypress="return tabular(event,this)" class="Tablas" id="ahrevision">
                      <? for($h=0;$h<24;$h++){ ?>
                      <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                        <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                      </option>
                      <? }?>
                    </select>
                    <select name="amrevision" size="1" onkeypress="return tabular(event,this)" class="Tablas" id="amrevision">
                      <? for($m=0;$m<60;$m++){ ?>
                      <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                        <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                      </option>
                      <? }?>
                    </select>
                </span></label></td>
            </tr>
          </table>
          </td>
        </tr>
    </table></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; visibility:visible;" id="tab_tab_id3">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="53">Banco:</td>
        <td width="162"><span class="Tablas">
          <input name="rbanco" type="text" class="Tablas" id="rbanco" style="width:80px" value="<?=$rbanco ?>" onkeypress="return tabular(event,this)" />
        </span></td>
        <td width="106">Sucursal:</td>
        <td width="147"><span class="Tablas">
          <input name="rsucursal" type="text" class="Tablas" id="rsucursal" style="width:80px" value="<?=$rsucursal ?>" onkeypress="return tabular(event,this)"/>
        </span></td>
        <td width="92">Cuenta:</td>
        <td width="147"><span class="Tablas">
          <input name="rcuenta" type="text" class="Tablas" id="rcuenta" style="width:80px" value="<?=$rcuenta ?>" onkeypress="if(event.keyCode==13){document.all.rtelefono.focus();}else{return solonumeros(event)}" />
        </span></td>
        <td width="99">Tel&eacute;fono:</td>
        <td width="134"><span class="Tablas">
          <input name="rtelefono" type="text" class="Tablas" id="rtelefono" onkeypress="if(event.keyCode==13){agregarBanco();}" style="width:80px" value="<?=$rtelefono ?>" />
		  <input name="rfecha" type="hidden" id="rfecha" />
        </span></td>
        <td width="35"><div class="ebtn_agregar" onclick="agregarBanco()"></div></td>
      </tr>
      <tr>
        <td colspan="9"></td>
        </tr>
      <tr>
        <td colspan="8"><table width="100%" cellpadding="0" border="0" cellspacing="0" name="tablaBanco" id="tablaBanco" ></table></td>
        <td><img src="../img/Boton_Eliminar.gif"  width="70" height="20" align="middle" style="cursor:pointer" onClick="borrarBanco();"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; " id="tab_tab_id4">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="284">Empresa:</td>
        <td width="285"><span class="Tablas">
          <input name="cempresa" type="text" class="Tablas" id="cempresa" style="width:80px" value="<?=$cempresa?>" onkeypress="return tabular(event,this)" />
        </span></td>
        <td width="571">&nbsp;</td>
        <td width="571">&nbsp;</td>
        <td width="571">Contacto:</td>
        <td width="571"><span class="Tablas">
          <input name="ccontacto" type="text" class="Tablas" id="ccontacto" style="width:160px" value="<?=$ccontacto ?>" onkeypress="return tabular(event,this)" />
        </span></td>
        <td width="284">Tel&eacute;fono:</td>
        <td width="141"><span class="Tablas">
          <input name="ctelefono" type="text" class="Tablas" id="ctelefono" style="width:80px" value="<?=$ctelefono ?>" onkeypress="if(event.keyCode==13){agregarComercial();}" />
		  <input name="cfecha" type="hidden" id="cfecha" />
        </span></td>
        <td><div class="ebtn_agregar" onclick="agregarComercial()"></div></td>
      </tr>
      <tr>
        <td colspan="8"><table width="515" cellpadding="0" cellspacing="0" border="0" id="tablaComer" name="tablaComer" >
            </table></td>
        <td width="142"><img src="../img/Boton_Eliminar.gif"  width="70" height="20" align="middle" style="cursor:pointer" onClick="borrarComercial();"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
<table width="610" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">SOLICITUD APERTURA CREDITO </td>
    </tr>
    <tr>
      <td height="550px" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="4"><table width="532" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="25">&nbsp;</td>
                    <td width="109">&nbsp;</td>
                    <td width="50">Fecha:</td>
                    <td width="119"><span class="Tablas">
                      <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
                    </span></td>
                    <td width="56">Estado:</td>
                    <td width="173"  id="estado" style="font:tahoma; font-size:15px; font-weight:bold"></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="4"><table width="535" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="27">Folio:</td>
                    <td width="98"><span class="Tablas">
                      <input name="folio" type="text" class="Tablas" id="folio" style="width:50px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
                      <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" 
              onclick="abrirVentanaFija('buscarCredito.php?accion=3', 600, 550, 'ventana', 'Busqueda')" /></span></td>
                    <td width="76">Folio Convenio:</td>
                    <td width="55"><span class="Tablas">
                      <input name="folioconvenio" type="text" class="Tablas" id="folioconvenio" style="width:50px" value="<?=$folioconvenio ?>" />
                    </span></td>
                    <td width="69"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarConvenioGen.php?funcion=obtenerConvenio&amp;cestado=ACTIVADO', 550, 450, 'ventana', 'Busqueda')"></div></td>
                    <td width="100">Fecha Autorizaci&oacute;n: </td>
                    <td width="110"><span class="Tablas">
                      <input name="fechaautorizacion" type="text" class="Tablas" id="fechaautorizacion" style="width:100px;background:#FFFF99" value="<?=$fechaautorizacion ?>" readonly=""/>
                    </span></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="4" class="FondoTabla">DATOS DEL CLIENTE </td>
            </tr>
            <tr>
              <td colspan="4"><label>
                <input name="rdmoral" type="radio" onclick="validarPersona()" value="1" checked="checked" />
                Persona Moral</label>
                  <label>
                  <input name="rdmoral" type="radio" onclick="validarPersona()" value="0" />
                    Persona F&iacute;sica</label></td>
            </tr>
            <tr>
              <td width="93"># Cliente: </td>
              <td colspan="3"><span class="Tablas">
                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:100px" value="<?=$cliente ?>" onkeypress="obtenerCliente(event,this.value);" onfocus="foco(this.name)" onblur="document.getElementById('oculto').value=''" />
                <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" 
              onclick="if(document.all.rdmoral[0].checked==true){
              abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&amp;tipo=moral', 550, 450, 'ventana', 'Busqueda')
               }else{                             abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&amp;tipo=fisica', 550, 450, 'ventana', 'Busqueda')
               }
              " /></span></td>
            </tr>
            <tr>
              <td>Nick:</td>
              <td width="194"><span class="Tablas">
                <input name="nick" type="text" class="Tablas" id="nick" style="width:140px;background:#FFFF99" value="<?=$nick ?>" readonly=""/>
              </span></td>
              <td width="77"><span class="Tablas">R.F.C.:</span></td>
              <td width="225"><span class="Tablas">
                <input name="rfc" type="text" class="Tablas" id="rfc" style="width:140px;background:#FFFF99" value="<?=$rfc ?>" readonly=""/>
              </span></td>
            </tr>
            <tr>
              <td>Nombre:</td>
              <td colspan="3"><span class="Tablas">
                <input name="nombre" type="text" class="Tablas" id="nombre" style="width:410px;background:#FFFF99" value="<?=$nombre ?>" readonly=""/>
              </span></td>
            </tr>
            <tr>
              <td><span class="Tablas">Ap. Paterno:</span></td>
              <td><span class="Tablas">
                <input name="paterno" type="text" class="Tablas" id="paterno" style="width:140px;background:#FFFF99" value="<?=$paterno ?>" readonly=""/>
              </span></td>
              <td>Ap. Materno:</td>
              <td><span class="Tablas">
                <input name="materno" type="text" class="Tablas" id="materno" style="width:140px;background:#FFFF99" value="<?=$materno ?>" readonly=""/>
              </span></td>
            </tr>
            <tr>
              <td><span class="Tablas">
                <label>Calle</label>
                : </span></td>
              <td id="celda_des_calle"><span class="Tablas">
                <input name="calle" type="text" class="Tablas" id="calle" style="width:180px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>
                <input type="hidden" name="rem_direcciones" />
              </span></td>
              <td><span class="Tablas">N&uacute;mero: </span></td>
              <td><span class="Tablas">
                <input name="numero" type="text" class="Tablas" id="numero" style="width:82px;background:#FFFF99" value="<?=$numero ?>" readonly=""/>
              </span></td>
            </tr>
            <tr>
              <td>CP:</td>
              <td><input name="cp" type="text" class="Tablas" id="cp" style="width:140px;background:#FFFF99" value="<?=$cp ?>" readonly=""/></td>
              <td>Colonia:</td>
              <td><input name="colonia" type="text" class="Tablas" id="colonia" style="width:140px;background:#FFFF99" value="<?=$colonia ?>" readonly=""/></td>
            </tr>
            <tr>
              <td>Poblaci&oacute;n:</td>
              <td><input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:140px;background:#FFFF99" value="<?=$poblacion ?>" readonly=""/></td>
              <td>Mun. / Deleg.:</td>
              <td><input name="municipio" type="text" class="Tablas" id="municipio" style="width:140px;background:#FFFF99" value="<?=$municipio ?>" readonly=""/></td>
            </tr>
            <tr>
              <td>Estado</td>
              <td><input name="estado2" type="text" class="Tablas" id="estado2" style="width:140px;background:#FFFF99" value="<?=$estado2 ?>" readonly=""/></td>
              <td>Pa&iacute;s:</td>
              <td><input name="pais" type="text" class="Tablas" id="pais" style="width:140px;background:#FFFF99" value="<?=$pais ?>" readonly=""/></td>
            </tr>
            <tr>
              <td>Celular:</td>
              <td><input name="celular" type="text" class="Tablas" id="celular" style="width:140px;background:#FFFF99" value="<?=$celular ?>" readonly=""/></td>
              <td>Telefono:</td>
              <td><input name="telefono" type="text" class="Tablas" id="telefono" style="width:140px;background:#FFFF99" value="<?=$telefono ?>" readonly=""/></td>
            </tr>
            <tr>
              <td>Email:</td>
              <td><input name="email" type="text" class="Tablas" id="email" style="width:140px;background:#FFFF99" value="<?=$email ?>" readonly=""/></td>
              <td>Giro:</td>
              <td><span class="Tablas">
                <input name="giro" type="text" class="Tablas" id="giro" style="width:140px" value="<?=$giro ?>" onkeypress="return tabular(event,this)" />
              </span></td>
            </tr>
            <tr>
              <td>Antiguedad:</td>
              <td><span class="Tablas">
                <input name="antiguedad" onkeypress="return tabular(event,this)" type="text" class="Tablas" id="antiguedad" style="width:140px" value="<?=$antiguedad ?>" />
              </span></td>
              <td>Repte. Legal:</td>
              <td><span class="Tablas">
                <input name="representantelegal" type="text" class="Tablas" id="representantelegal" style="width:140px" onkeypress="return tabular(event,this)" value="<?=$representantelegal ?>" />
              </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>        
        <tr>
          <td><table id="tab" cellpadding="0" cellspacing="0" border="0">
        </table></td>
        </tr>
		<tr>
		  <td height="148">&nbsp;</td>
	    </tr>
		<tr>
			<td height="19" class="FondoTabla">Autorización Trámite de Crédito</td>
		</tr>
		<tr>
			<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="6"><label>Monto Solicitado:<span class="Tablas">
                <input name="msolicitado" type="text" class="Tablas" id="msolicitado" style="width:120px" onkeypress="if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/g,'')); document.all.observaciones.focus();}else{return tiposMoneda(event,this.value);}"   value="<?=$msolicitado ?>" maxlength="15" />
                </span></label>
                  <label>Monto Autorizado:<span class="Tablas">
                  <input name="mautorizado" type="text" class="Tablas" id="mautorizado" style="width:120px" onkeypress="if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/g,'')); document.all.diacredito.focus();}else{return tiposMoneda(event,this.value);}" value="<?=$mautorizado ?>" maxlength="15" <? if($estado=="SOLICITUD"){ echo "style='background:#FF9'"; echo "disabled";} ?>>
Dias Credito:
<input name="diacredito" type="text" class="Tablas" id="diacredito" style="width:50px" onkeypress="return Numeros(event)" onkeydown="return tabular(event,this)" value="<?=$diacredito ?>" maxlength="10" <? if($estado=="SOLICITUD"){ echo "style='background:#FF9'"; echo "disabled";} ?>>
                  </span></label></td>
              </tr>
              <tr>
                <td width="15%" valign="top">Observaciones:</td>
                <td width="85%" colspan="5"><textarea name="observaciones" class="Tablas" style="text-transform:uppercase; height:25px" cols="60" onkeypress="return tabular(event,this)" id="observaciones"></textarea>
                  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />
                  <input name="registropersona" type="hidden" id="registropersona" value="<?=$registropersona ?>" />
                  <input name="registrobanco" type="hidden" id="registrobanco" value="<?=$registrobanco ?>" />
                  <input name="registrocomer" type="hidden" id="registrocomer" value="<?=$registrocomer ?>" />
                  <input name="index" type="hidden" id="index" value="<?=$index ?>" />
                  <input name="horariopago" type="hidden" id="horariopago" value="<?=$hpago ?>" />
                  <input name="apago" type="hidden" id="apago" value="<?=$apago ?>" />
                  <input name="horariorevision" type="hidden" id="horariorevision" value="<?=$hrevision ?>" />
                  <input name="arevision" type="hidden" id="arevision" value="<?=$arevision?>" />
                  <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>" />
                  <input name="clienteconvenio" type="hidden" id="clienteconvenio" value="<?=$clienteconvenio ?>" />
                  <input name="notieneconvenio" type="hidden" id="notieneconvenio" /></td>
              </tr>
              <tr>
                <td valign="top">&nbsp;</td>
                <td colspan="5"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="146" id="col"><div id="btn_Enviarp" class="ebtn_Enviarp"  onclick="validar();"></div></td>
                    <td width="90" id="colno">&nbsp;</td>
                    <td width="152"><div class="ebtn_imprimircontrato" ></div></td>
                    <td width="95"><div class="ebtn_nuevo" onclick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar(1);', '')"></div></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
		</tr>
      </table></td>
    </tr>
  </table>  
</form>
</body>
</html>
