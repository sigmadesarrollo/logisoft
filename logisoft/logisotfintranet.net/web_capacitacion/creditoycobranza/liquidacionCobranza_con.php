<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion] == "0"){//BORARR TABLA LIQUIDACION COBRANZA DETALLE
		$s = "DELETE FROM liquidacioncobranzadetalle_tmp WHERE ".(($_GET[folio]!="")? "foliorelacion=".$_GET[folio]."
		AND" : "")." idusuario=".$_SESSION[IDUSUARIO]."";
		$s = "DELETE FROM liquidacioncobranzadetalle_tmp_ori WHERE ".(($_GET[folio]!="")? "foliorelacion=".$_GET[folio]."
		AND" : "")." idusuario=".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			mysql_query($s,$l) or die($s);
		echo "ok";
		
	}else if($_GET[accion] == 1){//OBTENER DATOS GENERALES LIQUIDACION COBRANZA
		$s = "SELECT obtenerFolio('liquidacioncobranza',".$_GET[sucursal].") as folio, DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fecha,
		(SELECT descripcion FROM catalogosucursal WHERE id=".$_GET[sucursal].")
		AS sucursal";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		while($f = mysql_fetch_object($r)){
			$f->sucursal = cambio_texto($f->sucursal);			
			$f->folio	 = $f->folio;
			$registros[] = $f;
		}
		$s = "DELETE FROM registroscompromisos WHERE idusuario=".$_SESSION[IDUSUARIO]." AND folioliquidacion IS NULL";
			mysql_query($s,$l) or die($s);
		$s = "DELETE FROM registrodecontrarecibos WHERE idusuario=".$_SESSION[IDUSUARIO]." AND folioliquidacion IS NULL";
			mysql_query($s,$l) or die($s);
		$s = "DELETE FROM liquidacioncobranzadetalle_tmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
		$s = "DELETE FROM liquidacioncobranzadetalle_tmp_ori WHERE idusuario=".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);	
		echo str_replace('null','""',json_encode($registros));
	
	}else if($_GET[accion] == 2){//OBTENER DATOS X FOLIO DE RELACION COBRANZA
		$s = "SELECT DATE_FORMAT(rc.fecharelacion,'%d/%m/%Y') AS fecharelacion, cs.descripcion AS sector,
		DAYOFWEEK(fecharelacion) AS dia, rc.cobrador FROM relacioncobranza rc
		LEFT JOIN catalogosector cs ON rc.sector = cs.id
		WHERE rc.folio = ".$_GET[folio]." AND rc.sucursal=".$_GET[sucursal]." AND NOT EXISTS (SELECT * FROM liquidacioncobranza lq WHERE rc.folio=lq.foliocobranza AND rc.sucursal = lq.sucursal AND lq.estado='LIQUIDADO')";
		//echo $s;
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->sector = cambio_texto($f->sector);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo str_replace('null','""',json_encode(0));
			}
			
	}else if($_GET[accion] == 3){//MOSTRAR DETALLE LIQUIDACION COBRANZA E INSERTAR A LA TABLA LIQUIDACION COBRANZA DETALLE
		$s = "SELECT rd.cliente, GROUP_CONCAT(rd.guia) AS guia, DATE_FORMAT(rd.fechaguia,'%d/%m/%Y') AS fecha,
			DATE_FORMAT(rd.fechavencimiento,'%d/%m/%Y') AS fechavencimiento, rd.factura,
			rd.saldo importe, rd.saldo AS saldoactual,
			/*IF(rcon.fecharegistro IS NULL,'NO',rcon.fecharegistro) AS revision,*/
			IF(rcon.fecharegistro IS NULL,'NO','SI') AS revision,
			'NO' as cobrar,		
			IF(rcon.contrarecibo IS NULL,'',rcon.contrarecibo) AS contrarecibo,
			IF(rcom.fechacompromiso IS NULL,'',DATE_FORMAT(rcom.fechacompromiso,'%d/%m/%Y')) AS compromiso,0 as oculto
			,0 as motivo
			FROM relacioncobranza rc
			INNER JOIN relacioncobranzadetalle rd ON rc.folio = rd.relacioncobranza AND rc.sucursal = rd.sucursal
			LEFT JOIN registrodecontrarecibos rcon ON rd.factura = rcon.factura
			LEFT JOIN registroscompromisos rcom ON rd.factura = rcom.factura
			WHERE rc.folio = ".$_GET[folio]." AND rc.sucursal=".$_GET[sucursal]." 
			AND rd.estado = 'No Revisadas' GROUP BY rd.factura"; 
		$r = mysql_query($s,$l) or die($s);
		$registrosr = array();
			while($f = mysql_fetch_object($r)){				
				$registrosr[] = $f;
			}
		
		$s = "SELECT rd.cliente, GROUP_CONCAT(rd.guia) AS guia, DATE_FORMAT(rd.fechaguia,'%d/%m/%Y') AS fecha,
			DATE_FORMAT(rd.fechavencimiento,'%d/%m/%Y') AS fechavencimiento, rd.factura,
			rd.saldo importe, rd.saldo AS saldoactual,
			/*IF(rcon.fecharegistro IS NULL,'NO',rcon.fecharegistro) AS revision,*/
			IF(rcon.fecharegistro IS NULL,'NO','SI') AS revision,
			'NO' as cobrar,		
			IF(rcon.contrarecibo IS NULL,'',rcon.contrarecibo) AS contrarecibo,
			IF(rcom.fechacompromiso IS NULL,'',DATE_FORMAT(rcom.fechacompromiso,'%d/%m/%Y')) AS compromiso,0 as oculto
			,0 as motivo
			FROM relacioncobranza rc
			INNER JOIN relacioncobranzadetalle rd ON rc.folio = rd.relacioncobranza AND rc.sucursal = rd.sucursal
			LEFT JOIN registrodecontrarecibos rcon ON rd.factura = rcon.factura
			LEFT JOIN registroscompromisos rcom ON rd.factura = rcom.factura
			WHERE rc.folio = ".$_GET[folio]." AND rc.sucursal=".$_GET[sucursal]."
			AND rd.estado <> 'No Revisadas' GROUP BY rd.factura"; 
		$r = mysql_query($s,$l) or die($s);
		$registrosc = array();
			while($f = mysql_fetch_object($r)){				
				$registrosc[] = $f;
			}
			
		$s = "INSERT INTO liquidacioncobranzadetalle_tmp 
		(id,foliorelacion,cliente,guia,fechaguia,fechavencimiento,factura,importe,saldoactual,
		revision,cobrar,contrarecibo,compromiso,efectivo,cheque,banco,ncheque,tarjeta,transferencia,
		notacredito,nnotacredito,idusuario,fecha,motivo,proceso)
		SELECT 0 AS id, ".$_GET[folio]." AS foliorelacion, rd.cliente,  GROUP_CONCAT(rd.guia), rd.fechaguia,
		rd.fechavencimiento, rd.factura, rd.saldo, rd.saldo AS saldoactual,
		IF(rcon.fecharegistro IS NULL,'NO','SI') AS revision,
		'NO' AS cobrar,		
		IF(rcon.contrarecibo IS NULL,0,rcon.contrarecibo) AS contrarecibo,
		IF(rcom.fechacompromiso IS NULL,'0000-00-00',  DATE_FORMAT(rcom.fechacompromiso,'%d/%m/%Y')) AS compromiso,
		0 AS efectivo, 0 AS cheque, 0 AS banco, '' AS ncheque, 0 AS tarjeta,
		0 AS transferencia,0 as notacredito,0 as nnotacredito, ".$_SESSION[IDUSUARIO]." AS idusuario,
		CURRENT_TIMESTAMP as fecha,0 as motivo, if(rd.estado='No Revisadas','R','C') 
		FROM relacioncobranza rc
		INNER JOIN relacioncobranzadetalle rd ON rc.folio = rd.relacioncobranza AND rc.sucursal = rd.sucursal
		LEFT JOIN registrodecontrarecibos rcon ON rd.factura = rcon.factura
		LEFT JOIN registroscompromisos rcom ON rd.factura = rcom.factura
		WHERE rd.estado = 'No Revisadas' AND rc.folio = ".$_GET[folio]." AND rc.sucursal=".$_GET[sucursal]." group by rd.factura";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO liquidacioncobranzadetalle_tmp 
		(id,foliorelacion,cliente,guia,fechaguia,fechavencimiento,factura,importe,saldoactual,
		revision,cobrar,contrarecibo,compromiso,efectivo,cheque,banco,ncheque,tarjeta,transferencia,
		notacredito,nnotacredito,idusuario,fecha,motivo,proceso)
		SELECT 0 AS id, ".$_GET[folio]." AS foliorelacion, rd.cliente, GROUP_CONCAT(rd.guia), rd.fechaguia,
		rd.fechavencimiento, rd.factura, rd.saldo, rd.saldo AS saldoactual,
		IF(rcon.fecharegistro IS NULL,'NO','SI') AS revision,
		'NO' AS cobrar,		
		IF(rcon.contrarecibo IS NULL,0,rcon.contrarecibo) AS contrarecibo,
		IF(rcom.fechacompromiso IS NULL,'0000-00-00',  DATE_FORMAT(rcom.fechacompromiso,'%d/%m/%Y')) AS compromiso,
		0 AS efectivo, 0 AS cheque, 0 AS banco, '' AS ncheque, 0 AS tarjeta,
		0 AS transferencia,0 as notacredito,0 as nnotacredito, ".$_SESSION[IDUSUARIO]." AS idusuario,
		CURRENT_TIMESTAMP as fecha,0 as motivo, if(rd.estado='No Revisadas','R','C') 
		FROM relacioncobranza rc
		INNER JOIN relacioncobranzadetalle rd ON rc.folio = rd.relacioncobranza AND rc.sucursal = rd.sucursal
		LEFT JOIN registrodecontrarecibos rcon ON rd.factura = rcon.factura
		LEFT JOIN registroscompromisos rcom ON rd.factura = rcom.factura
		WHERE rd.estado <> 'No Revisadas' AND rc.folio = ".$_GET[folio]." AND rc.sucursal=".$_GET[sucursal]." GROUP BY rd.factura";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into liquidacioncobranzadetalle_tmp_ori
		(id,foliorelacion,cliente,guia,fechaguia,fechavencimiento,factura,importe,saldoactual,
		revision,cobrar,contrarecibo,compromiso,efectivo,cheque,banco,ncheque,tarjeta,transferencia,
		notacredito,nnotacredito,idusuario,fecha,motivo,proceso)
		select null,foliorelacion,cliente,guia,fechaguia,fechavencimiento,factura,importe,saldoactual,
		revision,cobrar,contrarecibo,compromiso,efectivo,cheque,banco,ncheque,tarjeta,transferencia,
		notacredito,nnotacredito,idusuario,fecha,motivo,proceso
		from liquidacioncobranzadetalle_tmp where idusuario = ".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		
		$arev = str_replace('null','""',json_encode($registrosr));
		$acob = str_replace('null','""',json_encode($registrosc));
		
		echo '({"registrosr":'.$arev.', "registrosc":'.$acob.'})';
		
	}else if($_GET[accion] == 4){//OBTENER EL NUMERO DE DIA 
		$s = "SELECT DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."') AS fecha";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo $f->fecha;
		
	}else if($_GET[accion] == 5){//OBTENER CLIENTE Y MONTO DE FACTURA
		$sql = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS cliente
		FROM catalogocliente WHERE id=".$_GET[cliente]."";
		$r = mysql_query($sql,$l) or die($sql);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo str_replace('null','""',json_encode(0));
			}
	}else if($_GET[accion] == 6){//REGISTRAR LIQUIDACION COBRANZA
		/*arr[0] = fecha; arr[1] = sucursal; arr[2] = foliocobranza; arr[3] = cobrador; */
		$estado=$_GET[estado];
		$arr = split(",",$_GET[arre]);		
		$s = "INSERT INTO liquidacioncobranza
		(folio, estado,fechaliquidacion,sucursal,foliocobranza,cobrador,cantidadentregada,diferencia,idusuario,fecha) VALUES
		(obtenerFolio('liquidacioncobranza',".$arr[1]."),".(($_GET[estado]!="")? "'LIQUIDADO'" : "'GUARDADO'" ).",'".cambiaf_a_mysql($arr[0])."',
		".$arr[1].",".$arr[2].",".$arr[3].",'".$_GET[cantidadentregada]."',
		'".(($_GET[diferencia] < 0)?0:$_GET[diferencia])."', ".$_SESSION[IDUSUARIO].",CURRENT_TIMESTAMP())";
		$r = mysql_query($s,$l) or die($s."<br>".mysql_error($l));
		
		$folio = mysql_insert_id($l);
		
		$s = "SELECT folio FROM liquidacioncobranza WHERE id = $folio";
		$r = mysql_query($s,$l);
		$f = mysql_fetch_object($r);
		$foliosucursal = $f->folio;
		
		$s = "INSERT INTO liquidacioncobranzadetalle 
		(id,folioliquidacion,foliorelacion,cliente,guia,fechaguia,fechavencimiento,factura,
		importe,saldoactual,revision,cobrar,contrarecibo,compromiso,efectivo,cheque,banco,
		ncheque,tarjeta,transferencia,notacredito,nnotacredito,idusuario,fecha,motivo,proceso)
		SELECT 0 AS id, ".$folio." AS folioliquidacion,
		foliorelacion, cliente, guia, fechaguia, fechavencimiento,
		factura, importe, saldoactual, revision, cobrar, contrarecibo,
		compromiso, efectivo, cheque, banco, ncheque, tarjeta,transferencia,notacredito,nnotacredito,
		idusuario, fecha, motivo, proceso FROM liquidacioncobranzadetalle_tmp
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
			
		
		$s = "SELECT * FROM registrodecontrarecibos 
		WHERE folioliquidacion IS NULL AND idusuario = ".$_SESSION[IDUSUARIO]."";
		$sq = mysql_query($s,$l) or die($s);
		$c	= mysql_num_rows($sq);
			for($j=0;$j<$c;$j++){
				$s = "UPDATE registrodecontrarecibos SET folioliquidacion=".$folio." 
				WHERE folioliquidacion IS NULL AND idusuario = ".$_SESSION[IDUSUARIO]."";
				mysql_query($s,$l) or die($s);
			}		
		$s = "SELECT * FROM registroscompromisos 
		WHERE folioliquidacion IS NULL AND idusuario = ".$_SESSION[IDUSUARIO]."";
		$ss = mysql_query($s,$l) or die($s);
		$cc = mysql_num_rows($ss);
			for($k=0;$k<$cc;$k++){
				$s = "UPDATE registroscompromisos SET folioliquidacion=".$folio."
				WHERE folioliquidacion IS NULL AND idusuario = ".$_SESSION[IDUSUARIO]."";
				mysql_query($s,$l) or die($s);
			}
			
			
			if($_GET[estado]=="LIQUIDADO"){
				/*registrar pago si es liquidar*/		
				$s = "UPDATE facturacion 
				INNER JOIN liquidacioncobranzadetalle_tmp ON facturacion.folio = liquidacioncobranzadetalle_tmp.factura
				SET facturacion.enrelacion = 'N'
				WHERE liquidacioncobranzadetalle_tmp.idusuario = ".$_SESSION[IDUSUARIO];
				mysql_query($s,$l) or die($s);
				
				/*AQUI VA GUARDAR LA FORMA DE PAGO*/
				$s = "SELECT ld.id, ld.cliente
				FROM liquidacioncobranza lc
				INNER JOIN liquidacioncobranzadetalle ld ON lc.id=ld.folioliquidacion 
				WHERE lc.id=$folio and ld.cobrar='SI' GROUP BY ld.factura";
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
					$sql = "INSERT INTO formapago(guia,procedencia,tipo,total,efectivo,
					tarjeta,transferencia,cheque,ncheque,banco,notacredito,nnotacredito,sucursal,usuario,fecha,cliente)
						SELECT ld.factura,'C','X',sum(ld.importe) as importe,ld.efectivo,
						ld.tarjeta,ld.transferencia,ld.cheque,ld.ncheque,ld.banco,ld.notacredito,ld.nnotacredito,
						lc.sucursal,lc.idusuario,lc.fecha, ld.cliente
						FROM liquidacioncobranza lc
						INNER JOIN liquidacioncobranzadetalle ld ON lc.id=ld.folioliquidacion
						WHERE ld.id = $f->id";
					mysql_query($sql,$l) or die($sql);
					$idfp = mysql_insert_id($l);
					
					$s="call proc_RegistroCobranza('LIQUIDACIONCOBRANZA', '$idfp', '', '', $folio, $f->cliente);";
					mysql_query($s,$l) or die($s);
				}
				
				
					
					/*AQUI VA PARA AFECTAR ALA TABLA pagoguias*/
				$sql="SELECT lcd.guia,lcd.factura FROM liquidacioncobranza lc
				INNER JOIN liquidacioncobranzadetalle lcd ON lc.id=lcd.folioliquidacion
				WHERE lc.id=$folio and lcd.cobrar='SI' GROUP BY lcd.guia,lcd.factura";
				$r = mysql_query($sql,$l) or die($sql);
				while ($f = mysql_fetch_object($r)){
						$tguias = $f->guia;
						$tfacturas = $f->factura;
						
						#actualizando el reporte de cobranza, pago de liquidacion 
						#si tienen un solo folio ps se actualiza directamente
						#si no se hace un ciclo para que actualice todos
						if(count(split(",",$tguias))>1){
							$arretguias = split(",",$tguias);
							for($i=0; $i<count($arretguias); $i++){
								$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$arretguias[$i]', '', '', 0, 0);";
								mysql_query($s,$l) or die($s);
							}
						}else{
							$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$tguias', '', '', 0, 0);";
							mysql_query($s,$l) or die($s);
						}
						
						if(count(split(",",$tfacturas))>1){
							$arretfacturas = split(",",$tfacturas);
							for($i=0; $i<count($arretfacturas); $i++){
								$s = "INSERT INTO liquidacioncobranza_facturas SET folioliquidacion='$folio', 
								factura='$arretfacturas[$i]', sucursal='$_SESSION[IDSUCURSAL]'";
								mysql_query($s,$l) or die($s);
								
								$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$arretfacturas[$i]', '', '', 0, 0);";
								mysql_query($s,$l) or die($s);
								
								
								#**********SE INSERTARA EN REPORTE VENDEDORES PARA SAACAR COMISION**************************
								$sql="select ge.id as guias from guiasempresariales ge where ge.factura = '$arretfacturas[$i]'";
									$rxz = mysql_query($sql,$l) or die($sql);
									if (mysql_num_rows($rxz)>0){
										while ($fxz=mysql_fetch_object($rxz)){
											#SE REGISTRA EL PAGO DE GUIAS CONSIGNACION
											$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$fxz->guias');";
										}
									}
									
									#para el registro de folios prepagados en reporte vendedores
									$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$arretfacturas[$i]'";
									$rxz = mysql_query($sql,$l) or die($sql);
									if (mysql_num_rows($rxz)>0){
										while ($fxz=mysql_fetch_object($rxz)){
											#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
											$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$fxz->id');";
										}
									}
								#*********************************************************************************************
								
								
							}
						}else{
							$s = "INSERT INTO liquidacioncobranza_facturas SET folioliquidacion='$folio', 
								factura='$tfacturas', sucursal='$_SESSION[IDSUCURSAL]'";
								mysql_query($s,$l) or die($s);
							
							$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$tfacturas', '', '', 0, 0);";
							mysql_query($s,$l) or die($s);
							
							#**********SE INSERTARA EN REPORTE VENDEDORES PARA SAACAR COMISION**************************
							$sql="select ge.id as guias from guiasempresariales ge where ge.factura = '$tfacturas'";
								$rxz = mysql_query($sql,$l) or die($sql);
								if (mysql_num_rows($rxz)>0){
									while ($fxz=mysql_fetch_object($rxz)){
										#SE REGISTRA EL PAGO DE GUIAS CONSIGNACION
										$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$fxz->guias');";
									}
								}
								
								#para el registro de folios prepagados en reporte vendedores
								$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$tfacturas'";
								$rxz = mysql_query($sql,$l) or die($sql);
								if (mysql_num_rows($rxz)>0){
									while ($fxz=mysql_fetch_object($rxz)){
										#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
										$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$fxz->id');";
									}
								}
							#*********************************************************************************************
						}
						
						//continua el procedimiento normal
						$guias = "'".str_replace(",","','",$tguias)."'";
						$facturas = "'".str_replace(",","','",$tfacturas)."'";
						
						$s="UPDATE pagoguias
							SET pagado='S',
							fechapago=CURRENT_DATE,
							usuariocobro='".$_SESSION[IDUSUARIO]."',
							sucursalcobro='".$_SESSION[IDSUCURSAL]."' 
							WHERE guia in($guias)";
							mysql_query($s,$l) or die($s);
							
							$s="UPDATE facturacion
							SET estadocobranza='C'
							WHERE folio in($facturas)";
							mysql_query($s,$l) or die($s);
							
							$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
							sucursalcobro='".$_SESSION[IDSUCURSAL]."' WHERE guia in($facturas) and tipo='FACT'";		
							mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
						
						$s = "UPDATE actividadusuario SET estado = 1
						WHERE referencia IN($facturas) AND tipo = 'cartera'";
						mysql_query($s,$l) or die($s);
						
						$s = "UPDATE actividadusuario SET estado = 1
						WHERE factura IN($facturas) AND tipo = 'cartera'";
						mysql_query($s,$l) or die($s);
						
						$s = "UPDATE actividadusuario SET estado = 1
						WHERE referencia IN($guias) AND tipo = 'cartera'";
						mysql_query($s,$l) or die($s);
							
					}
				}
			
		echo "ok,".$foliosucursal;
	}else if($_GET[accion] == 7){//LIQUIDAR FOLIO DE LIQUIDACION
		$arr = split(",",$_GET[arre]);	
		$s = "SELECT id FROM liquidacioncobranza WHERE folio = $_GET[folio] AND sucursal = ".$arr[1];
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$_GET[folio] = $f->id;
	
		$s = "UPDATE liquidacioncobranza SET estado='LIQUIDADO', cantidadentregada = '".$_GET[cantidadentregada]."',
		diferencia = '".(($_GET[diferencia] < 0)?0:$_GET[diferencia])."' WHERE id = ".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		$s = "SELECT * FROM liquidacioncobranzadetalle WHERE folioliquidacion=".$_GET[folio]."";
		$rs = mysql_query($s,$l) or die($s);
		$cant = mysql_num_rows($rs);
		
		$s = "UPDATE facturacion 
		INNER JOIN liquidacioncobranzadetalle_tmp ON facturacion.folio = liquidacioncobranzadetalle_tmp.factura
		SET facturacion.enrelacion = 'N'
		WHERE liquidacioncobranzadetalle_tmp.idusuario = ".$_SESSION[IDUSUARIO];
		mysql_query($s,$l) or die($s);
		
		if($cant>0){
			$s = "DELETE FROM liquidacioncobranzadetalle WHERE folioliquidacion=".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			$s = "INSERT INTO liquidacioncobranzadetalle 
			(id,folioliquidacion,foliorelacion,cliente,guia,fechaguia,fechavencimiento,factura,
			importe,saldoactual,revision,cobrar,contrarecibo,compromiso,efectivo,cheque,banco,
			ncheque,tarjeta,transferencia,notacredito,nnotacredito,idusuario,fecha,motivo,proceso)
			SELECT 0 AS id, ".$_GET[folio]." AS folioliquidacion,
			foliorelacion, cliente, guia, fechaguia, fechavencimiento,
			factura, importe, saldoactual, revision, cobrar, contrarecibo,
			compromiso, efectivo, cheque, banco, ncheque, tarjeta,transferencia,notacredito,nnotacredito,
			idusuario, CURDATE(),motivo, proceso FROM liquidacioncobranzadetalle_tmp
			WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
		}

			/*AQUI VA GUARDAR LA FORMA DE PAGO*/
		$s = "SELECT ld.id, ld.cliente
		FROM liquidacioncobranza lc
		INNER JOIN liquidacioncobranzadetalle ld ON lc.id=ld.folioliquidacion 
		WHERE lc.id=".$_GET[folio]." and ld.cobrar='SI' GROUP BY ld.factura";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$sql = "INSERT INTO formapago(guia,procedencia,tipo,total,efectivo,
			tarjeta,transferencia,cheque,ncheque,banco,notacredito,nnotacredito,sucursal,usuario,fecha,cliente)
				SELECT ld.factura,'C','X',sum(ld.importe) as importe,ld.efectivo,
				ld.tarjeta,ld.transferencia,ld.cheque,ld.ncheque,ld.banco,ld.notacredito,ld.nnotacredito,
				lc.sucursal,lc.idusuario,lc.fecha, ld.cliente 
				FROM liquidacioncobranza lc
				INNER JOIN liquidacioncobranzadetalle ld ON lc.id=ld.folioliquidacion 
				WHERE ld.id = $f->id";
			mysql_query($sql,$l) or die($sql);
			$idfp = mysql_insert_id($l);
			
			$s="call proc_RegistroCobranza('LIQUIDACIONCOBRANZA', '$idfp', '', '', $_GET[folio], $f->cliente);";
			mysql_query($s,$l) or die($s);
		}
			
			/*AQUI VA PARA AFECTAR ALA TABLA pagoguias*/
		$sql="SELECT lcd.guia,lcd.factura FROM liquidacioncobranza lc
		INNER JOIN liquidacioncobranzadetalle lcd ON lc.id=lcd.folioliquidacion
		WHERE lc.id=".$_GET[folio]." and lcd.cobrar='SI' GROUP BY lcd.guia,lcd.factura";
		$r = mysql_query($sql,$l) or die($sql);
		while ($f = mysql_fetch_object($r)){
				$tguias = $f->guia;
				$tfacturas = $f->factura;
				
				#actualizando el reporte de cobranza, pago de liquidacion 
				#si tienen un solo folio ps se actualiza directamente
				#si no se hace un ciclo para que actualice todos
				if(count(split(",",$tguias))>1){
					$arretguias = split(",",$tguias);
					for($i=0; $i<count($arretguias); $i++){
						$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$arretguias[$i]', '', '', 0, 0);";
						mysql_query($s,$l) or die($s);
					}
				}else{
					$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$tguias', '', '', 0, 0);";
					mysql_query($s,$l) or die($s);
				}
				
				if(count(split(",",$tfacturas))>1){
					$arretfacturas = split(",",$tfacturas);
					for($i=0; $i<count($arretfacturas); $i++){
						$s = "INSERT INTO liquidacioncobranza_facturas SET folioliquidacion='$_GET[folio]', 
								factura='$arretfacturas[$i]', sucursal='$_SESSION[IDSUCURSAL]'";
								mysql_query($s,$l) or die($s);
						
						$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$arretfacturas[$i]', '', '', 0, 0);";
						mysql_query($s,$l) or die($s);	
						
						#**********SE INSERTARA EN REPORTE VENDEDORES PARA SAACAR COMISION**************************
						$sql="select ge.id as guias from guiasempresariales ge where ge.factura = '$arretfacturas[$i]'";
						$rxz = mysql_query($sql,$l) or die($sql);
						if (mysql_num_rows($rxz)>0){
							while ($fxz=mysql_fetch_object($rxz)){
								#SE REGISTRA EL PAGO DE GUIAS CONSIGNACION
								$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$fxz->guias');";
							}
						}
						
						#para el registro de folios prepagados en reporte vendedores
						$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$arretfacturas[$i]'";
						$rxz = mysql_query($sql,$l) or die($sql);
						if (mysql_num_rows($rxz)>0){
							while ($fxz=mysql_fetch_object($rxz)){
								#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
								$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$fxz->id');";
							}
						}
						#*********************************************************************************************
						
					}
				}else{
					$s = "INSERT INTO liquidacioncobranza_facturas SET folioliquidacion='$_GET[folio]', 
								factura='$tfacturas', sucursal='$_SESSION[IDSUCURSAL]'";
								mysql_query($s,$l) or die($s);
					
					$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$tfacturas', '', '', 0, 0);";
					mysql_query($s,$l) or die($s);	
					
					#**********SE INSERTARA EN REPORTE VENDEDORES PARA SAACAR COMISION**************************
					$sql="select ge.id as guias from guiasempresariales ge where ge.factura = '$tfacturas'";
					$rxz = mysql_query($sql,$l) or die($sql);
					if (mysql_num_rows($rxz)>0){
						while ($fxz=mysql_fetch_object($rxz)){
							#SE REGISTRA EL PAGO DE GUIAS CONSIGNACION
							$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$fxz->guias');";
						}
					}
					
					#para el registro de folios prepagados en reporte vendedores
					$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$tfacturas'";
					$rxz = mysql_query($sql,$l) or die($sql);
					if (mysql_num_rows($rxz)>0){
						while ($fxz=mysql_fetch_object($rxz)){
							#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
							$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$fxz->id');";
						}
					}
					#*********************************************************************************************
				}
				
				//continua el procedimiento normal
				$guias = "'".str_replace(",","','",$tguias)."'";
				$facturas = "'".str_replace(",","','",$tfacturas)."'";
				
				$s="UPDATE pagoguias
					SET pagado='S',
					fechapago=CURRENT_DATE,
					usuariocobro='".$_SESSION[IDUSUARIO]."',
					sucursalcobro='".$_SESSION[IDSUCURSAL]."' 
					WHERE guia in($guias)";
					mysql_query($s,$l) or die($s);
					
					$s="UPDATE facturacion
					SET estadocobranza='C'
					WHERE folio in($facturas)";
					mysql_query($s,$l) or die($s);
					
					$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
					sucursalcobro='".$_SESSION[IDSUCURSAL]."' WHERE guia in($facturas) and tipo='FACT'";		
					mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
					
					$s = "UPDATE actividadusuario SET estado = 1
					WHERE referencia IN($facturas) AND tipo = 'cartera'";
					mysql_query($s,$l) or die($s);
					
					$s = "UPDATE actividadusuario SET estado = 1
					WHERE factura IN($facturas) AND tipo = 'cartera'";
					mysql_query($s,$l) or die($s);
					
					$s = "UPDATE actividadusuario SET estado = 1
					WHERE referencia IN($guias) AND tipo = 'cartera'";
					mysql_query($s,$l) or die($s);
			}
		
		echo "ok";
	}else if($_GET[accion] == 8){//ACTUALIZAR LIQUIDACION DETALLE REVISION
		$s = "UPDATE liquidacioncobranzadetalle_tmp SET revision='SI', contrarecibo=".$_GET[contrarecibo]."
		WHERE factura = ".$_GET[factura]." AND idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	}else if($_GET[accion] == 9){//ACTUALIZAR LIQUIDACION DETALLE COMPROMISO
		$s = "UPDATE liquidacioncobranzadetalle_tmp SET compromiso='".cambiaf_a_mysql($_GET[compromiso])."',
		cobrar = 'NO'
		WHERE factura = ".$_GET[factura]." AND idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
		
	}else if($_GET[accion] == 10){//ACTUALIZAR LIQUIDACION DETALLE COBRAR Y FORMAS DE PAGOS
	/*arr[0] = efectivo; arr[1] = cheque; arr[2] = banco; arr[3] = ncheque;
	arr[4] = tarjeta; arr[5] = transferencia;*/
		$row = split(",",$_GET[arre]);
		$s = "UPDATE liquidacioncobranzadetalle_tmp SET cobrar='SI', efectivo=".$row[0].",
		cheque=".$row[1].",banco=".$row[2].", ncheque='".$row[3]."', tarjeta=".$row[4].",
		transferencia= ".$row[5].",notacredito=".$row[6].",nnotacredito=".$row[7].",
		compromiso = ''
		WHERE factura = ".$_GET[factura]." AND idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);		
		echo "ok";
		
	}else if($_GET[accion] == 11){
		$s = "SELECT lq.estado, DATE_FORMAT(lq.fechaliquidacion,'%d/%m/%Y') AS fechaliquidacion,
		cs.descripcion AS sucursal, lq.sucursal AS idsucursal,
		lq.foliocobranza, lq.cobrador, date_format(rc.fecharelacion,'%d/%m/%Y') AS fechaal, 
		DAYOFWEEK(rc.fecharelacion) AS dia, sec.descripcion AS sector, lq.cantidadentregada, lq.diferencia
		FROM liquidacioncobranza lq
		INNER JOIN catalogosucursal cs ON lq.sucursal = cs.id
		INNER JOIN relacioncobranza rc ON lq.foliocobranza = rc.folio
		LEFT JOIN catalogosector sec ON rc.sector = sec.id
		WHERE lq.folio = $_GET[folio] and lq.sucursal=$_GET[sucursal]";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){
				$f->estado	= cambio_texto($f->estado);
				$f->sucursal = cambio_texto($f->sucursal);
				$f->sector = cambio_texto($f->sector);
				$registros[] = $f;		
			}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion] == 12){
		$s = "DELETE FROM liquidacioncobranzadetalle_tmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM liquidacioncobranzadetalle_tmp_ori WHERE idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		$s = "SELECT id FROM liquidacioncobranza WHERE folio = $_GET[folio] AND sucursal = $_GET[sucursal]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$folio = $f->id;
		
		$s="INSERT INTO liquidacioncobranzadetalle_tmp
		(id,foliorelacion,cliente,guia,fechaguia,fechavencimiento,factura,importe,saldoactual,
		revision,cobrar,contrarecibo,compromiso,efectivo,cheque,banco,ncheque,tarjeta,transferencia,
		notacredito,nnotacredito,idusuario,fecha,motivo,proceso)
		SELECT NULL,foliorelacion, cliente, guia,
		fechaguia,
		fechavencimiento,
		factura, importe, saldoactual, revision, cobrar, contrarecibo,
		compromiso,
		efectivo, cheque, banco, ncheque, tarjeta,transferencia,notacredito,nnotacredito,
		".$_SESSION[IDUSUARIO].",fecha,motivo,proceso
		FROM liquidacioncobranzadetalle
		WHERE folioliquidacion='$folio' GROUP BY guia";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT foliorelacion, cliente, guia, 
		DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fecha,
		DATE_FORMAT(fechavencimiento,'%d/%m/%Y') AS fechavencimiento,
		factura, importe, saldoactual, revision, cobrar,
		IF(contrarecibo=0,'',contrarecibo) AS contrarecibo,
		IF(compromiso='0000-00-00','',DATE_FORMAT(compromiso,'%d/%m/%Y')) AS compromiso,0 as oculto,motivo
		FROM liquidacioncobranzadetalle_tmp
		WHERE idusuario=".$_SESSION[IDUSUARIO]." and proceso='R' GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$registrosr = array();
			while($f = mysql_fetch_object($r)){				
				$registrosr[] = $f;
			}
		
		$s = "SELECT foliorelacion, cliente, guia, 
		DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fecha,
		DATE_FORMAT(fechavencimiento,'%d/%m/%Y') AS fechavencimiento,
		factura, importe, saldoactual, revision, cobrar,
		IF(contrarecibo=0,'',contrarecibo) AS contrarecibo,
		IF(compromiso='0000-00-00','',DATE_FORMAT(compromiso,'%d/%m/%Y')) AS compromiso,
		0 as oculto, motivo, if(cobrar='SI',0,1) as seleccion
		FROM liquidacioncobranzadetalle_tmp
		WHERE idusuario=".$_SESSION[IDUSUARIO]." and proceso='C' GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$registrosc = array();
			while($f = mysql_fetch_object($r)){				
				$registrosc[] = $f;
			}
		
		$arev = str_replace('null','""',json_encode($registrosr));
		$acob = str_replace('null','""',json_encode($registrosc));
		
		echo '({"registrosr":'.$arev.', "registrosc":'.$acob.'})';
		
	}else if($_GET[accion] == 13){
		$arr = split(",",$_GET[arre]);	
		$s = "SELECT id FROM liquidacioncobranza WHERE folio = $_GET[folio] AND sucursal = ".$arr[1];
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$foliosucursal = $_GET[folio];
		$_GET[folio] = $f->id;
		
		
		$s = "SELECT * FROM liquidacioncobranzadetalle WHERE folioliquidacion=".$_GET[folio]."";
		$r = mysql_query($s,$l) or die($s);
		$cant = mysql_num_rows($r);
		if($cant>0){
			$s = "DELETE FROM liquidacioncobranzadetalle WHERE folioliquidacion=".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO liquidacioncobranzadetalle 
			(id,folioliquidacion,foliorelacion,cliente,guia,fechaguia,fechavencimiento,factura,
			importe,saldoactual,revision,cobrar,contrarecibo,compromiso,efectivo,cheque,banco,
			ncheque,tarjeta,transferencia,notacredito,nnotacredito,idusuario,fecha,motivo,proceso)
			SELECT 0 AS id, ".$_GET[folio]." AS folioliquidacion,
			foliorelacion, cliente, guia, fechaguia, fechavencimiento,
			factura, importe, saldoactual, revision, cobrar, contrarecibo,
			compromiso, efectivo, cheque, banco, ncheque, tarjeta,transferencia,notacredito,nnotacredito,
			idusuario, fecha,motivo, proceso FROM liquidacioncobranzadetalle_tmp
			WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
		}
		
		echo "ok,".$foliosucursal;
					
	}else if($_GET[accion] == 14){//OBTENER FILTRO X FACTURAS
		$s = "SELECT foliorelacion, cliente, guia, 
		DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fecha,
		DATE_FORMAT(fechavencimiento,'%d/%m/%Y') AS fechavencimiento,
		factura, importe, saldoactual, revision, cobrar,
		IF(contrarecibo=0,'',contrarecibo) AS contrarecibo,
		IF(compromiso='0000-00-00','',DATE_FORMAT(compromiso,'%d/%m/%Y')) AS compromiso,0 as oculto,motivo
		FROM liquidacioncobranzadetalle_tmp
		WHERE ".(($_GET[factura]!="")? 'factura='.$_GET[factura].'' : 'foliorelacion='.$_GET[folio].'' )."
		AND proceso = 'R' AND idusuario=".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		$registrosr = array();
			while($f = mysql_fetch_object($r)){
				$registrosr[] = $f;
			}
		
		$s = "SELECT foliorelacion, cliente, guia, 
		DATE_FORMAT(fechaguia,'%d/%m/%Y') AS fecha,
		DATE_FORMAT(fechavencimiento,'%d/%m/%Y') AS fechavencimiento,
		factura, importe, saldoactual, revision, cobrar,
		IF(contrarecibo=0,'',contrarecibo) AS contrarecibo,
		IF(compromiso='0000-00-00','',DATE_FORMAT(compromiso,'%d/%m/%Y')) AS compromiso,0 as oculto,motivo
		FROM liquidacioncobranzadetalle_tmp
		WHERE ".(($_GET[factura]!="")? 'factura='.$_GET[factura].'' : 'foliorelacion='.$_GET[folio].'' )."
		AND proceso = 'C' AND idusuario=".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		$registrosc = array();
			while($f = mysql_fetch_object($r)){
				$registrosc[] = $f;
			}
		
		$arev = str_replace('null','""',json_encode($registrosr));
		$acob = str_replace('null','""',json_encode($registrosc));
		
		echo '({"registrosr":'.$arev.', "registrosc":'.$acob.'})';
		//echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion] == 15){
		$s="SELECT factura,(efectivo+cheque+tarjeta+transferencia+notacredito) AS total FROM liquidacioncobranzadetalle_tmp WHERE idusuario=".$_SESSION[IDUSUARIO]." GROUP BY factura";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}			
			echo str_replace('null','""',json_encode($registros));		
	}else if($_GET[accion] == 16){
		
		$s="UPDATE liquidacioncobranzadetalle_tmp SET motivo='".$_GET[motivo]."' 
		WHERE factura=".$_GET[factura]." AND idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	}else if($_GET[accion] == 17){	
		
		$s="SELECT * FROM liquidacioncobranzadetalle_tmp WHERE factura=".$_GET[factura]." AND idusuario=".$_SESSION[IDUSUARIO]." AND motivo<>0";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion] == 18){
		//agrupar
		$s = "DELETE FROM liquidacioncobranzadetalle_tmp WHERE factura IN($_GET[facturas]) and idusuario = $_SESSION[IDUSUARIO];";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO liquidacioncobranzadetalle_tmp
		SELECT NULL,foliorelacion,cliente,GROUP_CONCAT(guia),fechaguia,
		fechavencimiento,GROUP_CONCAT(factura),sum(importe),sum(saldoactual),
		revision,cobrar,contrarecibo,NULL,efectivo,cheque,banco,ncheque,tarjeta,transferencia,
		notacredito,nnotacredito,idusuario,fecha,motivo,proceso,$_SESSION[IDSUCURSAL] 
		FROM liquidacioncobranzadetalle_tmp_ori WHERE factura IN($_GET[facturas]) and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT cliente,guia as guia, fechaguia as fecha,
		fechavencimiento,factura, importe,saldoactual,
		cobrar,contrarecibo,'' as compromiso,0 as oculto, motivo
		FROM liquidacioncobranzadetalle_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and proceso = 'C'"; 
		$r = mysql_query($s,$l) or die($s);
		$registrosc = array();
			while($f = mysql_fetch_object($r)){				
				$registrosc[] = $f;
			}
		$acob = str_replace('null','""',json_encode($registrosc));
		
		echo '({"registrosc":'.$acob.'})';
	}else if($_GET[accion] == 19){
		//desagrupar
		$s = "DELETE FROM liquidacioncobranzadetalle_tmp WHERE factura ='$_GET[facturas]' and idusuario = $_SESSION[IDUSUARIO];";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO liquidacioncobranzadetalle_tmp
		SELECT NULL,foliorelacion,cliente,guia,fechaguia,
		fechavencimiento,factura,importe,saldoactual,
		revision,cobrar,contrarecibo,NULL,efectivo,cheque,banco,ncheque,tarjeta,transferencia,
		notacredito,nnotacredito,idusuario,fecha,motivo,proceso,$_SESSION[IDSUCURSAL]  
		FROM liquidacioncobranzadetalle_tmp_ori WHERE factura IN($_GET[facturas]) and idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT cliente,guia, fechaguia as fecha,
		fechavencimiento,factura, importe,saldoactual,
		cobrar,contrarecibo,'' as compromiso,0 as oculto, motivo
		FROM liquidacioncobranzadetalle_tmp 
		WHERE idusuario = $_SESSION[IDUSUARIO] and proceso = 'C'"; 
		$r = mysql_query($s,$l) or die($s);
		$registrosc = array();
			while($f = mysql_fetch_object($r)){				
				$registrosc[] = $f;
			}
		$acob = str_replace('null','""',json_encode($registrosc));
		
		echo '({"registrosc":'.$acob.'})';
	}
?>