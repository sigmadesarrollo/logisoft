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
  <Style ss:ID="s49" ss:Name="Moneda">
	<NumberFormat
	ss:Format="_-&quot;$&quot;* #,##0.00_-;-\-&quot;$&quot;* #,##0.00_-;_-&quot;$&quot;* &quot;-&quot;??_-;_-@_-"/>
  </Style>
  <Style ss:ID="s62" ss:Parent="s49">
	<Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
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
		$x = rand(1,1000); 	
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";
		mysql_query($s,$l) or die($s);
		$s = "DROP TABLE IF EXISTS tmp_clientes$x";
		mysql_query($s,$l) or die($s);
		/* tabla de convenios */
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL
		FROM generacionconvenio GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio
		SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		/* tabla de clientes */
		$s = "CREATE TABLE `tmp_clientes$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`idcliente` DOUBLE DEFAULT NULL,
		`sucursal` DOUBLE DEFAULT NULL,
		`dcredito` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los clientes en la temporal
		$s = "INSERT INTO tmp_clientes$x
		SELECT NULL,idremitente AS idcliente,0 AS sucursal,0 AS dcredito 
		FROM guiasempresariales 
		WHERE tipopago='CREDITO' AND (tipoguia='CONSIGNACION' OR (tipoguia='PREPAGADA' AND (texcedente>0 OR tseguro>0))) 
		AND ISNULL(factura) GROUP BY idremitente
		UNION 
		SELECT NULL,pg.cliente,0 AS sucursal,0 AS dcredito FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' GROUP BY pg.cliente
		UNION
		SELECT NULL,cliente,0 AS sucursal,0 AS dcredito FROM facturacion WHERE credito='SI' AND ISNULL(fechacancelacion) AND 
		estadocobranza <> 'C' GROUP BY cliente;";
		mysql_query($s,$l) or die($s); 
		//agregar datos a la temporal
		$s = "UPDATE tmp_clientes$x temp INNER JOIN solicitudcredito sc ON temp.idcliente=sc.cliente
		SET dcredito=sc.diascredito, sucursal=sc.idsucursal";
		mysql_query($s,$l) or die($s); 
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
		$s = "SELECT prefijo AS sucursal,CONCAT('#%',SUM(clientes)) clientes,SUM(carteravigente) carteravigente,
		SUM(carteramorosa) carteramorosa,(SUM(carteravigente) + SUM(carteramorosa)) carteratotal
		FROM(
		SELECT cs.id,cs.prefijo,0 AS clientes,SUM(IF(ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,$if,0)) carteravigente,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,$if,0)) carteramorosa
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON tc.idcliente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) GROUP BY tc.idsucursal
		UNION
		SELECT cs.id,cs.prefijo,0 AS clientes,SUM(IF(ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,gv.total,0)) carteravigente,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,gv.total,0)) carteramorosa
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON pg.cliente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO'
		GROUP BY pg.sucursalacobrar
		UNION
		SELECT cs.id,cs.prefijo,0 AS clientes,SUM(IF(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,(IFNULL(f.total,0) + 
		IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)),0)) carteravigente,
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) carteramorosa
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial'
		GROUP BY f.idsucursal
		UNION
		SELECT cs.id,cs.prefijo,0 AS clientes,SUM(IF(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,IFNULL(f.total,0),0)) carteravigente,
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,IFNULL(f.total,0),0)) carteramorosa
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial'
		GROUP BY f.idsucursal
		UNION
		SELECT cs.id,cs.prefijo, COUNT(DISTINCT temp.idcliente) AS clientes,0 AS carteravigente,0 AS carteramorosa
		FROM tmp_clientes$x temp INNER JOIN catalogosucursal cs ON temp.sucursal=cs.id GROUP BY temp.sucursal
		)t1 GROUP BY prefijo ORDER BY prefijo";
		$r = mysql_query($s,$l) or die ($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
			<Column ss:Index="2" ss:Width="76.5"/>
			<Column ss:AutoFitWidth="0" ss:Width="87.75" ss:Span="2"/>
		'.$cabezera.'		
		<Row ss:Index="4">
			<Cell ss:StyleID="s26"><Data ss:Type="String">SUCURSAL</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">CLIENTES</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">CARTERA</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">CARTERA</Data></Cell>
			<Cell ss:StyleID="s26"><Data ss:Type="String">CARTERA</Data></Cell>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s23"/>
			<Cell ss:StyleID="s27"><Data ss:Type="String"></Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">VIGENTE</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">MOROSA</Data></Cell>
			<Cell ss:StyleID="s27"><Data ss:Type="String">TOTAL</Data></Cell>
		</Row>';
		
		$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s); 
		$s = "DROP TABLE tmp_clientes$x";
			mysql_query($s,$l) or die($s); 
				
	}else if($_GET[accion]==2){//COBRANZA CLIENTES CON CREDITO
		$s = "SELECT cliente AS idcliente,CONCAT(nombre,' ',paterno,' ',materno,' ') cliente, montoautorizado,CONCAT('#',diascredito) diascredito,
		CONCAT(IF(semanarevision=1,'TODOS',''),IF(lunesrevision=1,'L',''),IF(martesrevision=1,'MA',''),IF(miercolesrevision=1,'MI',''),
		IF(juevesrevision=1,'J',''),IF(viernesrevision=1,'V','')) fecharevision,
		CONCAT(IF(semanapago=1,'TODOS',''),IF(lunespago=1,'L',''),IF(martespago=1,'MA',''),IF(miercolespago=1,'MI',''),
		IF(juevespago=1,'J',''),IF(viernespago=1,'V','')) fechapago,'C-0' AS rotacioncobranza
		FROM solicitudcredito 
		WHERE idsucursal=".$_GET[prefijosucursal]." 
		GROUP BY cliente";	
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
		
		$s = "SELECT DATE_FORMAT(sc.fechaactivacion, '%d/%m/%Y') AS fecha,sc.montoautorizado,
		CONCAT(ce.nombre,' ',ce.apellidopaterno,' ',ce.apellidomaterno) usuario,CONCAT('#',sc.folio) AS solicitud
		FROM solicitudcredito sc 
		INNER JOIN catalogoempleado ce ON sc.idusuario=ce.id 
		WHERE sc.cliente='$_GET[idcliente]'";
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
		<Cell ss:StyleID="s25"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String"></Data></Cell>
		<Cell ss:StyleID="s26"/>
		<Cell ss:StyleID="s26"/>
	   </Row>
	   <Row ss:Height="13.5">
		<Cell ss:StyleID="s27"><Data ss:Type="String">CREDITO </Data></Cell>
		<Cell ss:StyleID="s27"><Data ss:Type="String">IMPORTE</Data></Cell>
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
				if(substr($f[$i],0,1)=='#'){
					$f[$i]=str_replace('#','',$f[$i]);
				}
				if(substr($f[$i],0,1)=='%'){
					$f[$i]=str_replace('%','',$f[$i]);
					$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "X";
				}else{
					$xls .= '<Cell><Data ss:Type="String">'.(($f[$i]=="TODA LA SEMANA")?"TODOS":cambio_texto($f[$i])).'</Data></Cell>';
					$arre[$i+1] = "NO";
				}
			}else if(is_numeric($f[$i])){
				$xls .= '<Cell ss:StyleID="s62"><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
				$arre[$i+1] = "SI";
			}				
		}
		$xls .= '</Row>';
	}
	
	 $xls .='<Row>
	<Cell ss:StyleID="s25"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
	for($i=1;$i<$arr;$i++){
		if($arre[$i] == "SI"){
		$xls .= '<Cell ss:StyleID="s62" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
		}elseif($arre[$i] == "X"){
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