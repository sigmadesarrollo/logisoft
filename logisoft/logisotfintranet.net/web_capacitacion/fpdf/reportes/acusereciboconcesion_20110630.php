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

			$mes = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

			//Logo
			$this->Image('../logo.jpg',18,8,33);
			$this->Ln(10);
			//Arial bold 15		
			$this->SetFont('Arial','B',12);		
			//Movernos a la derecha		
			$this->Cell(80);		
			//Titulo				
			$this->Ln(5);
			$this->SetFont('Arial','B',12);
			$this->Cell(200,10,'ENTREGAS PUNTUALES S DE RL DE CV',0,0,'C');			
			$this->Ln(10);
			$this->SetFont('Arial','B',9);
			$this->Cell(176,10,'FOLIO  '.$_GET[folio],0,0,'R');
			$this->Ln(8);
			$this->Cell(150,10,'RESUMEN DE INGRESOS',0,0,'L');
			$this->Ln(4);
			$this->Cell(176,10,'MAZATLAN, SIN., A: '.date(d).' de '.$mes[date(n)].' de '.date(Y),0,0,'R');
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
	
	function num2letras($num, $fem = false, $dec = true) { 
	   $matuni[2]  = "dos"; 
	   $matuni[3]  = "tres"; 
	   $matuni[4]  = "cuatro"; 
	   $matuni[5]  = "cinco"; 
	   $matuni[6]  = "seis"; 
	   $matuni[7]  = "siete"; 
	   $matuni[8]  = "ocho"; 
	   $matuni[9]  = "nueve"; 
	   $matuni[10] = "diez"; 
	   $matuni[11] = "once"; 
	   $matuni[12] = "doce"; 
	   $matuni[13] = "trece"; 
	   $matuni[14] = "catorce"; 
	   $matuni[15] = "quince"; 
	   $matuni[16] = "dieciseis"; 
	   $matuni[17] = "diecisiete"; 
	   $matuni[18] = "dieciocho"; 
	   $matuni[19] = "diecinueve"; 
	   $matuni[20] = "veinte"; 
	   $matunisub[2] = "dos"; 
	   $matunisub[3] = "tres"; 
	   $matunisub[4] = "cuatro"; 
	   $matunisub[5] = "quin"; 
	   $matunisub[6] = "seis"; 
	   $matunisub[7] = "sete"; 
	   $matunisub[8] = "ocho"; 
	   $matunisub[9] = "nove"; 

	   $matdec[2] = "veint"; 
	   $matdec[3] = "treinta"; 
	   $matdec[4] = "cuarenta"; 
	   $matdec[5] = "cincuenta"; 
	   $matdec[6] = "sesenta"; 
	   $matdec[7] = "setenta"; 
	   $matdec[8] = "ochenta"; 
	   $matdec[9] = "noventa"; 
	   $matsub[3]  = 'mill'; 
	   $matsub[5]  = 'bill'; 
	   $matsub[7]  = 'mill'; 
	   $matsub[9]  = 'trill'; 
	   $matsub[11] = 'mill'; 
	   $matsub[13] = 'bill'; 
	   $matsub[15] = 'mill'; 
	   $matmil[4]  = 'millones'; 
	   $matmil[6]  = 'billones'; 
	   $matmil[7]  = 'de billones'; 
	   $matmil[8]  = 'millones de billones'; 
	   $matmil[10] = 'trillones'; 
	   $matmil[11] = 'de trillones'; 
	   $matmil[12] = 'millones de trillones'; 
	   $matmil[13] = 'de trillones'; 
	   $matmil[14] = 'billones de trillones'; 
	   $matmil[15] = 'de billones de trillones'; 
	   $matmil[16] = 'millones de billones de trillones'; 
   
	   //Zi hack
	   $float=explode('.',$num);
	   $num=$float[0];

	   $num = trim((string)@$num); 
	   if ($num[0] == '-') { 
		  $neg = 'menos '; 
		  $num = substr($num, 1); 
	   }else 
		  $neg = ''; 
	   while ($num[0] == '0') $num = substr($num, 1); 
	   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
	   $zeros = true; 
	   $punt = false; 
	   $ent = ''; 
	   $fra = ''; 
	   for ($c = 0; $c < strlen($num); $c++) { 
		  $n = $num[$c]; 
		  if (! (strpos(".,'''", $n) === false)) { 
			 if ($punt) break; 
			 else{ 
				$punt = true; 
				continue; 
			 } 
		  }elseif (! (strpos('0123456789', $n) === false)) { 
			 if ($punt) { 
				if ($n != '0') $zeros = false; 
				$fra .= $n; 
			 }else 
				$ent .= $n; 
		  }else 
			 break; 
	   } 
	   $ent = '     ' . $ent; 
	   if ($dec and $fra and ! $zeros) { 
		  $fin = ' coma'; 
		  for ($n = 0; $n < strlen($fra); $n++) { 
			 if (($s = $fra[$n]) == '0') 
				$fin .= ' cero'; 
			 elseif ($s == '1') 
				$fin .= $fem ? ' una' : ' un'; 
			 else 
				$fin .= ' ' . $matuni[$s]; 
		  } 
	   }else 
		  $fin = ''; 
	   if ((int)$ent === 0) return 'Cero ' . $fin; 
	   $tex = ''; 
	   $sub = 0; 
	   $mils = 0; 
	   $neutro = false; 
	   while ( ($num = substr($ent, -3)) != '   ') { 
		  $ent = substr($ent, 0, -3); 
		  if (++$sub < 3 and $fem) { 
			 $matuni[1] = 'una'; 
			 $subcent = 'as'; 
		  }else{ 
			 $matuni[1] = $neutro ? 'un' : 'uno'; 
			 $subcent = 'os'; 
		  } 
		  $t = ''; 
		  $n2 = substr($num, 1); 
		  if ($n2 == '00') { 
		  }elseif ($n2 < 21) 
			 $t = ' ' . $matuni[(int)$n2]; 
		  elseif ($n2 < 30) { 
			 $n3 = $num[2]; 
			 if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
			 $n2 = $num[1]; 
			 $t = ' ' . $matdec[$n2] . $t; 
		  }else{ 
			 $n3 = $num[2]; 
			 if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
			 $n2 = $num[1]; 
			 $t = ' ' . $matdec[$n2] . $t; 
		  } 
		  $n = $num[0]; 
		  if ($n == 1) { 
			 $t = ' ciento' . $t; 
		  }elseif ($n == 5){ 
			 $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
		  }elseif ($n != 0){ 
			 $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
		  } 
		  if ($sub == 1) { 
		  }elseif (! isset($matsub[$sub])) { 
			 if ($num == 1) { 
				$t = ' mil'; 
			 }elseif ($num > 1){ 
				$t .= ' mil'; 
			 } 
		  }elseif ($num == 1) { 
			 $t .= ' ' . $matsub[$sub] . '?n'; 
		  }elseif ($num > 1){ 
			 $t .= ' ' . $matsub[$sub] . 'ones'; 
		  }   
		  if ($num == '000') $mils ++; 
		  elseif ($mils != 0) { 
			 if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
			 $mils = 0; 
		  } 
		  $neutro = true; 
		  $tex = $t . $tex; 
	   } 
	   $tex = $neg . substr($tex, 1) . $fin; 
	   //Zi hack --> return ucfirst($tex);
	   $end_num=' '.ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
	   return $end_num; 
	} 
	
	$l = Conectarse("webpmm");

	$s = "SET lc_time_names = 'es_MX'";
	mysql_query($s,$l) or die("error ".mysql_error($l)."--".$s);

	$s="SELECT mc.folio,cs.nconcesionario,cs.descripcion AS oficina,
	CONCAT(IF(DAY(mc.fechainicio)=0,1,DAY(mc.fechainicio)),' AL ',DAY(mc.fechafin),' DE ',
	CASE WHEN MONTH(mc.fechafin) = 1 THEN 'ENERO'
	WHEN MONTH(mc.fechafin) = 2 THEN 'FEBRERO'
	WHEN MONTH(mc.fechafin) = 3 THEN 'MARZO'
	WHEN MONTH(mc.fechafin) = 4 THEN 'ABRIL'
	WHEN MONTH(mc.fechafin) = 5 THEN 'MAYO'
	WHEN MONTH(mc.fechafin) = 6 THEN 'JUNIO'
	WHEN MONTH(mc.fechafin) = 7 THEN 'JULIO'
	WHEN MONTH(mc.fechafin) = 8 THEN 'AGOSTO'
	WHEN MONTH(mc.fechafin) = 9 THEN 'SEPTIEMBRE'
	WHEN MONTH(mc.fechafin) = 10 THEN 'OCTUBRE'
	WHEN MONTH(mc.fechafin) = 11 THEN 'NOVIEMBRE'
	WHEN MONTH(mc.fechafin) = 12 THEN 'DICICIEMBRE'
	END ,' DEL ',YEAR(mc.fechafin))fecha2,
	SUM(IF(rcd.tipo='V' AND rcd.condicion='PAGADA-CONTADO',rcd.totalgral,0))pagcont,
	SUM(IF(rcd.tipo='V' AND rcd.condicion='PAGADA-CREDITO',rcd.totalgral,0))pagcred,
	SUM(IF(rcd.tipo='R' AND rcd.condicion='POR COBRAR-CONTADO',rcd.totalgral,0))cobcont,
	SUM(IF(rcd.tipo='R' AND rcd.condicion='POR COBRAR-CREDITO',rcd.totalgral,0))cobcred,
	ROUND(SUM(rcd.totalgral),2)total,ROUND(SUM(rcd.totalcom),2)importe,
	ROUND(SUM(rcd.totalgral)-SUM(rcd.totalcom),2)liquidar
	FROM moduloconcesiones mc
	INNER JOIN reporte_concesiondetalle rcd ON mc.folio=rcd.folio AND mc.sucursal=rcd.sucursal
	INNER JOIN catalogosucursal cs ON mc.sucursal=cs.id
	WHERE mc.folio='$_GET[folio]' AND mc.sucursal=$_GET[sucursal] ";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$pdf = new pdf();
	$pdf->AliasNbPages();
	$pdf -> AddPage();
	$pdf -> Ln(10);
	$pdf -> SetFont("Arial","",8);
	
	
	$contenido = 'CONCESIONARIO: '.$f->nconcesionario.'
