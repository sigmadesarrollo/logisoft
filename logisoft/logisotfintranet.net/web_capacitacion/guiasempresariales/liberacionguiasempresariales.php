<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	$venta		=$_POST['venta'];
	$vendedor	=$_POST['vendedor'];
	$r			=$_POST['r'];
	$folioi		=$_POST['folioi'];
	$foliof		=$_POST['foliof'];
	$accion		=$_POST['accion'];
	$registros	=$_POST['registros'];
	$usuario=$_SESSION[NOMBREUSUARIO];
	
	//echo $registros."<br>";
	if($_POST['fecha'] == ""){
		$fecha = date('d/m/Y');
	}
if($accion=="guardar"){
	$fecha=cambiaf_a_mysql($fecha);
		if($registros>0){
			for($i=0;$i<$registros;$i++){
			$fi=$_POST["tabladetalle_FOLIO_INICIAL"][$i];
			$ff=$_POST["tabladetalle_FOLIO_FINAL"][$i];
			$imp=$_POST["tabladetalle_IMPORTE"][$i];
			
				$folioii=(int)substr($fi,3,-1);
				$folioff=(int)substr($ff,3,-1);
				$i=(int)substr($fi,0,-10);
				$f=substr($fi,-1);
for($folioii;$folioii<=$folioff;$folioii++){
	$sql_sel=mysql_query("SELECT id,evaluacion FROM guiasempresariales
	WHERE id='".$i.str_pad($folioii,9,"0",STR_PAD_LEFT).$f."'",$link) or die("Error en la linea ".__LINE__);	
	$E= mysql_fetch_array($sql_sel)or die("Error en la linea ".__LINE__);
	
	$sql_update=mysql_query("UPDATE evaluacionmercancia SET
	estado='GUARDADO',fecha=CURRENT_TIMESTAMP(),usuario='$usuario'
	WHERE folio='".$E['evaluacion']."' and sucursal = ".$_SESSION[IDSUCURSAL]."",$link)or die("Error en la linea ".__LINE__);		
	
	$sql_del=mysql_query("DELETE FROM guiasempresariales 
	WHERE id='".$i.str_pad($folioii,9,"0",STR_PAD_LEFT).$f."'",$link)or die("Error en la linea ".__LINE__);
	
	$sql_del_det=mysql_query("DELETE FROM guiasempresariales_detalle 
	WHERE id='".$i.str_pad($folioii,9,"0",STR_PAD_LEFT).$f."'",$link)or die("Error en la linea ".__LINE__);
	
	$sql_del_uni=mysql_query("DELETE FROM guiasempresariales_unidades 
	WHERE idguia='".$i.str_pad($folioii,9,"0",STR_PAD_LEFT).$f."'",$link)or die("Error en la linea ".__LINE__);
	
	$sql_del_repven=mysql_query("CALL proc_RegistroVentas('CANCELAR_GUIAE','".$i.str_pad($folioii,9,"0",STR_PAD_LEFT).$f."',0)",
	$link)or die("Error en la linea ".__LINE__);
	
	
	$sql_del_repven=mysql_query("CALL proc_RegistroCobranza('CANCELARVENTA', '".$i.str_pad($folioii,9,"0",STR_PAD_LEFT).$f."', 'EMPRESARIAL', '', 0, 0);",
	$link)or die("Error en la linea ".__LINE__);
}
			$detalle .= "{
						folio_inicial:'".$fi."',
						folio_final:'".$ff."',
						importe:'".$imp."'},";
			}$detalle = substr($detalle,0,strlen($detalle)-1);
		}
		$mensaje	="Los datos han sido guardados correctamente";
		//$accion="";
		$fecha=cambiaf_a_normal($fecha);
	}else if($accion=="limpiar"){
		$fecha	="";
		$venta	="";
		$vendedor="";
		$folioi	="";
		$foliof	="";
		$accion ="";
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
			{nombre:"FOLIO_INICIAL", medida:140, alineacion:"center", datos:"folio_inicial"},
			{nombre:"FOLIO_FINAL", medida:140, alineacion:"center", datos:"folio_final"},
			{nombre:"IMPORTE", medida:130, tipo:"moneda", alineacion:"center", datos:"importe"}
		],
		filasInicial:10,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"document.all.eliminar.value=tabla1.getSelectedIdRow()",
		eventoDblClickFila:"ModificarFila()",
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
function agregarVar(total){
	if(u.modificarfila.value!=""){
			tabla1.deleteById(document.all.modificarfila.value);
			u.modificarfila.value="";
	}	

	var registro 			= new Object();
	registro.folio_inicial	= u.folioi.value;
	registro.folio_final	= u.foliof.value;
	
	registro.importe	= total;
	tabla1.add(registro);
	
	u.foliof.value="";
	u.folioi.value="";
}
//***************************
function OptenerNoVenta(id,prepagada){
	consulta("mostrarOptenerNoVenta","liberacionguiasempresariales_con.php?id="+id+"&accion="+1+"&prepagada="+prepagada+"&sid="+Math.random());
}

function mostrarOptenerNoVenta(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		if(con>0){
			u.venta.value	=datos.getElementsByTagName('venta').item(0).firstChild.data;
			var r		=datos.getElementsByTagName('r').item(0).firstChild.data;
			
			if(r=="SI"){
				document.all.r[0].checked = true;
			}else if(r=="NO"){
				document.all.r[1].checked = true;
			}
			u.vendedor.value=datos.getElementsByTagName('vendedor').item(0).firstChild.data;
			u.prepagada.value=r;
		}else{
			alerta3("No existe No.Venta",'¡Atención!');
			u.vendedor.value="";
			u.prepagada.value="";
			tabla1.clear();
		}
}
/*****/
function EliminarFila(){
	if(document.all.eliminar.value!=""){
		if(tabla1.getValSelFromField("folio_inicial","FOLIO_INICIAL")!=""){
			tabla1.deleteById(document.all.eliminar.value);
		}
	}else{
		alerta3('Seleccione la fila a eliminar','¡Atención!');
	}
}
/*****/
function ModificarFila(){
	var obj = tabla1.getSelectedRow();
	if(tabla1.getValSelFromField("folio_inicial","FOLIO_INICIAL")!=""){
		document.all.folioi.value	=obj.folio_inicial;
		document.all.foliof.value	=obj.folio_final;
		document.all.modificarfila.value =tabla1.getSelectedIdRow();
	}
}
/***/
	function ValidarFolio(){
		<?=$cpermiso->verificarPermiso("349",$_SESSION[IDUSUARIO]);?>
		if(u.venta.value==""){
			alerta('Debe capturar No Venta','¡Atención!','venta');
			return false;
		}else if(u.folioi.value==""){
			alerta('Debe capturar Folio Inicial','¡Atención!','folioi');
			return false;	
		}else if(u.folioi.value.length <13 || u.folioi.value.length>13){
			alerta('Debe capturar Folio Inicial correctamente','¡Atención!','folioi');
			return false;
		}else if(u.foliof.value!="" && u.foliof.value.length<13){
			alerta('Debe capturar Folio Final correctamente','¡Atención!','foliof');
			return false;
		}
		if(u.foliof.value==""){
			u.foliof.value=u.folioi.value;			
		}
		
			consulta("mostrarValidarFolio","liberacionguiasempresariales_con.php?accion=2&folioi="+u.folioi.value
			+"&foliof="+u.foliof.value+"&id="+u.venta.value+"&prepagada="+u.prepagada.value+"&sid="+Math.random());
	}
function mostrarValidarFolio(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var total = datos.getElementsByTagName('total').item(0).firstChild.data;
		var existe = datos.getElementsByTagName('existe').item(0).firstChild.data;
		if(con==1){
			if(existe==1){
				agregarVar(total);
			}else{
				alerta3('No coincide el folio con las guías registradas.','¡Atención!');
			}
		}else{
				alerta3('No coincide el rango de folio con el rango de solicitud de guías empresariales.','¡Atención!');
		}
}
/****/

function validar(){
	<?=$cpermiso->verificarPermiso("401",$_SESSION[IDUSUARIO]);?>
	if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
			alerta3('Debe agregar por lo menos un rango de folios','¡Atención!');
			return false;			
	}
	u.registros.value = tabla1.getRecordCount();
	u.accion.value="guardar";
	document.form1.submit();
}

function limpiarCompos(){
	u.venta.value	="";
	u.vendedor.value="";
	u.folioi.value	="";
	u.foliof.value	="";
}


function foco(nombrecaja){
	if(nombrecaja=="venta"){
		document.getElementById('oculto').value="1";
	}
}

shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
			if(document.all.r[0].checked==true){
			abrirVentanaFija('buscarSolicitudGuias.php?funcion=OptenerNoVenta&prepagada=SI', 550, 450, 'ventana', 'Busqueda');
			}else if(document.all.r[1].checked==true){
			abrirVentanaFija('buscarSolicitudGuias.php?funcion=OptenerNoVenta&prepagada=NO', 550, 450, 'ventana', 'Busqueda');
			}
	}
	
});


