<?
	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$paginado = 30;
	$contador = ($_GET[contador]!="")?$_GET[contador]:0;
	$desde	  = ($paginado*$contador);
	$limite = " limit $desde, $paginado ";
	
	function f_adelante($vdesde,$vpaginado,$total){
		if($vdesde+$vpaginado>($total-1))
			return false;
		else
			return true;
	}
	function f_atras($vdesde){
		if($vdesde==0)
			return false;
		else
			return true;
	}
	function f_paginado($vpaginado,$vtotal){
		if($vpaginado>=$vtotal)
			return false;
		else
			return true;
	}
	
	if($_GET[accion]==1){
		/*total de registros*/
		$s = "SELECT prefijo FROM(
		SELECT ge.idsucursalorigen AS prefijo FROM guiasempresariales ge 
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) GROUP BY ge.idsucursalorigen
		UNION
		SELECT gv.idsucursalorigen AS prefijo FROM guiasventanilla gv 
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' GROUP BY gv.idsucursalorigen
		UNION
		SELECT f.idsucursal AS prefijo FROM facturacion f WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) GROUP BY f.idsucursal
		UNION
		SELECT cs.id FROM solicitudcredito sc INNER JOIN catalogosucursal cs ON sc.idsucursal=cs.id GROUP BY cs.id
		)t1 WHERE NOT ISNULL(prefijo) GROUP BY prefijo";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT SUM(clientes) clientes,FORMAT(SUM(carteravigente),2) carteravigente,
		FORMAT(SUM(carteramorosa),2) carteramorosa,FORMAT(SUM(carteratotal),2) carteratotal
		FROM(
		SELECT 0 AS clientes,SUM(IF(ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,ge.total,0)) carteravigente,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,ge.total,0)) carteramorosa,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,ge.total,0)) +
		SUM(IF(ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,ge.total,0)) carteratotal
		FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON ge.idremitente=sc.cliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) AND ISNULL(ge.factura) 
		UNION
		SELECT 0 AS clientes,SUM(IF(ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,gv.total,0)) carteravigente,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,gv.total,0)) carteramorosa,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,gv.total,0)) +
		SUM(IF(ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,gv.total,0)) carteratotal
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON gv.idremitente=sc.cliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' 
		UNION
		SELECT 0 AS clientes,SUM(IF(ADDDATE(f.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,f.total,0)) carteravigente,
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,f.total,0)) carteramorosa,
		SUM(IF(ADDDATE(f.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,f.total,0)) +
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,f.total,0)) carteratotal
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		LEFT JOIN solicitudcredito sc ON f.cliente=sc.cliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) 
		UNION
		SELECT COUNT(DISTINCT sc.cliente) AS clientes,0 AS carteravigente,0 AS carteramorosa,0 AS carteratotal
		FROM solicitudcredito sc INNER JOIN catalogosucursal cs ON sc.idsucursal=cs.id
		)t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT id,prefijo AS sucursal,SUM(clientes) clientes,FORMAT(SUM(carteravigente),2) carteravigente,
		FORMAT(SUM(carteramorosa),2) carteramorosa,FORMAT(SUM(carteratotal),2) carteratotal
		FROM(
		SELECT cs.id,cs.prefijo,0 AS clientes,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,ge.total,0)) carteravigente,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,ge.total,0)) carteramorosa,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,ge.total,0)) +
		SUM(IF(ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,ge.total,0)) carteratotal
		FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON ge.idremitente=sc.cliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) GROUP BY ge.idsucursalorigen
		UNION
		SELECT cs.id,cs.prefijo,0 AS clientes,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,gv.total,0)) carteravigente,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,gv.total,0)) carteramorosa,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,gv.total,0)) +
		SUM(IF(ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,gv.total,0)) carteratotal
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON gv.idremitente=sc.cliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' GROUP BY gv.idsucursalorigen
		UNION
		SELECT cs.id,cs.prefijo,0 AS clientes,
		SUM(IF(ADDDATE(f.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,f.total,0)) carteravigente,
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,f.total,0)) carteramorosa,
		SUM(IF(ADDDATE(f.fecha,INTERVAL sc.diascredito DAY)>=CURRENT_DATE,f.total,0)) +
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY)<CURRENT_DATE,f.total,0)) carteratotal
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		LEFT JOIN solicitudcredito sc ON f.cliente=sc.cliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) GROUP BY f.idsucursal
		UNION
		SELECT cs.id,cs.prefijo, COUNT(DISTINCT sc.cliente) AS clientes,0 AS carteravigente,0 AS carteramorosa,0 AS carteratotal
		FROM solicitudcredito sc
		INNER JOIN catalogosucursal cs ON sc.idsucursal=cs.id GROUP BY sc.idsucursal
		)t1 GROUP BY prefijo ORDER BY prefijo $limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$ar[] = $f;
		}
		$registros = json_encode($ar);
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	}
	
	if($_GET[accion]==2){
		/*total de registros*/
		$s = "SELECT cliente FROM solicitudcredito WHERE idsucursal=".$_GET[prefijosucursal]." GROUP BY cliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = '""';
		
		/*registros*/
		$s = "SELECT cliente AS idcliente,CONCAT(nombre,' ',paterno,' ',materno,' ') cliente, montoautorizado,diascredito,
		CONCAT(IF(semanarevision=1,'TODOS',''),IF(lunesrevision=1,'L',''),IF(martesrevision=1,'MA',''),IF(miercolesrevision=1,'MI',''),
		IF(juevesrevision=1,'J',''),IF(viernesrevision=1,'V','')) fecharevision,
		CONCAT(IF(semanapago=1,'TODOS',''),IF(lunespago=1,'L',''),IF(martespago=1,'MA',''),IF(miercolespago=1,'MI',''),
		IF(juevespago=1,'J',''),IF(viernespago=1,'V','')) fechapago,0 AS rotacioncobranza
		FROM solicitudcredito WHERE idsucursal=".$_GET[prefijosucursal]." GROUP BY cliente $limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$ar[] = $f;
		}
		$registros = json_encode($ar);
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	}
	
	if($_GET[accion]==3){
		/*total de registros*/
		$s = "SELECT sc.folio FROM solicitudcredito sc INNER JOIN catalogoempleado ce ON sc.idusuario=ce.id	WHERE sc.cliente='$_GET[idcliente]'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = '""';
		
		/*registros*/
		$s = "SELECT DATE_FORMAT(sc.fechaactivacion, '%d/%m/%Y') AS fecha,sc.montoautorizado,
		CONCAT(ce.nombre,' ',ce.apellidopaterno,' ',ce.apellidomaterno) usuario,sc.folio AS solicitud
		FROM solicitudcredito sc INNER JOIN catalogoempleado ce ON sc.idusuario=ce.id WHERE sc.cliente='$_GET[idcliente]' $limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$ar[] = $f;
		}
		$registros = json_encode($ar);
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	}
	
	if($_GET[accion]==4){
		/*total de registros*/
		$s = "SELECT ge.id,sc.folio FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON ge.idremitente=sc.cliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND ge.idsucursalorigen='$_GET[sucursalprefijo]'
		UNION
		SELECT gv.id,sc.folio FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON gv.idremitente=sc.cliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND idsucursalorigen='$_GET[sucursalprefijo]'
		UNION
		SELECT f.folio AS id,sc.folio FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		LEFT JOIN solicitudcredito sc ON f.cliente=sc.cliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT FORMAT(SUM(vencido),2) AS vencido,FORMAT(SUM(alcorriente),2) AS alcorriente,FORMAT(SUM(total),2) AS total
		FROM(
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>0,ge.total,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))=0,ge.total,0)) AS alcorriente,SUM(ge.total) total
		FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON ge.idremitente=sc.cliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND ge.idsucursalorigen='$_GET[sucursalprefijo]'
		UNION
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>0,gv.total,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))=0,gv.total,0)) AS alcorriente,SUM(gv.total) total
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON gv.idremitente=sc.cliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND idsucursalorigen='$_GET[sucursalprefijo]'
		UNION
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>0,f.total,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))<=0,f.total,0)) AS alcorriente, SUM(f.total) total
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		LEFT JOIN solicitudcredito sc ON f.cliente=sc.cliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]')t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT cs.prefijo AS prefijosucursal,IFNULL(CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno),'') AS cliente,
		IFNULL(sc.folio,'') AS folio,ge.fecha,IFNULL(ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))<=0,ge.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))<16,ge.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))<31,ge.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL sc.diascredito DAY))<61,ge.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>60,ge.total,0) AS may60dias,
		ge.total AS saldo,0 AS factura,IFNULL(ge.acuserecibo,0)AS contrarecibo, ge.id FROM guiasempresariales ge 
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON ge.idremitente=sc.cliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND ge.idsucursalorigen='$_GET[sucursalprefijo]'
		UNION
		SELECT cs.prefijo AS prefijosucursal,IFNULL(CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno),'') AS cliente,
		IFNULL(sc.folio,'') AS folio,gv.fecha,IFNULL(ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))<=0,gv.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))<16,gv.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))<31,gv.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL sc.diascredito DAY))<61,gv.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>60,gv.total,0) AS may60dias,
		gv.total AS saldo,0 AS factura,IFNULL(gv.acuserecibo,0)AS contrarecibo,gv.id FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		LEFT JOIN solicitudcredito sc ON gv.idremitente=sc.cliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND idsucursalorigen='$_GET[sucursalprefijo]'
		UNION
		SELECT cs.prefijo AS prefijosucursal,IFNULL(CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno),'') AS cliente,
		IFNULL(sc.folio,'') AS folio,f.fecha,IFNULL(ADDDATE(f.fecha,INTERVAL sc.diascredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,0) DAY))<=0,f.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))<16,f.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))<31,f.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL sc.diascredito DAY))<61,f.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(sc.diascredito,1) DAY))>60,f.total,0) AS may60dias,
		f.total AS saldo,IFNULL(f.folio,'')AS factura,0 AS contrarecibo, f.folio AS id FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		LEFT JOIN solicitudcredito sc ON f.cliente=sc.cliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]' $limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$ar[] = $f;
		}
		$registros = json_encode($ar);
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	}
	
	if($_GET[accion]==5){
		
		/* proceso para llenar la temporal */
		$f1 = split("/",$_GET[fecha1]);
		$f2 = split("/",$_GET[fecha2]);
		$fecha1 = $f1[2]."-".$f1[1]."-".$f1[0];
		$fecha2 = $f2[2]."-".$f2[1]."-".$f2[0];
	
		/*total de registros*/	/* cargos */
		$s = "SELECT ge.id FROM guiasempresariales ge INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND ge.idremitente=$_GET[idcliente] AND ge.idsucursalorigen='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		UNION
		SELECT gv.id FROM guiasventanilla gv INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND 
		gv.idremitente=$_GET[idcliente] AND gv.idsucursalorigen='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION
		SELECT f.folio AS id FROM facturacion f INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION	/* abonos */
		SELECT fp.guia AS id FROM formapago fp INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT FORMAT(SUM(IFNULL(cargo,'')),2) cargos,FORMAT(SUM(IFNULL(abono,'')),2) abonos
		FROM(	/* cargos */
		SELECT ge.total AS cargo,0 AS abono FROM guiasempresariales ge INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND ge.idremitente=$_GET[idcliente] AND ge.idsucursalorigen='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		UNION
		SELECT gv.total AS cargo,0 AS abono FROM guiasventanilla gv INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND 
		gv.idremitente=$_GET[idcliente] AND gv.idsucursalorigen='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION
		SELECT f.total AS cargo,0 AS abono FROM facturacion f INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION	/* abonos */
		SELECT 0 AS cargo,fp.total AS abono FROM formapago fp INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2')t";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT fecha,sucursal,IFNULL(refcargo,'') AS referenciacargo,IFNULL(refabono,'') AS referenciaabono,cargos,abonos,saldo,descripcion	
		FROM(	/* cargos */
		SELECT ge.fecha,cs.prefijo AS sucursal,ge.id AS refcargo,0 AS refabono,ge.total AS cargos,0 AS abonos,ge.total AS saldo,'' AS descripcion
		FROM guiasempresariales ge INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND ge.idremitente=$_GET[idcliente] AND ge.idsucursalorigen='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		UNION
		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS refcargo,0 AS refabono,gv.total AS cargos,0 AS abonos,gv.total AS saldo,'' AS descripcion
		FROM guiasventanilla gv	INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND 
		gv.idremitente=$_GET[idcliente] AND gv.idsucursalorigen='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,f.folio AS refcargo,0 AS refabono,f.total AS cargos,0 AS abonos,f.total AS saldo,'' AS descripcion
		FROM facturacion f INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION	/* abonos */
		SELECT fp.fecha,cs.prefijo AS sucursal,0 AS refcargo,fp.guia AS refabono,0 AS cargos,fp.total AS abonos,fp.total AS saldo,
		CONCAT(IF(fp.efectivo>0,'EFECTIVO, ',''),IF(fp.tarjeta>0,'TARJETA, ',''),IF(fp.transferencia>0,'TRANSFERENCIA, ',''),
		IF(fp.cheque>0,CONCAT('CHEQUE ',IFNULL(fp.ncheque,'')),'')) AS descripcion
		FROM formapago fp INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2')t1 ORDER BY fecha $limite";
		$r = mysql_query($s,$l) or die($s."\n".mysql_error($l));
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$ar[] = $f;
		}
		$registros = json_encode($ar);
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';	
	}
?>