OFICINA: '.$f->oficina.'
ADJUNTO A LA PRESENTE SIRVASE ENCONTRAR LIQUIDACION CORRESPONDIENTE DE LA FECHA '.$f->fecha2.'


CON LOS SIGUIENTES IMPORTES A LIQUIDAR
VENTA DE FLETES ENVIADOS PAGADOS CONTADO                                                                                                          $ '.number_format($f->pagcont,2).'	
VENTA DE FLETES ENVIADOS PAGADOS CREDITO                                                                                                               $ '.number_format($f->pagcred,2).'
RECIBIDO DE FLETES RECIBIDOS POR COBRAR CONTADO                                                                                           $ '.number_format($f->cobcont,2).'
RECIBIDO DE FLETES RECIBIDOS POR COBRAR CREDITO                                                                                                  $ '.number_format($f->cobcred,2).'
';
$contenido2 = 'TOTAL A PAGAR                                                                                                                                                                    $ '.number_format($f->total,2).'
	
';	
$contenido3 = 'EL IMPORTE DE SUS COMISIONES ES DE: $ '.number_format($f->importe,2).' '.num2letras($f->importe).'

EL CUAL APLICARA DE LOS IMPORTES A LIQUIDAR Y ANEXARA FACTURA CORRESPONDIENTE A ESTE DOCUMENTO.
	
