<? 	session_start();





	require_once('../../Conectar.php');





	$l = Conectarse('webpmm');





	





	$vendedor=$_GET[vendedor];





	$ano=$_GET[ano];





	$mes=$_GET[mes];





	$idvendedor=$_GET[clavevendedor];





	$inicio=$_GET[inicio];











$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y')AS fechaguia,guia,cliente,nombrecliente,valorfleteneto,comision  FROM 





(





	SELECT gv.fecha AS fechaguia,gv.id AS guia,cc.id AS cliente,





	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,





	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, 





	(IFNULL(IFNULL(gv.tflete-gv.ttotaldescuento+gv.texcedente,0)*(com.comision/100),0))AS comision  





	FROM guiasventanilla gv





	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)





	INNER JOIN 





	(SELECT ld.factura FROM liquidacioncobranza l 





	INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 





	WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 





	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'





	GROUP BY ld.factura)ld ON gv.factura=ld.factura





	LEFT JOIN





	(SELECT cliente,comision FROM 





	(SELECT cc.id AS cliente, CASE  





	WHEN gc.tipoautorizacion='EN AUTORIZACION (ok)' THEN 





		(IFNULL(IF ((DATEDIFF(CURRENT_DATE,cc.fechainicioconvenio)/365)





		>(SELECT despues FROM configuradorpromociones),





		(SELECT porcentaje FROM configuradorpromociones),





		CASE cc.tipoclientepromociones





		WHEN 'A' THEN (SELECT porcA FROM configuradorpromociones) WHEN 'B' THEN (SELECT porcB FROM configuradorpromociones)END),0))





	WHEN gc.tipoautorizacion='EN AUTORIZACION (x)' THEN 





		(SELECT porcentaje FROM configuradorpromociones)





	END AS comision FROM catalogocliente cc 





	INNER JOIN generacionconvenio gc ON cc.id=gc.idcliente





	WHERE gc.vendedor='" .$_GET[clavevendedor]."'





	)comisiones WHERE cliente<>0 AND comision<>0 GROUP BY cliente ORDER BY cliente)com ON cc.id=com.cliente	





	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."'  AND gv.estado<>'CANCELADO'





	AND gv.fecha 





	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'





UNION ALL





	SELECT gv.fecha AS fechaguia,gv.id AS guia,cc.id AS cliente,





	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,





	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, 





	(IFNULL(IFNULL(gv.tflete-gv.ttotaldescuento+gv.texcedente,0)*(com.comision/100),0))AS comision  





	FROM guiasempresariales gv





	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)





	INNER JOIN 





	(SELECT ld.factura FROM liquidacioncobranza l 





	INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 





	WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 





	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'





	GROUP BY ld.factura)ld ON gv.factura=ld.factura





	LEFT JOIN





	(SELECT cliente,comision FROM 





	(SELECT cc.id AS cliente, CASE  





	WHEN gc.tipoautorizacion='EN AUTORIZACION (ok)' THEN 





		(IFNULL(IF ((DATEDIFF(CURRENT_DATE,cc.fechainicioconvenio)/365)





		>(SELECT despues FROM configuradorpromociones),





		(SELECT porcentaje FROM configuradorpromociones),





		CASE cc.tipoclientepromociones





		WHEN 'A' THEN (SELECT porcA FROM configuradorpromociones) WHEN 'B' THEN (SELECT porcB FROM configuradorpromociones)END),0))





	WHEN gc.tipoautorizacion='EN AUTORIZACION (x)' THEN 





		(SELECT porcentaje FROM configuradorpromociones)





	END AS comision FROM catalogocliente cc 





	INNER JOIN generacionconvenio gc ON cc.id=gc.idcliente





	WHERE gc.vendedor='" .$_GET[clavevendedor]."')comisiones WHERE cliente<>0 AND comision<>0 GROUP BY cliente ORDER BY cliente)





	com ON cc.id=com.cliente





	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."' AND gv.estado<>'CANCELADO'





	AND gv.fecha 





	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."')GuiasVentanillayGeren";





	$r=mysql_query($sql,$l)or die($sql); 





	





	$tdes = mysql_num_rows($r);





	$registros= array();





		





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

















