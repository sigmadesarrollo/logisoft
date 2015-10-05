<?
	include("../../coneccion/Coneccion.php");
	class Ventas{
		var $c;
		
		public function Ventas(){
			$this->c = new Coneccion();
		}
		
		public function getGeneralMes(){
			$s = "SELECT CASE tipoventa
			WHEN 'GUIA VENTANILLA' THEN 'G Ventanilla'
			WHEN 'GUIA EMPRESARIAL' THEN 'G Empresariales'
			WHEN 'SOLICITUD DE FOLIOS' THEN 'Solicitud Folios'
			WHEN 'FACTURA EXCEDENTE' THEN 'F Excedente'
			WHEN 'FACTURA OTROS' THEN 'F Otros'
			END AS tipo,fechafacturacion,fecharealizacion,
			ROUND(IFNULL(SUM(total),0),2) AS total
			FROM reportes_ventas INNER JOIN catalogosucursal cs ON 
			IF(tipoventa <> 'GUIA VENTANILLA',sucursalfacturo=cs.descripcion,sucursalrealizo=cs.id)
			WHERE activo = 'S' AND IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion) = YEAR(CURRENT_DATE),YEAR(fecharealizacion) = YEAR(CURRENT_DATE)) 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND cs.id = ".$_SESSION[IDSUCURSAL]."":"")."
			GROUP BY tipoventa";
			return $this->c->consultar($s);
		}
			
		public function getGeneralAnual(){
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
			END AS mes, ROUND(SUM(solicitudesfolio),2) AS solicitudesfolio,
			ROUND(SUM(guiasventanilla),2) AS guiasventanilla,ROUND(SUM(guiasempresariales),2) AS guiasempresariales
			FROM
			(SELECT UPPER(MONTHNAME(IF(tipoventa <> 'GUIA VENTANILLA',fechafacturacion,fecharealizacion))) AS mes,fecharealizacion,tipoventa,fechafacturacion,
				   SUM(CASE tipoventa WHEN 'SOLICITUD DE FOLIOS' THEN total ELSE 0 END) AS solicitudesfolio,
				   SUM(CASE tipoventa WHEN 'GUIA VENTANILLA' THEN total ELSE 0 END) AS guiasventanilla,
				   SUM(CASE tipoventa WHEN 'GUIA EMPRESARIAL' THEN total ELSE 0 END) AS guiasempresariales
			FROM reportes_ventas INNER JOIN catalogosucursal cs ON 
			IF(tipoventa <> 'GUIA VENTANILLA',sucursalfacturo=cs.descripcion,sucursalrealizo=cs.id)
			WHERE activo = 'S' AND IF(tipoventa <> 'GUIA VENTANILLA',
			fechafacturacion>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH),
			fecharealizacion>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH))
			".(($_SESSION[IDSUCURSAL]!=1)? " AND cs.id = ".$_SESSION[IDSUCURSAL]."":"")."
			GROUP BY UPPER(MONTHNAME(IF(tipoventa <> 'GUIA VENTANILLA',fechafacturacion,fecharealizacion))), tipoventa) R1
			GROUP BY mes ORDER BY IF(tipoventa <> 'GUIA VENTANILLA',fechafacturacion,fecharealizacion);";
			return $this->c->consultar($s);
		}
		
		public function getGeneralMesVentanilla($tipo){
			if($tipo=='G Ventanilla'){
				$s = "SELECT IF(tipoflete='PAGADA', IF(tipopago='CONTADO','PAG Contado', 'PAG Credito'),
				IF(tipopago='CREDITO','PCOB Contado', 'PCOB Credito')) AS tipo,
				ROUND(IFNULL(SUM(total),0),2) AS total
				FROM reportes_ventas INNER JOIN catalogosucursal cs ON 
				IF(tipoventa <> 'GUIA VENTANILLA',sucursalfacturo=cs.descripcion,sucursalrealizo=cs.id)
				WHERE activo = 'S' AND IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion) = YEAR(CURRENT_DATE),
				YEAR(fecharealizacion) = YEAR(CURRENT_DATE)) AND tipoventa = 'GUIA VENTANILLA'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND cs.id = ".$_SESSION[IDSUCURSAL]."":"")."
				GROUP BY tipo";
			}
			if($tipo=='G Empresariales'){
				$s = "SELECT IF(tipoflete='PAGADA', IF(tipopago='CONTADO','PAG Contado', 'PAG Credito'),
				IF(tipopago='CREDITO','PCOB Contado', 'PCOB Credito')) AS tipo,
				ROUND(IFNULL(SUM(total),0),2) AS total
				FROM reportes_ventas INNER JOIN catalogosucursal cs ON 
				IF(tipoventa <> 'GUIA VENTANILLA',sucursalfacturo=cs.descripcion,sucursalrealizo=cs.id)
				WHERE activo = 'S' AND IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion) = YEAR(CURRENT_DATE),
				YEAR(fecharealizacion) = YEAR(CURRENT_DATE)) AND tipoventa = 'GUIA EMPRESARIAL'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND cs.id = ".$_SESSION[IDSUCURSAL]."":"")."
				GROUP BY tipo";
			}
			if($tipo=='Solicitud Folios'){
				$s = "SELECT tipopago AS tipo,
				ROUND(IFNULL(SUM(total),0),2) AS total
				FROM reportes_ventas INNER JOIN catalogosucursal cs ON 
				IF(tipoventa <> 'GUIA VENTANILLA',sucursalfacturo=cs.descripcion,sucursalrealizo=cs.id)
				WHERE activo = 'S'
				AND IF(tipoventa <> 'GUIA VENTANILLA',YEAR(fechafacturacion) = YEAR(CURRENT_DATE),YEAR(fecharealizacion) = YEAR(CURRENT_DATE)) 
				AND tipoventa = 'SOLICITUD DE FOLIOS'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND cs.id = ".$_SESSION[IDSUCURSAL]."":"")."
				GROUP BY tipopago";
			}	
			return $this->c->consultar($s);
		}
		
		public function getGeneralMesCredito(){
			$l=$this->c->getConexion();
			$x =rand(1,1000); 
			$s = "DROP TABLE IF EXISTS tmp_convenio$x";
			mysql_query($s,$l) or die($s);
			// tabla de convenios 
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
			
			$s = "SELECT ivaretenido FROM configuradorgeneral";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$ivaretenido = $f->ivaretenido;
			$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
			(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
			
			$s = "SELECT ROUND(SUM(IFNULL(total,'')),2) total,tipo
				FROM(	/* cargos */
				SELECT $if AS total,'Ventas Crédito' AS tipo 
				FROM guiasempresariales ge 
				INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
				INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
				WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
				AND ISNULL(ge.factura) ".(($_SESSION[IDSUCURSAL]!=1)? " AND tc.idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."
				UNION
				SELECT gv.total AS total,'Ventas Crédito' AS tipo
				FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
				WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND pg.sucursalacobrar = ".$_SESSION[IDSUCURSAL]."":"")."
				UNION
				SELECT (IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS total,'Ventas Crédito' AS tipo
				FROM facturacion f 
				WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.tipoguia='empresarial'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."
				UNION
				SELECT IFNULL(f.total,0) AS total,'Ventas Crédito' AS tipo 
				FROM facturacion f 
				WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='empresarial'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."
				UNION	/* abonos */
				SELECT fp.total AS total,'Abonos Credito' AS tipo
				FROM formapago fp 
				WHERE (fp.procedencia='A' OR fp.procedencia='C') ".(($_SESSION[IDSUCURSAL]!=1)? " AND fp.sucursal = ".$_SESSION[IDSUCURSAL]."":"").")t 
				GROUP BY tipo";
			$resul = $this->c->consultar($s);
			$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s);
			return $resul;
		}
		
		public function getGeneralCobranzaAnual(){
			$l=$this->c->getConexion();
			$x =rand(1,1000); 
			$s = "DROP TABLE IF EXISTS tmp_convenio$x";
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
			
			$s = "SELECT ivaretenido FROM configuradorgeneral";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$ivaretenido = $f->ivaretenido;
			$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
			(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
			
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
			END AS mes, ROUND(SUM(IFNULL(Cobrado,'')),2) Cobrado,ROUND(SUM(IFNULL(Cargado,'')),2) Cargado,fecha
			FROM(	/* cargos */
			SELECT UPPER(MONTHNAME(ge.fecha)) AS mes,$if AS Cargado,0 AS Cobrado,ge.fecha
			FROM guiasempresariales ge 
			INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
			INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
			WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
			AND ISNULL(ge.factura) AND ge.fecha>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND tc.idsucursal = ".$_SESSION[IDSUCURSAL]."":"")." 
			UNION
			SELECT UPPER(MONTHNAME(gv.fecha)) AS mes,gv.total AS Cargado,0 AS Cobrado,fecha
			FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
			WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO'
			AND gv.fecha>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND pg.sucursalacobrar = ".$_SESSION[IDSUCURSAL]."":"")." 
			UNION
			SELECT UPPER(MONTHNAME(f.fecha)) AS mes,(IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + 
			IFNULL(f.otrosmontofacturar,0)) AS Cargado,0 AS Cobrado,f.fecha
			FROM facturacion f 
			WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.tipoguia='empresarial'
			AND f.fecha>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal = ".$_SESSION[IDSUCURSAL]."":"")." 
			UNION
			SELECT UPPER(MONTHNAME(f.fecha)) AS mes,IFNULL(f.total,0) AS Cargado,0 AS Cobrado,f.fecha
			FROM facturacion f 
			WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='empresarial'
			AND f.fecha>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal = ".$_SESSION[IDSUCURSAL]."":"")." 
			UNION	/* abonos */
			SELECT UPPER(MONTHNAME(fp.fecha)) AS mes,0 AS Cargado,fp.total AS Cobrado,fp.fecha
			FROM formapago fp 
			WHERE (fp.procedencia='A' OR fp.procedencia='C') 
			AND fp.fecha>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND fp.sucursal = ".$_SESSION[IDSUCURSAL]."":"").")t GROUP BY mes ORDER BY MONTH(fecha);";
			$resul = $this->c->consultar($s);
			$s = "DROP TABLE tmp_convenio$x";
			mysql_query($s,$l) or die($s);
			return $resul;
		}
		
		public function getGeneralMesIngreso(){
			$s = "SELECT CASE procedencia
				WHEN 'G' THEN 'Guias'
				WHEN 'M' THEN 'Liquidacion EAD'
				WHEN 'F' THEN 'Facturacion'
				WHEN 'A' THEN 'Abono Cliente'
				WHEN 'C' THEN 'Liquidacion Cobranza'
				WHEN 'O' THEN 'Entrega Ocurre'
			END AS tipo,
			ROUND(SUM(total),2) AS total
			FROM formapago
			WHERE YEAR(fecha) = YEAR(CURRENT_DATE) AND ISNULL(fechacancelacion)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"")."
			GROUP BY procedencia";
			return $this->c->consultar($s);
		}
		
		public function getGeneralIngresoAnual(){
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
			ROUND(SUM(guias),2) AS guias, ROUND(SUM(ead),2) AS ead, 
			ROUND(SUM(facturacion),2) AS facturacion, ROUND(SUM(abono),2) AS abono,
			ROUND(SUM(cobranza),2) AS cobranza, ROUND(SUM(ocurre),2) AS ocurre
			FROM
			(SELECT UPPER(MONTHNAME(fecha)) AS mes, fecha,				
					SUM(CASE procedencia WHEN 'G' THEN total ELSE 0 END) AS guias,
					SUM(CASE procedencia WHEN 'M' THEN total ELSE 0 END) AS ead,
					SUM(CASE procedencia WHEN 'F' THEN total ELSE 0 END) AS facturacion,
					SUM(CASE procedencia WHEN 'A' THEN total ELSE 0 END) AS abono,
					SUM(CASE procedencia WHEN 'C' THEN total ELSE 0 END) AS cobranza,
					SUM(CASE procedencia WHEN 'O' THEN total ELSE 0 END) AS ocurre
			FROM formapago
			WHERE ISNULL(fechacancelacion) AND fecha>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"")."
			GROUP BY UPPER(MONTHNAME(fecha))) R1
			GROUP BY mes ORDER BY fecha;";
			return $this->c->consultar($s);
		}
	}
?>