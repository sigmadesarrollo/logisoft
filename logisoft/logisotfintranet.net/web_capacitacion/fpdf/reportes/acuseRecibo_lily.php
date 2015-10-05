<? 
	require('../fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	
	$s = "SELECT s.factura, s.idcliente, CONCAT_WS(' ',s.nombre,s.apepat,s.apemat) AS cliente,
	s.cantidad, CONCAT('Del Folio ',s.desdefolio,' al Folio ',s.hastafolio) AS folios, FORMAT(s.total,2) AS total,
	g.limitekg, FORMAT(g.preciokgexcedente,2) AS preciokgexcedente, DATE_FORMAT(g.vigencia,'%d/%m/%Y') AS vigencia,
	s.prepagada, FORMAT(g.costo,2) AS precioporguia, cs.descripcion as sucursalacobrar
	FROM solicitudguiasempresariales s
	INNER JOIN generacionconvenio g ON s.idconvenio = g.folio
	INNER JOIN catalogosucursal cs on cs.id = s.sucursalacobrar	
	WHERE s.id = ".$_GET[venta]."";
	$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
	
	class PDF extends FPDF{
		//Cabecera de página
		function Header(){
			require_once('../../Conectar.php');
			$l = Conectarse('webpmm');
	
			$s = "SELECT * FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $su = mysql_fetch_object($r);
			
			switch (date('m')){
				case "01":
					$mes = "ENERO";
				break;
				
				case "02":
					$mes = "FEBRERO";
				break;
				
				case "03":
					$mes = "MARZO";
				break;
				
				case "04":
					$mes = "ABRIL";
				break;
				
				case "05":
					$mes = "MAYO";
				break;
				
				case "06":
					$mes = "JUNIO";
				break;
				
				case "07":
					$mes = "JULIO";
				break;
				
				case "08":
					$mes = "AGOSTO";
				break;
				
				case "09":
					$mes = "SEPTIEMBRE";
				break;
				
				case "10":
					$mes = "OCTUBRE";
				break;
				
				case "11":
					$mes = "NOVIEMBRE";
				break;
				
				case "12":
					$mes = "DICIEMBRE";
				break;
			
			} 
			
			//Logo
			$this->Image('../logo.jpg',10,8,33);
			//Arial bold 15
			$this->SetFont('Arial','B',13);
			//Movernos a la derecha
			$this->Cell(80);
			//Título
			$this->Cell(30,10,'ENTREGAS PUNTUALES S DE RL DE CV',0,0,'C');
			$this->Ln(10);
			$this->Cell(80);
			$this->Cell(30,10,'ACUSE DE RECIBO',0,0,'C');
			//Salto de línea
			$this->Ln(10);
			$this->SetFont('Arial','',10);
			$this->Cell(80);
			$this->Cell(100,10,''.$su->poblacion.', '.$su->estado.', '.date('d').' DE '.$mes.' DE '.date('Y').'',0,0,'R');
			$this->Ln(20);
			
		}
		
		//Pie de página
		function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
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
			$this->Cell(10,5, $titulo, 0, 0, "C");
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
			$this->SetFont( "Arial", "", 10);
			$this->Cell(10,5, $mode, 0, 0, "C");
		}
	}
	
	//Creación del objeto de la clase heredada
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$pdf->Cell(0,10,'No. De Cliente: '.$c->idcliente.'',0,1,'L');
	$pdf->Ln(5);
	$pdf->Cell(0,10,'No. De Venta: '.$_GET[venta].'',0,1,'L');
	
	$pdf->Ln(5);
	
	$pdf->SetFont('Times','',11);	
	$pdf->MultiCell(0,10,'                   Recibí de ENTREGAS PUNTUALES S DE RL DE CV. Sucursal '.$c->sucursalacobrar.' ('.$c->cantidad.') guías de tipo '.(($c->prepagada=='SI')?'PREPAGADAS':'CONSIGNACION').' '.$c->folios.' a Precio de $'.$c->total.' '.(($c->prepagada=='SI')?'con un precio unitario de 
$ '.$c->precioporguia:'').', un peso máximo de '.$c->limitekg.' Kilos (real o volumen) y $'.$c->preciokgexcedente.' cada kilo excedente que resulte. Importes más IVA.',0,1,'L');
			
	$pdf->SetFont('Times','B',11);
	
	$pdf->Cell(0,10,'Fecha de Vencimiento: '.$c->vigencia.'.',0,1,'R');
	
	$pdf->Cell(0,10,'DATOS FISCALES DE LA EMPRESA:',0,1,'L');
	
	$pdf->SetFont('Times','',10);
	
	$pdf->Cell(0,20,'EMPRESA Y/O RAZÓN: '.$c->cliente.'',0,1,'L');
	
	$pdf->addFirma("".$c->sucursalacobrar."",10,170,"Entregó",60);
	
	$pdf->addFirma("".$c->cliente."",110,170,"Recibí",60);
	
	$pdf->addFirma("DEPARTAMENTO DE VENTAS OFIC. MATRIZ",70,200,"Elaboró",60);
	
	$pdf->SetFont('Times','B',11);
	$pdf->Ln(40);
	$pdf->Cell(0,5,'No. De Factura: '.$c->factura.'',0,1,'L');
	$pdf->Ln(5);
	$pdf->MultiCell(0,5,'Nota: Cada Acuse de Recibo deberá ser devuelto a Matriz con No. De Factura, de lo contrario será devuelto, y en el Depto de Ventas no se dará de baja la venta.  Anexar Copia de Factura.',0,1,'L');
	$pdf->SetFont('Times','',11);
	$pdf->MultiCell(0,5,'FAVOR DE ENVIAR A OFIC. MATRIZ ORIGINAL FIRMADA DE RECIBIDO, POR EL GERENTE Y CLIENTE. ¡URGENTE!… "GRACIAS".',0,1,'L');
	
	$pdf->Output();
?>