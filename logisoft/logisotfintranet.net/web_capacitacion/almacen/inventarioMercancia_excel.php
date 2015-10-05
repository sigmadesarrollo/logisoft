<?
	
//	die("Estamos mejorando el proceso de trabajo de esta pagina, disculpe las molestias");
	
	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
	
	function cambiaf_a_mysql($fecha){//Convierte fecha de normal a mysql 
    	ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
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
  <Created>2010-07-22T17:29:48Z</Created>
  <Company>PMM</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>9975</WindowHeight>
  <WindowWidth>21195</WindowWidth>
  <WindowTopX>120</WindowTopX>
  <WindowTopY>75</WindowTopY>
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
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s24">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s49" ss:Name="Moneda">
	<NumberFormat
	ss:Format="_-&quot;$&quot;* #,##0.00_-;-\-&quot;$&quot;* #,##0.00_-;_-&quot;$&quot;* &quot;-&quot;??_-;_-@_-"/>
  </Style>
  <Style ss:ID="s62" ss:Parent="s49">
	<Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
  </Style>
 </Styles>';
 
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
	
	}else if(ereg("web_capacitacionPruebas/",$str)){
		mysql_select_db("pmm_cursoPruebas", $l);
		
	}else if(ereg("dbserver",$str)){
		mysql_select_db("webpmm", $l);
	}

 	$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal];
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
 	
		//POR RECIBIR
		if($_GET[estado]=="EN REPARTO EAD"){
			$condicionV=" AND g.estado='EN REPARTO EAD' and g.id not like '%Z'";
			$condicionE=" AND ge.estado='EN REPARTO EAD' and ge.id not like '%Z'";
		}else if($_GET[estado]=="EAD"){
			$condicionV=" AND (g.estado='ALMACEN DESTINO' OR g.estado='AUTORIZACION PARA SUSTITUIR' OR g.estado='POR RECIBIR') AND g.ocurre=0 and g.id not like '%Z'";
			$condicionE=" AND (ge.estado='ALMACEN DESTINO' OR ge.estado='AUTORIZACION PARA SUSTITUIR' OR ge.estado='POR RECIBIR') AND ge.ocurre=0 and ge.id not like '%Z'";
		}else if($_GET[estado]=="OCURRE"){
			$condicionV=" AND (g.estado='ALMACEN DESTINO' OR g.estado='AUTORIZACION PARA SUSTITUIR' OR g.estado='POR RECIBIR') AND g.ocurre=1 and g.id not like '%Z'";
			$condicionE=" AND (ge.estado='ALMACEN DESTINO' OR ge.estado='AUTORIZACION PARA SUSTITUIR' OR ge.estado='POR RECIBIR') AND ge.ocurre=1 and ge.id not like '%Z'";
		}else if($_GET[estado] == "ALMACEN TRASBORDO"){		
			$condicionV = " AND g.estado = 'ALMACEN TRASBORDO' and g.id not like '%Z'";
			$condicionE = " AND ge.estado = 'ALMACEN TRASBORDO' and ge.id not like '%Z'";
		}else if($_GET[estado] == "ALMACEN ORIGEN"){		
			$condicionV = " AND g.estado = 'ALMACEN ORIGEN' and g.id not like '%Z'";
			$condicionE = " AND ge.estado = 'ALMACEN ORIGEN' and ge.id not like '%Z'";
		}else if($_GET[estado] == "EN TRANSITO"){		
			$condicionV = " AND g.estado = 'EN TRANSITO' and g.id not like '%Z'";
			$condicionE = " AND ge.estado = 'EN TRANSITO' and ge.id not like '%Z'";
		}else if($_GET[estado] == "POR ENTREGAR"){		
			$condicionV = " AND g.estado = 'POR ENTREGAR' and g.id not like '%Z'";
			$condicionE = " AND ge.estado = 'POR ENTREGAR' and ge.id not like '%Z'";
		}else if($_GET[estado] == "POR RECIBIR"){		
			$condicionV = " AND g.estado = 'POR RECIBIR' and g.id not like '%Z'";
			$condicionE = " AND ge.estado = 'POR RECIBIR' and ge.id not like '%Z'";
		}else if($_GET[estado]=="TODOS"){
			$condicionV=" AND (g.estado<>'CANCELADA' AND g.estado <>'CANCELADO' AND IF(g.fechaentrega > '".cambiaf_a_mysql($_GET[fecha])."',(g.estado = 'ENTREGADA' AND g.estado = 'ENTREGADO'), g.estado <>'ENTREGADA' 
AND g.estado <> 'ENTREGADO') and g.id not like '%Z')";
			$condicionE=" AND (ge.estado<>'CANCELADA' AND ge.estado <>'CANCELADO' and IF(ge.fechaentrega > '".cambiaf_a_mysql($_GET[fecha])."',(ge.estado = 'ENTREGADA' AND ge.estado = 'ENTREGADO'),ge.estado <>'ENTREGADA' 