<script src="../../javascript/shortcut.js"></script>





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





			{nombre:"FECHA", medida:80, alineacion:"center", datos:"fechaguia"},





			{nombre:"GUIA", medida:80, alineacion:"left",  datos:"guia"},





			{nombre:"# CLIENTE", medida:50, alineacion:"center",  datos:"cliente"},





			{nombre:"NOMBRE DEL CLIENTE", medida:200, alineacion:"left",  datos:"nombrecliente"},





			{nombre:"VALOR DEL FLETEO NETO", medida:150, tipo:"moneda", alineacion:"center",  datos:"valorfleteneto"},			





			{nombre:"COMISION", medida:80,tipo:"moneda",alineacion:"right", datos:"comision"}





		],





		filasInicial:30,





		alto:220,





		seleccion:true,





		ordenable:false,





		//eventoDblClickFila:"verRecoleccion()",





		nombrevar:"tabla1"





	});





		





	window.onload = function(){





		





		tabla1.create();





		





		mostrardetalle('<?=$datos ?>');





		u.vendedor.value='<?=$vendedor ?>';





		u.ano.value='<?=$ano ?>';





		u.meses.value='<?=$mes ?>';





		u.clavevendedor.value='<?=$idvendedor ?>';





		u.d_atrasdes.style.visibility = "hidden";





		if(parseInt(u.mostrardes2.value) <= 30){





			u.d_sigdes.style.visibility  = "hidden";





		}





	}





	





	function mostrardetalle(datos){	





		if (datos!=0) {





				var total=0;





				var total2=0;





				tabla1.clear();





				var objeto = eval(convertirValoresJson(datos));





				for(var i=0;i<objeto.length;i++){





					var obj		 	   	= new Object();





					obj.fechaguia 			= objeto[i].fechaguia;





					obj.guia		 	   	= objeto[i].guia;





					obj.cliente				= objeto[i].cliente;





					obj.nombrecliente		= objeto[i].nombrecliente;





					obj.valorfleteneto		= objeto[i].valorfleteneto;





					obj.comision			= objeto[i].comision;





					$total += parseFloat(objeto[i].valorfleteneto);





					$total2 += parseFloat(objeto[i].comision);





					tabla1.add(obj);





				}	





				u.total.value=convertirMoneda(total);





				u.total2.value=convertirMoneda(total2);





			}else{





				tabla1.clear();





				u.total.value=0;





				u.total2.value=0;





				if (u.inicio.value!="1"){





					mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!");





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





	   if (u.meses.value=="0"){





			mens.show("A","Debe seleccionar el mes","¡Atención!","meses");





		}else{





			u.total.value=0;





			tabla1.clear();





			consultaTexto("mostrarcontador","principal_con.php?accion=8&ano="+u.ano.value+"&mes="+u.meses.value+"&clavevendedor="+u.clavevendedor.value);





		}





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





		consultaTexto("mostrardetalle","principal_con.php?accion=6&ano="+u.ano.value+"&mes="+u.meses.value+"&clavevendedor="+u.clavevendedor.value+"&inicio=0");





	}





	





	function tipoImpresion(valor){





		if(valor=="Archivo"){





			window.open("http://www.pmmentuempresa.com/web/general/vendedores/generarExcelPorVendedor.php?accion=1&titulo=COBRADAS POR VENDEDOR&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>&vendedor=<?=$_GET[vendedor] ?>&ano=<?=$_GET[ano] ?>&mes="+((u.meses.value==<?=$_GET[mes] ?>)?<?=$_GET[mes] ?>:u.meses.value)+"&clavevendedor=<?=$_GET[clavevendedor] ?>&nombremes="+u.meses.options[u.meses.selectedIndex].text+"&cambiomes="+((u.meses.value==<?=$_GET[mes] ?>)?0:1));			





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





				





				





			consultaTexto("mostrardetalle2","principal_con.php?accion=6&ano="+u.ano.value+"&mes="+u.meses.value+"&clavevendedor="+u.clavevendedor.value+"&inicio=0");





					





			}else{





				if(sepasods!=0){





					u.mostrardes.value = sepasods;





					sepasods = 0;





				}





				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;





				if(parseFloat(u.mostrardes.value) < inicio){





					u.mostrardes.value = inicio;





				}





				





				consultaTexto("mostrardetalle2","principal_con.php?accion=6&ano="+u.ano.value+"&mes="+u.meses.value+"&clavevendedor="+u.clavevendedor.value+"&inicio="+u.totaldes.value);





			





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





				





				consultaTexto("mostrardetalle2","principal_con.php?accion=6&ano="+u.ano.value+"&mes="+u.meses.value+"&clavevendedor="+u.clavevendedor.value+"&inicio="+u.totaldes.value);





	





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





    <td width="494"><table width="452" border="0" cellpadding="0" cellspacing="0">





      <tr>





        <td>Vendedor:</td>





        <td><input name="vendedor" type="text" class="Tablas" id="vendedor" style="width:300px;background:#FFFF99" value="<?=$vendedor ?>





      " readonly=""/></td>





        <td><span class="Estilo6 Tablas"><img id="../../img/Boton_refrescar" src="../../img/Boton_Refrescar.gif" width="74" height="20" align="right" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></td>





      </tr>





      <tr>





        <td width="51">Mes:</td>





        <td width="300"><select name="meses" style="width:150px">
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





        </select></td>





        <td width="101">&nbsp;</td>





      </tr>





    </table>





      </td>





    <td width="213">&nbsp;</td>





  </tr>





  <tr>





    <td colspan="2">





      <table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">





      </table>





    </td>





  </tr>





  <tr>





    <td colspan="2"><table width="559" height="16" border="0" cellpadding="0" cellspacing="0">





      <tr>





        <td width="3">&nbsp;</td>





        <td width="45"><div align="center"></div></td>





        <td width="225" align="center"><div align="right"></div>





              <a href="../menu/webministator.php"></a></td>





        <td width="102" align="center"><div align="right"><strong>Total General:&nbsp;</strong></div></td>





        <td width="106" align="center"><div align="right"><strong><strong>





          <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />





        </strong></strong></div></td>





        <td width="108" align="center"><div align="left">





          <input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>





                " readonly="" align="right" />





        </div></td>





        <td width="112" align="center"><div align="left">





          <input name="total2" type="text" class="Tablas" id="total2" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total2 ?>





                " readonly="" align="right" />





        </div></td>





      </tr>





    </table></td>





  </tr>





  <tr>





    <td colspan="2"><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">





      <tr>





        <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>





        <td width="302" align="center"><strong>





          <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />





          <strong><strong><a href="../menu/webministator.php">


          <input name="ano" type="hidden" id="ano" />


          <input name="clavevendedor" type="hidden" id="clavevendedor" />


          </a><strong><span style="color:#FF0000"><a href="../menu/webministator.php"><span class="Tablas"><span class="Estilo4">


          <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />


          </span><span class="Estilo4">


          <input name="totaldes" type="hidden" id="totaldes" value="1" />


          </span></span></a></span><strong><strong><a href="../menu/webministator.php"><span class="Estilo4">


          <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>" />


          </span></a></strong></strong></strong></strong><span style="color:#FF0000"></span></strong></strong></td>





        <td width="91" align="left"><div id="d_sigdes" <? if($tdes=="0" || $tdes==""){ echo 'style="visibility:hidden"';} ?> class="ebtn_adelante" onclick="mostrarDescuento('adelante');"></div></td>





      </tr>





    </table></td>





  </tr>





  <tr>





    <td colspan="2" align="right"><table width="74" align="center">


      <tr>


        <td width="66" ><div class="ebtn_imprimir" onclick="abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>


      </tr>


    </table>


    </td>


  </tr>





</table>





</form>





</body>





<script>





	//parent.frames[1].document.getElementById('titulo').innerHTML = '';





</script>





</html>