<? 	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	
	if($_GET['accion']==1){//  embarquedemercancia.php	y recepcionmercancia.php
	$s = "SELECT bs.folio,p.ruta, cr.descripcion AS desruta FROM programacionrecepciondiaria p 
	INNER JOIN catalogoruta cr ON p.ruta=cr.id 
	INNER JOIN bitacorasalida bs ON bs.ruta=cr.id 
	WHERE bs.unidad='".$_GET[unidad]."' and bs.status = 0 AND p.hrllegada<>'00:00:00'";
		$r = mysql_query($s,$link) or die($s."error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			$xml.="<ruta>".$f->ruta."</ruta>";
			$xml.="<descripcion>".$f->desruta."</descripcion>";
			$xml.="<encontro>".$cant."</encontro>";
			$xml.="<foliobitacora>".$f->folio."</foliobitacora>";
			$xml.="</datos>
					</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
}else if($_GET['accion']==2){
	$s = "SELECT CS.descripcion AS sector ,GV.id ,CSU.prefijo,GV.id  
		FROM guiasventanilla  AS GV
		INNER JOIN catalogosucursal AS CSU ON CSU.id=GV.idsucursalorigen
		INNER JOIN catalogosector AS CS ON CS.id=GV.sector
		WHERE GV.ocurre=0 AND GV.estado='ALMACEN ORIGEN'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			while($f = mysql_fetch_object($r)){
				$xml.="<sector>".$f->sector."</sector>";
				$xml.="<guia>".$f->id."</guia>";
				$xml.="<origen>".$f->prefijo."</origen>";
				$xml.="<codigobarra>".$f->id."</codigobarra>";
			}
			$xml.="</datos>
					</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			</datos>
			</xml>";
		}echo $xml;
}else if($_GET['accion']==3){
		$s = "SELECT id,totalpaquetes,estado FROM guiasventanilla WHERE id='".$_GET['guia']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$cant = mysql_num_rows($r);	
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			$i=1;
			while($f = mysql_fetch_object($r)){
				$xml.="<registro>$i</registro>";
				$xml.="<paquete>".$f->totalpaquetes."</paquete>";
				$xml.="<codigobarra>||||</codigobarra>";
				$xml.="<estado>".$f->estado."</estado>";
				$i++;
			}
			$xml.="</datos>
					</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			</datos>
			</xml>";
		}echo $xml;
}else if($_GET['accion']==4){
		$s = "SELECT id,totalpaquetes,estado FROM guiasventanilla WHERE id='".$_GET['guia']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$cant = mysql_num_rows($r);	
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			$i=1;
			while($f = mysql_fetch_object($r)){
				$xml.="<registro>$i</registro>";
				$xml.="<paquete>".$f->totalpaquetes."</paquete>";
				$xml.="<codigobarra>||||</codigobarra>";
				$xml.="<estado>".$f->estado."</estado>";
				$i++;
			}		
			$xml.="</datos>
					</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			</datos>
			</xml>";
		}echo $xml;
}else if($_GET['accion']==5){//OBTENER
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
		
	}


?>