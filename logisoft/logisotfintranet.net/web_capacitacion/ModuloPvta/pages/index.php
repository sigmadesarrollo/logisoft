<?

	session_start();

?>

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script>



		window.onload = function (){

			/*tree.create({

				nombre:"miTree",

				estructura: [

					{nombre:"Gu�as", contenido:[ 

						{nombre:"Cotizador de Gu�as", contenido:"", onclick:"", imagen:""},

						{nombre:"Consulta de Gu�as", contenido:"", onclick:"", imagen:""},

						{nombre:"Elaboraci�n de Gu�as", contenido:[

							{nombre:"De Ventanilla", contenido:"", onclick:"", imagen:""},

							{nombre:"Empresariales", contenido:"", onclick:"", imagen:""}

							], onclick:"", imagen:""}

						{nombre:"Historial de Gu�as", contenido:"", onclick:"", imagen:""},

						{nombre:"Evaluaci�n de Mercanc�a", contenido:[

							{nombre:"Evaluaciones Pendientes de Generar Gu�a", contenido:"", onclick:"", imagen:""}

						], onclick:"", imagen:""}

						], onclick:"", imagen:""},						

					{nombre:"Cancelaciones", contenido:[ 

						{nombre:"Locales", contenido:[

							{nombre:"Autorizadas para cancelar", contenido:"", onclick:"", imagen:""}

						], onclick:"", imagen:""},

						{nombre:"For�neas", contenido:[

							{nombre:"Autorizaciones para sustituir", contenido:"", onclick:"", imagen:""},

							{nombre:"Autorizadas para cancelar", contenido:"", onclick:"", imagen:""}

							], onclick:"", imagen:""}

						{nombre:"Pendientes de Autorizar", contenido:"", onclick:"", imagen:""},

						{nombre:"Gu�as Canceladas", contenido:"", onclick:"", imagen:""},

						{nombre:"Gu�as Sin Ruta Cliente Corporativo", contenido:"", onclick:"", imagen:""},

						{nombre:"Gu�as Empresariales Pendientes", contenido:"", onclick:"", imagen:""},						

						], onclick:"", imagen:""},						

					{nombre:"Entregas", contenido:[ 

						{nombre:"Gu�as EAD", contenido:[

							{nombre:"Gu�as For�neas", contenido:"", onclick:"", imagen:""},

							{nombre:"Cliente Corporativo", contenido:"", onclick:"", imagen:""},

							{nombre:"Gu�as Faltantes de Liquidaci�n EAD", contenido:"", onclick:"", imagen:""},

							{nombre:"Liquidaciones EAD", contenido:"", onclick:"", imagen:""}

						], onclick:"", imagen:""},

						{nombre:"Ocurre", contenido:[

							{nombre:"Entregas Ocurre en Sucursal", contenido:"", onclick:"", imagen:""},

							{nombre:"Entregas Ocurre en Almac�n", contenido:"", onclick:"", imagen:""}

							], onclick:"", imagen:""}											

						], onclick:"", imagen:""},					

					{nombre:"Recolecciones", contenido:[ 

						{nombre:"Agenda de Recolecciones", contenido:"", onclick:"", imagen:""}], onclick:"", imagen:""},						

					{nombre:"Facturaci�n", contenido:[ 

						{nombre:"Facturaci�n", contenido:"", onclick:"", imagen:""},

						{nombre:"Facturas Canceladas", contenido:"", onclick:"", imagen:""},

						{nombre:"Gu�as de Ventanilla Pendientes de Facturar", contenido:"", onclick:"", imagen:""}

						], onclick:"", imagen:""},					

					{nombre:"Clientes", contenido:[ 

						{nombre:"Directorio de clientes", contenido:[

							{nombre:"C�digo Postal", contenido:"", onclick:"", imagen:""},

							{nombre:"Colonias", contenido:"", onclick:"", imagen:""},

							{nombre:"Solicitud de Gu�a Empresarial", contenido:"", onclick:"", imagen:""}							

						], onclick:"", imagen:""},

						{nombre:"Propuestas de Convenios Pendientes de Autorizar", contenido:"",onclick:"", imagen:""},	

						{nombre:"Convenios Pendientes de Autorizar()", contenido:"", onclick:"", imagen:""},

						{nombre:"Convenios Pendientes de Activar()", contenido:"", onclick:"", imagen:""},

						{nombre:"Convenios por Vencer()", contenido:"", onclick:"", imagen:""}

						], onclick:"", imagen:""},						

					{nombre:"CAT", contenido:[ 

						{nombre:"Registro de CAT", contenido:"", onclick:"", imagen:""},

						{nombre:"Da�os y Faltantes()", contenido:"",onclick:"", imagen:""},	

						{nombre:"EAD mal efectuadas()", contenido:"", onclick:"", imagen:""},

						{nombre:"Recolecciones NO realizadas()", contenido:"", onclick:"", imagen:""},

						{nombre:"Cancelaciones de Gu�as()", contenido:"", onclick:"", imagen:""},

						{nombre:"Gu�as Extraviadas()", contenido:"", onclick:"", imagen:""}

						], onclick:"", imagen:""},						

					{nombre:"Caja", contenido:[ 

						{nombre:"Inicializar Caja", contenido:"", onclick:"", imagen:""},

						{nombre:"Cierre de Caja", contenido:"",onclick:"", imagen:""},	

						{nombre:"Cajero", contenido:"", onclick:"", imagen:""}

						], onclick:"", imagen:""}						

				]

			});*/

		}

	

	

