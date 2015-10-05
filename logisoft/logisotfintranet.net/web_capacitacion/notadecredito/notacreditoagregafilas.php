<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link			= Conectarse('webpmm');	
	$cantidad		= $_GET['cantidad'];
	$unidad			= $_GET['unidad'];
	$descripcion	= $_GET['descripcion'];
	$precio			= $_GET['precio'];
	$importe		= $_GET['importe'];
		
	$result=mysql_query("SELECT descripcion FROM contenidos",$link);
	if(mysql_num_rows($result)>0){
		while($con=mysql_fetch_array($result)){
			$cadena= "'".$con[0]."'".','.$cadena; 	
		}	
		$cadena=substr($cadena, 0, -1);
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="js/ajax.js"></script> 
<SCRIPT language="JavaScript" src="moautocomplete.js"></SCRIPT>
<script src="shortcut.js" type="text/javascript"></script>
<script src="../javascript/ajax.js"></script>
<script>
	var u = document.all;

	function Validar(){
	alert(u.descripcion.value);
		if(u.cantidad.value==""){
			alerta2('Debe Capturar Cantidad','¡Atención!','cantidad'); 
		}else if(u.unidad.value==""){ 
			alerta2('Debe Capturar unidad','¡Atención!','unidad');	
		}else if(u.descripcion.value==""){ 
			alerta2('Debe Capturar Descripción','¡Atención!','descripcion');
		}else if(u.precio.value==""){ 
			alerta2('Debe Capturar precio','¡Atención!','precio');
		}else if(u.importe.value==""){
			alerta2('Debe Capturar importe','¡Atención!','importe');	
		}else{ 	
			//u.img_agregar.style.visibility = "hidden";
			var arr = new Array();
			arr[0] = u.cantidad.value;
			arr[1] = u.unidad.value;
			arr[2] = u.descripcion.value;
			arr[3] = u.precio.value;
			arr[4] = u.importe.value.replace(/,/g,'').replace("$ ","");
		
			consultaTexto("registroEvaluacion","notacredito_con.php?accion=1&arre="+arr);	
		}
	}
	
	function registroEvaluacion(datos){
		if(datos.indexOf("ok")>-1){
			u.img_agregar.style.visibility = "visible";
			var fe = datos.split(",");
			var objeto = new Object();
			objeto.cantidad		=	u.cantidad.value; 
			objeto.unidad		=	u.unidad.value;
			objeto.descripcion	=	u.descripcion.value;
			objeto.precio		=	u.precio.value;
			objeto.importe		=	u.importe.value.replace(/,/g,'').replace("$ ","");
			
			limpiar();
			parent.<?=$_GET[funcion]?>(objeto<?=($_GET[eliminar]==1)?",1":"";?>);
			info('Los datos han sido agregados satisfactoriamente','');
		}else{
			u.img_agregar.style.visibility = "visible";
			alerta3("Hubo un Error al agregar "+datos,"¡Atención!");			
		}
	}

	function limpiar(){
		document.getElementById('cantidad').value="";
		document.getElementById('unidad').value="";
		document.getElementById('descripcion').value="";
		document.getElementById('precio').value="";
		document.getElementById('importe').value="";
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
			/*ACA ESTA EL CAMBIO*/
			if (frm.elements[i+1].disabled ==true )    
				tabular(e,frm.elements[i+1]);
			else if (frm.elements[i+1].readOnly ==true )    
				tabular(e,frm.elements[i+1]);				
			else frm.elements[i+1].focus();
			return false;
	} 

	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}

	function CalcularImporte(){
	var u = document.all;
	if (u.cantidad.value!="" && u.precio.value!="") {
		u.importe.value=convertirMoneda(parseFloat(u.precio.value) * parseFloat(u.cantidad.value));
	}
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
.style5 {
	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 13px;
	font-family: tahoma;
}
-->

</style>

<style>
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

<BODY onLoad="document.all.cantidad.focus();" >
<br>
<form id="form1" name="form1" method="post" action="">  
  <table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="3" height="3" background="img/Ccaf1.jpg"></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="img/Ccaf2.jpg"></td>
    </tr>
    <tr bgcolor="dee3d5">
      <td height="26"></td>
      <td ><table width="330" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="71" class="Tablas">Cantidad:</td>
          <td class="Tablas"><label>
          <input name="cantidad" type="text" class="Tablas" id="cantidad" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$cantidad ?>" size="10" maxlength="5" />
          </label></td>
        </tr>
        <tr>
          <td class="Tablas">Unidad:</td>
          <td class="Tablas"><input name="unidad" type="text" class="Tablas" id="unidad"  onKeyDown="return tabular(event,this)" value="<?=$unidad ?>" size="50" maxlength="50" /></td>
        </tr>
        <tr>
          <td class="Tablas">Descripción:</td>
          <td class="Tablas"><input name="descripcion" type="text" class="Tablas" id="descripcion" style="font-size:9px; text-transform:uppercase" onKeyDown="return tabular(event,this)" value="<?=$descripcion ?>" size="50" maxlength="50" /></td>
          </tr>
        
        <tr>
          <td class="Tablas">Precio:</td>
          <td class="Tablas"><input name="precio" type="text" class="Tablas" id="precio"  onBlur="CalcularImporte()" onKeyPress="return Numeros(event)" onKeyDown="CalcularImporte(event);" value="<?=$precio ?>" size="20" maxlength="15" /></td>
          </tr>
        <tr>
          <td class="Tablas">Importe:</td>
          <td class="Tablas"><input name="importe" type="text" class="Tablas" id="importe" value="<?=$importe ?>" size="20" readonly="" style="background:#FFFF99" /></td>
          </tr>
        
        
        <tr>
          <td colspan="2"><input name="usuario" type="text" id="usuario" value="<?=$usuario ?>">
            <input name="id" type="text" id="id" value="<?=$id ?>">
            <input name="oculto" type="text" id="oculto">
            <table width="100" border="0" align="right" cellpadding="0" cellspacing="0">
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
      <td width="3" height="3"  background="img/Ccaf3.jpg"></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="img/Ccaf4.jpg"></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
