<?
	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	if($_GET[accion]==1){
		$s = "select id, concat_ws(' ',nombre,apellidopaterno,apellidomaterno) as conductor
		from catalogoempleado where id = $_GET[idempleado] /*AND sucursal=$_SESSION[IDSUCURSAL]*/ AND puesto between 47 and 48";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->conductor = cambio_texto($f->conductor);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
		}else{
				echo "noencontrado";
		}
	}
	
	if($_GET[accion]==2){
		$s = "delete from repartomercanciaead_temp where idusuario = '$_SESSION[IDUSUARIO]'
		AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		if($_GET[sector]=="0"){
			$filtro1 = "/*and gv.sector not in (null)*/
						and gv.idsucursaldestino = $_SESSION[IDSUCURSAL]";
			//$filtro1 = "and gv.sector not in (select id from catalogosector WHERE idsucursal = $_SESSION[IDSUCURSAL]) 
			//			and gv.idsucursaldestino = $_SESSION[IDSUCURSAL]";
			$filtro2 = "/*and ge.sector not in (select id from catalogosector WHERE idsucursal = $_SESSION[IDSUCURSAL])*/
						and ge.idsucursaldestino = $_SESSION[IDSUCURSAL]";
		}else{
			$filtro1 = "and gv.sector = '$_GET[sector]' and gv.idsucursaldestino = $_SESSION[IDSUCURSAL]";
			$filtro2 = "and ge.sector = '$_GET[sector]' and ge.idsucursaldestino = $_SESSION[IDSUCURSAL] ";
		}
		
		$s = "INSERT INTO repartomercanciaead_temp
		SELECT null, gv.id, cs.descripcion, gv.fecha, gvu.paquete, gvu.depaquetes, 
		gvu.codigobarras, gvu.estado, gvu.peso, 'NO', '$_SESSION[IDUSUARIO]', '$_SESSION[IDSUCURSAL]'
		from guiasventanilla as gv
		inner join catalogosucursal as cs on gv.idsucursalorigen = cs.id
		inner join guiaventanilla_unidades as gvu on gv.id = gvu.idguia and (gv.estado='ALMACEN DESTINO' or gv.estado='PARCIALMENTE EN REPARTO EAD')
			and gv.entradasalida = 'SALIDA' AND gvu.proceso <> 'POR RECIBIR'
			$filtro1 
			and (gvu.proceso='ALMACEN DESTINO' or isnull(gvu.proceso))
		UNION
		SELECT null, ge.id, cs.descripcion, ge.fecha, geu.paquete, geu.depaquetes, 
		geu.codigobarras, geu.estado, geu.peso, 'NO', '$_SESSION[IDUSUARIO]', '$_SESSION[IDSUCURSAL]'
		from guiasempresariales as ge
		inner join catalogosucursal as cs on ge.idsucursalorigen = cs.id
		inner join guiasempresariales_unidades as geu on ge.id = geu.idguia and (ge.estado='ALMACEN DESTINO' or ge.estado='PARCIALMENTE EN REPARTO EAD')
			and ge.entradasalida = 'SALIDA' AND geu.proceso <> 'POR RECIBIR'
			$filtro2 
			and (geu.proceso='ALMACEN DESTINO' or isnull(geu.proceso))";
		mysql_query($s,$l) or die($s);
		
		$s = "select t2.* from (
			select t.*, ifnull(ead.cerro,1) as cerro from (
				select rmeadt.folioguia as guia, rmeadt.origen,DATE_FORMAT(rmeadt.fecha,'%d/%m/%Y') AS fecha,
				rmeadt.codigodebarras as codigobarra
				from repartomercanciaead_temp as rmeadt
				where rmeadt.idusuario = '$_SESSION[IDUSUARIO]' and rmeadt.sucursal = '$_SESSION[IDSUCURSAL]'
				and rmeadt.enunidad = 'NO'
				group by rmeadt.folioguia
			) as t
			left join liquidacion_detalleead as lead on t.guia = lead.guia
			left join liquidacionead as ead on lead.idliquidacion = ead.id and ead.cerro = 0
		) as t2 where t2.cerro = 1";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		echo str_replace("null",'""', json_encode($arre));
	}
	
	
	if($_GET[accion]==3){
		$s = "SELECT paquete as registro,concat(paquete,'-',depaquetes) as paquete,
		codigodebarras as codigobarra,estado, folioguia as guia
		FROM repartomercanciaead_temp 
		WHERE folioguia='$_GET[folioguia]' and enunidad='NO' 
		and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			//$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	
	
	if($_GET[accion]==4){
		$s = "SELECT paquete as registro,concat(paquete,'-',depaquetes) as paquete,
		codigodebarras as codigobarra,estado, folioguia as guia
		FROM repartomercanciaead_temp WHERE folioguia='$_GET[folioguia]' and enunidad='SI' 
		and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	
	if($_GET[accion]==5){
		$s = "UPDATE repartomercanciaead_temp SET enunidad = 'SI' WHERE folioguia = '$_GET[folio]' 
		and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	
	if($_GET[accion]==6){
		$s = "UPDATE repartomercanciaead_temp SET enunidad = 'SI' WHERE folioguia = '$_GET[folio]' 
		and paquete = $_GET[registro] and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	
	if($_GET[accion]==7){
		$s = "UPDATE repartomercanciaead_temp SET enunidad = 'NO' WHERE folioguia = '$_GET[folio]' 
		and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	
	if($_GET[accion]==8){
		$s = "UPDATE repartomercanciaead_temp SET enunidad = 'NO' WHERE folioguia = '$_GET[folio]'
		and paquete = $_GET[registro] and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	
	if($_GET[accion]==9){
		$s = "select t1.folioguia
		from
		(select folioguia, enunidad 
		from repartomercanciaead_temp
		where enunidad = 'SI' and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL].") as t1
		inner join 
		(select folioguia, enunidad 
		from repartomercanciaead_temp
		where enunidad = 'NO' and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL].") as t2 
		on t1.folioguia = t2.folioguia
		group by t1.folioguia";
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
	
	if($_GET[accion]==10){		
		
		$s = "CREATE TEMPORARY TABLE tmp_seleccionadas (
			`folioguia` VARCHAR(25) COLLATE utf8_unicode_ci DEFAULT NULL
		);";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT numeroeconomico FROM catalogounidad WHERE id = $_GET[unidad]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$numeroeconomico = $f->numeroeconomico;
		
		$s = "INSERT INTO tmp_seleccionadas
		SELECT folioguia FROM repartomercanciaead_temp
			WHERE idusuario = '$_SESSION[IDUSUARIO]' AND sucursal = '$_SESSION[IDSUCURSAL]'
			AND enunidad = 'SI'
			GROUP BY folioguia";
		mysql_query($s,$l) or die($s);
	
		//$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		#validacion para que no se repitan las guias****************
		
		$guiasYaGuardadas = "";
		$s = "SELECT rd.guia, ld.id
		FROM repartomercanciadetalle  rd
		inner join tmp_seleccionadas tp on rd.guia = tp.folioguia
		LEFT JOIN liquidacionead ld ON rd.idreparto = rd.id
		WHERE rd.sucursal = '$_SESSION[IDSUCURSAL]'
		AND not isnull(ld.id);";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$guiasYaGuardadas .= (($guiasYaGuardadas!="")?",":"").$f->guia;
		}
		if($guiasYaGuardadas!=""){
			//die("Las siguientes guias: $guiasYaGuardadas ya fueron asignadas a un reparto");
		}
		#***********************************************************
		
		/* guardar el seguimiento de ventanilla y empresarial */
		$s = "INSERT INTO seguimiento_guias
		(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
		SELECT e.folioguia,'$_SESSION[IDSUCURSAL]',
		IFNULL((SELECT cu.numeroeconomico FROM catalogounidad cu WHERE id = '$_GET[unidad]'),0),
		CONCAT('EN REPARTO EAD M ',IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), 
		CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','',''
		FROM repartomercanciaead_temp AS e
		inner join tmp_seleccionadas tp on e.folioguia = tp.folioguia
		INNER JOIN guiasventanilla g ON e.folioguia = g.id
		WHERE e.idusuario = '$_SESSION[IDUSUARIO]' AND e.sucursal=".$_SESSION[IDSUCURSAL]."
		GROUP BY e.folioguia";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
		SELECT e.folioguia,'$_SESSION[IDSUCURSAL]',
		IFNULL((SELECT cu.numeroeconomico FROM catalogounidad cu WHERE id = '$_GET[unidad]'),0),
		CONCAT('EN REPARTO EAD M ',IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), 
		CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','',''
		FROM repartomercanciaead_temp AS e 
		INNER JOIN tmp_seleccionadas tp ON e.folioguia = tp.folioguia
		INNER JOIN guiasempresariales g ON e.folioguia = g.id
		WHERE e.idusuario = '$_SESSION[IDUSUARIO]' AND e.sucursal=".$_SESSION[IDSUCURSAL]."
		GROUP BY e.folioguia";
		mysql_query($s,$l) or die($s);
		/*******************************************************/
		
		$s = "insert into repartomercanciaead set 
		folio = obtenerFolio('repartomercanciaead',".$_SESSION[IDSUCURSAL]."),unidad = $_GET[unidad], sector=$_GET[sector],
		conductor1 = $_GET[conductor1], conductor2 = $_GET[conductor2], fecha=current_date, usuario=".$_SESSION[IDUSUARIO].",
		sucursal=$_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s);		
		$id = mysql_insert_id($l);
		
		#para ingresar el movimiento de ventas de guias a ventas contra presupuesto
		$s = "call proc_VentasVsPresupuesto('OP REPARTO','$id',$_SESSION[IDSUCURSAL]);";
		$r = mysql_query($s,$l) or die("$s");
		
		$s = "SELECT folio FROM repartomercanciaead WHERE id = ".$id;
		$r = mysql_query($s,$l) or die($s); $fid = mysql_fetch_object($r);
		
		$s="insert into repartomercanciadetalle 
			select 0 as id, '$fid->folio', rg.folioguia as guia, rg.origen,
			rg.fecha, rg.codigodebarras as codigobarra, null,sucursal
			from repartomercanciaead_temp as rg
			INNER JOIN tmp_seleccionadas tp ON rg.folioguia = tp.folioguia
			where idusuario = $_SESSION[IDUSUARIO]
			group by rg.folioguia";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into repartomercanciadetallepaquetes
		select '$fid->folio', repartomercanciaead_temp.folioguia, repartomercanciaead_temp.paquete,
		repartomercanciaead_temp.sucursal
		from repartomercanciaead_temp
		where repartomercanciaead_temp.idusuario = $_SESSION[IDUSUARIO] AND repartomercanciaead_temp.enunidad = 'SI' ";
		mysql_query($s,$l) or die($s);
				
		$s = "UPDATE guiaventanilla_unidades
		INNER JOIN repartomercanciaead_temp ON guiaventanilla_unidades.idguia = repartomercanciaead_temp.folioguia
		AND guiaventanilla_unidades.paquete = repartomercanciaead_temp.paquete
		INNER JOIN tmp_seleccionadas tp ON guiaventanilla_unidades.idguia = tp.folioguia
		SET guiaventanilla_unidades.proceso = 'EN REPARTO EAD', guiaventanilla_unidades.unidad = '$numeroeconomico'
		WHERE repartomercanciaead_temp.idusuario = $_SESSION[IDUSUARIO] 
		and repartomercanciaead_temp.enunidad = 'SI'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN repartomercanciaead_temp ON guiasempresariales_unidades.idguia = repartomercanciaead_temp.folioguia
		AND guiasempresariales_unidades.paquete = repartomercanciaead_temp.paquete
		INNER JOIN tmp_seleccionadas tp ON guiasempresariales_unidades.idguia = tp.folioguia
		SET guiasempresariales_unidades.proceso = 'EN REPARTO EAD', guiasempresariales_unidades.unidad = '$numeroeconomico'
		WHERE repartomercanciaead_temp.idusuario = $_SESSION[IDUSUARIO] and repartomercanciaead_temp.enunidad = 'SI'";
		mysql_query($s,$l) or die($s);
		
		$s = "CREATE TEMPORARY TABLE paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN REPARTO EAD','PARCIALMENTE EN REPARTO EAD') AS estado
		FROM guiasventanilla AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN tmp_seleccionadas tp ON gv.id = tp.folioguia
		AND gvu.proceso = 'EN REPARTO EAD'
		GROUP BY gv.id";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "insert into paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN REPARTO EAD','PARCIALMENTE EN REPARTO EAD') AS estado
		FROM guiasempresariales AS gv
		INNER JOIN guiasempresariales_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN tmp_seleccionadas tp ON gv.id = tp.folioguia
		AND gvu.proceso = 'EN REPARTO EAD'
		GROUP BY gv.id";
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
		
		echo "guardado,".$fid->folio;
	}
	
	if($_GET[accion]==11){//Buscar Folio repartoMercanciaEad.php
		$s = "delete from repartomercanciaead_temp where idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		
		$principal = "";
		$s="SELECT rmead.folio as id,DATE_FORMAT(rmead.fecha,'%d/%m/%Y')AS fecha,rmead.unidad,rmead.conductor1,rmead.conductor2,rmead.sector,
			CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS nombre1,
			CONCAT_WS(' ',ce2.nombre,ce2.apellidopaterno,ce2.apellidomaterno) AS nombre2
			FROM repartomercanciaead rmead 
			INNER JOIN catalogoempleado ce ON rmead.conductor1=ce.id
			INNER JOIN catalogoempleado ce2 ON rmead.conductor2=ce2.id
			WHERE rmead.folio='$_GET[folio]' AND rmead.sucursal = $_SESSION[IDSUCURSAL]";
		$r=mysql_query($s,$l)or die($s."<BR>".mysql_error($l));
		$f=mysql_fetch_array($r);
		$id = $f[id];
		$principal = str_replace('null','""',json_encode($f));
	
		$detalle = "";
		$s="SELECT rmd.guia,rmd.origen,DATE_FORMAT(rmd.fecha,'%d/%m/%Y')AS fecha,rmd.codigobarras as codigobarra FROM repartomercanciadetalle AS rmd 
	WHERE rmd.idreparto='$_GET[folio]' and rmd.sucursal = $_SESSION[IDSUCURSAL]";
			$r=mysql_query($s,$l)or die($s."<BR>".mysql_error($l));
			while($f = mysql_fetch_object($r)){
					$registros[] = $f;
					$w="INSERT INTO repartomercanciaead_temp
						SELECT NULL, gv.id, cs.descripcion, gv.fecha, gvu.paquete, gvu.depaquetes, gvu.codigobarras, gvu.estado, gvu.peso, 'SI', '$_SESSION[IDUSUARIO]','$_SESSION[IDSUCURSAL]'
						FROM guiasventanilla AS gv
						INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
						INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia 
						WHERE gv.id='$f->guia' AND gvu.proceso <> 'POR RECIBIR'
						UNION
						SELECT NULL, ge.id, cs.descripcion, ge.fecha, geu.paquete, geu.depaquetes, geu.codigobarras, geu.estado, geu.peso, 'SI', '$_SESSION[IDUSUARIO]','$_SESSION[IDSUCURSAL]'
						FROM guiasempresariales AS ge
						INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
						INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia 
						WHERE ge.id='$f->guia' AND geu.proceso <> 'POR RECIBIR'";
						mysql_query($w,$l)or die($w."<BR>".mysql_error($l));	
			}
			$detalle = str_replace("null",'""',json_encode($registros));
			
			echo "({principal:$principal,detalle:$detalle})";
	}
	
	if($_GET[accion]==12){
		$s = "SELECT obtenerFolio('repartomercanciaead',".$_SESSION[IDSUCURSAL].") as folio";
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
		
		$s = "SELECT DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fecha,
		(SELECT descripcion FROM catalogosucursal where id=".$_SESSION[IDSUCURSAL].") as sucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		echo $fo->folio.",".$f->fecha.",".$f->sucursal;
	}
?>

