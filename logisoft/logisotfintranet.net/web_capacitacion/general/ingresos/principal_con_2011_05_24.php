<? 
	session_start();
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
		$s="SELECT nombresucursal,FORMAT(SUM(efectivo),2) efectivo,FORMAT(SUM(transferencia),2) transferencia,
		FORMAT(SUM(cheques),2) cheques,FORMAT(SUM(otros),2) otros,FORMAT(SUM(tarjeta),2) tarjeta,FORMAT(SUM(nc),2) nc,
		FORMAT(SUM(efectivo)+SUM(transferencia)+SUM(cheques)+SUM(otros)+SUM(tarjeta)+SUM(nc),2) total,
		SUM(total) totalsistema
		FROM ( 
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
			INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='C' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION 
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp 
			INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
			INNER JOIN catalogocliente cc ON fp.cliente=cc.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION	
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN guiasventanilla gv ON fp.guia=gv.id
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND (fp.procedencia='G' AND fp.tipo='V') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN facturacion f ON fp.guia=f.folio
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='F' AND f.facturaestado='GUARDADO' AND f.credito='NO' AND ISNULL(fp.fechacancelacion) 
			GROUP BY fp.sucursal
		UNION	
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND (fp.procedencia='M' OR fp.procedencia='O') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		)t GROUP BY nombresucursal ";
		$r = mysql_query($s,$l) or die($s);
	 	$totalregistros = mysql_num_rows($r);
		
		$s="SELECT FORMAT(SUM(efectivo),2) efectivo,FORMAT(SUM(transferencia),2) transferencia,FORMAT(SUM(cheques),2) cheques,
		FORMAT(SUM(otros),2) otros,FORMAT(SUM(tarjeta),2) tarjeta,FORMAT(SUM(nc),2) nc,
		FORMAT(SUM(efectivo)+SUM(transferencia)+SUM(cheques)+SUM(otros)+SUM(tarjeta)+SUM(nc),2) total,
		FORMAT(SUM(total),2) totalsistema
		FROM ( 
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
			INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='C' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION 
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp 
			INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
			INNER JOIN catalogocliente cc ON fp.cliente=cc.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION	
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN guiasventanilla gv ON fp.guia=gv.id
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND (fp.procedencia='G' AND fp.tipo='V') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN facturacion f ON fp.guia=f.folio
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='F' AND f.facturaestado='GUARDADO' AND f.credito='NO' AND ISNULL(fp.fechacancelacion) 
			GROUP BY fp.sucursal
		UNION	
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND (fp.procedencia='M' OR fp.procedencia='O') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal)t";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		$s="SELECT sucursal,nombresucursal,FORMAT(SUM(efectivo),2) efectivo,FORMAT(SUM(transferencia),2) transferencia,
		FORMAT(SUM(cheques),2) cheques,FORMAT(SUM(otros),2) otros,FORMAT(SUM(tarjeta),2) tarjeta,FORMAT(SUM(nc),2) nc,
		FORMAT(SUM(efectivo)+SUM(transferencia)+SUM(cheques)+SUM(otros)+SUM(tarjeta)+SUM(nc),2) total,
		SUM(total) totalsistema
		FROM ( 
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN liquidacioncobranzadetalle lcd ON fp.guia=lcd.factura
			INNER JOIN liquidacioncobranza lc ON lcd.folioliquidacion=lc.folio
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='C' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION 
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp 
			INNER JOIN abonodecliente ac ON fp.guia=ac.folio AND ac.idsucursal=fp.sucursal
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id 
			INNER JOIN catalogocliente cc ON fp.cliente=cc.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='A' AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION	
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN guiasventanilla gv ON fp.guia=gv.id
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND (fp.procedencia='G' AND fp.tipo='V') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		UNION
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN facturacion f ON fp.guia=f.folio
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND fp.procedencia='F' AND f.facturaestado='GUARDADO' AND f.credito='NO' AND ISNULL(fp.fechacancelacion) 
			GROUP BY fp.sucursal
		UNION	 
			SELECT cs.id AS sucursal,cs.prefijo AS nombresucursal,IFNULL(SUM(fp.efectivo),0) efectivo,
			IFNULL(SUM(fp.transferencia),0) transferencia,IFNULL(IF(fp.banco=2,SUM(fp.cheque),0),0) cheques,
			IFNULL(IF(fp.banco!=2,SUM(fp.cheque),0),0) otros,IFNULL(SUM(fp.tarjeta),0) tarjeta,
			IFNULL(SUM(fp.notacredito),0) nc, IFNULL(SUM(fp.total),0) total 
			FROM formapago fp
			INNER JOIN catalogosucursal cs ON fp.sucursal=cs.id		
			WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
			AND (fp.procedencia='M' OR fp.procedencia='O') AND ISNULL(fp.fechacancelacion) GROUP BY fp.sucursal
		)t GROUP BY nombresucursal ORDER BY nombresucursal $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		
		while($f = mysql_fetch_object($r)){
			$f->nombresucursal=cambio_texto($f->nombresucursal);
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