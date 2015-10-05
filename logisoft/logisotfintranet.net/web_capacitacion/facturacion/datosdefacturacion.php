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


<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />


<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />


<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />


<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />


<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />


<script language="javascript" src="../javascript/ajax.js"></script>


<script language="javascript" src="../javascript/funciones_tablas.js"></script>





<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">


<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">


<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">


<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>


<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>


<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>


</head>


<script>


	//botones para las acciones


	var botonesnuevo = "<table><tr><td><div class=\"ebtn_guardar\" onclick=\"confirmar('¿Desea guardar la factura?','¡Atencion!','guardarFacturar()','')\"/></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio()\"/></td></tr></table>";


	


	var botonescargar = "<table><tr><td><div class=\"ebtn_Cancelar_Factura\" onclick=\"if(document.all.estadofactura.innerHTML=='GUARDADO'){confirmar('¿Desea Cancelar la factura?','¡Atencion!','cancelarFactura()','')}else{alerta('La factura no se ha guardado','¡Atencion!','folio')}\"/></td><td><div class=\"ebtn_imprimir\"/></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio()\"/></td></tr></table>";


	


	var botonescancelado = "<table><tr><td><div class=\"ebtn_Sustituir_Factura\" onclick=\"if(document.all.estadofactura.innerHTML=='GUARDADO'){confirmar('¿Desea Cancelar la factura?','¡Atencion!','cancelarFactura()','')}else{alerta('La factura no se ha guardado','¡Atencion!','folio')}\"/></td><td><div class=\"ebtn_imprimir\"/></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio()\"/></td></tr></table>";





	//declaraciones para grids


	var valt1 			= agregar_una_tabla("tablaguias", "idf_", 10, "Balance2└Balance","");


	var vartabla 		= "";


	


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


		valor2 = "$ "+numcredvar(valor1.toLocaleString());


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





	//funciones para limpiar


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


		}


	}


	function limpiarGuias(){


		u = document.all;


		if(vartabla==""){


			vartabla = u.contenidoregistro.innerHTML;


		}


		u.contenidoregistro.innerHTML = vartabla;


		reiniciar_indice(valt1);


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


		u.sexcedente.value 		= "";


		u.ssubtotal.value 		= "";


		u.siva.value 			= "";


		u.sivar.value 			= "";


		u.smonto.value 			= "";


	}


	function limpiarTodo(){


		document.all.estadofactura.innerHTML = "";


		limpiarCliente();


		limpiarGuias();


		limpiarSobre();


		limpiarOtros();


		document.all.bonotesAccion.innerHTML = botonesnuevo;


	}


	function pedirFolio(){


		consulta("mostrarFolio", "Facturacion_consulta.php?accion=5&ran="+Math.random());


	}


	


	//funciones para ajax


	function mostrarFolio(datos){


		maximo = datos.getElementsByTagName('maximo').item(0).firstChild.data;


		document.all.folio.value = maximo;


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


			


			document.all.celdacalle.innerHTML = '<input name="calle" type="text" class="Tablas" id="calle" style="width:250px" readonly=""/>';			


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


		consulta("mostrarGuias", "Facturacion_consulta.php?accion=2&idcliente="+valor+"&valrandom="+Math.random());


	}


	function mostrarGuias(datos){


		var u = document.all;


		var encon = datos.getElementsByTagName('encontrados').item(0).firstChild.data;


		


		if(encon>0){


			var tflete 			= 0;


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


			


			for(m=0; m<encon; m++){


				noguia		= datos.getElementsByTagName('id').item(m).firstChild.data;


				tipoguia	= datos.getElementsByTagName('tipoguia').item(m).firstChild.data;


				fecha		= datos.getElementsByTagName('fecha').item(m).firstChild.data;


				flete		= datos.getElementsByTagName('tflete').item(m).firstChild.data;


				excedente	= datos.getElementsByTagName('texcedente').item(m).firstChild.data;


				ead			= datos.getElementsByTagName('tcostoead').item(m).firstChild.data;


				recoleccion	= datos.getElementsByTagName('trecoleccion').item(m).firstChild.data;


				seguro		= datos.getElementsByTagName('tseguro').item(m).firstChild.data;


				comb		= datos.getElementsByTagName('tcombustible').item(m).firstChild.data;


				otros		= datos.getElementsByTagName('totros').item(m).firstChild.data;


				subtotal	= datos.getElementsByTagName('subtotal').item(m).firstChild.data;


				iva			= datos.getElementsByTagName('tiva').item(m).firstChild.data;


				ivaret		= datos.getElementsByTagName('ivaretenido').item(m).firstChild.data;


				total		= datos.getElementsByTagName('total').item(m).firstChild.data;


				


				if(tipoguia=="NORMAL"){


					guiasn += 1;


				}else{


					guiase += 1;


				}


				


				tflete 			+= parseFloat(flete);


				texcedente 		+= parseFloat(excedente);


				tead 			+= parseFloat(ead);


				trecoleccion	+= parseFloat(recoleccion);


				tseguro 		+= parseFloat(seguro);


				tcomb 			+= parseFloat(comb);


				totros 			+= parseFloat(otros);


				tsubtotal		+= parseFloat(subtotal);


				tiva 			+= parseFloat(iva);


				tivaret 		+= parseFloat(ivaret);


				ttotal 			+= parseFloat(total);





				insertar_en_tabla(valt1,"<input type='checkbox' class='formato_chk' checked='true' name='checar_xxNOFILAxx' value='"+noguia


					+"' onclick='calcularSeleccion(\"xxNOFILAxx\",this.checked);'>└"+paracelda(noguia,"62","left")+"└"+


					paracelda(tipoguia,"46","left","tipoguia_")+"└"+paracelda(fecha,"36","left")+"└"+


					paracelda("$ "+numcredvar(flete),"40","right","flete_")+"└"+paracelda("$ "+numcredvar(excedente),"43","right","excedente_")+"└"+


					paracelda("$ "+numcredvar(ead),"35","right","ead_")+"└"+paracelda("$ "+numcredvar(recoleccion),"46","right","recoleccion_")+"└"+


					paracelda("$ "+numcredvar(seguro),"42","right","seguro_")+"└"+paracelda("$ "+numcredvar(comb),"36","right","combustible_")+"└"+


					paracelda("$ "+numcredvar(otros),"34","right","otros_")+"└"+paracelda("$ "+numcredvar(subtotal),"40","right","subtotal_")+"└"+


					paracelda("$ "+numcredvar(iva),"30","right","iva_")+"└"+paracelda("$ "+numcredvar(ivaret),"35","right","ivaret_")+"└"+


					paracelda("$ "+numcredvar(total),"35","right","total_"));


			}


			


			u.tflete.value			= convertirMoneda(tflete);


			u.texcedente.value 		= convertirMoneda(texcedente);


			u.tead.value 			= convertirMoneda(tead);


			u.trecoleccion.value	= convertirMoneda(trecoleccion);


			u.tseguro.value 		= convertirMoneda(tseguro);


			u.tcombustible.value 	= convertirMoneda(tcomb);


			u.totros.value 			= convertirMoneda(totros);


			u.tsubtotal.value		= convertirMoneda(tsubtotal);


			u.tiva.value 			= convertirMoneda(tiva);


			u.tivar.value 			= convertirMoneda(tivaret);


			u.ttotal.value 			= convertirMoneda(ttotal);


			u.guiase.value			= guiase;


			u.guiasn.value			= guiasn;


		}else{


			alerta("El Cliente no tiene guias para facturar","¡Atencion!","idcliente")


		}


		pedirFolio();


	}


	


	function guardarFacturar(){


		u = document.all;


		


		cliente 					= u.idcliente.value;


		nombrecliente 				= u.nombre.value;


		apellidopaternocliente 		= u.paterno.value;


		apellidomaternocliente 		= u.materno.value;


		rfc							= u.rfc.value;


		calle 						= u.calle.value;


		numero 						= u.numero.value;


		codigopostal 				= u.cp.value;


		colonia 					= u.colonia.value;


		crucecalles 				= u.ccalles.value;


		poblacion 					= u.poblacion.value;


		municipio 					= u.municipio.value;


		estado 						= u.estado.value;


		pais 						= u.pais.value;


		telefono 					= u.telefono.value;


		fax 						= u.fax.value;


		guiasempresa 				= u.guiase.value;


		guiasnormales 				= u.guiasn.value;


		flete 						= desconvertirMoneda(u.tflete.value);


		excedente 					= desconvertirMoneda(u.texcedente.value);


		ead 						= desconvertirMoneda(u.tead.value);


		recoleccion 				= desconvertirMoneda(u.trecoleccion.value);


		seguro 						= desconvertirMoneda(u.tseguro.value);


		combustible 				= desconvertirMoneda(u.tcombustible.value);


		otros 						= desconvertirMoneda(u.totros.value);


		subtotal 					= desconvertirMoneda(u.tsubtotal.value);


		iva 						= desconvertirMoneda(u.tiva.value);


		ivaretenido 				= desconvertirMoneda(u.tivar.value);


		total 						= desconvertirMoneda(u.ttotal.value);


		sobseguro 					= desconvertirMoneda(u.sseguro.value);


		sobexcedente 				= desconvertirMoneda(u.sexcedente.value);


		sobsubtotal 				= desconvertirMoneda(u.ssubtotal.value);


		sobiva 						= desconvertirMoneda(u.siva.value);


		sobivaretenido 				= desconvertirMoneda(u.sivar.value);


		sobmontoafacturar 			= desconvertirMoneda(u.smonto.value);


		otroscantidad 				= (u.cantidad.value=="")?"0":u.cantidad.value;


		otrosdescripcion 			= u.descripcion.value;


		otrosimporte 				= desconvertirMoneda(u.importe.value);


		otrossubtotal 				= desconvertirMoneda(u.subtotalotros.value);


		otrosiva 					= desconvertirMoneda(u.ivaotros.value);


		otrosivaretenido 			= desconvertirMoneda(u.ivarotros.value);


		otrosmontofacturar 			= desconvertirMoneda(u.montootros.value);


		


		var folio 	= "";


		var cantreg	= u.cantidadregistros.value;


		


		//u.descripcion.value = u.contenidoregistro.innerHTML;


		


		for(var i=1;i<=cantreg;i++){


			if(document.all["checar_"+i].checked){


				folio += (folio!="")?",":"";


				folio += "'" + u["checar_"+i].value + "'";


			}


		}


		


		consulta("respuestaGuardar","Facturacion_consulta.php?accion=3&nombrecliente=" + nombrecliente + 


		"&cliente=" + cliente +


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


		"&guiasempresa=" + guiasempresa + 


		"&guiasnormales=" + guiasnormales + 


		"&flete=" + flete + 


		"&excedente=" + excedente + 


		"&ead=" + ead + 


		"&recoleccion=" + recoleccion + 


		"&seguro=" + seguro + 


		"&combustible=" + combustible + 


		"&otros=" + otros + 


		"&subtotal=" + subtotal + 


		"&iva=" + iva + 


		"&ivaretenido=" + ivaretenido + 


		"&total=" + total + 


		"&sobseguro=" + sobseguro + 


		"&sobexcedente=" + sobexcedente + 


		"&sobsubtotal=" + sobsubtotal + 


		"&sobiva=" + sobiva + 


		"&sobivaretenido=" + sobivaretenido + 


		"&sobmontoafacturar=" + sobmontoafacturar + 


		"&otroscantidad=" + otroscantidad + 


		"&otrosdescripcion=" + otrosdescripcion + 


		"&otrosimporte=" + otrosimporte + 


		"&otrossubtotal=" +otrossubtotal + 


		"&otrosiva=" + otrosiva + 


		"&otrosivaretenido=" + otrosivaretenido + 


		"&otrosmontofacturar=" + otrosmontofacturar+


		"&foliosguias=" + folio + "&ranms="+Math.random());


	}


	function respuestaGuardar(datos){


		guardado = datos.getElementsByTagName('guardado').item(0).firstChild.data;


		if(guardado==1){


			foliofactura = datos.getElementsByTagName('foliofactura').item(0).firstChild.data;


			document.all.folio.value = foliofactura;


			alerta("Se ha guardado la factura","¡Atencion!","folio");


			u.estadofactura.innerHTML = "GUARDADO";


		}else{


			consulta = datos.getElementsByTagName('consulta').item(0).firstChild.data;


			alerta("Error al guardar "+consulta,"¡Atencion!","folio");


		}


	}


	


	function cancelarFactura(){


		u = document.all;


		consulta("respuestaCancelar","Facturacion_consulta.php?accion=4&foliofactura="+u.folio.value);


	}


	function respuestaCancelar(datos){


		cancelada = datos.getElementsByTagName('cancelada').item(0).firstChild.data;


		if(cancelada==1){


			alerta("Se ha Cancelado la factura","¡Atencion!","folio");


			u.estadofactura.innerHTML = "CANCELADO";


		}else{


			consulta = datos.getElementsByTagName('consulta').item(0).firstChild.data;


			alerta("Error al cancelar "+consulta,"¡Atencion!","folio");


		}


		document.all.bonotesAccion.innerHTML = botonescancelado;


	}


	


	function cargarFactura(folio){


		u = document.all;


		consulta("mostrarFactura","Facturacion_consulta.php?accion=6&folio="+folio);


	}


	function mostrarFactura(datos){


		var u = document.all;


		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;


		


		limpiarTodo();


		if(encon>0){


			var folio				= datos.getElementsByTagName('folio').item(0).firstChild.data;


			var facturaestado 		= datos.getElementsByTagName('facturaestado').item(0).firstChild.data;


			var cliente 			= datos.getElementsByTagName('cliente').item(0).firstChild.data;


			var nombrecliente 		= datos.getElementsByTagName('nombrecliente').item(0).firstChild.data;


			var apepat 				= datos.getElementsByTagName('apepat').item(0).firstChild.data;


			var apemat 				= datos.getElementsByTagName('apemat').item(0).firstChild.data;


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


			var excedente 			= datos.getElementsByTagName('excedente').item(0).firstChild.data;


			var ead 				= datos.getElementsByTagName('ead').item(0).firstChild.data;


			var recoleccion 		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;


			var seguro 				= datos.getElementsByTagName('seguro').item(0).firstChild.data;


			


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


			


			u.folio.value 			= folio;


			u.estadofactura.innerHTML	= facturaestado;


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


			u.guiase.value 			= guiasempresa;


			u.guiasn.value 			= guiasnormales;


			u.tflete.value 			= convertirMoneda(flete);


			u.texcedente.value 		= convertirMoneda(excedente);


			u.tead.value 			= convertirMoneda(ead);


			u.trecoleccion.value	= convertirMoneda(recoleccion);


			u.tseguro.value 		= convertirMoneda(seguro);


			u.tcombustible.value	= convertirMoneda(combustible);


			u.totros.value 			= convertirMoneda(otros);


			u.tsubtotal.value 		= convertirMoneda(subtotal);


			u.tiva.value 			= convertirMoneda(iva);


			u.tivar.value 			= convertirMoneda(ivaretenido);


			u.ttotal.value 			= convertirMoneda(total);


			u.sseguro.value 		= convertirMoneda(sobseguro);


			u.sexcedente.value 		= convertirMoneda(sobexcedente);


			u.ssubtotal.value 		= convertirMoneda(sobsubtotal);


			u.siva.value 			= convertirMoneda(sobiva);


			u.sivar.value 			= convertirMoneda(sobivaretenido);


			u.smonto.value 			= convertirMoneda(sobmontoafacturar);


			u.cantidad.value 		= otroscantidad;


			u.descripcion.value		= otrosdescripcion;


			u.importe.value 		= convertirMoneda(otrosimporte);


			u.subtotalotros.value 	= convertirMoneda(otrossubtotal);


			u.ivaotros.value 		= convertirMoneda(otrosiva);


			u.ivarotros.value 		= convertirMoneda(otrosivaretenido);


			u.montootros.value 		= convertirMoneda(otrosmontofacturar);


			


			var guiasencontradas	= datos.getElementsByTagName('guiasencontradas').item(0).firstChild.data;


			


			if(guiasencontradas>0){


				var tflete 			= 0;


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


				


				for(m=0; m<guiasencontradas; m++){


					noguia		= datos.getElementsByTagName('fid').item(m).firstChild.data;


					tipoguia	= datos.getElementsByTagName('ftipoguia').item(m).firstChild.data;


					fecha		= datos.getElementsByTagName('ffecha').item(m).firstChild.data;


					flete		= datos.getElementsByTagName('ftflete').item(m).firstChild.data;


					excedente	= datos.getElementsByTagName('ftexcedente').item(m).firstChild.data;


					ead			= datos.getElementsByTagName('ftcostoead').item(m).firstChild.data;


					recoleccion	= datos.getElementsByTagName('ftrecoleccion').item(m).firstChild.data;


					seguro		= datos.getElementsByTagName('ftseguro').item(m).firstChild.data;


					comb		= datos.getElementsByTagName('ftcombustible').item(m).firstChild.data;


					otros		= datos.getElementsByTagName('ftotros').item(m).firstChild.data;


					subtotal	= datos.getElementsByTagName('fsubtotal').item(m).firstChild.data;


					iva			= datos.getElementsByTagName('ftiva').item(m).firstChild.data;


					ivaret		= datos.getElementsByTagName('fivaretenido').item(m).firstChild.data;


					total		= datos.getElementsByTagName('ftotal').item(m).firstChild.data;


					


					if(tipoguia=="NORMAL"){


						guiasn += 1;


					}else{


						guiase += 1;


					}


					


					tflete 			+= parseFloat(flete);


					texcedente 		+= parseFloat(excedente);


					tead 			+= parseFloat(ead);


					trecoleccion	+= parseFloat(recoleccion);


					tseguro 		+= parseFloat(seguro);


					tcomb 			+= parseFloat(comb);


					totros 			+= parseFloat(otros);


					tsubtotal		+= parseFloat(subtotal);


					tiva 			+= parseFloat(iva);


					tivaret 		+= parseFloat(ivaret);


					ttotal 			+= parseFloat(total);


	


					insertar_en_tabla(valt1,"<input type='checkbox' class='formato_chk' checked='true' style='visibility:hidden'"


					+"' onclick='calcularSeleccion(\"xxNOFILAxx\",this.checked);'>└"+paracelda(noguia,"62","left")+"└"+


						paracelda(tipoguia,"46","left","tipoguia_")+"└"+paracelda(fecha,"36","left")+"└"+


						paracelda("$ "+numcredvar(flete),"40","right","flete_")+"└"+paracelda("$ "+numcredvar(excedente),"43","right","excedente_")+"└"+


						paracelda("$ "+numcredvar(ead),"35","right","ead_")+"└"+paracelda("$ "+numcredvar(recoleccion),"46","right","recoleccion_")+"└"+


						paracelda("$ "+numcredvar(seguro),"42","right","seguro_")+"└"+paracelda("$ "+numcredvar(comb),"36","right","combustible_")+"└"+


						paracelda("$ "+numcredvar(otros),"34","right","otros_")+"└"+paracelda("$ "+numcredvar(subtotal),"40","right","subtotal_")+"└"+


						paracelda("$ "+numcredvar(iva),"30","right","iva_")+"└"+paracelda("$ "+numcredvar(ivaret),"35","right","ivaret_")+"└"+


						paracelda("$ "+numcredvar(total),"35","right","total_"));


				}


				


				u.tflete.value			= convertirMoneda(tflete);


				u.texcedente.value 		= convertirMoneda(texcedente);


				u.tead.value 			= convertirMoneda(tead);


				u.trecoleccion.value	= convertirMoneda(trecoleccion);


				u.tseguro.value 		= convertirMoneda(tseguro);


				u.tcombustible.value 	= convertirMoneda(tcomb);


				u.totros.value 			= convertirMoneda(totros);


				u.tsubtotal.value		= convertirMoneda(tsubtotal);


				u.tiva.value 			= convertirMoneda(tiva);


				u.tivar.value 			= convertirMoneda(tivaret);


				u.ttotal.value 			= convertirMoneda(ttotal);


				u.guiase.value			= guiase;


				u.guiasn.value			= guiasn;


			}


			document.all.bonotesAccion.innerHTML = botonescargar;


		}else{


			alerta("No se encontro la factura","¡Atencion!","folio");


			


			document.all.bonotesAccion.innerHTML = botonesnuevo;


		}


	}


	


	//calculo de totales


	function seleccionarGuias(valor){


		var cantreg	= u.cantidadregistros.value;


		


		for(var i=1;i<=cantreg;i++){


			if(document.all["checar_"+i].checked != valor){


				document.all["checar_"+i].checked = valor;


				calcularSeleccion(i,valor);


			}


		}


	}


	function calcularSeleccion(numero,valor){


		u = document.all;


		


		u.tflete.value			= parseFloat(desconvertirMoneda(u.tflete.value)) 


								+ parseFloat((valor)?desconvertirMoneda(u["flete_"+numero].value):(desconvertirMoneda(u["flete_"+numero].value)*-1));


		u.texcedente.value 		= parseFloat(desconvertirMoneda(u.texcedente.value))


								+ parseFloat((valor)?desconvertirMoneda(u["excedente_"+numero].value):(desconvertirMoneda(u["excedente_"+numero].value)*-1));


		u.tead.value 			= parseFloat(desconvertirMoneda(u.tead.value))


								+ parseFloat((valor)?desconvertirMoneda(u["ead_"+numero].value):(desconvertirMoneda(u["ead_"+numero].value)*-1));


		u.trecoleccion.value	= parseFloat(desconvertirMoneda(u.trecoleccion.value))


								+ parseFloat((valor)?desconvertirMoneda(u["recoleccion_"+numero].value):(desconvertirMoneda(u["recoleccion_"+numero].value)*-1));


		u.tseguro.value 		= parseFloat(desconvertirMoneda(u.tseguro.value))


								+ parseFloat((valor)?desconvertirMoneda(u["seguro_"+numero].value):(desconvertirMoneda(u["seguro_"+numero].value)*-1));


		u.tcombustible.value 	= parseFloat(desconvertirMoneda(u.tcombustible.value))


								+ parseFloat((valor)?desconvertirMoneda(u["combustible_"+numero].value):(desconvertirMoneda(u["combustible_"+numero].value)*-1));


		u.totros.value 			= parseFloat(desconvertirMoneda(u.totros.value))


								+ parseFloat((valor)?desconvertirMoneda(u["otros_"+numero].value):(desconvertirMoneda(u["otros_"+numero].value)*-1));


		u.tsubtotal.value		= parseFloat(desconvertirMoneda(u.tsubtotal.value))


								+ parseFloat((valor)?desconvertirMoneda(u["subtotal_"+numero].value):(desconvertirMoneda(u["subtotal_"+numero].value)*-1));


		u.tiva.value 			= parseFloat(desconvertirMoneda(u.tiva.value))


								+ parseFloat((valor)?desconvertirMoneda(u["iva_"+numero].value):(desconvertirMoneda(u["iva_"+numero].value)*-1));


		u.tivar.value 			= parseFloat(desconvertirMoneda(u.tivar.value))


								+ parseFloat((valor)?desconvertirMoneda(u["ivaret_"+numero].value):(desconvertirMoneda(u["ivaret_"+numero].value)*-1));


		u.ttotal.value 			= parseFloat(desconvertirMoneda(u.ttotal.value))


								+ parseFloat((valor)?desconvertirMoneda(u["total_"+numero].value):(desconvertirMoneda(u["total_"+numero].value)*-1));


		if(u["tipoguia_"+numero].value=="EMPRESARIAL" && valor==true)


			u.guiase.value		= parseFloat(u.guiase.value)+1;


		else if(u["tipoguia_"+numero].value=="EMPRESARIAL" && valor==false)


			u.guiase.value		= parseFloat(u.guiase.value)-1;


			


		if(u["tipoguia_"+numero].value=="NORMAL" && valor==true)


			u.guiasn.value		= parseFloat(u.guiasn.value)+1;


		else if(u["tipoguia_"+numero].value=="NORMAL" && valor==false)


			u.guiasn.value		= parseFloat(u.guiasn.value)-1;


			


		u.tflete.value			= convertirMoneda(u.tflete.value);


		u.texcedente.value 		= convertirMoneda(u.texcedente.value);


		u.tead.value 			= convertirMoneda(u.tead.value);


		u.trecoleccion.value	= convertirMoneda(u.trecoleccion.value);


		u.tseguro.value 		= convertirMoneda(u.tseguro.value);


		u.tcombustible.value 	= convertirMoneda(u.tcombustible.value);


		u.totros.value 			= convertirMoneda(u.totros.value);


		u.tsubtotal.value		= convertirMoneda(u.tsubtotal.value);


		u.tiva.value 			= convertirMoneda(u.tiva.value);


		u.tivar.value 			= convertirMoneda(u.tivar.value);


		u.ttotal.value 			= convertirMoneda(u.ttotal.value);


	}


	function calcularTotalesOtros(){


		u = document.all;


		


		var subtotal 		= parseFloat(u.cantidad.value)*parseFloat(desconvertirMoneda(u.importe.value));


		var iva				= (parseFloat(u.porcentajeiva.value)/100)*subtotal;


		var ivaret			= (parseFloat(u.porcentajeivaretenido.value)/100)*subtotal;


		var montofacturar	= subtotal+iva-ivaret;


		


		u.subtotalotros.value = convertirMoneda(subtotal);


		u.ivaotros.value = convertirMoneda(iva);


		u.ivarotros.value = convertirMoneda(ivaret);


		u.montootros.value = convertirMoneda(montofacturar);


	}


