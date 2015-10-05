<?
	function cambio_texto($texto){
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
  <Style ss:ID="s49" ss:Name="Moneda">
	<NumberFormat
	ss:Format="_-&quot;$&quot;* #,##0.00_-;-\-&quot;$&quot;* #,##0.00_-;_-&quot;$&quot;* &quot;-&quot;??_-;_-@_-"/>
  </Style>
  <Style ss:ID="s62" ss:Parent="s49">
	<Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
  </Style>
 </Styles>';
 	
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
	
	$s = "SELECT g.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
	IF(g.tipoflete=0,'PAGADO','POR COBRAR') AS flete, 
	IF(g.condicionpago=0,'CONTADO','CREDITO') AS condicionpago,
	IFNULL(g.subtotal,0) AS subtotal, IFNULL(g.tiva,0) AS tiva,
	IFNULL(g.ivaretenido,0) AS ivaretenido, IFNULL(g.total,0) AS total
	FROM guiasventanilla g
	INNER JOIN catalogosucursal o ON g.idsucursalorigen = o.id
	INNER JOIN catalogosucursal d ON g.idsucursalorigen = d.id
	WHERE g.fecha = CURRENT_DATE AND g.idsucursalorigen = ".$_GET[sucursal];
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