</script>



	<link href="../css/styles.css" rel="stylesheet" type="text/css" />	

	<link href="../css/generalStyles.css" rel="stylesheet" type="text/css" />



<title>PMM</title>



</head>

<body link="#BA410D" vlink="#BA410D" alink="#BA410D" bgcolor="#ffffff">



<div id="wrap">

<table border="0" cellpadding="0" cellspacing="0" width="100%">

<tr>

	<td colspan="3" height="10%" width="100%" align="left" valign="top">

	<!--

		AREA BANNER, PANEL DE CONTROL 

	-->

	<!-- saved from url=(0013)about:internet -->

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="1000" height="150" id="Header" align="middle">

<param name="allowScriptAccess" value="sameDomain" />

<param name="movie" value="../css/puntodeventa.swf" /><param name="quality" value="high"/><param name="bgcolor" value="#ffffff" /><embed src="../css/puntodeventa.swf" quality="high" bgcolor="#ffffff" width="1000" height="150" name="Header" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

</object>

	<!-- 

		FIN AREA BANNER

	-->

    <script>

		function buscarlaguia(valor){

			frames[1].buscarUnaGuia(valor);

		}

	</script>

	</td>

</tr>

</table>

<table border="0" cellpadding="0" cellspacing="0" width="1000px">

<tr>

	<td width="231" valign="top" style="overflow:auto">	

	<!--

		PARTE IZQUIERDA DEL SITIO

	-->

	<iframe src="acordionIzquierdo.php" scrolling="no" width="220px" height="400px" frameborder="0"></iframe>

	<!--

		FIN PARTE IZQUIERDA DEL SITIO

	--></td>

	<td width="769" align="center" valign="top" >		

	<!-- PARTE CENTRO -->

		

			<iframe name="pagina" id="pagina" scrolling="auto" width="740" height="600" src="../../guias/guia.php" frameborder="0"></iframe>

	

	<!-- PARTE CENTRO -->

	

	<!--

	 	PARTE DERECHA DEL SITIO

	-->

	<!--

		FIN PARTE DERECHA DEL SITIO

	--></td>	

	</tr>

<tr>

	<td colspan="2" class="textfooter">

	<a href="../pages/contenidos/port1.html" target="cont">Facturaci&oacute;n</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="../pages/contenidos/port2.html" target="cont">Cr&eacute;dito y cobranza</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="../php/form0.php?arch=1" target="cont">Caja</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="../php/form0.php" target="cont">CAT</a>

	<br /><br />

	 PMM &copy; <?=date('Y'); ?> - Todos los derechos reservados.<br /></td>

</tr>

</table>

</div>



</body>

</html>

