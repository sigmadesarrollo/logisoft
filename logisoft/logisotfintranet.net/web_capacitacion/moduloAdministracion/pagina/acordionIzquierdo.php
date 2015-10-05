<? session_start();	

	require_once("../../Conectar.php");

	$l = Conectarse("webpmm");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />	

	<link href="../../moduloAdministracion/css/generalStyles.css" rel="stylesheet" type="text/css" />

	<link href="../../moduloAdministracion/css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="../../javascript/ClaseAcordeon.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../../moduloAdministracion/pagina/estilosPrincipal.css" rel="stylesheet" type="text/css">

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

</head>

<body>

<script>

	function mostrarPagina(pagina){

		parent.document.all.pagina.src = pagina;

	}

</script>

<table width="230" border=0 align=center cellpadding=0 cellspacing=0>

  

  <tr>

    <td align="center"><input type="hidden" name="idsucursal" value="<?=$f->idsuc?>"></td>

  </tr>

  <tr>

    <td align="left"><ul class="acc" id="acc">

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

													<td onclick="mostrarPagina('')">Cotizador de Guías</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Consulta de Guías</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Devolucion Guías</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>						</td>

					</tr>

				</table>

												<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Elaboración de Guías De Ventanilla</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

																<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Elaboración de Guías Empresarial</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

																<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Historial de Guías</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

																<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Evaluaciones Pendientes de Generar Guías()</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

			</div>

		</div>

	</li>

		<li>

		<h3>Caja</h3>

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

													<td onclick="mostrarPagina('')">Gastos Pendientes de Autorizar()</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte Gastos</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte Gastos Caja Chica</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>						</td>

					</tr>

				</table>

												<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte Gastos Proveedor</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

																<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte Gastos Prepagado</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

																<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte Consolidado Caja Chica</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

																<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte Cheques Para Imprimir</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

			</div>

		</div>

	</li>

	<li>

	<h3>Catálogos</h3>

	<div class="acc-section">

		<div class="acc-content">

			<table  border="0" cellpadding="0" cellspacing="0">

				<tr>

				  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">

					  <tr>

						<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">

							<tr>

							  <td onclick="mostrarPagina('../../Caja/iniciocaja.php')">Tipo Unidad</td>

							</tr>

						</table></td>

					  </tr>

					</table>

					  <table width="200" border="0" cellpadding="0" cellspacing="0">

						<tr>

						  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">

							  <tr>

								<td onclick="mostrarPagina('../../Caja/cierrecaja.php')">Unidad</td>

							  </tr>

						  </table></td>

						</tr>

					  </table>

					  <table width="200" border="0" cellpadding="0" cellspacing="0">

						<tr>

						  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">

							  <tr>

								<td onclick="mostrarPagina('')">Concepto Gastos</td>

							  </tr>

						  </table></td>

						</tr>

					  </table>

					  <table width="200" border="0" cellpadding="0" cellspacing="0">

						<tr>

						  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">

							  <tr>

								<td onclick="mostrarPagina('')">Proveedor</td>

							  </tr>

						  </table></td>

						</tr>

					  </table>

					  <table width="200" border="0" cellpadding="0" cellspacing="0">

						<tr>

						  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">

							  <tr>

								<td onclick="mostrarPagina('')">Tipo Proveedor</td>

							  </tr>

						  </table></td>

						</tr>

					  </table>

					  <table width="200" border="0" cellpadding="0" cellspacing="0">

						<tr>

						  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">

							  <tr>

								<td onclick="mostrarPagina('')">Gastos Liquidación Bitácora</td>

							  </tr>

						  </table></td>

						</tr>

					  </table>					  

					</td>

				</tr>

			</table>

		</div>

	</div>

</li>

<li>

	<h3>Configuradores</h3>

	<div class="acc-section">

		<div class="acc-content">

			<table  border="0" cellpadding="0" cellspacing="0">

				<tr>

				  <td align="left">

				  <table width="200" border="0" cellpadding="0" cellspacing="0">

					  <tr>

						<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">

							<tr>

							  <td onclick="mostrarPagina('../../Caja/iniciocaja.php')">Deposito Caja Chica</td>

							</tr>

						</table></td>

					  </tr>

					</table>

					<table width="200" border="0" cellpadding="0" cellspacing="0">

					  <tr>

						<td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">

							<tr>

							  <td onclick="mostrarPagina('../../Caja/iniciocaja.php')">Precintos</td>

							</tr>

						</table></td>

					  </tr>

					</table>

					</td>

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

