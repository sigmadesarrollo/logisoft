<? function cambio_texto($texto){
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
	$xls ='<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>PMM</Author>
  <LastAuthor>PMM</LastAuthor>
  <Created>'.date("d/m/Y").'</Created>
  <Company>PMM</Company>
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
   <Font ss:Size="9"/>
   <NumberFormat ss:Format="Standard"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
   <Interior/>
  </Style>
   <Style ss:ID="s25">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Borders/>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
   <Interior/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
   <Interior/>
  </Style>
 </Styles>';
 
 	$l = mysql_connect("97.74.31.27","dbwebpmm","Sistemapmm09");
	//$l = mysql_connect("DBSERVER","root","root");
	mysql_select_db("dbwebpmm", $l);
		/*$s = "SELECT DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, gv.id AS guia,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		cs.prefijo AS destino, gv.total, IFNULL(gv.factura,'') AS factura 
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete=0,gv.idremitente,gv.iddestinatario) = cc.id
		WHERE YEAR(gv.fecha) = '".$_GET[fecha]."' AND cc.id=".$_GET[cliente]."
		".(($_GET[sucursal]!="0") ? " AND cs.id = ".$_GET[sucursal]."" : '' )."	
		UNION
		SELECT ge.fecha, ge.id AS guia, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		cs.prefijo AS destino, ge.total, IFNULL(ge.factura,'') AS factura
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
		INNER JOIN catalogocliente cc ON IF(ge.tipoflete='PAGADO',ge.idremitente,ge.iddestinatario) = cc.id
		WHERE YEAR(ge.fecha) = '".$_GET[fecha]."' AND cc.id=".$_GET[cliente]."
		".(($_GET[sucursal]!="0") ? " AND cs.id = ".$_GET[sucursal]."" : '' )."	";*/
		
		$s = "SELECT DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, gv.id AS guia,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		cs.prefijo AS destino, gv.total, IFNULL(gv.factura,'') AS factura 
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete=0,gv.idremitente,gv.iddestinatario) = cc.id
		WHERE YEAR(gv.fecha) = '".$_GET[fecha]."' AND cc.id=".$_GET[cliente]." AND NOT ISNULL(gv.factura) 
		".(($_GET[sucursal]!="0") ? " AND cs.id = ".$_GET[sucursal]."" : '' )."	
		UNION
		SELECT ge.fecha, ge.id AS guia, 
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		cs.prefijo AS destino, ge.total, IFNULL(ge.factura,'') AS factura
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
		INNER JOIN catalogocliente cc ON 			IF(ge.tipoflete='PAGADO',ge.idremitente,ge.iddestinatario) = cc.id
		WHERE YEAR(ge.fecha) = '".$_GET[fecha]."' AND cc.id=".$_GET[cliente]."  AND NOT ISNULL(ge.factura) 
		".(($_GET[sucursal]!="0") ? " AND cs.id = ".$_GET[sucursal]."" : '' )."	";
		$r = mysql_query($s,$l) or die($s); 	
		
		$s = "SELECT (SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM catalogocliente WHERE id=".$_GET[cliente].") AS cliente
		,(SELECT prefijo FROM catalogosucursal WHERE id=".$_GET[sucursal].") AS sucursal";
		$sq = mysql_query($s,$l) or die($s);
		$row = mysql_fetch_object($sq);
	$filas = mysql_num_rows($r);	
	
	$xls .='<Worksheet ss:Name="Hoja1">		
	<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">   
	<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="87.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="225"/>
   <Column ss:Index="5" ss:AutoFitWidth="0" ss:Width="81.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="65.25"/>
   <Row>
    <Cell ss:MergeAcross="2" ss:StyleID="s25"><Data ss:Type="String">TITULO: '.$_GET[titulo].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s23"><Data ss:Type="String">FECHA: '.(($_GET[fecha]!="")? $_GET[fecha] : date('d/m/Y')).'</Data></Cell>
   </Row>
   <Row ss:Index="4">
    <Cell ss:MergeAcross="2" ss:StyleID="s25"><Data ss:Type="String">CLIENTE: '.$_GET[cliente]."  ".$row->cliente.'</Data></Cell>
    <Cell ss:Index="4" ss:StyleID="s21"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL: '.$row->sucursal.'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s23"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s26"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">REMITENTE / DESTINATARIO</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">DESTINO</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">IMPORTE</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">FACTURA</Data></Cell>
    <Cell ss:StyleID="s26"/>
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
	header ("Content-Disposition: attachment; filename=\"".$_GET[titulo].".xls\"" ); 
	print $xls;
?>