<?	session_start(); require_once('../Conectar.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script>
	
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript');
	
	window.onload = function(){
		u.empleado.focus();
	}
	
	function obtenerEmpleado(id){
		u.empleado.value = id;
		consultaTexto("mostrarEmpleado","cierrecaja_con.php?accion=9&empleado="+id+"&s="+Math.random());
	}
	
	function mostrarEmpleado(datos){
		if(datos.indexOf("noencontro")<0){
			var obj = eval(datos);
			u.nombre.value = obj.nombre;
		}else{
			mens.show("A","El numero de empleado no existe","¡Atención!");
			u.empleado.value = "";
			u.nombre.value = "";
		}
	}

	function generarReporte(){
		<?=$cpermiso->verificarPermiso(454,$_SESSION[IDUSUARIO]) ?>
		if(u.empleado.value == ""){
			mens.show("A","Debe capturar empleado","¡Atención!","empleado");
			return false;
		}
		
		consultaTexto("mostrarReporte","cierrecaja_con.php?accion=10&empleado="+u.empleado.value
		+"&fecha="+u.fecha.value+"&s="+Math.random());
	}
	
	function mostrarReporte(datos){
		if(datos.indexOf("noencontro")<0){
			var obj = eval(datos);
			u.efectivo.value 		= obj.efectivo;
			u.efectivo.value 		= "$ "+numcredvar(u.efectivo.value);
			
			u.tarjeta.value 		= obj.tarjeta;			
			u.tarjeta.value			= "$ "+numcredvar(u.tarjeta.value);
			
			u.transferencia.value 	= obj.transferencia;
			u.transferencia.value 	= "$ "+numcredvar(u.transferencia.value);
			
			u.cheque.value 			= obj.cheque;
			u.cheque.value 			= "$ "+numcredvar(u.cheque.value);
			
			u.refectivo.value 		= obj.refectivo;
			u.refectivo.value 		= "$ "+numcredvar(u.refectivo.value);
			
			u.rtarjeta.value 		= obj.rtarjeta;
			u.rtarjeta.value		= "$ "+numcredvar(u.rtarjeta.value);
			
			u.rtransferencia.value 	= obj.rtransferencia;
			u.rtransferencia.value 	= "$ "+numcredvar(u.rtransferencia.value);
			
			u.rcheque.value 		= obj.rcheque;
			u.rcheque.value 		= "$ "+numcredvar(u.rcheque.value);
		}else{
			mens.show("A","No se encontraron datos para el empleado "+u.nombre.value,"¡Atención!","empleado");
			u.efectivo.value 		= "$ 0.00";
			u.tarjeta.value			= "$ 0.00";
			u.transferencia.value 	= "$ 0.00";
			u.cheque.value 			= "$ 0.00";
			u.refectivo.value 		= "$ 0.00";
			u.rtarjeta.value		= "$ 0.00";
			u.rtransferencia.value 	= "$ 0.00";
			u.rcheque.value 		= "$ 0.00";
			return false;
		}
	}
	
	function limpiar(){
		u.empleado.value = "";
		u.nombre.value = "";
		u.fecha.value = "<?=date('d/m/Y') ?>";
		u.efectivo.value 		= "$ 0.00";
		u.tarjeta.value			= "$ 0.00";
		u.transferencia.value 	= "$ 0.00";
		u.cheque.value 			= "$ 0.00";
		u.refectivo.value 		= "$ 0.00";
		u.rtarjeta.value		= "$ 0.00";
		u.rtransferencia.value 	= "$ 0.00";
		u.rcheque.value 		= "$ 0.00";
	}
	
	function numcredvar(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString();
		if(cad!="0.00"){
			if(flag) cad += '.'; 
		}
		return cad;
	}
	
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">REPORTE DE CIERRE DE CAJA ENTREGADO VS REGISTRADO </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="13%">Empleado(a):</td>
          <td width="14%"><label>
            <input name="empleado" type="text" id="empleado" style="width:70px" class="Tablas" onkeypress="if(event.keyCode==13){obtenerEmpleado(this.value)}" />
          </label></td>
          <td width="6%"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarEmpleadoGen.php?funcion=obtenerEmpleado', 650, 500, 'ventana', 'Busqueda')"></div></td>
          <td width="38%"><label>
            <input name="nombre" type="text" id="nombre" style="width:200px; background:#FFFF99" readonly="" class="Tablas" />
          </label></td>
          <td width="8%">Fecha:</td>
          <td width="21%"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px" value="<?=date('d/m/Y') ?>" />
           <img src="../img/calendario.gif" width="20" height="20" align="absbottom" onclick="if(<?=$cpermiso->checarPermiso(453,$_SESSION[IDUSUARIO]);?>==false){mens.show('A','Usted no tiene los permisos para ejecutar esta acción','¡Atención!');}else{displayCalendar(document.forms[0].fecha,'dd/mm/yyyy',this);}" style="cursor:pointer" /></td>
        </tr>
		 
        <tr>
          <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="2" class="FondoTabla">ENTREGADO POR USUARIO</td>
                  </tr>
                <tr>
                  <td width="39%">Efectivo:</td>
                  <td width="61%"><input name="efectivo" type="text" class="Tablas" id="efectivo" style="text-align:right" readonly="" value="$ 0.00"/></td>
                </tr>
                <tr>
                  <td>Tarjeta:</td>
                  <td><input name="tarjeta" type="text" class="Tablas" id="tarjeta" style="text-align:right" readonly="" value="$ 0.00" /></td>
                </tr>
                <tr>
                  <td>Transferencia:</td>
                  <td><input name="transferencia" type="text" class="Tablas" id="transferencia" style="text-align:right" readonly="" value="$ 0.00" /></td>
                </tr>
                <tr>
                  <td>Cheque:</td>
                  <td><input name="cheque" type="text" class="Tablas" id="cheque" style="text-align:right" readonly="" value="$ 0.00" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                
              </table></td>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="2" class="FondoTabla">REGISTRADO POR SISTEMA</td> 
                  </tr>
                <tr>
                  <td>Efectivo:</td>
                  <td><input name="refectivo" type="text" class="Tablas" id="refectivo" style="text-align:right" readonly="" value="$ 0.00"/></td>
                </tr>
                <tr>
                  <td>Tarjeta:</td>
                  <td><input name="rtarjeta" type="text" class="Tablas" id="rtarjeta" style="text-align:right" readonly="" value="$ 0.00" /></td>
                </tr>
                <tr>
                  <td>Transferencia:</td>
                  <td><input name="rtransferencia" type="text" class="Tablas" id="rtransferencia" style="text-align:right" readonly="" value="$ 0.00" /></td>
                </tr>
                <tr>
                  <td>Cheque:</td>
                  <td><input name="rcheque" type="text" class="Tablas" id="rcheque" style="text-align:right" readonly="" value="$ 0.00" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                
              </table></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="84%" align="right"><div class="ebtn_Generar" onclick="generarReporte();"></div></td>
              <td width="16%" align="right"><div class="ebtn_nuevo" onclick='mens.show("C","Se perdera la informacion capturada ¿Desea continuar?","","","limpiar()");'></div></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
