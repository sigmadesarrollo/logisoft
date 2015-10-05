<?	session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	var pag1_cantidadporpagina = 30;
	mens.iniciar('../javascript');
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"FOLIO_SOLICITUD", medida:90, alineacion:"left", datos:"foliotraspaso"},
			{nombre:"FECHA", medida:70, alineacion:"left",  datos:"fechasolicitud"},
			{nombre:"SUC. SOLICITA", medida:80, alineacion:"left", datos:"solicita"},
			{nombre:"GUIA", medida:90, alineacion:"left",  datos:"guia"},
			{nombre:"IMPORTE", medida:90, tipo:"moneda", alineacion:"left", datos:"importe"},
			{nombre:"CLIENTE", medida:150, alineacion:"left", datos:"cliente"}					
		],
		filasInicial:15,
		alto:170,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	function obtenerDetalle(){
		consultaTexto("resTabla1","reporte_TraspasoCargo_con.php?fechainicio="+u.fecha.value
		+"&fechafin="+u.fecha2.value+"&contador="+u.pag1_contador.value+"&s="+Math.random());
	}
	
	function resTabla1(datos){
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		
		tabla1.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","reporte_TraspasoCargo_con.php?fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","reporte_TraspasoCargo_con.php?fechainicio="+u.fecha.value
					+"&fechafin="+u.fecha2.value+"&contador="+(parseFloat(u.pag1_contador.value)+1)+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","reporte_TraspasoCargo_con.php?fechainicio="+u.fecha.value
					+"&fechafin="+u.fecha2.value+"&contador="+(parseFloat(u.pag1_contador.value)-1)+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","reporte_TraspasoCargo_con.php?fechainicio="+u.fecha.value
				+"&fechafin="+u.fecha2.value+"&contador="+contador+"&s="+Math.random());
				break;
		}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="610" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">REPORTE DE TRASPASOS DE CARGO </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
		  <td width="183">Fecha Inicial: 
		    <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>" 
		  onkeypress="if(event.keyCode==13){document.all.fecha2.focus();}"/></td>
		  <td width="85"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
		  <td width="174">Fecha Final: 
		    <input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y') ?>" 
		  onkeypress="if(event.keyCode==13){obtenerDetalle();}"/></td>
		  <td width="68"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
		  <td width="110"><div class="ebtn_Generar" onclick="obtenerDetalle()" ></div></td>
        </tr>
		<tr>
          <td colspan="5"><div id="txtDir" style=" height:200px; width:620px; overflow:auto" align="left">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table>
		  </div></td>
        </tr>
        <tr>
          <td colspan="5"><div id="paginado" align="center" style="visibility:hidden">
                <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> 
			  <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> 
			  <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('adelante')" /> 
			  <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion('ultimo')" />
		  <input type="hidden" name="pag1_total" />
          <input type="hidden" name="pag1_contador" value="0" />
          <input type="hidden" name="pag1_adelante" value="" />
          <input type="hidden" name="pag1_atras" value="" />
            </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
