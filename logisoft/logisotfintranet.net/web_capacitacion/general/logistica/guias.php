<?





	session_start();





	require_once('../../Conectar.php');





	$l = Conectarse('webpmm');





	





$s = "(SELECT DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.id AS guia,gv.idsucursaldestino,cs.prefijo AS sucursal,gv.iddestinatario,





CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,COUNT(gv_u.proceso) AS nopaquetes





FROM bitacorasalida bs





INNER JOIN embarquedemercancia em ON bs.folio=em.foliobitacora





INNER JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque





INNER JOIN guiasventanilla gv ON emd.guia=gv.id





INNER JOIN guiaventanilla_detalle gv_d ON gv.id=gv_d.idguia





INNER JOIN catalogosucursal  cs ON gv.idsucursaldestino=cs.id





INNER JOIN catalogocliente cc ON gv.iddestinatario=cc.id





INNER JOIN guiaventanilla_unidades gv_u ON gv.id=gv_u.idguia





WHERE bs.folio='".$_GET[foliobitacora]."'





AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'





GROUP BY gv.id)





UNION





(SELECT DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fecha,gm.id AS guia,gm.idsucursaldestino,cs.prefijo AS sucursal,gm.iddestinatario,





CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,COUNT(gm_u.proceso) AS nopaquetes





FROM bitacorasalida bs





INNER JOIN embarquedemercancia em ON bs.folio=em.foliobitacora





INNER JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque





INNER JOIN guiasempresariales gm ON emd.guia=gm.id





INNER JOIN guiasempresariales_detalle gm_d ON gm.id=gm_d.id





INNER JOIN catalogosucursal  cs ON gm.idsucursaldestino=cs.id





INNER JOIN catalogocliente cc ON gm.iddestinatario=cc.id





INNER JOIN guiasempresariales_unidades gm_u ON gm.id=gm_u.id





WHERE bs.folio='".$_GET[foliobitacora]."'





AND gm.fecha BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'





GROUP BY gm.id)	LIMIT 0,30";





$r=mysql_query($s,$l)or die($s); 





	$tdes = mysql_num_rows($r);





	$registros= array();	





	$inicio=$_GET[inicio];





		if (mysql_num_rows($r)>0)





				{





				while ($f=mysql_fetch_object($r))





				{





					$registros[]=$f;	





				}





			$datos= str_replace('null','""',json_encode($registros));





		}else{





			$datos= str_replace('null','""',json_encode(0));





		}

















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





<script>





	var tabla1 		= new ClaseTabla();





	var	u		= document.all;





	var inicio		= 30;





	var sepasods	= 0;





	var mens = new ClaseMensajes();





	mens.iniciar('../../javascript',true);





	tabla1.setAttributes({





		nombre:"detalle",





		campos:[





			{nombre:"FECHA", medida:100, alineacion:"center",  datos:"fecha"},





			{nombre:"GUIA", medida:100, alineacion:"center", datos:"guia"},





			{nombre:"DESTINO", medida:190, alineacion:"center",  datos:"destino"},					





			{nombre:"DESTINATARIO", medida:170, alineacion:"left", datos:"destinatario"},





			{nombre:"NO. PAQUETES", medida:100, alineacion:"center", datos:"nopaquetes"}





		],





		filasInicial:30,





		alto:300,





		seleccion:true,





		ordenable:false,





		nombrevar:"tabla1"





	});





	





	window.onload = function(){





		tabla1.create();





		mostrardetalle('<?=$datos ?>');





		u.d_atrasdes.style.visibility = "hidden";





		if(parseInt(u.mostrardes2.value) <= 30){





			u.d_sigdes.style.visibility  = "hidden";





		}





	}





	





	function mostrardetalle(datos){	





		if (datos!=0) {





				tabla1.clear();





				var objeto = eval(convertirValoresJson(datos));





				for(var i=0;i<objeto.length;i++){





					var obj		 	   		= new Object();





					obj.fecha 				= objeto[i].fecha;





					obj.guia	 			= objeto[i].guia;





					obj.destino	 	   		= objeto[i].sucursal;





					obj.destinatario   		= objeto[i].nombre;





					obj.nopaquetes			= objeto[i].nopaquetes;





					tabla1.add(obj);





				}	





			}else{





				if (u.inicio.value!="1"){





				tabla1.clear();





				mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");





				}





			}





		}





		





	function obtenerDetalle(){





		consultaTexto("mostrarDetalle","logistica_con.php?accion=5&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&valram="+Math.random());





	}





	





	function mostrarDetalle(datos){	





		var objeto = eval(convertirValoresJson(datos));





		for(var i=0;i<objeto.length;i++){





			var obj		 	   			= new Object();





			obj.fecha		= objeto[i].fecha;





			obj.guia 		= objeto[i].guia;





			obj.destino		= objeto[i].sucursal;





			obj.destinatario= objeto[i].nombre;





			obj.nopaquetes	= objeto[i].nopaquetes;





			tabla1.add(obj);





		}





	}





	





	function tipoImpresion(valor){





		if(valor=="Archivo"){			





			window.open("http://www.pmmentuempresa.com/web/general/logistica/generarExcelLogistica.php?accion=5&titulo=RELACION EMBARQUE CONSOLIDADO&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>&foliobitacora=<?=$_GET[foliobitacora]?>&valram="+Math.random());





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





				





				consultaTexto("mostrardetalle2","logistica_con.php?accion=5&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&inicio=0");





			}else{





				if(sepasods!=0){





					u.mostrardes.value = sepasods;





					sepasods = 0;





				}





				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;





				if(parseFloat(u.mostrardes.value) < inicio){





					u.mostrardes.value = inicio;





				}





			consultaTexto("mostrardetalle2","logistica_con.php?accion=5&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&inicio="+u.totaldes.value);





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





				consultaTexto("mostrardetalle2","logistica_con.php?accion=5&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&inicio="+u.totaldes.value);





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





<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">





  





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


        <td width="302" align="center"><strong>&nbsp; <span class="Tablas"><span class="Estilo41">


          <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>" />


          <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />


          </span><span class="Estilo41">


          <input name="totaldes" type="hidden" id="totaldes" value="1" />


          </span></span>


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





	//parent.frames[1].document.getElementById('titulo').innerHTML = '';





</script>





</html>