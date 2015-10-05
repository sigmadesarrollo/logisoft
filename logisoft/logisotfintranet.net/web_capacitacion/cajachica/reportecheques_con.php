<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT ccc.id AS idpagocheque, ccc.nocuenta, ccc.nocheque, DATE_FORMAT(ccc.fecha, '%d-%m-%Y') AS fechacheque,
		ccc.gerente, 1 AS seleccion, catalogosucursal.prefijo
		FROM cajachica_cheques AS ccc
		INNER JOIN catalogosucursal ON ccc.sucursal = catalogosucursal.id
		WHERE ccc.capturado = 'N'";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$f->gerente = cambio_texto($f->gerente);
			$arre[] = $f;
		}
		echo str_replace("null",'""',json_encode($arre));
	}

	if($_GET[accion]==2){
		$s = "SELECT nocheque FROM cajachica_cheques WHERE capturado = 'N'";
		$r = mysql_query($s,$l) or die($s);
		$ids = "";
		while($f = mysql_fetch_object($r)){
			$ids .= ($ids!="")?(",".$f->nocheque):$f->nocheque;
		}
		echo $ids;
	}
	
	if($_GET[accion]==3){
		$s = "update cajachica_cheques set capturado = 'S' where nocheque in($_GET[ids])";
		mysql_query($s,$l) or die($s);
		
		echo "actualizo";
	}
?>