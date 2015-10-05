<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	require_once("../clases/ValidaConvenio.php");
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s="SELECT cs.descripcion, if(cs.id=13 or cs.id=12,1,cd.subdestinos) subdestinos, ifnull(cd.costoead,0) as costoead, 
		cd.notificacion, cd.notificaciones FROM catalogosucursal cs
			INNER JOIN catalogodestino cd ON cs.id=cd.sucursal
			WHERE cd.id=".$_GET[destino]."";
		$r = mysql_query($s,$l) or die(mysql_error($l).$s);		
		$f = mysql_fetch_object($r);
		
		$f->descripcion = utf8_encode($f->descripcion);
		$f->notificaciones = utf8_encode($f->notificaciones);
		
		$principal = str_replace('null','""',json_encode($f));
		
		echo "({principal:$principal})";
				
	}else if($_GET[accion]==2){	
		$arr = split(",",$_GET[arre]);
		$s = "SELECT CURRENT_TIMESTAMP() AS fecha";
		$ds= mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($ds);
		
		$fechan = $f->fecha;
		$contenido = $_GET[contenido];
			$s = "INSERT INTO evaluacionmercanciadetalle 
			(cantidad,descripcion,contenido, peso,largo,alto,ancho,volumen,
			pesototal,pesounit,idusuario,usuario,fecha,sucursal,espesototal)
			VALUES 
			('".$arr[0]."', '".$arr[1]."', '".cambio_texto($_GET[contenido])."', '".$arr[2]."', '".$arr[3]."', 
			 '".$arr[4]."', '".$arr[5]."', '".$arr[6]."', '".$arr[7]."', '".$arr[8]."', 
			 ".$_SESSION[IDUSUARIO].",'".$_SESSION[NOMBREUSUARIO]."','".$fechan."',".$_SESSION[IDSUCURSAL].", '".$arr[9]."')";

			$r = mysql_query(str_replace("''",'null', $s),$l) or die(mysql_error($l).$s);
			
		
		$s = "SELECT IFNULL(COUNT(*),0) AS total FROM contenidos WHERE descripcion = UCASE('".$contenido."')";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		if($f->total==0){
			$s = "INSERT INTO contenidos SET descripcion = UCASE('".$contenido."')";
			mysql_query($s,$l) or die($s);
		}
		
			echo "ok,".$fechan;

	}else if($_GET[accion]==3){//OBTENER GENERALES	
		$s = "DELETE FROM evaluacionmercanciadetalle WHERE idusuario=".$_SESSION[IDUSUARIO]." AND evaluacion=0 AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
	
		$s = "SELECT DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') AS fecha,
		(SELECT IFNULL(MAX(folio),0) + 1 AS folio FROM evaluacionmercancia WHERE sucursal=".$_SESSION[IDSUCURSAL].")
		 AS folio,(SELECT UCASE(descripcion) FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL].")
		 AS sucursal"; //OBTENER FOLIO Y FECHA
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);		
		$s = "SELECT con.costo AS bolsaempaque FROM catalogoservicio cs
		INNER JOIN configuradorservicios con ON cs.id=con.servicio
		WHERE cs.id=".$_GET[bolsa]."";//OBTENER COSTO BOLSA EMPAQUE
		$b = mysql_query($s, $l) or die($s);
		$bol = mysql_fetch_object($b);		
		$s = "SELECT cs.descripcion as servicio, con.condicion,
		con.costo AS emplaye, con.costoextra, con.limite,
		con.porcada FROM catalogoservicio cs
		INNER JOIN configuradorservicios con ON cs.id=con.servicio WHERE cs.id=".$_GET[emplaye]."";
		$e = mysql_query($s, $l) or die($s);//OBTENER COSTO EMPLAYE 
		$emp = mysql_fetch_object($e);	
		$f->bolsaempaque = $bol->bolsaempaque;
		$f->servicio = $emp->servicio;
		$f->condicion = $emp->condicion;
		$f->emplaye = $emp->emplaye;
		$f->costoextra = $emp->costoextra;
		$f->limite = $emp->limite;
		$f->porcada = $emp->porcada;
		$f->sucursal= cambio_texto($f->sucursal);	
		$datosgenerales = str_replace('null','""', json_encode($f));	
		echo "({datos:$datosgenerales})";	
	}else if($_GET[accion]==4){
		$s = "DELETE FROM evaluacionmercanciadetalle WHERE idusuario=".$_SESSION[IDUSUARIO]." AND evaluacion=0 AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		
	}else if($_GET[accion]==5){//REGISTRAR EVALUACION
		$arr = split(",",$_GET[arre]);	
		$s = "INSERT INTO evaluacionmercancia 
		(folio,fechaevaluacion, estado, guiaempresarial, recoleccion,
		destino, sucursaldestino, bolsaempaque, cantidadbolsa,
		totalbolsaempaque, emplaye, totalemplaye, sucursal, entrega, usuario, fecha)
		VALUES
		(obtenerFolio('evaluacionmercancia',$_SESSION[IDSUCURSAL]),current_date, 
		UCASE('".$arr[0]."'), UCASE('".$arr[1]."'), UCASE('".$arr[2]."'),
		".$arr[3].", UCASE('".$arr[4]."'), ".$arr[5].", ".$arr[6].", ".$arr[7].",
		".$arr[8].", ".$arr[9].", ".$arr[10].", '".$arr[11]."', '".$_SESSION[NOMBREUSUARIO]."', current_timestamp())";
		$r = mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
		
		$s = "SELECT folio FROM evaluacionmercancia WHERE id = ".$folio."";
		$r = mysql_query($s,$l) or die($s); 
		$fx = mysql_fetch_object($r);
				
		$s = "INSERT INTO seguimiento_guias 
		SET guia = $fx->folio, ubicacion = $_SESSION[IDSUCURSAL],
		estado = 'EVALUACION', unidad=null, fecha=CURRENT_DATE, hora = CURRENT_TIME,
		usuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "update guiasventanillaclientes set estado='APLICADA' where id = '".$arr[1]."'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE evaluacionmercanciadetalle SET evaluacion=".$fx->folio." 
		WHERE idusuario=".$_SESSION[IDUSUARIO]." AND evaluacion=0 AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE asignacionpapeletasrecdetalle SET utilizado = 1 WHERE folios IN ('".$arr[2]."')";
		mysql_query($s,$l) or die($s);
		
		echo "guardo,".$fx->folio;
	
	}else if($_GET[accion]==6){
		$s = "DELETE FROM evaluacionmercanciadetalle WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	
	}else if($_GET[accion]==7){
		$s = "UPDATE evaluacionmercancia SET estado='CANCELADA' 
		WHERE folio=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE asignacionpapeletasrecdetalle SET utilizado = 1 WHERE folios = (SELECT recoleccion FROM evaluacionmercancia
		WHERE folio=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL].")";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	
	}else if($_GET[accion]==8){
		$s = "SELECT e.fechaevaluacion, e.estado, e.guiaempresarial,
		e.recoleccion, e.destino, cd.descripcion As descripciondestino,
		e.sucursaldestino, e.bolsaempaque, e.cantidadbolsa, e.totalbolsaempaque,
		e.emplaye, e.totalemplaye, e.entrega FROM evaluacionmercancia e
		INNER JOIN catalogodestino cd ON e.destino=cd.id
		WHERE e.folio=".$_GET[evaluacion]." AND e.sucursal=".$_SESSION[IDSUCURSAL];
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
		$registros = array();
			while($f = mysql_fetch_object($r)){				
				$s = "SELECT costoead FROM catalogodestino WHERE id=".$f->destino."";
				$d = mysql_query($s,$l) or die($s);
				$de = mysql_fetch_object($d);				
				$f->precioead = $de->costoead;
				
				$f->fechaevaluacion = cambiaf_a_normal($f->fechaevaluacion);
				$f->descripciondestino = cambio_texto($f->descripciondestino);
				$f->sucursaldestino = cambio_texto($f->sucursaldestino);
				$f->guiaempresarial = cambio_texto($f->guiaempresarial);
				$registros[] = $f;
			}
		
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion]==9){
		$s = "SELECT e.id, e.evaluacion, e.cantidad, e.descripcion,
		cd.descripcion As catdes, e.contenido, e.peso, e.largo, e.ancho,
		e.alto, e.volumen, e.pesototal, e.pesounit FROM evaluacionmercanciadetalle e
		INNER JOIN catalogodescripcion cd ON e.descripcion=cd.id
		WHERE e.evaluacion=".$_GET[evaluacion]." AND e.sucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->catdes);
				$registros[] = $f;
			}
		
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==10){	
		$s = "SELECT folio FROM evaluacionmercancia WHERE estado = 'GUARDADO' AND guiaempresarial = '$_GET[folio]'";
		$rx = mysql_query($s,$l) or die($s);
		
		$s = "SELECT id as folio from guiasempresariales where id = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0 || mysql_num_rows($rx)>0){
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$mensaje = cambio_texto("El folio $f->folio ya fue registrado en una Guía");
			}elseif(mysql_num_rows($rx)>0){
				$f = mysql_fetch_object($rx);
				$mensaje = cambio_texto("El folio ".strtoupper($_GET[folio])." ya fue registrado en una Evaluación");
			}
			echo '({"encontro":"-2", "mensaje":"'.$mensaje.'"})';
		}else{		
			$s = "SELECT sge.prepagada, sge.foliosactivados
			FROM solicitudguiasempresariales AS sge
			INNER JOIN generacionconvenio AS gcn ON sge.idconvenio = gcn.folio AND CURRENT_DATE < gcn.vigencia
			WHERE sge.status = 1 AND
			SUBSTRING('$_GET[folio]',4,9) 
			BETWEEN SUBSTRING(sge.desdefolio,4,9) AND SUBSTRING(sge.hastafolio,4,9)
			AND SUBSTR('$_GET[folio]',1,3) = SUBSTRING(sge.desdefolio,1,3) 
			AND SUBSTRING('$_GET[folio]',13,1) BETWEEN SUBSTRING(sge.desdefolio,13,1) AND SUBSTRING(sge.hastafolio,13,1) 
			AND sge.foliosactivados = 'NO'";
			$r = mysql_query($s,$l) or die($s);
			
			if(mysql_num_rows($r)==0){
				$s = "SELECT sge.prepagada, gcn.folio, sge.id, gcn.limitekg
				FROM solicitudguiasempresariales AS sge
				INNER JOIN generacionconvenio AS gcn ON sge.idconvenio = gcn.folio AND CURRENT_DATE < gcn.vigencia
				WHERE sge.status = 1 AND
				SUBSTRING('$_GET[folio]',4,9) 
				BETWEEN SUBSTRING(sge.desdefolio,4,9) AND SUBSTRING(sge.hastafolio,4,9)
				AND SUBSTR('$_GET[folio]',1,3) = SUBSTRING(sge.desdefolio,1,3) 
				AND SUBSTRING('$_GET[folio]',13,1) BETWEEN SUBSTRING(sge.desdefolio,13,1) AND SUBSTRING(sge.hastafolio,13,1)";
				$r = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
					echo '({"encontro":"1", "idconvenio":"'.$f->folio.'", "prepagadas":"'.$f->prepagada.'", "limitekg":"'.$f->limitekg.'"})';
				}elseif($_GET[folio]!=""){
					echo '({"encontro":"0"})';
				}
			}else{
				echo '({"encontro":"-1"})';
			}	
		}		
	}else if($_GET[accion]==11){
		$s = "SELECT cs.descripcion FROM catalogosucursal cs
		INNER JOIN catalogodestino cd ON cs.id=cd.sucursal
		WHERE cd.id=".$_GET[destino]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->descripcion);
				$registros[] = $f;
			}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==12){
		$s = "SELECT * FROM catalogodescripcion WHERE descripcion='".$_GET[descripcion]."'";
		$ss= mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($ss);
		if(mysql_num_rows($ss)==0){
			echo "no";
		}else{
			echo "si,".$f->id;
		}
	}else if($_GET[accion]==13){		
		$row = split(",",$_GET[arre]);
		$s = "UPDATE evaluacionmercanciadetalle SET cantidad=".$row[0].", descripcion=".$row[1].",
		contenido='".cambio_texto($_GET[contenido])."', peso=".$row[2].", largo=".$row[3].", ancho=".$row[4].",
		alto=".$row[5].", volumen=".$row[6].", pesototal=".$row[7].", espesototal='".$row[9]."',
		pesounit=".$row[8]." WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		
		echo "ok";	
	}else if($_GET[accion]==14){		
		$s = "select gv.id, gv.iddestino, concat(cs.prefijo,'-',cd.descripcion) as destino, gv.emplaye, 
		gv.bolsaempaque, gv.totalbolsaempaque, cs.descripcion as sucursaldestino, gv.idsucursaldestino
		from guiasventanillaclientes as gv
		inner join catalogosucursal as cs on gv.idsucursaldestino = cs.id
		inner join catalogodestino as cd on gv.iddestino = cd.id
		where gv.id = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "(".str_replace("null","''",json_encode($f)).")";
			
	}else if($_GET[accion]==15){		
		$s = "delete from evaluacionmercanciadetalle where idusuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
	
		$s = "insert into evaluacionmercanciadetalle 
		(cantidad,descripcion,contenido,peso,largo,ancho,alto,volumen,
		pesototal,idusuario,usuario,fecha)
		select gec.cantidad, cd.id, gec.contenido, gec.peso, gec.largo, gec.ancho, gec.alto, 
		gec.volumen, gec.peso, '$_SESSION[IDUSUARIO]', '$_SESSION[NOMBREUSUARIO]'
		from guiasventanillaclientes_detalle as gec
		inner join catalogodescripcion as cd on gec.descripcion = cd.descripcion";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "select gv.id, gv.iddestino, concat(cs.prefijo,'-',cd.descripcion) as destino, gv.emplaye, 
		gv.bolsaempaque, gv.totalbolsaempaque, cs.descripcion as sucursaldestino, gv.idsucursaldestino
		from guiasventanillaclientes as gv
		inner join catalogosucursal as cs on gv.idsucursaldestino = cs.id
		inner join catalogodestino as cd on gv.iddestino = cd.id
		where gv.id = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "(".str_replace("null","''",json_encode($f)).")";
			
	}else if($_GET[accion]==16){
		$s = "UPDATE evaluacionmercancia SET destino=".$_GET[iddestino].", sucursaldestino = UCASE('".$_GET[destino]."')
		WHERE folio=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		echo "ok";
	
	}else if($_GET[accion]==17){
		$s = "SELECT prepagada FROM solicitudguiasempresariales WHERE '$_GET[folio]' BETWEEN desdefolio AND hastafolio";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$prepagada = $f->prepagada;
		
		$s = "delete from evaluacionmercanciadetalle where idusuario = '$_SESSION[IDUSUARIO]' and evaluacion = 0";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO evaluacionmercanciadetalle 
		(cantidad,descripcion,contenido, peso,largo,alto,ancho,volumen,
		pesototal,pesounit,idusuario,usuario,fecha,sucursal )
		SELECT gd.cantidad,cd.id, gd.contenido, gd.peso, gd.largo, gd.alto, gd.ancho, gd.volumen,
		gd.peso, gd.pesou, ".$_SESSION[IDUSUARIO].",'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP(),".$_SESSION[IDSUCURSAL]."
		FROM guiasventanillaclientes_detalle gd
		INNER JOIN catalogodescripcion cd ON gd.descripcion = cd.descripcion
		WHERE gd.idguia = '$_GET[folio]'";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT e.id, e.evaluacion, e.cantidad,
		cd.descripcion, e.contenido, e.peso, e.largo, e.ancho,
		e.alto, e.volumen, e.pesototal, e.pesounit FROM evaluacionmercanciadetalle e
		INNER JOIN catalogodescripcion cd ON e.descripcion=cd.id
		WHERE e.evaluacion=0 AND e.idusuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$datos[] = $f;
		}
		$detalle = json_encode($datos);
		
		$s = "SELECT gvc.id, gvc.iddestino, gvc.idsucursaldestino, CONCAT(cs.prefijo,'-',cd.descripcion) sucdestino, 
		cd.descripcion AS destino
		FROM guiasventanillaclientes gvc
		INNER JOIN catalogosucursal cs ON gvc.idsucursaldestino = cs.id
		INNER JOIN catalogodestino cd ON gvc.iddestino = cd.id
		where gvc.id = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->prepagada = ($prepagada=="SI")?"1":"0";
		$datosgen = json_encode($f);
		
		echo "({
			  datos:$datosgen,
			  detalleevaluacion:$detalle
		})";
	
	}else if($_GET[accion]==18){
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
		
		if(mysql_num_rows($r)==0){
			die("no registrado");
		}
		
		echo "ok";
	}
?>