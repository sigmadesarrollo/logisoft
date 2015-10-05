<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	/*if(isset($_SESSION['gvalidar'])!=100){echo"<script language='javascript' type='text/javascript'>			document.location.href='../index.php';</script>";}else{*/
		require_once('../../Conectar.php');	
		$link=Conectarse('webpmm');
		$usuario=$_SESSION[NOMBREUSUARIO];
$hrs=$_POST['hrs'];$min=$_POST['min'];
$horariolimiterecoleccion=$hrs.":".$min;

$accion=$_POST['accion']; $codigo=$_POST['codigo']; $prefijo=$_POST['prefijo']; $idsucursal=$_POST['idsucursal']; $descripcion=$_POST['descripcion'];  $calle=$_POST['calle']; $cp=$_POST['cp']; $colonia=$_POST['colonia']; $numero=$_POST['numero']; $poblacion=$_POST['poblacion']; $municipio=$_POST['municipio']; $estado=$_POST['estado']; $pais=$_POST['pais']; $telefono=$_POST['telefono']; $fax=$_POST['fax']; $cajachica = $_POST['cajachica']; $entrecalles=$_POST['entrecalles'];

$lectores=$_POST['lectores']; $iva=$_POST['iva']; $concesion=$_POST['concesion'];
$comision=$_POST['comision']; $ventas=$_POST['ventas']; $recibido=$_POST['recibido']; $porcead=$_POST['porcead']; $ead=$_POST['ead']; $precioead=$_POST['precioead']; $recoleccion=$_POST['recoleccion']; $preciorecoleccion=$_POST['preciorecoleccion']; $porcrecoleccion=$_POST['porcrecoleccion']; $arreglo=$_POST['arreglo']; $bascula=$_POST['bascula'];$monitoreo=$_POST['monitoreo'];
		$coma=",";
		$lista=split($coma,$arreglo);		
			for ($i=0;$i<count($lista);$i++){	
				$var = trim($lista[$i]);
				if ($var!=""){
					$arre[$i]=$var;
				}
			}
