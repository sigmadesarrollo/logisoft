<?	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT css.id as idservicio, css.descripcion AS servicio, cs.condicion, cs.costo, cs.limite, cs.porcada, cs.costoextra 
		FROM configuradorservicios cs 
		INNER JOIN catalogoservicio css ON cs.servicio=css.id
		ORDER BY css.descripcion";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->servicio = cambio_texto($f->servicio);
				$arr[] = $f;
			}
			
			echo str_replace('null','""',json_encode($arr));
			
		}else{
			echo "no encontro";
		}
	}
	
?>