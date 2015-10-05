<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
		header('Content-type: text/xml');
		include('../../Conectar.php');	
		$link=Conectarse('webpmm');
$usuario=$_SESSION[NOMBREUSUARIO]; $tipo=$_GET['tipo']; 	$cp=$_GET['cp']; $colonia=$_GET['colonia']; $poblacion=$_GET['poblacion']; $municipio=$_GET['municipio']; 		$estado=$_GET['estado']; $pais=$_GET['pais']; $id=$_GET['id'];
		

	if($tipo=='1'){
		//$sql=mysql_query("SELECT * FROM codigopostal WHERE d_codigo='$cp'",$link);
		$sql=@mysql_query("SELECT cpo.codigopostal, cc.id as idcol, 
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
		$poblacion=$row[poblacion];
		$pais=$row[pais];
		$estado=$row[estado];
		$municipio=$row[municipio];	
	
	}else if($tipo==4){
			$s="SELECT E.id,E.numempleado, E.sucursal, E.sexo, E.estadocivil, E.nombre, E.apellidopaterno,
			E.apellidomaterno, E.rfc, E.curp, E.nimss, E.celular, E.email, E.lugarnacimiento, E.user, E.password,
			DATE_FORMAT(E.fechanacimiento,'%d/%m/%Y')AS fechanacimiento,
			E.tipocontrato, DATE_FORMAT(E.alta,'%d/%m/%Y')AS alta,
			DATE_FORMAT(E.baja,'%d/%m/%Y')AS baja,
			DATE_FORMAT(E.reingreso,'%d/%m/%Y')AS reingreso,
			DATE_FORMAT(E.bajareingreso,'%d/%m/%Y')AS bajareingreso,
			E.departamento, E.turno, E.licenciamanejo, E.subcuentacontable,
			E.pagoelectronico, E.puesto, cp.descripcion as descripcionpuesto, DR.calle,
			DR.numero, DR.cp, DR.colonia, DR.poblacion, DR.municipio, DR.estado, DR.pais,
			DR.telefono, E.licencia, E.tipolicencia, DATE_FORMAT(E.vigencia,'%d/%m/%Y')AS vigencia,
			E.lentes, E.celularemp FROM catalogoempleado E
			INNER JOIN direccion DR ON DR.codigo=E.id AND DR.origen='emp'
			INNER JOIN catalogopuesto cp ON E.puesto=cp.id WHERE E.id='$id'";

$mo=@mysql_query("SELECT motivos FROM motivosbaja WHERE empleado='$id'",$link);
		$ro=@mysql_fetch_array($mo); $motivos=$ro[0];
		$r = mysql_query($s,$link) or die("error en linea ".__LINE__);
		if(mysql_num_rows($r)>0){
	$f = mysql_fetch_object($r); $cant = mysql_num_rows($r);
if($f->baja==""){$f->baja=0;} if($f->motivos==""){$f->motivos=0;} if($f->email==""){$f->email=0;} if($f->reingreso==""){$f->reingreso=0;}	if($f->bajareingreso==""){$f->bajareingreso=0;} if($f->licencia==""){$f->licencia=0;} if($f->celularemp==""){$f->celularemp=0;} if($f->alta==""){$f->alta=0;} if($f->licencia==""){$f->licencia=0;} if($f->tipolicencia==""){$f->tipolicencia=0;}	if($f->vigencia==""){$f->vigencia=0;} if($f->lentes==""){$f->lentes=0;} if($motivos==""){$motivos=0;} if($f->celularemp==""){$f->celularemp=0;}
			$xml = "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>			
			<empleado>$f->id</empleado>
			<numempleado>".cambio_texto($f->numempleado)."</numempleado>
			<sucursal>".cambio_texto($f->sucursal)."</sucursal>
			<Grsexo>".cambio_texto($f->sexo)."</Grsexo>
			<slecivil>".cambio_texto($f->estadocivil)."</slecivil>
			<nombre>".cambio_texto($f->nombre)."</nombre>
			<apaterno>".cambio_texto($f->apellidopaterno)."</apaterno>
			<amaterno>".cambio_texto($f->apellidomaterno)."</amaterno>
			<rfc>".cambio_texto($f->rfc)."</rfc>
			<curp>".cambio_texto($f->curp)."</curp>
			<nimss>".cambio_texto($f->nimss)."</nimss>
			<calle>".cambio_texto($f->calle)."</calle>
			<numero>".cambio_texto($f->numero)."</numero>
			<colonia>".cambio_texto($f->colonia)."</colonia>
			<cp>".cambio_texto($f->cp)."</cp>
			<poblacion>".cambio_texto($f->poblacion)."</poblacion>
			<municipio>".cambio_texto($f->municipio)."</municipio>
			<estado>".cambio_texto($f->estado)."</estado>
			<pais>".cambio_texto($f->pais)."</pais>		
			<telefono>".cambio_texto($f->telefono)."</telefono>
			<celular>".cambio_texto($f->celular)."</celular>
			<celularemp>".cambio_texto($f->celularemp)."</celularemp>
			<mail>".cambio_texto($f->email)."</mail>
			<lnacimiento>".cambio_texto($f->lugarnacimiento)."</lnacimiento>
			<slfNacimiento>".cambio_texto($f->fechanacimiento)."</slfNacimiento>
			<sltContrato>".cambio_texto($f->tipocontrato)."</sltContrato>
			<Alta>".cambio_texto($f->alta)."</Alta>			
			<Baja>".cambio_texto($f->baja)."</Baja>
			<Reingreso>".cambio_texto($f->reingreso)."</Reingreso>
			<BReingreso>".cambio_texto($f->bajareingreso)."</BReingreso>
			<slDepartamento>".cambio_texto($f->departamento)."</slDepartamento>
			<slTTrabajo>".cambio_texto($f->turno)."</slTTrabajo>
			<puesto>".cambio_texto($f->puesto)."</puesto>
			<ChLicencia>".cambio_texto($f->licenciamanejo)."</ChLicencia>
			<subcuenta>".cambio_texto($f->subcuentacontable)."</subcuenta>
			<cpagoelectronico>".cambio_texto($f->pagoelectronico)."</cpagoelectronico>
			<nlicencia>".cambio_texto($f->licencia)."</nlicencia>
			<sltlicencia>".cambio_texto($f->tipolicencia)."</sltlicencia>
			<vigencia>".cambio_texto($f->vigencia)."</vigencia>
			<lentes>".cambio_texto($f->lentes)."</lentes>
			<motivos>".cambio_texto($motivos)."</motivos>	
			<descripcionpuesto>".cambio_texto($f->descripcionpuesto)."</descripcionpuesto>
			<user>".cambio_texto($f->user)."</user>
			<password>".cambio_texto($f->password)."</password>
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
	
		}
		

function mostrarColonia($cp){	
	$link=Conectarse('webpmm');
	//$consulta=mysql_query("SELECT d_asenta FROM codigopostal WHERE d_codigo='$cp'",$link);	
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
	echo "<select name='colonia' id='colonia'  style='width:120px;font:tahoma;font-size:9px;'  onKeyPress='return tabular(event,this)'>";
	echo "<option value=''>Seleccionar</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		echo "<option value='".htmlentities($registro[2])."'>".htmlentities($registro[2])."</option>";
	}
	echo "</select>";
	}else{
	$registro=mysql_fetch_row($consulta);
	echo "<input name='colonia' type='text' id='colonia' size='24' value='".htmlentities($registro[2])."' style='font:tahoma;font-size:9px;background:#FFFF99' readonly='' onblur='document.getElementById('oculto').value='' onfocus='foco(this.name)'/>";
	}

}


