<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style>
	body{
		font-family:Verdana, Geneva, sans-serif;
		font-size:12px;
	}
	.titulo{
		font-size:14;
		font-weight:bold;
		background:url(../img/fondo_nuevoBuscar.jpg) repeat-x; 
		color:#FFF;
	}
	.tituloColumna{
		background:url(../img/fondo_nuevoBuscar.jpg) repeat-x; 
		color:#FFF;
	}
	.fila1{
		background-color:#F0F8FF;
	}
</style>
</head>
<?
	$s = "SELECT CONCAT_WS(' ',UCASE(eti_nombre1), UCASE(eti_nombre2)) empresa FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$empresa			= $f->empresa;
	
	$s = "select * from catalogosucursal where id = $_GET[sucursal_hidden]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	
	$idsucursalorigen 	= $f->id;
	$prefijosucursal	= $f->prefijo;
	$fechainicio 		= "'".cambiaf_a_mysql($_GET[inicio])."'";
	$fechafinal 		= "'".cambiaf_a_mysql($_GET[fin])."'";
	$inicuecom 			= '4800';
	$inicueivatra 		= '2525';
	$inicueivaret 		= '1420';
	$inicuetot 			= '1024';
	$inicuecansub 		= '4910';
	$inicuecaniva 		= '2525';
	
	$totalabonos = 0;
	$totalcargos = 0;
