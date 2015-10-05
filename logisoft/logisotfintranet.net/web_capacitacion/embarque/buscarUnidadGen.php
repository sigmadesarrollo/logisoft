<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
	
	if($_GET[validarconbitacora]==1){
	$get = @mysql_query("SELECT COUNT(*) FROM catalogounidad AS CU 
			INNER JOIN catalogotipounidad AS CTU ON CTU.id=CU.tipounidad
			INNER JOIN bitacorasalida ON CU.numeroeconomico = bitacorasalida.unidad
			INNER JOIN catalogoruta ON bitacorasalida.ruta=catalogoruta.id AND bitacorasalida.status = 0");
	}else if($_GET[validarconrecepcion]==1){
	$get = @mysql_query("SELECT COUNT(*) FROM catalogounidad AS CU 
			INNER JOIN catalogotipounidad AS CTU ON CTU.id=CU.tipounidad
			INNER JOIN programacionrecepciondiaria p ON CU.numeroeconomico = p.unidad
			WHERE p.sucursal=".$_GET[sucursal]." and p.recibida='N' AND p.hrllegada<>'00:00:00'");		
	}else{
	$get = @mysql_query("SELECT COUNT(*) FROM catalogounidad AS CU 
			INNER JOIN catalogotipounidad AS CTU ON CTU.id=CU.tipounidad");	
	}	
	
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
    <td height="310px" colspan="5" valign="top" class="Tablas"> 
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="Tablas">
          <?	
		  
		  if($_GET[validarconbitacora]==1){
		  
		  	
	$sql = @mysql_query("SELECT CU.id, CU.numeroeconomico, bitacorasalida.folio,
			CU.tipounidad AS idtipounidad,CTU.descripcion AS tipounidad
			FROM catalogounidad AS CU 
			INNER JOIN catalogotipounidad AS CTU ON CTU.id=CU.tipounidad
			INNER JOIN bitacorasalida ON CU.numeroeconomico = bitacorasalida.unidad
			INNER JOIN catalogoruta ON bitacorasalida.ruta=catalogoruta.id AND bitacorasalida.status = 0 limit $st,$pp",$link);
		
	}else if($_GET[validarconrecepcion]==1){
	$sql = @mysql_query("SELECT DISTINCT CU.id, CU.numeroeconomico,
			CU.tipounidad AS idtipounidad,CTU.descripcion AS tipounidad
			FROM catalogounidad AS CU 
			INNER JOIN catalogotipounidad AS CTU ON CTU.id=CU.tipounidad
			INNER JOIN programacionrecepciondiaria p ON CU.numeroeconomico = p.unidad
			WHERE p.sucursal=".$_GET[sucursal]." and p.recibida='N'
			limit $st,$pp",$link);		
	}else{
	$sql = @mysql_query("SELECT CU.id, CU.numeroeconomico,
		CU.tipounidad AS idtipounidad,CTU.descripcion AS tipounidad
		FROM catalogounidad AS CU 
		INNER JOIN catalogotipounidad AS CTU ON CTU.id=CU.tipounidad
		limit $st,$pp",$link);
	}	
		  	
		
		while($row=@mysql_fetch_array($sql)){
			
			if($_GET[validarconbitacora]==1){
				
				$s = "SELECT prd.sucursal AS sucursal, prd.hrllegada,prd.hrsalida 
				FROM programacionrecepciondiaria AS prd
				where prd.idbitacora = '$row[folio]'
				order by prd.folio desc limit 1";
				$rr = mysql_query($s,$link) or die($s);
				$ff = mysql_fetch_object($rr);
				//echo $s;
				$s = "SELECT crd.sucursal 
				FROM catalogorutadetalle AS crd 
				INNER JOIN bitacorasalida AS bs ON crd.ruta = bs.ruta AND bs.status = 0
				WHERE bs.folio = '$row[folio]'
				ORDER BY id ASC LIMIT 1";
				//echo $s;
				$rrx = mysql_query($s,$link) or die($s);
				$ffx = mysql_fetch_object($rrx);
				
				echo "<br>($ff->sucursal == $_SESSION[IDSUCURSAL] && $ff->hrllegada!='00:00:00') || ($ff->hrsalida=='' && $ffx->sucursal==$_SESSION[IDSUCURSAL])<br>";
				
				if(($ff->sucursal == $_SESSION[IDSUCURSAL] && $ff->hrllegada!='00:00:00') || (($ff->hrsalida=="" || $ff->hrsalida=="00:00:00") && $ffx->sucursal==$_SESSION[IDSUCURSAL])){
				
		?>
			<tr> 
            <td width="43"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[1]?>'); parent.VentanaModal.cerrar();"> 
              <?= $row['id'];?>
              </span></td>
            <td width="205" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['tipounidad']?>" readonly="true" style="width:200px; border:none; cursor:default"></td>
            <td width="206" class="Tablas"><input name="neconomico" type="text" class="Tablas" id="neconomico" style="width:200px; border:none; cursor:default" value="<?=$row['numeroeconomico']?>" readonly="true"></td>
            <td width="42"></td>
          </tr>
		<?
				}
			}else{
	?>
          <tr> 
            <td width="43"><span style="cursor:pointer;color:#0000FF" onclick="parent.<?=$_GET['funcion']?>('<?=$row[1]?>'); parent.VentanaModal.cerrar();"> 
              <?= $row['id'];?>
              </span></td>
            <td width="205" class="Tablas"><input class="Tablas" name="descripcion" type="text" value="<?=$row['tipounidad']?>" readonly="true" style="width:200px; border:none; cursor:default"></td>
            <td width="206" class="Tablas"><input name="neconomico" type="text" class="Tablas" id="neconomico" style="width:200px; border:none; cursor:default" value="<?=$row['numeroeconomico']?>" readonly="true"></td>
            <td width="42"></td>
          </tr>
      <? 	}
	  	} ?>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan="5" align="center"><font color="#FF0000" size="-1"><? echo paginacion($total, $pp, $st, "buscarUnidadGen.php?validarconbitacora=$_GET[validarconbitacora]&validarconrecepcion=$_GET[validarconrecepcion]&funcion=$_GET[funcion]&sucursal=$_GET[sucursal]&st="); ?></font></td>
  </tr>
</table>