?>
<?
if($tipo==1){
//BUSQUEDA POR CODIGO POSTAL
?>


<link href="Tablas.css" rel="stylesheet" type="text/css" />
<table width="450" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="66" class="Tablas">C.P.:</td>
    <td width="134"><input name="cp" type="text" id="cp" onblur="trim(document.getElementById('cp').value,'cp'); " onkeypress="return Numeros(event); " onkeydown="CodigoPostal(event, this.value); return tabular(event,this); " value="<?=$cp; ?>" size="24" maxlength="5" onkeyup="return validaCP(event,this.name)" style="font:tahoma;font-size:9px; text-transform:uppercase" /></td>
    <td width="81" class="Tablas">Colonia:</td>
    <td width="133" id="celcolonia"><? mostrarColonia($cp); ?></td>
    <td width="36"><strong><strong><strong><strong><img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('CatalogoEmpleadoBuscarColonia.php', 570, 350, 'ventana', 'Busqueda')" /></strong></strong></strong></strong></td>
  </tr>
  <tr>
    <td class="Tablas">Poblaci&oacute;n:</td>
    <td><input name="poblacion" type="text" id="poblacion" size="24"  style="font:tahoma;font-size:9px; background:#FFFF99" disabled="disabled"  value="<?= $poblacion; ?>" /></td>
    <td class="Tablas">Mun./Del.:</td>
    <td colspan="2"><input name="municipio" type="text" id="municipio" size="24"  style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled" value="<?= $municipio; ?>" /></td>
  </tr>
  <tr>
    <td class="Tablas">Estado:</td>
    <td><input name="estado" type="text" id="estado" size="24"  value="<?= $estado; ?>" style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled" /></td>
    <td class="Tablas">Pa&iacute;s:</td>
    <td colspan="2"><input name="pais" type="text" id="pais" size="24"  value="<?= $pais; ?>" style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled"/></td>
  </tr>
</table>
<? } ?>



