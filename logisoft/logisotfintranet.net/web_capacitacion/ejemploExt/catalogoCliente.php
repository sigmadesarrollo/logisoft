<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "INSERT INTO clienteprueba SET
	nombre	= UCASE('".$_POST[nombre]."'),
	paterno	= UCASE('".$_POST[paterno]."'),
	materno	= UCASE('".$_POST[materno]."'),
	rfc		= UCASE('".$_POST[rfc]."'),
	email	= UCASE('".$_POST[email]."'),
	celular	= UCASE('".$_POST[celular]."'),
	sitio	= UCASE('".$_POST[sitio]."'),
	-- usuario	= ".$_SESSION[IDUSUARIO].",
	fecha	= CURRENT_DATE()";	
	if (!$result = mysql_query($s)) {		
		echo '{success:false, msg: "'.cambio_texto('Hubo un Error al insertar los datos.').'"}';
	}else{				
		echo '{success:true, msg: "'.cambio_texto('Los datos se guardarón satisfactoriamente.').'"}';	
	}
	
?>