<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	include('../Conectar.php');	
	$link=Conectarse('webpmm');
	$folio=$_POST['folio'];
	$foliobitacora=$_POST['foliobitacora'];
	$unidad=$_POST['unidad'];
	$gastos=$_POST['gastos'];
	$conductor=$_POST['conductor'];
	$accion=$_POST['accion'];
	$usuario=$_SESSION[NOMBREUSUARIO];
	$registros=$_POST['registros']; 
	$fecha = $_POST['fecha'];
	if($fecha == ""){
		$fecha = date("d/m/Y");
	}else{
		$fecha = cambiaf_a_mysql($fecha);
	}
	
	if($accion==""){
		$s = "SELECT obtenerFolio('liquidaciongastos',".$_SESSION[IDSUCURSAL].") AS folio";
		$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);		
		$folio = $fo->folio;
	}
if($accion=="grabar"){
	$sql_nuevo = mysql_query("INSERT INTO liquidaciongastos 
	(folio,fechai,foliobitacora,unidad,gastos,conductor,usuario,fecha)
	VALUES 
	(obtenerFolio('liquidaciongastos',".$_SESSION[IDSUCURSAL]."), '$fecha','$foliobitacora',
	UCASE('$unidad'),'$gastos',UCASE('$conductor'),'$usuario',CURRENT_TIMESTAMP())",$link) or die("error en linea ".__LINE__);
	$folio = mysql_insert_id();
	
	$s = "SELECT folio FROM liquidaciongastos WHERE id = ".$folio;
	$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);
	
	if ($registros>0){
	//INSERTAR TABLA DETALLE
		for($i=0;$i<$registros;$i++){
			$sqlins=mysql_query("INSERT INTO liquidaciongastosdetalle(folioliquidacion,folioconcepto,concepto,cantidad,usuario,fecha,sucursal)
			VALUES('$fo->folio',
				   '".$_POST["tabladetalle_Id"][$i]."',
				   '".$_POST["tabladetalle_Concepto"][$i]."',
				   '".str_replace("$ ","",$_POST["tabladetalle_Cantidad"][$i])."',
			'$usuario',CURRENT_TIMESTAMP(),".$_SESSION[IDSUCURSAL].")",$link) or die("error en linea ".__LINE__);
			//Cadena Detalle
			$detalle .= "{
				id:'".$_POST["tabladetalle_Id"][$i]."',
				concepto:'".$_POST["tabladetalle_Concepto"][$i]."',
				cantidad:'".str_replace("$ ","",$_POST["tabladetalle_Cantidad"][$i])."'},";
		}
		$detalle = substr($detalle,0,strlen($detalle)-1);
	}
	$mensaje	= "Los datos han sido guardados correctamente";
	$accion		= "modificar";
	$fecha 		= cambiaf_a_normal($fecha);
}else if($accion == "modificar"){
	$sql_modificar	=mysql_query("UPDATE liquidaciongastos SET 
	foliobitacora ='$foliobitacora',unidad='$unidad',gastos ='$gastos', conductor=UCASE('$conductor'),
	usuario ='$usuario', fecha = CURRENT_TIMESTAMP() WHERE folio ='$folio' AND sucursal= ".$_SESSION[IDSUCURSAL]."",$link) or die("error en linea ".__LINE__);
	//Limpiar detalle
	$sql_eliminar=mysql_query("DELETE FROM liquidaciongastosdetalle 
	WHERE folio='$folio' AND sucursal = ".$_SESSION[IDSUCURSAL]."",$link);
	if ($registros>0){
	//INSERTAR TABLA DETALLE
		for($i=0;$i<$registros;$i++){
			$sqlins=mysql_query("INSERT INTO 
			liquidaciongastosdetalle(folio,folioconcepto,concepto,cantidad,usuario,fecha,sucursal)
			VALUES('$folio',
				   '".$_POST["tabladetalle_Id"][$i]."',
				   '".$_POST["tabladetalle_Concepto"][$i]."',
				   '".str_replace("$ ","",$_POST["tabladetalle_Cantidad"][$i])."',
			'$usuario',CURRENT_TIMESTAMP(),".$_SESSION[IDSUCURSAL].")",$link) or die("error en linea ".__LINE__);
			$detalle .= "{
			id:'".$_POST["tabladetalle_Id"][$i]."',	
			concepto:'".$_POST["tabladetalle_Concepto"][$i]."',
			cantidad:'".str_replace("$ ","",$_POST["tabladetalle_Cantidad"][$i])."'},";
			
		}
		$detalle = substr($detalle,0,strlen($detalle)-1);
	}
	$mensaje	='Los cambios han sido guardados correctamente';
	$fecha =cambiaf_a_normal($fecha);
	$accion		="modificar";
}else if($accion == "limpiar"){
	$folio			="";
	$foliobitacora	="";
	$unidad			="";
	$gastos			="";
	$conductor		="";
	$concepto		="";
	$cantidad		="";
	$registros		="";
	$accion 		="";
	$fecha = $_POST['fecha'];
	if($fecha == ""){
		$fecha = date("d/m/Y");
	}
	
	$s = "SELECT obtenerFolio('liquidaciongastos',".$_SESSION[IDSUCURSAL].") AS folio";
	$r = mysql_query($s,$l) or die($s); $fo = mysql_fetch_object($r);		
	$folio = $fo->folio;
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
<script language="javascript" src="../javascript/ClaseTabla.js"></script>
<script language="JavaScript" src="../javascript/ajax.js"></script>
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

var tabla1 = new ClaseTabla();
	
tabla1.setAttributes({
	nombre:"tabladetalle",
	campos:[
		{nombre:"Id", medida:4, alineacion:"center", tipo:"oculto", datos:"idconcepto"},			
		{nombre:"Concepto", medida:170, alineacion:"center", datos:"concepto"},
		{nombre:"Cantidad", medida:170,tipo:"moneda", alineacion:"center", datos:"cantidad"}
	],
	filasInicial:8,
	alto:100,
	seleccion:true,
	ordenable:true,
	eventoClickFila:"document.all.eliminar.value=tabla1.getSelectedIdRow();",
	eventoDblClickFila:"ModificarFila();",
	nombrevar:"tabla1"
});
	window.onload = function(){
	tabla1.create();	
	obtenerDetalles();
	}
	function obtenerDetalles(){
	var datosTablaDireccion = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTablaDireccion!=0){			
			for(var i=0; i<datosTablaDireccion.length;i++){
				tabla1.add(datosTablaDireccion[i]);
			}
		}
	}
	
	
function agregarVar(){
	var u= document.all;
	if(u.concepto.value == ""){
		alerta('Capture  Concepto', '¡Atención!','concepto');
		return false;
	}else if(u.cantidad.value == ""){
		alerta('Capture  Cantidad', '¡Atención!','cantidad');
		return false;
	}else if(u.modificarfila.value!=""){
		tabla1.deleteById(u.modificarfila.value);
		u.modificarfila.value="";
	}
	var registro = new Object();
	registro.idconcepto = document.getElementById('concepto').value;
	registro.concepto = u.concepto.options[u.concepto.options.selectedIndex].text;
	registro.cantidad = document.getElementById('cantidad').value;
	var newcolonia = tabla1.getValuesFromField("concepto",":");
	if(newcolonia.indexOf(registro.concepto)== '-1'){
		tabla1.add(registro);
	}else{
		alerta('El concepto ya existe','¡Atención!','concepto');
	}
	u.concepto.value = "";
	u.cantidad.value = "";
}
	
function EliminarFila(){
	if(document.all.eliminar.value!=""){
		if(tabla1.getValSelFromField("concepto","Concepto")!=""){
			tabla1.deleteById(document.all.eliminar.value);
		}
	}else{
		alerta('Seleccione una fila a eliminar','¡Atención!','tabladetalle');
	}
}
function ModificarFila(){
	var obj = tabla1.getSelectedRow();
	if(tabla1.getValSelFromField("concepto","Concepto")!=""){
		document.all.concepto.value			=obj.concepto;
		document.all.cantidad.value			=obj.cantidad;
		document.all.modificarfila.value	=tabla1.getSelectedIdRow();
	}
}
//***************************
function Limpiar(){
	document.getElementById('fecha').value		="";
	document.getElementById('folio').value		="";
	document.getElementById('foliobitacora').value="";
	document.getElementById('unidad').value		="";
	document.getElementById('gastos').value		="";
	document.getElementById('conductor').value	="";
	document.getElementById('concepto').value	="";
	document.getElementById('cantidad').value	="";
	document.getElementById('accion').value 	= "limpiar";
	document.form1.submit();
}
function validar(){
	var u = document.all;
	u.registros.value = tabla1.getRecordCount();
	if(u.foliobitacora.value==""){
			alerta('Debe capturar Folio Bitacora','¡Atención!','foliobitacora');
			return false;
	}else if(u.unidad.value==""){
			alerta('Debe capturar unidad','¡Atención!','unidad');
			return false;			
	}else if(u.gastos.value==""){
			alerta('Debe capturar gastos','¡Atención!','gastos');
			return false;			
	}else if(u.conductor.value==""){
			alerta('Debe capturar conductor','¡Atención!','conductor');
			return false;			
	}else if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
			alerta('Debe capturar Concepto y cantidad','¡Atención!','conductor');
			return false;			
	}else if(validarCantidad() != u.gastos.value){
			alerta('El total de las Cantidades no es igual al Gasto registrado','¡Atención!','cantidad');			
			return false;
	}else{
			if(u.accion.value==""){
				u.accion.value = "grabar";
				document.form1.submit();
			}else if(u.accion.value=="modificar"){
				document.form1.submit();
			}
	}
}
function foco(nombrecaja){
	if(nombrecaja=="foliobitacora"){
		document.getElementById('oculto').value="1";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
abrirVentanaFija('prestamossucursal_buscar.php?tipo=1', 550, 450, 'ventana', 'Busqueda')}
});
function obtener(folio){
	BuscarBitacora(folio,5);
}
/********************/
function BuscarBitacora(folio,tipo){
	consulta("mostrarBuscarBitacora","consultaCORM.php?folio="+folio+"&accion="+tipo+"&sid="+Math.random());
}
function mostrarBuscarBitacora(datos){
		var u = document.all;
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){	
			u.foliobitacora.value=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
			u.unidad.value=datos.getElementsByTagName('unidad').item(0).firstChild.data;
			u.gastos.value=datos.getElementsByTagName('gastos').item(0).firstChild.data;
			u.conductor.value=datos.getElementsByTagName('conductor').item(0).firstChild.data;
		}else{
			alerta("No existe",'¡Atención!','foliobitacora');
		}
}
/********************/
/*************************/
function obtenerLiquidacion(folio){
	consulta("mostrarBuscarLiquidacion","consultaCORM.php?folio="+folio+"&accion="+7+"&sid="+Math.random());
}
function mostrarBuscarLiquidacion(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		if(con>0){
			u.fecha.value=datos.getElementsByTagName('fecha').item(0).firstChild.data;
			u.folio.value=datos.getElementsByTagName('folio').item(0).firstChild.data;	
			u.foliobitacora.value=datos.getElementsByTagName('foliobitacora').item(0).firstChild.data;
			u.unidad.value=datos.getElementsByTagName('unidad').item(0).firstChild.data;
			u.gastos.value=datos.getElementsByTagName('gastos').item(0).firstChild.data;
			u.conductor.value=datos.getElementsByTagName('conductor').item(0).firstChild.data;
			//tabla detalle
			tabla1.setXML(datos);
			u.accion.value="modificar";
		}else{
			alerta("No existe el registro liquidación gastos",'¡Atención!','foliobitacora');
		}
}
//**********************/
function validarCantidad(){
	var total = 0;
	var sumcantidad = tabla1.getValuesFromField("cantidad",":");
	var s=sumcantidad.split(":");
	for(i=0;i<s.length;i++){
		total=parseFloat(s[i])+parseFloat(total);
	}
	return total;
}
var nav4 = window.Event ? true : false;
function Numeros(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57));
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
            if (frm.elements[i+1].disabled ==true )    
                tabular(e,frm.elements[i+1]);
            else if (frm.elements[i+1].readOnly ==true )    
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus();
            return false;
} 
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="466" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="462" class="FondoTabla Estilo4">DATOS GENERALES</td>
  </tr>
  <tr>
    <td height="98"><div align="center">
      <table width="461" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="center"></div></td>
        </tr>
        <tr>
          <td><table width="462" border="0" cellpadding="0" cellspacing="0">
                  <tr> 
                    <td width="438"><div align="right"> Fecha<span class="Tablas"> 
                        <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
                        </span>Folio<span class="Tablas"> 
                        <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" maxlength="0"  readonly=""/>
                        </span></div></td>
                    <td width="24"><div class="ebtn_buscar" onClick="abrirVentanaFija('liquidaciondegastos_buscar.php', 550, 450, 'ventana', 'Busqueda')"></div></td>
                  </tr>
                </table></td>
        </tr>
        
        
        
        <tr>
          <td><table width="461" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="70">Folio Bitacora </td>
              <td width="100"><span class="Tablas">
                <input name="foliobitacora" type="text" class="Tablas" id="foliobitacora" style="width:100px;" value="<?=$foliobitacora ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onKeyPress="if(event.keyCode==13){BuscarBitacora(this.value,5)};return Numeros(event);" onKeyDown="if(event.keyCode==8){document.all.unidad.value='';document.all.gastos.value='';document.all.conductor.value='';};"/>
              </span></td>
              <td width="24"><div class="ebtn_buscar" onClick="abrirVentanaFija('buscarBitacora.php', 550, 450, 'ventana', 'Busqueda')"></div></td>
              <td width="34"><label>Unidad</label></td>
              <td width="100"><span class="Tablas">
                <input name="unidad" type="text" class="Tablas" id="unidad" style="width:100px;background:#FFFF99" value="<?=$unidad ?>" readonly=""/>
              </span></td>
              <td width="33">Gastos</td>
              <td width="100"><span class="Tablas">
                <input name="gastos" type="text" class="Tablas" id="gastos" style="width:100px;background:#FFFF99" value="<?=$gastos?>" readonly=""/>
              </span></td>
            </tr>
          </table></td>
        </tr>
        
        <tr>
          <td width="461"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr> 
                    <td width="75" height="24">Conductor</td>
                    <td colspan="4"><label><span class="Tablas"> 
                      <input name="conductor" type="text" class="Tablas" id="conductor" style="width:311px;background:#FFFF99" value="<?=$conductor ?>"  readonly=""/>
                      </span></label></td>
                  </tr>
                  <tr> 
                    <td height="11">Concepto</td>
                    <td width="181"><label> 
                      <select name="concepto" id="concepto" style="width:145px;" onKeyPress='return tabular(event,this)'>
                        <option value=""></option>
                        <?
						$sql_concepto=mysql_query("SELECT id,descripcion FROM catalogoconcepto",$link);
						while($row=mysql_fetch_array($sql_concepto)){
						?>
                        <option value="<?=$row['id']?>"> 
                        <?=$row['descripcion']?>
                        </option>
                        <? } ?>
                      </select>
                      </label></td>
                    <td width="47"> Cantidad<span class="Tablas"> </span></td>
                    <td width="64"><span class="Tablas">
                      <input name="cantidad" type="text" class="Tablas" id="cantidad" style="width:100px;" value="<?=$cantidad ?>" onKeyPress="if(event.keyCode==13){agregarVar()}"/>
                      </span></td>
                    <td align="center"><div class="ebtn_agregar" onClick="agregarVar();"></div></td>
                  </tr>
                  <tr> 
                    <td height="11" colspan="4" align="center"><br>
                      <table cellpadding="0" cellspacing="0" id="tabladetalle">
                      </table></td>
                    <td width="100" align="center"><table width="52%" border="0" align="left">
                        <tr> 
                          <td></td>
                        </tr>
                        <tr> 
                          <td><div class="ebtn_eliminar" onClick="EliminarFila();"></div></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr> 
                    <td height="11"><input name="accion" type="hidden" id="accion" value="<?=$accion?>"> 
                      <input name="oculto" type="hidden" id="oculto" value="<?=$accion ?>"> 
                      <input name="registros" type="hidden" id="registros">
                      <input name="eliminar" type="hidden" id="eliminar">
                      <input name="modificarfila" type="hidden" id="modificarfila"></td>
                    <td colspan="4"> <table width="34%" border="0" align="right">
                        <tr> 
                          <td><div class="ebtn_guardar" onClick="validar();"></div></td>
                          <td><div class="ebtn_nuevo" onClick="Limpiar();"></div></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
          </tr>
      </table>
    <div align="center"></div></div></td>
  </tr>
</table>
</form>
</body>
</html>
<?
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
?>