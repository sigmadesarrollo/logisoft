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
  <Company>PMM</Company>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Size="14" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s28">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders/>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s31">
   <NumberFormat ss:Format="Standard"/>
  </Style>
  <Style ss:ID="s33">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s49">
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s50">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Borders/>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s54">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="3"/>
   </Borders>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s55">
   <Alignment ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="3"/>
   </Borders>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s56">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="3"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">';
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
		
 		$s = "(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, gv.estado, ifnull(gv.recibio,'') recibio
		FROM reportes_ventas rv
		INNER JOIN guiasventanilla gv ON rv.folio = gv.id
		WHERE rv.idenvia = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
		rv.tipoventa = 'GUIA VENTANILLA')
		UNION
		(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, ge.estado, ifnull(ge.recibio,'') recibio
		FROM reportes_ventas rv
		INNER JOIN guiasempresariales ge ON rv.folio = ge.id
		WHERE rv.idenvia = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
		rv.tipoventa = 'GUIA VENTANILLA')";
		$r = mysql_query($s,$l) or die($s);
 		$filas = mysql_num_rows($r);
	
 		$s = "(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, gv.estado, ifnull(gv.recibio,'') recibio
		FROM reportes_ventas rv
		INNER JOIN guiasventanilla gv ON rv.folio = gv.id
		WHERE rv.idrecibe = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
		rv.tipoventa = 'GUIA VENTANILLA')
		UNION
		(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, ge.estado, ifnull(ge.recibio,'') recibio
		FROM reportes_ventas rv
		INNER JOIN guiasempresariales ge ON rv.folio = ge.id
		WHERE rv.idrecibe = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
		rv.tipoventa = 'GUIA VENTANILLA')";
		$t = mysql_query($s,$l) or die($s);
 		$filas2 = mysql_num_rows($t);
		$totalfilas = $filas + $filas2;
		
		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) as nombre FROM catalogocliente
		WHERE id=".$_GET[idcliente]."";
		$cl = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($cl);
		
  $xls .='<Table ss:ExpandedColumnCount="12" ss:ExpandedRowCount="'.($totalfilas+15).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="4" ss:AutoFitWidth="0" ss:Width="222.75"/>
   <Column ss:Index="11" ss:AutoFitWidth="0" ss:Width="123"/>
   <Column ss:Width="67.5"/>
   <Row>
    <Cell ss:StyleID="s49"><Data ss:Type="String">TITULO: REPORTE DE ENVIOS POR CLIENTE</Data></Cell>
   </Row>
   <Row ss:Index="4" ss:Height="18">
    <Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE: '.$_GET[idcliente].'</Data></Cell>
    <Cell ss:StyleID="s22"><Data ss:Type="String">'.$f->nombre.'</Data></Cell>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s22"/>
    <Cell ss:StyleID="s23"/>
    <Cell ss:StyleID="s23"/>
   </Row>
   <Row ss:Height="18">
    <Cell ss:StyleID="s24"><Data ss:Type="String">DEL:</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">'.$_GET[fechaini].' AL '.$_GET[fechafin].'</Data></Cell>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s21"/>
    <Cell ss:StyleID="s23"/>
    <Cell ss:StyleID="s23"/>
    <Cell ss:StyleID="s23"/>
    <Cell ss:StyleID="s23"/>
    <Cell ss:StyleID="s23"/>
    <Cell ss:StyleID="s23"/>
    <Cell ss:StyleID="s23"/>
   </Row>
   <Row ss:Index="7" ss:Height="13.5" ss:StyleID="s56">
    <Cell ss:StyleID="s54"><Data ss:Type="String">FECHA </Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">GUIA </Data></Cell>
    <Cell ss:StyleID="s55"><Data ss:Type="String">DESTINO </Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">CLIENTE ORIGEN/DESTINO</Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">FLETE</Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">ENVIO</Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">PAQUETES</Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">KILOGRAMOS</Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">TOTAL </Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">ESTADO </Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">QUIEN RECIBIO</Data></Cell>
    <Cell ss:StyleID="s54"><Data ss:Type="String">DIAS ENTREGA</Data></Cell>
   </Row>
   <Row ss:Height="13.5">
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s50"><Data ss:Type="String">Enviado </Data></Cell>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
   </Row>';
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
					$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}
		
		$xls .='
		 <Row>
		 <Cell ><Data ss:Type="String"></Data></Cell>
		 </Row>
		<Row>		 
	  	<Cell ><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.($filas+1).']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
			}
		}
	   $xls .='</Row>';
	}
   $xls .='<Row>
    <Cell ss:Index="9" ss:StyleID="s31"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s33"><Data ss:Type="String">Recibido</Data></Cell>
    <Cell ss:Index="9" ss:StyleID="s31"/>
   </Row>';
	if($filas2>0){	
		$arre = array();
		$arr = 0;	
		while($ff = mysql_fetch_array($t)){
			$arr = ($arr==0)? count($ff) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($ff)/2;$i++){
				if(is_numeric($ff[$i])==false){
					$xls .= '<Cell><Data ss:Type="String">'.cambio_texto($ff[$i]).'</Data></Cell>';
					$arre[$i+1] = "NO";
				}else if(is_numeric($ff[$i])){
					$xls .= '<Cell><Data ss:Type="Number">'.$ff[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}
		
		 $xls .='
		 <Row>
		 <Cell ><Data ss:Type="String"></Data></Cell>
		 </Row>
		 <Row>		 
	  	<Cell ><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.($filas2+1).']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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
     <ActiveRow>4</ActiveRow>
     <ActiveCol>1</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet> 
</Workbook>';
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"REPORTE_ENVIOS_CLIENTE.xls\"" ); 
	print $xls;

?>