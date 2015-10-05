<? 	
	//die("DISCULPE LAS MOLESTIAS EN ESTE MOMENTO EL REPORTE SE ENCUENTRA FUERA DE SERVICIO");

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
	
	function cambiofecha($fecha){//Convierte fecha de normal a mysql 
    	ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    	return $lafecha;
	} 
	
	
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
	
	$s = "SELECT CASE DAYOFWEEK(CURDATE()) 
	WHEN 1 THEN 'DOMINGO'
	WHEN 2 THEN 'LUNES'
	WHEN 3 THEN 'MARTES'
	WHEN 4 THEN 'MIERCOLES'
	WHEN 5 THEN 'JUEVES'
	WHEN 6 THEN 'VIERNES'
	WHEN 7 THEN 'SABADO' ELSE '' END AS dia,	
		CASE MONTH(CURDATE()) 
		WHEN 1 THEN 'ENERO'
		WHEN 2 THEN 'FEBRERO'
		WHEN 3 THEN 'MARZO'
		WHEN 4 THEN 'ABRIL'
		WHEN 5 THEN 'MAYO'
		WHEN 6 THEN 'JUNIO'
		WHEN 7 THEN 'JULIO'
		WHEN 8 THEN 'AGOSTO'
		WHEN 9 THEN 'SEPTIEMBRE'
		WHEN 10 THEN 'OCTUBRE'
		WHEN 11 THEN 'NOVIEMBRE'
		WHEN 12 THEN 'DICIEMBRE' ELSE '' END AS mes";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$dia = $f->dia; $mes = $f->mes;
	
	$s = "SELECT descripcion FROM catalogosucursal WHERE id=".$_GET[sucursal];
	$r = mysql_query($s,$l) or die($s);
	$su= mysql_fetch_object($r);

	$s = "CALL proc_ReporteCajaCondensado(".$_GET[sucursal].",'".cambiofecha($_GET[fechainicio])."','".cambiofecha(((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]))."')";
	$r = mysql_query($s,$l) or die($s);
	
	$s = "SELECT @guiaspagadocontado AS guiaspagadocontado, @registrosguiaspagadocontado AS registrosguiaspagadocontado, 
	@guiaspagadocredito AS guiaspagadocredito, @registrosguiaspagadocredito AS registrosguiaspagadocredito, @guiasporcobrarcontado AS guiasporcobrarcontado,
	@registrosguiasporcobrarcontado AS registrosguiasporcobrarcontado, @guiasporcobrarcredito AS guiasporcobrarcredito, 
	@registrosguiasporcobrarcredito AS registrosguiasporcobrarcredito, @facturasguiasprepagadascontado AS facturasguiasprepagadascontado,
	@registrosfacturasguiasprepagadascontado AS registrosfacturasguiasprepagadascontado, @facturasguiasprepagadascredito As facturasguiasprepagadascredito,
	@registrosfacturasguiasprepagadascredito AS registrosfacturasguiasprepagadascredito, @factguiasconsignacioncontado AS factguiasconsignacioncontado,
	@registrosfactguiasconsignacioncontado AS registrosfactguiasconsignacioncontado, @factguiasconsignacioncredito AS factguiasconsignacioncredito,
	@registrosfactguiasconsignacioncredito AS registrosfactguiasconsignacioncredito, @facturassobrepesocontado AS facturassobrepesocontado,
	@registrosfacturassobrepesocontado AS registrosfacturassobrepesocontado, @facturassobrepesocredito AS facturassobrepesocredito,
	@registrosfacturassobrepesocredito AS registrosfacturassobrepesocredito, @valordeclaradocontado AS valordeclaradocontado,
	@registrosvalordeclaradocontado AS registrosvalordeclaradocontado, @valordeclaradocredito AS valordeclaradocredito,
	@registrosvalordeclaradocredito AS registrosvalordeclaradocredito, @facturacionotrosconceptoscontado AS facturacionotrosconceptoscontado,
	@registrosfacturacionotrosconceptoscontado AS registrosfacturacionotrosconceptoscontado, @facturacionotrosconceptoscredito AS facturacionotrosconceptoscredito,
	@registrosfacturacionotrosconceptoscredito AS registrosfacturacionotrosconceptoscredito, @correointernolocal AS correointernolocal,
	@guiascobrarcontadoentregadas AS guiascobrarcontadoentregadas, @registrosguiascobrarcontadoentregadas AS registrosguiascobrarcontadoentregadas,
	@guiascobrarcreditoentregadas AS guiascobrarcreditoentregadas, @registrosguiascobrarcreditoentregadas AS registrosguiascobrarcreditoentregadas,
	@guiaspagadascontadoentregas AS guiaspagadascontadoentregas, @registrosguiaspagadascontadoentregas AS registrosguiaspagadascontadoentregas,
	@guiaspagadascreditoentregas AS guiaspagadascreditoentregas, @registrosguiaspagadascreditoentregas AS registrosguiaspagadascreditoentregas,
	@guiascanceladas AS guiascanceladas, @registrosguiascanceladas AS registrosguiascanceladas, @facturascanceladas AS facturascanceladas,
	@registrosfacturascanceladas AS registrosfacturascanceladas, @guiasautorizacioncancelar AS guiasautorizacioncancelar, 
	@registrosguiasautorizacioncancelar AS registrosguiasautorizacioncancelar, @cobranza AS cobranza, @registroscobranza AS registroscobranza";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	if(!empty($_GET[fechainicio])){
		$fecha = 'DEL '.$_GET[fechainicio].' AL '.((!empty($_GET[fechafin]))?$_GET[fechafin]:$_GET[fechainicio]).'';
	}else{
		$fecha = 'DEL DIA '.$dia.', '.date("d").' DE '.$mes.' DE '.date("Y").'';
	}

