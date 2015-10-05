<?

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
require_once('../../Conectar.php');
$l = Conectarse("webpmm");

ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);

	if(!empty($_GET[checktodas])){
		$nsucursal = "TODAS LAS SUCURSALES";
		$andsuc = "";
	}else{
		if($_GET[sucursalmovio]==0){
			$andsuc = " AND gv.idsucursalorigen = $_GET[sucursal_hidden] ";
			$nsucursal = "REALIZADAS EN ".$_GET[sucursal];
		}else{
			$andsuc = " AND hc.sucursal = $_GET[sucursal_hidden] ";
			$nsucursal = "CANCELADAS EN ".$_GET[sucursal];
		}
	}
	
	if($_GET[tipofecha]==0){
		$fechas = "REALIZADAS ENTRE $_GET[inicio] Y $_GET[fin]";
		$andfec = " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."' ";
	}else{
		$fechas = "CANCELADAS ENTRE $_GET[inicio] Y $_GET[fin]";
		$andfec = " AND hc.fecha BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."' ";
	}

// create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData("logo.jpg", null, '                         PAQUETERIA Y MENSAJERIA EN MOVIMIENTO', 
									  '                           REPORTE: GUIAS CANCELADAS
                           '.$nsucursal.'
                           '.$fechas);

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
$pdf->SetFont('dejavusans', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print
	$s = "SELECT gv.id guia, cso.prefijo origen, csd.prefijo destino, DATE_FORMAT(gv.fecha, '%d/%m/%Y') emision,
	DATE_FORMAT(hc.fecha, '%d/%m/%Y') cancelacion, gv.total importe, 
	IF(gv.tipoflete = 0,'PAGADA','POR COBRAR') tipoflete,
	csc.prefijo cancelo, IF(gv.tipoflete=0, cso.prefijo, csd.prefijo) afecta,
	CONCAT(ce.nombre, ' ', ce.apellidopaterno, ' ', ce.apellidomaterno) empleado
	FROM guiasventanilla gv
	INNER JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia
	INNER JOIN catalogosucursal cso ON gv.idsucursalorigen = cso.id
	INNER JOIN catalogosucursal csd ON gv.idsucursaldestino = csd.id
	INNER JOIN catalogosucursal csc ON hc.sucursal = csc.id
	INNER JOIN catalogoempleado ce ON hc.usuario = ce.id
	WHERE (hc.accion = 'SUSTITUCION REALIZADA' OR hc.accion = 'CANCELADO')
	$andsuc $andfec";
	$r = mysql_query($s,$l) or die($s);
	
	$datos = <<<EOD
		<table width="888" cellpadding="0" cellspacing="0" border="1">
		<tr>
			  <td width="94" align="center">GUIA</td>
    	      <td width="52" align="center" >ORIGEN</td>
    	      <td width="53" align="center" >DESTINO</td>
    	      <td width="79" align="center">EMISION</td>
    	      <td width="69" align="center">CANCELACIÓN</td>
    	      <td width="90" align="right">IMPORTE</td>
    	      <td width="58" align="center" >TIPOFLETE</td>
    	      <td width="58" align="center" >CANCELO</td>
    	      <td width="61" align="center" >AFECTA</td>
    	      <td width="183" align="left" >EMPLEADO</td>
		</tr>
EOD;
	
	$total = mysql_num_rows($r);
	while($f = mysql_fetch_object($r)){
		$importes += $f->importe;
		$f->origen = utf8_encode($f->origen);
		$f->destino = utf8_encode($f->destino);
		$f->cancelo = utf8_encode($f->cancelo);
		$f->afecta = utf8_encode($f->afecta);
		$f->empleado = utf8_encode($f->empleado);
		$datos .= <<<EOD
			<tr>
    	      <td align="center">$f->guia</td>
    	      <td align="center">$f->origen</td>
    	      <td align="center">$f->destino</td>
    	      <td align="center">$f->emision</td>
    	      <td align="center">$f->cancelacion</td>
    	      <td align="right">$f->importe</td>
    	      <td align="center">$f->tipoflete</td>
    	      <td align="center">$f->cancelo</td>
    	      <td align="center">$f->afecta</td>
    	      <td align="left">$f->empleado</td>
  	      </tr>
EOD;
	}
	$datos .= <<<EOD
		  <tr>
    	      <td colspan="2" align="center" >TOTALES</td>
    	      <td align="center" >&nbsp;</td>
    	      <td align="right" >$total</td>
    	      <td align="right" >&nbsp;</td>
    	      <td align="right" >$importes</td>
    	      <td align="right" >&nbsp;</td>
    	      <td align="right" >&nbsp;</td>
    	      <td align="right" >&nbsp;</td>
    	      <td align="right" >&nbsp;</td>
  	      </tr>
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
?>