<?
	session_start();
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
		/*total de registros*/
		$s = "SELECT rv.id
		FROM reportes_ventas rv
		INNER JOIN catalogosucursal cs ON 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.sucursalfacturo=cs.descripcion,
		rv.sucursalrealizo=cs.id)
		WHERE IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND rv.activo='S' ".(($_SESSION[IDSUCURSAL]==1)?"":" AND cs.id = $_SESSION[IDSUCURSAL]")."
		GROUP BY cs.id";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT round(ifnull(SUM(IF(ISNULL(rv.convenio) or rv.convenio=0,0,rv.total)),0),2) AS convenio, 
		round(ifnull(SUM(IF(ISNULL(rv.convenio) or rv.convenio=0,rv.total,0)),0),2) AS sinconvenio,
		round(ifnull(SUM(rv.total),0),2) AS total
		FROM reportes_ventas rv
		INNER JOIN catalogosucursal cs ON 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.sucursalfacturo=cs.descripcion,
		rv.sucursalrealizo=cs.id)
		WHERE IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND rv.activo='S' ".(($_SESSION[IDSUCURSAL]==1)?"":" AND cs.id = $_SESSION[IDSUCURSAL]")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT cs.prefijo AS sucursal, 
		SUM(IF(ISNULL(rv.convenio) or rv.convenio=0,0,rv.total)) AS convenio, 
		SUM(IF(ISNULL(rv.convenio) or rv.convenio=0,rv.total,0)) AS sinconvenio,
		SUM(rv.total) AS total
		FROM reportes_ventas rv
		INNER JOIN catalogosucursal cs ON 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.sucursalfacturo=cs.descripcion,
		rv.sucursalrealizo=cs.id)
		WHERE IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND rv.activo='S' ".(($_SESSION[IDSUCURSAL]==1)?"":" AND cs.id = $_SESSION[IDSUCURSAL]")."
		GROUP BY cs.id
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$f->sucursal = cambio_texto($f->sucursal);
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
	
	if($_GET[accion]==2){
		/*total de registros*/
		$s = "SELECT IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo,
		rv.prefijosucursal)
		AS sucursal, rv.id
		FROM reportes_ventas rv
		WHERE 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')		
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0  AND rv.activo='S' AND 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		
		GROUP BY sucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = "''";
		
		/*registros*/
		$s = "SELECT IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo,
		rv.prefijosucursal)
		AS sucursal,
		SUM(IF(rv.tipoventa='GUIA VENTANILLA',rv.total,0)) AS normales, 
		SUM(IF((rv.tipoventa='SOLICITUD DE FOLIOS' AND tipoempresarial='PREPAGADA') or rv.tipoventa='FACTURA EXCEDENTE',rv.total,0)) AS prepagadas,
		SUM(IF(rv.tipoventa='GUIA EMPRESARIAL' AND tipoempresarial<>'PREPAGADA',rv.total,0)) AS consignacion,
		
		SUM(IF(rv.tipoventa='GUIA VENTANILLA',rv.total,0))+
		SUM(IF((rv.tipoventa='FACTURA EXCEDENTE' OR rv.tipoventa='GUIA EMPRESARIAL' 
				OR rv.tipoventa='SOLICITUD DE FOLIOS') AND tipoempresarial='PREPAGADA',rv.total,0))+
		SUM(IF(rv.tipoventa='GUIA EMPRESARIAL' AND tipoempresarial<>'PREPAGADA',rv.total,0))
		 AS total
		FROM reportes_ventas rv
		WHERE 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND rv.activo='S' AND 
		
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		GROUP BY sucursal
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
	
	//REPORTE POR CONDICION DE PAGO
	if($_GET[accion]==3){
		/*total de registros*/
		$s = "SELECT rv.id
		FROM reportes_ventas AS rv
		WHERE rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo='S'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = "''";
		
		/*registros*/
		$s = "SELECT IFNULL(SUM(IF(rv.tipoflete = 'PAGADA' AND rv.tipopago='CONTADO', rv.total,0)),0) contado,
		IFNULL(SUM(IF(rv.tipoflete = 'PAGADA' AND rv.tipopago='CREDITO', rv.total,0)),0) credito,
		IFNULL(SUM(IF(rv.tipoflete = 'POR COBRAR' AND rv.tipopago='CONTADO', rv.total,0)),0) cobcontado,
		IFNULL(SUM(IF(rv.tipoflete = 'POR COBRAR' AND rv.tipopago='CREDITO', rv.total,0)),0) cobcredito,
		IFNULL(SUM(rv.total),0) AS total
		FROM reportes_ventas AS rv
		WHERE rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' 
		AND	IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo='S'
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
	
	//DESGLOSE DE VENTA CONTADO  (CREDITO-COB-CONTADO, COB-CREDITO)
	if($_GET[accion]==4){
		/*total de registros*/
		$s = "SELECT rv.idcliente, rv.nombrecliente AS cliente, rv.destino, rv.folio, rv.total
		FROM reportes_ventas rv
		WHERE
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."') 
		AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		rv.tipoflete = 'PAGADA' AND rv.tipopago='CONTADO'
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT round(SUM(rv.total),2) AS total
		FROM reportes_ventas rv
		WHERE
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."') 
		AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		rv.tipoflete = 'PAGADA' AND rv.tipopago='CONTADO'
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT rv.idcliente, rv.nombrecliente AS cliente, rv.destino, rv.folio, rv.total
		FROM reportes_ventas rv
		WHERE
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."') 
		AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		rv.tipoflete = 'PAGADA' AND rv.tipopago='CONTADO'
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo = 'S'
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
	
	#VENTAS CON CONVENIO POR CLIENTE 
	if($_GET[accion]==5){
		/*total de registros*/
		$s = "SELECT rv.idcliente, rv.nombrecliente AS cliente, rv.destino, rv.folio, rv.tipopago, SUM(rv.total) AS total
		FROM reportes_ventas rv
		WHERE rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo = 'S'
		GROUP BY rv.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT round(IFNULL(SUM(rv.total),0),2) AS total
		FROM reportes_ventas rv
		WHERE rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT rv.idcliente, rv.nombrecliente AS cliente, rv.destino, rv.folio, rv.tipopago, 
		IFNULL(SUM(rv.total),0) AS total FROM reportes_ventas rv
		WHERE rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."')
		AND NOT ISNULL(rv.convenio) and rv.convenio <> 0 AND tipoventa = 'GUIA VENTANILLA' AND rv.activo = 'S'
		GROUP BY rv.idcliente
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
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
		rv.tipoventa = 'GUIA VENTANILLA')
		UNION
		(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, ge.estado, ge.recibio
		FROM reportes_ventas rv
		INNER JOIN guiasempresariales ge ON rv.folio = ge.id
		WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
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
			IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
			rv.tipoventa = 'GUIA VENTANILLA')
			UNION
			(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
			rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, ge.estado, ge.recibio
			FROM reportes_ventas rv
			INNER JOIN guiasempresariales ge ON rv.folio = ge.id
			WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
			rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
			IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
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
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
		rv.tipoventa = 'GUIA VENTANILLA')
		UNION
		(SELECT rv.fecharealizacion, rv.folio, rv.destino, CONCAT(rv.nombrecliente,'/',rv.recibe) origendestino, rv.flete, 
		rv.tipoentrega, rv.paquetes, rv.totalkilogramos, rv.total, ge.estado, ifnull(ge.recibio,'') recibio
		FROM reportes_ventas rv
		INNER JOIN guiasempresariales ge ON rv.folio = ge.id
		WHERE rv.".$_GET[tipo]." = '$_GET[idcliente]' AND
		rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,prefijosucursal) = '".$_GET[sucursal]."' AND
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
	
	#VENTAS CON CONVENIO POR CLIENTE 
	if($_GET[accion]==7){
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS t_prepagadas$x";mysql_query($s,$l) or die($s);
		/* tabla de convenios */
		$s = "CREATE TABLE `t_prepagadas$x` (
			`folio` DOUBLE DEFAULT NULL,
			`importe` DOUBLE DEFAULT NULL,
			PRIMARY KEY  (`folio`)
			) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO t_prepagadas$x
			SELECT DISTINCT(rv.folio),0 AS importe	
			FROM reportes_ventas rv
			LEFT JOIN guiasempresariales ge ON ge.id = rv.folio AND (ge.tseguro>0 OR ge.texcedente>0)
			WHERE (rv.tipoventa = 'SOLICITUD DE FOLIOS' OR rv.tipoventa='FACTURA EXCEDENTE') AND rv.tipoempresarial = 'PREPAGADA' AND 
			rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
			rv.prefijosucursal = '".$_GET[sucursal]."' AND rv.activo='S' GROUP BY rv.folio;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE t_prepagadas$x tp INNER JOIN reportes_ventas rv ON tp.folio=rv.folio SET tp.importe=rv.total;";
		mysql_query($s,$l) or die($s); 
		
		/*total de registros*/
		$s = "SELECT rv.id
		FROM reportes_ventas rv
		LEFT JOIN guiasempresariales ge ON ge.id = rv.folio AND (ge.tseguro>0 OR ge.texcedente>0)
		WHERE (tipoventa = 'SOLICITUD DE FOLIOS' or rv.tipoventa='FACTURA EXCEDENTE') AND rv.tipoempresarial = 'PREPAGADA' AND 
		rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		rv.prefijosucursal = '".$_GET[sucursal]."' AND rv.activo='S' 
		GROUP BY rv.folio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT FORMAT(SUM(importe),2) totalimporte, 0 totalpendientefacturar
		FROM t_prepagadas$x";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT IF(NOT ISNULL(rv.sucursalfacturo), rv.prefijosucfacturo,rv.prefijosucursal) sucursal,
		rv.idcliente AS cliente,rv.nombrecliente,rv.folio AS venta,rv.foliosempresariales AS folios,rv.factura,rv.total importe, 
		IFNULL(SUM(IF((ISNULL(ge.factura) OR ge.factura=0) AND (ge.tseguro>0 OR ge.texcedente>0),
		ge.tseguro+ge.texcedente,0)),0) porfacturar
		FROM reportes_ventas rv
		LEFT JOIN guiasempresariales ge ON ge.id = rv.folio AND (ge.tseguro>0 OR ge.texcedente>0)
		WHERE (tipoventa = 'SOLICITUD DE FOLIOS' OR rv.tipoventa='FACTURA EXCEDENTE') AND rv.tipoempresarial = 'PREPAGADA' AND 
		rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' AND
		rv.prefijosucursal = '".$_GET[sucursal]."' AND rv.activo='S'
		GROUP BY rv.folio
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
		
		$s = "DROP TABLE t_prepagadas$x";
		mysql_query($s,$l) or die($s);
	}
	
	#VENTAS CON CONVENIO POR CLIENTE 
	if($_GET[accion]==8){
		$arre = split("-",$_GET[folios]);
		$_GET[folioinicial] = $arre[0];
		$_GET[foliofinal] = $arre[1];
		
		
		/*total de registros*/
		$s = "SELECT rv.id
		FROM reportes_ventas rv 
		INNER JOIN generacionconvenio gc ON rv.convenio = gc.folio 
		INNER JOIN catalogosucursal cs ON rv.idsucorigen = cs.id
		INNER JOIN catalogocliente cc ON rv.idcliente = cc.id
		INNER JOIN configuradorgeneral cg
		WHERE rv.folio BETWEEN '$_GET[folioinicial]' AND '$_GET[foliofinal]' AND (ISNULL(rv.factura) OR rv.factura=0)
		AND (IFNULL(rv.seguro,0)>0 OR IFNULL(rv.excedente,0)>0)";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT 
		round(SUM(IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0) +
		IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0)*(cs.iva/100) +
		IF(cc.personamoral<>'SI',0,IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0)*((cg.ivaretenido)/100))),2) total
		FROM reportes_ventas rv 
		INNER JOIN generacionconvenio gc ON rv.convenio = gc.folio 
		INNER JOIN catalogosucursal cs ON rv.idsucorigen = cs.id
		INNER JOIN catalogocliente cc ON rv.idcliente = cc.id
		INNER JOIN configuradorgeneral cg
		WHERE rv.folio BETWEEN '$_GET[folioinicial]' AND '$_GET[foliofinal]' AND (ISNULL(rv.factura) OR rv.factura=0)
		AND (IFNULL(rv.seguro,0)>0 OR IFNULL(rv.excedente,0)>0)";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT DATE_FORMAT(rv.fecharealizacion,'%d/%m/%Y') fecha, rv.prefijoorigen, rv.prefijodestino,
		rv.folio, rv.paquetes, rv.totalkilogramos, ifnull(rv.valordeclarado,0) valordeclarado, rv.flete, 
		IF(rv.totalkilogramos-gc.limitekg<0,0,rv.totalkilogramos-gc.limitekg) kgexcedente, 
		ifnull(rv.seguro,0) seguro, ifnull(rv.combustible,0) combustible,  
		IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0) subtotal,
		IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0)*(cs.iva/100) iva, 
		IF(cc.personamoral<>'SI',0,IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0)*((cg.ivaretenido)/100)) ivaretenido,
		
		IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0) +
		IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0)*(cs.iva/100) +
		IF(cc.personamoral<>'SI',0,IFNULL(rv.seguro,0)+IFNULL(rv.excedente,0)*((cg.ivaretenido)/100)) total
		
		FROM reportes_ventas rv 
		INNER JOIN generacionconvenio gc ON rv.convenio = gc.folio 
		INNER JOIN catalogosucursal cs ON rv.idsucorigen = cs.id
		INNER JOIN catalogocliente cc ON rv.idcliente = cc.id
		INNER JOIN configuradorgeneral cg
		WHERE rv.folio BETWEEN '$_GET[folioinicial]' AND '$_GET[foliofinal]' AND (ISNULL(rv.factura) OR rv.factura=0)
		AND (IFNULL(rv.seguro,0)>0 OR IFNULL(rv.excedente,0)>0)
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
	
	#VENTAS POR GUIAS A CONSIGNACION 
	if($_GET[accion]==9){
		$arre = split("-",$_GET[folios]);
		$_GET[folioinicial] = $arre[0];
		$_GET[foliofinal] = $arre[1];
		
		
		/*total de registros*/
		$s = "SELECT rv.prefijosucursal sucursal, sg.idcliente, CONCAT_WS(' ',sg.nombre,sg.apepat,sg.apemat) AS nombrecliente,
		sg.cantidad, sg.factura, SUM(rv.total) importe, 0 servicios, SUM(rv.total) total,
		CONCAT(sg.desdefolio,'-',sg.hastafolio) folios
		FROM solicitudguiasempresariales sg
		INNER JOIN reportes_ventas rv ON rv.folio BETWEEN sg.desdefolio AND sg.hastafolio 
		WHERE sg.prepagada<>'SI' and 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND	IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."') AND rv.activo = 'S'
		GROUP BY sg.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT round(SUM(rv.total),2) total
		FROM solicitudguiasempresariales sg
		INNER JOIN reportes_ventas rv ON rv.folio BETWEEN sg.desdefolio AND sg.hastafolio 
		WHERE sg.prepagada<>'SI' and 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."') AND rv.activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT '".$_GET[sucursal]."' sucursal, sg.idcliente, rv.nombrecliente,
		sg.cantidad, rv.factura, ROUND(SUM(rv.total),2) importe, 0 servicios, ROUND(SUM(rv.total),2) total,
		CONCAT(sg.desdefolio,'-',sg.hastafolio) folios
		FROM solicitudguiasempresariales sg
		INNER JOIN reportes_ventas rv ON rv.folio BETWEEN sg.desdefolio AND sg.hastafolio 
		WHERE sg.prepagada<>'SI' AND 
		IF(rv.tipoventa <> 'GUIA VENTANILLA',
				 rv.fechafacturacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."',
				 rv.fecharealizacion BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."')
		AND IF(rv.tipoventa <> 'GUIA VENTANILLA',
		rv.prefijosucfacturo='".$_GET[sucursal]."',
		rv.prefijosucursal='".$_GET[sucursal]."') AND rv.activo = 'S'
		GROUP BY sg.idcliente, rv.factura 
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
	
	if($_GET[accion]==10){
		$arre = split("-",$_GET[folios]);
		$_GET[folioinicial] = $arre[0];
		$_GET[foliofinal] = $arre[1];
		
		
		/*total de registros*/
		$s = "SELECT DATE_FORMAT(rv.fecharealizacion, '%d/%m/%Y') fecha,
		rv.prefijoorigen, rv.prefijodestino, rv.folio, rv.paquetes, rv.totalkilogramos,
		rv.valordeclarado, rv.flete, rv.seguro, rv.combustible, rv.subtotal, rv.iva,
		rv.ivaretenido, rv.total, rv.factura
		FROM reportes_ventas rv
		WHERE  /*rv.folioBETWEEN '$_GET[folioinicial]' AND '$_GET[foliofinal]' and*/ tipoempresarial='CONSIGNACION'
		AND rv.factura = '$_GET[factura]'
		 AND rv.activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT round(SUM(rv.total),2) total
		FROM reportes_ventas rv
		WHERE /*rv.folio BETWEEN '$_GET[folioinicial]' AND '$_GET[foliofinal]' and*/  tipoempresarial='CONSIGNACION'
		AND rv.factura = '$_GET[factura]'
		 AND rv.activo = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT DATE_FORMAT(rv.fecharealizacion, '%d/%m/%Y') fecha,
		rv.prefijoorigen, rv.prefijodestino, rv.folio, rv.paquetes, rv.totalkilogramos,
		ifnull(rv.valordeclarado,0) valordeclarado, rv.flete, 
		ifnull(rv.seguro,0) seguro, ifnull(rv.combustible,0) combustible, ifnull(rv.subtotal,0) subtotal, 
		ifnull(rv.iva,0) iva, ifnull(rv.ivaretenido,0) ivaretenido, rv.total, ifnull(rv.factura,0) factura
		FROM reportes_ventas rv
		WHERE /*rv.folio BETWEEN '$_GET[folioinicial]' AND '$_GET[foliofinal]' and*/ tipoempresarial='CONSIGNACION'
		AND rv.factura = '$_GET[factura]'
		 AND rv.activo = 'S'
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