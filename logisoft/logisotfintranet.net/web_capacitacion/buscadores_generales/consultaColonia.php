<? session_start();	
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	
	if($_GET[accion] == 1){
		$s = "SELECT cpo.codigopostal, cc.id as idcolonia, 
		cc.descripcion As colonia, cp.id as idpob, cp.descripcion as poblacion,
		cm.id as idmun, cm.descripcion as municipio, ce.id as idest, 
		ce.descripcion as estado, cpa.id as idpais, cpa.descripcion as pais FROM 
		catalogocolonia cc
		INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal
		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
		INNER JOIN catalogoestado ce ON cm.estado=ce.id
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
		WHERE cc.id=".$_GET[colonia]."";
		$r = mysql_query($s,$link) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){
				$f->codigopostal = cambio_texto($f->codigopostal);
				$f->colonia = cambio_texto($f->colonia);
				$f->poblacion = cambio_texto($f->poblacion);
				$f->municipio = cambio_texto($f->municipio);
				$f->estado = cambio_texto($f->estado);
				$f->pais = cambio_texto($f->pais);
				$registros[] = $f;
			}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion] == 2){						
		$s = "SELECT cpo.codigopostal, cc.id as idcolonia, 
		cc.descripcion As colonia, cp.id as idpob, cp.descripcion as poblacion,
		cm.id as idmun, cm.descripcion as municipio, ce.id as idest, 
		ce.descripcion as estado, cpa.id as idpais, cpa.descripcion as pais FROM 
		catalogocolonia cc
		INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal
		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
		INNER JOIN catalogoestado ce ON cm.estado=ce.id
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
		WHERE cc.id=".$_GET[idcolonia]."";
		$r = mysql_query($s,$link) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$f->codigopostal = cambio_texto($f->codigopostal);
			$f->colonia = cambio_texto($f->colonia);
			$f->poblacion = cambio_texto($f->poblacion);
			$f->municipio = cambio_texto($f->municipio);
			$f->estado = cambio_texto($f->estado);
			$f->pais = cambio_texto($f->pais);
			
			echo str_replace('null','""',json_encode($f));
		}else{
			echo "noexiste_xx_xxx";	
		}		
	}
?>