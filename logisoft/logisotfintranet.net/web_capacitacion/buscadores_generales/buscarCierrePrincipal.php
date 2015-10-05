<? session_start();

	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$get = mysql_query('select count(*) from cierreprincipal WHERE sucursal = '.$_SESSION[IDSUCURSAL].'');
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<form name="buscar" >
  <table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="20%" class="FondoTabla">FOLIO</td>
      <td width="80%" class="FondoTabla">FECHA</td>
    </tr>
    <tr>
      <td colspan="2" class="Tablas" height="300px" valign="top"><table width="100%" border="0" align="center" class="Tablas">
          <?	
		$get = mysql_query("select folio, date_format(fechacierre, '%d/%m/%Y') as fechacierre from cierreprincipal
		WHERE sucursal = ".$_SESSION[IDSUCURSAL]."
		ORDER BY folio limit ".$st.",".$pp,$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="93" class="Tablas" >
<span onclick="parent.<?=$_GET[funcion] ?>('<?= $row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="338" class="Tablas"><?=$row['fechacierre']; ?></td>
            <td width="51"></td>
          </tr>
          <? } ?>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarCierrePrincipal.php?funcion='.$_GET[funcion].'&st='); ?></font></td>
    </tr>
  </table> 
</form>