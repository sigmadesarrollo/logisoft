<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/reseter.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="../../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
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
	var v_pestanas = Array(true,false);
	var mens 	= new ClaseMensajes();
	var u 		= document.all;
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
			{nombre:"SALDO", medida:4, tipo:"oculto", alineacion:"right", datos:"saldo"},
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
	}
	
	function seleccionarTab(index){
			var npagina = "div";
			var ntab	= "label";
			//var dlab	= "textoPestana";
			var pestanas= 2;
			
			if(v_pestanas[index]==false){			
				v_pestanas[index] = true;
				document.all[npagina+index].style.visibility = "visible";
			}
			
			for(var i=0; i<pestanas; i++){
				if(index!=i){
					document.all[npagina+i].style.display='none';
					document.all[ntab+i].className='';
				}
			}
			
			document.all[npagina+index].style.display='';
			document.all[ntab+index].className='active';
			
		}
	
	function obtenerCliente(id){
		u.cliente.value = id;
		consultaTexto("mostrarCliente","historialCliente_con.php?accion=1&cliente="+id+"&val="+Math.random());
	}
	
	function mostrarCliente(datos){
		if(datos.indexOf("no encontro")<0){
			try{
				var obj = eval(datos);
			}catch(e){
				mens.show("A",datos);
				return false;
			}
			u.nombre.value				= obj.cliente.cliente;
			u.telefono.innerHTML	 	= obj.cliente.telefono;
			u.celular.innerHTML		 	= obj.cliente.celular;
			u.email.innerHTML		 	= obj.cliente.email;
			u.tipocliente.innerHTML 	= obj.cliente.tipocliente;
			u.legal.innerHTML			= obj.cliente.legal;
			u.credito.innerHTML 		= "$ "+obj.cliente.limitecredito;
			u.tpropuesta.innerHTML		= "("+obj.alertas.tpropuesta+")";
			u.tcat.innerHTML			= "("+obj.alertas.tcat+")";
			u.tsolicitud.innerHTML		= "("+obj.alertas.tsolicitud+")";
			u.tespeciales.innerHTML		= "("+obj.alertas.tespeciales+")";
			u.consumo.innerHTML			= "$ "+obj.otros.comprado;
			u.pagado.innerHTML			= "$ "+obj.otros.pagado;
			u.compromiso.innerHTML		= "$ "+obj.otros.compromisomensual;
			u.consumido.innerHTML		= "$ "+obj.otros.consumido;
			seleccionarTab(0);
			u.pagina0.src = "";
			document.getElementById('label1').innerHTML = "";
			tabla1.setJsonData(obj.detalle);
		}else{
			mens.show("A","El numero de Cliente no existe","¡Atención!");
			return false;
		}
	}
	
	function obtenerDetalle(fecha){
		consultaTexto("mostrarDetalle","historialCliente_con.php?accion=1&cliente="+u.cliente.value
		+"&fecha="+fecha+"&val="+Math.random());
	}
	
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj.detalle);
		}else{
			mens.show("A","No se encontraron datos en el año seleccionado","¡Atención!");
			tabla1.clear();
			return false;
		}
	}
	
	function mostrarFrame(tipo){
		switch(tipo){		
			case "propuesta":			
					if(u.cliente.value!=""){
						document.getElementById('label1').innerHTML = "Propuestas de Convenio";
						seleccionarTab(1);
						u.pagina0.src = "propuestaConvenio.php?cliente="+u.cliente.value;
					}else{
						return false;
					}	
			break;
			
			case "vencidos":			
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Convenio por Vencer";
						seleccionarTab(1);
						u.pagina0.src = "index.php";
					}else{
						return false;
					}	
			break;
			
			case "convenio":			
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Convenio";
						seleccionarTab(1);
						u.pagina0.src = "informacionConvenio.php?cliente="+u.cliente.value;						
					}else{
						return false;
					}
			break;
			
			case "credito":
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Solicitud Credito";
						seleccionarTab(1);
						u.pagina0.src = "informacionCredito.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "cat":
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "CAT";
						seleccionarTab(1);
						u.pagina0.src = "cat.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "estadocuenta":
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Estado de Cuenta";
						seleccionarTab(1);
						u.pagina0.src = "estadocuenta.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "cobranza":
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Estado de Cobranza";						
						seleccionarTab(1);
						u.pagina0.src = "estadocobranza.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "solicitud":
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Solicitud Guias Empresariales";
						seleccionarTab(1);
						u.pagina0.src = "solicitudguias.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "historial":
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Historial de Movimientos";						
						seleccionarTab(1);
						u.pagina0.src = "historialmovimientos.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "recolecciones":
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Recolecciones y Envios";
						seleccionarTab(1);
						u.pagina0.src = "reporteProductividad.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "compromisos":					
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Compromisos de pagos";
						seleccionarTab(1);
						u.pagina0.src = "compromisospagos.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
			
			case "especiales":
					if(u.cliente.value!=""){			
						document.getElementById("label1").innerHTML = "Entregas Especiales";
						seleccionarTab(1);
						u.pagina0.src = "entregasespeciales.php?cliente="+u.cliente.value;
					}else{
						return false;
					}
			break;
		}
	}

</script>

