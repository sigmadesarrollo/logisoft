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
	
	$xls .= '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>pcJose</Author>
  <LastAuthor>pcJose</LastAuthor>
  <Created>2009-11-10T15:47:35Z</Created>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8445</WindowHeight>
  <WindowWidth>14955</WindowWidth>
  <WindowTopX>240</WindowTopX>
  <WindowTopY>375</WindowTopY>
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
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
   <Interior/>
  </Style>
  <Style ss:ID="s25">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
   <Interior/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
   <Interior/>
  </Style>
  <Style ss:ID="s30">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">';
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
		
		$cabezera = '
		<Row>
			<Cell ss:StyleID="s25"><Data ss:Type="String">TITULO:</Data></Cell>
			<Cell ss:MergeAcross="3" ss:StyleID="s30" ><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
		</Row>
		<Row>
			<Cell ss:StyleID="s25"><Data ss:Type="String">FECHA:</Data></Cell>
			<Cell ss:MergeAcross="3" ss:StyleID="s30" ><Data ss:Type="String">'.(($_GET[fecha]=="" || $_GET[fecha2]=="")?date('d/m/Y'): $_GET[fecha].' AL '.$_GET[fecha2]).'</Data></Cell>
		</Row>';

	if($_GET[accion]==1){//REPORTE PRINCIPAL
		
		$s = "SELECT prefijosucursal as sucursal, COUNT(DISTINCT(idcliente)) AS clientes, 
		SUM(
			IF(IFNULL(fechafactura,fecha)>=CURRENT_DATE,total,0)
		) AS carteravigente,
		SUM(
			IF(IFNULL(fechafactura,fecha)<CURRENT_DATE,total,0)
		) AS carteramorosa,
		SUM(total) AS carteratotal
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' 
		group by prefijosucursal";

		$r = mysql_query($s,$l) or die ($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
			<Column ss:Index="2" ss:Width="76.5"/>
			<Column ss:AutoFitWidth="0" ss:Width="87.75" ss:Span="2"/>
		'.$cabezera.'		
		<Row ss:Index="4">
			<Cell ss:StyleID="s26"><Data ss:Type="String">SUCURSAL</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">CLIENTES CON</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">CARTERA</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">CARTERA</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">CARTERA</Data></Cell>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s23"/>
			<Cell ss:StyleID="s27"><Data ss:Type="String">CREDITO</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">VIGENTE</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">MOROSA</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">TOTAL</Data></Cell>
		</Row>';
		
	}else if($_GET[accion]==2){//COBRANZA CLIENTES CON CREDITO
		$s = "SELECT rc2.idcliente, rc2.cliente, rc2.montoautorizado, rc2.diascredito,
		rc2.fecharevision, rc2.fechapago, rc2.rotacioncobranza
		FROM reporte_cobranza2 rc2
		INNER JOIN reporte_cobranza5 rc5 ON rc2.foliocredito = rc5.foliocredito
		WHERE rc5.prefijosucursal = '$_GET[prefijosucursal]'
		GROUP BY rc2.idcliente";	
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
			<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="224.25"/>
			<Column ss:AutoFitWidth="0" ss:Width="87.75" ss:Span="2"/>
			<Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="63.75"/>
		'.$cabezera.'
		<Row ss:Index="4">
			<Cell ss:StyleID="s26"/>
			<Cell ss:StyleID="s26"/>
			<Cell ss:StyleID="s26"><Data ss:Type="String">MONTO</Data></Cell>
			<Cell ss:StyleID="s26"/>
			<Cell ss:StyleID="s26"><Data ss:Type="String">FECHA</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">FECHA</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">ROTACION DE</Data></Cell>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s27"><Data ss:Type="String"># CLIENTE</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">CLIENTE</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">AUTORIZADO</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">DIAS CREDITO</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">REVISION</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">PAGO</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">CARTERA</Data></Cell>
		</Row>';
		
	}else if($_GET[accion]==3){//REPORTE MONTO AUTORIZADO		
		
		$s = "SELECT DATE_FORMAT(fechacredito, '%d/%m/%Y') AS fecha, 
		montoautorizado, usuario, IFNULL(solicitud,'') AS solicitud
		FROM reporte_cobranza3
		WHERE idcliente = '$_GET[idcliente]'";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre FROM catalogocliente WHERE id=".$_GET[idcliente]."";
		$cl= mysql_query($s,$l) or die($s);
		$cc = mysql_fetch_object($cl);
		
		$xls .= '<Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="2" ss:Width="96.75"/>
   		<Column ss:AutoFitWidth="0" ss:Width="162"/>
	   <Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String">TITULO:</Data></Cell>
		<Cell ss:MergeAcross="2"><Data ss:Type="String">MONTO AUTORIZADO</Data></Cell>
	   </Row>
	   <Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String"></Data></Cell>
		<Cell ss:MergeAcross="2" ><Data ss:Type="String"></Data></Cell>
	   </Row>
	   <Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String">CLIENTE:</Data></Cell>
		<Cell ss:MergeAcross="2" ><Data ss:Type="String">'.cambio_texto($cc->nombre).'</Data></Cell>
	   </Row>
	   <Row ss:Index="5">
		<Cell ss:StyleID="s25"><Data ss:Type="String">FECHA DE</Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">MONTO</Data></Cell>
		<Cell ss:StyleID="s26"/>
		<Cell ss:StyleID="s26"/>
	   </Row>
	   <Row ss:Height="13.5">
		<Cell ss:StyleID="s27"><Data ss:Type="String">CREDITO </Data></Cell>
		<Cell ss:StyleID="s27"><Data ss:Type="String">AUTORIZADO</Data></Cell>
		<Cell ss:StyleID="s27"><Data ss:Type="String">MODIFICO</Data></Cell>
		<Cell ss:StyleID="s27"><Data ss:Type="String">SOLICITUD</Data></Cell>
	   </Row>';	
		
	}
	
if($filas>0){	
	$arre = array();
	$arr = 0;	
	while($f = mysql_fetch_array($r)){
		$arr = ($arr==0)? count($f) : $arr;
		$xls .= '<Row>';			
		for($i=0;$i<count($f)/2;$i++){
			if(is_numeric($f[$i])==false || $i==$idcliente){
			$xls .= '<Cell><Data ss:Type="String">'.(($f[$i]=="TODA LA SEMANA")?"TODOS":cambio_texto($f[$i])).'</Data></Cell>';
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
     <ActiveRow>5</ActiveRow>
     <ActiveCol>2</ActiveCol>
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