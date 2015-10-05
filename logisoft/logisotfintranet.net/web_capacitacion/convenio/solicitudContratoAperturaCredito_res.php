<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "DELETE FROM solicitudcreditobancodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
	mysql_query($s,$l) or die($s);
	$s = "DELETE FROM solicitudcreditocomercialesdetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
	mysql_query($s,$l) or die($s);
	$s = "DELETE FROM solicitudcreditopersonadetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
	mysql_query($s,$l) or die($s);
	$s = "DELETE FROM solicitudcreditosucursaldetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
	mysql_query($s,$l) or die($s);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
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
		var v_pestanas = Array(true,false,false,false,false);
		var tabla1 = new ClaseTabla();
		var tabla2 = new ClaseTabla();
		var tabla3 = new ClaseTabla();
		var tabla4 = new ClaseTabla();
		var v_autorizar = "";
		var v_activar = "";
		var u = document.all;
		var nav4   = window.Event ? true : false;
		var Input = '<input  class="Tablas" name="colonia" type="text" id="colonia" size="32" readonly="" value="<?= $colonia; ?>" style="font:tahoma;font-size:9px; background:#FFFF99; text-transform:uppercase" onDblClick="javascript:popUp(\'buscarcolonia2.php\')" />';
		var combo1 = "<select class='Tablas' name='colonia' id='colonia'  style='width:185px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";
		var div = "<div id='btnAutorizacion' class='ebtn_autorizarcredito' onClick='enviarAutorizacion();'></div>";
		var divNo = "<div id='btnNoAutorizacion' class='ebtn_noautorizarcredito' onClick='enviarNoAutorizacion();'></div>";
		var divAc = "<div id='btnActivar' class='ebtn_activarcredito' onClick='activarCredito();'></div>";		
		var divEnviar = '<input type="button" id="btn_Enviarp"   value=" " class="env-btn" title="Enviar" onclick="validar();"/>';
		
		var tabla_1 = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="80%" style="text-align:right" ><input name="button6" type="button" class="button" id="btn_Enviarp"  onclick="validar();" value="Enviar Autorizaci&oacute;n" /></td><td width="20%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiar(1);\', \'\')" value="Nuevo"/></td></tr></table>';
		
		var tabla_2 = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="62%" style="text-align:right"><input name="autorizar" type="button" class="button" onclick="enviarAutorizacion()" value="Autorizar"/></td><td width="23%" style="text-align:right"><input name="noautorizar" type="button" class="button" onclick="enviarNoAutorizacion()" value="No Autorizar"/></td><td width="15%" style="text-align:right"><input name="button6" type="button" class="button" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiar(1);\', \'\')" value="Nuevo"/></td></tr></table>';
		
		var tabla_3 = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="83%" style="text-align:right"><input name="activar" type="button" class="button" onclick="activarCredito();" value="Activar"/></td><td width="17%" style="text-align:right"><input name="button6" type="button" class="button" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiar(1);\', \'\')" value="Nuevo"/></td></tr></table>';
		
		var tabla_4 = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="55%" style="text-align:right">&nbsp;</td> <td width="15%" style="text-align:right"><input name="btnimprimir" id="btnimprimir" type="button" class="button" onclick="imprimirCredito()" value="Imprimir"/></td><td width="17%" style="text-align:right"><input type="button" name="guardar" id="guardar" class="button" value="Guardar" onclick="confirmar(\'¿Se encuentra seguro de grabar las modificaciones del Crédito?\',\'\',\'guardarModificacion()\',\'\')" /></td><td width="13%" style="text-align:right"><input name="button6" type="button" class="button" onclick="confirmar(\'Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?\', \'\', \'limpiar(1);\', \'\')" value="Nuevo"/></td></tr></table>';
			
		tabla1.setAttributes({
			nombre:"tablaBanco", 
			campos:[
				{nombre:"BANCO", medida:115, alineacion:"left", datos:"banco"},
				{nombre:"SUCURSAL", medida:115, alineacion:"left", datos:"sucursal"},
				{nombre:"CUENTA", medida:115, alineacion:"left", datos:"cuenta"},
				{nombre:"TELEFONO", medida:115, alineacion:"left", datos:"telefono"},
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
				{nombre:"EMPRESA", medida:130, alineacion:"left", datos:"empresa"},
				{nombre:"CONTACTO", medida:200, alineacion:"left", datos:"contacto"},
				{nombre:"TELEFONO", medida:130, alineacion:"left", datos:"telefono"},
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
				{nombre:"PERSONA", medida:250, alineacion:"left", datos:"persona"},
				{nombre:"FECHA", medida:4, tipo:"oculto", alineacion:"left", datos:"fecha"}			
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
			u.estado.innerHTML = "SOLICITUD";
			obtenerGeneral();
			<?
				$_GET[funcion2] = str_replace("\'","'",$_GET[funcion2]);
				if($_GET[funcion2]!=""){
					echo 'setTimeout("'.$_GET[funcion2].'",1500);';
				}
			?>
		}
		
		function seleccionarTab(index){
			var npagina = "div";
			var ntab	= "label";
			var pestanas= 5;
			
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
		
		function obtenerGeneral(){
		consultaTexto("mostrarGeneral","solicitudContratoAperturaCredito_con.php?accion=4");
	}
	
	function mostrarGeneral(datos){
		var obj = eval(datos);
		u.folio.value = obj.folio;
		u.fecha.value = obj.fecha;
		u.cliente.focus();
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
			
			
			if(u.rdmoral[0].checked == true){
				if(u.actaconstitutiva.checked == true){
					if(u.nacta.value == ""){			
						alerta('Debe capturar No. Acta','¡Atención!','nacta');
						seleccionarTab(0);
						return false;						
					}else if(u.fechaescritura.value == ""){			
						alerta('Debe capturar Fecha Escritura','¡Atención!','fechaescritura');
						seleccionarTab(0);
						return false;						
					}else if(u.fechainscripcion.value == ""){				
						alerta('Debe capturar Fecha de Inscripción','¡Atención!','fechainscripcion');
						seleccionarTab(0);
						return false;						
					}					
				}
			}
			if(u.identificacion.checked == true){
				if(u.nidentificacion.value == ""){			
			 	  alerta('Debe capturar No. Identificación','¡Atención!','nidentificacion');
				  seleccionarTab(0);
				  return false;
				}
			}
			if(u.hacienda.checked == true){
				if(u.fechainiciooperaciones.value == ""){			
			 		 alerta('Debe capturar Fecha Inicio Operaciones','¡Atención!','fechainiciooperaciones');
					 seleccionarTab(0);
					 return false;
				}else if(!ValidaRfc(u.rfc2.value)){			
					alerta('Debe capturar un R.F.C valido','¡Atención!','rfc2');
					seleccionarTab(0);
					return false;
				}			
			}
			if(u.comprobante.checked == true){
				if(u.comprobanteluz[0].checked == false && u.comprobanteluz[1].checked == false){			
					alerta('Debe capturar un Comprobante ','¡Atención!','comprobanteluz[0]');
					seleccionarTab(0);
					return false;
				}
			}
			if(u.estadocuenta.checked == true){
				if(u.banco.value == ""){				
					alerta('Debe capturar Banco','¡Atención!','banco');
					seleccionarTab(0);
					return false;
				}else if(u.cuenta.value == ""){				
					alerta('Debe capturar Cuenta','¡Atención!','cuenta');
					seleccionarTab(0);
					return false;
				}				
			}
			
			if(u.rdmoral[0].checked == true){
				/*if(u.actaconstitutiva.checked == false || u.identificacion.checked == false
				   || u.hacienda.checked == false || u.comprobante.checked == false
				   || u.estadocuenta.checked == false || u.solicitud.checked == false){
					alerta3('Debe capturar toda la Documentación Requerida para poder enviar la solicitud','¡Atención!');
					seleccionarTab(0);
					return false;
				}*/
			}else{
				/*if(u.identificacion.checked == false || u.hacienda.checked == false 
					|| u.comprobante.checked == false || u.estadocuenta.checked == false 
					|| u.solicitud.checked == false){
					alerta3('Debe capturar toda la Documentación Requerida para poder enviar la solicitud','¡Atención!');
					seleccionarTab(0);
					return false;
				}*/
			}
			if(tabla4.getRecordCount() == 0){
				 alerta3("Debe capturar Sucursal donde aplicara el Crédito","¡Atención!");
				 seleccionarTab(1);
				 return false;
			}
			if(u.semanapago.checked == false){
				if(u.lunespago.checked == false && u.martespago.checked == false
				   && u.miercolespago.checked == false && u.juevespago.checked == false
				   && u.viernespago.checked == false && u.sabadopago.checked == false){
					alerta3('Debe capturar día de pago','¡Atención!');
					seleccionarTab(2);
					return false;
				}
			}
			
			if(u.semanarevision.checked == false){
				if(u.lunesrevision.checked == false && u.martesrevision.checked == false
				   && u.miercolesrevision.checked == false && u.juevesrevision.checked == false
				   && u.viernesrevision.checked == false && u.sabadorevision.checked == false){
					alerta3('Debe capturar día de revisión','¡Atención!');
					seleccionarTab(2);
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
				//u.btn_Enviarp.style.visibility = "hidden";
				u.horariopago.value  = u.shpago.value +":"+ u.smpago.value;
				u.apago.value  = u.ahpago.value +":"+ u.ampago.value;
				u.horariorevision.value  = u.shrevision.value +":"+ u.smrevision.value;
				u.arevision.value  = u.ahrevision.value +":"+ u.amrevision.value;
				u.accion.value = "grabar";
				consultaTexto("registro","solicitudContratoAperturaCredito_con.php?accion=3&grabar=grabar&folioconvenio="+((u.folioconvenio.value=="")?0:u.folioconvenio.value)
				+"&fechaautorizacion="+((u.fechaautorizacion.innerHTML=="")?"00/00/0000":u.fechaautorizacion.innerHTML)
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
				//u.btn_Enviarp.style.visibility = "hidden";
				u.horariopago.value  = u.shpago.value +":"+ u.smpago.value;
				u.apago.value  = u.ahpago.value +":"+ u.ampago.value;
				u.horariorevision.value  = u.shrevision.value +":"+ u.smrevision.value;
				u.arevision.value  = u.ahrevision.value +":"+ u.amrevision.value;				
				u.accion.value = "modificar";
				consultaTexto("registro","solicitudContratoAperturaCredito_con.php?accion=3&grabar=modificar&folioconvenio="+((u.folioconvenio.value=="")?0:u.folioconvenio.value)
				+"&fechaautorizacion="+((u.fechaautorizacion.innerHTML=="")?"00/00/0000":u.fechaautorizacion.innerHTML)
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
			//u.btn_Enviarp.style.visibility = "visible";
			if(row[1]=="grabar"){
				u.folio.value = row[2];
			}
		}else{
			alerta3("Hubo un error al autorizar "+datos,"¡Atención!");
			//u.btn_Enviarp.style.visibility = "visible";
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
				document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="text" id="calle" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/>';
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
			/*u.nacta.style.backgroundColor			= "#FFFF99";
			u.fechaescritura.style.backgroundColor	= "#FFFF99";
			u.fechainscripcion.style.backgroundColor = "#FFFF99";	*/
			try{
				closeCalendar();				
			}catch(e){
					e = null;
			}
			
		}else if(nombre == "actaconstitutiva" && u.actaconstitutiva.checked == true){
			u.nacta.readOnly		 	 			= false;
			u.fechaescritura.disabled 	 			= false;
			u.fechainscripcion.disabled 			= false;
			/*u.nacta.style.backgroundColor			= "";
			u.fechaescritura.style.backgroundColor	= "";
			u.fechainscripcion.style.backgroundColor = "";	*/		
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
				document.getElementById('rbanco').focus();
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
				document.getElementById('rbanco').focus();
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
		//alerta3("mostrarDatos","solicitudContratoAperturaCredito_con.php?accion=1&credito="+id);
		consultaTexto("mostrarDatos","solicitudContratoAperturaCredito_con.php?accion=1&credito="+id+"&s="+Math.random());
	}
	function mostrarDatos(datos){
		if(datos.indexOf("no encontro")<0){
			limpiarDatos();
			var obj = eval(convertirValoresJson(datos));			
			u.fecha.value 			= obj.principal.fechasolicitud;
			u.estado.innerHTML 		= obj.principal.estado;
			u.folioconvenio.value   = ((obj.principal.folioconvenio!="0")?obj.principal.folioconvenio:"");
			u.fechaautorizacion.innerHTML = obj.principal.fechaautorizacion;
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
				u.accion.value = "autorizar";
				u.mautorizado.disabled = false;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				u.td_tablas.innerHTML = tabla_2; 
				//u.colno.innerHTML = divNo;
				u.rdmoral[0].disabled = true;
				u.rdmoral[1].disabled = true;
			}
			if(u.estado.innerHTML == "AUTORIZADO"){
				u.accion.value ="activar";
				u.mautorizado.disabled = false;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				//u.col.innerHTML = divAc;
				u.td_tablas.innerHTML = tabla_3;
				u.rdmoral[0].disabled = true;
				u.rdmoral[1].disabled = true;
			}
			if(u.estado.innerHTML == "ACTIVADO" || u.estado.innerHTML == "BLOQUEADO"){
				//u.btn_Enviarp.style.visibility = "hidden";
				u.msolicitado.readOnly = false;
				u.msolicitado.disabled = false;	
				u.msolicitado.style.backgroundColor	= "";
				u.mautorizado.disabled = false;
				u.mautorizado.readOnly = false;
				u.mautorizado.style.backgroundColor	= "";
				u.diacredito.style.backgroundColor	= "";
				u.diacredito.disabled = false;
				u.diacredito.readOnly = false;
				//u.sucursalesead1_sel.disabled = true;
				u.rdmoral[0].disabled = true;
				u.rdmoral[1].disabled = true;
				if(u.estado.innerHTML == "ACTIVADO"){
					//u.btnImprimir.style.visibility = "visible";
					u.td_tablas.innerHTML = tabla_4;
				}
			}			
			u.todas.checked = ((obj.idsucursal == "0")?true:false);	
			tabla1.clear();
			tabla1.setJsonData(obj.banco);
			
			tabla2.clear();
			tabla2.setJsonData(obj.comerciales);
			
			tabla3.clear();
			tabla3.setJsonData(obj.persona);
			
			tabla4.clear();
			tabla4.setJsonData(obj.sucursal);
		}
	}	
	
	function limpiar(tipo){		
			u.fecha.value = ""; u.estado.innerHTML = "SOLICITUD"; u.folioconvenio.value = "";
			u.fechaautorizacion.innerHTML = ""; 
			u.rdmoral[0].disabled = false;
			u.rdmoral[1].disabled = false;
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
			u.msolicitado.readOnly = false;
			u.td_tablas.innerHTML = tabla_1;
			tabla1.clear(); tabla2.clear(); tabla3.clear(); tabla4.clear();
			if(tipo == 1){
				u.accion.value = "limpiar";
				document.form1.submit();
			}
	}
	function limpiarDatos(){
		u.fecha.value = ""; u.estado.innerHTML = "SOLICITUD"; u.folioconvenio.value = "";
		
		tabla1.clear();		
		u.fechaautorizacion.innerHTML = ""; 
		u.rdmoral[0].disabled = false;
		u.rdmoral[1].disabled = false;
		u.rdmoral[0].checked = false;
		u.rdmoral[1].checked = false; u.cliente.value = "";	u.giro.value= "";
		u.antiguedad.value = ""; u.representantelegal.value = ""; 
		u.actaconstitutiva.checked = false; u.nacta.value= ""; 	u.fechaescritura.value = "";	
		u.fechainscripcion.value = ""; 	u.identificacion.checked = false;
		u.nidentificacion.value = ""; u.hacienda.checked = false; 

		tabla2.clear();
		
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

		tabla3.clear();
		
		u.sabadorevision.checked = false; u.horariorevision.value="";
		u.arevision.value = "";	u.msolicitado.value=""; u.mautorizado.value= "";
		u.observaciones.value= ""; u.diacredito.value = ""; u.nick.value = "";
		u.rfc.value =""; u.nombre.value =""; u.paterno.value =""; u.materno.value ="";
		u.calle.value =""; 	u.numero.value =""; u.cp.value =""; u.colonia.value ="";
		u.poblacion.value =""; u.municipio.value = ""; 	u.pais.value ="";
		u.celular.value =""; u.telefono.value =""; u.email.value ="";

		tabla4.clear();
		
		u.estado2.value =""; u.accion.value =""; u.mautorizado.disabled = true;
		u.mautorizado.style.backgroundColor	= "#FFFF99";
		u.diacredito.style.backgroundColor	= "#FFFF99"; u.diacredito.disabled = true;
		u.td_tablas.innerHTML = tabla_1;
	
	   
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
		//u.btnAutorizacion.style.visibility = "hidden"; 
		u.accion.value = "autorizar";
		u.horariopago.value  = u.shpago.value +":"+ u.smpago.value;
		u.apago.value  = u.ahpago.value +":"+ u.ampago.value;
		u.horariorevision.value  = u.shrevision.value +":"+ u.smrevision.value;
		u.arevision.value  = u.ahrevision.value +":"+ u.amrevision.value;
		u.accion.value = "autorizar";
		consultaTexto("registroAutorizacion","solicitudContratoAperturaCredito_con.php?accion=10&folioconvenio="+((u.folioconvenio.value=="")?0:u.folioconvenio.value)
		+"&fechaautorizacion="+((u.fechaautorizacion.innerHTML=="")?"00/00/0000":u.fechaautorizacion.innerHTML)
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
			//u.btnNoAutorizacion.style.visibility = "hidden"; 
		}else{
			alerta3("Hubo un error al autorizar "+datos,"¡Atención!");
			//u.btnAutorizacion.style.visibility = "visible"; 
		}
	}
	
	function enviarNoAutorizacion(){
		confirmar('¿Esta seguro de NO Autorizar el Credito?','','noautorizarCredito()','');
	}
	function noautorizarCredito(){		
		u.accion.value = "noautorizar";
		//u.btnNoAutorizacion.style.visibility = "hidden";
		consultaTexto("registroNoAutorizado","solicitudContratoAperturaCredito_con.php?accion=12&folio="+u.folio.value);
	}
	
	function registroNoAutorizado(datos){
		if(datos.indexOf("ok")>-1){
			info("","La solicitud de credito NO fue Autorizada");
			u.estado.innerHTML = "NO AUTORIZADA";
			//u.btnAutorizacion.style.visibility = "hidden"; 			
		}else{
			alerta3("Hubo un error al no autorizar "+datos,"¡Atención!");
			//u.btnNoAutorizacion.style.visibility = "hidden";
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
		//u.btnActivar.style.visibility = "hidden";
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
			//u.btnImprimir.style.visibility = "visible";
			u.td_tablas.innerHTML = tabla_4;
			
		}else{
			alerta3("Hubo un error al activar "+datos,"¡Atención!");
			//u.btnActivar.style.visibility = "visible";
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
	function mostrarSolicitudCreditoPendientes(datos){
		if(v_autorizar==""){
			v_autorizar = 1;
			consultaTexto("mostrarSolicitudCreditoPendientes","solicitudContratoAperturaCredito_con.php?accion=4");
		}else{
			v_autorizar = "";
			abrirVentanaFija('buscarCredito.php?accion=1', 550, 450, 'ventana', 'Busqueda');			
		}
	}
	
	function mostrarSolicitudCreditoPendientesActivar(datos){
		if(v_activar==""){
			v_activar = 1;
			consultaTexto("mostrarSolicitudCreditoPendientesActivar","solicitudContratoAperturaCredito_con.php?accion=4");
		}else{
			v_activar = "";
			abrirVentanaFija('buscarCredito.php?accion=2', 550, 450, 'ventana', 'Busqueda');			
		}
	}
	
	function ValidaRfc(rfcStr) {
		var strCorrecta;
		strCorrecta = rfcStr;
		
		if (document.form1.rdmoral[0].checked){
			var valid = '^(([A-Z]|[a-z]|[&]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}else{
			var valid = '^(([A-Z]|[a-z]|[&]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
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
	
	function imprimirCredito(){
		if(u.estado.innerHTML=="AUTORIZADO" || u.estado.innerHTML=="ACTIVADO"){
			if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/fpdf/reportes/solicitudCredito.php?credito="+u.folio.value);
						
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
				window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/solicitudCredito.php?credito="+u.folio.value);
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/solicitudCredito.php?credito="+u.folio.value);
			}
		}else{
			alerta3("NO puede imprimir La solicitud de credito por que no tiene el estado AUTORIZADO o ACTIVADO","¡Atención!");
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
	
	/*function cancelarCredito(){
		//consultaTexto("creditoCancelado","");
	}
	
	function creditoCancelado(datos){
		if(datos.indexOf("ok")>-1){
			
		}
	}*/
	
	function guardarModificacion(){
		if(u.estado.innerHTML == "BLOQUEADO"){
			alerta3("No se puede modificar el credito por que actualmente se encuentra Bloqueado","¡Atención!");
			return false;
		}
	
		u.horariopago.value		  = u.shpago.value +":"+ u.smpago.value;
		u.apago.value			  = u.ahpago.value +":"+ u.ampago.value;
		u.horariorevision.value   = u.shrevision.value +":"+ u.smrevision.value;
		u.arevision.value  		  = u.ahrevision.value +":"+ u.amrevision.value;
		
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
		
		consultaTexto("semodifico","solicitudContratoAperturaCredito_con.php?accion=13&folio="+u.folio.value
		+"&semanapago="+((u.semanapago.checked==true)?1:0)
		+"&lunespago="+((u.lunespago.checked==true)?1:0)
		+"&martespago="+((u.martespago.checked==true)?1:0)
		+"&miercolespago="+((u.miercolespago.checked==true)?1:0)
		+"&juevespago="+((u.juevespago.checked==true)?1:0)
		+"&viernespago="+((u.viernespago.checked==true)?1:0)
		+"&sabadopago="+((u.sabadopago.checked==true)?1:0)
		+"&horariopago="+u.horariopago.value
		+"&apago="+u.apago.value
		+"&responsablepago="+u.responsablepago.value
		+"&celularpago="+u.celularpago.value
		+"&telefonopago="+u.telefonopago.value
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
		+"&h_pago="+pago
		+"&h_revision="+revision
		+"&idcliente="+u.cliente.value
		+"&val="+Math.random());
	}
	
	function semodifico(datos){
		if(datos.indexOf("ok")>-1){
			info("","Los datos han sido guardados correctamente");
		}else{
			alerta3("Hubo un error "+datos,"¡Atención!");
		}
	}
	
</script>
<link href="css/reseter.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
	<div class="canvas">
		<div class="content">
			<div class="det-guia">
				<div>
					<table width="254">
					<tbody>
					<tr>
					  <th width="81">Fecha:</th>
					  <th width="161" id="fecha" style="font-weight: bold;"><?=date('d/m/Y'); ?></th></tr>
					<tr>
					  <th width="81">Estado:</th>
					  <th width="161" id="estado" style="font-weight: bold;">&nbsp;</th>
					  </tr>
					<tr>
					  <th width="81">Folio:</th width="150"><th><span class="remi">
						<input name="folio" type="text" class="text" id="folio" style="width:120px;" onkeypress="if(event.keyCode==13){obtener(this.value)}"/>
					    <input name="button4" type="button" class="srch-btn" id="search3" title="Buscar" value=" " onclick="abrirVentanaFija('buscarCredito.php?accion=3', 600, 550, 'ventana', 'Busqueda')"/>
					  </span></th>
					</tr>
					<tr>
					  <th width="81">Folio Convenio:</th><th width="161"><span class="remi">					    
					    <input name="folioconvenio" type="text" class="text" id="folioconvenio" style="width:120px;" onkeypress="if(event.keyCode==13){obtenerConvenio(this.value)}"/>
						<input name="button5" type="button" class="srch-btn" id="search4" title="Buscar" value=" " onclick="abrirVentanaFija('../buscadores_generales/buscarConvenioGen.php?funcion=obtenerConvenio&amp;cestado=ACTIVADO', 550, 450, 'ventana', 'Busqueda')"/>
					  </span></th></tr>
					<tr>
					  <th width="81">F. Autorización</th>
					  <th width="161" id="fechaautorizacion" style="font-weight: bold;"></th></tr>
					</tbody>
					</table>
				</div>
			</div>
			 <div class="datos-cliente" style="font-size:11px;">
				<table width="526" height="182"  border="0" cellpadding="0" cellspacing="0" style="margin:0px 5px;">
				<tbody>
					<tr>
					<th width="99"><input name="rdmoral" type="radio" id="radio" onclick="validarPersona()" value="1" checked="checked" />
					  Persona Moral
					  
					  <label for="radio"></label></th>
					<th width="166"><input type="radio" name="rdmoral" id="radio2" value="0" onclick="validarPersona()" />
					  Persona Física</th>
					<th width="76">No. Cliente</th>
					<th width="185" align="right"><input name="cliente" type="text" class="text" id="cliente" style="width:120px;" onkeypress="obtenerCliente(event,this.value);"/>              
					<input type="button" id="search" value=" " class="srch-btn" title="Buscar" onclick="if(document.all.rdmoral[0].checked==true){
              abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&amp;tipo=moral', 550, 450, 'ventana', 'Busqueda')
               }else{                             abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda&amp;tipo=fisica', 550, 450, 'ventana', 'Busqueda')
               }
              "/></th>
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
					  <th><input name="estado2" type="text" class="text" id="estado2" style="width:120px; height:9px; font-size:9px; margin:0px;" readonly=""/></th>
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
					  <th>Giro</th>
					  <th><input name="giro" type="text" class="text" id="giro" style="width:120px; height:9px; font-size:9px; margin:0px; text-transform:uppercase" onkeypress="if(event.keyCode==13){document.getElementById('antiguedad').focus()}"/></th>
				  </tr>
					<tr>
					  <th>Antigüedad</th>
					  <th><input name="antiguedad" type="text" class="text" id="antiguedad" style="width:120px; height:9px; font-size:9px; margin:0px; text-transform:uppercase" onkeypress="if(event.keyCode==13){document.getElementById('representantelegal').focus()}"/></th>
					  <th>Repte. Legal</th>
					  <th><input name="representantelegal" type="text" class="text" id="representantelegal" style="width:120px; height:9px; font-size:9px; margin:0px; text-transform:uppercase"/></th>
				  </tr>
				</tbody>
				</table>
	      </div>
		</div>
		
		 <div class="doc-req">
		 	<div class="menu">
				<ul>
					<li class="active" id="pest0" onclick="seleccionarTab(0)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label0" class="active" >Documentaci&oacute;n Requerida</a></li>
					<li id="pest1" onclick="seleccionarTab(1)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label1">Rev./Pago Suc. Aplica Cr&eacute;dito</a></li>
					<li id="pest2" onclick="seleccionarTab(2)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label2">D&iacute;as revisi&oacute;n y pago</a></li>
					<li id="pest3" onclick="seleccionarTab(3)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label3">Referencias Bancarias</a></li>
					<li id="pest4" onclick="seleccionarTab(4)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label4">Referencias Comerciales</a></li>
				</ul>
			</div>
		   <div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; width:590px; height:235px" class="content-table" id="div0">
	        <table width="577" height="221" style="margin-top:20px; margin-left:10px;">
					<tbody>
					<tr>
					<th width="158"><input type="checkbox" name="actaconstitutiva" id="actaconstitutiva" onclick="validarDocumentacion(this.name);" />
					  <label for="checkbox">Acta Constitutiva</label></th>
					<th colspan="2">No. Acta</th>
					<th width="130"><input name="nacta" type="text" class="text" id="nacta" style="width:120px; height:12px; font-size:11px; margin:0px; text-transform:uppercase" onkeypress="return tabular(event,this)" readonly=""/></th>
					<th colspan="2">&nbsp;</th>
					</tr>
					<tr>
					  <th style="text-align:center;">Fecha Escritura:</th>
					  <th width="93">					    				  
				      <input name="fechaescritura" type="text" class="text" id="fechaescritura" style="width:80px; height:12px; font-size:11px; margin:0px; vertical-align:middle" onkeypress="validarFecha(event,this.value,this.name); if(event.keyCode==13){document.getElementById('fechainscripcion').focus()}" disabled="disabled"/></th>
					  <th width="31"><span class="remi">
					    <input name="button" type="button" class="cal-btn" id="button" title="Agregar" value="&nbsp;" onclick="if(document.all.fechaescritura.readOnly==false){displayCalendar(document.all.fechaescritura,'dd/mm/yyyy',this)}"/>
					  </span></th>
					  <th>Fecha Inscripci&oacute;n:</th>
					  <th width="92"><input name="fechainscripcion" type="text" class="text" id="fechainscripcion" style="width:80px; height:12px; font-size:11px; margin:0px;" onkeypress="validarFecha(event,this.value,this.name); if(event.keyCode==13){document.getElementById('identificacion').focus()}" disabled="disabled"/></th>
					  <th width="45"><span class="remi">
					    <input name="button2" type="button" class="cal-btn" id="button2" title="Agregar" value="&nbsp;" onclick="if(document.all.fechainscripcion.readOnly==false){displayCalendar(document.all.fechainscripcion,'dd/mm/yyyy',this)}"/>
					  </span></th>
					</tr>
					<tr>
					  <th><input type="checkbox" name="identificacion" id="identificacion" onclick="validarDocumentacion(this.name);" />
					  <label for="checkbox2">Identifaci&oacute;n Repr. Legal</label></th>
					  <th colspan="2">No. Identificación</th>
					  <th colspan="3"><input name="nidentificacion" type="text" class="text" id="nidentificacion" style="width:120px; height:12px; font-size:11px; margin:0px;text-transform:uppercase" readonly=""/></th>
					  </tr>
					<tr>
					  <th colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="18%"><input type="checkbox" name="hacienda" id="hacienda" onclick="validarDocumentacion(this.name);"/>
                            <label for="checkbox3">Alta Hacienda</label></td>
                          <td width="22%">F. Inicio de Operaciones:</td>
                          <td width="16%"><input name="fechainiciooperaciones" type="text" class="text" id="fechainiciooperaciones" style="width:80px; height:12px; font-size:11px; margin:0px; vertical-align:middle" disabled="disabled" onkeypress="validarFecha(event,this.value,this.name); if(event.keyCode==13){document.getElementById('comprobante').focus()}"/></td>
                          <td width="8%"><span class="remi">
                            <input name="button3" type="button" class="cal-btn" id="cal" title="Agregar" value=" " onclick="if(document.all.fechainiciooperaciones.readOnly==false){displayCalendar(document.all.fechainiciooperaciones,'dd/mm/yyyy',this)}"/>
                          </span></td>
                          <td width="7%">RFC: </td>
                          <td width="29%"><input name="rfc2" type="text" class="text" id="rfc2" style="width:120px; height:12px; font-size:11px; margin:0px;" readonly=""/></td>
                        </tr>
                      </table></th>
					  </tr>
					<tr>
					  <th><input type="checkbox" name="comprobante" id="comprobante" onclick="validarDocumentacion(this.name);"/>
						Comprobante Domicilio</th>
					  <th colspan="2"><input name="comprobanteluz" type="radio" id="comprobanteluz" value="radio3" disabled="disabled" />
                        <label for="radio3">Luz</label></th>
					  <th colspan="3"><input type="radio" name="comprobanteluz" id="comprobanteluz" value="radio3" disabled="disabled" />
Teléfono</th>
					  </tr>
					<tr>
					  <th colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="25%"><input type="checkbox" name="estadocuenta" id="estadocuenta" onclick="validarDocumentacion(this.name);"/>
                            <label for="checkbox5">Estado de Cuenta</label></td>
                          <td width="8%">Banco:</td>
                          <td width="25%"><input name="banco" type="text" class="text" id="banco" style="width:120px; height:12px; font-size:11px; margin:0px; text-transform:uppercase" readonly="" onkeypress="if(event.keyCode==13){document.getElementById('cuenta').focus()}"/></td>
                          <td width="8%">Cuenta:</td>
                          <td width="27%"><input name="cuenta" type="text" class="text" id="cuenta" style="width:120px; height:12px; font-size:11px; margin:0px; text-transform:uppercase" readonly=""/></td>
                          <td width="7%">&nbsp;</td>
                        </tr>
                      </table></th>
					  </tr>
					<tr>
					  <th><label for="checkbox5">
					    <input type="checkbox" name="solicitud" id="solicitud" />
Solicitud</label></th>
					  <th colspan="2">&nbsp;</th>
					  <th colspan="3">&nbsp;</th>
					  </tr>
					<tr>
					  <th>&nbsp;</th>
					  <th colspan="2">&nbsp;</th>
					  <th colspan="3">&nbsp;</th>
					  </tr>
					<tr>
					  <th><label for="checkbox6"></label></th>
					  <th colspan="2">&nbsp;</th>
					  <th colspan="3">&nbsp;</th>
					  </tr>
					<tr>
					  <th>&nbsp;</th>
					  <th colspan="2"><label for="radio3"></label></th>
					  <th colspan="3">&nbsp;</th>
					  </tr>
					<tr>
					  <th>&nbsp;</th>
					  <th colspan="2">&nbsp;</th>
					  <th colspan="3"><span class="Tablas">
					    <input type="hidden" name="rem_direcciones" /> <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />
					  </span></th>
					  </tr>
					<tr>
					  <th>&nbsp;</th>
					  <th colspan="2">&nbsp;</th>
					  <th colspan="3"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />
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
                        <input name="notieneconvenio" type="hidden" id="notieneconvenio" /></th>
					  </tr>
					</tbody>
			 </table>
		   </div>
		   <div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; visibility:hidden; width:590px; height:235px" class="content-table" id="div1">
				<table width="577" height="141" style="margin-top:20px; margin-left:10px;">
				<tbody>
					<tr>
						<th width="299" height="22" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td colspan="2">Persona autorizada(s) revisi&oacute;n:<input name="fechapersona" type="hidden" id="fechapersona" /></td>
						  </tr>
						  <tr>
							<td width="62%"><input name="persona" id="persona" type="text" class="text" style="width:150px; height:12px; font-size:11px; margin:0px; text-transform:uppercase" onKeyUp="return borrarIndex(event,this.name)" onKeyPress="if(event.keyCode==13){agregarPersona();}"/></td>
							<td width="38%">&nbsp;</td>
						  </tr>
						</table></th>
						<th width="266"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td><input type="checkbox" name="todas" style="width:13px" value="1" id="todas" <? if($todas==1){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="agregarTodasSucursales();">Todas</td>
							<td><select class="Tablas" name="sucursalesead1_sel" style="width:150px" onChange="agregarSucursal()")>
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
							<td width="28%"><input name="fechasucursal" type="hidden" id="fechasucursal" /></td>
							<td width="72%">&nbsp;</td>
						  </tr>
						</table></th>
					</tr>
					<tr>
						<th colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#282828">
						  <tr>
							<td><div style="background:#282828">
								<table width="290" border="0" cellpadding="0" cellspacing="0" id="tablaPersonas" name="tablaPersonas" >
							</table></div></td>
							<td><div style="background:#282828"><table id="detalleSucursal" width="100%" border="0" cellpadding="0" cellspacing="0">                 
							</table>
							</div></td>
						  </tr>
						  <tr>
							<th>&nbsp;</th>
						  </tr>							
						</table></th>
					</tr>					
				</tbody>
		  </table>
		   </div>

		  <div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; visibility:hidden; width:590px; height:235px" class="content-table" id="div2">
			<table width="577" height="141" style="margin-top:20px; margin-left:10px;">
				  <tr>
					<td><table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						<td colspan="2" class="FondoTabla">Dias de Pago </td>
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
						<td colspan="15"><div class="etiqueta" style="width:170px;">Personas Responsable de Pagos: </div>
						<input name="responsablepago" type="text" class="text" id="responsablepago" style="width:350px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$responsablepago ?>" onkeypress="return tabular(event,this)" />
						</td>
					  </tr>
					  <tr>
						<td colspan="15"><div class="etiqueta" style="width:55px;">Celular:</div>
						<input name="celularpago" type="text" class="text" id="celularpago" style="width:120px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$celularpago ?>" onkeypress="return tabular(event,this)" />
						<div class="etiqueta" style="width:57px;">Tel&eacute;fono:</div>
						<input name="telefonopago" type="text" class="text" id="telefonopago" style="width:120px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$telefonopago ?>" onkeypress="return tabular(event,this)" />
						<div class="etiqueta" style="width:30px;">Fax:</div>
						<input name="faxpago" type="text" class="text" id="faxpago" style="width:130px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$faxpago ?>" onkeypress="return tabular(event,this)" />
						</td>
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
							  <td colspan="2" class="FondoTabla">Dias de Revision </td>
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
		   <div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; visibility:hidden; width:590px; height:235px" class="content-table" id="div3">
		   		<table width="577" height="141" style="margin-top:20px; margin-left:10px;">
				  <tr>
					<td><table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						<td width="53">Banco:</td>
						<td width="162"><span class="Tablas">
						  <input name="rbanco" type="text" class="text" id="rbanco" style="width:80px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$rbanco ?>" onkeypress="return tabular(event,this)" />
						</span></td>
						<td width="106">Sucursal:</td>
						<td width="147"><span class="Tablas">
						  <input name="rsucursal" type="text" class="text" id="rsucursal" style="width:80px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$rsucursal ?>" onkeypress="return tabular(event,this)"/>
						</span></td>
						<td width="92">Cuenta:</td>
						<td width="147"><span class="Tablas">
						  <input name="rcuenta" type="text" class="text" id="rcuenta" style="width:80px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$rcuenta ?>" onkeypress="if(event.keyCode==13){document.getElementById('rtelefono').select();}else{return solonumeros(event)}" />
						</span></td>
						<td width="99">Tel&eacute;fono:</td>
						<td width="134"><span class="Tablas">
						  <input name="rtelefono" type="text" class="text" id="rtelefono" onkeypress="if(event.keyCode==13){agregarBanco();}" style="width:80px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$rtelefono ?>" />
						  <input name="rfecha" type="hidden" id="rfecha" />
						</span></td>
						<td width="35"><input name="agregar_banco" type="button" class="button" id="agregar_banco"  onclick="agregarBanco()" value="Agregar" /></td>
					  </tr>
					  <tr>
						<td colspan="9"></td>
					  </tr>
					  <tr>
						<td colspan="8"><div style="background:#282828; width:500px; height:120px "><table width="100%" cellpadding="0" border="0" cellspacing="0" name="tablaBanco" id="tablaBanco" ></table></div></td>
						<td><input name="eliminar_banco" type="button" class="button" id="eliminar_banco"  onclick="borrarBanco()" value="Eliminar" /></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
				  </tr>
				</table>
		   </div>
		   <div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; visibility:hidden; width:590px; height:235px" class="content-table" id="div4">
		   		<table width="577" height="141" style="margin-top:20px; margin-left:10px;">
				  <tr>
					<td><table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						<td width="284">Empresa:</td>
						<td width="285"><span class="Tablas">
						  <input name="cempresa" type="text" class="text" id="cempresa" style="width:80px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$cempresa?>" onkeypress="return tabular(event,this)" />
						</span></td>
						<td width="571">&nbsp;</td>
						<td width="571">&nbsp;</td>
						<td width="571">Contacto:</td>
						<td width="571"><span class="Tablas">
						  <input name="ccontacto" type="text" class="text" id="ccontacto" style="width:160px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$ccontacto ?>" onkeypress="return tabular(event,this)" />
						</span></td>
						<td width="284">Tel&eacute;fono:</td>
						<td width="141"><span class="Tablas">
						  <input name="ctelefono" type="text" class="text" id="ctelefono" style="width:80px;height:12px; font-size:11px; margin:0px; text-transform:uppercase" value="<?=$ctelefono ?>" onkeypress="if(event.keyCode==13){agregarComercial();}" />
						  <input name="cfecha" type="hidden" id="cfecha" />
						</span></td>
						<td><input name="agregar_comercial" type="button" class="button" id="agregar_comercial"  onclick="agregarComercial()" value="Agregar" /></td>
					  </tr>
					  <tr>
						<td colspan="8"><div style="background:#282828; width:500px; height:120px "><table width="515" cellpadding="0" cellspacing="0" border="0" id="tablaComer" name="tablaComer" >
							</table></div></td>
						<td width="142"><input name="eliminar_comercial" type="button" class="button" id="eliminar_comercial"  onclick="borrarComercial()" value="Eliminar" /></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
				  </tr>
				</table>
		   </div>
		 </div>
		 <div class="extra-data2">
			 <table width="187" style="margin:10px;">
				 <tbody>
				 <tr>
				 <th colspan="2">Monto Solicitado
				 </th>
				 </tr>
				 <tr>
				   <th colspan="2"><input name="msolicitado" type="text" class="text" id="msolicitado" style="width:180px;" onkeypress="if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/g,'')); document.all.observaciones.focus();}else{return tiposMoneda(event,this.value);}"  maxlength="15"/></th>
				 </tr>
				 <tr>
				   <th colspan="2">Monto Autorizado</th>
				 </tr>
				 <tr>
				   <th height="30" colspan="2" style="font-size:13px; font-weight:bold;"><input name="mautorizado" type="text" class="text" id="mautorizado" style="width:180px;" onkeypress="if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/g,'')); document.all.diacredito.focus();}else{return tiposMoneda(event,this.value);}" maxlength="15" disabled="disabled"/></th>
				 </tr>
				 <tr>
				   <th colspan="2">Días Crédito</th>
				 </tr>
				 <tr>
				   <th height="28" colspan="2" style="font-size:13px; font-weight:bold;"><input name="diacredito" type="text" class="text" id="diacredito" style="width:180px;" onkeypress="return solonumeros(event)" onkeydown="return tabular(event,this)" disabled="disabled" maxlength="10"/></th>
				 </tr>
				 <tr>
				   <th colspan="2">Observaciones</th>
				 </tr>
				 <tr>
				   <th colspan="2"><textarea name="observaciones" class="text" id="observaciones" style="width:180px; height:90px; text-transform:uppercase"/>
				   </textarea></th>
				 </tr>
				 <tr>
				   <th width="107" id="col">
					 
				   </th>
				   <th width="69">&nbsp;</th>
				 </tr>
				 </tbody>
			 </table>
			
	  </div>
      <div class="extra-data3">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="53%">&nbsp;</td>
				<td width="47%" id="td_tablas"><table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="80%" style="text-align:right" ><input name="button6" type="button" class="button" id="btn_Enviarp"  onclick="validar();" value="Enviar Autorizaci&oacute;n" /></td>
				<td width="20%" style="text-align:right"><input name="button6" type="button" class="button" id="cal5" onclick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar(1);', '')" value="Nuevo"/></td>
			  </tr>
			</table></td>				
				</tr>				
				</table>
      </div>		
	</div>	
</form>
</body>
</html>
