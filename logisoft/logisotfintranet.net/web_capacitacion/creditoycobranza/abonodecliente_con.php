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
		$datoscliente =  str_replace("null",'""',json_encode($f));
		
		$valor = $_GET[idpagina] - 3000000000;
		
		$s = "DELETE FROM abonodecliente_detalle_tmp WHERE usuario = $valor";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM abonodecliente_detalle_tmp WHERE usuario = $_GET[idpagina]";
		mysql_query($s,$l) or die($s);
		$s = "DELETE FROM abonodecliente_detalle_ori WHERE usuario = $_GET[idpagina]";
		mysql_query($s,$l) or die($s);
		
		$s="INSERT INTO abonodecliente_detalle_tmp (folios, fecha, fechavencimiento,factura,total,usuario)
		SELECT GROUP_CONCAT(folio) , fecha, fecha_venc, factura, saldo, $_GET[idpagina]
		FROM (
			(SELECT fd.folio, f.fecha, 
			DATE_ADD(f.fecha, INTERVAL cc.diascredito DAY) AS fecha_venc,
			f.folio factura, (ifnull(f.total,0) + ifnull(f.otrosmontofacturar,0) + ifnull(f.sobmontoafacturar,0)) AS saldo
			FROM facturacion f
			INNER JOIN facturadetalle fd ON f.folio = fd.factura
			INNER JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.cliente = '$_GET[cliente]' AND f.facturaestado = 'GUARDADO' AND f.credito = 'SI'
			AND estadocobranza <> 'C' AND idsucursal = $_SESSION[IDSUCURSAL])
			UNION
			(SELECT fd.guia, f.fecha, 
			DATE_ADD(f.fecha, INTERVAL cc.diascredito DAY) AS fecha_venc,
			f.folio, (ifnull(f.total,0) + ifnull(f.otrosmontofacturar,0) + ifnull(f.sobmontoafacturar,0)) AS saldo
			FROM facturacion f
			INNER JOIN facturadetalleguias fd ON f.folio = fd.factura
			INNER JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.cliente = '$_GET[cliente]' AND f.facturaestado = 'GUARDADO' AND f.credito = 'SI'
			AND estadocobranza <> 'C' AND idsucursal = $_SESSION[IDSUCURSAL])
			UNION
			(SELECT 'OTROS', f.fecha, 
			DATE_ADD(f.fecha, INTERVAL cc.diascredito DAY) AS fecha_venc,
			f.folio, (ifnull(f.total,0) + ifnull(f.otrosmontofacturar,0) + ifnull(f.sobmontoafacturar,0)) AS saldo
			FROM facturacion f
			INNER JOIN catalogocliente cc ON f.cliente=cc.id
			WHERE f.cliente = '$_GET[cliente]' AND f.otrosmontofacturar>0 AND f.facturaestado = 'GUARDADO' AND f.credito = 'SI'
			AND estadocobranza <> 'C' AND idsucursal = $_SESSION[IDSUCURSAL])
		) t1
		GROUP BY t1.factura";
		mysql_query($s,$l) or die($s);
		$s = "INSERT INTO abonodecliente_detalle_ori (folios, fecha, fechavencimiento,factura,total,usuario)
		select folios, fecha, fechavencimiento,factura,total,usuario from abonodecliente_detalle_tmp where usuario = $_GET[idpagina]";
		mysql_query($s,$l) or die($s);
		
				$s = "select tmp.*,DATE_FORMAT(fecha,'%d/%m/%Y') fecha, 
				DATE_FORMAT(fechavencimiento,'%d/%m/%Y') fechavencimiento, 
				1 aplicacion from abonodecliente_detalle_tmp tmp where usuario = $_GET[idpagina]";
				$registros = array();
				$r = mysql_query($s,$l) or die($s);
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}
				$facturas = str_replace("null",'""',json_encode($registros));
		
		echo "({
			   'cliente':$datoscliente,
			   'facturas':$facturas
		})";
	}
	
	if($_GET[accion] == 2){
		//agrupar
		$s = "DELETE FROM abonodecliente_detalle_tmp WHERE factura IN($_GET[facturas]) and usuario = $_GET[idpagina];";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO abonodecliente_detalle_tmp (folios, fecha, fechavencimiento,factura,total,usuario)
		SELECT GROUP_CONCAT(folios), fecha, fechavencimiento,GROUP_CONCAT(factura),sum(total),usuario 
		FROM abonodecliente_detalle_ori WHERE factura IN($_GET[facturas]) and usuario = $_GET[idpagina] order by factura";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT abonodecliente_detalle_tmp.*, date_format(fecha, '%d/%m/%Y') fecha, date_format(fechavencimiento, '%d/%m/%Y') fechavencimiento
		FROM abonodecliente_detalle_tmp 
		WHERE usuario = $_GET[idpagina] order by factura"; 
		$r = mysql_query($s,$l) or die($s);
		$registrosc = array();
			while($f = mysql_fetch_object($r)){				
				$registrosc[] = $f;
			}
		$acob = str_replace('null','""',json_encode($registrosc));
		
		echo '({"registrosc":'.$acob.'})';
	}
	
	if($_GET[accion] == 3){
		//desagrupar
		$s = "DELETE FROM abonodecliente_detalle_tmp WHERE factura ='$_GET[facturas]' and usuario = $_GET[idpagina];";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO abonodecliente_detalle_tmp (folios, fecha, fechavencimiento,factura,total,usuario)
		SELECT folios, fecha, fechavencimiento,factura,total,usuario 
		FROM abonodecliente_detalle_ori WHERE factura IN($_GET[facturas]) and usuario = $_GET[idpagina] order by factura";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT abonodecliente_detalle_tmp.*, date_format(fecha, '%d/%m/%Y') fecha, date_format(fechavencimiento, '%d/%m/%Y') fechavencimiento,
		factura*1 as orden
		FROM abonodecliente_detalle_tmp 
		WHERE usuario = $_GET[idpagina] order by orden"; 
		$r = mysql_query($s,$l) or die($s);
		$registrosc = array();
			while($f = mysql_fetch_object($r)){				
				$registrosc[] = $f;
			}
		$acob = str_replace('null','""',json_encode($registrosc));
		
		echo '({"registrosc":'.$acob.'})';
	}
	if($_GET[accion] == 4){
		$row = split(",",$_GET[arre]);
		
		$total = $row[0]+$row[4]+$row[5]+$row[6]+$row[1];
		
		$s = "UPDATE abonodecliente_detalle_tmp set
		totalpagado=$total, efectivo='$row[0]', tarjeta='$row[4]', transferencia='$row[5]', 
		notacredito='$row[7]', totalnotacredito='$row[6]', cantidadcheque='$row[1]',
		banco='$row[2]', nocheque='$row[3]' WHERE factura = '$_GET[factura]' and usuario = $_GET[idpagina]";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	}
	
	if($_GET[accion] == 5){
		
		$s = "UPDATE abonodecliente_detalle_tmp set
		totalpagado=0, efectivo='0', tarjeta='0', transferencia='0', 
		notacredito='0', totalnotacredito='0', cantidadcheque='0',
		banco='0', nocheque='0' WHERE factura = '$_GET[factura]' and usuario = $_GET[idpagina]";
		mysql_query($s,$l) or die($s);
		
		echo "okxxSEPAxx$_GET[factura]";
	}
	
	if($_GET[accion]==6){ // sucursal ,fecha,folio abonocliente.php
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
			
	}
	
	if($_GET[accion]==7){
			$s = "SELECT group_concat(factura) factura
			FROM abonodecliente_detalle_tmp
			WHERE usuario = '$_GET[idpagina]' and totalpagado>0";
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$arre = split(",",$f->factura);
				for($i=0; $i < count($arre); $i++){				
					$s = "SELECT id FROM abonodecliente_detalle 
					WHERE factura LIKE '".$arre[$i].",%' OR factura LIKE '%,".$arre[$i].",%' OR factura LIKE '%,".$arre[$i]."'
					LIMIT 1";
					$rx = mysql_query($s,$l) or die($s);
					if(mysql_num_rows($rx)>0){
						die("Ya fue liquidada una de las facturas, vuelva a cargar la pagina");
					}
				}
				
			}
		
			$s = "INSERT INTO abonodecliente (folio,fecharegistro,idsucursal,
			idcliente,descripcion,abonar,saldocon,   
			saldoantesdeaplicar,idusuario, usuario, fecha) VALUES 
			(obtenerFolio('abonodecliente',".$_SESSION[IDSUCURSAL]."),CURRENT_DATE,
			'".$_SESSION[IDSUCURSAL]."', '".$_GET[idcliente]."', 
			'".$_GET[descripcion]."', '".$_GET[abonar]."', 
			'".$_GET[saldocon]."', '".$_GET[sandoantes]."','".$_SESSION[IDUSUARIO]."', 
			'".$_SESSION[NOMBREUSUARIO]."',CURRENT_DATE)";	
			$r = mysql_query($s,$l) or die($s);
			$folio = mysql_insert_id();
			
			#se obtiene folio de abonocliente
			$s = "SELECT folio FROM abonodecliente WHERE id = ".$folio;
			$r = mysql_query($s,$l) or die($s); 
			$fo = mysql_fetch_object($r);
			$fosu = $fo->folio;
			
			$s = "INSERT INTO  abonodecliente_detalle
			(folioabono,sucursal,folios,fecha,fechavencimiento,factura,total,usuario,
			totalpagado,efectivo,tarjeta,transferencia,notacredito,totalnotacredito,
			cantidadcheque,banco,nocheque)
			SELECT '$folio','$_SESSION[IDSUCURSAL]',folios,fecha,fechavencimiento,factura,total,usuario,
			totalpagado,efectivo,tarjeta,transferencia,notacredito,totalnotacredito,
			cantidadcheque,banco,nocheque FROM abonodecliente_detalle_tmp
			WHERE usuario = '$_GET[idpagina]' and totalpagado>0";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM abonodecliente_detalle WHERE folioabono = $folio;";
			$rmd = mysql_query($s,$l) or die($s);
			while($fmd = mysql_fetch_object($rmd)){
				
				//insertar forma de pago
				$s = "INSERT INTO formapago SET guia='$fo->folio',procedencia='A',tipo='X',
				total='$fmd->total',efectivo='$fmd->efectivo',
				tarjeta='$fmd->tarjeta',transferencia='$fmd->transferencia',
				cheque='$fmd->cantidadcheque',ncheque='$fmd->nocheque',banco='$fmd->banco',notacredito='$fmd->totalnotacredito',
				nnotacredito='$fmd->notacredito',sucursal='$_SESSION[IDSUCURSAL]',
				usuario='$_SESSION[IDUSUARIO]',fecha=current_date, cliente = '".$_GET[idcliente]."'";
				@mysql_query(str_replace("''","null",$s),$l) or die($s);
				$idfp = mysql_insert_id($l);
				
				$s="call proc_RegistroCobranza('ABONOCLIENTE', '$idfp', '', '', $folio, $_GET[idcliente]);";
				mysql_query($s,$l) or die($s);
				
				$tguias = $fmd->folios;
				$tfacturas = $fmd->factura;
				
				if(count(split(",",$tguias))>1){
					$arretguias = split(",",$tguias);
					for($i=0; $i<count($arretguias); $i++){
						$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
						sucursalcobro='".$_SESSION[IDSUCURSAL]."' WHERE guia='$arretguias[$i]' and tipo<>'FACT'";		
						mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
						
						$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$arretguias[$i]', '', '', 0, 0);";
						mysql_query($s,$l) or die($s);
						
						#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES YA SEA EMPRESARIAL O VENTANILLA
						$s="CALL proc_RegistroVendedores('PAGO_VEN_GV', '$arretguias[$i]');";
						mysql_query($s,$l) or die($s);
						
						$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$arretguias[$i]');";
						mysql_query($s,$l) or die($s);
						
						$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$arretguias[$i]');";
						mysql_query($s,$l) or die($s);
					}
				}else{
					$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
					sucursalcobro='".$_SESSION[IDSUCURSAL]."' WHERE guia='$tguias' and tipo<>'FACT'";		
					mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
					
					$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$tguias', '', '', 0, 0);";
					mysql_query($s,$l) or die($s);
					
					#SE REGISTRA EL PAGO PARA LA COMISION DE VENDEDORES YA SEA EMPRESARIAL O VENTANILLA
					$s="CALL proc_RegistroVendedores('PAGO_VEN_GV', '$tguias');";
					mysql_query($s,$l) or die($s);
					
					$s="CALL proc_RegistroVendedores('PAGO_VEN_GEC', '$tguias');";
					mysql_query($s,$l) or die($s);
					
					$s="CALL proc_RegistroVendedores('PAGO_VEN_PRE', '$tguias');";
					mysql_query($s,$l) or die($s);
				}
				
				if(count(split(",",$tfacturas))>1){
					$arretfacturas = split(",",$tfacturas);
					for($i=0; $i<count($arretfacturas); $i++){
						$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$arretfacturas[$i]', '', '', 0, 0);";
						mysql_query($s,$l) or die($s);		
						
						$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
						sucursalcobro='".$_SESSION[IDSUCURSAL]."' WHERE guia='$arretfacturas[$i]' and tipo='FACT'";		
						mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
						
						#se debe marcar la factura como cobrada
						$s="UPDATE facturacion
						SET estadocobranza='C'
						WHERE folio='$arretfacturas[$i]'";
						mysql_query($s,$l) or die($s);
						
						$s = "INSERT INTO abonodecliente_facturas SET folioabono='$folio', 
						factura='".$arretfacturas[$i]."', sucursal='".$_SESSION[IDSUCURSAL]."'";
						mysql_query($s,$l) or die($s);
						
					}
				}else{
					$s="call proc_RegistroCobranza('PAGOS_FOLIOS', '$tfacturas', '', '', 0, 0);";
					mysql_query($s,$l) or die($s);
					
					$sq="UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
						sucursalcobro='".$_SESSION[IDSUCURSAL]."' WHERE guia='$tfacturas' and tipo='FACT'";		
						mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
					
					#se debe marcar la factura como cobrada
					$s="UPDATE facturacion
					SET estadocobranza='C'
					WHERE folio='$tfacturas'";
					mysql_query($s,$l) or die($s);
					
					$s = "INSERT INTO abonodecliente_facturas SET folioabono='$folio', 
					factura='".$tfacturas."', sucursal='".$_SESSION[IDSUCURSAL]."'";
					mysql_query($s,$l) or die($s);
				}
			}
			
			echo "ok";
	}
	
	if($_GET[accion]==8){
		$s="SELECT ac.id, ac.folio,date_format(ac.fecharegistro,'%d/%m/%Y')AS fecharegistro, 
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
			$id = $f->id;
			$abono = str_replace("null",'""',json_encode($f));
			
			$s = "SELECT folios, DATE_FORMAT(fecha,'%d/%m/%Y') fecha, 
			DATE_FORMAT(fechavencimiento,'%d/%m/%Y') fechavencimiento,
			factura,total,totalpagado,0 aplicacion 
			FROM abonodecliente_detalle WHERE folioabono = '$id'";
			
			$r = mysql_query($s,$l) or die($s);
			$arre = array();
			while($f = mysql_fetch_object($r)){
				$arre[] = $f;
			}
			
			$datos = str_replace("null",'""',json_encode($arre));
			
			echo "({cliente:$abono,
				   datos:$datos})";
	}
	?>