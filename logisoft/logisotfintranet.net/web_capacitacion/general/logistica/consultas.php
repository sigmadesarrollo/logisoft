<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){//LOGISTICA PRINCIPAL
		$s = "SELECT TIMEDIFF(tiemporecorrido,MAX(horasalida)) AS trecorrido, t1.* FROM(SELECT id, horasalida,
		DATE_FORMAT(fecha,'%d/%m/%Y') AS fechar, ruta, unidad, idoperador1, operador1,
		IF(idoperador2=0,'',idoperador2) AS idoperador2, IFNULL(operador2,'') AS operador2,
		IF(idoperador3=0,'',idoperador3) AS idoperador3, IFNULL(operador3,'') AS operador3, 
		IFNULL(guias,'') AS guias, tiemporecorrido, estado,
		IFNULL(reporteincidencias,0) AS reporteincidencias,bitacora FROM reporte_logistica1
		WHERE CAST(fecha AS DATE) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		ORDER BY fecha DESC)t1 GROUP BY unidad";
		if($_GET[tipo]=="0"){
			$r = mysql_query($s,$l) or die($s);
			echo mysql_num_rows($r);
		}else if($_GET[tipo]==1){
			$registros = array();
			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->unidad = cambio_texto($f->unidad);
					$f->operador1 = cambio_texto($f->operador1);
					$f->operador2 = cambio_texto($f->operador2);
					$f->operador3 = cambio_texto($f->operador3);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}
	}else if($_GET[accion]=="ultimoprincipal"){//ULTIMO PRINCIPAL LOGISTICA
		$s = "SELECT COUNT(DISTINCT(bitacora)) AS total FROM reporte_logistica1 
		WHERE fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;
		
		$s = "SELECT TIMEDIFF(tiemporecorrido,MAX(horasalida)) AS trecorrido, t1.* FROM(SELECT id, horasalida,
		DATE_FORMAT(fecha,'%d/%m/%Y') AS fechar, ruta, unidad, idoperador1, operador1,
		IF(idoperador2=0,'',idoperador2) AS idoperador2, IFNULL(operador2,'') AS operador2,
		IF(idoperador3=0,'',idoperador3) AS idoperador3, IFNULL(operador3,'') AS operador3, 
		IFNULL(guias,'') AS guias, tiemporecorrido, estado,
		IFNULL(reporteincidencias,0) AS reporteincidencias,bitacora FROM reporte_logistica1
		WHERE CAST(fecha AS DATE) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		ORDER BY fecha DESC)t1 GROUP BY unidad LIMIT ".$limit."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->unidad = cambio_texto($f->unidad);
				$f->operador1 = cambio_texto($f->operador1);
				$f->operador2 = cambio_texto($f->operador2);
				$f->operador3 = cambio_texto($f->operador3);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==2){//REPORTE LOGISTICA POR RUTA
		$s = "SELECT t2.descripcion_ruta AS ruta, TIMEDIFF(MAX(t1.fecha),MIN(t1.fecha)) AS trecorrido,
		CONCAT_WS(' / ',tiempocarga, tiempodescarga) AS tiempocd, recorrido FROM reporte_logistica2 t2
		INNER JOIN reporte_logistica1 t1 ON t2.bitacora = t1.bitacora 
		WHERE t2.bitacora = ".$_GET[bitacora]."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->descripcion_ruta = cambio_texto($f->descripcion_ruta);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==3){//REPORTE LOGISTICA POR UNIDAD
		$s = "SELECT b.unidad, CONCAT_WS('-',r.precinto,cs.prefijo) AS precintoasignado,
		cu.cvolumen, cu.ckilos FROM bitacorasalida b
		INNER JOIN catalogounidad cu ON b.unidad = cu.numeroeconomico
		LEFT JOIN recepcionregistroprecintosdetalle r ON b.folio = r.foliobitacora
		LEFT JOIN catalogosucursal cs ON r.sucursal = cs.id
		WHERE b.folio = ".$_GET[bitacora]."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->precintoasignado = cambio_texto($f->precintoasignado);
				$f->unidad = cambio_texto($f->unidad);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==4){//REPORTE LOGISTICA POR OPERADOR	
		$s = "SELECT nombre, IFNULL(SUM(diastrabajados),0) AS diastrabajados, SUM(kmrecorrido) AS kmrecorrido,
		COUNT(id) AS viajes FROM reporte_logistica4
		WHERE CAST(fechasalida AS DATE) BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."' AND idempleado = ".$_GET[operador]." 
		HAVING nombre IS NOT NULL";
		$registros = array();
		$r = mysql_query($s, $l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$f->diastrabajados = (($f->diastrabajados==0)? 1 : $f->diastrabajados);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}		
	}else if($_GET[accion]==5){//REPORTE LOGISTICA POR GUIAS
		$s = "SELECT DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fecha, guia,
		destino, destinatario, paquetes AS nopaquetes FROM reporte_logistica3
		WHERE idtabla1 = ".$_GET[idtabla]."";
		if($_GET[tipo]=="0"){
			$r = mysql_query($s,$l) or die($s);
			echo mysql_num_rows($r);
		}else if($_GET[tipo]==1){
			$registros = array();
			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->guia = cambio_texto($f->guia);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}
	}else if($_GET[accion]=="ultimoguias"){//ULTIMO REPORTE LOGISTICA POR GUIAS
		$s = "SELECT COUNT(*) AS total FROM reporte_logistica3
		WHERE idtabla1 = ".$_GET[idtabla]."";
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;
		
		$s = "SELECT DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fechaguia, guia,
		destino, destinatario, paquetes FROM reporte_logistica3
		WHERE idtabla1 = ".$_GET[idtabla]." LIMIT ".$limit."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->guia = cambio_texto($f->guia);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	
	}else if($_GET[accion]==6){//REPORTE INCIDENCIAS LOGISTICA
		$s = "SELECT DATE_FORMAT(rd.fecha,'%d/%m/%Y') AS fecha,
		IF(rd.dano=1,'DAÑO',IF(rd.faltante=1,'FALTANTE','')) AS incidencia,
		".$_GET[bitacora]." AS bitacora FROM recepcionmercancia rm
		INNER JOIN reportedanosfaltante rd ON rm.folio = rd.recepcion
		WHERE rm.foliobitacora = ".$_GET[bitacora]."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->incidencia = cambio_texto($f->incidencia);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==7){//REPORTE DAÑOS Y FALTANTES
		$s = "SELECT IF(rep.dano = 1,'DAÑO',IF(rep.faltante = 1,'FALTANTE',IF(rep.sobrante = 1,'SOBRANTE',''))) AS tipo,
		rep.guia, t.estado, t.destinatario, t.destino, t.origen,
		DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecharecepcion, rep.recepcion, rep.comentarios FROM reportedanosfaltante rep
		INNER JOIN (SELECT gv.id AS guia, gv.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasventanilla AS gv
		INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		UNION
		SELECT ge.id AS guia, ge.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasempresariales AS ge
		INNER JOIN catalogocliente des ON ge.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id) t ON rep.guia=t.guia
		INNER JOIN recepcionmercancia rm ON rep.recepcion = rm.folio
		WHERE rm.foliobitacora = ".$_GET[bitacora]."";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->tipo = cambio_texto($f->tipo);
				$f->guia = cambio_texto($f->guia);
				$f->estado = cambio_texto($f->estado);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->destino = cambio_texto($f->destino);
				$f->origen = cambio_texto($f->origen);
				$f->comentarios = cambio_texto($f->comentarios);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}
?>