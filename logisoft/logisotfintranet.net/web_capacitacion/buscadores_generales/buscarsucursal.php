<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogosucursal');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>

<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<form name="buscar" >
  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="7%" class="FondoTabla">ID</td>
      <td width="85%" class="FondoTabla">Nombre</td>
    </tr>
    <tr>
      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" class="Tablas">
          <?	
		$get = mysql_query('select id,descripcion from catalogosucursal
		ORDER BY descripcion ASC limit '.$st.','.$pp,$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="10%" class="Tablas" >
<span onclick="parent.obtenerSucursal('<?= $row['id'];?>','<?= $row['descripcion'];?>');try{parent.VentanaModal.cerrar();}catch(e){parent.mens.cerrar();}" style="cursor: pointer; color:#0000FF"><?= $row['id'];?></span></td>
            <td width="79%" class="Tablas"><?=utf8_decode($row['descripcion']); ?></td>
            <td width="19px"></td>
          </tr>
          <? } ?>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarsucursal.php?st='); ?></font></td>
    </tr>
  </table> 
</form>
<? //} ?>