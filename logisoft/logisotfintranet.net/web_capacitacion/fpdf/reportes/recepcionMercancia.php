<?php
	require('../fpdf.php');
	//require('tablaMultiCell.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	
	$s = "SELECT d.guia, t.destinatario, t.descripcion, t.totalpeso,
	t.pagado, t.cobrar, t.emb,
	IF(rd.dano = 1,'DAÑO',IF(rd.faltante = 1,'FALTANTE','  ')) AS incidente
	FROM recepcionmercancia r
	INNER JOIN recepcionmercanciadetalle d ON r.folio = d.recepcion AND r.idsucursal = d.sucursal
	LEFT JOIN reportedanosfaltante rd ON d.guia = rd.guia AND r.idsucursal = rd.sucursal
	INNER JOIN (SELECT CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
	gv.id AS guia, CONCAT(gd.cantidad,' ',gd.descripcion,'/',gd.contenido) AS descripcion,
	gv.totalpeso, IF(gv.tipoflete=0,gv.total,0) AS pagado,
	IF(gv.tipoflete=1,gv.total,0) AS cobrar,
	IF(gv.ocurre=0,'EAD','OCU') AS emb, gv.idsucursaldestino FROM guiasventanilla gv
	INNER JOIN catalogocliente ce ON gv.iddestinatario = ce.id
	INNER JOIN guiaventanilla_detalle gd ON gv.id = gd.idguia
	RIGHT JOIN recepcionmercanciadetalle d ON d.guia = gv.id
	WHERE d.recepcion = ".$_GET[folio]." AND d.sucursal = ".$_GET[sucursal]."
	UNION
	SELECT CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
	ge.id AS guia, CONCAT(gde.cantidad,' ',gde.descripcion,'/',gde.contenido) AS descripcion,
	ge.totalpeso, IF(ge.tipoflete='PAGADO',ge.total,0) AS pagado,
	IF(ge.tipoflete='POR COBRAR',ge.total,0) AS cobrar,
	IF(ge.ocurre=0,'EAD','OCU') AS emb, ge.idsucursaldestino FROM guiasempresariales ge
	INNER JOIN catalogocliente ce ON ge.iddestinatario = ce.id
	INNER JOIN guiasempresariales_detalle gde ON ge.id = gde.id
	RIGHT JOIN recepcionmercanciadetalle d ON d.guia = ge.id
	WHERE d.recepcion = ".$_GET[folio]." AND d.sucursal = ".$_GET[sucursal].") AS t ON d.guia = t.guia
	WHERE r.folio = ".$_GET[folio]." AND r.idsucursal = ".$_GET[sucursal]."
	GROUP BY d.guia
	HAVING d.guia IS NOT NULL";
	$r = mysql_query($s,$l) or die($s);
	$data = array();
	$tpagado = 0;
	$total = mysql_num_rows($r);
	if($total>0){
		while($f = mysql_fetch_array($r)){
			$f[0] = $f[0];
			$f[1] = $f[1];
			$f[2] = $f[2];
			$f[7] = $f[7];
			$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6],'7'=>$f[7]);
		}
	}
	
	class pdf extends FPDF{
		var $widths;
		var $aligns;
		var $recibio;
		var $origen;
		
		function Header(){
			require_once("../../Conectar.php");
			$l = Conectarse("webpmm");
			$s = "SELECT prefijo,descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
			$this->recibio = $f->prefijo;
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
			$this->Cell(70,10,'REPORTE: RECEPCION DE MERCANCIA                            FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'SUCURSAL: '.utf8_decode($f->descripcion).'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'FECHA DEL DIA: '.$_GET[fecharecepcion].'',0,0,'L');
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
	
	
	$pdf = new pdf('L','mm','A4');
	$pdf->AliasNbPages();
	$titulos = array('GUIA','DESTINATARIO','DESC./CONT.','PESO','PAGADO','COBRAR','EMB','DEFECTO');
	$medidas = array(25,70,70,20,22,25,15,20);
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',7);
	//Table with 20 rows and 4 columns
	
	$pdf->SetWidths($medidas);
	
	$pdf->Titulos($titulos,$medidas);
	
	$pdf->SetFont('Arial','',7);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
	$pdf->addLeyenda("Los operadores abajo firmantes manifestamos haber recibido la mercancia amparada con las",150);
	$pdf->addLeyenda("guias embarque registradas en la presente relacion, por lo que nos obligamos hacer entrega de",155);
	$pdf->addLeyenda("la misma en su destino, en las condiciones y cantidades que nos fueron entregadas.",160);
		
	//$pdf->addFirma("Nombre y Firma",10,170,"Entrego Sucl.LCR",60);
	$pdf->addFirma("Nombre y Firma",10,170,"OPERADOR",60);
	$pdf->addFirma("Nombre y Firma",80,170,"OPERADOR",60);
	$pdf->addFirma("",150,170,"UNIDAD",15);
	$pdf->addFirma("Nombre y Firma", 215,170,"Recibio Sucl.".$pdf->recibio."",50);
	
	$pdf->Output();
?>
