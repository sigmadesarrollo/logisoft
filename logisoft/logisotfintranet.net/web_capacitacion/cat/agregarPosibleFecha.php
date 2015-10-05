<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date('d/m/Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script>
	var u = document.all;
	
	function agregar(){
		if(u.fecha.value == ""){
			alerta('Debe capturar Fecha','¡Atención!','fecha');
		}else if(u.fecha.value <= u.fecha2.value){
			alerta('La Fecha de posible solución debe ser mayor a la actual','¡Atención!','fecha');
		}else{
			var arr = new Array();
			arr[0] = u.fecha.value;
			arr[1] = u.observaciones.value;
			<? if($_GET[bitacoraquejas]==1){?>
				consultaTexto("registro","bitacoraQuejasDanosFaltantes_con.php?accion=2&folio="+<?=$_GET[folio]?>+"&arre="+arr);		
			<? }else{?>
				consultaTexto("registro","centroAtencionTelefonica_con.php?accion=8&folio="+u.folio.value+"&arre="+arr);
			<? } ?>
		}
	}
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			window.parent.<?=$_GET[funcion]; ?>(u.fecha.value,u.observaciones.value);
			info('Los datos han sido agregados satisfactoriamente','')
		}else{
			alerta3('Hubo un error al guardar '+datos,'¡Atención!');
		}
	}
	function limpiar(){
		u.fecha.value = "";
		u.observaciones.value = "";
	}
	function validarFecha(e,param,name){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,2),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					alerta('La fecha no es valida', '¡Atención!',name);
					return false;
				}
				if (dia>"31" || dia=="0" ){
	alerta('La fecha no es valida, capture correctamente el Dia', '¡Atención!',name);
					return false;	
				}
				if (mes>"12" || mes=="0" ){
	alerta('La fecha no es valida, capture correctamente el Mes', '¡Atención!',name);
					return false;	
				}	
			}	
		}
	}
</script>
<script src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">

</head>

<body>
<p class="Tablas">&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">POSIBLES FECHAS DE SOLUCI&Oacute;N</td>
    </tr>
    <tr>
      <td><br><table width="349" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="130">Fecha Posible Soluci&oacute;n: </td>
          <td width="219"><label>
            <input name="fecha" style="background:#FFFF99" readonly="" class="Tablas" onKeyPress="validarFecha(event,this.value,this.name);" type="text" id="fecha" value="<?=$_GET[fecha] = str_replace('-','/',$_GET[fecha]); ?>">
            <span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></span></label></td>
        </tr>
        <tr>
          <td valign="top">Comentarios:</td>
          <td><label>
            <textarea name="observaciones" class="Tablas" id="observaciones" style="height:100px; width:200px; text-transform:uppercase"><?=$_GET[comentarios]; ?></textarea>
          </label></td>
        </tr>
        <tr>
          <td colspan="2"><input name="folio" type="hidden" id="folio" value="<?=$_GET[folio]; ?>">
            <input name="fecha2" type="hidden" id="fecha2" value="<?=$fecha; ?>"></td>
        </tr>
        <tr>
          <td colspan="2"><table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td><div class="ebtn_agregar" onClick="agregar()"></div></td>
              <td><div class="ebtn_nuevo" onClick="limpiar()"></div></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
