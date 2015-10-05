<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../javascript/ajaxlist/ajax.js"></script>
<script src="../../javascript/shortcut.js"></script>
<script language="javascript" type="text/javascript">
var u = document.all;
var Input = '<input name="colonia" type="text" class="Tablas" id="colonia" style=" width:207px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';

var combo1 = "<select class='Tablas' name='colonia' id='colonia'  style='width:200px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";
var guardando = 0;
var var_lic = '<img src="../../img/guia_azul_32.gif">';

	window.onload = function(){
		u.prefijo.focus();
		obtenerGeneral();
	}

	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","catalogosucursal_con.php?accion=3"); 
	}
	
	function mostrarGeneral(datos){
		u.codigo.value = datos;
	}
	
function validar(){	
	if(document.getElementById('prefijo').value==""){
			alerta('Debe capturar Prefijo','¡Atención!','prefijo');
	}else if (document.getElementById('descripcion').value==""){
			alerta('Debe capturar Descripción','¡Atención!','descripcion');
	}else if (document.getElementById('idsucursal').value==""){
			alerta('Debe capturar Cuenta contable','¡Atención!','idsucursal');
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
	var horario = u.hrs.value+":"+u.min.value;	
	if(document.getElementById('lleno').value=="SI"){
		row = document.getElementById('colonia').value.split("-");
		miArray = new Array(5);
		miArray[0] = row[0];
		miArray[1] = document.getElementById('poblacion').value;
		miArray[2] = document.getElementById('municipio').value;
		miArray[3] = document.getElementById('estado').value;
		miArray[4] = document.getElementById('pais').value;	
			if(document.getElementById('accion').value==""){
				consultaTexto("registro","catalogosucursal_con.php?accion=1&prefijo="+u.prefijo.value+"&idsucursal="+u.idsucursal.value+"&descripcion="+u.descripcion.value+"&monitoreo="+((u.monitoreo.checked==true)?1:0)+"&concesion="+((u.concesion.checked==true)?1:0)+"&comision="+u.comision.value+"&ventas="+u.ventas.value+"&recibido="+u.recibido.value+"&porcead="+u.porcead.value+"&porcrecoleccion="+u.porcrecoleccion.value+"&lectores="+((u.lectores.checked==true)?1:0)+"&iva="+u.iva.value+"&bascula="+((u.bascula.checked==true)?1:0)+"&cajachica="+u.cajachica.value+"&horariolimiterecoleccion="+horario+"&calle="+u.calle.value+"&numero="+u.numero.value+"&crucecalles="+u.entrecalles.value+"&cp="+u.cp.value+"&colonia="+u.colonia.value+"&poblacion="+u.poblacion.value+"&municipio="+u.municipio.value+"&estado="+u.estado.value+"&pais="+u.pais.value+"&telefono="+u.telefono.value+"&fax="+u.fax.value+"&colonia="+miArray[0]+"&poblacion="+miArray[1]+"&municipio="+miArray[2]+"&estado="+miArray[3]+"&pais="+miArray[4]+"&frontera="+((u.frontera.checked==true)?1:0)+"&fleteenviado="+u.fleteenviado.value+"&fleterecibido="+u.fleterecibido.value+"&sobrepeso="+u.sobrepeso.value+"&zonahoraria="+u.zonahoraria.value+"&val="+Math.random());
			}else if(document.getElementById('accion').value=="modificar"){
				consultaTexto("modifico","catalogosucursal_con.php?accion=2&prefijo="+u.prefijo.value+"&idsucursal="+u.idsucursal.value+"&descripcion="+u.descripcion.value+"&monitoreo="+((u.monitoreo.checked==true)?1:0)+"&concesion="+((u.concesion.checked==true)?1:0)+"&comision="+u.comision.value+"&ventas="+u.ventas.value+"&recibido="+u.recibido.value+"&porcead="+u.porcead.value+"&porcrecoleccion="+u.porcrecoleccion.value+"&lectores="+((u.lectores.checked==true)?1:0)+"&iva="+u.iva.value+"&bascula="+((u.bascula.checked==true)?1:0)+"&cajachica="+u.cajachica.value+"&horariolimiterecoleccion="+horario+"&calle="+u.calle.value+"&numero="+u.numero.value+"&crucecalles="+u.entrecalles.value+"&cp="+u.cp.value+"&colonia="+u.colonia.value+"&poblacion="+u.poblacion.value+"&municipio="+u.municipio.value+"&estado="+u.estado.value+"&pais="+u.pais.value+"&telefono="+u.telefono.value+"&fax="+u.fax.value+"&colonia="+miArray[0]+"&poblacion="+miArray[1]+"&municipio="+miArray[2]+"&estado="+miArray[3]+"&pais="+miArray[4]+"&codigo="+u.codigo.value+"&frontera="+((u.frontera.checked==true)?1:0)+"&fleteenviado="+u.fleteenviado.value+"&fleterecibido="+u.fleterecibido.value+"&sobrepeso="+u.sobrepeso.value+"&zonahoraria="+u.zonahoraria.value+"&val="+Math.random());
			}
	}
}

	function registro(datos){
		if(datos.indexOf("guardo")>-1){
			var row = datos.split(",");
			u.codigo.value = row[1];
			document.getElementById('accion').value = "modificar";
			info('Los datos han sido guardados correctamente', 'Operación realizada correctamente');
		}else{
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
		}
	}
	
	function modifico(datos){
		if(datos.indexOf("modifico")>-1){		
			info('Los cambios han sido guardados correctamente', 'Operación realizada correctamente');
		}else{
			alerta3("Hubo un error al modificar "+datos,"¡Atención!");
		}
	}
	
	function limpiar(){
		document.getElementById('prefijo').value=""; 	document.getElementById('descripcion').value=""; 
		document.getElementById('idsucursal').value="";	document.getElementById('calle').value="";	
		document.getElementById('numero').value="";		document.getElementById('cp').value="";	
		document.getElementById('ventas').value=""; 	document.getElementById('recibido').value=""; 
		document.getElementById('porcead').value=""; 	document.getElementById('porcrecoleccion').value=""; 
		document.getElementById('lleno').value=""; 		document.form1.concesion.checked=false; 
		document.getElementById('comision').value=""; 	document.getElementById('colonia').value=""; 
		document.getElementById('poblacion').value=""; 	document.getElementById('municipio').value=""; 
		document.getElementById('estado').value=""; 	document.getElementById('pais').value=""; 
		document.getElementById('arreglo').value=""; 	document.getElementById('telefono').value=""; 
		document.getElementById('fax').value="";  		document.form1.lectores.checked=false; 
		document.getElementById('iva').value=""; 		document.getElementById('oculto').value=""; 
		document.all.bascula.checked=false; 			document.all.cajachica.value =""; 
		document.all.hrs.value="00"; 					document.all.min.value="00"; 
		document.all.entrecalles.value="";				document.getElementById('accion').value = ""; 
		document.getElementById('colonia_hidden').value = ""; u.monitoreo.checked = false;
		u.fleteenviado.value = ""; u.sobrepeso.value = "";
		u.fleterecibido.value = "";
		u.frontera.checked = false;
		obtenerGeneral();                  
	}
function limpiartodo(){
document.getElementById('oculto').value=""; document.getElementById('prefijo').value=""; document.getElementById('descripcion').value=""; document.getElementById('idsucursal').value="";	document.getElementById('calle').value="";	document.getElementById('numero').value="";	document.getElementById('cp').value="";	document.getElementById('ventas').value=""; document.getElementById('recibido').value=""; document.getElementById('porcead').value=""; document.getElementById('porcrecoleccion').value=""; document.getElementById('lleno').value=""; document.form1.concesion.checked=false; document.getElementById('comision').value=""; document.getElementById('colonia').value=""; document.getElementById('poblacion').value=""; document.getElementById('municipio').value=""; document.getElementById('estado').value=""; 	document.getElementById('pais').value=""; document.getElementById('arreglo').value=""; document.getElementById('telefono').value=""; document.getElementById('fax').value="";  document.form1.lectores.checked=false; document.getElementById('iva').value="";document.all.bascula.checked=false; document.all.cajachica.value ="";document.all.entrecalles.value=""; u.fleteenviado.value = ""; u.fleterecibido.value = ""; u.sobrepeso.value = "";
 document.all.hrs.value=""; document.all.min.value=""; u.frontera.checked = false;
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
			u.celcolonia.innerHTML = Input;
			u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
			u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
			u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;
			u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;
			u.telefono.value=datos.getElementsByTagName('telefono').item(0).firstChild.data;
			u.zonahoraria.value =datos.getElementsByTagName('zonahoraria').item(0).firstChild.data;

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
			u.comision.value = ((u.comision.value!="0")?u.comision.value:"");
			u.ventas.value=datos.getElementsByTagName('ventas').item(0).firstChild.data;
			u.recibido.value=datos.getElementsByTagName('recibido').item(0).firstChild.data;
			
			u.porcead.value=datos.getElementsByTagName('porcead').item(0).firstChild.data;
			u.porcrecoleccion.value=datos.getElementsByTagName('porcrecoleccion').item(0).firstChild.data;
					
			if(datos.getElementsByTagName('lectores').item(0).firstChild.data==1){
				u.lectores.checked=true;
			}
			if(datos.getElementsByTagName('bascula').item(0).firstChild.data==1){
				u.bascula.checked = true;
			}
			u.iva.value=datos.getElementsByTagName('iva').item(0).firstChild.data;
			u.frontera.checked = ((datos.getElementsByTagName('frontera').item(0).firstChild.data==1)?true:false);
			u.cajachica.value = datos.getElementsByTagName('cajachica').item(0).firstChild.data;
			
			var v_fleteenviado = datos.getElementsByTagName('fleteenviado').item(0).firstChild.data;
			u.fleteenviado.value = ((v_fleteenviado==0)?"":v_fleteenviado);
			
			var v_fleterecibido = datos.getElementsByTagName('fleterecibido').item(0).firstChild.data;
			u.fleterecibido.value = ((v_fleterecibido==0)?"":v_fleterecibido);
			
			var v_sobrepeso = datos.getElementsByTagName('sobrepeso').item(0).firstChild.data;
			u.sobrepeso.value = ((v_sobrepeso==0)?"":v_sobrepeso);
			
			var hora = datos.getElementsByTagName('horariolimiterecoleccion').item(0).firstChild.data;
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

function devolverColonia(){		
		if(u.coloniaid.value==""){
			setTimeout("devolverColonia()",500);
		}else{
			consultaTexto("mostrarColonia","../../buscadores_generales/consultaColonia.php?accion=1&colonia="+u.coloniaid.value);
		}
	}
	
	function mostrarColonia(datos){
		var obj = eval(convertirValoresJson(datos));				
		document.getElementById('cp').value			= obj[0].codigopostal;
		document.all.celcolonia.innerHTML 			= Input;
		document.getElementById('colonia').value	= obj[0].colonia;
		document.getElementById('poblacion').value	= obj[0].poblacion;
		document.getElementById('municipio').value	= obj[0].municipio;
		document.getElementById('estado').value		= obj[0].estado;
		document.getElementById('pais').value		= obj[0].pais;
		setTimeout("document.getElementById('telefono').focus()",500);
	}
	function obtenerColoniaValida(){
		if(u.colonia_hidden.value==""){
			alerta2("Debe capturar una colonia valida","¡Atención!","colonia");
			return false;
		}
		consultaTexto("coloniaValida","../../buscadores_generales/consultaColonia.php?accion=2&colonia="+u.colonia.value
		+"&idcolonia="+u.colonia_hidden.value+"&val="+Math.random());
	}
	function coloniaValida(datos){
		if(datos.indexOf("noexiste_xx_xxx")<0){
			var obj = eval("("+datos+")");
			document.getElementById('cp').value			= obj.codigopostal;
			document.all.celcolonia.innerHTML 			= Input;
			document.getElementById('colonia').value	= obj.colonia;
			document.getElementById('poblacion').value	= obj.poblacion;
			document.getElementById('municipio').value	= obj.municipio;
			document.getElementById('estado').value		= obj.estado;
			document.getElementById('pais').value		= obj.pais;
			setTimeout("document.getElementById('telefono').focus()",500);
		}else{
			u.coloniaid.value="";
				u.colonia.value="";
				document.getElementById('cp').value=""; document.getElementById('poblacion').value="";
				document.getElementById('municipio').value=""; document.getElementById('estado').value="";
				document.getElementById('pais').value="";
				alerta("La Colonia no existe","¡Atención!","colonia");
				return false;
		}		
	}
	function validarColonia(e,obj){
		tecla	=	(document.all) ? e.keyCode : e.which;
		if((tecla==8 || tecla==46) && document.getElementById(obj).value==""){
			document.getElementById('cp').value=""; document.getElementById('poblacion').value="";
			document.getElementById('municipio').value=""; document.getElementById('estado').value="";
			document.getElementById('pais').value="";
		}	
	}
	
	function solonumeros(evnt){
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
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
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:210px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:1em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
	</style>

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

<body >
<form name="form1" method="post" action="">
  <table width="100%" border="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="520" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
	  	<tr>
			<td class="FondoTabla">CAT&Aacute;LOGO SUCURSAL</td>
		</tr>
		
          <tr>
            <td><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="5" class="Tablas"><table width="499" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="Tablas">Codigo:</td>
                    <td colspan="3"><input class="Tablas" name="codigo" type="text" readonly="" id="codigo" size="4"  value="<?= $codigo; ?>" style="font:tahoma;font-size:9px; background:#FFFF99" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''"/>
                      <img src="../../img/Buscar_24.gif" title="Buscar Sucursal" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarsucursal.php', 600, 500, 'ventana', 'Busqueda')" /></td>
                  </tr>
                  <tr>
                    <td colspan="4" class="Tablas"><table width="284" border="0" cellspacing="0" cellpadding="0">
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
                    <td colspan="3"><input class="Tablas" name="prefijo" type="text" id="prefijo" style="font:tahoma;font-size:9px; text-transform:uppercase" onBlur="trim(document.getElementById('prefijo').value,'prefijo');" onKeyPress="return tabular(event,this)" value="<?= $prefijo; ?>" size="20" maxlength="10"/></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Descripcion:</td>
                    <td colspan="3" class="Tablas"><input class="Tablas" name="descripcion" type="text" id="descripcion" size="34" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" value="<?= $descripcion; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/>
                      &nbsp; Cuentas Contables: 
                      <input name="idsucursal" class="Tablas" type="text" id="idsucursal" size="15" onBlur="trim(document.getElementById('idsucursal').value,'idsucursal');" value="<?= $idsucursal; ?>" onKeyPress="return solonumeros(event)" onKeyDown="return tabular(event,this)" style="font:tahoma;font-size:9px"/></td>
                  </tr>
                  <tr>
                    <td width="77" class="Tablas">Calle:</td>
                    <td colspan="3" class="Tablas"><input class="Tablas" name="calle" type="text" id="calle" size="38" onBlur="trim(document.getElementById('calle').value,'calle');" value="<?=$calle; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      Numero:
                      <input name="numero" class="Tablas" type="text" id="numero" onBlur="trim(document.getElementById('numero').value,'numero');" value="<?=$numero; ?>" onKeyDown="return tabular(event,this)" style="font:tahoma;font-size:9px; text-transform:uppercase; width:95px"/></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Cruce Calles:</td>
                    <td colspan="3"><input class="Tablas" name="entrecalles" type="text" id="entrecalles" onBlur="trim(document.getElementById('entrecalles').value,'entrecalles');" value="<?= $entrecalles; ?>" style=" width:397px;font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/></td>
                  </tr>
                  <tr>
                    <td class="Tablas">C.P.:</td>
                    <td colspan="2" class="Tablas"><input class="Tablas" name="cp" type="text" id="cp" onBlur="trim(document.getElementById('cp').value,'cp'); " onKeyPress="return solonumeros(event)" onKeyDown="CodigoPostal(event,this.value); return tabular(event,this);" onKeyUp="return validaCP(event,this.name)"  value="<?= $cp; ?>" size="10" maxlength="5" style="font:tahoma;font-size:9px; text-transform:uppercase" />
                      <img src="../../javascript/loading.gif" name="imagen" width="16" height="16" align="absbottom" id="imagen" style="visibility:hidden" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Colonia:</td>
                    <td width="232" id="celcolonia"><input name="colonia" type="text" class="Tablas" id="colonia" style=" width:207px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" /></td>
                    </tr>
                  <tr>
                    <td class="Tablas">Poblaci&oacute;n:</td>
                    <td width="187"><input class="Tablas" name="poblacion" type="text" id="poblacion" style="width:145px;font:tahoma;font-size:9px; background:#FFFF99;  text-transform:uppercase" readonly=""  value="<?= $poblacion; ?>" /></td>
                    <td colspan="2" class="Tablas">Mun./Del.:&nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="municipio" class="Tablas" type="text" id="municipio" size="20"  style="width:145px;font:tahoma;font-size:9px;background:#FFFF99; text-transform:uppercase" readonly="" value="<?= $municipio; ?>" /></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Estado:</td>
                    <td><input name="estado" class="Tablas" type="text" id="estado"  value="<?= $estado; ?>" style="font:tahoma;font-size:9px;background:#FFFF99; text-transform:uppercase;width:145px" readonly="" /></td>
                    <td colspan="2" class="Tablas">Pa&iacute;s:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="pais" class="Tablas" type="text" id="pais" value="<?= $pais; ?>" style="width:145px;font:tahoma;font-size:9px;background:#FFFF99; text-transform:uppercase" readonly=""/></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Tel&eacute;fono:</td>
                    <td><input name="telefono" class="Tablas" type="text" id="telefono"  onBlur="trim(document.getElementById('telefono').value,'telefono');" value="<?= $telefono; ?>" style="width:145px;font:tahoma;font-size:9px; " onKeyPress="return tabular(event,this)" /></td>
                    <td colspan="2" class="Tablas">Fax:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="fax" class="Tablas" type="text" id="fax" onBlur="trim(document.getElementById('fax').value,'fax');" value="<?= $fax; ?>" style="width:145px;font:tahoma;font-size:9px" onKeyPress="return tabular(event,this)" /></td>
                  </tr>
                  <tr>
                    <td colspan="4">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="5" class="FondoTabla">Caracter&iacute;sticas Sucursal </td>
              </tr>
              <tr>
                <td colspan="5"><table cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="127" class="Tablas"><input name="concesion" type="checkbox" id="concesion" style="width:12px; height:12px" onClick="HabilitarComision();" value="1" <? if($concesion==1){echo "checked";} ?> onKeyPress="return tabular(event,this)">
                      Concesi&oacute;n</td>
                    <td width="82" class="Tablas">% Comisi&oacute;n:</td>
                    <td width="66"><input class="Tablas" name="comision" type="text" id="comision" style="font:tahoma;font-size:9px; background:#FFFF99" onBlur="trim(document.getElementById('comision').value,'comision');" onKeyPress="return solonumeros(event)" disabled="disabled" onKeyDown="return tabular(event,this)" value="<?= $comision; ?>" size="5" maxlength="5" />
                      <? if($concesion==1){ echo "<script>HabilitarComision()</script>";} ?></td>
                    <td width="124"><span class="Tablas">% Ventas:</span></td>
                    <td width="99"><span class="Tablas">
                      <input class="Tablas" name="ventas" type="text" id="ventas" style="font:tahoma;font-size:9px;width:73px" onBlur="trim(document.getElementById('ventas').value,'ventas');" onKeyPress="return solonumeros(event)" onKeyDown="return tabular(event,this)" value="<?=$ventas; ?>" size="5" maxlength="5" />
                    </span></td>
                  </tr>
                  <tr>
                    <td class="Tablas">% Recibido:
                      <input class="Tablas" name="recibido" type="text" id="recibido" style=" width:30px" onBlur="trim(document.getElementById('recibido').value,'recibido');" onKeyPress="return solonumeros(event)" onKeyDown="return tabular(event,this)" value="<?= $recibido; ?>"  maxlength="5"/></td>
                    <td class="Tablas">% EAD:</td>
                    <td><span class="Tablas">
                      <input class="Tablas" name="porcead" type="text" id="porcead" style="font:tahoma;font-size:9px" onBlur="trim(document.getElementById('porcead').value,'porcead');" onKeyPress="return solonumeros(event)" onKeyDown="return tabular(event,this)" value="<?=$porcead; ?>" size="5" maxlength="5"/>
                    </span></td>
                    <td class="Tablas">% Recolecci&oacute;n:</td>
                    <td><span class="Tablas">
                      <input name="porcrecoleccion" type="text" id="porcrecoleccion" style="font:tahoma;font-size:9px;width:73px" onBlur="trim(document.getElementById('porcrecoleccion').value,'porcrecoleccion');" onKeyPress="return solonumeros(event)" class="Tablas"  value="<?=$porcrecoleccion; ?>" size="5" maxlength="5" onKeyDown="return tabular(event,this)"/>
                    </span></td>
                  </tr>
                  
                  <tr>
                    <td class="Tablas">% Flete Enviado 
                      <input class="Tablas" name="fleteenviado" type="text" id="fleteenviado" style=" width:30px" onBlur="trim(document.getElementById('recibido').value,'recibido');" onKeyPress="return solonumeros(event)" onKeyDown="return tabular(event,this)" value="<?=$fleteenviado; ?>"  maxlength="5"/></td>
                    <td colspan="2" class="Tablas">% Flete Recibido 
                      <input class="Tablas" name="fleterecibido" type="text" id="fleterecibido" style=" width:30px" onBlur="trim(document.getElementById('recibido').value,'recibido');" onKeyPress="return solonumeros(event)" onKeyDown="return tabular(event,this)" value="<?=$fleterecibido; ?>"  maxlength="5"/></td>
                    <td class="Tablas">% Sobrepeso </td>
                    <td><span class="Tablas">
                      <input class="Tablas" name="sobrepeso" type="text" id="sobrepeso" style=" width:30px" onBlur="trim(document.getElementById('recibido').value,'recibido');" onKeyPress="return solonumeros(event)" onKeyDown="return tabular(event,this)" value="<?=$sobrepeso; ?>"  maxlength="5"/>
                    </span></td>
                  </tr>
                  <tr>
                    <td class="Tablas"><label>
                      <input name="bascula" type="checkbox" id="bascula" style="width:12px; height:12px" value="1" <? if($bascula==1){echo "checked";} ?> onKeyPress="return tabular(event,this)">
                    </label>
Usa Bascula</td>
                    <td colspan="2" class="Tablas"><input name="lectores" type="checkbox" id="lectores" style="width:12px; height:12px" value="1" <? if($lectores==1){echo "checked";} ?> onKeyPress="return tabular(event,this)">
Utiliza Lectores</td>
                    <td><span class="Tablas">
                      <label></label>
                    % IVA: </span></td>
                    <td><span class="Tablas">
                      <input name="iva" class="Tablas" type="text" id="iva" style="font:tahoma;font-size:9px;width:73px" onBlur="trim(document.getElementById('iva').value,'iva');" onKeyPress="return solonumeros(event)"  value="<?=$iva; ?>" maxlength="5" onKeyDown="return tabular(event,this)"/>
                    </span></td>
                  </tr>
                  
                  <tr>
                    <td class="Tablas"><label>
                      <input name="frontera" type="checkbox" id="frontera" style="width:12px; height:12px" value="1" <? if($frontera==1){echo "checked";} ?> onKeyPress="return tabular(event,this)">
                    </label> 
                      Frontera</td>
                    <td colspan="2" class="Tablas">Fondo Caja chica: 
                      <input class="Tablas" name="cajachica" type="text" id="cajachica" style="font:tahoma;font-size:9px; width:50px" onBlur="trim(document.getElementById('cajachica').value,'cajachica');" onKeyPress="return solonumeros(event)"  value="<?=$cajachica; ?>" maxlength="10" onKeyDown="return tabular(event,this)"/></td>
                    <td class="Tablas">Horario Limite Registro Recolecciones</td>
                    <td class="Tablas"><select name="hrs" class="Tablas" id="hrs" style="font-size:9px; font:tahoma;width:40px">
                      <? for($h=0;$h<24;$h++){?>
                      <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT)?>"   <? if($hrs == str_pad($h,2,"0",STR_PAD_LEFT)){echo "selected";} ?> >
                      <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                      </option>
                      <? } ?>
                    </select>
                      <select name="min" id="min" class="Tablas" style="font-size:9px; font:tahoma;width:40px">
                        <? for($m=0;$m<60;$m++){?>
                        <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"  <? if($min == str_pad($m,2,"0",STR_PAD_LEFT)){echo "selected";} ?>>
                        <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                        </option>
                        <? } ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Zona Horaria<br></td>
                    <td colspan="2" class="Tablas">
                    	<select name="zonahoraria">
                        	<option value="-06:00">ZONA CENTRO</option>
                            <option value="-07:00">ZONA DEL PACIFICO</option>
                            <option value="-08:00">ZONA DEL NORTE</option>
                        </select>
                    </td>
                    <td class="Tablas">&nbsp;</td>
                    <td class="Tablas">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="5"></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="5"><input name="lleno" type="hidden" id="lleno" value="<?=$lleno; ?>">
                  <input name="accion" type="hidden" id="accion" value="<?=$accion; ?>">
                  <input name="arreglo" type="hidden" id="arreglo" value="<?=$arreglo; ?>">
                  <span class="Tablas">
                    <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />
                    <input type="hidden" id="colonia_hidden" name="coloniaid" />
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
              <table width="33" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="33"></td>
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