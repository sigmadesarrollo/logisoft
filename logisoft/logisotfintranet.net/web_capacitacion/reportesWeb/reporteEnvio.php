<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabsDivs.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/jquery.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">

<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	var tabla6 	= new ClaseTabla();
	mens.iniciar('../javascript');
	
	//para paginado
	var pag1_cantidadporpagina = 2;
	
	jQuery(function($){	   
	   $('#fecha').mask("99/99/9999");
	   $('#fecha2').mask("99/99/9999");
	});
		
	tabla6.setAttributes({//ENVIADOS
		nombre:"detalle5",	
		campos:[	
			{nombre:"FECHA", medida:80, alineacion:"left", datos:"fecharealizacion"},	
			{nombre:"GUIA", medida:80, alineacion:"center",  datos:"folio"},	
			{nombre:"DESTINO", medida:50, alineacion:"center",  datos:"destino"},	
			{nombre:"CLIENTE ORIGEN/DESTINO", medida:150, alineacion:"left", datos:"origendestino"},				
			{nombre:"FLETE", medida:50, alineacion:"center",  datos:"flete"},	
			{nombre:"ENVIO", medida:50, alineacion:"center",  datos:"tipoentrega"},	
			{nombre:"PAQUETES", medida:50, alineacion:"center",  datos:"paquetes"},	
			{nombre:"KILOGRAMOS", medida:50, alineacion:"center",  datos:"totalkilogramos"},	
			{nombre:"TOTAL", medida:50, tipo:"moneda", alineacion:"right",  datos:"total"},	
			{nombre:"ESTADO", medida:100, alineacion:"center",  datos:"estado"},	
			{nombre:"QUIEN RECIBIO", medida:150, alineacion:"center",  datos:"recibio"}	
		],	
		filasInicial:18,	
		alto:100,	
		seleccion:true,	
		ordenable:false,
		nombrevar:"tabla6"	
	});
	
	
	
	window.onload = function(){	
		tabla6.create();	
	}
	
	
	function obtenerDetalle(){
		consultaTexto("resTabla6","reporteEnvio_con.php?accion=6&contador="+u.pag6_contador.value+
					  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
					  "&idcliente="+u.cliente.value+
					  "&tipo=idenvia&s="+Math.random());
	}
	function resTabla6(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag6_total.value = obj.total;
		u.pag6_contador.value = obj.contador;
		u.pag6_adelante.value = obj.adelante;
		u.pag6_atras.value = obj.atras;
		tabla6.setJsonData(obj.registros);
		//totales
		u.t5_guias.value = obj.totales.totalguias;
		u.t5_paquetes.value = obj.totales.totalpaquetes;
		u.t5_kg.value = obj.totales.totalkilogramos;
		u.t5_total.value = "$ "+obj.totales.total;
	}
	function paginacion6(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla6","reporteEnvio_con.php?accion=6&contador=0"+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
					  		  "&idcliente="+u.cliente.value+
							  "&tipo=idenvia&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag6_adelante.value==1){
					consultaTexto("resTabla6","reporteEnvio_con.php?accion=6&contador="+(parseFloat(u.pag6_contador.value)+1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&idcliente="+u.cliente.value+
							  "&tipo=idenvia&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag6_atras.value==1){
					consultaTexto("resTabla6","reporteEnvio_con.php?accion=6&contador="+(parseFloat(u.pag6_contador.value)-1)+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&idcliente="+u.cliente.value+
							  "&tipo=idenvia&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag6_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla6","reporteEnvio_con.php?accion=6&contador="+contador+
							  "&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&idcliente="+u.cliente.value+
							  "&tipo=idenvia&s="+Math.random());
				break;
		}
	}
	
	
	function limpiar(){
		u.fecha.value = "<?=date('d/m/Y') ?>";
		u.fecha2.value = "<?=date('d/m/Y') ?>";
		if(tabla1.creada()){
			tabla1.clear();
		}
		
		u.tab_contenedor_id1.disabled=true;	
		tabs.seleccionar(0);
	}
	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<input type="hidden" name="cliente" value="<?=$_SESSION[IDUSUARIO]?>" />
<form id="form1" name="form1" method="post" action="">
	<table align="left">
		<tr>
			<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="71">Fecha Inicial: </td>
            <td width="113"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>" onkeypress="if(event.keyCode==13){document.all.fecha2.focus();}"/></td>
            <td width="82"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
            <td width="71">Fecha Final: </td>
            <td width="113"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y') ?>" onkeypress="if(event.keyCode==13){obtenerDetalle();}"/></td>
            <td width="67"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
          </tr>
	      </table>
			</td>
			<td width="99">
				<img src="../img/Boton_Generar.gif" width="74" height="20" align="absbottom" style="cursor:pointer" onclick="obtenerDetalle()" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
			
			
	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td colspan="4">
	<div style="height:120px; width:680px; overflow:auto; ">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle5" >
		</table>
	</div>
    <table border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="80px">Totales</td>
                <td width="80px">Guias</td>
                <td width="50px"></td>
                <td width="150px"></td>
                <td width="50px"></td>
                <td width="50px"></td>
                <td width="50px">Paquetes</td>
                <td width="50px">Kgs</td>
                <td width="50px">Total</td>
                <td width="100px"></td>
                <td width="150px"></td>
            </tr>
            <tr>
            	<td width="80px"></td>
                <td width="80px"><input type="text" name="t5_guias" id="t5_guias" style="background:#FFFF99; width:80px;" readonly="" /></td>
                <td width="50px"></td>
                <td width="150px"></td>
                <td width="50px"></td>
                <td width="50px"></td>
                <td width="50px"><input type="text" name="t5_paquetes" id="t5_paquetes" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="50px"><input type="text" name="t5_kg" id="t5_kg" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="50px"><input type="text" name="t5_total" id="t5_total" style="background:#FFFF99; width:50px;" readonly="" /></td>
                <td width="100px"></td>
                <td width="150px"></td>
            </tr>
        </table>
    	</td>
  </tr>
  <tr>
  	<td colspan="4" align="right">
    	
    </td>
  </tr>
  <tr>
    <td colspan="4"><div id="div_paginado6" align="center" style="visibility:hidden">
              <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion6('primero')" /> 
              <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion6('atras')" /> 
              <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion6('adelante')" /> 
              <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion6('ultimo')" />
          </div>
          <input type="hidden" name="pag6_total" />
          <input type="hidden" name="pag6_contador" value="0" />
          <input type="hidden" name="pag6_adelante" value="" />
          <input type="hidden" name="pag6_atras" value="" />
          </td>	
  </tr>
</table>
			
			
			</td>
		</tr>
	</table>
</form>
</body>
</html>
