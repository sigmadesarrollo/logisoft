<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	header('Content-type: text/xml');
	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');
	$tipo=$_GET['tipo']; $codigo=$_GET['codigo']; $cp=$_GET['cp']; $accion=$_GET['accion'];	
	if($accion==1){
	
	$s ="SELECT cs.id, cs.prefijo, cs.idsucursal, cs.descripcion, cs.concesion, cs.comision, cs.ventas, cs.recibido, cs.porcead, cs.ead, cs.precioead, cs.recoleccion, cs.preciorecoleccion, cs.porcrecoleccion, cs.trasbordo, cs.lectores, cs.iva, d.calle, d.numero, d.crucecalles, d.cp, d.colonia, d.poblacion, d.municipio, d.estado, d.pais, d.telefono, d.fax FROM catalogosucursal cs INNER JOIN direccion d ON cs.id=d.codigo AND d.origen='suc'
WHERE cs.id='$codigo' AND d.origen='suc' AND d.codigo='$codigo'";
	$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$cant = mysql_num_rows($r);
			if($f->fax==""){$f->fax=0;}	
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<id>$f->id</id>
			<prefijo>$f->prefijo</prefijo>
			<idsucursal>$f->idsucursal</idsucursal>
			<descripcion>".cambio_texto($f->descripcion)."</descripcion>
			<concesion>$f->concesion</concesion>
			<comision>$f->comision</comision>
			<ventas>$f->ventas</ventas>
			<recibido>$f->recibido</recibido>
			<porcead>$f->porcead</porcead>
			<recibido>$f->recibido</recibido>			
			<ead>$f->ead</ead>
			<precioead>$f->precioead</precioead>
			<recoleccion>$f->recoleccion</recoleccion>
			<preciorecoleccion>$f->preciorecoleccion</preciorecoleccion>			
			<porcrecoleccion>$f->porcrecoleccion</porcrecoleccion>
			<trasbordo>$f->trasbordo</trasbordo>
			<lectores>$f->recoleccion</lectores>
			<iva>$f->iva</iva>
			<calle>$f->calle</calle>
			<numero>$f->numero</numero>
			<cp>$f->cp</cp>
			<colonia>".cambio_texto($f->colonia)."</colonia>
			<estado>$f->estado</estado>
			<poblacion>$f->poblacion</poblacion>
			<municipio>$f->municipio</municipio>
			<pais>$f->pais</pais>
			<telefono>$f->telefono</telefono>
			<fax>$f->fax</fax>
			<encontro>$cant</encontro>
			</datos>
			</xml>";
		}else{
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
			<encontro>0</encontro>
			</datos>
			</xml>";
		}echo $xml;
	
	}else if($tipo=="cp"){
		$sql=mysql_query("SELECT cpo.codigopostal, cc.id as idcol, 
		cc.descripcion As colonia, cp.id as idpob, cp.descripcion as poblacion,
		cm.id as idmun, cm.descripcion as municipio, ce.id as idest, 
		ce.descripcion as estado, cpa.id as idpais, cpa.descripcion as pais FROM 
		catalogocolonia cc
		INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal
		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
		INNER JOIN catalogoestado ce ON cm.estado=ce.id
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
		WHERE cpo.codigopostal='$cp'",$link);			
		$row=mysql_fetch_array($sql); 		
		$poblacion=$row[poblacion]; $pais=$row[pais]; $estado=$row[estado]; $municipio=$row[municipio];
	}
	
	function mostrarColonia($cp){	
	$link=Conectarse('webpmm');
	$consulta=mysql_query("SELECT cpo.codigopostal, cc.id as idcol, 
		cc.descripcion As colonia, cp.id as idpob, cp.descripcion as poblacion,
		cm.id as idmun, cm.descripcion as municipio, ce.id as idest, 
		ce.descripcion as estado, cpa.id as idpais, cpa.descripcion as pais FROM 
		catalogocolonia cc
		INNER JOIN catalogocodigopostal cpo ON cc.cp=cpo.codigopostal
		INNER JOIN catalogopoblacion cp ON cc.poblacion=cp.id
		INNER JOIN catalogomunicipio cm ON cp.municipio=cm.id
		INNER JOIN catalogoestado ce ON cm.estado=ce.id
		INNER JOIN catalogopais cpa ON ce.pais=cpa.id 
		WHERE cpo.codigopostal='$cp'",$link);
	if($num_rows = mysql_num_rows($consulta)>1){
	echo "<select name='colonia' id='colonia'  style='width:122px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";
	echo "<option value=''>Seleccionar</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		echo "<option value='".htmlentities($registro[colonia])."'>".htmlentities($registro[colonia])."</option>";
	}
	echo "</select>";
	$poblacion=$registro[d_ciudad];	
	}else{
	$registro=mysql_fetch_row($consulta);
	echo "<input name='colonia' type='text' style='font:tahoma;font-size:9px;background:#FFFF99' disabled='disabled' id='colonia' size='20' value='".htmlentities($registro[colonia])."' />";
	}	
}
	
?>
<? if($tipo=="cp"){ ?>
<table width="499" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="79" class="Tablas">C.P.:</td>
    <td width="221"><input name="cp" type="text" id="cp" onblur="trim(document.getElementById('cp').value,'cp'); CodigoPostal(this.value); " onkeypress="return Numeros(event)" onkeydown="return tabular(event,this);" value="<?=$cp; ?>" size="20" maxlength="5" onkeyup="return validaCP(event,this.name)" style="font:tahoma;font-size:9px; text-transform:uppercase" /></td>
    <td width="66" class="Tablas">Colonia:</td>
    <td width="93" id="celcolonia"><? mostrarColonia($cp); ?></td>
    <td width="40"><img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('CatalogoSucursalBuscarColonia.php', 570, 350, 'ventana', 'Busqueda')" /></td>
  </tr>
  <tr>
    <td class="Tablas">Poblaci&oacute;n:</td>
    <td><input name="poblacion" type="text" id="poblacion" size="20"  style="font:tahoma;font-size:9px; background:#FFFF99" disabled="disabled"  value="<?= $poblacion; ?>" /></td>
    <td class="Tablas">Mun./Del.:</td>
    <td colspan="2"><input name="municipio" type="text" id="municipio" size="20"  style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled" value="<?= $municipio; ?>" /></td>
  </tr>
  <tr>
    <td class="Tablas">Estado:</td>
    <td><input name="estado" type="text" id="estado" size="20"  value="<?= $estado; ?>" style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled" /></td>
    <td class="Tablas">Pa&iacute;s:</td>
    <td colspan="2"><input name="pais" type="text" id="pais" size="20"  value="<?= $pais; ?>" style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled"/></td>
  </tr>
</table>
<? } ?>
 <?
if($tipo==3){
?>

<table width="480" border="0">
  <?	
		if($_GET['colonia']!=""){
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
  <tr class="Tablas" onclick="window.parent.CatalogoSucursalColonia('<?=$row[0];?>','<?=$row[2]; ?>','<?=$row[4]; ?>','<?=$row[6]; ?>','<?=$row[8]; ?>','<?=$row[10]; ?>');parent.VentanaModal.cerrar();" style="cursor:pointer"  >
    <td width="43%" ><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase; cursor:pointer" value="<?=$row[2] ?>" size="35" readonly="" /></td>
    <td width="6%"><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase; cursor:pointer" value="<?=$row[0] ?>" size="5" readonly="" /></td>
    <td width="17%"><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase;cursor:pointer " value="<?=$row[4] ?>" size="15" readonly="" /></td>
    <td width="17%"><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase;cursor:pointer" value="<?=$row[6] ?>" size="15" readonly="" /></td>
    <td width="17%"><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase;cursor:pointer" value="<?=$row[8] ?>" size="15" readonly="" /></td>
  </tr>
  <? } ?>
</table>
<? } ?>