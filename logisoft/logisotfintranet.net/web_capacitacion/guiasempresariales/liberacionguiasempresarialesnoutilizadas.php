<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	include('../Conectar.php');	
	$link=Conectarse('webpmm');
	
	$fecha=$_POST['fecha'];
	$accion=$_POST['accion'];
	$usuario=$_SESSION[NOMBREUSUARIO];
	$r=$_POST['r'];
	$detalle=$_POST['detalle'];
	$registros=$_POST['registros'];
	$venta=$_POST['venta'];
	$vendedor=$_POST['vendedor'];

	if($_POST['fecha'] == ""){
		$fecha = date('d/m/Y');
	}
	
	if($accion == "guardar"){
		$fecha=cambiaf_a_mysql($fecha);
		$sql=mysql_query("UPDATE solicitudguiasempresariales SET STATUS='0',fecha='$fecha', usuario='$usuario' WHERE foliotipo='".$_POST['venta']."' AND  prepagada='".$_POST['r']."'",$link);
		for($i=0;$i<$registros;$i++){
			$detalle .= "{
					folio:'".$_POST["tabladetalle_FOLIO"][$i]."'},";
		}$detalle = substr($detalle,0,strlen($detalle)-1);
		$mensaje	="Los datos han sido guardados correctamente";
		//$accion		="modificar";
		$fecha=cambiaf_a_normal($fecha);
	}else if($accion=="limpiar"){
		$fecha="";
		$venta="";
		$vendedor="";
		$folioi="";
		$foliof="";
		$eliminar="";
		$modificarfila="";
		$prepagada="";
		$accion="";
		$detalle="";
		$registros="";
			
		$fecha = date('d/m/Y');
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script src="../javascript/shortcut.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
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
-->
</style>

<script>
var u = document.all;
var tabla1 = new ClaseTabla();	
	
	tabla1.setAttributes({
	nombre:"tabladetalle",
	campos:[
			{nombre:"FOLIO", medida:300, alineacion:"left", datos:"folio"}
		],
		filasInicial:10,
		alto:100,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		//u.unidad.focus();
		tabla1.create();
		obtenerDetalles();
	}
	
	function obtenerDetalles(){
	var datosTabla = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTabla!=0){			
			for(var i=0; i<datosTabla.length;i++){
				tabla1.add(datosTabla[i]);
			}
		}	
	}
	
/*********/	
function OptenerNoVenta(id,prepagada){
	consulta("mostrarOptenerNoVenta","liberacionguiasempresarialesnoutilizadas_con.php?id="+id+"&accion="+1+"&prepagada="+prepagada+"&sid="+Math.random());
}

function mostrarOptenerNoVenta(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		
		document.getElementById('bloquear').style.display='none';
		document.getElementById('desbloquear').style.display='none';
		
		if(con>0){
			u.venta.value	=datos.getElementsByTagName('venta').item(0).firstChild.data;
						
			var r=datos.getElementsByTagName('r').item(0).firstChild.data;
			if(datos.getElementsByTagName('foliosactivados').item(0).firstChild.data=='NO'){
				document.getElementById('bloquear').style.display='none';
				document.getElementById('desbloquear').style.display='';
			}else{
				document.getElementById('bloquear').style.display='';
				document.getElementById('desbloquear').style.display='none';
			}
			if(r=="SI"){
				document.all.r[0].checked = true;
			}else if(r=="NO"){
				document.all.r[1].checked = true;
			}
			u.vendedor.value=datos.getElementsByTagName('vendedor').item(0).firstChild.data;
			tabla1.setXML(datos);
			
			var status = datos.getElementsByTagName('status').item(0).firstChild.data;
			if(status==1){
				u.aceptar.style.visibility	= "visible";
				u.nuevo.style.visibility	= "visible";
				
			}else{
				u.aceptar.style.visibility	= "hidden";
				u.nuevo.style.visibility	= "hidden";
				
			}
			
		}else{
			alerta3("No existe No.Venta",'¡Atención!');
			u.vendedor.value="";
			u.aceptar.style.visibility	= "visible";
			u.nuevo.style.visibility	= "visible";
			tabla1.clear();
		}
	
}
/*********/
function Validar(){
	<?=$cpermiso->verificarPermiso("351",$_SESSION[IDUSUARIO]);?>
	u.registros.value = tabla1.getRecordCount();
	if(document.all.vendedor.value == "" && document.all.venta.value == ""){
		alerta('Debe capturar No. Venta','¡Atención!','venta');
		return false;
	}
	
	document.all.accion.value="guardar";
	document.form1.submit();
}



function foco(nombrecaja){
	if(nombrecaja=="venta"){
		document.getElementById('oculto').value="1";
	}
}

shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
			if(document.all.r[0].checked==true){abrirVentanaFija('buscarSolicitudGuias.php?funcion=OptenerNoVenta&prepagada=SI', 550, 450, 'ventana', 'Busqueda');}
			else if(document.all.r[1].checked==true){abrirVentanaFija('buscarSolicitudGuias.php?funcion=OptenerNoVenta&prepagada=NO', 550, 450, 'ventana', 'Busqueda');}
			
	}
	
});

