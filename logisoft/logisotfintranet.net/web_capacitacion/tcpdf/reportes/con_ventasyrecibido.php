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
	$s = mysql_query("CREATE TEMPORARY TABLE `reporteConcesiones_tmp` (  
				`idx` INT(11) NOT NULL AUTO_INCREMENT,
				`movimiento` VARCHAR(20) DEFAULT NULL,
				`pagcontado` DOUBLE DEFAULT NULL,
                `pagcredito` DOUBLE DEFAULT NULL,
                `cobcontado` DOUBLE DEFAULT NULL,
                `cobcredito` DOUBLE DEFAULT NULL,
				`idusuario` DOUBLE DEFAULT NULL,
				PRIMARY KEY (`idx`)
				) ENGINE=INNODB DEFAULT CHARSET=latin1",$l)  or die($s);
		
				$s = "INSERT INTO reporteConcesiones_tmp(movimiento,pagcontado,pagcredito,cobcontado,cobcredito,idusuario)
				SELECT 'VENTA' AS movimiento,
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CONTADO' AND tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcontado,
				
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CREDITO' and tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcredito,
				
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CONTADO' and tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcontado,
				
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CREDITO' and tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcredito, ".$_GET[usuario]."
				FROM reporte_concesiones
				WHERE tipo = 'V' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S'
				GROUP BY movimiento";
				mysql_query($s,$l) or die($s);
				
				$s = "INSERT INTO reporteConcesiones_tmp(movimiento,pagcontado,pagcredito,cobcontado,cobcredito,idusuario)
				SELECT 'RECIBIDO' AS movimiento, 
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CONTADO' and tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcontado,
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CREDITO' and tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcredito,
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CONTADO' and tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcontado,
				(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CREDITO' and tipo = 'R' 

				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcredito, ".$_GET[usuario]."
				FROM reporte_concesiones
				WHERE tipo = 'R' 
				".((!empty($_GET[fechainicio]))? " AND fechaguia 
				BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
				AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S'
				GROUP BY movimiento";
				mysql_query($s,$l) or die($s);
				
				$s = "SELECT IFNULL(movimiento,'') AS movimiento, IFNULL(pagcontado,0) AS pagcontado, IFNULL(pagcredito,0) AS pagcredito,
				IFNULL(cobcontado,0) AS cobcontado, IFNULL(cobcredito,0) AS cobcredito
				FROM reporteConcesiones_tmp WHERE idusuario = ".$_GET[usuario];
				$r = mysql_query($s,$l) or die($s);
	
	$datos = <<<EOD
		<table width="888" cellpadding="0" cellspacing="0" border="1">
		<tr>
    	      <td width="171" height="19" align="left" class="cabecera">MOVIMIENTOS</td>
    	      <td align="right" class="cabecera" >PAGADA-CONTADO</td>
    	      <td align="right" class="cabecera">PAGADA-CREDITO </td>
    	      <td align="right" class="cabecera">COBRAR-CONTADO</td>
    	      <td align="right" class="cabecera" >COBRAR-CREDITO</td>
    	      <td width="182" class="cabecera" align="right" >TOTAL</td>
      	</tr>
EOD;
	while($f = mysql_fetch_object($r)){
		$total = $f->pagcontado + $f->pagcredito + $f->cobcontado + $f->cobcredito;
		$datos .= <<<EOD
			<tr>
    	      <td align="left">$f->movimiento</td>
    	      <td align="right">$f->pagcontado</td>
    	      <td align="right">$f->pagcredito</td>
    	      <td align="right">$f->cobcontado</td>
    	      <td align="right">$f->cobcredito</td>
    	      <td align="right">$total</td>
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
$pdf->Output('con_ventasyrecibido.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
