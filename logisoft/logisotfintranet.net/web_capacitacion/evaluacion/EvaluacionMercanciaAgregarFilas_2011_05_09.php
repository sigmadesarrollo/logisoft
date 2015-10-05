<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/	
	require_once('../Conectar.php');	
	$link			= Conectarse('webpmm');
	$cantidad		= $_GET['cantidad'];
	$descripcion	= $_GET['descripcion'];
	$contenido		= $_GET['contenido'];
	$peso			= $_GET['peso'];
	$largo			= $_GET['largo'];
	$ancho			= $_GET['ancho'];
	$alto			= $_GET['alto'];
	$volumen		= $_GET['volumen'];	
	$pesototal		= $_GET['pesototal'];
	$pesobascula	= $_GET['pesobascula'];
	$iddescripcion	= $_GET['id'];
	$pesounit		= $_GET['pesounit'];
	$esmodificar	= $_GET['esmodificar'];
	$fechahora		= $_GET['fechahora'];
	if($_GET[espesototal]==1 && $peso==""){
		$peso = 0;
		$pesototal = 0;
	}
	
	$s = "SELECT bascula FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL];
	$r = mysql_query($s,$link) or die($s);
	$ff = mysql_fetch_object($r);
	
	$result=mysql_query("SELECT descripcion FROM contenidos",$link);
	if(mysql_num_rows($result)>0){
		while($con=mysql_fetch_array($result)){
			$cadena= "'".utf8_decode($con[0])."'".','.$cadena;			
		}
		$cadena=substr($cadena, 0, -1);
	}
	$s = mysql_query("SELECT CONCAT_WS(':',descripcion,id) AS descripcion FROM catalogodescripcion",$link);
	if(mysql_num_rows($s)>0){
		while($f=mysql_fetch_array($s)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}	
		$desc=substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<?
	if($ff->bascula!=0){
?>
    <OBJECT ID="clsBalanza" style="display:none" CLASSID="CLSID:9FFC4EAB-D1CB-4E7B-8582-A8DD90AD57E5">
    </OBJECT>
    <OBJECT ID="cPeso" style="display:none" CLASSID="CLSID:195ED6FA-B663-49A4-AA32-69398F264F2B">
    </OBJECT>
<?
	}
?>

<script src="js/ajax.js"></script> 
<script src="../javascript/moautocomplete.js"></script>
<script src="shortcut.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script>

	var u = document.all;
	var mens = new ClaseMensajes();
	var espesototal = <?=$_GET[espesototal]?>;
	
	window.onload = function(){
		mens.iniciar('../javascript',false);
		u.cantidad.focus();
		if(u.h_bascula.value == "0"){
			u.btn_Peso.style.visibility = "hidden";
		}		
	}
	
	function ObtenerPeso(){
		var pesar = new ActiveXObject("DpsDrvBal.clsBalanza")
		pesar.LeerConfig("C:/DPSoft/Bascula/balanza.cfg");	
		if(pesar.Pesar() == 0){		
			u.peso.value = parseFloat(pesar.Peso.Valor);
			u.peso.focus();
			u.largo.focus();		
		}else{
			mens.show('A','Para obtener el peso de la bascula, debe estar conectada al ordenador','¡Atención!','peso');
		}	
	}

	function popUp(URL){
		if(URL!=""){
			if(document.getElementById('abierto').value==""){
			document.getElementById('abierto').value="abierto";
			day = new Date();
			id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=530,height=380,left = 470,top = 200');");				
			}else{
				mens.show('A','Ya Se Encuentra Abierta la busqueda','¡Atención!','descripcion');
			}
		}
	}

	function Validar(){
		if(u.cantidad.value=="0"){
			alerta3("Proporcione una cantidad  mayor a 0","¡Atención!");			
			return false;
		}
		
		if(u.esmodificar.value == "" && parent.tabla1.getRecordCount()==1 && parent.document.all.prepagada.value==1){
			alerta3("Solo puede registrar 1 articulo para las guias prepagadas","¡Atención!");			
			return false;
		}
		
		if(u.img_agregar.style.visibility == "hidden"){
			return false;
		}
		if(document.getElementById('cantidad').value==""){
			mens.show('A','Debe Capturar Cantidad','¡Atención!','cantidad');
		}else if(document.getElementById('cantidad').value<0){
			mens.show('A','Cantidad Debe ser Mayor a Cero','¡Atención!','cantidad');
		}else if(document.getElementById('descripcion_hidden').value==undefined || document.getElementById('descripcion').value==""){
			mens.show('A','Debe Capturar Descripción','¡Atención!','descripcion');
		}else if(document.getElementById('contenido').value==""){
			mens.show('A','Debe Capturar Contenido','¡Atención!','contenido');
		}else if(document.getElementById('peso').value==""){
			mens.show('A','Debe Capturar Peso','¡Atención!','peso');	
		}else if(document.getElementById('peso').value<0){
			mens.show('A','Peso Debe ser Mayor a Cero','¡Atención!','peso');	
		}else if(document.getElementById('largo').value==""){
			mens.show('A','Debe Capturar Largo','¡Atención!','largo');
		}else if(document.getElementById('largo').value<0){ 
			mens.show('A','Largo Debe ser Mayor a Cero','¡Atención!','largo');
		}else if(document.getElementById('ancho').value==""){ 
			mens.show('A','Debe Capturar Ancho','¡Atención!','ancho');
		}else if(document.getElementById('ancho').value<0){
			mens.show('A','Ancho Debe ser Mayor a Cero','¡Atención!','ancho');
		}else if(document.getElementById('alto').value==""){
			mens.show('A','Debe Capturar Alto','¡Atención!','alto');
		}else if(document.getElementById('alto').value<0){
			mens.show('A','Alto Debe ser Mayor a Cero','¡Atención!','alto');
		}else if(u.iddescripcion.value=='undefined' || u.iddescripcion.value=='' || u.iddescripcion.value=='0'){
			mens.show('A','Capture una descripcion valida','¡Atención!','alto');
		}else{			
			u.img_agregar.style.visibility = "hidden";
			/*var arr = new Array();
			arr[0] = u.cantidad.value;
			arr[1] = u.iddescripcion.value;
			arr[2] = u.peso.value;
			arr[3] = u.largo.value;
			arr[4] = u.alto.value;
			arr[5] = u.ancho.value;
			arr[6] = u.volumen.value;
			arr[7] = u.pesototal.value;
			arr[8] = ((u.pesounit.checked==true)? 1 : 0);
			arr[9] = (u.chk_pesototal.checked)?"1":"0";
			if(u.esmodificar.value == ""){
				consultaTexto("registroEvaluacion","evaluacionMercancia_con.php?accion=2&arre="+arr+"&contenido="+u.contenido.value);
			}else{
				consultaTexto("modificarEvaluacion","evaluacionMercancia_con.php?accion=13&arre="+arr+"&contenido="+u.contenido.value+"&fecha="+u.fechahora.value);
			}*/
			
			var objeto = new Object();
			objeto.cantidad		=	u.cantidad.value; 
			objeto.id			=	u.iddescripcion.value;
			objeto.descripcion	=	u.descripcion.value;
			objeto.contenido	=	u.contenido.value;
			objeto.peso			=	u.peso.value;
			objeto.largo		=	u.largo.value;	
			objeto.alto			=	u.alto.value;
			objeto.ancho		=	u.ancho.value; 	
			objeto.volumen		=	u.volumen.value;
			objeto.pesototal	=	u.pesototal.value;
			objeto.pesounit		=	((u.pesounit.checked==true)? 1 : 0);
			objeto.fecha		=	fechahora('');
			objeto.espesototal	=	(u.chk_pesototal.checked)?"1":"0";
			if(espesototal==0){
				espesototal = (u.chk_pesototal.checked)?"1":"0";
			}
			
			
			limpiar();
			parent.<?=$_GET[funcion]?>(objeto<?=($_GET[eliminar]==1)?",1":"";?>);
			u.img_agregar.style.visibility = "visible";
		}
	}
	
	function registroEvaluacion(datos){		
		if(datos.indexOf("ok")>-1){
			u.img_agregar.style.visibility = "visible";
			var fe = datos.split(",");
			var objeto = new Object();
			objeto.cantidad		=	u.cantidad.value; 
			objeto.id			=	u.iddescripcion.value;
			objeto.descripcion	=	u.descripcion.value;
			objeto.contenido	=	u.contenido.value;
			objeto.peso			=	u.peso.value;
			objeto.largo		=	u.largo.value;	
			objeto.alto			=	u.alto.value;
			objeto.ancho		=	u.ancho.value; 	
			objeto.volumen		=	u.volumen.value;
			objeto.pesototal	=	u.pesototal.value;
			objeto.pesounit		=	((u.pesounit.checked==true)? 1 : 0);
			objeto.fecha		=	fechahora('');
			objeto.espesototal	=	(u.chk_pesototal.checked)?"1":"0";
			if(espesototal==0){
				espesototal = (u.chk_pesototal.checked)?"1":"0";
			}
			limpiar();
			parent.<?=$_GET[funcion]?>(objeto<?=($_GET[eliminar]==1)?",1":"";?>);
			//mens.show('I','Los datos han sido agregados satisfactoriamente','');
		}else{
			u.img_agregar.style.visibility = "visible";
			alerta3("Hubo un Error al agregar "+datos,"¡Atención!");
		}
	}

	function modificarEvaluacion(datos){
		if(datos.indexOf("ok")>-1){
			u.img_agregar.style.visibility = "visible";
			var objeto = new Object();
			objeto.cantidad		=	u.cantidad.value; 
			objeto.id			=	u.iddescripcion.value;
			objeto.descripcion	=	u.descripcion.value;
			objeto.contenido	=	u.contenido.value;
			objeto.peso			=	u.peso.value;
			objeto.largo		=	u.largo.value;	
			objeto.alto			=	u.alto.value;
			objeto.ancho		=	u.ancho.value; 	
			objeto.volumen		=	u.volumen.value;
			objeto.pesototal	=	u.pesototal.value;
			objeto.pesounit		=	((u.pesounit.checked==true)? 1 : 0);
			objeto.fecha		=	u.fechahora.value;
			objeto.espesototal	=	(u.chk_pesototal.checked)?"1":"0";
			if(espesototal==0){
				espesototal = (u.chk_pesototal.checked)?"1":"0";
			}
			limpiar();
			parent.<?=$_GET[funcion]?>(objeto<?=($_GET[eliminar]==1)?",1":"";?>);
			//mens.show('I','Los datos han sido agregados satisfactoriamente','');
		}else{
			u.img_agregar.style.visibility = "visible";
			alerta3("Hubo un Error al agregar "+datos,"¡Atención!");
		}
	}



function CalcularVolumenFoco(){
	if(document.all.alto.value!=""){
		if(document.getElementById('largo').value >=0 &&
		   document.getElementById('largo').value >=0 &&
		   document.getElementById('ancho').value >=0 &&
		   document.getElementById('alto').value>=0){	
		   if(document.all.pesounit.checked==true){
		   document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
		   }else{
		   document.getElementById('volumen').value= ((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000);
		   }
		}
		esNan('volumen');
	}
}

function CalcularVolumen(e){
	if(e == 13){
		tecla = 13;
	}else{
		tecla=(document.all) ? e.keyCode : e.which;
	}
	if(tecla==13){
		if(document.getElementById('largo').value >=0 &&
		   document.getElementById('largo').value >=0 &&
		   document.getElementById('ancho').value >=0 &&
		   document.getElementById('alto').value>=0){	
		   if(document.all.pesounit.checked==true){
		   document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
		   }else{
		   document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000);
		   }
		}
		esNan('volumen');
	}
}
	function CalcularUnitarioFoco(){
		var u = document.all;
		if(u.peso.value!=""){
			if(u.pesounit.checked==true && u.chk_pesototal.checked==false){
				u.pesototal.value = parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
			}else{
				u.pesototal.value = u.peso.value;
			}
		}
	}

	function CalcularUnitario(e){
		tecla=(document.all) ? e.keyCode : e.which;
		var u = document.all;
		if(tecla==13){
			if(u.pesounit.checked==true && u.chk_pesototal.checked==false){
				u.pesototal.value=parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
			}else{
				u.pesototal.value= u.peso.value;
			}
		}
	}

	function CalcularUnitarioCheck(){
	var u = document.all;
		if(u.pesounit.checked==true && u.chk_pesototal.checked==false){
			if(u.peso.value!=""){
				u.pesototal.value=parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
			}else{
				u.pesototal.value="";
			}
	document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
		}else{
			u.pesototal.value= u.peso.value;
			document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000);
			if(u.pesounit.checked==true && u.chk_pesototal.checked==true){
				document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*
										parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * 
										parseFloat(document.getElementById('cantidad').value);
			}
		}
		esNan('volumen');
	}
	
	function esNan(caja){
		if(document.getElementById(caja).value == "NaN"){
			document.getElementById(caja).value = 0;
		}
	}
	
	function limpiar(){
		document.getElementById('cantidad').value="";
		document.getElementById('descripcion_hidden').value="";
		document.getElementById('descripcion').value="";
		document.getElementById('contenido').value="";
		document.getElementById('peso').value="";
		document.getElementById('largo').value="";
		document.getElementById('alto').value="";
		document.getElementById('ancho').value="";
		document.getElementById('volumen').value="";
		document.getElementById('pesototal').value="";
		document.all.pesounit.checked=false;
		u.fechahora.value = "";
		u.usuario.value = "";
		u.id.value = "";
		u.abierto.value = "";
		u.oculto.value = "";		
		u.iddescripcion.value = "";
		u.pesobascula.value = "";
		u.esmodificar.value = "";		
		if(espesototal==1){
			document.getElementById('peso').value="0";
			document.getElementById('peso').readOnly=true;
			document.getElementById('pesototal').value="0";
			u.chk_pesototal.checked = false;
			u.chk_pesototal.disabled = true;
		}
	}

	function obtener(id,descripcion){
		document.getElementById('descripcion').value=descripcion;
		document.getElementById('descripcion_hidden').value=id;
		document.getElementById('abierto').value="";
	}	
	
	function trim(cadena,caja){
		for(i=0;i<cadena.length;){
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(i+1, cadena.length);
			else
				break;
		}
		for(i=cadena.length-1; i>=0; i=cadena.length-1){
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(0,i);
			else
				break;
		}
		document.getElementById(caja).value=cadena;
	}

	function tabular(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
		if(tecla!=13) return;
		frm=obj.form;
		for(i=0;i<frm.elements.length;i++) 
			if(frm.elements[i]==obj){ 
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



	function obtenerDescripcionValida(){
		consultaTexto("descripcionValida","evaluacionMercancia_con.php?accion=12&descripcion="+u.descripcion.value);	
	}
	function descripcionValida(datos){
		if(datos.indexOf("no")>-1){
			if(u.descripcion.value!=""){
				u.iddescripcion.value="";
				u.descripcion.value="";
				alerta("La Descripción no es valida","¡Atención!","descripcion");
				return false;
			}
		}else{
			var row = datos.split(",");
			u.iddescripcion.value = row[1];
		}
		document.getElementById('img_agregar').style.display='';
	}

	function SoloND(evnt,contenido){
		evnt = (evnt) ? evnt : event;
		var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
		if ((contenido.indexOf(".") != -1) && (charCode==46)) return false;
		if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode!=46)) return false;
		return true;
	}

	function validaDescripcion(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
		if((tecla==8 || tecla==46)&& document.getElementById(obj).value==""){
			document.getElementById('iddescripcion').value=""; 
		}	
	}
	
	function foco(nombrecaja){
		if(nombrecaja=="descripcion"){
			document.getElementById('oculto').value="1";
		}	
	}

