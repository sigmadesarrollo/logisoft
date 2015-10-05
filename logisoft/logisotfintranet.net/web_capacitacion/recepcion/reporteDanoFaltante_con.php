<?  session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');	
	$link=Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$coma = ",";
		$obj = split($coma,$_GET[arreglo]);
		$obj[4] = cambio_texto($obj[4]);
		$s = mysql_query("INSERT INTO reportedanosfaltante
		(guia,dano,faltante,empleado1,comentarios,sucursal,idusuario,fecha)
		VALUES
		('".$obj[0]."',".$obj[1].",".$obj[2].",".$obj[3].",UCASE('".trim($obj[4])."'),".$_SESSION[IDSUCURSAL].",
		'".$_SESSION[IDUSUARIO]."', current_timestamp())",$link) or die(mysql_error($link).$s);
		$id = mysql_insert_id();
		
		if($obj[1]==1){//dano
			$s = "UPDATE guiasventanilla SET danos = IF('$obj[1]'='1','S','N') where id = '$obj[0]'";
			mysql_query($s,$link) or die($s);
			
			$s = "UPDATE guiasempresariales SET danos = IF('$obj[1]'='1','S','N') where id = '$obj[0]'";
			mysql_query($s,$link) or die($s);
		}
		if($obj[2]==1){//faltante -> incompleto
			$s = "UPDATE guiasventanilla SET incompleta = IF('$obj[2]'='1','S','N') where id = '$obj[0]'";
			mysql_query($s,$link) or die($s);
			
			$s = "UPDATE guiasempresariales SET incompleta = IF('$obj[2]'='1','S','N') where id = '$obj[0]'";
			mysql_query($s,$link) or die($s);
		}
		
		$s = "INSERT INTO reportedanosfaltante_detallado 
		SELECT NULL,IF('$obj[1]'='1','DANO','FALTANTE') AS tipo,t.enqueja,t.folioqueja,'$obj[0]' AS guia,t.estado,'$obj[5]' AS destinatario,
		t.destino,t.origen,CURRENT_DATE,t.recepcion, UCASE('".trim($obj[4])."') AS comentarios, 
		'RECEPCION' AS segenero,".$_SESSION[IDSUCURSAL]."
		FROM (
		SELECT IF(m.nguia IS NOT NULL,'SI','NO') enqueja,IFNULL(m.folio,'') folioqueja,gv.estado,sd.prefijo AS destino,
		so.prefijo AS origen,rm.folio AS recepcion
		FROM reportedanosfaltante rdf
		INNER JOIN guiasventanilla gv ON rdf.guia=gv.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		LEFT JOIN recepcionmercancia rm ON rdf.recepcion = rm.folio
		LEFT JOIN moduloquejasdanosfaltantes m ON rdf.guia = m.nguia
		WHERE rdf.guia='$obj[0]' AND gv.id='$obj[0]'
		UNION
		SELECT IF(m.nguia IS NOT NULL,'SI','NO') enqueja,IFNULL(m.folio,'') folioqueja,ge.estado,sd.prefijo AS destino,
		so.prefijo AS origen,rm.folio AS recepcion
		FROM reportedanosfaltante rdf
		INNER JOIN guiasempresariales ge ON rdf.guia=ge.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id
		LEFT JOIN recepcionmercancia rm ON rdf.recepcion = rm.folio
		LEFT JOIN moduloquejasdanosfaltantes m ON rdf.guia = m.nguia
		WHERE rdf.guia='$obj[0]' AND ge.id='$obj[0]' )t";
		mysql_query($s,$link) or die($s);
		
		echo "ok,$id";
		
	}else if($_GET[accion]==2){//REPORTE DAÑOS Y FALTANTES
		$s = "SELECT r.guia, g.estado, g.destinatario, g.sucursaldestino,
		g.sucursalorigen, DATE_FORMAT(rep.fecha,'%d/%m/%Y') AS fecharecepcion,
		rep.folio,r.comentarios FROM reportedanosfaltante r
		INNER JOIN recepcionmercancia rep ON r.recepcion = rep.folio
		INNER JOIN (
		SELECT gv.id AS guia,gv.estado,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destinatario,
		cd.descripcion AS sucursaldestino, cs.descripcion AS sucursalorigen
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen=cs.id
		INNER JOIN catalogosucursal cd ON gv.idsucursaldestino=cd.id
		INNER JOIN catalogocliente cc ON gv.iddestinatario = cc.id
		UNION
		SELECT ge.id AS guia,ge.estado,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destinatario,
		cd.descripcion AS sucursaldestino, cs.descripcion AS sucursalorigen
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen=cs.id
		INNER JOIN catalogosucursal cd ON ge.idsucursaldestino=cd.id
		INNER JOIN catalogocliente cc ON ge.iddestinatario = cc.id) g ON r.guia=g.guia
		WHERE rep.idsucursal=".$_GET[sucursal]." 
		AND r.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'";
		$r = mysql_query($s,$link) or die($s);
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
		
	}else if($_GET[accion]==3){//FALTANTES EN OCURRE
		$obj = split(",",$_GET[arreglo]);
		$obj[4] = cambio_texto($obj[4]);
		$s = mysql_query("INSERT INTO reportedanosfaltanteocurre
		(guia,faltante,empleado1,comentarios,sucursal,idusuario,fecha)
		VALUES
		('".$obj[0]."',".$obj[2].",".$obj[3].",UCASE('".trim($obj[4])."'),".$_SESSION[IDSUCURSAL].",
		'".$_SESSION[IDUSUARIO]."', current_timestamp())",$link) or die(mysql_error($link).$s);
		
		$id = mysql_insert_id();
		
		if($obj[2]==1){//faltante -> incompleto
			$s = "UPDATE guiasventanilla SET incompleta = IF('$obj[2]'='1','S','N') where id = '$obj[0]'";
			mysql_query($s,$link) or die($s);
			
			$s = "UPDATE guiasempresariales SET incompleta = IF('$obj[2]'='1','S','N') where id = '$obj[0]'";
			mysql_query($s,$link) or die($s);
		}
		
		$s = "INSERT INTO reportedanosfaltante_detallado 
		SELECT NULL,IF('$obj[1]'='1','','FALTANTE') AS tipo,t.enqueja,t.folioqueja,'$obj[0]' AS guia,t.estado,'$obj[5]' AS destinatario,
		t.destino,t.origen,CURRENT_DATE,t.recepcion, UCASE('".trim($obj[4])."') AS comentarios, 
		'OCURRE' AS segenero,".$_SESSION[IDSUCURSAL]."
		FROM (
		SELECT IF(m.nguia IS NOT NULL,'SI','NO') enqueja,IFNULL(m.folio,'') folioqueja,gv.estado,sd.prefijo AS destino,
		so.prefijo AS origen,rm.folio AS recepcion
		FROM reportedanosfaltanteocurre repo
		INNER JOIN guiasventanilla gv ON rdf.guia=gv.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		LEFT JOIN recepcionmercancia rm ON repo.recepcion = rm.folio
		LEFT JOIN moduloquejasdanosfaltantes m ON repo.guia = m.nguia
		WHERE repo.guia='$obj[0]' AND gv.id='$obj[0]'
		UNION
		SELECT IF(m.nguia IS NOT NULL,'SI','NO') enqueja,IFNULL(m.folio,'') folioqueja,ge.estado,sd.prefijo AS destino,
		so.prefijo AS origen,rm.folio AS recepcion
		FROM reportedanosfaltanteocurre repo
		INNER JOIN guiasempresariales ge ON rdf.guia=ge.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id
		LEFT JOIN recepcionmercancia rm ON repo.recepcion = rm.folio
		LEFT JOIN moduloquejasdanosfaltantes m ON repo.guia = m.nguia
		WHERE repo.guia='$obj[0]' AND ge.id='$obj[0]' )t";
		mysql_query($s,$link) or die($s);
		
		echo "ok,$id";
	}
?>
	