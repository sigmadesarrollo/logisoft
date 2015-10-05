<?	require('../fpdf.php');
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$fecha = split("/",$_GET[fechaembarque]);
	$fechaseleccionada = $fecha[2]."-".$fecha[1]."-".$fecha[0];
	
	$s = "SELECT GROUP_CONCAT(sucursal) sucursal FROM (
		SELECT gv.idsucursaldestino sucursal
		FROM guiasventanilla gv
		INNER JOIN seguimiento_guias sg ON gv.id = sg.guia
		AND sg.ubicacion = ".$_GET[sucursal]." AND (sg.estado='ALMACEN ORIGEN' OR sg.estado='ALMACEN TRASBORDO') 
		AND sg.fecha = '$fechaseleccionada' AND NOT ISNULL(gv.idsucursaldestino)
		UNION
		SELECT ge.idsucursaldestino sucursal
		FROM guiasempresariales ge
		INNER JOIN seguimiento_guias sg ON ge.id = sg.guia
		AND sg.ubicacion = ".$_GET[sucursal]." AND (sg.estado='ALMACEN ORIGEN' OR sg.estado='ALMACEN TRASBORDO') 
		AND sg.fecha = '$fechaseleccionada' AND NOT ISNULL(ge.idsucursaldestino)
	) t1 GROUP BY sucursal";
	//die($s);
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$_GET[destino] = $f->sucursal;
	if($_GET[destino]==""){
		die("");
	}
	
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
		
		function ImprovedTable($header,$data,$fechaseleccionada){
			require_once('../../Conectar.php');
			$l = Conectarse('webpmm');
			//print_r($data);
			//die('die');
			//Anchuras de las columnas	
			
			$s = "SELECT prefijo FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
			$r = mysql_query($s,$l) or die($s); $fq = mysql_fetch_object($r);
			
			$w = array(26,55,43,12,20,20,8,8);
			$contador=0;
			
			$data = split(",",$data);
			for($ii=0;$ii<count($data);$ii++){
				$s = "SELECT descripcion, prefijo FROM catalogosucursal WHERE id = ".$data[$ii]."";
				$r = mysql_query($s,$l) or die($s); $ff = mysql_fetch_object($r);
				
				$s = "(SELECT gv.id AS guia, CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
				CONCAT(gd.cantidad,' ',gd.descripcion,'/',gd.contenido) AS descripcion,
				gv.totalpeso, IF(gv.tipoflete=0,gv.total,0) AS pagado,
				IF(gv.tipoflete=1,gv.total,0) AS cobrar,
				IF(gv.ocurre=0,'EAD','OCU') AS emb, gv.idsucursaldestino,
				IF(fe.guia IS NULL,'COM','FAL') AS incidencia,gd.cantidad
				FROM guiasventanilla gv
				LEFT JOIN embarquedemercancia_faltante fe ON gv.id = fe.guia AND fe.sucursal = ".$_GET[sucursal]."
				INNER JOIN catalogocliente ce ON gv.iddestinatario = ce.id
				INNER JOIN guiaventanilla_detalle gd ON gv.id = gd.idguia
				INNER JOIN seguimiento_guias sg ON gv.id = sg.guia
				AND sg.ubicacion = ".$_GET[sucursal]." AND gv.idsucursaldestino = ".$data[$ii]."
				AND (sg.estado='ALMACEN ORIGEN' OR sg.estado='ALMACEN TRASBORDO') AND sg.fecha = '$fechaseleccionada'
				AND gv.id NOT LIKE '888%')
				UNION
				(SELECT ge.id AS guia, CONCAT_WS(' ',ce.nombre,ce.paterno,ce.materno) AS destinatario,
				CONCAT(gde.cantidad,' ',gde.descripcion,'/',gde.contenido) AS descripcion,
				ge.totalpeso, IF(ge.tipoflete='PAGADO',ge.total,0) AS pagado,
				IF(ge.tipoflete='POR COBRAR',ge.total,0) AS cobrar,
				IF(ge.ocurre=0,'EAD','OCU') AS emb, ge.idsucursaldestino,
				IF(fe.guia IS NULL,'COM','FAL') AS incidencia,gde.cantidad
				FROM guiasempresariales ge
				LEFT JOIN embarquedemercancia_faltante fe ON ge.id = fe.guia AND fe.sucursal = ".$_GET[sucursal]."
				INNER JOIN catalogocliente ce ON ge.iddestinatario = ce.id
				INNER JOIN guiasempresariales_detalle gde ON ge.id = gde.id
				INNER JOIN seguimiento_guias sg ON ge.id = sg.guia
				AND sg.ubicacion = ".$_GET[sucursal]." AND ge.idsucursaldestino = ".$data[$ii]."
				AND (sg.estado='ALMACEN ORIGEN' OR sg.estado='ALMACEN TRASBORDO') AND sg.fecha = '$fechaseleccionada'
				AND ge.id NOT LIKE '888%')
				UNION
				(SELECT gv.id AS guia, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS destinatario,
				CONCAT(gd.cantidad,' ',gd.descripcion,'/',gd.contenido) AS descripcion,
				gv.totalpeso, IF(gv.tipoflete=0,gv.total,0) AS pagado,
				IF(gv.tipoflete=1,gv.total,0) AS cobrar,
				IF(gv.ocurre=0,'EAD','OCU') AS emb, gv.idsucursaldestino,
				IF(fe.guia IS NULL,'COM','FAL') AS incidencia,gd.cantidad
				FROM guiasventanilla gv
				LEFT JOIN embarquedemercancia_faltante fe ON gv.id = fe.guia AND fe.sucursal = ".$_GET[sucursal]."
				INNER JOIN catalogoempleado ce ON gv.iddestinatario = ce.id
				INNER JOIN guiaventanilla_detalle gd ON gv.id = gd.idguia
				INNER JOIN seguimiento_guias sg ON gv.id = sg.guia
				AND sg.ubicacion = ".$_GET[sucursal]." AND gv.idsucursaldestino = ".$data[$ii]."
				AND (sg.estado='ALMACEN ORIGEN' OR sg.estado='ALMACEN TRASBORDO') AND sg.fecha = '$fechaseleccionada'
				AND gv.id LIKE '888%')
				UNION
				(SELECT ge.id AS guia, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS destinatario,
				CONCAT(gde.cantidad,' ',gde.descripcion,'/',gde.contenido) AS descripcion,
				ge.totalpeso, IF(ge.tipoflete='PAGADO',ge.total,0) AS pagado,
				IF(ge.tipoflete='POR COBRAR',ge.total,0) AS cobrar,
				IF(ge.ocurre=0,'EAD','OCU') AS emb, ge.idsucursaldestino,
				IF(fe.guia IS NULL,'COM','FAL') AS incidencia,gde.cantidad
				FROM guiasempresariales ge
				LEFT JOIN embarquedemercancia_faltante fe ON ge.id = fe.guia AND fe.sucursal = ".$_GET[sucursal]."
				INNER JOIN catalogoempleado ce ON ge.iddestinatario = ce.id
				INNER JOIN guiasempresariales_detalle gde ON ge.id = gde.id
				INNER JOIN seguimiento_guias sg ON ge.id = sg.guia
				AND sg.ubicacion = ".$_GET[sucursal]." AND ge.idsucursaldestino = ".$data[$ii]."
				AND (sg.estado='ALMACEN ORIGEN' OR sg.estado='ALMACEN TRASBORDO') AND sg.fecha = '$fechaseleccionada'
				AND ge.id LIKE '888%')";
				//echo $s."<br><br>";
				$r = mysql_query($s,$l) or die($s);
				$arr = array();
				if(mysql_num_rows($r)>0){
					$this->addPage();
					$totalpaquetes = 0;
					$totalguias = 0;
					while($f = mysql_fetch_array($r)){
						$totalguias++;
						$totalpaquetes += $f[cantidad];
						$f[0] = cambio_texto($f[0]);
						$f[1] = cambio_texto($f[1]);
						$f[2] = cambio_texto($f[2]);
						if($anterior == $f[0]){
							$totalguias--;
							$f[0]="";
							$f[1]="";
						}
						if($f[0]!=""){
							$anterior = $f[0];
						}
						$arr[] = array('0'=>$f[0],'1'=>substr($f[1],0,30),'2'=>substr($f[2],0,24),'3'=>$f[3],'4'=>$f[4],'5'=>$f[5],'6'=>$f[6],'7'=>$f[8]);
					}
					
					//Cabeceras
					$this->SetFont('Courier','B',8);
					for($i=0;$i<count($header);$i++){	
						$this->Cell($w[$i],7,$header[$i],0,0,'C');						
					}
					$this->Ln();
					
					//Datos		
					$cont = 0;
					$canArre = count($arr);
					$canReco = 0;
					
					foreach($arr as $row){
						$canReco++;
						$cont++;
						$row[1] = substr($row[1],0,40);
						$row[2] = substr($row[2],0,30);
						$this->SetFont('Courier','',8);
						$this->Cell($w[0],6, utf8_decode($row[0]),0);				
						$this->Cell($w[1],6, utf8_decode($row[1]),0);
						$this->Cell($w[2],6, utf8_decode($row[2]),0);				
						$this->Cell($w[3],6, number_format($row[3]),0,0,'R');
						$this->Cell($w[4],6, '$'.number_format($row[4],2),0,0,'R');
						$this->Cell($w[5],6, '$'.number_format($row[5],2),0,0,'R');
						$this->Cell($w[6],6, utf8_decode($row[6]),0);
						$this->Cell($w[7],6, utf8_decode($row[7]),0);				
						$this->Ln();
						if($cont>=27){
							$cont=0;
							
							$this->Cell(array_sum($w),0,'',0);
							$this->addDestino("DESTINO: ".utf8_decode($ff->descripcion)."",52);
							$this->addLeyenda("Los operadores abajo firmantes manifestamos haber recibido la mercancia amparada con las guias embarque registradas en la presente relacion,",235);
							$this->addLeyenda("por lo que nos obligamos hacer entrega de la misma en su destino, en las condiciones y cantidades que nos fueron entregadas.",240);
							
							$this->addFirma("Nombre y Firma",10,248,"Entrego Sucl.".$fq->prefijo."",30);
							$this->addFirma("Nombre y Firma",50,248,"OPERADOR",30);
							$this->addFirma("Nombre y Firma",90,248,"OPERADOR",30);
							$this->addFirma("",130,248,"UNIDAD",15);
							$this->addFirma("Nombre y Firma",160,248,"Entrego Sucl.".$ff->prefijo."",30);
							if($canReco<$canArre){
								$this->addPage();
								$this->SetFont('Courier','B',8);
								for($i=0;$i<count($header);$i++){	
									$this->Cell($w[$i],7,$header[$i],0,0,'C');						
								}
								$this->Ln();
							}
						}
						if($canReco==$canArre){
							$this->SetFont('Courier','',8);
							$this->Cell($w[0],6, '',0);				
							$this->Cell($w[1],6, "GUIAS:$totalguias   PAQUETES:$totalpaquetes",0);
							$this->Cell($w[2],6, '',0);				
							$this->Cell($w[3],6, '',0,0,'R');
							$this->Cell($w[4],6, '',0,0,'R');
							$this->Cell($w[5],6, '',0,0,'R');
							$this->Cell($w[6],6, '',0);
							$this->Cell($w[7],6, '',0);
						}
					}
					//Linea de cierre
					if($cont!=0){
						$this->Cell(array_sum($w),0,'',0);
						$this->addDestino("DESTINO: ".utf8_decode($ff->descripcion)."",52);
						$this->addLeyenda("Los operadores abajo firmantes manifestamos haber recibido la mercancia amparada con las guias embarque registradas en la presente relacion,",235);
						$this->addLeyenda("por lo que nos obligamos hacer entrega de la misma en su destino, en las condiciones y cantidades que nos fueron entregadas.",240);
						
						$this->addFirma("Nombre y Firma",10,248,"Entrego Sucl.".$fq->prefijo."",30);
						$this->addFirma("Nombre y Firma",50,248,"OPERADOR",30);
						$this->addFirma("Nombre y Firma",90,248,"OPERADOR",30);
						$this->addFirma("",130,248,"UNIDAD",15);
						$this->addFirma("Nombre y Firma",160,248,"Entrego Sucl.".$ff->prefijo."",30);				
					}
					//if($contador<count($data)-1){
						//$this->addPage();
					//}				
					$contador++;
				}
			}
			//die("xxx");
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
			$this->SetFont('Courier','B',15);
		
			//Movernos a la derecha		
			$this->Cell(80);
		
			//Titulo		
			$this->Cell(70,10,'PAQUETERIA Y MENSAJERIA',0,0,'C');
		
			//Salto de linea		
			/*$this->Ln(10);
			
			$this->SetFont('Courier','B',8);
			*/
			
			$this->Ln(10);
			$this->SetFont('Courier','B',10);
			$this->Cell(70,10,'REPORTE: RELACION DE EMBARQUE                            FECHA IMPRESO:'.date('d/m/Y').'',0,0,'L');
			$this->Ln(5);
			$this->Cell(70,10,'SUCURSAL: '.utf8_decode($f->descripcion).'               FECHA DEL DIA: '.$_GET[fechaembarque].'',0,0,'L');			
			$this->Ln(12);
		}
		
		function Footer(){
			//Posición: a 1,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Courier','I',8);
			//Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}
		
		function addLeyenda($ref,$posicion){
			$this->SetFont( 'Courier', "", 6);
			$length = $this->GetStringWidth($ref);
			$r1  = 10;
			$r2  = $r1 + $length;
			$y1  = $posicion;
			$y2  = $y1+5;
			$this->SetXY( $r1 , $y1 );
			$this->Cell($length,4, $ref);
		}
		
		function addDestino($ref,$posicion){
			$this->SetFont('Courier','B',10);
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
			$this->SetFont('Courier', "B", 6);
			$this->Cell(10,5, utf8_decode($titulo), 0, 0, "C");
			$this->SetXY($r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
			$this->SetFont( 'Courier', "", 6);
			$this->Cell(10,5, utf8_decode($mode), 0, 0, "C");
		}
	}
	
	$pdf = new PDF('P','mm','letter');
	$pdf->AliasNbPages();
	//Ttulos de las columnas
	$header = array('GUIA','DESTINATARIO','DESC./CONT.','PESO','PAGADO','COBRAR','EMB','INC');
	
	//Carga de datos
	$pdf->SetFont('Courier','B',12);	
	//$pdf->AddPage();	
	$pdf->ImprovedTable($header,$_GET[destino],$fechaseleccionada);
	
	$pdf->Output();
?>