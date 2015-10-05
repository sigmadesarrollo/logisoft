<?php
	//include("verificar");
	session_start();
	$Datos = split("##",$_SESSION[DATOSUSUARIO]);	
	$sId = $Datos[0]; $sNumEmpleado = $Datos[1]; $sNombre = $Datos[2]; $sAdmin = $Datos[3]; 
	$sSucursal = $Datos[4]; $sPrefijo = $Datos[5]; $sidSucursal = $Datos[6];
	
	require_once("Conectar.php");
	$link = conectarse();
	
	switch($_POST[accion]){
		case "Mant":
			$s = "SELECT Folio, IdSucursal, Sucursal, Usuario, Unidad, Kilometraje, Placas,
			Servicios, Costo, Proveedor, TiempoEntrega, Fecha, Hora, Autorizado,
			Consecutivo, IdUsuario FROM mantenimiento WHERE Folio = '".$_POST[eFolio]."'";
			$r = mysql_query($s,$link) or die(mysql_error($link)." ".$s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$f->Sucursal = utf8_encode($f->Sucursal);
				$f->Usuario = utf8_encode($f->Usuario);
				$f->Unidad = utf8_encode($f->Unidad);
				$f->Kilometraje = utf8_encode($f->Kilometraje);
				$f->Placas = utf8_encode($f->Placas);
				$f->Servicios = utf8_encode($f->Servicios);
				$f->Costo = utf8_encode($f->Costo);
				$f->Proveedor = utf8_encode($f->Proveedor);
				mysql_free_result($r);//LIBERAMOS MEMORIA
				echo "(".json_encode($f).")";
			}else{
				echo "noencontro";	
			}
		break;
		case "Fora":
			$s = "SELECT Folio, IdSucursal, Sucursal, Usuario, Unidad, Kilometraje, Placas,
			Servicios, Costo, Proveedor, TiempoEntrega, Fecha, Hora, Autorizado,
			Consecutivo, IdUsuario FROM foraneos WHERE Folio = '".$_POST[eFolio]."'";
			$r = mysql_query($s,$link) or die(mysql_error($link)." ".$s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$f->Sucursal = utf8_encode($f->Sucursal);
				$f->Usuario = utf8_encode($f->Usuario);
				$f->Unidad = utf8_encode($f->Unidad);
				$f->Kilometraje = utf8_encode($f->Kilometraje);
				$f->Placas = utf8_encode($f->Placas);
				$f->Servicios = utf8_encode($f->Servicios);
				$f->Costo = utf8_encode($f->Costo);
				$f->Proveedor = utf8_encode($f->Proveedor);
				mysql_free_result($r);//LIBERAMOS MEMORIA
				echo "(".json_encode($f).")";
			}else{
				echo "noencontro";
			}
		break;
		case "Mob":
			$s = "SELECT Folio,IdSucursal,Sucursal,Usuario,Servicios,Costo,Proveedor,
			TiempoEntrega,Fecha,Hora,Autorizado,Consecutivo,IdUsuario FROM mobiliario 
			WHERE Folio = '".$_POST[eFolio]."'";
			$r = mysql_query($s,$link) or die(mysql_error($link)." ".$s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$f->Sucursal = utf8_encode($f->Sucursal);
				$f->Usuario = utf8_encode($f->Usuario);				
				$f->Servicios = utf8_encode($f->Servicios);
				$f->Costo = utf8_encode($f->Costo);
				$f->Proveedor = utf8_encode($f->Proveedor);
				mysql_free_result($r);//LIBERAMOS MEMORIA
				echo "(".json_encode($f).")";
			}else{
				echo "noencontro";	
			}
		break;
		case "Pape":
			$s = "SELECT Folio,IdSucursal,Sucursal,Usuario,Pedido,Costo,Proveedor,
			TiempoEntrega,Fecha,Hora,Autorizado,Consecutivo,IdUsuario FROM papeleria 
			WHERE Folio = '".$_POST[eFolio]."'";
			$r = mysql_query($s,$link) or die(mysql_error($link)." ".$s);
			if(mysql_num_rows($r)>0){
				$f = mysql_fetch_object($r);
				$f->Sucursal = utf8_encode($f->Sucursal);
				$f->Usuario = utf8_encode($f->Usuario);				
				$f->Pedido = utf8_encode($f->Pedido);
				$f->Costo = utf8_encode($f->Costo);
				$f->Proveedor = utf8_encode($f->Proveedor);
				mysql_free_result($r);//LIBERAMOS MEMORIA
				echo "(".json_encode($f).")";
			}else{
				echo "noencontro";	
			}
		break;
		default:
			$Array = obtenerFolio($_POST[tabla],$sidSucursal,$sPrefijo);
			echo $Array["Folio"];
		break;
	}
	
	mysql_close($link);//CERRAMOS LA CONEXION
?>