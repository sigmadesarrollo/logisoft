<?
	session_start();
	include("../../coneccion/Coneccion.php");
	
	class OperacionesServicios{
		var $c;
		
		public function OperacionesServicios(){
			$this->c = new Coneccion();
		}
		
		public function getAlertas(){
			$s = "SELECT
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
 (SELECT COUNT(id) FROM (
				(SELECT gv.id
				FROM guiasventanilla gv 
				INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
				WHERE gvu.ubicacion = '$_SESSION[IDSUCURSAL]' AND gv.estado!='ENTREGADA' AND gv.estado!='CANCELADA' AND gv.estado!='CANCELADO'
				AND SUBSTRING(gv.id,1,3) <> '888'
				GROUP BY gv.id)
				UNION
				(SELECT ge.id
				FROM guiasempresariales ge 
				INNER JOIN guiasempresariales_unidades geu ON ge.id = geu.idguia
				WHERE geu.ubicacion = '$_SESSION[IDSUCURSAL]' AND ge.estado<>'ENTREGADA' AND ge.estado<>'CANCELADA' AND ge.estado<>'CANCELADO'
				GROUP BY ge.id)
			) AS t1) gual,
(SELECT COUNT(id) FROM reportedanosfaltante
				WHERE faltante = 1 AND sucursal = ".$_SESSION[IDSUCURSAL].") guvefa,
			(SELECT COUNT(id) FROM reportedanosfaltante
				WHERE dano = 1 AND sucursal = ".$_SESSION[IDSUCURSAL].") guveda,
			(SELECT COUNT(*) FROM sobrantes) guveso";
			
			return $this->c->consultar($s);
		}	
		
		function getProgramaTrabajo(){
			$s = "SELECT
			(SELECT SUM(total) FROM (
				(SELECT COUNT(gv.id) total 
				  FROM guiasventanilla gv  					  
				  WHERE gv.estado<>'ALMACEN DESTINO' AND gv.estado<>'CANCELADO' AND 
				  gv.estado<>'AUTORIZACION PARA CANCELAR' AND gv.estado<>'ENTREGADA'
				  AND gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]')
				UNION
				(SELECT COUNT(ge.id) total 
				  FROM guiasempresariales ge
				  WHERE ge.estado<>'ALMACEN DESTINO' AND ge.estado<>'ENTREGADA'
				  AND ge.idsucursaldestino = '$_SESSION[IDSUCURSAL]')
			) AS t1) gupore,
			(SELECT SUM(total) FROM (
								(SELECT COUNT(gv.id) total 
								  FROM guiasventanilla gv  					  
								  WHERE gv.estado = 'ALMACEN ORIGEN'
								  AND gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]')
								UNION
								(SELECT COUNT(ge.id) total 
								  FROM guiasempresariales ge
								  WHERE ge.estado = 'ALMACEN ORIGEN'
								  AND ge.idsucursaldestino = '$_SESSION[IDSUCURSAL]')
							) AS t1) mereem,
			(SELECT COUNT(*) FROM (
									(SELECT guiasventanilla.id total 
									FROM guiasventanilla 
									INNER JOIN guiaventanilla_unidades ON guiasventanilla.id = guiaventanilla_unidades.idguia
									WHERE guiasventanilla.estado = 'ALMACEN TRASBORDO' AND guiaventanilla_unidades.ubicacion = '$_SESSION[IDSUCURSAL]'
									GROUP BY guiasventanilla.id)
									UNION
									(SELECT guiasempresariales.id total 
									FROM guiasempresariales 
									INNER JOIN guiasempresariales_unidades ON guiasempresariales.id = guiasempresariales_unidades.idguia
									WHERE guiasempresariales.estado = 'ALMACEN TRASBORDO' AND guiasempresariales_unidades.ubicacion = '$_SESSION[IDSUCURSAL]'
									GROUP BY guiasempresariales.id)
								) AS t1) gupotr,
			(SELECT SUM(total) FROM (
								(SELECT COUNT(*) total FROM guiasventanilla WHERE estado = 'POR ENTREGAR' AND idsucursaldestino = '$_SESSION[IDSUCURSAL]' )
								UNION
								(SELECT COUNT(*) total FROM guiasempresariales WHERE estado = 'POR ENTREGAR' AND idsucursaldestino = '$_SESSION[IDSUCURSAL]' )
							) AS t1) gupoenoc,
			(SELECT COUNT(*) FROM recoleccion		
							WHERE fecharegistro = CURDATE() AND 
							sucursal = '$_SESSION[IDSUCURSAL]' AND
							realizo = 'NO' AND estado<>'CANCELADO') resire,
					(SELECT COUNT(id) FROM (
						SELECT id FROM guiasventanilla gv
						WHERE estado='ALMACEN DESTINO' AND ocurre=0 AND idsucursaldestino='".$_SESSION[IDSUCURSAL]."'
						UNION
						SELECT id FROM guiasempresariales gm
						WHERE estado='ALMACEN DESTINO' AND ocurre=0 AND idsucursaldestino='".$_SESSION[IDSUCURSAL]."'
					) t1) AS gupoenead";
					
			return $this->c->consultar($s);
		}
		
		function getIndicadores(){
			$s = "SELECT 
			(SELECT COUNT(*) FROM entregasocurre WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND fecha  = CURRENT_DATE) AS enoc,
			(SELECT COUNT(*) FROM recepcionmercancia WHERE idsucursal = '$_SESSION[IDSUCURSAL]' AND fecha  = CURRENT_DATE) AS reme,
			(SELECT COUNT(*) FROM recoleccion WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND fecha  = CURRENT_DATE) AS recme,
			(SELECT COUNT(*) FROM embarquedemercancia WHERE idsucursal = '$_SESSION[IDSUCURSAL]' AND fecha = CURRENT_DATE) emme,
			(SELECT COUNT(*) FROM repartomercanciaead WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND fecha = CURRENT_DATE) reead";
			
			return $this->c->consultar($s);
		}
	}
?>