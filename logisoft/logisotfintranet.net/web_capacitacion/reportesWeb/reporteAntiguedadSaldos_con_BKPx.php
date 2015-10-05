<?
	session_start();
	require_once('../Conectar.php');
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
	
	if($_GET[accion]==4){
		/*total de registros*/
		$s = "SELECT id
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT format(SUM(IF(IFNULL(fechavencimiento,fechavencimientof)<CURRENT_DATE,total,0)),2) AS vencido,
		format(SUM(IF(IFNULL(fechavencimiento,fechavencimientof)>CURRENT_DATE,total,0)),2) AS alcorriente,
		format(SUM(total),2) AS total
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT prefijosucursal, cliente,folio, IFNULL(fechafactura,fecha) AS fecha, 
		IFNULL(fechavencimiento,fechavencimientof) AS fechavenc, 
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<0,0,DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<=0,total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<16 
		AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>0,total,0) c1a15dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<31 
		AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>15,total,0) c16a30dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<61 
		AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>30,total,0) c31a60dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>60,total,0) may60dias,
		total AS saldo,
		IFNULL(factura,'') AS factura, IFNULL(contrarecibo,'') AS contrarecibo
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0
		$limite";
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
	
	

?>