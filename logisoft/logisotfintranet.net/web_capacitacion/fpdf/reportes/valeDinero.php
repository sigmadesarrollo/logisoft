<?	require_once('../fpdf.php');
	require_once('../../clases/CNumeroaLetra.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS gerente FROM catalogoempleado 
	WHERE id=".$_GET[gerente]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	$gerente = cambio_texto($f->gerente);
	
	class pdf extends FPDF{
		
		function titulo($ref,$posicion,$posicion2){
			$this->SetFont( "Arial", "B", 12);
			$length = $this->GetStringWidth($ref);
			$r1  = $posicion2;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function addLeyenda2($ref,$posicion,$posicion2){
			$this->SetFont( "Arial", "B", 10);
			$length = $this->GetStringWidth($ref);
			$r1  = $posicion2;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function ciudad($texto,$posicion1,$posicion2){
			$r1  = $this->w - $posicion1;
			$r2  = $r1 + 170;
			$y1  = $this->h - $posicion2;
			$y2  = $y1 + 100;
			$this->RoundedRect($r1, $y1-5, (($r2-45) - $r1), ($y2-$y1), 0, 'L');
			//$this->Line( $r1+20,  $y1, $r1+20, $y2-5); // Linea Izq
			//$this->Line( $r1, $y1, $r2-18, $y1); // Line Med
			//$this->Line( $r1+40,  $y1, $r1+40, $y2-5); // Linea Der
			
			$this->SetFont( "Arial", "B", 10);
			$this->SetXY( $r1, $y1-2);
			$this->Cell(15,4, $texto, 0, 0, "C");
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
		
		function agregarLinea($ancho,$alto,$x,$y){
			$r1  = $this->w - $ancho;
			$r2  = $r1 + $x;
			$y1  = $this->h - $alto;
			$y2  = $y1 + $y;
			$this->Line($r1, $y1, $r2, $y1);
		}
	}
	
	$pdf = new PDF();
	$pdf -> AliasNbPages();
	$pdf -> AddPage();
	$numalet = new CNumeroaletra; 
	if($_GET[nolleva]==0){
		//POR UN CAJERO
		$ar = split(",",$_GET[empleados]);		
		$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS responsable FROM catalogoempleado 
		WHERE id=".$ar[0]."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$responsable = $f->responsable;
		
		$numalet->setNumero($ar[1]);
		
		$pdf -> Image('../logo.jpg',40,22,20,20);
		$pdf -> ciudad("",170,270);
		$pdf -> titulo("PAQUETERIA Y MENSAJERIA EN MOVIMIENTO",25,60);
		$pdf -> addLeyenda2("FECHA: ".$_GET[fecha]."",35,125);
		$pdf -> agregarLinea(70,258,19,10);
		$pdf -> addLeyenda2("VALE PROVISIONAL DE CAJA: $ ".number_format($ar[1],2)."",50,40);
		$pdf -> agregarLinea(116,243,65,10);
		$pdf -> addLeyenda2($numalet->letra(),60,40);
		$pdf -> agregarLinea(169,233,118,10);
		$pdf -> addLeyenda2("CONCEPTO:",70,40);
		$pdf -> addLeyenda2("DEBIDO A QUE EXISTIERON DIFERENCIAS EN EL CIERRE DE CAJA,",75,40);
		$pdf -> agregarLinea(169,218,118,10);
		$pdf -> addLeyenda2("SE DESCONTARA A:",80,40);
		$pdf -> agregarLinea(169,213,118,10);
		$pdf -> addLeyenda2($responsable,85,40);
		$pdf -> agregarLinea(169,208,118,10);
		$pdf -> addLeyenda2("DICHA CANTIDAD POR MEDIO DE NOMINA.",90,40);
		$pdf -> agregarLinea(169,203,118,10);
		$pdf -> addLeyenda2("AUTORIZADO POR: ".$gerente."",100,40);
		$pdf -> agregarLinea(135,193,84,10);
		$pdf -> addLeyenda2("FIRMA:",105,70);
		$pdf -> agregarLinea(126,188,75,10);
		$pdf -> addLeyenda2("RESPONSABLE: ".$responsable."",110,40);
		$pdf -> agregarLinea(140,183,89,10);
		$pdf -> addLeyenda2("FIRMA:",115,70);
		$pdf -> agregarLinea(126,178,75,10);
	
	}else{
	
		$arr = split(":",$_GET[empleados]);		
		$ar = "";
		$contador = 0;
		$data = $arr;
		
		for($i=0; $i < count($arr); $i++){			
			$ar = split(",",$arr[$i]);
			for($k=0; $k<count($ar)/2; $k++){					
					$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS responsable FROM catalogoempleado 
					WHERE id=".$ar[0]."";
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					$responsable = cambio_texto($f->responsable);
					
					$numalet->setNumero($ar[1]);
					
					$pdf -> Image('../logo.jpg',40,22,20,20);
					$pdf -> ciudad("",170,270);
					$pdf -> titulo("PAQUETERIA Y MENSAJERIA EN MOVIMIENTO",25,60);
					$pdf -> addLeyenda2("FECHA: ".$_GET[fecha]."",35,125);
					$pdf -> agregarLinea(70,258,19,10);
					$pdf -> addLeyenda2("VALE PROVISIONAL DE CAJA: $ ".number_format($ar[1],2)."",50,40);
					$pdf -> agregarLinea(116,243,65,10);
					$pdf -> addLeyenda2($numalet->letra(),60,40);
					$pdf -> agregarLinea(169,233,118,10);
					$pdf -> addLeyenda2("CONCEPTO:",70,40);
					$pdf -> addLeyenda2("DEBIDO A QUE EXISTIERON DIFERENCIAS EN EL CIERRE DE CAJA,",75,40);
					$pdf -> agregarLinea(169,218,118,10);
					$pdf -> addLeyenda2("SE DESCONTARA A:",80,40);
					$pdf -> agregarLinea(169,213,118,10);
					$pdf -> addLeyenda2($responsable,85,40);
					$pdf -> agregarLinea(169,208,118,10);
					$pdf -> addLeyenda2("DICHA CANTIDAD POR MEDIO DE NOMINA.",90,40);
					$pdf -> agregarLinea(169,203,118,10);
					$pdf -> addLeyenda2("AUTORIZADO POR: ".$gerente."",100,40);
					$pdf -> agregarLinea(135,193,84,10);
					$pdf -> addLeyenda2("FIRMA:",105,70);
					$pdf -> agregarLinea(126,188,75,10);
					$pdf -> addLeyenda2("RESPONSABLE: ".$responsable."",110,40);
					$pdf -> agregarLinea(140,183,89,10);
					$pdf -> addLeyenda2("FIRMA:",115,70);
					$pdf -> agregarLinea(126,178,75,10);
					
				if($contador<count($data)-1){
					$pdf -> addPage();
				}				
				$contador++;
			}
		}
	}	
	
	$pdf -> Output();
?>