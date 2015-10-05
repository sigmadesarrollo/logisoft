<? session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT_WS(' ',nombre,apellidopaterno,apellidomaterno) AS empleado FROM catalogoempleado WHERE id = ".$_SESSION[IDUSUARIO]."";
	$r = mysql_query($s,$l) or die($s); $f = mysql_fetch_object($r);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript");
	
	window.onload = function(){
		obtenerDatosGuia('<?=$_GET[guia] ?>');		
		if('<?=$_GET[indice] ?>' > 0){
			mens.show('I','Se han capturado los datos de la guia faltante<br>Se continua con la siguiente', '');			
		}
	}

	function obtenerDatosGuia(guia){
		consultaTexto("mostrarDatosGuia","reporteFaltante_con.php?accion=1&guia="+guia
		+"&bitacora="+u.h_bitacora.value+"&s="+Math.random());
	}
	
	function mostrarDatosGuia(datos){
		if(datos.indexOf("noencontro")<0){
			var obj = eval(datos);
			u.guia.value 			= '<?=$_GET[guia] ?>';
			u.fecha.value 			= obj.principal.fecha;			
			u.origen.value 			= obj.principal.origen;
			u.destino.value 		= obj.principal.destino;
			u.importe.value 		= obj.principal.importe;
			u.remitente.value 		= obj.principal.remitente;
			u.destinatario.value 	= obj.principal.destinatario;
			u.unidad.value 			= obj.bitacora.unidad;
			u.ruta.value 			= obj.bitacora.ruta;
			u.empleado1.value 		= obj.bitacora.empleado1;
			u.empleado2.value 		= obj.bitacora.empleado2;
			u.sucursal.value 		= obj.bitacora.sucursal;
		}
	}
	
	function obtenerEmpleado(empleado){
		u.empleado.value = empleado;
		consultaTexto("mostrarEmpleado","reporteFaltante_con.php?accion=2&empleado="+empleado);
	}
	
	function mostrarEmpleado(datos){
		if(datos.indexOf("noencontro")<0){
			var obj = eval(datos);
			u.nombre.value = obj.empleado;
		}else{
			mens.show("A","El empleado no existe","¡Atención!","empleado");
			u.empleado.value = "";
			u.nombre.value = "";
		}
	}
	
	function validar(){
		if(u.empleado.value==""){
			mens.show("A","Debe capturar quien Autoriza","¡Atención!","empleado");
			return false;
		}
		u.d_guardar.style.visibility = "hidden";
		consultaTexto("registro","reporteFaltante_con.php?accion=3&guia=<?=$_GET[guia] ?>&autorizo="+u.empleado.value
		+"&observaciones="+u.observaciones.value+"&bitacora="+u.h_bitacora.value+"&s="+Math.random());
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","Se registro el faltante satisfactoriamente","");
			u.d_guardar.style.visibility = "visible";
			parent.mostrarGuiaArreglo();
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
			u.d_guardar.style.visibility = "visible";
		}
	}
	
	function limpiar(){
		u.empleado.value = "";
		u.nombre.value = "";
		u.observaciones.value = "";
	}
	
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">REPORTE DE  FALTANTES EMBARQUES </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td >Guia:</td>
          <td ><span class="Tablas">
            <input name="guia" type="text" class="Tablas" id="guia" style="width:100px;background:#FFFF99"  readonly=""/>
          </span></td>
          <td >Fecha:</td>
          <td ><span class="Tablas">
            <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99"  readonly=""/>
          </span></td>
          <td >Sucursal:</td>
          <td ><span class="Tablas">
            <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99"  readonly=""/>
          </span></td>
        </tr>
        <tr>
          <td>Origen:</td>
          <td><span class="Tablas">
            <input name="origen" type="text" class="Tablas" id="origen" style="width:100px;background:#FFFF99"  readonly=""/>
          </span></td>
          <td>Destino:</td>
          <td><span class="Tablas">
            <input name="destino" type="text" class="Tablas" id="destino" style="width:100px;background:#FFFF99"  readonly=""/>
          </span></td>
          <td>Importe:</td>
          <td><span class="Tablas">
            <input name="importe" type="text" class="Tablas" id="importe" style="width:100px;background:#FFFF99"  readonly=""/>
          </span></td>
        </tr>
        <tr>
          <td>Unidad:</td>
          <td><span class="Tablas">
            <input name="unidad" type="text" class="Tablas" id="unidad" style="width:100px;background:#FFFF99"  readonly=""/>
          </span></td>
          <td>Ruta:</td>
          <td colspan="3"><span class="Tablas">
            <input name="ruta" type="text" class="Tablas" id="ruta" style="width:250px;background:#FFFF99"  readonly=""/>
          </span></td>
          </tr>
        <tr>
          <td>Remitente:</td>
          <td colspan="5"><span class="Tablas">
            <input name="remitente" type="text" class="Tablas" id="remitente" style="width:400px;background:#FFFF99"  readonly=""/>
          </span></td>
          </tr>
        <tr>
          <td>Destinatario:</td>
          <td colspan="5"><span class="Tablas">
            <input name="destinatario" type="text" class="Tablas" id="destinatario" style="width:400px;background:#FFFF99"  readonly=""/>
          </span></td>
          </tr>
        <tr>
          <td>Conductor(es):</td>
          <td colspan="5"><span class="Tablas">
            <input name="empleado1" type="text" class="Tablas" id="empleado1" style="width:400px;background:#FFFF99"  readonly=""/>
          </span></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="5"><span class="Tablas">
            <input name="empleado2" type="text" class="Tablas" id="empleado2" style="width:400px;background:#FFFF99"  readonly=""/>
          </span></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Autoriza:</td>
          <td><span class="Tablas">
            <input name="empleado" type="text" class="Tablas" id="empleado" style="width:100px;background:#FFFF99"  value="<?=$_SESSION[IDUSUARIO] ?>" readonly=""/>
            </span></td>
          <td colspan="4"><span class="Tablas">
            <input name="nombre" type="text" class="Tablas" id="nombre" style="width:292px;background:#FFFF99" value="<?=utf8_decode($f->empleado)?>" readonly=""/>
          </span></td>
          </tr>
        <tr>
          <td>Observaciones:</td>
          <td colspan="5"><textarea name="observaciones" id="observaciones" class="Tablas" style="width:400px; text-transform:uppercase">
          </textarea></td>
          </tr>
        <tr>
          <td colspan="6"><input name="h_ruta" type="hidden" id="h_ruta" value="<?=$_GET[idruta] ?>" />
            <input name="h_bitacora" type="hidden" id="h_bitacora" value="<?=$_GET[bitacora] ?>" /></td>
        </tr>
        <tr>
          <td colspan="6"><table border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="84"><div class="ebtn_guardar" id="d_guardar" onclick="validar()"></div></td>
              <td width="84"><div class="ebtn_nuevo" onclick="mens.show('C','&iquest;Desea limpiar los datos?','&iexcl;Atencion!','','','limpiar()')"></div></td>
            </tr>
          </table></td>
          </tr>
        
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
