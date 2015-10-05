<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = mysql_query("SELECT 
		ifnull(sum(IF(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(trasferencia,0)+IFNULL(cheque,0) > total, 
			IF(efectivo - (total-(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(trasferencia,0)+IFNULL(cheque,0)))<0,
				0,
				efectivo - (total-(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(trasferencia,0)+IFNULL(cheque,0)))
			),
			IFNULL(efectivo,0)
		)),0) efectivo,
		IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(trasferencia),0) AS transferencia,
		IFNULL(SUM(cheque),0) AS cheque, 0 AS retiros FROM guiasventanilla WHERE tipoflete=0 AND condicionpago=0 		
		".((!empty($_GET[cambiafecha]))? " AND fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " AND fecha=CURDATE()")."
		AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."' AND estado<>'CANCELADO'",$l);	
		$row = mysql_fetch_object($s);
		
		$e = mysql_query("SELECT 
		ifnull(sum(IF(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(trasferencia,0)+IFNULL(cheque,0) > total, 
			IF(efectivo - (total-(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(trasferencia,0)+IFNULL(cheque,0)))<0,
				0,
				efectivo - (total-(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(trasferencia,0)+IFNULL(cheque,0)))
			),
			IFNULL(efectivo,0)
		)),0) efectivo, 						
		IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(trasferencia),0) AS transferencia,
		IFNULL(SUM(cheque),0) AS cheque, 0 AS retiros FROM guiasempresariales WHERE tipoflete='PAGADA' AND tipopago='CONTADO' 
		".((!empty($_GET[cambiafecha]))? " AND fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " AND fecha=CURDATE()")."
		AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."' AND tipoguia='CONSIGNACION'",$l);
		$emp = mysql_fetch_object($e);
		
		//A - ABONOS M - LIQ. MERCA C - COBRANZA O - ENTRE. OCURRE F - FACTURA
		$s = "SELECT 
		ifnull(sum(IF(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(transferencia,0)+IFNULL(cheque,0) > total, 
			IF(efectivo - (total-(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(transferencia,0)+IFNULL(cheque,0)))<0,
				0,
				efectivo - (total-(IFNULL(efectivo,0)+IFNULL(tarjeta,0)+IFNULL(transferencia,0)+IFNULL(cheque,0)))
			),
			IFNULL(efectivo,0)
		)),0) efectivo,
		IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(transferencia),0) AS transferencia,
		IFNULL(SUM(cheque),0) AS cheque FROM formapago WHERE procedencia IN ('A','M','C','O','F') AND usuario = ".$_SESSION[IDUSUARIO]." 
		AND sucursal = ".$_SESSION[IDSUCURSAL]." AND fecha = CURDATE()";
		$r = mysql_query($s,$l) or die($s);
		/*$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(transferencia),0) AS transferencia,
		IFNULL(SUM(cheque),0) AS cheque FROM formapago WHERE procedencia IN ('A','M','C','O','F') AND usuario = ".$_SESSION[IDUSUARIO]." 
		AND sucursal = ".$_SESSION[IDSUCURSAL]." AND fecha = CURDATE()";
		$r = mysql_query($s,$l) or die($s);*/
		$f = mysql_fetch_object($r);
		
		
		$p->tefectivo		 = $row->efectivo + $f->efectivo + $emp->efectivo;
		$p->ttarjeta		 = $row->tarjeta + $f->tarjeta + $emp->tarjeta;
		$p->ttransferencia	 = $row->transferencia + $f->transferencia + $emp->transferencia;
		$p->tcheque			 = $row->cheque + $f->cheque + $emp->cheque;
		$p->tretiros		 = $row->retiros;
		
		$s = "SELECT IFNULL(SUM(notacredito),0) notacredito FROM formapago 
		WHERE notacredito>0 AND ISNULL(fechacancelacion) 
		AND usuario = '$_SESSION[IDUSUARIO]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$p->notascredito = $f->notacredito;
		
		$s = "SELECT tipocierre FROM cierrecaja
		WHERE fechacierre = CURDATE() AND sucursal = ".$_SESSION[IDSUCURSAL]."
		AND usuariocaja = ".$_SESSION[IDUSUARIO]."";		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$p->tipocierre = $f->tipocierre;
		
		$principal = str_replace('null','""',json_encode($p));
		
		$can = mysql_query("SELECT COUNT(*) AS canceladas FROM guiasventanilla
		WHERE ".((!empty($_GET[cambiafecha]))? " fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " fecha=CURDATE()")."		
		AND estado='CANCELADO' AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."'",$l);
		$f = mysql_fetch_object($can);
		$tguiacancelada = $f->canceladas;
		
		$cre = mysql_query("SELECT COUNT(*) AS credito FROM guiasventanilla
		WHERE ".((!empty($_GET[cambiafecha]))? " fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " fecha=CURDATE()")."			
		AND tipoflete=0 AND condicionpago='1' AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."'",$l);
		$f = mysql_fetch_object($can);
		$tguiacredito = ((!empty($f->credito))?$f->credito:0); 
		
		$fac= mysql_query("SELECT COUNT(*) AS facturaestado FROM facturacion
		WHERE ".((!empty($_GET[cambiafecha]))? " fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " fecha=CURDATE()")."
		AND facturaestado='CANCELADO' AND idusuario='".$_SESSION[IDUSUARIO]."'
		AND idsucursal='".$_SESSION[IDSUCURSAL]."'",$l);	
		$f = mysql_fetch_object($fac);		
		$tfacturas = $f->facturaestado;
		
		$sqlini = mysql_query("SELECT id FROM iniciocaja WHERE usuariocaja='".$_SESSION[IDUSUARIO]."' AND 
		".((!empty($_GET[cambiafecha]))? " fechainiciocaja = '".cambiaf_a_mysql($_GET[fecha])."'" : " fechainiciocaja=CURDATE()")."",$l);
		$f = mysql_fetch_object($sqlini);
		$iniciocaja = 0;
		if(mysql_num_rows($sqlini)>0){
			$iniciocaja = $f->id;
		}
		$o->tguiacancelada 	= $tguiacancelada;
		$o->tguiacredito 	= $tguiacredito;
		$o->tfacturas 		= $tfacturas;
		$o->iniciocaja 		= $iniciocaja;
		
		$otro = str_replace('null','""',json_encode($o));
	
		echo "({principal:$principal,otros:$otro})";
		
	}else if($_GET[accion]==2){
		if(!empty($_GET[cambiafecha]))
			$fechacierre = "fechacierre = '".cambiaf_a_mysql($_GET[fecha])."'";
		else
			$fechacierre = "fechacierre = CURDATE()";
	
		$s = "INSERT INTO cierrecaja SET
		iniciocaja = ".$_GET[iniciocaja].", usuariocaja = ".$_SESSION[IDUSUARIO].",
		$fechacierre, efectivo = ".$_GET[efectivo].", tarjeta = ".$_GET[tarjeta].", transferencia = ".$_GET[transferencia].", cheque = ".$_GET[cheque].",
		retiros = ".$_GET[retiros].", guiacredito = ".$_GET[guiacredito].", guiacancelada = ".$_GET[guiacancelada].", facturacancelada = ".$_GET[facturacancelada].",
		tipocierre = 'definitivo', sucursal = ".$_SESSION[IDSUCURSAL].", usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp(),
		difefectivo = ".$_GET[difefectivo].", diftarjeta = ".$_GET[diftarjeta].", diftransferencia = ".$_GET[diftransferencia].",
		difcheque = ".$_GET[difcheque].", difretiros = ".$_GET[difretiros].", refectivo = ".$_GET[refectivo].", rcheque = ".$_GET[rcheque].", 
		rtransferencia = ".$_GET[rtransferencia].", rtarjeta = ".$_GET[rtarjeta]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok,".mysql_insert_id();
		
	}else if($_GET[accion]==3){
		$s = "SELECT dia FROM configuradorgeneraldias WHERE dia = '".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die("diafestivo");
		}
		
		$s = "SELECT DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."') AS dia";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		if($f->dia==1){
			die("domingo");
		}
			
		$s = "SELECT * FROM iniciocaja WHERE sucursal = ".$_SESSION[IDSUCURSAL]." 
		AND fechainiciocaja = '".cambiaf_a_mysql($_GET[fecha])."' AND usuariocaja = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)==0){
			die("noiniciocaja");
		}
	
		$s = "SELECT tipocierre FROM cierrecaja
		WHERE fechacierre = '".cambiaf_a_mysql($_GET[fecha])."' AND sucursal = ".$_SESSION[IDSUCURSAL]."
		AND usuariocaja = ".$_SESSION[IDUSUARIO]."";		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		if($f->tipocierre=="definitivo"){
			die("yacerro");
		}
		
		$s = mysql_query("SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(trasferencia),0) AS transferencia,
		IFNULL(SUM(cheque),0) AS cheque, 0 AS retiros FROM guiasventanilla WHERE tipoflete=0 AND condicionpago=0 		
		".((!empty($_GET[cambiafecha]))? " AND fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " AND fecha=CURDATE()")."
		AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."' AND estado<>'CANCELADO'",$l);	
		$row = mysql_fetch_object($s);	
		
		$e = mysql_query("SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(trasferencia),0) AS transferencia,
		IFNULL(SUM(cheque),0) AS cheque, 0 AS retiros FROM guiasempresariales WHERE tipoflete='PAGADA' AND tipopago='CONTADO' 
		".((!empty($_GET[cambiafecha]))? " AND fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " AND fecha=CURDATE()")."
		AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."' AND tipoguia='CONSIGNACION'",$l);
		$emp = mysql_fetch_object($e);
		
		//A - ABONOS M - LIQ. MERCA C - COBRANZA O - ENTRE. OCURRE F - FACTURA
		$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(transferencia),0) AS transferencia,
		IFNULL(SUM(cheque),0) AS cheque FROM formapago WHERE procedencia IN ('A','M','C','O','F') AND usuario = ".$_SESSION[IDUSUARIO]." 
		AND sucursal = ".$_SESSION[IDSUCURSAL]." 
		".((!empty($_GET[cambiafecha]))? " AND fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " AND fecha=CURDATE()")."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		
		$p->tefectivo 		= $row->efectivo + $f->efectivo + $emp->efectivo;
		$p->ttarjeta		= $row->tarjeta + $f->tarjeta + $emp->tarjeta;
		$p->ttransferencia 	= $row->transferencia + $f->transferencia + $emp->transferencia; 		
		$p->tcheque 		= $row->cheque + $f->cheque + $emp->cheque;
		$p->tretiros 		= $row->retiros;
		
		$principal = str_replace('null','""',json_encode($p));
		
		$can = mysql_query("SELECT COUNT(*) AS canceladas FROM guiasventanilla
		WHERE ".((!empty($_GET[cambiafecha]))? " fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " fecha=CURDATE()")."		
		AND estado='CANCELADO' AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."'",$l);
		$f = mysql_fetch_object($can);
		$tguiacancelada = $f->canceladas;
		
		$cre = mysql_query("SELECT COUNT(*) AS credito FROM guiasventanilla
		WHERE ".((!empty($_GET[cambiafecha]))? " fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " fecha=CURDATE()")."			
		AND tipoflete=0 AND condicionpago='1' AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."'",$l);
		$f = mysql_fetch_object($can);
		$tguiacredito = ((!empty($f->credito))?$f->credito:0); 
		
		$fac= mysql_query("SELECT COUNT(*) AS facturaestado FROM facturacion
		WHERE ".((!empty($_GET[cambiafecha]))? " fecha='".cambiaf_a_mysql($_GET[fecha])."'" : " fecha=CURDATE()")."
		AND facturaestado='CANCELADO' AND idusuario='".$_SESSION[IDUSUARIO]."'
		AND idsucursal='".$_SESSION[IDSUCURSAL]."'",$l);	
		$f = mysql_fetch_object($fac);		
		$tfacturas = $f->facturaestado;
		
		$sqlini = mysql_query("SELECT id FROM iniciocaja WHERE usuariocaja='".$_SESSION[IDUSUARIO]."' AND 
		".((!empty($_GET[cambiafecha]))? " fechainiciocaja = '".cambiaf_a_mysql($_GET[fecha])."'" : " fechainiciocaja=CURDATE()")."",$l);
		$f = mysql_fetch_object($sqlini);
		$iniciocaja = 0;
		if(mysql_num_rows($sqlini)>0){
			$iniciocaja = $f->id;
		}
		$o->tguiacancelada 	= $tguiacancelada;
		$o->tguiacredito 	= $tguiacredito;
		$o->tfacturas 		= $tfacturas;
		$o->iniciocaja 		= $iniciocaja;
		
		$otro = str_replace('null','""',json_encode($o));
	
		echo "({principal:$principal,otros:$otro})";
	
	}else if($_GET[accion]==4){
		$s = "SELECT * FROM cierreprincipal 
		WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND estado = 'CERRADA' AND fechacierre = CURDATE()";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0)
			$f->cierreprincipal="SI";
		else
			$f->cierreprincipal="NO";
		
		$s = "SELECT id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia= CURDATE()";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0)
			$f->iniciodia="SI";
		else
			$f->iniciodia="NO";
	
		$s = "SELECT * FROM cierredia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechacierredia=CURDATE()";		
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0)
			$f->existecierre="SI";
		else
			$f->existecierre="NO";
	
	
		$s = "SELECT MAX(id) AS iniciodia FROM iniciodia WHERE sucursal=".$_SESSION[IDSUCURSAL]." AND fechainiciodia=CURDATE()";
		$r = mysql_query($s,$l) or die($s); $t = mysql_fetch_object($r);
		
		$s = "SELECT * FROM cierredia WHERE iniciodia='".$t->iniciodia."' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0)
			$f->cierre="SI";
		else
			$f->cierre="NO";
		
		$f->folioiniciodia = $t->iniciodia;
		
		
		$s = "SELECT DAYOFWEEK(ADDDATE(CURRENT_DATE, INTERVAL -1 DAY)) dia";
		$r = mysql_query($s,$l) or die($s);
		$ff = mysql_fetch_object($r);
		if($ff->dia==1)
			$diasmenos = 2;
		else
			$diasmenos = 1;
		
		$s = "SELECT id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY)";
		$aa = mysql_query($s,$l) or die($s);
		
		$s = "SELECT id FROM iniciocaja WHERE fechainiciocaja = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY) AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$rt = mysql_query($s,$l) or die($s);
		$deposito = 0;
		if(mysql_num_rows($aa)>0 && mysql_num_rows($rt)>0){
			$s = "SELECT folio FROM deposito WHERE fechaefectivo = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY) AND sucursal = ".$_SESSION[IDSUCURSAL];
			$r = mysql_query($s,$l) or die($s);
			$deposito = ((mysql_num_rows($r)==0)?1:0);
		}
		
		$f->deposito = $deposito;
		
		$principal = str_replace('null','""',json_encode($f));
		
		echo "({principal:$principal})";
	
	}else if($_GET[accion]==5){
		if(!empty($_GET[cambiafecha]))
			$fechacierredia = "fechacierredia = '".cambiaf_a_mysql($_GET[fecha])."'";
		else
			$fechacierredia = "fechacierredia = CURDATE()";
	
		$s = "INSERT INTO cierredia SET 
		$fechacierredia,
		iniciodia = ".$_GET[iniciodia].",
		idusuario = ".$_SESSION[IDUSUARIO].",
		sucursal = ".$_SESSION[IDSUCURSAL].",
		usuario = '".$_SESSION[NOMBREUSUARIO]."',
		fecha = current_timestamp()";
		mysql_query($s,$l) or die($s);
		
		echo "ok,".mysql_insert_id();				
	
	}else if($_GET[accion]==6){
		$s = "SELECT dia FROM configuradorgeneraldias WHERE dia = '".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die("diafestivo");
		}
		
		$s = "SELECT DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."') AS dia";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		if($f->dia==1){
			die("domingo");
		}
		
		$s = "SELECT * FROM cierreprincipal 
		WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND estado = 'CERRADA' AND fechacierre = '".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)==0){
			die("cierreprincipal");
		}		
		
		
		$s = "SELECT id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia= '".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)==0){
			die("iniciodia");
		}
	
		$s = "SELECT * FROM cierredia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechacierredia='".cambiaf_a_mysql($_GET[fecha])."'";		
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die("yacerro");
		}
	
	
		$s = "SELECT MAX(id) AS iniciodia FROM iniciodia WHERE sucursal=".$_SESSION[IDSUCURSAL]." AND fechainiciodia='".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s); $t = mysql_fetch_object($r);
		
		$f->folio = $t->iniciodia;
		
			
