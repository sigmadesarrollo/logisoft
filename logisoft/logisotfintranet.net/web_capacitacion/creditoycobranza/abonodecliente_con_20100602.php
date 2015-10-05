<?
	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){ // optener cliente abonocliente.php
		$s = "SELECT id, CONCAT_WS(' ',nombre, paterno, materno) AS ncliente,
		(SELECT SUM(total + otrosmontofacturar + sobmontoafacturar) AS total 
		FROM facturacion WHERE cliente='".$_GET[valor]."' GROUP BY cliente) AS totaldeudacliente
		FROM catalogocliente 
		WHERE id = '".$_GET[valor]."'";	
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->ncliente = cambio_texto($f->ncliente);
		$w="SELECT SUM(importe)AS importe FROM		
			(SELECT SUM(ge.total)  AS importe
			FROM guiasventanilla ge
			INNER JOIN facturacion f ON ge.factura=f.folio
			INNER JOIN catalogocliente cc ON f.cliente=cc.id
			INNER JOIN pagoguias pg ON ge.id=pg.guia
			WHERE pg.pagado='N' AND f.cliente='".$_GET[valor]."'
			UNION
			SELECT IF(SUM(ge.total) IS NULL,0,SUM(ge.total))  AS importe
			FROM guiasempresariales ge
			INNER JOIN facturacion f ON ge.factura=f.folio
			INNER JOIN catalogocliente cc ON f.cliente=cc.id
			INNER JOIN pagoguias pg ON ge.id=pg.guia
			WHERE pg.pagado='N' AND f.cliente='".$_GET[valor]."') tabla";
		$wx = mysql_query($w,$l) or die($w);
		$w_row=mysql_fetch_array($wx);
		$f->importe=$w_row[importe];
		
		echo str_replace("null",'""',json_encode($f));
		
	}else if($_GET[accion]==2){ // sucursal ,fecha,folio abonocliente.php
		$s = "select id AS idsucursal, descripcion as sucursal, 
		date_format(current_date, '%d/%m/%Y') AS fecha 
		from catalogosucursal where id = '".$_SESSION[IDSUCURSAL]."'";	
			//$registros = array();
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);

			$s = "SELECT obtenerFolio('abonodecliente',".$_SESSION[IDSUCURSAL].") AS folio";
			$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
			
			$f->folio=$fo->folio;			
			$f->sucursal = cambio_texto($f->sucursal);
			
			echo str_replace("null",'""',json_encode($f));
			
	}else if($_GET[accion]==3){//obtener Guia abonocliente.php
			$s = "(SELECT ge.id AS guia,ge.fecha, DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,
					f.folio AS foliofactura, ge.total  AS importe,
					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion
					FROM guiasventanilla ge
					INNER JOIN facturacion f ON ge.factura=f.folio
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE f.Folio NOT IN (SELECT ld.factura FROM liquidacioncobranza l
					INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
					WHERE l.estado='LIQUIDADO' GROUP BY ld.factura) 
					AND
					f.Folio NOT IN (SELECT a.factura FROM abonodecliente a 
					WHERE a.factura=(SELECT factura FROM guiasventanilla WHERE id='".$_GET[guia]."')
					&& a.idcliente='".$_GET[cliente]."') 
					AND
					ge.factura=(SELECT factura FROM guiasventanilla 
					WHERE id='".$_GET[guia]."') AND f.cliente='".$_GET[cliente]."')
					UNION 
					(SELECT ge.id AS guia,ge.fecha,
					DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,
					f.folio AS foliofactura, ge.total  AS importe,
					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual,1 AS aplicacion
					FROM guiasempresariales ge
					INNER JOIN facturacion f ON ge.factura=f.folio
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE f.Folio NOT IN (SELECT ld.factura FROM liquidacioncobranza l
					INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion 
					WHERE l.estado='LIQUIDADO' GROUP BY ld.factura)
					AND
					f.Folio NOT IN (SELECT a.factura FROM abonodecliente a 
					WHERE a.factura=(SELECT factura FROM guiasventanilla WHERE id='".$_GET[guia]."')
					&& a.idcliente='".$_GET[cliente]."') 
					AND ge.factura=(SELECT factura FROM guiasventanilla WHERE id='".$_GET[guia]."') 
					AND f.cliente='".$_GET[cliente]."')";	
				$registros = array();
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}
				echo str_replace("null",'""',json_encode($registros));
	}else if($_GET[accion]==4){// obtener factura abonocliente.php
		$s="SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fecha_venc,
		f.folio AS factura, gv.total  AS importe,
		(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,'' AS aplicacion
		FROM guiasventanilla gv
		INNER JOIN pagoguias pg ON gv.id=pg.guia
		INNER JOIN facturacion f ON gv.factura=f.folio
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE pg.pagado='N' AND f.cliente=".$_GET[cliente]."  and f.facturaestado = 'GUARDADO' 
		GROUP BY gv.id
	UNION 
		SELECT ge.id AS guia,ge.fecha,
		DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fecha_venc,
		f.folio AS factura, ge.total  AS importe,
		(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,'' AS aplicacion
		FROM guiasempresariales ge
		INNER JOIN pagoguias pg ON ge.id=pg.guia
		INNER JOIN facturacion f ON ge.factura=f.folio
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE pg.pagado='N' AND f.cliente=".$_GET[cliente]."  and f.facturaestado = 'GUARDADO' 
		GROUP BY ge.id
	UNION
		SELECT sg.id AS guia,DATE_FORMAT(sg.fecha,'%d/%m/%Y') AS fecha,
		DATE_FORMAT(DATE_ADD(sg.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fecha_venc,
		f.folio AS factura, sg.total  AS importe,
		(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,'' AS aplicacion
		FROM solicitudguiasempresariales sg
		INNER JOIN facturacion f ON sg.factura=f.folio
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.estadocobranza<>'C' AND f.cliente=".$_GET[cliente]."  and f.facturaestado = 'GUARDADO' 
		GROUP BY sg.id
	UNION
		SELECT f.folio AS guia,DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
		DATE_FORMAT(DATE_ADD(f.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fecha_venc,
		f.folio AS factura, f.otrosmontofacturar  AS importe,
		(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,'' AS aplicacion
		FROM facturacion f
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		WHERE f.estadocobranza<>'C' AND f.cliente=".$_GET[cliente]."  and f.facturaestado = 'GUARDADO'  
		and f.otrosmontofacturar>0
		GROUP BY f.folio";
			
				$registros = array();
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}
				echo str_replace("null",'""',json_encode($registros));
	}else if($_GET[accion]==5){  //Guardar abonodecliente.php
			$s = "INSERT INTO abonodecliente (folio,fecharegistro,idsucursal,
			idcliente,descripcion,abonar,saldocon,   
			saldoantesdeaplicar,efectivo,banco,cheque,ncheque,tarjeta,
			transferencia,notacredito,nnotacredito,factura,idusuario, usuario, fecha) VALUES 
			(obtenerFolio('abonodecliente',".$_SESSION[IDSUCURSAL]."),CURRENT_DATE,
			'".$_GET[idsucursal]."', '".$_GET[idcliente]."', 
			'".$_GET[descripcion]."', '".$_GET[abonar]."', 
			'".$_GET[saldocon]."', '".$_GET[sandoantes]."','".$_GET[efectivo]."',
			'".$_GET[banco]."','".$_GET[cheque]."','".$_GET[ncheque]."','".$_GET[tarjeta]."',
			'".$_GET[transferencia]."','".$_GET[nc]."','".$_GET[nc_folio]."','".$_GET[factura]."', '".$_SESSION[IDUSUARIO]."', 
			'".$_SESSION[NOMBREUSUARIO]."',CURRENT_DATE)";	
			$r = mysql_query($s,$l) or die($s);
			$folio = mysql_insert_id();
			
			$s = "SELECT folio FROM abonodecliente WHERE id = ".$folio;
			$r = mysql_query($s,$l) or die($s); 
			$fo = mysql_fetch_object($r);
			
			$s = "INSERT INTO formapago SET guia='$fo->folio',procedencia='A',tipo='X',
			total='$_GET[abonar]',efectivo='$_GET[efectivo]',
			tarjeta='$_GET[tarjeta]',transferencia='$_GET[transferencia]',
			cheque='$_GET[cheque]',ncheque='$_GET[ncheque]',banco='$_GET[banco]',notacredito='$_GET[nc]',
			nnotacredito='$_GET[nc_folio]',sucursal='$_SESSION[IDSUCURSAL]',
			usuario='$_SESSION[IDUSUARIO]',fecha=current_date, cliente = '".$_GET[idcliente]."'";
			@mysql_query(str_replace("''","null",$s),$l) or die($s);
			$idfp = mysql_insert_id($l);
			
			$s="call proc_RegistroCobranza('ABONOCLIENTE', '$idfp', '', '', $folio, $_GET[idcliente]);";
			mysql_query($s,$l) or die($s);
		
			#se debe liquidar el cargo de la factura de sobrepeso y otros y las prepagadas
			$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
			sucursalcobro='".$_GET[idsucursal]."' WHERE guia='$_GET[factura]' and tipo='FACT'";		
			mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
			
			#se debe marcar la factura como cobrada
			$s="UPDATE facturacion
			SET estadocobranza='C'
			WHERE folio='$_GET[factura]'";
			mysql_query($s,$l) or die($s);
		
			#liquidar las guias de la factura
			$sql="select gv.id as guias from guiasventanilla gv where gv.factura = '$_GET[factura]'
				union
				select ge.id as guias from guiasempresariales ge where ge.factura = '$_GET[factura]'";
			$r = mysql_query(str_replace("''",'NULL',$sql),$l) or die($sql);
			if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
				sucursalcobro='".$_GET[idsucursal]."' WHERE guia='$f->guias' and tipo<>'FACT'";		
				mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
				
				#SE REGISTRA EL PAGO PARA EL REPORTE DE COBRANZA
				$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$f->guias', '', '', 0, 0);";
				mysql_query($s,$l) or die($s);
				
				#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES YA SEA EMPRESARIAL O VENTANILLA
				$s="CALL proc_RegistroVendedores('PAGO_VEN_GV', '$f->guias');";
				mysql_query($s,$l) or die($s);
				
				$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$f->guias');";
				mysql_query($s,$l) or die($s);
				}
			}
			
			#para el registro de folios prepagados en reporte vendedores
			$sql = "SELECT id FROM solicitudguiasempresariales WHERE factura = '$_GET[factura]'";
			$r = mysql_query($sql,$l) or die($sql);
			if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
					#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES para pregadas
					$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$f->id');";
					mysql_query($s,$l) or die($s);
				}
			}
			
			echo "ok";
		
	}else if($_GET[accion]==6){
		$s="SELECT ac.folio,date_format(ac.fecharegistro,'%d/%m/%Y')AS fecharegistro, 
			ac.idsucursal,cs.descripcion AS sucursal, ac.idcliente, 
			CONCAT_WS(' ',cc.nombre, cc.paterno, cc.materno) AS cliente,
			ac.descripcion, ac.cobrador,
			ac.abonar, ac.saldocon, ac.saldoantesdeaplicar, 
			ac.efectivo, ac.banco, ac.cheque,ac.ncheque, ac.tarjeta, ac.transferencia, ac.factura 
			FROM abonodecliente  ac 
			INNER JOIN catalogosucursal cs ON cs.id=ac.idsucursal
			INNER JOIN catalogocliente cc ON cc.id=ac.idcliente
			WHERE ac.folio='".$_GET[id]."' and ac.idsucursal = ".$_SESSION[IDSUCURSAL];
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			echo str_replace("null",'""',json_encode($f));
			
	}else if($_GET[accion]==7){// obtener  factura guardada abonocliente.php
	
				$s="SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
					DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fecha_venc,
					f.folio AS factura, gv.total  AS importe,
					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,'' AS aplicacion
					FROM guiasventanilla gv
					INNER JOIN pagoguias pg ON gv.id=pg.guia
					INNER JOIN facturacion f ON gv.factura=f.folio
					INNER JOIN abonodecliente a ON f.folio=a.factura
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE a.folio=".$_GET[folio]." AND a.idsucursal = ".$_SESSION[IDSUCURSAL]."
					GROUP BY gv.id
				UNION 
					SELECT ge.id AS guia,ge.fecha,
					DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fecha_venc,
					f.folio AS factura, ge.total  AS importe,
					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,'' AS aplicacion
					FROM guiasempresariales ge
					INNER JOIN pagoguias pg ON ge.id=pg.guia
					INNER JOIN facturacion f ON ge.factura=f.folio
					INNER JOIN abonodecliente a ON f.folio=a.factura
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE a.folio=".$_GET[folio]." AND a.idsucursal = ".$_SESSION[IDSUCURSAL]."
					GROUP BY ge.id
				UNION
					SELECT sg.id AS guia,DATE_FORMAT(sg.fecha,'%d/%m/%Y') AS fecha,
					DATE_FORMAT(DATE_ADD(sg.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fecha_venc,
					f.folio AS factura, sg.total  AS importe,
					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,'' AS aplicacion
					FROM solicitudguiasempresariales sg
					INNER JOIN facturacion f ON sg.factura=f.folio
					INNER JOIN abonodecliente a ON f.folio=a.factura
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE a.folio=".$_GET[folio]." AND a.idsucursal = ".$_SESSION[IDSUCURSAL]."
					GROUP BY sg.id
				UNION
					SELECT f.folio AS guia,DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fecha,
					DATE_FORMAT(DATE_ADD(f.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fecha_venc,
					f.folio AS factura, f.otrosmontofacturar  AS importe,
					(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,'' AS aplicacion
					FROM facturacion f
					INNER JOIN abonodecliente a ON f.folio=a.factura
					INNER JOIN catalogocliente cc ON f.cliente=cc.id
					WHERE a.folio=".$_GET[folio]." AND a.idsucursal = ".$_SESSION[IDSUCURSAL]."
					and f.otrosmontofacturar>0
					GROUP BY f.folio
									
				/*SELECT ge.id AS guia,DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,
				DATE_FORMAT(DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY),'%d/%m/%Y') AS fecha_venc,
				f.folio AS factura, ge.total  AS importe,
				(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,1 AS aplicacion
				FROM guiasventanilla ge
				INNER JOIN facturacion f ON ge.factura=f.folio
				INNER JOIN abonodecliente a ON f.folio=a.factura
				INNER JOIN catalogocliente cc ON f.cliente=cc.id
				WHERE a.folio=".$_GET[folio]." AND a.idsucursal = ".$_SESSION[IDSUCURSAL]." GROUP BY ge.id
				UNION 
				SELECT ge.id AS guia,ge.fecha,
				DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fecha_venc,
				f.folio AS factura, ge.total  AS importe,
				(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldo,1 AS aplicacion
				FROM guiasempresariales ge
				INNER JOIN facturacion f ON ge.factura=f.folio
				INNER JOIN abonodecliente a ON f.folio=a.factura
				INNER JOIN catalogocliente cc ON f.cliente=cc.id
				WHERE a.folio=".$_GET[folio]." AND a.idsucursal = ".$_SESSION[IDSUCURSAL]." GROUP BY ge.id*/";
				$registros = array();
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}
				echo str_replace("null",'""',json_encode($registros));
	}else if($_GET[accion]==8){// obtener  factura 
	
		$s="SELECT factura FROM (SELECT IFNULL(factura,0)AS factura FROM guiasventanilla WHERE id='".$_GET[guia]."'
		UNION
		SELECT IFNULL(factura,0)AS factura  FROM guiasempresariales WHERE id='".$_GET[guia]."'
		)Tabla";
		$r=mysql_query($s,$l)or die($s); 
			$registros= array();
			if (mysql_num_rows($r)>0){
					while ($f=mysql_fetch_object($r))
					{
						$registros[]=$f;	
					}
					echo str_replace('null','""',json_encode($registros));
			}else{
					echo str_replace('null','""',json_encode(0));
			}
	}
?>