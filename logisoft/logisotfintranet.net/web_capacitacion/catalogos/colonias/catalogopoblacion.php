<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){
	 echo "<script language='javascript' type='text/javascript'>
						document.location.href='../../../index.php';
					</script>";
	}else{*/
		include('../../Conectar.php');	
		$link=Conectarse('webpmm');
$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion'];  $usuario=$_SESSION[NOMBREUSUARIO];$municipio=$_POST['municipio'];$descripcionmunicipio=$_POST['descripcionmunicipio'];$estado=$_POST['estado'];$pais=$_POST['pais'];

if($accion==""){
	$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogopoblacion",$link);
	$row=mysql_fetch_array($sql);
	$codigo=$row[0];
 }
	if($accion=="grabar"){		
		$sqlins="insert into catalogopoblacion 	(id, descripcion, municipio, usuario, fecha)	values ('$codigo', UCASE('$descripcion'), '$municipio','$usuario', current_timestamp())";
		$res=mysql_query($sqlins,$link);
		$codigo=mysql_insert_id();
		$mensaje = 'Los datos han sido guardados correctamente';
		$accion="modificar";
	}else if($accion=="modificar"){
		$sqlupd="UPDATE catalogopoblacion SET  descripcion=UCASE('$descripcion'), municipio='$municipio', usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'"; 
		$res=mysql_query($sqlupd,$link);
		$mensaje = 'Los cambios han sido guardados correctamente';	
	}else if($accion=="limpiar"){
		$tipocliente="";
		$descripcion="";
		$codigo="";
		$msg="";
		$accion="";
	$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogopoblacion",$link);
	$row=mysql_fetch_array($sql);
	$codigo=$row[0];
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/shortcut.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo Poblaci&oacute;n</title>
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
<script src="../../javascript/ajax.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="select.js"></script>
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
function limpiar(){
	document.getElementById('codigo').value="";
	document.getElementById('descripcion').value="";
	document.getElementById('municipio').value="";
	document.getElementById('descripcionmunicipio').value="";
	document.getElementById('estado').value="";
	document.getElementById('pais').value="";
	document.getElementById('accion').value = "limpiar";
	document.form1.submit();
}
function validar(){
	 if(document.getElementById('codigo').value==""){
			alerta('Debe capturar ', '¡Atención!','codigo');			
	 }else if(document.getElementById('descripcion').value==""){
			alerta('Debe capturar Descripción', '¡Atención!','descripcion');
	 }else if(document.getElementById('municipio').value==""){
			alerta('Debe seleccionar Mun./Del.', '¡Atención!','municipio');
	 }else{
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
			}else if(document.getElementById('accion').value="modificar"){
				document.form1.submit();
			}
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
            else frm.elements[i+1].focus();
            return false;
} 



function foco(nombrecaja){
	//VALIDAR SHORTCUT
	if(nombrecaja=="municipio"){
		document.getElementById('oculto').value="1";	
	}
	if(nombrecaja=="codigo"){
		document.getElementById('oculto').value="2";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
		abrirVentanaFija('buscarMunicipioPoblacion.php', 570, 480, 'ventana', 'Busqueda Municipio');
	}
	if(document.form1.oculto.value=="2"){
		abrirVentanaFija('buscarPoblacionPoblacion.php', 570, 480, 'ventana', 'Busqueda Población');
	}
});

function obtener(id,descripcion){
	document.getElementById('codigo').value=id;
	document.getElementById('descripcion').value=descripcion;
	ConsultaPoblacion(id,descripcion,'1');
}
function obtenerPoblacionx(id){
	if(id!=""){
		document.getElementById('codigo').value=id;		
consulta("mostrarConsultaPoblacion","consultas.php?accion=6&poblacion="+id);
	}
}

function obtenerMunicipiox(id){
	if(id!=""){
		document.getElementById('municipio').value=id;		
		consulta("mostrarMunicipio","consultas.php?accion=5&municipio="+id);
		}
}
function mostrarMunicipio(datos){
		var u	= document.all;
		var con  = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){			
		u.descripcionmunicipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.estado.value = datos.getElementsByTagName('estado').item(0).firstChild.data;
		u.pais.value = datos.getElementsByTagName('pais').item(0).firstChild.data;
		}		
}

function mostrarConsultaPoblacion(datos){
		var u	= document.all;
		var con  = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){			
			u.descripcion.value = datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
			u.descripcionmunicipio.value = datos.getElementsByTagName('descripcionmunicipio').item(0).firstChild.data;
			u.estado.value = datos.getElementsByTagName('estado').item(0).firstChild.data;
			u.pais.value = datos.getElementsByTagName('pais').item(0).firstChild.data;
			u.accion.value = datos.getElementsByTagName('accion').item(0).firstChild.data;	
			
		}		
}
</script>
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
-->
</style>
</head>

<body onLoad="document.all.descripcion.focus();" >
<form name="form1" method="post" >
  <table width="100%" border="0">
    <tr>
      <td><table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
          <tr>
            <td width="563" class="FondoTabla">CAT&Aacute;LOGO POBLACI&Oacute;N</td>
          </tr>
          <tr>
            <td><table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="72" class="Tablas"><strong>C&oacute;digo:</strong></td>
                <td width="328"><label>
                  <input name="codigo" type="text" id="codigo" style="font:tahoma; font-size:9px; background:#FFFF99" value="<?=$codigo ?>" size="10" maxlength="0" onBlur="document.getElementById('oculto').value=''" onKeyPress="return tabular(event,this)"    onFocus="foco(this.name)">
                  &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarPoblacionPoblacion.php', 600, 500, 'ventana', 'Busqueda Población')">
                  &nbsp;&nbsp;</label></td>
              </tr>
              <tr>
                <td colspan="2" class="Tablas"></td>
                </tr>
              <tr>
                <td colspan="2" class="Tablas"><div id="txtHint"><table width="399" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="73" class="Tablas">Descripci&oacute;n:</td>
                    <td width="326"><input name="descripcion" type="text" id="descripcion" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" onKeyPress="return tabular(event,this)" size="59" value="<?=$descripcion ?>" style="text-transform:uppercase;font:tahoma; font-size:9px"></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="Tablas"><div id="txtEstado"><table width="399" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="73" class="Tablas">Mun./Del.:</td>
                        <td width="326"><input name="municipio" type="text" id="municipio" style=" font:tahoma; font-size:9px; background:#FFFF99" onFocus="foco(this.name)"  onBlur="document.getElementById('oculto').value=''" value="<?=$municipio ?>" size="10" maxlength="0" >                          &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarMunicipioPoblacion.php', 600, 500, 'ventana', 'Busqueda Municipio')">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="Tablas">&nbsp;</td>
                        <td><input name="descripcionmunicipio" type="text" id="descripcionmunicipio" size="35" value="<?=$descripcionmunicipio ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly=""></td>
                      </tr>
                      <tr>
                        <td class="Tablas">Estado:</td>
                        <td class="Tablas"><input name="estado" type="text" id="estado" size="25" value="<?=$estado ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">
                          &nbsp;&nbsp;Pa&iacute;s:&nbsp;&nbsp;
                          <input name="pais" type="text" id="pais" size="21" value="<?=$pais ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">
                          <input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>
                      </tr>
                    </table></div></td>
                    </tr>
                  
                </table></div></td>
                </tr>
              <tr>
                <td height="32"><input name="oculto" type="hidden" id="oculto"></td>
                <td><table width="141" border="0" align="right">
                  <tr>
                    <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>
                    <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" style="cursor:pointer" ></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="32" colspan="2"></td>
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

<?
	if ($mensaje!=""){
		echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
	//}
?>