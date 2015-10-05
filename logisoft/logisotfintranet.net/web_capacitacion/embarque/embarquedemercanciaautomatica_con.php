<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	if($_GET['accion']==1){
		
		$s = "DELETE FROM embarques_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT unidad,remolque1,remolque2 from bitacorasalida where status = 0 and unidad = '$_GET[unidad]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$ins = "(";
		if($f->unidad!="")
			$ins .= "'".$f->unidad."'";
		if($f->remolque1!="")
			$ins .= ",'".$f->remolque1."'";
		if($f->remolque2!="")
			$ins .= ",'".$f->remolque1."'";
		$ins .= ")";
		
		$s = "INSERT INTO embarques_tmp
		SELECT gvu.id, cse.descripcion, gvu.idguia, 'NORMAL', cs.descripcion, cs2.descripcion, gvu.codigobarras, 
		gvu.paquete, CONCAT(gvu.paquete,' de ', gvu.depaquetes), gvu.estado, if(gvu.unidad in $ins,'S','N'), gvu.peso, '$_SESSION[IDUSUARIO]','$_SESSION[IDSUCURSAL]'
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS cse ON gv.sector = cse.id
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON gv.idsucursaldestino = cs2.id
		WHERE gvu.proceso = 'RECOLECTADA' and (gv.estado='ALMACEN ORIGEN' or gv.estado='ALMACEN TRASBORDO' or gv.estado='ALMACEN TRASBORDO INCOMPLETO') and gvu.unidad = '$_GET[unidad]'
		and gvu.ubicacion = $_SESSION[IDSUCURSAL]
		GROUP BY gvu.idguia, gvu.paquete";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "INSERT INTO embarques_tmp
		SELECT geu.id, cse.descripcion, geu.idguia, 'EMPRESARIAL', cs.descripcion, cs2.descripcion, geu.codigobarras, 
		geu.paquete, CONCAT(geu.paquete,' de ', geu.depaquetes), geu.estado, if(geu.unidad in $ins,'S','N'), geu.peso, '$_SESSION[IDUSUARIO]','$_SESSION[IDSUCURSAL]'
		FROM guiasempresariales AS ge
		LEFT JOIN catalogosector AS cse ON ge.sector = cse.id
		INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia
		INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON ge.idsucursaldestino = cs2.id
		WHERE geu.proceso = 'RECOLECTADA' and (ge.estado='ALMACEN ORIGEN' or ge.estado='ALMACEN TRASBORDO' or ge.estado='ALMACEN TRASBORDO INCOMPLETO') and geu.unidad = '$_GET[unidad]'
		and geu.ubicacion = $_SESSION[IDSUCURSAL]
		GROUP BY geu.idguia, geu.paquete;";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "INSERT INTO embarques_tmp
		SELECT gvu.id, cse.descripcion, gvu.idguia, 'NORMAL', cs.descripcion, cs2.descripcion, gvu.codigobarras, 
		gvu.paquete, CONCAT(gvu.paquete,' de ', gvu.depaquetes), gvu.estado,'N', gvu.peso, '$_SESSION[IDUSUARIO]','$_SESSION[IDSUCURSAL]'
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS cse ON gv.sector = cse.id
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON gv.idsucursaldestino = cs2.id
		INNER JOIN (
			SELECT idguia 
			FROM guiaventanilla_unidades 
			WHERE unidad = '$_GET[unidad]' AND proceso = 'RECOLECTADA' 
			GROUP BY idguia
		) t1 ON gv.id = t1.idguia
		WHERE (gvu.unidad='' or isnull(gvu.unidad))";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "INSERT INTO embarques_tmp
		SELECT geu.id, cse.descripcion, geu.idguia, 'EMPRESARIAL', cs.descripcion, cs2.descripcion, geu.codigobarras, 
		geu.paquete, CONCAT(geu.paquete,' de ', geu.depaquetes), geu.estado, 'N', geu.peso, '$_SESSION[IDUSUARIO]','$_SESSION[IDSUCURSAL]'
		FROM guiasempresariales AS ge
		LEFT JOIN catalogosector AS cse ON ge.sector = cse.id
		INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia
		INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON ge.idsucursaldestino = cs2.id
		INNER JOIN (
			SELECT idguia 
			FROM guiasempresariales_unidades 
			WHERE unidad = '$_GET[unidad]' AND proceso = 'RECOLECTADA' 
			GROUP BY idguia
		) t1 ON ge.id = t1.idguia
		WHERE (geu.unidad='' or isnull(geu.unidad))";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'N' and idusuario = $_SESSION[IDUSUARIO] AND sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		//echo $s."<br>";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		$izquierda = str_replace("null",'""',json_encode($arre));
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'S' and idusuario = $_SESSION[IDUSUARIO] AND sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$arre[] = $f;
		}
		$derecha = str_replace("null",'""',json_encode($arre));
		
		echo "({
			   		izquierda:$izquierda, 
			   		derecha:$derecha
				})";
	}
	
	if($_GET[accion]==2){
		$s = "SELECT registro,paquete,codigobarra as codigobarras,estado,guia,peso
		FROM embarques_tmp WHERE guia='$_GET[folio]' and subido='N' and idusuario = $_SESSION[IDUSUARIO] 
		AND sucursal=$_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==3){
		$s = "SELECT registro,paquete,codigobarra as codigobarras,estado,guia,peso
		FROM embarques_tmp 
		WHERE guia='$_GET[folio]' and subido='S' and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal=$_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	
	if($_GET[accion]==4){
		$s = "UPDATE embarques_tmp SET subido = 'S' 
		WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO] AND sucursal=$_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S' AND sucursal=$_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==5){
		$s = "UPDATE embarques_tmp SET subido = 'S' 
		WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal=$_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S' AND sucursal=$_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==6){
		$s = "UPDATE embarques_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal=$_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S' AND sucursal=$_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==7){
		$s = "UPDATE embarques_tmp SET subido = 'N' 
		WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal=$_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S'
		AND sucursal=$_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	
	if($_GET[accion]==8){
		$s = "select t1.guia
		from
		(select guia, subido 
		from embarques_tmp
		where subido = 'S' and idusuario = '$_SESSION[IDUSUARIO]' AND sucursal=$_SESSION[IDSUCURSAL]) as t1
		inner join 
		(select guia, subido 
		from embarques_tmp
		where subido = 'N' and idusuario = '$_SESSION[IDUSUARIO]' AND sucursal=$_SESSION[IDSUCURSAL]) as t2 
		on t1.guia = t2.guia
		group by t1.guia";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$arre = array();
			while($f = mysql_fetch_object($r)){
				$arre[] = $f;
			}
			echo str_replace("null",'""', json_encode($arre));
		}else{
			echo "ok";
		}
	}
	
	if($_GET[accion]==9){
		$s = "insert into embarquedemercancia set 
		folio = obtenerFolio('embarquedemercancia',$_SESSION[IDSUCURSAL]),
		foliobitacora='$_GET[foliobitacora]',unidad = '$_GET[unidad]', tipo='$_GET[tipoembarque]', idsucursal=$_SESSION[IDSUCURSAL],
		ruta = '$_GET[ruta]', recorrido = '$_GET[recorrido]', fecha=current_date, 
		usuario='$_SESSION[NOMBREUSUARIO]', idusuario=$_SESSION[IDUSUARIO]";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		$idx = mysql_insert_id($l);
		
		$s = "SELECT folio FROM embarquedemercancia WHERE id = ".$idx;
		$r = mysql_query($s,$l) or die($s); $fidx = mysql_fetch_object($r);
		
		$idx = $fidx->folio;
		
		$s = "update embarquedemercancia_faltante set embarque = ".$fidx->folio." where embarque IS NULL AND idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "select * from embarques_tmp where subido='N' and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "update guiasventanilla set estado = IF(idsucursalorigen=$_SESSION[IDSUCURSAL],'ALMACEN ORIGEN','ALMACEN TRASBORDO') where id = '$f->guia'";
			mysql_query($s,$l);
			
			$s = "update guiasempresariales set estado = IF(idsucursalorigen=$_SESSION[IDSUCURSAL],'ALMACEN ORIGEN','ALMACEN TRASBORDO') where id = '$f->guia'";
			mysql_query($s,$l);
		}
		
		$s = "CREATE TEMPORARY TABLE tmp_seleccionadas (
			`folioguia` VARCHAR(25) COLLATE utf8_unicode_ci DEFAULT NULL,
			 KEY `folioguia_g` (`folioguia`)
		);";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO tmp_seleccionadas
		VALUES ('".str_replace(",","'),('",$_GET[folios])."')";
		mysql_query($s,$l) or die($s);
		
		//$_GET[folios] = "('".str_replace(",","'),('",$_GET[folios])."')";
		$s="insert into embarquedemercanciadetalle 
		SELECT NULL, '$idx', rg.guia AS guia, rg.origen, 
		CURRENT_DATE, rg.codigobarra, NULL, $_SESSION[IDSUCURSAL]
		FROM embarques_tmp AS rg
		INNER JOIN tmp_seleccionadas tp on rg.guia = tp.folioguia
		WHERE idusuario = $_SESSION[IDUSUARIO] and rg.subido = 'S'
		AND sucursal = '".$_SESSION[IDSUCURSAL]."' 
		GROUP BY rg.guia";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s="INSERT INTO embarquedemercanciapaquetes
		SELECT idunidad,$idx,sector,guia,tipoguia,origen,destino,
		codigobarra,registro,paquete,estado,peso, $_SESSION[IDSUCURSAL]
		FROM embarques_tmp WHERE idusuario=$_SESSION[IDUSUARIO] AND sucursal = ".$_SESSION[IDSUCURSAL]."
		AND subido = 'S'";
		mysql_query($s,$l) or die($s);
		
		//obtener la bitacora para registrarla en el seguimiento
		$s = "SELECT bs.folio
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		INNER JOIN bitacorasalida AS bs ON catalogoruta.id = bs.ruta AND bs.status = 0
		WHERE bs.unidad = '$_GET[unidad]' AND 
		catalogorutadetalle.sucursal = '$_SESSION[IDSUCURSAL]';";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		//seguimiento guiasventanilla y empresariales
		$s = "INSERT INTO seguimiento_guias
		(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
		SELECT e.guia,'$_SESSION[IDSUCURSAL]','$_GET[unidad]',
		CONCAT('EN TRANSITO A ',IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','$f->folio','$fidx->folio'
		FROM embarques_tmp AS e 
		INNER JOIN guiasventanilla g ON e.guia = g.id
		WHERE e.idusuario = '$_SESSION[IDUSUARIO]' AND e.subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
		SELECT e.guia,'$_SESSION[IDSUCURSAL]','$_GET[unidad]',
		CONCAT('EN TRANSITO A ',IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','$f->folio','$fidx->folio'
		FROM embarques_tmp AS e 
		INNER JOIN guiasempresariales g ON e.guia = g.id
		WHERE e.idusuario = '$_SESSION[IDUSUARIO]' AND e.subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		mysql_query($s,$l) or die($s);
		//*******************************************
		
		
		$s = "UPDATE guiaventanilla_unidades
		INNER JOIN embarques_tmp ON guiaventanilla_unidades.idguia = embarques_tmp.guia
		INNER JOIN tmp_seleccionadas on embarques_tmp.guia = tmp_seleccionadas.folioguia
		AND guiaventanilla_unidades.paquete = embarques_tmp.registro
		SET guiaventanilla_unidades.proceso = 'EN TRANSITO', guiaventanilla_unidades.unidad='$_GET[unidad]'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'S'
		and embarques_tmp.sucursal = ".$_SESSION[IDSUCURSAL]."";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiaventanilla_unidades
		INNER JOIN embarques_tmp ON guiaventanilla_unidades.idguia = embarques_tmp.guia
		INNER JOIN tmp_seleccionadas on embarques_tmp.guia = tmp_seleccionadas.folioguia
		AND guiaventanilla_unidades.paquete = embarques_tmp.registro
		SET guiaventanilla_unidades.proceso = '', guiaventanilla_unidades.unidad='$_GET[unidad]'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'N'
		and embarques_tmp.sucursal = ".$_SESSION[IDSUCURSAL]."";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN embarques_tmp ON guiasempresariales_unidades.idguia = embarques_tmp.guia
		INNER JOIN tmp_seleccionadas on embarques_tmp.guia = tmp_seleccionadas.folioguia
		AND guiasempresariales_unidades.paquete = embarques_tmp.registro
		SET guiasempresariales_unidades.proceso = 'EN TRANSITO', guiasempresariales_unidades.unidad='$_GET[unidad]'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'S'
		and embarques_tmp.sucursal = ".$_SESSION[IDSUCURSAL]."";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN embarques_tmp ON guiasempresariales_unidades.idguia = embarques_tmp.guia
		INNER JOIN tmp_seleccionadas on embarques_tmp.guia = tmp_seleccionadas.folioguia
		AND guiasempresariales_unidades.paquete = embarques_tmp.registro
		SET guiasempresariales_unidades.proceso = '', guiasempresariales_unidades.unidad='$_GET[unidad]'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'N'
		and embarques_tmp.sucursal = ".$_SESSION[IDSUCURSAL]."";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "CREATE TEMPORARY TABLE paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN TRANSITO','EN TRANSITO INCOMPLETO') AS estado
		FROM guiasventanilla AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN tmp_seleccionadas tmp on gv.id = tmp.folioguia
		AND gvu.proceso = 'EN TRANSITO'
		GROUP BY gv.id";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "insert into paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN TRANSITO','EN TRANSITO INCOMPLETO') AS estado
		FROM guiasempresariales AS gv
		INNER JOIN guiasempresariales_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN tmp_seleccionadas tmp on gv.id = tmp.folioguia
		AND gvu.proceso = 'EN TRANSITO'
		GROUP BY gv.id";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));

		$s = "UPDATE guiasventanilla
		INNER JOIN paraactualizar ON guiasventanilla.id = paraactualizar.id
		SET guiasventanilla.estado = paraactualizar.estado";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "UPDATE guiasempresariales
		INNER JOIN paraactualizar ON guiasempresariales.id = paraactualizar.id
		SET guiasempresariales.estado = paraactualizar.estado";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "SELECT prefijo FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";
		$su= mysql_query($s,$l) or die($s); 
		$sucur = mysql_fetch_object($su);
		
		$s = "call proc_RegistroLogistica1('embarque','".$_GET[ruta]."','',
		".$_GET[foliobitacora].",UCASE('".$_GET[unidad]."'),'','".$_GET[fechahora]."',
		'','".$_GET[fechahora]."',0,'".$sucur->prefijo."')";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT d.tipo FROM catalogoruta cr
		INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
		WHERE cr.id = ".$_GET[ruta]." AND d.sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		$s = "call proc_RegistroOperaciones('EMBARQUE',".$_GET[foliobitacora].",".$f->tipo.",".$_SESSION[IDSUCURSAL].")";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT COUNT(*) cantidad FROM embarquedemercanciadetalle 
		WHERE idembarque=$idx AND sucursal=".$_SESSION[IDSUCURSAL].";";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$s = "UPDATE lasalertas SET gunoem=gunoem-$f->cantidad WHERE sucursal=$_SESSION[IDSUCURSAL];";
		mysql_query($s,$l) OR die(mysql_error($l).$s);
		
		$s = "SELECT COUNT(*) cantidad FROM recepcionmercanciadetalle 
		WHERE recepcion=$fidx->folio AND sucursal=".$_SESSION[IDSUCURSAL].";";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$s = "UPDATE lasalertas SET mereem=mereem-$f->cantidad WHERE sucursal=$_SESSION[IDSUCURSAL];";
		mysql_query($s,$l) OR die(mysql_error($l).$s);
		
		echo "guardado,".$idx;		
		
	}
	
	if($_GET[accion]==10){
		$s = "SELECT em.unidad, cr.descripcion, cr.id, CONCAT(cs.prefijo,'-',LPAD(em.folio,5,'0')) AS folio, 
		cs.descripcion AS sucursal, DATE_FORMAT(em.fecha, '%d-%m-%Y') AS fecha 
		FROM embarquedemercancia AS em 
		INNER JOIN catalogoruta AS cr ON em.ruta = cr.id
		INNER JOIN catalogosucursal AS cs ON em.idsucursal = cs.id		
		WHERE folio = $_GET[folio] and em.idsucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		
		$s = "SELECT d.id FROM catalogoruta cr
		INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
		WHERE cr.id = ".$f->id."";
		$ru = mysql_query($s,$l) or die($s);
		$cr = mysql_fetch_object($ru);
		
		$s = "SELECT GROUP_CONCAT(d.sucursal) AS sucursal FROM catalogoruta cr
			INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
			WHERE cr.id = ".$f->id." AND tipo between 2 AND 3";
		$des = mysql_query($s,$l) or die($s);
		$dest= mysql_fetch_object($des);
		
		
		$s = "SELECT sucursalestransbordo FROM catalogorutadetalle WHERE ruta=".$f->id." AND tipo BETWEEN 2 AND 3";
		$ry = mysql_query($s,$l) or die($s);
	
		$sucursales = "";
		
		while($fy = mysql_fetch_object($ry)){			
			if(!empty($fy->sucursalestransbordo)){
				$ro = split(",",$fy->sucursalestransbordo);
				for($i=0;$i < count($ro);$i++){
					$y = split(":",$ro[$i]);
					for($j=0;$j < count($y);$j++){
						if(is_numeric($y[$j])){
							$sucursales .= $y[$j].",";
						}
					}
				}					
			}
		}
		
		if(!empty($sucursales)){
			$f->destino = $dest->sucursal.",".substr($sucursales,0, strlen($sucursales)-1);
		}else{			
			$f->destino = $dest->sucursal;
		}
		
		$datosembarque = str_replace("null",'""',json_encode($f));
		
		$s = "DELETE FROM embarques_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]'
		and sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO embarques_tmp
		SELECT idunidad, sector, guia, tipoguia, origen, destino, codigobarra, 
		registro, paquete, estado, 'S', peso, '$_SESSION[IDUSUARIO]','$_SESSION[IDSUCURSAL]'
		FROM embarquedemercanciapaquetes WHERE folioembarque = $_GET[folio] and sucursal = $_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'S' and idusuario = $_SESSION[IDUSUARIO] and sucursal = $_SESSION[IDSUCURSAL]
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$arre[] = $f;
		}
		$derecha = str_replace("null",'""',json_encode($arre));
		
		echo "({
				datosembarque:$datosembarque,
				izquierda:[], 
				derecha:$derecha
			})";
	}
	
	if($_GET[accion]==11){
		$s = "SELECT MAX(folio) AS folio, DATE_FORMAT(CURRENT_DATE, '%d-%m-%Y') AS fecha 
		FROM embarquedemercancia WHERE idsucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$foliofecha = str_replace("null",'""',json_encode($f));
		
		echo "($foliofecha)";
	}
	
?>