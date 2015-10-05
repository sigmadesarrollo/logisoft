<? session_start();


	require_once('../Conectar.php');


	$link = Conectarse('webpmm');	

	if($_GET[criterio]!=""){
		$criterio =" WHERE rc.sucursal=".$_GET[sucursal]." and
		rc.folio NOT IN (SELECT lc.foliocobranza FROM liquidacioncobranza  lc
		INNER JOIN relacioncobranza rc ON lc.foliocobranza=rc.folio
		WHERE lc.estado='LIQUIDADO' AND lc.sucursal = ".$_SESSION[IDSUCURSAL].")";
	}else if($_GET[cobranza]!=""){
		$criterio = " WHERE rc.sucursal = ".$_SESSION[IDSUCURSAL]."";
	}

	$get = @mysql_query("SELECT COUNT(*) FROM relacioncobranza rc $criterio");
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr> 
    <td width="9%" class="FondoTabla">Folio</td>
    <td width="21%" class="FondoTabla">Fecha</td>
    <td width="56%" class="FondoTabla">Cobrador</td>
    <td width="14%" class="FondoTabla">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="5" class="Tablas" height="300px" valign="top"> 
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
    <?
		$sql = @mysql_query("SELECT rc.folio, DATE_FORMAT(rc.fecharelacion,'%d/%m/%Y') AS fecharelacion,
		CONCAT(ce.nombre,' ',ce.apellidopaterno,' ',ce.apellidomaterno)AS cobrador FROM relacioncobranza rc
		INNER JOIN catalogoempleado ce ON rc.cobrador=ce.id $criterio order by rc.fecharelacion limit $st,$pp",$link);


		while($row=@mysql_fetch_array($sql)){
	?>
          <tr> 
            <td width="43"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[0]?>'); parent.VentanaModal.cerrar();"> 
              <?= $row[0];?>
              </span></td>
            <td width="104" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row[1]?>" readonly="true" style="width:100px; border:none; cursor:default"></td>
            <td width="307" class="Tablas"><input class="Tablas" name="descripcion2" type="text" value="<?=$row[2]?>" readonly="true" style="width:300px; border:none; cursor:default" /></td>
            <td width="42"></td>
          </tr>
      <? } ?>
        </table>
      </td>
  </tr>
  <tr> 
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioCobranzaGen.php?funcion=$_GET[funcion]&sucursal=$_GET[sucursal]&criterio=$_GET[criterio]&cobranza=$_GET[cobranza]&st="); ?></font></td>
  </tr>
</table>
