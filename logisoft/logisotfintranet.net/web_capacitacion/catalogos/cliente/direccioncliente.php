<?	session_start();
	require_once('../../Conectar.php');
	$link=Conectarse('webpmm');

	$calle		=$_GET['calle'];
	$numero		=$_GET['numero'];
	$colonia	=$_GET['colonia'];
	$cp			=$_GET['cp'];
	$poblacion	=$_GET['poblacion'];
	$telefono	=$_GET['telefono'];
	$facturacion=$_GET['chfacturacion'];
	$modificarfila=$_GET['modificarfila'];
	$entrecalles=$_GET['entrecalles'];
	$municipio	=$_GET['municipio'];
	$estado		=$_GET['estado'];
	$pais		=$_GET['pais'];
	$fax		= $_GET['fax'];

	if($accion=="limpiar"){
		$calle=""; $colonia="";
		$entrecalles=""; $cp="";
		$colonia=""; $poblacion="";
		$municipio=""; $estado="";
		$pais=""; $telefono="";
		$fax=""; $facturacion="";
		$numero="";	$modificar="";
		$accion=""; $id="";
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/funciones.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../javascript/ajaxlist/ajax.js"></script>
<script>
var u = document.all;
var Input = '<input name="colonia" type="text" class="Tablas" id="colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';

var combo1 = "<select name='colonia' id='colonia' class='Tablas' style='width:183px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";

	function popUp(URL) {
		if(URL!=""){
			if(document.getElementById('abierto').value==""){
			document.getElementById('abierto').value="abierto";
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=400,left = 312,top = 184');");
			}else{
				alerta2('Ya se encuentra abierta la ventana de busqueda por colonia','¡Atención!','cp');
			}
		}
	}

	function ObtenerColoniaClic(codigopostal,colonia,poblacion,municipio,estado,pais){
		document.all.celcolonia.innerHTML = Input;
		document.getElementById('cp').value = codigopostal;
		document.getElementById('colonia').value = colonia;
		document.getElementById('poblacion').value = poblacion;
		document.getElementById('municipio').value = municipio;
		document.getElementById('estado').value = estado;
		document.getElementById('pais').value = pais;
	}

function Arreglo(){
	if(u.chfacturacion.checked){
		 u.chfacturacion.value="SI";
	}else{
		 u.chfacturacion.value="NO";
	}
	if(document.getElementById('calle').value==""){
		alerta2('Debe capturar Calle', '¡Atención!','calle');
		return false;
	}else if(document.getElementById('numero').value==""){
		alerta2('Debe capturar Numero', '¡Atención!','numero');	
		return false;
	}else if(document.getElementById('cp').value==""){
		alerta2('Debe capturar Código Postal', '¡Atención!','cp');
		return false;
	}else if(document.getElementById('colonia').value==""){
		alerta2('Debe capturar Colonia', '¡Atención!','colonia');	
		return false;
	}else if(document.getElementById('telefono').value==""){
		alerta2('Debe capturar Teléfono', '¡Atención!','telefono');		
		return false;
	}else{
		var miArray = new Array(11);
		var col = "";
		var row = "";
		//if(document.getElementById('colonia').value.indexOf("-")>-1){
			//row = document.getElementById('colonia').value.split("-");
		//}else{
			col = document.getElementById('colonia').value;
		//}
		
		miArray[0] = document.getElementById('calle').value;
		miArray[1] = document.getElementById('numero').value;
		miArray[2] = document.getElementById('entrecalles').value;
		miArray[3] = document.getElementById('cp').value; 
		miArray[4] = ((col!="") ? col : row[0]);
		miArray[5] = document.getElementById('poblacion').value;
		miArray[6] = document.getElementById('municipio').value;
		miArray[7] = document.getElementById('estado').value;
		miArray[8] = document.getElementById('pais').value;
		miArray[9] = document.getElementById('telefono').value;
		miArray[10] = document.getElementById('fax').value;
		miArray[11] = document.form1.chfacturacion.value;
		miArray[12] = '<?=$_GET[iddireccion]?>';
		miArray[13] = '<?=$_GET[id]?>';
		if(window.parent.ValAddFact(miArray)){
			window.parent.agregarVar(miArray);
			window.parent.VentanaModal.cerrar();
		}else{
			alerta3('Ya existe una dirección con facturación','¡Atención!');
			return false;
		}
	}
}


	function limpiar(){
		u.calle.value		=""; 	u.numero.value		="";
		u.entrecalles.value	=""; 	u.cp.value			=""; 
		u.colonia.value		=""; 	u.poblacion.value	="";
		u.municipio.value	=""; 	u.estado.value		="";
		u.pais.value		=""; 	u.telefono.value	=""; 
		u.fax.value			=""; 	u.accion.value		="limpiar";
		u.chfacturacion.checked=false;
	}


	function CodigoPostal(e,cp){
		tecla=(document.all) ? e.keyCode : e.which;
		if((tecla==13 || tecla==9) && cp!=""){
			consulta("mostrarPostal","consultas.php?accion=1&cp="+cp+"&sid="+Math.random());
		}
	}

	function mostrarPostal(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		document.getElementById('colonia').value=""; document.getElementById('poblacion').value="";
		document.getElementById('municipio').value=""; document.getElementById('estado').value="";
		document.getElementById('pais').value="";
		
	if(con>0){
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
			setTimeout("document.getElementById('telefono').focus()",500);
		}else{		
			document.all.celcolonia.innerHTML = Input;
			u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;
			u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
			u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
			u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;
			u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;
			setTimeout("document.getElementById('telefono').focus()",500);
		}
		}else{			
			alerta2("El Código Postal no existe",'¡Atención!','cp');
			document.all.celcolonia.innerHTML = Input;
			u.cp.focus();
		}
}

	function validaCP(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
		if((tecla==8 || tecla==46)&& document.getElementById(obj).value==""){
		document.getElementById('colonia').value=""; document.getElementById('poblacion').value="";
		document.getElementById('municipio').value=""; document.getElementById('estado').value="";
		document.getElementById('pais').value="";
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

	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57));
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
            /*ACA ESTA EL CAMBIO*/
            if (frm.elements[i+1].disabled == true)
                tabular(e,frm.elements[i+1]);
            else if (frm.elements[i+1].readOnly == true)  
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus();
            return false;
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
</script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Direcci&oacute;n Cliente</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">
<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:180px;	/* Width of box */
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

