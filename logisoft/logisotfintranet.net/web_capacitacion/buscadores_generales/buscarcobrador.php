<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$get = @mysql_query("select count(*) from catalogoempleado ce  INNER JOIN catalogopuesto cp ON ce.puesto=cp.id
WHERE cp.descripcion='MENSAJERO'");	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="10%" class="FondoTabla">ID</td>
    <td width="90%" class="FondoTabla">Nombre</td>
  </tr>
  <tr>
    <td colspan="3" height="300px" valign="top">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <?		
		$get = mysql_query("SELECT ce.id,CONCAT(ce.nombre,'',ce.apellidopaterno,'',ce.apellidomaterno)AS nombre FROM catalogoempleado ce
INNER JOIN catalogopuesto cp ON ce.puesto=cp.id
WHERE cp.descripcion='MENSAJERO' limit ".$st.",".$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="49"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtenercConductorBusqueda('<?=$row['id'];?>','<?=$_GET['caja']; ?>');parent.VentanaModal.cerrar();">
            <?= $row['id'];?>
          </span></td>
          <td width="405" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['nombre']; ?>" readonly="true" style="width:300px; border:none; cursor:default"></td>         
          <td width="42"></td>
        </tr>
        <? } ?>
      </table>
   </td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarcobrador.php?st='); ?></font></td>
  </tr>
</table>