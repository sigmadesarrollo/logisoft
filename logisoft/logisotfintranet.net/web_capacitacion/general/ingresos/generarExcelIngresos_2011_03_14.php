<?	function cambio_texto($texto){
		if($texto == " ")
			$texto = "";
		if($texto!=""){
			$n_texto=ereg_replace("�","&#224;",$texto);
			$n_texto=ereg_replace("�","&#233;",$n_texto);
			$n_texto=ereg_replace("�","&#237;",$n_texto);
			$n_texto=ereg_replace("�","&#243;",$n_texto);
			$n_texto=ereg_replace("�","&#250;",$n_texto);
			
			$n_texto=ereg_replace("�","&#193;",$n_texto);
			$n_texto=ereg_replace("�","&#201;",$n_texto);
			$n_texto=ereg_replace("�","&#205;",$n_texto);
			$n_texto=ereg_replace("�","&#211;",$n_texto);
			$n_texto=ereg_replace("�","&#218;",$n_texto);
			
			$n_texto=ereg_replace("�", "&#241;", $n_texto);
			$n_texto=ereg_replace("�", "&#209;", $n_texto);
			$n_texto=ereg_replace("�", "&#191;", $n_texto);
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
  <Created>2009-11-09T23:58:05Z</Created>
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
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s23">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
   <Font x:Family="Swiss" ss:Size="9" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s24">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>
   </Borders>
  </Style>
  <Style ss:ID="s25">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s28">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">';
 			
	
		$cabezera = '<Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String">TITULO:</Data></Cell>
		<Cell ss:MergeAcross="3" ss:StyleID="s28" ><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
		</Row>
		<Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String">FECHA:</Data></Cell>
		<Cell ss:MergeAcross="3" ss:StyleID="s28" ><Data ss:Type="String">'.$_GET[fecha].' AL '.$_GET[fecha2].'</Data></Cell>
		</Row>';
		
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
		
	if($_GET[accion]==1){//REPORTE INGRESOS
		$s="SELECT cs.prefijo AS nombresucursal ,IFNULL(SUM(fp.efectivo),0)AS efectivo,
		IFNULL(ck.cheques,0) AS cheques, IFNULL(cko.otros,0) AS otros , IFNULL(SUM(fp.transferencia),0)AS transferencia,
		IFNULL(SUM(fp.tarjeta),0)AS tarjeta,
		IFNULL(SUM(fp.notacredito),0) AS nc,(IFNULL(SUM(fp.efectivo),0)+IFNULL(ck.cheques,0)+IFNULL(cko.otros,0)+
		IFNULL(SUM(fp.transferencia),0)+IFNULL(SUM(fp.tarjeta),0)+IFNULL(SUM(fp.notacredito),0)) as total FROM formapago fp
		INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
		LEFT JOIN
		(SELECT IFNULL(fp.sucursal,'') AS sucursal,IFNULL(SUM(fp.cheque),0)AS cheques FROM formapago fp 
		INNER JOIN catalogobanco cb ON fp.banco=cb.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND cb.PorDefault=1 and isnull(fp.fechacancelacion) GROUP BY fp.sucursal)ck ON cs.id=ck.sucursal
		LEFT JOIN
		(SELECT IFNULL(fp.sucursal,'') AS sucursal,IFNULL(SUM(fp.cheque),0)AS otros FROM formapago fp 
		INNER JOIN catalogobanco cb ON fp.banco=cb.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND cb.PorDefault<>1 and isnull(fp.fechacancelacion) GROUP BY fp.sucursal)cko ON cs.id=cko.sucursal
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		and isnull(fp.fechacancelacion)GROUP BY cs.id";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72" ss:Span="4"/>
		<Column ss:Index="8" ss:AutoFitWidth="0" ss:Width="82.5"/>
		'.$cabezera.'
		<Row ss:Index="4">
		<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">EFECTIVO </Data></Cell>
		<Cell ss:MergeAcross="1" ss:StyleID="s21"><Data ss:Type="String">CHEQUES</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TRANFERENCIA</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">PAGO CON </Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">NOTAS DE </Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TOTAL</Data></Cell>
		</Row>
		<Row ss:Height="13.5">
		<Cell ss:StyleID="s23"/>
		<Cell ss:StyleID="s23"/>
		<Cell ss:StyleID="s23"><Data ss:Type="String">BANCOMER</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">OTROS</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">ELECTRONICA</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TARJETA</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CREDITO </Data></Cell>
		<Cell ss:StyleID="s24"/>
		</Row>';
		
   }else if($_GET[accion]==2){//CONCILIACION DE INGRESOS
   		
	$s="SELECT cs.prefijo AS nombresucursal,SUM(IngresosFormadeCobro.contado) AS contado,
	SUM(IngresosFormadeCobro.cobranza)AS cobranza,SUM(IngresosFormadeCobro.entregadas)AS entregadas,
	(SUM(IngresosFormadeCobro.contado)+SUM(IngresosFormadeCobro.cobranza)+SUM(IngresosFormadeCobro.entregadas))AS total, 0 AS depositado,
	(SUM(IngresosFormadeCobro.contado)+SUM(IngresosFormadeCobro.cobranza)+SUM(IngresosFormadeCobro.entregadas)) AS saldo FROM (
		/*GUIAS VENTANILLA Y EMPRESARIALES*/
		SELECT sucursal,0 AS cobranza,SUM(total) AS contado,0 AS entregadas 
		FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND procedencia IN('G') 
		and isnull(formapago.fechacancelacion)
		GROUP BY sucursal	
	UNION 
		/*LIQUIDACION COBRANZA Y ABONOS*/
		SELECT sucursal,SUM(total)AS cobranza,0 AS contado,0 AS entregadas 
		FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND procedencia IN('A','C') 
		and isnull(formapago.fechacancelacion)
		GROUP BY sucursal	
	UNION 
		/*LIQUIDACION MERCANCIA Y ENTREGAS OCURRE (ENTREGADAS)*/
		SELECT sucursal,0 AS cobranza,0 AS contado,SUM(total) AS entregadas 
		FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND procedencia IN('M') 
		and isnull(formapago.fechacancelacion)
		GROUP BY sucursal
	UNION 
		/*LIQUIDACION MERCANCIA Y ENTREGAS OCURRE (ENTREGADAS)*/
		SELECT fp.sucursal,0 AS cobranza,0 AS contado, sum(gv.total) AS entregadas 
		FROM formapago fp
		INNER JOIN entregasocurre eo ON fp.guia=eo.folio
		INNER JOIN entregasocurre_detalle ed ON eo.folio=ed.entregaocurre	
		INNER JOIN guiasventanilla gv ON ed.guia=gv.id	
		INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
		INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='O' and gv.tipoflete=1 and gv.condicionpago=0
		AND isnull(fp.fechacancelacion) GROUP BY cs.id
	UNION 
		/*FACTURACION*/
		SELECT formapago.sucursal, 0 AS cobranza, SUM(if(f.credito='SI',0,formapago.total)) AS contado,0 AS entregadas 
		FROM formapago 
		inner join facturacion f on formapago.guia = f.folio
		WHERE formapago.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND formapago.procedencia IN('F') 
		and isnull(formapago.fechacancelacion)
		GROUP BY formapago.sucursal
	)IngresosFormadeCobro 
	INNER JOIN catalogosucursal cs ON IngresosFormadeCobro.sucursal=cs.id
	WHERE IngresosFormadeCobro.sucursal<>''
	GROUP BY IngresosFormadeCobro.sucursal";
		$r = mysql_query($s,$l) or die($s);
		
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72" ss:Span="4"/>
		<Column ss:Index="8" ss:AutoFitWidth="0" ss:Width="82.5"/>
		'.$cabezera.'
		<Row ss:Index="4" ss:Height="13.5">
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CONTADO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">COBRANZA</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">ENTREGADAS</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TOTAL</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">DEPOSITADO</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">SALDO</Data></Cell>
		</Row>';
		
   }else if($_GET[accion]==3){//INGRESOS POR GUAS DE CONTADO   	

$s="SELECT nombresucursal,fecha,guia,cliente,importe,caja FROM (
	(SELECT cs.id AS sucursal,cs.prefijo as nombresucursal,DATE_FORMAT(gv.fecha,'%d/%m/%Y')AS fecha,gv.id AS guia,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp
	INNER JOIN guiasventanilla gv ON fp.guia=gv.id
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND fp.procedencia IN('G')
	AND isnull(fp.fechacancelacion)
	AND cs.id='" .$_GET[sucursal]."')
UNION ALL
	(SELECT cs.id AS sucursal,cs.prefijo as nombresucursal,gv.fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	gv.total AS importe,gv.idusuario AS caja FROM formapago fp
	INNER JOIN guiasempresariales gv ON fp.guia=gv.id
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)		
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND 
	fp.procedencia IN('E')
	AND isnull(fp.fechacancelacion)
	AND cs.id='" .$_GET[sucursal]."')
UNION ALL
	(SELECT cs.id AS sucursal,cs.prefijo as nombresucursal,DATE_FORMAT(f.fecha,'%d/%m/%Y')AS fecha,concat('FA-',f.folio) AS guia,
CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
fp.total AS importe,f.idusuario AS caja
	FROM facturacion f
	INNER join formapago fp on f.folio = fp.guia and fp.procedencia = 'F'	
	INNER JOIN catalogosucursal cs ON cs.id=f.idsucursal
	INNER JOIN catalogocliente cc ON cc.id = f.cliente
	WHERE f.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND f.facturaestado='GUARDADO' and f.credito='NO'
	AND cs.id='" .$_GET[sucursal]."')
)Tabla ORDER BY fecha ";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= ' <Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72"/>
		<Column ss:AutoFitWidth="0" ss:Width="213.75"/>
		<Column ss:AutoFitWidth="0" ss:Width="72" ss:Span="1"/>
		<Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="82.5"/>
		'.$cabezera.'
		<Row ss:Index="4" ss:Height="13.5">
		<Cell ss:StyleID="s23"><Data ss:Type="String">SUCURSAL </Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">GUIA</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">IMPORTE</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CAJA</Data></Cell>
		</Row>';
		
   }else if($_GET[accion]==4){//INGRESOS POR COBRANZA
	
$s="SELECT nombresucursal,DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,guia,cliente,importe,caja FROM (
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,fp.fecha,lcd.guia,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,lcd.importe,
		lcd.idusuario AS caja 
		FROM formapago fp
		INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
		INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		INNER JOIN catalogocliente cc ON lcd.cliente=cc.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='C' AND cs.id='" .$_GET[sucursal]."'
		AND isnull(fp.fechacancelacion) group by lcd.guia)
	UNION 
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,fp.fecha,
		gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
		gv.total AS importe,gv.idusuario AS caja FROM formapago fp 
		INNER JOIN abonodecliente a ON fp.guia=a.folio
		INNER JOIN guiasventanilla gv ON a.factura=gv.factura		
		LEFT JOIN catalogosucursal cs ON fp.sucursal=cs.id 
		LEFT JOIN catalogocliente cc ON a.idcliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='A' AND cs.id='" .$_GET[sucursal]."'
		AND isnull(fp.fechacancelacion) GROUP BY gv.id)
	UNION 
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,fp.fecha,
		 gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp
		INNER JOIN guiasempresariales gv ON fp.guia=gv.id 
		INNER JOIN abonodecliente a ON gv.factura=a.factura		
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
		INNER JOIN catalogocliente cc ON a.idcliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='G' AND fp.tipo='E' AND cs.id='" .$_GET[sucursal]."'
		AND isnull(fp.fechacancelacion) GROUP BY gv.id)
	UNION
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,fp.fecha,
		 f.folio AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,fp.total AS importe,f.cliente AS caja 
		FROM formapago fp
		INNER JOIN facturacion f ON fp.guia=f.folio 		
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='F' AND cs.id='" .$_GET[sucursal]."' and f.credito='SI'
		AND isnull(fp.fechacancelacion) GROUP BY f.folio)
	)Tabla GROUP BY guia ORDER BY fecha,guia";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72"/>
		<Column ss:AutoFitWidth="0" ss:Width="213.75"/>
		<Column ss:AutoFitWidth="0" ss:Width="72" ss:Span="1"/>
		<Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="82.5"/>
		'.$cabezera.'
		<Row ss:Index="4" ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">GUIA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">IMPORTE</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">CAJA</Data></Cell>
		</Row>';
		
   }else if($_GET[accion]==5){//INGRESOS POR GUIAS ENTREGADAS   		

		$s="SELECT nombresucursal,DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,guia,cliente,importe,caja 
		FROM (
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
		gv.total AS importe,gv.idusuario AS caja FROM formapago fp
		INNER JOIN entregasocurre eo ON fp.guia=eo.folio
		INNER JOIN entregasocurre_detalle ed ON eo.folio=ed.entregaocurre	
		INNER JOIN guiasventanilla gv ON ed.guia=gv.id	
		INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
		INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='O' AND cs.id='" .$_GET[sucursal]."' and gv.tipoflete=1 and gv.condicionpago=0
		AND isnull(fp.fechacancelacion) GROUP BY gv.id)
	UNION
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
		gv.total AS importe,gv.idusuario AS caja FROM formapago fp
		INNER JOIN guiasventanilla gv ON fp.guia=gv.id	
		INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
		INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='M' AND cs.id='" .$_GET[sucursal]."'
		AND isnull(fp.fechacancelacion) GROUP BY gv.id)
)Tabla GROUP BY guia ORDER BY fecha,guia";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="7" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72"/>
		<Column ss:AutoFitWidth="0" ss:Width="213.75"/>
		<Column ss:AutoFitWidth="0" ss:Width="72" ss:Span="1"/>
		<Column ss:Index="7" ss:AutoFitWidth="0" ss:Width="82.5"/>
		'.$cabezera.'
		<Row ss:Index="4" ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL </Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">FECHA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">GUIA</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">IMPORTE</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">CAJA</Data></Cell>
		</Row>';
		
   }else if($_GET[accion]==6){//DIFERENCIAS EN CONCILIACION
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="8" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72" ss:Span="4"/>
		<Column ss:Index="8" ss:AutoFitWidth="0" ss:Width="82.5"/>
		'.$cabezera.'
		<Row ss:Index="4">
		<Cell ss:StyleID="s21"><Data ss:Type="String">SUCURSAL</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">EFECTIVO </Data></Cell>
		<Cell ss:MergeAcross="1" ss:StyleID="s21"><Data ss:Type="String">CHEQUES</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TRANFERENCIA</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">PAGO CON </Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">NOTAS DE </Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">TOTAL</Data></Cell>
		</Row>
		<Row ss:Height="13.5">
		<Cell ss:StyleID="s23"/>
		<Cell ss:StyleID="s23"/>
		<Cell ss:StyleID="s23"><Data ss:Type="String">BANCOMER</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">OTROS</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">ELECTRONICA</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">TARJETA</Data></Cell>
		<Cell ss:StyleID="s23"><Data ss:Type="String">CREDITO </Data></Cell>
		<Cell ss:StyleID="s24"/>
		</Row>';
		
   }else if($_GET[accion]==7){//DESCRIPICON DE NOTA DE CREDITO
   		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="226.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72"/>
		<Column ss:AutoFitWidth="0" ss:Width="82.5"/>
		'.$cabezera.'
		<Row ss:Index="4" ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s24"><Data ss:Type="String">SUCURSAL </Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String"># NOTA CREDITO</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">IMPORTE</Data></Cell>
		</Row>';
		
   }else if($_GET[accion]==8){//DEPOSITO DE INGRESOS
   		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .='<Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="'.($filas+7).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Width="123"/>
		<Column ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72"/>
		<Column ss:AutoFitWidth="0" ss:Width="93.75"/>
		<Column ss:AutoFitWidth="0" ss:Width="203.25"/>
		<Column ss:AutoFitWidth="0" ss:Width="72"/>
		<Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String">TITULO:</Data></Cell>
		<Cell ss:MergeAcross="3" ss:StyleID="s28"><Data ss:Type="String">'.$_GET[titulo].'</Data></Cell>
		</Row>
		<Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String">FECHA:</Data></Cell>
		<Cell ss:MergeAcross="3" ss:StyleID="s28"><Data ss:Type="String">'.$_GET[fecha].' AL '.$_GET[fecha2].'</Data></Cell>
		</Row>
		<Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String">SUCURSAL:</Data></Cell>
		<Cell ss:StyleID="s28"><Data ss:Type="String">'.$_GET[sucursal].'</Data></Cell>
		<Cell ss:StyleID="s25"/>
		<Cell ss:StyleID="s25"/>
		<Cell ss:StyleID="s25"/>
		</Row>
		<Row>
		<Cell ss:StyleID="s25"><Data ss:Type="String">IMPORTE A DEPOSITAR:</Data></Cell>
		<Cell ss:Index="3" ss:MergeAcross="1" ss:StyleID="s28" ><Data ss:Type="Number">'.$_GET[depositar].'</Data></Cell>
		</Row>
		<Row ss:Index="6" ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s24"><Data ss:Type="String">COMPROBACION</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">IMPORTE</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">FOLIO</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">BANCO</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s24"><Data ss:Type="String">FACTURAS</Data></Cell>
		</Row>';
		
   }else if($_GET[accion]==9){//RELACION DE CHEQUES DEPOSITADOS
   		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$filas = mysql_num_rows($r);
		
		$xls .= '<Table ss:ExpandedColumnCount="5" ss:ExpandedRowCount="'.($filas+6).'" x:FullColumns="1"
		x:FullRows="1" ss:DefaultColumnWidth="60">
		<Column ss:Index="2" ss:AutoFitWidth="0" ss:Width="73.5"/>
		<Column ss:AutoFitWidth="0" ss:Width="72"/>
		<Column ss:AutoFitWidth="0" ss:Width="221.25"/>
		<Column ss:Width="89.25"/>
		'.$cabezera.'
		<Row ss:Index="4" ss:AutoFitHeight="0" ss:Height="13.5">
		<Cell ss:StyleID="s26"><Data ss:Type="String"># CHEQUE</Data></Cell>
		<Cell ss:StyleID="s26"><Data ss:Type="String">IMPORTE</Data></Cell>
		<Cell ss:StyleID="s26"><Data ss:Type="String">BANCO</Data></Cell>
		<Cell ss:StyleID="s26"><Data ss:Type="String">CLIENTE</Data></Cell>
		<Cell ss:StyleID="s26"><Data ss:Type="String">FACTURAS O GUIAS</Data></Cell>
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
	  	<Cell ss:StyleID="s25"><Data ss:Type="String">TOTALES</Data></Cell>';   	 	
		for($i=1;$i<$arr;$i++){
			if($arre[$i] == "SI"){
			$xls .= '<Cell ss:StyleID="s25" ss:Index="'.$i.'" ss:Formula="=SUM(R[-'.$filas.']C:R[-1]C)"><Data ss:Type="Number">0</Data></Cell>';
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