<?	session_start();
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
	
	if($_GET[accion]=="0"){
		$rr = ObtenerFolio("moduloconcesiones","webpmm");
		
		if($_GET[sucursal]!=''){
			$where = " WHERE sucursal = '$_GET[sucursal]' ";
		}
		$s = "SELECT IF(ISNULL(MAX(folio)),1,MAX(folio)+1) folio,IF(ISNULL(MAX(fechafin)), '' ,
		DATE_FORMAT(DATE_ADD(MAX(fechafin),INTERVAL 1 DAY),'%d/%m/%Y')) fechainicio 
		FROM moduloconcesiones  $where";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		//$f->folio = $rr[0];
		
		echo "(".str_replace('null','""',json_encode($f)).")";
		
	}else if($_GET[accion]==1){//PRINCIPAL CONCESIONES	
		$totalregistros = 0;
		$totales = 0;
		
		if ($_GET[fechainicio]!=''){
			$restar=" AND t2.fecha<'".$_GET[fechainicio]."'";
		}else{
			$restar=" AND t2.fecha<'2000-01-01'";
		}
		if ($_GET[folio]!=''){
		$s = "SELECT movimiento,pagcontado,pagcredito,cobcontado,cobcredito,total 
		FROM reporte_concesion WHERE folio=$_GET[folio] AND sucursal=$_GET[sucursal]";
		}else{
		$s = "DELETE FROM reporte_concesion WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		/*enviado*/
		$s = "INSERT INTO reporte_concesion
		SELECT NULL,NULL,$_GET[sucursal],'VENTA' AS movimiento,SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,
		SUM(cobcred) AS cobcredito, SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total,$_SESSION[IDUSUARIO] FROM(
		SELECT t1.id,t1.descripcion,t1.ventas,t1.porcrecoleccion,
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS pagcont,
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS pagcred,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS cobcont,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS cobcred
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2 ON t1.id=t2.idsucursalorigen
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t1.id
		UNION
		SELECT t1.id,t1.descripcion,t1.ventas,t1.porcrecoleccion, 
		SUM(IF(t2.tipoflete='PAGADA' AND t2.tipopago='CONTADO',(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS pagcont,
		SUM(IF(t2.tipoflete='PAGADA' AND t2.tipopago='CREDITO',(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100)),0)) AS pagcred,
		0 cobcont, 0 cobcred FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursalorigen 
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " 
		AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." 
		GROUP BY t1.id) AS t1
		UNION
		/*recibido*/
		SELECT NULL,NULL,$_GET[sucursal],'RECIBIDO' AS movimiento,SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,
		SUM(cobcred) AS cobcredito,SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total,$_SESSION[IDUSUARIO] FROM(
		SELECT t1.id,t1.descripcion,t1.recibido,t1.porcead, 
		SUM(IF(t2.tipoflete='PAGADA' AND t2.tipopago='CONTADO',(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS pagcont,
		SUM(IF(t2.tipoflete='PAGADA' AND t2.tipopago='CREDITO',(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS pagcred,
		0 cobcont, 0 cobcred FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' 
		".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t2.idsucursalorigen!=t2.idsucursaldestino
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t1.id
		UNION
		SELECT t1.id,t1.descripcion,t1.recibido,t1.porcead, 
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS pagcont,
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS pagcred,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS cobcont,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0)) AS cobcred
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t2.idsucursalorigen!=t2.idsucursaldestino
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t1.id
		UNION /*canceladas*/
		SELECT t1.id,t1.descripcion,t1.recibido,t1.porcead, 
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0))*-1 AS pagcont,
		SUM(IF(t2.tipoflete=0 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0))*-1 AS pagcred,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=0,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0))*-1 AS cobcont,
		SUM(IF(t2.tipoflete=1 AND t2.condicionpago=1,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*
		(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)),0))*-1 AS cobcred
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON t2.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE t2.estado='CANCELADO' AND YEAR(t2.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t2.idsucursalorigen!=t2.idsucursaldestino
		AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND h.sucursal=".$_GET[sucursal]." $restar GROUP BY t1.id) AS t2";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_concesiondetalle WHERE ISNULL(folio) AND tipo='V' AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_concesiondetalle
		SELECT NULL,NULL,$_GET[sucursal],'V' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100)) comision,
		(t2.trecoleccion) recoleccion,(t2.trecoleccion*(t1.porcrecoleccion/100)) comisionrad,(t2.tcostoead) entrega, 0 AS comisionead,(t2.texcedente) sobrepeso,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100))) total,(t2.total) tgral,
		CONCAT(IF(t2.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(t2.condicionpago=0,'CONTADO','CREDITO')) condicion,t2.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2 ON t1.id=t2.idsucursalorigen
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) 
		AND t1.id=".$_GET[sucursal]." GROUP BY t2.id
		UNION
		SELECT NULL,NULL,$_GET[sucursal],'V' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100)) comision,
		(t2.trecoleccion) recoleccion,(t2.trecoleccion*(t1.porcrecoleccion/100)) comisionrad,(t2.tcostoead) entrega, 0 AS comisionead,(t2.texcedente) sobrepeso,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100))) total, (t2.total) tgral,
		CONCAT('PAGADA','-',IF(t2.tipopago='CONTADO','CONTADO','CREDITO')) condicion,t2.estado,	$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursalorigen 
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." 
		GROUP BY t2.id ";	
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_concesiondetalle WHERE ISNULL(folio) AND tipo='R' AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_concesiondetalle
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100)) comision,
		(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega,(t2.tcostoead*(t1.porcead/100)) comisionead,(t2.texcedente) sobrepeso,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100))) total,(t2.total) tgral,
		CONCAT('PAGADA','-',IF(t2.tipopago='CONTADO','CONTADO','CREDITO')) condicion,t2.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND t2.idsucursalorigen!=t2.idsucursaldestino AND t1.concesion!=0 AND 
		NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t2.id
		UNION
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100)) comision,
		(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega,(t2.tcostoead*(t1.porcead/100)) comisionead,(t2.texcedente) sobrepeso,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100))) total,(t2.total) tgral,
		CONCAT(IF(t2.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(t2.condicionpago=0,'CONTADO','CREDITO')) condicion,t2.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.idsucursalorigen!=t2.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." 
		GROUP BY t2.id
		UNION /*canceladas*/
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))*-1) comision,
		(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega,((t2.tcostoead*(t1.porcead/100))*-1) comisionead,(t2.texcedente) sobrepeso,
		(((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)))*-1) total,(t2.total) tgral,
		CONCAT(IF(t2.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(t2.condicionpago=0,'CONTADO','CREDITO')) condicion,t2.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON t2.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE t2.estado='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.idsucursalorigen!=t2.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND h.sucursal=".$_GET[sucursal]." $restar 
		GROUP BY t2.id";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT movimiento,pagcontado,pagcredito,cobcontado,cobcredito,total 
		FROM reporte_concesion WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		}
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->total = $f->pagcontado + $f->pagcredito + $f->cobcontado + $f->cobcredito;
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
		
	}else if($_GET[accion]==2){//VENTAS
		
		$totales = 0;
		
		if ($_GET[folio]!=''){
		$s = "SELECT guia,fecha AS fechaguia,flete,descuento,fleteneto,comision,recoleccion,comisionrad,entrega,comisionead,comsobrepeso AS sobrepeso,
		totalcom AS total,totalgral AS tgral,condicion,status AS estado 
		FROM reporte_concesiondetalle WHERE folio=$_GET[folio] AND sucursal=$_GET[sucursal] AND tipo='V' $limite";
		}else{
		$s = "DELETE FROM reporte_concesiondetalle WHERE ISNULL(folio) AND tipo='V' AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_concesiondetalle
		SELECT NULL,NULL,$_GET[sucursal],'V' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100)) comision,
		(t2.trecoleccion) recoleccion,(t2.trecoleccion*(t1.porcrecoleccion/100)) comisionrad,(t2.tcostoead) entrega, 0 AS comisionead,(t2.texcedente) sobrepeso,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100))) total,(t2.total) tgral,
		CONCAT(IF(t2.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(t2.condicionpago=0,'CONTADO','CREDITO')) condicion,t2.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2 ON t1.id=t2.idsucursalorigen
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) 
		AND t1.id=".$_GET[sucursal]." GROUP BY t2.id
		UNION
		SELECT NULL,NULL,$_GET[sucursal],'V' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100)) comision,
		(t2.trecoleccion) recoleccion,(t2.trecoleccion*(t1.porcrecoleccion/100)) comisionrad,(t2.tcostoead) entrega, 0 AS comisionead,(t2.texcedente) sobrepeso,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.ventas/100))+(t2.trecoleccion*(t1.porcrecoleccion/100))) total, (t2.total) tgral,
		CONCAT('PAGADA','-',IF(t2.tipopago='CONTADO','CONTADO','CREDITO')) condicion,t2.estado,	$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursalorigen 
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." 
		GROUP BY t2.id ";	
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT guia,fecha AS fechaguia,flete,descuento,fleteneto,comision,recoleccion,comisionrad,entrega,comisionead,comsobrepeso AS sobrepeso,
		totalcom AS total,totalgral AS tgral,condicion,status AS estado
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='V' $limite";
		}
		
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		if ($_GET[folio]!=''){
		$s = "SELECT id 
		FROM reporte_concesiondetalle WHERE folio=$_GET[folio] AND sucursal=$_GET[sucursal] AND tipo='V'";
		}else{
		$s = "SELECT id
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='V'";	
		}
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==3){//RECIBIDO
		
		if ($_GET[fechainicio]!=''){
			$restar=" AND t2.fecha<'".$_GET[fechainicio]."'";
		}else{
			$restar=" AND t2.fecha<'2000-01-01'";
		}
		
		$totales = 0;
		
		if ($_GET[folio]!=''){
		$s = "SELECT guia,fecha AS fechaguia,flete,descuento,fleteneto,comision,recoleccion,comisionrad,entrega,comisionead,comsobrepeso AS sobrepeso,
		totalcom AS total,totalgral AS tgral,condicion,status AS estado 
		FROM reporte_concesiondetalle WHERE folio=$_GET[folio] AND sucursal=$_GET[sucursal] AND tipo='R' $limite";
		}else{
		$s = "DELETE FROM reporte_concesiondetalle WHERE ISNULL(folio) AND tipo='R' AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_concesiondetalle
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100)) comision,
		(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega,(t2.tcostoead*(t1.porcead/100)) comisionead,(t2.texcedente) sobrepeso,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100))) total,(t2.total) tgral,
		CONCAT('PAGADA','-',IF(t2.tipopago='CONTADO','CONTADO','CREDITO')) condicion,t2.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasempresariales t2 ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND t2.idsucursalorigen!=t2.idsucursaldestino AND t1.concesion!=0 AND 
		NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." GROUP BY t2.id
		UNION
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,(((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100)) comision,
		(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega,(t2.tcostoead*(t1.porcead/100)) comisionead,(t2.texcedente) sobrepeso,
		((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100))) total,(t2.total) tgral,
		CONCAT(IF(t2.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(t2.condicionpago=0,'CONTADO','CREDITO')) condicion,t2.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		WHERE t2.estado!='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.idsucursalorigen!=t2.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND t1.id=".$_GET[sucursal]." 
		GROUP BY t2.id
		UNION /*canceladas*/
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(t2.id) guia,(t2.fecha) fechaguia,(t2.tflete) flete,(t2.ttotaldescuento) descuento,
		((t2.tflete+t2.texcedente)-t2.ttotaldescuento) fleteneto,((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))*-1) comision,
		(t2.trecoleccion) recoleccion,0 AS comisionrad,(t2.tcostoead) entrega,((t2.tcostoead*(t1.porcead/100))*-1) comisionead,(t2.texcedente) sobrepeso,
		(((((t2.tflete+t2.texcedente)-t2.ttotaldescuento)*(t1.recibido/100))+(t2.tcostoead*(t1.porcead/100)))*-1) total,(t2.total) tgral,
		CONCAT(IF(t2.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(t2.condicionpago=0,'CONTADO','CREDITO')) condicion,t2.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal t1 INNER JOIN guiasventanilla t2  ON t1.id=t2.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON t2.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE t2.estado='CANCELADO' AND YEAR(t2.fecha)>='2011' AND t2.idsucursalorigen!=t2.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND t2.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		t2.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND t1.concesion!=0 AND NOT ISNULL(t1.concesion) AND h.sucursal=".$_GET[sucursal]." $restar 
		GROUP BY t2.id";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT guia,fecha AS fechaguia,flete,descuento,fleteneto,comision,recoleccion,comisionrad,entrega,comisionead,comsobrepeso AS sobrepeso,
		totalcom AS total,totalgral AS tgral,condicion,status AS estado
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='R' $limite";
		}
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);			
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		if ($_GET[folio]!=''){
		$s = "SELECT id 
		FROM reporte_concesiondetalle WHERE folio=$_GET[folio] AND sucursal=$_GET[sucursal] AND tipo='R'";
		}else{
		$s = "SELECT id
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='R'";	
		}
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==4){//INGRESOS
		
	
	}else if($_GET[accion]==5){//REGISTRAR REPORTE
		$s = "INSERT INTO moduloconcesiones SET 
		folio = $_GET[folio],
		fechaconcesion = CURDATE(),
		sucursal = ".$_GET[sucursal].",
		fechainicio = '".cambiaf_a_mysql($_GET[fechainicio])."',
		fechafin = '".cambiaf_a_mysql($_GET[fechafin])."',
		idusuario = ".$_SESSION[IDUSUARIO].",
		fecha = CURRENT_TIMESTAMP";
		mysql_query($s,$l) or die($s);
		$folio = mysql_insert_id();
		
		$s = "UPDATE reporte_concesion SET folio = $_GET[folio] WHERE usuario = $_SESSION[IDUSUARIO] AND sucursal=".$_GET[sucursal]." AND ISNULL(folio)";	
		mysql_query($s,$l) or die($s);
		
		$s = "UPDATE reporte_concesiondetalle SET folio = $_GET[folio] WHERE usuario = $_SESSION[IDUSUARIO] AND sucursal=".$_GET[sucursal]." AND ISNULL(folio)";	
		mysql_query($s,$l) or die($s);
				
		echo "ok,".$_GET[folio];
	
	}else if($_GET[accion]==6){//OBTENER REPORTE GENERADO
		$s = "SELECT DATE_FORMAT(fechaconcesion,'%d/%m/%Y') AS fechaconcesion, 
		IF(fechainicio IS NULL OR fechainicio='0000-00-00','',DATE_FORMAT(fechainicio,'%d/%m/%Y')) AS fechainicio,
		DATE_FORMAT(fechafin,'%d/%m/%Y') AS fechafin, sucursal AS idsucursal FROM moduloconcesiones
		WHERE folio = ".$_GET[folio]." AND sucursal=$_GET[sucursal]";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
		
			$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
			FROM catalogosucursal WHERE id = ".$f->idsucursal."";	
			$r = mysql_query($s,$l) or die($s); $fs = mysql_fetch_object($r);
			$f->sucursal = cambio_texto($fs->descripcion);
			
			$principal = str_replace('null','""',json_encode($f));
	
			$s = "SELECT IFNULL(movimiento,'') AS movimiento, IFNULL(pagcontado,0) AS pagcontado, IFNULL(pagcredito,0) AS pagcredito,
			IFNULL(cobcontado,0) AS cobcontado, IFNULL(cobcredito,0) AS cobcredito
			FROM reporte_concesion WHERE folio=$_GET[folio] AND sucursal=$_GET[sucursal]";
			$r = mysql_query($s,$l) or die($s);
			$arr = array();
			while($f1 = mysql_fetch_object($r)){
				$f1->total = $f1->pagcontado + $f1->pagcredito + $f1->cobcontado + $f1->cobcredito;
				$arr[] = $f1;
			}
			
			$tabla1 = str_replace('null','""',json_encode($arr));		
				
			echo "({principal:$principal,tabla1:$tabla1})";
		}else{
			echo "no encontro";
		}	
	
	}else if($_GET[accion]==7){
		$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion 
		FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";	
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		echo "(".str_replace('null','""',json_encode($f)).")";
	}


?>