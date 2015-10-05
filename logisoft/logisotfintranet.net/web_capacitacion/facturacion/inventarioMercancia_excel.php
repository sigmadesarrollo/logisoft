<?
	
	//die("Estamos mejorando el proceso de trabajo de esta pagina, disculpe las molestias");
	
	function cambiaf_a_mysql($fecha){//Convierte fecha de normal a mysql 
    	ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
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
  <Created>2010-07-22T17:29:48Z</Created>
  <Company>PMM</Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>9975</WindowHeight>
  <WindowWidth>21195</WindowWidth>
  <WindowTopX>120</WindowTopX>
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
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s24">
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s26">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s27">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font x:Family="Swiss" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s31">
   <Borders/>
   <Font x:Family="Swiss" ss:Size="8"/>
   <Interior/>
  </Style>
  <Style ss:ID="s32">
   <Font x:Family="Swiss" ss:Size="8"/>
  </Style>
 </Styles>';
 
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
 
 	$s = "SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal];
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
 
 	//$row[0]=sucursal, $row[1]=fechainicio, $row[2]=fechafin, $row[3]=estado
		$row = split(",",$_GET[arre]);
		if($_GET[estado] == "EN REPARTO EAD"){
			$condicion=" t.estado='EN REPARTO EAD'";
		}else if($_GET[estado] == "EAD"){
			$condicion=" t.estado='ALMACEN DESTINO' AND ocurre=0";
		}else if($_GET[estado] == "OCURRE"){
			$condicion=" t.estado='ALMACEN DESTINO' AND ocurre=1";
		}else if($_GET[estado] == "ALMACEN TRANSBORDO"){		
			$condicion = " t.estado = 'ALMACEN TRANSBORDO'";
		}else if($_GET[estado] =="TODOS"){
			$condicion=" (t.estado='EN REPARTO EAD' OR t.estado='ALMACEN DESTINO' OR t.estado='ALMACEN TRANSBORDO') AND (t.ocurre=0 OR t.ocurre=1)";
		}
 
 		$s = "SELECT DATE_FORMAT(t.fecha,'%d/%m/%Y') AS fecha, t.guia, t.flete, t.pago, t.cliente, t.descripcion, 
		t.contenido, t.importe, t.almacen FROM (
		SELECT g.idsucursaldestino, cs.prefijo AS sucursal, g.id AS guia, 
		g.iddestinatario AS nocliente, CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
		g.fecha, g.est