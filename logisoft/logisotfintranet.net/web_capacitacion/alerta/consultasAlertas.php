<?	session_start();
	require_once('../Conectar.php');
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
	
	if($_GET[accion]=="0"){//OBTENER SUCURSAL
		$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion FROM catalogosucursal WHERE id = ".$_GET[sucursal]."";	
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		$f->descripcion = cambio_texto($f->descripcion);
		
		echo "(".str_replace('null','""',json_encode($f)).")";
		
	}else if($_GET[accion]==1){//Atrasos Embarques
		
	}else if($_GET[accion]==2){//Cancelaciones Pendiente Autorizar		
		$s = "SELECT g.id AS guia
		FROM guiasventanilla g
		INNER JOIN guiasventanilla_cs gcs ON g.id=gcs.folioguia
		INNER JOIN historial_cancelacionysustitucion can ON g.id=can.guia
		WHERE g.estado='AUTORIZACION PARA SUSTITUIR' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND g.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;		
		
		$s = "SELECT g.id AS guia,g.fecha,so.prefijo AS origen,CONCAT_WS(' ',cr.nombre,cr.paterno,cr.materno) remitente,
		sd.prefijo AS destino,CONCAT_WS(' ',cd.nombre,cd.paterno,cd.materno) destinatario,IF(g.tipoflete=1,'POR COBRAR','PAGADA') tipoflete,
		g.total,can.fecha,gcs.motivocancelacion AS motivo,gcs.usuario
		FROM guiasventanilla g
		INNER JOIN catalogosucursal so ON g.idsucursalorigen=so.id
		INNER JOIN catalogocliente cr ON g.idremitente=cr.id
		INNER JOIN catalogosucursal sd ON g.idsucursaldestino=sd.id
		INNER JOIN catalogocliente cd ON g.iddestinatario=cd.id
		INNER JOIN guiasventanilla_cs gcs ON g.id=gcs.folioguia
		INNER JOIN historial_cancelacionysustitucion can ON g.id=can.guia
		WHERE g.estado='AUTORIZACION PARA SUSTITUIR' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND g.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."":"")." $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();			
		while($f = mysql_fetch_object($r)){				
			$f->origen = cambio_texto($f->origen);
			$f->remitente = cambio_texto($f->remitente);
			$f->destino = cambio_texto($f->destino);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->motivo = cambio_texto($f->motivo);
			$f->usuario = cambio_texto($f->usuario);
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
			
	}else if($_GET[accion]==3){//Cobranza 30 dias	
		$s = "SELECT * FROM cobranza30dias_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT factura, date_format(fechaemision,'%d/%m/%Y') as fechaemision,
		cliente, nombre, importe, tipofactura, date_format(fechavencimiento,'%d/%m/%Y') as fechavencimiento,
		diasatraso, contrarecibo, diapago FROM cobranza30dias_tmp
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")." $limite";
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
		
	}else if($_GET[accion]==4){//Credito Linea Saturada
		$s = "SELECT credito,cliente,direccion,limitecredito,SUM(montoconsumido) montoconsumido,SUM(montovencido) montovencido,vendedorasignado 
			FROM (
			SELECT sc.folio AS credito,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) cliente,
			CONCAT(sc.calle,' ',sc.numero, ' ',sc.colonia,' ',sc.poblacion) direccion,IFNULL(sc.montoautorizado,0) limitecredito,
			0 AS montoconsumido,SUM(gv.total)AS montovencido,gv.nvendedorconvenio AS vendedor 
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON cc.id=IF(gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente	
			WHERE DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>0 
			AND sc.idsucursal=20
			AND gv.estado<>'CANCELADA' GROUP BY cc.id
			UNION
			SELECT sc.folio AS credito,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) cliente,
			CONCAT(sc.calle,' ',sc.numero, ' ',sc.colonia,' ',sc.poblacion) direccion,IFNULL(sc.montoautorizado,0) limitecredito,
			0 AS montoconsumido,SUM(gv.total)AS montovencido,gv.nvendedorconvenio AS vendedor 
			FROM guiasempresariales gv
			INNER JOIN catalogocliente cc ON cc.id=IF(gv.tipoflete='PAGADO',gv.idremitente,gv.iddestinatario)
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
			WHERE DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))>0 
			AND sc.idsucursal=20
			AND gv.estado<>'CANCELADA' GROUP BY cc.id
			UNION
			SELECT sc.folio AS credito,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) cliente,
			CONCAT(sc.calle,' ',sc.numero, ' ',sc.colonia,' ',sc.poblacion) direccion,IFNULL(sc.montoautorizado,0) limitecredito,
			SUM(gv.total) montoconsumido,0 AS montovencido,gv.nvendedorconvenio AS vendedor  
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON cc.id=IF(gv.tipoflete=0,gv.idremitente,gv.iddestinatario)
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
			WHERE DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))<=0
			AND sc.idsucursal=20
			AND gv.estado<>'CANCELADA' GROUP BY cc.id
			UNION
			SELECT sc.folio AS credito,CONCAT(cc.nombre,' ',cc.paterno,' ',cc.materno) cliente,
			CONCAT(sc.calle,' ',sc.numero, ' ',sc.colonia,' ',sc.poblacion) direccion,IFNULL(sc.montoautorizado,0) limitecredito,
			SUM(gv.total) montoconsumido,0 AS montovencido,gv.nvendedorconvenio AS vendedor  
			FROM guiasempresariales gv
			INNER JOIN catalogocliente cc ON cc.id=IF(gv.tipoflete='PAGADO',gv.idremitente,gv.iddestinatario)
			INNER JOIN solicitudcredito sc ON cc.id=sc.cliente
			WHERE DATEDIFF(CURDATE(),DATE_ADD(gv.fecha,INTERVAL cc.diascredito DAY))<=0			
			AND sc.idsucursal=20
			AND gv.estado<>'CANCELADA' GROUP BY cc.id
			)t GROUP BY cliente	ORDER BY cliente LIMIT ".$_GET[inicio].",30";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
					$f->cliente = cambio_texto($f->cliente);
					$f->direccion = cambio_texto($f->direccion);
					$f->vendedorasignado = cambio_texto($f->vendedorasignado);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==5){//Entregas Atrasadas
		$s = "SELECT guia,DATE_FORMAT(fechaemision,'%d/%m/%Y')AS fechaemision,remitente,destinatario,direccion, 
		sector,tipoflete,importe,tipoentrega FROM(
		SELECT gv.id AS guia,gv.fecha AS fechaemision,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS remitente,
		CONCAT_WS(' ',ccc.nombre,ccc.paterno,ccc.materno) AS destinatario,
		CONCAT(d.calle,' #',d.numero,' ',d.colonia,' ',d.poblacion) AS direccion, 
		cs.descripcion AS sector,IF (gv.tipoflete=0,'PAGADA','POR COBRAR') AS tipoflete, 
		gv.total AS importe,IF(gv.ocurre=1,'OCURRE','EAD') AS tipoentrega  
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente=cc.id
		INNER JOIN catalogocliente ccc ON gv.iddestinatario=ccc.id
		INNER JOIN direccion d ON gv.iddirecciondestinatario=d.id
		LEFT JOIN catalogosector cs ON gv.sector=cs.id
		WHERE gv.estado = 'ALMACEN DESTINO' AND
		ADDDATE(gv.fecha, INTERVAL (IF(gv.entregaocurre=0 OR ISNULL(gv.entregaocurre),gv.entregaocurre,24)/24) DAY) < CURRENT_DATE
		AND gv.ocurre=".(($_GET[tipo]==0)?0:1)." 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino = '" .$_SESSION[IDSUCURSAL]."'" :"")."
		GROUP BY gv.id 
		UNION
		SELECT ge.id AS guia,ge.fecha AS fechaemision,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS remitente,
		CONCAT_WS(' ',ccc.nombre,ccc.paterno,ccc.materno) AS destinatario,
		CONCAT(d.calle,' #',d.numero,' ',d.colonia,' ',d.poblacion) AS direccion, 
		cs.descripcion AS sector, ge.tipoflete, 
		ge.total AS importe,IF(ge.ocurre=1,'OCURRE','EAD') AS tipoentrega  
		FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON ge.idremitente=cc.id
		INNER JOIN catalogocliente ccc ON ge.iddestinatario=ccc.id
		INNER JOIN direccion d ON ge.iddirecciondestinatario=d.id
		LEFT JOIN catalogosector cs ON ge.sector=cs.id
		WHERE ge.estado = 'ALMACEN DESTINO' AND
		ADDDATE(ge.fecha, INTERVAL (IF(ge.entregaocurre=0 OR ISNULL(ge.entregaocurre),ge.entregaocurre,24)/24) DAY) < CURRENT_DATE
		AND ge.ocurre=".(($_GET[tipo]==0)?0:1)." 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursaldestino = '" .$_SESSION[IDSUCURSAL]."'" :"")."
		GROUP BY ge.id 
		)Tabla ORDER BY remitente,destinatario,direccion";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;	
			
		$s = "SELECT guia,DATE_FORMAT(fechaemision,'%d/%m/%Y')AS fechaemision,remitente,destinatario,direccion, 
		sector,tipoflete,importe,tipoentrega FROM(
		SELECT gv.id AS guia,gv.fecha AS fechaemision,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS remitente,
		CONCAT_WS(' ',ccc.nombre,ccc.paterno,ccc.materno) AS destinatario,
		UCASE(CONCAT(d.calle,' #',d.numero,' ',d.colonia,' ',d.poblacion)) AS direccion,  
		cs.descripcion AS sector,IF (gv.tipoflete=0,'PAGADA','POR COBRAR') AS tipoflete, 
		gv.total AS importe,IF(gv.ocurre=1,'OCURRE','EAD') AS tipoentrega  
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente=cc.id
		INNER JOIN catalogocliente ccc ON gv.iddestinatario=ccc.id
		INNER JOIN direccion d ON gv.iddirecciondestinatario=d.id
		LEFT JOIN catalogosector cs ON gv.sector=cs.id
		WHERE gv.estado = 'ALMACEN DESTINO' AND
		ADDDATE(gv.fecha, INTERVAL (IF(gv.entregaocurre=0 OR ISNULL(gv.entregaocurre),gv.entregaocurre,24)/24) DAY) < CURRENT_DATE
		AND gv.ocurre=".(($_GET[tipo]==0)?0:1)." 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino = '" .$_SESSION[IDSUCURSAL]."'" :"")."
		GROUP BY gv.id 
		UNION
		SELECT ge.id AS guia,ge.fecha AS fechaemision,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS remitente,
		CONCAT_WS(' ',ccc.nombre,ccc.paterno,ccc.materno) AS destinatario,
		UCASE(CONCAT(d.calle,' #',d.numero,' ',d.colonia,' ',d.poblacion)) AS direccion, 
		cs.descripcion AS sector, ge.tipoflete, 
		ge.total AS importe,IF(ge.ocurre=1,'OCURRE','EAD') AS tipoentrega  
		FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON ge.idremitente=cc.id
		INNER JOIN catalogocliente ccc ON ge.iddestinatario=ccc.id
		INNER JOIN direccion d ON ge.iddirecciondestinatario=d.id
		LEFT JOIN catalogosector cs ON ge.sector=cs.id
		WHERE ge.estado = 'ALMACEN DESTINO' AND
		ADDDATE(ge.fecha, INTERVAL (IF(ge.entregaocurre=0 OR ISNULL(ge.entregaocurre),ge.entregaocurre,24)/24) DAY) < CURRENT_DATE
		AND ge.ocurre=".(($_GET[tipo]==0)?0:1)." 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursaldestino = '" .$_SESSION[IDSUCURSAL]."'" :"")."
		GROUP BY ge.id 
		)Tabla ORDER BY remitente,destinatario,direccion $limite";
		//die($s);	
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->sector = cambio_texto($f->sector);
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
			
	}else if($_GET[accion]==6){//Entregas Por Vencer.php
		$s = "SELECT diasvencimientoconvenio FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		
		$s = "SELECT folio AS convenio,idcliente,CONCAT(nombre,' ',apaterno,' ',amaterno)AS cliente,
		CONCAT(calle,' ',numero,' ',colonia,' ',poblacion) AS direccion,
		DATE_FORMAT(vigencia,'%d/%m/%Y') AS fechavencimiento,
		0 AS tipoconvenio,0 AS precios,ifnull(nvendedor,'') AS vendedorasignado FROM generacionconvenio 
		WHERE ".(($_SESSION[IDSUCURSAL]!=1)? "sucursal='".$_SESSION[IDSUCURSAL]."' AND ":"")."
		DATEDIFF(vigencia,CURDATE()) <= '".$f->diasvencimientoconvenio."'
		ORDER BY nombre,apaterno,amaterno";	
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT folio AS convenio,idcliente,CONCAT(nombre,' ',apaterno,' ',amaterno)AS cliente,
		CONCAT(calle,' ',numero,' ',colonia,' ',poblacion) AS direccion,
		DATE_FORMAT(vigencia,'%d/%m/%Y') AS fechavencimiento,
		0 AS tipoconvenio,0 AS precios,ifnull(nvendedor,'') AS vendedorasignado FROM generacionconvenio 
		WHERE ".(($_SESSION[IDSUCURSAL]!=1)? "sucursal='".$_SESSION[IDSUCURSAL]."' AND ":"")."
		DATEDIFF(vigencia,CURDATE()) <= '".$f->diasvencimientoconvenio."'
		ORDER BY nombre,apaterno,amaterno $limite";			
		$r = mysql_query($s,$l) or die($s);
		$arr = array();			
		while($f = mysql_fetch_object($r)){
		$f->cliente = cambio_texto($f->cliente);
		$f->direccion = cambio_texto($f->direccion);
		$f->vendedorasignado = cambio_texto($f->vendedorasignado);
		
		$sql="SELECT precioporkg,precioporcaja,descuentosobreflete,
					prepagadas,consignacionkg,consignacioncaja,
					consignaciondescuento FROM generacionconvenio WHERE folio='$f->convenio'";
				$d = mysql_query($sql,$l) or die($sql);
				if(mysql_num_rows($d)>0){
					$t = mysql_fetch_object($d);
					$conbinacion=0;
					if ($t->precioporkg!=0){
						$conbinacion1='KILOGRAMO'; 
					}
					
					if ($t->precioporcaja!=0){
						$conbinacion2='PAQUETE'; 
					}
					
					if ($t->descuentosobreflete!=0){
						$conbinacion3='DESCUENTO'; 
					}
					
					if ($t->prepagadas!=0){
						$conbinacion4='PREPAGADAS'; 
					}
					
					if ($t->consignacionkg!=0){
						$conbinacion5='KILOGRAMO'; 
					}
					
					if ($t->consignacioncaja!=0){
						$conbinacion6='PAQUETE'; 
					}
					
					if ($t->consignaciondescuento!=0){
						$conbinacion7='DESCUENTO'; 
					}
					if ($conbinacion1!="" or $conbinacion2!="" or $conbinacion3!=""){
						$conbinacion8='GUIA NORMAL-'.$conbinacion1.$conbinacion2.$conbinacion3;	
					}
					
					if ($conbinacion4!=""  or $conbinacion5!="" or $conbinacion6!="" or $conbinacion7!=""){
						$conbinacion9='GUIA EMPRESARIAL-'.$conbinacion4.$conbinacion5.$conbinacion6.$conbinacion7;		
					}
					
					$f->tipoconvenio=$conbinacion8.$conbinacion9;
				}
		
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
				
	}else if($_GET[accion]==7){//Evaluacion Pendiente Generar Guia
		$s = "SELECT * FROM evaluacionmercancia WHERE estado = 'GUARDADO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal=".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT fechaevaluacion, folio, recoleccion, guiaempresarial FROM evaluacionmercancia
		WHERE estado = 'GUARDADO' ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal=".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){				
			$f->guiaempresarial = cambio_texto($f->guiaempresarial);
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
		
	}else if($_GET[accion]==8){//Facturas Canceladas
		$s = "SELECT * FROM facturacion WHERE facturaestado = 'CANCELADO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal=".$_SESSION[IDSUCURSAL]."":"")."
		GROUP BY folio";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT f.folio, date_format(f.fecha,'%d/%m/%Y') as fecha,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		f.tipofactura, (f.total + f.sobmontoafacturar + f.otrosmontofacturar) AS importe,
		date_format(f.fechacancelacion,'%d/%m/%Y') as fechacancelacion
		FROM facturacion f
		INNER JOIN catalogocliente cc ON f.cliente = cc.id
		WHERE f.facturaestado = 'CANCELADO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal=".$_SESSION[IDSUCURSAL]."":"")."		
		GROUP BY f.folio";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){				
			$f->cliente = cambio_texto($f->cliente);
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
		
	}else if($_GET[accion]==9){//Guias Canceladas
	$s = "SELECT guia, accion, tipo FROM historial_cancelacionysustitucion
	WHERE ((tipo='LOCAL' AND accion='CANCELADO') OR (tipo='FORANEA' AND accion='SUSTITUCION REALIZADA'))
	".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal='".$_SESSION[IDSUCURSAL]."'":"")."";
	$r = mysql_query($s,$l) or die($s);
	$totalregistros = mysql_num_rows($r);		
		
	$totales = 0;
	
	$s = "SELECT guia, accion, tipo, CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) usuario
	FROM historial_cancelacionysustitucion hc
	INNER JOIN catalogoempleado ce ON hc.usuario = ce.id
	WHERE ((tipo='LOCAL' AND accion='CANCELADO') OR (tipo='FORANEA' AND accion='SUSTITUCION REALIZADA'))
	".(($_SESSION[IDSUCURSAL]!=1)? " AND  hc.sucursal='".$_SESSION[IDSUCURSAL]."'":"")." $limite";
	$arr = array();
	$rx = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($rx)){
			if($f->tipo == "LOCAL"){
				$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
				o.prefijo AS origen,
				CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
				d.prefijo AS destino,
				CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
				IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete,gv.total,
				DATE_FORMAT(can.fecha,'%d/%m/%Y') AS fechacancelacion,
				cm.descripcion AS motivo, 
				'$f->usuario' usuario
				FROM guiasventanilla gv
				INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
				INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
				INNER JOIN catalogocliente re ON gv.idremitente = re.id
				INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
				INNER JOIN cancelacionguiasventanilla can ON gv.id = can.guia
				INNER JOIN catalogomotivos cm ON can.motivocancelacion = cm.id
				INNER JOIN catalogoempleado ce ON can.usuario = ce.id
				WHERE gv.id='".$f->guia."'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen='".$_SESSION[IDSUCURSAL]."'":"")."";
				$r = mysql_query($s,$l) or die($s);
				$ff = mysql_fetch_object($r);
				
			}else if($f->tipo == "FORANEA"){
				$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
				o.prefijo AS origen,
				CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
				d.prefijo AS destino,
				CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
				IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total,
				DATE_FORMAT(h.fecha,'%d/%m/%Y') AS fechacancelacion,
				cs.motivocancelacion AS motivo,  
				'$f->usuario' usuario
				FROM historial_cancelacionysustitucion h
				INNER JOIN guiasventanilla gv ON h.guia = gv.id
				INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
				INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
				INNER JOIN catalogocliente re ON gv.idremitente = re.id
				INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
				INNER JOIN guiasventanilla_cs cs ON h.guia = cs.folioguia
				INNER JOIN catalogoempleado ce ON h.idusuario = ce.id
				WHERE h.guia='".$f->guia."'
				".(($_SESSION[IDSUCURSAL]!=1)? " AND  h.sucursal='".$_SESSION[IDSUCURSAL]."'":"")."";
				$r = mysql_query($s,$l) or die($s);
				$ff = mysql_fetch_object($r);				
			}
				$f->fechaemision	= $ff->fecha;
				$f->origen 			= cambio_texto($ff->origen);
				$f->remitente 		= cambio_texto($ff->remitente);
				$f->destinatario 	= cambio_texto($ff->destinatario);
				$f->destino 		= cambio_texto($ff->destino);
				$f->remitente 		= cambio_texto($ff->remitente);
				$f->tipoflete 		= cambio_texto($ff->tipoflete);
				$f->importe 			= $ff->total;
				$f->fechacancelacion 	= $ff->fechacancelacion;
				$f->motivo		 	= $ff->motivo;
				//$f->usuario 			= $ff->usuario;
				//echo print_r($f);
				$arr[] 			= $f;				
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==10){//Guias Con danos
		$s = "SELECT * FROM reportedanosfaltante
		WHERE dano = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;		
		
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino,
		rd.id AS foliodano, 
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
		DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecharecepcion, DATE_FORMAT(em.fecha,'%d/%m/%Y') AS fechaembarque
		FROM reportedanosfaltante rd
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		INNER JOIN guiasventanilla gv ON rd.guia = gv.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal
		INNER JOIN embarquedemercanciadetalle emd ON emd.guia = rd.guia
		INNER JOIN embarquedemercancia em ON emd.idembarque = em.folio AND emd.sucursal = em.idsucursal
		WHERE rd.dano = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND rd.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino,
		rd.id AS foliodano, 
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
		DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecharecepcion, DATE_FORMAT(em.fecha,'%d/%m/%Y') AS fechaembarque
		FROM reportedanosfaltante rd
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		INNER JOIN guiasempresariales ge ON rd.guia = ge.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal
		INNER JOIN embarquedemercanciadetalle emd ON emd.guia = rd.guia
		INNER JOIN embarquedemercancia em ON emd.idembarque = em.folio AND emd.sucursal = em.idsucursal
		WHERE rd.dano = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND rd.sucursal = ".$_SESSION[IDSUCURSAL]."":"");
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);			
			$f->destino = cambio_texto($f->destino);
			$f->responsable = cambio_texto($f->responsable);			
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
		
	}else if($_GET[accion]==11){//Guias Extraviadas
		if($_SESSION[IDSUCURSAL]!=1){
			$where = "WHERE idsucursal = ".$_SESSION[IDSUCURSAL]."	";
			$where2 = "WHERE rd.idsucursal = ".$_SESSION[IDSUCURSAL]."	";
		}
		
		$s = "SELECT * FROM moduloquejasdanosfaltantes $where";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, rd.folio AS foliodano, 
		DATE_FORMAT(em.fecha,'%d/%m/%Y') AS fechaembarque,DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecharecepcion
		FROM moduloquejasdanosfaltantes rd
		INNER JOIN guiasventanilla gv ON rd.nguia = gv.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN recepcionmercanciadetalle rde ON rd.nguia = rde.guia
		INNER JOIN recepcionmercancia r ON rde.recepcion = r.folio AND rde.sucursal = r.idsucursal
		INNER JOIN embarquedemercanciadetalle emd ON emd.guia = rd.nguia
		INNER JOIN embarquedemercancia em ON emd.idembarque = em.folio AND emd.sucursal = em.idsucursal $where2
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, rd.folio AS foliodano, 
		DATE_FORMAT(em.fecha,'%d/%m/%Y') AS fechaembarque,DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecharecepcion
		FROM moduloquejasdanosfaltantes rd
		INNER JOIN guiasempresariales ge ON rd.nguia = ge.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		INNER JOIN recepcionmercanciadetalle rde ON rd.nguia = rde.guia
		INNER JOIN recepcionmercancia r ON rde.recepcion = r.folio AND rde.sucursal = r.idsucursal
		INNER JOIN embarquedemercanciadetalle emd ON emd.guia = rd.nguia
		INNER JOIN embarquedemercancia em ON emd.idembarque = em.folio AND emd.sucursal = em.idsucursal $where2";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->destino = cambio_texto($f->destino);
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
		
	}else if($_GET[accion]==12){//Guias Faltantes
		$s = "SELECT * FROM reportedanosfaltante
		WHERE faltante = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		/*$s = "SELECT t.guia, t.fecha, t.remitente, t.destinatario,t.destino, rd.id AS foliodano, 
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
		DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecharecepcion, DATE_FORMAT(em.fecha,'%d/%m/%Y') AS fechaembarque
		FROM reportedanosfaltante rd
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		INNER JOIN (SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id) t ON rd.guia = t.guia
		INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal
		INNER JOIN embarquedemercanciadetalle emd ON emd.guia = rd.guia
		INNER JOIN embarquedemercancia em ON emd.idembarque = em.folio AND emd.sucursal = em.idsucursal
		WHERE rd.faltante = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND rd.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";*/
		
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino,
		rd.id AS foliodano, 
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
		DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecharecepcion, DATE_FORMAT(em.fecha,'%d/%m/%Y') AS fechaembarque
		FROM reportedanosfaltante rd
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		INNER JOIN guiasventanilla gv ON rd.guia = gv.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal
		INNER JOIN embarquedemercanciadetalle emd ON emd.guia = rd.guia
		INNER JOIN embarquedemercancia em ON emd.idembarque = em.folio AND emd.sucursal = em.idsucursal
		WHERE rd.faltante = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND rd.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino,
		rd.id AS foliodano, 
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable,
		DATE_FORMAT(r.fecha,'%d/%m/%Y') AS fecharecepcion, DATE_FORMAT(em.fecha,'%d/%m/%Y') AS fechaembarque
		FROM reportedanosfaltante rd
		INNER JOIN catalogoempleado ce ON rd.empleado1 = ce.id
		INNER JOIN guiasempresariales ge ON rd.guia = ge.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal
		INNER JOIN embarquedemercanciadetalle emd ON emd.guia = rd.guia
		INNER JOIN embarquedemercancia em ON emd.idembarque = em.folio AND emd.sucursal = em.idsucursal
		WHERE rd.faltante = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND rd.sucursal = ".$_SESSION[IDSUCURSAL]."":"");
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);			
			$f->destino = cambio_texto($f->destino);
			$f->responsable = cambio_texto($f->responsable);
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
		
	}else if($_GET[accion]==13){//Guias Faltantes de Recoleccion Ead
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==14){//Guias No Embarcadas
		$s = "SELECT * FROM
		(SELECT gv.id FROM guiasventanilla gv		
		WHERE gv.estado = 'ALMACEN ORIGEN' AND gv.id NOT LIKE '%Z'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id FROM guiasempresariales ge
		WHERE ge.estado = 'ALMACEN ORIGEN' AND ge.id NOT LIKE '%Z'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") tb";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * FROM (
		SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, o.prefijo AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		d.prefijo AS destino, CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		cs.descripcion AS sector, IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total,
		IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		LEFT JOIN catalogosector cs ON gv.sector = cs.id
		WHERE gv.estado = 'ALMACEN ORIGEN' AND SUBSTRING(gv.id,1,3)<>'888' AND gv.id NOT LIKE '%Z'  
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, o.prefijo AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		d.prefijo AS destino, CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		cs.descripcion AS sector, IF(ge.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, ge.total,
		IF(ge.ocurre=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		LEFT JOIN catalogosector cs ON ge.sector = cs.id
		WHERE ge.estado = 'ALMACEN ORIGEN' AND ge.id NOT LIKE '%Z'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."
		UNION
		SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, o.prefijo AS origen,
		CONCAT_WS(' ',r.nombre,r.apellidopaterno,r.apellidomaterno) AS remitente,
		d.prefijo AS destino, CONCAT_WS(' ',de.nombre,de.apellidopaterno,de.apellidomaterno) AS destinatario,
		cs.descripcion AS sector, IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total,
		IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogoempleado r ON gv.idremitente = r.id
		INNER JOIN catalogoempleado de ON gv.iddestinatario = de.id
		LEFT JOIN catalogosector cs ON gv.sector = cs.id
		WHERE gv.estado = 'ALMACEN ORIGEN' AND SUBSTRING(gv.id,1,3)='888' AND gv.id NOT LIKE '%Z'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."
		) tb
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->origen = cambio_texto($f->origen);
			$f->remitente = cambio_texto($f->remitente);
			$f->destino = cambio_texto($f->destino);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->sector = cambio_texto($f->sector);
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
		
	}else if($_GET[accion]==15){//Guias Sin Ruta Clientes Corporativo
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==16){//Entregas Especiales EAD
		$s = "SELECT * FROM entregasespecialesead
		WHERE fechaespecial BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = $_SESSION[IDSUCURSAL]" : "")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "select * from (
		SELECT e.folio, DATE_FORMAT(e.fechaead,'%d/%m/%Y') AS fechaespecial,e.guia, e.observaciones, cs.prefijo AS sucursal,
		IF(e.personarequireead<>'',e.personarequireead,IF(e.opcion2=0,CONCAT_WS(' ',re.nombre,re.paterno,re.materno),
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno))) AS persona,
		gv.estado estadoguia, CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno) registro
		FROM entregasespecialesead e
		INNER JOIN guiasventanilla gv ON gv.id = e.guia
		INNER JOIN catalogoempleado ce ON e.idusuario = ce.id
		INNER JOIN catalogosucursal cs ON e.sucursal = cs.id
		INNER JOIN catalogocliente re ON e.remitente = re.id
		INNER JOIN catalogocliente de ON e.destinatario = de.id
		WHERE e.fechaead BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND e.sucursal = $_SESSION[IDSUCURSAL]" : "")."
		UNION
		SELECT e.folio, DATE_FORMAT(e.fechaead,'%d/%m/%Y') AS fechaespecial,e.guia,e.observaciones,cs.prefijo AS sucursal,
		IF(e.personarequireead<>'',e.personarequireead,IF(e.opcion2=0,CONCAT_WS(' ',re.nombre,re.paterno,re.materno),
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno))) persona,ge.estado AS estadoguia, 
		CONCAT_WS(' ',ce.nombre, ce.apellidopaterno, ce.apellidomaterno) registro
		FROM entregasespecialesead e
		INNER JOIN guiasempresariales ge ON ge.id = e.guia
		INNER JOIN catalogoempleado ce ON e.idusuario = ce.id
		INNER JOIN catalogosucursal cs ON e.sucursal = cs.id
		INNER JOIN catalogocliente re ON e.remitente = re.id
		INNER JOIN catalogocliente de ON e.destinatario = de.id
		WHERE e.fechaead BETWEEN '".cambiaf_a_mysql($_GET[inicio])."' AND '".cambiaf_a_mysql($_GET[fin])."'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND e.sucursal = $_SESSION[IDSUCURSAL]" : "")."  ) t  $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){				
			$f->persona = cambio_texto($f->persona);
			$f->sucursal = cambio_texto($f->sucursal);
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
		
	}else if($_GET[accion]==17){//Guias Sobrantes
		$s = "";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
				//Por si la consulta trae descripciones o nombres con acentos --- BORRAR ESTE COMENTARIO
				//$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==18){//Recolecciones Atrasadas
		$s = "SELECT * FROM recoleccion
		WHERE fecharegistro < CURDATE() AND estado<>'REALIZADO' AND estado<>'CANCELADO'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT DISTINCT r.folio, DATE_FORMAT(r.fecharegistro,'%d/%m/%Y')AS fecharegistro,
		rd.contenido, rd.descripcion,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' Col. ',r.colonia,', ',r.poblacion) AS direccion,
		d.descripcion AS destino FROM recoleccion r
		INNER JOIN recolecciondetalle rd ON r.folio = rd.recoleccion
		INNER JOIN catalogocliente cc ON r.cliente = cc.id
		INNER JOIN catalogodestino d ON r.destino = d.id
		WHERE r.fecharegistro < CURDATE() AND r.estado<>'REALIZADO' AND r.estado<>'CANCELADO'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND r.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."
		GROUP BY r.folio $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->folio = cambio_texto($f->folio);
			$f->contenido = cambio_texto($f->contenido);
			$f->descripcion = cambio_texto($f->descripcion);
			$f->cliente = cambio_texto($f->cliente);
			$f->direccion = cambio_texto($f->direccion);
			$f->destino = cambio_texto($f->destino);
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
		
	}else if($_GET[accion]==19){//Retraso De Almacen
		$s = "SELECT g.guia, g.fecha, g.remitente, g.destinatario,
		g.direccion, g.tipoflete, g.total, DATEDIFF(g.fecha, rm.fecha) AS diasalmacen
		FROM recepcionmercancia rm
		INNER JOIN recepcionmercanciadetalle rd ON rm.folio = rd.recepcion
		INNER JOIN (SELECT gv.id AS guia, gv.estado, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',d.nombre,d.paterno,d.materno) AS destinatario,
		CONCAT(dir.calle,' #',dir.numero,' Col.',dir.colonia,', ',dir.poblacion) AS direccion,
		IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total
		FROM guiasventanilla gv 
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente d ON gv.iddestinatario = d.id
		INNER JOIN direccion dir ON gv.iddirecciondestinatario = dir.id
		WHERE gv.estado='ALMACEN DESTINO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id AS guia, ge.estado, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',d.nombre,d.paterno,d.materno) AS destinatario,
		CONCAT(dir.calle,' #',dir.numero,' Col.',dir.colonia,', ',dir.poblacion) AS direccion,
		ge.tipoflete, ge.total FROM guiasempresariales ge
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente d ON ge.iddestinatario = d.id
		INNER JOIN direccion dir ON ge.iddirecciondestinatario = dir.id
		WHERE ge.estado='ALMACEN DESTINO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") g ON rd.guia = g.guia";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){				
					$f->remitente = cambio_texto($f->remitente);
					$f->destinatario = cambio_texto($f->destinatario);
					$f->direccion = cambio_texto($f->direccion);					
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
			
	}else if($_GET[accion] == 20){
		$s = "SELECT c.id, CONCAT_WS(' ',c.nombre,c.paterno,c.materno) AS nombre,
		CONCAT(d.calle,' #',d.numero,', ',d.colonia) AS direccion,
		d.poblacion, d.estado, IFNULL(s.montoautorizado,0) AS credito
		FROM catalogocliente c 
		INNER JOIN direccion d ON c.id = d.codigo AND d.origen = 'cl'
		LEFT JOIN generacionconvenio co ON c.id = co.idcliente
		LEFT JOIN solicitudcredito s ON c.id = s.cliente
		WHERE c.tipocliente = 2 AND d.facturacion = 'SI'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND c.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT c.id, CONCAT_WS(' ',c.nombre,c.paterno,c.materno) AS nombre,
		CONCAT(d.calle,' #',d.numero,', ',d.colonia) AS direccion,
		d.poblacion, d.estado, IFNULL(s.montoautorizado,0) AS credito,
		IFNULL(co.folio,'') AS convenio, 
		CONCAT(IF(co.descuentosobreflete=1 OR co.consignaciondescuento=1,'DESCUENTO,', ''), 
		IF(co.precioporkg=1 OR co.consignacionkg=1,'KILOGRAMO,',''), IF(co.precioporcaja=1 
		OR co.consignacioncaja=1,'PAQUETE,',''), IF(co.prepagadas=1,'PREPAGADA,','')) AS tipoconvenio 
		FROM catalogocliente c 
		INNER JOIN direccion d ON c.id = d.codigo AND d.origen = 'cl'
		LEFT JOIN generacionconvenio co ON c.id = co.idcliente
		LEFT JOIN solicitudcredito s ON c.id = s.cliente
		WHERE c.tipocliente = 2 AND d.facturacion = 'SI'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND c.sucursal = ".$_SESSION[IDSUCURSAL]."":"")."
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
			$f->direccion = cambio_texto($f->direccion);
			$f->poblacion = cambio_texto($f->poblacion);
			$f->estado = cambio_texto($f->estado);
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
			
	}else if($_GET[accion]==21){
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',rm.nombre,rm.paterno,rm.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(gv.sector,0) AS sector FROM guiasventanilla gv
		INNER JOIN catalogocliente rm ON gv.idremitente = rm.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.estado = 'ALMACEN DESTINO' AND gv.entradasalida = 'SALIDA'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen=$_SESSION[IDSUCURSAL]":"")."
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',rm.nombre,rm.paterno,rm.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(ge.sector,0) AS sector FROM guiasempresariales ge
		INNER JOIN catalogocliente rm ON ge.idremitente = rm.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.estado = 'ALMACEN DESTINO' AND ge.entradasalida = 'SALIDA'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen=$_SESSION[IDSUCURSAL]":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * FROM(SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',rm.nombre,rm.paterno,rm.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(gv.sector,0) AS sector FROM guiasventanilla gv
		INNER JOIN catalogocliente rm ON gv.idremitente = rm.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.estado = 'ALMACEN DESTINO' AND gv.entradasalida = 'SALIDA'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen=$_SESSION[IDSUCURSAL]":"")."
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',rm.nombre,rm.paterno,rm.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(ge.sector,0) AS sector FROM guiasempresariales ge
		INNER JOIN catalogocliente rm ON ge.idremitente = rm.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.estado = 'ALMACEN DESTINO' AND ge.entradasalida = 'SALIDA'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen=$_SESSION[IDSUCURSAL]":"").")t
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->guia = cambio_texto($f->guia);
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
		
	}else if($_GET[accion]==22){//CREDITO LINEA SATURADA
		$s = "SELECT porcentajelimitecredito AS porcentaje FROM configuradorgeneral";
		$r = mysql_query($s,$l) or die($s);
		$c = mysql_fetch_object($r);
		
		$s = "SELECT sc.folio as credito, sc.montoautorizado as limitecredito,
		cc.id, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		CONCAT(sc.calle,' #',sc.numero,' ',sc.colonia,' ',sc.poblacion) AS direccion,
		IFNULL(pg.consumido,0) AS consumido,IFNULL(pgs.vencido,0) AS montovencido,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS vendedorasigando
		FROM solicitudcredito sc
		INNER JOIN catalogocliente cc ON sc.cliente = cc.id
		INNER JOIN catalogoempleado ce ON sc.idusuario = ce.id
		INNER JOIN (SELECT cliente, IFNULL(SUM(total),0) AS consumido FROM pagoguias
		WHERE pagado='S' GROUP BY cliente) pg ON cc.id = pg.cliente
		LEFT JOIN (SELECT pg.cliente, IFNULL(SUM(total),0) AS vencido FROM pagoguias pg
		INNER JOIN solicitudcredito sc ON pg.cliente = sc.cliente
		WHERE DATEDIFF(fechacreo,CURDATE()) > sc.diascredito GROUP BY pg.cliente) pgs ON cc.id = pgs.cliente
		WHERE ".(($_SESSION[IDSUCURSAL]!=1)? " cc.sucursal = ".$_SESSION[IDSUCURSAL]." AND ":"")."
		sc.estado='ACTIVADO' AND cc.activado='SI'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT sc.folio as credito, sc.montoautorizado as limitecredito,
		cc.id, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		CONCAT(sc.calle,' #',sc.numero,' ',sc.colonia,' ',sc.poblacion) AS direccion,
		IFNULL(pg.consumido,0) AS consumido,IFNULL(pgs.vencido,0) AS montovencido,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS vendedorasigando
		FROM solicitudcredito sc
		INNER JOIN catalogocliente cc ON sc.cliente = cc.id
		INNER JOIN catalogoempleado ce ON sc.idusuario = ce.id
		INNER JOIN (SELECT cliente, IFNULL(SUM(total),0) AS consumido FROM pagoguias
		WHERE pagado='S' GROUP BY cliente) pg ON cc.id = pg.cliente
		LEFT JOIN (SELECT pg.cliente, IFNULL(SUM(total),0) AS vencido FROM pagoguias pg
		INNER JOIN solicitudcredito sc ON pg.cliente = sc.cliente
		WHERE DATEDIFF(fechacreo,CURDATE()) > sc.diascredito GROUP BY pg.cliente) pgs ON cc.id = pgs.cliente
		WHERE ".(($_SESSION[IDSUCURSAL]!=1)? " cc.sucursal = ".$_SESSION[IDSUCURSAL]." AND ":"")."
		sc.estado='ACTIVADO' AND cc.activado='SI' $limite";		
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$s = "SELECT limitecredito * ".$c->porcentaje."/100.00 AS limite FROM catalogocliente
			WHERE id = ".$f->id."";
			$cl = mysql_query($s,$l) or die($s);
			$cli = mysql_fetch_object($cl);
			
			$s = "SELECT $f->limitecredito - IFNULL(SUM(IF(pagado='N', total,0)),0) AS disponible
			FROM pagoguias WHERE cliente = '".$f->id."'";
			$rx = mysql_query($s,$l) or die($s);
			$fx = mysql_fetch_object($rx);			
			if($fx->disponible <= $cli->limite){
				$f->cliente = cambio_texto($f->cliente);
				$f->direccion = cambio_texto($f->direccion);
				$f->limitecredito = $cli->limite;
				$arr[] = $f;
			}
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_GET[accion]==23){//HISTORIAL GUIAS EN TRANSITO
		$s = "SELECT id FROM guiasventanilla		
		WHERE estado = 'EN TRANSITO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT id FROM guiasempresariales WHERE estado = 'EN TRANSITO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT * FROM 
		(SELECT gv.id, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, cs.descripcion AS destino, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(gv.total,0) AS total, IF(gv.ocurre=1,'OCURRE','EAD') AS tipoentrega
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON gv.idsucursaldestino = cs.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.estado = 'EN TRANSITO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, cs.descripcion AS destino, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IFNULL(ge.total,0) AS total, IF(ge.ocurre=1,'OCURRE','EAD') AS tipoentrega
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal cs ON ge.idsucursaldestino = cs.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.estado = 'EN TRANSITO' 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") t
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->destino = cambio_texto($f->destino);
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->tipoentrega = cambio_texto($f->tipoentrega);
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
		
	}else if($_GET[accion]==24){
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		CONCAT(dir.calle,' #',dir.numero,' COL.',dir.colonia) AS direccion,
		IFNULL(gv.sector,0) AS sector, IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete,
		gv.total AS importe, IF(gv.ocurre=0,'EAD','OCURRE') AS entrega 
		FROM guiasventanilla gv
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN direccion dir ON gv.iddirecciondestinatario = dir.id
		WHERE gv.estado = 'ALMACEN DESTINO' AND re.tipocliente=2 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		CONCAT(dir.calle,' #',dir.numero,' COL.',dir.colonia) AS direccion,
		IFNULL(gv.sector,0) AS sector, IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete,
		gv.total AS importe, IF(gv.ocurre=0,'EAD','OCURRE') AS entrega 
		FROM guiasventanilla gv
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN direccion dir ON gv.iddirecciondestinatario = dir.id
		WHERE gv.estado = 'ALMACEN DESTINO' AND re.tipocliente=2 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		$limite";		
		$r = mysql_query($s,$l) or die($s);
		$arr = array();		
		while($f = mysql_fetch_object($r)){
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->direccion = cambio_texto($f->direccion);
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
		
	}else if($_GET[accion]==25){//GUIAS PENDIENTES DE FACTURAR
		$s = "SELECT * FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE IF(gv.tipoflete = 1 AND gv.condicionpago = 1, gv.estado = 'ALMACEN DESTINO', gv.tipoflete = 0 AND gv.condicionpago = 1 AND gv.estado <> 'CANCELADO')
		AND (gv.factura IS NULL OR gv.factura=0)
		".(($_SESSION[IDSUCURSAL]!=1)? " AND pg.sucursalacobrar = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS remitente,
		ori.prefijo AS origen, des.prefijo AS destino, gv.total AS importe, if(gv.condicionpago=0,'CONTADO','CREDITO') AS tipopago
		FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
		INNER JOIN pagoguias pg ON gv.id = pg.guia
		WHERE IF(gv.tipoflete = 1 AND gv.condicionpago = 1, gv.estado = 'ALMACEN DESTINO', gv.tipoflete = 0 AND gv.condicionpago = 1 AND gv.estado <> 'CANCELADO') 
		AND (gv.factura IS NULL OR gv.factura=0)
		".(($_SESSION[IDSUCURSAL]!=1)? " AND pg.sucursalacobrar = ".$_SESSION[IDSUCURSAL]."":"")."
		ORDER BY tipopago DESC
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->remitente = cambio_texto($f->remitente);
			$f->guia = cambio_texto($f->guia);
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
		
	}else if($_GET[accion]==26){//Cobranza 60 dias	
		$s = "SELECT * FROM cobranza60dias_tmp		
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT factura, date_format(fechaemision,'%d/%m/%Y') as fechaemision,
		cliente, nombre, importe, tipofactura, date_format(fechavencimiento,'%d/%m/%Y') as fechavencimiento,
		diasatraso, contrarecibo, diapago FROM cobranza60dias_tmp
		WHERE idusuario = ".$_SESSION[IDUSUARIO]."
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->nombre = cambio_texto($f->nombre);
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
			
	}else if($_GET[accion] == 27){
		$s = "SELECT * FROM recoleccion		
		WHERE fecharegistro = CURDATE() AND ".(($_SESSION[IDSUCURSAL]!=1)? " sucursal = $_SESSION[IDSUCURSAL] AND " :"")."
		realizo = 'NO' AND estado<>'CANCELADO'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);	
		
		$totales = 0;
		
		$s = "SELECT DISTINCT r.folio, ".(($_SESSION[IDSUCURSAL]==1)? "cs.prefijo AS sucursal," :"")."
		DATE_FORMAT(r.fecharegistro,'%d/%m/%Y')AS fecharegistro, rd.contenido, rd.descripcion,
		CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente,
		CONCAT(r.calle,' #',r.numero,' Col. ',r.colonia,', ',r.poblacion) AS direccion,
		d.descripcion AS destino FROM recoleccion r
		INNER JOIN recolecciondetalle rd ON r.folio = rd.recoleccion
		INNER JOIN catalogocliente cc ON r.cliente = cc.id
		INNER JOIN catalogodestino d ON r.destino = d.id
		INNER JOIN catalogosucursal cs ON r.sucursal = cs.id
		WHERE r.fecharegistro = CURDATE() AND ".(($_SESSION[IDSUCURSAL]!=1)? " r.sucursal = $_SESSION[IDSUCURSAL] AND " :"")."
		r.realizo = 'NO' AND r.estado<>'CANCELADO'
		GROUP BY r.folio, r.sucursal $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->folio = cambio_texto($f->folio);
			$f->sucursal = cambio_texto($f->sucursal);
			$f->contenido = cambio_texto($f->contenido);
			$f->descripcion = cambio_texto($f->descripcion);
			$f->cliente = cambio_texto($f->cliente);
			$f->direccion = cambio_texto($f->direccion);
			$f->destino = cambio_texto($f->destino);
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
		
	}else if($_GET[accion]==28){
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, o.descripcion AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total,
		IF(gv.tipoflete=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.estado = 'ALMACEN ORIGEN' 
		".(($_SESSION[IDSUCURSAL]!=1)?" AND gv.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."":"")."
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, o.descripcion AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IF(ge.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, ge.total,
		IF(ge.tipoflete=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.estado = 'ALMACEN ORIGEN'
		".(($_SESSION[IDSUCURSAL]!=1)?" AND ge.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, o.descripcion AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IF(gv.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, gv.total,
		IF(gv.tipoflete=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal o ON gv.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		WHERE gv.estado = 'ALMACEN ORIGEN'
		".(($_SESSION[IDSUCURSAL]!=1)?" AND gv.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."":"")."
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, o.descripcion AS origen,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		IF(ge.tipoflete=0,'PAGADO','POR COBRAR') AS tipoflete, ge.total,
		IF(ge.tipoflete=0,'EAD','OCURRE') AS tipoentrega
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		WHERE ge.estado = 'ALMACEN ORIGEN' 
		".(($_SESSION[IDSUCURSAL]!=1)?" AND ge.idsucursaldestino = ".$_SESSION[IDSUCURSAL]."":"")."";		
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->destinatario = cambio_texto($f->destinatario);
			$f->remitente = cambio_texto($f->remitente);
			$f->guia = cambio_texto($f->guia);
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
		
	}else if($_GET[accion]==29){//Guias Sobrantes
		$s = "SELECT * FROM
		(SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, DATE_FORMAT(emb.fecha,'%d/%m/%Y') AS fechaembarque,
		DATE_FORMAT(rec.fecha,'%d/%m/%Y') AS fecharecepcion, 
		rdf.id AS foliodano,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.idremitente = de.id
		INNER JOIN (SELECT e.fecha, ed.guia FROM embarquedemercancia e
		INNER JOIN embarquedemercanciadetalle ed ON e.folio = ed.idembarque and e.idsucursal = ed.sucursal) emb ON emb.guia = gv.id
		INNER JOIN (SELECT re.fecha, rd.guia FROM recepcionmercancia re
		INNER JOIN recepcionmercanciadetalle rd ON re.folio = rd.recepcion and re.idsucursal = rd.sucursal) rec ON gv.id = rec.guia
		INNER JOIN reportedanosfaltante rdf ON gv.id = rdf.guia
		INNER JOIN catalogoempleado ce ON rdf.empleado1 = ce.id
		WHERE rdf.sobrante = 1 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, DATE_FORMAT(emb.fecha,'%d/%m/%Y') AS fechaembarque,
		DATE_FORMAT(rec.fecha,'%d/%m/%Y') AS fecharecepcion, 
		rdf.id AS foliodano,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.idremitente = de.id
		INNER JOIN (SELECT e.fecha, ed.guia FROM embarquedemercancia e
		INNER JOIN embarquedemercanciadetalle ed ON e.folio = ed.idembarque and e.idsucursal = ed.sucursal) emb ON emb.guia = ge.id
		INNER JOIN (SELECT re.fecha, rd.guia FROM recepcionmercancia re
		INNER JOIN recepcionmercanciadetalle rd ON re.folio = rd.recepcion and re.idsucursal = rd.sucursal) rec ON ge.id = rec.guia
		INNER JOIN reportedanosfaltante rdf ON ge.id = rdf.guia
		INNER JOIN catalogoempleado ce ON rdf.empleado1 = ce.id
		WHERE rdf.sobrante = 1 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") tb";
		if($_GET[tipo]=="0"){
			$r = mysql_query($s,$l) or die($s);
			echo mysql_num_rows($r);
			
		}else if($_GET[tipo]=="1"){
			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);
			$registros = array();
				if(mysql_num_rows($r)>0){
					while($f = mysql_fetch_object($r)){
						$f->remitente = cambio_texto($f->remitente);
						$f->destinatario = cambio_texto($f->destinatario);
						$f->destino = cambio_texto($f->destino);
						$f->responsable = cambio_texto($f->responsable);
						$registros[] = $f;
					}
					echo str_replace('null','""',json_encode($registros));
				}else{
					echo "no encontro";
				}
		}
		
	}else if($_GET[accion] == "ultimosobrante"){//ULTIMO SOBRANTE
		$s = "SELECT COUNT(*) AS total FROM reportedanosfaltante 
		WHERE sobrante = 1 ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;
		
		$s = "SELECT * FROM
		(SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, DATE_FORMAT(emb.fecha,'%d/%m/%Y') AS fechaembarque,
		DATE_FORMAT(rec.fecha,'%d/%m/%Y') AS fecharecepcion, 
		rdf.id AS foliodano,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal d ON gv.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON gv.idremitente = r.id
		INNER JOIN catalogocliente de ON gv.idremitente = de.id
		INNER JOIN (SELECT e.fecha, ed.guia FROM embarquedemercancia e
		INNER JOIN embarquedemercanciadetalle ed ON e.folio = ed.idembarque and e.idsucursal = ed.sucursal) emb ON emb.guia = gv.id
		INNER JOIN (SELECT re.fecha, rd.guia FROM recepcionmercancia re
		INNER JOIN recepcionmercanciadetalle rd ON re.folio = rd.recepcion and re.idsucursal = rd.sucursal) rec ON gv.id = rec.guia
		INNER JOIN reportedanosfaltante rdf ON gv.id = rdf.guia
		INNER JOIN catalogoempleado ce ON rdf.empleado1 = ce.id
		WHERE rdf.sobrante = 1 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"")."		
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario,
		d.descripcion AS destino, DATE_FORMAT(emb.fecha,'%d/%m/%Y') AS fechaembarque,
		DATE_FORMAT(rec.fecha,'%d/%m/%Y') AS fecharecepcion, 
		rdf.id AS foliodano,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS responsable
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal d ON ge.idsucursaldestino = d.id
		INNER JOIN catalogocliente r ON ge.idremitente = r.id
		INNER JOIN catalogocliente de ON ge.idremitente = de.id
		INNER JOIN (SELECT e.fecha, ed.guia FROM embarquedemercancia e
		INNER JOIN embarquedemercanciadetalle ed ON e.folio = ed.idembarque and e.idsucursal = ed.sucursal) emb ON emb.guia = ge.id
		INNER JOIN (SELECT re.fecha, rd.guia FROM recepcionmercancia re
		INNER JOIN recepcionmercanciadetalle rd ON re.folio = rd.recepcion and re.idsucursal = rd.sucursal) rec ON ge.id = rec.guia
		INNER JOIN reportedanosfaltante rdf ON ge.id = rdf.guia
		INNER JOIN catalogoempleado ce ON rdf.empleado1 = ce.id
		WHERE rdf.sobrante = 1 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."":"").") tb
		LIMIT ".$limit."";
		
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->remitente = cambio_texto($f->remitente);
					$f->destinatario = cambio_texto($f->destinatario);
					$f->destino = cambio_texto($f->destino);
					$f->responsable = cambio_texto($f->responsable);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
	
	}else if($_GET[accion]==30){
		$s = "SELECT f.folio
		FROM facturacion f
		INNER JOIN solicitudcredito sc ON f.cliente = sc.cliente
						WHERE
						(CASE DAYOFWEEK(CURRENT_DATE)
							WHEN 2 THEN sc.lunespago=1
							WHEN 3 THEN sc.martespago=1
							WHEN 4 THEN sc.miercolespago=1
							WHEN 5 THEN sc.juevespago=1
							WHEN 6 THEN sc.viernespago=1
							WHEN 7 THEN sc.sabadopago=1
						END OR sc.semanapago = 1)
		AND f.credito = 'SI' AND f.tipofactura = 'NORMAL' AND f.estadocobranza <> 'C'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal = $_SESSION[IDSUCURSAL]" : "")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT f.folio AS factura, DATE_FORMAT(f.fecha,'%d/%m/%Y') AS fechaemision, 
		CONCAT_WS(' ',f.nombrecliente,f.apellidopaternocliente,f.apellidopaternocliente) AS cliente,
		f.total + f.sobmontoafacturar + f.otrosmontofacturar AS importe 
		FROM facturacion f
		INNER JOIN solicitudcredito sc ON f.cliente = sc.cliente
						WHERE
						(CASE DAYOFWEEK(CURRENT_DATE)
							WHEN 2 THEN sc.lunespago=1
							WHEN 3 THEN sc.martespago=1
							WHEN 4 THEN sc.miercolespago=1
							WHEN 5 THEN sc.juevespago=1
							WHEN 6 THEN sc.viernespago=1
							WHEN 7 THEN sc.sabadopago=1
						END OR sc.semanapago = 1)
		AND credito = 'SI' AND tipofactura = 'NORMAL' AND f.estadocobranza <> 'C'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND f.idsucursal = $_SESSION[IDSUCURSAL]" : "")."		
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->cliente);
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
	}else if($_GET[accion]==31){
		$s = "SELECT pb.folio, pb.renovacionde, DATE_FORMAT(pb.fecha, '%d/%m/%Y') AS fecha, DATE_FORMAT(pb.vigencia, '%d/%m/%Y') vigencia,
		pb.nombre, cs.prefijo AS sucursal, CONCAT_WS(' ', ce.nombre,ce.apellidopaterno, ce.apellidomaterno) empleado
		FROM propuestaconvenio_bitacora pb
		INNER JOIN catalogosucursal cs ON pb.sucursal = cs.id
		INNER JOIN catalogoempleado ce ON pb.idusuario = ce.id
		".(($_SESSION[IDSUCURSAL]!=1)? " where cs.id = $_SESSION[IDSUCURSAL]" : "")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT pb.folio, pb.renovacionde, DATE_FORMAT(pb.fecha, '%d/%m/%Y') AS fecha, DATE_FORMAT(pb.vigencia, '%d/%m/%Y') vigencia,
		pb.nombre, cs.prefijo AS sucursal, CONCAT_WS(' ', ce.nombre,ce.apellidopaterno, ce.apellidomaterno) empleado
		FROM propuestaconvenio_bitacora pb
		INNER JOIN catalogosucursal cs ON pb.sucursal = cs.id
		INNER JOIN catalogoempleado ce ON pb.idusuario = ce.id
		".(($_SESSION[IDSUCURSAL]!=1)? " where cs.id = $_SESSION[IDSUCURSAL]" : "")."
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->cliente);
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
		
	}else if($_GET[accion]==32){//HISTORIAL DE GUIAS CANCELADAS
		$s = "SELECT * FROM guiasventanilla WHERE estado = 'CANCELADO'
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursalorigen = $_GET[sucursal]" : "")."";		
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT g.id AS guia, DATE_FORMAT(g.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',c.nombre,c.paterno,c.materno) AS remitente, o.prefijo AS origen, 
		d.prefijo AS destino, g.total
		FROM guiasventanilla g
		INNER JOIN catalogosucursal o ON g.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON g.idsucursaldestino = d.id
		INNER JOIN catalogocliente c ON g.idremitente = c.id
		WHERE g.estado = 'CANCELADO' AND g.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND g.idsucursalorigen = $_GET[sucursal]" : "")."
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->remitente = cambio_texto($f->remitente);
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
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
	
	}else if($_GET[accion]==33){//HISTORIAL DE GUIAS ENTREGADAS
		$s = "SELECT * FROM guiasventanilla WHERE estado = 'ENTREGADA'
		AND fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND idsucursalorigen = $_GET[sucursal]" : "")."";		
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT g.id AS guia, DATE_FORMAT(g.fecha,'%d/%m/%Y') AS fecha,
		CONCAT_WS(' ',c.nombre,c.paterno,c.materno) AS remitente,
		CONCAT_WS(' ',r.nombre,r.paterno,r.materno) AS destinatario,
		o.prefijo AS origen, 
		d.prefijo AS destino, g.total
		FROM guiasventanilla g
		INNER JOIN catalogosucursal o ON g.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON g.idsucursaldestino = d.id
		INNER JOIN catalogocliente c ON g.idremitente = c.id
		INNER JOIN catalogocliente r ON g.iddestinatario = r.id
		WHERE g.estado = 'ENTREGADA' AND g.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		".(($_SESSION[IDSUCURSAL]!=1)? " AND g.idsucursalorigen = $_GET[sucursal]" : "")."
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
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
	
	}else if($_GET[accion]==34){
		$s = "SELECT * FROM sobrantes 
		".(($_SESSION[IDSUCURSAL]!=1)?" WHERE sucursal = $_SESSION[IDSUCURSAL]":"")."
		GROUP BY guia";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		/*$s = "SELECT s.guia, t.fecha, t.remitente, t.destinatario, s.unidad, t.origen, t.destino 
		FROM sobrantes s
		INNER JOIN (SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario, so.prefijo AS origen, sd.prefijo AS destino
		FROM guiasventanilla gv
		LEFT JOIN catalogocliente re ON gv.idremitente = re.id
		LEFT JOIN catalogocliente de ON gv.iddestinatario = de.id
		LEFT JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		LEFT JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, 
		CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario, so.prefijo AS origen, sd.prefijo AS destino
		FROM guiasempresariales ge
		LEFT JOIN catalogocliente re ON ge.idremitente = re.id
		LEFT JOIN catalogocliente de ON ge.iddestinatario = de.id
		LEFT JOIN catalogosucursal so ON ge.idsucursalorigen = so.id
		LEFT JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id) AS t ON s.guia = t.guia
		".(($_SESSION[IDSUCURSAL]!=1)?" WHERE s.sucursal = $_SESSION[IDSUCURSAL]":"")."
		GROUP BY s.guia $limite";*/
		
		$s = "SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') fecha,CONCAT_WS(' ',re.nombre,re.paterno,re.materno) remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) destinatario, so.prefijo AS origen, sd.prefijo AS destino,s.unidad
		FROM sobrantes s
		INNER JOIN guiasventanilla gv ON s.guia = gv.id
		INNER JOIN catalogocliente re ON gv.idremitente = re.id
		INNER JOIN catalogocliente de ON gv.iddestinatario = de.id
		INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
		INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
		".(($_SESSION[IDSUCURSAL]!=1)?" WHERE s.sucursal = $_SESSION[IDSUCURSAL]":"")."
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') fecha,CONCAT_WS(' ',re.nombre,re.paterno,re.materno) remitente,
		CONCAT_WS(' ',de.nombre,de.paterno,de.materno) AS destinatario, so.prefijo AS origen, sd.prefijo AS destino,s.unidad
		FROM sobrantes s
		INNER JOIN guiasempresariales ge ON s.guia = ge.id
		INNER JOIN catalogocliente re ON ge.idremitente = re.id
		INNER JOIN catalogocliente de ON ge.iddestinatario = de.id
		INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id
		INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
		".(($_SESSION[IDSUCURSAL]!=1)?" WHERE s.sucursal = $_SESSION[IDSUCURSAL]":"")." $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->guia = cambio_texto($f->guia);
			$f->remitente = cambio_texto($f->remitente);
			$f->destinatario = cambio_texto($f->destinatario);
			$f->origen = cambio_texto($f->origen);
			$f->destino = cambio_texto($f->destino);
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
	
	}else if($_GET[accion]==35){
		$s = "SELECT * FROM sobrantes WHERE guia = '".$_GET[guia]."'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT paquete FROM sobrantes
		WHERE guia = '".$_GET[guia]."'
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->paquete = cambio_texto($f->paquete);
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
		
	}else if($_GET[accion]==36){//RELACION COBRANZA PENDIENTE DE LIQUIDAR
		$s = "SELECT CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente, rd.factura, rd.saldo FROM relacioncobranza rc
		INNER JOIN relacioncobranzadetalle rd ON rc.folio = rd.relacioncobranza AND rc.sucursal = rd.sucursal
		INNER JOIN catalogocliente cc ON rd.cliente = cc.id
		WHERE ".(($_SESSION[IDSUCURSAL]!=1)?" rc.sucursal=".$_SESSION[IDSUCURSAL]." AND" :"")." rc.fecharelacion = CURDATE() AND
		NOT EXISTS (SELECT * FROM liquidacioncobranza lq WHERE rc.folio=lq.foliocobranza AND rc.sucursal = lq.sucursal AND lq.estado='LIQUIDADO')
		GROUP BY rd.factura";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
		
		$s = "SELECT CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente, rd.factura, rd.saldo FROM relacioncobranza rc
		INNER JOIN relacioncobranzadetalle rd ON rc.folio = rd.relacioncobranza AND rc.sucursal = rd.sucursal
		INNER JOIN catalogocliente cc ON rd.cliente = cc.id
		WHERE ".(($_SESSION[IDSUCURSAL]!=1)?" rc.sucursal=".$_SESSION[IDSUCURSAL]." AND" :"")." rc.fecharelacion = CURDATE() AND
		NOT EXISTS (SELECT * FROM liquidacioncobranza lq WHERE rc.folio=lq.foliocobranza AND rc.sucursal = lq.sucursal AND lq.estado='LIQUIDADO')
		GROUP BY rd.factura $limite";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->cliente = cambio_texto($f->cliente);
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