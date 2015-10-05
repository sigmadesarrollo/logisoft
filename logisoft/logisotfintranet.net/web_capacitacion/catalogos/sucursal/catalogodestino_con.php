<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_GET[accion] == 1){
		$s = "INSERT INTO catalogodestino SET 
		descripcion=UCASE('".$_GET[descripcion]."'),sucursal='".$_GET[sucursal]."',poblacion='".$_GET[poblacion]."',
		costoead='".$_GET[costoead]."',costorecoleccion='".$_GET[costorecoleccion]."',restringiread='".$_GET[restringiread]."',
		restringireadapfsinconvenio='".$_GET[restringireadapfsinconvenio]."',restringirrecoleccion='".$_GET[restringirrecoleccion]."',
		restringirporcobrar='".$_GET[restringirporcobrar]."',deshabilitarconvenio='".$_GET[deshabilitarconvenio]."',
		notificacion='".$_GET[notificacion]."',notificaciones=UCASE('".$_GET[notificaciones]."'),subdestinos='".$_GET[subdestinos]."',
		todasemana='".$_GET[todasemana]."',lunes='".$_GET[lunes]."',martes='".$_GET[martes]."',miercoles='".$_GET[miercoles]."',
		jueves='".$_GET[jueves]."',viernes='".$_GET[viernes]."',sabado='".$_GET[sabado]."',
		usuario='".$_SESSION[NOMBREUSUARIO]."',fecha=CURRENT_TIMESTAMP";	
		mysql_query($s,$l) or die($s);
		$codigo = mysql_insert_id();
		
		echo "guardo,".$codigo;
		
	}else if($_GET[accion] == 2){
		$s = "UPDATE catalogodestino SET 
		descripcion=UCASE('".$_GET[descripcion]."'),sucursal='".$_GET[sucursal]."',poblacion='".$_GET[poblacion]."',
		costoead='".$_GET[costoead]."',costorecoleccion='".$_GET[costorecoleccion]."',restringiread='".$_GET[restringiread]."',
		restringireadapfsinconvenio='".$_GET[restringireadapfsinconvenio]."',restringirrecoleccion='".$_GET[restringirrecoleccion]."',
		restringirporcobrar='".$_GET[restringirporcobrar]."',deshabilitarconvenio='".$_GET[deshabilitarconvenio]."',
		notificacion='".$_GET[notificacion]."',notificaciones=UCASE('".$_GET[notificaciones]."'),subdestinos='".$_GET[subdestinos]."',
		todasemana='".$_GET[todasemana]."',lunes='".$_GET[lunes]."',martes='".$_GET[martes]."',miercoles='".$_GET[miercoles]."',
		jueves='".$_GET[jueves]."',viernes='".$_GET[viernes]."',sabado='".$_GET[sabado]."',
		usuario='".$_SESSION[NOMBREUSUARIO]."',fecha=CURRENT_TIMESTAMP WHERE id=".$_GET[destino];
		mysql_query($s,$l) or die($s);
		
		echo "modifico";
	}
?>