<?	session_start(); 
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$s = "SELECT CONCAT(prefijo,' - ',descripcion,':',id) AS descripcion 
	FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL];
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
	$fecha=date('d/m/Y'); 
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion,
	cs.id as sucursal FROM catalogosucursal cs
	ORDER BY cs.descripcion";	
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

<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ClaseTabla.js"></script>
<link href="../estilos_estandar.css" />
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var u=document.all;
	var v_suc = "<?=$_SESSION[IDSUCURSAL] ?>";
	
	jQuery(function($){
	   $('#fecha').mask("99/99/9999");
	   $('#fecha2').mask("99/99/9999");
	});
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:100, alineacion:"left", datos:"sucursal"},
			{nombre:"GUIAS", medida:100, alineacion:"left", datos:"guias"},
			{nombre:"VALOR DECLARADO", medida:200, alineacion:"right", tipo:"moneda",datos:"valordeclarado"},
			{nombre:"SEGURO", medida:100, alineacion:"right", tipo:"moneda",datos:"seguro"}
		],
		filasInicial:30,
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	function ObtenerDetalle(){	
		<?=$cpermiso->verificarPermiso(343,$_SESSION[IDUSUARIO]);?>
		if (u.sucursal.value==""){
			alerta("Debe capturar sucursal","¡Atención!","sucursal");
		}else if((u.fecha.value=="" || u.fecha.value=="__/__/____") || (u.fecha2.value=="" || u.fecha2.value=="__/__/____")){
			alerta("Debe capturar "+((u.fecha.value=="" || u.fecha.value=="__/__/____")? " fecha inicio" : "fecha fin"),"¡Atención!",((u.fecha.value=="" || u.fecha.value=="__/__/____")? "fecha" : "fecha2" ));
			return false;
		}
		
		var f1 = u.fecha.value.split("/");
		var f2 = u.fecha2.value.split("/");		
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
		
		if(f2 < f1){
			alerta("La fecha final debe ser mayor ala fecha de inicio","¡Atención!","fecha2");
		}else{			
			if(u.sucursal_hidden.value == undefined || u.sucursal_hidden.value == "undefined" || u.sucursal_hidden.value == "no"){
				u.sucursal_hidden.value = v_suc;
			}
			
			var sucursal = "&sucursal="+u.sucursal_hidden.value;
			var fecha 	 = "&fecha="+u.fecha.value;
			var fecha2 	 = "&fecha2="+u.fecha2.value;
			consultaTexto("mostrardetalle","valordeclarado_con.php?accion=1"+sucursal+fecha+fecha2);	
		}
	}
	
	function mostrardetalle(datos){
		if (datos.indexOf("no encontro")<0) {
			try{
				var objeto = eval(convertirValoresJson(datos));
				tabla1.setJsonData(objeto);	
			}catch(e){
				alerta3(datos);
			}
		}else{
			alerta("No existieron datos con los filtros seleccionados","¡Atención!","sucursal");
		}
	}
	
	function obtenerSucursal(id){	
		u.sucursal_hidden.value	= id;
		consultaTexto("mostrarSucursal","valordeclarado_con.php?accion=2&sucursal="+id);
	}
	
	function mostrarSucursal(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.sucursal.value = obj;
		}
	}

	function BuscarSucursal(){
		abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 550, 450, 'ventana', 'Busqueda');
	}
	
	function imprimirReporte(tipo){
		<?=$cpermiso->verificarPermiso(383,$_SESSION[IDUSUARIO]);?>
		if(tabla1.getRecordCount()==0){
			alerta3("No existen datos en el detalle","¡Atención!");
			return false;
		}
		if(tipo == "Archivo"){
		
			if(document.URL.indexOf("web/")>-1){		
			window.open("http://www.pmmintranet.net/web/reportes/ValorDeclarado.php?fechainicio="+u.fecha.value
			+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
		
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/reportes/ValorDeclarado.php?fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("http://www.pmmintranet.net/web_pruebas/reportes/ValorDeclarado.php?fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
			}
		
			
		}else{
			if(document.URL.indexOf("web/")>-1){		
				window.open("http://www.pmmintranet.net/web/fpdf/reportes/valorDeclarado.php?fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
						
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("http://www.pmmintranet.net/web_capacitacion/fpdf/reportes/valorDeclarado.php?fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
			
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				window.open("http://www.pmmintranet.net/web_pruebas/fpdf/reportes/valorDeclarado.php?fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
			}
		}
	}
	
	var desc = new Array(<?php echo $desc; ?>);
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
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<br>
<table width="535" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="553" class="FondoTabla Estilo4">VALOR DECLARADO</td>
  </tr>
  <tr>
    <td><table width="542" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="440"><div align="left">Sucursal: <span class="Tablas">
          <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:150px" value="<?=$f->descripcion ?>" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onblur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}" />
          </span> <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onclick="BuscarSucursal()" />
          <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="<?=$_SESSION[IDSUCURSAL] ?>" />
          </span>
        </div>
          <div align="left"></div></td>
        <td width="102">&nbsp;</td>
      </tr>
      <tr>
        <td>Fecha Inicio:
          <input name="fecha"  type="text" id="fecha" value="<?=$fecha ?>"  />
          <span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></span>
          </select>
Fecha Fin:
<input name="fecha2"  type="text" id="fecha2" value="<?=$fecha ?>" />
<span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)" /></span></td>
        <td><div align="center"><span class="Estilo6 Tablas"><img id="img_refrescar" src="../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onclick="ObtenerDetalle();" /></span></div></td>
      </tr>
      
      
      <tr>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><table width="578" id="detalle" border="0" cellpadding="0" cellspacing="0">
          </table></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center"></td>
        <td><div align="center"><span class="Estilo6 Tablas"><img id="img_refrescar" src="../img/Boton_Imprimir.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('../buscadores_generales/formaImpresion.php?funcion=imprimirReporte', 300, 230, 'ventana', 'Busqueda')" /></span></div></td>
      </tr>
      <tr >      </tr>
      <tr>      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>