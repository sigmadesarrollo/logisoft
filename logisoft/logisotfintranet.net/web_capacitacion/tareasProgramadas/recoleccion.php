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

			{nombre:"FOLIO", medida:50, alineacion:"left", datos:"folio"},

			{nombre:"CLIENTE", medida:170, alineacion:"left", datos:"cliente"},

			{nombre:"DIRECCION", medida:170, alineacion:"left", datos:"direccion"},

			{nombre:"TRANSMITIDA", medida:50, alineacion:"center",  datos:"transmitida"},

			{nombre:"REALIZO", medida:50, alineacion:"center",  datos:"realizo"},

			{nombre:"UNIDAD", medida:50, alineacion:"left", datos:"unidad"},

			{nombre:"FECHA", medida:80, alineacion:"center",  datos:"fecha"},

			{nombre:"HORARIO", medida:4, alineacion:"left", tipo:"oculto", datos:"horario"},			

			{nombre:"TELEFONO", medida:80, alineacion:"left", datos:"telefono"},			

			{nombre:"FOLIO RECOLECCION/EMPRESARIAL", medida:160, alineacion:"left", datos:"folios"},

			{nombre:"SUCURSAL", medida:4, alineacion:"left", tipo:"oculto", datos:"sucursal"},			

			{nombre:"MOTIVOS", medida:160, alineacion:"left", datos:"motivos"},

			{nombre:"COLORCAN", medida:4, alineacion:"left", tipo:"oculto", datos:"colorcan"},

			{nombre:"COLORREP", medida:4, alineacion:"left", tipo:"oculto", datos:"colorrep"},

			{nombre:"FREGISTRO", medida:4, alineacion:"left", tipo:"oculto", datos:"fecharegistro"},

			{nombre:"ESTADO", medida:4, alineacion:"left", tipo:"oculto", datos:"estado"}

		],



		filasInicial:30,

		alto:250,

		seleccion:true,

		ordenable:false,

		eventoDblClickFila:"verRecoleccion()",

		nombrevar:"tabla1"



});

	

	window.onload = function(){

		tabla1.create();

		obtener();

	}

	

	function obtener(){

		consultaTexto("mostrar","consultas_con.php?accion=8&valram="+Math.random());

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

      <td><div style="width:600px; height:280px; overflow:auto;"><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">       

      </table></div></td>

    </tr>

    <tr>

      <td>&nbsp;</td>

    </tr>

  </table>

 </form>

</body>

</html>

