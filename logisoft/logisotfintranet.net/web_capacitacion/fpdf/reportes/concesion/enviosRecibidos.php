<?	require_once('../../fpdf.php');
	require_once("../../../Conectar.php");
	$l = Conectarse("webpmm");
				
		$s = "SELECT (ge.id) guia,(ge.fecha) fechaguia,(ge.tflete) flete,(ge.ttotaldescuento) descuento,((ge.tflete+ge.texcedente)-ge.ttotaldescuento) fleteneto,
		(((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.recibido/100)) comision,(ge.trecoleccion) recoleccion,0 AS comisionrad,(ge.tcostoead) entrega, 
		(ge.tcostoead*(cs.porcead/100)) comisionead,((((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.recibido/100))+
		(ge.tcostoead*(cs.porcead/100))) total,(ge.total) tgral,ge.estado,CONCAT('PAGADA','-',IF(ge.tipopago='CONTADO','CONTADO','CREDITO')) condicion
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursaldestino
		WHERE /*ge.estado!='CANCELADO' AND*/ YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND ge.idsucursalorigen!=ge.idsucursaldestino AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) 
		AND cs.id=".$_GET[sucursal]." GROUP BY ge.id
		UNION
		SELECT (gv.id) guia,(gv.fecha) fechaguia,(gv.tflete) flete,(gv.ttotaldescuento) descuento,((gv.tflete+gv.texcedente)-gv.ttotaldescuento) fleteneto,
		(((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100)) comision,(gv.trecoleccion) recoleccion,0 AS comisionrad,(gv.tcostoead) entrega, 
		(gv.tcostoead*(cs.porcead/100)) comisionead,((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))+
		(gv.tcostoead*(cs.porcead/100))) total,(gv.total) tgral,gv.estado,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		WHERE /*gv.estado!='CANCELADO' AND*/ YEAR(gv.fecha)>='2011' AND gv.idsucursalorigen!=gv.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY gv.id
		UNION /*canceladas*/
		SELECT (gv.id) guia,(gv.fecha) fechaguia,(gv.tflete*-1) flete,(gv.ttotaldescuento*-1) descuento,((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*-1 fleteneto,
		((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))*-1) comision,(gv.trecoleccion)*-1 recoleccion,0 AS comisionrad,(gv.tcostoead*-1) entrega, 
		((gv.tcostoead*(cs.porcead/100))*-1) comisionead,(((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))+
		(gv.tcostoead*(cs.porcead/100)))*-1) total,(gv.total*-1) tgral,gv.estado,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON gv.id=h.guia AND (h.accion='SUSTITUCION REALIZADA' or h.accion='CANCELADO')
		WHERE gv.estado='CANCELADO' AND YEAR(gv.fecha)>='2011' AND YEAR(h.fecha)>=2011 
		AND gv.idsucursalorigen!=gv.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND h.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		h.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND h.sucursal=".$_GET[sucursal]." $restar 
		GROUP BY gv.id ";
		$r = mysql_query($s,$l) or die($s);
		$data = array();
		$flete = 0; $descuento = 0; $fleteneto = 0; $comision = 0; $recoleccion = 0;
		$comisionrad = 0; $entrega = 0; $comisionead = 0; $sobrepeso = 0; $total = 0;$totalgral = 0;
	while($f = mysql_fetch_array($r)){
		$flete 			= $f[2] + $flete;
		$descuento 		= $f[3] + $descuento;
		$fleteneto 		= $f[4] + $fleteneto;
		$comision 		= $f[5] + $comision;
		$recoleccion 	= $f[6] + $recoleccion;
		$comisionrad 	= $f[7] + $comisionrad;
		$entrega 		= $f[8] + $entrega;
		$comisionead 	= $f[9] + $comisionead;		
		$total 			= $f[10] + $total;
		$totalgral 			= $f[11] + $totalgral;
		
		$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6],'7'=>$f[7],'8'=>$f[8],'9'=>$f[9],'10'=>$f[10],'11'=>$f[11],'12'=>$f[12],'13'=>$f[13]);
	}
	
	class pdf extends FPDF{
		var $widths;
		var $aligns;
		
		function Header(){
			//Logo
			$this->Image('../../logo.jpg',10,8,33);
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
			$this->Cell(70,10,'REPORTE: ENVIOS RECIBIDOS POR LA FRANQUICIA                                                   FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'PERIODO DEL: '.$_GET[fechainicio].' AL '.$_GET[fechafin].'',0,0,'L');			

			$this->Ln(10);
		
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
				//$this->Rect($x,$y,$w,$h);
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
	$titulos = array('GUIA','FECHA','FLETE','DESCUENTO','FLETE NETO','COMISION','RECOLECCION','COMISION RAD','ENTREGA','COMISION EAD','TOTAL','TOTAL GRAL','CONDICION','STATUS');
	$medidas = array(22,15,20,20,25,20,20,20,20,20,35,35,25,40);
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',6);	
	
	$pdf->SetWidths($medidas);
	
	$pdf->Titulos($titulos,$medidas);
	
	$pdf->SetFont('Arial','',6);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
	$pdf->addLeyenda2("TOTALES:",190,10);
	$pdf->addLeyenda2("$".number_format($flete,2,'.',','),190,55);
	$pdf->addLeyenda2("$".number_format($descuento,2,'.',','),190,75);
	$pdf->addLeyenda2("$".number_format($fleteneto,2,'.',','),190,100);
	$pdf->addLeyenda2("$".number_format($comision,2,'.',','),190,120);
	$pdf->addLeyenda2("$".number_format($recoleccion,2,'.',','),190,140);
	
	$pdf->addLeyenda2("$".number_format($comisionrad,2,'.',','),190,160);
	$pdf->addLeyenda2("$".number_format($entrega,2,'.',','),190,180);
	$pdf->addLeyenda2("$".number_format($comisionead,2,'.',','),190,200);	
	$pdf->addLeyenda2("$".number_format($total,2,'.',','),190,240);
	$pdf->addLeyenda2("$".number_format($totalgral,2,'.',','),190,280);
	
	$pdf->Output();
?>