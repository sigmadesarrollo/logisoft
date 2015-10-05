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
		$s = "SELECT * FROM historialdetraspaso		
		WHERE aceptada = 0 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursalacepta = ".$_SESSION[IDSUCURSAL]."":"")."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);		
		
		$totales = 0;
	
		$s = "SELECT 1 AS aceptar, h.foliotraspaso, ss.prefijo AS solicita, sa.prefijo AS acepta,
		DATE_FORMAT(h.fechasolicitud,'%d/%m/%Y') AS fechasolicitud, h.guia, h.cliente, h.importe
		FROM historialdetraspaso h
		INNER JOIN catalogosucursal ss ON h.sucursalsolicita = ss.id
		INNER JOIN catalogosucursal sa ON h.sucursalacepta = sa.id
		WHERE h.aceptada = 0 
		".(($_SESSION[IDSUCURSAL]!=1)? " AND h.sucursalacepta = ".$_SESSION[IDSUCURSAL]."":"")."";
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
		
	}else if($_GET[accion]==2){
		$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		$_GET[guias] = "'".str_replace(",","','",$_GET[guias])."'";
		$s = "UPDATE historialdetraspaso SET
		fechaaceptado = CURDATE(), aceptada = 1
		WHERE foliotraspaso IN (".$_GET[folios].")";
		mysql_query($s,$l) or die($s);
	
		$s = "UPDATE pagoguias SET sucursalacobrar=".$_SESSION[IDSUCURSAL]." 
		WHERE guia IN (".$_GET[guias].")";
		mysql_query($s,$l) or die($s);
		
		$guiasFolios = split(",",$_GET[guias]);
		foreach($guiasFolios as $folio){
			$s = "call proc_RegistroCobranza('CAMBIAR_CARGO', $folio,'', '', $_SESSION[IDSUCURSAL], 0)";
			mysql_query($s,$l) or die($s);
			
			$s = "call proc_RegistroAuditorias('LGT',$folio,$_SESSION[IDSUCURSAL])";
			mysql_query($s, $l) or die($s);
		}
		
		echo "ok";	
	}else if($_GET[accion]==3){
		$_GET[folios] = "'".str_replace(",","','",$_GET[folios])."'";
		$_GET[guias] = "'".str_replace(",","','",$_GET[guias])."'";
		$s = "UPDATE historialdetraspaso SET
		fechaaceptado = CURDATE(), aceptada = 2
		WHERE foliotraspaso IN (".$_GET[folios].")";
		mysql_query($s,$l) or die($s);
	
		echo "ok";	
	}
?>
