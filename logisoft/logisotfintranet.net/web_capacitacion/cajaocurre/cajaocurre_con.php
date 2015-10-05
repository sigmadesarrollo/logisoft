<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_GET[accion] == 1){
		$principal = "";
		$s = "SELECT IFNULL(SUM(efectivo),0) AS efectivo,
		IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(transferencia),0) AS transferencia,
		IFNULL(SUM(cheque),0) AS cheque FROM entregasocurre WHERE idusuario=".$_SESSION[IDUSUARIO]."
		AND fecha='".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		$principal = str_replace('null','""',json_encode($f));
		
		$iniciocaja = "";
		$s = "SELECT id FROM iniciocajaocurre
		WHERE usuariocaja='".$_SESSION[IDUSUARIO]."' AND fechainiciocaja='".cambiaf_a_mysql($_GET[fecha])."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);		
		
		$iniciocaja = str_replace('null','""',json_encode($f->id));
		
		echo "({principal:$principal,iniciocaja:$iniciocaja})";
		
	}else if($_GET[accion] == 2){
		/*if($_GET[tipo] == "parcial"){
	
		$s = "INSERT INTO cierrecajaocurre SET
		iniciocaja = '".$_GET[iniciocaja]."', fechacierre = '".cambiaf_a_mysql($_GET[fecha])."', 
		usuariocaja = ".$_SESSION[IDUSUARIO].", efectivo = ".$_GET[efectivo].", 
		tarjeta = ".$_GET[tarjeta].", transferencia = ".$_GET[transferencia].", cheque = ".$_GET[cheque].",
		tipocierre = '".$_GET[tipo]."', sucursal = ".$_SESSION[IDSUCURSAL].", idusuario = ".$_SESSION[IDUSUARIO].",
		fecha = current_timestamp()";
		mysql_query($s,$l) or die($s);
		
		$codigo = mysql_insert_id();
		
		echo "ok,".$codigo;

		}else if($_GET[tipo] == "definitivo"){
			$s = mysql_query("SELECT id, tipocierre FROM cierrecajaocurre
			WHERE usuariocaja='".$_SESSION[IDUSUARIO]."' AND fechacierre='".cambiaf_a_mysql($_GET[fecha])."'",$link);
			$row = mysql_fetch_array($s);

			if($row[1]=="parcial"){
				$s = "UPDATE cierrecajaocurre SET
				iniciocaja = '".$_GET[iniciocaja]."', fechacierre = '".cambiaf_a_mysql($_GET[fecha])."', 
				usuariocaja = ".$_SESSION[IDUSUARIO].", efectivo = ".$_GET[efectivo].", 
				tarjeta = ".$_GET[tarjeta].", transferencia = ".$_GET[transferencia].", cheque = ".$_GET[cheque].",
				tipocierre = '".$_GET[tipo]."', sucursal = ".$_SESSION[IDSUCURSAL].", idusuario = ".$_SESSION[IDUSUARIO].",
				fecha = current_timestamp() WHERE id='".$row[0]."'";
				mysql_query($s,$l) or die($s);
		
			}else{	*/	
				$s = "INSERT INTO cierrecajaocurre SET
				iniciocaja = '".$_GET[iniciocaja]."', fechacierre = '".cambiaf_a_mysql($_GET[fecha])."', 
				usuariocaja = ".$_SESSION[IDUSUARIO].", efectivo = ".$_GET[efectivo].", 
				tarjeta = ".$_GET[tarjeta].", transferencia = ".$_GET[transferencia].", cheque = ".$_GET[cheque].",
				tipocierre = '".$_GET[tipo]."', sucursal = ".$_SESSION[IDSUCURSAL].", idusuario = ".$_SESSION[IDUSUARIO].",
				fecha = current_timestamp()";
				mysql_query($s,$l) or die($s);
		
				$codigo = mysql_insert_id();
			//}		
			echo "ok,SI,definitivo,".$codigo;
		//}
		
	}else if($_GET[accion] == 3){
		$principal = "";
		$s = "SELECT tipocierre FROM cierrecajaocurre WHERE fechacierre='".cambiaf_a_mysql($_GET['fechacierrecaja'])."'
		AND usuariocaja='".$_SESSION[IDUSUARIO]."'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		
		if(mysql_num_rows($r)>0){
			$principal = str_replace('null','""',json_encode($f));
			echo "({principal:$principal})";
		}else{
			echo "no encontro";
		}
		
	}

?>