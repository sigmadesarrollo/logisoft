<? session_start();

	require_once("../../Conectar.php");

	$l=Conectarse('webpmm');

	

	if ($_GET[accion]==1){

$sql="SELECT tabla.sucursal,cs.prefijo as nombresucursal,ifnull(cl.contador,0)AS carteracredito,SUM(tabla.carteravigente) AS carteravigente,SUM(tabla.carteramorosa)AS carteramorosa,(IFNULL(SUM(tabla.carteravigente),0)+IFNULL(SUM(tabla.carteramorosa),0))AS carteratotal FROM (

	SELECT pg.sucursalacobrar AS sucursal,pg.guia,pg.cliente,SUM(pg.total) AS total,

	DATE_ADD(pg.fechacreo,INTERVAL sc.diascredito DAY)AS fechavencimiento,

	IFNULL(CASE 

	WHEN '" .cambiaf_a_mysql($_GET[fecha])."'<DATE_ADD(pg.fechacreo,INTERVAL sc.diascredito+1 DAY) THEN

	SUM(pg.total)

	END,0) AS carteravigente,

	IFNULL(CASE 

	WHEN '" .cambiaf_a_mysql($_GET[fecha])."'>=DATE_ADD(pg.fechacreo,INTERVAL sc.diascredito+1 DAY) THEN

	SUM(pg.total)

	END,0) AS carteramorosa

	FROM pagoguias pg

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	WHERE sc.estado='ACTIVADO' AND 

	sc.fechaactivacion<='" .cambiaf_a_mysql($_GET[fecha])."' AND pg.pagado='N'

	GROUP BY pg.sucursalacobrar,pg.guia)tabla 

	INNER JOIN catalogosucursal cs on tabla.sucursal=cs.id 

	LEFT JOIN 

	(SELECT sucursal,count(*)as contador from(SELECT pg.sucursalacobrar AS sucursal,pg.cliente 

	FROM pagoguias pg

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	WHERE sc.estado='ACTIVADO' AND sc.fechaactivacion<='" .cambiaf_a_mysql($_GET[fecha])."' AND pg.pagado='N'

	GROUP BY pg.sucursalacobrar,pg.cliente)Tabla group by sucursal)cl on tabla.sucursal=cl.sucursal

	GROUP BY tabla.sucursal";



		$r=mysql_query($sql,$l)or die($sql); 

		$registros= array();

		if (mysql_num_rows($r)>0){

				while ($f=mysql_fetch_object($r)){

				$f->nombresucursal=cambio_texto($f->nombresucursal);

				$registros[]=$f;	

				}

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo str_replace('null','""',json_encode(0));

		}

}else if ($_GET[accion]==2){

		$sql="SELECT CASE MONTH('".cambiaf_a_mysql($_GET[fecha])."') 

		WHEN 1 THEN 'Enero' 

		WHEN 2 THEN 'Febero'

		WHEN 3 THEN 'Marzo'

		WHEN 4 THEN 'Abril'

		WHEN 5 THEN 'Mayo'

		WHEN 6 THEN 'Junio'

		WHEN 7 THEN 'Julio'

		WHEN 8 THEN 'Agosto'

		WHEN 9 THEN 'Setiembre'

		WHEN 10 THEN 'Octubre'

		WHEN 11 THEN 'Noviembre'

		WHEN 12 THEN 'Diciembre'

		END AS mes,

		DATE_FORMAT(DATE_SUB('".cambiaf_a_mysql($_GET[fecha])."',INTERVAL (DAY('" .cambiaf_a_mysql($_GET[fecha])."')-1) DAY),'%d/%m/%Y') AS fechaini";

		$r=mysql_query($sql,$l)or die($sql); 

		$registros= array();

		

		if (mysql_num_rows($r)>0){

				while ($f=mysql_fetch_object($r)){

					$f->mes=cambio_texto($f->mes);

					$registros[]=$f;	

				}

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo str_replace('null','""',json_encode(0));

		}

}else if ($_GET[accion]==3){

		$tipo=$_GET[tipo];

		

		if ($tipo=="0"){

			$sql="SELECT DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,sucursal,referenciacargo,referenciaabono,cargo,abono,saldo,descripcion FROM 

(

	/*GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]' and gv.tipopago='CREDITO'

UNION

	/*GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM facturacion f

	INNER JOIN guiasempresariales gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND */ gv.factura<>0 AND f.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]' and gv.tipopago='CREDITO' AND f.total>0

	GROUP BY f.folio

UNION

	/*GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ 		

	ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	AND gv.condicionpago = 1

UNION

	/*GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, 

	f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM facturacion f

	INNER JOIN guiasventanilla gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	WHERE /*gv.fecha	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  

	AND */ gv.factura<>0 AND f.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	AND gv.condicionpago = 1 AND f.total>0

	GROUP BY f.folio	

UNION

	/*VENTA DE PREPAGADAS*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' PREP') AS referenciacargo,0 AS referenciaabono, 

	pg.total-(f.sobmontoafacturar+f.otrosmontofacturar) AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN solicitudguiasempresariales sf ON f.folio=sf.factura

	INNER JOIN pagoguias pg on f.folio = pg.guia and pg.tipo = 'FACT'

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]'

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO'  AND pg.total-(f.sobmontoafacturar+f.otrosmontofacturar)>0

UNION

	/*VALORES DECLARADOS Y SOBREPESO*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' SP.VD') AS referenciacargo,0 AS referenciaabono, 

	f.sobmontoafacturar AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN guiasempresariales ge ON f.folio=ge.factura AND (ge.texcedente>0 OR ge.tseguro>0)

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]' and f.sobmontoafacturar>0

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO' 

UNION

	/*VALORES OTROS*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' OTRO') AS referenciacargo,0 AS referenciaabono, 

	f.otrosmontofacturar AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]' and f.otrosmontofacturar>0

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO'

UNION

	/*ABONOS CLIENTE*/

	SELECT a.fecharegistro AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',a.folio) AS referenciaabono, 0 AS cargo, SUM(a.abonar) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(a.efectivo>0,'EFECTIVO',' '),',',IF(a.banco>0,cb.descripcion,' '),',',IF(a.cheque>0,concat('CHEQUE: ',a.cheque),' '),',',IF(a.tarjeta>0,'TARJETA',' '),',',IF(a.transferencia>0,'TRANSFERENCIA',' '))AS descripcion FROM abonodecliente a

	INNER JOIN catalogosucursal cs ON a.idsucursal=cs.id 

	LEFT JOIN catalogobanco as cb ON a.banco = cb.id

	WHERE /*a.fecharegistro BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ 

	a.idcliente='" .$_GET[cliente]. "' and a.idsucursal='$_GET[sucursal]' 

	GROUP BY a.factura

UNION

	/*ABONOS GUIAS A CONTADO removido para el reporte de cobranza*/

	/*SELECT fp.fecha AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',fp.guia) AS referenciaabono, 0 AS cargo, SUM(fp.total) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(fp.efectivo>0,'EFECTIVO',' '),',',IF(fp.banco>0,'BANCO',' '),',',IF(fp.cheque>0,'CHEQUE',' '),',',IF(fp.tarjeta>0,'TARJETA',' '),',',IF(fp.transferencia>0,'TRANSFERENCIA',' '))AS descripcion 

	FROM formapago fp

	INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 

	INNER JOIN pagoguias pg ON fp.guia=pg.guia

	WHERE  fp.procedencia='G' AND pg.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	GROUP BY fp.guia

UNION*/

	/*LIQUIDACION COBRANZA*/

	SELECT lc.fechaliquidacion AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',lc.folio) AS referenciaabono, 0 AS cargo, SUM(lcd.importe) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(lcd.efectivo>0,'EFECTIVO',' '),',',IF(lcd.banco>0,'BANCO',' '),',',IF(lcd.cheque>0,'CHEQUE',' '),',',IF(lcd.tarjeta>0,'TARJETA',' '),',',IF(lcd.transferencia>0,'TRANSFERENCIA',' '))AS descripcion 

	FROM liquidacioncobranza lc

	INNER JOIN liquidacioncobranzadetalle lcd ON lc.folio=lcd.folioliquidacion

	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

	WHERE /*lc.fechaliquidacion BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */  lcd.cliente='" .$_GET[cliente]. "' AND lcd.cobrar='SI' and lc.sucursal='$_GET[sucursal]'

	GROUP BY lcd.factura

UNION

	/*CANCELACION GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' AND gv.estado='CANCELADO' and pg.sucursalacobrar = '$_GET[sucursal]'

UNION

	/*CANCELACION GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' AND gv.estado='CANCELADO' and pg.sucursalacobrar = '$_GET[sucursal]'

)Tabla ORDER BY fecha, referenciaabono,referenciacargo LIMIT ".$_GET[inicio].",30";

		$r=mysql_query($sql,$l)or die($sql); 

}else if ($tipo=="1"){

			$sql="SELECT DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,sucursal,referenciacargo,referenciaabono,cargo,abono,saldo,descripcion FROM 

(

	/*GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]' and gv.tipopago='CREDITO'

UNION

	/*GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM facturacion f

	INNER JOIN guiasempresariales gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND */ gv.factura<>0 AND f.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]' and gv.tipopago='CREDITO' AND f.total>0

	GROUP BY f.folio

UNION

	/*GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ 		

	ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	AND gv.condicionpago = 1

UNION

	/*GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, 

	f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM facturacion f

	INNER JOIN guiasventanilla gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	WHERE /*gv.fecha	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  

	AND */ gv.factura<>0 AND f.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	AND gv.condicionpago = 1 AND f.total>0

	GROUP BY f.folio	

UNION

	/*VENTA DE PREPAGADAS*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' PREP') AS referenciacargo,0 AS referenciaabono, 

	pg.total-(f.sobmontoafacturar+f.otrosmontofacturar) AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN solicitudguiasempresariales sf ON f.folio=sf.factura

	INNER JOIN pagoguias pg on f.folio = pg.guia and pg.tipo = 'FACT'

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]'

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO'  AND pg.total-(f.sobmontoafacturar+f.otrosmontofacturar)>0

UNION

	/*VALORES DECLARADOS Y SOBREPESO*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' SP.VD') AS referenciacargo,0 AS referenciaabono, 

	f.sobmontoafacturar AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN guiasempresariales ge ON f.folio=ge.factura AND (ge.texcedente>0 OR ge.tseguro>0)

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]' and f.sobmontoafacturar>0

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO' 

UNION

	/*VALORES OTROS*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' OTRO') AS referenciacargo,0 AS referenciaabono, 

	f.otrosmontofacturar AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]' and f.otrosmontofacturar>0

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO'

UNION

	/*ABONOS CLIENTE*/

	SELECT a.fecharegistro AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',a.folio) AS referenciaabono, 0 AS cargo, SUM(a.abonar) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(a.efectivo>0,'EFECTIVO',' '),',',IF(a.banco>0,cb.descripcion,' '),',',IF(a.cheque>0,concat('CHEQUE: ',a.cheque),' '),',',IF(a.tarjeta>0,'TARJETA',' '),',',IF(a.transferencia>0,'TRANSFERENCIA',' '))AS descripcion FROM abonodecliente a

	INNER JOIN catalogosucursal cs ON a.idsucursal=cs.id 

	LEFT JOIN catalogobanco as cb ON a.banco = cb.id

	WHERE /*a.fecharegistro BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ 

	a.idcliente='" .$_GET[cliente]. "' and a.idsucursal='$_GET[sucursal]' 

	GROUP BY a.factura

UNION

	/*ABONOS GUIAS A CONTADO removido para el reporte de cobranza*/

	/*SELECT fp.fecha AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',fp.guia) AS referenciaabono, 0 AS cargo, SUM(fp.total) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(fp.efectivo>0,'EFECTIVO',' '),',',IF(fp.banco>0,'BANCO',' '),',',IF(fp.cheque>0,'CHEQUE',' '),',',IF(fp.tarjeta>0,'TARJETA',' '),',',IF(fp.transferencia>0,'TRANSFERENCIA',' '))AS descripcion 

	FROM formapago fp

	INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 

	INNER JOIN pagoguias pg ON fp.guia=pg.guia

	WHERE  fp.procedencia='G' AND pg.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	GROUP BY fp.guia

UNION*/

	/*LIQUIDACION COBRANZA*/

	SELECT lc.fechaliquidacion AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',lc.folio) AS referenciaabono, 0 AS cargo, SUM(lcd.importe) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(lcd.efectivo>0,'EFECTIVO',' '),',',IF(lcd.banco>0,'BANCO',' '),',',IF(lcd.cheque>0,'CHEQUE',' '),',',IF(lcd.tarjeta>0,'TARJETA',' '),',',IF(lcd.transferencia>0,'TRANSFERENCIA',' '))AS descripcion 

	FROM liquidacioncobranza lc

	INNER JOIN liquidacioncobranzadetalle lcd ON lc.folio=lcd.folioliquidacion

	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

	WHERE /*lc.fechaliquidacion BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */  lcd.cliente='" .$_GET[cliente]. "' AND lcd.cobrar='SI' and lc.sucursal='$_GET[sucursal]'

	GROUP BY lcd.factura

UNION

	/*CANCELACION GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' AND gv.estado='CANCELADO' and pg.sucursalacobrar = '$_GET[sucursal]'

UNION

	/*CANCELACION GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' AND gv.estado='CANCELADO' and pg.sucursalacobrar = '$_GET[sucursal]'

)Tabla ORDER BY fecha, referenciaabono,referenciacargo LIMIT ".$_GET[inicio].",30";

		$r=mysql_query($sql,$l)or die($sql); 

		

		}

		

		$registros= array();

		if (mysql_num_rows($r)>0){

				while ($f=mysql_fetch_object($r)){

					$f->sucursal=cambio_texto($f->sucursal);

					$f->descripcion=cambio_texto($f->descripcion);

					$registros[]=$f;	

				}

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo "0";

		}

}else if ($_GET[accion]==4){

$sql="SELECT cc.id,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,CONCAT(d.calle,' ',d.numero,'',d.colonia,' ',d.cp)AS direccion,cc.rfc,d.municipio AS ciudad,d.estado FROM catalogocliente cc

INNER JOIN direccion d ON  cc.id=d.codigo AND d.origen='cl'

WHERE cc.id='".$_GET[cliente]."'";

		$r=mysql_query($sql,$l)or die($sql); 

				$registros= array();

				if (mysql_num_rows($r)>0){

						while ($f=mysql_fetch_object($r))

						{

						$f->cliente=cambio_texto($f->cliente);

						$f->direccion=cambio_texto($f->direccion);

						$f->ciudad=cambio_texto($f->ciudad);

						$f->estado=cambio_texto($f->estado);

						$registros[]=$f;	

						}

						echo str_replace('null','""',json_encode($registros));

				}else{

					echo str_replace('null','""',json_encode(0));

				}

}else if ($_GET[accion]==5){

			$cliente=$_GET[cliente];

			if ($cliente!=""){

				$concatenacion = " AND cc.id=$cliente";

			}else{

				$concatenacion = "";

			}

			$sucursal=$_GET[sucursal];

			if ($sucursal!=""){

				$concatenacion2 = " AND cs.id=$sucursal";

			}else{

				$concatenacion2 = "";

			}

			$tipo=$_GET[tipo];

		

	if ($tipo=="0"){

		$sql="SELECT clavecliente,cliente,sucursal,nombresucursal,guiafactura,DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,DATE_FORMAT(fechavencimiento,'%d/%m/%Y')AS fechavencimiento,diasvencimiento,corriente,
dias1,dias2,dias3,dias4,saldo,factura,contrarecibo 
FROM (
	/*GUIAS VENTANILLA NO FACTURADAS*/
	(SELECT cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.id AS guiafactura,
	gv.fecha AS fecha,DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento, 
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',gv.fecha)<=cc.diascredito,gv.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',gv.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',gv.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',gv.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>60,gv.total,0)AS dias4,
	gv.total AS saldo,
	0 AS factura,
	0 AS contrarecibo 
	FROM guiasventanilla gv
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND*/ pg.pagado='N'
	AND ISNULL(gv.factura) AND sc.estado='ACTIVADO'  AND cs.id='$_GET[sucursal]')
