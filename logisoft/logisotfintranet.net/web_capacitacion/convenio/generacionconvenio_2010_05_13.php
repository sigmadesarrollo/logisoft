<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../javascript/ClaseTabsDivs.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/jquery-1.4.js"></script>
<script src="../javascript/jquery.maskedinput.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script>
		var tabs = new ClaseTabs();
		var ubi = document.all;
		var conveniopesokg 			= "";
		var conveniodescripcion 	= "";
		var consignacionpesokg 		= "";
		var consignaciondescripcion = "";
		var dconveniopesokg 			= "";
		var dconveniodescripcion 		= "";
		var dconsignacionpesokg 		= "";
		var dconsignaciondescripcion 	= "";
		var tablaservicios 			= new ClaseTabla();
		var tablaservicios2			= new ClaseTabla();

		var accionesNuevo = '<table align="center">'
          +'<tr>'
            +'<td width=><div class="ebtn_Guardar_Convenio" id="boton_guardar" onClick="guardarConvenio(); "></div></td>'
			+'<td width=><div class="ebtn_nuevo" onClick="limpiarTodo(); valoresIniciales()"></div></td>'
          +'</tr>'
        +'</table>';
		var accionesImpreso = '<table align="center">'
			 +' <tr>'
				+'<td ><div class="ebtn_activarconvenio" onClick="activarConvenio();" id="boton_activar"></div></td>'
				+'<td ><div class="ebtn_cancelarconvenio" onClick="noActivarConvenio();" id="boton_noactivar"></div></td>'
				+'<td width=><div class="ebtn_nuevo" onClick="limpiarTodo(); valoresIniciales()"></div></td>'
			 +' </tr>'
		   +' </table>';
		
		var accionesAutorizado = '<table align="center">'
			 +' <tr>'
			   +' <td><div class="ebtn_imprimirconvenio" onClick="imprimirActivado()"></div></td>'
				+'<td width=><div class="ebtn_nuevo" onClick="limpiarTodo();valoresIniciales()"></div></td>'
			  +'</tr>'
		   +' </table>';
		
		var accionesActivado = '<table align="center">'
			 +' <tr>'
				+'<td><div class="ebtn_imprimirconvenio" onClick="imprimirActivado()"></div></td>'
				+'<td width=><div class="ebtn_nuevo" onClick="limpiarTodo(); valoresIniciales()"></div></td>'
				+'<td width=><div class="ebtn_cancelarconvenio" onClick="cancelarConvenio();" id="boton_cancelar"></div></td>'
			 +' </tr>'
		   +' </table>';
		
		var accionesActivado2 = '<table align="center">'
			 +' <tr>'
				+'<td><div class="ebtn_imprimirconvenio" onClick="imprimirActivado()"></div></td>'
				+'<td width=><div class="ebtn_nuevo" onClick="limpiarTodo(); valoresIniciales()"></div></td>'
				+'<td width=><div class="ebtn_renovarc" onClick="renovarConvenio();" id="boton_renovar"></div></td>'
				+'<td width=><div class="ebtn_cancelarconvenio" onClick="cancelarConvenio();" id="boton_cancelar"></div></td>'
			 +' </tr>'
		   +' </table>';
		
		var accionesCancelado = '<table align="center">'
			 +' <tr>'
				+'<td width=><div class="ebtn_nuevo" onClick="limpiarTodo(); valoresIniciales()"></div></td>'
				+'<td width=></td>'
			 +' </tr>'
		   +' </table>';
		
		jQuery(function($){
		   $('#vencimiento').mask("99/99/9999");
		});

		function solonumeros(evnt){
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
	}
	
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
		filasInicial:7,
		alto:110,
		seleccion:true,
		ordenable:false,
		nombrevar:"tablaservicios2"
	});
	window.onload = function(){
		tablaservicios.create();
		tablaservicios2.create();
		
		conveniopesokg 				= ubi.div_preciokg.innerHTML;
		conveniodescripcion 		= ubi.div_descripcion.innerHTML;
		consignacionpesokg 			= ubi.detallekgc.innerHTML;
		consignaciondescripcion 	= ubi.detalledescripcionc.innerHTML;
		dconveniopesokg 			= conveniopesokg;
		dconveniodescripcion 		= conveniodescripcion;
		dconsignacionpesokg 		= consignacionpesokg;
		dconsignaciondescripcion 	= consignaciondescripcion;
		tabs.iniciar({
				nombre:"tab", largo:605, alto:150, ajustex:11,
				ajustey:12, imagenes:"../img", titulo:"Guias Normales"
			});		
			tabs.agregarTabs('Servicios G. Normales',1,null);
			tabs.agregarTabs('Guias Empresariales',2,null);
			tabs.agregarTabs('Servicios G. Empresariales',3,null);
			tabs.seleccionar(0);
		valoresIniciales();
		
		<?
		$_GET[funcion] = str_replace("\'","'",$_GET[funcion]);
		if($_GET[funcion]!=""){
			echo 'setTimeout("'.$_GET[funcion].'",1500);';
			}
		?>
	}
	
	function limpiarTodo(){
		ubi.foliop.value 			= "";
		ubi.vendedor.value			= "";
		ubi.vendedorb.value			= "";
		ubi.estadoc.innerHTML		= "";
		ubi.compromiso.value		= "";
		limpiarCliente();
		dconveniopesokg 			= conveniopesokg;
		dconveniodescripcion 		= conveniodescripcion;
		dconsignacionpesokg 		= consignacionpesokg;
		dconsignaciondescripcion 	= consignaciondescripcion;
		
		ubi.descuentoflete.value = "";
		
		ubi.div_preciokg.innerHTML			= conveniopesokg;
		ubi.div_descripcion.innerHTML		= conveniodescripcion;
		ubi.detallekgc.innerHTML			= consignacionpesokg;
		ubi.detalledescripcionc.innerHTML	= consignaciondescripcion;
		
		tablaservicios.clear();
		ubi.limitekg.value="";
		ubi.costoguia.value="";
		ubi.preciokgexcedente.value="";
		
		ubi.consignaciondes.value = "";
		tablaservicios2.clear();
		
		ubi.serviciosr1_sel.options.length = 0;
		ubi.sucursalesead1_sel.options.length = 0;
		ubi.serviciosr2_sel.options.length = 0;
		ubi.sucursalesead3_sel.options.length = 0;
		ubi.valordeclarado.value = "";
		ubi.limite.value = "";
		ubi.porcada.value = "";
		ubi.costoextra.value = "";
		ubi.legal.value = "";
		ubi.celdaacciones.innerHTML = accionesNuevo;
		tabs.seleccionar(0);
	}
	
	function mostrarPropuestasPendientes(){
		abrirVentanaFija('../buscadores_generales/buscarPropuestasPorEstado.php?funcion=pedirPropuesta&pestado=AUTORIZADA', 650, 500, 'ventana', 'Busqueda');
	}
	
	function mostrarConveniosPendientesImp(){
		abrirVentanaFija('../buscadores_generales/buscarConveniosPorEstado.php?funcion=pedirConvenio&cestado=AUTORIZADO', 650, 500, 'ventana', 'Busqueda');
	}
	
	function mostrarConveniosPendientesAct(){
		abrirVentanaFija('../buscadores_generales/buscarConveniosPorEstado.php?funcion=pedirConvenio&cestado=IMPRESO', 650, 500, 'ventana', 'Busqueda');
	}
	
	function mostrarConveniosPendientesVen(){
		abrirVentanaFija('../buscadores_generales/buscarConvenioGen.php?funcion=pedirConvenio', 650, 500, 'ventana', 'Busqueda');
	}
	
	function valoresIniciales(){
		consultaTexto("resValoresIniciales","generacionconvenio_con.php?accion=11");
	}
	
	function resValoresIniciales(datos){
		var cosa = eval(datos);
		
		ubi.folio.value = cosa.folio;
		ubi.fecha.value = cosa.fecha.replace(/-/g,"/");
		ubi.vencimiento.value = cosa.fechalimite.replace(/-/g,"/");
	}
	
	function agregarGrid(tabla,objeto){
		for(var i=0; i<objeto.length; i++)
			tabla.add(objeto[i]);
	}
	function agregarValores(combo,objeto,combo2){
		combo.options.length = 0;
		var opcion;
		
		if(objeto[0]!=undefined){
			if(objeto[0].nombre=="TODOS"){
				for(var i=0; i<combo2.options.length; i++){
					opcion = new Option(combo2.options[i].text,combo2.options[i].value);
					combo.options[combo.options.length] = opcion;
				}
			}else{		
				for(var i=0; i<objeto.length; i++){
					opcion = new Option(objeto[i].nombre,objeto[i].clave);
					combo.options[combo.options.length] = opcion;
				}
			}
		}
	}
	
	//funciones para propuesta
	function pedirPropuesta(valor){
		ubi.foliop.value = valor;
		consultaTexto("respPedirPropuesta","generacionconvenio_con.php?accion=1&valor="+valor+"&folio="+valor+"&ranm="+Math.random());
	}
	function respPedirPropuesta(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		limpiarTodo();
		if(objeto[0].propuesta[0].estadopropuesta){
			var epropuesta 					= objeto[0].propuesta[0].estadopropuesta;
			//ubi.folio.value 				= objeto[0].propuesta[0].folio;
			ubi.foliop.value 				= objeto[0].propuesta[0].folio;
			ubi.fecha.value 				= objeto[0].propuesta[0].factual;
			ubi.vencimiento.value			= objeto[0].propuesta[0].fvencimiento ;
			ubi.vendedor.value 				= objeto[0].propuesta[0].vendedor ;
			ubi.vendedorb.value 			= objeto[0].propuesta[0].nvendedor ;
			ubi.h_vencido.value				= objeto[0].propuesta[0].vencido;
			ubi.h_yatiene.value				= objeto[0].propuesta[0].yatiene;
			
			
			if(objeto[0].propuesta[0].personamoral==0){
				ubi.personamoral[1].checked = true;
			}else{
				ubi.personamoral[0].checked = true;
			}
			if(objeto[0].propuesta[0].tipo=='PRO'){
				ubi.clienterdo[0].checked = true;
			}else{
				ubi.clienterdo[1].checked = true;
			}
			
			
			ubi.prospecto.value 			= objeto[0].propuesta[0].idprospecto ;
			ubi.nick.value 					= objeto[0].propuesta[0].nick;
			ubi.rfc.value 					= objeto[0].propuesta[0].rfc;
			ubi.nombre.value 				= objeto[0].propuesta[0].nombre;
			ubi.paterno.value 				= objeto[0].propuesta[0].apaterno;
			ubi.materno.value 				= objeto[0].propuesta[0].amaterno;
			ubi.calle.value 				= objeto[0].propuesta[0].calle;
			ubi.numero.value 				= objeto[0].propuesta[0].numero;
			ubi.colonia.value 				= objeto[0].propuesta[0].colonia;
			ubi.cp.value 					= objeto[0].propuesta[0].cp;
			ubi.poblacion.value 			= objeto[0].propuesta[0].poblacion;
			ubi.municipio.value 			= objeto[0].propuesta[0].municipio;
			ubi.estado.value 				= objeto[0].propuesta[0].estado;
			ubi.pais.value 					= objeto[0].propuesta[0].pais;
			ubi.celular.value 				= objeto[0].propuesta[0].celular;
			ubi.telefono.value 				= objeto[0].propuesta[0].telefono;
			ubi.email.value 				= objeto[0].propuesta[0].email;
			ubi.valordeclarado.value		= objeto[0].propuesta[0].valordeclarado;
			ubi.valordeclarado.value		= convertirMoneda(ubi.valordeclarado.value);
			ubi.limite.value				= objeto[0].propuesta[0].limite;
			ubi.porcada.value				= objeto[0].propuesta[0].porcada;
			ubi.costoextra.value			= objeto[0].propuesta[0].costoextra;
			ubi.legal.value			= objeto[0].propuesta[0].legal;
			ubi.costoextra.value			= convertirMoneda(ubi.costoextra.value);
					
	if(ubi.h_vencido.value=="vencido"){
		alerta3("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio y actualmente se encuentra vencido", "¡Atención!");			
	}else if(ubi.h_yatiene.value=="ya tiene"){
		alerta3("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio", "¡Atención!");
	}
			
			ubi.radiogrupo[0].checked 		= (objeto[0].propuesta[0].precioporkg==1)?true:false;
			ubi.radiogrupo[1].checked 		= (objeto[0].propuesta[0].precioporcaja==1)?true:false;
			ubi.radiogrupo[2].checked = (objeto[0].propuesta[0].descuentosobreflete==1)?true:false;
			ubi.descuentoflete.value 		= objeto[0].propuesta[0].cantidaddescuento;
			ubi.limitekg.value 				= objeto[0].propuesta[0].limitekg;
			ubi.costoguia.value 			= objeto[0].propuesta[0].costo;
			ubi.costoguia.value 			= convertirMoneda(ubi.costoguia.value);
			ubi.preciokgexcedente.value		= objeto[0].propuesta[0].preciokgexcedente;
			ubi.preciokgexcedente.value		= convertirMoneda(ubi.preciokgexcedente.value);
			ubi.consignaciondes.value		= objeto[0].propuesta[0].consignaciondescantidad;
			
			ubi.checkprepagadas.checked		= (objeto[0].propuesta[0].prepagadas==1)?true:false;
			ubi.radiobutton[0].checked		= (objeto[0].propuesta[0].consignacionkg==1)?true:false;
			ubi.radiobutton[1].checked		= (objeto[0].propuesta[0].consignacioncaja==1)?true:false;
			ubi.radiobutton[2].checked		= (objeto[0].propuesta[0].consignaciondescuento==1)?true:false;
			
			agregarValores(ubi.serviciosr1_sel,objeto[0].serviciocombo1,ubi.serviciosr1, ubi.serviciosr1);
			agregarValores(ubi.sucursalesead1_sel,objeto[0].serviciocombo2,ubi.sucursalesead1, ubi.sucursalesead1);
			agregarValores(ubi.serviciosr2_sel,objeto[0].serviciocombo3,ubi.serviciosr1, ubi.serviciosr1);
			agregarValores(ubi.sucursalesead3_sel,objeto[0].serviciocombo5,ubi.sucursalesead1, ubi.sucursalesead1);
			
			agregarGrid(tablaservicios,objeto[0].serviciogrid1);
			agregarGrid(tablaservicios2,objeto[0].serviciogrid2);
			if(objeto[0].propuesta[0].precioporkg==1){
				consultaTexto("mostrarCGridKg", "propuestaconvenio_con.php?accion=7&valor=1&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].precioporcaja==1){
				consultaTexto("mostrarCGridPeso", "propuestaconvenio_con.php?accion=7&valor=2&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].consignacionkg==1){
				consultaTexto("mostrarSGridKg", "propuestaconvenio_con.php?accion=7&valor=3&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].consignacioncaja==1){
				consultaTexto("mostrarSGridPeso", "propuestaconvenio_con.php?accion=7&valor=4&idconvenio="+objeto[0].propuesta[0].folio)
			}
		}
	}
	function mostrarCGridKg(datos){
		ubi.div_preciokg.innerHTML = datos;
		dconveniopesokg 			= datos;
	}
	function mostrarCGridPeso(datos){
		ubi.div_descripcion.innerHTML = datos;
		dconveniodescripcion 		= datos;
	}
	function mostrarSGridKg(datos){
		ubi.detallekgc.innerHTML = datos;
		dconsignacionpesokg 		= datos;
	}
	function mostrarSGridPeso(datos){
		ubi.detalledescripcionc.innerHTML = datos;
		dconsignaciondescripcion 	= datos;
	}
	
	function pedirConvenio(valor){
		consultaTexto("respPedirConvenio","generacionconvenio_con.php?accion=5&valor="+valor+"&folio="+valor+"&ranm="+Math.random());
	}
	function respPedirConvenio(datos){
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		limpiarTodo();
		if(objeto.length){
			var econvenio 					= objeto[0].propuesta[0].estadoconvenio;
			
			if(econvenio == "AUTORIZADO"){
				ubi.celdaacciones.innerHTML = accionesAutorizado;
			}
			if(econvenio == "IMPRESO"){
				ubi.celdaacciones.innerHTML = accionesImpreso;
			}
			if(econvenio == "CANCELADO"){
			ubi.celdaacciones.innerHTML = accionesCancelado;
			}
			if(econvenio == "ACTIVADO" || econvenio == "NO ACTIVADO"){
				if(objeto[0].propuesta[0].botonrenovar == 'RENOVAR'){
					ubi.celdaacciones.innerHTML = accionesActivado2;
				}else{
					ubi.celdaacciones.innerHTML = accionesActivado;
				}
			}
			
			if(econvenio == "EXPIRADO"){
				ubi.celdaacciones.innerHTML = accionesActivado2;
			}
			
			ubi.clienterdo[1].checked = true;
			
			ubi.compromiso.value			= objeto[0].propuesta[0].consumomensual;
			
			ubi.folio.value 				= objeto[0].propuesta[0].folio;
			ubi.credito.value				= objeto[0].propuesta[0].foliocredito;
			ubi.fecha.value 				= objeto[0].propuesta[0].factual;
			ubi.estadoc.innerHTML			= objeto[0].propuesta[0].estadoconvenio;
			ubi.vencimiento.value			= objeto[0].propuesta[0].fvencimiento;
			ubi.vendedor.value 				= objeto[0].propuesta[0].vendedor ;
			ubi.vendedorb.value 			= objeto[0].propuesta[0].nvendedor ;
			ubi.consignaciondes.value		= objeto[0].propuesta[0].consignaciondescantidad;
			if(objeto[0].propuesta[0].personamoral==1)
				ubi.personamoral[0].checked = true;
			else
				ubi.personamoral[1].checked = true;
			
			ubi.prospecto.value 			= objeto[0].propuesta[0].idcliente;
			ubi.nick.value 					= objeto[0].propuesta[0].nick;
			ubi.rfc.value 					= objeto[0].propuesta[0].rfc;
			ubi.nombre.value 				= objeto[0].propuesta[0].nombre;
			ubi.paterno.value 				= objeto[0].propuesta[0].apaterno;
			ubi.materno.value 				= objeto[0].propuesta[0].amaterno;
			ubi.calle.value 				= objeto[0].propuesta[0].calle;
			ubi.numero.value 				= objeto[0].propuesta[0].numero;
			ubi.colonia.value 				= objeto[0].propuesta[0].colonia;
			ubi.cp.value 					= objeto[0].propuesta[0].cp;
			ubi.poblacion.value 			= objeto[0].propuesta[0].poblacion;
			ubi.municipio.value 			= objeto[0].propuesta[0].municipio;
			ubi.estado.value 				= objeto[0].propuesta[0].estado;
			ubi.pais.value 					= objeto[0].propuesta[0].pais;
			ubi.celular.value 				= objeto[0].propuesta[0].celular;
			ubi.telefono.value 				= objeto[0].propuesta[0].telefono;
			ubi.email.value 				= objeto[0].propuesta[0].email;
			ubi.valordeclarado.value		= objeto[0].propuesta[0].valordeclarado;
			ubi.valordeclarado.value		= convertirMoneda(ubi.valordeclarado.value);
			ubi.limite.value				= objeto[0].propuesta[0].limite;
			ubi.porcada.value				= objeto[0].propuesta[0].porcada;
			ubi.costoextra.value			= objeto[0].propuesta[0].costoextra;
			ubi.legal.value			= objeto[0].propuesta[0].legal;
			ubi.costoextra.value			= convertirMoneda(ubi.costoextra.value);
			
			ubi.radiogrupo[0].checked 		= (objeto[0].propuesta[0].precioporkg==1)?true:false;
			if(objeto[0].propuesta[0].precioporcaja==1){
				ubi.radiogrupo[1].checked 		= (objeto[0].propuesta[0].precioporcaja==1)?true:false;
			}else{
				ubi.radiogrupo[2].checked 		= (objeto[0].propuesta[0].descuentosobreflete==1)?true:false;
			}
			
			ubi.descuentoflete.value 		= objeto[0].propuesta[0].cantidaddescuento;
			ubi.limitekg.value 				= objeto[0].propuesta[0].limitekg;
			ubi.costoguia.value 			= objeto[0].propuesta[0].costo;
			ubi.preciokgexcedente.value		= objeto[0].propuesta[0].preciokgexcedente;
			
			ubi.checkprepagadas.checked		= (objeto[0].propuesta[0].prepagadas==1)?true:false;
			ubi.radiobutton[0].checked		= (objeto[0].propuesta[0].consignacionkg==1)?true:false;
			ubi.radiobutton[1].checked		= (objeto[0].propuesta[0].consignacioncaja==1)?true:false;
			ubi.radiobutton[2].checked		= (objeto[0].propuesta[0].consignaciondescuento==1)?true:false;
			
			agregarValores(ubi.serviciosr1_sel,objeto[0].serviciocombo1,ubi.serviciosr1);
			agregarValores(ubi.sucursalesead1_sel,objeto[0].serviciocombo2,ubi.sucursalesead1);
			agregarValores(ubi.serviciosr2_sel,objeto[0].serviciocombo3,ubi.serviciosr1);
			agregarValores(ubi.sucursalesead3_sel,objeto[0].serviciocombo5,ubi.sucursalesead1);
			
			agregarGrid(tablaservicios,objeto[0].serviciogrid1);
			agregarGrid(tablaservicios2,objeto[0].serviciogrid2);
			if(objeto[0].propuesta[0].precioporkg==1){
				consultaTexto("mostrarCGridKg", "generacionconvenio_con.php?accion=8&valor=1&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].precioporcaja==1){
				consultaTexto("mostrarCGridPeso", "generacionconvenio_con.php?accion=8&valor=2&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].consignacionkg==1){
				consultaTexto("mostrarSGridKg", "generacionconvenio_con.php?accion=8&valor=3&idconvenio="+objeto[0].propuesta[0].folio)
			}
			if(objeto[0].propuesta[0].consignacioncaja==1){
				consultaTexto("mostrarSGridPeso", "generacionconvenio_con.php?accion=8&valor=4&idconvenio="+objeto[0].propuesta[0].folio)
			}
		}
	}
	
	//EMPLEADO
	function pedirEmpleado(valor){
		document.all.vendedor.value = valor;
		consultaTexto("mostrarEmpleado", "propuestaconvenio_con.php?accion=1&valor="+valor+"&valram="+Math.random());
	}
	function mostrarEmpleado(valor){
		document.all.vendedorb.value = valor;
	}
	function pedirCliente(valor){
		consultaTexto("mostrarCliente", "generacionconvenio_con.php?accion=2&personamoral="+document.all.personamoral_valor.value+"&valor="+valor+"&valram="+Math.random());
	}
	function mostrarCliente(datos){
		
		if(datos.indexOf("vencido")>-1){
		alerta("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio y actualmente se encuentra vencido", "¡Atención!","prospecto");
		return false;
		}
		
		if(datos.indexOf("ya tiene")>-1){
			alerta("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio", "¡Atención!","prospecto");
			return false;
		}
		
		var objeto = eval(datos);
		limpiarCliente();
		if(objeto.length){
			ubi.credito.value	= objeto[0].folio;
			ubi.prospecto.value	= objeto[0].id;
			ubi.nick.value 		= objeto[0].nick;
			ubi.rfc.value 		= objeto[0].rfc;
			ubi.nombre.value 	= objeto[0].nombre;
			ubi.paterno.value 	= objeto[0].paterno;
			ubi.materno.value 	= objeto[0].materno;
			ubi.calle.value 	= objeto[0].calle;
			ubi.numero.value 	= objeto[0].numero;
			ubi.colonia.value 	= objeto[0].colonia;
			ubi.cp.value 		= objeto[0].cp;
			ubi.poblacion.value = objeto[0].poblacion;
			ubi.municipio.value = objeto[0].municipio;
			ubi.estado.value 	= objeto[0].estado;
			ubi.pais.value 		= objeto[0].pais;
			ubi.celular.value 	= objeto[0].celular;
			ubi.telefono.value 	= objeto[0].telefono;
			ubi.email.value 	= objeto[0].email;
		}else{
			alerta3("No se encontraron datos del prospecto", "¡Atención!");
		}
	}
	function limpiarCliente(){
			ubi.credito.value	= "";
			ubi.prospecto.value	= "";
			ubi.nick.value 		= "";
			ubi.rfc.value 		= "";
			ubi.nombre.value 	= "";
			ubi.paterno.value 	= "";
			ubi.materno.value 	= "";
			ubi.calle.value 	= "";
			ubi.numero.value 	= "";
			ubi.colonia.value 	= "";
			ubi.cp.value 		= "";
			ubi.poblacion.value = "";
			ubi.municipio.value = "";
			ubi.estado.value 	= "";
			ubi.pais.value 		= "";
			ubi.celular.value 	= "";
			ubi.telefono.value 	= "";
			ubi.email.value 	= "";
	}
	
	//paraconsignacion
	function desactivarConsignacion(valor){
		ubi.detallekgc.innerHTML 		  = consignacionpesokg;
		ubi.detalledescripcionc.innerHTML = consignaciondescripcion;
		switch (valor){
			case 2:
				ubi.detallekgc.innerHTML 		  = dconsignacionpesokg;
				break;
			case 3:
				ubi.detalledescripcionc.innerHTML = dconsignaciondescripcion;
				break;
		}
	}
	
	function guardarConvenio(){		
		<?=$cpermiso->verificarPermiso("393,396",$_SESSION[IDUSUARIO]);?>
		if(ubi.h_vencido.value=="vencido"){
		alerta3("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio y actualmente se encuentra vencido", "¡Atención!");
			return false;
		}else if(ubi.h_yatiene.value=="ya tiene"){
			alerta3("El Cliente #"+ubi.prospecto.value+" ya cuenta con convenio", "¡Atención!");
			return false;
		}		
		
		var precioporkg 			= (ubi.radiogrupo[0].checked)?"1":"0";
		var precioporcaja 			= (ubi.radiogrupo[1].checked)?"1":"0";
		
		var consignacionkg 			= (ubi.radiobutton[0].checked)?"1":"0";
		var consignacioncaja 		= (ubi.radiobutton[1].checked)?"1":"0";
		
		var vigencia				= document.all.vencimiento.value;
		
		if(ubi.clienterdo[0].checked){
			var tipoc = "PRO";
		}else{
			var tipoc = "CLI";
		}
		
		if(ubi.compromiso.value == ""){
			alerta("Porfavor ingrese el compromiso mensual","¡Atención!","compromiso");
		}else{
			
			document.all.boton_guardar.style.visibility='hidden';
			/*ubi.celdaerrores.innerHTML = "generacionconvenio_con.php?accion=3&idpropuesta="+ubi.foliop.value+"&tipoc="+tipoc
					  +"&idcliente="+ubi.prospecto.value+"&crecito="+ubi.credito.value+"&consumomensual="+ubi.compromiso.value
					  +"&precioporkg="+precioporkg+"&precioporcaja="+precioporcaja+"&consignacionkg="+consignacionkg
					  +"&consignacioncaja="+consignacionkg+"&random="+Math.random();*/
			consultaTexto("resGuardarConvenio","generacionconvenio_con.php?accion=3&idpropuesta="+ubi.foliop.value+"&tipoc="+tipoc
					  +"&idcliente="+ubi.prospecto.value+"&crecito="+ubi.credito.value+"&consumomensual="+ubi.compromiso.value
					  +"&precioporkg="+precioporkg+"&precioporcaja="+precioporcaja+"&consignacionkg="+consignacionkg
					  +"&consignacioncaja="+consignacionkg+"&vigencia="+vigencia+"&random="+Math.random());
		}
	}
	
	function resGuardarConvenio(datosx){
		if(datosx.indexOf("guardo")>-1){
			ubi.estadoc.innerHTML = "AUTORIZADO";	
			ubi.folio.value = datosx.split(",")[1];
			ubi.clienterdo[1].checked = true;
			ubi.prospecto.value = datosx.split(",")[3];
			info("El convenio a cambiado su estado a autorizado","¡Atención!");
			ubi.celdaacciones.innerHTML = accionesAutorizado;
		}else{
			alerta3("Error al guardar",datosx);
		}
	}
	
	function imprimirActivado(){
		<?=$cpermiso->verificarPermiso("394,397",$_SESSION[IDUSUARIO]);?>
		if(ubi.estadoc.innerHTML == "ACTIVADO"){
			window.open("../clasePDF/imprimirConvenio.php?folio="+ubi.folio.value);
		}else{
			if(ubi.folio.value==""){
				alerta3("Seleccione el folio para activar","¡Atención!");
			}else{
				consultaTexto("resImprimir","generacionconvenio_con.php?accion=4&folio="+ubi.folio.value
						  +"&random="+Math.random());
			}
		}
	}
	function resImprimir(datos){
		if(datos.indexOf("impreso")>-1){
			ubi.estadoc.innerHTML = "IMPRESO";
			window.open("../clasePDF/imprimirConvenio.php?folio="+ubi.folio.value);
			info("El convenio a cambiado su estado a IMPRESO", "¡Atención!");
			ubi.celdaacciones.innerHTML = accionesImpreso;
		}else{
			alerta3("Error al cambiar estado", datos);
		}
	}
	
	function activarConvenio(){
		<?=$cpermiso->verificarPermiso("395,398",$_SESSION[IDUSUARIO]);?>
		consultaTexto("resConsultarSiActivado","generacionconvenio_con.php?accion=12&cliente="+ubi.prospecto.value+"&random="+Math.random());
	}
	
	function resConsultarSiActivado(datos){
		if(datos.indexOf("SI TIENE")>-1){
			alerta3("El cliente ya tiene convenio","¡Atención!");
		}else{
			document.all.boton_activar.style.visibility='hidden';
			consultaTexto("resActivarConvenio","generacionconvenio_con.php?accion=6&folio="+ubi.folio.value
					  +"&random="+Math.random());
		}
	}
	
	function resActivarConvenio(datos){
		if(datos.indexOf("impreso")>-1){
			ubi.estadoc.innerHTML = "ACTIVADO";
			info("El convenio a cambiado su estado a ACTIVADO", "¡Atención!");
			ubi.celdaacciones.innerHTML = accionesActivado;
		}else{
			alerta3("Error al cambiar estado", datos);
		}
	}
	
	function noActivarConvenio(){
		<?=$cpermiso->verificarPermiso("387,388",$_SESSION[IDUSUARIO]);?>
		document.all.boton_noactivar.style.visibility='hidden';
		consultaTexto("resNoActivarConvenio","generacionconvenio_con.php?accion=7&folio="+ubi.folio.value
					  +"&random="+Math.random());
	}
	function resNoActivarConvenio(datos){
		if(datos.indexOf("impreso")>-1){
			ubi.estadoc.innerHTML = "NO ACTIVADO";
			info("El convenio a cambiado su estado a NO ACTIVADO", "¡Atención!");
			ubi.celdaacciones.innerHTML = accionesActivado;
		}else{
			alerta3("Error al cambiar estado", datos);
		}
	}
	
	function renovarConvenio(){
		document.all.boton_renovar.style.visibility='hidden';
		consultaTexto("resRenovarConvenio","generacionconvenio_con.php?accion=9&folio="+ubi.folio.value
					  +"&random="+Math.random());
	}
	function resRenovarConvenio(datos){
		if(datos.indexOf("actualizo")>-1){
			info("El convenio se ha renovado, <br>cambiado su estado a AUTORIZADO", "¡Atención!");
			ubi.estadoc.innerHTML = "AUTORIZADO";
			ubi.celdaacciones.innerHTML = accionesAutorizado;
			ubi.vencimiento.value = datos.split(',')[1];
		}else{
			alerta3("Error al renovar<bR>"+datos, "");
		}
	}
	
	function cancelarConvenio(){
		confirmar("¿Seguro desea cancelar el convenio?","ATENCION","siCancelar()")
	}
	
	function siCancelar(){
		document.all.boton_cancelar.style.visibility='hidden';
		consultaTexto("resCancelarConvenio","generacionconvenio_con.php?accion=10&folio="+ubi.folio.value
					  +"&random="+Math.random());
	}
	
	function resCancelarConvenio(datos){
		if(datos.indexOf("cancelado")>-1){
			alerta3("El Convenio ha sido Cancelado","¡Atención!");
			ubi.estadoc.innerHTML = "CANCELADO";
			ubi.celdaacciones.innerHTML = accionesCancelado;
		}else{
			alerta3(datos,"Error al cancelar");
		}
	}
	
	function validarFecha(e,param,name){
		tecla = (ubi) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,3),10);
				var year = 	parseInt(param.substring(6,10),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					alerta('La fecha no es valida', '¡Atención!',name);
					return false;
				}				
				if(dia > 29 && (mes=="02" || mes==2)){
					if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){
						alerta3('La fecha de vencimiento no es valida, por que el año '+year+' es bisiesto su maximo dia es 29', '¡Atención!');
						return false;
					}else{
						alerta3('La fecha de vencimiento no es valida, por que el año '+year+' no es bisiesto su maximo dia es 28', '¡Atención!');
						return false;
					}
				}
				
				if(dia >= 29 && (mes=="02" || mes=="2")){
					if(!((year % 4 == 0 && year % 100 != 0) || year % 400 == 0)){
						alerta3('La fecha de vencimiento no es valida, por que el año '+year+' no es bisiesto su maximo dia es 28', '¡Atención!');
							return false;
					}
				}
				if(dia > "31" || dia=="0"){
					alerta('La fecha no es valida, capture correctamente el Dia', '¡Atención!',name);
					return false;
				}
				if(mes > "12" || mes=="0"){
					alerta('La fecha no es valida, capture correctamente el Mes', '¡Atención!',name);
					return false;	
				}
			}	
		}
	}
	function convertirMoneda(valor){		
		valor = (valor=="")?"0.00":valor;
		valor = Math.round(parseFloat(valor)*100)/100;
		valor = "$ "+numcredvar(valor.toLocaleString());
		return valor;
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" >
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div style="position:absolute; left: 2px; top: 24px; visibility:visible;" id="tab_tab_id0">
	<table width="534" border="0" cellpadding="0" cellspacing="0">
		
      <tr>
        <td colspan="2"><table width="534" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><input type="radio" name="radiogrupo" value="checkbox"  /></td>
            <td>Precio por KG</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="radio" name="radiogrupo" value="checkbox" /></td>
            <td>Precio por Caja/Paquete </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="20"><input type="radio" name="radiogrupo" /></td>
            <td width="125">Descuento Sobre Flete</td>
            <?
				$s = "SELECT desmaximopermitido FROM configuradorgeneral";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
            <td width="158"><input name="descuentoflete" type="text" class="Tablas" id="descuentoflete" onKeyPress="return solonumeros(event)" style="width:100px;background:#FFFF99" readonly onBlur="if(this.value><?=$f->desmaximopermitido?>){alerta('El descuento maximo permitido es de <?=$f->desmaximopermitido?>','¡Atención!', 'descuentoflete');}" /> <input type="hidden" name="descmaxpermitido" value="<?=$f->desmaximopermitido?>"></td>
            <td width="92">&nbsp;</td>
            <td width="139">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
            <td width="810"><div id="div_preciokg" name="detalle" style="width:600px; height:80px; overflow:auto" align="left">
            	<?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
                <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                	<tr>
                     <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 0;
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" class="formato_columnasg" width="55px" align="center" >Zona <?=$zona?><br><?=$f->zoi?>-<?=$f->zof?></td>
                    <?
							$zona++;
						}
					?>
                  </tr>
                 <tr>
                 	 <td  class="formato_columnasg" height="16" >Precio KG</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" >&nbsp;</td>
                    <?
						}
					?>
                  </tr>
                </table>
            </div></td>
          </tr>
        </table></td>
      </tr>
	  <tr>
	  <td width="259">&nbsp;</td>
	  </tr>
	  <tr>
	    <td>&nbsp;</td>
	    </tr>
      <tr>
        <td colspan="2"><table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
            <td width="810"><div id="div_descripcion" style="width:600px; height:80px; overflow:auto" align="left">
            <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
                <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                	<tr>
                     <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 0;
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" class="formato_columnasg" width="55px" align="center" >Zona <?=$zona?><br><?=$f->zoi?>-<?=$f->zof?></td>
                    <?
							$zona++;
						}
					?>
                  </tr>
                 <tr>
                 	 <td  class="formato_columnasg" height="16" >Descripcion</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" >&nbsp;</td>
                    <?
						}
					?>
                  </tr>
                </table>
            </div></td>
          </tr>
        </table></td>
      </tr>
	</table>
