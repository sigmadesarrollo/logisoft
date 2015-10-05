<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

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

	var tabla1 		= new ClaseTabla();

	var	u		= document.all;

	tabla1.setAttributes({

		nombre:"detalle",

		campos:[

			{nombre:"NOMBRE", medida:250, alineacion:"center",  datos:"nombre"},

			{nombre:"DIAS TRABAJADOS", medida:120, alineacion:"center", datos:"diastrabajados"},

			{nombre:"VIAJES", medida:150, alineacion:"center",  datos:"viajes"},					

			{nombre:"KM RECORRIDOS", medida:140, alineacion:"center", datos:"kmrecorrido"}

		],

		

		filasInicial:30,

		alto:320,

		seleccion:true,

		ordenable:false,

		nombrevar:"tabla1"

	});

	

	window.onload = function(){

		tabla1.create();

		obtenerDetalle();

	}

	/*****/

	function obtenerDetalle(){

		consultaTexto("mostrarDetalle","logistica_con.php?accion=4&operador=<?=$_GET[operador]?>&foliobitacora=<?=$_GET[foliobitacora]?>&fecha=<?=$_GET[fecha]?>&fecha2=<?=$_GET[fecha2]?>&valram="+Math.random());

	}

	

	function mostrarDetalle(datos){	

		var objeto = eval(convertirValoresJson(datos));

		for(var i=0;i<objeto.length;i++){

			var obj		 	   			= new Object();

			obj.nombre		 		= objeto[i].nombre;

			obj.diastrabajados 		= objeto[i].dias;

			obj.viajes				= objeto[i].viajes;

			obj.kmrecorrido			= objeto[i].suma;

			tabla1.add(obj);

		}

	}

	/***/

	function tipoImpresion(valor){

		if(valor=="Archivo"){			

			window.open("http://www.pmmentuempresa.com/web/general/logistica/generarExcelLogistica.php?accion=4&titulo=ESTADISTICAS DEL OPERADOR&fecha=<?=$_GET[fecha] ?>&fecha2=<?=$_GET[fecha2] ?>&operador=<?=$_GET[operador]?>&foliobitacora=<?=$_GET[foliobitacora]?>&valram="+Math.random());			

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