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
		$s = "SELECT id
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' 
		group by prefijosucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT COUNT(DISTINCT(idcliente)) AS clientes, 
		format(SUM(
			IF(IFNULL(fechafactura,fecha)>=CURRENT_DATE,total,0)
		),2) AS carteravigente,
		format(SUM(
			IF(IFNULL(fechafactura,fecha)<CURRENT_DATE,total,0)
		),2) AS carteramorosa,
		format(SUM(total),0) AS carteratotal
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT prefijosucursal as sucursal, COUNT(DISTINCT(idcliente)) AS clientes, 
		SUM(
			IF(IFNULL(fechafactura,fecha)>=CURRENT_DATE,total,0)
		) AS carteravigente,
		SUM(
			IF(IFNULL(fechafactura,fecha)<CURRENT_DATE,total,0)
		) AS carteramorosa,
		SUM(total) AS carteratotal
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' 
		group by prefijosucursal
		$limite";
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
	
	if($_GET[accion]==2){
		/*total de registros*/
		$s = "SELECT rc2.idcliente, rc2.cliente, rc2.montoautorizado, rc2.diascredito,
		rc2.fecharevision, rc2.fechapago, rc2.rotacioncobranza
		FROM reporte_cobranza2 rc2
		INNER JOIN reporte_cobranza5 rc5 ON rc2.foliocredito = rc5.foliocredito
		WHERE rc5.prefijosucursal = '$_GET[prefijosucursal]'
		GROUP BY rc2.idcliente";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = '""';
		
		/*registros*/
		$s = "SELECT rc2.idcliente, rc2.cliente, rc2.montoautorizado, rc2.diascredito,
		rc2.fecharevision, rc2.fechapago, rc2.rotacioncobranza
		FROM reporte_cobranza2 rc2
		INNER JOIN reporte_cobranza5 rc5 ON rc2.foliocredito = rc5.foliocredito
		WHERE rc5.prefijosucursal = '$_GET[prefijosucursal]'
		GROUP BY rc2.idcliente
		$limite";
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
	
	if($_GET[accion]==3){
		/*total de registros*/
		$s = "SELECT DATE_FORMAT(fechacredito, '%d/%m/%Y') AS fecha, 
		montoautorizado, usuario, IFNULL(solicitud,'') AS solicitud
		FROM reporte_cobranza3
		WHERE idcliente = '$_GET[idcliente]'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = '""';
		
		/*registros*/
		$s = "SELECT DATE_FORMAT(fechacredito, '%d/%m/%Y') AS fecha, 
		montoautorizado, usuario, IFNULL(solicitud,'') AS solicitud
		FROM reporte_cobranza3
		WHERE idcliente = '$_GET[idcliente]'
		$limite";
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
	
	if($_GET[accion]==4){
		/*total de registros*/
		$s = "SELECT id
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0 AND prefijosucursal = '$_GET[sucursalprefijo]'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT format(SUM(IF(IFNULL(fechavencimiento,fechavencimientof)<CURRENT_DATE,total,0)),2) AS vencido,
		format(SUM(IF(IFNULL(fechavencimiento,fechavencimientof)>CURRENT_DATE,total,0)),2) AS alcorriente,
		format(SUM(total),2) AS total
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0 AND prefijosucursal = '$_GET[sucursalprefijo]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT prefijosucursal, cliente,folio, IFNULL(fechafactura,fecha) AS fecha, 
		IFNULL(fechavencimiento,fechavencimientof) AS fechavenc, 
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<0,0,DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<=0,total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<16 
		AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>0,total,0) c1a15dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<31 
		AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>15,total,0) c16a30dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))<61 
		AND DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>30,total,0) c31a60dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(fechavencimiento,fechavencimientof))>60,total,0) may60dias,
		total AS saldo,
		IFNULL(factura,'') AS factura, IFNULL(contrarecibo,'') AS contrarecibo
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0 AND prefijosucursal = '$_GET[sucursalprefijo]'
		$limite";
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
	
	if($_GET[accion]==5){
		
		/* proceso para llenar la temporal */
		
		$f1 = split("/",$_GET[fecha1]);
		$f2 = split("/",$_GET[fecha2]);
		$fecha1 = $f1[2]."-".$f1[1]."-".$f1[0];
		$fecha2 = $f2[2]."-".$f2[1]."-".$f2[0];
	
		$s = "CREATE TEMPORARY TABLE `movimientos_tmp` (                                                  
          `id` DOUBLE NOT NULL AUTO_INCREMENT,                                  
          `fecha` DATE DEFAULT NULL,  
          `sucursal` VARCHAR(50) COLLATE utf8_general_ci DEFAULT NULL,                                           
          `referenciacargo` VARCHAR(250) COLLATE utf8_general_ci DEFAULT NULL,  
          `referenciaabono` VARCHAR(20) COLLATE utf8_general_ci DEFAULT NULL,   
          `cargos` DOUBLE DEFAULT NULL,                                         
          `abonos` DOUBLE DEFAULT NULL,                                         
          `saldo` DOUBLE DEFAULT NULL,                                          
          `descripcion` VARCHAR(100) COLLATE utf8_general_ci DEFAULT NULL,      
          PRIMARY KEY  (`id`)                                                   
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
        mysql_query($s,$l) or die($s);
        
		//se insertan los movimientos anteriores
		$s = "INSERT INTO movimientos_tmp (saldo)
		SELECT IFNULL(SUM(cargo)-SUM(abono),0) AS saldo
		FROM reporte_cobranza4
		WHERE fecha < '$fecha1' 
		and idcliente = $_GET[idcliente] and prefijosucursal = '$_GET[prefijosucursal]'
		and reporte_cobranza4.estado <> 'DESACTIVADO'
		HAVING saldo>0;"; 
		$r = mysql_query($s,$l) or die($s);
		
		$s = "SELECT IFNULL(SUM(cargo)-SUM(abono),0) AS saldo
		FROM reporte_cobranza4
		WHERE fecha < '$fecha1' 
		and idcliente = $_GET[idcliente] and prefijosucursal = '$_GET[prefijosucursal]'
		and reporte_cobranza4.estado <> 'DESACTIVADO'
		HAVING saldo>0 ;";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$saldo = $f->saldo;
		
		//se insertan los nuevos
		$s = "SELECT reporte_cobranza4.*, cargo FROM reporte_cobranza4 
		WHERE fecha BETWEEN '$fecha1' AND '$fecha2'
		and prefijosucursal = '$_GET[prefijosucursal]'
		and reporte_cobranza4.estado <> 'DESACTIVADO'"; 
		$r = mysql_query($s,$l) or die($s);
		
		while($f=mysql_fetch_object($r)){
			$saldo = $saldo+$f->cargo;
			$saldo = $saldo-$f->abono;
			$s = "INSERT INTO movimientos_tmp
			SET fecha = '$f->fecha', sucursal = '$f->prefijosucursal', referenciacargo = '$f->folio', 
			referenciaabono = '$f->refabono', cargos = '$f->cargo', abonos = '$f->abono', saldo = '$saldo',
			descripcion = '$f->descripcion';";
			mysql_query($s,$l) or die($s);
		}
		
		/* fin del proceso */
		
		/*total de registros*/
		$s = "SELECT id
		FROM movimientos_tmp";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,
		sum(cargos) cargos, sum(abonos) abonos
		FROM movimientos_tmp";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,
		sucursal, referenciacargo, referenciaabono, cargos, abonos, saldo, descripcion
		FROM movimientos_tmp
		$limite";
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
		
	}

?>