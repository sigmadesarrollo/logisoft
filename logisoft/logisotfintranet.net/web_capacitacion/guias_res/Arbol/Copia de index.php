<?  

	//include ("Conectar.php");

	//$conexion = Conectarse("pmm");

	//$sql = mysql_query("SELECT COUNT(*) As Numero, TR.TReporte As Tipo ,CTR.Descripcion FROM TReportes TR INNER JOIN CatalogoTipoReporte CTR ON TR.TReporte = CTR.Codigo GROUP BY TR.TReporte");

?>

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<title>Untitled Document</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

  <script language="JavaScript" src="tree.js"></script>

  <script language="JavaScript" src="tree_tpl.js"></script>



<body>

<p>

  <?

	include ("treeview.php");

	init_menu("Administración","");

	init_folder("Catalogos","");	

		Koption("Consultar Prospecto","../cliente/buscarprospecto.php");

		Koption("Consultar Tipo Cliente","../cliente/buscartipocliente.php");

	end_folder();

	end_menu();

	/*init_folder("Tipo de Problema","");

	

	while($row = mysql_fetch_array($sql,$conexion))

	{

		Koption($row['Descripcion']. "(" . $row['Numero'] . ")","../Listado.php?Tipo=". $row['Tipo']);	

	}

	end_folder();	

	end_menu();*/

?>

</p>

<p>&nbsp;</p>

<script>

	new tree (TREE_ITEMS, tree_tpl);

</script>

</body>

</html>



