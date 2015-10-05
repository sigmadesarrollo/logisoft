<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.css?random=20051112"></LINK>

<SCRIPT type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar2.js?random=20060118"></script>

<script src="../../../javascript/ClaseTabla.js"></script>

<link href="../../../estilos_estandar.css" />

<script src="../../../javascript/ajax.js"></script>

<script language="javascript" src="../../../javascript/funcionesDrag.js"></script>

<script language="javascript" src="../../../javascript/ClaseMensajes.js"></script>

<script src="../../../javascript/shortcut.js"></script>

<script src="../../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script src="../../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script src="../../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<link href="../../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script language="javascript" src="../../../javascript/Mascara.js"></script>

<script language="javascript" src="../../../javascript/ClaseTabs.js"></script>

<script language="javascript" src="../../../ClaseNavegador/ClaseNavegador.js"></script>

<script>



	var tabs 		= new ClaseTabs();

	var cn = new ClaseNavegador();

	window.onload = function (){

		tabs.iniciar({

			nombre:"barratabs",

			largo:710,

			alto:400,

			ajustex:11,

			ajustey:12,

			imagenes:"../../../img",

			paginainicial:"principal.php"

		});

		cn.crearNavegador();

	}

	

		cn.asignarContenidos({

			fila:"filaxx",

			contenidos:[

				{nombre:"Tipo de Venta"},

				{nombre:"Condicion de Pago Convenio"},

				{nombre:"Ventas Guias Prepagadas"},

				{nombre:"Ventas Guias a Consignación"},

				{nombre:"Ventas Contado Convenio"},

				{nombre:"Ventas Convenio Cliente"},

				{nombre:"(Prep)Serv Pend de Facturar"},

				{nombre:"Guias y Servicios"},

				{nombre:"Envios Por Cliente"},

				{nombre:"Condicion de Pago sin Convenio"},

				{nombre:"Ventas Convenio Cliente Sin Convenio"},

				{nombre:"Ventas Contado Sin Convenio"}

			],

			links:"false"

		});

		

		function agregar(value){

			cn.agregarDireccion(value);

		}





</script>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Documento sin t&iacute;tulo</title>



<link href="../../../estilos_estandar.css" rel="stylesheet" type="text/css" />







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



#form1 table tr #lastablas {



	font-size: 14px;



	font-weight: bold;



}



-->



</style>







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



<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />



<style type="text/css">



<!--



.Estilo4 {



	font-size: 14px



}



.Balance {background-color: #FFFFFF; border: 0px none}



.Balance2 {background-color: #DEECFA; border: 0px none;}



-->



</style>



</head>







<body>



<form id="form1" name="form1" method="post" action="">

  <table width="600" height="496" border="0" align="center" cellpadding="0" cellspacing="0">

    <tr>

      <td height="29" align="center" class="FondoTabla Estilo4">Reporte Principal de Ventas </td>

    </tr>

    <tr>

      <td height="33" align="left"><table border="0" cellpadding="0" cellspacing="0" style="font-family:Verdana, Geneva, sans-serif; font-size:10px; font-weight:bold">

          <tr id="filaxx"></tr>

      </table></td>

    </tr>

    <tr>

      <td height="17" id="lastablas">&nbsp;</td>

    </tr>

    <tr>

      <td width="490" height="17" id="lastablas"><table id="barratabs" cellpadding="0" cellspacing="0" border="0">

      </table></td>

    </tr>

    <tr>

      <td height="400"></td>

    </tr>

  </table>

</form>



</body>



<script>



	//parent.frames[1].document.getElementById('titulo').innerHTML = 'RM VENTAS';



</script>



</html>



