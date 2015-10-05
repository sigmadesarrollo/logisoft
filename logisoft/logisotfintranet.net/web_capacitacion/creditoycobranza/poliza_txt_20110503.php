<?
	
	//header("Content-Disposition: attachment; filename=Poliza.txt");
	
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");


	$var_itabla = $_SESSION[IDUSUARIO].date("dHis");
	//preparar las sucursales
	$s = "CREATE TABLE `catalogosucursal_tmp_".$var_itabla."` (
		  `id` INT(11) NOT NULL DEFAULT '0',
		  `prefijo` VARCHAR(10) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `idsucursal` VARCHAR(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  KEY `id` (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	mysql_query($s,$l) or die(mysql_error($l)."-".$s);
	
		$s = "insert into catalogosucursal_tmp_".$var_itabla."
			SELECT id, 
			CASE prefijo
				WHEN 'MY1' THEN 'MTY'
				WHEN 'LE1' THEN 'LEO'
				ELSE prefijo
			END prefijo,
			CASE idsucursal
				WHEN '561' THEN '560'
				WHEN '401' THEN '400'
				ELSE idsucursal
			END idsucursal
			FROM catalogosucursal";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);

	$s = "SELECT CONCAT_WS(' ',UCASE(eti_nombre1), UCASE(eti_nombre2)) empresa FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die(mysql_error($l)."-".$s);
	$f = mysql_fetch_object($r);
	
	$empresa			= $f->empresa;
	
	$s = "select * from catalogosucursal_tmp_".$var_itabla." where id = $_GET[sucursal_hidden]";
	$r = mysql_query($s,$l) or die(mysql_error($l)."-".$s);
	$f = mysql_fetch_object($r);
	
	
	//header('Content-Type: application/download');	
    //header('Content-Disposition: filename=P.txt');
	
	$idsucursalorigen 	= $f->id;
	$prefijosucursal	= $f->prefijo;
	$cuentacontable		= $f->idsucursal;
	$fechainicio 		= "'".cambiaf_a_mysql($_GET[inicio])."'";
	$fechafinal 		= "'".cambiaf_a_mysql($_GET[fin])."'";
	$inicuecom 			= '4800';
	$inicueivatra 		= '2525';
	$inicueivaret 		= '1420';
	$inicuetot 			= '1024';
	$inicuecansub 		= '4910';
	$inicuecaniva 		= '2525';
	
	//notas de credito cuando se paga con notas de credito unicamente
	$notacredito		= '4900';
	
	header("Content-Disposition: attachment; filename=P".$cuentacontable."_".str_replace("/","",$_GET[inicio]).".txt");
	
	$fechaarriba		= str_replace("-","",cambiaf_a_mysql($_GET[inicio]));
	
	
	$meses = array("","ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
	$dias = split("-",cambiaf_a_mysql($_GET[inicio]));
	$diainicioarriba = $dias[2];
	$dias = split("-",cambiaf_a_mysql($_GET[fin]));
	$diafinarriba = $dias[2];
	$mesarriba = $meses[$dias[1]];
	$tituloarriba = "LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ".$prefijosucursal;
	$numeropoliza = "01".$cuentacontable.str_pad($dias[2],3,"0",STR_PAD_LEFT);
	//					01460003
	$totalabonos = 0;
	$totalcargos = 0;

		#GUIAS HECHAS
		$s = "DROP TEMPORARY TABLE IF EXISTS movimientos;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);

		$s = "CREATE TEMPORARY TABLE `movimientos` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `orden` VARCHAR(50) DEFAULT NULL,
		  `tipom` CHAR(2) DEFAULT NULL,
		  `guia` VARCHAR(50) DEFAULT NULL,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		//0 cargo
		//1 abono
		
		$s = "INSERT INTO movimientos
		(tipom,orden,guia,descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT 1,'B', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tflete,0)) flete, CONCAT_WS('',$inicuecom,cs.idsucursal,'000100') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'C', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tcostoead,0)) ead, CONCAT_WS('',$inicuecom,cs.idsucursal,'000200') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'D', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.trecoleccion,0)) rec, CONCAT_WS('',$inicuecom,cs.idsucursal,'000300') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'E', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tseguro,0)) seguro, CONCAT_WS('',$inicuecom,cs.idsucursal,'000400') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 0,'F', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.ttotaldescuento,0)) descuento, CONCAT_WS('',$inicuecom,cs.idsucursal,'000500') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'G', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.totros,0)) adicional, 
		CONCAT_WS('',$inicuecom,cs.idsucursal,'000600') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'H', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tcombustible,0)) otros, CONCAT_WS('',$inicuecom,cs.idsucursal,'000700') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'I', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tiva,0)) iva, CONCAT_WS('',$inicueivatra,'001000000') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 0,'J', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONCAT_WS('',$inicueivaret,'010080000') cuenta, cs.prefijo 
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 0,'A', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.total,0)) total, CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "INSERT INTO movimientos
		(tipom, orden, guia,descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT 1,'B', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tflete,0)) flete, CONCAT_WS('',$inicuecom,cs.idsucursal,'000100') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'C', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tcostoead,0)) ead, CONCAT_WS('',$inicuecom,cs.idsucursal,'000200') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'D', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.trecoleccion,0)) rec, CONCAT_WS('',$inicuecom,cs.idsucursal,'000300') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'E', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tseguro,0)) seguro, CONCAT_WS('',$inicuecom,cs.idsucursal,'000400') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 0,'F', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.ttotaldescuento,0)) descuento, CONCAT_WS('',$inicuecom,cs.idsucursal,'000500') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'G', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.totros,0)) adicional, 
		CONCAT_WS('',$inicuecom,cs.idsucursal,'000600') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'H', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tcombustible,0)) otros, CONCAT_WS('',$inicuecom,cs.idsucursal,'000700') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 1,'I', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tiva,0)) iva, CONCAT_WS('',$inicueivatra,'001000000') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 0,'J', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONCAT_WS('',$inicueivaret,'010080000') cuenta, cs.prefijo 
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT DISTINCT 0,'A', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.total,0)) total, CONCAT_WS('',$inicuetot,'460000100') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "INSERT INTO movimientos
		(tipom, orden, guia,descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT 1,'B', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tflete,0)) flete, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 1,'C', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tcostoead,0)) ead, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000200') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 1,'D', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.trecoleccion,0)) rec, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000300') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 1,'E', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tseguro,0)) seguro, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000400') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 0,'F', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.ttotaldescuento,0)) descuento, 
		CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000500') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 1,'G', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.totros,0)) adicional, 
		CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000600') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 1,'H', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tcombustible,0)) otros, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000700') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 1,'I', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.tiva,0)) iva, CONVERT(CONCAT_WS('',$inicueivatra,'001000000') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 0,'J', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('',$inicueivaret,'010080000') USING utf8) cuenta , cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursaldestino = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT DISTINCT 0,'A', gv.id, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(gv.total,0)) total, CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursaldestino = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		
		
		$s = "INSERT INTO movimientos
		(tipom,orden, guia, descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT  1,'B', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.flete,0)) flete, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'C', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.ead,0)) ead, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000200') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'D', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.recoleccion,0)) rec, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000300') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'E', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.seguro,0)) seguro, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000400') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'F', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.totaldescuento,0)) descuento, 
		CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000500') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'G', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.excedente,0)+IFNULL(f.otros,0)) adicional, 
		CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000600') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'H', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.combustible,0)) otros, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000700') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'I', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.iva,0)) iva, CONVERT(CONCAT_WS('',$inicueivatra,'001000000') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 0, 'J', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.ivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('',$inicueivaret,'010080000') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 0, 'A', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.total,0)) total, CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0 having not isnull(f.folio);";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		
		
		$s = "INSERT INTO movimientos
		(tipom,orden,guia,descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT 1, 'E', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), 
		SUM(IFNULL(f.sobseguro,0)) seguro, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000400') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'G', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), 
		SUM(IFNULL(f.sobexcedente,0)) adicional, 
		CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000600') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'I', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.sobiva,0)) iva, CONVERT(CONCAT_WS('',$inicueivatra,'001000000') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 0, 'J', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.sobivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('',$inicueivaret,'010080000') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 0, 'A', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.sobmontoafacturar,0)) total, CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0 having not isnull(f.folio);";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		
		
		$s = "INSERT INTO movimientos
		(tipom,orden,guia,descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT 1, 'H', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.otrossubtotal,0)) otros, CONVERT(CONCAT_WS('',$inicuecom,cs.idsucursal,'000700') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 1, 'I', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.otrosiva,0)) iva, CONVERT(CONCAT_WS('',$inicueivatra,'001000000') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 0, 'J', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.otrosivaretenido,0)) ivaretenido, 
		CONVERT(CONCAT_WS('',$inicueivaret,'010080000') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0 having not isnull(f.folio)
		UNION
		SELECT DISTINCT 0, 'A', f.folio, concat('LIQ. DEL $diainicioarriba AL $diafinarriba $mesarriba/".substr($dias[0],2,4)." ',cs.prefijo), SUM(IFNULL(f.otrosmontofacturar,0)) total, CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0  having not isnull(f.folio);";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		
		$s = "INSERT INTO movimientos
		(tipom,orden,guia,descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT 0, 'CA',gv.id,concat(gv.id,' ',cs.prefijo), SUM(IFNULL(gv.subtotal,0)) otros, 
		CONVERT(CONCAT_WS('',$inicuecansub,cs.idsucursal,'000000') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal = $idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal group by gv.id
		UNION
		SELECT DISTINCT 0, 'CA',gv.id,concat(gv.id,' ',cs.prefijo), SUM(IFNULL(gv.tiva,0)) iva, CONVERT(CONCAT_WS('',$inicuecaniva,'001000000') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal = $idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal group by gv.id
		UNION
		SELECT DISTINCT 1, 'CA',gv.id,concat(gv.id,' ',cs.prefijo), SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, 
		CONVERT(CONCAT_WS('',$inicueivaret,'010080000') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal = $idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal group by gv.id
		UNION
		SELECT DISTINCT 1, 'CA',gv.id,concat(gv.id,' ',cs.prefijo), SUM(IFNULL(gv.total,0)) total, 
		CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON if(gv.tipoflete=0,gv.idsucursalorigen = cs.id,gv.idsucursaldestino = cs.id)
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal = $idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal group by gv.id;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		
		$s = "INSERT INTO movimientos
		(tipom,orden,guia,descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT 0, 'CA',f.folio,concat(f.folio,' ',cs.prefijo), SUM(IFNULL(f.otrossubtotal,0)+IFNULL(f.sobsubtotal,0)+IFNULL(f.subtotal,0)) subtotal, 
		CONVERT(CONCAT_WS('',$inicuecansub,cs.idsucursal,'000000') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fechacancelacion BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		GROUP BY f.folio
		UNION
		SELECT DISTINCT 0, 'CA',f.folio,concat(f.folio,' ',cs.prefijo), SUM(IFNULL(f.otrosiva,0)+IFNULL(f.sobiva,0)+IFNULL(f.iva,0)) iva, 
		CONVERT(CONCAT_WS('',$inicuecaniva,'001000000') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fechacancelacion BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		GROUP BY f.folio
		UNION
		SELECT DISTINCT 1, 'CA',f.folio,concat(f.folio,' ',cs.prefijo), SUM(IFNULL(f.otrosivaretenido,0)+IFNULL(f.sobivaretenido,0)+IFNULL(f.ivaretenido,0)) ivaretenido, 
		CONVERT(CONCAT_WS('',$inicueivaret,'010080000') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fechacancelacion BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		GROUP BY f.folio
		UNION
		SELECT DISTINCT 1, 'CA',f.folio,concat(f.folio,' ',cs.prefijo), SUM(IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0)+IFNULL(f.total,0)) total, 
		CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fechacancelacion BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		GROUP BY f.folio;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "INSERT INTO movimientos
		(tipom,orden,guia,descripcion,cantidad,cuenta,prefijo)
		SELECT DISTINCT 1, 'CA',gv.id,'CANC TRASPASO', gv.total, 
		CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON pg.guia = gv.id
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursaldestino = cs.id
		INNER JOIN traspasocredito tc ON gv.id = tc.guia
		WHERE gv.tipoflete = 1 AND gv.idsucursaldestino <> pg.sucursalacobrar
		AND gv.idsucursaldestino = $idsucursalorigen 
		AND date(tc.fecha) BETWEEN $fechainicio AND $fechafinal
		group by gv.id
		UNION
		SELECT DISTINCT 0, 'CA',gv.id,'CRED TRASPASO', gv.total, 
		CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON pg.guia = gv.id
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON pg.sucursalacobrar = cs.id
		INNER JOIN traspasocredito tc ON gv.id = tc.guia
		WHERE gv.tipoflete = 1 AND gv.idsucursaldestino <> pg.sucursalacobrar
		AND gv.idsucursaldestino = $idsucursalorigen 
		AND date(tc.fecha) BETWEEN $fechainicio AND $fechafinal
		group by gv.id
		UNION
		SELECT DISTINCT 1, 'CA',gv.id,'CANC TRASPASO', gv.total, 
		CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON pg.guia = gv.id
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON gv.idsucursalorigen = cs.id
		INNER JOIN traspasocredito tc ON gv.id = tc.guia
		WHERE gv.tipoflete = 0 AND gv.idsucursalorigen <> pg.sucursalacobrar
		AND gv.idsucursalorigen = $idsucursalorigen 
		AND date(tc.fecha) BETWEEN $fechainicio AND $fechafinal
		group by gv.id
		UNION
		SELECT DISTINCT 0, 'CA',gv.id,'CRED TRASPASO', gv.total, 
		CONVERT(CONCAT_WS('',$inicuetot,cs.idsucursal,'000100') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON pg.guia = gv.id
		INNER JOIN catalogosucursal_tmp_".$var_itabla." cs ON pg.sucursalacobrar = cs.id
		INNER JOIN traspasocredito tc ON gv.id = tc.guia
		WHERE gv.tipoflete = 0 AND gv.idsucursalorigen <> pg.sucursalacobrar
		AND gv.idsucursalorigen = $idsucursalorigen 
		AND date(tc.fecha) BETWEEN $fechainicio AND $fechafinal
		group by gv.id";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		/*$s = "UPDATE movimientos
		SET prefijo = 'LEO', cuenta = CONCAT(SUBSTRING(cuenta,1,4),'460',SUBSTRING(cuenta,7,6))
		WHERE prefijo = 'LE1' AND LENGTH(cuenta)=13;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "UPDATE movimientos
		SET prefijo = 'MTY', cuenta = CONCAT(SUBSTRING(cuenta,1,4),'560',SUBSTRING(cuenta,7,6))
		WHERE prefijo = 'MY1' AND LENGTH(cuenta)=13;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);*/
		
		echo "P  $fechaarriba    1  $numeropoliza 1 0          $tituloarriba                                                                            1 2\r\n";
		
		$s = "select tipom, cuenta, descripcion, round(sum(cantidad),2) cantidad
		from movimientos
		where not isnull(guia) and orden <> 'CA'
		group by cuenta
		order by orden";
		$r = mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		while($f = mysql_fetch_object($r)){
				echo "M  $f->cuenta                             ".$f->tipom.str_pad($f->cantidad,17," ",STR_PAD_LEFT)."     0          0.0                  $f->descripcion	\r\n";
		}
		
		$s = "select tipom, cuenta, descripcion, round(sum(cantidad),2) cantidad
		from movimientos
		where not isnull(guia) and orden = 'CA'
		group by cuenta
		order by orden";
		$r = mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		while($f = mysql_fetch_object($r)){
				echo "M  $f->cuenta                             ".$f->tipom.str_pad($f->cantidad,17," ",STR_PAD_LEFT)."     0          0.0                  $f->descripcion	\r\n";
		}
		
		$s = "drop table catalogosucursal_tmp_".$var_itabla;
		mysql_query($s,$l);
?>