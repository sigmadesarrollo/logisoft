<?	require('../fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	
	if(!empty($_GET[rmoperaciones])){
		$s = "SELECT ruta FROM bitacorasalida WHERE folio = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		$s = "SELECT GROUP_CONCAT(d.sucursal) AS sucursal FROM catalogoruta cr
		INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
		WHERE cr.id = ".$f->ruta." AND tipo between 2 AND 3";
		$ru = mysql_query($s,$l) or die($s);
		$cr = mysql_fetch_object($ru);
		$_GET[destino] = $cr->sucursal;
	}
	
	class PDF extends FPDF{
		
		function ImprovedTable($header,$data){
			require_once('../../Conectar.php');
			$l = Conectarse('webpmm');
			//print_r($data);
			//die('die');
			//Anchuras de las columnas	
			
			$s = "SELECT prefijo FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $fq = mysql_fetch_object($r);
			
			$w = array(25,110,70,15,20,20,10,10);
			$contador=0;
			$data = split(",",$data);
			for($ii=0;$ii<count($data);$ii++){
				$s = "SELECT descripcion, prefijo FROM catalogosucursal WHERE id = ".$data[$ii]."";
				$r = mysql_query($s,$l) or die($s); $ff = mysql_fetch_object($r);
				
				/*$s = "SELECT d.guia, t.destinatario, t.descripcion, t.totalpeso,
				t.pagado, t.cobrar, t.emb, t.idsucursaldestino, IF(fe.guia is null,'COM','FAL') as incidencia FROM embarquedemercancia e
				INNER JOIN embarquedemercanciadetalle d ON e.folio = d.idembarque AND e.idsucursal = d.sucursal
				LEFT JOIN embarquedemercancia_faltante fe ON d.guia = fe.guia AND e.idsucursal = fe.sucursal
				INNER JOIN (SELECT CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
				gv.id AS guia, CONCAT(gd.cantidad,' ',gd.descripcion,'/',gd.contenido) AS descripcion,
				gv.totalpeso, IF(gv.tipoflete=0,gv.total,0) AS pagado,
				IF(gv.tipoflete=1,gv.total,0) AS cobrar,
				IF(gv.ocurre=0,'EAD','OCU') AS emb, gv.idsucursaldestino FROM guiasventanilla gv
				INNER JOIN catalogocliente ce ON gv.iddestinatario = ce.id
				INNER JOIN guiaventanilla_detalle gd ON gv.id = gd.idguia
				UNION
				SELECT CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
				ge.id AS guia, CONCAT(gde.cantidad,' ',gde.descripcion,'/',gde.contenido) AS descripcion,
				ge.totalpeso, IF(ge.tipoflete='PAGADO',ge.total,0) AS pagado,
				IF(ge.tipoflete='POR COBRAR',ge.total,0) AS cobrar,
				IF(ge.ocurre=0,'EAD','OCU') AS emb, ge.idsucursaldestino FROM guiasempresariales ge
				INNER JOIN catalogocliente ce ON ge.iddestinatario = ce.id
				INNER JOIN guiasempresariales_detalle gde ON ge.id = gde.id) AS t ON d.guia = t.guia
				WHERE e.folio = ".$_GET[folio]." AND e.idsucursal = ".$_GET[sucursal]." 
				AND t.idsucursaldestino = ".$data[$ii]."
				GROUP BY d.guia";*/
				
				$s = "SELECT gv.id AS guia, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destinatario,
				CONCAT(gd.cantidad,' ',gd.descripcion,'/',gd.contenido) AS descripcion,
				gv.totalpeso, IF(gv.tipoflete=0,gv.total,0) AS pagado, IF(gv.tipoflete=1,gv.total,0) AS cobrar,
				IF(gv.ocurre=0,'EAD','OCU') AS emb, gv.idsucursaldestino,
				IF(fe.guia IS NULL,'COM','FAL') AS incidencia 
				FROM embarquedemercanciadetalle d
				INNER JOIN guiasventanilla gv ON d.guia = gv.id
				INNER JOIN guiaventanilla_detalle gd ON gv.id = gd.idguia
				INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id
				LEFT JOIN embarquedemercancia_faltante fe ON gv.id = fe.guia AND d.sucursal = fe.sucursal
				WHERE d.idembarque = ".$_GET[folio]." AND d.sucursal = ".$_GET[sucursal]." 
				AND gv.idsucursaldestino = ".$data[$ii]."
				GROUP BY d.guia
				UNION
				SELECT gv.id AS guia, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destinatario,
				CONCAT(gd.cantidad,' ',gd.descripcion,'/',gd.contenido) AS descripcion,
				gv.totalpeso, IF(gv.tipoflete=0,gv.total,0) AS pagado, IF(gv.tipoflete=1,gv.total,0) AS cobrar,
				IF(gv.ocurre=0,'EAD','OCU') AS emb, gv.idsucursaldestino,
				IF(fe.guia IS NULL,'COM','FAL') AS incidencia 
				FROM embarquedemercanciadetalle d
				INNER JOIN guiasempresariales gv ON d.guia = gv.id
				INNER JOIN guiasempresariales_detalle gd ON gv.id = gd.id
				INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id
				LEFT JOIN embarquedemercancia_faltante fe ON gv.id = fe.guia AND d.sucursal = fe.sucursal
				WHERE d.idembarque = ".$_GET[folio]." AND d.sucursal = ".$_GET[sucursal]." 
				AND gv.idsucursaldestino = ".$data[$ii]."
				GROUP BY d.guia";
				$r = mysql_query($s,$l) or die($s);
				$arr = array();
				if(mysql_num_rows($r)>0){
					$this->addPage();
					while($f = mysql_fetch_array($r)){
						$f[0] = cambio_texto($f[0]);
						$f[1] = cambio_texto($f[1]);
						$f[2] = cambio_texto($f[2]);
						$arr[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6],'7'=>$f[8]);
					}
					
					//Cabeceras
					$this->SetFont('Arial','B',10);
					for($i=0;$i<count($header);$i++){	
						$this->Cell($w[$i],7,$header[$i],1,0,'C');						
					}
					$this->Ln();
					
					//Datos			
					$nodatos = 0;
					foreach($arr as $row){
						$nodatos++;
						if($nodatos>15){
							$this->addPage();
						}
						$this->SetFont('Arial','',8);
						$this->Cell($w[0],6, utf8_decode($row[0]),'LR');				
						$this->Cell($w[1],6, utf8_decode($row[1]),'LR');
						$this->Cell($w[2],6, utf8_decode($row[2]),'LR');				
						$this->Cell($w[3],6, number_format($row[3]),'LR',0,'R');
						$this->Cell($w[4],6, '$'.number_format($row[4],2),'LR',0,'R');
						$this->Cell($w[5],6, '$'.number_format($row[5],2),'LR',0,'R');
						$this->Cell($w[6],6, utf8_decode($row[6]),'LR');
						$this->Cell($w[7],6, utf8_decode($row[7]),'LR');				
						$this->Ln();
						
					}
					//Linea de cierre
					$this->Cell(array_sum($w),0,'','T');
					$this->addDestino("DESTINO: ".utf8_decode($ff->descripcion)."",52);
					$this->addLeyenda("Los operadores abajo firmantes manifestamos haber recibido la mercancia amparada con las guias embarque registradas en la presente relacion,",155);
					$this->addLeyenda("por lo que nos obligamos hacer entrega de la misma en su destino, en las condiciones y cantidades que nos fueron entregadas.",160);
					
					$this->addFirma("Nombre y Firma",10,170,"Entrego Sucl.".$fq->prefijo."",60);
					$this->addFirma("Nombre y Firma",80,170,"OPERADOR",60);
					$this->addFirma("Nombre y Firma",150,170,"OPERADOR",60);
					$this->addFirma("",215,170,"UNIDAD",15);
					$this->addFirma("Nombre y Firma",235,170,"Entrego Sucl.".$ff->prefijo."",50);				
					
					//if($contador<count($data)-1){
						//$this->addPage();
					//}				
					$contador++;
				}
			}
		}
		function Header(){
			require_once('../../Conectar.php');
			$l = Conectarse('webpmm');
			
			$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);			
			
			//Logo
			$this->Image('../logo.jpg',10,8,33);
			$this->Ln(20);
			//Arial bold 15		
			$this->SetFont('Arial','B',15);
		
			//Movernos a la derecha		
			$this->Cell(80);
		
			//Titulo		
			$this->Cell(70,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');
		
			//Salto de linea		
			/*$this->Ln(10);
			
			$this->SetFont('Arial','B',8);
			*/
			
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->Cell(70,10,'REPORTE: RELACION DE EMBARQUE                            FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'SUCURSAL: '.utf8_decode($f->descripcion).'               FECHA DEL DIA: '.$_GET[fechaembarque].'',0,0,'L');			
			$this->Ln(12);
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
		
		function addDestino($ref,$posicion){
			$this->SetFont('Arial','B',10);
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
	}
	
	$pdf = new PDF('L','mm','A4');
	$pdf->AliasNbPages();
	//Ttulos de las columnas
	$header = array('GUIA','DESTINATARIO','DESC./CONT.','PESO','PAGADO','COBRAR','EMB','INC');
	
	//Carga de datos
	$pdf->SetFont('Arial','B',10);	
	//$pdf->AddPage();	
	$pdf->ImprovedTable($header,$_GET[destino]);
	
	$pdf->Output();
?>