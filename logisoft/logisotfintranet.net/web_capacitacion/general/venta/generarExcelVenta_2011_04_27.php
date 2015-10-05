<?	function cambio_texto($texto){
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
	
	function cambiaf_a_mysql($fecha){//Convierte fecha de normal a mysql
    	ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    	return $lafecha; 
	}
	
	$xls = '<?xml version="1.0"?>
	<?mso-application progid="Excel.Sheet"?>
	<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
	 xmlns:o="urn:schemas-microsoft-com:office:office"
	 xmlns:x="urn:schemas-microsoft-com:office:excel"
	 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
	 xmlns:html="http://www.w3.org/TR/REC-html40">
	 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
	  <Author>PMM</Author>
	  <LastAuthor>PMM</LastAuthor>
	  <Created>'.date('d/m/Y').'</Created>
	  <Company>PMM</Company>
	  <Version>11.9999</Version>
	 </DocumentProperties>
	 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
	  <WindowHeight>8445</WindowHeight>
	  <WindowWidth>14955</WindowWidth>
	  <WindowTopX>240</WindowTopX>
	  <WindowTopY>375</WindowTopY>
	  <ProtectStructure>False</ProtectStructure>
	  <ProtectWindows>False</ProtectWindows>
	 </ExcelWorkbook>
	 <Styles>
	  <Style ss:ID="Default" ss:Name="Normal">
	   <Alignment ss:Vertical="Bottom"/>
	   <Borders/>
	   <Font/>
	   <Interior/>
	   <NumberFormat/>
	   <Protection/>
	  </Style>
	  <Style ss:ID="s21">
	   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
	  </Style>
	  <Style ss:ID="s22">
	   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
	   <Borders>
		<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
	   </Borders>
	   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
	  </Style>
	  <Style ss:ID="s49" ss:Name="Moneda">
		<NumberFormat
		ss:Format="_-&quot;$&quot;* #,##0.00_-;-\-&quot;$&quot;* #,##0.00_-;_-&quot;$&quot;* &quot;-&quot;??_-;_-@_-"/>
	  </Style>
	  <Style ss:ID="s62" ss:Parent="s49">
		<Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
	  </Style>
	 </Styles>
	 <Worksheet ss:Name="Hoja1">';
 
 	$titulo = '<Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
	<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">FECHA: </Data></Cell>
	<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[fechainicio].' AL '.$_GET[fechafin].'</Data></Cell>
    <Cell ss:Index="3" ss:StyleID="s21"><Data ss:Type="String"></Data></Cell>
	<Cell ss:StyleID="s21"><Data ss:Type="String"></Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"/>
   </Row>';
 
 	$str = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 

		if(!ereg("dbserver",$str)){
			$l = mysql_connect("localhost","pmm","gqx64p9n");
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
 	
	if($_GET[accion] == 1){//STD-ProcedimientoReporteVentas_PM
		$s = "SELECT cs.prefijo AS sucursal, 
		SUM(IF(ISNULL(rv.convenio) or rv.convenio=0,0,rv.total)) AS convenio, 
		SUM(IF(ISNULL(rv.convenio) or rv.convenio=0,rv.total,0)) AS sinconvenio, 
		SUM(rv.total) AS total 
		FROM reportes_ventas rv
		INNER JOIN catalogosucursal cs ON 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.sucursalfacturo=cs.descripcion,
		rv.sucursalrealizo=cs.id)
		WHERE IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND rv.activo='S' GROUP BY cs.id";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="72" ss:Span="1"/>
	   '.$titulo.'
	   <Row ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s22"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CONVENIO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">SIN CONVENIO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';
	   
	}else if($_GET[accion] == 2){//STD-ProcedimientoReporteTipodeVentas_PM
		$s = "SELECT IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo,
		rv.prefijosucursal) AS sucursal,
		SUM(IF(rv.tipoventa='GUIA VENTANILLA',rv.total,0)) AS normales, 
		SUM(IF((rv.tipoventa='SOLICITUD DE FOLIOS' AND tipoempresarial='PREPAGADA') or rv.tipoventa='FACTURA EXCEDENTE',rv.total,0)) AS prepagadas,
		SUM(IF(rv.tipoventa='GUIA EMPRESARIAL' AND tipoempresarial<>'PREPAGADA',rv.total,0)) AS consignacion,
		
		SUM(IF(rv.tipoventa='GUIA VENTANILLA',rv.total,0))+
		SUM(IF((rv.tipoventa='FACTURA EXCEDENTE' OR rv.tipoventa='GUIA EMPRESARIAL' 
				OR rv.tipoventa='SOLICITUD DE FOLIOS') AND tipoempresarial='PREPAGADA',rv.total,0))+
		SUM(IF(rv.tipoventa='GUIA EMPRESARIAL' AND tipoempresarial<>'PREPAGADA',rv.total,0))
		 AS total
		FROM reportes_ventas rv
		WHERE IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND rv.activo='S' AND 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		GROUP BY sucursal";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="72" ss:Span="1"/>
	   <Column ss:Index="4" ss:Width="66.75"/>
	   '.$titulo.'
	   <Row ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s22"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">NORMALES </Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">PREPAGADAS </Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CONSIGNACION</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';
		$idcliente = "";
		
	}else if($_GET[accion] == 3){
		$s = "SELECT IFNULL(SUM(IF(rv.tipoflete = 'PAGADA' AND rv.tipopago='CONTADO', rv.total,0)),0) contado,
		IFNULL(SUM(IF(rv.tipoflete = 'PAGADA' AND rv.tipopago='CREDITO', rv.total,0)),0) credito,
		IFNULL(SUM(IF(rv.tipoflete = 'POR COBRAR' AND rv.tipopago='CONTADO', rv.total,0)),0) cobcontado,
		IFNULL(SUM(IF(rv.tipoflete = 'POR COBRAR' AND rv.tipopago='CREDITO', rv.total,0)),0) cobcredito,
		IFNULL(SUM(rv.total),0) AS total
		FROM reportes_ventas AS rv
		WHERE rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' 
		AND	IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo='S'";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="72" ss:Span="1"/>
	   <Column ss:Index="4" ss:Width="66.75"/>
	   '.$titulo.'
	   <Row ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s22"><Data ss:Type="String">CONTADO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CREDITO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">COB-CONTADO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">COB-CREDITO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';
	   $idcliente = "";
	   
	}else if($_GET[accion] == 4){//STD-ProcedimientoReporteVentasalContado_PM
		$s = "SELECT rv.idcliente, rv.nombrecliente AS cliente, rv.destino, rv.folio, rv.total
		FROM reportes_ventas rv
		WHERE
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		rv.tipoflete = 'PAGADA' AND rv.tipopago='CONTADO'
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="210.75"/>
	   <Column ss:AutoFitWidth="0" ss:Width="210.75"/>
	   <Column ss:Width="100"/>
	  	'.$titulo.'
	   <Row ss:Height="13.5">
		<Cell ss:StyleID="s22"><Data ss:Type="String"># CLIENTE</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">DESTINO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">GUIA</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">IMPORTE</Data></Cell>
	   </Row>';
		$idcliente = 0;
	}else if($_GET[accion] == 5){//STD-ProcedimientoReporteVentasxsucursal_PM
		$s = "SELECT rv.idcliente, rv.nombrecliente AS cliente, rv.destino, rv.folio, rv.tipopago, 
		IFNULL(SUM(rv.total),0) AS total FROM reportes_ventas rv
		WHERE rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo = 'S'
		GROUP BY rv.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="210.75"/>
	   <Column ss:AutoFitWidth="0" ss:Width="210.75"/>
	   <Column ss:Width="100"/>
	   <Column ss:Width="80.25"/>
	  	'.$titulo.'
	   <Row ss:Height="13.5">
		<Cell ss:StyleID="s22"><Data ss:Type="String"># CLIENTE</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">DESTINO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">GUIA</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CONDICION PAGO</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">IMPORTE</Data></Cell>
	   </Row>';
		
		$idcliente = 0;
	
	}else if($_GET[accion] == 6){//consignacion
		$s = "SELECT rv.prefijosucursal sucursal,CONCAT('#',sg.idcliente),CONCAT('#',CONCAT_WS(' ',sg.nombre,sg.apepat,sg.apemat)) AS cliente,
		sg.cantidad, sg.factura, SUM(rv.total) importe, 0 servicios, SUM(rv.total) total
		FROM solicitudguiasempresariales sg
		INNER JOIN reportes_ventas rv ON rv.folio BETWEEN sg.desdefolio AND sg.hastafolio 
		WHERE sg.prepagada<>'SI' and rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."'
		GROUP BY sg.idcliente";		
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
		   x:FullRows="1" ss:DefaultColumnWidth="60">
		   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="204"/>
		   <Column ss:AutoFitWidth="0" ss:Width="72"/>
		   <Column ss:Index="5" ss:Width="96"/>
		   <Column ss:Width="85.5"/>
		   '.$titulo.'
		   <Row>
			<Cell ss:StyleID="s21"/>
		   </Row>
		   <Row>
			<Cell ss:StyleID="s21"/>
		   </Row>
		   <Row ss:AutoFitHeight="0" ss:Height="13.5">
			<Cell ss:StyleID="s22"><Data ss:Type="String">SUCURSAL</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">NOMBRE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">CANT. FOLIOS</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">FACTURA</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">IMPORTE FACTURA</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">SERV. ADICIONALES</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">TOTAL </Data></Cell>
		   </Row>';
		$idcliente = 0;
	}else if($_GET[accion] == 7){//Prepagadas
		$s = "SELECT IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) sucursal,
		CONCAT('#',rv.idcliente),rv.nombrecliente AS cliente,CONCAT('#',folio) AS venta, foliosempresariales AS folios,CONCAT('#',rv.factura),rv.total AS importe, 
		IFNULL(SUM(IF((ISNULL(ge.factura) OR ge.factura=0) and(ge.tseguro>0 or ge.texcedente>0),ge.tseguro+ge.texcedente,0)),0) porfacturar
		FROM reportes_ventas rv
		LEFT JOIN solicitudguiasempresariales sg ON rv.folio = sg.id
		LEFT JOIN guiasempresariales ge ON ge.id BETWEEN sg.desdefolio AND sg.hastafolio AND (ge.tseguro>0 OR ge.texcedente>0)
		WHERE (tipoventa = 'SOLICITUD DE FOLIOS' OR rv.tipoventa='FACTURA EXCEDENTE') AND rv.tipoempresarial = 'PREPAGADA' AND 
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."'
		GROUP BY rv.folio";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
		   x:FullRows="1" ss:DefaultColumnWidth="60">
		   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="75"/>
		   <Column ss:AutoFitWidth="0" ss:Width="250"/>
		   <Column ss:AutoFitWidth="0" ss:Width="72"/>
		   <Column ss:Width="150"/>
		   <Column ss:Width="85.5"/>
		   <Column ss:Width="105.75"/>
		   '.$titulo.'
		   <Row ss:Height="13.5">
			<Cell ss:StyleID="s22"><Data ss:Type="String">SUCURSAL</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String"># CLIENTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">VENTA</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">FOLIOS</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">FACTURA</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">IMPORTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">SIN FA OTROS</Data></Cell>
		   </Row>';
		   $idcliente = 0;
	}	
	
 	if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==$idcliente){
					if(substr($f[$i],0,1)=='#'){
						$f[$i]=str_replace('#','',$f[$i]);
					}
					$xls .= '<Cell><Data ss:Type="String">'.cambio_texto($f[$i]).'</Data></Cell>';
					$arre[$i+1] = "NO";
				}else if(is_numeric($f[$i])){
					$xls .= '<Cell ss:StyleID="s62"><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}
		
		 $xls .='<Row>
	  	<Cell ss:StyleID="s21"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s62" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
			}
		}
	   $xls .='</Row>';
	}
  
   
	  $xls .='</Table>
	  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
	   <PageSetup>
		<Header x:Margin="0"/>
		<Footer x:Margin="0"/>
		<PageMargins x:Bottom="0.984251969" x:Left="0.78740157499999996"
		 x:Right="0.78740157499999996" x:Top="0.984251969"/>
	   </PageSetup>
	   <Print>
		<ValidPrinterInfo/>
		<PaperSizeIndex>9</PaperSizeIndex>
		<VerticalResolution>0</VerticalResolution>
	   </Print>
	   <Selected/>
	   <Panes>
		<Pane>
		 <Number>3</Number>
		 <ActiveRow>5</ActiveRow>
		 <ActiveCol>2</ActiveCol>
		</Pane>
	   </Panes>
	   <ProtectObjects>False</ProtectObjects>
	   <ProtectScenarios>False</ProtectScenarios>
	  </WorksheetOptions>
	 </Worksheet> 
	</Workbook>';
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"".$_GET[titulo].".xls\"" ); 
	print $xls;
?>