<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');	
	
	if($_GET[accion]==1){
		$s = "select * from catalogounidad where id = $_GET[idunidad] and fueradeservicio=0";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->conductor = cambio_texto($f->conductor);
		echo "(".str_replace("null",'""', json_encode($f)).")";
	}
	
	if($_GET[accion]==2){
		$s = "delete from asignacionmanual_temp where idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO asignacionmanual_temp
		SELECT null, gv.id, cs.descripcion, gv.fecha, gvu.paquete, gvu.depaquetes, gvu.codigobarras, gvu.estado, gvu.peso, 'NO', $_SESSION[IDUSUARIO]
		from guiasventanilla as gv
		inner join catalogosucursal as cs on gv.idsucursalorigen = cs.id
		inner join guiaventanilla_unidades as gvu on gv.id = gvu.idguia and (gv.estado='ALMACEN DESTINO' or gv.estado='PARCIALMENTE EN REPARTO EAD')
			and gv.entradasalida = 'SALIDA' and gvu.proceso='ALMACEN DESTINO'
		UNION
		SELECT null, ge.id, cs.descripcion, ge.fecha, geu.paquete, geu.depaquetes, geu.codigobarras, geu.estado, geu.peso, 'NO', $_SESSION[IDUSUARIO]
		from guiasempresariales as ge
		inner join catalogosucursal as cs on ge.idsucursalorigen = cs.id
		inner join guiasempresariales_unidades as geu on ge.id = geu.idguia and (ge.estado='ALMACEN DESTINO' or ge.estado='PARCIALMENTE EN REPARTO EAD')
			and ge.entradasalida = 'SALIDA' and geu.proceso='ALMACEN DESTINO'";
		mysql_query($s,$l) or die($s);
		
		$s = "select rmeadt.folioguia as guia, rmeadt.origen, DATE_FORMAT(rmeadt.fecha,'%d%m%Y') AS fecha,rmeadt.codigodebarras as codigobarra
		from asignacionmanual_temp as rmeadt
		where idusuario = '$_SESSION[IDUSUARIO]' and rmeadt.enunidad = 'NO'
		group by rmeadt.folioguia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		echo str_replace("null",'""', json_encode($arre));
	}
	
	if($_GET[accion]==3){
		$s = "SELECT paquete as registro,concat(paquete,'-',depaquetes) as paquete,codigodebarras as codigobarra,estado, folioguia as guia
		FROM asignacionmanual_temp WHERE folioguia='$_GET[folioguia]' and enunidad='NO' and idusuario = '$_SESSION[IDUSUARIO]'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==4){
		$s = "SELECT paquete as registro,concat(paquete,'-',depaquetes) as paquete,codigodebarras as codigobarra,estado, folioguia as guia
		FROM asignacionmanual_temp WHERE folioguia='$_GET[folioguia]' and enunidad='SI' and idusuario = '$_SESSION[IDUSUARIO]'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==5){
		$s = "UPDATE asignacionmanual_temp SET enunidad = 'SI' WHERE folioguia = '$_GET[folio]' and idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==6){
		$s = "UPDATE asignacionmanual_temp SET enunidad = 'SI' WHERE folioguia = '$_GET[folio]' and paquete = $_GET[registro] and idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==7){
		$s = "UPDATE asignacionmanual_temp SET enunidad = 'NO' WHERE folioguia = '$_GET[folio]' and idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==8){
		$s = "UPDATE asignacionmanual_temp SET enunidad = 'NO' WHERE folioguia = '$_GET[folio]' and paquete = $_GET[registro] and idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	
	if($_GET[accion]==9){
		$s = "select t1.folioguia
		from
		(select folioguia, enunidad 
		from asignacionmanual_temp
		where enunidad = 'SI' and idusuario = '$_SESSION[IDUSUARIO]') as t1
		inner join 
		(select folioguia, enunidad 
		from asignacionmanual_temp
		where enunidad = 'NO' and idusuario = '$_SESSION[IDUSUARIO]') as t2 
		on t1.folioguia = t2.folioguia
		group by t1.folioguia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		echo str_replace("null",'""', json_encode($arre));
	}
	if($_GET[accion]==10){
		$s = "insert into repartomercanciaead
		set unidad = $_GET[unidad], tipo='MANUAL', fecha=current_date";
		mysql_query($s,$l) or die($s);
		
		$id = mysql_insert_id($l);
		
		$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		$s="insert into repartomercanciadetalle 
			select null, '$id', rg.folioguia as guia, rg.origen,
			rg.fecha, rg.codigodebarras as codigobarra, null
			from asignacionmanual_temp as rg
			where rg.folioguia in ($_GET[folios]) and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into repartomercanciadetallepaquetes
		select '$id', asignacionmanual_temp.folioguia, asignacionmanual_temp.paquete
		from asignacionmanual_temp
		where asignacionmanual_temp.idusuario = $_SESSION[IDUSUARIO] AND asignacionmanual_temp.enunidad = 'SI' ";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiaventanilla_unidades
		INNER JOIN asignacionmanual_temp ON guiaventanilla_unidades.idguia = asignacionmanual_temp.folioguia
		AND guiaventanilla_unidades.paquete = asignacionmanual_temp.paquete
		SET guiaventanilla_unidades.proceso = 'EN REPARTO EAD'
		WHERE asignacionmanual_temp.idusuario = $_SESSION[IDUSUARIO] and asignacionmanual_temp.enunidad = 'SI'
		and idguia in ($_GET[folios])";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN asignacionmanual_temp ON guiasempresariales_unidades.idguia = asignacionmanual_temp.folioguia
		AND guiasempresariales_unidades.paquete = asignacionmanual_temp.paquete
		SET guiasempresariales_unidades.proceso = 'EN REPARTO EAD'
		WHERE asignacionmanual_temp.idusuario = $_SESSION[IDUSUARIO] and asignacionmanual_temp.enunidad = 'SI'
		and idguia in ($_GET[folios])";
		mysql_query($s,$l) or die($s);
		
		/**/
		$s = "CREATE TEMPORARY TABLE paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN REPARTO EAD','PARCIALMENTE EN REPARTO EAD') AS estado
		FROM guiasventanilla AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'EN REPARTO EAD' AND gv.id IN ($_GET[folios])
		GROUP BY gv.id";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "insert into paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN REPARTO EAD','PARCIALMENTE EN REPARTO EAD') AS estado
		FROM guiasempresariales AS gv
		INNER JOIN guiasempresariales_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'EN REPARTO EAD' AND gv.id IN ($_GET[folios])
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
	
	if($_GET[accion]==11){
		$s = "select repartomercanciaead.id, catalogounidad.numeroeconomico
		from repartomercanciaead
		inner join catalogounidad on repartomercanciaead.unidad = catalogounidad.id
		where repartomercanciaead.id = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->numeroeconomico = cambio_texto($f->numeroeconomico);
		$datosreparto = str_replace("",'""',json_encode($f));
		
		$s = "select * repartomercanciadetalle where idreparto = $_GET[folio]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		$datostabla = str_replace("null",'""', json_encode($arre));
		echo "{
			datosreparto:$datosreparto,
			datostabla:$datostabla
		}";
	}
?>


