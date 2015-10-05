<? session_start();
	require_once("../../Conectar.php");
	$l=Conectarse('webpmm');
	
	$paginado = 30;
	$contador = ($_GET[contador]!="")?$_GET[contador]:0;
	$desde	  = ($paginado*$contador);
	$limite = " limit $desde, $paginado ";
	
	function f_adelante($vdesde,$vpaginado,$total){
		if($vdesde+$vpaginado>($total-1))
			return false;
		else
			return true;
	}
	function f_atras($vdesde){
		if($vdesde==0)
			return false;
		else
			return true;
	}
	function f_paginado($vpaginado,$vtotal){
		if($vpaginado>=$vtotal)
			return false;
		else
			return true;
	} 	

if ($_GET[accion]==1){
	
	$s="SELECT sucursal	FROM ( /* cobranza */
		(SELECT cs.id AS sucursal FROM formapago fp	INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
		INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."'  
		AND fp.procedencia='C' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION 
		(SELECT cs.id AS sucursal FROM formapago fp 
		INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id INNER JOIN catalogocliente cc ON fp.cliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION	/* contado */
		(SELECT cs.id AS sucursal FROM formapago fp INNER JOIN guiasventanilla gv ON fp.guia=gv.id
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND (fp.procedencia='G' AND fp.tipo='V') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION
		(SELECT cs.id AS sucursal FROM formapago fp INNER JOIN facturacion f ON fp.guia=f.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='F' AND f.facturaestado='GUARDADO' AND f.credito='NO' GROUP BY fp.sucursal)
	UNION	/* entregadas */
		(SELECT cs.id AS sucursal FROM formapago fp INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id	
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND (fp.procedencia='M' OR fp.procedencia='O') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	)t GROUP BY sucursal";
	$r = mysql_query($s,$l) or die($s);
	$totalregistros = mysql_num_rows($r);
	
	$s="SELECT FORMAT(SUM(IFNULL(contado,0)),2) contado,FORMAT(SUM(IFNULL(cobranza,0)),2) cobranza,
	FORMAT(SUM(IFNULL(entregadas,0)),2) entregadas,FORMAT((SUM(IFNULL(contado,0))+SUM(IFNULL(cobranza,0))+
	SUM(IFNULL(entregadas,0))),2) total,0 AS depositado,FORMAT(SUM(IFNULL(contado,0))+
	SUM(IFNULL(cobranza,0))+SUM(IFNULL(entregadas,0)),2) saldo 	FROM ( /* cobranza */
		(SELECT IFNULL(cs.id,1) AS sucursal,cs.prefijo AS nombresucursal,0 AS contado,
		SUM(IFNULL(fp.total,0)) AS cobranza,0 AS entregadas FROM formapago fp
		INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
		INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='C' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION 
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,0 AS contado,
		SUM(IFNULL(fp.total,0)) AS cobranza,0 AS entregadas
		FROM formapago fp 
		INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
		INNER JOIN catalogocliente cc ON fp.cliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION	/* contado */
		(SELECT IFNULL(cs.id,1) AS sucursal,cs.prefijo AS nombresucursal,SUM(IFNULL(fp.total,0)) AS contado,
		0 AS cobranza,0 AS entregadas FROM formapago fp
		INNER JOIN guiasventanilla gv ON fp.guia=gv.id
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND (fp.procedencia='G' AND fp.tipo='V') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION
		(SELECT IFNULL(cs.id,1) AS sucursal,cs.prefijo AS nombresucursal,SUM(IFNULL(fp.total,0)) AS contado,
		0 AS cobranza,0 AS entregadas FROM formapago fp
		INNER JOIN facturacion f ON fp.guia=f.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='F' AND f.facturaestado='GUARDADO' AND f.credito='NO' AND ISNULL(fp.fechacancelacion) 
		GROUP BY fp.sucursal)
	UNION	/* entregadas */
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,0 AS contado,
		0 AS cobranza,SUM(IFNULL(fp.total,0)) AS entregadas FROM formapago fp
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND (fp.procedencia='M' OR fp.procedencia='O') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal))t ";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$totales = json_encode($f);

	$s="SELECT sucursal,nombresucursal,SUM(IFNULL(contado,0)) contado,SUM(IFNULL(cobranza,0)) cobranza,SUM(IFNULL(entregadas,0)) entregadas,
	(SUM(IFNULL(contado,0))+SUM(IFNULL(cobranza,0))+SUM(IFNULL(entregadas,0))) total,0 AS depositado,
	(SUM(IFNULL(contado,0))+SUM(IFNULL(cobranza,0))+SUM(IFNULL(entregadas,0))) saldo 
	FROM ( /* cobranza */
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,0 AS contado,
		SUM(IFNULL(fp.total,0)) AS cobranza,0 AS entregadas FROM formapago fp
		INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
		INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='C' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION 
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,0 AS contado,
		SUM(IFNULL(fp.total,0)) AS cobranza,0 AS entregadas
		FROM formapago fp 
		INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
		INNER JOIN catalogocliente cc ON fp.cliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION	/* contado */
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,SUM(IFNULL(fp.total,0)) AS contado,
		0 AS cobranza,0 AS entregadas FROM formapago fp
		INNER JOIN guiasventanilla gv ON fp.guia=gv.id
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND (fp.procedencia='G' AND fp.tipo='V') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	UNION
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,SUM(IFNULL(fp.total,0)) AS contado,
		0 AS cobranza,0 AS entregadas FROM formapago fp
		INNER JOIN facturacion f ON fp.guia=f.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='F' AND f.facturaestado='GUARDADO' AND f.credito='NO' GROUP BY fp.sucursal)
	UNION	/* entregadas */
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,0 AS contado,
		0 AS cobranza,SUM(IFNULL(fp.total,0)) AS entregadas FROM formapago fp
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND (fp.procedencia='M' OR fp.procedencia='O') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)
	)t GROUP BY nombresucursal $limite";
	$r = mysql_query($s,$l) or die($s);
	$arr = array();	

	while($f = mysql_fetch_object($r)){
		$f->nombrecliente=cambio_texto($f->nombrecliente);
		$arr[] = $f;
	}
	
	$registros = str_replace('null','""',json_encode($arr));

	echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
}