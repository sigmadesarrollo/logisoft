<?
	session_start();
	require_once('web_pruebas/Conectar.php');
	$l=Conectarse('webpmm'); 
	
	if($_GET[accion]==1){
		$s = "SELECT pg.nombre
		FROM catalogoempleado AS ce
		INNER JOIN permisos_grupos AS pg ON ce.grupo = pg.id
		WHERE ce.id = '$_SESSION[IDUSUARIO]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($_GET[modulo]=="PANEL DE CONTROL" && ($f->nombre=='DIRECCION GENERAL' || $f->nombre=='GERENTE SUCURSAL' || $f->nombre=="ADMINISTRADOR GRAL")){
			echo "web_pruebas/panelControl/pages";
		}elseif($_GET[modulo]=="DIRECCION GENERAL" && $f->nombre=="ADMINISTRADOR GRAL"){
			echo "web_pruebas/tableroPermisos/pages";
		}elseif($_GET[modulo]==$f->nombre){
			echo "web_pruebas/tableroPermisos/pages";
		}else{
			echo "acceso denegado";
		}
	}
?>