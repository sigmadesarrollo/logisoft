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
	$iddescripcion	= $_GET['id'];
	$pesototal		= $_GET['pesototal'];
	$pesounit		= $_GET['pesounit'];
	$esmodificar	= $_GET['esmodificar'];
	$fechahora		= $_GET['fechahora'];
	
	$result=mysql_query("SELECT descripcion FROM contenidos",$link);
	if(mysql_num_rows($result)>0){
		while($con=mysql_fetch_array($result)){
			$cadena= "'".utf8_encode($con[0])."'".','.$cadena; 	
		}	
		$cadena=substr($cadena, 0, -1);
	}
	
	$s = mysql_query("SELECT CONCAT_WS(':',descripcion,id) AS descripcion FROM catalogodescripcion",$link);
	if(mysql_num_rows($s)>0){
		while($f=mysql_fetch_array($s)){
			$desc= "'".utf8_encode($f[0])."'".','.$desc; 	
		}	
		$desc=substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	window.onload = function(){
		mens.iniciar('../javascript',false);
		u.cantidad.focus();		
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
		if(u.img_agregar.style.visibility == "hidden"){
				return false;
		}
		if(document.getElementById('cantidad').value==""){
			mens.show('A','Debe Capturar Cantidad','¡Atención!','cantidad'); 
		}else if(document.getElementById('cantidad').value<0){ 
			mens.show('A','Cantidad Debe ser Mayor a Cero','¡Atención!','cantidad');	
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
		}else{
			u.img_agregar.style.visibility = "hidden";			
			if(u.esmodificar.value == ""){
				consultaTexto("registro","recoleccion_consultas.php?accion=6&tipo=guardar&cantidad="+u.cantidad.value
				+"&iddescripcion="+u.iddescripcion.value+"&descripcion="+u.descripcion.value+"&contenido="+u.contenido.value
				+"&peso="+u.peso.value+"&largo="+u.largo.value+"&alto="+u.alto.value
				+"&ancho="+u.ancho.value+"&volumen="+u.volumen.value+"&pesototal="+u.pesototal.value
				+"&pesounit="+((u.pesounit.checked==true)? 1 : 0));
			}else{
				consultaTexto("modificar","recoleccion_consultas.php?accion=6&tipo=modificar&cantidad="+u.cantidad.value
				+"&iddescripcion="+u.iddescripcion.value+"&descripcion="+u.descripcion.value+"&contenido="+u.contenido.value
				+"&peso="+u.peso.value+"&largo="+u.largo.value+"&alto="+u.alto.value
				+"&ancho="+u.ancho.value+"&volumen="+u.volumen.value+"&pesototal="+u.pesototal.value
				+"&pesounit="+((u.pesounit.checked==true)? 1 : 0)+"&fecha="+u.fechahora.value);
			}
		} 
	}
	function registro(datos){		
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
			objeto.fecha		=	fe[1];
			limpiar();
			parent.agregarDatos(objeto);
			mens.show('I','Los datos han sido agregados satisfactoriamente','');
			parent.VentanaModal.cerrar()	
		}else{
			u.img_agregar.style.visibility = "visible";
			mens.show("A","Hubo un Error al agregar "+datos,"¡Atención!");
			parent.VentanaModal.cerrar()	
		}
	}

	function modificar(datos){
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
			limpiar();
			parent.agregarDatos(objeto);
			mens.show('I','Los datos han sido agregados satisfactoriamente','');
			parent.VentanaModal.cerrar()	
		}else{
			u.img_agregar.style.visibility = "visible";
			mens.show("A","Hubo un Error al agregar "+datos,"¡Atención!");
			parent.VentanaModal.cerrar()	
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
			   document.getElementById('volumen').value=
			   ((parseFloat(document.getElementById('largo').value)*
				 parseFloat(document.getElementById('ancho').value)*
				 parseFloat(document.getElementById('alto').value))/ 4000) *
				 parseFloat(document.all.cantidad.value);
			   }
			}
		}	
}
function CalcularVolumen(e){
		if(e == 13){
			tecla = 13;
		}else{
			tecla = (document.all) ? e.keyCode : e.which;
		}
		if(tecla==13){
			if(document.getElementById('largo').value >=0 &&
			   document.getElementById('largo').value >=0 &&
			   document.getElementById('ancho').value >=0 &&
			   document.getElementById('alto').value>=0){	
			   if(document.all.pesounit.checked==true){
			   document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
			   }else{
			   document.getElementById('volumen').value=
			   ((parseFloat(document.getElementById('largo').value)*
				 parseFloat(document.getElementById('ancho').value)*
				 parseFloat(document.getElementById('alto').value))/ 4000) *
				 parseFloat(document.all.cantidad.value);
			   }
			
			}
		}	
}
function CalcularUnitarioFoco(){
		var u = document.all;
		if(u.peso.value!=""){
			if(u.pesounit.checked==true){
				u.pesototal.value=parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
			}else{
				u.pesototal.value= u.peso.value;
			}
		}	
	}
	function CalcularUnitario(e){
		tecla=(document.all) ? e.keyCode : e.which;
		var u = document.all;
		if(tecla==13){
			if(u.pesounit.checked==true){
				u.pesototal.value=parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
			}else{
				u.pesototal.value= u.peso.value;
			}
		}
	}
	function CalcularUnitarioCheck(){
		var u = document.all;
			if(u.pesounit.checked==true){
				if(u.peso.value!=""){
				u.pesototal.value=parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
				}else{
				u.pesototal.value="";
				}
		document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
				if(document.getElementById('volumen').value=='NaN'){
					document.getElementById('volumen').value="";			
				}
			}else{
				u.pesototal.value= u.peso.value;
				document.getElementById('volumen').value=
		   ((parseFloat(document.getElementById('largo').value)*
			 parseFloat(document.getElementById('ancho').value)*
			 parseFloat(document.getElementById('alto').value))/ 4000);
				if(document.getElementById('volumen').value=='NaN'){
					document.getElementById('volumen').value="";			
				}
			}
	}
	function limpiar(){
		document.getElementById('cantidad').value="";
		document.getElementById('descripcion').value="";
		document.getElementById('contenido').value="";
		document.getElementById('peso').value="";
		document.getElementById('largo').value="";
		document.getElementById('alto').value="";
		document.getElementById('ancho').value="";
		document.getElementById('volumen').value="";
		document.getElementById('pesototal').value="";
		document.all.pesounit.checked = false;	
		u.id.value = "";
		u.abierto.value = "";
		u.oculto.value = "";
		u.iddescripcion.value = "";
		u.fechahora.value = "";
		u.esmodificar.value = "";	
	}

