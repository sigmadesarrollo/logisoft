<?	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

?>

<OBJECT ID="clsBalanza"

CLASSID="CLSID:9FFC4EAB-D1CB-4E7B-8582-A8DD90AD57E5">

</OBJECT>



<OBJECT ID="cPeso"

CLASSID="CLSID:195ED6FA-B663-49A4-AA32-69398F264F2B">

</OBJECT>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script type="text/javascript" src="js/ajax.js"></script> 

<SCRIPT language="JavaScript" src="moautocomplete.js"></SCRIPT>

<script src="../javascript/ajax.js"></script>

<script>

	var u = document.all;

	function ObtenerPeso(){

		var pesar = new ActiveXObject("DpsDrvBal.clsBalanza")

		pesar.LeerConfig("C:/DPSoft/Bascula/balanza.cfg");	

		if(pesar.Pesar() == 0){		

			u.peso.value = parseFloat(pesar.Peso.Valor);

			u.peso.focus();

			u.largo.focus();		

		}else{

			alerta2('Ocurrio un error al pesar','¡Atención!','peso');

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

				alerta2('Ya Se Encuentra Abierta la busqueda','¡Atención!','descripcion');

			}

		}

	}

	

	function Validar(){

		if(document.getElementById('peso').value==""){

			alerta2('Debe Capturar Peso','¡Atención!','peso');	

		}else if(document.getElementById('peso').value<0){

			alerta2('Peso Debe ser Mayor a Cero','¡Atención!','peso');	

		}else if(document.getElementById('largo').value==""){

			alerta2('Debe Capturar Largo','¡Atención!','largo');

		}else if(document.getElementById('largo').value<0){ 

			alerta2('Largo Debe ser Mayor a Cero','¡Atención!','largo');

		}else if(document.getElementById('ancho').value==""){ 

			alerta2('Debe Capturar Ancho','¡Atención!','ancho');	

		}else if(document.getElementById('ancho').value<0){ 

			alerta2('Ancho Debe ser Mayor a Cero','¡Atención!','ancho');

		}else if(document.getElementById('alto').value==""){ 

			alerta2('Debe Capturar Alto','¡Atención!','alto');	

		}else if(document.getElementById('alto').value<0){ 

			alerta2('Alto Debe ser Mayor a Cero','¡Atención!','alto'); 

		}else{ 	

			u.img_agregar.style.visibility = "hidden";

			var arr = new Array();

			consultaTexto("registroEvaluacion","ModificarDetalle_con.php?accion=2&idmercancia=<?=$_GET[iddetalle]?>&arre="+arr+

			"&pesou="+u.peso.value+"&largo="+u.largo.value+

			"&ancho="+u.ancho.value+"&alto="+u.alto.value+

			"&peso="+u.pesototal.value+"&volumen="+u.volumen.value+"&guardar="+Math.random());	

		}

	}

	

	function registroEvaluacion(datos){

		if(datos.indexOf("guardado")>-1){

			parent.cambiarValor();

			info('Los datos han sido modificados','');

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

				   document.getElementById('volumen').value = ((parseFloat(document.getElementById('largo').value) * 

					parseFloat(document.getElementById('ancho').value) * 

					parseFloat(document.getElementById('alto').value))/ 4000) * 

					parseFloat(document.getElementById('cantidad').value);

			   }else{

			   document.getElementById('volumen').value=

			   ((parseFloat(document.getElementById('largo').value)*

				 parseFloat(document.getElementById('ancho').value)*

				 parseFloat(document.getElementById('alto').value))/ 4000);

			   }

			}

			esNan('volumen');

		}

	}



