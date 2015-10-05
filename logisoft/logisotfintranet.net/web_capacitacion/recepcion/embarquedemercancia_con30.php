<?
	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = "SELECT catalogorutadetalle.id
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		INNER JOIN bitacorasalida AS bs ON catalogoruta.id = bs.ruta AND bs.status = 0 and bs.cancelada = 0
		WHERE bs.unidad = '$_GET[unidad]' AND catalogorutadetalle.sucursal = $_SESSION[IDSUCURSAL]";
		//echo $s."<br>";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$iddetalle = ($f->id!="")?$f->id:"0";
		//echo $s."<br>";
		$s = "create temporary table tmp_sucursales".$_SESSION[IDUSUARIO]."
		SELECT catalogorutadetalle.sucursal
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		inner join bitacorasalida as bs on catalogoruta.id = bs.ruta AND bs.status = 0 and bs.cancelada = 0
		WHERE bs.unidad = '$_GET[unidad]'
		AND catalogorutadetalle.id > $iddetalle";
		//echo $s."<br>";
		mysql_query($s,$l) or die($s);
		//echo $s."<br>";	
		
		//obtener trasbordos
		$s = "SELECT sucursalestransbordo
		FROM catalogorutadetalle
		INNER JOIN catalogoruta ON catalogorutadetalle.ruta = catalogoruta.id
		INNER JOIN bitacorasalida AS bs ON catalogoruta.id = bs.ruta AND bs.status = 0 and bs.cancelada = 0
		WHERE bs.unidad = '$_GET[unidad]'
		AND catalogorutadetalle.id > $iddetalle AND sucursalestransbordo <> ''";
		//echo $s."<br>";
		$r = mysql_query($s,$l) or die($s);
		while($fs=mysql_fetch_object($r)){
			$suctrans = split(",",$fs->sucursalestransbordo);
			for($i=0; $i<count($suctrans); $i++){
				$obtsuc = split(":",$suctrans[$i]);
				$s = "insert into tmp_sucursales".$_SESSION[IDUSUARIO]." set sucursal = '".$obtsuc[0]."'";
				mysql_query($s,$l) or die($s);
			}
		}
		
		//echo $s."<br>";
		$s = "DELETE FROM embarques_tmp 
		WHERE idusuario = '$_SESSION[IDUSUARIO]' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		//echo $s."<br>";
		
		$s = "SELECT catalogorutadetalle.* 
		FROM catalogoruta
		INNER JOIN catalogorutadetalle ON catalogoruta.id = catalogorutadetalle.ruta
		INNER JOIN bitacorasalida ON catalogoruta.id = bitacorasalida.ruta
		WHERE bitacorasalida.status = 0 and bitacorasalida.cancelada = 0 AND bitacorasalida.unidad = '$_GET[unidad]'
		AND catalogorutadetalle.tipo=1 AND catalogorutadetalle.sucursal = '$_SESSION[IDSUCURSAL]'";
		//echo $s."<br>";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$inner1 = " ";
		}else{
			$s = "SELECT catalogorutadetalle.* 
			FROM catalogoruta
			INNER JOIN catalogorutadetalle ON catalogoruta.id = catalogorutadetalle.ruta
			INNER JOIN bitacorasalida ON catalogoruta.id = bitacorasalida.ruta
			WHERE bitacorasalida.status = 0 and bitacorasalida.cancelada = 0 AND catalogorutadetalle.tipo<>1 
			AND catalogorutadetalle.sucursal = '$_SESSION[IDSUCURSAL]'";
			//echo $s."<br>";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$inner1 = " INNER JOIN programacionrecepciondiaria as pr on '$_GET[unidad]' = pr.unidad and pr.hrllegada <> '00:00:00' and pr.hrsalida = '00:00:00' ";
			}else{
				$inner1 = " ";
			}
		}
		
		$s = "INSERT INTO embarques_tmp
		SELECT gvu.id, cse.descripcion, gvu.idguia, 'NORMAL', cs.prefijo, cs2.prefijo, gvu.codigobarras, 
		gvu.paquete, CONCAT(gvu.paquete,' de ', gvu.depaquetes), gvu.estado, 'N', gvu.peso, '$_SESSION[IDUSUARIO]',
		'$_SESSION[IDSUCURSAL]'
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS cse ON gv.sector = cse.id
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON gv.idsucursaldestino = cs2.id
		$inner1
		INNER JOIN tmp_sucursales".$_SESSION[IDUSUARIO]." as tmps on gv.idsucursaldestino = tmps.sucursal
		WHERE (isnull(gvu.proceso) or gvu.proceso = 'ALMACEN TRASBORDO') and (gv.estado='ALMACEN ORIGEN' or gv.estado like '%ALMACEN TRASBORDO%')
		and (isnull(gvu.unidad) OR gvu.unidad='')
		and gvu.ubicacion = $_SESSION[IDSUCURSAL]";
		//echo $s."<br>";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "INSERT INTO embarques_tmp
		SELECT geu.id, cse.descripcion, geu.idguia, 'EMPRESARIAL', cs.prefijo, cs2.prefijo, geu.codigobarras, 
		geu.paquete, CONCAT(geu.paquete,' de ', geu.depaquetes), geu.estado, 'N', geu.peso, '$_SESSION[IDUSUARIO]',
		'$_SESSION[IDSUCURSAL]'
		FROM guiasempresariales AS ge
		LEFT JOIN catalogosector AS cse ON ge.sector = cse.id
		INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia
		INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON ge.idsucursaldestino = cs2.id
		$inner1
		INNER JOIN tmp_sucursales".$_SESSION[IDUSUARIO]." as tmps on ge.idsucursaldestino = tmps.sucursal
		WHERE (isnull(geu.proceso) or geu.proceso = 'ALMACEN TRASBORDO') and (ge.estado='ALMACEN ORIGEN' or ge.estado like '%ALMACEN TRASBORDO%')
		and (isnull(geu.unidad) OR geu.unidad='')
		and geu.ubicacion = $_SESSION[IDSUCURSAL];";
		//echo $s."<br>";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'N' and idusuario = $_SESSION[IDUSUARIO] AND sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia
		order by destino";
		//echo $s."<br>";
		$r = mysql_query($s,$l) or die($s);
		/*$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$f->guia = cambio_texto($f->guia);
			$arre[] = $f;
		}
		$izquierda = str_replace("null",'""',json_encode($arre));*/
		
		
		$cont = 0;
		$contr = 0;
		$datos = "";
		$comienzo = true;
		$datosIzq = "";
		
		if(mysql_num_rows($r)>0){
			$registros = mysql_num_rows($r);
			while($f = mysql_fetch_object($r)){				
				$cont++;
				$contr++;
				if($cont==1){
					if($comienzo){
						$comienzo=false;
					}else{
						$datosIzq .= ",";
					}
					$datosIzq .= "{'izquierda':[";
				}
				$f->origen = cambio_texto($f->origen);
				$f->destino = cambio_texto($f->destino);
				$f->guia = cambio_texto($f->guia);
				if($cont==30){
					$datosIzq .= "{'sector':'$f->sector','guia':'$f->guia','origen':'$f->origen','destino':'$f->destino','peso':'$f->peso'}";
					if($contr!=$registros){
						$datosIzq .= "]}";
					}
					$cont = 0;
				}else{
					$datosIzq .= "{'sector':'$f->sector','guia':'$f->guia','origen':'$f->origen','destino':'$f->destino','peso':'$f->peso'}";
					if($contr!=$registros){
						$datosIzq .= ",";
					}
				}
			}	
			$datosIzq .= "]}";
		}
		
		$datosIzq = (($datosIzq=="")? "{'izquierda':[]}" : $datosIzq);
		
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'S' and idusuario = $_SESSION[IDUSUARIO] AND sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia
		order by destino";
		$r = mysql_query($s,$l) or die($s);
		$cont = 0;
		$contr = 0;
		$datos = "";
		$comienzo = true;
		$datosDer = "";
		
		if(mysql_num_rows($r)>0){
			$registros = mysql_num_rows($r);
			while($f = mysql_fetch_object($r)){				
				$cont++;
				$contr++;
				if($cont==1){
					if($comienzo){
						$comienzo=false;
					}else{
						$datosDer .= ",";
					}
					$datosDer .= "{'derecha':[";
				}
				$f->origen = cambio_texto($f->origen);
				$f->destino = cambio_texto($f->destino);
				$f->guia = cambio_texto($f->guia);
				if($cont==30){
					$datosDer .= "{'sector':'$f->sector','guia':'$f->guia','origen':'$f->origen','destino':'$f->destino','peso':'$f->peso'}";
					if($contr!=$registros){
						$datosDer .= "]}";
					}
					$cont = 0;
				}else{
					$datosIzq .= "{'sector':'$f->sector','guia':'$f->guia','origen':'$f->origen','destino':'$f->destino','peso':'$f->peso'}";
					if($contr!=$registros){
						$datosDer .= ",";
					}
				}
			}	
			$datosDer .= "]}";
		}
		
		$datosDer = (($datosDer=="")? "{'derecha':[{'sector':'','guia':'','origen':'','destino':'','peso':''}]}" : $datosDer);
		
		echo "[".$datosIzq."]";
		
	}
	
	if($_GET[accion]==2){
		$s = "SELECT registro,paquete,codigobarra as codigobarras,estado,guia,peso
		FROM embarques_tmp 
		WHERE guia='$_GET[folio]' and subido='N' and idusuario = $_SESSION[IDUSUARIO] 
		AND sucursal = ".$_SESSION[IDSUCURSAL]."";		
		$r = mysql_query($s,$l) or die($s);
		$cont = 0;
		$contr = 0;
		$datos = "";
		$comienzo = true;		
		
		if(mysql_num_rows($r)>0){
			$registros = mysql_num_rows($r);
			while($f = mysql_fetch_object($r)){
				$cont++;
				$contr++;
				if($cont==1){
					if($comienzo){
						$comienzo=false;
					}else{
						$datos .= ",";
					}
					$datos .= "{'datos':[";
				}
				$f->paquete = cambio_texto($f->paquete);
				$f->estado = cambio_texto($f->estado);
				$f->guia = cambio_texto($f->guia);
				if($cont==30){
					$datos .= "{'registro':'$f->registro','paquete':'$f->paquete','codigobarras':'$f->codigobarras','estado':'$f->estado','guia':'$f->guia','peso':'$f->peso'}";
					if($contr!=$registros){
						$datos .= "]}";
					}
					$cont = 0;
				}else{
					$datos .= "{'registro':'$f->registro','paquete':'$f->paquete','codigobarras':'$f->codigobarras','estado':'$f->estado','guia':'$f->guia','peso':'$f->peso'}";
					if($contr!=$registros){
						$datos .= ",";
					}
				}
			}	
			$datos .= "]}";
		}
		
		echo "[".$datos."]";
		
	}
	if($_GET[accion]==3){
		$s = "SELECT registro,paquete,codigobarra as codigobarras,estado,guia,peso
		FROM embarques_tmp 
		WHERE guia='$_GET[folio]' and subido='S' and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		
		$cont = 0;
		$contr = 0;
		$datos = "";
		$comienzo = true;		
		
		if(mysql_num_rows($r)>0){
			$registros = mysql_num_rows($r);
			while($f = mysql_fetch_object($r)){				
				$cont++;
				$contr++;
				if($cont==1){
					if($comienzo){
						$comienzo=false;
					}else{
						$datos .= ",";
					}
					$datos .= "{'datos':[";
				}
				$f->paquete = cambio_texto($f->paquete);
				$f->estado = cambio_texto($f->estado);
				$f->guia = cambio_texto($f->guia);
				if($cont==30){
					$datos .= "{'registro':'$f->registro','paquete':'$f->paquete','codigobarras':'$f->codigobarras','estado':'$f->estado','guia':'$f->guia','peso':'$f->peso'}";
					if($contr!=$registros){
						$datos .= "]}";
					}
					$cont = 0;
				}else{
					$datos .= "{'registro':'$f->registro','paquete':'$f->paquete','codigobarras':'$f->codigobarras','estado':'$f->estado','guia':'$f->guia','peso':'$f->peso'}";
					if($contr!=$registros){
						$datos .= ",";
					}
				}
			}	
			$datos .= "]}";
		}
		
		echo "[".$datos."]";
		
	}
	if($_GET[accion]==4){
		$s = "UPDATE embarques_tmp SET subido = 'S' 
		WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==5){
		$s = "UPDATE embarques_tmp SET subido = 'S' 
		WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==6){
		$s = "UPDATE embarques_tmp SET subido = 'N' 
		WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);		
		echo "guardado,$f->pesototal";
	}
	if($_GET[accion]==7){
		$s = "UPDATE embarques_tmp SET subido = 'N' 
		WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]
		AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		$s = "SELECT ifnull(SUM(peso),0) AS pesototal 
		FROM embarques_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "guardado,$f->pesototal";
	}
	
	if($_GET[accion]==8){
		$s = "select t1.guia
		from
		(select guia, subido 
		from embarques_tmp
		where subido = 'S' and idusuario = '$_SESSION[IDUSUARIO]' AND sucursal = ".$_SESSION[IDSUCURSAL].") as t1
		inner join 
		(select guia, subido 
		from embarques_tmp
		where subido = 'N' and idusuario = '$_SESSION[IDUSUARIO]' AND sucursal = ".$_SESSION[IDSUCURSAL].") as t2 
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
		//$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		
		$s = "CREATE TEMPORARY TABLE tmp_seleccionadas (
			`folioguia` VARCHAR(25) COLLATE utf8_unicode_ci DEFAULT NULL
		);";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO tmp_seleccionadas
		SELECT guia FROM embarques_tmp 
		WHERE subido = 'S' AND idusuario = '$_SESSION[IDUSUARIO]' AND sucursal = $_SESSION[IDSUCURSAL]
		GROUP BY guia";
		mysql_query($s,$l) or die($s);
		#validacion para que no se repitan las guias
		
		$guiasYaGuardadas = "";
		$s = "SELECT embarquedemercanciadetalle.guia 
		FROM embarquedemercanciadetalle 
		INNER JOIN tmp_seleccionadas tp on embarquedemercanciadetalle.guia = tp.folioguia 
		WHERE embarquedemercanciadetalle.sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$guiasYaGuardadas .= (($guiasYaGuardadas!="")?",":"").$f->guia;
		}
		if($guiasYaGuardadas!=""){
			die("Las siguientes guias: $guiasYaGuardadas ya fueron embarcadas");
		}
		
		$s = "insert into embarquedemercancia set
		folio = obtenerFolio('embarquedemercancia',$_SESSION[IDSUCURSAL]),
		foliobitacora='$_GET[foliobitacora]',unidad = '$_GET[unidad]', tipo='$_GET[tipoembarque]',
		idsucursal=$_SESSION[IDSUCURSAL], ruta = '$_GET[ruta]', recorrido = '$_GET[recorrido]',
		fecha=current_date, usuario='$_SESSION[NOMBREUSUARIO]', idusuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		$idx = mysql_insert_id($l);
		
		$s = "SELECT folio FROM embarquedemercancia WHERE id = ".$idx;
		$r = mysql_query($s,$l) or die($s); $fidx = mysql_fetch_object($r);
		
		$s = "update catalogounidad set embarcado = 'S' where numeroeconomico = '$_GET[unidad]'";
		mysql_query($s,$l) or die($s);
		
		$s = "update embarquedemercancia_faltante set embarque = ".$fidx->folio." where embarque IS NULL AND idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s="insert into embarquedemercanciadetalle 
		SELECT 0 AS id,'$fidx->folio', rg.guia AS guia, rg.origen, 
		CURRENT_DATE, rg.codigobarra, NULL, sucursal
		FROM embarques_tmp AS rg 
		INNER JOIN tmp_seleccionadas tp on rg.guia = tp.folioguia 
		WHERE idusuario = $_SESSION[IDUSUARIO] and rg.subido = 'S'
		AND sucursal = ".$_SESSION[IDSUCURSAL]." 
		GROUP BY rg.guia";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s="INSERT INTO embarquedemercanciapaquetes
		SELECT idunidad,$fidx->folio,sector,guia,tipoguia,origen,destino,
		codigobarra,registro,paquete,estado,peso,sucursal
		FROM embarques_tmp WHERE idusuario=$_SESSION[IDUSUARIO]
		AND subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
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
		CONCAT('EN TRANSITO M ',IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','$f->folio','$fidx->folio'
		FROM embarques_tmp AS e 
		INNER JOIN guiasventanilla g ON e.guia = g.id
		WHERE e.idusuario = '$_SESSION[IDUSUARIO]' AND e.subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
		SELECT e.guia,'$_SESSION[IDSUCURSAL]','$_GET[unidad]',
		CONCAT('EN TRANSITO M ',IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','$f->folio','$fidx->folio'
		FROM embarques_tmp AS e 
		INNER JOIN guiasempresariales g ON e.guia = g.id
		WHERE e.idusuario = '$_SESSION[IDUSUARIO]' AND e.subido = 'S' AND sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		mysql_query($s,$l) or die($s);
		//*******************************************
		
		$s = "UPDATE guiaventanilla_unidades
		INNER JOIN embarques_tmp ON guiaventanilla_unidades.idguia = embarques_tmp.guia
		INNER JOIN tmp_seleccionadas tp on embarques_tmp.guia = tp.folioguia 
		AND guiaventanilla_unidades.paquete = embarques_tmp.registro
		SET guiaventanilla_unidades.proceso = 'EN TRANSITO', guiaventanilla_unidades.unidad='$_GET[unidad]'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'S'
		and embarques_tmp.sucursal = ".$_SESSION[IDSUCURSAL]."";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN embarques_tmp ON guiasempresariales_unidades.idguia = embarques_tmp.guia
		INNER JOIN tmp_seleccionadas tp on embarques_tmp.guia = tp.folioguia 
		AND guiasempresariales_unidades.paquete = embarques_tmp.registro
		SET guiasempresariales_unidades.proceso = 'EN TRANSITO', guiasempresariales_unidades.unidad='$_GET[unidad]'
		WHERE embarques_tmp.idusuario = $_SESSION[IDUSUARIO] and embarques_tmp.subido = 'S'
		and embarques_tmp.sucursal = ".$_SESSION[IDSUCURSAL]."";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "CREATE TEMPORARY TABLE paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN TRANSITO','EN TRANSITO INCOMPLETO') AS estado
		FROM guiasventanilla AS gv
		INNER JOIN tmp_seleccionadas tp on gv.id = tp.folioguia
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'EN TRANSITO'
		GROUP BY gv.id";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "insert into paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'EN TRANSITO','EN TRANSITO INCOMPLETO') AS estado
		FROM guiasempresariales AS gv
		INNER JOIN tmp_seleccionadas tp on gv.id = tp.folioguia
		INNER JOIN guiasempresariales_unidades AS gvu ON gv.id = gvu.idguia
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
		$su= mysql_query($s,$l) or die($s); $sucur = mysql_fetch_object($su);
		
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
		
		echo "guardado,".$fidx->folio;
	}
	
	if($_GET[accion]==10){
		$s = "SELECT em.unidad, cr.descripcion, cr.id, CONCAT(cs.prefijo,'-',LPAD(em.folio,5,'0')) AS folio, 
		cs.descripcion AS sucursal, DATE_FORMAT(em.fecha, '%d-%m-%Y') AS fecha 
		FROM embarquedemercancia AS em 
		INNER JOIN catalogoruta AS cr ON em.ruta = cr.id
		INNER JOIN catalogosucursal AS cs ON em.idsucursal = cs.id		
		WHERE folio = $_GET[folio] AND em.idsucursal=$_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		$f->descripcion = cambio_texto($f->descripcion);
		
		$s = "SELECT d.id FROM catalogoruta cr
		INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
		WHERE cr.id = ".$f->id."";
		$ru = mysql_query($s,$l) or die($s);
		$cr = mysql_fetch_object($ru);
		
		$s = "SELECT cs.descripcion FROM catalogorutadetalle d
		INNER JOIN catalogosucursal cs ON d.sucursal = cs.id
		WHERE d.id > ".$cr->id." LIMIT 1";
		$des = mysql_query($s,$l) or die($s);
		$dest= mysql_fetch_object($des);
		
		$f->destino = cambio_texto($dest->descripcion);
		
		$datosembarque = str_replace("null",'""',json_encode($f));
		
		$s = "DELETE FROM embarques_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]' AND sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO embarques_tmp
		SELECT idunidad, sector, guia, tipoguia, origen, destino, codigobarra, 
		registro, paquete, estado, 'S', peso, '$_SESSION[IDUSUARIO]', sucursal
		FROM embarquedemercanciapaquetes 
		WHERE folioembarque = $_GET[folio] AND sucursal=$_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
		FROM embarques_tmp
		where subido = 'S' and idusuario = $_SESSION[IDUSUARIO] and sucursal = ".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$f->guia = cambio_texto($f->guia);
			$arre[] = $f;
		}
		$derecha = str_replace("null",'""',json_encode($arre));
		
		/*echo "({
				datosembarque:$datosembarque,
				izquierda:[], 
				derecha:$derecha
			})";*/
		
		/*$cont = 0;
		$contr = 0;
		$datos = "";
		$comienzo = true;		
		
		if(mysql_num_rows($r)>0){
			$registros = mysql_num_rows($r);
			while($f = mysql_fetch_object($r)){				
				$cont++;
				$contr++;
				if($cont==1){
					if($comienzo){
						$comienzo=false;
					}else{
						$datos .= ",";
					}
					$datos .= "{'derecha':[";
				}
				$f->origen = cambio_texto($f->origen);
				$f->destino = cambio_texto($f->destino);
				$f->guia = cambio_texto($f->guia);
				if($cont==30){
					$datos .= "{'sector':'$f->sector','guia':'$f->guia','origen':'$f->origen','destino':'$f->destino','peso':'$f->peso'}";
					$datos .= "]}";
					$cont = 0;
				}else{
					$datos .= "{'sector':'$f->sector','guia':'$f->guia','origen':'$f->origen','destino':'$f->destino','peso':'$f->peso'}";
					if($contr!=$registros){
						$datos .= ",";
					}
				}
			}	
			$datos .= "]}";
		}*/
