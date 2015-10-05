<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion] == 1){		
		//$row = split(",",$_GET[arr]);
		if($_GET[tipo] == "guardar"){
			if($_GET[folio] == "modificar"){				
				$s = "UPDATE cierreprincipal SET fechacierre=CURDATE(), sucursal=".$_SESSION[IDSUCURSAL].",
				estado='GUARDADO', idusuario=".$_SESSION[IDUSUARIO].",
				fecha=CURRENT_TIMESTAMP() 
				WHERE folio=".$_GET[cierre]." AND sucursal=".$_SESSION[IDSUCURSAL]."";
				mysql_query($s,$l) or die($s);
				echo "ok,".$_GET[tipo].",".$_GET[folio].",".$_GET[cierre];
			}else{
				$s = "INSERT INTO cierreprincipal SET folio = obtenerFolio('cierreprincipal',$_SESSION[IDSUCURSAL]),
				fechacierre=CURDATE(), sucursal=".$_SESSION[IDSUCURSAL].",
				estado='GUARDADO', idusuario=".$_SESSION[IDUSUARIO].",
				fecha=CURRENT_TIMESTAMP()";				
				mysql_query($s,$l) or die($s);
				$folio = mysql_insert_id();
				
				$s = "SELECT folio FROM cierreprincipal WHERE id = ".$folio."";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				
				echo "ok,".$_GET[tipo].",".$_GET[folio].",".$f->folio;
			}
		}else if($_GET[tipo] == "cierre"){
			if($_GET[folio] == "modificar"){
				$s = "UPDATE cierreprincipal SET fechacierre=CURDATE(), sucursal=".$_SESSION[IDSUCURSAL].",
				estado='CERRADA', idusuario=".$_SESSION[IDUSUARIO].",
				fecha=CURRENT_TIMESTAMP() 
				WHERE folio=".$_GET[cierre]." AND sucursal=".$_SESSION[IDSUCURSAL]."";
				mysql_query($s,$l) or die($s);
				echo "ok,".$_GET[tipo].",".$_GET[folio].",".$_GET[cierre];
			}else{				
				$s = "INSERT INTO cierreprincipal SET folio = obtenerFolio('cierreprincipal',$_SESSION[IDSUCURSAL]),
				fechacierre=CURDATE(), sucursal=".$_SESSION[IDSUCURSAL].",
				estado='CERRADA', idusuario=".$_SESSION[IDUSUARIO].",
				fecha=CURRENT_TIMESTAMP()";
				mysql_query($s,$l) or die($s);
				$folio = mysql_insert_id();
				
				$s = "SELECT folio FROM cierreprincipal WHERE id = ".$folio."";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				
				echo "ok,".$_GET[tipo].",".$_GET[folio].",".$f->folio;
			}
		}	
	}else if($_GET[accion] == 2){
		$s = "SELECT folio,DATE_FORMAT(fechacierre,'%d/%m/%Y') AS fechacierre, fechacierre as fechac,
		sucursal, estado, idusuario, cast(fecha as date) as fecha FROM cierreprincipal
		WHERE folio =".$_GET[cierre]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$principal = str_replace('null','""',json_encode($f));
		$fecha = $f->fechac;
		/*$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta,
			IFNULL(SUM(transferencia),0) AS transferencia, IFNULL(SUM(cheque),0) AS cheque,
			IFNULL(SUM(notacredito),0) AS notacredito FROM abonodecliente
			WHERE fecha='".$fecha."' AND idsucursal=".$_SESSION[IDSUCURSAL];*/
			
			$s = "SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
			IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
			IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
			INNER JOIN solicitudguiasempresariales s ON f.guia = s.factura
			WHERE f.procedencia = 'F' AND f.fecha='".$fecha."' AND f.sucursal=".$_SESSION[IDSUCURSAL]." 
			AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00')";
			$r = mysql_query($s,$l) or die($s); $fact = mysql_fetch_object($r);
			
			$fact->factentregado = $fact->efectivo + $fact->tarjeta + $fact->transferencia + $fact->cheque + $fact->notacredito;
			$facturado = str_replace('null','""',json_encode($fact));
			
			$e = mysql_query("SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
			IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
			IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
			INNER JOIN guiasempresariales g ON f.guia = g.id
			WHERE f.procedencia ='G' AND f.tipo='E' AND f.fecha='".$fecha."' AND f.sucursal=".$_SESSION[IDSUCURSAL]." 
			AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00') AND g.tipoguia = 'CONSIGNACION'",$l);
			$emp = mysql_fetch_object($e);
			
			$totalemp = $emp->efectivo + $emp->tarjeta + $emp->transferencia + $emp->cheque;
			
			$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta,
			IFNULL(SUM(transferencia),0) AS transferencia, IFNULL(SUM(cheque),0) AS cheque,
			IFNULL(SUM(notacredito),0) AS notacredito FROM formapago";			
			
			$criterioventanilla = " WHERE procedencia ='G' AND tipo='V' AND fecha='".$fecha."'
			AND sucursal=".$_SESSION[IDSUCURSAL]." AND (fechacancelacion IS NULL OR fechacancelacion='0000-00-00')"; 
			
			//die($s.$criterioventanilla);			
			
			$r = mysql_query($s.$criterioventanilla,$l) or die($s);
			$v = mysql_fetch_object($r);	
			$v->entregarventanilla = ($v->efectivo + $emp->efectivo) + ($v->tarjeta + $emp->tarjeta) + ($v->transferencia + $emp->transferencia) + ($v->cheque + $emp->cheque) + $v->notacredito;
			
			$v->efectivo = $v->efectivo + $emp->efectivo;
			$v->tarjeta = $v->tarjeta + $emp->tarjeta;
			$v->transferencia = $v->transferencia + $emp->transferencia;
			$v->cheque = $v->cheque + $emp->cheque;
			
			$ventanilla = str_replace('null','""',json_encode($v));
			
			$criterioead = " WHERE procedencia='M' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$r = mysql_query($s.$criterioead,$l) or die($s);
			$f = mysql_fetch_object($r);
			$f->entregaread = $f->efectivo + $f->tarjeta + $f->transferencia + $f->cheque + $f->notacredito;	
			
			$liquidacion = str_replace('null','""',json_encode($f));
			
			$criterioead = " WHERE procedencia='C' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			
			$r = mysql_query($s.$criterioead,$l) or die($s);
			$c = mysql_fetch_object($r);	
			$c->entregarcobranza = $c->efectivo + $c->tarjeta + $c->transferencia + $c->cheque + $c->notacredito;
			$cobranza = str_replace('null','""',json_encode($c));
			
			
			$criterioocurre = " WHERE procedencia='O' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
			$co= mysql_fetch_object($d);
			
			$co->entregarocurre = $co->efectivo + $co->tarjeta + $co->transferencia + $co->cheque + $co->notacredito;	
			$ocurre = str_replace('null','""',json_encode($co));
			
			
			$criterioocurre = " WHERE procedencia='A' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
			$abo= mysql_fetch_object($d);
			
			$abo->entregarabono = $abo->efectivo + $abo->tarjeta + $abo->transferencia + $abo->cheque + $abo->notacredito;
			$abono = str_replace('null','""',json_encode($abo));
			
			$s = "SELECT(SELECT COUNT(*) FROM guiasventanilla
			WHERE fecha = '".$fecha."' AND estado='CANCELADO' 
			AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."') AS cancelada,
			(SELECT COUNT(*) FROM guiasventanilla
			WHERE fecha = '".$fecha."' AND condicionpago='1'
			AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."')  AS credito,
			(SELECT COUNT(*) FROM facturacion
			WHERE fecha = '".$fecha."' AND facturaestado='CANCELADO'
			AND idsucursal='".$_SESSION[IDSUCURSAL]."') AS fcanceladas";
			$r = mysql_query($s,$l) or die($s); $o = mysql_fetch_object($r);
			$otros = str_replace('null','""',json_encode($o));
			
			$s = "SELECT ifnull(efectivo,0) AS efectivo, ifnull(tarjeta,0) AS tarjeta, ifnull(transferencia,0) AS transferencia, 
			ifnull(cheque,0) AS cheque FROM cierrecaja
			WHERE fechacierre = '".$fecha."' AND tipocierre = 'definitivo' AND sucursal = ".$_SESSION[IDSUCURSAL];
			$r = mysql_query($s,$l) or die($s); $te = mysql_fetch_object($r);
			$te->efectivo = ((empty($te->efectivo))?0:$te->efectivo);
			$te->tarjeta = ((empty($te->tarjeta))?0:$te->tarjeta);
			$te->transferencia = ((empty($te->transferencia))?0:$te->transferencia);
			$te->cheque = ((empty($te->efectivo))?0:$te->cheque);
			$te->entregado = $te->efectivo + $te->tarjeta + $te->transferencia + $te->cheque;
			
			$entregado = str_replace('null','""',json_encode($te));
			
			echo "({principal:$principal,ventanilla:$ventanilla,ocurre:$ocurre,
					abono:$abono,liquidacion:$liquidacion,cobranza:$cobranza,
					otros:$otros,entregado:$entregado,facturado:$facturado})";
		
		
	}else if($_GET[accion] == 3){
		$s = "SELECT obtenerFolio('cierreprincipal',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$fecha = date('d/m/Y');
		
		$usuario = "";
		
		$s = "select usuariocaja from iniciocaja where sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciocaja = curdate()";
		$r = mysql_query($s,$l) or die($s);
		while($t = mysql_fetch_object($r)){			
			$s = "select tipocierre from cierrecaja
			where sucursal = ".$_SESSION[IDSUCURSAL]." AND fechacierre = curdate() AND tipocierre = 'definitivo'
			and usuariocaja = ".$t->usuariocaja."";
			$rr = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rr)==0){
				$usuario .= $t->usuariocaja.",";
			}
		}
		
		if(!empty($usuario)){
			$empleados = "";
			$usuario = substr($usuario,0,strlen($usuario)-1);
			$row = split(",",$usuario);
			for($i=0;$i<count($row);$i++){
				$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado 
				FROM catalogoempleado WHERE id = ".$row[$i]."";
				$r = mysql_query($s,$l) or die($s);
				$em= mysql_fetch_object($r);
				$empleados .= $em->empleado.",";
			}
			
			$usuario = ((!empty($empleados)) ? utf8_encode(substr($empleados,0,strlen($empleados)-1)) : $empleados);
		}
		
		$s = "SELECT * FROM cierrecaja 
		WHERE tipocierre='definitivo' AND fechacierre=CURDATE() AND sucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$cierre = "SI";
		}else{
			$cierre = "NO";
		}
		echo $f->folio."%".$fecha."%".$cierre."%".trim(cambio_texto($usuario));
		
	}else if($_GET[accion]==4){
		$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado FROM catalogoempleado 
		WHERE id=".$_GET[empleado]." AND sucursal=".$_SESSION[IDSUCURSAL];
		$r = mysql_query($s,$l) or die($s);
		$em= mysql_fetch_object($r);
		$empleado = "";
		$ventanilla = "";
		$ocurre = "";
		$abono = "";
		$liquidacion = "";
		$cobranza = "";
		
		if(mysql_num_rows($r)>0){
			$em->empleado = cambio_texto($em->empleado);
			
			$empleado = str_replace('null','""',json_encode($em));	
			
			$s = "SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
			IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
			IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
			INNER JOIN solicitudguiasempresariales s ON f.guia = s.factura
			WHERE f.procedencia = 'F' AND f.fecha='".cambiaf_a_mysql($_GET[fecha])."' AND f.sucursal=".$_SESSION[IDSUCURSAL]." 
			AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00') AND f.usuario=".$_GET[empleado]."";
			$r = mysql_query($s,$l) or die($s); $fact = mysql_fetch_object($r);
			
			$fact->factentregado = $fact->efectivo + $fact->tarjeta + $fact->transferencia + $fact->cheque + $fact->notacredito;
			$facturado = str_replace('null','""',json_encode($fact));
			
			$e = mysql_query("SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
			IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
			IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
			INNER JOIN guiasempresariales g ON f.guia = g.id
			WHERE f.procedencia ='G' AND f.tipo='E' AND f.sucursal=".$_SESSION[IDSUCURSAL]." 			
			".((!empty($_GET[cambiafecha]))? " AND f.fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND f.fecha = CURDATE()")."
			AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00') AND g.tipoguia = 'CONSIGNACION' AND f.usuario=".$_GET[empleado]."",$l);
			$emp = mysql_fetch_object($e);
			
			$totalemp = $emp->efectivo + $emp->tarjeta + $emp->transferencia + $emp->cheque;
			
			$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta,
			IFNULL(SUM(transferencia),0) AS transferencia, IFNULL(SUM(cheque),0) AS cheque,
			IFNULL(SUM(notacredito),0) AS notacredito FROM formapago";
			
			$criterioventanilla = " WHERE procedencia ='G' AND tipo='V' 
			".((!empty($_GET[cambiafecha]))? " AND fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fecha = CURRENT_DATE")."
			AND sucursal=".$_SESSION[IDSUCURSAL]." AND usuario=".$_GET[empleado]." AND (fechacancelacion IS NULL OR fechacancelacion = '0000-00-00')"; 			
			$r = mysql_query($s.$criterioventanilla,$l) or die($s);
			$v = mysql_fetch_object($r);	
			
			$v->entregarventanilla = ($v->efectivo + $emp->efectivo) + ($v->tarjeta + $emp->tarjeta) + ($v->transferencia + $emp->transferencia) + ($v->cheque + $emp->cheque) + $v->notacredito;
			
			$v->efectivo = $v->efectivo + $emp->efectivo;
			$v->tarjeta = $v->tarjeta + $emp->tarjeta;
			$v->transferencia = $v->transferencia + $emp->transferencia;
			$v->cheque = $v->cheque + $emp->cheque;
			
			$ventanilla = str_replace('null','""',json_encode($v));
			
			$criterioead = " WHERE procedencia='M' 
			".((!empty($_GET[cambiafecha]))? " AND fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fecha = CURRENT_DATE")." 
			AND usuario=".$_GET[empleado]." AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$r = mysql_query($s.$criterioead,$l) or die($s);
			$f = mysql_fetch_object($r);
			$f->entregaread = $f->efectivo + $f->tarjeta + $f->transferencia + $f->cheque + $f->notacredito;	
			
			$liquidacion = str_replace('null','""',json_encode($f));
			
			$criterioead = " WHERE procedencia='C' ".((!empty($_GET[cambiafecha]))? " AND fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fecha = CURRENT_DATE")."
			AND usuario=".$_GET[empleado]." AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$r = mysql_query($s.$criterioead,$l) or die($s);
			$c = mysql_fetch_object($r);	
			$c->entregarcobranza = $c->efectivo + $c->tarjeta + $c->transferencia + $c->cheque + $c->notacredito;
			$cobranza = str_replace('null','""',json_encode($c));
			
			
			$criterioocurre = " WHERE procedencia='O' ".((!empty($_GET[cambiafecha]))? " AND fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fecha = CURRENT_DATE")."
			AND usuario=".$_GET[empleado]." AND sucursal=".$_SESSION[IDSUCURSAL];
			$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
			$co= mysql_fetch_object($d);
			
			$co->entregarocurre = $co->efectivo + $co->tarjeta + $co->transferencia + $co->cheque + $co->notacredito;	
			$ocurre = str_replace('null','""',json_encode($co));
			
			$criterioocurre = " WHERE procedencia='A' 
			".((!empty($_GET[cambiafecha]))? " AND fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" AND fecha = CURRENT_DATE")."
			AND usuario=".$_GET[empleado]." AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
			$abo= mysql_fetch_object($d);
			
			$abo->entregarabono = $abo->efectivo + $abo->tarjeta + $abo->transferencia + $abo->cheque + $abo->notacredito;
			$abono = str_replace('null','""',json_encode($abo));
			
			$s = "SELECT(SELECT COUNT(*) FROM guiasventanilla
			WHERE ".((!empty($_GET[cambiafecha]))? " fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" fecha = CURDATE()")."
			AND estado='CANCELADO' AND idusuario='".$_GET[empleado]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."') AS cancelada,
			(SELECT COUNT(*) FROM guiasventanilla
			WHERE ".((!empty($_GET[cambiafecha]))? " fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" fecha = CURDATE()")."
			AND condicionpago='1' AND idusuario='".$_GET[empleado]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."')  AS credito,
			(SELECT COUNT(*) FROM facturacion
			WHERE ".((!empty($_GET[cambiafecha]))? " fecha = '".cambiaf_a_mysql($_GET[fecha])."'" :" fecha = CURDATE()")." 
			AND facturaestado='CANCELADO' AND idusuario='".$_GET[empleado]."' AND idsucursal='".$_SESSION[IDSUCURSAL]."') AS fcanceladas";
			$r = mysql_query($s,$l) or die($s); $o = mysql_fetch_object($r);
			$otros = str_replace('null','""',json_encode($o));
			
			$s = "SELECT ifnull(efectivo,0) AS efectivo, ifnull(tarjeta,0) AS tarjeta, ifnull(transferencia,0) AS transferencia, 
			ifnull(cheque,0) AS cheque FROM cierrecaja
			WHERE ".((!empty($_GET[cambiafecha]))? " fechacierre = '".cambiaf_a_mysql($_GET[fecha])."'" :" fechacierre = CURDATE()")." 
			AND usuariocaja = ".$_GET[empleado]." AND tipocierre = 'definitivo' AND sucursal = ".$_SESSION[IDSUCURSAL];
			$r = mysql_query($s,$l) or die($s); $te = mysql_fetch_object($r);
			$te->efectivo = ((empty($te->efectivo))?0:$te->efectivo);
			$te->tarjeta = ((empty($te->tarjeta))?0:$te->tarjeta);
			$te->transferencia = ((empty($te->transferencia))?0:$te->transferencia);
			$te->cheque = ((empty($te->efectivo))?0:$te->cheque);
			$te->entregado = $te->efectivo + $te->tarjeta + $te->transferencia + $te->cheque;
			
			$entregado = str_replace('null','""',json_encode($te));
			
			echo "({empleado:$empleado,ventanilla:$ventanilla,ocurre:$ocurre,
					abono:$abono,liquidacion:$liquidacion,cobranza:$cobranza,
					otros:$otros,entregado:$entregado,facturado:$facturado})";
		}else{
			echo "no encontro";
		}	
				
	}else if($_GET[accion]==5){
		$usuario = "";
		
		$s = "select usuariocaja from iniciocaja where sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciocaja = '".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		while($t = mysql_fetch_object($r)){			
			$s = "select tipocierre from cierrecaja
			where sucursal = ".$_SESSION[IDSUCURSAL]." AND fechacierre = '".cambiaf_a_mysql($_GET[fecha])."' AND tipocierre = 'definitivo'
			and usuariocaja = ".$t->usuariocaja."";
			$rr = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rr)==0){
				$usuario .= $t->usuariocaja.",";
			}
		}
		
		if(!empty($usuario)){
			$empleados = "";
			$usuario = substr($usuario,0,strlen($usuario)-1);
			$row = split(",",$usuario);
			for($i=0;$i<count($row);$i++){
				$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado 
				FROM catalogoempleado WHERE id = ".$row[$i]."";
				$r = mysql_query($s,$l) or die($s);
				$em= mysql_fetch_object($r);
				$empleados .= $em->empleado.",";
			}
			
			$usuario = ((!empty($empleados)) ? utf8_encode(substr($empleados,0,strlen($empleados)-1)) : $empleados);
		}
		
	
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
		
		$s = "SELECT * FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]." 
		AND fechainiciodia = '".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)==0){
			die("noiniciodia");
		}
	
		$s = "SELECT folio, estado, fechacierre, date_format(fechacierre,'%d/%m/%Y') as fecha FROM cierreprincipal
		WHERE fechacierre = '".cambiaf_a_mysql($_GET[fecha])."' AND sucursal = ".$_SESSION[IDSUCURSAL]."";		
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);		
		
		if($f->estado=="CERRADA"){
			die("yacerro");
		}
		
		if($f->estado=="GUARDADO"){
			$principal = str_replace('null','""',json_encode($f));			
			$fecha = cambiaf_a_mysql($_GET[fecha]);
			
			$s = "SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
			IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
			IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
			INNER JOIN solicitudguiasempresariales s ON f.guia = s.factura
			WHERE f.procedencia = 'F' AND f.fecha='".$fecha."' AND f.sucursal=".$_SESSION[IDSUCURSAL]." 
			AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00')";
			$r = mysql_query($s,$l) or die($s); $fact = mysql_fetch_object($r);
			
			$fact->factentregado = $fact->efectivo + $fact->tarjeta + $fact->transferencia + $fact->cheque + $fact->notacredito;
			$facturado = str_replace('null','""',json_encode($fact));
			
			$e = mysql_query("SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
			IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
			IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
			INNER JOIN guiasempresariales g ON f.guia = g.id
			WHERE f.procedencia ='G' AND f.tipo='E' AND f.sucursal=".$_SESSION[IDSUCURSAL]."
			AND f.fecha = '".$fecha."'
			AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00') AND g.tipoguia = 'CONSIGNACION'",$l);
			$emp = mysql_fetch_object($e);
			
			$totalemp = $emp->efectivo + $emp->tarjeta + $emp->transferencia + $emp->cheque;
			
			$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta,
			IFNULL(SUM(transferencia),0) AS transferencia, IFNULL(SUM(cheque),0) AS cheque,
			IFNULL(SUM(notacredito),0) AS notacredito FROM formapago";
			
			$criterioventanilla = " WHERE procedencia ='G' AND tipo='V' AND fecha='".$fecha."'
			AND sucursal=".$_SESSION[IDSUCURSAL]." AND (fechacancelacion IS NULL OR fechacancelacion = '0000-00-00')"; 
						
			$r = mysql_query($s.$criterioventanilla,$l) or die($s);
			$v = mysql_fetch_object($r);	
			$v->entregarventanilla = ($v->efectivo + $emp->efectivo) + ($v->tarjeta + $emp->tarjeta) + ($v->transferencia + $emp->transferencia) + ($v->cheque + $emp->cheque) + $v->notacredito;
			
			$v->efectivo = $v->efectivo + $emp->efectivo;
			$v->tarjeta = $v->tarjeta + $emp->tarjeta;
			$v->transferencia = $v->transferencia + $emp->transferencia;
			$v->cheque = $v->cheque + $emp->cheque;
			$ventanilla = str_replace('null','""',json_encode($v));
			
			$criterioead = " WHERE procedencia='M' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$r = mysql_query($s.$criterioead,$l) or die($s);
			$f = mysql_fetch_object($r);
			$f->entregaread = $f->efectivo + $f->tarjeta + $f->transferencia + $f->cheque + $f->notacredito;	
			
			$liquidacion = str_replace('null','""',json_encode($f));
			
			$criterioead = " WHERE procedencia='C' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			
			$r = mysql_query($s.$criterioead,$l) or die($s);
			$c = mysql_fetch_object($r);	
			$c->entregarcobranza = $c->efectivo + $c->tarjeta + $c->transferencia + $c->cheque + $c->notacredito;
			$cobranza = str_replace('null','""',json_encode($c));
			
			
			$criterioocurre = " WHERE procedencia='O' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
			$co= mysql_fetch_object($d);
			
			$co->entregarocurre = $co->efectivo + $co->tarjeta + $co->transferencia + $co->cheque + $co->notacredito;	
			$ocurre = str_replace('null','""',json_encode($co));
			
			$criterioocurre = " WHERE procedencia='A' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
			$abo= mysql_fetch_object($d);
			
			$abo->entregarabono = $abo->efectivo + $abo->tarjeta + $abo->transferencia + $abo->cheque + $abo->notacredito;
			$abono = str_replace('null','""',json_encode($abo));
			
			$s = "SELECT(SELECT COUNT(*) FROM guiasventanilla
			WHERE fecha = '".$fecha."' AND estado='CANCELADO' 
			AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."') AS cancelada,
			(SELECT COUNT(*) FROM guiasventanilla
			WHERE fecha = '".$fecha."' AND condicionpago='1'
			AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."')  AS credito,
			(SELECT COUNT(*) FROM facturacion
			WHERE fecha = '".$fecha."' AND facturaestado='CANCELADO'
			AND idsucursal='".$_SESSION[IDSUCURSAL]."') AS fcanceladas";
			$r = mysql_query($s,$l) or die($s); $o = mysql_fetch_object($r);
			$otros = str_replace('null','""',json_encode($o));
			
			$s = "SELECT cobrador, diferencia FROM liquidacioncobranza 
			WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechaliquidacion = '".$fecha."' AND diferencia > 0";
			$rr= mysql_query($s,$l) or die($s);
			$empleadoscobranza = "";
			if(mysql_num_rows($rr)>0){
				while($fr = mysql_fetch_object($rr)){
					$empleadoscobranza .= $fr->cobrador.",".$fr->diferencia.":";
				}
				
				$empleadoscobranza = substr($empleadoscobranza,0,strlen($empleadoscobranza)-1);
			}
			
			$s = "SELECT r.conductor1, l.diferencia FROM repartomercanciaead r
			INNER JOIN liquidacionead l ON r.folio = l.idreparto AND r.sucursal = l.sucursal
			WHERE l.sucursal = ".$_SESSION[IDSUCURSAL]." AND r.fecha = '".$fecha."' AND l.diferencia > 0";
			$rr= mysql_query($s,$l) or die($s);
			$empleadosead = "";
			if(mysql_num_rows($rr)>0){
				while($fr = mysql_fetch_object($rr)){
					$empleadosead .= $fr->conductor1.",".$fr->diferencia.":";			
				}
				
				$empleadosead = substr($empleadosead,0,strlen($empleadosead)-1);
			}
			
			$s = "SELECT usuariocaja, (difefectivo + diftarjeta + difcheque + diftransferencia + difretiros) AS diferencia 
			FROM cierrecaja WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechacierre = '".$fecha."'";
			
			$rr= mysql_query($s,$l) or die($s);
			$empleadosventanilla = "";
			if(mysql_num_rows($rr)>0){
				while($fr = mysql_fetch_object($rr)){
					if($fr->diferencia > 0){
						$empleadosventanilla .= $fr->usuariocaja.",".$fr->diferencia.":";
					}
				}
				$empleadosventanilla = substr($empleadosventanilla,0,strlen($empleadosventanilla)-1);				
			}
			
			/*$s = "SELECT * FROM iniciocaja WHERE  ";
			
			$s = "SELECT * FROM cierrecaja 
			WHERE tipocierre='definitivo' AND fechacierre = '".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]."";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$cierre = "SI";
			}else{
				$cierre = "NO";
			}*/
			$cierre = "";
			echo "({principal:$principal,ventanilla:$ventanilla,ocurre:$ocurre,
					abono:$abono,liquidacion:$liquidacion,cobranza:$cobranza,
					otros:$otros,empleadoscobranza:'$empleadoscobranza',empleadosead:'$empleadosead',
					empleadosventanilla:'$empleadosventanilla',cierre:'$cierre',usuarios:'$usuario',facturado:$facturado})";
		}else{
			$principal = "";
			$fecha = cambiaf_a_mysql($_GET[fecha]);
			
			$s = "SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
			IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
			IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
			INNER JOIN solicitudguiasempresariales s ON f.guia = s.factura
			WHERE f.procedencia = 'F' AND f.fecha='".$fecha."' AND f.sucursal=".$_SESSION[IDSUCURSAL]." 
			AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00')";
			$r = mysql_query($s,$l) or die($s); $fact = mysql_fetch_object($r);
			
			$fact->factentregado = $fact->efectivo + $fact->tarjeta + $fact->transferencia + $fact->cheque + $fact->notacredito;
			$facturado = str_replace('null','""',json_encode($fact));
			
			$e = mysql_query("SELECT IFNULL(SUM(f.efectivo),0) AS efectivo, IFNULL(SUM(f.tarjeta),0) AS tarjeta,
			IFNULL(SUM(f.transferencia),0) AS transferencia, IFNULL(SUM(f.cheque),0) AS cheque,
			IFNULL(SUM(f.notacredito),0) AS notacredito FROM formapago f
			INNER JOIN guiasempresariales g ON f.guia = g.id
			WHERE f.procedencia ='G' AND f.tipo='E' AND f.sucursal=".$_SESSION[IDSUCURSAL]."
			AND f.fecha = '".$fecha."'
			AND (f.fechacancelacion IS NULL OR f.fechacancelacion='0000-00-00') AND g.tipoguia = 'CONSIGNACION'",$l);
			$emp = mysql_fetch_object($e);
			
			$totalemp = $emp->efectivo + $emp->tarjeta + $emp->transferencia + $emp->cheque;
			
			$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta,
			IFNULL(SUM(transferencia),0) AS transferencia, IFNULL(SUM(cheque),0) AS cheque,
			IFNULL(SUM(notacredito),0) AS notacredito FROM formapago";
			
			$criterioventanilla = " WHERE procedencia ='G' AND tipo='V' AND fecha='".$fecha."'
			AND sucursal=".$_SESSION[IDSUCURSAL]." AND (fechacancelacion IS NULL OR fechacancelacion = '0000-00-00')"; 
						
			$r = mysql_query($s.$criterioventanilla,$l) or die($s);
			$v = mysql_fetch_object($r);	
			$v->entregarventanilla = ($v->efectivo + $emp->efectivo) + ($v->tarjeta + $emp->tarjeta) + ($v->transferencia + $emp->transferencia) + ($v->cheque + $emp->cheque) + $v->notacredito;
			
			$v->efectivo = $v->efectivo + $emp->efectivo;
			$v->tarjeta = $v->tarjeta + $emp->tarjeta;
			$v->transferencia = $v->transferencia + $emp->transferencia;
			$v->cheque = $v->cheque + $emp->cheque;
			$ventanilla = str_replace('null','""',json_encode($v));
			
			$criterioead = " WHERE procedencia='M' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$r = mysql_query($s.$criterioead,$l) or die($s);
			$f = mysql_fetch_object($r);
			$f->entregaread = $f->efectivo + $f->tarjeta + $f->transferencia + $f->cheque + $f->notacredito;	
			
			$liquidacion = str_replace('null','""',json_encode($f));
			
			$criterioead = " WHERE procedencia='C' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			
			$r = mysql_query($s.$criterioead,$l) or die($s);
			$c = mysql_fetch_object($r);	
			$c->entregarcobranza = $c->efectivo + $c->tarjeta + $c->transferencia + $c->cheque + $c->notacredito;
			$cobranza = str_replace('null','""',json_encode($c));
			
			
			$criterioocurre = " WHERE procedencia='O' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
			$co= mysql_fetch_object($d);
			
			$co->entregarocurre = $co->efectivo + $co->tarjeta + $co->transferencia + $co->cheque + $co->notacredito;	
			$ocurre = str_replace('null','""',json_encode($co));
			
			$criterioocurre = " WHERE procedencia='A' AND fecha='".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]; 
			$d = mysql_query($s.$criterioocurre,$l) or die($s.$criterioocurre);
			$abo= mysql_fetch_object($d);
			
			$abo->entregarabono = $abo->efectivo + $abo->tarjeta + $abo->transferencia + $abo->cheque + $abo->notacredito;
			$abono = str_replace('null','""',json_encode($abo));
			
			$s = "SELECT(SELECT COUNT(*) FROM guiasventanilla
			WHERE fecha = '".$fecha."' AND estado='CANCELADO' 
			AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."') AS cancelada,
			(SELECT COUNT(*) FROM guiasventanilla
			WHERE fecha = '".$fecha."' AND condicionpago='1'
			AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."')  AS credito,
			(SELECT COUNT(*) FROM facturacion
			WHERE fecha = '".$fecha."' AND facturaestado='CANCELADO'
			AND idsucursal='".$_SESSION[IDSUCURSAL]."') AS fcanceladas";
			$r = mysql_query($s,$l) or die($s); $o = mysql_fetch_object($r);
			$otros = str_replace('null','""',json_encode($o));
			
			
			$s = "SELECT cobrador, diferencia FROM liquidacioncobranza 
			WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechaliquidacion = '".$fecha."' AND diferencia > 0";
			$rr= mysql_query($s,$l) or die($s);
			$empleadoscobranza = "";
			if(mysql_num_rows($rr)>0){
				while($fr = mysql_fetch_object($rr)){
					$empleadoscobranza .= $fr->cobrador.",".$fr->diferencia.":";
				}
				
				$empleadoscobranza = substr($empleadoscobranza,0,strlen($empleadoscobranza)-1);
			}
			
			$s = "SELECT r.conductor1, l.diferencia FROM repartomercanciaead r
			INNER JOIN liquidacionead l ON r.folio = l.idreparto AND r.sucursal = l.sucursal
			WHERE l.sucursal = ".$_SESSION[IDSUCURSAL]." AND r.fecha = '".$fecha."' AND l.diferencia > 0";
			$rr= mysql_query($s,$l) or die($s);
			$empleadosead = "";
			if(mysql_num_rows($rr)>0){
				while($fr = mysql_fetch_object($rr)){
					$empleadosead .= $fr->conductor1.",".$fr->diferencia.":";			
				}
				
				$empleadosead = substr($empleadosead,0,strlen($empleadosead)-1);
			}
			
			$s = "SELECT usuariocaja, (difefectivo + diftarjeta + difcheque + diftransferencia + difretiros) AS diferencia 
			FROM cierrecaja WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechacierre = '".$fecha."'";
			$rr= mysql_query($s,$l) or die($s);
			$empleadosventanilla = "";
			if(mysql_num_rows($rr)>0){
				while($fr = mysql_fetch_object($rr)){
					if($fr->diferencia > 0){
						$empleadosventanilla .= $fr->usuariocaja.",".$fr->diferencia.":";
					}
				}		
				$empleadosventanilla = substr($empleadosventanilla,0,strlen($empleadosventanilla)-1);				
			}
			
			/*$s = "SELECT * FROM cierrecaja 
			WHERE tipocierre='definitivo' AND fechacierre = '".$fecha."' AND sucursal=".$_SESSION[IDSUCURSAL]."";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				$cierre = "SI";
			}else{
				$cierre = "NO";
			}*/
			$cierre = "";
			$s = "SELECT ifnull(efectivo,0) AS efectivo, ifnull(tarjeta,0) AS tarjeta, ifnull(transferencia,0) AS transferencia, 
			ifnull(cheque,0) AS cheque FROM cierrecaja
			WHERE fechacierre = '".$fecha."' AND tipocierre = 'definitivo' AND sucursal = ".$_SESSION[IDSUCURSAL];
			$r = mysql_query($s,$l) or die($s); 
			$efectivo = 0; $tarjeta= 0; $transferencia= 0; $cheque= 0; $entregado = 0;
			if(mysql_num_rows($r)>0){
				while($te = mysql_fetch_object($r)){				
					$efectivo 		= ((empty($te->efectivo))?0:$te->efectivo) + $efectivo;
					$tarjeta 		= ((empty($te->tarjeta))?0:$te->tarjeta) + $tarjeta;
					$transferencia 	= ((empty($te->transferencia))?0:$te->transferencia) + $transferencia;
					$cheque 		= ((empty($te->efectivo))?0:$te->cheque) + $cheque;
				}
				
				$te->efectivo = $efectivo; $te->tarjeta = $tarjeta; $te->transferencia = $transferencia; $te->cheque = $cheque;	
				$te->entregado = $te->efectivo + $te->tarjeta + $te->transferencia + $te->cheque;
			}else{
				$te->efectivo = 0; $te->tarjeta= 0; $te->transferencia= 0; $te->cheque= 0; $te->entregado = 0;
			}
			$entregado = str_replace('null','""',json_encode($te));
			
			echo "({principal:'$principal',ventanilla:$ventanilla,ocurre:$ocurre,
					abono:$abono,liquidacion:$liquidacion,cobranza:$cobranza,
					otros:$otros,empleadoscobranza:'$empleadoscobranza',empleadosead:'$empleadosead',
					empleadosventanilla:'$empleadosventanilla',cierre:'$cierre',entregado:$entregado,usuarios:'$usuario',facturado:$facturado})";
		}		
	}else if($_GET[accion] == 6){
		//$row = split(",",$_GET[arr]);
		if($_GET[tipo] == "guardar"){
			if($_GET[folio] == "modificar"){				
				$s = "UPDATE cierreprincipal SET fechacierre='".cambiaf_a_mysql($_GET[fecha])."', sucursal=".$_SESSION[IDSUCURSAL].",
				estado='GUARDADO', idusuario=".$_SESSION[IDUSUARIO].",
				fecha=CURRENT_TIMESTAMP() 
				WHERE folio=".$_GET[cierre]." AND sucursal=".$_SESSION[IDSUCURSAL]."";
				mysql_query($s,$l) or die($s);
				echo "ok,".$_GET[tipo].",".$_GET[folio].",".$_GET[cierre];
			}else{
				$s = "INSERT INTO cierreprincipal SET folio = obtenerFolio('cierreprincipal',$_SESSION[IDSUCURSAL]),
				fechacierre='".cambiaf_a_mysql($_GET[fecha])."', sucursal=".$_SESSION[IDSUCURSAL].",
				estado='GUARDADO', idusuario=".$_SESSION[IDUSUARIO].",
				fecha=CURRENT_TIMESTAMP()";				
				mysql_query($s,$l) or die($s);
				$folio = mysql_insert_id();
				
				$s = "SELECT folio FROM cierreprincipal WHERE id = ".$folio."";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				
				
				echo "ok,".$_GET[tipo].",".$_GET[folio].",".$f->folio;
			}
		}else if($_GET[tipo] == "cierre"){
			if($_GET[folio] == "modificar"){
				$s = "UPDATE cierreprincipal SET fechacierre='".cambiaf_a_mysql($_GET[fecha])."', sucursal=".$_SESSION[IDSUCURSAL].",
				estado='CERRADA', idusuario=".$_SESSION[IDUSUARIO].",
				fecha=CURRENT_TIMESTAMP() 
				WHERE folio=".$_GET[cierre]." AND sucursal=".$_SESSION[IDSUCURSAL]."";
				mysql_query($s,$l) or die($s);
				echo "ok,".$_GET[tipo].",".$_GET[folio].",".$_GET[cierre];
			}else{				
				$s = "INSERT INTO cierreprincipal SET folio = obtenerFolio('cierreprincipal',$_SESSION[IDSUCURSAL]),
				fechacierre='".cambiaf_a_mysql($_GET[fecha])."', sucursal=".$_SESSION[IDSUCURSAL].",
				estado='CERRADA', idusuario=".$_SESSION[IDUSUARIO].",
				fecha=CURRENT_TIMESTAMP()";
				mysql_query($s,$l) or die($s);
				$folio = mysql_insert_id();
				
				$s = "SELECT folio FROM cierreprincipal WHERE id = ".$folio."";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
				
				echo "ok,".$_GET[tipo].",".$_GET[folio].",".$f->folio;
			}
		}	
	}else if($_GET[accion]==7){
		$s = "SELECT folio FROM repartomercanciaead 
		WHERE ".((!empty($_GET[dia_anterior]))? " AND fecha = '".cambiaf_a_mysql($_GET[fecha])."'" : " fecha = CURDATE()")." AND liquidado = 0 AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "SELECT folio FROM deposito 
		WHERE ".((!empty($_GET[dia_anterior]))? " AND fechadeposito = '".cambiaf_a_mysql($_GET[fecha])."'" : " fechadeposito = CURDATE()")." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$d = mysql_query($s,$l) or die($s);
		
		echo "({'liquidaciones':".((mysql_num_rows($r)>0)?1:0).",
		'depositos':".((mysql_num_rows($d)==0)?1:0).",
		'cierre':'".$_GET[cierre]."'})";
		
	}
?>