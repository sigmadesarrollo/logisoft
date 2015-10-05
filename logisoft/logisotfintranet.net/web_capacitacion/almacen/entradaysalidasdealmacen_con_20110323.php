<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	//solicitar guias ead
	if($_GET[accion] == 1){
		$s = "delete from entradaysalidadealmacen_tmp where idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "SELECT IFNULL(MAX(folio),0)+1 as folio FROM entradaysalidadealmacen WHERE tipo = 'SALIDA'";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		$f = mysql_fetch_object($r);
		$folio = $f->folio;
		
		$s = "insert into entradaysalidadealmacen_tmp
		SELECT gv.id, concat(count(gvu.id),'-',gv.totalpaquetes), 'NORMAL', gv.fecha, $_SESSION[IDUSUARIO],
		if(cc1.tipocliente=2 or cc2.tipocliente=2,1,0)
		FROM guiasventanilla as gv 
		inner join guiaventanilla_unidades as gvu on gv.id = gvu.idguia
		inner join catalogocliente as cc1 on gv.idremitente = cc1.id
		inner join catalogocliente as cc2 on gv.iddestinatario = cc2.id
		WHERE gv.ocurre = 0 and (gv.entradasalida = '' OR isnull(gv.entradasalida) OR gv.entradasalida = 'ENTRADA') 
		and (gv.estado = 'ALMACEN DESTINO' or gv.estado = 'PARCIALMENTE EN REPARTO EAD') 
		and gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]'
		and (gvu.proceso = 'ALMACEN DESTINO' OR ISNULL(gvu.proceso))
		group by gv.id
		UNION
		SELECT ge.id, concat(count(gvu.id),'-',ge.totalpaquetes), 'EMPRESARIAL', ge.fecha, $_SESSION[IDUSUARIO],
		if(cc1.tipocliente=2 or cc2.tipocliente=2,1,0)
		FROM guiasempresariales as ge 
		inner join guiasempresariales_unidades as gvu on ge.id = gvu.idguia
		inner join catalogocliente as cc1 on ge.idremitente = cc1.id
		inner join catalogocliente as cc2 on ge.iddestinatario = cc2.id
		WHERE ge.ocurre = 0 and (ge.entradasalida = '' OR isnull(ge.entradasalida) OR ge.entradasalida = 'ENTRADA') 
		and (ge.estado = 'ALMACEN DESTINO' or ge.estado = 'PARCIALMENTE EN REPARTO EAD')
		and ge.idsucursaldestino = '$_SESSION[IDSUCURSAL]'
		and (gvu.proceso = 'ALMACEN DESTINO' OR ISNULL(gvu.proceso))
		group by ge.id";
		//echo $s;
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "SELECT guia FROM entregasespecialesead WHERE sucursal = ".$_SESSION[IDSUCURSAL]."
		AND CURDATE() <> fechaead AND estado = 1";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$s = "DELETE FROM entradaysalidadealmacen_tmp WHERE guia = '".$f->guia."'";
				mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
			}
		}
		$s = "select guia, cantidad, tipoguia, date_format(fecha, '%d/%m/%Y') as fecha, idusuario 
		from entradaysalidadealmacen_tmp where idusuario = $_SESSION[IDUSUARIO]
		order by corporativo desc";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			while($f = mysql_fetch_object($r)){
				$xml .= "
				<guia>$f->guia</guia>
				<cantidad>$f->cantidad</cantidad>
				<fecha>$f->fecha</fecha>
				<tipoguia>$f->tipoguia</tipoguia>
				<seleccion>1</seleccion>";
			}
			$xml .= "
			</datos>
			<foliomax>$folio</foliomax>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			</datos>
			<foliomax>0</foliomax>
			</xml>";
		}
	}
	
	//guardar guias seleccionadas
	if($_GET[accion] == 2){
		$s = "delete from entradaysalidadealmacen_tmp where idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "SELECT IFNULL(MAX(folio),0)+1 as folio FROM entradaysalidadealmacen WHERE tipo = 'ENTRADA'";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		$f = mysql_fetch_object($r);
		$folio = $f->folio;
		
		$s = "insert into entradaysalidadealmacen_tmp
		SELECT gv.id, concat(count(gvu.id),'-',gv.totalpaquetes), 'NORMAL', gv.fecha, $_SESSION[IDUSUARIO],
		if(cc1.clasificacioncliente='CORPORATIVO' or cc2.clasificacioncliente='CORPORATIVO',1,0)
		FROM guiasventanilla as gv 
		inner join guiaventanilla_unidades as gvu on gv.id = gvu.idguia
		inner join catalogocliente as cc1 on gv.idremitente = cc1.id
		inner join catalogocliente as cc2 on gv.iddestinatario = cc2.id
		WHERE gv.ocurre = 0 and gv.entradasalida = 'SALIDA' 
		and (gv.estado = 'ALMACEN DESTINO' or gv.estado = 'PARCIALMENTE EN REPARTO EAD')
		and gv.idsucursaldestino = '$_GET[sucorigen]'
		and (gvu.proceso = 'ALMACEN DESTINO' OR ISNULL(gvu.proceso))
		group by gv.id
		UNION
		SELECT ge.id, concat(count(gvu.id),'-',ge.totalpaquetes), 'EMPRESARIAL',ge.fecha, $_SESSION[IDUSUARIO],
		if(cc1.clasificacioncliente='CORPORATIVO' or cc2.clasificacioncliente='CORPORATIVO',1,0)
		FROM guiasempresariales as ge 
		inner join guiasempresariales_unidades as gvu on ge.id = gvu.idguia
		inner join catalogocliente as cc1 on ge.idremitente = cc1.id
		inner join catalogocliente as cc2 on ge.iddestinatario = cc2.id
		WHERE ge.ocurre = 0 and ge.entradasalida = 'SALIDA' 
		and (ge.estado = 'ALMACEN DESTINO' or ge.estado = 'PARCIALMENTE EN REPARTO EAD')
		and ge.idsucursaldestino = '$_GET[sucorigen]'
		and (gvu.proceso = 'ALMACEN DESTINO' OR ISNULL(gvu.proceso))
		group by ge.id";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		
		$s = "select guia, cantidad, tipoguia, date_format(fecha, '%d/%m/%Y') as fecha, idusuario 
		from entradaysalidadealmacen_tmp where idusuario = $_SESSION[IDUSUARIO]
		order by corporativo desc";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$enc = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			while($f = mysql_fetch_object($r)){
				$xml .= "
				<guia>$f->guia</guia>
				<cantidad>$f->cantidad</cantidad>
				<fecha>$f->fecha</fecha>
				<tipoguia>$f->tipoguia</tipoguia>
				<seleccion>1</seleccion>";
			}
			$xml .= "
			</datos>
			<foliomax>$folio</foliomax>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			</datos>
			<foliomax>0</foliomax>
			</xml>";
		}
	}
	
	//guardar guias seleccionadas
	if($_GET[accion] == 3){
		if($_GET[dar]==1){
			$DIO = "SALIDA";
		}else{
			$DIO = "ENTRADA";
		}
		
		$s = "CREATE TEMPORARY TABLE tmp_seleccionadas (
			`folioguia` VARCHAR(25) COLLATE utf8_unicode_ci DEFAULT NULL
		);";
		mysql_query($s,$l) or die($s);
		
		//echo $s;
		
		$s = "INSERT INTO tmp_seleccionadas
		VALUES ('".str_replace(",","'),('",$_GET[folio])."')";
		mysql_query($s,$l) or die($s);
		
		//echo $s;
		
		//$losfolios = "'".str_replace(",","','",$_GET[folio])."'";
		
		$s = "select ifnull(max(folio),0)+1 as newfolio from entradaysalidadealmacen where tipo = '$DIO'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "INSERT INTO entradaysalidadealmacen
		SET tipo='$DIO', folio=$f->newfolio, usuario='$_SESSION[NOMBREUSUARIO]', idusuario='$_SESSION[IDUSUARIO]', fecha=CURRENT_DATE";
		mysql_query($s,$l) or die($s);
		$id = mysql_insert_id($l);
		
		$s = "INSERT INTO entradaysalidadealmacendetalle
		SELECT '$id', guia FROM entradaysalidadealmacen_tmp where idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "update guiasventanilla gv
		inner join tmp_seleccionadas tp on gv.id = tp.folioguia
		set gv.entradasalida='$DIO'";
		//die($s);
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<guardado>1</guardado>
			</datos>
			</xml>";
		$s = "update guiasempresariales gv
		inner join tmp_seleccionadas tp on gv.id = tp.folioguia
		set entradasalida='$DIO'";
		mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<guardado>1</guardado>
			</datos>
			</xml>";
		
		$s = "INSERT INTO seguimiento_guias
		(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
		SELECT e.guia,'$_SESSION[IDSUCURSAL]','',
		'SALIDA', CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','',''
		FROM entradaysalidadealmacen_tmp AS e 
		WHERE idusuario = '$_SESSION[IDUSUARIO]'
		GROUP BY guia";
		mysql_query($s,$l) or die($s);
		
		if($DIO == "ENTRADA"){
			$s = "UPDATE entregasespecialesead SET estado=0 
			WHERE fechaead = CURDATE() AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		}
	}
	
	echo $xml;
	
?>
