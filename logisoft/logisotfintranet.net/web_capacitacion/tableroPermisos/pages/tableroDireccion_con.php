<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = "select ";
		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(*) FROM generacionconvenio 
		WHERE DATEDIFF(vigencia,CURDATE()) <= (SELECT diasvencimientoconvenio FROM configuradorgeneral)) conven";
		
		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(*) FROM guiasventanilla WHERE (factura IS NULL OR factura=0)) gpenfac";
		
		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(t1.id) FROM (
		SELECT gv.id FROM guiasventanilla AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		WHERE gv.estado = 'CANCELADO'
		GROUP BY gv.id
		) AS t1) gcan";	
		
		$ss = "SELECT CASE DATE_FORMAT(CURRENT_DATE,'%W')
			WHEN 'Monday' 	 THEN 'lunesrevision'
			WHEN 'Tuesday'   THEN 'martesrevision'
			WHEN 'Wednesday' THEN 'miercolesrevision'
			WHEN 'Thursday'  THEN 'juevesrevision'
			WHEN 'Friday' 	 THEN 'viernesrevision'
			WHEN 'Saturday'  THEN 'sabadorevision'
			ELSE 0
			END AS dia";
			$r = mysql_query($ss, $l) or die($s);
			$ff = mysql_fetch_object($r);
		
		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(*) FROM liquidacioncobranza lc
		INNER JOIN liquidacioncobranzadetalle d ON lc.folio = d.folioliquidacion
		INNER JOIN facturacion f ON d.factura=f.folio
		INNER JOIN solicitudcredito sc ON d.cliente = sc.cliente
		WHERE sc.".$ff->dia."=1 AND f.credito='SI' AND d.cobrar='NO') facpenrev";

		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT IFNULL(SUM(total),0) AS total FROM(
		SELECT IFNULL(COUNT(*),0) AS total FROM facturacion f
		INNER JOIN guiasventanilla gv ON f.folio=gv.factura
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
		LEFT JOIN liquidacioncobranzadetalle ld ON gv.id=ld.guia
		LEFT JOIN registrodecontrarecibos rc ON ld.folioliquidacion=rc.folioliquidacion
		WHERE (DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)))>120
		AND f.estado<>'CANCELADO' GROUP BY f.folio
		UNION
		SELECT IFNULL(COUNT(*),0) AS total FROM facturacion f
		INNER JOIN guiasempresariales gv ON f.folio=gv.factura
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
		LEFT JOIN liquidacioncobranzadetalle ld ON gv.id=ld.guia
		LEFT JOIN registrodecontrarecibos rc ON ld.folioliquidacion=rc.folioliquidacion
		WHERE (DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)))>120
		AND f.estado<>'CANCELADO' GROUP BY f.folio) t) cob120";
		
		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT SUM(total)  FROM (
		(SELECT COUNT(*) total FROM guiasventanilla WHERE estado <> 'ENTREGADA' AND
		ADDDATE(fecha, INTERVAL (IF(entregaocurre=0 OR ISNULL(entregaocurre),entregaocurre,24)/24) DAY) < CURRENT_DATE
		AND ocurre = 1)
		UNION
		(SELECT COUNT(*) total FROM guiasempresariales WHERE estado <> 'ENTREGADA' AND
		ADDDATE(fecha, INTERVAL (IF(entregaocurre=0 OR ISNULL(entregaocurre),entregaocurre,24)/24) DAY) < CURRENT_DATE
		AND ocurre = 1 )
		) AS t1) entatrasadas";

		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(*) FROM recoleccion 
		WHERE estado <> 'REPROGRAMADA' AND estado <> 'REALIZADO' AND estado <> 'CANCELADO'
		AND fecharegistro < CURRENT_DATE) recatrasadas";

		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT SUM(total) FROM (
		(SELECT COUNT(*) total FROM guiasventanilla WHERE estado = 'ALMACEN ORIGEN')
		UNION
		(SELECT COUNT(*) total FROM guiasempresariales WHERE estado = 'ALMACEN ORIGEN')
		) AS t1) galmacen";
		
		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(reportedanosfaltante.id) 
		FROM reportedanosfaltante
		INNER JOIN recepcionmercancia ON reportedanosfaltante.recepcion = recepcionmercancia.folio
		WHERE faltante = 1) gfaltante";

		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(reportedanosfaltante.id) 
		FROM reportedanosfaltante
		INNER JOIN recepcionmercancia ON reportedanosfaltante.recepcion = recepcionmercancia.folio
		WHERE dano = 1) gdano";

		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(*) FROM sobrantes) gsobrantes";

		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(*) FROM solicitudtelefonica WHERE estado = 'POR SOLUCIONAR') reclamasiones";

		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(*) FROM guiasempresariales WHERE tipoguia='CONSIGNACION'
		AND (factura IS NULL OR factura = 0)) gconsisinfact";

		$s .= ($s != "select ")?",":"";
		$s .= "(SELECT COUNT(*) FROM guiasempresariales WHERE valordeclarado IS NOT NULL 
		AND (texcedente > 0 OR texcedente IS NOT NULL)) gsobrepeso";
		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$principal = str_replace('null','""',json_encode($f));
		
		$ventas = "";
		
		$s = "SELECT 
		SUM(IF(tipoventa = 'GUIA VENTANILLA', IFNULL(total,0), 0)) AS gv,
		SUM(IF(tipoventa = 'GUIA EMPRESARIAL', IFNULL(total,0), 0)) AS ge
		FROM reportes_ventas
		WHERE activo = 'S'
		AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE)
		AND (tipoventa = 'GUIA VENTANILLA' OR tipoventa = 'GUIA EMPRESARIAL')";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$ventas = str_replace('null','""',json_encode($f));
		
		$credito = "";
		
		$s = "SELECT 
			SUM(cargo) AS cargo,
			SUM(abono) AS abono
			FROM reporte_cobranza4
			WHERE estado = 'ACTIVADO'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$credito = str_replace('null','""',json_encode($f));
		
		$ingreso = "";
		
		$s = "SELECT 
				SUM(IF(procedencia='G',total,0)) guia,
				SUM(IF(procedencia='M',total,0)) ead,
				SUM(IF(procedencia='F',total,0)) facturacion,
				SUM(IF(procedencia='A',total,0)) abono,
				SUM(IF(procedencia='C',total,0)) cobranza,
				SUM(IF(procedencia='O',total,0)) ocurre
			FROM formapago
			WHERE YEAR(fecha) = YEAR(CURRENT_DATE) AND ISNULL(fechacancelacion)";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$ingreso = str_replace('null','""',json_encode($f));
		
		echo "({principal:$principal, ventas:$ventas, credito:$credito, ingreso:$ingreso})";
		
	}else if($_GET[accion]==2){
		$s = "";
	}

?>