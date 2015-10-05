<?	session_start();
	require_once("../Conectar.php");
	$l=Conectarse('webpmm');
	
	if ($_GET[accion]==1){	
		$sql="SELECT codigo,sucursal,guias,date_format(fecha, '%d/%m/%Y')AS fecha,
		remitente,destinatario,valordeclarado,seguro FROM (	
		SELECT cs.id AS codigo,cs.Descripcion AS sucursal,gv.id AS guias,gv.fecha,
		CONCAT(ccr.nombre,' ',ccr.paterno,' ',ccr.materno)AS remitente,
		CONCAT(ccd.nombre ,' ',ccd.paterno,' ', ccd.materno)AS destinatario,
		IFNULL(gv.valordeclarado,0) AS valordeclarado, IFNULL(gv.valordeclarado * (SELECT IFNULL(prima / 100,0)AS prima 
		FROM configuradorgeneral),0) AS seguro FROM guiasempresariales gv
		INNER JOIN catalogosucursal cs ON cs.id = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)		
		LEFT JOIN catalogocliente ccr ON gv.idremitente=ccr.id
		LEFT JOIN catalogocliente ccd ON gv.iddestinatario=ccd.id	
		WHERE gv.valordeclarado>=(SELECT cantidadvalordeclarado FROM configuradorgeneral)  
		UNION ALL
		SELECT cs.id AS codigo,cs.Descripcion AS sucursal,gv.id AS guias,gv.fecha, 
		CONCAT(ccr.nombre,' ',ccr.paterno,' ',ccr.materno)AS remitente,
		CONCAT(ccd.nombre ,' ',ccd.paterno,' ', ccd.materno)AS destinatario,
		IFNULL(gv.valordeclarado,0) AS valordeclarado, 
		IFNULL(gv.valordeclarado * (SELECT IFNULL(prima / 100,0)AS prima FROM configuradorgeneral),0) AS seguro 
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal cs ON cs.id   = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)		
		LEFT JOIN catalogocliente ccr ON gv.idremitente=ccr.id
		LEFT JOIN catalogocliente ccd ON gv.iddestinatario=ccd.id
		WHERE gv.valordeclarado>=(SELECT cantidadvalordeclarado FROM configuradorgeneral))ValoresDeclaradosDetalle 
		where fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		".(($_GET[sucursal]!=1)?" and codigo='".$_GET[sucursal]."' ":"")." ORDER BY fecha";
			$r=mysql_query($sql,$l)or die($sql); 
			
			$sql2 = "SELECT ajustarvalordeclarado FROM configuradorgeneral";
			$d = mysql_query($sql2,$l) or die("$sql2<br>error en linea ".__LINE__);
			$h = mysql_fetch_object($d);
			$ajuste = $h->ajustarvalordeclarado;
			
			$registros= array();
			$Monto=0;
		
		if (mysql_num_rows($r)>0){
			while ($f=mysql_fetch_object($r)){
				$Monto += $f->valordeclarado;
				if($Monto<=$ajuste){
					$f->sucursal=cambio_texto($f->sucursal);
					$f->remitente=cambio_texto($f->remitente);
					$f->destinatario=cambio_texto($f->destinatario);
					$registros[]=$f;	
				}
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}


?>