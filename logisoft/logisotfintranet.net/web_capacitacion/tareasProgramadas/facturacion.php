<?	session_start();

	require_once('../Conectar.php');

	$l = Conectarse('webpmm');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../javascript/ClaseTabla.js"></script>

<script src="../javascript/ajax.js"></script>

<script src="../javascript/ClaseMensajes.js"></script>

<script>

	var tabla1 		= new ClaseTabla();

	var	u			= document.all;

	var mens 		= new ClaseMensajes();	

	

	tabla1.setAttributes({

		nombre:"detalle",

		campos:[

			{nombre:"ORIGEN", medida:40, alineacion:"center", datos:"origen"},

			{nombre:"GUIA", medida:70, alineacion:"left",  datos:"guia"},

			{nombre:"DESTINO", medida:40, alineacion:"center",  datos:"destino"},

			{nombre:"FECHA", medida:55, alineacion:"center", datos:"fecha"},

			{nombre:"CLIENTE", medida:140, alineacion:"left", datos:"cliente"},

			{nombre:"IMPORTE", medida:80, tipo:"moneda", alineacion:"right", datos:"importe"},

			{nombre:"TIPO GUIA", medida:70, alineacion:"left", datos:"tipoguia"},

			{nombre:"STATUS", medida:60, alineacion:"left", datos:"status"}

		],

		filasInicial:9,

		alto:150,

		seleccion:true,

		ordenable:false,

		nombrevar:"tabla1"

	});

	

	window.onload = function(){

		tabla1.create();

		obtenerDetalle();

	}

	function obtenerDetalle(){

		consultaTexto("mostrarDetalle","consultas_con.php?accion=10&valram="+Math.random());

	}

	function mostrarDetalle(datos){

		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){

			var obj = eval(convertirValoresJson(datos));

			tabla1.setJsonData(obj);

		}

	}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

</head>



<body>

<form id="form1" name="form1" method="post" action="">

  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td>&nbsp;</td>

    </tr>

    <tr>

      <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">       

      </table></td>

    </tr>

    <tr>

      <td>&nbsp;</td>

    </tr>

  </table>

</form>

</body>

</html>

