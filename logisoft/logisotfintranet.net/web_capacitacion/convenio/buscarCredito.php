<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
		switch ($_GET['accion']) {
			case 1:
		$get = @mysql_query('select count(*) from solicitudcredito sc
			INNER JOIN catalogosucursal cs ON sc.idsucursal = cs.id
			WHERE sc.estado="EN AUTORIZACION"');		
			break;
			case 2:
		$get = @mysql_query('select count(*) from solicitudcredito sc
			INNER JOIN catalogosucursal cs ON sc.idsucursal = cs.id
			WHERE sc.estado="AUTORIZADO"');			
			break;
			case 3:
		$get = @mysql_query('select count(*) from solicitudcredito sc
			INNER JOIN catalogosucursal cs ON sc.idsucursal = cs.id
			WHERE sc.estado="ACTIVADO"');			
			break; 
		}		
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="Tablas.css" rel="stylesheet" type="text/css">

<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="10%" class="FondoTabla">FOLIO</td>
    <td width="20%" class="FondoTabla">FECHA</td>
    <td width="25%" class="FondoTabla">SUC. SOLICITANTE</td>
    <td width="45%" class="FondoTabla">CLIENTE</td>
  </tr>
  <tr>
    <td colspan="4" height="300px" valign="top" >
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
        <?		
		
		switch ($_GET['accion']) {
			case 1:
	$get = mysql_query("SELECT sc.folio, sc.fechasolicitud, cs.prefijo AS sucursal,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente FROM solicitudcredito sc
			INNER JOIN catalogosucursal cs ON sc.idsucursal = cs.id
			INNER JOIN catalogocliente cc ON sc.cliente = cc.id
			WHERE sc.estado='EN AUTORIZACION' limit ".$st.",".$pp,$link);	
			break;
			case 2:
			$get = mysql_query("SELECT sc.folio, sc.fechasolicitud, cs.prefijo AS sucursal,			 
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente FROM solicitudcredito sc
		 	INNER JOIN catalogosucursal cs ON sc.idsucursal = cs.id
			INNER JOIN catalogocliente cc ON sc.cliente = cc.id
			WHERE sc.estado='AUTORIZADO' limit ".$st.",".$pp,$link);	
			break;
			case 3:
			$get = mysql_query("SELECT sc.folio, sc.fechasolicitud, cs.prefijo AS sucursal,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente			
			FROM solicitudcredito sc
			INNER JOIN catalogosucursal cs ON sc.idsucursal = cs.id
			INNER JOIN catalogocliente cc ON sc.cliente = cc.id
			WHERE sc.estado='ACTIVADO' OR sc.estado='BLOQUEADO' limit ".$st.",".$pp,$link);	
			break;
		}
		while($row=@mysql_fetch_array($get)){
	?>
        <tr>
          <td width="48"><span style="cursor:pointer;color:#0000FF" onclick="window.parent.obtener('<?=$row[0];?>');parent.VentanaModal.cerrar();">
            <?= $row[0];?>
          </span></td>
          <td width="99" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=cambiaf_a_normal($row[1]); ?>" readonly="true" style="width:80px; border:none; cursor:default"></td> 
           <td width="123" class="Tablas"><input class="Tablas" name="sucursal" type="text" value="<?=cambio_texto($row[2]); ?>" readonly="true" style="width:80px; border:none; cursor:default"></td>        
           <td width="183" class="Tablas"><input class="Tablas" name="sucursal2" type="text" value="<?=cambio_texto($row[3]); ?>" readonly="true" style="width:160px; border:none; cursor:default" /></td>
          <td width="43"></td>
        </tr>
        <? } ?>
    </table>    </td>
  </tr>
  <tr>
    <td colspan="4" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarCredito.php?accion=$_GET[accion]&st="); ?></font></td>
  </tr>
</table>
