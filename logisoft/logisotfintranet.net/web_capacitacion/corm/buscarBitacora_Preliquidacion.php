<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$get = @mysql_query('select count(*) from bitacorasalida where preliquidaciondebitacora=0');	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>


<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="15%" class="FondoTabla">#Bitacora</td>
    <td width="21%" class="FondoTabla">Fecha</td>
    <td width="64%" class="FondoTabla">Estado</td>
  </tr>
  <tr>
    <td colspan="4" height="300px" valign="top" >
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <?		
$get = mysql_query("SELECT folio, date_format(fechabitacora,'%d/%m/%Y')AS fechabitacora,'NO PRE-LIQUIDADO' AS estado FROM bitacorasalida where preliquidaciondebitacora=0 limit ".$st.",".$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="73"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtener('<?=$row['folio'];?>');parent.VentanaModal.cerrar();">
            <?= $row['folio'];?>
          </span></td>
          <td width="381" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['fechabitacora']; ?>" readonly="true" style="width:100px; border:none; cursor:default">
          <input name="estado" type="text" class="Tablas" id="estado" style="width:150px; border:none; cursor:default" value="<?=$row['estado']; ?>" readonly="true" /></td>         
          <td width="42"></td>
        </tr>
        <? } ?>
      </table>    
      <p class="Tablas">&nbsp;</p></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarBitacora_Preliquidacion.php?st='); ?></font></td>
  </tr>
</table>
