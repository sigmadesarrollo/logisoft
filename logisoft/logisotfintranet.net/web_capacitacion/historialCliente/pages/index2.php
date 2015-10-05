<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/ClaseTabsDivs.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var mens 	= new ClaseMensajes();
	var u 		= document.all;
	var tabs 	= new ClaseTabs();
	var tabla1	= new ClaseTabla();
	mens.iniciar("../../javascript");
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[						
			{nombre:"MES", medida:60, alineacion:"left", datos:"mes"},
			{nombre:"VENTAS", medida:90, tipo:"moneda", alineacion:"right", datos:"ventas"},
			{nombre:"COMPROMISO M.", medida:90, tipo:"moneda", alineacion:"right", datos:"consumomensual"},
			{nombre:"PORCENTAJE", medida:80, alineacion:"center", datos:"porc"},
			{nombre:"ENTREGAS", medida:90, alineacion:"center", datos:"entregas"},
			{nombre:"VTA. RECOLECCION", medida:100, tipo:"moneda", alineacion:"right", datos:"recoleccion"},
			{nombre:"SALDO", medida:90, tipo:"moneda", alineacion:"right", datos:"saldo"},
			{nombre:"CONSUMO", medida:90, tipo:"moneda", alineacion:"right", datos:"consumo"}
		],
		filasInicial:12,
		alto:190,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		tabs.iniciar({
			nombre:"tab", largo:680, alto:280, ajustex:11,
			ajustey:12, imagenes:"../../img", titulo:"Información Cliente"
		});
		u.btnMovIzq.style.visibility = "hidden";
		u.btnMovDer.style.visibility = "hidden";
		tabs.agregarTabs('Estado de Movimientos',1,null);
		tabs.seleccionar(0);
	}
	
	function obtenerCliente(id){
		u.cliente.value = id;
		consultaTexto("mostrarCliente","historialCliente_con.php?accion=1&cliente="+id);
	}
	
	function mostrarCliente(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			u.nombre.value				= obj.cliente.cliente;
			u.telefono.value	 		= obj.cliente.telefono;
			u.celular.value		 		= obj.cliente.celular;
			u.email.value		 		= obj.cliente.email;
			u.tipocliente.value 		= obj.cliente.tipocliente;
			u.clasificacion.value		= obj.cliente.clasificacioncliente;
			u.credito.value 			= "$ "+obj.cliente.limitecredito;
			u.tpropuesta.innerHTML		= "("+obj.alertas.tpropuesta+")";
			u.tcat.innerHTML			= "("+obj.alertas.tcat+")";
			u.tsolicitud.innerHTML		= "("+obj.alertas.tsolicitud+")";
			u.tespeciales.innerHTML		= "("+obj.alertas.tespeciales+")";
			u.consumo.value				= "$ "+obj.otros.comprado+" VS $ "+obj.otros.pagado;
			u.compromiso.value			= "$ "+obj.otros.compromisomensual+" VS $ "+obj.otros.consumido;
			tabla1.setJsonData(obj.detalle);
		}else{
			mens.show("A","El numero de Cliente no existe","¡Atención!");
			return false;
		}
	}
	
	function obtenerDetalle(fecha){
		consultaTexto("mostrarDetalle","historialCliente_con.php?accion=1&cliente="+u.cliente.value
		+"&fecha="+fecha);
	}
	
	function mostrarDetalle(datos){
		alert(datos);
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
		}else{
			mens.show("A","No se encontraron datos en el año seleccionado","¡Atención!");
			return false;
		}
	}
	
	function mostrarFrame(tipo){
		switch(tipo){		
			case "propuesta":			
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Propuestas de Convenio";
						tabs.seleccionar(1);
						u.pagina0.src = "propuestaConvenio.php?cliente="+u.cliente.value;
					}else{
						return false;
					}	
			break;
			
			case "vencidos":			
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Convenio por Vencer";
						tabs.seleccionar(1);
						u.pagina0.src = "index.php";
					}else{
						return false;
					}	
			break;
			
			case "convenio":			
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Convenio";
						tabs.seleccionar(1);
						u.pagina0.src = "informacionConvenioCredito.php?tab=1&cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "credito":
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Solicitud Credito";
						tabs.seleccionar(1);
						u.pagina0.src = "informacionConvenioCredito.php?tab=0&cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "cat":
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "CAT";
						tabs.seleccionar(1);
						u.pagina0.src = "cat.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "estadocuenta":
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Estado de Cuenta";
						tabs.seleccionar(1);
						u.pagina0.src = "estadocuenta.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "cobranza":
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Estado de Cobranza";
						tabs.seleccionar(1);
						u.pagina0.src = "estadocobranza.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "solicitud":
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Solicitud Guias Empresariales";
						tabs.seleccionar(1);
						u.pagina0.src = "solicitudguias.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "historial":
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Historial de Movimientos";
						tabs.seleccionar(1);
						u.pagina0.src = "index.php";
					}else{
						return false;
					}
			break;
			
			case "envios":					
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Indicador de produc. de envios";
						tabs.seleccionar(1);
						u.pagina0.src = "index.php";
					}else{
						return false;
					}
			break;
			
			case "recolecciones":
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Indicador de produc. de Recolecciones";
						tabs.seleccionar(1);
						u.pagina0.src = "index.php";
					}else{
						return false;
					}
			break;
			
			case "compromisos":					
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Compromisos de pagos";
						tabs.seleccionar(1);
						u.pagina0.src = "compromisospagos.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "especiales":
					if(u.cliente.value!=""){			
						document.getElementById("tab_contenedor_id1").innerHTML = "Entregas Especiales";
						tabs.seleccionar(1);
						u.pagina0.src = "entregasespeciales.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
		}
	}
	
	
