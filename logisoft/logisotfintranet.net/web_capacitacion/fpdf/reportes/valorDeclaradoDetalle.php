<?	require_once('../fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

	$s = "SELECT cs.prefijo AS sucursal,gv.id AS guias,DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
	CONCAT_WS(' ',ccr.nombre,ccr.paterno,ccr.materno)AS remitente,
	CONCAT_WS(' ',ccd.nombre,ccd.paterno,ccd.materno)AS destinatario,
	IFNULL(gv.valordeclarado,0) AS valordeclarado, IFNULL(gv.valordeclarado * (SELECT IFNULL(prima / 100,0)AS prima 
	FROM configuradorgeneral),0) AS seguro FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON cs.id = IF(gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)		
	INNER JOIN catalogocliente ccr ON gv.idremitente=ccr.id
	INNER JOIN catalogocliente ccd ON gv.iddestinatario=ccd.id	
	WHERE gv.valordeclarado >= (SELECT cantidadvalordeclarado FROM configuradorgeneral) AND
	gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
	".(($_GET[sucursal]!=1)?" and IF(gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)='".$_GET[sucursal]."'":"")."		
	UNION
	SELECT cs.prefijo AS sucursal,ge.id AS guias,DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha,
	CONCAT_WS(' ',ccr.nombre,ccr.paterno,ccr.materno)AS remitente,
	CONCAT_WS(' ',ccd.nombre,ccd.paterno,ccd.materno)AS destinatario,
	IFNULL(ge.valordeclarado,0) AS valordeclarado, 
	IFNULL(ge.valordeclarado * (SELECT IFNULL(prima / 100,0)AS prima FROM configuradorgeneral),0) AS seguro 
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal cs ON cs.id = IF(ge.tipoflete=0,ge.idsucursalorigen,ge.idsucursaldestino)
	INNER JOIN catalogocliente ccr ON ge.idremitente=ccr.id
	INNER JOIN catalogocliente ccd ON ge.iddestinatario=ccd.id
	WHERE ge.valordeclarado>=(SELECT cantidadvalordeclarado FROM configuradorgeneral) AND
	ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
	".(($_GET[sucursal]!=1)?" and IF(ge.tipoflete=0,ge.idsucursalorigen,ge.idsucursaldestino)='".$_GET[sucursal]."'":"")."";
	$r = mysql_query($s,$l) or die($s);
	$data = array();
	$tvalor = 0;
	$tseguro = 0;
	$total = mysql_num_rows($r);
	if($total>0){
		while($f = mysql_fetch_array($r)){
			$f[0] = cambio_texto($f[0]);
			$f[1] = cambio_texto($f[1]);
			$f[3] = cambio_texto($f[3]);
			$f[4] = cambio_texto($f[4]);
			$tvalor = $f[5] + $tvalor;
			$tseguro = $f[6] + $tseguro;
			$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6]);
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
			$this->Cell(70,10,'REPORTE: DETALLE DE GUIAS CON VALOR DECLARADO                                                   FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
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
	$titulos = array('SUC','GUIA','FECHA','REMITENTE','DESTINATARIO','VALOR DECLARADO','SEGURO');
	$medidas = array(15,25,20,80,80,27,27);
	$pdf->Header($_GET[fechainicio],$_GET[fechafin]);
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',7);
	//Table with 20 rows and 4 columns
	
	$pdf->SetWidths($medidas);
	
	$pdf->Titulos($titulos,$medidas);
	
	$pdf->SetFont('Arial','',7);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
	$pdf->addLeyenda2("TOTAL:",180,210);
	$pdf->addLeyenda2("$".number_format($tvalor,2,'.',','),180,235);
	$pdf->addLeyenda2("$".number_format($tseguro,2,'.',','),180,265);
//	
	$pdf->Output();

?>
