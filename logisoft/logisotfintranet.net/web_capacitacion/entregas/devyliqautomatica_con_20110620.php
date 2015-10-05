<?	session_start();
	require_once("../Conectar.php");
	require_once("../fn-error.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT obtenerFolio('liquidacionead',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
		
		$s = "SELECT DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fecha, 
		(SELECT descripcion FROM catalogosucursal where id=".$_SESSION[IDSUCURSAL].") as sucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		
		echo $fo->folio.",".$f->fecha.",".$_SESSION[IDSUCURSAL].",".$f->sucursal;
		
	}else if($_GET[accion]==2){//
		/*$s = "SELECT id FROM catalogounidad WHERE numeroeconomico = '".$_GET[unidad]."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT MAX(folio) folioreparto FROM repartomercanciaead WHERE unidad = '$f->id' and liquidado = 0 
		and sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "UPDATE devyliqautomatica SET reparto = $f->folioreparto 
		WHERE unidad = '".$_GET[unidad]."' AND liquidada = 'N' 
		AND date(fechahora)=current_date";
		mysql_query($s,$l) or die($s);*/
		
		/*$s = "UPDATE catalogounidad cu
		INNER JOIN repartomercanciaead re ON cu.id = re.unidad AND liquidado = 0
		INNER JOIN repartomercanciadetalle rd ON re.folio = rd.idreparto AND re.sucursal = rd.sucursal 
		INNER JOIN devyliqautomatica dl ON rd.guia = dl.guia
		SET dl.reparto = re.folio
		WHERE cu.numeroeconomico = '".$_GET[unidad]."'";
		mysql_query($s,$l) or die($s);*/
		
		$s = "SELECT id FROM catalogounidad WHERE numeroeconomico = '".$_GET[unidad]."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$s = "SELECT MAX(folio) folioreparto FROM repartomercanciaead WHERE unidad = '$f->id' and liquidado = 0 
		and sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$folioreparto2 = $f->folioreparto;
		
		$s = "SELECT reparto 
		FROM devyliqautomatica 
		WHERE liquidada = 'N' AND sucursal = $_SESSION[IDSUCURSAL] 
		AND unidad = '".$_GET[unidad]."' GROUP BY unidad";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$folioreparto = $f->reparto;
		
		$s = "SELECT r.folio, CONCAT_WS(' ',c1.nombre,c1.apellidopaterno) AS conductor1,
		CONCAT_WS(' ',c2.nombre,c2.apellidopaterno) AS conductor2, c1.id as idc1
		FROM repartomercanciaead r
		INNER JOIN catalogoempleado c1 ON r.conductor1 = c1.id
		INNER JOIN catalogoempleado c2 ON r.conductor2 = c2.id
		WHERE r.folio=$folioreparto2 AND r.sucursal = ".$_SESSION[IDSUCURSAL];
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->conductor1 = utf8_encode($f->conductor1);
			$f->conductor2 = utf8_encode($f->conductor2);
			
			$principal = str_replace('null','""',json_encode($f));
			
			$s = "(SELECT dl.guia, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destinatario, 
			IF(gv.tipoflete=0,'PAGADA', 'POR COBRAR') AS tipoflete, 
			IF(gv.condicionpago = 0, 'CONTADO', 'CREDITO') AS condicionpago,
			if(dl.estado='C', 'D','E') as estado, cm.descripcion AS motivo, dl.efectivo, dl.importecheque, dl.importenotacredito,
			IF(gv.tipoflete=1, gv.total,0) AS total
			FROM devyliqautomatica dl
			INNER JOIN guiasventanilla gv ON dl.guia = gv.id
			INNER JOIN catalogocliente cc ON cc.id = gv.iddestinatario	
			LEFT JOIN catalogomotivos cm ON dl.motivo = cm.id
			WHERE dl.liquidada = 'N' AND dl.sucursal = $_SESSION[IDSUCURSAL] AND gv.estado like '%EN REPARTO EAD%' 
			AND dl.unidad = '".$_GET[unidad]."'
			GROUP BY dl.guia)
			UNION 
			(SELECT dl.guia, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS destinatario, 
			gv.tipoflete, gv.tipopago AS condicionpago, 
			if(dl.estado='C', 'D','E') as estado, cm.descripcion AS motivo, dl.efectivo, dl.importecheque, dl.importenotacredito,
			0 AS total
			FROM devyliqautomatica dl
			INNER JOIN guiasempresariales gv ON dl.guia = gv.id
			INNER JOIN catalogocliente cc ON cc.id = gv.iddestinatario	
			LEFT JOIN catalogomotivos cm ON dl.motivo = cm.id
			WHERE dl.liquidada = 'N' AND dl.sucursal = $_SESSION[IDSUCURSAL] AND gv.estado like '%EN REPARTO EAD%' 
			AND dl.unidad = '".$_GET[unidad]."'
			GROUP BY dl.guia)
			";		
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
				while($f = mysql_fetch_object($r)){
					$arr[] = $f;
				}
			$detalle = str_replace('null','""',json_encode($arr));
			
			echo "({'principal':$principal,'detalleTabla':$detalle})";
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==3){//
		/*$s = "SELECT IFNULL(id,0)as folio FROM liquidacionead 
		WHERE idreparto=".$_GET[idreparto]." AND sucursal = '$_SESSION[IDSUCURSAL]'
		AND cerro = 1";
		$r = mysql_query($s,$l) or postError($s);
		if(mysql_num_rows($r)>0){
			die("Esta liquidacion ya fue cerrada");
		}*/
	
			$s = "SELECT reparto 
			FROM devyliqautomatica 
			WHERE liquidada = 'N' AND sucursal = $_SESSION[IDSUCURSAL] 
			AND unidad = '".$_GET[unidad]."' GROUP BY unidad";
			$r = mysql_query($s,$l) or postError($s);
			$f = mysql_fetch_object($r);
			$_GET[idreparto] = $f->reparto;
	
			$s="insert into liquidacionead set tipoliquidacion='A', folio = obtenerFolio('liquidacionead',".$_SESSION[IDSUCURSAL]."),
			idreparto=$_GET[idreparto],entregadas=$_GET[entregadas],devueltas=$_GET[devueltas],
			pagadas_credito='$_GET[pagadas_credito]',pagadas_contado='$_GET[pagadas_contado]',
			tpagadas_credito='$_GET[tpagadas_credito]',tpagadas_contado='$_GET[tpagadas_contado]',
			porcobrar_contado='$_GET[porcobrar_contado]',porcobrar_credito='$_GET[porcobrar_credito]',
			tporcobrar_contado='$_GET[tporcobrar_contado]',tporcobrar_credito='$_GET[tporcobrar_credito]',
			sucursal=$_SESSION[IDSUCURSAL],entrego='$_GET[entrego]',total='$_GET[total]',
			fecha = CURRENT_DATE,idusuario=$_SESSION[IDUSUARIO],
			cantidadentregada = '$_GET[cantidadentregada]', 
			diferencia =  $_GET[total]-$_GET[cantidadentregada],cerro=1";
			mysql_query($s,$l) or postError($s);
			$idliquidacion = mysql_insert_id($l);
			
			/*insertar el seguimiento ventanilla y emprsarial*/
			$s = "INSERT INTO seguimiento_guias
			(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
			SELECT dyl.guia,'$_SESSION[IDSUCURSAL]','',
			CONCAT(IF(dyl.estado='E','ENTREGADA','DEVUELTA ALMACEN'),IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), 
			CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','',''
			FROM devyliqautomatica AS dyl 
			INNER JOIN guiasventanilla g ON dyl.guia = g.id
			WHERE dyl.reparto = '$_GET[idreparto]' AND g.estado= 'EN REPARTO EAD' 
			AND dyl.sucursal = ".$_SESSION[IDSUCURSAL];
			mysql_query($s,$l) or postError($s);
			
			$s = "INSERT INTO seguimiento_guias
			(guia,ubicacion,unidad,estado,fecha,hora,usuario,bitacora,embarque)
			SELECT dyl.guia,'$_SESSION[IDSUCURSAL]','',
			CONCAT(IF(dyl.estado='E','ENTREGADA','DEVUELTA ALMACEN'),IF(g.incompleta='S',' INCOM',''),IF(g.danos='S',' DAÑO','')), 
			CURRENT_DATE, CURRENT_TIME, '$_SESSION[IDUSUARIO]','',''
			FROM devyliqautomatica AS dyl 
			INNER JOIN guiasempresariales g ON dyl.guia = g.id
			WHERE dyl.reparto = '$_GET[idreparto]' AND g.estado= 'EN REPARTO EAD' 
			AND dyl.sucursal = ".$_SESSION[IDSUCURSAL];
			mysql_query($s,$l) or postError($s);
			
			/**************************************************/
			
			#actualizar guias
			$s = "UPDATE guiasventanilla gv
			INNER JOIN devyliqautomatica dyl ON gv.id = dyl.guia
			SET gv.estado = IF(dyl.estado = 'E','ENTREGADA','ALMACEN DESTINO'), gv.firma = dyl.firma,
			gv.recibio = dyl.recibe, gv.tipoidentificacion = dyl.tipoidentificacion,
			gv.fechaentrega = if(dyl.estado = 'E',current_date,null),
			gv.numeroidentificacion	= dyl.noidentificacion, gv.entradasalida='ENTRADA'
			WHERE dyl.reparto = $_GET[idreparto] AND dyl.sucursal = ".$_SESSION[IDSUCURSAL]."
			AND gv.estado = 'EN REPARTO EAD'";
			mysql_query($s,$l) or postError($s);
			
			$s = "UPDATE guiasempresariales gv
			INNER JOIN devyliqautomatica dyl ON gv.id = dyl.guia
			SET gv.estado = IF(dyl.estado = 'E','ENTREGADA','ALMACEN DESTINO'), gv.firma = dyl.firma,
			gv.recibio = dyl.recibe, gv.tipoidentificacion = dyl.tipoidentificacion,
			gv.fechaentrega = if(dyl.estado = 'E',current_date,null),
			gv.numeroidentificacion	= dyl.noidentificacion, gv.entradasalida='ENTRADA'
			WHERE dyl.reparto = $_GET[idreparto] AND dyl.sucursal = ".$_SESSION[IDSUCURSAL]."
			AND gv.estado = 'EN REPARTO EAD'";
			mysql_query($s,$l) or postError($s);
			
			#actualizar paquetes
			$s = "UPDATE guiaventanilla_unidades gu
			INNER JOIN devyliqautomatica dyl ON dyl.guia = gu.idguia
			SET gu.unidad = '', gu.proceso = IF(dyl.estado='E','ENTREGADA','ALMACEN DESTINO')
			WHERE  dyl.reparto = $_GET[idreparto] AND dyl.sucursal = ".$_SESSION[IDSUCURSAL]."
			and gu.ubicacion = $_SESSION[IDSUCURSAL] AND gu.proceso = 'EN REPARTO EAD'";
			mysql_query($s,$l) or postError($s);
			
			$s = "UPDATE guiasempresariales_unidades gu
			INNER JOIN devyliqautomatica dyl ON dyl.guia = gu.idguia
			SET gu.unidad = '', gu.proceso = IF(dyl.estado='E','ENTREGADA','ALMACEN DESTINO')
			WHERE dyl.reparto = $_GET[idreparto] AND dyl.sucursal = ".$_SESSION[IDSUCURSAL]."
			and gu.ubicacion = $_SESSION[IDSUCURSAL] AND gu.proceso = 'EN REPARTO EAD'";
			mysql_query($s,$l) or postError($s);
			
			#***********************************************************************
			
			$s = "delete from liquidacion_detalle_tmp where idusuario = $_SESSION[IDUSUARIO]";
			mysql_query($s,$l) or postError($s);
			
			$s = "insert into liquidacion_detalle_tmp
			(SELECT gd.sector, gd.id AS guia, IFNULL(gd.factura,''), cs.descripcion AS origen,
				gd.iddestinatario,
				CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) AS destinatario,
				IF(gd.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete,
				IF(gd.condicionpago=0,'CONTADO','CREDITO') AS condicionpago,
				gd.total AS importe,IF(dyl.estado='E','ENTREGADA','ALMACEN DESTINO')AS estado,
				IF((gd.tipoflete=1 OR gd.tipoflete=1) && pg.pagado='N'
				   ,'1','0') AS seleccion, 'N', pg.pagado,
				dyl.motivo, '$_SESSION[IDUSUARIO]',dyl.recibe,dyl.tipoidentificacion, dyl.noidentificacion,
				dyl.efectivo,dyl.importecheque,dyl.nocheque,dyl.bancocheque,dyl.folionotacredito,
				dyl.importenotacredito,".$_SESSION[IDSUCURSAL]."
				FROM repartomercanciadetalle AS rm
				INNER JOIN guiasventanilla AS gd ON gd.id=rm.guia
				INNER JOIN catalogosucursal AS cs ON gd.idsucursalorigen = cs.id
				INNER JOIN catalogocliente AS cc ON gd.iddestinatario = cc.id
				INNER JOIN devyliqautomatica dyl ON gd.id = dyl.guia
				LEFT JOIN pagoguias pg ON gd.id = pg.guia
				WHERE dyl.sucursal = $_SESSION[IDSUCURSAL] AND dyl.reparto = $_GET[idreparto])
			UNION
			(SELECT gd.sector, gd.id AS guia, IFNULL(gd.factura,''), cs.descripcion AS origen,
				CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) AS destinatario,
				gd.iddestinatario,
				gd.tipoflete,
				gd.tipopago AS condicionpago,
				gd.total AS importe,IF(dyl.estado='E','ENTREGADA','ALMACEN DESTINO')AS estado,
				IF((gd.tipoflete='POR COBRAR' AND pg.pagado='N')
				   ,'1','0') AS seleccion, 'N', pg.pagado,
				dyl.motivo, '$_SESSION[IDUSUARIO]',dyl.recibe,dyl.tipoidentificacion, dyl.noidentificacion,
				dyl.efectivo,dyl.importecheque,dyl.nocheque,dyl.bancocheque,dyl.folionotacredito,
				dyl.importenotacredito,".$_SESSION[IDSUCURSAL]."
				FROM repartomercanciadetalle AS rm
				INNER JOIN guiasempresariales AS gd ON gd.id=rm.guia 
				INNER JOIN catalogosucursal AS cs ON gd.idsucursalorigen = cs.id
				INNER JOIN catalogocliente AS cc ON gd.iddestinatario = cc.id
				INNER JOIN devyliqautomatica dyl ON gd.id = dyl.guia
				LEFT JOIN pagoguias pg ON gd.id = pg.guia
				WHERE dyl.sucursal = $_SESSION[IDSUCURSAL] AND dyl.reparto = $_GET[idreparto])";
			mysql_query($s,$l) or postError($s);
			
			$sql="INSERT INTO liquidacion_detalleead
			SELECT 0 as id,'$idliquidacion', sector,guia,factura,origen,
			iddestinatario, destinatario,tipoflete,
			condicionpago,importe,estado,seleccion, seleccionada, pagada, motivo,
			idusuario,nombre,identificacion,numero_id,
			efectivo,cheque,ncheque,banco,nnotacredito,notacredito,sucursal
			FROM liquidacion_detalle_tmp 
			WHERE idusuario = '$_SESSION[IDUSUARIO]'";
			mysql_query($sql,$l) or postError($sql);
			
			$s = "SELECT guia FROM liquidacion_detalleead 
			WHERE idliquidacion = ".$idliquidacion." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$rs = mysql_query($s,$l) or postError($s);
			while($t = mysql_fetch_object($rs)){
				$s = "UPDATE actividadusuario SET estado = 1
				WHERE tipo = 'inventario' AND referencia = UCASE('$t->guia')";
				mysql_query($s,$l) or postError("$s<br>error en linea ".__LINE__);
			}
			
			#insertar el motivo de loas guias devueltas en el detalle
			$s = "SELECT ld.guia, cm.descripcion
			FROM liquidacion_detalleead ld
			INNER JOIN catalogomotivos cm ON ld.motivo = cm.id
			WHERE ld.estado = 'ALMACEN DESTINO' AND ld.idliquidacion = ".$idliquidacion."";
			$rs = mysql_query($s,$l) or postError($s);
			while($t = mysql_fetch_object($rs)){
				$s = "UPDATE seguimiento_guias SET motivodevolucion = '$t->descripcion' WHERE guia = '$t->guia' ORDER BY id DESC LIMIT 1";
				mysql_query($s,$l) or postError("$s<br>error en linea ".__LINE__);
			}
			
			$s = "(SELECT dyl.guia, dyl.estado, IF(dyl.efectivo>0 OR dyl.importecheque>0 OR dyl.importenotacredito>0, 'SI', 'NO') pagado, 
			dyl.efectivo + dyl.importecheque + dyl.importenotacredito AS importetotal,
			dyl.efectivo, dyl.bancocheque, dyl.nocheque, dyl.importecheque, dyl.folionotacredito, dyl.importenotacredito,
			IF(gv.tipoflete=0,'PAGADA','POR COBRAR') AS tipoflete, 
			IF(gv.condicionpago=0,'CONTADO','CREDITO') AS tipopago, 
			'V' AS tipove, 'N' AS tipoguia, gv.fecha,
			gv.tflete, gv.texcedente, gv.tcostoead, gv.trecoleccion, gv.tseguro, gv.tcombustible, gv.totros, 
			gv.subtotal, gv.tiva, gv.ivaretenido, gv.total,
			IF(gv.tipoflete=0,gv.idremitente,gv.iddestinatario) AS cliente
			FROM devyliqautomatica dyl
			INNER JOIN guiasventanilla gv ON dyl.guia = gv.id
			WHERE reparto = '$_GET[idreparto]' AND sucursal = '$_SESSION[IDSUCURSAL]')
			UNION
			(SELECT dyl.guia, dyl.estado, IF(dyl.efectivo>0 OR dyl.importecheque>0 OR dyl.importenotacredito>0, 'SI', 'NO') pagado, 
			dyl.efectivo + dyl.importecheque + dyl.importenotacredito AS importetotal,
			dyl.efectivo, dyl.bancocheque, dyl.nocheque, dyl.importecheque, dyl.folionotacredito, dyl.importenotacredito,
			ge.tipoflete, ge.tipopago, 'G' AS tipove, ge.tipoguia, ge.fecha,
			ge.tflete, ge.texcedente, ge.tcostoead, ge.trecoleccion, ge.tseguro, ge.tcombustible, ge.totros, 
			ge.subtotal, ge.tiva, ge.ivaretenido, ge.total,
			IF(ge.tipoflete='PAGADA',ge.idremitente,ge.iddestinatario) AS cliente
			FROM devyliqautomatica dyl
			INNER JOIN guiasempresariales ge ON dyl.guia = ge.id
			WHERE reparto = '$_GET[idreparto]' AND sucursal = '$_SESSION[IDSUCURSAL]')";
			$m= mysql_query($s,$l) or postError($s);
			if (mysql_num_rows($m)>0){
				while ($f=mysql_fetch_object($m)){
					
					#si se entrego
					if($f->estado == 'E'){
						#si se pago
						if($f->pagado == 'SI'){
							
							#insertardetalle de la factura, la guia que se va a facturar.
							if($f->tipopago!='CREDITO' && $f->tipoflete == 'POR COBRAR' 
							&& $f->tipoguia!='PREPAGADA' && $f->tipoguia!='CONSIGNACION'){
								
								$sql = "INSERT INTO formapago(guia,procedencia,tipo,total,efectivo,
								tarjeta,transferencia,cheque,ncheque,banco,
								notacredito,nnotacredito,sucursal,usuario,fecha,cliente)
								SELECT '$f->guia','M','X','$f->total','$f->efectivo',
								0 AS tarjeta,0 AS transferencia,'$f->importecheque','$f->nocheque','$f->bancocheque',
								'$f->importenotacredito','$f->folionotacredito',
								'$_SESSION[IDSUCURSAL]','$_SESSION[IDUSUARIO]',curdate(),'$f->cliente'";
								$t = mysql_query($sql,$l) or die($sql);	
								
								$sq = "UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
								sucursalcobro='$_SESSION[IDSUCURSAL]' WHERE guia='$f->guia' AND pagado='N' ";					
								$d = mysql_query(str_replace("''",'NULL',$sq),$l) or postError($sq);
							}
							#se insreta la forma de pago
						}
						$s = "SELECT * FROM entregasespecialesead WHERE guia=$f->guia";
						$r=mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($r)){
						$s = "UPDATE lasalertas SET entesp=entesp-1 WHERE sucursal = '$_SESSION[IDSUCURSAL]';";
						mysql_query($s,$l) or die($s."<BR>".mysql_error($l));
						}
					}
				}
			}
			
			# se actualiza el reparto para ponerlo como liquidado
			$sql="UPDATE repartomercanciaead SET liquidado = 1
			WHERE folio = '$_GET[idreparto]' AND sucursal = '$_SESSION[IDSUCURSAL]'";
			mysql_query($sql,$l) or postError($sql);
			
			# se actualiza los datos de devyliq para ponerlo como liquidado
			$sql="UPDATE devyliqautomatica SET liquidada = 'S'
			WHERE reparto = '$_GET[idreparto]' AND sucursal = '$_SESSION[IDSUCURSAL]'";
			mysql_query($sql,$l) or postError($sql);
			
			echo "ok";
			
	}else if($_GET[accion]==4){
		$foliosucursal = $_GET[folio];
		$s = "SELECT id FROM liquidacionead WHERE folio = $_GET[folio] AND sucursal = $_SESSION[IDSUCURSAL]";
		$r=mysql_query($s,$l)or die($s); 
		$f = mysql_fetch_object($r);
		$_GET[folio] = $f->id;
	
		$s = "SELECT ce1.id idempleado1, CONCAT_WS(' ', ce1.nombre, ce1.apellidopaterno, ce1.apellidopaterno) AS conductor1,
		ce2.id idempleado2, CONCAT_WS(' ', ce2.nombre, ce2.apellidopaterno, ce2.apellidopaterno) AS conductor2,
		cu.numeroeconomico unidad,
		ld.idreparto,ld.entregadas,ld.devueltas,ld.pagadas_credito,ld.pagadas_contado,
		ld.tpagadas_credito,ld.tpagadas_contado,ld.porcobrar_contado,ld.porcobrar_credito,
		ld.tporcobrar_contado,ld.tporcobrar_credito,ld.sucursal,ld.entrego,ld.total,
		DATE_FORMAT(ld.fecha,'%d/%m/%Y')AS fecha,ld.cerro,ld.cantidadentregada,ld.diferencia,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado 
		FROM liquidacionead ld
		INNER JOIN catalogoempleado ce ON ld.entrego = ce.id
		INNER JOIN repartomercanciaead rm ON ld.idreparto = rm.folio AND rm.sucursal = $_SESSION[IDSUCURSAL]
		INNER JOIN catalogoempleado ce1 ON rm.conductor1 = ce1.id
		INNER JOIN catalogoempleado ce2 ON rm.conductor2 = ce2.id
		INNER JOIN catalogounidad cu ON rm.unidad = cu.id
		WHERE ld.id = ".$_GET[folio];	
		$r=mysql_query($s,$l)or die($s); 
			
		$registros = array();
		$principal = "";
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->empleado = cambio_texto($f->empleado);
			$f->foliodevyliq = $foliosucursal;
			$principal = str_replace('null','""',json_encode($f));
			
			$sql = "SELECT IFNULL(cs.descripcion,'') AS sector,ld.guia,ld.factura,ld.origen,
			ld.iddestinatario, ld.destinatario,ld.tipoflete,ld.condicionpago,ld.importe,IF(ld.estado='ENTREGADA','E','D') estado,ld.seleccion, seleccionada, pagada,
			ld.nombre,ld.identificacion,ld.numero_id, cm.descripcion AS motivo, 
			ld.efectivo, ld.cheque importecheque, ld.notacredito importenotacredito,
			ld.efectivo + ld.cheque + ld.notacredito  total
			FROM liquidacion_detalleead ld 
			LEFT JOIN catalogosector cs ON ld.sector=cs.id
			LEFT JOIN catalogomotivos cm ON ld.motivo = cm.id
			WHERE ld.idliquidacion=".$_GET[folio]." AND ld.sucursal = ".$_SESSION[IDSUCURSAL]."
			group by ld.guia";
			$r = mysql_query($sql,$l)or die($sql); 
			$registros= array();
			while($f = mysql_fetch_object($r)){
				$f->sector = cambio_texto($f->sector);
				$f->motivo = cambio_texto($f->motivo);
				$registros[] = $f;
			}
			$detalle = str_replace('null','""',json_encode($registros));
			
			echo "({principal:$principal,detalle:$detalle})";
			
		}else{
			echo "no encontro";
		}
	}
	?>