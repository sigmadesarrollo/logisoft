<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");

	if($_GET[accion]==1){
		$fecha = date('d/m/Y'); 
		$row = ObtenerFolio('bitacorasalida','webpmm');
		echo $row[0].",".$fecha;
		
	}else if($_GET[accion]==2){
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
		sucursal = ".$_SESSION[IDSUCURSAL].", usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp()";		
		mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
				
		$s = "UPDATE catalogounidad SET enuso=1 
		WHERE numeroeconomico IN(UCASE('".$_GET[unidad]."'),UCASE('".$_GET[remolque1]."'),UCASE('".$_GET[remolque2]."'))";
		mysql_query($s,$l) or die($s);
		
		$con = "INSERT INTO programacionrecepciondiaria
				(idbitacora,fechaprogramacion, unidad, ruta, hrllegada, hrsalida,sucursal,
				 tipo, usuario, fecha)
				VALUES ('".$f->foliobitacora."',CURRENT_DATE(),'".$f->unidad."','".$f->id."',
				'".$f->llegada."','".$f->salida."',
				".$_GET[sucursal].",".$f->tipo.",
				'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";
				$s = mysql_query(str_replace("''","'00:00:00'",$con),$l)
				or die(mysql_error($l)."<br>$con<br>".__LINE__);
		
		echo "guardo,".$folio;
		
	}else if($_GET[accion]==3){
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
		sucursal = ".$_SESSION[IDSUCURSAL].", usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp()
		WHERE folio =".$_GET[folio];
		mysql_query($s,$l) or die($s);		

		if($_GET[unidad]!=$_GET[h_unidad]){
			$s = "UPDATE catalogounidad SET enuso=0 
			WHERE numeroeconomico = '".$_GET[h_unidad]."'";
			mysql_query($s,$l) or die($s);
		}
		
		if($_GET[remolque1]!=$_GET[h_remolque1]){
			$s = "UPDATE catalogounidad SET enuso=0 
			WHERE numeroeconomico = '".$_GET[h_remolque1]."'";
			mysql_query($s,$l) or die($s);
		}
		
		if($_GET[remolque2]!=$_GET[h_remolque2]){
			$s = "UPDATE catalogounidad SET enuso=0 
			WHERE numeroeconomico = '".$_GET[h_remolque2]."'";
			mysql_query($s,$l) or die($s);
		}
				
		$s = "UPDATE catalogounidad SET enuso=1 
		WHERE numeroeconomico IN(UCASE('".$_GET[unidad]."'),UCASE('".$_GET[remolque1]."'),UCASE('".$_GET[remolque2]."'))";
		mysql_query($s,$l) or die($s);
		
		echo "guardo";
		
	}else if($_GET[accion]==4){
		$s = "UPDATE bitacorasalida SET cancelada=1 WHERE folio=".$_GET[bitacora];
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM bitacorasalida WHERE folio=".$_GET[bitacora];
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "UPDATE catalogounidad SET enuso=0 
		WHERE numeroeconomico IN(UCASE('".cambio_texto($f->unidad)."'),UCASE('".cambio_texto($f->remolque1)."'),UCASE('".cambio_texto($f->remolque2)."'))";
		mysql_query($s,$l) or die($s);
		
		echo "guardo";
	}

?>