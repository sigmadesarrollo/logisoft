<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	if($_GET['accion']==1){//OBTENER BITACORA SALIDA
	$s = "SELECT b.fechabitacora, b.conductor1, b.conductor2, b.conductor3,
	b.gastos, b.licencia_conductor1, b.licencia_conductor2, 
	b.licencia_conductor3, b.poliza_remolque1, b.poliza_remolque2,
	b.poliza_unidad, b.remolque1, b.remolque2, b.ruta, b.tarjeta_remolque1,
	b.pcd_unidad,pcd_remolque1,pcd_remolque2,
	b.tarjeta_remolque2, b.tarjeta_unidad, b.unidad, b.vrf_unidad,
	b.gastos_estatus,b.id_cliente,b.Nombre_Cliente,b.fecha_Bodega,b.Hora_Bodega,
	r.descripcion AS rdescripcion,
	CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno) AS nombre1,
	CONCAT(e1.nombre,' ',e1.apellidopaterno,' ',e1.apellidomaterno) AS nombre2,
	CONCAT(e2.nombre,' ',e2.apellidopaterno,' ',e2.apellidomaterno) AS nombre3
	FROM bitacorasalida b
	INNER JOIN catalogoempleado e ON b.conductor1 = e.id 
	LEFT JOIN catalogoempleado e1 ON b.conductor2 = e1.id 
	LEFT JOIN catalogoempleado e2 ON b.conductor3 = e2.id 
	INNER JOIN catalogoruta r ON b.ruta = r.id 
	INNER JOIN catalogounidad u ON b.unidad = u.numeroeconomico
	LEFT JOIN catalogounidad u1 ON b.remolque1 = u1.numeroeconomico 
	LEFT JOIN catalogounidad u2 ON b.remolque2 = u2.numeroeconomico
	WHERE b.folio='".$_GET['folio']."'";	
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$fecha = cambiaf_a_normal($f->fechabitacora);
			$cant = mysql_num_rows($r);			
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 			<datos>";
		$xml.="<fechabitacora>".cambio_texto($fecha)."</fechabitacora>";
		$xml.="<conductor1>".cambio_texto($f->conductor1)."</conductor1>";
		$xml.="<conductor2>".cambio_texto($f->conductor2)."</conductor2>";
		$xml.="<conductor3>".cambio_texto($f->conductor3)."</conductor3>";
		$xml.="<gastos>".cambio_texto($f->gastos)."</gastos>";
		$xml.="<licencia_conductor1>".cambio_texto($f->licencia_conductor1)."</licencia_conductor1>";
		$xml.="<licencia_conductor2>".cambio_texto($f->licencia_conductor2)."</licencia_conductor2>";
		$xml.="<licencia_conductor3>".cambio_texto($f->licencia_conductor3)."</licencia_conductor3>";
		$xml.="<poliza_remolque1>".cambio_texto($f->poliza_remolque1)."</poliza_remolque1>";
		$xml.="<poliza_remolque2>".cambio_texto($f->poliza_remolque2)."</poliza_remolque2>";
		$xml.="<poliza_unidad>".cambio_texto($f->poliza_unidad)."</poliza_unidad>";
		$xml.="<remolque1>".cambio_texto($f->remolque1)."</remolque1>";
		$xml.="<remolque2>".cambio_texto($f->remolque2)."</remolque2>";
		$xml.="<ruta>".cambio_texto($f->ruta)."</ruta>";
		$xml.="<tarjeta_remolque1>".cambio_texto($f->tarjeta_remolque1)."</tarjeta_remolque1>";
		$xml.="<tarjeta_remolque2>".cambio_texto($f->tarjeta_remolque2)."</tarjeta_remolque2>";
		$xml.="<tarjeta_unidad>".cambio_texto($f->tarjeta_unidad)."</tarjeta_unidad>";
		$xml.="<vrf_unidad>".cambio_texto($f->vrf_unidad)."</vrf_unidad>";
		$xml.="<pcd_unidad>".cambio_texto($f->pcd_unidad)."</pcd_unidad>";
		$xml.="<pcd_remolque1>".cambio_texto($f->pcd_remolque1)."</pcd_remolque1>";
		$xml.="<pcd_remolque2>".cambio_texto($f->pcd_remolque2)."</pcd_remolque2>";
		$xml.="<unidad>".cambio_texto($f->unidad)."</unidad>";
		$xml.="<gastos_estatus>".cambio_texto($f->gastos_estatus)."</gastos_estatus>";
		$xml.="<id_cliente>".cambio_texto($f->id_cliente)."</id_cliente>";
		$xml.="<Nombre_Cliente>".cambio_texto($f->Nombre_Cliente)."</Nombre_Cliente>";
		$xml.="<fecha_Bodega>".cambio_texto($f->fecha_Bodega)."</fecha_Bodega>";
		$xml.="<Hora_Bodega>".cambio_texto($f->Hora_Bodega)."</Hora_Bodega>";
		$xml.="<rdescripcion>".cambio_texto($f->rdescripcion)."</rdescripcion>";
		$xml.="<nombre1>".cambio_texto($f->nombre1)."</nombre1>";
		$xml.="<nombre2>".cambio_texto($f->nombre2)."</nombre2>";
		$xml.="<nombre3>".cambio_texto($f->nombre3)."</nombre3>";
		$xml.="<encontro>".$cant."</encontro>";
		$xml.="</datos>
				</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
		
	}else if($_GET['accion']==2){//OBTENER
	$s = "SELECT CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) AS nombre FROM catalogoempleado 
	WHERE id='".$_GET['empleado']."' and enunidad=0 and puesto = 47 or puesto =48 or puesto =60";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<nombre>".cambio_texto($f->nombre)."</nombre>";
		$xml.="<caja>".cambio_texto($_GET['caja'])."</caja>";
		$xml.="<encontro>".$cant."</encontro>";		
		$xml.="</datos>
				</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			<caja>".cambio_texto($_GET['caja'])."</caja>
			</datos>
			</xml>";
		}echo $xml;
		
	}else if($_GET['accion']==3){//OBTENER UNIDAD Y REMOLQUE
	
		if($_GET['caja']==2 || $_GET['caja']==3){
			$and = " and tipounidad=3 ";
		}
		$s = "SELECT numeroeconomico FROM catalogounidad 
		WHERE numeroeconomico='".$_GET['unidad']."' 
		$and AND tiporuta='FORANEA' AND enuso=0 AND fueradeservicio=0";
		$r = mysql_query($s,$link) or die("$s <br>error en linea ".__LINE__);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
	$xml.="<numeroeconomico>".cambio_texto($f->numeroeconomico)."</numeroeconomico>";	
			$xml.="<caja>".cambio_texto($_GET['caja'])."</caja>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datos>
					</xml>";		
			}else{
				$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontro>0</encontro>
				<caja>".cambio_texto($_GET['caja'])."</caja>
				</datos>
				</xml>";
			}
		echo $xml;	
		
	}else if($_GET['accion']==4){//OBTENER RUTA
	$s = "SELECT descripcion FROM catalogoruta WHERE id='".$_GET['ruta']."'";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<descripcion>".cambio_texto($f->descripcion)."</descripcion>";
		$xml.="<encontro>".$cant."</encontro>";
		$xml.="</datos>
				</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;	
		
	}else if($_GET['accion']==5){// BUSCAR FOLIOBITACORA ----LIQUIDACION GASTOS.PHP
				
			$s = "SELECT * FROM liquidaciongastos WHERE foliobitacora = ".$_GET[folio];
			$r = mysql_query($s,$link) or die($s);
			if(mysql_num_rows($r)>0){
				$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<existe>si</existe>					
					<encontro>1</encontro>
					</datos>
					</xml>";
			}else{	
				$s = "SELECT afavorencontra FROM preliquidaciondebitacora WHERE foliobitacora=".$_GET['folio'];
				$r = mysql_query($s,$link) or die($s);
				$ff= mysql_fetch_object($r);
				
				$s = "SELECT BS.folio,BS.unidad,BS.gastos,EMP.nombre 
				FROM bitacorasalida  AS BS 
				INNER JOIN catalogoempleado AS EMP ON BS.conductor1=EMP.id
				WHERE BS.folio='".$_GET['folio']."'";
				$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
				if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$cant = mysql_num_rows($r);			
				$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>";
				$xml.="<foliobitacora>".$f->folio."</foliobitacora>";
				$xml.="<unidad>".$f->unidad."</unidad>";
				$xml.="<gastos>".$f->gastos."</gastos>";
				$xml.="<conductor>".$f->nombre."</conductor>";
				$xml.="<favor>".$ff->afavorencontra."</favor>";
				$xml.="<encontro>".$cant."</encontro>";
				$xml.="<existe>no</existe>";				
				$xml.="</datos>
						</xml>";		
				}else{
					$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<encontro>0</encontro>
					</datos>
					</xml>";
				}			
		}
		echo $xml;
	}else if($_GET['accion']==6){// BUSCAR FOLIOBITACORA ----prestamosucursal.PHP y complementosgastosbitacora.php
	$s = "SELECT BS.folio ,EMP.nombre FROM bitacorasalida AS BS INNER JOIN catalogoempleado AS EMP ON  BS.conductor1=EMP.id  WHERE  BS.folio='".$_GET['folio']."' ";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<foliobitacora>".$f->folio."</foliobitacora>";
		$xml.="<conductor>".$f->nombre."</conductor>";
		$xml.="<encontro>".$cant."</encontro>";
		$xml.="</datos>
				</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==7){// BUSCAR Liquidacion de gastos ----liquidaciondegastos.php 
	$s = "SELECT LG.folio,LG.fechai,LG.foliobitacora,LG.unidad,LG.gastos,LG.conductor,LGD.concepto,LGD.cantidad
FROM liquidaciongastos AS LG
INNER JOIN liquidaciongastosdetalle  AS LGD
ON LGD.folio=LG.folio
WHERE LG.folio='".$_GET['folio']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
			
			$xml.="<datos>";
			$rdetalle = mysql_query($s,$link) or die("error en linea ".__LINE__);
			while($row=mysql_fetch_object($rdetalle)){
				$xml.="<concepto>".$row->concepto."</concepto>";
				$xml.="<cantidad>".$row->cantidad."</cantidad>";
			}
			$xml.="</datos>";
			
			$xml.="<datos>";
			$fechai=cambiaf_a_normal($f->fechai);
			$xml.="<fecha>".$fechai."</fecha>";
			$xml.="<folio>".$f->folio."</folio>";
			$xml.="<foliobitacora>".$f->foliobitacora."</foliobitacora>";
			$xml.="<unidad>".$f->unidad."</unidad>";
			$xml.="<gastos>".$f->gastos."</gastos>";
			$xml.="<conductor>".$f->conductor."</conductor>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datos>";
			
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
		
	}else if($_GET['accion']==8){// BUSCAR prestamo sucursal ----presamossucursal.php 
	$s = "SELECT folio, fechai, foliobitacora, conductor, cantidad FROM prestamosucursal WHERE folio='".$_GET['folio']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
			$xml.="<datos>";
			$xml.="<fecha>".cambiaf_a_normal($f->fechai)."</fecha>";
			$xml.="<folio>".$f->folio."</folio>";
			$xml.="<foliobitacora>".$f->foliobitacora."</foliobitacora>";
			$xml.="<conductor>".$f->conductor."</conductor>";
			$xml.="<cantidad>".$f->cantidad."</cantidad>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datos>";
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==9){// BUSCAR Complemento Bitacora ----complementogastobitacora.php 
	$s = "SELECT folio,fechai,foliobitacora,conductor, cantidad FROM prestamosgastossucursal WHERE folio='".$_GET['folio']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
			$xml.="<datos>";
			$xml.="<fecha>".cambiaf_a_normal($f->fechai)."</fecha>";
			$xml.="<folio>".$f->folio."</folio>";
			$xml.="<foliobitacora>".$f->foliobitacora."</foliobitacora>";
			$xml.="<conductor>".$f->conductor."</conductor>";
			$xml.="<cantidad>".$f->cantidad."</cantidad>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datos>";
			
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}	else if($_GET['accion']==10){//OBTENER EMPLEADO
	$s = "SELECT CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) AS nombre FROM catalogoempleado WHERE id='".$_GET['id']."'";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<nombre>".cambio_texto($f->nombre)."</nombre>";
		$xml.="<caja>".cambio_texto($_GET['caja'])."</caja>";
		$xml.="<encontro>".$cant."</encontro>";		
		$xml.="</datos>
				</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			<caja>".cambio_texto($_GET['caja'])."</caja>
			</datos>
			</xml>";
		}echo $xml;		
	}else if($_GET['accion']==11){
	$s = "SELECT * FROM preliquidaciondebitacora WHERE foliobitacora =".$_GET[folio];
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
	
	if(mysql_num_rows($r)>0){
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<existe>si</existe>
			<encontro>0</encontro>
			</datos>
			</xml>";
	}else{		
		$s = "SELECT  b.conductor1,b.gastos,  b.unidad, b.conductor1,
		CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno) AS nombre
		FROM bitacorasalida b
		INNER JOIN catalogoempleado e ON b.conductor1 = e.id 
		INNER JOIN catalogounidad u ON b.unidad = u.numeroeconomico
		WHERE b.folio='".$_GET['folio']."' AND b.preliquidaciondebitacora=0";
			$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$cant = mysql_num_rows($r);			
				$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
				$xml.="<datos>";
				$xml.="<foliobitacora>".$_GET['folio']."</foliobitacora>";
				$xml.="<unidad>".cambio_texto($f->unidad)."</unidad>";
				$xml.="<gastos>".cambio_texto($f->gastos)."</gastos>";
				$xml.="<conductor>".cambio_texto($f->nombre)."</conductor>";
				$xml.="<idconductor>".cambio_texto($f->conductor1)."</idconductor>";
				$xml.="<encontro>".$cant."</encontro><existe>no</existe>";
				$xml.="</datos>";
				$xml.="</xml>";		
			}else{
				$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>
				<encontro>0</encontro>
				</datos>
				</xml>";
			}
	}
		echo $xml;
	}else if($_GET['accion']==12){
		$s = "SELECT id, CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) as nombre 
FROM catalogoempleado WHERE id='".$_GET['id']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
			$xml.="<datos>";
			$xml.="<id>".$_GET['id']."</id>";
			$xml.="<empleado>".cambio_texto($f->nombre)."</empleado>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datos>";
			
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	
	}else if($_GET['accion']==13){
		$s = "SELECT PRE.folio,date_format(PRE.fecha,'%d/%m/%Y') as fecha,PRE.afavorencontra,PRE.cantidad,
				BS.folio as foliobitacora,BS.unidad,BS.gastos,BS.conductor1,
				EMP1.id as idempleado1, CONCAT(EMP1.nombre,' ',EMP1.apellidopaterno,' ',EMP1.apellidomaterno) as entrego,
				EMP2.id as idempleado2, CONCAT(EMP2.nombre,' ',EMP2.apellidopaterno,' ',EMP2.apellidomaterno) as recibio
				FROM preliquidaciondebitacora AS PRE
				INNER JOIN bitacorasalida AS BS ON BS.folio=PRE.foliobitacora
				INNER JOIN catalogoempleado AS EMP1 ON EMP1.id=PRE.entrego
				INNER JOIN catalogoempleado AS EMP2 ON EMP2.id=PRE.recibio
				WHERE PRE.folio='".$_GET['folio']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
			
			$s = "SELECT liquidada FROM bitacorasalida WHERE folio = ".$f->foliobitacora."";
			$rr = mysql_query($s,$link) or die("error en linea ".__LINE__);
			$ff = mysql_fetch_object($rr);
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
			$xml.="<datos>";
			$xml.="<folio>".cambio_texto($f->folio)."</folio>";
			$xml.="<fecha>".cambio_texto($f->fecha)."</fecha>";
			$xml.="<foliobitacora>".cambio_texto($f->foliobitacora)."</foliobitacora>";
			$xml.="<unidad>".cambio_texto($f->unidad)."</unidad>";
			$xml.="<gastos>".cambio_texto($f->gastos)."</gastos>";
			$xml.="<conductor>".cambio_texto($f->conductor1)."</conductor>";
			$xml.="<r>".cambio_texto($f->afavorencontra)."</r>";
			$xml.="<cantidad>".cambio_texto($f->cantidad)."</cantidad>";
			$xml.="<entrego>".cambio_texto($f->idempleado1)."</entrego>";
			$xml.="<empleadoentrego>".cambio_texto($f->entrego)."</empleadoentrego>";
			$xml.="<recibio>".cambio_texto($f->idempleado2)."</recibio>";
			$xml.="<empleadorecibio>".cambio_texto($f->recibio)."</empleadorecibio>";
			$xml.="<liquidada>".cambio_texto($f->liquidada)."</liquidada>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datos>";
			
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==14){
		$fecha 		= date('d/m/Y'); 
		$row = ObtenerFolio('preliquidaciondebitacora','webpmm');
		$folio = $row[0];
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
			$xml.="<datos>";
			$xml.="<folio>".cambio_texto($folio)."</folio>";
			$xml.="<fecha>".$fecha."</fecha>";
			$xml.="</datos>";
			$xml.="</xml>";		
		echo $xml;
	}else if($_GET['accion']==15){		
		$s = "SELECT * FROM comprobantedeliquidaciondebitacora WHERE foliobitacora =".$_GET[folio];
		$r = mysql_query($s,$link) or die($s);
		
		if(mysql_num_rows($r)>0){
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<existeliquidacion>si</existeliquidacion>
					<encontro>1</encontro>
					</datos>
					</xml>";
		}else{		
			$s = "SELECT * FROM preliquidaciondebitacora WHERE foliobitacora = ".$_GET[folio];
			$r = mysql_query($s,$link) or die($s);		
			if(mysql_num_rows($r)==0){
				$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
						<datos>
						<existepreliquidacion>no</existepreliquidacion>
						<existeliquidacion>no</existeliquidacion>
						<encontro>1</encontro>
						</datos>
						</xml>";
			}else{
				$s = "SELECT  b.conductor1, b.gastos, b.unidad, 
				CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno) AS nombre
				FROM bitacorasalida b
				INNER JOIN catalogoempleado e ON b.conductor1 = e.id 
				INNER JOIN catalogounidad u ON b.unidad = u.numeroeconomico
				WHERE b.folio='".$_GET['folio']."'";
				$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
				if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
					$cant = mysql_num_rows($r);			
					$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
					$xml.="<datos>";
					$xml.="<foliobitacora>".$_GET['folio']."</foliobitacora>";
					$xml.="<unidad>".cambio_texto($f->unidad)."</unidad>";
					$xml.="<gastos>".cambio_texto($f->gastos)."</gastos>";
					$xml.="<conductor>".cambio_texto($f->nombre)."</conductor>";
					
					$sqlpre="SELECT folio,cantidad,afavorencontra FROM preliquidaciondebitacora WHERE foliobitacora='".$_GET['folio']."'";
					$sql_pre=mysql_query($sqlpre,$link)or die("error en linea ".__LINE__);
					$row=mysql_fetch_array($sql_pre);
					
					$xml.="<foliopre>".cambio_texto($row[folio])."</foliopre>";
					$xml.="<cantidadpre>".cambio_texto($row[cantidad])."</cantidadpre>";
					$xml.="<afavorencontra>".cambio_texto($row[afavorencontra])."</afavorencontra>";
					$xml.="<encontro>".$cant."</encontro>";
					$xml.="<existepreliquidacion>si</existepreliquidacion>";
					$xml.="<existeliquidacion>no</existeliquidacion>";
					$xml.="</datos>";
					
					$xml.="</xml>";		
				}else{
					$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
					<datos>
					<encontro>0</encontro>
					</datos>
					</xml>";
				}
		}
	}
	echo $xml;
	}else if($_GET['accion']==16){
		
		$s = "SELECT  clb.folio,DATE_FORMAT(clb.fecha,'%d/%m/%Y') AS fecha,
				b.folio as foliobitacora,b.conductor1,b.gastos,  b.unidad, 
				CONCAT(e.nombre,' ',e.apellidopaterno,' ',e.apellidomaterno) AS nombre,
				IF(clb.status='COMPROBANTE LIQUIDACION','LIQUIDADO','NO LIQUIDADO') AS estado
				FROM comprobantedeliquidaciondebitacora clb
				INNER JOIN bitacorasalida b ON b.folio=clb.foliobitacora
				INNER JOIN catalogoempleado e ON b.conductor1 = e.id 
				INNER JOIN catalogounidad u ON b.unidad = u.numeroeconomico
				WHERE clb.folio='".$_GET['folio']."' AND clb.sucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\">"; 
			$xml.="<datosX>";
			$xml.="<folio>".$_GET['folio']."</folio>";
			$xml.="<fecha>".cambio_texto($f->fecha)."</fecha>";
			$xml.="<foliobitacora>".$f->foliobitacora."</foliobitacora>";
			$xml.="<unidad>".cambio_texto($f->unidad)."</unidad>";
			$xml.="<gastos>".cambio_texto($f->gastos)."</gastos>";
			$xml.="<conductor>".cambio_texto($f->nombre)."</conductor>";
			$xml.="<estado>".cambio_texto($f->estado)."</estado>";
			$sqlpre="SELECT folio,IFNULL(cantidad,0)AS cantidad,afavorencontra FROM preliquidaciondebitacora WHERE foliobitacora='".$f->foliobitacora."'";
			$sql_pre=@mysql_query($sqlpre,$link)or die("error en linea ".__LINE__);
			$row=@mysql_fetch_array($sql_pre);
			$xml.="<foliopre>".cambio_texto($row[folio])."</foliopre>";
			$xml.="<cantidadpre>".cambio_texto($row[cantidad])."</cantidadpre>";
			$xml.="<afavorencontra>".cambio_texto($row[afavorencontra])."</afavorencontra>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="</datosX>";
			
			$sqldetalle="select idconcepto,concepto,ifnull(cantidad,0)AS cantidad 
			from comprobantedeliquidaciondebitacoradetalle 
			where comprobantedeliquida ='".$_GET['folio']."' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
			$sql_detalle=mysql_query($sqldetalle,$link);
			$xml.="<datos>";
			while($r=mysql_fetch_array($sql_detalle)){
			$xml.="<idconcepto>".cambio_texto($r['idconcepto'])."</idconcepto>";
			$xml.="<concepto>".cambio_texto($r['concepto'])."</concepto>";
			$xml.="<cantidad>".cambio_texto($r['cantidad'])."</cantidad>";
			}
			$xml.="</datos>";
			$xml.="</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}

?>