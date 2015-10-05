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
			FORMAT((100 * SUM(rv.total) / g.consumomensual),2) porc,rv.fecharealizacion
			FROM reportes_ventas rv 
			INNER JOIN generacionconvenio g ON rv.convenio = g.folio
			WHERE rv.idcliente = ".$_GET[cliente]." AND rv.activo = 'S' $adicional 
			GROUP BY MONTH(rv.fecharealizacion)) t GROUP BY mes ORDER BY MONTH(fecharealizacion)";
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
		$x =rand(1,1000); 
		$s = "DROP TABLE IF EXISTS tmp_convenio$x";
		mysql_query($s,$l) or die($s);
		$s = "DROP TABLE IF EXISTS tmp_clientes$x";
		mysql_query($s,$l) or die($s);
		
		/* tabla de convenios */
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
		FROM generacionconvenio GROUP BY idcliente;";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		/* tabla de clientes */
		$s = "CREATE TABLE `tmp_clientes$x` (
		`id` DOUBLE NOT NULL AUTO_INCREMENT,
		`idcliente` DOUBLE DEFAULT NULL,
		`montoautorizado` DOUBLE DEFAULT NULL,
		`diascredito` DOUBLE DEFAULT NULL,
		`fecharevision` VARCHAR(20) COLLATE utf8_unicode_ci DEFAULT NULL,
		`fechapago` VARCHAR(20) COLLATE utf8_unicode_ci DEFAULT NULL,
		PRIMARY KEY  (`id`),
		KEY  `idcliente` (`idcliente`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        mysql_query($s,$l) or die($s);
		//guardar los clientes en la temporal
		$s = "INSERT INTO tmp_clientes$x
		SELECT NULL,sc.cliente,sc.montoautorizado,sc.diascredito,
		CONCAT(IF(sc.lunesrevision=1,'L,',''),IF(sc.martesrevision=1,'MA,',''),IF(sc.miercolesrevision=1,'MI,',''),
		IF(sc.juevesrevision=1,'J,',''),IF(sc.viernesrevision=1,'V,',''),IF(sc.sabadorevision=1,'S',''),
		IF(sc.semanarevision=1,'TODOS','')) fecharevision,
		CONCAT(IF(sc.lunespago=1,'L,',''),IF(sc.martespago=1,'MA,',''),IF(sc.miercolespago=1,'MI,',''),IF(sc.juevespago=1,'J,',''),
		IF(sc.viernespago=1,'V,',''),IF(sc.sabadopago=1,'S',''),IF(sc.semanapago=1,'TODOS','')) fechapago
		FROM solicitudcredito sc WHERE cliente= ".$_GET[cliente].";	";
		mysql_query($s,$l) or die($s); 
		
		$s = "SELECT idcliente FROM tmp_clientes$x";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = '""';
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
		/*registros*/
		$s = "SELECT temp.idcliente,temp.montoautorizado,temp.diascredito,temp.fecharevision,temp.fechapago,0 AS rotacioncobranza,
		(SUM(t.cargo)-SUM(t.abono)) AS consumido,(temp.montoautorizado-(SUM(t.cargo)-SUM(t.abono))) AS disponible
		FROM(	/* cargos */
		SELECT SUM($if) AS cargo,0 AS abono,tc.idcliente AS cliente
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idcliente=".$_GET[cliente]."
		GROUP BY tc.idcliente
		UNION
		SELECT SUM(gv.total) AS cargo,0 AS abono,pg.cliente
		FROM guiasventanilla gv	
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.cliente=".$_GET[cliente]."
		GROUP BY pg.cliente
		UNION
		SELECT SUM(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS cargo,0 AS abono,f.cliente
		FROM facturacion f 
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=".$_GET[cliente]."  AND f.tipoguia='empresarial' 
		GROUP BY f.cliente
		UNION
		SELECT SUM(IFNULL(f.total,0)) AS cargo,0 AS abono,f.cliente
		FROM facturacion f 
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.cliente=".$_GET[cliente]."  AND f.tipoguia!='empresarial' 
		GROUP BY f.cliente
		UNION	/* abonos */
		SELECT 0 AS cargo,SUM(fp.total) AS abono,fp.cliente
		FROM formapago fp 
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=".$_GET[cliente]."
		GROUP BY fp.cliente
		)t INNER JOIN tmp_clientes$x temp ON t.cliente = temp.idcliente";
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
		
		$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s);
		$s = "DROP TABLE tmp_clientes$x";
			mysql_query($s,$l) or die($s);
			
	}else if($_GET[accion]==7){//OBTENER ESTADO DE CUENTA
		$x =rand(1,1000); 
		/* tabla de convenios */
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
		FROM generacionconvenio WHERE idcliente= $_GET[cliente];";
		mysql_query($s,$l) or die($s); 
		//agregar el convenio y la sucursal
		$s = "UPDATE tmp_convenio$x tc INNER JOIN generacionconvenio gc ON tc.folio=gc.folio SET idsucursal=gc.sucursal;";
		mysql_query($s,$l) or die($s); 
		
		$s = "CREATE TABLE `movimientos_tmp$x` (                                                  
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
		
		$s = "SELECT ivaretenido FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$ivaretenido = $f->ivaretenido;
		$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
		(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
		
		//insertar el saldo viejo
		$s = "INSERT INTO movimientos_tmp$x
		SELECT NULL,fecha,sucursal,IFNULL(refcargo,'') referenciacargo,IFNULL(refabono,'') referenciaabono,SUM(cargos),
		SUM(abonos),SUM(saldo),descripcion	
		FROM(	/* cargos */
		SELECT ge.fecha,cs.prefijo AS sucursal,ge.id AS refcargo,0 AS refabono,SUM($if) AS cargos,0 AS abonos,SUM($if) AS saldo,'' AS descripcion
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idcliente=$_GET[cliente] AND MONTH(ge.fecha)!=MONTH(CURRENT_DATE()) AND 
		(YEAR(ge.fecha)=YEAR(CURRENT_DATE()) OR YEAR(ge.fecha)!=YEAR(CURRENT_DATE())) GROUP BY tc.idcliente
		UNION
		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS refcargo,0 AS refabono,SUM(gv.total) AS cargos,0 AS abonos,SUM(gv.total) AS saldo,'' AS descripcion
		FROM guiasventanilla gv	
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.cliente=$_GET[cliente] 
		AND MONTH(gv.fecha)!=MONTH(CURRENT_DATE()) AND (YEAR(gv.fecha)=YEAR(CURRENT_DATE()) OR YEAR(gv.fecha)!=YEAR(CURRENT_DATE())) GROUP BY pg.cliente
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,fd.folio AS refcargo,0 AS refabono,SUM(fd.total) AS cargos,0 AS abonos,SUM(fd.total) AS saldo,'' AS descripcion
		FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.cliente=$_GET[cliente]
		AND MONTH(f.fecha)!=MONTH(CURRENT_DATE()) AND (YEAR(f.fecha)=YEAR(CURRENT_DATE()) OR YEAR(f.fecha)!=YEAR(CURRENT_DATE())) GROUP BY fd.folio,f.cliente
		UNION	/* abonos */
		SELECT fp.fecha,cs.prefijo AS sucursal,0 AS refcargo,fp.guia AS refabono,0 AS cargos,SUM(fp.total) AS abonos,SUM(fp.total) AS saldo,
		CONCAT(IF(fp.efectivo>0,'EFECTIVO, ',''),IF(fp.tarjeta>0,'TARJETA, ',''),IF(fp.transferencia>0,'TRANSFERENCIA, ',''),
		IF(fp.cheque>0,CONCAT('CHEQUE ',IFNULL(fp.ncheque,'')),'')) AS descripcion
		FROM formapago fp 
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[cliente]
		AND MONTH(fp.fecha)!=MONTH(CURRENT_DATE()) AND (YEAR(fp.fecha)=YEAR(CURRENT_DATE()) OR YEAR(fp.fecha)!=YEAR(CURRENT_DATE())) GROUP BY fp.cliente)t1";
		mysql_query($s,$l) or die($s);
		//insertar lo nuevo
		$s = "INSERT INTO movimientos_tmp$x
		SELECT NULL,fecha,sucursal,IFNULL(refcargo,'') referenciacargo,IFNULL(refabono,'') referenciaabono,cargos,abonos,saldo,
		descripcion	
		FROM(	/* cargos */
		SELECT ge.fecha,cs.prefijo AS sucursal,ge.id AS refcargo,0 AS refabono,$if AS cargos,0 AS abonos,$if AS saldo,'' AS descripcion
		FROM guiasempresariales ge 
		INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
		INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
		WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
		AND ISNULL(ge.factura) AND tc.idcliente=$_GET[cliente] AND MONTH(ge.fecha)=MONTH(CURRENT_DATE()) AND YEAR(ge.fecha)=YEAR(CURRENT_DATE())
		UNION
		SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS refcargo,0 AS refabono,gv.total AS cargos,0 AS abonos,gv.total AS saldo,'' AS descripcion
		FROM guiasventanilla gv	
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
		WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND pg.cliente=$_GET[cliente] 
		AND MONTH(gv.fecha)=MONTH(CURRENT_DATE()) AND YEAR(gv.fecha)=YEAR(CURRENT_DATE())
		UNION
		SELECT f.fecha,cs.prefijo AS sucursal,fd.folio AS refcargo,0 AS refabono,fd.total AS cargos,0 AS abonos,fd.total AS saldo,'' AS descripcion
		FROM facturacion f 
		INNER JOIN facturadetalle fd ON f.folio=fd.factura
		INNER JOIN catalogosucursal cs ON f.idsucursal=cs.id
		WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.estadocobranza <> 'C' AND f.cliente=$_GET[cliente]
		AND MONTH(f.fecha)=MONTH(CURRENT_DATE()) AND YEAR(f.fecha)=YEAR(CURRENT_DATE()) GROUP BY fd.folio
		UNION	/* abonos */
		SELECT fp.fecha,cs.prefijo AS sucursal,0 AS refcargo,fp.guia AS refabono,0 AS cargos,fp.total AS abonos,fp.total AS saldo,
		CONCAT(IF(fp.efectivo>0,'EFECTIVO, ',''),IF(fp.tarjeta>0,'TARJETA, ',''),IF(fp.transferencia>0,'TRANSFERENCIA, ',''),
		IF(fp.cheque>0,CONCAT('CHEQUE ',IFNULL(fp.ncheque,'')),'')) AS descripcion
		FROM formapago fp 
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE (fp.procedencia='A' OR fp.procedencia='C') AND fp.cliente=$_GET[cliente]
		AND MONTH(fp.fecha)=MONTH(CURRENT_DATE()) AND YEAR(fp.fecha)=YEAR(CURRENT_DATE()))t1 ORDER BY fecha";
		mysql_query($s,$l) or die($s);
		
		
		/*total de registros*/
		$s = "SELECT id FROM  movimientos_tmp$x";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$s = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,FORMAT(SUM(cargos),2) cargos,FORMAT(SUM(abonos),2) abonos
		FROM movimientos_tmp$x";
		$r = mysql_query($s,$l) or die(mysql_error($l));
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
				
		/*registros*/
		$s = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,sucursal,referenciacargo,referenciaabono,cargos,abonos,saldo,
		descripcion	
		FROM movimientos_tmp$x ORDER BY fecha $limite";
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
		
		$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s);
		$s = "DROP TABLE movimientos_tmp$x";
			mysql_query($s,$l) or die($s);
			
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
		FORMAT(((g.consumomensual - SUM(rv.total)) *100)/1000,2) AS porc,rv.fecharealizacion
		FROM reportes_ventas rv INNER JOIN generacionconvenio g ON rv.convenio = g.folio
		WHERE rv.idcliente = ".$_GET[cliente]." AND rv.activo = 'S' $adicional
		GROUP BY MONTH(rv.fecharealizacion)) R1 y	GROUP BY mes ORDER BY MONTH(fecharealizacion)";
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