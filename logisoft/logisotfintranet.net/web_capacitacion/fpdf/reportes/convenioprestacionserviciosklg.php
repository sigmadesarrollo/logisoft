<?php
	session_start();
	require('../fpdf.php');
	//require('tablaMultiCell.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT gc.folio, gc.consumomensual, DATE_FORMAT(gc.fecha, '%d/%m/%Y') AS fecha, DATE_FORMAT(gc.vigencia, '%d/%m/%Y') AS vigencia,
	cs.descripcion AS sucursal, gc.nvendedor, gc.idcliente, gc.rfc, concat_ws(' ',gc.nombre, gc.apaterno, gc.amaterno) as ncliente, gc.calle, gc.numero,
	gc.colonia, gc.cp, gc.poblacion, gc.municipio, gc.estado, gc.pais, gc.celular, gc.telefono, gc.email,
	gc.precioporkg, gc.precioporcaja, gc.descuentosobreflete, gc.cantidaddescuento, gc.limitekg, gc.costo,
	gc.preciokgexcedente, gc.prepagadas, gc.consignacionkg, gc.consignacioncaja, gc.consignaciondescuento, gc.consignaciondescantidad,
	gc.valordeclarado, limite, porcada, costoextra,
	date_format(current_date, '%d/%m/%Y') as fechaactual,IFNULL(gc.legal,'NINGUNO') AS representante,
	CONCAT(DAY(gc.fecha) , ' DE ',
	CASE MONTH(gc.fecha) 
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
	END , ' DEL ' , YEAR(gc.fecha))AS fechaac,
	CONCAT(DAY(gc.vigencia) , ' DE ',
	CASE MONTH(gc.vigencia) 
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
	END , ' DEL ' , YEAR(gc.vigencia))AS fechavige
	FROM generacionconvenio gc
	LEFT JOIN catalogosucursal cs ON gc.sucursal = cs.idsucursal
	WHERE gc.folio = '$_GET[folio]'";
	
	$r = mysql_query($s,$l) or die("error ".mysql_error($l)."--".$s);
	$f = mysql_fetch_object($r);

	class pdf extends FPDF{
		var $widths;
		var $aligns;
		
		function Header(){
			require_once("../../Conectar.php");
			$l = Conectarse("webpmm");
			$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";
			$r = mysql_query($s,$l) or die($s); $ft = mysql_fetch_object($r);
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
			$this->Cell(70,10,'REPORTE:  CONVENIO DE PRESTACION DE SERVICIOS PRECIO POR KILOGRAMO 						FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'SUCURSAL: '.utf8_decode($ft->descripcion).'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'FECHA DEL DIA: '.$_GET[fecharecepcion].'',0,0,'L');
			$this->Ln(5);
			
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
			$this->SetFont('Arial','I',9);
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
	//$titulos = array('GUIA','DESTINATARIO','DESC./CONT.','PESO','PAGADO','COBRAR','EMB','DEFECTO');
	$medidas = array(25,70,70,20,22,25,15,20);
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',9);
	//Table with 20 rows and 4 columns
	
	$pdf->SetWidths($medidas);
	
	$pdf->Titulos($titulos,$medidas);
	
	$pdf->SetFont('Arial','',9);
	for($i=0;$i<count($data);$i++){
		$pdf->Row($data[$i]);
	}
	
		
	

		$pdf->MultiCell(0,5,'Contrato de prestación de servicios que celebra por una parte PAQUETERIA Y MENSAJERIA EN MOVIMIENTO, S.A. DE C.V, a quien en lo sucesivo se le denominará "La Prestadora de Servicios",      y      por    otra    parte  '.$f->ncliente.'   representada por  '.$f->representante.' a quien en lo sucesivo se le denominará "El Cliente".',0,'J');
		
		$pdf->MultiCell(0,5,'',0,'J');
		
		$pdf->MultiCell(0,10,'1.- La Prestadora de Servicios otorgará a "El Cliente" los siguientes servicios:',0,'J');
		
		$pdf->MultiCell(0,5,'		GUIAS VENTANILLA   				GUIAS ELECTRONICAS',0,'J');
		
		$pdf->MultiCell(0,10,'2.- La Prestadora de Servicios, otorgará a "El Cliente" los siguientes servicios adicionales:',0,'J');
			
		$pdf->MultiCell(0,5,'Entrega a Domicilio		______		Recolección a Domicilio		______',0,'J');
		$pdf->MultiCell(0,5,'Valor Declarado				______			Acuse de Recibo		______',0,'J');
		$pdf->MultiCell(0,5,'Entregas a Subdestinos		______				Guías prepagadas  ______',0,'J');
		
		$pdf->MultiCell(0,10,'3.- El precio del servicio de Recolección a Domicilio es un 10% sobre el valor del flete, considerando un cobro mínimo dependiendo de la Ciudad donde se origine el servicio (ver tarifas).',0,'J');
		
		$pdf->MultiCell(0,10,'4.- El precio del servicio de Entrega a Domicilio es un 10% sobre el valor del flete, considerando un cobro mínimo dependiendo de la Ciudad donde se envíe el servicio (ver tarifas).',0,'J');
		
		$pdf->MultiCell(0,5,'5.- El precio Valor Declarado es de $8.00 (Son Ocho Pesos 00/100 M.N.)  por millar, este seguro únicamente ampara riegos ordinarios de tránsito (incendio, accidente, asalto a mano armada). De ocurrir cualquiera de los eventos anteriores se cobrará un 20% de deducible sobre el valor declarado, de acuerdo con la póliza de seguros vigente. El máximo del Valor Declarado por guía o carta de porte es de $150,000.00 (Son Ciento cincuenta mil pesos 00/100 M.N)',0,'J');
		
		$pdf->MultiCell(0,5,'',0,'J');
		
		$pdf->MultiCell(0,5,'6.- El Acuse de Recibo tiene un costo de 15.00 (Son Quince pesos 00/100) por guía, el Cliente acepta que la falta de entrega de acuse no será una condicionante para la realización de pago de los envíos que realice con La Prestadora de Servicios.',0,'J');
		
		$pdf->MultiCell(0,5,'',0,'J');
		
		$pdf->MultiCell(0,5,'7.- Las Entregas a Subdestinos o poblados apartados donde no hay oficina PMM, a los que se va 1 o 2 veces por semana, tiene un costo adicional al precio conveniado (Ver Tarifas) por envío, en ningún caso será SIN COSTO.',0,'J');
		
		$pdf->MultiCell(0,5,'',0,'J');
		
		$pdf->MultiCell(0,5,'8.- El precio unitario de las Guías Prepagadas es $_______ amparando hasta 5 kilogramos cada una, se vende en paquetes mínimos de 30 guías. Estas guías incluyen el servicio de Recolección a Domicilio y Entrega a Domicilio, de excederse en los kilogramos el precio por kilogramo es de $_____________',0,'J');
		$pdf->MultiCell(0,5,'',0,'J');
		
	$pdf->MultiCell(0,10,'9.- El cliente dice que su consumo mensual estimado es de ___________________ en base a este consumo La Prestadora de Servicios ofrece la siguiente forma de cobro para sus envíos:',0,'J');
	
	$pdf->MultiCell(0,10,'			Precio por Kilogramo:___________ hasta 200 kms aumentando $______________ cada 200 kms',0,'J');
		
	$pdf->MultiCell(0,5,'			9.1.- Servicios sin costo para el cliente',0,'J');
	$pdf->MultiCell(0,5,'					Recolección a Domicilio				(Sobre lo capturado el Grid)',0,'J');
		
	$pdf->MultiCell(0,10,'El precio por kilogramo no aplica a las tarifas mínimas establecidas por la Prestadora de Servicios.',0,'J');
		
	$pdf->MultiCell(0,5,'10.- El Cliente acepta que para determinar el peso de sus envíos, se considerará el peso báscula o volumétrico (largo x alto x ancho), tomándose siempre el que resulte superior.',0,'J');
			$pdf->MultiCell(0,5,'',0,'J');
	$pdf->MultiCell(0,5,'11.- Si El Cliente solicita Guías Electrónicas se le asignarán un password, nip y los folios de las guías, los cuales podrá utilizar a través de nuestra página web, el uso del nip y los folios es responsabilidad de El Cliente.  La elaboración de una guía electrónica no implicará una responsabilidad de su contenido para la Prestadora de Servicios hasta que esta confirme que ha recibido las mercancías.',0,'J');
			$pdf->MultiCell(0,5,'',0,'J');
	$pdf->MultiCell(0,5,'12.- El Cliente solicita que se consideren las siguientes Restricciones en sus envíos.',0,'J');
				
	$pdf->MultiCell(0,5,'					No recibe por cobrar',0,'J');
				$pdf->MultiCell(0,5,'',0,'J');
	$pdf->MultiCell(0,5,'13.- El Precio otorgado al cliente en este contrato es en base al consumo estimado prometido en el punto 7, de no cumplir con el consumo mensual La Prestadora de Servicios podrá dar por terminado este convenio en cualquier momento.',0,'J');
	$pdf->MultiCell(0,5,'',0,'J');
		$pdf->MultiCell(0,5,'14.- En caso de no declarar valor "La Prestadora de Servicios" se compromete a cubrir por daños o estravío, lo establecido en el contrato de adhesión en su cláusula décima segunda de la guía expedida.',0,'J');
		$pdf->MultiCell(0,5,'',0,'J');
		
		$pdf->MultiCell(0,5,'15.- A todos nuestros servicios le será agregado el 16% del impuesto al Valor Agregado.',0,'J');
		$pdf->MultiCell(0,5,'',0,'J');
		$pdf->MultiCell(0,5,'16.- Estos precios pueden variar sin previo aviso durante la vigencia de este contrato.',0,'J');
		$pdf->MultiCell(0,5,'',0,'J');
		$pdf->MultiCell(0,5,'17.- En caso de presentar inconformidad respecto a la interpretación, aplicación o ejecución de este contrato, las partes se someten a la competencia de los tribunales del fuero común y a las leyes que rigan en la Ciudad de Mazatlán, Sinaloa, renunciando expresamente a cualquier fuero que por su domicilio presente o futuro pudiera corresponder.',0,'J');
		$pdf->MultiCell(0,10,'',0,'J');
		$pdf->MultiCell(0,5,'DATOS GENERALES DEL CLIENTE',0,'J');
		$pdf->MultiCell(0,5,'Nombre o Razón Social: '.$f->ncliente.'',0,'J');
		$pdf->MultiCell(0,5,'Domicilio Fiscal:  '.$f->calle.', #'.$f->numero.', '.$f->colonia.'',0,'J');
		$pdf->MultiCell(0,5,'RFC:  '.$f->rfc.'  Codigo Postal: '.$f->cp.'',0,'J');
		$pdf->MultiCell(0,5,'Teléfono(s):  '.$f->telefono.'  E-mail: '.$f->email.'',0,'J');
		
		$pdf->MultiCell(0,10,'',0,'J');
		$pdf->MultiCell(0,5,'Este convenio se celebra en la ciudad de '.$f->municipio.','.$f->estado.'  el '.$f->fechaac.' , con vencimiento el '.$f->fechavige.' (siempre será 31 diciembre del año en curso). Sujeto a revisión y aprobación del Departamento de Ventas corporativas en un lapso 72 horas hábiles.',0,'J');
		
		if($f->precioporkg==1 && $fx->c1>0 || $fx->c2>0 || $fx->c3>0){
		$pdf->setColor(.79,.67,.10);
		$pdf->ezText("<b>\nDATOS DEL CONVENIO PARA GUIAS DE VENTANILLA</b>",12,array('justification'=>'left'));
		$pdf->setColor(.25,.25,.25);	
		$pdf->ezText(" CONVENIO DE ".(($f->precioporkg==1)?"PRECIO POR KILOGRAMO":(($f->precioporcaja==1)?"PRECIO POR CAJA":"DESCUENTO SOBRE FLETE") ),
											10,array('justification'=>'left'));
		if($f->precioporkg==1){
			$s = "SELECT cconvenio_configurador_preciokg.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_preciokg 
			where tipo = 'CONVENIO' and idconvenio = '$_GET[folio]'
			GROUP BY zona";
			$rx = mysql_query($s,$l) or die($s);
			$cantcol = mysql_num_rows($rx)/2;
			$zona = 1;
			$data0 = array();
			$datap0 = array();
			$data1 = array();
			$datap1 = array();
			$columnasp0 = array();
			$columnasp1 = array();
			$options0 = array();
			$options1 = array();
			$datap0["ZONA0"] = "Prec Kg";
			$datap1["ZONA0"] = "Prec Kg";
			$columnasp0["ZONA0"] = "ZONA";
			$columnasp1["ZONA0"] = "ZONA";
			while($fx = mysql_fetch_object($rx)){
				if($zona<$cantcol){
					$columnasp0["ZONA$zona"] = "ZONA $zona\n$fx->zoi/$fx->zof";
					$datap0["ZONA$zona"] = "$ ".number_format($fx->valor,2,".",",");
					$options0["ZONA$zona"] = array('justification'=>'right');
				}else{
					$columnasp1["ZONA$zona"] = "ZONA $zona\n$fx->zoi/$fx->zof";
					$datap1["ZONA$zona"] = "$ ".number_format($fx->valor,2,".",",");
					$options1["ZONA$zona"] = array('justification'=>'right');
				}
				$zona++;
			}
			$data0[] = $datap0;
			$data1[] = $datap1;
			$columnas0[] = $columnasp0;
			$columnas1[] = $columnasp1;
			
			//print_r($data);
			$estilo0 = array('fontSize' => 8, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25), 'cols' => $options0);
			$estilo1 = array('fontSize' => 8, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25), 'cols' => $options1);
			
			//print_r($estilo0);
			
			$pdf->ezText("",8);
			$pdf->ezTable($data0,$columnasp0,'',$estilo0);
			$pdf->ezText("",8);
			$pdf->ezTable($data1,$columnasp1,'',$estilo1);
		}
		//servicios gratuitos
		$s = "SELECT servicio, cobro, precio FROM cconvenio_servicios WHERE idconvenio = $_GET[folio] and tipo = 'CONVENIO'";
		$rx = mysql_query($s,$l) or die($s);
		$servgrat = "";
		while($fx = mysql_fetch_object($rx)){
			$servgrat .= (($servgrat!="")?", ":"").$fx->servicio;
		}
		if(mysql_num_rows($rx)>0){
			$pdf->ezText("",4);
			$pdf->ezText(strtoupper(" EL CLIENTE TENDRA LOS SIGUIENTES SERVICIOS GRATUITOS: $servgrat"),10,array('justification'=>'left'));
		}
		//sucursales
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[folio] AND tipo = 'SUCONVENIO'";
		$rx = mysql_query($s,$l) or die($s);
		$sucgrat = "";
		while($fx = mysql_fetch_object($rx)){
			$sucgrat .= (($sucgrat!="")?", ":"").$fx->nombre;
		}
		if(mysql_num_rows($rx)>0){
			$pdf->ezText("",4);
			$pdf->ezText(strtoupper(" LOS SERVICIOS GRATUITOS APLICARAN EN LAS SIGUIENTES SUCURSALES: $sucgrat"),10,array('justification'=>'left'));
		}
		//servicios restringidos
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[folio] AND tipo = 'SRCONVENIO'";
		$rx = mysql_query($s,$l) or die($s);
		$servrest = "";
		while($fx = mysql_fetch_object($rx)){
			$servrest .= (($servrest!="")?", ":"").$fx->nombre;
		}
		if(mysql_num_rows($rx)>0){
			$pdf->ezText("",4);
			$pdf->ezText(strtoupper(" EL CLIENTE TIENE LOS SIGUIENTES SERVICIOS RESTRINGIDOS: $servrest"),10,array('justification'=>'left'));
		}
		
		/*
		SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = 1 AND tipo = 'SRCONVENIO'
		SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = 1 AND tipo = 'SUCONVENIO'
		*/
	}
		
		
			if($f->consignacionkg==1 || $fx->c1>0 || $fx->c2>0 || $fx->c3>0){
		$pdf->setColor(.79,.67,.10);
		$pdf->ezText("<b>\nDATOS DEL CONVENIO PARA GUIAS EMPRESARIALES</b>",12,array('justification'=>'left'));
		$pdf->ezText("",4);
		$pdf->setColor(.25,.25,.25);
		if($f->prepagadas==1){
			$pdf->ezText(" EL CLIENTE TIENE SERVICIO DE GUIAS PREPAGADAS CON UN COSTO DE $".number_format($f->costo,2,".",",").". SI EXCEDE EL LIMITE DE $f->limitekg KG SE COBRARA POR CADA KG EXTRA $".number_format($f->preciokgexcedente,2,".",","),
			10,array('justification'=>'left'));
			$pdf->ezText("",4);
		}
		$pdf->ezText(" CONVENIO DE ".(($f->consignacionkg==1)?"PRECIO POR KILOGRAMO":(($f->consignacioncaja==1)?"PRECIO POR CAJA":"DESCUENTO SOBRE FLETE") ),
											10,array('justification'=>'left'));
		
		
		if($f->consignacionkg==1){
			$s = "SELECT cconvenio_configurador_preciokg.*, kmi as zoi, kmf as zof FROM cconvenio_configurador_preciokg 
			where tipo = 'CONSIGNACION' and idconvenio = '$_GET[folio]'
			GROUP BY zona";
			$rx = mysql_query($s,$l) or die($s);
			$cantcol = mysql_num_rows($rx)/2;
			$zona = 1;
			$data0 = array();
			$datap0 = array();
			$data1 = array();
			$datap1 = array();
			$columnasp0 = array();
			$columnasp1 = array();
			$options0 = array();
			$options1 = array();
			$datap0["ZONA0"] = "Prec Kg";
			$datap1["ZONA0"] = "Prec Kg";
			$columnasp0["ZONA0"] = "ZONA";
			$columnasp1["ZONA0"] = "ZONA";
			while($fx = mysql_fetch_object($rx)){
				if($zona<$cantcol){
					$columnasp0["ZONA$zona"] = "ZONA $zona\n$fx->zoi/$fx->zof";
					$datap0["ZONA$zona"] = "$ ".number_format($fx->valor,2,".",",");
					$options0["ZONA$zona"] = array('justification'=>'right');
				}else{
					$columnasp1["ZONA$zona"] = "ZONA $zona\n$fx->zoi/$fx->zof";
					$datap1["ZONA$zona"] = "$ ".number_format($fx->valor,2,".",",");
					$options1["ZONA$zona"] = array('justification'=>'right');
				}
				$zona++;
			}
			$data0[] = $datap0;
			$data1[] = $datap1;
			$columnas0[] = $columnasp0;
			$columnas1[] = $columnasp1;
			
			//print_r($data);
			$estilo0 = array('fontSize' => 8, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25), 'cols' => $options0);
			$estilo1 = array('fontSize' => 8, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25), 'cols' => $options1);
			
			//print_r($estilo0);
			
			$pdf->ezText("",8);
			$pdf->ezTable($data0,$columnasp0,'',$estilo0);
			$pdf->ezText("",8);
			$pdf->ezTable($data1,$columnasp1,'',$estilo1);
		}
		//servicios gratuitos
		$s = "SELECT servicio, cobro, precio FROM cconvenio_servicios WHERE idconvenio = $_GET[folio] and tipo = 'CONSIGNACION'";
		$rx = mysql_query($s,$l) or die($s);
		$servgrat = "";
		while($fx = mysql_fetch_object($rx)){
			$servgrat .= (($servgrat!="")?", ":"").$fx->servicio;
		}
		if(mysql_num_rows($rx)>0){
			$pdf->ezText("",4);
			$pdf->ezText(strtoupper(" EL CLIENTE TENDRA LOS SIGUIENTES SERVICIOS GRATUITOS: $servgrat"),10,array('justification'=>'left'));
		}
		//sucursales
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[folio] AND tipo = 'SUCONSIGNACION2'";
		$rx = mysql_query($s,$l) or die($s);
		$sucgrat = "";
		while($fx = mysql_fetch_object($rx)){
			$sucgrat .= (($sucgrat!="")?", ":"").$fx->nombre;
		}
		if(mysql_num_rows($rx)>0){
			$pdf->ezText("",4);
			$pdf->ezText(strtoupper(" LOS SERVICIOS GRATUITOS APLICARAN EN LAS SIGUIENTES SUCURSALES: $sucgrat"),10,array('justification'=>'left'));
		}
		//servicios restringidos
		$s = "SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = $_GET[folio] AND tipo = 'SRCONSIGNACION'";
		$rx = mysql_query($s,$l) or die($s);
		$servrest = "";
		while($fx = mysql_fetch_object($rx)){
			$servrest .= (($servrest!="")?", ":"").$fx->nombre;
		}
		if(mysql_num_rows($rx)>0){
			$pdf->ezText("",4);
			$pdf->ezText(strtoupper(" EL CLIENTE TIENE LOS SIGUIENTES SERVICIOS RESTRINGIDOS: $servrest"),10,array('justification'=>'left'));
		}
		
		/*
		SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = 1 AND tipo = 'SRCONVENIO'
		SELECT clave,nombre,tipo FROM cconvenio_servicios_sucursales WHERE idconvenio = 1 AND tipo = 'SUCONVENIO'
		*/
	}
	
	$pdf->Output();
?>
