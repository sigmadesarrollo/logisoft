<?	require_once('../fpdf.php');
	
	class PDF extends FPDF{
		
		function addCuadros(){
			$r1  = $this->w - 100;//SE DESPLAZA IZQ A DER
			$r2  = $r1+ 50;//ANCHO DEL CUADRO
			$y1  = $this->h - 250;//SE MUEVE ARRIBA Y ABAJO
			$y2  = $y1+10;//ALTO DEL CUADRO
			$this->RoundedRect($r1, $y1-5, (($r2-25) - $r1), ($y2-$y1), 0, 'D');
			$this->Line( $r1+13,  $y1, $r1+13, $y2-5); // Linea Izq
			$this->Line( $r1, $y1, $r2-25, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont( "Arial", "B", 6);
			$this->SetXY( $r1, $y1-4 );
			$this->MultiCell($this->GetStringWidth("COMUNICACION ORGANIZACIONAL")-10,2, "COMUNICACION ORGANIZACIONAL", 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(13,4, "SEM", 0, 0, "C");
			
			$this->SetFont( "Arial", "B", 6);	
			$this->SetXY( $r1, $y1+1 );
			$this->Cell(38,4, "CAL", 0, 0, "C");
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
	}
	
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$pdf -> addCuadros();
	
	$pdf->Output();

?>