<?
if($tipo=='2'){
	//OPTIENE PARA MOSTRAR LA COLONIA
?>
<table width="450" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="66" class="Tablas">C.P.:</td>
    <td width="134"><input name="cp" type="text" id="cp" onblur="trim(document.getElementById('cp').value,'cp'); " onkeypress="return Numeros(event); " onkeydown="CodigoPostal(event, this.value); return tabular(event,this); " value="<?=$cp; ?>" size="24" maxlength="5" onkeyup="return validaCP(event,this.name)" style="font:tahoma;font-size:9px; text-transform:uppercase" /></td>
    <td width="81" class="Tablas">Colonia:</td>
    <td width="133" id="celcolonia"><input name="colonia" type="text" id="colonia" size="24" value="<?= $colonia; ?>" style="font:tahoma;font-size:9px;background:#FFFF99" readonly="" onfocus="foco(this.name)" onblur="document.getElementById('oculto').value=''" /></td>
    <td width="36"><strong><strong><strong><strong><img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('CatalogoEmpleadoBuscarColonia.php', 570, 350, 'ventana', 'Busqueda')" /></strong></strong></strong></strong></td>
  </tr>
  <tr>
    <td class="Tablas">Poblaci&oacute;n:</td>
    <td><input name="poblacion" type="text" id="poblacion" size="24"  style="font:tahoma;font-size:9px; background:#FFFF99" disabled="disabled"  value="<?= $poblacion; ?>" /></td>
    <td class="Tablas">Mun./Del.:</td>
    <td colspan="2"><input name="municipio" type="text" id="municipio" size="24"  style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled" value="<?= $municipio; ?>" /></td>
  </tr>
  <tr>
    <td class="Tablas">Estado:</td>
    <td><input name="estado" type="text" id="estado" size="24"  value="<?= $estado; ?>" style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled" /></td>
    <td class="Tablas">Pa&iacute;s:</td>
    <td colspan="2"><input name="pais" type="text" id="pais" size="24"  value="<?= $pais; ?>" style="font:tahoma;font-size:9px;background:#FFFF99" disabled="disabled"/></td>
  </tr>
</table>
<? }?>
  <?
if($tipo==3){
//FILTRO CATALOGO EMPLEADO	
?>
</p>
<table width="480" border="0">
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
		}
		while($row=@mysql_fetch_array($get)){
		
	?>
  <tr class="Tablas" onclick="window.parent.CalogoEmpleadoColonia('<?=$row[codigopostal];?>','<?=$row[colonia];?>','<?=$row[poblacion];?>','<?=$row[municipio];?>','<?=$row[estado];?>','<?=$row[pais];?>')
  ;parent.VentanaModal.cerrar();" style="cursor:pointer"  >
    <td width="43%" ><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase; cursor:pointer" value="<?=$row[colonia] ?>" size="35" readonly="" /></td>
    <td width="6%"><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase; cursor:pointer" value="<?=$row[codigopostal] ?>" size="5" readonly="" /></td>
    <td width="17%"><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase;cursor:pointer " value="<?=$row[poblacion] ?>" size="15" readonly="" /></td>
    <td width="17%"><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase;cursor:pointer" value="<?=$row[municipio] ?>" size="15" readonly="" /></td>
    <td width="17%"><input name="d_asenta" type="text" class="Tablas" style="border:none; text-transform:uppercase;cursor:pointer" value="<?=$row[estado] ?>" size="15" readonly="" /></td>
  </tr>
  <? } ?>
</table>
<? } ?>
