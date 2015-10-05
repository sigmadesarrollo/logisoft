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
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>	
	var u = document.all;
	var pag1_cantidadporpagina = 30;
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript');
	var v_Sucursal = '<?=$_SESSION[IDSUCURSAL] ?>';
	window.onload = function(){
		u.embarque.focus();
	}
	
	function obtenerEmbarque(embarque){
		u.embarque.value = embarque;
		consultaTexto("mostrarEmbarque","reportes_con.php?accion=3&embarque="+embarque+"&sucursal="+u.sucursal_hidden.value+"&val="+Math.random());
	}
	
	function mostrarEmbarque(datos){
		//mens.show("I",datos,"");
		if(datos.indexOf("no existe")<0){		
			var obj = eval(convertirValoresJson(datos));
			u.unidad.value = obj.unidad;
			u.destinos.value = obj.destinos;
			u.fecha.value = obj.fecha;
		}else{
			mens.show("A","El folio de embarque capturado no existe","¡Atención!","embarque");
		}
	}
	
	function imprimirEmbarque(){
		u.sucursal_hidden.value = ((u.sucursal_hidden.value=='undefined')? v_Sucursal : u.sucursal_hidden.value);
		
		if(u.sucursal_hidden.value=="" || u.sucursal_hidden.value=='undefined'){
			mens.show("A","Seleccione una sucursal v&aacute;lida","¡Atenci&oacute;n!");
			return false;
		}
		if(u.tipoembarque[0].checked){
			if(u.embarque.value == ""){
				mens.show("A","Seleccione el folio del embarque","¡Atención!");
				return false;
			}
		}
		
		var tipoRelacion = "";
		if(u.tipoembarque[1].checked==true){
			u.fecha.value = u.inicio.value;
			tipoRelacion = "relacionEmbarque_noguardada.php";
		}else{
			tipoRelacion = "relacionEmbarque.php";
		}
		var laUrl = document.URL;
			if(laUrl.indexOf('pmmintranet.net')>-1){
				var direccion = laUrl.substr(0,laUrl.indexOf('pmmintranet.net')+15+((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacionPruebas')>-1)?25:18)));
			}else{
				var direccion = laUrl.substr(0,laUrl.indexOf('pmmintranet.com')+15+((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacionPruebas')>-1)?25:18)));
			}
		
			window.open(direccion+"fpdf/reportes/"+tipoRelacion+"?sucursal="+u.sucursal_hidden.value
			+"&folio="+u.embarque.value+"&unidad="+u.unidad.value+"&fechaembarque="+u.fecha.value+"&destino="+u.destinos.value+"&val="+Math.random());
	}
	
	function obtenerSucursal(sucursal){
		u.sucursal_hidden.value = sucursal;
		consultaTexto("mostrarSucursal","reportes_con.php?accion=4&sucursal="+sucursal+"&val="+Math.random());
	}
	
	function mostrarSucursal(datos){
		var obj = eval(convertirValoresJson(datos));
		u.sucursal.value = obj.sucursal;
	}
	
	function seleccionaTipo(tipo){
		if(tipo==0){
			document.getElementById('imgSeleccionoEmbarque').style.display='';
			u.embarque.value = '';
			u.embarque.readOnly=false;
			u.embarque.style.backgroundColor='';
			document.getElementById('calendarioInicio').style.display='none';
		}else{
			document.getElementById('imgSeleccionoEmbarque').style.display='none';
			u.embarque.value = '';
			u.embarque.readOnly=true;
			u.embarque.style.backgroundColor='#FFFF99';
			document.getElementById('calendarioInicio').style.display='';
		}
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="610" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Reporte Relaci&oacute;n de Embarque </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="9%">Sucursal:</td>
          <?
		  	$s = "select concat(cs.prefijo,' - ',cs.descripcion) sucursal
			from catalogosucursal cs
			where cs.id = $_SESSION[IDSUCURSAL]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			
		  ?>
          <td width="44%"><input name="sucursal" class="Tablas" value="<?=$f->sucursal?>" type="text" id="sucursal" style="width:250px" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; document.all.embarque.focus();}" onblur="document.all.sucursal_hidden.value = this.codigo; document.all.embarque.focus();" onfocus="this.value=''" /></td>
          <td width="8%"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');"  /></td>
          <td width="14%">Folio Embarque: </td>
          <td width="19%"><label>
            <input name="embarque" type="text" id="embarque" class="Tablas" onkeypress="if(event.keyCode==13){if(document.getElementById('sucursal_hidden').value==null || document.getElementById('sucursal_hidden').value==undefined || document.getElementById('sucursal').value==''){mens.show('A','Debe capturar Sucursal','¡Atención!','sucursal')}else{obtenerEmbarque(this.value);}}" onblur="if(this.value!=''){if(document.getElementById('sucursal_hidden').value==null || document.getElementById('sucursal_hidden').value==undefined || document.getElementById('sucursal').value==''){mens.show('A','Debe capturar Sucursal','¡Atención!','sucursal')}else{obtenerEmbarque(this.value);}}" />
          </label></td>
          <td width="6%"><img id="imgSeleccionoEmbarque" src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="if(document.getElementById('sucursal_hidden').value==null || document.getElementById('sucursal_hidden').value==undefined || document.getElementById('sucursal').value==''){mens.show('A','Debe capturar Sucursal','¡Atención!','sucursal');}else{abrirVentanaFija('../buscadores_generales/buscarFoliosEmbarque.php?funcion=obtenerEmbarque&tipo=3&sucursal='+document.getElementById('sucursal_hidden').value, 600, 450, 'ventana', 'Busqueda');}" /></td>
        </tr>
        <tr>
          <td align="left">Tipo:</td>
          <td align="left">
          	<table width="255" border="0" cellpadding="0" cellspacing="0">
          		<tr>
                	<td width="29"><input type="radio" name="tipoembarque" onclick="seleccionaTipo(0)" checked="checked" /></td>
                    <td width="147">Embarcado</td>
                    <td width="26"><input type="radio" name="tipoembarque" onclick="seleccionaTipo(1)" /></td>
                    <td width="53">Del dia</td>
                </tr>
          	</table>
          </td>
          <td align="left">Fecha</td>
          <td align="left"><input name="inicio" type="text" class="Tablas" id="inicio" readonly="readonly" style="width:65px; background-color:#FFFF99; border:1px #CCC solid;" value="<?=date('d/m/Y') ?>" /></td>
          <td align="left"><img src="../img/calendario.gif" id="calendarioInicio" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer; display:none" title="Calendario" onclick="displayCalendar(document.all.inicio,'dd/mm/yyyy',this)" /></td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="right"><div class="ebtn_imprimir" onclick="imprimirEmbarque()"></div></td>
        </tr>
        <tr>
          <td colspan="6">
            <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_SESSION[IDSUCURSAL]?>" />
            <input name="unidad" type="hidden" id="unidad" />
            <input name="destinos" type="hidden" id="destinos" />
            <input name="fecha" type="hidden" id="fecha" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
