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
	function obtenerFecha($fecha){
		$row = split("/",$fecha);		
		switch($row[1]){
			case "1":
				$mes = "ENERO";
			break;
			case "01":
				$mes = "ENERO";
			break;
			case "2":
				$mes = "FEBRERO";
			break;
			case "02":
				$mes = "FEBRERO";
			break;
			case "3":
				$mes = "MARZO";
			break;
			case "03":
				$mes = "MARZO";
			break;
			case "4" :
				$mes = "ABRIL";
			break;
			case "04":
				$mes = "ABRIL";
			break;
			case "5" :
				$mes = "MAYO";
			break;
			case "05":
				$mes = "MAYO";
			break;
			case "6":
				$mes = "JUNIO";
			break;
			case "06":
				$mes = "JUNIO";
			break;
			case "7":
				$mes = "JULIO";
			break;
			case "07":
				$mes = "JULIO";
			break;
			case "8":
				$mes = "AGOSTO";
			break;
			case "08":
				$mes = "AGOSTO";
			break;
			case "9":
				$mes = "SEPTIEMBRE";
			break;
			case "09":
				$mes = "SEPTIEMBRE";
			break;
			case "10":
				$mes = "OCTUBRE";
			break;			
			case "11":
				$mes = "NOVIEMBRE";
			break;
			case "12":
				$mes = "DICIEMBRE";
			break;
		}		
		
		$day = weekday($fecha);
		
		switch($day){
			case "0":
				$dia = "LUNES ";
			break;
			case "1":
				$dia = "MARTES ";
			break;
			case "2":
				$dia = "MIERCOLES ";
			break;
			case "3":
				$dia = "JUEVES ";
			break;
			case "4":
				$dia = "VIERNES ";
			break;
			case "5":
				$dia = "SABADO ";
			break;
			case "6":
				$dia = "DOMINGO ";
			break;
		}
		
		return "DEL DIA ".$dia.$row[0]." DE ".$mes." DEL ".$row[2];
	}
		
	function weekday($fecha){
	   $fecha=str_replace("/","-",$fecha);
	   list($dia,$mes,$anio)=explode("-",$fecha);
	   return (((mktime ( 0, 0, 0, $mes, $dia, $anio) - mktime ( 0, 0, 0, 7, 17, 2006))/(60*60*24))+700000) % 7;
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
  <LastPrinted>2009-11-24T17:48:31Z</LastPrinted>
  <Created>2009-11-24T17:42:43Z</Created>
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
  <Style ss:ID="s24">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s26">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s28">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s29">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s31">
   <Alignment ss:Vertical="Bottom"/>
  </Style>
 </Styles>'; 	

	//$l = mysql_connect("97.74.31.27","dbwebpmm","Sistemapmm09");	
	//mysql_select_db("dbwebpmm", $l);
	
	//$l = mysql_connect("DBSERVER","root","root");
	$l = mysql_connect("97.74.31.27","dbwebpmm","Sistemapmm09");	
	mysql_select_db("dbwebpmm", $l);
	
	$s = "SELECT estado,cliente,direccion,CONCAT('FA-',factura) as factura,fechaguia,fechavencimiento,importe FROM (
		SELECT IF(rd.estado='No Revisadas','COBRANZA','REVISION')AS estado,
		CONCAT(f.nombrecliente,' ',f.apellidopaternocliente,' ',f.apellidomaternocliente) AS cliente,
		CONCAT(f.calle,' ',f.numero,' ',f.colonia,' ',f.poblacion)AS direccion,
		rd.factura, DATE_FORMAT(rd.fechaguia,'%d/%m/%Y') AS fechaguia, 
		DATE_FORMAT(rd.fechavencimiento,'%d/%m/%Y')AS fechavencimiento, rd.importe 
		FROM relacioncobranza r
		INNER JOIN relacioncobranzadetalle rd ON r.folio = rd.relacioncobranza
		INNER JOIN facturacion f ON rd.factura=f.folio 
		WHERE r.folio = ".$_GET[folio]."
		GROUP BY rd.factura)tabla order by estado,factura";
	$r = mysql_query($s,$l) or die($s);
	$filas = mysql_num_rows($r);
	
	
 $xls .='<Worksheet ss:Name="Hoja1">
   <Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+11).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:AutoFitWidth="0" ss:Width="68.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="182.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="211.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="77.25" ss:Span="1"/>
   <Column ss:Index="8" ss:AutoFitWidth="0" ss:Width="150"/>
   <Row>
    <Cell ss:MergeAcross="5" ss:StyleID="s24"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row ss:Index="3">
    <Cell ss:MergeAcross="2" ss:StyleID="s27"><ss:Data ss:Type="String"
      xmlns="http://www.w3.org/TR/REC-html40"><B>TITULO: </B><Font>RELACION COBRANZA</Font></ss:Data></Cell>
   </Row>
   <Row>
    <Cell ss:MergeAcross="2" ss:StyleID="s27"><Data ss:Type="String"><B>FOLIO: </B><Font>'.$_GET[folio].'</Font></ss:Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">FECHA Y HR. IMP.: </Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s29"><Data ss:Type="String">'.date('d/m/Y h:i a').'</Data></Cell>
    <Cell ss:StyleID="s31"/>
   </Row>
   <Row>
    <Cell ss:MergeAcross="2" ss:StyleID="s27"><Data ss:Type="String"><B>FECHA: </B><Font>'.obtenerFecha($_GET[fecha]).'</Font></ss:Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"/>
   </Row>';
   	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS cobrador
	FROM catalogoempleado WHERE id=".$_GET[cobrador];
   	$c = mysql_query($s,$l) or die($s);
	$co= mysql_fetch_object($c);
	
   $xls .='<Row>
    <Cell ss:MergeAcross="2" ss:StyleID="s27"><Data ss:Type="String"><B>COBRADOR: </B><Font>'.cambio_texto($co->cobrador).'</Font></ss:Data></Cell>
   </Row>
   <Row>
    <Cell ss:MergeAcross="2" ss:StyleID="s27"><Data ss:Type="String"><B> </B><Font>'.$_GET[unidad].'</Font></ss:Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s26"/>
   </Row>
   <Row ss:Height="13.5">
    <Cell ss:StyleID="s25"><Data ss:Type="String">ESTADO</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">CLIENTE</Data></Cell>
	<Cell ss:StyleID="s25"><Data ss:Type="String">DIRECCION</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">FACTURA</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">F. VENCIMIENTO</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">IMPORTE</Data></Cell>
    <Cell ss:StyleID="s25"><Data ss:Type="String">OBSERVACIONES</Data></Cell>
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
		
		 $xls .='<Row>
	  	<Cell ss:StyleID="s27"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s27" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
			}
		}
	   $xls .='</Row>';
	}
   
  
  $xls .='</Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Layout x:Orientation="Landscape"/>
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
     <ActiveRow>10</ActiveRow>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"RELACIONCOBRANZA.xls\""); 
		print $xls;
?>