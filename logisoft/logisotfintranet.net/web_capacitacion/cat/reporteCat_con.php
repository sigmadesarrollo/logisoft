<?	session_start();
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
	
	if($_GET[accion]==1){
		$s = "SELECT * FROM solicitudtelefonica s
		WHERE s.fechaqueja BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_GET[estado]!='0') ? " AND s.estado = '$_GET[estado]'" : "")."
		".(($_GET[todas]==0) ? " AND s.sucursal = $_GET[sucursal]" : "")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);	
		
		$totales = 0;
		
		$s = "SELECT cs.prefijo AS sucursal, DATE_FORMAT(s.fechaqueja,'%d/%m/%Y') AS fechaqueja,
		s.queja, IFNULL(s.folioatencion,'') AS folioatencion, IFNULL(s.guia,'') AS guia,
		IFNULL(s.recoleccion,'') AS recoleccion, IF(s.foliofaltante=0,'',s.foliofaltante) AS folioqueja,
		IFNULL(s.nombre,'') AS nombre
		FROM solicitudtelefonica s
		INNER JOIN catalogosucursal cs ON s.sucursal = cs.id
		WHERE s.fechaqueja BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_GET[estado]!='0') ? " AND s.estado = '$_GET[estado]' " : "")."
		".(($_GET[todas]==0) ? " AND s.sucursal = $_GET[sucursal] " : "")."
		ORDER BY cs.prefijo $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->sucursal = cambio_texto($f->sucursal);
			$f->nombre = cambio_texto($f->nombre);
			$f->guia = cambio_texto($f->guia);
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
	
	}else if($_GET[accion]==2){//SOLUCIONADOS
		$s = "SELECT * FROM solicitudtelefonica
		WHERE estado = 'SOLUCIONADO' AND 
		fechaqueja BETWEEN '".cambiaf_a_mysql($_GET[fechaincio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);	
		
		$totales = 0;
		
		$s = "SELECT cs.prefijo AS sucursal, DATE_FORMAT(s.fechaqueja,'%d/%m/%Y') AS fechaqueja,
		s.queja, IFNULL(s.folioatencion,'') AS folioatencion, IFNULL(s.guia,'') AS guia,
		IFNULL(s.recoleccion,'') AS recoleccion, IFNULL(s.foliofaltante,'') AS folioatencion,
		IFNULL(s.nombre,'') AS nombre
		FROM solicitudtelefonica s
		INNER JOIN catalogosucursal cs ON s.sucursal = cs.id
		WHERE s.estado = 'SOLUCIONADO' AND
		s.fechaqueja BETWEEN '".cambiaf_a_mysql($_GET[fechaincio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		ORDER BY cs.prefijo $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->sucursal = cambio_texto($f->sucursal);
			$f->nombre = cambio_texto($f->nombre);
			$f->guia = cambio_texto($f->guia);
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
