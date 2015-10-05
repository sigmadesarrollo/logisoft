<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/

	require_once('../../Conectar.php');	
	$link=Conectarse('webpmm');
	
	$accion=$_POST['accion']; $cp=$_POST['cp'];
	 $usuario=$_SESSION[NOMBREUSUARIO];
	
	if($accion=="grabar"){		
		$sql = mysql_query("INSERT INTO catalogocodigopostal
		(id, codigopostal, usuario, fecha) VALUES
		(null,'$cp','$usuario', current_timestamp())",$link);
		
		$mensaje = 'Los datos han sido guardados correctamente';
		
	}else if($accion=="limpiar"){
		$mensaje=""; $cp="";
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script type="text/javascript" src="../../javascript/funciones_tablas.js"></script>
<script type="text/javascript" src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script language="JavaScript" type="text/javascript">
	var tabla_valt1 = "";
	var valt1 = agregar_una_tabla("codigopostal", "te_", 11, "Balance2└Balance","");
	ordenamiento_tabla(valt1, "center,left,left,center,center,center");
	
	var tabla1 	= new ClaseTabla();

	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"COLONIA", medida:170, alineacion:"left", datos:"colonia"},
			{nombre:"POBLACION", medida:90, alineacion:"left", datos:"poblacion"},
			{nombre:"MUN/DEL", medida:90, alineacion:"left", datos:"municipio"},
			{nombre:"ESTADO", medida:90, alineacion:"left", datos:"estado"},
			{nombre:"PAIS", medida:80, alineacion:"center", datos:"pais"}			
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});

	window.onload = function(){
		tabla1.create();
	}

function Limpiar(){
	tabla1.clear();
	document.getElementById('cp').value="";	
	document.getElementById('existe').value="";
	document.getElementById('accion').value="";
	document.getElementById('cp').focus();	
}
function validar(){
	<?=$cpermiso->verificarPermiso(284,$_SESSION[IDUSUARIO]);?>
	if(document.getElementById('cp').value==""){
		alerta('Debe capturar Código Postal', '¡Atención!','cp');
	}else if(document.getElementById('existe').value=="SI"){
		alerta('El Código Postal ya existe', '¡Atención!','cp');
	}else if(document.all.existe.value==""){
	consulta("Existe", "consultas.php?accion=4&codigopostal="+document.all.cp.value+"&valrandom="+Math.random());
	}else if(document.all.cp.value.length < 5){
		alerta('El Código Postal debe contener 5 numeros', '¡Atención!','cp');		
	}else{
			document.getElementById('accion').value = "grabar";
			document.form1.submit();	
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
	else frm.elements[i+1].focus();
	return false;
} 
function limpiarCodigo(){
	u = document.all;
	
	if(tabla_valt1==""){
		tabla_valt1 = u.detalle.innerHTML;
	}
	//u.detalle.innerHTML = tabla_valt1;
	reiniciar_indice(valt1);

}
function BuscarCP(){
	if(document.all.cp.value!=""){
		consulta("mostrarCodigoPostal", "consultas.php?accion=3&codigopostal="+document.all.cp.value+"&valrandom="+Math.random());
		document.all.imagen.style.visibility="visible";
	
	}else{
		alerta('Debe Captura Código Postal','¡Atención!','cp');
	}
}
function ObtenerCodigoPostal(e,cp){	
	 tecla=(document.all) ? e.keyCode : e.which;
	 if(tecla==13){
consulta("mostrarCodigoPostal", "consultas.php?accion=3&codigopostal="+cp+"&valrandom="+Math.random());
		//document.all.imagen.style.visibility="visible";
	}
}
function mostrarCodigoPostal(datos){
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	consulta("ExisteCP", "consultas.php?accion=4&codigopostal="+document.all.cp.value+"&valrandom="+Math.random());
	tabla1.clear();
		if(con>0){
			tabla1.setXML(datos);		
		}else{
			document.all.imagen.style.visibility="hidden";
			alerta("El Código Postal no existe o no esta relacionado con alguna colonia","¡Alerta!","cp");			
		}	
}
function Existe(datos){
	var u = document.all;
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	
		if(con>0){
	if (datos.getElementsByTagName('existe').item(0).firstChild.data=="SI"){
		alerta('El Código Postal ya existe', '¡Atención!','cp');
		}
	}
}
function ExisteCP(datos){
	var u = document.all;
	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
	
		if(con>0){
u.existe.value = datos.getElementsByTagName('existe').item(0).firstChild.data;
		}else{
		u.existe.value = "NO";
		}
}
var nav4 = window.Event ? true : false;
function Numeros(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);

}		
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Catálogo Código Postal</title>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {	font-size: 9px;
	color: #464442;
}
.style5 {color: #FFFFFF ; font-size:9px}

.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="select.js"></script>

<style type="text/css">
<!--
.Estilo2 {	font-size: 8px;
	font-weight: bold;
}
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {font-size: 9px;
	color: #464442;
}
.style51 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
<br>
  <table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="563" class="FondoTabla">CATALOGO CODIGO POSTAL </td>
    </tr>
    <tr>
      <td><br><table width="540" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="91" class="Tablas">&nbsp;&nbsp;&nbsp;C&oacute;digo Postal:</td>
          <td width="104"><input class="Tablas"  name="cp" type="text" id="cp" style="text-transform:uppercase;font:tahoma; font-size:9px" value="<?=$cp ?>" onKeyPress="return Numeros(event)"  onKeyUp="ObtenerCodigoPostal(event,this.value)" size="10" maxlength="5">
            <span class="Tablas"><img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="BuscarCP()" style="cursor:pointer"></span></td>
          <td width="354" id="loading"><img src="../../javascript/loading.gif" name="imagen" width="16" height="16" align="absbottom" id="imagen" style="visibility:hidden"></td>
        </tr>
        <tr>
          <td colspan="3" class="Tablas"><table width="539" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td colspan="3"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
            <input name="existe" type="hidden" id="existe" value="<?=$existe ?>">
            <table width="157" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="71"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>
              <td width="70"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perderá la información capturada ¿Desea continuar?', '', 'Limpiar();', '')" style="cursor:pointer" ></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="3"></td>
        </tr>
      </table></td>
    </tr>
  </table>
  </p>
<p>&nbsp;</p>
</form>
</body>


</html>

<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//}
?>