<? 	session_start();





	require_once('../../Conectar.php');





	$l = Conectarse('webpmm');

	$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y') AS fechaguia,guia,destino,cliente,nombrecliente,valorfleteneto, estado FROM 
(

	SELECT gv.fecha AS fechaguia,gv.id AS guia, 

	cs.descripcion AS destino,cc.id AS cliente,

	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,

	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, gv.estado

	FROM guiasventanilla gv

	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)

	INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino

	WHERE gv.idvendedorconvenio='" .$_GET[idvendedor]."' AND gv.estado<>'CANCELADO' AND gv.fecha BETWEEN 

	'" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'





UNION ALL





	SELECT gv.fecha AS fechaguia,gv.id AS guia, 





	cs.descripcion AS destino,cc.id AS cliente,





	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,





	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto,gv.estado FROM guiasempresariales gv





	INNER JOIN catalogocliente cc ON cc.id  = IF(gv.tipoflete=0,gv.idremitente,gv.iddestino)





	INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino





	WHERE gv.idvendedorconvenio='" .$_GET[idvendedor]."' AND gv.estado<>'CANCELADO' AND gv.fecha BETWEEN 





	'" .cambiaf_a_mysql($_GET[fecha])."' and 





	'".cambiaf_a_mysql($_GET[fecha2])."' 





)guiasventanillayempresariales";





	$r=mysql_query($sql,$l)or die($sql); 





	$tdes = mysql_num_rows($r);





	$registros= array();





	





	$vendedor=$_GET[vendedor];





	$ano=$_GET[ano];





	$mes=$_GET[mes];





	$idvendedor=$_GET[idvendedor];





	$inicio=$_GET[inicio];





		





		if (mysql_num_rows($r)>0)





				{





				while ($f=mysql_fetch_object($r))





				{





					$f->nombrecliente=cambio_texto($f->nombrecliente);





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





<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.css?random=20051112"></LINK>





<SCRIPT type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.js?random=20060118"></script>





<script src="../../javascript/ClaseTabla.js"></script>





<link href="../../estilos_estandar.css" />





<script src="../../javascript/ajax.js"></script>





<script language="javascript" src="../../javascript/funcionesDrag.js"></script>





<script language="javascript" src="../../javascript/ClaseMensajes.js"></script>





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





			{nombre:"FECHA", medida:60, alineacion:"left", datos:"fechaguia"},





			{nombre:"GUIA", medida:80, alineacion:"center",  datos:"guia"},





			{nombre:"DESTINO", medida:60, alineacion:"left",  datos:"destino"},





			{nombre:"# CLIENTE", medida:50, alineacion:"center",  datos:"cliente"},





			{nombre:"NOMBRE DEL CLIENTE", medida:200, alineacion:"left",  datos:"nombrecliente"},





			{nombre:"VALOR DEL FLETE NETO", medida:120, tipo:"moneda" ,alineacion:"center",  datos:"valorfleteneto"},			





			{nombre:"STATUS", medida:80, alineacion:"left", datos:"estado"}





		],





		filasInicial:30,





		alto:230,





		seleccion:true,





		ordenable:false,





		nombrevar:"tabla1"





	});





	





	window.onload = function(){





		tabla1.create();





		mostrardetalle('<?=$datos ?>');





		u.vendedor.value='<?=$vendedor ?>' ;



		

		u.ano.value='<?=$ano ?>' ;





		u.meses.value='<?=$mes ?>' ;





		u.clavevendedor.value='<?=$idvendedor ?>';





		u.d_atrasdes.style.visibility = "hidden";





		if(parseInt(u.mostrardes2.value) <= 30){





			u.d_sigdes.style.visibility  = "hidden";





		}





	}





	





		function mostrardetalle(datos){	





		if (datos!=0) {





				$total=0;





				tabla1.clear();





				var objeto = eval(convertirValoresJson(datos));





				for(var i=0;i<objeto.length;i++){





					var obj		 	   	= new Object();





					obj.fechaguia 			= objeto[i].fechaguia;





					obj.guia		 	   	= objeto[i].guia;





					obj.destino   			= objeto[i].destino;





					obj.cliente				= objeto[i].cliente;





					obj.nombrecliente		= objeto[i].nombrecliente;





					obj.valorfleteneto		= objeto[i].valorfleteneto;





					obj.estado				= objeto[i].estado;





					$total += parseFloat(objeto[i].valorfleteneto);





					tabla1.add(obj);





				}	





				u.total.value=convertirMoneda($total);





			}else{





				if (u.inicio.value!="1"){





				tabla1.clear();





				u.total.value=0;





				mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");





				}





				





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





	





	function ObtenerDetalle(){





			u.total.value=0.00;





			tabla1.clear();





			





			consultaTexto("mostrarcontador","principal_con.php?accion=7&mes="+u.meses.value+"&ano="+u.ano.value+"&clavevendedor="+u.clavevendedor.value);





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





			consultaTexto("mostrardetalle","principal_con.php?accion=2&mes="+u.meses.value+"&ano="+u.ano.value+"&clavevendedor="+u.clavevendedor.value+"&inicio=0");





	}





	





	function tipoImpresion(valor){





		if(valor=="Archivo"){





			window.open("http://www.pmmentuempresa.com/web/general/vendedores/generarExcelPorVendedor.php?accion=2&titulo=GENERADOS POR CONVENIO&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>&ano=<?=$_GET[ano] ?>&mes="+((u.meses.value==<?=$_GET[mes] ?>)?<?=$_GET[mes] ?>:u.meses.value)+"&vendedor=<?=$_GET[vendedor] ?>&idvendedor=<?=$_GET[idvendedor] ?>&nombremes="+u.meses.options[u.meses.selectedIndex].text+"&cambiomes="+((u.meses.value==<?=$_GET[mes] ?>)?0:1));





			





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





				





			consultaTexto("mostrardetalle2","principal_con.php?accion=2&mes="+u.meses.value+"&ano="+u.ano.value+"&clavevendedor="+u.clavevendedor.value+"&inicio=0");





					





			}else{





				if(sepasods!=0){





					u.mostrardes.value = sepasods;





					sepasods = 0;





				}





				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;





				if(parseFloat(u.mostrardes.value) < inicio){





					u.mostrardes.value = inicio;





				}





				





				consultaTexto("mostrardetalle2","principal_con.php?accion=2&mes="+u.meses.value+"&ano="+u.ano.value+"&clavevendedor="+u.clavevendedor.value+"&inicio="+u.totaldes.value);





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





					consultaTexto("mostrardetalle2","principal_con.php?accion=2&mes="+u.meses.value+"&ano="+u.ano.value+"&clavevendedor="+u.clavevendedor.value+"&inicio="+u.totaldes.value);





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





<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>





<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>





<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>





<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">





<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">





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





<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>





<body>





<form id="form1" name="form1" method="post" action="">





<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">





  <tr>





    <td width="881"><table width="428" border="0" cellpadding="0" cellspacing="0">





      <tr>





        <td>Mes:</td>





        <td><select name="meses" id="meses" style="width:150px">
          <option value="01">Enero</option>
          <option value="02">Febrero</option>
          <option value="03">Marzo</option>
          <option value="04">Abril</option>
          <option value="05">Mayo</option>
          <option value="06">Junio</option>
          <option value="07">Julio</option>
          <option value="08">Agosto</option>
          <option value="09">Septiembre</option>





          <option value="10">Octubre</option>





          <option value="11">Noviembre</option>





          <option value="12">Diciembre</option>





        </select>





              <strong><strong><span class="Tablas"><span class="Estilo4">





              <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />





              </span><span class="Estilo4">





              <input name="totaldes" type="hidden" id="totaldes" value="1" />





              </span></span><strong><span class="Estilo4">





              <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>" />





            </span></strong></strong></strong></td>





        <td><span class="style31"><span class="Estilo6 Tablas">





          <label></label>





          <img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></span></td>


      </tr>





      <tr>





        <td width="54">Vendedor:</td>





        <td width="300"><input name="vendedor" type="text" class="Tablas" id="vendedor" style="width:300px;background:#FFFF99" value="<?=$vendedor ?>





      " readonly=""/></td>





        <td width="74">&nbsp;</td>


      </tr>





    </table></td>


  </tr>





  <tr></tr>





  <tr>





    <td width="881">





      <table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">


      </table>    </td>


  </tr>





  <tr>


    <td><table width="552" height="16" border="0" cellpadding="0" cellspacing="0">


      <tr>


        <td width="3">&nbsp;</td>


        <td width="161"><div align="right"></div></td>


        <td width="118" align="center"><div align="right">Total General: </div></td>


        <td width="95" align="center"><div align="right"><strong><strong>


            <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />


        </strong></strong></div></td>


        <td width="202" align="center"><div align="left">


            <input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>





                " readonly="" align="right" />


        </div></td>


      </tr>


    </table></td>


  </tr>


  <tr>


    <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">


      <tr>


        <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>


        <td width="302" align="center"><strong>&nbsp;


              <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />


              <strong><span style="color:#FF0000"><a href="../menu/webministator.php">


              <input name="ano" type="hidden" id="ano" />


              <input name="clavevendedor" type="hidden" id="clavevendedor" />


              </a></span><strong><a href="../menu/webministator.php"></a></strong></strong></strong></td>


        <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>


      </tr>


    </table></td>


  </tr>


  





  <tr>





    <td align="right"><table width="74" align="center">


      <tr>


        <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>


      </tr>


    </table>


      </td>


  </tr>


</table>





</form>





</body>





</html>