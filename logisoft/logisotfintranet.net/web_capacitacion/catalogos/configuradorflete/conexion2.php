<html>


<?


function conexion()


{


$link=@mysql_connect('mysql.hostinger.mx',"u356875594_pmm","gqx64p9n");


mysql_select_db("u356875594_pmm",$link);


return $link;


}


function conectar()


{


	$link=@mysql_connect('mysql.hostinger.mx',"u356875594_pmm","gqx64p9n");


	mysql_select_db("u356875594_pmm");


}





function desconectar()


{


	mysql_close();


}





?>


</html>


