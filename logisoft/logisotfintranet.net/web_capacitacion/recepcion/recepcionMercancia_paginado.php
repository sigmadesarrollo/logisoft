<?
	session_start();
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
		/*total de registros*/
		$s = "SELECT registro,paquete,codigobarra,estado,guia 
		FROM recepcion_tmp WHERE guia='$_GET[folio]' AND subido='N' 
		AND idusuario = $_SESSION[IDUSUARIO] AND sucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = 0;
		
		/*registros*/
		$s = "SELECT registro,paquete,codigobarra,estado,guia 
		FROM recepcion_tmp WHERE guia='$_GET[folio]' AND subido='N' 
		AND idusuario = $_SESSION[IDUSUARIO] AND sucursal=".$_SESSION[IDSUCURSAL]."
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$ar[] = $f;
		}
		$registros = json_encode($ar);
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	}
	
	if($_GET[accion]==2){
		/*total de registros*/
		$s = "SELECT registro,paquete,codigobarra,estado,guia 
		FROM recepcion_tmp WHERE guia='$_GET[folio]' AND subido='S' 
		AND idusuario = $_SESSION[IDUSUARIO] AND sucursal=".$_SESSION[IDSUCURSAL]."";
		$r = mysql_query($s,$l) or die($s);
		$totalregistros = mysql_num_rows($r);
		
		/*totales de los registros*/
		$totales = 0;
		
		/*registros*/
		$s = "SELECT registro,paquete,codigobarra,estado,guia 
		FROM recepcion_tmp WHERE guia='$_GET[folio]' AND subido='S' 
		AND idusuario = $_SESSION[IDUSUARIO] AND sucursal=".$_SESSION[IDSUCURSAL]."
		$limite";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		while($f = mysql_fetch_object($r)){
			$ar[] = $f;
		}
		$registros = json_encode($ar);
		echo '({"total":"'.$totalregistros.'",
		"totales":'.$totales.',
		"registros":'.$registros.',
		"contador":"'.$contador.'",
		"adelante":"'.f_adelante($desde,$paginado,$totalregistros).'",
		"atras":"'.f_atras($contador).'",
		"paginado":"'.f_paginado($paginado,$totalregistros).'"})';
	}
	
	

?>