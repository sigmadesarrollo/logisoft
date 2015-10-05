<?php
// (c) Xavier Nicolay
// Exemple de gnration de devis/facture PDF
require('CNumeroaLetra.php');
require('invoice.php');
require("../ConectarSolo.php");
$l = Conectarse("webpmm");

ini_set('post_max_size','512M');
ini_set('upload_max_filesize','512M');
ini_set('memory_limit','500M');
ini_set('max_execution_time',600);
ini_set('limit',-1);

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->Image('logo.jpg',10,8,33);

function cabeceras($pdf,$l,$num_llev){
	$pdf->Image('logo.jpg',10,8,33);
	
	$s = "SELECT UCASE(eti_nombre1) eti_nombre1, UCASE(eti_nombre2) eti_nombre2, UCASE(eti_direccion) eti_direccion, 
	UCASE(eti_colonia) eti_colonia, UCASE(eti_ciudad) eti_ciudad, UCASE(eti_rfc) eti_rfc
	FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);

	$pdf->addReglement("Razón Social: ".ucwords($f->eti_nombre1));
	$pdf->addReglement1("                        ".ucwords($f->eti_nombre2));
	$pdf->addReglement2("Domicilio: ".ucwords($f->eti_direccion));
	$pdf->addReglement3("Colonia: ".ucwords($f->eti_colonia));
	$pdf->addReglement4("Población: ".ucwords($f->eti_ciudad));
	$pdf->addRfc("Rfc: ".ucwords($f->eti_rfc));
	
	$s = "SELECT f.folio, f.idsucursal, f.sustitucion, f.facturaestado, f.credito, f.cliente,
	concat_ws(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) as nombre, f.rfc, f.calle calle1, f.numero n1, 
	f.codigopostal cp1, f.colonia colonia1, f.crucecalles, f.poblacion p1, f.municipio m1, f.estado e1, f.pais, f.telefono, 
	f.fax, f.guiasempresa, f.guiasnormales, f.flete, f.excedente, f.ead, f.recoleccion, f.seguro, 
	f.combustible, f.otros, f.subtotal, f.iva, f.ivaretenido, f.total, f.sobseguro, f.sobexcedente, 
	f.sobsubtotal, f.sobiva, f.sobivaretenido, f.sobmontoafacturar, f.otroscantidad, f.otrosdescripcion, 
	f.otrosimporte, f.otrossubtotal, f.otrosiva, f.otrosivaretenido, f.otrosmontofacturar, f.usuario, 
	f.idusuario, f.fecha, f.estadocobranza, f.ivacobrado, f.ivarcobrado, f.personamoral, f.xml, f.cadenaoriginal,
	cs.prefijo lasucursal, if(f.credito='SI', 'CREDITO', 'CONTADO') tipopago, UCASE(f.tipoguia) tipofactura,
	cs.descripcion as nombresucursal, cs.calle, cs.numero, cs.crucecalles, cs.cp, cs.colonia,
	obtenerSerieVentaFactura(".$_GET[factura].") seriefactura
	FROM facturacion f
	inner join catalogosucursal cs on f.idsucursal = cs.id
	WHERE folio = ".$_GET[factura]."";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$los_subtotales = $f->subtotal+$f->otrossubtotal+$f->sobsubtotal;
	$los_totales = $f->total+$f->otrosmontofacturar+$f->sobmontoafacturar;
	$ivaret = $f->otrosivaretenido+$f->sobivaretenido+$f->ivaretenido;
	$los_iva = $f->otrosiva+$f->sobiva+$f->iva;
	
	$pdf->addReglement5("Razón Social: ".$f->nombre);
	$pdf->addReglement6("Domicilio: ".$f->calle1." No ".$f->n1);
	$pdf->addReglement7("Colonia: ".$f->colonia1);
	$pdf->addReglement8("CP: ".$f->cp1);
	$pdf->addReglement9("Población: ".$f->p1.", ".$f->e1);
	$pdf->addReglement10("RFC: ".$f->rfc);
	
	$pdf->addReference("");
	
	$cols=array( "CANTIDAD"    => 30,
             "CONCEPTO"  => 110,
             "IMPORTE"     => 50);
	$pdf->addCols( $cols);
	$cols=array( "CANTIDAD"    => "C",
				 "CONCEPTO"  => "L",
				 "IMPORTE"     => "R");

	$pdf->addLineFormat($cols);
	
	$xml = split('"',$f->xml);
	
	//echo $inicio."-".$fin."<br>";
	//echo substr($f->cadenaoriginal,$inicio,$fin)."<br>";
	
	//$pdf->addCadenaOriginal(substr($f->cadenaoriginal,$inicio,$fin));
	$pdf->addCadenaOriginal("VER ULTIMA HOJA");
	$pdf->addSelloOriginal("VER ULTIMA HOJA");
	
	$numalet= new CNumeroaletra; 
	$numalet->setNumero(round($los_totales,2)); 
	$pdf->addNumeroLetra($numalet->letra());
	$fec = split("-",substr($xml[23],0,10));
	$fecha = $fec[2]."/".$fec[1]."/".$fec[0];
	
	
	$pfolio = split("folio",$f->xml);
	$pfolio = split('"',$pfolio[1]);
	
	
	$pdf->addCadreEurosFrancs($fecha,$pfolio[1],$f->lasucursal);
	$pdf->addTotales(number_format(round($los_subtotales,2),2,'.',','),number_format(round($los_iva,2),2,'.',','),
					 number_format(round($ivaret,2),2,'.',','),number_format(round($los_totales,2),2,'.',','));
	
	
	$aproba = split(" noAprobacion=",$f->xml);
	$aproba = split('"',$aproba[1]);
	$serie = split(" serie=",$f->xml);
	$serie = split('"',$serie[1]);
	$anoAp = split(" anoAprobacion=",$f->xml);
	$anoAp = split('"',$anoAp[1]);
	
	$pdf->tituloFactura("FACTURA");
	$pdf->addSerieAprobacion($serie[1], $aproba[1], $anoAp[1]);
	$pdf->addNumeroAno($f->tipopago,$f->tipofactura);
	$pdf->addCertificado($f->seriefactura);
	$pdf->addDirSucursal("EMITIDA EN $f->nombresucursal","$f->calle $f->numero, CP $f->cp, $f->colonia");
	$pdf->addLeyenda(utf8_decode("Este Documento es una representación impresa de un CFD"));
}

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

