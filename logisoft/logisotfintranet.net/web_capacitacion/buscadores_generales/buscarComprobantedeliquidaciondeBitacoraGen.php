<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$get = @mysql_query('select count(*) from comprobantedeliquidaciondebitacora WHERE sucursal = '.$_SESSION[IDSUCURSAL].'');	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
	
	if($_GET[tipo]==1){
		//$condicion="where isnull(status)";
		$condicion=" WHERE sucursal = ".$_SESSION[IDSUCURSAL]."";
	}
	
	
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="10%" class="FondoTabla">ID</td>
    <td width="22%" class="FondoTabla">Fecha</td>
    <td width="22%" class="FondoTabla">#Bitacora</td>
    <td width="46%" class="FondoTabla">Estado</td>
  </tr>
  <tr>
    <td colspan="5" height="300px" valign="top">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <?		
		$get = mysql_query("SELECT folio, DATE_FORMAT(fecha,'%d/%m/%Y') fecha,foliobitacora,
		IF(STATUS='COMPROBANTE LIQUIDACION','LIQUIDADO','NO LIQUIDADO') AS estado 
		FROM comprobantedeliquidaciondebitacora $condicion limit ".$st.",".$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="49"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.<?=$_GET[funcion]?>('<?=$row['folio'];?>');parent.VentanaModal.cerrar();">
            <?= $row['folio'];?>
          </span></td>
          <td width="110" class="Tablas"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px; border:none; cursor:default" value="<?=$row['fecha']; ?>" readonly="true"></td>         
          <td width="108" class="Tablas"><input class="Tablas" name="descripcion2" type="text" value="<?=$row['foliobitacora']; ?>" readonly="true" style="width:100px; border:none; cursor:default" /></td>
          <td width="229"><span class="Tablas">
            <input name="estado" type="text" class="Tablas" id="estado" style="width:100px; border:none; cursor:default" value="<?=$row['estado']; ?>" readonly="true" />
          </span></td>
        </tr>
        <? } ?>
      </table>   </td>
  </tr>
  <tr>
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarComprobantedeliquidaciondeBitacoraGen.php?funcion='.$_GET[funcion].'&tipo='.$_GET[tipo].'&st='); ?></font></td>
  </tr>
</table>
