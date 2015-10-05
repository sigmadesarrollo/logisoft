<? session_start();
	require_once("../../Conectar.php");
	$l=Conectarse('webpmm');
	if ($_GET[accion]==1){

$sql="SELECT  IngresosFormadeCobro.sucursal,cs.prefijo AS nombresucursal,SUM(IngresosFormadeCobro.contado) AS contado,SUM(IngresosFormadeCobro.cobranza)AS cobranza,SUM(IngresosFormadeCobro.entregadas)AS entregadas,(SUM(IngresosFormadeCobro.contado)+SUM(IngresosFormadeCobro.cobranza)+SUM(IngresosFormadeCobro.entregadas))AS total,
0 AS depositado,(SUM(IngresosFormadeCobro.contado)+SUM(IngresosFormadeCobro.cobranza)+SUM(IngresosFormadeCobro.entregadas)) AS saldo FROM (
	/*GUIAS VENTANILLA Y EMPRESARIALES*/
	SELECT sucursal,0 AS cobranza,SUM(total) AS contado,0 AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('G') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal	
UNION 
	/*LIQUIDACION COBRANZA Y ABONOS*/
	SELECT sucursal,SUM(total)AS cobranza,0 AS contado,0 AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('A','C') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal	
UNION 
	UNION 
	/*LIQUIDACION MERCANCIA Y ENTREGAS OCURRE (ENTREGADAS)*/
	SELECT sucursal,0 AS cobranza,0 AS contado,SUM(total) AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('M') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal
UNION 
	/*LIQUIDACION MERCANCIA Y ENTREGAS OCURRE (ENTREGADAS)*/
	SELECT fp.sucursal,0 AS cobranza,0 AS contado, sum(gv.total) AS entregadas 
	FROM formapago fp
	INNER JOIN entregasocurre eo ON fp.guia=eo.folio
	INNER JOIN entregasocurre_detalle ed ON eo.folio=ed.entregaocurre	
	INNER JOIN guiasventanilla gv ON ed.guia=gv.id	
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND fp.procedencia='O' and gv.tipoflete=1 and gv.condicionpago=0
	AND isnull(fp.fechacancelacion) GROUP BY cs.id
UNION 
	/*FACTURACION*/
	SELECT formapago.sucursal, SUM(if(f.credito='SI',formapago.total,0)) AS cobranza, SUM(if(f.credito='SI',0,formapago.total)) AS contado,0 AS entregadas 
	FROM formapago 
	inner join facturacion f on formapago.guia = f.folio
	WHERE formapago.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND formapago.procedencia IN('F') 
	and isnull(formapago.fechacancelacion)
	GROUP BY formapago.sucursal
)IngresosFormadeCobro 
INNER JOIN catalogosucursal cs ON IngresosFormadeCobro.sucursal=cs.id
WHERE IngresosFormadeCobro.sucursal<>''
GROUP BY IngresosFormadeCobro.sucursal LIMIT ".$_GET[inicio].",30";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$f->nombresucursal=cambio_texto($f->nombresucursal);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
}
if ($_GET[accion]==2){

$sql="SELECT  IngresosFormadeCobro.sucursal,cs.prefijo AS nombresucursal,SUM(IngresosFormadeCobro.contado) AS contado,SUM(IngresosFormadeCobro.cobranza)AS cobranza,SUM(IngresosFormadeCobro.entregadas)AS entregadas,(SUM(IngresosFormadeCobro.contado)+SUM(IngresosFormadeCobro.cobranza)+SUM(IngresosFormadeCobro.entregadas))AS total,
0 AS depositado,(SUM(IngresosFormadeCobro.contado)+SUM(IngresosFormadeCobro.cobranza)+SUM(IngresosFormadeCobro.entregadas)) AS saldo FROM (
	/*GUIAS VENTANILLA Y EMPRESARIALES*/
	SELECT sucursal,0 AS cobranza,SUM(total) AS contado,0 AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('G') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal		
UNION 
	/*LIQUIDACION COBRANZA Y ABONOS*/
	SELECT sucursal,SUM(total)AS cobranza,0 AS contado,0 AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('A','C') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal	
UNION 
	/*LIQUIDACION MERCANCIA Y ENTREGAS OCURRE (ENTREGADAS)*/
	SELECT sucursal,0 AS cobranza,0 AS contado,SUM(total) AS entregadas 
	FROM formapago WHERE fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND procedencia IN('M') 
	and isnull(formapago.fechacancelacion)
	GROUP BY sucursal
UNION 
	/*LIQUIDACION MERCANCIA Y ENTREGAS OCURRE (ENTREGADAS)*/
	SELECT fp.sucursal,0 AS cobranza,0 AS contado, sum(gv.total) AS entregadas 
	FROM formapago fp
	INNER JOIN entregasocurre eo ON fp.guia=eo.folio
	INNER JOIN entregasocurre_detalle ed ON eo.folio=ed.entregaocurre	
	INNER JOIN guiasventanilla gv ON ed.guia=gv.id	
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND fp.procedencia='O' and gv.tipoflete=1 and gv.condicionpago=0
	AND isnull(fp.fechacancelacion) GROUP BY cs.id
UNION 
	/*FACTURACION*/
	SELECT formapago.sucursal, SUM(if(f.credito='SI',formapago.total,0)) AS cobranza, SUM(if(f.credito='SI',0,formapago.total)) AS contado,0 AS entregadas 
	FROM formapago 
	inner join facturacion f on formapago.guia = f.folio
	WHERE formapago.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	AND formapago.procedencia IN('F') 
	and isnull(formapago.fechacancelacion)
	GROUP BY formapago.sucursal
)IngresosFormadeCobro 
INNER JOIN catalogosucursal cs ON IngresosFormadeCobro.sucursal=cs.id
WHERE IngresosFormadeCobro.sucursal<>''
GROUP BY IngresosFormadeCobro.sucursal LIMIT 0,30
";
	$t = mysql_query($sql,$l) or die($sql);
	$tdes = mysql_num_rows($t);
	echo $tdes;
}

