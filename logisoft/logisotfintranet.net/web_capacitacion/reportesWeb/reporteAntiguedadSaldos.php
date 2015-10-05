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
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script>
	var u = document.all;
	var mens = new ClaseMensajes();
	var tabla4 	= new ClaseTabla();
	mens.iniciar('../javascript');
	
	//para paginado
	var pag1_cantidadporpagina = 30;
	
	
	tabla4.setAttributes({
		nombre:"detalle3",
		campos:[
			{nombre:"Sucursal", medida:80, alineacion:"center",  datos:"prefijosucursal"},
			{nombre:"Cliente", medida:120, alineacion:"center", datos:"cliente"},
			{nombre:"Folio", medida:90, alineacion:"center",  datos:"folio"},
			{nombre:"Fecha", medida:80, alineacion:"center", datos:"fecha"},
			{nombre:"fechavenc", medida:80, alineacion:"center",  datos:"fechavenc"},
			{nombre:"Dias vencidos", medida:100, alineacion:"center", datos:"diasvencidos"},
			{nombre:"alcorriente", medida:100, alineacion:"right", tipo:"moneda",  datos:"alcorriente"},
			{nombre:"1_15_Dias", medida:90, alineacion:"right", tipo:"moneda", datos:"c1a15dias"},
			{nombre:"16_30_Dias", medida:90, alineacion:"right", tipo:"moneda",  datos:"c16a30dias"},
			{nombre:"31_60_Dias", medida:90, alineacion:"right", tipo:"moneda", datos:"c31a60dias"},
			{nombre:"Mas_60_Dias", medida:90, alineacion:"right", tipo:"moneda",  datos:"may60dias"},
			{nombre:"Saldo", medida:90, alineacion:"right", tipo:"moneda", datos:"saldo"},
			{nombre:"Factura", medida:90, alineacion:"center",  datos:"factura"},
			{nombre:"Contrarecibo", medida:90, alineacion:"center", datos:"contrarecibo"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla4"
	});
	
	window.onload = function(){		
		consultaTexto("resTabla4","reporteAntiguedadSaldos_con.php?accion=4&contador="+u.pag4_contador.value+
					  "&s="+Math.random());
	}
	
	function resTabla4(datos){
		
		if(!tabla4.creada())
			tabla4.create();
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
		}
		u.pag4_total.value = obj.total;
		u.pag4_contador.value = obj.contador;
		u.pag4_adelante.value = obj.adelante;
		u.pag4_atras.value = obj.atras;
		tabla4.setJsonData(obj.registros);
		
		//totales
		u.t4_vencido.value = "$ "+obj.totales.vencido;
		u.t4_alcorriente.value = "$ "+obj.totales.alcorriente;
		u.t4_total.value = "$ "+obj.totales.total;
		
		if(obj.paginado==1){
			document.getElementById('div_paginado4').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado4').style.visibility = 'hidden';
		}
	}
	function paginacion4(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla4","reporteAntiguedadSaldos_con.php?accion=4&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag4_adelante.value==1){
					consultaTexto("resTabla4","reporteAntiguedadSaldos_con.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)+1)+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag4_atras.value==1){
					consultaTexto("resTabla4","reporteAntiguedadSaldos_con.php?accion=4&contador="+(parseFloat(u.pag4_contador.value)-1)+
						  "&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag4_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla4","reporteAntiguedadSaldos_con.php?accion=4&contador="+contador+
					  "&s="+Math.random());
				break;
		}
	}
	
	
	
	
	function limpiar(){
		if(tabla4.creada()){
			tabla4.clear();
		}
		
		u.t1_clientes.value			= "";
		u.t1_carteravigente.value	= "";
		u.t1_carteramorosa.value	= "";
		u.t1_carteratotal.value		= "";
		u.t4_vencido.value			= "";
		u.t4_alcorriente.value		= "";
		u.t4_total.value			= "";
		
		u.tab_contenedor_id1.disabled=true;		
		u.tab_contenedor_id2.disabled=true;
		u.tab_contenedor_id3.disabled=true;
		u.tab_contenedor_id4.disabled=true;
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
<form id="form1" name="form1" method="post" action="">




<table width="600" height="66" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="24" align="center" class="FondoTabla Estilo4">Reporte Antiguedad Saldos </td>
  </tr>
  <tr>
    <td height="400px" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
     	<table width="550" border="0" cellspacing="0" cellpadding="0">  
  <tr>
    <td width="700" colspan="6">
	<div style="height:280px; width:700px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle3">
		</table>
	</div>	</td>
  </tr>
  <tr>
  	<td colspan="7" align="right">
    	<table align="right">
        	<tr>
            	<td>Vencido</td>
                <td>Al Corriente</td>
                <td>Total</td>
            </tr>
        	<tr>
            	<td><input type="text" value="" class="Tablas" style="text-align:right"  name="t4_vencido" readonly=""/></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t4_alcorriente" readonly=""/></td>
                <td><input type="text" value="" class="Tablas" style="text-align:right"  name="t4_total" readonly=""/></td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td colspan="7"><div id="div_paginado4" align="center" style="visibility:hidden">
              <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion4('primero')" /> 
              <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion4('atras')" /> 
              <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion4('adelante')" /> 
              <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion4('ultimo')" />
          </div>
          <input type="hidden" name="pag4_total" />
          <input type="hidden" name="pag4_contador" value="0" />
          <input type="hidden" name="pag4_adelante" value="" />
          <input type="hidden" name="pag4_atras" value="" />
          <input type="hidden" name="pag4_sucursal" value="" />
          </td>	
  </tr>
</table>
</form>
</body>
</html>
