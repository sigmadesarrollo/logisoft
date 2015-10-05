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
		
		if ($_GET[fechainicio]!=''){
			$restar=" AND gv.fecha<'".$_GET[fechainicio]."'";
		}else{
			$restar=" AND gv.fecha<'2000-01-01'";
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
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0)) AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0)) AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0)) AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0)) AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv ON cs.id=gv.idsucursalorigen
		WHERE gv.estado!='CANCELADO' AND YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION
		SELECT cs.id,SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO',ge.total,0)) AS pagcont,
		SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CREDITO',ge.total,0)) AS pagcred,0 cobcont, 0 cobcred 
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursalorigen 
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " 
		AND ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." 
		GROUP BY cs.id) AS t1
		UNION
		/*recibido*/
		SELECT NULL,NULL,$_GET[sucursal],'RECIBIDO' AS movimiento,SUM(pagcont) AS pagcontado,SUM(pagcred) AS pagcredito,SUM(cobcont) AS cobcontado,
		SUM(cobcred) AS cobcredito,SUM(pagcont) + SUM(pagcred) + SUM(cobcont) + SUM(cobcred) AS total,$_SESSION[IDUSUARIO] FROM(
		SELECT cs.id,SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CONTADO',ge.total,0)) AS pagcont,
		SUM(IF(ge.tipoflete='PAGADA' AND ge.tipopago='CREDITO',ge.total,0)) AS pagcred,0 cobcont, 0 cobcred 
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursaldestino
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' 
		".((!empty($_GET[fechainicio]))? " AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND ge.idsucursalorigen!=ge.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0)) AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0)) AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0)) AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0)) AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		WHERE gv.estado!='CANCELADO' AND YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND gv.idsucursalorigen!=gv.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY cs.id
		UNION /*canceladas*/
		SELECT cs.id,SUM(IF(gv.tipoflete=0 AND gv.condicionpago=0,gv.total,0))*-1 AS pagcont,
		SUM(IF(gv.tipoflete=0 AND gv.condicionpago=1,gv.total,0))*-1 AS pagcred,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=0,gv.total,0))*-1 AS cobcont,
		SUM(IF(gv.tipoflete=1 AND gv.condicionpago=1,gv.total,0))*-1 AS cobcred
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON gv.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE gv.estado='CANCELADO' AND YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND gv.idsucursalorigen!=gv.idsucursaldestino
		AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND h.sucursal=".$_GET[sucursal]." $restar GROUP BY cs.id) AS gv";
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_concesiondetalle WHERE ISNULL(folio) AND tipo='V' AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_concesiondetalle
		SELECT NULL,NULL,$_GET[sucursal],'V' AS tipo,(gv.id) guia,(gv.fecha) fechaguia,(gv.tflete) flete,(gv.ttotaldescuento) descuento,
		((gv.tflete+gv.texcedente)-gv.ttotaldescuento) fleteneto,(((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.ventas/100)) comision,
		(gv.trecoleccion) recoleccion,(gv.trecoleccion*(cs.porcrecoleccion/100)) comisionrad,(gv.tcostoead) entrega, 0 AS comisionead,(gv.texcedente) sobrepeso,
		((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.ventas/100))+(gv.trecoleccion*(cs.porcrecoleccion/100))) total,(gv.total) tgral,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion,gv.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv ON cs.id=gv.idsucursalorigen
		WHERE gv.estado!='CANCELADO' AND YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) 
		AND cs.id=".$_GET[sucursal]." GROUP BY gv.id
		UNION
		SELECT NULL,NULL,$_GET[sucursal],'V' AS tipo,(ge.id) guia,(ge.fecha) fechaguia,(ge.tflete) flete,(ge.ttotaldescuento) descuento,
		((ge.tflete+ge.texcedente)-ge.ttotaldescuento) fleteneto,(((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.ventas/100)) comision,
		(ge.trecoleccion) recoleccion,(ge.trecoleccion*(cs.porcrecoleccion/100)) comisionrad,(ge.tcostoead) entrega, 0 AS comisionead,(ge.texcedente) sobrepeso,
		((((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.ventas/100))+(ge.trecoleccion*(cs.porcrecoleccion/100))) total, (ge.total) tgral,
		CONCAT('PAGADA','-',IF(ge.tipopago='CONTADO','CONTADO','CREDITO')) condicion,ge.estado,	$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursalorigen 
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." 
		GROUP BY ge.id ";	
		mysql_query($s,$l) or die($s);
		
		$s = "DELETE FROM reporte_concesiondetalle WHERE ISNULL(folio) AND tipo='R' AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_concesiondetalle
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(ge.id) guia,(ge.fecha) fechaguia,(ge.tflete) flete,(ge.ttotaldescuento) descuento,
		((ge.tflete+ge.texcedente)-ge.ttotaldescuento) fleteneto,(((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.recibido/100)) comision,
		(ge.trecoleccion) recoleccion,0 AS comisionrad,(ge.tcostoead) entrega,(ge.tcostoead*(cs.porcead/100)) comisionead,(ge.texcedente) sobrepeso,
		((((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.recibido/100))+(ge.tcostoead*(cs.porcead/100))) total,(ge.total) tgral,
		CONCAT('PAGADA','-',IF(ge.tipopago='CONTADO','CONTADO','CREDITO')) condicion,ge.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursaldestino
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND ge.idsucursalorigen!=ge.idsucursaldestino AND cs.concesion!=0 AND 
		NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY ge.id
		UNION
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(gv.id) guia,(gv.fecha) fechaguia,(gv.tflete) flete,(gv.ttotaldescuento) descuento,
		((gv.tflete+gv.texcedente)-gv.ttotaldescuento) fleteneto,(((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100)) comision,
		(gv.trecoleccion) recoleccion,0 AS comisionrad,(gv.tcostoead) entrega,(gv.tcostoead*(cs.porcead/100)) comisionead,(gv.texcedente) sobrepeso,
		((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))+(gv.tcostoead*(cs.porcead/100))) total,(gv.total) tgral,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion,gv.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		WHERE gv.estado!='CANCELADO' AND YEAR(gv.fecha)>='2011' AND gv.idsucursalorigen!=gv.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." 
		GROUP BY gv.id
		UNION /*canceladas*/
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(gv.id) guia,(gv.fecha) fechaguia,(gv.tflete) flete,(gv.ttotaldescuento) descuento,
		((gv.tflete+gv.texcedente)-gv.ttotaldescuento) fleteneto,((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))*-1) comision,
		(gv.trecoleccion) recoleccion,0 AS comisionrad,(gv.tcostoead) entrega,((gv.tcostoead*(cs.porcead/100))*-1) comisionead,(gv.texcedente) sobrepeso,
		(((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))+(gv.tcostoead*(cs.porcead/100)))*-1) total,(gv.total) tgral,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion,gv.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON gv.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE gv.estado='CANCELADO' AND YEAR(gv.fecha)>='2011' AND gv.idsucursalorigen!=gv.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND h.sucursal=".$_GET[sucursal]." $restar 
		GROUP BY gv.id";
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
		
		$s = "SELECT FORMAT(SUM(pagcontado),2) pagcont,FORMAT(SUM(pagcredito),2) pagcred,FORMAT(SUM(cobcontado),2) cobcont,
		FORMAT(SUM(cobcredito),2)cobcred,FORMAT(SUM(total),2) totalgral
		FROM reporte_concesion WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==2){//VENTAS
		
		if ($_GET[folio]!=''){
		$s = "SELECT guia,fecha AS fechaguia,flete,descuento,fleteneto,comision,recoleccion,comisionrad,entrega,comisionead,comsobrepeso AS sobrepeso,
		totalcom AS total,totalgral AS tgral,condicion,status AS estado 
		FROM reporte_concesiondetalle WHERE folio=$_GET[folio] AND sucursal=$_GET[sucursal] AND tipo='V' $limite";
		}else{
		$s = "DELETE FROM reporte_concesiondetalle WHERE ISNULL(folio) AND tipo='V' AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_concesiondetalle
		SELECT NULL,NULL,$_GET[sucursal],'V' AS tipo,(gv.id) guia,(gv.fecha) fechaguia,(gv.tflete) flete,(gv.ttotaldescuento) descuento,
		((gv.tflete+gv.texcedente)-gv.ttotaldescuento) fleteneto,(((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.ventas/100)) comision,
		(gv.trecoleccion) recoleccion,(gv.trecoleccion*(cs.porcrecoleccion/100)) comisionrad,(gv.tcostoead) entrega, 0 AS comisionead,(gv.texcedente) sobrepeso,
		((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.ventas/100))+(gv.trecoleccion*(cs.porcrecoleccion/100))) total,(gv.total) tgral,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion,gv.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv ON cs.id=gv.idsucursalorigen
		WHERE gv.estado!='CANCELADO' AND YEAR(gv.fecha)>='2011' ".((!empty($_GET[fechainicio]))? " AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' 
		AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) 
		AND cs.id=".$_GET[sucursal]." GROUP BY gv.id
		UNION
		SELECT NULL,NULL,$_GET[sucursal],'V' AS tipo,(ge.id) guia,(ge.fecha) fechaguia,(ge.tflete) flete,(ge.ttotaldescuento) descuento,
		((ge.tflete+ge.texcedente)-ge.ttotaldescuento) fleteneto,(((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.ventas/100)) comision,
		(ge.trecoleccion) recoleccion,(ge.trecoleccion*(cs.porcrecoleccion/100)) comisionrad,(ge.tcostoead) entrega, 0 AS comisionead,(ge.texcedente) sobrepeso,
		((((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.ventas/100))+(ge.trecoleccion*(cs.porcrecoleccion/100))) total, (ge.total) tgral,
		CONCAT('PAGADA','-',IF(ge.tipopago='CONTADO','CONTADO','CREDITO')) condicion,ge.estado,	$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursalorigen 
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." 
		GROUP BY ge.id ";	
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT guia,fecha AS fechaguia,flete,descuento,fleteneto,comision,recoleccion,comisionrad,entrega,comisionead,comsobrepeso AS sobrepeso,
		totalcom AS total,totalgral AS tgral,condicion,status AS estado
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='V' AND usuario=$_SESSION[IDUSUARIO] $limite";
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
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='V' AND usuario=$_SESSION[IDUSUARIO]";	
		}
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(flete),2) flete,FORMAT(SUM(descuento),2) descuento,FORMAT(SUM(fleteneto),2) fleteneto, 
		FORMAT(SUM(comision),2) comision,FORMAT(SUM(recoleccion),2) recoleccion,FORMAT(SUM(comisionrad),2) comisionrad,
		FORMAT(SUM(entrega),2) entrega,FORMAT(SUM(comisionead),2) comisionead,FORMAT(SUM(comsobrepeso),2) sobrepeso,
		FORMAT(SUM(totalcom),2) total,FORMAT(SUM(totalgral),2) totalgral
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='V' AND usuario=$_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==3){//RECIBIDO
		
		if ($_GET[fechainicio]!=''){
			$restar=" AND gv.fecha<'".$_GET[fechainicio]."'";
		}else{
			$restar=" AND gv.fecha<'2000-01-01'";
		}
		
		if ($_GET[folio]!=''){
		$s = "SELECT guia,fecha AS fechaguia,flete,descuento,fleteneto,comision,recoleccion,comisionrad,entrega,comisionead,comsobrepeso AS sobrepeso,
		totalcom AS total,totalgral AS tgral,condicion,status AS estado 
		FROM reporte_concesiondetalle WHERE folio=$_GET[folio] AND sucursal=$_GET[sucursal] AND tipo='R' $limite";
		}else{
		$s = "DELETE FROM reporte_concesiondetalle WHERE ISNULL(folio) AND tipo='R' AND sucursal=$_GET[sucursal] AND usuario=$_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO reporte_concesiondetalle
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(ge.id) guia,(ge.fecha) fechaguia,(ge.tflete) flete,(ge.ttotaldescuento) descuento,
		((ge.tflete+ge.texcedente)-ge.ttotaldescuento) fleteneto,(((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.recibido/100)) comision,
		(ge.trecoleccion) recoleccion,0 AS comisionrad,(ge.tcostoead) entrega,(ge.tcostoead*(cs.porcead/100)) comisionead,(ge.texcedente) sobrepeso,
		((((ge.tflete+ge.texcedente)-ge.ttotaldescuento)*(cs.recibido/100))+(ge.tcostoead*(cs.porcead/100))) total,(ge.total) tgral,
		CONCAT('PAGADA','-',IF(ge.tipopago='CONTADO','CONTADO','CREDITO')) condicion,ge.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasempresariales ge ON cs.id=ge.idsucursaldestino
		WHERE ge.estado!='CANCELADO' AND YEAR(ge.fecha)>='2011' AND ge.tipoflete='PAGADA' ".((!empty($_GET[fechainicio]))? " 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		ge.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND ge.idsucursalorigen!=ge.idsucursaldestino AND cs.concesion!=0 AND 
		NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." GROUP BY ge.id
		UNION
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(gv.id) guia,(gv.fecha) fechaguia,(gv.tflete) flete,(gv.ttotaldescuento) descuento,
		((gv.tflete+gv.texcedente)-gv.ttotaldescuento) fleteneto,(((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100)) comision,
		(gv.trecoleccion) recoleccion,0 AS comisionrad,(gv.tcostoead) entrega,(gv.tcostoead*(cs.porcead/100)) comisionead,(gv.texcedente) sobrepeso,
		((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))+(gv.tcostoead*(cs.porcead/100))) total,(gv.total) tgral,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion,gv.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		WHERE gv.estado!='CANCELADO' AND YEAR(gv.fecha)>='2011' AND gv.idsucursalorigen!=gv.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")."	AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND cs.id=".$_GET[sucursal]." 
		GROUP BY gv.id
		UNION /*canceladas*/
		SELECT NULL,NULL,$_GET[sucursal],'R' AS tipo,(gv.id) guia,(gv.fecha) fechaguia,(gv.tflete) flete,(gv.ttotaldescuento) descuento,
		((gv.tflete+gv.texcedente)-gv.ttotaldescuento) fleteneto,((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))*-1) comision,
		(gv.trecoleccion) recoleccion,0 AS comisionrad,(gv.tcostoead) entrega,((gv.tcostoead*(cs.porcead/100))*-1) comisionead,(gv.texcedente) sobrepeso,
		(((((gv.tflete+gv.texcedente)-gv.ttotaldescuento)*(cs.recibido/100))+(gv.tcostoead*(cs.porcead/100)))*-1) total,(gv.total) tgral,
		CONCAT(IF(gv.tipoflete=0,'PAGADA','POR COBRAR'),'-',IF(gv.condicionpago=0,'CONTADO','CREDITO')) condicion,gv.estado,$_SESSION[IDUSUARIO]
		FROM catalogosucursal cs INNER JOIN guiasventanilla gv  ON cs.id=gv.idsucursaldestino
		INNER JOIN historial_cancelacionysustitucion h ON gv.id=h.guia AND h.accion='SUSTITUCION REALIZADA'
		WHERE gv.estado='CANCELADO' AND YEAR(gv.fecha)>='2011' AND gv.idsucursalorigen!=gv.idsucursaldestino ".((!empty($_GET[fechainicio]))? " 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'" : " AND 
		gv.fecha <= '".cambiaf_a_mysql($_GET[fechafin])."' ")." AND cs.concesion!=0 AND NOT ISNULL(cs.concesion) AND h.sucursal=".$_GET[sucursal]." $restar 
		GROUP BY gv.id";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT guia,fecha AS fechaguia,flete,descuento,fleteneto,comision,recoleccion,comisionrad,entrega,comisionead,comsobrepeso AS sobrepeso,
		totalcom AS total,totalgral AS tgral,condicion,status AS estado
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='R' AND usuario=$_SESSION[IDUSUARIO] $limite";
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
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='R' AND usuario=$_SESSION[IDUSUARIO]";	
		}
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		$s = "SELECT FORMAT(SUM(flete),2) flete,FORMAT(SUM(descuento),2) descuento,FORMAT(SUM(fleteneto),2) fleteneto, 
		FORMAT(SUM(comision),2) comision,FORMAT(SUM(recoleccion),2) recoleccion,FORMAT(SUM(comisionrad),2) comisionrad,
		FORMAT(SUM(entrega),2) entrega,FORMAT(SUM(comisionead),2) comisionead,FORMAT(SUM(totalcom),2) total,FORMAT(SUM(totalgral),2) totalgral
		FROM reporte_concesiondetalle WHERE ISNULL(folio) AND sucursal=$_GET[sucursal] AND tipo='R' AND usuario=$_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$totales = json_encode($f);
		
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