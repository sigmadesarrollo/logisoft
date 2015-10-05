<?	
	$xls ='<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>pcJose</Author>
  <LastAuthor>pcJose</LastAuthor>
  <Created>2009-11-05T23:06:14Z</Created>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8700</WindowHeight>
  <WindowWidth>15195</WindowWidth>
  <WindowTopX>0</WindowTopX>
  <WindowTopY>120</WindowTopY>
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
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
   <Interior/>
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
 	
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
 	
	$cabezera = '<Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
	<Cell ss:MergeAcross="2" ss:StyleID="s25"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
   </Row>';
   
    $s = "SELECT c.id, CONCAT_WS(' ',c.nombre,c.paterno,c.materno) AS nombre,
		CONCAT(d.calle,' #',d.numero,', ',d.colonia) AS direccion,
		d.poblacion, d.estado, IFNULL(s.montoautorizado,0) AS credito,
		IFNULL(CONCAT('%',co.folio),'') AS convenio, 
		CONCAT(IF(co.descuentosobreflete=1 OR co.consignaciondescuento=1,'DESCUENTO,', ''), 
		IF(co.precioporkg=1 OR co.consignacionkg=1,'KILOGRAMO,',''), IF(co.precioporcaja=1 
		OR co.consignacioncaja=1,'PAQUETE,',''), IF(co.prepagadas=1,'PREPAGADA,','')) AS tipoconvenio 
		FROM catalogocliente c 
		INNER JOIN direccion d ON c.id = d.codigo AND d.origen = 'cl'
		LEFT JOIN generacionconvenio co ON c.id = co.idcliente
		LEFT JOIN solicitudcredito s ON c.id = s.cliente
		WHERE c.tipocliente = 2 AND d.facturacion = 'SI'
		".(($_GET[sucursal]!=1)? " AND c.sucursal = ".$_GET[sucursal]." ":"")." ";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
	$xls .= '<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
			<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="224.25"/>
			<Column ss:AutoFitWidth="0" ss:Width="87.75" ss:Span="2"/>
			<Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="63.75"/>
		'.$cabezera.'
		<Row ss:Index="4">
			<Cell ss:StyleID="s26"/>
			<Cell ss:StyleID="s26"/>
			<Cell ss:StyleID="s26"><Data ss:Type="String"></Data></Cell>
			<Cell ss:StyleID="s26"/>
			<Cell ss:StyleID="s26"><Data ss:Type="String"></Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">MONTO</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String"></Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">TIPO</Data></Cell>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s27"><Data ss:Type="String"># CLIENTE</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">CLIENTE</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">DIRECCION</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">POBLACION</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">ESTADO</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">CREDITO</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">CONVENIO</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">CONVENIO</Data></Cell>
		</Row>';
		
	if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==$noformula){
					if(substr($f[$i],0,1)=='%'){
						$f[$i]=str_replace('%','',$f[$i]);
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
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>0</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>6</ActiveRow>
     <ActiveCol>1</ActiveCol>
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