<?	session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_POST[accion]==2){
		
		$losfolios = "'".str_replace(",","','",$_POST['folios'])."'";
		
		$s = "SELECT id FROM guiasventanilla 
		WHERE id IN($losfolios) AND 
		estado IN('ENTREGADA','POR ENTREGAR')
		UNION
		SELECT id FROM guiasempresariales 
		WHERE id IN($losfolios) AND 
		estado IN('ENTREGADA','POR ENTREGAR')";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die("Existen guias que ya han sido entregadas");
		}
		
		
		$slq="INSERT INTO entregasocurre (folio,idsucursal,sucursal,nguia,cliente,nombre,recepcion,chkliste,cartaporte,contrarecibocarga,facturascarga,recibomaniobras,
			total,efectivo,cheque,banco,nocheque,tarjeta,transferencia, tipodeidentificacion,numeroidentificacion,personaquerecibe,usuario, idusuario,fecha)	VALUES
			(obtenerFolio('entregasocurre',".$_SESSION[IDSUCURSAL]."),'".$_POST['idsucursal']."',
			UCASE('".$_POST['sucursal']."'),UCASE('".$_POST['nguia']."'),'".$_POST['cliente']."',
			UCASE('".$_POST['nombre']."'),
			UCASE('".$_POST['recepcion']."'),
			UCASE('".$_POST['chkliste']."'),
			UCASE('".$_POST['cartaporte']."'),
			UCASE('".$_POST['contrarecibocarga']."'),
			UCASE('".$_POST['facturascarga']."'),
			UCASE('".$_POST['recibomaniobras']."'),
			'".$_POST['total']."',	'".$_POST['efectivo']."', '".$_POST['cheque']."',
			'".$_POST['banco']."', '".$_POST['ncheque']."', '".$_POST['tarjeta']."',
			'".$_POST['transferencia']."',	'".$_POST['identificacion']."',
			'".$_POST['nidentificacion']."', UCASE('".$_POST['precibe']."'), '".$_SESSION['NOMBREUSUARIO']."',
			'".$_SESSION['IDUSUARIO']."', CURRENT_DATE)";
		$s=mysql_query(str_replace("''","NULL",$slq),$l) or die($slq." <BR> Error en la linea ".__LINE__);
		$folio=mysql_insert_id();
		
		
		$s = "UPDATE cartaporte SET IDEntregaOcurre= ".$folio."
		WHERE Folio = '".$_POST['nguia']."'";
		mysql_query($s,$l) or die($s);

		
		$s = "UPDATE entregasocurre SET firma = (SELECT firma FROM entregasocurrefirma WHERE id = '".$_POST[firma]."')
		WHERE id = ".$folio."";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT folio FROM entregasocurre WHERE id = ".$folio;
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
		
		if($_POST[total] > 0){
			$s = "INSERT INTO formapago SET guia='$fo->folio',procedencia='O',tipo='X',
			total='$_POST[total]',efectivo='$_POST[efectivo]',tarjeta='$_POST[tarjeta]',
			transferencia='$_POST[transferencia]',cheque='$_POST[cheque]',
			ncheque='$_POST[ncheque]',banco='$_POST[banco]',
			notacredito='$_POST[nc]',nnotacredito='$_POST[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',
			usuario='$_SESSION[IDUSUARIO]',fecha=current_date, cliente = '".$_POST[cliente]."'";
		mysql_query(str_replace("''","null",$s),$l) or die($s);
		}
			$guia=split(",",$_POST['folios']);
			$num=count($guia);
			for($i=0;$i<$num;$i++){
				$detalle="INSERT INTO entregasocurre_detalle(entregaocurre,guia,usuario,idusuario,fecha,sucursal)
				VALUES 
				('".$fo->folio."','".$guia[$i]."','".$_SESSION['NOMBREUSUARIO']."',
				'".$_SESSION['IDUSUARIO']."',CURRENT_DATE,".$_SESSION[IDSUCURSAL].")";
				
				$res_detalle=mysql_query(str_replace("''","NULL",$detalle),$l)or die($detalle." <BR> Error en la linea ".__LINE__);
				
				$estadoguia="UPDATE guiasventanilla SET estado ='POR ENTREGAR', fechaentrega=current_date WHERE id='".$guia[$i]."'";				
				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);
				
				$estadoguia="UPDATE guiasempresariales SET estado ='POR ENTREGAR', fechaentrega=current_date WHERE id='".$guia[$i]."'";				
				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);
			}
			
			$s = "UPDATE pagoguias AS pg
			INNER JOIN entregasocurre_detalle AS eo ON pg.guia = eo.guia
			SET pg.pagado = 'S', pg.sucursalcobro='$_SESSION[IDSUCURSAL]', fechapago = CURRENT_DATE,
			pg.usuariocobro = '$_SESSION[IDUSUARIO]'
			WHERE eo.sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_POST[folio]'
			AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]' AND pg.credito='NO'";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM entregasocurre_detalle 
			WHERE entregaocurre = ".$fo->folio." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$e = mysql_query($s,$l) or die($s);
			while($ead = mysql_fetch_object($e)){
				$s = "CALL proc_ReporteProductividad('OCURRE','','".$ead->guia."','".$_POST[fechahora]."',
				".$_SESSION[IDSUCURSAL].")";
				mysql_query($s,$l) or die($s);
			}
			
		echo "guardado,".$fo->folio;
		
	}else if($_POST[accion]==4){
		
		
		
		$slq="UPDATE entregasocurre
			SET total='$_POST[total]', efectivo='$_POST[efectivo]', cheque='$_POST[cheque]', banco='$_POST[banco]',recepcion='$_POST[recepcion]',chkliste='$_POST[chkliste]',cartaporte='$_POST[cartaporte]',  
			contrarecibocarga='$_POST[contrarecibocarga]',facturascarga='$_POST[facturascarga]',recibomaniobras='$_POST[recibomaniobras]',
			nocheque='$_POST[ncheque]', tarjeta='$_POST[tarjeta]', transferencia='$_POST[transferencia]'
			where folio = '$_POST[folio]' and idsucursal = '$_SESSION[IDSUCURSAL]'";
		$s=mysql_query(str_replace("''","NULL",$slq),$l) or die($slq." <BR> Error en la linea ".__LINE__);
		
		$s = "select id from entregasocurre where folio = '$_POST[folio]' and idsucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$idocurre = $f->id;
		
		$s = "delete from formapago where guia='$_POST[folio]' and sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		if($_POST[total] > 0){
			
			$s = "INSERT INTO formapago SET guia='$_POST[folio]',procedencia='O',tipo='X',
			total='$_POST[total]',efectivo='$_POST[efectivo]',tarjeta='$_POST[tarjeta]',
			transferencia='$_POST[transferencia]',cheque='$_POST[cheque]',
			ncheque='$_POST[ncheque]',banco='$_POST[banco]',
			notacredito='$_POST[nc]',nnotacredito='$_POST[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',
			usuario='$_SESSION[IDUSUARIO]',fecha=current_date, cliente = '".$_POST[cliente]."'";
			mysql_query(str_replace("''","null",$s),$l) or die($s);
		}
		
		$s = "UPDATE guiasventanilla AS gv
			INNER JOIN entregasocurre_detalle AS eo ON gv.id = eo.guia
			SET gv.estado = 'ALMACEN DESTINO'
			WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_POST[folio]'";
			mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales AS gv
			INNER JOIN entregasocurre_detalle AS eo ON gv.id = eo.guia
			SET gv.estado = 'ALMACEN DESTINO'
			WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_POST[folio]'";
			mysql_query($s,$l) or die($s);
		
		$s = "UPDATE pagoguias AS pg
		INNER JOIN entregasocurre_detalle AS eo ON pg.guia = eo.guia
		SET pg.pagado = 'N', pg.sucursalcobro=null, fechapago = null,
		pg.usuariocobro = null
		WHERE eo.sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_POST[folio]'
		AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]' AND pg.credito='NO'";
		mysql_query($s,$l) or die($s);
		
		$s = "delete from entregasocurre_detalle where sucursal = '$_SESSION[IDSUCURSAL]' AND entregaocurre = '$_POST[folio]'";
		mysql_query($s,$l) or die($s);
	
		if($_POST['folios']==""){
			$guia=split(",",$_POST['folios']);
			$num=count($guia);
			for($i=0;$i<$num;$i++){
				$detalle="INSERT INTO entregasocurre_detalle(entregaocurre,guia,usuario,idusuario,fecha,sucursal)
				VALUES 
				('".$idocurre."','".$guia[$i]."','".$_SESSION['NOMBREUSUARIO']."',
				'".$_SESSION['IDUSUARIO']."',CURRENT_DATE,".$_SESSION[IDSUCURSAL].")";
				
				$res_detalle=mysql_query(str_replace("''","NULL",$detalle),$l)or die($detalle." <BR> Error en la linea ".__LINE__);
				
				$estadoguia="UPDATE guiasventanilla SET estado ='POR ENTREGAR', fechaentrega=current_date WHERE id='".$guia[$i]."'";				
				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);
				
				$estadoguia="UPDATE guiasempresariales SET estado ='POR ENTREGAR', fechaentrega=current_date WHERE id='".$guia[$i]."'";				
				$estado_guia=mysql_query($estadoguia,$l) or die($estadoguia." Error en la linea ".__LINE__);
			}
		}
			$s = "UPDATE pagoguias AS pg
			INNER JOIN entregasocurre_detalle AS eo ON pg.guia = eo.guia
			SET pg.pagado = 'S', pg.sucursalcobro='$_SESSION[IDSUCURSAL]', fechapago = CURRENT_DATE,
			pg.usuariocobro = '$_SESSION[IDUSUARIO]'
			WHERE eo.sucursal = '$_SESSION[IDSUCURSAL]' AND eo.entregaocurre = '$_POST[folio]'
			AND pg.sucursalacobrar = '$_SESSION[IDSUCURSAL]' AND pg.credito='NO'";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM entregasocurre_detalle 
			WHERE entregaocurre = ".$_POST[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$e = mysql_query($s,$l) or die($s);
			while($ead = mysql_fetch_object($e)){
				$s = "CALL proc_ReporteProductividad('OCURRE','','".$ead->guia."','".$_POST[fechahora]."',
				".$_SESSION[IDSUCURSAL].")";
				mysql_query($s,$l) or die($s);
			}
		
		if($_POST['folios']==""){
			$s = "delete from entregasocurre where folio = '$_POST[folio]' and idsucursal = '$_SESSION[IDSUCURSAL]'";
			mysql_query($s,$l) or die($s);
		}
			
		
			echo "guardado,".$_POST[folio];
	}
?>