<?

		require_once('../../../Conectar.php');	

		$l = Conectarse('webpmm');	

		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre	

		FROM catalogocliente WHERE id = ".$_GET[cliente]."";	

		$r = mysql_query($s,$l) or die($s);	

		$f = mysql_fetch_object($r);	

	?>	

	<html xmlns="http://www.w3.org/1999/xhtml">	

	<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />	

	<script src="../../../javascript/ClaseTabla.js"></script>	

	<script src="../../../javascript/ajax.js"></script>	

	<script src="../../../javascript/funciones.js"></script>	

	<script>	

		var tabla1 		= new ClaseTabla();	

		var tabla2 		= new ClaseTabla();	

		var	u			= document.all;	

		tabla1.setAttributes({	

			nombre:"detalle",	

			campos:[	

				{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecha"},	

				{nombre:"GUIA", medida:80, alineacion:"center",  datos:"guia"},	

				{nombre:"DESTINO", medida:50, alineacion:"center",  datos:"sucursal"},	

				{nombre:"CLIENTE ORIGEN/DESTINO", medida:150, alineacion:"left", datos:"cliente"},				

				{nombre:"FLETE", medida:50, alineacion:"center",  datos:"flete"},	

				{nombre:"ENVIO", medida:50, alineacion:"center",  datos:"envio"},	

				{nombre:"PAQUETES", medida:50, alineacion:"center",  datos:"paquete"},	

				{nombre:"KILOGRAMOS", medida:50, alineacion:"center",  datos:"kilogramos"},	

				{nombre:"TOTAL", medida:50, tipo:"moneda", alineacion:"right",  datos:"total"},	

				{nombre:"ESTADO", medida:100, alineacion:"center",  datos:"estado"},	

				{nombre:"QUIEN RECIBIO", medida:150, alineacion:"center",  datos:"recibio"},	

				{nombre:"DIAS PARA ENTREGA", medida:100, alineacion:"center",  datos:"diasentrega"}	

			],	

			filasInicial:11,	

			alto:180,	

			seleccion:true,	

			ordenable:false,	

			//eventoDblClickFila:"verRecoleccion()",	

			nombrevar:"tabla1"	

		});	

		tabla2.setAttributes({	

			nombre:"detalle2",	

			campos:[	

				{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecha"},	

				{nombre:"GUIA", medida:80, alineacion:"center",  datos:"guia"},	

				{nombre:"DESTINO", medida:50, alineacion:"center",  datos:"sucursal"},	

				{nombre:"CLIENTE ORIGEN/DESTINO", medida:150, alineacion:"left", datos:"cliente"},				

				{nombre:"FLETE", medida:50, alineacion:"center",  datos:"flete"},	

				{nombre:"ENVIO", medida:50, alineacion:"center",  datos:"envio"},	

				{nombre:"PAQUETES", medida:50, alineacion:"center",  datos:"paquete"},	

				{nombre:"KILOGRAMOS", medida:50, alineacion:"center",  datos:"kilogramos"},	

				{nombre:"TOTAL", medida:50, tipo:"moneda", alineacion:"right",  datos:"total"},	

				{nombre:"ESTADO", medida:100, alineacion:"center",  datos:"estado"},	

				{nombre:"QUIEN RECIBIO", medida:150, alineacion:"center",  datos:"recibio"},	

				{nombre:"DIAS PARA ENTREGA", medida:100, alineacion:"center",  datos:"diasentrega"}	

			],	

			filasInicial:11,	

			alto:180,	

			seleccion:true,	

			ordenable:false,	

			//eventoDblClickFila:"verRecoleccion()",	

			nombrevar:"tabla2"	

		});	

			

		window.onload = function(){	

			tabla1.create();	

			tabla2.create();	

			obtenerDetalle();	

		}	

		function obtenerDetalle(){	

			consultaTexto("mostrarDetalle","consultasVentas.php?accion=10&cliente=<?=$_GET[cliente];?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&guia=<?=$_GET[guia] ?>&m="+Math.random());	

		}	

		function mostrarDetalle(datos){	

			if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){			

			var obj = eval(convertirValoresJson(datos));	

			tabla1.setJsonData(obj);	

				

			u.rguia.value = tabla1.getRecordCount();	

				

			var vig = ""; var ven = ""; var tot = "";	

			v_vig = 0; v_ven = 0; v_tot = 0; 	

				

			vig = tabla1.getValuesFromField("paquete",",").split(",");	

			ven = tabla1.getValuesFromField("kilogramos",",").split(",");	

			tot = tabla1.getValuesFromField("total",",").split(",");	

			

			for(var i=0;i<vig.length;i++){	

				v_vig = parseFloat(vig[i]) + parseFloat(v_vig);	

			}	

			u.rpaquete.value = v_vig;			

			esNan('rpaquete');	

			for(var i=0;i<ven.length;i++){	

				v_ven = parseFloat(ven[i]) + parseFloat(v_ven);			

			}	

			u.rkilos.value = v_ven;			

			esNan('rkilos');	

			for(var i=0;i<tot.length;i++){	

				v_tot = parseFloat(tot[i]) + parseFloat(v_tot);			

			}	

			u.rtotal.value = v_tot;	

			u.rtotal.value = "$ "+numcredvar(u.rtotal.value);	

			esNan('rtotal');	

				

			consultaTexto("mostrarDetalle2","consultasVentas.php?accion=11&cliente=<?=$_GET[cliente];?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&m="+Math.random());	

			}	

		}	

		

		function mostrarDetalle2(datos){	

			if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){	

			var obj = eval(convertirValoresJson(datos));	

			tabla2.setJsonData(obj);	

				

			u.dguia.value = tabla2.getRecordCount();	

				

			var vig = ""; var ven = ""; var tot = ""; var guias = "";	

			v_vig = 0; v_ven = 0; v_tot = 0; v_guias = 0;	

				

			//guias = tabla2.getValuesFromField("guia",",").split(",");	

			vig = tabla2.getValuesFromField("paquete",",").split(",");	

			ven = tabla2.getValuesFromField("kilogramos",",").split(",");	

			tot = tabla2.getValuesFromField("total",",").split(",");	

			

			//u.dguia.value = guias.length;	

				

			for(var i=0;i<vig.length;i++){	

				v_vig = parseFloat(vig[i]) + parseFloat(v_vig);	

			}	

			u.dpaquete.value = v_vig;			

			esNan('dpaquete');	

				

			for(var i=0;i<ven.length;i++){	

				v_ven = parseFloat(ven[i]) + parseFloat(v_ven);			

			}	

			u.dkilos.value = v_ven;			

			esNan('dkilos');	

				

			for(var i=0;i<tot.length;i++){	

				v_tot = parseFloat(tot[i]) + parseFloat(v_tot);	

			}	

			u.dtotal.value = v_tot;	

			u.dtotal.value = "$ "+numcredvar(u.dtotal.value);	

			esNan('dtotal');	

				

			u.tguia.value = parseInt(u.rguia.value) + parseInt(u.dguia.value);	

			u.tpaquete.value = parseFloat(u.rpaquete.value.replace("$ ","").replace(/,/g,"")) + parseFloat(u.dpaquete.value.replace("$ ","").replace(/,/g,""));	

			esNan('tpaquete');	

			u.tkilos.value = parseFloat(u.rkilos.value.replace("$ ","").replace(/,/g,"")) + parseFloat(u.dkilos.value.replace("$ ","").replace(/,/g,""));	

			esNan('tkilos');	

			u.ttotal.value = parseFloat(u.rtotal.value.replace("$ ","").replace(/,/g,"")) + parseFloat(u.dtotal.value.replace("$ ","").replace(/,/g,""));	

			esNan('ttotal');	

			u.ttotal.value = "$ "+numcredvar(u.ttotal.value);	

			}	

		}	

			

		function esNan(caja){	

			if(document.getElementById(caja).value.replace("$ ","").replace(/,/g,"")=="NaN"){	

				document.getElementById(caja).value = "";	

			}	

		}	

		function tipoImpresion(valor){	

			if(valor=="Archivo"){	

				window.open("http://www.pmmentuempresa.com/web/general/venta/ventaTotalExcel.php?accion=11&sucursal=<?=$_GET[sucursal]; ?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&cliente=<?=$_GET[cliente] ?>&guia=<?=$_GET[guia] ?>&m="+Math.random()+"&titulo=REPORTE DE ENVIOS POR CLIENTE");	

			}	

		}	

	</script>	

	<script src="../../../javascript/ventanas/js/ventana-modal-1.3.js"></script>	

	<script src="../../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>	

	<script src="../../../javascript/ventanas/js/abrir-ventana-fija.js"></script>	

	<link href="../../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">	

	<link href="../../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">	

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	

	<title>Documento sin t&iacute;tulo</title>	

	<link href="../../../FondoTabla.css" rel="stylesheet" type="text/css" />	

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

	<link href="../../../estilos_estandar.css" rel="stylesheet" type="text/css" />	

	<style type="text/css">	

	<!--	

	.Estilo4 {font-size: 12px}	

	.Balance {background-color: #FFFFFF; border: 0px none}	

	.Balance2 {background-color: #DEECFA; border: 0px none;}	

	-->	

	</style>	

	<link href="../Tablas.css" rel="stylesheet" type="text/css">	

	</head>	

	<body>	

	<form id="form1" name="form1" method="post" action="">	

	  <br>	

	  <table width="670" border="0" align="center" cellpadding="0" cellspacing="0">	

	    <tr>	

	      <td><table width="424" border="0" cellpadding="0" cellspacing="0">	

	          <tr>	

	            <td width="39">Cliente:</td>	

	            <td width="97"><input name="cliente" type="text" class="Tablas" id="cliente" style="width:80px;background:#FFFF99" readonly="" value="<?=$_GET[cliente] ?>"/></td>	

	            <td width="288"><input name="nombre" type="text" class="Tablas" id="nombre" style="width:300px;background:#FFFF99" readonly="" value="<?=$f->nombre ?>"/></td>	

	          </tr>	

	      </table></td>	

	    </tr>	

	    <tr>	

	      <td width="426"><table width="426" border="0" cellpadding="0" cellspacing="0">	

	          <tr>	

	            <td width="39">Fecha:</td>	

	            <td width="108"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" readonly="" value="<?=$_GET[fechaini] ?>"/></td>	

	            <td width="16">Al:</td>	

	            <td width="263"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px;background:#FFFF99" readonly="" value="<?=$_GET[fechafin] ?>"/></td>	

	          </tr>	

	      </table></td>	

	    </tr>	

	  <td width="426">	

	  	

	<tr>	

	    <td><span class="style31">	

	      <input name="lugar" type="text" class="style2" id="totall" readonly="" style="font-size:8px; width:50px; font:tahoma; font-weight:bold" />	

	    </span></td>	

	  </tr>	

	  <tr>	

	    <td>Enviado</td>	

	  </tr>	

	  <tr>	

	    <td><div id="txtDir" style=" height:200px; width:670px; overflow:auto" align=left>	

	      <table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">	

	      </table>	

	    </div></td>	

	  </tr>	

	  <tr>	

	    <td><table width="661" border="0" cellspacing="0" cellpadding="0">	

	      <tr>	

	        <td width="66">Total Guias: </td>	

	        <td width="100"><span class="style31">	

	          <input name="rguia" type="text" class="Tablas" id="rguia" readonly="" style="width:100px; background-color:#FFFF99; text-align:center" />	

	        </span></td>	

	        <td width="76">T. Paquetes: </td>	

	        <td width="72"><span class="style31">	

	          <input name="rpaquete" type="text" class="Tablas" id="rpaquete" readonly="" style="width:100px; background-color:#FFFF99; text-align:center" />	

	        </span></td>	

	        <td width="79">T. Kilogramos: </td>	

	        <td width="64"><span class="style31">	

	          <input name="rkilos" type="text" class="Tablas" id="rkilos" readonly="" style="width:100px;background-color:#FFFF99; text-align:center" />	

	        </span></td>	

	        <td width="50">Total:</td>	

	        <td width="69"><div align="left"><span class="style31">	

	          <input name="rtotal" type="text" class="Tablas" id="rtotal" readonly="" style="width:100px;background-color:#FFFF99; text-align:right" />	

	        </span></div></td>	

	      </tr>	

	    </table></td>	

	  </tr>	

	  <tr>	

	    <td>&nbsp;</td>	

	  </tr>	

	  <tr>	

	    <td>Recibido</td>	

	  </tr>	

	  <tr>	

	    <td></td>	

	  </tr>	

	  <tr>	

	    <td><div id="txtDir" style=" height:200px; width:670px; overflow:auto" align=left>	

	      <table width="578" id="detalle2" border="0" cellpadding="0" cellspacing="0">	

	      </table>	

	    </div></td>	

	  </tr>	

	  <tr>	

	    <td><table width="666" border="0" cellspacing="0" cellpadding="0">	

	      <tr>	

	        <td width="66">Total Guias: </td>	

	        <td width="100"><span class="style31">	

	          <input name="dguia" type="text" class="Tablas" id="dguia" readonly="" style="width:100px;background-color:#FFFF99; " />	

	        </span></td>	

	        <td width="76">T. Paquetes: </td>	

	        <td width="72"><span class="style31">	

	          <input name="dpaquete" type="text" class="Tablas" id="dpaquete" readonly="" style="width:100px;background-color:#FFFF99;" />	

	        </span></td>	

	        <td width="79">T. Kilogramos: </td>	

	        <td width="64"><span class="style31">	

	          <input name="dkilos" type="text" class="Tablas" id="dkilos" readonly="" style="width:100px;background-color:#FFFF99; text-align:center" />	

	        </span></td>	

	        <td width="48">Total:</td>	

	        <td width="71"><div align="left"><span class="style31">	

	          <input name="dtotal" type="text" class="Tablas" id="dtotal" readonly="" style="width:100px; background-color:#FFFF99; text-align:right" />	

	        </span></div></td>	

	      </tr>	

	    </table></td>	

	  </tr>	

	  <tr>	

	    <td><table width="667" border="0" cellspacing="0" cellpadding="0">	

	<tr>	

	        <td width="66" height="27">Total Guias: </td>	

	        <td width="100"><span class="style31">	

	          <input name="tguia" type="text" class="Tablas" id="tguia" readonly="" style="width:100px;background-color:#FFFF99;" />	

	        </span></td>	

	        <td width="76">T. Paquetes: </td>	

	        <td width="72"><span class="style31">	

	          <input name="tpaquete" type="text" class="Tablas" id="tpaquete" readonly="" style="width:100px;background-color:#FFFF99;" />	

	        </span></td>	

	        <td width="79">T. Kilogramos: </td>	

	        <td width="64"><span class="style31">	

	          <input name="tkilos" type="text" class="Tablas" id="tkilos" readonly="" style="width:100px;background-color:#FFFF99; text-align:center" />	

	        </span></td>	

	        <td width="49">Total:</td>	

	        <td width="70"><span class="style31">	

	          <input name="ttotal" type="text" class="Tablas" id="ttotal" readonly="" style="width:100px; background-color:#FFFF99; text-align:right" />	

	        </span></td>	

      </tr>



    </table></td>



  </tr>



<tr>



    <td align="right"><table width="74" align="center">



      <tr>



        <td width="66" ><div class="ebtn_imprimir" onClick="abrirVentanaFija('../../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>



      </tr>



    </table></td>



  </tr>



  </table>



  <p><label></label>



  </p>



</form>



</body>



</html>