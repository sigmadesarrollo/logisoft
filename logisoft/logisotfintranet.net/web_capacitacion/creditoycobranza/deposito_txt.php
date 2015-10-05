<?	
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");

	$var_itabla = $_SESSION[IDUSUARIO].date("dHis");
	//preparar las sucursales
	$s = "CREATE TABLE `catalogosucursal_tmp_".$var_itabla."` (
		  `id` INT(11) NOT NULL DEFAULT '0',
		  `prefijo` VARCHAR(10) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `idsucursal` VARCHAR(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  KEY `id` (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	mysql_query($s,$l) or die(mysql_error($l)."-".$s);
	
	$s = "INSERT INTO catalogosucursal_tmp_".$var_itabla."
		SELECT id, 
		CASE prefijo
			WHEN 'MY1' THEN 'MTY'
			WHEN 'LE1' THEN 'LEO'
			ELSE prefijo
		END prefijo,
		CASE idsucursal
			WHEN '561' THEN '560'
			WHEN '401' THEN '400'
			ELSE idsucursal
		END idsucursal
		FROM catalogosucursal";
		mysql_query($s,$l) or die(mysql_error($l)."-".$s);

	$s = "SELECT CONCAT_WS(' ',UCASE(eti_nombre1), UCASE(eti_nombre2)) empresa FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die(mysql_error($l)."-".$s);
	$f = mysql_fetch_object($r);
	$empresa = $f->empresa;
	
	$s = "SELECT * FROM catalogosucursal_tmp_".$var_itabla." WHERE id = $_GET[sucursal_hidden]";
	$r = mysql_query($s,$l) or die(mysql_error($l)."-".$s);
	$f = mysql_fetch_object($r);
	
	$idsucursal 	= $f->id;
	$prefijosucursal= $f->prefijo;
	$fechainicio 	= "'".cambiaf_a_mysql($_GET[inicio])."'";
	$fechafinal 	= "'".cambiaf_a_mysql($_GET[fin])."'";
	$inicuenta 		= '1024';
	$cuentacontable	= $f->idsucursal;
	$fincuenta 		= '000100';
	$cuentamov		='1000001000100';
	$fechaarriba = str_replace("-","",cambiaf_a_mysql($_GET[inicio]));
	$meses = array("","ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
	$dias = split("-",cambiaf_a_mysql($_GET[inicio]));
	$diainicioarriba = $dias[2];
	$dias = split("-",cambiaf_a_mysql($_GET[fin]));
	$diafinarriba = $dias[2];
	$mesarriba = $meses[$dias[1]*1];
	$tituloarriba = "DEP. DEL $diainicioarriba AL $diafinarriba DE $mesarriba/".substr($dias[0],2,4)." ".$prefijosucursal;
	$numeropoliza = "01".$cuentacontable.str_pad($dias[2],3,"0",STR_PAD_LEFT);
	$totalabonos = 0;
	$totalcargos = 0;
	//nombre archivo
	header("Content-Disposition: attachment; filename=D".$cuentacontable."_".str_replace("/","",$_GET[inicio]).".txt");
	echo "P $fechaarriba 1   ".$cuentacontable."135 1   0 $tituloarriba                                                                       5 2\r\n";
	$s = "SELECT cantidad,ficha,descripcion,fecha FROM(
		SELECT SUM(cantidad) cantidad,ficha,CONCAT('DEP.',
		IF(DAY(fecha)<10,CONCAT('0',DAY(fecha)),DAY(fecha)),'/',
		CASE WHEN MONTH(fecha) = 1 THEN 'ENE'
		WHEN MONTH(fecha) = 2 THEN 'FEB'
		WHEN MONTH(fecha) = 3 THEN 'MAR'
		WHEN MONTH(fecha) = 4 THEN 'ABR'
		WHEN MONTH(fecha) = 5 THEN 'MAY'
		WHEN MONTH(fecha) = 6 THEN 'JUN'
		WHEN MONTH(fecha) = 7 THEN 'JUL'
		WHEN MONTH(fecha) = 8 THEN 'AGO'
		WHEN MONTH(fecha) = 9 THEN 'SEP'
		WHEN MONTH(fecha) = 10 THEN 'OCT'
		WHEN MONTH(fecha) = 11 THEN 'NOV'
		WHEN MONTH(fecha) = 12 THEN 'DIC'
		END,'/',DATE_FORMAT(fecha,'%y'),' C.',
		IF(DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))<10,
		CONCAT('0',DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))),
		DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))),'/',
		CASE WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 1 THEN 'ENE'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 2 THEN 'FEB'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 3 THEN 'MAR'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 4 THEN 'ABR'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 5 THEN 'MAY'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 6 THEN 'JUN'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 7 THEN 'JUL'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 8 THEN 'AGO'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 9 THEN 'SEP'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 10 THEN 'OCT'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 11 THEN 'NOV'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 12 THEN 'DIC'
		END,'/',DATE_FORMAT(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)),'%y')) AS descripcion,fecha
		FROM deposito WHERE fecha BETWEEN $fechainicio AND $fechafinal AND sucursal=$idsucursal
		GROUP BY ficha
		UNION
		SELECT SUM(cantidad) cantidad,ficha,CONCAT('DEP.',
		IF(DAY(fecha)<10,CONCAT('0',DAY(fecha)),DAY(fecha)),'/',
		CASE WHEN MONTH(fecha) = 1 THEN 'ENE'
		WHEN MONTH(fecha) = 2 THEN 'FEB'
		WHEN MONTH(fecha) = 3 THEN 'MAR'
		WHEN MONTH(fecha) = 4 THEN 'ABR'
		WHEN MONTH(fecha) = 5 THEN 'MAY'
		WHEN MONTH(fecha) = 6 THEN 'JUN'
		WHEN MONTH(fecha) = 7 THEN 'JUL'
		WHEN MONTH(fecha) = 8 THEN 'AGO'
		WHEN MONTH(fecha) = 9 THEN 'SEP'
		WHEN MONTH(fecha) = 10 THEN 'OCT'
		WHEN MONTH(fecha) = 11 THEN 'NOV'
		WHEN MONTH(fecha) = 12 THEN 'DIC'
		END,'/',DATE_FORMAT(fecha,'%y'),' C.',
		IF(DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))<10,
		CONCAT('0',DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))),
		DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))),'/',
		CASE WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 1 THEN 'ENE'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 2 THEN 'FEB'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 3 THEN 'MAR'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 4 THEN 'ABR'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 5 THEN 'MAY'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 6 THEN 'JUN'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 7 THEN 'JUL'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 8 THEN 'AGO'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 9 THEN 'SEP'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 10 THEN 'OCT'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 11 THEN 'NOV'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 12 THEN 'DIC'
		END,'/',DATE_FORMAT(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)),'%y')) AS descripcion,fecha
		FROM depositodetalle WHERE fecha BETWEEN $fechainicio AND $fechafinal AND sucursal=$idsucursal
		GROUP BY ficha)t ORDER BY fecha";
	$r = mysql_query($s,$l) or die(mysql_error($l)."-".$s);
	while($f = mysql_fetch_object($r)){
		echo "M $cuentamov                   0".str_pad($f->cantidad,17," ",STR_PAD_LEFT)." 000             0.00 $f->descripcion	\r\n";
	}
		
	$s = "SELECT cantidad,ficha,descripcion,fecha FROM(
		SELECT SUM(cantidad) cantidad,ficha,CONCAT('DEP.',
		IF(DAY(fecha)<10,CONCAT('0',DAY(fecha)),DAY(fecha)),'/',
		CASE WHEN MONTH(fecha) = 1 THEN 'ENE'
		WHEN MONTH(fecha) = 2 THEN 'FEB'
		WHEN MONTH(fecha) = 3 THEN 'MAR'
		WHEN MONTH(fecha) = 4 THEN 'ABR'
		WHEN MONTH(fecha) = 5 THEN 'MAY'
		WHEN MONTH(fecha) = 6 THEN 'JUN'
		WHEN MONTH(fecha) = 7 THEN 'JUL'
		WHEN MONTH(fecha) = 8 THEN 'AGO'
		WHEN MONTH(fecha) = 9 THEN 'SEP'
		WHEN MONTH(fecha) = 10 THEN 'OCT'
		WHEN MONTH(fecha) = 11 THEN 'NOV'
		WHEN MONTH(fecha) = 12 THEN 'DIC'
		END,'/',DATE_FORMAT(fecha,'%y'),' C.',
		IF(DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))<10,
		CONCAT('0',DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))),
		DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))),'/',
		CASE WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 1 THEN 'ENE'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 2 THEN 'FEB'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 3 THEN 'MAR'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 4 THEN 'ABR'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 5 THEN 'MAY'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 6 THEN 'JUN'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 7 THEN 'JUL'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 8 THEN 'AGO'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 9 THEN 'SEP'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 10 THEN 'OCT'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 11 THEN 'NOV'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 12 THEN 'DIC'
		END,'/',DATE_FORMAT(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)),'%y')) AS descripcion,fecha
		FROM deposito WHERE fecha BETWEEN $fechainicio AND $fechafinal AND sucursal=$idsucursal
		GROUP BY ficha
		UNION
		SELECT SUM(cantidad) cantidad,ficha,CONCAT('DEP.',
		IF(DAY(fecha)<10,CONCAT('0',DAY(fecha)),DAY(fecha)),'/',
		CASE WHEN MONTH(fecha) = 1 THEN 'ENE'
		WHEN MONTH(fecha) = 2 THEN 'FEB'
		WHEN MONTH(fecha) = 3 THEN 'MAR'
		WHEN MONTH(fecha) = 4 THEN 'ABR'
		WHEN MONTH(fecha) = 5 THEN 'MAY'
		WHEN MONTH(fecha) = 6 THEN 'JUN'
		WHEN MONTH(fecha) = 7 THEN 'JUL'
		WHEN MONTH(fecha) = 8 THEN 'AGO'
		WHEN MONTH(fecha) = 9 THEN 'SEP'
		WHEN MONTH(fecha) = 10 THEN 'OCT'
		WHEN MONTH(fecha) = 11 THEN 'NOV'
		WHEN MONTH(fecha) = 12 THEN 'DIC'
		END,'/',DATE_FORMAT(fecha,'%y'),' C.',
		IF(DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))<10,
		CONCAT('0',DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))),
		DAY(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)))),'/',
		CASE WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 1 THEN 'ENE'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 2 THEN 'FEB'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 3 THEN 'MAR'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 4 THEN 'ABR'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 5 THEN 'MAY'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 6 THEN 'JUN'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 7 THEN 'JUL'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 8 THEN 'AGO'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 9 THEN 'SEP'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 10 THEN 'OCT'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 11 THEN 'NOV'
		WHEN MONTH(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2))) = 12 THEN 'DIC'
		END,'/',DATE_FORMAT(IF(DAYOFWEEK(fecha)!=1,ADDDATE(fecha,-1),ADDDATE(fecha,-2)),'%y')) AS descripcion,fecha
		FROM depositodetalle WHERE fecha BETWEEN $fechainicio AND $fechafinal AND sucursal=$idsucursal
		GROUP BY ficha)t ORDER BY fecha";
	$r = mysql_query($s,$l) or die(mysql_error($l)."-".$s);
	while($f = mysql_fetch_object($r)){
		echo "M ".$inicuenta.$cuentacontable.$fincuenta."                   1".str_pad($f->cantidad,17," ",STR_PAD_LEFT)." 000             0.00 $f->descripcion	\r\n";
		}
?>