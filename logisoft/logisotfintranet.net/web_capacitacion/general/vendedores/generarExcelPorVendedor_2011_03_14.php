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
 		/*$str = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 

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
		}*/
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
 		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, guia, idcliente, cliente, 
		flete, comision FROM reporte_vendedores_cobrado
		WHERE idvendedor = '".$_GET[vendedor]."' AND activo = 'S'
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l)or die($s); 
 		$filas = mysql_num_rows($r);
		
	$xls .='<Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="'.($filas+8).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="104.25"/>
   <Column ss:Width="54"/>
   <Column ss:AutoFitWidth="0" ss:Width="233.25"/>
   <Column ss:Width="102"/>
   <Column ss:AutoFitWidth="0" ss:Width="84"/>
  	'.$titulo1.'
   <Row ss:Index="7" ss:Height="13.5">
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">GUIA</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String"># CLIENTE</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE ASIGNADO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">VALOR FLETE NETO</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">COMISION</Data></Cell>
   </Row>';
		$idcliente = 2;
 	}else if($_GET[accion] == 2){
		
		$s = "SELECT DATE_FORMAT(v.fecha,'%d/%m/%Y') AS fecha, v.guia, IFNULL(v.prefijodestino,'') AS prefijodestino, 
		v.idcliente, v.cliente, v.flete, IF(v.guia=t.id,t.estado,IF(v.guia=s.id,s.estado,'')) AS estado
		FROM reporte_vendedores_ventas v
		INNER JOIN guiasventanilla t ON v.guia = t.id		
		LEFT JOIN solicitudguiasempresariales s ON v.guia = s.id
		WHERE v.idvendedor = '".$_GET[vendedor]."' AND v.activo = 'S'
		AND v.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND v.prefijoorigen = '$_GET[sucursal]'
		UNION
		SELECT DATE_FORMAT(v.fecha,'%d/%m/%Y') AS fecha, v.guia, IFNULL(v.prefijodestino,'') AS prefijodestino, 
		v.idcliente, v.cliente, v.flete, IF(v.guia=t.id,t.estado,IF(v.guia=s.id,s.estado,'')) AS estado
		FROM reporte_vendedores_ventas v
		INNER JOIN guiasempresariales t ON v.guia = t.id		
		LEFT JOIN solicitudguiasempresariales s ON v.guia = s.id
		WHERE v.idvendedor = '".$_GET[vendedor]."' AND v.activo = 'S'
		AND v.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND v.prefijoorigen = '$_GET[sucursal]'";
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
    <Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE ASIGNADO</Data></Cell>
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
					$xls .= '<Cell><Data ss:Type="Number">'.$f[$i].'</Data></Cell>';
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
			$xls .= '<Cell ss:StyleID="s22" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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