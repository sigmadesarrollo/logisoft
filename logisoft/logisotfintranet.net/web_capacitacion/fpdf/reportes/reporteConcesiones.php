<?	require_once('../fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT guia,DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fechaguia,flete,descuento,fleteneto,
	comision,recoleccion,comisionrad,entrega,comisionead,total,condicion,estado FROM reporte_concesiones";
	$r = mysql_query($s,$l) or die($s);
	$arr = array();
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$arr[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6],'7'=>$f[7],'8'=>$f[8],'9'=>$f[9],'10'=>$f[10],'11'=>$f[11],'12'=>$f[12],'13'=>$f[13],'14'=>$f[14],'15'=>$f[15]);
		}
	}
	
	class pdf extends FPDF{
		var $widths;
		var $aligns;
		
		function Header(){
			//Logo
			$this->Image('../logo.jpg',10,8,33);
			$this->Ln(20);
			//Arial bold 15		
			$this->SetFont('Arial','B',15);		
			//Movernos a la derecha		
			$this->Cell(80);		
			//Titulo		
			$this->Cell(100,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');		
			//Salto de linea
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(70,10,'REPORTE DE FRANQUICIAS O CONCESIONES                                                   FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'PERIODO DEL: '.$_GET[fechainicio].' AL '.$_GET[fechafin].'',0,0,'L');			

			$this->Ln(10);
		
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


	$pdf = new pdf('L','mm','Legal');
	$pdf->AliasNbPages();
	$header = array('GUIA','FECHA','FLETE','DESCUENTO','FLETE NETO','COMISION','RECOLECCION','COMISION RAD','ENTREGA','COMSION EAD','TOTAL','CONDICION','STATUS');
	$w = array(25,15,23,23,23,23,23,23,23,23,23,40,40);
	//Carga de datos
	$pdf->SetFont('Arial','B',7);	
	$pdf->AddPage();
	
	$pdf->SetWidths($w);
	
	for($i=0;$i<count($header);$i++){	
		$pdf->Cell($w[$i],5,$header[$i],1,0,'C');						
	}
	$pdf->Ln();
	$pdf->SetFont('Arial','',6);
	for($i=0;$i<count($arr);$i++){
		$pdf->Row($arr[$i]);
	}
	
	$contador = 0;
	
	$pdf->Output();
?>