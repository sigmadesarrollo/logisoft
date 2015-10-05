<? 
	session_start(); 
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');

	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion
	FROM catalogosucursal cs ORDER BY cs.descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
		}
		$desc = substr($desc, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112">
<script>
	
	var tabla1 = new ClaseTabla();
	var u = document.all;
	var pag1_cantidadporpagina = 30;
	var mens = new ClaseMensajes();
	
	mens.iniciar('../javascript');
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"Fecha", medida:60, alineacion:"left",  datos:"fecha"},
			{nombre:"Sucursal", medida:60, alineacion:"left", datos:"sucursal"},
			{nombre:"Ref Cargo", medida:70, alineacion:"left",  datos:"referenciacargo"},
			{nombre:"Ref Abono", medida:70, alineacion:"left", datos:"referenciaabono"},
			{nombre:"Cargo", medida:90, tipo:"moneda", alineacion:"right", datos:"cargos"},
			{nombre:"Abono", medida:90, tipo:"moneda", alineacion:"right", datos:"abonos"},
			{nombre:"Saldo", medida:90, tipo:"moneda", alineacion:"right", datos:"saldo"},
			{nombre:"Descripcion", medida:100, alineacion:"left", datos:"descripcion"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.cliente.focus();
	}
	
	function obtenerCliente(cliente){
		u.cliente.value = cliente;
		consultaTexto("mostrarCliente","reportes_con.php?accion=2&cliente="+u.cliente.value+"&val="+Math.random());
	}
	
	function mostrarCliente(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.nombre.value = obj.cliente;
		}else{
			mens.show("A","El numero de cliente no existe","¡Atención!","cliente");
		}
	}
	
	function obtenerDetalle(){
		if(u.cliente.value==""){
			mens.show("A","Debe capturar Cliente","¡Atención!","cliente");
			return false;
		}
		if(u.checktodas.checked==false){
			if(u.sucursal_hidden.value==""){		
				mens.show('A','Proporcione una sucursal adecuada','¡Atención!');
				return false;
			}
		}
		consultaTexto("resTabla5","reportes_con.php?accion=1&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&cliente="+u.cliente.value+"&contador="+u.pag5_contador.value+"&sucursal="+
							  u.sucursal_hidden.value+"&val="+Math.random());
	}
	
	function resTabla5(datos){
		try {
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos,"Error");
			return false;
		}
		u.pag5_total.value = obj.total;
		u.pag5_contador.value = obj.contador;
		u.pag5_adelante.value = obj.adelante;
		u.pag5_atras.value = obj.atras;
		tabla1.setJsonData(obj.registros);
		
		u.totalAbonado.value = "$ "+((obj.totales.abonos==null)?0:obj.totales.abonos);
		u.totalCargos.value = "$ "+((obj.totales.cargos==null)?0:obj.totales.cargos);
		u.totalSaldo.value = "$ "+((obj.totales.saldo==null)?0:obj.totales.saldo);
		
		if(obj.paginado==1){
			document.getElementById('div_paginado5').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado5').style.visibility = 'hidden';
		}
	}
	function paginacion5(movimiento){
		switch(movimiento){
			case 'primero':
					consultaTexto("resTabla5","reportes_con.php?accion=1&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&cliente="+u.cliente.value+"&contador=0&val="+Math.random());
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("resTabla5","reportes_con.php?accion=1&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&cliente="+u.cliente.value+"&contador="+(parseFloat(u.pag5_contador.value)+1)+"&val="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){					
					consultaTexto("resTabla5","reportes_con.php?accion=1&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&cliente="+u.cliente.value+"&contador="+(parseFloat(u.pag5_contador.value)-1)+"&val="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla5","reportes_con.php?accion=1&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+
							  "&cliente="+u.cliente.value+"&contador="+contador+"&val="+Math.random());
				break;
		}
	}
	
	function bloquearSucursal(valor){
		u.sucursal.readOnly=valor;
		u.sucursal.style.backgroundColor=(valor)?"#FFFF99":"";
		u.sucursal.value = "";
		u.sucursal_hidden.value = "";
		document.getElementById('imagenBuscarSucursal').style.display = (valor)?'none':'';
	}
	
	function obtenerSucursal(id,descripcion){
		u.sucursal.value = descripcion;
		u.sucursal_hidden.value = id;
		u.sucursal.codigo = id;
	}
	
	function imprimirDetalle(){
		if(u.cliente.value==""){
			mens.show("A","Debe capturar Cliente","¡Atención!","cliente");
			return false;
		}
		if(u.checktodas.checked==false){
			if(u.sucursal_hidden.value==""){		
				mens.show('A','Proporcione una sucursal adecuada','¡Atención!');
				return false;
			}
		}
		if(document.URL.indexOf("web/")>-1){		
			window.open("https://www.pmmintranet.net/web_capacitacion/reportesWeb/estadoCuenta_Excel.php?cliente="+u.cliente.value+
						"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
			
		}else if(document.URL.indexOf("web_capacitacion/")>-1){
			window.open("https://www.pmmintranet.net/web_capacitacion/reportesWeb/estadoCuenta_Excel.php?cliente="+u.cliente.value+
						"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
			
		}else if(document.URL.indexOf("web_pruebas/")>-1){
			window.open("https://www.pmmintranet.net/web_pruebas/reportesWeb/estadoCuenta_Excel.php?cliente="+u.cliente.value+
						"&fechainicio="+u.fecha.value+"&fechafin="+u.fecha2.value+"&sucursal="+u.sucursal_hidden.value);
		}
	}
	var desc = new Array(<?php echo $desc; ?>);
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="630" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla" >ESTADO DE CUENTA</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
	  	<tr>
			<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="8%">Cliente:</td>
                <td width="16%"><label>
                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:70px" onkeypress="if(event.keyCode==13){obtenerCliente(this.value)}" />
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen2.php?funcion=obtenerCliente', 600, 450, 'ventana', 'Busqueda')" style="cursor:pointer" /></label></td>
                <td width="60%"><label>
                  <input name="nombre" type="text" id="nombre" class="Tablas" style="width:350px; background:#FFFF99" readonly="" />
                </label></td>
                <td width="16%">&nbsp;</td>
                </tr>
            </table></td>
		</tr>
        <tr>
        	<td>
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="57">Sucursal</td>
                        <td width="23"><input type="checkbox" name="checktodas" checked="checked" onclick="bloquearSucursal(this.checked);" /></td>
                        <td width="49">Todas</td>
                        <td width="395"><input name="sucursal" type="text" id="sucursal" style="width:200px; background:#FFFF99" readonly="readonly" autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value='';}}" onblur="document.all.sucursal_hidden.value = this.codigo; if(u.sucursal_hidden.value=='undefined'){ this.codigo=''; u.sucursal_hidden.value=''; }" />
                  <img src="../img/Buscar_24.gif" id="imagenBuscarSucursal" width="24" height="23" align="absbottom" style="cursor:pointer; display:none" onclick="abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /> </td>
                        <td width="102"></td>
                    </tr>
                </table>
            </td>
        </tr>
		<tr>
			<td>
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
		  		<td width="100">Fecha Inicial: </td>
				<td width="113"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px" value="<?=date('d/m/Y')?>" onkeypress="if(event.keyCode==13){document.all.fecha2.focus();}"/></td>
				<td width="82"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha,'dd/mm/yyyy',this)"></div></td>
				<td width="100">Fecha Final: </td>
				<td width="113"><input name="fecha2" type="text" class="Tablas" id="fecha2" style="width:100px" value="<?=date('d/m/Y') ?>" onkeypress="if(event.keyCode==13){obtenerDetalle();}"/></td>
				<td width="67"><div class="ebtn_calendario" onclick="displayCalendar(document.all.fecha2,'dd/mm/yyyy',this)"></div></td>
				<td width="270"><div class="ebtn_Generar" onclick="obtenerDetalle()" ></div></td>
				<td width="1"></td>
			  </tr>
			</table>
			</td>
	    </tr> 
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td>
          	<table align="right">
            	<tr>
                    <td>Total Cargos</td>
                    <td><input type="text" name="totalCargos" style="width:80px; text-align:right" /></td>
                	<td>Total Abonado</td>
                    <td><input type="text" name="totalAbonado" style="width:80px; text-align:right" /></td>
                	<td>Total Saldo</td>
                    <td><input type="text" name="totalSaldo" style="width:80px; text-align:right" /></td>
                </tr>
          	</table>
          </td>
        </tr>
        <tr>
          <td><div id="div_paginado5" align="center" style="visibility:hidden">
              <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion5('primero')" /> 
              <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion5('atras')" /> 
              <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion5('adelante')" /> 
              <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion5('ultimo')" />          
              <input type="hidden" name="pag5_total" />
              <input type="hidden" name="pag5_contador" value="0" />
              <input type="hidden" name="pag5_adelante" value="" />
              <input type="hidden" name="pag5_atras" value="" />
              <input type="hidden" name="pag5_idcliente" value="" />
              <input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="" />
          </div>
		  </td>
        </tr>
        <tr>
    	<td align="right">
                <table>
                    <tr>
                        <td width="106" align="center"><img src="../img/Boton_Imprimir.gif" onclick="imprimirDetalle()" /></td>
                    </tr>
                </table>
            </td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
