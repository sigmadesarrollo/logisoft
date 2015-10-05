<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/	
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');	
	$get=@mysql_query("SELECT COUNT(guia)  FROM 
	(SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, gv.estado
	FROM guiasventanilla gv
	INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
	INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
	INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
	WHERE gv.condicionpago=1 
	AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = gv.id) AND isnull(gv.factura)
	AND ((gv.tipoflete=0 AND gv.idsucursalorigen = '$_SESSION[IDSUCURSAL]' and gv.fecha=current_Date) OR 
	(gv.tipoflete=1 AND (gv.estado='ALMACEN DESTINO' or gv.estado='ENTREGADA') AND gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]'))
	UNION
	SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, ge.estado
	FROM guiasempresariales ge
	INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
	INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
	INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
	WHERE ge.tipopago='CREDITO' 
	and tipoguia = 'CONSIGNACION' AND isnull(ge.factura)
	AND ((ge.tipoflete='PAGADA' AND ge.idsucursalorigen = '.$_SESSION[IDSUCURSAL].' and ge.fecha=current_Date) OR 
	(ge.tipoflete<>'PAGADA' AND (ge.estado='ALMACEN DESTINO' OR ge.estado='ENTREGADA') AND ge.idsucursaldestino = '.$_SESSION[IDSUCURSAL].'))
	AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = ge.id))
	AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = ge.id)) t");
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
<table width="700"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="16%" class="FondoTabla">Guia</td>
      <td width="13%" class="FondoTabla">Fecha</td>
      <td width="18%" class="FondoTabla">Estado</td>
	  
      <td width="19%" class="FondoTabla">Cliente</td>
      <td width="14%" class="FondoTabla">Importe</td>
      <td width="8%" class="FondoTabla">Origen</td>
      <td width="12%" class="FondoTabla">Destino</td>
    </tr>
    <tr>
      <td colspan="7" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
				 
		$s = 'SELECT guia, fecha, estado, cliente, total, origen, destino FROM( 
		SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,"%d/%m/%Y") AS fecha, gv.estado,
		CONCAT_WS(" ",cc.nombre,cc.paterno,cc.materno) AS cliente, 
		gv.total, ori.prefijo AS origen, d.prefijo AS destino
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		WHERE gv.condicionpago=1 AND gv.estado<>"ALMACEN ORIGEN" AND gv.estado<>"EN TRANSITO" 
		AND gv.estado<>"CANCELADO" AND gv.estado<>"AUTORIZACION PARA SUSTITUIR"
		AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = gv.id) AND isnull(gv.factura)
		AND ((gv.tipoflete=0 AND gv.idsucursalorigen = '.$_SESSION[IDSUCURSAL].' and gv.fecha=current_Date) OR 
		(gv.tipoflete=1 AND gv.idsucursaldestino = '.$_SESSION[IDSUCURSAL].'))
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,"%d/%m/%Y") AS fecha, ge.estado,
		CONCAT_WS(" ",cc.nombre,cc.paterno,cc.materno) AS cliente,
		ge.total, ori.prefijo AS origen, d.prefijo AS destino
		FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		WHERE ge.tipopago="CREDITO" AND ge.estado<>"ALMACEN ORIGEN" AND ge.estado<>"EN TRANSITO" and tipoguia = "CONSIGNACION"
        AND isnull(ge.factura)AND ((ge.tipoflete="PAGADA" AND ge.idsucursalorigen = '.$_SESSION[IDSUCURSAL].' and ge.fecha=current_Date) OR 
		(ge.tipoflete<>"PAGADA" AND ge.idsucursaldestino = '.$_SESSION[IDSUCURSAL].'))
		AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = ge.id)) t limit '.$st.','.$pp;
		$get =@mysql_query($s,$link);
		while($row=@mysql_fetch_array($get)){
			?>
				<tr >
       <td width="110" class="Tablas" >
<span onClick="parent.<?=$_GET[funcion]?>('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">
<input name="fecha" type="text" class="Tablas" value="<?=$row[0]; ?>" style="width:100px; border:none; color:#33F; cursor:pointer" readonly="true"></span></td>
            <td width="90" class="Tablas"><input name="fecha" class="Tablas" type="text" value="<?=$row[1]; ?>" style="width:80px; border:none" readonly="true"></td>
			<td width="127" class="Tablas"><input name="estado" class="Tablas" type="text" style="border:none" value="<?=$row[2]; ?>" readonly="true"></td>
            <td width="134" class="Tablas"><input name="cliente" type="text" class="Tablas" style="border:none" id="cliente" value="<?=$row[3]; ?>" readonly="true"></td>
            <td width="97" class="Tablas"><input name="importe" type="text" style="width:90px; border:none" class="Tablas" id="importe" value="<?="$ ".number_format($row[4],2,'.',''); ?>" readonly="true"></td>
            <td width="55" class="Tablas"><input name="ori" class="Tablas" style="width:50px; border:none" type="text" value="<?=$row[5]; ?>" readonly="true"></td>
            <td width="74" class="Tablas"><input name="destino" type="text" class="Tablas" id="destino" style="width:50px; border:none" value="<?=$row[6]; ?>" readonly="true"></td>
            <td width="9"></td>
          </tr>	
		<?	}  ?>      </table></td>
    </tr>
    <tr>
      <td colspan="7" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarGuiasTraspaso.php?funcion=$_GET[funcion]&st="); ?></font></td>
    </tr>
  </table> 
</form>
</body>
</html>