AND ge.estado <> 'ENTREGADO') and ge.id not like '%Z')";
		}
		
		$fechaV = " AND g.fecha <= '".cambiaf_a_mysql($_GET[fecha])."' ";
		$fechaE = " AND ge.fecha <= '".cambiaf_a_mysql($_GET[fecha])."' ";
 
 		$s = "select fecha,guia,flete,pago,cliente,descripcion,contenido,importe,almacen from (
			SELECT g.idsucursaldestino, cs.prefijo AS sucursal, g.id AS guia, 
			g.iddestinatario AS nocliente, CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
			DATE_FORMAT(g.fecha,'%d/%m/%Y') fecha, g.estado,g.ocurre, gd.descripcion, gd.contenido, 
			IF(g.tipoflete='1' AND g.condicionpago=0,g.total,0) AS importe,
			IF(g.estado='ALMACEN DESTINO' AND g.ocurre=1,'OCURRE',IF(g.estado='ALMACEN DESTINO' AND g.ocurre=0,'EAD',g.estado)) AS almacen, IF(g.tipoflete=0,'PAGADA','POR COBRAR') AS flete, IF(g.condicionpago=0,'CONTADO','CREDITO') AS pago
			FROM guiasventanilla g 
			LEFT JOIN catalogosucursal cs ON g.idsucursaldestino = cs.id 
			LEFT JOIN catalogocliente cc ON g.iddestinatario = cc.id 
			LEFT JOIN guiaventanilla_detalle gd ON g.id = gd.idguia
			WHERE g.id NOT LIKE '888%' AND ".(($_GET[sucursal]!=1)? "g.idsucursaldestino='$_GET[sucursal]' ": "")." $condicionV $fechaV
			GROUP BY g.id
			UNION 
			SELECT g.idsucursaldestino, cs.prefijo AS sucursal, g.id AS guia, 
			g.iddestinatario AS nocliente, CONCAT(cc.nombre,' ',cc.apellidopaterno,' ',cc.apellidomaterno) AS cliente,
			DATE_FORMAT(g.fecha,'%d/%m/%Y') fecha, g.estado,g.ocurre, gd.descripcion, gd.contenido, 
			IF(g.tipoflete='1' AND g.condicionpago=0,g.total,0) AS importe,
			IF(g.estado='ALMACEN DESTINO' AND g.ocurre=1,'OCURRE',IF(g.estado='ALMACEN DESTINO' AND g.ocurre=0,'EAD',g.estado)) AS almacen, IF(g.tipoflete=0,'PAGADA','POR COBRAR') AS flete, IF(g.condicionpago=0,'CONTADO','CREDITO') AS pago
			FROM guiasventanilla g 
			LEFT JOIN catalogosucursal cs ON g.idsucursaldestino = cs.id 
			LEFT JOIN catalogoempleado cc ON g.iddestinatario = cc.id 
			LEFT JOIN guiaventanilla_detalle gd ON g.id = gd.idguia
			WHERE g.id LIKE '888%' AND ".(($_GET[sucursal]!=1)? "g.idsucursaldestino='$_GET[sucursal]' ": "")." $condicionV $fechaV
			GROUP BY g.id
			UNION
			SELECT ge.idsucursaldestino, cs.prefijo AS sucursal, ge.id AS guia, 
			ge.iddestinatario AS nocliente, CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
			DATE_FORMAT(ge.fecha,'%d/%m/%Y') fecha, ge.estado,ge.ocurre, gd.descripcion, gd.contenido, 
			IF(ge.tipoflete='POR COBRAR' AND ge.tipopago=0,ge.total,0) AS importe,
			IF(ge.estado='ALMACEN DESTINO' AND ge.ocurre=1,'OCURRE',IF(ge.estado='ALMACEN DESTINO' AND ge.ocurre=0,'EAD',ge.estado)) AS almacen, ge.tipoflete AS flete, ge.tipopago AS pago
			FROM guiasempresariales ge
			LEFT JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id 
			LEFT JOIN catalogocliente cc ON ge.iddestinatario = cc.id 
			LEFT JOIN guiasempresariales_detalle gd ON ge.id = gd.id
			WHERE ".(($_GET[sucursal]!=1)? "ge.idsucursaldestino='$_GET[sucursal]' ": "")." $condicionE $fechaE
			GROUP BY ge.id
		) t";	
		$r = mysql_query($s,$l) or die ($s);
		$filas = mysql_num_rows($r);
 
 $xls .= '<Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="11" ss:ExpandedRowCount="'.($filas+9).'" x:FullColumns="1" x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:AutoFitWidth="0" ss:Width="69.75"/>
   <Column ss:Width="86.25"/>
   <Column ss:Index="4" ss:Width="67.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="236.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="108.75" ss:Span="1"/>
   <Column ss:Index="8" ss:AutoFitWidth="0" ss:Width="103.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="108.75"/>
   <Row>
    <Cell ss:Index="2" ss:MergeAcross="4" ss:StyleID="s26"><Data ss:Type="String">ENTREGAS PUNTUALES S DE RL DE CV</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s23"><Data ss:Type="String">INVENTARIO DE MERCANCIA</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s23"><Data ss:Type="String">'.utf8_encode($f->descripcion).'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">A LA FECHA:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s23"><Data ss:Type="String">'.$_GET[fecha].'</Data></Cell>
   </Row>
   <Row ss:Index="6">
    <Cell ss:StyleID="s27"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String">FLETE</Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String">COND. PAGO</Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String">CLIENTE</Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String">DESCRIPCION</Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String">CONTENIDO</Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String">IMPORTE</Data></Cell>
    <Cell ss:StyleID="s27"><Data ss:Type="String">ALMACEN</Data></Cell>
   </Row>';
   
   
   if($filas>0){
		$arre = array();
		$arr = 0;
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false){
					$xls .= '<Cell><Data ss:Type="String">'.utf8_encode($f[$i]).'</Data></Cell>';
					$arre[$i+1] = "NO";
				}else if(is_numeric($f[$i])){
					$xls .= '<Cell ss:StyleID="s62"><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}
			}
			$xls .= '</Row>';
		}
		 $xls .='<Row>

	  	<Cell ss:StyleID="s24"><Data ss:Type="String">TOTAL:</Data></Cell>';
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
				$xls .= '<Cell ss:StyleID="s62" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
			}
		}
	   $xls .='</Row>';
	}
   
  $xls .= '</Table>
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
     <ActiveCol>7</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
	
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"inventarioMercancia.xls\"" ); 
	print $xls;

?>