<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	$s = "SELECT SUM(total) FROM(
	(SELECT COUNT(DISTINCT(unidad)) AS total FROM guiaventanilla_unidades WHERE ubicacion = $_SESSION[IDSUCURSAL]
	AND NOT ISNULL(unidad) AND unidad<>'' and proceso='ENRUTADA')
	UNION
	(SELECT COUNT(DISTINCT(unidad)) AS total FROM guiasempresariales_unidades WHERE ubicacion = $_SESSION[IDSUCURSAL]
	AND NOT ISNULL(unidad) AND unidad<>'' and proceso='ENRUTADA')
	) AS t1";
	$get=@mysql_query($s,$link) or die($s);

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
      <td width="7%" class="FondoTabla">Id <input type="hidden" name="ands" value="<?=$losands?>"></td>
      <td width="85%" class="FondoTabla">Unidad</td>
    </tr>
    <tr>
      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
			$s = "SELECT id, unidad FROM(
			(SELECT catalogounidad.id, unidad 
			 FROM guiaventanilla_unidades 
			 INNER JOIN catalogounidad ON guiaventanilla_unidades.unidad = catalogounidad.numeroeconomico
			 WHERE guiaventanilla_unidades.ubicacion = $_SESSION[IDSUCURSAL]
			AND NOT ISNULL(unidad) AND unidad<>'' and proceso='ENRUTADA')
			UNION
			(SELECT  catalogounidad.id, unidad  
			 FROM guiasempresariales_unidades 
			 INNER JOIN catalogounidad ON guiasempresariales_unidades.unidad = catalogounidad.numeroeconomico
			 WHERE guiasempresariales_unidades.ubicacion = $_SESSION[IDSUCURSAL]
			AND NOT ISNULL(unidad) AND unidad<>'' and proceso='ENRUTADA')
			) t1 GROUP BY unidad";
			$get=@mysql_query($s,$link) or die($s);
			while($row = mysql_fetch_array($get)){
				
			?>
				<tr >
       <td width="10%" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>','<?=$row[1];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="79%" class="Tablas"><?=$row[1];?></td>
            <td width="19px"></td>
          </tr>	
		<?	}
		
		?>

      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioRepartos.php?funcion=$_GET[funcion]&tipo=".$tipo."&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>