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
	$xls ='<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>pcJose</Author>
  <LastAuthor>pcJose</LastAuthor>
  <Created>2009-11-05T23:06:14Z</Created>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
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
		
	$cabezera = '<Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
	<Cell ss:MergeAcross="2" ss:StyleID="s25"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">'.((!empty($_GET[fechainicio]))?"FECHA:":"").'</Data></Cell>
	<Cell ss:MergeAcross="2" ss:StyleID="s25"><Data ss:Type="String">'.((!empty($_GET[fechainicio]))? $_GET[fechainicio].' AL '.$_GET[fechafin] :"")   .'</Data></Cell>    
   </Row>';
   //Tiempo.Folio,bs.folio AS foliobitacora,
 	if($_GET[accion]==1){
		$s = "CREATE TEMPORARY TABLE `logistica_tmp` (                                    
                 `id` DOUBLE NOT NULL AUTO_INCREMENT,                            
                 `fecha` DATE DEFAULT NULL,                                      
                 `unidad` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,      
				 `ruta` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,
                 `operador1` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,  
                 `operador2` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,  
                 `operador3` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,  
                 `guias` DOUBLE DEFAULT NULL,                                    
                 `recorrido` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,   
                 `estado` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,     
                 `incidencias` DOUBLE DEFAULT NULL,
				 `horasalida` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,               
                 `tiemporecorrido` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,                              
                 PRIMARY KEY  (`id`)                                             
               ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO logistica_tmp (recorrido, fecha, unidad, ruta, operador1,operador2,operador3,guias,estado,incidencias, horasalida, tiemporecorrido)
		SELECT TIMEDIFF(t1.tiemporecorrido,MAX(t1.horasalida)) AS recorrido, t1.fecha, t1.unidad, t1.ruta, t1.operador1, 
		t1.operador2, t1.operador3, t1.guias, t1.estado, t1.reporteincidencias, t1.horasalida, t1.tiemporecorrido
		FROM(SELECT horasalida, fecha, unidad, ruta, operador1, IFNULL(operador2,'') AS operador2, IFNULL(operador3,'') AS operador3, 
		IFNULL(guias,'') AS guias, tiemporecorrido, estado, IFNULL(reporteincidencias,0) AS reporteincidencias FROM reporte_logistica1
		WHERE CAST(fecha AS DATE) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		ORDER BY fecha DESC)t1 GROUP BY unidad";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT date_format(fecha,'%d/%m/%Y') as fecha, ruta, unidad, operador1, operador2, 
		operador3, guias,recorrido,estado,incidencias FROM logistica_tmp";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		$xls .='<Table ss:ExpandedColumnCount="10" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		   x:FullRows="1" ss:DefaultColumnWidth="60">
		   <Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="68.25"/>
		   <Column ss:AutoFitWidth="0" ss:Width="187.5" ss:Span="2"/>
		   <Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="98.25"/>
		   <Column ss:Width="74.25"/>
		   <Column ss:AutoFitWidth="0" ss:Width="87.75"/>
		   <Column ss:Width="67.5"/>
		'.$cabezera.'
		<Row ss:Index="4" ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">RUTA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">UNIDAD</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">OPERADOR 1</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">OPERADOR 2</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">OPERADOR 3</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">GUIAS</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">T. RECORRIDO</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">ESTADO</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">INCIDENCIAS</Data></Cell>
	   </Row>';
		$noformula=1; $otro = 10;
		
    }else if($_GET[accion]==2){	
		$s = "SELECT t2.descripcion_ruta AS ruta, TIMEDIFF(MAX(t1.fecha),MIN(t1.fecha)) AS trecorrido,
		CONCAT_WS(' / ',tiempocarga, tiempodescarga) AS tiempocd, recorrido FROM reporte_logistica2 t2
		INNER JOIN reporte_logistica1 t1 ON t2.bitacora = t1.bitacora 
		WHERE t2.bitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:AutoFitWidth="0" ss:Width="87.75"/>
		<Column ss:Width="75"/>
		<Column ss:AutoFitWidth="0" ss:Width="191.25"/>
		<Column ss:Width="63"/>
		'.$cabezera.'
		<Row>
			<Cell ss:Index="3" ss:StyleID="s23"><Data ss:Type="String">DESVIACIONES TIEMPO</Data></Cell>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s24"><Data ss:Type="String">RUTA</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">T. RECORRIDO</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">CARGA/DESCARGA</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">RECORRIDO</Data></Cell>
		</Row>';
		$noformula=0;
		
	}else if($_GET[accion]==3){
		$s = "SELECT b.unidad, CONCAT_WS('-',r.precinto,cs.prefijo) AS precintoasignado,
		cu.cvolumen, cu.ckilos FROM bitacorasalida b
		INNER JOIN catalogounidad cu ON b.unidad = cu.numeroeconomico
		LEFT JOIN recepcionregistroprecintosdetalle r ON b.folio = r.foliobitacora
		LEFT JOIN catalogosucursal cs ON r.sucursal = cs.id
		WHERE b.folio = ".$_GET[bitacora]."";
		$r = mysql_query($s, $l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:AutoFitWidth="0" ss:Width="87.75"/>
		<Column ss:Width="75"/>
		<Column ss:Width="93"/>
		<Column ss:Width="91.5"/>
		'.$cabezera.'
		<Row>
			<Cell ss:Index="2" ss:StyleID="s23"><Data ss:Type="String">PRECINTOS</Data></Cell>
			<Cell ss:StyleID="s23"><Data ss:Type="String">CAPACIDAD PESO</Data></Cell>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s24"><Data ss:Type="String">No. ECONOMICO</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">ASIGNADOS</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">VOLUMETRICO</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">CAPACIDAD REAL</Data></Cell>
		</Row>';
		$noformula=0;
		
	}else if($_GET[accion]==4){
		$s = "SELECT nombre, IFNULL(SUM(diastrabajados),0) AS diastrabajados, COUNT(id) AS viajes,
		SUM(kmrecorrido) AS kmrecorrido FROM reporte_logistica4
		WHERE CAST(fechasalida AS DATE) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."' AND idempleado = ".$_GET[operador]." 
		HAVING nombre IS NOT NULL";
		$r = mysql_query($s, $l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:AutoFitWidth="0" ss:Width="164.25"/>
		<Column ss:Width="98.25"/>
		<Column ss:AutoFitWidth="0" ss:Width="56.25"/>
		<Column ss:Width="91.5"/>
		'.$cabezera.'
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s24"><Data ss:Type="String">NOMBRE</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">DIAS TRABAJADOS</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">VIAJES</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">KM RECORRIDOS</Data></Cell>
		</Row>';
		$noformula=0;
		
	}else if($_GET[accion]==5){		
		$s = "SELECT DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fecha, guia,
		destino, destinatario, paquetes AS nopaquetes FROM reporte_logistica3
		WHERE idtabla1 = ".$_GET[idtabla]."";
		$r = mysql_query($s, $l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:AutoFitWidth="0" ss:Width="69"/>
		<Column ss:Width="98.25"/>
		<Column ss:AutoFitWidth="0" ss:Width="87.75"/>
		<Column ss:Width="91.5"/>
		<Column ss:Width="76.5"/>
		'.$cabezera.'
		<Row>
			<Cell><Data ss:Type="String"></Data></Cell>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">GUIA</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">DESTINO</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">DESTINATARIO</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">No. PAQUETES</Data></Cell>
		</Row>';
		
	}else if($_GET[accion]==6){
		$s = "SELECT DATE_FORMAT(rd.fecha,'%d/%m/%Y') AS fecha,
		IF(rd.dano=1,'DAÑO',IF(rd.faltante=1,'FALTANTE','')) AS incidencia
		FROM recepcionmercancia rm
		INNER JOIN reportedanosfaltante rd ON rm.folio = rd.recepcion
		WHERE rm.foliobitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s, $l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:AutoFitWidth="0" ss:Width="69"/>
		<Column ss:AutoFitWidth="0" ss:Width="115.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="87.75"/>
		<Column ss:Width="91.5"/>
		<Column ss:Width="76.5"/>
		'.$cabezera.'
		<Row>
			<Cell ss:Index="2" ss:StyleID="s23"/>
			<Cell ss:StyleID="s23"/>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">TIPO INCIDENTE</Data></Cell>
		</Row>';
		
	}else if($_GET[accion]==7){		
		
		$r = mysql_query($s, $l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:AutoFitWidth="0" ss:Width="69"/>
		<Column ss:AutoFitWidth="0" ss:Width="83.25"/>
		<Column ss:AutoFitWidth="0" ss:Width="105.75"/>		
		'.$cabezera.'
		<Row>
			<Cell ss:Index="2" ss:StyleID="s23"/>
			<Cell ss:StyleID="s23"/>
		</Row>
		<Row ss:Height="13.5">
			<Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">UNIDAD</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">ESTADO</Data></Cell>
		</Row>';
		
	}else if($_GET[accion]==8){
		//
	}
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
					$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
					$arre[$i+1] = "SI";
				}				
			}
			$xls .= '</Row>';
		}
		
		 $xls .='<Row>
	  	<Cell ss:StyleID="s21"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s21" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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
     <ActiveRow>6</ActiveRow>
     <ActiveCol>1</ActiveCol>
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