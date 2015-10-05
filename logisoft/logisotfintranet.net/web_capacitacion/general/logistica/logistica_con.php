<?

	session_start();

	require_once('../../Conectar.php');

	$l = Conectarse('webpmm');





if($_GET[accion]==1){//OBTENER DATOS GENERALES -principal.php

		$s = "SELECT Tiempo.Folio,bs.folio AS foliobitacora,DATE_FORMAT(bs.fechabitacora,'%d/%m/%Y') AS fechabitacora,bs.ruta,IF(ISNULL(bs.unidad),
	IF(ISNULL(bs.remolque1),bs.remolque2,bs.remolque1),bs.unidad) AS unidad ,
	ce.id AS idoperador,CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS operador,
	IFNULL(G.guias,0) as guias,
	IF(ISNULL(Tiempo.Tiempo),'00:00:00',Tiempo.Tiempo) AS tiemporecorrido,Tiempo.tipo,
	CONCAT_WS('-',cs.prefijo,IF(Tiempo.tipo='1',CONVERT('DESCARGA' USING latin1),IF(Tiempo.tipo=2 ,CONVERT('EN TRANSITO' USING latin1),IF(Tiempo.tipo=3 ,CONVERT('TERMINADA' USING latin1),NULL))) ) estado,
	IFNULL(DYF.r,0) AS reporte
	FROM  bitacorasalida  bs
	INNER JOIN catalogoempleado ce 	ON ce.id=IF(ISNULL(bs.conductor1),
		IF(ISNULL(bs.conductor2),bs.conductor3,bs.conductor2),bs.conductor1)
	LEFT JOIN 
	(SELECT folio,COUNT(*)AS guias FROM(
	SELECT bs.folio,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.id AS guia,
	gv.idsucursaldestino,cs.prefijo AS sucursal,gv.iddestinatario,
	CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
	COUNT(gv_u.proceso) AS nopaquetes
	FROM bitacorasalida bs
	INNER JOIN embarquedemercancia em ON bs.folio=em.foliobitacora
	INNER JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque
	INNER JOIN guiasventanilla gv ON emd.guia=gv.id
	INNER JOIN guiaventanilla_detalle gv_d ON gv.id=gv_d.idguia
	INNER JOIN catalogosucursal  cs ON gv.idsucursaldestino=cs.id
	INNER JOIN catalogocliente cc ON gv.iddestinatario=cc.id
	INNER JOIN guiaventanilla_unidades gv_u ON gv.id=gv_u.idguia
	WHERE gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
	GROUP BY gv.id
UNION
	SELECT bs.folio,DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fecha,gm.id AS guia,gm.idsucursaldestino,
	cs.prefijo AS sucursal,gm.iddestinatario,
	CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,COUNT(gm_u.proceso) AS nopaquetes
	FROM bitacorasalida bs
	INNER JOIN embarquedemercancia em ON bs.folio=em.foliobitacora
	INNER JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque
	INNER JOIN guiasempresariales gm ON emd.guia=gm.id
	INNER JOIN guiasempresariales_detalle gm_d ON gm.id=gm_d.id
	INNER JOIN catalogosucursal  cs ON gm.idsucursaldestino=cs.id
	INNER JOIN catalogocliente cc ON gm.iddestinatario=cc.id
	INNER JOIN guiasempresariales_unidades gm_u ON gm.id=gm_u.id
	WHERE gm.fecha BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
	GROUP BY gm.id)Tabla GROUP BY folio) AS G ON bs.folio = G.Folio
	LEFT JOIN 
		(SELECT id,idbitacora AS Folio,TIMEDIFF(MAX(fecha),MIN(fecha))AS Tiempo,sucursal,tipo FROM(
		SELECT folio AS id,idbitacora ,fecha,sucursal,tipo FROM programacionrecepciondiaria 
		ORDER BY folio DESC)Tabla GROUP BY idbitacora) Tiempo ON bs.folio = Tiempo.folio
		INNER JOIN catalogosucursal cs ON Tiempo.sucursal=cs.id 
		LEFT JOIN (SELECT COUNT(*) AS r,IFNULL(rm.foliobitacora,0) AS Folio
		FROM reportedanosfaltante rdf 
		INNER JOIN recepcionmercancia rm  ON rdf.recepcion =rm.folio 
		INNER JOIN bitacorasalida bs ON rm.foliobitacora=bs.folio	
		WHERE bs.fechabitacora BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
		GROUP BY rm.foliobitacora) AS DYF ON bs.folio=DYF.Folio
	WHERE  bs.fechabitacora	BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' GROUP BY bs.folio LIMIT ".$_GET[inicio].",30";

		$r=mysql_query($s,$l)or die($s); 

		$registros= array();

		

		if (mysql_num_rows($r)>0){

				while ($f=mysql_fetch_object($r)){

				$registros[]=$f;	

				}

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo "0";

		}

		

}else if($_GET[accion]==2){// RUTA.PHP

	$s = "SELECT cr.descripcion,SEC_TO_TIME(TIME_TO_SEC(prd.ultimahrs)-TIME_TO_SEC(prd2.primerahrs)) AS tiempor,

'DESVIACIONES TIEMPO' AS desviaciones,cr.recorrido 

FROM bitacorasalida bs 

INNER JOIN(SELECT IF(hrsalida='00:00:00',hrllegada,hrsalida) ultimahrs 

FROM programacionrecepciondiaria prd WHERE prd.idbitacora=$_GET[folio]

ORDER BY folio DESC LIMIT 1) prd

INNER JOIN(SELECT IF(hrsalida='00:00:00',hrllegada,hrsalida) primerahrs 

FROM programacionrecepciondiaria prd WHERE prd.idbitacora=$_GET[folio]

ORDER BY folio ASC LIMIT 1) prd2

INNER JOIN catalogoruta cr ON bs.ruta=cr.id

WHERE bs.folio=$_GET[folio] AND bs.fechabitacora	BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' /*AND bs.status=0*/";

		$r = mysql_query($s, $l) or die($s);

		$registros = array();

		while($f = mysql_fetch_object($r)){

			$registros[] = $f;

		}

		echo str_replace('null','""',json_encode($registros));

}else if($_GET[accion]==3){ // UNIDAD.PHP

		$s = "SELECT cu.numeroeconomico,CONCAT_WS('-',cs.prefijo,IF(ISNULL(rrpd.precinto),' ',rrpd.precinto))AS precinto,

		cu.cvolumen, cu.ckilos

		FROM bitacorasalida bs 

		LEFT JOIN catalogounidad cu ON  bs.unidad=cu.numeroeconomico		

		LEFT JOIN recepcionregistroprecintos rrp ON bs.folio=rrp.foliobitacora

		LEFT JOIN recepcionregistroprecintosdetalle rrpd ON rrpd.foliobitacora=rrp.foliobitacora

		LEFT JOIN programacionrecepciondiaria prd ON prd.idbitacora=bs.folio

		LEFT JOIN catalogosucursal cs ON prd.sucursal=cs.id

		WHERE bs.folio='".$_GET[foliobitacora]."' AND bs.fechabitacora	BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' GROUP BY rrpd.precinto /*AND bs.status=0*/";

		$r = mysql_query($s, $l) or die($s);

		$registros = array();

		while($f = mysql_fetch_object($r)){

			$registros[] = $f;

		}

		echo str_replace('null','""',json_encode($registros));

}else if($_GET[accion]==4){ // OPERADOR.PHP

	

	$s = "SELECT CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS nombre,

d.dias, v.viajes, k.suma

FROM bitacorasalida bs

LEFT JOIN programacionrecepciondiaria prd ON prd.idbitacora=bs.folio

INNER JOIN catalogoempleado ce  ON ce.id=IF(ISNULL(bs.conductor1),IF(ISNULL(bs.conductor2),bs.conductor3,bs.conductor2),bs.conductor1)

LEFT JOIN catalogoruta cr ON bs.folio=cr.id

INNER JOIN(SELECT COUNT(*) AS viajes FROM bitacorasalida bs WHERE /*bs.status=1 AND*/ IF(ISNULL(bs.conductor1),IF(ISNULL(bs.conductor2),bs.conductor3,bs.conductor2),bs.conductor1)='".$_GET[operador]."') AS v

INNER JOIN(SELECT SUM(cr.km)AS suma FROM bitacorasalida bs LEFT JOIN catalogoruta cr ON bs.folio=cr.id  WHERE IF(ISNULL(bs.conductor1),IF(ISNULL(bs.conductor2),bs.conductor3,bs.conductor2),bs.conductor1)='".$_GET[operador]."' /*AND bs.status='1'*/) AS k

INNER JOIN(SELECT DATEDIFF(IF(ISNULL(MAX(prd2.fechaprogramacion)),CURRENT_DATE,MAX(prd2.fechaprogramacion)),bs2.fechabitacora)AS dias

FROM bitacorasalida bs2 LEFT JOIN programacionrecepciondiaria prd2 ON bs2.folio=prd2.idbitacora 

WHERE IF(ISNULL(bs2.conductor1),IF(ISNULL(bs2.conductor2),bs2.conductor3,bs2.conductor2),bs2.conductor1)='".$_GET[operador]."'

AND bs2.folio='".$_GET[foliobitacora]."') AS d

WHERE 

IF(ISNULL(bs.conductor1),IF(ISNULL(bs.conductor2),bs.conductor3,bs.conductor2),bs.conductor1)='".$_GET[operador]."'

AND bs.fechabitacora	BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'

GROUP BY nombre";

		$r = mysql_query($s, $l) or die($s);

		$registros = array();

		while($f = mysql_fetch_object($r)){

			$registros[] = $f;

		}

		echo str_replace('null','""',json_encode($registros));

	

}else if($_GET[accion]==5){// GUIAS.PHP

	$s = "(SELECT DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,gv.id AS guia,gv.idsucursaldestino,cs.prefijo AS sucursal,gv.iddestinatario,
CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,COUNT(gv_u.proceso) AS nopaquetes
FROM bitacorasalida bs
LEFT JOIN embarquedemercancia em ON bs.folio=em.foliobitacora
LEFT JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque
LEFT JOIN guiasventanilla gv ON emd.guia=gv.id
LEFT JOIN guiaventanilla_detalle gv_d ON gv.id=gv_d.idguia
LEFT JOIN guiaventanilla_unidades gv_u ON gv.id=gv_u.idguia
LEFT JOIN catalogosucursal  cs ON gv.idsucursaldestino=cs.id
LEFT JOIN catalogocliente cc ON gv.iddestinatario=cc.id
WHERE bs.folio='".$_GET[foliobitacora]."'
AND  bs.fechabitacora BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
GROUP BY emd.guia)
UNION
(SELECT DATE_FORMAT(gm.fecha,'%d/%m/%Y') AS fecha,gm.id AS guia,gm.idsucursaldestino,cs.prefijo AS sucursal,gm.iddestinatario,
CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,COUNT(gm_u.proceso) AS nopaquetes
FROM bitacorasalida bs
LEFT JOIN embarquedemercancia em ON bs.folio=em.foliobitacora
LEFT JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque
LEFT JOIN guiasempresariales gm ON emd.guia=gm.id
LEFT JOIN guiasempresariales_detalle gm_d ON gm.id=gm_d.id
LEFT JOIN guiasempresariales_unidades gm_u ON gm.id=gm_u.id
LEFT JOIN catalogosucursal  cs ON gm.idsucursaldestino=cs.id
LEFT JOIN catalogocliente cc ON gm.iddestinatario=cc.id
WHERE bs.folio='".$_GET[foliobitacora]."'
AND  bs.fechabitacora BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
GROUP BY emd.guia)	LIMIT ".$_GET[inicio].",30";

		$r=mysql_query($s,$l)or die($s); 

		$registros= array();

		

		if (mysql_num_rows($r)>0){

				while ($f=mysql_fetch_object($r)){

				$registros[]=$f;	

				}

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo "0";

		}

}else if($_GET[accion]==6){//  TIPO INCIDENCIAS.PHP

	$s = "SELECT rdf.guia,DATE_FORMAT(rdf.fecha,'%d/%m/%Y') AS fecha,IF(rdf.dano='0',IF(rdf.faltante='0',NULL,'FALTANTE'),'DAO') AS tipoincidente

	FROM reportedanosfaltante rdf 

	INNER JOIN recepcionmercancia rm  ON rdf.recepcion =rm.folio

	WHERE rm.foliobitacora='".$_GET[foliobitacora]."'    ";
	
	/*LIMIT ".$_GET[inicio].",30*/
	/*and DATE(rdf.fecha)

BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' */

		$r=mysql_query($s,$l)or die($s); 

		$registros= array();

		

		if (mysql_num_rows($r)>0){

				while ($f=mysql_fetch_object($r)){
				$f->tipo=cambio_texto($f->tipo);
				$registros[]=$f;	

				}

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo "0";

		}

}else if($_GET[accion]==7){ // Daños y faltantes

	$s = "SELECT rdf.guia,DATE_FORMAT(rdf.fecha,'%d/%m/%Y') AS fecha,rm.unidad,

IF(rdf.dano=0,IF(rdf.faltante=0,'null','FALTANTE'),'DAÑO') AS estado

FROM reportedanosfaltante rdf 

INNER JOIN recepcionmercancia rm  ON rdf.recepcion =rm.folio

WHERE rdf.guia='$_GET[guia]'  ";
/*AND  DATE(rdf.fecha) BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' */

		$r = mysql_query($s, $l) or die($s);

		$registros = array();

		while($f = mysql_fetch_object($r)){

			$f->estado	 =cambio_texto($f->estado);

			$registros[] = $f;

		}

		echo str_replace('null','""',json_encode($registros));

}else if($_GET[accion]==8){ // FECHAACTUAL DEFAULT

	$s = "SELECT DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fechaactual";

		$r = mysql_query($s, $l) or die($s);

		$registros = array();

		$f = mysql_fetch_object($r);

		echo str_replace('null','""',json_encode($f));

}else if($_GET[accion]==9){// rmdañosfaltantes.php

		$s = "SELECT T1.guia,T1.estado,T1.destinatario,T1.destino,T1.origen,T1.fecha,T1.recepcion,T1.comentario FROM 

			(SELECT gv.id AS guia,gv.estado,

			CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario,

			cd.descripcion AS destino,

			cs.descripcion AS origen, DATE_FORMAT(rm.fecha,'%d/%m/%Y')AS fecha,rdf.recepcion,'COMENTARIO' AS comentario

			FROM guiasventanilla gv

			INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia

			LEFT JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id

			LEFT JOIN catalogodestino cd ON gv.iddestino = cd.id

			LEFT JOIN catalogocliente cc ON gv.idremitente = cc.id

			LEFT JOIN catalogocliente cde ON gv.iddestinatario = cde.id

			LEFT JOIN reportedanosfaltante  rdf ON gv.id=rdf.guia

			LEFT JOIN recepcionmercancia  rm ON rdf.recepcion=rm.folio

			UNION 

			SELECT ge.id AS guia,ge.estado,

			CONCAT(cde.nombre,' ',cde.paterno,' ',cde.materno) AS destinatario,

			cd.descripcion AS destino,

			cs.descripcion AS origen, DATE_FORMAT(rm.fecha,'%d/%m/%Y')AS fecha ,rdf.recepcion,'COMENTARIO' AS comentario

			FROM guiasempresariales ge

			INNER JOIN guiaventanilla_unidades gvu ON ge.id = gvu.idguia

			LEFT JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id

			LEFT JOIN catalogodestino cd ON ge.iddestino = cd.id

			LEFT JOIN catalogocliente cc ON ge.idremitente = cc.id

			LEFT JOIN catalogocliente cde ON ge.iddestinatario = cde.id

			LEFT JOIN reportedanosfaltante  rdf ON ge.id=rdf.guia

			LEFT JOIN recepcionmercancia  rm ON rdf.recepcion=rm.folio) AS T1

			WHERE T1.guia = '".$_GET[guia]."'

			GROUP BY T1.guia";

		$r = mysql_query($s, $l) or die($s);

		$registros = array();

		while($f = mysql_fetch_object($r)){

			$registros[] = $f;

		}

		echo str_replace('null','""',json_encode($registros));

		

}else if($_GET[accion]==10){

		

			$s = "SELECT Tiempo.Folio,bs.folio AS foliobitacora,DATE_FORMAT(bs.fechabitacora,'%d/%m/%Y') AS fechabitacora,bs.ruta,IF(ISNULL(bs.unidad),

	IF(ISNULL(bs.remolque1),bs.remolque2,bs.remolque1),bs.unidad) AS unidad ,

	ce.id AS idoperador,CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS operador,

	G.guias,

	IF(ISNULL(Tiempo.Tiempo),'00:00:00',Tiempo.Tiempo) AS tiemporecorrido,Tiempo.tipo,

	CONCAT_WS('-',cs.prefijo,IF(Tiempo.tipo='1',CONVERT('DESCARGA' USING latin1),IF(Tiempo.tipo=2 ,CONVERT('EN TRANSITO' USING latin1),IF(Tiempo.tipo=3 ,CONVERT('TERMINADA' USING latin1),NULL))) ) estado,

	IFNULL(DYF.r,0) AS reporte

	FROM  bitacorasalida  bs

	INNER JOIN catalogoempleado ce 	ON ce.id=IF(ISNULL(bs.conductor1),

		IF(ISNULL(bs.conductor2),bs.conductor3,bs.conductor2),bs.conductor1)

	LEFT JOIN (SELECT COUNT(*) AS guias,IFNULL(bs.folio,0) AS Folio

		FROM bitacorasalida bs

		INNER JOIN embarquedemercancia em ON bs.folio=em.foliobitacora

		INNER JOIN embarquedemercanciadetalle emd ON em.folio=emd.idembarque) AS G ON bs.folio = G.Folio

	LEFT JOIN 

		(SELECT id,idbitacora AS Folio,TIMEDIFF(MAX(fecha),MIN(fecha))AS Tiempo,sucursal,tipo FROM(

SELECT folio AS id,idbitacora ,fecha,sucursal,tipo

FROM programacionrecepciondiaria 

ORDER BY folio DESC)Tabla GROUP BY idbitacora  ) Tiempo ON bs.folio = Tiempo.folio

	INNER JOIN catalogosucursal cs ON Tiempo.sucursal=cs.id 

	LEFT JOIN (SELECT COUNT(*) AS r,IFNULL(rm.foliobitacora,0) AS Folio

		FROM reportedanosfaltante rdf 

		INNER JOIN recepcionmercancia rm  ON rm.foliobitacora =rm.folio ) AS DYF ON bs.folio=DYF.Folio

	WHERE  bs.fechabitacora	BETWEEN '".cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' GROUP BY bs.folio";

	$t = mysql_query($s,$l) or die($s);

	$tdes = mysql_num_rows($t);

	echo $tdes;

}



?>