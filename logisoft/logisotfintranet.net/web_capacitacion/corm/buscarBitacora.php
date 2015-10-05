<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');	
	$get = @mysql_query('select count(*) from bitacorasalida where cancelada=0 
	'.(($_SESSION[IDSUCURSAL]!=1)? ' AND sucursal='.$_SESSION[IDSUCURSAL].'' :'').'');	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="10%" class="FondoTabla">ID</td>
    <td width="90%" class="FondoTabla">Fecha</td>
  </tr>
  <tr>
    <td colspan="3" class="Tablas" height="300px" valign="top"  >
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
        <?		
		$get = mysql_query("SELECT folio, DATE_FORMAT(fechabitacora,'%d/%m/%Y') AS fechabitacora
		 FROM bitacorasalida
		 where cancelada=0 ".(($_SESSION[IDSUCURSAL]!=1)? " AND sucursal=$_SESSION[IDSUCURSAL]":"")."
		 limit ".$st.",".$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="49"><span style="cursor:pointer;color:#0000FF" onClick="window.parent.obtener('<?=$row['folio'];?>');parent.VentanaModal.cerrar();">
            <?= $row['folio'];?>
          </span></td>
          <td width="405" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['fechabitacora']; ?>" readonly="true" style="width:300px; border:none; cursor:default"></td>         
          <td width="42"></td>
        </tr>
        <? } ?>
      </table>
   </td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, 'buscarBitacora.php?st='); ?></font></td>
  </tr>
</table>
</body>
</html>