$colonia=$arre[0]; $poblacion=$arre[1]; $municipio=$arre[2]; $estado=$arre[3]; $pais=$arre[4];
	if($accion==""){		
		$row=folio('catalogosucursal','webpmm');
		$codigo=$row[0];	
	}
	
	
	if($accion=="grabar"){
	$sqlins=mysql_query("INSERT INTO catalogosucursal (id, prefijo, idsucursal, descripcion,monitoreo, concesion, comision,
 ventas, recibido, porcead, ead, precioead, recoleccion, preciorecoleccion, porcrecoleccion,
  lectores, iva, bascula, cajachica,horariolimiterecoleccion, usuario, fecha) 
VALUES(null,UCASE('$prefijo'), '$idsucursal', UCASE('$descripcion'),'$monitoreo', '$concesion', 
'$comision', '$ventas', '$recibido', '$porcead', '$ead', '$precioead', '$recoleccion', 
'$preciorecoleccion', '$porcrecoleccion', '$lectores', '$iva', '$bascula', '$cajachica',
'$horariolimiterecoleccion', '$usuario', current_timestamp())",$link) or die("INSERT INTO catalogosucursal (id, prefijo, idsucursal, descripcion,monitoreo, concesion, comision,
 ventas, recibido, porcead, ead, precioead, recoleccion, preciorecoleccion, porcrecoleccion,
  lectores, iva, bascula, cajachica,horariolimiterecoleccion, usuario, fecha) 
VALUES(null,UCASE('$prefijo'), '$idsucursal', UCASE('$descripcion'),'$monitoreo', '$concesion', 
'$comision', '$ventas', '$recibido', '$porcead', '$ead', '$precioead', '$recoleccion', 
'$preciorecoleccion', '$porcrecoleccion', '$lectores', '$iva', '$bascula', '$cajachica',
'$horariolimiterecoleccion', '$usuario', current_timestamp())"." Error en la linea ".__LINE__);
		$codigo=mysql_insert_id();
		$dir=mysql_query("INSERT INTO direccion (id, origen, codigo, calle, numero,crucecalles, cp, colonia, poblacion, municipio, estado, pais, telefono, fax, facturacion, usuario, fecha) VALUES (null, 'suc', '$codigo', UCASE('$calle'), '$numero','$entrecalles', '$cp', UCASE('$colonia'), UCASE('$poblacion'), UCASE('$municipio'), UCASE('$estado'), UCASE('$pais'), '$telefono', '$fax', 'NO', '$usuario', current_timestamp())",$link)or die("INSERT INTO direccion (id, origen, codigo, calle, numero, cp, colonia, poblacion, municipio, estado, pais, telefono, fax, facturacion, usuario, fecha) VALUES (null, 'suc', '$codigo', UCASE('$calle'), '$numero', '$cp', UCASE('$colonia'), UCASE('$poblacion'), UCASE('$municipio'), UCASE('$estado'), UCASE('$pais'), '$telefono', '$fax', 'NO', '$usuario', current_timestamp())"." Error en la linea ".__LINE__);		
		$mensaje="Los datos han sido guardados correctamente";
		$accion="modificar";
		
	}else if($accion=="modificar"){
	$sqlupd=mysql_query("UPDATE catalogosucursal SET prefijo=UCASE('$prefijo'), idsucursal='$idsucursal', descripcion=UCASE('$descripcion'),monitoreo='$monitoreo', concesion='$concesion', comision='$comision', ventas='$ventas', recibido='$recibido', porcead='$porcead', ead='$ead', precioead='$precioead', recoleccion='$recoleccion', preciorecoleccion='$preciorecoleccion', porcrecoleccion='$porcrecoleccion', lectores='$lectores', iva='$iva', bascula='$bascula', cajachica='$cajachica',horariolimiterecoleccion='$horariolimiterecoleccion', usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'",$link)or die("UPDATE catalogosucursal SET prefijo=UCASE('$prefijo'), idsucursal='$idsucursal', descripcion=UCASE('$descripcion'),monitoreo='$monitoreo', concesion='$concesion', comision='$comision', ventas='$ventas', recibido='$recibido', porcead='$porcead', ead='$ead', precioead='$precioead', recoleccion='$recoleccion', preciorecoleccion='$preciorecoleccion', porcrecoleccion='$porcrecoleccion', lectores='$lectores', iva='$iva', bascula='$bascula', cajachica='$cajachica',horariolimiterecoleccion='$horariolimiterecoleccion', usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'"." Error en la linea ".__LINE__);
	
		$mensaje="Los cambios han sido guardados correctamente";
		$dir1=mysql_query("UPDATE direccion SET calle=UCASE('$calle'), numero='$numero',crucecalles='$entrecalles', cp='$cp', colonia=UCASE('$colonia'), poblacion=UCASE('$poblacion'), municipio=UCASE('$municipio'), estado=UCASE('$estado'), pais=UCASE('$pais'), telefono='$telefono', fax='$fax', facturacion='NO', usuario='$usuario', fecha=current_timestamp() WHERE origen='suc' AND codigo='$codigo'",$link)or die("UPDATE direccion SET calle=UCASE('$calle'), numero='$numero', cp='$cp', colonia=UCASE('$colonia'), poblacion=UCASE('$poblacion'), municipio=UCASE('$municipio'), estado=UCASE('$estado'), pais=UCASE('$pais'), telefono='$telefono', fax='$fax', facturacion='NO', usuario='$usuario', fecha=current_timestamp() WHERE origen='suc' AND codigo='$codigo'"." Error en la linea ".__LINE__);
		$accion="modificar";		
	}else if($accion=="limpiar"){
$accion=''; $prefijo=''; $idsucursal=''; $descripcion='';  $calle=''; $cp=''; $colonia=''; $numero=''; $poblacion=''; $municipio=''; $estado=''; $pais=''; $telefono=''; $fax=''; $lectores=''; $iva=''; $concesion=''; $comision=''; $ventas=''; $recibido=''; $porcead=''; $ead=''; $precioead=''; $recoleccion=''; $preciorecoleccion='';  $porcrecoleccion=''; $bascula=''; $cajachica = ''; $entrecalles='';$monitoreo="";
		$row=folio('catalogosucursal','webpmm');
		$codigo=$row[0];
		
		$hrs='';$min=''; $horariolimiterecoleccion='';
		
	}
		
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/shortcut.js"></script>
<script language="javascript" type="text/javascript">

var Input = '<input  class="Tablas" name="colonia" type="text" id="colonia" size="32" readonly="" value="<?= $colonia; ?>" style="font:tahoma;font-size:9px; background:#FFFF99; text-transform:uppercase" onDblClick="javascript:popUp(\'buscarcolonia2.php\')"  />';

var combo1 = "<select class='Tablas' name='colonia' id='colonia'  style='width:185px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";

var guardando = 0;
var var_lic = '<img src="../../img/guia_azul_32.gif">';

	var nav4 = window.Event ? true : false;
