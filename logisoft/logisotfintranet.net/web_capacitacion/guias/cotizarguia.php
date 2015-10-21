<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = mysql_query("SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),
	CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd 
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id",$l);
	if(mysql_num_rows($s)>0){
		while($f=mysql_fetch_array($s)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}	
		$desc=substr($desc, 0, -1);
	}
	if($_POST['accion']==""){
		$idsucursal 			= $_GET['idsucorigen'];
		$_POST[sucursalant] 	= $_GET['idsucorigen'];
		$folio_hidden 			= $_GET['folio'];
		$idsucursal2 			= $_GET['sucursal'];
		$_POST[estado_hidden] 	= $_GET['estado'];
		$fecha_hidden 			= $_GET['fecha'];
		$confirFecha 			= date("d/m/Y");
	}
	$s = mysql_query("SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),
	CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd 
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id",$l);
	if(mysql_num_rows($s)>0){
		while($f=mysql_fetch_array($s)){
			$desc2= "'".utf8_decode($f[0])."'".','.$desc2;
		}	
		$desc2=substr($desc2, 0, -1);
	}
	
	$s = mysql_query("SELECT CONCAT_WS(':',descripcion,id) AS descripcion FROM catalogodescripcion",$l);
	if(mysql_num_rows($s)>0){
		while($f=mysql_fetch_array($s)){
			$desc3= "'".utf8_decode($f[0])."'".','.$desc3;
		}	
		$desc3=substr($desc3, 0, -1);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">

<head> 
	<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
	body{
		font-family:Verdana, Geneva, sans-serif;
		font-size:12px;
	}
	.titulos{
		padding-top:5px;
		height:20px; 
		background-color:#225E99; 
		text-align:center; 
		vertical-align:middle; 
		color:#FFF; font-weight:bold;
	}
</style>
	
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script src="jquery.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../javascript/DataSet.js"></script>
<script>
    var u = document.all;
	var tabla1 	= new ClaseTabla();
	var mens	= new ClaseMensajes();
	var desc = new Array(<?php echo $desc; ?>);
	var desc2 = new Array(<?php echo $desc2; ?>);
	var desc3 = new Array(<?php echo $desc3; ?>);
	
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},			
			{nombre:"DESCRIPCION", medida:150, alineacion:"right", datos:"descripcion"},
			{nombre:"CONTENIDO", medida:150,  alineacion:"right", datos:"contenido"},		
			{nombre:"PESO", medida:50, alineacion:"CENTER", datos:"peso"},
			{nombre:"IMPORTE", medida:50, alineacion:"CENTER",  datos:"largo"},
			{nombre:"CONCEPTO", medida:150,alineacion:"right",  datos:"ancho"},
			{nombre:"CLASE", medida:120, alineacion:"right",  datos:"alto"},
			{nombre:"P_VOLU", medida:4, tipo:"oculto", alineacion:"right", datos:"volumen"},
			{nombre:"P_TOTAL", medida:50,  alineacion:"CENTER", datos:"pesototal"}
		],
		filasInicial:5,
		alto:100,
		seleccion:true,
		ordenable:false,		
		nombrevar:"tabla1",
		//eventoDblClickFila:"eliminarFila()"
		eventoDblClickFila:"ModificarFila()",
	});
	function agregarDatos(objeto){
		if(u.index.value!=""){
			tabla1.deleteById(tabla1.getSelectedIdRow());
			tabla1.add(objeto);
			u.index.value	= "";			
		}else{
			tabla1.add(objeto);
			u.Eliminar.style.visibility ="visible";
		}		
	}
	function ModificarFila(){
		
			var obj = tabla1.getSelectedRow();		
			if(tabla1.getValSelFromField("cantidad","CANT")!=""){
			u.index.value	= tabla1.getSelectedIndex();
			abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos&cantidad='+obj.cantidad
			+'&id='+obj.id
			+'&descripcion='+obj.descripcion
			+'&contenido='+obj.contenido
			+'&peso='+obj.peso
			+'&largo='+obj.largo
			+'&ancho='+obj.ancho
			+'&alto='+obj.alto
			+'&pesototal='+obj.pesototal
			+'&pesounit='+obj.pesounit
			+'&fechahora='+obj.fecha
			+'&volumen='+obj.volumen+'&esmodificar=si', 500, 280, 'ventana', 'Datos Mercancia','ponerFoco();');
		}
	}
	function cotizar(valores){
		document.getElementById('paginaRastreo').src = "../../axfbt5asd/cotizar.php?tipo=1&idsucursal="+u.idsucursal2.value+"&folio2="+u.folio_hidden.value+"&"+valores;
		//alert(valores);
	}

	
	function imprimir(valores){		
		if(u.accion.value == "" ){
			u.registroMercancia.value = tabla1.getRecordCount();			
			u.accion.value 		      = "grabar";
			consultaTexto("registro","recoleccion_consultas.php?accion=1&idsucursal="+u.idsucursal2.value+"&folio="+u.folio.value
			+"&fecha="+u.fecha.value			
			+"&idcliente="+u.idcliente.value+"&origennombre="+u.origen.value+"&desde="+u.desde.value
			+"&hasta="+u.hasta.value+"&destino="+u.destino.value
			+"&valorunitario="+u.valorunitario.value
			+"&IDRecoleccion="+u.folio_hidden.value
			+"&valordeclarado="+u.valordeclarado2.value+"&tiporviaje="+u.tiporviaje.value
			+"&condicionespago="+u.condicionespago.value+"&cuotatonelada="+u.cuotatonelada.value
			+"&tip=grabar&val="+Math.random());
		}else if(u.accion.value == "modificar"){
			u.registroMercancia.value = tabla1.getRecordCount();			
			u.accion.value 		      = "modificar";
			consultaTexto("modifico","recoleccion_consultas.php?accion=1&idsucursal="+u.idsucursal2.value+"&folio="+u.folio.value
			+"&fecha="+u.fecha.value			
			+"&idcliente="+u.idcliente.value+"&origennombre="+u.origen.value+"&desde="+u.desde.value
			+"&hasta="+u.hasta.value+"&destino="+u.destino.value
			+"&valorunitario="+u.valorunitario.value
			+"&IDRecoleccion="+u.folio_hidden.value
			+"&valordeclarado="+u.valordeclarado2.value+"&tiporviaje="+u.tiporviaje.value
			+"&condicionespago="+u.condicionespago.value+"&cuotatonelada="+u.cuotatonelada.value
			+"&tip=modif&val="+Math.random());
		}else{
			u.registroMercancia.value = tabla1.getRecordCount();			
			u.accion.value 		      = "grabar";
			consultaTexto("registro","recoleccion_consultas.php?accion=1&idsucursal="+u.idsucursal2.value+"&folio="+u.folio.value
			+"&fecha="+u.fecha.value			
			+"&idcliente="+u.idcliente.value+"&origennombre="+u.origen.value+"&desde="+u.desde.value
			+"&hasta="+u.hasta.value+"&destino="+u.destino.value
			+"&valorunitario="+u.valorunitario.value
			+"&IDRecoleccion="+u.folio_hidden.value
			+"&valordeclarado="+u.valordeclarado2.value+"&tiporviaje="+u.tiporviaje.value
			+"&condicionespago="+u.condicionespago.value+"&cuotatonelada="+u.cuotatonelada.value
			+"&tip=grabar&val="+Math.random());
		}
		window.open("../../axfbt5asd/cotizarImprimir.php?tipo=1&idsucursal="+u.idsucursal2.value+"&folio2="+u.folio_hidden.value+"&"+valores,null,"width=5px height=5px top=5000 left=5000");
		//alert(valores);
	}
	function registro(datos){
		if(datos.indexOf("guardo")>-1){
			var row = datos.split(",");
			u.folio.value = row[1];
			//u.d_guardar.style.visibility = "visible";
			//u.colEstado.innerHTML = "NO TRANSMITIDO";
			mens.show("I","Los datos han sido guardados correctamente","");
			//cambiarPagina();
		}else{
			//u.d_guardar.style.visibility = "visible";
			mens.show("A","Hubo un error al registrar "+datos,"¡Atención!");
		}
	}
	
	function modifico(datos){
		if(datos.indexOf("modifico")>-1){
			var row = datos.split(",");
			if(row[1] != u.folio.value && row[1] != undefined){
				u.folio.value = row[1];
			}
			//u.colEstado.innerHTML = "NO TRANSMITIDO";
			//u.d_guardar.style.visibility = "visible";
			mens.show("I","Los cambios han sido guardados correctamente","");
			//cambiarPagina();
		}else{
			//u.d_guardar.style.visibility = "visible";
			mens.show("A","Hubo un error al registrar "+datos,"¡Atención!");
		}
	}
	
	function enviarCorreo(valores){
		window.open("../../axfbt5asd/enviarCorreo.php?email="+$('#email').val()+"&"+valores,null,"width=5px height=5px top=5000 left=5000");
	}
	
	function devolverCliente(valor){
		$.ajax({
		   type: "GET",
		   url: "cotizarguia_con.php",
		   data: "accion=1&idcliente="+valor,
		   success: function(msg){
			  var obj = eval(msg);
			  $("#nombrecliente").val(obj.nombre);
			  $("#idcliente").val(obj.id);
		   }
		});
	}
	
	$(document).ready(function(){
		$('#idcliente').keypress(function(event) {
		  	if (event.keyCode == '13') {
			 	event.preventDefault();
				devolverCliente( $("#idcliente").val());
		   	}
		});
		
		$('#idcliente').blur(function() {
			devolverCliente( $("#idcliente").val());
		});
		$('#agregar').click(function() {
			agregarDetalle();
		});
		tabla1.create();
		mens.iniciar("../javascript");
	})
	
	function agregarDetalle(){
		if($('#descripcion').val()==""){
			mens.show("A","Debe capturar Descripcion","¡Atención!","descripcion");
			return false;
		}
		if($('#largo').val()==""){
			mens.show("A","Debe capturar Largo","¡Atención!","largo");
			return false;
		}
		if(parseFloat($('#largo').val())==0){
			mens.show("A","Largo debe ser mayor a Cero","¡Atención!","largo");
			return false;
		}
		if($('#alto').val()==""){
			mens.show("A","Debe capturar Alto","¡Atención!","alto");
			return false;
		}
		if(parseFloat($('#alto').val())==0){
			mens.show("A","Alto debe ser mayor a Cero","¡Atención!","alto");
			return false;
		}
		if($('#ancho').val()==""){
			mens.show("A","Debe capturar Ancho","¡Atención!","ancho");
			return false;
		}
		if(parseFloat($('#ancho').val())==0){
			mens.show("A","Ancho debe ser mayor a Cero","¡Atención!","ancho");
			return false;
		}
		if($('#cantidad').val()==""){
			mens.show("A","Debe capturar Cantidad","¡Atención!","cantidad");
			return false;
		}
		if(parseFloat($('#cantidad').val())==0){
			mens.show("A","Cantidad debe ser mayor a Cero","¡Atención!","cantidad");
			return false;
		}
		if($('#peso').val()==""){
			mens.show("A","Debe capturar Peso","¡Atención!","peso");
			return false;
		}
		if(parseFloat($('#peso').val())==0){
			mens.show("A","Peso debe ser mayor a Cero","¡Atención!","peso");
			return false;
		}
		var obj = new Object();
		obj.cantidad	= $('#cantidad').val();
		obj.descripcion	= $('#descripcion').val();
		obj.peso		= $('#peso').val();
		obj.largo		= $('#largo').val();
		obj.ancho		= $('#ancho').val();
		obj.alto		= $('#alto').val();
		obj.pesototal	= parseFloat($('#peso').val());
		if($('#pesounitario').attr("checked")){
			obj.pesototal *= obj.cantidad;
		}
		obj.volumen		= (parseFloat($('#largo').val()) * parseFloat($('#ancho').val()) * parseFloat($('#alto').val()) / 4000);
		if($('#medidaunitario').attr("checked")){
			obj.volumen *= obj.cantidad;
		}
		
		$('#pesounitario').removeAttr("checked");
		$('#medidaunitario').removeAttr("checked");
		
		tabla1.add(obj);
		$('#cantidad').val("");
		$('#descripcion').val("");
		$('#peso').val("");
		$('#largo').val("");
		$('#ancho').val("");
		$('#alto').val("");
	}
	
	function eliminarFila(){
		if(tabla1.getRecordCount()>0){
			tabla1.deleteById(tabla1.getSelectedIdRow());
		}
	}
	window.onload = function(){
		abrirVentanaFija('recoleccionMercancia.php', 700, 500, 'ventana', 'Busqueda')
	}
	function obtener(folio1,Suc,Carta){
		u.folio_hidden.value = folio1;
		u.folio.value = Carta;
		
		if(Carta > 0){
			u.accion.value = "modificar";
			u.d_cancelado.style.visibility="visible";
		}else{
			obtenerGeneral2();
	    }
		u.idsucursal2.value = Suc;
		obtenerRecoleccionMercancia()
	}
	function obtenerGeneral2(){
		consultaTexto("mostrarGeneral2","recoleccion_conj.php?accion=18");
	}
	
	function mostrarGeneral2(datos){
		var row = datos.split(",");
		u.folio.value = row[0];
	}
	function obtenerRecoleccionMercancia(){
		consultaTexto("mostrarTodo","recoleccion_conj.php?accion=6&folio="+u.folio_hidden.value
		+"&idsucursal="+u.idsucursal2.value+"&valor="+Math.random());
	}
	
	function mostrarTodo(datos){
		if(datos.indexOf("no encontro")<0){
			var objeto = eval(convertirValoresJson(datos));
			u.idcliente.value = objeto.principal.cliente;
			u.nombrecliente.value = objeto.principal.ncliente;
			u.desde.value 	= objeto.principal.origen;
			u.hasta.value = objeto.principal.destino;
			u.origen.value = objeto.principal.desori;
			u.destino.value = objeto.principal.desdes;
			u.fecha.value = objeto.principal.fecharegistro;
			u.valorunitario.value  	= objeto.cartaporte.ValorUnitario;
			u.valordeclarado2.value = objeto.cartaporte.ValorDeclarado;
			u.tiporviaje.value  	= objeto.cartaporte.TipoViaje;
			u.condicionespago.value	= objeto.cartaporte.CondicionesPago;
			u.cuotatonelada.value 	= objeto.cartaporte.CuotaTonelada;
					
			tabla1.setJsonData(objeto.detalle);
		
		}
	}
	
	var desc 	= new Array(<?php echo $desc; ?>);
