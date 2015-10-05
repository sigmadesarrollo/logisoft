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
	
	if($_GET[accion]=="0"){
		$rr = ObtenerFolio("moduloconcesiones","webpmm");
		
		$s = "SELECT IF(ISNULL(MAX(fechafin)), '' ,DATE_FORMAT(DATE_ADD(fechafin,INTERVAL 1 DAY),'%d/%m/%Y')) AS fechainicio 
		FROM moduloconcesiones";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->folio = $rr[0];
		
		echo "(".str_replace('null','""',json_encode($f)).")";
		
	}else if($_GET[accion]==1){//PRINCIPAL CONCESIONES	
		$totalregistros = 0;
		$totales = 0;
		
		$s = mysql_query("CREATE TEMPORARY TABLE `reporteConcesiones_tmp` (  
				`idx` INT(11) NOT NULL AUTO_INCREMENT,
				`movimiento` VARCHAR(20) DEFAULT NULL,
				`pagcontado` DOUBLE DEFAULT NULL,
                `pagcredito` DOUBLE DEFAULT NULL,
                `cobcontado` DOUBLE DEFAULT NULL,
                `cobcredito` DOUBLE DEFAULT NULL,
				`idusuario` DOUBLE DEFAULT NULL,
				PRIMARY KEY (`idx`)
				) ENGINE=INNODB DEFAULT CHARSET=latin1",$l)  or die($s);
		
		$s = "INSERT INTO reporteConcesiones_tmp(movimiento,pagcontado,pagcredito,cobcontado,cobcredito,idusuario)
		SELECT 'VENTA' AS movimiento,
		(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CONTADO' AND tipo = 'V' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcontado,
		
		(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CREDITO' and tipo = 'V' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcredito,
		
		(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CONTADO' and tipo = 'V' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcontado,
		
		(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CREDITO' and tipo = 'V' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcredito, ".$_SESSION[IDUSUARIO]."
		FROM reporte_concesiones
		WHERE tipo = 'V' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S'
		GROUP BY movimiento";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporteConcesiones_tmp(movimiento,pagcontado,pagcredito,cobcontado,cobcredito,idusuario)
		SELECT 'RECIBIDO' AS movimiento, 
		(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CONTADO' and tipo = 'R' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcontado,
		(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CREDITO' and tipo = 'R' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS pagcredito,
		(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CONTADO' and tipo = 'R' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcontado,
		(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CREDITO' and tipo = 'R' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S') AS cobcredito, ".$_SESSION[IDUSUARIO]."
		FROM reporte_concesiones
		WHERE tipo = 'R' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal =".$_GET[sucursal]." AND folioconcesion IS NULL and activo='S'
		GROUP BY movimiento";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT IFNULL(movimiento,'') AS movimiento, IFNULL(pagcontado,0) AS pagcontado, IFNULL(pagcredito,0) AS pagcredito,
		IFNULL(cobcontado,0) AS cobcontado, IFNULL(cobcredito,0) AS cobcredito
		FROM reporteConcesiones_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->total = $f->pagcontado + $f->pagcredito + $f->cobcontado + $f->cobcredito;
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
		
	}else if($_GET[accion]==2){//VENTAS
		$s = "SELECT * FROM reporte_concesiones WHERE tipo = 'V' 
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$totales = 0;
		
		$s = "INSERT INTO reporte_concesionestmp(guia,idusuario)
		SELECT guia, ".$_SESSION[IDUSUARIO]." FROM reporte_concesiones WHERE tipo = 'V'
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT guia,DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fechaguia,tipoguia,tipoflete,condicionpago,flete,descuento,fleteneto,
		comision,recoleccion,comisionead,entrega,comisionrad,total,condicion,estado,sucursal,activo,ifnull(sobrepeso,0) as sobrepeso FROM reporte_concesiones WHERE tipo = 'V'
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
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
		
	}else if($_GET[accion]==3){//RECIBIDO
		$s = "SELECT * FROM reporte_concesiones WHERE tipo = 'R'
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$totales = 0;
		
		$s = "INSERT INTO reporte_concesionestmp(guia,idusuario)
		SELECT guia, ".$_SESSION[IDUSUARIO]." FROM reporte_concesiones WHERE tipo = 'R'
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE() ")."
		AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT guia,DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fechaguia,tipoguia,tipoflete,condicionpago,flete,descuento,fleteneto,
		comision,recoleccion,comisionead,entrega,comisionrad,total,condicion,estado,sucursal,activo FROM reporte_concesiones WHERE tipo = 'R'
		".((!empty($_GET[fechainicio]))? " AND fechaguia BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND fechaguia <= CURDATE()")."
		AND sucursal = ".$_GET[sucursal]." AND activo = 'S' AND folioconcesion IS NULL
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
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
		
	}else if($_GET[accion]==4){//INGRESOS
		
	
	}else if($_GET[accion]==5){//REGISTRAR REPORTE
		$s = "INSERT INTO moduloconcesiones SET 
		fechaconcesion = CURDATE(), sucursal = ".$_GET[sucursal].",
		fechainicio = '".cambiaf_a_mysql($_GET[fechainicio])."',
		fechafin = '".cambiaf_a_mysql($_GET[fechafin])."',
		idusuario = ".$_SESSION[IDUSUARIO].",
		fecha = CURRENT_TIMESTAMP";
		mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
		
		$s = "SELECT guia FROM reporte_concesionestmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "UPDATE reporte_concesiones SET folioconcesion = ".$folio." WHERE guia = '".$f->guia."'";	
			mysql_query($s,$l) or die($s);
		}
		
		echo "ok,".$folio;
	
	}else if($_GET[accion]==6){//OBTENER REPORTE GENERADO
		$s = "SELECT DATE_FORMAT(fechaconcesion,'%d/%m/%Y') AS fechaconcesion, 
		IF(fechainicio IS NULL OR fechainicio='0000-00-00','',DATE_FORMAT(fechainicio,'%d/%m/%Y')) AS fechainicio,
		DATE_FORMAT(fechafin,'%d/%m/%Y') AS fechafin, sucursal AS idsucursal FROM moduloconcesiones
		WHERE folio = ".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
		
			$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
			FROM catalogosucursal WHERE id = ".$f->idsucursal."";	
			$r = mysql_query($s,$l) or die($s); $fs = mysql_fetch_object($r);
			$f->sucursal = cambio_texto($fs->descripcion);
			
			$principal = str_replace('null','""',json_encode($f));
	
			$s = mysql_query("CREATE TEMPORARY TABLE `reporteConcesiones_tmp` (  
					`idx` INT(11) NOT NULL AUTO_INCREMENT,
					`movimiento` VARCHAR(20) DEFAULT NULL,
					`pagcontado` DOUBLE DEFAULT NULL,
					`pagcredito` DOUBLE DEFAULT NULL,
					`cobcontado` DOUBLE DEFAULT NULL,
					`cobcredito` DOUBLE DEFAULT NULL,
					`idusuario` DOUBLE DEFAULT NULL,
					PRIMARY KEY (`idx`)
					) ENGINE=INNODB DEFAULT CHARSET=latin1",$l)  or die($s);
			
			$s = "INSERT INTO reporteConcesiones_tmp(movimiento,pagcontado,pagcredito,cobcontado,cobcredito,idusuario)
			SELECT 'VENTA' AS movimiento,
			(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CONTADO') AS pagcontado,
			(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CREDITO') AS pagcredito,
			(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CONTADO') AS cobcontado,
			(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CREDITO') AS cobcredito, ".$_SESSION[IDUSUARIO]."
			FROM reporte_concesiones
			WHERE tipo = 'V' AND folioconcesion = ".$_GET[folio]." GROUP BY movimiento";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO reporteConcesiones_tmp(movimiento,pagcontado,pagcredito,cobcontado,cobcredito,idusuario)
			SELECT 'RECIBIDO' AS movimiento, 
			(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CONTADO') AS pagcontado,
			(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='PAGADO-CREDITO') AS pagcredito,
			(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CONTADO') AS cobcontado,
			(SELECT IFNULL(SUM(total),0) FROM reporte_concesiones WHERE condicion='POR COBRAR-CREDITO') AS cobcredito, ".$_SESSION[IDUSUARIO]."
			FROM reporte_concesiones
			WHERE tipo = 'R' AND folioconcesion = ".$_GET[folio]." GROUP BY movimiento";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT IFNULL(movimiento,'') AS movimiento, IFNULL(pagcontado,0) AS pagcontado, IFNULL(pagcredito,0) AS pagcredito,
			IFNULL(cobcontado,0) AS cobcontado, IFNULL(cobcredito,0) AS cobcredito
			FROM reporteConcesiones_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO];
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
			while($f1 = mysql_fetch_object($r)){
				$f1->total = $f1->pagcontado + $f1->pagcredito + $f1->cobcontado + $f1->cobcredito;
				$arr[] = $f1;
			}
			
			$tabla1 = str_replace('null','""',json_encode($arr));		
			
			$s = "SELECT guia,DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fechaguia,tipoguia,tipoflete,condicionpago,flete,descuento,fleteneto,
			comision,recoleccion,comisionead,entrega,comisionrad,total,condicion,estado,sucursal,activo,ifnull(sobrepeso,0) as sobrepeso FROM reporte_concesiones WHERE tipo = 'V' 		
			AND folioconcesion = ".$_GET[folio]."";
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
			while($f2 = mysql_fetch_object($r)){
				$f2->guia = cambio_texto($f2->guia);
				$arr[] = $f2;
			}
			$tabla2 = str_replace('null','""',json_encode($arr));
		
			$s = "SELECT guia,DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fechaguia,tipoguia,tipoflete,condicionpago,flete,descuento,fleteneto,
			comision,recoleccion,comisionead,entrega,comisionrad,total,condicion,estado,sucursal,activo FROM reporte_concesiones WHERE tipo = 'R'
			AND folioconcesion = ".$_GET[folio]."";
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
			while($f3 = mysql_fetch_object($r)){
				$f3->guia = cambio_texto($f3->guia);			
				$arr[] = $f3;
			}
			$tabla3 = str_replace('null','""',json_encode($arr));
				
			echo "({principal:$principal,tabla1:$tabla1,tabla2:$tabla2,tabla3:$tabla3})";
		}else{
			echo "no encontro";
		}	
	
	}else if($_GET[accion]==7){
		$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";	
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "(".str_replace('null','""',json_encode($f)).")";
	}


?>