?>
<body>
	<table width="741" cellpadding="0px" cellspacing="0px" style="margin:auto;">
    	<tr>
        	<td colspan="4" align="center" class="titulo"><hr /></td>
        </tr>
    	<tr>
        	<td colspan="4" align="center" class="titulo"><?=$empresa?></td>
        </tr>
        <tr>
        	<td colspan="4" align="center" class="titulo">POLIZA DE <?=$prefijosucursal?> DEL <?=$_GET[inicio]?> AL <?=$_GET[fin]?></td>
        </tr>
        <tr>
        	<td colspan="4" align="center" class="titulo"><hr /></td>
        </tr>
    	<tr class="tituloColumna">
        	<td width="321">Descripcion</td>
        	<td width="189" align="center">Poliza</td>
        	<td width="119" align="center">Cargo</td><td width="110" align="center">Abono</td>
        </tr>
        <?
		#GUIAS HECHAS
		$s = "DROP TEMPORARY TABLE IF EXISTS movimientoguiaslocales;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `movimientoguiaslocales` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `guia` VARCHAR(50) DEFAULT NULL,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO movimientoguiaslocales
		(guia,descripcion,cantidad,cuenta,prefijo)
		SELECT gv.id, 'FLETE', SUM(IFNULL(gv.tflete,0)) flete, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0001-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'EAD', SUM(IFNULL(gv.tcostoead,0)) ead, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0002-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'REC', SUM(IFNULL(gv.trecoleccion,0)) rec, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0003-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'SEGURO', SUM(IFNULL(gv.tseguro,0)) seguro, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'DESCUENTO', SUM(IFNULL(gv.ttotaldescuento,0)) descuento, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0005-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'ADICIONAL', SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.tcombustible,0)) adicional, 
		CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'OTROS', SUM(IFNULL(gv.totros,0)) otros, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'IVA', SUM(IFNULL(gv.tiva,0)) iva, CONCAT_WS('-',$inicueivatra,'001-0000-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'IVARETENIDO', SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONCAT_WS('-',$inicueivaret,'010-0800-00') cuenta, cs.prefijo 
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'TOTAL', SUM(IFNULL(gv.total,0)) total, CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		WHERE gv.idsucursalorigen = $idsucursalorigen AND SUBSTRING(gv.id,1,3) = cs.idsucursal
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO movimientoguiaslocales
		(guia,descripcion,cantidad,cuenta,prefijo)
		SELECT gv.id, 'FLETE', SUM(IFNULL(gv.tflete,0)) flete, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0001-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'EAD', SUM(IFNULL(gv.tcostoead,0)) ead, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0002-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'REC', SUM(IFNULL(gv.trecoleccion,0)) rec, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0003-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'SEGURO', SUM(IFNULL(gv.tseguro,0)) seguro, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'DESCUENTO', SUM(IFNULL(gv.ttotaldescuento,0)) descuento, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0005-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'ADICIONAL', SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.tcombustible,0)) adicional, 
		CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'OTROS', SUM(IFNULL(gv.totros,0)) otros, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'IVA', SUM(IFNULL(gv.tiva,0)) iva, CONCAT_WS('-',$inicueivatra,'001-0000-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'IVARETENIDO', SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONCAT_WS('-',$inicueivaret,'010-0800-00') cuenta, cs.prefijo 
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)
		UNION
		SELECT gv.id, 'TOTAL', SUM(IFNULL(gv.total,0)) total, CONCAT_WS('-',$inicuetot,'460-0001-00') cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id 
		INNER JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE hc2.sucursal = $idsucursalorigen AND gv.idsucursalorigen <> $idsucursalorigen
		AND hc2.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0 having not isnull(gv.id)";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO movimientoguiaslocales
		(guia,descripcion,cantidad,cuenta,prefijo)
		SELECT gv.id, 'FLETE', SUM(IFNULL(gv.tflete,0)) flete, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'EAD', SUM(IFNULL(gv.tcostoead,0)) ead, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0002-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'REC', SUM(IFNULL(gv.trecoleccion,0)) rec, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0003-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'SEGURO', SUM(IFNULL(gv.tseguro,0)) seguro, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'DESCUENTO', SUM(IFNULL(gv.ttotaldescuento,0)) descuento, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0005-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'ADICIONAL', SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.tcombustible,0)) adicional, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'OTROS', SUM(IFNULL(gv.totros,0)) otros, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'IVA', SUM(IFNULL(gv.tiva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON $idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'IVARETENIDO', SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	
		UNION
		SELECT gv.id, 'TOTAL', SUM(IFNULL(gv.total,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		LEFT JOIN historial_cancelacionysustitucion hc2 ON gv.id = hc2.sustitucion AND hc2.accion = 'SUSTITUCION REALIZADA'
		WHERE IF(ISNULL(hc2.sucursal),gv.idsucursalorigen = $idsucursalorigen ,hc2.sucursal = $idsucursalorigen ) 
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 
		
		GROUP BY gv.idsucursaldestino having not isnull(gv.id)	;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT id, descripcion,prefijo,sum(cantidad) cantidad, cuenta 
		FROM movimientoguiaslocales
		group by cuenta,descripcion
		order by id";
		$r = mysql_query($s,$l) or die($s);
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){			
			if($f->descripcion=='IVARETENIDO' || $f->descripcion=='TOTAL'){
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}else{
				if($f->descripcion=='DESCUENTO'){
					$totalabonos -= $f->cantidad;
					$ta -= $f->cantidad;
				}else{
					$totalabonos += $f->cantidad;
					$ta += $f->cantidad;
				}
			}
			$cf=$cf;
		?>
    	<tr class="<?=($cf)?"fila1":"fila2"; $cf=!$cf;?>">
    	  <td align="left"><?=$f->descripcion?> <?=$f->prefijo?></td>
          <td align="center"><?=$f->cuenta?></td>
          <?
		  	if($f->descripcion=='IVARETENIDO' || $f->descripcion=='TOTAL'){
		  ?>
    	  	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			  	echo '<td align="right"></td>';
			}else{
				echo '<td align="right"></td>';
		  ?>
          	<td align="right"><?=(($f->descripcion=='DESCUENTO')?"-":"")?><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			}
		  ?>
  	  	</tr>
        <?
		}
		?>
        <tr>
          <td colspan="4" align="center"><hr /></td>
        </tr>
		<tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
          <td colspan="2" align="right">Total</td>
          <td align="right"><?=number_format($tc,2,'.',',');?></td>
          <td align="right"><?=number_format($ta,2,'.',',');?></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><hr /></td>
        </tr>
		<?
		/*
		#GUIAS FORANEAS
        $s = "DROP TEMPORARY TABLE IF EXISTS movimientosotrassucursales;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `movimientosotrassucursales` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO movimientosotrassucursales
		(descripcion,cantidad,cuenta,prefijo)
		SELECT 'FLETE', SUM(IFNULL(gv.tflete,0)) flete, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'EAD', SUM(IFNULL(gv.tcostoead,0)) ead, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0002-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'REC', SUM(IFNULL(gv.trecoleccion,0)) rec, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0003-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'SEGURO', SUM(IFNULL(gv.tseguro,0)) seguro, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'DESCUENTO', SUM(IFNULL(gv.ttotaldescuento,0)) descuento, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0005-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'ADICIONAL', SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.tcombustible,0)) adicional, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'OTROS', SUM(IFNULL(gv.totros,0)) otros, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'IVA', SUM(IFNULL(gv.tiva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'IVARETENIDO', SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'TOTAL', SUM(IFNULL(gv.total,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM movimientosotrassucursales order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
                </tr>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <?
				$tc = 0;
				$ta = 0;
			}
			$prefijo = $f->prefijo;
			
			if($f->descripcion=='IVARETENIDO' || $f->descripcion=='TOTAL'){
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}else{
				if($f->descripcion=='DESCUENTO'){
					$totalabonos -= $f->cantidad;
					$ta -= $f->cantidad;
				}else{
					$totalabonos += $f->cantidad;
					$ta += $f->cantidad;
				}
			}
		$cf=$cf;
		?>
    	<tr class="<?=($cf)?"fila1":"fila2"; $cf=!$cf;?>">
    	  <td align="left"><?=$f->descripcion?>  <?=$prefijosucursal?> -> <?=$f->prefijo?></td>
          <td align="center"><?=$f->cuenta?></td>
          <?
		  	if($f->descripcion=='IVARETENIDO' || $f->descripcion=='TOTAL'){
		  ?>
    	  	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			  	echo '<td align="right"></td>';
			}else{
				echo '<td align="right"></td>';
		  ?>
          	<td align="right"><?=(($f->descripcion=='DESCUENTO')?"-":"")?><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			}
		  ?>
  	  	</tr>
        <?
		}
		?>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
        <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
          <td colspan="2" align="right">Total</td>
          <td align="right"><?=number_format($tc,2,'.',',');?></td>
          <td align="right"><?=number_format($ta,2,'.',',');?></td>
        </tr>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		 <?
		 */
        $s = "DROP TEMPORARY TABLE IF EXISTS movimientosfacturacion;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `movimientosfacturacion` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO movimientosfacturacion
		(descripcion,cantidad,cuenta,prefijo)
		SELECT 'FACT FLETE', SUM(IFNULL(f.flete,0)) flete, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT EAD', SUM(IFNULL(f.ead,0)) ead, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0002-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT REC', SUM(IFNULL(f.recoleccion,0)) rec, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0003-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT SEGURO', SUM(IFNULL(f.seguro,0)) seguro, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT DESCUENTO', SUM(IFNULL(f.totaldescuento,0)) descuento, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0005-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT ADICIONAL', SUM(IFNULL(f.excedente,0)+IFNULL(f.combustible,0)) adicional, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT OTROS', SUM(IFNULL(f.otros,0)) otros, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT IVA', SUM(IFNULL(f.iva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT IVARETENIDO', SUM(IFNULL(f.ivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT TOTAL', SUM(IFNULL(f.total,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM movimientosfacturacion order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
                </tr>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <?
				$tc = 0;
				$ta = 0;
			}
			$prefijo = $f->prefijo;
			
			if($f->descripcion=='FACT IVARETENIDO' || $f->descripcion=='FACT TOTAL'){
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}else{
				if($f->descripcion=='FACT DESCUENTO'){
					$totalabonos -= $f->cantidad;
					$ta -= $f->cantidad;
				}else{
					$totalabonos += $f->cantidad;
					$ta += $f->cantidad;
				}
			}
		$cf=$cf;
		?>
    	<tr class="<?=($cf)?"fila1":"fila2"; $cf=!$cf;?>">
    	  <td align="left"><?=$f->descripcion?> <?=$f->prefijo?></td>
          <td align="center"><?=$f->cuenta?></td>
          <?
		  	if($f->descripcion=='FACT IVARETENIDO' || $f->descripcion=='FACT TOTAL'){
		  ?>
    	  	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			  	echo '<td align="right"></td>';
			}else{
				echo '<td align="right"></td>';
		  ?>
          	<td align="right"><?=(($f->descripcion=='FACT DESCUENTO')?"-":"")?><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			}
		  ?>
  	  	</tr>
        <?
		}
        ?>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
          <td colspan="2" align="right">Total</td>
          <td align="right"><?=number_format($tc,2,'.',',');?></td>
          <td align="right"><?=number_format($ta,2,'.',',');?></td>
        </tr>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<?
        $s = "DROP TEMPORARY TABLE IF EXISTS movimientosfactsobseguro;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `movimientosfactsobseguro` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO movimientosfactsobseguro
		(descripcion,cantidad,cuenta,prefijo)
		SELECT 'FACT SEGURO', SUM(IFNULL(f.sobseguro,0)) seguro, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0
		UNION
		SELECT 'FACT ADICIONAL', SUM(IFNULL(f.sobexcedente,0)) adicional, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0
		UNION
		SELECT 'FACT IVA', SUM(IFNULL(f.sobiva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0
		UNION
		SELECT 'FACT IVARETENIDO', SUM(IFNULL(f.sobivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0
		UNION
		SELECT 'FACT TOTAL', SUM(IFNULL(f.sobmontoafacturar,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM movimientosfactsobseguro order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
                </tr>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <?
				$tc = 0;
				$ta = 0;
			}
			$prefijo = $f->prefijo;
			
			if($f->descripcion=='FACT IVARETENIDO' || $f->descripcion=='FACT TOTAL'){
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}else{
				if($f->descripcion=='FACT DESCUENTO'){
					$totalabonos -= $f->cantidad;
					$ta -= $f->cantidad;
				}else{
					$totalabonos += $f->cantidad;
					$ta += $f->cantidad;
				}
			}
		$cf=$cf;
		?>
    	<tr class="<?=($cf)?"fila1":"fila2"; $cf=!$cf;?>">
    	  <td align="left"><?=$f->descripcion?> <?=$f->prefijo?></td>
          <td align="center"><?=$f->cuenta?></td>
          <?
		  	if($f->descripcion=='FACT IVARETENIDO' || $f->descripcion=='FACT TOTAL'){
		  ?>
    	  	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			  	echo '<td align="right"></td>';
			}else{
				echo '<td align="right"></td>';
		  ?>
          	<td align="right"><?=(($f->descripcion=='FACT DESCUENTO')?"-":"")?><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			}
		  ?>
  	  	</tr>
        <?
		}
		
		?>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
          <td colspan="2" align="right">Total</td>
          <td align="right"><?=number_format($tc,2,'.',',');?></td>
          <td align="right"><?=number_format($ta,2,'.',',');?></td>
        </tr>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<?
		
        $s = "DROP TEMPORARY TABLE IF EXISTS movimientosfactotros;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `movimientosfactotros` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO movimientosfactotros
		(descripcion,cantidad,cuenta,prefijo)
		SELECT 'FACT OTROS', SUM(IFNULL(f.otrossubtotal,0)) otros, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0
		UNION
		SELECT 'FACT IVA', SUM(IFNULL(f.otrosiva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0
		UNION
		SELECT 'FACT IVARETENIDO', SUM(IFNULL(f.otrosivaretenido,0)) ivaretenido, 
		CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0
		UNION
		SELECT 'FACT TOTAL', SUM(IFNULL(f.otrosmontofacturar,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND DATE(f.fecha) BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM movimientosfactotros order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
                </tr>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <?
				$tc = 0;
				$ta = 0;
			}
			$prefijo = $f->prefijo;
			
			if($f->descripcion=='FACT IVARETENIDO' || $f->descripcion=='FACT TOTAL'){
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}else{
				if($f->descripcion=='FACT DESCUENTO'){
					$totalabonos -= $f->cantidad;
					$ta -= $f->cantidad;
				}else{
					$totalabonos += $f->cantidad;
					$ta += $f->cantidad;
				}
			}
		$cf=$cf;
		?>
    	<tr class="<?=($cf)?"fila1":"fila2"; $cf=!$cf;?>">
    	  <td align="left"><?=$f->descripcion?> <?=$f->prefijo?></td>
          <td align="center"><?=$f->cuenta?></td>
          <?
		  	if($f->descripcion=='FACT IVARETENIDO' || $f->descripcion=='FACT TOTAL'){
		  ?>
    	  	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			  	echo '<td align="right"></td>';
			}else{
				echo '<td align="right"></td>';
		  ?>
          	<td align="right"><?=(($f->descripcion=='FACT DESCUENTO')?"-":"")?><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			}
		  ?>
  	  	</tr>
        <?
		}
		?>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
          <td colspan="2" align="right">Total</td>
          <td align="right"><?=number_format($tc,2,'.',',');?></td>
          <td align="right"><?=number_format($ta,2,'.',',');?></td>
        </tr>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<?
        
		 $s = "DROP TEMPORARY TABLE IF EXISTS guiascancelaciones;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `guiascancelaciones` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `orden` VARCHAR(50) DEFAULT NULL,
		  `folio` VARCHAR(50) DEFAULT NULL,
		  `guia` VARCHAR(50) DEFAULT NULL,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO guiascancelaciones
		(orden,folio,descripcion,cantidad,cuenta,prefijo)
		SELECT 'A',gv.id,'CANC OTROS', SUM(IFNULL(gv.subtotal,0)) otros, 
		CONVERT(CONCAT_WS('-',$inicuecansub,cs.idsucursal,'0000-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal=$idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal group by gv.id
		UNION
		SELECT 'B',gv.id,'CANC IVA', SUM(IFNULL(gv.tiva,0)) iva, CONVERT(CONCAT_WS('-',$inicuecaniva,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal=$idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal group by gv.id
		UNION
		SELECT 'C',gv.id,'CANC IVARETENIDO', SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, 
		CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal=$idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal group by gv.id
		UNION
		SELECT 'D',gv.id,'CANC TOTAL', SUM(IFNULL(gv.total,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON if(gv.tipoflete=0,gv.idsucursalorigen = cs.id,gv.idsucursaldestino = cs.id)
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia AND hc.accion = 'SUSTITUCION REALIZADA'
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal=$idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal group by gv.id;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "SELECT * FROM guiascancelaciones order by folio,orden";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
			/*	?>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
                </tr>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <? */
				//$tc = 0;
				//$ta = 0;
			}
			$prefijo = $f->prefijo;
			
			if($f->descripcion=='CANC IVARETENIDO' || $f->descripcion=='CANC TOTAL'){
				$totalabonos += $f->cantidad;
				$ta += $f->cantidad;
			}else{
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}
		$cf=$cf;
		?>
    	<tr class="<?=($cf)?"fila1":"fila2"; $cf=!$cf;?>">
    	  <td align="left"><?=$f->descripcion?> FOLIO <?=$f->folio?> <?=$f->prefijo?></td>
          <td align="center"><?=$f->cuenta?></td>
          <?
		  	if($f->descripcion=='CANC IVARETENIDO' || $f->descripcion=='CANC TOTAL'){
		   		echo '<td align="right"></td>';
		  ?>
          	<td align="right"><?=(($f->descripcion=='CANC DESCUENTO')?"-":"")?><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			}else{
		   ?>
    	  	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			  	echo '<td align="right"></td>';
			}
		  ?>
  	  	</tr>
        <?
		}
		?>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
          <td colspan="2" align="right">Total</td>
          <td align="right"><?=number_format($tc,2,'.',',');?></td>
          <td align="right"><?=number_format($ta,2,'.',',');?></td>
        </tr>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<?
		 $s = "DROP TEMPORARY TABLE IF EXISTS guiascancelaciones;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `guiascancelaciones` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `orden` VARCHAR(50) DEFAULT NULL,
		  `folio` DOUBLE DEFAULT NULL,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO guiascancelaciones
		(orden,folio,descripcion,cantidad,cuenta,prefijo)
		SELECT 'A',f.folio,'CANC FACT SUBTOTAL', SUM(IFNULL(f.otrossubtotal,0)+IFNULL(f.sobsubtotal,0)+IFNULL(f.subtotal,0)) subtotal, 
		CONVERT(CONCAT_WS('-',$inicuecansub,cs.idsucursal,'0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fechacancelacion BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		GROUP BY f.folio
		UNION
		SELECT 'B',f.folio,'CANC FACT IVA', SUM(IFNULL(f.otrosiva,0)+IFNULL(f.sobiva,0)+IFNULL(f.iva,0)) iva, 
		CONVERT(CONCAT_WS('-',$inicuecaniva,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fechacancelacion BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		GROUP BY f.folio
		UNION
		SELECT 'C',f.folio,'CANC FACT IVARETENIDO', SUM(IFNULL(f.otrosivaretenido,0)+IFNULL(f.sobivaretenido,0)+IFNULL(f.ivaretenido,0)) ivaretenido, 
		CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fechacancelacion BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		GROUP BY f.folio
		UNION
		SELECT 'D',f.folio,'CANC FACT TOTAL', SUM(IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0)+IFNULL(f.total,0)) total, 
		CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fechacancelacion BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		GROUP BY f.folio;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "SELECT * FROM guiascancelaciones order by folio,orden";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
                </tr>
                <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
                <?
				$tc = 0;
				$ta = 0;
			}
			$prefijo = $f->prefijo;
			
			if($f->descripcion=='CANC FACT IVARETENIDO' || $f->descripcion=='CANC FACT TOTAL'){
				$totalabonos += $f->cantidad;
				$ta += $f->cantidad;
			}else{
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}
		$cf=$cf;
		?>
    	<tr class="<?=($cf)?"fila1":"fila2"; $cf=!$cf;?>">
    	  <td align="left"><?=$f->descripcion?> FOLIO <?=$f->folio?> <?=$f->prefijo?></td>
          <td align="center"><?=$f->cuenta?></td>
          <?
		  	if($f->descripcion=='CANC FACT IVARETENIDO' || $f->descripcion=='CANC FACT TOTAL'){
		   		echo '<td align="right"></td>';
		  ?>
          	<td align="right"><?=(($f->descripcion=='CANC FACT DESCUENTO')?"-":"")?><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			}else{
		   ?>
    	  	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			  	echo '<td align="right"></td>';
			}
		  ?>
  	  	</tr>
        <?
		}
		?>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
          <td colspan="2" align="right">Total</td>
          <td align="right"><?=number_format($tc,2,'.',',');?></td>
          <td align="right"><?=number_format($ta,2,'.',',');?></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><hr /></td>
        </tr>
        
        
        
        <?
		 $s = "DROP TEMPORARY TABLE IF EXISTS traspasocargo;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `traspasocargo` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `orden` VARCHAR(50) DEFAULT NULL,
		  `folio` VARCHAR(50) DEFAULT NULL,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO traspasocargo
		(orden,folio,descripcion,cantidad,cuenta,prefijo)
		SELECT 'A',gv.id,'CANC TRASPASO', gv.total, 
		CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON pg.guia = gv.id
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		INNER JOIN traspasocredito tc ON gv.id = tc.guia
		WHERE gv.tipoflete = 1 AND gv.idsucursaldestino <> pg.sucursalacobrar
		AND gv.idsucursaldestino = $idsucursalorigen 
		AND date(tc.fecha) BETWEEN $fechainicio AND $fechafinal
		group by gv.id
		UNION
		SELECT 'A',gv.id,'CRED TRASPASO', gv.total, 
		CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON pg.guia = gv.id
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar = cs.id
		INNER JOIN traspasocredito tc ON gv.id = tc.guia
		WHERE gv.tipoflete = 1 AND gv.idsucursaldestino <> pg.sucursalacobrar
		AND gv.idsucursaldestino = $idsucursalorigen 
		AND date(tc.fecha) BETWEEN $fechainicio AND $fechafinal
		group by gv.id
		UNION
		SELECT 'A',gv.id,'CANC TRASPASO', gv.total, 
		CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON pg.guia = gv.id
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN traspasocredito tc ON gv.id = tc.guia
		WHERE gv.tipoflete = 0 AND gv.idsucursalorigen <> pg.sucursalacobrar
		AND gv.idsucursalorigen = $idsucursalorigen 
		AND date(tc.fecha) BETWEEN $fechainicio AND $fechafinal
		group by gv.id
		UNION
		SELECT 'A',gv.id,'CRED TRASPASO', gv.total, 
		CONVERT(CONCAT_WS('-',$inicuetot,cs.idsucursal,'0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON pg.guia = gv.id
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar = cs.id
		INNER JOIN traspasocredito tc ON gv.id = tc.guia
		WHERE gv.tipoflete = 0 AND gv.idsucursalorigen <> pg.sucursalacobrar
		AND gv.idsucursalorigen = $idsucursalorigen 
		AND date(tc.fecha) BETWEEN $fechainicio AND $fechafinal
		group by gv.id
		";
		
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "SELECT * FROM traspasocargo order by folio,orden";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){			
			if($f->descripcion=='CANC TRASPASO'){
				$totalabonos += $f->cantidad;
				$ta += $f->cantidad;
			}else{
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}
		$cf=$cf;
		?>
    	<tr class="<?=($cf)?"fila1":"fila2"; $cf=!$cf;?>">
    	  <td align="left"><?=$f->descripcion?> FOLIO <?=$f->folio?> <?=$f->prefijo?></td>
          <td align="center"><?=$f->cuenta?></td>
          <?
		  	if($f->descripcion=='CANC TRASPASO'){
		   		echo '<td align="right"></td>';
		  ?>
          	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			}else{
		   ?>
    	  	<td align="right"><?=number_format($f->cantidad,2,'.',',');?></td>
          <?
			  	echo '<td align="right"></td>';
			}
		  ?>
  	  	</tr>
        <?
		}
		?>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
		<tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#FFD2D2;"; }else{ echo "background-color:#C8F1A9;";} ?>">
          <td colspan="2" align="right">Total</td>
          <td align="right"><?=number_format($tc,2,'.',',');?></td>
          <td align="right"><?=number_format($ta,2,'.',',');?></td>
        </tr>
        
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
        <tr>
    	  <td colspan="2" align="right">Total</td>
    	  <td align="right"><?=number_format($totalcargos,2,'.',',');?></td>
    	  <td align="right"><?=number_format($totalabonos,2,'.',',');?></td>
  	  	</tr>
        <tr>
                  <td colspan="4" align="center"><hr /></td>
                </tr>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><hr /></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><img src="../img/Boton_Imprimir.gif" style="cursor:pointer" onclick="this.style.display='none'; window.print(); this.style.display = '';" /></td>
        </tr>
    </table>
</body>
</html>