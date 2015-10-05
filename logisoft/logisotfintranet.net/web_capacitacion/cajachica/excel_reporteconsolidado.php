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
  <Author>pcJose</Author>
  <LastAuthor>pcJose</LastAuthor>
  <Created>2010-03-04T01:41:11Z</Created>
  <LastSaved>2010-03-04T04:35:53Z</LastSaved>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8700</WindowHeight>
  <WindowWidth>12795</WindowWidth>
  <WindowTopX>120</WindowTopX>
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
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss"/>
   <NumberFormat ss:Format="Short Date"/>
  </Style>
  <Style ss:ID="s24">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s30">
   <NumberFormat ss:Format="Short Date"/>
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
	}
	
	$s = "SELECT folio, IF(estado = 1 OR estado = 0, 'PENDIENTE', IF(estado = 2, 'AUTORIZADA', 'REPOSICION')) AS estado,
	prefijosucursal, nombregerente, cheque, DATE_FORMAT(fechacheque,'%d/%m/%Y') AS fecha, 
	totalgasto, foliocorreointerno FROM foliosgastoscajachica 
	WHERE keytipopagoindex = '1' ".(($_GET[sucursal]!=1)? " AND keysucursal = $_GET[sucursal]":"")." 
	".(($_GET[estado]!=0 && $_GET[estado]!="")?" AND estado = '".$_GET[estado]."'":"")."
	AND date(fechagenerada) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
	$r = mysql_query($s,$l) or die($s);
	$filas = mysql_num_rows($r);
	
 $xls .='<Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="9" ss:ExpandedRowCount="'.($filas+8).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="4" ss:AutoFitWidth="0" ss:Width="112.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="71.25"/>
   <Column ss:Width="80.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="93.75"/>
   <Column ss:Width="112.5"/>
   <Row>
    <Cell ss:Index="2" ss:MergeAcross="4" ss:StyleID="s27"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s22"><Data ss:Type="String">CONSOLIDADO DE GASTOS CAJA CHICA</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA:</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s23"><Data ss:Type="String">'.date('d/m/Y').'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"/>
    <Cell ss:StyleID="s23"/>
    <Cell ss:StyleID="s22"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">DEL:</Data></Cell>
    <Cell ss:StyleID="s30"><Data ss:Type="String">'.$_GET[fechainicio].'</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">AL:</Data></Cell>
    <Cell ss:StyleID="s30"><Data ss:Type="String">'.$_GET[fechafin].'</Data></Cell>
    <Cell ss:StyleID="s30"/>
    <Cell ss:StyleID="s24"/>
   </Row>
   <Row ss:Index="7">
    <Cell ss:StyleID="s25"><Data ss:Type="String">FOLIO</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">ESTADO</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">SUCURSAL</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">GERENTE</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">CHEQUE</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">FECHA CHEQUE</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">TOTAL</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">No. C. INTERNO</Data></Cell>
    <Cell ss:StyleID="s26"/>
   </Row>';
	$folio = 0; $factura = 4; $folio2 = 7;
	
	if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==$folio || $i==$folio2 || $i==$factura){
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
	  	<Cell ss:StyleID="s30"><Data ss:Type="String">TOTALES:</Data></Cell>';   	 	
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
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>0</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>9</ActiveRow>
     <ActiveCol>7</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"reporteconsolidado.xls\"" ); 
	print $xls;
?>