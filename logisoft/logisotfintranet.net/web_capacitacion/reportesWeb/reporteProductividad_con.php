<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmintranet.net';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		
		if($_GET[sucursal]<>0){
			$and1 = " and (idsucursalorigen=$_GET[sucursal] or idsucursaldestino=$_GET[sucursal]) ";
			$and2 = " and r2.sucursal=$_GET[sucursal] ";
		}
		
		$s = "SELECT nombrecliente,SUM(undiaead) AS undiaead, SUM(dosdiaead) AS dosdiaead, SUM(faltanteead) AS faltanteead,
		ROUND(100*(IFNULL(SUM(undiaead),0)/(SUM(undiaead)+SUM(dosdiaead)+SUM(faltanteead))),0) AS undiaporc,
		ROUND(100*(IFNULL(SUM(dosdiaead),0)/(SUM(undiaead)+SUM(dosdiaead)+SUM(faltanteead))),0) AS dosdiaporc,
		ROUND(100*(IFNULL(SUM(faltanteead),0)/(SUM(undiaead)+SUM(dosdiaead)+SUM(faltanteead))),0) AS faltanteporc,
		SUM(undiarec) AS undiarec, SUM(dosdiasrec) AS dosdiasrec, SUM(faltanterec) AS faltanterec,
		ROUND(100*IFNULL(IFNULL(SUM(undiarec),0)/(SUM(undiarec)+SUM(dosdiasrec)+SUM(faltanterec)),0),0) AS undiaporc2,
		ROUND(100*IFNULL(IFNULL(SUM(dosdiasrec),0)/(SUM(undiarec)+SUM(dosdiasrec)+SUM(faltanterec)),0),0) AS dosdiaporc2,
		ROUND(100*IFNULL(IFNULL(SUM(faltanterec),0)/(SUM(undiarec)+SUM(dosdiasrec)+SUM(faltanterec)),0),0) AS faltanteporc2 
		FROM(
			SELECT r1.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente, 
			IFNULL(SUM(IF(diasead = 0,1,0)),0) AS undiaead, IFNULL(SUM(IF(diasead >= 1,1,0)),0) AS dosdiaead,
			IFNULL(
				(SELECT SUM(t.total) FROM(
						SELECT COUNT(*) AS total FROM guiasventanilla 
						WHERE YEAR(fecha)=YEAR('".cambiaf_a_mysql($_GET[fecha])."') AND MONTH(fecha)=MONTH('".cambiaf_a_mysql($_GET[fecha])."') AND 
						estado = 'ALMACEN DESTINO' AND ocurre = 0 $and1
					UNION
						SELECT COUNT(*) AS total FROM guiasempresariales 
						WHERE YEAR(fecha)=YEAR('".cambiaf_a_mysql($_GET[fecha])."') AND MONTH(fecha)=MONTH('".cambiaf_a_mysql($_GET[fecha])."') AND 
						estado = 'ALMACEN DESTINO' AND ocurre = 0 $and1
										) t
				),0) AS faltanteead,0 AS undiarec, 0 AS dosdiasrec, 0 AS faltanterec 
				FROM reporteproductividad_cliente1 r1
				INNER JOIN catalogocliente cc ON r1.cliente = cc.id
				WHERE YEAR(fecharecepcion)=YEAR('".cambiaf_a_mysql($_GET[fecha])."') AND MONTH(fecharecepcion)=MONTH('".cambiaf_a_mysql($_GET[fecha])."')
			UNION
				SELECT r2.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente,
				0 AS undiaead, 0 AS dosdiasead, 0 AS totalead, SUM(IF(diasrecoleccion = 0,1,0)) AS undiarec,
				SUM(IF(diasrecoleccion >= 1,1,0)) AS dosdiasrec,
				(
					SELECT COUNT(*) FROM recoleccion WHERE (realizo IS NULL OR realizo = 'NO')
				) AS faltanterec
				FROM reporteproductividad_cliente2 r2
				INNER JOIN catalogocliente cc ON r2.cliente = cc.id
				WHERE YEAR(fechasolicitud)=YEAR('".cambiaf_a_mysql($_GET[fecha])."') AND MONTH(fechasolicitud)=MONTH('".cambiaf_a_mysql($_GET[fecha])."') AND 
				diasrecoleccion IS NOT NULL	$and2
		) t GROUP BY cliente HAVING nombrecliente <>''";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$f->nombrecliente = cambio_texto($f->nombrecliente);
			$ar[] = $f;
		}
			
		echo json_encode($ar);	
	}
	
	if($_GET[accion]==2){
		$s = "SELECT id, CONCAT_WS(' ',nombre,paterno,materno) ncliente
		FROM catalogocliente WHERE id = '$_GET[cliente]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->ncliente = utf8_encode($f->ncliente);
		
		echo "(".json_encode($f).")";
	}
?>