<? 	session_start();

	require_once('../../Conectar.php');

	$l = Conectarse('webpmm');

	

	

	$sql="SELECT fechaconvenio,clave,cliente,convenio,mes1,mes2,mes3,total,IFNULL(tipo,'')AS tipo FROM (

SELECT DATE_FORMAT(gc.fecha, '%d/%m/%Y') AS fechaconvenio, cc.id AS clave,

	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,

	IFNULL(gc.cantidaddescuento + gc.consignaciondescuento,0)AS convenio,

	IFNULL(m1.ventas,0) AS mes1,

	IFNULL(m2.ventas,0) AS mes2,

	IFNULL(m3.ventas,0) AS mes3,

	(IFNULL(m1.ventas,0)+IFNULL(m2.ventas,0)+IFNULL(m3.ventas,0)) AS total,

	CASE WHEN gc.precioporkg=1 THEN 'P' 

	WHEN gc.precioporcaja=1 THEN 'K' 

	WHEN  gc.descuentosobreflete=1 THEN 'D' 

	END AS tipo

	FROM generacionconvenio gc

	INNER JOIN catalogocliente cc ON gc.idcliente=cc.id

	LEFT JOIN 

	(SELECT vendedor,cliente,SUM(ventas) AS ventas FROM (

		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 

		FROM guiasventanilla WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH))

		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio

	UNION 

		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 

		FROM guiasempresariales WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH))

		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio

	)ventasguiasventanillayempresariales GROUP BY vendedor,cliente)m1 ON gc.vendedor=m1.vendedor

	LEFT JOIN

	(SELECT vendedor,cliente,SUM(ventas) AS ventas FROM (

		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 

		FROM guiasventanilla WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH))

		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio

	UNION  ALL

		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 

		FROM guiasempresariales WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH))

		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio

	)ventasguiasventanillayempresariales GROUP BY vendedor,cliente)m2 ON gc.vendedor=m2.vendedor

	LEFT JOIN 

	(SELECT vendedor,cliente,SUM(ventas) AS ventas FROM (

		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 

		FROM guiasventanilla WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH))

		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio

	UNION 

		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 

		FROM guiasempresariales WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH))

		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio

	)ventasguiasventanillayempresariales GROUP BY vendedor,cliente)m3 ON gc.vendedor=m3.vendedor AND cc.id=m3.cliente

	WHERE gc.vendedor='" .$_GET[clavevendedor]."'

	)Tabla WHERE total>0 order by fechaconvenio LIMIT 0,30";

	$r=mysql_query($sql,$l)or die($sql);	

	$tdes = mysql_num_rows($r);

	$registros= array();

	

	$vendedor=$_GET[vendedor];

	$mes1=$_GET[mes1];

	$mes2=$_GET[mes2];

	$mes3=$_GET[mes3];

	$inicio=$_GET[inicio];

		

		if (mysql_num_rows($r)>0)

				{

				while ($f=mysql_fetch_object($r))

				{

					$f->cliente=cambio_texto($f->cliente);

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

	var	u			= document.all;

	var inicio		= 30;

	var sepasods	= 0;

	var mens = new ClaseMensajes();

	mens.iniciar('../../javascript',true);

	

	tabla1.setAttributes({

		nombre:"detalle",

		campos:[

			{nombre:"FECHA CONVENIO", medida:80, alineacion:"left", datos:"fechaconvenio"},

			{nombre:"# CLIENTE", medida:50, alineacion:"center",  datos:"clave"},

			{nombre:"NOMBRE DEL CLIENTE", medida:200, alineacion:"left",  datos:"cliente"},	

			{nombre:"CONVENIO", medida:50, alineacion:"center",  datos:"convenio"},			

			{nombre:"<?=$mes1 ?>", medida:100, tipo:"moneda", alineacion:"right", datos:"mes1"},

			{nombre:"<?=$mes2 ?>", medida:100, tipo:"moneda", alineacion:"right", datos:"mes2"},

			{nombre:"<?=$mes3 ?>", medida:100, tipo:"moneda", alineacion:"right", datos:"mes3"},

			{nombre:"TIPO", medida:50, alineacion:"center", datos:"tipo"}

		],

		filasInicial:30,

		alto:150,

		seleccion:true,

		ordenable:false,

		//eventoDblClickFila:"verRecoleccion()",

		nombrevar:"tabla1"

	});

	

		window.onload = function(){

			tabla1.create();

			mostrardetalle('<?=$datos ?>');

			u.vendedor.value='<?=$vendedor ?>';

			u.d_atrasdes.style.visibility = "hidden";

			if(parseInt(u.mostrardes2.value) <= 30){

				u.d_sigdes.style.visibility  = "hidden";

			}

		}

	

		function mostrardetalle(datos){	

		if (datos!=0) {

				var total=0;

				var total2=0;

				var total3=0;

				tabla1.clear();

				var objeto = eval(convertirValoresJson(datos));

				for(var i=0;i<objeto.length;i++){

					var obj		 	   	= new Object();

					obj.fechaconvenio 		= objeto[i].fechaconvenio;

					obj.clave		 	   	= objeto[i].clave;

					obj.cliente				= objeto[i].cliente;

					obj.convenio			= objeto[i].convenio;

					obj.mes1				= objeto[i].mes1;

					obj.mes2				= objeto[i].mes2;

					obj.mes3				= objeto[i].mes3;

					obj.tipo				= objeto[i].tipo;

					total += parseFloat(objeto[i].mes1);

					total2 += parseFloat(objeto[i].mes2);

					total3 += parseFloat(objeto[i].mes3);

					tabla1.add(obj);

				}	

				u.total.value=convertirMoneda(total);

				u.total2.value=convertirMoneda(total2);

				u.total3.value=convertirMoneda(total3);

			}else{

				tabla1.clear();

				u.total.value=0;

				u.total2.value=0;

				u.total3.value=0;

				if (u.inicio.value!="1"){

					parent.mens.show("A","No existieron datos con los filtros seleccionados","¡Atención!","");	

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

				consultaTexto("mostrarDetalle","consultasVendedores.php?accion=4&inicio=0&clavevendedor=<?=$_GET[clavevendedor] ?>&vendedor=<?=$_GET[vendedor] ?>&fecha=<?=$_GET[fecha] ?>&mes1=<?=$_GET[mes1] ?>&mes2=<?=$_GET[mes2] ?>&mes3=<?=$_GET[mes3] ?>");				

			}else{

				if(sepasods!=0){

					u.mostrardes.value = sepasods;

					sepasods = 0;

				}

				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;

				if(parseFloat(u.mostrardes.value) < inicio){

					u.mostrardes.value = inicio;

				}

				consultaTexto("mostrarDetalle","consultasVendedores.php?accion=4&clavevendedor=<?=$_GET[clavevendedor] ?>&vendedor=<?=$_GET[vendedor] ?>&fecha=<?=$_GET[fecha] ?>&mes1=<?=$_GET[mes1] ?>&mes2=<?=$_GET[mes2] ?>&mes3=<?=$_GET[mes3] ?>&inicio="+u.totaldes.value);

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

				consultaTexto("mostrarDetalle","consultasVendedores.php?accion=4&clavevendedor=<?=$_GET[clavevendedor] ?>&vendedor=<?=$_GET[vendedor] ?>&fecha=<?=$_GET[fecha] ?>&mes1=<?=$_GET[mes1] ?>&mes2=<?=$_GET[mes2] ?>&mes3=<?=$_GET[mes3] ?>&inicio="+u.totaldes.value);

			}			

		}	

	}

	

	function mostrarDetalle(datos){

		if(datos.indexOf("nada")<0){

			var obj = eval(datos);

			tabla1.setJsonData(obj);

		}		

	}

	

	function tipoImpresion(valor){

		if(valor=="Archivo"){			

			window.open("http://www.pmmentuempresa.com/web/general/vendedores/generarExcelMeses.php?accion=1&titulo=DETALLADA POR VENDEDOR&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>&mes1=<?=$mes1 ?>&mes2=<?=$mes2 ?>&mes3=<?=$mes3 ?>&clavevendedor=<?=$_GET[clavevendedor] ?>");			

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

    <td width="881"><table width="160" border="0" cellpadding="0" cellspacing="0">

      <tr>

        <td width="57">Vendedor:</td>

        <td width="103"><input name="vendedor" type="text" class="Tablas" id="vendedor" style="width:350px;background:#FFFF99" value="<?=$vendedor ?>

      " readonly=""/></td>

      </tr>

    </table>

      </td>

  </tr>

  <tr>

    <td width="881">

      <table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">

      </table>

    </td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td><table width="551" height="16" border="0" cellpadding="0" cellspacing="0">

      <tr>

        <td width="4">&nbsp;</td>

        <td width="568"><div align="right"><strong><strong>Totales:

          <input name="mostrardes2" class="Tablas" type="text" id="mostrardes2" style="width:100px; text-align:center; background-color:#FFFF99;" value="<?=$tdes; ?>" />

        </strong></strong></div></td>

        <td width="1" align="center"><div align="right"></div></td>

        <td width="110" align="center"><div align="left">

          <input name="total" type="text" class="Tablas" id="total" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>

                " readonly="" align="right" />

        </div></td>

        <td width="110" align="center"><div align="left">

          <input name="total2" type="text" class="Tablas" id="total2" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total2 ?>

                " readonly="" align="right" />

        </div></td>

        <td width="110" align="center"><div align="left">

          <input name="total3" type="text" class="Tablas" id="total3" style="text-align:right;width:100px;background:#FFFF99" value="<?=$total ?>

                " readonly="" align="right" />

        </div></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td><table width="490" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td width="107" align="right"><div id="d_atrasdes" class="ebtn_atraz" onclick="mostrarDescuento('atras');"></div></td>

        <td width="302" align="center"><strong>

          <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" style="width:70px; text-align:center; border:none" value="30" />

          <strong><span style="color:#FF0000"><span class="Estilo4">
          <input name="totaldes" type="hidden" id="totaldes" value="01" />
          <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
          <input name="inicio" type="hidden" id="inicio" value="<?=$inicio ?>" />
          </span></span></strong></strong></td>

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

<script>

	//parent.frames[1].document.getElementById('titulo').innerHTML = '';

</script>

</html>