<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
	<div class="canvas"> 
		<div class="content">
			<div class="extra-data3 clearfix">
				      <div class="dvTable clearfix" style="width:780px;  margin-top:5px; float:left;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<th width="7%" style="color:#000000"><div class="etiqueta" style="width:50px">Cliente</div></th>
							<th width="11%" valign="top"><input name="cliente" type="text" class="text" id="cliente" style="width:80px;" onkeypress="if(event.keyCode==13){obtenerCliente(this.value);}"/></th>
							<th width="6%" ><div class="etiqueta" style="width:40px"><input name="button" type="button" class="srch-btn" id="search" title="Buscar" onclick="abrirVentanaFija('../../buscadores_generales/buscarClienteGen.php?funcion=obtenerCliente', 550, 450, 'ventana', 'Busqueda')" value=" " /></div></th>
							<th width="76%"><input name="nombre" type="text" class="text" id="nombre" style="width:500px;" readonly="" /></th>
						  </tr>
						</table>
					  </div>
		  </div>
			<div class="det-guia" style="height:310px;">				
				  <div class="dvTable clearfix" style="width:250px; color:#FFFFFF;">						
						<div class="c1">Repre. Legal</div>
						<div class="c2" id="legal"></div>						
						<div class="c1">Tel.</div>
						<div class="c2" id="telefono"></div>
						<div class="c1">Cel.</div>
						<div class="c2" id="celular"></div>
						<div class="c1">Email</div>
						<div class="c2" id="email"></div>
						<div class="c1">Tipo cliente</div>
						<div class="c2" id="tipocliente"></div>						
						<div class="c1">Límite Crédito</div>
						<div class="c2" id="credito"></div>
						<div class="c1">Consumo</div>
						<div class="c2" id="consumo"></div>
						<div class="c1">Pagos</div>
						<div class="c2" id="pagado"></div>
						<div class="c1">Compromiso Men.</div>
						<div class="c2" id="compromiso"></div>
						<div class="c1">Compras Men.</div>
						<div class="c2" id="consumido"></div>
						<div class="c2" style="width:250px; font-size:12px; text-align:right;"></div>
				  </div>
		  </div>
				<div class="datos-cliente" style="font-size:11px; height:310px;">
				  <div class="dvTable clearfix" style="width:500px; margin-left:5px;">
				  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="43%"><span style="cursor:pointer" onclick="mostrarFrame('propuesta')">Propuestas de convenios</span></td>
						<td width="57%" id="tpropuesta">(0)</td>
					  </tr>
					  <tr>
					    <td height="5px"></td>
					    <td ></td>
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
						<td height="5px"></td>
						<td ></td>
					  </tr>
					  <tr>
						<td ><span style="cursor:pointer" onclick="mostrarFrame('solicitud')">Solicitud de gu&iacute;as empresariales</span></td>
						<td id="tsolicitud">(0)</td>
					  </tr>
					  <tr>
					    <td height="5px"></td>
					    <td></td>
				      </tr>
					  <tr>
						<td height="5px"></td>
						<td></td>
					  </tr>
					  <tr>
						<td ><span style="cursor:pointer" onclick="mostrarFrame('historial')">Historial de Movimientos</span></td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
					    <td height="5px"></td>
				      </tr>
					  <tr>
						<td height="5px"></td>
					  </tr>
					  <tr>
						<td colspan="2"><span style="cursor:pointer" onclick="mostrarFrame('recolecciones')">Reporte de Productividad</span></td>
					  </tr>
					  <tr>
					    <td height="5px"></td>
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
						<td height="5px"></td>
					  </tr>
					  <tr>
						<td ><span style="cursor:pointer" onclick="mostrarFrame('especiales')">Entregas especiales</span></td>
					    <td  id="tespeciales">(0)</td>
					  </tr>
					  <tr>
					    <td height="5px"></td>
				      </tr>
					  <tr>
						<td height="5px"></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
				  </table>
				  </div>
				</div>
			<div class="doc-req2">
				<div class="menu">
					<ul>
					<li class="active" id="pest0" onclick="seleccionarTab(0)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label0" class="active">Ventas</a></li>
					<li id="pest1" onclick="seleccionarTab(1)"><div class="mnu-l"></div><div class="mnu-r"></div><a href="#" id="label1">&nbsp;</a></li>						
					</ul>
				</div>				
				<div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; width:800px; height:320px" class="content-table" id="div0">
					<table width="577" height="141" style="margin-top:20px; margin-left:10px;">
					  <tr>
						<td>Ventas por Año:&nbsp;&nbsp;
						<select name="slMeses" id="slMeses" class="Tablas" onchange="obtenerDetalle(this.value)">
							<option value="">Seleccionar Año</option>
							<?	$s = "SELECT MIN(YEAR(fecha)) AS primera, YEAR(CURDATE())AS actual FROM generacionconvenio";
								$ss = mysql_query($s,$l) or die($s);
								$fs = mysql_fetch_object($ss);
							
								for($i=$fs->primera;$i<=$fs->actual;$i++){?>
						  <option value="<?=$i ?>"<? if($fecha==$i){ echo 'selected';} ?>><?=$i ?></option>
						  <?	} ?>
						</select></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td align="center"><div style="background:#282828">
						<div style="height:210px; width:720px; overflow:auto; ">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">
							</table>
						</div></div></td>
					  </tr>
					  <tr>
						<td><div id="paginado" align="center" style="visibility:hidden">     
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
				<div style="background:#FFF;margin-top:20px; margin-left:5px;position:absolute; visibility:hidden; width:800px; height:320px" class="content-table" id="div1">
					<table width="577" height="141" style="margin-top:20px; margin-left:10px;" border="1px">
						<tr>
							<td>
								<iframe name="pagina0" id="pagina0" scrolling="auto" align="top" width="750px" height="280px" frameborder="0" ></iframe>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</form>
</body>
</html>
