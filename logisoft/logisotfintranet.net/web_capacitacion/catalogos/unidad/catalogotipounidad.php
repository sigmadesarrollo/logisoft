<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if(isset($_SESSION['gvalidar'] )!=100){ echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{ */
		include('../../Conectar.php');	
		$link=Conectarse('webpmm');
$usuario=$_SESSION[NOMBREUSUARIO]; $accion=$_POST['accion'];	$codigo=$_POST['codigo']; $descripcion=$_POST['descripcion'];
	 	
	if($accion==""){
	$sqlid=mysql_query("SELECT IFNULL(MAX(id),0)+1 as id FROM catalogotipounidad",$link)or die(__LINE__);
		$resid=mysql_fetch_array($sqlid);
		$codigo=$resid[0];
	}
		
	if($accion=="grabar"){
		$s = "INSERT INTO catalogotipounidad (id, descripcion, usuario, fecha)VALUES(null, UCASE('$descripcion'),'$usuario', current_timestamp())";
		$sqlins=mysql_query($s,$link) or die($s);			
			$codigo=mysql_insert_id();
			$mensaje="Los datos han sido guardados correctamente";
			$accion="modificar";
		}else if($accion=="modificar"){
		$sqlupd=mysql_query("UPDATE catalogotipounidad SET descripcion=UCASE('$descripcion'),usuario='$usuario',fecha=current_timestamp() where id='$codigo'",$link) or die(__LINE__);		
			$mensaje = 'Los cambios han sido guardados correctamente';	
		}else if($accion=="limpiar"){
		$codigo=""; $accion=""; $descripcion=""; $usuario=$_SESSION[NOMBREUSUARIO];
		$sqlid=mysql_query("SELECT IFNULL(MAX(id),0)+1 as id FROM catalogotipounidad",$link)or die(__LINE__);
		$resid=mysql_fetch_array($sqlid); $codigo=$resid[0];		
		}	
?>
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<title>Cat&aacute;logo Tipo Unidades </title>
<script src="../../javascript/shortcut.js"></script>
<script src="../../javascript/ajax.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
function Limpiar(){
	document.getElementById('codigo').value="";
	document.getElementById('descripcion').value="";
	document.getElementById('accion').value = "limpiar";
	document.form1.submit();
}
function validar(){
	if(document.getElementById('codigo').value==""){
			alerta('Debe capturar Código','¡Atención!','codigo');
	}else if(document.getElementById('descripcion').value==""){
			alerta('Debe capturar Descripción','¡Atención!','descripcion');			
	}else{
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
			}else if(document.getElementById('accion').value="modificar"){
				document.form1.submit();
			}
	}
}
function obtener(id,descripcion){
	if(id!=""){
	document.getElementById('codigo').value=id;
	document.getElementById('descripcion').value=descripcion;
	document.getElementById('descripcion').focus();
	document.getElementById('accion').value="modificar";
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
function tabular(e,obj) { 
  tecla=(document.all) ? e.keyCode : e.which; 
  if(tecla!=13) return; 
  frm=obj.form; 
  for(i=0;i<frm.elements.length;i++) 
    if(frm.elements[i]==obj) { 
      if (i==frm.elements.length-1) i=-1; 
      break } 
  frm.elements[i+1].focus(); 
  return false; 
}
var nav4 = window.Event ? true : false;
function Numeros(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57));
}
function foco(nombrecaja){
	if(nombrecaja=="codigo"){
		document.getElementById('oculto').value="1";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
abrirVentanaFija('buscar.php?tipo=tipounidad', 600, 550, 'ventana', 'Busqueda')	}
});

</script>
<script src="select.js"></script>
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
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
-->
</style>
</head>
<body onLoad="document.form1.descripcion.focus()">
<form name="form1" method="post" action=""  >

 <table width="100%" border="0">
   <tr>
     <td><table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
         <tr>
           <td width="563" class="FondoTabla">CAT&Aacute;LOGO TIPO UNIDAD</td>
         </tr>
         <tr>
           <td><table width="301" border="0" align="center">
               <tr>
                 <td width="85" class="Tablas">C&oacute;digo:</td>
                 <td width="206" class="Tablas">
                   <input name="codigo" class="Tablas" type="text" id="codigo" size="10" value="<?= $codigo ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" >
                   &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscar.php?tipo=tipounidad', 600, 470, 'ventana', 'Busqueda')"> &nbsp;&nbsp;</td>
               </tr>
               <tr>
                 <td class="Tablas">Descripci&oacute;n:</td>
                 <td><input name="descripcion" type="text" id="descripcion" onBlur="trim(document.getElementById('descripcion').value,'descripcion'); " onKeyPress="return tabular(event,this)" size="50" class="Tablas" value="<?= $descripcion ?>" style="text-transform:uppercase;font:tahoma; font-size:9px" ></td>
               </tr>
               <tr>
                 <td height="32"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
                   <input name="oculto" type="hidden" id="oculto" value="<?=$accion ?>"></td>
                 <td><table width="141" border="0" align="right">
                   <tr>
                     <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>
                     <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'Limpiar();', '')" style="cursor:pointer" ></td>
                   </tr>
                 </table></td>
               </tr>
               <tr>
                 <td height="32" colspan="2"></td>
</tr>
           </table></td>
         </tr>
     </table>
       </p>
<p><center>
      </center></p></td>
   </tr>
 </table> 
 </form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO TIPO UNIDAD';
</script>
</html>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//	}
?>