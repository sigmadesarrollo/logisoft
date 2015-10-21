<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
		require_once('../../Conectar.php');	
		$link=Conectarse('webpmm');
		$usuario=$_SESSION[NOMBREUSUARIO];
		$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion']; $poblacion=$_POST['poblacion']; $municipio=$_POST['municipio']; $estado=$_POST['estado']; $restringiread=$_POST['restringiread']; $restringirrecoleccion=$_POST['restringirrecoleccion']; $sucursal=$_POST['sucursal']; $todasemana=$_POST['todasemana']; $lunes=$_POST['lunes']; $martes=$_POST['martes']; $miercoles=$_POST['miercoles']; $jueves=$_POST['jueves']; $viernes=$_POST['viernes']; $sabado=$_POST['sabado']; $costorecoleccion=$_POST['costorecoleccion']; $costoead=$_POST['costoead']; $despoblacion=$_POST['despoblacion']; $idpoblacion=$_POST['idpoblacion']; $restringircobrar=$_POST['restringircobrar']; $deshabilitarconvenio=$_POST['deshabilitarconvenio']; $restringireadapf=$_POST['restringireadapf']; $notificacion=$_POST['notificacion']; $notificaciones=$_POST['notificaciones'];
$subdestinos=$_POST['subdestinos'];
		

		if($accion==""){
			$row=folio('catalogodestino','webpmm');
			$codigo=$row[0];
			
		}elseif($accion=="limpiar"){
			
		$codigo=""; $sucursal=""; $descripcion=""; $poblacion=""; $municipio=""; $estado=""; 
		$msg=""; $todasemana=""; $lunes=""; $martes=""; $miercoles=""; $jueves=""; $viernes=""; 
		$sabado=""; $costoead=""; $costorecoleccion=""; $usuario=$_SESSION[NOMBREUSUARIO]; 
		$accion=""; $despoblacion=""; $idpoblacion="";
		$restringircobrar=""; $deshabilitarconvenio=""; $restringireadapf=""; 
		$notificaciones=''; $notificacion=''; $subdestinos='';
		$row=folio('catalogodestino','webpmm');
		$codigo=$row[0];	
	}	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/shortcut.js"></script>
<script src="select.js"></script>
<script language="JavaScript" type="text/javascript">
var u = document.all;

function limpiar(){
	u.descripcion.value="";
	u.idpoblacion.value="";
	u.despoblacion.value="";
	u.restringiread.checked=false;
	u.restringirrecoleccion.checked=false;
	u.restringircobrar.checked=false;
	u.deshabilitarconvenio.checked=false;	
	u.todasemana.checked=false;
	u.lunes.checked=false;
	u.martes.checked=false;
	u.miercoles.checked=false;
	u.jueves.checked=false;
	u.viernes.checked=false;
	u.sabado.checked=false;
	u.notificacion.checked = false;
	u.notificaciones.value = "";
	u.subdestinos.value = "";
	u.accion.value = "limpiar";
	document.form1.submit();
}
function limpiartodo(){
	u.descripcion.value="";
	u.idpoblacion.value="";
	u.despoblacion.value="";
	u.restringiread.checked=false;
	u.restringirrecoleccion.checked=false;
	u.restringircobrar.checked=false;
	u.deshabilitarconvenio.checked=false;	
	u.todasemana.checked=false;
	u.lunes.checked=false;
	u.martes.checked=false;
	u.miercoles.checked=false;
	u.jueves.checked=false;
	u.viernes.checked=false;
	u.sabado.checked=false;
	u.notificacion.checked = false;
	u.notificaciones.value = "";
	u.subdestinos.value = "";	
}