UNION
	/*GUIAS EMPRESARIALES NO FACTURADAS*/
	(SELECT cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.id AS guiafactura,
	gv.fecha AS fecha,DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento, 
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',gv.fecha)<=cc.diascredito,gv.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',gv.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',gv.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',gv.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>60,gv.total,0)AS dias4,
	gv.total AS saldo,
	0 AS factura,
	0 AS contrarecibo 
	FROM guiasempresariales gv
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND */ pg.pagado='N'
	AND ISNULL(gv.factura) AND sc.estado='ACTIVADO'  AND cs.id='$_GET[sucursal]')
UNION
	/*GUIAS VENTANILLA FACTURADAS*/
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	f.total-ifnull(fpg.total,0) AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  
	FROM facturacion f
	INNER JOIN guiasventanilla gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	LEFT JOIN pagoguias as fpg on f.folio = fpg.guia and fpg.tipo = 'FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN 
	(SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
	INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
	WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO'  AND cs.id='$_GET[sucursal]' 
	GROUP BY lcd.factura)cr 
	ON f.folio=cr.factura
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND*/ pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND gv.factura<>0  AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
UNION
	/*GUIAS EMPRESARIALES FACTURADAS*/
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	f.total-ifnull(fpg.total,0) AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  
	FROM facturacion f
	INNER JOIN guiasempresariales gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	LEFT JOIN pagoguias as fpg on f.folio = fpg.guia and fpg.tipo = 'FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN 
	(SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
	INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
	WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO'  AND cs.id='$_GET[sucursal]' 
	GROUP BY lcd.factura
	)cr 
	ON f.folio=cr.factura
	WHERE pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND not isnull(gv.factura) AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
