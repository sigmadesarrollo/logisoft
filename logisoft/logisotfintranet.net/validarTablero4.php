<?
	session_start();
	require_once('web_capacitacionPruebas/Conectar.php');
	$l=Conectarse('webpmm'); 
	
	if($_GET[accion]==1){
		
		/*if($_SESSION[IDUSUARIO]==8){
			$s = "select id from permisos_grupos where nombre = '$_GET[modulo]'";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
			$s = "update catalogoempleado set grupo = '$f->id' where id = '$_SESSION[IDUSUARIO]'";
			mysql_query($s,$l) or die($s);
			
			$s = "DELETE FROM permisos_empleadospermisos WHERE idempleado = '$_SESSION[IDUSUARIO]'";
			 mysql_query($s,$l) or die($s);
	
			$s = "INSERT INTO permisos_empleadospermisos
			SELECT idpermiso,$_SESSION[IDUSUARIO] FROM permisos_grupospermisos WHERE idgrupo = '$f->id'";
			mysql_query($s,$l) or die($s);	
		}*/
		
		
		$s = "SELECT pg.nombre
		FROM catalogoempleado AS ce
		INNER JOIN permisos_grupos AS pg ON ce.grupo = pg.id
		WHERE ce.id = '$_SESSION[IDUSUARIO]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		if($_GET[modulo]=="PANEL DE CONTROL" && ($f->nombre=='DIRECCION GENERAL' || $f->nombre=='GERENTE SUCURSAL' || $f->nombre=="ADMINISTRADOR GRAL")){
			echo "web_capacitacionPruebas/panelControl/pages";
		}elseif($_GET[modulo]=="DIRECCION GENERAL" && $f->nombre=="ADMINISTRADOR GRAL"){
			echo "web_capacitacionPruebas/tableroPermisos/pages";
		}elseif($_GET[modulo]==$f->nombre){
			echo "web_capacitacionPruebas/tableroPermisos/pages";
		}else{
			echo "acceso denegado";
		}
	}
?>