<?	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT cr.descripcion, d.tipo, d.sucursal FROM catalogoruta cr
	INNER JOIN catalogorutadetalle d ON cr.id = d.ruta
	WHERE cr.id = 1";
	$r = mysql_query($s,$l) or die($s);
	while($f = mysql_fetch_object($r)){
		if($_SESSION[IDSUCURSAL] != $f->sucursal){
			$destino = $f->sucursal;
			break;
		}
	}
	
	echo $destino;
?>
