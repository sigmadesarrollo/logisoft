<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_POST[accion]==1){
		
		$s = "SELECT * 
		FROM configuracion_promociones 
		WHERE ('".cambiaf_a_mysql($_POST[inicio])."' BETWEEN desde AND hasta OR 
		'".cambiaf_a_mysql($_POST[fin])."' BETWEEN desde AND hasta)
		".(($_POST[sucursal_hidden]==0)?"":"
		AND (sucursal = '".$_POST[sucursal_hidden]."' OR sucursal = 0)");
		
		
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			die("Ya existe una promocion registrada con esas fechas");
		}
		
		$_POST[sucursal_hidden] = ($_POST[checktodas]=='on')?"0":$_POST[sucursal_hidden];
		
			$grec = 'NO';
			$gead = 'NO';
		if($_POST[tipoPromo]=='ead'){
			$gead = 'SI';
			$grec = 'NO';
		}elseif($_POST[tipoPromo]=='rec'){
			$grec = 'SI';
			$gead = 'NO';
		}
		
		$s = "INSERT INTO configuracion_promociones
		SET sucursal='$_POST[sucursal_hidden]',
		tipo='$_POST[tipoguia]',
		desde='".cambiaf_a_mysql($_POST[inicio])."',
		hasta='".cambiaf_a_mysql($_POST[fin])."',
		gratisead='$gead',
		gratisrec='$grec',
		valpeso='$_POST[peso]',
		descuento='$_POST[descuento]'";
		mysql_query($s,$l) or die($s);
		
		echo "datosGuardados";
	}
	
	if($_POST[accion]==2){
		
		if($_POST[ano]!=""){
			$ano_cc = $_POST[ano];
			$anoactual = $_POST[ano];
		}else{
			$ano_cc = " YEAR(CURRENT_DATE)";
			$anoactual = date("Y");
		}
		
		$s = "SELECT ano FROM (
		SELECT YEAR(desde) ano
		FROM configuracion_promociones
		) t1
		GROUP BY ano";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		$ano = array();
		if(mysql_num_rows($r)>0){
			while($f=mysql_fetch_object($r)){
				$ano[] = $f->ano;
			}
			$anos = json_encode($ano);
		}else{
			$anos = date("Y");
		}
		
		$anoactual = date("Y");
		
		$s = "SELECT cp.*, date_format(cp.desde, '%d/%m/%Y') desde, date_format(cp.desde, '%d/%m/%Y') hasta,
		if(cp.sucursal=0,'TODAS',cc.prefijo) sucursal
		FROM configuracion_promociones cp 
		LEFT JOIN catalogosucursal cc on cp.sucursal = cc.id
		WHERE YEAR(desde) = $ano_cc 
		order by cp.desde desc";
		$r = mysql_query($s,$l) or die($s);
		$arre = array();
		while($f = mysql_fetch_object($r)){
			$arre[] = $f;
		}
		$filas = json_encode($arre);
		
		echo "({
			'anos':$anos,
			'anoactual':$anoactual,
			'filas':$filas
		})";
	}
	
	if($_POST[accion]==3){
		$s = "delete from configuracion_promociones where id = '$_POST[idpromociones]'";
		mysql_query($s,$l) or die($s);
		
		echo "borradoExistoso";
	}
	
?>
