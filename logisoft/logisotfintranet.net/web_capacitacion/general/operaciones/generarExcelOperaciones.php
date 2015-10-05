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
  <Created>2009-11-05T00:39:21Z</Created>
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
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
   <Style ss:ID="s27">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
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
		
   $cabezera = '<Row>
    <Cell ss:Index="2" ss:MergeAcross="1" ss:StyleID="s27"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>   
	<Cell ss:MergeAcross="2" ss:StyleID="s23"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">'.((!empty($_GET[fechainicio]))?"FECHA:":"").'</Data></Cell>
	<Cell ss:MergeAcross="2" ss:StyleID="s23"><Data ss:Type="String">'.((!empty($_GET[fechainicio]))? $_GET[fechainicio].' AL '.$_GET[fechafin]:"").'</Data></Cell>    
   </Row>';
   
   if($_GET[accion]==1){
		$s = "SELECT DATE_FORMAT(bs.fecha,'%d/%m/%Y') AS fecha,cr.descripcion AS ruta,0 AS gastoruta,IFNULL(lg.gastos,0) gastotranscurso,
		IFNULL(bs.gastos,0)-IFNULL(lg.gastos,0) utilidad
		FROM bitacorasalida bs INNER JOIN catalogoruta cr ON bs.ruta=cr.id
		INNER JOIN programacionrecepciondiaria prd ON bs.folio=prd.idbitacora
		LEFT JOIN liquidaciongastos lg ON bs.folio=lg.foliobitacora
		WHERE bs.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'	
		GROUP BY bs.folio";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
	   $xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+5).'" x:FullColumns="1"
	   x:FullRows="1" ss:DefaultColumnWidth="60">
	   <Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="87.75" ss:Span="2"/>
		'.$cabezera.'
	   <Row ss:Index="4">
		<Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">RUTA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">T. EMBARCADO</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">GASTO</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">UTILIDAD</Data></Cell>
	   </Row>';
   		$noformula=1;
		
   }else if($_GET[accion]==2){
   		$s = "SELECT IFNULL(COUNT(*),0) AS total FROM recepcionmercancia rm
		INNER JOIN reportedanosfaltante rd ON rm.folio = rd.recepcion
		WHERE rm.foliobitacora = ".$_GET[bitacora]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT b.unidad, CONCAT_WS(' ',ce1.nombre,ce1.apellidopaterno,ce1.apellidomaterno) AS operador1,
		IF(b.conductor2=0,'',CONCAT_WS(' ',ce2.nombre,ce2.apellidopaterno,ce2.apellidomaterno)) AS operador2,
		IF(b.conductor3=0,'',CONCAT_WS(' ',ce3.nombre,ce3.apellidopaterno,ce3.apellidomaterno)) AS operador3,
		CONCAT('#',$f->total) AS incidentes
		FROM bitacorasalida b
		INNER JOIN catalogoempleado ce1 ON b.conductor1 = ce1.id
		LEFT JOIN catalogoempleado ce2 ON b.conductor2 = ce2.id
		LEFT JOIN catalogoempleado ce3 ON b.conductor3 = ce3.id
		WHERE b.folio = $_GET[bitacora]";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
   		$xls .='<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+5).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:AutoFitWidth="0" ss:Width="88.5"/>
	    <Column ss:AutoFitWidth="0" ss:Width="254.25" ss:Span="2"/>
	    <Column ss:Index="5" ss:AutoFitWidth="0" ss:Width="87.75" ss:Span="2"/>
		'.$cabezera.'
		<Row ss:Index="4">
			<Cell ss:StyleID="s24"><Data ss:Type="String">UNIDAD</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">OPERADOR</Data></Cell>
		    <Cell ss:StyleID="s24"><Data ss:Type="String">OPERADOR 2</Data></Cell>
		    <Cell ss:StyleID="s24"><Data ss:Type="String">OPERADOR 3</Data></Cell>
			<Cell ss:StyleID="s24"><Data ss:Type="String">INCIDENTES</Data></Cell>			
	   </Row>';
	   $noformula = 0;
	   
   }else if($_GET[accion]==3){
   		$s = "SELECT DATE_FORMAT(rd.fecha,'%d/%m/%Y') AS fecha,cs.prefijo AS sucursal, 
		IF(rd.dano=1,'DAÑO',IF(rd.faltante=1,'FALTANTE',IF(rd.sobrante=1,'SOBRANTE',''))) AS tipoincidente,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS operador
		FROM recepcionmercancia rm
		INNER JOIN reportedanosfaltante rd ON rm.folio = rd.recepcion
		INNER JOIN catalogosucursal cs ON rd.sucursal = cs.id
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		WHERE rm.foliobitacora = ".$_GET[bitacora]."";
   		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+5).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Width="68.25"/>
		<Column ss:AutoFitWidth="0" ss:Width="87.75"/>
		<Column ss:AutoFitWidth="0" ss:Width="171"/>
		<Column ss:AutoFitWidth="0" ss:Width="225.75"/>
		<Column ss:AutoFitWidth="0" ss:Width="87.75"/>
		'.$cabezera.'
		<Row ss:Index="4">
		<Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">TIPO INCIDENTE</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">OPERADOR</Data></Cell>
	    </Row>';  
		
   }else if($_GET[accion]==4){
		//se crea una tabla temporal
		$s = "CREATE TEMPORARY TABLE `tmp_operaciones` (                                                  
		`id` DOUBLE NOT NULL AUTO_INCREMENT,             
		`ruta` VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,                     
		`guia` VARCHAR(15) COLLATE utf8_unicode_ci DEFAULT NULL,                                           
		`orden` DOUBLE DEFAULT NULL,
		`sucursal` DOUBLE DEFAULT NULL,                                         
		`importe` DOUBLE DEFAULT NULL,                                         
		`tipo` DOUBLE DEFAULT NULL,                                          
		PRIMARY KEY  (`id`),
		KEY  `guia` (`guia`)                                                   
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//consulta para sacar las guias y insertarlas
		$s = "INSERT INTO tmp_operaciones
		SELECT NULL,cr.descripcion AS ruta,emd.guia,crd.id AS orden,emd.sucursal,0 AS importe,1 AS tipo
		FROM bitacorasalida bs INNER JOIN catalogoruta cr ON bs.ruta=cr.id
		INNER JOIN catalogorutadetalle crd ON bs.ruta=crd.ruta
		LEFT JOIN embarquedemercancia em ON bs.folio=em.foliobitacora AND crd.sucursal=em.idsucursal
		LEFT JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque AND crd.sucursal=emd.sucursal
		WHERE bs.folio=".$_GET[bitacora]." AND NOT ISNULL(emd.guia) GROUP BY emd.guia
		UNION
		SELECT NULL,cr.descripcion AS ruta,rmd.guia,crd.id AS orden,rmd.sucursal,0 AS importe,2 AS tipo
		FROM bitacorasalida bs INNER JOIN catalogoruta cr ON bs.ruta=cr.id
		INNER JOIN catalogorutadetalle crd ON bs.ruta=crd.ruta
		LEFT JOIN recepcionmercancia rm ON bs.folio=rm.foliobitacora AND crd.sucursal=rm.idsucursal
		LEFT JOIN recepcionmercanciadetalle rmd ON rm.folio=rmd.recepcion AND crd.sucursal=rmd.sucursal
		WHERE bs.folio=".$_GET[bitacora]." AND NOT ISNULL(rmd.guia) GROUP BY rmd.guia;";
		mysql_query($s,$l) or die($s);
		//agregar el importe
		$s = "UPDATE tmp_operaciones temp INNER JOIN guiasventanilla gv ON temp.guia=gv.id
		SET importe=gv.total;";
		mysql_query($s,$l) or die($s);
		$s = "UPDATE tmp_operaciones temp INNER JOIN guiasempresariales ge ON temp.guia=ge.id
		SET importe=ge.total;";
		mysql_query($s,$l) or die($s);
		//fin datos tmp_operaciones
		
		$s = "SELECT t.ruta,cs.prefijo AS descripcionsucursal,CONCAT('#%',SUM(IF(tipo=1,1,0))) AS guiasembarcadas,
		CONCAT('#%',SUM(IF(tipo=2,1,0))) AS guiasrecibidas, SUM(t.importe) AS importeembarcadas
		FROM tmp_operaciones t INNER JOIN catalogosucursal cs ON t.sucursal=cs.id
		GROUP BY t.sucursal ORDER BY t.orden ";
		
		$r = mysql_query($s,$l)or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:AutoFitWidth="0" ss:Width="72" ss:Span="1"/>
		<Column ss:Index="3" ss:AutoFitWidth="0" ss:Width="87.75" ss:Span="1"/>
		<Column ss:Index="5" ss:Width="98.25"/>
		'.$cabezera.'
		<Row ss:Index="4">
		<Cell ss:Index="3" ss:MergeAcross="1" ss:StyleID="s24"><Data ss:Type="String">GUIAS</Data></Cell>		
		</Row>
		<Row>
		<Cell ss:StyleID="s24"><Data ss:Type="String">RUTA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">EMBARCADAS</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">RECIBIDAS</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">IMP. EMBARCADAS</Data></Cell>
		</Row>';
   }
   if($filas>0){	
		$arre = array();
		$arr = 0;	
		while($f = mysql_fetch_array($r)){
			$arr = ($arr==0)? count($f) : $arr;
			$xls .= '<Row>';			
			for($i=0;$i<count($f)/2;$i++){
				if(is_numeric($f[$i])==false || $i==$noformula){
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
		if(in_array("SI",$arre)){
			$xls .='<Row>
	  		<Cell ss:StyleID="s21"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
			for($i=1;$i<$arr;$i++){
				if($arre[$i] == "SI"){
					$xls .= '<Cell ss:StyleID="s62" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
				}elseif($arre[$i] == "X"){
					$xls .= '<Cell ss:StyleID="s25" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
				}
			}
	    $xls .='</Row>';
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
     <ActiveRow>2</ActiveRow>
     <ActiveCol>2</ActiveCol>
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