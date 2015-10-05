<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/
	include('../Conectar.php');

	$link=Conectarse('webpmm');

	$get = mysql_query('select count(*) from deposito where sucursal = '.$_SESSION[IDSUCURSAL].'');

	$total = mysql_result($get,0);

	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }

	$pp = 20;

?>



<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />



<form name="buscar" >

  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="7%" class="FondoTabla">Folio</td>

      <td width="85%" class="FondoTabla">Cheque</td>

    </tr>

    <tr>

      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" class="Tablas">

          <?	

		$get = mysql_query('SELECT d.folio, IF(d.fechaefectivo="0000-00-00","",DATE_FORMAT(d.fechaefectivo,"%d/%m/%Y")) AS fecha FROM deposito d
		WHERE sucursal = '.$_SESSION[IDSUCURSAL].' limit '.$st.','.$pp,$link);

		while($row=@mysql_fetch_array($get)){

		

	?> 

          <tr >

       <td width="10%" class="Tablas" >

<span onclick="parent.obtenerDeposito('<?=$row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>

            <td width="79%" class="Tablas"><?=utf8_decode($row[1]); ?></td>

            <td width="19px"></td>

          </tr>

          <? } ?>

      </table></td>

    </tr>

    <tr>

      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarDeposito.php?st='); ?></font></td>

    </tr>

  </table> 

</form>
