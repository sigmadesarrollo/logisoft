<?





	session_start();





	require_once('../../Conectar.php');





	$l = Conectarse('webpmm');





	





	$s = "SELECT rdf.guia,DATE_FORMAT(rdf.fecha,'%d/%m/%Y') AS fecha,IF(rdf.dano='0',IF(rdf.faltante='0',NULL,'FALTANTE'),'DAÑO') AS tipoincidente





	FROM reportedanosfaltante rdf 





	INNER JOIN recepcionmercancia rm  ON  rdf.recepcion=rm.folio





	WHERE rm.foliobitacora='".$_GET[foliobitacora]."' /*and DATE(rdf.fecha)





BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'*/ LIMIT 0,30";





$r=mysql_query($s,$l)or die($s); 





	$tdes = mysql_num_rows($r);





	$registros= array();	





	$inicio=$_GET[inicio];





		if (mysql_num_rows($r)>0)





				{





				while ($f=mysql_fetch_object($r))





				{


					$f->tipoincidente=cambio_texto($f->tipoincidente);


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





			{nombre:"GUIA", medida:4, tipo:"oculto",alineacion:"center",  datos:"guia"},





			{nombre:"FECHA", medida:90, alineacion:"center",  datos:"fecha"},					





			{nombre:"TIPO INCIDENTE", medida:565, onDblClick:"Tipo" ,alineacion:"center", datos:"tipoincidente"}





		],





		filasInicial:30,





		alto:290,





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





					obj.guia	 			= objeto[i].guia;





					obj.fecha 				= objeto[i].fecha;





					obj.tipoincidente	 	= objeto[i].tipoincidente;





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


		consultaTexto("mostrardetalle","logistica_con.php?accion=6&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&valram="+Math.random());





	}





	





	function Tipo(){





		var t = tabla1.getValSelFromField('tipoincidente','TIPO INCIDENTE');





		var g = tabla1.getValSelFromField('guia','GUIA');





		if(t==""){return false}





		if(t.indexOf('FALTANTE')!=-1){





		parent.document.all.barratabs_contenedor_id6.disabled=false;	





		parent.document.all.iframe_id6.src='RmDanosFaltantes.php?guia='+g+'&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>';





		parent.tabs.seleccionar(6);	


		parent.cn.agregarDireccion(5);


		





		}else if(t.indexOf('DAÑO')!=-1){





		parent.document.all.barratabs_contenedor_id7.disabled=false;	





		parent.document.all.iframe_id7.src='dano.php?guia='+g+'&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>';





		parent.tabs.seleccionar(7);	


		parent.cn.agregarDireccion(6);


		}





	}





	





	function tipoImpresion(valor){





		if(valor=="Archivo"){			





			window.open("http://www.pmmentuempresa.com/web/general/logistica/generarExcelLogistica.php?accion=6&titulo=INCIDENTES EN RUTA&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>&foliobitacora=<?=$_GET[foliobitacora]?>&valram="+Math.random());





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





				consultaTexto("mostrardetalle2","logistica_con.php?accion=6&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&inicio=0");





			}else{





				if(sepasods!=0){





					u.mostrardes.value = sepasods;





					sepasods = 0;





				}





				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;





				if(parseFloat(u.mostrardes.value) < inicio){





					u.mostrardes.value = inicio;





				}





				consultaTexto("mostrardetalle2","logistica_con.php?accion=6&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&inicio="+u.totaldes.value);





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





			consultaTexto("mostrardetalle2","logistica_con.php?accion=6&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&inicio="+u.totaldes.value);





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


      <td align="right"><table width="339" border="0" align="center" cellpadding="0" cellspacing="0">


        <tr>


          <td width="34" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>


          <td width="190" align="center"><strong><span class="Tablas"><span class="Estilo41">


            <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>" />


            <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />


            </span><span class="Estilo41">


            <input name="totaldes" type="hidden" id="totaldes" value="1" />


            </span></span>&nbsp;


            <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />


            <strong><strong>


            <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />


          </strong><span style="color:#FF0000"></span></strong></strong></td>


          <td width="35" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>


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