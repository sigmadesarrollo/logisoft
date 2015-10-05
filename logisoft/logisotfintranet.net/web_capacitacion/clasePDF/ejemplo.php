<?php
	require_once("../ConectarSolo.php");
	$l = Conectarse("webpmm");
	$_GET[folio] = 164;
	$s = "SELECT gc.folio, gc.consumomensual, DATE_FORMAT(gc.fecha, '%d/%m/%Y') AS fecha, DATE_FORMAT(gc.vigencia, '%d/%m/%Y') AS vigencia,
	cs.descripcion AS sucursal, gc.nvendedor, gc.idcliente, gc.rfc, concat_ws(' ',gc.nombre, gc.apaterno, gc.amaterno) as ncliente, gc.calle, gc.numero,
	gc.colonia, gc.cp, gc.poblacion, gc.municipio, gc.estado, gc.pais, gc.celular, gc.telefono, gc.email,
	gc.precioporkg, gc.precioporcaja, gc.descuentosobreflete, gc.cantidaddescuento, gc.limitekg, gc.costo,
	gc.preciokgexcedente, gc.prepagadas, gc.consignacionkg, gc.consignacioncaja, gc.consignaciondescuento, gc.consignaciondescantidad,
	gc.valordeclarado, gc.valordeclaradoemp, date_format(current_date, '%d/%m/%Y') as fechaactual
	FROM generacionconvenio gc
	LEFT JOIN catalogosucursal cs ON gc.sucursal = cs.idsucursal
	WHERE gc.folio = '$_GET[folio]'";
	$r = mysql_query($s,$l) or die("error ".mysql_error($l)."--".$s);
	$f = mysql_fetch_object($r);
	
	include ('class.ezpdf.php');
	$pdf = new Cezpdf('LETTER','portrait');
	#					 t, b, l, r
	$pdf -> ezSetMargins(50,70,50,50);
	#w = 612 h = 792
	
	$pdf->setColor(.16,.38,.61);
	$pdf->selectFont('fonts/Helvetica.afm');
	$img = ImageCreatefromjpeg('../img/logo.jpg');
	$pdf->addImage($img,50,697,47,50);
	$pdf->addText(110,722,24,'<b>Paqueteria y Mensajeria en Movimiento</b>');
	$pdf->setColor(.79,.67,.11);
	$pdf->addText(110,702,16,'<b>Convenio de venta de guias</b>');
	$pdf->line(50,690,560,690);
	$pdf->setColor(.25,.25,.25);
	$pdf->ezText("\n\n\n\n$f->fechaactual",12,array('justification'=>'right'));
	$pdf->setColor(.79,.67,.11);
	$pdf->ezText("<b>DATOS DEL CLIENTE</b>",12,array('justification'=>'left'));
	$pdf->setColor(.25,.25,.25);
	$pdf->ezText(strtoupper("<b> NOMBRE:</b> $f->ncliente"),10,array('justification'=>'left'));
	$pdf->ezText(strtoupper("<b> DIRECCION:</b> $f->calle NO. $f->numero.    <b>COL:</b> $f->colonia.    <b>CP:</b> $f->cp"),10,array('justification'=>'left'));
	$pdf->ezText(strtoupper("<b> CIUDAD:</b> $f->poblacion.    <b>ESTADO:</b> $f->estado.    <b>PAIS:</b> $f->pais "),10,array('justification'=>'left'));
	$pdf->ezText(strtoupper("<b> TELEFONO:</b> $f->telefono.    <b>CELULAR:</b> $f->celular."),10,array('justification'=>'left'));
	$pdf->ezText(strtoupper("<b> EMAIL:</b> $f->email"),10,array('justification'=>'left'));
	#$pdf->line(50,590,560,590);
	
	$s = "SELECT (SELECT COUNT(*) FROM cconvenio_servicios WHERE idconvenio = '$_GET[folio]' AND tipo = 'CONVENIO') c1,
	(SELECT COUNT(*) FROM cconvenio_servicios_sucursales WHERE idconvenio = '$_GET[folio]' AND tipo = 'SUCONVENIO') c2,
	(SELECT COUNT(*) FROM cconvenio_servicios_sucursales WHERE idconvenio = '$_GET[folio]' AND tipo = 'SRCONVENIO') c3";
	$rx = mysql_query($s,$l) or die($s);
	$fx = mysql_fetch_object($rx);
	
	if($f->precioporkg==1 || $f->precioporcaja==1 || $f->descuentosobreflete==1 || $fx->c1>0 || $fx->c2>0 || $fx->c3>0){
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
		}elseif($f->precioporcaja==1){
			
			$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONVENIO' and idconvenio = $_GET[folio] GROUP BY descripcion";
			$rx = mysql_query($s,$l) or die($s);
			$data0 = array();
			$data1 = array();
			$options0 = array();
			$options1 = array();
			while($fx = mysql_fetch_array($rx)){
				$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONVENIO' and idconvenio = $_GET[folio] 
				and descripcion = '$fx[descripcion]' order by zona";
				$ry = mysql_query($s,$l) or die($s);
				$cantcol = mysql_num_rows($ry)/2;
				$zona = 1;
				$datap0 = array();
				$datap1 = array();
				$columnasp0 = array();
				$columnasp1 = array();
				$datap0["DESC"] = "$fx[descripcion]";
				$datap1["DESC"] = "$fx[descripcion]";
				$columnasp0["DESC"] = "DESC";
				$columnasp1["DESC"] = "DESC";
				while($fy = mysql_fetch_object($ry)){
					if($zona<$cantcol){
						$columnasp0["ZONA$zona"] = "ZONA $zona\n$fy->kmi/$fy->kmf";
						$datap0["ZONA$zona"] = "$ ".number_format($fy->precio,2,".",",");
						$options0["ZONA$zona"] = array('justification'=>'right');
					}else{
						$columnasp1["ZONA$zona"] = "ZONA $zona\n$fy->kmi/$fy->kmf";
						$datap1["ZONA$zona"] = "$ ".number_format($fy->precio,2,".",",");
						$options1["ZONA$zona"] = array('justification'=>'right');
					}
					$zona++;
				}
				$data0[] = $datap0;
				$data1[] = $datap1;
			}		
			
			//print_r($data);
			$estilo0 = array('fontSize' => 8, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25), 'cols' => $options0);
			$estilo1 = array('fontSize' => 8, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25), 'cols' => $options1);
			
			//print_r($estilo0);
			
			$pdf->ezText("",8);
			$pdf->ezTable($data0,$columnasp0,'',$estilo0);
			$pdf->ezText("",8);
			$pdf->ezTable($data1,$columnasp1,'',$estilo1);
			
			$pdf->ezText(strtoupper("\n DESCUENTO EN CASO DE SELECCIONAR OTRA DESCRIPCION: $f->cantidaddescuento %"),10,array('justification'=>'left'));
		}elseif($f->descuentosobreflete==1){
			$pdf->ezText(strtoupper("\n DESCUENTO SOBRE FLETE: $f->cantidaddescuento %"),10,array('justification'=>'left'));
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
	
	//empresariales
	$s = "SELECT (SELECT COUNT(*) FROM cconvenio_servicios WHERE idconvenio = '$_GET[folio]' AND tipo = 'CONVENIO') c1,
	(SELECT COUNT(*) FROM cconvenio_servicios_sucursales WHERE idconvenio = '$_GET[folio]' AND tipo = 'SUCONVENIO') c2,
	(SELECT COUNT(*) FROM cconvenio_servicios_sucursales WHERE idconvenio = '$_GET[folio]' AND tipo = 'SRCONVENIO') c3";
	$rx = mysql_query($s,$l) or die($s);
	$fx = mysql_fetch_object($rx);
	
	if($f->prepagadas==1 || $f->consignacionkg==1 || $f->consignacioncaja==1 || $f->consignaciondescuento==1 || $fx->c1>0 || $fx->c2>0 || $fx->c3>0){
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
		}elseif($f->consignacioncaja==1){
			
			$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[folio] GROUP BY descripcion";
			$rx = mysql_query($s,$l) or die($s);
			$data0 = array();
			$data1 = array();
			$options0 = array();
			$options1 = array();
			while($fx = mysql_fetch_array($rx)){
				$s = "SELECT * FROM cconvenio_configurador_caja WHERE tipo='CONSIGNACION' and idconvenio = $_GET[folio] 
				and descripcion = '$fx[descripcion]' order by zona";
				$ry = mysql_query($s,$l) or die($s);
				$cantcol = mysql_num_rows($ry)/2;
				$zona = 1;
				$datap0 = array();
				$datap1 = array();
				$columnasp0 = array();
				$columnasp1 = array();
				$datap0["DESC"] = "$fx[descripcion]";
				$datap1["DESC"] = "$fx[descripcion]";
				$columnasp0["DESC"] = "DESC";
				$columnasp1["DESC"] = "DESC";
				while($fy = mysql_fetch_object($ry)){
					if($zona<$cantcol){
						$columnasp0["ZONA$zona"] = "ZONA $zona\n$fy->kmi/$fy->kmf";
						$datap0["ZONA$zona"] = "$ ".number_format($fy->precio,2,".",",");
						$options0["ZONA$zona"] = array('justification'=>'right');
					}else{
						$columnasp1["ZONA$zona"] = "ZONA $zona\n$fy->kmi/$fy->kmf";
						$datap1["ZONA$zona"] = "$ ".number_format($fy->precio,2,".",",");
						$options1["ZONA$zona"] = array('justification'=>'right');
					}
					$zona++;
				}
				$data0[] = $datap0;
				$data1[] = $datap1;
			}		
			
			//print_r($data);
			$estilo0 = array('fontSize' => 8, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25), 'cols' => $options0);
			$estilo1 = array('fontSize' => 8, 'showHeadings' => 1, 'lineCol' => array(.25,.25,.25), 'cols' => $options1);
			
			//print_r($estilo0);
			
			$pdf->ezText("",8);
			$pdf->ezTable($data0,$columnasp0,'',$estilo0);
			$pdf->ezText("",8);
			$pdf->ezTable($data1,$columnasp1,'',$estilo1);
			
			$pdf->ezText(strtoupper("\n DESCUENTO EN CASO DE SELECCIONAR OTRA DESCRIPCION: $f->consignaciondescantidad %"),10,array('justification'=>'left'));
		}elseif($f->consignaciondescuento==1){
			$pdf->ezText(strtoupper("\n DESCUENTO SOBRE FLETE: $f->consignaciondescantidad %"),10,array('justification'=>'left'));
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
	/*$pdf->ezNewPage();*/
	
	$pdf->ezStream();
?>