<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
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
		$s = "SELECT * FROM(
		SELECT g.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
		IF(g.tipoflete=0,'PAGADO','POR COBRAR') AS flete,
		IF(g.condicionpago=0,'CONTADO', IF(g.condicionpago=1,'CREDITO','')) AS condicionpago,
		IFNULL(g.subtotal,0) AS subtotal, IFNULL(g.tiva,0) AS tiva,
		IFNULL(g.ivaretenido,0) AS ivaretenido, IFNULL(g.total,0) AS total
		FROM guiasventanilla g
		INNER JOIN catalogosucursal o ON g.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON g.idsucursaldestino = d.id
		WHERE g.fecha = CURRENT_DATE 
		".(($_SESSION[IDSUCURSAL]!=1)?" AND g.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."
		UNION
		SELECT ge.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
		ge.tipoflete AS flete, ge.tipopago AS condicionpago,
		IFNULL(ge.subtotal,0) AS subtotal, IFNULL(ge.tiva,0) AS tiva,
		IFNULL(ge.ivaretenido,0) AS ivaretenido, IFNULL(ge.total,0) AS total
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		WHERE ge.fecha = CURRENT_DATE 
		".(($_SESSION[IDSUCURSAL]!=1)?" AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").")t";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(IFNULL(SUM(total),0),2) AS total FROM(
		SELECT IFNULL(total,0) AS total FROM guiasventanilla
		WHERE fecha = CURRENT_DATE 
		".(($_SESSION[IDSUCURSAL]!=1)?" AND idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."
		UNION
		SELECT IFNULL(total,0) AS total FROM guiasempresariales
		WHERE fecha = CURRENT_DATE ".(($_SESSION[IDSUCURSAL]!=1)?" AND idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") t";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = $f->total;
	
		$s = "SELECT * FROM(
		SELECT g.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
		IF(g.tipoflete=0,'PAGADO','POR COBRAR') AS flete,
		IF(g.condicionpago=0,'CONTADO', IF(g.condicionpago=1,'CREDITO','')) AS condicionpago,
		IFNULL(g.subtotal,0) AS subtotal, IFNULL(g.tiva,0) AS tiva,
		IFNULL(g.ivaretenido,0) AS ivaretenido, IFNULL(g.total,0) AS total
		FROM guiasventanilla g
		INNER JOIN catalogosucursal o ON g.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON g.idsucursaldestino = d.id
		WHERE g.fecha = CURRENT_DATE 
		".(($_SESSION[IDSUCURSAL]!=1)?" AND g.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."
		UNION
		SELECT ge.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
		ge.tipoflete AS flete, ge.tipopago AS condicionpago,
		IFNULL(ge.subtotal,0) AS subtotal, IFNULL(ge.tiva,0) AS tiva,
		IFNULL(ge.ivaretenido,0) AS ivaretenido, IFNULL(ge.total,0) AS total
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		WHERE ge.fecha = CURRENT_DATE 
		".(($_SESSION[IDSUCURSAL]!=1)?" AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").")t $limite";		
		$r = mysql_query($s,$l) or die($s);
		$arr = array();			
		while($f = mysql_fetch_object($r)){				
			$f->guia = cambio_texto($f->guia);
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":"'.$totales.'",
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	}
	
?>