<?
	include("../../coneccion/Coneccion.php");
	class Presupuesto{
		var $c;
		var $host = "localhost";
		var $user = "pmm";
		var $pass = "gqx64p9n";
		var $base = "pmm_curso";
		
		public function Presupuesto(){
			$this->c = new Coneccion();
		}
		
		public function getDetallado(){
			$inicio = 2010;
			$fin 	= date("Y");
			
			for($i=2010; $i<=$fin; $i++){
				$anos[] = $i;
			}
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			
			$fechas = array();
			$fechas[anos] = $anos;
			$fechas[meses] = $meses;
			
			return $fechas;
		}
		
		public function getDetalladoAbajo($mes,$ano){
			$l=$this->c->getConexion();
			$x =rand(1,1000); 
			
			$s = "DROP TABLE IF EXISTS detallado$x";
			mysql_query($s,$l) or die($s);
			$s = "DROP TABLE IF EXISTS tmp_convenio$x";
			mysql_query($s,$l) or die($s);
			$s = "DROP TABLE IF EXISTS saldos$x";
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
			
			// tabla de saldos 
			$s = "CREATE TABLE `saldos$x` (
			`cargo` DOUBLE DEFAULT 0,
			`abono` DOUBLE DEFAULT 0,
			`sucursal` DOUBLE DEFAULT 0,
			KEY  `sucursal` (`sucursal`)
			) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT ivaretenido FROM configuradorgeneral";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$ivaretenido = $f->ivaretenido;
			$if = " IF(ge.tipoguia='PREPAGADA',((ge.texcedente+ge.tseguro)*((cs.iva/100)+1))-
			(IF(ge.ivaretenido>0,(ge.texcedente+ge.tseguro)*($ivaretenido/100),0)),ge.total)";
			
			$s = "INSERT INTO saldos$x
				SELECT ROUND(SUM(IFNULL(cargo,0)),2) cargo,ROUND(SUM(IFNULL(abono,0)),2) abono,sucursal
				FROM(	
				SELECT SUM($if) AS cargo,0 AS abono,cs.id AS sucursal
				FROM guiasempresariales ge 
				INNER JOIN tmp_convenio$x tc ON ge.idremitente = tc.idcliente
				INNER JOIN catalogosucursal cs ON tc.idsucursal=cs.id
				WHERE ge.tipopago='CREDITO' AND (ge.tipoguia='CONSIGNACION' OR (ge.tipoguia='PREPAGADA' AND (ge.texcedente>0 OR ge.tseguro>0))) 
				AND ISNULL(ge.factura) AND YEAR(ge.fecha)='$ano' AND MONTH(ge.fecha)='$mes' GROUP BY tc.idsucursal
				UNION
				SELECT gv.total AS cargo,0 AS abono,pg.sucursalacobrar AS sucursal
				FROM guiasventanilla gv INNER JOIN pagoguias pg ON gv.id = pg.guia
				WHERE gv.condicionpago=1 AND ISNULL(gv.factura) AND gv.estado!='CANCELADO' AND YEAR(gv.fecha)='$ano' AND MONTH(gv.fecha)='$mes'
				GROUP BY pg.sucursalacobrar
				UNION
				SELECT (IFNULL(f.total,0) + IFNULL(f.sobmontoafacturar,0) + IFNULL(f.otrosmontofacturar,0)) AS cargo,0 AS abono,f.idsucursal
				FROM facturacion f 
				WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.tipoguia='empresarial' AND YEAR(f.fecha)='$ano' AND MONTH(f.fecha)='$mes'
				GROUP BY f.idsucursal
				UNION
				SELECT IFNULL(f.total,0) AS cargo,0 AS abono,f.idsucursal
				FROM facturacion f 
				WHERE f.credito='SI' AND ISNULL(f.fechacancelacion) AND f.tipoguia!='empresarial' AND YEAR(f.fecha)='$ano' AND MONTH(f.fecha)='$mes'
				GROUP BY f.idsucursal 
				UNION	
				SELECT 0 AS cargo,fp.total AS abono,fp.sucursal
				FROM formapago fp 
				WHERE (fp.procedencia='A' OR fp.procedencia='C') AND YEAR(fp.fecha)='$ano' AND MONTH(fp.fecha)='$mes' GROUP BY fp.sucursal)t 
				GROUP BY sucursal;";
			mysql_query($s,$l) or die($s); 
									
			// tabla de detallado 
			$s = "CREATE TABLE `detallado$x` (
				`id` DOUBLE NOT NULL AUTO_INCREMENT,
				`suc` DOUBLE DEFAULT 0,
				`nguias` DOUBLE DEFAULT 0,
				`tguias` DOUBLE DEFAULT 0,
				`nfacturas` DOUBLE DEFAULT 0,
				`tfacturas` DOUBLE DEFAULT 0,
				`ead` DOUBLE DEFAULT 0,
				`recolec` DOUBLE DEFAULT 0,
				`liquidado` DOUBLE DEFAULT 0,
				`catrecolec` DOUBLE DEFAULT 0,
				`catead` DOUBLE DEFAULT 0,
				`ingresos` DOUBLE DEFAULT 0,
				`cobranza` DOUBLE DEFAULT 0,
				PRIMARY KEY  (`id`),
				KEY  `suc` (`suc`)
			) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
			mysql_query($s,$l) or die($s);
			
			//guardar datos en la temporal
			$s = "INSERT INTO detallado$x
			SELECT NULL,id,SUM(nguias),ROUND(SUM(tguias),2),SUM(nfacturas),ROUND(SUM(tfacturas),2),SUM(ead),SUM(liquidado),SUM(recolec),SUM(catrecolec),
			SUM(catead),NULL,NULL
			FROM(
			SELECT idsucorigen id,COUNT(id) nguias,SUM(total) tguias,0 AS nfacturas,0 AS tfacturas,0 AS ead,0 AS liquidado,0 AS recolec,0 AS catrecolec,0 AS catead
			FROM reportes_ventas 
			WHERE YEAR(fecharealizacion)='$ano' AND MONTH(fecharealizacion)='$mes' AND (tipoventa='GUIA EMPRESARIAL' OR tipoventa='GUIA VENTANILLA') 
			AND (factura=0 OR ISNULL(factura)) AND activo='S' GROUP BY idsucorigen
			UNION
			SELECT idsucorigen id,0 AS nguias,0 AS tguias,COUNT(id) nfacturas,SUM(totalfactura) tfacturas,0 AS ead,0 AS liquidado,0 AS recolec,0 AS catrecolec,
			0 AS catead
			FROM reportes_ventas 
			WHERE YEAR(fechafacturacion)='$ano' AND MONTH(fechafacturacion)='$mes' AND (tipoventa='GUIA EMPRESARIAL' OR tipoventa='GUIA VENTANILLA') 
			AND (factura!=0 OR NOT ISNULL(factura)) AND activo='S' GROUP BY idsucorigen 
			UNION
			SELECT sucursal id,0 AS nguias,0 AS tguias,0 AS nfacturas,0 AS tfacturas,COUNT(id)ead,SUM(IF(rep.liquidado=1,1,0)) liquidado,0 AS recolec,
			0 AS catrecolec,0 AS catead
			FROM repartomercanciaead rep
			WHERE YEAR(fecha)='$ano' AND MONTH(fecha)='$mes' GROUP BY sucursal
			UNION
			SELECT sucursal id,0 AS nguias,0 AS tguias,0 AS nfacturas,0 AS tfacturas,0 AS ead,0 AS liquidado,COUNT(folio)recolec,0 AS catrecolec,0 AS catead
			FROM recoleccion 
			WHERE YEAR(fecharegistro)='$ano' AND MONTH(fecharegistro)='$mes' AND estado!='CANCELADO' GROUP BY sucursal
			UNION
			SELECT sucursal id,0 AS nguias,0 AS tguias,0 AS nfacturas,0 AS tfacturas,0 AS ead,
			0 AS liquidado,0 AS recolec,SUM(IF(queja='RECOLECCION',1,0)) catrecolec,
			SUM(IF(queja='EAD MAL EFECTUADAS',1,0)) catead
			FROM solicitudtelefonica 
			WHERE YEAR(fechaqueja)='$ano' AND MONTH(fechaqueja)='$mes'
			GROUP BY sucursal
			)t GROUP BY id;";
			mysql_query($s,$l) or die($s); 
			
			//agregar datos pendientes
			$s = "UPDATE detallado$x det INNER JOIN saldos$x sal ON det.suc=sal.sucursal 
			SET det.ingresos=sal.abono,det.cobranza=sal.cargo;";
			mysql_query($s,$l) or die($s); 
			
			$s = "SELECT 
			suc AS sucursal,
			nguias AS ve_guias,
			tguias AS ve_cant_guias,
			nfacturas AS ve_facturacion,
			tfacturas AS ve_cant_facturacion,
			ead AS op_repartos,
			recolec AS op_recolecciones,
			liquidado AS op_ead,
			catrecolec AS ca_recolecion,
			catead AS ca_ead,
			ingresos AS cc_ingreso,
			cobranza AS cc_cobranza
			FROM detallado$x";
			$resul = $this->c->consultar($s);
			
			$s = "DROP TABLE IF EXISTS detallado$x";
			mysql_query($s,$l) or die($s);
			$s = "DROP TABLE IF EXISTS tmp_convenio$x";
			mysql_query($s,$l) or die($s);
			$s = "DROP TABLE IF EXISTS saldos$x";
			mysql_query($s,$l) or die($s);
			
			return $resul;
		}
		
		public function getGrafica($ano, $sucursal=null){
			$l = mysql_connect($this->host, $this->user, $this->pass);
			mysql_select_db($this->base);
			
			if($sucursal!=null){
				$s = "select id from catalogosucursal where prefijo = '$sucursal'";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				$and = " AND idsucorigen = '$sucursal'";
				$and2 = " AND sucursal = '$f->id'";
			}
			$s = "SELECT CASE UPPER(MONTHNAME(fecharealizacion)) 
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
			END AS mes, MONTH(fecharealizacion) AS ordenar,
			SUM(total) total
			FROM reportes_ventas
			WHERE YEAR(fecharealizacion)=$ano $and AND activo='S'
			GROUP BY UPPER(MONTHNAME(fecharealizacion))
			ORDER BY MONTH(fecharealizacion)";
			$r=mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$s = "select sum(".strtolower($f->mes).") mes FROM catalogopresupuesto WHERE YEAR(fechapresupuesto)=$ano $and2";
				$rx=mysql_query($s,$l) or die($s);
				$fx = mysql_fetch_object($rx);
				
				$arre[] = array('mes'=>$f->mes,'ventas'=>$f->total,'presupuesto'=>$fx->mes);
			}
			return $arre;
		}
		
		public function detalleArriba($sucursal=null){
			$l = mysql_connect($this->host, $this->user, $this->pass);
			mysql_select_db($this->base);
			
			if($sucursal!=null){
				$s = "select id from catalogosucursal where prefijo = '$sucursal'";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				$and = " AND sucursal = '$f->id'";
				$and2 = " AND idsucursal = '$f->id'";
				$and3 = " AND idsucursalorigen = '$f->id'";
				$and4 = " AND idsucursaldestino = '$f->id'";
				$and5a = " AND gv.ubicacion = '$f->id'";
				$and5b = " AND ge.ubicacion = '$f->id'";
			}
			
			$s = "SELECT
			(SELECT COUNT(*) FROM generacionconvenio WHERE estado = 'IMPRESO' $and) AS conveniosencierre,
			(SELECT COUNT(*) FROM generacionconvenio WHERE estado = 'AUTORIZADO' $and) AS conveniosnuevos,
			(SELECT COUNT(*) FROM evaluacionmercancia WHERE guiaempresarial<>'' AND estado = 'GUARDADO' $and) AS guiasempresarialespaplicar,
			(SELECT COUNT(*) FROM facturacion WHERE fecha=CURRENT_DATE $and2) AS facturasrealizadas,
			(SELECT SUM(encon) FROM (
				SELECT COUNT(*) encon
				FROM guiasventanilla WHERE estado = 'EN TRANSITO' $and4
				UNION
				SELECT COUNT(*) encon
				FROM guiasempresariales WHERE estado = 'EN TRANSITO' $and4
			) AS t1) AS guiasporrecibir,
			(SELECT SUM(encon) FROM (
				SELECT COUNT(*) encon
				FROM guiasventanilla WHERE estado = 'ALMACEN ORIGEN' $and3
				UNION
				SELECT COUNT(*) encon
				FROM guiasempresariales WHERE estado = 'ALMACEN ORIGEN' $and3
			) AS t1) AS guiasporembarcar,
			(SELECT SUM(encon) FROM (
				SELECT COUNT(DISTINCT(gv.id)) encon FROM guiasventanilla gv
				INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
				WHERE gv.estado = 'ALMACEN TRASBORDO' $and5a
				UNION
				SELECT COUNT(DISTINCT(ge.id)) encon FROM guiasempresariales ge
				INNER JOIN guiaventanilla_unidades geu ON ge.id = geu.idguia
				WHERE ge.estado = 'ALMACEN TRASBORDO' $and5b
			) AS t1 ) AS guiasportrasbordar,
			(SELECT SUM(encon) FROM (
			SELECT COUNT(*) encon FROM guiasventanilla WHERE 
						  ADDDATE(fecha, INTERVAL FLOOR(IF(ocurre=1,entregaocurre,entregaead)/24) DAY)<CURRENT_DATE
						  $and4
			UNION
			SELECT COUNT(*) encon FROM guiasempresariales WHERE 
						  ADDDATE(fecha, INTERVAL FLOOR(IF(ocurre=1,entregaocurre,entregaead)/24) DAY)<CURRENT_DATE
						  $and4
			) AS t1 ) entregasatrasadas,
			(SELECT SUM(encon) FROM (
			SELECT COUNT(*) encon FROM guiasventanilla 
				WHERE estado = 'ALMACEN DESTINO' AND ocurre = 0
				$and4
			UNION
			SELECT COUNT(*) encon FROM guiasempresariales 
				WHERE estado = 'ALMACEN DESTINO' AND ocurre = 0
				$and4
			) AS t1) AS ead,
			(SELECT SUM(encon) FROM (
			SELECT COUNT(*) encon FROM guiasventanilla 
				WHERE estado = 'ALMACEN DESTINO' AND ocurre = 1
				$and4
			UNION
			SELECT COUNT(*) encon FROM guiasempresariales 
				WHERE estado = 'ALMACEN DESTINO' AND ocurre = 1
				$and4
			) AS t1) AS ocurre,
			(SELECT COUNT(*) FROM liquidacioncobranza WHERE fecha = CURRENT_DATE $and) AS liquidacioncobranza,
			(SELECT COUNT(*) FROM abonodecliente WHERE fecha = CURRENT_DATE $and2) AS abonocliente,
			(SELECT COUNT(*) FROM solicitudtelefonica WHERE estado = 'POR SOLUCIONAR' AND queja = 'RECOLECCION' $and) AS recoleccion,
			(SELECT COUNT(*) FROM solicitudtelefonica WHERE estado = 'POR SOLUCIONAR' AND queja = 'EAD MAL EFECTUADAS' $and) AS eadmalefectuadas,
			(SELECT COUNT(*) FROM solicitudtelefonica WHERE estado = 'POR SOLUCIONAR' AND queja = 'CONVENIOS NO APLICADOS' $and) AS conveniosnoaplicados,
			(SELECT COUNT(*) FROM solicitudtelefonica WHERE estado = 'POR SOLUCIONAR' AND queja = 'OTROS SERVICIOS' $and) AS otrosservicios,
			(SELECT COUNT(*) FROM solicitudtelefonica WHERE estado = 'POR SOLUCIONAR' AND queja = 'QUEJAS DAÑOS Y FALTANTES' $and) AS quejasdanos";
			return $this->c->consultar($s);
		}
		
		public function ventas_presupuesto($mes=null,$ano=null,$sucursal=null){
			
			$meses = array('','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
			
			$l = mysql_connect($this->host, $this->user, $this->pass);
			mysql_select_db($this->base);
			
			if($sucursal!=null){
				$s = "select id from catalogosucursal where prefijo = '$sucursal'";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				$and = " AND cp.sucursal = '$f->id'";
				$and2 = "AND rv.idsucorigen = '$f->id'";
			}
			
			$s = "SELECT FORMAT(SUM(rv.total),2) totalventa,
			FORMAT(SUM(cp.".$meses[$mes].")/COUNT(DISTINCT(rv.id)),2) presupuesto,
			FORMAT(SUM(rv.total)*(100/(SUM(cp.".$meses[$mes].")/COUNT(DISTINCT(rv.id)))),2) AS porcentaje
			FROM reportes_ventas rv
			LEFT JOIN catalogopresupuesto cp ON YEAR(cp.fechapresupuesto)=$ano $and
			WHERE YEAR(rv.fecharealizacion)='$ano' AND MONTH(rv.fecharealizacion)='$mes' $and2 AND rv.activo='S'";
			return $this->c->consultar($s);
		}
	}
?>