UNION
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC.OTROS-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	pg.total AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  

	FROM facturacion f
	INNER JOIN solicitudguiasempresariales sg ON f.folio=sg.factura
	INNER JOIN pagoguias pg ON f.folio=pg.guia and pg.tipo='FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN (
		SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
		INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
		INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
		WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO' AND cs.id='$_GET[sucursal]' 
		GROUP BY lcd.factura
	)cr 
	ON f.folio=cr.factura
	WHERE pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND not isnull(sg.factura) AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
)Tabla ORDER BY clavecliente,fecha LIMIT ".$_GET[inicio].",30";

	$r=mysql_query($sql,$l)or die($sql); 

	

	}else if ($tipo=="1"){

		$sql="SELECT clavecliente,cliente,sucursal,nombresucursal,guiafactura,DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,DATE_FORMAT(fechavencimiento,'%d/%m/%Y')AS fechavencimiento,diasvencimiento,corriente,
dias1,dias2,dias3,dias4,saldo,factura,contrarecibo 
FROM (
	/*GUIAS VENTANILLA NO FACTURADAS*/
	(SELECT cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.id AS guiafactura,
	gv.fecha AS fecha,DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento, 
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',gv.fecha)<=cc.diascredito,gv.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',gv.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',gv.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',gv.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>60,gv.total,0)AS dias4,
	gv.total AS saldo,
	0 AS factura,
	0 AS contrarecibo 
	FROM guiasventanilla gv
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND*/ pg.pagado='N'
	AND ISNULL(gv.factura) AND sc.estado='ACTIVADO'  AND cs.id='$_GET[sucursal]')
UNION
	/*GUIAS EMPRESARIALES NO FACTURADAS*/
	(SELECT cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.id AS guiafactura,
	gv.fecha AS fecha,DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento, 
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',gv.fecha)<=cc.diascredito,gv.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',gv.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',gv.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',gv.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>60,gv.total,0)AS dias4,
	gv.total AS saldo,
	0 AS factura,
	0 AS contrarecibo 
	FROM guiasempresariales gv
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND */ pg.pagado='N'
	AND ISNULL(gv.factura) AND sc.estado='ACTIVADO'  AND cs.id='$_GET[sucursal]')
