<? session_start();
	require_once("../../Conectar.php");
	$l=Conectarse('webpmm');
	
	if ($_GET[accion]==1){
	
$sql="SELECT sucursal,clavevendedor,vendedor,IFNULL(SUM(ventas),0) AS ventas,IFNULL(SUM(ventascobradas),0)AS ventascobradas FROM 
(
	SELECT cs.prefijo AS sucursal,gv.idvendedorconvenio AS clavevendedor,
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
	SELECT cs.prefijo AS sucursal,gv.idvendedorconvenio AS clavevendedor,
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
)ventasporvendedor GROUP BY clavevendedor ORDER BY vendedor";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$f->sucursal=cambio_texto($f->sucursal);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
		
}else if ($_GET[accion]==2){
	
	$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y') AS fechaguia,guia,destino,
	cliente,nombrecliente,valorfleteneto, estado FROM (
	SELECT gv.fecha AS fechaguia,gv.id AS guia,cs.descripcion AS destino,cc.id AS cliente,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, gv.estado
	FROM guiasventanilla gv
	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino
	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."' AND gv.estado<>'CANCELADO' AND 
	MONTH(gv.fecha)='".$_GET[mes]."' AND YEAR(gv.fecha)='".$_GET[ano]."'
UNION ALL
	SELECT gv.fecha AS fechaguia,gv.id AS guia, cs.descripcion AS destino,cc.id AS cliente,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, gv.estado 
	FROM guiasempresariales gv
	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino
	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."' AND gv.estado<>'CANCELADO' AND 
	MONTH(gv.fecha)='".$_GET[mes]."' AND YEAR(gv.fecha)='".$_GET[ano]."'
)guiasventanillayempresariales LIMIT ".$_GET[inicio].",30";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					$f->destino=cambio_texto($f->destino);
					$f->nombrecliente=cambio_texto($f->nombrecliente);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}

}else if ($_GET[accion]==3){

		$sql="SELECT YEAR('" .cambiaf_a_mysql($_GET[fecha])."')as ano,MONTH('" .cambiaf_a_mysql($_GET[fecha])."')as mes";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
}else if ($_GET[accion]==4){

		$sql="SELECT YEAR('" .cambiaf_a_mysql($_GET[fecha])."')as ano,MONTH('" .cambiaf_a_mysql($_GET[fecha])."')as mes";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}

}else if ($_GET[accion]==5){ 
 		$sql = "SELECT 
		CASE MONTH(DATE_ADD('".cambiaf_a_mysql($_GET[fecha])."', INTERVAL -2 MONTH))
		WHEN 1 THEN 'ENERO' WHEN 2 THEN 'FEBRERO' WHEN 3 THEN 'MARZO' WHEN 4 THEN 'ABRIL'
		WHEN 5 THEN 'MAYO' WHEN 6 THEN 'JUNIO' WHEN 7 THEN 'JULIO' WHEN 8 THEN 'AGOSTO'
		WHEN 9 THEN 'SEPTIEMBRE' WHEN 10 THEN 'OCTUBRE' WHEN 11 THEN 'NOVIEMBRE'
		WHEN 12 THEN 'DICIEMBRE' END AS mes1,
		CASE MONTH(DATE_ADD('".cambiaf_a_mysql($_GET[fecha])."', INTERVAL -1 MONTH))
		WHEN 1 THEN 'ENERO' WHEN 2 THEN 'FEBRERO' WHEN 3 THEN 'MARZO' WHEN 4 THEN 'ABRIL'
		WHEN 5 THEN 'MAYO' WHEN 6 THEN 'JUNIO' WHEN 7 THEN 'JULIO' WHEN 8 THEN 'AGOSTO'
		WHEN 9 THEN 'SEPTIEMBRE' WHEN 10 THEN 'OCTUBRE' WHEN 11 THEN 'NOVIEMBRE'
		WHEN 12 THEN 'DICIEMBRE' END AS mes2,
		CASE MONTH(DATE_ADD('".cambiaf_a_mysql($_GET[fecha])."', INTERVAL 0 MONTH))
		WHEN 1 THEN 'ENERO' WHEN 2 THEN 'FEBRERO' WHEN 3 THEN 'MARZO' WHEN 4 THEN 'ABRIL'
		WHEN 5 THEN 'MAYO' WHEN 6 THEN 'JUNIO' WHEN 7 THEN 'JULIO' WHEN 8 THEN 'AGOSTO'
		WHEN 9 THEN 'SEPTIEMBRE' WHEN 10 THEN 'OCTUBRE' WHEN 11 THEN 'NOVIEMBRE'
		WHEN 12 THEN 'DICIEMBRE' END AS mes3";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					$f->mes1=cambio_texto($f->mes1);
					$f->mes2=cambio_texto($f->mes2);
					$f->mes3=cambio_texto($f->mes3);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}

}else if ($_GET[accion]==6){

$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y')AS fechaguia,guia,cliente,nombrecliente,valorfleteneto,comision  FROM 
(
	SELECT gv.fecha AS fechaguia,gv.id AS guia,cc.id AS cliente,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, 
	(IFNULL(IFNULL(gv.tflete-gv.ttotaldescuento+gv.texcedente,0)*(com.comision/100),0))AS comision  
	FROM guiasventanilla gv
	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN 
	(SELECT ld.factura FROM liquidacioncobranza l 
	INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
	WHERE l.estado='LIQUIDADO' AND MONTH(l.fechaliquidacion )='".$_GET[mes]."' AND YEAR(l.fechaliquidacion )='".$_GET[ano]."'
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
	AND MONTH(gv.fecha)='".$_GET[mes]."' AND YEAR(gv.fecha)='".$_GET[ano]."'
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
	WHERE l.estado='LIQUIDADO' AND MONTH(l.fechaliquidacion )='".$_GET[mes]."' AND YEAR(l.fechaliquidacion )='".$_GET[ano]."'
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
	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."' AND gv.estado<>'CANCELADO'
	AND MONTH(gv.fecha)='".$_GET[mes]."' AND YEAR(gv.fecha)='".$_GET[ano]."'
)GuiasVentanillayGeren LIMIT ".$_GET[inicio].",30";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					$f->nombrecliente=cambio_texto($f->nombrecliente);
					$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}


}else if ($_GET[accion]==7){
	
	
	$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y') AS fechaguia,guia,destino,
	cliente,nombrecliente,valorfleteneto, estado FROM (
	SELECT gv.fecha AS fechaguia,gv.id AS guia,cs.descripcion AS destino,cc.id AS cliente,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, gv.estado
	FROM guiasventanilla gv
	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino
	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."' AND gv.estado<>'CANCELADO' AND 
	MONTH(gv.fecha)='".$_GET[mes]."' AND YEAR(gv.fecha)='".$_GET[ano]."'
UNION ALL
	SELECT gv.fecha AS fechaguia,gv.id AS guia, cs.descripcion AS destino,cc.id AS cliente,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, gv.estado 
	FROM guiasempresariales gv
	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN catalogosucursal cs ON cs.id	= gv.idsucursaldestino
	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."' AND gv.estado<>'CANCELADO' AND 
	MONTH(gv.fecha)='".$_GET[mes]."' AND YEAR(gv.fecha)='".$_GET[ano]."'
)guiasventanillayempresariales";
	$t = mysql_query($sql,$l) or die($sql);
	$tdes = mysql_num_rows($t);
	echo $tdes;
}else if ($_GET[accion]==8){

$sql="SELECT DATE_FORMAT(fechaguia, '%d/%m/%Y')AS fechaguia,guia,cliente,nombrecliente,valorfleteneto,comision  FROM 
(
	SELECT gv.fecha AS fechaguia,gv.id AS guia,cc.id AS cliente,
	CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,
	IFNULL((gv.tflete-gv.ttotaldescuento+gv.texcedente),0) AS valorfleteneto, 
	(IFNULL(IFNULL(gv.tflete-gv.ttotaldescuento+gv.texcedente,0)*(com.comision/100),0))AS comision  
	FROM guiasventanilla gv
	INNER JOIN catalogocliente cc ON cc.id  = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	INNER JOIN 
	(SELECT ld.factura FROM liquidacioncobranza l 
	INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
	WHERE l.estado='LIQUIDADO' AND MONTH(l.fechaliquidacion )='".$_GET[mes]."' AND YEAR(l.fechaliquidacion )='".$_GET[ano]."'
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
	AND MONTH(gv.fecha)='".$_GET[mes]."' AND YEAR(gv.fecha)='".$_GET[ano]."'
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
	WHERE l.estado='LIQUIDADO' AND MONTH(l.fechaliquidacion )='".$_GET[mes]."' AND YEAR(l.fechaliquidacion )='".$_GET[ano]."'
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
	WHERE gv.idvendedorconvenio='" .$_GET[clavevendedor]."' AND gv.estado<>'CANCELADO'
	AND MONTH(gv.fecha)='".$_GET[mes]."' AND YEAR(gv.fecha)='".$_GET[ano]."'
)GuiasVentanillayGeren";
	$t = mysql_query($sql,$l) or die($sql);
	$tdes = mysql_num_rows($t);
	echo $tdes;
}

?>