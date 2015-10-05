<?	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
	$sql="SELECT sucursal,clavevendedor,vendedor,IFNULL(SUM(ventas),0) AS ventas,
	IFNULL(SUM(ventascobradas),0)AS ventascobradas FROM(
	SELECT cs.descripcion AS sucursal,gv.idvendedorconvenio AS clavevendedor,
	gv.nvendedorconvenio AS vendedor,IFNULL(SUM(gv.tflete-gv.ttotaldescuento+gv.texcedente),0)AS ventas,
	IFNULL(vc.ventascobradas,0) AS ventascobradas FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.sucursalconvenio=cs.id
	LEFT JOIN
	(SELECT gc.vendedor,SUM(ld.importe)AS ventascobradas FROM liquidacioncobranza l 
				INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
				INNER JOIN generacionconvenio gc ON ld.cliente=gc.idcliente
				WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 
				BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
				GROUP BY gc.vendedor)vc ON gv.idvendedorconvenio=vc.vendedor
	WHERE gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'
	GROUP BY cs.descripcion,gv.idvendedorconvenio
UNION ALL
	SELECT cs.descripcion AS sucursal,gv.idvendedorconvenio AS clavevendedor,
	gv.nvendedorconvenio AS vendedor,IFNULL(SUM(gv.tflete-gv.ttotaldescuento+gv.texcedente),0)AS ventas,
	IFNULL(vc.ventascobradas,0) AS ventascobradas FROM guiasempresariales gv
	INNER JOIN catalogosucursal cs ON gv.sucursalconvenio=cs.id
	LEFT JOIN
	(SELECT gc.vendedor,SUM(ld.importe)AS ventascobradas FROM liquidacioncobranza l 
				INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
				INNER JOIN generacionconvenio gc ON ld.cliente=gc.idcliente
				WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 
				BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
				GROUP BY gc.vendedor)vc ON gv.idvendedorconvenio=vc.vendedor
	WHERE gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'
	GROUP BY cs.descripcion,gv.idvendedorconvenio 
)ventasporvendedor GROUP BY clavevendedor ORDER BY vendedor
	LIMIT ".$_GET[inicio].",30";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$f->sucursal=cambio_texto($f->sucursal);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion]==2){
		$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y') AS fechaguia,guia,destino,cliente,
		nombrecliente,valorfleteneto, estado FROM(
		SELECT gv.fecha AS fechaguia,gv.id AS guia, 
		cs.descripcion AS destino,cc.id AS cliente,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
		IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, gv.estado
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
		INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino
		WHERE gv.idvendedorconvenio='" .$_GET[idvendedor]."' AND gv.estado<>'CANCELADO' AND gv.fecha BETWEEN 
		'" .cambiaf_a_mysql($_GET[fecha])."' and 
		'".cambiaf_a_mysql($_GET[fecha2])."'
	UNION ALL
		SELECT gv.fecha AS fechaguia,gv.id AS guia, 
		cs.descripcion AS destino,cc.id AS cliente,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
		IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto,gv.estado FROM guiasempresariales gv
		INNER JOIN catalogocliente cc ON cc.id  = IF       (gv.tipoflete=0,gv.idremitente,gv.iddestino)
		INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino
		WHERE gv.idvendedorconvenio='" .$_GET[idvendedor]."' AND gv.estado<>'CANCELADO' AND gv.fecha BETWEEN 
		'" .cambiaf_a_mysql($_GET[fecha])."' and 
		'".cambiaf_a_mysql($_GET[fecha2])."' 
		)guiasventanillayempresariales LIMIT ".$_GET[inicio].",30";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$f->sucursal=cambio_texto($f->sucursal);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion]==3){
	$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y')AS fechaguia,guia,cliente,
	nombrecliente,valorfleteneto,comision FROM(
	SELECT gv.fecha AS fechaguia,gv.id AS guia,cc.id AS cliente,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, 
	(IFNULL(IFNULL(gv.tflete-gv.ttotaldescuento+gv.texcedente,0)*(com.comision/100),0))AS comision  
	FROM guiasventanilla gv
	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN 
	(SELECT ld.factura FROM liquidacioncobranza l 
	INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
	WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 
	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'
	GROUP BY ld.factura)ld ON gv.factura=ld.factura
	LEFT JOIN
	(SELECT cliente,comision FROM 
	(SELECT cc.id AS cliente, CASE  
	WHEN gc.tipoautorizacion='EN AUTORIZACION (ok)' THEN 
		(IFNULL(IF ((DATEDIFF(CURRENT_DATE,cc.fechainicioconvenio)/365)
		>(SELECT despues FROM configuradorpromociones),
		(SELECT porcentaje FROM configuradorpromociones),
		CASE cc.tipoclientepromociones
		WHEN 'A' THEN (SELECT porcA FROM configuradorpromociones) WHEN 'B' THEN (SELECT porcB FROM configuradorpromociones)END),0))
	WHEN gc.tipoautorizacion='EN AUTORIZACION (x)' THEN 
		(SELECT porcentaje FROM configuradorpromociones)
	END AS comision FROM catalogocliente cc 
	INNER JOIN generacionconvenio gc ON cc.id=gc.idcliente
	WHERE gc.vendedor='" .$_GET[clavevendedor]."'
	)comisiones WHERE cliente<>0 AND comision<>0 GROUP BY cliente ORDER BY cliente)com ON cc.id=com.cliente	
	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."'  AND gv.estado<>'CANCELADO'
	AND gv.fecha 
	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'
UNION ALL
	SELECT gv.fecha AS fechaguia,gv.id AS guia,cc.id AS cliente,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, 
	(IFNULL(IFNULL(gv.tflete-gv.ttotaldescuento+gv.texcedente,0)*(com.comision/100),0))AS comision  
	FROM guiasempresariales gv
	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN 
	(SELECT ld.factura FROM liquidacioncobranza l 
	INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
	WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 
	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'
	GROUP BY ld.factura)ld ON gv.factura=ld.factura
	LEFT JOIN
	(SELECT cliente,comision FROM 
	(SELECT cc.id AS cliente, CASE  
	WHEN gc.tipoautorizacion='EN AUTORIZACION (ok)' THEN 
		(IFNULL(IF ((DATEDIFF(CURRENT_DATE,cc.fechainicioconvenio)/365)
		>(SELECT despues FROM configuradorpromociones),
		(SELECT porcentaje FROM configuradorpromociones),
		CASE cc.tipoclientepromociones
		WHEN 'A' THEN (SELECT porcA FROM configuradorpromociones) WHEN 'B' THEN (SELECT porcB FROM configuradorpromociones)END),0))
	WHEN gc.tipoautorizacion='EN AUTORIZACION (x)' THEN 
		(SELECT porcentaje FROM configuradorpromociones)
	END AS comision FROM catalogocliente cc 
	INNER JOIN generacionconvenio gc ON cc.id=gc.idcliente
	WHERE gc.vendedor='" .$_GET[clavevendedor]."')comisiones WHERE cliente<>0 AND comision<>0 GROUP BY cliente ORDER BY cliente)
	com ON cc.id=com.cliente
	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."' AND gv.estado<>'CANCELADO'
	AND gv.fecha 
	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."')GuiasVentanillayGeren";
	$r=mysql_query($sql,$l)or die($sql); 
	$registros= array();
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$f->sucursal=cambio_texto($f->sucursal);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion]==4){
	
		$sql="SELECT fechaconvenio,clave,cliente,convenio,mes1,mes2,mes3,total,IFNULL(tipo,'')AS Tipo FROM (
SELECT DATE_FORMAT(gc.fecha, '%d/%m/%Y') AS fechaconvenio, cc.id AS clave,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	IFNULL(gc.cantidaddescuento + gc.consignaciondescuento,0)AS convenio,
	IFNULL(m1.ventas,0) AS mes1,
	IFNULL(m2.ventas,0) AS mes2,
	IFNULL(m3.ventas,0) AS mes3,
	(IFNULL(m1.ventas,0)+IFNULL(m2.ventas,0)+IFNULL(m3.ventas,0)) AS total,
	CASE WHEN gc.precioporkg=1 THEN 'P' 
	WHEN gc.precioporcaja=1 THEN 'K' 
	WHEN  gc.descuentosobreflete=1 THEN 'D' 
	END AS tipo
	FROM generacionconvenio gc
	INNER JOIN catalogocliente cc ON gc.idcliente=cc.id
	LEFT JOIN 
	(SELECT vendedor,cliente,SUM(ventas) AS ventas FROM (
		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 
		FROM guiasventanilla WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH))
		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio
	UNION 
		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 
		FROM guiasempresariales WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH))
		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio
	)ventasguiasventanillayempresariales GROUP BY vendedor,cliente)m1 ON gc.vendedor=m1.vendedor
	LEFT JOIN 
	(SELECT vendedor,cliente,SUM(ventas) AS ventas FROM (
		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 
		FROM guiasventanilla WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH))
		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio
	UNION  ALL
		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 
		FROM guiasempresariales WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH))
		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio
	)ventasguiasventanillayempresariales GROUP BY vendedor,cliente)m2 ON gc.vendedor=m2.vendedor
	LEFT JOIN 
	(SELECT vendedor,cliente,SUM(ventas) AS ventas FROM (
		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 
		FROM guiasventanilla WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH))
		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio
	UNION 
		SELECT idvendedorconvenio AS vendedor,clienteconvenio AS cliente,SUM(tflete-ttotaldescuento+texcedente)AS ventas 
		FROM guiasempresariales WHERE MONTH(fecha)=MONTH(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH))
		AND  YEAR(fecha)=YEAR(DATE_ADD('" .cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH)) GROUP BY idvendedorconvenio,clienteconvenio
	)ventasguiasventanillayempresariales GROUP BY vendedor,cliente)m3 ON gc.vendedor=m3.vendedor AND cc.id=m3.cliente
	WHERE gc.vendedor='" .$_GET[clavevendedor]."'
	)Tabla WHERE total>0 order by fechaconvenio LIMIT ".$_GET[inicio].",30";
	$r=mysql_query($sql,$l)or die($sql);	
	$registros= array();
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$f->sucursal=cambio_texto($f->sucursal);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion]==5){
	$sql="SELECT sucursal,clavevendedor,vendedor,IFNULL(SUM(ventascobradas),0)AS ventascobradas,
	IFNULL(SUM(comision),0) AS comision FROM(
	SELECT cs.descripcion AS sucursal,gv.idvendedorconvenio AS clavevendedor,
	gv.nvendedorconvenio AS vendedor,
	IFNULL(vc.ventascobradas,0) AS ventascobradas,
	(IFNULL(vc.ventascobradas,0)*(IFNULL(c.comision,0)/100))AS comision FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON gv.sucursalconvenio=cs.id
	LEFT JOIN
	(SELECT gc.vendedor,SUM(ld.importe)AS ventascobradas FROM liquidacioncobranza l 
				INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
				INNER JOIN generacionconvenio gc ON ld.cliente=gc.idcliente
				WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 
				BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  
				GROUP BY gc.vendedor)vc ON gv.idvendedorconvenio=vc.vendedor
	LEFT JOIN 
		(SELECT vendedor,comision FROM 
		(SELECT gc.vendedor, CASE  
		WHEN gc.tipoautorizacion='EN AUTORIZACION (ok)' THEN 
			(IFNULL(IF ((DATEDIFF(CURRENT_DATE,cc.fechainicioconvenio)/365)
			>(SELECT despues FROM configuradorpromociones),
			(SELECT porcentaje FROM configuradorpromociones),
			CASE cc.tipoclientepromociones
			WHEN 'A' THEN (SELECT porcA FROM configuradorpromociones) WHEN 'B' THEN (SELECT porcB FROM configuradorpromociones)END),0))
		WHEN gc.tipoautorizacion='EN AUTORIZACION (x)' THEN 
			(SELECT porcentaje FROM configuradorpromociones)
		END AS comision FROM catalogocliente cc 
		INNER JOIN generacionconvenio gc ON cc.id=gc.idcliente
		)Tabla WHERE vendedor<>0 AND comision<>0 GROUP BY vendedor ORDER BY vendedor)c ON gv.idvendedorconvenio=c.vendedor
			WHERE gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	GROUP BY cs.descripcion,gv.idvendedorconvenio
UNION ALL
	SELECT cs.descripcion AS sucursal,gv.idvendedorconvenio AS clavevendedor,
	gv.nvendedorconvenio AS vendedor,
	IFNULL(vc.ventascobradas,0) AS ventascobradas,(IFNULL(vc.ventascobradas,0)*(IFNULL(c.comision,0)/100))AS comision FROM guiasempresariales gv
	INNER JOIN catalogosucursal cs ON gv.sucursalconvenio=cs.id
	LEFT JOIN
	(SELECT gc.vendedor,SUM(ld.importe)AS ventascobradas FROM liquidacioncobranza l 
				INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
				INNER JOIN generacionconvenio gc ON ld.cliente=gc.idcliente
				WHERE l.estado='LIQUIDADO' AND l.fechaliquidacion 
				BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  
				GROUP BY gc.vendedor)vc ON gv.idvendedorconvenio=vc.vendedor
	LEFT JOIN 
		(SELECT vendedor,comision FROM 
		(SELECT gc.vendedor, CASE  
		WHEN gc.tipoautorizacion='EN AUTORIZACION (ok)' THEN 
			(IFNULL(IF ((DATEDIFF(CURRENT_DATE,cc.fechainicioconvenio)/365)
			>(SELECT despues FROM configuradorpromociones),
			(SELECT porcentaje FROM configuradorpromociones),
			CASE cc.tipoclientepromociones
			WHEN 'A' THEN (SELECT porcA FROM configuradorpromociones) WHEN 'B' THEN (SELECT porcB FROM configuradorpromociones)END),0))
		WHEN gc.tipoautorizacion='EN AUTORIZACION (x)' THEN 
			(SELECT porcentaje FROM configuradorpromociones)
		END AS comision FROM catalogocliente cc 
		INNER JOIN generacionconvenio gc ON cc.id=gc.idcliente
		)Tabla WHERE vendedor<>0 AND comision<>0 GROUP BY vendedor ORDER BY vendedor)c ON gv.idvendedorconvenio=c.vendedor
			WHERE gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	GROUP BY cs.descripcion,gv.idvendedorconvenio)ventasporvendedor GROUP BY clavevendedor ORDER BY vendedor
	LIMIT ".$_GET[inicio].",30";
	$r=mysql_query($sql,$l)or die($sql);	
	$registros= array();
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$f->sucursal=cambio_texto($f->sucursal);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}else if($_GET[accion]==6){
	
	}else if($_GET[accion]==7){
	
	}
	
?>