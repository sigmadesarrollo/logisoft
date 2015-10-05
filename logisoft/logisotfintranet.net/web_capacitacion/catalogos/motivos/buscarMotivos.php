<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	require_once('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogomotivos');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<form name="buscar" >
  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="9%" class="FondoTabla">ID</td>
      <td width="56%" class="FondoTabla">Descripción</td>
      <td width="35%" class="FondoTabla">Clasificaci&oacute;n</td>
    </tr>
    
    <tr>
      <td colspan="3" class="Tablas" height="300px" valign="top" >
	  
	  <table width="496"  border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?	
		$get = mysql_query('select * from catalogomotivos limit '.$st.','.$pp,$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr>
       <td width="49" class="Tablas" height="17">
<span onclick="window.parent.obtener('<?=$row['id'];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row['id'];?></span></td>
            <td class="Tablas"  style="width:280px"><?=$row['descripcion'];?></td>
            <td  class="Tablas" style="width:200px"><?=$row['clasificacion'];?></td>
          </tr>
          <? } ?>
		 
      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarMotivos.php?st='); ?></font></td>
    </tr>
  </table> 
</form>
<? //} ?>