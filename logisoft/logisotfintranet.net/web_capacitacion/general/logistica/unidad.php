<!--- +"&fecha="+u.fecha.value+"&fecha2="+u.fecha2.value -->



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.css?random=20051112"></LINK>

<SCRIPT type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.js?random=20060118"></script>

<script src="../../javascript/ClaseTabla.js"></script>

<script src="../../javascript/ajax.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript"  src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript"  src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />

<script>

	var tabla1	= new ClaseTabla();

	var	u		= document.all;

	tabla1.setAttributes({

		nombre:"detalle",

		campos:[

			{nombre:"NO. ECONOMICO", medida:105, alineacion:"center",  datos:"noeconomico"},

			{nombre:"PRECINTOS ASIGNADOS", medida:250, alineacion:"center", datos:"precintosasignados"},

			{nombre:"CAPACIDAD PESO VOLUMETRICO", medida:200, alineacion:"center",  datos:"capacidadpesovolumetrico"},					

			{nombre:"CAPACIDAD REAL", medida:105, alineacion:"center", datos:"capacidadreal"}

		],

		filasInicial:30,

		alto:320,

		seleccion:true,

		ordenable:false,

		//eventoDblClickFila:"verRecoleccion()",

		nombrevar:"tabla1"

	});

	

	window.onload = function(){

		tabla1.create();

		obtenerDetalle();

	}

	

	

	/*****/

	

	function obtenerDetalle(){

		consultaTexto("mostrarDetalle","logistica_con.php?accion=3&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&valram="+Math.random());

	}

	

	function mostrarDetalle(datos){	

		var objeto = eval(convertirValoresJson(datos));

		for(var i=0;i<objeto.length;i++){

			var obj		 	   			= new Object();

			obj.noeconomico		 		= objeto[i].numeroeconomico;

			obj.precintosasignados 		= objeto[i].precinto;

			obj.capacidadpesovolumetrico= objeto[i].cvolumen;

			obj.capacidadreal			= objeto[i].ckilos;

			tabla1.add(obj);

		}

	}

	function tipoImpresion(valor){

		if(valor=="Archivo"){			

			window.open("http://www.pmmentuempresa.com/web/general/logistica/generarExcelLogistica.php?accion=3&titulo=REPORTE POR UNIDADES&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>&foliobitacora=<?=$_GET[foliobitacora]?>&valram="+Math.random());

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

.Estilo41 {font-size: 12px}

-->

</style>

</head>

<body>

<form id="form1" name="form1" method="post" action="">

<table width="685" border="0" align="center" cellpadding="0" cellspacing="0">

 

  <tr>

    <td>

      <table width="426" id="detalle" border="0" cellpadding="0" cellspacing="0">

      </table>

    </td>

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