</script>

<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="" onsubmit="return false">
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id0">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" valign="top" ><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#016193">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><table width="99%" border="0" cellspacing="0" cellpadding="0" align="left">
                  <tr>
                    <td width="40%" valign="top">Tel.:</td>
                    <td width="60%" id="tdTelefono"><label>
                      <input name="telefono" type="text" id="telefono" class="Tablas" style="width:200px" readonly=""/>
                    </label></td>
                  </tr>
                 <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td valign="top">Cel.:</td>
                    <td id="tdCelular"><input name="celular" type="text" id="celular" class="Tablas" style="width:200px" readonly=""/></td>
                  </tr>
                  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td valign="top">Email:</td>
                    <td id="tdEmail"><input name="email" type="text" id="email" class="Tablas" style="width:200px" readonly=""/></td>
                  </tr>
                  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td valign="top">T. Cliente: </td>
                    <td id="tdTipo"><input name="tipocliente" type="text" id="tipocliente" class="Tablas" style="width:200px" readonly=""/></td>
                  </tr>
                  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td>Clasificacion:</td>
                    <td id="tdClasificacion"><input name="clasificacion" type="text" id="clasificacion" class="Tablas" style="width:200px" readonly=""/></td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td>L. Cr&eacute;dito: </td>
                    <td id="tdCredito"><input name="credito" type="text" id="credito" class="Tablas" style="width:200px" readonly=""/></td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer">Consumo vs Pagos</span></td>
					<td id="tdCopa"><input name="consumo" type="text" id="consumo" class="Tablas" style="width:200px" readonly=""/></td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer">Comp. mensual vs compras mensuales</span></td>
					<td id="tdCoco"><input name="compromiso" type="text" id="compromiso" class="Tablas" style="width:200px" readonly=""/></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="right"><a href="#">Ver mas...</a></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
              </table></td>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="85%"><span style="cursor:pointer" onclick="mostrarFrame('propuesta')">Propuestas de convenios</span></td>
                    <td width="15%" id="tpropuesta">(0)</td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td ><span style="cursor:pointer" onclick="mostrarFrame('convenio')">Convenio</span></td>
                    <td>&nbsp;</td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td ><span style="cursor:pointer" onclick="mostrarFrame('credito')">Solicitud de Cr&eacute;dito</span></td>
                    <td>&nbsp;</td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td ><span style="cursor:pointer" onclick="mostrarFrame('cat')">CAT</span> </td>
                    <td id="tcat">(0)</td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td ><span style="cursor:pointer" onclick="mostrarFrame('estadocuenta')">Estado de Cuenta</span></td>
                    <td>&nbsp;</td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer" onclick="mostrarFrame('cobranza')">Estado de Cobranza</span></td>
                    <td>&nbsp;</td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                    <td ></td>
                  </tr>
                  <tr>
                    <td ><span style="cursor:pointer" onclick="mostrarFrame('solicitud')">Solicitud de gu&iacute;as empresariales</span></td>
                    <td id="tsolicitud">(0)</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td ><span style="cursor:pointer" onclick="mostrarFrame('historial')">Historial de Movimientos</span></td>
                    <td>&nbsp;</td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                  </tr>
                  <tr>
                    <td colspan="2"><span style="cursor:pointer" onclick="mostrarFrame('envios')">Indicador de produc. de envios</span></td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                  </tr>
                  <tr>
                    <td colspan="2" ><span style="cursor:pointer" onclick="mostrarFrame('recolecciones')">Indicador de produc. de recolecciones</span></td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                  </tr>
                  <tr>
                    <td colspan="2" ><span style="cursor:pointer" onclick="mostrarFrame('compromisos')">Compromisos de pagos</span></td>
                  </tr>
				  <tr>
                    <td height="5px"></td>
                  </tr>
                  <tr>
                    <td><span style="cursor:pointer" onclick="mostrarFrame('especiales')">Entregas especiales</span></td>
                    <td id="tespeciales">(0)</td>
                  </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
  	<td width="50%">&nbsp;</td>
  </tr>
  <tr>
  	<td>Ventas por Año:&nbsp;&nbsp;<select name="slMeses" id="slMeses" class="Tablas" onchange="obtenerDetalle(this.value)">
		<option value="">Seleccionar Año</option>
		<?	$s = "SELECT MIN(YEAR(fecha)) AS primera, YEAR(CURDATE())AS actual FROM generacionconvenio";
						$ss = mysql_query($s,$l) or die($s);
						$fs = mysql_fetch_object($ss);
					
						for($i=$fs->primera;$i<=$fs->actual;$i++){
							?>
      <option value="<?=$i ?>"<? if($fecha==$i){ echo 'selected';} ?>>
      <?=$i ?>
      </option>
      <?	} ?>
	</select></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><div style="height:200px; width:670px; overflow:auto">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">
		</table>
	</div></td>
  </tr>
  <tr>
    <td colspan="2"><div id="paginado" align="center" style="visibility:hidden">     
      <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> 
			  <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> 
			  <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('adelante')" /> 
			  <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion('ultimo')" />
      	  <input type="hidden" name="pag_total" />
          <input type="hidden" name="pag_contador" value="0" />
          <input type="hidden" name="pag_adelante" value="" />
          <input type="hidden" name="pag_atras" value="" />
    </div></td>
  </tr>
