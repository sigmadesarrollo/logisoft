<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/funciones.js"></script>
<script src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script>
	var u = document.all;
	var tabla1 = new ClaseTabla();
	var mens = new ClaseMensajes();
	var pag1_cantidadporpagina = 30;
	
	mens.iniciar('../../javascript');
	
	tabla1.setAttributes({//PRINCIPAL
		nombre:"detalle",
		campos:[
			{nombre:"REFERENCIA", medida:100, alineacion:"left", datos:"folio"},
			{nombre:"ESTADO", medida:160, alineacion:"left", datos:"estado"},
			{nombre:"USUARIO", medida:160, alineacion:"left", datos:"empleado"},
			{nombre:"FECHA MODIFICACION", medida:115, alineacion:"center", datos:"fecha"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.modulo.focus();
	}
	
	function obtenerDetalle(){
		if(u.modulo.value=="0"){
			mens.show("A","Debe seleccionar Modulo","¡Atención!","modulo");
			return false;
		}
		
		if(u.referencia.value==""){
			mens.show("A","Debe capturar Referencia","¡Atención!","referencia");
			return false;
		}
		
		/*if(u.fechainicio.value == "" || u.fechainicio.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha inicio","¡Atención!","fechainicio");
			return false;
		}
		
		if(u.fechafin.value == "" || u.fechafin.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha fin","¡Atención!","fechafin");
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
			mens.show("A","La fecha fin debe ser mayor a la fecha inicio","¡Atención!","fechafin");
			return false;
		}*/
		consultaTexto("mostrarPrincipal","consultas.php?accion=1&referencia="+u.referencia.value
		+"&contador="+u.pag1_contador.value+"&modulo="+u.modulo.value+"&s="+Math.random());
	}
	
	function mostrarPrincipal(datos){
	//	mens.show("I",datos);
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;		
		if(obj.registros.length==0){
			mens.show("I","No se encontrarón datos con los criterios seleccionados","¡Atención!");
			tabla1.clear();
		}else{			
			tabla1.setJsonData(obj.registros);
		}
		if(obj.paginado==1){
			document.getElementById('paginado1').style.visibility = 'visible';
		}else{
			document.getElementById('paginado1').style.visibility = 'hidden';
		}
	}
	
	function paginacion1(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("mostrarPrincipal","consultas.php?accion=1&referencia="+u.referencia.value
				+"&contador="+u.pag1_contador.value+"&modulo="+u.modulo.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)
					+"&referencia="+u.referencia.value+"&modulo="+u.modulo.value+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)
					+"&referencia="+u.referencia.value+"&modulo="+u.modulo.value+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("mostrarPrincipal","consultas.php?accion=1&contador="+contador
				+"&referencia="+u.referencia.value+"&modulo="+u.modulo.value+"&s="+Math.random());
				break;
		}
	}
	
	function limpiar(){
		tabla1.clear();
		u.pag1_contador.value = 0;
		u.modulo.value = 0;
		u.referencia.value = "";
	}	
	
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Historial de Movimientos </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            
            <tr>
              <td width="12%">Modulo:</td>
              <td width="30%" colspan="2"><label>
                <select name="modulo" class="Tablas" id="modulo" style="text-transform:uppercase">
					<option value="0">Seleccionar Modulo</option>
					<option value="guiasventanilla">G. Ventanilla</option>
					<option value="guiasempresariales">G. Empresarial</option>
					<option value="facturacion">Facturación</option>
					<option value="propuestaconvenio">Propuesta Convenio</option>
					<option value="generacionconvenio">Generacion Convenio</option>
					<option value="solicitudcredito">Solicitud Credito</option>
                </select>
              </label></td>
              <td width="16%">Referencia:</td>
              <td width="21%" colspan="2"><input name="referencia" type="text" id="referencia" class="Tablas" onkeypress="if(event.keyCode==13){obtenerDetalle()}" /></td>
              <td width="21%"><div class="ebtn_Generar" onclick="obtenerDetalle()"></div></td>
            </tr>
            
          </table></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td><div id="paginado1" align="center" style="visibility:hidden"> <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion1('primero')" /> <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion1('atras')" /> <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion1('adelante')" /> <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion1('ultimo')" />
                <input type="hidden" name="pag1_total" />
                <input type="hidden" name="pag1_contador" value="0" />
                <input type="hidden" name="pag1_adelante" value="" />
                <input type="hidden" name="pag1_atras" value="" />
          </div></td>
        </tr>
        <tr>
          <td align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
