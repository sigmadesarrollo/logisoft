<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
  <script language="JavaScript" src="tree.js"></script>
  <script language="JavaScript" src="tree_tpl.js"></script>

<body>
<?
	include ("treeview.php");
	init_menu("Bandeja de Pedidos","");
	init_folder("folder 1","");
	Koption("opcion1","");
	Koption("opcion2","");
		init_folder("folder1","");
			Koption("opcion1","");
		end_folder();	
	end_folder();
	init_folder("folder 2","");
	Koption("opcion1","");
	Koption("opcion2","");
	end_folder();

	end_menu();
?>
<script>
	new tree (TREE_ITEMS, tree_tpl);
</script>	

</body>
</html>
