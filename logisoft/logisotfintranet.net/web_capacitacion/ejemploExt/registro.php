<?	
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "INSERT INTO prueba SET nombre=UCASE('".$_GET[nombre]."'),
	paterno=UCASE('".$_GET[paterno]."'), materno=UCASE('".$_GET[materno]."'),
	email=UCASE('".$_GET[email]."')";
	mysql_query($s,$l) or die($s);
	$id = mysql_insert_id();
	
	if(!empty($id)){
		//header('HTTP/1.1 201 Created success');
		$info = array(
			'success' => true,
			'msg' => 'LOS DATOS BLABLABLA'
		);
	}else{
		//header('HTTP/1.1 501 Error saving the record');
		$info = array(
			'success' => false,
			'msg' => 'buuuuuuuuuuuu'
		);
	}
	
	echo json_encode($info);

?>