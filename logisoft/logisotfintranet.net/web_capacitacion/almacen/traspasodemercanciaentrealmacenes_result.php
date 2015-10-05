<?	session_start();
	include('../Conectar.php');
	$link=Conectarse('webpmm');

	if($_GET['accion']==1){// BUSCAR FOLIO GUIA ----traspasodemercanciaentrealmacenes.php
		$s = "(SELECT gv.id, gv.fecha FROM guiasventanilla gv
		INNER JOIN catalogodestino cd ON gv.iddestino = cd.id
		WHERE gv.estado='ALMACEN DESTINO' AND gv.ocurre='$_GET[tipo]'
		AND gv.idsucursaldestino='$_SESSION[IDSUCURSAL]' 
		AND gv.id='".$_GET[guia]."' AND cd.restringiread=0
		AND (gv.entradasalida = '' OR gv.entradasalida = 'ENTRADA' OR isnull(gv.entradasalida))) 
		UNION
		(SELECT ge.id, ge.fecha FROM guiasempresariales ge
		INNER JOIN catalogodestino cd ON ge.iddestino = cd.id 
		WHERE ge.estado='ALMACEN DESTINO' AND ge.ocurre='$_GET[tipo]' 
		AND ge.id='".$_GET[guia]."' AND cd.restringiread=0
		AND (ge.entradasalida = '' OR ge.entradasalida = 'ENTRADA' OR isnull(ge.entradasalida))
		AND ge.idsucursaldestino='$_SESSION[IDSUCURSAL]')";

		$r = mysql_query($s,$link) or die($s);
		if(mysql_num_rows($r)>0){
			echo "encontro";
		}else{
			echo "no encontro";
		}
	}

?>