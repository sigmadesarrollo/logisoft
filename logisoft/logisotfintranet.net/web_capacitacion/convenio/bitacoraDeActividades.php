<?	session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes2.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();	
	var pag1_cantidadporpagina = 30;
	mens.iniciar('../javascript');
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecha"},
			{nombre:"ACTIVIDAD", medida:100, alineacion:"left", datos:"actividad"},
			{nombre:"FOLIO", medida:100, alineacion:"left", datos:"convenio"},
			{nombre:"No.", medida:60, alineacion:"left", datos:"idcliente"},
			{nombre:"CLIENTE/PROSPECTO", medida:180, alineacion:"left", datos:"cliente"},
			{nombre:"LOGRO", medida:180, alineacion:"left",  datos:"logro"},
			{nombre:"TIEMPO", medida:80, alineacion:"left", datos:"tiempoinvertido"},
			{nombre:"PROX CITA", medida:80, alineacion:"left", datos:"proximacita"},
			{nombre:"SUCURSAL", medida:65, alineacion:"left", datos:"sucursal"}
		],
		filasInicial:30,
		alto:470,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
	}
	
	function obtenerDetalle(){
		consultaTexto("resTabla1","bitacoraDeActividades_con.php?accion=1&contador="+u.pag1_contador.value
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
				consultaTexto("resTabla1","bitacoraDeActividades_con.php?accion=1&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","bitacoraDeActividades_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","bitacoraDeActividades_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","bitacoraDeActividades_con.php?accion=1&contador="+contador
				+"&s="+Math.random());
				break;
		}
	}
	
	function agregarActividad(){		
		mens.popup("bitacoraDeActividades_form.php",800,580,null,'AGREGAR ACTIVIDAD');
	}
	
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","Las solicitud(es) fuerón aceptadas satisfactoriamente","");
			obtenerDetalle();
		}else{
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!");
		}
	}
	
	function cerrarVentana(){
		mens.cerrar();
		obtenerDetalle();
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="820" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla" style="background-color:#24528A; color:#FFF; font-size:12px">BITACORA DEL EMPLEADO</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><div id="txtDir" style=" height:490px; width:820px; overflow:auto" align="left">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table>
		  </div></td>
        </tr>
        <tr>
          <td><div id="paginado" align="center" style="visibility:hidden">
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
          <td align="left"><div class="ebtn_agregar" onclick="mens.show('C',' &iquest; Desea agregar una actividad?', '', '', 'agregarActividad()')"></div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>

</body>
</html>