UNION
	/*GUIAS VENTANILLA FACTURADAS*/
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	f.total-ifnull(fpg.total,0) AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  
	FROM facturacion f
	INNER JOIN guiasventanilla gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	LEFT JOIN pagoguias as fpg on f.folio = fpg.guia and fpg.tipo = 'FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN 
	(SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
	INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
	WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO'  AND cs.id='$_GET[sucursal]' 
	GROUP BY lcd.factura)cr 
	ON f.folio=cr.factura
	WHERE /*pg.fechacreo BETWEEN '2010-01-01' and '".cambiaf_a_mysql($_GET[fecha2])."' AND*/ pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND gv.factura<>0  AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
UNION
	/*GUIAS EMPRESARIALES FACTURADAS*/
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	f.total-ifnull(fpg.total,0) AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  
	FROM facturacion f
	INNER JOIN guiasempresariales gv ON f.folio=gv.factura
	INNER JOIN pagoguias pg ON gv.id=pg.guia
	LEFT JOIN pagoguias as fpg on f.folio = fpg.guia and fpg.tipo = 'FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN 
	(SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
	INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
	WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO'  AND cs.id='$_GET[sucursal]' 
	GROUP BY lcd.factura
	)cr 
	ON f.folio=cr.factura
	WHERE pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND not isnull(gv.factura) AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
