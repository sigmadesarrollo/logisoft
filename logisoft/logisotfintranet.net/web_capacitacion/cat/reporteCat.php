<?	session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion FROM catalogosucursal cs
	ORDER BY cs.descripcion";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.utf8_decode($desc);
		}
		$desc = substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/jquery-1.4.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/funciones.js"></script>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var mens = new ClaseMensajes();
	var pag1_cantidadporpagina = 30;
	mens.iniciar('../javascript');
	
	jQuery(function($){
	   $('#fechainicio').mask("99/99/9999");
	   $('#fechafin').mask("99/99/9999");
	});
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:60, alineacion:"center", datos:"sucursal"},
			{nombre:"FECHA", medida:60, alineacion:"left", datos:"fechaqueja"},
			{nombre:"TIPO QUEJA", medida:80, alineacion:"left", datos:"queja"},
			{nombre:"F. ATENCION", medida:80, alineacion:"center", datos:"folioatencion"},
			{nombre:"GUIA", medida:75, alineacion:"left", datos:"guia"},
			{nombre:"RECOLECCION", medida:70, alineacion:"left", datos:"recoleccion"},
			{nombre:"FOLIO QUEJA", medida:70, alineacion:"left", datos:"folioqueja"},
			{nombre:"QUIEN LEVANTA LA QUEJA", medida:150, alineacion:"left", datos:"nombre"}
		],

		filasInicial:20,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});	
	
	window.onload = function(){
		tabla1.create();
		u.sucursal.select();
	}
	
	function obtenerDetalle(){
		if(u.todas.checked == false){
			if(u.sucursal.value == ""){
				mens.show('A','Debe capturar Sucursal','¡Atención!','sucursal');
				return false;
			}
		}
		
		if(u.fechainicio.value=="" || u.fechainicio.value=="__/__/____"){
			mens.show('A','Debe capturar Fecha inicial','¡Atención!','fechainicio');
			return false;
		}
		
		if(u.fechafin.value=="" || u.fechafin.value=="__/__/____"){
			mens.show('A','Debe capturar Fecha fin','¡Atención!','fechafin');
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
			mens.show('A','La fecha final no debe ser menor que la fecha inicial','¡Atención!','fechafin');			
		}else{
			consultaTexto("resTabla1","reporteCat_con.php?accion=1&fechainicio="+u.fechainicio.value
			+"&fechafin="+u.fechafin.value+"&contador="+u.pag1_contador.value
			+"&estado="+u.estado.value+"&sucursal="+u.sucursal_hidden.value+"&todas="+((u.todas.checked==true)?1:0)
			+"&s="+Math.random());
		}
	}
	
	function resTabla1(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		if(obj.registros.length==0){
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla1.clear();
		}else{			
			tabla1.setJsonData(obj.registros);
		}
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","reporteCat_con.php?accion=1&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","reporteCat_con.php?accion=1&fechainicio="+u.fechainicio.value
					+"&fechafin="+u.fechafin.value+"&contador="+(parseFloat(u.pag1_contador.value)+1)+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","reporteCat_con.php?accion=1&fechainicio="+u.fechainicio.value
					+"&fechafin="+u.fechafin.value+"&contador="+(parseFloat(u.pag1_contador.value)-1)+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","reporteCat_con.php?accion=1&fechainicio="+u.fechainicio.value
				+"&fechafin="+u.fechafin.value+"&contador="+contador+"&s="+Math.random());
				break;
		}
	}
	
	function limpiar(){
		tabla1.clear();
		u.pag1_total.value 		= "";
		u.pag1_contador.value 	= 0;
		u.pag1_adelante.value 	= "";
		u.pag1_atras.value 		= "";
		u.fechainicio.value		= '<?=date('d/m/Y') ?>';
		u.fechafin.value		= '<?=date('d/m/Y') ?>';
		u.sucursal.value 		= "";
		u.sucursal_hidden.value = "";
		u.todas.checked 		= false;
		u.estado.value			= 0;
		u.sucursal.select();
	}
	var desc = new Array(<?php echo $desc; ?>);
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="615" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">REPORTE CENTRO ATENCION TELEFONICA </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="9%">Sucursal:</td>
          <td colspan="2"><span class="Tablas">
            <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:200px" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value=this.codigo;}" onblur="if(this.value!=''){document.all.sucursal_hidden.value = this.codigo; if(this.codigo==undefined){document.all.sucursal_hidden.value ='no'}}">
          </span></td>
          <td width="15%" valign="middle"><input name="todas" type="checkbox" id="todas" onclick="if(!this.checked){document.all.sucursal.disabled=false;}else{document.all.sucursal.disabled=true; document.all.sucursal.value=''}" value="todas" />
Todas</td>
          <td width="38%" colspan="2">Estado:
            <label>
            <select name="estado" id="estado" class="Tablas">
			<option value="0">AMBOS</option>
			<option value="POR SOLUCIONAR">POR SOLUCIONAR</option>
			<option value="SOLUCIONADO">SOLUCIONADO</option>
            </select>
            </label></td>
          </tr>
        <tr>
          <td colspan="6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="9%">F. Inicial: </td>
              <td width="22%"><input name="fechainicio" type="text" id="fechainicio" value="<?=date('d/m/Y') ?>" class="Tablas" style="width:80px" />
                <span class="Tablas"><img src="../img/calendario.gif" width="20" height="20" align="absbottom" style="cursor:pointer" onclick="displayCalendar(document.all.fechainicio,'dd/mm/yyyy',this)" /></span></td>
              <td width="9%">F. Final: </td>
              <td width="23%"><input name="fechafin" type="text" id="fechafin" value="<?=date('d/m/Y') ?>" class="Tablas" style="width:80px" />
                <span class="Tablas"><img src="../img/calendario.gif" width="20" height="20" align="absbottom" style="cursor:pointer" onclick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)" /></span></td>
              <td width="15%"><img src="../img/Boton_Generar.gif" width="74" height="20" onclick="obtenerDetalle()" style="cursor:pointer" /></td>
              <td width="22%"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
            </tr>
          </table></td>
          </tr>
        
        <tr>
          <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
          </tr>
        <tr>
          <td colspan="6"><div id="paginado" align="center" style="visibility:hidden">              
              <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> 
			  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> 
			  <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('adelante')" /> 
			  <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion('ultimo')" />
		  <input type="hidden" name="pag1_total" />
          <input type="hidden" name="pag1_contador" value="0" />
          <input type="hidden" name="pag1_adelante" value="" />
          <input type="hidden" name="pag1_atras" value="" />
          <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" />
          </div></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
