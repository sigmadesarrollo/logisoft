<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/	
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');	
	$get=@mysql_query("SELECT COUNT(guia) FROM 
	(SELECT gv.id AS guia, IFNULL(gv.total,0) AS importe, cd.descripcion AS destino, cs.descripcion AS origen,
	CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente, 
	CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario
	FROM guiasventanilla gv
	INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
	INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
	INNER JOIN catalogocliente re ON gv.idremitente = re.id
	INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
	WHERE gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado='ALMACEN DESTINO'
		and ISNULL(gv.factura) and gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]'
	UNION
	SELECT ge.id AS guia, IFNULL(ge.total,0) AS importe, cd.descripcion AS destino, cs.descripcion AS sucursalorigen,
	CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente, 
	CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario
	FROM guiasempresariales ge
	INNER JOIN catalogodestino cd ON ge.iddestino = cd.id
	INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
	INNER JOIN catalogocliente re ON ge.idremitente = re.id
	INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
	WHERE ge.tipoflete='POR COBRAR' AND ge.tipopago='CONTADO' and ge.estado='ALMACEN DESTINO'
		and ISNULL(ge.factura) and ge.idsucursaldestino = '$_SESSION[IDSUCURSAL]') t");
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
</head><body>
<form name="buscar" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="30%" class="FondoTabla">Guia</td>
      <td width="25%" class="FondoTabla">Fecha</td>
      <td width="45%" class="FondoTabla">Estado</td>
	  
    </tr>
    <tr>
      <td colspan="3" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?		 
		$get =@mysql_query('SELECT guia, fecha, estado FROM 
		(SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,"%d/%m/%Y") AS fecha, gv.estado
		FROM guiasventanilla gv
		WHERE gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado<>"ALMACEN ORIGEN" AND gv.estado<>"EN TRANSITO"
		AND gv.estado="ALMACEN DESTINO"
		and ISNULL(gv.factura) and gv.idsucursaldestino = "'.$_SESSION[IDSUCURSAL].'"
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,"%d/%m/%Y") AS fecha, ge.estado
		FROM guiasempresariales ge		
		WHERE ge.tipoflete="POR COBRAR" AND ge.tipopago="CONTADO" AND ge.estado<>"ALMACEN ORIGEN" AND ge.estado<>"EN TRANSITO"
		and ge.estado="ALMACEN DESTINO"
		and ISNULL(ge.factura) and ge.idsucursaldestino = "'.$_SESSION[IDSUCURSAL].'"
		) t 
		limit '.$st.','.$pp,$link);
		while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="143" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>
            <td width="117" class="Tablas"><?=$row[1]; ?></td>
			<td width="186" class="Tablas"><?=$row[2]; ?></td>
            <td width="32"></td>
          </tr>	
		<?	}
		
		?>      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarGuiasGenCAT.php?funcion=$_GET[funcion]&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>
<? //} ?>