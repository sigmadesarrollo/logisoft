<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	//guardarliquidar
	if($_POST[accion]==4){
		$cerrardevolucion=$_POST[cerrar];
		if ($cerrardevolucion==1){
			$concatenacion=',cerro=1';	
		}else{
			$concatenacion='';	
		}
		
		$s = "SELECT IFNULL(id,0)as folio FROM liquidacionead 
		WHERE idreparto=".$_POST[idreparto]." AND sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$folio = $f->folio;
		
		$_POST[total] 				= ($_POST[total]=="")?0:$_POST[total];
		$_POST[cantidadentregada]	= ($_POST[cantidadentregada]=="")?0:$_POST[cantidadentregada];
		
		if ($folio!=0){
			$s = "update liquidacionead set idreparto='$_POST[idreparto]', 
			entregadas='$_POST[entregadas]', devueltas='$_POST[devueltas]', 
			pagadas_credito='$_POST[pagadas_credito]', pagadas_contado='$_POST[pagadas_contado]',
			tpagadas_credito='$_POST[tpagadas_credito]', tpagadas_contado='$_POST[tpagadas_contado]', 
			porcobrar_contado='$_POST[porcobrar_contado]',
			porcobrar_credito='$_POST[porcobrar_credito]', tporcobrar_credito='$_POST[tporcobrar_credito]', 
			tporcobrar_contado='$_POST[tporcobrar_contado]', sucursal='$_SESSION[IDSUCURSAL]',
			entrego='$_POST[entrego]',total=$_POST[total],fecha=current_date,idusuario = '$_SESSION[IDUSUARIO]', 
			cantidadentregada = '$_POST[cantidadentregada]', 
			diferencia = $_POST[total]-$_POST[cantidadentregada]
			$concatenacion where id = $folio";
			//echo $s."<br>";
			mysql_query($s,$l) or die($s);
			$idliquidacion = $folio;			
		}else{
			$s="insert into liquidacionead set tipoliquidacion='M', folio = obtenerFolio('liquidacionead',".$_SESSION[IDSUCURSAL]."),
			idreparto=$_POST[idreparto],entregadas=$_POST[entregadas],devueltas=$_POST[devueltas],
			pagadas_credito=$_POST[pagadas_credito],pagadas_contado=$_POST[pagadas_contado],
			tpagadas_credito=$_POST[tpagadas_credito],tpagadas_contado=$_POST[tpagadas_contado],
			porcobrar_contado=$_POST[porcobrar_contado],porcobrar_credito=$_POST[porcobrar_credito],
			tporcobrar_contado=$_POST[tporcobrar_contado],tporcobrar_credito=$_POST[tporcobrar_credito],
			sucursal=$_SESSION[IDSUCURSAL],entrego=$_POST[entrego],total=$_POST[total],
			fecha = CURRENT_DATE,idusuario=$_POST[idusuario],
			cantidadentregada = '$_POST[cantidadentregada]', 
			diferencia = $_POST[total]-$_POST[cantidadentregada] 
			$concatenacion";
			//echo $s."<br>";
			mysql_query($s,$l) or die($s);
			$idliquidacion = mysql_insert_id($l);						
		}
	
		$s = "SELECT folio FROM liquidacionead WHERE id = ".$idliquidacion;
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
	
		$sql = "delete from liquidacion_detalleead 
		where idliquidacion = $idliquidacion and sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($sql,$l)or die($sql);
		
		$sql="INSERT INTO liquidacion_detalleead
		SELECT 0 as id,'$idliquidacion', sector,guia,factura,origen,
		iddestinatario, destinatario,tipoflete,
		condicionpago,importe,estado,seleccion, seleccionada, pagada, motivo,
		idusuario,nombre,identificacion,numero_id,
		efectivo,cheque,ncheque,banco,nnotacredito,notacredito,sucursal
		FROM liquidacion_detalle_tmp 
		WHERE idusuario = '$_SESSION[IDUSUARIO]'";
		mysql_query($sql,$l) or die($sql);
		
		if($concatenacion==",cerro=1"){
			$s = "SELECT * FROM liquidacion_detalleead 
			WHERE idliquidacion = ".$fo->folio." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$e = mysql_query($s,$l) or die($s);
			while($ead = mysql_fetch_object($e)){
				$s = "CALL proc_ReporteProductividad('EAD','','".$ead->guia."','".$_POST[fechahora]."',".$_SESSION[IDSUCURSAL].")";
				mysql_query($s,$l) or die($s);
			} 
		}
		
		//echo $sql."<br>";
		
		if ($cerrardevolucion==1){
			
			#para ingresar el movimiento de ventas de guias a ventas contra presupuesto
			$s = "call proc_VentasVsPresupuesto('OP EAD','$idliquidacion',$_SESSION[IDSUCURSAL]);";
			$r = mysql_query($s,$l) or die("$s");
			
			/*AQUI VA GUARDAR LA FORMA DE PAGO*/
			$sql = "INSERT INTO formapago(guia,procedencia,tipo,total,efectivo,
			tarjeta,transferencia,cheque,ncheque,banco,notacredito,nnotacredito,sucursal,usuario,fecha,cliente)
			SELECT ld.guia,'M','X',ld.importe,ld.efectivo,0 AS tarjeta,0 AS transferencia,ld.cheque,ld.ncheque,
			ld.banco,ld.notacredito,ld.nnotacredito,lc.sucursal,lc.idusuario,curdate(),pg.cliente FROM liquidacionead lc
			INNER JOIN liquidacion_detalleead ld ON lc.id=ld.idliquidacion 
			INNER JOIN pagoguias pg ON ld.guia=pg.guia
			WHERE lc.id='$idliquidacion' AND ld.estado='ENTREGADA POR LIQUIDAR' AND pg.pagado='N' and ld.seleccionada='S'";
			//echo $sql."<br>";
			$t = mysql_query($sql,$l) or die($sql);	
			
			$s = "UPDATE guiasventanilla 
			INNER JOIN liquidacion_detalle_tmp ON guiasventanilla.id = liquidacion_detalle_tmp.guia
			AND liquidacion_detalle_tmp.idusuario = '$_SESSION[IDUSUARIO]' AND 
			liquidacion_detalle_tmp.estado='ENTREGADA POR LIQUIDAR'
			SET guiasventanilla.estado = 'ENTREGADA', 
			guiasventanilla.recibio=liquidacion_detalle_tmp.nombre,
			guiasventanilla.tipoidentificacion=liquidacion_detalle_tmp.identificacion,
			guiasventanilla.numeroidentificacion=liquidacion_detalle_tmp.numero_id;";
			//echo $s."<br>";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE guiasempresariales 
			INNER JOIN liquidacion_detalle_tmp ON guiasempresariales.id = liquidacion_detalle_tmp.guia
			AND liquidacion_detalle_tmp.idusuario = '$_SESSION[IDUSUARIO]' AND 
			liquidacion_detalle_tmp.estado='ENTREGADA POR LIQUIDAR'
			SET guiasempresariales.estado = 'ENTREGADA', 
			guiasempresariales.recibio=liquidacion_detalle_tmp.nombre,
			guiasempresariales.tipoidentificacion=liquidacion_detalle_tmp.identificacion,
			guiasempresariales.numeroidentificacion=liquidacion_detalle_tmp.numero_id;";
			//echo $s."<br>";
			mysql_query($s,$l) or die($s);
			
			$s = "update liquidacion_detalleead set estado = 'ENTREGADA' 
			where idliquidacion = $idliquidacion and sucursal = ".$_SESSION[IDSUCURSAL]." 
			and estado = 'ENTREGADA POR LIQUIDAR'";
			//echo $s;
			mysql_query($s,$l) or die($s);
			
			$q="select * from liquidacion_detalleead where idliquidacion='$idliquidacion' 
			and sucursal = ".$_SESSION[IDSUCURSAL]." and seleccionada='S'";
			$m= mysql_query(str_replace("''",'NULL',$q),$l) or die($q);
			$registros= array();
			if (mysql_num_rows($m)>0){
				while ($f=mysql_fetch_object($m)){
					if($f->factura!=0){
						$sq = "UPDATE facturacion SET estadocobranza = 'C' WHERE folio = '$f->factura'";					
						 mysql_query($sq,$l) or die($sq);
						
						$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$f->guias', '', '', 0, 0);";
						mysql_query($s,$l) or die($s);
						
						#**********SE INSERTARA EN REPORTE VENDEDORES PARA SAACAR COMISION**************************
						$sql="select ge.id as guias from guiasempresariales ge where ge.factura = '$f->factura'";
							$r = mysql_query($sql,$l) or die($sql);
							if (mysql_num_rows($r)>0){
								while ($f=mysql_fetch_object($r)){
									#en caso de ke sea contado se registran la comision
									$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$f->guias');";
									mysql_query($s,$l) or die($s);
								}
							}
							
							#para el registro de folios prepagados en reporte vendedores
							$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$f->factura'";
							$r = mysql_query($sql,$l) or die($sql);
							if (mysql_num_rows($r)>0){
								while ($f=mysql_fetch_object($r)){
									#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
									$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$f->id');";
									mysql_query($s,$l) or die($s);
								}
							}
						#*********************************************************************************************
					}
					
					$sq = "UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
					sucursalcobro='$_SESSION[IDSUCURSAL]' WHERE guia='$f->guia' AND pagado='N' ";					
					$d = mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
					
					$s = "UPDATE actividadusuario SET estado = 1 
					WHERE tipo = 'inventario' AND referencia = UCASE('$f->guia')";
					mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
				}
			}
		}
		echo "ok,".$fo->folio;
	}
	
	
?>
