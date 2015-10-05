<?	session_start();
	require_once("../Conectar.php");
	$l=Conectarse('webpmm');
	
	if ($_GET[accion]==1){
	
	$s = "SELECT codigo,sucursal,guias,valordeclarado,seguro,fecha FROM 
	(SELECT cs.id AS codigo,cs.Descripcion AS sucursal,gv.id AS guias,
	IFNULL(gv.valordeclarado,0) AS valordeclarado, 
	IFNULL(gv.valordeclarado * (SELECT IFNULL(prima / 100,0)AS prima FROM configuradorgeneral),0) AS seguro,
	gv.fecha FROM guiasempresariales gv
	INNER JOIN catalogosucursal cs ON cs.id = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)		
	WHERE gv.valordeclarado>=(SELECT cantidadvalordeclarado FROM configuradorgeneral)  
	AND gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
	".(($_GET[sucursal]!=1)? " AND cs.id='".$_GET[sucursal]."' ":"")." 
	UNION ALL
	SELECT cs.id AS codigo,cs.Descripcion AS sucursal,gv.id AS guias,IFNULL(gv.valordeclarado,0) AS valordeclarado,
	IFNULL(gv.valordeclarado * (SELECT IFNULL(prima / 100,0)AS prima FROM configuradorgeneral),0) AS seguro,gv.fecha
	FROM guiasventanilla gv
	INNER JOIN catalogosucursal cs ON cs.id   = IF (gv.tipoflete=0,gv.idsucursalorigen,gv.idsucursaldestino)		
	WHERE gv.valordeclarado >= (SELECT cantidadvalordeclarado FROM configuradorgeneral)  
	AND gv.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' AND '".cambiaf_a_mysql($_GET[fecha2])."' 
	".(($_GET[sucursal]!=1)? " AND cs.id='".$_GET[sucursal]."' ":"")." 
	)ValoresDeclarados 
	ORDER BY fecha";
	
	//die($s);
	$r = mysql_query($s,$l)or die($s); 
		
		$s = "SELECT ajustarvalordeclarado FROM configuradorgeneral";
		$d = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		$h = mysql_fetch_object($d);
		$ajuste = $h->ajustarvalordeclarado;
		
		$registros= array();
		$Monto=0;
		
		if (mysql_num_rows($r)>0){
			while ($f=mysql_fetch_object($r)){
			$Monto += $f->valordeclarado;
				if($Monto<=$ajuste){
					$f->sucursal=cambio_texto($f->sucursal);
					$registros[]=$f;	
				}
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion] == 2){
		$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion 
		FROM catalogosucursal WHERE id = ".$_GET[IDSUCURSAL];
		$r = mysql_query($s,$l) or die($s); 
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->descripcion);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}
	
?>