<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');	
	
	$get = @mysql_query("SELECT COUNT(*) FROM liquidacionead where sucursal = $_SESSION[IDSUCURSAL] and tipoliquidacion = '$_GET[tipo]'");
	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr> 
    <td width="9%" class="FondoTabla">Folio</td>
    <td width="24%" class="FondoTabla">Folio Reparto Ead </td>
    <td width="22%" class="FondoTabla">Fecha</td>
    <td width="45%" class="FondoTabla">Estado</td>
  </tr>
  <tr> 
    <td height="300px" colspan="5" valign="top" class="Tablas">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
	$sql = @mysql_query("SELECT folio,idreparto,DATE_FORMAT(fecha,'%d/%m/%Y')AS fecha,if(cerro=1,'CERRADO','NO CERRADO')as estado 
						FROM liquidacionead where sucursal = $_SESSION[IDSUCURSAL] and tipoliquidacion = '$_GET[tipo]' limit $st,$pp",$link);
		while($row=@mysql_fetch_array($sql)){
	?>
          <tr> 
            <td width="36"><div align="center"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[0]?>'); try{ parent.VentanaModal.cerrar(); }catch(e){ parent.mens.cerrar();}"> 
              <?= $row[0];?>
            </span></div></td>
            <td width="128" class="Tablas"><div align="center">
              <input class="Tablas" name="descripcion" type="text" value="<?=$row[1]?>" readonly="true" style="width:100px; border:none; cursor:default">
            </div></td>
            <td width="109" class="Tablas"><input class="Tablas" name="descripcion2" type="text" value="<?=$row[2]?>" readonly="true" style="width:100px; border:none; cursor:default" /></td>
            <td width="223"><input class="Tablas" name="descripcion3" type="text" value="<?=$row[3]?>" readonly="true" style="width:150px; border:none; cursor:default" /></td>
          </tr>
      <? } ?>
        </table>
    </td>
  </tr>
  <tr> 
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarliquidacionmercancia.php?funcion=$_GET[funcion]&tipo=$_GET[tipo]&st="); ?></font></td>
  </tr>
</table>
