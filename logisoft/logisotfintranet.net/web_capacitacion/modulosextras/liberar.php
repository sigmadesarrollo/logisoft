<?
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	if($_POST[accion]=='guardar'){
		
		$guiasacambiar = "'".str_replace(",","','",$_POST[guias]);
												
		$s = "UPDATE guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gu ON gv.id = gu.idguia
		SET gv.estado = 'ALMACEN DESTINO', gu.proceso = 'ALMACEN DESTINO',
		gu.unidad = '', gu.ubicacion = gv.idsucursaldestino
		WHERE gv.id IN ($guiasacambiar) AND gv.estado <> 'ENTREGADA' AND gv.tipoflete <> 1 AND gv.condicionpago <> 0";
		echo $s;
		mysql_query($s,$l) or die($s);
		
		
		$s = "UPDATE guiasempresariales gv
		INNER JOIN guiasempresariales_unidades gu ON gv.id = gu.idguia
		SET gv.estado = 'ALMACEN DESTINO', gu.proceso = 'ALMACEN DESTINO',
		gu.unidad = '', gu.ubicacion = gv.idsucursaldestino
		WHERE gv.id IN ($guiasacambiar) AND gv.estado <> 'ENTREGADA' AND gv.tipoflete <> 'POR COBRAR' AND gv.tipopago <> 'CONTADO'";
		echo $s;
		mysql_query($s,$l) or die($s);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<script src="../javascript/ClaseMensajes.js"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>
<script>
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript',false);
</script>
<body>
<form name="form1" action="" method="POST">
<table width="576" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="572" class="FondoTabla Estilo4">CAMBIAR GUIAS A DESTINO</td>
  </tr>
  <tr>
    <td height="170">
    <table width="574">
    	<tr>
        	<td>&nbsp;</td>
        </tr>
    	<tr>
    	  <td>&nbsp;</td>
  	  </tr>
    	<tr>
    	  <td align="center">
          	<img src="../img/Boton_Guardar.gif" onclick="enviarDatos()" />
          </td>
  	  </tr>
    </table>
    </td>
  </tr>
</table>
<input type="hidden" name="accion" id="accion" />
</form>
<script>
	function enviarDatos(){
		mens.show("C","Desea cambiar las guias a estado 'ALMACEN DESTINO'","CAMBIAR ESTADO","","enviar()");
	}
	
	function enviar(){
		document.getElementById('accion').value = 'guardar';
		document.form1.submit();
	}
</script>
</body>
</html>