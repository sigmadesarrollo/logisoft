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
    	ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    	return $lafecha;
	} 
	
	
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
  <Created>2010-03-30T01:43:05Z</Created>
  <LastSaved>2010-03-30T01:55:31Z</LastSaved>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8445</WindowHeight>
  <WindowWidth>15195</WindowWidth>
  <WindowTopX>210</WindowTopX>
  <WindowTopY>870</WindowTopY>
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
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s30">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Size="16" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s32">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
 </Styles>';
 
		$s = "SELECT cs.prefijo AS sucursal,gv.id AS guias,DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',ccr.nombre,ccr.paterno,ccr.materno)AS remitente,
		CONCAT_WS(' ',ccd.nombre,ccd.paterno,ccd.materno)AS destinatario,
		IFNULL(gv.valordeclarado,0) AS valordeclarado, IFNULL(gv.valordeclarado * (SELECT IFNULL(prima / 100,0)AS prima 
		FROM configuradorgeneral),0) AS seguro FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON cs.id = IF(gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)		
		INNER JOIN catalogocliente ccr ON gv.idremitente=ccr.id
		INNER JOIN catalogocliente ccd ON gv.iddestinatario=ccd.id	
		WHERE gv.valordeclarado >= (SELECT cantidadvalordeclarado FROM configuradorgeneral) AND
		gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_GET[sucursal]!=1)?" and IF(gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)='".$_GET[sucursal]."' ":"")."		
		UNION
		SELECT cs.prefijo AS sucursal,ge.id AS guias,DATE_FORMAT(ge.fecha, '%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',ccr.nombre,ccr.paterno,ccr.materno)AS remitente,
		CONCAT_WS(' ',ccd.nombre,ccd.paterno,ccd.materno)AS destinatario,
		IFNULL(ge.valordeclarado,0) AS valordeclarado, 
		IFNULL(ge.valordeclarado * (SELECT IFNULL(prima / 100,0)AS prima FROM configuradorgeneral),0) AS seguro 
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON cs.id = IF(ge.tipoflete=0,ge.idsucursalorigen,ge.idsucursaldestino)
		INNER JOIN catalogocliente ccr ON ge.idremitente=ccr.id
		INNER JOIN catalogocliente ccd ON ge.iddestinatario=ccd.id
		WHERE ge.valordeclarado>=(SELECT cantidadvalordeclarado FROM configuradorgeneral) AND
		ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_GET[sucursal]!=1)?" and IF(ge.tipoflete=0,ge.idsucursalorigen,ge.idsucursaldestino)='".$_GET[sucursal]."' ":"")."		";
 		$r = mysql_query($s,$l) or die ($s);
		$filas = mysql_num_rows($r);
		
 $xls .='<Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="87.75"/>
   <Column ss:Index="4" ss:AutoFitWidth="0" ss:Width="187.5" ss:Span="1"/>
   <Column ss:Index="6" ss:AutoFitWidth="0" ss:Width="103.5" ss:Span="1"/>
   <Row ss:Height="20.25">
    <Cell ss:MergeAcross="6" ss:StyleID="s30"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row ss:Index="3">
    <Cell ss:StyleID="s21"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s32"><Data ss:Type="String">DETALLE DE GUIAS CON VALOR DECLARADO</Data></Cell>
    <Cell><Data ss:Type="String">FECHA: '.date('d/m/Y').'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">PERIODO:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s32"><Data ss:Type="String">DEL: '.$_GET[fechainicio].' AL '.$_GET[fechafin].'</Data></Cell>
   </Row>
   <Row ss:Index="6">
    <Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">FOLIO</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">CLIENTE ORIGEN</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">CLIENTE DESTINO</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">VALOR DECLARADO</Data></Cell>
    <Cell ss:StyleID="s23"><Data ss:Type="String">SEGURO</Data></Cell>
   </Row>';
   
   $s = "SELECT ajustarvalordeclarado FROM configuradorgeneral";
   $rr= mysql_query($s,$l) or die($s); $fr = mysql_fetch_object($rr);
   $monto = 0;
   
	if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$monto += $f->valordeclarado;
			if($monto <= $fr->ajustarvalordeclarado){
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
		
		 $xls .='<Row>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
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
     <ActiveRow>12</ActiveRow>
     <ActiveCol>3</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
	
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"valorDeclarado.xls\"" ); 
	print $xls;
?>