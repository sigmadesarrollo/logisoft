<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = "SELECT so.guia, so.paquete,  so.sucursal AS idsucursal, cs.descripcion AS sucursal, so.modulo, so.unidad, so.fecha
		FROM sobrantes so
		LEFT JOIN catalogosucursal cs ON so.sucursal = cs.id
		WHERE so.guia = '$_GET[folio]'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}		
		
		echo "(".str_replace("NULL","",json_encode($arre)).")";
	}
?>