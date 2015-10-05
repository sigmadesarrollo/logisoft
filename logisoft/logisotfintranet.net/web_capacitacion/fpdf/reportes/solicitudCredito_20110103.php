<?	require_once('../fpdf.php');
	//require_once('../../fpdi/fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT folio,DATE_FORMAT(fechasolicitud,'%d/%m/%Y') AS fechasolicitud,
	estado,folioconvenio,DATE_FORMAT(fechaautorizacion,'%d/%m/%Y') AS fechaautorizacion,
	DATE_FORMAT(fechaactivacion,'%d/%m/%Y') AS fechaactivacion,solicitante,personamoral,cliente,nick,
	CONCAT_WS(' ',nombre,paterno,materno) AS nombrecliente,rfc,calle,numero,cp,colonia,
	poblacion,municipio,estadoc,pais,celular,telefono,
	email,giro,antiguedad,representantelegal,actaconstitutiva,
	numeroacta,fechaescritura,fechainscripcion,identificacionlegal,
	numeroidentificacion,hacienda,fechainiciooperaciones,rfc2,comprobante,
	comprobanteluz,estadocuenta,banco,cuenta,solicitud,semanapago,lunespago,
	martespago,miercolespago,juevespago,viernespago,sabadopago,horariopago,apago,
	responsablepago,celularpago,telefonopago,faxpago,
	semanarevision,lunesrevision,martesrevision,miercolesrevision,juevesrevision,
	viernesrevision,sabadorevision,horariorevision,arevision,montosolicitado,
	montoautorizado,diascredito,observaciones,usuario,idusuario,idsucursal,fecha FROM solicitudcredito
	WHERE folio = ".$_GET[credito]."";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$diarevision = "";
	$diapago = "";
	if($f->semanarevision==1){
		$diarevision = "L,M,MI,J,V,S";
	}else{
		if($f->lunesrevision==1){
			$diarevision = "L,";
		}
		if($f->martesrevision==1){
			$diarevision = "M,";
		}
		if($f->miercolesrevision==1){
			$diarevision = "MI,";
		}
		if($f->juevesrevision==1){
			$diarevision = "J,";
		}
		if($f->viernesrevision==1){
			$diarevision = "V,";
		}
		if($f->sabadorevision==1){
			$diarevision = "S,";
		}
		$diarevision = substr($diarevision,0,strlen($diarevision)-1);
	}
	
	if($f->semanapago==1){
		$diapago = "L,M,MI,J,V,S";
	}else{
		if($f->lunespago==1){
			$diapago = "L,";
		}
		if($f->martespago==1){
			$diapago = "M,";
		}
		if($f->miercolespago==1){
			$diapago = "MI,";
		}
		if($f->juevespago==1){
			$diapago = "J,";
		}
		if($f->viernespago==1){
			$diapago = "V,";
		}
		if($f->sabadopago==1){
			$diapago = "S,";
		}
		$diapago = substr($diapago,0,strlen($diapago)-1);
	}
	
	class pdf extends FPDF{
		function Header(){
			$this->SetFont('Arial','B',15);
			//$this->Cell(70,10,'PAQUETERIA Y MENSAJERIA EN MOVIMIENTO',0,0,'C');
			$this->Ln(20);
			//Logo
			$this->Image('cabecera2.jpg',10,15,180);
			
		}
		
		/*function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}*/
		
		function addLeyenda($ref,$posicion){
			$this->SetFont( "Arial", "B", 8);
			$length = $this->GetStringWidth($ref);
			$r1  = 10;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function addLeyenda3($ref,$posicion){
			$this->SetFont( "Arial", "B", 7);
			$length = $this->GetStringWidth($ref);
			$r1  = 10;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function addLeyenda2($ref,$posicion,$posicion2){
			$this->SetFont( "Arial", "B", 8);
			$length = $this->GetStringWidth($ref);
			$r1  = $posicion2;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function agregarLinea($ancho,$alto,$x,$y){
			$r1  = $this->w - $ancho;
			$r2  = $r1 + $x;
			$y1  = $this->h - $alto;
			$y2  = $y1 + $y;
			$this->Line($r1, $y1, $r2, $y1);
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
			$this->SetFont("Arial", "B", 7);
			$this->Cell(10,5, utf8_decode($titulo), 0, 0, "C");
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
			$this->SetFont( "Arial", "B", 7);
			$this->Cell(10,5, utf8_decode($mode), 0, 0, "C");
		}
		
		function obtenerFecha(){
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
			
			return "MAZATLAN SINALOA ".date("d")." DE ".$mes." DE ".date("Y")."";
		}
	}
	
	$pdf = new PDF();
	//$pdf -> AliasNbPages();
	$pdf -> AddPage();
	//INFORMACION GENERAL
	$pdf -> Image('cab2_informacion_general.jpg',10,42,180);
	$pdf -> addLeyenda("Nombre o Razón Social: ".$f->nombrecliente."",50);
	$pdf -> agregarLinea(166,243,140,10);
	$pdf -> addLeyenda("Giro: ".$f->giro."",55);
	$pdf -> agregarLinea(192,238,65,10);
	$pdf -> addLeyenda2("R.F.C.: ".$f->rfc."",55,85);
	$pdf -> agregarLinea(114,238,30,10);
	$pdf -> addLeyenda2("Antigüedad: ".$f->antiguedad."",55,130);
	$pdf -> agregarLinea(62,238,36,10);
	$pdf -> addLeyenda("DOMICILIO FISCAL",60);
	$pdf -> addLeyenda("Calle: ".$f->calle."",65);
	$pdf -> agregarLinea(191,228,100,10);
	$pdf -> addLeyenda2("No. Ext.: ".$f->numero."",65,120);
	$pdf -> agregarLinea(77,228,51,10);
	$pdf -> addLeyenda("Colonia: ".$f->colonia."",70);
	$pdf -> agregarLinea(187,223,161,10);
	$pdf -> addLeyenda("CP: ".$f->cp."",75);
	$pdf -> agregarLinea(193,218,8,10);
	$pdf -> addLeyenda2("Ciudad: ".$f->poblacion."",75,25);
	$pdf -> agregarLinea(173,218,85,10);
	$pdf -> addLeyenda2("Estado: ".(($f->estadoc=="VERACRUZ DE IGNACIO DE LA LLAVE")?"VERACRUZ":$f->estadoc)."",75,122);
	$pdf -> agregarLinea(76,218,50,10);
	
	//REFERENCIAS BANCARIAS Y COMERCIALES
	$pdf -> Image('cab2_referencias_bancarias_comerciales.jpg',10,80,180);
	
	$s = "SELECT banco, sucursal, cuenta FROM solicitudcreditobancodetalle 
	WHERE solicitud = ".$_GET[credito]." LIMIT 2";
	$r = mysql_query($s,$l) or die($s);	
	$totalbanco = mysql_num_rows($r);
	
	$s = "SELECT empresa, contacto, telefono FROM solicitudcreditocomercialesdetalle 
	WHERE solicitud = ".$_GET[credito]." LIMIT 2";
	$rr = mysql_query($s,$l) or die($s);
	$totalcomercial = mysql_num_rows($rr);

	$pdf -> addLeyenda("A) BANCARIA",88);	
	
	$posicionTexto = 93;
	$posicionLineaY = 200;
	
	if($totalbanco > 0){
		while($ff = mysql_fetch_object($r)){
			$pdf -> addLeyenda("Banco: ".$ff->banco."",$posicionTexto);
			$pdf -> agregarLinea(189,$posicionLineaY,38,10);
			$pdf -> addLeyenda2("Sucursal: ".$ff->sucursal."",$posicionTexto,60);
			$pdf -> agregarLinea(136,$posicionLineaY,60,10);
			$pdf -> addLeyenda2("Cuenta: ".$ff->cuenta."",$posicionTexto,135);
			$pdf -> agregarLinea(62,$posicionLineaY,36,10);
			$posicionLineaY = $posicionLineaY - 5;
			$posicionTexto = $posicionTexto + 5;
		}
			$posicionLineaY = $posicionLineaY - 5;
			$posicionTexto = $posicionTexto + 5;
			$pdf -> addLeyenda("A) COMERCIAL",(($totalbanco > 1)? 103 : 98));
	}else{
			$pdf -> addLeyenda("Banco: ",$posicionTexto);
			$pdf -> agregarLinea(189,$posicionLineaY,38,10);
			$pdf -> addLeyenda2("Sucursal: ",$posicionTexto,60);
			$pdf -> agregarLinea(136,$posicionLineaY,60,10);
			$pdf -> addLeyenda2("Cuenta: ",$posicionTexto,135);
			$pdf -> agregarLinea(62,$posicionLineaY,36,10);
			$posicionLineaY = $posicionLineaY - 10;
			$posicionTexto = $posicionTexto + 10;
			$pdf -> addLeyenda("A) COMERCIAL",98);
	}
	
	if($totalcomercial > 0){
		while($ff = mysql_fetch_object($rr)){
			$pdf -> addLeyenda("Empresa: ".$ff->empresa."",$posicionTexto);
			$pdf -> agregarLinea(186,$posicionLineaY,55,10);
			$pdf -> addLeyenda2("Contacto: ".$ff->contacto."",$posicionTexto,80);
			$pdf -> agregarLinea(115,$posicionLineaY,55,10);
			$pdf -> addLeyenda2("Tel.: ".$ff->telefono."",$posicionTexto,150);
			$pdf -> agregarLinea(53,$posicionLineaY,27,10);
			$posicionLineaY = $posicionLineaY - 5;
			$posicionTexto = $posicionTexto + 5;
		}
			$posicionLineaY = $posicionLineaY - 5;
			$posicionTexto = $posicionTexto + 5;
	}else{
			$pdf -> addLeyenda("Empresa: ",$posicionTexto);
			$pdf -> agregarLinea(186,$posicionLineaY,55,10);
			$pdf -> addLeyenda2("Contacto: ",$posicionTexto,80);
			$pdf -> agregarLinea(115,$posicionLineaY,55,10);
			$pdf -> addLeyenda2("Tel.: ",$posicionTexto,150);
			$pdf -> agregarLinea(53,$posicionLineaY,27,10);
			$posicionLineaY = $posicionLineaY - 10;
			$posicionTexto = $posicionTexto + 10;
	}
	//INFORMACION DE REVISION Y PAGOS
	$posicionTexto = $posicionTexto - 5;
	$totalregistros = $totalbanco + $totalcomercial;
	
	$posicionLineaY = (($totalregistros == 4)?168:173);
	
	$pdf -> Image('cab2_informacion_revision_pagos.jpg',10,$posicionTexto,180);
	$pdf -> addLeyenda("Persona(s) Autorizada(s) Para Tomar a Revisión: ",($posicionTexto+7));	
	
	$s = "SELECT persona FROM solicitudcreditopersonadetalle WHERE solicitud = ".$_GET[credito]." LIMIT 2";
	$rr = mysql_query($s,$l) or die($s);
	
	$posicionTexto = $posicionTexto + 7;	
	$posicionLineaY = (($posicionLineaY == 173)?$posicionLineaY+5:$posicionLineaY);	
	$posicionLineaY = (($totalregistros == 3)? $posicionLineaY - 5 : $posicionLineaY);
	
	if(mysql_num_rows($rr)>0){
		while($ff = mysql_fetch_object($rr)){
			$pdf -> addLeyenda2($ff->persona,$posicionTexto,77);
			$pdf -> agregarLinea(133,$posicionLineaY,107,10);
			$posicionTexto = $posicionTexto + 5;
			$posicionLineaY = $posicionLineaY - 5;
		}
		
		if(mysql_num_rows($rr)==1){
			$pdf -> addLeyenda2("",$posicionTexto,77);
			$pdf -> agregarLinea(133,$posicionLineaY,107,10);
		}
		
		$posicionLineaY = $posicionLineaY - 5;
		$posicionTexto = $posicionTexto + 5;
	}else{
		$pdf -> addLeyenda2("",$posicionTexto,77);
		$pdf -> agregarLinea(133,$posicionLineaY,107,10);
		
		$pdf -> addLeyenda2("",($posicionTexto+5),77);
		$pdf -> agregarLinea(133,($posicionLineaY - 5),107,10);
	}
	
	$posicionTexto = (($posicionLineaY==178) ? $posicionTexto + 10 : $posicionTexto);
	$posicionTexto = (($posicionLineaY==153) ? $posicionTexto - 5  : $posicionTexto);
	$posicionTexto = (($totalregistros == 3) ? $posicionTexto : $posicionTexto);

	$posicionLineaY = (($posicionLineaY==178 && $posicionTexto==125) ? 168 : $posicionLineaY);
	
	$pdf -> addLeyenda("Días de Revisión: ".$diarevision."",$posicionTexto);
	$pdf -> agregarLinea(175,$posicionLineaY,25,10);
	$pdf -> addLeyenda2("Horario: ".$f->horariorevision." ".((substr($f->horariorevision,0,strlen($f->horariorevision)-3) >12)?"pm":"am")."",$posicionTexto,60);
	$pdf -> agregarLinea(138,$posicionLineaY,20,10);
	$pdf -> addLeyenda2("Días de Pago: ".$diapago."",$posicionTexto,100);
	$pdf -> agregarLinea(90,$posicionLineaY,25,10);
	$pdf -> addLeyenda2("Horario: ".$f->horariopago." ".((substr($f->horariopago,0,strlen($f->horariopago)-3) >12)?"pm":"am")."",$posicionTexto,155);
	$pdf -> agregarLinea(43,$posicionLineaY,17,10);
	$pdf -> addLeyenda("Formas de Pago: ".$f->formaspago."",($posicionTexto+5));
	$pdf -> agregarLinea(175,($posicionLineaY - 5),149,10);
	$pdf -> addLeyenda("Encargado(a) de Pagos: ".$f->responsablepago."",($posicionTexto+10));
	$pdf -> agregarLinea(166,($posicionLineaY - 10),85,10);
	$pdf -> addLeyenda2("Tel.: ".$f->telefonopago."",($posicionTexto+10),130);	
	$pdf -> agregarLinea(73,($posicionLineaY - 10),47,10);
	$pdf -> addLeyenda("Email: ".$f->emailrevision."",($posicionTexto+15));
	$pdf -> agregarLinea(190,($posicionLineaY - 15),80,10);
	
	//AUTORIZACION PARA TRAMITE DE CREDITO
	$pdf -> Image('cab2_autorizacion_tramite_credito.jpg',10,($posicionTexto+20),180);
	$pdf -> addLeyenda("Monto Solicitado: $".number_format($f->montosolicitado,2,".",",")."",($posicionTexto+27));
	$pdf -> agregarLinea(175,($posicionLineaY - 27),30,10);
	$pdf -> addLeyenda2("Monto Autorizado: $".number_format($f->montoautorizado,2,".",",")."",($posicionTexto+27),95);
	$pdf -> agregarLinea(90,($posicionLineaY - 27),30,10);
	$pdf -> addLeyenda("Observaciones: ".$f->observaciones."",($posicionTexto+32));
	$pdf -> agregarLinea(177,($posicionLineaY - 32),151,10);
	$pdf -> addLeyenda("EL PLAZO DE CREDITO ES A PARTIR DE LA FECHA DE EMISION DE LA GUIA.",($posicionTexto+37));
	$pdf -> SetFont("Arial","B",7);
	$pdf -> Ln(5);
	$pdf -> MultiCell(177,3,'POR ESTE CONDUCTO AUTORIZO A ENTREGAS PUNTUALES S DE RL DE CV PARA QUE LLEVE A CABO LAS INVESTIGACIONES Y MONITOREO CREDITICIO QUE JUZGUE CONVENIENTE DE LA EMPRESA QUE REPRESENTO, ASI MISMO, DECLARO QUE CONOZCO LA NATURALEZA Y ALCANCE DE LA INFORMACION QUE SE SOLICITARA Y BAJO PROTESTA DE DECIR VERDADA, MANIFIESTO SER REPRESENTANTE LEGAL DE LA EMPRESA MENCIONADA EN ESTE CONTRATO DE CREDITO DE PRESTACION DE SERVICIOS.	
RATIFICO LO AQUI ASENTADO COMO VERDADERO Y ACEPTO A MI ENTERA CONFORMIDAD, QUE SI ENTREGAS PUNTUALES S DE RL DE CV AUTORIZA LA PRESENTE SOLICITUD DE CREDITO, ME SUJETARE A SUS POLITICAS DE CREDITO Y COBRANZA. ASI MISMO, A SUS TARIFAS DE PRECIO EN VIGENCIA Y A LAS MODIFICACIONES QUE OPEREN EN EL FUTURO, EN CASO DE INCUMPLIMIENTO EN LOS PAGOS, ME OBLIGO A PAGAR A LA ORDEN DE ENTREGAS PUNTUALES S DE RL DE CV DONDE SE ME REQUIERA EL IMPORTE VENCIDO DE LOS DOCUMENTOS (CONTRA-RECIBOS Y/O FACTURAS) EXHIBIDOS POR P.M.M., S.A. DE C.V. SEGUN EL PLAZO DE CREDITO OTORGADO. DE SER AUTORIZADA ESTA SOLICITUD, ES NECESARIO PRESENTAR LA SIGUIENTE DOCUMENTACION: COPIA DE ACTA CONSTITUTIVA, COPIA DEL REPRESENTANTE LEGAL (FACULTADO PARA SUSCRIBIR TITULOS DE CREDITO), COPIA DEL REGISTRO FEDERAL DE CAUSANTES Y COPIA DE LOS ESTADOS FINANCIEROS ACTUALES. EN CASO DE PRESENTAR INCONFORMIDAD, RESPECTO A LA INTERPRETACION, APLICACION O EJECUCION DE ESTE CONTRATO LAS PARTES RECONOCEN LA COMPETENCIA DENTRO DE LA ESFERA DE ATRIBUCCIONES. QUE LA LEY OTORGA A LA PROCURADURIA FEDERAL DEL CONSUMIDOR, CONVENIENDO, ADEMAS EN SOMETER A LA COMPETENCIA DE LOS TRIBUNALES DEL FUERO COMUN Y DE LAS LEYES QUE RIGEN EN LA CIUDAD DE MAZATLAN, SINALOA; RENUNCIANDO EXPRESAMENTE A CUALQUIER OTRO FUERO QUE POR SU DOMICILIO PRESENTE O FUTURO PUDIERA CORRESPONDER.',0,'J');
	$pdf -> Ln(5);
	$pdf -> addFirma("NOMBRE Y FIRMA",30,230,"REPRESENTANTE LEGAL DE LA EMPRESA SOLICITANTE",75);
	$pdf -> addFirma("NOMBRE Y FIRMA",120,230,"CREDITO Y COBRANZA PMM",60);
	$pdf -> Ln(20);
	$pdf -> addLeyenda("LUGAR Y FECHA DE AUTORIZACION: ".$pdf -> obtenerFecha()."",250);
	$pdf -> agregarLinea(148,43,70,10);
	$pdf -> addLeyenda2("NUM. CLIENTE: ".$f->cliente."",250,135);
	$pdf -> agregarLinea(53,43,28,10);
	$pdf -> Ln(10);
	$pdf -> SetFont("Arial","",5);
	$pdf -> MultiCell(177,3,'FRANCISCO SERRANO 2316-306 MAZATLAN, SIN. C.P. 82000 TEL.(669)985-48-11 FAX:(669)985-48-12 E-mail: credycob@pmm.com.mx
	***El pago de los créditos deberá realizarse independientemente de quien reciba y firme en los envios.',0,'J');
	
	$pdf -> Output();
?>