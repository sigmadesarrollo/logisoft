<?	session_start();
	require_once('../Conectar.php');
	$conexion = Conectarse('webpmm');
	$fecha = date("d/m/Y");
	
	$s = "delete from relacioncobranzadetalle_tmp where usuario=".$_SESSION[IDUSUARIO]."";
	mysql_query($s,$conexion)or die($s); 
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ClaseTabla.js"></script>
<link href="../estilos_estandar.css" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script>
	jQuery(function($){
	   $('#fecha').mask("99/99/9999");
	});
	var tabla1 	= new ClaseTabla();
	var u=document.all;
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[			
			{nombre:"CLIENTE", medida:40, alineacion:"left", datos:"clave"},
			{nombre:"NOMBRE", medida:140, alineacion:"left", datos:"cliente"},			
			{nombre:"GUIA", medida:70, alineacion:"center",datos:"guia"},
			{nombre:"FECHA", medida:60, alineacion:"center",datos:"fecha"},
			{nombre:"FECHA_VENCIMIENTO", medida:100, alineacion:"left",datos:"fechavencimiento"},
			{nombre:"FACTURA", medida:50, alineacion:"left",datos:"foliofactura"},
			{nombre:"IMPORTE", medida:90, alineacion:"left", tipo:"moneda",datos:"importe"},
			{nombre:"SALDO_ACTUAL", medida:90, alineacion:"left", tipo:"moneda",datos:"saldoactual"},
			{nombre:"ESTADO", medida:4, alineacion:"left", tipo:"oculto",datos:"estado"},
			{nombre:"CHEKBOX", medida:15, tipo:"checkbox", alineacion:"left",datos:"ck"},
			{nombre:"SUCURSAL", medida:5, tipo:"oculto", alineacion:"left",datos:"sucursal"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		eventoClickFila:"mostrar()",
		nombrevar:"tabla1"
	});
	window.onload = function(){
		tabla1.create();
		obtenerDefault();
	}
	function obtenerDefault(){	
			consultaTexto("mostrarDefault", "relaciondecobranzaaldia_con.php?accion=9&and="+Math.random());
	}
	function mostrarDefault(datos){
		row = datos.split(",");
		u.folio.value = row[0];
		u.fecha.value 		= row[1];
		u.sucursal_hidden.value	= row[2];
		u.sucursal.value 	= row[3];
		u.dia.value		= row[4];
		ObtenerDetalle();
	}

	function mostrarFolioCobranza(datos){	
		if (datos!=0) {
			tabla1.clear();
			var objeto = eval(convertirValoresJson(datos));
			for(var i=0;i<objeto.length;i++){
				var obj		 	   	= new Object();
				obj.clave 			= objeto[i].clave;
				obj.cliente	 	   	= objeto[i].cliente;
				obj.guia   			= objeto[i].guia;
				obj.fecha		   	= objeto[i].fecha;
				obj.fechavencimiento = objeto[i].fechavencimiento;
				obj.foliofactura 	= objeto[i].foliofactura;
				obj.importe		 	= objeto[i].importe;
				obj.saldoactual		= objeto[i].saldoactual;
				obj.estado			= objeto[i].estado;
				obj.sucursal		= objeto[i].sucursal;
				tabla1.add(obj);
				if(objeto[i].estado=="Revisadas"){
					tabla1.setColorById('#FF0000','detalle_id'+i);
				}
				u["detalle_CHEKBOX"][i].checked=true;
			}	
		}else{
			tabla1.clear();
			alerta("No existieron datos con los filtros seleccionados","¡Atención!","sucursal");
		}
	}
		
	function mostrarFolioCobranza2(datos){	
		if (datos!=0) {
				tabla1.clear();
				var objeto = eval(convertirValoresJson(datos));
				for(var i=0;i<objeto.length;i++){
					var obj		 	   	= new Object();
					obj.clave 			= objeto[i].clave;
					obj.cliente	 	   	= objeto[i].cliente;
					obj.guia   			= objeto[i].guia;
					obj.fecha		   	= objeto[i].fecha;
					obj.fechavencimiento = objeto[i].fechavencimiento;
					obj.foliofactura 	= objeto[i].foliofactura;
					obj.importe		 	= objeto[i].importe;
					obj.saldoactual		= objeto[i].saldoactual;
					obj.estado			= objeto[i].estado;
					tabla1.add(obj);
					if(objeto[i].estado=="Revisadas"){
						tabla1.setColorById('#FF0000','detalle_id'+i);
					}
					
					if (objeto[i].foliofactura==u.factura.value){
						u["detalle_CHEKBOX"][i].checked=true;
					}
				}	
			}else{
				tabla1.clear();
				alerta("No existieron datos con los filtros seleccionados","¡Atención!","sucursal");
			}
		}
	function mostrarDatosEncabezados(datos){
		var obj = eval(convertirValoresJson(datos));
		u.fecha.value			= obj[0].fecha;
		u.sucursal_hidden.value	= obj[0].clave; 
		u.sucursal.value		= obj[0].sucursal;
		u.sector.value			= obj[0].sector
		u.cobrador.value		= obj[0].cobrador
		obtenerDia(obj[0].fecha);
		u.guardar.style.visibility="hidden"
	}
	
	function obtenerDetallebtn(){
		if(u.fecha.value == "" || u.fecha.value == "__/____/__"){
			alerta("Debe capturar la fecha","¡Atención!","fecha");
			return false;
		}else{
			u.accion.value = "";
			u.cliente.value="";
			u.tpagar.value="";
		
			consultaTexto("MostrarGuias","relaciondecobranzaaldia_con.php?accion=1&fecha="+u.fecha.value
			+"&elsector="+u.sector.value
			+"&sucursal="+u.sucursal_hidden.value);
		}
		
	}
	
	function ObtenerDetalle(){
		if (u.sucursal.value==""){
			alerta("Debe capturar sucursal","¡Atención!","sucursal");
		}else if(u.fecha.value==""){
			alerta("Debe capturar la fecha","¡Atención!","fecha");	 
		}else{
		u.accion.value = "";
		u.cliente.value="";
		u.tpagar.value="";
		
		consultaTexto("MostrarGuias","relaciondecobranzaaldia_con.php?accion=1&dia="+u.dia.value+"&fecha="+u.fecha.value
					  +"&elsector="+u.sector.value+"&sucursal="+u.sucursal_hidden.value);		
		}
	}
	function MostrarGuias(datos){	
		if (datos.indexOf("ok")>-1) {
				consultaTexto("mostrarFolioCobranza","relaciondecobranzaaldia_con.php?accion=8&fecha="+u.fecha.value+"&sucursal="+u.sucursal_hidden.value);
			}else{
				tabla1.clear();
				alerta("No existieron datos con los filtros seleccionados","¡Atención!","sucursal");
			}
		}
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	function obtenerSucursal(id,descripcion){
		u.sucursal_hidden.value	= id;
		u.sucursal.value = descripcion;
		ObtenerDetalle();
	}
	function BuscarSucursal(){
		abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 550, 450, 'ventana', 'Busqueda');
	}
	function cargarFactura(folio){
		u = document.all;
		u.factura.value = folio;
	}
	function  mostrar(){
		if(tabla1.getSelectedRow()!=null){
			var arr = tabla1.getSelectedRow();
			var factura =0;
			var sel=0;
			factura=arr.foliofactura;
			sel=u["detalle_CHEKBOX"][tabla1.getSelectedIndex()].checked;
			for(var i=0; i<tabla1.getRecordCount();i++){	
					if (u["detalle_FACTURA"][i].value==factura){
						u["detalle_CHEKBOX"][i].checked=sel;
					}
			}
			u.cliente.value= arr.cliente;
			u.tpagar.value=convertirMoneda(arr.saldoactual);
		}
	}
	
	function validar(){
		if(tabla1.getRecordCount()==0){
			alerta("Debe seleccionar por lo menos un detalle","¡Atención!","sucursal");
		}else if (u.sucursal.value==""){
			alerta("Debe capturar sucursal","¡Atención!","sucursal");
		}else if(u.fecha.value==""){
			alerta("Debe capturar la fecha","¡Atención!","fecha");		
		}else if (u.cobrador.value=="0"){
			alerta("Debe capturar el cobrador","¡Atención!","cobrador");	
		}else if (u.sector.value=="") {
			alerta("Debe capturar el sector","¡Atención!","sector");	
		}else{
				u.seleccionados.value=="";
				var cuantos=0;
				var facturas = "";;
				for(var i=0; i<tabla1.getRecordCount(); i++){
					if(u["detalle_CHEKBOX"][i].checked == true){
						facturas += ((facturas!="")?",":"") + u["detalle_FACTURA"][i].value;
						u.seleccionados.value += (u.seleccionados.value!="")?(","+i):i;
						cuantos++;
					}
				}
				if (cuantos!=""){
					u.registros.value = cuantos;					
					u.accion.value = "GRABAR";
					u.guardar.style.visibility="hidden";
					u.agregar.style.visibility="hidden";	
					consultaTexto("registroCobranza","relaciondecobranzaaldia_con.php?accion=15&fecha="+u.fecha.value
					+"&sector="+u.sector.value+"&cobrador="+u.cobrador.value+"&facturas="+facturas);
				}else{
					alerta("Debe seleccionar por lo menos una factura","¡Atención!","sucursal");
				}
		}
	}

	function registroCobranza(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			info('Los datos han sido guardados correctamente','');
			u.folio.value = row[1];
			u.btn_imprimir.style.visibility = "visible";
		}else{
			alerta3("Hubo un error al guardar "+datos,"¡Atención!");
			u.guardar.style.visibility="visible";
		}
	}

	function obtenerDia(fecha){
		consultaTexto("mostrarDia","relaciondecobranzaaldia_con.php?accion=13&fecha="+fecha);
	}
	function mostrarDia(datos){
		var obj = eval(convertirValoresJson(datos));
		u.dia.value			= obj[0].dia;
	}
	function obtenerFolioCobranzaBusqueda(folio){
		u.folio.value = folio;
		consultaTexto("mostrarRelacionCobranza","relaciondecobranzaaldia_con.php?accion=4&folio="+folio);
	}
	
	function mostrarRelacionCobranza(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.fecha.value			= obj.principal.fecha;
			u.sucursal_hidden.value	= obj.principal.clave; 
			u.sucursal.value		= obj.principal.sucursal;
			u.sector.value			= obj.principal.sector
			u.cobrador.value		= obj.principal.cobrador
			
			tabla1.setJsonData(obj.detalle);
			
			obtenerDia(obj.principal.fecha);
			u.guardar.style.visibility="hidden";
			u.btn_imprimir.style.visibility="visible";
			u.accion.value = 1;
		}
	}
	
		function limpiarTodo(){
			u.folio.value 		= "";
			u.fecha.value		= "";
			u.dia.value			= "";
			u.sector.value		= "0";
			u.cobrador.value	= "0";
			u.factura.value		= "";
			u.sucursal.value		= "";
			u.sucursal_hidden.value="";
			u.cliente.value="";
			u.tpagar.value="";
			tabla1.clear();
			u.guardar.style.visibility="visible";			
			if(u.accion.value != ""){
				u.btn_imprimir.style.visibility = "hidden";
			}
			u.accion.value = "";
			obtenerDefault();
	}
	
	function ValidarFechaActual(){
		var f1 = u.h_fecha.value.split("/");
		var f2 = u.fecha.value.split("/");
		
		if(f1[0].substr(0,1)=="0"){
			f1[0] = f1[0].substr(1,1);
		}
		if(f1[1].substr(0,1)=="0"){
			f1[1] = f1[1].substr(1,1);
		}
		
		if(f2[0].substr(0,1)=="0"){
			f2[0] = f2[0].substr(1,1);
		}
		if(f2[1].substr(0,1)=="0"){
			f2[1] = f2[1].substr(1,1);
		}
		
		f1 = new Date(f1[2],f1[1],f1[0]);
		f2 = new Date(f2[2],f2[1],f2[0]);
		if(f1 > f2){			
			alerta("La fecha debe ser mayor o igual a la actual","¡Atención!","fecha");
			u.fecha.value=u.h_fecha.value;
			consultaTexto("mostrarDia","relaciondecobranzaaldia_con.php?accion=13&fecha="+u.h_fecha.value);				
		}else{
			ObtenerDetalle();
		}
	}
	
	function AgregarNuevaFactura(){
		if(u.checkbox.checked==true){
			if(u.factura.value==""){
				alerta("Debe capturar la factura que desea agregar","¡Atención!","factura");
			}else{
				consultaTexto("MostrarSiFacturaExiste","relaciondecobranzaaldia_con.php?accion=10&factura="+u.factura.value
				+"&fecha="+u.fecha.value);
			}
		}else{
			alerta3("Debe seleccionar agregar factura","¡Atención!");
		}
	}
	
	function MostrarSiFacturaExiste(datos){	
		if(datos.indexOf("cancelado")>-1){
			alerta("La factura "+u.factura.value+" no existe","¡Atención!","factura");
		
		}else if(datos.indexOf("cancelado")>-1){
			alerta("La factura "+u.factura.value+" esta cancelada","¡Atención!","factura");
			
		}else if(datos.indexOf("liquidada")>-1){
			alerta("La factura "+u.factura.value+" ya fue liquidada","¡Atención!","factura");
			
		}else if(datos.indexOf("revision")>-1){
			alerta("La factura "+u.factura.value+" esta en proceso de Revision/Liquidación","¡Atención!","factura");
		
		}else if(datos.indexOf("ya fue agregada")>-1){
			alerta("La factura "+u.factura.value+" ya existe en la relacion de cobranza","¡Atención!","factura");
		
		}else if(datos.indexOf("no encontro")>-1){
			alerta("La factura "+u.factura.value+" no obtuvo resultados","¡Atención!","factura");
			
		}else{
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
			for(var i=0;i<tabla1.getRecordCount();i++){
				if(u["detalle_ESTADO"][i].value == "Revisadas"){
					tabla1.setColorById('#FF0000','detalle_id'+i);
				}
				u["detalle_CHEKBOX"][i].checked = true;
			}
		}
	}
	function obtenerFactura(factura){
		if(u.factura.value==""){
			alerta('Capture el numero de factura','¡Atención!','factura');
		}else{
			var factura =0;
			var sel=0;
			factura=u.factura.value;				
			for(var i=0; i<tabla1.getRecordCount();i++){
				if (u["detalle_FACTURA"][i].value==factura){
					u["detalle_CHEKBOX"][i].checked=true;
				}
			}
		}
	}
	
	function tipoImpresion(valor){
		if (u.folio.value!=""){
			if(valor=="Archivo"){
			window.open("http://www.pmmentuempresa.com/web/creditoycobranza/generaExcelrelacioncobranza.php?titulo=REPORTE RELACION DE COBRANZA&folio="+u.folio.value+"&cobrador="+u.cobrador.value+"&fecha="+u.fecha.value);
			}
		}else{
			alerta("Esta relación de cobranza no ha sido guardada","¡Atención!","factura");
		}
		
	}
	
	function permitirbuscarsucursal(){
		BuscarSucursal();
	}
	
	function imprimir(){
		if(document.URL.indexOf("web/")>-1){		
			window.open("http://www.pmmintranet.net/web/fpdf/reportes/relacionCobranza.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&fecha="+u.fecha.value+"&cobrador="+u.cobrador.options[u.cobrador.options.selectedIndex].text);
		
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
		window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/relacionCobranza.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&fecha="+u.fecha.value+"&cobrador="+u.cobrador.options[u.cobrador.options.selectedIndex].text);
		
		}else if(document.URL.indexOf("web_pruebas/")>-1){
		window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/relacionCobranza.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&folio="+u.folio.value+"&fecha="+u.fecha.value+"&cobrador="+u.cobrador.options[u.cobrador.options.selectedIndex].text);
		}
	}
		

