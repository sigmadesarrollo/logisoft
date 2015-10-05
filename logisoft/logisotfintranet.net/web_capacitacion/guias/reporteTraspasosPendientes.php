<?	session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	var pag1_cantidadporpagina = 30;
	mens.iniciar('../javascript');
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"ACEPTAR", medida:70, tipo:"checkbox", alineacion:"left", datos:"aceptar"},
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
		obtenerDetalle();
	}
	
	function obtenerDetalle(){
		consultaTexto("resTabla1","reporteTraspasosPendientes_con.php?accion=1&contador="+u.pag1_contador.value
		+"&s="+Math.random());
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
				consultaTexto("resTabla1","reporteTraspasosPendientes_con.php?accion=1&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","reporteTraspasosPendientes_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","reporteTraspasosPendientes_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","reporteTraspasosPendientes_con.php?accion=1&contador="+contador
				+"&s="+Math.random());
				break;
		}
	}
	
	function aceptarTraspaso(){
		var folios = "";
		var guias = "";
		for(var i=0;i<tabla1.getRecordCount();i++){
			if(u["detalle_ACEPTAR"][i].checked == true){
				folios += u["detalle_FOLIO_SOLICITUD"][i].value + ",";
				guias += u["detalle_GUIA"][i].value + ",";
			}
		}
		
		if(folios!=""){
			folios = folios.substring(0,folios.length - 1);
			guias = guias.substring(0,guias.length - 1);
		}
		
		consultaTexto("registro","reporteTraspasosPendientes_con.php?accion=2&folios="+folios
		+"&guias="+guias
		+"&val="+Math.random());	
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","Las solicitud(es) fuerón aceptadas satisfactoriamente","");
			obtenerDetalle();
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
		}
	}
	function cancelarTraspaso(){
		var folios = "";
		var guias = "";
		for(var i=0;i<tabla1.getRecordCount();i++){
			if(u["detalle_ACEPTAR"][i].checked == true){
				folios += u["detalle_FOLIO_SOLICITUD"][i].value + ",";
				guias += u["detalle_GUIA"][i].value + ",";
			}
		}
		if(folios!=""){
			folios = folios.substring(0,folios.length - 1);
			guias = guias.substring(0,guias.length - 1);
		}
		consultaTexto("registro2","reporteTraspasosPendientes_con.php?accion=3&folios="+folios
		+"&guias="+guias+"&val="+Math.random());	
	}
	
	function registro2(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","Las solicitud(es) fuerón canceladas satisfactoriamente","");
			obtenerDetalle();
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
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
      <td class="FondoTabla">REPORTE DE TRASPASOS DE CARGO PENDIENTES DE AUTORIZAR </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2"><div id="txtDir" style=" height:200px; width:620px; overflow:auto" align="left">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table>
		  </div></td>
        </tr>
        <tr>
          <td colspan="2"><div id="paginado" align="center" style="visibility:hidden">
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
        <tr>
          <td width="550px" align="right"><div class="ebtn_aceptar" onclick="mens.show('C',' &iquest; Esta seguro de Aceptar la(s) solicitud de Traspaso de cargo?', '', '', 'aceptarTraspaso()')"></div></td>
		   <td width="80px" align="right"><div class="ebtn_noautorizar" onclick="mens.show('C',' &iquest; Esta seguro de Cancelar la(s) solicitud de Traspaso de cargo?', '', '', 'cancelarTraspaso()')"></div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>

</body>
</html>
