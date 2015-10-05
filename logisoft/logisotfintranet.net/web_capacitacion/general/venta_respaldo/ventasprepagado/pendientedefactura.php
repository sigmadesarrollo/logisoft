<? //	echo "pendiente facturar";



	require_once('../../../Conectar.php');



	$l = Conectarse('webpmm');



	if($_GET[cliente]!=""){



		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre



		FROM catalogocliente WHERE id=".$_GET[cliente]."";



		$r = mysql_query($s,$l) or die($s);



		$f = mysql_fetch_object($r);



	}



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



	var	u		= document.all;



	tabla1.setAttributes({



		nombre:"detalle",



		campos:[



			{nombre:"FECHA", medida:50, alineacion:"left", datos:"fecha"},



			{nombre:"ORIGEN", medida:40, alineacion:"center",  datos:"origen"},



			{nombre:"DESTINO", medida:40, alineacion:"center",  datos:"destino"},



			{nombre:"# GUIA", medida:100, alineacion:"center", datos:"guia"},			



			{nombre:"# PAQUETE", medida:60, alineacion:"center",  datos:"cantidad"},



			{nombre:"KILOGRAMOS", medida:60, alineacion:"center",  datos:"kilogramos"},



			{nombre:"VALOR DECLARADO", medida:90, tipo:"moneda", alineacion:"right",  datos:"valordeclarado"},



			{nombre:"FLETE", medida:60, alineacion:"right", tipo:"moneda",  datos:"tflete"},



			{nombre:"EXC. KILOGRAMOS", medida:90, alineacion:"center",  datos:"excedente"},			



			{nombre:"EAD", medida:60, alineacion:"right", tipo:"moneda",  datos:"subdestino"},



			{nombre:"COSTO SEGURO", medida:120, alineacion:"right", tipo:"moneda",  datos:"costoseg"},



			{nombre:"CARGO COMBUSTIBLE", medida:100, alineacion:"right", tipo:"moneda",  datos:"cargocombustible"},



			{nombre:"SUBTOTAL", medida:60, alineacion:"right", tipo:"moneda",  datos:"subtotal"},



			{nombre:"IVA", medida:60, alineacion:"right", tipo:"moneda", datos:"tiva"},



			{nombre:"IVA RETENIDO", medida:90, alineacion:"right", tipo:"moneda",  datos:"ivaretenido"},



			{nombre:"TOTAL", medida:60, alineacion:"right", tipo:"moneda",  datos:"total"}



		],



		filasInicial:14,



		alto:220,



		seleccion:true,



		ordenable:false,



		//eventoDblClickFila:"verRecoleccion()",



		nombrevar:"tabla1"



	});



	window.onload = function(){



		tabla1.create();



		obtenerDetalle();



	}



	



	function obtenerDetalle(){



		consultaTexto("mostrarDetalle","../ventas/consultasVentas.php?accion=6&cliente=<?=$_GET[cliente] ?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>");



	}



	



	function mostrarDetalle(datos){



		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){



		var obj = eval(convertirValoresJson(datos));



		tabla1.setJsonData(obj);



		



		var tot = ""; v_tot = 0;



		



		tot= tabla1.getValuesFromField("total",",").split(",");



	



		for(var i=0;i<tot.length;i++){



			v_tot = parseFloat(tot[i]) + parseFloat(v_tot);		



		}



		u.total.value = v_tot;



		u.total.value = "$ "+numcredvar(u.total.value);



		esNan('total');



		



		}else{



			var obj = new Object();



			obj.fecha = "";



			obj.origen = "";



			obj.destino = "";



			obj.guia = "";



			obj.cantidad = 0;



			obj.kilogramos = 0;



			obj.valordeclarado = 0;



			obj.tflete = 0;



			obj.excedente = 0;



			obj.subdestino = 0;



			obj.costoseg = 0;



			obj.cargocombustible = 0;



			obj.subtotal = 0;



			obj.tiva = 0;



			obj.ivaretenido = 0;



			obj.total = 0;



			tabla1.add(obj);			



		}



	}



	function esNan(caja){	



		if(document.getElementById(caja).value.replace("$ ","").replace(/,/g,"")=="NaN"){



			document.getElementById(caja).value = "";



		}



	}	



	function tipoImpresion(valor){



		if(valor=="Archivo"){



			window.open("http://www.pmmentuempresa.com/web/general/venta/ventaPrepagadasPendienteExcel.php?accion=6&cliente=<?=$_GET[cliente] ?>&fechaini=<?=$_GET[fechaini] ?>&fechafin=<?=$_GET[fechafin] ?>&m="+Math.random()+"&titulo=(PREPAGADAS) SERVICIOS PENDIENTES DE FACTURAR");



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



<form id="form1" name="form1" method="post" action=""><br>



  <table width="670" border="0" align="center" cellpadding="0" cellspacing="0">



    <tr>



      <td width="61">Cliente:</td>



      <td width="117"><input name="cliente" type="text" class="Tablas" id="cliente" style="width:80px;background:#FFFF99" readonly="" value="<?=$_GET[cliente] ?>"/></td>



      <td width="432"><input name="cliente2" type="text" class="Tablas" id="cliente2" style="width:250px;background:#FFFF99" readonly="" value="<?=$f->nombre ?>"/></td>

    </tr>



<tr>



      <td colspan="3">



          <table width="1000" id="detalle" border="0" cellpadding="0" cellspacing="0">

          </table>



         </td>

    </tr>



<tr>

  <td colspan="3" align="right"><table width="610" border="0" cellspacing="0" cellpadding="0">

    <tr>

      <td width="538" align="right">Total:</td>

      <td width="110"><span class="style31">

        <input name="total" type="text" class="Tablas" id="total" readonly="" style="width:100px; background-color:#FFFF99;" />

      </span></td>

    </tr>

  </table></td>

</tr>

<tr>



      <td colspan="3" align="right"><table width="74" align="center">



        <tr>



          <td width="66" ><div class="ebtn_imprimir" onClick="abrirVentanaFija('../../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda')"></div></td>

        </tr>



      </table></td>

    </tr>

  </table>



</form>



</body>



</html>