<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery-1.4.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
</link>
<script>
	var u = document.all;
		
	
	function generarReporte(){
		if(document.URL.indexOf("web_capacitacionPruebas/")>-1){		
			window.open("http://www.pmmintranet.net/web_capacitacionPruebas/facturacion/reporteTXT.php?mes="+
						u.cmbMes.value+"&ano="+u.cmbAno.value);
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/facturacion/reporteTXT.php?mes="+
						u.cmbMes.value+"&ano="+u.cmbAno.value);
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("http://www.pmmintranet.net/web_pruebas/facturacion/reporteTXT.php?mes="+
						u.cmbMes.value+"&ano="+u.cmbAno.value);
		}
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">REPORTE MENSUAL DE FACTURACION</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="46">Mes:</td>
          <td width="153">
          	<select name="cmbMes" style="width:100px">
            	<option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
          </td>
          <td width="45">Ano:</td>
          <td width="101">
          	<select name="cmbAno" style="width:100px">
            	<?
					for($i=2010; $i<=date("Y"); $i++){
				?>
						<option value="<?=$i?>"><?=$i?></option>
                <?
					}
				?>
            </select>
          </td>
          <td width="51">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5" align="right"><img src="../img/Boton_Generar.gif" width="74" height="20" style="cursor:pointer" onclick="generarReporte()" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>

</body>
</html>
