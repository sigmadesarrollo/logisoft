<? 
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogoproveedor');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
-->
</style>
<form name="form1" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="10%" class="estilo_relleno Estilo1">ID</td>
    <td width="49%" class="estilo_relleno Estilo1">Nombre</td>
  </tr>
  <tr>
    <td colspan="3"  height="300px" valign="top">
      <table width="100%" border="0" align="center">
        <?		
		$get = mysql_query('select * from catalogoproveedor limit '.$st.','.$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
         <td width="45">
<span onclick="window.parent.obtener('<?= $row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span>          </td>
        <td width="177" class="Tablas"><?=htmlentities(strtoupper($row['nombre'])); ?></td>
          <td width="57"></td>
        </tr>
        <? } ?>
      </table>
   </td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'catalogoproveedor.php?st='); ?></font></td>
  </tr>
</table>
</form>
<? //} ?>