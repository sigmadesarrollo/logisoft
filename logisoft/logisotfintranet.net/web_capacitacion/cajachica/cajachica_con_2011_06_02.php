<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');	

	if($_GET[accion]==1){//OBTENER DEPOSITOS CAJA CHICA
		$s = "SELECT * FROM depositoscajachica ORDER BY prefijosucursal";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){	
			while($f = mysql_fetch_object($r)){
				$f->sucursal = cambio_texto($f->prefijosucursal);
				$f->total = $f->totalcajachica;
				$f->idsucursal = $f->keysucursal;
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==2){//CAPTURA GASTOS CAJA CHICA
		$s = "select folio, keysucursal, prefijosucursal, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, tipogastoindex, tipogastodesc,
		tipopagoindex, tipopagodesc, keyunidad, 
		unidadnumeconomico, factura, DATE_FORMAT(fechafacturavale,'%d/%m/%Y') as fechafacturavale,
		keyproveedor, nombreproveedor, keyconcepto, descripcionconcepto, subtotal, iva, total,
		descripcion, autorizado, folioautorizacion, motivonoautorizacion, sustituir 
		from capturagastoscajachica where folio = ".$_GET[folio]." and keysucursal = ".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
			while($f = mysql_fetch_object($r)){
				$f->prefijosucursal = cambio_texto($f->prefijosucursal);
				$f->unidadnumeconomico = cambio_texto($f->unidadnumeconomico);
				$f->nombreproveedor = cambio_texto($f->nombreproveedor);
				$f->descripcionconcepto = cambio_texto($f->descripcionconcepto);
				$f->descripcion = cambio_texto($f->descripcion);
				$f->motivonoautorizacion = cambio_texto($f->motivonoautorizacion);
				$registros[] = $f;
			}
			
			echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==3){
		$s = "SELECT id, CONCAT_WS(' ', nombre, apellidopaterno, apellidomaterno) AS gerente
		FROM catalogoempleado WHERE puesto = 2 AND id=".$_GET[gerente];
		$r = mysql_query($s,$l) or die($s);
		$registro = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->gerente = cambio_texto($f->gerente);
				$registro[] = $f;
			}
			echo str_replace('null','""',json_encode($registro));
		}else{
			echo "no encontro";
		}
	}else if($_GET[accion]==4){
		$s = "SELECT id, nombre	FROM catalogoproveedor WHERE id=".$_GET[proveedor];
		$r = mysql_query($s,$l) or die($s);
		$registro = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->nombre = cambio_texto($f->nombre);
				$registro[] = $f;
			}
			echo str_replace('null','""',json_encode($registro));
		}else{
			echo "no encontro";
		}
	}

?>