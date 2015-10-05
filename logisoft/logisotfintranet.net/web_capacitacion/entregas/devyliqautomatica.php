<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
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
-->
</style>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ClaseMensajes.js" language="javascript"></script>
<script src="../javascript/ajax.js" language="javascript"></script>
<script>
	var u 	   = 	 document.all;
	var tabla1 = new ClaseTabla();	
	var mens = new ClaseMensajes();
	mens.iniciar("../javascript");
	
	tabla1.setAttributes({
		nombre:"tablalista",
		campos:[
			{nombre:"No_GUIA", medida:80, alineacion:"center", datos:"guia"},
			{nombre:"DESTINATARIO", medida:100, alineacion:"left", datos:"destinatario"},
			{nombre:"TIPO_FLETE", medida:50, alineacion:"center", datos:"tipoflete"},
			{nombre:"PAGO", medida:50, alineacion:"left", datos:"condicionpago"},
			{nombre:"EST", medida:40, alineacion:"center", datos:"estado"},
			{nombre:"MOTIVO", medida:70, alineacion:"left", datos:"motivo"},
			{nombre:"EFECTIVO", medida:70, alineacion:"right", tipo:'moneda', datos:"efectivo"},
			{nombre:"CHEQUE", medida:70, alineacion:"right", tipo:'moneda', datos:"importecheque"},
			{nombre:"NOTACREDITO", medida:70, alineacion:"right", tipo:'moneda', datos:"importenotacredito"},
			{nombre:"TOTAL", medida:80, alineacion:"right", tipo:'moneda', datos:"total"}
		],
		filasInicial:15,
		alto:200,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerGeneral();
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","devyliqautomatica_con.php?accion=1");
	}
	
	function mostrarGeneral(datos){
		var row = datos.split(",");
		u.folio.value = row[0];
		u.fecha.value = row[1];
		u.sucursal.value = row[3];
	}
	
	function obtenerUnidad(unidad){
		u.unidad.value = unidad;
		consultaTexto("mostrarUnidad","devyliqautomatica_con.php?accion=2&unidad="+unidad);
	}
	
	function mostrarUnidad(datos){
		if(datos.indexOf("no encontro")<0){
			try{
				var obj = eval(datos);
			}catch(e){
				mens.show("A",datos);
			}
			u.folioreparto.value = obj.principal.folio;
			u.conductor.value = obj.principal.conductor1;
			u.conductor2.value = obj.principal.conductor2;
			
			u.entregob.value = obj.principal.conductor1;
			u.entrego.value = obj.principal.idc1;
			
			tabla1.setJsonData(obj.detalleTabla);
			
			var entregadas = 0;
			var devueltas = 0;
			
			var efectivo = 0;
			var cheque = 0;
			var notacredito = 0;
			var total = 0;
			
			/*if(u["tablalista_CHECK"][i].checked==true){
				 guias += ((guias!="")?",":"")+objeto.guia;
			}*/
			
			for(var i=0; i<tabla1.getRecordCount(); i++){
				entregadas 	+= (u["tablalista_EST"][i].value=='E')?1:0;
				devueltas 	+= (u["tablalista_EST"][i].value=='E')?0:1;
				
				efectivo 	+= parseFloat(u["tablalista_EFECTIVO"][i].value.replace("$","").replace(/,/g,''));
				cheque 		+= parseFloat(u["tablalista_CHEQUE"][i].value.replace("$","").replace(/,/g,''));
				notacredito += parseFloat(u["tablalista_NOTACREDITO"][i].value.replace("$","").replace(/,/g,''));
				total 		+= parseFloat(u["tablalista_TOTAL"][i].value.replace("$","").replace(/,/g,''));
			}
			
			u.entregadas.value = entregadas;
			u.devueltas.value = devueltas;
			
			u.efectivo.value = "$ "+numcredvar(efectivo.toString());
			u.cheque.value = "$ "+numcredvar(cheque.toString());
			u.notacredito.value = "$ "+numcredvar(notacredito.toString());
			u.total.value = "$ "+numcredvar(total.toString());
			u.entregado.value = "$ "+numcredvar(total.toString());
		}else{
			mens.show("A","No se encontro información de la guia");
		}
	}
	
	function ValidarGuardar(){
		if(tabla1.getRecordCount()>0){
			consultaTexto("MostrarGuardar", "devyliqautomatica_con.php?accion=3&idreparto="+u.folioreparto.value
			+"&entregadas="+u.entregadas.value
			+"&devueltas="+u.devueltas.value+"&sucursal=<?=$_SESSION[IDSUCURSAL]?>&unidad="+u.unidad.value+
			"&entrego="+u.entrego.value+"&fecha="+u.fecha.value+"&total="+parseFloat(u.total.value.replace("$ ","").replace(/,/,""))+
			"&valram="+Math.random()+"&cantidadentregada="+u.entregado.value.replace("$ ","").replace(/,/,"")+
			"&diferencia="+u.diferencia.value.replace("$ ","").replace(/,/,""));
		}else{
			mens.show("A","El folio de reparto automático seleccionado no tiene ninguna guia","¡Atención!");
		}
	}
	
	function MostrarGuardar(datos){
		if(datos.indexOf("ok")>-1){
			mens.show("I","Los datos han sido guardados","¡Atención!");	
			document.getElementById('cerrar').style.display = 'none';
		}else{
			mens.show("A",""+datos,"¡Atención!");	
		}
	}
	
	function obtenerFolio(folio){
		consultaTexto("resObtener","devyliqautomatica_con.php?accion=4&folio="+folio);
	}
	
	function resObtener(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.folio.value			= obj.principal.foliodevyliq;
			u.fecha.value			= obj.principal.fecha;
			u.conductor.value		= obj.principal.conductor1;
			u.conductor2.value		= obj.principal.conductor2;
			u.unidad.value			= obj.principal.unidad;
			u.entregadas.value = obj.principal.entregadas;
			u.devueltas.value =  obj.principal.devueltas;
			u.folioreparto.value = obj.principal.idreparto;
			u.entregado.value =      convertirMoneda(obj.principal.cantidadentregada.toString());
			//u.diferencia.value =      obj.principal.diferencia;
			u.entrego.value =    obj.principal.entrego;
			u.entregob.value = obj.principal.empleado;
			u.cerrar.style.visibility="hidden";
			u.limpiar.style.visibility="visible";
			
			tabla1.setJsonData(obj.detalle);
			
			var efectivo = 0;
			var cheque = 0;
			var notacredito = 0;
			var total = 0;
			
			for(var i=0; i<tabla1.getRecordCount(); i++){				
				efectivo 	+= parseFloat(u["tablalista_EFECTIVO"][i].value.replace("$","").replace(/,/g,''));
				cheque 		+= parseFloat(u["tablalista_CHEQUE"][i].value.replace("$","").replace(/,/g,''));
				notacredito += parseFloat(u["tablalista_NOTACREDITO"][i].value.replace("$","").replace(/,/g,''));
				total 		+= parseFloat(u["tablalista_TOTAL"][i].value.replace("$","").replace(/,/g,''));
			}
			
			u.efectivo.value = "$ "+numcredvar(efectivo.toString());
			u.cheque.value = "$ "+numcredvar(cheque.toString());
			u.notacredito.value = "$ "+numcredvar(notacredito.toString());
			u.total.value = "$ "+numcredvar(total.toString());
				
		}
	}
	
	function limpiarTodo(){
			u.folio.value 		= "";
			u.folioreparto.value= "";
			u.fecha.value		= "";
			u.folio.value 		= "";
			u.unidad.value 		= "";
			u.conductor.value 	= "";
			u.conductor2.value 	= "";
			u.entregadas.value 	= "";
			u.devueltas.value 	= "";
			u.total.value 		= "";
			u.entrego.value 	= "";
			u.entregob.value 	= "";
			u.entregado.value 	= "";
			tabla1.clear();
			u.cerrar.style.visibility="visible";
			u.limpiar.style.visibility="visible";
			document.getElementById('cerrar').style.display = '';
			obtenerGeneral();
	}
	
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
</script>
</head>

