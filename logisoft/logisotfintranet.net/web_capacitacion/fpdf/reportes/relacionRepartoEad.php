<?	require('../fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');	
	
	$s = "SELECT cu.numeroeconomico unidad, CONCAT_WS(' ',c1.nombre,c1.apellidopaterno,c1.apellidomaterno) AS conductor1
	FROM repartomercanciaead r
	INNER JOIN catalogoempleado c1 ON r.conductor1 = c1.id
	INNER JOIN catalogounidad cu ON r.unidad = cu.id
	WHERE r.folio = ".$_GET[folio]." AND r.sucursal = ".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s); $fr = mysql_fetch_object($r);
	
	$s = "(SELECT gv.id AS guia, CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
	CONCAT(d.calle,' #',d.numero,', ',d.colonia) AS direccion, '' AS recibe, 
	IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,'0') AS pagado, 
	IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,'0') AS cobrar,
	IF(gv.condicionpago = 1 AND (gv.tipoflete=0 OR gv.tipoflete=1),gv.total,'0') AS credito
	FROM repartomercanciaead r
	INNER JOIN repartomercanciadetalle dr ON r.folio = dr.idreparto AND r.sucursal = dr.sucursal
	INNER JOIN guiasventanilla gv ON dr.guia = gv.id
	INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
	INNER JOIN direccion d ON gv.iddirecciondestinatario = d.id
	WHERE r.folio = ".$_GET[folio]." AND r.sucursal = ".$_GET[sucursal].")
	UNION
	(SELECT ge.id AS guia, CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
	CONCAT(d.calle,' #',d.numero,', ',d.colonia) AS direccion, '' AS recibe, 
	IF(ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO',ge.total,'0') AS pagado, 
	IF(ge.tipoflete='POR COBRAR' AND ge.tipopago='CONTADO',ge.total,'0') AS cobrar,
	IF(ge.tipopago = 'CREDITO' AND (ge.tipoflete='PAGADA' OR ge.tipoflete='POR COBRAR'),ge.total,'0') AS credito
	FROM repartomercanciaead r
	INNER JOIN repartomercanciadetalle dr ON r.folio = dr.idreparto AND r.sucursal = dr.sucursal
	INNER JOIN guiasempresariales ge ON dr.guia = ge.id
	INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
	INNER JOIN direccion d ON ge.iddirecciondestinatario = d.id
	WHERE r.folio = ".$_GET[folio]." AND r.sucursal = ".$_GET[sucursal].")";
	$r = mysql_query($s,$l) or die($s);
	$data = array();
	$total = mysql_num_rows($r);
	$tpagado = 0; $tcobrar = 0; $tcredito = 0;
	if($total>0){
		while($f = mysql_fetch_array($r)){
			$f[0] = cambio_texto($f[0]);
			$f[1] = cambio_texto($f[1]);
			$f[2] = cambio_texto($f[2]);
			$tpagado = $tpagado + $f[4];
			$tcobrar = $tcobrar + $f[5];
			$tcredito = $tcredito + $f[6];
			$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6]);
		}
	}	
	
	$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	
	class PDF extends FPDF{			
		var $widths;
		var $aligns;
		function Header(){
			require_once('../../Conectar.php');
			$l = Conectarse('webpmm');
			
			$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);			
			
			$s = "SELECT DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecha,
			CONCAT_WS(' ',c1.nombre,c1.apellidopaterno,c1.apellidomaterno) AS conductor1,
			CONCAT_WS(' ',c2.nombre,c2.apellidopaterno,c2.apellidomaterno) AS conductor2, 
			u.numeroeconomico AS unidad
			FROM repartomercanciaead r
			INNER JOIN catalogoempleado c1 ON r.conductor1 = c1.id
			INNER JOIN catalogoempleado c2 ON r.conductor2 = c2.id
			INNER JOIN catalogounidad u ON r.unidad = u.id
			WHERE r.folio = ".$_GET[folio]." AND r.sucursal = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $ff = mysql_fetch_object($r);
			//Logo
//			$this->Image('../logo.jpg',10,8,33);
	//		$this->Ln(20);
			//Arial bold 15		
			$this->SetFont('Arial','B',10);
		
			//Movernos a la derecha		
			$this->Cell(80);
		
			//Titulo		
			$this->Cell(70,10,'ENTREGAS PUNTUALES S DE RL DE CV',0,0,'C');
		
			//Salto de linea
			$this->Ln(10);
			$this->SetFont('Arial','B',8);
			$this->Cell(70,10,'REPORTE: ENTREGA A DOMICILIO FOLIO '.$_GET[folio].'                                                   FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'SUCURSAL: '.utf8_decode($f->descripcion).'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'FECHA DEL DIA: '.$ff->fecha.'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'CONDUCTORES: '.$ff->conductor1.' y '.$ff->conductor2.'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'UNIDAD: '.$ff->unidad.'',0,0,'L');
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
		
		function addLeyenda($ref,$posicion,$posicion2){
			$this->SetFont( "Arial", "", 6);
			$length = $this->GetStringWidth($ref);
			$r1  = $posicion2;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function addLeyenda2($ref,$posicion,$posicion2){
			$this->SetFont( "Arial", "", 6);
			$length = $this->GetStringWidth($ref);
			$r1  = $posicion2;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
	}
	
	$pdf = new PDF('L','mm','A4');
	$pdf->AliasNbPages();	
	$titulos = array('GUIA','DESTINATARIO','DIRECCION','RECIBE','PAGADO','COBRAR','CREDITO');
	$medidas = array(23,90,80,25,20,20,20);
	
	//Carga de datos
	$pdf->SetFont('Arial','B',10);	
	$pdf->AddPage();	

	$pdf->SetWidths($medidas);
	$pdf->SetFont('Arial','B',6);	
	$pdf->Titulos($titulos,$medidas);
	$pdf->SetFont('Arial','B',6);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
	$pdf->addLeyenda("TOTAL GUIAS A REPARTIR: ".$total."",170,10);
	$pdf->addLeyenda("RECIBI:_______________________________",175,10);
	$pdf->addLeyenda("CHOFERES: ".$fr->conductor1."",180,10);
	$pdf->addLeyenda("UNIDAD: ".$fr->unidad."",185,10);
	$pdf->addLeyenda("GUIAS DEVUELTAS:_____",180,150);
	$pdf->addLeyenda("ENCARGADO DE ALMACEN:_______________________________",185,150);
	$pdf->addLeyenda2("TOTAL:",160,226);
	$pdf->addLeyenda2("$".number_format($tpagado,2,'.',','),160,236);
	$pdf->addLeyenda2("$".number_format($tcobrar,2,'.',','),160,256);
	$pdf->addLeyenda2("$".number_format($tcredito,2,'.',','),160,276);
	
	$pdf->Output();
?>