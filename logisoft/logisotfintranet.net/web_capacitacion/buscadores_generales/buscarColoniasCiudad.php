<?	include('../Conectar.php');
	$l=Conectarse('webpmm');
	
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
		WHERE cc.descripcion like '%".$_GET['colonia']."%' AND cp.descripcion LIKE '%".$_GET['ciudad']."%'";
		$r = mysql_query($s,$l) or die($s);
		
		$cont = 0;
		$contr = 0;
		$datos = "";
		$comienzo = true;
		echo "[";
		if(mysql_num_rows($r)>0){
			$registros = mysql_num_rows($r);
			while($f = mysql_fetch_object($r)){
				$cont++;
				$contr++;
				if($cont==1){
					if($comienzo){
						$comienzo=false;
					}else{
						echo ",";
					}
					echo "{'inicial':'$f->guia','datos':[";
				}
				$f->colonia = cambio_texto($f->colonia);
				$f->poblacion = cambio_texto($f->poblacion);
				$f->municipio = cambio_texto($f->municipio);
				$f->estado = cambio_texto($f->estado);
				$f->pais = cambio_texto($f->pais);
				if($cont==30){
					echo json_encode($f);
					if($contr!=$registros){
						echo "]}";
					}
					$cont = 0;
				}else{
					echo json_encode($f);
					if($contr!=$registros){
						echo ",";
					}
				}
			}	
		}
		echo "]}]";
		
?>

