<?
	include("Ventas.php");
	
	$venta = new Ventas();
	$cosa = $venta->getGeneralMes();
	
	print_r($cosa);
  ?>