<? include_once("cTarifa.php");


$vpintervalozona=$_POST['vpintzon'];



$vpcolumnas=$_POST['vpzonaf']/$_POST['vpintzon'];


$vpzonai2=$_POST['vpzonai'];


$vpfolio2=$_POST['vpfolio'];

sleep(1);
//actualiza los datos de la tarifa
$objempleado = new ctarifa;
$objempleado->eliminar($vpcolumnas,$vpzonai2,$vpintervalozona,$vpfolio2);
include('consulta.php');

?>