<?





$tdes=0;





?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">





<html xmlns="http://www.w3.org/1999/xhtml">





<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />





<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>





<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />





<title>Reporte Ventas</title>





<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />





<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />





<link href="../Tablas.css" rel="stylesheet" type="text/css">





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





.Estilo4 {font-size: 12px}





.Balance {background-color: #FFFFFF; border: 0px none}





.Balance2 {background-color: #DEECFA; border: 0px none;}





#form1 table tr td table tr td div {





	text-align: right;





}





#form1 table tr td #txtDir table tr td {





	text-align: center;





}





#form1 table tr td #txtDir table {





	text-align: center;





}





-->





</style>





<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>





<script src="../../javascript/ClaseTabla.js"></script>





<script src="../../javascript/ajax.js"></script>





<script src="../../javascript/funciones.js"></script>





<script language="javascript1.1" src="../../javascript/funcionesDrag.js"></script>





<script language="javascript1.1" src="../../javascript/ClaseMensajes.js"></script>





<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>





<script src="../../javascript/ajaxlist/ajax.js"></script>





<script src="../../javascript/ClaseTabs.js"></script>





<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>





<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>





<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>





<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">





<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">





<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>





<script>





	var tabla1 	= new ClaseTabla();





	var	u		= document.all;





	var inicio		= 30;





	var sepasods	= 0;





	var mens = new ClaseMensajes();





	mens.iniciar('../../javascript',true);





	tabla1.setAttributes({





		nombre:"detalle",





		campos:[





			{nombre:"FOLIOBITACORA", medida:4,tipo:"oculto", alineacion:"center", datos:"foliobitacora"},





			//{nombre:"RANGO FECHA", medida:130, alineacion:"center", datos:"rangofecha"},





			{nombre:"FECHA", medida:70, alineacion:"left", datos:"fecha"},





			{nombre:"RUTA", medida:70, onDblClick:"Ruta",alineacion:"center",  datos:"ruta"},





			{nombre:"UNIDAD", medida:100,onDblClick:"Unidad", alineacion:"center",  datos:"unidad"},





			{nombre:"IDOPERADOR", medida:4,tipo:"oculto", alineacion:"left",  datos:"idoperador"},





			{nombre:"OPERADOR", medida:135,onDblClick:"Operador",alineacion:"left",  datos:"operador"},





			{nombre:"IDGUIA", medida:4,tipo:"oculto", alineacion:"left",  datos:"idguia"},	





			{nombre:"GUIAS", medida:70, onDblClick:"Guias",alineacion:"center",  datos:"guia"},	





			{nombre:"RECORRIDO", medida:70, alineacion:"center", datos:"recorrido"},





			{nombre:"ESTADO", medida:70, alineacion:"left",  datos:"estado"},		





			{nombre:"INCIDENCIAS", medida:70,onDblClick:"Incidencias", alineacion:"center", datos:"incidencias"}





		],





		filasInicial:30,





		alto:265,





		seleccion:true,





		ordenable:false,





		nombrevar:"tabla1"





	});




	jQuery(function($){
			$('#fecha2').mask("99/99/9999");
			$('#fecha').mask("99/99/9999");
	});
	





	window.onload = function(){





		





		tabla1.create();





		obtenerFecha();





		u.d_atrasdes.style.visibility = "hidden";





		if(parseInt(u.mostrardes2.value) <= 30){





			u.d_sigdes.style.visibility  = "hidden";





		}





		





		parent.tabs.agregarTabs('DESCRIPCION DE RUTA',1,"ruta.php?folio=0&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=1");





		parent.document.all.barratabs_contenedor_id1.disabled=true;	





		





		parent.tabs.agregarTabs("UNIDADES",2,"unidad.php?foliobitacora=0&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=1");





		parent.document.all.barratabs_contenedor_id2.disabled=true;	





		





		parent.tabs.agregarTabs("ESTADISTICAS DEL OPERADOR",3,"operador.php?operador=0&foliobitacora=0&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=1");





		parent.document.all.barratabs_contenedor_id3.disabled=true;	





		





		parent.tabs.agregarTabs("RELACION EMBARQUE CONSOLIDADO",4,"guias.php?foliobitacora=0&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=1");





		parent.document.all.barratabs_contenedor_id4.disabled=true;	





		





		parent.tabs.agregarTabs("INCIDENTES EN RUTA",5,"incidencias.php?foliobitacora=0&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=1");





		parent.document.all.barratabs_contenedor_id5.disabled=true;	





		





		parent.tabs.agregarTabs("REPORTE DAÑOS FALTANTES",6,"RmDanosFaltantes.php?guia=0&fecha="+u.fecha.value+"&fecha2="+u.fecha.value+"&inicio=1");





		parent.document.all.barratabs_contenedor_id6.disabled=true;	





		





		parent.tabs.agregarTabs("REPORTE DE DAÑOS",7,"dano.php?guia=0&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=1");





		parent.document.all.barratabs_contenedor_id7.disabled=true;	





		parent.tabs.seleccionar(0);





	}





	





	function obtenerFecha(){





		consultaTexto("mostrarFecha","logistica_con.php?accion=8&valram="+Math.random());





	}





	function mostrarFecha(datos){





		var objeto = eval("("+convertirValoresJson(datos)+")");





		u.fecha.value 	= objeto.fechaactual;





		u.fecha2.value 	= objeto.fechaactual;





	}





	





	/****/





	function obtenerDetalle(){





		var fecha1 =u.fecha.value.split("/");





		var fecha2 =u.fecha2.value.split("/");





		var f1=new Date(fecha1[1]+"/"+fecha1[0]+"/"+fecha1[2]);





		var f2=new Date(fecha2[1]+"/"+fecha2[0]+"/"+fecha2[2]);





		if(f2<f1) {





			parent.mens.show("A","La fecha fin no puede ser menor","¡Atención!","fecha");





			return false;





		}





		consultaTexto("mostrarcontador","logistica_con.php?accion=10&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&valram="+Math.random());	





	}





	





		





	function mostrarcontador(datos){





		row = datos.split(",");





		tdes = row[0];





		u.mostrardes2.value=tdes;





		u.contadordes.value=tdes;





		u.d_atrasdes.style.visibility = "hidden";





		if(parseInt(u.mostrardes2.value) > 30){





			u.d_sigdes.style.visibility  = "visible";





		}	





		consultaTexto("mostrarDetalle","logistica_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=0");





	}





	





	function mostrarDetalle(datos){





		tabla1.clear();	





		var objeto = eval(convertirValoresJson(datos));





		for(var i=0;i<objeto.length;i++){





			var obj		 	   	= new Object();





			obj.foliobitacora 	= objeto[i].foliobitacora;





			//obj.rangofecha		= objeto[i].fecharango;





			obj.fecha 			= objeto[i].fechabitacora;





			obj.ruta	 	   	= objeto[i].ruta;





			obj.unidad 	  		= objeto[i].unidad;





			obj.idoperador	   	= objeto[i].idoperador;





			obj.operador	   	= objeto[i].operador;





			//obj.idguia  		= objeto[i].idguia;





			obj.guia  			= objeto[i].guias;





			obj.recorrido 	   	= objeto[i].tiemporecorrido;





			obj.estado		   	= objeto[i].estado;





			obj.incidencias	   	= objeto[i].reporte;





			tabla1.add(obj);





		}





	}





	/****/





	





	function Ruta(){





		var r = tabla1.getValSelFromField('foliobitacora','FOLIOBITACORA');	





		//if (r!=""){





			parent.document.all.barratabs_contenedor_id1.disabled=false;	





			parent.document.all.iframe_id1.src="ruta.php?folio="+r+"&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&valram="+Math.random();





			parent.tabs.seleccionar(1);


			parent.cn.agregarDireccion(0);


		//}





	}





	





	function Unidad(){





		var r = tabla1.getValSelFromField('foliobitacora','FOLIOBITACORA');	





		//if (r!=""){





			parent.document.all.barratabs_contenedor_id2.disabled=false;	





			parent.document.all.iframe_id2.src='unidad.php?foliobitacora='+r+"&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&valram="+Math.random();





			parent.tabs.seleccionar(2);	


			parent.cn.agregarDireccion(1);


		//}





	}











	function Operador(){





		var op = tabla1.getValSelFromField('idoperador','IDOPERADOR');





		var b = tabla1.getValSelFromField('foliobitacora','FOLIOBITACORA');	





		//if (op!=""){





			parent.document.all.barratabs_contenedor_id3.disabled=false;	





			parent.document.all.iframe_id3.src='operador.php?operador='+op+"&foliobitacora="+b+"&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&valram="+Math.random();





			parent.tabs.seleccionar(3);


			parent.cn.agregarDireccion(2);


		//}





	}





	





	function Guias(){





		var G = tabla1.getValSelFromField('guia','GUIAS');





		var b = tabla1.getValSelFromField('foliobitacora','FOLIOBITACORA');	





		





		//if (G!=""){





			parent.document.all.barratabs_contenedor_id4.disabled=false;	





			parent.document.all.iframe_id4.src='guias.php?foliobitacora='+b+"&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&valram="+Math.random();





			parent.tabs.seleccionar(4);


			parent.cn.agregarDireccion(3);


		//}





	}





	





	function Incidencias(){





		//var inc = tabla1.getValSelFromField('incidencias','INCIDENCIAS');





		var b = tabla1.getValSelFromField('foliobitacora','FOLIOBITACORA');	


		//if (inc!=""){





			parent.document.all.barratabs_contenedor_id5.disabled=false;	





			parent.document.all.iframe_id5.src='incidencias.php?foliobitacora='+b+"&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&valram="+Math.random();


	


			parent.tabs.seleccionar(5);


			parent.cn.agregarDireccion(4);


		//}





	}





	function tipoImpresion(valor){





		if(valor=="Archivo"){			





			window.open("http://www.pmmentuempresa.com/web/general/logistica/generarExcelLogistica.php?accion=1&titulo=REPORTE POR RUTAS&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&m="+Math.random());			





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





				





				consultaTexto("mostrardetalle2","logistica_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio=0");





			}else{





				if(sepasods!=0){





					u.mostrardes.value = sepasods;





					sepasods = 0;





				}





				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;





				if(parseFloat(u.mostrardes.value) < inicio){





					u.mostrardes.value = inicio;





				}





				consultaTexto("mostrardetalle2","logistica_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio="+u.totaldes.value);





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





				consultaTexto("mostrardetalle2","logistica_con.php?accion=1&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value+"&inicio="+u.totaldes.value);





			}			





		}	





	}





	





	function mostrardetalle2(datos){





		if(datos.indexOf("nada")<0){





			var obj = eval(datos);





			tabla1.setJsonData(obj);





		}		





	}





	





