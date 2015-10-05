<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once("../Conectar.php");
	$link = Conectarse("webpmm");	
	$fecha = $_GET['fecha'];;
	$f=cambiaf_a_mysql($fecha);	
	if($_GET['accion']==1){// VALIDAR INICIO DIA
		$s = "SELECT * FROM iniciodia WHERE fechainiciodia='".$f."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml .= "<existe>SI</existe>";
			$xml .= "<encontro>$cant</encontro>";
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==2){// OBTENER FONDO CAJA
		$s = "SELECT cajachica FROM catalogosucursal WHERE id='".$_GET['sucursal']."'";	
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml .= "<fondocaja>".$f->cajachica."</fondocaja>";
			$xml .= "<encontro>$cant</encontro>";
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==3){// VALIDAR INICIO CAJA
		$s = "SELECT 
		CASE (SELECT COUNT(id) FROM iniciocajaabonocliente WHERE usuariocaja='".$_GET['idusuario']."')
		WHEN 0 THEN 1 ELSE (SELECT COUNT(id) FROM cierrecajaabonocliente WHERE
		iniciocaja=(SELECT MAX(id) FROM iniciocajaabonocliente) AND usuariocaja='".$_GET['idusuario']."')
		END AS validar";
		
		$sql = "SELECT * FROM iniciocajaabonocliente 
		WHERE usuariocaja='".$_GET['idusuario']."' AND fechainiciocaja='".$f."'";		
		$t = mysql_query($sql,$link) or die($sql);
		
		if(mysql_num_rows($t)>0){
			$existe = 1;
		}else{
			$existe = 0;
		}
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml .= "<validar>".$f->validar."</validar>";
			$xml .= "<existe>".$existe."</existe>";
			$xml .= "<encontro>$cant</encontro>";
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==4){// VALIDAR INICIO DIA ANTERIOR
		$s = "SELECT IFNULL(MAX(i.id),0) AS iniciodia FROM iniciodia i
		INNER JOIN cierredia c ON i.id=c.iniciodia
		WHERE i.idusuario='".$_GET['idusuario']."' 
		AND c.iniciodia=(SELECT iniciodia FROM cierredia WHERE iniciodia=i.id AND idusuario='".$_GET['idusuario']."')";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml .= "<iniciodia>".$f->iniciodia."</iniciodia>";
			$xml .= "<encontro>$cant</encontro>";
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==5){// VALIDAR INICIO DIA ANTERIOR
		$s = "SELECT 
		CASE(SELECT COUNT(id) FROM iniciodia WHERE idusuario='".$_GET['idusuario']."')
		WHEN 0 THEN 1 ELSE (SELECT COUNT(id) FROM cierredia WHERE
		iniciodia=(SELECT MAX(id) FROM iniciodia) AND idusuario='".$_GET['idusuario']."')
		END AS validar";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml .= "<iniciodia>".$f->iniciodia."</iniciodia>";
			$xml .= "<encontro>$cant</encontro>";
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==6){// OBTENER DIA INHABIL
		$fu = cambiaf_a_mysql($_GET['dia']);	
		$s = "SELECT inhabilitados FROM configuradorgeneral";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
	$day = mysql_query("SELECT DAYOFWEEK('".$fu."') AS dia",$link);
		$rowDay = mysql_fetch_array($day);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml .= "<inhabilitados>".$f->inhabilitados."</inhabilitados>";
			$xml .= "<dia>".$rowDay[0]."</dia>";
			$xml .= "<encontro>$cant</encontro>";
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}else if($_GET['accion']==7){// OBTENER CAJA PARCIAL	
		$s = "SELECT tipocierre FROM cierrecajaabonocliente WHERE fechacierre='".cambiaf_a_mysql($_GET['fechacierrecaja'])."'
		AND usuariocaja='".$_SESSION[IDUSUARIO]."'";
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>";
			$xml .= "<tipocierre>".$f->tipocierre."</tipocierre>";			
			$xml .= "<encontro>$cant</encontro>";
			$xml .= "</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	}
?>