</div>
<div style="position:absolute; left: 2px; top: 24px; visibility:visible;" id="tab_tab_id1">
<table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios">
    </table></td>
	</tr>
	
	<tr>
		<td colspan="2"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
        <td align="center" style="font-size:20"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9" height="16"   class="formato_columnas_izq"></td>
            <td width="250"class="formato_columnas" align="center">SERVICIOS RESTRINGIDOS POR EL CLIENTE </td>
            <td width="9"class="formato_columnas_der"></td>
          </tr>
          <tr>
            <td colspan="12"><select name="sucursalesead1" style="width:200px; visibility:hidden">
              <? 
					$s = "select * from catalogosucursal where id > 1";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
              <option value="<?=$f->id?>">
                <?=$f->descripcion?>
                </option>
              <?
					}
				?>
            </select></td>
          </tr>
          <tr>
            <td colspan="12"><div align="center">
                <select name="serviciosr1_sel" size="7" style="width:265px">
                </select>
            </div></td>
          </tr>
        </table></td>
        <td width="276" align="center" style="font-size:20"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9" height="16"   class="formato_columnas_izq"></td>
            <td width="250"class="formato_columnas" align="center">SUCURSALES QUE APLICA EAD Y RECOLECCIONE</td>
            <td width="9"class="formato_columnas_der"></td>
          </tr>
          <tr>
            <td colspan="12"><table width="266" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="12"><select name="serviciosr1" style="width:200px; visibility:hidden">
                    <? 
					$s = "select * from catalogoservicio where restringir = 'SI'";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
                    <option value="<?=$f->id?>">
                      <?=$f->descripcion?>
                      </option>
                    <?
					}
				?>
                  </select>                 </td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="12"><div align="center">
                <select name="sucursalesead1_sel" size="7" style="width:265px" >
                </select>
            </div></td>
          </tr>
        </table></td>
      </tr>
        </table></td>
	</tr>
