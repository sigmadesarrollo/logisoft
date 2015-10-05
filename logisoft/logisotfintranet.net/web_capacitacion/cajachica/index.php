<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
</head>
<body>
<form id="form1" name="form1" method="post" action="reportargastos.php">
  <br>
<table width="337" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="333" class="FondoTabla Estilo4">
    	<input type="button" name="btnsubmit" value="Reportar Gastos" onClick="submitform('reportargastos.php')">
        <input type="button" name="btngpaa" value="Gastos Pendientes a Aut" onClick="submitform('gastospendientesaautorizar.php')">
        <input type="button" name="btnrgcc" value="Reporte Gastos Caja Chica" onClick="submitform('reportegastoscajachica.php')">
        <input type="button" name="btncdcc" value="Configurador Depósitos Caja Chica" onClick="submitform('configuradordepositoscajachica.php')">
    </td>
  </tr>
  
</table>
<p>&nbsp;</p>
</form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'INDEX';
</script>

<script language="javascript">
  	function submitform(estaaccion)
	{
		document.form1.action = estaaccion;
		document.form1.submit();
	}
</script>
</html>