$xml = '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>pcJose</Author>
  <LastAuthor>pcJose</LastAuthor>
  <LastPrinted>2010-09-23T06:39:35Z</LastPrinted>
  <Created>2010-09-23T06:38:16Z</Created>
  <LastSaved>2010-09-23T06:48:04Z</LastSaved>
  <Company>DPSoft</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>9975</WindowHeight>
  <WindowWidth>18795</WindowWidth>
  <WindowTopX>240</WindowTopX>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s24">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s26">
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
 <Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="51" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="60">
   <Row>
    <Cell ss:MergeAcross="7" ss:StyleID="s23"><Data ss:Type="String">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</Data></Cell>
   </Row>
   <Row>
    <Cell ss:Index="5" ss:StyleID="s24"/>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL:</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="s25"><Data ss:Type="String">'.cambio_texto($su->descripcion).'</Data></Cell>
    <Cell ss:StyleID="s24"><Data ss:Type="String">IMPRESO:</Data></Cell>
    <Cell><Data ss:Type="String">'.date('d/m/Y').'</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">REPORTE:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s25"><Data ss:Type="String">CORTE DIARIO CONDENSADO</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="s24"><Data ss:Type="String">FECHA:</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s25"><Data ss:Type="String">'.$fecha.'</Data></Cell>
   </Row>
   <Row ss:Index="7">
    <Cell ss:StyleID="s24"><Data ss:Type="String">POR PAQUETERIA LOCAL ENVIADA:</Data></Cell>
    <Cell ss:Index="6" ss:StyleID="s26"><Data ss:Type="String">IMPORTE</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">GUIAS</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias Pagado de Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiaspagadocontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiaspagadocontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias Pagado de Credito</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiaspagadocredito.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiaspagadocredito.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias por Cobrar Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiasporcobrarcontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiasporcobrarcontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias por Cobrar Credito</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiasporcobrarcredito.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiasporcobrarcredito.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Ventas Guias Prepagadas Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturasguiasprepagadascontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturasguiasprepagadascontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Ventas Guias Prepagadas Credito</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturasguiasprepagadascredito.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturasguiasprepagadascredito.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Guias Consignacion Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->factguiasconsignacioncontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfactguiasconsignacioncontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Guias Consignacion Credito</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->factguiasconsignacioncredito.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfactguiasconsignacioncredito.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Sobrepeso Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturassobrepesocontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturassobrepesocontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Sobrepeso Credito</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturassobrepesocredito.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturassobrepesocredito.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Valor Declarado Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->valordeclaradocontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosvalordeclaradocontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Valor Declarado Credito</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->valordeclaradocredito.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosvalordeclaradocredito.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Otros Conceptos Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturacionotrosconceptoscontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturacionotrosconceptoscontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturacion de Otros Conceptos Credito</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturacionotrosconceptoscredito.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturacionotrosconceptoscredito.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Correo Interno</Data></Cell>
	<Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">0</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->correointernolocal.'</Data></Cell>
   </Row>
   <Row ss:Index="24">
    <Cell ss:StyleID="s24"><Data ss:Type="String">TOTALES:</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6" ss:Formula="=SUM(R[-16]C:R[-2]C)"><Data ss:Type="Number">0</Data></Cell>
    <Cell ss:Formula="=SUM(R[-16]C:R[-2]C)"><Data ss:Type="Number">0</Data></Cell>
   </Row>
   <Row ss:Index="26">
    <Cell ss:StyleID="s24"><Data ss:Type="String">POR PAQUETERIA FORANEA ENTREGADA:</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias por Cobrar Contado Entregadas</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiascobrarcontadoentregadas.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiascobrarcontadoentregadas.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias por Cobrar Credito Entregadas</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiascobrarcreditoentregadas.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiascobrarcreditoentregadas.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias Pagadas Contado Entregadas</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiaspagadascontadoentregas.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiaspagadascontadoentregas.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias Pagadas Credito Entregadas</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiaspagadascreditoentregas.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiaspagadascreditoentregas.'</Data></Cell>
   </Row>
   <Row ss:Index="32">
    <Cell ss:StyleID="s24"><Data ss:Type="String">TOTALES:</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6" ss:Formula="=SUM(R[-5]C:R[-2]C)"><Data ss:Type="Number">0</Data></Cell>
    <Cell ss:Formula="=SUM(R[-5]C:R[-2]C)"><Data ss:Type="Number">0</Data></Cell>
   </Row>
   <Row ss:Index="34">
    <Cell ss:StyleID="s24"><Data ss:Type="String">RESUMEN DE INGRESOS:</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Ingresos de Guias Pagado de Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiaspagadocontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiaspagadocontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Ingresos de Guias por Cobrar Contado Entregadas</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiascobrarcontadoentregadas.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiascobrarcontadoentregadas.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Ingresos de Cobranza Paqueteria</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->cobranza.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registroscobranza.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Ingresos de Facturacion de Ventas Guias Prepagadas Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturasguiasprepagadascontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturasguiasprepagadascontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Ingresos de Facturacion de Guias Consignacion Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->factguiasconsignacioncontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfactguiasconsignacioncontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Ingresos de Facturacion de Sobrepeso Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturassobrepesocontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturassobrepesocontado.'</Data></Cell>
   </Row>   
   <Row>
    <Cell><Data ss:Type="String">Ingresos de Facturacion de Valor Declarado Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->valordeclaradocontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosvalordeclaradocontado.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Ingresos de Facturacion de Otros Conceptos Contado</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturacionotrosconceptoscontado.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturacionotrosconceptoscontado.'</Data></Cell>
   </Row>
   <Row ss:Index="45">
    <Cell ss:StyleID="s24"><Data ss:Type="String">TOTALES:</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6" ss:Formula="=SUM(R[-10]C:R[-2]C)"><Data ss:Type="Number">0</Data></Cell>
    <Cell ss:Formula="=SUM(R[-10]C:R[-2]C)"><Data ss:Type="Number">0</Data></Cell>
   </Row>
   <Row ss:Index="47">
    <Cell><Data ss:Type="String">Guias Canceladas del Dia</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiascanceladas.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiascanceladas.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Facturas Canceladas del Dia</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->facturascanceladas.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosfacturascanceladas.'</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Guias Pendientes de Cancelar</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6"><Data ss:Type="Number">'.$f->guiasautorizacioncancelar.'</Data></Cell>
    <Cell><Data ss:Type="Number">'.$f->registrosguiasautorizacioncancelar.'</Data></Cell>
   </Row>
   <Row ss:Index="51">
    <Cell ss:StyleID="s24"><Data ss:Type="String">TOTALES:</Data></Cell>
    <Cell ss:StyleID="s62" ss:Index="6" ss:Formula="=SUM(R[-4]C:R[-2]C)"><Data ss:Type="Number">0</Data></Cell>
    <Cell ss:Formula="=SUM(R[-4]C:R[-2]C)"><Data ss:Type="Number">0</Data></Cell>
   </Row>
  </Table>
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
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <Selected/>
   <TopRowVisible>27</TopRowVisible>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>50</ActiveRow>
     <ActiveCol>5</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet> 
</Workbook>';

	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"reporteCorteDiario.xls\"" ); 
	print $xml;
?>
