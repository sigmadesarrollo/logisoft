<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');	
	$l = Conectarse('webpmm');
	
	if($_GET[accion] == 1){
		$s = "DELETE FROM preliquidaciondebitacoradetalle 
		WHERE preliquidacion = 0 AND idusuario = ".$_SESSION[IDUSUARIO]."";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS recibio FROM catalogoempleado 
		WHERE id = ".$_SESSION[IDUSUARIO]."";
		$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
		$f->recibio = cambio_texto($f->recibio);
		
		$s = "SELECT obtenerFolio('preliquidaciondebitacora',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);		
		echo $fo->folio.",".date('d/m/Y').",".$f->recibio;
		
	}else if($_GET[accion] == 2){		
		$s = mysql_query("insert into preliquidaciondebitacora 
		(folio,foliobitacora, afavorencontra, cantidad,entrego,recibio,usuario,fecha,sucursal)
		values
		(obtenerFolio('preliquidaciondebitacora',".$_SESSION[IDSUCURSAL]."),'".$_GET['foliobitacora']."',
		'".$_GET[afavor]."', '".$_GET[cantidad]."','".$_GET[entrego]."','".$_GET[recibio]."',
		'".$_SESSION[NOMBREUSUARIO]."',CURRENT_DATE,".$_SESSION[IDSUCURSAL].")",$l) or die($s);
		$folio = mysql_insert_id();
		
		$s = "SELECT folio FROM preliquidaciondebitacora WHERE id = ".$folio;
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);		
		$folio = $fo->folio;
		
		$s = mysql_query("UPDATE bitacorasalida SET preliquidaciondebitacora='1' 
		WHERE folio='".$_GET['foliobitacora']."'",$l)or die($s);		
		
		$foliosprecintos = "'".str_replace(",","','",$_GET[precintos])."'";
		
		if(!empty($_GET[precintos])){
			$s = "UPDATE asignacionprecintosdetalle SET utilizado=0
			WHERE folios IN(".$foliosprecintos.")";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM recepcionregistroprecintosdetalle_tmp WHERE precinto IN(".$foliosprecintos.")";
			mysql_query($s,$l) or die($s);
			
			$s = "UPDATE preliquidaciondebitacoradetalle SET devuelto=1
			WHERE precinto IN(".$foliosprecintos.") AND idusuario = ".$_SESSION[IDUSUARIO]."";
			mysql_query($s,$l) or die($s);
		}
		
		$nobajap = "'".str_replace(",","','",$_GET[nobaja])."'";
		
		if(!empty($_GET[nobaja])){
			$s = "DELETE FROM preliquidaciondebitacoradetalle WHERE precinto IN(".$nobajap.")";
			mysql_query($s,$l) or die($s);
		}
		
		$s = "UPDATE preliquidaciondebitacoradetalle SET preliquidacion = ".$folio." 
		WHERE idusuario = ".$_SESSION[IDUSUARIO]." AND preliquidacion = 0";
		mysql_query($s,$l) or die($s);
		
		echo "ok,grabar,".$folio;
	
	}else if($_GET[accion] == 3){	
		$s = "UPDATE preliquidaciondebitacora SET 
		foliobitacora='".$_GET['foliobitacora']."', 
		afavorencontra='".$_GET[r]."',cantidad='".$_GET[cantidad]."',entrego='".$_GET[entrego]."',
		recibio='".$_GET[recibio]."',usuario='".$_SESSION[NOMBREUSUARIO]."', fecha='".cambiaf_a_mysql($_GET[fecha])."' 
		WHERE folio='".$_GET['folio']."' AND sucursal = ".$_SESSION[IDSUCURSAL]."";
		mysql_query($s,$l) or die($s);
		
		$s = mysql_query("UPDATE bitacorasalida SET preliquidaciondebitacora='1' 
		WHERE folio='".$_GET['foliobitacora']."'",$l)or die($s);		
		
		echo "ok,modificar";
		
	}else if($_GET[accion] == 4){	
		$s = "INSERT INTO preliquidaciondebitacoradetalle
		SELECT 0 AS id, 0 as preliquidacion, ".$_SESSION[IDSUCURSAL].", precinto, fechaasignado, 0, ".$_SESSION[IDUSUARIO].",
		CURRENT_TIMESTAMP FROM recepcionregistroprecintosdetalle
		WHERE foliobitacora = ".$_GET[foliobitacora]." and tipo = 'bitacora'";
		mysql_query($s,$l) or die($s);
		
		$s = "SELECT 1 AS sel, precinto, date_format(fechaasignado,'%d/%m/%Y') as fechaasignado
		FROM preliquidaciondebitacoradetalle WHERE idusuario = ".$_SESSION[IDUSUARIO]."";
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
	
	}else if($_GET[accion] == 5){
		$s = "SELECT 0 as sel,precinto, DATE_FORMAT(fechaasignado,'%d/%m/%Y') AS fechaasignado 
		FROM preliquidaciondebitacoradetalle 
		WHERE preliquidacion = ".$_GET[folio]." AND sucursal = ".$_SESSION[IDSUCURSAL];
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
