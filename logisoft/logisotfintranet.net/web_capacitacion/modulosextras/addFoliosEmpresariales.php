<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_SESSION[modulosextras]!="SI"){
		die("<script>document.location.href='index.php';</script>");
	}
	
	if($_POST[accion]=='guardar'){
		
		$s = "INSERT INTO solicitudguiasempresariales
			(desdefolio,hastafolio,idcliente,idconvenio,estado,foliotipo,fecha,foliosactivados,tipo,prepagada,condicionpago)										VALUES('$_POST[txtDesdeFolio]','$_POST[txtHastaFolio]',$_POST[txtCte],$_POST[txtConvenio],'GUARDADA',1,CURRENT_DATE,'SI','sistema','$_POST[ddlPrepagada]','$_POST[ddlTipoPago]')";
		mysql_query($s,$l) or die($s);
		?><script>document.location.href='addFoliosEmpresariales.php?accion=guardar'</script><?		
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

	function valLengthGuia()
	{  
		if(document.all.txtDesdeFolio.value.length != 13)
		{
			alert("Debe capturar 13 caracteres en el campo 'Desde Folio'");
			return false;
		}
		
		if(document.all.txtHastaFolio.value.length != 13)
		{
			alert("Debe capturar 13 caracteres en el campo 'Hasta Folio'");
			return false;
		}
		
		if(document.all.txtCte.value.length == "")
		{
			alert("Debe capturar el Numero del Cliente");
			return false;
		}
		
		if(document.all.txtConvenio.value.length == "")
		{
			alert("Debe capturar el Numero del Convenio del Cliente");
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
					<B>REGISTRO DE FOLIOS EMPRESARIALES</B>
				</td>
			</tr>
			<tr><td colspan="2"><br /></td></tr>
			<tr>
				<td align="right">
					<label id="lblDesdeFolio"><strong>Desde Folio:</strong></label>
					<input type="text" name="txtDesdeFolio" maxlength="13" />
				</td>
				<td align="right">				
					<label id="lblHastaFolio"><strong>Hasta Folio:</strong></label>
					<input type="text" name="txtHastaFolio" maxlength="13" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label id="lblCte"><strong># Cliente:</strong></label>
					<input type="text" name="txtCte" maxlength="13" />
				</td>
				<td align="right">				
					<label id="lblConvenio"><strong># Convenio:</strong></label>
					<input type="text" name="txtConvenio" maxlength="13" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label id="lblPrepagada"><strong>Prepagada?:</strong></label>
					<select name="ddlPrepagada">
						<option value="SI">SI</option>
						<option value="NO">NO</option>
					</select>
				</td>
				<td align="right">				
					<label id="lblTipoPago"><strong>Tipo Pago:</strong></label>
					<select name="ddlTipoPago">
						<option value="CREDITO">CREDITO</option>
						<option value="CONTADO">CONTADO</option>
					</select>
				</td>
			</tr>		
			<tr>			
				<td colspan="2" align="center">
					<br />
					<img src="../img/Boton_Guardar.gif" onClick="if(valLengthGuia()){enviarDatos()}" />
					<input type="hidden" name="accion" />
				</td>
			</tr>
		</table>
	</form>
	<script>
		function enviarDatos(){
			mens.show("C","¿Desea registrar Folios Empresariales?","","","enviar()");
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
