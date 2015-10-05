<?include_once("ctarifa.php");


$vpintervalozona=$_POST['vpintzon'];



$vpcolumnas=$_POST['vpzonaf']/$_POST['vpintzon'];


$vpzonai2=$_POST['vpzonai'];


sleep(1);
//actualiza los datos de la tarifa
$objempleado = new ctarifa;
$objempleado->eliminar($vpcolumnas,$vpzonai2,$vpintervalozona);
include('consulta.php');

?>