function validar(){
	if(document.getElementById('descripcion').value==""){			
			alerta('Debe capturar Descripción','¡Atención!','descripcion');
	}else if(document.getElementById('sucursal').value==""){
			alerta('Debe capturar Sucursal','¡Atención!','sucursal');			
	}else if(document.getElementById('costoead').value==""){
			alerta('Debe capturar Costo EAD','¡Atención!','costoead');
	}else if(document.getElementById('costorecoleccion').value==""){
			alerta('Debe capturar Costo Recolección','¡Atención!','costorecoleccion');
	}else{
			if(document.getElementById('accion').value==""){
				u.btnguardar.style.visibility = "hidden";
				consultaTexto("registro","catalogodestino_con.php?accion=1&descripcion="+u.descripcion.value+"&sucursal="+u.sucursal.value+"&poblacion="+u.idpoblacion.value+"&costoead="+u.costoead.value+"&costorecoleccion="+u.costorecoleccion.value+"&restringiread="+((u.restringiread.checked==true)?1:0)+"&restringirrecoleccion="+((u.restringirrecoleccion.checked==true)?1:0)+"&todasemana="+((u.todasemana.checked==true)?1:0)+"&lunes="+((u.lunes.checked==true)?1:0)+"&martes="+((u.martes.checked==true)?1:0)+"&miercoles="+((u.miercoles.checked==true)?1:0)+"&jueves="+((u.jueves.checked==true)?1:0)+"&viernes="+((u.viernes.checked==true)?1:0)+"&sabado="+((u.sabado.checked==true)?1:0)+"&restringireadapfsinconvenio="+((u.restringireadapf.checked==true)?1:0)+"&restringirporcobrar="+((u.restringircobrar.checked==true)?1:0)+"&deshabilitarconvenio="+((u.deshabilitarconvenio.checked==true)?1:0)+"&notificacion="+((u.notificacion.checked==true)?1:0)+"&notificaciones="+u.notificaciones.value+"&subdestinos="+((u.subdestinos.checked==true)?1:0));
				
			}else if(document.getElementById('accion').value=="modificar"){	
				u.btnguardar.style.visibility = "hidden";
				consultaTexto("modificar","catalogodestino_con.php?accion=2&descripcion="+u.descripcion.value+"&sucursal="+u.sucursal.value+"&poblacion="+u.idpoblacion.value+"&costoead="+u.costoead.value+"&costorecoleccion="+u.costorecoleccion.value+"&restringiread="+((u.restringiread.checked==true)?1:0)+"&restringirrecoleccion="+((u.restringirrecoleccion.checked==true)?1:0)+"&todasemana="+((u.todasemana.checked==true)?1:0)+"&lunes="+((u.lunes.checked==true)?1:0)+"&martes="+((u.martes.checked==true)?1:0)+"&miercoles="+((u.miercoles.checked==true)?1:0)+"&jueves="+((u.jueves.checked==true)?1:0)+"&viernes="+((u.viernes.checked==true)?1:0)+"&sabado="+((u.sabado.checked==true)?1:0)+"&restringireadapfsinconvenio="+((u.restringireadapf.checked==true)?1:0)+"&restringirporcobrar="+((u.restringircobrar.checked==true)?1:0)+"&deshabilitarconvenio="+((u.deshabilitarconvenio.checked==true)?1:0)+"&notificacion="+((u.notificacion.checked==true)?1:0)+"&notificaciones="+u.notificaciones.value+"&subdestinos="+((u.subdestinos.checked==true)?1:0)+"&destino="+u.codigo.value);				
			}
	}
}
	function registro(datos){
		if(datos.indexOf("guardo")>-1){
			var row = datos.split(",");
			info('Los datos han sido guardados correctamente', 'Operación realizada correctamente');
			u.codigo.value = row[1];
			u.btnguardar.style.visibility = "visible";
			document.getElementById('accion').value = "modificar";
		}else{
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
			u.btnguardar.style.visibility = "visible";
		}
	}
	
	function modificar(datos){
		if(datos.indexOf("modifico")>-1){
			info('Los cambios han sido guardados correctamente', 'Operación realizada correctamente');
			u.btnguardar.style.visibility = "visible";
		}else{
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
			u.btnguardar.style.visibility = "visible";
		}
	}
	
	function devolverPoblacion(){		
		if(u.idpoblacion.value==""){
			setTimeout("devolverPoblacion()",500);
		}else{
			consultaTexto("mostrarPoblacion", "catalogosucursal_con.php?accion=4&poblacion="+u.idpoblacion.value);
		}
	}
	
	function mostrarPoblacion(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.municipio.value 		= obj[0].municipio; 
			u.estado.value 			= obj[0].estado;
			u.despoblacion.value 	= obj[0].poblacion;
		}
	}
	
function obtener(codigo){
	if(codigo!=""){
	document.getElementById('codigo').value=codigo;
	document.getElementById('accion').value="modificar";	
	var tipo = "destino";
		consulta("mostrarObtenerDestino","consultaDestino.php?destino="+codigo+"&tipo="+tipo+"&sid="+Math.random());
	}
}

