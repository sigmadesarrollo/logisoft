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

			{nombre:"SECTOR", medida:50, alineacion:"left", datos:"sector"},

			{nombre:"No_GUIA", medida:80, alineacion:"center", datos:"guia"},

			{nombre:"ORIGEN", medida:100, alineacion:"left", datos:"origen"},

			{nombre:"DESTINATARIO", medida:100, alineacion:"left", datos:"destinatario"},

			{nombre:"TIPO_FLETE", medida:50, alineacion:"center", datos:"tipoflete"},

			{nombre:"IMPORTE", medida:70, tipo:"moneda", alineacion:"right", datos:"importe",onClick:"agregarFormaPago"},

			{nombre:"ESTADO", medida:70, alineacion:"center", datos:"estado"},

			{nombre:"CHECK", medida:50, alineacion:"center", datos:"seleccion", tipo:"checkbox", onClick:"validacheck"},

			{nombre:"NOMBRE", medida:4, tipo:"oculto", alineacion:"left", datos:"nombre"},

			{nombre:"IDENTIFICACION", medida:4, tipo:"oculto", alineacion:"left", datos:"identificacion"},

			{nombre:"NUMERO_ID", medida:4, tipo:"oculto", alineacion:"left", datos:"numero_id"},

			{nombre:"CONDICION_PAGO", medida:4, tipo:"oculto", alineacion:"left", datos:"condicionpago"}

		],

		filasInicial:15,

		alto:150,

		seleccion:true,

		ordenable:false,

		nombrevar:"tabla1"

	});

	

	window.onload = function(){

		tabla1.create();

		obtener();

	}

	

	function obtener(){

		consultaTexto("mostrar","consultas_con.php?accion=7&valram="+Math.random());

			

	}

	

	function mostrar(datos){

		tabla1.clear();	

		var obj = eval(convertirValoresJson(datos));

		tabla1.setJsonData(obj);

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

