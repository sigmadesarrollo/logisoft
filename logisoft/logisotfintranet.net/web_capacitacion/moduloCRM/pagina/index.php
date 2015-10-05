<?	session_start();

?>

<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	<link href="../../moduloCRM/css/styles.css" rel="stylesheet" type="text/css" />
	<link href="../../moduloCRM/css/generalStyles.css" rel="stylesheet" type="text/css" />
<title>PMM</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
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

<param name="movie" value="../../moduloCRM/css/moduloCRM.swf" /><param name="quality" value="high"/><param name="bgcolor" value="#ffffff" /><embed src="../../moduloCRM/css/moduloCRM.swf" quality="high" bgcolor="#ffffff" width="1000" height="150" name="Header" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

</object>

	<!-- 

		FIN AREA BANNER

	-->

	</td>

</tr>

</table>

<table border="0" cellpadding="0" cellspacing="0" width="1000px">

<tr>

	<td width="258" valign="top" style="overflow:auto">	

	<!--

		PARTE IZQUIERDA DEL SITIO

	-->

	<iframe src="acordionIzquierdo.php?cliente=<?=$_GET[cliente] ?>" scrolling="no" width="220px" height="400px" frameborder="0"></iframe>

	<!--

		FIN PARTE IZQUIERDA DEL SITIO

	--></td>

	<td width="742" align="center" valign="top" >

	<!-- PARTE CENTRO -->

		

		<iframe name="pagina" id="pagina" scrolling="auto" width="740" height="600"  frameborder="0"></iframe>

	

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

	<a href="../../Copia de moduloOperaciones/pages/contenidos/port1.html" target="cont">Facturaci&oacute;n</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="../../Copia de moduloOperaciones/pages/contenidos/port2.html" target="cont">Cr&eacute;dito y cobranza</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="../../Copia de moduloOperaciones/php/form0.php?arch=1" target="cont">Caja</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="../../Copia de moduloOperaciones/php/form0.php" target="cont">CAT</a>

	<br /><br />

	 PMM &copy; <?=date('Y'); ?> - Todos los derechos reservados.<br /></td>

</tr>

</table>

</div>



</body>

</html>