</script>


<body>


<form id="form1" name="form1" method="post" action="">


<table width="562" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">


  <tr>


    <td width="578" class="FondoTabla Estilo4">Datos Generales </td>


  </tr>


  <tr>


    <td><table width="560" border="0" align="center" cellpadding="0" cellspacing="0">


      


      <tr>


        <td colspan="49"><table width="548" border="0" cellpadding="0" cellspacing="0">


          <tr>


            <td width="48"><span class="Tablas"> # Cliente </span></td>


            <td width="63"><span class="Tablas">


              <input name="cliente" type="text" class="Tablas" id="cliente" style="width:50px" value="<?=$vendedro ?>" />


            </span></td>


            <td width="92"><div class="ebtn_buscar"></div></td>


            <td width="20">&nbsp;</td>


            <td width="130">&nbsp;</td>


            <td width="29"></td>


            <td width="50">&nbsp;</td>


            <td width="120">&nbsp;</td>


            <td width="26">&nbsp;</td>


          </tr>


        </table></td>


      </tr>


      <tr>


        <td width="578" colspan="2" class="Tablas">Nombre&nbsp;


            &nbsp;<input name="nombre" type="text" class="Tablas" id="nombre" style="width:100px" value="<?=$nombre ?>" readonly=""/> 


            Apellido Pat


            <input name="paterno" type="text" class="Tablas" id="paterno" style="width:100px" value="<?=$paterno ?>" readonly=""/>


          Apellido Mat


          <input name="materno" type="text" class="Tablas" id="materno" style="width:100px" value="<?=$materno ?>" readonly=""/></td>


      </tr>


      <tr>


        <td colspan="3" class="Tablas">


        <table width="560" border="0" cellpadding="0" cellspacing="0">


        	<tr>


            	<td width="67"> 


                


                Calle</td>


            	<td width="240" id="celdacalle"><input name="calle" type="text" class="Tablas" id="calle" style="width:240px" readonly=""/></td>


            	<td width="39">N&uacute;mero</td>


            	<td width="100"><input name="numero" type="text" class="Tablas" id="numero" style="width:100px" value="<?=$numero ?>" readonly=""/></td>


            	<td width="14">CP</td>


            	<td width="100"><input name="cp" type="text" class="Tablas" id="cp" style="width:100px" value="<?=$cp ?>" readonly=""/></td>


            </tr>


        </table>        </td>


      </tr>


      <tr>


        <td colspan="49"><table width="558" border="0" cellpadding="0" cellspacing="0">


            <tr>


              <td><label>Colonia</label></td>


              <td colspan="3"><input name="colonia" type="text" class="Tablas" id="colonia" style="width:220px" value="<?=$colonia ?>" readonly=""/>


                  <label></label></td>


              <td>Cruce de Calles </td>


              <td colspan="3"><input name="ccalles" type="text" class="Tablas" id="ccalles" style="width:170px" value="<?=$ccalles ?>" readonly=""/></td>


            </tr>


            <tr>


              <td><label>Poblacion</label></td>


              <td><input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:80px" value="<?=$poblacion ?>" readonly=""/></td>


              <td><label>Municipio/ Delegacion</label></td>


              <td><input name="municipio" type="text" class="Tablas" id="municipio" style="width:80px" value="<?=$municipio ?>" readonly=""/></td>


              <td width="81"><label>Estado </label></td>


              <td width="76"><input name="estado" type="text" class="Tablas" id="estado" style="width:70px" value="<?=$estado ?>" readonly=""/></td>


              <td width="26"><label>País</label></td>


              <td width="78"><input name="pais" type="text" class="Tablas" id="pais" style="width:70px" value="<?=$pais ?>" readonly=""/></td>


            </tr>


            <tr>


              <td width="48"><label>Télefono</label></td>


              <td width="81"><input name="telefono" type="text" class="Tablas" id="telefono" style="width:80px" value="<?=$telefono ?>" readonly=""/></td>


              <td width="84"><label>Fax</label></td>


              <td width="84"><input name="fax" type="text" class="Tablas" id="fax" style="width:80px" value="<?=$fax ?>" readonly=""/></td>


              <td><span class="Tablas">RFC</span></td>


              <td colspan="3"><span class="Tablas">


                <input name="rfc" type="text" class="Tablas" id="rfc" style="width:130px" value="<?=$rfc ?>" readonly=""/>


              </span></td>


</tr>


            <tr>


              <td height="44" colspan="8" align="center" valign="middle"><div class="ebtn_imprimir"></div></td>


</tr>


            


          </table>


            <label></label>


            <label></label></td>


      </tr>


    </table></td>


  </tr>


</table>


</form>


<script>


	u 	= document.all;


	ub 	= parent.document.all;


	


	u.nombre.value = ub.nombre.value;


	u.paterno.value = ub.paterno.value;


	u.materno.value = ub.materno.value;


	u.calle.value = ub.calle.value;


	u.numero.value = ub.numero.value;


	u.cp.value = ub.cp.value;


	u.colonia.value = ub.colonia.value;


	u.ccalles.value = ub.ccalles.value;


	u.poblacion.value = ub.poblacion.value;


	u.municipio.value = ub.municipio.value;


	u.estado.value = ub.estado.value;


	u.pais.value = ub.pais.value;


	u.telefono.value = ub.telefono.value;


	u.fax.value = ub.fax.value;


	u.rfc.value = ub.rfc.value;


</script>


</body>


</html>


