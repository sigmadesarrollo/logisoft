<?	







	session_start();







	/*if(!$_SESSION[IDUSUARIO]!=""){







		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");







	}*/







	require_once('../Conectar.php');







	$l = Conectarse('webpmm');







	$fecha= date("d/m/Y");







	$idsucursal=$_SESSION[IDSUCURSAL];







	$usuario=$_SESSION[IDUSUARIO];







?>







<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">







<html xmlns="http://www.w3.org/1999/xhtml">







<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />







<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />







<title>Documento sin t&iacute;tulo</title>







<script src="../javascript/shortcut.js"></script>







<script src="../javascript/ClaseTabla.js"></script>







<script src="../javascript/ajax.js"></script>







<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>







<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>







<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>







<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>







<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>







<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">







<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">







<script>







	var u 	   = 	 document.all;







	var tabla1 = new ClaseTabla();	







	var nav4   = window.Event ? true : false;







	







	tabla1.setAttributes({







		nombre:"tablalista",







		campos:[







			{nombre:"SECTOR", medida:50, alineacion:"left", datos:"sector"},







			{nombre:"No_GUIA", medida:80, alineacion:"center", datos:"guia"},







			{nombre:"ORIGEN", medida:100, alineacion:"left", datos:"origen"},







			{nombre:"DESTINATARIO", medida:100, alineacion:"left", datos:"destinatario"},







			{nombre:"TIPO_FLETE", medida:50, alineacion:"center", datos:"tipoflete"},







			{nombre:"IMPORTE", medida:70, tipo:"moneda", alineacion:"right", datos:"importe",onClick:"agregarFormaPago"},







			{nombre:"ESTADO", medida:70, alineacion:"center", datos:"estado"},







			{nombre:"CHECK", medida:50, alineacion:"center", datos:"seleccion", tipo:"checkbox", onClick:"validacheck"},







			{nombre:"NOMBRE", medida:4, tipo:"oculto", alineacion:"left", datos:"nombre"},







			{nombre:"IDENTIFICACION", medida:4, tipo:"oculto", alineacion:"left", datos:"identificacion"},







			{nombre:"NUMERO_ID", medida:4, tipo:"oculto", alineacion:"left", datos:"numero_id"},







			{nombre:"CONDICION_PAGO", medida:4, tipo:"oculto", alineacion:"left", datos:"condicionpago"}







		],







		filasInicial:15,







		alto:200,







		seleccion:true,







		ordenable:false,







		eventoDblClickFila:"mostrarDetalle()",







		nombrevar:"tabla1"







	});







	







	window.onload = function(){

		tabla1.create();

		obtenerInicio();

		u.accion.value = "";

		u.cerrar.style.visibility = "hidden";

	}



	function obtenerInicio(){

		consultaTexto("mostrarInicio", "liquidaciondemercancia_con.php?accion=10&and="+Math.random());

	}



	function mostrarInicio(datos){

		row = datos.split(",");

		u.folio2.value = row[0];

		u.fecha.value 		= row[1];

		u.idsucursal.value	= row[2];

		u.sucursal.value 	= row[3];

	}







	







	







	function mostrarDetalle(){







		var arr = tabla1.getSelectedRow();







		var total = 0;







		total=parseFloat(u.ccontado2.value.replace("$ ","").replace(/,/,""));







		if (arr.estado!=""){







			if (u.estado.value==""){







				if (arr.estado=="ENTREGADA"){







				for(var i=0; i<tabla1.getRecordCount();i++){	







					if (u["tablalista_CHECK"][i].style.visibility=="visible"){	







						if (arr.guia==u["tablalista_No_GUIA"][i].value){







							u["tablalista_CHECK"][i].checked=true;







							total += parseFloat(arr.importe.replace("$ ","").replace(/,/,""));







							u.total.value = convertirMoneda(total);







						}







					}







				}







				u.total.value = convertirMoneda(parseFloat(u.total.value.replace("$ ","").replace(/,/,"")));







				abrirVentanaFija("datosentrega.php?folio="+tabla1.getSelectedIdRow(),600,200,"ventana","DATOS PERSONALES","")







				}







			}







		}







	}







	







		







	function validacheck(){







		var total = 0;







		total=parseFloat(u.ccontado2.value.replace("$ ","").replace(/,/,""));







		for(var i=0; i<tabla1.getRecordCount();i++){







		  	tabla1.setSelectedById("tablalista_id"+i);







		  	var objeto = tabla1.getSelectedRow();







			if(u["tablalista_CHECK"][i].checked==true){







				total += parseFloat(objeto.importe);







			}







		}







		u.total.value = convertirMoneda(total);







	}







	







	function buscarFolio(){







			var campos = tabla1.getValuesFromField("guia");







			camposarreglo = campos.split(",");







			for(var i=0; i<tabla1.getRecordCount(); i++){







				if(camposarreglo[i] == u.guia.value){







					tabla1.setSelectedById("tablalista_id"+i);







					u["tablalista_CHECK"][i].checked=true;







					return true;







				}







			}







		







	}







	







	function obtenerGuia(idreparto){







		consultaTexto("Validar","liquidaciondemercancia_con.php?accion=11&folio="+u.folio.value);







	}







	







	function Validar(datos){







		if(datos!=0 && datos!=""){







			alerta("El folio de reparto ya fue agregado a una liquidación","¡Atención!","folio");







		}else{







			obtenerInicio();







			consultaTexto("mostrarsicerroladevolucion","liquidaciondemercancia_con.php?accion=13&folio="+u.folio.value+"&and="+Math.random());







		}







	}







	







	function mostrarsicerroladevolucion(datos){







		if (datos!=0 && datos!=""){







			var obj = eval(convertirValoresJson(datos));







			if (obj[0].cerro==0){







					alerta("Favor de cerrar la devolución de mercancia con folio: " +obj[0].folio+ "","¡Atencion!","folio");







					return false;







				}else if (obj[0].cerro==1){







					consultaTexto("mostrarGuia","liquidaciondemercancia_con.php?accion=1&idreparto="+u.folio.value+"&and="+Math.random());







					u.accion.value ="";







					u.guardar.style.visibility="visible";







					//u.cerrar.style.visibility="visible";







					u.limpiar.style.visibility="visible";	







				}







			}else{







					alerta("No se encontro el Folio de Reparto","¡Atencion!","folio");







					limpiarTodo();







			}







	}







	







	function mostrarGuia(datos){







		var objeto = eval(convertirValoresJson(datos));







		if(objeto.idreparto==undefined){







			alerta("No se encontro el Folio de Reparto","¡Atencion!","folio");







			limpiarTodo();







		}else{







			u.folio.value = objeto.idreparto;







			u.unidad.value = objeto.numeroeconomico;







			u.conductor.value = objeto.conductorn1;







			u.conductor2.value = objeto.conductorn2;







			u.idconductor1.value = objeto.conductor1;







			u.idconductor2.value = objeto.conductor2;







			consultaTexto("mostrarGuiasTabla","liquidaciondemercancia_con.php?accion=2&folio="+u.folio.value+"&and="+Math.random());







		}







	}







	







	function mostrarGuiasTabla(datos){







		if (datos!=0) {







				tabla1.clear();







				var objeto = eval(convertirValoresJson(datos));







				var pagCredit = 0;







				var pagContad = 0;







				var cobContad = 0;







				var cobCredit = 0;







				var tpagCredit = 0;







				var tpagContad = 0;







				var tcobCredit = 0;







				var tcobContad = 0;







				for(var i=0;i<objeto.length;i++){







					var obj		 	   	= new Object();







					obj.sector 			= objeto[i].sector;







					obj.guia	 	   	= objeto[i].guia;







					obj.origen   		= objeto[i].origen;







					obj.destinatario	= objeto[i].destinatario;







					obj.tipoflete 		= objeto[i].tipoflete;







					obj.importe 		= objeto[i].importe;







					obj.estado		 	= objeto[i].estado;







					obj.nombre			= objeto[i].nombre;







					obj.identificacion	= objeto[i].identificacion;







					obj.numero_id		= objeto[i].numero_id;







					obj.condicionpago	= objeto[i].condicionpago;







					







					pagCredit += parseFloat((objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CREDITO")?1:0);







					pagContad += parseFloat((objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CONTADO")?1:0);







					cobCredit += parseFloat((objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CREDITO")?1:0);







					cobContad += parseFloat((objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CONTADO" && objeto[i].estado == "ENTREGADA")?1:0);







					







					tpagCredit += parseFloat((objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CREDITO")?objeto[i].importe:0);







					tpagContad += parseFloat((objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CONTADO")?objeto[i].importe:0);







					tcobCredit += parseFloat((objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CREDITO")?objeto[i].importe:0);







					tcobContad += parseFloat((objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CONTADO" && objeto[i].estado == "ENTREGADA")?objeto[i].importe:0);







					







					tabla1.add(obj);







				}	







				$sumaentregadas=0;







				$sumadevuletas=0;







				







				for(var i=0;i<objeto.length;i++){







					if (objeto[i].pagada=="1"){







						//u["tablalista_CHECK"][i].style.visibility="hidden";







						u["tablalista_CHECK"][i].disable=false;







						u["tablalista_CHECK"][i].checked==true;







					}else{







						if (objeto[i].estado=="ENTREGADA"){







							$sumaentregadas+=1;







						}else{







							$sumadevuletas+=1;







						}







					}







					







					







					if (objeto[i].tipoflete != "PAGADO" && objeto[i].condicionpago == "CONTADO"){







						u["tablalista_CHECK"][i].style.visibility="hidden";







					}







					







					if (objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CONTADO"){







						u["tablalista_CHECK"][i].style.visibility="hidden";







					}







					







					if (objeto[i].tipoflete == "PAGADO" && objeto[i].condicionpago == "CREDITO"){







						u["tablalista_CHECK"][i].style.visibility="hidden";







					}







				}







				







				u.entregadas.value = $sumaentregadas;







				u.devueltas.value = $sumadevuletas;







				u.pcredito.value = pagCredit;







				u.pcontado.value = pagContad;







				u.ccredito.value = cobCredit;







				u.ccontado.value = cobContad;







				u.pcredito2.value = convertirMoneda(tpagCredit);







				u.pcontado2.value = convertirMoneda(tpagContad);







				u.ccredito2.value = convertirMoneda(tcobCredit);







				u.ccontado2.value = convertirMoneda(tcobContad);







				u.total.value = convertirMoneda(tcobContad);







			}else{







				tabla1.clear();







				alerta("No existieron datos con los filtros seleccionados","¡Atención!","sucursal");







			}







	}







	







	function obtenerConductor(idconductor){







		consultaTexto("mostrarConductor","liquidaciondemercancia_con.php?accion=7&idempleado="+idconductor+"&and="+Math.random());







	}







	







	function mostrarConductor(datos){







		if (datos!=0){







			var obj = eval(convertirValoresJson(datos));







			u.entrego.value = obj[0].id;







			u.entregob.value = obj[0].conductor;







		}







	}







	







	function guardarValores(){







		var validar=0;







		if(u.entregob.value==""){







			alerta("Por favor proporcione quien entrego","¡Atencion!","entrego");







		}else{







				for(var i=0; i<tabla1.getRecordCount();i++){







					//if (u["tablalista_CHECK"][i].style.visibility!="hidden"){







						if (u["tablalista_ESTADO"][i].value=="ENTREGADA"){







							if (u["tablalista_NOMBRE"][i].value==""){







								alerta("Proprocione los datos personales de la guia: " + u["tablalista_No_GUIA"][i].value + " ","¡Atencion!","folio");







								//tabla1.setSelectedById("tablalista_id"+i);







								//abrirVentanaFija("datosentrega.php",600,200,"ventana","DATOS PERSONALES");







								return false;







							}







						}







				}	







					if (u.accion.value=="grabar"){







						ValidarGuardar(0);







					}else if(u.accion.value=="cerrar"){







						ValidarGuardar(1);







					}







		}







	}







	







	function ValidarGuardar(tipo){







		if(u.folio.value==""){







			alerta("Por favor proporcione el folio reparto ead","¡Atencion!","folio");







		}else if(u.entrego.value==""){







			alerta("Por favor proporcione quien entrego","¡Atencion!","entrego");







		}else if(u.total.value==""){







			alerta("Por favor proporcione el folio reparto ead","¡Atencion!","folio");







		}else{







	







			consultaTexto("MostrarGuardar", "liquidaciondemercancia_con.php?accion=3&idreparto="+u.folio.value+"&entregadas="+u.entregadas.value+"&devueltas="+u.devueltas.value+"&pagadas_credito="+u.pcredito.value+"&pagadas_contado="+u.pcontado.value+"&tpagadas_credito="+parseFloat(u.pcredito2.value.replace("$ ","").replace(/,/,""))+"&tpagadas_contado="+parseFloat(u.pcontado2.value.replace("$ ","").replace(/,/,""))+"&porcobrar_contado="+u.ccontado.value+"&porcobrar_credito="+u.ccredito.value+"&tporcobrar_contado="+parseFloat(u.ccontado2.value.replace("$ ","").replace(/,/,""))+"&tporcobrar_credito="+parseFloat(u.ccredito2.value.replace("$ ","").replace(/,/,""))+"&sucursal="+u.idsucursal.value+"&entrego="+u.entrego.value+"&fecha="+u.fecha.value+"&total="+parseFloat(u.total.value.replace("$ ","").replace(/,/,""))+"&idusuario="+u.usuario.value+"&folio="+u.folio2.value+"&cerrar="+tipo+"&valram="+Math.random());







		}







	}







	







	function MostrarGuardar(datos){







		if(datos!=0){







			var obj = eval(convertirValoresJson(datos));







			u.folio2.value = obj[0].folio;







			info("La informacion ha sido guardada","");







			//limpiarTodo();







			u.cerrar.style.visibility = "visible";







		}else{







			alerta("Hubo un error "+datos,"¡Atencion!","folio");







		}







	}







	







	function facturar(){







		consultaTexto("ValidarFacturacion", "liquidaciondemercancia_con.php?accion=16&folio="+u.folio2.value);	







	}







	







	function ValidarFacturacion(datos){







		if (datos!=0 && datos!="") {







			v_index = 0;







			var obj = eval(datos);







			var mensaje = "";







			var count=0;







			for(var i=0; i<obj.length; i++){







				mensaje += obj[i].cliente+",";







			}







			v_incompletas = mensaje.substr(0,mensaje.length-1);







			v_incompletas = v_incompletas.split(",");







			mostrarGuiaArreglo();







		}		







	}







	







	function mostrarGuiaArreglo(){







		setTimeout("mostrarGuiaArreglo2()",1500);







	}







	







	function mostrarGuiaArreglo2(){			







		//abrirVentanaFija("../facturacion/Facturacion.php", 600, 480, 'ventana', 'FACTURACION');







abrirVentanaFija("../facturacion/Facturacion.php?&modificar=1&cliente="+v_incompletas[v_index]+"&folio="+u.folio2.value+"&indice="+v_index, 700, 480, 'ventana', 'FACTURACION');







		if(v_incompletas[v_index]==undefined){







			VentanaModal.cerrar();







			//checarsifacturo();







			info("Se han completado las facturas","¡Atencion!");







			guardarValores();







			limpiarTodo();







		}







		v_index++;







	}	







	







	function checarsifacturo(){







		consultaTexto("Validarchecarsifacturo", "liquidaciondemercancia_con.php?accion=17&folio="+u.folio2.value);	







	}







	







	function Validarchecarsifacturo(datos){







		if (datos!=0 && datos!="") {







			alerta("No se completaron las facturas.!No se podra cerrar la liquidación EAD¡","¡Atencion!","");		







			return false;







		}else{







			info("Se han completado las facturas","¡Atencion!");







			guardarValores();







			limpiarTodo();







		}		







	}







	







	







	function actualizarFila(datos){

		var fila = tabla1.getSelectedRow();

		fila.nombre = datos.nombre

		fila.identificacion = datos.identificacion

		fila.numero_id = datos.numero_id

		tabla1.updateRowById(tabla1.getSelectedIdRow(),fila);

		actualizarDatosPersonales(fila.nombre,fila.identificacion,fila.numero_id,fila.guia);

		info("Los datos de entrega ha sido guardada","");

	}







	







	function actualizarDatosPersonales(nombre,identificacion,numero,guia){







		consultaTexto("MostrarGuardarDatosPersonales","liquidaciondemercancia_con.php?accion=8&nombre="+nombre+"&identificacion="+identificacion+"&numero="+numero+"&guia="+guia+"&valram="+Math.random());







	}







	







	function actualizarFormaPago(datos){







		var fila = tabla1.getSelectedRow();







		actualizarDatosFormaPago(datos.efectivo,datos.cheque,datos.ncheque,datos.banco,datos.nnotacredito,datos.notacredito,fila.guia);







		info("Los datos de formas de pago ha sido guardada","");







	}







	







	function actualizarDatosFormaPago(efectivo,cheque,ncheque,banco,nnotacredito,notacredito,guia){







		consultaTexto("MostrarGuardarDatosFormasPago","liquidaciondemercancia_con.php?accion=15&efectivo="+efectivo+"&cheque="+cheque+"&ncheque="+ncheque+"&banco="+banco+"&nnotacredito="+nnotacredito+"&notacredito="+notacredito+"&guia="+guia+"&valram="+Math.random());







	}







	







	function MostrarGuardarDatosFormasPago(datos){







		if (datos.indexOf("ok")>-1) {







	







		}else{







			alerta("Hubo un error","¡Atencion!","folio");







		}







	}







	







	function MostrarGuardarDatosPersonales(datos){







		if (datos.indexOf("ok")>-1) {







	







		}else{







			alerta("Hubo un error","¡Atencion!","folio");







		}







	}







	







	function obtenerFolio(folio){







		u.folio2.value = folio;







		consultaTexto("mostrarDatosEncabezados","liquidaciondemercancia_con.php?accion=5&folio="+folio);







		consultaTexto("mostrarDetalleDatos","liquidaciondemercancia_con.php?accion=6&folio="+folio);







	}







	







	function mostrarDatosEncabezados(datos){







		if(datos!=0){







			var obj = eval(convertirValoresJson(datos));







			u.fecha.value			= obj[0].fecha;







			u.idsucursal.value		= obj[0].clave; 







			u.entregadas.value = obj[0].entregadas;







			u.devueltas.value =  obj[0].devueltas;







			u.pcredito.value = 	 obj[0].pagadas_credito;







			u.pcontado.value =   obj[0].pagadas_contado;







			u.pcredito2.value =  convertirMoneda(obj[0].tpagadas_credito);







			u.pcontado2.value =  convertirMoneda(obj[0].tpagadas_contado);







			u.ccontado.value =   obj[0].porcobrar_contado;







			u.ccredito.value =   obj[0].porcobrar_credito;







			u.ccontado2.value =  convertirMoneda(obj[0].tporcobrar_contado);







			u.ccredito2.value =  convertirMoneda(obj[0].tporcobrar_credito);







			u.total.value =      convertirMoneda(obj[0].total);







			u.entrego.value =    obj[0].entrego;







			if (obj[0].cerro==0){







				u.guardar.style.visibility="visible";







				u.cerrar.style.visibility="visible";







				u.limpiar.style.visibility="visible";







			}else{







				u.accion.value ="";







				u.guardar.style.visibility="hidden";







				u.cerrar.style.visibility="hidden";







				u.limpiar.style.visibility="visible";







				u.folio.readOnly=true;







				u.folio.style.backgroundColor="#FFFF99";	







				u.guia.focus();	







			}







			consultaTexto("mostrarConductor","liquidaciondemercancia_con.php?accion=7&idempleado="+u.entrego.value+"&and="+Math.random());







			consultaTexto("mostrarGuiaReparto","liquidaciondemercancia_con.php?accion=1&idreparto="+obj[0].idreparto+"&and="+Math.random());







		}







	}







	







	function mostrarDetalleDatos(datos){







		if (datos!=0) {







				tabla1.clear();







				var objeto = eval(convertirValoresJson(datos));







				for(var i=0;i<objeto.length;i++){







					var obj		 	   	= new Object();







					obj.sector 			= objeto[i].sector;







					obj.guia	 	   	= objeto[i].guia;







					obj.origen   		= objeto[i].origen;







					obj.destinatario	= objeto[i].destinatario;







					obj.tipoflete 		= objeto[i].tipoflete;







					obj.importe 		= objeto[i].importe;







					obj.estado		 	= objeto[i].estado;







					obj.nombre			= objeto[i].nombre;







					obj.identificacion	= objeto[i].identificacion;







					obj.numero_id		= objeto[i].numero_id;







					obj.condicionpago	= objeto[i].condicionpago;







					tabla1.add(obj);







				}	







				for(var i=0;i<objeto.length;i++){







					u["tablalista_CHECK"][i].style.visibility="hidden";







				}







			}else{







				tabla1.clear();







				alerta("No existieron datos con los filtros seleccionados","¡Atención!","sucursal");







			}







	}







	







	







	function mostrarGuiaReparto(datos){







		var objeto = eval(convertirValoresJson(datos));







		if(objeto.idreparto==undefined){







			alerta("No se encontro el Folio de Reparto","¡Atencion!","folio");







		}else{







			u.folio.value = objeto.idreparto;







			u.unidad.value = objeto.numeroeconomico;







			u.conductor.value = objeto.conductorn1;







			u.conductor2.value = objeto.conductorn2;







			u.idconductor1.value = objeto.conductor1;







			u.idconductor2.value = objeto.conductor2;







		}







	}







	







	function limpiarTodo(){







			u.folio.value 		= "";







			u.folio2.value 		= "";







			u.fecha.value		= "";







			u.folio.value 		= "";







			u.unidad.value 		= "";







			u.conductor.value 	= "";







			u.conductor2.value 	= "";







			u.idsucursal.value	= "";







			u.entregadas.value 	= "";







			u.devueltas.value 	= "";







			u.pcredito.value 	= "";







			u.pcontado.value 	= "";







			u.pcredito2.value 	= "";







			u.pcontado2.value 	= "";







			u.ccontado.value 	= "";







			u.ccredito.value 	= "";







			u.ccontado2.value 	= "";







			u.ccredito2.value 	= "";







			u.total.value 		= "";







			u.entrego.value 	= "";







			u.entregob.value 	= "";







			u.estado.value		= "";







			tabla1.clear();







			u.folio.readOnly=false;







			u.folio.style.backgroundColor="";







			tabla1.clear();







			u.accion.value="";







			u.cerrar.style.visibility="hidden";







			u.limpiar.style.visibility="visible";







			u.guardar.style.visibility="visible";







			obtenerInicio();







	}







	







	function agregarFormaPago(){







		setTimeout("agregarMFormaPago()","1000");







	}







	







	function agregarMFormaPago(){







		var arr = tabla1.getSelectedRow();	







		if (u.estado.value==""){







			var importe=0;







			importe=convertirMoneda(arr.importe);







			if (arr.estado=="ENTREGADA" && arr.tipoflete=="POR COBRAR" && (arr.condicionpago=="CONTADO" || arr.condicionpago=="CREDITO")){







				abrirVentanaFija("liquidaciondemercancia_formapago.php?importe="+importe, 525, 418, "ventana", "FORMA DE PAGO");







			}







		}	







	}







	







	function validarfolioreparto(tipo){







		if (tipo==0){







			u.accion.value="grabar";







		}else if(tipo==1){







			u.accion.value="cerrar";







		}







		consultaTexto("mostrarGuiavalidacion","liquidaciondemercancia_con.php?accion=1&idreparto="+u.folio.value+"&and="+Math.random());







	}







	







	function mostrarGuiavalidacion(datos){







		var objeto = eval(convertirValoresJson(datos));







		if(objeto.idreparto==undefined){







			alerta("No se encontro el Folio de Reparto","¡Atencion!","folio");







			limpiarTodo();







		}else{







			if(u.accion.value=="cerrar"){







			







				consultaTexto("mostrarcontador2", "liquidaciondemercancia_con.php?accion=17&folio="+u.folio2.value+"&tipo=1");	







		







				if (u.nofacturadas.value==u.facturadas.value){







					confirmar('Se Generaran Facturas.', '', 'facturar()', '');	







				}else{







					confirmar('¿Deseas Generar las Facturas Faltantes?', '', 'facturar()', '');		







				}







			}else if (u.accion.value=="grabar"){







					guardarValores();







			}







		}







	}







	







	function mostrarcontador(datos){







		row = datos.split(",");







		u.nofacturadas.value = row[0];







	}







	







	function mostrarcontador2(datos){







		row = datos.split(",");







		u.facturadas.value = row[0];







	}







	







	function cerrarliquidacion(){







			consultaTexto("mostrarcontador", "liquidaciondemercancia_con.php?accion=17&folio="+u.folio2.value+"&tipo=0");







		







			confirmar('¿Desea cerrar la liquidación del folio de reparto EAD: '+ u.folio.value +'?', '', 'validarfolioreparto(1)', '');	







		







	}







	







	function ventanamodificar(){







		consultaTexto("Datosmodificar","liquidaciondemercancia_con.php?accion=12&folio="+u.folio.value);







	}







	







	function Datosmodificar(datos){







		if(datos.indexOf("ok")>-1){







			limpiarTodo();







		}else{







			alerta3("Hubo un error al guardar "+datos,"¡Atención!");







		}







	}







	







	function convertirMoneda(valor){







		valorx = (valor=="")?"0.00":valor;







		valor1 = Math.round(parseFloat(valorx)*100)/100;







		valor2 = "$ "+numcredvar(valor1.toLocaleString());







		return valor2;







	}







	







	function numcredvar(cadena){ 







		var flag = false; 







		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 







		var num = cadena.split(',').join(''); 







		cadena = Number(num).toLocaleString(); 







		if(flag) cadena += '.'; 







		return cadena;







	}







	







	function esNan(caja){







		if (document.getElementById(caja).value.replace('$ ','').replace(/,/g,'')=="NaN"){







				document.getElementById(caja).value	= 0;







		}







	}







	







</script>







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







-->







</style>















<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />







<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />







<style type="text/css">







<!--







.Estilo4 {font-size: 12px}







-->







</style>







<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />







</head>







<body>







<form id="form1" name="form1" method="post" action="">







  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />







  <span class="Tablas">







<input name="usuario" type="hidden" class="Tablas" id="usuario" style="width:100px;background:#FFFF99" value="<?=$usuario?>" readonly=""/>







</span><br>







<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">







  <tr>







    <td width="619" class="FondoTabla Estilo4">LIQUIDACI&Oacute;N DE MERCANC&Iacute;A</td>







  </tr>







  <tr>







    <td><table width="617" border="0" cellpadding="0" cellspacing="0">







      <tr>







        <td width="615"><table width="615" border="0" cellpadding="0" cellspacing="0">







          <tr>







            <td width="113">Folio Reparto EAD </td>







            <td width="86"><input name="folio" type="text" class="Tablas" id="folio" value="<?=$folio ?>" style="width:80px" onKeyPress="if(event.keyCode==13){obtenerGuia(this.value)}"/></td>







            <td width="29">Fecha</td>







            <td width="80"><span class="Tablas">







              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>







            </span></td>







            <td width="42">Sucursal</td>







            <td width="100"><span class="Tablas">







              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal?>" readonly=""/>







              <input name="idsucursal" type="hidden" class="Tablas" id="idsucursal" style="width:100px;background:#FFFF99" value="<?=$idsucursal?>" readonly=""/>







              <input name="estado" type="hidden" class="Tablas" id="estado" style="width:100px;background:#FFFF99" value="<?=$estado?>" readonly=""/>







              <input name="facturadas" type="hidden" class="Tablas" id="facturadas" style="width:100px;background:#FFFF99" value="<?=$facturadas?>" readonly=""/>







              <input name="nofacturadas" type="hidden" class="Tablas" id="nofacturadas" style="width:100px;background:#FFFF99" value="<?=$nofacturadas?>" readonly=""/>







            </span></td>







            <td width="26">Folio</td>







            <td width="87"><input name="folio2" type="text" class="Tablas" id="folio2" style="width:80px;background:#FFFF99" value="<?=$folio2 ?>" readonly=""/></td>







            <td width="38"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarliquidacionmercancia.php?funcion=obtenerFolio',600,475,'ventana','BUSCAR LIQUIDACION MERCANCIA','');"></div></td>







            <td width="14"><span class="Tablas">              </span></td>







          </tr>







        </table></td>







        </tr>







      <tr>







        <td><table width="610" border="0" cellpadding="0" cellspacing="0">







          <tr>







            <td width="38">Unidad</td>







            <td width="198"><span class="Tablas">







              <input name="unidad" type="text" class="Tablas" id="unidad" style="width:150px;background:#FFFF99"  readonly=""/>







            </span></td>







            <td width="55"><div align="right">Conductor</div></td>







            <td width="319"><span class="Tablas">







              <input name="conductor" type="text" class="Tablas" id="conductor" style="width:305px;background:#FFFF99"  readonly=""/>







            </span></td>







          </tr>







        </table></td>







      </tr>







      <tr>







        <td><table width="615" border="0" cellpadding="0" cellspacing="0">







          <tr>







            <td width="42"># Gu&iacute;a</td>







            <td width="96"><input name="guia" type="text" id="guia" onKeyPress="if(event.keyCode==13){buscarFolio();}" /></td>







            <td width="98"><input name="idconductor1" type="hidden" id="idconductor1" />







              <input name="idconductor2" type="hidden" id="idconductor2" /></td>







            <td width="54"><div align="right">Conductor</div></td>







            <td width="325"><span class="Tablas">







              <input name="conductor2" type="text" class="Tablas" id="conductor2" style="width:303px;background:#FFFF99"  readonly=""/>







            </span></td>







          </tr>







        </table></td>







      </tr>







      <tr>







        <td><table border="0" cellpadding="0" cellspacing="0" id="tablalista"></table></td>







      </tr>







      <tr>







        <td>&nbsp;</td>







      </tr>







      <tr>







        <td><table width="615" border="0" cellpadding="0" cellspacing="0">







          <tr>







            <td width="57">Entregadas</td>







            <td width="34"><span class="Tablas">







              <input name="entregadas" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







            <td width="94">Pagadas Cr&eacute;dito </td>







            <td width="174"><span class="Tablas">







              <input name="pcredito" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>







              <input name="pcredito2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







            <td width="105">Por Cobrar Contado </td>







            <td width="33"><span class="Tablas">







              <input name="ccontado" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







            <td width="118"><span class="Tablas">







              <input name="ccontado2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







          </tr>







        </table></td>







      </tr>







      <tr>







        <td><table width="615" border="0" cellpadding="0" cellspacing="0">







          <tr>







            <td>Devueltas</td>







            <td><span class="Tablas">







              <input name="devueltas" type="text" class="Tablas" id="devueltas" style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







            <td>Pagadas Contado</td>







            <td><span class="Tablas">







              <input name="pcontado" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>







              <input name="pcontado2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







            <td width="107">Por Cobrar Cr&eacute;dito </td>







            <td width="32"><span class="Tablas">







              <input name="ccredito" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







            <td><span class="Tablas">







              <input name="ccredito2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







          </tr>







          <tr>







            <td width="56">&nbsp;</td>







            <td width="35">&nbsp;</td>







            <td width="94">&nbsp;</td>







            <td width="173">&nbsp;</td>







            <td colspan="2"><div align="right">Total a Liquidar </div></td>







            <td width="118"><span class="Tablas">







              <input name="total" type="text" class="Tablas" id="total" style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>







            </span></td>







          </tr>







          







        </table>







          </td>







      </tr>







      







      







      <tr>







        <td><table width="615" border="0" cellpadding="0" cellspacing="0">







          <tr>







            <td width="42"><div align="left">Entrego              </div></td>







            <td width="96"><input name="entrego" type="text" id="entrego"  onKeyPress="if(event.keyCode==13){obtenerConductor(this.value)} " /></td>







            <td width="24"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerConductor&entrego=4750&conductor1='+document.all.idconductor1.value+'&conductor2='+document.all.idconductor2.value,500,500,'ventana','BUSCAR EMPLEADO','');"></div></td>







            <td width="453"><span class="Tablas">







              <input name="entregob" type="text" class="Tablas" id="entregob" style="width:360px;background:#FFFF99"  readonly=""/>







            </span></td>







          </tr>







          <tr>







            <td colspan="4" align="center">&nbsp;</td>







          </tr>







          <tr>







            <td colspan="4" align="center"><table width="276">







                <tr>







                  <td ><div id="guardar" style=":<? if($_POST[accion]=='grabar'){?>visibility:hidden<? }?>" class="ebtn_guardar" onclick="validarfolioreparto(0)"></div></td>







                  <td><div id="cerrar" class="ebtn_cerrarliquidacion" onclick="cerrarliquidacion()"></div></td>







                  <td><div id="limpiar" class="ebtn_nuevo" onclick="limpiarTodo()"></div></td>







                </tr>







              </table>







              </td>







            </tr>







        </table></td>







      </tr>







      <tr>







        <td>&nbsp;</td>







      </tr>







    </table></td>







  </tr>







</table>







</form>







</body>















</html>