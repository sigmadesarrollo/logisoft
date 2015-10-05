<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET[accion] == 1){
		$row = ObtenerFolio('traspasocredito','webpmm');
		$fecha = date('d/m/Y');
		
		echo $row[0].",".$fecha;
		
	}else if($_GET[accion] == 2){
		$row = split(",",$_GET[arre]);
		$s = "INSERT INTO traspasocredito
		(fechatraspaso,guia,importe,remitente,destinatario,origen,destino,sucursaltraspaso,idusuario,idsucursal,fecha)
		VALUES
		('".cambiaf_a_mysql($row[0])."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."',
		'".$row[6]."', ".$row[7].", ".$_SESSION[IDUSUARIO].",".$_SESSION[IDSUCURSAL].",CURRENT_TIMESTAMP)";
		$r = mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
		
		$s = "UPDATE pagoguias SET sucursalacobrar=".$row[7]." WHERE guia='".$row[1]."'";
		mysql_query($s,$l) or die($s);
		
		echo "ok,".$folio;
		
	}else if($_GET[accion] == 3){
		$s = "SELECT DATE_FORMAT(t.fechatraspaso,'%d/%m/%Y') AS fecha,t.guia,t.importe,t.remitente,t.destinatario,
		t.origen,t.destino,t.sucursaltraspaso FROM traspasocredito t
		INNER JOIN catalogosucursal cs ON t.sucursaltraspaso = cs.id
		WHERE t.folio=".$_GET[traspaso];
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS sucursal FROM catalogosucursal
				id=".$f->sucursaltraspaso;
				$t = mysql_query($s,$l) or die($s);
				$tt = mysql_fetch_object($t);
				
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->origen = cambio_texto($f->origen);
				$f->destino = cambio_texto($f->destino);
				$f->sucursal = cambio_texto($tt->sucursal);
				$registros[] = $f;
			}
			echo str_replace('""','null',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion] == 4){
		$s = "SELECT * FROM (
		SELECT gv.id as guia, IFNULL(gv.total,0) AS importe, cd.descripcion AS destino, cs.descripcion AS origen,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente, 
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario
		FROM guiasventanilla gv
		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.condicionpago=1 AND gv.estado<>'ALMACEN ORIGEN' AND gv.estado<>'EN TRANSITO'
		AND gv.estado<>'CANCELADO' AND gv.estado<>'AUTORIZACION PARA SUSTITUIR'
		AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = gv.id) AND isnull(gv.factura)
		UNION
		SELECT ge.id as guia, IFNULL(ge.total,0) AS importe, cd.descripcion AS destino, cs.descripcion AS sucursalorigen,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente, 
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario
		FROM guiasempresariales ge
		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.tipopago='CREDITO' AND ge.estado<>'ALMACEN ORIGEN' AND ge.estado<>'EN TRANSITO'
		and tipoguia = 'CONSIGNACION' AND isnull(ge.factura)
		AND NOT EXISTS (SELECT * FROM traspasocredito WHERE traspasocredito.guia = ge.id))
		t WHERE guia='".$_GET[guia]."'";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->origen = cambio_texto($f->origen);
				$f->destino = cambio_texto($f->destino);
				$registros[] = $f;
			}
			echo str_replace('""','null',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}
?>