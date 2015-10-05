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
	
	if($_GET[accion]==1){//OBTENER ESTADO DE CUENTA	
		
		/* proceso para llenar la temporal */
		
		if($_GET[sucursal]!=''){
			$sucursal_filtro = " AND idsucursal = '$_GET[sucursal]' ";
		}
		
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
		$s = "INSERT INTO movimientos_tmp (cargos,abonos,saldo,fecha)
		SELECT SUM(cargo),SUM(abono),IFNULL(SUM(cargo)-SUM(abono),0) AS saldo, adddate(current_date, interval -1 day)
		FROM reporte_cobranza4
		WHERE ((MONTH(fecha) < MONTH(CURDATE()) and year(fecha) = year(CURDATE())) or (year(fecha) < year(CURDATE())))
		and idcliente = ".$_GET[cliente]." and reporte_cobranza4.estado <> 'DESACTIVADO' $sucursal_filtro
		HAVING saldo>0"; 
		$r = mysql_query($s,$l) or die($s);
		
		$s = "SELECT IFNULL(SUM(cargo)-SUM(abono),0) AS saldo
		FROM reporte_cobranza4
		WHERE ((MONTH(fecha) < MONTH(CURDATE()) and year(fecha) = year(CURDATE())) or (year(fecha) < year(CURDATE())))
		and idcliente = $_GET[cliente]
		and reporte_cobranza4.estado <> 'DESACTIVADO' $sucursal_filtro
		HAVING saldo>0 ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$saldo = $f->saldo;
		
		//se insertan los nuevos
		$s = "SELECT reporte_cobranza4.*, cargo FROM reporte_cobranza4 
		WHERE MONTH(fecha) = MONTH(CURDATE()) and year(fecha) = year(CURDATE()) and idcliente = ".$_GET[cliente]."
		and reporte_cobranza4.estado <> 'DESACTIVADO' $sucursal_filtro"; 
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
		$s = "SELECT id FROM movimientos_tmp";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,
		format(sum(cargos),2) cargos, format(sum(abonos),2) abonos,
		format(sum(cargos)-sum(abonos),2) saldo
		FROM movimientos_tmp";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT ifnull(DATE_FORMAT(fecha, '%d/%m/%Y'),'') AS fecha,
		ifnull(sucursal,'') as sucursal, ifnull(referenciacargo,'') as referenciacargo,
		ifnull(referenciaabono,'') as referenciaabono, 		
		ifnull(cargos,0) as cargos, ifnull(abonos,0) as abonos, ifnull(saldo,0) as saldo,
		ifnull(descripcion,'') as descripcion
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
	
	}else if($_GET[accion]==2){
		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS cliente FROM catalogocliente WHERE id = ".$_GET[cliente]."";
		$r = mysql_query($s,$l) or die($s."\n".mysql_error($l));
		$f = mysql_fetch_object($r);
		$f->cliente = cambio_texto($f->cliente);
		echo "(".str_replace('null','""',json_encode($f)).")";
	
	}else if($_GET[accion]==3){
		$s = "SELECT * FROM embarquedemercancia WHERE folio = ".$_GET[embarque]." AND idsucursal = ".$_GET[sucursal]."";
		$r = mysql_query($s,$l) or die($s."\n".mysql_error($l));
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$s = "SELECT GROUP_CONCAT(d.sucursal) AS sucursal FROM catalogoruta cr
			INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
			WHERE cr.id = ".$f->ruta." AND tipo between 2 AND 3";
			$r = mysql_query($s,$l) or die($s);
			$cr = mysql_fetch_object($r);
			
			$s = "SELECT sucursalestransbordo FROM catalogorutadetalle WHERE ruta=".$f->ruta." AND tipo BETWEEN 2 AND 3";
			$ry = mysql_query($s,$l) or die($s);
		
			$sucursales = "";
			
			while($fy = mysql_fetch_object($ry)){			
				if(!empty($fy->sucursalestransbordo)){
					$ro = split(",",$fy->sucursalestransbordo);
					for($i=0;$i < count($ro);$i++){
						$y = split(":",$ro[$i]);
						for($j=0;$j < count($y);$j++){
							if(is_numeric($y[$j])){
								$sucursales .= $y[$j].",";
							}
						}
					}					
				}
			}
			
			if(!empty($sucursales)){
				$f->destinos = $cr->sucursal.",".substr($sucursales,0, strlen($sucursales)-1);
			}else{			
				$f->destinos = $cr->sucursal;
			}
			echo "(".str_replace('null','""',json_encode($f)).")";
		}else{
			echo "no existe";
		}
	
	}else if($_GET[accion]==4){
		$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion) AS sucursal
		FROM catalogosucursal cs WHERE id = ".$_GET[sucursal]."";	
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		echo "(".str_replace('null','""',json_encode($f)).")";
	
	}else if($_GET[accion]==5){//ANTIGUEDAD DE SALDOS
		if($_GET[todassucursales]!='true'){
			$s = "SELECT prefijo FROM catalogosucursal WHERE id = '".$_GET[sucursal]."'";
			$r = mysql_query($s,$l) or die($s);
			$ff = mysql_fetch_object($r);
		}

		
		if($_GET[todassucursales]!='true'){
			$andsucursal = " AND prefijosucursal = '$ff->prefijo' ";
		}
		/*total de registros*/
		$s = "SELECT id FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0 $andsucursal";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT FORMAT(IFNULL(SUM(IF(IFNULL(fechavencimiento,fechavencimientof)<CURRENT_DATE,total,0)),0),2) AS vencido,
		FORMAT(IFNULL(SUM(IF(IFNULL(fechavencimiento,fechavencimientof)>CURRENT_DATE,total,0)),0),2) AS alcorriente,
		FORMAT(IFNULL(SUM(total),0),2)  AS total
		FROM reporte_cobranza1
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0 $andsucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT rc.cliente, rc.prefijosucursal, rc.folio, IFNULL(DATE_FORMAT(rc.fecha,'%d/%m/%Y'),'') AS fechaguia, 
		IFNULL(DATE_FORMAT(rc.fechafactura,'%d/%m/%Y'),'') AS fechafactura,
		DATE_FORMAT(IFNULL(rc.fechavencimiento,rc.fechavencimientof),'%d/%m/%Y') AS fechavenc, 
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<0,0,DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<=0,rc.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<16 
		AND DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))>0,rc.total,0) c1a15dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<31 
		AND DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))>15,rc.total,0) c16a30dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))<61 
		AND DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))>30,rc.total,0) c31a60dias,
		IF(DATEDIFF(CURRENT_DATE,IFNULL(rc.fechavencimiento,rc.fechavencimientof))>60,rc.total,0) may60dias,
		total AS saldo,
		IFNULL(rc.factura,'') AS factura, IFNULL(co.contrarecibo,'') AS contrarecibo
		FROM reporte_cobranza1 rc
		LEFT JOIN registrodecontrarecibos co ON rc.factura = co.factura
		WHERE estado = 'ACTIVA' AND pagado = 'N' AND folio<>0 
		$andsucursal
		group by rc.folio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->cliente);
			$f->cliente = str_replace("&#38;","&",$f->cliente);
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
	
	}else if($_GET[accion]==6){
		if($_GET[sucursal]!="" && $_GET[sucursal]!=0){
			$sucuv = " AND gv.idsucursaldestino = $_GET[sucursal] ";
			$sucue = " AND ge.idsucursaldestino = $_GET[sucursal] ";
		}
		$s = "SELECT SUM(total) FROM
		(SELECT COUNT(*) AS total FROM guiasventanilla gv WHERE idremitente = ".$_GET[cliente]." 
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND estado <> 'CANCELADO' $sucuv
		UNION
		SELECT COUNT(*) AS total FROM guiasempresariales ge WHERE idremitente = ".$_GET[cliente]." 
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucue)t";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(total),2) AS total FROM
		(SELECT SUM(total) AS total FROM guiasventanilla gv WHERE idremitente = ".$_GET[cliente]." 
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND estado <> 'CANCELADO' $sucuv
		UNION
		SELECT SUM(total) AS total FROM guiasempresariales ge WHERE idremitente = ".$_GET[cliente]." 
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucue)t";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s = "SELECT * FROM(
		SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		IF(gv.tipoflete=0,'PAGADA','POR COBRAR') AS flete, 
		IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condicion, IF(gv.ocurre=0,'EAD','OCURRE') AS entrega, 
		ori.prefijo AS origen, des.prefijo AS destino, CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS destinatario,
		gv.total
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
		INNER JOIN catalogocliente re ON gv.iddestinatario = re.id
		WHERE gv.idremitente = ".$_GET[cliente]." 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.estado <> 'CANCELADO' $sucuv
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, ge.tipoflete AS flete, 
		ge.tipopago AS condicion, IF(ge.ocurre=0,'EAD','OCURRE') AS entrega, ori.prefijo AS origen,
		des.prefijo AS destino, CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS destinatario, ge.total
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
		INNER JOIN catalogocliente re ON ge.iddestinatario = re.id
		WHERE ge.idremitente = ".$_GET[cliente]." 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucue) t $limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$f->destinatario = cambio_texto($f->destinatario);
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