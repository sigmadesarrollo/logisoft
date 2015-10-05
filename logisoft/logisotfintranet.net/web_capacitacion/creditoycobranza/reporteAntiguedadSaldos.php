<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion) AS descripcion
	FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL]."";	
	$r = mysql_query($s,$l) or die($s); $fs = mysql_fetch_object($r);
	
	
	$s = "SELECT CONCAT(prefijo,' - ',descripcion,':',id) AS descripcion
	FROM catalogosucursal ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}		
		$desc = substr($desc, 0, -1);
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>

<script>

	jQuery(function($){
	   $('#fecha').mask("99/99/9999");
	});

	var u = document.all;
	var v_suc	= "<?=$_SESSION[IDSUCURSAL] ?>";
	
	function devolverSucursal(){		
		if(u.sucursal_hidden.value==""){
			setTimeout("devolverSucursal()",500);
		}
		u.criterio[0].checked = true;
		u.criterio[0].focus();
		habilitarCliente();
	}
	function obtenerClienteBusqueda(cliente){
		u.cliente.value = cliente;
		consultaTexto("mostrarCliente","consultasReportes.php?accion=1&cliente="+cliente);		
	}
	function obtenerCliente(cliente){
		consultaTexto("mostrarCliente","consultasReportes.php?accion=1&cliente="+cliente);	
	}
	function mostrarCliente(datos){		
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			u.nombre.value = row[1];
		}else if(datos=="0"){
			alerta("El codigo del cliente no existe","¡Atencion!","cliente");
		}
	}
	function validarCliente(e,obj){		
		tecla = (u) ? e.keyCode : e.which;
	    if((tecla == 8 || tecla == 46) && document.getElementById(obj).value==""){
			u.nombre.value = "";
		}
	}
	function habilitarCliente(){
		if(u.criterio[0].checked == true){
			u.cliente.style.backgroundColor='';
			u.cliente.readOnly = false;
			u.cliente.focus();
			
		}else if(u.criterio[1].checked == true){
			u.cliente.value = "";
			u.nombre.value	= "";
			u.cliente.style.backgroundColor='#FFFF99';
			u.cliente.readOnly = true;
		}
	}
	function generar(){
		if(u.sucursal.value == ""){
			alerta("Debe capturar Sucursal","¡Atención!","sucursal");
			return false;
		}
		if(u.criterio[0].checked == true){
			if(u.cliente.value == ""){
				alerta("Debe capturar Cliente","¡Atención!","cliente");
				return false;
			}
		}
		
		if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no"){
			u.sucursal_hidden.value = v_suc;
		}		
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
</script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="431" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="398" class="FondoTabla Estilo4">REPORTE DE ANTIG&Uuml;EDAD DE SALDOS</td>
  </tr>
  <tr>
    <td height="37"><table width="430" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="11" colspan="5"><label>
            <div align="right">Sucursal:
              <span class="Tablas">              
<input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:220px" value="<?=$fs->descripcion ?>" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onBlur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}"/>
              </span>
              <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$sucursal_hidden ?>">
            </div>
          </label></td>
      </tr>
      <tr>
        <td colspan="5" class="FondoTabla" >Criterio de Selecci&oacute;n de Clientes</td>
      </tr>
      <tr>
        <td width="20" height="24"><input name="criterio" type="radio" value="1" onClick="habilitarCliente()"></td>
        <td width="62"><label></label>
          Cliente</td>
        <td colspan="3"><label><span class="Tablas">
              <input name="cliente" type="text" class="Tablas" id="cliente" onKeyPress="if(event.keyCode==13){obtenerCliente(this.value);}" onKeyUp="return validarCliente(event,this.name);"  style="width:80px; background:#FFFF99" readonly="" value="<?=$cliente ?>" />
              <img src="../img/Buscar_24.gif" name="img_cliente" width="24" height="23" align="absbottom" id="img_cliente" style="cursor:pointer" onClick="if(document.all.criterio[0].checked == true){abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda', 625, 418, 'ventana', 'Busqueda');}">
          <input name="nombre" type="text" class="Tablas" id="nombre" readonly="" style="width:200px;background:#FFFF99" value="<?=$nombre ?>"/>
        </span></label></td>
      </tr>
      <tr>
        <td height="11"><label>
          <input name="criterio" type="radio" value="0" onClick="habilitarCliente()">
        </label></td>
        <td height="11">Todos</td>
        <td height="11" colspan="3" >&nbsp;</td>
      </tr>
      <tr>
        <td height="11" colspan="5" class="FondoTabla">Contenido del reporte </td>
      </tr>
      <tr>
        <td height="11"><label>
          <input name="contenido" type="radio" value="1">
        </label></td>
        <td height="11">Resumen</td>
        <td width="51" height="11">&nbsp;</td>
        <td width="20"><label>
          <input name="contenido" type="radio" value="0">
        </label></td>
        <td width="245">Detalle por factura</td>
      </tr>
      <tr>
        <td height="11"><label>
          <input name="incluir" type="checkbox" id="incluir" value="1">
        </label></td>
        <td height="11" colspan="4">Incluir Clientes Dados de Baja </td>
      </tr>
      <tr>
        <td height="11"><label>
          <input name="corte" type="checkbox" id="corte" value="1">
        </label></td>
        <td height="11" colspan="4">Corte de Hoja por Cliente </td>
      </tr>
      <tr>
        <td height="11" colspan="5">&nbsp;</td>
      </tr>
      <tr>
        <td height="11" colspan="5">A la Fecha:
          <label>
          <input name="fecha" class="Tablas"  type="text" id="fecha" value="<?=$fecha ?>" >
          <span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></span></label></td>
      </tr>
      <tr>
        <td height="11" colspan="5"><div align="right"><img src="../img/Boton_Generar.gif" width="74" height="20" style="cursor:pointer" onClick="generar()"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'REPORTE DE ANTIGÜEDAD DE SALDOS';
</script>
</html>