if ($_GET[accion]==3){

$sql="SELECT sucursal,nombresucursal,fecha,guia,cliente,importe,caja FROM (
	SELECT cs.id AS sucursal,cs.prefijo as nombresucursal,DATE_FORMAT(gv.fecha,'%d/%m/%Y')AS fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp
	INNER JOIN guiasventanilla gv ON fp.guia=gv.id
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND fp.procedencia IN('G') AND cs.id='" .$_GET[sucursal]."'
UNION ALL
	SELECT cs.id AS sucursal,cs.prefijo as nombresucursal,gv.fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp
	INNER JOIN guiasempresariales gv ON fp.guia=gv.id
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestino)		
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND fp.procedencia IN('E') AND cs.id='" .$_GET[sucursal]."'
)Tabla ORDER BY fecha LIMIT ".$_GET[inicio].",30";	
	$r=mysql_query($sql,$l)or die($sql); 
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
}

if ($_GET[accion]==4){
	$sql="SELECT sucursal,nombresucursal,DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,guia,cliente,importe,caja FROM (

		SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,lcd.fechaguia AS fecha,lcd.guia,

		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,lcd.importe,

		lcd.idusuario AS caja 

		FROM formapago fp

		INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura

		INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio

		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id

		INNER JOIN catalogocliente cc ON lcd.cliente=cc.id		

		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND fp.procedencia='C' AND cs.id='" .$_GET[sucursal]."' group by lcd.guia
	UNION 

		SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.fecha,

		gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,

		gv.total AS importe,gv.idusuario AS caja FROM formapago fp 

		INNER JOIN abonodecliente a ON fp.guia=a.folio

		INNER JOIN guiasventanilla gv ON a.factura=gv.factura		

		LEFT JOIN catalogosucursal cs ON fp.sucursal=cs.id 

		LEFT JOIN catalogocliente cc ON a.idcliente=cc.id

		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND fp.procedencia='A' AND cs.id='" .$_GET[sucursal]."' GROUP BY gv.id

	UNION 

		SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp

		INNER JOIN guiasempresariales gv ON fp.guia=gv.id 

		INNER JOIN abonodecliente a ON gv.factura=a.factura		

		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 

		INNER JOIN catalogocliente cc ON a.idcliente=cc.id

		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND fp.procedencia='G' AND fp.tipo='E' AND cs.id='" .$_GET[sucursal]."' GROUP BY gv.id

	)Tabla GROUP BY guia ORDER BY fecha,guia LIMIT ".$_GET[inicio].",30";
	$r=mysql_query($sql,$l)or die($sql); 
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
}

if ($_GET[accion]==5){
	$sql="SELECT sucursal,nombresucursal,DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,guia,cliente,importe,caja 
FROM (
		SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp

		INNER JOIN entregasocurre eo ON fp.guia=eo.folio

		INNER JOIN entregasocurre_detalle ed ON eo.folio=ed.entregaocurre	

		INNER JOIN guiasventanilla gv ON ed.guia=gv.id	

		INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal

		INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)

		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND fp.procedencia='O' AND cs.id='" .$_GET[sucursal]."' GROUP BY gv.id

	UNION
		SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,gv.fecha,gv.id AS guia,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja FROM formapago fp

		INNER JOIN entregasocurre eo ON fp.guia=eo.folio

		INNER JOIN entregasocurre_detalle ed ON eo.folio=ed.entregaocurre	

		INNER JOIN guiasventanilla gv ON ed.guia=gv.id	

		INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal

		INNER JOIN catalogocliente cc ON cc.id   = IF (gv.tipoflete=0,gv.idremitente,gv.iddestinatario)

		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' AND fp.procedencia='M' AND cs.id='" .$_GET[sucursal]."' GROUP BY gv.id
)Tabla GROUP BY guia ORDER BY fecha,guia LIMIT ".$_GET[inicio].",30";
	$r=mysql_query($sql,$l)or die($sql); 
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
}
?>