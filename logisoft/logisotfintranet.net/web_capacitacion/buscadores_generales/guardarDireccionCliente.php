<?
session_start();
require_once('../Conectar.php');
$link = Conectarse('webpmm');
	//INSERTAR TABLA DETALLE
	$s = "INSERT INTO direccion 
	(origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado, 
	pais,telefono,usuario,fecha)VALUES
	('cl','".$_GET["idcliente"]."',
	 UCASE('".$_GET["calle"]."'),
	 UCASE('".$_GET["numero"]."'),
	 UCASE('".$_GET["crucecalles"]."'),
	 '".$_GET["cp"]."',
	 UCASE('".$_GET["colonia"]."'),	 
     '".$_GET["poblacion"]."',
	 '".$_GET["municipio"]."', 
	 '".$_GET["estado"]."',
	 '".$_GET["pais"]."',
	 '".$_GET["telefono"]."',
	 '$usuario',CURRENT_TIMESTAMP())";
	mysql_query($s,$link) or die("error en linea".__LINE__);
	$codigo = mysql_insert_id($link);
		
	echo "ok,$codigo";
?>