</script>
</head>
<body>

<table width="813" border="0" cellpadding="0" cellspacing="0" height="483">
  <tbody>
    <tr>
      <td rowspan="4" width="1" valign="top"></td>
      <td width="808" valign="top"><form id="cotizador" name="cotizador" method="post" action="">
        <span>
        <div class="titulos">Generar Carta Porte</div><br />
        <table height="163" border="0px" style="width:100%">
        	    <tr>
            	<td width="432">Folio:
                     <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$_POST['folio']; ?>"/>    
                </td> 
        	  <td height="24" colspan="3">
        	    <select name="tipopaquete" size="1" style="visibility:hidden" >
        	      <option selected="selected" value="Envase">Envase</option>
        	      <option value="Paquete">Paquete</option>
      	      </select>
       	  <strong>Ruta</strong></td>
                           
             
            	
        	 <tr>
            	<td width="432" height="24">Fecha
                      <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$_POST[fecha] ?>" readonly="" onchange="obtenerFolioxFecha(this.value)" />
                      <img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this); " />                      
                </td>
                <td width="418">Origen :  &nbsp;&nbsp;
                  <input type="text" name="origen" autocomplete="array:desc" style="width:140px; text-transform:uppercase"
             			 onkeypress="if(event.keyCode==13){document.all.desde.value=this.codigo;}" onkeydown="if(event.keyCode==9){document.all.desde.value=this.codigo;}" onblur="if(this.value!=''){document.all.desde.value=this.codigo;}"
              			 />
                <input type="hidden" name="desde" id="desde" size="1" />
                </td>            
             </tr>
        	<tr>
            	<td width="432" height="29"><input type="text" name="idcliente" id="idcliente" style="width:78px" />
           	    <img src="../img/Buscar_24.gif" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverCliente', 725, 418, 'ventana', 'Busqueda')" />Cliente</td>
            	<td width="418">Destino: &nbsp;
                  <input type="text" name="destino" autocomplete="array:desc2" style="width:140px; text-transform:uppercase"
              onkeypress="if(event.keyCode==13){document.all.hasta.value=this.codigo;}" onkeydown="if(event.keyCode==9){document.all.hasta.value=this.codigo;}" onblur="if(this.value!=''){document.all.hasta.value=this.codigo;}"
               />
                <input type="hidden" name="hasta" id="hasta" size="1" /></td>
            </tr>
        	<tr>
        	  <tr>
        	  <td height="24" colspan="3">
         <table width="64%" border="0px" cellpadding="0px" cellspacing="0px" style="width:100%">
          <tr>
        	  <td height="0" colspan="3"><span style="text-align:left">
        	    <input type="text" name="nombrecliente" id="nombrecliente" readonly="readonly" style="width:550px" />
        	  </span></td>
      	  </tr>
              	<tr>  
