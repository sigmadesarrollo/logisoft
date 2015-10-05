<? session_start();
	require_once("../../Conectar.php");
	$l=Conectarse('webpmm');
	
	if ($_GET[accion]==1){
$sql="SELECT IFNULL(cs.id,'') AS sucursal,cs.prefijo AS nombresucursal ,IFNULL(SUM(fp.efectivo),0)AS efectivo,
	IFNULL(ck.cheques,0) AS cheques,IFNULL(cko.otros,0) AS otros ,
	IFNULL(SUM(fp.transferencia),0)AS transferencia,IFNULL(SUM(fp.tarjeta),0)AS tarjeta,IFNULL(SUM(fp.notacredito),0) AS nc,(IFNULL(SUM(fp.efectivo),0)+
	IFNULL(ck.cheques,0)+IFNULL(cko.otros,0)+
	IFNULL(SUM(fp.transferencia),0)+IFNULL(SUM(fp.tarjeta),0)+IFNULL(SUM(fp.notacredito),0)) as total FROM formapago fp
	INNER JOIN catalogosucursal cs ON cs.id=fp.sucursal
	LEFT JOIN
	(SELECT IFNULL(fp.sucursal,'') AS sucursal,IFNULL(SUM(fp.cheque),0)AS cheques FROM formapago fp 
		INNER JOIN catalogobanco cb ON fp.banco=cb.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND cb.PorDefault=1 and isnull(fp.fechacancelacion) GROUP BY fp.sucursal)ck ON cs.id=ck.sucursal
	LEFT JOIN
	(SELECT IFNULL(fp.sucursal,'') AS sucursal,IFNULL(SUM(fp.cheque),0)AS otros FROM formapago fp 
		INNER JOIN catalogobanco cb ON fp.banco=cb.id
		WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
		AND cb.PorDefault<>1 and isnull(fp.fechacancelacion) GROUP BY fp.sucursal)cko ON cs.id=cko.sucursal
	WHERE fp.fecha BETWEEN '" .cambiaf_a_mysql($_GET[fecha])."' and '".cambiaf_a_mysql($_GET[fecha2])."' 
	 and isnull(fp.fechacancelacion)GROUP BY cs.id";
		$r=mysql_query($sql,$l)or die($sql); 
		$registros= array();
		
		if (mysql_num_rows($r)>0){
				while ($f=mysql_fetch_object($r)){
				$f->nombresucursal=cambio_texto($f->nombresucursal);
				$registros[]=$f;	
				}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo str_replace('null','""',json_encode(0));
		}
}
	
?>