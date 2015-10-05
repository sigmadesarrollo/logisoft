<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	$s = "SELECT count(*) FROM traspasocredito";
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
      <td width="10%" class="FondoTabla">Folio</td>
      <td width="26%" class="FondoTabla">Fecha</td>
      <td width="64%" class="FondoTabla">Sucursal Traspaso</td>
    </tr>
    <tr>
      <td height="300px" colspan="3" valign="top" class="Tablas" ><table width="100%" border="0" align="center" class="Tablas">
          <?			
		$s = "SELECT t.folio, date_format(t.fechatraspaso, '%d/%m/%Y') as fechatraspaso, cs.descripcion FROM traspasocredito t
		INNER JOIN catalogosucursal cs ON t.sucursaltraspaso = cs.id";
		$get = mysql_query("$s limit $st,$pp",$link);		
		while($row=@mysql_fetch_array($get)){
		
	?> 
          <tr >
       <td width="45" class="Tablas" >
<span onclick="parent.<?=$_GET[funcion]?>('<?= $row[0];?>');parent.VentanaModal.cerrar();" style="cursor: pointer; color:#0000FF"><?= $row[0];?></span></td>
            <td width="125" class="Tablas"><?=$row[1] ?></td>
            <td width="256" class="Tablas"><?=cambio_texto($row[2]); ?></td>
            <td width="52"></td>
          </tr>
          <? } ?>
      </table>
      </td>
    </tr>
    <tr>
      <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarTraspasoCredito.php?funcion=$_GET[funcion]&st="); ?></font></td>
    </tr>
  </table> 
</form>