function limpiar(){
	u.venta.value	="";
	u.vendedor.value="";
	u.r.checked		=true;
	u.folioi.value	="";
	u.foliof.value	="";
	tabla1.clear();
	u.accion.value="limpiar";
	document.form1.submit();
}



</script>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="330" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>No. Venta:</td>
        <td><span style="width:150px"><span class="Tablas">
          <input name="venta" type="text" class="Tablas" id="venta" style="width:80px;" value="<?=$venta ?>" onkeypress="if(event.keyCode==13){if(document.all.r[0].checked==true){OptenerNoVenta(this.value,'SI');}else if(document.all.r[1].checked==true){OptenerNoVenta(this.value,'NO');}}" onfocus="foco(this.name)" onblur="document.getElementById('oculto').value=''"/>
          <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="if(document.all.r[0].checked==true){abrirVentanaFija('buscarSolicitudGuias.php?funcion=OptenerNoVenta&prepagada=SI', 550, 450, 'ventana', 'Busqueda');}else if(document.all.r[1].checked==true){abrirVentanaFija('buscarSolicitudGuias.php?funcion=OptenerNoVenta&prepagada=NO', 550, 450, 'ventana', 'Busqueda');}" /></span></span></td>
        <td>&nbsp;</td>
        <td width="48">Fecha:</td>
        <td width="115"><span class="Tablas">
          <input name="fecha" type="text" class="Tablas" id="fecha" style="width:90px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
        </span></td>
        </tr>
      <tr>
        <td colspan="5"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="105"><label>
              <input name="r" type="radio" value="SI" checked="checked" onclick="limpiarCompos()" />
              Pre-Pagadas</label></td>
            <td width="394"><label>
              <input name="r" type="radio" value="NO" onclick="limpiarCompos()"/>
              Consignaci&oacute;n</label></td>
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
        <td>Vendedor</td>
        <td colspan="4"><span class="Tablas">
          <input name="vendedor" type="text" class="Tablas" id="vendedor" style="width:200px;background:#FFFF99" value="<?=$vendedor ?>" readonly=""/>
        </span></td>
        </tr>
      <tr>
        <td colspan="5" class="FondoTabla">Datos Folio </td>
        </tr>
      <tr>
        <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="10%">F. Inicial: </td>
            <td width="21%">
              <input name="folioi" type="text" class="Tablas" id="folioi" style="width:110px;" value="<?=$folioi ?>" />
            </td>
            <td width="9%">F. Final:</td>
            <td width="21%">
              <input name="foliof" type="text" class="Tablas" id="foliof" style="width:110px;" value="<?=$foliof ?>" />
           </td>
            <td width="19%"><div class="ebtn_aceptar" onclick="ValidarFolio();"></div></td>
            <td width="2%">&nbsp;</td>
            <td width="18%"><div class="ebtn_eliminar" onclick="EliminarFila()"></div></td>
            </tr>
          <tr>
            <td colspan="7"><table width="450" id="tabladetalle" border="0" cellspacing="0" cellpadding="0">
</table>
</td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td width="62">&nbsp;</td>
        <td width="100"><input name="eliminar" type="hidden" id="eliminar" />
          <input name="modificarfila" type="hidden" id="modificarfila" />
          <input name="prepagada" type="hidden" id="prepagada" />
          <input name="accion" type="hidden" id="accion" />
          <input name="registros" type="hidden" id="registros" />
          <input name="oculto" type="hidden" id="oculto" /></td>
        <td width="43">&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="41%"><div class="ebtn_guardar" onclick="validar()"></div></td>
            <td width="59%"><div  class="ebtn_nuevo" onclick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')"></div></td>
          </tr>
        </table></td>
        </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>