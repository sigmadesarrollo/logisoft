<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$usuario=$_SESSION[NOMBREUSUARIO];
	$prospecto=$_GET['prospecto'];
	$cp=$_GET['cp'];	
	$tipo=$_GET['tipo'];	
	$colonia=$_GET['colonia'];
	$poblacion=$_GET['poblacion'];
	$municipio=$_GET['municipio'];
	$estado=$_GET['estado'];
	$pais=$_GET['pais'];
?>


<? if($tipo==1){ ?>
<!---$TIPO 1 FILRO DE BUSQUEDA COLONIA -->
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<table width="95%" border="0" align="left" cellpadding="0" cellspacing="0" class="Tablas" id="tab">
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
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
		WHERE cc.descripcion like '".$_GET['colonia']."%'",$link);		
		}		
		while($row=@mysql_fetch_array($get)){
	?> 
<tr onClick="window.parent.OptenerBuscarColonia('<?=$row[codigopostal];?>','<?=$row[idcol]; ?>','<?=$row[colonia]; ?>','<?=$row[poblacion]; ?>','<?=$row[municipio]; ?>','<?=$row[estado]; ?>','<?=$row[pais]; ?>'); parent.VentanaModal.cerrar();" >	
    <td width="191" class="Tablas"><input name="text" type="text" style="cursor:pointer; border:none; font-size:9px; text-transform:uppercase" value="<?=htmlentities($row[colonia]);?>" size="25" readonly=""  /></td>
    <td width="20" class="Tablas"><input name="text3" type="text" style="cursor:pointer; border:none; font-size:9px; text-transform:uppercase" value="<?=htmlentities($row[codigopostal]);?>" size="3" readonly=""></td>
    <td width="72" class="Tablas">
        <input name="text3" type="text" style="cursor:pointer; border:none; font-size:9px; text-transform:uppercase" value="<?=htmlentities($row[poblacion]);?>" size="9" readonly=""  /></td>
    <td width="72" class="Tablas">
        <input name="text3" type="text" style="cursor:pointer; border:none; font-size:9px; text-transform:uppercase" value="<?=htmlentities($row[municipio]);?>" size="9" readonly=""  /></td>
    <td width="90" class="Tablas">&nbsp;
        <input name="text3" type="text" style="cursor:pointer; border:none; font-size:9px; text-transform:uppercase" value="<?=htmlentities($row[estado]);?>" size="9" readonly=""  /></td>
    <td width="3"></td>
  </tr>
  <? } ?>
</table>	
	
<p>
<? } ?>
  
  
 




