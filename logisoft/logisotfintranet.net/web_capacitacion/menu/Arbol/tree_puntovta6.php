<? session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
?>

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
}
-->
</style></head>
<body>
<font size="0" color="#FFFFFF">&lt;</font>
<table width="223" border=0 align=center cellpadding=0 cellspacing=0>
  <tr>
    <td width="223" align=center><img src="../../img/logo.jpg" width=95 height=100></td>
  </tr>
  <tr>
    <td align="center">
    <select name="nombresucursal" style="width:218px" onChange="document.all.idsucursal.value=this.options[this.selectedIndex].idsuc;" disabled>
    	<?
			$s = "select cs.descripcion as sdesc, cs.id as idsuc
		from catalogosucursal as cs where id = $_SESSION[IDSUCURSAL]";
		$idsuc = 0;
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			if($idsuc==0){
				$idsuc = $f->idsuc;
			}
		?>
    	<option value="<?=$f->idsuc?>" idsuc="<?=$f->idsuc?>"><?=$f->sdesc?></option>
        <?
			}
		?>
    </select>
    <input type="hidden" name="idsucursal" value="<?=$idsuc?>">
    </td>
  </tr>
  <tr>
    <td height="602" align="left" valign="top" bgcolor="#ecf2eb">
	<?
	include ("treeview.php");
	init_menu("Bandeja de trabajo","../Listado.php?Tipo=2");
	init_folder("Gu&iacute;as","");
		Koption("Normales","");
		Koption("Empresariales","");
		Koption("Correo interno","");
	end_folder();			
	init_folder("Entregas","");
		init_folder("Ocurre","");
			Koption("Confirmacin de entregas","");
			Koption("Verificador","");					
		end_folder();	
		init_folder("Domicilio","");	
			Koption("Asignaci&oacute;n de rutas","");
			Koption("Confirmacin de entregas","");					
			Koption("Verificador","");								
		end_folder();	
	end_folder();	
	init_folder("Recolecci&oacute;n","");
		Koption("Agenda","");
		Koption("Transmitidas","");
		Koption("Realizadas","");
		Koption("Pendientes","");
		Koption("Reprogramadas","");						
		Koption("Canceladas","");		
	end_folder();
	init_folder("Embarques","");
		Koption("Registro","");
		Koption("Relaci&oacute;n de embarques","");
		Koption("Lista de re-expedici&oacute;n","");
		Koption("Inventarios","");		
	end_folder();		
	init_folder("Evaluaci&oacute;n","");
		Koption("Evaluacion Mercancia","../../evaluacion/EvaluacionDeMercancia.php");	
		Koption("Realizadas","");
		Koption("Pendientes","");						
		Koption("Canceladas","");		
	end_folder();
	init_folder("Caja","");
		init_folder("Apertura","");		
			Koption("Dia","");		
			Koption("Caja","");					
		end_folder();	
		Koption("Movimientos","");						
		init_folder("Cierre","");		
			Koption("Corte de caja","");	
			Koption("Definitivo","");				
			Koption("Parcial","");				
		end_folder();	
	end_folder();
	end_menu();
	?>
	<script> new tree (TREE_ITEMS, tree_tpl); </script> 
    </td>
  </tr>
</table>
</body>
</html>