<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script>
var combo1 = "<select name='sltsucursales' id='sltsucursales' class='Tablas' style='width:210px' onKeyPress='return tabular(event,this)'>";
var div_preciokg ='<table width="530" border="0"><tr><td>Precio por KG</td></tr>  <tr><td><div id="div_preciokg" name="detalle" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var div_descripcion ='<table width="530" border="0"><tr><td>Precio por Caja/Paquete</td></tr>  <tr><td><div id="div_descripcion" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
var var_prepagadas = '<table width="530" border="0"><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="67">Pre-Pagadas</td><td width="150" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="44">Costo:</td><td width="113" class="Tablas"><input name="costoguia" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" readonly="readonly" value="<?=$Costo ?>"/></td><td width="73" class="Tablas">Excedente:</td><td width="88" class="Tablas"><input name="preciokgexcedente" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return tiposMoneda(event,this.value)" readonly="readonly" value="<?=$Costo ?>"/></td></tr></table>  </td></tr></table>';

var detallekgc ='<table width="530" border="0"><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr>    <td width="67">Pre-Pagadas</td><td width="150" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="44">Costo:</td><td width="113" class="Tablas"><input name="costoguia" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" readonly="readonly" value="<?=$Costo ?>"/></td><td width="73" class="Tablas">Excedente:</td><td width="88" class="Tablas"><input name="preciokgexcedente" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return tiposMoneda(event,this.value)" readonly="readonly" value="<?=$Costo ?>"/></td></tr></table></td></tr><tr><td>Precio por KG</td></tr>  <tr><td><div id="detallekgc" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';

var detalledescripcionc='<table width="530" border="0"><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="67">Pre-Pagadas</td><td width="150" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly"></td><td width="44">Costo:</td><td width="113" class="Tablas"><input name="costoguia" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" readonly="readonly" value="<?=$Costo ?>"/></td><td width="73" class="Tablas">Excedente:</td><td width="88" class="Tablas"><input name="preciokgexcedente" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return tiposMoneda(event,this.value)" readonly="readonly" value="<?=$Costo ?>"/></td></tr></table></td></tr><tr><td>Precio por Caja/Paquete</td></tr>  <tr><td><div id="detalledescripcionc" name="detalle" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';

var descuentocon='  <table width="530" border="0" cellspacing="0" cellpadding="0"><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="67">Pre-Pagadas</td><td width="150" class="Tablas">Limite KG:<input name="limitekg" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return solonumeros(event)" value="<?=$limitekg ?>" readonly="readonly" /></td><td width="44">Costo:</td><td width="113" class="Tablas"><input name="costoguia" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" readonly="readonly" value="<?=$Costo ?>"/></td><td width="73" class="Tablas">Excedente:</td><td width="88" class="Tablas"><input name="preciokgexcedente" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return tiposMoneda(event,this.value)" readonly="readonly" value="<?=$Costo ?>"/></td></tr></table></td></tr> <tr><td><table width="257" border="0" cellpadding="0" cellspacing="0"><tr><td>Consignaci&oacute;n Descuento</td><td width="113" class="Tablas"><input name="consignaciondes" type="text" class="Tablas" id="consignaciondes" style="width:100px;background:#FFFF99" readonly="readonly" value="<?=$consignaciondes ?>"/></td></tr></table></td></tr></table>';
var descuento = '<table width="534" border="0" cellpadding="0" cellspacing="0"><tr> <td width="150">Descuento Sobre Flete:</td><td width="384"><input name="descuentoflete" type="text" class="Tablas" id="descuentoflete" style="width:100px;background:#FFFF99" readonly="readonly" /></td></tr></table>';
	
	var tablaservicios = new ClaseTabla();
	var tablaservicios2 = new ClaseTabla();
	var u = document.all;
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
		tablaservicios.create();
		tablaservicios2.create();
		u.tabladeservicios.style.display = "none";
		u.tabladeservicios2.style.display= "none";
		obtenerCliente('<?=$_GET[cliente] ?>');
	}
	
	function obtenerCliente(cliente){
		if(cliente!=""){
			consulta("mostrarConvenio","../../catalogos/cliente/consultasClientes.php?accion=8&cliente="+cliente);
		}
	}
	function mostrarConvenio(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			limpiarConvenios();	
				u.convenio_d.style.display = "";
				u.folioconvenio.value = datos.getElementsByTagName('folio').item(0).firstChild.data;
				u.vigencia.value = datos.getElementsByTagName('vigencia').item(0).firstChild.data;	
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
			consultaTexto("mostrarCGridKg", "../../catalogos/cliente/consultasHTML.php?accion=6&valor=1&idconvenio="+u.folioconvenio.value+"&cliente=<?=$_GET[cliente] ?>");
				}
				if(preciocaja==1){
					u.div1.innerHTML = div_descripcion;
			consultaTexto("mostrarCGridPeso", "../../catalogos/cliente/consultasHTML.php?accion=6&valor=2&idconvenio="+u.folioconvenio.value+"&cliente=<?=$_GET[cliente] ?>");
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
				consultaTexto("mostrarSGridKg", "../../catalogos/cliente/consultasHTML.php?accion=6&valor=3&idconvenio="+u.folioconvenio.value+"&cliente=<?=$_GET[cliente] ?>");
				}
			
				if(consignacioncaja==1){
					u.div2.innerHTML = detalledescripcionc;
					if(prepagadas==1){
						v_prepagadas;
					}
				consultaTexto("mostrarSGridPeso", "../../catalogos/cliente/consultasHTML.php?accion=6&valor=4&idconvenio="+u.folioconvenio.value+"&cliente=<?=$_GET[cliente] ?>");
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
			consultaTexto("mostrarGrids","../../catalogos/cliente/consultasHTML.php?accion=8&idconvenio="+u.folioconvenio.value+"&cliente=<?=$_GET[cliente] ?>");
		}else{
			u.empresariales.style.display="none";
			u.normales.style.display="none";
		}
	}
	function mostrarGrids(datos){	
		var objeto = eval(datos);
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
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="565" border="1" bordercolor="#016193" cellspacing="0" cellpadding="0" id="convenio_d">
	<tr>		
		<td>
<table width="564" cellspacing="0" cellpadding="0" >
              <tr>
                <td>&nbsp;</td>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td width="92">Folio Convenio: </td>
                <td width="236"><span class="Tablas">
                  <input name="folioconvenio" type="text" class="Tablas" id="folioconvenio" readonly="readonly" />
                </span></td>
                <td width="236">Vigencia:</td>
                <td width="472"><span class="Tablas">
                  <input name="vigencia" type="text" class="Tablas" id="vigencia" readonly="readonly" />
                </span></td>
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
</body>
</html>
