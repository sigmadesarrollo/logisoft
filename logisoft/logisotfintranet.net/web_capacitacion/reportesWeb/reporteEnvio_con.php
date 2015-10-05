<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$paginado = 2;
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
	
	#REPORTE DE ENVIOS POR CLIENTE 
	#enviar tipo=idenvia para los puros enviados
	#enviar tipo=idrecibe para los puros recibidos
	if($_GET[accion]==6){
		/*total de registros*/
		$s = "(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, gv.estado, gv.recibio
		FROM reportes_ventas rv
		INNER JOIN guiasventanilla gv ON rv.folio = gv.id
		WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		/*IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND*/
		rv.tipoventa = 'GUIA VENTANILLA')
		UNION
		(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, ge.estado, ge.recibio
		FROM reportes_ventas rv
		INNER JOIN guiasempresariales ge ON rv.folio = ge.id
		WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		/*IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND*/
		rv.tipoventa = 'GUIA VENTANILLA')";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT IFNULL(COUNT(folio),0) totalguias, IFNULL(SUM(paquetes),0) totalpaquetes, 
			IFNULL(SUM(totalkilogramos),0) totalkilogramos, IFNULL(SUM(total),0) total FROM (
			(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
			rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, gv.estado, gv.recibio
			FROM reportes_ventas rv
			INNER JOIN guiasventanilla gv ON rv.folio = gv.id
			WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
			rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
			/*IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND*/
			rv.tipoventa = 'GUIA VENTANILLA')
			UNION
			(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
			rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, ge.estado, ge.recibio
			FROM reportes_ventas rv
			INNER JOIN guiasempresariales ge ON rv.folio = ge.id
			WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
			rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
			/*IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND*/
			rv.tipoventa = 'GUIA VENTANILLA')
		) AS t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, gv.estado, ifnull(gv.recibio,'') recibio
		FROM reportes_ventas rv
		INNER JOIN guiasventanilla gv ON rv.folio = gv.id
		WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		/*IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND*/
		rv.tipoventa = 'GUIA VENTANILLA')
		UNION
		(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, ge.estado, ifnull(ge.recibio,'') recibio
		FROM reportes_ventas rv
		INNER JOIN guiasempresariales ge ON rv.folio = ge.id
		WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		/*IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND*/
		rv.tipoventa = 'GUIA VENTANILLA')
		$limite";
		//die($s);
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