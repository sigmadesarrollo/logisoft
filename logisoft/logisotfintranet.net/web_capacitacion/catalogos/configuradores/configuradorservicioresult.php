<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}/*
if(isset($_SESSION['gvalidar'])!=100){echo"<script language='javascript' type='text/javascript'>			document.location.href='../index.php';</script>";}else{*/
header('Content-type: text/xml');
	include('../../Conectar.php');	
	$link=Conectarse('webpmm');	
	$tipo=$_GET['tipo'];
	$id=$_GET['id']; $servicio=$_GET[servicio];
	if($tipo=="modificar"){
		$sql=@mysql_query("SELECT id, servicio, condicion, costo, costoextra, limite, porcada FROM configuradorservicios WHERE id='$id'",$link);
		$row=@mysql_fetch_array($sql);
$id=$row[0]; $slServicio=$row[1]; $condicion=$row[2]; $costo=$row[3]; $costoextra=$row[4]; $limite=$row[5]; $porcada=$row[6];
		$accion="modificar";
	}else if($tipo=="eliminar"){
		$sql=@mysql_query("DELETE FROM configuradorservicios WHERE id='$id'",$link);
	}
	if($accion==1){
$s="SELECT servicio FROM configuradorservicios WHERE servicio=$servicio";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
		if($f->servicio==""){$f->servicio="NO";}else{$f->servicio="SI";}
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<servicio>$f->servicio</servicio>			
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}
		echo $xml;
	}

?>
<? if($tipo=="modificar"){ ?>
<style type="text/css">
<!--
.style2 {color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {font-size: 9px;
	color: #464442;
}
-->
</style>

<table width="445" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="65" class="Tablas">Servicio:</td>
    <td width="175"><label>
      <select name="slServicio" class="Tablas">
        <option selected="selected">SELECCIONAR SERVICIO</option>
        <? $sql=@mysql_query("SELECT * FROM catalogoservicio",$link);
				  while($row=mysql_fetch_array($sql)){
				   ?>
        <option value="<?=$row[0];?>" <? if($row[0]==$slServicio){ echo 'selected'; } ?>>
          <?=$row[1];?>
        </option>
        <? } ?>
      </select>
    </label></td>
    <td width="88" class="Tablas"><input name="condicion" type="checkbox" id="condicion" onKeyPress="return tabular(event,this)" onClick="Habilitar();" value="1" <? if($condicion==1){ echo 'checked'; } ?>>
      Condici&oacute;n </td>
    <td width="120">&nbsp;</td>
  </tr>
  <tr>
    <td class="Tablas">Costo:</td>
    <td class="Tablas"><input name="costo" type="text" class="Tablas" id="costo" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('costoextra').value,'costoextra');" onKeyPress="return tabular(event,this)" value="<?=$costo ?>" size="20" /></td>
    <td class="Tablas">Limite: </td>
    <td><span class="Tablas">
      <input name="limite" type="text" disabled="disabled" class="Tablas" id="limite" style="font-size:9px; font:tahoma; background:#FFFF99" onBlur="trim(document.getElementById('limite').value,'limite');" onKeyPress="return tabular(event,this)" value="<?=$limite ?>" size="20" />
    </span></td>
  </tr>
  <tr>
    <td class="Tablas">Por Cada: </td>
    <td><input name="porcada" type="text" disabled="disabled" class="Tablas" id="porcada" style="font-size:9px; font:tahoma;background:#FFFF99" onBlur="trim(document.getElementById('porcada').value,'porcada');" onKeyPress="return tabular(event,this)" value="<?=$porcada ?>" size="20" /></td>
    <td class="Tablas">Costo Extra: </td>
    <td><input name="costoextra" type="text" disabled="disabled" class="Tablas" id="costoextra" style="font-size:9px; font:tahoma;background:#FFFF99" onBlur="trim(document.getElementById('costoextra').value,'costoextra');" onKeyPress="return tabular(event,this)" value="<?=$costoextra ?>" size="20" /></td>
  </tr>
</table>
<? }else if($tipo=="eliminar"){ ?>
<div id="detalle" name="detalle" style=" height:70px; overflow:auto" align="left">
<? $line = 0; ?>
<table width="427" border="0" cellspacing="0" cellpadding="0">
  <?
		$sql=@mysql_query("SELECT cs.id, css.descripcion AS servicio, cs.condicion, cs.costo, cs.limite, cs.porcada, cs.costoextra FROM configuradorservicios cs INNER JOIN catalogoservicio css ON cs.servicio=css.id",$link);						
			if(@mysql_num_rows($sql)>0){
			$linea=@mysql_num_rows($sql);
			while($row=@mysql_fetch_array($sql)){?>
  <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" >
    <td height="16" width="21" ><input name="id" type="hidden" value="<?=$row[0] ?>" /></td>
    <td width="57" align="center" class="style31"  ><img src="img/delete.png" width="16" height="16" alt="Borrar" onclick="ObtenerId(<?=$row[0] ?>); confirmar('&iquest;Esta seguro de Eliminar el Servicio?', '', 'window.parent.Eliminar();', 'parent.VentanaModal.cerrar();')" /><img src="img/update.gif" width="16" height="16" alt="Modificar" onclick="Modificar(<?=$row[0] ?>)"></td>
    <td width="99" align="center" class="style31"  ><input name="descripcion" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[1]; ?>" readonly="" size="20" /></td>
    <td width="62" align="center" class="style31"><input name="peso" type="text" class="style2" id="peso" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[3]; ?>" readonly="" size="8" /></td>
    <td width="52" class="style31" align="center"><input name="largo" type="text" readonly="" class="style2" id="largo" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[4]; ?>" size="5" /></td>
    <td width="62" class="style31" align="center"><input name="ancho" type="text" readonly="" class="style2" id="ancho" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[5] ?>" size="5" /></td>
    <td width="74" class="style31" align="center"><input name="alto" type="text" class="style2" id="alto" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[6] ?>" size="5" /></td>
  </tr>
  <?
		$line ++ ; }	
			}else{ $msg="";echo"<input name='msg' type='hidden' value='".$msg."'>";
			while($line<=200){?>
  <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
    <td height="16" width="21" ><input name="id" type="hidden" id="id" value="<?=$row[id] ?>" /></td>
    <td width="57" align="center" class="style31"  >&nbsp;</td>
    <td align="center" class="style31"  ><input name="descripcion" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[descripcion]) ?>" readonly="" size="20" /></td>
    <td width="62" align="center" class="style31"><input name="peso" type="text" class="style2" id="peso" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[peso]) ?>" readonly="" size="8" /></td>
    <td width="52" class="style31" align="center"><input name="largo" type="text" readonly="" class="style2" id="largo" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[largo]) ?>" size="5" /></td>
    <td width="62" class="style31" align="center"><input name="ancho" type="text" readonly="" class="style2" id="ancho" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[ancho]) ?>" size="5" /></td>
    <td width="74" class="style31" align="center"><input name="alto" type="text" class="style2" id="alto" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[alto]) ?>" size="5" /></td>
  </tr>
  <?
		$line ++ ; }	
			}
			
	?>
</table>
</div>
<? } ?>