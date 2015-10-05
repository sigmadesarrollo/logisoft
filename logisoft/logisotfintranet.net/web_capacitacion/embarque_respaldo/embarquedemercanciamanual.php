<? 	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');

	$fecha = date('d/m/Y');
	//Sucursal
	$suc = @mysql_query("SELECT descripcion,prefijo FROM catalogosucursal WHERE id=".$_SESSION[IDSUCURSAL]."",$link); 
		$rsuc = @mysql_fetch_array($suc); 
		$sucursal = $rsuc[0];
		$prefijo  =	$rsuc['prefijo'];
	//Folio
	$sql=mysql_query("SELECT CONCAT('$prefijo','-',LPAD((SELECT MAX(folio)+1 FROM embarquedemercancia),5,'0'))",$link);
		$row=mysql_fetch_array($sql);
		$folio = $row[0];
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
	var u 	   = 	 document.all;
	var tabla1 = new ClaseTabla();	
	var tabla2 = new ClaseTabla();
	var tabla3 = new ClaseTabla();
	var tabla4 = new ClaseTabla();
	
	tabla1.setAttributes({
	nombre:"tabladetalleizq",
	campos:[
			{nombre:"SECTOR", medida:50, alineacion:"center", datos:"sector"},
			{nombre:"No_GUIA", medida:60, alineacion:"center", datos:"guia"},
			{nombre:"ORIGEN", medida:70, alineacion:"center", datos:"origen"},
			{nombre:"CODIGO_DE_BARRAS", medida:90, alineacion:"center", datos:"codigobarra"}
		],
		filasInicial:14,
		alto:200,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"ObtDetalleIzq()",
		nombrevar:"tabla1"
	});
	tabla2.setAttributes({
	nombre:"tabladetalleder",
	campos:[
			{nombre:"SECTOR", medida:50, alineacion:"center", datos:"sector"},
			{nombre:"No_GUIA", medida:60, alineacion:"center", datos:"guia"},
			{nombre:"ORIGEN", medida:70, alineacion:"center", datos:"origen"},
			{nombre:"CODIGO_DE_BARRAS", medida:90, alineacion:"center", datos:"codigobarra"}
		],
		filasInicial:14,
		alto:200,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"ObtDetalleDer()",
		nombrevar:"tabla2"
	});
	
	tabla3.setAttributes({
	nombre:"tabladetalleizqabajo",
	campos:[
			{nombre:"REGISTRO", medida:70, alineacion:"center", datos:"registro"},
			{nombre:"PAQUETE", medida:40, alineacion:"center", datos:"paquete"},
			{nombre:"CODIGO_DE_BARRAS", medida:70, alineacion:"center", datos:"codigobarra"},
			{nombre:"ESTADO", medida:90, alineacion:"center", datos:"estado"},
			{nombre:"GUIA", medida:4, tipo:"oculto", alineacion:"center", datos:"guia"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"limpiarSelIzq()",
		nombrevar:"tabla3"
	});
	tabla4.setAttributes({
	nombre:"tabladetallederabajo",
	campos:[
			{nombre:"REGISTRO", medida:70, alineacion:"center", datos:"registro"},
			{nombre:"PAQUETE", medida:40, alineacion:"center", datos:"paquete"},
			{nombre:"CODIGO_DE_BARRAS", medida:70, alineacion:"center", datos:"codigobarra"},
			{nombre:"ESTADO", medida:90, alineacion:"center", datos:"estado"},
			{nombre:"GUIA", medida:4, tipo:"oculto", alineacion:"center", datos:"guia"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"limpiarSelDer()",
		nombrevar:"tabla4"
	});
	
	window.onload = function(){
		//u.unidad.focus();
		tabla1.create();
		tabla2.create();
		tabla3.create();
		tabla4.create();
		obtenerDetalles();
	}
	
	function obtenerDetalles(){
		var datosTabla1 = <? if($detalle1!=""){echo "[".$detalle1."]";}else{echo "0";} ?>;
			if(datosTabla1!=0){			
				for(var i=0; i<datosTabla1.length;i++){
					tabla1.add(datosTabla1[i]);
				}
			}
		var datosTabla2 = <? if($detalle2!=""){echo "[".$detalle2."]";}else{echo "0";} ?>;
			if(datosTabla2!=0){			
				for(var i=0; i<datosTabla2.length;i++){
					tabla2.add(datosTabla2[i]);
				}
			}
	
	}
	
	function moverAlaDerecha(){
		if(tabla1.getRecordCount()==""){
			alerta3('No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			if(tabla1.getSelectedIdRow()!=""){
				consultaTexto("resMovADerC","embarquedemercancia_con.php?accion=4&folio="+tabla1.getSelectedRow().guia);
			}else if(tabla3.getSelectedIdRow()!=""){
				consultaTexto("resMovADerU","embarquedemercancia_con.php?accion=5&folio="+tabla3.getSelectedRow().guia
					+"&registro="+tabla3.getSelectedRow().registro+"&random="+Math.random());
			}else{
				alerta3('Debe seleccionar la fila que desea mover a la derecha','¡Atención!');	
			}
		}
	}
	function resMovADerC(datos){
		if(datos.indexOf("guardado")>-1){
			var folios = tabla2.getValuesFromField("guia");
			var folio  = tabla1.getValSelFromField("guia","No_GUIA");
			if(folios.indexOf(folio)<0)
				tabla2.add(tabla1.getSelectedRow());
			tabla1.deleteById(tabla1.getSelectedIdRow());
			tabla3.clear();
			buscarEnDer(folio);
		}
	}
	function resMovADerU(datos){
		if(datos.indexOf("guardado")>-1){
			var folio = tabla3.getValSelFromField("guia","GUIA");
			var folios = tabla2.getValuesFromField("guia");
			if(folios.indexOf(folio)<0){
				folios = tabla1.getValuesFromField("guia");
				var folioarre = folios.split(",");
				for(var i=0; i<tabla1.getRecordCount();i++){
					if(folioarre[i]==folio){
						tabla1.setSelectedById("tabladetalleizq_id"+i);
						tabla2.add(tabla1.getSelectedRow());
					}
				}
			}
			if(tabla3.getRecordCount()==1){
				folios = tabla1.getValuesFromField("guia");
				var folioarre = folios.split(",");
				for(var i=0; i<tabla1.getRecordCount();i++){
					if(folioarre[i]==folio){
						tabla1.setSelectedById("tabladetalleizq_id"+i);
						tabla1.deleteById(tabla1.getSelectedIdRow());
					}
				}
			}
			tabla4.add(tabla3.getSelectedRow());
			tabla3.deleteById(tabla3.getSelectedIdRow());
			
			buscarEnDer(folio);
			
		}
	}
	
	function moverAlaizquierda(){
		if(tabla2.getRecordCount()==""){
			alerta3('No existen Guias en el apartado izquierdo','¡Atención!');
		}else{
			if(tabla2.getSelectedIdRow()!=""){
				consultaTexto("resMovAIzqC","embarquedemercancia_con.php?accion=6&folio="+tabla2.getSelectedRow().guia);
			}else if(tabla4.getSelectedIdRow()!=""){
				consultaTexto("resMovAIzqU","embarquedemercancia_con.php?accion=7&folio="+tabla4.getSelectedRow().guia
					+"&registro="+tabla4.getSelectedRow().registro+"&random="+Math.random());
			}else{
				alerta3('Debe seleccionar la fila que desea mover a la derecha','¡Atención!');	
			}
		}
	}
	function resMovAIzqC(datos){
		if(datos.indexOf("guardado")>-1){
			var folios = tabla1.getValuesFromField("guia");
			var folio  = tabla2.getValSelFromField("guia","No_GUIA");
			if(folios.indexOf(folio)<0)
				tabla1.add(tabla2.getSelectedRow());
			tabla2.deleteById(tabla2.getSelectedIdRow());
			tabla4.clear();
			buscarEnIzq(folio);
		}
	}
	function resMovAIzqU(datos){
		
		if(datos.indexOf("guardado")>-1){
			
			var folio = tabla4.getValSelFromField("guia","GUIA");
			var folios = tabla1.getValuesFromField("guia");
			if(folios.indexOf(folio)<0){
				folios = tabla2.getValuesFromField("guia");
				var folioarre = folios.split(",");
				for(var i=0; i<tabla2.getRecordCount();i++){
					if(folioarre[i]==folio){
						tabla2.setSelectedById("tabladetalleder_id"+i);
						tabla1.add(tabla2.getSelectedRow());
					}
				}
			}
			if(tabla4.getRecordCount()==1){
				folios = tabla2.getValuesFromField("guia");
				var folioarre = folios.split(",");
				for(var i=0; i<tabla2.getRecordCount();i++){
					if(folioarre[i]==folio){
						tabla2.setSelectedById("tabladetalleder_id"+i);
						tabla2.deleteById(tabla2.getSelectedIdRow());
					}
				}
			}
			tabla3.add(tabla4.getSelectedRow());
			tabla4.deleteById(tabla4.getSelectedIdRow());
			
			buscarEnIzq(folio);
		}
	}
	

/*******************/
	function ObtDetalleIzq(){
		tabla3.setSelectedById("");
		if(tabla1.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarIzqAbajo","embarquedemercancia_con.php?accion=2&folio="+tabla1.getValSelFromField('guia','No_GUIA'));
		}
	}
	function mostrarIzqAbajo(datos){
			var objeto = eval(datos);
			tabla3.setJsonData(objeto);
	}
/****************/
	function ObtDetalleDer(){
		tabla4.setSelectedById("");
		if(tabla2.getValSelFromField('guia','No_GUIA')!=""){
			consultaTexto("mostrarDerAbajo","embarquedemercancia_con.php?accion=3&folio="+tabla2.getValSelFromField('guia','No_GUIA'));
		}
	}
	function mostrarDerAbajo(datos){
			var objeto = eval(datos);
			tabla4.setJsonData(objeto);
	}
/******* Funcion limpiar seleccion detalles **********/
	function limpiarSelIzq(){
		tabla1.setSelectedById("");
	}
	function limpiarSelDer(){
		tabla2.setSelectedById("");
	}

/********************/
	function obtenerUnidadBusqueda(unidad){
		consulta("mostrarUnidadBusqueda","consultasembarque.php?unidad="+unidad+"&accion="+1+"&sid="+Math.random());
	}
	function mostrarUnidadBusqueda(datos){
			var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
			var u = document.all;
			if(con>0){
				u.unidad.value		=datos.getElementsByTagName('unidad').item(0).firstChild.data;
				u.ruta.value		=datos.getElementsByTagName('ruta').item(0).firstChild.data;
				u.recorrido.value	=datos.getElementsByTagName('descripcion').item(0).firstChild.data;
				//tabla1.setXML(datos);
				
				CargarGuias();
				tabla2.clear();
				tabla3.clear();
				tabla4.clear();
			}else{
				alerta3("La unidad seleccionada no ha sido registrada en una Bitácora de Salida",'¡Atención!');
					u.unidad.value	="";
					u.ruta.value	="";
					u.recorrido.value="";
					tabla1.clear();
					tabla2.clear();
					tabla3.clear();
					tabla4.clear();
			}
	}
/********************/
	function CargarGuias(){
		consultaTexto("mostrarCargarGuias","embarquedemercancia_con.php?accion=1&unidad="+document.all.unidad.value+"&sid="+Math.random());
	}
	function mostrarCargarGuias(datos){
		var objeto = eval(convertirValoresJson(datos));
		tabla1.setJsonData(objeto.izquierda);
		tabla2.setJsonData(objeto.derecha);
	}
/********************/
	function BuscarGuia(valor){
		var idguia =tabla1.getValuesFromField('guia',':');
		idguia = idguia.split(":");
		for(i=0;i<idguia.length;i++){
			if(valor==idguia[i]){
					tabla1.setSelectedById("tabladetalleizq_id"+i);
					break;
			}
		}
	}

/*****/
	function buscarEnDer(folio){
		var campos = tabla2.getValuesFromField("guia");
		camposarreglo = campos.split(",");
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(camposarreglo[i] == folio){
				tabla2.setSelectedById("tabladetalleder_id"+i);
				ObtDetalleDer();
				return true;
			}
		}
	}
	function buscarEnIzq(folio){
		var campos = tabla1.getValuesFromField("guia");
		camposarreglo = campos.split(",");
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(camposarreglo[i] == folio){
				tabla1.setSelectedById("tabladetalleizq_id"+i);
				ObtDetalleIzq();
				return true;
			}
		}
	}
/********************/
	function foco(nombrecaja){
		if(nombrecaja=="unidad"){
			u.oculto.value="1";
		}
	}
	shortcut.add("Ctrl+b",function() {
		if(u.oculto.value=="1"){
		abrirVentanaFija('../buscadores_generales/buscarUnidadGen.php?funcion=obtenerUnidadBusqueda', 550, 450, 'ventana', 'Busqueda');
		}
	});

	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57));
	}
	function validar(){
		document.all.registros.value = tabla1.getRecordCount();
		document.all.accion.value="grabar";
		document.form1.submit();
	}
	
	function limpiarDatos(){
		var u = document.all;
		u.unidad.value	="";
		u.ruta.value	="";
		u.recorrido.value="";
		tabla1.clear();
		tabla2.clear();
		tabla3.clear();
		tabla4.clear();
		u.guia.value	="";
		u.guia.diseable	=true;
		document.form1.submit();
	}
	
	function buscarFolio(){
		if(u.buscar.value==0){
			var campo = "guia";
		}else{
			var campo = "codigobarra";
		}
		if(tabla1.getSelectedIdRow()!=""){
			var campos = tabla1.getValuesFromField(campo);
			camposarreglo = campos.split(",");
			for(var i=0; i<tabla1.getRecordCount(); i++){
				if(camposarreglo[i] == u.guia.value){
					tabla1.setSelectedById("tabladetalleizq_id"+i);
					obtenerGuiaIzq();
					return true;
				}
			}
		}
		if(tabla2.getSelectedIdRow()!=""){
			var campos = tabla2.getValuesFromField(campo);
			camposarreglo = campos.split(",");
			for(var i=0; i<tabla2.getRecordCount(); i++){
				if(camposarreglo[i] == u.guia.value){
					tabla2.setSelectedById("tabladetalleder_id"+i);
					obtenerGuiaDer();
					return true;
				}
			}
		}
		alerta3("No se encontro "+((u.buscar.value==0)?"la guia buscada":"el codigo buscado"));
	}

	function guardarValores(){
		if(u.ruta.value==""){
			alerta("Por favor proporcione unidad","¡Atencion!","unidad");
			return false;
		}
		if(tabla2.getRecordCount()>0 && u.guardado.value == 0){
			consultaTexto("resValidar","embarquedemercancia_con.php?accion=8&mathrand="+Math.random());
		}else{
			alerta3("No hay ningun registro a guardar");
			return false;
		}
	}
	function resValidar(datos){
		if(datos.indexOf("ok")>-1){
			guardarFinal();			
		}else{
			var objeto = eval(datos);
			var mensaje = "";
			for(var i=0; i<objeto.length; i++){
				mensaje += objeto[i].guia+",";
			}
			confirmar("Existen guias incompletas:<br>"+mensaje+"<br>¿Desea continuar?","¡Atencion!","mostrarLogueo()");
		}
	}
	function mostrarLogueo(){
		abrirVentanaFija("../buscadores_generales/logueo_permisos.php?funcion=guardarFinal&modulo=GuiaVentanilla&usuario=Admin",500,400,"ventana","DATOS PERSONALES")	
	}
	
	function guardarFinal(){
		var unidad		= u.unidad.value;
		var ruta		= u.ruta.value;
		var recorrido	= u.recorrido.value;
		//alerta3("embarquedemercancia_con.php?accion=9&unidad="+unidad+"&recorrido="+recorrido
			//	+"&ruta="+ruta+"&folios="+tabla2.getValuesFromField('guia')+"&mathrand="+Math.random(),"");
					consultaTexto("resGuardar","embarquedemercancia_con.php?accion=9&unidad="+unidad
					 +"&ruta="+ruta+"&tipoembarque=MANUAL&folios="+tabla2.getValuesFromField('guia')+"&mathrand="+Math.random());
	}
	function resGuardar(datos){
		if(datos.indexOf("guardado")>-1){
			info("La informacion ha sido guardada","");
			u.guardado.value = 1;
		}else{
			alerta3("Hubo un error "+datos,"¡Atencion!");
		}
	}
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">EMBARQUE DE MERCANC&Iacute;A MANUAL</td>
  </tr>
  <tr>
    <td><table width="618" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><table width="618" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="171"></td>
            <td width="42">Folio</td>
            <td width="106"><span class="Tablas">
              <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
            </span></td>
            <td width="31">Fecha</td>
            <td width="106"><span class="Tablas">
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
            </span></td>
            <td width="44">Sucursal</td>
            <td width="118"><span class="Tablas">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
        <table width="618" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="35">Unidad</td>
            <td width="100"><span class="Tablas">
              <input name="unidad" type="text" class="Tablas" id="unidad" style="width:100px" value="<?=$unidad ?>" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onkeydown="if(event.keyCode==8){document.all.ruta.value='';document.all.recorrido.value='';}"  onkeypress="if(event.keyCode==13){obtenerUnidadBusqueda(this.value)}" />
            </span></td>
            <td width="483"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarUnidadGen.php?funcion=obtenerUnidadBusqueda&validarconbitacora=1', 550, 450, 'ventana', 'Busqueda');"></div><input type="hidden" name="guardado" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="618" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="34">Ruta</td>
            <td width="100"><span class="Tablas">
              <input name="ruta" type="text" class="Tablas" id="ruta" style="width:100px;background:#FFFF99" value="<?=$ruta ?>" readonly=""/>
            </span></td>
            <td width="49">Recorrido</td>
            <td width="407"><span class="Tablas">
              <input name="recorrido" type="text" class="Tablas" id="recorrido" style="width:100px;background:#FFFF99" value="<?=$recorrido ?>" readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="618" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td></td>
              <td align="center">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
                  <td width="298" height="66"><table border="0" cellpadding="0" cellspacing="0" id="tabladetalleizq" >
                            </table></td>
              <td width="24" align="center"><div class="ebtn_adelante" onclick="moverAlaDerecha()"></div><BR /><BR /><div class="ebtn_atraz" onclick="moverAlaizquierda()"></div></td>
                  <td width="296">
                    <table border="0" cellpadding="0" cellspacing="0"  id="tabladetalleder">
                    </table>
                  </td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="618" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
                  <td width="298"><table border="0" cellpadding="0" cellspacing="0" id="tabladetalleizqabajo">
                  </table></td>
            <td width="24">&nbsp;</td>      <td width="296"><table border="0" cellpadding="0" cellspacing="0" id="tabladetallederabajo"></table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="315" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55">
            Buscar
            </td>
            <td width="112"><span class="Tablas">
              <input name="guia" type="text" class="Tablas" id="guia" style="width:100px" value="<?=$guia ?>" onKeyPress="if(event.keyCode==13){buscarFolio();}" />
            </span></td>
            <td width="132"><select name="buscar" class="Tablas" style="width:140px" >
            <option value="0">GUIA</option>
            <option value="1">CODIGO DE BARRAS</option>
            </select>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center"><input name="accion" type="hidden" id="accion" />
              <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />
              <input name="registros" type="hidden" id="registros" />
              <input name="prefijo" type="hidden" id="prefijo" />
              <table border="0" cellpadding="0" cellspacing="0">
          <tr>
          <td width="84"><div class="ebtn_nuevo" onClick="confirmar('¿Desea limpiar los datos?','¡Atencion!','limpiarDatos()','')"></div></td>
          <td width="79"><div class="ebtn_guardar" onClick="guardarValores()"></div></td>
          </tr>
          </table>
            </td>
      </tr>
    </table>
    </tr>
</table>
</form>
</body>
</html>