<? session_start();



	if(!$_SESSION[IDUSUARIO]!=""){



		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");



	}



	require_once('../Conectar.php');



	$link = Conectarse('webpmm');



	



	$where = "";



	if($_GET[validasucursal]==1){



		$where =  " where sucursal = $_SESSION[IDSUCURSAL] and tiporuta='LOCAL' and fueradeservicio=0";



	}



	



	$get = @mysql_query('select count(*) from catalogounidad $where');	



	$total = mysql_result($get,0);



	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }



	$pp = 20;



?>



<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />



<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />



<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">



  <tr>



    <td width="9%" class="FondoTabla">ID</td>



    <td width="91%" class="FondoTabla">UNIDAD</td>



  </tr>



  <tr>



    <td colspan="3" class="Tablas" height="300px" valign="top">



      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">



        <?		



		$get = mysql_query('SELECT id, numeroeconomico FROM catalogounidad  $where limit '.$st.','.$pp,$link);



		while($row=@mysql_fetch_array($get)){



	?>



        <tr>



          <td width="43"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtenerUnidadBusqueda('<?= $row['id'];?>');parent.VentanaModal.cerrar();">



            <?= $row['id'];?>



          </span></td>



          <td width="411" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['numeroeconomico']; ?>" readonly="true" style="width:200px; border:none; cursor:default"></td>          



          <td width="42"></td>



        </tr>



        <? } ?>



      </table>



   </td>



  </tr>



  <tr>



    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarUnidadFinal.php?validasucursal=$_GET[validasucursal]&st="); ?></font></td>



  </tr>



</table>