//*************MUESTRA DESTINOS*********************//
function mostrarObtenerDestino(datos){
		limpiartodo();
		var u= document.all;
		u.codigo.value = datos.getElementsByTagName('codigo').item(0).firstChild.data;
		u.sucursal.value = datos.getElementsByTagName('sucursal').item(0).firstChild.data;
		u.descripcion.value = datos.getElementsByTagName('descripcion').item(0).firstChild.data;
		u.idpoblacion.value = datos.getElementsByTagName('idpoblacion').item(0).firstChild.data;
		u.poblacion.value = datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.estado.value = datos.getElementsByTagName('estado').item(0).firstChild.data;
		u.costoead.value = datos.getElementsByTagName('costoead').item(0).firstChild.data;
		u.costorecoleccion.value = datos.getElementsByTagName('costorecoleccion').item(0).firstChild.data;
		if(datos.getElementsByTagName('restringiread').item(0).firstChild.data==1){
		u.restringiread.checked=true;
		Habilitar();
		}else{
		u.restringiread.checked=false;
		}
		//****
		if(datos.getElementsByTagName('restringireadapf').item(0).firstChild.data==1){
			u.restringireadapf.checked=true;
			Habilitar();
		}else{
			u.restringireadapf.checked=false;
		}
		//....
		if(datos.getElementsByTagName('restringirrecoleccion').item(0).firstChild.data == 1){
		u.restringirrecoleccion.checked = true;
		HabilitarRecoleccion();
		}else{
		u.restringirrecoleccion.checked=false;
		}
		if(datos.getElementsByTagName('restringircobrar').item(0).firstChild.data == 1){
		u.restringircobrar.checked = true;		
		}else{
		u.restringircobrar.checked = false;
		}
		if(datos.getElementsByTagName('deshabilitarconvenio').item(0).firstChild.data == 1){
		u.deshabilitarconvenio.checked = true;		
		}else{
		u.deshabilitarconvenio.checked = false;
		}
		/***/
		
		if(datos.getElementsByTagName('subdestinos').item(0).firstChild.data == 1){
			u.subdestinos.checked = true;		
		}else{
			u.subdestinos.checked = false;
		}
		/***/
		
		u.todasemana.checked = ((datos.getElementsByTagName('todasemana').item(0).firstChild.data == 1)?true:false);		
		u.lunes.checked = ((datos.getElementsByTagName('lunes').item(0).firstChild.data == 1)?true:false);
		u.martes.checked =((datos.getElementsByTagName('martes').item(0).firstChild.data== 1)?true:false);
		u.miercoles.checked = ((datos.getElementsByTagName('miercoles').item(0).firstChild.data == 1)?true:false);
		u.jueves.checked = ((datos.getElementsByTagName('jueves').item(0).firstChild.data == 1)?true:false);
		u.viernes.checked = ((datos.getElementsByTagName('viernes').item(0).firstChild.data== 1)?true:false);
		u.sabado.checked = ((datos.getElementsByTagName('sabado').item(0).firstChild.data == 1)?true:false);
		activarDias();
		desactivarSemana();
		u.accion.value = datos.getElementsByTagName('accion').item(0).firstChild.data;
		if(datos.getElementsByTagName('notificacion').item(0).firstChild.data==1){
			u.notificacion.checked = true;
			u.notificaciones.style.backgroundColor='';
            u.notificaciones.disabled=false;
			u.notificaciones.value = datos.getElementsByTagName('notificaciones').item(0).firstChild.data;		
		}		
}