</script>











</head>





<body>





<form id="form1" name="form1" method="post" action="">





<table width="695" border="0" align="center" cellpadding="0" cellspacing="0">





  <tr>





    <td width="702"><table width="426" border="0" cellpadding="0" cellspacing="0">





      <tr>





        <td width="18">De</td>





        <td width="100"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=$fecha ?>" /></td>





        <td width="34"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>





        <td width="17">Al</td>





        <td width="100"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=$fecha2 ?>" /></td>





        <td width="31"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>





        <td width="126"><div class="ebtn_Generar" onclick="obtenerDetalle()"></div></td>


      </tr>





    </table></td>


  </tr>





  <tr>





    <td>


      <table width="426" id="detalle" border="0" cellpadding="0" cellspacing="0">


      </table>





     </td>


  </tr>





  <tr>


    <td align="right"><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">


      <tr>


        <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>


        <td width="302" align="center"><strong><span class="Tablas"><span class="Estilo41">


          <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />


          </span><span class="Estilo41">


          <input name="totaldes" type="hidden" id="totaldes" value="1" />


          </span></span>&nbsp;


          <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />


          <strong><strong>


          <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />


        </strong><span style="color:#FF0000"></span></strong></strong></td>


        <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>


      </tr>


    </table></td>


  </tr>


  <tr>





    <td align="right"><table width="74" align="center">





      <tr>





        <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>


      </tr>





    </table></td>


  </tr>


</table>





</form>





</body>





<script>





	//parent.frames[1].document.getElementById('titulo').innerHTML = 'RUTAS';





</script>





</html>