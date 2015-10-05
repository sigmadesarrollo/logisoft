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
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursalrealizo = ".$_SESSION[IDSUCURSAL]."":"")."
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
			END AS mes, 
			SUM(solicitudesfolio) AS solicitudesfolio, SUM(guiasventanilla) AS guiasventanilla, SUM(guiasempresariales) AS guiasempresariales
			FROM
			(SELECT UPPER(MONTHNAME(fecharealizacion)) AS mes, 
				   SUM(CASE tipoventa WHEN 'SOLICITUD DE FOLIOS' THEN total ELSE 0 END) AS solicitudesfolio,
				   SUM(CASE tipoventa WHEN 'GUIA VENTANILLA' THEN total ELSE 0 END) AS guiasventanilla,
				   SUM(CASE tipoventa WHEN 'GUIA EMPRESARIAL' THEN total ELSE 0 END) AS guiasempresariales
			FROM reportes_ventas
			WHERE activo = 'S' AND fecharealizacion>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursalrealizo = ".$_SESSION[IDSUCURSAL]."":"")."
			GROUP BY UPPER(MONTHNAME(fecharealizacion)), tipoventa) R1
			GROUP BY mes;";
			return $this->c->consultar($s);
		}
		
		public function getGeneralMesVentanilla($tipo){
			if($tipo=='G Ventanilla'){
				$s = "SELECT IF(tipoflete='PAGADA', IF(tipopago='CONTADO','PAG Contado', 'PAG Credito'),
				IF(tipopago='CREDITO','PCOB Contado', 'PCOB Credito')) AS tipo,
				IFNULL(SUM(total),0) AS total
				FROM reportes_ventas
				WHERE activo = 'S'
				AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE)
				AND tipoventa = 'GUIA VENTANILLA'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursalrealizo = ".$_SESSION[IDSUCURSAL]."":"")."
				GROUP BY tipoflete, tipopago";
			}
			if($tipo=='G Empresariales'){
				$s = "SELECT IF(tipoentrega='EAD', IF(tipopago='CONTADO','PAG Contado', 'PAG Credito'),
				IF(tipopago='CREDITO','PCOB Contado', 'PCOB Credito')) AS tipo,
				IFNULL(SUM(total),0) AS total
				FROM reportes_ventas
				WHERE activo = 'S'
				AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE)
				AND tipoventa = 'GUIA EMPRESARIAL'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursalrealizo = ".$_SESSION[IDSUCURSAL]."":"")."
				GROUP BY tipoflete, tipopago";
			}
			if($tipo=='Solicitud Folios'){
				$s = "SELECT tipopago AS tipo,
				IFNULL(SUM(total),0) AS total
				FROM reportes_ventas
				WHERE activo = 'S'
				AND YEAR(fecharealizacion)=YEAR(CURRENT_DATE)
				AND tipoventa = 'SOLICITUD DE FOLIOS'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursalrealizo = ".$_SESSION[IDSUCURSAL]."":"")."
				GROUP BY tipopago";
			}	
			return $this->c->consultar($s);
		}
		
		public function getGeneralMesCredito(){
			$s = "SELECT IFNULL(SUM(IF(cargo=0,abono,cargo)),0) AS total,
			IF(cargo>0,'Ventas Crédito', 'Abonos Credito') AS tipo
			FROM reporte_cobranza4
			WHERE estado = 'ACTIVADO'
			".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."
			GROUP BY tipo";
			return $this->c->consultar($s);
		}
		
		public function getGeneralCobranzaAnual(){
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
			SUM(Cobrado) AS Cobrado, SUM(Cargado) AS Cargado
			FROM
			(SELECT UPPER(MONTHNAME(fecha)) AS mes, 
				   SUM(IF(abono>0, abono, 0)) AS Cobrado,
				   SUM(IF(cargo>0, cargo, 0)) AS Cargado
			FROM reporte_cobranza4
			WHERE  estado = 'ACTIVADO' AND fecha>=DATE_ADD(CAST(CONCAT(YEAR(CURRENT_DATE()),'/',MONTH(CURRENT_DATE()),'/01') AS DATE), INTERVAL -3 MONTH)
			".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."
			GROUP BY UPPER(MONTHNAME(fecha))) R1
			GROUP BY mes;";
			return $this->c->consultar($s);
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
			SUM(total) AS total
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
			SUM(guias) AS guias, SUM(ead) AS ead, 
			SUM(facturacion) AS facturacion, SUM(abono) AS abono,
			SUM(cobranza) AS cobranza, SUM(ocurre) AS ocurre
			FROM
			(SELECT UPPER(MONTHNAME(fecha)) AS mes, 					
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
			GROUP BY mes;";
			return $this->c->consultar($s);
		}
	}
?>