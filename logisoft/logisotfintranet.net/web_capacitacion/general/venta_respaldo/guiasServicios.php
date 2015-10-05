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
  <Created>2009-11-12T23:00:51Z</Created>
  <LastSaved>2009-11-13T02:02:55Z</LastSaved>
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
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s28">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">';
  	$l = mysql_connect("97.74.31.27","dbwebpmm","Sistemapmm09");
	//$l = mysql_connect("DBSERVER","root","root");
	mysql_select_db("dbwebpmm", $l);
	
	if($_GET[accion] == 9){//Reporte de Relación de conceptos por consignación		
			$s = "SELECT ge.fecha, ori.prefijo AS origen,
			des.prefijo AS destino, ge.id AS guia,
			IFNULL(ge.valordeclarado,0) AS valordeclarado, IFNULL(ge.tflete,0) AS tflete,
			SUM(de.kgexcedente) AS excedente, IFNULL(sub.costoead,0) AS subdestino,
			IFNULL(ge.tseguro,0) AS costoseg, IFNULL(ge.tcombustible,0) AS cargocombustible, 
			IFNULL(ge.subtotal,0) AS subtotal, IFNULL(ge.tiva,0) AS tiva,
			IFNULL(ge.ivaretenido,0) AS ivaretenido, IFNULL(ge.total,0) AS total, ge.factura,
			SUM(de.cantidad) AS cantidad, SUM(de.peso) AS kilogramos
			FROM guiasempresariales ge
			INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
			INNER JOIN guiasempresariales_detalle de ON ge.id = de.id
			INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
			INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
			LEFT JOIN catalogodestino sub ON ge.idsucursaldestino = sub.id
			WHERE ge.tipoguia = 'CONSIGNACION' AND cc.id = ".$_GET[cliente]."
			AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			GROUP BY ge.fecha";
			$r = mysql_query($s,$l) or die($s);
			$filas = mysql_num_rows($r);
			$nodatos = "";
			$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre FROM catalogocliente
			WHERE id=".(($_GET[cliente]!='')?$_GET[cliente]:0)."";
			$t = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($t);
			
			if($filas==0){
				$s = "SELECT '' AS fecha, '' origen, '' AS destino, '' AS guia, 0 AS cantidad,
				0 AS kilogramos, 0 AS valordeclarado, 0 AS tflete, 0 AS excedente, 0 AS subdestino,
				0 AS costoseg, 0 AS cargocombustible, 0 AS subtotal, 0 AS tiva, 0 AS ivaretenido, 
				0 AS total, 0 AS factura";				
				$r = mysql_query($s,$l) or die($s);
				$filas = mysql_num_rows($r);
				$nodatos = 0;
			}
		}
	
  $xls .='<Table ss:ExpandedColumnCount="17" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="4" ss:AutoFitWidth="0" ss:Width="96.75"/>
   <Column ss:Width="76.5"/>
   <Column ss:Width="72"/>
   <Column ss:Width="102.75"/>
   <Column ss:Index="9" ss:Width="98.25"/>
   <Column ss:Width="68.25"/>
   <Column ss:Width="84.75"/>
   <Column ss:Width="115.5"/>
   <Column ss:Index="15" ss:Width="73.5"/>
   <Row>
    <Cell ss:StyleID="s25"><Data ss:Type="String">TITULO:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s28"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s25"><Data ss:Type="String">FECHA:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s28" ><Data ss:Type="String">'.$_GET[fechaini].' AL '.$_GET[fechafin].'</Data></Cell>
   </Row>
   <Row ss:Index="4">
    <Cell ss:StyleID="s25"><Data ss:Type="String">CLIENTE:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s28"><Data ss:Type="String">'.$_GET[cliente].' '.$f->nombre.'</Data></Cell>
   </Row>
   <Row ss:Index="6" ss:Height="13.5">
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">ORIGEN</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">DESTINO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">No. GUIA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">No. PAQUETES</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">KILOGRAMOS</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">VALOR DECLARADO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">FLETE</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">EXC. KILOGRAMOS</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUBDESTINO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">COSTO SEGURO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">CARGO COMBUSTIBLE</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUBTOTAL</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">IVA </Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">IVA RETENIDO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">TOTAL</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">FACTURA</Data></Cell>
   </Row>';
   
   if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==$guia){
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
	  	<Cell ss:StyleID="s25"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s25" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
			}
		}
	   $xls .='</Row>';
	}
   	
	if($nodatos!=""){
		$xls .= '<Row>
		<Cell ss:Index="6"><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
		<Cell><Data ss:Type="Number">0</Data></Cell>
	   </Row>';
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
     <ActiveRow>3</ActiveRow>
     <ActiveCol>1</ActiveCol>
     <RangeSelection>R4C2:R4C5</RangeSelection>
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