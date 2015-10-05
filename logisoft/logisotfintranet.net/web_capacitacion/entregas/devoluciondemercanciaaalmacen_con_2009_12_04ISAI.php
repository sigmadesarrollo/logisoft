<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "select repartomercanciadetalle.idreparto,
		concat_ws(' ',emp1.nombre,emp1.apellidopaterno,emp1.apellidomaterno) as conductorn1,
		concat_ws(' ',emp2.nombre,emp2.apellidopaterno,emp2.apellidomaterno) as conductorn2,
		catalogounidad.numeroeconomico
		from repartomercanciadetalle
		inner join repartomercanciaead on repartomercanciaead.id=repartomercanciadetalle.idreparto
		left join catalogoempleado as emp1 on emp1.id = repartomercanciaead.conductor1
		left join catalogoempleado as emp2 on emp2.id = repartomercanciaead.conductor2
		left join catalogounidad on catalogounidad.id = repartomercanciaead.unidad
		where repartomercanciadetalle.idreparto = $_GET[idreparto]
		and repartomercanciaead.liquidado = 0
		group by repartomercanciadetalle.idreparto";
		$r = mysql_query($s,$l) or die($s);
	
		$f = mysql_fetch_object($r);
		
		//echo str_replace("null",'""',json_encode($f));
		echo "(".str_replace("null",'""', json_encode($f)).")";
	}
	
	if($_GET[accion]==2){
		$s = "delete from devolucionmercancia_tmp where idusuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into devolucionmercancia_tmp
		select  gd.sector, gd.id as guia, cs.descripcion as origen,
			concat_ws(' ',cc.nombre, cc.paterno, cc.materno) as destinatario,
			if(gd.tipoflete=0,'PAGADO','POR COBRAR') as tipoflete,
			if(gd.condicionpago=0,'CONTADO','CREDITO') as condicionpago,
			gd.total as importe,gd.estado,
			'' as seleccion,rm.motivo, $_SESSION[IDUSUARIO]
			from repartomercanciadetalle as rm
			inner join guiasventanilla as gd on gd.id=rm.guia
			inner join catalogosucursal as cs on gd.idsucursalorigen = cs.id
			inner join catalogocliente as cc on gd.iddestinatario = cc.id
			where rm.idreparto=$_GET[folio]
		union
		select  gd.sector, gd.id as guia, cs.descripcion as origen,
			concat_ws(' ',cc.nombre, cc.paterno, cc.materno) as destinatario,
			gd.tipoflete,
			gd.tipopago as condicionpago,
			gd.total as importe,gd.estado,
			'' as seleccion,rm.motivo, $_SESSION[IDUSUARIO]
			from repartomercanciadetalle as rm
			inner join guiasempresariales as gd on gd.id=rm.guia 
			inner join catalogosucursal as cs on gd.idsucursalorigen = cs.id
			inner join catalogocliente as cc on gd.iddestinatario = cc.id
			where rm.idreparto=$_GET[folio]";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT cs.descripcion AS sector,dt.guia,dt.origen,dt.destinatario,dt.tipoflete,dt.condicionpago,dt.importe,dt.estado,dt.seleccion,dt.motivo FROM devolucionmercancia_tmp dt
INNER JOIN catalogosector cs ON dt.sector=cs.id  WHERE dt.idusuario=$_SESSION[IDUSUARIO] ORDER BY dt.guia";
		$r = mysql_query($s,$l) or die($s);
		$arreglo = array();
		while($f = mysql_fetch_object($r)){
			//$f->idreparto = cambio_texto($f->idreparto);
			$arreglo[] = $f;
		}
	//	echo str_replace("null",'""',json_encode($arreglo));
		echo "(".str_replace("null",'""', json_encode($arreglo)).")";
	}
	
	if($_GET[accion]==3){
		
		$cerrardevolucion=$_GET[cerrar];
		if ($cerrardevolucion==1){
			$concatenacion=',cerro=1';	
		}else{
			$concatenacion='';	
		}
	
		$s = "SELECT IFNULL(id,0)as folio FROM devolucionmercancia WHERE idreparto=".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$folio = $f->folio;
		if ($folio!=0){
		
			$s = "update devolucionmercancia set idreparto=$_GET[folio], 
			unidad='$_GET[unidad]', conductor1='$_GET[conductor1]', 
			conductor2='$_GET[conductor2]',
			entregadas='$_GET[entregadas]', devueltas='$_GET[devueltas]', 
			pagadas_credito='$_GET[pagcre]', pagadas_contado='$_GET[pagcon]',
			tpagadas_credito='$_GET[tpagcre]', tpagadas_contado='$_GET[tpagcon]', 
			porcobrar_contado='$_GET[porcobrarcont]',
			porcobrar_credito='$_GET[porcobrarcre]', tporcobrar_credito='$_GET[tporcobrarcre]', 
			tporcobrar_contado='$_GET[tporcobrarcont]', sucursal='$_SESSION[IDSUCURSAL]',
			fecha=current_date, usuario='$_SESSION[NOMBREUSUARIO]', idusuario = '$_SESSION[IDUSUARIO]' $concatenacion where id=$folio";
			mysql_query($s,$l) or die($s);
			$iddevolucion = $folio;
			$sql = "delete from devolucionmercancia_detalle where iddevolucion=$iddevolucion";
			mysql_query($sql,$l)or die($sql); 
		
		}else{
			/*$s = "insert into devolucionmercancia set id='$_GET[id]',idreparto='$_GET[folio]', 
			unidad='$_GET[unidad]', conductor1='$_GET[conductor1]', 
			conductor2='$_GET[conductor2]',
			entregadas='$_GET[entregadas]', devueltas='$_GET[devueltas]', 
			pagadas_credito='$_GET[pagcre]', pagadas_contado='$_GET[pagcon]',
			tpagadas_credito='$_GET[tpagcre]', tpagadas_contado='$_GET[tpagcon]', 
			porcobrar_contado='$_GET[porcobrarcont]',
			porcobrar_credito='$_GET[porcobrarcre]', tporcobrar_credito='$_GET[tporcobrarcre]', 
			tporcobrar_contado='$_GET[tporcobrarcont]', sucursal='$_SESSION[IDSUCURSAL]',
			fecha=current_date, usuario='$_SESSION[NOMBREUSUARIO]', idusuario = '$_SESSION[IDUSUARIO]' $concatenacion";*/
			
			$s = "insert into devolucionmercancia set idreparto=$_GET[folio], 
			unidad='$_GET[unidad]', conductor1='$_GET[conductor1]', 
			conductor2='$_GET[conductor2]',
			entregadas='$_GET[entregadas]', devueltas='$_GET[devueltas]', 
			pagadas_credito='$_GET[pagcre]', pagadas_contado='$_GET[pagcon]',
			tpagadas_credito='$_GET[tpagcre]', tpagadas_contado='$_GET[tpagcon]', 
			porcobrar_contado='$_GET[porcobrarcont]',
			porcobrar_credito='$_GET[porcobrarcre]', tporcobrar_credito='$_GET[tporcobrarcre]', 
			tporcobrar_contado='$_GET[tporcobrarcont]', sucursal='$_SESSION[IDSUCURSAL]',
			fecha=current_date, usuario='$_SESSION[NOMBREUSUARIO]', idusuario = '$_SESSION[IDUSUARIO]' $concatenacion";
			mysql_query($s,$l) or die($s);
			$iddevolucion = mysql_insert_id($l);
		}
		
		if($_GET[folios]!=""){
			$folios = split(",",$_GET[folios]);
			foreach($folios as $foliog){
				$motivod = split("#",$foliog);
				$s = "insert into devolucionmercancia_detalle
				set iddevolucion = $iddevolucion, folioguia='$motivod[0]',
				devuelto='S', motivo = '$motivod[1]'";
				mysql_query($s,$l) or die($s);
			}
		}
		if($_GET[folios2]!=""){
			$folios2 = split(",",$_GET[folios2]);
			foreach($folios2 as $foliog2){
				$s = "insert into devolucionmercancia_detalle
				set iddevolucion = $iddevolucion, folioguia='$foliog2',
				devuelto='N'";
				mysql_query($s,$l) or die($s);
			}
		}
		
		$cadenas_folios2 = "'".str_replace(",","','",$_GET[folios])."'";//Devolver
		$cadenas_folios = "'".str_replace(",","','",$_GET[folios2])."'";//Entregar
		//echo $cadenas_folios2;
		
		$s = "UPDATE guiasventanilla SET fechaentrega = CURRENT_DATE WHERE id in($cadenas_folios)";
		mysql_query($s,$l) or die($s);

		$s = "UPDATE guiasempresariales SET fechaentrega = CURRENT_DATE WHERE id in($cadenas_folios)";
		mysql_query($s,$l) or die($s);
		
	//mercancia entregada
		$s = "update guiaventanilla_unidades as gvu
		inner join repartomercanciadetallepaquetes as rmp on gvu.idguia = rmp.folioguia
		and gvu.paquete = rmp.paquete
		set gvu.proceso = 'ENTREGADA'
		where rmp.idreparto = $_GET[folio] and gvu.idguia in($cadenas_folios)";
		//echo "<br>$s<br>";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		SELECT NULL, r.guia,'$_SESSION[IDSUCURSAL]','',
		'ENTREGADA', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'
		FROM guiasventanilla AS r 
		WHERE r.id in($cadenas_folios)";
		mysql_query($s,$l) or die($s);
		
		$s = "update guiasempresariales_unidades as gvu
		inner join repartomercanciadetallepaquetes as rmp on gvu.idguia = rmp.folioguia
		and gvu.paquete = rmp.paquete
		set gvu.proceso = 'ENTREGADA'
		where rmp.idreparto = $_GET[folio] and gvu.idguia in($cadenas_folios)";
		//echo "<br>$s<br>";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		SELECT NULL, r.id,'$_SESSION[IDSUCURSAL]','',
		'ENTREGADA', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'
		FROM guiasempresariales AS r 
		WHERE r.id IN($cadenas_folios)";
		mysql_query($s,$l) or die($s);
		
	//devolver paquetes a almacen
		$s = "update guiaventanilla_unidades as gvu
		inner join repartomercanciadetallepaquetes as rmp on gvu.idguia = rmp.folioguia
		and gvu.paquete = rmp.paquete
		set gvu.proceso = 'ALMACEN DESTINO'
		where rmp.idreparto = $_GET[folio] and gvu.idguia in($cadenas_folios2)";
		//echo "<br>$s<br>";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		SELECT NULL, r.guia,'$_SESSION[IDSUCURSAL]','',
		'DEVUELTA A SUCURSAL', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'
		FROM guiasventanilla AS r 
		WHERE r.id in($cadenas_folios2)";
		mysql_query($s,$l) or die($s);
		
		$s = "update guiasempresariales_unidades as gvu
		inner join repartomercanciadetallepaquetes as rmp on gvu.idguia = rmp.folioguia
		and gvu.paquete = rmp.paquete
		set gvu.proceso = 'ALMACEN DESTINO'
		where rmp.idreparto = $_GET[folio] and gvu.idguia in($cadenas_folios2)";
		//echo "<br>$s<br>";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		SELECT NULL, r.id,'$_SESSION[IDSUCURSAL]','',
		'DEVUELTA A SUCURSAL', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'
		FROM guiasempresariales AS r 
		WHERE r.id IN($cadenas_folios2)";
		mysql_query($s,$l) or die($s);
		
		
		//guias ENTREGADAS
		$s = "CREATE TEMPORARY TABLE paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'ENTREGADA',gv.estado) AS estado
		FROM guiasventanilla AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'ENTREGADA' AND gv.id IN ($cadenas_folios)
		GROUP BY gv.id";
		//echo "<br>$s<br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "insert into paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'ENTREGADA',gv.estado) AS estado
		FROM guiasempresariales AS gv
		INNER JOIN guiasempresariales_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'ENTREGADA' AND gv.id IN ($cadenas_folios)
		GROUP BY gv.id";
		//echo "<br>$s<br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		//actualizar las entregadas
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
		
		//actualizar las devueltas
		$s = "UPDATE guiasventanilla set estado='ALMACEN DESTINO' where id in($cadenas_folios2)";
			mysql_query($s,$l) or die($s);
		$s = "UPDATE guiasempresariales set estado='ALMACEN DESTINO' where id in($cadenas_folios2)";
			mysql_query($s,$l) or die($s);
		
		echo "guardado,".$iddevolucion;
	}
	
	if($_GET[accion]==4){
		
		$s = "SELECT dv.*,cs.descripcion as nsucursal
		FROM devolucionmercancia as dv
		inner join catalogosucursal AS cs ON dv.sucursal = cs.id
		WHERE dv.id = $_GET[folio]";
		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$dprinci = str_replace("null",'""',json_encode($f));
		
		$s = "
		SELECT sector,guia,origen,destinatario,tipoflete,condicionpago,importe,estado,motivo,seleccion from 
		(
		SELECT cs.descripcion AS sector, gv.id AS guia, csu.descripcion AS origen,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destinatario,
		gv.tipoflete, gv.tipopago AS condicionpago,
		gv.total AS importe, gv.estado, dmd.motivo, IF(dmd.devuelto='S','X','') AS seleccion
		FROM devolucionmercancia_detalle AS dmd
		INNER JOIN guiasempresariales AS gv ON dmd.folioguia = gv.id
		INNER JOIN catalogosector AS cs ON gv.sector = cs.id
		INNER JOIN catalogosucursal AS csu ON gv.idsucursalorigen = csu.id
		INNER JOIN catalogocliente AS cc ON gv.iddestinatario = cc.id
		WHERE iddevolucion = $_GET[folio]
		UNION
		SELECT cs.descripcion AS sector, gv.id AS guia, csu.descripcion AS origen,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destinatario,
		IF(gv.tipoflete = 0, 'PAGADO', 'POR COBRAR') AS tipoflete,
		IF(gv.condicionpago = 0,'CONTADO','CREDITO') AS condicionpago,
		gv.total AS importe, gv.estado, dmd.motivo, IF(dmd.devuelto='S','X','') AS seleccion
		FROM devolucionmercancia_detalle AS dmd
		INNER JOIN guiasventanilla AS gv ON dmd.folioguia = gv.id
		INNER JOIN catalogosector AS cs ON gv.sector = cs.id
		INNER JOIN catalogosucursal AS csu ON gv.idsucursalorigen = csu.id
		INNER JOIN catalogocliente AS cc ON gv.iddestinatario = cc.id
		WHERE iddevolucion = $_GET[folio]
		)Tabla order by guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destinatario = cambio_texto($f->destinatario);
			$arre[] = $f;
		}
		
		$resuldetalle = str_replace("null",'""',json_encode($arre));
		echo "({
			   principal:$dprinci,
			   detalle:$resuldetalle
		})";
	}
	
	if($_GET[accion]==5){
		$row=folio("devolucionmercancia","webpmm");
		$s = "SELECT DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fecha, (SELECT descripcion FROM catalogosucursal where id=".$_SESSION[IDSUCURSAL].") as sucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		$folio = $row[0];
//		$resul = str_replace("null",'""',json_encode($f));
		echo $folio.",".$f->fecha.",".$f->sucursal;
	}
	
	if($_GET[accion]==6){
		$s = "SELECT id,idreparto,cerro,date_format(fecha,'%d/%m/%Y')as fecha FROM devolucionmercancia WHERE idreparto=".$_GET[folio]."";
		$r=mysql_query($s,$l)or die($s); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}
	
	if($_GET[accion]==7){
		$s = "UPDATE devolucionmercancia SET cerro=1 where idreparto=".$_GET[folio]."";
		mysql_query($s,$l)or die($s); 
		
		echo "ok";
	
	}
?>
