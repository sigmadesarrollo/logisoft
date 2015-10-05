<?

	require_once("../Conectar.php");

	require_once("../clases/ValidaConvenio.php");

	$l = Conectarse("webpmm");

	

	if($_GET[accion] == 1){

		$vc = new ValidaConvenio('','','','');

$flete = $vc->ObtenerFlete($_GET[convenio], $_GET[idorigen], $_GET[iddestino], $_GET[descripcion], $_GET[peso], $_GET[cantidad]);		

		$f = split(",",$flete);

		echo $f[0].','.$f[1];

	}

	

?>