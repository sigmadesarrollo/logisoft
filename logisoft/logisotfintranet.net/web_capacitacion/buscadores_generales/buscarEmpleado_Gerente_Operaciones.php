<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	if($_GET[tipo]==1){
		$condicion = " WHERE puesto=12 ";
	}

	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	$get = @mysql_query("select count(*) from catalogoempleado  $condicion");	
	$total = mysql_result($get,0) + $valor;
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
    <td height="300px" colspan="3" valign="top" class="Tablas">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <?		
		$get = mysql_query("SELECT id, CONCAT(nombre,' ',apellidopaterno,' ',apellidomaterno) as nombre FROM catalogoempleado  $condicion limit ".$st.",".$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="49"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET[funcion]?>('<?=$row['id'];?>'); parent.VentanaModal.cerrar();">
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
    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarEmpleado.php?funcion=$_GET[funcion]&tipo=$_GET[tipo]&st="); ?></font></td>
  </tr>
</table>