</head>
<body onLoad="document.getElementById('calle').focus()">
<form id="form1" name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="490" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="3" height="3" background="../../img/Ccaf1.jpg"></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="../../img/Ccaf2.jpg"></td>
    </tr>
    <tr bgcolor="dee3d5">
      <td height="26"></td>
      <td ><table width="450" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td width="139">&nbsp;</td>
          <td width="4">&nbsp;</td>
          <td><span class="Tablas"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="chfacturacion" type="checkbox" id="chfacturacion" style="width:13px" value="SI" <? 


			if($_GET[esmodificar] != ""){
				if($facturacion=="SI"){
					echo 'checked';
				}
			}else{
				echo 'checked';
			}


			 ?> />
            Facturaci&oacute;n</span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="77" class="Tablas">Calle:</td>
          <td colspan="4"><span class="Tablas">
            <input name="calle" type="text" id="calle" class="Tablas" size="38" onBlur="trim(document.getElementById('calle').value,'calle');" value="<?=$calle; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/>
            Numero:
            <input name="numero" type="text" class="Tablas" id="numero" size="9" onBlur="trim(document.getElementById('numero').value,'numero');" value="<?=$numero; ?>" onKeyDown="return tabular(event,this)" style="font:tahoma;font-size:9px; text-transform:uppercase"/>
          </span></td>
        </tr>
        <tr>
          <td class="Tablas">Cruce Calles:</td>
          <td colspan="4"><input name="entrecalles" class="Tablas" type="text" id="entrecalles" size="61" onBlur="trim(document.getElementById('entrecalles').value,'entrecalles');" value="<?= $entrecalles; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/></td>
        </tr>
        <tr>
          <td class="Tablas">C.P.:</td>
          <td colspan="2"><span class="Tablas">
            <input name="cp" type="text" id="cp" class="Tablas" onBlur="trim(document.getElementById('cp').value,'cp'); " onKeyPress="return solonumeros(event)" onKeyDown="CodigoPostal(event,this.value); return tabular(event,this);" onKeyUp="return validaCP(event,this.name)"  value="<?= $cp; ?>" size="10" maxlength="5" style="font:tahoma;font-size:9px; text-transform:uppercase" />
          </span><span class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Colonia:</span></td>
          <td width="186" id="celcolonia"><input name="colonia" type="text" value="<?=$colonia ?>" class="Tablas" id="colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'../../buscadores_generales/ajax-list-colonias.php'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=''){setTimeout('obtenerColoniaValida()',1000);}" /></td>
          <td width="42"><div class="ebtn_buscar" onClick="javascript:popUp('buscarcolonia2.php')"></div></td>
        </tr>
        <tr>
          <td class="Tablas">Poblaci&oacute;n:</td>
          <td><input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:120px; background:#FFFF99;  text-transform:uppercase" readonly=""  value="<?= $poblacion; ?>" /></td>
          <td colspan="3"><span class="Tablas">Mun./Del.:</span><span class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="municipio" type="text" class="Tablas" id="municipio" size="20"  style="width:120px;background:#FFFF99; text-transform:uppercase" readonly="" value="<?=$municipio; ?>" />
          </span></td>
        </tr>
        <tr>
          <td class="Tablas">Estado:</td>
          <td><input name="estado" type="text" class="Tablas" id="estado" size="20" value="<?=$estado; ?>" style="width:120px;background:#FFFF99; text-transform:uppercase" readonly="" /></td>
          <td colspan="3"><span class="Tablas">Pa&iacute;s:</span><span class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="pais" type="text" id="pais" class="Tablas" size="20" value="<?=$pais; ?>" style="width:120px; text-transform:uppercase; background-color:#FFFF99" readonly=""/>
          </span></td>
        </tr>
        <tr>
          <td class="Tablas">Tel&eacute;fono:</td>
          <td><input name="telefono" type="text" class="Tablas" id="telefono" size="20" onBlur="trim(document.getElementById('telefono').value,'telefono');" value="<?= $telefono; ?>" style="width:120px;" onKeyPress="return tabular(event,this)" /></td>
          <td colspan="3"><span class="Tablas">Fax:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="Tablas">
            <input name="fax" type="text" id="fax" class="Tablas" size="20" onBlur="trim(document.getElementById('fax').value,'fax');" value="<?= $fax; ?>" style="width:120px;" onKeyPress="return tabular(event,this)" />
          </span></td>
        </tr>
        <tr>
          <td colspan="5"><span class="Tablas">
            <input name="abierto" type="hidden" id="abierto" value="<?=$abierto ?>">            
            <input type="hidden" id="colonia_hidden" name="coloniaid" />
          </span><span class="Tablas">
          <input name="id" type="hidden" id="id" value="<?=$_GET[id] ?>">
          </span>
              <table width="15" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td><img src="../../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" align="absbottom" onClick="Arreglo();" style="cursor:pointer" /></td>
                </tr>
            </table></td>
			</tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
      </table></td>
      <td></td>
    </tr>
    <tr>
      <td width="3" height="3"  background="../../img/Ccaf3.jpg"></td>
      <td bgcolor="dee3d5"></td>
      <td width="3"  background="../../img/Ccaf4.jpg"></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
</body>
</html>