</table>

</div>
<div style="position:absolute; left: 2px; top: 24px; visibility:visible;" id="tab_tab_id2">
	<table width="534" border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td><table width="534" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="20"><label>
                <input name="checkprepagadas" type="checkbox" value="0"  />
              </label></td>
              <td width="74">Pre-Pagadas</td>
              <td width="157">Limite KG <span class="Tablas">
                <input name="limitekg" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return tiposMoneda(event,this.value)" value="<?=$limitekg ?>" readonly="readonly">
              </span></td>
              <td width="37">Costo </td>
              <td width="110"><span class="Tablas">
                <input name="costoguia" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right" onkeypress="return tiposMoneda(event,this.value)" readonly="readonly" value="<?=$Costo ?>"/>
              </span></td>
              <td width="57"> Precio KG Excedente</td>
              <td width="79"><input name="preciokgexcedente" type="text" class="Tablas" style="width:70px;background:#FFFF99; text-align:right" onkeypress="return tiposMoneda(event,this.value)" readonly="readonly" value="<?=$Costo ?>"/></td>
            </tr>
          </table></td>
	  </tr>
		<tr>
		  <td><table width="534" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="20"><input name="radiobutton" type="radio" value="1" /></td>
              <td width="87">Consignaci&oacute;n KG</td>
              <td width="80">&nbsp;</td>
              <td width="155">&nbsp;</td>
              <td width="103">&nbsp;</td>
              <td width="89"><input type="hidden" value="<?=$tarminkgexc?>" name="tarminkgexc" /></td>
            </tr>
          </table></td>
	  </tr>
		<tr>
		  <td><table width="277" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="21"><input name="radiobutton" type="radio" value="2"  /></td>
              <td width="256"> Consignaci&oacute;n Paquete </td>
            </tr>
          </table></td>
	  </tr>
		<tr>
		  <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="21"><input name="radiobutton" type="radio" value="3"  /></td>
              <td width="135">Consignaci&oacute;n Descuento </td>
              <td width="156"><span class="Tablas">
                <?
				$s = "SELECT desmaximopermitido FROM configuradorgeneral";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
                <input name="consignaciondes" type="text" class="Tablas" id="consignaciondes" style="width:100px;background:#FFFF99" onkeypress="return solonumeros(event)" readonly="readonly" value="<?=$consignaciondes ?>" onblur="if(this.value&gt;<?=$f->desmaximopermitido?>){alerta('El descuento maximo permitido es de <?=$f->desmaximopermitido?>','&iexcl;Atenci&oacute;n!', 'descuentoflete');}" />
              </span></td>
              <td width="112">&nbsp;</td>
              <td width="115">&nbsp;</td>
            </tr>
          </table></td>
	  </tr>
		<tr>
		  <td><table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
            <td width="810" align="right"><div id="detallekgc" style="width:600px; height:80px; overflow:auto" align="left">
            <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
                <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                	<tr>
                     <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 0;
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" class="formato_columnasg" width="55px" align="center" >Zona <?=$zona?><br><?=$f->zoi?>-<?=$f->zof?></td>
                    <?
							$zona++;
						}
					?>
                  </tr>
                 <tr>
                 	 <td  class="formato_columnasg" height="16" >Precio KG</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" >&nbsp;</td>
                    <?
						}
					?>
                  </tr>
                </table>
            </div></td>
          </tr>
        </table></td>
	  </tr>
		<tr>
		  <td>
      <input type="hidden" name="tienedatoskgc" value="0">
      <input type="hidden" name="tienedatoskgc_excedio" value="0"></td>
	  </tr>
		<tr>
		  <td><table width="532" border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
            <td width="810" align="right"><div id="detalledescripcionc" name="detalle" style="width:600px; height:100px; overflow:auto" align="left">
            <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$tamanotabla = 55*mysql_num_rows($r);
						
					?>
                <table border="0" cellspacing="0" cellpadding="0" width="<?=$tamanotabla+55?>">
                	<tr>
                     <td height="16" width="55"  class="formato_columnasg">&nbsp;</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						$zona = 0;
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" class="formato_columnasg" width="55px" align="center" >Zona <?=$zona?><br><?=$f->zoi?>-<?=$f->zof?></td>
                    <?
							$zona++;
						}
					?>
                  </tr>
                 <tr>
                 	 <td  class="formato_columnasg" height="16" >Descripcion</td>
                    <?
						$s = "SELECT * FROM configuraciondetalles 
						GROUP BY zoi";
						$r = mysql_query($s,$l) or die($s);
						while($f = mysql_fetch_object($r)){
					?>
                    <td height="16" >&nbsp;</td>
                    <?
						}
					?>
                  </tr>
                </table>
            </div></td>
          </tr>
        </table></td>
	  </tr>
		<tr>
		  <td>
        <input type="hidden" name="tienedatosprecioc" value="0">
        <input type="hidden" name="tienedatosprecioc_excedio" value="0"></td>
	  </tr>
     </table>
