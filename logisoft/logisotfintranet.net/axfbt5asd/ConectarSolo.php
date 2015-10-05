<?
function Conectarse($base){
	if (!($link=mysql_connect('localhost',"pmm","guhAf2eh")))
	{
		echo " en conectar Error conectando a la base de datos.";
		exit();
	}
	if($base=="webpmm")
		$base = "pmm_curso";
	if (!mysql_select_db($base,$link))
	{
		echo "en conectar Error seleccionando la base de datos.";
		exit();
	}
	return $link;
}
?>