<?php
// (c) Xavier Nicolay
// Exemple de gnration de devis/facture PDF

require('invoice.php');

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->Image('logo.jpg',10,8,33);
/*$pdf->addSociete( "Paqueteria y Mensajeria en Movimiento",
                  "Ofna. Matriz Fco. Serrano No 2316-306 Tel:(669) 985 48 11\n" .
                  "C.P.:82000 Col. Centro, Mazatlan, Sinaloa\n".
                  "R.F.C. PMM-900725-698\n" .
                  "Contribuyente del Regimen de Transparencia");*/
//$pdf->fact_dev( "Devis ", "TEMPO" );
//$pdf->temporaire( "Devis temporaire" );
/*$pdf->addDate(date('d/m/Y'));
$pdf->addClient("CL01");
$pdf->addPageNumber("1");*/
//$pdf->addClientAdresse("Ste\nM. XXXX\n3me tage\n33, rue d'ailleurs\n75000 PARIS");


$pdf->addReglement("Paqueteria y Mensajeria en Movimiento S.A. de C.V.");
$pdf->addReglement1("Ofna. Matriz Fco. Serrano No 2316-306");
$pdf->addReglement2("Tel:(669) 985 48 11 C.P.:82000");
$pdf->addReglement3("Col. Centro, Mazatlan, Sinaloa");
$pdf->addReglement4("R.F.C. PMM-900725-698");

$pdf->addReglement5("Paqueteria y Mensajeria en Movimiento S.A. de C.V.");
$pdf->addReglement6("Ofna. Matriz Fco. Serrano No 2316-306");
$pdf->addReglement7("Tel:(669) 985 48 11 C.P.:82000");
$pdf->addReglement8("Col. Centro, Mazatlan, Sinaloa");
$pdf->addReglement9("R.F.C. PMM-900725-698");

$pdf->addSerie("15");

//$pdf->addEcheance("03/12/2003");
//$pdf->addNumTVA("FR888777666");
//$pdf->addReference("Devis ... du ....");
$cols=array( "CANTIDAD"    => 30,
             "CONCEPTO"  => 110,
             "IMPORTE"     => 70);
$pdf->addCols( $cols);
$cols=array( "CANTIDAD"    => "C",
             "CONCEPTO"  => "L",
             "IMPORTE"     => "R");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 109;
/*$line = array( "REFERENCE"    => "REF1",
               "DESIGNATION"  => "Carte Mre MSI 6378\n" .
                                 "Processeur AMD 1Ghz\n" .
                                 "128Mo SDRAM, 30 Go Disque, CD-ROM, Floppy, Carte vido",
               "QUANTITE"     => "1",
               "P.U. HT"      => "600.00",
               "MONTANT H.T." => "600.00",
               "TVA"          => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "REFERENCE"    => "REF2",
               "DESIGNATION"  => "Cble RS232",
               "QUANTITE"     => "1",
               "P.U. HT"      => "10.00",
               "MONTANT H.T." => "60.00",
               "TVA"          => "1" );
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;
*/
$pdf->addCadreTVAs();
        
// invoice = array( "px_unit" => value,
//                  "qte"     => qte,
//                  "tva"     => code_tva );
// tab_tva = array( "1"       => 19.6,
//                  "2"       => 5.5, ... );
// params  = array( "RemiseGlobale" => [0|1],
//                      "remise_tva"     => [1|2...],  // {la remise s'applique sur ce code TVA}
//                      "remise"         => value,     // {montant de la remise}
//                      "remise_percent" => percent,   // {pourcentage de remise sur ce montant de TVA}
//                  "FraisPort"     => [0|1],
//                      "portTTC"        => value,     // montant des frais de ports TTC
//                                                     // par defaut la TVA = 19.6 %
//                      "portHT"         => value,     // montant des frais de ports HT
//                      "portTVA"        => tva_value, // valeur de la TVA a appliquer sur le montant HT
//                  "AccompteExige" => [0|1],
//                      "accompte"         => value    // montant de l'acompte (TTC)
//                      "accompte_percent" => percent  // pourcentage d'acompte (TTC)
//                  "Remarque" => "texte"              // texte
$tot_prods = array( array ( "px_unit" => 600, "qte" => 1, "tva" => 1 ),
                    array ( "px_unit" =>  10, "qte" => 1, "tva" => 1 ));
$tab_tva = array( "1"       => 19.6,
                  "2"       => 5.5);
$params  = array( "RemiseGlobale" => 1,
                      "remise_tva"     => 1,       // {la remise s'applique sur ce code TVA}
                      "remise"         => 0,       // {montant de la remise}
                      "remise_percent" => 10,      // {pourcentage de remise sur ce montant de TVA}
                  "FraisPort"     => 1,
                      "portTTC"        => 10,      // montant des frais de ports TTC
                                                   // par defaut la TVA = 19.6 %
                      "portHT"         => 0,       // montant des frais de ports HT
                      "portTVA"        => 19.6,    // valeur de la TVA a appliquer sur le montant HT
                  "AccompteExige" => 1,
                      "accompte"         => 0,     // montant de l'acompte (TTC)
                      "accompte_percent" => 15,    // pourcentage d'acompte (TTC)
                  "Remarque" => "Avec un acompte, svp..." );

//$pdf->addTVAs( $params, $tab_tva, $tot_prods);
$pdf->addCadreEurosFrancs();
$pdf->Output();
?>
