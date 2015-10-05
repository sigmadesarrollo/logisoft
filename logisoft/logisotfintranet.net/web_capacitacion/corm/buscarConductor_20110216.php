<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}
	

	$puesto = " and puesto between 47 and 48 ";
	
	require_once('../Conectar.php');

	$link = Conectarse('webpmm');

	$get = @mysql_query("select count(*) from catalogoempleado where enunidad=0 $puesto 
						AND (baja < '2009-01-01' OR (baja > '2009-01-01' AND bajareingreso > '2009-01-01')) $and ");	

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>



<link href="../FondoTabla.css" rel="stylesheet" type="text/css">

<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />



<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="10%" class="FondoTabla">ID</td>

    <td width="90%" class="FondoTabla">Nombre</td>

  </tr>

  <tr>

    <td colspan="3" height="300px" valign="top" >

      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

        <?		

		$get = mysql_query("SELECT id, CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) as nombre 
		FROM catalogoempleado where enunidad=0 $puesto 
		AND (baja < '2009-01-01' OR (baja > '2009-01-01' AND bajareingreso > '2009-01-01')) $and 
		limit ".$st.",".$pp,$link);

		while($row=@mysql_fetch_array($get)){

	?>

        <tr>

          <td width="49"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtenerConductorBusqueda('<?=$row['id'];?>','<?=$_GET['caja']; ?>');parent.VentanaModal.cerrar();">

            <?= $row['id'];?>

          </span></td>

          <td width="405" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['nombre']; ?>" readonly="true" style="width:300px; border:none; cursor:default"></td>         

          <td width="42"></td>

        </tr>

        <? } ?>

      </table>

      <p class="Tablas">&nbsp;</p></td>

  </tr>

  <tr>

    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarConductor.php?puesto='.$_GET[puesto].'&caja='.$_GET[caja].'&st='); ?></font></td>

  </tr>

</table>

