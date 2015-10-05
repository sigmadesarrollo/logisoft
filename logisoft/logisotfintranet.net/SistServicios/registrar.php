<?php
	session_start();
	//include("verificar.php");
	$Datos = split("##",$_SESSION[DATOSUSUARIO]);	
	$sId = $Datos[0];
	$sNumEmpleado = $Datos[1];
	$sNombre = $Datos[2];
	$sAdmin = $Datos[3];
	$sSucursal = $Datos[4];
	$sPrefijo = $Datos[5];
	$sidSucursal = $Datos[6];
	require_once("Conectar.php");
	$link = conectarse();
	
	switch($_POST[accion]){
		case "Mant":
			$s = "SET AUTOCOMMIT=0;";
			$r = mysql_query($s, $link);
			$s = "BEGIN;";
			$r = mysql_query($s, $link);
			if(!empty($_POST[hFolio])){
				//UPDATE
				$sql = "UPDATE mantenimiento SET 
				IdSucursal = '".$sidSucursal."',
				Sucursal = '".utf8_decode(trim($_POST[eSucursal]))."', 
				Usuario = '".utf8_decode($sNombre)."', 
				Unidad = '".utf8_decode(trim($_POST[eUnidad]))."',
				Kilometraje = '".utf8_decode(trim($_POST[eKilometraje]))."', 
				Placas = '".utf8_decode(trim($_POST[ePlacas]))."',
				Servicios = '".utf8_decode(trim($_POST[eServicios]))."', 
				Costo = '".$_POST[eCosto]."',
				Proveedor = '".utf8_decode(trim($_POST[eProveedor]))."', 
				TiempoEntrega = '".$_POST[cbEntrega]."',
				Fecha = '".$_POST[eFecha]."',
				Hora = '".$_POST[eHora]."', 
				IdUsuario = ".$sId."
				WHERE Folio = '".$_POST[hFolio]."'";
				$r = mysql_query($sql,$link);
			}else{
				$Array = obtenerFolio("mantenimiento",$sidSucursal,$sPrefijo);
				//INSERT
				$sql = "INSERT INTO mantenimiento SET 
				Folio = '".$Array["Folio"]."', 
				IdSucursal = '".$sidSucursal."',
				Sucursal = '".utf8_decode(trim($_POST[eSucursal]))."', 
				Usuario = '".utf8_decode($sNombre)."',
				Unidad = '".utf8_decode(trim($_POST[eUnidad]))."',
				Kilometraje = '".utf8_decode(trim($_POST[eKilometraje]))."', 
				Placas = '".utf8_decode(trim($_POST[ePlacas]))."',
				Servicios = '".utf8_decode(trim($_POST[eServicios]))."', 
				Costo = '".$_POST[eCosto]."',
				Proveedor = '".utf8_decode(trim($_POST[eProveedor]))."', 
				TiempoEntrega = '".$_POST[cbEntrega]."',
				Fecha = '".$_POST[eFecha]."',
				Hora = '".$_POST[eHora]."', 
				Consecutivo = ".$Array["Consecutivo"].",
				IdUsuario = ".$sId."";
				$r = mysql_query($sql,$link);
			}
			if($r){
				$s = "COMMIT;";
				$resultado = mysql_query($s, $link);
				echo "ok##&&##".$Array["Folio"];
			}else{
				$s = "ROLLBACK;";
				$resultado = mysql_query($s, $link);
				echo "mal##&&##".$sql;
			}
		break;
		case "Fora":
			$s = "SET AUTOCOMMIT=0;";
			$r = mysql_query($s, $link);
			$s = "BEGIN;";
			$r = mysql_query($s, $link);
			if(!empty($_POST[hFolio])){
				//UPDATE
				$sql = "UPDATE foraneos SET 
				IdSucursal = '".$sidSucursal."',
				Sucursal = '".utf8_decode(trim($_POST[eSucursal]))."', 
				Usuario = '".utf8_decode($sNombre)."',
				Unidad = '".utf8_decode(trim($_POST[eUnidad]))."',
				Kilometraje = '".utf8_decode(trim($_POST[eKilometraje]))."', 
				Placas = '".utf8_decode(trim($_POST[ePlacas]))."',
				Servicios = '".utf8_decode(trim($_POST[eServicios]))."', 
				Costo = '".$_POST[eCosto]."',
				Proveedor = '".utf8_decode(trim($_POST[eProveedor]))."', 
				TiempoEntrega = '".$_POST[cbEntrega]."',
				FechaMod = CURRENT_TIMESTAMP, 
				IdUsuario = ".$sId."
				WHERE Folio = '".$_POST[hFolio]."'";
				$r = mysql_query($sql,$link);
			}else{
				$Array = obtenerFolio("foraneos",$sidSucursal,$sPrefijo);
				//INSERT
				$sql = "INSERT INTO foraneos SET 
				Folio = '".$Array["Folio"]."', 
				IdSucursal = '".$sidSucursal."',
				Sucursal = '".utf8_decode(trim($_POST[eSucursal]))."', 
				Usuario = '".utf8_decode($sNombre)."',
				Unidad = '".utf8_decode(trim($_POST[eUnidad]))."',
				Kilometraje = '".utf8_decode(trim($_POST[eKilometraje]))."', 
				Placas = '".utf8_decode(trim($_POST[ePlacas]))."',
				Servicios = '".utf8_decode(trim($_POST[eServicios]))."', 
				Costo = '".$_POST[eCosto]."',
				Proveedor = '".utf8_decode(trim($_POST[eProveedor]))."', 
				TiempoEntrega = '".$_POST[cbEntrega]."',
				FechaAlta = CURRENT_TIMESTAMP,
				FechaMod = CURRENT_TIMESTAMP,
				Consecutivo = ".$Array["Consecutivo"].",
				IdUsuario = ".$sId."";
				$r = mysql_query($sql,$link);
			}
			if($r){
				$s = "COMMIT;";
				$resultado = mysql_query($s, $link);
				echo "ok##&&##".$Array["Folio"];
			}else{
				$s = "ROLLBACK;";
				$resultado = mysql_query($s, $link);
				echo "mal##&&##".$sql;
			}
		break;
		case "Mob":
			$s = "SET AUTOCOMMIT=0;";
			$r = mysql_query($s, $link);
			$s = "BEGIN;";
			$r = mysql_query($s, $link);
			if(!empty($_POST[hFolio])){
				//UPDATE
				$sql = "UPDATE mobiliario SET 
				Sucursal = '".utf8_decode(trim($_POST[eSucursal]))."', 
				Usuario = '".utf8_decode($sNombre)."',
				Servicios = '".utf8_decode(trim($_POST[eServicios]))."', 
				Costo = '".$_POST[eCosto]."',
				Proveedor = '".utf8_decode(trim($_POST[eProveedor]))."',
				TiempoEntrega = '".$_POST[cbEntrega]."',
				Fecha = CURRENT_TIMESTAMP WHERE Folio = '".$_POST[hFolio]."'";
				$r = mysql_query($sql,$link);
			}else{
				$Array = obtenerFolio("mobiliario",$sidSucursal,$sPrefijo);
				//INSERT
				$sql = "INSERT INTO mobiliario SET 
				Folio = '".$Array["Folio"]."', 
				Sucursal = '".utf8_decode(trim($_POST[eSucursal]))."', 
				Usuario = '".utf8_decode($sNombre)."',
				Servicios = '".utf8_decode(trim($_POST[eServicios]))."', 
				Costo = '".$_POST[eCosto]."',
				Proveedor = '".utf8_decode(trim($_POST[eProveedor]))."', 
				TiempoEntrega = '".$_POST[cbEntrega]."',
				Fecha = CURRENT_TIMESTAMP";
				$r = mysql_query($sql,$link);
			}
			if($r){
				$s = "COMMIT;";
				$resultado = mysql_query($s, $link);
				echo "ok##&&##".$Array["Folio"];
			}else{
				$s = "ROLLBACK;";
				$resultado = mysql_query($s, $link);
				echo "mal##&&##".$sql;
			}
		break;
		case "Pap":
			$s = "SET AUTOCOMMIT=0;";
			$r = mysql_query($s, $link);
			$s = "BEGIN;";
			$r = mysql_query($s, $link);
			if(!empty($_POST[hFolio])){
				//UPDATE
				$sql = "UPDATE papeleria SET 
				Sucursal = '".utf8_decode(trim($_POST[eSucursal]))."', 
				Usuario = '".utf8_decode($sNombre)."',
				Pedido = '".utf8_decode(trim($_POST[ePedido]))."', 
				Costo = '".$_POST[eCosto]."',
				Proveedor = '".utf8_decode(trim($_POST[eProveedor]))."',
				Fecha = CURRENT_TIMESTAMP WHERE Folio = '".$_POST[hFolio]."'";
				$r = mysql_query($sql,$link);
			}else{
				$Array = obtenerFolio("mobiliario",$sidSucursal,$sPrefijo);
				//INSERT
				$sql = "INSERT INTO papeleria SET 
				Folio = '".$Array["Folio"]."', 
				Sucursal = '".utf8_decode(trim($_POST[eSucursal]))."', 
				Usuario = '".utf8_decode($sNombre)."',
				Pedido = '".utf8_decode(trim($_POST[ePedido]))."', 
				Costo = '".$_POST[eCosto]."',
				Proveedor = '".utf8_decode(trim($_POST[eProveedor]))."',
				Fecha = CURRENT_TIMESTAMP";
				$r = mysql_query($sql,$link);
			}
			if($r){
				$s = "COMMIT;";
				$resultado = mysql_query($s, $link);
				echo "ok##&&##".$Array["Folio"];
			}else{
				$s = "ROLLBACK;";
				$resultado = mysql_query($s, $link);
				echo "mal##&&##".$sql;
			}
		break;
		
		case "AutMant":
			$s = "SET AUTOCOMMIT=0;";
			$r = mysql_query($s, $link);
			$s = "BEGIN;";
			$r = mysql_query($s, $link);
			$sql = "UPDATE mantenimiento SET Autorizado = '".$_POST[Autoriza]."' 
			WHERE Folio = '".$_POST[hFolio]."'";
			$r = mysql_query($sql,$link);
			if($r){
				$s = "COMMIT;";
				$resultado = mysql_query($s, $link);
				$paso = "ok";
			}else{
				$s = "ROLLBACK;";
				$resultado = mysql_query($s, $link);
				$paso = "mal##&&##".$sql;
			}
			
			if($paso=="ok"){
				mail("papay0@hotmail.com","Ejemplo de envio de email","Ejemplo de envio de email de texto plano\n\nWebEstilo.\nhttp://www.webestilo.com/\n Manuales para desarrolladores web.\n","FROM: Pruebas <webmaster@hotmail.com>\n");
				echo "ok";
			}else{
				echo $paso;
			}
		   /*
		   $direccion=$_GET['direccion']; 
		   $tipo=$_GET['tipo']; 
			
		   if ($direccion!=""){ 
			   if ($tipo=="plano"){ 
				  // Envio en formato texto plano 
				   
				  mail($direccion,"Ejemplo de envio de email","Ejemplo de envio de email de texto plano\n\nWebEstilo.\nhttp://www.webestilo.com/\n Manuales para desarrolladores web.\n","FROM: Pruebas <webmaster@hotmail.com>\n"); 
			   } else { 
				  // Envio en formato HTML 
				  mail($direccion,"Ejemplo de envio de email","<html><head><title>WebEstilo. Manual de PHP</title></head><body>Ejemplo de envio de email de HTML<br><br>WebEstilo.<br>http://www.webestilo.com/<br> <u>Manuales</u> para <b>desarrolladores</b> web.</body></html>","Content-type: text/html\n", "FROM: Pruebas <webmaster@hotmail.com>\n"); 
			   }       
					echo "Se ha enviado un email a la direccion: ",$direccion," en formato <b>",$tipo,"</b>."; 
			}
*/
		break;
		
		case "AutFora":
			$s = "SET AUTOCOMMIT=0;";
			$r = mysql_query($s, $link);
			$s = "BEGIN;";
			$r = mysql_query($s, $link);
			$sql = "UPDATE foraneos SET Autorizado = '".$_POST[Autoriza]."' 
			WHERE Folio = '".$_POST[hFolio]."'";
			$r = mysql_query($sql,$link);
			if($r){
				$s = "COMMIT;";
				$resultado = mysql_query($s, $link);
				$paso = "ok";
			}else{
				$s = "ROLLBACK;";
				$resultado = mysql_query($s, $link);
				$paso = "mal##&&##".$sql;
			}
			
			if($paso=="ok"){
				mail("papay0@hotmail.com","Ejemplo de envio de email","Ejemplo de envio de email de texto plano\n\nWebEstilo.\nhttp://www.webestilo.com/\n Manuales para desarrolladores web.\n","FROM: Pruebas <webmaster@hotmail.com>\n");
				echo "ok";
			}else{
				echo $paso;
			}
			
		break;
		
		case "AutPap":
			$s = "SET AUTOCOMMIT=0;";
			$r = mysql_query($s, $link);
			$s = "BEGIN;";
			$r = mysql_query($s, $link);
			$sql = "UPDATE papeleria SET Autorizado = '".$_POST[Autoriza]."' 
			WHERE Folio = '".$_POST[hFolio]."'";
			$r = mysql_query($sql,$link);
			if($r){
				$s = "COMMIT;";
				$resultado = mysql_query($s, $link);
				$paso = "ok";
			}else{
				$s = "ROLLBACK;";
				$resultado = mysql_query($s, $link);
				$paso = "mal##&&##".$sql;
			}
			
			if($paso=="ok"){
				mail("papay0@hotmail.com","Ejemplo de envio de email","Ejemplo de envio de email de texto plano\n\nWebEstilo.\nhttp://www.webestilo.com/\n Manuales para desarrolladores web.\n","FROM: Pruebas <webmaster@hotmail.com>\n");
				echo "ok";
			}else{
				echo $paso;
			}
			
		break;
	}
?>