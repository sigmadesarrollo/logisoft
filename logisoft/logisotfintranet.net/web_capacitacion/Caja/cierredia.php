<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></link>

<script>

	var u = document.all;	
	var mens = new ClaseMensajes();
	
	mens.iniciar("../javascript");
	
	window.onload = function(){
		obtenerDatos();
	}
	
	function obtenerDatos(){
		consultaTexto("mostrarDatos","cierrecaja_con.php?accion=4");
	}
	
	function mostrarDatos(datos){
		var obj = eval(datos);
		u.existe.value = obj.principal.iniciodia;
		u.existecierre.value = obj.principal.existecierre;
		u.cierre.value = obj.principal.cierre;
		u.cierreprincipal.value = obj.principal.cierreprincipal;
		u.iniciodia.value = obj.principal.folioiniciodia;
		u.deposito.value = obj.principal.deposito;
	}

	function validar(){
		<?=$cpermiso->verificarPermiso("324",$_SESSION[IDUSUARIO]);?>
		var fechas = "<?=$fechas?>";
		if(u.cambiafecha.value==""){
			if(u.existe.value=="NO" /*&& fechas.indexOf(document.all.fechacierre.value)<0*/){
				mens.show('A','Debe Iniciar dia antes de realizar Cierre de dia','¡Atención!');
				return false;
			}
			
			if(u.cierreprincipal.value == "NO"){
				mens.show('A','Debe hacer el cierre principal antes de Cerrar día','¡Atención!');
				return false;
			}
			
			if(u.deposito.value==1){
				mens.show("A","Para poder hacer el cierre de dia debe hacer los depositos del dia anterior","¡Atención!");
				return false;
			}
			
			if(u.existecierre.value=="SI"){
				mens.show('A','El dia ya ha sido Cerrado','¡Atención!');
			}else if(u.cierre.value=="NO"){
				u.accion.value = "grabar";
				consultaTexto("registro","cierrecaja_con.php?accion=5&iniciodia="+u.iniciodia.value+"&s="+Math.random());
			}
		}else{
			if(u.existecierre.value=="SI"){
				mens.show("A","El dia ya ha sido Cerrado","¡Atención!");
				return false;
			}
			consultaTexto("registro","cierrecaja_con.php?accion=5&cambiafecha=si&fecha="+u.fechacierre.value
			+"&iniciodia="+u.iniciodia.value+"&s="+Math.random());
		}
	}

	function registro(datos){
		if(datos.indexOf("ok")>-1){
			var r = datos.split(",");
			mens.show("I","Se ha realizado el cierre de dia correctamente","");
			u.existecierre.value = "SI";
		}else{
			mens.show("A","Hubo un error al guadar "+datos,"¡Atención!");
		}
	}
	
	function obtenerFecha(v_fecha){
		var f1 = u.h_fecha.value.split("/");
		var f2 = v_fecha.split("/");
		
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
		
		if(f2 > f1){			
			mens.show("A","La Fecha de cierre debe ser menor a la fecha actual","¡Atención!");
			return false;
		}
		
		if(f2 >= f1 && u.cambiafecha.value != ""){
			u.existe.value = "";
			u.existecierre.value = "";
			u.cierre.value = "";
			u.cierreprincipal.value = "";
			u.iniciodia.value = "";
			u.deposito.value = "";
			u.cambiafecha.value = "";
		}
		
		consultaTexto("mostrarDatosFechaAnterior","cierrecaja_con.php?accion=6&fecha="+v_fecha+"&ss="+Math.random());	
	}
	
	function mostrarDatosFechaAnterior(datos){
		if(datos.indexOf("diafestivo")>-1){
			mens.show("A","La fecha seleccionada es un dia configurado como festivo","¡Atención!");
			u.fechacierre.value = '<?=date('d/m/Y'); ?>';
			return false;
		}
		
		if(datos.indexOf("domingo")>-1){
			mens.show("A","La fecha seleccionada es un dia no laboral","¡Atención!");
			u.fechacierre.value = '<?=date('d/m/Y'); ?>';
			return false;
		}
		
		if(datos.indexOf("iniciodia")>-1){
			mens.show("A","No han hecho inicio de dia con la fecha seleccionada","¡Atención!");
			return false;
		}
		
		if(datos.indexOf("cierreprincipal")>-1){
			mens.show("A","No han hecho el cierre principal con la fecha seleccionada","¡Atención!");
			u.fechacierre.value = '<?=date('d/m/Y'); ?>';
			return false;
		}
		
		if(datos.indexOf("yacerro")>-1){
			mens.show("A","La Fecha de cierre seleccionada ya fue registrada","¡Atención!");
			return false;
		}
		var obj = eval(datos);
		u.iniciodia.value = obj.principal.folio;
		u.cambiafecha.value = "SI";
	}
</script>
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="279" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="275" class="FondoTabla Estilo4">CIERRE DE D&Iacute;A</td>
    </tr>
    <tr>
      <td><table width="178" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td width="43">Fecha: </td>
            <td width="135"><input name="fechacierre" type="text" class="Tablas" id="fechacierre" style="background:#FFFF99; text-align:center" value="<?=date('d/m/Y') ?>" size="15" readonly="" onchange="obtenerFecha(this.value)" />
                <img src="../img/calendario.gif" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="if(<?=$cpermiso->checarPermiso(445,$_SESSION[IDUSUARIO]);?>==false){mens.show('A','Usted no tiene los permisos para ejecutar esta acción','¡Atención!');}else{displayCalendar(document.forms[0].fechacierre,'dd/mm/yyyy',this);}" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="center"><div class="ebtn_cerrar" onclick="validar();"></div></td>
          </tr>
          <tr>
            <td colspan="2"><input name="accion" type="hidden" id="accion"  />
                <input name="existe" type="hidden" id="existe" />
                <input name="existecierre" type="hidden" id="existecierre"  />
                <input name="cierre" type="hidden" id="cierre" />
                <input name="cierreprincipal" type="hidden" id="cierreprincipal"  />
            <input name="iniciodia" type="hidden" id="iniciodia"/>
            <input name="h_fecha" type="hidden" id="h_fecha" value="<?=date('d/m/Y') ?>"/>
            <input name="cambiafecha" type="hidden" id="cambiafecha" />
            <input name="deposito" type="hidden" id="deposito" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center"></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
