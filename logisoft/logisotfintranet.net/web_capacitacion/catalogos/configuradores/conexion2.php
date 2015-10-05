<html>

<?

function conexion()

{

$link=mysql_connect("DBSERVER","root","root");

mysql_select_db("pmm_dbpruebas",$link);

return $link;

}

function conectar()

{

	mysql_connect("DBSERVER","root","root");

	mysql_select_db("pmm_dbpruebas");

}



function desconectar()

{

	mysql_close();

}



?>

</html>

