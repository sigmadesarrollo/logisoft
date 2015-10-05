<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');	
	//$criterio = ($_GET[criterio]=="") ? "" : "WHERE NOT EXISTS (SELECT * FROM notacredito)";
	
	$criterio ="";
	
	if($_GET[valorx]==""){
		$criterio .= " and usada = 'N' ";
	}
	
	if($_GET[cliente]!=""){
		$criterio .= " and cliente = '$_GET[cliente]' ";
	}
	
	$get = @mysql_query("SELECT COUNT(*) FROM notacredito WHERE 1=1 $criterio");
	
	$total = mysql_result($get,0);
	if(isset($_GET['st'])){ $st = $_GET['st']; }else{ $st = 0; }
	$pp = 20;
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<table width="500"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr> 
    <td width="9%" class="FondoTabla">Folio</td>
    <td width="21%" class="FondoTabla">Fecha</td>
    <td width="56%" class="FondoTabla">Estado</td>
    <td width="14%" class="FondoTabla">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="5" class="Tablas" height="300px" valign="top">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?
		  $s = "SELECT folio, DATE_FORMAT(fechanotacredito,'%d/%m/%Y') AS fecha, 'GUARDADO' AS estado FROM notacredito  WHERE 1=1 $criterio limit $st,$pp";
	$sql = @mysql_query($s,$link);
		
		  	
		
		while($row=@mysql_fetch_array($sql)){
	?>
          <tr> 
            <td width="43"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[0]?>'); parent.VentanaModal.cerrar();"> 
              <?= $row[0];?>
              </span></td>
            <td width="106" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row[1]?>" readonly="true" style="width:100px; border:none; cursor:default"></td>
            <td width="111" class="Tablas"><input name="estado" type="text" class="Tablas" id="estado" style="width:100px; border:none; cursor:default" value="<?=$row[2]?>" readonly="true" /></td>
            <td width="236"></td>
          </tr>
      <? } ?>
        </table>
      </td>
  </tr>
  <tr> 
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarFolionotacredito.php?funcion=$_GET[funcion]&valorx=$_GET[valorx]&sucursal=$_GET[sucursal]&cliente=$_GET[cliente]&st="); ?></font></td>
  </tr>
</table>
