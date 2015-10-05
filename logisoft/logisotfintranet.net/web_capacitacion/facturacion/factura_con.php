<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT 0 as seleccion, folio as id,tipoguia,DATE_FORMAT(fecha,'%d/%m/%Y') AS fecha,
		flete as tflete, cantidaddescuento ttotaldescuento,excedente as texcedente,costoead as tcostoead,costorecoleccion as trecoleccion, costoseguro as tseguro,
		costocombustible as tcombustible,otros as totros,subtotal as subtotal,iva as tiva,ivaretenido,total,tipo FROM facturadetalle
		WHERE factura = ".$_GET[factura]."";
		$registros = array();
		$detalle = "";
		$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
		$detalle = str_replace('null','""',json_encode($registros));
		
		$s = "SELECT  0 as seleccion, guia id,date_format(fechaguia, '%d/%m/%Y') fecha,tipoguia,concepto,tseguro,texcedente,
		subtotal,tiva,ivaretenido,total FROM facturadetalleguias
		WHERE factura = ".$_GET[factura]."";
		$registros = array();
		$detalle2 = "";
		$r = mysql_query($s,$l) or die($s);
			while($f = mysql_fetch_object($r)){
				$f->concepto = cambio_texto($f->concepto);
				$registros[] = $f;
			}
		$detalle2 = str_replace('null','""',json_encode($registros));
		
		echo "({detalle:$detalle,detalle2:$detalle2})";
	}

?>