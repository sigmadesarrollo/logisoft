<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<!--<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />-->
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js" language="javascript"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js" language="javascript"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js" language="javascript"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js" language="javascript"></script>

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
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="capturagastoscajachica.php">
  <br>
<table width="337" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="333" class="FondoTabla Estilo4">REPORTE DE GASTOS</td>
  </tr>
  <tr>
    <td><div align="center" class="Tablas">
      <table width="333" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="center"></div></td>
        </tr>
        <tr>
          <td>Gasto</td>
          <td><span class="Tablas">
            <select name="select_tipo" size="1" class="estilo_cajaseleccion"style="width:300px" >
            	<option value="0">Seleccione un Gasto</option>
                <option value="1">Gasto Mantenimiento Locales</option>
                <option value="2">Gasto Veh&iacute;culos For&aacute;neos</option>
                <option value="3">Inmobiliario y Equipo</option>
                <option value="4">Gastos Diversos</option>
                <option value="5">Prestamo</option>
            </select>
          </span></td>
          </tr>
        <tr>
          <td></td>
          <td><table width="100%">
              <tr>
                <td align="right" width="50%"></td>
                <td align="right"><div class="ebtn_guardar" onClick="checadatos()"> </div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td width="28">&nbsp;</td>
          <td width="305"><span class="Tablas">
            <label></label>
          </span></td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
<input type="hidden" name="tipo_gasto" value="<?=$tipo_gasto?>">
</form>
</body>
<script>	
	function checadatos()
	{
		var selindex = document.form1.select_tipo.selectedIndex;
		if(document.form1.select_tipo.options[selindex].value == 0)
		{
			alerta('Debe seleccionar un gasto','¡Atención!','select_tipo');
			return;
		}
		document.form1.tipo_gasto.value = document.form1.select_tipo.options[selindex].text;
		document.form1.submit();
	}
</script>
</html>
