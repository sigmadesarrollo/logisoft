<?	require('fpdf.php');
	
	function cambio_texto($texto){
		if($texto == " ")
			$texto = "";
		if($texto!=""){
			$n_texto=ereg_replace("á","&#224;",$texto);
			$n_texto=ereg_replace("é","&#233;",$n_texto);
			$n_texto=ereg_replace("í","&#237;",$n_texto);
			$n_texto=ereg_replace("ó","&#243;",$n_texto);
			$n_texto=ereg_replace("ú","&#250;",$n_texto);
			
			$n_texto=ereg_replace("Á","&#193;",$n_texto);
			$n_texto=ereg_replace("É","&#201;",$n_texto);
			$n_texto=ereg_replace("Í","&#205;",$n_texto);
			$n_texto=ereg_replace("Ó","&#211;",$n_texto);
			$n_texto=ereg_replace("Ú","&#218;",$n_texto);
			
			$n_texto=ereg_replace("ñ", "&#241;", $n_texto);
			$n_texto=ereg_replace("Ñ", "&#209;", $n_texto);
			$n_texto=ereg_replace("¿", "&#191;", $n_texto);
			return $n_texto;
		}else{
			return "&#32;";
		}
	}
	
	class PDF extends FPDF{	
		
		function Header(){
			//Logo
			$this->Image('logo.jpg',10,8,33);
			$this->Ln(20);
			//Arial bold 15		
			$this->SetFont('Arial','B',15);
		
			//Movernos a la derecha		
			$this->Cell(80);
		
			//Titulo		
			$this->Cell(70,10,'RELACION DE EMBARQUE',0,0,'C');
		
			//Salto de linea		
			$this->Ln(20);
		}

		//Cargar los datos	
		function LoadData(){
			$str = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 

			if(!ereg("dbserver",$str)){
				$l = mysql_connect("localhost","pmm","guhAf2eh");
			}else{
				$l = mysql_connect("DBSERVER","root","root");
			}
			
			if(ereg("web_pruebas/",$str)){
				mysql_select_db("pmm_dbpruebas", $l);
			
			}else if(ereg("web_capacitacion/",$str)){
				mysql_select_db("pmm_curso", $l);
			
			}else if(ereg("web/",$str)){
				mysql_select_db("pmm_dbweb", $l);
				
			}else if(ereg("dbserver",$str)){
				mysql_select_db("webpmm", $l);
			}
			//Leer las lneas del fichero
			$s = "SELECT d.guia, t.destinatario, t.descripcion, t.totalpeso,
			t.pagado, t.cobrar, t.emb FROM embarquedemercancia e
			INNER JOIN embarquedemercanciadetalle d ON e.folio = d.idembarque
			INNER JOIN (SELECT CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
			gv.id AS guia, CONCAT(gd.cantidad,' ',gd.descripcion,'/',gd.contenido) AS descripcion,
			gv.totalpeso, IF(gv.tipoflete=0,gv.total,0) AS pagado,
			IF(gv.tipoflete=1,gv.total,0) AS cobrar,
			IF(gv.ocurre=0,'EAD','OCU') AS emb FROM guiasventanilla gv
			INNER JOIN catalogocliente ce ON gv.iddestinatario = ce.id
			INNER JOIN guiaventanilla_detalle gd ON gv.id = gd.idguia
			UNION
			SELECT CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
			ge.id AS guia, CONCAT(gde.cantidad,' ',gde.descripcion,'/',gde.contenido) AS descripcion,
			ge.totalpeso, IF(ge.tipoflete='PAGADO',ge.total,0) AS pagado,
			IF(ge.tipoflete='POR COBRAR',ge.total,0) AS cobrar,
			IF(ge.ocurre=0,'EAD','OCU') AS emb FROM guiasempresariales ge
			INNER JOIN catalogocliente ce ON ge.iddestinatario = ce.id
			INNER JOIN guiaventanilla_detalle gde ON ge.id = gde.idguia) AS t ON d.guia = t.guia
			WHERE e.folio = ".$_GET[folio]." AND e.idsucursal = ".$_GET[sucursal]."
			GROUP BY d.guia";
			$r = mysql_query($s,$l) or die($s);
			$data = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_array($r)){
					$f[0] = cambio_texto($f[0]);
					$f[1] = cambio_texto($f[1]);
					$f[2] = cambio_texto($f[2]);
					$data[] = array('0'=>$f[0],'1'=>$f[1],'2'=>$f[2],'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6]);
				}
				return $data;
			}else{
				return "no encontro";
			}
		}
		
		function ImprovedTable($header,$data){
			//print_r($data);
			//die('die');
			//Anchuras de las columnas	
			$w = array(25,115,80,15,20,20,10);
			
			//Cabeceras
			for($i=0;$i<count($header);$i++){	
				$this->Cell($w[$i],7,$header[$i],1,0,'C');						
			}
			$this->Ln();
			//Datos			
			foreach($data as $row){
				$this->SetFont('Arial','',8);
				$this->Cell($w[0],6, utf8_decode($row[0]),'LR');				
				$this->Cell($w[1],6, utf8_decode($row[1]),'LR');
				$this->Cell($w[2],6, utf8_decode($row[2]),'LR');				
				$this->Cell($w[3],6, number_format($row[3]),'LR',0,'R');
				$this->Cell($w[4],6, number_format($row[4],2),'LR',0,'R');
				$this->Cell($w[5],6, number_format($row[5],2),'LR',0,'R');
				$this->Cell($w[6],6, utf8_decode($row[6]),'LR');				
				$this->Ln();
				
			}
			//Linea de cierre
			$this->Cell(array_sum($w),0,'','T');	
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
	}
	
	$pdf = new PDF('L','mm','A4');
	$pdf->AliasNbPages();
	//Ttulos de las columnas
	$header = array('GUIA','DESTINATARIO','DESC./CONT.','PESO','PAGADO','COBRAR','EMB');
	
	//Carga de datos	
	$data = $pdf->LoadData();
	$pdf->SetFont('Arial','B',10);	
	$pdf->AddPage();	
	$pdf->ImprovedTable($header,$data);	
	$pdf->addLeyenda("Los operadores abajo firmantes manifestamos haber recibido la mercancia amparada con las",150);
	$pdf->addLeyenda("guias embarque registradas en la presente relacion, por lo que nos obligamos hacer entrega de",155);
	$pdf->addLeyenda("la misma en su destino, en las condiciones y cantidades que nos fueron entregadas.",160);
	$pdf->addFirma("Nombre y Firma",10,170,"Entrego Sucl.LCR",60);
	$pdf->addFirma("Nombre y Firma",80,170,"OPERADOR",60);
	$pdf->addFirma("Nombre y Firma",150,170,"OPERADOR",60);
	$pdf->addFirma("",215,170,"UNIDAD",15);
	$pdf->addFirma("Nombre y Firma",235,170,"Entrego Sucl.TLQ",50);
	$pdf->Output();
?>