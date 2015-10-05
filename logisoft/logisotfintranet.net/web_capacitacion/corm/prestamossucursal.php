<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../Conectar.php');	
	$link=Conectarse('webpmm');

	$folio=$_POST['folio'];$foliobitacora=$_POST['foliobitacora'];
	$conductor=$_POST['conductor'];	$usuario=$_SESSION[NOMBREUSUARIO];
	$accion=$_POST['accion'];

$fecha = $_POST['fecha'];
	if($fecha == ""){
		$fecha = date("d/m/Y");
	}else{
		$fecha = cambiaf_a_mysql($fecha);
	}

if($accion==""){	
	$s = "SELECT obtenerFolio('prestamosucursal',".$_SESSION[IDSUCURSAL].") AS folio";
	$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
	$folio = $fo->folio;
}else if($accion=="grabar"){
	$sql_nuevo	=mysql_query("INSERT INTO prestamosucursal(folio,fechai,foliobitacora,conductor,cantidad,usuario,fecha)VALUES(NULL,'$fecha','$foliobitacora','$conductor','$cantidad','$usuario',CURRENT_TIMESTAMP())",$link);
	$folio		=mysql_insert_id();
	$mensaje	="Los datos han sido guardados correctamente";
	$accion		="limpiar";
}else if($accion == "modificar"){
	$sql_modificar	=mysql_query("UPDATE prestamosucursal SET  foliobitacora = '$foliobitacora',conductor = '$conductor' , cantidad='$cantidad', usuario = '$usuario',fecha=CURRENT_TIMESTAMP() WHERE folio = '$folio' ",$link);
	$mensaje	= 'Los cambios han sido guardados correctamente';
	$accion		="limpiar";
}else if($accion=="limpiar"){
	$fecha			="";
	$folio			="";
	$foliobitacora	="";
	$conductor		="";
	$cantidad		="";	
	$accion  		="";
	$fecha = date("d/m/Y");	
	$row=ObtenerFolio('prestamosucursal','webpmm');
	$folio=$row[0];
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ajax.js"></script>
<style type="text/css">
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
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
<script language="JavaScript">
var nav4 = window.Event ? true : false;
function Numeros(evt){
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);
}
function Limpiar(){
	document.getElementById('fecha').value="";
	document.getElementById('folio').value="";
	document.getElementById('foliobitacora').value="";
	document.getElementById('conductor').value="";
	document.getElementById('cantidad').value="";
	document.getElementById('accion').value = "limpiar";
	document.form1.submit();
}
function validar(){
	if(document.getElementById('foliobitacora').value==""){
			alerta('Debe capturar Folio Bitacora','¡Atención!','foliobitacora');
			return false;
	}else if(document.getElementById('conductor').value==""){
			alerta('Debe capturar Conductor','¡Atención!','conductor');
			return false;
	}else if(document.getElementById('cantidad').value==""){
			alerta('Debe capturar Cantidad','¡Atención!','cantidad');
	}else if(document.getElementById('cantidad').value < 0 || document.getElementById('cantidad').value=="-0" ){
			alerta('Cantidad debe ser mayor o igual a Cero','¡Atención!','cantidad');
	}else{
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
			}else if(document.getElementById('accion').value=="modificar"){
				document.form1.submit();
			}
	}
}

function OptenerIdBitacora(foliobitacora,conductor){
	document.getElementById('foliobitacora').value	=foliobitacora;
	document.getElementById('conductor').value		=conductor;
}

function foco(nombrecaja){
	if(nombrecaja=="foliobitacora"){
		document.getElementById('oculto').value="1";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
abrirVentanaFija('prestamossucursal_buscar.php?tipo=1', 550, 450, 'ventana', 'Busqueda')	}
});

/********************/
function BuscarFolioBitacora(folio){
		consulta("mostrarBuscarFolioBitacora","consultaCORM.php?folio="+folio+"&accion="+6+"&sid="+Math.random());
}