</div>
<div style="position:absolute; left: 2px; top: 24px; visibility:visible;" id="tab_tab_id3">
	<table width="534" border="0" cellpadding="0" cellspacing="0">
		<tr>
		    <td width="534"><table border="0" cellpadding="0" cellspacing="0" id="tabladeservicios2">
   			</table></td>
        </tr>
		<tr>
		    <td>
          <input type="hidden" name="servicios2" value="1"></td>
        </tr>
		<tr>
		  <td>&nbsp;</td>
	  </tr>
		<tr>
		  <td><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
        <td align="center" style="font-size:20"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9" height="16"   class="formato_columnas_izq"></td>
            <td width="250"class="formato_columnas" align="center">SERVICIOS RESTRINGIDOS POR EL CLIENTE </td>
            <td width="9"class="formato_columnas_der"></td>
          </tr>
          <tr>
            <td colspan="12">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="12"><div align="center">
                <select name="serviciosr2_sel" size="7" style="width:265px">
                </select>
            </div></td>
          </tr>
        </table></td>
        <td width="276" align="center" style="font-size:20"><table width="266" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9" height="16"   class="formato_columnas_izq"></td>
            <td width="250"class="formato_columnas" align="center">SUCURSALES QUE APLICA EAD Y RECOLECCIONE</td>
            <td width="9"class="formato_columnas_der"></td>
          </tr>
          <tr>
            <td colspan="12"><table width="266" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="12">&nbsp;
                 </td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="12"><div align="center">
                <select name="sucursalesead3_sel" size="7" style="width:265px" >
                </select>
            </div></td>
          </tr>
        </table></td>
      </tr>
        </table></td>
	  </tr>
		<tr>
		  	<td>&nbsp;</td>
		</tr>
     </table>
