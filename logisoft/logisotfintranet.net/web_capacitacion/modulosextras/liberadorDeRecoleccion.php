<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_SESSION[modulosextras]!="SI"){
		die("<script>document.location.href='index.php';</script>");
	}
	
	if($_POST[accion]=='guardar'){
		
		$unidad = $_POST[txtUnidad];
		$edoAlmacen = $_POST[ddlEdoAlmacen];
										
		$s = "UPDATE guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gu ON gv.id = gu.idguia
		SET gv.estado = '$edoAlmacen', gu.proceso = '$edoAlmacen',
		gu.unidad = '', gu.ubicacion = if('$edoAlmacen' = 'ALMACEN DESTINO',gv.idsucursaldestino, gv.idsucursalorigen)
		WHERE gu.unidad = '$unidad' AND gu.proceso = 'RECOLECTADA'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales gv
		INNER JOIN guiasempresariales_unidades gu ON gv.id = gu.idguia
		SET gv.estado = '$edoAlmacen', gu.proceso = '$edoAlmacen',
		gu.unidad = '', gu.ubicacion = if('$edoAlmacen' = 'ALMACEN DESTINO',gv.idsucursaldestino, gv.idsucursalorigen)
		WHERE gu.unidad = '$unidad' AND gu.proceso = 'RECOLECTADA'";
		mysql_query($s,$l) or die($s);
		
		?><script>document.location.href='liberadorDeRecoleccion.php?accion=guardar'</script><?		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PMM</title>
</head>
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>

<script src="../javascript/ClaseMensajes.js"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<script>
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript',false);

	function valObligatorios()
	{  
		if(document.all.txtUnidad.value.length == "")
		{
			alert("Debe capturar la Unidad");
			return false;
		}
		
		if(document.all.ddlEdoAlmacen.value == "0")
		{
			alert("Debe seleccionar un Estado de Almacen");
			return false;
		}
		
		return true;
	}
	
</script>

<body>

	<form name="form1" action="" method="POST">
		<table style="margin:auto" align="center">
			<tr>
				<td align="center" colspan="2">
					<B>Liberador de Recoleccion</B>
				</td>
			</tr>	
			<tr><td colspan="2"><br /></td></tr>
			<tr>
				<td align="right">
					<label id="lblUnidad"><strong>Unidad:</strong></label>				
				</td>
				<td>
					<input type="text" name="txtUnidad" width="80px" />
				</td>
			</tr>	
			<tr>
				<td align="right">
					<label id="lblEdoAlmacen"><strong>Estado Almacen:</strong></label>					
				</td>
				<td>
					<select name="ddlEdoAlmacen">
						<option value="0">Seleccione una opcion</option>
						<option value="ALMACEN ORIGEN">ALMACEN ORIGEN</option>
						<option value="ALMACEN DESTINO">ALMACEN DESTINO</option>
					</select>
				</td>
			</tr>	
			<tr>			
				<td colspan="2" align="center">
					<br />
					<img src="../img/Boton_Guardar.gif" onClick="if(valObligatorios()){enviarDatos()}" />
					<input type="hidden" name="accion" id="accion" />
				</td>
			</tr>
		</table>
	</form>
	<script>
		function enviarDatos(){
			mens.show("C","¿Desea liberar las guias?","","","enviar()");
		}
		
		function enviar(){
			document.getElementById('accion').value = 'guardar';
			document.form1.submit();
		}
		
		<? 
			if($_GET[accion]=='guardar'){
		?>
				mens.show("I","Datos Guardados","ATENCION");
		<?
			}
		?>
	</script>

</body>
</html>