<body>
<table width="619" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="619" class="FondoTabla Estilo4">DEVOLUCIÓN Y LIQUIDACI&Oacute;N DE AUTOMÁTICA</td>
  </tr>
  <tr>
    <td><table width="617" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="615"><table width="615" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="98">Folio Reparto EAD </td>
            <td width="75"><input name="folioreparto" type="text" class="Tablas" id="folioreparto"  style="width:70px;background:#FFFF99"  readonly=""/></td>
            <td width="30">&nbsp;</td>
            <td width="29">Fecha</td>
            <td width="80"><span class="Tablas">
              <input name="fecha" type="text" class="Tablas" id="fecha" style="width:80px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
            </span></td>
            <td width="42">Sucursal</td>
            <td width="100"><span class="Tablas">
              <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal?>" readonly=""/>
            </span></td>
            <td width="25">Folio</td>
            <td width="86"><input name="folio" type="text" class="Tablas" id="folio" style="width:80px" onkeypress="if(event.keyCode==13){obtenerFolio(this.value);}"/></td>
            <td width="36"><div class="ebtn_buscar" onclick="mens.popup('../buscadores_generales/buscarliquidacionmercancia.php?funcion=obtenerFolio&tipo=A',600,475,'ventana','BUSCAR LIQUIDACION MERCANCIA');"></div></td>
            <td width="14"><span class="Tablas">              </span></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td><table width="610" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="36">Unidad</td>
            <td width="158"><span class="Tablas">
              <input name="unidad" type="text" class="Tablas" id="unidad" style="width:150px;background:#FFFF99"  readonly=""/>
            </span></td>
            <td width="49"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="mens.popup('../buscadores_generales/buscarFolioDevLiq.php?funcion=obtenerUnidad',600,475,'ventana','BUSCAR LIQUIDACION MERCANCIA','');" /></td>
            <td width="53"><div align="right">Conductor</div></td>
            <td width="314"><span class="Tablas">
              <input name="conductor" type="text" class="Tablas" id="conductor" style="width:305px;background:#FFFF99"  readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="615" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="42">&nbsp;</td>
            <td width="96">&nbsp;</td>
            <td width="98">&nbsp;</td>
            <td width="54"><div align="right">Conductor</div></td>
            <td width="325"><span class="Tablas">
              <input name="conductor2" type="text" class="Tablas" id="conductor2" style="width:303px;background:#FFFF99"  readonly=""/>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" cellpadding="0" cellspacing="0" id="tablalista"></table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="615" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="57">&nbsp;</td>
            <td width="34">&nbsp;</td>
            <td width="116">&nbsp;</td>
            <td width="152"><span class="Tablas">
            </span></td>
            <td width="105">&nbsp;</td>
            <td width="33">
            
              <input name="pcredito" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right; display:none"  readonly=""/>
              <input name="pcredito2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right; display:none"  readonly=""/>
            </td>
            <td width="118">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="615" border="0" cellpadding="0" cellspacing="0">
         <tr>
            <td>Entregadas</td>
            <td><span class="Tablas">
              <input name="entregadas" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
            </span></td>
            <td>&nbsp;</td>
            <td colspan="2" rowspan="2">
            	<table width="540" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">Efectivo</td>
                    	<td align="right">Cheque</td>
                    	<td align="right">Notacredito</td>
                    	<td align="right">Total</td>
                    </tr>
                	<tr>
                    	<td align="right">
                         <input name="efectivo" type="text" class="Tablas" id="efectivo" style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
                        </td>
                    	<td align="right">
                         <input name="cheque" type="text" class="Tablas" id="cheque" style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
                        </td>
                    	<td align="right">
                         <input name="notacredito" type="text" class="Tablas" id="notacredito" style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
                        </td>
                    	<td align="right">
                         <input name="total" type="text" class="Tablas" id="total" style="width:100px;background:#FFFF99; text-align:right"  readonly=""/>
                        </td>
                    </tr>
                </table>
            </td>
            </tr>
          <tr>
            <td>Devueltas</td>
            <td><span class="Tablas">
              <input name="devueltas" type="text" class="Tablas" id="devueltas" style="width:30px;background:#FFFF99; text-align:right"  readonly=""/>
            </span></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td width="98" align="right">Entregado</td>
            </tr>
          <tr>
            <td width="56">&nbsp;</td>
            <td width="30">&nbsp;</td>
            <td width="3">
            
              <input name="ccredito" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right; display:none"  readonly=""/>
                <input name="ccredito2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right; display:none"  readonly=""/>
              <input name="ccredito" type="text" class="Tablas"  style="width:30px;background:#FFFF99; text-align:right; display:none"  readonly=""/>
              <input name="ccredito2" type="text" class="Tablas"  style="width:100px;background:#FFFF99; text-align:right; display:none"  readonly=""/>
            
            </td>
            <td width="442">&nbsp;</td>
            <td align="right">
            <input name="entregado" type="text" class="Tablas" id="entregado" onfocus="this.select()" style="text-align:right;width:100px;" value="0" onkeypress="solonumeros(event);"/>
            </td>
            </tr>
          
        </table>
          </td>
      </tr>
      
      
      <tr>
        <td><table width="615" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="42"><div align="left">Entrego              </div></td>
            <td width="96"><input name="entrego" type="text" id="entrego"  style="width:100px;background:#FFFF99"  readonly="" /></td>
            <td width="24">&nbsp;</td>
            <td width="453"><span class="Tablas">
              <input name="entregob" type="text" class="Tablas" id="entregob" style="width:360px;background:#FFFF99"  readonly=""/>
              <input name="h_importe" type="hidden" class="Tablas" id="h_importe"/>
            </span></td>
          </tr>
          <tr>
            <td colspan="4" align="center"><span class="Tablas">
              <input name="diferencia" type="hidden" class="Tablas" id="diferencia"/>
            </span></td>
          </tr>
          <tr>
            <td colspan="4" align="center"><table width="276">
                <tr>
                  <td width="8" >&nbsp;</td>
                  <td width="145"><div id="cerrar" class="ebtn_cerrarliquidacion" onclick="mens.show('C','¿Desea cerrar la liquidación del folio de reparto EAD: '+ u.folio.value +'?', '', '', 'ValidarGuardar()')"></div></td>
                  <td width="94"><div id="limpiar" class="ebtn_nuevo" onclick="limpiarTodo()"></div></td>
                  <td width="9">&nbsp;</td>
                </tr>
              </table>
              </td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>