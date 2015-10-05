<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/

	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogopais');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<script src="select.js"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<form name="buscar" >
  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="7%" class="FondoTabla">ID</td>
      <td width="85%" class="FondoTabla">Nombre</td>
    </tr>
    <tr>
      <td colspan="2" height="300px" valign="top"><div id="txtHint" style="width:100%; height:auto;"><table width="100%" border="0" align="center" class="Tablas">
          <?	
		$get = mysql_query('select * from catalogopais limit '.$st.','.$pp,$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="10%" class="Tablas" >
<span onclick="window.parent.obtener('<?= $row['id'];?>','<?=htmlentities($row['descripcion']); ?>','<?=htmlentities($row['default']); ?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>
            <td width="79%" class="Tablas"><?=htmlentities($row['descripcion']); ?></td>
            <td width="19px"></td>
          </tr>
          <? } ?>
      </table></div></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarpais.php?st='); ?></font></td>
    </tr>
  </table> 
</form>
