<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) as descripcion from catalogosucursal cs";	
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>
<script>

	var u = document.all;
	var mensaje = "";
	var mens = new ClaseMensajes();
	var tabla1 	= new ClaseTabla();
	mens.iniciar("../javascript");

	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"CERRAR", medida:70, tipo:"checkbox", alineacion:"center", datos:"sel"},
			{nombre:"FECHA", medida:180, alineacion:"center", datos:"fecha"},
			{nombre:"USUARIO", medida:4, tipo:"oculto", alineacion:"center", datos:"usuariocaja"},
			{nombre:"MODULO", medida:4, tipo:"oculto", alineacion:"center", datos:"modulo"},
			{nombre:"SUCURSAL", medida:4, tipo:"oculto", alineacion:"center", datos:"sucursal"},
			{nombre:"CAJA", medida:4, tipo:"oculto", alineacion:"center", datos:"caja"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.sucursal.focus();
	}

	function obtenerFechas(){
		if(u.sucursal.value == "" || u.h_sucursal.value==null || u.h_sucursal.value==undefined){
			mens.show("A","Debe capturar Sucursal","¡Atención!","sucursal");
			return false;
		}
		
		if(u.modulos.value=="0"){
			mens.show("A","Debe seleccionar Modulo","¡Atención!","modulos");
			return false;
		}
		
		consultaTexto("mostrarFechas","cierrecaja_con.php?accion=7&modulo="+u.modulos.value
		+"&sucursal="+u.h_sucursal.value+"&s="+Math.random());
	}
	
	function mostrarFechas(datos){
		if(datos.indexOf("noencontro")<0){
			var obj = eval(datos);
			tabla1.setJsonData(obj);
		}else{
			mens.show("A","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla1.clear();
		}
	}

	function cerrarModulo(){
		if(tabla1.getRecordCount()==0){
			mens.show("A","No existen datos en el detalle","¡Atención!");
			return false;	
		}
		var seleccion = "";
		var v_modulo = "";
		for(var i=0;i<tabla1.getRecordCount();i++){
			if(u["detalle_CERRAR"][i].checked == true){			
				seleccion += u["detalle_FECHA"][i].value+","+u["detalle_USUARIO"][i].value+","+u["detalle_SUCURSAL"][i].value
				+","+u["detalle_MODULO"][i].value+","+u["detalle_CAJA"][i].value+":";
			}
		}
		
		if(seleccion.indexOf("cierrecaja")>-1){
			v_modulo = "cierrecaja";
		}else if(seleccion.indexOf("cierreprincipal")>-1){
			v_modulo = "cierreprincipal";
		}else if(seleccion.indexOf("cierredia")>-1){
			v_modulo = "cierredia";	
		}else if(seleccion.indexOf("evaluacion")>-1){
			v_modulo = "evaluacion";	
		}
		
		seleccion = seleccion.substring(0,seleccion.length-1);
		if(seleccion.indexOf(":")>-1)
			var v_trae="s";
		else
			var v_trae="n";

		consultaTexto("cerro","cierrecaja_con.php?accion=8&datos="+seleccion+"&modulo="+v_modulo+"&trae="+v_trae+"&s="+Math.random());
	}

	function cerro(datos){
		if(datos.indexOf("ok")>-1){			
			mens.show("I","El cierre se registro correctamente","¡Atención!");
			u.btn_aceptar.style.visibility = "hidden";
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
			u.btn_aceptar.style.visibility = "visible";
		}
	}

	function limpiar(){
		u.sucursal.value = "";
		u.h_sucursal.value = "";
		u.modulos.value = "0";
		u.btn_aceptar.style.visibility = "visible";
		tabla1.clear();
		u.sucursal.focus();
	}

	function obtenerSucursal(suc){
		u.h_sucursal.value = suc;
		consultaTexto("mostrarSucursal","../reportesWeb/reportes_con.php?accion=4&sucursal="+suc);
	}
	
	function mostrarSucursal(datos){
		var obj = eval(datos);
		u.sucursal.value = obj.sucursal;
	}
	
	var desc = new Array(<?php echo $desc; ?>);
	
</script>
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">MODULO DE CIERRES </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="21%">Sucursal:</td>
              <td colspan="2"><input name="sucursal" type="text" id="sucursal" style="width:200px" class="Tablas" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.getElementById('h_sucursal').value = this.codigo;}" onblur="document.getElementById('h_sucursal').value = this.codigo;"/>
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td>Modulo:</td>
              <td width="47%">
			  <select name="modulos" class="Tablas" id="modulos">
                <option value="0" selected="selected">Seleccionar</option>
                <option value="evaluacion">Evaluación Mercancia</option>
				<option value="cierrecaja">Cierre de Caja</option>
                <option value="cierreprincipal">Cierre Principal</option>
                <option value="cierredia">Cierre de D&iacute;a</option>
              </select></td>
              <td width="32%"><div class="ebtn_Generar" onclick="obtenerFechas()"></div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2" align="right">&nbsp;</td>
            </tr>
            <tr>
              <td><input name="h_sucursal" type="hidden" id="h_sucursal" /></td>
              <td colspan="2" align="right">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
          </table></td>
          <td width="48%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle"></table></td>
        </tr>
      </table></td>
          </tr>
        <tr>
          <td width="57%">&nbsp;</td>
          <td width="43%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="63%" align="right"><div id="btn_aceptar" class="ebtn_aceptar" onclick="cerrarModulo()"></div></td>
              <td width="37%" align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  </form>
</body>
</html>
