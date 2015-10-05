<? 
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
require_once('../../ConectarSolo.php');
$l = Conectarse("webpmm");

ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
	
function cambiofecha($fecha){//Convierte fecha de normal a mysql 
	ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	$lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
	return $lafecha;
}

$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal];
$r = mysql_query($s,$l) or die($s);
$f = mysql_fetch_object($r);
// create new PDF document
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData("logo.jpg", null, '                           ENTREGAS PUNTUALES S DE RL DE CV', 
									  '                           REPORTE: CORTE DIARIO CONDENSADO
                           SUCURSAL: '.$f->descripcion.'
                           FECHA:	 DEL '.$_GET[fechainicio].' AL '.((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]).'	               IMPRESO:	'.date('d/m/Y').'');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 12));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print
	$s = "SELECT descripcion FROM catalogosucursal WHERE id=".$_GET[sucursal];
	$r = mysql_query($s,$l) or die($s);
	$su= mysql_fetch_object($r);

	$s = "CALL proc_ReporteCajaCondensado_pruebas(".$_GET[sucursal].",'".cambiofecha($_GET[fechainicio])."','".cambiofecha(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."')";
	$r = mysql_query($s,$l) or die($s);
	
	$s = "SELECT @guiaspagadocontado AS guiaspagadocontado, @registrosguiaspagadocontado AS registrosguiaspagadocontado, 
	@guiaspagadocredito AS guiaspagadocredito, @registrosguiaspagadocredito AS registrosguiaspagadocredito, @guiasporcobrarcontado AS guiasporcobrarcontado,
	@registrosguiasporcobrarcontado AS registrosguiasporcobrarcontado, @guiasporcobrarcredito AS guiasporcobrarcredito, 
	@registrosguiasporcobrarcredito AS registrosguiasporcobrarcredito, @facturasguiasprepagadascontado AS facturasguiasprepagadascontado,
	@registrosfacturasguiasprepagadascontado AS registrosfacturasguiasprepagadascontado, @facturasguiasprepagadascredito As facturasguiasprepagadascredito,
	@registrosfacturasguiasprepagadascredito AS registrosfacturasguiasprepagadascredito, @factguiasconsignacioncontado AS factguiasconsignacioncontado,
	@registrosfactguiasconsignacioncontado AS registrosfactguiasconsignacioncontado, @factguiasconsignacioncredito AS factguiasconsignacioncredito,
	@registrosfactguiasconsignacioncredito AS registrosfactguiasconsignacioncredito, @facturassobrepesocontado AS facturassobrepesocontado,
	@registrosfacturassobrepesocontado AS registrosfacturassobrepesocontado, @facturassobrepesocredito AS facturassobrepesocredito,
	@registrosfacturassobrepesocredito AS registrosfacturassobrepesocredito, @valordeclaradocontado AS valordeclaradocontado,
	@registrosvalordeclaradocontado AS registrosvalordeclaradocontado, @valordeclaradocredito AS valordeclaradocredito,
	@registrosvalordeclaradocredito AS registrosvalordeclaradocredito, @facturacionotrosconceptoscontado AS facturacionotrosconceptoscontado,
	@registrosfacturacionotrosconceptoscontado AS registrosfacturacionotrosconceptoscontado, @facturacionotrosconceptoscredito AS facturacionotrosconceptoscredito,
	@registrosfacturacionotrosconceptoscredito AS registrosfacturacionotrosconceptoscredito, @correointernolocal AS correointernolocal,
	@guiascobrarcontadoentregadas AS guiascobrarcontadoentregadas, @registrosguiascobrarcontadoentregadas AS registrosguiascobrarcontadoentregadas,
	@guiascobrarcreditoentregadas AS guiascobrarcreditoentregadas, @registrosguiascobrarcreditoentregadas AS registrosguiascobrarcreditoentregadas,
	@guiaspagadascontadoentregas AS guiaspagadascontadoentregas, @registrosguiaspagadascontadoentregas AS registrosguiaspagadascontadoentregas,
	@guiaspagadascreditoentregas AS guiaspagadascreditoentregas, @registrosguiaspagadascreditoentregas AS registrosguiaspagadascreditoentregas,
	@guiascanceladas AS guiascanceladas, @registrosguiascanceladas AS registrosguiascanceladas, @facturascanceladas AS facturascanceladas,
	@registrosfacturascanceladas AS registrosfacturascanceladas, @guiasautorizacioncancelar AS guiasautorizacioncancelar, 
	@registrosguiasautorizacioncancelar AS registrosguiasautorizacioncancelar, @cobranza AS cobranza, @registroscobranza AS registroscobranza";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$locales = $f->guiaspagadocontado + $f->guiaspagadocredito + $f->guiasporcobrarcontado + $f->guiasporcobrarcredito + $f->facturasguiasprepagadascontado 
	+ $f->facturasguiasprepagadascredito + $f->factguiasconsignacioncontado + $f->factguiasconsignacioncredito + $f->facturassobrepesocontado 
	+ $f->facturassobrepesocredito + $f->valordeclaradocontado + $f->valordeclaradocredito + $f->facturacionotrosconceptoscontado + $f->facturacionotrosconceptoscredito;
	
	$registrosLocales = $f->registrosguiaspagadocontado + $f->registrosguiaspagadocredito + $f->registrosguiasporcobrarcontado 
	+ $f->registrosguiasporcobrarcredito + $f->registrosfacturasguiasprepagadascontado
	+ $f->registrosfacturasguiasprepagadascredito + $f->registrosfactguiasconsignacioncontado 
	+ $f->registrosfactguiasconsignacioncredito + $f->registrosfacturassobrepesocontado
	+ $f->registrosfacturassobrepesocredito + $f->registrosvalordeclaradocontado 
	+ $f->registrosvalordeclaradocredito + $f->registrosfacturacionotrosconceptoscontado
	+ $f->registrosfacturacionotrosconceptoscredito + $f->correointernolocal;
	
	$entregadas = $f->guiascobrarcontadoentregadas + $f->guiascobrarcreditoentregadas + $f->guiaspagadascontadoentregas + $f->guiaspagadascreditoentregas;
	
	$registrosEntregadas = $f->registrosguiascobrarcontadoentregadas + $f->registrosguiascobrarcreditoentregadas + $f->registrosguiaspagadascontadoentregas 
	+ $f->registrosguiaspagadascreditoentregas;
	
	$resumen = $f->guiaspagadocontado + $f->guiascobrarcontadoentregadas + $f->facturasguiasprepagadascontado 
	+ $f->factguiasconsignacioncontado + $f->facturassobrepesocontado + $f->valordeclaradocontado 
	+ $f->facturacionotrosconceptoscontado + $f->cobranza;

	$resumenregistros = $f->registrosguiaspagadocontado + $f->registrosguiascobrarcontadoentregadas + $f->registrosfacturasguiasprepagadascontado + $f->registrosfactguiasconsignacioncontado 
	+ $f->registrosfacturassobrepesocontado + $f->registrosvalordeclaradocontado + $f->registrosfacturacionotrosconceptoscontado + $f->registroscobranza;
	
	$canceladas = $f->guiascanceladas + $f->facturascanceladas + $f->guiasautorizacioncancelar;
	
	$registroCanceladas = $f->registrosguiascanceladas + $f->registrosfacturascanceladas + $f->registrosguiasautorizacioncancelar;
	$f->factguiasconsignacioncontado = ((empty($f->factguiasconsignacioncontado))?0:$f->factguiasconsignacioncontado);
	$f->factguiasconsignacioncredito = ((empty($f->factguiasconsignacioncredito))?0:$f->factguiasconsignacioncredito);
	$f->facturassobrepesocontado = ((empty($f->facturassobrepesocontado))?0:$f->facturassobrepesocontado);
	$f->facturassobrepesocredito = ((empty($f->facturassobrepesocredito))?0:$f->facturassobrepesocredito);
	$f->valordeclaradocontado = ((empty($f->valordeclaradocontado))?0:$f->valordeclaradocontado);
	$f->valordeclaradocredito = ((empty($f->valordeclaradocredito))?0:$f->valordeclaradocredito);
$datos=<<<EOD
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="406" style="font-weight:bold">POR PAQUETERIA LOCAL ENVIADA</td>
    <td width="135" align="center" style="font-weight:bold">IMPORTE</td>
    <td width="109" align="center" style="font-weight:bold">GUIAS</td>
  </tr>
  <tr>
    <td>Guias Pagado de Contado</td>
    <td align="right">$f->guiaspagadocontado</td>
    <td align="right">$f->registrosguiaspagadocontado</td>
  </tr>
  <tr>
    <td>Guias Pagado de Credito</td>
    <td align="right">$f->guiaspagadocredito</td>
    <td align="right">$f->registrosguiaspagadocredito</td>
  </tr>
  <tr>
    <td>Guias por Cobrar Contado</td>
    <td align="right">$f->guiasporcobrarcontado</td>
    <td align="right">$f->registrosguiasporcobrarcontado</td>
  </tr>
  <tr>
    <td>Guias por Cobrar Credito</td>
    <td align="right">$f->guiasporcobrarcredito</td>
    <td align="right">$f->registrosguiasporcobrarcredito</td>
  </tr>
  <tr>
    <td>Facturacion de Ventas Guias Prepagadas Contado</td>
    <td align="right">$f->facturasguiasprepagadascontado</td>
    <td align="right">$f->registrosfacturasguiasprepagadascontado</td>
  </tr>
  <tr>
    <td>Facturacion de Ventas Guias Prepagadas Credito</td>
    <td align="right">$f->facturasguiasprepagadascredito</td>
    <td align="right">$f->registrosfacturasguiasprepagadascredito</td>
  </tr>
  <tr>
    <td>Facturacion de Guias Consignacion Contado</td>
    <td align="right">$f->factguiasconsignacioncontado</td>
    <td align="right">$f->registrosfactguiasconsignacioncontado</td>
  </tr>
  <tr>
    <td>Facturacion de Guias Consignacion Credito</td>
    <td align="right">$f->factguiasconsignacioncredito</td>
    <td align="right">$f->registrosfactguiasconsignacioncredito</td>
  </tr>
  <tr>
    <td>Facturacion de Sobrepeso Contado</td>
    <td align="right">$f->facturassobrepesocontado</td>
    <td align="right">$f->registrosfacturassobrepesocontado</td>
  </tr>
  <tr>
    <td>Facturacion de Sobrepeso Credito</td>
    <td align="right">$f->facturassobrepesocredito</td>
    <td align="right">$f->registrosfacturassobrepesocredito</td>
  </tr>
  <tr>
    <td>Facturacion de Valor Declarado Contado</td>
    <td align="right">$f->valordeclaradocontado</td>
    <td align="right">$f->registrosvalordeclaradocontado</td>
  </tr>
  <tr>
    <td>Facturacion de Valor Declarado Credito</td>
    <td align="right">$f->valordeclaradocredito</td>
    <td align="right">$f->registrosvalordeclaradocredito</td>
  </tr>
  <tr>
    <td>Facturacion de Otros Conceptos Contado</td>
    <td align="right">$f->facturacionotrosconceptoscontado</td>
    <td align="right">$f->registrosfacturacionotrosconceptoscontado</td>
  </tr>
  <tr>
    <td>Facturacion de Otros Conceptos Credito</td>
    <td align="right">$f->facturacionotrosconceptoscredito</td>
    <td align="right">$f->registrosfacturacionotrosconceptoscredito</td>
  </tr>
  <tr>
    <td>Correo Interno</td>
    <td align="right">0</td>
    <td align="right">$f->correointernolocal</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="font-weight:bold">TOTALES:</td>
    <td align="right" style="font-weight:bold">$locales</td>
    <td align="right" style="font-weight:bold">$registrosLocales</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="font-weight:bold">POR PAQUETERIA FORANEA ENTREGADA</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Guias por Cobrar Contado Entregadas</td>
    <td align="right">$f->guiascobrarcontadoentregadas</td>
    <td align="right">$f->registrosguiascobrarcontadoentregadas</td>
  </tr>
  <tr>
    <td>Guias por Cobrar Credito Entregadas</td>
    <td align="right">$f->guiascobrarcreditoentregadas</td>
    <td align="right">$f->registrosguiascobrarcreditoentregadas</td>
  </tr>
  <tr>
    <td>Guias Pagadas Contado Entregadas</td>
    <td align="right">$f->guiaspagadascontadoentregas</td>
    <td align="right">$f->registrosguiaspagadascontadoentregas</td>
  </tr>
  <tr>
    <td>Guias Pagadas Credito Entregadas</td>
    <td align="right">$f->guiaspagadascreditoentregas</td>
    <td align="right">$f->registrosguiaspagadascreditoentregas</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="font-weight:bold">TOTALES:</td>
    <td align="right" style="font-weight:bold">$entregadas</td>
    <td align="right" style="font-weight:bold">$registrosEntregadas</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="font-weight:bold">RESUMEN DE INGRESOS:</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Ingresos de Guias Pagado de Contado</td>
    <td align="right">$f->guiaspagadocontado</td>
    <td align="right">$f->registrosguiaspagadocontado</td>
  </tr>
  <tr>
    <td>Ingresos de Guias por Cobrar Contado Entregadas</td>
    <td align="right">$f->guiascobrarcontadoentregadas</td>
    <td align="right">$f->registrosguiascobrarcontadoentregadas</td>
  </tr>
  <tr>
    <td>Ingresos de Cobranza Paqueteria</td>
    <td align="right">$f->cobranza</td>
    <td align="right">$f->registroscobranza</td>
  </tr>
  <tr>
    <td>Facturacion de Ventas Guias Prepagadas Contado</td>
    <td align="right">$f->facturasguiasprepagadascontado</td>
    <td align="right">$f->registrosfacturasguiasprepagadascontado</td>
  </tr>
  <tr>
    <td>Facturacion de Guias Consignacion Contado</td>
    <td align="right">$f->factguiasconsignacioncontado</td>
    <td align="right">$f->registrosfactguiasconsignacioncontado</td>
  </tr>  
  <tr>
    <td>Facturacion de Sobrepeso Contado</td>
    <td align="right">$f->facturassobrepesocontado</td>
    <td align="right">$f->registrosfacturassobrepesocontado</td>
  </tr>
  <tr>
    <td>Facturacion de Valor Declarado Contado</td>
    <td align="right">$f->valordeclaradocontado</td>
    <td align="right">$f->registrosvalordeclaradocontado</td>
  </tr>
  <tr>
    <td>Facturacion de Otros Conceptos Contado</td>
    <td align="right">$f->facturacionotrosconceptoscontado</td>
    <td align="right">$f->registrosfacturacionotrosconceptoscontado</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="font-weight:bold">TOTALES:</td>
    <td align="right" style="font-weight:bold">$resumen</td>
    <td align="right" style="font-weight:bold">$resumenregistros</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Guias Canceladas del Dia</td>
    <td align="right">$f->guiascanceladas</td>
    <td align="right">$f->registrosguiascanceladas</td>
  </tr>
  <tr>
    <td>Facturas Canceladas del Dia</td>
    <td align="right">$f->facturascanceladas</td>
    <td align="right">$f->registrosfacturascanceladas</td>
  </tr>
  <tr>
    <td>Guias Pendientes de Cancelar</td>
    <td align="right">$f->guiasautorizacioncancelar</td>
    <td align="right">$f->registrosguiasautorizacioncancelar</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="font-weight:bold">TOTALES:</td>
    <td align="right" style="font-weight:bold">$canceladas</td>
    <td align="right" style="font-weight:bold">$registroCanceladas</td>
  </tr>
</table>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y=45, $datos, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('reporteDiarioCondensado.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>