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
			END AS tipo,
			IFNULL(SUM(total),0) AS total
			FROM reportes_ventas
			WHERE activo = 'S'
			AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE)
			AND (tipoventa = 'GUIA VENTANILLA' OR tipoventa = 'GUIA EMPRESARIAL' OR tipoventa = 'SOLICITUD DE FOLIOS')
			GROUP BY tipoventa";
			return $this->c->consultar($s);
		}
			
		public function getGeneralAnual(){
			$s = "SELECT mes, SUM(solicitudesfolio) AS solicitudesfolio, SUM(guiasventanilla) AS guiasventanilla, SUM(guiasempresariales) AS guiasempresariales
			FROM
			(SELECT UPPER(MONTHNAME(fecharealizacion)) AS mes, 
				   SUM(CASE tipoventa WHEN 'SOLICITUD DE FOLIOS' THEN total ELSE 0 END) AS solicitudesfolio,
				   SUM(CASE tipoventa WHEN 'GUIA VENTANILLA' THEN total ELSE 0 END) AS guiasventanilla,
				   SUM(CASE tipoventa WHEN 'GUIA EMPRESARIAL' THEN total ELSE 0 END) AS guiasempresariales
			FROM reportes_ventas
			WHERE fecharealizacion>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			GROUP BY UPPER(MONTHNAME(fecharealizacion)), tipoventa) R1
			GROUP BY mes;";
			return $this->c->consultar($s);
		}
		
		public function getGeneralMesVentanilla($tipo){
			if($tipo=='G Ventanilla'){
				$s = "SELECT IF(tipoentrega='EAD', IF(tipopago='CONTADO','EAD Contado', 'EAD Credito'),
				IF(tipopago='CREDITO','Ocurre Contado', 'Ocurre Credito')) AS tipo,
				IFNULL(SUM(total),0) AS total
				FROM reportes_ventas
				WHERE activo = 'S'
				AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE)
				AND tipoventa = 'GUIA VENTANILLA'
				GROUP BY tipoentrega, tipopago";
			}
			if($tipo=='G Empresariales'){
				$s = "SELECT IF(tipoentrega='EAD', IF(tipopago='CONTADO','EAD Contado', 'EAD Credito'),
				IF(tipopago='CREDITO','Ocurre Contado', 'Ocurre Credito')) AS tipo,
				IFNULL(SUM(total),0) AS total
				FROM reportes_ventas
				WHERE activo = 'S'
				AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE)
				AND tipoventa = 'GUIA EMPRESARIAL'
				GROUP BY tipoentrega, tipopago";
			}
			if($tipo=='Solicitud Folios'){
				$s = "SELECT tipopago AS tipo,
				IFNULL(SUM(total),0) AS total
				FROM reportes_ventas
				WHERE activo = 'S'
				AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE)
				AND tipoventa = 'SOLICITUD DE FOLIOS'
				GROUP BY tipopago";
			}	
			return $this->c->consultar($s);
		}
		
		public function getGeneralMesCredito(){
			$s = "SELECT IFNULL(SUM(IF(cargo=0,abono,cargo)),0) AS total,
			IF(cargo>0,'Ventas Crédito', 'Abonos Credito') AS tipo
			FROM reporte_cobranza4
			WHERE estado = 'ACTIVADO'
			GROUP BY tipo";
			return $this->c->consultar($s);
		}
	}
?>