shortcut.add("Ctrl+b",function() {
	if(document.all.oculto.value=="1"){
	popUp('buscar.php?tipo=descripcion');
	}
});



var concep = new Array(<?php echo $cadena; ?>);
var desc 	= new Array(<?php echo $desc; ?>);
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Datos Evaluaci&oacute;n</title>
<script type="text/javascript" src="js/ajax-dynamic-list.js"></script>
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../javascript/funciones.js"></script>
<style type="text/css">	
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
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
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
</head>
<BODY>
<br>
<form id="form1" name="form1" method="post" action="">
<center>
  <table width="200" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="3" height="3" background="../img/Ccaf1.jpg"></td>
          <td bgcolor="dee3d5"></td>
          <td width="3"  background="../img/Ccaf2.jpg"></td>
        </tr>
        <tr bgcolor="dee3d5">
          <td height="26"></td>
          <td ><table width="330" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="71" class="Tablas">Cantidad:</td>
                <td colspan="4" class="Tablas"><label>
                  <input name="cantidad" type="text" class="Tablas" id="cantidad" onkeypress="if(event.keyCode==13){ if(this.value!='' && parent.document.all.prepagada.value==1 && parseFloat(this.value)>1){this.value=1; mens.show('A','En las guias prepagadas solo se puede documentar un articulo','ATENCION')} document.all.descripcion.focus();} return solonumeros(event);" value="<?=$cantidad ?>" style="width:50px" maxlength="5" onblur="if(this.value!='' && parent.document.all.prepagada.value==1 && parseFloat(this.value)>1){this.value=1; mens.show('A','En las guias prepagadas solo puede poner una cantidad','ATENCION')}" />
                  <input name="pesounit" type="checkbox" onclick="CalcularUnitarioCheck()" id="pesounit" onkeypress="return tabular(event,this)" value="1" <? if($pesounit==1){ echo 'checked';} ?> />
                  Peso y Medidas Unitarias </label></td>
              </tr>
              <tr>
                <td class="Tablas">Descripci&oacute;n:</td>
                <td colspan="4" class="Tablas"><input name="descripcion" type="text" class="Tablas" id="descripcion" style="text-transform:uppercase" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.iddescripcion.value=this.codigo; document.all.contenido.focus();}" onKeyDown="if(event.keyCode==9){document.all.iddescripcion.value=this.codigo;}" onKeyUp="return validaDescripcion(event,this.name)" value="<?=$descripcion ?>" size="30" maxlength="50" onblur="if(this.value!=''){document.getElementById('img_agregar').style.display='none'; setTimeout('obtenerDescripcionValida()',500); document.getElementById('oculto').value=''}" />
                    <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onclick="javascript:popUp('buscar.php?tipo=descripcion')" style="cursor:pointer" /></td>
              </tr>
              <tr>
                <td class="Tablas">Contenido:</td>
                <td colspan="4" class="Tablas"><input name="contenido" type="text" class="Tablas" id="contenido" style="text-transform:uppercase; font:tahoma" onblur="trim(document.getElementById('contenido').value,'contenido');" onkeypress="return tabular(event,this)" value="<?=$contenido ?>" size="42" maxlength="50" autocomplete="array:concep" />                </td>
              </tr>
              <tr>
                <td class="Tablas">Peso:</td>
                <td width="81" class="Tablas">
				<input name="peso" type="text" class="Tablas" id="peso" onblur="CalcularUnitarioFoco()"  onkeypress="return SoloND(event,this.value)" onkeydown="CalcularUnitario(event); return tabular(event,this)" value="<?=$peso ?>" size="10" maxlength="15" <? if($_GET[espesototal]==1 && $peso==0){ echo "readonly=''"; } ?>  <? if($ff->bascula==1){ echo "readonly=''";} ?>/></td>
                <td width="51" class="Tablas"><div class="ebtn_peso" id="btn_Peso" onclick="ObtenerPeso();" style="cursor:pointer"></div></td>
                <td width="44" class="Tablas">Largo:</td>
                <td width="83" class="Tablas"><input name="largo" type="text" class="Tablas" id="largo"  onkeypress="return SoloND(event,this.value)" onkeydown="return tabular(event,this)" value="<?=$largo ?>" size="7" maxlength="10" />
                  cm</td>
              </tr>
              <tr>
                <td class="Tablas">Ancho:&nbsp;</td>
                <td colspan="2" class="Tablas"><input name="ancho" type="text" class="Tablas" id="ancho" onkeydown="return tabular(event,this)" onkeypress="return SoloND(event,this.value)" value="<?=$ancho ?>" size="10" maxlength="10" />
                  cm</td>
                <td class="Tablas">Alto:</td>
                <td class="Tablas"><input name="alto" type="text" class="Tablas" id="alto" onblur="CalcularVolumenFoco()" onkeypress="if(event.keyCode==13){document.all.pesounit.focus();Validar();}else{return SoloND(event,this.value)}" onkeydown="CalcularVolumen(event);" value="<?=$alto ?>" size="7" maxlength="10" />
                  cm</td>
              </tr>
              <tr>
                <td class="Tablas">Peso Total: </td>
                <td class="Tablas"><input name="pesototal" type="text" class="Tablas" id="pesototal" value="<?=$pesototal ?>" size="10" readonly="" style="background:#FFFF99" /></td>
                <td colspan="2" class="Tablas">Peso Volum&eacute;trico:</td>
                <td class="Tablas"><input name="volumen" type="text" class="Tablas" id="volumen" value="<?=$volumen ?>" size="9" readonly="" style="background:#FFFF99" /></td>
              </tr>
              <tr>
                <td class="Tablas">&nbsp;</td>
                <td colspan="4" class="Tablas">
                	<input type="checkbox" name="chk_pesototal"<? if($_GET[espesototal]==1 && $peso > 0){ echo "checked='checked'";}?> <? if($_GET[espesototal]==1 && $peso==0){ echo "disabled='true'"; }?>
                    onclick="CalcularUnitarioCheck()" />Peso total
                    </td>
                </tr>
              <tr>
                <td colspan="5">
					<input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora ?>" />
                    <input name="usuario" type="hidden" id="usuario" value="<?=$usuario ?>" />
                    <input name="id" type="hidden" id="id" value="<?=$id ?>" />
                    <input name="abierto" type="hidden" id="abierto" value="<?=$abierto ?>" />
                    <input name="oculto" type="hidden" id="oculto" />                   
                    <input name="iddescripcion" type="hidden" id="descripcion_hidden" value="<?=$iddescripcion ?>" />
                    <input name="pesobascula" type="hidden" id="pesobascula" value="<?=$pesobascula ?>" />
                    <input name="esmodificar" type="hidden" id="esmodificar" value="<?=$esmodificar ?>" />                    
                    <input name="h_bascula" type="hidden" id="h_bascula" value="<?=$ff->bascula ?>" />
                    <table width="100" border="0" align="right" cellpadding="0" cellspacing="0">
                      <tr>
                        <td><img src="../img/Boton_Agregari.gif" alt="Guardar" name="img_agregar" width="70" height="20" id="img_agregar" style="cursor:pointer" onclick="CalcularVolumen(13); Validar();" /></td>
                        <td><img src="../img/Boton_Cerrar_.gif" alt="Cerrar" width="70" height="20" style="cursor:pointer" onclick="parent.alCerrar(); parent.VentanaModal.cerrar()" /></td>                       
                      </tr>
                  </table></td>
              </tr>
          </table></td>
          <td></td>
        </tr>
        <tr>
          <td width="3" height="3"  background="../img/Ccaf3.jpg"></td>
          <td bgcolor="dee3d5"></td>
          <td width="3"  background="../img/Ccaf4.jpg"></td>
        </tr>
      </table></td>
    </tr>
  </table>
  </center>
  </form>
</body>
</html>