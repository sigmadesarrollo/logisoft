<? session_start();	

	require_once("../../Conectar.php");

	$l = Conectarse("webpmm");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />	

	<link href="../../moduloOperaciones/css/generalStyles.css" rel="stylesheet" type="text/css" />

	<link href="../../moduloOperaciones/css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="../../javascript/ClaseAcordeon.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../../moduloOperaciones/pagina/estilosPrincipal.css" rel="stylesheet" type="text/css">

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

		<h3>PROSPECTO</h3>

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

													<td onclick="mostrarPagina('')">Directorio de prospectos</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../almacen/devolucionGuia.php')">Propuestas de Conv. Pend. Autorizar (0)</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Propuestas de Conv. Autorizadas (0)</td>

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Webmarketing</td>

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

		<h3>CLIENTES</h3>

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

													<td onclick="mostrarPagina('')">Agregar CP</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Agregar Colonias</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../recepcion/recepcionMercancia.php')">Solicitud de Guía Empresarial</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../almacen/entregademercanciaocurre.php')">Datos Personales</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../almacen/traspasarmercancia.php')">Datos Fiscales</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Datos Comerciales</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Propuestas de Convenios Pendientes de Autorizar()</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Convenios Pendientes de Autorizar()</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Convenios Pendientes de Activar()</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Convenios por Vencer()</td>													

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

		<h3>EAD</h3>

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

													<td onclick="mostrarPagina('')">Guías Foráneas</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')" >Cliente Corporativo</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')" >Guías Faltantes de Liquidación EAD</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')" >Liquidaciones EAD (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')" >Entregas Atrasadas (0)</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/repartoMercanciaEad.php')">Reparto EAD Automático</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/asignacionmanualead.php')" >Reparto EAD Manual</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/devoluciondemercanciaaalmacen.php')" >Devolución Mercancía EAD</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../entregas/liquidaciondemercancia.php')" >Liquidación Mercancía EAD</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Guías para EAD</td>													

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

		<h3>Ocurre</h3>

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

													<td onclick="mostrarPagina('../../entregas/entregaocurre.php')">Entregas Ocurre en Sucursal</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../almacen/entregademercanciaocurre.php')">Entregas Ocurre en Almacén</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Entregas Atrasadas()</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Guías para Entregas Ocurre</td>													

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

		<h3>Embarques</h3>

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

													<td onclick="mostrarPagina('../../embarque/programaciondeembarquediarias.php')">Programación Recepción Diaria</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../embarque/embarquedemercancia.php')">Automático</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../embarque/embarquedemercanciamanual.php')">Manual</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Guías No embarcadas()</td>													

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

		<h3>Recepciones</h3>

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

													<td onclick="mostrarPagina('../../recepcion/programacionRecepcionDiaria.php')">Programación Recepción Diaria</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../recepcion/recepcionMercancia.php')">Automático</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Manual</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../recepcion/historicoDanosFaltantes.php')">Reporte Histórico Daños y Faltantes</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Guías Por Recibir()</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Guías Con Faltantes()</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Guías Con Daños()</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Guías Con Sobrantes()</td>													

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

		<h3>Recolecciones</h3>

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

													<td onclick="mostrarPagina('../../recoleccion/recoleccion.php?idsucorigen=<?=$_SESSION[IDSUCURSAL]?>&fecha=<?=date("d/m/Y",time()); ?>')">Registrar Recolección</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('../../recoleccion/recoleccionMercancia.php')">Agenda de Recolecciones</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Recolecciones Atrasadas()</td>													

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

	<h3>Catálogos</h3>

	<div class="acc-section">

		<div class="acc-content">

			<table  border="0" cellpadding="0" cellspacing="0">

				<tr>

				  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">

					  <tr>

						<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">

							<tr>

							  <td onclick="mostrarPagina('../../Caja/iniciocaja.php')">Motivos</td>

							</tr>

						</table></td>

					  </tr>

					</table>

					  <table width="200" border="0" cellpadding="0" cellspacing="0">

						<tr>

						  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">

							  <tr>

								<td onclick="mostrarPagina('../../Caja/cierrecaja.php')">Rutas</td>

							  </tr>

						  </table></td>

						</tr>

					  </table>

					  <table width="200" border="0" cellpadding="0" cellspacing="0">

						<tr>

						  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">

							  <tr>

								<td onclick="mostrarPagina('')">Sector</td>

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

				  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">

					  <tr>

						<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">

							<tr>

							  <td onclick="mostrarPagina('../../Caja/iniciocaja.php')">Configurador Recolecciones Programadas</td>

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

		<h3>Reporte de Logistica</h3>

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

													<td onclick="mostrarPagina('')">Rutas</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Descripción de Ruta</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Estadísticas del Operador</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte Embarque Consolidado</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Incidentes de Ruta</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Unidades</td>													

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

		<h3>Reporte de Operación</h3>

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

													<td onclick="mostrarPagina('')">Rentabilidad por Ruta</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Informe de Ruta</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Productividad por Ruta</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Gastos por Ruta</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila1">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Reporte de Incidentes</td>													

												</tr>												

											</table>										</td>										

									</tr>

								</table>

								<table width="200" border="0" cellpadding="0" cellspacing="0">

									<tr>

										<td colspan="3" class="fila2">

											<table cellpadding="0" cellspacing="0" border="0">

												<tr>

													<td onclick="mostrarPagina('')">Relación de Guías de Embarque</td>													

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

