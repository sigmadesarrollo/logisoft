<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogopuesto');
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
      <td width="85%" class="FondoTabla">Descripcion</td>
    </tr>
    <tr>
      <td height="300px" colspan="2" valign="top" class="Tablas"><table width="100%" border="0" align="center" cellpadding="0" class="Tablas">
          <?	
		$get = mysql_query('select * from catalogopuesto limit '.$st.','.$pp,$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="10%" class="Tablas" >
<span onclick="window.parent.ObtenerPuesto('<?= $row[0];?>','<?= $row[1];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="79%" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row[1]; ?>" readonly="" style="border:none" size="50"></td>
            <td width="19px"></td>
          </tr>
          <? } ?>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'CatalogoEmpleadoBuscarPuesto.php?st='); ?></font></td>
    </tr>
  </table> 
</form>
<? //} ?>