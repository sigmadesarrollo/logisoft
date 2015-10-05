<? session_start();

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

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="Tablas.css" rel="stylesheet" type="text/css" />



<form name="buscar" >

  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    

    <tr>

      <td width="10%" class="FondoTabla">ID</td>

      <td width="90%" class="FondoTabla">Nombre</td>

    </tr>

    <tr>

      <td colspan="2" height="300px" valign="top"><div id="div" style="width:100%; height:auto;">

        <table width="100%" border="0" align="center" class="Tablas" id="tabla">

            <?		

		$get = mysql_query('select * from catalogoprospecto limit '.$st.','.$pp,$link);

		while($row=@mysql_fetch_array($get)){

	?>

            <tr>

              <td width="10%" class="Tablas" >

<span onclick="window.parent.obtenerprospecto('<?= $row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=strtoupper($row['id']);?></span></td>

              <td width="79%" class="Tablas"><?=strtoupper($row['nombre']); ?></td>

              <td width="19px"></td>

            </tr>

            <? } ?>

          </table>

      </div></td>

    </tr>

    <tr>

      <td colspan="2" align="center"><? echo paginacion($total, $pp, $st, 'buscarprospecto.php?st='); ?></td>

    </tr>

  </table>

</form>

<? //} ?>