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
	  <Author>PMM</Author>
	  <LastAuthor>PMM</LastAuthor>
	  <LastPrinted>2009-10-16T15:01:55Z</LastPrinted>
	  <Created>'.date("d/m/Y").'</Created>
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
	  <Style ss:ID="s21">
	   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
	   <Borders/>
	   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
	  </Style>
	  <Style ss:ID="s22">
	   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
	   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
	  </Style>
	  <Style ss:ID="s23">
	   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
	   <Borders>
		<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
	   </Borders>
	   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
	  </Style>
	  <Style ss:ID="s24">
	   <Font ss:Size="9"/>
	   <NumberFormat ss:Format="Standard"/>
	  </Style>
	  <Style ss:ID="s25">
	   <Font x:Family="Swiss" ss:Bold="1"/>
	  </Style>
	  <Style ss:ID="s26">
	   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
	   <NumberFormat ss:Format="Standard"/>
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
	
		if (is_numeric($_GET[sucursal]) ){
			if($_GET[sucursal]!="0"){
				$s = "SELECT prefijo FROM catalogosucursal WHERE id=".$_GET[sucursal]."";
				$sq = mysql_query($s,$l) or die($s);
				$suc = mysql_fetch_object($sq);
			}
		}else{
			$s = "SELECT prefijo FROM catalogosucursal WHERE prefijo='".$_GET[sucursal]."'";
			$sq = mysql_query($s,$l) or die($s);
			$suc = mysql_fetch_object($sq);
		}
		
	$titulo ='<Row>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
	   </Row>
	   <Row>
		<Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">'.(($_GET[fecha]!="")? $_GET[fecha] : date("d/m/Y")).'</Data></Cell>
	   </Row>
	   <Row>
		<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL:</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TODAS</Data></Cell>
	   </Row>
	   <Row>
		<Cell ss:StyleID="s22"/>
		<Cell ss:StyleID="s22"/>
		<Cell ss:StyleID="s22"/>
		<Cell ss:StyleID="s22"/>
	   </Row>';
	
	if($_GET[accion] == 1){//PRINCIPAL CLIENTES
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";
		mysql_query($s,$l) or die($s);
		// tabla de convenios 
		$s = "CREATE TABLE `tmp_convenio$x` (
			`id` DOUBLE NOT NULL AUTO_INCREMENT,
			`folio` DOUBLE DEFAULT NULL,
			`idcliente` DOUBLE DEFAULT NULL,
			`estado` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,
			`sucursal` DOUBLE DEFAULT NULL,
			`fecha` DATE,
			PRIMARY KEY  (`id`),
			KEY  `idcliente` (`idcliente`)
			) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
			SELECT NULL,MAX(folio) folio,idcliente,NULL,NULL,NULL
			FROM generacionconvenio GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio 
		SET tc.estado=gc.estadoconvenio,tc.sucursal=gc.sucursal, tc.fecha=gc.fecha;";
		mysql_query($s,$l) or die($s); 
		
		$s = "SELECT t2.sucursal,CONCAT('#%',IFNULL(SUM(IF(t1.estado ='ACTIVADO' AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0)) AS vigentes,
		CONCAT('#%',IFNULL(SUM(IF(t1.estado ='EXPIRADO' AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0)) AS vencidos,
		CONCAT('#%',IFNULL(SUM(IF((t1.estado ='EXPIRADO' OR t1.estado ='ACTIVADO') AND YEAR(t1.fecha)='".$_GET[fecha]."',1,0)),0)) total,
		t2.timporte AS importe 
		FROM tmp_convenio$x t1
		INNER JOIN (
		SELECT SUM(IF(tipoventa!='GUIA EMPRESARIAL',total,0) + IF(tipoventa='GUIA EMPRESARIAL',total,0)) timporte,prefijoorigen AS sucursal,idsucorigen 
		FROM reportes_ventas
		WHERE IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion)='".$_GET[fecha]."',YEAR(fecharealizacion)='".$_GET[fecha]."') 
		AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) ".(($_GET[sucursal]==1)?"":" AND idsucorigen = $_GET[sucursal]")."
		GROUP BY sucursal) AS t2 ON t1.sucursal=t2.idsucorigen
		GROUP BY t2.sucursal ORDER BY t2.sucursal";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);		
		$filas = mysql_num_rows($r);
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   '.$titulo.'
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s25"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">CONVENIOS_VIGENTES </Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">CONVENIOS_VENCIDOS</Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">TOTALES_CONVENIOS</Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">IMPORTE</Data></Cell>
	   </Row>';
	  
	  $s = "DROP TABLE tmp_convenio$x";
		mysql_query($s,$l) or die($s);
		
	}else if($_GET[accion] == 2){// VENTAS POR CONVENIO 
		$s = "SELECT (cs.prefijo) AS prefijosucursal,SUM(IFNULL(t1.tfacturado,0)) AS facturado,
		SUM(IFNULL(t1.tnofacturado,0)) AS nofacturado,SUM(IFNULL(t1.tfacturado,0)) + SUM(IFNULL(t1.tnofacturado,0)) AS totales
		FROM catalogosucursal cs
		INNER JOIN(SELECT SUM(IF(NOT ISNULL(factura),total,0)) AS tfacturado, idsucorigen,
		SUM(IF(ISNULL(factura),total,0)) AS tnofacturado FROM reportes_ventas 
		WHERE IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') 
		AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) ".(($_GET[sucursal]==1)?"":" AND idsucorigen = $_GET[sucursal]")."
		GROUP BY idsucorigen) t1 ON cs.id=t1.idsucorigen WHERE t1.tfacturado>0 OR t1.tnofacturado>0 GROUP BY prefijosucursal ORDER BY prefijosucursal";	
		$r = mysql_query($s,$l) or die($s);	
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="98.25"/>
	   '.$titulo.'
	   <Row ss:Height="13.5">
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">FACTURADO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">NO FACTURADO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TOTALES</Data></Cell>
	   </Row>';
	}else if($_GET[accion] == 3){//VENTAS CON COVENIO FACTURADAS
		$s = "SELECT (prefijoorigen) AS prefijosucursal,SUM(IF(tipoventa='GUIA VENTANILLA',total,0)) AS normales, 
		SUM(IF(tipoventa='SOLICITUD DE FOLIOS',total,0)) AS prepagadas, 
		SUM(IF(tipoventa='GUIA EMPRESARIAL' AND tipoempresarial='CONSIGNACION',total,0)) AS consignacion,
		SUM(IF(tipoventa='FACTURA OTROS' OR tipoventa='FACTURA EXCEDENTE',total,0)) AS otros,
		SUM(IF(tipoventa='GUIA VENTANILLA',total,0) + IF(tipoventa='SOLICITUD DE FOLIOS',total,0) +
		IF(tipoventa='GUIA EMPRESARIAL' AND tipoempresarial='CONSIGNACION',total,0) + 
		IF(tipoventa='FACTURA OTROS' OR tipoventa='FACTURA EXCEDENTE',total,0)) AS total		
		FROM reportes_ventas 
		WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND IF(tipoventa <> 'GUIA VENTANILLA',
		YEAR(fechafacturacion)='".$_GET[fecha]."', YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND NOT ISNULL(factura) ".(($_GET[sucursal]==1)?"":" AND idsucorigen = $_GET[sucursal]")." 
		GROUP BY prefijosucursal ORDER BY prefijosucursal";
		$r = mysql_query($s,$l) or die($s);		
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="3" ss:Width="66.75"/>
	   <Column ss:Width="73.5"/>
	   '.$titulo.'
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">NORMALES </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">PREPAGADAS </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CONSIGNACION </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">OTROS </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';
	}else if($_GET[accion] == 4){//VENTAS CON CONVENIO SIN FACTURAR
		/* tabla temp */
		$s = "CREATE TEMPORARY TABLE `tmp_convenio` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND 
		estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT cs.prefijo AS prefijosucursal,SUM(t.tnormales) normales,SUM(t.tprepagadas) prepagadas,
		SUM(t.tconsignacion) consignacion,SUM(t.tnormales) + SUM(t.tprepagadas) + SUM(t.tconsignacion) total
		FROM(
		SELECT idsucorigen AS sucursalacobrar,SUM(IF(tipoventa='GUIA VENTANILLA',total,0)) tnormales,0 AS tprepagadas,0 AS tconsignacion
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND activo='S' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (ISNULL(factura) OR factura=0) ".(($_GET[sucursal]==1)?"":" AND idsucorigen = $_GET[sucursal]")." 
		GROUP BY idsucorigen
		UNION
		SELECT sucursalacobrar,0 AS tnormales,SUM(IFNULL(total,0)) AS tprepagadas,0 AS tconsignacion
		FROM solicitudguiasempresariales WHERE prepagada='SI' AND estado!='CANCELADA' AND (ISNULL(factura) OR factura=0) 
		AND YEAR(fecha)='".$_GET[fecha]."' ".(($_GET[sucursal]==1)?"":" AND sucursalacobrar = $_GET[sucursal]")." 
		GROUP BY sucursalacobrar
		UNION
		SELECT tc.idsucursal AS sucursalacobrar,0 AS tnormales,0 AS tprepagadas,SUM(IF(ge.tipoguia='CONSIGNACION',ge.total,0)) AS tconsignacion
		FROM guiasempresariales ge INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente
		WHERE (ISNULL(ge.factura) OR ge.factura=0) AND YEAR(ge.fecha)='".$_GET[fecha]."' 
		AND NOT ISNULL(ge.total) AND ge.total!=0 ".(($_GET[sucursal]==1)?"":" AND tc.idsucursal = $_GET[sucursal]")." 
		GROUP BY tc.idsucursal )t INNER JOIN catalogosucursal cs ON t.sucursalacobrar=cs.id GROUP BY sucursalacobrar ORDER BY prefijo";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="3" ss:Width="66.75"/>
	   <Column ss:Width="73.5"/>
	   '.$titulo.'
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">NORMALES </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">PREPAGADAS </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CONSIGNACION </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';	
			
	}else if($_GET[accion] == 5){//PREPAGADAS SIN FACTURAR
		$x = rand(1,1000); 	
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";
		/* tabla temp */
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' 
		GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT cs.prefijo AS prefijosucursal,CONCAT('#',temp.idcliente) idcliente,temp.cliente,CONCAT('#',sge.id) nventa,
		CONCAT('#%',IFNULL(sge.cantidad,0)) cantidad,CONCAT(sge.desdefolio,'-',sge.hastafolio) pfolios,sge.fecha,IFNULL(SUM(sge.subtotal),0) flete,
		t.excedente AS sobrepeso,t.seguro AS costoseguro,t.costoead AS costoead,SUM(sge.total) AS total
		FROM solicitudguiasempresariales sge 
		INNER JOIN tmp_convenio$x temp ON sge.idcliente=temp.idcliente
		INNER JOIN catalogosucursal cs ON temp.idsucursal=cs.id
		LEFT JOIN (
		SELECT SUM(IFNULL(ge.tseguro,0)) seguro,SUM(IFNULL(ge.texcedente,0)) excedente,SUM(IFNULL(ge.tcostoead,0)) costoead,tc.idsucursal,tc.idcliente 
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente=tc.idcliente 
		WHERE ISNULL(ge.factura) AND ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0) AND YEAR(ge.fecha)='".$_GET[fecha]."' 
		".(($_GET[sucursal]==1)?"":" AND tc.idsucursal = $_GET[sucursal]")."
		GROUP BY tc.idcliente)t 
		ON sge.sucursalacobrar=t.idsucursal AND sge.idcliente=t.idcliente
		WHERE sge.prepagada='SI' AND (ISNULL(sge.factura) OR sge.factura=0) AND YEAR(sge.fecha) = '".$_GET[fecha]."' AND 
		sge.estado!='CANCELADA' AND NOT ISNULL(sge.total) AND sge.total!=0 ".(($_GET[sucursal]==1)?"":" AND sge.sucursalacobrar = $_GET[sucursal]")."
		GROUP BY temp.idcliente ORDER BY cs.prefijo";
		$r = mysql_query($s,$l) or die($s);
		
		$filas = mysql_num_rows($r);
		$xls .= '<Table ss:ExpandedColumnCount="12" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	    x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="180"/>
	    <Column ss:Index="5" ss:AutoFitWidth="0" ss:Width="106.5"/>
	    <Column ss:Index="7" ss:Width="66"/>
	    <Column ss:Width="84.75"/>
	    <Column ss:Width="78"/>
		 '.$titulo.'	
		<Row ss:Index="5" ss:Height="13.5">
	    <Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL</Data></Cell>
	    <Cell ss:StyleID="s23"><Data ss:Type="String"># CLIENTE</Data></Cell>
	    <Cell ss:StyleID="s23"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String"># VENTA</Data></Cell>
	    <Cell ss:StyleID="s23"><Data ss:Type="String">Q GUIAS</Data></Cell>
	    <Cell ss:StyleID="s23"><Data ss:Type="String">GUIAS</Data></Cell>
	    <Cell ss:StyleID="s23"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">FLETE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">SOBRE PESO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">COSTO SEGURO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">EAD</Data></Cell>
    	<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';
	   
	  $s = "DROP TABLE tmp_convenio$x;";
			mysql_query($s,$l) or die($s);
		
	}else if($_GET[accion] == 6){//CONSIGNACION SIN FACTURAR
		/* tabla temp */
		$s = "CREATE TEMPORARY TABLE `tmp_convenio` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		$s = "SELECT cs.prefijo AS prefijosucursal,tc.idcliente,tc.cliente,SUM(ge.tflete) porfacturar,SUM(IFNULL(ge.texcedente,0)) sobrepeso,
		SUM(IFNULL(ge.tseguro,0)) valordeclarado,SUM(IFNULL(ge.tcostoead,0)) costoead,SUM(ge.total) total 
		FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id 
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente 
		WHERE YEAR(ge.fecha) = '".$_GET[fecha]."' AND ge.tipoguia='CONSIGNACION' AND (ISNULL(ge.factura) OR ge.factura=0)
		AND NOT ISNULL(ge.idremitente) AND NOT ISNULL(ge.total) AND ge.total!=0 ".(($_GET[sucursal]==1)?"":" AND cs.id = $_GET[sucursal]")."
		GROUP BY tc.idcliente ORDER BY cliente ";
		$r = mysql_query($s,$l) or die($s);		
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="178.5"/>
	   <Column ss:Width="106.5"/>
	   <Column ss:Width="66"/>
	   <Column ss:Width="102.75"/>
	   <Column ss:Width="78"/>
	    '.$titulo.'	
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String"># CLIENTE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">FLETE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">SOBREPESO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">VALOR DECLARADO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUB DESTINOS</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">IMPORTE POR FACTURAR</Data></Cell>
	   </Row>';
		$idcliente = 1;
	}else if($_GET[accion] == 7){//VENTAS CON COVENIO FACTURADAS
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";
		mysql_query($s,$l) or die($s);
		
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`tipoconvenio` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente,NULL
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' 
		GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal,tipoconvenio= CONCAT(IF(precioporkg=1,'KG, ',''),IF(precioporcaja=1,'CAJA, ',''),
		IF(descuentosobreflete=1,'DESCUENTO, ',''),IF(prepagadas=1,'PREPAGADAS, ',''),IF(consignacionkg=1,'C. KG, ',''),
		IF(consignacioncaja=1,'C. CAJA, ',''),IF(consignaciondescuento=1,'C. DESC., ',''));";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT (prefijoorigen) AS prefijosucursal,t2.idcliente,t2.cliente,t2.tipoconvenio,
		ROUND(SUM(IF(rv.tipoventa='GUIA VENTANILLA',total,0)),2) AS normales, 
		ROUND(SUM(IF(rv.tipoventa='SOLICITUD DE FOLIOS',total,0)),2) AS prepagadas, 
		ROUND(SUM(IF(rv.tipoventa='GUIA EMPRESARIAL' AND rv.tipoempresarial='CONSIGNACION',total,0)),2) AS consignacion,
		ROUND(SUM(IF(rv.tipoventa='FACTURA OTROS' OR rv.tipoventa='FACTURA EXCEDENTE',total,0)),2) AS otros,
		ROUND(SUM(IF(rv.tipoventa='GUIA VENTANILLA',total,0) + IF(rv.tipoventa='SOLICITUD DE FOLIOS',total,0) +
		IF(rv.tipoventa='GUIA EMPRESARIAL' AND rv.tipoempresarial='CONSIGNACION',total,0) + 
		IF(rv.tipoventa='FACTURA OTROS' OR rv.tipoventa='FACTURA EXCEDENTE',total,0)),2) AS total
		FROM reportes_ventas rv 
		INNER JOIN tmp_convenio$x t2 ON rv.idsucorigen=t2.idsucursal AND rv.idcliente=t2.idcliente
		WHERE IF(rv.tipoventa <> 'GUIA VENTANILLA', YEAR(rv.fechafacturacion)='".$_GET[fecha]."', YEAR(rv.fecharealizacion)='".$_GET[fecha]."') 
		AND rv.activo='S' AND(NOT ISNULL(rv.convenio) AND rv.convenio!=0) AND (NOT ISNULL(rv.factura)) AND rv.idsucorigen=".$_GET[sucursal]."
		GROUP BY rv.idcliente ORDER BY prefijosucursal";
		$r = mysql_query($s,$l) or die($s);	
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="9" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="180"/>
	   <Column ss:Width="72.75"/>
	   <Column ss:Index="6" ss:Width="64.5"/>
	   <Column ss:Width="71.25"/>
	   '.$titulo.'
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String"># CLIENTE </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TIPO CONVENIO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">NORMALES</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">PREPAGADAS</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CONSIGNACION</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">OTROS</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';	
	   	$idcliente = 1;
		$s = "DROP TABLE tmp_convenio$x;";
		mysql_query($s,$l) or die($s);
			  
	}else if($_GET[accion] == 8){ //RELACION DE ENVIOS FACTURADOS POR CLIENTE
		$s = "SELECT DATE_FORMAT(fecharealizacion,'%d/%m/%Y') fecha,prefijoorigen,nombrecliente,folio AS guia,recibe AS destinatario,prefijodestino,
		total AS importe, CONCAT('#',factura)	
		FROM reportes_ventas 
		WHERE YEAR(fecharealizacion)='".$_GET[fecha]."' AND IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion)='".$_GET[fecha]."',
		YEAR(fecharealizacion)='".$_GET[fecha]."') AND activo='S' AND (NOT ISNULL(convenio) AND convenio!=0) AND (NOT ISNULL(factura)) AND 
		idcliente =".$_GET[cliente]." AND idsucorigen=".$_GET[sucursal]."";
		$r = mysql_query($s,$l) or die($s);	
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
		   x:FullRows="1" ss:DefaultColumnWidth="60">
		   <Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="236.25"/>
		   <Column ss:Index="5" ss:AutoFitWidth="0" ss:Width="236.25"/>
		   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
		   </Row>
		   <Row ss:Index="5" ss:AutoFitHeight="0" ss:Height="13.5">
		   	<Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>
		   	<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL:</Data></Cell>
		   	<Cell ss:StyleID="s21"><Data ss:Type="String">CLIENTE:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">GUIA</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">REMITENTE / DESTINATARIO</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">DESTINO</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">IMPORTE</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">FACTURA</Data></Cell>
		   </Row>';
	}else if($_GET[accion]==9){ //VENTAS SIN CONVENIO SIN FACTURAR
		/* tabla temp */
		$s = "CREATE TEMPORARY TABLE `tmp_convenio` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`tipoconvenio` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente,NULL
		FROM generacionconvenio 
		WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal,tipoconvenio= CONCAT(IF(precioporkg=1,'KG, ',''),IF(precioporcaja=1,'CAJA, ',''),
		IF(descuentosobreflete=1,'DESCUENTO, ',''),IF(prepagadas=1,'PREPAGADAS, ',''),IF(consignacionkg=1,'C. KG, ',''),
		IF(consignacioncaja=1,'C. CAJA, ',''),IF(consignaciondescuento=1,'C. DESC., ',''));";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT cs.prefijo AS prefijosucursal,CONCAT('#',t.idcliente),t.cliente,t.tipoconvenio,
		SUM(t.tnormales) normales,SUM(t.tprepagadas) prepagadas,SUM(t.tconsignacion) consignacion,SUM(t.tnormales) + 
		SUM(t.tprepagadas) + SUM(t.tconsignacion) total
		FROM(
		SELECT idsucorigen AS sucursalacobrar,idcliente,nombrecliente AS cliente,'' AS tipoconvenio,SUM(IFNULL(total,0)) tnormales,
		0 AS tprepagadas,0 AS tconsignacion
		FROM reportes_ventas WHERE YEAR(fecharealizacion) = '".$_GET[fecha]."' AND activo='S' AND tipoventa='GUIA VENTANILLA' AND
		(NOT ISNULL(convenio) AND convenio!=0) AND (ISNULL(factura) OR factura=0) AND idsucorigen=".$_GET[sucursal]." 
		GROUP BY idcliente
		UNION
		SELECT sucursalacobrar,idcliente,CONCAT(nombre,' ',apepat,'',apemat) cliente,'' AS tipoconvenio,0 AS tnormales,
		SUM(IFNULL(total,0)) AS tprepagadas,0 AS tconsignacion
		FROM solicitudguiasempresariales WHERE prepagada='SI' AND estado!='CANCELADA' AND (ISNULL(factura) OR factura=0) 
		AND YEAR(fecha)='".$_GET[fecha]."' AND sucursalacobrar=".$_GET[sucursal]." GROUP BY idcliente
		UNION
		SELECT tc.idsucursal AS sucursalacobrar,tc.idcliente,tc.cliente,tc.tipoconvenio,0 AS tnormales,0 AS tprepagadas,
		SUM(IF(ge.tipoguia='CONSIGNACION',ge.total,0)) AS tconsignacion
		FROM guiasempresariales ge INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente
		WHERE (ISNULL(ge.factura) OR ge.factura=0) AND YEAR(ge.fecha)='".$_GET[fecha]."' AND tc.idsucursal=".$_GET[sucursal]."
		GROUP BY tc.idcliente
		)t INNER JOIN catalogosucursal cs ON t.sucursalacobrar=cs.id GROUP BY t.idcliente ORDER BY t.idcliente";
		$r = mysql_query($s,$l) or die($s);	
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="180"/>
	   <Column ss:Width="72.75"/>
	   <Column ss:Index="6" ss:Width="64.5"/>
	   <Column ss:Width="71.25"/>
	   '.$titulo.'
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String"># CLIENTE </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TIPO CONVENIO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">NORMALES</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">PREPAGADAS</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CONSIGNACION</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';	
	   $idcliente = 1;
	}else if($_GET[accion] == 10){	//GUIAS SIN FACTURAR
		/* tabla temp */
		$s = "CREATE TEMPORARY TABLE `tmp_convenio` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		`cliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL,CONCAT(nombre,' ',apaterno,' ',amaterno) AS cliente
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' 
		GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio temp INNER JOIN generacionconvenio gc ON temp.folio=gc.folio
		SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		$s = "SELECT ge.id,ge.tipoguia,ge.fecha,ge.tflete,ge.ttotaldescuento,ge.texcedente,ge.tcostoead ,ge.trecoleccion,ge.tseguro,
		ge.tcombustible,ge.totros,ge.subtotal,ge.tiva,ge.ivaretenido,ge.total
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio tc ON ge.idremitente=tc.idcliente 
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id 
		WHERE YEAR(ge.fecha) = '".$_GET[fecha]."' AND ge.tipoguia='CONSIGNACION' AND (ISNULL(ge.factura) OR ge.factura=0)
		AND NOT ISNULL(ge.idremitente) AND NOT ISNULL(ge.total) AND ge.total!=0 AND tc.idcliente='".$_GET[cliente]."' AND cs.prefijo='".$_GET[prefijo]."'
		GROUP BY ge.id ORDER BY ge.fecha ";
		$r = mysql_query($s,$l) or die($s);		
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="15" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="100">
	   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
	   </Row>
	   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[fecha].'</Data></Cell>
	   </Row>
	   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[prefijo].'</Data></Cell>
	   </Row>
	   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">NO. CLIENTE:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[cliente].'</Data></Cell>
	   </Row>
	   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String"></Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String"></Data></Cell>
	   </Row>
	   <Row ss:Index="6" ss:Height="13.5">
		<Cell ss:StyleID="s21"><Data ss:Type="String">FOLIO</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TIPO GUIA</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">FLETE</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">DESCUENTO</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">EXCEDENTE</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">EAD</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">RECOLECCION</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">SEGURO</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">COMBUSTIBLE</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">OTROS</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">SUBTOTAL</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">IVA</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">IVA RETENIDO</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';
		$idcliente = 1;
	}
 
 	if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false  || $i==$idcliente){
					if(substr($f[$i],0,1)=='#'){
						$f[$i]=str_replace('#','',$f[$i]);
					}
					if(substr($f[$i],0,1)=='%'){
						$f[$i]=str_replace('%','',$f[$i]);
						$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
						$arre[$i+1] = "X";
					}else{
						$xls .= '<Cell><Data ss:Type="String">'.cambio_texto($f[$i]).'</Data></Cell>';
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
	header ("Content-Disposition: attachment; filename=\"".$_GET[titulo].".xls\"" ); 
	print $xls;   
?>