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
		$s = "SELECT id FROM movimientos_tmp WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,
		format(sum(cargos),2) cargos, format(sum(abonos),2) abonos,
		format(sum(cargos)-sum(abonos),2) saldo
		FROM movimientos_tmp WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT ifnull(DATE_FORMAT(fecha, '%d/%m/%Y'),'') AS fecha,
		ifnull(sucursal,'') as sucursal, ifnull(referenciacargo,'') as referenciacargo,
		ifnull(referenciaabono,'') as referenciaabono, 		
		ifnull(cargos,0) as cargos, ifnull(abonos,0) as abonos, ifnull(saldo,0) as saldo,
		ifnull(descripcion,'') as descripcion
		FROM movimientos_tmp WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."' $limite";
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
			$andsucursal1 = " AND tc.idsucursal = '".$_GET[sucursal]."'"; 
			$andsucursal2 = " AND pg.sucursalacobrar = '".$_GET[sucursal]."'"; 
			$andsucursal3 = " AND f.idsucursal = '".$_GET[sucursal]."'"; 
		}
		if($_GET[idCliente]!=''){
			$folioCliente = " AND temp.idcliente = $_GET[idCliente]";
		}
		$x =rand(1,1000); 
		/* tabla de convenios */  //$andsucursal
		$s = "CREATE TABLE `tmp_convenio$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`folio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`idsucursal` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los convenios en la temporal
		$s = "INSERT INTO tmp_convenio$x
		SELECT NULL,MAX(folio) AS folio,idcliente,NULL
		FROM generacionconvenio WHERE estadoconvenio!='AUTORIZADO' AND estadoconvenio!='IMPRESO' AND estadoconvenio!='NO ACTIVADO' GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		/* tabla de clientes */
		$s = "CREATE TABLE `tmp_clientes$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`nfolio` DOUBLE DEFAULT NULL,
		`idcliente` DOUBLE DEFAULT NULL,
		`ncliente` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		`sucursal` DOUBLE DEFAULT NULL,
		`dcredito` DOUBLE DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los clientes en la temporal
		$s = "INSERT INTO tmp_clientes$x
		SELECT NULL,0 AS nfolio,pg.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM guiasempresariales ge INNER JOIN pagoguias pg ON ge.id = pg.guia
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND pg.pagado = 'N' GROUP BY pg.cliente
		UNION 
		SELECT NULL,0 AS nfolio,pg.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.pagado = 'N' GROUP BY pg.cliente
		UNION
		SELECT NULL,0 AS nfolio,f.cliente AS idcliente,0 AS ncliente,0 AS sucursal,0 AS dcredito 
		FROM facturacion f WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' GROUP BY f.cliente;";
		mysql_query($s,$l) or die($s); 
		//agregar datos a la temporal
		$s = "UPDATE tmp_clientes$x temp INNER JOIN solicitudcredito sc ON temp.idcliente=sc.cliente
		SET dcredito=sc.diascredito,sucursal=sc.idsucursal,nfolio=sc.folio,ncliente=CONCAT(sc.nombre,' ',sc.paterno,' ',sc.materno)";
		mysql_query($s,$l) or die($s); 
		
		/*total de registros*/   
		$s = "SELECT ge.id FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN tmp_clientes$x temp ON ge.idremitente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) $folioCliente $andsucursal1
		UNION
		SELECT gv.id FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN tmp_clientes$x temp ON gv.idremitente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.pagado = 'N' $andsucursal2 $folioCliente  
		UNION
		SELECT fd.folio AS id FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND	f.tipoguia='ventanilla' $folioCliente $andsucursal3
		GROUP BY fd.folio
		UNION
		SELECT fd.folio AS id FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND	f.tipoguia='empresarial' $folioCliente $andsucursal3
		GROUP BY fd.folio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT FORMAT(SUM(vencido),2) AS vencido,FORMAT(SUM(alcorriente),2) AS alcorriente,FORMAT(SUM(total),2) AS total
		FROM(
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>0,ge.total,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))=0,ge.total,0)) AS alcorriente,SUM(ge.total) total
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON ge.idremitente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) $andsucursal1 $folioCliente
		UNION
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>0,gv.total,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))=0,gv.total,0)) AS alcorriente,SUM(gv.total) total
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON gv.idremitente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.pagado = 'N' $andsucursal2 $folioCliente
		UNION
		SELECT SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>0,fd.total,0)) AS vencido,
		SUM(IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,fd.total,0)) AS alcorriente, SUM(fd.total) total
		FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' $folioCliente $andsucursal3)t1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		/*registros*/
		$s = "SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,ge.id AS folio,ge.fecha AS fechaguia,'' AS fechafactura,
		IFNULL(ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,ge.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<16,ge.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<31,ge.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL temp.dcredito DAY))<61,ge.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(ge.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,ge.total,0) AS may60dias,
		ge.total AS saldo,0 AS factura,IFNULL(ge.acuserecibo,0)AS contrarecibo 
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON ge.idremitente=temp.idcliente
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) $andsucursal1 $folioCliente
		UNION
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,gv.id AS folio,gv.fecha AS fechaguia,'' AS fechafactura,
		IFNULL(ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,gv.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<16,gv.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<31,gv.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL temp.dcredito DAY))<61,gv.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(gv.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,gv.total,0) AS may60dias,
		gv.total AS saldo,0 AS factura,IFNULL(gv.acuserecibo,0)AS contrarecibo FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		INNER JOIN tmp_clientes$x temp ON gv.idremitente=temp.idcliente
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.pagado = 'N' $folioCliente $andsucursal2
		UNION
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,fd.folio,gv.fecha AS fechaguia,f.fecha AS fechafactura,
		IFNULL(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,fd.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<16,fd.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<31,fd.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<61,fd.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,fd.total,0) AS may60dias,
		fd.total AS saldo,IFNULL(f.folio,'')AS factura,0 AS contrarecibo
		FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN guiasventanilla gv ON fd.folio=gv.id
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia='ventanilla' $andsucursal3 $folioCliente GROUP BY fd.folio 
		UNION
		SELECT cs.prefijo AS prefijosucursal,temp.ncliente AS cliente,fd.folio,ge.fecha AS fechaguia,f.fecha AS fechafactura,
		IFNULL(ADDDATE(f.fecha,INTERVAL temp.dcredito DAY),'') AS fechavenc,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0,
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY)),0) AS diasvencidos,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,0) DAY))<=0,fd.total,0) AS alcorriente,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>0 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<16,fd.total,0) AS c1a15dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>15 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<31,fd.total,0) AS c16a30dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))>30 AND
		DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL temp.dcredito DAY))<61,fd.total,0) AS c31a60dias,
		IF(DATEDIFF(CURRENT_DATE(),ADDDATE(f.fecha,INTERVAL IFNULL(temp.dcredito,1) DAY))>60,fd.total,0) AS may60dias,
		fd.total AS saldo,IFNULL(f.folio,'')AS factura,0 AS contrarecibo
		FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN guiasempresariales ge ON fd.folio=ge.id
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		INNER JOIN tmp_clientes$x temp ON f.cliente=temp.idcliente
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.tipoguia='empresarial' $andsucursal3 $folioCliente 
		GROUP BY fd.folio $limite";
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
		
		$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s); 
		$s = "DROP TABLE tmp_clientes$x";
			mysql_query($s,$l) or die($s); 
	
	}else if($_GET[accion]==6){
		if($_GET[sucursal]!="" && $_GET[sucursal]!=0){
			$sucuv = " AND gv.idsucursaldestino = $_GET[sucursal] ";
			$sucue = " AND ge.idsucursaldestino = $_GET[sucursal] ";
		}
		$s = "SELECT SUM(total) total FROM
		(SELECT COUNT(*) AS total FROM guiasventanilla gv WHERE idremitente = ".$_GET[cliente]." 
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND estado <> 'CANCELADO' $sucuv
		UNION
		SELECT COUNT(*) AS total FROM guiasempresariales ge WHERE idremitente = ".$_GET[cliente]." 
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucue)t";
		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totalregistros = $f->total;
		
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
		gv.total,ifnull(DATE_FORMAT(gv.fechaentrega, '%d/%m/%Y'),'') fechaentrega, ifnull(gv.recibio,'') recibio
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
		des.prefijo AS destino, CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS destinatario, ge.total,
		ifnull(DATE_FORMAT(ge.fechaentrega, '%d/%m/%Y'),'') fechaentrega, ifnull(ge.recibio,'') recibio
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