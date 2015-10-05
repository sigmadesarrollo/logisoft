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
	$tipo			= $_GET['tipo'];
	$result=mysql_query("SELECT descripcion FROM contenidos",$link);
	if(mysql_num_rows($result)>0){
		while($con=mysql_fetch_array($result)){
			$cadena= "'".$con[0]."'".','.$cadena; 	
		}	
		$cadena=substr($cadena, 0, -1);
	}
	
	$s = mysql_query("SELECT CONCAT_WS(':',descripcion,id) AS descripcion FROM catalogodescripcion",$link);
	if(mysql_num_rows($s)>0){
		while($f=mysql_fetch_array($s)){
			$desc= "'".cambio_texto($f[0])."'".','.$desc; 	
		}	
		$desc=substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/shortcut.js" type="text/javascript"></script>
<script src="../javascript/moautocomplete.js"></script>
<!--<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">!-->
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funcionesDrag.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ajax.js"></script>
<script>
	var u = document.all;
	var mens 		= new ClaseMensajes();
	mens.iniciar('../javascript',true);
	var fecha = "";
	
	window.onload = function(){
		document.all.cantidad.focus();
	}
	
	function Validar(){
		if(document.getElementById('cantidad').value==""){
			mens.show('A','Debe Capturar Cantidad','¡Atención!','cantidad'); 
			return false;
		}else if(document.getElementById('cantidad').value<0){ 
			mens.show('A','Cantidad Debe ser Mayor a Cero','¡Atención!','cantidad');
			return false;
		}else if(document.getElementById('iddescripcion').value==undefined){ 
			mens.show('A','Debe Capturar Descripción','¡Atención!','descripcion');
			return false;
		}else if(document.getElementById('contenido').value==""){ 
			mens.show('A','Debe Capturar Contenido','¡Atención!','contenido');
			return false;
		}else if(document.getElementById('peso').value==""){
			mens.show('A','Debe Capturar Peso','¡Atención!','peso');
			return false;
		}else if(document.getElementById('peso').value<0){
			mens.show('A','Peso Debe ser Mayor a Cero','¡Atención!','peso');	
			return false;
		}else if(document.getElementById('largo').value==""){
			mens.show('A','Debe Capturar Largo','¡Atención!','largo');
			return false;
		}else if(document.getElementById('largo').value<0){ 
			mens.show('A','Largo Debe ser Mayor a Cero','¡Atención!','largo');
			return false;
		}else if(document.getElementById('ancho').value==""){ 
			mens.show('A','Debe Capturar Ancho','¡Atención!','ancho');	
			return false;
		}else if(document.getElementById('ancho').value<0){ 
			mens.show('A','Ancho Debe ser Mayor a Cero','¡Atención!','ancho');
			return false;
		}else if(document.getElementById('alto').value==""){ 
			mens.show('A','Debe Capturar Alto','¡Atención!','alto');	
			return false;
		}else if(document.getElementById('alto').value<0){ 
			mens.show('A','Alto Debe ser Mayor a Cero','¡Atención!','alto'); 
			return false;
		}else{ 	
			document.all.d_guardar.style.visibility = "hidden";
			var tipo = ((document.all.tipo.value=="")? "grabar" : document.all.tipo.value);	
			fecha = ((document.all.tipo.value=="")? fechahora(fecha) : '<?=$_GET[fecha] ?>');		
			var arr = new Array();
			arr[0]	= "<?=$_GET[sucursal]; ?>";
			arr[1]	= document.getElementById('cantidad').value;
			arr[2]	= document.getElementById('iddescripcion').value;
			arr[3]	= document.getElementById('peso').value;
			arr[4]	= document.getElementById('largo').value;
			arr[5]	= document.getElementById('alto').value;
			arr[6]	= document.getElementById('ancho').value;
			arr[7]	= document.getElementById('volumen').value;
			arr[8]	= document.getElementById('pesototal').value;
			arr[9]	= ((document.all.pesounit.checked == true) ? "1" : "0");			
			consultaTexto("registrarMercancia","devolucionGuia_con.php?accion=4&arre="+arr
			+"&tipo="+tipo+"&fecha="+fecha+"&contenido="+document.getElementById('contenido').value+"&descripcion="+document.getElementById('descripcion').value);
		}
	}
	function registrarMercancia(datos){
		if(datos.indexOf("ok")>-1){
			document.all.d_guardar.style.visibility = "visible";
			var row = datos.split(",");
				var objeto = new Object();				
				objeto.cantidad		= document.getElementById('cantidad').value; 
				objeto.iddescripcion= document.getElementById('iddescripcion').value;
				objeto.descripcion	= document.getElementById('descripcion').value;
				objeto.contenido	= document.getElementById('contenido').value;
				objeto.peso			= document.getElementById('peso').value;
				objeto.largo		= document.getElementById('largo').value;	
				objeto.alto			= document.getElementById('alto').value;
				objeto.ancho		= document.getElementById('ancho').value; 	
				objeto.volumen		= document.getElementById('volumen').value;
				objeto.pesounit		= ((document.all.pesounit.checked == true)?1:0);
				objeto.pesototal	= document.getElementById('pesototal').value;
				objeto.importe		= 0;
				objeto.fecha		= ((document.all.tipo.value=="")? fecha : '<?=$_GET[fecha] ?>');
				objeto.peso2		= document.getElementById('peso').value;
				limpiar();
				parent.<?=$_GET[funcion]?>(objeto);
			if(row[1]=="grabar"){
				mens.show('I','Los datos han sido agregados satisfactoriamente','');
			}else if(row[1]=="modificar"){
				mens.show('I','Los datos han sido agregados satisfactoriamente','');
			}
		}else{
			mens.show("A","Hubo un error al insertar "+datos,"¡Atención!","");
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
		if(document.all.peso.value!=""){
			if(document.all.pesounit.checked==true){
				document.all.pesototal.value=parseFloat(document.all.peso.value) * parseFloat(document.all.cantidad.value);
			}else{
				document.all.pesototal.value= document.all.peso.value;
			}
		}	
	}
	function CalcularUnitario(e){
		tecla=(document.all) ? e.keyCode : e.which;
		var u = document.all;
		if(tecla==13){
			if(document.all.pesounit.checked==true){
				document.all.pesototal.value=parseFloat(document.all.peso.value) * parseFloat(document.all.cantidad.value);
			}else{
				document.all.pesototal.value= document.all.peso.value;
			}
		}
	}
	function CalcularUnitarioCheck(){
		var u = document.all;
			if(document.all.pesounit.checked==true){
				if(document.all.peso.value!=""){
				document.all.pesototal.value=parseFloat(document.all.peso.value) * parseFloat(document.all.cantidad.value);
				}else{
				document.all.pesototal.value="";
				}
		document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
				if(document.getElementById('volumen').value=='NaN'){
					document.getElementById('volumen').value="";			
				}
			}else{
				document.all.pesototal.value= document.all.peso.value;
				document.getElementById('volumen').value=
		   ((parseFloat(document.getElementById('largo').value)*
			 parseFloat(document.getElementById('ancho').value)*
			 parseFloat(document.getElementById('alto').value))/ 4000);
				if(document.getElementById('volumen').value=='NaN'){
					document.getElementById('volumen').value="";			
				}
			}
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
		document.all.pesounit.checked = false;
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
	var concep = new Array(<? echo $cadena; ?>);	
	var desc   = new Array(<? echo $desc;   ?>);
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
            <td width="71" class="Tablas">Cantidad:</td>
            <td colspan="4" class="Tablas"><label>
              <input name="cantidad" type="text" class="Tablas" id="cantidad" onKeyPress="return Numeros(event)" onKeyUp="if(event.keyCode==13){document.all.descripcion.focus();}" value="<?=$cantidad ?>" size="5" maxlength="5" />
              <input name="pesounit" type="checkbox" onClick="CalcularUnitarioCheck()" id="pesounit" value="1" <? if($pesounit==1){ echo 'checked';} ?>>
              Peso y Medidas Unitarias </label></td>
          </tr>
          <tr>
            <td class="Tablas">Descripci&oacute;n:</td>
            <td colspan="3" class="Tablas" id="coldescripcion"><input name="descripcion" type="text" class="Tablas" id="descripcion" style="text-transform:uppercase" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.iddescripcion.value=this.codigo; document.all.contenido.focus();}" onKeyDown="if(event.keyCode==9){document.all.iddescripcion.value=this.codigo;}" onKeyUp="return validaDescripcion(event,this.name)" value="<?=$descripcion ?>" size="30" maxlength="50" onBlur="if(this.value!=''){setTimeout('obtenerDescripcionValida()',1000);document.getElementById('oculto').value=''}" /></td>
            <td class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="parent.mens.popup('../evaluacion/buscar.php?tipo=descripcion',600,450,'v1','titulo');"
			
			 style="cursor:pointer" /></td>
          </tr>
          <tr>
            <td class="Tablas">Contenido:</td>
            <td colspan="4" class="Tablas"><input name="contenido" type="text" class="Tablas" id="contenido" style="text-transform:uppercase; font:tahoma" onBlur="trim(document.getElementById('contenido').value,'contenido');" onKeyPress="return tabular(event,this)" value="<?=$contenido ?>" size="42" maxlength="50" autocomplete="array:concep" />
            </td>
          </tr>
          <tr>
            <td class="Tablas">Peso:</td>
            <td width="81" class="Tablas"><input name="peso" type="text" class="Tablas" id="peso" onBlur="CalcularUnitarioFoco()" onKeyPress="return Numeros(event)" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$peso ?>" size="10" maxlength="15" /></td>
            <td width="51" class="Tablas">&nbsp;</td>
            <td width="44" class="Tablas">Largo:</td>
            <td width="83" class="Tablas"><input name="largo" type="text" class="Tablas" id="largo" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$largo ?>" size="7" maxlength="10" />
              cm</td>
          </tr>
          <tr>
            <td class="Tablas">Ancho:&nbsp;</td>
            <td colspan="2" class="Tablas"><input name="ancho" type="text" class="Tablas" id="ancho" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$ancho ?>" size="10" maxlength="10" />
              cm</td>
            <td class="Tablas">Alto:</td>
            <td class="Tablas"><input name="alto" type="text" class="Tablas" id="alto" onBlur="CalcularVolumenFoco()" onKeyPress="if(event.keyCode==13){Validar();}else{return Numeros(event);}" onKeyDown="CalcularVolumen(event);" value="<?=$alto ?>" size="7" maxlength="10" />
              cm</td>
          </tr>
          <tr>
            <td class="Tablas">Peso Total: </td>
            <td class="Tablas"><input name="pesototal" type="text" class="Tablas" id="pesototal" value="<?=$pesototal ?>" size="10" readonly="" style="background:#FFFF99" /></td>
            <td colspan="2" class="Tablas">Peso Volum&eacute;trico:</td>
            <td class="Tablas"><input name="volumen" type="text" class="Tablas" id="volumen" value="<?=$volumen ?>" size="9" readonly="" style="background:#FFFF99"  /></td>
          </tr>
          <tr>
            <td colspan="5"><input name="id" type="hidden" id="id" value="<?=$id ?>" />
              <input name="abierto" type="hidden" id="abierto" value="<?=$abierto ?>" />
              <input name="oculto" type="hidden" id="oculto" />
              <span class="Tablas">
              <input name="iddescripcion" type="hidden" id="iddescripcion" value="<?=$iddescripcion ?>" />
              <input name="tipo" type="hidden" id="tipo" value="<?=$tipo ?>">
              </span>
              
              <table width="147" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><img src="../img/Boton_Agregari.gif" id="d_guardar" alt="Guardar" width="70" height="20" style="cursor:pointer" onClick="CalcularVolumen(13); Validar();" /></td>
                    <td><img src="../img/Boton_Cerrar_.gif" alt="Cerrar" width="70" height="20" style="cursor:pointer" onClick="parent.VentanaModal.cerrar()" /></td>
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
