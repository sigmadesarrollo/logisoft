<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT g.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
		IF(g.tipoflete=0,'PAGADO','POR COBRAR') AS flete,
		IF(g.condicionpago=0,'CONTADO','CREDITO') AS condicionpago,
		IFNULL(g.subtotal,0) AS subtotal, IFNULL(g.tiva,0) AS tiva,
		IFNULL(g.ivaretenido,0) AS ivaretenido, IFNULL(g.total,0) AS total
		FROM guiasventanilla g
		INNER JOIN catalogosucursal o ON g.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON g.idsucursalorigen = d.id
		WHERE g.fecha = CURRENT_DATE AND g.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."
		UNION
		SELECT ge.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
		ge.tipoflete AS flete, ge.tipopago AS condicionpago,
		IFNULL(ge.subtotal,0) AS subtotal, IFNULL(ge.tiva,0) AS tiva,
		IFNULL(ge.ivaretenido,0) AS ivaretenido, IFNULL(ge.total,0) AS total
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursalorigen = d.id
		WHERE ge.fecha = CURRENT_DATE AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."";
		if($_GET[tipo]=="0"){
			$r = mysql_query($s,$l) or die($s);
			echo mysql_num_rows($r);
		}else if($_GET[tipo]=="1"){
			$r = mysql_query($s." LIMIT ".$_GET[inicio].",30",$l) or die($s);
			$registros = array();
			if(mysql_num_rows($r)>0){
				while($f = mysql_fetch_object($r)){
					$f->guia = cambio_texto($f->guia);
					$f->origen = cambio_texto($f->origen);
					$f->destino = cambio_texto($f->destino);
					$registros[] = $f;
				}
				echo str_replace('null','""',json_encode($registros));
			}else{
				echo "no encontro";
			}
		}
	}else if($_GET[accion]==2){
		$s = "SELECT COUNT(*) AS total FROM guiasventanilla
		WHERE fecha = CURRENT_DATE AND idsucursalorigen = ".$_SESSION[IDSUCURSAL]."
		UNION
		SELECT COUNT(*) AS total FROM guiasempresariales
		WHERE fecha = CURRENT_DATE AND idsucursalorigen = ".$_SESSION[IDSUCURSAL];
		$r = mysql_query($s,$l) or die($s); $c = mysql_fetch_object($r);
		$re = $c->total%30; $res = intval($c->total/30) * 30;
		$limit = $res.",".$re;
		
		$s = "SELECT * FROM (SELECT g.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
		IF(g.tipoflete=0,'PAGADO','POR COBRAR') AS flete,
		IF(g.condicionpago=0,'CONTADO','CREDITO') AS condicionpago,
		IFNULL(g.subtotal,0) AS subtotal, IFNULL(g.tiva,0) AS tiva,
		IFNULL(g.ivaretenido,0) AS ivaretenido, IFNULL(g.total,0) AS total
		FROM guiasventanilla g
		INNER JOIN catalogosucursal o ON g.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON g.idsucursalorigen = d.id
		WHERE g.fecha = CURRENT_DATE AND g.idsucursalorigen = ".$_SESSION[IDSUCURSAL]."
		UNION
		SELECT ge.id AS guia, o.prefijo AS origen, d.prefijo AS destino,
		ge.tipoflete AS flete, ge.tipopago AS condicionpago,
		IFNULL(ge.subtotal,0) AS subtotal, IFNULL(ge.tiva,0) AS tiva,
		IFNULL(ge.ivaretenido,0) AS ivaretenido, IFNULL(ge.total,0) AS total
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal o ON ge.idsucursalorigen = o.id
		INNER JOIN catalogosucursal d ON ge.idsucursalorigen = d.id
		WHERE ge.fecha = CURRENT_DATE AND ge.idsucursalorigen = ".$_SESSION[IDSUCURSAL].") AS tb
		LIMIT ".$limit."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->cliente = cambio_texto($f->cliente);
				$f->descripcion = cambio_texto($f->descripcion);
				$f->contenido = cambio_texto($f->contenido);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}
	
?>