<?	session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$paginado = 30;
	$contador = ($_GET[contador]!="")?$_GET[contador]:0;
	$desde	  = ($paginado*$contador);
	$limite = " limit $desde, $paginado ";
	
	function f_adelante($vdesde,$vpaginado,$total){
		if($vdesde+$vpaginado>($total-1))
			return false;
		else
			return true;
	}
	function f_atras($vdesde){
		if($vdesde==0)
			return false;
		else
			return true;
	}
	function f_paginado($vpaginado,$vtotal){
		if($vpaginado>=$vtotal)
			return false;
		else
			return true;
	}
	
	if($_GET[accion]==1){
		$s = "select prefijo from catalogosucursal where id = '$_SESSION[IDSUCURSAL]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		$prefijo = $f->prefijo;
		
		$s = "SELECT be.id
		FROM bitacoraempleado be 
		WHERE DATE(be.fechahoraregistro) = CURRENT_DATE AND sucursal = '$prefijo'";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
	
		$s = "SELECT date_format(be.fecha,'%d/%m/%Y') fecha , ca.actividad, 
		be.idcliente, be.cliente, be.logro, be.tiempoinvertido, date_format(be.proximacita,'%d/%m/%Y') proximacita, be.sucursal,
		be.convenio
		FROM bitacoraempleado be 
		INNER JOIN catalogoactividades ca ON be.actividad = ca.id
		WHERE DATE(be.fechahoraregistro) = CURRENT_DATE AND sucursal = '$prefijo'";
		$r = mysql_query($s,$l) or die($s);
		$arr = array();
		while($f = mysql_fetch_object($r)){
			$f->solicita = cambio_texto($f->solicita);
			$f->acepta = cambio_texto($f->acepta);
			$f->guia = cambio_texto($f->guia);
			$f->cliente = cambio_texto($f->cliente);
			$arr[] = $f;
		}
		$registros = str_replace('null','""',json_encode($arr));
		
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
		
	}else if($_POST[accion]==2){
		$s = "INSERT INTO bitacoraempleado
		SET usuario = '$_SESSION[IDUSUARIO]',
		fecha = '".cambiaf_a_mysql($_POST[fecha])."',
		actividad = '$_POST[actividades]',
		idcliente = '$_POST[idcliente]',
		cliente = '$_POST[nombre]',
		convenio = '$_POST[folio]',
		logro = '$_POST[logro]',
		tiempoinvertido = '$_POST[tiempoinvertido]',
		proximacita = '".cambiaf_a_mysql($_POST[fecha])."',
		sucursal = (SELECT prefijo FROM catalogosucursal WHERE id = '$_SESSION[IDSUCURSAL]');";
		mysql_query($s,$l) or die($s);
		
		echo "muybien";
	}
?>