$pdf->addReglement("Razón Social: ".ucwords($f->eti_nombre1));
$pdf->addReglement1("                        ".ucwords($f->eti_nombre2));
$pdf->addReglement2("Domicilio: ".ucwords($f->eti_direccion));
$pdf->addReglement3("Colonia: ".ucwords($f->eti_colonia));
$pdf->addReglement4("Población: ".ucwords($f->eti_ciudad));
$pdf->addRfc("Rfc: ".ucwords($f->eti_rfc));
	
	$s = "SELECT f.folio, f.idsucursal, f.sustitucion, f.facturaestado, f.credito, f.cliente,
	concat_ws(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) as nombre, f.rfc, f.calle calle1, f.numero n1, 
	f.codigopostal cp1, f.colonia colonia1, f.crucecalles, f.poblacion p1, f.municipio m1, f.estado e1, f.pais, f.telefono, 
	f.fax, f.guiasempresa, f.guiasnormales, f.flete, f.excedente, f.ead, f.recoleccion, f.seguro, 
	f.combustible, f.otros, f.subtotal, f.iva, f.ivaretenido, f.total, f.sobseguro, f.sobexcedente, 
	f.sobsubtotal, f.sobiva, f.sobivaretenido, f.sobmontoafacturar, f.otroscantidad, f.otrosdescripcion, 
	f.otrosimporte, f.otrossubtotal, f.otrosiva, f.otrosivaretenido, f.otrosmontofacturar, f.usuario, 
	f.idusuario, f.fecha, f.estadocobranza, f.ivacobrado, f.ivarcobrado, f.personamoral, f.xml, f.cadenaoriginal,
	cs.prefijo lasucursal, if(f.credito='SI', 'CREDITO', 'CONTADO') tipopago, UCASE(f.tipoguia) tipofactura,
	cs.descripcion as nombresucursal, cs.calle, cs.numero, cs.crucecalles, cs.cp, cs.colonia,
	obtenerSerieVentaFactura(".$_GET[factura].") seriefactura
	FROM facturacion f
	inner join catalogosucursal cs on f.idsucursal = cs.id
	WHERE folio = ".$_GET[factura]."";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$ivaret = $f->otrosivaretenido+$f->sobivaretenido+$f->ivaretenido;
	
$pdf->addReglement5("Razón Social: ".$f->nombre);
$pdf->addReglement6("Domicilio: ".$f->calle1." No ".$f->n1);
$pdf->addReglement7("Colonia: ".$f->colonia1);
$pdf->addReglement8("CP: ".$f->cp1);
$pdf->addReglement9("Población: ".$f->p1.", ".$f->e1);
$pdf->addReglement10("RFC: ".$f->rfc);

