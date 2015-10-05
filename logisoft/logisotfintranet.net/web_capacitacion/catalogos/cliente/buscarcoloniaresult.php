<?	include('../../Conectar.php');
	$link=Conectarse('webpmm');
?>
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" id="tab">
  <?	
		if(!empty($_GET[colonia]) || !empty($_GET[ciudad])){
			$get = mysql_query("SELECT cpo.codigopostal, cc.id as idcol, 
			cc.descripcion As colonia, cp.id as idpob, cp.descripcion as poblacion,
			cm.id as idmun, cm.descripcion as municipio, ce.id as idest, 
			ce.descripcion as estado, cpa.id as idpais, cpa.descripcion as pais FROM 
			catalogocolonia cc
			INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal
			INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
			INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
			INNER JOIN catalogoestado ce ON cm.estado=ce.id
			INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
			WHERE cc.descripcion like '%".$_GET['colonia']."%' AND cp.descripcion LIKE '%".$_GET['ciudad']."%'",$link);
			if(mysql_num_rows($get)<1){
				die("No se encontro ninguna colonia");
			}
		}
		while($row=@mysql_fetch_array($get)){
	?> 	
	<tr onClick="try{window.opener.ObtenerColoniaClic('<?=$row[codigopostal];?>','<?=$row[colonia];?>','<?=$row[poblacion];?>','<?=$row[municipio];?>','<?=$row[estado];?>','<?=$row[pais];?>');LimpiarMensaje();window.close();}catch(e){ opener.document.all.cp.value='<?=$row[codigopostal];?>'; opener.buscarCodigo(); opener.document.all.abierto.value=''; window.close();}" >
    <td width="112" class="Tablas"><input name="text3" type="text" style="cursor:pointer; border:none; font-size:9px; width:100px" class="Tablas" value="<?=$row[colonia];?>" readonly=""  /></td>
    <td width="31" class="Tablas"><input class="Tablas" name="text3" type="text" style="cursor:pointer; border:none; font-size:9px; width:45px; text-transform:uppercase" value="<?=$row[codigopostal];?>" size="4" readonly=""></td>
    <td width="56" class="Tablas"><input name="text3" type="text" class="Tablas" style="cursor:pointer; border:none; font-size:9px; width:80px " value="<?=$row[poblacion];?>"  readonly=""  /></td>
    <td width="54" class="Tablas"><input name="text3" type="text" class="Tablas" style="cursor:pointer; border:none; font-size:9px;  width:80px" value="<?=$row[municipio];?>"  readonly=""  /></td>
    <td width="93" class="Tablas"><input name="text3" type="text" class="Tablas" style="cursor:pointer; border:none; font-size:9px;  width:80px" value="<?=$row[estado];?>"  readonly=""  /></td>
    <td width="10"></td>
  </tr>
  <? } ?>
</table>

