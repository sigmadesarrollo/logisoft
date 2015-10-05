<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion]==1){
		$s = folio('catalogotipoajuste','webpmm');
		echo $s[0];
		
	}else if($_GET[accion]==2){
		if($_GET[tipo] == "grabar"){
			$s = "INSERT INTO catalogotipoajuste SET
			descripcion = UCASE('".$_GET[descripcion]."'),
			positivo	= ".$_GET[positivo].",
			idusuario	= ".$_SESSION[IDUSUARIO].",
			fecha		= CURRENT_TIMESTAMP";
			mysql_query($s,$l) or die($s);
			$id = mysql_insert_id();
			
			echo "ok,grabar,".$id;
			
		}else{
		
			$s = "UPDATE catalogotipoajuste SET
			descripcion = UCASE('".$_GET[descripcion]."'),
			positivo	= ".$_GET[positivo].",
			idusuario	= ".$_SESSION[IDUSUARIO].",
			fecha		= CURRENT_TIMESTAMP
			WHERE id	= ".$_GET[ajuste]."";
			mysql_query($s,$l) or die($s);
			
			echo "ok,modificar";
		}
		
	}else if($_GET[accion]==3){
		$s = "SELECT descripcion, positivo FROM catalogotipoajuste WHERE id = ".$_GET[ajuste]."";
		$r = mysql_query($s,$l) or die($s);		
		$f = mysql_fetch_object($r);
		$f->descripcion = cambio_texto($f->descripcion);
		
		echo "(".str_replace('null','""',json_encode($f)).")";
	}
?>