</div>
  <table width="620" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="533" class="FondoTabla Estilo4">GENERACI&Oacute;N DE CONVENIO</td>
    </tr>
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="2"><table width="534" border="1">
              </table>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td colspan="5" id="celdaerrores"><div align="center"></div></td>
                  </tr>
                  <tr>
                    <td width="90">Folio Propuesta: </td>
                    <td width="107"><span class="Tablas">
                      <input name="foliop" type="text" class="Tablas" id="foliop" style="width:100px" onkeypress="if(event.keyCode==13){pedirPropuesta(this.value);}" value="<?=$foliop ?>" />
                    </span></td>
                    <td width="111"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarPropuestaConvenioGen.php?funcion=pedirPropuesta&amp;pestado=AUTORIZADA', 600, 500, 'ventana', 'Busqueda')"></div></td>
                    <td width="58"><span class="Tablas">Fecha: </span></td>
                    <td width="166"><span class="Tablas">
                      <input name="fecha" type="text" class="Tablas" id="fecha2" style="width:80px;background:#FFFF99" value="<?=$fecha ?>" readonly=""/>
                    </span></td>
                  </tr>
              </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="36">Folio:</td>
                  <td width="78"><span class="Tablas">
                    <input name="folio" type="text" class="Tablas" style="width:70px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>
                  </span></td>
                  <td width="36"><div class="ebtn_buscar" onclick="abrirVentanaFija('../buscadores_generales/buscarConvenioGen.php?funcion=pedirConvenio', 600, 500, 'ventana', 'Busqueda')"></div></td>
                  <td width="23">&nbsp;</td>
                  <td width="82">Vencimiento:</td>
                  <td width="94"><span class="Tablas">
                    <input name="vencimiento" type="text" class="Tablas" id="vencimiento" style="width:80px;" value="<?=$vencimiento ?>"  onkeypress="validarFecha(event,this.value,this.name);"/>
                  </span></td>
                  <td width="183"><span class="Tablas"><img src="../img/calendario.gif" alt="Baja" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.forms[0].vencimiento,'dd/mm/yyyy',this)" /></span></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="95" height="20">Estado Convenio: </td>
                  <td colspan="4" id="estadoc" style=" font-family:Tahoma, Geneva, sans-serif;font-size:20px;"></td>
                  <td width="125">&nbsp;</td>
                  <td width="78">&nbsp;</td>
                </tr>
                <tr>
                  <td>Cr&eacute;dito:</td>
                  <td width="75" style=" font-family:Tahoma, Geneva, sans-serif;font-size:20px;"><input name="credito" type="text" class="Tablas" id="credito" style="width:60px;background:#FFFF99" value="<?=$credito ?>" readonly=""/></td>
                  <td colspan="3" align="right">Compromiso de Consumo:</td>
                  <td><span class="Tablas">
                    <input name="compromiso" type="text" class="Tablas" id="compromiso" style="width:60px" value="<?=$compromiso ?>" />
                    Mensual</span></td>
                  <td>&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="59">Vendedor:</td>
                  <td width="91"><span class="Tablas">
                    <input name="vendedor" type="text" class="Tablas" id="vendeor" style="width:80px" value="<?=$vendedro ?>"  onkeypress="if(event.keyCode==13){pedirEmpleado(this.value)}else{return solonumeros(event)}" />
                  </span></td>
                  <td width="28"><div class="ebtn_buscar" id="buscarvendedor" onclick="abrirVentanaFija('../buscadores_generales/buscarEmpleado.php?funcion=pedirEmpleado&amp;tipo=29', 600, 550, 'ventana', 'Busqueda')"></div></td>
                  <td width="354"><span class="Tablas">
                    <input name="vendedorb" type="text" class="Tablas" id="vendedorb" style="width:250px;background:#FFFF99" value="<?=$vendedrob ?>" readonly=""/>
                  </span></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2" class="FondoTabla Estilo4">Datos Cliente </td>
          </tr>
          <tr>
            <td width="228"><label>
              <input name="clienterdo" type="radio" disabled="disabled" checked="checked" />
              Prospecto </label>
                <label>
                <input name="clienterdo" type="radio" disabled="disabled" />
                  Cliente</label>            </td>
            <td width="388"><label>
              <input name="personamoral" type="radio" value="SI" checked="checked" onclick="document.all.personamoral_valor.value = 'SI'; limpiarCliente()" />
