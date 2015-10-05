<?php
	require('../fpdf.php');
	//require('tablaMultiCell.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT estado, cliente, direccion,CONCAT('FA-',factura) AS factura,fechaguia,
	fechavencimiento,importe,'' AS observaciones FROM (
	SELECT IF(rd.estado='No Revisadas','REVISION','COBRANZA')AS estado,
	CONCAT(f.nombrecliente,' ',f.apellidopaternocliente,' ',f.apellidomaternocliente) AS cliente,
	CONCAT(f.calle,' ',f.numero,' ',f.colonia,' ',f.poblacion)AS direccion,
	rd.factura, DATE_FORMAT(rd.fechaguia,'%d/%m/%Y') AS fechaguia, 
	DATE_FORMAT(rd.fechavencimiento,'%d/%m/%Y')AS fechavencimiento, 
	IFNULL(f.total,0)+IFNULL(f.sobmontoafacturar,0)+IFNULL(f.otrosmontofacturar,0) AS importe,'' as observaciones
	FROM relacioncobranza r
	INNER JOIN relacioncobranzadetalle rd ON r.folio = rd.relacioncobranza AND r.sucursal = rd.sucursal
	INNER JOIN facturacion f ON rd.factura = f.folio 
	WHERE r.folio = ".$_GET[folio]." AND r.sucursal = ".$_GET[sucursal]."
	GROUP BY rd.factura)tabla ORDER BY factura";
	$r = mysql_query($s,$l) or die($s);
	$data = array();
	$tpagado = 0;
	$total = mysql_num_rows($r);
	if($total>0){
		while($f = mysql_fetch_array($r)){
			$f[0] = cambio_texto($f[0]);
			$f[1] = cambio_texto($f[1]);
			$tpagado += $f['importe'];
			$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6],'7'=>$f[7]);
		}
	}
	
	class pdf extends FPDF{
		var $widths;
		var $aligns;
		
		function Header(){
			require_once("../../Conectar.php");
			$l = Conectarse("webpmm");
			$s="SELECT CONCAT('',' ',
             CASE DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."')
                  WHEN 1 THEN 'DOMINGO'
                  WHEN 2 THEN 'LUNES'
                  WHEN 3 THEN 'MARTES'
                  WHEN 4 THEN 'MIERCOLES'
                  WHEN 5 THEN 'JUEVES'
                  WHEN 6 THEN 'VIERNES'
                  WHEN 7 THEN 'SABADO'
             END,' ',DAY('".cambiaf_a_mysql($_GET[fecha])."'),' ','',' ',
		CASE MONTH('".cambiaf_a_mysql($_GET[fecha])."')
		WHEN 1 THEN 'ENERO' 
		WHEN 2 THEN 'FEBRERO' 
		WHEN 3 THEN 'MARZO' 
		WHEN 4 THEN 'ABRIL' 
		WHEN 5 THEN 'MAYO' 
		WHEN 6 THEN 'JUNIO' 
		WHEN 7 THEN 'JULIO' 
		WHEN 8 THEN 'AGOSTO' 
		WHEN 9 THEN 'SEPTIEMBRE' 
		WHEN 10 THEN 'OCTUBRE' 
		WHEN 11 THEN 'NOVIEMBRE' 
		WHEN 12 THEN 'DICIEMBRE' 
		END,' ','DEL',' ',YEAR('".cambiaf_a_mysql($_GET[fecha])."')) AS dia";
		$r = mysql_query($s,$l)or die($s); 
		$f = mysql_fetch_object($r);
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
			$this->Cell(70,10,'REPORTE: LISTADO DE COBRANZA                                                   FECHA IMPRESO:'.date('d/m/Y').'                                                   FOLIO DE RELACION: '.$_GET[folio],0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'COBRADOR: '.$_GET[cobrador].'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'DIA: '.$f->dia.'',0,0,'L');			

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
	$titulos = array('ESTADO','CLIENTE','DIRECCION','REFERENCIA','FECHA','F. VENCIMIENTO','COBRAR','OBSERVACIONES');
	$medidas = array(20,60,65,30,20,22,25,40);
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',7);
	//Table with 20 rows and 4 columns
	
	$pdf->SetWidths($medidas);
	
	$pdf->Titulos($titulos,$medidas);
	
	$pdf->SetFont('Arial','',7);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	$pdf->addLeyenda2("TOTAL:",180,200);
	$pdf->addLeyenda2("$".number_format($tpagado,2,'.',','),180,238);
	$pdf->Output();
?>
