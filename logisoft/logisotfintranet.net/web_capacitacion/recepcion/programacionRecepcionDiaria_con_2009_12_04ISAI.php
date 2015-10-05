<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET[accion]==1){//OBTENER UNIDAD
		$s = "SELECT * FROM catalogounidad 
		WHERE numeroeconomico='".$_GET[unidad]."'";
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){			
			echo str_replace("null",'""',json_encode(1));
		}else{
			echo str_replace("null",'""',json_encode(0));
		}
		
	}else if($_GET[accion]==2){//DATOS GENERALES
		$s = "SELECT DATE_FORMAT(CURRENT_DATE , '%d/%m/%Y') AS fecha,
		(SELECT descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal].") AS sucursal";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		$registros = array();
		while($f=mysql_fetch_object($r)){
			$registros[] = $f;
		}
		echo str_replace("null",'""',json_encode($registros));
		
	}else if($_GET[accion]==3){//OBTENER HORA LLEGADA ----- HORA SALIDA
			$s = "SELECT TIME_FORMAT(CURRENT_TIME,'%H:%i:%s') as hora";
			$r = mysql_query($s, $l) or die($s);
			$registros = array();
			while($f=mysql_fetch_object($r)){
				$f->tipo = $_GET[tipo];
				$registros[] = $f;
			}
			echo str_replace("null",'""',json_encode($registros));		
	}else if($_GET[accion]==4){//OBTENER RUTA
		$s = "SELECT cr.descripcion AS ruta FROM bitacorasalida bs
		INNER JOIN catalogoruta cr ON bs.ruta = cr.id
		WHERE bs.unidad='".$_GET[unidad]."'";
		//echo $s;
		$r = mysql_query($s, $l) or die($s);
		$registros = array();
		while($f=mysql_fetch_object($r)){			
			$registros[] = $f;
		}
		echo str_replace("null",'""',json_encode($registros));
		
	}else if($_GET[accion]==5){
		$s = "SELECT DISTINCT g.guia, g.paquete, g.estado, g.codigobarras
		FROM embarquedemercancia em
		INNER JOIN embarquedemercanciadetalle d ON em.folio = d.idembarque
		INNER JOIN 
		(SELECT gv.id AS guia, CONCAT(gvu.paquete,' de ',gvu.depaquetes) AS paquete,
		gvu.estado, '||' AS codigobarras,gv.estado AS estadoguia FROM guiasventanilla gv
		INNER JOIN guiaventanilla_unidades gvu ON gv.id = gvu.idguia
		WHERE gv.estado='EN TRANSITO' OR gv.estado='POR RECIBIR'
		UNION
		SELECT ge.id AS guia, CONCAT(gmu.paquete,' de ',gmu.depaquetes) AS paquete,
		gmu.estado, '||' AS codigobarras,ge.estado AS estadoguia FROM guiasempresariales ge
		INNER JOIN guiasempresariales_unidades gmu ON ge.id = gmu.idguia
		WHERE ge.estado='EN TRANSITO' OR ge.estado='POR RECIBIR') g ON d.guia = g.guia
		WHERE em.unidad='".$_GET[unidad]."'";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$registros[] = $f;
		}
		echo str_replace("null",'""',json_encode($registros));
		
	}else if($_GET[accion]==6){	
		
		
		$s = "SELECT DISTINCT bs.unidad,bs.folio as foliobitacora, cr.id, cr.descripcion AS ruta, crd.tipo,
		crd.sucursal, crd.diasalidas, '' as llegada, '' as salida FROM bitacorasalida bs
		INNER JOIN catalogoruta cr ON bs.ruta = cr.id
		INNER JOIN catalogorutadetalle crd ON cr.id = crd.ruta
		WHERE crd.sucursal = ".$_GET[sucursal]."";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		
		
		while($f = mysql_fetch_object($r)){
		
		$fbitacora = $f->foliobitacora;	
		$s = "SELECT catalogorutadetalle.id
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		INNER JOIN bitacorasalida AS bs ON catalogoruta.id = bs.ruta and bs.status = 0
		WHERE bs.unidad = '$f->unidad' AND catalogorutadetalle.sucursal = ".$_GET[sucursal]."";
		$rr = mysql_query($s,$l) or die($s);
		$ff = mysql_fetch_object($rr);
		
		$iddetalle = ($ff->id!="")?$ff->id:"0";
		
		$s = "drop temporary table if exists tmp_sucursales";
		
		mysql_query($s,$l) or die($s);
		$s = "create temporary table tmp_sucursales
		SELECT catalogorutadetalle.id, catalogorutadetalle.sucursal
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		inner join bitacorasalida as bs on catalogoruta.id = bs.ruta and bs.status = 0
		WHERE bs.unidad = '$f->unidad' AND (catalogorutadetalle.id < $iddetalle)";
		
		
		mysql_query($s,$l) or die(mysql_error($l).$s);
	
		$s = "SELECT * FROM tmp_sucursales ORDER BY id DESC LIMIT 1";
		
		$rr = mysql_query($s,$l) or die($s);
		$ff = mysql_fetch_object($rr);
		$ultsucursal = $ff->sucursal;
			
			$s = "SELECT t1.* FROM (SELECT IFNULL(hrsalida,'00:00:00') AS hrsalida, sucursal FROM programacionrecepciondiaria
			WHERE unidad='".$f->unidad."' AND idbitacora='".$fbitacora."' ORDER BY folio DESC) as t1 LIMIT 1";
		
			$d = mysql_query($s,$l) or die($s);
		 	$df = mysql_fetch_object($d);
		 	
		 	if($df->hrsalida!='00:00:00' && $df->sucursal==$ultsucursal){
		
				$con = "INSERT INTO programacionrecepciondiaria
				(idbitacora,fechaprogramacion, unidad, ruta, hrllegada, hrsalida,sucursal,
				 tipo, usuario, fecha)
				VALUES ('".$f->foliobitacora."',CURRENT_DATE(),'".$f->unidad."','".$f->id."',
				'".$f->llegada."','".$f->salida."',
				".$_GET[sucursal].",".$f->tipo.",
				'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";
				$s = mysql_query(str_replace("''","'00:00:00'",$con),$l)
				or die(mysql_error($l)."<br>$con<br>".__LINE__);
		
				
			}else if(mysql_num_rows($d)==0){
		
				$s = "SELECT crd.sucursal FROM catalogorutadetalle AS crd 
				INNER JOIN bitacorasalida AS bs ON crd.ruta = bs.ruta AND bs.status = 0
				WHERE bs.unidad = '$f->unidad'
				ORDER BY id ASC LIMIT 1";
				$ru = mysql_query($s,$l) or die($s);
				$fu = mysql_fetch_object($ru);
				
		
				
				if($fu->sucursal == $_SESSION[IDSUCURSAL]){
					$con = "INSERT INTO programacionrecepciondiaria
					(idbitacora,fechaprogramacion, unidad, ruta, hrllegada, hrsalida,sucursal,
					 tipo, usuario, fecha)
					VALUES ('".$f->foliobitacora."',CURRENT_DATE(),'".$f->unidad."','".$f->id."',
					'".$f->llegada."','".$f->salida."',
					".$_GET[sucursal].",".$f->tipo.",
					'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";
					$s = mysql_query(str_replace("''","'00:00:00'",$con),$l)
					or die(mysql_error($l)."<br>$s<br>".__LINE__);
		
				}
			}
		}
		/*p.fechaprogramacion=CURRENT_DATE() AND*/
		$s = "SELECT p.folio, p.unidad, cr.descripcion AS ruta, 
		p.hrllegada as llegada, p.hrsalida as salida, p.tipo
		FROM programacionrecepciondiaria p
		INNER JOIN catalogoruta cr ON p.ruta = cr.id
		WHERE  sucursal=".$_GET[sucursal]."
		AND 1=IF(p.tipo<>3 AND p.hrsalida='00:00:00',1,IF(p.tipo=3 AND p.hrllegada='00:00:00',1,0))";
		$sq = mysql_query($s, $l) or die(mysql_error($l).$s);		
		$registros = array();
		while($fq = mysql_fetch_object($sq)){
			$registros[] = $fq;
		}
		
		echo str_replace("null",'""',json_encode($registros));		
		
	}else if($_GET[accion]==7){
		$arr = split(",",$_GET[arre]);
		$s = "UPDATE programacionrecepciondiaria SET hrllegada='".$arr[1]."', hrsalida='".$arr[2]."'
		WHERE folio=".$arr[0]."";
		//echo $s;
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		 
		 $s = "SELECT catalogorutadetalle.* 
		FROM catalogorutadetalle 
		INNER JOIN bitacorasalida ON catalogorutadetalle.ruta = bitacorasalida.ruta 
		 AND bitacorasalida.status = 0 AND bitacorasalida.unidad = '$_GET[unidad]'
		WHERE catalogorutadetalle.sucursal = '$_GET[sucursal]'
		AND catalogorutadetalle.transbordo = 1";
		 $rx = mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		 if(mysql_num_rows($rx)>0){
			  $fx = mysql_fetch_object($rx);
			  if($fx->sucursalestransbordo=="TODAS"){
				$and1   = "";
				$and2   = "";
				$inner1 = "";
				$inner2 = "";
			  }else{	  
				$arreglo = split(",",$fx->sucursalestransbordo);
				  
				 $s = "CREATE TEMPORARY TABLE `sucursalestransbordo_tmp` (
				  `idsucursal` INT(6) NOT NULL DEFAULT '0'
				) ENGINE=INNODB DEFAULT CHARSET=latin1";
				 mysql_query($s,$l) or die($s);
				 foreach($arreglo as $fila){
					 $arre = split(":",$fila);
					 $s = "insert into sucursalestransbordo_tmp set idsucursal = '$arre[0]'";
					 mysql_query($s,$l) or die($s);
				 }
				 $s = "insert into sucursalestransbordo_tmp set idsucursal = '$_SESSION[IDSUCURSAL]'";
					 mysql_query($s,$l) or die($s);
				$and1  = "";
				$and2  = "";
				$inner1 = " inner join sucursalestransbordo_tmp on guiasventanilla.idsucursaldestino = sucursalestransbordo_tmp.idsucursal ";
				$inner2 = " inner join sucursalestransbordo_tmp on guiasempresariales.idsucursaldestino = sucursalestransbordo_tmp.idsucursal ";
			 }
		 }else{			 
			$and1   = " AND guiasventanilla.idsucursaldestino=".$_GET[sucursal];
			$and2   = " AND guiasempresariales.idsucursaldestino=".$_GET[sucursal];
			$inner1 = "";
			$inner2 = "";
		 }
		 
		if($arr[1]!="00:00:00" && $arr[2]=="00:00:00"){
			$s = "INSERT INTO seguimiento_guias
			SELECT NULL, guiaventanilla_unidades.idguia,'$_SESSION[IDSUCURSAL]','$_GET[unidad]',
			'POR RECIBIR', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'	
			FROM guiaventanilla_unidades	
			INNER JOIN guiasventanilla ON guiaventanilla_unidades.idguia = guiasventanilla.id
			$inner1
			WHERE guiaventanilla_unidades.unidad = '$_GET[unidad]' AND guiaventanilla_unidades.proceso = 'EN TRANSITO'
			$and1
			group by guiaventanilla_unidades.idguia";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO seguimiento_guias
			SELECT NULL, guiasempresariales_unidades.idguia,'$_SESSION[IDSUCURSAL]','$_GET[unidad]',
			'POR RECIBIR', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'	
			FROM guiasempresariales_unidades	
			INNER JOIN guiasempresariales ON guiasempresariales_unidades.idguia = guiasempresariales.id
			$inner2
			WHERE guiasempresariales_unidades.unidad = '$_GET[unidad]' AND guiasempresariales_unidades.proceso = 'EN TRANSITO'
			$and2
			group by guiasempresariales_unidades.idguia";
			mysql_query($s,$l) or die($s);
			
			/******************************************/
			
			$s = "UPDATE guiaventanilla_unidades 
			INNER JOIN guiasventanilla ON guiaventanilla_unidades.idguia = guiasventanilla.id
			$inner1
			SET guiaventanilla_unidades.proceso = 'POR RECIBIR', guiasventanilla.estado = 'POR RECIBIR', 
			guiaventanilla_unidades.unidad = null, guiaventanilla_unidades.ubicacion = $_SESSION[IDSUCURSAL]
			WHERE guiaventanilla_unidades.unidad = '$_GET[unidad]' AND guiaventanilla_unidades.proceso = 'EN TRANSITO'
			$and1";
			//echo "$s<br><br>";
			mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
			
			$s = "UPDATE guiasempresariales_unidades 
			INNER JOIN guiasempresariales ON guiasempresariales_unidades.idguia = guiasempresariales.id
			$inner2
			SET guiasempresariales_unidades.proceso = 'POR RECIBIR', guiasempresariales.estado = 'POR RECIBIR', 
			guiasempresariales_unidades.unidad = null, guiasempresariales_unidades.ubicacion = $_SESSION[IDSUCURSAL]
			WHERE guiasempresariales_unidades.unidad = '$_GET[unidad]' AND guiasempresariales_unidades.proceso = 'EN TRANSITO'
			$and2";
			//echo "$s<br><br>";
			mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
			
			$s = "INSERT INTO seguimiento_guias
			SELECT NULL, g.idguia,'$_SESSION[IDSUCURSAL]','$_GET[unidad]',
			'ARRIBO A SUCURSAL', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'
			FROM guiasempresariales_unidades AS g
			WHERE g.unidad = '$_GET[unidad]' AND g.proceso = 'EN TRANSITO'
			GROUP BY g.idguia";
			mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
			
			$s = "INSERT INTO seguimiento_guias
			SELECT NULL, g.idguia,'$_SESSION[IDSUCURSAL]','$_GET[unidad]',
			'ARRIBO A SUCURSAL', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]'
			FROM guiaventanilla_unidades AS g
			WHERE g.unidad = '$_GET[unidad]' AND g.proceso = 'EN TRANSITO'
			GROUP BY g.idguia"
			mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		}
		
		$s="select idbitacora from programacionrecepciondiaria where folio=".$arr[0]." ";
		$r=mysql_query($s,$l)or die($s."<BR>".mysql_error($l));
		$f=mysql_fetch_array($r);
		echo "guardo,$f[idbitacora]";
		
	}else if($_GET[accion]==12){
		$arr = split(",",$_GET[arre]);
		$s = "UPDATE programacionrecepciondiaria SET hrllegada='".$arr[1]."', hrsalida='".$arr[2]."' WHERE folio=".$arr[0]."";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		
	}else if($_GET[accion]==8){		
		$s = "SELECT CURRENT_TIMESTAMP() AS fecha";
		$fe= mysql_query($s,$l) or die($s);
		$fec = mysql_fetch_object($fe);
		
		$s = "INSERT INTO recepcionregistroprecintosdetalle (foliobitacora,remolque,precinto,ubicacion,idusuario,usuario,fecha)
		 VALUES ('".$_GET[foliobitacora]."','".$_GET[remolque]."',".$_GET[precinto].",'".$_GET[ubicacion]."',".$_SESSION[IDUSUARIO].",
		 '".$_SESSION[NOMBREUSUARIO]."','".$_GET[fecha]."')";
		$r = mysql_query($s,$l) or die($s);
		
		echo "ok,".$fec->fecha;
		
	}else if($_GET[accion]==9){
		$s = "SELECT remolque1, remolque2 FROM bitacorasalida WHERE unidad='$_GET[unidad]' AND status=0";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$f->remolque1 = cambio_texto($f->remolque1);
		$f->remolque2 = cambio_texto($f->remolque2);
		
		$remolques = str_replace('null','""',json_encode($f));
		
		echo "({remolques:$remolques})";
		
	}else if($_GET[accion]==10){
		$s = "SELECT * FROM recepcionregistroprecintosdetalle WHERE unidad IS NULL";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "UPDATE recepcionregistroprecintosdetalle SET unidad='".$_GET[unidad]."'
			WHERE unidad IS NULL AND idusuario=".$_SESSION[IDUSUARIO]."";
			$upd = mysql_query($s,$l) or die($s);
			
			$s = "UPDATE asignacionprecintosdetalle SET utilizado=1
			WHERE sucursal=".$_GET[sucursal]." AND folios=".$f->precinto."";
			$d = mysql_query($s,$l) or die($s);
		}
		if($_GET[observaciones]!=""){
			$s = "INSERT INTO recepcionregistroprecintos (foliobitacora,unidad,observaciones,usuario,fecha) VALUES ('".$_GET[foliopro]."','".$_GET[unidad]."','".cambio_texto(trim($_GET[observaciones]))."','".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";
			$ins = mysql_query($s,$l) or die($s);
		}
		echo "guardado";
	}else if($_GET[accion]==11){
		$s = "SELECT remolque, precinto, ubicacion FROM recepcionregistroprecintosdetalle p
		INNER JOIN bitacorasalida bs ON p.unidad = bs.unidad
		WHERE p.unidad='".$_GET[unidad]."' AND bs.status=0";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$registros[] = $f;
		}		
		echo str_replace("null",'""',json_encode($registros));

	}else if($_GET[accion]==13){
		$s = "SELECT p.id, p.observaciones FROM recepcionregistroprecintos p
		LEFT JOIN bitacorasalida bs ON p.unidad = bs.unidad
		WHERE p.unidad='".$_GET[unidad]."' 
		LIMIT ".$_GET[limit].",1";
		/*$s = "SELECT p.id, p.observaciones FROM recepcionregistroprecintos p
		INNER JOIN bitacorasalida bs ON p.unidad = bs.unidad
		WHERE p.unidad='".$_GET[unidad]."' AND bs.status=0
		LIMIT ".$_GET[limit].",1";*/
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}		
			echo str_replace("null",'""',json_encode($registros));
		}else{
			echo str_replace("null",'""',json_encode(0));
		}
	}else if($_GET[accion]==14){
		$s = "DELETE FROM recepcionregistroprecintosdetalle
		WHERE idusuario=".$_SESSION[IDUSUARIO]." AND unidad IS NULL";
		$de= mysql_query($s,$l) or die($s);
	
	}else if($_GET[accion]==15){
		$s = "DELETE FROM recepcionregistroprecintosdetalle
		WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."' AND unidad IS NULL";
		$de= mysql_query($s,$l) or die($s);
		
		echo "ok";
	}
?>