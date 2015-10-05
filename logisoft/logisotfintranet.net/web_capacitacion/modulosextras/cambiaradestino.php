<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_SESSION[modulosextras]!="SI"){
		die("<script>document.location.href='index.php';</script>");
	}
	
	if($_POST[accion]=='guardar'){
		
		$guiasacambiar = "'".str_replace(",","','",$_POST[guias])."'";
												
		$s = "UPDATE guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gu ON gv.id = gu.idguia
		SET gv.estado = 'ALMACEN DESTINO', gu.proceso = 'ALMACEN DESTINO',
		gu.unidad = '', gu.ubicacion = gv.idsucursaldestino
		WHERE gv.id IN ($guiasacambiar)
		AND gv.estado not like '%ENTREG%' AND gv.estado not like '%POR EN%' and gv.estado not like '%CANCE%'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales gv
		INNER JOIN guiasempresariales_unidades gu ON gv.id = gu.idguia
		SET gv.estado = 'ALMACEN DESTINO', gu.proceso = 'ALMACEN DESTINO',
		gu.unidad = '', gu.ubicacion = gv.idsucursaldestino
		WHERE gv.id IN ($guiasacambiar) 
		AND gv.estado not like '%ENTREG%' AND gv.estado not like '%POR EN%' AND gv.estado not like '%CANCE%'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO historial_cambiodeguias
		SET guias = '$_POST[guias]';";
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
        	<td>PROPORCIONE LAS GUIAS SEPARADAS POR COMAS</td>
        </tr>
    	<tr>
    	  <td>
          	<textarea name="guias" rows="9" style="width:98%"></textarea>
          </td>
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
	
	<? 
		if($_POST[accion]=='guardar'){
	?>
			mens.show("I","Datos Guardados","ATENCION");
	<?
		}
	?>
</script>
</body>
</html>