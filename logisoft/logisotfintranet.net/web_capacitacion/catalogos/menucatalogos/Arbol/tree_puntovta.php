<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="puntovta.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="tree.js"></script>
<script language="JavaScript" src="tree_tpl.js"></script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-size:9px;
}
-->
</style></head>
<body>
<table width="300" border=0 align=center cellpadding=0 cellspacing=0 style="font-size:9px">
  <tr>
    <td align=center><img src="../imagen/logo.jpg" width=95 height=100></td>
  </tr>
  <tr>
    <td><select name="Sucursal" id="Sucursal" style="width:216px">
      <option value="SMZT">Sucursal Mazatl&aacute;n</option>
    
        </select>
    </td>
  </tr>
  <tr>
    <td height="602" align="left" valign="top" bgcolor="#ecf2eb" >
	<?
	include ("treeview.php");
	init_menu("Catalogos","");
		init_folder("Relacionado Cliente","");
			Koption("Catalogo Clientes","../../cliente/client.php");
			Koption("Catalogo Prospecto","../../cliente/prospecto.php");
			Koption("Catalogo Tipo Cliente","../../cliente/tipocliente.php");
		end_folder();
		init_folder("Relacionado Sucursal","");
			Koption("Catalogo Sucursal","../../sucursal/catalogosucursal.php");
			Koption("Catalogo Destino","../../sucursal/catalogodestino.php");
			Koption("Catalogo Servicio","../../sucursal/catalogoservicio.php");		
			Koption("Catalogo Descripcion","../../sucursal/catalogodescripcion.php");
		end_folder();
		init_folder("Relacionado Unidades","");
			Koption("Tipo Unidad","../../unidad/catalogotipounidad.php","mainFrame");
			Koption("Unidades","../../unidad/catalogounidad.php?modulo=CatalogoUnidad");
			Koption("Carga y Descarga","../../unidad/catalogotiempocargadescarga.php");
		end_folder();
		
		init_folder("Relacionado Entregas","");
			Koption("Configurador Fletes","");
			Koption("Configurador Distancias","");
			Koption("Configurador Tiempos","");
		end_folder();
		
		init_folder("Relacionado Rutas","");
			Koption("Rutas Foraneas","../../rutas/catalogorutas.php");
			Koption("Rutas","");
		end_folder();
		
		init_folder("Relacionado Empleado","");
			Koption("Catalogo Empleado","../../empleado/catalogoempleado.php");
			Koption("Puesto","../../empleado/Catalogopuesto.php");
		end_folder();
		
		init_folder("Relacionado Configuradores","");
			Koption("Configurador General","../../configuradores/configuradorgeneral.php");
			Koption("Configurador Servicios","../../configuradores/configuradorservicio.php");
		end_folder();

		init_folder("Relacionado Codigo Postal","");
			Koption("País","../../colonias/catalogopais.php");
			Koption("Estados","../../colonias/catalogoestado.php");
			Koption("Mun./Del.","../../colonias/catalogomunicipio.php");
			Koption("Población","../../colonias/catalogopoblacion.php");
			Koption("Colonias","../../colonias/catalogocolonias.php");
			Koption("Código Postal","../../colonias/catalogocodigopostal.php");
		end_folder();




	end_menu();
	?>    
	<script> new tree (TREE_ITEMS, tree_tpl); </script> 
    </td>
  </tr>
</table>
</body>
</html>