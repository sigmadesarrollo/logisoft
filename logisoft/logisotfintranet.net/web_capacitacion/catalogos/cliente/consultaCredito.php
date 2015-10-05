
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script>
var combo1 = "<select name='sltsucursales' id='sltsucursales' class='Tablas' style='width:210px' onKeyPress='return tabular(event,this)'>";
var div_preciokg ='<table width="530" border="0"><tr><td>Precio por KG</td></tr>  <tr><td><div id="div_preciokg" name="detalle" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var div_descripcion ='<table width="530" border="0"><tr><td>Precio por Caja/Paquete</td></tr>  <tr><td><div id="div_descripcion" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var var_prepagadas = '<table width="530" border="0"><tr><td><table border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="78">Pre-Pagadas</td><td width="203" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" id="limitekg" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="40">Costo:</td><td width="205" class="Tablas"><input name="costoguia" type="text" class="Tablas" id="costoguia" style="width:100px;background:#FFFF99; text-align:right" value="<?=$Costo ?>" readonly="readonly"/></td></tr></table></td></tr></table>';
var detallekgc ='<table width="530" border="0"><tr><td><table border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="78">Pre-Pagadas</td><td width="203" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" id="limitekg" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="40">Costo:</td><td width="205" class="Tablas"><input name="costoguia" type="text" class="Tablas" id="costoguia" style="width:100px;background:#FFFF99; text-align:right" value="<?=$Costo ?>" readonly="readonly"/></td></tr></table></td></tr><tr><td>Precio por KG</td></tr>  <tr><td><div id="detallekgc" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var detalledescripcionc='<table width="530" border="0"><tr><td><table border="0" id="col_prepagadas" cellpadding="0" cellspacing="0"><tr><td width="78">Pre-Pagadas</td><td width="203" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly"></td><td width="40">Costo:</td><td width="205" class="Tablas"><input name="costoguia" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right" readonly="readonly" value="<?=$Costo ?>"/></td></tr>    </table></td></tr><tr><td>Precio por Caja/Paquete</td></tr>  <tr><td><div id="detalledescripcionc" name="detalle" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var descuentocon='<table width="530" border="0" cellspacing="0" cellpadding="0"><tr><td><table border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="78">Pre-Pagadas</td><td width="203" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" id="limitekg" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="40">Costo:</td><td width="205" class="Tablas"><input name="costoguia" type="text" class="Tablas" id="costoguia" style="width:100px;background:#FFFF99; text-align:right" value="<?=$Costo ?>" readonly="readonly"/></td></tr></table></td></tr> <tr><td><table width="257" border="0" cellpadding="0" cellspacing="0"><tr><td>Consignaci&oacute;n Descuento</td><td width="113" class="Tablas"><input name="consignaciondes" type="text" class="Tablas" id="consignaciondes" style="width:100px;background:#FFFF99" readonly="readonly" value="<?=$consignaciondes ?>"/></td></tr></table></td></tr></table>';
var descuento = '<table width="534" border="0" cellpadding="0" cellspacing="0"><tr> <td width="150">Descuento Sobre Flete:</td><td width="384"><input name="descuentoflete" type="text" class="Tablas" id="descuentoflete" style="width:100px;background:#FFFF99" readonly="readonly" /></td></tr></table>';	var u = document.all;
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
	});	tablaservicios2.setAttributes({
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
		u.codigo.focus();
		if("<?=$_GET[accion] ?>" == "1"){
			u.convenio_d.style.display = "none";
		}else if("<?=$_GET[accion] ?>" == "2"){
			u.credito.style.display = "none";
			tablaservicios.create();
			tablaservicios2.create();
			u.tabladeservicios.style.display = "none";
			u.tabladeservicios2.style.display= "none";
			u.convenio_d.style.display = "none";
		}
	}
	function validarCliente(e,obj){
		tecla = (u) ? e.keyCode : e.which;
    	if(tecla == 8 && document.getElementById(obj).value==""){
			u.cliente.value = "";
			if("<?=$_GET[accion] ?>" == "1"){
				limpiarCredito();
			}else if("<?=$_GET[accion] ?>" == "2"){
				u.convenio_d.style.display = "none";
			}
		}
	}
	function obtenerCliente(cliente){
		u.codigo.value = cliente;
		consultaTexto("mostrarCliente","consultaCredito_con.php?accion=1&tipo=<?=$_GET[accion] ?>&cliente="+cliente);
	}
	function mostrarCliente(datos){
		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){
			var obj = eval(convertirValoresJson(datos));
			u.cliente.value = obj[0].cliente;
			if("<?=$_GET[accion] ?>" == "1"){
				if(obj[0].foliocredito=="" || obj[0].foliocredito=="0"){
					alerta('El cliente no cuenta con credito','¡Atención!','codigo');
					return false;
				}
				u.foliocredito.value	= obj[0].foliocredito;
				u.activado.checked		= ((obj[0].activado=="SI")?true:false);
				u.clasificacion.value	= obj[0].clasificacioncliente;
				u.saldo.value			= obj[0].saldo;
				u.disponible.value		= ((obj[0].disponible<"0")?0:obj[0].disponible);
				u.ventames.value		= obj[0].ventames;
				u.limitecredito.value	= obj[0].limitecredito;
				u.diacredito.value		= obj[0].diascredito;
				u.diapago.value			= obj[0].diapago;
				u.diarevision.value		= obj[0].diarevision;
				
				if(u.foliocredito.value!="" && u.foliocredito.value!="0"){
				consulta("mostrarSucursales","consultasClientes.php?accion=4&cliente="+
				u.codigo.value+"&credito="+u.foliocredito.value);
				}			}else if("<?=$_GET[accion] ?>" == "2"){
				consulta("mostrarConvenio","consultasClientes.php?accion=7&cliente="+u.codigo.value);
			}			
		}else{
			alerta('El numero de Cliente no existe','¡Atención!','codigo');
			u.cliente.value = "";
			u.convenio_d.style.display = "none";
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
	}
	function mostrarConvenio(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
		limpiarConvenios();
		u.convenio_d.style.display = "";
		u.folioconvenio.value = datos.getElementsByTagName('folio').item(0).firstChild.data;		
		var precioporkg = datos.getElementsByTagName('precioporkg').item(0).firstChild.data;
		var preciocaja = datos.getElementsByTagName('precioporcaja').item(0).firstChild.data;	
		var descuentoflete = datos.getElementsByTagName('descuentoflete').item(0).firstChild.data;
		var consignacionkg = datos.getElementsByTagName('consignacionkg').item(0).firstChild.data;
		var consignacioncaja = datos.getElementsByTagName('consignacioncaja').item(0).firstChild.data;
		var consigdescuento = datos.getElementsByTagName('consignaciondescuento').item(0).firstChild.data;			
		var prepagadas = datos.getElementsByTagName('prepagadas').item(0).firstChild.data;	if(precioporkg==0 && preciocaja==0 && descuentoflete==0){
		u.normales.style.display="none";
	}else{
		u.normales.style.display="";
		if(precioporkg==1){u.div1.innerHTML = div_preciokg;
	consultaTexto("mostrarCGridKg", "consultasHTML.php?accion=6&valor=1&idconvenio="+u.folioconvenio.value+"&cliente="+u.codigo.value);
		}
		if(preciocaja==1) {u.div1.innerHTML = div_descripcion;
	consultaTexto("mostrarCGridPeso", "consultasHTML.php?accion=6&valor=2&idconvenio="+u.folioconvenio.value+"&cliente="+u.codigo.value);
		}
		if(descuentoflete==1){u.div1.innerHTML = descuento;
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
		if(consignacionkg==1){u.div2.innerHTML = detallekgc;
		v_prepagadas;
	consultaTexto("mostrarSGridKg", "consultasHTML.php?accion=6&valor=3&idconvenio="+u.folioconvenio.value+"&cliente="+u.codigo.value);
		}
 		if(consignacioncaja==1){u.div2.innerHTML = detalledescripcionc;
		v_prepagadas;
	consultaTexto("mostrarSGridPeso", "consultasHTML.php?accion=6&valor=4&idconvenio="+u.folioconvenio.value+"&cliente="+u.codigo.value);
		}
 		if(consigdescuento==1){u.div2.innerHTML = descuentocon;
		v_prepagadas;
	u.consignaciondes.value = datos.getElementsByTagName('cantidaddescuentoconsignacion').item(0).firstChild.data;
		
		}
		if(prepagadas==1){
		u.costoguia.value = "";
		u.limitekg.value  = "";
		u.costoguia.value = datos.getElementsByTagName('costo').item(0).firstChild.data;
		u.limitekg.value = datos.getElementsByTagName('limitekg').item(0).firstChild.data;		
		}			
	}	
	consultaTexto("mostrarGrids","consultasHTML.php?accion=8&idconvenio="+u.folioconvenio.value+"&cliente="+u.codigo.value);
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
		u.clasificacion.value	= "";
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
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head><body>
<form id="form1" name="form1" method="post" action="">
  <table width="565" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla"><?=$_GET[titulo]?></td>
    </tr>
    <tr>
      <td><table width="560" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="65">Cliente:</td>
          <td width="120"><label>
            <input name="codigo" type="text" id="codigo" class="Tablas" style="width:80px" maxlength="10" onkeypress="if(event.keyCode==13){obtenerCliente(this.value)}" onkeyup="validarCliente(event,this.name)" />
            <span class="Tablas"><img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Cliente" onclick="abrirVentanaFija('../../buscadores_generales/buscarClienteGen.php?funcion=obtenerCliente', 600, 450, 'ventana', 'Busqueda')"/></span></label></td>
          <td width="375"><label>
            <input name="cliente" type="text" class="Tablas" id="cliente" style="width:300px" readonly="" />
          </label></td>
        </tr>
        <tr>
          <td colspan="3"><table width="559" border="0" id="credito" cellpadding="1" cellspacing="0">
              <tr>
                <td class="Tablas"><input name="activado" type="checkbox" id="activado" style="width:13px" value="SI" <? if($activado=="SI"){ echo 'checked';} ?> />
                  Activado</td>
                <td width="134" class="Tablas">&nbsp;</td>
                <td width="10" class="Tablas">&nbsp;</td>
                <td class="Tablas">&nbsp;</td>
                <td width="222" class="Tablas">&nbsp;</td>
              </tr>
              <tr>
                <td class="Tablas">Folio Cr&eacute;dito:</td>
                <td colspan="2" class="Tablas"><input name="foliocredito" type="text" class="Tablas" id="foliocredito"  readonly="readonly" /></td>
                <td class="Tablas">Clasificaci&oacute;n:</td>
                <td class="Tablas"><input class="Tablas" name="clasificacion" type="text" id="clasificacion" readonly="readonly" /></td>
              </tr>
              <tr>
                <td width="89" class="Tablas">Saldo:</td>
                <td colspan="2" class="Tablas"><input class="Tablas" name="saldo" type="text" id="saldo" readonly="readonly" /></td>
                <td width="94" class="Tablas">Disponible:</td>
                <td class="Tablas"><input class="Tablas" name="disponible" type="text" id="disponible" readonly="readonly" /></td>
              </tr>
              <tr>
                <td class="Tablas">Limite Credito: </td>
                <td colspan="2" class="Tablas"><input class="Tablas" name="limitecredito" type="text" id="limitecredito" readonly="readonly" /></td>
                <td class="Tablas">Ventas Mes: </td>
                <td class="Tablas"><input class="Tablas" name="ventames" type="text" id="ventames"  readonly="readonly" /></td>
              </tr>
              <tr>
                <td class="Tablas">D&iacute;as Cr&eacute;dito:</td>
                <td colspan="2" class="Tablas" ><input class="Tablas" name="diacredito" type="text" id="diacredito" readonly="readonly" /></td>
                <td class="Tablas" >D&iacute;as Revisi&oacute;n:</td>
                <td class="Tablas" ><input class="Tablas" name="diarevision" type="text" id="diarevision" readonly="readonly" /></td>
              </tr>
              <tr>
                <td class="Tablas">D&iacute;as Pago:</td>
                <td class="Tablas" ><input class="Tablas" name="diapago" type="text" id="diapago" readonly="readonly" /></td>
                <td class="Tablas">&nbsp;</td>
                <td class="Tablas" >Sucursales Cred:                  </td>
                <td class="Tablas" id="celsuc"><select class="Tablas" name="sltsucursales" id="sltsucursales" style="width:210px; font-size:9px">
                  <option selected="selected" style="width:205px">SUC. EN LAS QUE APLICA CREDITO</option>
                </select></td>
              </tr>
          </table></td>
        </tr>
		<tr>
			<td colspan="3"><table width="564" border="0" cellspacing="0" cellpadding="0" id="convenio_d">
              <tr>
                <td width="92">Folio Convenio: </td>
                <td width="472"><span class="Tablas">
                  <input name="folioconvenio" type="text" class="Tablas" id="folioconvenio" readonly="readonly" />
                </span></td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0" id="normales">
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
                                    <select name="serviciosr1" size="7" class="Tablas" style="width:265px" ondblclick="borrarServicio(this, 'SRCONSIGNACION')" id="serviciosr1">
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
                                    <select name="sucursalesead1" size="7" class="Tablas" style="width:265px" ondblclick="borrarServicio(this, 'SUCONSIGNACION2')" id="sucursalesead1">
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
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0" id="empresariales">
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
                                    <select name="serviciosr2" size="7" class="Tablas" style="width:265px" ondblclick="borrarServicio(this, 'SRCONSIGNACION')" id="serviciosr2">
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
                                    <select name="sucursalesead2" class="Tablas" size="7" style="width:265px" ondblclick="borrarServicio(this, 'SUCONSIGNACION2')" id="sucursalesead2">
                                    </select>
                                </div></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
		</tr>
        <tr>
          <td colspan="3">
		  
		  </td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
