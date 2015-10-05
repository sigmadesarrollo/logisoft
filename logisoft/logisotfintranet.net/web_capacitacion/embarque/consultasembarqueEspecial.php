<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	
if($_GET['accion']==1){//  embarquedemercancia.php
	$s = "SELECT bitacorasalida.folio, bitacorasalida.unidad,bitacorasalida.ruta,catalogoruta.descripcion 
	FROM bitacorasalida 
	INNER JOIN catalogoruta ON  bitacorasalida.ruta=catalogoruta.id 
	WHERE bitacorasalida.unidad='$_GET[unidad]' and bitacorasalida.folio = '$_GET[bitacora]'";
		$r = mysql_query($s,$link) or die($s."error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$s = "SELECT (IFNULL(cu1.cvolumen,0) + IFNULL(cu2.cvolumen,0) + IFNULL(cu3.cvolumen,0)) AS capacidad
			FROM bitacorasalida AS bs
			LEFT JOIN catalogounidad AS cu1 ON bs.unidad = cu1.numeroeconomico
			LEFT JOIN catalogounidad AS cu2 ON bs.remolque1 = cu2.numeroeconomico
			LEFT JOIN catalogounidad AS cu3 ON bs.remolque2 = cu3.numeroeconomico
			WHERE bs.folio = $f->folio";
			$rx = mysql_query($s,$link) or die($s);
			$fx = mysql_fetch_object($rx);
			
			$s = "SELECT IFNULL(SUM(encontrados),0) as total FROM(
				(SELECT COUNT(*) encontrados FROM guiaventanilla_unidades WHERE proceso = 'POR RECIBIR' AND unidad = '$_GET[unidad]')
				UNION
				(SELECT COUNT(*) encontrados FROM guiasempresariales_unidades WHERE proceso = 'POR RECIBIR' AND unidad = '$_GET[unidad]')
			) AS t1";
			$ry = mysql_query($s,$link) or die($s);
			$fy = mysql_fetch_object($ry);
			
			$s = "SELECT GROUP_CONCAT(d.sucursal) AS sucursal FROM catalogoruta cr
			INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
			WHERE cr.id = ".$f->ruta." AND tipo between 2 AND 3";
			$ru = mysql_query($s,$link) or die($s);
			$cr = mysql_fetch_object($ru);
			
			$s = "SELECT sucursalestransbordo FROM catalogorutadetalle WHERE ruta=".$f->ruta." AND tipo BETWEEN 2 AND 3";
			$ryy = mysql_query($s,$link) or die($s);
		
			$sucursales = "";
			
			while($fyy = mysql_fetch_object($ryy)){			
				if(!empty($fyy->sucursalestransbordo)){
					$ro = split(",",$fyy->sucursalestransbordo);
					for($i=0;$i < count($ro);$i++){
						$y = split(":",$ro[$i]);
						for($j=0;$j < count($y);$j++){
							if(is_numeric($y[$j])){
								$sucursales .= $y[$j].",";
							}
						}
					}					
				}
			}
			
			if(!empty($sucursales)){
				$cr->sucursal = $cr->sucursal.",".substr($sucursales,0, strlen($sucursales)-1);
			}
			
			$cant = mysql_num_rows($r);			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
				<datos>";
			$xml.="<unidad>".$f->unidad."</unidad>";
			$xml.="<total>".$fy->total."</total>";
			$xml.="<ruta>".$f->ruta."</ruta>";
			$xml.="<descripcion>".cambio_texto($f->descripcion)."</descripcion>";
			$xml.="<capacidad>".$fx->capacidad."</capacidad>";
			$xml.="<folio>".$f->folio."</folio>";
			$xml.="<destino>".cambio_texto($cr->sucursal)."</destino>";
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
}
?>