<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link  href="../css/estilosclaseacordeon.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../javascript/ClaseAcordeon.js"></script>
<script>
	function mostrarPagina(pagina){
		parent.document.all.pagina.src = pagina;
	}

</script>
</head>

<body>
    <table width="230" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
	  	<td align="left">
			<ul class="acc" id="acc">
			  <li>
				<h3>Guias</h3>
				<div class="acc-section">
					<div class="acc-content">
						<table  border="0" cellpadding="0" cellspacing="0">
							<tr>
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('../../guias/cotizarguia.php')">Cotizador de guías</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Consulta de guías</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('../../guias/guia.php')">Elaboración de guías Ventanilla</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('../../guiasempresariales/guiasempresariales.php')">Elaboración de guía Empresarial</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('../../guias/informacionextra.php')">Historial de guías</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('../../buscadores_generales/buscarEvaluacionGen.php?funcion=pedirDatosEvaluacion&tipo=evaluacion&sucorigen=<?=$_SESSION[IDSUCURSAL]?>')">Evaluaciones pend. de generar guía ()</td>
										  </tr>
									  </table></td>
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
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('')">Locales Autorizadas para cancelar (0)</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Foráneas Autorizaciones para sustituir (0)</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Foráneas Autorizadas para cancelar ()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('../../buscadores_generales/buscarGuiasGen.php?funcion=solicitarGuia&estado=AUTORIZACION PARA CANCELAR')">Pendientes de autorizar()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Guías canceladas()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Guías sin ruta cliente corporativo</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Guías empresariales pendientes</td>
										  </tr>
									  </table></td>
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
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('')">Guías foráneas</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Cliente corporativo</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Guías faltantes de liquidación ead</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Liquidaciones ead()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Entregas ocurre en sucursal</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Entregas ocurre en almacén</td>
										  </tr>
									  </table></td>
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
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('../../recoleccion/recoleccionMercancia.php')">Agenda de recolecciones</td>
										</tr>
									</table></td>
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
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('../../facturacion/Facturacion.php')">Facturación</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Facturas canceladas()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Guías de ventanilla pendientes de facturar()</td>
										  </tr>
									  </table></td>
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
                        <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
                                  <tr>
                                    <td onclick="mostrarPagina('../../catalogos/cliente/client.php')">Registrar Cliente</td>
                                  </tr>
                              </table></td>
                            </tr>
                          </table>
                            <table width="200" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                      <td onclick="mostrarPagina('../../catalogos/cliente/consultaCredito.php?accion=1&titulo=Consulta Credito')">Consultar de Crédito</td>
                                    </tr>
                                </table></td>
                              </tr>
                            </table>
                            <table width="200" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                      <td onclick="mostrarPagina('../../catalogos/cliente/consultaCredito.php?accion=2&titulo=Consulta Convenio')">Consultar Convenio</td>
                                    </tr>
                                </table></td>
                              </tr>
                            </table>
                            <table width="200" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                      <td onclick="mostrarPagina('../../../catalogos/colonias/catalogocodigopostal.php')">Código Postal</td>
                                    </tr>
                                </table></td>
                              </tr>
                            </table>
                            <table width="200" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                      <td onclick="mostrarPagina('../catalogos/conceptos/CatalogoConceptos.php')">Colonias</td>
                                    </tr>
                                </table></td>
                              </tr>
                            </table>
                            <table width="200" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                      <td onclick="mostrarPagina('../catalogos/conceptos/CatalogoConceptos.php')">Solicitud de guía empresarial</td>
                                    </tr>
                                </table></td>
                              </tr>
                          </table></td>
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
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('../../cat/bitacoraquejas.php')">Bitacora Quejas</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('../../cat/bitacoraQuejasDanosFaltantes.php')">Bitacora Quejas Daños y Faltantes</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Daños y Faltantes()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
							  <table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('')">EAD mal Efectuadas()</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Recolecciones NO Realizadas()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Cancelaciones de Guias()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>	
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Guias Extraviadas()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>						</td>
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
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('../../Caja/iniciocaja.php')">Inicializar caja</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('../../Caja/cierrecaja.php')">Cierre de caja</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Cajero</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>							</td>
							</tr>
						</table>
					</div>
				</div>
			</li>
			</ul>		</td>
 	  </tr>     
	</table>

</body>
</html>
<script>
		var parentAccordion=new TINY.accordion.slider("parentAccordion");
		parentAccordion.init("acc","h3",1,-1);
		
		var nestedAccordion=new TINY.accordion.slider("nestedAccordion");
		nestedAccordion.init("nested","h3",1,-1);
</script>
