<?
	session_start();
	include("../../coneccion/Coneccion.php");
			
	class GerenteSucursal{
		var $c;
		
		public function GerenteSucursal(){
			$this->c = new Coneccion();
		}
		
		public function getAlertas(){
			$s = "SELECT 
			(SELECT COUNT(*) FROM generacionconvenio 
			WHERE sucursal=".$_SESSION[IDSUCURSAL]." AND 
			DATEDIFF(vigencia,CURDATE()) <= (SELECT diasvencimientoconvenio FROM configuradorgeneral)) copove,
			(SELECT COUNT(t1.id) FROM (
				SELECT gv.id 
				FROM guiasventanilla AS gv
				INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
				WHERE gv.estado = 'CANCELADO' AND gvu.ubicacion = '$_SESSION[IDSUCURSAL]'
				GROUP BY gv.id
			) AS t1) guca,
			(SELECT COUNT(*)
			FROM facturacion AS f
			INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
			WHERE f.estadocobranza = 'N' AND f.idsucursal='$_SESSION[IDSUCURSAL]' AND 
				(CASE DAYOFWEEK(CURRENT_DATE)
					WHEN 2 THEN sc.lunesrevision=1
					WHEN 3 THEN sc.martesrevision=1
					WHEN 4 THEN sc.miercolesrevision=1
					WHEN 5 THEN sc.juevesrevision=1
					WHEN 6 THEN sc.viernesrevision=1
					WHEN 7 THEN sc.sabadorevision=1
				END OR sc.semanarevision = 1)) fare,
			(SELECT COUNT(*) 
			FROM facturacion f
			INNER JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE (DATEDIFF(CURDATE(),DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)))>30
			AND f.estado<>'CANCELADO' AND f.idsucursal=".$_SESSION[IDSUCURSAL].") coma30,
			(SELECT COUNT(*) 
			FROM facturacion f
			INNER JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE (DATEDIFF(CURDATE(),DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)))>60
			AND f.estado<>'CANCELADO' AND f.idsucursal=".$_SESSION[IDSUCURSAL].") coma60,
			
			(SELECT SUM(total)  FROM (
				 (SELECT COUNT(*) total FROM guiasventanilla WHERE estado <> 'ENTREGADA' AND
				 ADDDATE(fecha, INTERVAL (IF(entregaead=0 OR ISNULL(entregaead),entregaead,24)/24) DAY) < CURRENT_DATE
				 AND ocurre = 0 AND idsucursaldestino='$_SESSION[IDSUCURSAL]')
				 UNION
				 (SELECT COUNT(*) total FROM guiasempresariales WHERE estado <> 'ENTREGADA' AND
				 ADDDATE(fecha, INTERVAL (IF(entregaead=0 OR ISNULL(entregaead),entregaead,24)/24) DAY) < CURRENT_DATE
				 AND ocurre = 0 AND idsucursaldestino='$_SESSION[IDSUCURSAL]')
			) AS t1) enatead,
			(SELECT SUM(total)  FROM (
					 (SELECT COUNT(*) total FROM guiasventanilla WHERE estado <> 'ENTREGADA' AND
					 ADDDATE(fecha, INTERVAL (IF(entregaocurre=0 OR ISNULL(entregaocurre),entregaocurre,24)/24) DAY) < CURRENT_DATE
					 AND ocurre = 1 AND idsucursaldestino='$_SESSION[IDSUCURSAL]')
					 UNION
					 (SELECT COUNT(*) total FROM guiasempresariales WHERE estado <> 'ENTREGADA' AND
					 ADDDATE(fecha, INTERVAL (IF(entregaocurre=0 OR ISNULL(entregaocurre),entregaocurre,24)/24) DAY) < CURRENT_DATE
					 AND ocurre = 1 AND idsucursaldestino='$_SESSION[IDSUCURSAL]')
				) AS t1) enataocu,
			(SELECT COUNT(*) FROM recoleccion 
				 WHERE estado <> 'REPROGRAMADA' AND estado <> 'REALIZADO' AND estado <> 'CANCELADO'
				 AND fecharegistro < CURRENT_DATE AND sucursal = '$_SESSION[IDSUCURSAL]') reat,
			(SELECT SUM(total) FROM (
				(SELECT COUNT(*) total FROM guiasventanilla WHERE estado = 'ALMACEN ORIGEN' AND idsucursalorigen = '$_SESSION[IDSUCURSAL]' )
				UNION
				(SELECT COUNT(*) total FROM guiasempresariales WHERE estado = 'ALMACEN ORIGEN' AND idsucursalorigen = '$_SESSION[IDSUCURSAL]' )
			) AS t1) gunoem,
			(SELECT COUNT(id) FROM reportedanosfaltante
				WHERE faltante = 1 AND sucursal = ".$_SESSION[IDSUCURSAL].") guvefa,
			(SELECT COUNT(id) FROM reportedanosfaltante
				WHERE dano = 1 AND sucursal = ".$_SESSION[IDSUCURSAL].") guveda,
			(SELECT COUNT(*) FROM sobrantes) guveso,
			(SELECT COUNT(*) FROM guiasventanilla AS gv 
				INNER JOIN pagoguias AS pg ON gv.id = pg.guia
				WHERE (ISNULL(gv.factura) OR gv.factura='') 
				AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]') gupefa";
			return $this->c->consultar($s);
		}	
		
		public function getVentas(){
			$s = "SELECT 
			FORMAT(SUM(IF(tipoventa = 'GUIA VENTANILLA', IFNULL(total,0), 0)),2) AS gv,
			FORMAT(SUM(IF(tipoventa = 'GUIA EMPRESARIAL', IFNULL(total,0), 0)),2) AS ge,
			FORMAT(SUM(IFNULL(total,0)),2) AS total
			FROM reportes_ventas
			WHERE activo = 'S'
			AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE) and idsucorigen = $_SESSION[IDSUCURSAL] 
			AND (tipoventa = 'GUIA VENTANILLA' OR tipoventa = 'GUIA EMPRESARIAL')";
			return $this->c->consultar($s);
		}
		
		public function getCobranza(){
			$s = "SELECT format(SUM(cargo),2) AS cargo, format(SUM(abono),2) AS abono, format(SUM(cargo)-SUM(abono),2) AS saldo
			FROM reporte_cobranza4
			WHERE idsucursal = $_SESSION[IDSUCURSAL]";
			return $this->c->consultar($s);
		}
		
		public function getOperaciones(){
			$s = "SELECT 
			(SELECT COUNT(*) FROM entregasocurre WHERE sucursal = $_SESSION[IDSUCURSAL] AND fecha  = CURRENT_DATE) AS enoc,
			(SELECT COUNT(*) FROM recepcionmercancia WHERE idsucursal = $_SESSION[IDSUCURSAL] AND fecha  = CURRENT_DATE) AS reme,
			(SELECT COUNT(*) FROM recoleccion WHERE sucursal = $_SESSION[IDSUCURSAL] AND fecha  = CURRENT_DATE) AS recme,
			(SELECT COUNT(*) FROM embarquedemercancia WHERE idsucursal = $_SESSION[IDSUCURSAL] AND fecha = CURRENT_DATE) emme,
			(SELECT CONCAT(COUNT(id),'-',FORMAT(SUM(total),2)) FROM (
				(SELECT gv.id, gv.total
				FROM guiasventanilla gv 
				INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
				WHERE gvu.ubicacion = $_SESSION[IDSUCURSAL] AND gv.estado!='ENTREGADA' AND gv.estado!='CANCELADA' AND gv.estado!='CANCELADO'
				AND SUBSTRING(gv.id,1,3) <> '888'
				GROUP BY gv.id)
				UNION
				(SELECT ge.id, ge.total
				FROM guiasempresariales ge 
				INNER JOIN guiasempresariales_unidades geu ON ge.id = geu.idguia
				WHERE geu.ubicacion = $_SESSION[IDSUCURSAL] AND ge.estado<>'ENTREGADA' AND ge.estado<>'CANCELADA' AND ge.estado<>'CANCELADO'
				GROUP BY ge.id)
			) AS t1) gual,
			(SELECT COUNT(id) FROM catalogounidad 
			WHERE sucursal = $_SESSION[IDSUCURSAL] AND fueradeservicio=0) unense,
			(SELECT COUNT(id) FROM catalogounidad 
			WHERE sucursal = $_SESSION[IDSUCURSAL] AND fueradeservicio<>0) unfudeop";
			return $this->c->consultar($s);
		}
		
		public function getProgramaTrabajo(){
			$s = "SELECT
			(SELECT COUNT(*) FROM facturacion WHERE facturaestado = 'GUARDADO' AND fecha = CURRENT_DATE AND idsucursal = '$_SESSION[IDSUCURSAL]') AS fahe,
			(SELECT COUNT(*) FROM liquidacioncobranza WHERE estado = 'LIQUIDADO' AND fechaliquidacion = CURRENT_DATE) AS lico,
			(SELECT COUNT(*) FROM solicitudcredito WHERE estado = 'EN AUTORIZACION') AS socrpeau,
			(SELECT COUNT(*) FROM solicitudcredito WHERE estado = 'AUTORIZADO') AS socrpeac,
			(SELECT SUM(total)  FROM (
				(SELECT COUNT(*) total FROM guiasventanilla WHERE estado = 'ENTREGADA POR LIQUIDAR' AND idsucursaldestino = '$_SESSION[IDSUCURSAL]' )
				UNION
				(SELECT COUNT(*) total FROM guiasempresariales WHERE estado = 'ENTREGADA POR LIQUIDAR' AND idsucursaldestino = '$_SESSION[IDSUCURSAL]' )
			) AS t1) gupoli";
			return $this->c->consultar($s);
		}
	}
?>