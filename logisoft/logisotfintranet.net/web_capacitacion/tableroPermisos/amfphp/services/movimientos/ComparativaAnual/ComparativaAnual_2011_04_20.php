<?
	session_start();
	include("../../coneccion/Coneccion.php");
			
	class ComparativaAnual{
		var $c;
		
		public function ComparativaAnual(){
			$this->c = new Coneccion();
		}
		
		public function getAnos(){
			$s = "SELECT ano AS label, ano AS data FROM tabcon_comparativoanual
			GROUP BY ano;";
			return $this->c->consultar($s);
		}
		
		public function getComparativa($ano1,$ano2,$mes){
			$s = "SELECT tca1.sucursal,tca1.zona,tca1.clasificacion,
			tca1.ventamensual, tca1.presupuesto, tca1.ventamensual-tca1.presupuesto AS variacion_vm_m, ROUND(((tca1.ventamensual-tca1.presupuesto)*100)/tca1.ventamensual,2) AS variacion_vm_p,
			tca1.venta venta1, tca2.venta2, tca1.venta-tca2.venta2 AS variacion_va_m, ROUND(((tca1.venta-tca2.venta2)*100)/tca1.venta,2) AS variacion_va_p,
			tca1.guias guias1, tca2.guias2, tca1.guias-tca2.guias2 AS variacion_ga_m, ROUND(((tca1.guias-tca2.guias2)*100)/tca1.guias,2) AS variacion_ga_p,
			tca1.importeguias importeguias1, tca2.importeguias2, tca1.importeguias-tca2.importeguias2 AS variacion_iga_m, ROUND(((tca1.importeguias-tca2.importeguias2)*100)/tca1.importeguias,2) AS variacion_iga_p,
			tca1.facturas facturas1, tca2.facturas2,
			tca1.recibido recibido1, tca2.recibido2, tca1.recibido-tca2.recibido2 AS variacion_ra_m, ROUND(((tca1.recibido-tca2.recibido2)*100)/tca1.recibido,2) AS variacion_ra_p
			FROM tabcon_comparativoanual tca1
			INNER JOIN(
				SELECT tca2.venta venta2,tca2.guias guias2, tca2.importeguias importeguias2, tca2.facturas facturas2 ,tca2.recibido recibido2
				FROM tabcon_comparativoanual tca2
				WHERE tca2.ano = $ano2 AND tca2.mes = $mes
			) AS tca2
			WHERE tca1.ano = $ano1 AND tca1.mes = $mes";
			return $this->c->consultar($s);
		}	
	}
?>