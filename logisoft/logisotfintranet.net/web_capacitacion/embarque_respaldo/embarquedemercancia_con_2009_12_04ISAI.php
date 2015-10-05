<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	if($_GET['accion']==1){
		$s = "SELECT catalogorutadetalle.id
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		INNER JOIN bitacorasalida AS bs ON catalogoruta.id = bs.ruta AND bs.status = 0
		WHERE bs.unidad = '$_GET[unidad]' AND catalogorutadetalle.sucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$iddetalle = ($f->id!="")?$f->id:"0";
		
		$s = "create temporary table tmp_sucursales
		SELECT catalogorutadetalle.sucursal
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		inner join bitacorasalida as bs on catalogoruta.id = bs.ruta AND bs.status = 0
		WHERE bs.unidad = '$_GET[unidad]'
		AND catalogorutadetalle.id > $iddetalle";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into tmp_sucursales
		SELECT catalogorutadetallesucursal.idsucursal
		FROM catalogorutadetallesucursal
		INNER JOIN catalogoruta ON catalogorutadetallesucursal.idruta = catalogoruta.id
		inner join bitacorasalida as bs on catalogoruta.id = bs.ruta AND bs.status = 0
		WHERE bs.unidad = '$_GET[unidad]'";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM embarques_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]';";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT catalogorutadetalle.* 
		FROM catalogoruta
		INNER JOIN catalogorutadetalle ON catalogoruta.id = catalogorutadetalle.ruta
		INNER JOIN bitacorasalida ON catalogoruta.id = bitacorasalida.ruta
		WHERE bitacorasalida.status = 0 AND catalogorutadetalle.tipo=1 AND catalogorutadetalle.sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$inner1 = " ";
		}else{
			$s = "SELECT catalogorutadetalle.* 
			FROM catalogoruta
			INNER JOIN catalogorutadetalle ON catalogoruta.id = catalogorutadetalle.ruta
			INNER JOIN bitacorasalida ON catalogoruta.id = bitacorasalida.ruta
			WHERE bitacorasalida.status = 0 AND catalogorutadetalle.tipo<>1 AND catalogorutadetalle.sucursal = '$_SESSION[IDSUCURSAL]'";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$inner1 = " INNER JOIN programacionrecepciondiaria as pr on '$_GET[unidad]' = pr.unidad and pr.hrllegada <> '00:00:00' and pr.hrsalida = '00:00:00' ";
			}else{
				$inner1 = " ";
			}
		}
		
		$s = "INSERT INTO embarques_tmp
		SELECT gvu.id, cse.descripcion, gvu.idguia, 'NORMAL', cs.descripcion, cs2.descripcion, gvu.codigobarras, 
		gvu.paquete, CONCAT(gvu.paquete,' de ', gvu.depaquetes), gvu.estado, 'N', gvu.peso, '$_SESSION[IDUSUARIO]'
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS cse ON gv.sector = cse.id
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON gv.idsucursaldestino = cs2.id
		$inner1
		INNER JOIN tmp_sucursales as tmps on gv.idsucursaldestino = tmps.sucursal
		WHERE (isnull(gvu.proceso) or gvu.proceso = 'ALMACEN TRASBORDO') and (isnull(gvu.unidad) OR gvu.unidad='')
		and gvu.ubicacion = $_SESSION[IDSUCURSAL]";
		//echo $s;
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "INSERT INTO embarques_tmp
		SELECT geu.id, cse.descripcion, geu.idguia, 'EMPRESARIAL', cs.descripcion, cs2.descripcion, geu.codigobarras, 
		geu.paquete, CONCAT(geu.paquete,' de ', geu.depaquetes), geu.estado, 'N', geu.peso, '$_SESSION[IDUSUARIO]'
		FROM guiasempresariales AS ge
		LEFT JOIN catalogosector AS cse ON ge.sector = cse.id
		INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia
		INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON ge.idsucursaldestino = cs2.id
		$inner1
		WHERE (isnull(geu.proceso) or geu.proceso = 'ALMACEN TRASBORDO') and (isnull(geu.unidad) OR geu.unidad='')
		and geu.ubicacion = $_SESSION[IDSUCURSAL];";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'N' and idusuario = $_SESSION[IDUSUARIO]
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		$izquierda = str_replace("null",'""',json_encode($arre));
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'S' and idusuario = $_SESSION[IDUSUARIO]
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		$derecha = str_replace("null",'""',json_encode($arre));
		
		echo "({
			   		izquierda:$izquierda, 
			   		derecha:$derecha
				})";
	}
	
	if($_GET[accion]==2){
		$s = "SELECT registro,paquete,codigobarra,estado,guia,peso
		FROM embarques_tmp WHERE guia='$_GET[folio]' and subido='N' and idusuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==3){
		$s = "SELECT registro,paquete,codigobarra,estado,guia,peso
		FROM embarques_tmp WHERE guia='$_GET[folio]' and subido='S' and idusuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==4){
		$s = "UPDATE embarques_tmp SET subido = 'S' WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==5){
		$s = "UPDATE embarques_tmp SET subido = 'S' WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==6){
		$s = "UPDATE embarques_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==7){
		$s = "UPDATE embarques_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	
	if($_GET[accion]==8){
		$s = "select t1.guia
		from
		(select guia, subido 
		from embarques_tmp
		where subido = 'S' and idusuario = '$_SESSION[IDUSUARIO]') as t1
		inner join 
		(select guia, subido 
		from embarques_tmp
		where subido = 'N' and idusuario = '$_SESSION[IDUSUARIO]') as t2 
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
		$s = "insert into embarquedemercancia
		set foliobitacora='$_GET[foliobitacora]',unidad = '$_GET[unidad]', tipo='$_GET[tipoembarque]', idsucursal=$_SESSION[IDSUCURSAL],
		ruta = '$_GET[ruta]', recorrido = '$_GET[recorrido]', fecha=current_date, 
		usuario='$_SESSION[NOMBREUSUARIO]', idusuario=$_SESSION[IDUSUARIO]";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		$idx = mysql_insert_id($l);
		
		$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		$s="insert into embarquedemercanciadetalle 
		SELECT NULL, '$idx', rg.guia AS guia, rg.origen, 
		CURRENT_DATE, rg.codigobarra, NULL 
		FROM embarques_tmp AS rg 
		WHERE rg.guia IN ($_GET[folios]) AND idusuario = $_SESSION[IDUSUARIO] and rg.subido = 'S' 
		GROUP BY rg.guia";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s="INSERT INTO embarquedemercanciapaquetes
		SELECT idunidad,$idx,sector,guia,tipoguia,origen,destino,
		codigobarra,registro,paquete,estado,peso 
		FROM embarques_tmp WHERE idusuario=$_SESSION[IDUSUARIO]
		AND subido = 'S'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		SELECT NULL, e.guia,'$_SESSION[IDSUCURSAL]','$_GET[unidad]',
		'EN TRANSITO', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'
		FROM embarques_tmp AS e 
		WHERE idusuario = '$_SESSION[IDUSUARIO]' and e.subido = 'S'
		GROUP BY guia";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiaventanilla_unidades
		INNER JOIN embarques_tmp ON guiaventanilla_unidades.idguia = embarques_tmp.guia
		AND guiaventanilla_unidades.paquete = embarques_tmp.registro
		SET guiaventanilla_unidades.proceso = 'EN TRANSITO', guiaventanilla_unidades.unidad='$_GET[unidad]'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'S'
		and idguia in ($_GET[folios])";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN embarques_tmp ON guiasempresariales_unidades.idguia = embarques_tmp.guia
		AND guiasempresariales_unidades.paquete = embarques_tmp.registro
		SET guiasempresariales_unidades.proceso = 'EN TRANSITO', guiasempresariales_unidades.unidad='$_GET[unidad]'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'S'
		and idguia in ($_GET[folios])";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "CREATE TEMPORARY TABLE paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN TRANSITO','EN TRANSITO INCOMPLETO') AS estado
		FROM guiasventanilla AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'EN TRANSITO' AND gv.id IN ($_GET[folios])
		GROUP BY gv.id";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "insert into paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN TRANSITO','EN TRANSITO INCOMPLETO') AS estado
		FROM guiasempresariales AS gv
		INNER JOIN guiasempresariales_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'EN TRANSITO' AND gv.id IN ($_GET[folios])
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
		
		echo "guardado";
		
		
	}
	
	if($_GET[accion]==10){
		$s = "SELECT em.unidad, cr.descripcion, cr.id, CONCAT(cs.prefijo,'-',LPAD(em.folio,5,'0')) AS folio, 
		cs.descripcion AS sucursal, DATE_FORMAT(em.fecha, '%d-%m-%Y') AS fecha 
		FROM embarquedemercancia AS em 
		INNER JOIN catalogoruta AS cr ON em.ruta = cr.id
		INNER JOIN catalogosucursal AS cs ON em.idsucursal = cs.id		
		WHERE folio = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$datosembarque = str_replace("null",'""',json_encode($f));
		
		$s = "DELETE FROM embarques_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]';";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO embarques_tmp
		SELECT idunidad, sector, guia, tipoguia, origen, destino, codigobarra, 
		registro, paquete, estado, 'S', peso, '$_SESSION[IDUSUARIO]'
		FROM embarquedemercanciapaquetes WHERE folioembarque = $_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'S' and idusuario = $_SESSION[IDUSUARIO]
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
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
		FROM embarquedemercancia";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$foliofecha = str_replace("null",'""',json_encode($f));
		
		echo "($foliofecha)";
	}
	
?>