function mostrarBuscarFolioBitacora(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		if(con>0){	
			u.foliobitacora.value=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
			u.conductor.value=datos.getElementsByTagName('conductor').item(0).firstChild.data;
		}else{
			alerta("No existe la bitacora",'¡Atención!','foliobitacora');
		}
}

function OptenerPrestamoSucursal(folio){
	consulta("mostrarPrestamoSucursal","consultaCORM.php?folio="+folio+"&accion=8&sid="+Math.random());
}
function mostrarPrestamoSucursal(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		if(con>0){
			u.fecha.value			=datos.getElementsByTagName('fecha').item(0).firstChild.data;
			u.folio.value			=datos.getElementsByTagName('folio').item(0).firstChild.data;	
			u.foliobitacora.value	=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
			u.conductor.value		=datos.getElementsByTagName('conductor').item(0).firstChild.data;
			u.cantidad.value		=datos.getElementsByTagName('cantidad').item(0).firstChild.data;			
			u.accion.value			="modificar";
		}else{
			alerta("No existe el registro",'¡Atención!','foliobitacora');
		}
}
/***********************/
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="377" class="FondoTabla Estilo4">PRESTAMOS SUCURSAL</td>
  </tr>
  <tr>
    <td height="98"><div align="center">
      <table width="377" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="center"></div></td>
        </tr>
        <tr>
          <td><table width="375" border="0" cellpadding="0" cellspacing="0">
                  <tr> 
                    <td width="347"><div align="right">Fecha:<span class="Tablas"> 
                        <input name="fecha" type="text" class="Tablas" id="fecha" style="width:90px;background:#FFFF99" value="<?=$fecha ?>" size="40" readonly=""/>
                        </span>Folio:<span class="Tablas"> 
                        <input name="folio" type="text" class="Tablas" id="folio" style="width:50px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
                        </span></div></td>
                    <td width="28"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/BuscarPrestamoSucursal.php', 600, 550, 'ventana', 'Busqueda')"></div></td>
                  </tr>
                </table></td>
        </tr>
  
        
        
        <tr>
          <td>&nbsp;</td>
        </tr>
        
        <tr>
          <td width="377"><table width="377" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td height="24">Folio Bitacora:</td>
                    <td width="115"><span class="Tablas">
                      <input name="foliobitacora" type="text" class="Tablas" id="foliobitacora" style="width:100px;" value="<?=$foliobitacora ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onKeyPress="if(event.keyCode==13){BuscarFolioBitacora(this.value)}" onKeyDown="if(event.keyCode==8){document.all.conductor.value=''}"/>
                    </span></td>
                    <td><div class="ebtn_buscar" onClick="abrirVentanaFija('prestamossucursal_buscar.php?tipo=1', 600, 550, 'ventana', 'Busqueda')"></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="24">Conductor:</td>
                    <td colspan="3"><span class="Tablas">
                      <input name="conductor" type="text" class="Tablas" id="conductor" style="width:250px;background:#FFFF99" value="<?=$conductor ?>" readonly=""/>
                    </span></td>
                  </tr>
                  <tr> 
                    <td width="88" height="24">Cantidad:</td>
                    <td colspan="3"><span class="Tablas">
                      <input name="cantidad" type="text" class="Tablas" id="cantidad" style="width:100px;" onKeyPress="return Numeros(event)" value="<?=$cantidad ?>" maxlength="10" />
                    </span></td>
                  </tr>
                  <tr> 
                    <td height="11" colspan="2"><div align="right">
                      <input name="accion" type="hidden" id="accion" value="<?=$accion?>">
                      <input name="oculto" type="hidden" id="oculto" value="<?=$accion ?>">                      
                      </div></td>
                    <td width="97"></td>
                    <td width="77"><table width="145" border="0" align="right" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="75"><div class="ebtn_guardar" onClick="validar();"> 
                      </div></td>
                        <td width="125"><div class="ebtn_nuevo" onClick="Limpiar();"></div></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
</form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'PRÉSTAMO SUCURSAL';
</script>
</html>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//	}
?>