function CalcularVolumen(e){

	tecla=(document.all) ? e.keyCode : e.which;

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

			 parseFloat(document.getElementById('alto').value))/ 4000);			

		   }		

		}

		esNan('volumen');

	}

}



	function CalcularUnitarioFoco(){

		var u = document.all;

		if(u.peso.value!=""){

			if(u.pesounit.checked==true){

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

		}else{

			u.pesototal.value= u.peso.value;

			document.getElementById('volumen').value=

		   ((parseFloat(document.getElementById('largo').value)*

			 parseFloat(document.getElementById('ancho').value)*

			 parseFloat(document.getElementById('alto').value))/ 4000);			 

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

		document.getElementById('iddescripcion').value="";

		document.getElementById('descripcion').value="";

		document.getElementById('contenido').value="";

		document.getElementById('peso').value="";

		document.getElementById('largo').value="";

		document.getElementById('alto').value="";

		document.getElementById('ancho').value="";

		document.getElementById('volumen').value="";

		document.getElementById('pesototal').value="";

		document.all.pesounit.checked=false;

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



function foco(nombrecaja){

	if(nombrecaja=="descripcion"){

		document.getElementById('oculto').value="1";	

	}	

}



window.onload = function (){

	document.all.peso.focus();

	consultaTexto("resMostrarDatos","ModificarDetalle_con.php?accion=1&folioguia=<?=$_GET[folioguia]?>&iddetalle=<?=$_GET[iddetalle]?>");

}



function resMostrarDatos(datos){

	var objeto = eval(convertirValoresJson(datos));

	

	document.getElementById("cantidad").value			= objeto.cantidad;

	document.getElementById("pesounit").checked			= (objeto.pesounit==1)?true:false;

	document.getElementById("descripcion").value		= objeto.descripcion;

	document.getElementById("contenido").value			= objeto.contenido;

	document.getElementById("peso").value				= objeto.pesou;

	document.getElementById("largo").value				= objeto.largo;

	document.getElementById("alto").value				= objeto.alto;

	document.getElementById("ancho").value				= objeto.ancho;

	document.getElementById("pesototal").value			= objeto.peso;

	document.getElementById("volumen").value			= objeto.volumen;

}



var concep = new Array(<?php echo $cadena; ?>);

</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Datos Evaluación</title>

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

.style5 {

	color: #FFFFFF;

	font-size:8px;

	font-weight: bold;

}

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

.Estilo1 {

	color: #FFFFFF;

	font-weight: bold;

	font-size: 13px;

	font-family: tahoma;

}

.Txtamarillo{

font:tahoma; font-size:9px; background-color:#FFFF99;text-transform:uppercase;

}

.Txt{

font:tahoma; font-size:9px;text-transform:uppercase;

}



.Button {

margin: 0;

padding: 0;

border: 0;

background-color: transparent;

width:70px;

height:20px;

}

.Estilo2 {

	font-size: 8px;

	font-weight: bold;

}

.Estilo3 {font-size: 9px}

.style31 {font-size: 9px;

	color: #464442;

}

.style31 {font-size: 9px;

	color: #464442;

}

</style>



<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">

</head>



<BODY>

<br>

<form id="form1" name="form1" method="post" action="">  

  <table width="350" border="0" align="center" cellpadding="0" cellspacing="0">

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

            <input name="cantidad" readonly="" style="background:#FFFF99" type="text" class="Tablas" id="cantidad" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$cantidad ?>" size="5" maxlength="5" />

            <input name="pesounit" disabled type="checkbox" onClick="CalcularUnitarioCheck()" id="pesounit" onKeyPress="return tabular(event,this)" value="1" <? if($pesounit==1){ echo 'checked';} ?>>

          Peso y Medidas Unitarias </label></td>

        </tr>

        <tr>

          <td class="Tablas">Descripción:</td>

          <td colspan="4" class="Tablas"><input name="descripcion" type="text" class="Tablas" id="descripcion" readonly="" style="background:#FFFF99; font-size:9px; text-transform:uppercase" onFocus="foco(this.name)" onKeyPress="return tabular(event,this)" value="<?=$descripcion ?>" size="30" maxlength="50" />

            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="javascript:popUp('buscar.php?tipo=descripcion')" style="cursor:pointer"></td>

        </tr>

        <tr>

          <td class="Tablas">Contenido:</td>

          <td colspan="4" class="Tablas"><input name="contenido" type="text" class="Tablas" id="contenido" readonly="" style="background:#FFFF99; text-transform:uppercase; font:tahoma"  onKeyPress="return tabular(event,this)" value="<?=$contenido ?>" size="42" maxlength="50" autocomplete="array:concep" />           </td>

          </tr>

        

        <tr>

          <td class="Tablas">Peso:</td>

          <td width="81" class="Tablas"><input name="peso" type="text" class="Tablas" id="peso" onBlur="CalcularUnitarioFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$peso ?>" size="10" maxlength="15" /></td>

          <td width="51" class="Tablas"><div class="ebtn_peso" onClick="ObtenerPeso();" style="cursor:pointer"></div></td>

          <td width="44" class="Tablas">Largo:</td>

          <td width="83" class="Tablas"><input name="largo" type="text" class="Tablas" id="largo" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$largo ?>" size="7" maxlength="10" />

          cm</td>

        </tr>

        

        <tr>

          <td class="Tablas">Ancho:&nbsp;</td>

          <td colspan="2" class="Tablas"><input name="ancho" type="text" class="Tablas" id="ancho" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$ancho ?>" size="10" maxlength="10" />

          cm</td>

          <td class="Tablas">Alto:</td>

          <td class="Tablas"><input name="alto" type="text" class="Tablas" id="alto" onBlur="CalcularVolumenFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularVolumen(event);" value="<?=$alto ?>" size="7" maxlength="10" />

          cm</td>

        </tr>

        <tr>

          <td class="Tablas">Peso Total: </td>

          <td class="Tablas"><input name="pesototal" type="text" class="Tablas" id="pesototal" value="<?=$pesototal ?>" size="10" readonly="" style="background:#FFFF99" /></td>

          <td colspan="2" class="Tablas">Peso Volumétrico:</td>

          <td class="Tablas"><input name="volumen" type="text" class="Tablas" id="volumen" value="<?=$volumen ?>" size="9" readonly="" style="background:#FFFF99" /></td>

        </tr>

        

        

        <tr>

          <td colspan="5"><table width="100" border="0" align="right" cellpadding="0" cellspacing="0">

              <tr>

                <td><img src="../img/Boton_Agregari.gif" alt="Guardar" name="img_agregar" width="70" height="20" id="img_agregar" style="cursor:pointer" onClick="Validar()" /></td>

                <td><img src="../img/Boton_Cerrar_.gif" alt="Cerrar" width="70" height="20" style="cursor:pointer" onClick="parent.VentanaModal.cerrar()" /></td>

                <? if($_GET[eliminar]==1){ ?>

                <? } ?>

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

  </table>

  <p>&nbsp;</p>

</form>

</body>

</html>

