<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if (isset($_SESSION['gvalidar'])!=100){

echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";

	}else{*/

	require_once('../../Conectar.php');

	$link=Conectarse('webpmm');

	$usuario=$_SESSION[NOMBREUSUARIO];

	$tipo=$_GET['tipo'];

		

	switch ($tipo){

		case 1:

			$get = @mysql_query('SELECT count(*) FROM catalogotipounidad ');

			break;

		case 2:

			$get = @mysql_query('select count(*) from catalogosucursal');

			break;

		case 3:

			$get = mysql_query('select count(*) from catalogoruta');

			break;

	}

	$total = @mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>

<script src="select.js"></script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="Tablas.css" rel="stylesheet" type="text/css" />

</head>



<body>

<? if($tipo==1){?>

<!-- BUSQUEDA POR TIPO UNIDAD -->

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr class="FondoTabla">

    <td width="9%">ID</td>

    <td width="27%">Descripción</td>

    

  </tr>

<tr>

    <td colspan="3" class="Tablas" height="300px" valign="top" >

      <table width="100%" border="0" align="center" class="Tablas"  >

        <?	

		$get = mysql_query('SELECT id,descripcion FROM catalogotipounidad limit '.$st.','.$pp,$link);		

		while($row=@mysql_fetch_array($get)){

		?>

        <tr >

          <td width="45" class="Tablas" ><span onClick="window.parent.obtenerTipoUnidad('<?=$row['id'];?>','<?=$row['descripcion'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">

            <?=$row['id'];?>

          </span></td>

          <td width="120" class="Tablas"><?=$row['descripcion'];?></td>

          <td width="317"></td>

        </tr>

        <? } ?>

      </table>

    </td>

  </tr>

  <tr>

    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'catalogosrutas_Buscar.php?tipo=1&st='); ?></font></td>

  </tr>

</table>

<p>

  <? } ?>



  <? if($tipo=='2'){?>

  <!-- BUSQUEDA POR SUCURSAL --></p>

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193" class="Tablas">

  <tr class="FondoTabla">

    <td width="7%">ID</td>

    <td width="85%">Descripción</td>

  </tr>

<tr>

    <td colspan="2" class="Tablas" height="300px" valign="top" >

      <table width="100%" border="0" align="center" class="Tablas"  >

        <?	

		$get = mysql_query('select id,descripcion,prefijo from catalogosucursal ORDER BY descripcion ASC limit '.$st.','.$pp,$link);		

		while($row=@mysql_fetch_array($get)){

		

	?>

        <tr >

          <td width="10%" class="Tablas" ><span onClick="window.parent.obtenerSucursal('<?=$row['id'];?>','<?=$row['prefijo'];?>','<?=$row['descripcion'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">

            <?=$row['id'];?>

          </span></td>

          <td width="79%" class="Tablas"><?=$row['descripcion'];?></td>

          <td width="19px"></td>

        </tr>

        <? } ?>

      </table>

   </td>

  </tr>

  <tr>

    <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'catalogosrutas_Buscar.php?tipo=2&st='); ?></font></td>

  </tr>

</table>

<? } ?>



<? if($tipo==3){?>

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193" class="Tablas">

    <tr>

      <td width="7%" class="FondoTabla">ID</td>

      <td width="85%" class="FondoTabla">Descripcion</td>

    </tr>

    <tr>

      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" class="Tablas"   >

          <?	

		$get = mysql_query('select * from catalogoruta limit '.$st.','.$pp,$link);		

		while($row=@mysql_fetch_array($get)){

		

	?> 

          <tr>

       <td width="10%" class="Tablas" >

<span onClick="window.parent.obtenerRutaBusqueda('<?= $row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>

            <td width="79%" class="Tablas"><?=htmlentities($row['descripcion']); ?></td>

            <td width="19px"></td>

          </tr>

          <? } ?>

      </table></td>

    </tr>

    <tr>

      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'catalogosrutas_Buscar.php?tipo=3&st='); ?></font></td>

    </tr>

</table>

<? }?>

</body>

</html>

