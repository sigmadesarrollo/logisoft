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
  <Created>2010-07-24T00:33:38Z</Created>
  <LastSaved>2010-07-24T00:39:30Z</LastSaved>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>9210</WindowHeight>
  <WindowWidth>20955</WindowWidth>
  <WindowTopX>360</WindowTopX>
  <WindowTopY>390</WindowTopY>
  <MaxChange>0.0001</MaxChange>
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
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
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
		
		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS cliente FROM catalogocliente WHERE id = ".$_GET[cliente];
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT DATE_FORMAT(t2.fechaalta,'%d/%m/%Y') AS fechaalta, 
		DATE_FORMAT(t2.fechamodificacion,'%d/%m/%Y') AS fechamodificacion,
		DATE_FORMAT(t2.fechavencimiento,'%d/%m/%Y') AS fechavencimiento, 
		t2.estadocredito, t2.limitecredito, t2.tipoconvenio, 0 AS valorconvenio,
		t2.pesomaximo, t2.preciosobrepeso FROM reportecliente2 t2 
		WHERE (YEAR(t2.fechaalta) = '".$_GET[fecha]."' OR YEAR(t2.fechamodificacion) = '".$_GET[fecha]."') 
		AND t2.idcliente = ".$_GET[cliente]." AND t2.activo = 0";
		$r = mysql_query($s,$l) or die($s);	
		$filas = mysql_num_rows($r);
 
 $xls .='<Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="9" ss:ExpandedRowCount="'.($filas+8).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:AutoFitWidth="0" ss:Width="72" ss:Span="2"/>
   <Column ss:Index="4" ss:AutoFitWidth="0" ss:Width="120.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="82.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="98.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="72"/>
   <Column ss:AutoFitWidth="0" ss:Width="87.75" ss:Span="1"/>
   <Row>
    <Cell ss:Index="2" ss:MergeAcross="5" ss:StyleID="s26"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s23"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s27"><Data ss:Type="String">HISTORIAL DE CLIENTE</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s23"><Data ss:Type="String">CLIENTE:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s27"><Data ss:Type="String">'.$_GET[cliente]." ".utf8_encode($f->cliente).'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s23"><Data ss:Type="String">FECHA:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s27"><Data ss:Type="String">'.$_GET[fecha].'</Data></Cell>
   </Row>
   <Row ss:Index="6">
    <Cell ss:StyleID="s21"><Data ss:Type="String">F. ALTA</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">F. RENOVACION</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">F. VENCIMIENTO</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">ESTADO</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">LIMITE</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">TIPO</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">VALOR</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">PESO </Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">PRECIO </Data></Cell>
   </Row>
   <Row ss:Height="13.5">
    <Cell ss:StyleID="s22"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CREDITO </Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CREDITO</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CONVENIO </Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">CONVENIO </Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">MAXIMO</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">SOBREPESO</Data></Cell>
   </Row>';
   
   if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false){
					$xls .= '<Cell><Data ss:Type="String">'.utf8_encode($f[$i]).'</Data></Cell>';
					$arre[$i+1] = "NO";
				}else if(is_numeric($f[$i])){
					$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}
		
		/* $xls .='<Row>
	  	<Cell ss:StyleID="s25"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s25" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
			}
		}
	   $xls .='</Row>';*/
	}
  
  $xls .='</Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0"/>
    <Footer x:Margin="0"/>
    <PageMargins x:Bottom="0.984251969" x:Left="0.78740157499999996"
     x:Right="0.78740157499999996" x:Top="0.984251969"/>
   </PageSetup>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>4</ActiveRow>
     <ActiveCol>1</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
	
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"historialCliente.xls\"" ); 
	print $xls; 
?>