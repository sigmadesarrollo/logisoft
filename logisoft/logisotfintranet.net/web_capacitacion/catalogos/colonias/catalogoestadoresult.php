<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../../Conectar.php');	
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = "SELECT * FROM catalogoestado WHERE id=".$_GET[estado];
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->descripcion = cambio_texto($f->descripcion);
				$registros[] = $f;
			}			
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion]==2){
		$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogoestado",$l);
		$row=mysql_fetch_array($sql);
		echo $row[0];
	}
	
?>