<?
	session_start();
	include("../../coneccion/Coneccion.php");	
	class Cobranza{
		var $c;
		
		public function Cobranza(){
			$this->c = new Coneccion();
		}
		
		public function getGenerarAlertas(){
			$s = "SELECT ";
			$s .= "(SELECT COUNT(*) FROM facturacion WHERE estado='CANCELADO' 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."" : "" ).") AS factcan";
			
			$s .= ($s != "SELECT ")?",":"";
			$s .= "(SELECT COUNT(*) FROM guiasventanilla AS gv 
			INNER JOIN pagoguias AS pg ON gv.id = pg.guia
			WHERE (ISNULL(gv.factura) OR gv.factura='')
			".(($_SESSION[IDSUCURSAL]!=1)? " AND pg.sucursalacobrar = ".$_SESSION[IDSUCURSAL]."" : "" ).") AS guiasfacturar";
			
			$s .= ($s != "SELECT ")?",":"";
			$s .= "(SELECT COUNT(t1.id) FROM(
			SELECT gv.id FROM guiasventanilla AS gv
			INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
			WHERE gv.estado = 'CANCELADO'
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gvu.ubicacion = ".$_SESSION[IDSUCURSAL]."" : "" )."				
			GROUP BY gv.id ) AS t1) AS guiascanceladas";
			
			$l = mysql_connect("localhost","pmm","gqx64p9n");
			mysql_select_db("pmm_dbpruebas",$l);
			
			$ss = "SELECT porcentajelimitecredito AS porcentaje FROM configuradorgeneral";
			$r = mysql_query($ss,$l) or die($ss);
			$c = mysql_fetch_object($r);
			
			$sss = "SELECT sc.folio as credito, sc.montoautorizado as limitecredito,
			cc.id, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
			CONCAT(sc.calle,' #',sc.numero,' ',sc.colonia,' ',sc.poblacion) AS direccion,
			IFNULL(pg.consumido,0) AS consumido,IFNULL(pgs.vencido,0) AS montovencido,
			CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS vendedorasigando
			FROM solicitudcredito sc
			INNER JOIN catalogocliente cc ON sc.cliente = cc.id
			INNER JOIN catalogoempleado ce ON sc.idusuario = ce.id
			INNER JOIN (SELECT cliente, IFNULL(SUM(total),0) AS consumido FROM pagoguias
			WHERE pagado='S' GROUP BY cliente) pg ON cc.id = pg.cliente
			LEFT JOIN (SELECT pg.cliente, IFNULL(SUM(total),0) AS vencido FROM pagoguias pg
			INNER JOIN solicitudcredito sc ON pg.cliente = sc.cliente
			WHERE DATEDIFF(fechacreo,CURDATE()) > sc.diascredito GROUP BY pg.cliente) pgs ON cc.id = pgs.cliente
			WHERE ".(($_SESSION[IDSUCURSAL]!=1)? " cc.sucursal = ".$_SESSION[IDSUCURSAL]." AND ":"")."
			sc.estado='ACTIVADO' AND cc.activado='SI'";		
			$r = mysql_query($sss,$l) or die($sss);
			$contador = 0;
			while($f = mysql_fetch_object($r)){
				$s4 = "SELECT limitecredito * ".$c->porcentaje."/100.00 AS limite FROM catalogocliente
				WHERE id = ".$f->id."";
				$cl = mysql_query($s4,$l) or die($s4);
				$cli = mysql_fetch_object($cl);
				
				$s5 = "SELECT $f->limitecredito - IFNULL(SUM(IF(pagado='N', total,0)),0) AS disponible
				FROM pagoguias WHERE cliente = '".$f->id."'";
				$rx = mysql_query($s5,$l) or die($s5);
				$fx = mysql_fetch_object($rx);			
				if($fx->disponible <= $cli->limite){
					$contador++;
				}
			}
			
			$s .= ($s != "SELECT ")?",":"";
			$s .= "(SELECT ".$contador.") AS creditolinea";
			
			$s .= ($s != "SELECT ")?",":"";
			$s .= "(SELECT COUNT(*) FROM facturacion
			WHERE credito = 'SI' AND estadocobranza = 'N') AS factrevision";
			
			$s .= ($s != "SELECT ")?",":"";
			$s .= "(SELECT COUNT(*) FROM facturacion f
			INNER JOIN solicitudcredito sc ON f.cliente = sc.cliente
			WHERE f.credito = 'SI' AND f.estadocobranza <> 'C' AND facturaestado <> 'CANCELADO' AND
			DATEDIFF(CURDATE(),f.fecha) - (sc.diascredito) IN(5,4,3,2,1,0)) AS factxvencer";
			
			$s .= ($s != "SELECT ")?",":"";
			$s .= "(SELECT COUNT(*) FROM facturacion
			WHERE credito = 'SI' AND estadocobranza <> 'C' AND facturaestado <> 'CANCELADO' AND
			DATEDIFF(CURDATE(),fecha)>30) AS fact30";
			
			$s .= ($s != "SELECT ")?",":"";
			$s .= "(SELECT IFNULL(SUM(total + sobmontoafacturar + otrosmontofacturar),0) FROM facturacion 
			WHERE estadocobranza <> 'C' AND facturaestado <> 'CANCELADO' AND credito = 'SI') AS cobranza";
			
			return $this->c->consultar($s);
		}
		
		
		public function getProgramaTrabajo(){
			$s = "SELECT 
			(SELECT COUNT(*) FROM facturacion 
			WHERE facturaestado = 'GUARDADO' AND idsucursal = '$_SESSION[IDSUCURSAL]'
			AND estadocobranza <> 'C' AND estadocobranza <> 'R') fapere";	
			
			return $this->c->consultar($s);
		}
		
		public function getIndicadores(){
			$s = "SELECT
			(SELECT IFNULL(SUM(total),0) 
			FROM pagoguias pg
			INNER JOIN catalogocliente cc ON pg.cliente = cc.id
			WHERE (DATEDIFF(CURDATE(),DATE_ADD(pg.fechacreo,INTERVAL cc.diascredito DAY)))>30
			AND pg.pagado = 'N' AND pg.credito='SI' AND pg.sucursalacobrar='$_SESSION[IDSUCURSAL]') AS ca30,
			(SELECT SUM(total) AS total FROM(
				(SELECT IFNULL(SUM(gv.total),0) total
				FROM guiasventanilla gv
				INNER JOIN pagoguias pg ON gv.id = pg.guia
				WHERE (ISNULL(gv.factura) OR gv.factura = '') 
				AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]' AND pg.credito='SI')
				UNION
				(SELECT IFNULL(SUM(ge.total),0) total
				FROM guiasempresariales ge
				INNER JOIN pagoguias pg ON ge.id = pg.guia
				WHERE (ISNULL(ge.factura) OR ge.factura = '') 
				AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]' AND pg.credito='SI')
			) t1) AS fape,
			(SELECT IFNULL(SUM(IFNULL(f.total,0)+IFNULL(f.sobmontoafacturar,0)+IFNULL(f.otrosmontofacturar,0)),0)
			FROM facturacion f
			WHERE f.credito='SI' AND f.estadocobranza = 'R' AND f.idsucursal = '$_SESSION[IDSUCURSAL]') AS faenre,
			(SELECT IFNULL(SUM(total),0) 
			FROM pagoguias pg
			INNER JOIN catalogocliente cc ON pg.cliente = cc.id
			WHERE pg.pagado = 'N' AND pg.credito='SI' AND pg.sucursalacobrar='$_SESSION[IDSUCURSAL]') AS saca,
			(SELECT IFNULL(SUM(total),0) 
			FROM pagoguias pg
			INNER JOIN catalogocliente cc ON pg.cliente = cc.id
			WHERE pg.pagado = 'S' AND pg.credito='SI' AND pg.sucursalacobrar='$_SESSION[IDSUCURSAL]' AND fechapago = CURRENT_DATE) ingre,
			(SELECT IFNULL(SUM(total),0) FROM (
				SELECT IFNULL(de.cantidad,0)+SUM(IFNULL(dd.cantidad,0)) total
				FROM deposito de
				LEFT JOIN depositodetalle dd ON de.folio = dd.deposito
				WHERE de.sucursal = '$_SESSION[IDSUCURSAL]' AND de.fecha = CURRENT_DATE
				GROUP BY de.folio
			) t1) depo";
			
			return $this->c->consultar($s);
		}
	}

?>