<? 	
	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');

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
	$s="SELECT guia
	FROM (
		(SELECT cs.id AS sucursal,fp.guia FROM formapago fp
		INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
		INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		INNER JOIN catalogocliente cc ON lcd.cliente=cc.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
		AND fp.procedencia='C' AND cs.id='".$_GET[sucursal]."' AND ISNULL(fp.fechacancelacion) GROUP BY fp.guia)
	UNION 
		(SELECT cs.id AS sucursal,gv.id AS guia FROM formapago fp 
		INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
		INNER JOIN abonodecliente_facturas a ON ac.id=a.folioabono INNER JOIN facturadetalle fd ON a.factura=fd.factura
		INNER JOIN guiasventanilla gv ON fd.folio=gv.id	INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
		INNER JOIN catalogocliente cc ON fp.cliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
		AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) AND cs.id='".$_GET[sucursal]."'  GROUP BY gv.id)
	)Tabla GROUP BY guia ";
	$r = mysql_query($s,$l) or die($s);
	$totalregistros = mysql_num_rows($r);
	
	$s="SELECT FORMAT(SUM(importe),2) AS importe FROM (
		SELECT cs.id AS sucursal,fp.total AS importe FROM formapago fp
		INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
		INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		INNER JOIN catalogocliente cc ON lcd.cliente=cc.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
		AND fp.procedencia='C' AND cs.id='".$_GET[sucursal]."' AND ISNULL(fp.fechacancelacion)
		UNION 
		SELECT cs.id AS sucursal,fp.total AS importe 
		FROM formapago fp 
		INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
		INNER JOIN catalogocliente cc ON fp.cliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."'
		AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) AND cs.id='".$_GET[sucursal]."'
	)t";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$totales = json_encode($f);
	
	$s="SELECT sucursal,nombresucursal,DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,guia,cliente,importe,caja 
	FROM (
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,fp.fecha,fp.guia,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,fp.total AS importe,lcd.idusuario AS caja FROM formapago fp
		INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
		INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
		INNER JOIN catalogocliente cc ON lcd.cliente=cc.id		
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='C' AND cs.id='".$_GET[sucursal]."' AND ISNULL(fp.fechacancelacion) GROUP BY fp.guia)
	UNION 
		(SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,fp.fecha,gv.id AS guia,
		CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno)AS cliente,gv.total AS importe,gv.idusuario AS caja 
		FROM formapago fp 
		INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
		INNER JOIN abonodecliente_facturas a ON ac.id=a.folioabono
		INNER JOIN facturadetalle fd ON a.factura=fd.factura
		INNER JOIN guiasventanilla gv ON fd.folio=gv.id	
		INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
		INNER JOIN catalogocliente cc ON fp.cliente=cc.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) AND cs.id='".$_GET[sucursal]."'  GROUP BY gv.id)
)Tabla GROUP BY guia ORDER BY fecha,guia $limite"; 
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
?>
