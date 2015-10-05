<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	
	if($_GET[accion] == 1){// Dia registrodecompromisos.php y registrodecontrarecibos.php
		$fechaa = split("/",$_GET[fecha]);
		$fecha = $fechaa[2]."-".$fechaa[1]."-".$fechaa[0];
		
		$s = 'SELECT 
			CASE DAYOFWEEK("'.$fecha.'")
				WHEN 1 THEN "DOMINGO"
				WHEN 2 THEN "LUNES"
				WHEN 3 THEN "MARTES"
				WHEN 4 THEN "MIERCOLES"
				WHEN 5 THEN "JUEVES"
				WHEN 6 THEN "VIERNES"
				WHEN 7 THEN "SABADO"
			END';
		$r = mysql_query($s,$l) or die($s);
		echo mysql_result($r,0);
	}else if($_GET[accion]==2){ //Guardar registrodecompromisos.php
		$s="INSERT INTO registroscompromisos (cliente, fechacompromiso,  hora, observacion,idusuario, fecha,sucursal )	
		VALUES 	('".$_GET[idcliente]."', '".cambiaf_a_mysql($_GET[fecha])."','".$_GET[hrs]."', '".$_GET[observaciones]."','".$_SESSION[IDUSUARIO]."',CURRENT_TIMESTAMP(),".$_SESSION[IDSUCURSAL].")";
		$r = mysql_query(str_replace("''",'null',$s),$l) or die($s);
		
		$s="update facturacion set estadocobranza='R' WHERE folio=".$_GET[factura]."";
		$r = mysql_query(str_replace("''",'null',$s),$l) or die($s);
		echo "ok";
		
	}else if($_GET[accion]==3){ //Guardar registrodecontrarecibos.php
		$s="INSERT INTO registrodecontrarecibos (factura,cliente, fecharegistro, hora, contrarecibo, observacion, idusuario, usuario, fecha,sucursal)VALUES (".$_GET[factura].",".$_GET[idcliente].", '".cambiaf_a_mysql($_GET[fecha])."', '".$_GET[hrs]."', ".$_GET[recibo].", '".$_GET[observaciones]."', '".$_SESSION[IDUSUARIO]."',  '".$_SESSION[NOMBREUSUARIO]."', CURRENT_DATE,".$_SESSION[IDSUCURSAL].")";
		$r = mysql_query(str_replace("''",'null',$s),$l) or die($s);
		
		$s="update facturacion set estadocobranza='R' WHERE folio=".$_GET[factura]."";
		$r = mysql_query(str_replace("''",'null',$s),$l) or die($s);
		echo "ok";
	}else if($_GET[accion]==4){ 
	
		$s = "SELECT CASE DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."') 
                  WHEN 1 THEN 'DOMINGO'
                  WHEN 2 THEN 'LUNES'
                  WHEN 3 THEN 'MARTES'
                  WHEN 4 THEN 'MIERCOLES'
                  WHEN 5 THEN 'JUEVES'
                  WHEN 6 THEN 'VIERNES'
                  WHEN 7 THEN 'SABADO'
             END AS dia";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo $f->dia;
	}else if($_GET[accion]==5){ 
	
		$s = "SELECT sc.semanapago AS todo,sc.lunespago AS lunes,sc.martespago AS martes,sc.miercolespago AS miercoles,sc.juevespago AS jueves,sc.viernespago AS viernes,sc.sabadopago AS sabado FROM catalogocliente cc
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
			WHERE cc.id=".$_GET[cliente]."";
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
	}else if($_GET[accion]==6){ 
	
		$s = "SELECT DAYOFWEEK(CURDATE())AS numero";
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
	}else if($_GET[accion]==7){ 
		$s = "SELECT DATE_FORMAT(DATE_ADD(CURDATE(),INTERVAL ".$_GET[dia]." DAY),'%d/%m/%Y')AS fecha";
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