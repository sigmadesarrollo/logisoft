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

  <Created>2009-10-28T19:53:05Z</Created>

  <LastSaved>2009-10-28T20:04:56Z</LastSaved>

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

   <Font x:Family="Swiss" ss:Size="8" ss:Bold="1"/>

  </Style>

  <Style ss:ID="s22">

   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>

   <Borders>

    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>

   </Borders>

   <Font x:Family="Swiss" ss:Size="8" ss:Bold="1"/>

  </Style>

  <Style ss:ID="s23">

   <NumberFormat ss:Format="0%"/>

  </Style>

  <Style ss:ID="s27">

   <Font/>

  </Style>

 </Styles>

 <Worksheet ss:Name="Hoja1">';

	

	$l = mysql_connect("97.74.31.27","dbwebpmm","Sistemapmm09");

	//$l = mysql_connect("DBSERVER","root","root");

	mysql_select_db("dbwebpmm", $l);

	

	if($_GET[accion] == 19){//HISTORIAL DE CLIENTE

		$s = "SELECT DATE_FORMAT(gc.fecha,'%d/%m/%Y') AS fecha,
		IFNULL(DATE_FORMAT(gc.fecharenovacion,'%d/%m/%Y'),'') AS renovacion,
		DATE_FORMAT(gc.vigencia,'%d/%m/%Y') AS vigencia,
		IFNULL(sc.estado,'') AS estadocredito, 
		IFNULL(sc.montoautorizado,0) AS limitecredito,
		IF(gc.descuentosobreflete=1 OR gc.consignaciondescuento=1,'DESCUENTO',
		IF(gc.precioporkg=1 OR gc.consignacionkg=1,'KILOGRAMO',
		IF(gc.precioporcaja=1 OR gc.consignacioncaja=1,'PAQUETE',''))) AS tipoconvenio,
		IF(gc.descuentosobreflete=1 OR gc.consignaciondescuento=1,IFNULL(IF(gc.descuentosobreflete=1,
		CONCAT(gc.cantidaddescuento,'%'),CONCAT(0,'%')),
		CONCAT(0,'%')),
		IF(gc.precioporkg=1 OR gc.consignacionkg=1,'$0.00',
		IF(gc.precioporcaja=1 OR gc.consignacioncaja=1,'$0.00',''))) AS valorconvenio,
		IF(gc.consignaciondescuento=1,IFNULL(IF(gc.consignaciondescuento=1,
		CONCAT(gc.consignaciondescantidad,'%'),
		CONCAT(0,'%')),CONCAT(0,'%')),
		IF(gc.precioporkg=1 OR gc.consignacionkg=1,'',
		IF(gc.precioporcaja=1 OR gc.consignacioncaja=1,'',''))) AS precioempresarial
		FROM generacionconvenio gc
		LEFT JOIN solicitudcredito sc ON gc.folio = sc.folioconvenio
		WHERE gc.idcliente = ".$_GET[cliente]." AND YEAR(gc.fecha)= '".$_GET[fecha]."'";
		
		
		/*$s = "SELECT DATE_FORMAT(gc.fecha,'%d/%m/%Y') AS fecha,
		IFNULL(DATE_FORMAT(gc.fecharenovacion,'%d/%m/%Y'),'') AS renovacion,
		IFNULL(sc.estado,'') AS estadocredito, IFNULL(sc.montoautorizado,0) AS limitecredito,
		DATE_FORMAT(gc.vigencia,'%d/%m/%Y') AS vigencia,IF(gc.descuentosobreflete=1 OR gc.consignaciondescuento=1,'DESCUENTO',
		IF(gc.precioporkg=1 OR gc.consignacionkg=1,'KILOGRAMO',
		IF(gc.precioporcaja=1 OR gc.consignacioncaja=1,'PAQUETE',''))) AS tipoconvenio,
		IF(gc.descuentosobreflete=1 OR gc.consignaciondescuento=1,IFNULL(IF(gc.descuentosobreflete=1,
		CONCAT(gc.cantidaddescuento,'%'),CONCAT(0,'%')),
		CONCAT(0,'%')),
		IF(gc.precioporkg=1 OR gc.consignacionkg=1,'$0.00',
		IF(gc.precioporcaja=1 OR gc.consignacioncaja=1,'$0.00',''))) AS valorconvenio,
		IF(gc.consignaciondescuento=1,IFNULL(IF(gc.consignaciondescuento=1,
		CONCAT(gc.consignaciondescantidad,'%'),
		CONCAT(0,'%')),CONCAT(0,'%')),
		IF(gc.precioporkg=1 OR gc.consignacionkg=1,'',
		IF(gc.precioporcaja=1 OR gc.consignacioncaja=1,'',''))) AS precioempresarial
		FROM generacionconvenio gc
		LEFT JOIN solicitudcredito sc ON gc.folio = sc.folioconvenio
		WHERE gc.idcliente = ".$_GET[cliente]." AND YEAR(gc.fecha) = ".$_GET[fecha]."
		/*LIMIT 0,30*/

	}

		$r = mysql_query($s,$l) or die($s);

		$filas = mysql_num_rows($r);

	

  $xls .='<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+8).'" x:FullColumns="1"

   x:FullRows="1" ss:DefaultColumnWidth="60">

   <Column ss:Width="68.25"/>

   <Column ss:Width="93.75"/>

   <Column ss:Width="89.25"/>

   <Column ss:Width="76.5"/>

   <Column ss:Width="81"/>

   <Column ss:Width="67.5"/>

   <Column ss:Width="77.25"/>

   <Column ss:Width="94.5"/>

   <Row>

    <Cell ss:StyleID="s21"><Data ss:Type="String">TITULO:</Data></Cell>

	<Cell ss:StyleID="s21"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>

   </Row>

   <Row>

    <Cell ss:StyleID="s21"><Data ss:Type="String">FECHA:</Data></Cell>

	<Cell ss:StyleID="s21"><Data ss:Type="String">'.(($_GET[fecha]!="")? $_GET[fecha] : date('d/m/Y')).'</Data></Cell>

   </Row>

   <Row>

    <Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL:</Data></Cell>

	<Cell ss:StyleID="s21"><Data ss:Type="String">'.(($_GET[sucursal]!="0")? $row->prefijo : "TODAS" ).'</Data></Cell>

   </Row>

   <Row ss:Index="5" ss:AutoFitHeight="0" ss:Height="13.5">

    <Cell ss:StyleID="s22"><Data ss:Type="String">FECHA DE ALTA</Data></Cell>

    <Cell ss:StyleID="s22"><Data ss:Type="String">FECHA MODIFICACION</Data></Cell>

    <Cell ss:StyleID="s22"><Data ss:Type="String">FECHA VENCIMIENTO</Data></Cell>

    <Cell ss:StyleID="s22"><Data ss:Type="String">ESTADO CREDITO </Data></Cell>

    <Cell ss:StyleID="s22"><Data ss:Type="String">LIMITE DE CREDITO</Data></Cell>

    <Cell ss:StyleID="s22"><Data ss:Type="String">TIPO CONVENIO</Data></Cell>

    <Cell ss:StyleID="s22"><Data ss:Type="String">VALOR CONVENIO</Data></Cell>

    <Cell ss:StyleID="s22"><Data ss:Type="String">PRECIO EMPRESARIAL</Data></Cell>

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

    <PaperSizeIndex>9</PaperSizeIndex>

    <VerticalResolution>0</VerticalResolution>

   </Print>

   <Selected/>

   <Panes>

    <Pane>

     <Number>3</Number>

     <ActiveRow>6</ActiveRow>

     <ActiveCol>7</ActiveCol>

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