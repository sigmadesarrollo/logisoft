<?
	include ("../../../../Conectar.php");
	$conexion = Conectarse("webpmm");
	$sql = mysql_query("select * from catalogoempleado");
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
  <script language="JavaScript" src="../../tree.js"></script>
  <script language="JavaScript" src="../../tree_tpl.js"></script>

<body>
<p>
  <?
	include ("treeview.php");
	init_menu("Atención a Clientes","");
	init_folder("Tipo de Problema","");
	
	while($row = mysql_fetch_array($sql,$conexion))
	{
		Koption($row['nombre']. "(" . $row['id'] . ")","../Listado.php?Tipo=". $row['id']);	
	}
	end_folder();	
	end_menu();
?>
</p>
<p>&nbsp;</p>
<script>
	new tree (TREE_ITEMS, tree_tpl);
</script>
</body>
</html>
