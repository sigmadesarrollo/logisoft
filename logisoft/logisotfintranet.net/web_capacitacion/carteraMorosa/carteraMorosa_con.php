<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET[accion]=="0"){
		$s = "DELETE FROM ".$_GET[tabla]." WHERE idusuario=".$_SESSION[IDUSUARIO];
		$r = mysql_query($s,$l) or die($s);
		
		echo "ok";
		
	}else if($_GET[accion]==1){
		$s = "SELECT DATE_FORMAT(CURDATE(),'%d/%m/%Y') AS fecha,
		(SELECT descripcion FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL].") AS sucursal";
		$r = mysql_query($s,$l) or die($s); 
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		
		echo $f->sucursal.",".$f->fecha;
		
	}else if($_GET[accion]==2){
		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS nombre FROM catalogocliente WHERE id=".$_GET[cliente];
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		
		if(mysql_num_rows($r)>0){	
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "0";
		}
		
	}else if($_GET[accion]==3){//CARTERA MOROSA
		$s = "DELETE FROM carteramorosadetalle_tmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "INSERT INTO carteramorosadetalle_tmp
		SELECT 0 AS id, 1 AS sel, pg.cliente,CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre, pg.guia AS referencia,
		pg.fechacreo AS fechareferencia, g.factura, pg.total AS importe, 'NO' AS asignado,
		'' AS fechaasignada, '' AS causa, '' AS compromiso, '' AS empleado, ".$_SESSION[IDUSUARIO]." AS idusuario,
		CURRENT_TIMESTAMP, '' AS guardado, 0 AS cartera FROM pagoguias pg
		INNER JOIN catalogocliente cc ON pg.cliente = cc.id
		INNER JOIN (SELECT id AS guia, factura FROM guiasventanilla
		UNION
		SELECT id AS guia, factura FROM guiasempresariales) g ON g.guia = pg.guia
		WHERE pg.credito='SI' AND pg.pagado = 'N' AND DATEDIFF(CURDATE(),pg.fechacreo) > cc.diascredito
		AND NOT EXISTS(SELECT referencia FROM carteramorosadetalle WHERE pg.guia = referencia)
		".(($_GET[cliente]!="0")? " AND pg.cliente = $_GET[cliente]":"")."
		UNION
		SELECT 0 AS id, sel, cliente, nombre, referencia, fechareferencia, factura, importe, asignado,
		fechaasignada, causa, compromiso, empleado, idusuario, fecha, 'SI' AS guardado, id as cartera FROM carteramorosadetalle
		WHERE asignado='SI' AND fechareferencia <= '".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "SELECT tmp.id, tmp.sel, tmp.cliente, tmp.nombre, tmp.referencia,
		DATE_FORMAT(tmp.fechareferencia,'%d/%m/%Y') AS fechareferencia,
		tmp.factura, tmp.importe, tmp.asignado, DATE_FORMAT(tmp.fechaasignada,'%d/%m/%Y') AS fechaasignada, tmp.causa, 
		IF(tmp.compromiso='0000-00-00','',DATE_FORMAT(tmp.compromiso,'%d/%m/%Y')) as compromiso,
		IF(tmp.empleado<>0,CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno),'') AS empleado,
		IF(tmp.empleado=0,'',tmp.empleado) AS idempleado,tmp.idusuario, tmp.guardado, tmp.cartera, tmp.cartera AS cartera2
		FROM carteramorosadetalle_tmp tmp
		inner join catalogoempleado ce on tmp.empleado = ce.id
		WHERE tmp.idusuario = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion]==4){//CARTERA MOROSA
		$row = split(":",$_GET[folios]);
		if($_GET[noseleccion]=="si"){
			for($i=0; $i<count($row); $i++){
				$dat = split(",",$row[$i]);
				if($dat[5] != $dat[6]){				
					$s = "UPDATE carteramorosadetalle_tmp SET
					asignado = 'NO', fechaasignada = '', causa = '', empleado = '' 
					WHERE cartera = ".$dat[6]." AND idusuario = ".$_SESSION[IDUSUARIO]."";
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					
					$s = "SELECT * FROM carteramorosadetalle WHERE id = ".$dat[6];
					$rr = mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__); $fr = mysql_fetch_object($rr);
					
					$s = "DELETE FROM actividadusuario 
					WHERE referencia = '".$fr->referencia."' AND tipo = 'cartera'";
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					
					$s = "DELETE FROM carteramorosadetalle WHERE id = ".$dat[6];
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					
				}else if($dat[5]=="0"){
					$s = "INSERT INTO carteramorosadetalle
					SELECT 0 AS id,1 AS sel,cliente,nombre,referencia,fechareferencia,
					factura,importe, '".$dat[1]."' AS asignado, CURDATE(), '".$dat[2]."' AS causa, 
					'' AS compromiso, ".$dat[3]." AS empleado, 
					".$_SESSION[IDUSUARIO]." AS idusuario, CURRENT_TIMESTAMP AS fecha
					FROM carteramorosadetalle_tmp WHERE id=".$dat[0];
					mysql_query($s,$l) or die($s);
					
					$s = "INSERT INTO actividadusuario
					SELECT 0 AS id,1 AS sel,cliente,referencia,fechareferencia, '' AS entrega,
					factura,importe, '".$dat[1]."' AS asignado, CURDATE(), '".$dat[2]."' AS causa, 
					'' AS compromiso, ".$dat[3]." AS empleado,'' AS recoleccion,0 AS danofaltante,
					'cartera' AS tipo,idusuario,fecha, 0 AS estado, ".$_SESSION[IDSUCURSAL]." AS idsucursal,
					'' AS razonqueja, '' AS personaqueja
					FROM carteramorosadetalle_tmp WHERE id = ".$dat[0];
					mysql_query($s,$l) or die($s);
					
				}else{
					$s = "UPDATE carteramorosadetalle SET 
					asignado = '".$dat[1]."', causa = '".$dat[2]."',
					compromiso = '', empleado = ".$dat[3].", idusuario = ".$_SESSION[IDUSUARIO].",
					fecha = CURRENT_TIMESTAMP WHERE id = ".$dat[5];
					mysql_query($s,$l) or die($s);
					
					$s = "SELECT * FROM carteramorosadetalle WHERE id = ".$dat[5];
					$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
					
					$s = "DELETE FROM actividadusuario 
					WHERE referencia = '".$f->referencia."' AND tipo = 'cartera'";
					mysql_query($s,$l) or die($s);
					
					$s = "INSERT INTO actividadusuario
					SELECT 0 AS id,1 AS sel,cliente,referencia,fechareferencia,'' AS entrega,
					factura,importe,asignado,fechaasignada,causa,'' AS fechacompromiso,
					empleado,'' AS recoleccion, 0 AS danofaltante,'cartera' AS tipo,idusuario,fecha, 
					0 AS estado, ".$_SESSION[IDSUCURSAL]." AS idsucursal,
					'' AS razonqueja, '' AS personaqueja
					FROM carteramorosadetalle WHERE id = ".$dat[5];
					mysql_query($s,$l) or die($s);
				}
			}
			
			echo "ok,guardado";
		}else{
			$s = "UPDATE carteramorosadetalle_tmp SET 
			asignado = 'SI', fechaasignada = CURDATE(), causa = '".$_GET[causa]."',
			empleado = ".$_GET[empleado]." WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
			
			$s = "SELECT * FROM carteramorosadetalle_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){					
				if($f->cartera!="0"){
					$s = "UPDATE carteramorosadetalle SET 
					asignado = 'SI', causa = '".$f->causa."',
					compromiso = '', empleado = ".$f->empleado.", idusuario = ".$_SESSION[IDUSUARIO].",
					fecha = CURRENT_TIMESTAMP WHERE id = ".$f->cartera."";
					mysql_query($s,$l) or die($s);
					
					$s = "SELECT * FROM carteramorosadetalle WHERE id = ".$f->cartera;
					$ro = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
					
					$s = "DELETE FROM actividadusuario 
					WHERE referencia = '".$fo->referencia."' AND tipo = 'cartera'";
					mysql_query($s,$l) or die($s);
					
					$s = "INSERT INTO actividadusuario
					SELECT 0 AS id,1 AS sel,cliente,referencia,fechareferencia,'' AS entrega,
					factura,importe,asignado,fechaasignada,causa,'' AS fechacompromiso,
					empleado,'' AS recoleccion, 0 AS danofaltante,'cartera' AS tipo,idusuario,fecha, 
					0 AS estado, ".$_SESSION[IDSUCURSAL]." AS idsucursal,
					'' AS razonqueja, '' AS personaqueja
					FROM carteramorosadetalle WHERE id = ".$f->cartera;
					mysql_query($s,$l) or die($s);
					
				}else{
					$s = "INSERT INTO carteramorosadetalle
					SELECT 0 AS id, 1 AS sel,cliente,nombre,referencia,fechareferencia,
					factura,importe, asignado, fechaasignada, causa, 
					compromiso, empleado, idusuario, fecha
					FROM carteramorosadetalle_tmp WHERE id = ".$f->id;
					mysql_query($s,$l) or die($s);
					
					$s = "INSERT INTO actividadusuario
					SELECT 0 AS id,1 AS sel,cliente,referencia,fechareferencia,'' AS entrega,
					factura,importe,asignado,fechaasignada,causa,'' AS fechacompromiso,
					empleado,'' AS recoleccion,0 AS danofaltante,'cartera' AS tipo,idusuario,fecha, 
					0 AS estado, ".$_SESSION[IDSUCURSAL]." AS idsucursal,
					'' AS razonqueja, '' AS personaqueja
					FROM carteramorosadetalle_tmp WHERE id = ".$f->id;
					mysql_query($s,$l) or die($s);
				}
			}
			
			echo "ok,guardado";
		}
		
	}else if($_GET[accion]==5){//INVENTARIO MOROSO
		$s = mysql_query("DELETE FROM inventariomorosodetalle_tmp 
		WHERE idusuario=".$_SESSION[IDUSUARIO]."",$l);
		
		$s = "INSERT INTO inventariomorosodetalle_tmp		
		SELECT NULL AS id, 1 AS sel, cliente, nombre, referencia, 
		fechareferencia, entrega,importe,'NO' AS asignado, '' AS fechaasignada,
		'' AS causa, compromiso, '' AS empleado, ".$_SESSION[IDUSUARIO]." AS idusuario,
		CURRENT_TIMESTAMP AS fecha, 'NO' AS guardado, inventario FROM
		(SELECT cc.id AS cliente,CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		gv.id AS referencia, rp.fecha AS fechareferencia, gv.fechaentrega AS entrega,gv.total AS importe,
		'' AS compromiso, 0 as inventario FROM guiasventanilla gv
		INNER JOIN catalogocliente cc ON cc.id = gv.iddestinatario
		INNER JOIN recepcionmercanciadetalle rd ON gv.id = rd.guia
		INNER JOIN recepcionmercancia rp ON rd.recepcion = rp.folio AND rd.sucursal = rp.idsucursal
		WHERE gv.estado = 'ALMACEN DESTINO' ".(($_GET[ead]==0)? " AND gv.ocurre = 0 " : "")."
		AND rp.fecha <= '".cambiaf_a_mysql($_GET[fecha])."'
		AND NOT EXISTS(SELECT referencia FROM inventariomorosodetalle WHERE gv.id = referencia)
		".(($_GET[cliente]!="0") ? " AND gv.iddestinatario = ".$_GET[cliente] : "")."
		UNION
		SELECT ge.iddestinatario AS cliente,CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		ge.id AS referencia, rp.fecha AS fechareferencia, ge.fechaentrega AS entrega,
		ge.total AS importe, '' AS compromiso, 0 as inventario FROM guiasempresariales ge
		INNER JOIN catalogocliente cc ON cc.id = ge.iddestinatario
		INNER JOIN recepcionmercanciadetalle rd ON ge.id = rd.guia
		INNER JOIN recepcionmercancia rp ON rd.recepcion = rp.folio AND rd.sucursal = rp.idsucursal
		WHERE ge.estado = 'ALMACEN DESTINO' ".(($_GET[ead]==0)? " AND ge.ocurre = 0 " : "")."
		AND rp.fecha <= '".cambiaf_a_mysql($_GET[fecha])."'
		AND NOT EXISTS(SELECT referencia FROM inventariomorosodetalle WHERE ge.id = referencia)
		".(($_GET[cliente]!="0") ? " AND ge.iddestinatario = ".$_GET[cliente] : "").") tb
		UNION
		SELECT NULL, sel,cliente,nombre,referencia,fechareferencia,entrega,importe,asignado,
		fechaasignada,causa,compromiso,empleado,idusuario,fecha,'SI' AS guardado, id as inventario 
		FROM inventariomorosodetalle WHERE sel = 1 AND fechareferencia <= '".cambiaf_a_mysql($_GET[fecha])."'";		
		//die($s);
		$r = mysql_query($s,$l) or die($s);
		
		$s = "SELECT tmp.id, tmp.sel, tmp.cliente, tmp.nombre, tmp.referencia, 
		DATE_FORMAT(tmp.fechareferencia,'%d/%m/%Y') AS fechareferencia,
		DATE_FORMAT(tmp.entrega,'%d/%m/%Y') AS entrega, tmp.importe, tmp.asignado, 
		DATE_FORMAT(tmp.fechaasignada,'%d/%m/%Y') AS fechaasignada,
		tmp.causa, if(tmp.compromiso='0000-00-00','',date_format(tmp.compromiso,'%d/%m/%Y')) AS compromiso,
		if(tmp.empleado=0,'', CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno)) AS empleado,
		IF(tmp.empleado=0,'',tmp.empleado) AS idempleado, tmp.guardado, tmp.inventario, 
		tmp.inventario AS inventario2 FROM inventariomorosodetalle_tmp AS tmp 
		inner join catalogoempleado ce on tmp.empleado = ce.id
		WHERE tmp.idusuario=".$_SESSION[IDUSUARIO];	
				
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		
		if(mysql_num_rows($r)>0){	
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion]==6){//INVENTARIO MOROSO
		$row = split(";",$_GET[folios]);
		if($_GET[noseleccion]=="si"){
			for($i=0; $i<count($row); $i++){
				$dat = split(",",$row[$i]);
				if($dat[5] != $dat[6]){				
					$s = "UPDATE inventariomorosodetalle_tmp SET
					asignado = 'NO', fechaasignada = '', causa = '', empleado = '' 
					WHERE inventario = ".$dat[6]." AND idusuario = ".$_SESSION[IDUSUARIO]."";
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					
					$s = "SELECT * FROM inventariomorosodetalle WHERE id = ".$dat[6];
					$rr = mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__); $fr = mysql_fetch_object($rr);
					
					$s = "DELETE FROM actividadusuario 
					WHERE referencia = '".$fr->referencia."' AND tipo = 'inventario'";
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					
					$s = "DELETE FROM inventariomorosodetalle WHERE id = ".$dat[6];
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					
				}else if($dat[5]=="0"){
					$s = "INSERT INTO inventariomorosodetalle
					SELECT 0 AS id,1 AS sel,cliente,nombre,referencia,fechareferencia, entrega,
					importe, '".$dat[1]."' AS asignado, CURDATE(), '".$dat[2]."' AS causa, 
					'' AS compromiso, ".$dat[3]." AS empleado, 
					".$_SESSION[IDUSUARIO]." AS idusuario, CURRENT_TIMESTAMP AS fecha
					FROM inventariomorosodetalle_tmp WHERE id=".$dat[0];
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					//echo $s."<br>";
					
					$s = "INSERT INTO actividadusuario
					SELECT 0 AS id,1 AS sel,cliente,referencia,fechareferencia, entrega,
					'' AS factura,importe, '".$dat[1]."' AS asignado, CURDATE(), '".$dat[2]."' AS causa, 
					'' AS compromiso, ".$dat[3]." AS empleado,'' AS recoleccion,0 AS danofaltante,
					'inventario' AS tipo,idusuario,fecha, 0 AS estado, ".$_SESSION[IDSUCURSAL]." AS idsucursal,
					'' AS razonqueja, '' AS personaqueja
					FROM inventariomorosodetalle_tmp WHERE id = ".$dat[0];
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					//echo $s."<br>";
				}else{
					$s = "UPDATE inventariomorosodetalle SET 
					asignado = '".$dat[1]."', causa = '".$dat[2]."',
					compromiso = '', empleado = ".$dat[3].", idusuario = ".$_SESSION[IDUSUARIO].",
					fecha = CURRENT_TIMESTAMP WHERE id = ".$dat[5];
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					//echo $s."<br>";
					$s = "SELECT * FROM inventariomorosodetalle WHERE id = ".$dat[5];
					$r = mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__); $f = mysql_fetch_object($r);
					
					$s = "DELETE FROM actividadusuario 
					WHERE referencia = '".$f->referencia."' AND tipo = 'inventario'";
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					
					$s = "INSERT INTO actividadusuario
					SELECT 0 AS id,1 AS sel,cliente,referencia,fechareferencia, entrega,
					'' AS factura,importe,asignado,fechaasignada,causa,'' AS fechacompromiso,
					empleado,'' AS recoleccion, 0 AS danofaltante,'inventario' AS tipo,idusuario,fecha, 
					0 AS estado, ".$_SESSION[IDSUCURSAL]." AS idsucursal,'' AS razonqueja, '' AS personaqueja
					FROM inventariomorosodetalle WHERE id = ".$dat[5];
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
				}
			}
			
			echo "ok,guardado";
		}else{
			$s = "UPDATE inventariomorosodetalle_tmp SET 
			asignado = 'SI', fechaasignada = CURDATE(), causa = '".$_GET[causa]."',
			empleado = ".$_GET[empleado]." WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			//echo $s."<br>"; 
			mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
			
			$s = "SELECT * FROM inventariomorosodetalle_tmp WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
			$r = mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
			while($f = mysql_fetch_object($r)){
				if($f->inventario!="0"){
					$s = "UPDATE inventariomorosodetalle SET 
					asignado = 'SI', causa = '".$f->causa."',
					compromiso = '', empleado = ".$f->empleado.", idusuario = ".$_SESSION[IDUSUARIO].",
					fecha = CURRENT_TIMESTAMP WHERE id = ".$f->inventario."";
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					//echo $s."<br>"; 

					$s = "SELECT * FROM inventariomorosodetalle WHERE id = ".$f->inventario;
					$ro = mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__); $fo = mysql_fetch_object($r);
					
					$s = "DELETE FROM actividadusuario 
					WHERE referencia = '".$fo->referencia."' AND tipo = 'inventario'";
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					//echo $s."<br>"; 
					
					$s = "INSERT INTO actividadusuario
					SELECT 0 AS id,1 AS sel,cliente,referencia,fechareferencia, entrega,
					'' AS factura,importe,asignado,fechaasignada,causa, '' AS fechacompromiso,
					empleado, '' AS recoleccion, 0 AS danofaltante,'inventario' AS tipo,idusuario,fecha, 
					0 AS estado, ".$_SESSION[IDSUCURSAL]." AS idsucursal,
					'' AS razonqueja, '' AS personaqueja
					FROM inventariomorosodetalle WHERE id = ".$f->inventario;
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					//echo $s."<br>"; 
					
				}else{
					$s = "INSERT INTO inventariomorosodetalle
					SELECT 0 AS id, 1 AS sel,cliente,nombre,referencia,fechareferencia,entrega,
					importe, asignado, fechaasignada, causa, 
					compromiso, empleado, idusuario, fecha
					FROM inventariomorosodetalle_tmp WHERE id = ".$f->id;
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					//echo $s."<br>"; 
					$s = "INSERT INTO actividadusuario
					SELECT 0 AS id,1 AS sel,cliente,referencia,fechareferencia,entrega,
					'' AS factura,importe,asignado,fechaasignada,causa,'' AS fechacompromiso,
					empleado,'' AS recoleccion,0 AS danofaltante,'inventario' AS tipo,idusuario,fecha, 
					0 AS estado, ".$_SESSION[IDSUCURSAL]." AS idsucursal,
					'' AS razonqueja, '' AS personaqueja
					FROM inventariomorosodetalle_tmp WHERE id = ".$f->id;
					mysql_query($s,$l) or die($s."ERROR EN LA LINEA".__LINE__);
					//echo $s."<br>"; 
				}
			}
			
			echo "ok,guardado";
		}
		
		
	}else if($_GET[accion]==7){//ACTIVIDADES USUARIO
		$s = "SELECT tmp.id, sel, cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		referencia, IF(fechareferencia = '0000-00-00','',DATE_FORMAT(fechareferencia,'%d/%m/%Y')) AS fechareferencia,
		IF(factura=0,'',factura) AS factura,
		importe, asignado, DATE_FORMAT(fechaasignada,'%d/%m/%Y') AS fechaasignado, 
		IF(fechacompromiso='0000-00-00','', DATE_FORMAT(fechacompromiso,'%d/%m/%Y')) AS compromiso, recoleccion, 
		IF(danofaltante=0,'',danofaltante) AS danofaltante, tmp.tipo,
		tmp.id AS actividad2, tmp.id AS actividad,
		IF(tmp.causa is null,IFNULL(tmp.razonqueja,''),tmp.causa) AS razonqueja, IFNULL(tmp.personaqueja,'') AS personaqueja
		/*IF(fecharevision='0000-00-00','',DATE_FORMAT(fecharevision,'%d/%m/%Y')) AS fecharevision, guardado*/
		FROM actividadusuario tmp
		INNER JOIN catalogocliente cc ON tmp.cliente = cc.id
		WHERE empleado = ".$_SESSION[IDUSUARIO]." AND estado = 0
		/*".(($_GET[fecha]!="")? " AND fecharevision < '".cambiaf_a_mysql($_GET[fecha])."'" : "")."*/ ";
		$r = mysql_query($s,$l) or die($s);
		/*
		if($_GET[tipo]=="0"){
			$r = mysql_query($s,$l) or die($s);
			echo mysql_num_rows($r);
		}else if($_GET[tipo]=="1"){
			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);*/
		if(mysql_num_rows($r)>0){	
			$registros = array();
			while($f = mysql_fetch_object($r)){
				$f->cliente = cambio_texto($f->cliente);
				$f->razonqueja = cambio_texto($f->razonqueja);
				$f->personaqueja = cambio_texto($f->personaqueja);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion]==9){//ACTIVIDADES USUARIO ULTIMO
		$s = "SELECT COUNT(*) AS total FROM actividadesusuariodetalle_tmp		
		WHERE idusuario = ".$_GET[empleado]."";
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;
	
		$s = "SELECT id, sel, cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombre,
		referencia, DATE_FORMAT(fechareferencia,'%d/%m/%Y') AS fechareferencia, factura, importe, 
		asignado, DATE_FORMAT(fechaasignado,'%d/%m/%Y') AS fechaasignado, compromiso, 
		DATE_FORMAT(fecharevision,'%d/%m/%Y') AS fecharevision, guardado
		FROM actividadesusuariodetalle_tmp tmp
		INNER JOIN catalogocliente cc ON tmp.cliente = cc.id
		WHERE idusuario=".$_GET[empleado]."
		LIMIT ".$limit."";
		$r = mysql_query($s,$l) or die($s);
		
		$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$s = "SELECT folioatencion, foliofaltante FROM solicitudtelefonica 
					WHERE (responsable = ".$_GET[empleado]." OR supervisor=".$_GET[empleado].")";				
					$r = mysql_query($s,$l) or die($s);
					$t = mysql_fetch_object($r);
					
					$f->recoleccion = cambio_texto($t->folioatencion);
					$f->danofaltante = cambio_texto($t->foliofaltante);
					
					$f->cliente = cambio_texto($f->cliente);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "0";
			}
	}else if($_GET[accion]==8){//ACTIVIDADES USUARIO
		$row = split(":",$_GET[folios]);		
			for($i=0; $i<count($row); $i++){
				$dat = split(",",$row[$i]);
				if($dat[4] != $dat[5]){
					$s = "UPDATE actividadusuario SET fechacompromiso = '' WHERE id = ".$dat[5]."";
					mysql_query($s,$l) or die($s);
					
					if($dat[3]=="cartera"){
						$s = "UPDATE carteramorosadetalle SET compromiso = '' WHERE referencia = '".$dat[2]."'";
						mysql_query($s,$l) or die($s);
						
					}else if($dat[3]=="inventario"){
						$s = "UPDATE inventariomorosodetalle SET compromiso = '' WHERE referencia = '".$dat[2]."'";
						mysql_query($s,$l) or die($s);
					}					
				}else{
					$s = "UPDATE actividadusuario SET fechacompromiso = '".cambiaf_a_mysql($dat[1])."'
					WHERE id = ".$dat[0]."";
					mysql_query($s,$l) or die($s);
					
					if($dat[3]=="cartera"){
						$s = "UPDATE carteramorosadetalle SET compromiso = '".cambiaf_a_mysql($dat[1])."'
						WHERE referencia = '".$dat[2]."'";
						mysql_query($s,$l) or die($s);
						
					}else if($dat[3]=="inventario"){
						$s = "UPDATE inventariomorosodetalle SET compromiso = '".cambiaf_a_mysql($dat[1])."'
						WHERE referencia = '".$dat[2]."'";
						mysql_query($s,$l) or die($s);
					}
				}
			}		
			echo "ok,guardado";
		
	}else if($_GET[accion]==10){//ASIGNAR COMPROMISO AL EMPLEADO
		if($_GET[tipo] == "modi"){
			$row = split(",",$_GET[arr]);
			$s = "UPDATE carteramorosadetalle_tmp SET asignado='SI', causa='".$row[0]."', empleado=".$row[1].",
			fechaasignada=CURDATE() WHERE id=".$row[2]."";
			mysql_query($s,$l) or die($s);
			
			echo "ok";
		}else{
			$s = "UPDATE carteramorosadetalle_tmp SET asignado='NO', causa='', empleado='', fechaasignada='0000-00-00'
			WHERE id=".$_GET[id]."";
			mysql_query($s,$l) or die($s);
			
			echo "ok";
		}
		
	}else if($_GET[accion]==11){
		$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado FROM catalogoempleado 
		WHERE id = ".$_GET[empleado]."";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->empleado = cambio_texto($f->empleado);
				$arr[] = $f;
			}
			
			echo str_replace('null','""',json_encode($arr));
		}else{
			echo "no encontro";
		}
	}
	
?>