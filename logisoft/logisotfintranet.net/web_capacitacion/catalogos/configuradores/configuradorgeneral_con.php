<?	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion]==1){
		$s = "SELECT DATE_FORMAT(dia,'%d/%m/%Y') AS dia FROM configuradorgeneraldias";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}		
	}
?>