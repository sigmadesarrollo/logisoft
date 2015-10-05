<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<style type="text/css">

<!--

.menu {font-family:Arial; font-weight:bold}



.menu a{

text-decoration:none;

color:black;

}

-->

</style>

<script language="javascript">

<!--



/*

Cool Table Menu

By Clarence Eldefors (http://www.freebox.com/cereweb) with modifications from javascriptkit.com

Visit http://javascriptkit.com for this and over 400+ other scripts

*/







function movein(which,html){

which.style.background='coral'

if (document.getElementById)

document.getElementById("boxdescription").innerHTML=html

else

boxdescription.innerHTML=html

}



function moveout(which){

which.style.background='bisque'

if (document.getElementById)

document.getElementById("boxdescription").innerHTML='&nbsp;'

else

boxdescription.innerHTML='&nbsp;'

}



//-->

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<style type="text/css">

<!--

.Estilo1 {font-family: tahoma}

-->

<!--

.enlaceboton {

PADDING-RIGHT: 4px; PADDING-LEFT: 4px; FONT-WEIGHT: bold; FONT-SIZE: 10pt; PADDING-BOTTOM: 4px; COLOR: #FFFFFF; PADDING-TOP: 4px; FONT-FAMILY: verdana, arial, sans-serif; BACKGROUND-COLOR: #3168D5; TEXT-DECORATION: none

}

.enlaceboton:link {

	BORDER-RIGHT: #666666 2px solid; BORDER-TOP: #cccccc 1px solid; BORDER-LEFT: #cccccc 1px solid; BORDER-BOTTOM: #666666 2px solid

}

.enlaceboton:visited {

	BORDER-RIGHT: #666666 2px solid; BORDER-TOP: #cccccc 1px solid; BORDER-LEFT: #cccccc 1px solid; BORDER-BOTTOM: #666666 2px solid

}

.enlaceboton:hover {

	BORDER-RIGHT: #cccccc 1px solid; BORDER-TOP: #666666 2px solid; BORDER-LEFT: #666666 2px solid; BORDER-BOTTOM: #cccccc 1px solid

}

-->

</style>

</head>



<body>

<form name="form1" method="post" action="">

  <table width="236" border="0" align="center">

    <tr>

      <td width="230" colspan="3"><div align="center" class="Estilo1">Numero de Guia</div></td>

    </tr>

    <tr>

      <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="guia" type="text" id="guia" value="<?=$guia ?>" size="25" style="height:50px"></td>

    </tr>

    <tr>

      <td colspan="3">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="3"><div align="center" class="Estilo1">Busqueda</div></td>

    </tr>

    <tr>

      <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        <input name="guia" type="text" id="guia" style="height:50px" value="<?=$busqueda ?>" size="25">

      <label></label></td>

    </tr>

	<tr><td>

	<div>

	<a class="enlaceboton" href="#">&nbsp;&nbsp;#Guia&nbsp;&nbsp;</a>&nbsp;&nbsp;<a class="enlaceboton" href="#">Nombre</a>&nbsp;&nbsp;<a class="enlaceboton" href="#">Paterno</a></div>

	</td>

	</tr>

    <tr>

      <td colspan="3"></td>

    </tr>

    <tr>

      <td colspan="3"><div>

	<a class="enlaceboton" href="#">Materno</a>&nbsp;&nbsp;<a class="enlaceboton" href="#">&nbsp;&nbsp;&nbsp;Nick&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;<a class="enlaceboton" href="#">&nbsp;&nbsp;&nbsp;&nbsp;RFC&nbsp;&nbsp;&nbsp;&nbsp;</a>

</div></td>

    </tr>

    <tr>

      <td colspan="3">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="3" ><img src="file://///pcerika/curso/pmm/img/directorio.gif" width="33" height="29">DIRECTORIO</td>

    </tr>

    <tr>

      <td colspan="3" ><img src="../img/clientes.gif" width="30" height="23"><a href="#">Clientes</a></td>

    </tr>

    <tr>

      <td colspan="3" ><img src="../img/tarifa.gif" width="31" height="31"><a href="#">Tarifas</a></td>

    </tr>

    <tr>

      <td colspan="3" ><img src="../img/rutas.gif" width="32" height="23"><a href="#">Rutas</a></td>

    </tr>

    <tr>

      <td colspan="3" ><img src="../img/sucursal.gif" width="33" height="32"><a href="#">Sucursales</a></td>

    </tr>

    <tr>

      <td colspan="3" ><img src="../img/destino.gif" width="26" height="30"><a href="#">Destinos</a></td>

    </tr>

    <tr>

      <td colspan="3">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="3">Evaluacion de Mercancia </td>

    </tr>

    <tr>

      <td colspan="3">Reimpresion de Guias y Etiquetas </td>

    </tr>

    <tr>

      <td colspan="3">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="3">&nbsp;</td>

    </tr>

  </table>



  <p>&nbsp;</p>

</form>

</body>

</html>

