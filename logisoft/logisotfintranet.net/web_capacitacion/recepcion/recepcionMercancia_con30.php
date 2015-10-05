<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$paginado = 30;
	$contador = ($_GET[contador]!="")?$_GET[contador]:0;
	$desde	  = ($paginado*$contador);
	$limite = " limit $desde, $paginado ";
	
	function f_adelante($vdesde,$vpaginado,$total){
		if($vdesde+$vpaginado>($total-1))
			return false;
		else
			return true;
	}
	function f_atras($vdesde){
		if($vdesde==0)
			return false;
		else
			return true;
	}
	function f_paginado($vpaginado,$vtotal){
		if($vpaginado>=$vtotal)
			return false;
		else
			return true;
	}
	
	if($_GET['accion']==1){		
		$s = "DELETE FROM recepcion_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO recepcion_tmp
		SELECT gvu.id, cse.descripcion, gvu.idguia, 'NORMAL', cs.descripcion, cs2.descripcion, gvu.codigobarras, 
		gvu.paquete, CONCAT(gvu.paquete,' de ', gvu.depaquetes), gv.estado, 'N', '$_SESSION[IDUSUARIO]',
		'$_SESSION[IDSUCURSAL]'
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS cse ON gv.sector = cse.id
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON gv.idsucursaldestino = cs2.id
		WHERE gvu.proceso = 'POR RECIBIR' and gvu.ubicacion = $_SESSION[IDSUCURSAL] and gvu.unidad = '$_GET[unidad]'
		UNION
		SELECT geu.id, cse.descripcion, geu.idguia, 'EMPRESARIAL', cs.descripcion, cs2.descripcion, geu.codigobarras, 
		geu.paquete, CONCAT(geu.paquete,' de ', geu.depaquetes), ge.estado, 'N', '$_SESSION[IDUSUARIO]',
		'$_SESSION[IDSUCURSAL]'
		FROM guiasempresariales AS ge
		LEFT JOIN catalogosector AS cse ON ge.sector = cse.id
		INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia
		INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON ge.idsucursaldestino = cs2.id
		WHERE geu.proceso = 'POR RECIBIR' and geu.ubicacion = $_SESSION[IDSUCURSAL] and geu.unidad = '$_GET[unidad]'";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "SELECT sector, guia, origen, destino FROM recepcion_tmp
		where subido = 'N' and idusuario = $_SESSION[IDUSUARIO] and sucursal=".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		
		
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
					$datosIzq .= "{'sector':'$f->sector','guia':'$f->guia','origen':'$f->origen','destino':'$f->destino'}";
					if($contr!=$registros){
						$datosIzq .= "]}";
					}
					$cont = 0;
				}else{
					$datosIzq .= "{'sector':'$f->sector','guia':'$f->guia','origen':'$f->origen','destino':'$f->destino'}";
					if($contr!=$registros){
						$datosIzq .= ",";
					}
				}
			}	
			$datosIzq .= "]}";
		}		
		
		echo "[".$datosIzq."]";
		
		/*$s = "SELECT sector, guia, origen, destino, sum(peso) as peso
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
					$datosDer .= "]}";
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
		
		$datosDer = (($datosDer=="")? "{'derecha':[{'sector':'','guia':'','origen':'','destino':'','peso':''}]}" : $datosDer);*/
		
		
		
		/*$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$arre[] = $f;
		}
		$izquierda = str_replace("null",'""',json_encode($arre));*/
		
		
