<?	require('fpdf.php');

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
		
			//Arial bold 15		
			$this->SetFont('Arial','B',15);
		
			//Movernos a la derecha		
			$this->Cell(80);
		
			//Titulo		
			$this->Cell(30,10,'RELACION DE EMBARQUE',1,0,'C');
		
			//Salto de linea		
			$this->Ln(20);
		}

		//Cargar los datos	
		function LoadData(){
			//Leer las lneas del fichero
			$s = "SELECT d.guia, t.destinatario, t.descripcion, t.totalpeso,
			t.pagado, t.cobrar, t.emb FROM embarquedemercancia e
			INNER JOIN embarquedemercanciadetalle d ON e.folio = d.idembarque
			INNER JOIN (SELECT CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
			gv.id AS guia, CONCAT(gd.cantidad,' ',gd.descripcion,'/',gd.contenido) AS descripcion,
			gv.totalpeso, IF(gv.tipoflete=0,gv.total,0) AS pagado,
			IF(gv.tipoflete=1,gv.total,0) AS cobrar,
			IF(gv.ocurre=0,'EAD','OCURRE') AS emb FROM guiasventanilla gv
			INNER JOIN catalogocliente ce ON gv.iddestinatario = ce.id
			INNER JOIN guiaventanilla_detalle gd ON gv.id = gd.idguia
			UNION
			SELECT CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
			ge.id AS guia, CONCAT(gde.cantidad,' ',gde.descripcion,'/',gde.contenido) AS descripcion,
			ge.totalpeso, IF(ge.tipoflete='PAGADO',ge.total,0) AS pagado,
			IF(ge.tipoflete='POR COBRAR',ge.total,0) AS cobrar,
			IF(ge.ocurre=0,'EAD','OCURRE') AS emb FROM guiasempresariales ge
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
					$data[] = $f;
				}
				return $data;
			}else{
				return "no encontro";
			}
			
			/*$lines=file($file);
			$data=array();
			foreach($lines as $line)
				$data[]=explode(';',chop($line));*/
				
			return $data;
		}
		
		function ImprovedTable($header,$data){
			//Anchuras de las columnas	
			$w = array(40,35,40,45);
			
			//Cabeceras		
			for($i=0;$i<count($header);$i++){	
					$this->Cell($w[$i],7,$header[$i],1,0,'C');		
				$this->Ln();
			}
			
			//Datos		
			foreach($data as $row){	
				$this->Cell($w[0],6,$row[0],'LR');	
				$this->Cell($w[1],6,$row[1],'LR');	
				$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');	
				$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');	
				$this->Ln();	
			}	
			//Lnea de cierre	
			$this->Cell(array_sum($w),0,'','T');	
		}
	}
	
	$pdf = new PDF('L','mm','A4');

	//Ttulos de las columnas	
	$header = array('Guia','Destinatario','Desc./Cont.','Peso','Pagado','Cobrar','EMB');
	
	//Carga de datos	
	$data = $pdf->LoadData();
	$pdf->SetFont('Arial','',14);	
	$pdf->AddPage();
	$pdf->ImprovedTable($header,$data);	
	
	$pdf->Output();
?>