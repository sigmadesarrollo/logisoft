<?
	 session_start();
	require_once("../Conectar.php");
	$l=Conectarse('webpmm');
	if ($_GET[accion]==1){
		
		$sql = "SELECT CASE DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."') 
			WHEN 1 THEN 'DOMINGO' 
			WHEN 2 THEN 'LUNES' 
			WHEN 3 THEN 'MARTES' 
			WHEN 4 THEN 'MIERCOLES' 
			WHEN 5 THEN 'JUEVES' 
			WHEN 6 THEN 'VIERNES' 
			WHEN 7 THEN 'SABADO' 
			END AS dia";
		$r = mysql_query($sql,$l)or die($sql); 
		$f = mysql_fetch_object($r);
		$diasemana = strtolower($f->dia);
		$concatenacion1 = "sc.".strtolower($diasemana)."pago";
		$concatenacion2 = "sc.".strtolower($diasemana)."revision";
		$sectors=$_GET[elsector];
		if ($sectors!="0"){
			$concatenacion3 = " AND gv.sector=$sectors";
		}else{
			$concatenacion3 = "";
		}
		$s = "DELETE FROM relacioncobranzadetalle_tmp WHERE usuario = '$_SESSION[IDUSUARIO]' AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l)or die($s); 
		
		///SOLICITUD GUIAS EMPRESARIALES FACTURADAS
		$s = "INSERT INTO relacioncobranzadetalle_tmp
		(cliente,guia,fechaguia,fechavencimiento,factura,importe,saldo,estado,usuario,sucursal)
		SELECT clave,guia,fecha,fechavencimiento,foliofactura,importe,saldoactual,estado,".$_SESSION[IDUSUARIO].",
		".$_SESSION[IDSUCURSAL]." FROM (
		SELECT f.cliente AS clave,
			gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
			DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
			f.folio AS foliofactura, gv.total  AS importe,
			(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
			'No Revisadas' AS estado
			FROM facturacion AS f
			INNER JOIN solicitudguiasempresariales gv ON f.folio=gv.factura
			INNER JOIN pagoguias pg ON f.folio=pg.guia
			INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
			WHERE pg.pagado='N' AND pg.sucursalacobrar=".$_GET[sucursal]." AND f.estadocobranza = 'N' AND 
				(CASE DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."')
					WHEN 2 THEN sc.lunesrevision=1
					WHEN 3 THEN sc.martesrevision=1
					WHEN 4 THEN sc.miercolesrevision=1
					WHEN 5 THEN sc.juevesrevision=1
					WHEN 6 THEN sc.viernesrevision=1
					WHEN 7 THEN sc.sabadorevision=1
				END OR sc.semanarevision = 1))t";
	mysql_query($s,$l)or die($s); 
	//echo $s."<br>";
	
	$s = "INSERT INTO relacioncobranzadetalle_tmp
(cliente,guia,fechaguia,fechavencimiento,factura,importe,saldo,estado,usuario,sucursal)
SELECT clave,guia,fecha,fechavencimiento,foliofactura,importe,saldoactual,estado,".$_SESSION[IDUSUARIO].",
".$_SESSION[IDSUCURSAL]." FROM (
SELECT f.cliente AS clave,
	gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
	DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
	f.folio AS foliofactura, gv.total  AS importe,
	(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
	'Revisadas' AS estado
	FROM facturacion AS f
	INNER JOIN solicitudguiasempresariales gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON f.folio=pg.guia
	INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
	WHERE pg.pagado='N' AND pg.sucursalacobrar=".$_GET[sucursal]." AND f.estadocobranza = 'R' AND 
		(CASE DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."')
			WHEN 2 THEN sc.lunespago=1
			WHEN 3 THEN sc.martespago=1
			WHEN 4 THEN sc.miercolespago=1
			WHEN 5 THEN sc.juevespago=1
			WHEN 6 THEN sc.viernespago=1
			WHEN 7 THEN sc.sabadopago=1
		END OR sc.semanapago = 1))t";
	mysql_query($s,$l)or die($s); 
	//echo $s."<br>";
	
	$s = "INSERT INTO relacioncobranzadetalle_tmp
(cliente,guia,fechaguia,fechavencimiento,factura,importe,saldo,estado,usuario,sucursal)
SELECT clave,guia,fecha,fechavencimiento,foliofactura,importe,saldoactual,estado,".$_SESSION[IDUSUARIO].",
".$_SESSION[IDSUCURSAL]." FROM (
SELECT f.cliente AS clave,
	gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
	DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
	f.folio AS foliofactura, gv.total  AS importe,
	(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
	'Revisadas' AS estado
	FROM facturacion AS f
	INNER JOIN guiasventanilla gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
	INNER JOIN liquidacioncobranzadetalle lcd ON gv.id=lcd.guia
	WHERE pg.pagado='N' AND pg.sucursalacobrar=".$_GET[sucursal]." AND f.estadocobranza = 'R' 
	AND lcd.compromiso='".cambiaf_a_mysql($_GET[fecha])."' AND lcd.contrarecibo<>0)t";
	mysql_query($s,$l)or die($s); 
	
	//echo $s."<br>";
	
		/*guia no revisadas*/	
	$sql="INSERT INTO relacioncobranzadetalle_tmp(cliente,guia,fechaguia,fechavencimiento,factura,importe,saldo,estado,usuario,sucursal)
	SELECT clave,guia,fecha,fechavencimiento,foliofactura,importe,saldoactual,estado,".$_SESSION[IDUSUARIO].",
	".$_SESSION[IDSUCURSAL]." FROM (
	SELECT f.cliente AS clave,
	gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
	DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
	f.folio AS foliofactura, gv.total  AS importe,
	(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
	'No Revisadas' AS estado
	FROM facturacion AS f
	INNER JOIN guiasventanilla gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
	WHERE pg.pagado='N' AND pg.sucursalacobrar=" .$_GET[sucursal]." AND f.estadocobranza = 'N' AND 
		(CASE DAYOFWEEK('" .cambiaf_a_mysql($_GET[fecha])."')
			WHEN 2 THEN sc.lunesrevision=1
			WHEN 3 THEN sc.martesrevision=1
			WHEN 4 THEN sc.miercolesrevision=1
			WHEN 5 THEN sc.juevesrevision=1
			WHEN 6 THEN sc.viernesrevision=1
			WHEN 7 THEN sc.sabadorevision=1
		END OR sc.semanarevision = 1) 
	$concatenacion3
	GROUP BY gv.id
UNION 
	SELECT f.cliente AS clave,
	gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
	DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
	f.folio AS foliofactura, gv.total  AS importe,
	(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
	'No Revisadas' AS estado
	FROM facturacion AS f
	INNER JOIN guiasempresariales gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
	WHERE pg.pagado='N' AND pg.sucursalacobrar=" .$_GET[sucursal]." AND f.estadocobranza = 'N' AND 
		(CASE DAYOFWEEK('" .cambiaf_a_mysql($_GET[fecha])."')
			WHEN 2 THEN sc.lunesrevision=1
			WHEN 3 THEN sc.martesrevision=1
			WHEN 4 THEN sc.miercolesrevision=1
			WHEN 5 THEN sc.juevesrevision=1
			WHEN 6 THEN sc.viernesrevision=1
			WHEN 7 THEN sc.sabadorevision=1
		END OR sc.semanarevision = 1) 
	$concatenacion3
	GROUP BY gv.id
)tabla ORDER BY guia";
//echo $sql."<br>";
$r=mysql_query($sql,$l)or die($sql); 
/*guia revisadas*/
$sql="INSERT INTO relacioncobranzadetalle_tmp(cliente,guia,fechaguia,fechavencimiento,factura,importe,saldo,estado,usuario,sucursal)
SELECT clave,guia,fecha,fechavencimiento,foliofactura,importe,saldoactual,estado,".$_SESSION[IDUSUARIO].",
".$_SESSION[IDSUCURSAL]." FROM (
	SELECT f.cliente AS clave,
	gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
	DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
	f.folio AS foliofactura, gv.total  AS importe,
	(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
	'Revisadas' AS estado
	FROM facturacion AS f
	INNER JOIN guiasventanilla gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
	WHERE pg.pagado='N' AND pg.sucursalacobrar=" .$_GET[sucursal]." AND f.estadocobranza = 'R' AND 
		(CASE DAYOFWEEK('" .cambiaf_a_mysql($_GET[fecha])."')
			WHEN 2 THEN sc.lunespago=1
			WHEN 3 THEN sc.martespago=1
			WHEN 4 THEN sc.miercolespago=1
			WHEN 5 THEN sc.juevespago=1
			WHEN 6 THEN sc.viernespago=1
			WHEN 7 THEN sc.sabadopago=1
		END OR sc.semanapago = 1) 
	$concatenacion3
	GROUP BY gv.id
UNION
	SELECT f.cliente AS clave,
	gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
	DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
	f.folio AS foliofactura, gv.total  AS importe,
	(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
	'Revisadas' AS estado
	FROM facturacion AS f
	INNER JOIN guiasempresariales gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
	WHERE pg.pagado='N' AND pg.sucursalacobrar=" .$_GET[sucursal]." AND f.estadocobranza = 'R' AND 
		(CASE DAYOFWEEK('" .cambiaf_a_mysql($_GET[fecha])."')
			WHEN 2 THEN sc.lunespago=1
			WHEN 3 THEN sc.martespago=1
			WHEN 4 THEN sc.miercolespago=1
			WHEN 5 THEN sc.juevespago=1
			WHEN 6 THEN sc.viernespago=1
			WHEN 7 THEN sc.sabadopago=1
		END OR sc.semanapago = 1) 
	$concatenacion3
	GROUP BY gv.id
)tabla ORDER BY guia";
$r=mysql_query($sql,$l)or die($sql); 
//echo $sql."<br>";
		
	/*guia con fecha compromiso*/	
$sql="INSERT INTO relacioncobranzadetalle_tmp
(cliente,guia,fechaguia,fechavencimiento,factura,importe,saldo,estado,usuario,sucursal)
SELECT clave,guia,fecha,fechavencimiento,foliofactura,importe,saldoactual,estado,".$_SESSION[IDUSUARIO].",
".$_SESSION[IDSUCURSAL]." FROM (
	SELECT f.cliente AS clave,
	gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
	DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
	f.folio AS foliofactura, gv.total  AS importe,
	(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
	'Revisadas' AS estado
	FROM facturacion AS f
	INNER JOIN guiasventanilla gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
	INNER JOIN liquidacioncobranzadetalle lcd ON gv.id=lcd.guia
	WHERE pg.pagado='N' AND pg.sucursalacobrar=" .$_GET[sucursal]." AND f.estadocobranza = 'R' 
	AND lcd.compromiso='" .cambiaf_a_mysql($_GET[fecha])."' AND lcd.contrarecibo<>0
	$concatenacion3
	GROUP BY gv.id
UNION
	SELECT f.cliente AS clave,
	gv.id AS guia,DATE_FORMAT(gv.fecha, '%Y-%m-%d')AS fecha,
	DATE_FORMAT(DATE_ADD(gv.fecha, INTERVAL sc.diascredito DAY),'%Y-%m-%d') AS fechavencimiento,
	f.folio AS foliofactura, gv.total  AS importe,
	(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
	'Revisadas' AS estado
	FROM facturacion AS f
	INNER JOIN guiasempresariales gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito AS sc ON f.cliente = sc.cliente
	INNER JOIN liquidacioncobranzadetalle lcd ON gv.id=lcd.guia
	WHERE pg.pagado='N' AND pg.sucursalacobrar=" .$_GET[sucursal]." AND f.estadocobranza = 'R' 
	AND lcd.compromiso='" .cambiaf_a_mysql($_GET[fecha])."' AND lcd.contrarecibo<>0
	$concatenacion3
	GROUP BY gv.id
)tabla ORDER BY guia";
		$r=mysql_query($sql,$l)or die($sql); 
//		echo $sql."<br>";
		echo "ok";
	}else if($_GET[accion]==2){
	
		$s = "SELECT DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."') AS fecha";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo $f->fecha;
		
	}else if($_GET[accion]==4){
				$sql="SELECT cs.id as clave,cs.descripcion AS sucursal,
				DATE_FORMAT(rc.fecharelacion,'%d/%m/%Y')AS fecha,rc.sector,rc.cobrador FROM relacioncobranza rc 
				INNER JOIN catalogosucursal cs ON rc.sucursal=cs.id 
				where rc.folio=".$_GET[folio]." AND rc.sucursal = ".$_SESSION[IDSUCURSAL]."";
				$r = mysql_query($sql,$l)or die($sql);
				if (mysql_num_rows($r)>0){
					$r = mysql_query($sql,$l)or die($sql);
					$f = mysql_fetch_object($r);
					$f->sucursal = cambio_texto($f->sucursal);
					$principal = str_replace('null','""',json_encode($f));
					
					$sql="SELECT cc.id AS clave,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
					rcd.guia,DATE_FORMAT(rcd.fechaguia,'%d/%m/%Y') AS fecha,
					DATE_FORMAT(rcd.fechavencimiento,'%d/%m/%Y')as fechavencimiento,rcd.factura AS foliofactura, 
					rcd.importe,rcd.saldo AS saldoactual,rcd.estado,rcd.sucursal FROM relacioncobranza rc
					INNER JOIN relacioncobranzadetalle rcd ON rc.folio=rcd.relacioncobranza AND rc.sucursal = rcd.sucursal
					INNER JOIN catalogocliente cc ON rcd.cliente=cc.id
					WHERE rc.folio=".$_GET[folio]." AND rc.sucursal = ".$_SESSION[IDSUCURSAL]."
					order by rcd.fechaguia";
					$r = mysql_query($sql,$l)or die($sql);
						while ($f=mysql_fetch_object($r)){
							$f->cliente = cambio_texto($f->cliente);
							$f->guia = cambio_texto($f->guia);
							$f->guia = cambio_texto($f->guia);
							$f->estado = cambio_texto($f->estado);
							$registros[] = $f;
						}
						$detalle = str_replace('null','""',json_encode($registros));
					echo "({principal:$principal,detalle:$detalle})";
				}else{
					echo "no encontro";
				}
	
	}else if ($_GET[accion]==5){	
			$sql = "SELECT id AS idsucursal, descripcion as sucursal, 
			date_format(current_date, '%d/%m/%Y') AS fecha from catalogosucursal 
			WHERE id ='".$_SESSION[IDSUCURSAL]."'";	
			$r=mysql_query($sql,$l)or die($sql); 
				$registros= array();
		
				if (mysql_num_rows($r)>0){
						while ($f=mysql_fetch_object($r))
						{
						$f->sucursal=cambio_texto($f->sucursal);
						$registros[]=$f;	
						}
						echo str_replace('null','""',json_encode($registros));
				}else{
						echo str_replace('null','""',json_encode(0));
				}
				
	}else if ($_GET[accion]==6){
		$sql = "INSERT INTO relacioncobranzadetalle_tmp(cliente,guia,fechaguia,fechavencimiento,factura,
		importe,saldo,estado,usuario,sucursal)
		SELECT cc.id AS cliente,ge.id AS guia,ge.fecha,
		DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,f.folio AS foliofactura, ge.total  AS importe,
		(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
		IF(ISNULL(lqt.estado),'No Revisadas','Revisadas') AS estado,".$_SESSION[IDUSUARIO].",".$_SESSION[IDSUCURSAL]."
		FROM guiasventanilla ge
		INNER JOIN facturacion f ON ge.factura=f.folio
		INNER JOIN catalogocliente cc ON f.cliente=cc.id
		LEFT JOIN 
		(SELECT ld.factura FROM liquidacioncobranza l INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion
		WHERE l.estado='LIQUIDADO' and ld.cobrar='SI' GROUP BY ld.factura)lq ON f.folio<>lq.factura
		LEFT JOIN 
		(SELECT ld.factura,'Revisadas' AS estado FROM liquidacioncobranza l INNER JOIN liquidacioncobranzadetalle ld ON
		l.folio=ld.folioliquidacion 
		WHERE l.estado='GUARDADO' AND ld.contrarecibo<>'0' GROUP BY ld.factura)lqt ON lqt.factura=f.folio	
		WHERE f.folio=".$_GET[factura]."";	
			$r=mysql_query($sql,$l)or die($sql); 
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
	}else if ($_GET[accion]==7){
	
			$sql = "SELECT * FROM relacioncobranzadetalle_tmp 
			WHERE factura=".$_GET[factura]." and usuario=".$_SESSION[IDUSUARIO]."";	
			$r=mysql_query($sql,$l)or die($sql); 
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
	}else if ($_GET[accion]==8){
		$sq = "SELECT cc.id AS clave,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
		rc.guia,DATE_FORMAT(rc.fechaguia, '%d/%m/%Y') AS fecha,
		DATE_FORMAT(rc.fechavencimiento, '%d/%m/%Y') AS fechavencimiento,
		rc.factura AS foliofactura, rc.importe,rc.saldo AS saldoactual,MAX(rc.estado)AS estado,rc.sucursal 
		FROM relacioncobranzadetalle_tmp rc
		INNER JOIN facturacion f ON rc.factura = f.folio
		INNER JOIN catalogocliente cc ON rc.cliente=cc.id
		WHERE rc.usuario=".$_SESSION[IDUSUARIO]." AND f.enrelacion = 'N'
		GROUP BY rc.guia ORDER BY rc.fechaguia";	
			$d=mysql_query($sq,$l)or die($sq); 
			$registross= array();
			if (mysql_num_rows($d)>0){
				while ($f=mysql_fetch_object($d))
				{
					$f->cliente=cambio_texto($f->cliente);
					$registross[]=$f;	
				}
				echo str_replace('null','""',json_encode($registross));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}else if ($_GET[accion]==9){		
		$s = "SELECT obtenerFolio('relacioncobranza',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$folio = $f->folio;
		
		$s = "SELECT DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fecha, (SELECT id FROM catalogosucursal 
		where id=".$_SESSION[IDSUCURSAL].") as idsucursal,(SELECT descripcion FROM catalogosucursal 
		where id=".$_SESSION[IDSUCURSAL].") as sucursal,(SELECT CASE DAYOFWEEK(CURDATE()) WHEN 1 THEN 'DOMINGO' WHEN 2 THEN 'LUNES' WHEN 3 THEN 'MARTES' WHEN 4 THEN 'MIERCOLES' WHEN 5 THEN 'JUEVES' WHEN 6 THEN 'VIERNES' WHEN 7 THEN 'SABADO' END AS Dia)AS dia";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		
		echo $folio.",".$f->fecha.",".$f->idsucursal.",".$f->sucursal.",".$f->dia;
		
	}else if ($_GET[accion]==10){			
			$s = "SELECT * FROM facturacion WHERE folio=".$_GET[factura]."";	
			$r = mysql_query($s,$l)or die($s); 
			$f = mysql_fetch_object($r);
			
			if(mysql_num_rows($r)==0){
				die("no existe");
			}
			
			if($f->facturaestado == "CANCELADO"){
				die("cancelado");
			}
			
			$s = "SELECT factura FROM (
			SELECT factura FROM liquidacioncobranzadetalle WHERE cobrar='SI' 
			AND factura=".$_GET[factura]." GROUP BY factura
			UNION		
			SELECT gv.factura FROM guiasventanilla gv 
			INNER JOIN pagoguias pg ON gv.id=pg.guia
			WHERE pg.pagado='S' AND gv.factura=".$_GET[factura]."
			UNION
			SELECT gv.factura FROM guiasempresariales gv 
			INNER JOIN pagoguias pg ON gv.id=pg.guia
			WHERE pg.pagado='S' AND gv.factura=".$_GET[factura].")tabla 
			GROUP BY factura";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				die("liquidada");
			}
			
			$s = "SELECT rcd.factura FROM relacioncobranza rc 
			INNER JOIN relacioncobranzadetalle rcd ON rc.folio = rcd.relacioncobranza
			WHERE rc.fecharelacion='".cambiaf_a_mysql($_GET[fecha])."' AND rcd.factura=".$_GET[factura]."";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
				die("revision");
			}
			
			$s = "SELECT * FROM relacioncobranzadetalle_tmp 
			WHERE factura = ".$_GET[factura]." and usuario=".$_SESSION[IDUSUARIO]."";	
			$r = mysql_query($s,$l)or die($s);
			if(mysql_num_rows($r)>0){
				die("ya fue agregada");
			}
			
			$s = "INSERT INTO relacioncobranzadetalle_tmp(cliente,guia,fechaguia,fechavencimiento,factura,
			importe,saldo,estado,usuario,sucursal)
			SELECT cc.id AS cliente,ge.id AS guia,ge.fecha,
			DATE_ADD(ge.fecha, INTERVAL cc.diascredito DAY) AS fechavencimiento,f.folio AS foliofactura, ge.total  AS importe,
			(f.total + f.otrosmontofacturar + f.sobmontoafacturar) AS saldoactual, 
			IF(f.estadocobranza='N','No Revisadas','Revisadas') AS estado,".$_SESSION[IDUSUARIO].",".$_SESSION[IDSUCURSAL]."
			FROM guiasventanilla ge
			INNER JOIN facturacion f ON ge.factura=f.folio
			INNER JOIN catalogocliente cc ON f.cliente=cc.id
			LEFT JOIN 
			(SELECT ld.factura FROM liquidacioncobranza l INNER JOIN liquidacioncobranzadetalle ld ON l.folio=ld.folioliquidacion
			WHERE l.estado='LIQUIDADO' and ld.cobrar='SI' GROUP BY ld.factura)lq ON f.folio<>lq.factura
			LEFT JOIN 
			(SELECT ld.factura,'Revisadas' AS estado FROM liquidacioncobranza l INNER JOIN liquidacioncobranzadetalle ld ON
			l.folio=ld.folioliquidacion 
			WHERE l.estado='GUARDADO' AND ld.contrarecibo<>'0' GROUP BY ld.factura)lqt ON lqt.factura=f.folio	
			WHERE f.folio=".$_GET[factura]."";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM relacioncobranzadetalle_tmp WHERE factura = ".$_GET[factura]."";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)==0){
				die("no encontro");
			}
			
			$s = "SELECT t.cliente as clave, concat_ws(' ',cc.nombre,cc.paterno,cc.materno) as cliente,
			t.guia, date_format(t.fechaguia,'%d/%m/%Y') as fecha, date_format(t.fechavencimiento,'%d/%m/%Y') as fechavencimiento,
			t.factura as foliofactura, t.importe, t.saldo as saldoactual, t.estado, t.sucursal
			FROM relacioncobranzadetalle_tmp t
			INNER JOIN catalogocliente cc ON t.cliente = cc.id
			WHERE t.usuario = ".$_SESSION[IDUSUARIO]."";
			$r = mysql_query($s,$l)or die($s);
			$arr = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->cliente = cambio_texto($f->cliente);
					$f->guia = cambio_texto($f->guia);
					$arr[] = $f;
				}
				echo str_replace('null','""',json_encode($arr));
			}else{
				echo "no encontro";
			}
			
	}else if ($_GET[accion]==11){
		
			/*$sql = "SELECT factura FROM liquidacioncobranzadetalle WHERE  cobrar='SI' 
			AND factura=".$_GET[factura]." GROUP BY factura";	*/
			
			$sql="SELECT factura FROM (
				SELECT factura FROM liquidacioncobranzadetalle WHERE cobrar='SI' 
				AND factura=".$_GET[factura]." GROUP BY factura
				UNION		
				SELECT gv.factura FROM guiasventanilla gv 
				INNER JOIN pagoguias pg ON gv.id=pg.guia
				WHERE pg.pagado='S' AND gv.factura=".$_GET[factura]."
				UNION
				SELECT gv.factura FROM guiasempresariales gv 
				INNER JOIN pagoguias pg ON gv.id=pg.guia
				WHERE pg.pagado='S' AND gv.factura=".$_GET[factura].")tabla 
				GROUP BY factura";
			$r=mysql_query($sql,$l)or die($sql); 
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
			
	}else if ($_GET[accion]==12){
		
			$sql="SELECT rcd.factura FROM relacioncobranza rc 
			INNER JOIN relacioncobranzadetalle rcd ON rc.folio=rcd.relacioncobranza
			WHERE rc.fecharelacion='".cambiaf_a_mysql($_GET[fecha])."' AND rcd.factura=".$_GET[factura]."";
			$r=mysql_query($sql,$l)or die($sql); 
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
		
	}else if ($_GET[accion]==13){
			$sql = "SELECT CASE DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."') 
			WHEN 1 THEN 'DOMINGO' 
			WHEN 2 THEN 'LUNES' 
			WHEN 3 THEN 'MARTES' 
			WHEN 4 THEN 'MIERCOLES' 
			WHEN 5 THEN 'JUEVES' 
			WHEN 6 THEN 'VIERNES' 
			WHEN 7 THEN 'SABADO' 
			END AS dia";
		$r=mysql_query($sql,$l)or die($sql); 
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
		
	}else if ($_GET[accion]==14){	
		$sql = "select id AS idsucursal, descripcion as sucursal, 
		date_format(current_date, '%d/%m/%Y') AS fecha from catalogosucursal 
		where id = '".$_SESSION[IDSUCURSAL]."'";	
		$r=mysql_query($sql,$l)or die($sql); 
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
		
	}else if($_GET[accion]==15){
		$s ="insert into relacioncobranza set 
		folio = obtenerFolio('relacioncobranza',".$_SESSION[IDSUCURSAL]."),
		sucursal=".$_SESSION[IDSUCURSAL].",
		fecharelacion='".cambiaf_a_mysql($_GET[fecha]) ."',sector=".$_GET[sector].",cobrador=".$_GET[cobrador].",
		usuario=".$_SESSION[IDUSUARIO].", fecha=CURRENT_TIMESTAMP()";
		mysql_query(str_replace("''",'null', $s), $l) or die($s);
		$folio = mysql_insert_id();
		
		$s = "SELECT folio FROM relacioncobranza WHERE id = ".$folio."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$folio = $f->folio;
		
		$s = "INSERT INTO relacioncobranzadetalle
		SELECT 0 AS id, ".$folio." AS relacioncobranza,cliente,guia,fechaguia,fechavencimiento,
		factura,importe,saldo,estado,usuario,CURRENT_TIMESTAMP,sucursal FROM relacioncobranzadetalle_tmp
		WHERE usuario = ".$_SESSION[IDUSUARIO]." AND sucursal = $_SESSION[IDSUCURSAL] AND factura in ($_GET[facturas])
		GROUP BY guia";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT * FROM relacioncobranzadetalle 
		WHERE relacioncobranza = ".$folio." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "update facturacion set enrelacion='S' where folio = ".$f->factura."";
			mysql_query($s,$l) or die($s);
		}
		
		echo "ok,".$folio;
	}
?>