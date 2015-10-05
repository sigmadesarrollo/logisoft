<?
	session_start();
	include("../../coneccion/Coneccion.php");
	
	class VentasServicioCliente{
		var $c;
		
		public function VentasServicioCliente(){
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
			(SELECT IFNULL(SUM(IFNULL(f.total,0)+IFNULL(f.sobmontoafacturar,0)+IFNULL(f.otrosmontofacturar,0)),0)
						FROM facturacion f
						WHERE f.credito='SI' AND f.estadocobranza <> 'C' AND f.estadocobranza = 'R' 
						AND f.idsucursal = '$_SESSION[IDSUCURSAL]' AND f.facturaestado = 'GUARDADO') AS fapere";
			
			return $this->c->consultar($s);
		}	
		
		function getProgramaTrabajo(){
			$s = "SELECT
			(SELECT COUNT(*) FROM generacionconvenio WHERE estadoconvenio = 'IMPRESO') copeac,
			(SELECT COUNT(*) FROM generacionconvenio WHERE estadoconvenio = 'AUTORIZADO') copeim,
			(SELECT COUNT(*) FROM propuestaconvenio WHERE estadopropuesta LIKE '%EN AUTORIZACION%') prpeau";
					
			return $this->c->consultar($s);
		}
		
		function getIndicadores(){
			$s = "SELECT
			(SELECT COUNT(*) FROM guiasventanilla
			WHERE fecha=CURDATE() AND estado='CANCELADO' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."') AS cancelada,
			(SELECT COUNT(*) FROM guiasventanilla
			WHERE fecha=CURDATE() AND condicionpago='1'	AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."') AS credito,
			(SELECT COUNT(*) FROM facturacion
			WHERE fecha=CURDATE() AND facturaestado='CANCELADO' AND idsucursal='".$_SESSION[IDSUCURSAL]."') AS fcanceladas,
			(SELECT SUM(total) FROM (
				(SELECT COUNT(*) total FROM guiasventanilla WHERE condicionpago = 1 
				AND (estado <>'CANCELADO' OR estado <>'CANCELADA') AND idsucursalorigen = '".$_SESSION[IDSUCURSAL]."')
				UNION
				(SELECT COUNT(*) total FROM guiasempresariales WHERE tipopago = 'CREDITO' 
				AND (estado <>'CANCELADO' OR estado <>'CANCELADA') AND idsucursalorigen = '".$_SESSION[IDSUCURSAL]."')
			)t1 ) vecr";
						
			return $this->c->consultar($s);
		}
	}
?>