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

	if($_GET[accion]==1){//HISTORIAL DE MOVIMIENTOS v_historialMovimientos es una vista
		$s = "SELECT v.folio, v.estado, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado, 
		DATE_FORMAT(v.fechamodificacion,'%d/%m/%Y %T') AS fecha FROM v_historialMovimientos v
		INNER JOIN catalogoempleado ce ON v.idusuario = ce.id
		WHERE v.modulo = '".$_GET[modulo]."' AND SUBSTRING(v.folio,1,13) = '".$_GET[referencia]."' 
		/* AND DATE(v.fechamodificacion) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'*/";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$totales = 0;
		
		$s = "SELECT v.modulo, IF(SUBSTRING(v.folio,14,1)=',', SUBSTRING(v.folio,15), v.folio) AS folio,
		IF(SUBSTRING(v.folio,14,1)=',', 'EVALUACION MERCANCIA', v.estado) AS estado, 
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado, 
		DATE_FORMAT(v.fechamodificacion,'%d/%m/%Y %T') AS fecha FROM v_historialMovimientos v
		INNER JOIN catalogoempleado ce ON v.idusuario = ce.id
		WHERE v.modulo = '".$_GET[modulo]."' AND SUBSTRING(v.folio,1,13) = '".$_GET[referencia]."'
		/* AND DATE(v.fechamodificacion) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'*/
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		
		while($f = mysql_fetch_object($r)){
			$f->folio = cambio_texto($f->folio);
			$f->estado = cambio_texto($f->estado);
			$f->empleado = cambio_texto($f->empleado);
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