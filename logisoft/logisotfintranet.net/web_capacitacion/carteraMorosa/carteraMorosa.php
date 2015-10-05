<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date("d/m/Y");
	$s = "DELETE FROM carteramorosadetalle_tmp WHERE idusuario=".$_SESSION[IDUSUARIO]."";
	mysql_query($s,$l) or die($s);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SEL", medida:20, tipo:"checkbox", alineacion:"left",datos:"sel"},
			{nombre:"#CLIENTE", medida:80, alineacion:"left", datos:"cliente"},
			{nombre:"NOMBRE", medida:150, alineacion:"left", datos:"nombre"},
			{nombre:"REFERENCIA", medida:80, alineacion:"center",  datos:"referencia"},
			{nombre:"FECHA", medida:80, alineacion:"center",  datos:"fechareferencia"},
			{nombre:"FACTURA", medida:70, alineacion:"left", datos:"factura"},
			{nombre:"IMPORTE", medida:100, alineacion:"right", tipo:"moneda", datos:"importe"},
			{nombre:"ASIGNADO", medida:80, alineacion:"center",  datos:"asignado"},
			{nombre:"CAUSA", medida:120, alineacion:"left", datos:"causa"},
			{nombre:"COMPROMISO", medida:80, alineacion:"left", datos:"compromiso"},
			{nombre:"USUARIO", medida:120, alineacion:"left", datos:"empleado"},
			{nombre:"IDUSUARIO", medida:4, alineacion:"left", tipo:"oculto", datos:"idempleado"},
			{nombre:"ID", medida:4, alineacion:"left", tipo:"oculto", datos:"id"},
			{nombre:"GUARDADO", medida:4, alineacion:"left", tipo:"oculto", datos:"guardado"},
			{nombre:"CARTERA", medida:4, alineacion:"left", tipo:"oculto", datos:"cartera"},
			{nombre:"CARTERA2", medida:4, alineacion:"left", tipo:"oculto", datos:"cartera2"}
		],
		filasInicial:20,
		alto:230,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"quitarResponsable()",
		nombrevar:"detalle"
	});
	
	jQuery(function($){
	 	$('#fecha').mask("99/99/9999");
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerGeneral();
	}	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","carteraMorosa_con.php?accion=1");
	}
	function mostrarGeneral(datos){
		var row = datos.split(",");
		u.sucursal.value = row[0];
		u.fecha.value = row[1];
		consultaTexto("eliminarTemporal","carteraMorosa_con.php?accion=0&tabla=carteramorosadetalle_tmp");
	}
	function eliminarTemporal(datos){
		if(datos.indexOf("ok")<0){
			alerta3("Hubo un error al eliminar Temporal","¡Atención!");
		}
	}
	function obtenerCliente(cliente){
		u.cliente.value = cliente;
		consultaTexto("mostrarCliente","carteraMorosa_con.php?accion=2&cliente="+cliente);
	}
	function obtenerClienteEnter(cliente){
		consultaTexto("mostrarCliente","carteraMorosa_con.php?accion=2&cliente="+cliente);
	}
	function mostrarCliente(datos){
		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){
			var obj = eval(convertirValoresJson(datos));
			u.nombre.value	= obj[0].nombre;
			u.fecha.select();
		}else{
			alerta("El código de Cliente no existe","¡Atención!","cliente");
			u.cliente.value = ""; u.nombre.value = "";
		}
	}	
	function validarCriterio(tipo){
		switch (tipo){
			case 1:
				u.cliente.readOnly = false;
				u.cliente.select();
			break;
			
			case 2:
				u.cliente.value = "";
				u.cliente.readOnly = true;
				u.nombre.value = "";
			break;
		}
	}
	function generarDetalle(){
		if(u.criterio[0].checked==true){
			if(u.cliente.value == ""){
				alerta("Debe capturar Cliente","¡Atención!","cliente");
				return false;
			}
		}
		u.btnGenerar.style.visibility = "hidden";
		consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=3&cliente="+((u.criterio[0].checked==true)?u.cliente.value:0)+"&fecha="+u.fecha.value);
	}
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro")<0){
			u.btnGenerar.style.visibility = "visible";
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
			for(var i=0;i<tabla1.getRecordCount();i++){
				if(u["detalle_ASIGNADO"][i].value == "SI"){
					u["detalle_SEL"][i].checked = true;
				}
			}
		}else{
			alerta3("No se encontraron registros con los criterios seleccionados","¡Atención!");
			tabla1.clear();
			u.btnGenerar.style.visibility = "visible";
		}
	}
	function guardar(){
		var cant = tabla1.getRecordCount();
		if(cant == 0){
			alerta3("No existen datos en el Detalle de la Cartera Morosa","¡Atención!");
			return false;
		}
		if(u.compromiso.value==0){
			alerta("Debe capturar Compromiso","¡Atención!","compromiso");
			return false;
		}
		if(u.empleado.value=="" || u.empleado_hidden.value==""){
			alerta("Debe capturar Empleado","¡Atención!","empleado");
			return false;
		}
		confirmar('¿Desea guardar la información?', '', 'guardarConfirmacion()', '');
	}
	function guardarConfirmacion(){
		var cant = tabla1.getRecordCount();
		var contador = 0;
		var contador2 = 0;
		var si = 0;
		for(var i=0; i<cant; i++){
			if(u["detalle_CARTERA"][i].value != u["detalle_CARTERA2"][i].value){				
				u.registros.value += u["detalle_ID"][i].value+","+u["detalle_ASIGNADO"][i].value
				+","+u["detalle_CAUSA"][i].value+","+u["detalle_IDUSUARIO"][i].value
				+","+u["detalle_COMPROMISO"][i].value+","+u["detalle_CARTERA"][i].value
				+","+u["detalle_CARTERA2"][i].value+";";
				
			}else if(u["detalle_SEL"][i].checked == true && u["detalle_GUARDADO"][i].value != "SI"){
				u.registros.value += u["detalle_ID"][i].value+","+u["detalle_ASIGNADO"][i].value
				+","+u["detalle_CAUSA"][i].value+","+u["detalle_IDUSUARIO"][i].value
				+","+u["detalle_COMPROMISO"][i].value+","+u["detalle_CARTERA"][i].value
				+","+u["detalle_CARTERA2"][i].value+":";
				
			}else if(u["detalle_GUARDADO"][i].value == "SI"){
				si++;
			}else{
				contador++;
			}
			
			if(u["detalle_SEL"][i].checked == false){				
				contador2++;
			}
			
		}
		
		if(si == cant){
			return false;
		}else if(cant == contador || cant == contador2){
			confirmar('No selecciono ningun registro, se le asignaran todas las actividades al empleado seleccionado como responsable ¿Desea continuar?', '', 'confirmarAsignacion();', '');
		}else{
			u.registros.value = u.registros.value.substring(0,u.registros.value.length-1);
			consultaTexto("registrarCartera","carteraMorosa_con.php?accion=4&noseleccion=si&folios="+u.registros.value);
		}
	}	
	function registrarCartera(datos){
		if(datos.indexOf("ok")>-1){		
			var row = datos.split(",");
			if(row[1]=="guardado"){
				info("","La información ha sido guardada correctamente");
				u.registros.value = "";
				for(var i=0; i < tabla1.getRecordCount(); i++){
					if(u["detalle_SEL"][i].checked == true){
						u["detalle_GUARDADO"][i].value = "SI";	
					}
				}
				tabla1.clear();
				consultaTexto("mostrarDetalle","carteraMorosa_con.php?accion=3&cliente="+((u.criterio[0].checked==true)?u.cliente.value:0)+"&fecha="+u.fecha.value);
			}
		}else{
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
		}
	}
	function confirmarAsignacion(){
		consultaTexto("registrarCartera","carteraMorosa_con.php?accion=4&noseleccion=no&empleado="+u.empleado_hidden.value
		+"&causa="+u.compromiso.options[u.compromiso.selectedIndex].text);
	}
	function agregarResponsable(){
		//if(tabla1.getValSelFromField('cliente','#CLIENTE')!=""){
		if(u.compromiso.value==0){
			alerta("Debe capturar Compromiso","¡Atención!","compromiso");
			/*if(tabla1.getRecordCount()>1){
				u["detalle_SEL"][tabla1.getSelectedIndex()].checked = false;
			}*/				
			return false;
		}
		if(u.empleado.value=="" || u.empleado_hidden.value==""){
			alerta("Debe capturar Empleado","¡Atención!","empleado");
			/*if(tabla1.getRecordCount()>1){
				u["detalle_SEL"][tabla1.getSelectedIndex()].checked = false;
			}*/
			return false;
		}

		for(var i=0;i<tabla1.getRecordCount();i++){		
		
			if(u["detalle_SEL"][i].checked == true && u["detalle_ASIGNADO"][i].value=="NO"){
				tabla1.setSelectedById("detalle_id"+i);
				var arr = tabla1.getSelectedRow();
				var obj = new Object();
				obj.sel			= 2;
				obj.cliente 	= arr.cliente;
				obj.nombre		= arr.nombre;
				obj.referencia 	= arr.referencia;
				obj.fechareferencia	= arr.fechareferencia;
				obj.factura 	= arr.factura;
				obj.importe 	= arr.importe;
				obj.asignado 	= "SI";
				obj.causa		= u.compromiso.options[u.compromiso.selectedIndex].text;
				obj.compromiso 	= "";
				obj.empleado	= u.empleado.value;
				obj.idempleado	= u.empleado_hidden.value;
				obj.id 			= arr.id;
				obj.cartera		= arr.cartera;
				obj.cartera2	= arr.cartera2;
				obj.guardado	= ((arr.idusuario!=u.empleado_hidden.value)?"":arr.guardado);
				tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);			
			}
		}		
	}
	
	function quitarResponsable(){
			var arr = tabla1.getSelectedRow();
			var obj = new Object();
		if(arr.asignado=="SI"){	
			obj.sel			= 1;
			obj.cliente 	= arr.cliente;
			obj.nombre		= arr.nombre;
			obj.referencia 	= arr.referencia;
			obj.fechareferencia 		= arr.fechareferencia;
			obj.factura 	= arr.factura;
			obj.importe 	= arr.importe;
			obj.asignado 	= "NO";
			obj.causa		= "";
			obj.compromiso 	= "";
			obj.empleado	= "";
			obj.idempleado	= "";
			obj.id 			= arr.id;
			obj.guardado	= "";
			obj.cartera		= arr.cartera;
			obj.cartera2	= arr.cartera2;
			tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
		}
	}
	
	function modificoDetalle(datos){
		if(datos.indexOf("ok")<0){
			alerta3("Hubo un error al seleccionar "+datos,"¡Atención!");
		}
	}
	function nuevo(){
		u.cliente.value			= "";
		u.nombre.value 			= "";
		u.referencia.value 		= "";
		//u.deseleccionar.checked	= false;
		u.compromiso.value		= 0;
		u.empleado.value		= "";
		u.criterio.checked		= false;
		tabla1.clear();
		obtenerGeneral();
	}
	function buscarFactura(factura){
		if(tabla1.getRecordCount()>0){
			if(factura!=""){
				tabla1.setFilter("factura",factura,"!=");
			}else{
				tabla1.setFilter("","none");
			}
		}
	}	
	function validarCaja(e,obj,tipo){
		tecla = (u) ? e.keyCode : e.which;
    	if((tecla==8 || tecla==46) && document.getElementById(obj).value==""){
			if(tipo==1){
				u.empleado_hidden.value	= "";
			}else if(tipo==2){
				u.nombre.value = "";
			}
		}
	}
	function obtenerEmpleado(empleado){
		u.empleado_hidden.value = empleado;
		consultaTexto("mostrarEmpleado","carteraMorosa_con.php?accion=11&empleado="+empleado);
	}
	
	function mostrarEmpleado(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.empleado.value = obj[0].empleado;
		}else{
			alerta("El numero de empleado no existe","¡Atención!","empleado_hidden");
			u.empleado.value = "";
		}
	}
	var desc = new Array(<?php echo $desc; ?>);
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:185px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:1em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
<!--
</style>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<link href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Cartera Morosa  </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="right">Sucursal: &nbsp;&nbsp;<input name="sucursal" type="text" class="Tablas" style="width:150px" value="<?=$f->descripcion  ?>" readonly="true" /></td>
        </tr>
        <tr>
          <td colspan="4"><table width="549" border="0" cellspacing="0" cellpadding="0">
            
            <tr>
              <td width="86" rowspan="2"><p>
                    <label>
                    <input type="radio" name="criterio" value="1" style="width:13px" checked="checked" onclick="validarCriterio(1)" />
                      Clientes</label>
                    <br />
                    <label>
                    <input type="radio" name="criterio" value="0" style="width:13px" onclick="validarCriterio(2)" />
                      Todos</label>
                    <br />
              </p></td>
              <td width="174"><label>
                <input name="cliente" type="text" id="cliente" class="Tablas" onkeypress="if(event.keyCode==13){obtenerClienteEnter(this.value)}" onkeyup="validarCaja(event,this.name,2)" />
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="if(document.all.cliente.readOnly==false){abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerCliente', 600, 450, 'ventana', 'Busqueda')}" /></label></td>
              <td width="289"><input name="nombre" type="text" class="Tablas" id="nombre" readonly="" style="width:250px; background-color:#FFFF99" /></td>
            </tr>
            
            <tr>
              <td colspan="2">Fecha al:
                <input name="fecha" type="text" style="width:75px" class="Tablas" id="fecha" maxlength="10"/>
                <img src="../img/calendario.gif" alt="Baja" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../img/Boton_Generar.gif" align="absbottom" style="cursor:pointer" onclick="generarDetalle()" id="btnGenerar" /></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td width="86">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td width="191">&nbsp;</td>
        </tr>
        <tr>
          <td>Compromiso:</td>
          <td colspan="2"><select name="compromiso" class="Tablas" id="compromiso" style="width:220px">
		  	<option value="0" selected="selected">SELECCIONAR COMPROMISO</option>
			<option value="1" >FACTURAR Y PRESENTAR A REVISION</option>
			<option value="2" >PRESENTAR A REVISION</option>
			<option value="3" >PRESENTAR A COBRO</option>
			<option value="4" >PASAR POR PAGO</option>
			<option value="5" >RETRASO EN ACUSE DE RECIBO</option>
			<option value="6" >EN PROCESO LEGAL</option>
			<option value="7" >RECLAMACION DE DAÑOS Y FALTANTES</option>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        
        <tr>
          <td>&nbsp;</td>
          <td width="207">&nbsp;</td>
          <td width="65">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>Referencia:</td>
              <td><input name="referencia" type="text" class="Tablas" id="referencia" style="width:80px" onkeypress="if(event.keyCode==13){buscarFactura(this.value)}"></td>
              <td width="48%">&nbsp;</td>
              <td valign="middle"><input name="registros" type="hidden" id="registros" value="<?=$_POST[registros] ?>" />
                <input name="accion" type="hidden" id="accion" value="<?=$_POST[accion] ?>" />
                <input name="h_fecha" type="hidden" id="h_fecha" value="<?=$fecha ?>" /></td>
            </tr>
            <tr>
              <td width="9%">Empleados:</td>
              <td width="19%"><input name="empleado_hidden" class="Tablas" type="text" id="empleado_hidden" style="width:70px" onkeyup="validarCaja(event,this.name,1)" onkeypress="if(event.keyCode==13){obtenerEmpleado(this.value)}"/>
                  <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=obtenerEmpleado', 625, 550, 'ventana', 'Busqueda')" /></td>
              <td><input name="empleado2" type="text" class="Tablas" id="empleado" readonly="" style="width:250px; background-color:#FFFF99" />
                  <label></label></td>
              <td width="24%" valign="middle"><img src="../img/Boton_AsignarAct.gif" width="117" height="20" style="cursor:pointer" onclick="agregarResponsable()" /></td>
            </tr>
          </table></td>
          </tr>
        
        <tr>
          <td colspan="4" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><div style=" height:260px; width:690px; overflow:auto">
		  <table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></div></td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><table width="166" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="83"><div class="ebtn_guardar" onclick="guardar();"></div></td>
              <td width="83" align="right"><div class="ebtn_nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'nuevo();', '')"></div></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
