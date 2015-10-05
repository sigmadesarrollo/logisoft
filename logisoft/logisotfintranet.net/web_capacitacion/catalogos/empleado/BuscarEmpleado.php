<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogoempleado');
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
      <td width="10%" class="FondoTabla">ID</td>
      <td width="27%" class="FondoTabla">Nombre</td>
	  
      <td width="27%" class="FondoTabla">Ap. Paterno </td>
      <td width="28%" class="FondoTabla">Ap. Materno </td>
      <td width="8%" class="FondoTabla">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" height="300px" valign="top"><div id="txtHint" style="width:100%; height:auto;"><table width="100%" border="0" align="center">
          <?	
		$get = mysql_query('select * from catalogoempleado where id>0 limit '.$st.','.$pp,$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="41" class="Tablas" >
<span onclick="window.parent.BuscarEmpleado('<?= $row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>
            <td width="133" class="Tablas"><input class="Tablas" name="nombre" type="text" value="<?=htmlentities($row['nombre']); ?>" style="border:none" size="20" readonly="true" /></td>
            <td width="127" class="Tablas"><input class="Tablas" name="paterno" type="text" style="border:none" value="<?=htmlentities($row['apellidopaterno']); ?>" size="20" readonly="true" /></td>
            <td width="124" class="Tablas"><input class="Tablas" name="materno" type="text" style="border:none" value="<?=htmlentities($row['apellidomaterno']); ?>" size="20" readonly="true" /></td>
            <td width="49"></td>
          </tr>
          <? } ?>
      </table></div></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'BuscarEmpleado.php?st='); ?></font></td>
    </tr>
  </table> 
</form>
<? //} ?>