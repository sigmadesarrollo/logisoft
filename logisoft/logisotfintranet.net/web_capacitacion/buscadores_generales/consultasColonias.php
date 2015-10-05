<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	header('Content-type: text/xml');
	require_once('../Conectar.php');	
	$link=Conectarse('webpmm');	
	$cp=$_GET['cp'];	
	if($cp!=""){	
	$s = "SELECT cpo.codigopostal, cc.id as idcol, 
		cc.descripcion As colonia, cp.id as idpob, cp.descripcion as poblacion,
		cm.id as idmun, cm.descripcion as municipio, ce.id as idest, 
		ce.descripcion as estado, cpa.id as idpais, cpa.descripcion as pais FROM 
		catalogocolonia cc
		INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal
		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
		INNER JOIN catalogoestado ce ON cm.estado=ce.id
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
		WHERE cpo.codigopostal='$cp'";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__."-".mysql_error($link));
		if(mysql_num_rows($r)>0){
			$to=mysql_num_rows($r);
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);	
			
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml.="<cp>".$f->codigopostal."</cp>";
			$xml.="<poblacion>".cambio_texto($f->poblacion)."</poblacion>";
			$xml.="<municipio>".cambio_texto($f->municipio)."</municipio>";
			$xml.="<estado>".cambio_texto($f->estado)."</estado>";
			$xml.="<pais>".cambio_texto($f->pais)."</pais>";
			$xml.="<total>".$to."</total>";		
			if($to>1){
				$sql=mysql_query($s,$link);
			while($row=mysql_fetch_array($sql)){
			$xml.="<colonia>".cambio_texto($row['colonia'])."</colonia>";
			}
			}else{
			$xml.="<colonia>".cambio_texto($f->colonia)."</colonia>";
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
		}echo $xml;	
	}	
?>