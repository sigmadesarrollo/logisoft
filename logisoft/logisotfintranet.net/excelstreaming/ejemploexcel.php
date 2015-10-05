<?
require_once("excel.php");
require_once("excel-ext.php");

	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
	
// Consultamos los datos desde MySQL
$conEmp = mysql_connect("localhost", "pmm", "gqx64p9n");
mysql_select_db("pmm_curso", $conEmp);
$queEmp = "SELECT * from catalogocliente limit 30000";
$resEmp = mysql_query($queEmp, $conEmp) or die(mysql_error());
$totEmp = mysql_num_rows($resEmp);
// Creamos el array con los datos
while($datatmp = mysql_fetch_assoc($resEmp)) {
	$datatmp[sistema] = "$34454";
    $data[] = $datatmp;
}
// Generamos el Excel  
createExcel("excel-mysql-xx.xls", $data);
exit;
?>