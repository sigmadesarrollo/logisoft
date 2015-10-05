<? 	session_start();
	
	require_once("../Conectar.php");
	require_once("../clases/ValidaConvenio.php");
	$l = Conectarse("webpmm");	
	
	//solicitar sucursales
	if($_GET[accion]==1){
		$s = "select cc.id, concat_ws(' ', cc.nombre, cc.paterno, cc.materno) as nombre
		from catalogocliente as cc where id = $_GET[idcliente]";
		$r = mysql_query($s,$l) or die("$s<br>error en linea ".__LINE__);
		$f = mysql_fetch_object($r);
		
		echo "(".json_encode($f).")";
	}
?>
