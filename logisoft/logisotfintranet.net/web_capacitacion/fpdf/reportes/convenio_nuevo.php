<?	
	//session_start();
	require('../fpdf.php');
	require_once("../../Conectar.php");
	
	class pdf extends FPDF{
		var $widths;
		var $aligns;
		
		function Header(){
			$l = Conectarse("webpmm");
			$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $ft = mysql_fetch_object($r);
			//Logo
			$this->Image('../logo.jpg',18,8,33);
			$this->Ln(10);
			//Arial bold 15		
			$this->SetFont('Arial','B',12);		
			//Movernos a la derecha		
			$this->Cell(80);		
			//Titulo		
			//$this->Cell(40,10,'ENTREGAS PUNTUALES S DE RL DE CV',0,0,'C');			
			$this->Cell(100,10,'No.'.$_GET[folio],0,0,'R');
			$this->Ln(10);
			$this->SetFont('Arial','B',12);
			$this->Cell(200,10,'CONVENIO DE PRESTACION DE SERVICIOS',0,0,'C');			
			$this->Ln(6);
			$this->SetTextColor(5,66,129);
			$this->Cell(20,20,'Paqueteria y Mensajeria',0,0,'L');			
			$this->SetTextColor(0,0,0);
			$this->Ln(5);
			
		}	
		
		function Titulos($titulos,$medidas){
			for($i=0;$i<count($titulos);$i++){	
				$this->Cell($medidas[$i],7,$titulos[$i],1,0,'C');						
			}
			$this->Ln();
		}
		
		function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}
		
		function addLeyenda($ref,$posicion){
			$this->SetFont( "Arial", "", 10);
			$length = $this->GetStringWidth($ref);
			$r1  = 10;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function addFirma($mode,$p1,$p2,$titulo,$linea){
			$r1  = $p1;
			$r2  = $r1 + $linea;
			$y1  = $p2;
			$y2  = $y1+10;
			$mid = $y1 + (($y2-$y1) / 2);
			//$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
			$this->Line($r1, $mid, $r2, $mid);
			$this->SetXY($r1 + ($r2-$r1)/2 -3 , ($y1 + 10));
			$this->SetFont("Arial", "B", 8);
			$this->Cell(10,5, utf8_decode($titulo), 0, 0, "C");
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
			$this->SetFont( "Arial", "", 8);
			$this->Cell(10,5, utf8_decode($mode), 0, 0, "C");
		}
		
		function addFirma2($mode,$mode2,$p1,$p2,$titulo,$linea){
			
			$mode 	= utf8_encode($mode);
			$mode2 	= utf8_encode($mode2);
			$titulo = utf8_encode($titulo);
			
			$r1  = $p1;
			$r2  = $r1 + $linea;
			$y1  = $p2;
			$y2  = $y1+10;
			$mid = $y1 + (($y2-$y1) / 2);
			//$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
			$this->Line($r1, $mid, $r2, $mid);
			
			$this->SetXY($r1 + ($r2-$r1)/2-5 , ($y1 - 25));
			$this->SetFont("Arial", "B", 8);
			$this->Cell(10,5, utf8_decode($titulo), 0, 0, "C");
			
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
			$this->SetFont( "Arial", "", 8);
			$this->Cell(10,5, utf8_decode($mode), 0, 0, "C");
			
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 10 );
			$this->SetFont( "Arial", "", 8);
			$this->Cell(10,5, utf8_decode($mode2), 0, 0, "C");
		}
		
		function addFirma3($mode,$mode2,$p1,$p2,$titulo,$linea){
			$mode 	= utf8_encode($mode);
			$mode2 	= utf8_encode($mode2);
			$titulo = utf8_encode($titulo);
			
			$r1  = $p1;
			$r2  = $r1 + $linea;
			$y1  = $p2;
			$y2  = $y1+10;
			$mid = $y1 + (($y2-$y1) / 2);
			//$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
			$this->Line($r1, $mid, $r2, $mid);
			
			$this->SetXY($r1 + ($r2-$r1)/2-5 , ($y1 + 9));
			$this->SetFont("Arial", "B", 8);
			$this->Cell(10,5, utf8_decode($titulo), 0, 0, "C");
			
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
			$this->SetFont( "Arial", "", 8);
			$this->Cell(10,5, utf8_decode($mode), 0, 0, "C");
			
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 14 );
			$this->SetFont( "Arial", "", 8);
			$this->Cell(10,5, utf8_decode($mode2), 0, 0, "C");
		}
		
		function SetWidths($w){
			//Set the array of column widths
			$this->widths=$w;
		}
		
		function SetAligns($a){
			//Set the array of column alignments
			$this->aligns=$a;
		}
		
		function Row($data){
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++)
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			$h=5*$nb;
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			//Draw the cells of the row
			for($i=0;$i<count($data);$i++){
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				$this->Rect($x,$y,$w,$h);
				//Print the text
				if(!is_numeric($data[$i])){
					$this->MultiCell($w,5,$data[$i],0,$a);
				}else{
					$this->MultiCell($w,5, "$".number_format($data[$i],2,".",","),0,'R');
				}
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
		}
		
		function CheckPageBreak($h){
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
		}
		
		
		function NbLines($w,$txt){
			//Computes the number of lines a MultiCell of width w will take
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb)
			{
				$c=$s[$i];
				if($c=="\n")
				{
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
				}
				if($c==' ')
					$sep=$i;
				$l+=$cw[$c];
				if($l>$wmax)
				{
					if($sep==-1)
					{
						if($i==$j)
							$i++;
					}
					else
						$i=$sep+1;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
				}
				else
					$i++;
			}
			return $nl;
		}
	}
	
	$l = Conectarse("webpmm");

	$s = "SET lc_time_names = 'es_MX'";
	mysql_query($s,$l) or die("error ".mysql_error($l)."--".$s);

	$s = "SELECT gc.folio, gc.consumomensual, DATE_FORMAT(gc.fecha, '%d/%m/%Y') AS fecha, 
	DATE_FORMAT(gc.vigencia, '%d/%m/%Y') AS vigencia,
	cs.descripcion AS sucursal, gc.nvendedor, gc.idcliente, gc.rfc, 
	concat_ws(' ',gc.nombre, gc.apaterno, gc.amaterno) as ncliente, gc.calle, gc.numero,
	gc.colonia, gc.cp, gc.poblacion, gc.municipio, gc.estado, gc.pais, gc.celular, gc.telefono, gc.email,
	gc.precioporkg, gc.precioporcaja, gc.descuentosobreflete, gc.cantidaddescuento, gc.limitekg, gc.costo,
	gc.preciokgexcedente, gc.prepagadas, gc.consignacionkg, gc.consignacioncaja, gc.consignaciondescuento,
	gc.consignaciondescantidad, gc.valordeclarado, limite, porcada, costoextra,
	date_format(current_date, '%d/%m/%Y') as fechaactual,
	cs.estado eest, cs.municipio emun, gc.legal,
	CONCAT(day(gc.fecha),' de ', UCASE(MONTHNAME(gc.fecha)), ' de ',YEAR(gc.fecha)) fechaconvenio,
	CONCAT(day(gc.vigencia),' de ', UCASE(MONTHNAME(gc.vigencia)), ' de ',YEAR(gc.vigencia)) fechavigencia
	FROM generacionconvenio gc
	LEFT JOIN catalogosucursal cs ON gc.sucursal = cs.id
	WHERE gc.folio = '$_GET[folio]'";
	$r = mysql_query($s,$l) or die("error ".mysql_error($l)."--".$s);
	$f = mysql_fetch_object($r);
	//die($s);
	
	$pdf = new pdf();
	$pdf->AliasNbPages();
	$pdf -> AddPage();
	$pdf -> Ln(10);
	$pdf -> SetFont("Arial","",8);
	
	
	$contenido = 'Contrato de prestación de servicios que celebra por una parte ENTREGAS PUNTUALES S DE RL DE CV, a quien en lo sucesivo se le denominará "La Prestadora de Servicios", y por otra parte '.$f->ncliente.' representada por '.ucwords($f->legal).' a quien en lo sucesivo se le denominará "El Cliente".
	
	1.- La Prestadora de Servicios otorgará a "El Cliente" los siguientes servicios:
	                Guias Ventanilla                    Guias Electronicas
	                Entrega a Domicilio                 Recolección a Domicilio
	                Valor Declarado                     Acuse de Recibo
	                Entregas a Subdestinos
					
	2.- El precio del servicio de Recolección a Domicilio (RAD) y Entrega a Domicilio (EAD) es un 10% sobre el valor del flete o bien un cobro mínimo dependiendo de la Ciudad donde se origine el servicio de RAD o donde se envíe el servicio de EAD (ver tarifas).
		
	3.- El precio Valor Declarado es de $8.00 (Son Ocho Pesos 00/100 M.N.)  por millar, este seguro únicamente ampara riegos ordinarios de tránsito (incendio, accidente, asalto a mano armada). De ocurrir cualquiera de los eventos anteriores se cobrará un 20% de deducible sobre el valor declarado, de acuerdo con la póliza de seguros vigente. El máximo del Valor Declarado por guía o carta de porte es de $150,000.00 (Son Ciento cincuenta mil pesos 00/100 M.N).
	
	4.- El Acuse de Recibo tiene un costo de $ 15.00 (Son Quince pesos 00/100) por guía, el Cliente acepta que la falta de entrega de acuse no será una condicionante para la realización de pago de los envíos que realice con La Prestadora de Servicios.
	
	5.- Las Entregas a Subdestinos o poblados apartados donde no hay oficina PMM, a los que se va 1 o 2 veces por semana, tiene un costo adicional al precio conveniado (Ver Tarifas) por envío, en ningún caso será SIN COSTO.
	';
	
	$num = 5;
	$numpunto = 0;
	
	$tablasMostradas = 0;
	
	if($f->prepagadas==1){
		$num++;
		$contenido .= '
	'.$num.'.- El precio unitario de las Guías Prepagadas es $ '.number_format($f->costo,2,'.',',').' amparando hasta '.number_format($f->limitekg,2,'.','').' kilogramos cada una, se vende en paquetes mínimos de 30 guías. Estas guías incluyen el servicio de Recolección a Domicilio y Entrega a Domicilio, de excederse en los kilogramos el precio por kilogramo es de $ '.number_format($f->preciokgexcedente,2,'.',',').'
	';
		
		$num++;
		$contenido .= '
	'.$num.'.- El cliente dice que su consumo mensual estimado es de $ '.number_format($f->consumomensual, 2, ".",",").', en base a este consumo la Prestadora de Servicios ofrece la siguiente forma de cobro para sus envios:
	';
	}else{
		$num++;
		$contenido .= '
	'.$num.'.- El cliente dice que su consumo mensual estimado es de $ '.number_format($f->consumomensual, 2, ".",",").', en base a este consumo la Prestadora de Servicios ofrece la siguiente forma de cobro para sus envios:
	';
	}
	$pdf -> MultiCell(177,4,$contenido,0,'J');
	
	/* validaciones de convenios para guias normales */
	///////////////////////////////////////////////////////////////////////////////////////////////
	if($f->precioporkg==1){
		$tablasMostradas++;
		$numpunto++;
		$pdf -> MultiCell(177,4,'
	'.$num.'.'.$numpunto.'.- Precio por Kilogramo en Guías Ventanilla:
	',0,'J');
		
		$columnasde 	= 7;
		$columnas 		= 15;
		$medidastotal	= 188;
		
		
		
		$s = "SELECT CONCAT(kmi,'-',kmf,' KM') zonas, valor
		FROM cconvenio_configurador_preciokg 
		WHERE tipo = 'CONVENIO' AND idconvenio = '$_GET[folio]'
		GROUP BY zona
		order by zona
		limit 0,7";
		$rx = mysql_query($s,$l) or die($s);
		$titulos = array();
		$medidas = array();
		$data = array();
		while($fx = mysql_fetch_object($rx)){
				$titulos[] 	= $fx->zonas;
				$medidas[] 	= round($medidastotal/7,0);
				$data[]		= $fx->valor;
		}
		
		$pdf->SetFont('Arial','B',6);
		//Table with 20 rows and 4 columns
		$pdf->SetWidths($medidas);
		$pdf->Titulos($titulos,$medidas);
		
		$pdf->SetFont('Arial','',6);
		$pdf->Row($data);
		
		$pdf -> Ln(2);
		
		$s = "SELECT CONCAT(kmi,'-',kmf,' KM') zonas, valor
		FROM cconvenio_configurador_preciokg 
		WHERE tipo = 'CONVENIO' AND idconvenio = '$_GET[folio]'
		GROUP BY zona
		order by zona
		limit 8,20";
		$rx = mysql_query($s,$l) or die($s);
		$titulos = array();
		$medidas = array();
		$data = array();
		while($fx = mysql_fetch_object($rx)){
				$titulos[] 	= $fx->zonas;
				$medidas[] 	= round($medidastotal/7,0);
				$data[]		= $fx->valor;
		}
		
		$pdf->SetFont('Arial','B',6);
		//Table with 20 rows and 4 columns
		$pdf->SetWidths($medidas);
		$pdf->Titulos($titulos,$medidas);
		
		$pdf->SetFont('Arial','',6);
		$pdf->Row($data);
		
		if($numpunto==1){
			$pdf -> AddPage();
			$pdf -> Ln(10);
		}
	}
	
	if($f->precioporcaja==1){
		$tablasMostradas++;
		$numpunto++;
		$pdf -> MultiCell(177,4,'
		'.$num.'.'.$numpunto.'.- Precio por Caja en Guías Ventanilla:
		',0,'J');
		
		$titulos1 = array();
		$medidas1 = array();
		$data1 = array();
		$titulos2 = array();
		$medidas2 = array();
		$data2 = array();
		$filas = 0;
		$limites = array();
		//$titulos1[] = "";
		//$titulos2[] = "";
		
		$titulos1[] = "DESCRIP";
		$medidas1[] = 18;
		$titulos2[] = "DESCRIP";
		$medidas2[] = 18;
		
		
		$s = "SELECT CONCAT(kmi,'-',kmf,' KM') zonas, pesolimite, preciokgexcedente
		FROM cconvenio_configurador_caja 
		WHERE tipo='CONVENIO' AND idconvenio = '$_GET[folio]' GROUP BY zona order by zona";
		$rx = mysql_query($s,$l) or die($s);
		$columnasde = floor(mysql_num_rows($rx)/2);
		
		$columnas		= mysql_num_rows($rx);
		$medidatotal 	= 170;
		
		
		
		
		
		$contacolum = 0;
		while($fx = mysql_fetch_object($rx)){
			$contacolum++;
			if($contacolum<=$columnasde){
				$titulos1[] = $fx->zonas;
				$medida1 = round($medidatotal/$columnasde,0);
				$medidas1[] = $medida1;
			}else{
				$titulos2[] = $fx->zonas;
				$medida2 = round($medidatotal/($columnas-$columnasde),0);
				$medidas2[] = $medida2;
			}
		}
		
		$s = "SELECT descripcion, pesolimite, preciokgexcedente FROM cconvenio_configurador_caja 
		WHERE tipo='CONVENIO' AND idconvenio =  '$_GET[folio]' 
		GROUP BY descripcion order by zona";
		$rz = mysql_query($s,$l) or die($s);
		$filas = 0;
		while($fz = mysql_fetch_object($rz)){
			$data1[$filas] = array();
			$data2[$filas] = array();
			$s = "SELECT zona, precio 
			FROM cconvenio_configurador_caja WHERE tipo='CONVENIO' 
			AND idconvenio = '$_GET[folio]' and descripcion = '$fz->descripcion' order by zona asc";
			$rx = mysql_query($s,$l) or die($s);
			$columnasde = mysql_num_rows($rx)/2;
			$contacolum = 0;
			
			$data1[$filas][] = $fz->descripcion;
			$data2[$filas][] = $fz->descripcion;
			$limites[] = 'La descripción '.$fz->descripcion.' tiene un limite de '.$fz->pesolimite.' Kg y precio excedente de $ '.number_format($fz->preciokgexcedente,2,'.',',');
			
			while($fx = mysql_fetch_object($rx)){
				$contacolum++;
				if($contacolum<=$columnasde){
					$data1[$filas][] = $fx->precio;
				}else{
					$data2[$filas][] = $fx->precio;
				}
			}
			$filas++;
		}
		
		//die(print_r($data1));
		
		$pdf->SetFont('Arial','B',6);
		//Table with 20 rows and 4 columns
		$pdf->SetWidths($medidas1);
		$pdf->Titulos($titulos1,$medidas1);
		$pdf->SetFont('Arial','',6);
		for($i=0;$i<count($data1);$i++){
			$pdf->Row($data1[$i]);
		}
		
		$pdf -> Ln(2);
		$pdf->SetFont('Arial','B',6);
		$pdf->SetWidths($medidas2);
		$pdf->Titulos($titulos2,$medidas2);
		$pdf->SetFont('Arial','',6);
		for($i=0;$i<count($data2);$i++){
			$pdf->Row($data2[$i]);
		}
		
		$pdf -> Ln(5);
		
		for($i=0;$i<count($limites);$i++){
		$pdf->SetFont('Arial','',7);
			$pdf->MultiCell(177,4,$limites[$i],0,'L',0);
		}
		//MultiCell($w,$h,$txt,$border=0,$align='J',$fill=0)
		if($numpunto==1){
			$pdf -> AddPage();
			$pdf -> Ln(10);
		}
		
		$numpunto++;
		$pdf -> SetFont("Arial","",8);
		$pdf -> MultiCell(177,4,'
		'.$num.'.'.$numpunto.'.- Cualquier descripcion (tipo de empaque) no plasmada en este documento, se efectuará el cobro del '.$f->cantidaddescuento.' %',0,'J');
	}
	
	if($f->descuentosobreflete==1){
		$numpunto++;
		$pdf -> SetFont("Arial","",8);
		$pdf -> MultiCell(177,4,'
	'.$num.'.'.$numpunto.'.- Descuento sobre flete aplicado a Guias de Ventanilla: <Bold>'.$f->cantidaddescuento.' %</Bold>
	El descuento sobre flete no aplica a las tarifas mínimas establecidas por la prestadora de servicios, ni a los servicios adicionales.
	',0,'J');
	}
	
	$s = "SELECT servicio, cobro, precio FROM cconvenio_servicios WHERE idconvenio = $_GET[folio] and tipo = 'CONVENIO'";
	$rx = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($rx)>-1){
		$numpunto++;
		$pdf -> SetFont("Arial","",8);
		$pdf -> MultiCell(177,4,'
	'.$num.'.'.$numpunto.'.- La prestadora de servicios otorgará sin costo para el cliente en Guias de Ventanilla los siguientes servicios:',0,'J');
		$pdf -> SetFont("Arial","",7);
		while($fx = mysql_fetch_object($rx)){
			$pdf -> MultiCell(177,4,'		-'.$fx->servicio.'',0,'J');
		}
	}
	
	/* validaciones de convenios para guias empresariales */
	/////////////////////////////////////////////////////////////////////////////////////////////////
	if($f->consignacionkg==1){
		$tablasMostradas++;
		$numpunto++;
		$pdf -> SetFont("Arial","",8);
		$pdf -> MultiCell(177,4,'
	'.$num.'.'.$numpunto.'.- Precio por Kilogramo en Guías Electrónicas:
	',0,'J');
		
		
		$columnasde 	= 7;
		$columnas 		= 15;
		$medidastotal	= 188;
		
		$s = "SELECT CONCAT(kmi,'-',kmf,' KM') zonas, valor
		FROM cconvenio_configurador_preciokg 
		WHERE tipo = 'CONSIGNACION' AND idconvenio = '$_GET[folio]'
		GROUP BY zona
		order by zona
		limit 1,7";
		$rx = mysql_query($s,$l) or die($s);
		$titulos = array();
		$medidas = array();
		$data = array();
		while($fx = mysql_fetch_object($rx)){
				$titulos[] 	= $fx->zonas;
				$medidas[] 	= round($medidastotal/7,0);
				$data[]		= $fx->valor;
		}
		
		$pdf->SetFont('Arial','B',6);
		//Table with 20 rows and 4 columns
		$pdf->SetWidths($medidas);
		$pdf->Titulos($titulos,$medidas);
		
		$pdf->SetFont('Arial','',6);
		$pdf->Row($data);
		
		$pdf -> Ln(2);
		
		$s = "SELECT CONCAT(kmi,'-',kmf,' KM') zonas, valor
		FROM cconvenio_configurador_preciokg 
		WHERE tipo = 'CONSIGNACION' AND idconvenio = '$_GET[folio]'
		GROUP BY zona
		order by zona
		limit 8,20";
		$rx = mysql_query($s,$l) or die($s);
		$titulos = array();
		$medidas = array();
		$data = array();
		while($fx = mysql_fetch_object($rx)){
				$titulos[] 	= $fx->zonas;
				$medidas[] 	= round($medidastotal/7,0);
				$data[]		= $fx->valor;
		}
		
		$pdf->SetFont('Arial','B',6);
		//Table with 20 rows and 4 columns
		$pdf->SetWidths($medidas);
		$pdf->Titulos($titulos,$medidas);
		
		$pdf->SetFont('Arial','',6);
		$pdf->Row($data);
		
		if($numpunto==1){
			$pdf -> AddPage();
			$pdf -> Ln(10);
		}
	}
	
	if($f->consignacioncaja==1){
		$tablasMostradas++;
		$numpunto++;
		$pdf -> SetFont("Arial","",8);
		$pdf -> MultiCell(177,4,'
		'.$num.'.'.$numpunto.'.- Precio por Caja en Guías Electrónicas:		
		',0,'J');
		
		
		
		$titulos1 = array();
		$medidas1 = array();
		$data1 = array();
		$titulos2 = array();
		$medidas2 = array();
		$data2 = array();
		$filas = 0;
		//$titulos1[] = "";
		//$titulos2[] = "";
		
		$s = "SELECT CONCAT(kmi,'-',kmf,' KM') zonas FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' AND idconvenio = '$_GET[folio]' GROUP BY zona order by zona";
		$rx = mysql_query($s,$l) or die($s);
		$columnasde = floor(mysql_num_rows($rx)/2);
		
		$columnas		= mysql_num_rows($rx);
		$medidatotal 	= 170;
		
		$contacolum = 0;
		
		$titulos1[] = "DESCRIP";
		$medidas1[] = 18;
		$titulos2[] = "DESCRIP";
		$medidas2[] = 18;
		
		while($fx = mysql_fetch_object($rx)){
			$contacolum++;
			if($contacolum<=$columnasde){
				$titulos1[] = $fx->zonas;
				$medida1 = round($medidatotal/$columnasde,0);
				$medidas1[] = $medida1;
			}else{
				$titulos2[] = $fx->zonas;
				$medida2 = round($medidatotal/($columnas-$columnasde),0);
				$medidas2[] = $medida2;
			}
		}
		
		$s = "SELECT descripcion FROM cconvenio_configurador_caja 
		WHERE tipo='CONSIGNACION' AND idconvenio =  '$_GET[folio]' 
		GROUP BY descripcion order by zona";
		$rz = mysql_query($s,$l) or die($s);
		$filas = 0;
		while($fz = mysql_fetch_object($rz)){
			$data1[$filas] = array();
			$data2[$filas] = array();
			$s = "SELECT precio
			FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' 
			AND idconvenio = '$_GET[folio]' and descripcion = '$fz->descripcion'";
			$rx = mysql_query($s,$l) or die($s);
			$columnasde = mysql_num_rows($rx)/2;
			$contacolum = 0;
			
			$data1[$filas][] = $fz->descripcion;
			$data2[$filas][] = $fz->descripcion;
			
			while($fx = mysql_fetch_object($rx)){
				$contacolum++;
				if($contacolum<=$columnasde){
					$data1[$filas][] = $fx->precio;
				}else{
					$data2[$filas][] = $fx->precio;
				}
			}
			$filas++;
		}
		
		//die(print_r($data1));
		
		$pdf->SetFont('Arial','B',6);
		//Table with 20 rows and 4 columns
		$pdf->SetWidths($medidas1);
		$pdf->Titulos($titulos1,$medidas1);
		$pdf->SetFont('Arial','',6);
		for($i=0;$i<count($data1);$i++){
			$pdf->Row($data1[$i]);
		}
		
		$pdf -> Ln(2);
		
		$pdf->SetFont('Arial','B',6);
		$pdf->SetWidths($medidas2);
		$pdf->Titulos($titulos2,$medidas2);
		$pdf->SetFont('Arial','',6);
		for($i=0;$i<count($data2);$i++){
			$pdf->Row($data2[$i]);
		}
		
			
		if($numpunto==1){
			$pdf -> AddPage();
			$pdf -> Ln(10);
		}
		
		$numpunto++;
		$pdf -> SetFont("Arial","",8);
		$pdf -> MultiCell(175,4,'
'.$num.'.'.$numpunto.'.- Cualquier descripcion (tipo de empaque) no plasmada en este documento, se efectuará el cobro del '.$f->consignaciondescantidad.' %',0,'J');
	}
	
	if($f->consignaciondescuento==1){
		$numpunto++;
		$pdf -> SetFont("Arial","",8);
		$pdf -> MultiCell(177,4,'
	'.$num.'.'.$numpunto.'.- Descuento sobre flete aplicado a Guias Empresariales: '.$f->consignaciondescantidad.' %
	El descuento sobre flete no aplica a las tarifas mínimas establecidas por la prestadora de servicios, ni a los servicios adicionales.
	',0,'J');
	}
	
	$s = "SELECT servicio, cobro, precio FROM cconvenio_servicios WHERE idconvenio = $_GET[folio] and tipo = 'CONSIGNACION'";
	$rx = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($rx)>-1){
		$numpunto++;
		$pdf -> SetFont("Arial","",8);
		$pdf -> MultiCell(177,4,'
	'.$num.'.'.$numpunto.'.- La prestadora de servicios otorgará sin para el cliente en Guias Electrónicas los siguientes servicios:',0,'J');
		$pdf -> SetFont("Arial","",7);
		while($fx = mysql_fetch_object($rx)){
			$pdf -> MultiCell(177,4,'		-'.$fx->servicio.'',0,'J');
		}
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	//$pdf -> AddPage();
	$pdf -> SetFont("Arial","",8);
	$pdf -> MultiCell(177,4,'
	'.++$num.'.- El Cliente acepta que para determinar el peso de sus envíos, se considerará el peso báscula o volumétrico (largo x alto x ancho)/4000, tomándose siempre el que resulte superior.
	
	'.++$num.'.- Si El Cliente solicita Guías Electrónicas se le asignarán un password, nip y los folios de las guías, los cuales podrá utilizar a través de nuestra página web, el uso del nip y los folios es responsabilidad de El Cliente.  La elaboración de una guía electrónica no implicará una responsabilidad de su contenido para la Prestadora de Servicios hasta que esta confirme que ha recibido las mercancías.
	
	'.++$num.'.- El Cliente solicita que se consideren las siguientes Restricciones en sus envíos.
	
	'.++$num.'.- El Precio otorgado al cliente en este contrato es en base al consumo estimado prometido en el punto 8, de no cumplir con el consumo mensual La Prestadora de Servicios podrá dar por terminado este convenio en cualquier momento.
	
	'.++$num.'.- En caso de que el cliente no asegure sus mercancias declarando el valor en la carta porte "La Prestadora de Servicios" se compromete a cubrir por daños o extravío, lo establecido en el contrato de adhesión en su cláusula décima segunda de la guía o carta porte expedida.
	
	'.++$num.'.- A todos nuestros servicios le será agregado el 16% del impuesto al Valor Agregado.
	
	'.++$num.'.- Estos precios pueden variar sin previo aviso durante la vigencia de este contrato.',0,'J');
	
	if($tablasMostradas>=2){
		$pdf -> AddPage();
		$pdf -> Ln(10);
	}
	$pdf -> SetFont("Arial","",8);
	$pdf -> MultiCell(177,4,'
	'.++$num.'.- En caso de presentar inconformidad respecto a la interpretación, aplicación o ejecución de este contrato, las partes se someten a la competencia de los tribunales del fuero común y a las leyes que rigan en la Ciudad de Mazatlán, Sinaloa, renunciando expresamente a cualquier fuero que por su domicilio presente o futuro pudiera corresponder.
	
	DATOS GENERALES DEL CLIENTE
	Nombre o Razón Social: '.$f->ncliente.'  No. Cte '.$f->idcliente.' 
	Domicilio Fiscal: '.$f->calle.' '.$f->numero.' '.$f->colonia.'  C.P. '.$f->cp.'
	RFC: '.$f->rfc.'
	Teléfono(s): '.$f->telefono.'  Correo Electrónico: '.$f->email.'
	
	Este convenio se celebra en la ciudad de '.$f->emun.', '.$f->eest.' el '.$f->fechaconvenio.' , con vencimiento el '.$f->fechavigencia.'. Sujeto a revisión y aprobación del Departamento de Ventas corporativas en un lapso 72 horas hábiles.',0,'J');
	
	$pdf->addFirma('"La Prestadora De Servicios"',20,248,"Entregas Puntuales S De RL de CV",30);
	$pdf->addFirma2("*Sujeto a aprobación del departamento de Ventas Corporativas",'*Convenio entrará en vigor en un lapso de 72 hrs Máximo',90,260,"Firma de Conformidad",40);
	$pdf->addFirma3($f->ncliente,"Representante Legal",160,248,'"EL CLIENTE"',30);
	
	$pdf -> Output();



?>