</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; width: 621px; visibility:visible;" id="tab_tab_id1">
	<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#016193">
		<tr>
			<td>			
			<iframe name="pagina0" id="pagina0" scrolling="auto" align="top" width="100%" height="475" frameborder="1" ></iframe>
			</td>
		</tr>
	</table>
</div>
  <table width="620" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">HISTORIAL DE CLIENTES </td>
    </tr>
    <tr>
      <td><table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="9%" class="Tablas">Cliente:</td>
                    <td width="11%"><label>
                      <input name="cliente" type="text" id="cliente" class="Tablas" style="width:60px" onkeypress="if(event.keyCode==13){obtenerCliente(this.value)}" />
                    </label></td>
                    <td width="6%"><div class="ebtn_buscar" onclick="abrirVentanaFija('../../buscadores_generales/buscarClienteGen2.php?funcion=obtenerCliente', 625, 450, 'ventana', 'Busqueda')" ></div></td>
                    <td width="74%"><label>Nombre:
                      <input name="nombre" type="text" id="nombre" style="width:350px" readonly="" class="Tablas" />
                    </label></td>
                  </tr>
                </table></td>
                </tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
              <tr>
                <td height="500px" valign="top"><table id="tab" cellpadding="0" cellspacing="0" border="0">
        			</table>
				</td>
                </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
