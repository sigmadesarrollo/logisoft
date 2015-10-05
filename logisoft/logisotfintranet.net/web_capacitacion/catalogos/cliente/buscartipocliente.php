<? session_start();
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogotipocliente');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<script src="select.js"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<form name="form1" >
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="12%" class="FondoTabla">ID</td>
    <td width="33%" class="FondoTabla">Tipo</td>
    <td width="55%" class="FondoTabla">Descripci&oacute;n</td>
  </tr>
  <tr>
    <td colspan="3"><div id="div" style="width:100%; height:330px; overflow: scroll;">
      <table width="100%" border="0" align="center">
        <?		
		$get = mysql_query('select * from catalogotipocliente limit '.$st.','.$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>		
		<tr>
       		<td width="8%" class="Tablas" >
<span class="Tablas" onclick="window.parent.obtener('<?= $row['id'];?>','<?=cambio_texto($row['descripcion']);?>','<?=cambio_texto($row['tipocliente']);?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>
            <td width="37%" class="Tablas"><?=cambio_texto($row['tipocliente']); ?></td>
            <td width="55%" class="Tablas"><?=cambio_texto($row['descripcion']); ?></td>
          </tr>
		
        <? } ?>
      </table>
    </div></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscartipocliente.php?st='); ?></font></td>
  </tr>
</table>
</form>