//		echo "[".$datos."]";
		
		echo "({
				datosembarque:$datosembarque,				
				derecha:$derecha
			})";
			
	}
	
	if($_GET[accion]==11){
		$s = "SELECT MAX(folio) AS folio, DATE_FORMAT(CURRENT_DATE, '%d-%m-%Y') AS fecha 
		FROM embarquedemercancia where idsucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$foliofecha = str_replace("null",'""',json_encode($f));
		
		echo "($foliofecha)";
	}
	
	/*if($_GET[accion]==13){
		$s = "SELECT ef.id, em.folio
		FROM embarquedemercancia_faltante ef
		INNER JOIN embarquedemercancia em ON ef.bitacora = em.foliobitacora
		AND em.idsucursal = ef.sucursal
		WHERE em.folio = '$_GET[folio]' AND em.idsucursal='$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			echo "({'idsucursal':'$_SESSION[IDSUCURSAL]','folio':'$f->folio'})";
		}else{
			echo "({'idsucursal':'$_SESSION[IDSUCURSAL]','folio':''})";
		}
	}*/
	
	/*if($_GET[accion]==12){		
		$s = "SELECT sucursalestransbordo FROM catalogorutadetalle WHERE ruta=5";
		$r = mysql_query($s,$l) or die($s);
		
		$sucursales = "";
		
		while($f = mysql_fetch_object($r)){			
			if(!empty($f->sucursalestransbordo)){
				if($f->sucursalestransbordo!="TODAS"){
					$ro = split(",",$f->sucursalestransbordo);
					for($i=0;$i < count($ro);$i++){
						$y = split(":",$ro[$i]);
						for($j=0;$j < count($y);$j++){
							if(is_numeric($y[$j])){
								$sucursales .= $y[$j].",";
							}
						}
					}
				}else{
					$s = "SELECT id FROM catalogosucursal WHERE id NOT IN(1)";
					$t = mysql_query($s,$l) or die($s);
					while($u = mysql_fetch_object($t)){
						$sucursales .= $u->id.",";
					}
				}
			}
		}
		echo substr($sucursales,0, strlen($sucursales)-1);		
	}*/
	
?>