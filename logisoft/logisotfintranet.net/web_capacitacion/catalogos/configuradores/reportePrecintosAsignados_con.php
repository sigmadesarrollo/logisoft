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
	
	if($_GET[accion]==1){
		$s = "SELECT cs.prefijo AS sucursal, a.folioinicial, a.foliofinal FROM asignacionprecintos a
		INNER JOIN catalogosucursal cs ON a.sucursal = cs.id";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT cs.prefijo AS sucursal, a.folioinicial, a.foliofinal FROM asignacionprecintos a
		INNER JOIN catalogosucursal cs ON a.sucursal = cs.id
		ORDER BY folioinicial
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->sucursal = cambio_texto($f->sucursal);
			$f->folioinicial = str_pad($f->folioinicial,9,'0',STR_PAD_LEFT);
			$f->foliofinal = str_pad($f->foliofinal,9,'0',STR_PAD_LEFT);
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
	}
?>
