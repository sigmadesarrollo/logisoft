<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');	
	
	if($_GET['tipo']=='default'){
		$s = "SELECT * FROM catalogopais WHERE defaul=1";	
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>								
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==1){	
			$s="SELECT cc.id, cc.descripcion, cc.cp, cp.id as poblacion, cp.descripcion as despoblacion, cm.descripcion as municipio, ce.descripcion as estado, cpa.descripcion as pais FROM catalogocolonia cc
				INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
				INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
				INNER JOIN catalogoestado ce ON cm.estado=ce.id
				INNER JOIN catalogopais cpa ON ce.pais=cpa.id
				WHERE cc.id='".$_GET['colonia']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);

			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>			
			<descripcion>$f->descripcion</descripcion>
			<poblacion>$f->poblacion</poblacion>
			<cp>$f->cp</cp>
			<estado>$f->estado</estado>
			<municipio>$f->municipio</municipio>
			<pais>$f->pais</pais>
			<despoblacion>$f->despoblacion</despoblacion>						
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	
		}else if($_GET['accion']==2){
			$s="SELECT cp.id, cp.descripcion as despoblacion, cm.descripcion as municipio, ce.descripcion as estado, cpa.descripcion as pais FROM catalogopoblacion cp
				INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
				INNER JOIN catalogoestado ce ON cm.estado=ce.id
				INNER JOIN catalogopais cpa ON ce.pais=cpa.id
				WHERE cp.id='".$_GET['poblacion']."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);

			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<estado>$f->estado</estado>
			<municipio>$f->municipio</municipio>
			<pais>$f->pais</pais>
			<despoblacion>$f->despoblacion</despoblacion>						
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	
		}else if($_GET['accion']==3){
			$s = "SELECT cp.id, c.descripcion as colonia, 
			p.descripcion as poblacion, m.descripcion as municipio,
			e.descripcion as estado, pa.descripcion as pais FROM 
			catalogocodigopostal cp
			INNER JOIN catalogocolonia c ON cp.codigopostal=c.cp
			INNER JOIN catalogopoblacion p ON c.poblacion=p.id
			INNER JOIN catalogomunicipio m ON p.municipio=m.id
			INNER JOIN catalogoestado e ON m.estado=e.id
			INNER JOIN catalogopais pa ON e.pais=pa.id
			WHERE cp.codigopostal='".$_GET['codigopostal']."'";		
			$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
			$to=mysql_num_rows($r);
			if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml.="<total>".$to."</total>";	
		if($cant>1){
			$sql=mysql_query($s,$link);
			while($row=mysql_fetch_array($sql)){
			$xml.="<id>".cambio_texto($row[0])."</id>";
			$xml.="<colonia>".cambio_texto($row[1])."</colonia>";
			$xml.="<poblacion>".cambio_texto($row[2])."</poblacion>";
			$xml.="<municipio>".cambio_texto($row[3])."</municipio>";
			$xml.="<estado>".cambio_texto($row[4])."</estado>";
			$xml.="<pais>".cambio_texto($row[5])."</pais>";
			}
		}else{
			$xml.="<id>".cambio_texto($row[0])."</id>";
			$xml.="<colonia>".cambio_texto($f->colonia)."</colonia>";
			$xml.="<poblacion>".cambio_texto($f->poblacion)."</poblacion>";
			$xml.="<municipio>".cambio_texto($f->municipio)."</municipio>";
			$xml.="<estado>".cambio_texto($f->estado)."</estado>";
			$xml.="<pais>".cambio_texto($f->pais)."</pais>";
		}
			$xml.="<encontro>$cant</encontro>";
			$xml.="</datos>
					</xml>";			
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		} echo $xml;
	
	}else if($_GET['accion']==4){
			$s = "SELECT codigopostal FROM catalogocodigopostal
			WHERE codigopostal='".$_GET['codigopostal']."'";		
			$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
			if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";					
			$xml.="<existe>SI</existe>";			
			$xml.="<encontro>$cant</encontro>";
			$xml.="</datos>
					</xml>";			
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		} echo $xml;
	}else if($_GET['accion']==5){
			$s = "SELECT m.id, m.descripcion As municipio, e.descripcion As 
			estado, p.descripcion As pais FROM catalogomunicipio m
			INNER JOIN catalogoestado e ON m.estado=e.id
			INNER JOIN catalogopais p ON e.pais=p.id
			WHERE m.id='".$_GET['municipio']."'";
			$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
			if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml.="<municipio>".cambio_texto($f->municipio)."</municipio>";
			$xml.="<estado>".cambio_texto($f->estado)."</estado>";
			$xml.="<pais>".cambio_texto($f->pais)."</pais>";
			$xml.="<encontro>$cant</encontro>";
			$xml.="</datos>
					</xml>";			
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		} echo $xml;
	}else if($_GET['accion']==6){
			$s = "SELECT cp.id, cp.descripcion As poblacion,
			m.id As municipio, m.descripcion As descripcionmunicipio,
			e.descripcion As estado, p.descripcion As pais FROM
			catalogopoblacion cp			
			INNER JOIN catalogomunicipio m ON cp.municipio=m.id
			INNER JOIN catalogoestado e ON m.estado=e.id
			INNER JOIN catalogopais p ON e.pais=p.id
			WHERE cp.id='".$_GET['poblacion']."'";
			$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
			if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml.="<poblacion>".cambio_texto($f->poblacion)."</poblacion>";
			$xml.="<municipio>".cambio_texto($f->municipio)."</municipio>";
			$xml.="<descripcionmunicipio>".cambio_texto($f->descripcionmunicipio)."</descripcionmunicipio>";
			$xml.="<estado>".cambio_texto($f->estado)."</estado>";
			$xml.="<pais>".cambio_texto($f->pais)."</pais>";
			$xml.="<accion>modificar</accion>";			
			$xml.="<encontro>$cant</encontro>";
			$xml.="</datos>
					</xml>";			
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		} echo $xml;
	}
?>
