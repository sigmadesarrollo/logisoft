<?
	session_start();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery-1.4.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
</link>
<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript');
	jQuery(function($){
	   $('#inicio').mask("99/99/9999");
	   $('#fin').mask("99/99/9999");
	});
	
	function generarReporte(){
		if(u.inicio.value == "" || u.inicio.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha inicio","¡Atención!","inicio");
			return false;
		}
		if(u.fin.value == "" || u.fin.value == "__/__/____"){
			mens.show("A","Debe capturar Fecha fin","¡Atención!","fin");
			return false;
		}
		var f1 = u.inicio.value.split("/");
		var f2 = u.fin.value.split("/");
		
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
			mens.show("A","La Fecha inicio no debe ser mayor a la Fecha fin","¡Atención!","inicio");
			return false;
		}
		
		abrirVentanaFija('../buscadores_generales/formaImpresion.php?funcion=tipoImpresion', 300, 230, 'ventana', 'Busqueda');
		
	}
	
	function tipoImpresion(tipo){
		var laUrl = document.URL;
			if(laUrl.indexOf('pmmintranet.net')>-1){
				var direccion = laUrl.substr(0,laUrl.indexOf('pmmintranet.net')+15+((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacion')>-1)?18:1)));
			}else{
				var direccion = laUrl.substr(0,laUrl.indexOf('pmmintranet.com')+15+((laUrl.indexOf('web_pruebas')>-1)?13:((laUrl.indexOf('web_capacitacion')>-1)?18:1)));
			}
		if(tipo=="Pantalla"){
			window.open(direccion+"tcpdf/reportes/reporteCajaCondensado.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&fechainicio="+u.inicio.value+"&fechafin="+u.fin.value);
		}else{			
			window.open(direccion+"Caja/reporteCaja.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>&fechainicio="+u.inicio.value+"&fechafin="+u.fin.value);
		}
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">GENERAR REPORTE DE CORTE DIARIO </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="79">Fecha Inicio: </td>
          <td width="120"><label>
            <input name="inicio" type="text" class="Tablas" id="inicio" style="width:80px" value="<?=date('d/m/Y') ?>" />
            <span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.inicio,'dd/mm/yyyy',this)" /></span></label></td>
          <td width="58">Fecha Fin: </td>
          <td width="88"><input name="fin" type="text" class="Tablas" id="fin" style="width:80px" value="<?=date('d/m/Y') ?>" /></td>
          <td width="51"><span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.all.fin,'dd/mm/yyyy',this)" /></span></td>
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5" align="right"><img src="../img/Boton_Generar.gif" width="74" height="20" style="cursor:pointer" onclick="generarReporte()" /></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>

</body>
</html>
