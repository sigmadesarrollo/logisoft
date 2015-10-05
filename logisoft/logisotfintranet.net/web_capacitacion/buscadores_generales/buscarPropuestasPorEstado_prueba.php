<? session_start();
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	
	if($_GET[pestado]!="TODOS"){
		if($_GET[pestado] == "EN AUTORIZACION"){
			$estadopropuesta = " where p.estadopropuesta LIKE '$_GET[pestado]%' ";
		}else{
			$estadopropuesta = " where p.estadopropuesta = '$_GET[pestado]' AND NOT EXISTS(SELECT * FROM generacionconvenio WHERE propuesta = p.folio) ";
		}
	}
	
	$get=@mysql_query("SELECT count(*) FROM propuestaconvenio p
		$estadopropuesta");
	$total =@mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="select.js"></script>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form name="buscar" >
<table width="604"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="59" class="FondoTabla">Folio</td>
      <td width="102" class="FondoTabla">Fecha</td>
      <td width="54" class="FondoTabla">Tipo</td>
      <td width="243" class="FondoTabla">Nombre</td>
      <td width="80" class="FondoTabla">AUTORIZ.</td>
      <td width="52" class="FondoTabla">SUC</td>
    </tr>
    <tr>
      <td height="300px" colspan="6" valign="top" class="Tablas"><table width="100%" border="0" align="center" class="Tablas">
          <?
		$get =@mysql_query("SELECT p.folio, p.estadopropuesta AS estado, 
		CONCAT_WS(' ', p.nombre, p.apaterno, p.amaterno) AS nombrec, p.tipo,
		DATE_FORMAT(p.fecha, '%d/%m/%Y') AS fecha, UCASE(SUBSTRING(p.tipoautorizacion,17)) AS tipoautorizacion,
		cs.prefijo
		FROM propuestaconvenio p
		LEFT JOIN catalogosucursal cs ON p.sucursal = cs.id
		$estadopropuesta limit $st,$pp",$link);	
		
		echo "SELECT p.folio, p.estadopropuesta AS estado, 
		CONCAT_WS(' ', p.nombre, p.apaterno, p.amaterno) AS nombrec, p.tipo,
		DATE_FORMAT(p.fecha, '%d/%m/%Y') AS fecha, UCASE(SUBSTRING(p.tipoautorizacion,17)) AS tipoautorizacion,
		cs.prefijo
		FROM propuestaconvenio p
		LEFT JOIN catalogosucursal cs ON p.sucursal = cs.id
		$estadopropuesta";
			while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="60" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[folio];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[folio];?></span></td>
            <td width="96" class="Tablas"><?=$row[fecha]; ?></td>
            <td width="51" class="Tablas"><?=$row[tipo]; ?></td>
            <td width="261" class="Tablas"><?=$row[nombrec]; ?></td>
            <td width="42" class="Tablas"><?=$row[tipoautorizacion]; ?></td>
            <td width="64" class="Tablas" align="center"><?=$row[prefijo]?></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="6" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarPropuestasPorEstado_prueba.php?funcion=$_GET[funcion]&pestado=$_GET[pestado]&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>