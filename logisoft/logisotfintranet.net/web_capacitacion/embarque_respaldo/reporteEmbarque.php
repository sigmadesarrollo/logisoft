<?	$str = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 

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
	
	$s = "SELECT prefijo FROM catalogosucursal WHERE id=".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die(mysql_error($l).$s);
	$su= mysql_fetch_object($r);
	
	$s = "SELECT bs.folio, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS conductor1,
	CONCAT_WS(' ',ce2.nombre,ce2.apellidopaterno,ce2.apellidomaterno) AS conductor2, bs.ruta
	FROM bitacorasalida bs
	INNER JOIN catalogoruta cr ON bs.ruta = cr.id
	INNER JOIN catalogoempleado ce ON bs.conductor1 = ce.id 
	LEFT JOIN catalogoempleado ce2 ON bs.conductor2 = ce2.id
	WHERE bs.unidad='".$_GET[unidad]."' AND bs.status = 0";	
	$r = mysql_query($s,$l) or die($s);
	$t = mysql_fetch_object($r);
	
	$s = "SELECT cs.prefijo AS destino FROM catalogoruta cr
	INNER JOIN catalogorutadetalle cd ON cr.id = cd.ruta
	INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
	WHERE cd.tipo=3 AND cr.id=".$t->ruta."";
	$r = mysql_query($s,$l) or die($s);
	$d = mysql_fetch_object($r);
	
	$s = "SELECT d.guia, g.fecha, g.total, g.destino, g.tipoflete, g.condicionpago FROM embarquedemercancia ocu
	INNER JOIN embarquedemercanciadetalle d ON ocu.folio = d.idembarque
	INNER JOIN
	(SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,IFNULL(gv.total,0) AS total, cd.descripcion AS destino,
	IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condicionpago
	FROM guiasventanilla gv
	INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
	UNION
	SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,IFNULL(ge.total,0) AS total, cd.descripcion AS destino,
	ge.tipoflete, ge.tipopago AS condicionpago FROM guiasempresariales ge
	INNER JOIN catalogodestino cd ON ge.iddestino = cd.id) g ON d.guia = g.guia
	WHERE ocu.folio=".$_GET[folio]." AND ocu.idsucursal = ".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s);
	$filas = mysql_num_rows($r);
	
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
  <LastPrinted>2010-02-13T02:30:04Z</LastPrinted>
  <Created>2010-02-13T02:22:25Z</Created>
  <LastSaved>2010-02-13T02:31:41Z</LastSaved>
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
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s29">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
 </Styles>';
 
 $xls .='<Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="'.($filas+13).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Width="91.5"/>
   <Column ss:Width="58.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="98.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="75.75"/>
   <Column ss:Index="6" ss:AutoFitWidth="0" ss:Width="75"/>
   <Row>
    <Cell ss:MergeAcross="5" ss:StyleID="s22"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s25"><Data ss:Type="String">EMBARQUE DE MERCANCIA</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s25"><Data ss:Type="String">'.date('d/m/Y').'</Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String">SUC. DESTINO:</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s25"><Data ss:Type="String">'.cambio_texto($d->destino).'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL:</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s25"><Data ss:Type="String">'.cambio_texto($su->prefijo).'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">CONDUCTOR:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s25"><Data ss:Type="String">'.cambio_texto($t->conductor1).'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">CONDUCTOR:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s25"><Data ss:Type="String">'.cambio_texto($t->conductor2).'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">BITACORA:</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s25"><Data ss:Type="Number">'.$t->folio.'</Data></Cell>
   </Row>
   <Row ss:Index="10">
    <Cell ss:StyleID="s29"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String">F. EMISION</Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String">IMPORTE</Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String">DESTINO</Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String">FLETE</Data></Cell>
    <Cell ss:StyleID="s29"><Data ss:Type="String">COND. PAGO</Data></Cell>
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
	  	<Cell ss:StyleID="s21"><Data ss:Type="String">TOTALES:</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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
     <ActiveRow>2</ActiveRow>
     <ActiveCol>1</ActiveCol>
     <RangeSelection>R3C2:R3C4</RangeSelection>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet> 
</Workbook>';
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"reporteEmbarque.xls\"" ); 
	print $xls;
?>