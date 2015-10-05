<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if(isset($_SESSION['gvalidar'] )!=100){ echo "<script language='javascript' type='text/javascript'>						document.location.href='../../../index.php';</script>";
	}else{*/
		require_once('../../Conectar.php');	
		$link=Conectarse('webpmm');		
		$usuario=$_SESSION[NOMBREUSUARIO];
	$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion']; $restringir=$_POST['restringir'];
		if($accion==""){
		$row=folio('catalogoservicio','webpmm');
		$codigo=$row[0];
	}
		if($accion=="grabar"){
	$sqlins=mysql_query("INSERT INTO catalogoservicio (id, descripcion, restringir, usuario, fecha)VALUES('null', UCASE('$descripcion'),'$restringir', '$usuario', current_timestamp())",$link);		
			$mensaje="Los datos han sido guardados correctamente";
			$codigo=mysql_insert_id();
			$accion="modificar";
		}else if($accion=="modificar"){
			$sqlupd=mysql_query("UPDATE catalogoservicio SET descripcion=UCASE('$descripcion'), restringir='$restringir', usuario='$usuario', fecha=current_timestamp() where id='$codigo'",$link);
			$mensaje='Los cambios han sido guardados correctamente';
			$accion="modificar";
	}else if($accion=="limpiar"){
		$codigo="";
		$accion="";
		$descripcion="";
		$restringir="";
		$msg="";
		$usuario=$_SESSION[NOMBREUSUARIO];
		$row=folio('catalogoservicio','webpmm');
		$codigo=$row[0];
	}

?>
<script language="JavaScript" type="text/javascript">
function Limpiar(){
	document.getElementById('descripcion').value="";
	document.getElementById('accion').value = "limpiar";
	document.form1.submit();
}
function validar(){
 	 if(document.getElementById('descripcion').value==""){
			alerta('Debe capturar Descripción', '¡Atención!','descripcion');
	}else{
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
			}else if(document.getElementById('accion').value="modificar"){
				document.form1.submit();
			}
	}
}
function Consultar(Codigo){
	if(Codigo!=""){
		document.getElementById('accion').value = "buscar";
		document.form1.submit();
	}
}
function obtener(id,descripcion,restringir){
	document.getElementById('codigo').value=id;
	document.getElementById('descripcion').value=descripcion;	
	document.getElementById('descripcion').focus();
	document.getElementById('accion').value="modificar";
	if(restringir!=""){
			document.getElementById('restringir').checked=true;
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
</script>
<script src="../../javascript/ajax.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
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
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 13px;
	font-family: tahoma;
}
-->
</style>
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<body onLoad="document.form1.descripcion.focus()">
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0">
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td><table width="310" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        <tr>
          <td class="FondoTabla">Datos Generales </td>
        </tr>
        <tr>
          <td><br><table width="309" border="0" align="center">
            
            <tr>
              <td width="73" class="Tablas">C&oacute;digo:</td>
              <td width="159">
                <input name="codigo" class="Tablas" type="text" id="codigo" size="10" value="<?=$codigo ?>" style="background:#FFFF99; font-size:9px; font:tahoma" readonly=""  />
                &nbsp;<img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarcatservicio.php', 550, 450, 'ventana', 'Busqueda')"></td>
              <td width="137"><label>
                <input name="restringir" onKeyPress="return tabular(event,this)" type="checkbox" id="restringir" value="SI" <? if($restringir=="SI"){echo'checked'; }?> />
                </label>
                  <span class="Tablas">Restringir</span></td>
              </tr>
            <tr>
              <td class="Tablas">Descripci&oacute;n:</td>
              <td colspan="2"><input name="descripcion" type="text" id="descripcion" onKeyPress="return tabular(event,this)" class="Tablas" onBlur="trim(document.getElementById('descripcion').value,'descripcion')" size="50" value="<?= $descripcion ?>" style=" font:tahoma; font-size:9px; text-transform:uppercase" /></td>
            </tr>
            <tr>
              <td height="32"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>" /></td>
              <td colspan="2"><table width="141" border="0" align="right">
                  <tr>
                    <td><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>
                    <td><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'Limpiar();', '')" style="cursor:pointer" ></td>
                  </tr>
              </table></td>
            </tr>            
          </table>
          <br></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO SERVICIOS';
</script>
<? 
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";	
	}
//} ?>