// --------------------------------$pdf->addCadenaOriginal($f->cadenaoriginal);

$xml = split('"',$f->xml);
		//echo $cosa[19];

//---------------------------------$pdf->addSelloOriginal($xml[19]);

	
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
		
		$pdf->SetFont( "Arial", "", 7);
		$conceptos = split('Concepto',$f->xml);

		$y    = 95;
		
		$subtgeneral = 0;
		
		$guias = "";
		$cantidad = 0;
		$elimporte = 0;
		$filas = 0;
		$cabeceraPuesta = false;
		$paginas = 0;
		if(count($conceptos)>=17){
			for($i=2; $i<count($conceptos)-1; $i++){
				$cabeceraPuesta = false;
				 $arreline = split('"',$conceptos[$i]);
				 
				 if($guias!=""){
					 $guias .= ",";
				 }
				 
				 $guias .= str_replace("GUIA ","",$arreline[3]);
				 $cantidad += $arreline[1];
				 $elimporte += $arreline[7];
				 
				 if($cantidad>8){
					 $pdf->SetFont( "Arial", "", 7);
					 $filas++;
					 $line = array( "CANTIDAD"    => $cantidad,
				   "CONCEPTO"  => $guias,
				   "IMPORTE"     => number_format($elimporte,2,'.',','),
				   "" => "");
					 
					$subtgeneral += $arreline[7];
					$size = $pdf->addLine( $y, $line );
					$y   += $size + 2;
					
					$guias = "";
					$cantidad = 0;
					$elimporte = 0;
				 }
				 
				 if($filas>=17){
					 $cabeceraPuesta = true;
					 cabeceras($pdf,$l,$paginas);
					 $y    = 95;
					 $pdf->addPage();
					 $filas=0;
					 $paginas++;
				 }
				// echo "$arreline[1] $arreline[3] $arreline[7]<br>";
				 
				// echo "pdf -> addLine( $y, $line )<br>";
			}
		}else{
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
		}
		if($cabeceraPuesta==false){
			cabeceras($pdf,$l,$paginas);
		}
	
	$linetotales = split('Traslado',$f->xml);
			//echo $linetotales[2];
			$arrelinetotales = split('"',$linetotales[2]);
			 //echo "xxxxx".$arrelinetotales[1];
			 $subtotal   = $subtgeneral;
			 $ivatotal   = $arrelinetotales[1];
			 //$ivaret   	 = $arrelinetotales[1];
			 $total		 = $arrelinetotales[1]+$subtgeneral-$ivaret;


/*$numalet= new CNumeroaletra; 
$numalet->setNumero(round($total,2)); 
$pdf->addNumeroLetra($numalet->letra());*/
$fec = split("-",substr($xml[23],0,10));
$fecha = $fec[2]."/".$fec[1]."/".$fec[0];

$pfolio = split("folio",$f->xml);
$pfolio = split('"',$pfolio[1]);

//$pdf->addCadreEurosFrancs($fecha,$pfolio[1],$f->lasucursal);
//$pdf->addTotales(number_format(round($subtotal,2),2,'.',','),number_format(round($ivatotal,2),2,'.',','),
//				 number_format(round($ivaret,2),2,'.',','),number_format(round($total,2),2,'.',','));

$aproba = split(" noAprobacion=",$f->xml);
$aproba = split('"',$aproba[1]);
$serie = split(" serie=",$f->xml);
$serie = split('"',$serie[1]);
$anoAp = split(" anoAprobacion=",$f->xml);
$anoAp = split('"',$anoAp[1]);

$pdf->tituloFactura("FACTURA");
$pdf->addSerieAprobacion($serie[1], $aproba[1], $anoAp[1]);
$pdf->addNumeroAno($f->tipopago,$f->tipofactura);
$pdf->addCertificado($f->seriefactura);
$pdf->addDirSucursal("EMITIDA EN $f->nombresucursal","$f->calle $f->numero, CP $f->cp, $f->colonia");
$pdf->addLeyenda(utf8_decode("Este Documento es una representación impresa de un CFD"));

$pdf->addPage();
$pdf->addCadenaOriginalUltima($f->cadenaoriginal);
$pdf->Ln(4);
$pdf->SetFont( "Arial", "B", 8);
$pdf->MultiCell(190,2, "Sello original", 0, "L");
$pdf->Ln(2);
$pdf->SetFont( "Arial", "", 5);
$pdf->MultiCell(191,2, $xml[19], 1, "L");

$pdf->Output();
?>
