<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	if($_GET['accion']==1){
		
		$s = "create temporary table tmp_sucursales
		SELECT sucursal
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		WHERE catalogoruta.idtipounidad = '$_GET[unidad]'";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into tmp_sucursales
		SELECT idsucursal
		FROM catalogorutadetallesucursal
		INNER JOIN catalogoruta ON catalogorutadetallesucursal.idruta = catalogoruta.id
		WHERE catalogoruta.idtipounidad = '$_GET[unidad]'";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM embarques_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]';";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO embarques_tmp
		SELECT gvu.id, cse.descripcion, gvu.idguia, 'NORMAL', cs.descripcion, gvu.codigobarras, 
		gvu.paquete, CONCAT(gvu.paquete,' de ', gvu.depaquetes), gvu.estado, 'N', '$_SESSION[IDUSUARIO]'
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS cse ON gv.sector = cse.id
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia and isnull(gvu.proceso)
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN tmp_sucursales as tmps on gv.idsucursaldestino = tmps.sucursal
		WHERE gv.estado = 'ALMACEN ORIGEN' or gv.estado = 'PARCIALMENTE EN TRANSITO' 
		UNION
		SELECT geu.id, cse.descripcion, geu.idguia, 'EMPRESARIAL', cs.descripcion, geu.codigobarras, 
		geu.paquete, CONCAT(geu.paquete,' de ', geu.depaquetes), geu.estado, 'N', '$_SESSION[IDUSUARIO]'
		FROM guiasempresariales AS ge
		LEFT JOIN catalogosector AS cse ON ge.sector = cse.id
		INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia and isnull(geu.proceso)
		INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
		WHERE ge.estado = 'ALMACEN ORIGEN' or ge.estado = 'PARCIALMENTE EN TRANSITO';";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "SELECT sector, guia, origen, codigobarra
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
		$s = "SELECT sector, guia, origen, codigobarra
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
		$s = "SELECT registro,paquete,codigobarra,estado,guia 
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
		$s = "SELECT registro,paquete,codigobarra,estado,guia
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
		echo "guardado";
	}
	if($_GET[accion]==5){
		$s = "UPDATE embarques_tmp SET subido = 'S' WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==6){
		$s = "UPDATE embarques_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==7){
		$s = "UPDATE embarques_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		echo "guardado";
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
		set unidad = '$_GET[unidad]', tipo='$_GET[tipoembarque]', idsucursal=$_SESSION[IDSUCURSAL],
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
		
		$s = "UPDATE guiaventanilla_unidades
		INNER JOIN embarques_tmp ON guiaventanilla_unidades.idguia = embarques_tmp.guia
		AND guiaventanilla_unidades.paquete = embarques_tmp.registro
		SET guiaventanilla_unidades.proceso = 'EN TRANSITO'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'S'
		and idguia in ($_GET[folios])";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN embarques_tmp ON guiasempresariales_unidades.idguia = embarques_tmp.guia
		AND guiasempresariales_unidades.paquete = embarques_tmp.registro
		SET guiasempresariales_unidades.proceso = 'EN TRANSITO'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'S'
		and idguia in ($_GET[folios])";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "CREATE TEMPORARY TABLE paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN TRANSITO','PARCIALMENTE EN TRANSITO') AS estado
		FROM guiasventanilla AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'EN TRANSITO' AND gv.id IN ($_GET[folios])
		GROUP BY gv.id";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "insert into paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN TRANSITO','PARCIALMENTE EN TRANSITO') AS estado
		FROM guiasempresariales AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
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
?>