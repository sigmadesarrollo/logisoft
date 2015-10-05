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
	$xls .= '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>pcJose</Author>
  <LastAuthor>pcJose</LastAuthor>
  <Created>2009-11-10T15:47:10Z</Created>
  <LastSaved>2009-11-10T17:01:44Z</LastSaved>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font ss:Color="#000000" ss:Bold="1"/>
   <Interior/>
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
	
		$f1 = split("/",$_GET[fecha1]);
		$f2 = split("/",$_GET[fecha2]);
		$fecha1 = $f1[2]."-".$f1[1]."-".$f1[0];
		$fecha2 = $f2[2]."-".$f2[1]."-".$f2[0];
	
		$s = "CREATE TEMPORARY TABLE `movimientos_tmp` (                                                  
          `id` DOUBLE NOT NULL AUTO_INCREMENT,                                  
          `fecha` DATE DEFAULT NULL,  
          `sucursal` VARCHAR(50) COLLATE utf8_general_ci DEFAULT NULL,                                           
          `referenciacargo` VARCHAR(250) COLLATE utf8_general_ci DEFAULT NULL,  
          `referenciaabono` VARCHAR(20) COLLATE utf8_general_ci DEFAULT NULL,   
          `cargos` DOUBLE DEFAULT NULL,                                         
          `abonos` DOUBLE DEFAULT NULL,                                         
          `saldo` DOUBLE DEFAULT NULL,                                          
          `descripcion` VARCHAR(100) COLLATE utf8_general_ci DEFAULT NULL,      
          PRIMARY KEY  (`id`)                                                   
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
        mysql_query($s,$l) or die($s);
        
		//se insertan los movimientos anteriores
		$s = "INSERT INTO movimientos_tmp (saldo)
		SELECT IFNULL(SUM(cargo)-SUM(abono),0) AS saldo
		FROM reporte_cobranza4
		WHERE fecha < '$fecha1' 
		and idcliente = $_GET[idcliente] and prefijosucursal = '$_GET[prefijosucursal]'
		and reporte_cobranza4.estado <> 'DESACTIVADO'
		HAVING saldo>0;"; 
		$r = mysql_query($s,$l) or die($s);
		
		$s = "SELECT IFNULL(SUM(cargo)-SUM(abono),0) AS saldo
		FROM reporte_cobranza4
		WHERE fecha < '$fecha1' 
		and idcliente = $_GET[idcliente] and prefijosucursal = '$_GET[prefijosucursal]'
		and reporte_cobranza4.estado <> 'DESACTIVADO'
		HAVING saldo>0 ;";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$saldo = $f->saldo;
		
		//se insertan los nuevos
		$s = "SELECT reporte_cobranza4.*, cargo FROM reporte_cobranza4 
		WHERE fecha BETWEEN '$fecha1' AND '$fecha2'
		and prefijosucursal = '$_GET[prefijosucursal]'
		and reporte_cobranza4.estado <> 'DESACTIVADO'"; 
		$r = mysql_query($s,$l) or die($s);
		
		while($f=mysql_fetch_object($r)){
			$saldo = $saldo+$f->cargo;
			$saldo = $saldo-$f->abono;
			$s = "INSERT INTO movimientos_tmp
			SET fecha = '$f->fecha', sucursal = '$f->prefijosucursal', referenciacargo = '$f->folio', 
			referenciaabono = '$f->refabono', cargos = '$f->cargo', abonos = '$f->abono', saldo = '$saldo',
			descripcion = '$f->descripcion';";
			mysql_query($s,$l) or die($s);
		}
		
		/* fin del proceso */
		
		/*total de registros*/
		$s = "SELECT id
		FROM movimientos_tmp";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,
		sum(cargos) cargos, sum(abonos) abonos
		FROM movimientos_tmp";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,
		sucursal, referenciacargo, referenciaabono, cargos, abonos, saldo, descripcion
		FROM movimientos_tmp";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
	
	$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre FROM catalogocliente WHERE id=".$_GET[idcliente]."";
	$cl= mysql_query($s,$l) or die($s);
	$cc = mysql_fetch_object($cl);
		
  $xls .='<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="2" ss:Width="58.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="87.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="98.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="82.5" ss:Span="1"/>
   <Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="87.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="133.5"/>   
   <Row>
	<Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>
	<Cell ss:MergeAcross="3" ss:StyleID="s23" ><Data ss:Type="String">ESTADO DE CUENTA</Data></Cell>
   </Row>
   <Row>
	<Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>
	<Cell ss:MergeAcross="3" ss:StyleID="s23" ><Data ss:Type="String">'.$_GET[fecha1].' AL '.$_GET[fecha2].'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s21"><Data ss:Type="String">CLIENTE:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s23" ><Data ss:Type="String">'.$cc->nombre.'</Data></Cell>
   </Row>
   <Row ss:Index="5" ss:AutoFitHeight="0" ss:Height="13.5">
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">REF. CARGO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">REF. ABONO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">CARGOS</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">ABONOS</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SALDO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">DESCRIPCION</Data></Cell>
   </Row>';
   $noformula = 3;
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
   $xls .='<Row>
    <Cell ss:Index="4" ss:StyleID="s21"><Data ss:Type="String">SALDO CONTABLE:</Data></Cell>
    <Cell ss:Formula="=SUM(R[-1]C:R[-1]C[1])"><Data ss:Type="Number">0</Data></Cell>
   </Row>
  </Table>
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
     <ActiveRow>7</ActiveRow>
     <ActiveCol>4</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"ESTADO DE CUENTA.xls\"" ); 
	print $xls;
?>
