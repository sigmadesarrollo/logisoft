<? 
#declarada para las clases de impresion de pdf
function Conectarse($base){
	//if (!($link=@mysql_connect("10.6.186.45","webpmm","Sistemapmm09")))
	if (!($link=mysql_connect('localhost',"pmm","gqx64p9n"))){
		echo " en conectar Error conectando a la base de datos.";
		exit();
	}
	if($base=="webpmm")
		$base = "pmm_curso";
	if (!mysql_select_db($base,$link)){
		echo "en conectar Error seleccionando la base de datos.";
		exit();
	}
	if($_SESSION[IDSUCURSAL]!=""){
		$s = "SET @@session.time_zone = (SELECT zonahoraria FROM catalogosucursal WHERE id = '$_SESSION[IDSUCURSAL]');";
		mysql_query($s,$link);
	}
	mysql_set_charset('utf8',$link);
	mysql_query("SET NAMES 'utf8'");
	return $link;
}
?>