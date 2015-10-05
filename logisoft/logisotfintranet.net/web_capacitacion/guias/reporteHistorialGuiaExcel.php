<?
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
  <Created>2010-02-12T22:07:42Z</Created>
  <LastSaved>2010-02-12T22:12:39Z</LastSaved>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8700</WindowHeight>
  <WindowWidth>11595</WindowWidth>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
 </Styles>';
 	
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT g.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
	IF(g.tipoflete=0,'PAGADO','POR COBRAR') AS flete,
	IF(g.condicionpago=0,'CONTADO','CREDITO') AS condicionpago,
	IFNULL(g.subtotal,0) AS subtotal, IFNULL(g.tiva,0) AS tiva,
	IFNULL(g.ivaretenido,0) AS ivaretenido, IFNULL(g.total,0) AS total
	FROM guiasventanilla g
	INNER JOIN catalogosucursal o ON g.idsucursalorigen = o.id
	INNER JOIN catalogosucursal d ON g.idsucursalorigen = d.id
	WHERE g.fecha = CURRENT_DATE AND g.idsucursalorigen = ".$_GET[sucursal]."
	UNION
	SELECT ge.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
	ge.tipoflete AS flete, ge.tipopago AS condicionpago,
	IFNULL(ge.subtotal,0) AS subtotal, IFNULL(ge.tiva,0) AS tiva,
	IFNULL(ge.ivaretenido,0) AS ivaretenido, IFNULL(ge.total,0) AS total
	FROM guiasempresariales ge
	INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
	INNER JOIN catalogosucursal d ON ge.idsucursalorigen = d.id
	WHERE ge.fecha = CURRENT_DATE AND ge.idsucursalorigen = ".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s);
	$filas = mysql_num_rows($r);
	
 $xls .='<Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="9" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:AutoFitWidth="0" ss:Width="99.75"/>
   <Column ss:Width="42.75"/>
   <Column ss:Width="48"/>
   <Column ss:AutoFitWidth="0" ss:Width="83.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="80.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="98.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="103.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="97.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="100.5"/>
   <Row>
    <Cell ss:Index="2" ss:MergeAcross="3" ss:StyleID="s21"><Data ss:Type="String">REPORTE CIERRE CAJA</Data></Cell>
   </Row>
   <Row ss:Index="3">
    <Cell ss:StyleID="s21"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">ORIGEN</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">DESTINO</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">FLETE</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">COND. PAGO</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">SUBTOTAL</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">IVA</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">IVA RETENIDO</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">TOTAL</Data></Cell>
   </Row>';
   
	
   
   if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false){
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
			$xls .= '<Cell ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
			}
		}
	   $xls .='</Row>';
	}
   
  $xls.='</Table>
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
     <ActiveRow>5</ActiveRow>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
 
 header ("Content-type: application/x-msexcel");
 header ("Content-Disposition: attachment; filename=\"reporteCierreCaja.xls\"" ); 
 print $xls;

?>