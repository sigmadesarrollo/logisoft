<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$sucursal = (($_GET[sucursal]!="")? " WHERE idsucursal=".$_GET[sucursal]."" : "");	
	$get = @mysql_query("SELECT COUNT(*) FROM moduloquejasdanosfaltantes $sucursal");
	
	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr> 
    <td width="14%" class="FondoTabla">Folio</td>
    <td width="40%" class="FondoTabla">Guia</td>    
    <td width="46%" class="FondoTabla">Fecha</td>
  </tr>
  <tr> 
    <td colspan="6" class="Tablas" height="300px" valign="top"> 
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
     <?
		$sql = @mysql_query("SELECT folio, nguia, date_format(fecharegistro,'%d/%m/%Y') as fecharegistro 
		FROM moduloquejasdanosfaltantes $sucursal limit $st,$pp",$link);
		while($row=@mysql_fetch_array($sql)){
	?>
          <tr> 
            <td width="68"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[0]?>'); parent.VentanaModal.cerrar();"> 
              <?= $row[0];?>
              </span></td>
            <td width="201" class="Tablas"><input name="guia" type="text" class="Tablas" id="guia" style="width:150px; border:none; cursor:default" value="<?=$row[1]?>" readonly="true"></td>
            <td width="171" class="Tablas"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:150px; border:none; cursor:default" value="<?=$row[2]?>" readonly="true" /></td>
            <td width="56"></td>
          </tr>
      <? } ?>
        </table>
      </td>
  </tr>
  <tr> 
    <td colspan="6" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolioModuloQuejas.php?funcion=$_GET[funcion]&sucursal=$_GET[sucursal]&st="); ?></font></td>
  </tr>
</table>
