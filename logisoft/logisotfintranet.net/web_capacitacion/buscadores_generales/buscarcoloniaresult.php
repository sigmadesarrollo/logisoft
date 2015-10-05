<?
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$colonia=$_GET['colonia'];
?>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" id="tab">
  <?	
		if($colonia!=""){		
		$get = mysql_query("SELECT cpo.codigopostal, cc.id as idcol, 
		cc.descripcion As colonia, cp.id as idpob, cp.descripcion as poblacion,
		cm.id as idmun, cm.descripcion as municipio, ce.id as idest, 
		ce.descripcion as estado, cpa.id as idpais, cpa.descripcion as pais FROM 
		catalogocolonia cc
		INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal
		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
		INNER JOIN catalogoestado ce ON cm.estado=ce.id
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id WHERE cc.descripcion like '".$colonia."%'",$link);
		
			if(mysql_num_rows($get)<1){
				die("No se encontro ninguna colonia");
			}
		}
		while($row=@mysql_fetch_array($get)){
	?> 
	<tr onClick="opener.document.all.cp.value='<?=$row[codigopostal];?>'; opener.buscarCodigo(); parent.document.all.abierto.value=''; window.close();" >
    <td width="112" class="Tablas"><input name="text3" type="text" style="cursor:pointer; border:none; font-size:9px; " class="Tablas" value="<?=$row[colonia];?>" size="25" readonly=""  /></td>
    <td width="31" class="Tablas"><input class="Tablas" name="text3" type="text" style="cursor:pointer; border:none; font-size:9px; text-transform:uppercase" value="<?=$row[codigopostal];?>" size="4" readonly=""></td>
    <td width="56" class="Tablas">
        <input name="text3" type="text" class="Tablas" style="cursor:pointer; border:none; font-size:9px; " value="<?=$row[poblacion];?>" size="9" readonly=""  /></td>
    <td width="54" class="Tablas">
        <input name="text3" type="text" class="Tablas" style="cursor:pointer; border:none; font-size:9px; " value="<?=$row[municipio];?>" size="9" readonly=""  /></td>
    <td width="93" class="Tablas">&nbsp;
        <input name="text3" type="text" class="Tablas" style="cursor:pointer; border:none; font-size:9px; " value="<?=$row[estado];?>" size="9" readonly=""  /></td>
    <td width="10"></td>
  </tr>
  <? } ?>
</table>

