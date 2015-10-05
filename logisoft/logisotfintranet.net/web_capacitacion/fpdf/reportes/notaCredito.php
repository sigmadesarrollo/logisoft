<?	require_once('../fpdf.php');
	//require_once('../invoice.php');
	require_once('../../Conectar.php');
	require_once("../../clases/EnLetras.php");
	$l = Conectarse('webpmm');
	$V=new EnLetras();
	/*$s = "SELECT b.folio, DATE_FORMAT(b.fechabitacora,'%d/%m/%Y') AS fechabitacora, b.unidad,
	b.remolque1, b.remolque2, r.descripcion AS ruta, b.gastos,
	CONCAT_WS(' ',e1.nombre,e1.apellidopaterno,e1.apellidomaterno) AS conductor1,
	IF(b.conductor2=0,'',CONCAT_WS(' ',e2.nombre,e2.apellidopaterno,e2.apellidomaterno)) AS conductor2,
	IF(b.conductor3=0,'',CONCAT_WS(' ',e3.nombre,e3.apellidopaterno,e3.apellidomaterno)) AS conductor3
	FROM bitacorasalida b
	INNER JOIN catalogoempleado e1 ON b.conductor1 = e1.id
	LEFT JOIN catalogoempleado e2 ON b.conductor2 = e2.id
	LEFT JOIN catalogoempleado e3 ON b.conductor3 = e3.id
	INNER JOIN catalogoruta r ON b.ruta = r.id
	WHERE folio = ".$_GET[bitacora]."";*/
	$s = "SELECT CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) ncliente, 
	d.calle, d.numero, d.colonia, d.codigo, cc.rfc, d.poblacion, d.estado,
	t1.*,
	CONCAT_WS(' ',c1.nombre, c1.apellidopaterno, c1.apellidomaterno) formulo,
	CONCAT_WS(' ',c2.nombre, c2.apellidopaterno, c2.apellidomaterno) reviso,
	CONCAT_WS(' ',c3.nombre, c3.apellidopaterno, c3.apellidomaterno) autorizo
	FROM catalogocliente cc
	INNER JOIN direccion d ON cc.id = d.codigo
	INNER JOIN (
		SELECT nc.cliente, SUM(ncd.importe) + (SUM(ncd.importe)*nc.impuestoporc) total,
		SUM(ncd.importe)*nc.impuestoporc AS iva, SUM(ncd.importe) importe, formulo, reviso, autorizo
		FROM notacredito nc
		INNER JOIN notacreditodetalle ncd ON nc.folio = ncd.folionotacredito
		WHERE nc.folio = $_GET[folionota]
	) AS t1 ON t1.cliente = cc.id
	INNER JOIN catalogoempleado c1 ON t1.formulo = c1.id
	INNER JOIN catalogoempleado c2 ON t1.reviso = c2.id
	INNER JOIN catalogoempleado c3 ON t1.autorizo = c3.id
	GROUP BY cc.id ";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$f->totalenletra = $V->ValorEnLetras($f->importe,"pesos");
	
	class PDF extends FPDF{
		function Header(){
			//Logo
			$this->Image('../logo.jpg',10,8);
			$this->Ln(10);
			//Arial bold 15		
			$this->SetFont('Arial','B',15);
		
			//Movernos a la derecha		
			$this->Cell(80);
		
			//Titulo		
			$this->Cell(70,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');
			
			$this->Ln(10);
			$this->Cell(80);
			$this->SetFont('Arial','B',12);
			$this->Cell(70,10,'NOTA DE CRÉDITO',0,0,'C');
			$this->Ln(10);
		}
		
		function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}
		
		function addFirma($mode,$p1,$p2,$titulo,$linea){
			$r1  = $p1;
			$r2  = $r1 + $linea;
			$y1  = $p2;
			$y2  = $y1+10;
			$mid = $y1 + (($y2-$y1) / 2);
			//$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
			$this->Line($r1, $mid, $r2, $mid);
			$this->SetXY($r1 + ($r2-$r1)/2 -10 , ($y1 + 1) -5 );
			$this->SetFont("Arial", "B", 8);
			$this->Cell(10,5, utf8_decode($titulo), 0, 0, "L");
			$this->SetXY($r1 + ($r2-$r1)/2 -28 , $y1 + 5 );
			$this->SetFont( "Arial", "", 8);
			$this->Cell(10,5, utf8_decode($mode), 0, 0, "L");
		}
		
		function addLeyenda($ref,$posicion){
			$this->SetFont( "Arial", "B", 10);
			$length = $this->GetStringWidth($ref);
			$r1  = 10;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function addNotas($ref,$posicion){
			$this->SetFont( "Arial", "B", 7);
			$length = $this->GetStringWidth($ref);
			$r1  = 10;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function addLeyenda2($ref,$posicion,$posicion2,$alineacion=null){
			$this->SetFont( "Arial", "B", 10);
			$length = $this->GetStringWidth($ref);
			$r1  = $posicion2;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$alineacion = ($alineacion==null)?"L":$alineacion;
			$this->Cell($length,4, $ref, 0, 0, "J");
		}
		
		function precintos(){
			$r1  = $this->w - 86;
			$r2  = $r1 + 60;
			$y1  = $this->h - 195;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, ($r2 - $r1), ($y2-$y1), 0, 'D');
			$this->Line( $r1+20,  $y1, $r1+20, $y2-5); // Linea Izq
			$this->Line( $r1+0, $y1, $r2, $y1); // Line Med
			$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont( "Arial", "B", 8);
			$this->SetXY( $r1+21, $y1-4 );
			$this->Cell(15,4, "PRECINTOS", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(20,4, "POSTERIOR", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(60,4, "LATERAL DER", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(100,4, "LATERAL IZQ", 0, 0, "C");
		}
		
		function firmas(){
			$r1  = $this->w - 128;
			$r2  = $r1+ 60;
			$y1  = $this->h - 195;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, (($r2-18) - $r1), ($y2-$y1), 0, 'D');
			$this->Line( $r1+20,  $y1, $r1+20, $y2-5); // Linea Izq
			$this->Line( $r1, $y1, $r2-18, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont( "Arial", "B", 8);
			$this->SetXY( $r1+13, $y1-4 );
			$this->Cell(15,4, "FIRMAS", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(20,4, "CHOFER", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(60,4, "DOCUMENTADOR", 0, 0, "C");
		}
		
		function horario(){
			$r1  = $this->w - 185;
			$r2  = $r1+ 60;
			$y1  = $this->h - 195;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, (($r2-18) - $r1), ($y2-$y1), 0, 'D');
			$this->Line( $r1+20,  $y1, $r1+20, $y2-5); // Linea Izq
			$this->Line( $r1, $y1, $r2-18, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont( "Arial", "B", 8);
			$this->SetXY( $r1+13, $y1-4 );
			$this->Cell(15,4, "HORARIO", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(20,4, "ARRIBAR", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(60,4, "SALIDA", 0, 0, "C");
		}
		
		function fecha($texto,$pos1,$pos2){
			$r1  = $this->w - $pos1;
			$r2  = $r1+ 60;
			$y1  = $this->h - $pos2;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, (($r2-45) - $r1), ($y2-$y1), 0, 'D');
			//$this->Line( $r1+20,  $y1, $r1+20, $y2-5); // Linea Izq
			//$this->Line( $r1, $y1, $r2-18, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont( "Arial", "B", 8);
			$this->SetXY( $r1, $y1-2);
			$this->Cell(15,4, $texto, 0, 0, "C");
		}
		
		function ciudad($texto,$posicion1,$posicion2){
			$r1  = $this->w - $posicion1;
			$r2  = $r1+ 60;
			$y1  = $this->h - $posicion2;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, (($r2-45) - $r1), ($y2-$y1), 0, 'D');
			//$this->Line( $r1+20,  $y1, $r1+20, $y2-5); // Linea Izq
			//$this->Line( $r1, $y1, $r2-18, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont( "Arial", "B", 10);
			$this->SetXY( $r1, $y1-2);
			$this->Cell(15,4, $texto, 0, 0, "C");
		}
		
		function ab($texto1,$texto2,$pos1,$pos2){
			$r1  = $this->w - $pos1;
			$r2  = $r1+ 60;
			$y1  = $this->h - $pos2;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, (($r2-45) - $r1), ($y2-$y1), 0, 'D');
			//$this->Line( $r1+20,  $y1, $r1+20, $y2-5); // Linea Izq
			$this->Line( $r1, $y1, $r2-45, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont("Arial", "B", 6);
			$this->SetXY($r1, $y1-4);
			$this->Cell(15,4, $texto1, 0, 0, "C");
			
			$this->SetFont("Arial", "B", 6);
			$this->SetXY($r1, $y1 );
			$this->Cell(15,4, $texto2, 0, 0, "C");
		}
		
		function fechaAS($texto1,$texto2,$pos1,$pos2){
			$r1  = $this->w - $pos1;
			$r2  = $r1+ 60;
			$y1  = $this->h - $pos2;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, (($r2-45) - $r1), ($y2-$y1), 0, 'D');
			$this->Line($r1+3, $y1+5, $r1+3, $y2-15); // Linea Izq
			$this->Line($r1, $y1, $r2-45, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont("Arial", "B", 6);
			$this->SetXY($r1-6, $y1-4);
			$this->Cell(15,4, $texto1, 0, 0, "C");
			
			$this->SetFont("Arial", "B", 6);
			$this->SetXY($r1-6, $y1);
			$this->Cell(15,4, $texto2, 0, 0, "C");
		}
		
		function cuadrosPrecintos($pos1,$pos2){
			$r1  = $this->w - $pos1;
			$r2  = $r1 + 60;
			$y1  = $this->h - $pos2;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, ($r2 - $r1), ($y2-$y1), 0, 'D');
			$this->Line( $r1+20,  $y1+5, $r1+20, $y2-15); // Linea Izq
			$this->Line( $r1+0, $y1, $r2, $y1); // Line Med
			$this->Line( $r1+40,  $y1+5, $r1+40, $y2-15); // Linea Der
			
			/*$this->SetFont( "Arial", "B", 8);
			$this->SetXY( $r1+21, $y1-4 );
			$this->Cell(15,4, "PRECINTOS", 0, 0, "C");*/
			
			/*$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(20,4, "POSTERIOR", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(60,4, "LATERAL DER", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(100,4, "LATERAL IZQ", 0, 0, "C");*/
		}
		
		function cuadrosFirmas($pos1,$pos2, $nombre){
			$r1  = $this->w - $pos1;
			$r2  = $r1+ 60;
			$y1  = $this->h - $pos2;
			$y2  = $y1+10;
			$this->RoundedRect($r1, $y1-5, (($r2-18) - $r1), ($y2-$y1), 0, 'D');
			$this->Line( $r1+20,  $y1+5, $r1+20, $y2-15); // Linea Izq
			//$this->Line( $r1, $y1, $r2-18, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont( "Arial", "B", 8);
			$this->SetXY( $r1+13, $y1-4 );
			$this->Cell(15,4, $nombre, 0, 0, "C");
			/*
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(20,4, "CHOFER", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(60,4, "DOCUMENTADOR", 0, 0, "C");*/
		}
		
		function RoundedRect($x, $y, $w, $h, $r, $style = ''){
			$k = $this->k;
			$hp = $this->h;
			if($style=='F')
				$op='f';
			elseif($style=='FD' || $style=='DF')
				$op='B';
			else
				$op='S';
			$MyArc = 4/3 * (sqrt(2) - 1);
			$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
			$xc = $x+$w-$r ;
			$yc = $y+$r;
			$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
		
			$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
			$xc = $x+$w-$r ;
			$yc = $y+$h-$r;
			$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
			$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
			$xc = $x+$r ;
			$yc = $y+$h-$r;
			$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
			$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
			$xc = $x+$r ;
			$yc = $y+$r;
			$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
			$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
			$this->_out($op);
		}

		function _Arc($x1, $y1, $x2, $y2, $x3, $y3){
			$h = $this->h;
			$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
								$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
		}
		
		function Titulos($titulos,$medidas){
			$this->SetFont('Arial','B',7);
			for($i=0;$i<count($titulos);$i++){	
				$this->Cell($medidas[$i],7,$titulos[$i],1,0,'C');						
			}
			$this->Ln();
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
				if(is_numeric($data[$i]) && $i<>0){
					$this->MultiCell($w,5, "$".number_format($data[$i],2,".",","),0,'R');
				}else{
					$this->MultiCell($w,5,$data[$i],0,$a);
				}
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
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
		
		function CheckPageBreak($h){
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
		}
	}
	
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$pdf->addLeyenda("A FAVOR DE",49);
	$pdf->addLeyenda2($f->cliente,49,40); $pdf->addLeyenda2($f->ncliente,49,55);
	
	$pdf->addLeyenda("DIRECCIÓN",54);
	$pdf->addLeyenda2("$f->calle, $f->numero, $f->colonia, $f->codigo",54,40);
	
	$pdf->addLeyenda("RFC",59);
	$pdf->addLeyenda2($f->rfc,59,40);
	
	$pdf->addLeyenda2($f->poblacion,59,90); $pdf->addLeyenda2($f->estado,59,125);
	
	
	$titulos = array('CANTIDAD','DESCRIPCION','IMPORTE');
	$medidas = array(20,100,65);
	
	//$pdf->SetFont('Arial','B',7);
	//Table with 20 rows and 4 columns
	
	$pdf -> Ln(10);
	
	$pdf->SetWidths($medidas);
	$pdf->Titulos($titulos,$medidas);
	
	$s = "SELECT ncd.cantidad, ncd.importe, ncd.descripcion
	FROM notacredito nc
	INNER JOIN notacreditodetalle ncd ON nc.folio = ncd.folionotacredito
	WHERE nc.folio = $_GET[folionota]";
	$rx = mysql_query($s,$l) or die($s);
	
	while($fx = mysql_fetch_object($rx)){
		/*$pdf->addLeyenda2($fx->cantidad,68+(5*$i),30);
		$pdf->addLeyenda2($fx->descripcion,68+(5*$i),40);
		$pdf->addLeyenda2($fx->importe,68+(5*$i),90);
		$i++;*/
		$data[] = array('0'=>$fx->cantidad,'1'=>strtoupper($fx->descripcion),'2'=>$fx->importe);
	}
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
	$pdf->addLeyenda2("CANTIDAD EN LETRA: ",163,10);
	$pdf->addLeyenda2(strtoupper($f->totalenletra),168,10);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY( 133 , 170 );
	$pdf->Cell(60,10, "$ ".number_format($f->importe,2,".",","), 0, 0, 'R');
	$pdf->SetXY( 133 , 175 );
	$pdf->Cell(60,10, "$ ".number_format($f->iva,2,".",","), 0, 0, 'R');
	$pdf->SetXY( 133 , 180 );
	$pdf->Cell(60,10, "$ ".number_format($f->total,2,".",","), 0, 0, 'R');
	
	$pdf->addFirma($f->formulo,10,260,"FORMULO",60);
	$pdf->addFirma($f->reviso,75,260,"REVISO",60);
	$pdf->addFirma($f->autorizo,140,260,"AUTORIZO",60);
	
	/*$pdf->cuadrosFirmas(50, 50, "Pedro nogales");
	$pdf->addLeyenda2($f->importe,170,80,"R");
	$pdf->addLeyenda2($f->iva,180,80,"R");
	$pdf->addLeyenda2($f->total,190,80,"R");*/
	
	/*$pdf->addLeyenda("Conductor: ".$f->conductor1."",54);
	$pdf->addLeyenda("Conductor: ".$f->conductor2."",59);
	$pdf->addLeyenda("Conductor: ".$f->conductor3."",64);
	$pdf->addLeyenda("Unidad: ".$f->unidad."",69);
	$pdf->addLeyenda("Ruta: ".$f->ruta."",74);
	$pdf->addLeyenda("Gastos: $".number_format($f->gastos,2,'.',',')."",85);
	$pdf->addLeyenda2("Remolque: ".$f->remolque1."",69,70);
	$pdf->addLeyenda2("Remolque: ".$f->remolque2."",69,130);
	$pdf->addLeyenda2("Firma Recibido: _______________________________",85,80);
	$pdf->addLeyenda("A = ARRIBAR,LLEGADA             S = SALIDA",92);		
	$pdf->precintos();
	$pdf->firmas();
	$pdf->horario();
	$pdf->fecha("FECHA",143,195);
	$pdf->ciudad("CD.",200,195);
	$pdf->ciudad("",26,195);
	
	$alto = 10;
		for($i=0;$i<17;$i++){
			$pdf->ciudad("",200,(195-$alto));
			$pdf->ab("A","S",26,(195-$alto));
			$pdf->fechaAS("A","S",143,(195-$alto));
			$pdf->cuadrosPrecintos(86,(195-$alto));
			$pdf->cuadrosFirmas(128,(195-$alto));
			$pdf->cuadrosFirmas(185,(195-$alto));
			$alto = $alto + 10;
		}
	$pdf->AddPage();
	$tit = array('SUCURSAL','CONDUCTOR (ES)','CORMZ - CMO','AUTORIDAD (ES)');
	
	$pos = 49;	
	for($i=0;$i<count($tit);$i++){
		$pdf->addLeyenda($tit[$i],$pos);
		for($k=0;$k<7;$k++){
			$pos = $pos + 6;
			$pdf->addLeyenda("__________________________________________________________________________________________",$pos);
		}		
		$pdf->Ln(10);
		$pos = $pos + 6;
	}
	
	$pdf->addNotas("1.- SR. DOCUMENTADOR FAVOR DE ANOTAR LOS DATOS EN FORMA CLARA Y SIN TACHADURAS.",$pos);
	$pdf->addNotas("2.- EL CHOFER QUE LLEGA EN CADA SUCURSAL CONDUCIENDO LA UNIDAD, SERA EL QUE FIRME EN PRESENCIA DEL DOCUMENTADOR.",($pos+4));	
	$pdf->addNotas("3.- AUTORIDADES CIVILES O MILITARES FAVOR DE REGISTRAR EL No. DE FOLIO DEL PRECINTO QUE SE RETIRA, ASI COMO EL DEL QUE INSTALA.",($pos+8));*/
	$pdf->Output();
?>