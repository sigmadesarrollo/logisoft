<?

	session_start();

?>

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

	<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

	<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

    <link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

    <link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

    

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

<param name="movie" value="../css/todosHeader.swf" /><param name="quality" value="high"/><param name="bgcolor" value="#ffffff" /><embed src="../css/todosHeader.swf" quality="high" bgcolor="#ffffff" width="1000" height="150" name="Header" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

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

