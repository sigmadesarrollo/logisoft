<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
		
	if($_GET[accion]==1){		
		if(!empty($_GET[nombre])){
			
			$fecinicio=cambiaf_a_mysql($_GET[fechainicio]);
			$fecfin=cambiaf_a_mysql($_GET[fechafin]);
			
			$s = "SELECT gv.id AS guia, des.prefijo AS destino, ori.prefijo AS origen, date_format(gv.fecha, '%d/%m/%Y') fecha, gv.estado 
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
			INNER JOIN catalogocliente cd ON gv.iddestinatario = cd.id
			INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
			INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
			WHERE (CONCAT_WS(' ', cc.nombre,cc.paterno, cc.materno) LIKE '%".$_GET[nombre]."%' OR 
				CONCAT_WS(' ', cd.nombre,cd.paterno, cd.materno) LIKE '%".$_GET[nombre]."%')
				AND gv.fecha BETWEEN '$fecinicio' AND '$fecfin'
			UNION
			SELECT ge.id AS guia, des.prefijo AS destino, ori.prefijo AS origen, date_format(ge.fecha, '%d/%m/%Y') fecha, ge.estado 
			FROM guiasempresariales ge
			INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
			INNER JOIN catalogocliente cd ON ge.iddestinatario = cd.id
			INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
			INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
			WHERE (CONCAT_WS(' ', cc.nombre,cc.paterno, cc.materno) LIKE '%".$_GET[nombre]."%' OR 
				CONCAT_WS(' ', cd.nombre,cd.paterno, cd.materno) LIKE '%".$_GET[nombre]."%')
				AND ge.fecha BETWEEN '$fecinicio' AND '$fecfin'";
				
				
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->guia = cambio_texto($f->guia);
					$f->destino = cambio_texto($f->destino);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}else if(!empty($_GET[guia])){
			$s = "SELECT gv.id AS guia, des.prefijo AS destino, ori.prefijo AS origen, date_format(gv.fecha, '%d/%m/%Y') fecha, gv.estado 
			FROM guiasventanilla gv
			INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
			INNER JOIN catalogocliente cd ON gv.iddestinatario = cd.id
			INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
			INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
			WHERE gv.id = '".$_GET[guia]."'
			UNION
			SELECT ge.id AS guia, des.prefijo AS destino, ori.prefijo AS origen, date_format(ge.fecha, '%d/%m/%Y') fecha, ge.estado 
			FROM guiasempresariales ge
			INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
			INNER JOIN catalogocliente cd ON ge.iddestinatario = cd.id
			INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
			INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
			WHERE ge.id = '".$_GET[guia]."'";			
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->guia = cambio_texto($f->guia);
					$f->destino = cambio_texto($f->destino);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}else if(!empty($_GET[rastreo])){
			$s = "SELECT noguia FROM guia_rastreo WHERE numerorastreo = '".$_GET[rastreo]."'";
			$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
			
			$s = "SELECT gv.id AS guia, des.prefijo AS destino, ori.prefijo AS origen, date_format(gv.fecha, '%d/%m/%Y') fecha, gv.estado 
			FROM guiasventanilla gv
			INNER JOIN guia_rastreo gr ON gv.id = gr.noguia
			INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
			INNER JOIN catalogocliente cd ON gv.iddestinatario = cd.id
			INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
			INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
			WHERE gr.numerorastreo = '".$_GET[rastreo]."'
			UNION
			SELECT ge.id AS guia, des.prefijo AS destino, ori.prefijo AS origen, date_format(ge.fecha, '%d/%m/%Y') fecha, ge.estado 
			FROM guiasempresariales ge
			INNER JOIN guia_rastreo gr ON ge.id = gr.noguia
			INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
			INNER JOIN catalogocliente cd ON ge.iddestinatario = cd.id
			INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
			INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
			WHERE gr.numerorastreo = '".$_GET[rastreo]."'";			
			$r = mysql_query($s,$l) or die($s);
			$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->guia = cambio_texto($f->guia);
					$f->destino = cambio_texto($f->destino);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}else if(!empty($_GET[recoleccion])){
			
			if($_GET[recoleccion]!=0 && is_numeric($_GET[recoleccion])){
				$s = "SELECT gv.id AS guia, des.prefijo AS destino, ori.prefijo AS origen, date_format(gv.fecha, '%d/%m/%Y') fecha, gv.estado 
				FROM guiasventanilla gv
				INNER JOIN catalogocliente cc ON gv.idremitente = cc.id
				INNER JOIN catalogocliente cd ON gv.iddestinatario = cd.id
				INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
				INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
				INNER JOIN evaluacionmercancia e ON gv.evaluacion = e.folio AND gv.idsucursalorigen = e.sucursal
				WHERE e.recoleccion = '".$_GET[recoleccion]."'
				UNION
				SELECT ge.id AS guia, des.prefijo AS destino, ori.prefijo AS origen, date_format(ge.fecha, '%d/%m/%Y') fecha, ge.estado 
				FROM guiasempresariales ge
				INNER JOIN catalogocliente cc ON ge.idremitente = cc.id
				INNER JOIN catalogocliente cd ON ge.iddestinatario = cd.id
				INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
				INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
				INNER JOIN evaluacionmercancia e ON ge.evaluacion = e.folio AND ge.idsucursalorigen = e.sucursal
				WHERE e.recoleccion= '".$_GET[recoleccion]."'";
				$r = mysql_query($s,$l) or die($s);
				$registros = array();
				if(mysql_num_rows($r)>0){
					while($f = mysql_fetch_object($r)){
						$f->guia = cambio_texto($f->guia);
						$f->destino = cambio_texto($f->destino);
						$registros[] = $f;
					}
					echo str_replace('null','""',json_encode($registros));
				}else{
					echo "no encontro";
				}
			}else{
				echo "no encontro";
			}
		}
	}

?>