<?
	require_once("Conectar.php");
	require_once("fn-error.php");
	$l = Conectarse("webpmm");
	
	
	$s = "CREATE TEMPORARY TABLE `xxxx` (
	  `ultid` DOUBLE DEFAULT NULL,
	  `guia` VARCHAR(25) COLLATE utf8_unicode_ci DEFAULT NULL,
	  `xax` BIGINT(21) NOT NULL DEFAULT '0',
	  `pri` DATE DEFAULT NULL,
	  `ult` DATE DEFAULT NULL,
	  KEY `guia` (`guia`)
	) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	mysql_query($s,$l) or postError($s);

	$s = "INSERT INTO xxxx
	SELECT t1.*
	FROM
		(SELECT MAX(id) ultid, guia, COUNT(id) xax, MIN(fecha) pri, MAX(fecha) ult
		FROM seguimiento_guias
		WHERE estado = 'ENTREGADA' AND guia <> ''
		GROUP BY guia
		HAVING xax > 1 AND pri<>ult) AS t1
	INNER JOIN 
		(SELECT MAX(id) ultid, guia, COUNT(id) xax, MIN(fecha) pri, MAX(fecha) ult
		FROM seguimiento_guias
		WHERE estado = 'ALMACEN DESTINO' AND guia <> ''
		GROUP BY guia) AS t2 ON t1.guia = t2.guia AND t1.ult > t2.ult";
		mysql_query($s,$l) or postError($s);
		
		$s = "select xxxx.* 
		from xxxx
		inner join guiasAconvertir ga on convert(xxxx.guia using utf8) = convert(ga.guia using utf8)";
		$r = mysql_query($s,$l) or postError(mysql_error($l).$s);
		while($f = mysql_fetch_object($r)){
			$fecha = "";
			$cambiar = true;
			
			$s = "SELECT folio, estado, date(fechamodificacion) fec FROM historialmovimientos_respaldo
			WHERE folio = '$f->guia' AND estado IN('ALMACEN DESTINO','ENTREGADA') order by id";
			$rx = mysql_query($s,$l) or postError($s);
			while($fx = mysql_fetch_object($rx)){
				if($cambiar==true && $fx->estado=='ENTREGADA'){
					$fecha = $fx->fec;
					$cambiar = false;
				}
				if($fx->estado=='ALMACEN DESTINO'){
					$cambiar = true;
				}
			}
			
			$s = "UPDATE guiasventanilla set fechaentrega = '$fecha' where id = '$f->guia'";
			echo $s."<br>";
			mysql_query($s,$l) or postError($s);
			
			$s = "UPDATE guiasempresariales set fechaentrega = '$fecha' where id = '$f->guia'";
			echo $s."<br>";
			mysql_query($s,$l) or postError($s);
		}
		
?>























