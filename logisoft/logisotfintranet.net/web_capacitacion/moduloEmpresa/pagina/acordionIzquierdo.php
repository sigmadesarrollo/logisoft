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
		<td>&nbsp;</td>
	</tr>
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
											<td onclick="mostrarPagina('')">Historial de Guías Emitidas</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Historial de Guías Entregadas</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Historial de Guías en Tránsito</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Historial de Guías Canceladas</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Historial de Guías Envíos Problema</td>
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
											<td onclick="mostrarPagina('')">Guías de Ventanilla Pendientes de Facturar()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>	
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Estado de Cuenta</td>
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
				<h3>Clientes</h3>
				<div class="acc-section">
					<div class="acc-content">
						<table  border="0" cellpadding="0" cellspacing="0">
							<tr>
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('../catalogos/proveedores/catalogoproveedores.php')">Directorio de Clientes</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('../../buscadores_generales/buscarPropuestaConvenioGen.php?pestado=EN AUTORIZACION&funcion=solicitarPropuestaConvenio&estado=AUTORIZACION PARA CANCELAR')">Propuestas de Convenios Pendientes de Autorizar</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Convenios Pendientes de Autorizar()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
							  <table width="200" border="0" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="3" class="fila2"><table cellpadding="0" cellspacing="0" border="0">
										<tr>
										  <td onclick="mostrarPagina('')">Convenios Pendientes de Activar()</td>
										</tr>
									</table></td>
								  </tr>
								</table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Convenios por Vencer()</td>
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
				<h3>CAT</h3>
				<div class="acc-section">
					<div class="acc-content">
						<table  border="0" cellpadding="0" cellspacing="0">
							<tr>
							  <td align="left"><table width="200" border="0" cellpadding="0" cellspacing="0">
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
											<td onclick="mostrarPagina('')">Cancelaciones de Guías()</td>
										  </tr>
									  </table></td>
									</tr>
								  </table>
								  <table width="200" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <td colspan="3" class="fila1"><table cellpadding="0" cellspacing="0" border="0">
										  <tr>
											<td onclick="mostrarPagina('')">Guías Extraviadas()</td>
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
			</ul>							
		</td>
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
