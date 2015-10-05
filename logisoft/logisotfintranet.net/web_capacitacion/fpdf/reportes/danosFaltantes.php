<?	require_once('../fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecha, rep.recepcion, su.prefijo AS sucursal,
	rep.guia, t.origen, t.destino, IF(rep.dano = 1,'DAÑO',IF(rep.faltante = 1,'FALTANTE',
	IF(rep.dano = 1 AND rep.faltante = 1,'DAÑO,FALT',''))) AS tipo,
	CONCAT_WS(' ',e.nombre,e.apellidopaterno,e.apellidomaterno) AS recibio,
	r.unidad, cr.descripcion AS ruta
	FROM reportedanosfaltante rep
	INNER JOIN recepcionmercancia r ON rep.recepcion = r.folio AND rep.sucursal = r.idsucursal
	INNER JOIN catalogoempleado e ON rep.empleado1 = e.id
	INNER JOIN catalogoruta cr ON r.ruta = cr.id
	INNER JOIN (SELECT gv.id AS guia, sd.prefijo AS destino, so.prefijo AS origen
	FROM guiasventanilla AS gv
	INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
	INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
	UNION
	SELECT ge.id AS guia, sd.prefijo AS destino, so.prefijo AS origen
	FROM guiasempresariales AS ge
	INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
	INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id) t ON rep.guia=t.guia
	INNER JOIN catalogosucursal su ON r.idsucursal = su.id
	WHERE r.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
	".(($_GET[sucursal]!="todas")? " AND r.sucursal=".$_GET[sucursal]."" : "")."
	GROUP BY rep.guia";
	$r = mysql_query($s, $l) or die(mysql_error($l).$s);
	$data = array();
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$f[2] = utf8_encode($f[2]);			
			$f[4] = utf8_encode($f[4]);
			$f[5] = utf8_encode($f[5]);
			$f[7] = utf8_encode($f[7]);
			$f[8] = utf8_encode($f[8]);
			$f[9] = utf8_encode($f[9]);
			
			$s = "SELECT guia FROM sobrantes WHERE guia = '".$f[3]."'";
			$rr= mysql_query($s,$l) or die($s); $ff = mysql_fetch_array($rr);
			
			if($f[3]==$ff[1]){
				if($f[6]=="DAÑO,FALT"){
					$f[6] = utf8_encode($f[6]).",SOBR";
				}else{
					$f[6] = utf8_encode($f[6]).",SOBRANTE";
				}
			}else{
				$f[6] = utf8_encode($f[6]);
			}
			
			$f[3] = utf8_encode($f[3]);
			
			$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],
			'6'=>$f[6],'7'=>$f[7],'8'=>$f[8],'9'=>$f[9]);
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
			$this->Cell(70,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');			
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(70,10,'REPORTE: HISTORICO DAÑOS Y FALTANTES                           FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);			
			$this->Cell(70,10,'PERIODO DEL: '.$_GET[fechainicio].' AL '.$_GET[fechafin].'',0,0,'L');
			$this->Ln(10);
		}	
		
		function Titulos($titulos,$medidas){
			$this->SetFont('Arial','B',7);
			for($i=0;$i<count($titulos);$i++){	
				$this->Cell($medidas[$i],7,$titulos[$i],1,0,'C');						
			}
			$this->Ln();
		}
		
		function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
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
			$this->SetXY($r1 + ($r2-$r1)/2 -3 , ($y1 + 1) -5 );
			$this->SetFont("Arial", "B", 10);
			$this->Cell(10,5, utf8_decode($titulo), 0, 0, "C");
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
			$this->SetFont( "Arial", "", 10);
			$this->Cell(10,5, utf8_decode($mode), 0, 0, "C");
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
				if(!is_numeric(utf8_decode($data[$i]))){
					$this->MultiCell($w,5,utf8_decode($data[$i]),0,$a);
				}else{
					$this->MultiCell($w,5,utf8_decode($data[$i]),0,'R');
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
	
	$pdf = new pdf('L','mm','A4');
	$pdf->AliasNbPages();
	$titulos = array('FECHA','F. RECEPCION','SUCURSAL','GUIA','ORIGEN','DESTINO','INCIDENTE','RECIBIO','UNIDAD','RUTA');
	$medidas = array(15,20,15,22,12,12,30,80,20,55);
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',7);
	//Table with 20 rows and 4 columns
	
	$pdf->SetWidths($medidas);
	
	$pdf->Titulos($titulos,$medidas);
	
	$pdf->SetFont('Arial','',7);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
	$pdf->Output();
?>