Persona Moral</label>
              <label>
              <input name="personamoral" type="radio" value="NO" onclick="document.all.personamoral_valor.value = 'NO'; limpiarCliente()" />
Persona F&iacute;sica</label>
              <input type="hidden" name="personamoral_valor" value="SI" /></td>
          </tr>
          
          <tr>
            <td colspan="2"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="68" id="cliproscelda">Clave:</td>
                  <td width="190"><span class="Tablas">
                    <input name="prospecto" type="text" class="Tablas" id="prospecto" style="width:100px" value="<?=$vendedro ?>" onkeypress="if(event.keyCode==13){if(ubi.clienterdo[0].checked){pedirProspecto(this.value)}else{pedirCliente(this.value)}}else{return solonumeros(event)}" />
                    <img src="../img/Buscar_24.gif" id="buscarprospecto" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarClienteGen2.php?funcion=pedirCliente&amp;tipo='+((document.all.personamoral_valor.value=='SI')?'moral':'fisica'), 625, 450, 'ventana', 'Busqueda')" /></span></td>
                  <td width="13">&nbsp;</td>
                  <td width="74">&nbsp;</td>
                  <td width="178">&nbsp;</td>
                  <td width="11">&nbsp;</td>
                </tr>
                <tr>
                  <td>Nick:</td>
                  <td><span class="Tablas">
                    <input name="nick" type="text" class="Tablas" id="nick" style="width:170px;background:#FFFF99" value="<?=$nick ?>" readonly=""/>
                  </span></td>
                  <td>&nbsp;</td>
                  <td>R.F.C.:</td>
                  <td><span class="Tablas">
                    <input name="rfc" type="text" class="Tablas" id="rfc" style="width:170px;background:#FFFF99" value="<?=$rfc ?>" readonly=""/>
                  </span></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Nombre:</td>
                  <td colspan="4"><span class="Tablas">
                    <input name="nombre" type="text" class="Tablas" id="nombre" style="width:447px;background:#FFFF99" value="<?=$nombre ?>" readonly=""/>
                  </span></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Ap. Paterno: </td>
                  <td><span class="Tablas">
                    <input name="paterno" type="text" class="Tablas" id="paterno" style="width:170px;background:#FFFF99" value="<?=$paterno ?>" readonly=""/>
                  </span></td>
                  <td>&nbsp;</td>
                  <td>Ap. Materno: </td>
                  <td><span class="Tablas">
                    <input name="materno" type="text" class="Tablas" id="materno" style="width:170px;background:#FFFF99" value="<?=$materno ?>" readonly=""/>
                  </span></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Calle:</td>
                  <td><span class="Tablas">
                    <input name="calle" type="text" class="Tablas" id="calle" style="width:170px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>
                  </span></td>
                  <td>&nbsp;</td>
                  <td><span class="Tablas">N&uacute;mero</span>:</td>
                  <td><span class="Tablas">
                    <input name="numero" type="text" class="Tablas" id="numero" style="width:170px;background:#FFFF99" value="<?=$numero ?>" readonly=""/>
                  </span></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Colonia:</td>
                  <td><input name="colonia" type="text" class="Tablas" id="colonia" style="width:170px;background:#FFFF99" value="<?=$colonia ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                  <td>C.P.:</td>
                  <td><input name="cp" type="text" class="Tablas" id="cp" style="width:170px;background:#FFFF99" value="<?=$cp ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Poblaci&oacute;n:</td>
                  <td><input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:170px;background:#FFFF99" value="<?=$poblacion ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                  <td>Mun./Del.:</td>
                  <td><input name="municipio" type="text" class="Tablas" id="municipio" style="width:170px;background:#FFFF99" value="<?=$municipio ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Estado:</td>
                  <td><input name="estado" type="text" class="Tablas" id="estado" style="width:170px;background:#FFFF99" value="<?=$estado ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                  <td>Pa&iacute;s:</td>
                  <td><input name="pais" type="text" class="Tablas" id="pais" style="width:170px;background:#FFFF99" value="<?=$pais ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Tel&eacute;fono:</td>
                  <td><input name="telefono" type="text" class="Tablas" id="telefono" style="width:170px;background:#FFFF99" value="<?=$telefono ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                  <td>Celular:</td>
                  <td><input name="celular" type="text" class="Tablas" id="celular" style="width:170px;background:#FFFF99" value="<?=$celular ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Email:</td>
                  <td><input name="email" type="text" class="Tablas" id="email" style="width:170px;background:#FFFF99" value="<?=$email ?>" readonly=""/></td>
                  <td>&nbsp;</td>
                  <td>Repre. Legal: </td>
                  <td><input name="legal" type="text" class="Tablas" id="legal" style="width:170px" value="<?=$legal ?>" /></td>
                  <td>&nbsp;</td>
                </tr>
               
            </table></td>
          </tr>
          <tr>
            <td colspan="2" class="FondoTabla Estilo4">Valor Declarado </td>
          </tr>
          <tr>
            <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>Costo: </td>
                  <td><input name="valordeclarado" type="text" class="Tablas" id="valordeclarado" onkeypress="if(event.keyCode==13){this.value = convertirMoneda(this.value);document.all.limite.focus();}else{return tiposMoneda(event,this.value)}" /></td>
                  <td>Limite:</td>
                  <td><input name="limite" type="text" class="Tablas" id="limite" onkeypress="if(event.keyCode==13){document.all.porcada.focus();}else{ return solonumeros2(event)}"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td width="13%">Por Cada: </td>
                  <td width="31%"><input name="porcada" type="text" class="Tablas" id="porcada" onkeypress="if(event.keyCode==13){ document.all.costoextra.focus();}else{return solonumeros2(event)}"></td>
                  <td width="13%">Costo Extra: </td>
                  <td width="24%"><input name="costoextra" type="text" class="Tablas" id="costoextra" onkeypress="if(event.keyCode==13){ this.value = convertirMoneda(this.value);document.all.radiogrupo[0].focus();}else{return tiposMoneda(event,this.value)}"></td>
                  <td width="19%">&nbsp;</td>
                </tr>
            </table></td>
          </tr>

          <tr>
            <td colspan="2"><table id="tab" cellpadding="0" cellspacing="0" border="0">
        </table></td>
          </tr>
          <tr>
            <td colspan="2" height="300" >&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="celdaacciones"><table align="center">
                <tr>
                  <td width=""><div class="ebtn_Guardar_Convenio" id="boton_guardar" onclick="guardarConvenio()"></div></td>
                  <td width=""><div class="ebtn_nuevo" onclick="limpiarTodo(); valoresIniciales()"></div></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><input name="h_vencido" type="hidden" id="h_vencido" />
                <input name="h_yatiene" type="hidden" id="h_yatiene" /></td>
          </tr>
        </table>
          </td>
    </tr>
  </table>

</form>
</body>
</html>