UNION
	(SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,
	cs.id AS sucursal,cs.prefijo AS nombresucursal,CONCAT('FAC.OTROS-',f.folio) AS guiafactura,
	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 
	cc.diascredito AS diasvencimiento,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,
	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,
	pg.total AS saldo,
	f.folio AS factura,
	IFNULL(cr.contrarecibo,0) AS contrarecibo  

	FROM facturacion f
	INNER JOIN solicitudguiasempresariales sg ON f.folio=sg.factura
	INNER JOIN pagoguias pg ON f.folio=pg.guia and pg.tipo='FACT'
	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente
	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id
	INNER JOIN catalogocliente cc ON pg.cliente=cc.id
	LEFT JOIN (
		SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 
		INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 
		INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id
		WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO' AND cs.id='$_GET[sucursal]' 
		GROUP BY lcd.factura
	)cr 
	ON f.folio=cr.factura
	WHERE pg.pagado='N' 
	AND sc.estado='ACTIVADO'
	AND not isnull(sg.factura) AND cs.id='$_GET[sucursal]'
	GROUP BY f.folio)
)Tabla ORDER BY clavecliente,fecha LIMIT ".$_GET[inicio].",30";
	$r=mysql_query($sql,$l)or die($sql); 
	}
	$registros= array();
	if (mysql_num_rows($r)>0){
			while ($f=mysql_fetch_object($r)){
				$f->cliente=cambio_texto($f->cliente);
				$f->nombresucursal=cambio_texto($f->nombresucursal);
				$registros[]=$f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
	}

}else if ($_GET[accion]==6){

		$sql="SELECT cc.id AS cliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS nombrecliente,sc.montoautorizado,cc.diascredito,cc.diarevision AS fecharevision,cc.diapago AS fechapago,IFNULL(d.dias,0) AS rotacioncartera FROM pagoguias pg

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	INNER JOIN catalogocliente cc ON pg.cliente=cc.id

	LEFT JOIN

	(

	SELECT cliente,IFNULL(SUM(rotacioncartera) / COUNT(guia),0)AS dias FROM 

	(

	SELECT pg.cliente,pg.guia,IFNULL(DATEDIFF(pg.fechapago,pg.fechacreo),0) AS rotacioncartera FROM 

	pagoguias pg

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	WHERE sc.estado='ACTIVADO' AND sc.fechaactivacion<='" .cambiaf_a_mysql($_GET[fechafin])."' AND 

	pg.sucursalacobrar='" .$_GET[sucursal]."' AND IFNULL(DATEDIFF(pg.fechapago,pg.fechacreo),0)<>0

	)tabla GROUP BY cliente

	)d ON cc.id=d.cliente

	WHERE sc.estado='ACTIVADO' AND sc.fechaactivacion<='" .cambiaf_a_mysql($_GET[fechafin])."' AND 

	pg.sucursalacobrar='" .$_GET[sucursal]."'	GROUP BY cc.id ORDER BY cc.id LIMIT ".$_GET[inicio].",30";

	$r=mysql_query($sql,$l)or die($sql); 

	$registros= array();	

		if (mysql_num_rows($r)>0){

			while ($f=mysql_fetch_object($r))

			{

			$f->nombrecliente=cambio_texto($f->nombrecliente);

			$registros[]=$f;	

			}

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo "0";

		}

}else if ($_GET[accion]==7){

$sql="SELECT DATE_FORMAT(sc.fechaautorizacion,'%d/%m/%Y') AS fechacredito,sc.montoautorizado,cu.Nombre AS modifico,sc.folio AS solicitud 

FROM solicitudcredito sc 

INNER JOIN catalogousuario cu ON sc.idusuario=cu.Id

WHERE sc.cliente='" .$_GET[cliente]."' LIMIT ".$_GET[inicio].",30";

	$r=mysql_query($sql,$l)or die($sql); 

	$registros= array();	

		if (mysql_num_rows($r)>0){

			while ($f=mysql_fetch_object($r))

			{

			$f->modifico=cambio_texto($f->modifico);

			$registros[]=$f;	

			}

			echo str_replace('null','""',json_encode($registros));

		}else{

			echo "0";

		}

}else if ($_GET[accion]==8){

		$cliente=$_GET[cliente];

			if ($cliente!=""){

				$concatenacion = " AND cc.id=$cliente";

			}else{

				$concatenacion = "";

			}

			$sucursal=$_GET[sucursal];

			if ($sucursal!=""){

				$concatenacion2 = " AND cs.id=$sucursal";

			}else{

				$concatenacion2 = "";

			}
		
		
		$sql="SELECT clavecliente,cliente,sucursal,nombresucursal,guiafactura,DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,DATE_FORMAT(fechavencimiento,'%d/%m/%Y')AS fechavencimiento,diasvencimiento,corriente,

dias1,dias2,dias3,dias4,saldo,factura,contrarecibo 

FROM (

	/*GUIAS VENTANILLA NO FACTURADAS*/

	SELECT cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,

	cs.id AS sucursal,cs.descripcion AS nombresucursal,gv.id AS guiafactura,

	gv.fecha AS fecha,DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 

	cc.diascredito AS diasvencimiento, 

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',gv.fecha)<=cc.diascredito,gv.total,0)AS corriente,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',gv.total,0)AS dias1,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',gv.total,0)AS dias2,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',gv.total,0)AS dias3,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>60,gv.total,0)AS dias4,

	gv.total AS saldo,

	0 AS factura,

	0 AS contrarecibo 

	FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id

	INNER JOIN catalogocliente cc ON pg.cliente=cc.id

	WHERE pg.fechacreo BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 

	AND sc.estado='ACTIVADO'

	AND ISNULL(gv.factura) $concatenacion2 $concatenacion

