<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if(isset($_SESSION['gvalidar'] )!=100){ echo "<script language='javascript' type='text/javascript'>document.location.href='../../../index.php';</script>";
	}else{ */
	include('../../Conectar.php');	
	$link=Conectarse('webpmm');
	$accion=$_POST['accion']; $registros=$_POST['registros'];$usuario=$_SESSION[NOMBREUSUARIO];$codigo=$_POST['codigo'];
	$descripcion=$_POST['descripcion'];$idsucursal=$_POST['idsucursal'];$sucursal=$_POST['sucursal'];
	$idcolonia=$_POST['idcolonia'];
	

if($accion==""){	
	$row=folio('catalogosector','webpmm');
	$codigo=$row[0];
}

if($accion=="grabar"){
	if($registros>0){
	$sql_nuevo	=mysql_query("INSERT INTO catalogosector (id, descripcion,idsucursal,sucursal,usuario, fecha) VALUES(NULL,UCASE('$descripcion'),'$idsucursal',UCASE('$sucursal'),'$usuario',CURRENT_TIMESTAMP())",$link) or die("error en linea ".__LINE__);
	$codigo		=mysql_insert_id();
	//INSERTAR TABLA DETALLE
		for($i=0;$i<$registros;$i++){
			$sqlins=mysql_query("INSERT INTO catalogosectordetalle (id,idsector,cp,idcolonia,colonia,usuario,fecha)
	VALUES(NULL,'$codigo','".$_POST["tabladetalle_CP"][$i]."','".$_POST["tabladetalle_ID"][$i]."','".$_POST["tabladetalle_COLONIA"][$i]."','$usuario',CURRENT_TIMESTAMP())",$link) or die("error en linea ".__LINE__);
	
			$detalle .= "{
				cp:'".$_POST["tabladetalle_CP"][$i]."',
				idcolonia:'".$_POST["tabladetalle_ID"][$i]."',
				colonia:'".$_POST["tabladetalle_COLONIA"][$i]."'},";
		}$detalle = substr($detalle,0,strlen($detalle)-1);
	}
	$mensaje	="Los datos han sido guardados correctamente";
	$accion		="modificar";
}else if($accion == "modificar"){
	if ($registros>0){
	$sql_modificar =mysql_query("UPDATE catalogosector SET descripcion = UCASE('$descripcion') ,idsucursal='$idsucursal',sucursal=UCASE('$sucursal'),usuario = '$usuario' ,fecha = CURRENT_TIMESTAMP()
	WHERE 	id = '$codigo'",$link) or die("error en linea ".__LINE__);
	$sql_limpiar=mysql_query("DELETE  FROM catalogosectordetalle WHERE idsector='$codigo'",$link);
	//MODIFICAR TABLA DETALLE
		for($i=0;$i<$registros;$i++){
			$sqlins=mysql_query("INSERT INTO catalogosectordetalle (id,idsector,cp,idcolonia,colonia,usuario,fecha)
	VALUES(NULL,'$codigo','".$_POST["tabladetalle_CP"][$i]."','".$_POST["tabladetalle_ID"][$i]."','".$_POST["tabladetalle_COLONIA"][$i]."','$usuario',CURRENT_TIMESTAMP())",$link) or die("error en linea ".__LINE__);
			$detalle .= "{
				cp:'".$_POST["tabladetalle_CP"][$i]."',
				idcolonia:'".$_POST["tabladetalle_ID"][$i]."',
				colonia:'".$_POST["tabladetalle_COLONIA"][$i]."'},";			
		}$detalle = substr($detalle,0,strlen($detalle)-1);	
	}
	$mensaje	='Los cambios han sido guardados correctamente';
	$accion		="modificar";
}else if($accion=="limpiar"){
	$codigo			="";
	$descripcion	="";
	$cp				="";
	$colonia		="";
	$idsucursal		="";
	$sucursal		="";
	$accion			="";

	$row=folio('catalogosector','webpmm');
	$codigo=$row[0];
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<link href="../../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />

<script src="../../javascript/shortcut.js"></script>
<script language="javascript" src="../../javascript/ClaseTabla.js"></script>
<script language="JavaScript" src="../../javascript/ajax.js"></script>


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
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
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
var Input = '<input name="colonia" type="text" class="Tablas" id="colonia" style="width:200px;font-size:9px; text-transform:uppercase;background-color:#FFFF99;"/>';
var combo1 = "<select class='Tablas' name='colonia' id='colonia'  style='width:200px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";

//**************************
var u = document.all;
var tabla1 = new ClaseTabla();
	
tabla1.setAttributes({
	nombre:"tabladetalle",
	campos:[
		{nombre:"CP", medida:70, alineacion:"left", datos:"cp"},
		{nombre:"ID", medida:4,tipo:"oculto", alineacion:"center", datos:"idcolonia"},
		{nombre:"COLONIA", medida:300, alineacion:"left", datos:"colonia"}
		
	],
	filasInicial:8,
	alto:100,
	seleccion:true,
	ordenable:true,
	eventoClickFila:"document.all.eliminar.value=tabla1.getSelectedIdRow()",
	eventoDblClickFila:"ModificarFila()",
	nombrevar:"tabla1"
});

	window.onload = function(){
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
	
function EliminarFila(){
	if(document.all.eliminar.value!=""){
		if(tabla1.getValSelFromField("colonia","COLONIA")!=""){
			tabla1.deleteById(document.all.eliminar.value);
		}
		if(tabla1.getRecordCount==0){
			u.d_eliminar.style.visibility = "hidden";
		}
	}else{
		alerta('Seleccione la fila a eliminar','¡Atención!','tabladetalle');
	}
}


function ModificarFila(){
	var obj = tabla1.getSelectedRow();
	if(tabla1.getValSelFromField("cp","CP")!=""){
		document.all.cp.value				=obj.cp;
		document.all.colonia.value			=obj.colonia;
		document.all.modificarfila.value	=tabla1.getSelectedIdRow();
	}
}


function agregarVar(){
	var u= document.all;
	if(u.cp.value==""){
		alerta('Debe capturar Codigo Postal','¡Atención!','cp');
		return false;	
	}else if(u.colonia.value=="" || u.colonia.value==0){
		alerta('Debe capturar una colonia','¡Atención!','colonia');
		return false;
	}else if(u.modificarfila.value!=""){
			tabla1.deleteById(document.all.modificarfila.value);
			u.modificarfila.value="";
	}	
	var registro 	= new Object();
	registro.cp 	= document.getElementById('cp').value;
	if(document.getElementById('colonia').type=="text"){
		registro.idcolonia= document.getElementById('id_colonia').value;
		registro.colonia= document.getElementById('colonia').value;
	}else{
		registro.idcolonia= document.getElementById('colonia').value;	
		registro.colonia= document.getElementById('colonia').options[document.getElementById('colonia').options.selectedIndex].text;
	}
  	var newcp 		= tabla1.getValuesFromField("cp",":");
	var newcolonia 	= tabla1.getValuesFromField("colonia",":");
	if((newcp.indexOf(registro.cp)== -1  && newcolonia.indexOf(registro.colonia)== -1) || (newcp.indexOf(registro.cp)== -1  && newcolonia.indexOf(registro.colonia)!= -1) || (newcp.indexOf(registro.cp)!= -1  && newcolonia.indexOf(registro.colonia)== -1)){
		tabla1.add(registro);
		u.d_eliminar.style.visibility = "visible";
	}else{
		alerta('La colonia ya existe','¡Atención!','cp');
	}
	document.all.celcolonia.innerHTML = Input;
	u.cp.value		="";
	u.colonia.value ="";
	u.id_colonia.value="";
}
//***************************
function Limpiar(){
	document.getElementById('codigo').value		="";
	document.getElementById('descripcion').value="";
	document.getElementById('cp').value			="";
	document.getElementById('colonia').value	="";
	document.getElementById('accion').value 	="limpiar";
	u.d_eliminar.style.visibility = "hidden";
	tabla1.clear();
	document.form1.submit();
}
function validar(){
	var u = document.all;
	u.registros.value = tabla1.getRecordCount();
	if(u.descripcion.value == ""){
			alerta('Debe capturar Descripcion','¡Atención!','descripcion');
			return false;
	}else if(u.idsucursal.value == "" && u.sucursal.value == "" ){
			alerta('Debe capturar Sucursal','¡Atención!','descripcion');
			return false;
	}else if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
			alerta('Debe agregar por lo menos una colonia','¡Atención!','cp');
			return false;			
	}else{
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
			}else if(document.getElementById('accion').value=="modificar"){
				document.form1.submit();
			}
	}
}


function foco(nombrecaja){
	if(nombrecaja=="codigo"){
		document.getElementById('oculto').value="1";
	}else if(nombrecaja=="cp" || nombrecaja=="colonia" ){
		document.getElementById('oculto').value="2";
	}else if(nombrecaja=="idsucursal" || nombrecaja=="sucursal" ){
		document.getElementById('oculto').value="3";
	}
}

shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
		abrirVentanaFija('../../buscadores_generales/buscarSector.php', 550, 450, 'ventana', 'Busqueda');
	}else if(document.form1.oculto.value=="2"){
		abrirVentanaFija('../../buscadores_generales/BuscarColonia.php', 550, 450, 'ventana', 'Busqueda');
	}else if(document.form1.oculto.value=="3"){
		abrirVentanaFija('../../buscadores_generales/buscarsucursal.php', 550, 450, 'ventana', 'Busqueda');
	}
	
});