SALDO A LIQUIDAR $ '.number_format($f->liquidar,2).'
	
DICHO IMPORTE SERA CUBIERTO POR DEPOSITO EN CADA PLAZA A LA CUENTA ASIGNADA A NOMBRE DE ENTREGAS PUNTUALES S DE RL DE CV., CONVENIO CIE 941492 DE BANCOMER REFERENCIA 3608.
	
DEBO Y PAGARE INCONDICIONALMENTE EN ESTA PLAZA A LA ORDEN DE ENTREGAS PUNTUALES S DE RL DE CV, LA CANTIDAD DE $ '.number_format($f->liquidar,2).' A MAS TARDAR 15 DIAS DESPUES DE EMITIDO EL PRESENTE DOCUMENTO. ESTE PAGARE ES MERCANTIL Y ESTA REGIDO POR LA LEY GENERAL DE TITULOS Y OPERACIONES DE CREDITO EN SU ARTICULO 173 EN SU PARTE FINAL Y ARTICULOS CORRELATIVOS POR NO SER PAGARE DOMICILIADO.
DE NO HACER EL PAGO QUE ESTE PAGARE EXPRESA A SU VENCIMIENTO, CAUSARA INTERES MORATORIOS DEL C.P.P. MAS 50% DEL MISMO.,	MAS LOS GASTOS QUE POR ELLO SE ORIGINEN.
	';
	
	$pdf -> MultiCell(177,4,$contenido,0,'J');
	$pdf -> SetFont("Arial","B",8);
	$pdf -> MultiCell(177,4,$contenido2,0,'J');
	$pdf -> SetFont("Arial","",8);
	$pdf -> MultiCell(177,4,$contenido3,0,'J');
	$pdf -> SetFont("Arial","",8);
	$pdf -> Ln(25);
	$pdf -> SetFont('Arial',"",9);
	$pdf -> Cell(176,10,'_____________________________________',0,0,'C');
	$pdf -> Ln(4);
	$pdf -> Cell(176,10,'ACEPTO DE CONFORMIDAD',0,0,'C');
	
	$pdf -> Output();



?>