function ObtenerMunEstado(Poblacion){
	if(Poblacion!=""){
		ConsultarPoblacion(Poblacion,'pob');
	}		
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
           if (frm.elements[i+1].disabled ==true )    
                tabular(e,frm.elements[i+1]);
            else if (frm.elements[i+1].readOnly ==true )    
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus();
            return false;
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
function eliminarapostrofo(e){
var isNS4 = (navigator.appName=="Netscape")?1:0;
if(!isNS4){
	if (e.keyCode==34 || e.keyCode==39) e.returnValue = false;
	}else{
	if (e.which==34 || e.which==39) return false;
	}
}
function Poblacion(e,obj){
	tecla=(document.all) ? e.keyCode : e.which;
    if(tecla==13 && document.getElementById('poblacion').value!=""){
consulta("mostrarConsultarPoblacion","consultaDestino.php?accion=1&poblacion="+document.all.idpoblacion.value+"&sd="+Math.random());
	}
}
function esperaPoblacion(){
	setTimeout("ObtenerPoblacion()",500);	
}
function ObtenerPoblacion(){
	var valor = document.all.idpoblacion.value;
	if(valor!=""){
consulta("mostrarConsultarPoblacion","consultaDestino.php?accion=1&poblacion="+valor+"&sd="+Math.random());
	}
}
function mostrarConsultarPoblacion(datos){
	var u= document.all;
	var cont= datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(cont>0){
			u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data; 
			u.estado.value = datos.getElementsByTagName('estado').item(0).firstChild.data;
			u.despoblacion.value = datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		}		
}

function Habilitar(){
	if(u.restringiread.checked==true){
		u.costoead.disabled=true;
		u.costoead.value = 0;
		u.costoead.style.backgroundColor='#FFFF99';
		u.todasemana.disabled=true;
		u.lunes.disabled=true;
		u.martes.disabled=true;
		u.miercoles.disabled=true;
		u.jueves.disabled=true;
		u.viernes.disabled=true;
		u.sabado.disabled=true;
	}else{
		u.costoead.disabled=false;
		//u.costoead.value="";
		u.costoead.style.backgroundColor='';
		u.todasemana.disabled=false;		
		u.lunes.disabled=false;
		u.martes.disabled=false;
		u.miercoles.disabled=false;
		u.jueves.disabled=false;
		u.viernes.disabled=false;
		u.sabado.disabled=false;
	}
}
function HabilitarRecoleccion(){
	if(u.restringirrecoleccion.checked==true){
		u.costorecoleccion.disabled=true;
		u.costorecoleccion.value = 0;
		u.costorecoleccion.style.backgroundColor='#FFFF99';		
	}else{
		u.costorecoleccion.disabled=false;
		//u.costorecoleccion.value="";
		u.costorecoleccion.style.backgroundColor='';		
	}
}
function activarDias(){
		if(u.todasemana.checked == true){
			u.lunes.disabled = true; u.martes.disabled = true;
			u.miercoles.disabled = true; u.jueves.disabled = true;
			u.viernes.disabled = true; u.sabado.disabled = true;
		}else{
			u.lunes.disabled = false; u.martes.disabled = false;
			u.miercoles.disabled = false; u.jueves.disabled = false;
			u.viernes.disabled = false; u.sabado.disabled = false;
		}
}
function desactivarSemana(){
		if(u.lunes.checked==true){u.todasemana.disabled = true;}
		if(u.martes.checked==true){u.todasemana.disabled = true;}
		if(u.miercoles.checked==true){u.todasemana.disabled = true;}
		if(u.jueves.checked==true){u.todasemana.disabled = true;}
		if(u.viernes.checked==true){u.todasemana.disabled = true;}
		if(u.sabado.checked==true){u.todasemana.disabled = true;}
		if(u.lunes.checked==false && u.martes.checked==false
		   && u.miercoles.checked==false && u.jueves.checked==false
		   && u.viernes.checked==false && u.sabado.checked==false){
			u.todasemana.disabled = false;
		}
	}
function foco(nombrecaja){
	if(nombrecaja=="codigo"){
		document.getElementById('oculto').value="1";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
		abrirVentanaFija('buscarcatdestino.php', 550, 430, 'ventana', 'Busqueda');
	}
});
</script>
<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
<title>Catálogo Destino</title>
<script type="text/javascript" src="../../javascript/ajaxlist/ajax.js"></script> 
<script type="text/javascript" src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../javascript/ajax.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../css/Tablas.css" rel="stylesheet" type="text/css">
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
<style type="text/css">	
	
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:250px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
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
.style1 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
.style2 {
	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {
	font-size: 9px;
	color: #464442;
}
.style4 {color: #025680;font-size:9px }
.style5 {color: #FFFFFF ; font-size:9px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
<!--
.Estilo3 {
	color: #FFFFFF;
	font-size: 14px;
	font-weight: bold;
}
.style6 {	color: #FFFFFF;
	font-weight: bold;
}
.Tablas #txtHint table tr .Tablas #checkbox {
	text-align: left;
}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.form1.descripcion.focus()">
<form name="form1" method="post">  
  <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0"> 
 <tr>
      <td height="50"><br><table width="403" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
                <tr>
                  <td width="96%" bordercolor="#016193" class="FondoTabla">CATÁLOGO DESTINOS</td>
                </tr>
                <tr>
                  <td height="209"><table width="401" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="62" class="Tablas">Código:</td>
                      <td width="309" class="Tablas"><input name="codigo" type="text" id="codigo" class="Tablas" style="background:#FFFF99; font:tahoma; font-size:9px" value="<?=$codigo; ?>" size="10" maxlength="0" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" >
                      <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Destino" onClick="abrirVentanaFija('buscarcatdestino.php', 600, 500, 'ventana', 'Busqueda')"/></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="Tablas"><div id="txtHint">
                <table width="400" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="62" class="Tablas">Descripci&oacute;n:</td>
                    <td colspan="2"><input name="descripcion" type="text" class="Tablas" id="descripcion" style="font-size:9px; text-transform:uppercase; width:250px" onKeyPress="return tabular(event,this)" value="<?=$descripcion; ?>" maxlength="60"></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Sucursal:</td>
                    <td colspan="2"><?
	  		$sqlsuc=mysql_query('SELECT id, descripcion FROM catalogosucursal ORDER BY descripcion',$link);	
		?>
                      <select onKeyPress="return tabular(event,this)" name="sucursal" class="Tablas" id="sucursal" style="font-size:9px; text-transform:uppercase;width:250px" >
                      <option value="0" selected="selected">Seleccionar Sucursal</option>
                      <?
		while($res=mysql_fetch_row($sqlsuc)){?>
                      <option value="<?=$res[0]?>" <? if($res[0]==$sucursal) echo "selected";?> >
                      <?=htmlentities($res[1]);?>
                      </option>
                      <? } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Poblaci&oacute;n:</td>
                    <td colspan="2"><input name="poblacion" type="text" class="Tablas" id="poblacion" style="font-size:9px; text-transform:uppercase; width:250px"  onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-destino.php')"  value="<?=$poblacion ?>" onKeyPress="if(event.keyCode==13){devolverPoblacion();}" onBlur="if(document.all.idpoblacion.value!=''){devolverPoblacion();}" >					
					</td>
                  </tr>
                  <tr>
                    <td class="Tablas">Mun./Del.:</td>
                    <td colspan="2"><input class="Tablas" name="municipio" type="text" id="municipio" style="font-size:9px; text-transform:uppercase; background-color: #FFFF99; width:250px" value="<?=$municipio; ?>"  readonly></td>
                  </tr>
                  <tr>
                    <td class="Tablas">Estado:</td>
                    <td colspan="2"><input class="Tablas" name="estado" type="text" id="estado" style="font-size:9px; text-transform:uppercase; background-color: #FFFF99; width:250px" value="<?=$estado; ?>" readonly></td>
                  </tr>
                  
                  <tr>
                    <td class="Tablas">Costo EAD: </td>
                    <td colspan="2" class="Tablas"><input name="costoead" type="text" class="Tablas" id="costoead" style="font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)" value="<?=$costoead; ?>" size="8" maxlength="10">
                      &nbsp;&nbsp;&nbsp;&nbsp;Costo Recolección:
                      <input name="costorecoleccion" type="text" class="Tablas" id="costorecoleccion" style="font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)" value="<?=$costorecoleccion; ?>" size="8" maxlength="10"></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td width="142" class="Tablas"><input type="checkbox" name="restringiread" onKeyPress="return tabular(event,this)" onClick="Habilitar();" style="width:13px" value="1" <? if($restringiread==1){echo'checked';} ?>>
  Restringir EAD &nbsp;&nbsp;</td>
                    <td width="129" class="Tablas"><input type="checkbox" onKeyPress="return tabular(event,this)" style="width:13px" onClick="HabilitarRecoleccion()" name="restringirrecoleccion" value="1" <? if($restringirrecoleccion==1){echo'checked';} ?>>
Restringir Recolecci&oacute;n</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td class="Tablas"><input name="restringircobrar" type="checkbox" id="restringircobrar" style="width:13px"  onKeyPress="return tabular(event,this)" value="1" <? if($restringircobrar==1){echo'checked';} ?>>
Restringir por Cobrar </td>
                    <td class="Tablas"><input name="deshabilitarconvenio" type="checkbox" id="deshabilitarconvenio" style="width:13px" onKeyPress="return tabular(event,this)" value="1" <? if($deshabilitarconvenio==1){echo'checked';} ?>>
Deshabilitar Convenio</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td class="Tablas"><input name="restringireadapf" type="checkbox" id="restringireadapf" style="width:13px"  onKeyPress="return tabular(event,this)" value="1" <? if($restringireadapf==1){echo'checked';} ?> >
Restringir EAD a PF Sin Convenio </td>
                    <td class="Tablas"><label>
                      <input name="subdestinos" type="checkbox" id="subdestinos" style="width:13px" onClick="HabilitarRecoleccion()" onKeyPress="return tabular(event,this)" value="1" <? if($subdestinos=="1"){echo 'checked';}?>>
                    </label>
                      Sucursal</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td valign="top" class="Tablas"><input name="notificacion" type="checkbox" id="notificacion" style="width:13px" onClick="if(!this.checked){                    
                    document.all.notificaciones.value='';
                    document.all.notificaciones.style.backgroundColor='#FFFF99';
                    document.all.notificaciones.disabled=true;
                    }else{
                    document.all.notificaciones.style.backgroundColor='';
                    document.all.notificaciones.disabled=false;
                    document.all.notificaciones.focus();
                    }"  onKeyPress="return tabular(event,this)" value="1" <? if($restringireadapf==1){echo'checked';} ?>>
                      Notificaciones                        </td>
                    <td class="Tablas"><label>
                      <textarea name="notificaciones" class="Tablas" cols="45" rows="3" <? if($restringireadapf!=1){echo'disabled';} ?> id="notificaciones" style="width:160px; height:60px; background:#FF9; text-transform:uppercase"><?=$notificaciones ?></textarea>
                    </label></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="FondoTabla">Dias Entrega</td>
                    </tr>
                  <tr>
                    <td colspan="3" class="Tablas"><label>
                      <input name="todasemana" type="checkbox" id="todasemana" onClick="activarDias()" value="1" <? if($todasemana==1){echo'checked';} ?> >
                    </label>
                      Toda Semana
  <input name="lunes" onKeyPress="return tabular(event,this)" type="checkbox" id="lunes" style="width:13px" onClick="desactivarSemana()" value="1" <? if($lunes==1){echo'checked';} ?> >
Lun
<input name="martes" onKeyPress="return tabular(event,this)" type="checkbox" id="martes" style="width:13px" onClick="desactivarSemana()" value="1" <? if($martes==1){echo'checked';} ?>>
Mar
<input name="miercoles" onKeyPress="return tabular(event,this)" type="checkbox" id="miercoles" style="width:13px" onClick="desactivarSemana()" value="1" <? if($miercoles==1){echo'checked';} ?>>
Mier
<input name="jueves" onKeyPress="return tabular(event,this)" type="checkbox" id="jueves" style="width:13px" onClick="desactivarSemana()" value="1" <? if($jueves==1){echo'checked';} ?>>
Jue
<input name="viernes" onKeyPress="return tabular(event,this)" type="checkbox" id="viernes" style="width:13px" value="1" <? if($viernes==1){echo'checked';} ?>>
Vie
<input name="sabado" onClick="desactivarSemana()" onKeyPress="return tabular(event,this)" type="checkbox" id="sabado" style="width:13px" value="1" <? if($sabado==1){echo'checked';} ?>>
Sab</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="Tablas"><script>Habilitar();</script><script>HabilitarRecoleccion();</script></td>
                  </tr>
                </table>
            </div></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="Tablas">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" class="Tablas"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />
                        <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />
                        <input name="idpoblacion" type="hidden" id="poblacion_hidden" value="<?=$idpoblacion ?>" />
                        <input name="despoblacion" type="hidden" id="despoblacion" value="<?=$despoblacion ?>" />
                        <table width="20" border="0" align="right" cellpadding="0" cellspacing="0">
                          <tr>
                            <td><img id="btnguardar" src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" style="cursor:pointer" onClick="validar();" ></td>
                            <td><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" style="cursor:pointer" onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'limpiar();', '')" ></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="Tablas"><table width="33" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="33"></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table>
                  </td>
                </tr>                
              </table>
        </td>
    </tr>
  </table>   
</form>
</body>
</html>