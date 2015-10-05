<?php
	require('../fpdf/fpdf.php');
//	require('../Conectar.php');	

class PDF extends FPDF{
	function Header(){
		//Logo
		$this->Image('logo.jpg',10,8,33);
		//Arial bold 15
		$this->SetFont('Arial','B',15);
		//Movernos a la derecha
		$this->Cell(80);
		//Titulo
		$this->Cell(30,10,'ACTIVIDADES DEL USUARIO',0,0,'C');
		//Salto de lnea
		$this->Ln(20);
		$this->Ln(20);
	}
	//Cargar los datos
	function LoadData($file){
		//Leer las lneas del fichero
		$lines=file($file);
		$data=array();
		foreach($lines as $line)
			$data[]=explode(';',chop($line));
		return $data;
	}

//Tabla simple
function BasicTable($header,$data)
{
	//Cabecera
	foreach($header as $col)
		$this->Cell(40,7,$col,1);
	$this->Ln();
	//Datos
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(40,6,$col,1);
		$this->Ln();
	}
}

//Una tabla ms completa
function ImprovedTable($header,$data)
{
	//Anchuras de las columnas
	$w=array(60,30,20,35,35);
	//Cabeceras
	$l = mysql_connect("localhost","pmm","guhAf2eh");
	//$l = mysql_connect("DBSERVER","root","root");
	mysql_select_db("pmm_dbpruebas", $l);
	//mysql_select_db("pmm_dbweb", $l);
	$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS cliente, d.calle, d.poblacion, d.estado, d.pais
	FROM catalogocliente cc
	INNER JOIN direccion d ON cc.id = d.codigo
	WHERE d.facturacion='SI'
	LIMIT 0,100";	
	$r = mysql_query($s,$l) or die($s);
	
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	//Datos	
		while($row = mysql_fetch_array($r)){
			$this->Cell($w[0],6,$row[0],'LR');
			$this->Cell($w[1],6,$row[1],'LR');
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
			$this->Ln();
		}
	/*foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR');
		$this->Cell($w[1],6,$row[1],'LR');
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
		$this->Ln();
	}*/
	//Lnea de cierre
	$this->Cell(array_sum($w),0,'','T');
}

//Tabla coloreada
function FancyTable($header,$data){
	//Colores, ancho de lnea y fuente en negrita
	$this->SetFillColor(255,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(128,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B');
	//Cabecera
	$w = array(40,35,40,45);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',1);
	$this->Ln();
	//Restauracin de colores y fuentes
	//$this->SetFillColor(224,235,255);
	//$this->SetTextColor(0);
	$this->SetFont('');
	//Datos
	$fill=0;
	/*foreach($data as $row){
		$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
		$this->Ln();
		$fill=!$fill;
	}*/
		$this->Cell(array_sum($w),0,'','T');
	}
}

$pdf = new PDF();
//Ttulos de las columnas
$header = array('Cliente','Referencia','Fecha','Factura','Importe');
//Carga de datos
//$data=$pdf->LoadData('paises.txt');
$pdf->SetFont('Arial','',12);
$pdf->AddPage();
//$pdf->BasicTable($header,$data);
//$pdf->AddPage();
$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
//$pdf->FancyTable($header,$data);
$pdf->Output();
?>
