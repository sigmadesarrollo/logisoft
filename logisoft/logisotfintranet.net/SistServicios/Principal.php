<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<?php
	include("librerias.php");
?>
<script>
	jQuery(document).ready(function(){
		jQuery("span").css({color:"#006",textDecoration:"underline",cursor:"pointer", fontSize:"30px", fontWeight:"bold"});
		jQuery("#h2Mantenimiento, #imgMantenimiento").click(function(){
			document.location.href = "altaMantenimiento.php";
		});
		jQuery("#h2Foraneo, #imgForaneo").click(function(){
			document.location.href = "altaForaneos.php";
		});
		jQuery("#h2Mobiliario, #imgMobiliario").click(function(){
			document.location.href = "altaMobiliario.php";
		});
		jQuery("#h2Papeleria, #imgPapeleria").click(function(){
			document.location.href = "altaPapeleria.php";
		});
	});
</script>
</head>

<body style="background-color:transparent">

<table width="100%" border="0" style="font-family:Arial, Helvetica, sans-serif;">
<?php
	  if($_SESSION[MENSAJE]=="NO"){ ?>
		<tr id="trSesion">
  			<td colspan="2" align="center" style="color:#FF0000; font-size:14px; font-weight:bold">DEBE INICIAR SESION PARA CONTINUAR</td>
  		</tr>
<?php } ?>
  <tr>
    <td width="16%" align="center"><img src="img/Herramienta.png" id="imgMantenimiento" width="110px" height="81px" style="cursor:pointer" title="Sistema Mantenimiento Vehicular" /></td>
    <td width="78%"><span id="h2Mantenimiento">Sistema Mantenimiento Vehicular</span></td>   
  </tr>
  <tr>
  	<td colspan="2"></td>
  </tr>
  <tr>
    <td width="16%" align="center"><img src="img/vehiculo.png" id="imgForaneo" style="cursor:pointer" title="Sistema Vehiculos Foraneos" /></td>
    <td width="78%"><span id="h2Foraneo">Sistema Vehiculos Foraneos</span></td>    
  </tr>
   <tr>
  	<td colspan="2"></td>
  </tr>
  <tr>
    <td width="16%" align="center"><img src="img/Equipo.png" id="imgMobiliario" style="cursor:pointer" title="Sistema Mobiliario y Equipo" /></td>
    <td width="78%"><span id="h2Mobiliario">Sistema Mobiliario y Equipo</span></td>    
  </tr>
  <tr>
  	<td colspan="2"></td>
  </tr>
  <tr>
    <td width="16%" align="center"><img src="img/Papeleria.png" id="imgPapeleria" style="cursor:pointer" title="Sistema Papeler&iacute;a" /></td>
    <td width="78%"><span id="h2Papeleria">Sistema Papeler&iacute;a</span></td>    
  </tr>
</table>
</body>
</html>