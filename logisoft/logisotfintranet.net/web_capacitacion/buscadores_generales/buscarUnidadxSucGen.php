<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$get = @mysql_query("SELECT COUNT(*) FROM catalogounidad AS CU 
	INNER JOIN catalogotipounidad AS CTU ON CTU.id=CU.tipounidad 
	WHERE CU.sucursal=".$_GET[sucursal]." AND CU.fueradeservicio=0");	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr> 
    <td width="9%" class="FondoTabla">ID</td>
    <td width="41%" class="FondoTabla">Unidad</td>
    <td width="36%" class="FondoTabla">Numero Economico</td>
    <td width="14%" class="FondoTabla">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="5" height="300px" valign="top">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?		
		$sql = @mysql_query('SELECT CU.id, CU.numeroeconomico,CU.tipounidad AS idtipounidad,CTU.descripcion AS tipounidad
		FROM catalogounidad AS CU 
		INNER JOIN catalogotipounidad AS CTU ON CTU.id=CU.tipounidad
		WHERE CU.sucursal="'.$_GET[sucursal].'" AND CU.fueradeservicio=0 limit '.$st.','.$pp,$link);
		while($row=@mysql_fetch_array($sql)){
	?>
          <tr> 
            <td width="43"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[1]?>'); parent.VentanaModal.cerrar();"> 
              <?= $row['id'];?>
              </span></td>
            <td width="205" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['tipounidad']?>" readonly="true" style="width:200px; border:none; cursor:default"></td>
            <td width="206" class="Tablas"><input name="neconomico" type="text" class="Tablas" id="neconomico" style="width:200px; border:none; cursor:default" value="<?=$row['numeroeconomico']?>" readonly="true"></td>
            <td width="42"></td>
          </tr>
      <? } ?>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarUnidad.php?funcion=$_GET[funcion]&sucursal=$_GET[sucursal]&st="); ?></font></td>
  </tr>
</table>
