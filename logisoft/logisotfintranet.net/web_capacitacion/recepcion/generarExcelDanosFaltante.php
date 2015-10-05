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
	
	$str = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 
	$l = mysql_connect("localhost","pmm","guhAf2eh");
	
	if(ereg("web_pruebas/",$str)){
		mysql_select_db("pmm_dbpruebas", $l);
	
	}else if(ereg("web_capacitacion/",$str)){
		mysql_select_db("pmm_curso", $l);
	
	}else if(ereg("web/",$str)){
		mysql_select_db("pmm_dbweb", $l);
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
  <LastPrinted>2010-04-06T19:34:35Z</LastPrinted>
  <Created>2009-11-12T01:28:08Z</Created>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s30">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Size="14" ss:Bold="1"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">';
 	
	$s = "SELECT IF(rep.dano = 1,'DAÑO',IF(rep.faltante = 1,'FALTANTE',IF(rep.sobrante = 1,'SOBRANTE',''))) AS tipo,
		rep.guia, t.estado, t.destinatario, t.destino, t.origen,
		DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecharecepcion, rep.recepcion, rep.comentarios FROM reportedanosfaltante rep
		INNER JOIN (SELECT gv.id AS guia, gv.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasventanilla AS gv
		INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		UNION
		SELECT ge.id AS guia, ge.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasempresariales AS ge
		INNER JOIN catalogocliente des ON ge.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id) t ON rep.guia=t.guia
		INNER JOIN recepcionmercancia rm ON rep.recepcion = rm.folio
		WHERE ".(($_GET[sucursal]!="todas")? "rep.sucursal=".$_GET[sucursal]." AND" : "")." 
		rm.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' ";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		$filas = mysql_num_rows($r);
		
		if($_GET[sucursal]!="todas"){
			$s = "SELECT descripcion FROM catalogosucursal WHERE id=".$_GET[sucursal];
			$t = mysql_query($s,$l) or die($s);
			$su= mysql_fetch_object($t);
		}
  $xls .='
  <Table ss:ExpandedColumnCount="9" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="98.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="135.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="207"/>
   <Column ss:Width="73.5"/>
   <Column ss:Width="69"/>
   <Column ss:Width="74.25"/>
   <Column ss:Width="94.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="138"/>
   <Row ss:Height="18">
    <Cell ss:MergeAcross="3" ss:StyleID="s30"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row ss:Index="3">
    <Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s23"><Data ss:Type="String">'.cambio_texto('REPORTE DAÑOS Y FALTANTES').'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s23"><Data ss:Type="String">'.$_GET[fechaini].' AL '.$_GET[fechafin].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s23"><Data ss:Type="String">'.(($_GET[sucursal]=="todas")?"TODAS":cambio_texto($su->descripcion)).'</Data></Cell>
   </Row>
   <Row ss:Index="7" ss:AutoFitHeight="0" ss:Height="13.5">
    <Cell ss:StyleID="s24"><Data ss:Type="String">TIPO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">ESTADO GUIA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">DESTINATARIO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUC. DESTINO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUC. ORIGEN</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">F. RECEPCION</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">FOLIO RECEPCION</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">COMENTARIOS</Data></Cell>
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
  $xls .=' </Table>
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
     <ActiveRow>7</ActiveRow>
     <ActiveCol>3</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"REPORTEDANOSFALTANTES.xls\""); 
	print $xls;
?>