function Numeros(evt){
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
}
function validar(){
	if(document.getElementById('prefijo').value==""){
			alerta('Debe capturar Prefijo','¡Atención!','prefijo');
	}else if (document.getElementById('descripcion').value==""){
			alerta('Debe capturar Descripción','¡Atención!','descripcion');	
	}else if (document.getElementById('idsucursal').value==""){			
			alerta('Debe capturar ID Sucursal','¡Atención!','idsucursal');	
	}else if (document.getElementById('calle').value==""){
			alerta('Debe capturar Calle','¡Atención!','calle');
	}else if (document.getElementById('numero').value==""){			
			alerta('Debe capturar Numero','¡Atención!','numero');
	}else if (document.getElementById('cp').value==""){			
			alerta('Debe capturar Código Postal','¡Atención!','cp');
	}else if (document.getElementById('telefono').value==""){
			alerta('Debe capturar Teléfono','¡Atención!','telefono');			
	}else if (document.getElementById('ventas').value<0){			
alerta('El Porcentaje de Ventas debe ser mayor a Cero','¡Atención!','ventas');
	}else if (document.getElementById('ventas').value>100){			
alerta('El Porcentaje de Ventas no debe ser Mayor al 100%','¡Atención!','ventas');		
	}else if (document.getElementById('recibido').value<0){			
alerta('El Porcentaje de Recibido debe ser mayor a Cero','¡Atención!','recibido');
	}else if (document.getElementById('recibido').value>100){			
alerta('El Porcentaje de Recibido no debe ser Mayor al 100%','¡Atención!','recibido');	
	}else if (document.getElementById('porcead').value<0){			
alerta('El Porcentaje de EAD debe ser mayor a Cero','¡Atención!','porcead');
	}else if (document.getElementById('porcead').value>100){			
alerta('El Porcentaje de EAD no debe ser Mayor al 100%','¡Atención!','porcead');
	}else if (document.getElementById('porcrecoleccion').value<0){			
alerta('El Porcentaje de Recolección debe ser mayor a Cero','¡Atención!','porcrecoleccion');
	}else if (document.getElementById('porcrecoleccion').value>100){			
alerta('El Porcentaje de Recolección no debe ser Mayor al 100%','¡Atención!','porcrecoleccion');
	}else if(document.getElementById('hrs').value==00 && document.getElementById('min').value==00){
 alerta('Debe capturar Horario Limite Registro Recolecciones', '¡Atención!','hrs');
	}else{
		document.getElementById('lleno').value="SI";
	}	
	if(document.form1.concesion.checked==true){		
		if(document.getElementById('comision').value==""){
		alerta('Debe capturar Comisión','¡Atención!','comision');
		document.getElementById('lleno').value="NO";
		return false;
		}else if(document.getElementById('comision').value>100){			
alerta('El Porcentaje de Comisión no debe ser Mayor al 100%','¡Atención!','comision'); document.getElementById('lleno').value="NO";
		return false;
		}
	}	
	if(document.form1.ead.checked==true){	
		if(document.getElementById('precioead').value==""){
		alerta('Debe capturar Precio EAD','¡Atención!','precioead');
		document.getElementById('lleno').value="NO";
		return false;
		}else if (document.getElementById('precioead').value<0){
alerta('El Precio EAD debe ser mayor a Cero','¡Atención!','precioead');
		document.getElementById('lleno').value="NO";
		return false;
		}
	}	
	if(document.form1.recoleccion.checked==true){
		if(document.getElementById('preciorecoleccion').value==""){
		alerta('Debe capturar Precio Recolección','¡Atención!','preciorecoleccion');
		document.getElementById('lleno').value="NO";
		return false;
		}else if (document.getElementById('preciorecoleccion').value<0){			
alerta('El Precio de Recolección debe ser mayor a Cero','¡Atención!','preciorecoleccion');
		document.getElementById('lleno').value="NO";
		return false;
		}		
	}	
	if(document.getElementById('lleno').value=="SI"){
		miArray = new Array(5)
		miArray[0] = document.getElementById('colonia').value;
		miArray[1] = document.getElementById('poblacion').value;
		miArray[2] = document.getElementById('municipio').value;
		miArray[3] = document.getElementById('estado').value;
		miArray[4] = document.getElementById('pais').value;	
		document.getElementById('arreglo').value=miArray;
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
			}else if(document.getElementById('accion').value=="modificar"){
				document.form1.submit();
			}
	}
	
	
}
function limpiar(){
document.getElementById('prefijo').value=""; document.getElementById('descripcion').value=""; document.getElementById('idsucursal').value="";	document.getElementById('calle').value="";	document.getElementById('numero').value="";	document.getElementById('cp').value="";	document.getElementById('ventas').value=""; document.getElementById('recibido').value=""; document.getElementById('porcead').value=""; document.getElementById('porcrecoleccion').value=""; document.getElementById('lleno').value=""; document.form1.concesion.checked=false; document.getElementById('comision').value=""; document.form1.ead.checked=false; document.getElementById('precioead').value=""; document.form1.recoleccion.checked=false; 	document.getElementById('preciorecoleccion').value=""; document.getElementById('colonia').value=""; document.getElementById('poblacion').value=""; document.getElementById('municipio').value=""; document.getElementById('estado').value=""; 	document.getElementById('pais').value=""; document.getElementById('arreglo').value=""; document.getElementById('telefono').value=""; document.getElementById('fax').value="";  document.form1.lectores.checked=false; document.getElementById('iva').value=""; document.getElementById('oculto').value=""; document.all.bascula.checked=false; document.all.cajachica.value =""; document.all.hrs.value=""; document.all.min.value=""; document.all.entrecalles.value="";
document.getElementById('accion').value = "limpiar"; 
document.form1.submit();
}
function limpiartodo(){
document.getElementById('oculto').value=""; document.getElementById('prefijo').value=""; document.getElementById('descripcion').value=""; document.getElementById('idsucursal').value="";	document.getElementById('calle').value="";	document.getElementById('numero').value="";	document.getElementById('cp').value="";	document.getElementById('ventas').value=""; document.getElementById('recibido').value=""; document.getElementById('porcead').value=""; document.getElementById('porcrecoleccion').value=""; document.getElementById('lleno').value=""; document.form1.concesion.checked=false; document.getElementById('comision').value=""; document.form1.ead.checked=false; document.getElementById('precioead').value=""; document.form1.recoleccion.checked=false; 	document.getElementById('preciorecoleccion').value=""; document.getElementById('colonia').value=""; document.getElementById('poblacion').value=""; document.getElementById('municipio').value=""; document.getElementById('estado').value=""; 	document.getElementById('pais').value=""; document.getElementById('arreglo').value=""; document.getElementById('telefono').value=""; document.getElementById('fax').value="";  document.form1.lectores.checked=false; document.getElementById('iva').value="";document.all.bascula.checked=false; document.all.cajachica.value ="";document.all.entrecalles.value="";
 document.all.hrs.value=""; document.all.min.value="";
}
function obtener(id){
	document.getElementById('codigo').value=id;
	document.getElementById('accion').value="modificar";
consulta("mostrarSucursal","catalogosucursalresult.php?accion=1&codigo="+id);
}
function mostrarSucursal(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		limpiartodo();
		
		if(con>0){
			u.codigo.value=datos.getElementsByTagName('id').item(0).firstChild.data;
			u.prefijo.value=datos.getElementsByTagName('prefijo').item(0).firstChild.data;
			u.descripcion.value=datos.getElementsByTagName('descripcion').item(0).firstChild.data;
			u.idsucursal.value=datos.getElementsByTagName('idsucursal').item(0).firstChild.data;
			u.calle.value=datos.getElementsByTagName('calle').item(0).firstChild.data;
			
			u.entrecalles.value=datos.getElementsByTagName('entrecalles').item(0).firstChild.data;
			u.numero.value=datos.getElementsByTagName('numero').item(0).firstChild.data;
			u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;
			document.all.celcolonia.innerHTML = Input;
			u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
			u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
			u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;
			u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;
			u.telefono.value=datos.getElementsByTagName('telefono').item(0).firstChild.data;

			if(datos.getElementsByTagName('fax').item(0).firstChild.data==0){
				u.fax.value="";
			}else{ u.fax.value=datos.getElementsByTagName('fax').item(0).firstChild.data; }
			
			if(datos.getElementsByTagName('monitoreo').item(0).firstChild.data==1){
				u.monitoreo.checked=true;
			}
			
			if(datos.getElementsByTagName('concesion').item(0).firstChild.data==1){
				u.concesion.checked=true;
				HabilitarComision();
			}
			u.comision.value=datos.getElementsByTagName('comision').item(0).firstChild.data;
			u.ventas.value=datos.getElementsByTagName('ventas').item(0).firstChild.data;
			u.recibido.value=datos.getElementsByTagName('recibido').item(0).firstChild.data;
			if(datos.getElementsByTagName('ead').item(0).firstChild.data==1){
				u.ead.checked=true;
				HabilitarPrecioEAD();
			}
			u.porcead.value=datos.getElementsByTagName('porcead').item(0).firstChild.data;
			u.porcrecoleccion.value=datos.getElementsByTagName('porcrecoleccion').item(0).firstChild.data;
			u.precioead.value=datos.getElementsByTagName('precioead').item(0).firstChild.data;
			if(datos.getElementsByTagName('recoleccion').item(0).firstChild.data==1){
				u.recoleccion.checked=true;
				HabilitarPrecioRecoleccion();
			}
			u.preciorecoleccion.value=datos.getElementsByTagName('preciorecoleccion').item(0).firstChild.data;

		
			if(datos.getElementsByTagName('lectores').item(0).firstChild.data==1){
				u.lectores.checked=true;
			}
			if(datos.getElementsByTagName('bascula').item(0).firstChild.data==1){
				u.bascula.checked=true;
			}
			u.iva.value=datos.getElementsByTagName('iva').item(0).firstChild.data;	
			u.cajachica.value=datos.getElementsByTagName('cajachica').item(0).firstChild.data;
			var hora =datos.getElementsByTagName('horariolimiterecoleccion').item(0).firstChild.data;
			hora = hora.split(":");
			u.hrs.value = hora[0]; 
			u.min.value = hora[1]; 
			
			u.prefijo.focus();
			}else{
				alerta("La Sucursal No Existe",'¡Atención!','prefijo');
				u.prefijo.focus();
			}
	}
