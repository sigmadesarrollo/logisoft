<?	require_once('../../Conectar.php');


	$l = Conectarse('webpmm');


	$get = mysql_query("SELECT COUNT(*) FROM generacionconvenio


						WHERE CURDATE()<=vigencia AND YEAR(fecha) = '".$_GET[fecha]."' AND


						".(($_GET[sucursal]!="0") ? " sucursal = ".$_GET[sucursal]." AND " : '' )."


						(descuentosobreflete=1 OR consignaciondescuento=1)",$l);


	$tdes = mysql_result($get,0);	


	


	$s = mysql_query("SELECT COUNT(*) FROM generacionconvenio


					  WHERE CURDATE()<=vigencia AND YEAR(fecha) = '".$_GET[fecha]."' AND


					  ".(($_GET[sucursal]!="0") ? " sucursal = ".$_GET[sucursal]." AND " : '' )."


					  (consignacionkg=1 OR precioporkg=1)",$l);


	$tkg = mysql_result($s,0);	


		


	$r = mysql_query("SELECT COUNT(*) FROM generacionconvenio


					  WHERE CURDATE()<=vigencia AND YEAR(fecha) = '".$_GET[fecha]."' AND


					  ".(($_GET[sucursal]!="0") ? " sucursal = ".$_GET[sucursal]." AND " : '' )."


					  (consignacioncaja=1 OR precioporcaja=1)",$l);


	$tpq = mysql_result($r,0);


	


	$s = "SELECT cs.prefijo AS sucursal, gc.idcliente, 


		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,


		'DESCUENTO' AS tipoconvenio,


		IFNULL(IF(gc.descuentosobreflete=1,


		CONCAT(gc.cantidaddescuento,'%'),CONCAT(0,'%')),


		CONCAT(0,'%')) AS precionormal,


		IFNULL(IF(gc.consignaciondescuento=1,


		CONCAT(gc.consignaciondescantidad,'%'),


		CONCAT(0,'%')),CONCAT(0,'%')) AS precioempresarial,


		DATE_FORMAT(gc.vigencia,'%d/%m/%Y') AS vigencia


		FROM generacionconvenio gc


		INNER JOIN catalogosucursal cs ON gc.sucursal = cs.id


		INNER JOIN catalogocliente cc ON gc.idcliente = cc.id


		WHERE YEAR(gc.fecha) = '".$_GET[fecha]."' AND CURDATE()<=gc.vigencia AND


		".(($_GET[sucursal]!="0") ? " gc.sucursal = ".$_GET[sucursal]." AND " : '' )."


		(gc.descuentosobreflete=1 OR gc.consignaciondescuento=1)


		ORDER BY sucursal ASC


		LIMIT 0,30";


		$g = mysql_query($s,$l) or die($s);


		while($f = mysql_fetch_object($g)){


			$cadenaDescuento .= "{sucursal:'".cambio_texto($f->sucursal).


								"',idcliente:'".$f->idcliente.


								"',cliente:'".cambio_texto($f->cliente).


								"',tipoconvenio:'".$f->tipoconvenio.


								"',precionormal:'".$f->precionormal.


								"',precioempresarial:'".$f->precioempresarial.								


								"',vigencia:'".$f->vigencia."'},";


		}


		$cadenaDescuento = substr($cadenaDescuento,0,strlen($cadenaDescuento)-1);


		


		$s = "SELECT cs.prefijo AS sucursal, gc.idcliente, 


		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,


		'KILOGRAMO' AS tipoconvenio, 0 AS precio,


		DATE_FORMAT(gc.vigencia,'%d/%m/%Y') AS vigencia


		FROM generacionconvenio gc


		INNER JOIN catalogosucursal cs ON gc.sucursal = cs.id


		INNER JOIN catalogocliente cc ON gc.idcliente = cc.id


		WHERE YEAR(gc.fecha) = '".$_GET[fecha]."' AND CURDATE()<=gc.vigencia AND


		".(($_GET[sucursal]!="0") ? " gc.sucursal = ".$_GET[sucursal]." AND " : '' )."


		(gc.consignacionkg=1 OR gc.precioporkg=1)


		ORDER BY sucursal ASC


		LIMIT 0,30";


		$t = mysql_query($s,$l) or die($s);


		while($ff = mysql_fetch_object($t)){				


			$cadenaKilogramo .= "{sucursal:'".cambio_texto($ff->sucursal).


								"',idcliente:'".$ff->idcliente.


								"',cliente:'".cambio_texto($ff->cliente).


								"',tipoconvenio:'".$ff->tipoconvenio.


								"',precio:'".$ff->precio.


								"',vigencia:'".$ff->vigencia."'},";


		}


		$cadenaKilogramo = substr($cadenaKilogramo,0,strlen($cadenaKilogramo)-1);


		$s = "SELECT cs.prefijo AS sucursal, gc.idcliente, 


		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,


		'PAQUETE' AS tipoconvenio, 0 AS precio,


		DATE_FORMAT(gc.vigencia,'%d/%m/%Y') AS vigencia


		FROM generacionconvenio gc


		INNER JOIN catalogosucursal cs ON gc.sucursal = cs.id


		INNER JOIN catalogocliente cc ON gc.idcliente = cc.id


		WHERE YEAR(gc.fecha) = '".$_GET[fecha]."' AND CURDATE()<=gc.vigencia AND


		".(($_GET[sucursal]!="0") ? " gc.sucursal = ".$_GET[sucursal]." AND " : '' )."


		(gc.consignacioncaja=1 OR gc.precioporcaja=1)


		ORDER BY sucursal ASC


		LIMIT 0,30";


		$k = mysql_query($s,$l) or die($s);		


		while($fk = mysql_fetch_object($k)){				


			$cadenaPaquete .= "{sucursal:'".cambio_texto($fk->sucursal).


								"',idcliente:'".$fk->idcliente.


								"',cliente:'".cambio_texto($fk->cliente).


								"',tipoconvenio:'".$fk->tipoconvenio.


								"',precio:'".$fk->precio.								


								"',statu:'".$fk->statu.


								"',vigencia:'".$fk->vigencia."'},";


		}


		$cadenaPaquete = substr($cadenaPaquete,0,strlen($cadenaPaquete)-1);


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />


<script src="../../javascript/ajax.js"></script>


<script src="../../javascript/ClaseTabla.js"></script>


<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>


<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>


<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>


<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">


<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">


<script>


	var tabla1 		= new ClaseTabla();


	var tabla2 		= new ClaseTabla();


	var tabla3 		= new ClaseTabla();


	var	u			= document.all;


	var inicio		= 30;


	var sepasopq	= 0;


	var sepasods	= 0;


	var sepasokg	= 0;


	tabla1.setAttributes({


		nombre:"detalle",


		campos:[


			{nombre:"SUCURSAL", medida:80, alineacion:"center", datos:"sucursal"},


			{nombre:"# CLIENTE", medida:50, onDblClick:"historialCliente(1);", alineacion:"left",  datos:"idcliente"},


			{nombre:"CLIENTE", medida:200, alineacion:"left",  datos:"cliente"},


			{nombre:"TIPO CONVENIO", medida:80, alineacion:"center", datos:"tipoconvenio"},			


			{nombre:"% NORMAL", medida:80, alineacion:"center",  datos:"precionormal"},


			{nombre:"% EMPRESARIAL", medida:80, alineacion:"center",  datos:"precioempresarial"},


			{nombre:"VENCIMIENTO", medida:80, alineacion:"center",  datos:"vigencia"}


		],


		filasInicial:30,


		alto:160,


		seleccion:true,


		ordenable:false,


		//eventoDblClickFila:"verRecoleccion()",


		nombrevar:"tabla1"


	});


	tabla2.setAttributes({


		nombre:"detalle2",


		campos:[


			{nombre:"SUCURSAL", medida:80, alineacion:"center", datos:"sucursal"},


			{nombre:"# CLIENTE", medida:50, alineacion:"left", onDblClick:"historialCliente(2);",  datos:"idcliente"},


			{nombre:"CLIENTE", medida:220, alineacion:"left",  datos:"cliente"},


			{nombre:"TIPO CONVENIO", medida:100, alineacion:"center", datos:"tipoconvenio"},			


			{nombre:"PRECIO", medida:100, alineacion:"center", tipo:"moneda", onDblClick:"obtenerPrecioKilos",  datos:"precio"},


			{nombre:"VENCIMIENTO", medida:100, alineacion:"center",  datos:"vigencia"}


		],


		filasInicial:30,


		alto:150,


		seleccion:true,


		ordenable:false,


		//eventoDblClickFila:"verRecoleccion()",


		nombrevar:"tabla2"


	});


	tabla3.setAttributes({


		nombre:"detalle3",


		campos:[


			{nombre:"SUCURSAL", medida:80, alineacion:"center", datos:"sucursal"},


			{nombre:"# CLIENTE", medida:80, alineacion:"left", onDblClick:"historialCliente(3);",  datos:"idcliente"},


			{nombre:"CLIENTE", medida:210, alineacion:"left",  datos:"cliente"},


			{nombre:"TIPO CONVENIO", medida:100, alineacion:"center", datos:"tipoconvenio"},			


			{nombre:"PRECIO", medida:100, alineacion:"center", tipo:"moneda", onDblClick:"obtenerPrecioPaquete",  datos:"precio"},


			{nombre:"VENCIMIENTO", medida:80, alineacion:"center",  datos:"vigencia"}


		],


		filasInicial:30,


		alto:150,


		seleccion:true,


		ordenable:false,


		//eventoDblClickFila:"verRecoleccion()",


		nombrevar:"tabla3"


	});


	window.onload = function(){


		tabla1.create();


		tabla2.create();


		tabla3.create();


		obtenerDetalles();


		u.d_atrasdes.style.visibility = "hidden";


		u.d_atraskg.style.visibility  = "hidden";


		u.d_atraspq.style.visibility  = "hidden";


		if(parseInt(u.mostrardes2.value) <= 30){


			u.d_sigdes.style.visibility  = "hidden";


		}


		if(parseInt(u.mostrarkg2.value) <=30){


			u.d_sigkg.style.visibility  = "hidden";


		}


		if(parseInt(u.mostrarpq2.value)<=30){


			u.d_sigpq.style.visibility  = "hidden";


		}


	}


	function obtenerDetalles(){


		var datosDescuento	 = <? if($cadenaDescuento!=""){echo "[".$cadenaDescuento."]";}else{echo "0";} ?>;


		var datosKilogramos	 = <? if($cadenaKilogramo!=""){echo "[".$cadenaKilogramo."]";}else{echo "0";} ?>;


		var datosPaquete	 = <? if($cadenaPaquete!=""){echo "[".$cadenaPaquete."]";}else{echo "0";} ?>;


		if(datosDescuento!="0"){


			for(var i=0; i<datosDescuento.length;i++){


				tabla1.add(datosDescuento[i]);


			}


		}


		if(datosKilogramos!="0"){


			for(var i=0; i<datosKilogramos.length;i++){


				tabla2.add(datosKilogramos[i]);


			}


		}


		if(datosPaquete!="0"){


			for(var i=0; i<datosPaquete.length;i++){


				tabla3.add(datosPaquete[i]);


			}


		}


	}	


	function mostrarDetalle(datos){


		if(datos.indexOf("nada")<0){


			var obj = eval(datos);


			tabla1.setJsonData(obj);


		}


	}


	function mostrarDetalleKg(datos){


		if(datos.indexOf("nada")<0){


			var obj = eval(datos);


			tabla2.setJsonData(obj);


		}


	}


	function mostrarDetallePaq(datos){


		if(datos.indexOf("nada")<0){


			var obj = eval(datos);


			tabla3.setJsonData(obj);


		}


	}	


	function mostrarDescuento(tipo){


		if(tipo == "atras"){


			u.d_sigdes.style.visibility = "visible";


			u.totaldes.value = parseFloat(u.totaldes.value) - inicio;


			if(parseFloat(u.totaldes.value) <= "1"){				


				u.totaldes.value = "01";


				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;


				if(parseFloat(u.mostrardes.value) < inicio){


					u.mostrardes.value = inicio;


				}


				u.d_atrasdes.style.visibility = "hidden";				


				consultaTexto("mostrarDetalle","consultasClientes.php?accion=12&inicio=0&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>");


			}else{


				if(sepasods!=0){


					u.mostrardes.value = sepasods;


					sepasods = 0;


				}


				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;


				if(parseFloat(u.mostrardes.value) < inicio){


					u.mostrardes.value = inicio;


				}


				consultaTexto("mostrarDetalle","consultasClientes.php?accion=12&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>&inicio="+u.totaldes.value);


			}			


		}else{


			u.d_atrasdes.style.visibility = "visible";


			u.totaldes.value = inicio + parseFloat(u.totaldes.value);				


			if(parseFloat(u.totaldes.value) > parseFloat(u.contadordes.value)){


				u.totaldes.value = parseFloat(u.totaldes.value) - inicio;


				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;


				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){


					u.mostrardes.value = u.contadordes.value;


				}


				u.d_sigdes.style.visibility = "hidden";


			}else{


				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;


				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){


					sepasods	=	u.mostrardes.value;


					u.mostrardes.value = u.contadordes.value;


				}


				consultaTexto("mostrarDetalle","consultasClientes.php?accion=12&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>&inicio="+u.totaldes.value);


			}			


		}	


	}


	function mostrarKilogramo(tipo){


		if(tipo == "atras"){


			u.d_sigkg.style.visibility = "visible";


			u.totalkg.value = parseFloat(u.totalkg.value) - inicio;


			if(parseFloat(u.totalkg.value) <= "1"){


				u.totalkg.value = "01";


				u.mostrarkg.value = parseFloat(u.mostrarkg.value) - inicio;


				if(parseFloat(u.mostrarkg.value) < inicio){


					u.mostrarkg.value = inicio;


				}


				u.d_atraskg.style.visibility = "hidden";				


				consultaTexto("mostrarDetalleKg","consultasClientes.php?accion=13&inicio=0&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>");


			}else{


				if(sepasokg!=0){


					u.mostrarkg.value = sepasokg;


					sepasokg = 0;


				}


				u.mostrarkg.value = parseFloat(u.mostrarkg.value) - inicio;


				if(parseFloat(u.mostrarkg.value) < inicio){


					u.mostrarkg.value = inicio;


				}


				consultaTexto("mostrarDetalleKg","consultasClientes.php?accion=13&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>&inicio="+u.totalkg.value);


			}			


		}else{


			u.d_atraskg.style.visibility = "visible";


			u.totalkg.value = inicio + parseFloat(u.totalkg.value);				


			if(parseFloat(u.totalkg.value) > parseFloat(u.contadorkg.value)){


				u.totalkg.value = parseFloat(u.totalkg.value) - inicio;


				u.mostrarkg.value = parseFloat(u.mostrarkg.value) + inicio;


				if(parseFloat(u.mostrarkg.value)>parseFloat(u.contadorkg.value)){


					u.mostrarkg.value = u.contadordes.value;


				}


				u.d_sigkg.style.visibility = "hidden";


			}else{


				u.mostrarkg.value = parseFloat(u.mostrarkg.value) + inicio;


				if(parseFloat(u.mostrarkg.value)>parseFloat(u.contadorkg.value)){


					sepasokg = u.mostrarkg.value;


					u.mostrarkg.value = u.contadorkg.value;


				}


				consultaTexto("mostrarDetalleKg","consultasClientes.php?accion=13&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>&inicio="+u.totalkg.value);


			}			


		}	


	}


	function mostrarPaquete(tipo){


		if(tipo == "atras"){


			u.d_sigpq.style.visibility = "visible";


			u.totalpq.value = parseFloat(u.totalpq.value) - inicio;


			if(parseFloat(u.totalpq.value) <= "1"){


				u.totalpq.value = "01";


				u.mostrarpq.value = parseFloat(u.mostrarpq.value) - inicio;


				if(parseFloat(u.mostrarpq.value) < inicio){


					u.mostrarpq.value = inicio;


				}


				u.d_atraspq.style.visibility = "hidden";				


				consultaTexto("mostrarDetallePaq","consultasClientes.php?accion=14&inicio=0&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>");


			}else{


				if(sepasopq!=0){


					u.mostrarpq.value	= sepaso;


					sepasopq = 0;


				}


				u.mostrarpq.value = parseFloat(u.mostrarpq.value) - inicio;


				if(parseFloat(u.mostrarpq.value) < inicio){


					u.mostrarpq.value = inicio;


				}


				


				consultaTexto("mostrarDetallePaq","consultasClientes.php?accion=14&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>&inicio="+u.totalpq.value);


			}			


		}else{


			u.d_atraspq.style.visibility = "visible";


			u.totalpq.value = inicio + parseFloat(u.totalpq.value);


			if(parseFloat(u.totalpq.value) > parseFloat(u.contadorpq.value)){


				u.totalpq.value = parseFloat(u.totalpq.value) - inicio;


				u.mostrarpq.value = parseFloat(u.mostrarpq.value) + inicio;


				if(parseFloat(u.mostrarpq.value)>parseFloat(u.contadorpq.value)){


					u.mostrarpq.value = u.contadorpq.value;


				}


				u.d_sigpq.style.visibility = "hidden";


			}else{


				u.mostrarpq.value = parseFloat(u.mostrarpq.value) + inicio;


				if(parseFloat(u.mostrarpq.value)>parseFloat(u.contadorpq.value)){


					sepasopq = u.mostrarpq.value;


					u.mostrarpq.value = u.contadorpq.value;


				}				


				consultaTexto("mostrarDetallePaq","consultasClientes.php?accion=14&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>&inicio="+u.totalpq.value);


			}


		}	


	}


	


	function obtenerPrecioPaquete(){


		var obj = tabla3.getSelectedRow();


		parent.document.all.barratabs_contenedor_id7.disabled=false;	


		parent.document.all.iframe_id7.src='mostrarDesglozeConvenio.php?cliente='+obj.idcliente+'&tipo=paquete';


		parent.tabs.seleccionar(7);


	}


	function obtenerPrecioKilos(){


		var obj = tabla2.getSelectedRow();


		parent.document.all.barratabs_contenedor_id7.disabled=false;	


		parent.document.all.iframe_id7.src='mostrarDesglozeConvenio.php?cliente='+obj.idcliente+'&tipo=kilos';


		parent.tabs.seleccionar(7)


	}


	function historialCliente(tipo){


		var obj = eval("tabla"+tipo+".getSelectedRow()");


		parent.document.all.barratabs_contenedor_id8.disabled=false;	


			parent.document.all.iframe_id8.src='reporteHistorialCliente.php?cliente='+obj.idcliente+'&fecha='+<?=$_GET[fecha]?>;


		parent.tabs.seleccionar(8)


	}


	


	function tipoImpresionDescuento(valor){


		if(valor=="Archivo"){


			window.open("http://www.pmmentuempresa.com/web/general/clientes/convenioVigVenExcel.php?accion=12&titulo=CONVENIOS VIGENTES DESCUENTO&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>");


		}


	}


	function tipoImpresionKilos(valor){


		if(valor=="Archivo"){


			window.open("http://www.pmmentuempresa.com/web/general/clientes/convenioVigVenExcel.php?accion=13&titulo=CONVENIOS VIGENTES KILOGRAMOS&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>");


		}


	}


	function tipoImpresionPaquetes(valor){


		if(valor=="Archivo"){


			window.open("http://www.pmmentuempresa.com/web/general/clientes/convenioVigVenExcel.php?accion=14&titulo=CONVENIOS VIGENTES PAQUETES&sucursal=<?=$_GET[sucursal] ?>&fecha=<?=$_GET[fecha] ?>");


		}


	}


</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />


<title>Documento sin t&iacute;tulo</title>


<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />


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


<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />


<style type="text/css">


<!--


.Estilo4 {font-size: 12px}


.Balance {background-color: #FFFFFF; border: 0px none}


.Balance2 {background-color: #DEECFA; border: 0px none;}


-->


</style>


<link href="../venta/Tablas.css" rel="stylesheet" type="text/css" />


</head>


<body>


<form id="form1" name="form1" method="post" action="">


<table width="680" border="0" align="center" cellpadding="0" cellspacing="0">


  <tr>


    <td width="426"></td>


  </tr>


  <tr>


    <td><span class="Estilo4">Descuento


      <input name="totaldes" type="hidden" id="totaldes" value="01" />


          <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />


    </span></td>


  </tr>


  <tr>


    <td><div align="left" style="overflow:auto;height:170px; width:680px">


      <table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">


      </table>


    </div></td>


  </tr>


  <tr>


    <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">


      <tr>


        <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>


        <td width="302" align="center"><strong>Total Descuento:


          <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />


                <strong>


                <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:90px; text-align:center;background-color:#FFFF99;" value="<?=$tdes; ?>" />


              </strong></strong></td>


        <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>


      </tr>


    </table></td>


  </tr>


  <tr>


    <td align="right"><table width="80" align="center">


      <tr>


        <td width="72" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresionDescuento', 300, 230, 'ventana', 'Busqueda')"></div></td>


      </tr>


    </table></td>


  </tr>


  <tr>


    <td><span class="Estilo4">Kilogramo


      <input name="totalkg" type="hidden" id="totalkg" value="01" />


          <input name="contadorkg" type="hidden" id="contadorkg" value="<?=$tkg ?>" />


    </span></td>


  </tr>


  <tr>


    <td></td>


  </tr>


  <tr>


    <td><div align="left" style="overflow:auto;height:170px; width:680px">


      <table width="578" id="detalle2" border="0" cellpadding="0" cellspacing="0">


      </table>


    </div></td>


  </tr>


  <tr>


    <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">


      <tr>


        <td width="107" align="right"><div id="d_atraskg" class="ebtn_atraz" onclick="mostrarKilogramo('atras');"></div></td>


        <td width="302" align="center"><strong>Total Kilogramo:


          <input name="mostrarkg" class="Tablas" type="hidden" id="mostrarkg" style="width:70px; text-align:center; border:none" value="30" />


                <input name="mostrarkg2" class="Tablas" type="text" id="mostrarkg2" style="width:90px; text-align:center; background-color:#FFFF99;" value="<?=$tkg; ?>" />


        </strong></td>


        <td width="91" align="left"><div id="d_sigkg" <? if($tkg=="0" || $tkg==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarKilogramo('adelante');"></div></td>


      </tr>


    </table></td>


  </tr>


  <tr>


    <td align="right"><table width="80" align="center">


      <tr>


        <td width="72" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresionKilos', 300, 230, 'ventana', 'Busqueda')"></div></td>


      </tr>


    </table></td>


  </tr>


  <tr>


    <td><span class="Estilo4">Paquete


      <input name="totalpq" type="hidden" id="totalpq" value="01" />


          <input name="contadorpq" type="hidden" id="contadorpq" value="<?=$tpq ?>" />


    </span></td>


  </tr>


  <tr>


    <td><div align="left" style="overflow:auto;height:170px; width:680px">


      <table width="578" id="detalle3" border="0" cellpadding="0" cellspacing="0">


      </table>


    </div></td>


  </tr>


  <tr>


    <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">


      <tr>


        <td width="107" align="right"><div id="d_atraspq" class="ebtn_atraz" onclick="mostrarPaquete('atras');"></div></td>


        <td width="302" align="center"><strong>Total Paquete:


          <input name="mostrarpq" class="Tablas" type="hidden" id="mostrarpq" style="width:70px; text-align:center; border:none" value="30" />


                <input name="mostrarpq2" class="Tablas" type="text" id="mostrarpq2" style="width:90px; text-align:center; background-color:#FFFF99;" value="<?=$tpq; ?>" />


        </strong></td>


        <td width="91" align="left"><div id="d_sigpq" <? if($tpq=="0" || $tpq==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarPaquete('adelante');"></div></td>


      </tr>


    </table></td>


  </tr>


  <tr>


    <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">


      <tr>


        <td width="107" align="right">&nbsp;</td>


        <td width="302" align="center"><strong>Total General:   


            <input name="motrartotal" class="Tablas" type="text" id="motrartotal" style="width:90px; text-align:center; background-color:#FFFF99;"  value="<?=$total = $tdes + $tkg + $tpq; ?>"/>


        </strong></td>


        <td width="91" align="left">&nbsp;</td>


      </tr>


    </table></td>


  </tr>


  <tr>


    <td align="right"><table width="80" align="center">


      <tr>


        <td width="72" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresionPaquetes', 300, 230, 'ventana', 'Busqueda')"></div></td>


      </tr>


    </table></td>


  </tr>


</table>


<p>&nbsp;</p>


</form>


</body>


</html>