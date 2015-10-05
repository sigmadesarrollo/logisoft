<?
	require_once("../../Conectar.php");	
	require_once("../fpdf.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado 
	FROM catalogoempleado WHERE id = ".$_GET[empleado];
	$r = mysql_query($s,$l) or die($s);
	$e = mysql_fetch_object($r);
	
	$s = "SELECT e.cantidad,cd.descripcion AS des,e.contenido,e.pesototal,e.volumen
	FROM evaluacionmercanciadetalle e
	INNER JOIN catalogodescripcion cd ON e.descripcion = cd.id
	WHERE e.evaluacion = ".$_GET[evaluacion]." AND e.sucursal = ".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s);
	$data = array();
		while($f = mysql_fetch_array($r)){
			$f[0] = cambio_texto($f[0]);
			$tguias = $tguias + $f[1];
			$tvalor = $tvalor + $f[2];
			$tseguro = $tseguro + $f[3];
			$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4]);
		}
	
	class pdf extends FPDF{
		var $widths;
		var $aligns;
		
		function Header(){
			require_once('../../Conectar.php');
			$l = Conectarse('webpmm');
			
			$s = "SELECT e.folio, e.fechaevaluacion, e.estado, e.guiaempresarial,
			IF(e.recoleccion=0,'',e.recoleccion) AS recoleccion, e.destino, e.sucursaldestino,
			cs.descripcion AS sucursal, IF(e.entrega=1,'EAD','OCURRE') AS entrega, 
			SUBSTRING(e.fecha,12) AS hora
			FROM evaluacionmercancia e
			INNER JOIN catalogosucursal cs ON e.sucursal = cs.id
			WHERE e.folio = ".$_GET[evaluacion]." AND e.sucursal = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s);
			$ff = mysql_fetch_object($r);
			
			switch (substr($ff->fechaevaluacion,5,strlen($ff->fechaevaluacion)-8)){
				case "01":
					$mes = "ENERO ";
				break;
				
				case "02":
					$mes = "FEBRERO ";
				break;
				
				case "03":
					$mes = "MARZO ";
				break;
				
				case "04":
					$mes = "ABRIL ";
				break;
				
				case "05":
					$mes = "MAYO ";
				break;
				
				case "06":
					$mes = "JUNIO ";
				break;
				
				case "07":
					$mes = "JULIO ";
				break;
				
				case "08":
					$mes = "AGOSTO ";
				break;
				
				case "09":
					$mes = "SEPTIEMBRE ";
				break;
				
				case "10":
					$mes = "OCTUBRE ";
				break;
				
				case "11":
					$mes = "NOVIEMBRE ";
				break;
				
				case "12":
					$mes = "DICIEMBRE ";
				break;
			
			} 
			
			//Logo				
			$this->Image('../logo.jpg',10,8,33);
			$this->Ln(20);
			//Arial bold 15		
			$this->SetFont('Arial','B',15);		
			//Movernos a la derecha		
			$this->Cell(80);		
			//Titulo		
			$this->Cell(30,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');		
			$this->Ln(10);
			$this->Cell(80);
			$this->SetFont('Arial','B',12);
			$this->Cell(30,10,'ORDEN DE EMBARQUE',0,0,'C');
			
			$this->addLeyenda2("FOLIO: ".$_GET[evaluacion]."",50,10);
			$this->addLeyenda2("SUCURSAL: ".$ff->sucursal."",53,10);
			$this->addLeyenda2("FECHA Y HORA EVALUACION: ".substr($ff->fechaevaluacion,8)." DE ".$mes.substr($ff->fechaevaluacion,0,strlen($ff->fechaevaluacion)-6)." ".$ff->hora.((substr($ff->hora,0,strlen($ff->hora)-5)>="12" && substr($ff->hora,3,strlen($ff->hora)-3)>"00")?" PM": " AM")."",56,10);
			
			$this->addLeyenda2("RECOLECCION: ".$ff->recoleccion."",50,100);
			$this->addLeyenda2("GUIA EMPRESARIAL: ".$ff->guiaempresarial."",53,100);
			$this->addLeyenda2("DESTINO: ".$ff->sucursaldestino."",60,10);
			$this->addLeyenda2("TIPO DE ENTREGA: ".$ff->entrega."",60,100);
			$this->Ln(5);
			
		}
		
		function addLeyenda2($ref,$posicion,$posicion2){
			$this->SetFont( "Arial", "B", 7);
			$length = $this->GetStringWidth($ref);
			$r1  = $posicion2;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
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
				$dato = "-".$data[$i]."-";
				if(!is_numeric($data[$i])){
					$this->MultiCell($w,5,utf8_encode($data[$i]),0,$a);
				}else{
					if(preg_match($dato,".")){
						$this->MultiCell($w,5, "$".number_format($data[$i],2,".",","),0,'R');
					}else{
						$this->MultiCell($w,5,$data[$i],0,'R');
					}
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
		
		function addFirma($mode,$p1,$p2,$titulo,$linea){
			$r1  = $p1;
			$r2  = $r1 + $linea;
			$y1  = $p2;
			$y2  = $y1+10;
			$mid = $y1 + (($y2-$y1) / 2);
			//$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
			$this->Line($r1, $mid, $r2, $mid);
			$this->SetXY($r1 + ($r2-$r1)/2 -3 , ($y1 + 1) -5 );
			$this->SetFont("Arial", "", 7);
			$this->Cell(10,5, utf8_decode($titulo), 0, 0, "C");
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
			$this->SetFont( "Arial", "", 7);
			$this->Cell(10,5, utf8_decode($mode), 0, 0, "C");
		}
	}
	
	$pdf = new pdf();
	$pdf->AliasNbPages();
	$titulos = array('CANTIDAD','DESCRIPCION','CONTENIDO','PESO KG','PESO VOL');
	$medidas = array(20,60,60,18,18);
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',7);
	
	$pdf->SetWidths($medidas);
	
	$pdf->Titulos($titulos,$medidas);
	
	$pdf->SetFont('Arial','',7);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
	$pdf->addLeyenda2("EVALUADOR: ".$e->empleado."",240,10);
	$pdf->addFirma("FIRMA CONFORMIDAD",80,260,"",60);
	$pdf->Output();	

?>