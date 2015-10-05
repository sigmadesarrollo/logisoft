<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	if($_GET[accion] == 1){
		$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno) AS cliente
		FROM catalogocliente
		WHERE id=".$_GET[cliente]."";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if(mysql_num_rows($r)>0){
			echo "ok,".cambio_texto($f->cliente);
		}else{
			echo "0";
		}
	}
?>