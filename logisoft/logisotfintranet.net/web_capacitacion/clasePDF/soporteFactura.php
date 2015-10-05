<?php
	require_once("../ConectarSolo.php");
	$l = Conectarse("webpmm");
	
	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
	
	include ('class.ezpdf.php');
	$pdf = new Cezpdf('LETTER','portrait');
	#					 t, b, l, r
	$pdf -> ezSetMargins(50,70,50,50);
	#w = 612 h = 792
	
	$pdf->setColor(.16,.38,.61);
	$pdf->selectFont('fonts/Helvetica.afm');
	$img = ImageCreatefromjpeg('../img/logo.jpg');
	$pdf->addImage($img,50,697,47,50);
	$pdf->addText(110,722,24,'<b>ENTREGAS PUNTUALES</b>');
	$pdf->setColor(.79,.67,.11);
	$pdf->addText(110,702,16,'<b>Soporte de Factura '.$_GET[folio].'</b>');
	$pdf->line(50,690,560,690);
	$pdf->setColor(.25,.25,.25);
	$pdf->ezText("\n\n\n\n$f->fechaactual",12,array('justification'=>'right'));
	
	#detallado superior
	$s = "SELECT fd.*,SUBSTRING(CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno),1,25) cliente,gv.totalpaquetes,
		DATE_FORMAT(fd.fecha,'%d/%m/%Y') AS fecha
		FROM facturadetalle fd
		INNER JOIN guiasventanilla gv ON fd.folio=gv.id
		INNER JOIN catalogocliente cc ON gv.iddestinatario=cc.id
		WHERE fd.factura = '$_GET[folio]'
		UNION
		SELECT fd.*,SUBSTRING(CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno),1,25) cliente,ge.totalpaquetes,
		DATE_FORMAT(fd.fecha,'%d/%m/%Y') AS fecha
		FROM facturadetalle fd
		INNER JOIN guiasempresariales ge ON fd.folio=ge.id
		INNER JOIN catalogocliente cc ON ge.iddestinatario=cc.id
		WHERE fd.factura = '$_GET[folio]'";
	$rx = mysql_query($s,$l) or die($s);
	$registros=mysql_num_rows($rx);
	if($registros>0){
		
		$pdf->setColor(.79,.67,.11);
		$pdf->ezText("<b>DETALLADO DE FACTURA, GUIAS</b>\n",12,array('justification'=>'left'));
		$pdf->setColor(.25,.25,.25);
	
		$s = "SELECT fd.*,SUBSTRING(CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno),1,25) cliente,gv.totalpaquetes,
		DATE_FORMAT(fd.fecha,'%d/%m/%Y') AS fecha
		FROM facturadetalle fd
		INNER JOIN guiasventanilla gv ON fd.folio=gv.id
		INNER JOIN catalogocliente cc ON gv.iddestinatario=cc.id
		WHERE fd.factura = '$_GET[folio]'
		UNION
		SELECT fd.*,SUBSTRING(CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno),1,25) cliente,ge.totalpaquetes,
		DATE_FORMAT(fd.fecha,'%d/%m/%Y') AS fecha
		FROM facturadetalle fd
		INNER JOIN guiasempresariales ge ON fd.folio=ge.id
		INNER JOIN catalogocliente cc ON ge.iddestinatario=cc.id
		WHERE fd.factura = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		$col = array('0'=>"Folio",'1'=>'Destinatario','2'=>'# Paq','3'=>'Fecha','4'=>'Flete','5'=>'Desc','6'=>'Exced','7'=>'EAD',
		'8'=>'Recol','9'=>'Seguro', '10'=>'Combus', '11'=>'Otros', '12'=>'Subtotal','13'=>'Iva','14'=>'Iva Ret','15'=>'Total');
	
		$opt = array('0'=>array('justification'=>'center','width'=>'47'),'1'=>array('justification'=>'left','width'=>'90'),	
		'2'=>array('justification'=>'center','width'=>'20'),'3'=>array('justification'=>'center'),'4'=>array('justification'=>'right'),
		'5'=>array('justification'=>'right'),'6'=>array('justification'=>'right'),'7'=>array('justification'=>'right'), 
		'8'=>array('justification'=>'right'),'9'=>array('justification'=>'right'),'10'=>array('justification'=>'right'),
		'11'=>array('justification'=>'right'),'12'=>array('justification'=>'right'),'13'=>array('justification'=>'right'),
		'14'=>array('justification'=>'right'),'15'=>array('justification'=>'right'));
		
		$est = array('fontSize' => 5, 'showHeadings' => 1, 'lineCol' => array(0,0,0),'colGap'=>1,'cols'=>$opt,'width'=>560);
		
		$data = array();
		$tflete = 0;
		$tcantidaddescuento = 0;
		$texcedente = 0;
		$tcostoead = 0;
		$tcostorecoleccion = 0;
		$tcostoseguro = 0;
		$tcostocombustible = 0;
		$totros = 0;
		$tsubtotal = 0;
		$tiva = 0;
		$tivaretenido = 0;
		$ttotal = 0;
		while($f = mysql_fetch_object($r)){
			$da = array();
			$da[]=$f->folio;
			$da[]=$f->cliente;
			$da[]=$f->totalpaquetes;
			$da[]=$f->fecha;
			$da[]="$ ".number_format($f->flete,2,".",",");
			$da[]="$ ".number_format($f->cantidaddescuento,2,".",",");
			$da[]="$ ".number_format($f->excedente,2,".",",");
			$da[]="$ ".number_format($f->costoead,2,".",",");
			$da[]="$ ".number_format($f->costorecoleccion,2,".",",");
			$da[]="$ ".number_format($f->costoseguro,2,".",",");
			$da[]="$ ".number_format($f->costocombustible,2,".",",");
			$da[]="$ ".number_format($f->otros,2,".",",");
			$da[]="$ ".number_format($f->subtotal,2,".",",");
			$da[]="$ ".number_format($f->iva,2,".",",");
			$da[]="$ ".number_format($f->ivaretenido,2,".",",");
			$da[]="$ ".number_format($f->total,2,".",",");
			
			$tflete += $f->flete;
			$tcantidaddescuento += $f->cantidaddescuento;
			$texcedente += $f->excedente;
			$tcostoead += $f->costoead;
			$tcostorecoleccion += $f->costorecoleccion;
			$tcostoseguro += $f->costoseguro;
			$tcostocombustible += $f->costocombustible;
			$totros += $f->otros;
			$tsubtotal += $f->subtotal;
			$tiva += $f->iva;
			$tivaretenido += $f->ivaretenido;
			$ttotal += $f->total;
			
			$data[]=$da;
		}
		//calculando totales
		$da = array();
			$da[]='Totales';
			$da[]='';
			$da[]='';
			$da[]='';
			$da[]="$ ".number_format($tflete,2,".",",");
			$da[]="$ ".number_format($tcantidaddescuento,2,".",",");
			$da[]="$ ".number_format($texcedente,2,".",",");
			$da[]="$ ".number_format($tcostoead,2,".",",");
			$da[]="$ ".number_format($tcostorecoleccion,2,".",",");
			$da[]="$ ".number_format($tcostoseguro,2,".",",");
			$da[]="$ ".number_format($tcostocombustible,2,".",",");
			$da[]="$ ".number_format($totros,2,".",",");
			$da[]="$ ".number_format($tsubtotal,2,".",",");
			$da[]="$ ".number_format($tiva,2,".",",");
			$da[]="$ ".number_format($tivaretenido,2,".",",");
			$da[]="$ ".number_format($ttotal,2,".",",");
			$data[]=$da;
		
		$pdf->ezTable($data,$col,'', $est);
		$pdf->ezText("",10,array('justification'=>'left'));
	}
	
	$s = "SELECT * FROM facturadetalleguias WHERE factura = $_GET[folio]";
	$rx = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($rx)>0){
		//detallado de excedente y valor declarado
		$pdf->setColor(.79,.67,.11);
		$pdf->ezText("<b>DETALLADO DE FACTURA, EXCEDENTE Y VALOR DECLARADO</b>\n",12,array('justification'=>'left'));
		$pdf->setColor(.25,.25,.25);
		
		#detallado inferior
		$s = "SELECT *, date_format(fechaguia,'%d/%m/%Y') as fechaguia FROM facturadetalleguias WHERE factura = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$col = array('0'=>"Folio",'1'=>'Tipo Guia',	'2'=>'Concepto','3'=>'Seguro','4'=>'Exced',
		'5'=>'Fecha', '6'=>'Importe', '7'=>'Iva', '8'=>'Iva Ret', '9'=>'Total');
		
		$opt = array('0'=>array('justification'=>'center'),'1'=>array('justification'=>'center'),	
		'2'=>array('justification'=>'left'),'3'=>array('justification'=>'right'),'4'=>array('justification'=>'right'),
		'5'=>array('justification'=>'right'), '6'=>array('justification'=>'right'), '7'=>array('justification'=>'right'), 
		'8'=>array('justification'=>'right'), '9'=>array('justification'=>'right'));
		$est = array('fontSize' => 6, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25),'cols'=>$opt);
		
		$data = array();
		$tseguro = 0;
		$texcedente = 0;
		$timporte = 0;
		$tiva = 0;
		$tivaretenido = 0;
		$ttotal = 0;
		while($f = mysql_fetch_object($r)){
			$da = array();
			$da[]=$f->guia;
			$da[]=$f->tipoguia;
			$da[]=$f->concepto;
			$da[]="$ ".number_format($f->tseguro,2,".",",");
			$da[]="$ ".number_format($f->texcedente,2,".",",");
			$da[]=$f->fechaguia;
			$da[]="$ ".number_format($f->subtotal,2,".",",");
			$da[]="$ ".number_format($f->tiva,2,".",",");
			$da[]="$ ".number_format($f->ivaretenido,2,".",",");
			$da[]="$ ".number_format($f->total,2,".",",");
			
			$tseguro += $f->tseguro;
			$texcedente += $f->texcedente;
			$timporte += $f->subtotal;
			$tiva += $f->tiva;
			$tivaretenido += $f->ivaretenido;
			$ttotal += $f->total;
			
			$data[]=$da;
		}
		//calculando totales
		$da = array();
			$da[]='Totales';
			$da[]='';
			$da[]='';
			$da[]="$ ".number_format($tseguro,2,".",",");
			$da[]="$ ".number_format($texcedente,2,".",",");
			$da[]='';
			$da[]="$ ".number_format($timporte,2,".",",");
			$da[]="$ ".number_format($tiva,2,".",",");
			$da[]="$ ".number_format($tivaretenido,2,".",",");
			$da[]="$ ".number_format($ttotal,2,".",",");
			$data[]=$da;
		
		$pdf->ezTable($data,$col,'', $est);
	}
	
	$s = "SELECT otrosmontofacturar FROM facturacion WHERE folio = $_GET[folio]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	if($f->otrosmontofacturar>0){
		$pdf->setColor(.79,.67,.11);
		$pdf->ezText("<b>DETALLADO DE FACTURA, OTROS</b>\n",12,array('justification'=>'left'));
		$pdf->setColor(.25,.25,.25);
		
		#detallado inferior
		$col = array('0'=>"Cantidad",'1'=>'Descripcion', '2'=>'Importe','3'=>'Subtotal','4'=>'Iva',
		'5'=>'Iva Ret', '6'=>'Monto a Facturar');
		
		$opt = array('0'=>array('justification'=>'right'),'1'=>array('justification'=>'left'),	
		'2'=>array('justification'=>'right'),'3'=>array('justification'=>'right'),
		'4'=>array('justification'=>'right'),'5'=>array('justification'=>'right'),
		'6'=>array('justification'=>'right'));
		$est = array('fontSize' => 6, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25),'cols'=>$opt);
		
		$data = array();
		$s = "SELECT otroscantidad, otrosdescripcion, otrosimporte,
		otrossubtotal, otrosiva, otrosivaretenido, otrosmontofacturar 
		FROM facturacion WHERE folio = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
			$da = array();
			$da[]=$f->otroscantidad;
			$da[]=$f->otrosdescripcion;
			$da[]="$ ".number_format($f->otrosimporte,2,".",",");
			$da[]="$ ".number_format($f->otrossubtotal,2,".",",");
			$da[]="$ ".number_format($f->otrosiva,2,".",",");
			$da[]="$ ".number_format($f->otrosivaretenido,2,".",",");
			$da[]="$ ".number_format($f->otrosmontofacturar,2,".",",");			
			$data[]=$da;
		
		$pdf->ezTable($data,$col,'', $est);
	}
	
	$pdf->ezStream(); 
?>