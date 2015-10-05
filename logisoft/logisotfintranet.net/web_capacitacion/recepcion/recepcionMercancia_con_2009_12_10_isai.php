<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	if($_GET['accion']==1){
		
		$s = "DELETE FROM recepcion_tmp WHERE idusuario = '$_SESSION[IDUSUARIO]';";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO recepcion_tmp
		SELECT gvu.id, cse.descripcion, gvu.idguia, 'NORMAL', cs.descripcion, cs2.descripcion, gvu.codigobarras, 
		gvu.paquete, CONCAT(gvu.paquete,' de ', gvu.depaquetes), gvu.estado, 'N', '$_SESSION[IDUSUARIO]'
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS cse ON gv.sector = cse.id
		INNER JOIN guiaventanilla_unidades AS gvu ON gv.id = gvu.idguia
		INNER JOIN catalogosucursal AS cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON gv.idsucursaldestino = cs2.id
		WHERE gvu.proceso = 'POR RECIBIR' and gvu.ubicacion = $_SESSION[IDSUCURSAL]
		UNION
		SELECT geu.id, cse.descripcion, geu.idguia, 'EMPRESARIAL', cs.descripcion, cs2.descripcion, geu.codigobarras, 
		geu.paquete, CONCAT(geu.paquete,' de ', geu.depaquetes), geu.estado, 'N', '$_SESSION[IDUSUARIO]'
		FROM guiasempresariales AS ge
		LEFT JOIN catalogosector AS cse ON ge.sector = cse.id
		INNER JOIN guiasempresariales_unidades AS geu ON ge.id = geu.idguia
		INNER JOIN catalogosucursal AS cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogosucursal AS cs2 ON ge.idsucursaldestino = cs2.id
		WHERE geu.proceso = 'POR RECIBIR' and geu.ubicacion = $_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$s = "SELECT sector, guia, origen, destino
		FROM recepcion_tmp
		where subido = 'N' and idusuario = $_SESSION[IDUSUARIO]
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		$izquierda = str_replace("null",'""',json_encode($arre));
		$s = "SELECT sector, guia, origen, destino
		FROM recepcion_tmp
		where subido = 'S' and idusuario = $_SESSION[IDUSUARIO]
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		$derecha = str_replace("null",'""',json_encode($arre));
		
		echo "({
			   		izquierda:$izquierda, 
			   		derecha:$derecha
				})";
	}
	
	if($_GET[accion]==2){
		$s = "SELECT registro,paquete,codigobarra,estado,guia 
		FROM recepcion_tmp WHERE guia='$_GET[folio]' and subido='N' and idusuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==3){
		$s = "SELECT registro,paquete,codigobarra,estado,guia
		FROM recepcion_tmp WHERE guia='$_GET[folio]' and subido='S' and idusuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}
	if($_GET[accion]==4){
		$s = "UPDATE recepcion_tmp SET subido = 'S' WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==5){
		$s = "UPDATE recepcion_tmp SET subido = 'S' WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==6){
		$s = "UPDATE recepcion_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	if($_GET[accion]==7){
		$s = "UPDATE recepcion_tmp SET subido = 'N' WHERE guia = '$_GET[folio]' and registro = $_GET[registro] and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		echo "guardado";
	}
	
	if($_GET[accion]==8){
		$s = "select t1.guia
		from
		(select guia, subido from recepcion_tmp
		where subido = 'S' and idusuario = '$_SESSION[IDUSUARIO]') as t1
		inner join 
		(select guia, subido 
		from recepcion_tmp
		where subido = 'N' and idusuario = '$_SESSION[IDUSUARIO]') as t2 
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
		$s = "insert into recepcionmercancia
		set foliobitacora='$_GET[foliobitacora]',unidad = '$_GET[unidad]', tipo='$_GET[tiporecepcion]', idsucursal=$_SESSION[IDSUCURSAL],
		ruta = '$_GET[ruta]', recorrido = '$_GET[recorrido]', fecha=current_date, 
		usuario='$_SESSION[NOMBREUSUARIO]', idusuario=$_SESSION[IDUSUARIO]";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		$idx = mysql_insert_id($l);
		
		$s = "UPDATE programacionrecepciondiaria SET recibida = 'S' WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND recibida='N'
			AND unidad = '$_GET[unidad]'";
		mysql_query($s,$l) or die($s);
		
		$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		$s="insert into recepcionmercanciadetalle 
		SELECT NULL, '$idx', rg.guia AS guia, rg.origen, 
		CURRENT_DATE, rg.codigobarra, NULL 
		FROM recepcion_tmp AS rg 
		WHERE rg.guia IN ($_GET[folios]) AND idusuario = $_SESSION[IDUSUARIO] and rg.subido = 'S'
		GROUP BY rg.guia";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s = "select descripcion from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "INSERT INTO seguimiento_guias
		SELECT NULL, r.guia,'$_SESSION[IDSUCURSAL]','',
		if('$f->descripcion'=r.destino,'ALMACEN DESTINO','ALMACEN TRANSBORDO'), CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','',''
		FROM recepcion_tmp AS r 
		WHERE idusuario = '$_SESSION[IDUSUARIO]' AND r.subido = 'S'
		GROUP BY r.guia";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiaventanilla_unidades		
		INNER JOIN recepcion_tmp ON guiaventanilla_unidades.idguia = recepcion_tmp.guia
		INNER JOIN guiasventanilla gv ON guiaventanilla_unidades.idguia = gv.id
		AND guiaventanilla_unidades.paquete = recepcion_tmp.registro		
		SET guiaventanilla_unidades.proceso = IF(".$_SESSION[IDSUCURSAL]."=gv.idsucursaldestino, 'ALMACEN DESTINO', 'ALMACEN TRASBORDO'),
		gv.estado = IF(".$_SESSION[IDSUCURSAL]."=gv.idsucursaldestino, 'ALMACEN DESTINO', 'ALMACEN TRASBORDO')
		WHERE recepcion_tmp.idusuario = $_SESSION[IDUSUARIO] and recepcion_tmp.subido = 'S'
		and idguia in ($_GET[folios])";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales_unidades
		INNER JOIN recepcion_tmp ON guiasempresariales_unidades.idguia = recepcion_tmp.guia
		AND guiasempresariales_unidades.paquete = recepcion_tmp.registro
		INNER JOIN guiasempresariales ge ON guiasempresariales_unidades.idguia = ge.id
		SET guiasempresariales_unidades.proceso = IF(".$_SESSION[IDSUCURSAL]."=ge.idsucursaldestino,'ALMACEN DESTINO', 'ALMACEN TRASBORDO'),
		ge.estado = IF(".$_SESSION[IDSUCURSAL]."=ge.idsucursaldestino,'ALMACEN DESTINO', 'ALMACEN TRASBORDO')
		WHERE recepcion_tmp.idusuario = $_SESSION[IDUSUARIO] and recepcion_tmp.subido = 'S'
		and idguia in ($_GET[folios])";
		//echo "$s<br><br>";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
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
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		
		$s = "SELECT d.tipo, bs.ruta FROM catalogoruta cr
		INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
		INNER JOIN bitacorasalida bs ON cr.id=bs.ruta
		WHERE bs.unidad ='".$_GET[unidad]."' AND d.sucursal=".$_GET[sucursal]."";
		$t = mysql_query($s, $l) or die($s); $ti = mysql_fetch_object($t);
		if($ti->tipo==3){
			$s = "UPDATE bitacorasalida SET status=1 WHERE unidad='".$_GET[unidad]."' AND status=0";
			mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
			
			$s = "update catalogoruta set enuso=0 where id = $ti->ruta";
			mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		}
		
		$s = "UPDATE reportedanosfaltante SET recepcion = ".$idx." WHERE recepcion is null AND idusuario='".$_SESSION[IDUSUARIO]."'";
		mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
		echo "guardado";
	}
	
	if($_GET[accion]==10){//MOSTRAR DETALLE HISTORICO DAÑOS FALTANTES
		$s = "SELECT rep.guia, t.estado, t.destinatario, t.destino, t.origen,
		DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecharecepcion, rep.recepcion, rep.comentarios FROM reportedanosfaltante rep
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
		WHERE ".(($_GET[sucursal]!="todas")? "rm.sucursal=".$_GET[sucursal]."" : "")." 
		rm.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechaini])."' ";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->destinatario = cambio_texto($f->destinatario);
				$f->sucursalorigen = cambio_texto($f->sucursalorigen);
				$f->sucursaldestino = cambio_texto($f->sucursaldestino);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
	}
	
	if($_GET[accion]==11){
		$s = "UPDATE recepcion_tmp SET estado=UCASE('".cambio_texto($_GET[estado])."') WHERE guia='".$_GET[folio]."'";
		$r = mysql_query($s, $l) or die(mysql_error($l).$s);
		
		$s = "SELECT registro,paquete,codigobarra,estado,guia
		FROM recepcion_tmp WHERE guia='".$_GET[folio]."' and subido='S' and idusuario = $_SESSION[IDUSUARIO]";
		$re = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($re)){
			$f->origen = cambio_texto($f->origen);
			$f->estado = cambio_texto($f->estado);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));

	}
?>