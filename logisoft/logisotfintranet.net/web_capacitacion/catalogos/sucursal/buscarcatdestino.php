<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{*/
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from catalogodestino');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<script src="select.js"></script>
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="puntovta.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<form name="form1" >
  <table width="100%" border="0">
    <tr>
      <td><table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        <tr>
          <td width="7%" class="FondoTabla">ID</td>
          <td width="85%" class="FondoTabla">Descripci&oacute;n</td>
        </tr>
        <tr>
          <td colspan="2" height="300px" valign="top">
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <?		
		$get = mysql_query('select * from catalogodestino ORDER BY descripcion  ASC
 limit '.$st.','.$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
                <tr>
                  <td width="52"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtener('<?= $row['id'];?>');parent.VentanaModal.cerrar();">
                    <?= $row['id'];?>
                  </span></td>
                  <td width="420" class="Tablas"><?= $row['descripcion']; ?></td>
                  <td width="22"></td>
                </tr>
                <? } ?>
              </table>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarcatdestino.php?st='); ?></font></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
<? //} ?>