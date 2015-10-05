<?	
	require_once('../../fpdf.php');
	require_once("../../../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s ="SELECT 'VENTA' AS movimiento,SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,
		SUM(cobcred) AS cobcredito, SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total FROM(
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0)) AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0)) AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0)) AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0)) AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv ON cs.id=gv.idsucursalorigen
		WHERE /*gv.estado!='CANCELADO' AND*/ YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION
		SELECT cs.id,SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO',ge.total,0)) AS pagcont,
		SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CREDITO',ge.total,0)) AS pagcred,0 cobcont, 0 cobcred 
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursalorigen 
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." 
		GROUP BY cs.id
		UNION /*canceladas*/
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0))*-1 AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0))*-1 AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0))*-1 AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0))*-1 AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursalorigen
		INNER JOIN historial_cancelacionysustitucion h ON gv.id=h.guia AND (h.accion='SUSTITUCION REALIZADA' or h.accion='CANCELADO')
		WHERE gv.estado='CANCELADO' AND YEAR(gv.fecha)>='2011' AND YEAR(h.fecha)>=2011 
		".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND gv.idsucursalorigen!=gv.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND h.sucursal=".$_GET[sucursal]." GROUP BY cs.id
		) AS t1
		UNION
		/*recibido*/
		SELECT 'RECIBIDO' AS movimiento, SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,SUM(cobcred) AS cobcredito,
		SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total FROM(
		SELECT cs.id,SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO',ge.total,0)) AS pagcont,
		SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CREDITO',ge.total,0)) AS pagcred,0 cobcont, 0 cobcred 
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursaldestino
		WHERE /*ge.estado!='CANCELADO' AND*/ YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' 
		".((!empty($_GET[fechainicio]))? " AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND ge.idsucursalorigen!=ge.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0)) AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0)) AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0)) AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0)) AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		WHERE /*gv.estado!='CANCELADO' AND*/ YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND gv.idsucursalorigen!=gv.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION /*canceladas*/
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0))*-1 AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0))*-1 AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0))*-1 AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0))*-1 AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON gv.id=h.guia AND (h.accion='SUSTITUCION REALIZADA' or h.accion='CANCELADO')
		WHERE gv.estado='CANCELADO' AND YEAR(gv.fecha)>='2011' AND YEAR(h.fecha)>=2011 
		".((!empty($_GET[fechainicio]))? " AND h.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND h.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND gv.idsucursalorigen!=gv.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND h.sucursal=".$_GET[sucursal]." GROUP BY cs.id) AS t2";
	$r = mysql_query($s,$l) or die($s);
	$data = array();
	$pagcontado = 0; $pagcredito = 0; $cobcontado = 0; $cobcredito = 0;
	while($f = mysql_fetch_array($r)){
		$pagcontado = $f[1] + $pagcontado;
		$pagcredito = $f[2] + $pagcredito;
		$cobcontado = $f[3] + $cobcontado;
		$cobcredito = $f[4] + $cobcredito;
		$f[5] = $f[1] + $f[2] + $f[3] + $f[4];
		$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5]);
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
			$this->Cell(30,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');		
			//Salto de linea
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(70,10,'REPORTE: VENTAS Y RECIBIDOS                                  FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
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
//				$this->Rect($x,$y,$w,$h);
				//Print the text
				if(!is_numeric($data[$i])){
					$this->MultiCell($w,5,utf8_encode($data[$i]),0,$a);
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

	$pdf = new pdf();
	$pdf->AliasNbPages();
	$titulos = array('MOVIMIENTO','PAGADA-CONTADO','PAGADA-CREDITO','COBRAR-CONTADO','COBRAR-CREDITO','TOTAL');
	$medidas = array(20,35,35,35,35,35);
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',6);
	//Table with 20 rows and 4 columns
	
	$pdf->SetWidths($medidas);
	
	$pdf->Titulos($titulos,$medidas);
	
	$pdf->SetFont('Arial','',7);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
	$pdf->SetFont('Arial','B',6);
	$total = $pagcontado + $pagcredito + $cobcontado + $cobcredito;
	$pdf->addLeyenda2("TOTALES:",160,10);
	$pdf->addLeyenda2("$".number_format($pagcontado,2,'.',','),160,40);
	$pdf->addLeyenda2("$".number_format($pagcredito,2,'.',','),160,80);
	$pdf->addLeyenda2("$".number_format($cobcontado,2,'.',','),160,120);
	$pdf->addLeyenda2("$".number_format($cobcredito,2,'.',','),160,150);
	$pdf->addLeyenda2("$".number_format($total,2,'.',','),160,190);
	
	$pdf->Output();		

?>