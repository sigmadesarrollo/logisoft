<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "select repartomercanciadetalle.idreparto, repartomercanciaead.conductor1,
		concat_ws(' ',emp1.nombre,emp1.apellidopaterno,emp1.apellidomaterno) as conductorn1,
		repartomercanciaead.conductor2,
		concat_ws(' ',emp2.nombre,emp2.apellidopaterno,emp2.apellidomaterno) as conductorn2,
		catalogounidad.numeroeconomico, repartomercanciaead.liquidado
		from repartomercanciadetalle
		inner join repartomercanciaead on repartomercanciaead.id=repartomercanciadetalle.idreparto
		left join catalogoempleado as emp1 on emp1.id = repartomercanciaead.conductor1
		left join catalogoempleado as emp2 on emp2.id = repartomercanciaead.conductor2
		left join catalogounidad on catalogounidad.id = repartomercanciaead.unidad
		where repartomercanciadetalle.idreparto = $_GET[idreparto] and repartomercanciadetalle.sucursal = $_SESSION[IDSUCURSAL]
		and (repartomercanciaead.liquidado = 0 or repartomercanciaead.liquidado = 1)
		group by repartomercanciadetalle.idreparto";
		$r = mysql_query($s,$l) or die($s);
	
		$f = mysql_fetch_object($r);
		//echo "<br>$s<br>";
		
		$f->conductorn1 = cambio_texto($f->conductorn1);
		$f->conductorn2 = cambio_texto($f->conductorn2);
		echo "(".str_replace("null",'""', json_encode($f)).")";
	}
	
