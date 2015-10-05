<?	session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date("d/m/Y");
	
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	
	jQuery(function($){		
		$('#fechade').mask("99/99/9999");
		$('#fechaa').mask("99/99/9999");
	});
	
	var tabla1 	= new ClaseTabla();
	var	u		= document.all;
	var v_suc	= "<?=$_SESSION[IDSUCURSAL] ?>";
	var mens	= new ClaseMensajes();
	mens.iniciar('../javascript');
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:150, alineacion:"left", datos:"dessucursal"},
			{nombre:"ENT. EL MISMO DÍA", medida:100, alineacion:"center", datos:"undiaead"},
			{nombre:"ENT. MAS DE 2 DÍAS", medida:100, alineacion:"center", datos:"dosdiaead"},
			{nombre:"GUÍAS PEND. ENTREGA", medida:100, alineacion:"center",  datos:"faltanteead"},
			{nombre:"ENT_EL MISMO DÍA", medida:100, alineacion:"center", datos:"undiarec"},
			{nombre:"ENT_MAS DE 2 DÍAS", medida:100, alineacion:"center", datos:"dosdiasrec"},
			{nombre:"GUÍAS_PEND. ENTREGA", medida:100, alineacion:"center",  datos:"faltanterec"},
			{nombre:"ENT. MAS DE 3 DÍAS", medida:100, alineacion:"center", datos:"tresdiasocurre"},
			{nombre:"GUÍAS PEND. ENTREGA", medida:100, alineacion:"center",  datos:"faltanteocurre"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		//eventoDblClickFila:"verRecoleccion()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}
	function obtenerDetalle(){
		if(u.todas.checked == true){
			if(u.fechade.value == "" || u.fechade.value == "__/__/____"){
				mens.show('A','Debe capturar Fecha inicio','¡Atención!','fechade');
			
			}
			if(u.fechaa.value == ""){
				mens.show('A','Debe capturar Fecha fin','¡Atención!','fechaa');
			
			}
			var f1 = u.fechade.value.split("/");
			var f2 = u.fechaa.value.split("/");
			
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
			
			if(u.fechade.value > u.fechaa.value){
				mens.show('A','La fecha fin no debe ser menor a la fecha inicio','¡Atención!','fechaa');			
			}	
			
			consultaTexto("mostrarDetalle","reporteProductividad_con.php?accion=3&fechade="+u.fechade.value
			+"&fechaa="+u.fechaa.value+"&sucursal=todas");	
/*			mens.show("A","mostrarDetalle","reporteProductividad_con.php?accion=3&fechade="+u.fechade.value
			+"&fechaa="+u.fechaa.value+"&sucursal=todas");*/
		}else{
			
			if(u.sucursal_hidden.value==undefined || u.sucursal.value == ""){
				mens.show('A','Debe capturar Sucursal','¡Atención!','sucursal');
				
			}else if(u.fechade.value == ""){
				mens.show('A','Debe capturar Fecha inicio','¡Atención!','fechade');
				
			}else if(u.fechaa.value	 == ""){
				mens.show('A','Debe capturar Fecha fin','¡Atención!','fechaa');
				
			}else if(u.fechade.value > u.fechaa.value){
				mens.show('A','La fecha fin no debe ser menor a la fecha inicio','¡Atención!','fechaa');
				
			}else{
			
			if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no"){
				u.sucursal_hidden.value = v_suc;
			}
			
			consultaTexto("mostrarDetalle","reporteProductividad_con.php?accion=3&fechade="+u.fechade.value
			+"&fechaa="+u.fechaa.value+"&sucursal="+u.sucursal_hidden.value);
			/*mens.show("A","mostrarDetalle","reporteProductividad_con.php?accion=3&fechade="+u.fechade.value
			+"&fechaa="+u.fechaa.value+"&sucursal="+u.sucursal_hidden.value);*/
			}
		}
	}
	function mostrarDetalle(datos){
		
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.clear();
			tabla1.setJsonData(obj);
		}else{
			mens.show("A","No se encontraron datos con los criterios seleccionados","¡Atención!");	
			tabla1.clear();
		}	
	}	
	function obtenerSucursal(id){
		u.sucursal_hidden.value = id;
		consultaTexto("mostrarSucursal","reporteProductividad_con.php?accion=2&sucursal="+id);
	}
	function mostrarSucursal(datos){		
		u.sucursal.value = convertirValoresJson(datos);
	}
	var desc = new Array(<?php echo $desc; ?>);
</script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
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
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">REPORTE DE PRODUCTIVIDAD</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="Tablas">Sucursal:
          <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_SESSION[IDSUCURSAL] ?>"></td>
        <td colspan="3" class="Tablas"><input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:220px" value="<?=utf8_decode($fs->descripcion) ?>" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onBlur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}"
        /></td>
        <td class="Tablas"><img src="../img/Buscar_24.gif" align="absbottom" style="cursor:pointer" onclick=            "if(!document.all.todas.checked){abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 550, 470, 'ventana', 'Busqueda');}">&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="todas" type="checkbox" value="todas" style="width:13px" onClick="if(!this.checked){document.all.sucursal.style.backgroundColor=''; document.all.sucursal.readOnly=false;}else{ document.all.sucursal.style.backgroundColor='#FFFF99';document.all.sucursal.value='';document.all.sucursal.readOnly=true;}"> 
          Todas</td>
        <td width="54" class="Tablas">Fecha:</td>
        <td width="284" class="Tablas"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/></td>
      </tr>
      <tr>
        <td width="101">Periodo de:</td>
        <td width="136"><input name="fechade" type="text" class="Tablas" id="fechade" style="width:80px;" value="<?=date('d/m/Y') ?>"  />
          <span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fechade,'dd/mm/yyyy',this)" /></span></td>
        <td width="31">Al:</td>
        <td width="81"><input name="fechaa" type="text" class="Tablas" id="fechaa" style="width:80px;" value="<?=date('d/m/Y') ?>" /></td>
        <td width="112"><span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fechaa,'dd/mm/yyyy',this)" /></span></td>
        <td colspan="2"><div class="ebtn_Generar" onClick="obtenerDetalle()"></div></td>
        </tr>      
      <tr>
        <td colspan="7"><div id="txtDir" style=" height:290px; width:799px; overflow:auto" align=left><table width="850" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="FondoTabla"><font color="#016193">SUCURSAL</font></td>
    <td align="center" width="220" class="FondoTabla">EAD</td>
    <td align="center" width="220" class="FondoTabla">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RECOLECCION</td>
    <td align="center" width="200" class="FondoTabla">OCURRE</td>
  </tr>
  <tr>
    <td colspan="4"><table width="850" id="detalle" border="0" cellspacing="0" cellpadding="0">          
        </table></td>
  </tr>
</table></div></td>
      </tr>
      <tr>
        <td colspan="7" align="center"></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>
