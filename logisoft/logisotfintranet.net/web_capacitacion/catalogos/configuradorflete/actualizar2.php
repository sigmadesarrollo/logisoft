<?include_once("ctarifa.php");


$vpcolumna=$_POST['vpcolumna'];
$vprenglon=$_POST['vprenglon'];
$vpvalor=$_POST['vpvalor'];

sleep(1);
//actualiza los datos de la tarifa
$objempleado = new ctarifa;
$objempleado->grabar($vpcolumna,$vprenglon,$vpvalor);
//include('consulta.php');

?>