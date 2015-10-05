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
  <Author>PMM</Author>
  <LastAuthor>PMM</LastAuthor>
  <Created>'.date('d/m/Y').'</Created>
  <LastSaved>2009-11-02T15:23:01Z</LastSaved>
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
  <Style ss:ID="s49" ss:Name="Moneda">
	<NumberFormat
	ss:Format="_-&quot;$&quot;* #,##0.00_-;-\-&quot;$&quot;* #,##0.00_-;_-&quot;$&quot;* &quot;-&quot;??_-;_-@_-"/>
  </Style>
  <Style ss:ID="s62" ss:Parent="s49">
	<Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
  </Style>
  <Style ss:ID="s21">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s22">
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">';
		require_once("../../ConectarSolo.php");
		$l = Conectarse("webpmm");
 
 
 	$titulo = '<Row>
    <Cell ss:StyleID="s22"><Data ss:Type="String">TITULO:</Data></Cell>
	<Cell ss:StyleID="s22"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s22"><Data ss:Type="String">FECHA: </Data></Cell>
	<Cell ss:StyleID="s22"><Data ss:Type="String">'.$_GET[fechainicio].' AL '.$_GET[fechafin].'</Data></Cell>
    <Cell ss:Index="3" ss:StyleID="s22"><Data ss:Type="String"></Data></Cell>
	<Cell ss:StyleID="s22"><Data ss:Type="String"></Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s22"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s22"/>
   </Row>';
 
 	if ($_GET[accion]==1){
		
		$s = "SELECT prefijoorigen,vendedor,SUM(IFNULL(flete,0)) flete,SUM(IFNULL(vtascobradas,0)) vtascobradas
		FROM(
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,
			SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.tflete-IFNULL(gv.ttotaldescuento,0),0)) vtascobradas
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
			WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,0 AS flete,
			SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.tflete-IFNULL(gv.ttotaldescuento,0),0)) vtascobradas
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
			WHERE gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,SUM(ge.tflete-IFNULL(ge.ttotaldescuento,0)) flete,
			0 AS vtascobradas
			FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON ge.clienteconvenio=cc.id
			WHERE ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) flete,
			SUM(f.flete-IFNULL(f.totaldescuento,0)) AS vtascobradas 
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='NO' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
			GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,0 AS flete,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS vtascobradas 
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
			INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
			WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.estadocobranza='C'
			GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,0 AS vtascobradas 
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
			GROUP BY gc.vendedor
		)t GROUP BY idvendedor ORDER BY prefijoorigen";
 		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
	  $xls .='<Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="231"/>
	   <Column ss:AutoFitWidth="0" ss:Width="72"/>
	   <Column ss:Width="81.75"/>
	   '.$titulo.'
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">VENDEDOR</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">VENTAS</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">VENTAS COBRADAS</Data></Cell>
	   </Row>';
	   
	}else if($_GET[accion] == 2){
		$s = "SELECT prefijo AS prefijosucursal,nvendedor AS vendedor,SUM(flete) flete,SUM(comision) comision
		FROM(
		SELECT cs.prefijo,gc.vendedor,gc.nvendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,
		SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) comision
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id	LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.tipoflete=0 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
		GROUP BY gc.vendedor
		UNION
		SELECT cs.prefijo,gc.vendedor,gc.nvendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,
		SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) comision
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id	LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
		GROUP BY gc.vendedor
		UNION
		SELECT cs.prefijo,gc.vendedor,gc.nvendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,
		SUM((f.flete-IFNULL(f.totaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision
		FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
		INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND f.credito='NO' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
		GROUP BY gc.vendedor
		UNION
		SELECT cs.prefijo,gc.vendedor,gc.nvendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,
		SUM((f.flete-IFNULL(f.totaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision
		FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
		INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
		WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.estadocobranza='C'
		GROUP BY gc.vendedor )t GROUP BY vendedor ORDER BY prefijo ";
		$r = mysql_query($s,$l)or die($s); 
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="231"/>
	   <Column ss:AutoFitWidth="0" ss:Width="80"/>
	   <Column ss:Width="81.75"/>
	   '.$titulo.'
	   <Row ss:Index="5" ss:Height="13.5">
		<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">VENDEDOR</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">VENTAS COBRADAS</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">COMISION</Data></Cell>
	   </Row>';
	}

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
					$xls .= '<Cell ss:StyleID="s62"><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}
		
		 $xls .='<Row>
	  	<Cell ss:StyleID="s22"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s62" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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
    <PaperSizeIndex>9</PaperSizeIndex>
    <VerticalResolution>0</VerticalResolution>
   </Print>
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