<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	if($_GET['accion']==1){// OBTENER SECTOR(GUIAS RELACIONADAS)
	$s = "SELECT g.id AS guia, cs.prefijo AS origen, g.fecha, 0 AS codigobarra,
		  s.descripcion AS sector FROM guiasventanilla g
		  INNER JOIN catalogosucursal cs ON g.idsucursalorigen = cs.id
		  INNER JOIN catalogosector s ON g.sector = s.id
		  WHERE g.ocurre=0 AND g.idsucursalorigen=".$_GET['sucursal']."
		  AND g.entradasalida='SALIDA' AND g.sector=".$_GET['sector']."";
	$r = mysql_query($s,$link) or die(mysql_error($link)."error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);	
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 		<datos>";
		if($cant>1){
			while($row=mysql_fetch_array($r)){
			$xml.="<guia>".cambio_texto($row[guia])."</guia>";
			$xml.="<origen>".cambio_texto($row[origen])."</origen>";
			$xml.="<fecha>".cambiaf_a_normal($row[fecha])."</fecha>";
		$xml.="<codigobarra>".cambio_texto($row[codigobarra])."</codigobarra>";
			}
		}else{
			$xml.="<guia>".cambio_texto($f->guia)."</guia>";
			$xml.="<origen>".cambio_texto($f->origen)."</origen>";
			$xml.="<fecha>".cambio_texto($f->fecha)."</fecha>";
			$xml.="<codigobarra>".cambio_texto($f->codigobarra)."</codigobarra>";
		}

		$xml.="</datos>
		<datos>
				<sector>".cambio_texto($f->sector)."</sector>	
		</datos>

				</xml>";		
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>			
			</datos>
			</xml>";
		}echo $xml;		
		
	}else if($_GET['accion']==2){//OBTENER CONDUCTORES
	$s = "SELECT CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) AS nombre FROM catalogoempleado WHERE id='".$_GET['empleado']."'";
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
	
	}else if($_GET['accion']==3){//OBTENER UNIDAD
$s = "SELECT numeroeconomico FROM catalogounidad WHERE numeroeconomico=".$_GET['unidad']."";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);			
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<unidad>".cambio_texto($f->numeroeconomico)."</unidad>";
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
		
	}else if($_GET['accion']==4){//OBTENER DATOS GUIA ABAJO
//	$s = "SELECT * FROM guiaventanilla WHERE guia=".$_GET['guia']."";
	//$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
	//	if(mysql_num_rows($r)>0){
	//		$f = mysql_fetch_object($r);
	//		$cant = mysql_num_rows($r);	
		
		if($_GET[guia]!=""){
		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
		$xml.="<registro>fffffffff</registro>";
		$xml.="<paquete>gggggggggg</paquete>";
		$xml.="<codigobarra>gggggggggg</codigobarra>";
		$xml.="<estado>gggggggggg</estado>";
		$xml.="</datos>
				</xml>";
				
	//	}else{
	/*		$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";*/
		}echo $xml;			
	}
?>