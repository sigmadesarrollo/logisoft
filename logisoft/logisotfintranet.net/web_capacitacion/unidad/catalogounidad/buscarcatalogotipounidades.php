<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if (isset($_SESSION['gvalidar'])!=100){
echo "<script language='javascript' type='text/javascript'>document.location.href='index.php';</script>";
	}else{ */
	include('../Conectar.php');
	$link=Conectarse('pmm');
	$get = mysql_query('select count(*) from catalogotipounidad');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<script src="select.js"></script>
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<form name="form1" >
<table width="500"  border="1" align="center" bordercolor="#016193">
  <tr>
    <td width="7%" bordercolor="#016193" class="FondoTabla">ID</td>
    <td width="85%" bordercolor="#016193" class="FondoTabla">Descripci&oacute;n</td>
  </tr>
  <tr>
    <td colspan="2"><div id="div" style="width:100%; height:300px; overflow: scroll;">
      <table width="100%" border="0" align="center">
        <?		
		$get = mysql_query('select * from catalogotipounidad limit '.$st.','.$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="10%" class="Tablas"><a href="JavaScript:close();" onClick="window.opener.obtener('<?= $row['codigo'];?>','<?= $row['descripcion'];?>')";>
            <?= $row['codigo'];?>
          </a></td>
          <td width="79%" class="Tablas"><?= $row['descripcion']; ?></td>
          <td width="19px"></td>
        </tr>
        <?  }  ?>
      </table>
    </div></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarcatalogotipounidades.php?st='); ?></font></td>
  </tr>
</table>
</form>
<? /*} */?>