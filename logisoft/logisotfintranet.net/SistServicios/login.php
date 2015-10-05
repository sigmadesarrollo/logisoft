<?php
	session_start();
	require_once("Conectar.php");
	$link = conectarse();
	
	$s = "SELECT a.id,a.numempleado,c.descripcion AS sucursal, c.prefijo, a.sucursal AS idsucursal,
	CONCAT_WS(' ',a.nombre,a.apellidopaterno,a.apellidomaterno) AS Nombre, 
	IF(ISNULL(b.idEmpleado),'NO','SI') AS EmpleadoAdmin
	FROM catalogoempleado a
	LEFT JOIN catempleado_admin b ON a.id = b.idEmpleado
	LEFT JOIN catalogosucursal c ON a.sucursal = c.id
	WHERE a.user = '".$_POST[eUser]."' AND a.password = '".$_POST[ePwd]."'";
	$r = mysql_query($s,$link) or die(mysql_error($link)." ".$s);
	if(mysql_num_rows($r)>0){
		$f = mysql_fetch_object($r);
		$_SESSION[DATOSUSUARIO] = $f->id."##".$f->numempleado."##".utf8_encode($f->Nombre)."##".$f->EmpleadoAdmin."##".$f->sucursal."##".$f->prefijo."##".$f->idsucursal;
		$_SESSION[MENSAJE] = "";
		echo "ok,".utf8_encode($f->Nombre);
	}else{
		echo "noexiste";
	}
?>