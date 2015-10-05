<? session_start();	
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
		<h3>Expediente de Clientes</h3>
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
													<td onclick="mostrarPagina('../../catalogos/cliente/client.php')">Datos Personales</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../catalogos/cliente/client.php')">Datos Fiscales</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../catalogos/cliente/client.php')">Datos Comerciales</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../convenio/generacionconvenio.php')">Convenios</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Historial de Movtos. Guía</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Historial de Movtos. Entregas</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Historial de Movtos. Recolecciones</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Estado de Cta. Compras</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Estado de Cta. Pagos</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Estado de Cta. Vencimientos</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Compromisos de Pagos</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../facturacion/Facturacion.php')">Facturación</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../convenio/solicitudContratoAperturaCredito.php')">Status de Cartera</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../convenio/solicitudContratoAperturaCredito.php')">Límites de Crédito</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">CAT</td>
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
		<h3>Prospecto</h3>
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
													<td onclick="mostrarPagina('../../convenio/generacionconvenio.php')">Convenios 
Pendientes de Autorizar ()</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Propuestas Autorizadas(0)</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
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
													<td onclick="mostrarPagina('../../guias/cotizarguia.php')">Cotizador Guías</td>													
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
													<td onclick="mostrarPagina('')">Historial de Guías</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Valor Declarado</td>
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Valor Declarado a Detalle</td>
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
		<h3>Entregas</h3>
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
													<td onclick="mostrarPagina('')">Cliente Corporativo</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Guías Faltantes de Liquidación EAD</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Liquidaciones EAD()</td>													
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
													<td onclick="mostrarPagina('../../recoleccion/recoleccionMercancia.php')">Agenda de Recolecciones</td>													
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
		<h3>Facturación</h3>
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
													<td onclick="mostrarPagina('../../facturacion/Facturacion.php')">Facturación</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Facturas Canceladas (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Guías de Ventanilla Pendientes de Facturar (0)</td>													
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
		<h3>Guía Empresarial</h3>
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
													<td onclick="mostrarPagina('')">Solicitud Guias Emp. Pend. de Autorizar</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Solicitud Guías Emp. Pend. por Asignar Folios</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../guiasempresariales/imprecionguiasempresariales.php')">Impresión Folios Empresariales</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../guiasempresariales/liberacionguiasempresariales.php')">Liberación Folios Guías Emp.</td>													
												</tr>												
											</table>
										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../guiasempresariales/liberacionguiasempresarialesnoutilizadas.php')">Liberación Folios Guías Emp. No Utilizados</td>													
												</tr>												
											</table>
										</td>										
									</tr>
								</table>
							</td>
						</tr>					
					</table>
			</div>
		</div>
	</li>
	<li>
		<h3>Convenios</h3>
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
													<td onclick="mostrarPagina('')">Propta. Renovación Propuesta</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Propta. Pend. de Autorizar (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Propta. Pend. de Generar Convenio (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../convenio/generacionconvenio.php')">Renovar Convenio</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('../../convenio/generacionconvenio.php')">Cancelar Convenio</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Conv. Pend. de Autorizar (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Conv. Pend. por Imprimir (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Conv. Pend. de Activar (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Convenios por Vencer (0)</td>													
												</tr>												
											</table>
										</td>										
									</tr>
								</table>
							</td>
						</tr>					
					</table>
			</div>
		</div>
	</li>
	<li>
		<h3>CAT</h3>
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
													<td onclick="mostrarPagina('')">Registro de CAT</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Daños y Faltantes (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">EAD mal efectuadas (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila2">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Cancelaciones de Guías (0)</td>													
												</tr>												
											</table>										</td>										
									</tr>
								</table>
								<table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="3" class="fila1">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td onclick="mostrarPagina('')">Guías Extraviadas (0)</td>													
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
