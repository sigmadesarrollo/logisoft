<?	
	session_start();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css"></LINK>
<SCRIPT type="text/javascript" src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<link href="../estilos_estandar.css" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/funcionesDrag.js"></script>
<script language="javascript" src="../javascript/ClaseMensajes.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	var inicio		= 30;
	var sepasods	= 0;
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"GUIA", medida:80, alineacion:"center",  datos:"guia"},
			{nombre:"CLIENTE", medida:120, alineacion:"left", datos:"cliente"},
			{nombre:"CREACION", medida:60, alineacion:"left", datos:"fecha"},
			{nombre:"PAGO", medida:50,alineacion:"center", datos:"tipopago"},
			{nombre:"MODIFICO", medida:60, alineacion:"center", datos:"modifico"},
			{nombre:"SUCURSAL", medida:70, alineacion:"left",  datos:"sucursal"},
			{nombre:"DESCRIPCION", medida:100, alineacion:"left", datos:"descripcion"},
			{nombre:"TIPO", medida:70, alineacion:"center", datos:"tipo"},
			{nombre:"USUARIO", medida:120, alineacion:"left", datos:"usuario"}		
		],
		filasInicial:20,
		alto:280,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	function generar(){
		var u = document.all;
		consultaTexto("mostrardetalle","cancelacionYsustitucion_con.php?accion=1&fecha1="+u.fechainicio.value+"&fecha2="+u.fechafin.value);
	}
	
	function mostrardetalle(datos){
		if (datos.indexOf("no encontro")<0) {
			var obj = eval(datos);
			tabla1.setJsonData(obj);
		}
	}
	
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="710" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="700" class="FondoTabla Estilo4">Reporte de sustitucion y Cancelaci&oacute;n</td>
    </tr>
<tr>
      <td><table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>
          	<table width="698" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="53">Desde:</td>
                <td width="108"><input name="fechainicio" type="text" class="Tablas" id="fechainicio" style="width:100px" value="<?=$fecha ?>" /></td>
                <td width="64"><div class="ebtn_calendario" onClick="displayCalendar(document.all.fechainicio,'dd/mm/yyyy',this)"></div></td>
                <td width="58">Hasta:</td>
                <td width="107"><input name="fechafin" type="text" class="Tablas" id="fechafin" style="width:100px" value="<?=$fecha ?>" /></td>
                <td width="125"><div class="ebtn_calendario" onClick="displayCalendar(document.all.fechafin,'dd/mm/yyyy',this)"></div></td>
                <td width="183"><img src="../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onClick="generar()" /></td>
              </tr>
          </table>
          </td>
        </tr>
        <tr>
        	<td>
            	<table id="detalle" border="0" cellpadding="0" cellspacing="0"></table>
            </td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
