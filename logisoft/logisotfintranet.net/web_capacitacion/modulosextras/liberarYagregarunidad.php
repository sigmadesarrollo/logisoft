<?
	session_start();
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	
	if($_SESSION[modulosextras]!="SI"){
		die("<script>document.location.href='index.php';</script>");
	}
	
	if($_POST[accion]=='guardar')
	{	
			
			$s = "UPDATE guiaventanilla_unidades 
			SET unidad = '$_POST[nuevaunidad]'
			WHERE unidad = '$_POST[unidadanterior]'";
			mysql_query($s,$link) or die($s);
			
			$s = "UPDATE guiasempresariales_unidades 
			SET unidad = '$_POST[nuevaunidad]'
			WHERE unidad = '$_POST[unidadanterior]'";
			mysql_query($s,$link) or die($s);
			
			$s = "SELECT embarcado,recepcionado,ubicacion FROM catalogounidad WHERE numeroeconomico = '$_POST[unidadanterior]'";
			$r = mysql_query($s,$link) or die($s);
			$f = mysql_fetch_object($r);
			
			$s = "UPDATE catalogounidad
			SET embarcado = '$f->embarcado',
			recepcionado = '$f->recepcionado',
			ubicacion =  '$f->ubicacion',
			enuso = 1
			WHERE numeroeconomico = '$_POST[nuevaunidad]'";
			mysql_query($s,$link) or die($s);
			
			$s = "UPDATE catalogounidad
			SET embarcado = 'N',
			recepcionado = 'N',
			ubicacion =  NULL,
			enuso = 0
			WHERE numeroeconomico = '$_POST[unidadanterior]'";
			mysql_query($s,$link) or die($s);
			
			$s = "SELECT folio FROM bitacorasalida 
			WHERE unidad = '$_POST[unidadanterior]' AND STATUS = 0 AND cancelada = 0;";
			$rx = mysql_query($s,$link) or die($s);
			$fx = mysql_fetch_object($rx);
			$foliobitacora = $fx->folio;
			
			$s = "UPDATE programacionrecepciondiaria 
			SET unidad  = '$_POST[nuevaunidad]'
			WHERE idbitacora = '$foliobitacora';";
			mysql_query($s,$link) or die($s);
			
			$s = "UPDATE bitacorasalida 
			SET unidad  = '$_POST[nuevaunidad]'
			WHERE folio = '$foliobitacora'";
			mysql_query($s,$link) or die($s);
			
			if($_POST[des_servicio] !='' && $_POST[des_servicio] !='0')
			{
				$s = "UPDATE catalogounidad
				SET fueradeservicio = 1, desservicio = $_POST[des_servicio]
				WHERE numeroeconomico = '$_POST[unidadanterior]'";
				mysql_query($s,$link) or die($s);
			}
			
			?><script>document.location.href='liberarYagregarunidad.php?accion=guardar'</script><?		
			
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
		if(document.all.unidadanterior.value == "")
		{
			alert("Debe capturar la Unidad anterior","¡Atencion!");
			return false;
		}
		
		if(document.all.nuevaunidad.value == "")
		{
			alert("Debe capturar la Unidad Nueva","¡Atencion!");
			return false;
		}
		
		if(document.all('servicio').checked==true && document.all.des_servicio.value==0)
		{
			alert('Debe seleccionar un Servico','¡Atención!');
			return false;
		}
		
		return true;
	}
	
</script>

<body>

	<form name="form1" action="" method="POST">
		<table style="margin:auto" align="center">
			<tr>
				<td align="center" colspan="4">
					<B>Liberador y Asignación de Unidades</B>
				</td>
			</tr>	
			<tr><td colspan="4"><br /></td></tr>
			<tr>
				<td align="right">
					<label id="lblUnidadAnterior"><strong>Unidad Anterior:</strong></label>				
				</td>
				<td>
					<select name="unidadanterior" id="unidadanterior" class="Tablas" style="width:100px; text-transform:uppercase" >
                        <option>SELECCIONE</option>
                        <?
					  $sqlt="SELECT UCASE(numeroeconomico) as numeroeconomico 
							 FROM catalogounidad
							 WHERE tiporuta = 'FORANEA'
							 ORDER BY numeroeconomico ASC";
					  $result=mysql_query($sqlt,$link);
					  while($row=mysql_fetch_array($result)){ 			  
						?>
                        <option value="<?=$row[numeroeconomico] ?>" <? if($numeroeconomico==$row[0]){echo "selected";} ?> >
                        <?=$row[0]; ?>
                        </option>
                        <?	}   ?>
                      </select>
				</td>
				<td align="right">
					<label id="lblNuevaUnidad"><strong>Unidad Nueva:</strong></label>					
				</td>
				<td>
					<select name="nuevaunidad" id="nuevaunidad" class="Tablas" style="width:100px; text-transform:uppercase" >
                        <option>SELECCIONE</option>
                        <?
					  $sqlt="SELECT UCASE(numeroeconomico) as numeroeconomico 
					  		 FROM catalogounidad
							 WHERE tiporuta = 'FORANEA' AND fueradeservicio = 0
							 ORDER BY numeroeconomico ASC";
					  $result=mysql_query($sqlt,$link);
					  while($row=mysql_fetch_array($result)){ 			  
						?>
                        <option value="<?=$row[numeroeconomico] ?>" <? if($numeroeconomico==$row[0]){echo "selected";} ?> >
                        <?=$row[0]; ?>
                        </option>
                        <?	}   ?>
                      </select>
				</td>
			</tr>	
			<tr><td colspan="4"><br></td></tr>
			<tr>
				<td colspan="4">
					<label id="lblfueraservicio"><strong>Selecciona el check box si deseas poner la unidad anterior como fuera de servicio:</strong></label>		
				</td>
			</tr>
			<tr>				
				<td align="right">
					<input type="checkbox" name="servicio" value="0" onClick="if(document.all.servicio.checked==true){document.all.servicio.value=1; document.all.des_servicio.disabled=false;  }else{document.all.servicio.value=0; document.all.des_servicio.disabled=true;document.all.des_servicio.value=0; }">
				</td>
				<td>
					<select name="des_servicio" class="Tablas"  style="text-transform:uppercase; width:180px" disabled="disabled">
						<option value="0"></option>
						<? 
							$sqlt = "SELECT id,descripcion FROM fueradeservicio";
							$result=mysql_query($sqlt,$link);
							while($row=mysql_fetch_array($result)){ 			  
						?>
                        <option value="<?=$row[id] ?>" <? if($descripcion==$row[0]){echo "selected";} ?> >
                        <?=$row[1]; ?>
                        </option>
                      	  <? } ?>          
						</select>  
				</td>
			</tr>
			<tr>
				
			</tr>	
			<tr>			
				<td colspan="4" align="center">
					<br />
					<img src="../img/Boton_Guardar.gif" onClick="if(valObligatorios()){enviarDatos()}" />
					<input type="hidden" name="accion" id="accion" />
				</td>
			</tr>
		</table>
	</form>
	<script>
		function enviarDatos(){
			mens.show("C","¿Desea liberar la unidad y asignar una nueva?","","","enviar()");
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