<!--                    <td >Origen  
                  		 <input type="text" name="origen" autocomplete="array:desc" style="width:140px; text-transform:uppercase"
             			 onkeypress="if(event.keyCode==13){document.all.desde.value=this.codigo;}" onkeydown="if(event.keyCode==9){document.all.desde.value=this.codigo;}" onblur="if(this.value!=''){document.all.desde.value=this.codigo;}"
              			 />
                          <input type="hidden" name="desde" id="desde" size="1" />
               		</td>-->
                </tr>
                <tr>                	
<!--                    <td>Destino
                    <input type="text" name="destino" autocomplete="array:desc2" style="width:140px; text-transform:uppercase"
              onkeypress="if(event.keyCode==13){document.all.hasta.value=this.codigo;}" onkeydown="if(event.keyCode==9){document.all.hasta.value=this.codigo;}" onblur="if(this.value!=''){document.all.hasta.value=this.codigo;}"
               />
                    <input type="hidden" name="hasta" id="hasta" size="1" /></td>-->
                </tr>

              </table>
            </td>
      	  </tr>
          	    <td height="5" colspan="3" style="text-align:Left">Valor Unitario: 
                <input type="text" name="valorunitario" id="valorunitario" style="width:140px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Valor Declarado:
                <input type="text" name="valordeclarado2" id="valordeclarado2" style="width:140px" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Tipo de Viaje:
                <input type="text" name="tiporviaje" id="tiporviaje" style="width:140px" />              </td>
              
      	  </tr>
          
          <tr>
        	  <td height="24" colspan="3">Condiciones de Pago:
                <input type="text" name="condicionespago" id="condicionespago" style="width:140px" />&nbsp;&nbsp;&nbsp;&nbsp;
              Cuota Tonelada:
              <input type="text" name="cuotatonelada " id="cuotatonelada" style="width:140px" />
              <input type="hidden" name="chk1" />
              <input type="hidden" name="chk2" /></td>
      	  </tr>
         
       	  </table>
        </span>
        <table width="800" border="0" cellpadding="0" cellspacing="0">
          <!--DWLayoutTable-->
          <tr>
            <td width="800" height="20" colspan="2" valign="middle" bordercolor="#BCD4E0" bgcolor="#336699" style="padding-left:4px">
            	<div class="titulos">Articulos</div>            </td>
            </tr>
          <tr>
				<td colspan="2">
					<div style="background-color:#282828">
						<table id="detalle" width="100%" border="0" cellspacing="0" cellpadding="0">
						</table>
					</div>			</td>
			</tr>
          <tr>
            <td height="37" colspan="2" valign="top"><span class="style7"><strong></strong></span>
            <img src="../img/Boton_Generar.gif" style="cursor:pointer" onclick="cotizar($('#cotizador').serialize())"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img src="../img/Boton_Cancelar.gif" id="d_cancelado" style="cursor:pointer; visibility:hidden;" onclick="cotizar($('#cotizador').serialize())"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../img/Boton_Procede.gif" style="cursor:pointer" onclick="imprimir($('#cotizador').serialize())" /><br />
            <input type="hidden" name="valordeclarado" value="" />
              <input name="folio_hidden" type="hidden" id="folio_hidden" value="<?=$folio_hidden ?>" />
              <input name="idsucursal" id="idsucursal" type="hidden" value="<?=$idsucursal ?>" />
              <input name="idsucursal2" type="hidden" id="idsucursal2" value="<?=$idsucursal2 ?>" />
              <input name="confirFecha" type="hidden" id="confirFecha" value="<?=$confirFecha ?>" />
              <input name="sucursalant" type="hidden" id="sucursalant" value="<?=$_POST[sucursalant] ?>" />
              <input name="fecha_hidden" type="hidden" id="fecha_hidden" value="<?=$fecha_hidden ?>" />
			  <input name="index" type="hidden" id="index" /> 
			  <input name="accion" type="hidden" id="accion" value="<?=$_POST[accion] ?>" />
               <input name="registroMercancia" type="hidden" id="registroMercancia" value="<?=$_POST[registroMercancia] ?>" />
             </td>
            </tr>
          	  <td colspan="3">
            	<table width="170" border="0px" cellspacing="0px" cellpadding="0px">
                	<tr>
                    	<td width="26"><input type="hidden" name="pesounitario" id="pesounitario" /></td>
                    	<td width="144"><input type="hidden" style="width:150px" name="descripcion"  id="descripcion" autocomplete="array:desc3" onkeypress="if(event.keyCode==13){document.all.iddescripcion.value=this.codigo; document.all.largo.focus();}" onkeydown="if(event.keyCode==9){document.all.iddescripcion.value=this.codigo;}"/>
                   	    <input type="hidden" name="iddescripcion" id="iddescripcion" />
                   	    <strong>
                   	    <input style="width:40px" name="largo" type="hidden" id="largo" onkeypress="if(event.keyCode==13){document.getElementById('alto').focus()}" />
                   	    <input style="width:40px" name="ancho" type="hidden" id="ancho" onkeypress="if(event.keyCode==13){document.getElementById('cantidad').focus()}"/>
                   	    <input style="width:40px" name="alto" type="hidden" id="alto" onkeypress="if(event.keyCode==13){document.getElementById('ancho').focus()}"/>
                   	    <span class="style5"><strong>
                   	    <input type="hidden" style="width:45px" name="peso"  id="peso"/>
                   	    <strong>
                   	    <input type="hidden" style="width:65px" name="cantidad"  id="cantidad" onkeypress="if(event.keyCode==13){document.getElementById('peso').focus()}"/>
                   	    </strong></strong></span></strong></td>
                    <tr>
                    </tr>
                        <td><input type="hidden" name="medidaunitario" id="medidaunitario" /></td>
                          <td>
                          <input name="esmodificar" type="hidden" id="esmodificar" value="<?=$esmodificar ?>" />    
                            
           <!--                 <td width="166">Enviar Correo(1 a la vez)</td>
                                <td width="157"><input type="text" name="email" id="email" style="width:150px" /></td>
                                <td width="216" style="text-align:left"><img src="../img/Boton_enviar.jpg" onclick="enviarCorreo($('#cotizador').serialize())" /></td>
