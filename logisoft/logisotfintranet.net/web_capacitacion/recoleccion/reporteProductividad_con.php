<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		/*$s = "SELECT sucursal, DATEDIFF(fecharecoleccion,fecharegistro) AS totaldias FROM recoleccion
		WHERE realizo='SI' ".(($_GET[sucursal]=="todas")? "" : "AND sucursal=".$_GET[sucursal]."" )."  
		AND fecharegistro".(($_GET[fechaa]!="") ? " BETWEEN '".cambiaf_a_mysql(str_replace("-","/",$_GET[fechade]))."' 
		AND '".cambiaf_a_mysql(str_replace("-","/",$_GET[fechaa]))."' " :
		'='.cambiaf_a_mysql(str_replace("-","/",$_GET[fechade])).'')."";
		//echo $s;
		$contadorceros = 0;
		$contadormayor = 0;
		$r = mysql_query($s,$l) or die(mysql_error($l).$s);
		$registros = array();
		$totalregistros = mysql_num_rows($r);*/
		
		$s = mysql_query("CREATE TEMPORARY TABLE `recoleccion_tmp` (  
				`idx` INT(11) NOT NULL AUTO_INCREMENT,
				`tipo` VARCHAR(5) DEFAULT NULL,	
				`sucursal` VARCHAR(150) DEFAULT NULL,
				`totaldias` INT(11) DEFAULT NULL,
				`totalescero` INT(11) DEFAULT NULL,
				`totalesmayor` INT(11) DEFAULT NULL,
				`pendientes` INT(11) DEFAULT NULL,				      
				PRIMARY KEY (`idx`)                     
				) ENGINE=INNODB DEFAULT CHARSET=latin1",$l)  or die($s);	
		
		
		if($_GET[sucursal]!="todas"){			
			/*$s = "INSERT INTO recoleccion_tmp
			SELECT 0 AS idx, 'rec' AS tipo, s.descripcion AS sucursal,
			IFNULL(DATEDIFF(fecharecoleccion,fecharegistro),0) AS totaldias, 
			IF(DATEDIFF(fecharecoleccion,fecharegistro) =0,1,0) AS totalescero,
			IF(DATEDIFF(fecharecoleccion,fecharegistro)>=1,1,0) AS totalesmayor,
			IF(fecharecoleccion='0000-00-00',1,0) AS pendiente FROM recoleccion r
			INNER JOIN catalogosucursal s ON r.sucursal = s.id
			WHERE r.sucursal=".$_GET[sucursal]." AND r.fecharegistro".(($_GET[fechaa]!="") ? 
			" BETWEEN '".cambiaf_a_mysql($_GET[fechade])."' AND '".cambiaf_a_mysql($_GET[fechaa])."'" : 
			'='.cambiaf_a_mysql($_GET[fechade])).'')."";
			$tmp = mysql_query($s,$l) or die($s);*/
			
			$s = "SELECT COUNT(*) AS total FROM recoleccion_tmp WHERE tipo='rec' ";
			$t = mysql_query($s,$l) or die($s); $tr = mysql_fetch_object($t); 
			$total = $tr->total;
			
			$sl = "SELECT sucursal, (SUM(totalescero) / ".$total.") AS mismodiarec,
			(SUM(totalesmayor) / ".$total.") AS masdiarec, (".$total." - (SUM(totalescero) + SUM(totalesmayor))) AS entregarec 
			FROM recoleccion_tmp WHERE tipo='rec' GROUP BY sucursal";
			//die($sl);
			$to = mysql_query($sl,$l) or die($sl);
			
			$registros = array();
			while($f = mysql_fetch_object($to)){
					$f->mismodiaead = 0;
					$f->masdiaead	= 0;
					$f->entregaead	= 0;
					$f->mismodiaocu = 0;
					$f->masdiaocu	= 0;
					$f->entregaocu	= 0;
					$registros[]	= $f;
			}				
		
		}else{
			
			/*$s = "INSERT INTO recoleccion_tmp
			SELECT 0 AS idx, 'rec' AS tipo, s.descripcion AS sucursal,
			IFNULL(DATEDIFF(fecharecoleccion,fecharegistro),0) AS totaldias, 
			IF(DATEDIFF(fecharecoleccion,fecharegistro) =0,1,0) AS totalescero,
			IF(DATEDIFF(fecharecoleccion,fecharegistro)>=1,1,0) AS totalesmayor,
			IF(fecharecoleccion='0000-00-00',1,0) AS pendiente FROM recoleccion r
			INNER JOIN catalogosucursal s ON r.sucursal = s.id
			WHERE r.fecharegistro".(($_GET[fechaa]!="") ? 
			" BETWEEN '".cambiaf_a_mysql($_GET[fechade])."' AND '".cambiaf_a_mysql($_GET[fechaa])."'" : 
			'='.cambiaf_a_mysql($_GET[fechade])).'')."";
			$tmp = mysql_query($s,$l) or die($s);*/
			
			$s = "SELECT COUNT(*) AS total FROM recoleccion_tmp WHERE tipo='rec' ";
			$t = mysql_query($s,$l) or die($s); $tr = mysql_fetch_object($t); 
			$total = $tr->total;
			
			$sl = "SELECT sucursal, (SUM(totalescero) / ".$total.") AS mismodiarec,
			(SUM(totalesmayor) / ".$total.") AS masdiarec, (".$total." - (SUM(totalescero) + SUM(totalesmayor))) AS entregarec FROM recoleccion_tmp WHERE tipo='rec' GROUP BY sucursal";
			
			$to = mysql_query($sl,$l) or die($sl);
			$registros = array();
			while($f = mysql_fetch_object($to)){
					$f->mismodiaead = 0;
					$f->masdiaead	= 0;
					$f->entregaead	= 0;
					$f->mismodiaocu = 0;
					$f->masdiaocu	= 0;
					$f->entregaocu	= 0;
					$registros[] = $f;
			}
		}
		echo str_replace("null",'""',json_encode($registros));
		/*$ead 	= '"0,0,0"';
		$ocurre = '"0,0,0"';
		echo "[{recolecciones:'$recolecciones',
				ead:$ead,
				ocurre:$ocurre}]";*/
				
		
	}else if($_GET[accion]==2){
		$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion 
		FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";
		$r = mysql_query($s,$l) or die(mysql_error($l).$s);
		$f = mysql_fetch_object($r);
		
		echo cambio_texto($f->descripcion);
		
	}else if($_GET[accion]==3){	
		$s = "SELECT dessucursal, SUM(undiaead) AS undiaead, SUM(dosdiaead) AS dosdiaead, SUM(faltanteead) AS faltanteead,
		SUM(tresdiasocurre) AS tresdiasocurre, SUM(faltanteocurre) AS faltanteocurre, SUM(undiarec) AS undiarec, 
		SUM(dosdiasrec) AS dosdiasrec, SUM(faltanterec) AS faltanterec FROM(
		SELECT r1.sucursal, cs.descripcion AS dessucursal, IFNULL(SUM(IF(diasead = 0,1,0)),0) AS undiaead, 
		IFNULL(SUM(IF(diasead >= 1,1,0)),0) AS dosdiaead,
		IFNULL((SELECT SUM(t.total) FROM(
		SELECT COUNT(*) AS total FROM guiasventanilla 
		WHERE ".(($_GET[sucursal]!="todas")? " idsucursaldestino = $_GET[sucursal] AND " : "")."		
		estado = 'ALMACEN DESTINO' AND ocurre = 0
		UNION
		SELECT COUNT(*) AS total FROM guiasempresariales 
		WHERE ".(($_GET[sucursal]!="todas")? " idsucursaldestino = $_GET[sucursal] AND " : "")."
		estado = 'ALMACEN DESTINO' AND ocurre = 0)t),0) AS faltanteead,
		IFNULL(SUM(IF(diasocurre >= 3,1,0)),0) AS tresdiasocurre,
		IFNULL((SELECT SUM(t.total) FROM(
		SELECT COUNT(*) AS total FROM guiasventanilla 
		WHERE ".(($_GET[sucursal]!="todas")? " idsucursaldestino = $_GET[sucursal] AND " : "")."
		estado = 'ALMACEN DESTINO' AND ocurre = 1
		UNION
		SELECT COUNT(*) AS total FROM guiasempresariales 
		WHERE ".(($_GET[sucursal]!="todas")? " idsucursaldestino = $_GET[sucursal] AND " : "")."
		estado = 'ALMACEN DESTINO' AND ocurre = 1)t),0) AS faltanteocurre,
		0 AS undiarec, 0 AS dosdiasrec, 0 AS faltanterec
		FROM reporteproductividad1 r1
		INNER JOIN catalogosucursal cs ON r1.sucursal = cs.id
		WHERE ".(($_GET[sucursal]!="todas")? " sucursal = $_GET[sucursal] AND " : "")."
		".(($_GET[fechaa]!="")? " CAST(fecharecepcion AS DATE) BETWEEN '".cambiaf_a_mysql($_GET[fechade])."' AND 
		'".cambiaf_a_mysql($_GET[fechaa])."'": " CAST(fecharecepcion AS DATE) = '".cambiaf_a_mysql($_GET[fechade])."'")."		
		UNION		
		SELECT r2.sucursal, cs.descripcion AS dessucursal, 0 as undiaead, 0 AS dosdiasead, 0 AS totalead, 0 AS tresdiasocurre,
		0 AS faltanteocurre,
		SUM(IF(diasrecoleccion = 0,1,0)) AS undiarec,
		SUM(IF(diasrecoleccion >= 1,1,0)) AS dosdiasrec,
		(SELECT COUNT(*) FROM recoleccion WHERE ".(($_GET[sucursal]!="todas")? " sucursal = $_GET[sucursal] AND " : "")."
		(realizo IS NULL OR realizo = 'NO')
		".(($_GET[fechaa]!="")? " AND fecharegistro BETWEEN '".cambiaf_a_mysql($_GET[fechade])."' AND 
		'".cambiaf_a_mysql($_GET[fechaa])."'": " AND fecharegistro = '".cambiaf_a_mysql($_GET[fechade])."'").") AS faltanterec
		FROM reporteproductividad2 r2
		INNER JOIN catalogosucursal cs ON r2.sucursal = cs.id
		WHERE ".(($_GET[sucursal]!="todas")? " sucursal = $_GET[sucursal] AND " : "")." diasrecoleccion IS NOT NULL
		".(($_GET[fechaa]!="")? " AND CAST(fechasolicitud AS DATE) BETWEEN '".cambiaf_a_mysql($_GET[fechade])."' AND 
		'".cambiaf_a_mysql($_GET[fechaa])."'": " AND CAST(fechasolicitud AS DATE) = '".cambiaf_a_mysql($_GET[fechade])."'").") t
		GROUP BY sucursal
		HAVING dessucursal IS NOT NULL";		
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->dessucursal = cambio_texto($f->dessucursal);
				$arr[] = $f;
			}
			echo str_replace('null','""',json_encode($arr));
		}else{		
			echo "no encontro";
		}
	}
?>