function obtener(id,descripcion){
	document.getElementById('descripcion').value=descripcion;
	document.getElementById('iddescripcion').value=id;
	document.getElementById('abierto').value="";
}
var nav4 = window.Event ? true : false;
function Numeros(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
}
function trim(cadena,caja){
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
function tabular(e,obj){
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
	
	function obtenerDescripcionValida(){
		consultaTexto("descripcionValida","../evaluacion/evaluacionMercancia_con.php?accion=12&descripcion="+u.descripcion.value);	
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
var concep 	= new Array(<?php echo $cadena; ?>);
var desc 	= new Array(<?php echo $desc; ?>);
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<style>
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="3" height="3" background="../img/Ccaf1.jpg" ></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="../img/Ccaf2.jpg"></td>
    </tr>
    <tr bgcolor="dee3d5">
      <td height="26"></td>
      <td ><table width="330" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="63" class="Tablas">Cantidad:</td>
            <td colspan="4" class="Tablas"><label>
              <input name="cantidad" type="text" class="Tablas" id="cantidad" onKeyPress="return SoloND(event,this.value)

" onKeyUp="if(event.keyCode==13){document.all.descripcion.focus();}" value="<?=$cantidad ?>" size="5" maxlength="5" readonly="readonly" />
              <input name="pesounit" type="hidden" onClick="CalcularUnitarioCheck()" id="pesounit" value="1" <? if($pesounit==1){ echo 'checked';} ?>>
              </label></td>
          </tr>
          <tr>
            <td class="Tablas">Descripci&oacute;n: </td>
            <td colspan="3" class="Tablas" id="coldescripcion"><input name="descripcion" type="text" class="Tablas" id="descripcion" style="text-transform:uppercase" autocomplete="array:desc" onBlur="if(this.value!=''){setTimeout('obtenerDescripcionValida()',1000);document.getElementById('oculto').value=''}" onKeyPress="if(event.keyCode==13){document.all.iddescripcion.value=this.codigo; document.all.contenido.focus();}" onKeyDown="if(event.keyCode==9){document.all.iddescripcion.value=this.codigo;}" onKeyUp="return validaDescripcion(event,this.name)" value="<?=$descripcion ?>" size="30" maxlength="50" readonly="readonly" /></td>
            <td class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="javascript:popUp('../evaluacion/buscar.php?tipo=descripcion')" style="cursor:pointer" /></td>
          </tr>
          <tr>
            <td class="Tablas">Contenido:</td>
            <td colspan="4" class="Tablas"><input name="contenido" type="text" class="Tablas" id="contenido" style="text-transform:uppercase; font:tahoma" autocomplete="array:concep" onBlur="trim(document.getElementById('contenido').value,'contenido');" onKeyPress="return tabular(event,this)" value="<?=$contenido ?>" size="42" maxlength="50" readonly="readonly" />
            </td>
          </tr>
          <tr>
            <td class="Tablas">Peso:</td>
            <td width="300" class="Tablas"><input name="peso" type="text" class="Tablas" id="peso" onBlur="CalcularUnitarioFoco()" onKeyPress="return SoloND(event,this.value)

" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$peso ?>" size="10" maxlength="15" readonly="readonly" /> 
                         Importe <input name="largo" type="text" class="Tablas" id="largo" onkeydown="return tabular(event,this)" value="<?=$largo ?>" size="7" maxlength="10" /></td>
            <td width="3" class="Tablas">&nbsp;</td>
            <td width="49" class="Tablas">&nbsp;</td>
            <td width="35" class="Tablas">&nbsp;</td>
          </tr>
          <tr>
            <td class="Tablas">Concepto</td>
            <td colspan="2" class="Tablas"><input name="ancho" type="text" class="Tablas" id="ancho"  value="<?=$ancho ?>" size="60" maxlength="200" /></td>
          </tr>
          <tr>
            <td class="Tablas">Clase:</td>
            <td class="Tablas"><input name="alto" type="text" class="Tablas" id="alto"  value="<?=$alto ?>" size="60" maxlength="200" /></td>
          </tr>
          <tr>
            <td class="Tablas">Peso Total: </td>
            <td class="Tablas"><input name="pesototal" type="text" class="Tablas" id="pesototal" value="<?=$pesototal ?>" size="10" readonly="" style="background:#FFFF99" /></td>
            <td colspan="2" class="Tablas"></td>
            <td class="Tablas"><input name="volumen" type="hidden" class="Tablas" id="volumen" value="<?=$volumen ?>" size="9" readonly="" style="background:#FFFF99" /></td>
          </tr>
          <tr>
            <td colspan="5">
			  <input name="id" type="hidden" id="id" value="<?=$id ?>" />
              <input name="abierto" type="hidden" id="abierto" value="<?=$abierto ?>" />
              <input name="oculto" type="hidden" id="oculto" />
              <input name="iddescripcion" type="hidden" id="descripcion_hidden" value="<?=$iddescripcion ?>" />             
              <input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora ?>" />
         	  <input name="esmodificar" type="hidden" id="esmodificar" value="<?=$esmodificar ?>" />
              <table width="148" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="78"><img src="../img/Boton_Agregari.gif" id="img_agregar" alt="Guardar" width="70" height="20" style="cursor:pointer" onClick="CalcularVolumen(13); Validar();" /></td>
                    <td width="70"><img src="../img/Boton_Cerrar_.gif" alt="Cerrar" width="70" height="20" style="cursor:pointer" onClick="parent.VentanaModal.cerrar()" /></td>
                  </tr>
              </table></td>
          </tr>
        </table>
          </td>
      <td></td>
    </tr>
    <tr>
      <td width="3" height="3" background="../img/Ccaf3.jpg" ></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="../img/Ccaf4.jpg"></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
</body>
</html>
