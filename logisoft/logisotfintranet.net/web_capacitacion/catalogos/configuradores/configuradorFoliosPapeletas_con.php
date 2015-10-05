<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = "SELECT folioinicial as finicial, foliofinal as ffinal, cantidad, 'x' AS existe FROM configuradorfoliospapeletas";
		$r = mysql_query($s, $l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->finicial = str_pad($f->finicial,9,'0',STR_PAD_LEFT);
				$f->ffinal = str_pad($f->ffinal,9,'0',STR_PAD_LEFT);
				$registros[] = $f;
			}
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
		
	}else if($_GET[accion]==2){
		/*$s = "SELECT * FROM asignacionpapeletas";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$s = "SELECT IFNULL(MAX(foliofinal),0) + 1 as foliofinal FROM asignacionpapeletas";
			$sq = mysql_query($s, $l) or die($s); $fol = mysql_fetch_object($sq);
			$inicial = $fol->foliofinal;
			$final = ($inicial + $_GET[cantidad]) - 1;
		}else{
			$s = "SELECT folioinicial FROM configuradorfoliospapeletas";
			$sql = mysql_query($s, $l) or die($s); 
			$ol = mysql_fetch_object($sql);
			$inicial = $ol->folioinicial;			
			$final = ($inicial + $_GET[cantidad]) - 1;					
		}*/
		
		$s = "SELECT * FROM asignacionpapeletas 
		WHERE '".$_GET[finicial]."' BETWEEN folioinicial AND foliofinal";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die('folio inicial');
		}
		
		$s = "SELECT * FROM asignacionpapeletas 
		WHERE '".$_GET[ffinal]."' BETWEEN folioinicial AND foliofinal";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die('folio final');
		}
		
		$s = "SELECT * FROM asignacionpapeletas WHERE folioinicial BETWEEN '".$_GET[finicial]."' AND '".$_GET[ffinal]."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die('folio inicial entre');
		}
		
		$s = "SELECT * FROM asignacionpapeletas WHERE foliofinal BETWEEN '".$_GET[finicial]."' AND '".$_GET[ffinal]."'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die('folio final entre');
		}
		
		echo str_pad($_GET[finicial],9,'0',STR_PAD_LEFT).",".str_pad($_GET[ffinal],9,'0',STR_PAD_LEFT);
				
	}else if($_GET[accion]==3){
		$cant = $_GET[cantidad];
		while($_GET[cantidad]>0){
			$s = "SELECT id, folioinicial, foliofinal, restante FROM configuradorfoliospapeletas WHERE restante > 0";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0){
					$f = mysql_fetch_object($r);
				if($f->restante >= $_GET[cantidad]){
					$upd = mysql_query("UPDATE configuradorfoliospapeletas SET
					restante= restante - $_GET[cantidad] WHERE id=$f->id",$l);					
					$_GET[cantidad] = 0;
				}else{
					$upd = mysql_query("UPDATE configuradorfoliospapeletas SET restante = 0 WHERE id=$f->id",$l);					
					$_GET[cantidad] -= $f->restante;
				}
				
			}else{
				echo (($cant>$_GET[cantidad])?"ok":"")."faltaron ".$_GET[cantidad];				
				break;
			}			
		}
		if($_GET[cantidad]==0){
		$s = "INSERT INTO asignacionpapeletas (fechaasignacion,sucursal,folioinicial,foliofinal,cantidad,usuario,fecha)
				 VALUES
				  (CURRENT_DATE(),".$_GET[sucursal].",".$_GET[finicial].",".$_GET[ffinal].",".$cant.",
				  '".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";	
				$ins = mysql_query($s,$l) or die($s);
				$folio = mysql_insert_id();
			
		for($i=$_GET[finicial];$i<=$_GET[ffinal];$i++){
			$s = "INSERT INTO asignacionpapeletasrecdetalle
			(asignacion,sucursal,folios, usuario, fecha) VALUES
			(".$folio.",".$_GET[sucursal].",".$i.",
			'".$_SESSION[NOMBREUSUARIO]."',CURRENT_TIMESTAMP())";
			$d = mysql_query($s,$l) or die($s);
		}
		
			$s = "SELECT IFNULL(MAX(folioinicial),0) AS folioinicial, 
			IFNULL(MAX(foliofinal),0) AS foliofinal FROM asignacionpapeletas";
			$sq = mysql_query($s, $l) or die($s); $fol = mysql_fetch_object($sq);
			
			$fol->finicial = str_pad($fol->folioinicial,9,'0',STR_PAD_LEFT);
			$fol->ffinal = str_pad($fol->foliofinal,9,'0',STR_PAD_LEFT);
		
			echo "ok,".$fol->finicial.",".$fol->ffinal;
		}
	}else if($_GET[accion]==4){
		$s = "SELECT IFNULL(SUM(restante),0) AS cantidad FROM configuradorfoliospapeletas";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();	
		while($f = mysql_fetch_object($r)){
			$s = "SELECT IFNULL(MAX(folioinicial),0) AS folioinicial, 
			IFNULL(MAX(foliofinal),0) AS foliofinal FROM asignacionpapeletas";
			$sq = mysql_query($s, $l) or die($s); $fol = mysql_fetch_object($sq);
			
			$f->finicial = str_pad($fol->folioinicial,9,'0',STR_PAD_LEFT);
			$f->ffinal = str_pad($fol->foliofinal,9,'0',STR_PAD_LEFT);
			
			$registros[] = $f;
		}
		echo str_replace('null','""',json_encode($registros));
		
	}else if($_GET[accion]==5){
		$s = "SELECT folioinicial,foliofinal,cantidad, DATE_FORMAT(fechaasignacion,'%d/%m/%Y') AS fecha, 'x' AS x 
		FROM asignacionpapeletas WHERE sucursal=".$_GET[sucursal]." ORDER BY fechaasignacion ASC";
		$r = mysql_query($s,$l) or die($s);
		$registros = array();
		if(mysql_num_rows($r)>0){
			while($f = mysql_fetch_object($r)){
				$f->folioinicial = str_pad($f->folioinicial,9,'0',STR_PAD_LEFT);
				$f->foliofinal = str_pad($f->foliofinal,9,'0',STR_PAD_LEFT);
				$registros[] = $f;
			}			
			echo str_replace('null','""',json_encode($registros));
		}else{
			echo "no encontro";
		}
	}	
?>