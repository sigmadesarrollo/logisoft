<? 
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
require_once('../../ConectarSolo.php');
$l = Conectarse("webpmm");

/*$s = "
SELECT cs.descripcion, em.foliobitacora
FROM catalogosucursal cs
INNER JOIN embarquedemercancia em ON cs.id = $_GET[sucursal] 
AND em.folio = $_GET[folioembarque] AND cs.id = em.idsucursal";
$r = mysql_query($s,$l) or die($s);
$f = mysql_fetch_object($r);
$nombreSucursal = $f->descripcion;
$folioBitacora = $f->foliobitacora;*/

// create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData("", null, '                         PAQUETERIA Y MENSAJERIA EN MOVIMIENTO', 
									  '                           REPORTE: FALTANTES EN EMBARQUE
                           SUCURSAL: '.$nombreSucursal.'
                           BITACORA: '.$folioBitacora.'
                           EMBARQUE: '.$_GET[folioembarque]);

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
	$s = "SELECT gv.id as guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') as fecha,
	CONCAT_WS(' ', cc.nombre,cc.paterno,cc.materno) AS cliente,
	cs.prefijo AS destino, gv.total AS importe, gv.idusuario,
	CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) as empleado, gv.ocurre
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
	INNER JOIN catalogoempleado ce ON gv.idusuario = ce.id";
	$criterioguiascontado = " WHERE gv.tipoflete = 0 AND gv.condicionpago = 0 AND gv.idsucursalorigen = ".$_GET[sucursal]." 
	AND gv.fecha = '2010-09-09' AND gv.estado<>'CANCELADO' ORDER BY gv.idusuario,gv.ocurre";	
	$r = mysql_query($s.$criterioguiascontado,$l) or die($s);
	//echo $s.$criterioguiascontado;
	$datos = <<<EOD
		<table width="888" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td width="127" style="font-weight:bold">GUIA</td>
			<td width="155" style="font-weight:bold">FECHA</td>
			<td width="206" style="font-weight:bold">CLIENTE</td>
			<td width="183" style="font-weight:bold">DESTINO</td>
			<td width="215" style="font-weight:bold">IMPORTE</td>
		</tr>
EOD;
	while($f = mysql_fetch_object($r)){
		$datos .= <<<EOD
			<tr style="font-size:9nm;">
			  <td>$f->guia</td>
			  <td>$f->fecha</td>
			  <td>$f->cliente</td>
			  <td>$f->destino</td>
			  <td>$f->importe</td>
		  </tr>  
EOD;
	}
	$datos .= <<<EOD
		</table>
EOD;
	
	

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y=45, $datos, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
