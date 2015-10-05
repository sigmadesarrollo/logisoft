<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');	
		
	$s = "SELECT COUNT(*) FROM asignacionprecintosdetalle d 
			INNER JOIN catalogosucursal cs ON d.sucursal=cs.id
			WHERE d.sucursal = $_GET[sucursal] AND utilizado=0 AND 
			".(($_GET[tipo]=="bitacora")? " NOT EXISTS (SELECT * FROM recepcionregistroprecintosdetalle_tmp r
			WHERE r.precinto = d.folios)" : " NOT EXISTS (SELECT * FROM recepcionregistroprecintosdetalle r
			WHERE r.precinto = d.folios)")." ";
	$get = @mysql_query($s,$link) or die($s);
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="350"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr> 
    <td width="27%" class="FondoTabla">Folio Precinto</td>
    <td width="73%" class="FondoTabla">Sucursal</td>
  </tr>
  <tr> 
    <td height="250px" colspan="5" valign="top" class="Tablas">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
		 	
	$sql = @mysql_query("SELECT d.folios, cs.prefijo as sucursal
			FROM asignacionprecintosdetalle d 
			INNER JOIN catalogosucursal cs ON d.sucursal=cs.id
			WHERE d.sucursal = $_GET[sucursal] AND utilizado=0 AND 
			".(($_GET[tipo]=="bitacora")? " NOT EXISTS (SELECT * FROM recepcionregistroprecintosdetalle_tmp r
			WHERE r.precinto = d.folios)" : " NOT EXISTS (SELECT * FROM recepcionregistroprecintosdetalle r
			WHERE r.precinto = d.folios)")."
			limit $st,$pp",$link);
		while($row=@mysql_fetch_array($sql)){
	?>
          <tr> 
            <td width="93"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[0]?>'); parent.VentanaModal.cerrar();"> 
              <?= $row[0];?>
              </span></td>
            <td width="212" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row[1]?>" readonly="true" style="width:150px; border:none; cursor:default"></td>            
            <td width="41"></td>
          </tr>
      <? } ?>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarPrecintosGen.php?sucursal=$_GET[sucursal]&funcion=$_GET[funcion]&st="); ?></font></td>
  </tr>
</table>