/*		$deposito = 0;
		
		$s = "SELECT folio FROM deposito WHERE fechadeposito = '".cambiaf_a_mysql($_GET[fecha])."' AND sucursal = ".$_SESSION[IDSUCURSAL];
		$r = mysql_query($s,$l) or die($s);
		$deposito = ((mysql_num_rows($r)==0)?1:0);		
		
		$f->deposito = $deposito;*/
		
		
		$principal = str_replace('null','""',json_encode($f));
		
		echo "({principal:$principal})";
	
	}else if($_GET[accion]==7){
		if($_GET[modulo]=="cierrecaja"){
			$s = "SELECT 1 AS sel, i.id AS caja, DATE_FORMAT(i.fechainiciocaja,'%d/%m/%Y') AS fecha, i.usuariocaja FROM iniciocaja i
			WHERE NOT EXISTS(SELECT * FROM cierrecaja c WHERE i.id = c.iniciocaja) AND i.sucursal = ".$_GET[sucursal]." AND
			fechainiciocaja < CURDATE()";
		
		}else if($_GET[modulo]=="cierreprincipal"){
			$s = "SELECT 1 AS sel, DATE_FORMAT(cf.fecha,'%d/%m/%Y') AS fecha, cp.fechacierre, 0 AS usuariocaja, 0 AS caja
			FROM cierreprincipal_fechas cf
			LEFT JOIN cierreprincipal cp ON cf.fecha = cp.fechacierre AND cf.sucursal = cp.sucursal 
			WHERE cf.fecha < CURDATE() AND cf.sucursal = ".$_GET[sucursal]." AND (cp.estado IS NULL OR cp.estado = 'GUARDADO')";

		}else if($_GET[modulo]=="cierredia"){
			$s = "SELECT 1 AS sel, i.id AS caja, DATE_FORMAT(i.fechainiciodia,'%d/%m/%Y') AS fecha, 0 AS usuariocaja FROM iniciodia i
			WHERE NOT EXISTS(SELECT * FROM cierredia c WHERE i.id = c.iniciodia) AND i.sucursal = ".$_GET[sucursal]." AND
			i.fechainiciodia < CURDATE()";
		
		}else if($_GET[modulo]=="evaluacion"){
			$s = "SELECT 1 AS sel, e.id AS caja, DATE_FORMAT(e.fechaevaluacion,'%d/%m/%Y') AS fecha, 0 AS usuariocaja FROM evaluacionmercancia e
			WHERE e.estado = 'GUARDADO' AND e.sucursal = ".$_GET[sucursal]." AND e.fechaevaluacion < CURDATE()";
		}
		$arr = array();
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->modulo = $_GET[modulo];
				$f->sucursal = $_GET[sucursal];
				$arr[] = $f;
			}
			
			echo str_replace('null','""',json_encode($arr));
		}else{
			echo "noencontro";
		}
	
	}else if($_GET[accion]==8){
		if($_GET[modulo]=="cierrecaja"){
			if($_GET[trae]=="s"){
				$row = split(":",$_GET[datos]);
				for($i=0;$i<count($row);$i++){
					$t = split(",",$row[$i]);
					$s = "INSERT INTO cierrecaja SET 
					iniciocaja = ".$t[4].", fechacierre = '".cambiaf_a_mysql($t[0])."', usuariocaja = ".$t[1].", efectivo = 0,
					tarjeta = 0, transferencia = 0, cheque = 0, retiros = 0,
					guiacredito = 0, guiacancelada = 0, facturacancelada = 0, 
					notacredito = 0, tipocierre = 'definitivo', sucursal = ".$t[2].",
					usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp(),
					difefectivo = 0, diftarjeta = 0, diftransferencia = 0, difcheque = 0,
					difretiros = 0, refectivo = 0, rtarjeta = 0, rtransferencia = 0, rcheque = 0";
					mysql_query($s,$l) or die($s);
				}
			}else{
				$t = split(",",$_GET[datos]);
				$s = "INSERT INTO cierrecaja SET 
				iniciocaja = ".$t[4].", fechacierre = '".cambiaf_a_mysql($t[0])."', usuariocaja = ".$t[1].", efectivo = 0,
				tarjeta = 0, transferencia = 0, cheque = 0, retiros = 0,
				guiacredito = 0, guiacancelada = 0, facturacancelada = 0, 
				notacredito = 0, tipocierre = 'definitivo', sucursal = ".$t[2].",
				usuario = '".$_SESSION[NOMBREUSUARIO]."', fecha = current_timestamp(),
				difefectivo = 0, diftarjeta = 0, diftransferencia = 0, difcheque = 0,
				difretiros = 0, refectivo = 0, rtarjeta = 0, rtransferencia = 0, rcheque = 0";
				mysql_query($s,$l) or die($s);
			}
			echo "ok";
		}else if($_GET[modulo]=="cierreprincipal"){
			if($_GET[trae]=="s"){			
				$row = split(":",$_GET[datos]);
				for($i=0;$i<count($row);$i++){
					$t = split(",",$row[$i]);
					$s = "SELECT folio, estado FROM cierreprincipal WHERE sucursal = ".$t[2]." AND fecha = '".cambiaf_a_mysql($t[0])."'";
					$r = mysql_query($s,$l) or die($s); 
					if(mysql_num_rows($r)>0){
						$f = mysql_fetch_object($r);					
						if($f->estado == "GUARDADO"){
							$s = "UPDATE cierreprincipal SET estado = 'CERRADA' WHERE folio = ".$f->folio." AND sucursal = ".$t[2]."";
							mysql_query($s,$l) or die($s);
						}	
					}else{
						$s = "INSERT INTO cierreprincipal SET 
						folio = obtenerFolio('cierreprincipal',".$t[2]."),
						fechacierre = '".cambiaf_a_mysql($t[0])."',
						sucursal = ".$t[2].",
						estado = 'CERRADA',
						idusuario = ".$_SESSION[IDUSUARIO].",
						fecha = CURRENT_TIMESTAMP";
						mysql_query($s,$l) or die($s);
					}
				}
			}else{
				$t = split(",",$_GET[datos]);
				$s = "SELECT folio, estado FROM cierreprincipal WHERE sucursal = ".$t[2]." AND fecha = '".cambiaf_a_mysql($t[0])."'";
				$r = mysql_query($s,$l) or die($s); 
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);					
					if($f->estado == "GUARDADO"){
						$s = "UPDATE cierreprincipal SET estado = 'CERRADA' WHERE folio = ".$f->folio." AND sucursal = ".$t[2]."";
						mysql_query($s,$l) or die($s);
					}	
				}else{
					$s = "INSERT INTO cierreprincipal SET 
					folio = obtenerFolio('cierreprincipal',".$t[2]."),
					fechacierre = '".cambiaf_a_mysql($t[0])."',
					sucursal = ".$t[2].",
					estado = 'CERRADA',
					idusuario = ".$_SESSION[IDUSUARIO].",
					fecha = CURRENT_TIMESTAMP";
					mysql_query($s,$l) or die($s);
				}
			}
			echo "ok";
		}else if($_GET[modulo]=="cierredia"){
			if($_GET[trae]=="s"){
				$row = split(":",$_GET[datos]);
				for($i=0;$i<count($row);$i++){
					$t = split(",",$row[$i]);
					$s = "SELECT MAX(id) AS iniciodia FROM iniciodia WHERE sucursal = ".$t[2]." AND fechainiciodia='".cambiaf_a_mysql($t[0])."'";
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					
					$s = "INSERT INTO cierredia SET 
					fechacierredia = '".cambiaf_a_mysql($t[0])."',
					iniciodia = ".$f->iniciodia.",
					idusuario = ".$_SESSION[IDUSUARIO].",
					sucursal = ".$t[2].",
					usuario = '".$_SESSION[NOMBREUSUARIO]."',
					fecha = CURRENT_TIMESTAMP";
					mysql_query($s,$l) or die($s);
				}
			}else{
				$t = split(",",$_GET[datos]);
				$s = "SELECT MAX(id) AS iniciodia FROM iniciodia WHERE sucursal = ".$t[2]." AND fechainiciodia='".cambiaf_a_mysql($t[0])."'";
				$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
				
				$s = "INSERT INTO cierredia SET 
				fechacierredia = '".cambiaf_a_mysql($t[0])."',
				iniciodia = ".$f->iniciodia.",
				idusuario = ".$_SESSION[IDUSUARIO].",
				sucursal = ".$t[2].",
				usuario = '".$_SESSION[NOMBREUSUARIO]."',
				fecha = CURRENT_TIMESTAMP";
				mysql_query($s,$l) or die($s);				
			}
			echo "ok";
		}else if($_GET[modulo]=="evaluacion"){
			if($_GET[trae]=="s"){
				$row = split(":",$_GET[datos]);
				for($i=0;$i<count($row);$i++){
					$t = split(",",$row[$i]);
					$s = "UPDATE evaluacionmercancia SET estado = 'CANCELADA' WHERE id = ".$t[4];
					mysql_query($s,$l) or die($s);
				}
			}else{
				$t = split(",",$_GET[datos]);
				
				$s = "UPDATE evaluacionmercancia SET estado = 'CANCELADA' WHERE id = ".$t[4];
				mysql_query($s,$l) or die($s);				
			}
			echo "ok";
		}
	}else if($_GET[accion]==9){
		$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS nombre FROM catalogoempleado WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND id = ".$_GET[empleado];
		$r = mysql_query($s,$l) or die($s);
		
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->nombre = cambio_texto($f->nombre);
			
			echo "(".str_replace('null','""',json_encode($f)).")";		
		}else{
			echo "noencontro";
		}
	}else if($_GET[accion]==10){
		$s = "SELECT efectivo, tarjeta, cheque, transferencia, refectivo, rtarjeta, rcheque, rtransferencia FROM cierrecaja
		WHERE usuariocaja = ".$_GET[empleado]." AND fechacierre = '".cambiaf_a_mysql($_GET[fecha])."' AND sucursal = ".$_SESSION[IDSUCURSAL];
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			echo "(".str_replace('null','""',json_encode($f)).")";		
		}else{
			echo "noencontro";
		}
	}
?>
