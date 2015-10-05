<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_SESSION[modulosextras]!="SI"){
		die("<script>document.location.href='index.php';</script>");
	}
	
	if($_POST[accion]=='guardar')
	{		
		if($_POST[todassucursales] == '')
		{						 
			$idsucursal = " AND gv.idsucursaldestino =  '$_POST[sucursal]'";
		}
		
		$s = "UPDATE guiasempresariales gv
			INNER JOIN entregasocurre_detalle od  ON gv.id = od.guia
			INNER JOIN entregasocurre o ON od.entregaocurre = o.folio AND o.idsucursal = od.sucursal
			SET gv.recibio = o.personaquerecibe,
			gv.tipoidentificacion = o.tipodeidentificacion,
			gv.numeroidentificacion = o.numeroidentificacion,
            gv.estado = 'ENTREGADA'
			WHERE gv.estado = 'POR ENTREGAR' $idsucursal;";
		mysql_query($s,$l) or die($s);
						
		$s ="UPDATE guiasempresariales gv
			INNER JOIN guiasempresariales_unidades gu ON gv.id = gu.idguia
			SET gv.estado = 'ENTREGADA', gu.proceso = 'ENTREGADA'
			WHERE gv.estado = 'POR ENTREGAR' $idsucursal;";
 		mysql_query($s,$l) or die($s);
			
		$s = "UPDATE guiasventanilla gv
			INNER JOIN entregasocurre_detalle od  ON gv.id = od.guia
			INNER JOIN entregasocurre o ON od.entregaocurre = o.folio AND o.idsucursal = od.sucursal
			SET gv.recibio = o.personaquerecibe,
			gv.tipoidentificacion = o.tipodeidentificacion,
			gv.numeroidentificacion = o.numeroidentificacion,
			gv.estado = 'ENTREGADA'
			WHERE gv.estado = 'POR ENTREGAR' $idsucursal;";
		mysql_query($s,$l) or die($s);
					
		$s = "UPDATE guiasventanilla gv
			INNER JOIN guiaventanilla_unidades gu ON gv.id = gu.idguia
			SET gv.estado = 'ENTREGADA', gu.proceso = 'ENTREGADA'
			WHERE gv.estado = 'POR ENTREGAR' $idsucursal;";
		mysql_query($s,$l) or die($s);	
			
		?><script>document.location.href='cambioEdoXEntregarAEntregada.php?accion=guardar'</script><?	
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
		
	function f_todas(valor){
		document.all.sucursal.value = "0";
		document.all.sucursal.disabled = (valor)?true:false;
		document.all.sucursal.style.backgroundColor = (valor)?"#FFFF99":"";
	}
	
	
	function validacion()
	{  
		if(document.all.todassucursales.checked==false && document.all.sucursal.value == "0")
		{			
			mens.show("A","Debe capturar la Sucursal","¡Atención!","sucursal");
			return false;
		}
					
		return true;
	}
	
</script>

<body>
	<form name="form1" action="" method="POST">
		<table style="margin:auto" align="center">
			<tr>
				<td align="center" colspan="3">
					<B>CAMBIAR ESTADO POR ENTREGAR A ENTREGADOS</B>
				</td>
			</tr>
			<tr><td colspan="2"><br /></td></tr>
			<tr>
				<td align="left" colspan="2">
					<label id="lblsucursal"><strong>Sucursal Destino:</strong></label>
				</td>
			</tr>
			<tr>
				<td>
					<label id="lblSucursalTodas">Todas</label>
					<input type="checkbox" name="todassucursales" value="si" onClick="f_todas(this.checked)" /> 					
				</td>				
				<td>
					<select name="sucursal" style="width:150px; font-family:Verdana, Geneva, sans-serif; font-size:12px">
						<option value="0">Seleccione una sucursal</option>
						<?
							$s = "select * from catalogosucursal order by descripcion";
							$r = mysql_query($s,$l) or die($s);
							while($f = mysql_fetch_object($r)){
						?>		
						<option value="<?=$f->id?>"><?=strtoupper(utf8_encode($f->descripcion))?>
						</option>		
						<?
							}
							
						?>
					</select>
				</td>		
			</tr>			
			<tr>			
				<td colspan="3" align="center">
					<br />
					<img src="../img/Boton_Guardar.gif" onClick="if(validacion()){enviarDatos()}" />
					<input type="hidden" name="accion" id="accion" />
				</td>
			</tr>
		</table>
	</form>
	<script>
		function enviarDatos(){
			mens.show("C","¿Desea cambiar el estado Por Entregar a Entregado?","","","enviar()");
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
