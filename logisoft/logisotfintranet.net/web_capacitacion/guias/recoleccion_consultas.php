<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]=="0"){//BORRAR TEMPORALES
		$s = "DELETE FROM recolecciondetalle_tmp WHERE idusuario=".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM recolecciondetallefolioempresariales WHERE recoleccion IS NULL AND idusuario=".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM recolecciondetallefoliorecoleccion WHERE recoleccion IS NULL AND idusuario=".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		
		$folio = obtenerFolioRecoleccion($_GET[sucursal],'');
		
		$principales = "";
		$s = "SELECT DATE_FORMAT(CURRENT_DATE , '%d/%m/%Y') as fecha, id, descripcion FROM catalogosucursal 
		".(($_GET['sucursal']==1)?"":" WHERE  id = ".$_GET[sucursal]."")." -- AND subdestinos=1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
			$f->descripcion = cambio_texto($f->descripcion);			
			$f->folio = $folio;
			
		$principales = str_replace('null','""',json_encode($f));
		
		$origenes = "";
		$s = "SELECT cd.id, cd.descripcion FROM catalogosucursal cs
		INNER JOIN catalogodestino cd ON cs.id = cd.sucursal
		WHERE ".(($_GET['sucursal']==1)?"":"cs.id = ".$_GET[sucursal]." AND ")." cd.subdestinos=1";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
			$f->descripcion = cambio_texto($f->descripcion);			
		
		$origenes = str_replace('null','""',json_encode($f));
		
		$origen = "";	
		$s = "SELECT id, descripcion AS destino FROM catalogodestino 
		".(($_GET['sucursal']==1)?"":" WHERE sucursal=".$_GET[sucursal]."");
		$registros = array();
		$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$f->destino = cambio_texto($f->destino);
				$registros[] = $f;
			}

		$origen = str_replace('null','""',json_encode($registros));
		
		$horarios = "";
		$s = "SELECT (SELECT horariolimiterecoleccion FROM catalogosucursal WHERE id=".$_GET['sucursal'].") AS horariolimite,
		DATE_FORMAT(ADDDATE(CURRENT_DATE,INTERVAL 1 DAY),'%d/%m/%Y') AS fechasig";
		$r = mysql_query($s,$l) or die($s);
		$h = mysql_fetch_object($r);
		
		$horarios = str_replace('null','""',json_encode($h));
		
		echo "({principal:$principales, origen:$origen, horarios:$horarios, origenes:$origenes})";		
		
	}else if($_GET[accion]==1){//GUARDAR Y MODIFICAR	
		if($_GET[tip]=="grabar"){
			$s = "INSERT INTO cartaporte
			( Folio, Fecha, IDCliente, DestinoNombre, OrigenNombre, Origen, Destino, ValorUnitario, ValorDeclarado, TipoViaje,
			CondicionesPago, CuotaTonelada, IDRecoleccion, Usuario)
			values 
			('".$_GET[folio]."','".cambiaf_a_mysql($_GET[fecha])."',
			'".$_GET[idcliente]."',
			'".$_GET[destino]."','".trim($_GET[origennombre])."',
			'".trim($_GET[desde])."','".$_GET[hasta]."',
			'".$_GET[valorunitario]."','".$_GET[valordeclarado]."',
			'".$_GET[tiporviaje]."','".$_GET[condicionespago]."',
			'".$_GET[cuotatonelada]."','".$_GET[IDRecoleccion]."',
			'".$_SESSION[NOMBREUSUARIO]."')";
			mysql_query($s,$l) or die($s);
			$id = mysql_insert_id($l);
			
			$s = "Update recoleccion set FolioCarta = '".$_GET[folio]."' where folio ='".$_GET[IDRecoleccion]."'";
			mysql_query($s,$l) or die($s);

			$s = mysql_query("DELETE FROM recolecciondetalle 
			WHERE recoleccion='".$_GET[IDRecoleccion]."' AND sucursal=".$_GET[idsucursal]."",$l);
			
			//$folio = (($_GET[idsucursal]!=$_GET[sucursalant])?obtenerFolioRecoleccion($_GET[idsucursal],cambiaf_a_mysql($_GET[fecha])):$_GET[folioant]);
			$s = "INSERT INTO recolecciondetalle
			SELECT 0 AS id,'".$_GET[IDRecoleccion]."' AS recoleccion,
			'".$_GET[idsucursal]."' AS sucursal,
			cantidad, iddescripcion,descripcion,contenido,peso,largo,ancho,alto,volumen,
			pesototal,pesounit,idusuario,fecha FROM recolecciondetalle_tmp
			WHERE idusuario=".$_SESSION[IDUSUARIO];
			mysql_query($s,$l) or die($s);			
			
			echo "guardo,".$folio;
			
		}else{
			//$folio = (($_GET[idsucursal]!=$_GET[sucursalant])?"folio='".obtenerFolioRecoleccion($_GET[idsucursal],cambiaf_a_mysql($_GET[fecha]))."'," : "");
			$s = "UPDATE cartaporte SET
			Fecha = '".cambiaf_a_mysql($_GET[fecha])."',
			ValorUnitario = '".$_GET[valorunitario]."',
			ValorDeclarado = '".$_GET[valordeclarado]."',
			TipoViaje = '".$_GET[tiporviaje]."',
			CondicionesPago = '".$_GET[condicionespago]."',
			CuotaTonelada = '".$_GET[cuotatonelada]."',
			IDRecoleccion = '".$_GET[IDRecoleccion]."',
			Usuario = '".$_SESSION[NOMBREUSUARIO]."'
			WHERE FOlio='".$_GET[folio]."'
			";
			mysql_query($s,$l) or die($s);
			
			$s = "Update recoleccion set FolioCarta = '".$_GET[folio]."' where folio ='".$_GET[IDRecoleccion]."'";
			mysql_query($s,$l) or die($s);

			$s = mysql_query("DELETE FROM recolecciondetalle 
			WHERE recoleccion='".$_GET[IDRecoleccion]."' AND sucursal=".$_GET[idsucursal]."",$l);
			
			//$folio = (($_GET[idsucursal]!=$_GET[sucursalant])?obtenerFolioRecoleccion($_GET[idsucursal],cambiaf_a_mysql($_GET[fecha])):$_GET[folioant]);
			$s = "INSERT INTO recolecciondetalle
			SELECT 0 AS id,'".$_GET[IDRecoleccion]."' AS recoleccion,
			'".$_GET[idsucursal]."' AS sucursal,
			cantidad, iddescripcion,descripcion,contenido,peso,largo,ancho,alto,volumen,
			pesototal,pesounit,idusuario,fecha FROM recolecciondetalle_tmp
			WHERE idusuario=".$_SESSION[IDUSUARIO];
			mysql_query($s,$l) or die($s);		
			
	/*		if($_GET[idsucursal]!=$_GET[sucursalant]){
				$s = "DELETE FROM reporteproductividad2 WHERE foliorecoleccion = '".$_GET[folioant]."'
				AND sucursal = ".$_GET[sucursalant]."";
				mysql_query($s,$l) or die($s);
				
				$s = "CALL proc_ReporteProductividad('RECOLECCION','".$_GET['unidad']."', '".$folio."',
				current_timestamp(), ".$_GET[idsucursal].")";
				mysql_query($s,$l) or die($s);
			}else{
				$s = "DELETE FROM reporteproductividad2 WHERE foliorecoleccion = '".$_GET[folioant]."'
				AND sucursal = ".$_GET[sucursalant]."";
				mysql_query($s,$l) or die($s);
				
				$s = "CALL proc_ReporteProductividad('RECOLECCION','".$_GET['unidad']."', '".$_GET[folioant]."',
				current_timestamp(), ".$_GET[sucursalant].")";
				mysql_query($s,$l) or die($s);
			}
			
			$s = mysql_query("DELETE FROM recolecciondetalle 
			WHERE recoleccion='".$_GET[folioant]."' AND sucursal=".$_GET[sucursalant]."",$l);
			
			$folio = (($_GET[idsucursal]!=$_GET[sucursalant])?obtenerFolioRecoleccion($_GET[idsucursal],cambiaf_a_mysql($_GET[fecha])):$_GET[folioant]);
			
			$s = "INSERT INTO recolecciondetalle
			SELECT 0 AS id,'".(($_GET[idsucursal]!=$_GET[sucursalant])?$folio:$_GET[folioant])."' AS recoleccion,
			'".(($_GET[idsucursal]!=$_GET[sucursalant])?$_GET[idsucursal]:$_GET[sucursalant])."' AS sucursal,
			cantidad, iddescripcion,descripcion,contenido,peso,largo,ancho,alto,volumen,
			pesototal,pesounit,idusuario,fecha FROM recolecciondetalle_tmp
			WHERE idusuario=".$_SESSION[IDUSUARIO];
			mysql_query($s,$l) or die($s);			
		*/
			echo "modifico,".(($_GET[idsucursal]!=$_GET[idsucursal])?$folio:$_GET[folio]);
		}
		
	}else if($_GET[accion]==2){//TRANSMITIR
		$folio = (($_GET[idsucursal]!=$_GET[sucursalant])?"folio='".obtenerFolioRecoleccion($_GET[idsucursal],'')."'," : "");
		$s = "UPDATE recoleccion SET
		".$folio."
		estado='TRANSMITIDO', transmitida='SI', unidad=UCASE('".$_GET['unidad']."')
		WHERE folio='".(($_GET[idsucursal]!=$_GET[sucursalant])?obtenerFolioRecoleccion($_GET[idsucursal],''):
		$_GET['folio'])."' AND 
		sucursal=".(($_GET[idsucursal]!=$_GET[sucursalant])?$_GET[sucursalant]:$_GET[idsucursal])."";		
		mysql_query($s,$l) or die($s);		
		
		if($_GET[idsucursal]!=$_GET[sucursalant]){
			$s = "DELETE FROM reporteproductividad2 WHERE foliorecoleccion = '".$_GET[folio]."'
			AND sucursal = ".$_GET[sucursalant]."";
			mysql_query($s,$l) or die($s);
			
			$s = "CALL proc_ReporteProductividad('RECOLECCION','".$_GET['unidad']."', '".$folio."',
			current_timestamp(), ".$_GET[idsucursal].")";
			mysql_query($s,$l) or die($s);
		}
		
		echo "transmitio";
		
	}else if($_GET[accion]==3){//REALIZAR
		$s = "UPDATE recoleccion SET fecharecoleccion=CURRENT_DATE(),
		estado='REALIZADO', multiple=".$_GET[multiple].", realizo='SI' 
		WHERE folio='".$_GET['folio']."' AND sucursal=".$_GET[idsucursal]."";		
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM recolecciondetallefolioempresariales
		WHERE recoleccion IS NULL AND idusuario=".$_SESSION[IDUSUARIO];
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$s = "UPDATE recolecciondetallefolioempresariales SET
			recoleccion='".$_GET[folio]."', sucursal=".$_GET[idsucursal]."
			WHERE recoleccion IS NULL AND idusuario=".$_SESSION[IDUSUARIO];
			mysql_query($s,$l) or die($s);
		}		
		
		$s = "SELECT * FROM recolecciondetallefoliorecoleccion
		WHERE recoleccion IS NULL AND idusuario=".$_SESSION[IDUSUARIO];
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$s = "UPDATE recolecciondetallefoliorecoleccion SET
			recoleccion='".$_GET[folio]."', sucursal=".$_GET[idsucursal]."
			WHERE recoleccion IS NULL AND idusuario=".$_SESSION[IDUSUARIO];
			mysql_query($s,$l) or die($s);
		}		
		
		//CAMBIA ESTADO A SOLICITUD TELEFONICA
		$s = "SELECT * FROM solicitudtelefonica 
		WHERE folioatencion='".$_GET['folio']."' AND sucursal=".$_GET[idsucursal];
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$s = "UPDATE solicitudtelefonica SET estado='SOLUCIONADO'
			WHERE folioatencion='".$_GET['folio']."' AND sucursal=".$_GET[idsucursal];
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE actividadusuario SET estado = 1 
			WHERE recoleccion = '".$_GET[folio]."' AND idsucursal = ".$_GET[idsucursal]."";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "CALL proc_ReporteProductividad('REALIZADA','','".$_GET['folio']."',
		'".$_GET[fechahora]."', ".$_GET[idsucursal].")";
		mysql_query($s,$l) or die($s);
		
		echo "realizo";
		
	}else if($_GET[accion]==4){//REPROGRAMAR
		$s = "UPDATE recoleccion SET 
		fecharegistro='".cambiaf_a_mysql($_GET[fecha])."', estado='NO TRANSMITIDO', transmitida='NO'
		WHERE folio='".$_GET[folioant]."' AND sucursal=".$_GET[idsucursal]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO recoleccionmotivoreprogramacion SET
		recoleccion='".$_GET[folioant]."',sucursal=".$_GET[idsucursal].",
		fecharegistro = '".cambiaf_a_mysql($_GET[fecha])."',
		motivo = '".$_GET['motivoreprogramar']."',notificar=UCASE('".$_GET['notificarreprogramar']."'),
		observaciones = UCASE('".$_GET['observacionesreprogramar']."'), 
		usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp()";
		mysql_query($s,$l) or die($s);
		
		echo "reprogramo";
		
	}else if($_GET[accion]==5){//CANCELAR
		$s = "UPDATE recoleccion SET estado='CANCELADO'
		WHERE folio='".$_GET['folio']."' AND sucursal=".$_GET[idsucursal];
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO recoleccionmotivocancelacion SET
		recoleccion='".$_GET['folio']."',sucursal=".$_GET[idsucursal].",
		fecharegistro='".cambiaf_a_mysql($_GET[fecha])."',
		motivo='".$_GET['motivo']."',notificar=UCASE('".$_GET['notificaciones']."'),
		usuario='".$_SESSION[NOMBREUSUARIO]."',fecha=current_timestamp()";
		mysql_query($s,$l) or die($s);
		
		$s = "CALL proc_ReporteProductividad('RECANCELADA','','".$_GET['folio']."',
		'', ".$_GET[idsucursal].")";
		mysql_query($s,$l) or die($s);
		
		
		echo "cancelo";
		
	}else if($_GET[accion]==6){//INSERTAR EN TEMPORAL DETALLE
		if($_GET[tipo]=="guardar"){
			$s = "SELECT CURRENT_TIMESTAMP() AS fecha";
			$ds= mysql_query($s, $l) or die($s);
			$f = mysql_fetch_object($ds);

			$s = "INSERT INTO recolecciondetalle_tmp SET
			cantidad='".$_GET[cantidad]."',iddescripcion='".$_GET[iddescripcion]."',
			descripcion='".$_GET[descripcion]."',contenido='".$_GET[contenido]."',
			peso='".$_GET[peso]."',	largo='".$_GET[largo]."',
			ancho='".$_GET[ancho]."',alto='".$_GET[alto]."',volumen='".$_GET[volumen]."',
			pesototal='".$_GET[pesototal]."',pesounit='".$_GET[pesounit]."',
			idusuario=".$_SESSION[IDUSUARIO].", fecha='".$f->fecha."'";
			mysql_query($s,$l) or die($s);
			
			echo "ok,".$f->fecha;
		}else{
			$s = "UPDATE recolecciondetalle_tmp SET
			cantidad='".$_GET[cantidad]."',iddescripcion='".$_GET[iddescripcion]."',
			descripcion='".$_GET[descripcion]."',contenido='".$_GET[contenido]."',
			peso='".$_GET[peso]."',	largo='".$_GET[largo]."',
			ancho='".$_GET[ancho]."',alto='".$_GET[alto]."',volumen='".$_GET[volumen]."',
			pesototal='".$_GET[pesototal]."',pesounit='".$_GET[pesounit]."'
			WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."'";
			mysql_query($s,$l) or die($s);
			
			echo "ok";
		}
		
	}else if($_GET[accion]==7){//INSERTAR EN TEMPORAL GUIAS EMPRESARIALES
		if($_GET[tipo]=="guardar"){
			$s = "INSERT INTO recolecciondetallefolioempresariales SET
			foliosempresariales='".$_GET[foliosempresarial]."',
			idusuario=".$_SESSION[IDUSUARIO].", fecha=current_timestamp()";
			mysql_query($s,$l) or die($s);
			
			echo "ok";
		}else{
			$s = "DELETE FROM recolecciondetallefolioempresariales			
			WHERE foliosempresariales='".$_GET[foliosempresarial]."' AND 
			idusuario=".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			echo "ok";
		}
		
	}else if($_GET[accion]==8){//INSERTAR EN TEMPORAL RECOLECCIONES
		if($_GET[tipo]=="guardar"){
			
			$s = "SELECT folios, utilizado FROM asignacionpapeletasrecdetalle WHERE folios = '".$_GET[foliorecoleccion]."'";
			$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
			
			if(mysql_num_rows($r)==0){
				die("no existe");
			}
			
			if($f->utilizado==1){
				die("utilizado");
			}
			
			$s = "SELECT * FROM recolecciondetallefoliorecoleccion WHERE foliosrecolecciones = '".$_GET[foliorecoleccion]."'";
			$r = mysql_query($s,$l) or die($s);
			
			if(mysql_num_rows($r)>0){
				die("agregado");
			}
			
			$s = "INSERT INTO recolecciondetallefoliorecoleccion SET
			foliosrecolecciones='".$_GET[foliorecoleccion]."',
			idusuario=".$_SESSION[IDUSUARIO].", fecha=current_timestamp()";
			mysql_query($s,$l) or die($s);
			
			echo "ok,".$_GET[caja].",".$_GET[foliorecoleccion].",".$_GET[va];
		}else{
			$s = "DELETE FROM recolecciondetallefoliorecoleccion 			
			WHERE foliosrecolecciones='".$_GET[foliorecoleccion]."' AND 
			idusuario=".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			echo "ok,".$_GET[va];
		}

	}
	
	function obtenerFolioRecoleccion($sucursal,$fecha){
		$l = Conectarse('webpmm');
		$fecha	= (($fecha!='')? "$fecha" : 'CURRENT_DATE');
		$s = "SELECT IFNULL(CONCAT(DATE_FORMAT($fecha,'%m'),'',
		DATE_FORMAT($fecha,'%d'),'-',MAX(SUBSTRING(folio,6,LENGTH(folio)-1)*1) + 1),
		CONCAT(DATE_FORMAT(".$fecha.",'%m'),'',DATE_FORMAT($fecha,'%d'),'-','1')) AS folio
		FROM recoleccion
		WHERE CONCAT(DATE_FORMAT($fecha,'%m'),'',DATE_FORMAT($fecha,'%d'))=SUBSTRING(folio,1,4)
		AND sucursal=$sucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		return  $f->folio;
	}
?>