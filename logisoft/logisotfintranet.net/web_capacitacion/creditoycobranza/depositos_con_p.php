<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "DELETE FROM depositodetalletmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT obtenerFolio('deposito',$_SESSION[IDSUCURSAL]) AS folio";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		$folio = str_replace('null','""',json_encode($f->folio));
		
		$s = "SELECT id FROM iniciodia WHERE sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		
		$fecha = "";
		if(mysql_num_rows($r)>0){
			$s = "SELECT DAYOFWEEK(ADDDATE(CURRENT_DATE, INTERVAL -1 DAY)) dia";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			if($f->dia==1)
				$diasmenos = 2;
			else
				$diasmenos = 1;
			
			$s = "SELECT ifnull(id,0) as id FROM iniciodia 
			WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciodia = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY)";
			$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
			
			$s = "SELECT id FROM iniciocaja WHERE sucursal = ".$_SESSION[IDSUCURSAL]." AND fechainiciocaja = ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY)";
			$t = mysql_query($s,$l) or die($s);
			if(($f->id > 0 || !empty($f->id)) && mysql_num_rows($t)>0){
				$s = "SELECT DATE_FORMAT(ADDDATE(CURDATE(), INTERVAL - $diasmenos DAY),'%d/%m/%Y') AS fecha";
				$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
				$fecha = $f->fecha;
			}
		}
		
		$fechadeposito = $fecha;
		
		$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo FROM formapago 
		WHERE ".((!empty($fecha))? "fecha = '".cambiaf_a_mysql($fechadeposito)."'" : "fecha < CURDATE()" )." 
		AND sucursal = ".$_SESSION[IDSUCURSAL]." AND ISNULL(fechacancelacion) ";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r); $efectivo = $f->efectivo;
		
		$s = "INSERT INTO depositodetalletmp(cantidad, fechacheque, ncheque, banco, cliente, ficha, agrego, idusuario, fecha, sucursal)
		SELECT cheque, fecha, ncheque, banco, cliente, '' AS ficha, 'NO' AS agrego, ".$_SESSION[IDUSUARIO].", CURRENT_TIMESTAMP, ".$_SESSION[IDSUCURSAL]." 
		FROM formapago	WHERE ".((!empty($fecha))? "fecha = '".cambiaf_a_mysql($fechadeposito)."'" : "fecha < CURDATE()" )." 
		AND cheque IS NOT NULL AND sucursal = ".$_SESSION[IDSUCURSAL]." AND (fechacancelacion IS NULL OR fechacancelacion = '0000-00-00')
		AND endeposito = 0";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT 1 as sel, d.cantidad, DATE_FORMAT(d.fechacheque,'%d/%m/%Y') AS fechacheque,
		d.ncheque, d.banco, d.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS ncliente,
		cb.descripcion AS nbanco, ficha, agrego FROM depositodetalletmp d
		INNER JOIN catalogobanco cb ON d.banco = cb.id
		INNER JOIN catalogocliente cc ON d.cliente = cc.id
		WHERE d.idusuario = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){
				$f->nbanco = cambio_texto($f->nbanco);
				$f->ncliente = cambio_texto($f->ncliente);
				$registros[] = $f;
			}	
		$detalle = str_replace('null','""',json_encode($registros));
		
		echo "({folio:$folio,detalle:$detalle,fechadeposito:'$fechadeposito',efectivo:$efectivo})" ;
	
	}else if($_GET[accion]==2){
		$s = "INSERT INTO depositodetalletmp SET 
		cantidad = ".$_GET[cantidad].",
		fechacheque = '".cambiaf_a_mysql($_GET[fechacheque])."',
		ficha = '".$_GET[ficha]."',
		banco = ".$_GET[banco].",
		idusuario = ".$_SESSION[IDUSUARIO].",
		fecha = '".$_GET[fecha]."',
		sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
		
	}else if($_GET[accion]==3){
		$s = "UPDATE depositodetalletmp SET 
		cantidad = ".$_GET[cantidad].",
		fechacheque = '".cambiaf_a_mysql($_GET[fechacheque])."',
		ficha = '".$_GET[ficha]."',
		banco = ".$_GET[banco].",
		WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha = '".$_GET[fecha]."' AND sucursal = ".$_SESSION[IDSUCURSAL]."";		
		mysql_query($s,$l) or die($s);
		
		echo "ok";
		
	}else if($_GET[accion]==4){//REGISTRAR DEPOSITOS
		$s = "INSERT INTO deposito SET 
		folio = obtenerFolio('deposito',$_SESSION[IDSUCURSAL]), fechadeposito = CURDATE(), cantidad = '".$_GET[cantidad]."', 
		fechaefectivo = '".cambiaf_a_mysql($_GET[fecha])."', 
		ficha = '".$_GET[ficha]."', banco = '".$_GET[banco]."', 
		idusuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP, 
		sucursal = $_SESSION[IDSUCURSAL]";
		mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
		
		if($_GET[lleva]=="si"){		
			$row = split(":",$_GET[fichas]);
				for($i=0;$i<count($row);$i++){
					$t = split(",",$row[$i]);
					$s = "UPDATE depositodetalletmp SET ficha = '".$t[0]."', agrego = 'SI' WHERE ncheque = '".$t[1]."' AND idusuario = ".$_SESSION[IDUSUARIO]."";
					mysql_query($s,$l) or die($s);
				}
		}else{
			$row = split(",",$_GET[fichas]);
			$s = "UPDATE depositodetalletmp SET ficha = '".$row[0]."', agrego = 'SI' WHERE ncheque = '".$row[1]."' AND idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
		}
		
		$_GET[cheques] = "'".str_replace(",","','",$_GET[cheques])."'";
		
		$s = "INSERT INTO depositodetalle
		SELECT null AS id, ".$folio." AS deposito, cantidad,fechacheque,ncheque,banco,cliente,ficha,agrego,".$_SESSION[IDUSUARIO].", fecha, sucursal 
		FROM depositodetalletmp
		WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND ncheque IN(".$_GET[cheques].")";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE formapago SET endeposito = 1 WHERE ncheque IN(".$_GET[cheques].")";
		mysql_query($s,$l) or die($s);
		
		/* ************ se manejara en el modulo de auditorias, cada ves que se consulte
		$s = "call proc_RegistroAuditorias('DE','$folio',$_SESSION[IDSUCURSAL])";
		$d = mysql_query($s, $l) or die($s);*/
		
		echo "ok,".$folio;
		
	}else if($_GET[accion]==5){//OBTENER DEPOSITOS
		$s = "SELECT id, cantidad, DATE_FORMAT(fechaefectivo,'%d/%m/%Y') AS fechaefectivo,
		ficha, banco FROM deposito WHERE folio=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		echo $s;
		$r = mysql_query($s,$l) or die($s);
		$principal = "";
		if(mysql_num_rows($r)>0){
		
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
			$principal = str_replace('null','""',json_encode($f));
			
			$s = "DELETE FROM depositodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "INSERT INTO depositodetalletmp
			SELECT null AS id, cantidad,fechacheque,ncheque,banco,cliente,ficha,agrego,".$_SESSION[IDUSUARIO].",
			fecha, ".$_SESSION[IDSUCURSAL]." FROM depositodetalle WHERE deposito=".$_GET[folio]."";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT FORMAT(SUM(cantidad),2) AS importe FROM depositodetalletmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
			
			$importe = str_replace('null','""',json_encode($f));
			
			$s = "SELECT 0 as sel, d.cantidad, DATE_FORMAT(d.fechacheque,'%d/%m/%Y') AS fechacheque,
			d.ncheque, d.banco, d.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS ncliente,
			cb.descripcion AS nbanco,ficha,agrego FROM depositodetalletmp d
			INNER JOIN catalogobanco cb ON d.banco = cb.id
			INNER JOIN catalogocliente cc ON d.cliente = cc.id
			WHERE d.idusuario = ".$_SESSION[IDUSUARIO]."";
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			$detalle = "";
			while($f = mysql_fetch_object($r)){
				$f->nbanco = cambio_texto($f->nbanco);
				$f->ncliente = cambio_texto($f->ncliente);
				$registros[] = $f;
			}
			$detalle = str_replace('null','""',json_encode($registros));
			
			echo "({principal:$principal,detalle:$detalle,importe:$importe})";
		
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==6){//ELIMINAR DEPOSITOS
		$s = "DELETE FROM depositodetalletmp WHERE idusuario=".$_SESSION[IDUSUARIO]." AND fecha='".$_GET[fecha]."'";
		mysql_query($s,$l) or die($s);
		
		echo "ok";
	}
?>