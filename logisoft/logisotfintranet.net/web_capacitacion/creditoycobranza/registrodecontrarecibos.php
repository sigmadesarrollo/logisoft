<? 	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	$fecha = date('d/m/Y');
	$s = "SELECT CONCAT_WS(' ',nombre,paterno,materno)AS nombre,CURRENT_TIME as hrs
	FROM catalogocliente  WHERE id='".$_GET[cliente]."'";
	$s_q=mysql_query($s,$l) or die("Error en la liena ".mysql_error($l));
	$row=mysql_fetch_array($s_q);
	$cliente=$row[nombre];
	$hrs=$row[hrs];
	$idcliente=$_GET[cliente];
	$fecha=date('d/m/Y');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css">

<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>
<SCRIPT type="text/javascript" src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<style type="text/css">
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

<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>

<script>
	var u = document.all;
	
	jQuery(function($){
	   $('#fecha').mask("99/99/9999");
	});
	
	function obtenerDia(fecha){
		consultaTexto("mostrarDia","registrodecompromiso_con.php?accion=1&fecha="+fecha);
	}
	function mostrarDia(datos){
	datos=	datos.substring(2,datos.length);
	datos=parseInt(datos);
		switch (datos){			
			case 1:
				u.dia.value = "DOMINGO";
			break;
			case 2:
				u.dia.value = "LUNES";
			break;
			case 3:
				u.dia.value = "MARTES";
			break;	
			case 4:
				u.dia.value = "MIERCOLES";
			break;
			case 5:
				u.dia.value = "JUEVES";
			break;
			case 6:
				u.dia.value = "VIERNES";
			break;
			case 7:
				u.dia.value = "SABADO";
			break;
			default:
				u.dia.value = "";
		}
	}


	function Validar(){
		if(u.fecha.value==""){
			alerta('Debe capturar una Fecha','Atencin!','fecha');
		}else if(u.fecha.value < u.fechaactual.value){
			alerta('La fecha compromiso debe ser mayor a la actual','Atencin!','fecha');
			return false;
		}else if(u.contrarecibo.value == ""){
			alerta('Debe capturar Contrarecibo','Atencin!','contrarecibo');
		}else{
			u.d_guardar.style.visibility = "hidden";
		consultaTexto("resultado","registrodecompromiso_con.php?accion=3&idcliente="+u.idcliente.value
				+"&fecha="+u.fecha.value
				+"&recibo="+u.contrarecibo.value
				+"&hrs="+u.hrs.value
				+"&observaciones="+u.observaciones.value
				+"&factura="+u.factura.value
				+"&sid="+Math.random());
		}
	}
	
	function resultado(datos){
		if(datos.indexOf("ok")>-1){
			u.d_guardar.style.visibility = "visible";
			info('Los datos han sido guardados correctamente','');
			window.parent.<?=$_GET[funcion]; ?>(u.contrarecibo.value,u.factura.value);
		}else{
			u.d_guardar.style.visibility = "visible";
			alerta3('Hubo un error al guardar '+datos,'Atencin!');
		}
	}
	window.onload = function(){
		u.fecha.value='<?=$fecha ?>';
		obtenerDefault();
		u.hrs.value = obtenerHora();
	}
	
	function obtenerDefault(){	
			consultaTexto("mostrarDefault", "registrodecompromiso_con.php?accion=4&fecha="+u.fecha.value+"&and="+Math.random());
	}
	function mostrarDefault(datos){
		row = datos.split(",");
		u.dia.value = row[0];
	}
	function validarFecha(e,param,name){
		var fechao = '<?=$fecha?>';
		var f1 = fechao.split("/");
		var f2 = param.split("/");
		
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
			alerta("La fecha debe ser mayor O igual ala actual","Atencin!","fecha");
			u.fecha.value='<?=$fecha ?>';
			consultaTexto('ponerDiaSemana','consultasCobranza.php?accion=1&fecha=<?=$fecha ?>');
			obtenerDefault();
		}else{
			consultaTexto('ponerDiaSemana','consultasCobranza.php?accion=1&fecha='+param);
		}
	}
	
	function ponerDiaSemana(datos){
		u.dia.value = datos;
	}
var nav4 = window.Event ? true : false;
function Numeros(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57));
}
</script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="351" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="398" class="FondoTabla Estilo4">REGISTRO DE CONTRARECIBOS</td>
  </tr>
  <tr>
    <td height="60"><div align="center">
      <table width="259" border="0" cellpadding="0" cellspacing="0">
        
        
        <tr>
          <td width="352"><table width="350" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="76" height="24"># Cliente:</td>
              <td><label><span class="Tablas">
              <input name="idcliente" type="text" class="Tablas" id="idcliente" style="width:100px;background:#FFFF99" value="<?=$idcliente ?>" readonly=""/>
              </span></label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="11">Nombre:</td>
              <td colspan="2"><span class="Tablas">
                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:250px;background:#FFFF99" value="<?=$cliente ?>"/>
              </span></td>
              </tr>
            <tr>
              <td height="11">Fecha:</td>
              <td width="123"><span class="Tablas">
                <label>
                  <input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px;" onChange="obtenerDia(this.value);validarFecha(event,this.value,this.name)" onKeyPress="validarFecha(event,this.value,this.name)" value="<?=$fecha ?>" />
<span class="Estilo6 Tablas"><img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)" /></span></label>
              </span></td>
              <td width="151"><span class="Tablas">Dia:
                  <input name="dia" type="text" class="Tablas" id="dia" style="width:100px;background:#FFFF99" value="<?=$dia ?>" readonly=""/>
              </span></td>
            </tr>
            <tr>
              <td height="11"><span class="Tablas">Hora</span>:</td>
              <td colspan="2"><span class="Tablas">
                <label>                </label>
                <input name="hrs" type="text" class="Tablas" id="hrs" style="width:100px;background:#FFFF99" readonly=""/>
                <input name="factura" type="hidden" id="factura" value="<?=$_GET[factura] ?>">
                <input name="fechaactual" type="hidden" id="fechaactual" value="<?=$fecha ?>">
              </span></td>
            </tr>
            <tr>
              <td height="11">Contrarecibo:</td>
              <td colspan="2"><input name="contrarecibo" type="text" class="Tablas" id="contrarecibo" style="width:250px;" onKeyPress="return Numeros(event); "/></td>
            </tr>
            <tr>
              <td height="11" valign="top">Observaciones:</td>
              <td colspan="2"><textarea name="observaciones" class="Tablas" style="text-transform:uppercase; width:250px; height:80px" id="observaciones"></textarea></td>
            </tr>
            <tr align="right">
              <td height="11" colspan="3"><table width="172" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="right"><div class="ebtn_guardar" id="d_guardar" onClick="Validar()"></div></td>
                  <td align="right"><div class="ebtn_cerrarventana" id="d_cerrar" onClick="parent.VentanaModal.cerrar()"></div></td>
                </tr>
              </table></td>
            </tr>
            
            
            
          </table></td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
</body>
<script>
	//parent.frames[1].document.getElementById('titulo').innerHTML = 'REGISTRO DE CONTRARECIBOS';
</script>
</html>