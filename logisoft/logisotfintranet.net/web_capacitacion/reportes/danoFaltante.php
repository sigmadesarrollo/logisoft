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
  <LastPrinted>2010-05-03T15:54:47Z</LastPrinted>
  <Created>2010-05-03T15:48:59Z</Created>
  <LastSaved>2010-05-03T15:57:53Z</LastSaved>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>7935</WindowHeight>
  <WindowWidth>15195</WindowWidth>
  <WindowTopX>0</WindowTopX>
  <WindowTopY>105</WindowTopY>
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
  </Style>
  <Style ss:ID="s23">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Size="14" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s28">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
 </Styles>';
		$s = "SELECT DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecha, rep.recepcion, su.prefijo AS sucursal,
		rep.guia, t.origen, t.destino, IF(rep.dano = 1,'DAÑO',IF(rep.faltante = 1,'FALTANTE',
		IF(rep.dano = 1 AND rep.faltante = 1,'DAÑO,FALT',''))) AS tipo,
		CONCAT_WS(' ',e.nombre,e.apellidopaterno,e.apellidomaterno) AS recibio,
		r.unidad, cr.descripcion AS ruta
		FROM reportedanosfaltante rep
		INNER JOIN recepcionmercancia r ON rep.recepcion = r.folio AND rep.sucursal = r.idsucursal
		INNER JOIN catalogoempleado e ON rep.empleado1 = e.id
		INNER JOIN catalogoruta cr ON r.ruta = cr.id
		INNER JOIN (SELECT gv.id AS guia, sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasventanilla AS gv
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		UNION
		SELECT ge.id AS guia, sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasempresariales AS ge
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id) t ON rep.guia=t.guia
		INNER JOIN catalogosucursal su ON r.idsucursal = su.id
		WHERE r.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_GET[sucursal]!="todas")? " AND r.sucursal=".$_GET[sucursal]."" : "")."
		GROUP BY rep.guia";
		$r = mysql_query($s,$l) or die ($s);
		$filas = mysql_num_rows($r);
		
 $xls .='<Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="10" ss:ExpandedRowCount="'.($filas+8).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="2" ss:Width="73.5"/>
   <Column ss:Index="4" ss:AutoFitWidth="0" ss:Width="84.75"/>
   <Column ss:Index="7" ss:Width="90.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="180.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="73.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="216"/>
   <Row ss:Height="18">
    <Cell ss:Index="2" ss:MergeAcross="7" ss:StyleID="s26"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row ss:Index="3">
    <Cell ss:StyleID="s23"><Data ss:Type="String">FECHA:</Data></Cell>
    <Cell><Data ss:Type="String">'.date('d/m/Y').'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s23"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell><Data ss:Type="String">'.cambio_texto('REPORTE HISTORICO DAÑOS Y FALTANTES').'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s23"><Data ss:Type="String">PERIODO:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s28"><Data ss:Type="String">DEL: '.$_GET[fechainicio].' AL '.$_GET[fechafin].'</Data></Cell>
   </Row>
   <Row ss:Index="7">
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">F. RECEPCION</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">ORIGEN</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">DESTINO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">INCIDENTE</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">RECIBIO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">UNIDAD</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">RUTA</Data></Cell>
   </Row>';
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
					$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}		
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
     <ActiveRow>3</ActiveRow>
     <ActiveCol>1</ActiveCol>
     <RangeSelection>R4C2:R4C4</RangeSelection>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"reportedanofaltante.xls\"" ); 
	print $xls;
?>