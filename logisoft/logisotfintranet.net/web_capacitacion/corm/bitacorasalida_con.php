<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");

	if($_GET[accion]==1){
		$fecha = date('d/m/Y'); 
		$s = "DELETE FROM recepcionregistroprecintosdetalle_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$row = ObtenerFolio('bitacorasalida','webpmm');
		echo $row[0].",".$fecha;
		
	}else if($_GET[accion]==2){
		
		$unidad1 = "NULL";
		$unidad2 = "NULL";
		$unidad3 = "NULL";
		
		if($_GET[unidad]!="")
			$unidad1 = "SELECT CONCAT('La unidad ',unidad,' esta registrada en la bitacora ',folio) 
					   FROM bitacorasalida WHERE unidad='$_GET[unidad]' AND status = 0 AND cancelada <> 1 LIMIT 1";
		if($_GET[remolque1]!="")
			$unidad2 = "SELECT CONCAT('La unidad ',remolque1,' esta registrada en la bitacora ',folio) 
					   FROM bitacorasalida WHERE remolque1='$_GET[remolque1]' AND status = 0 AND cancelada <> 1 LIMIT 1";
		if($_GET[remolque2]!="")
			$unidad3 = "SELECT CONCAT('La unidad ',remolque2,' esta registrada en la bitacora ',folio) 
					   FROM bitacorasalida WHERE remolque2='$_GET[remolque2]' AND status = 0 AND cancelada <> 1 LIMIT 1";
		
		$s = "SELECT * FROM (
		SELECT
		($unidad1) unidad,
		($unidad2) remolque1,
		($unidad3) remolque2
		) as t1
		where not isnull(unidad) or not isnull(remolque1) or not isnull(remolque2)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			die($f->unidad."<br>".$f->remolque1."<br>".$f->remolque2);
		}
		
		$s = "INSERT INTO bitacorasalida SET
		fechabitacora = current_timestamp(),
		conductor1 = '".$_GET[conductor1]."', licencia_conductor1 = '".$_GET[licencia1]."',
		conductor2 = '".$_GET[conductor2]."', licencia_conductor2 = '".$_GET[licencia2]."',
		conductor3 = '".$_GET[conductor3]."', licencia_conductor3 = '".$_GET[licencia3]."', 
		unidad = UCASE('".$_GET[unidad]."'),  tarjeta_unidad = '".$_GET[tarjeta_unidad]."', 
		poliza_unidad = '".$_GET[poliza_unidad]."', vrf_unidad = '".$_GET[vrf_unidad]."',
		pcd_unidad = '".$_GET[pcd_unidad]."', remolque1 = UCASE('".$_GET[remolque1]."'),
		tarjeta_remolque1 = '".$_GET[tarjeta_remolque1]."', poliza_remolque1 = '".$_GET[poliza_remolque1]."',
		pcd_remolque1 = '".$_GET[pcd_remolque1]."', remolque2 = UCASE('".$_GET[remolque2]."'),
		tarjeta_remolque2 = '".$_GET[tarjeta_remolque2]."', poliza_remolque2 = '".$_GET[poliza_remolque2]."',
		pcd_remolque2 = '".$_GET[pcd_remolque2]."', ruta = '".$_GET[ruta]."', gastos = '".$_GET[gastos]."',
		Nombre_Cliente = '".$_GET[Nombre_Cliente]."', id_cliente = '".$_GET[id_cliente]."', fecha_Bodega = '".$_GET[fecha_Bodega]."',Hora_Bodega = '".$_GET[Hora_Bodega]."',
		sucursal = ".$_SESSION[IDSUCURSAL].", usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp()";		
		mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
				
		$s = "UPDATE catalogounidad SET enuso=1
		WHERE numeroeconomico IN(UCASE('".$_GET[unidad]."'),UCASE('".$_GET[remolque1]."'),UCASE('".$_GET[remolque2]."'))";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE catalogoempleado SET enunidad=1 
		WHERE id IN ('".$_GET[conductor1]."','".$_GET[conductor2]."','".$_GET[conductor3]."')";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE catalogounidad SET embarcado='N', recepcionado='S', 
		ubicacion=(SELECT sucursal FROM catalogorutadetalle WHERE ruta = '".$_GET[ruta]."' ORDER BY id ASC LIMIT 1)
		WHERE numeroeconomico = '".$_GET[unidad]."'";
		mysql_query($s,$l) or die($s);
		
		#se insertaran en programacion recepcion diara para que aparesca la unidad
		#en todas las sucursales
		$s = "SELECT bs.folio, bs.unidad, bs.ruta, '00:00:00' AS llegada, 
		'00:00:00' AS salida, crd.sucursal, crd.tipo
		FROM bitacorasalida bs
		INNER JOIN catalogorutadetalle crd ON bs.ruta = crd.ruta
		WHERE bs.folio = $folio";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			/*$con = "INSERT INTO programacionrecepciondiaria
			(idbitacora,fechaprogramacion, unidad, ruta, hrllegada, hrsalida,sucursal,
			 tipo, usuario, fecha)
			VALUES ('".$f->folio."',CURRENT_DATE(),'".$f->unidad."','".$f->ruta."',
			'".$f->llegada."','".$f->salida."',
			".$f->sucursal.",".$f->tipo.",
			'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";
			$s = mysql_query(str_replace("''","'00:00:00'",$con),$l)
			or die(mysql_error($l)."<br>$con<br>".__LINE__);*/
					$con = "INSERT INTO programacionrecepciondiaria
			(idbitacora,fechaprogramacion, unidad, ruta, hrllegada, hrsalida,sucursal,
			 tipo, usuario, fecha)
			VALUES ('".$f->folio."',CURRENT_DATE(),'".$f->unidad."','".$f->ruta."',
			'".$f->llegada."','".$f->salida."',
			".$_SESSION[IDSUCURSAL].",".$f->tipo.",
			'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";
			$s = mysql_query(str_replace("''","'00:00:00'",$con),$l)
			or die(mysql_error($l)."<br>$con<br>".__LINE__);
		}
								
		$s = "INSERT INTO recepcionregistroprecintosdetalle
		SELECT 0 as id, ".$folio." AS foliobitacora, unidad, remolque, precinto, ubicacion, 
		fechaasignado, idusuario, usuario, fecha, status,tipo, sucursal FROM recepcionregistroprecintosdetalle_tmp
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM recepcionregistroprecintosdetalle_tmp 
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){			
			$s = "UPDATE asignacionprecintosdetalle SET utilizado=1
			WHERE sucursal=".$_SESSION[IDSUCURSAL]." AND folios=".$f->precinto."";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "SELECT prefijo FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";
		$su= mysql_query($s,$l) or die($s); $sucur = mysql_fetch_object($su);
		
		$s = "call proc_RegistroLogistica1('bitacora','".$_GET[ruta]."','',".$folio.",
		UCASE('".$_GET[unidad]."'),'".$_GET[fechahora]."','','','',0,'".$sucur->prefijo."')";
		mysql_query($s,$l) or die($s);
		
		$s = "call proc_RegistroOperaciones('BITACORA',".$folio.",0,".$_SESSION[IDSUCURSAL].")";
		mysql_query($s,$l) or die($s);
		
		echo "guardo,".$folio;
		
	}else if($_GET[accion]==3){
		$s = mysql_query("SELECT * FROM embarquedemercancia WHERE foliobitacora = ".$_GET[folio]."",$l);
		if(mysql_num_rows($s)>0){		
			die("embarcada");
		}
	
		$s = "UPDATE bitacorasalida SET
		fechabitacora = '".cambiaf_a_mysql($_GET[fechabitacora])."',
		conductor1 = '".$_GET[conductor1]."', licencia_conductor1 = '".$_GET[licencia1]."',
		conductor2 = '".$_GET[conductor2]."', licencia_conductor2 = '".$_GET[licencia2]."',
		conductor3 = '".$_GET[conductor3]."', licencia_conductor3 = '".$_GET[licencia3]."', 
		unidad = UCASE('".$_GET[unidad]."'),  tarjeta_unidad = '".$_GET[tarjeta_unidad]."', 
		poliza_unidad = '".$_GET[poliza_unidad]."', vrf_unidad = '".$_GET[vrf_unidad]."',
		pcd_unidad = '".$_GET[pcd_unidad]."', remolque1 = UCASE('".$_GET[remolque1]."'),
		tarjeta_remolque1 = '".$_GET[tarjeta_remolque1]."', poliza_remolque1 = '".$_GET[poliza_remolque1]."',
		pcd_remolque1 = '".$_GET[pcd_remolque1]."', remolque2 = UCASE('".$_GET[remolque2]."'),
		tarjeta_remolque2 = '".$_GET[tarjeta_remolque2]."', poliza_remolque2 = '".$_GET[poliza_remolque2]."',
		pcd_remolque2 = '".$_GET[pcd_remolque2]."', ruta = '".$_GET[ruta]."', gastos = '".$_GET[gastos]."',
		Nombre_Cliente = '".$_GET[Nombre_Cliente]."', id_cliente = '".$_GET[id_cliente]."', fecha_Bodega = '".$_GET[fecha_Bodega]."',Hora_Bodega = '".$_GET[Hora_Bodega]."',
		sucursal = ".$_SESSION[IDSUCURSAL].", usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp()
		WHERE folio =".$_GET[folio];
		mysql_query($s,$l) or die($s);		

		if($_GET[unidad]!=$_GET[h_unidad]){
			$s = "UPDATE catalogounidad SET enuso=0 
			WHERE numeroeconomico = '".$_GET[h_unidad]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE catalogounidad SET enuso=1 
			WHERE numeroeconomico = UCASE('".$_GET[unidad]."')";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE catalogounidad SET embarcado='N', recepcionado='S', 
			ubicacion=(SELECT sucursal FROM catalogorutadetalle WHERE ruta = '".$_GET[ruta]."' ORDER BY id ASC LIMIT 1)
			WHERE numeroeconomico = '".$_GET[unidad]."'";
			mysql_query($s,$l) or die($s);
		}
		
		if($_GET[remolque1]!=$_GET[h_remolque1]){
			$s = "UPDATE catalogounidad SET enuso=0 
			WHERE numeroeconomico = '".$_GET[h_remolque1]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE catalogounidad SET enuso=1 
			WHERE numeroeconomico = UCASE('".$_GET[remolque1]."')";
			mysql_query($s,$l) or die($s);
		}
		
		if($_GET[remolque2]!=$_GET[h_remolque2]){
			$s = "UPDATE catalogounidad SET enuso=0 
			WHERE numeroeconomico = '".$_GET[h_remolque2]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE catalogounidad SET enuso=1 
			WHERE numeroeconomico = UCASE('".$_GET[remolque2]."')";
			mysql_query($s,$l) or die($s);
		}
				
		/*$s = "UPDATE catalogounidad SET enuso=1 
		WHERE numeroeconomico IN(UCASE('".$_GET[unidad]."'),UCASE('".$_GET[remolque1]."'),UCASE('".$_GET[remolque2]."'))";
		mysql_query($s,$l) or die($s);*/
		
		
		if($_GET[conductor1]!=$_GET[h_conductor1]){
			$s = "UPDATE catalogoempleado SET enunidad=0 
			WHERE id = '".$_GET[h_conductor1]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE catalogoempleado SET enunidad=1 
			WHERE id = '".$_GET[conductor1]."'";
			mysql_query($s,$l) or die($s);
		}
		
		if($_GET[conductor2]!=$_GET[h_conductor2]){
			$s = "UPDATE catalogoempleado SET enunidad=0 
			WHERE id = '".$_GET[h_conductor2]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE catalogoempleado SET enunidad=1 
			WHERE id = '".$_GET[conductor2]."'";
			mysql_query($s,$l) or die($s);
		}
		
		if($_GET[conductor3]!=$_GET[h_conductor3]){
			$s = "UPDATE catalogoempleado SET enunidad=0 
			WHERE id = '".$_GET[h_conductor3]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE catalogoempleado SET enunidad=1 
			WHERE id = '".$_GET[conductor3]."'";
			mysql_query($s,$l) or die($s);
			
		}
		
		#se insertaran en programacion recepcion diara para que aparesca la unidad
		#en todas las sucursales
		
		$s = "DELETE FROM programacionrecepciondiaria WHERE idbitacora = ".$_GET[folio]."";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT bs.folio, bs.unidad, bs.ruta, '00:00:00' AS llegada, 
		'00:00:00' AS salida, crd.sucursal, crd.tipo
		FROM bitacorasalida bs
		INNER JOIN catalogorutadetalle crd ON bs.ruta = crd.ruta
		WHERE bs.folio = ".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$con = "INSERT INTO programacionrecepciondiaria
			(idbitacora,fechaprogramacion, unidad, ruta, hrllegada, hrsalida,sucursal,
			 tipo, usuario, fecha)
			VALUES ('".$f->folio."',CURRENT_DATE(),'".$f->unidad."','".$f->ruta."',
			'".$f->llegada."','".$f->salida."', ".$f->sucursal.",".$f->tipo.",
			'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";
			$s = mysql_query(str_replace("''","'00:00:00'",$con),$l)
			or die(mysql_error($l)."<br>$con<br>".__LINE__);
		}
		
		$s = "SELECT * FROM recepcionregistroprecintosdetalle WHERE foliobitacora = ".$_GET[folio];
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "UPDATE asignacionprecintosdetalle SET utilizado=0
			WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND folios=".$f->precinto."";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "DELETE FROM recepcionregistroprecintosdetalle WHERE foliobitacora = ".$_GET[folio];
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO recepcionregistroprecintosdetalle
		SELECT 0 as id, ".$_GET[folio]." AS foliobitacora, unidad, remolque, precinto, ubicacion, 
		fechaasignado, idusuario, usuario, fecha, status,tipo, sucursal FROM recepcionregistroprecintosdetalle_tmp
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM recepcionregistroprecintosdetalle_tmp 
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){			
			$s = "UPDATE asignacionprecintosdetalle SET utilizado=1
			WHERE sucursal=".$_SESSION[IDSUCURSAL]." AND folios=".$f->precinto."";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "SELECT prefijo FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";
		$su= mysql_query($s,$l) or die($s); $sucur = mysql_fetch_object($su);
		
		$s = "call proc_RegistroLogistica1('bitacora','".$_GET[ruta]."','',".$_GET[folio].",
		UCASE('".$_GET[unidad]."'),'".$_GET[fechahora]."','','','',0,'".$sucur->prefijo."')";
		mysql_query($s,$l) or die($s);
		
		$s = "call proc_RegistroOperaciones('BITACORA',".$_GET[folio].",0,".$_SESSION[IDSUCURSAL].")";
		mysql_query($s,$l) or die($s);
		
		echo "guardo";
		
	}else if($_GET[accion]==4){
		$s = mysql_query("SELECT * FROM embarquedemercancia WHERE foliobitacora = ".$_GET[folio]."",$l);
		if(mysql_num_rows($s)>0){		
			die("embarcada");
		}
		
		$s = "UPDATE bitacorasalida SET cancelada=1 WHERE folio=".$_GET[bitacora];
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE catalogounidad cu
		INNER JOIN bitacorasalida b ON cu.numeroeconomico = b.unidad OR 
			cu.numeroeconomico = b.remolque1 OR cu.numeroeconomico = b.remolque2
		SET cu.enuso = 0, cu.embarcado='N', cu.recepcionado='N', cu.ubicacion=NULL
		WHERE b.folio='".$_GET[bitacora]."'";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "UPDATE catalogoempleado ce
		INNER JOIN bitacorasalida b ON ce.id = b.conductor1 OR
			ce.id = b.conductor2 OR ce.id = b.conductor3
		SET ce.enunidad=0
		WHERE b.folio='".$_GET[bitacora]."'";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "DELETE FROM programacionrecepciondiaria WHERE idbitacora = ".$_GET[bitacora]."";
		mysql_query($s,$l) or die($s);
		
		echo "guardo";
		
	}else if($_GET[accion] == 5){
		if($_GET[tipo]=="grabar"){
			$s = "INSERT INTO recepcionregistroprecintosdetalle_tmp SET
			precinto = ".$_GET[precinto].", unidad = UCASE('".$_GET[unidad]."'),
			fechaasignado = '".cambiaf_a_mysql($_GET[fechaasignado])."', tipo = 'bitacora',
			idusuario=".$_SESSION[IDUSUARIO].",usuario='".$_SESSION[NOMBREUSUARIO]."',
			fecha='".$_GET[fecha]."', sucursal = ".$_SESSION[IDSUCURSAL]."";
		
		}else if($_GET[tipo]=="modificar"){
			$s = "UPDATE recepcionregistroprecintosdetalle_tmp SET
			precinto = ".$_GET[precinto].", unidad = UCASE('".$_GET[unidad]."'),
			fechaasignado = '".cambiaf_a_mysql($_GET[fechaasignado])."', tipo = 'bitacora'
			WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."'";
			
		}else if($_GET[tipo]=="eliminar"){
			$s = "DELETE FROM recepcionregistroprecintosdetalle_tmp 
			WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."'";
		}
		$r = mysql_query($s,$l) or die($s);
		echo "ok";
		
	}else if($_GET[accion]==6){
		$s = "INSERT INTO recepcionregistroprecintosdetalle_tmp
		SELECT 0 AS id, unidad, remolque, precinto, ubicacion, fechaasignado, ".$_SESSION[IDUSUARIO].", 
		usuario, fecha, status,tipo, sucursal FROM recepcionregistroprecintosdetalle
		WHERE foliobitacora = ".$_GET[folio]." AND tipo='bitacora'";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT precinto, DATE_FORMAT(fechaasignado,'%d/%m/%Y') AS fechaasignado,
		fecha FROM recepcionregistroprecintosdetalle_tmp 
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==7){
		$s = "SELECT d.folios, cs.prefijo as sucursal
			FROM asignacionprecintosdetalle d 
			INNER JOIN catalogosucursal cs ON d.sucursal=cs.id
			WHERE d.sucursal = $_GET[sucursal] AND utilizado=0 AND d.folios=$_GET[precinto] AND
			".(($_GET[tipo]=="bitacora")? " NOT EXISTS 
			(SELECT * FROM recepcionregistroprecintosdetalle_tmp r WHERE r.precinto = d.folios)" : " NOT EXISTS 
			(SELECT * FROM recepcionregistroprecintosdetalle r WHERE r.precinto = d.folios)");
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}
?>