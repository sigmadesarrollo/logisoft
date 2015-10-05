<?php
// (c) Xavier Nicolay
// Exemple de gnration de devis/facture PDF
require('CNumeroaLetra.php');
require('invoice_notacredito.php');
require("../ConectarSolo.php");
$l = Conectarse("webpmm");

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->Image('logo.jpg',10,8,33);
/*$pdf->addSociete( "Paqueteria y Mensajeria en Movimiento",
                  "Ofna. Matriz Fco. Serrano No 2316-306 Tel:(669) 985 48 11\n" .
                  "C.P.:82000 Col. Centro, Mazatlan, Sinaloa\n".
                  "R.F.C. PMM-900725-698\n" .
                  "Contribuyente del Regimen de Transparencia");*/
//$pdf->fact_dev("Certificado", "01234567890123456789" );
//$pdf->temporaire( "Devis temporaire" );
/*$pdf->addDate(date('d/m/Y'));
$pdf->addClient("CL01");
$pdf->addPageNumber("1");*/
//$pdf->addClientAdresse("Este Documento es una impresin de un Comprobante Fiscal Digital");


/*$pdf->addReglement("Paqueteria y Mensajeria en Movimiento S.A. de C.V.");
$pdf->addReglement1("Ofna. Matriz Fco. Serrano No 2316-306");
$pdf->addReglement2("Tel:(669) 985 48 11 C.P.:82000");
$pdf->addReglement3("Col. Centro, Mazatlan, Sinaloa");
$pdf->addReglement4("R.F.C. PMM-900725-698");*/

$s = "SELECT UCASE(eti_nombre1) eti_nombre1, UCASE(eti_nombre2) eti_nombre2, UCASE(eti_direccion) eti_direccion, 
UCASE(eti_colonia) eti_colonia, UCASE(eti_ciudad) eti_ciudad, UCASE(eti_rfc) eti_rfc
FROM configuradorgeneral";
$r = mysql_query($s,$l) or die($s);
$f = mysql_fetch_object($r);

$pdf->addReglement("Razón Social: ".strtoupper($f->eti_nombre1));
$pdf->addReglement1("                        ".strtoupper($f->eti_nombre2));
$pdf->addReglement2("Domicilio: ".strtoupper($f->eti_direccion));
$pdf->addReglement3("Colonia: ".strtoupper($f->eti_colonia));
$pdf->addReglement4("Población: ".strtoupper($f->eti_ciudad));
$pdf->addRfc("Rfc: ".strtoupper($f->eti_rfc));
	
	$s = "SELECT nc.*, cs.descripcion nombresucursal, cs.prefijo lasucursal,
	obtenerSerieNotaCredito(".$_GET[factura].") seriefactura,
	cs.descripcion nombresucursal, cs.calle calle1, cs.numero numero1, cs.colonia colonia1, cs.cp cp1
	FROM notacredito nc
	inner join catalogosucursal cs on nc.sucursal = cs.id
	WHERE folio = ".$_GET[factura]."";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$ivaret = $f->otrosivaretenido+$f->sobivaretenido+$f->ivaretenido;
	
$pdf->addReglement5("Razón Social: ".strtoupper($f->nombrecliente));
$pdf->addReglement6("Domicilio: ".strtoupper($f->calle)." ".strtoupper($f->numero));
$pdf->addReglement7("Colonia: ".strtoupper($f->colonia));
$pdf->addReglement8("CP: ".strtoupper($f->cp));
$pdf->addReglement9("Población: ".strtoupper($f->ciudad).", ".strtoupper($f->estado));
$pdf->addReglement10("RFC: ".strtoupper($f->rfc));

$pdf->addCadenaOriginal($f->cadena);

$xml = split('"',$f->xml);
		//echo $cosa[19];

$pdf->addSelloOriginal($xml[19]);

	
//$pdf->addSerie("15");

//$pdf->addEcheance("03/12/2003");
//$pdf->addNumTVA("FR888777666");
$pdf->addReference("");

# estaba IMPORTE 30
$cols=array( "CANTIDAD"    => 30,
             "CONCEPTO"  => 110,
             "IMPORTE"     => 50);
$pdf->addCols( $cols);
$cols=array( "CANTIDAD"    => "C",
             "CONCEPTO"  => "L",
             "IMPORTE"     => "R");
$pdf->addLineFormat($cols);
		
		$conceptos = split('Concepto',$f->xml);

		$y    = 95;
		
		$pdf->SetFont( "Arial", "B", 7);
		
		$subtgeneral = 0;
		
		for($i=2; $i<count($conceptos)-1; $i++){
			 $arreline = split('"',$conceptos[$i]);
			 			 
			 $line = array( "CANTIDAD"    => $arreline[1],
               "CONCEPTO"  => (($arreline[3]!="")?$arreline[3]:"GUIA"),
               "IMPORTE"     => number_format($arreline[7],2,'.',','),
			   "" => "");
			// echo "$arreline[1] $arreline[3] $arreline[7]<br>";
			 
			// echo "pdf -> addLine( $y, $line )<br>";
			 $subtgeneral += $arreline[7];
				$size = $pdf->addLine( $y, $line );
				$y   += $size + 2;
		}
	
	$linetotales = split('Traslado',$f->xml);
			//echo $linetotales[2];
			$arrelinetotales = split('"',$linetotales[2]);
			 //echo "xxxxx".$arrelinetotales[1];
			 $subtotal   = $subtgeneral;
			 $ivatotal   = $arrelinetotales[1];
			 //$ivaret   	 = $arrelinetotales[1];
			 $total		 = $arrelinetotales[1]+$subtgeneral-$ivaret;


$numalet= new CNumeroaletra; 
$numalet->setNumero(round($total,2)); 
$pdf->addNumeroLetra($numalet->letra());
//$fec = split("-",substr($xml[23],0,10));
//$fecha = $fec[2]."/".$fec[1]."/".$fec[0];
$fecha = substr($xml[23],0,19);

$pfolio = split("folio",$f->xml);
$pfolio = split('"',$pfolio[1]);

$pdf->addCadreEurosFrancs($fecha,$pfolio[1],$f->lasucursal);
$pdf->addTotales(number_format(round($subtotal,2),2,'.',','),number_format(round($ivatotal,2),2,'.',','),
				 number_format(round($ivaret,2),2,'.',','),number_format(round($total,2),2,'.',','));

$pdf->addLiva("IMPUESTO RETENIDO DE CONFIRMIDAD CON LIVA-ART 1A Y RIVA-ART 3 FRACC.II",10,236);

$aproba = split(" noAprobacion=",$f->xml);
$aproba = split('"',$aproba[1]);
$serie = split(" serie=",$f->xml);
$serie = split('"',$serie[1]);
$anoAp = split(" anoAprobacion=",$f->xml);
$anoAp = split('"',$anoAp[1]);

$pdf->tituloFactura("NOTA CREDITO");
$pdf->addSerieAprobacion($serie[1], $aproba[1], $anoAp[1]);
//$pdf->addNumeroAno($f->tipopago,$f->tipofactura);
$pdf->addCertificado($f->seriefactura);
$pdf->addDirSucursal("EMITIDA EN $f->nombresucursal","$f->calle1 $f->numero1, CP $f->cp1, $f->colonia1");
$pdf->addLeyenda(utf8_decode("Este Documento es una representación impresa de un CFD"));
$pdf->Output();
?>
