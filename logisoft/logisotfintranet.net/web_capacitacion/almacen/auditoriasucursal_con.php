<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "delete from reporte_auditoria_ajustes where usuario = '$_SESSION[IDUSUARIO]' and isnull(folioauditoria) 
		AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
	
		$s = "SELECT IFNULL(MAX(folio),0)+1 folio FROM reporte_auditoria_principal WHERE sucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$foliosucursal = $f->folio;
		
		$s = "SELECT ifnull(saldoanterior,0) saldoanterior, ifnull(inventarioafecha,0) inventarioafecha, ifnull(carteraafecha,0) as carteraafecha, 
		date_format(fecha, '%d/%m/%Y') as fecha, date_format(current_date, '%d/%m/%Y') as factual, date_format(adddate(current_date, interval -1 day), '%d/%m/%Y') as fechaanterior
		FROM reporte_auditoria_principal
		WHERE sucursal = $_SESSION[IDSUCURSAL]
		ORDER BY folio DESC LIMIT 1";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$anterior = "{'saldoanterior':'$f->saldoanterior','inventarioal':'$f->inventarioafecha','carteraal':'$f->carteraafecha',
			fecha:'$f->fecha', 'factual':'$f->factual', 'folioauditoria':'$foliosucursal', 'fechaanterior':'$f->fechaanterior'}";
		}else{
			$anterior = "{'saldoanterior':'0','inventarioal':'0','carteraal':'0', 'fecha':'', 'factual':'".date("d/m/Y")."', 'folioauditoria':'$foliosucursal'}";
		}		
		
		
		$s = "DELETE FROM reporte_auditoria_liquidacion WHERE ISNULL(folioauditoria) AND tipo = 'G' AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT id FROM guiasventanilla WHERE estado LIKE IF(tipoflete=0,'%ALMACEN ORIGEN%','%ALMACEN DESTINO%') 
		AND IF(tipoflete=0,idsucursalorigen,idsucursaldestino) = '$_SESSION[IDSUCURSAL]' AND condicionpago = 0";
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			$s = "call proc_RegistroAuditorias('LG','$f->id',null)";
			mysql_query($s,$l) or die($s);
		}
		
		//LIQUIDACIONES
		$s = "SELECT IFNULL(SUM(total),0) AS total 
		FROM reporte_auditoria_liquidacion
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$liquidaciones = $f->total;
		}else{
			$liquidaciones = 0;
		}
		
		//depositos
		$s = "SELECT IFNULL(SUM(importe),0) as total FROM reporte_auditoria_depositos
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$depositos = $f->total;
		}else{
			$depositos = 0;
		}
		
		//nota credito
		$s = "SELECT IFNULL(SUM(importe),0) AS total
		FROM reporte_auditoria_notacredito
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$notacredito = $f->total;
		}else{
			$notacredito = 0;
		}
		
		//guias canceladas
		$s = "SELECT IFNULL(SUM(importe),0) AS total
		FROM reporte_auditoria_guiascanceladas
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$guiascanceladas = $f->total;
		}else{
			$guiascanceladas = 0;
		}
		
		//facturas canceladas
		$s = "SELECT IFNULL(SUM(importe),0) AS total 
		FROM reporte_auditoria_facturascanceladas
		WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$facturascanceladas = $f->total;
		}else{
			$facturascanceladas = 0;
		}
		
		echo "({
				'anterior':$anterior,
				'liquidaciones':'$liquidaciones',
				'depositos':'$depositos',
				'notacredito':'$notacredito',
				'guiascanceladas':'$guiascanceladas',
				'facturascanceladas':'$facturascanceladas'
			})";
	}
	
	if($_GET[accion]==2){
		$s = "DELETE FROM reporte_auditoria_leido WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_leido
		(guia, fecha, cliente, importe, flete, pago, STATUS, leida, tipo, sucursal)
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		gv.total, gv.tflete, IF(gv.condicionpago=0,'CONTADO', 'CREDITO'), gv.estado,
		'S', 'G','$_SESSION[IDSUCURSAL]'
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete = 0, gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN reporte_auditorias_paq rp ON gv.id = rp.folio
		WHERE rp.sucursal = '$_SESSION[IDSUCURSAL]')
		UNION
		(SELECT gv.id, gv.fecha, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno),
		gv.total, gv.tflete, gv.tipopago, gv.estado,
		'S','G','$_SESSION[IDSUCURSAL]'
		FROM guiasempresariales gv
		INNER JOIN catalogocliente cc ON IF(gv.tipoflete <> 'POR COBRAR', gv.idremitente, gv.iddestinatario) = cc.id
		INNER JOIN reporte_auditorias_paq rp ON gv.id = rp.folio
		WHERE rp.sucursal = '$_SESSION[IDSUCURSAL]')";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_auditoria_leido
		(guia, fecha, cliente, importe, flete, pago, STATUS, leida, tipo, sucursal)
		SELECT f.folio, f.fecha, CONCAT_WS(' ',f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente),
		IFNULL(f.total,0)+IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0),
		0,IF(f.credito='SI','CREDITO','CONTADO'), f.estado, 'S', 'F', '$_SESSION[IDSUCURSAL]'
		FROM facturacion AS f
		INNER JOIN reporte_auditorias_fac rf ON f.folio = rf.folio
		WHERE rf.sucursal = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);		
		
		$s = "SELECT IFNULL(SUM(IF(tipo='F',importe,0)),0) factura,  
		IFNULL(SUM(IF(tipo<>'F',importe,0)),0) guia
		FROM reporte_auditoria_leido WHERE sucursal = '$_SESSION[IDSUCURSAL]' AND 
		ISNULL(folioauditoria)";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cartera = $f->factura;
			$inventario = $f->guia;
		}else{
			$cartera = 0;
			$inventario = 0;
		}
		
		echo "({'inventario':'$inventario', 'cartera':'$cartera'})";
	}
	
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
	
	
	if($_GET[accion]==3){//liquidaciones
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL]");
		
			
		$s = "SELECT * 
		FROM reporte_auditoria_liquidacion
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_liquidacion
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->nombre);
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
	
	if($_GET[accion]==4){//depositos
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
		
		$s = "SELECT * FROM reporte_auditoria_depositos
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * FROM reporte_auditoria_depositos
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->nombre);
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
	
	if($_GET[accion]==5){//nota credito
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
		$s = "SELECT *
		FROM reporte_auditoria_notacredito
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_notacredito
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
	
	if($_GET[accion]==6){//
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
		$s = "SELECT *  
		FROM reporte_auditoria_facturascanceladas
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_facturascanceladas
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
	
	if($_GET[accion]==7){//nota credito
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
		$s = "SELECT * 
		FROM reporte_auditoria_guiascanceladas
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_guiascanceladas
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
	
	if($_GET[accion]==8){//nota credito
		if($_GET[folio]!=""){
			$s = "select id from reporte_auditoria_principal where sucursal = $_SESSION[IDSUCURSAL] and folio = $_GET[folio]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$_GET[folio] = $f->id;
		}
	
		$filtroFolio = (($_GET[folio]!="")?" WHERE folioauditoria = $_GET[folio] ":"WHERE sucursal = $_SESSION[IDSUCURSAL] and ISNULL(folioauditoria)");
		
		$s = "SELECT * 
		FROM reporte_auditoria_leido
		$filtroFolio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * 
		FROM reporte_auditoria_leido
		$filtroFolio
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = utf8_encode($f->cliente);
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
	
	if($_GET[accion]==9){
		$s = "INSERT INTO reporte_auditoria_principal SET folio=obtenerFolio('reporte_auditoria_principal',$_SESSION[IDSUCURSAL]),
		saldoanterior='$_GET[saldoanterior]', inventarioafecha='$_GET[inventarioal]', carteraafecha='$_GET[carteraal]', 
		liquidaciones='$_GET[liquidaciones]', depositos='$_GET[depositos]', facturascanceladas='$_GET[facturascanceladas]', 
		guiascanceladas='$_GET[guiascanceladas]', notascredito='$_GET[notasdecredito]',saldocontable='$_GET[saldocontable]', 
		inventariocierre='$_GET[inventarioactual]', carteracierre='$_GET[carteraalcierre]', saldofinal='$_GET[saldofinal]',
		totalajustes='$_GET[totalajustes]',saldoconajustes='$_GET[saldoconajustes]',
		fecha=CURRENT_DATE,hora=CURRENT_TIME,sucursal='$_SESSION[IDSUCURSAL]';";
		$r = mysql_query($s,$l) or die($s);
		$folioauditoria = mysql_insert_id($l);		
		
		$s = "UPDATE reporte_auditoria_depositos SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_facturascanceladas SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_guiascanceladas SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_liquidacion SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_notacredito SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]'";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_leido SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]';";
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_auditoria_ajustes SET folioauditoria = '$folioauditoria'
		WHERE ISNULL(folioauditoria) AND sucursal = '$_SESSION[IDSUCURSAL]' and usuario = $_SESSION[IDUSUARIO];";
		mysql_query($s,$l) or die($s);
		
		$totales = 0;
	}
	
	if($_GET[accion]==10){		
		$s = "SELECT id, ifnull(saldoanterior,0) saldoanterior, ifnull(inventarioafecha,0) inventarioafecha, ifnull(carteraafecha,0) as carteraafecha, 
		date_format(fecha, '%d/%m/%Y') as fecha, date_format(current_date, '%d/%m/%Y') as factual,
		inventariocierre, carteracierre, saldofinal, totalajustes, saldoconajustes, date_format(adddate(fecha, interval -1 day), '%d/%m/%Y') as fechaanterior
		FROM reporte_auditoria_principal
		WHERE folio = '$_GET[folio]' and sucursal = $_SESSION[IDSUCURSAL]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$anterior = "{'saldoanterior':'$f->saldoanterior','inventarioal':'$f->inventarioafecha','carteraal':'$f->carteraafecha',
		fecha:'$f->fecha', 'factual':'$f->factual', 'folioauditoria':'$_GET[folio]', 
		'inventariocierre':'$f->inventariocierre', 'carteracierre':'$f->carteracierre', 'saldofinal':'$f->saldofinal',
		'totalajustes':'$f->totalajustes', 'saldoconajustes':'$f->saldoconajustes', 'fechaanterior':'$f->fechaanterior'}";
		
		$folioauditoria = $f->id;
		
		//LIQUIDACIONES
		$s = "SELECT IFNULL(SUM(total),0) AS total 
		FROM reporte_auditoria_liquidacion
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$liquidaciones = $f->total;
		}else{
			$liquidaciones = 0;
		}
		
		//depositos
		$s = "SELECT IFNULL(SUM(importe),0) as total FROM reporte_auditoria_depositos
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$depositos = $f->total;
		}else{
			$depositos = 0;
		}
		
		//nota credito
		$s = "SELECT IFNULL(SUM(importe),0) AS total
		FROM reporte_auditoria_notacredito
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$notacredito = $f->total;
		}else{
			$notacredito = 0;
		}
		
		//guias canceladas
		$s = "SELECT IFNULL(SUM(importe),0) AS total
		FROM reporte_auditoria_guiascanceladas
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$guiascanceladas = $f->total;
		}else{
			$guiascanceladas = 0;
		}
		
		//facturas canceladas
		$s = "SELECT IFNULL(SUM(importe),0) AS total 
		FROM reporte_auditoria_facturascanceladas
		WHERE folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$facturascanceladas = $f->total;
		}else{
			$facturascanceladas = 0;
		}
		
		$s = "select cantidad, concepto, tipoajuste 
		from reporte_auditoria_ajustes 
		where folioauditoria = $folioauditoria";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$ajustes = array();
			while($f = mysql_fetch_object($r)){
				$ajustes[] = $f;
			}
			$ajustes = json_encode($ajustes);
		}else{
			$ajustes = "[]";
		}
		
		echo "({
				'anterior':$anterior,
				'ajustes':$ajustes,
				'liquidaciones':'$liquidaciones',
				'depositos':'$depositos',
				'notacredito':'$notacredito',
				'guiascanceladas':'$guiascanceladas',
				'facturascanceladas':'$facturascanceladas'
			})";
	}
	
	if($_GET[accion]==11){		
		$s = "insert into reporte_auditoria_ajustes 
		set cantidad = '$_GET[cantidad]', concepto='$_GET[concepto]', tipoajuste = '$_GET[tipoajuste]',
		sucursal = '$_SESSION[IDSUCURSAL]', usuario='$_SESSION[IDUSUARIO]', fecha = current_date";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "ok";
	}
?>
