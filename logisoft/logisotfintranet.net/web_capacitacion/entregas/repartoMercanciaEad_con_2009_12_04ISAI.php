<?
	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	if($_GET[accion]==1){
		$s = "select id, concat_ws(' ',nombre,apellidopaterno,apellidomaterno) as conductor
		from catalogoempleado where id = $_GET[idempleado] AND sucursal=$_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->conductor = cambio_texto($f->conductor);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
		}else{
				echo "0";
		}
	}
	
	if($_GET[accion]==2){
		$s = "delete from repartomercanciaead_temp where idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO repartomercanciaead_temp
		SELECT null, gv.id, cs.descripcion, gv.fecha, gvu.paquete, gvu.depaquetes, gvu.codigobarras, gvu.estado, gvu.peso, 'NO', '$_SESSION[IDUSUARIO]'
		from guiasventanilla as gv
		inner join catalogosucursal as cs on gv.idsucursalorigen = cs.id
		inner join guiaventanilla_unidades as gvu on gv.id = gvu.idguia and (gv.estado='ALMACEN DESTINO' or gv.estado='PARCIALMENTE EN REPARTO EAD')
			and gv.entradasalida = 'SALIDA' and gv.sector = '$_GET[sector]' and gvu.proceso='ALMACEN DESTINO'
		UNION
		SELECT null, ge.id, cs.descripcion, ge.fecha, geu.paquete, geu.depaquetes, geu.codigobarras, geu.estado, geu.peso, 'NO', '$_SESSION[IDUSUARIO]'
		from guiasempresariales as ge
		inner join catalogosucursal as cs on ge.idsucursalorigen = cs.id
		inner join guiasempresariales_unidades as geu on ge.id = geu.idguia and (ge.estado='ALMACEN DESTINO' or ge.estado='PARCIALMENTE EN REPARTO EAD')
			and ge.entradasalida = 'SALIDA' and ge.sector = '$_GET[sector]' and geu.proceso='ALMACEN DESTINO'";
		mysql_query($s,$l) or die($s);
		
		$s = "select rmeadt.folioguia as guia, rmeadt.origen,DATE_FORMAT(rmeadt.fecha,'%d/%m/%Y') AS fecha,rmeadt.codigodebarras as codigobarra
		from repartomercanciaead_temp as rmeadt
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
		FROM repartomercanciaead_temp WHERE folioguia='$_GET[folioguia]' and enunidad='NO' and idusuario = '$_SESSION[IDUSUARIO]'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			//$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	
	
	if($_GET[accion]==4){
		$s = "SELECT paquete as registro,concat(paquete,'-',depaquetes) as paquete,codigodebarras as codigobarra,estado, folioguia as guia
		FROM repartomercanciaead_temp WHERE folioguia='$_GET[folioguia]' and enunidad='SI' and idusuario = '$_SESSION[IDUSUARIO]'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==5){
		$s = "UPDATE repartomercanciaead_temp SET enunidad = 'SI' WHERE folioguia = '$_GET[folio]' and idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==6){
		$s = "UPDATE repartomercanciaead_temp SET enunidad = 'SI' WHERE folioguia = '$_GET[folio]' and paquete = $_GET[registro] and idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==7){
		$s = "UPDATE repartomercanciaead_temp SET enunidad = 'NO' WHERE folioguia = '$_GET[folio]' and idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==8){
		$s = "UPDATE repartomercanciaead_temp SET enunidad = 'NO' WHERE folioguia = '$_GET[folio]' and paquete = $_GET[registro] and idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	
	if($_GET[accion]==9){
		$s = "select t1.folioguia
		from
		(select folioguia, enunidad 
		from repartomercanciaead_temp
		where enunidad = 'SI' and idusuario = '$_SESSION[IDUSUARIO]') as t1
		inner join 
		(select folioguia, enunidad 
		from repartomercanciaead_temp
		where enunidad = 'NO' and idusuario = '$_SESSION[IDUSUARIO]') as t2 
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
		$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		$s = "INSERT INTO seguimiento_guias
		SELECT NULL, e.folioguia,'$_SESSION[IDSUCURSAL]','',
		'EN REPARTO EAD', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'
		FROM repartomercanciaead_temp AS e 
		WHERE idusuario = '$_SESSION[IDUSUARIO]' and e.folioguia in ($_GET[folios])
		GROUP BY folioguia";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into repartomercanciaead
		set id=$_GET[folio],unidad = $_GET[unidad], sector=$_GET[sector],
		conductor1 = $_GET[conductor1], conductor2 = $_GET[conductor2], fecha=current_date";
		mysql_query($s,$l) or die($s);
		
		$id = mysql_insert_id($l);
		
		
		$s="insert into repartomercanciadetalle 
			select null, '$id', rg.folioguia as guia, rg.origen,
			rg.fecha, rg.codigodebarras as codigobarra, null
			from repartomercanciaead_temp as rg
			where rg.folioguia in ($_GET[folios]) and idusuario = $_SESSION[IDUSUARIO]
			group by rg.folioguia";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into repartomercanciadetallepaquetes
		select '$id', repartomercanciaead_temp.folioguia, repartomercanciaead_temp.paquete
		from repartomercanciaead_temp
		where repartomercanciaead_temp.idusuario = $_SESSION[IDUSUARIO] AND repartomercanciaead_temp.enunidad = 'SI' ";
		mysql_query($s,$l) or die($s);
				
		$s = "UPDATE guiaventanilla_unidades
		INNER JOIN repartomercanciaead_temp ON guiaventanilla_unidades.idguia = repartomercanciaead_temp.folioguia
		AND guiaventanilla_unidades.paquete = repartomercanciaead_temp.paquete
		SET guiaventanilla_unidades.proceso = 'EN REPARTO EAD'
		WHERE repartomercanciaead_temp.idusuario = $_SESSION[IDUSUARIO] and repartomercanciaead_temp.enunidad = 'SI'
		and idguia in ($_GET[folios])";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN repartomercanciaead_temp ON guiasempresariales_unidades.idguia = repartomercanciaead_temp.folioguia
		AND guiasempresariales_unidades.paquete = repartomercanciaead_temp.paquete
		SET guiasempresariales_unidades.proceso = 'EN REPARTO EAD'
		WHERE repartomercanciaead_temp.idusuario = $_SESSION[IDUSUARIO] and repartomercanciaead_temp.enunidad = 'SI'
		and idguia in ($_GET[folios])";
		mysql_query($s,$l) or die($s);
		
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
	if($_GET[accion]==11){//Buscar Folio repartoMercanciaEad.php
		$s = "delete from repartomercanciaead_temp where idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($s,$l) or die($s);
		
		$principal = "";
		$s="SELECT rmead.id,DATE_FORMAT(rmead.fecha,'%d/%m/%Y')AS fecha,rmead.unidad,rmead.conductor1,rmead.conductor2,rmead.sector,
			CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS nombre1,
			CONCAT_WS(' ',ce2.nombre,ce2.apellidopaterno,ce2.apellidomaterno) AS nombre2
			FROM repartomercanciaead rmead 
			INNER JOIN catalogoempleado ce ON rmead.conductor1=ce.id
			INNER JOIN catalogoempleado ce2 ON rmead.conductor2=ce2.id
			WHERE rmead.id='$_GET[folio]'";
		$r=mysql_query($s,$l)or die($s."<BR>".mysql_error($l));
		$f=mysql_fetch_array($r);
		$id = $f[id];
		$principal = str_replace('null','""',json_encode($f));
	
		$detalle = "";
		$s="SELECT rmd.guia,rmd.origen,DATE_FORMAT(rmd.fecha,'%d/%m/%Y')AS fecha,rmd.codigobarras as codigobarra FROM repartomercanciadetalle AS rmd 
	WHERE rmd.idreparto='$id'";
			$r=mysql_query($s,$l)or die($s."<BR>".mysql_error($l));
			while($f = mysql_fetch_object($r)){
					$registros[] = $f;
					$w="INSERT INTO repartomercanciaead_temp
						SELECT NULL, gv.id, cs.descripcion, gv.fecha, gvu.paquete, gvu.depaquetes, gvu.codigobarras, gvu.estado, gvu.peso, 'NO', '$_SESSION[IDUSUARIO]'
						FROM guiasventanilla AS gv
						INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
						INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia 
						WHERE gv.id='$f->guia'
						UNION
						SELECT NULL, ge.id, cs.descripcion, ge.fecha, geu.paquete, geu.depaquetes, geu.codigobarras, geu.estado, geu.peso, 'NO', '$_SESSION[IDUSUARIO]'
						FROM guiasempresariales AS ge
						INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
						INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia 
						WHERE ge.id='$f->guia'";
						mysql_query($w,$l)or die($w."<BR>".mysql_error($l));	
			}
			$detalle = str_replace("null",'""',json_encode($registros));
			
			echo "({principal:$principal,detalle:$detalle})";
		
	
	}
	
	if($_GET[accion]==12){
		$row=folio("repartomercanciaead","webpmm");
		$s = "SELECT DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fecha, (SELECT descripcion FROM catalogosucursal where id=".$_SESSION[IDSUCURSAL].") as sucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		$folio = $row[0];
//		$resul = str_replace("null",'""',json_encode($f));
		echo $folio.",".$f->fecha.",".$f->sucursal;
	}
?>