if($_GET[accion]==2){
		$s = "delete from liquidacion_detalle_tmp where idusuario = $_SESSION[IDUSUARIO]";
		mysql_query($s,$l) or die($s);
		
		$s = "insert into liquidacion_detalle_tmp
		(select gd.sector, gd.id as guia, cs.descripcion as origen,
			concat_ws(' ',cc.nombre, cc.paterno, cc.materno) as destinatario,
			if(gd.tipoflete=0,'PAGADO','POR COBRAR') as tipoflete,
			if(gd.condicionpago=0,'CONTADO','CREDITO') as condicionpago,
			gd.total as importe,IF(gd.estado='ENTREGADA','ENTREGADA POR LIQUIDAR',gd.estado)as estado,
			if(gd.tipoflete=1 and gd.condicionpago=0,'1','0') as seleccion,
			rm.motivo, $_SESSION[IDUSUARIO],null,null,null,gd.total,0,0,0,0,0,".$_SESSION[IDSUCURSAL]."
			from repartomercanciadetalle as rm
			inner join guiasventanilla as gd on gd.id=rm.guia
			inner join catalogosucursal as cs on gd.idsucursalorigen = cs.id
			inner join catalogocliente as cc on gd.iddestinatario = cc.id
			where rm.idreparto=$_GET[folio] and rm.sucursal = $_SESSION[IDSUCURSAL])
		union
		(select gd.sector, gd.id as guia, cs.descripcion as origen,
			concat_ws(' ',cc.nombre, cc.paterno, cc.materno) as destinatario,
			gd.tipoflete,
			gd.tipopago as condicionpago,
			gd.total as importe,IF(gd.estado='ENTREGADA','ENTREGADA POR LIQUIDAR',gd.estado),
			if(gd.tipoflete='POR COBRAR' and gd.tipopago='CONTADO','1','0') as seleccion,
			rm.motivo, $_SESSION[IDUSUARIO],null,null,null,gd.total,0,0,0,0,0,".$_SESSION[IDSUCURSAL]."
			from repartomercanciadetalle as rm
			inner join guiasempresariales as gd on gd.id=rm.guia 
			inner join catalogosucursal as cs on gd.idsucursalorigen = cs.id
			inner join catalogocliente as cc on gd.iddestinatario = cc.id
			where rm.idreparto=$_GET[folio] and rm.sucursal = $_SESSION[IDSUCURSAL])";
			
		mysql_query($s,$l) or die($s);
		
		$sql="SELECT tm.sector,tm.guia,tm.origen,tm.destinatario,tm.tipoflete,
		tm.condicionpago,tm.importe,
		IF(tm.estado='ENTREGADA','ENTREGADA POR LIQUIDAR',tm.estado)as estado,tm.seleccion,tm.motivo,
		tm.idusuario,IFNULL(tm.nombre,'')AS nombre,IFNULL(tm.identificacion,'')AS identificacion,
		IFNULL(tm.numero_id,0)AS numero_id,IF(tm.guia=IFNULL(le.guia,''),1,0)AS pagada
 		FROM liquidacion_detalle_tmp tm 
 		LEFT JOIN 
 		(SELECT DISTINCT ld.guia FROM liquidacionead le 
 		INNER JOIN liquidacion_detalleead ld ON le.id=ld.idliquidacion 
		WHERE  le.idreparto=$_GET[folio])le ON tm.guia=le.guia
 		WHERE tm.idusuario = $_SESSION[IDUSUARIO] and tm.sucursal=".$_SESSION[IDSUCURSAL]."
		order by tm.guia";
		$d=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($d)>0){
			while ($f=mysql_fetch_object($d))
			{
				//$f->cliente=cambio_texto($f->cliente);
				$registros[]=$f;	
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}
	
	if($_GET[accion]==3){
	
		$cerrardevolucion=$_GET[cerrar];
		if ($cerrardevolucion==1){
			$concatenacion=',cerro=1';	
		}else{
			$concatenacion='';	
		}
		
		$s = "SELECT IFNULL(id,0)as folio FROM liquidacionead 
		WHERE idreparto=".$_GET[idreparto]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$folio = $f->folio;
		if ($folio!=0){
			$s = "update liquidacionead set idreparto='$_GET[idreparto]', 
			entregadas='$_GET[entregadas]', devueltas='$_GET[devueltas]', 
			pagadas_credito='$_GET[pagadas_credito]', pagadas_contado='$_GET[pagadas_contado]',
			tpagadas_credito='$_GET[tpagadas_credito]', tpagadas_contado='$_GET[tpagadas_contado]', 
			porcobrar_contado='$_GET[porcobrar_contado]',
			porcobrar_credito='$_GET[porcobrar_credito]', tporcobrar_credito='$_GET[tporcobrar_credito]', 
			tporcobrar_contado='$_GET[tporcobrar_contado]', sucursal='$_SESSION[IDSUCURSAL]',
			entrego='$_GET[entrego]',total=$_GET[total],fecha=current_date,idusuario = '$_SESSION[IDUSUARIO]', 
			cantidadentregada = '$_GET[cantidadentregada]', diferencia = '".(($_GET[diferencia]<0)?0:$_GET[diferencia])."'
			$concatenacion where id = $folio";
			//echo $s."<br>";
			mysql_query($s,$l) or die($s);
			$idliquidacion = $folio;			
			 			
		}else{
		
			$s="insert into liquidacionead set folio = obtenerFolio('liquidacionead',".$_SESSION[IDSUCURSAL]."),
			idreparto=$_GET[idreparto],entregadas=$_GET[entregadas],devueltas=$_GET[devueltas],
			pagadas_credito=$_GET[pagadas_credito],pagadas_contado=$_GET[pagadas_contado],
			tpagadas_credito=$_GET[tpagadas_credito],tpagadas_contado=$_GET[tpagadas_contado],
			porcobrar_contado=$_GET[porcobrar_contado],porcobrar_credito=$_GET[porcobrar_credito],
			tporcobrar_contado=$_GET[tporcobrar_contado],tporcobrar_credito=$_GET[tporcobrar_credito],
			sucursal=$_SESSION[IDSUCURSAL],entrego=$_GET[entrego],total=$_GET[total],
			fecha = CURRENT_DATE,idusuario=$_GET[idusuario],
			cantidadentregada = '$_GET[cantidadentregada]', 
			diferencia = '".(($_GET[diferencia]<0)?0:$_GET[diferencia])."' $concatenacion";
			//echo $s."<br>";
			mysql_query($s,$l) or die($s);
			$idliquidacion = mysql_insert_id($l);						
		}
	
		$s = "SELECT folio FROM liquidacionead WHERE id = ".$idliquidacion;
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
	
		$sql = "delete from liquidacion_detalleead 
		where idliquidacion = $fo->folio and sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($sql,$l)or die($sql);
	
		$sql="INSERT INTO liquidacion_detalleead
		SELECT 0 as id,'$fo->folio',sector,guia,origen,destinatario,tipoflete,
		condicionpago,importe,estado,seleccion,motivo,
		idusuario,nombre,identificacion,numero_id,
		efectivo,cheque,ncheque,banco,nnotacredito,notacredito,sucursal
		FROM liquidacion_detalle_tmp 
		WHERE idusuario = '$_SESSION[IDUSUARIO]' AND estado='ENTREGADA POR LIQUIDAR'";
		
		if($concatenacion==",cerro=1"){
			$s = "SELECT * FROM liquidacion_detalleead 
			WHERE idliquidacion = ".$fo->folio." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$e = mysql_query($s,$l) or die($s);
			while($ead = mysql_fetch_object($e)){
				$s = "CALL proc_ReporteProductividad('EAD','','".$ead->guia."','".$_GET[fechahora]."',".$_SESSION[IDSUCURSAL].")";
				mysql_query($s,$l) or die($s);
			} 
		}
		
		//echo $sql."<br>";
		mysql_query($sql,$l) or die($sql);
		
		if ($cerrardevolucion==1){
			/*AQUI VA GUARDAR LA FORMA DE PAGO*/
			$sql = "INSERT INTO formapago(guia,procedencia,tipo,total,efectivo,
			tarjeta,transferencia,cheque,ncheque,banco,notacredito,nnotacredito,sucursal,usuario,fecha,cliente)
			SELECT ld.guia,'M','X',ld.importe,ld.efectivo,0 AS tarjeta,0 AS transferencia,ld.cheque,ld.ncheque,
			ld.banco,ld.notacredito,ld.nnotacredito,lc.sucursal,lc.idusuario,curdate(),pg.cliente FROM liquidacionead lc
			INNER JOIN liquidacion_detalleead ld ON lc.id=ld.idliquidacion 
			INNER JOIN pagoguias pg ON ld.guia=pg.guia
			WHERE lc.id='$fo->folio' AND ld.estado='ENTREGADA POR LIQUIDAR' AND pg.pagado='N' and seleccion=1";
			//echo $sql."<br>";
			$t = mysql_query($sql,$l) or die($sql);	
			
			$s = "UPDATE guiasventanilla 
			INNER JOIN liquidacion_detalle_tmp ON guiasventanilla.id = liquidacion_detalle_tmp.guia
			AND liquidacion_detalle_tmp.idusuario = '$_SESSION[IDUSUARIO]' AND 
			liquidacion_detalle_tmp.estado='ENTREGADA POR LIQUIDAR'
			SET guiasventanilla.estado = 'ENTREGADA', 
			guiasventanilla.recibio=liquidacion_detalle_tmp.nombre,
			guiasventanilla.tipoidentificacion=liquidacion_detalle_tmp.identificacion,
			guiasventanilla.numeroidentificacion=liquidacion_detalle_tmp.numero_id;";
			//echo $s."<br>";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE guiasempresariales 
			INNER JOIN liquidacion_detalle_tmp ON guiasempresariales.id = liquidacion_detalle_tmp.guia
			AND liquidacion_detalle_tmp.idusuario = '$_SESSION[IDUSUARIO]' AND 
			liquidacion_detalle_tmp.estado='ENTREGADA POR LIQUIDAR'
			SET guiasempresariales.estado = 'ENTREGADA', 
			guiasempresariales.recibio=liquidacion_detalle_tmp.nombre,
			guiasempresariales.tipoidentificacion=liquidacion_detalle_tmp.identificacion,
			guiasempresariales.numeroidentificacion=liquidacion_detalle_tmp.numero_id;";
			//echo $s."<br>";
			mysql_query($s,$l) or die($s);
			
			$s = "update liquidacion_detalleead set estado = 'ENTREGADA' 
			where idliquidacion = $fo->folio and sucursal = ".$_SESSION[IDSUCURSAL]." 
			and estado = 'ENTREGADA POR LIQUIDAR'";
			//echo $s;
			mysql_query($s,$l) or die($s);
			
			$q="select * from liquidacion_detalleead where idliquidacion='$fo->folio' 
			and sucursal = ".$_SESSION[IDSUCURSAL]." and seleccion=1";
			$m= mysql_query(str_replace("''",'NULL',$q),$l) or die($q);
			$registros= array();
			if (mysql_num_rows($m)>0){
				while ($f=mysql_fetch_object($m)){
					$sq = "UPDATE pagoguias SET pagado='S',fechapago=CURRENT_DATE,usuariocobro='".$_SESSION[IDUSUARIO]."',
					sucursalcobro='$_SESSION[IDSUCURSAL]' WHERE guia='$f->guia' AND pagado='N' ";					
					$d = mysql_query(str_replace("''",'NULL',$sq),$l) or die($sq);
					
					$s = "UPDATE actividadusuario SET estado = 1 
					WHERE tipo = 'inventario' AND referencia = UCASE('$f->guia')";
					mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
				}
			}
		}
		
		echo "ok,".$fo->folio;
	}
	
	if($_GET[accion]==4){
		$s= "select id AS idsucursal, descripcion,DATE_FORMAT(CURDATE(),'%d/%m/%Y')AS fecha from
		catalogosucursal where id = ".$_SESSION[IDSUCURSAL]."";	
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->descripcion = cambio_texto($f->descripcion);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
		}else{
				echo str_replace('null','""',json_encode(0));
		}
	
	}
	
	if($_GET[accion]==5){		
		$s = "SELECT ld.idreparto,ld.entregadas,ld.devueltas,ld.pagadas_credito,ld.pagadas_contado,
		ld.tpagadas_credito,ld.tpagadas_contado,ld.porcobrar_contado,ld.porcobrar_credito,
		ld.tporcobrar_contado,ld.tporcobrar_credito,ld.sucursal,ld.entrego,ld.total,
		date_format(ld.fecha,'%d/%m/%Y')AS fecha,ld.cerro,ld.cantidadentregada,ld.diferencia,
		CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado 
		FROM liquidacionead ld
		INNER JOIN catalogoempleado ce ON ld.entrego = ce.id
		WHERE ld.folio = ".$_GET[folio]." AND ld.sucursal = ".$_SESSION[IDSUCURSAL]."";	
		$r=mysql_query($s,$l)or die($s); 
			
		$registros = array();
		$principal = "";
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->empleado = cambio_texto($f->empleado);
			$principal = str_replace('null','""',json_encode($f));
			
			$sql = "SELECT IFNULL(cs.descripcion,'') AS sector,ld.guia,ld.origen,
			ld.destinatario,ld.tipoflete,ld.condicionpago,ld.importe,ld.estado,ld.seleccion,
			IFNULL(ld.motivo,'') AS motivo,ld.nombre,ld.identificacion,ld.numero_id 
			FROM liquidacion_detalleead ld 
			LEFT JOIN catalogosector cs ON ld.sector=cs.id	
			WHERE ld.idliquidacion=".$_GET[folio]." AND ld.sucursal = ".$_SESSION[IDSUCURSAL]."
			group by ld.guia";
			$r = mysql_query($sql,$l)or die($sql); 
			$registros= array();
			while($f = mysql_fetch_object($r)){
				$f->sector = cambio_texto($f->sector);
				$f->motivo = cambio_texto($f->motivo);
				$registros[] = $f;
			}
			$detalle = str_replace('null','""',json_encode($registros));
			
			echo "({principal:$principal,detalle:$detalle})";
			
		}else{
			echo "no encontro";
		}
	}
	
	if($_GET[accion]==7){
		$s = "select id, concat_ws(' ',nombre,apellidopaterno,apellidomaterno) as conductor	from catalogoempleado where 
		id =".$_GET[idempleado]."";	
		$r=mysql_query($s,$l)or die($s); 
		$registros= array();			
		if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->conductor = cambio_texto($f->conductor);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
		}else{
				echo "no encontro";
		}
	}
	
	if($_GET[accion]==8){
	
		$sql="UPDATE liquidacion_detalle_tmp SET 
		nombre='".$_GET[nombre]."',identificacion='".$_GET[identificacion]."',
		numero_id='".$_GET[numero]."' WHERE guia='".$_GET[guia]."' AND idusuario='".$_SESSION[IDUSUARIO]."' ";
		mysql_query($sql,$l) or die($sql);
		echo "ok";
	}
	
	if($_GET[accion]==9){
		$s = " SELECT DISTINCT guia FROM liquidacion_detalleead WHERE guia='".$_GET[guia]."'";	
		$r=mysql_query($s,$l)or die($s); 
		$registros= array();
			
		if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
		}else{
				echo str_replace('null','""',json_encode(0));
		}
	}
	
	if($_GET[accion]==10){
		$s = "SELECT obtenerFolio('liquidacionead',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
		
		$s = "SELECT DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS fecha, 
		(SELECT descripcion FROM catalogosucursal where id=".$_SESSION[IDSUCURSAL].") as sucursal";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$f->sucursal = cambio_texto($f->sucursal);
		
		echo $fo->folio.",".$f->fecha.",".$_SESSION[IDSUCURSAL].",".$f->sucursal;
	}
	
	if($_GET[accion]==11){
		$s = "SELECT * FROM liquidacionead WHERE idreparto=".$_GET[folio]." and sucursal = $_SESSION[IDSUCURSAL]";
		$r=mysql_query($s,$l)or die($s); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}
	
	if($_GET[accion]==12){
		$s = "UPDATE liquidacionead SET cerro=1, cantidadentregada = '$_GET[cantidadentregada]', 
		diferencia = '".(($_GET[diferencia]<0)?0:$_GET[diferencia])."' where idreparto=".$_GET[folio]."";
		mysql_query($s,$l)or die($s); 
		
		echo "ok";
	
	}
	
	if($_GET[accion]==13){
		$s = "SELECT folio, cerro FROM devolucionmercancia 
		WHERE idreparto=".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r=mysql_query($s,$l)or die($s); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}
	
	if($_GET[accion]==14){		
		$s = "SELECT SUM(importe)AS importe FROM notacreditodetalle WHERE folionotacredito=$_GET[folio]";
		$r=mysql_query($s,$l)or die($s); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
	}
	
	if($_GET[accion]==15){
		$sql="UPDATE liquidacion_detalle_tmp SET 
		efectivo=".$_GET[efectivo].",cheque=".$_GET[cheque].",ncheque=".$_GET[ncheque].",
		banco=".$_GET[banco].",nnotacredito=".$_GET[nnotacredito].",notacredito=".$_GET[notacredito]." 
		WHERE guia='".$_GET[guia]."' AND idusuario='".$_SESSION[IDUSUARIO]."' ";
		mysql_query($sql,$l) or die($sql);
		echo "ok";
	}
	
	if($_GET[accion]==16){		
		$s="SELECT cliente FROM (SELECT cc.id AS cliente FROM liquidacion_detalleead ld 
		INNER JOIN guiasventanilla gv ON ld.guia=gv.id
		INNER JOIN catalogocliente cc ON  
		IF(gv.tipoflete=0, gv.idremitente=cc.id,gv.iddestinatario =cc.id)
		WHERE ld.tipoflete!='PAGADO' AND ld.condicionpago='CREDITO' AND ld.estado='ENTREGADA POR LIQUIDAR' 		        
		AND ISNULL(gv.factura) AND ld.idliquidacion='".$_GET[folio]."' AND ld.sucursal=".$_SESSION[IDSUCURSAL]." 
		GROUP BY cc.id	
		UNION
		SELECT cc.id AS cliente FROM liquidacion_detalleead ld 
		INNER JOIN guiasempresariales ge ON ld.guia=ge.id
		INNER JOIN catalogocliente cc ON  
		IF(ge.tipoflete='PAGADA', ge.idremitente=cc.id,ge.iddestinatario =cc.id)
		WHERE ld.tipoflete!='PAGADO' AND ld.condicionpago='CREDITO' AND ld.estado='ENTREGADA POR LIQUIDAR' 		        
		AND ISNULL(ge.factura) AND ld.idliquidacion='".$_GET[folio]."' AND ld.sucursal=".$_SESSION[IDSUCURSAL]."
		GROUP BY cc.id
		)Tabla GROUP BY cliente";
		$r=mysql_query($s,$l)or die($s); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}
	
	if($_GET[accion]==17){
		
		$tipos=$_GET[tipo];
		if ($tipos=="1"){
			$concatenacion=" AND ISNULL(gv.factura)";
		}else{
			$concatenacion="";
		}
		
		$s = "SELECT cc.id as cliente FROM liquidacion_detalleead ld 
		INNER JOIN guiasventanilla gv ON ld.guia=gv.id
		INNER JOIN catalogocliente cc ON  
		IF(gv.tipoflete=0, gv.idremitente=cc.id,gv.iddestinatario =cc.id)
		WHERE ld.tipoflete<>'PAGADO' AND ld.condicionpago='CREDITO' 
		AND ld.idliquidacion=".$_GET[folio]." AND ld.sucursal = ".$_SESSION[IDSUCURSAL]." $concatenacion GROUP BY cc.id	
	UNION
		SELECT cc.id as cliente FROM liquidacion_detalleead ld 
		INNER JOIN guiasempresariales gv ON ld.guia=gv.id
		INNER JOIN catalogocliente cc ON  
		IF(gv.tipoflete='PAGADA', gv.idremitente=cc.id,gv.iddestinatario =cc.id)
		WHERE ld.tipoflete<>'PAGADO' AND ld.condicionpago='CREDITO' 
		AND ld.idliquidacion=".$_GET[folio]." AND ld.sucursal = ".$_SESSION[IDSUCURSAL]." $concatenacion GROUP BY cc.id";
		$t = mysql_query($s,$l) or die($s);
		$tdes = mysql_num_rows($t);
		echo $tdes;
	}
	
	if($_GET[accion]==18){
		$s = "update liquidacion_detalle_tmp set seleccion = 0 where idusuario = $_SESSION[IDUSUARIO]";
		$r = mysql_query($s,$l) or die("error ".$s);
		
		$guias = "'".str_replace(",","','",$_GET[guiasseleccionadas])."'";

		$s = "update liquidacion_detalle_tmp set seleccion = 1 where idusuario = $_SESSION[IDUSUARIO] and guia in($guias)";
		$r = mysql_query($s,$l) or die("error ".$s);
	}
?>