function CodigoPostal(e,cp){
		tecla=(document.all) ? e.keyCode : e.which;
		if(tecla==13 && cp!=""){
consulta("mostrarPostal","ConsultaCodigoPostal.php?accion=1&cp="+cp+"&sid="+Math.random());
		document.all.imagen.style.visibility="visible";
		}	
}
function mostrarPostal(datos){
var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
document.getElementById('colonia').value=""; document.getElementById('poblacion').value=""; document.getElementById('municipio').value=""; document.getElementById('estado').value=""; document.getElementById('pais').value="";
				
		
	if(con>0){		
		document.all.imagen.style.visibility="hidden";
		if(datos.getElementsByTagName('total').item(0).firstChild.data>1){
			document.all.celcolonia.innerHTML = combo1;
			var combo = document.all.colonia;		
			combo.options.length = null;
			
			uOpcion = document.createElement("OPTION");
			uOpcion.value=0;
			uOpcion.text="..:: Selecciona ::..";
			combo.add(uOpcion);
		var total =datos.getElementsByTagName('total').item(0).firstChild.data;
			for(i=0;i<total;i++){	
				uOpcion = document.createElement("OPTION");
				uOpcion.value=datos.getElementsByTagName('colonia').item(i).firstChild.data;
				uOpcion.text=datos.getElementsByTagName('colonia').item(i).firstChild.data;
				combo.add(uOpcion);
			}
		u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;
		u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
		u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;
		u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;
		}else{		
			
		document.all.celcolonia.innerHTML = Input;
		u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;
		u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
		u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;
		u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;
		}
		}else{
			document.all.imagen.style.visibility="hidden";
			alerta("El Código Postal no existe",'¡Atención!','cp');
			document.all.celcolonia.innerHTML = Input;
			u.cp.focus();
		}
}
function existeCP(){
if(document.getElementById('poblacion').value=="" && document.getElementById('colonia').value=="" && document.getElementById('pais').value==""){
		alerta('El codigo postal no existe', '¡Atención!','cp');
	}
}
function validaCP(e,obj){
	tecla=(document.all) ? e.keyCode : e.which;
    if(tecla==8 && document.getElementById(obj).value=="" || tecla==46){
document.getElementById('colonia').value=""; document.getElementById('poblacion').value=""; document.getElementById('municipio').value=""; document.getElementById('estado').value=""; document.getElementById('pais').value="";
	}
}
function trim(cadena,caja)
{
	for(i=0;i<cadena.length;)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(i+1, cadena.length);
		else
			break;
	}

	for(i=cadena.length-1; i>=0; i=cadena.length-1)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(0,i);
		else
			break;
	}
	
	document.getElementById(caja).value=cadena;
}
function tabular(e,obj) 
        {
            tecla=(document.all) ? e.keyCode : e.which;
            if(tecla!=13) return;
            frm=obj.form;
            for(i=0;i<frm.elements.length;i++) 
                if(frm.elements[i]==obj) 
                { 
                    if (i==frm.elements.length-1) 
                        i=-1;
                    break
                }
            /*ACA ESTA EL CAMBIO*/
             if (frm.elements[i+1].disabled ==true )    
                tabular(e,frm.elements[i+1]);
            else if (frm.elements[i+1].readOnly ==true )    
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus();
            return false;
} 
function HabilitarComision(){
	if(document.form1.concesion.checked==true){
	document.getElementById('comision').disabled=false
	document.getElementById('comision').style.backgroundColor='';
	document.getElementById('comision').focus();
	}else{
	document.getElementById('comision').disabled=true
	document.getElementById('comision').value="";
	document.getElementById('comision').style.backgroundColor='#FFFF99';
	}
} 
function HabilitarPrecioEAD(){
	if(document.form1.ead.checked==true){
	document.getElementById('precioead').disabled=false
	document.getElementById('precioead').style.backgroundColor='';
	document.getElementById('precioead').focus();
	}else{
	document.getElementById('precioead').disabled=true
	document.getElementById('precioead').value="";
	document.getElementById('precioead').style.backgroundColor='#FFFF99';
	}
} 
function HabilitarPrecioRecoleccion(){
	if(document.form1.recoleccion.checked==true){
	document.getElementById('preciorecoleccion').disabled=false
	document.getElementById('preciorecoleccion').style.backgroundColor='';
	document.getElementById('preciorecoleccion').focus();
	}else{
	document.getElementById('preciorecoleccion').disabled=true
	document.getElementById('preciorecoleccion').value="";
	document.getElementById('preciorecoleccion').style.backgroundColor='#FFFF99';
	}
} 
function CatalogoSucursalColonia(cp,colonia,poblacion,municipio,estado,pais){
	document.getElementById('cp').value=cp;
	document.all.celcolonia.innerHTML=Input;
	document.getElementById('colonia').value=colonia;
	document.getElementById('poblacion').value=poblacion;
	document.getElementById('municipio').value=municipio;
	document.getElementById('estado').value=estado;
	document.getElementById('pais').value=pais;	
	document.all.telefono.focus();
}
function foco(nombrecaja){
	if(nombrecaja=="codigo"){
		document.getElementById('oculto').value="1";
	}else if(nombrecaja=="colonia"){
		document.getElementById('oculto').value="2";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
	abrirVentanaFija('buscarsucursal.php', 550, 430, 'ventana', 'Busqueda')
	}else if(document.form1.oculto.value=="2"){
abrirVentanaFija('CatalogoSucursalBuscarColonia.php', 570, 350, 'ventana', 'Busqueda')
	}
});
</script>
<script src="select.js"></script>
<script src="../../javascript/ajax.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo Sucursal</title>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {	font-size: 9px;
	color: #464442;
}
.style5 {color: #FFFFFF ; font-size:9px}
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 14px;
}
-->
</style>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="document.getElementById('prefijo').focus()">
<form name="form1" method="post" action="">
  <table width="100%" border="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="520" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
	  	<tr>
			<td class="FondoTabla">Datos Sucursal </td>
		</tr>
		
          <tr>
            <td><br><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="1194" colspan="5" class="FondoTabla">Datos Generales </td>
              </tr>
              <tr>
                <td colspan="5" class="Tablas"><table width="499" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="Tablas">Codigo:</td>
                    <td colspan="4"><input class="Tablas" name="codigo" type="text" readonly="" id="codigo" size="4"  value="<?= $codigo; ?>" style="font:tahoma;font-size:9px; background:#FFFF99" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''"/>
                      <img src="../../img/Buscar_24.gif" title="Buscar Sucursal" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarsucursal.php', 550, 450, 'ventana', 'Busqueda')" /></td>
                  </tr>
                  <tr>
                    <td colspan="5" class="Tablas"><table width="284" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="20"><label>
                          <input name="monitoreo" type="checkbox" id="monitoreo" onKeyPress="return tabular(event,this)" value="1"   <? if($monitoreo==1){echo "checked";} ?>>
                        </label></td>
                        <td width="264" class="formato_fuente">Monitoreo Incidencias </td>
                      </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td class="Tablas">Prefijo:</td>
                    <td colspan="4"><input class="Tablas" name="prefijo" type="text" id="prefijo" style="font:tahoma;font-size:9px; text-transform:uppercase" onBlur="trim(document.getElementById('prefijo').value,'prefijo');" onKeyPress="return tabular(event,this)" value="<?= $prefijo; ?>" size="20" maxlength="10"/></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Descripcion:</td>
                    <td colspan="4" class="Tablas"><input class="Tablas" name="descripcion" type="text" id="descripcion" size="34" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" value="<?= $descripcion; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/>
                      &nbsp; Cuentas Contables: 
                      <input name="idsucursal" class="Tablas" type="text" id="idsucursal" size="15" onBlur="trim(document.getElementById('idsucursal').value,'idsucursal');" value="<?= $idsucursal; ?>" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" style="font:tahoma;font-size:9px"/></td>
                  </tr>
                  <tr>
                    <td width="77" class="Tablas">Calle:</td>
                    <td colspan="4" class="Tablas"><input class="Tablas" name="calle" type="text" id="calle" size="38" onBlur="trim(document.getElementById('calle').value,'calle');" value="<?=$calle; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      Numero:
                      <input name="numero" class="Tablas" type="text" id="numero" size="9" onBlur="trim(document.getElementById('numero').value,'numero');" value="<?=$numero; ?>" onKeyDown="return tabular(event,this)" style="font:tahoma;font-size:9px; text-transform:uppercase"/></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Cruce Calles:</td>
                    <td colspan="4"><input class="Tablas" name="entrecalles" type="text" id="entrecalles" size="69" onBlur="trim(document.getElementById('entrecalles').value,'entrecalles');" value="<?= $entrecalles; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/></td>
                  </tr>
                  <tr>
                    <td class="Tablas">C.P.:</td>
                    <td colspan="2" class="Tablas"><input class="Tablas" name="cp" type="text" id="cp" onBlur="trim(document.getElementById('cp').value,'cp'); " onKeyPress="return Numeros(event)" onKeyDown="CodigoPostal(event,this.value); return tabular(event,this);" onKeyUp="return validaCP(event,this.name)"  value="<?= $cp; ?>" size="10" maxlength="5" style="font:tahoma;font-size:9px; text-transform:uppercase" />
                      <img src="../../javascript/loading.gif" name="imagen" width="16" height="16" align="absbottom" id="imagen" style="visibility:hidden" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Colonia:</td>
                    <td width="187" id="celcolonia"><input class="Tablas" name="colonia" type="text" id="colonia" size="32" readonly="" value="<?= $colonia; ?>" style="font:tahoma;font-size:9px; background:#FFFF99; text-transform:uppercase" onDblClick="javascript:popUp('buscarcolonia2.php')"  /></td>
                    <td width="47"><img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('CatalogoSucursalBuscarColonia.php', 570, 350, 'ventana', 'Busqueda')" /></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Poblaci&oacute;n:</td>
                    <td width="182"><input class="Tablas" name="poblacion" type="text" id="poblacion" size="20"  style="font:tahoma;font-size:9px; background:#FFFF99;  text-transform:uppercase" disabled="disabled"  value="<?= $poblacion; ?>" /></td>
                    <td colspan="3" class="Tablas">Mun./Del.:&nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="municipio" class="Tablas" type="text" id="municipio" size="20"  style="font:tahoma;font-size:9px;background:#FFFF99; text-transform:uppercase" disabled="disabled" value="<?= $municipio; ?>" /></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Estado:</td>
                    <td><input name="estado" class="Tablas" type="text" id="estado" size="20" value="<?= $estado; ?>" style="font:tahoma;font-size:9px;background:#FFFF99; text-transform:uppercase" disabled="disabled" /></td>
                    <td colspan="3" class="Tablas">Pa&iacute;s:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="pais" class="Tablas" type="text" id="pais" size="20" value="<?= $pais; ?>" style="font:tahoma;font-size:9px;background:#FFFF99; text-transform:uppercase" disabled="disabled"/></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Tel&eacute;fono:</td>
                    <td><input name="telefono" class="Tablas" type="text" id="telefono" size="20" onBlur="trim(document.getElementById('telefono').value,'telefono');" value="<?= $telefono; ?>" style="font:tahoma;font-size:9px; " onKeyPress="return tabular(event,this)" /></td>
                    <td colspan="3" class="Tablas">Fax:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="fax" class="Tablas" type="text" id="fax" size="20" onBlur="trim(document.getElementById('fax').value,'fax');" value="<?= $fax; ?>" style="font:tahoma;font-size:9px" onKeyPress="return tabular(event,this)" /></td>
                  </tr>
                  <tr>
                    <td colspan="5">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="5" class="FondoTabla">Caracter&iacute;sticas Sucursal </td>
              </tr>
              <tr>
                <td colspan="5"><table cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="65" class="Tablas"><input name="concesion" type="checkbox" id="concesion" style="width:12px; height:12px" onClick="HabilitarComision();" value="1" <? if($concesion==1){echo "checked";} ?> onKeyPress="return tabular(event,this)">
                      Concesi&oacute;n</td>
                    <td width="49" class="Tablas">&nbsp;</td>
                    <td width="79" class="Tablas">% Comisi&oacute;n:</td>
                    <td width="66"><input class="Tablas" name="comision" type="text" id="comision" style="font:tahoma;font-size:9px; background:#FFFF99" onBlur="trim(document.getElementById('comision').value,'comision');" onKeyPress="return Numeros(event)" disabled="disabled" onKeyDown="return tabular(event,this)" value="<?= $comision; ?>" size="5" maxlength="5" />
                      <? if($concesion==1){ echo "<script>HabilitarComision()</script>";} ?></td>
                    <td width="16">&nbsp;</td>
                    <td width="124"><span class="Tablas">% Ventas:</span></td>
                    <td width="99"><span class="Tablas">
                      <input class="Tablas" name="ventas" type="text" id="ventas" style="font:tahoma;font-size:9px;width:73px" onBlur="trim(document.getElementById('ventas').value,'ventas');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$ventas; ?>" size="5" maxlength="5" />
                    </span></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="Tablas">% Recibido:
                      <input class="Tablas" name="recibido" type="text" id="recibido" style="font:tahoma;font-size:9px" onBlur="trim(document.getElementById('recibido').value,'recibido');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?= $recibido; ?>" size="5" maxlength="5"/></td>
                    <td class="Tablas">% EAD:</td>
                    <td><span class="Tablas">
                      <input class="Tablas" name="porcead" type="text" id="porcead" style="font:tahoma;font-size:9px" onBlur="trim(document.getElementById('porcead').value,'porcead');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$porcead; ?>" size="5" maxlength="5"/>
                    </span></td>
                    <td>&nbsp;</td>
                    <td class="Tablas">% Recolecci&oacute;n:</td>
                    <td><span class="Tablas">
                      <input name="porcrecoleccion" type="text" id="porcrecoleccion" style="font:tahoma;font-size:9px;width:73px" onBlur="trim(document.getElementById('porcrecoleccion').value,'porcrecoleccion');" onKeyPress="return Numeros(event)" class="Tablas"  value="<?=$porcrecoleccion; ?>" size="5" maxlength="5" onKeyDown="return tabular(event,this)"/>
                    </span></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="Tablas"><input name="ead" type="checkbox" id="ead" style="width:12px; height:12px" value="1" <? if($ead==1){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="HabilitarPrecioEAD();">
                      EAD</td>
                    <td class="Tablas">Precio EAD:</td>
                    <td><span class="Tablas">
                      <input class="Tablas" name="precioead" type="text" id="precioead" style="font:tahoma;font-size:9px;background:#FFFF99" onBlur="trim(document.getElementById('precioead').value,'precioead');" onKeyPress="return Numeros(event)" disabled="disabled" value="<?=$precioead; ?>" size="10" maxlength="13" onKeyDown="return tabular(event,this)" <? if($ead==1){ echo "HabilitarPrecioEAD()";} ?>/>
                      <? if($ead==1){ echo "<script>HabilitarPrecioEAD()</script>";} ?>
                    </span></td>
                    <td colspan="3"><span class="Tablas"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="Tablas"><input name="recoleccion" type="checkbox" id="recoleccion" style="width:12px; height:12px" value="1" <? if($recoleccion==1){echo "checked";} ?> onKeyPress="return tabular(event,this)" onClick="HabilitarPrecioRecoleccion();">
                      Recolecci&oacute;n</td>
                    <td class="Tablas">Precio Recole.:</td>
                    <td class="Tablas"><input class="Tablas" name="preciorecoleccion" type="text" id="preciorecoleccion" style="font:tahoma;font-size:9px;background:#FFFF99" onBlur="trim(document.getElementById('preciorecoleccion').value,'preciorecoleccion');" onKeyPress="return Numeros(event)" disabled="disabled" value="<?=$preciorecoleccion; ?>" size="10" maxlength="13" onKeyDown="return tabular(event,this)" <? if($ead==1){ echo "HabilitarPrecioRecoleccion()";} ?>/>
                      <? if($recoleccion==1){ echo "<script>HabilitarPrecioRecoleccion()</script>";} ?></td>
                    <td><span class="Tablas">
                      <label></label>
                    </span></td>
                    <td><span class="Tablas">
                      <label>
                        <input name="bascula" type="checkbox" id="bascula" style="width:12px; height:12px" value="1" <? if($bascula==1){echo "checked";} ?> onKeyPress="return tabular(event,this)">
                      </label>
                      Usa Bascula </span></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="Tablas"><label>
                      <input name="lectores" type="checkbox" id="lectores" style="width:12px; height:12px" value="1" <? if($lectores==1){echo "checked";} ?> onKeyPress="return tabular(event,this)">
Utiliza Lectores </label></td>
                    <td class="Tablas">% IVA: </td>
                    <td class="Tablas"><input name="iva" class="Tablas" type="text" id="iva" style="font:tahoma;font-size:9px;width:73px" onBlur="trim(document.getElementById('iva').value,'iva');" onKeyPress="return Numeros(event)"  value="<?=$iva; ?>" maxlength="5" onKeyDown="return tabular(event,this)"/></td>
                    <td>&nbsp;</td>
                    <td class="Tablas">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="Tablas">Fondo Caja Chica:<br></td>
                    <td colspan="2" class="Tablas"><input class="Tablas" name="cajachica" type="text" id="cajachica" style="font:tahoma;font-size:9px" onBlur="trim(document.getElementById('cajachica').value,'cajachica');" onKeyPress="return Numeros(event)"  value="<?=$cajachica; ?>" size="10" maxlength="10" onKeyDown="return tabular(event,this)"/></td>
                    <td>&nbsp;</td>
                    <td class="Tablas">Horario Limite Registro Recolecciones</td>
                    <td class="Tablas"><select name="hrs" id="hrs" style="font-size:9px; font:tahoma;width:40px">
                        <? for($h=0;$h<24;$h++){?>
                        <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT)?>"   <? if($hrs == str_pad($h,2,"0",STR_PAD_LEFT)){echo "selected";} ?> >
                        <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                          </option>
                        <? } ?>
                      </select>
                        <select name="min" id="min" style="font-size:9px; font:tahoma;width:40px">
                          <? for($m=0;$m<24;$m++){?>
                          <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"  <? if($min == str_pad($m,2,"0",STR_PAD_LEFT)){echo "selected";} ?>>
                            <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                            </option>
                          <? } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td colspan="7"></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="5"><input name="lleno" type="hidden" id="lleno" value="<?=$lleno; ?>">
                  <input name="accion" type="hidden" id="accion" value="<?=$accion; ?>">
                  <input name="arreglo" type="hidden" id="arreglo" value="<?=$arreglo; ?>">
                  <span class="Tablas">
                    <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />
                  </span></td>
              </tr>
              <tr>
                <td colspan="5"><table width="20" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><img src="../../img/Boton_Guardar.gif" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>
                    <td><img src="../../img/Boton_Nuevo.gif" width="70" height="20" title="Nuevo" onClick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" style="cursor:pointer" ></td>
                  </tr>
                </table></td>
              </tr>
            </table>
              <br>
              <table width="33" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="33"><a href="../../menu/webministator.php"><img src="../../img/inicio_30.gif" width="29" height="33" border="0"></a></td>
                  </tr>
              </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO SUCURSAL';
</script>
<? 
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//} ?>