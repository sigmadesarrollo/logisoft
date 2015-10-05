<?	session_start();
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

	if($_GET[accion]==1){//PRINCIPAL
		$s = "SELECT vendedor FROM(
			SELECT gc.vendedor FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
			WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
			WHERE gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
			LEFT JOIN catalogocliente cc ON ge.clienteconvenio=cc.id
			WHERE ge.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			LEFT JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='NO' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
			GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			LEFT JOIN catalogocliente cc ON f.cliente=cc.id INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
			WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.estadocobranza='C'
			GROUP BY gc.vendedor
		)t GROUP BY vendedor ";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT $totalregistros AS tvendedor,FORMAT(SUM(IFNULL(flete,0)),2) flete,FORMAT(SUM(IFNULL(vtascobradas,0)),2) vtascobradas
		FROM(
			SELECT gc.vendedor AS idvendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,
			SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.tflete-IFNULL(gv.ttotaldescuento,0),0)) vtascobradas
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
			WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor AS idvendedor,0 AS flete,SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.tflete-IFNULL(gv.ttotaldescuento,0),0)) vtascobradas
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
			WHERE gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor AS idvendedor,SUM(ge.tflete-IFNULL(ge.ttotaldescuento,0)) flete,0 AS vtascobradas
			FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
			LEFT JOIN catalogocliente cc ON ge.clienteconvenio=cc.id
			WHERE ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor AS idvendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) flete,
			SUM(f.flete-IFNULL(f.totaldescuento,0)) AS vtascobradas 
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			LEFT JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='NO' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
			GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor AS idvendedor,0 AS flete,SUM(f.flete-IFNULL(f.totaldescuento,0)) vtascobradas  
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			LEFT JOIN catalogocliente cc ON f.cliente=cc.id INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
			WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.estadocobranza='C' GROUP BY gc.vendedor
		UNION
			SELECT gc.vendedor AS idvendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,0 AS vtascobradas  
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			LEFT JOIN catalogocliente cc ON f.cliente=cc.id 
			WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' GROUP BY gc.vendedor
		)t ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT prefijoorigen,idvendedor,vendedor,SUM(IFNULL(flete,0)) flete,SUM(IFNULL(vtascobradas,0)) vtascobradas
		FROM(
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,
			SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.tflete-IFNULL(gv.ttotaldescuento,0),0)) vtascobradas
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
			WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,0 AS flete,
			SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.tflete-IFNULL(gv.ttotaldescuento,0),0)) vtascobradas
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
			WHERE gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,SUM(ge.tflete-IFNULL(ge.ttotaldescuento,0)) flete,
			0 AS vtascobradas
			FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON ge.clienteconvenio=cc.id
			WHERE ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO' GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) flete,
			SUM(f.flete-IFNULL(f.totaldescuento,0)) AS vtascobradas 
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='NO' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
			GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,0 AS flete,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS vtascobradas 
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
			INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
			WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.estadocobranza='C'
			GROUP BY gc.vendedor
		UNION
			SELECT cs.prefijo AS prefijoorigen,gc.vendedor AS idvendedor,gc.nvendedor AS vendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,0 AS vtascobradas 
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
			AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
			GROUP BY gc.vendedor
		)t GROUP BY idvendedor ORDER BY prefijoorigen $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijoorigen = cambio_texto($f->prefijoorigen);
			$f->vendedor = cambio_texto($f->vendedor);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==2){//VentasVendedor
		$s = "SET lc_time_names = 'es_MX'";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT idcliente FROM(
			SELECT gv.idremitente AS idcliente,gv.clienteconvenio AS convenio
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			INNER JOIN catalogocliente cc ON gv.idremitente=cc.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
			AND gv.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), 
			INTERVAL -2 MONTH) AND '".cambiaf_a_mysql($_GET[fechafin])."' AND gv.id NOT LIKE '888%' AND gv.id NOT LIKE '777%'
			GROUP BY gv.idremitente
		UNION
			SELECT ge.idremitente AS idcliente,ge.clienteconvenio AS convenio
			FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
			INNER JOIN catalogocliente cc ON ge.idremitente=cc.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO'
			AND ge.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), 
			INTERVAL -2 MONTH) AND '".cambiaf_a_mysql($_GET[fechafin])."' AND ge.id NOT LIKE '888%' AND ge.id NOT LIKE '777%'
			GROUP BY ge.idremitente		
		UNION
			SELECT f.cliente AS idcliente,gc.folio AS convenio
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND gc.estadoconvenio='ACTIVADO' AND f.tipoguia='empresarial' AND	ISNULL(f.fechacancelacion)
			AND f.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), 
			INTERVAL -2 MONTH)  AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.folio NOT LIKE '888%' AND f.folio NOT LIKE '777%'
			GROUP BY f.cliente
		)t GROUP BY idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(tot3),2) AS tot3,mes3,FORMAT(SUM(tot2),2) AS tot2,mes2,FORMAT(SUM(tot1),2) AS tot1,mes1	FROM(
			SELECT gc.fecha,gv.idremitente AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS nombrecliente,
			gv.clienteconvenio AS convenio,
			SUM(IF(MONTH(gv.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
			YEAR(gv.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
			(gv.tflete-IFNULL(gv.ttotaldescuento,0)),0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
			SUM(IF(MONTH(gv.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
			YEAR(gv.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
			(gv.tflete-IFNULL(gv.ttotaldescuento,0)),0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
			SUM(IF(MONTH(gv.fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
			YEAR(gv.fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
			(gv.tflete-IFNULL(gv.ttotaldescuento,0)),0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			INNER JOIN catalogocliente cc ON gv.idremitente=cc.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
			AND gv.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), 
			INTERVAL -2 MONTH) AND '".cambiaf_a_mysql($_GET[fechafin])."' AND gv.id NOT LIKE '888%' AND gv.id NOT LIKE '777%'
			GROUP BY gv.idremitente
		UNION
			SELECT gc.fecha,ge.idremitente AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS nombrecliente,
			ge.clienteconvenio AS convenio,
			SUM(IF(MONTH(ge.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
			YEAR(ge.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
			(ge.tflete-IFNULL(ge.ttotaldescuento,0)),0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
			SUM(IF(MONTH(ge.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
			YEAR(ge.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
			(ge.tflete-IFNULL(ge.ttotaldescuento,0)),0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
			SUM(IF(MONTH(ge.fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
			YEAR(ge.fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
			(ge.tflete-IFNULL(ge.ttotaldescuento,0)),0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
			FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
			INNER JOIN catalogocliente cc ON ge.idremitente=cc.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO' 
			AND ge.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), 
			INTERVAL -2 MONTH) AND '".cambiaf_a_mysql($_GET[fechafin])."' AND ge.id NOT LIKE '888%' AND ge.id NOT LIKE '777%'
			GROUP BY ge.idremitente		
		UNION
			SELECT gc.fecha,f.cliente AS idcliente,f.nombrecliente,gc.folio AS convenio,
			SUM(IF(MONTH(f.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
			YEAR(f.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
			(f.flete-IFNULL(f.totaldescuento,0)),0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
			SUM(IF(MONTH(f.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
			YEAR(f.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
			(f.flete-IFNULL(f.totaldescuento,0)),0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
			SUM(IF(MONTH(f.fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
			YEAR(f.fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
			(f.flete-IFNULL(f.totaldescuento,0)),0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND gc.estadoconvenio='ACTIVADO' AND f.tipoguia!='ventanilla' AND
			ISNULL(f.fechacancelacion) AND f.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',
			MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), INTERVAL -2 MONTH) AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.folio NOT LIKE '888%' 
			AND f.folio NOT LIKE '777%' GROUP BY f.cliente
		)t ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fechaconvenio,idcliente,nombrecliente AS cliente,convenio,
		SUM(tot3) tot3,mes3,SUM(tot2) tot2,mes2,SUM(tot1) tot1,mes1	FROM(
			SELECT gc.fecha,gv.idremitente AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS nombrecliente,
			gv.clienteconvenio AS convenio,
			SUM(IF(MONTH(gv.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
			YEAR(gv.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
			(gv.tflete-IFNULL(gv.ttotaldescuento,0)),0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
			SUM(IF(MONTH(gv.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
			YEAR(gv.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
			(gv.tflete-IFNULL(gv.ttotaldescuento,0)),0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
			SUM(IF(MONTH(gv.fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
			YEAR(gv.fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
			(gv.tflete-IFNULL(gv.ttotaldescuento,0)),0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
			FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
			INNER JOIN catalogocliente cc ON gv.idremitente=cc.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO'
			AND gv.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), 
			INTERVAL -2 MONTH) AND '".cambiaf_a_mysql($_GET[fechafin])."' AND gv.id NOT LIKE '888%' AND gv.id NOT LIKE '777%'
			GROUP BY gv.idremitente
		UNION
			SELECT gc.fecha,ge.idremitente AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS nombrecliente,
			ge.clienteconvenio AS convenio,
			SUM(IF(MONTH(ge.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
			YEAR(ge.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
			(ge.tflete-IFNULL(ge.ttotaldescuento,0)),0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
			SUM(IF(MONTH(ge.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
			YEAR(ge.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
			(ge.tflete-IFNULL(ge.ttotaldescuento,0)),0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
			SUM(IF(MONTH(ge.fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
			YEAR(ge.fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
			(ge.tflete-IFNULL(ge.ttotaldescuento,0)),0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
			FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
			INNER JOIN catalogocliente cc ON ge.idremitente=cc.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO' 
			AND ge.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), 
			INTERVAL -2 MONTH) AND '".cambiaf_a_mysql($_GET[fechafin])."' AND ge.id NOT LIKE '888%' AND ge.id NOT LIKE '777%'
			GROUP BY ge.idremitente		
		UNION
			SELECT gc.fecha,f.cliente AS idcliente,f.nombrecliente,gc.folio AS convenio,
			SUM(IF(MONTH(f.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
			YEAR(f.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
			(f.flete-IFNULL(f.totaldescuento,0)),0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
			SUM(IF(MONTH(f.fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
			YEAR(f.fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
			(f.flete-IFNULL(f.totaldescuento,0)),0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
			SUM(IF(MONTH(f.fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
			YEAR(f.fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
			(f.flete-IFNULL(f.totaldescuento,0)),0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
			FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
			INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
			WHERE gc.vendedor=".$_GET[vendedor]." AND gc.estadoconvenio='ACTIVADO' AND f.tipoguia!='ventanilla' AND
			ISNULL(f.fechacancelacion) AND f.fecha BETWEEN ADDDATE(CONCAT(YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),'-',
			MONTH('".cambiaf_a_mysql($_GET[fechafin])."'),'-01'), INTERVAL -2 MONTH) AND '".cambiaf_a_mysql($_GET[fechafin])."' 
			AND f.folio NOT LIKE '888%' AND f.folio NOT LIKE '777%' GROUP BY f.cliente
		)t GROUP BY idcliente ORDER BY convenio	$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->cliente);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==3){//Ventas
		$s = "SELECT guia FROM(
		SELECT gv.clienteconvenio AS idcliente,gv.id AS guia
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id INNER JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO' GROUP BY gv.id
		UNION
		SELECT ge.clienteconvenio AS idcliente,ge.id AS guia
		FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id INNER JOIN catalogocliente cc ON ge.clienteconvenio=cc.id
		WHERE ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gc.vendedor='".$_GET[vendedor]."' AND (ISNULL(ge.factura) OR ge.factura=0) AND gc.estadoconvenio='ACTIVADO' GROUP BY ge.id
		UNION
		SELECT f.cliente AS idcliente,fd.folio AS guia FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura LEFT JOIN guiasempresariales ge ON fd.folio=ge.id 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND gc.vendedor='".$_GET[vendedor]."' 
		AND f.tipoguia!='ventanilla' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO' GROUP BY fd.folio)t ORDER BY guia ";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(flete),2) flete FROM(
		SELECT gv.id AS guia,(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO' GROUP BY gv.id
		UNION
		SELECT ge.id AS guia,(ge.tflete-IFNULL(ge.ttotaldescuento,0)) AS flete
		FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id LEFT JOIN catalogocliente cc ON ge.clienteconvenio=cc.id
		WHERE ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gc.vendedor='".$_GET[vendedor]."' AND (ISNULL(ge.factura) OR ge.factura=0) AND gc.estadoconvenio='ACTIVADO' GROUP BY ge.id
		UNION
		SELECT fd.folio AS guia,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) flete FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura LEFT JOIN guiasempresariales ge ON fd.folio=ge.id 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND gc.vendedor='".$_GET[vendedor]."' 
		AND f.tipoguia!='ventanilla' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO' GROUP BY fd.folio)t ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,idcliente,cliente,guia,prefijodestino,flete,estado FROM(
		SELECT gv.fecha,gv.clienteconvenio AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
		gv.id AS guia,cs.prefijo AS prefijodestino,(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete,gv.estado
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND /* ISNULL(gv.factura) AND */
		gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO' GROUP BY gv.id
		UNION
		SELECT ge.fecha,ge.clienteconvenio AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,
		ge.id AS guia,cs.prefijo AS prefijodestino,(ge.tflete-IFNULL(ge.ttotaldescuento,0)) AS flete,ge.estado
		FROM guiasempresariales ge INNER JOIN generacionconvenio gc ON ge.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id LEFT JOIN catalogocliente cc ON ge.clienteconvenio=cc.id
		WHERE ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(ge.factura) AND gc.estadoconvenio='ACTIVADO' GROUP BY ge.id
		UNION
		SELECT f.fecha,f.cliente AS idcliente,CONCAT(gc.nombre,' ',gc.apaterno,' ',gc.amaterno) AS cliente,
		IF(fd.tipoguia='PREPAGADA',fd.factura,fd.folio) AS guia,cs.prefijo AS prefijodestino,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) flete,ge.estado 
		FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura LEFT JOIN guiasempresariales ge ON fd.folio=ge.id 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND gc.vendedor='".$_GET[vendedor]."' 
		AND f.tipoguia!='ventanilla' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO' GROUP BY fd.folio
		)t ORDER BY fecha $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->prefijodestino = cambio_texto($f->prefijodestino);
			$f->cliente = cambio_texto($f->cliente);
			$f->estado = cambio_texto($f->estado);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==4){//Ventas Cobradas
		$s ="SELECT guia FROM(
		SELECT gv.id AS guia FROM guiasventanilla gv 
		INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente	LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.tipoflete=0 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO'
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' GROUP BY gv.id
		UNION
		SELECT gv.id AS guia FROM guiasventanilla gv 
		INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente	LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO'
		AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' GROUP BY gv.id
		UNION
		SELECT fd.folio AS guia	FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.credito='NO' 
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO' AND f.tipoguia!='ventanilla' GROUP BY fd.folio
		UNION
		SELECT fd.folio AS guia FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.credito='SI' 
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO'  AND f.estadocobranza='C' GROUP BY fd.folio )t 
		ORDER BY guia ";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(flete),2) flete,FORMAT(SUM(comision),2) comision FROM(
		SELECT gv.id AS guia,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete,
		SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision FROM guiasventanilla gv 
		INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente	LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.tipoflete=0 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO'
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' GROUP BY gv.id
		UNION
		SELECT gv.id AS guia,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete,
		SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision 
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente	LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO'
		AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' GROUP BY gv.id
		UNION
		SELECT fd.folio AS guia,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) AS flete,
		SUM((fd.flete-IFNULL(fd.cantidaddescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision
		FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.credito='NO' 
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO' AND f.tipoguia!='ventanilla' GROUP BY fd.folio
		UNION
		SELECT fd.folio AS guia,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) AS flete,
		SUM((fd.flete-IFNULL(fd.cantidaddescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision
		FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.credito='SI' 
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO'  AND f.estadocobranza='C' GROUP BY fd.folio )t ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,idcliente,cliente,guia,flete,comision,porcentaje FROM(
		SELECT gv.fecha,gv.clienteconvenio AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,gv.id AS guia,
		SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete,SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision,
		IFNULL(cc.comision,0) porcentaje
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente	
		LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.tipoflete=0 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO'
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY gv.id
		UNION
		SELECT gv.fecha,gv.clienteconvenio AS idcliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) AS cliente,gv.id AS guia,
		SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) AS flete,SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision,
		IFNULL(cc.comision,0) porcentaje
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente	
		LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.vendedor='".$_GET[vendedor]."' AND gc.estadoconvenio='ACTIVADO'
		AND gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY gv.id
		UNION
		SELECT f.fecha,f.cliente AS idcliente,CONCAT(gc.nombre,' ',gc.apaterno,' ',gc.amaterno) AS cliente,
		IF(fd.tipoguia='PREPAGADA',fd.factura,fd.folio) AS guia,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) flete,
		SUM((fd.flete-IFNULL(fd.cantidaddescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision,
		IFNULL(cc.comision,0) porcentaje
		FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.credito='NO' 
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO' AND f.tipoguia!='ventanilla'
		GROUP BY fd.folio
		UNION
		SELECT f.fecha,f.cliente AS idcliente,CONCAT(gc.nombre,' ',gc.apaterno,' ',gc.amaterno) AS cliente,
		IF(fd.tipoguia='PREPAGADA',fd.factura,fd.folio) AS guia,SUM(fd.flete-IFNULL(fd.cantidaddescuento,0)) flete,
		SUM((fd.flete-IFNULL(fd.cantidaddescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision,
		IFNULL(cc.comision,0) porcentaje
		FROM facturacion f INNER JOIN facturadetalle fd ON f.folio=fd.factura INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND f.credito='SI' 
		AND gc.vendedor='".$_GET[vendedor]."' AND ISNULL(f.fechacancelacion) AND gc.estadoconvenio='ACTIVADO'  AND f.estadocobranza='C'
		GROUP BY fd.folio )t ORDER BY fecha $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->cliente = cambio_texto($f->cliente);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==5){//TotalVentasCobradas
		$s = "SELECT vendedor FROM(
		SELECT gc.vendedor FROM guiasventanilla gv 
		INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.tipoflete=0 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
		GROUP BY gc.vendedor
		UNION
		SELECT gc.vendedor FROM guiasventanilla gv 
		INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
		GROUP BY gc.vendedor
		UNION
		SELECT gc.vendedor FROM facturacion f 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND f.credito='NO' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
		GROUP BY gc.vendedor
		UNION
		SELECT gc.vendedor FROM facturacion f 
		INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
		WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.estadocobranza='C'
		GROUP BY gc.vendedor )t GROUP BY vendedor";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT vendedor,FORMAT(SUM(flete),2) flete,FORMAT(SUM(comision),2) comision FROM(
		SELECT gc.vendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) comision 
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.tipoflete=0 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
		GROUP BY gc.vendedor
		UNION
		SELECT gc.vendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,
		SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) comision	
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
		GROUP BY gc.vendedor
		UNION
		SELECT gc.vendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,
		SUM((f.flete-IFNULL(f.totaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision 
		FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND f.credito='NO' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
		GROUP BY gc.vendedor
		UNION
		SELECT gc.vendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,
		SUM((f.flete-IFNULL(f.totaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision 
		FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
		WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.estadocobranza='C'
		GROUP BY gc.vendedor )t ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT prefijo AS prefijosucursal,vendedor AS idvendedor,nvendedor AS vendedor,SUM(flete) flete,SUM(comision) comision
		FROM(
		SELECT cs.prefijo,gc.vendedor,gc.nvendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,
		SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) comision
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id	LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.tipoflete=0 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
		GROUP BY gc.vendedor
		UNION
		SELECT cs.prefijo,gc.vendedor,gc.nvendedor,SUM(gv.tflete-IFNULL(gv.ttotaldescuento,0)) flete,
		SUM((gv.tflete-IFNULL(gv.ttotaldescuento,0))*(IFNULL(cc.comision,0)/100)) comision
		FROM guiasventanilla gv INNER JOIN generacionconvenio gc ON gv.clienteconvenio=gc.idcliente
		INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id	LEFT JOIN catalogocliente cc ON gv.clienteconvenio=cc.id
		WHERE gv.fechaentrega BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.tipoflete=1 AND gv.condicionpago=0 AND gv.estado!='CANCELADO' AND gc.estadoconvenio='ACTIVADO' 
		GROUP BY gc.vendedor
		UNION
		SELECT cs.prefijo,gc.vendedor,gc.nvendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,
		SUM((f.flete-IFNULL(f.totaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision
		FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
		INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND f.credito='NO' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='ventanilla' 
		GROUP BY gc.vendedor
		UNION
		SELECT cs.prefijo,gc.vendedor,gc.nvendedor,SUM(f.flete-IFNULL(f.totaldescuento,0)) AS flete,
		SUM((f.flete-IFNULL(f.totaldescuento,0))*(IFNULL(cc.comision,0)/100)) AS comision
		FROM facturacion f INNER JOIN generacionconvenio gc ON f.cliente=gc.idcliente
		INNER JOIN catalogosucursal cs ON gc.sucursal=cs.id LEFT JOIN catalogocliente cc ON f.cliente=cc.id
		INNER JOIN facturacion_fechapago ffp ON f.folio=ffp.factura
		WHERE ffp.fechapago BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND f.credito='SI' AND gc.estadoconvenio='ACTIVADO' AND ISNULL(f.fechacancelacion) AND f.estadocobranza='C'
		GROUP BY gc.vendedor )t GROUP BY vendedor ORDER BY prefijo $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->prefijosucursal = cambio_texto($f->prefijosucursal);
			$f->vendedor = cambio_texto($f->vendedor);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==6){//Obtener Meses
		$s = "SELECT 
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
		$r = mysql_query($s,$l)or die($s); 
		$registros = array();		
		if(mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					$f->mes1 = cambio_texto($f->mes1);
					$f->mes2 = cambio_texto($f->mes2);
					$f->mes3 = cambio_texto($f->mes3);
					$registros[] = $f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}
?>