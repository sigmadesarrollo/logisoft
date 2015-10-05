<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}



/*if (isset($_SESSION['gvalidar'])!=100){

echo "<script language='javascript' type='text/javascript'>document.location.href='http://172.16.40.39/curso/pmm/index.php';</script>";

	}else{*/

	include('../../Conectar.php');

	$link=Conectarse('webpmm');

	$get = mysql_query('select count(*) from catalogoprospecto');

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st'];	}else{ $st = 0;	}

	$pp = 20;

?>

<script src="select.js"></script>



<style type="text/css">

<!--

.Estilo3 {background-color: #016193}

.Estilo4 {font-size: 9px; font-weight: bold; background-color: #016193; font-family: tahoma;}

-->

</style>





<link href="Tablas.css" rel="stylesheet" type="text/css" />

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<form name="buscar" >

  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#006194">

    <tr class="FondoTabla">

      <td width="10%" class="FondoTabla">ID</td>

      <td width="16%" class="FondoTabla">Nombre</td>

      <td width="24%" class="FondoTabla">Ap. Paterno </td>

      <td width="50%" class="FondoTabla">Ap. Materno </td>

</tr>

    <tr>

      <td colspan="4" height="300px" valign="top"><div id="div" style="width:100%; height:auto;">

        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="Tablas" id="tabla">

            <?		

		$get = mysql_query('select * from catalogoprospecto limit '.$st.','.$pp,$link);

		while($row=@mysql_fetch_array($get)){

	?>

            <tr onclick="window.parent.obtener('<?= $row['id'];?>'); parent.VentanaModal.cerrar();" style="cursor:pointer">

              <td width="48" height="20" class="Tablas">

                <?=$row['id'];?>              </td>

              <td width="78" class="Tablas"><?=strtoupper(htmlentities($row['nombre'])); ?></td>

              <td width="125" class="Tablas"><?=strtoupper(htmlentities($row['paterno'])); ?></td>

              <td width="240" class="Tablas"><?=strtoupper(htmlentities($row['materno'])); ?></td>

            </tr>

            <? } ?>

          </table>

      </div></td>

    </tr>

    <tr>

      <td colspan="4" align="center"><? echo paginacion($total, $pp, $st, 'buscarprospecto.php?st='); ?></td>

    </tr>

  </table>

</form>

<? //} ?>



