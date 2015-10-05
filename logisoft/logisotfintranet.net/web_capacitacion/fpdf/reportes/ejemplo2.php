<?	require_once('../fpdf.php');
	
	class pdf extends FPDF{
		function SetWidths($w){		
			$this->widths=$w;
		}
		
		function SetAligns($a){
			$this->aligns=$a;
		}
		
		function addDetalle(){
			$l = mysql_connect("201.155.192.116","liacrouly","alejandro");
			mysql_select_db("nomina", $l);
			
			$s = "SELECT a.id_semestre, UCASE(m.nombre) AS materia, '2001-2001' AS ciclo, ca.calificacion, 'saddsasd' AS observaciones
			FROM asig_materias a
			INNER JOIN materias m ON a.id_materia = m.id_materia
			INNER JOIN semestres s ON a.id_semestre = s.id_semestre
			INNER JOIN det_calificaciones ca ON m.id_materia = ca.idmateria
			ORDER BY a.id_semestre";
			$r = mysql_query($s,$l) or die($s);
				$arr = array();
				if(mysql_num_rows($r)>0){
					while($f = mysql_fetch_array($r)){
						$arr[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4]);
					}
				}
		
			$titulos = array('MATERIAS','CICLO','CALIFICACION','OBSERVACIONES');
			$medidas = array(70,20,20,30);
			$this->SetFont('Arial','B',7);
			for($i=0;$i<count($titulos);$i++){
				$this->Cell($medidas[$i],7,$titulos[$i],1,0,'C');
			}
			$this->Ln();
			$this->SetWidths($medidas);			
			$this->Row($arr);
			
			
			/*$d = 0;
			$data = $arr;
			foreach($arr as $row){
				$this->SetFont('Arial','B',6);
				if($row[0]!=$d){
					switch($row[0]){
						case 1:
							$semestre = 'PRIMER SEMESTRE';
						break;
						case 2:
							$semestre = 'SEGUNDO SEMESTRE';
						break;
						case 3:
							$semestre = 'TERCER SEMESTRE';
						break;
						case 4:
							$semestre = 'CUARTO SEMESTRE';
						break;
						case 5:
							$semestre = 'QUINTO SEMESTRE';
						break;
						case 6:
							$semestre = 'SEXTO SEMESTRE';
						break;
						case 7:
							$semestre = 'SEPTIMO SEMESTRE';
						break;
						case 8:
							$semestre = 'OCTAVO SEMESTRE';
						break;
						case 9:
							$semestre = 'NOVENO SEMESTRE';
						break;
						case 10:
							$semestre = 'DECIMO SEMESTRE';
						break;
					}
					$this->MultiCell(140,3,$semestre,1,'C');
					$d = $row[0];
				}
					$this->SetFont('Arial','',6);*/
					/*$this->Cell($medidas[0],3, utf8_decode($row[1]),'LR',0,'L');
					$this->Cell($medidas[1],3, utf8_decode($row[2]),'LR',0,'C');
					$this->Cell($medidas[2],3, utf8_decode($row[3]),'LR',0,'C');*/
					//$this->Cell($medidas[3],3, utf8_decode($row[4]),'LR',0,'L');
					/*$this->MultiCell($medidas[0],3,utf8_decode($row[1]),1,'L');
					$this->MultiCell($medidas[1],3,utf8_decode($row[2]),1,'C');
					$this->MultiCell($medidas[2],3,utf8_decode($row[3]),1,'C');
					$this->MultiCell($medidas[3],3,utf8_decode($row[4]),1,'L');
					$this->SetXY($x+$w,$y);*/
					//$this->Ln();
				/*if(ereg("SEMESTRE",$data[$i])){
					if($d==0){
						$this->MultiCell(140,5,utf8_encode($data[$i]),1,'C');
						$d = 1;
					}else if($d!=$data[$i]){
						$this->MultiCell(140,5,utf8_encode($data[$i]),1,'C');
					}
					
				}else{
					$this->Rect($x,$y,$w,$h);
					if(!is_numeric($data[$i])){
						$this->MultiCell($w,5,utf8_encode($data[$i]),0,'L');
					}else{
						if(!ereg("-",$data[$i]))
							$this->MultiCell($w,5,$data[$i],0,'C');
						else
							$this->MultiCell($w,5,$data[$i],0,'C');
					}
				}*/
			//}
			
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
			$d = 0;
			for($i=0;$i<count($data);$i++){
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				
				if($data[$i]!=$d){
					switch($data[$i]){
						case 1:
							$semestre = 'PRIMER SEMESTRE';
						break;
						case 2:
							$semestre = 'SEGUNDO SEMESTRE';
						break;
						case 3:
							$semestre = 'TERCER SEMESTRE';
						break;
						case 4:
							$semestre = 'CUARTO SEMESTRE';
						break;
						case 5:
							$semestre = 'QUINTO SEMESTRE';
						break;
						case 6:
							$semestre = 'SEXTO SEMESTRE';
						break;
						case 7:
							$semestre = 'SEPTIMO SEMESTRE';
						break;
						case 8:
							$semestre = 'OCTAVO SEMESTRE';
						break;
						case 9:
							$semestre = 'NOVENO SEMESTRE';
						break;
						case 10:
							$semestre = 'DECIMO SEMESTRE';
						break;
					}
					$this->MultiCell(140,3,$semestre,1,'C');
					$d = $data[$i];
					
				}
					$this->Rect($x,$y,$w,$h);
					/*$this->MultiCell($w,3,utf8_decode($data[1]),1,'L');
					$this->MultiCell($w,3,utf8_decode($data[2]),1,'C');
					$this->MultiCell($w,3,utf8_decode($data[3]),1,'C');
					$this->MultiCell($w,3,utf8_decode($data[4]),1,'L');*/
					
					$this->Rect($x,$y,$w,$h);
					if(!is_numeric($data[$i])){
						$this->MultiCell($w,5,utf8_encode($data[$i]),0,'L');
					}else{
						if(!ereg("-",$data[$i]))
							$this->MultiCell($w,5,$data[$i],0,'C');
						else
							$this->MultiCell($w,5,$data[$i],0,'C');
					}
				
				/*$dato = "-".$data[$i]."-";
				if(!is_numeric($data[$i])){
					$this->MultiCell($w,5,utf8_encode($data[$i]),0,$a);
				}else{
					if(preg_match($dato,".")){
						$this->MultiCell($w,5, "$".number_format($data[$i],2,".",","),0,'R');
					}else{
						$this->MultiCell($w,5,$data[$i],0,'R');
					}
				}*/
				
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
		}
		
		function CheckPageBreak($h){
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
	
	$pdf = new pdf();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->adddetalle();
	$pdf->Output();	
?>