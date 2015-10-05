<?	session_start();
	
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	if($_GET['accion']==1){//OBTENER BITACORA SALIDA
		$s = "SELECT 7 AS idconcepto, 'Prestamo' concepto, total cantidad
		FROM capturagastoscajachica
		WHERE unidadnumeconomico = $_GET[folio] AND tipogastoindex = 5";	
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		$f = mysql_fetch_object($r);
		
		echo "[".json_encode($f)."]";
	}

?>