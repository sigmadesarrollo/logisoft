<?	session_start();
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script>

	var u = document.all;
	var tablaservicios = new ClaseTabla();
	var tablaservicios2 = new ClaseTabla();
	
	var combo1 = "<select name='sltsucursales' id='sltsucursales' class='Tablas' style='width:210px;'>";
	var div_preciokg ='<table width="530" border="0" ><tr><td>Precio por KG</td></tr>  <tr><td><div id="div_preciokg" name="detalle" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
	var div_descripcion ='<table width="530" border="0"><tr><td>Precio por Caja/Paquete</td></tr>  <tr><td><div id="div_descripcion" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
	var var_prepagadas ='<table width="530" border="0"><tr><td><table border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="94">Pre-Pagadas</td><td width="195"><div class="etiqueta" style="width:50px">Limite KG:</div><input name="limitekg" type="text" class="text2" id="limitekg" style="width:100px; text-align:right" readonly="readonly" /></td><td width="66"><div class="etiqueta" style="width:30px">Costo:</div></td><td width="169"><input name="costoguia" type="text" class="text2" id="costoguia" style="width:100px; text-align:right" readonly="readonly"/></td></tr></table></td></tr></table>';
	var detallekgc = '<table width="530" border="0"><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="95">Pre-Pagadas</td><td width="224">Limite KG:<input name="limitekg" type="text" class="text2" id="limitekg" style="width:100px; text-align:right" readonly="readonly" /></td><td width="56">Costo:</td><td width="160"><input name="costoguia" type="text" class="text2" id="costoguia" style="width:100px; text-align:right" readonly="readonly"/></td></tr></table></td></tr><tr><td>Precio por KG</td></tr><tr><td><div id="detallekgc" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
	var detalledescripcionc = '<table width="530" border="0"><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="101">Pre-Pagadas</td><td width="229">Limite KG:<input name="limitekg" type="text" class="text2" style="width:100px; text-align:right" readonly="readonly"></td><td width="66">Costo:</td><td width="139"><input name="costoguia" type="text" class="text2" style="width:100px;text-align:right" readonly="readonly" /></td></tr></table></td></tr><tr><td>Precio por Caja/Paquete</td></tr><tr><td><div id="detalledescripcionc" name="detalle" style="width:535px; height:80px; overflow:auto" align="left"></div></td></tr></table>';
	var descuentocon = '<table width="530" border="0" cellspacing="0" cellpadding="0"><tr><td><table border="0" cellpadding="0" cellspacing="0" id="col_prepagadas"><tr><td width="102">Pre-Pagadas</td><td width="207">Limite KG:<input name="limitekg" type="text" class="text2" id="limitekg" style="width:100px;text-align:right" readonly="readonly" /></td><td width="59">Costo:</td><td width="158"><input name="costoguia" type="text" class="text2" id="costoguia" style="width:100px; text-align:right" readonly="readonly"/></td></tr></table></td></tr><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="170">Consignaci&oacute;n Descuento</td><td width="360"><input name="consignaciondes" type="text" class="text2" id="consignaciondes" style="width:100px;" readonly="readonly" /></td></tr></table></td></tr></table>';
	var descuento = '<table width="534" border="0" cellpadding="0" cellspacing="0"><tr> <td width="150">Descuento Sobre Flete:</td><td width="384"><input name="descuentoflete" type="text" class="text2" id="descuentoflete" style="width:100px;" readonly="readonly" /></td></tr></table>';
	
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
		obtenerCliente();
	}
	
	function obtenerCliente(){
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
			consultaTexto("mostrarCGridKg", "consultasHTML.php?accion=6&valor=1&idconvenio="+u.folioconvenio.value
			+"&cliente=<?=$_GET[cliente] ?>&val="+Math.random());
				}
				if(preciocaja==1){
					u.div1.innerHTML = div_descripcion;
			consultaTexto("mostrarCGridPeso", "consultasHTML.php?accion=6&valor=2&idconvenio="+u.folioconvenio.value
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
				consultaTexto("mostrarSGridKg", "consultasHTML.php?accion=6&valor=3&idconvenio="+u.folioconvenio.value
				+"&cliente=<?=$_GET[cliente] ?>&val="+Math.random());
				}
				
				if(consignacioncaja==1){
					u.div2.innerHTML = detalledescripcionc;
					if(prepagadas==1){
						v_prepagadas;
					}
				consultaTexto("mostrarSGridPeso", "consultasHTML.php?accion=6&valor=4&idconvenio="+u.folioconvenio.value
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
					//u.preciokgexcedente.value = datos.getElementsByTagName('preciokgexcedente').item(0).firstChild.data;		
				}else{
					u.col_prepagadas.style.display="none";
				}
			}
			consultaTexto("mostrarGrids","consultasHTML.php?accion=8&idconvenio="+u.folioconvenio.value
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
	
	function numcredvar(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString(); 
		if(flag) cad += '.'; 
		return cad;
	}
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/reseter.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table width="100%" cellspacing="0" cellpadding="0" id="convenio_d" class="datos-cliente-letra">
	<tr>
		<td>
			<table width="564" cellspacing="0" cellpadding="0" >
              <tr>
                <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<th width="4%">&nbsp;</th>
					<th width="18%"><div class="etiqueta" style="width:100px">Folio Convenio:</div></th>
					<th width="14%" ><input name="folioconvenio" type="text" class="text2" id="folioconvenio" readonly="readonly" style="width:70px" /></th>
					<th width="4%">&nbsp;</th>
					<th width="12%">Vigencia:</th>
					<th width="17%" ><input name="vigencia" type="text" class="text2" id="vigencia" readonly="readonly" style="width:80px" /></th>
					<th width="4%">&nbsp;</th>
					<th width="9%">Estado:</th>
					<th width="30%" ><input name="estado" type="text" class="text2" id="estado" style="width:120px" readonly="" /></th>
				  </tr>
				</table></td>
              </tr>
              <tr>
                <td colspan="4">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" id="normales">
					  <tr>
						<td style="color:#000000;font-family: tahoma; font-size: 12px; font-weight: bold;">&nbsp;Guia Normal</td>
					  </tr>
					  <tr>
                    	<td><table width="534" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2" id="div1"></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="td_servicio1"><div style="background:#282828; height:95px"><table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios" style="display:none">
                          </table></div></td>
                        </tr>
						<tr>
                          <td><div style="background:#282828; height:100px"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="9" height="16" class="formato_columnas_izq"></td>
                                <td width="250"class="formato_columnas" align="center">SERVICIOS RESTRINGIDOS POR EL CLIENTE </td>
                                <td width="9"class="formato_columnas_der"></td>
                              </tr>
                              <tr>
                                <td colspan="12"><div align="center">
                                    <select name="serviciosr1" size="7" class="Tablas" style="width:265px; font-family: tahoma;	font-size: 9px;	font-style: normal;	font-weight: bold;" id="serviciosr1">
                                    </select>
                                </div></td>
                              </tr>
                          </table></div></td>
                          <td><div style="background:#282828; height:100px"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="9" height="16"   class="formato_columnas_izq"></td>
                                <td width="250"class="formato_columnas" align="center">SUCURSALES QUE APLICA EAD </td>
                                <td width="9"class="formato_columnas_der"></td>
                              </tr>
                              <tr>
                                <td colspan="12"><div align="center">
                                    <select name="sucursalesead1" size="7" class="Tablas" style="width:265px;font-family: tahoma;	font-size: 9px;	font-style: normal;	font-weight: bold;" id="sucursalesead1">
                                    </select>
                                </div></td>
                              </tr>
                          </table></div></td>
                        </tr>
						</table>
						</td>
					  </tr>
					</table>
				</td>
              </tr>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><table width="100%" border="0" cellpadding="0" cellspacing="0" id="empresariales">
				  <tr>
					<td style="color:#000000;font-family: tahoma; font-size: 12px; font-weight: bold;">&nbsp;Guia Empresarial</td>
				  </tr>
				  <tr>
					<td><table width="534" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
					  <td colspan="2" id="div2"></td>
					</tr>
					<tr>
					  <td colspan="2" id="td_servicio2"><div style="background:#282828; height:95px"><table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios2" style="display:none">
					  </table></div></td>
					</tr>
					<tr>
					  <td><div style="background:#282828; height:100px"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
						  <tr>
							<td width="9" height="16" class="formato_columnas_izq"></td>
							<td width="250"class="formato_columnas" align="center">SERVICIOS RESTRINGIDOS POR EL CLIENTE </td>
							<td width="9"class="formato_columnas_der"></td>
						  </tr>
						  <tr>
							<td colspan="12"><div align="center">
								<select name="serviciosr2" size="7" class="Tablas" style="width:265px;font-family: tahoma;	font-size: 9px;	font-style: normal;	font-weight: bold;" id="serviciosr2">
								</select>
							</div></td>
						  </tr>
					  </table></div></td>
					  <td><div style="background:#282828; height:100px"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
						  <tr>
							<td width="9" height="16"   class="formato_columnas_izq"></td>
							<td width="250"class="formato_columnas" align="center">SUCURSALES QUE APLICA EAD </td>
							<td width="9"class="formato_columnas_der"></td>
						  </tr>
						  <tr>
							<td colspan="12"><div align="center">
								<select name="sucursalesead2" class="Tablas" size="7" style="width:265px;font-family: tahoma;	font-size: 9px;	font-style: normal;	font-weight: bold;" id="sucursalesead2">
								</select>
							</div></td>
						  </tr>
					  </table></div></td>
					</tr>
					</table>
					</td>
				  </tr>
				</table></td>
              </tr>
          </table>
		</td>
	  </tr>
	</table>

</body>
</html>
