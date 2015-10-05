<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	
	
	$tipo=$_GET['tipo'];
	switch ($tipo) {
		case "manual":
			$get=@mysql_query("select count(*) from repartomercanciaead where tipo='MANUAL' and sucursal=$_SESSION[IDSUCURSAL]",$link);
			break;
		case "normal":
			$get=@mysql_query("select count(*) from repartomercanciaead where isNull(tipo) and sucursal=$_SESSION[IDSUCURSAL]",$link);
			break;
		case "ambos":
			$get=@mysql_query("select count(*) from repartomercanciaead where sucursal=$_SESSION[IDSUCURSAL]",$link);
			break;
		case "preliquidacion":
			$get=@mysql_query("select count(*) from repartomercanciaead 
			left join liquidacionead on repartomercanciaead.folio = liquidacionead.idreparto AND liquidacionead.sucursal = $_SESSION[IDSUCURSAL]
			where repartomercanciaead.sucursal=$_SESSION[IDSUCURSAL] and isnull(liquidacionead.id)",$link);
			break;
	}
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
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="14%" class="FondoTabla">Folio <input type="hidden" name="ands" value="<?=$losands?>"></td>
      <td width="21%" class="FondoTabla">Fecha</td>
      <td width="65%" class="FondoTabla">Unidad</td>
    </tr>
    <tr>
      <td colspan="3" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
		  switch ($tipo) {
				case "manual":
					$get =@mysql_query("SELECT rm.folio, DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecha, cu.numeroeconomico unidad
					FROM repartomercanciaead rm
					INNER JOIN catalogounidad cu ON rm.unidad = cu.id
					WHERE rm.tipo='MANUAL'  AND rm.sucursal='$_SESSION[IDSUCURSAL]' 
					order by folio
					limit ".$st.','.$pp,$link);
					break;
				case "normal":
					$get =@mysql_query("SELECT rm.folio, DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecha, cu.numeroeconomico unidad
					FROM repartomercanciaead rm
					INNER JOIN catalogounidad cu ON rm.unidad = cu.id
					WHERE isnull(rm.tipo)  AND rm.sucursal='$_SESSION[IDSUCURSAL]'
					order by folio
					limit ".$st.','.$pp,$link);
					break;
				case "ambos":
					$s = "SELECT rm.folio, DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecha, cu.numeroeconomico unidad
					FROM repartomercanciaead rm
					INNER JOIN catalogounidad cu ON rm.unidad = cu.id
					WHERE rm.sucursal='$_SESSION[IDSUCURSAL]' limit ".$st.",".$pp;
					$get = mysql_query($s,$link) or die($s);
					break;
				case "preliquidacion":
					$get=@mysql_query("select repartomercanciaead.folio, date_format(repartomercanciaead.fecha,'%d/%m/%Y') as fecha
					from repartomercanciaead 
					left join liquidacionead on repartomercanciaead.folio = liquidacionead.idreparto AND liquidacionead.sucursal = $_SESSION[IDSUCURSAL]
					where repartomercanciaead.sucursal=$_SESSION[IDSUCURSAL] and isnull(liquidacionead.folio)",$link);
					break;
			}
			while($row = mysql_fetch_array($get)){
				
			?>
				<tr >
       <td width="71" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="103" class="Tablas"><?=$row[1]; ?></td>
            <td width="266" class="Tablas"><?=$row[2]; ?></td>
            <td width="56"></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioRepartos.php?funcion=$_GET[funcion]&tipo=".$tipo."&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>