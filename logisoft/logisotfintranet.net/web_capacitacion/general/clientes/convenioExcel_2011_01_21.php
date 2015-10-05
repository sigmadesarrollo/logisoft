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
  <Created>2010-08-02T19:42:30Z</Created>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Font x:Family="Swiss" ss:Size="8" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Size="8" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s26">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
 </Styles>';
 
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
 
 	$cabezera = '<Row>
    <Cell ss:Index="2" ss:MergeAcross="2" ss:StyleID="s27"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s26"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s25"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s26"><Data ss:Type="String"></Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s25"><Data ss:Type="String"></Data></Cell>
   </Row>';
 
 	if($_GET[accion]==1){
		$s = "SELECT t3.prefijosucursal, t3.idcliente, t3.cliente, t3.tipo, 0 AS precio, 
		DATE_FORMAT(t3.vencimiento,'%d/%m/%Y') AS vencimiento FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE ".((!empty($_GET[vigente]))? "CURDATE() < t3.vencimiento" : "CURDATE() > t3.vencimiento")."
		 AND YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t3.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t3.idcliente";
		$r = mysql_query($s,$l) or die($s);	
		$filas = mysql_num_rows($r);
		
		$xls .='<Worksheet ss:Name="Hoja1">
	  <Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="'.($filas + 7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="355.5"/>
	   <Column ss:AutoFitWidth="0" ss:Width="184.5"/>';
	   
	   $xls .= $cabezera;
	   
	   $xls .='<Row ss:Index="5">
		<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL </Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">NUMERO DE </Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">NOMBRE DEL</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TIPO DE </Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">PRECIO </Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">VENCIMIENTO</Data></Cell>
	   </Row>
	   <Row ss:Height="13.5">
		<Cell ss:StyleID="s22"/>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE </Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CONVENIO </Data></Cell>
		<Cell ss:StyleID="s22"/>
		<Cell ss:StyleID="s22"><Data ss:Type="String">CONVENIO </Data></Cell>
	   </Row>';
	   $noformula = 1;
	}else if($_GET[accion]==2){
		$s = "SELECT t3.prefijosucursal, t3.idcliente, t3.cliente, t3.tipo, 0 AS precio,
		IF(CURDATE() < t3.vencimiento,'VIGENTE','VENCIDO') AS estatus,
		DATE_FORMAT(t3.vencimiento,'%d/%m/%Y') AS vencimiento FROM reportecliente3 t3
		INNER JOIN reportecliente1 t1 ON t3.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t3.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t3.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t3.idcliente";
		$r = mysql_query($s,$l) or die($s);	
		$filas = mysql_num_rows($r);
		$xls .='<Worksheet ss:Name="Hoja1">
		<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas + 7).'" x:FullColumns="1"
		   x:FullRows="1" ss:DefaultColumnWidth="60">
		   <Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="355.5"/>
		   <Column ss:AutoFitWidth="0" ss:Width="184.5"/>
		   <Column ss:Index="6" ss:AutoFitWidth="0" ss:Width="116.25"/>';
		   $xls .= $cabezera;
		 $xls .='<Row ss:Index="5">
    <Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL </Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">NUMERO DE </Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">NOMBRE DEL</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">TIPO DE </Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">PRECIO </Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">STATUS</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">VENCIMIENTO</Data></Cell>
   </Row>
   <Row ss:Height="13.5">
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE </Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CLIENTE</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CONVENIO </Data></Cell>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CONVENIO</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CONVENIO </Data></Cell>
   </Row>';
    $noformula = 1;
	}
   	
	 if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==$noformula){
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
	  	<Cell ss:StyleID="s26"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s26" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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
     <ActiveRow>9</ActiveRow>
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