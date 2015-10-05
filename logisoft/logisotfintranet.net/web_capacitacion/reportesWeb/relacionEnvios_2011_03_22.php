<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion
	FROM catalogosucursal cs ORDER BY cs.descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script>
	
	var tabla5 = new ClaseTabla();
	var u = document.all;
	var pag1_cantidadporpagina = 30;
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript');
	
	jQuery(function($){	   
	   $('#fechainicio').mask("99/99/9999");
	   $('#fechafin').mask("99/99/9999");
	});
	
	tabla5.setAttributes({
		nombre:"detalle",
		campos:[			
			{nombre:"GUIA", 		medida:80,alineacion:"center", datos:"guia"},
			{nombre:"FECHA", 		medida:80, alineacion:"center", datos:"fecha"},
			{nombre:"DESTINATARIO", medida:170,alineacion:"left",  	datos:"destinatario"},
			{nombre:"ORIGEN", 		medida:70, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", 		medida:70, alineacion:"center", datos:"destino"},
			{nombre:"FLETE", 		medida:80, alineacion:"left", 	datos:"flete"},
			{nombre:"COND. PAGO", 	medida:80, alineacion:"left", 	datos:"condicion"},
			{nombre:"ENTREGA", 		medida:80, alineacion:"left", 	datos:"entrega"},
			{nombre:"IMPORTE", 		medida:100,alineacion:"right", 	datos:"total", tipo:"moneda"},
			{nombre:"FECHA ENTREGA",medida:100,alineacion:"left", 	datos:"fechaentrega"},
			{nombre:"RECIBIO", 		medida:100,alineacion:"left",  datos:"recibio"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla5"
	});
	
	window.onload = function(){
		tabla5.create();
	}
	
	function obtenerCliente(cliente){
		u.cliente.value = cliente;
		consultaTexto("mostrarCliente","reportes_con.php?accion=2&cliente="+cliente);
	}
	
	function mostrarCliente(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.nombre.value = obj.cliente;
		}else{
			mens.show("A","El numero de cliente no existe","¡Atención!","cliente");
		}
	}
	
	function obtenerDetalle(){
		if(u.cliente.value==""){
			mens.show("A","Debe capturar Cliente","¡Atención!","cliente");
			return false;
		}	
		
		if(u.fechainicio.value=="" || u.fechainicio.value=="__/__/____"){
			mens.show("A","Debe capturar Fecha inicio","¡Atención!","fechainicio");
			return false;
		}
		
		if(u.fechafin.value=="" || u.fechafin.value=="__/__/____"){
			mens.show("A","Debe capturar Fecha fin","¡Atención!","fechafin");
			return false;
		}
		
		if(validarFecha(u.fechainicio.value,'fechainicio')==false){
			return false;
		}
		
		if(validarFecha(u.fechafin.value,'fechafin')==false){
			return false;
		}
		
		var f1 = u.fechainicio.value.split("/");
		var f2 = u.fechafin.value.split("/");
		
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
			mens.show("A","La Fecha inicio no debe ser mayor a la Fecha fin","¡Atención!","fechainicio");
			return false;
		}		
		consultaTexto("resTabla5","reportes_con.php?accion=6&cliente="+u.cliente.value
		+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value
		+"&contador="+u.pag5_contador.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random());
	}
	
	function resTabla5(datos){
		try{
			var obj = eval(convertirValoresJson(datos));
		}catch(e){
			mens.show("A",datos);
			return false;
		}
		u.pag5_total.value 		= obj.total;
		u.pag5_contador.value 	= obj.contador;
		u.pag5_adelante.value 	= obj.adelante;
		u.pag5_atras.value 		= obj.atras;		
		u.totalgeneral.value 	= "$ "+obj.totales.total;
		if(obj.registros.length>0){
			tabla5.setJsonData(obj.registros);
		}
		if(obj.paginado==1){
			document.getElementById('paginado4').style.visibility = 'visible';
		}else{
			document.getElementById('paginado4').style.visibility = 'hidden';
		}
	}
	
	function paginacion4(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla5","reportes_con.php?accion=6&contador=0&cliente="+u.cliente.value
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value+"&sucursal="+u.sucursal_hidden.value
				+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("resTabla5","reportes_con.php?accion=6&contador="+(parseFloat(u.pag5_contador.value)+1)
					+"&cliente="+u.cliente.value+"&sucursal="+u.sucursal_hidden.value
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){
					consultaTexto("resTabla5","reportes_con.php?accion=6&contador="+(parseFloat(u.pag5_contador.value)-1)
					+"&cliente="+u.cliente.value+"&sucursal="+u.sucursal_hidden.value
					+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla5","reportes_con.php?accion=6&contador="+contador
				+"&cliente="+u.cliente.value+"&sucursal="+u.sucursal_hidden.value
				+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value
				+"&s="+Math.random());
				break;
		}
	}
	
	function imprimirRelacionEnvios(){
		//abrirVentanaFija('../../buscadores_generales/formaDeImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda');
		window.open("relacionEnviosExcel.php?cliente="+u.cliente.value
		+"&fechainicio="+u.fechainicio.value+"&fechafin="+u.fechafin.value
		+"&contador="+u.pag5_contador.value+"&sucursal="+u.sucursal_hidden.value+"&s="+Math.random())
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="610" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">RELACION DE ENVIOS POR CLIENTE</td>
    </tr>
    <tr>
      <td>
  <table width="550" border="0" cellspacing="0" cellpadding="0" align="center">  
  <tr>
    <td>Cliente:</td>
    <td><input name="cliente" type="text" class="Tablas" id="cliente" style="width:80px;" onkeypress="if(event.keyCode==13){obtenerCliente(this.value); document.getElementById('fechainicio').focus();}" />
      <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen2.php?funcion=obtenerCliente', 600, 450, 'ventana', 'Busqueda')" /></td>
    <td valign="bottom"><input name="nombre" type="text" class="Tablas" id="nombre" style="width:300px" readonly="" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  	<td>Sucursal:</td>
    <td colspan="2">
    	<input name="sucursal" value="" type="text" id="sucursal" style="width:250px" autocomplete="array:desc" onkeypress="if(event.keyCode==13){if(this.codigo!=null && this.value!=''){document.all.sucursal_hidden.value = this.codigo;}else{document.all.sucursal_hidden.value = '';}}" onblur="if(this.codigo!=null && this.value!=''){document.all.sucursal_hidden.value = this.codigo;}else{document.all.sucursal_hidden.value = '';}" />
    </td>
    <td><input type="hidden" name="sucursal_hidden" id="sucursal_hidden" /></td>
  </tr>
  <tr>
    <td width="70">Fecha Inicio: </td>
    <td width="136"><input name="fechainicio" type="text" class="Tablas" id="fechainicio" style="width:80px;" onkeypress="if(event.keyCode==13){validarFecha(this.value,'fechainicio'); document.getElementById('fechafin').focus();}"  />
      <img src="../img/calendario.gif" width="20" height="20" align="absbottom" onclick="displayCalendar(document.all.fechainicio,'dd/mm/yyyy',this)" style="cursor:pointer" /></td>
    <td width="329">Fecha Fin: 
      <input name="fechafin" type="text" class="Tablas" id="fechafin" style="width:80px;" onkeypress="if(event.keyCode==13){validarFecha(this.value,'fechafin');}" />
      <img src="../img/calendario.gif" width="20" height="20" align="absbottom" onclick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)" style="cursor:pointer"/></td>
    <td width="165"><div class="ebtn_Generar" onclick="obtenerDetalle()"></div></td>
  </tr>
  
  <tr>
    <td colspan="9">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">
		</table>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="9"><div id="paginado4" align="center" style="visibility:hidden">
              <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion4('primero')" /> 
			  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion4('atras')" /> 
			  <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion4('adelante')" /> 
			  <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion4('ultimo')" />
      	  <input type="hidden" name="pag5_total" />
          <input type="hidden" name="pag5_contador" value="0" />
          <input type="hidden" name="pag5_adelante" value="" />
          <input type="hidden" name="pag5_atras" value="" />
          </div></td>
  </tr>
  <tr>
    <td colspan="9"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15">&nbsp;</td>
        <td width="104">&nbsp;</td>
        <td width="99" align="center">&nbsp;</td>
        <td width="99" align="center">Total Gral:</td>
        <td width="104" align="center"><span class="style31">
          <input name="totalgeneral" type="text" class="Tablas" id="totalgeneral" readonly="" style="text-align:right;background-color:#FFFF99; width:100px;" />
        </span></td>
        <td width="94" align="center"><div class="ebtn_imprimir" onclick="imprimirRelacionEnvios()"></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="9">&nbsp;</td>
  </tr>
</table></td>
</tr>
</table>
</form>
</body>
</html>