function OptenerBuscarColonia(cp,idcol,colonia){
	var u = document.all;
	u.cp.value=cp;
	u.id_colonia.value=idcol;
	u.colonia.value=colonia;
}


/********************/
function BuscarCP(cp){
	consulta("mostrarBuscarCP","CatalogoSector_xml.php?cp="+cp+"&accion="+1+"&sid="+Math.random());
}
function mostrarBuscarCP(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		if(con>0){
			if(con>1){
				document.all.celcolonia.innerHTML = combo1;
				var combo = document.all.colonia;
				combo.options.length = null;
				uOpcion = document.createElement("OPTION");
				uOpcion.value=0;
				uOpcion.text="..:: Selecciona ::..";
				combo.add(uOpcion);
				for(i=0;i<con;i++){	
				uOpcion = document.createElement("OPTION");
				uOpcion.value=datos.getElementsByTagName('idcolonia').item(i).firstChild.data;
				uOpcion.text=datos.getElementsByTagName('colonia').item(i).firstChild.data;
				combo.add(uOpcion);
				}
			}else {
					document.all.celcolonia.innerHTML = Input;
					u.id_colonia.value	=datos.getElementsByTagName('idcolonia').item(0).firstChild.data;
					u.colonia.value		=datos.getElementsByTagName('colonia').item(0).firstChild.data;
			}
			u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;
			
			
		}else{
			alerta("No existe",'¡Atención!','cp');
			u.cp.value="";
			u.colonia.value="";
		}

}
/********************/

