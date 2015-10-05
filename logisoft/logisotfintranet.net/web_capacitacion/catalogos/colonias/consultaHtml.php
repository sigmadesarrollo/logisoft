<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../../Conectar.php');
	$link=Conectarse('webpmm');
if($_GET['tipo']=="poblacion"){ ?>
<table width="100%" border="0" align="center">
          <?	
		$get = mysql_query("SELECT * FROM catalogopoblacion WHERE descripcion like '".$_GET['poblacion']."%'",$link);		
		while($row=@mysql_fetch_array($get)){		
	?> 
          <tr >
       <td width="10%" class="Tablas" >
<span onclick="parent.obtenerPoblacionx('<?= $row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>
            <td width="79%" class="Tablas"><input name="descripcion" type="text" class="Tablas" value="<?=$row['descripcion']; ?>" size="50" readonly="true" style="border:none; cursor:pointer" /></td>
            <td width="19px"></td>
          </tr>
          <? } ?>
</table>

<? }if($_GET['tipo']=="colonia"){ ?>
<table width="100%" border="0" align="center">
          <?	
		$get = mysql_query("SELECT * FROM catalogocolonia WHERE descripcion like '".$_GET['colonia']."%'",$link);					
		while($row=@mysql_fetch_array($get)){		
	?> 
          <tr >
       <td width="10%" class="Tablas" >
<span onclick="parent.obtener('<?= $row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>
            <td width="79%" class="Tablas"><input name="descripcion" type="text" class="Tablas" value="<?=$row['descripcion']; ?>" size="50" readonly="true" style="border:none; cursor:pointer" /></td>
            <td width="19px"></td>
          </tr>
          <? } ?>
</table>
  <? }else if($_GET['tipo']=="municipio"){ ?>
<table width="100%" border="0" align="center">
  <?	
		$get = mysql_query("SELECT * FROM catalogomunicipio WHERE descripcion like '".$_GET['poblacion']."%'",$link);		
		while($row=@mysql_fetch_array($get)){
	?>
  <tr >
    <td width="10%" class="Tablas" ><span onclick="parent.obtenerMunicipiox('<?= $row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF">
      <?= $row['id'];?>
    </span></td>
    <td width="79%" class="Tablas"><input name="descripcion" type="text" class="Tablas" value="<?=$row['descripcion']; ?>" size="50" readonly="true" style="border:none; cursor:pointer" /></td>
    <td width="19px"></td>
  </tr>
  <? } ?>
</table>
<? } ?>
