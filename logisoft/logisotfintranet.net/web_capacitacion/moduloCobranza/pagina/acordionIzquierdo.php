<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

	require_once("../../Conectar.php");

	$l = Conectarse("webpmm");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />	

	<link href="../css/generalStyles.css" rel="stylesheet" type="text/css" />

	<link href="../css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="../../javascript/ClaseAcordeon.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="estilosPrincipal.css" rel="stylesheet" type="text/css">

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

<table width="210" border=0 align=center cellpadding=0 cellspacing=0>

  

  <tr>

    <td align="center"><input type="hidden" name="idsucursal" value="<?=$f->idsuc?>"></td>

  </tr>

  <tr>

    <td align="left"><ul class="acc" id="acc">

	<li>

		<h3>Cobranza</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/liquidaciondemercancia.php')">Relación Cob. Facturas a Revisión (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/liquidaciondemercancia.php')">Relación Cob. Facturar para Cobranza (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/liquidaciondemercancia.php')">Liquidación Cobranza (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/liquidaciondemercancia.php')">Cobranza > 30 Dias</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/liquidaciondemercancia.php')">Cobranza > 60 Dias</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/liquidaciondemercancia.php')">Abono Cliente</td>													

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

		<h3>Guias</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../facturacion/Facturacion.php')">Cotizador de Guías</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../creditoycobranza/relaciondecobranzaaldia.php')">Consulta de Guías</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../creditoycobranza/liquidacionCobranza.php')">Nota Crédito</td>													

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

		<h3>Crédito</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../Caja/cierrePrincipal.php')">Solicitud de Credito Pend. por Autorizar</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../Caja/cierrePrincipal.php')">Solicitud de Credito Pend. por Activar</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../Caja/cierrePrincipal.php')">Créditos con Limites Saturados (0)</td>													

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

		<h3>Cancelaciones</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td  onclick="mostrarPagina('../../general/cobranza/principal.php')">Locales Autorizadas para Cancelar</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../general/ingresos/principal.php')">Foráneas Aut. para Sustituir (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../general/ingresos/principal.php')">Foráneas Aut. para Cancelar (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../general/ingresos/principal.php')">Pendientes de Autorizar (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../general/ingresos/principal.php')">Guias Canceladas (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../general/ingresos/principal.php')">Guias sin Ruta Cliente Corporativo</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../general/ingresos/principal.php')">Guias Empresariales Pendientes</td>													

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

		<h3>Entregas</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportargastos.php')">Generar Reparto EAD</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php')">Devolución EAD</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reporteconsolidadogastosgastoschica.php')">Liquidaciones EAD (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportesgastosprepagados.php')">Guias Faltantes de Liquidacion EAD (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Entregas Ocurre en Sucursal</td>													

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

		<h3>Facturación</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportargastos.php')">Facturación</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php')">Facturas Canceladas (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reporteconsolidadogastosgastoschica.php')">Guías de Vent. Pend. de Facturar (0) </td>													

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

		<h3>Clientes</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportargastos.php')">Agregar CP</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php')">Agregar Colonias</td>

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

		<h3>Caja</h3>

		<div class="acc-section">

			<div class="acc-content">

				<table  border="0" cellpadding="0" cellspacing="0">

						<tr>

							<td align="left">

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportargastos.php')">Inicio Día</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php')">Cierre Día</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportargastos.php')">Cierre Caja Principal</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php')">Reportes Gastos</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php')">Reportes Gastos Caja Chica</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php')">Reportes Gastos Proveedor</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="205" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../cajachica/reportegastoscajachica.php')">Reportes Gastos Prepagado</td>

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

		<h3>Reporte de Ventas</h3>

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

													<td onclick="mostrarPagina('')">Reporte de Ventas</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte por Tipo de Venta</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte por Condición de Pago</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte de Ventas por Guías Prepagadas</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Servicios Pendientes de Facturar (Prepagadas)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Guías y Servicios</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Desglose de Venta Contado</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Ventas con Convenio por Cliente</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

							</td>

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

							  <td onclick="mostrarPagina('../../Caja/iniciocaja.php')">Clientes</td>

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

		<h3>Reporte de Clientes</h3>

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

													<td onclick="mostrarPagina('')">Convenio por Sucursal</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Ventas por Convenio</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Convenios Vigentes Vencidos</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Ventas con Convenio Facturadas</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Ventas con Convenio Sin Facturar</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Prepagadas sin Facturar</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Consignación sin Facturar</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Ventas con Convenio Facturadas</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Relaciones de Envió Facturados por Cliente</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Tipo de Convenios</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte Historial de Clientes</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

							</td>

						</tr>					

					</table>

			</div>

		</div>

	</li>

	<li>

		<h3>Reporte de Ingresos</h3>

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

													<td onclick="mostrarPagina('')">Ingresos</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Conciliación Ingresos</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Diferencias en Conciliación</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Ingresos por Guías de Contado</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Ingresos por Cobranza</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Ingresos por Guías Entregadas</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Depósitos de Ingresos</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Relación de Cheques Depositados</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Descripción de Nota de Crédito</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>								

							</td>

						</tr>					

					</table>

			</div>

		</div>

	</li>

	<li>

		<h3>Reporte de Cobranza</h3>

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

													<td onclick="mostrarPagina('')">Estados de Cuenta por Cobrar</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Antigüedad de Saldos</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Clientes con Crédito</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Historial de Línea de Crédito</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Estados de Cuenta</td>													

												</tr>												

											</table>										</td>										

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

