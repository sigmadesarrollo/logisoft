<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
		
	if($_GET[accion]=="0"){
		$row = ObtenerFolio('correointerno','webpmm');
		$s = "SELECT DATE_FORMAT(CURDATE(),'%d/%m/%Y') AS fecha";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$s = "DELETE FROM correointernodetalle WHERE idusuario=".$_SESSION[IDUSUARIO]." AND evaluacion=0";
		mysql_query($s,$l) or die($s);
		
		$f->folio = $row[0];
		echo $f->folio.",".$f->fecha;
	
	}else if($_GET[accion]==1){
		$s = "select id, concat_ws(' ',nombre, apellidopaterno, apellidomaterno) as nempleado,
		rfc from catalogoempleado where id = $_GET[idempleado]";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);		
			$f->ncliente = cambio_texto($f->ncliente);
			$f->rfc = cambio_texto($f->rfc);
			echo "(".str_replace("null",'""', json_encode($f)).")";
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==2){
		$arr = split(",",$_GET[arre]);
		$s = "SELECT CURRENT_TIMESTAMP() AS fecha";
		$ds= mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($ds);		
		
			$s = "INSERT INTO correointernodetalle 
			(cantidad,descripcion,contenido, peso,largo,ancho,alto,volumen,
			pesototal,pesounit,idusuario,usuario,fecha )
			VALUES 
			('".$arr[0]."', '".$arr[1]."', '".cambio_texto($_GET[contenido])."', '".$arr[4]."', '".$arr[5]."', 
			 '".$arr[6]."', '".$arr[7]."', '".$arr[8]."', '".$arr[9]."', '".$arr[10]."', 
			 ".$_SESSION[IDUSUARIO].",'".$_SESSION[NOMBREUSUARIO]."','".$f->fecha."')";

			$r = mysql_query(str_replace("''",'null', $s),$l) or die(mysql_error($l).$s);

			echo "ok,".$f->fecha;
			
	}else if($_GET[accion]==3){
		$row = split(",",$_GET[arre]);//INSERTAR DATOS CORREO INTERNO
		/*$row[0] = Fecha
		$row[1] = Destino
		$row[2] = idremitente
		$row[3] = iddestinatario
		$row[4] = sucursaldestino*/
		
		//OBTENER FOLIO GUIA CORREO INTERNO
		$s = "SELECT CONCAT(cuentac,numerodesde,letra) AS newfolio FROM (
			SELECT 
				(888) AS cuentac,
			LPAD(
				IFNULL(
					IF(SUBSTRING(MAX(id),4,9)+1=1000000000,1,SUBSTRING(MAX(id),4,9)+1)
				,1)
			,9,'0') AS numerodesde,
			CHAR(ASCII(IFNULL(SUBSTRING(MAX(id),13,1),'A'))+IF(SUBSTRING(MAX(id),4,9)+1=1000000000,1,0)) AS letra
			FROM guiasventanilla WHERE SUBSTRING(id,1,3) = (888)
		) AS t1";

		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$newfolio = $f->newfolio;
		
		$s = "INSERT INTO correointerno 
		(fechacorreo,estado,destino,sucdestino,remitente,destintario,sucorigen,guia,idusuario,fecha) VALUES 
		('".cambiaf_a_mysql($row[0])."','GUARDADO',".$row[1].",".$row[4].",".$row[2].",".$row[3].",
		".$_SESSION[IDSUCURSAL].",UCASE('".$newfolio."'),".$_SESSION[IDUSUARIO].",CURRENT_TIMESTAMP)";
		$r = @mysql_query(str_replace("''","null",$s),$l) or die($s);
		$folio = mysql_insert_id();
		
		//INSERTAR DATOS CORREO INTERNO DETALLADO
		$s = "SELECT * FROM correointernodetalle WHERE idusuario=".$_SESSION[IDUSUARIO]." AND evaluacion=0";
		$t = mysql_query($s,$l) or die($s);
			while($ss = mysql_fetch_object($t)){
				$s = "UPDATE correointernodetalle SET evaluacion=".$folio." 
				WHERE evaluacion=0 AND idusuario=".$_SESSION[IDUSUARIO]."";
				//die($s);
				$r = mysql_query($s,$l) or die($s);
			}
				
		//REGISTRAR GUIA CORREO INTERNO
		$s = "insert into guiasventanilla set id='".$newfolio."', estado='ALMACEN ORIGEN', 
		fecha=CURDATE(), idsucursalorigen='".$_SESSION[IDSUCURSAL]."', iddestino='".$row[1]."', 
		idsucursaldestino='".$row[4]."', idremitente='".$row[2]."',
		iddestinatario='".$row[3]."', totalpaquetes='".$_GET[totalpaquetes]."',
		totalpeso='".$_GET[totalpeso]."', totalvolumen='".$_GET[totalvolumen]."',
		usuario='".$_SESSION[NOMBREUSUARIO]."',	ubicacion = ".$_SESSION[IDSUCURSAL].",
		idusuario='".$_SESSION[IDUSUARIO]."', fecha_registro=current_date, hora_registro=current_time";
		$r = @mysql_query(str_replace("''","null",$s),$l) or die($s);
		
		$s = "INSERT INTO guiaventanilla_detalle
		SELECT '".$newfolio."' AS idguia, d.cantidad, cd.descripcion, d.contenido, d.pesototal AS pesou,
		d.alto, d.ancho, d.largo, d.peso, d.volumen, 0 AS importe, 0 AS excedente, 0 AS kgexcedente,
		".$_SESSION[IDUSUARIO]." AS idusuario FROM correointernodetalle d
		INNER JOIN catalogodescripcion cd ON d.descripcion = cd.id
		WHERE d.evaluacion=".$folio."";
		$r = @mysql_query(str_replace("''","null",$s),$l) or die($s);

		$cantidad = 1;
		$s = "select * from guiaventanilla_detalle where idguia = '$newfolio'";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$pesousado = ($f->peso>$f->volumen)?$f->peso:$f->volumen;
			for($i=0;$i<$f->cantidad;$i++){
				$s = "INSERT INTO guiaventanilla_unidades SET idguia='$newfolio', descripcion='$f->descripcion', 
				contenido='".$f->contenido."', peso=$pesousado/$f->cantidad, paquete=$cantidad, 
				depaquetes=".$_GET[totalpaquetes].", ubicacion = ".$_SESSION[IDSUCURSAL].",
				codigobarras='".$newfolio.str_pad($cantidad,4,"0",STR_PAD_LEFT).str_pad($_GET[totalpaquetes],4,"0",STR_PAD_LEFT)."'";
				@mysql_query($s,$l) or die($s);
				$cantidad++;
			}
		}

		$s = "INSERT INTO seguimiento_guias SET 
		guia='$newfolio', ubicacion='$_SESSION[IDSUCURSAL]', unidad='',estado='ALMACEN ORIGEN',
		fecha=CURRENT_DATE, hora=CURRENT_TIME, usuario=$_SESSION[IDUSUARIO]";
		@mysql_query($s,$l) or die($s);
		
		echo "guardado,".$folio.",".$newfolio;
		
	}else if($_GET[accion]==4){//OBTENER SUCURSAL DESTINO
		$s="SELECT cs.id as idsucursal, cs.descripcion as sucursal FROM catalogosucursal cs
		INNER JOIN catalogodestino cd ON cs.id=cd.sucursal
		WHERE cd.id=".$_GET[destino]."";
		$r = mysql_query($s,$l) or die(mysql_error($l).$s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		
		echo $f->sucursal.",".$f->idsucursal;
		
	}else if($_GET[accion]==5){//OBTENER CORREO INTERNO
		$principal = "";
		$s="SELECT ci.folio, DATE_FORMAT(ci.fechacorreo,'%d/%m/%Y') AS fecha, ci.estado, ci.destino,
		ci.sucdestino, ci.remitente, CONCAT_WS(' ',re.nombre,re.apellidopaterno,re.apellidomaterno) AS rem,
		ci.destintario, CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS des,
		re.rfc AS remrfc, de.rfc AS desrfc, ci.guia FROM correointerno ci
		INNER JOIN catalogodestino cd ON ci.destino = cd.id
		INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
		INNER JOIN catalogoempleado re ON ci.remitente = re.id
		INNER JOIN catalogoempleado de ON ci.destintario = de.id		
		WHERE ci.folio=".$_GET[correo]." AND ci.sucorigen=".$_SESSION[IDSUCURSAL];
		$r = mysql_query($s,$l) or die(mysql_error($l).$s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion),
			CONCAT(cd.descripcion,' - ',cs.prefijo)) AS desdes FROM catalogodestino cd
			INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
			WHERE cd.id=".$f->destino;
			$t = mysql_query($s,$l) or die($s);
			$tt = mysql_fetch_object($t);
			
			$f->ddestino = cambio_texto($tt->desdes);
			$f->rem = cambio_texto($f->rem);
			$f->des = cambio_texto($f->des);
			
			$principal = str_replace('null','""',json_encode($f));
			
			$detalle = "";
			$s = "SELECT d.cantidad,d.descripcion AS iddescripcion,cd.descripcion,d.contenido,d.peso,
			d.largo,d.ancho,d.alto,d.volumen, d.pesototal,d.pesounit FROM correointernodetalle d
			INNER JOIN catalogodescripcion cd ON d.descripcion = cd.id
			WHERE d.evaluacion = ".$_GET[correo]."";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
				while($t = mysql_fetch_object($r)){
					$t->descripcion = cambio_texto($t->descripcion);
					$t->contenido = cambio_texto($t->contenido);
					$registros[] = $t;
				}
			
			$detalle = str_replace('null','""',json_encode($registros));
			
			echo "({principal:$principal,detalle:$detalle})";
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==6){
		$row = split(",",$_GET[arre]);
		$s = "UPDATE correointernodetalle SET cantidad=".$row[0].", descripcion=".$row[1].",
		contenido='".cambio_texto($_GET[contenido])."', peso=".$row[4].", largo=".$row[5].", ancho=".$row[7].",
		alto=".$row[6].", volumen=".$row[8].", pesototal=".$row[9].",
		pesounit=".$row[10]." WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."'";
		$r = mysql_query($s,$l) or die($s);
		echo "ok";
		
	}else if($_GET[accion]==7){		
		$principal = "";
		$s="SELECT ci.folio, DATE_FORMAT(ci.fechacorreo,'%d/%m/%Y') AS fecha, ci.estado, ci.destino,
		ci.sucdestino, ci.remitente,
		CONCAT_WS(' ',re.nombre,re.apellidopaterno,re.apellidomaterno) AS rem,
		ci.destintario, CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS des,
		re.rfc AS remrfc, de.rfc AS desrfc, ci.guia FROM correointerno ci
		INNER JOIN catalogodestino cd ON ci.destino = cd.id
		INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
		INNER JOIN catalogoempleado re ON ci.remitente = re.id
		INNER JOIN catalogoempleado de ON ci.destintario = de.id		
		WHERE ci.guia='".$_GET[guia]."'";
		$r = mysql_query($s,$l) or die(mysql_error($l).$s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion),
			CONCAT(cd.descripcion,' - ',cs.prefijo)) AS desdes FROM catalogodestino cd
			INNER JOIN catalogosucursal cs ON cd.sucursal = cs.id
			WHERE cd.id=".$f->destino;
			$t = mysql_query($s,$l) or die($s);
			$tt = mysql_fetch_object($t);
			
			$f->ddestino = cambio_texto($tt->desdes);
			$f->rem = cambio_texto($f->rem);
			$f->des = cambio_texto($f->des);
			
			$principal = str_replace('null','""',json_encode($f));
			
			$detalle = "";
			$s = "SELECT d.cantidad,d.descripcion AS iddescripcion,cd.descripcion,d.contenido,d.peso,
			d.largo,d.ancho,d.alto,d.volumen, d.pesototal,d.pesounit FROM correointernodetalle d
			INNER JOIN catalogodescripcion cd ON d.descripcion = cd.id
			WHERE d.evaluacion = ".$f->folio."";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
				while($t = mysql_fetch_object($r)){
					$t->descripcion = cambio_texto($t->descripcion);
					$t->contenido = cambio_texto($t->contenido);
					$registros[] = $t;
				}
			
			$detalle = str_replace('null','""',json_encode($registros));
			
			echo "({principal:$principal,detalle:$detalle})";
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion] == 8){
		$s = "DELETE FROM correointernodetalle 
		WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."'";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	}
?>
