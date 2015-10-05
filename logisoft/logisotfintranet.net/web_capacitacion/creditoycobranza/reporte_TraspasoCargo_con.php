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
	
	/*total de registros*/ 
	$s = "SELECT foliotraspaso FROM historialdetraspaso WHERE aceptada=1 AND sucursalacepta='$_SESSION[IDSUCURSAL]'
	AND fechasolicitud BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."';";
	$r = mysql_query($s,$l) or die($s."\n".mysql_error($l));
	$totalregistros = mysql_num_rows($r);
	
	/*totales de los registros*/
	$totales = '""';
	
	/*registros*/
	$s = "SELECT foliotraspaso,fechasolicitud,sucursalsolicita AS solicita,guia,importe,cliente
	FROM historialdetraspaso WHERE aceptada=1 AND sucursalacepta='$_SESSION[IDSUCURSAL]'
	AND fechasolicitud BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $limite";
	$r = mysql_query($s,$l) or die($s."\n".mysql_error($l));
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
?>