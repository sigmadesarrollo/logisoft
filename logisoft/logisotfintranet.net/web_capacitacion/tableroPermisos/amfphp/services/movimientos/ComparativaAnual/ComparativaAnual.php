<?
	session_start();
	include("../../coneccion/Coneccion.php");
			
	class ComparativaAnual{
		var $c;
		
		public function ComparativaAnual(){
			$this->c = new Coneccion();
		}
		
		public function getAnos(){
			$s = "SELECT YEAR(fecharealizacion) AS label, YEAR(fecharealizacion) AS data 
			FROM reportes_ventas
			GROUP BY YEAR(fecharealizacion);";
			return $this->c->consultar($s);
		}
		
		public function getComparativa($ano1,$ano2,$mes){
			
			$l=$this->c->getConexion();
			$x =rand(1,1000); 
			
			$meses = array('','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
			
			$s = "DROP TABLE IF EXISTS ano$x";
			mysql_query($s,$l) or die($s);
			
			$s = "CREATE TABLE `ano$x` (
				`id` DOUBLE NOT NULL AUTO_INCREMENT,
				`idsuc` DOUBLE DEFAULT 0,
				`suc` VARCHAR(10) COLLATE utf8_unicode_ci DEFAULT NULL,
				`zona` VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT NULL,
				`venta` DOUBLE DEFAULT 0,
				`presupuesto` DOUBLE DEFAULT 0,
				`ventaano` DOUBLE DEFAULT 0,
				`ventaano2` DOUBLE DEFAULT 0,
				`guias` DOUBLE DEFAULT 0,
				`guias2` DOUBLE DEFAULT 0,
				`impguias` DOUBLE DEFAULT 0,
				`impguias2` DOUBLE DEFAULT 0,
				`fact` DOUBLE DEFAULT 0,
				`fact2` DOUBLE DEFAULT 0,
				`recibido1` DOUBLE DEFAULT 0,
				`recibido2` DOUBLE DEFAULT 0,
				`ano` DOUBLE DEFAULT 0,
				PRIMARY KEY  (`id`),
				KEY  `suc` (`suc`)
				) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO ano$x
				SELECT NULL,cs.id,cs.prefijo,cs.estado,SUM(rv.total),NULL,
				NULL,NULL,COUNT(DISTINCT(rv.folio)),NULL,SUM(IF(ISNULL(rv.factura),rv.total,0)),NULL,
				COUNT(IF(NOT ISNULL(rv.factura),1,0)),NULL,NULL,NULL,YEAR(rv.fecharealizacion)
				FROM reportes_ventas rv 
				INNER JOIN catalogosucursal cs ON rv.idsucorigen=cs.id
				WHERE YEAR(rv.fecharealizacion)=$ano1 AND MONTH(rv.fecharealizacion)=$mes 
				AND rv.activo='S' GROUP BY rv.idsucorigen
				UNION 
				SELECT NULL,cs.id,cs.prefijo,cs.estado,NULL,NULL,NULL,NULL,NULL,
				COUNT(DISTINCT(rv.folio)),NULL,SUM(IF(ISNULL(rv.factura),rv.total,0)),NULL,
				COUNT(IF(NOT ISNULL(rv.factura),1,0)),NULL,NULL,YEAR(rv.fecharealizacion)
				FROM reportes_ventas rv 
				INNER JOIN catalogosucursal cs ON rv.idsucorigen=cs.id
				WHERE YEAR(rv.fecharealizacion)=$ano2 AND MONTH(rv.fecharealizacion)=$mes 
				AND rv.activo='S' GROUP BY rv.idsucorigen;";
			mysql_query($s,$l) or die($s);
			
			$s = "CREATE TEMPORARY TABLE `presup`
				SELECT ".$meses[$mes]." AS total, sucursal FROM catalogopresupuesto
				WHERE YEAR(fechapresupuesto)= $ano1 AND MONTH(fechapresupuesto)=$mes GROUP BY sucursal;";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE ano$x a INNER JOIN presup p ON a.idsuc=p.sucursal
				SET presupuesto=total;";
			mysql_query($s,$l) or die($s);
			
			$s = "CREATE TEMPORARY TABLE `rmerc`
				SELECT COUNT(rmd.guia) AS total, rmd.sucursal FROM recepcionmercancia rm 
				INNER JOIN recepcionmercanciadetalle rmd ON rm.folio=rmd.recepcion
				WHERE YEAR(rm.fecha)=$ano1 AND MONTH(rm.fecha)=$mes GROUP BY rmd.sucursal;";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE ano$x a INNER JOIN rmerc rm ON a.idsuc=rm.sucursal
				SET recibido1=total;";
			mysql_query($s,$l) or die($s);
			
			$s = "CREATE TEMPORARY TABLE `rmerc2`
				SELECT COUNT(rmd.guia) AS total, rmd.sucursal FROM recepcionmercancia rm 
				INNER JOIN recepcionmercanciadetalle rmd ON rm.folio=rmd.recepcion
				WHERE YEAR(rm.fecha)=$ano2 AND MONTH(rm.fecha)=$mes GROUP BY rmd.sucursal;";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE ano$x a INNER JOIN rmerc2 rm2 ON a.idsuc=rm2.sucursal
				SET recibido2=total;";
			mysql_query($s,$l) or die($s);
			
			$s = "CREATE TEMPORARY TABLE `vanual`
				SELECT SUM(total) total, idsucorigen FROM reportes_ventas
				WHERE YEAR(fecharealizacion)= $ano1 AND activo='S' GROUP BY idsucorigen;";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE ano$x a INNER JOIN vanual v ON a.idsuc=v.idsucorigen
				SET ventaano=total;";
			mysql_query($s,$l) or die($s);
			
			$s = "CREATE TEMPORARY TABLE `vanual2`
				SELECT SUM(total) total, idsucorigen FROM reportes_ventas
				WHERE YEAR(fecharealizacion)= $ano2 AND activo='S' GROUP BY idsucorigen;";
			mysql_query($s,$l) or die($s);

			$s = "UPDATE ano$x a INNER JOIN vanual2 v2 ON a.idsuc=v2.idsucorigen
				SET ventaano2=total;";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT suc AS sucursal,zona,'A' AS clasificacion,ROUND(SUM(venta),2) ventamensual,ROUND(presupuesto,2) presupuesto,
			ROUND(SUM(venta)-presupuesto,2) variacion_vm_m,ROUND(((SUM(venta)-presupuesto)*100)/SUM(venta),2) variacion_vm_p,
			ROUND(SUM(ventaano),2) venta1,ROUND(SUM(ventaano2),2) venta2,ROUND(SUM(ventaano)-SUM(ventaano2),2) variacion_va_m,
			ROUND(((SUM(ventaano)-SUM(ventaano2))*100)/SUM(ventaano),2) variacion_va_p,SUM(guias) guias1,SUM(guias2) guias2,
			(SUM(guias)-SUM(guias2)) variacion_ga_m,((SUM(guias)-SUM(guias2))*100)/SUM(guias) variacion_ga_p,
			ROUND(SUM(impguias),2) importeguias1,ROUND(SUM(impguias2),2) importeguias2,SUM(fact) facturas1,SUM(fact2) facturas2,
			recibido1,recibido2,(recibido1-recibido2) variacion_ra_m,
			(((recibido1-recibido2)*100)/recibido1) variacion_ra_p
			FROM ano$x GROUP BY suc;";
			$resul = $this->c->consultar($s);
			
			$s = "DROP TABLE IF EXISTS ano$x";
			mysql_query($s,$l) or die($s);
			
			return $resul;
		}	
	}
?>