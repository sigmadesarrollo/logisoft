<?

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
/*require_once('../../ConectarSolo.php');
$l = Conectarse("webpmm");

$s = "
SELECT cs.descripcion, em.foliobitacora
FROM catalogosucursal cs
INNER JOIN embarquedemercancia em ON cs.id = $_GET[sucursal] 
AND em.folio = $_GET[folioembarque] AND cs.id = em.idsucursal";
$r = mysql_query($s,$l) or die($s);
$f = mysql_fetch_object($r);
$nombreSucursal = $f->descripcion;
$folioBitacora = $f->foliobitacora;*/
require_once("../../Conectar.php");
$l = Conectarse('webpmm');

	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);

$s = "select * from catalogosucursal where id = '$_GET[sucursal]'";
$r = mysql_query($s,$l) or die($s);
$f = mysql_fetch_object($r);

$nsucursal = $f->descripcion;

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
									  '                           REPORTE: VENTAS Y RECIBIDO
                           SUCURSAL: '.$nsucursal.'
                           FECHA: '.date("d/m/Y"));

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
	$s = "INSERT INTO reporte_concesionestmp(guia,idusuario)
	SELECT guia, ".$_GET[usuario]." FROM reporte_concesiones WHERE tipo = 'V' 
	".((!empty($_GET[fechainicio]))? " AND fechaguia 
	BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
	AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL";
	mysql_query($s,$l) or die($s);
	
	$s = "SELECT guia,DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fechaguia,tipoguia,tipoflete,condicionpago,flete,descuento,fleteneto,
	comision,recoleccion,comisionead,entrega,comisionrad,total,condicion,estado,sucursal,activo,ifnull(sobrepeso,0) as sobrepeso 
	FROM reporte_concesiones WHERE tipo = 'V'
	".((!empty($_GET[fechainicio]))? " AND fechaguia 
	BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
	AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL";
	$r = mysql_query($s,$l) or die($s);
	
	$datos = <<<EOD
		<table width="882" cellpadding="0" cellspacing="0" border="1">
		<tr>
    	      <td width="71" align="left" style="font-family:'Courier New', Courier, monospace; font-size:25px">GUIA</td>
    	      <td width="63" align="left" style="font-family:'Courier New', Courier, monospace; font-size:25px">FECHA</td>
    	      <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px" >FLETE</td>
    	      <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px">DESCUENTO</td>
    	      <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px">FLETE NETO</td>
    	      <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px">COMISION</td>
    	      <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px">RECOLECCION</td>
    	      <td width="59" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px" >COM. RAD</td>
    	      <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px" >ENTREGA</td>
    	      <td width="59" style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right" >COM. EAD</td>
    	      <td width="63" style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right" >C. SOBREPESO</td>
    	      <td width="63" style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right" >TOTAL</td>
    	      <td width="63" style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right" >CONDICION</td>
    	      <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px" >STATUS</td>
      </tr>
EOD;
	$cont = 0; //18;
	while($f = mysql_fetch_object($r)){
		$cont++;
		$f->flete = round($f->flete,2);
		$f->descuento = round($f->descuento,2);
		$f->fleteneto = round($f->fleteneto,2);
		$f->comision = round($f->comision,2);
		$f->recoleccion = round($f->recoleccion,2);
		$f->comisionrad = round($f->comisionrad,2);
		$f->entrega = round($f->entrega,2);
		$f->comisionead = round($f->comisionead,2);
		$f->sobrepeso = round($f->sobrepeso,2);
		$f->total = round($f->total,2);
		$f->condicion = str_replace('-',' - ',$f->condicion);
		
		if($cont>20){
			$cont=0;
			$datos .= <<<EOD
			</table>
			<table width="882">
				<tr>
					<td height="75px">&nbsp;</td>
				</tr>
			</table>
			<table width="882" cellpadding="0" cellspacing="0" border="1">
			<tr>
				  <td width="71" align="left" style="font-family:'Courier New', Courier, monospace; font-size:25px">GUIA</td>
				  <td width="63" align="left" style="font-family:'Courier New', Courier, monospace; font-size:25px">FECHA</td>
				  <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px" >FLETE</td>
				  <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px">DESCUENTO</td>
				  <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px">FLETE NETO</td>
				  <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px">COMISION</td>
				  <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px">RECOLECCION</td>
				  <td width="59" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px" >COM. RAD</td>
				  <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px" >ENTREGA</td>
				  <td width="59" style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right" >COM. EAD</td>
				  <td width="63" style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right" >C. SOBREPESO</td>
				  <td width="63" style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right" >TOTAL</td>
				  <td width="63" style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right" >CONDICION</td>
				  <td width="63" align="right" style="font-family:'Courier New', Courier, monospace; font-size:25px" >STATUS</td>
		  </tr>
EOD;
		}
		
		$datos .= <<<EOD
			<tr>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="left">$f->guia</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="left">$f->fechaguia</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->flete</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->descuento</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->fleteneto</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->comision</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->recoleccion</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->comisionrad</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->entrega</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->comisionead</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->sobrepeso</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->total</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->condicion</td>
    	      <td style="font-family:'Courier New', Courier, monospace; font-size:25px" align="right">$f->estado</td>
          </tr>
EOD;
	}
	$datos .= <<<EOD
		</table>
EOD;
	
	

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y=40, $datos, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('con_ventasyrecibido.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
