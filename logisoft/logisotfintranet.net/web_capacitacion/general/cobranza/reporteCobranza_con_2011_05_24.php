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
		$x = rand(1,1000); 	
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";
		mysql_query($s,$l) or die($s);
		$s = "DROP TABLE IF EXISTS tmp_clientes$x";
		mysql_query($s,$l) or die($s);
		/* tabla de convenios */
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL
		FROM generacionconvenio GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio
		SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		/* tabla de clientes */
		$s = "CREATE TABLE `tmp_clientes$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`idcliente` DOUBLE DEFAULT NULL,
		`sucursal` DOUBLE DEFAULT NULL,
		`dcredito` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los clientes en la temporal
		$s = "INSERT INTO tmp_clientes$x
		SELECT NULL,idremitente AS idcliente,0 AS sucursal,0 AS dcredito 
		FROM guiasempresariales 
		WHERE tipopago='CREDITO' AND (tipoguia='CONSIGNACION' OR (tipoguia='PREPAGADA' AND (texcedente>0 OR tseguro>0))) 
		AND ISNULL(factura) GROUP BY idremitente
		UNION 
		SELECT NULL,pg.cliente,0 AS sucursal,0 AS dcredito FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' GROUP BY pg.cliente
		UNION
		SELECT NULL,f.cliente,0 AS sucursal,0 AS dcredito FROM facturacion f WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND 
		f.estadocobranza <> 'C' GROUP BY f.cliente;";
		mysql_query($s,$l) or die($s); 
		//agregar datos a la temporal
		$s = "UPDATE tmp_clientes$x temp INNER JOIN solicitudcredito sc ON temp.idcliente=sc.cliente
		SET dcredito=sc.diascredito, sucursal=sc.idsucursal";
		mysql_query($s,$l) or die($s); 
				
		/*total de registros*/
		$s = "SELECT id FROM(
		SELECT ge.idsucursalorigen AS id FROM guiasempresariales ge 
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) GROUP BY ge.idsucursalorigen
		UNION
		SELECT gv.idsucursalorigen AS id FROM guiasventanilla gv 
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' GROUP BY gv.idsucursalorigen
		UNION
		SELECT f.idsucursal AS id FROM facturacion f WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) GROUP BY f.idsucursal
		UNION
		SELECT sucursal AS id FROM tmp_clientes$x GROUP BY sucursal
		)t1 WHERE NOT ISNULL(id) GROUP BY id";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
		/*totales de los registros*/
		$s = "SELECT SUM(clientes) clientes,FORMAT(SUM(carteravigente),2) carteravigente,
		FORMAT(SUM(carteramorosa),2) carteramorosa,FORMAT(SUM(carteravigente) + SUM(carteramorosa),2) carteratotal
		FROM(
		SELECT 0 AS clientes,SUM(IF(ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,$if,0)) carteravigente,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,$if,0)) carteramorosa
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON tc.idcliente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura)
		UNION
		SELECT 0 AS clientes,SUM(IF(ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,gv.total,0)) carteravigente,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,gv.total,0)) carteramorosa
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON  pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON pg.cliente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO'
		UNION
		SELECT 0 AS clientes,SUM(IF(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) carteravigente,
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) carteramorosa
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial'
		UNION
		SELECT 0 AS clientes,SUM(IF(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,IFNULL(f.total,0),0)) carteravigente,
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,IFNULL(f.total,0),0)) carteramorosa
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial'
		UNION
		SELECT COUNT(DISTINCT temp.idcliente) AS clientes,0 AS carteravigente,0 AS carteramorosa
		FROM tmp_clientes$x temp INNER JOIN catalogosucursal cs ON temp.sucursal=cs.id
		)t1";
		$r = mysql_query($s,$l) or die(mysql_error($l));
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT id,prefijo AS sucursal,SUM(clientes) clientes,SUM(carteravigente) carteravigente,
		SUM(carteramorosa) carteramorosa,(SUM(carteravigente)+ SUM(carteramorosa)) carteratotal
		FROM(
		SELECT cs.id,cs.prefijo,0 AS clientes,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,$if,0)) carteravigente,
		SUM(IF(ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,$if,0)) carteramorosa
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON tc.idcliente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) GROUP BY tc.idsucursal
		UNION
		SELECT cs.id,cs.prefijo,0 AS clientes,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,gv.total,0)) carteravigente,
		SUM(IF(ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,gv.total,0)) carteramorosa
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON pg.cliente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO'
		GROUP BY pg.sucursalacobrar
		UNION
		SELECT cs.id,cs.prefijo,0 AS clientes,
		SUM(IF(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) +
		IFNULL(f.otrosmontofacturar,0)),0)) carteravigente,
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) carteramorosa
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial'
		GROUP BY f.idsucursal
		UNION
		SELECT cs.id,cs.prefijo,0 AS clientes,
		SUM(IF(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)>=CURRENT_DATE,IFNULL(f.total,0),0)) carteravigente,
		SUM(IF(ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY)<CURRENT_DATE,IFNULL(f.total,0),0)) carteramorosa
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial'
		GROUP BY f.idsucursal
		UNION
		SELECT cs.id,cs.prefijo, COUNT(DISTINCT temp.idcliente) AS clientes,0 AS carteravigente,0 AS carteramorosa
		FROM tmp_clientes$x temp INNER JOIN catalogosucursal cs ON temp.sucursal=cs.id GROUP BY temp.sucursal
		)t1 GROUP BY prefijo ORDER BY prefijo $limite";
		$r = mysql_query($s,$l) or die(mysql_error($l));
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
		
		$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s); 
		$s = "DROP TABLE tmp_clientes$x";
			mysql_query($s,$l) or die($s); 
	}
	
	if($_GET[accion]==2){
		/*total de registros*/
		$s = "SELECT cliente 
		FROM solicitudcredito 
		WHERE idsucursal=".$_GET[prefijosucursal]." 
		GROUP BY cliente";
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
		FROM solicitudcredito 
		WHERE idsucursal=".$_GET[prefijosucursal]." 
		GROUP BY cliente $limite";
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
		$s = "SELECT sc.folio 
		FROM solicitudcredito sc 
		INNER JOIN catalogoempleado ce ON sc.idusuario=ce.id	
		WHERE sc.cliente='$_GET[idcliente]'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = '""';
		
		/*registros*/
		$s = "SELECT DATE_FORMAT(sc.fechaactivacion, '%d/%m/%Y') AS fecha,sc.montoautorizado,
		CONCAT(ce.nombre,' ',ce.apellidopaterno,' ',ce.apellidomaterno) usuario,sc.folio AS solicitud
		FROM solicitudcredito sc 
		INNER JOIN catalogoempleado ce ON sc.idusuario=ce.id 
		WHERE sc.cliente='$_GET[idcliente]' $limite";
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
	
	if($_GET[accion]==4){ //Antiguedad de saldos
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";mysql_query($s,$l) or die($s);
		$s = "DROP TABLE IF EXISTS tmp_clientes$x";mysql_query($s,$l) or die($s);
		/* tabla de convenios */
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL
		FROM generacionconvenio GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		/* tabla de clientes */
		$s = "CREATE TABLE `tmp_clientes$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`nfolio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`ncliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`sucursal` DOUBLE DEFAULT NULL,
		`dcredito` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los clientes en la temporal
		$s = "INSERT INTO tmp_clientes$x
		SELECT NULL,0 AS nfolio,ge.idremitente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM guiasempresariales ge 
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) GROUP BY ge.idremitente
		UNION 
		SELECT NULL,0 AS nfolio,pg.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' GROUP BY pg.cliente
		UNION
		SELECT NULL,0 AS nfolio,f.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM facturacion f WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' GROUP BY f.cliente;";
		mysql_query($s,$l) or die($s); 
		//agregar datos a la temporal
		$s = "UPDATE tmp_clientes$x temp INNER JOIN solicitudcredito sc ON temp.idcliente=sc.cliente
		SET dcredito=sc.diascredito,sucursal=sc.idsucursal,nfolio=sc.folio,ncliente=CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno)";
		mysql_query($s,$l) or die($s); 
		
		/*total de registros*/
		$s = "SELECT ge.id,temp.nfolio FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON tc.idcliente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idsucursal='$_GET[sucursalprefijo]'
		UNION
		SELECT gv.id,temp.nfolio FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON pg.cliente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.sucursalacobrar='$_GET[sucursalprefijo]'
		UNION
		SELECT f.folio AS id,temp.nfolio FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]' AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial'
		UNION
		SELECT fd.folio AS id,temp.nfolio FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN guiasventanilla gv ON fd.folio=gv.id
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]' AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial' 
		GROUP BY fd.folio ";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
		/*totales de los registros*/
		$s = "SELECT FORMAT(SUM(vencido),2) AS vencido,FORMAT(SUM(alcorriente),2) AS alcorriente,FORMAT(SUM(total),2) AS total
		FROM(
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>0,$if,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,$if,0)) AS alcorriente,SUM($if) total
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON tc.idcliente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idsucursal='$_GET[sucursalprefijo]'
		UNION
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>0,gv.total,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,gv.total,0)) AS alcorriente,SUM(gv.total) total
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON pg.cliente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.sucursalacobrar='$_GET[sucursalprefijo]'
		UNION
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>0,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) AS alcorriente,SUM(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) total
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]' AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial'
		UNION
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>0,fd.total,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,fd.total,0)) AS alcorriente, SUM(fd.total) total
		FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN guiasventanilla gv ON fd.folio=gv.id
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]' AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial' )t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,
		ge.id AS folio,ge.fecha,IFNULL(ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,$if,0)) AS alcorriente,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<16,$if,0)) AS c1a15dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<31,$if,0)) AS c16a30dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<61,$if,0)) AS c31a60dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,$if,0)) AS may60dias,
		SUM($if) AS saldo,0 AS factura,IFNULL(ge.acuserecibo,0)AS contrarecibo FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON tc.idcliente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idsucursal='$_GET[sucursalprefijo]' GROUP BY ge.id
		UNION
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,
		gv.id AS folio,gv.fecha,IFNULL(ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,gv.total,0)) AS alcorriente,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<16,gv.total,0)) AS c1a15dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<31,gv.total,0)) AS c16a30dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<61,gv.total,0)) AS c31a60dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,gv.total,0)) AS may60dias,
		SUM(gv.total) AS saldo,0 AS factura,IFNULL(gv.acuserecibo,0)AS contrarecibo FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON pg.cliente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.sucursalacobrar='$_GET[sucursalprefijo]' GROUP BY gv.id
		UNION
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,f.folio,f.fecha,IFNULL(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) AS alcorriente,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<16,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) AS c1a15dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<31,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) AS c16a30dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<61,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) AS c31a60dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
		IFNULL(f.otrosmontofacturar,0)),0)) AS may60dias,
		SUM(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS saldo,IFNULL(f.folio,'')AS factura,0 AS contrarecibo
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]' AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial'
		GROUP BY f.folio
		UNION
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,fd.folio,f.fecha,IFNULL(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,fd.total,0)) AS alcorriente,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<16,fd.total,0)) AS c1a15dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<31,fd.total,0)) AS c16a30dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<61,fd.total,0)) AS c31a60dias,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,fd.total,0)) AS may60dias,
		SUM(fd.total) AS saldo,IFNULL(f.folio,'')AS factura,0 AS contrarecibo
		FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN guiasventanilla gv ON fd.folio=gv.id
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.idsucursal='$_GET[sucursalprefijo]' AND f.estadocobranza <> 'C' AND f.tipoguia!='empresarial' 
		GROUP BY fd.folio $limite";
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
		
		$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s); 
		$s = "DROP TABLE tmp_clientes$x";
			mysql_query($s,$l) or die($s); 
	}
	
	if($_GET[accion]==5){
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";mysql_query($s,$l) or die($s);
		/* tabla de convenios */
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL
		FROM generacionconvenio GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		/* proceso para llenar la temporal */
		$f1 = split("/",$_GET[fecha1]);
		$f2 = split("/",$_GET[fecha2]);
		$fecha1 = $f1[2]."-".$f1[1]."-".$f1[0];
		$fecha2 = $f2[2]."-".$f2[1]."-".$f2[0];
	
		/*total de registros*/	/* cargos */
		$s = "SELECT ge.id 
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) AND ISNULL(ge.factura) 
		AND tc.idcliente=$_GET[idcliente] AND tc.idsucursal='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		UNION
		SELECT gv.id 
		FROM guiasventanilla gv 
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.cliente=$_GET[idcliente] AND 
		pg.sucursalacobrar='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION
		SELECT f.folio AS id 
		FROM facturacion f 
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.estadocobranza <> 'C'
		UNION	/* abonos */
		SELECT fp.guia AS id 
		FROM formapago fp 
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
		/*totales de los registros*/
		$s = "SELECT FORMAT(SUM(IFNULL(cargo,'')),2) cargos,FORMAT(SUM(IFNULL(abono,'')),2) abonos
		FROM(	/* cargos */
		SELECT SUM($if) AS cargo,0 AS abono 
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idcliente=$_GET[idcliente] AND tc.idsucursal='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		UNION
		SELECT SUM(gv.total) AS cargo,0 AS abono 
		FROM guiasventanilla gv 
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.cliente=$_GET[idcliente] AND 
		pg.sucursalacobrar='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2'
		UNION
		SELECT SUM(IFNULL(f.total,0)) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS cargo,0 AS abono 
		FROM facturacion f 
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.tipoguia='empresarial'
		UNION
		SELECT SUM(IFNULL(f.total,0)) AS cargo,0 AS abono 
		FROM facturacion f 
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.tipoguia!='empresarial'
		UNION	/* abonos */
		SELECT 0 AS cargo,SUM(fp.total) AS abono 
		FROM formapago fp 
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2')t";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT fecha,sucursal,IFNULL(refcargo,'') AS referenciacargo,IFNULL(refabono,'') AS referenciaabono,cargos,abonos,saldo,descripcion	
		FROM(	/* cargos */
		SELECT ge.fecha,cs.prefijo AS sucursal,ge.id AS refcargo,0 AS refabono,SUM($if) AS cargos,0 AS abonos,SUM($if) AS saldo,'' AS descripcion
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idcliente=$_GET[idcliente] AND tc.idsucursal='$_GET[prefijosucursal]' AND ge.fecha BETWEEN '$fecha1' AND '$fecha2' 
		GROUP BY ge.id
		UNION
		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS refcargo,0 AS refabono,SUM(gv.total) AS cargos,0 AS abonos,SUM(gv.total) AS saldo,'' AS descripcion
		FROM guiasventanilla gv	
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND 
		pg.cliente=$_GET[idcliente] AND pg.sucursalacobrar='$_GET[prefijosucursal]' AND gv.fecha BETWEEN '$fecha1' AND '$fecha2' GROUP BY gv.id
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,f.folio AS refcargo,0 AS refabono,SUM(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) 
		AS cargos,0 AS abonos,SUM(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS saldo,'' AS descripcion
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.tipoguia='empresarial' GROUP BY f.folio
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,f.folio AS refcargo,0 AS refabono,SUM(IFNULL(f.total,0)) AS cargos,0 AS abonos,SUM(IFNULL(f.total,0)) AS saldo,'' AS descripcion
		FROM facturacion f 
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=$_GET[idcliente] AND f.idsucursal='$_GET[prefijosucursal]' 
		AND f.fecha BETWEEN '$fecha1' AND '$fecha2' AND f.tipoguia!='empresarial' GROUP BY f.folio
		UNION	/* abonos */
		SELECT fp.fecha,cs.prefijo AS sucursal,0 AS refcargo,fp.guia AS refabono,0 AS cargos,SUM(fp.total) AS abonos,SUM(fp.total) AS saldo,
		CONCAT(IF(fp.efectivo>0,'EFECTIVO, ',''),IF(fp.tarjeta>0,'TARJETA, ',''),IF(fp.transferencia>0,'TRANSFERENCIA, ',''),
		IF(fp.cheque>0,CONCAT('CHEQUE ',IFNULL(fp.ncheque,'')),'')) AS descripcion
		FROM formapago fp 
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[idcliente] AND fp.sucursal='$_GET[prefijosucursal]' 
		AND fp.fecha BETWEEN '$fecha1' AND '$fecha2' GROUP BY fp.guia)t1 ORDER BY fecha $limite";
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
		
		$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s);
	}
?>