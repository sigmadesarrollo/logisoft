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

	include('treeview.php');	

	init_menu("Punto de Venta - PMM","");

	init_folder("Guias","");

		Koption("Cotizar","");

		Koption("Normales","");

		Koption("Empresariales","");

	end_folder();

	init_folder("Facturaci�n","");

		Koption("Guias Normales","");

			init_folder("Otros Conceptos","");

					Koption("Guias Normales","");

					Koption("Otros Conceptos","");

			end_folder();

	end_folder();

	init_folder("Entregas","");

		Koption("Ocurre","");

		Koption("Domicilio","");

	end_folder();

	init_folder("Recolecciones","");

		Koption("Transmitida","");

		Koption("Realizada","");

		Koption("No Realizada","");

		Koption("Reprogramadas","");

		Koption("Canceladas","");

	end_folder();

	init_folder("Almac�n","");

		Koption("Ocurre","");

		Koption("EAD","");

		Koption("Unidades","");

	end_folder();

	init_folder("Embarques","");

		Koption("Manual","");

		Koption("Automatico","");

		Koption("Relaci�n de Embarques","");

			init_folder("Destinos","");

					Koption("Culiac�n","");

					Koption("Durango","");

					Koption("Jalisco","");

					Koption("Mexico","");					

			end_folder();

	end_folder();

	init_folder("CAT","");

		Koption("Da�os y Faltantes","");

		Koption("Envio Problema","");

		Koption("Cancelaciones","");

	end_folder();

	init_folder("Caja","");

		Koption("Apertura","");

		Koption("Movimientos","");

		Koption("Corte de Caja","");

		Koption("Cierre de D�a","");		

	end_folder();

	end_menu();

?>

<script>

	new tree (TREE_ITEMS, tree_tpl);

</script>

</body>

</html>