function Limpiar(){
	u.venta.value	="";
	u.vendedor.value="";
	u.oculto.value="";
	u.registros.value="";
	u.accion.value ="";
	tabla1.clear();
	u.aceptar.style.visibility	= "visible";
	u.nuevo.style.visibility	= "visible";
	u.accion.value="limpiar";
	document.getElementById('bloquear').style.display='none';
	document.getElementById('desbloquear').style.display='none';
	
}
function limpiarCompos(){
	u.venta.value	="";
	u.vendedor.value="";
	tabla1.clear();
	u.aceptar.style.visibility	= "visible";
	u.nuevo.style.visibility	= "visible";
}

function des_act(valor){
	<?=$cpermiso->verificarPermiso("350",$_SESSION[IDUSUARIO]);?>
	consulta("res_Act","liberacionguiasempresarialesnoutilizadas_con.php?accion=2&id="+document.all.venta.value
			 +"&estado="+valor+"&sid="+Math.random());
}

function res_Act(datos){
	if(datos.getElementsByTagName('cambio').item(0).firstChild.data=='SI'){
		info("Los folios han sido Activados","");		
		document.getElementById('bloquear').style.display='';
		document.getElementById('desbloquear').style.display='none';
	}else{
		info("Los folios han sido Desactivados","");
		document.getElementById('bloquear').style.display='none';
		document.getElementById('desbloquear').style.display='';
	}
}
</script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="334" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="330" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="330" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="4"><div align="right">Fecha:<span class="Tablas">
          <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
        </span></div></td>
        </tr>
      <tr>
        <td width="66">No. Venta:</td>
        <td width="104"><span class="Tablas">
          <input name="venta" type="text" class="Tablas" id="venta" style="width:100px" value="<?=$venta ?>"  onkeypress="if(event.keyCode==13){if(document.all.r[0].checked==true){OptenerNoVenta(this.value,'SI');}else if(document.all.r[1].checked==true){OptenerNoVenta(this.value,'NO');}}"  onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''"/>
        </span></td>
		      <td width="160"><div class="ebtn_buscar" onclick="if(document.all.r[0].checked==true){abrirVentanaFija('buscarSolicitudGuias.php?funcion=OptenerNoVenta&prepagada=SI', 600, 550, 'ventana', 'Busqueda');}else if(document.all.r[1].checked==true){abrirVentanaFija('buscarSolicitudGuias.php?funcion=OptenerNoVenta&prepagada=NO', 600, 550, 'ventana', 'Busqueda');}"></div></td>
      </tr>
      <tr>
        <td colspan="4"><table width="331" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="98"><label>
              <input name="r" type="radio" value="SI" <?  if($r=="SI"){echo "checked";} ?>  checked="checked" onclick="limpiarCompos()"/>
              Pre-Pagadas</label></td>
            <td width="233"><label>
              <input name="r" type="radio" value="NO" <?  if($r=="NO"){echo "checked";} ?>  onclick="limpiarCompos()"/>
              Consignación</label></td>
          </tr>
          <tr>
            <td><label>
              <input name="radiobutton" type="radio" value="radiobutton" checked="checked" />
              Impresas</label></td>
            <td><label>
              <input name="radiobutton" type="radio" value="radiobutton" />
              Web</label></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>Vendedor:</td>
        <td colspan="3"><span class="Tablas">
          <input name="vendedor" type="text" class="Tablas" id="vendedor" style="width:250px;background:#FFFF99" value="<?=$vendedor ?>" readonly="" />
        </span></td>
      </tr>
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" class="FondoTabla Estilo4">Datos Folio </td>
      </tr>
      <tr>
        <td colspan="6"><table width="100%" border="0">
          <tr>
            <td width="240" ><table id="tabladetalle" border="0" cellpadding="0" cellspacing="0">
       
            </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="6" align="center"><table width="174" border="0" align="right" >
          
          <tr>
            <td width="86"><div id="bloquear" class="ebtn_bloquear" onclick="confirmar('¿Desea Bloquear los folios?','¡ATENCION!','des_act(\'NO\')','')"   style='display:none'></div>
            <div id="desbloquear" class="ebtn_desbloquear" onclick="confirmar('¿Desea Desbloquear los folios?','¡ATENCION!','des_act(\'SI\')','')" 	style='display:none;'></div></td>
            <td width="86"><div id="aceptar" class="ebtn_liberar" onclick="Validar()" 	<? if($accion=="guardar"){echo "style='visibility:hidden;'"; }else{  echo "style='visibility:visible;'";   } ?> ></div></td>
            <td width="99"><div id="nuevo" class="ebtn_nuevo" onclick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'Limpiar();', '')" ></div></td>
          </tr>
        </table>
        
          <p>
            <input name="oculto" type="hidden" id="oculto" />
            <input name="accion" type="hidden" id="accion" />
            <input name="registros" type="hidden" id="registros" />
            </p></td>
      </tr>
    </table></td>
  </tr>
</table>

</form>
</body>
</html>

<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
//	}
?>