UNION

	/*GUIAS EMPRESARIALES NO FACTURADAS*/

	SELECT cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,

	cs.id AS sucursal,cs.descripcion AS nombresucursal,gv.id AS guiafactura,

	gv.fecha AS fecha,DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 

	cc.diascredito AS diasvencimiento, 

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',gv.fecha)<=cc.diascredito,gv.total,0)AS corriente,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',gv.total,0)AS dias1,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',gv.total,0)AS dias2,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',gv.total,0)AS dias3,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>60,gv.total,0)AS dias4,

	gv.total AS saldo,

	0 AS factura,

	0 AS contrarecibo 

	FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id

	INNER JOIN catalogocliente cc ON pg.cliente=cc.id

	WHERE pg.fechacreo BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 

	AND sc.estado='ACTIVADO'

	AND ISNULL(gv.factura) $concatenacion2 $concatenacion

UNION

	/*GUIAS VENTANILLA FACTURADAS*/

	SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,

	cs.id AS sucursal,cs.descripcion AS nombresucursal,CONCAT('FAC-',f.folio) AS guiafactura,

	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 

	cc.diascredito AS diasvencimiento,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,

	f.total AS saldo,

	f.folio AS factura,

	IFNULL(cr.contrarecibo,0) AS contrarecibo  

	FROM facturacion f

	INNER JOIN guiasventanilla gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id

	INNER JOIN catalogocliente cc ON pg.cliente=cc.id

	LEFT JOIN 

	(SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 

	INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 

	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

	WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO' $concatenacion2 GROUP BY lcd.factura)cr 

	ON f.folio=cr.factura

	WHERE pg.fechacreo BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 

	AND sc.estado='ACTIVADO'

	AND gv.factura<>0 $concatenacion2 $concatenacion

	GROUP BY f.folio

UNION

	/*GUIAS EMPRESARIALES FACTURADAS*/

	SELECT  cc.id AS clavecliente,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,

	cs.id AS sucursal,cs.descripcion AS nombresucursal,CONCAT('FAC-',f.folio) AS guiafactura,

	f.fecha AS fecha,DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY) AS fechavencimiento, 

	cc.diascredito AS diasvencimiento,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',f.fecha)<=cc.diascredito,f.total,0)AS corriente,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '1' AND '15',f.total,0)AS dias1,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '16' AND '30',f.total,0)AS dias2,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY)) BETWEEN '31' AND '60',f.total,0)AS dias3,

	IF (DATEDIFF('".cambiaf_a_mysql($_GET[fecha2])."',DATE_ADD(f.fecha,INTERVAL cc.diascredito DAY))>60,f.total,0)AS dias4,

	f.total AS saldo,

	f.folio AS factura,

	IFNULL(cr.contrarecibo,0) AS contrarecibo  

	FROM facturacion f

	INNER JOIN guiasempresariales gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN solicitudcredito sc ON pg.cliente=sc.cliente

	INNER JOIN catalogosucursal cs ON pg.sucursalacobrar=cs.id

	INNER JOIN catalogocliente cc ON pg.cliente=cc.id

	LEFT JOIN 

	(SELECT lcd.factura,lcd.contrarecibo FROM liquidacioncobranzadetalle lcd 

	INNER JOIN liquidacioncobranza lc ON lc.folio=lcd.folioliquidacion 

	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

	WHERE lcd.contrarecibo<>0 AND lcd.cobrar='NO' $concatenacion2 GROUP BY lcd.factura)cr 

	ON f.folio=cr.factura

	WHERE pg.fechacreo BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 

	AND sc.estado='ACTIVADO'

	AND gv.factura<>0 $concatenacion2 $concatenacion

	GROUP BY f.folio)Tabla ORDER BY clavecliente,fecha";

	$t = mysql_query($sql,$l) or die($sql);

	$tdes = mysql_num_rows($t);

	echo $tdes;

}else if ($_GET[accion]==9){

		$sql="SELECT DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,sucursal,referenciacargo,referenciaabono,cargo,abono,saldo,descripcion FROM 

(

	/*GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]' and gv.tipopago='CREDITO'

UNION

	/*GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM facturacion f

	INNER JOIN guiasempresariales gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND */ gv.factura<>0 AND f.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]' and gv.tipopago='CREDITO' AND f.total>0

	GROUP BY f.folio

UNION

	/*GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,gv.id AS referenciacargo,0 AS referenciaabono, gv.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ 		

	ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	AND gv.condicionpago = 1

UNION

	/*GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,CONCAT('FAC-','',f.folio) AS referenciacargo,0 AS referenciaabono, 

	f.total AS cargo, 0 AS abono,0 AS saldo,'Guia Crédito Foraneo' AS descripcion FROM facturacion f

	INNER JOIN guiasventanilla gv ON f.folio=gv.factura

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	WHERE /*gv.fecha	BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  

	AND */ gv.factura<>0 AND f.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	AND gv.condicionpago = 1 AND f.total>0

	GROUP BY f.folio	

UNION

	/*VENTA DE PREPAGADAS*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' PREP') AS referenciacargo,0 AS referenciaabono, 

	pg.total-(f.sobmontoafacturar+f.otrosmontofacturar) AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN solicitudguiasempresariales sf ON f.folio=sf.factura

	INNER JOIN pagoguias pg on f.folio = pg.guia and pg.tipo = 'FACT'

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]'

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO'  AND pg.total-(f.sobmontoafacturar+f.otrosmontofacturar)>0

UNION

	/*VALORES DECLARADOS Y SOBREPESO*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' SP.VD') AS referenciacargo,0 AS referenciaabono, 

	f.sobmontoafacturar AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN guiasempresariales ge ON f.folio=ge.factura AND (ge.texcedente>0 OR ge.tseguro>0)

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]' and f.sobmontoafacturar>0

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO' 

UNION

	/*VALORES OTROS*/

	SELECT f.fecha, cs.prefijo, CONCAT('FAC-',f.folio,' OTRO') AS referenciacargo,0 AS referenciaabono, 

	f.otrosmontofacturar AS cargo, 0 AS abono,0 AS saldo, 'Guia Crédito Foraneo' AS descripcion 

	from facturacion f

	INNER JOIN catalogosucursal cs ON cs.id= f.idsucursal	

	WHERE f.cliente='$_GET[cliente]' and f.idsucursal = '$_GET[sucursal]' and f.otrosmontofacturar>0

	AND f.credito = 'SI' and f.facturaestado = 'GUARDADO'

UNION

	/*ABONOS CLIENTE*/

	SELECT a.fecharegistro AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',a.folio) AS referenciaabono, 0 AS cargo, SUM(a.abonar) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(a.efectivo>0,'EFECTIVO',' '),',',IF(a.banco>0,cb.descripcion,' '),',',IF(a.cheque>0,concat('CHEQUE: ',a.cheque),' '),',',IF(a.tarjeta>0,'TARJETA',' '),',',IF(a.transferencia>0,'TRANSFERENCIA',' '))AS descripcion FROM abonodecliente a

	INNER JOIN catalogosucursal cs ON a.idsucursal=cs.id 

	LEFT JOIN catalogobanco as cb ON a.banco = cb.id

	WHERE /*a.fecharegistro BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ 

	a.idcliente='" .$_GET[cliente]. "' and a.idsucursal='$_GET[sucursal]' 

	GROUP BY a.factura

UNION

	/*ABONOS GUIAS A CONTADO removido para el reporte de cobranza*/

	/*SELECT fp.fecha AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',fp.guia) AS referenciaabono, 0 AS cargo, SUM(fp.total) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(fp.efectivo>0,'EFECTIVO',' '),',',IF(fp.banco>0,'BANCO',' '),',',IF(fp.cheque>0,'CHEQUE',' '),',',IF(fp.tarjeta>0,'TARJETA',' '),',',IF(fp.transferencia>0,'TRANSFERENCIA',' '))AS descripcion 

	FROM formapago fp

	INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 

	INNER JOIN pagoguias pg ON fp.guia=pg.guia

	WHERE  fp.procedencia='G' AND pg.cliente='" .$_GET[cliente]. "' and pg.sucursalacobrar = '$_GET[sucursal]'

	GROUP BY fp.guia

UNION*/

	/*LIQUIDACION COBRANZA*/

	SELECT lc.fechaliquidacion AS fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('ABONO-','',lc.folio) AS referenciaabono, 0 AS cargo, SUM(lcd.importe) AS abono,0 AS saldo,

	CONCAT('PAGO',' ',IF(lcd.efectivo>0,'EFECTIVO',' '),',',IF(lcd.banco>0,'BANCO',' '),',',IF(lcd.cheque>0,'CHEQUE',' '),',',IF(lcd.tarjeta>0,'TARJETA',' '),',',IF(lcd.transferencia>0,'TRANSFERENCIA',' '))AS descripcion 

	FROM liquidacioncobranza lc

	INNER JOIN liquidacioncobranzadetalle lcd ON lc.folio=lcd.folioliquidacion

	INNER JOIN catalogosucursal cs ON lc.sucursal=cs.id

	WHERE /*lc.fechaliquidacion BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */  lcd.cliente='" .$_GET[cliente]. "' AND lcd.cobrar='SI' and lc.sucursal='$_GET[sucursal]'

	GROUP BY lcd.factura

UNION

	/*CANCELACION GUIAS VENTANILLA*/

	SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion FROM guiasventanilla gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' AND gv.estado='CANCELADO' and pg.sucursalacobrar = '$_GET[sucursal]'

UNION

	/*CANCELACION GUIAS EMPRESARIALES*/

	SELECT gv.fecha,cs.prefijo AS sucursal,0 AS referenciacargo,CONCAT('CANCELACION-','',gv.id)AS referenciaabono,0 AS cargo, gv.total AS abono,0 AS saldo,'Cancelacion de Guia' AS descripcion FROM guiasempresariales gv

	INNER JOIN pagoguias pg ON gv.id=pg.guia

	INNER JOIN catalogosucursal cs ON cs.id= pg.sucursalacobrar	

	INNER JOIN catalogocliente cc ON cc.id= pg.cliente

	WHERE /*gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  AND */ ISNULL(gv.factura) AND cc.id='" .$_GET[cliente]. "' AND gv.estado='CANCELADO' and pg.sucursalacobrar = '$_GET[sucursal]'

)Tabla ORDER BY fecha, referenciaabono,referenciacargo";
	
	
	$t = mysql_query($sql,$l) or die($sql);

	$tdes = mysql_num_rows($t);

	echo $tdes;

}

?>