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
  <Created>2009-11-04T00:30:17Z</Created>
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
  <Style ss:ID="s49" ss:Name="Moneda">
	<NumberFormat
	ss:Format="_-&quot;$&quot;* #,##0.00_-;-\-&quot;$&quot;* #,##0.00_-;_-&quot;$&quot;* &quot;-&quot;??_-;_-@_-"/>
  </Style>
  <Style ss:ID="s62" ss:Parent="s49">
	<Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
  </Style>
  <Style ss:ID="s22">
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">';
 		require_once("../../ConectarSolo.php");
		$l = Conectarse("webpmm");
	
	$titulo1 .='<Row>
    <Cell ss:StyleID="s22"><Data ss:Type="String">TITULO:</Data></Cell>
	<Cell><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s22"><Data ss:Type="String">FECHA:</Data></Cell>
    <Cell ss:MergeAcross="2"><Data ss:Type="String">'.$_GET[fechainicio].' AL '.$_GET[fechafin].'</Data></Cell>
   </Row>
   <Row ss:Index="4">
    <Cell ss:StyleID="s22"><Data ss:Type="String">VENDEDOR:</Data></Cell>
    <Cell ss:MergeAcross="2" ><Data ss:Type="String">'.$_GET[vendedor].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s22"><Data ss:Type="String">MES:</Data></Cell>
    <Cell><Data ss:Type="String">'.$_GET[nombremes].'</Data></Cell>
   </Row>';	
	
 	if($_GET[accion] == 1){	
 		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,guia,idcliente,cliente,flete,comision,CONCAT('%',porcentaje) porcentaje FROM(
		SELECT gv.fecha,gv.clienteconvenio AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,gv.id AS guia,
		SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete,
		SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision,
		IFNULL(cc.comision,0) porcentaje
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente	
		LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.tipoflete=0 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO'
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY gv.id
		UNION
		SELECT gv.fecha,gv.clienteconvenio AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,gv.id AS guia,
		SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete,SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision,
		IFNULL(cc.comision,0) porcentaje
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente	
		LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO'
		AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY gv.id
		UNION
		SELECT f.fecha,f.cliente AS idcliente,CONCAT(gc.nombre,' ',gc.apaterno,' ',gc.amaterno) AS cliente,
		CONCAT('F-',IF(fd.tipoguia='PREPAGADA',fd.factura,fd.folio)) AS guia,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) flete,
		SUM((fd.flete-IFNULL(fd.cantidaddescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision,IFNULL(cc.comision,0) porcentaje
		FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.credito='NO' 
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO' AND f.tipoguia!='ventanilla'
		GROUP BY fd.folio
		UNION
		SELECT f.fecha,f.cliente AS idcliente,CONCAT(gc.nombre,' ',gc.apaterno,' ',gc.amaterno) AS cliente,
		CONCAT('F-',IF(fd.tipoguia='PREPAGADA',fd.factura,fd.folio)) AS guia,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) flete,
		SUM((fd.flete-IFNULL(fd.cantidaddescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision,IFNULL(cc.comision,0) porcentaje
		FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.credito='SI' 
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO'  AND f.estadocobranza='C'
		GROUP BY fd.folio )t ORDER BY fecha ";
		$r = mysql_query($s,$l)or die($s); 
 		$filas = mysql_num_rows($r);
		
	$xls .='<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+8).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="100"/>
   <Column ss:Width="54"/>
   <Column ss:Index="4" ss:AutoFitWidth="0" ss:Width="233.25"/>
   <Column ss:Width="102"/>
   <Column ss:AutoFitWidth="0" ss:Width="84"/>
  	'.$titulo1.'
   <Row ss:Index="7" ss:Height="13.5">
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String"># CLIENTE</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">VALOR FLETE NETO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">COMISION</Data></Cell>
	<Cell ss:StyleID="s24"><Data ss:Type="String">% C</Data></Cell>
   </Row>';
		$idcliente = 2;
 	}else if($_GET[accion] == 2){
		
		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,guia,prefijodestino,idcliente,cliente,flete,estado FROM(
		SELECT gv.fecha,gv.clienteconvenio AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
		gv.id AS guia,cs.prefijo AS prefijodestino,(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete,gv.estado
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND /* ISNULL(gv.factura) AND */
		gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO' GROUP BY gv.id
		UNION
		SELECT ge.fecha,ge.clienteconvenio AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
		ge.id AS guia,cs.prefijo AS prefijodestino,(ge.tflete-IFNULL(ge.ttotaldescuento,0)) AS flete,ge.estado
		FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id LEFT JOIN catalogocliente cc ON ge.clienteconvenio=cc.id
		WHERE ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO' GROUP BY ge.id
		UNION
		SELECT f.fecha,f.cliente AS idcliente,CONCAT(gc.nombre,' ',gc.apaterno,' ',gc.amaterno) AS cliente,
		CONCAT('F-',IF(fd.tipoguia='PREPAGADA',fd.factura,fd.folio)) AS guia,cs.prefijo AS prefijodestino,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) flete,ge.estado 
		FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura LEFT JOIN guiasempresariales ge ON fd.folio=ge.id 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND gc.vendedor='".$_GET[vendedor]."' 
		AND f.tipoguia!='ventanilla' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO' GROUP BY fd.folio)t ORDER BY fecha ";
		$r = mysql_query($s,$l) or die($s);
 		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+8).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="104.25" ss:Span="1"/>
   <Column ss:Index="4" ss:Width="54"/>
   <Column ss:AutoFitWidth="0" ss:Width="233.25"/>
   <Column ss:Width="102"/>
   <Column ss:AutoFitWidth="0" ss:Width="84"/>
   '.$titulo1.'
   <Row ss:Index="7" ss:AutoFitHeight="0" ss:Height="13.5">
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">DESTINO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String"># CLIENTE</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">VALOR FLETE NETO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">STATUS</Data></Cell>
   </Row>';
	   $idcliente = 3;
	}  
   
   if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==$idcliente){
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
		$arre[5] = "SI";
		$arre[6] = "SI";
		for($i=1;$i<=count($arre);$i++){
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
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>0</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>8</ActiveRow>
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