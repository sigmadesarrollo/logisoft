<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');	
	$s = "SELECT SUM(total) FROM (
		(SELECT COUNT(*) AS total FROM guiasventanilla gv
		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
		WHERE estado='ALMACEN DESTINO' AND gv.ocurre=".$_GET[tipo]." 
		AND gv.idsucursaldestino='$_SESSION[IDSUCURSAL]' 
		AND cd.restringiread=0
		AND (gv.entradasalida = '' OR gv.entradasalida = 'ENTRADA' OR isnull(gv.entradasalida)))
		UNION
		(SELECT COUNT(*) AS total FROM guiasempresariales ge
		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id
		WHERE ge.estado='ALMACEN DESTINO' AND ge.ocurre=".$_GET[tipo]." 
		AND ge.idsucursaldestino='$_SESSION[IDSUCURSAL]'
		AND cd.restringiread=0
		AND (ge.entradasalida = '' OR ge.entradasalida = 'ENTRADA' OR isnull(ge.entradasalida)))
	) AS t1";
	$get = @mysql_query($s,$link);
	
	$total = @mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<script src="select.js"></script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr class="FondoTabla">
    <td width="24%">Folio</td>
    <td width="76%">Descripción</td>
  </tr>
<tr>
    <td colspan="2" height="300px" valign="top">
      <table width="100%" border="0" align="center">
        <?	
		$s = "select * from (
		(SELECT gv.id, gv.fecha FROM guiasventanilla gv
		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
		WHERE gv.estado='ALMACEN DESTINO' AND gv.ocurre='$_GET[tipo]'
		AND gv.idsucursaldestino='$_SESSION[IDSUCURSAL]'
		AND cd.restringiread=0
		AND (gv.entradasalida = '' OR gv.entradasalida = 'ENTRADA' OR isnull(gv.entradasalida))) 
		UNION
		(SELECT ge.id, ge.fecha FROM guiasempresariales ge
		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id 
		WHERE ge.estado='ALMACEN DESTINO' AND ge.ocurre='$_GET[tipo]' 
		AND ge.idsucursaldestino='$_SESSION[IDSUCURSAL]'
		AND cd.restringiread=0
		AND (ge.entradasalida = '' OR ge.entradasalida = 'ENTRADA' OR isnull(ge.entradasalida)))) as t1
		limit ".$st.",".$pp;
		$get = mysql_query($s,$link);
		if($_GET[guias]=="")
			$_GET[guias] = "_";
		while($row=@mysql_fetch_array($get)){	
			$varEncon = "-".strrpos( $row[0], $_GET[guias])."-";
			if($varEncon == "--"){
	  ?>
        <tr >
          <td width="118" class="Tablas" ><span onClick="window.parent.obtenerIdGuia('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">
            <?=$row['0'];?>
          </span></td>
          <td width="313" class="Tablas"><?=$row['1'];?></td>
          <td width="51"></td>
        </tr>
       <?  }
		} ?>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "traspasodemercanciaentrealmacenes_buscar.php?tipo=$_GET[tipo]&st="); ?></font></td>
  </tr>
</table>
</body>
</html>
