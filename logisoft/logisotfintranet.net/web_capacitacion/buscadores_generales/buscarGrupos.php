<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$s = "SELECT count(*) FROM permisos_grupos";
	$get = mysql_query($s);
	
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
      <td width="85%" class="FondoTabla">FECHA</td>
    </tr>
    <tr>
      <td height="300px" colspan="2" valign="top" class="Tablas" ><table width="100%" border="0" align="center" class="Tablas">
          <?	
			$s = "SELECT id, nombre FROM permisos_grupos";
		$get = mysql_query("$s limit $st,$pp",$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="10%" class="Tablas" >
<span onclick="parent.<?=$_GET[funcion]?>('<?= $row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="79%" class="Tablas"><?=htmlentities($row[1]); ?></td>
            <td width="19px"></td>
          </tr>
          <? } ?>
      </table>
          <p class="Tablas">&nbsp;</p>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarsucursal.php?function=$_GET[funcion]&st="); ?></font></td>
    </tr>
  </table> 
</form>
<? //} ?>