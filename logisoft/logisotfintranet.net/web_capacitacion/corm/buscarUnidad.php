<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	/*if($_GET[nunidad]!=""){
		$unids = "'".str_replace(",","','",$_GET[nunidad])."'";
		$andunidad = " and u.numeroeconomico not in($unids) ";
		
	}*/
	
	if($_GET[validarconbitacora]==1){//bitacorasalida.php
		$inner = "LEFT JOIN bitacorasalida ON u.numeroeconomico = bitacorasalida.unidad
		LEFT JOIN catalogoruta ON  bitacorasalida.ruta=catalogoruta.id 
		where /*(bitacorasalida.status = 1 or isnull(bitacorasalida.status) ) AND*/ u.fueradeservicio=0
		AND u.enuso=0 ";

	}
	
	if($_GET[caja]==2 || $_GET[caja]==3){
		$and = " and u.tipounidad = 3 ";
	}
	
	$s = "select count(*) from catalogounidad u
			INNER JOIN catalogotipounidad t ON u.tipounidad = t.id and u.tiporuta='FORANEA'
			$and $andunidad $inner ";
	$get = @mysql_query($s,$link) or die($s."<br>".mysql_error()."<BR>".$s);	
	if(mysql_num_rows($get)>0){
		$total = mysql_result($get,0);
	}else{
		$total = 0;
	}
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20; 
?>

<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="7%" class="FondoTabla">ID</td>
    <td width="42%" class="FondoTabla">Descripci&oacute;n</td>
    <td width="43%" class="FondoTabla">No. Economico</td>
  </tr>
  <tr>
    <td height="300px" colspan="3" valign="top" class="Tablas" >
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <?
		$inner .= "  group by u.id";
		
		$get = mysql_query("SELECT u.id, t.descripcion, u.numeroeconomico FROM catalogounidad u
		INNER JOIN catalogotipounidad t ON u.tipounidad = t.id and u.tiporuta='FORANEA' $and
		$andunidad $inner order by u.numeroeconomico limit ".$st.",".$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="46"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtenerUnidadBusqueda('<?=$row['numeroeconomico']; ?>','<?=$_GET['caja'];?>');parent.VentanaModal.cerrar();">
            <?= $row['id'];?>
          </span></td>
          <td width="223" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['descripcion']; ?>" readonly="true" style="width:200px; border:none; cursor:default"></td>
          <td width="203" class="Tablas"><input class="Tablas" name="economico" type="text" value="<?=$row['numeroeconomico']; ?>" readonly="true" style="width:170px; border:none; cursor:default"></td>
          <td width="24"></td>
        </tr>
        <? } ?>
      </table>
      <p class="Tablas">&nbsp;</p></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarUnidad.php?validarconbitacora=$_GET[validarconbitacora]&nunidad=$_GET[nunidad]&caja=$_GET[caja]&st="); ?></font></td>
  </tr>
</table>
