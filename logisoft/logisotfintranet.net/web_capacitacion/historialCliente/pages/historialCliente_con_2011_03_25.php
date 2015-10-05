<?	//session_start();
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
		
		if ($_GET[fecha]!=''){
			$adicional=" AND YEAR(rv.fecharealizacion)='".$_GET[fecha]."'";
		}else{
			$adicional=" AND rv.fecharealizacion>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS 
			DATE), INTERVAL -11 MONTH)";
		}
		
		$s = "SELECT CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		d.telefono, cc.celular, cc.email, ct.descripcion AS tipocliente,
		FORMAT(IFNULL(sc.montoautorizado,0),2) AS limitecredito,
		IF(gc.legal IS NOT NULL,UCASE(gc.legal),IF(sc.representantelegal IS NOT NULL, UCASE(sc.representantelegal),'')) AS legal
		FROM catalogocliente cc
		INNER JOIN direccion d ON cc.id = d.codigo
		LEFT JOIN catalogotipocliente ct ON cc.tipocliente = ct.id
		LEFT JOIN solicitudcredito sc ON cc.id = sc.cliente AND sc.estado = 'ACTIVADO'
		LEFT JOIN generacionconvenio gc ON cc.id = gc.idcliente AND gc.estadoconvenio = 'ACTIVADO'
		WHERE cc.id = ".$_GET[cliente]."/* AND d.facturacion='SI'*/ GROUP BY cliente";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);		
			$f->cliente = cambio_texto($f->cliente);
			$f->email = cambio_texto($f->email);
			$f->tipocliente = cambio_texto($f->tipocliente);
			$f->legal = cambio_texto($f->legal);
			
			$cliente = str_replace('null','""',json_encode($f));
			
			$s = "SELECT 
			(SELECT COUNT(*) FROM propuestaconvenio p WHERE p.idprospecto = ".$_GET[cliente]." AND p.tipo = 'CLI') tpropuesta,
			(SELECT COUNT(*) FROM generacionconvenio g WHERE idcliente = ".$_GET[cliente]." AND 
			DATEDIFF(vigencia,CURDATE()) <= (SELECT diasvencimientoconvenio FROM configuradorgeneral)) tconveniovencimiento,
			(SELECT COUNT(*) FROM solicitudtelefonica WHERE cliente = ".$_GET[cliente]." AND estado = 'POR SOLUCIONAR') tcat,
			(SELECT COUNT(*) FROM solicitudguiasempresarialesnw s WHERE s.ncliente = ".$_GET[cliente]." AND
			(s.status IS NULL OR s.status='')) tsolicitud,
			(SELECT COUNT(*) FROM entregasespecialesead WHERE IF(opcion2 = 0,remitente,destinatario) =  ".$_GET[cliente].") tespeciales";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			$alertas = str_replace('null','""',json_encode($f));
			
			$s = "SELECT(SELECT FORMAT(IFNULL(SUM(total),0),2) FROM pagoguias WHERE cliente = ".$_GET[cliente]." 
			AND fechacancelacion IS NULL) AS comprado,
			(SELECT FORMAT(IFNULL(SUM(total),0),2) FROM pagoguias WHERE cliente = ".$_GET[cliente]." AND pagado = 'S' 
			AND fechacancelacion IS NULL) AS pagado,
			(SELECT FORMAT(consumomensual,2) FROM generacionconvenio WHERE idcliente = ".$_GET[cliente]." AND estadoconvenio = 'ACTIVADO')
			AS compromisomensual,
			(SELECT FORMAT(IFNULL(SUM(total),0),2) FROM pagoguias WHERE cliente = ".$_GET[cliente]." AND 
			MONTH(fechacreo) = MONTH(CURDATE()) AND fechacancelacion IS NULL) AS consumido";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			$otros = str_replace('null','""',json_encode($f));
				
			$s = "SELECT CASE mes
				WHEN 'JANUARY' THEN 'Enero'
				WHEN 'FEBRUARY' THEN 'Febrero'
				WHEN 'MARCH' THEN 'Marzo'
				WHEN 'APRIL' THEN 'Abril'
				WHEN 'MAY' THEN 'Mayo'
				WHEN 'JUNE' THEN 'Junio'
				WHEN 'JULY' THEN 'Julio'
				WHEN 'AUGUST' THEN 'Agosto'
				WHEN 'SEPTEMBER' THEN 'Septiembre'
				WHEN 'OCTOBER' THEN 'Octubre'
				WHEN 'NOVEMBER' THEN 'Noviembre'
				WHEN 'DECEMBER' THEN 'Diciembre'
			END AS mes, 
			SUM(ventas) ventas,consumomensual,CONCAT(porc,'%') porc,0 AS entregas,0 AS recoleccion,0 AS saldo,0 AS consumo
			FROM
			(SELECT UPPER(MONTHNAME(rv.fecharealizacion)) mes,SUM(rv.total) ventas, FORMAT(g.consumomensual,2) consumomensual,
			FORMAT((100 * SUM(rv.total) / g.consumomensual),2) porc  FROM reportes_ventas rv 
			INNER JOIN generacionconvenio g ON rv.convenio = g.folio
			WHERE rv.idcliente = ".$_GET[cliente]." AND rv.activo = 'S' $adicional 
			GROUP BY MONTH(rv.fecharealizacion)) R1 GROUP BY mes";
			$arr = array();
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$arr[] = $f;
			}
			$detalle = str_replace('null','""',json_encode($arr));
			echo "({cliente:$cliente,alertas:$alertas,otros:$otros,detalle:$detalle})";
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==2){//OBTENER CONVENIO
		$s = "SELECT pc.folio, DATE_FORMAT(pc.fecha,'%d/%m/%Y') AS fecha,
		CONCAT(IF(pc.descuentosobreflete=1 OR pc.consignaciondescuento=1,'DESCUENTO,', ''), 
		IF(pc.precioporkg=1 OR pc.consignacionkg=1,'KILOGRAMO,',''), IF(pc.precioporcaja=1 OR 
		pc.consignacioncaja=1,'PAQUETE,',''), IF(pc.prepagadas=1,'PREPAGADA,','')) AS tipo,
		pc.estadopropuesta,pc.tipoautorizacion, DATE_FORMAT(pc.vigencia,'%d/%m/%Y') AS vigencia,
		cs.prefijo AS sucursal FROM propuestaconvenio pc
		INNER JOIN catalogosucursal cs ON pc.sucursal = cs.id
		WHERE pc.idprospecto = ".$_GET[cliente]." AND pc.tipo = 'CLI'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){	
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->prefijosucursal = cambio_texto($f->prefijosucursal);
				$f->cliente = cambio_texto($f->cliente);
				$arr[] = $f;
			}
			echo str_replace('null','""',json_encode($arr));
		}else{
			echo "no encontro";
		}
	
	}else if($_GET[accion]==3){//OBTENER ENTREGAS ESPECIALES
		$s = "SELECT e.folio, cs.descripcion AS sucursal, DATE_FORMAT(e.fechaespecial,'%d/%m/%Y') AS fecha,
		e.guia FROM entregasespecialesead e
		INNER JOIN catalogosucursal cs ON e.sucursal = cs.id
		WHERE IF(e.opcion2 = 0,e.remitente,e.destinatario) =  ".$_GET[cliente]."";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){	
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->sucursal = cambio_texto($f->sucursal);
				$f->guia = cambio_texto($f->guia);
				$arr[] = $f;
			}
			echo str_replace('null','""',json_encode($arr));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==4){//OBTENER CAT
		$s = "SELECT s.folio,DATE_FORMAT(s.fechaqueja,'%d/%m/%Y') fechaqueja,cs.descripcion AS sucursal,s.queja,
		IFNULL(s.guia,'') guia,IFNULL(s.folioatencion,'') folioatencion,IFNULL(s.recoleccion,'') recoleccion,
		IF(s.foliofaltante = 0,'', s.foliofaltante) AS foliofaltante
		FROM solicitudtelefonica s INNER JOIN catalogosucursal cs ON s.sucursal = cs.id
		WHERE s.cliente = ".$_GET[cliente]." AND s.estado = 'POR SOLUCIONAR'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){	
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->sucursal = cambio_texto($f->sucursal);
				$f->guia = cambio_texto($f->guia);
				$f->queja = cambio_texto($f->queja);
				$arr[] = $f;
			}
			echo str_replace('null','""',json_encode($arr));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==5){//OBTENER SOLICITUD DE GUIAS EMPRESARIALES
		$s = "SELECT s.folio, s.preocon, s.cantidad, IFNULL(cs.descripcion,'') AS sucursal,
		DATE_FORMAT(s.fecha,'%d/%m/%Y') as fecha 
		FROM solicitudguiasempresarialesnw s
		INNER JOIN catalogosucursal cs ON s.sucursal = cs.id
		WHERE s.ncliente = ".$_GET[cliente]." AND (s.status IS NULL OR s.status='')";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){	
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->sucursal = cambio_texto($f->sucursal);
				$f->preocon = cambio_texto($f->preocon);
				$arr[] = $f;
			}
			echo str_replace('null','""',json_encode($arr));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==6){//OBTENER ESTADO DE COBRANZA
		$s = "SELECT rc2.idcliente, rc2.cliente, rc2.montoautorizado, rc2.diascredito,
		rc2.fecharevision, rc2.fechapago, rc2.rotacioncobranza
		FROM reporte_cobranza2 rc2
		INNER JOIN reporte_cobranza4 t4 ON rc2.idcliente = t4.idcliente
		WHERE rc2.idcliente = ".$_GET[cliente]."
		HAVING idcliente IS NOT NULL";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = '""';
		
		/*registros*/
		$s = "SELECT rc2.idcliente, rc2.montoautorizado, rc2.diascredito,
		rc2.fecharevision, rc2.fechapago, rc2.rotacioncobranza,
		SUM(t4.cargo) - SUM(t4.abono) as consumido,
		(SELECT montoautorizado FROM solicitudcredito
		WHERE cliente = ".$_GET[cliente].") - (SUM(t4.cargo) - SUM(t4.abono)) as disponible
		FROM reporte_cobranza2 rc2
		left JOIN reporte_cobranza4 t4 ON rc2.idcliente = t4.idcliente
		WHERE t4.idcliente = ".$_GET[cliente]."
		HAVING idcliente IS NOT NULL
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$ar[] = $f;
		}
		$registros = json_encode($ar);
		$datos =  '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		echo str_replace("NULL","",$datos);
		
	}else if($_GET[accion]==7){//OBTENER ESTADO DE CUENTA
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
		WHERE ((MONTH(fecha) < MONTH(CURDATE()) and year(fecha) = year(CURDATE())) or (year(fecha) < year(CURDATE())))
		and idcliente = ".$_GET[cliente]." and reporte_cobranza4.estado <> 'DESACTIVADO'
		HAVING saldo>0"; 
		$r = mysql_query($s,$l) or die($s);
		
		$s = "SELECT IFNULL(SUM(cargo)-SUM(abono),0) AS saldo
		FROM reporte_cobranza4
		WHERE ((MONTH(fecha) < MONTH(CURDATE()) and year(fecha) = year(CURDATE())) or (year(fecha) < year(CURDATE())))
		and idcliente = $_GET[cliente]
		and reporte_cobranza4.estado <> 'DESACTIVADO'
		HAVING saldo>0 ";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$saldo = $f->saldo;
		
		//se insertan los nuevos
		$s = "SELECT reporte_cobranza4.*, cargo FROM reporte_cobranza4 
		WHERE MONTH(fecha) = MONTH(CURDATE()) and idcliente = ".$_GET[cliente]."
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
		$s = "SELECT id FROM movimientos_tmp";
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
	
	}else if($_GET[accion]==8){//OBTENER COMPROMISOS
		$s = "SELECT DATE_FORMAT(d.compromiso,'%d/%m/%Y') AS fcompromiso, d.factura, d.saldoactual, cs.prefijo,
		CONCAT_WS(' ',e.nombre,e.apellidopaterno,e.apellidomaterno) AS cobrador
		FROM liquidacioncobranza l
		INNER JOIN liquidacioncobranzadetalle d ON l.id = d.folioliquidacion
		INNER JOIN catalogosucursal cs ON l.sucursal = cs.id
		INNER JOIN catalogoempleado e ON l.cobrador = e.id
		WHERE d.cliente = ".$_GET[cliente]." AND l.estado<>'LIQUIDADO' AND d.compromiso<>'0000-00-00'
		GROUP BY d.factura";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){	
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$f->prefijo = cambio_texto($f->prefijo);
				$f->cobrador = cambio_texto($f->cobrador);
				$arr[] = $f;
			}
			echo str_replace('null','""',json_encode($arr));
		}else{
			echo "no encontro";
		}	
	
	}else if($_GET[accion]==9){//OBTENER DETALLE
		if ($_GET[fecha]!=''){
			$adicional=" AND YEAR(rv.fecharealizacion)='".$_GET[fecha]."'";
		}else{
			$adicional=" AND rv.fecharealizacion>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS 
			DATE), INTERVAL -11 MONTH)";
		}
		$s = "SELECT CASE mes
			WHEN 'JANUARY' THEN 'Enero'
			WHEN 'FEBRUARY' THEN 'Febrero'
			WHEN 'MARCH' THEN 'Marzo'
			WHEN 'APRIL' THEN 'Abril'
			WHEN 'MAY' THEN 'Mayo'
			WHEN 'JUNE' THEN 'Junio'
			WHEN 'JULY' THEN 'Julio'
			WHEN 'AUGUST' THEN 'Agosto'
			WHEN 'SEPTEMBER' THEN 'Septiembre'
			WHEN 'OCTOBER' THEN 'Octubre'
			WHEN 'NOVEMBER' THEN 'Noviembre'
			WHEN 'DECEMBER' THEN 'Diciembre'
		END AS mes,SUM(ventas) ventas,consumomensual,CONCAT(porc,'%') porc FROM
		(SELECT UPPER(MONTHNAME(rv.fecharealizacion)) mes,SUM(rv.total) ventas, FORMAT(g.consumomensual,2) consumomensual,
		FORMAT(((g.consumomensual - SUM(rv.total)) *100)/1000,2) AS porc
		FROM reportes_ventas rv INNER JOIN generacionconvenio g ON rv.convenio = g.folio
		WHERE rv.idcliente = ".$_GET[cliente]." AND rv.activo = 'S' $adicional
		GROUP BY MONTH(rv.fecharealizacion)) R1 y	GROUP BY mes";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){	
			$arr = array();
			while($f = mysql_fetch_object($r)){
				$arr[] = $f;
			}
			echo str_replace('null','""',json_encode($arr));
		}else{
			echo "no encontro";
		}	
	}
?>