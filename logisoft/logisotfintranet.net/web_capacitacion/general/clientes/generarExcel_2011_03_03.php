<?
	function cambio_texto($texto){
		if($texto == " ")
			$texto = "";
		if($texto!=""){
			$n_texto=ereg_replace("�","&#224;",$texto);
			$n_texto=ereg_replace("�","&#233;",$n_texto);
			$n_texto=ereg_replace("�","&#237;",$n_texto);
			$n_texto=ereg_replace("�","&#243;",$n_texto);
			$n_texto=ereg_replace("�","&#250;",$n_texto);
			
			$n_texto=ereg_replace("�","&#193;",$n_texto);
			$n_texto=ereg_replace("�","&#201;",$n_texto);
			$n_texto=ereg_replace("�","&#205;",$n_texto);
			$n_texto=ereg_replace("�","&#211;",$n_texto);
			$n_texto=ereg_replace("�","&#218;",$n_texto);
			
			$n_texto=ereg_replace("�", "&#241;", $n_texto);
			$n_texto=ereg_replace("�", "&#209;", $n_texto);
			$n_texto=ereg_replace("�", "&#191;", $n_texto);
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
		<Cell ss:StyleID="s21"><Data ss:Type="String">'.(($_GET[sucursal]!="0")? $suc->prefijo : 'TODAS').'</Data></Cell>
	   </Row>
	   <Row>
		<Cell ss:StyleID="s22"/>
		<Cell ss:StyleID="s22"/>
		<Cell ss:StyleID="s22"/>
		<Cell ss:StyleID="s22"/>
	   </Row>';
	
	if($_GET[accion] == 1){//PRINCIPAL CLIENTES
		$s = "SELECT prefijosucursal AS sucursal, SUM(IF(CURDATE()<=vigencia,1,0)) AS vigentes,
		SUM(IF(CURDATE() > vigencia,1,0)) AS vencidos,
		SUM(IF(CURDATE()<=vigencia,1,0) + IF(CURDATE() > vigencia,1,0)) AS total, 
		SUM((IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) + IFNULL(ventasfacturaconsignacion,0) +
		IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + IFNULL(ventasnofacturaconsignacion,0)))AS importe 
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."" : '' )."
		GROUP BY idsucursal ORDER BY prefijosucursal";
		
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);		
		$filas = mysql_num_rows($r);
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   '.$titulo.'
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s25"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">VIGENTES </Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">VENCIDOS</Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">TOTAL</Data></Cell>
		<Cell ss:StyleID="s25"><Data ss:Type="String">IMPORTE</Data></Cell>
	   </Row>';
		
	}else if($_GET[accion] == 2){//Reporte de Facturaci�n 
		$s = "SELECT prefijosucursal, SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) +
		IFNULL(ventasfacturaconsignacion,0)) AS facturado, 
		SUM(IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + 
		IFNULL(ventasnofacturaconsignacion,0)) AS nofacturado,
		SUM(IFNULL(ventasfacturanormal,0) + IFNULL(ventasfacturaprepagada,0) + IFNULL(ventasfacturaconsignacion,0) +
		IFNULL(ventasnofacturanormal,0) + IFNULL(ventasnofacturaprepagada,0) + IFNULL(ventasnofacturaconsignacion,0)) AS totales
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal ORDER BY prefijosucursal";		
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
	   
	}else if($_GET[accion] == 3){//STD-ProcedimientoReporteTiposdeVentaFacturada_PM
		$s = "SELECT prefijosucursal, SUM(IFNULL(ventasfacturanormal,0)) AS normales, 
		SUM(IFNULL(ventasfacturaprepagada,0)) AS prepagadas,
		SUM(IFNULL(ventasfacturaconsignacion,0)) AS consignacion,
		SUM(IFNULL(ventasfacturanormal,0)) + SUM(IFNULL(ventasfacturaprepagada,0)) + 
		SUM(IFNULL(ventasfacturaconsignacion,0)) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."'  AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal ORDER BY prefijosucursal";
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
		
	}else if($_GET[accion] == 4){//STD-ProcedimientoReporteTiposdeVentasNoFacturada_PM
		$s = "SELECT idsucursal, prefijosucursal, SUM(IFNULL(ventasnofacturanormal,0)) AS normales, 
		SUM(IFNULL(ventasnofacturaprepagada,0)) AS prepagadas,
		SUM(IFNULL(ventasnofacturaconsignacion,0)) AS consignacion,
		SUM(IFNULL(ventasnofacturanormal,0)) + SUM(IFNULL(ventasnofacturaprepagada,0)) + 
		SUM(IFNULL(ventasnofacturaconsignacion,0)) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1") ? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idsucursal ORDER BY prefijosucursal";
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
		<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';	
		
	}else if($_GET[accion] == 5){//STD-ProcedimientoReportePrepagadasSinFacturar_PM
		$s = "SELECT t1.prefijosucursal, t1.idcliente, t1.cliente, IFNULL(t1.pcantidadguia,0) AS cantidad, t1.pfolios,
		IFNULL(t1.pflete,0) AS flete, t1.ptotal AS total FROM reportecliente1 t1
		INNER JOIN solicitudguiasempresariales s ON t1.idcliente = s.idcliente
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND s.factura = 0 AND s.prepagada = 'SI' AND t1.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t1.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t1.idcliente";
		$r = mysql_query($s,$l) or die($s);
		
		$filas = mysql_num_rows($r);
		$xls .= '<Table ss:ExpandedColumnCount="9" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
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
	    <Cell ss:StyleID="s23"><Data ss:Type="String">Q GUIAS</Data></Cell>
	    <Cell ss:StyleID="s23"><Data ss:Type="String">FOLIOS DE GUIAS</Data></Cell>
	    <Cell ss:StyleID="s23"><Data ss:Type="String">FLETE</Data></Cell>
    	<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';
	   
	}else if($_GET[accion] == 6){//STD-ProcedimientoReporteConsignacionSinFacturar_PM
		$s = "SELECT prefijosucursal, idcliente, cliente, IFNULL(ventasnofacturaconsignacion,0) AS porfacturar,
		IFNULL(csobrepeso,0) AS sobrepeso, IFNULL(cvalordeclarado,0) AS valordeclarado,
		IFNULL(csubdestino,0) AS costoead,
		SUM(IFNULL(ventasnofacturaconsignacion,0) + IFNULL(csobrepeso,0) + 
		IFNULL(cvalordeclarado,0) + IFNULL(csubdestino,0)) AS total
		FROM reportecliente1
		WHERE YEAR(fechacreacion) = '".$_GET[fecha]."' AND activo = 0
		".(($_GET[sucursal]!="1")? " AND idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY idcliente";
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
		<Cell ss:StyleID="s23"><Data ss:Type="String">IMP. POR FACTURAR</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">SOBREPESO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">VALOR DECLARADO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUB DESTINOS</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
	   </Row>';
		$idcliente = 1;
	}else if($_GET[accion] == 7){//� STD-ProcedimientoReporteFacturaradas_PM ?
		$s = "SELECT t4.prefijosucursal, t4.idcliente, t4.cliente, t4.tipoconvenio, 
		SUM(IFNULL(t4.facturadasnormales,0)) AS normales,
		SUM(IFNULL(t4.facturadasprepagadas,0)) AS prepagadas, 
		SUM(IFNULL(t4.facturadasconsignacion,0)) AS consignacion,
		SUM(IFNULL(t4.facturadasnormales,0) + IFNULL(t4.facturadasprepagadas,0) + IFNULL(t4.facturadasconsignacion,0)) AS total
		FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."'  AND t4.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t4.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t4.idcliente";
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
	}else if($_GET[accion] == 8){
		
		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, destinatario, guia, prefijodestino, 
		importe, IFNULL(factura,'') AS factura  FROM reportecliente5
		WHERE YEAR(fecha) = '".$_GET[fecha]."' AND idcliente = ".$_GET[cliente]." 
		AND factura IS NOT NULL AND estadofactura = 'A'";
		$r = mysql_query($s,$l) or die($s);	
		$filas = mysql_num_rows($r);
		
		$s = "SELECT (SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM catalogocliente WHERE id = ".$_GET[cliente].") AS cliente,
		(SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal].") AS sucursal ";
		$sq = mysql_query($s,$l) or die($s);
		$row = mysql_fetch_object($sq);
		
		$xls .='<Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
		   x:FullRows="1" ss:DefaultColumnWidth="60">
		   <Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="236.25"/>
		   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
		   </Row>
		   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.(($_GET[fecha]!="")? $_GET[fecha] : date('d/m/Y')).'</Data></Cell>
		   </Row>
		   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.$row->sucursal.'</Data></Cell>
		   </Row>
		   <Row>
			<Cell ss:StyleID="s21"><Data ss:Type="String">CLIENTE:</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[cliente]."  ".$row->cliente.'</Data></Cell>
		   </Row>
		   <Row ss:Index="6" ss:AutoFitHeight="0" ss:Height="13.5">
			<Cell ss:StyleID="s23"><Data ss:Type="String">FECHA</Data></Cell>
			<Cell ss:StyleID="s23"><Data ss:Type="String">GUIA</Data></Cell>
			<Cell ss:StyleID="s23"><Data ss:Type="String">REMITENTE / DESTINATARIO</Data></Cell>
			<Cell ss:StyleID="s23"><Data ss:Type="String">DESTINO</Data></Cell>
			<Cell ss:StyleID="s23"><Data ss:Type="String">IMPORTE</Data></Cell>
			<Cell ss:StyleID="s23"><Data ss:Type="String">FACTURA</Data></Cell>
		   </Row>';
		
	}else if($_GET[accion]==9){
		$s = "SELECT t4.prefijosucursal, t4.idcliente, t4.cliente, t4.tipoconvenio, 
		SUM(IFNULL(t4.nofacturadasnormales,0)) AS normales,
		SUM(IFNULL(t4.nofacturadasprepagadas,0)) AS prepagadas, 
		SUM(IFNULL(t4.nofacturadasconsignacion,0)) AS consignacion,
		SUM(IFNULL(t4.nofacturadasnormales,0) + IFNULL(t4.nofacturadasprepagadas,0) + 
		IFNULL(t4.nofacturadasconsignacion,0)) AS total
		FROM reportecliente4 t4
		INNER JOIN reportecliente1 t1 ON t4.convenio = t1.convenio
		WHERE YEAR(t1.fechacreacion) = '".$_GET[fecha]."' AND t4.activo = 0
		".(($_GET[sucursal]!="1") ? " AND t4.idsucursal = ".$_GET[sucursal]."":"")."
		GROUP BY t4.idcliente";
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
	}
 
 	if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false  || $i==$idcliente){
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