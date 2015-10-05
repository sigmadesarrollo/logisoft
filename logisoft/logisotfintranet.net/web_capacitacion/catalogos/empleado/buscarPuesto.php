<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	
	require_once('../../Conectar.php');

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

      <td width="85%" class="FondoTabla">Descripción</td>

    </tr>

    <tr>

      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">

    <?	

		$get = mysql_query('select * from catalogopuesto limit '.$st.','.$pp,$link);		

		while($row=@mysql_fetch_array($get)){

		

	?> 

          <tr >

       <td width="10%" class="Tablas" >

<span onclick="window.parent.obtener('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?=$row[0];?></span></td>

            <td width="79%" class="Tablas"><input name="descripcion" type="text" class="Tablas" value="<?=$row[1]; ?>" style="border:none; width:350px" readonly="" /></td>

            <td width="19px"></td>

          </tr>

          <? } ?>

      </table></td>

    </tr>

    <tr>

      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarPuesto.php?st='); ?></font></td>

    </tr>

  </table> 

</form>
