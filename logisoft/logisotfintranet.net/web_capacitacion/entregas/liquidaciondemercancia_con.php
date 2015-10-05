<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("Se acabo la sesión, favor de loguearse de nuevo");
	}
	require_once("../Conectar.php");
	require_once("../fn-error.php");
	$l = Conectarse("webpmm");
	
	if($_GET[idpagina]==""){
		$_GET[idpagina] = $_SESSION[IDUSUARIO];
	}
	
	if($_GET[accion]==1){
		$s = "SELECT obtenerFolio('liquidacionead',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or postError($s); $fo = mysql_fetch_object($r);
		
		$s = "SELECT DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fecha, 
		(SELECT descripcion FROM catalogosucursal where id=".$_SESSION[IDSUCURSAL].") as sucursal";
		$r = mysql_query($s,$l) or postError($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		
		echo $fo->folio.",".$f->fecha.",".$_SESSION[IDSUCURSAL].",".$f->sucursal;
	}
	
	if($_GET[accion]==2){
		$s = "SELECT * FROM liquidacionead WHERE idreparto=".$_GET[folio]." and sucursal = $_SESSION[IDSUCURSAL]";
		$r=mysql_query($s,$l)or postError($s); 
		$enliq = mysql_num_rows($r);
		
		$s = "SELECT cerro FROM devolucionmercancia 
		WHERE idreparto=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l)or postError($s); 
		$f = mysql_fetch_object($r);
		$cerrodev = ((!empty($f->cerro))? $f->cerro : 0);
		
		#datos del conductor
		$s = "select repartomercanciadetalle.idreparto, repartomercanciaead.conductor1,
		concat_ws(' ',emp1.nombre,emp1.apellidopaterno,emp1.apellidomaterno) as conductorn1,
		repartomercanciaead.conductor2,
		concat_ws(' ',emp2.nombre,emp2.apellidopaterno,emp2.apellidomaterno) as conductorn2,
		catalogounidad.numeroeconomico, repartomercanciaead.liquidado
		from repartomercanciadetalle
		inner join repartomercanciaead on repartomercanciaead.folio=repartomercanciadetalle.idreparto AND
			repartomercanciaead.sucursal=$_SESSION[IDSUCURSAL]
		left join catalogoempleado as emp1 on emp1.id = repartomercanciaead.conductor1
		left join catalogoempleado as emp2 on emp2.id = repartomercanciaead.conductor2
		left join catalogounidad on catalogounidad.id = repartomercanciaead.unidad
		where repartomercanciadetalle.idreparto = $_GET[folio] and repartomercanciadetalle.sucursal = $_SESSION[IDSUCURSAL]
		and (repartomercanciaead.liquidado = 0 or repartomercanciaead.liquidado = 1)
		group by repartomercanciadetalle.idreparto";
		$r = mysql_query($s,$l) or postError($s);
		$f = mysql_fetch_object($r);
		$datosconductor = json_encode($f);
		
		#datosguias
		$s = "delete from liquidacion_detalle_tmp where idusuario = $_GET[idpagina]";
		mysql_query($s,$l) or postError($s);
		
		$s = "insert into liquidacion_detalle_tmp
		(select gd.sector, gd.id as guia, ifnull(gd.factura,''), cs.prefijo as origen,
			gd.iddestinatario,
			concat_ws(' ',cc.nombre, cc.paterno, cc.materno) as destinatario,
			if(gd.tipoflete=0,'PAGADO','POR COBRAR') as tipoflete,
			if(gd.condicionpago=0,'CONTADO','CREDITO') as condicionpago,
			gd.total as importe,IF(gd.estado='ENTREGADA','ENTREGADA POR LIQUIDAR',gd.estado)as estado,
			if(gd.tipoflete=1 && pg.pagado='N' && gd.estado='ENTREGADA' && gd.condicionpago=0
			   ,'1','0') as seleccion, 
			if(gd.tipoflete=1 && pg.pagado='N' && gd.estado='ENTREGADA' && (gd.condicionpago=0 /* ||  (gd.condicionpago=1 && gd.factura <>'' && not isnull(gd.factura)) */ )
			   ,'S','N'), pg.pagado,
			rm.motivo, $_GET[idpagina],null,null,null,gd.total,0,0,0,0,0,".$_SESSION[IDSUCURSAL]."
			from repartomercanciadetalle as rm
			inner join guiasventanilla as gd on gd.id=rm.guia
			inner join catalogosucursal as cs on gd.idsucursalorigen = cs.id
			inner join catalogocliente as cc on gd.iddestinatario = cc.id
			left join pagoguias pg on gd.id = pg.guia
			where rm.idreparto=$_GET[folio] and rm.sucursal = $_SESSION[IDSUCURSAL])
		union
		(select gd.sector, gd.id as guia, ifnull(gd.factura,''), cs.prefijo as origen,
			gd.iddestinatario,concat_ws(' ',cc.nombre, cc.paterno, cc.materno) as destinatario,
			if(gd.tipoflete='PAGADA','PAGADO',gd.tipoflete) tipoflete, gd.tipopago as condicionpago,
			gd.total as importe,IF(gd.estado='ENTREGADA','ENTREGADA POR LIQUIDAR',gd.estado),
			if((gd.tipoflete='POR COBRAR' and pg.pagado='N' && gd.estado='ENTREGADA' && gd.tipopago='CREDITO'),'1','0') as seleccion, 
			if(gd.tipoguia = 'CONSIGNACION' && gd.tipoflete='POR COBRAR'  && gd.estado='ENTREGADA'
			   && pg.pagado='N' && (gd.tipopago='CONTADO' /* || (gd.tipopago='CREDITO' && gd.factura <>'' && not isnull(gd.factura)) */ )
			   ,'S','N'), pg.pagado,
			rm.motivo, $_GET[idpagina],null,null,null,gd.total,0,0,0,0,0,".$_SESSION[IDSUCURSAL]."
			from repartomercanciadetalle as rm
			inner join guiasempresariales as gd on gd.id=rm.guia 
			inner join catalogosucursal as cs on gd.idsucursalorigen = cs.id
			inner join catalogocliente as cc on gd.iddestinatario = cc.id
			left join pagoguias pg on gd.id = pg.guia
			where rm.idreparto=$_GET[folio] and rm.sucursal = $_SESSION[IDSUCURSAL])";
			
		mysql_query($s,$l) or postError($s);
		
		$sql="SELECT tm.sector,tm.guia,tm.factura,tm.origen,
		tm.iddestinatario, tm.destinatario, tm.tipoflete,
		tm.condicionpago,tm.importe,
		IF(tm.estado='ENTREGADA','ENTREGADA POR LIQUIDAR',tm.estado)as estado,tm.seleccion,
		tm.seleccionada, tm.pagada,tm.motivo,
		tm.idusuario,IFNULL(tm.nombre,'')AS nombre,IFNULL(tm.identificacion,'')AS identificacion,
		IFNULL(tm.numero_id,0)AS numero_id
 		FROM liquidacion_detalle_tmp tm 
 		LEFT JOIN 
 		(SELECT DISTINCT ld.guia FROM liquidacionead le 
 		INNER JOIN liquidacion_detalleead ld ON le.id=ld.idliquidacion 
		WHERE  le.idreparto=$_GET[folio])le ON tm.guia=le.guia
 		WHERE tm.idusuario = $_GET[idpagina] and tm.sucursal=".$_SESSION[IDSUCURSAL]."
		order by tm.guia";
		$d=mysql_query($sql,$l)or postError($sql); 
		$registros= array();
		
		while ($f=mysql_fetch_object($d)){
			$f->guia = cambio_texto($f->guia);
			$f->origen = cambio_texto($f->origen);
			$f->destinatario = cambio_texto($f->destinatario);
			$registros[]=$f;	
		}
		$guias = json_encode($registros);
		echo "({
			'enliquidacion':'$enliq',
			'cerrodevolucion':'$cerrodev',
			'dc':$datosconductor,
			'registros':$guias
			   })";
		
	}
	//conductor
	if($_GET[accion]==3){
		$s = "select id, concat_ws(' ',nombre,apellidopaterno,apellidomaterno) as conductor	from catalogoempleado where 
		id =".$_GET[idempleado]."";	
		$r=mysql_query($s,$l)or postError($s); 
		$registros= array();			
		if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->conductor = cambio_texto($f->conductor);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
		}else{
				echo "no encontro";
		}
	}
	
	//guardarliquidar
	if($_GET[accion]==4){
		$cerrardevolucion=$_GET[cerrar];
		if ($cerrardevolucion==1){
			$concatenacion=',cerro=1';	
		}else{
			$concatenacion='';	
		}
		
		$s = "SELECT IFNULL(id,0)as folio FROM liquidacionead 
		WHERE idreparto=".$_GET[idreparto]." AND sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or postError($s);
		$f = mysql_fetch_object($r);
		$folio = $f->folio;
		
		$_GET[total] 				= ($_GET[total]=="")?0:$_GET[total];
		$_GET[cantidadentregada]	= ($_GET[cantidadentregada]=="")?0:$_GET[cantidadentregada];
		
		if ($folio!=0){
			$s = "update liquidacionead set idreparto='$_GET[idreparto]', 
			entregadas='$_GET[entregadas]', devueltas='$_GET[devueltas]', 
			pagadas_credito='$_GET[pagadas_credito]', pagadas_contado='$_GET[pagadas_contado]',
			tpagadas_credito='$_GET[tpagadas_credito]', tpagadas_contado='$_GET[tpagadas_contado]', 
			porcobrar_contado='$_GET[porcobrar_contado]',
			porcobrar_credito='$_GET[porcobrar_credito]', tporcobrar_credito='$_GET[tporcobrar_credito]', 
			tporcobrar_contado='$_GET[tporcobrar_contado]', sucursal='$_SESSION[IDSUCURSAL]',
			entrego='$_GET[entrego]',total=$_GET[total],fecha=current_date,idusuario = '$_SESSION[IDUSUARIO]', 
			cantidadentregada = '$_GET[cantidadentregada]', 
			diferencia = $_GET[total]-$_GET[cantidadentregada]
			$concatenacion where id = $folio";
			//echo $s."<br>";
			mysql_query($s,$l) or postError($s);
			$idliquidacion = $folio;			
		}else{
			$s="insert into liquidacionead set tipoliquidacion='M', folio = obtenerFolio('liquidacionead',".$_SESSION[IDSUCURSAL]."),
			idreparto=$_GET[idreparto],entregadas=$_GET[entregadas],devueltas=$_GET[devueltas],
			pagadas_credito=$_GET[pagadas_credito],pagadas_contado=$_GET[pagadas_contado],
			tpagadas_credito=$_GET[tpagadas_credito],tpagadas_contado=$_GET[tpagadas_contado],
			porcobrar_contado=$_GET[porcobrar_contado],porcobrar_credito=$_GET[porcobrar_credito],
			tporcobrar_contado=$_GET[tporcobrar_contado],tporcobrar_credito=$_GET[tporcobrar_credito],
			sucursal=$_SESSION[IDSUCURSAL],entrego=$_GET[entrego],total=$_GET[total],
			fecha = CURRENT_DATE,idusuario=$_GET[idusuario],
			cantidadentregada = '$_GET[cantidadentregada]', 
			diferencia = $_GET[total]-$_GET[cantidadentregada] 
			$concatenacion";
			//echo $s."<br>";
			mysql_query($s,$l) or postError($s);
			$idliquidacion = mysql_insert_id($l);						
		}
	
		$s = "SELECT folio FROM liquidacionead WHERE id = ".$idliquidacion;
		$r = mysql_query($s,$l) or postError($s); $fo = mysql_fetch_object($r);
	
		$sql = "delete from liquidacion_detalleead 
		where idliquidacion = $idliquidacion and sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($sql,$l)or postError($sql);
		
		$sql="INSERT INTO liquidacion_detalleead
		SELECT 0 as id,'$idliquidacion', sector,guia,factura,origen,
		iddestinatario, destinatario,tipoflete,
		condicionpago,importe,estado,seleccion, seleccionada, pagada, motivo,
		$_SESSION[IDUSUARIO],nombre,identificacion,numero_id,
		efectivo,cheque,ncheque,banco,nnotacredito,notacredito,sucursal
		FROM liquidacion_detalle_tmp 
		WHERE idusuario = '$_GET[idpagina]'";
		mysql_query($sql,$l) or postError($sql);
		
		if($concatenacion==",cerro=1"){
			$sql="UPDATE repartomercanciaead SET liquidado = 1
			WHERE folio = '$_GET[idreparto]' AND sucursal = '$_SESSION[IDSUCURSAL]'";
			mysql_query($sql,$l) or postError($sql);
			
			$s = "SELECT * FROM liquidacion_detalleead 
			WHERE idliquidacion = ".$fo->folio." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$e = mysql_query($s,$l) or postError($s);
			while($ead = mysql_fetch_object($e)){
				$s = "CALL proc_ReporteProductividad('EAD','','".$ead->guia."','".$_GET[fechahora]."',".$_SESSION[IDSUCURSAL].")";
				mysql_query($s,$l) or postError($s);
			} 
		}
		
		//echo $sql."<br>";
		
		if ($cerrardevolucion==1){
			
			#para ingresar el movimiento de ventas de guias a ventas contra presupuesto
			$s = "call proc_VentasVsPresupuesto('OP EAD','$idliquidacion',$_SESSION[IDSUCURSAL]);";
			$r = mysql_query($s,$l) or postError("$s");
			
			/*AQUI VA GUARDAR LA FORMA DE PAGO*/
			$sql = "INSERT INTO formapago(guia,procedencia,tipo,total,efectivo,
			tarjeta,transferencia,cheque,ncheque,banco,notacredito,nnotacredito,sucursal,usuario,fecha,cliente)
			SELECT ld.guia,'M','X',ld.importe,ld.efectivo,0 AS tarjeta,0 AS transferencia,ld.cheque,ld.ncheque,
			ld.banco,ld.notacredito,ld.nnotacredito,lc.sucursal,lc.idusuario,curdate(),pg.cliente FROM liquidacionead lc
			INNER JOIN liquidacion_detalleead ld ON lc.id=ld.idliquidacion 
			INNER JOIN pagoguias pg ON ld.guia=pg.guia
			WHERE lc.id='$idliquidacion' AND ld.estado='ENTREGADA POR LIQUIDAR' AND pg.pagado='N' and ld.seleccionada='S'";
			//echo $sql."<br>";
			$t = mysql_query($sql,$l) or postError($sql);	
			
			$s = "UPDATE guiasventanilla 
			INNER JOIN liquidacion_detalle_tmp ON guiasventanilla.id = liquidacion_detalle_tmp.guia
			AND liquidacion_detalle_tmp.idusuario = '$_GET[idpagina]' AND 
			liquidacion_detalle_tmp.estado='ENTREGADA POR LIQUIDAR'
			SET guiasventanilla.estado = 'ENTREGADA', 
			guiasventanilla.recibio=liquidacion_detalle_tmp.nombre,
			guiasventanilla.tipoidentificacion=liquidacion_detalle_tmp.identificacion,
			guiasventanilla.numeroidentificacion=liquidacion_detalle_tmp.numero_id;";
			//echo $s."<br>";
			mysql_query($s,$l) or postError($s);
			
			$s = "UPDATE guiasempresariales 
			INNER JOIN liquidacion_detalle_tmp ON guiasempresariales.id = liquidacion_detalle_tmp.guia
			AND liquidacion_detalle_tmp.idusuario = '$_GET[idpagina]' AND 
			liquidacion_detalle_tmp.estado='ENTREGADA POR LIQUIDAR'
			SET guiasempresariales.estado = 'ENTREGADA', 
			guiasempresariales.recibio=liquidacion_detalle_tmp.nombre,
			guiasempresariales.tipoidentificacion=liquidacion_detalle_tmp.identificacion,
			guiasempresariales.numeroidentificacion=liquidacion_detalle_tmp.numero_id;";
			//echo $s."<br>";
			mysql_query($s,$l) or postError($s);
			
			$s = "update liquidacion_detalleead set estado = 'ENTREGADA' 
			where idliquidacion = $idliquidacion and sucursal = ".$_SESSION[IDSUCURSAL]." 
			and estado = 'ENTREGADA POR LIQUIDAR'";
			//echo $s;
			mysql_query($s,$l) or postError($s);
			
			$q="select * from liquidacion_detalleead where idliquidacion='$idliquidacion' 
			and sucursal = ".$_SESSION[IDSUCURSAL]." and seleccionada='S'";
			$m= mysql_query(str_replace("''",'NULL',$q),$l) or postError($q);
			$registros= array();
			if (mysql_num_rows($m)>0){
				while ($f=mysql_fetch_object($m)){
					if($f->tipoflete=='POR COBRAR' && $f->condicionpago=='CONTADO'){
						/*if($f->factura!=0){
							$sq = "UPDATE facturacion SET estadocobranza = 'C' WHERE folio = '$f->factura'";					
							 mysql_query($sq,$l) or postError($sq);
							
							$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$f->guias', '', '', 0, 0);";
							mysql_query($s,$l) or postError($s);
							
							#**********SE INSERTARA EN REPORTE VENDEDORES PARA SAACAR COMISION**************************
							$sql="select ge.id as guias from guiasempresariales ge where ge.factura = '$f->factura'";
								$r = mysql_query($sql,$l) or postError($sql);
								if (mysql_num_rows($r)>0){
									while ($f=mysql_fetch_object($r)){
										#en caso de ke sea contado se registran la comision
										$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$f->guias');";
										mysql_query($s,$l) or postError($s);
									}
								}
								
								#para el registro de folios prepagados en reporte vendedores
								$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$f->factura'";
								$r = mysql_query($sql,$l) or postError($sql);
								if (mysql_num_rows($r)>0){
									while ($f=mysql_fetch_object($r)){
										#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
										$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$f->id');";
										mysql_query($s,$l) or postError($s);
									}
								}
							#*********************************************************************************************
						}*/
						
						$sq = "UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
						sucursalcobro='$_SESSION[IDSUCURSAL]' WHERE guia='$f->guia' AND pagado='N' ";					
						$d = mysql_query(str_replace("''",'NULL',$sq),$l) or postError($sq);
					}
					
					$s = "UPDATE actividadusuario SET estado = 1 
					WHERE tipo = 'inventario' AND referencia = UCASE('$f->guia')";
					mysql_query($s,$l) or postError("$s<br>error en linea ".__LINE__);
				}
			}
		}
		echo "ok,".$fo->folio;
	}
	
	if($_GET[accion]==5){
		$s = "update liquidacion_detalle_tmp set seleccionada = 'N' where idusuario = $_GET[idpagina]";
		$r = mysql_query($s,$l) or postError("error ".$s);
		
		$guias = "'".str_replace(",","','",$_GET[guiasseleccionadas])."'";

		$s = "update liquidacion_detalle_tmp set seleccionada = 'S' where idusuario = $_GET[idpagina] and guia in($guias)";
		$r = mysql_query($s,$l) or postError("error ".$s);
	}
	
	if($_GET[accion]==6){		
		$s = "SELECT id FROM liquidacionead WHERE folio = $_GET[folio] AND sucursal = $_SESSION[IDSUCURSAL]";
		$r=mysql_query($s,$l)or postError($s); 
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
		WHERE ld.id = '".$_GET[folio]."'";	
		$r=mysql_query($s,$l)or postError($s); 
			
		$registros = array();
		$principal = "";
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->empleado = cambio_texto($f->empleado);
			$principal = str_replace('null','""',json_encode($f));
			
			$sql = "SELECT IFNULL(cs.descripcion,'') AS sector,ld.guia,ld.factura,ld.origen,
			ld.iddestinatario, ld.destinatario,ld.tipoflete,ld.condicionpago,ld.importe,ld.estado,ld.seleccion, seleccionada, pagada,
			IFNULL(ld.motivo,'') AS motivo,ld.nombre,ld.identificacion,ld.numero_id 
			FROM liquidacion_detalleead ld 
			LEFT JOIN catalogosector cs ON ld.sector=cs.id	
			WHERE ld.idliquidacion='".$_GET[folio]."' AND ld.sucursal = '".$_SESSION[IDSUCURSAL]."'
			group by ld.guia";
			//postError($sql);
			$r = mysql_query($sql,$l)or postError($sql); 
			
			
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
	
	if($_GET[accion]==7){
		$sql="UPDATE liquidacion_detalle_tmp SET 
		nombre='".$_GET[nombre]."',identificacion='".$_GET[identificacion]."',
		numero_id='".$_GET[numero]."' WHERE guia='".$_GET[guia]."' AND idusuario='".$_GET[idpagina]."' ";
		mysql_query($sql,$l) or postError($sql);
		echo "ok";
	}
	
	if($_GET[accion]==8){
		$sql="UPDATE liquidacion_detalle_tmp SET 
		efectivo=".$_GET[efectivo].",cheque=".$_GET[cheque].",ncheque=".$_GET[ncheque].",
		banco=".$_GET[banco].",nnotacredito=".$_GET[nnotacredito].",notacredito=".$_GET[notacredito]." 
		WHERE guia='".$_GET[guia]."' AND idusuario='".$_GET[idpagina]."' ";
		mysql_query($sql,$l) or postError($sql);
		echo "ok";
	}
	
	if($_GET[accion]==14){
		/*$s="SELECT SUM(ncd.importe)AS importe FROM notacredito nc 
		INNER JOIN notacreditodetalle ncd ON nc.folio=ncd.folionotacredito  
		WHERE nc.folio=$_GET[folio] AND nc.cliente=$_GET[cliente] group by nc.folio order by nc.fechanotacredito";*/
		
		if($_GET[cliente]!="")
			$elcliente = " AND n.cliente='$_GET[cliente]'";
		
		$s = "SELECT SUM(importe)*(1+n.impuestoporc) AS importe 
		FROM notacreditodetalle nd
		INNER JOIN notacredito n ON nd.folionotacredito = n.folio
		WHERE folionotacredito=$_GET[folio] $elcliente";
		$r=mysql_query($s,$l)or postError($s); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}
?>