/********************/
function obtenerSectorBusqueda(sector){
	consulta("mostrarBuscarSector","CatalogoSector_xml.php?sector="+sector+"&accion="+2+"&sid="+Math.random());
}
function mostrarBuscarSector(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		if(con>0){
			u.codigo.value		=datos.getElementsByTagName('codigo').item(0).firstChild.data;
			u.descripcion.value	=datos.getElementsByTagName('descripcion').item(0).firstChild.data;
			u.idsucursal.value	=datos.getElementsByTagName('idsucursal').item(0).firstChild.data;
			u.sucursal.value	=datos.getElementsByTagName('sucursal').item(0).firstChild.data;
			tabla1.setXML(datos);
			u.accion.value="modificar";
			u.d_eliminar.style.visibility = "visible";
		}else{
			alerta("No existe",'¡Atención!','cp');
		}
}
/********************/
function obtenerSucursal(idsucursal){
	consulta("mostrarBuscarSucursal","CatalogoSector_xml.php?accion=3&sucursal="+idsucursal+"&sid="+Math.random());
}
function mostrarBuscarSucursal(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;
		if(con>0){
			u.idsucursal.value	=datos.getElementsByTagName('idsucursal').item(0).firstChild.data;
			u.sucursal.value	=datos.getElementsByTagName('sucursal').item(0).firstChild.data;
		}else{
			alerta("No existe",'¡Atención!','idsucursal');
			u.idsucursal.value	="";
			u.sucursal.value	="";
		}
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
<link href="../cliente/Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="430" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="400" class="FondoTabla Estilo4">CAT&Aacute;LOGO SECTOR</td>
  </tr>
  <tr>
    <td height="98"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="24">Codigo:</td>
        <td><span class="Tablas">
          <input name="codigo" type="text" class="Tablas" id="codigo" style="width:50px;font-size:9px; text-transform:uppercase;" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onKeyPress="if(event.keyCode==13){obtenerSectorBusqueda(this.value,5);}return Numeros(event);" value="<?=$codigo ?>" onKeyDown="" />
        </span></td>
        <td colspan="3"><div class="ebtn_buscar" onClick="abrirVentanaFija('../../buscadores_generales/buscarSector.php', 600, 500, 'ventana', 'Busqueda')"></div></td>
      </tr>
      <tr>
        <td width="86" height="24">Descripcion:</td>
        <td colspan="4"><label><span class="Tablas"> </span></label>
            <label><span class="Tablas">
            <input name="descripcion" type="text" class="Tablas" id="descripcion4" style="width:250px;" value="<?=$descripcion ?>"/>
          </span></label></td>
      </tr>
      <tr>
        <td height="11">Sucursal:</td>
        <td><span class="Tablas">
          <input name="idsucursal" type="text" class="Tablas" id="idsucursal" style="width:50px;" value="<?=$idsucursal ?>" onKeyPress="if(event.keyCode==13){obtenerSucursal(this.value);}" onKeyDown="if(event.keyCode==8){document.all.sucursal.value='';}" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''"/>
        </span></td>
        <td colspan="3" ><span class="Tablas"> </span><span class="Tablas"> <img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../../buscadores_generales/buscarsucursal.php', 600, 500, 'ventana', 'Busqueda')" style="cursor:pointer">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:120px;font-size:9px; text-transform:uppercase;background-color:#FFFF99;" value="<?=$sucursal ?>" readonly=""/>
        </span></td>
      </tr>
      <tr>
        <td height="11">Codigo 
          Postal:</td>
        <td width="64"><label><span class="Tablas">
          <input name="cp" type="text" class="Tablas" id="cp" style="width:50px;font-size:9px; text-transform:uppercase" onKeyPress="if(event.keyCode==13){BuscarCP(this.value)}" onKeyDown="if(event.keyCode==8){document.all.colonia.value='';document.all.celcolonia.innerHTML = Input;}" value="<?=$cp?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''"/>
          </span> </label>        </td>
        <td width="208" id="celcolonia"><span class="Tablas">
          <input name="colonia" type="text" class="Tablas" id="colonia" style="width:200px;font-size:9px; text-transform:uppercase;background-color:#FFFF99;" value="<?=$colonia?>"/>
        </span></td>
        <td width="51"><div class="ebtn_buscar" onClick="abrirVentanaFija('../../buscadores_generales/BuscarColonia.php', 550, 450, 'ventana', 'Busqueda')"></div></td>
        <td width="17"></td>
      </tr>
      <tr>
        <td height="11" colspan="5" align="center" ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center">
			  <table id="tabladetalle" width="420" border="0" cellspacing="0" cellpadding="0"> 
				</table>

                 </td>
              </tr>
          </table>
            <table width="177" height="0" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="83" height="29"><div style="visibility:hidden" id="d_eliminar" class="ebtn_eliminar" onClick="EliminarFila()"></div></td>
                <td width="82"><div class="ebtn_agregar" onClick="agregarVar();"> </div></td>
              </tr>
              
            </table></td>
      </tr>
      <tr>
        <td height="11" colspan="5"><input name="accion" type="hidden" id="accion" value="<?=$accion?>">
            <input name="oculto" type="hidden" id="oculto">
            <input name="registros" type="hidden" id="registros">
            <input name="eliminar" type="hidden" id="eliminar">
            <input name="modificarfila" type="hidden" id="modificarfila">
            <input name="id_colonia" type="hidden" id="id_colonia">
            <table width="44%" border="0" align="right">
              <tr>
                <td width="44%"><div class="ebtn_guardar" onClick="validar();"></div></td>
                <td width="56%"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'Limpiar();', '')"></div></td>
              </tr>
          </table></td>
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