</script>
	
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">

	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
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
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {font-size: 9px;
	color: #464442;
}
-->
</style>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="relaciondecobranzaaldia.php">
  <p>&nbsp;</p>
  <table width="690" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="692" class="FondoTabla Estilo4">RELACI&Oacute;N COBRANZA AL D&Iacute;A</td>
  </tr>
  <tr>
    <td height="98">
      <table width="690" border="0" cellpadding="0" cellspacing="0">
        
        
        
        <tr>
          <td></td>
          </tr>
        
        <tr align="center">
          <td><div align="left">
            <table width="688" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Sucursal:</td>
                <td width="234"><span class="Tablas">
                  <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$_POST[sucursal] ?>" readonly=""/>
                  <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$sucursal_hidden ?>" />
                  <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=RelacionCobranza&usuario=Admin&funcion=permitirbuscarsucursal', 370, 500, 'ventana', 'Inicio de Sesión Secundaria')" style="cursor:pointer" /></span></td>
				
                <td width="41">Folio:</td>
                <td width="169"><input name="folio" type="text" class="Tablas" id="folio" style="width:70px" value="<?=$folio ?>" onKeyPress="if(event.keyCode==13){obtenerFolioCobranzaBusqueda(this.value);}" />
                  <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarFolioCobranzaGen.php?funcion=obtenerFolioCobranzaBusqueda&cobranza=1', 625, 418, 'ventana', 'Busqueda')" /></td>
              </tr>
              <tr>
                <td colspan="6" class="FondoTabla Estilo4">Filtros</td>
                </tr>
              <tr>
                <td width="55">Fecha al: </td>
                <td width="140"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;" value="<?=$_POST[fecha] ?>" onChange="obtenerDia(this.value)" />
                  <span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></span></td>
                <td width="49">D&iacute;a:</td>
                <td colspan="3"><span class="Tablas">
                  <input name="dia" type="text" class="Tablas" id="dia" style="width:100px;background:#FFFF99" value="<?=$_POST[dia] ?>" readonly=""/>
                Sector:
                <select name="sector" id="sector" style="width:150px" class="Tablas">
                  <option value="0" selected="selected" >TODOS</option>
                  <?            
                    $s = "SELECT id,descripcion FROM catalogosector where idsucursal = $_SESSION[IDSUCURSAL]";
                    $sq = mysql_query($s,$conexion) or die($s);
					
					while($row = mysql_fetch_row($sq))
					{ 
					?>
                  <option value="<?=$row[0]?>"><?=$row[1]?></option>
                  <?
					}
					?>
                </select>
                <img src="../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onClick="obtenerDetallebtn()"></span></td>
                </tr>
              <tr>
                <td>Cobrador:</td>
                <td colspan="3"><span class="Tablas">
                  <select name="cobrador" id="cobrador" style="width:350px" class="Tablas">
                    <option value="0" style="text-transform:none" >SELECCIONAR COBRADOR</option>
                    <?
            
                    $ss = "SELECT ce.id, CONCAT(ce.nombre,' ',ce.apellidopaterno,' ',ce.apellidomaterno)AS descripcion FROM 				                    catalogoempleado ce
                    INNER JOIN catalogopuesto cp ON ce.puesto=cp.id
                    WHERE cp.descripcion='MENSAJERO' order by ce.nombre";
                    $sql = mysql_query($ss,$conexion) or die($ss);
					
					while($raw = mysql_fetch_row($sql))
					{ 
					?>
                    <option value="<?=$raw[0]?>"  <?=$raw[descripcion] == $_POST[cobrador] ? "selected" : "" ?>>
                    <?=$raw[1]?>
                    </option>
                    <?
					}
					?>
                  </select>
                </span></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="6" class="FondoTabla Estilo4">Seleccionar Factura</td>
                </tr>
              <tr>
                <td>Factura:</td>
                <td><span class="Tablas">
                  <input name="factura" type="text" class="Tablas" id="factura" style="width:70px" value="<?=$factura ?>" onKeyPress="if(event.keyCode==13){AgregarNuevaFactura()};" />
                  <img src="../img/Buscar_24.gif" width="24" height="23" style="cursor:pointer" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarFacturasGen.php?funcion=cargarFactura', 570, 470, 'ventana', 'Busqueda')" /></span></td>
                <td colspan="2"><input type="checkbox" name="checkbox" value="1" onClick= "if(document.all.checkbox.checked==true){if(document.all.factura.value!=''){AgregarNuevaFactura()}else{document.all.factura.focus();}}">                  <span class="Estilo6 Tablas">Agregar Factura </span></td>
                <td><span class="Estilo6 Tablas"><img src="../img/Boton_Agregarchico.gif" name="agregar" id="cliente_dir" style=":<? if($_POST[accion]==''){?>visibility:hidden<? }?>" onClick="AgregarNuevaFactura()"/></span></td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </div></td>
        </tr>
        
        <tr align="center">
          <td>
		</td>
        </tr>
        <tr align="center">
          <td>    <div id="txtDir" style=" height:250px; width:690px; overflow:auto" align=left><table width="611" id="detalle" border="0" cellpadding="0" cellspacing="0">
              </table></div></td>
        </tr>
        <tr align="center">
          <td><div align="left"></div></td>
        </tr>
        <tr align="center">
          <td><table width="599" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="49">&nbsp;</td>
                <td width="339"><span class="Tablas">
                Cliente:<br>
                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:250px;background:#FFFF99" value="<?=$_POST[cliente] ?>" readonly=""/>
                </span></td>
                <td width="212"><div align="center">Importe total a Cobrar:<br>
                  <span class="Tablas">
                    <input name="tpagar" type="text" class="Tablas" id="tpagar" style="text-align:right;width:150px;background:#FFFF99" value="<?=$_POST[tpagar] ?>" readonly=""/>
                  </span></div></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td><input name="h_fecha" type="hidden" id="h_fecha" value="<?=$fecha ?>"></td>
        </tr>
        <tr>
          <td><table width="230" height="32" align="center">
              <tr>
                <td width="160"><table width="155" border="0">
                    <tr>
                      <td width="75"><div id="btn_imprimir" class="ebtn_imprimir" onClick="imprimir()" style="visibility:hidden"></div></td>
                      <td width="70"><div id="guardar" class="ebtn_guardar" onClick="validar()" ></div></td>
                      <td width="70"><div class="ebtn_nuevo" id="nuevo" onClick="limpiarTodo()"/></td>
                    </tr>
                </table></td>
              </tr>
            </table>
              <p>
                <center>
                 
                  <input name="registros" type="hidden" id="registros" value="<?=$registropersona ?>">
				  <input name="seleccionados" type="hidden" id="seleccionados" >
                  <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
                   
                </center>
              </p></td>
          </tr>
      </table></td>
  </tr>
      <tr>
   
      </tr>
  </table>
</td>
  </tr>
        <tr>
          <td>&nbsp;</td>
  </tr>
        <tr>
          <td width="653">&nbsp;</td>
  </tr>
      </table>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<?
	if($mensaje!=""){
		echo "<script>info('Los datos han sido guardados correctamente','');</script>";
	}
?>