/*		$s = "SELECT sector, guia, origen, destino
		FROM recepcion_tmp
		where subido = 'S' and idusuario = $_SESSION[IDUSUARIO] and sucursal=".$_SESSION[IDSUCURSAL]."
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);*/
		/*$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$arre[] = $f;
		}
		$derecha = str_replace("null",'""',json_encode($arre));
		
		echo "({
			   		izquierda:$izquierda, 
			   		derecha:$derecha
				})";*/
	}
	
	if($_GET[accion]==2){
		$s = "SELECT registro,paquete,codigobarra,estado,guia 
		FROM recepcion_tmp WHERE guia='$_GET[folio]' and subido='N' 
		and idusuario = $_SESSION[IDUSUARIO] and sucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		/*$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));*/
		
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
					$datos .= "{'registro':'$f->registro','paquete':'$f->paquete','codigobarras':'$f->codigobarras','estado':'$f->estado','guia':'$f->guia'}";
					$datos .= "]}";
					$cont = 0;
				}else{
					$datos .= "{'registro':'$f->registro','paquete':'$f->paquete','codigobarras':'$f->codigobarras','estado':'$f->estado','guia':'$f->guia'}";
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
		$s = "SELECT registro,paquete,codigobarra,estado,guia
		FROM recepcion_tmp WHERE guia='$_GET[folio]' and subido='S' 
		and idusuario = $_SESSION[IDUSUARIO] and sucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		/*$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));*/
		
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
					$datos .= "{'registro':'$f->registro','paquete':'$f->paquete','codigobarras':'$f->codigobarras','estado':'$f->estado','guia':'$f->guia'}";
					$datos .= "]}";
					$cont = 0;
				}else{
					$datos .= "{'registro':'$f->registro','paquete':'$f->paquete','codigobarras':'$f->codigobarras','estado':'$f->estado','guia':'$f->guia'}";
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
		$s = "UPDATE recepcion_tmp SET subido = 'S' WHERE guia = '$_GET[folio]' 
		and idusuario = $_SESSION[IDUSUARIO] and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reportedanosfaltante WHERE guia = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		echo "guardado";
	}
	if($_GET[accion]==5){
		$s = "UPDATE recepcion_tmp SET subido = 'S' WHERE guia = '$_GET[folio]' and registro = $_GET[registro] 
		and idusuario = $_SESSION[IDUSUARIO] and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==6){
		$s = "UPDATE recepcion_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' 
		and idusuario = $_SESSION[IDUSUARIO] and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reportedanosfaltante WHERE guia = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		echo "guardado";
	}
	if($_GET[accion]==7){
		$s = "UPDATE recepcion_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' and registro = $_GET[registro] 
		and idusuario = $_SESSION[IDUSUARIO] and sucursal=".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	
	if($_GET[accion]==8){
		/*$s = "select t1.guia
		from
		(select guia, subido from recepcion_tmp
		where subido = 'S' and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL].") as t1
		inner join 
		(select guia, subido 
		from recepcion_tmp
		where subido = 'N' and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL].") as t2 
		on t1.guia = t2.guia
		group by t1.guia";*/
		
		$s = "select guia from recepcion_tmp
		where subido = 'N' and idusuario = '$_SESSION[IDUSUARIO]' and sucursal=".$_SESSION[IDSUCURSAL]."";
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
		
		$s = "CREATE TEMPORARY TABLE tmp_seleccionadas (
			`folioguia` VARCHAR(25) COLLATE utf8_unicode_ci DEFAULT NULL
		);";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO tmp_seleccionadas
		SELECT guia FROM recepcion_tmp
			WHERE idusuario = '$_SESSION[IDUSUARIO]' AND sucursal = '$_SESSION[IDSUCURSAL]'
			AND  subido = 'S'
			GROUP BY guia";
		mysql_query($s,$l) or die($s);
		
		//$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		
		#validacion para que no se repitan las guias****************
		$guiasYaGuardadas = "";
		$s = "SELECT rm.guia FROM recepcionmercanciadetalle rm
		INNER JOIN tmp_seleccionadas tp ON rm.guia = tp.folioguia
		WHERE rm.sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$guiasYaGuardadas .= (($guiasYaGuardadas!="")?",":"").$f->guia;
		}
		if($guiasYaGuardadas!=""){
			die("Las siguientes guias: $guiasYaGuardadas ya fueron recepcionadas");
		}
		#***********************************************************
		
		$s = "SELECT guia FROM recepcion_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]' AND sucursal = '$_SESSION[IDSUCURSAL]' AND subido = 'N'
		AND NOT EXISTS(SELECT * FROM reportedanosfaltante rd WHERE rd.guia = recepcion_tmp.guia)";
		$r = mysql_query($s,$l) or die($s);
		$valor = "";
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$valor .= (($valor!="")?",":"").$f->guia;
			}
		}
		if($valor!=""){
			die("({'noserecepcionaron':'$valor'})");
		}
		
		$s = "insert into recepcionmercancia set 
		folio = obtenerFolio('recepcionmercancia',$_SESSION[IDSUCURSAL]),
		foliobitacora='$_GET[foliobitacora]',unidad = '$_GET[unidad]', 
		tipo='$_GET[tiporecepcion]', idsucursal=$_SESSION[IDSUCURSAL],
		ruta = '$_GET[ruta]', recorrido = '$_GET[recorrido]', fecha=current_date, 
		usuario='$_SESSION[NOMBREUSUARIO]', idusuario=$_SESSION[IDUSUARIO]";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		$idx = mysql_insert_id($l);
		
		$s = "SELECT folio FROM recepcionmercancia WHERE id = ".$idx;
		$r = mysql_query($s,$l) or die($s); $fidx = mysql_fetch_object($r);
		
		$s = "update catalogounidad set recepcionado = 'S' where numeroeconomico = '$_GET[unidad]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE programacionrecepciondiaria SET recibida = 'S' WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND recibida='N'
			AND unidad = '$_GET[unidad]'";
		mysql_query($s,$l) or die($s);
		
		#quitar unidad de los registrados como faltantes
		$s="UPDATE guiaventanilla_unidades gu
		INNER JOIN recepcion_tmp rt ON rt.guia = gu.idguia
		INNER JOIN reportedanosfaltante rd ON rt.guia = rd.guia
		SET gu.unidad = ''
		where rt.idusuario = '$_SESSION[IDUSUARIO]' and rd.faltante = 1";
		mysql_query($s,$l) or die($s);
		
		$s="UPDATE guiasempresariales_unidades gu
		INNER JOIN recepcion_tmp rt ON rt.guia = gu.idguia
		INNER JOIN reportedanosfaltante rd ON rt.guia = rd.guia
		SET gu.unidad = ''
		where rt.idusuario = '$_SESSION[IDUSUARIO]' and rd.faltante = 1";
		mysql_query($s,$l) or die($s);
		#----------------------------------------------------------------
		
		$s="insert into recepcionmercanciadetalle 
		SELECT 0 as id, '$fidx->folio', rg.guia AS guia, rg.origen, 
		CURRENT_DATE, rg.codigobarra, NULL, sucursal 
		FROM recepcion_tmp AS rg
		INNER JOIN tmp_seleccionadas tp ON rg.guia = tp.folioguia
		WHERE  rg.idusuario = $_SESSION[IDUSUARIO] and rg.subido = 'S'
		and rg.sucursal=".$_SESSION[IDSUCURSAL]."
		GROUP BY rg.guia";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s = "select descripcion from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		//seguimiento para recepcion ventanilla y empresarial
		$s = "INSERT INTO seguimiento_guias
		(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
		SELECT r.guia,'$_SESSION[IDSUCURSAL]','',
		CONCAT(IF('$f->descripcion'=r.destino,'ALMACEN DESTINO','ALMACEN TRASBORDO'),

		IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), 
		CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','',''
		FROM recepcion_tmp AS r 
		INNER JOIN guiasventanilla g ON r.guia = g.id
		WHERE r.idusuario = '$_SESSION[IDUSUARIO]' AND r.subido = 'S' AND r.sucursal=".$_SESSION[IDSUCURSAL]."
		GROUP BY r.guia";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO seguimiento_guias
		(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
		SELECT r.guia,'$_SESSION[IDSUCURSAL]','',
		CONCAT(IF('$f->descripcion'=r.destino,'ALMACEN DESTINO','ALMACEN TRASBORDO'),
		IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), 
		CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','',''
		FROM recepcion_tmp AS r 
		INNER JOIN guiasempresariales g ON r.guia = g.id
		WHERE r.idusuario = '$_SESSION[IDUSUARIO]' AND r.subido = 'S' AND r.sucursal=".$_SESSION[IDSUCURSAL]."
		GROUP BY r.guia";
		mysql_query($s,$l) or die($s);
		//****************************************************
		
		
		
		$s = "UPDATE guiaventanilla_unidades		
		INNER JOIN recepcion_tmp ON guiaventanilla_unidades.idguia = recepcion_tmp.guia
		INNER JOIN guiasventanilla gv ON guiaventanilla_unidades.idguia = gv.id
		INNER JOIN tmp_seleccionadas tp ON gv.id = tp.folioguia
		AND guiaventanilla_unidades.paquete = recepcion_tmp.registro		
		SET guiaventanilla_unidades.proceso = IF(".$_SESSION[IDSUCURSAL]."=gv.idsucursaldestino, 'ALMACEN DESTINO', 'ALMACEN TRASBORDO'),
		gv.estado = IF(".$_SESSION[IDSUCURSAL]."=gv.idsucursaldestino, 'ALMACEN DESTINO', 'ALMACEN TRASBORDO'),
		guiaventanilla_unidades.unidad = ''
		WHERE recepcion_tmp.idusuario = $_SESSION[IDUSUARIO] and recepcion_tmp.subido = 'S'
		and recepcion_tmp.sucursal = $_SESSION[IDSUCURSAL]";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$fidx->folio = 6;
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN recepcion_tmp ON guiasempresariales_unidades.idguia = recepcion_tmp.guia
		AND guiasempresariales_unidades.paquete = recepcion_tmp.registro
		INNER JOIN guiasempresariales ge ON guiasempresariales_unidades.idguia = ge.id
		INNER JOIN tmp_seleccionadas tp ON ge.id = tp.folioguia
		SET guiasempresariales_unidades.proceso = IF(".$_SESSION[IDSUCURSAL]."=ge.idsucursaldestino,'ALMACEN DESTINO', 'ALMACEN TRASBORDO'),
		ge.estado = IF(".$_SESSION[IDSUCURSAL]."=ge.idsucursaldestino,'ALMACEN DESTINO', 'ALMACEN TRASBORDO'),
		guiasempresariales_unidades.unidad = ''
		WHERE recepcion_tmp.idusuario = $_SESSION[IDUSUARIO] and recepcion_tmp.subido = 'S'
		and recepcion_tmp.sucursal = $_SESSION[IDSUCURSAL]";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		#actualizar si las guias de almacen trasbordo vienen incompletas desde el embarque
		$s = "UPDATE guiasventanilla gv
		INNER JOIN embarquedemercancia_faltante rf ON gv.id = rf.guia
		INNER JOIN recepcionmercanciadetalle rd ON gv.id = rd.guia
		SET gv.estado = 'ALMACEN TRASBORDO INCOMPLETO'
		WHERE gv.estado = 'ALMACEN TRASBORDO' AND rd.recepcion = '$fidx->folio' AND rd.sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "UPDATE guiasempresariales gv
		INNER JOIN embarquedemercancia_faltante rf ON gv.id = rf.guia
		INNER JOIN recepcionmercanciadetalle rd ON gv.id = rd.guia
		SET gv.estado = 'ALMACEN TRASBORDO INCOMPLETO'
		WHERE gv.estado = 'ALMACEN TRASBORDO' AND rd.recepcion = '$fidx->folio' AND rd.sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		#actualizar si las guias estan incompletas en la recepccion
		$s = "UPDATE guiasventanilla gv
		INNER JOIN reportedanosfaltante rf ON gv.id = rf.guia and rf.faltante = 1
		INNER JOIN recepcionmercanciadetalle rd ON gv.id = rd.guia
		SET gv.estado = 'ALMACEN TRASBORDO INCOMPLETO'
		WHERE gv.estado = 'ALMACEN TRASBORDO' AND rd.recepcion = '$fidx->folio' AND rd.sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "UPDATE guiasempresariales gv
		INNER JOIN reportedanosfaltante rf ON gv.id = rf.guia and rf.faltante = 1
		INNER JOIN recepcionmercanciadetalle rd ON gv.id = rd.guia
		SET gv.estado = 'ALMACEN TRASBORDO INCOMPLETO'
		WHERE gv.estado = 'ALMACEN TRASBORDO' AND rd.recepcion = '$fidx->folio' AND rd.sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		/*Posiblemente esto no se use
		
		$s = "CREATE TEMPORARY TABLE paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'ALMACEN DESTINO','ALMACEN DESTINO INCOMPLETO') AS estado
		FROM guiasventanilla AS gv
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'EN ALMACEN' AND gv.id IN ($_GET[folios])
		GROUP BY gv.id";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "insert into paraactualizar
		SELECT gv.id, IF(gv.totalpaquetes = COUNT(gvu.id),'ALMACEN DESTINO','ALMACEN DESTINO INCOMPLETO') AS estado
		FROM guiasempresariales AS gv
		INNER JOIN guiasempresariales_unidades AS gvu ON gv.id = gvu.idguia
		AND gvu.proceso = 'EN ALMACEN' AND gv.id IN ($_GET[folios])
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
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));*/
		
		$s = "SELECT d.tipo, bs.ruta, bs.conductor1, bs.conductor2, bs.conductor3 FROM catalogoruta cr
		INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
		INNER JOIN bitacorasalida bs ON cr.id=bs.ruta
		WHERE bs.unidad ='".$_GET[unidad]."' AND d.sucursal=".$_GET[sucursal]." AND bs.folio = ".$_GET[foliobitacora]."";
		$t = mysql_query($s, $l) or die($s); 
		$ti = mysql_fetch_object($t);
		if($ti->tipo==3){
			$s = "UPDATE bitacorasalida SET status=1 WHERE unidad=UCASE('".$_GET[unidad]."') AND status=0";
			mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
			
			$s = "update catalogoruta set enuso=0 where id = $ti->ruta";
			mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
			
			$s = "update catalogounidad set embarcado='N', recepcionado = 'N', enuso=0, ubicacion = null 
			where numeroeconomico = UCASE('$_GET[unidad]')";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE recepcionregistroprecintosdetalle SET status=1 
			WHERE unidad='".$_GET[unidad]."' AND foliobitacora = ".$_GET[foliobitacora]."";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE recepcionregistroprecintos SET enruta = 1
			WHERE unidad='".$_GET[unidad]."' AND foliobitacora = ".$_GET[foliobitacora]."";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE catalogoempleado SET enunidad=0 
			WHERE id IN ('".$ti->conductor1."','".$ti->conductor2."','".$ti->conductor3."')";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "UPDATE reportedanosfaltante SET recepcion = ".$fidx->folio." 
		WHERE recepcion is null AND idusuario='".$_SESSION[IDUSUARIO]."'";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = mysql_query("SELECT * FROM reportedanosfaltante 
		WHERE recepcion = ".$fidx->folio."  AND sucursal = ".$_SESSION[IDSUCURSAL]."",$l);
		
		$sur = "SELECT prefijo FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";
		$su= mysql_query($sur,$l) or die($sur); $sucur = mysql_fetch_object($su);
		
		$s = "call proc_RegistroLogistica1('recepcion','".$_GET[ruta]."','".mysql_num_rows($s)."',
		".$_GET[foliobitacora].",UCASE('".$_GET[unidad]."'),'','".$_GET[fechahora]."',
		'','".$_GET[fechahora]."','".$ti->tipo."','".$sucur->prefijo."')";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT d.tipo FROM catalogoruta cr
		INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
		WHERE cr.id = ".$_GET[ruta]." AND d.sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		$s = "call proc_RegistroOperaciones('RECEPCION',".$_GET[foliobitacora].",".$f->tipo.",".$_SESSION[IDSUCURSAL].")";
		mysql_query($s,$l) or die($s);
		
		$s = "CALL proc_RegistroFranquiciasConceciones('recepcion', '', ".$_SESSION[IDSUCURSAL].", ".$fidx->folio.")";
		mysql_query($s,$l) or die($s);
		
		echo "guardado,".$fidx->folio;
	}
	
	if($_GET[accion]==10){//MOSTRAR DETALLE HISTORICO DAÑOS FALTANTES
		$s = "SELECT * FROM (
		SELECT 'FALTANTE' AS tipo,
		IF(m.nguia IS NOT NULL,'SI','NO') AS enqueja, IFNULL(m.folio,'') AS folioqueja, rep.guia, t.estado, t.destinatario, t.destino, t.origen,
		DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecharecepcion, rep.embarque as recepcion, rep.observaciones AS comentarios, 
		'EMBARQUE' AS segenero FROM embarquedemercancia_faltante rep
		INNER JOIN (SELECT gv.id AS guia, gv.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasventanilla AS gv
		INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		UNION
		SELECT ge.id AS guia, ge.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasempresariales AS ge
		INNER JOIN catalogocliente des ON ge.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id) t ON rep.guia=t.guia
		INNER JOIN embarquedemercancia rm ON rep.embarque = rm.folio
		LEFT JOIN moduloquejasdanosfaltantes m ON rep.guia = m.nguia
		WHERE ".(($_GET[sucursal]!="todas")? " rep.sucursal=".$_GET[sucursal]." AND " : "")." 
		rm.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY rep.guia
		union
		SELECT IF(rep.dano = 1,'DAÑO',IF(rep.faltante = 1,'FALTANTE',IF(rep.sobrante = 1,'SOBRANTE',''))) AS tipo,
		IF(m.nguia IS NOT NULL,'SI','NO') AS enqueja, IFNULL(m.folio,'') AS folioqueja, rep.guia, t.estado, t.destinatario, t.destino, t.origen,
		DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecharecepcion, rep.recepcion, rep.comentarios, 
		'RECEPCION' AS segenero FROM reportedanosfaltante rep
		INNER JOIN (SELECT gv.id AS guia, gv.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasventanilla AS gv
		INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		UNION
		SELECT ge.id AS guia, ge.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen
		FROM guiasempresariales AS ge
		INNER JOIN catalogocliente des ON ge.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id) t ON rep.guia=t.guia
		INNER JOIN recepcionmercancia rm ON rep.recepcion = rm.folio
		LEFT JOIN moduloquejasdanosfaltantes m ON rep.guia = m.nguia
		WHERE ".(($_GET[sucursal]!="todas")? " rep.sucursal=".$_GET[sucursal]." AND " : "")." 
		rm.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY rep.guia
		UNION
		SELECT IF(repo.faltante = 1,'FALTANTE','') AS tipo,
		IF(m.nguia IS NOT NULL,'SI','NO') AS enqueja, IFNULL(m.folio,'') AS folioqueja, repo.guia, t.estado, t.destinatario, t.destino, t.origen,
		DATE_FORMAT(t.fecha,'%d/%m/%Y') AS fecharecepcion, repo.folioentrega AS recepcion, repo.comentarios, 
		'OCURRE' AS segenero FROM reportedanosfaltanteocurre repo
		INNER JOIN (SELECT gv.id AS guia, gv.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen, r.fecha
		FROM guiasventanilla AS gv
		INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		INNER JOIN recepcionmercanciadetalle rd ON gv.id = rd.guia
		INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal
		UNION
		SELECT ge.id AS guia, ge.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
		sd.prefijo AS destino, so.prefijo AS origen, r.fecha
		FROM guiasempresariales AS ge
		INNER JOIN catalogocliente des ON ge.iddestinatario = des.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id
		INNER JOIN recepcionmercanciadetalle rd ON ge.id = rd.guia
		INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal) t ON repo.guia=t.guia
		LEFT JOIN moduloquejasdanosfaltantes m ON repo.guia = m.nguia
		WHERE ".(($_GET[sucursal]!="todas")? " repo.sucursal=".$_GET[sucursal]." AND " : "")." 
		t.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		GROUP BY repo.guia)f ";
		
		//die($s);
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$totales = 0;
		
		$r = mysql_query($s.$limite,$l) or die($s.$limite);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->sucursalorigen = cambio_texto($f->sucursalorigen);
			$f->sucursaldestino = cambio_texto($f->sucursaldestino);
			$f->tipo = cambio_texto($f->tipo);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}
	
	if($_GET[accion]==11){
		$s = "UPDATE recepcion_tmp SET estado=UCASE('".cambio_texto($_GET[estado])."') WHERE guia='".$_GET[folio]."'";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		
		$s = "SELECT registro,paquete,codigobarra,estado,guia
		FROM recepcion_tmp WHERE guia='".$_GET[folio]."' and subido='S' 
		and idusuario = $_SESSION[IDUSUARIO] and sucursal = ".$_SESSION[IDSUCURSAL]."";
		$re = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($re)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$f->estado = cambio_texto($f->estado);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));

	}else if($_GET[accion]==12){
		$s = "SELECT em.unidad, cr.descripcion, cr.id, CONCAT(cs.prefijo,'-',LPAD(em.folio,5,'0')) AS folio, 
		cs.descripcion AS sucursal, DATE_FORMAT(em.fecha, '%d/%m/%Y') AS fecha 
		FROM recepcionmercancia AS em 
		INNER JOIN catalogoruta AS cr ON em.ruta = cr.id
		INNER JOIN catalogosucursal AS cs ON em.idsucursal = cs.id		
		WHERE folio = $_GET[folio] and em.idsucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$datosembarque = str_replace("null",'""',json_encode($f));		
		
		$s = "(SELECT gv.sector, rd.guia, rd.origen, cs.descripcion AS destino
		FROM recepcionmercanciadetalle rd
		INNER JOIN guiasventanilla gv ON rd.guia = gv.id
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE recepcion = $_GET[folio] and rd.sucursal = $_SESSION[IDSUCURSAL])
		UNION
		(SELECT gv.sector, rd.guia, rd.origen, cs.descripcion AS destino
		FROM recepcionmercanciadetalle rd
		INNER JOIN guiasempresariales gv ON rd.guia = gv.id
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		WHERE recepcion = $_GET[folio] and rd.sucursal = $_SESSION[IDSUCURSAL])";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			$f->guia = cambio_texto($f->guia);
			$arre[] = $f;
		}
		$derecha = str_replace("null",'""',json_encode($arre));
		
		echo "({
				datosembarque:$datosembarque,
				derecha:$derecha
			})";
	}
?>