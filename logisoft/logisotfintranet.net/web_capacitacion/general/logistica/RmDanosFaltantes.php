<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	require_once('../../Conectar.php');

	$l = Conectarse('webpmm');

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script language="javascript" src="../../javascript/ClaseTabla.js"></script>

<link href="../../recepciones/estilos_estandar.css" rel="stylesheet" type="text/css" />

<script src="../../javascript/ajax.js"></script>

<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="../../facturacion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../../facturacion/puntovta.css" rel="stylesheet" type="text/css" />

<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />

<link type="text/css" rel="stylesheet" href="../../recepcion/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></LINK>

<SCRIPT type="text/javascript" src="../../recepcion/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script>

	var u = document.all;	

	var tabla1 = new ClaseTabla();

	

	tabla1.setAttributes({

		nombre:"detalle",

	campos:[

			{nombre:"No_GUIA", medida:80, alineacion:"left", datos:"guia"},

			{nombre:"ESTADO_GUIA", medida:70, alineacion:"left", datos:"estado"},

			{nombre:"DESTINATARIO", medida:90, alineacion:"left", datos:"destinatario"},

			{nombre:"SUC_DESTINO", medida:70, alineacion:"left", datos:"destino"},

			{nombre:"SUC_ORIGEN", medida:70, alineacion:"left", datos:"origen"},

			{nombre:"FECHA_RECEPCION", medida:90, alineacion:"left", datos:"fecha"},

			{nombre:"FOLIO_RECEPCION", medida:90, alineacion:"left", datos:"folio"},

			{nombre:"COMENTARIOS", medida:90, alineacion:"left", datos:"comentario"}			

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

		consultaTexto("mostrarDetalle","logistica_con.php?accion=9&guia=<?=$_GET[guia]?>&valram="+Math.random());

	}

	

	function mostrarDetalle(datos){	

	

		var objeto = eval(convertirValoresJson(datos));

		for(var i=0;i<objeto.length;i++){

			var obj			= new Object();

			obj.guia		= objeto[i].guia;

			obj.estado		= objeto[i].estado;

			obj.destinatario= objeto[i].destinatario;

			obj.destino		= objeto[i].destino;

			obj.origen		= objeto[i].origen;

			obj.fecha 		= objeto[i].fecha;

			obj.folio 		= objeto[i].recepcion;

			obj.comentario	= objeto[i].comentario;

			tabla1.add(obj);

		}

	}

	/***/

	

</script>

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

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

</style>

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.Estilo4 {font-size: 12px}

.Estilo5 {

	font-size: 9px;

	font-family: tahoma;

	font-style: italic;

}

-->

</style>

<link href="../../recepcion/Tablas.css" rel="stylesheet" type="text/css">

<style type="text/css">

<!--

.Estilo41 {font-size: 12px}

-->

</style>

</head>

<body>

<form id="form1" name="form1" method="post" action="">
<table width="680" border="0" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td width="1070" colspan="2">

        <table width="426" id="detalle" border="0" cellpadding="0" cellspacing="0">

        </table>

    </td>

    </tr>

<tr>

      <td colspan="2" align="center"></td>

    </tr>

  </table>
</form>

</body>

<script>

	//parent.frames[1].document.getElementById('titulo').innerHTML = 'HISTORICO DA?OS Y FALTANTES';

</script>

</html>