-->
                          </td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
            	<td height="8" colspan="2"></td>
            </tr>
			<tr>
			  <td colspan="2" align="right"><!--<img  src="../img/Boton_Agregari.gif" style="cursor:pointer" name="agregar" id="agregar" />--></td>
		    </tr>

			<table>
            <tr>
                          <td width="164" id="colEstado" style="font:tahoma; font-size:15px; font-weight:bold"><?=$_POST['estado_hidden'];?></td>
            </tr>
        	 </table> 
            <tr>
        	<td ><iframe name="paginaRastreo" id="paginaRastreo" frameborder="0" style="width:860px; height:600px;">
        	  <ol>
        	    <li></li>
      	    </ol>
        	</iframe></td>
           </tr>
<!--        <tr>
        <td width="800"><img src="../img/Boton_Imprimir.gif" style="cursor:pointer" onclick="imprimir($('#cotizador').serialize())" /></td>
      	<td width="166">Enviar Correo(1 a la vez)</td>
        	<td width="157"><input type="text" name="email" id="email" style="width:150px" /></td>
        	<td width="216" style="text-align:left"><img src="../img/Boton_enviar.jpg" onclick="enviarCorreo($('#cotizador').serialize())" /></td>
       </tr>-->
          </table>
        <label></label>
      </form></td>
      <td rowspan="4" width="10" align="center">
      <table>
<!--      	<tr>
        	<td ><iframe name="paginaRastreo" id="paginaRastreo" frameborder="0" style="width:660px; height:840px;"></iframe></td>
        </tr>-->

      </table>
      </td>
    </tr>
    <tr>
      <td height="14" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    </tr>
  </tbody>
</table>
</body>
</html>
