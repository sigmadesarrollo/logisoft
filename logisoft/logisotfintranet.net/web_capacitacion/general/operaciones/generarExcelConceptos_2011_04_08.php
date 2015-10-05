<?
	$xls = '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>pcJose</Author>
  <LastAuthor>pcJose</LastAuthor>
  <Created>2010-07-30T22:48:42Z</Created>
  <LastSaved>2010-07-30T22:52:10Z</LastSaved>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>9975</WindowHeight>
  <WindowWidth>21195</WindowWidth>
  <WindowTopX>120</WindowTopX>
  <WindowTopY>75</WindowTopY>
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
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">';
 
 	$str = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 

		if(!ereg("dbserver",$str)){
			$l = mysql_connect("localhost","pmm","guhAf2eh");
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
 
	$s = "SELECT unidad, ruta, bitacora, viaticos, IFNULL(folioliquidacion,0) AS folioliquidacion,
	IFNULL(sucursalfolioliquidacion,0) AS sucursal
	FROM reporteoperaciones3 WHERE bitacora = ".$_GET[bitacora]."";
	$r = mysql_query($s,$l) or die($s);
	$filas = mysql_num_rows($r);
	
	$s = "SELECT d.concepto, d.cantidad FROM comprobantedeliquidaciondebitacora c
	INNER JOIN comprobantedeliquidaciondebitacoradetalle d ON c.folio = d.comprobantedeliquida AND c.sucursal = d.sucursal
	WHERE c.foliobitacora = ".$_GET[bitacora]."";
	$rr = mysql_query($s,$l) or die($s);
	$filass = mysql_num_rows($rr);
	
  $xls .='<Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="'.($filas+9).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="5" ss:AutoFitWidth="0" ss:Width="87"/>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell><Data ss:Type="String">GASTOS POR RUTA</Data></Cell>
   </Row>
   <Row ss:Index="3" ss:Height="13.5">
    <Cell ss:StyleID="s23"><Data ss:Type="String">UNIDAD</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">RUTA</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">BITACORA</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">VIATICOS</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">F. LIQUIDACION</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL</Data></Cell>
   </Row>';
   
   if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==1 || $i==2 || $i==4 || $i==5){
					$xls .= '<Cell><Data ss:Type="String">'.utf8_encode($f[$i]).'</Data></Cell>';
					$arre[$i+1] = "NO";
				}else if(is_numeric($f[$i])){
					$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}  
		$xls .='<Row>
	  	<Cell ss:StyleID="s22"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s21" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
			}
		}
	   $xls .='</Row>
	   <Row>
		<Cell ss:StyleID="s22"><Data ss:Type="String"></Data></Cell>
		<Cell ss:Index="4" ss:StyleID="s22"><Data ss:Type="String"></Data></Cell>
	   </Row>
	   <Row>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CONCEPTO</Data></Cell>
		<Cell ss:Index="4" ss:StyleID="s22"><Data ss:Type="String">IMPORTE</Data></Cell>
	   </Row>';
	}
   	
  	if($filass>0){
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($rr)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false){
					$xls .= '<Cell><Data ss:Type="String">'.utf8_encode($f[$i]).'</Data></Cell>';
					$arre[$i+1] = "NO";
				}else if(is_numeric($f[$i])){
					$xls .= '<Cell ss:Index="4"><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}  
		$xls .='<Row>
	  	<Cell ss:StyleID="s22"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s21" ss:Index="4" ss:Formula="=SUM(R[-'.$filass.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>0</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>8</ActiveRow>
     <ActiveCol>1</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';

	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"GASTOSRUTA.xls\"" ); 
	print $xls;

?>