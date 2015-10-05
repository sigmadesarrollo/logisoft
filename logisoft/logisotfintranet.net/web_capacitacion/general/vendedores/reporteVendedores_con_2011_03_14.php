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
		$s = "SELECT * FROM reporte_vendedores_ventas
		WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND activo = 'S'
		GROUP BY prefijoorigen, idvendedor";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT COUNT(DISTINCT(idvendedor)) AS tvendedor, FORMAT(SUM(flete),2) AS flete, 
		FORMAT(SUM(IF(cobrado='S',flete,0)),2) AS vtascobradas 
		FROM reporte_vendedores_ventas
		WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$totales = json_encode($f);
		
		$s = "SELECT prefijoorigen, idvendedor, vendedor, SUM(flete) AS flete,
		SUM(IF(cobrado='S',flete,0)) AS vtascobradas 
		FROM reporte_vendedores_ventas
		WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND activo = 'S'
		GROUP BY prefijoorigen, idvendedor
		$limite";
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
		
		$s = "SELECT rv.fechaconvenio, rv.idcliente, rv.cliente, rv.convenio,
		SUM(IF(MONTH(fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
		YEAR(fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
		flete,0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
		SUM(IF(MONTH(fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
		YEAR(fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
		flete,0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
		SUM(IF(MONTH(fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
		YEAR(fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
		flete,0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
		FROM reporte_vendedores_ventas rv
		WHERE rv.idvendedor = ".$_GET[vendedor]." AND rv.activo = 'S'
		GROUP BY rv.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(tot1),2) AS tot1, FORMAT(SUM(tot2),2) AS tot2, FORMAT(SUM(tot3),2) AS tot3 FROM(
		SELECT SUM(IF(MONTH(fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
		YEAR(fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
		flete,0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
		SUM(IF(MONTH(fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
		YEAR(fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
		flete,0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
		SUM(IF(MONTH(fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
		YEAR(fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
		flete,0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
		FROM reporte_vendedores_ventas rv
		WHERE rv.idvendedor = ".$_GET[vendedor]." AND rv.activo = 'S'
		GROUP BY rv.idcliente) t";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT DATE_FORMAT(rv.fechaconvenio,'%d/%m/%Y') AS fechaconvenio, rv.idcliente, rv.cliente, rv.convenio,
		SUM(IF(MONTH(fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) AND
		YEAR(fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)),
		flete,0)) tot3, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -2 MONTH)) mes3,
		SUM(IF(MONTH(fecha) = MONTH(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) AND
		YEAR(fecha) = YEAR(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)),
		flete,0)) tot2, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -1 MONTH)) mes2,
		SUM(IF(MONTH(fecha) = MONTH('".cambiaf_a_mysql($_GET[fechafin])."') AND
		YEAR(fecha) = YEAR('".cambiaf_a_mysql($_GET[fechafin])."'),
		flete,0)) tot1, MONTHNAME(ADDDATE('".cambiaf_a_mysql($_GET[fechafin])."', INTERVAL -0 MONTH)) mes1
		FROM reporte_vendedores_ventas rv
		WHERE rv.idvendedor = ".$_GET[vendedor]." AND rv.activo = 'S'
		GROUP BY rv.idcliente
		$limite";
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
		$s = "SELECT DATE_FORMAT(v.fecha,'%d/%m/%Y') AS fecha, v.guia, IFNULL(v.prefijodestino,'') AS prefijodestino, 
		v.idcliente, v.cliente, v.flete, IF(v.guia=t.id,t.estado,IF(v.guia=s.id,s.estado,'')) AS estado
		FROM reporte_vendedores_ventas v
		INNER JOIN guiasventanilla t ON v.guia = t.id		
		LEFT JOIN solicitudguiasempresariales s ON v.guia = s.id
		WHERE v.idvendedor = '".$_GET[vendedor]."' AND v.activo = 'S'
		AND v.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND v.prefijoorigen = '$_GET[sucursal]'
		UNION
		SELECT DATE_FORMAT(v.fecha,'%d/%m/%Y') AS fecha, v.guia, IFNULL(v.prefijodestino,'') AS prefijodestino, 
		v.idcliente, v.cliente, v.flete, IF(v.guia=t.id,t.estado,IF(v.guia=s.id,s.estado,'')) AS estado
		FROM reporte_vendedores_ventas v
		INNER JOIN guiasempresariales t ON v.guia = t.id		
		LEFT JOIN solicitudguiasempresariales s ON v.guia = s.id
		WHERE v.idvendedor = '".$_GET[vendedor]."' AND v.activo = 'S'
		AND v.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND v.prefijoorigen = '$_GET[sucursal]'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(flete),2) AS flete FROM reporte_vendedores_ventas v
		WHERE idvendedor = '".$_GET[vendedor]."' AND activo = 'S'
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND v.prefijoorigen = '$_GET[sucursal]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT * FROM (
		SELECT DATE_FORMAT(v.fecha,'%d/%m/%Y') AS fecha, v.guia, IFNULL(v.prefijodestino,'') AS prefijodestino, 
		v.idcliente, v.cliente, v.flete, IF(v.guia=t.id,t.estado,IF(v.guia=s.id,s.estado,'')) AS estado
		FROM reporte_vendedores_ventas v
		INNER JOIN guiasventanilla t ON v.guia = t.id		
		LEFT JOIN solicitudguiasempresariales s ON v.guia = s.id
		WHERE v.idvendedor = '".$_GET[vendedor]."' AND v.activo = 'S'
		AND v.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND v.prefijoorigen = '$_GET[sucursal]'
		UNION
		SELECT DATE_FORMAT(v.fecha,'%d/%m/%Y') AS fecha, v.guia, IFNULL(v.prefijodestino,'') AS prefijodestino, 
		v.idcliente, v.cliente, v.flete, IF(v.guia=t.id,t.estado,IF(v.guia=s.id,s.estado,'')) AS estado
		FROM reporte_vendedores_ventas v
		INNER JOIN guiasempresariales t ON v.guia = t.id		
		LEFT JOIN solicitudguiasempresariales s ON v.guia = s.id
		WHERE v.idvendedor = '".$_GET[vendedor]."' AND v.activo = 'S'
		AND v.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND v.prefijoorigen = '$_GET[sucursal]'
		) t
		$limite";
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
		$s = "SELECT * FROM reporte_vendedores_cobrado
		WHERE idvendedor = '".$_GET[vendedor]."' AND activo = 'S'
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(flete),2) AS flete, FORMAT(SUM(comision),2) AS comision FROM reporte_vendedores_cobrado
		WHERE idvendedor = '".$_GET[vendedor]."' AND activo = 'S'
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha, guia, idcliente, cliente, 
		flete, comision FROM reporte_vendedores_cobrado
		WHERE idvendedor = '".$_GET[vendedor]."' AND activo = 'S'
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		$limite";
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
		$s = "SELECT prefijosucursal, vendedor, flete, comision FROM reporte_vendedores_cobrado
		WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND activo = 'S'
		GROUP BY idvendedor";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(flete),2) AS flete, FORMAT(SUM(comision),2) AS comision FROM reporte_vendedores_cobrado
		WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT prefijosucursal, vendedor, SUM(flete) AS flete, SUM(comision) AS comision FROM reporte_vendedores_cobrado
		WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND activo = 'S'
		GROUP BY idvendedor
		$limite";
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