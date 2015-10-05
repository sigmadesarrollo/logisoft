<?	session_start();
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
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script>	
	var tabla4 = new ClaseTabla();
	var u = document.all;
	var pag1_cantidadporpagina = 30;
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript');
	
	tabla4.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"SUCURSAL", medida:80, alineacion:"center",  datos:"prefijosucursal"},
			{nombre:"CLIENTE", medida:120, alineacion:"center", datos:"cliente"},
			{nombre:"FOLIO", medida:90, alineacion:"center",  datos:"folio"},
			{nombre:"FECHAGUIA", medida:80, alineacion:"center", datos:"fechaguia"},
			{nombre:"FECHAFACT", medida:80, alineacion:"center", datos:"fechafactura"},
			{nombre:"F_VENCI", medida:80, alineacion:"center",  datos:"fechavenc"},
			{nombre:"DIAS VENCIDOS", medida:100, alineacion:"center", datos:"diasvencidos"},
			{nombre:"AL CORRIENTE", medida:100, alineacion:"right", tipo:"moneda",  datos:"alcorriente"},
			{nombre:"1_15_DIAS", medida:90, alineacion:"right", tipo:"moneda", datos:"c1a15dias"},
			{nombre:"16_30_DIAS", medida:90, alineacion:"right", tipo:"moneda",  datos:"c16a30dias"},
			{nombre:"31_60_DIAS", medida:90, alineacion:"right", tipo:"moneda", datos:"c31a60dias"},
			{nombre:"MAS_60_DIAS", medida:90, alineacion:"right", tipo:"moneda",  datos:"may60dias"},
			{nombre:"SALDO", medida:90, alineacion:"right", tipo:"moneda", datos:"saldo"},
			{nombre:"FACTURA", medida:90, alineacion:"center",  datos:"factura"},
			{nombre:"CONTRARECIBO", medida:90, alineacion:"center", datos:"contrarecibo"},
			{nombre:"TRASPASO CARGO", medida:40, alineacion:"center", datos:"tcargo"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla4"
	});
	
	window.onload = function(){
		tabla4.create();
	}
	
	function obtenerDetalle(){
		var todasucursales = false;
		try{
			if((u.sucursal_hidden.value==null || u.sucursal_hidden.value.toUpperCase()=="UNDEFINED") && u.todassucursales.checked==false){
				mens.show("A","Debe capturar la Sucursal","¡Atención!","sucursal");
				return false;
			}
			if(u.todassucursales.checked){
				var todasucursales = true;
			}else{
				var todasucursales = false;
			}
		}catch(e){
			e = null;
		}	
		consultaTexto("resTabla4","reportes_con.php?accion=5&sucursal="+u.sucursal_hidden.value+"&contador="+u.pag4_contador.value+"&idCliente="+u.idCliente.value
		+"&todassucursales="+todasucursales+"&s="+Math.random());			
	}
	
	function resTabla4(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			mens.show("A",datos);
			return false;
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
				consultaTexto("resTabla4","reportes_con.php?accion=5&contador=0&sucursal="+u.sucursal_hidden.value+<?=($_SESSION[IDSUCURSAL]==1)?'"&todassucursales="+u.todassucursales.checked+':''?>"&idCliente="+u.idCliente.value+"&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag4_adelante.value==1){
					consultaTexto("resTabla4","reportes_con.php?accion=5&contador="+(parseFloat(u.pag4_contador.value)+1)+
						  "&sucursal="+u.sucursal_hidden.value+
						  <?=($_SESSION[IDSUCURSAL]==1)?'"&todassucursales="+u.todassucursales.checked+':''?>"&idCliente="+u.idCliente.value+"&s="+Math.random());
					break;
				}
				break;
			case 'atras':
				if(u.pag4_atras.value==1){
					consultaTexto("resTabla4","reportes_con.php?accion=5&contador="+(parseFloat(u.pag4_contador.value)-1)+
						  "&sucursal="+u.sucursal_hidden.value+
						  <?=($_SESSION[IDSUCURSAL]==1)?'"&todassucursales="+u.todassucursales.checked+':''?>"&idCliente="+u.idCliente.value+"&s="+Math.random());
					break;
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag4_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla4","reportes_con.php?accion=5&contador="+contador+
					  "&sucursal="+u.sucursal_hidden.value+
					  <?=($_SESSION[IDSUCURSAL]==1)?'"&todassucursales="+u.todassucursales.checked+':''?>"&idCliente="+u.idCliente.value+"&s="+Math.random());
				break;
		}
	}
	
	function obtenerSucursal(suc){
		u.sucursal_hidden.value = suc;
		consultaTexto("mostrarSucursal","reportes_con.php?accion=4&sucursal="+suc);
	}
	
	function mostrarSucursal(datos){
		var obj = eval(datos);
		u.sucursal.value = obj.sucursal;
	}
	
	function imprimirReporte(){
		if(tabla4.getRecordCount()>0){
			if(document.URL.indexOf("web_capacitacionPruebas/")>-1){		
				var v_dir = "https://www.pmmintranet.net/web_capacitacionPruebas/general/cobranza/";
			}else if(document.URL.indexOf("web_capacitacion/")>-1){
				var v_dir = "https://www.pmmintranet.net/web_capacitacion/general/cobranza/";
			}else if(document.URL.indexOf("web_pruebas/")>-1){
				var v_dir = "https://www.pmmintranet.net/web_pruebas/general/cobranza/";
			}
			window.open(v_dir+"generarExcelAntiguedadSaldos.php?reporteweb=s&sucursal="+u.sucursal_hidden.value+"&idCliente="+u.idCliente.value+"&val="+Math.random());
		}else{
			mens.show("A","Debe generar el reporte","¡Atención!","sucursal");
		}
	}
	
	function f_todas(valor){
		document.getElementById('botonBuscar').style.display = (valor)?"none":"";
		document.all.sucursal.value = "";
		document.all.sucursal_hidden.value = "";
		document.all.sucursal.readOnly = (valor)?true:false;
		document.all.sucursal.style.backgroundColor = (valor)?"#FFFF99":"";
	}
	
	function obtenerCliente(idCliente){
		u.idCliente.value = idCliente;
		consultaTexto("mostrarCliente","reportes_con.php?accion=2&cliente="+idCliente);
	}
	
	function mostrarCliente(datos){

		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.nombre.value = obj.cliente;
		}else{
			mens.show("A","El numero de cliente no existe","¡Atención!","cliente");
		}
		
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
	<tr>
		<td class="FondoTabla">Reporte Antig&uuml;edad de Saldos </td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="7%"><?=($_SESSION[IDSUCURSAL]==1)?"Todas":"";?></td>
					<td width="5%">
						<?=($_SESSION[IDSUCURSAL]==1)?'<input type="checkbox" name="todassucursales" value="si" onclick="f_todas(this.checked)" />':"";?>
					</td>
					<td width="7%" id="nombreSucursal">Sucursal:</td>
					<td width="80%">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="83%"><input name="sucursal" type="text" id="sucursal" style="width:250px" autocomplete="array:desc" onkeypress=		"if(event.keyCode==13){document.all.sucursal_hidden.value = this.codigo; document.all.idCliente.focus();};" onblur="document.all.sucursal_hidden.value = this.codigo;" />
									<img id="botonBuscar" src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick=	"abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 600, 450, 'ventana', 'Busqueda');" /></td><br />
								<td width="17%"><div class="ebtn_Generar" onclick="setTimeout('obtenerDetalle()',500)"></div></td>
							</tr>											
						</table>
					</td>
					<td width="1%" valign="bottom">&nbsp;</td>
				</tr>
				<tr><td><br /></td></tr>
				<tr>
				    <td width="7%"></td>
					<td width="5%"></td>
					<td width="7%" id="cliente" align="left">Cliente:</td>
					<td>
						<input name="idCliente" type="text" id="idCliente" style="width:80px" onkeypress="if(event.keyCode==13){obtenerCliente(this.value);}"/>
						<img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen2.php?funcion=obtenerCliente', 600, 450, 'ventana', 'Busqueda')" />&nbsp;&nbsp;
					<input name="nombre" type="text" class="Tablas" id="nombre" style="width:300px" readonly="" /></td>
				</tr>
				<tr><td><br /></td></tr>
				<tr>
					<td colspan="5"><div style="height:270px; width:799px; overflow:auto" align="left">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">              
						</table></div></td>
				</tr>
				<tr>
					<td colspan="5">
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
					<td colspan="5" >
						<div id="div_paginado4" align="center" style="visibility:hidden">
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
							<input name="sucursal_hidden" type="hidden" id="sucursal_hidden" value="" />
					</td>
				</tr>
				<tr>
					<td colspan="5" align="right"><div class="ebtn_imprimir" onclick="imprimirReporte()"></div></td>
				</tr>
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>
