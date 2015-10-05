<?
	include("../../coneccion/Coneccion.php");
	class Presupuesto{
		var $c;
		var $host = "localhost";
		var $user = "pmm";
		var $pass = "guhAf2eh";
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
			$s = "SELECT 
			sucursal,
			SUM(ve_guias) ve_guias,
			SUM(ve_cant_guias) ve_cant_guias,
			SUM(ve_facturacion) ve_facturacion,
			SUM(ve_cant_facturacion) ve_cant_facturacion,
			SUM(op_repartos) op_repartos,
			SUM(op_recolecciones) op_recolecciones,
			SUM(op_ead) op_ead,
			SUM(ca_recolecion) ca_recolecion,
			SUM(ca_ead) ca_ead,
			SUM(cc_ingreso) cc_ingreso,
			SUM(cc_cobranza) cc_cobranza
			FROM tabcon_ventaspresupuesto
			WHERE YEAR(fecha)='$ano' AND MONTH(fecha)='$mes'
			GROUP BY sucursal";
			return $this->c->consultar($s);
		}
		
		public function getGrafica($ano, $sucursal=null){
			$l = mysql_connect($this->host, $this->user, $this->pass);
			mysql_select_db($this->base);
			
			if($sucursal!=null){
				$s = "select id from catalogosucursal where prefijo = '$sucursal'";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				$and = " AND sucursal = '$sucursal'";
				$and2 = " AND sucursal = '$f->id'";
			}
			$s = "SELECT CASE UPPER(MONTHNAME(fecha)) 
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
			END AS mes, month(fecha) as ordenar,
			SUM(ve_cant_guias)+SUM(ve_cant_facturacion) total
			FROM tabcon_ventaspresupuesto
			WHERE YEAR(fecha)=$ano $and
			GROUP BY UPPER(MONTHNAME(fecha))
			order by ordenar asc";
			$r=mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$s = "select sum(".strtolower($f->mes).") mes from catalogopresupuesto where YEAR(fechapresupuesto)=$ano $and2";
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
				$and2 = "AND tvp.sucursal = '$sucursal'";
			}
			
			$s = "SELECT FORMAT(SUM(tvp.ve_cant_guias) + SUM(tvp.ve_cant_facturacion),2) totalventa,
			FORMAT(SUM(cp.".$meses[$mes].")/COUNT(DISTINCT(tvp.id)) ,2) presupuesto,
			FORMAT((SUM(tvp.ve_cant_guias) + SUM(tvp.ve_cant_facturacion))*(100/ (SUM(cp.".$meses[$mes].")/COUNT(DISTINCT(tvp.id))) ),2) AS porcentaje
			FROM tabcon_ventaspresupuesto tvp
			LEFT JOIN catalogopresupuesto cp ON YEAR(cp.fechapresupuesto)=$ano $and
			WHERE YEAR(tvp.fecha)='$ano' AND MONTH(tvp.fecha)='$mes' $and2";
			
			return $this->c->consultar($s);
		}
	}
?>