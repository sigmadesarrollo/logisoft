<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	$get = @mysql_query("SELECT count(distinct(pr.unidad))
		FROM programacionrecepciondiaria pr
		INNER JOIN bitacorasalida b ON pr.idbitacora = b.folio
		WHERE b.status = 0");
	
	if(mysql_num_rows($get)>0){
		$total = mysql_result($get,0);
		if(isset($_GET['st'])){ 
			$st = $_GET['st']; }else{ $st = 0; 
		}
		$pp = 20;
	}else{
		$total = 0;
		$pp = 20;
	}
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="5%" class="FondoTabla">&nbsp;</td>
    <td width="21%" class="FondoTabla">UNIDAD</td>
    <td width="20%" class="FondoTabla">FECHA</td>
    <td width="27%" class="FondoTabla"><p>BITACORA</p></td>
    <td width="27%" class="FondoTabla">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" class="Tablas" height="300px" valign="top">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
        <?		
		$get = mysql_query("SELECT pr.unidad, pr.idbitacora bitacora, date_format(pr.fecha, '%d/%m/%Y') fecha
		FROM programacionrecepciondiaria pr
		INNER JOIN bitacorasalida b ON pr.idbitacora = b.folio
		WHERE b.status = 0
		GROUP BY unidad
		limit ".$st.",".$pp,$link);
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="24">&nbsp;</td>
          <td width="103" class="Tablas" style="cursor:pointer" 
          	onClick="parent.<?=$_GET[funcion]?>('<?=$row['unidad']; ?>',<?=$row['bitacora']; ?>);parent.VentanaModal.cerrar();"><?=$row['unidad']; ?></td>          
          <td width="101" style="cursor:pointer" onClick="parent.<?=$_GET[funcion]?>('<?=$row['unidad']; ?>',<?=$row['bitacora']; ?>); parent.VentanaModal.cerrar();"><?=$row['fecha']; ?></td>
          <td width="268" style="cursor:pointer" onClick="parent.<?=$_GET[funcion]?>('<?=$row['unidad']; ?>',<?=$row['bitacora']; ?>); parent.VentanaModal.cerrar();"><?=$row['bitacora']; ?></td>
        </tr>
        <? } ?>
      </table>
   </td>
  </tr>
  <tr>
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? 
		echo paginacion($total, $pp, $st, "buscarUnidadEmbEspecial.php?funcion=$_GET[funcion]&tipo=$_GET[tipo]&validasucursal=$_GET[validasucursal]&st=" ); 
		?></font></td>
  </tr>
</table>
