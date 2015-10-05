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

	var tabla1 	= new ClaseTabla();

	var	u		= document.all;

	tabla1.setAttributes({

		nombre:"detalle",

		campos:[

			{nombre:"FOLIO ATENCION", medida:80, alineacion:"left", datos:"folio"},

			{nombre:"FECHA", medida:60, alineacion:"left", datos:"fecha"},

			{nombre:"FOLIO DOC", medida:70, alineacion:"left", datos:"foliodoc"},

			{nombre:"SUCURSAL", medida:70, alineacion:"left", datos:"sucursal"},

			{nombre:"QUEJA", medida:150, alineacion:"left",  datos:"queja"},

			{nombre:"RESPONSABLE", medida:150, alineacion:"left",  datos:"responsable"},

			{nombre:"OBSERVACIONES", medida:150, alineacion:"left", datos:"observaciones"},

			{nombre:"POSIBLE FECHA SOL.", medida:90, alineacion:"left", datos:"solucion"},

			{nombre:"COMENTARIOS", medida:4, tipo:"oculto", alineacion:"left", datos:"comentarios"}

		],

		filasInicial:30,

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

		consultaTexto("mostrar","consultas_con.php?accion=11&valram="+Math.random());

			

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

      <td><div style="width:630px; height:180px; overflow:auto;"><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">       

      </table></div></td>

    </tr>

    <tr>

      <td>&nbsp;</td>

    </tr>

  </table>

</form>

</body>

</html>

