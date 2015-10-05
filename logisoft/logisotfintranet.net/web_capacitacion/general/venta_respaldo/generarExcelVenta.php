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
 
 	$l = mysql_connect("97.74.31.27","dbwebpmm","Sistemapmm09");
	//$l = mysql_connect("DBSERVER","root","root");
	mysql_select_db("dbwebpmm", $l);		 
 	
	if($_GET[accion] == 1){//STD-ProcedimientoReporteVentas_PM
		$s = "SELECT sucursal, SUM(convenio) AS convenio, SUM(sinconvenio) AS sinconvenio,
		SUM((convenio + sinconvenio)) AS total FROM 
		(SELECT cs.prefijo AS sucursal, IFNULL(SUM(IF(gv.convenioaplicado<>'',gv.total,0)),0) AS convenio,
		IFNULL(SUM(IF(gv.convenioaplicado='' OR gv.convenioaplicado IS NULL,gv.total,0)),0) AS sinconvenio
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($row[0])."' AND '".cambiaf_a_mysql($row[1])."'
		GROUP BY gv.idsucursalorigen
		UNION
		SELECT cs.prefijo AS sucursal, IFNULL(SUM(IF(ge.convenioaplicado<>'',ge.total,0)),0) AS convenio,
		IFNULL(SUM(IF(ge.convenioaplicado='' OR ge.convenioaplicado IS NULL,ge.total,0)),0) AS sinconvenio
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		WHERE ge.fecha BETWEEN '".cambiaf_a_mysql($row[0])."' AND '".cambiaf_a_mysql($row[1])."'
		GROUP BY ge.idsucursalorigen) tabla
		GROUP BY sucursal";
		
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
		$idcliente = "";
	}else if($_GET[accion] == 2){//STD-ProcedimientoReporteTipodeVentas_PM
		$s = "SELECT sucursal, SUM(normales) AS normales, SUM(prepagadas) AS prepagadas,
		SUM(consignacion) AS consignacion, 
		SUM((normales + prepagadas + consignacion)) AS total FROM 
		(SELECT cs.prefijo AS sucursal,
		IFNULL(SUM(gv.total),0) AS normales,0 AS prepagadas,
		0 AS consignacion
		FROM guiasventanilla gv 
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY gv.idsucursalorigen
		UNION
		SELECT cs.prefijo AS sucursal, 0 AS normales,
		IFNULL(SUM(IF(ge.tipoguia='PREPAGADA',ge.total,0)),0) AS prepagadas,
		IFNULL(SUM(IF(ge.tipoguia='CONSIGNACION',ge.total,0)),0) AS consignacion
		FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."'
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'		
		GROUP BY ge.idsucursalorigen) tabla";		
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
		$s = "SELECT * FROM (
		SELECT cs.prefijo AS sucursal,
		IF(gv.condicionpago=0 AND gv.tipoflete=0,SUM(gv.total),0) AS contados,
		IF(gv.condicionpago=1 AND gv.tipoflete=0,SUM(gv.total),0) AS credito,
		IF(gv.condicionpago=0 AND gv.tipoflete=1,SUM(gv.total),0) AS cobcontado,
		IF(gv.condicionpago=1 AND gv.tipoflete=1,SUM(gv.total),0) AS cobcredito
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."'
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY cs.prefijo
		UNION
		SELECT cs.prefijo AS sucursal,
		IF(ge.tipopago='CONTADO' AND ge.tipoflete='PAGADO',SUM(ge.total),0) AS contados,
		IF(ge.tipopago='CREDITO' AND ge.tipoflete='PAGADO',SUM(ge.total),0) AS credito,
		IF(ge.tipopago='CONTADO' AND ge.tipoflete='POR COBRAR',SUM(ge.total),0) AS cobcontado,
		IF(ge.tipopago='CREDITO' AND ge.tipoflete='POR COBRAR',SUM(ge.total),0) AS cobcredito
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY cs.prefijo) t
		WHERE contados>0 OR credito>0 OR cobcontado>0 OR cobcredito>0";
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
		$s = "SELECT gv.idremitente AS cliente,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destino,
		gv.id AS guia, gv.total AS importe
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' AND gv.condicionpago=0 AND gv.tipoflete=0
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		UNION
		SELECT ge.idremitente AS cliente,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destino,
		ge.id AS guia, ge.total AS importe
		FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' AND ge.tipopago='CONTADO' AND ge.tipoflete='PAGADO'
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
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
		$s = "SELECT gv.idremitente AS cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destino, gv.id AS guia, 
		IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condicionpago, gv.total AS importe 
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."'
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."'
		AND '".cambiaf_a_mysql($_GET[fechafin])."'";
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
	
	}else if($_GET[accion] == 7){//consignacion
		$s = "SELECT f.cliente,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		IFNULL(s.cantidad,0) AS cantidadfolios, f.folio AS factura,
		(f.total + f.sobmontoafacturar) AS importefactura,
		f.otrosmontofacturar AS serviciosadicionales,
		(f.total + f.sobmontoafacturar + f.otrosmontofacturar) AS total
		FROM facturacion f
		INNER JOIN catalogocliente cc ON f.cliente = cc.id
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		LEFT JOIN solicitudguiasempresariales s ON cc.id = s.idcliente
		LEFT JOIN guiasempresariales ge ON f.folio = ge.factura
		WHERE cs.prefijo = '".$_GET[sucursal]."' AND ge.tipoguia='CONSIGNACION'
		AND f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";		
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
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
			<Cell ss:StyleID="s22"><Data ss:Type="String"># CLIENTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">Q FOLIOS</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">FACTURA</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">IMPORTE A FACTURAR</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">SERV. ADICIONALES</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">TOTAL </Data></Cell>
		   </Row>';
		$idcliente = 0;
	}else if($_GET[accion] == 8){//Prepagadas
		$s = "SELECT f.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente,
		s.foliotipo AS venta, IFNULL(CONCAT_WS(' - ',s.desdefolio,s.hastafolio),0) AS folios, f.folio,
		IFNULL(IF(ge.factura IS NOT NULL OR ge.factura<>0,ge.total,0),0) AS importe,
		IFNULL(IF(ge.factura IS NULL OR ge.factura=0,ge.total,0),0) AS porfacturar
		FROM facturacion f
		INNER JOIN catalogocliente cc ON f.cliente = cc.id
		LEFT JOIN guiasempresariales ge ON ge.idremitente = cc.id
		LEFT JOIN solicitudguiasempresariales s ON cc.id = s.idcliente
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE cs.prefijo = '".$_GET[sucursal]."' AND ge.tipoguia = 'PREPAGADA'
		AND f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY s.foliotipo";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+5).'" x:FullColumns="1"
		   x:FullRows="1" ss:DefaultColumnWidth="60">
		   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="210.75"/>
		   <Column ss:AutoFitWidth="0" ss:Width="72"/>
		   <Column ss:AutoFitWidth="0" ss:Width="147"/>
		   <Column ss:Width="80.25"/>
		   <Column ss:Width="85.5"/>
		   <Column ss:Width="105.75"/>
		   '.$titulo.'
		   <Row ss:Height="13.5">
			<Cell ss:StyleID="s22"><Data ss:Type="String"># CLIENTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">VENTA</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">FOLIOS</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">FACTURA</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">IMPORTE</Data></Cell>
			<Cell ss:StyleID="s22"><Data ss:Type="String">PENDIENTE DE FACTURAR</Data></Cell>
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
					$xls .= '<Cell><Data ss:Type="String">'.cambio_texto($f[$i]).'</Data></Cell>';
					$arre[$i+1] = "NO";
				}else if(is_numeric($f[$i])){
					$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}
		
		 $xls .='<Row>
	  	<Cell ss:StyleID="s21"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s21" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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