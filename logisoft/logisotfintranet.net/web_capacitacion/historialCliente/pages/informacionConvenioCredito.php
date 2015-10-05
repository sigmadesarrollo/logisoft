<?	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script language="javascript" src="../../javascript/ClaseTabla.js"></script>
<script>
var combo1 = "<select name='sltsucursales' id='sltsucursales' class='Tablas' style='width:210px' onKeyPress='return tabular(event,this)'>";
var div_preciokg ='<table width="530" border="0"><tr><td>Precio por KG</td></tr>  <tr><td><div id="div_preciokg" name="detalle" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var div_descripcion ='<table width="530" border="0"><tr><td>Precio por Caja/Paquete</td></tr>  <tr><td><div id="div_descripcion" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var var_prepagadas = '<table width="530" border="0"><tr><td><table border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="78">Pre-Pagadas</td><td width="203" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" id="limitekg" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="40">Costo:</td><td width="205" class="Tablas"><input name="costoguia" type="text" class="Tablas" id="costoguia" style="width:100px;background:#FFFF99; text-align:right" value="<?=$Costo ?>" readonly="readonly"/></td></tr></table></td></tr></table>';
var detallekgc ='<table width="530" border="0"><tr><td><table border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="78">Pre-Pagadas</td><td width="203" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" id="limitekg" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="40">Costo:</td><td width="205" class="Tablas"><input name="costoguia" type="text" class="Tablas" id="costoguia" style="width:100px;background:#FFFF99; text-align:right" value="<?=$Costo ?>" readonly="readonly"/></td></tr></table></td></tr><tr><td>Precio por KG</td></tr>  <tr><td><div id="detallekgc" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var detalledescripcionc='<table width="530" border="0"><tr><td><table border="0" id="col_prepagadas" cellpadding="0" cellspacing="0"><tr><td width="78">Pre-Pagadas</td><td width="203" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly"></td><td width="40">Costo:</td><td width="205" class="Tablas"><input name="costoguia" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right" readonly="readonly" value="<?=$Costo ?>"/></td></tr>    </table></td></tr><tr><td>Precio por Caja/Paquete</td></tr>  <tr><td><div id="detalledescripcionc" name="detalle" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var descuentocon='<table width="530" border="0" cellspacing="0" cellpadding="0"><tr><td><table border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="78">Pre-Pagadas</td><td width="203" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" id="limitekg" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="40">Costo:</td><td width="205" class="Tablas"><input name="costoguia" type="text" class="Tablas" id="costoguia" style="width:100px;background:#FFFF99; text-align:right" value="<?=$Costo ?>" readonly="readonly"/></td></tr></table></td></tr> <tr><td><table width="257" border="0" cellpadding="0" cellspacing="0"><tr><td>Consignaci&oacute;n Descuento</td><td width="113" class="Tablas"><input name="consignaciondes" type="text" class="Tablas" id="consignaciondes" style="width:100px;background:#FFFF99" readonly="readonly" value="<?=$consignaciondes ?>"/></td></tr></table></td></tr></table>';
var descuento = '<table width="534" border="0" cellpadding="0" cellspacing="0"><tr> <td width="150">Descuento Sobre Flete:</td><td width="384"><input name="descuentoflete" type="text" class="Tablas" id="descuentoflete" style="width:100px;background:#FFFF99" readonly="readonly" /></td></tr></table>';
	
	var u = document.all;
	var tablaservicios = new ClaseTabla();
	var tablaservicios2 = new ClaseTabla();
	
	tablaservicios.setAttributes({
		nombre:"tabladeservicios",
		campos:[
			{nombre:"Servicio", medida:280, alineacion:"left", datos:"servicio"},
			{nombre:"Cobro", medida:110, alineacion:"right", datos:"cobro"},
			{nombre:"Precio", medida:110, alineacion:"center", tipo:"moneda", datos:"precio"}
		],
		filasInicial:5,
		alto:80,
		seleccion:true,
		ordenable:false,
		nombrevar:"tablaservicios"
	});	
	tablaservicios2.setAttributes({
		nombre:"tabladeservicios2",
		campos:[
			{nombre:"Servicio", medida:280, alineacion:"left", datos:"servicio"},
			{nombre:"Cobro", medida:110, alineacion:"right", datos:"cobro"},
			{nombre:"Precio", medida:110, alineacion:"center", tipo:"moneda", datos:"precio"}
		],
		filasInicial:5,
		alto:80,
		seleccion:true,
		ordenable:false,
		nombrevar:"tablaservicios2"
	});
	
	window.onload = function(){
		seleccionarTabs(<?=$_GET[tab]?>);		
		tablaservicios.create();
		tablaservicios2.create();
		u.tabladeservicios.style.display = "none";
		u.tabladeservicios2.style.display= "none";
		document.getElementById("tab1").style.visibility = "hidden";
		document.getElementById("tab0").style.visibility = "hidden";
		obtenerCliente('<?=$_GET[cliente] ?>');
	}	
	function aceptar(){
		var p = parent.document.all;
		p.activado.value = ((u.activado.checked==true)?'SI':'NO');
		p.clasificacioncliente.value = u.clasificacioncliente.value;
		parent.VentanaModal.cerrar();
	}
	function cerrar(){		
		parent.VentanaModal.cerrar();
	}	
	function obtenerCliente(cliente){
		if(cliente!=""){
			consultaTexto("mostrarCliente","../../catalogos/cliente/consultaCredito_con.php?accion=1&tipo=1&cliente="+cliente
			+"&val="+Math.random());
		}
	}
	function mostrarCliente(datos){
		if(datos.indexOf("no encontro")<0){			
			var obj = eval(convertirValoresJson(datos));
				u.foliocredito.value	= obj[0].foliocredito;
				u.activado.checked		= ((obj[0].activado=="SI")?true:false);
				//u.activado.disabled		= ((obj[0].estado=="ACTIVADO")?false:true);
				u.clasificacioncliente.value = obj[0].clasificacioncliente;
				u.saldo.value			= '$ '+numcredvar(obj[0].saldo);
				u.disponible.value		= ((obj[0].disponible<"0")?0:'$ '+numcredvar(obj[0].disponible.toString()));
				u.ventames.value		= obj[0].ventames;
				u.limitecredito.value	= '$ '+numcredvar(obj[0].limitecredito);
				u.diacredito.value		= obj[0].diascredito;
				u.diapago.value			= obj[0].diapago;
				u.diarevision.value		= obj[0].diarevision;
				if(u.foliocredito.value!="" || u.foliocredito.value!="0"){
				consulta("mostrarSucursales","../../catalogos/cliente/consultasClientes.php?accion=4&cliente=<?=$_GET[cliente] ?>&credito="+u.foliocredito.value);
				}				
		}else{
		
			consulta("mostrarConvenio","../../catalogos/cliente/consultasClientes.php?accion=7&cliente=<?=$_GET[cliente] ?>");
		}
	}
	function mostrarSucursales(datos){
		if(datos.getElementsByTagName('total').item(0).firstChild.data>=1){
				u.celsuc.innerHTML = combo1;
			var combo = document.all.sltsucursales;		
				combo.options.length = null;
				uOpcion = document.createElement("OPTION");
				uOpcion.value=0;
				uOpcion.text="SUC. EN LAS QUE APLICA CREDITO";
				combo.add(uOpcion);
			var total =datos.getElementsByTagName('total').item(0).firstChild.data;
			for(i=0;i<total;i++){	
				uOpcion = document.createElement("OPTION");
				uOpcion.value=datos.getElementsByTagName('sucursal').item(i).firstChild.data;
				uOpcion.text=datos.getElementsByTagName('sucursal').item(i).firstChild.data;
				combo.add(uOpcion);
			}			
		}
		
		consulta("mostrarConvenio","../../catalogos/cliente/consultasClientes.php?accion=7&cliente=<?=$_GET[cliente] ?>");
	}
	function mostrarConvenio(datos){	
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			limpiarConvenios();		
				u.convenio_d.style.display = "";
				u.folioconvenio.value = datos.getElementsByTagName('folio').item(0).firstChild.data;
				u.vigencia.value = datos.getElementsByTagName('vigencia').item(0).firstChild.data;
				u.estado.value = datos.getElementsByTagName('estadoconvenio').item(0).firstChild.data;
				var precioporkg = datos.getElementsByTagName('precioporkg').item(0).firstChild.data;
				var preciocaja = datos.getElementsByTagName('precioporcaja').item(0).firstChild.data;	
				var descuentoflete = datos.getElementsByTagName('descuentoflete').item(0).firstChild.data;
				var consignacionkg = datos.getElementsByTagName('consignacionkg').item(0).firstChild.data;
				var consignacioncaja = datos.getElementsByTagName('consignacioncaja').item(0).firstChild.data;
				var consigdescuento = datos.getElementsByTagName('consignaciondescuento').item(0).firstChild.data;			
				var prepagadas = datos.getElementsByTagName('prepagadas').item(0).firstChild.data;
			if(precioporkg==0 && preciocaja==0 && descuentoflete==0){
				u.normales.style.display="none";
			}else{
				u.normales.style.display="";
				if(precioporkg==1){
					u.div1.innerHTML = div_preciokg;
			consultaTexto("mostrarCGridKg", "../../catalogos/cliente/consultasHTML.php?accion=6&valor=1&idconvenio="+u.folioconvenio.value
			+"&cliente=<?=$_GET[cliente] ?>&val="+Math.random());
				}
				if(preciocaja==1){
					u.div1.innerHTML = div_descripcion;
			consultaTexto("mostrarCGridPeso", "../../catalogos/cliente/consultasHTML.php?accion=6&valor=2&idconvenio="+u.folioconvenio.value
			+"&cliente=<?=$_GET[cliente] ?>&val="+Math.random());
				}
				if(descuentoflete==1){
				u.div1.innerHTML = descuento;
				u.descuentoflete.value="";
				u.descuentoflete.value = datos.getElementsByTagName('cantidaddescuento').item(0).firstChild.data;	
				}
			}
			
			var v_prepagadas ='if(prepagadas==0){u.col_prepagadas.style.display="none";}else{u.costoguia.value = datos.getElementsByTagName("costo").item(0).firstChild.data; u.limitekg.value = datos.getElementsByTagName("limitekg").item(0).firstChild.data;}';
			
			if(consignacionkg==0 && consignacioncaja==0 && prepagadas==0 && consigdescuento==0){
				u.empresariales.style.display="none";
			}else{
				u.empresariales.style.display="";
				if(prepagadas==1){
					u.div2.innerHTML = var_prepagadas;
				}
				if(consignacionkg==1){
					u.div2.innerHTML = detallekgc;
					if(prepagadas==1){
						v_prepagadas;
					}
				consultaTexto("mostrarSGridKg", "../../catalogos/cliente/consultasHTML.php?accion=6&valor=3&idconvenio="+u.folioconvenio.value
				+"&cliente=<?=$_GET[cliente] ?>&val="+Math.random());
				}
				
				if(consignacioncaja==1){
					u.div2.innerHTML = detalledescripcionc;
					if(prepagadas==1){
						v_prepagadas;
					}
				consultaTexto("mostrarSGridPeso", "../../catalogos/cliente/consultasHTML.php?accion=6&valor=4&idconvenio="+u.folioconvenio.value
				+"&cliente=<?=$_GET[cliente] ?>&val="+Math.random());
				}
				
				if(consigdescuento==1){
					u.div2.innerHTML = descuentocon;
					if(prepagadas==1){
						v_prepagadas;
					}
			u.consignaciondes.value = datos.getElementsByTagName('cantidaddescuentoconsignacion').item(0).firstChild.data;		
				}
				
				if(prepagadas==1){
					u.costoguia.value = "";
					u.limitekg.value  = "";
					u.costoguia.value = datos.getElementsByTagName('costo').item(0).firstChild.data;
					u.limitekg.value = datos.getElementsByTagName('limitekg').item(0).firstChild.data;
					u.preciokgexcedente.value = datos.getElementsByTagName('preciokgexcedente').item(0).firstChild.data;		
				}else{
					u.col_prepagadas.style.display="none";
				}
			}
			consultaTexto("mostrarGrids","../../catalogos/cliente/consultasHTML.php?accion=8&idconvenio="+u.folioconvenio.value
			+"&cliente=<?=$_GET[cliente] ?>&val="+Math.random());
		}else{
			u.empresariales.style.display="none";
			u.normales.style.display="none";
		}
	}	
	function mostrarGrids(datos){		
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		agregarValores(u.serviciosr1,objeto[0].serviciocombo1);
		agregarValores(u.sucursalesead1,objeto[0].serviciocombo2);
		agregarValores(u.serviciosr2,objeto[0].serviciocombo3);
		agregarValores(u.sucursalesead2,objeto[0].serviciocombo4);		
		if(objeto[0].serviciogrid1!="0"){
			u.tabladeservicios.style.display = "";
			agregarGrid(tablaservicios,objeto[0].serviciogrid1);
		}		
		if(objeto[0].serviciogrid2!="0"){	
			u.tabladeservicios2.style.display= "";
			agregarGrid(tablaservicios2,objeto[0].serviciogrid2);
		}
	}
	
	function mostrarCGridKg(datos){		
		u.div_preciokg.innerHTML = datos;
	}
	function mostrarCGridPeso(datos){
		u.div_descripcion.innerHTML = datos;
	}
	function mostrarSGridKg(datos){
		u.detallekgc.innerHTML = datos;
	}
	function mostrarSGridPeso(datos){
		u.detalledescripcionc.innerHTML = datos;
	}
	function agregarGrid(tabla,objeto){
		for(var i=0; i<objeto.length; i++)
			tabla.add(objeto[i]);
	}
	function agregarValores(combo,objeto){
		combo.options.length = 0;
		var opcion;
		for(var i=0; i<objeto.length; i++){
			opcion = new Option(objeto[i].nombre,objeto[i].clave);
			
			combo.options[combo.options.length] = opcion;
		}
	}
	function limpiarConvenios(){
		tablaservicios.clear();
		tablaservicios2.clear();
		u.tabladeservicios.style.display = "none";
		u.tabladeservicios2.style.display = "none";
	}
	function limpiarCredito(){
		u.foliocredito.value	= "";
		u.activado.checked		= false;
		u.clasificacion.value	= "SELECCIONAR";
		u.saldo.value			= "";
		u.disponible.value		= "";
		u.ventames.value		= "";
		u.limitecredito.value	= "";
		u.diacredito.value		= "";
		u.diapago.value			= "";
		u.diarevision.value		= "";
		u.celsuc.innerHTML = combo1;
		var combo = document.all.sltsucursales;		
		combo.options.length = null;
		uOpcion = document.createElement("OPTION");
		uOpcion.value=0;
		uOpcion.text = "SUC. EN LAS QUE APLICA CREDITO";
		combo.add(uOpcion);
	}
	function seleccionarTabs(seleccion){
		var totaltabs 	= 2;
		var estilosel 	= "tab_seleccionado";
		var estilodesel = "tab_deseleccionado";
		var tabs		= "tab";
		var canvas		= "canvas";
		
		for(var i=0; i<totaltabs; i++){
			if(seleccion==i){
				document.getElementById(tabs+i).className = estilosel;
			}else{
				document.getElementById(tabs+i).className = estilodesel;
			}
		}
		
		for(var i=0; i<totaltabs; i++){
			if(seleccion==i){
				document.getElementById(canvas+i).style.visibility = "visible";
			}else{
				document.getElementById(canvas+i).style.visibility = "hidden";
			}
		}
	}
	function activarCredito(){
		if(u.foliocredito.value!="" || u.foliocredito.value!="0"){
			if(u.activado.checked == true){
				confirmar('¿Esta seguro de Activar el Credito?','','confirmarActivacion(0)','');
			}else{
				confirmar('¿Esta seguro de Desactivar el Credito?','','confirmarActivacion(1)','');
			}
		}
	}
	function confirmarActivacion(tipo){
		u.activado.checked = ((tipo==0)?true:false);
	}
	function numcredvar(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString(); 
		if(flag) cad += '.'; 
		return cad;
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../../javascript/ajax.js"></script>
<script language="javascript" src="../../javascript/ClaseMensajes.js"></script>
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
.Estilo4 {font-size: 12px}
body {
	margin-left: 1px;
	margin-top: 5px;
	margin-right: 1px;
	margin-bottom: 1px;
}
-->
</style>
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="591" border="0" align="left" cellpadding="0" cellspacing="0">
	<tr>
    	<td width="178" id="tab0" height="21" class="tab_seleccionado" onclick="seleccionarTabs(0)" align="center">DATOS CREDITO </td>
        <td width="176" id="tab1" class="tab_deseleccionado" onclick="seleccionarTabs(1)" align="center">DATOS CONVENIO </td>
        <td width="237"></td>
    </tr>
    <tr>
    	<td height="257" colspan="3">&nbsp;</td>
    </tr>
</table>
<div style="position:absolute; left: 2px; top: 24px; width: 570px; visibility:visible;" id="canvas0">
<table width="571" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td height="109"><table width="559" border="0" id="credito" cellpadding="1" cellspacing="0">
      <tr>
        <td class="Tablas"><input name="activado" type="checkbox" id="activado" style="width:13px" value="SI" onclick="activarCredito()" />
          Activado</td>
        <td width="134" class="Tablas">&nbsp;</td>
        <td width="10" class="Tablas">&nbsp;</td>
        <td class="Tablas">&nbsp;</td>
        <td width="222" class="Tablas">&nbsp;</td>
      </tr>
      <tr>
        <td class="Tablas">Folio Cr&eacute;dito:</td>
        <td colspan="2" class="Tablas"><input name="foliocredito" type="text" class="Tablas" id="foliocredito" value="<?=$f->foliocredito ?>"  readonly="readonly" /></td>
        <td class="Tablas">Clasificaci&oacute;n:</td>
        <td class="Tablas"> <select name="clasificacioncliente" id="clasificacioncliente" class="Tablas" style="width:123px; font-size:9px; text-transform:uppercase">
                      <option value="SELECCIONAR" selected="selected" class="Tablas" >Seleccionar</option>
                      <option value="MALO" <? if($f->clasificacioncliente=="MALO"){echo 'selected';} ?>>MALO</option>
                      <option value="BUENO" <? if($f->clasificacioncliente=="BUENO"){echo 'selected';} ?>>BUENO</option>
                      <option value="REGULAR" <? if($f->clasificacioncliente=="REGULAR"){echo 'selected';} ?>>REGULAR</option>
                      <option value="EXCELENTE" <? if($f->clasificacioncliente=="EXCELENTE"){echo 'selected';} ?>>EXCELENTE</option>
                    </select> </td>
      </tr>
      <tr>
        <td width="89" class="Tablas">Saldo:</td>
        <td colspan="2" class="Tablas"><input name="saldo" type="text" class="Tablas" id="saldo" value="<?=$f->saldo ?>" readonly="readonly" /></td>
        <td width="94" class="Tablas">Disponible:</td>
        <td class="Tablas"><input name="disponible" type="text" class="Tablas" id="disponible" value="<?=$f->disponible ?>" readonly="readonly" /></td>
      </tr>
      <tr>
        <td class="Tablas">Limite Credito: </td>
        <td colspan="2" class="Tablas"><input name="limitecredito" type="text" class="Tablas" id="limitecredito" value="<?=$f->limitecredito ?>" readonly="readonly" /></td>
        <td class="Tablas">Ventas Mes: </td>
        <td class="Tablas"><input name="ventames" type="text" class="Tablas" id="ventames" value="<?=$f->ventames ?>"  readonly="readonly" /></td>
      </tr>
      <tr>
        <td class="Tablas">D&iacute;as Cr&eacute;dito:</td>
        <td colspan="2" class="Tablas" ><input name="diacredito" type="text" class="Tablas" id="diacredito" value="<?=$f->diacredito ?>" readonly="readonly" /></td>
        <td class="Tablas" >D&iacute;as Revisi&oacute;n:</td>
        <td class="Tablas" ><input name="diarevision" type="text" class="Tablas" id="diarevision" value="<?=$f->diasrevision ?>" readonly="readonly" /></td>
      </tr>
      <tr>
        <td class="Tablas">D&iacute;as Pago:</td>
        <td class="Tablas" ><input name="diapago" type="text" class="Tablas" id="diapago" value="<?=$f->diapago ?>" readonly="readonly" /></td>
        <td class="Tablas">&nbsp;</td>
        <td class="Tablas" >Sucursales Cred:</td>
        <td class="Tablas" id="celsuc"><select class="Tablas" name="sltsucursales" id="sltsucursales" style="width:210px; font-size:9px">
          <option selected="selected" style="width:205px">SUC. EN LAS QUE APLICA CREDITO</option>
        </select></td>
      </tr>
      <tr>
        <td class="Tablas">&nbsp;</td>
        <td class="Tablas" >&nbsp;</td>
        <td class="Tablas">&nbsp;</td>
        <td class="Tablas" >&nbsp;</td>
        <td class="Tablas" id="celsuc">&nbsp;</td>
      </tr>
      <tr>
        <td class="Tablas">&nbsp;</td>
        <td class="Tablas" >&nbsp;</td>
        <td class="Tablas">&nbsp;</td>
        <td class="Tablas" >&nbsp;</td>
        <td class="Tablas" id="celsuc">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</div>

<div style="position:absolute; left: 2px; top: 24px; width: 570px; visibility:hidden;" id="canvas1">
<table width="565" border="1" bordercolor="#016193" cellspacing="0" cellpadding="0" id="convenio_d">
	<tr>		
		<td>
<table width="564" cellspacing="0" cellpadding="0" >
              <tr>
                <td width="116">&nbsp;</td>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="18%">Folio Convenio:</td>
                    <td width="14%"><input name="folioconvenio" type="text" class="Tablas" id="folioconvenio" readonly="readonly" style="width:70px" /></td>
                    <td width="12%">Vigencia:</td>
                    <td width="17%"><span class="Tablas">
                      <input name="vigencia" type="text" class="Tablas" id="vigencia" readonly="readonly" style="width:80px" />
                    </span></td>
                    <td width="9%">Estado:</td>
                    <td width="30%"><label>
                      <input name="estado" type="text" class="Tablas" id="estado" style="width:120px" readonly="" />
                    </label></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="4"><table width="100%" border="0" cellpadding="0" cellspacing="0" id="normales">
                  <tr>
                    <td class="FondoTabla">Guia Normal</td>
                  </tr>
                  <tr>
                    <td><table width="534" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2" id="div1"></td>
                        </tr>
                        <tr>
                          <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios">
                          </table></td>
                        </tr>
                        <tr>
                          <td><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="9" height="16"   class="formato_columnas_izq"></td>
                                <td width="250"class="formato_columnas" align="center">SERVICIOS RESTRINGIDOS POR EL CLIENTE </td>
                                <td width="9"class="formato_columnas_der"></td>
                              </tr>
                              <tr>
                                <td colspan="12"><div align="center">
                                    <select name="serviciosr1" size="7" class="Tablas" style="width:265px" id="serviciosr1">
                                    </select>
                                </div></td>
                              </tr>
                          </table></td>
                          <td><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="9" height="16"   class="formato_columnas_izq"></td>
                                <td width="250"class="formato_columnas" align="center">SUCURSALES QUE APLICA EAD </td>
                                <td width="9"class="formato_columnas_der"></td>
                              </tr>
                              <tr>
                                <td colspan="12"><div align="center">
                                    <select name="sucursalesead1" size="7" class="Tablas" style="width:265px" id="sucursalesead1">
                                    </select>
                                </div></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><table width="100%" border="0" cellpadding="0" cellspacing="0" id="empresariales">
                  <tr>
                    <td class="FondoTabla">Guia Empresarial</td>
                  </tr>
                  <tr>
                    <td><table width="534" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2" id="div2"></td>
                        </tr>
                        <tr>
                          <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios2">
                          </table></td>
                        </tr>
                        <tr>
                          <td><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="9" height="16"   class="formato_columnas_izq"></td>
                                <td width="250"class="formato_columnas" align="center">SERVICIOS RESTRINGIDOS POR EL CLIENTE </td>
                                <td width="9"class="formato_columnas_der"></td>
                              </tr>
                              <tr>
                                <td colspan="12"><div align="center">
                                    <select name="serviciosr2" size="7" class="Tablas" style="width:265px" id="serviciosr2">
                                    </select>
                                </div></td>
                              </tr>
                          </table></td>
                          <td><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="9" height="16"   class="formato_columnas_izq"></td>
                                <td width="250"class="formato_columnas" align="center">SUCURSALES QUE APLICA EAD </td>
                                <td width="9"class="formato_columnas_der"></td>
                              </tr>
                              <tr>
                                <td colspan="12"><div align="center">
                                    <select name="sucursalesead2" class="Tablas" size="7" style="width:265px" id="sucursalesead2">
                                    </select>
                                </div></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
          </table>
		</td>
	  </tr>
	</table>
</div>
</form>
</body>
</html>