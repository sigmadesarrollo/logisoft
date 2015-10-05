<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_POST[accion]=='guardar'){
		
		$s = "INSERT INTO tablasguiasclientes
			SELECT guiaempresarial id,'no' borrar
			FROM evaluacionmercancia 
			WHERE estado = 'GUARDADO' AND sucursal = '$_POST[sucursal]' AND guiaempresarial <> ''
			UNION
			SELECT id,'no' borrar 
			FROM guiasventanillaclientes 
			WHERE estado = 'GUARDADA' AND idremitente = '$_POST[txtnumCliente]' and impreso = 'S';";
		mysql_query($s,$l) or die($s);
			
		$s ="UPDATE tablasguiasclientes gc
			INNER JOIN guiasempresariales g ON gc.id = g.id
			SET gc.borrar = 'si';";
 		mysql_query($s,$l) or die($s);

		$s = "DELETE FROM tablasguiasclientes WHERE borrar = 'si';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE tablasguiasclientes set borrar = 'si';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE tablasguiasclientes gc
			INNER JOIN guiasventanillaclientes g ON gc.id = g.id 
			SET gc.borrar = 'no';";
		mysql_query($s,$l) or die($s);
			
		$s = "DELETE FROM tablasguiasclientes WHERE borrar = 'si';";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO guiasempresariales 
			(id,estado,tipoflete,tipopago,tipoguia,
			fecha,iddireccionremitente,
			ocurre, idsucursalorigen, iddestino, 
			idsucursaldestino, idremitente,iddestinatario, iddirecciondestinatario, entregaocurre, entregaead,
					restrinccion, totalpaquetes, totalpeso, totalvolumen, emplaye, bolsaempaque, 
					totalbolsaempaque, avisocelular, celular, valordeclarado, acuserecibo, cod, recoleccion, 
					observaciones, tflete, tdescuento, ttotaldescuento, tcostoead, trecoleccion, 
					tseguro, totros, texcedente, tcombustible, subtotal, tiva, ivaretenido, total, 
					efectivo, cheque, banco, ncheque, clienteconvenio, sucursalconvenio, idvendedorconvenio, 
					nvendedorconvenio, convenioaplicado, idsolicitudguia, tarjeta, trasferencia, usuario,ubicacion,sector,
					idusuario, fecha_registro, hora_registro)
			SELECT gc.id,'ALMACEN ORIGEN',IF(gc.tipoflete=0,'PAGADA','POR COBRAR'), IF(gc.condicionpago=0,'CONTADO','CREDITO'),
			 		'CONSIGNACION', CURRENT_DATE,gc.iddireccionremitente, gc.ocurre, gc.idsucursalorigen, gc.iddestino, 
					gc.idsucursaldestino, gc.idremitente, gc.iddestinatario, gc.iddirecciondestinatario, gc.entregaocurre, 
					gc.entregaead, gc.restrinccion, gc.totalpaquetes, gc.totalpeso, gc.totalvolumen, gc.emplaye, gc.bolsaempaque, 
					gc.totalbolsaempaque, gc.avisocelular, gc.celular, gc.valordeclarado, gc.acuserecibo, gc.cod, gc.recoleccion, 
					gc.observaciones, 41, 0, 0, 0, 0, 0, 0, 0, 0, 41, 6.56, 1.64, 45.92, gc.efectivo, gc.cheque, gc.banco, gc.ncheque,  
					gc.clienteconvenio, gc.sucursalconvenio, gc.idvendedorconvenio, gc.nvendedorconvenio, gc.convenioaplicado, 0,
					gc.tarjeta, gc.trasferencia, gc.usuario,gc.ubicacion,0, gc.idusuario, CURRENT_DATE, CURRENT_TIME 
			FROM guiasventanillaclientes gc
			INNER JOIN tablasguiasclientes pgc ON gc.id = pgc.id;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO guiasempresariales_detalle (id,cantidad,descripcion,contenido,peso,largo,ancho,alto,volumen,importe)
			SELECT gcd.idguia,gcd.cantidad,gcd.descripcion,gcd.contenido,gcd.peso,gcd.largo,gcd.ancho,gcd.alto,gcd.volumen,gcd.importe
			FROM guiasventanillaclientes_detalle gcd
			INNER JOIN tablasguiasclientes pgc ON gcd.idguia = pgc.id;";
		mysql_query($s,$l) or die($s);
			
		$s = "INSERT INTO pagoguias (guia,tipo,total, fechacreo,usuariocreo,sucursalcreo, cliente,credito, sucursalacobrar)
			SELECT g.id,'EMPRESARIAL',g.total, CURRENT_DATE,1,g.idsucursalorigen, g.idremitente,'SI', g.idsucursalorigen
			FROM guiasempresariales g
			INNER JOIN tablasguiasclientes pgc ON g.id = pgc.id;";
		mysql_query($s,$l) or die($s);

		$s = "CALL meterPaquetesClien();";
		mysql_query($s,$l) or die($s);

		$s = "UPDATE guiasventanillaclientes g
			INNER JOIN tablasguiasclientes pgc ON g.id = pgc.id
			SET estado = 'APLICADA';";
		mysql_query($s,$l) or die($s);	
		
		$s = "UPDATE evaluacionmercancia g
			INNER JOIN tablasguiasclientes pgc ON g.guiaempresarial = pgc.id
			SET estado = 'ENGUIA';";
		mysql_query($s,$l) or die($s);
		
		?><script>document.location.href='elaborarGuiasEmpresariales.php?accion=guardar'</script><?		
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

	function validacion()
	{  
		if(document.all.txtnumCliente.value.length == "")
		{
			alert("Debe capturar el Numero del Cliente");
			return false;
		}
		
		if(document.all.sucursal.value == "0")
		{
			alert("Debe seleccionar una sucursal");
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
					<B>ELABORACION EMPRESARIALES</B>
				</td>
			</tr>
			<tr><td colspan="2"><br /></td></tr>
			<tr>
				<td align="right">
					<label id="lblnumCliente"><strong># Cliente:</strong></label>
				</td>
				<td>
					<input type="text" name="txtnumCliente" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label id="lblPrepagada"><strong>Sucursal Origen:</strong></label>
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
				<td colspan="2" align="center">
					<br />
					<img src="../img/Boton_Guardar.gif" onClick="if(validacion()){enviarDatos()}" />
					<input type="hidden" name="accion" id="accion" />
				</td>
			</tr>
		</table>
	</form>
	<script>
		function enviarDatos(){
			mens.show("C","¿Desea documentar las guias Empresariales?","","","enviar()");
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
