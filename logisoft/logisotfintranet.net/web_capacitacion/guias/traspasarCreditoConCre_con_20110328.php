<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET[accion] == 1){
		$row = ObtenerFolio('traspasocreditoconcre','webpmm');
		$fecha = date('d/m/Y');
		
		echo $row[0].",".$fecha;
		
	}else if($_GET[accion] == 2){
		$row = split(",",$_GET[arre]);
		
		if(count($row)>8){
			die("Hubo un problema al guardar, <br>$_GET[arre]");
		}
		
		$s = "SELECT folio FROM solicitudcredito WHERE cliente = '".$row[7]."' and estado = 'ACTIVADO'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)<1){
			die("El cliente no tiene un credito registrado activo");
		}
		
		$s = "select iddestinatario, estado, ocurre 
		from guiasventanilla where id = '".$row[1]."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		if($f->ocurre==1 && ($f->estado == 'ENTREGADA' || $f->estado == 'POR ENTREGAR')){
			die("Esta guia es ocurre ya fue entregada y afecta en el corte, no puede ser ");
		}
		
		if($f->iddestinatario != $row[7]){
			$s = "update guiasventanilla 
			set iddestinatario = $row[7]
			where id = '".$row[1]."'";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE pagoguias 
			SET cliente = '".$row[7]."'
			WHERE guia = '".$row[1]."'";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "SELECT scd.id 
		FROM solicitudcreditosucursaldetalle scd
		INNER JOIN solicitudcredito sc ON scd.solicitud = sc.folio
		INNER JOIN guiasventanilla gv ON sc.cliente = IF(gv.tipoflete=1, gv.iddestinatario, gv.idremitente)
		WHERE (scd.idsucursal=0 OR scd.idsucursal='$_SESSION[IDSUCURSAL]')
		AND gv.id = '".$row[1]."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)<1){
			die("El cliente no tiene cr&eacute;dito en esta sucursal");
		}
		
		$s = "INSERT INTO traspasocreditoconcre
		(fechatraspaso,guia,importe,remitente,destinatario,origen,destino,sucursaltraspaso,idusuario,idsucursal,fecha)
		VALUES
		('".cambiaf_a_mysql($row[0])."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."',
		'".$row[6]."', '".$row[7]."', '".$_SESSION[IDUSUARIO]."','".$_SESSION[IDSUCURSAL]."',CURRENT_TIMESTAMP)";
		$r = mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id($l);
		
		//insertar en seguimiento guias
		$s = "INSERT INTO seguimiento_guias (guia,ubicacion,estado,fecha,hora,usuario)
		VALUES
		('".$row[1]."','".$_SESSION[IDSUCURSAL]."','TRASPASO CREDITO',CURRENT_DATE(),CURRENT_TIME(),'".$_SESSION[IDUSUARIO]."')";
		$r = mysql_query($s,$l) or die($s);	
		
		#Traspasar guias de contado a credito
		$s = "UPDATE guiasventanilla SET condicionpago=1 WHERE id='".$row[1]."'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE guiasempresariales SET tipopago='CREDITO' WHERE id='".$row[1]."'";
		mysql_query($s,$l) or die($s);
		#------
		
		#registrar el cambio de contado a credito en el reporte maestro de cobranza.
		if(substr($row[1],0,3) == "999"){
			$s = "call proc_RegistroCobranza('VENTA', '".$row[1]."', 'EMPRESARIAL', '', 0, 0)";
		}else{
			$s = "CALL proc_RegistroCobranza('VENTA', '".$row[1]."', 'VENTANILLA', '', 0, 0);";
		}
		mysql_query($s,$l) or die($s);
		
		#--- cambiar el pagoguias
		$s = "UPDATE pagoguias SET credito = 'SI', pagado = 'N',
		usuariocobro = NULL, sucursalcobro = NULL, fechapago = NULL
		WHERE guia = '".$row[1]."'";
		mysql_query($s,$l) or die($s);
		#---
		
		#--- Verificar si existe el pago
		$s = "DELETE FROM formapago WHERE guia = '".$row[1]."'";
		mysql_query($s,$l) or die($s);
		
		
		echo "ok,".$folio;
	}else if($_GET[accion] == 3){
		$s = "SELECT DATE_FORMAT(t.fechatraspaso,'%d/%m/%Y') AS fecha,t.guia,t.importe,t.remitente,t.destinatario,
		t.origen,t.destino,t.sucursaltraspaso, cs.descripcion AS sucursal FROM traspasocreditoconcre t
		INNER JOIN catalogosucursal cs ON t.sucursaltraspaso = cs.id
		WHERE t.folio=".$_GET[traspaso];
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->remitente = cambio_texto($f->remitente);
				$f->destinatario = cambio_texto($f->destinatario);
				$f->origen = cambio_texto($f->origen);
				$f->destino = cambio_texto($f->destino);
				$f->sucursal = cambio_texto($f->sucursal);
				$registros[] = $f;
			}
			echo str_replace('""','null',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion] == 4){
		$s = "SELECT IFNULL(gv.total,0) AS importe, cd.descripcion AS destino, cs.descripcion AS origen,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente, 
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario, gv.iddestinatario, gv.total
		FROM guiasventanilla gv
		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
		INNER JOIN catalogosucursal cs ON gv.idsucursalorigen = cs.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.tipoflete=1 AND gv.condicionpago=0 AND 
		(gv.estado='ALMACEN DESTINO' OR gv.estado='EN REPARTO EAD' OR ((gv.estado='ENTREGADA' or gv.estado='POR ENTREGAR') AND gv.fechaentrega=CURRENT_DATE))
		and ISNULL(gv.factura) and gv.idsucursaldestino = '$_SESSION[IDSUCURSAL]' and gv.id = '$_GET[guia]'
		UNION
		SELECT IFNULL(ge.total,0) AS importe, cd.descripcion AS destino, cs.descripcion AS sucursalorigen,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente, 
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario, ge.iddestinatario, ge.total
		FROM guiasempresariales ge
		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id
		INNER JOIN catalogosucursal cs ON ge.idsucursalorigen = cs.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.tipoflete='POR COBRAR' AND ge.tipopago='CONTADO' 
		and (ge.estado='ALMACEN DESTINO' OR ge.estado='EN REPARTO EAD' OR ((ge.estado='ENTREGADA' OR ge.estado='POR ENTREGAR') AND ge.fechaentrega=CURRENT_DATE))
		and ISNULL(ge.factura) and ge.idsucursaldestino = '$_SESSION[IDSUCURSAL]' and ge.id = '$_GET[guia]'";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			/*$s = "SELECT IF(IFNULL(sc.montoautorizado,0) <= (IFNULL(SUM(pg.total),0)+'$f->total') OR cc.activado='NO','NO','SI') AS cambiable
			FROM solicitudcredito sc 
			INNER JOIN catalogocliente cc ON sc.cliente = cc.id 
			LEFT JOIN pagoguias pg ON sc.cliente = pg.cliente AND pg.pagado = 'N' AND pg.credito='SI'
			WHERE sc.cliente = $f->iddestinatario  AND sc.estado = 'ACTIVADO'";
			$rx = mysql_query($s,$l) or die($s);
			$fx = mysql_fetch_object($rx);*/
			
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
			
			
			echo "({'encontro':'SI',
				   'cambiable':'SI',
				   'datos':".json_encode($f)."
				   })";
		}else{
			echo "({'encontro':'NO'})";
		}
	}else if($_GET[accion] == 5){
		$s = "SELECT id, CONCAT_WS(' ',nombre,paterno,materno) ncliente
		FROM catalogocliente WHERE id = '$_GET[cliente]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		echo "(".json_encode($f).")";
	}
?>