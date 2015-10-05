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
</head>
<?
	$idsucursalorigen 	= 9;
	$prefijosucursal	= 'TLQ';
	$fechainicio 		= "'2011-01-03'";
	$fechafinal 		= "'2011-01-03'";
	$inicuecom 			= '4800';
	$inicueivatra 		= '2525';
	$inicueivaret 		= '1420';
	$inicuetot 			= '1024';
	$inicuecansub 		= '4910';
	$inicuecaniva 		= '2525';
	
	$totalabonos = 0;
	$totalcargos = 0;
	
	function ejecutarConsulta($con,$l){
		$r = mysql_query($con,$l) or die($s);
		return mysql_fetch_object($r);
	}
?>
<body>
	<table width="741">
    	<tr>
        	<td colspan="4">Titulo</td>
        </tr>
    	<tr>
        	<td width="235">Descripcion</td>
        	<td width="184">Poliza</td>
        	<td width="148" align="center">Cargo</td><td width="154" align="center">Abono</td>
        </tr>
        <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.tflete,0)) flete, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0001-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0",$l);
			$totalabonos += $f->flete;
			$totalcargos += 0;
		?>
    	<tr>
    	  <td align="left">Flete <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right">&nbsp;</td>
    	  <td align="right"><?=number_format($f->flete,2,'.',',');?></td>
  	  	</tr>
        <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.tcostoead,0)) ead, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0002-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos += $f->ead;
			$totalcargos += 0;
		?>
    	<tr>
    	  <td align="left">EAD <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right">&nbsp;</td>
    	  <td align="right"><?=number_format($f->ead,2,'.',',');?></td>
  	  	</tr>
        <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.trecoleccion,0)) rec, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0003-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos += $f->rec;
			$totalcargos += 0;
		?>
    	<tr>
    	  <td align="left">REC <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right">&nbsp;</td>
    	  <td align="right"><?=number_format($f->rec,2,'.',',');?></td>
  	  	</tr>
        <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.tseguro,0)) seguro, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos += $f->seguro;
			$totalcargos += 0;
		?>
    	<tr>
    	  <td align="left">Seguro <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right">&nbsp;</td>
    	  <td align="right"><?=number_format($f->seguro,2,'.',',');?></td>
  	  	</tr>
         <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.ttotaldescuento,0)) descuento, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0005-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos -= $f->descuento;
			$totalcargos += 0;
		?>
    	<tr>
    	  <td align="left">Descuento <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right">&nbsp;</td>
    	  <td align="right">-<?=number_format($f->descuento,2,'.',',');?></td>
  	  	</tr>
        <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.tcombustible,0)) adicional, 
			CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos += $f->adicional;
			$totalcargos += 0;
		?>
    	<tr>
    	  <td align="left">Adicional <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right">&nbsp;</td>
    	  <td align="right"><?=number_format($f->descuento,2,'.',',');?></td>
  	  	</tr>
        <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.totros,0)) otros, CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos += $f->otros;
			$totalcargos += 0;
		?>
    	<tr>
    	  <td align="left">Otros <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right">&nbsp;</td>
    	  <td align="right"><?=number_format($f->otros,2,'.',',');?></td>
  	  	</tr>
        <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.tiva,0)) iva, CONCAT_WS('-',$inicueivatra,'001-0000-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos += $f->iva;
			$totalcargos += 0;
		?>
    	<tr>
    	  <td align="left">IVA <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right">&nbsp;</td>
    	  <td align="right"><?=number_format($f->iva,2,'.',',');?></td>
  	  	</tr>
         <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONCAT_WS('-',$inicueivaret,'010-0800-00') cuenta 
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos += 0;
			$totalcargos += $f->ivaretenido;
		?>
    	<tr>
    	  <td align="left">IVA RET <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right"><?=number_format($f->ivaretenido,2,'.',',');?></td>
    	  <td align="right">&nbsp;</td>
  	  	</tr>
        <?
			$f = ejecutarConsulta("SELECT SUM(IFNULL(gv.total,0)) total, CONCAT_WS('-',$inicuetot,'460-0001-00') cuenta
			FROM guiasventanilla gv
			INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
			WHERE gv.idsucursalorigen = $idsucursalorigen
			AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 0;",$l);
			$totalabonos += 0;
			$totalcargos += $f->total;
		?>
    	<tr>
    	  <td align="left">Total <?=$prefijosucursal?></td>
    	  <td align="center"><?=$f->cuenta?></td>
    	  <td align="right"><?=number_format($f->total,2,'.',',');?></td>
    	  <td align="right">&nbsp;</td>
  	  	</tr>
        <?
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
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'EAD', SUM(IFNULL(gv.tcostoead,0)) ead, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0002-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'REC', SUM(IFNULL(gv.trecoleccion,0)) rec, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0003-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'SEGURO', SUM(IFNULL(gv.tseguro,0)) seguro, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'DESCUENTO', SUM(IFNULL(gv.ttotaldescuento,0)) descuento, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0005-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'ADICIONAL', SUM(IFNULL(gv.texcedente,0)+IFNULL(gv.tcombustible,0)) adicional, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'OTROS', SUM(IFNULL(gv.totros,0)) otros, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'IVA', SUM(IFNULL(gv.tiva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'IVARETENIDO', SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE gv.idsucursalorigen = $idsucursalorigen
		AND gv.fecha BETWEEN $fechainicio AND $fechafinal AND gv.tipoflete = 1 GROUP BY gv.idsucursaldestino
		UNION
		SELECT 'TOTAL', SUM(IFNULL(gv.total,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,'460-0001-00') USING utf8) cuenta, cs.prefijo
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
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#F00;"; } ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
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
		?>
    	<tr>
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
		 <?
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
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT EAD', SUM(IFNULL(f.ead,0)) ead, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0002-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT REC', SUM(IFNULL(f.recoleccion,0)) rec, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0003-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT SEGURO', SUM(IFNULL(f.seguro,0)) seguro, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0004-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT DESCUENTO', SUM(IFNULL(f.totaldescuento,0)) descuento, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0005-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT ADICIONAL', SUM(IFNULL(f.excedente,0)+IFNULL(f.combustible,0)) adicional, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT OTROS', SUM(IFNULL(f.otros,0)) otros, CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0007-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT IVA', SUM(IFNULL(f.iva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT IVARETENIDO', SUM(IFNULL(f.ivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0
		UNION
		SELECT 'FACT TOTAL', SUM(IFNULL(f.total,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,'460-0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.total > 0;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM movimientosfacturacion order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#F00;"; } ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
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
		?>
    	<tr>
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
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0
		UNION
		SELECT 'FACT ADICIONAL', SUM(IFNULL(f.sobexcedente,0)+IFNULL(f.combustible,0)) adicional, 
		CONVERT(CONCAT_WS('-',$inicuecom,cs.idsucursal,'0006-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0
		UNION
		SELECT 'FACT IVA', SUM(IFNULL(f.sobiva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0
		UNION
		SELECT 'FACT IVARETENIDO', SUM(IFNULL(f.sobivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0
		UNION
		SELECT 'FACT TOTAL', SUM(IFNULL(f.sobmontoafacturar,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,'460-0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.sobmontoafacturar > 0;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM movimientosfactsobseguro order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#F00;"; } ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
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
		?>
    	<tr>
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
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0
		UNION
		SELECT 'FACT IVA', SUM(IFNULL(f.otrosiva,0)) iva, CONVERT(CONCAT_WS('-',$inicueivatra,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0
		UNION
		SELECT 'FACT IVARETENIDO', SUM(IFNULL(f.otrosivaretenido,0)) ivaretenido, 
		CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0
		UNION
		SELECT 'FACT TOTAL', SUM(IFNULL(f.otrosmontofacturar,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,'460-0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.otrosmontofacturar > 0;";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM movimientosfactotros order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#F00;"; } ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
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
		?>
    	<tr>
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
		
        
		 $s = "DROP TEMPORARY TABLE IF EXISTS guiascancelaciones;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `guiascancelaciones` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO guiascancelaciones
		(descripcion,cantidad,cuenta,prefijo)
		SELECT 'CANC OTROS', SUM(IFNULL(gv.subtotal,0)) otros, CONVERT(CONCAT_WS('-',$inicuecansub,cs.idsucursal,'0007-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal=$idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal
		UNION
		SELECT 'CANC IVA', SUM(IFNULL(gv.tiva,0)) iva, CONVERT(CONCAT_WS('-',$inicuecaniva,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal=$idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal
		UNION
		SELECT 'CANC IVARETENIDO', SUM(IFNULL(gv.ivaretenido,0)) ivaretenido, CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal=$idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal
		UNION
		SELECT 'CANC TOTAL', SUM(IFNULL(gv.total,0)) total, CONVERT(CONCAT_WS('-',$inicuetot,'460-0001-00') USING utf8) cuenta, cs.prefijo
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		LEFT JOIN historial_cancelacionysustitucion hc ON gv.id = hc.guia
		WHERE gv.estado = 'CANCELADO' AND IF(ISNULL(hc.id),gv.idsucursalorigen = $idsucursalorigen, hc.sucursal=$idsucursalorigen) 
		AND IF(ISNULL(hc.id),gv.fecha,hc.fecha)  BETWEEN $fechainicio AND $fechafinal;";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "SELECT * FROM guiascancelaciones order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#F00;"; } ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
                </tr>
                <?
				$tc = 0;
				$ta = 0;
			}
			$prefijo = $f->prefijo;
			
			if($f->descripcion=='CANC IVARETENIDO' || $f->descripcion=='CANC TOTAL'){
				$totalabonos += $f->cantidad;
				$ta += $f->cantidad;
			}else{
				$totalcargos += $f->cantidad;
				$tc += $f->cantidad;
			}
		?>
    	<tr>
    	  <td align="left"><?=$f->descripcion?> <?=$f->prefijo?></td>
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
		
		 $s = "DROP TEMPORARY TABLE IF EXISTS guiascancelaciones;";
		mysql_query($s,$l) or die($s);

		$s = "CREATE TEMPORARY TABLE `guiascancelaciones` (
		  `id` DOUBLE NOT NULL AUTO_INCREMENT,
		  `descripcion` VARCHAR(50) DEFAULT NULL,
		  `cantidad` DOUBLE DEFAULT NULL,
		  `cuenta` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
		  `prefijo` VARCHAR(50) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO guiascancelaciones
		(descripcion,cantidad,cuenta,prefijo)
		SELECT 'CANC FACT SUBTOTAL', SUM(IFNULL(f.otrossubtotal,0)+IFNULL(f.sobsubtotal,0)+IFNULL(f.subtotal,0)) subtotal, 
		CONVERT(CONCAT_WS('-',$inicuecansub,cs.idsucursal,'0007-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		UNION
		SELECT 'CANC FACT IVA', SUM(IFNULL(f.otrosiva,0)+IFNULL(f.sobiva,0)+IFNULL(f.iva,0)) iva, 
		CONVERT(CONCAT_WS('-',$inicuecaniva,'001-0000-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		UNION
		SELECT 'CANC FACT IVARETENIDO', SUM(IFNULL(f.otrosivaretenido,0)+IFNULL(f.sobivaretenido,0)+IFNULL(f.ivaretenido,0)) ivaretenido, 
		CONVERT(CONCAT_WS('-',$inicueivaret,'010-0800-00') USING utf8) cuenta , cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO'
		UNION
		SELECT 'CANC FACT TOTAL', SUM(IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0)+IFNULL(f.total,0)) total, 
		CONVERT(CONCAT_WS('-',$inicuetot,'460-0001-00') USING utf8) cuenta, cs.prefijo
		FROM facturacion f
		INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
		WHERE f.idsucursal = $idsucursalorigen
		AND f.fecha BETWEEN $fechainicio AND $fechafinal AND f.tipoguia<>'ventanilla' AND f.facturaestado = 'CANCELADO';";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);
		
		$s = "SELECT * FROM guiascancelaciones order by prefijo";
		$r = mysql_query($s,$l) or die($s);
		$prefijo = "";
		$tc = 0;
		$ta = 0;
		while($f=mysql_fetch_object($r)){
			if($prefijo!="" && $prefijo!=$f->prefijo){
				?>
                <tr style="border:2px #F00 solid;<? if(round($tc,2)!=round($ta,2)){ echo "background-color:#F00;"; } ?>">
                  <td colspan="2" align="right">Total</td>
                  <td align="right"><?=number_format($tc,2,'.',',');?></td>
                  <td align="right"><?=number_format($ta,2,'.',',');?></td>
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
		?>
    	<tr>
    	  <td align="left"><?=$f->descripcion?> <?=$f->prefijo?></td>
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
    	  <td colspan="2" align="right">Total</td>
    	  <td align="right"><?=number_format($totalcargos,2,'.',',');?></td>
    	  <td align="right"><?=number_format($totalabonos,2,'.',',');?></td>
  	  	</tr>
    </table>
</body>
</html>