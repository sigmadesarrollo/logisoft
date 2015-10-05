<? session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ajax.js"></script>
<script>
	var u = document.all;
	window.onload = function(){
		obtenerCliente();
	}
	
	function obtenerCliente(){		
		if(u.idcliente.value==""){
			setTimeout("obtenerCliente()",500);
		}else{
			consultaTexto("mostrarCliente","consultas_crm.php?accion=0&cliente="+u.idcliente.value);
		}
	}
	
	function mostrarCliente(datos){		
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.cliente.value = obj[0].cliente;
		}
	}
</script>
<link href="../../moduloCRM/css/generalStyles.css" rel="stylesheet" type="text/css" />
<link href="../../moduloCRM/css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">	
<script src="../../javascript/ClaseAcordeon.js"></script>	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../moduloCRM/pagina/estilosPrincipal.css" rel="stylesheet" type="text/css">
<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../javascript/ajaxlist/ajax.js"></script>
<style type="text/css">
body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
.Estilo1 {
	color: #3162A4;
	font-weight: bold;
}
</style>
<style type="text/css">	
	
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
	

</style>
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script>
	function mostrarPagina(pagina){
		parent.document.all.pagina.src = pagina;
	}
</script>
<table width="230" border=0 align=center cellpadding=0 cellspacing=0>  
  <tr>
    <td colspan="2" align="center"><input type="hidden" name="idsucursal" value="<?=$f->idsuc?>" />
      <input name="idcliente" type="hidden" id="cliente_hidden" value="<?=$_GET[cliente] ?>" />
     </td>
  </tr>
  <tr>
    <td width="20">&nbsp;</td>
    <td width="224"><input type="text" class="Tablas" name="cliente" id="cliente" style="width:200px; text-transform:uppercase" onKeyPress="if(event.keyCode==13){obtenerCliente();}" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-clientes.php')" onblur="if(document.all.idcliente.value!=''){obtenerCliente();}" /></td>
  </tr>
  <tr>
    <td colspan="2" align="left"><ul class="acc" id="acc">
	<li>
		<h3>Información Personal</h3>
		<div class="acc-section">
			<div class="acc-content">
				<table  border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left">
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('informacionComerFiscal.php?cliente='+document.all.idcliente.value+'&tipo=comercial&titulo=DATOS COMERCIALES')">Comercial</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('informacionComerFiscal.php?cliente='+document.all.idcliente.value+'&tipo=fiscal&titulo=DATOS FISCALES')">Fiscal</td>
												</tr>
											</table>										</td>										
									</tr>
								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('informacionextra.php?cliente='+document.all.idcliente.value+'&tab=0')">Condiciones de Crédito</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('informacionextra.php?cliente='+document.all.idcliente.value+'&tab=1')">Convenio</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>								</td>
						</tr>					
					</table>
			</div>
		</div>
	</li>
	
	<li>

		<h3>Guias</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('historialGuiaV.php?cliente='+document.all.idcliente.value+'')">Historial Guías Ventanilla</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('historialGuiaE.php?cliente='+document.all.idcliente.value+'')">Historial Guias Empresariales</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('historialSolicitudGuias.php?cliente='+document.all.idcliente.value+'')">Historial Solicitud Guias Empresariales</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>						</td>
					</tr>
				</table>
			</div>
		</div>
	</li>

	

	<li>

		<h3>Logistica</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('historialEnvios.php?cliente='+document.all.idcliente.value+'')">Historial de Envios</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Historial Recolecciones</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>							</td>
						</tr>					
					</table>
			</div>
		</div>
	</li>
	
	<li>

		<h3>Cuentas Por Cobrar</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Historial de Envios</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Historial Recolecciones</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>							</td>
						</tr>					
					</table>
			</div>
		</div>
	</li>
	
	<li>
		<h3>Sus Clientes</h3>
		<div class="acc-section">
			<div class="acc-content">
				<table  border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left">
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Historial de Clientes</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>							</td>
						</tr>					
					</table>
			</div>
		</div>
	</li>
	
    </ul></td>
  </tr> 
</table>
</td>
</tr>
</table>
</body>
</html>
<script>
		var parentAccordion=new TINY.accordion.slider("parentAccordion");
		parentAccordion.init("acc","h3",1,-1);
</script>