<?
session_start();
require_once('../Conectar.php');
require_once("../fn-error.php");
$link = Conectarse('webpmm');

	$s = "INSERT INTO catalogocliente 
	(id, personamoral, tipocliente, nombre, paterno, materno, rfc, email,
	celular, web,  poliza, npoliza, aseguradora, vigencia, clasificacioncliente,
	activado, pagocheque, tipoclientepromociones, sucursal, comision, usuario, fecha,fecharegistro)
	VALUES(null, '$_GET[rdmoral]', '$_GET[tipocliente]', UCASE('$_GET[nombre]'), UCASE('$_GET[paterno]'),
	UCASE('$_GET[materno]'), UCASE('$_GET[rfc]'), '$_GET[email]', '$_GET[celular]', '$_GET[web]',  '$_GET[poliza]',
	'$_GET[npoliza]', UCASE('$_GET[aseguradora]'), '$_GET[vigencia]', UCASE('$_GET[clasificacioncliente]'),
	'$_GET[activado]','$_GET[pago]','$_GET[clasificacion]', '$_SESSION[IDSUCURSAL]', '$_GET[comisiongeneral]', 
	'$_GET[usuario]', current_timestamp(),current_date)";
	$sqlins=mysql_query($s,$link) or postError($s);
	$codigo=mysql_insert_id();
		
	$s = "INSERT INTO losclientes 
	(nick,rfc,id,nombre,paterno,materno,sucursal,convenio,credito)
	SELECT '$_GET[nick]','$_GET[rfc]','$codigo','$_GET[nombre]','$_GET[paterno]',
	'$_GET[materno]','".$_GET[municipio]."','0','0'";
	mysql_query($s,$link) or postError($s);
	//INSERTAR TABLA DETALLE
	$sqlins=mysql_query("INSERT INTO direccion 
	(origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado, 
	pais,telefono,usuario,fecha)VALUES
	('cl','$codigo',
	 UCASE('".$_GET[calle]."'),
	 UCASE('".$_GET["numero"]."'),
	 UCASE('".$_GET["crucecalles"]."'),
	 '".$_GET["cp"]."',
	 UCASE('".$_GET["colonia"]."'),	 
     '".$_GET["poblacion"]."',
	 '".$_GET["municipio"]."', 
	 '".$_GET["estado"]."',
	 '".$_GET["pais"]."',
	 '".$_GET["telefono"]."',
	 '$usuario',CURRENT_TIMESTAMP())",$link) or postError("error en linea".__LINE__);
		
	echo "ok,$codigo";
?>