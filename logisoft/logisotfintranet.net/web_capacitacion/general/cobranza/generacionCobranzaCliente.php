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
  <Created>2009-11-10T15:47:10Z</Created>
  <LastSaved>2009-11-10T17:01:44Z</LastSaved>
  <Company>DPSoft</Company>
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
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font ss:Color="#000000" ss:Bold="1"/>
   <Interior/>
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
	
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";mysql_query($s,$l) or die($s);
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
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		/* proceso para llenar la temporal */
		$f1 = split("/",$_GET[fecha1]);
		$f2 = split("/",$_GET[fecha2]);
		$fecha1 = $f1[2]."-".$f1[1]."-".$f1[0];
		$fecha2 = $f2[2]."-".$f2[1]."-".$f2[0];
	
		/*total de registros*/
		$s = "SELECT ge.id 
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) AND ISNULL(ge.factura) 
		AND tc.idcliente=$_GET[idcliente] AND tc.idsucursal='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		UNION
		SELECT gv.id 
		FROM guiasventanilla gv 
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.cliente=$_GET[idcliente] AND 
		pg.sucursalacobrar='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION
		SELECT f.folio AS id 
		FROM facturacion f 
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.estadocobranza <> 'C'
		UNION	/* abonos */
		SELECT fp.guia AS id 
		FROM formapago fp 
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
		/*totales de los registros*/
		$s = "SELECT FORMAT(SUM(IFNULL(cargo,'')),2) cargos,FORMAT(SUM(IFNULL(abono,'')),2) abonos
		FROM(	/* cargos */
		SELECT $if AS cargo,0 AS abono 
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idcliente=$_GET[idcliente] AND tc.idsucursal='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		UNION
		SELECT gv.total AS cargo,0 AS abono 
		FROM guiasventanilla gv 
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.cliente=$_GET[idcliente] AND 
		pg.sucursalacobrar='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION
		SELECT (IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS cargo,0 AS abono 
		FROM facturacion f 
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial'
		UNION
		SELECT IFNULL(f.total,0) AS cargo,0 AS abono 
		FROM facturacion f 
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial'
		UNION	/* abonos */
		SELECT 0 AS cargo,fp.total AS abono 
		FROM formapago fp 
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2')t";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT fecha,sucursal,IFNULL(refcargo,'') AS referenciacargo,IFNULL(refabono,'') AS referenciaabono,cargos,abonos,saldo,descripcion	
		FROM(	/* cargos */
		SELECT ge.fecha,cs.prefijo AS sucursal,ge.id AS refcargo,0 AS refabono,$if AS cargos,0 AS abonos,$if AS saldo,'' AS descripcion
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idcliente=$_GET[idcliente] AND tc.idsucursal='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		UNION
		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS refcargo,0 AS refabono,gv.total AS cargos,0 AS abonos,gv.total AS saldo,'' AS descripcion
		FROM guiasventanilla gv	
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND 
		pg.cliente=$_GET[idcliente] AND pg.sucursalacobrar='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,f.folio AS refcargo,0 AS refabono,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) 
		AS cargos,0 AS abonos,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS saldo,'' AS descripcion
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial'
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,f.folio AS refcargo,0 AS refabono,IFNULL(f.total,0) AS cargos,0 AS abonos,IFNULL(f.total,0) AS saldo,'' AS descripcion
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial' 
		UNION	/* abonos */
		SELECT fp.fecha,cs.prefijo AS sucursal,0 AS refcargo,fp.guia AS refabono,0 AS cargos,fp.total AS abonos,fp.total AS saldo,
		CONCAT(IF(fp.efectivo>0,'EFECTIVO, ',''),IF(fp.tarjeta>0,'TARJETA, ',''),IF(fp.transferencia>0,'TRANSFERENCIA, ',''),
		IF(fp.cheque>0,CONCAT('CHEQUE ',IFNULL(fp.ncheque,'')),'')) AS descripcion
		FROM formapago fp 
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2')t1 ORDER BY fecha";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
	
	$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre FROM catalogocliente WHERE id=".$_GET[idcliente]."";
	$cl= mysql_query($s,$l) or die($s);
	$cc = mysql_fetch_object($cl);
		
  $xls .='<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="2" ss:Width="58.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="87.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="98.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="82.5" ss:Span="1"/>
   <Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="87.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="133.5"/>   
   <Row>
	<Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
	<Cell ss:MergeAcross="3" ss:StyleID="s23" ><Data ss:Type="String">ESTADO DE CUENTA</Data></Cell>
   </Row>
   <Row>
	<Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>
	<Cell ss:MergeAcross="3" ss:StyleID="s23" ><Data ss:Type="String">'.$_GET[fecha1].' AL '.$_GET[fecha2].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">CLIENTE:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s23" ><Data ss:Type="String">'.$cc->nombre.'</Data></Cell>
   </Row>
   <Row ss:Index="5" ss:AutoFitHeight="0" ss:Height="13.5">
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">REF. CARGO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">REF. ABONO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">CARGO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">ABONO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SALDO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">DESCRIPCION</Data></Cell>
   </Row>';
   $noformula = 3;
   if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==$noformula){
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
   $xls .='<Row>
    <Cell ss:Index="4" ss:StyleID="s21"><Data ss:Type="String">SALDO CONTABLE:</Data></Cell>
    <Cell ss:StyleID="s62" ss:Formula="=SUM(R[-1]C:R[-1]C[1])"><Data ss:Type="Number">0</Data></Cell>
   </Row>
  </Table>
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
     <ActiveRow>7</ActiveRow>
     <ActiveCol>4</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"ESTADO DE CUENTA.xls\"" ); 
	print $xls;
	
	$s = "DROP TABLE tmp_convenio$x";
		mysql_query($s,$l) or die($s); 
	
?>
