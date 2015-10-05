<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$s = "select descripcion from catalogosucursal where id = $_SESSION[IDSUCURSAL]";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$nsucursal = $f->descripcion;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
.Estilo2 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
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
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../javascript/ajax.js"></script>
<script language="javascript" src="../javascript/funciones_tablas.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../javascript/jquery-1.4.js"></script>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style></head>
<script>
	
	//botones para las acciones
	var condicionpuesta = false;
	var botonesnuevo = "<table><tr><td><div class=\"ebtn_guardar\" onclick=\"if(validarGuardar()){ confirmar('¿Desea guardar la factura?','¡Atencion!','guardarFacturar()',''); }\"/></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio(); bloquear(false);\"/></td></tr></table>";
	var botonescargar = "<table><tr><td><div class=\"ebtn_Cancelar_Factura\" onclick=\"if(document.all.estadofactura.innerHTML=='GUARDADO' || document.all.estadofactura.innerHTML=='PAGADA'){confirmar('¿Desea Cancelar la factura?','¡Atencion!','cancelarFactura()','')}else{alerta('La factura no se ha guardado','¡Atencion!','folio')}\"/></td><td><div class=\"ebtn_imprimir\" onClick=\"preguntarImprimir();\" /></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio(); bloquear(false);\"/></td></tr></table>";
	var botonescancelado = "<table><tr><td><div class=\"ebtn_Sustituir_Factura\" onclick=\"if(document.all.estadofactura.innerHTML=='CANCELADO'){confirmar('¿Desea Sustituir la factura?','¡Atencion!','sustituirFactura()','')}else{alerta('La factura no se ha guardado','¡Atencion!','folio')}\"/></td><td><div class=\"ebtn_nuevo\" onclick=\"limpiarTodo();pedirFolio(); bloquear(false);\"/></td></tr></table>";
	var tabla1 = new ClaseTabla();
	var tabla2 = new ClaseTabla();
	var u = document.all;
	tabla1.setAttributes({
		nombre:"detalle_guias1",
		campos:[
			{nombre:"S", medida:16, alineacion:"center", tipo:"checkbox", datos:"seleccion", onClick:"calcularSeleccionX"},
			{nombre:"FOLIO", medida:75, alineacion:"center" , datos:"id"},
			{nombre:"TIPOGUIA", medida:45, alineacion:"center", datos:"tipoguia"},
			{nombre:"FECHA", medida:50, alineacion:"center", datos:"fecha"},
			{nombre:"FLETE", medida:45, tipo:"moneda", alineacion:"right", datos:"tflete"},
			{nombre:"DESC", medida:45, tipo:"moneda", alineacion:"right", datos:"ttotaldescuento"},
			{nombre:"EXCEDENTE", medida:48, tipo:"moneda", alineacion:"right", datos:"texcedente"},
			{nombre:"EAD", medida:42, tipo:"moneda", alineacion:"right", datos:"tcostoead"},
			{nombre:"RECOL.", medida:51, tipo:"moneda", alineacion:"right", datos:"trecoleccion"},
			{nombre:"SEGURO", medida:51, tipo:"moneda", alineacion:"right", datos:"tseguro"},
			{nombre:"COMB", medida:51, tipo:"moneda", alineacion:"right", datos:"tcombustible"},
			{nombre:"OTROS", medida:51, tipo:"moneda", alineacion:"right", datos:"totros"},
			{nombre:"SUBTOTAL", medida:51, tipo:"moneda", alineacion:"right", datos:"subtotal"},
			{nombre:"IVA", medida:51, tipo:"moneda", alineacion:"right", datos:"tiva"},
			{nombre:"IVARET.", medida:51, tipo:"moneda", alineacion:"right", datos:"ivaretenido"},
			{nombre:"TOTAL", medida:51, tipo:"moneda", alineacion:"right", datos:"total"},
			{nombre:"T", medida:4, tipo:"oculto", alineacion:"right", datos:"tipo"}
		],
		filasInicial:7,
		alto:100,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"detalle_guias2",
		campos:[
			{nombre:"S", medida:16, alineacion:"center", tipo:"checkbox", datos:"seleccion", onClick:"calcularSeleccionY"},
			{nombre:"GUIA", medida:75, alineacion:"center" , datos:"id"},
			{nombre:"TIPOGUIA", medida:60, alineacion:"center", datos:"tipoguia"},
			{nombre:"CONCEPTO", medida:85, alineacion:"center", datos:"concepto"},
			{nombre:"SEGURO", medida:65, tipo:"moneda", alineacion:"right", datos:"tseguro"},
			{nombre:"EXCEDENTE", medida:65, tipo:"moneda", alineacion:"right", datos:"texcedente"},
			{nombre:"FECHA", medida:53, alineacion:"center", datos:"fecha"},
			{nombre:"IMPORTE", medida:60, tipo:"moneda", alineacion:"right", datos:"subtotal"},
			{nombre:"IVA", medida:50, tipo:"moneda", alineacion:"right", datos:"tiva"},
			{nombre:"IVARET.", medida:50, tipo:"moneda", alineacion:"right", datos:"ivaretenido"},
			{nombre:"TOTAL", medida:60, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:7,
		alto:100,
		seleccion:false,
		ordenable:false,
		nombrevar:"tabla2"
	});
	
	window.onload = function(){
		tabla1.create();
		tabla2.create();
		u.modificar.value='<?=$_GET[modificar] ?>';
		Inicio();
		
		<?
		$_GET[funcion] = str_replace("\'","'",$_GET[funcion]);
		if($_GET[funcion]!=""){
			echo 'setTimeout("'.$_GET[funcion].'",1500);';
		}
		?>
	}
	
	
	function preguntarImprimir(){
		<?=$cpermiso->verificarPermiso("293,322",$_SESSION[IDUSUARIO]);?>;
		abrirVentanaFija('../buscadores_generales/impresionFactura.php', 300, 230, 'ventana', 'Busqueda');
	}
	
	function imprimirFactura(){
		window.open('../fpdf/ex.php?factura='+document.all.folio.innerHTML);
	}
	
	function imprimirSoporte(){
		window.open('../clasePDF/soporteFactura.php?folio='+document.all.folio.innerHTML);
	}
	
	function Inicio(){
		if(u.modificar.value=="1"){
			u.idcliente.value='<?=$_GET[cliente] ?>';
			u.folioliquidacionmercancia.value='<?=$_GET[folio] ?>';
			pedirClienteLiquidacionmercancia(u.idcliente.value);
			u.idcliente.readOnly=true;
			u.idcliente.style.backgroundColor="#FFFF99";
		}
	}
	
	function pedirClienteLiquidacionmercancia(valor){
		limpiarTodo();
		u.idcliente.value = valor;
		consulta("mostrarClienteLiquidacionmercancia", "Facturacion_consulta.php?accion=1&idcliente="+valor+"&valrandom="+Math.random());
	}
	
	function mostrarClienteLiquidacionmercancia(datos){
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;
			u.nombre.value 				= datos.getElementsByTagName('nombre').item(0).firstChild.data;
			u.paterno.value				= datos.getElementsByTagName('paterno').item(0).firstChild.data;
			u.materno.value				= datos.getElementsByTagName('materno').item(0).firstChild.data;
			u.rfc.value					= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			
			document.all.celdacalle.innerHTML = '<input name="calle" type="text" class="Tablas" id="calle" style="width:250px;"/>';			
			if(endir==1){
				direccionesf			= datos.getElementsByTagName('direccionesf').item(0).firstChild.data;
				u.calle.value			= datos.getElementsByTagName('calle').item(0).firstChild.data;
				u.ccalles.value 		= datos.getElementsByTagName('crucecalles').item(0).firstChild.data;
				u.numero.value 			= datos.getElementsByTagName('numero').item(0).firstChild.data;
				u.cp.value 				= datos.getElementsByTagName('cp').item(0).firstChild.data;
				u.colonia.value 		= datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.poblacion.value 		= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				u.telefono.value 		= datos.getElementsByTagName('telefono').item(0).firstChild.data;
				u.fax.value 			= datos.getElementsByTagName('fax').item(0).firstChild.data;
				u.pais.value 			= datos.getElementsByTagName('pais').item(0).firstChild.data;
				u.estado.value 			= datos.getElementsByTagName('estado').item(0).firstChild.data;
				u.municipio.value		= datos.getElementsByTagName('municipio').item(0).firstChild.data;
				u.facturacion.value		= datos.getElementsByTagName('facturacion').item(0).firstChild.data;
			if(direccionesf==0)
					alerta("El Cliente no tiene direccion para facturar","¡Atencion!","idcliente");				
				cargarGuiasLiquidacionmercancia(u.idcliente.value);
			}else if(endir>1){
				direccionesf			= datos.getElementsByTagName('direccionesf').item(0).firstChild.data;
				
				var comb = "<select name='calle' style='width:250px;font:tahoma; font-size:9px' onchange='"
				+"document.all.ccalles.value=this.options[this.selectedIndex].ccalles;"
				+"document.all.numero.value=this.options[this.selectedIndex].numero;"
				+"document.all.cp.value=this.options[this.selectedIndex].cp;"
				+"document.all.poblacion.value=this.options[this.selectedIndex].poblacion;"
				+"document.all.telefono.value=this.options[this.selectedIndex].telefono;"
				+"document.all.fax.value=this.options[this.selectedIndex].fax;"
				+"document.all.pais.value=this.options[this.selectedIndex].pais;"
				+"document.all.ccalles.value=this.options[this.selectedIndex].numero;"
				+"document.all.estado.value=this.options[this.selectedIndex].estado;"
				+"document.all.municipio.value=this.options[this.selectedIndex].municipio;"
				+"document.all.facturacion.value=this.options[this.selectedIndex].facturacion;"
				+"'>";
				
				for(var i=0; i<endir; i++){
					calle			= datos.getElementsByTagName('calle').item(i).firstChild.data;
					ccalles 		= datos.getElementsByTagName('crucecalles').item(i).firstChild.data;
					numero 			= datos.getElementsByTagName('numero').item(i).firstChild.data;
					cp 				= datos.getElementsByTagName('cp').item(i).firstChild.data;
					colonia 		= datos.getElementsByTagName('colonia').item(i).firstChild.data;
					poblacion 		= datos.getElementsByTagName('poblacion').item(i).firstChild.data;
					telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;
					fax 			= datos.getElementsByTagName('fax').item(i).firstChild.data;
					pais 			= datos.getElementsByTagName('pais').item(i).firstChild.data;
					estado 			= datos.getElementsByTagName('estado').item(i).firstChild.data;
					municipio		= datos.getElementsByTagName('municipio').item(i).firstChild.data;
					facturacion		= datos.getElementsByTagName('facturacion').item(i).firstChild.data;
					if(i==0){
						u.ccalles.value 		= ccalles;
						u.numero.value 			= numero;
						u.cp.value 				= cp;
						u.colonia.value 		= colonia;
						u.poblacion.value 		= poblacion;
						u.telefono.value 		= telefono;
						u.fax.value 			= fax;
						u.pais.value 			= pais;
						u.estado.value 			= estado;
						u.municipio.value		= municipio;
						u.facturacion.value		= facturacion;
					}
					
					comb += "<option value='"+calle+"' calle='"+calle+"' numero='"+numero+"' cp='"+cp+"' colonia='"+colonia+"'"
					+"poblacion='"+poblacion+"' telefono='"+telefono+"' fax='"+fax+"' pais='"+pais+"' estado='"+estado+"' municipio='"+municipio+"' facturacion='"+facturacion+"'>"
					+calle+", "+numero+", "+colonia+"</option>";
				}
				comb += "</select>";
				document.all.celdacalle.innerHTML = comb;
				u.calle.focus();
				
				//if(direccionesf==0)
					//alerta2("El Cliente no tiene direccion para facturar","¡Atencion!","idcliente");
				
				cargarGuiasLiquidacionmercancia(u.idcliente.value);
			}else{
				//alerta("El Cliente no tiene direccion","¡Atencion!","idcliente");
			}
		}else{
			//alerta("El Cliente no existe","¡Atencion!","idcliente");
		}
	}
	
	function cargarGuiasLiquidacionmercancia(valor){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			sucursalorigen 	= '<?=$_SESSION[IDSUCURSAL]?>';
			if(document.all.tipoguias[0].checked==true){
				tipoguia = document.all.tipoguias[0].value;
			}else{
				tipoguia = document.all.tipoguias[1].value;
			}
			u.tipoguias[0].disabled = true;
			u.tipoguias[1].disabled = true;
			u.guia[0].disabled = true;
			u.guia[1].disabled = true;
			//alerta3("Facturacion_consulta.php?accion=8&folio="+u.folioliquidacionmercancia.value+"&cliente="+u.idcliente.value);
			consulta("mostrarGuias", "Facturacion_consulta.php?accion=8&folio="+u.folioliquidacionmercancia.value+"&cliente="+u.idcliente.value);
			
			//consulta("mostrarGuias2", "Facturacion_consulta.php?accion=9&folio="+u.folioliquidacionmercancia.value+"&cliente="+u.idcliente.value);
		}else{
			alerta("Seleccione una sucursal de Origen","¡Atencion!","fecha");
		}
	}
	
	function bloquear(valor){
		var u = document.all;
		
		u.idcliente.readOnly = valor;
		u.idcliente.style.backgroundColor = (valor)?"#FFFF99":"";
		u.tipoguias[0].disabled = valor;
		u.tipoguias[1].disabled = valor;
		u.chktodos.disabled = valor;
		u.cantidad.readOnly = valor;
		u.cantidad.style.backgroundColor = (valor)?"#FFFF99":"";
		u.descripcion.readOnly = valor;
		u.descripcion.style.backgroundColor = (valor)?"#FFFF99":"";
		u.importe.readOnly = valor;
		u.importe.style.backgroundColor = (valor)?"#FFFF99":"";
		u.txtbuscado.readOnly = valor;
		u.txtbuscado.style.backgroundColor = (valor)?"#FFFF99":"";
	}
	function paracelda(valor, tamano, alineacion, idcaja){
		elid = "";
		if(idcaja!=undefined){
			elid = ' name="'+idcaja+'xxNOFILAxx"';
		}
		return '<input type="text" readonly="true" style=" width:'+tamano+'px;font:tahoma; font-size:9px; text-align:'+alineacion+'; font-weight:bold; border:none; background:none" value="'+valor+'" '+elid+' />';
	}
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		if( isNaN(numcredvar(valor1.toLocaleString()).replace(/,/g,'') )){
			valor2 = "$ 0.00";
		}else{
			valor2 = "$ "+numcredvar(valor1.toLocaleString());
		}
		return valor2;
	}
	function desconvertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = valorx.toLocaleString().replace("$ ","").replace(/,/g,"");
		return valor1;
	}
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
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
	function numerosydecimal(evnt,valor){
		caja = valor;
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
				//alert("Solo Numeros, Por favor...");
				return false;
			}else{
				if(charCode==46){
					if(caja.indexOf(".")>-1){
						return false;
					}
				}
			}
			return true;
		}
	}
	//funciones para limpiar
	function limpiarSustitucion(){
		u.folioasustituir.value = "0";
		u.textosustitucion.innerHTML= "";
	}
	function limpiarCliente(){
		with(document.all){
			idcliente.value = "";
			nombre.value = "";
			paterno.value = "";
			materno.value = "";
			calle.value = "";
			numero.value = "";
			cp.value = "";
			colonia.value = "";
			ccalles.value = "";
			poblacion.value = "";
			municipio.value = "";
			estado.value = "";
			pais.value = "";
			telefono.value = "";
			fax.value = "";
			rfc.value = "";
		}
		document.getElementById('facturacancelada').style.display='none';
	}
	function limpiarGuias(){
		tabla1.clear();
		tabla2.clear();
		document.all.valorcontado.value = "0";
		document.all.solicitudesdecontado.value = "0";
		document.all.excedentesdecontado.value = "0";
	}
	function limpiarOtros(){
		u = document.all;
		u.cantidad.value 			= "";
		u.descripcion.value			= "";
		u.importe.value				= "";
		u.subtotalotros.value		= "";
		u.ivaotros.value			= "";
		u.ivarotros.value			= "";
		u.montootros.value			= "";
		u.guiase.value				= "";
		u.guiasn.value				= "";
		u.tflete.value 				= "";
		u.ttotaldescuento.value		= "";
		u.texcedente.value			= "";
		u.tead.value 				= "";
		u.trecoleccion.value		= "";
		u.tseguro.value 			= "";
		u.tcombustible.value		= "";
		u.totros.value 				= "";
		u.tsubtotal.value			= "";
		u.tiva.value 				= "";
		u.tivar.value				= "";
		u.ttotal.value 				= "";
	}
	
	function limpiarSobre(){
		u = document.all;
		
		u.sseguro.value 		= "";
		//u.sexcedente.value 		= "";
		u.ssubtotal.value 		= "";
		u.siva.value 			= "";
		u.sivar.value 			= "";
		u.smonto.value 			= "";
	}
	
	function limpiarTodo(){
		document.all.valorcontado.value = "0";
		document.all.solicitudesdecontado.value = "0";
		document.all.excedentesdecontado.value = "0";
		document.getElementById('estadofactura').innerHTML = "";
		document.all.sepuede.value = "";
		document.getElementById('fechapago').innerHTML = "";
		document.getElementById('fechacancelacion').innerHTML = "";
		document.all.valorcontado.value = "";
		document.all.conformapago.value = "";
		document.all.estadocobranza.value = "";
		document.all.personamoral.value = "";
		document.all.activado.value = "";
		document.all.creditodisponible.value = "";
		document.all.solicitudesdecontado.value="0";
        document.all.excedentesdecontado.value="0";
		document.getElementById('lasucursal').innerHTML = "<?=$nsucursal?>";
		limpiarCliente();
		limpiarGuias();
		limpiarSobre();
		limpiarOtros();
		limpiarSustitucion();
		document.all.bonotesAccion.innerHTML = botonesnuevo;
		document.all.cantidadregistros.value=0;
	}
	
	function limpiarCambioClientes(){
		document.all.valorcontado.value = "";
		document.all.conformapago.value = "";
		limpiarGuias();
		limpiarSobre();
		limpiarOtros();
	}
	
	function pedirFolio(){
		consulta("mostrarFolio", "Facturacion_consulta.php?accion=5&ran="+Math.random());
	}
	
	//funciones para ajax
	function mostrarFolio(datos){
		maximo = datos.getElementsByTagName('maximo').item(0).firstChild.data;
		fecha  = datos.getElementsByTagName('fecha').item(0).firstChild.data;
		document.all.folio.innerHTML = maximo;
		document.all.fecha.value = fecha;
	}
	
	function pedirCliente(valor){
		limpiarTodo();
		document.all.idcliente.value = valor;
		consulta("mostrarCliente", "Facturacion_consulta.php?accion=1&idcliente="+valor+"&valrandom="+Math.random());
	}
	
	function mostrarCliente(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var endir = datos.getElementsByTagName('encontrodirecciones').item(0).firstChild.data;
			u.nombre.value 				= datos.getElementsByTagName('nombre').item(0).firstChild.data;
			u.paterno.value				= datos.getElementsByTagName('paterno').item(0).firstChild.data;
			u.materno.value				= datos.getElementsByTagName('materno').item(0).firstChild.data;
			u.rfc.value					= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			u.personamoral.value		= datos.getElementsByTagName('personamoral').item(0).firstChild.data;
			u.creditodisponible.value	= datos.getElementsByTagName('disponible').item(0).firstChild.data;
			u.activado.value			= datos.getElementsByTagName('creditoactivado').item(0).firstChild.data;
			if(condicionpuesta==false && '<?=$_GET[condicionpago]?>'!=""){
				var indice = '<?=$_GET[condicionpago]?>';
				if(indice=='0'){
					document.all.tipoguias[1].checked = true;
				}else{
					document.all.tipoguias[0].checked = true;
				}
			}else{
				if(datos.getElementsByTagName('foliocredito').item(0).firstChild.data=="NO"){
					document.all.tipoguias[1].checked = true;
				}else{
					document.all.tipoguias[0].checked = true;
				}
			}
			
			document.all.celdacalle.innerHTML = '<input name="calle" type="text" class="Tablas" id="calle" style="width:250px;" />';			
			if(endir==1){			
				direccionesf			= datos.getElementsByTagName('direccionesf').item(0).firstChild.data;
				u.calle.value			= datos.getElementsByTagName('calle').item(0).firstChild.data;
				u.ccalles.value 		= datos.getElementsByTagName('crucecalles').item(0).firstChild.data;
				u.numero.value 			= datos.getElementsByTagName('numero').item(0).firstChild.data;
				u.cp.value 				= datos.getElementsByTagName('cp').item(0).firstChild.data;
				u.colonia.value 		= datos.getElementsByTagName('colonia').item(0).firstChild.data;
				u.poblacion.value 		= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
				u.telefono.value 		= datos.getElementsByTagName('telefono').item(0).firstChild.data;
				u.fax.value 			= datos.getElementsByTagName('fax').item(0).firstChild.data;
				u.pais.value 			= datos.getElementsByTagName('pais').item(0).firstChild.data;
				u.estado.value 			= datos.getElementsByTagName('estado').item(0).firstChild.data;
				u.municipio.value		= datos.getElementsByTagName('municipio').item(0).firstChild.data;
				u.facturacion.value		= datos.getElementsByTagName('facturacion').item(0).firstChild.data;
			if(direccionesf==0)
					alerta("El Cliente no tiene direccion para facturar","¡Atencion!","idcliente");				
				cargarGuias(document.all.idcliente.value);
			}else if(endir>1){
				direccionesf			= datos.getElementsByTagName('direccionesf').item(0).firstChild.data;
				
				var comb = "<select name='calle' style='width:250px;font:tahoma; font-size:9px' onchange='"
				+"document.all.ccalles.value=this.options[this.selectedIndex].ccalles;"
				+"document.all.numero.value=this.options[this.selectedIndex].numero;"
				+"document.all.cp.value=this.options[this.selectedIndex].cp;"
				+"document.all.poblacion.value=this.options[this.selectedIndex].poblacion;"
				+"document.all.telefono.value=this.options[this.selectedIndex].telefono;"
				+"document.all.fax.value=this.options[this.selectedIndex].fax;"
				+"document.all.pais.value=this.options[this.selectedIndex].pais;"
				+"document.all.ccalles.value=this.options[this.selectedIndex].numero;"
				+"document.all.estado.value=this.options[this.selectedIndex].estado;"
				+"document.all.municipio.value=this.options[this.selectedIndex].municipio;"
				+"document.all.facturacion.value=this.options[this.selectedIndex].facturacion;"
				+"'>";
				
				for(var i=0; i<endir; i++){
					calle			= datos.getElementsByTagName('calle').item(i).firstChild.data;
					ccalles 		= datos.getElementsByTagName('crucecalles').item(i).firstChild.data;
					numero 			= datos.getElementsByTagName('numero').item(i).firstChild.data;
					cp 				= datos.getElementsByTagName('cp').item(i).firstChild.data;
					colonia 		= datos.getElementsByTagName('colonia').item(i).firstChild.data;
					poblacion 		= datos.getElementsByTagName('poblacion').item(i).firstChild.data;
					telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;
					fax 			= datos.getElementsByTagName('fax').item(i).firstChild.data;
					pais 			= datos.getElementsByTagName('pais').item(i).firstChild.data;
					estado 			= datos.getElementsByTagName('estado').item(i).firstChild.data;
					municipio		= datos.getElementsByTagName('municipio').item(i).firstChild.data;
					facturacion		= datos.getElementsByTagName('facturacion').item(i).firstChild.data;
					if(i==0){
						u.ccalles.value 		= ccalles;
						u.numero.value 			= numero;
						u.cp.value 				= cp;
						u.colonia.value 		= colonia;
						u.poblacion.value 		= poblacion;
						u.telefono.value 		= telefono;
						u.fax.value 			= fax;
						u.pais.value 			= pais;
						u.estado.value 			= estado;
						u.municipio.value		= municipio;
						u.facturacion.value		= facturacion;
					}
					
					comb += "<option value='"+calle+"' calle='"+calle+"' numero='"+numero+"' cp='"+cp+"' colonia='"+colonia+"'"
					+"poblacion='"+poblacion+"' telefono='"+telefono+"' fax='"+fax+"' pais='"+pais+"' estado='"+estado+"' municipio='"+municipio+"' facturacion='"+facturacion+"'>"
					+calle+", "+numero+", "+colonia+"</option>";
				}
				comb += "</select>";
				document.all.celdacalle.innerHTML = comb;
				u.calle.focus();
				
				if(direccionesf==0)
					alerta2("El Cliente no tiene direccion para facturar","¡Atencion!","idcliente");
				
				cargarGuias(document.all.idcliente.value);
			}else{
				alerta("El Cliente no tiene direccion","¡Atencion!","idcliente");
			}
		}else{
			alerta("El Cliente no existe","¡Atencion!","idcliente");
		}
	}
	
	function cargarGuias(valor){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			sucursalorigen 	= '<?=$_SESSION[IDSUCURSAL]?>';
			if(document.all.tipoguias[0].checked==true){
				tipoguia = document.all.tipoguias[0].value;
			}else{
				tipoguia = document.all.tipoguias[1].value;
			}
			var guia = ((document.all.guia[0].checked == true)?"vent":"emp");
			consulta("mostrarGuias", "Facturacion_consulta.php?accion=2&idcliente="+valor
			+"&tipoguia="+tipoguia
			+"&sucorigen="+sucursalorigen+"&guia="+guia
			+"&valrandom="+Math.random());
			
			if(document.all.guia[1].checked == true){
				consulta("mostrarGuias2", "Facturacion_consulta.php?accion=7&idcliente="+valor
				+"&tipoguia="+tipoguia
				+"&sucorigen="+sucursalorigen+"&guia="+guia
				+"&valrandom="+Math.random());
			}
		}else{
			alerta("Seleccione una sucursal de Origen","¡Atencion!","fecha");
		}
	}
	
	function mostrarGuias(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('id').length;
		if(encon>0){
			var tflete 			= 0;
			var ttotaldescuento = 0;
			var texcedente 		= 0;
			var tead 			= 0;
			var trecoleccion	= 0;
			var tseguro 		= 0;
			var tcomb 			= 0;
			var totros 			= 0;
			var tsubtotal		= 0;
			var tiva 			= 0;
			var tivaret 		= 0;
			var ttotal 			= 0;
			var guiase			= 0;
			var guiasn			= 0;
			u.cantidadregistros.value = encon;
			var obj = new Object();
			//alert(datos.getElementsByTagName('tflete').item(0).firstChild.data);
			//for(var i=0; i<encon; i++){
				
			//}
			tabla1.setXML(datos);
			var decontado = 0;
			var utotal = 0;
			for(m=0; m<encon; m++){	
				tipoguia = datos.getElementsByTagName('tipoguia').item(m).firstChild.data;
				
				if(tipoguia=="NORMAL"){
					guiasn += 1;
				}else{
					guiase += 1;
				}
				if(tipoguia=="PREPAGADA" || tipoguia=="CONSIGNACION"){
					utotal = datos.getElementsByTagName('total').item(m).firstChild.data;
					decontado += parseFloat(utotal);
				}
			}
			u.solicitudesdecontado.value = decontado;
			u.guiase.value = guiase;
			u.guiasn.value = guiasn;
			if(condicionpuesta==false && '<?=$_GET[condicionpago]?>'!=""){
				condicionpuesta = true;
				seleccionarUnaGuia('<?=$_GET[folio]?>');	
			}else{
				seleccionarGuias(true);
			}
		}else{
			alerta("El Cliente no tiene guias " + ((u.tipoguias[0].checked)?"credito":"contado") + " para facturar","¡Atencion!","idcliente")
		}
	}
	function mostrarGuias2(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('id').length;
		if(encon>0){
			u.cantidadregistros.value = encon;
			tabla2.setXML(datos);
			
			seleccionarGuias2(true);
		}else{
			alerta("El Cliente no tiene guias Valor Declarado de " + ((u.tipoguias[0].checked)?"credito":"contado") + " para facturar","¡Atencion!","idcliente")
		}
	}
	
	function guardarFacturar(){
		var u = document.all;
		
		var valorcontado = parseFloat( ((u.solicitudesdecontado.value=='')?'0':u.solicitudesdecontado.value).replace("$ ","").replace(/,/g,"") )+
			parseFloat( ((u.montootros.value=='')?'0':u.montootros.value).replace("$ ","").replace(/,/g,"") )+
			parseFloat( ((u.excedentesdecontado.value=='')?'0':u.excedentesdecontado.value).replace("$ ","").replace(/,/g,"") );
		u.valorcontado.value = valorcontado;
		
		if(u.tipoguias[0].checked==true && u.activado.value=='NO'){
			alerta3("El credito del cliente esta desactivado");
			return false;
		}else if(u.tipoguias[0].checked==true && u.activado.value=='SI'){
			if(valorcontado>parseFloat(u.creditodisponible.value)){
				alerta3("Crédito insuficiente","¡ATENCION!");
				return false;
			}
		}
		
		if(u.tipoguias[1].checked==true && u.conformapago.value=="" && (u.valorcontado.value!=""  || u.montootros.value!="" || u.smonto.value!="")){
			if(valorcontado!=0){
				abrirVentanaFija('formapago_facturacion.php?total='+valorcontado.toFixed(2)+
								 '&cliente='+u.idcliente.value, 600, 400, 'ventana', 'Forma de Pago');
				return false;
			}
		}
		
		
		var cliente 					= u.idcliente.value;
		var nombrecliente 				= u.nombre.value;
		var sustitutode					= u.folioasustituir.value;
		var apellidopaternocliente 		= u.paterno.value;
		var apellidomaternocliente 		= u.materno.value;
		var rfc							= u.rfc.value;
		var calle 						= u.calle.value;
		var numero 						= u.numero.value;
		var codigopostal 				= u.cp.value;
		var colonia 					= u.colonia.value;
		var crucecalles 				= u.ccalles.value;
		var poblacion 					= u.poblacion.value;
		var municipio 					= u.municipio.value;
		var estado 						= u.estado.value;
		var pais 						= u.pais.value;
		var telefono 					= u.telefono.value;
		var fax 						= u.fax.value;
		var guiasempresa 				= u.guiase.value;
		var guiasnormales 				= u.guiasn.value;
		var flete 						= desconvertirMoneda(u.tflete.value);
		var totaldescuento				= desconvertirMoneda(u.ttotaldescuento.value);
		var excedente 					= desconvertirMoneda(u.texcedente.value);
		var ead 						= desconvertirMoneda(u.tead.value);
		var recoleccion 				= desconvertirMoneda(u.trecoleccion.value);
		var seguro 						= desconvertirMoneda(u.tseguro.value);
		var combustible 				= desconvertirMoneda(u.tcombustible.value);
		var otros 						= desconvertirMoneda(u.totros.value);
		var subtotal 					= desconvertirMoneda(u.tsubtotal.value);
		var iva 						= desconvertirMoneda(u.tiva.value);
		var ivaretenido 				= desconvertirMoneda(u.tivar.value);
		var total 						= desconvertirMoneda(u.ttotal.value);
		var sobseguro 					= desconvertirMoneda(u.sseguro.value);
		var credito						= (u.tipoguias[0].checked==true)?'SI':'NO';
		//sobexcedente 				= desconvertirMoneda(u.sexcedente.value);
		var sobsubtotal 				= desconvertirMoneda(u.ssubtotal.value);
		var sobiva 						= desconvertirMoneda(u.siva.value);
		var sobivaretenido 				= desconvertirMoneda(u.sivar.value);
		var sobmontoafacturar 			= desconvertirMoneda(u.smonto.value);
		var otroscantidad 				= (u.cantidad.value=="")?"0":u.cantidad.value;
		var otrosdescripcion 			= u.descripcion.value;
		var otrosimporte 				= desconvertirMoneda(u.importe.value);
		var otrossubtotal 				= desconvertirMoneda(u.subtotalotros.value);
		var otrosiva 					= desconvertirMoneda(u.ivaotros.value);
		var otrosivaretenido 			= desconvertirMoneda(u.ivarotros.value);
		var otrosmontofacturar 			= desconvertirMoneda(u.montootros.value);
		
		var folio 	= "";
		var foliotipo = "";
		var folio2	= "";
		var cantreg	= u.cantidadregistros.value;
		
		//u.descripcion.value = u.contenidoregistro.innerHTML;
		
		if(tabla1.getRecordCount()>0){
			folio = tabla1.getValSelFromField("id","S");
			foliotipo = tabla1.getValSelFromField("tipo","S");
		}else{
			folio = "";
			foliotipo = "";
		}
		
		if(tabla2.getRecordCount()>0){
			folio2 = tabla2.getValSelFromField("id","S");
		}else{
			folio2 = "";
		}
		
		var efectivo				= u.efectivo.value.replace("$ ","").replace(/,/g,"");
		var cheque					= u.cheque.value.replace("$ ","").replace(/,/g,"");
		var banco					= u.banco.value.replace("$ ","").replace(/,/g,"");
		var ncheque					= u.ncheque.value;
		var tarjeta					= u.tarjeta.value.replace("$ ","").replace(/,/g,"");
		var nc 						= u.nc.value.replace("$ ","").replace(/,/g,"");
		var nc_folio				= u.nc_folio.value.replace("$ ","").replace(/,/g,"");
		var trasferencia			= u.transferencia.value.replace("$ ","").replace(/,/g,"");
		
		var ladata = enviardatosFacturacionElectronica();
		
		consulta("respuestaGuardar","Facturacion_consulta.php?accion=3&nombrecliente=" + nombrecliente + 
		"&cliente=" + cliente +
		"&apellidopaternocliente=" + apellidopaternocliente + 
		"&apellidomaternocliente=" + apellidomaternocliente + 
		"&sustitutode=" + sustitutode + 
		"&rfc=" + rfc + 
		"&calle=" + calle + 
		"&numero=" + numero + 
		"&codigopostal=" + codigopostal + 
		"&colonia=" + colonia + 
		"&crucecalles=" + crucecalles + 
		"&poblacion=" + poblacion + 
		"&municipio=" + municipio + 
		"&credito=" + credito +
		"&estado=" + estado + 
		"&pais=" + pais + 
		"&telefono=" + telefono + 
		"&fax=" + fax + 
		"&guiasempresa=" + guiasempresa + 
		"&guiasnormales=" + guiasnormales + 
		"&flete=" + flete + 
		"&totaldescuento=" + totaldescuento +
		"&excedente=" + excedente + 
		"&ead=" + ead + 
		"&recoleccion=" + recoleccion + 
		"&seguro=" + seguro + 
		"&combustible=" + combustible + 
		"&otros=" + otros + 
		"&subtotal=" + subtotal + 
		"&iva=" + iva + 
		"&ivaretenido=" + ivaretenido + 
		"&total=" + total + 
		"&sobseguro=" + sobseguro + 
		//"&sobexcedente=" + sobexcedente + 
		"&sobsubtotal=" + sobsubtotal + 
		"&sobiva=" + sobiva + 
		"&sobivaretenido=" + sobivaretenido + 
		"&sobmontoafacturar=" + sobmontoafacturar + 
		"&otroscantidad=" + otroscantidad + 
		"&otrosdescripcion=" + otrosdescripcion + 
		"&otrosimporte=" + otrosimporte + 
		"&otrossubtotal=" +otrossubtotal + 
		"&otrosiva=" + otrosiva + 
		"&otrosivaretenido=" + otrosivaretenido + 
		"&otrosmontofacturar=" + otrosmontofacturar+
		"&foliosguias=" + folio +
		"&foliosguias2=" + folio2 +
		"&foliotipo=" + foliotipo +
		"&valorcontado=" + ((valorcontado=="")?0:valorcontado) +
		"&efectivo=" + ((efectivo=="")?0:efectivo) +
		"&cheque=" + ((cheque=="")?0:cheque) +
		"&banco=" + ((banco=="")?0:banco) +
		"&ncheque=" + ((ncheque=="")?0:ncheque) +
		"&tarjeta=" + ((tarjeta=="")?0:tarjeta) +
		"&nc=" + ((nc=="")?0:nc) +
		"&nc_folio=" + ((nc_folio=="")?0:nc_folio) +
		"&trasferencia=" + ((trasferencia=="")?0:trasferencia) +
		"&data="+ladata+
		"&tipoguiac="+((document.all.guia[0].checked==true)?'ventanilla':'empresarial')+
		"&ranms="+Math.random());
		
		if (u.modificar.value!="1"){
			document.all.bonotesAccion.innerHTML = botonescargar;
		}
			
	}
	
	
	
	function respuestaGuardar(datos){
		guardado = datos.getElementsByTagName('guardado').item(0).firstChild.data;
		if(guardado==1){
			
			foliofactura = datos.getElementsByTagName('foliofactura').item(0).firstChild.data;
			document.all.folio.innerHTML = foliofactura;
			info("Se ha guardado la factura","¡Atencion!");
			if(document.all.tipoguias[0].checked==true){
				u.estadofactura.innerHTML = "GUARDADO";
			}else{
				u.estadofactura.innerHTML = "PAGADA";
				u.fechapago.value = datos.getElementsByTagName('fechapago').item(0).firstChild.data;
			}
			if (u.modificar.value=="1"){
				parent.mostrarGuiaArreglo(foliofactura);	
			}
		}else{
			consulta = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al guardar "+consulta,"¡Atencion!","folio");
		}
	}
	
	function cancelarFactura(){
		/*<?=$cpermiso->verificarPermiso("292,321",$_SESSION[IDUSUARIO]);?>;
		var esventanilla=false;
		if(tabla1.getRecordCount()>0 && tabla2.getRecordCount()<1 && u.montootros.value=="$ 0.00")
			esventanilla = true;
		
		if(u.estadocobranza.value == "C" && u.fechapago.innerHTML!='<?=date('d/m/Y')?>' && esventanilla==false){
			alerta3("No puede cancelar una factura que ya esta pagada","¡ATENCION!");
			return false;
		}
			
		if(u.sepuede.value=='NO'){
			alerta3("No puede cancelar una factura de cargo EAD si la guía no esta en estado ALMACEN DESTINO","¡ATENCION!");
			return false;
		}*/
		var f1 = u.fecha.value.split("/");
		var f2 = u.confirFecha.value.split("/");
		
		if(f1[0].substr(0,1)=="0"){
			f1[0] = f1[0].substr(1,1);
		}
		if(f1[1].substr(0,1)=="0"){
			f1[1] = f1[1].substr(1,1);
		}
		
		if(f2[0].substr(0,1)=="0"){
			f2[0] = f2[0].substr(1,1);
		}
		if(f2[1].substr(0,1)=="0"){
			f2[1] = f2[1].substr(1,1);
		}
		
		var initDate = new Date(f1[2],f1[1],f1[0]);
		var endDate = new Date(f2[2],f2[1],f2[0]);
		
		/*if(u.tipoguias[1].checked==true && initDate < endDate){
			alerta3("Solo se pueden cancelar Facturas de contado del dia actual","¡Atención!");
			return false;
		}*/
		
		u = document.all;
		consulta("respuestaCancelar","Facturacion_consulta.php?accion=4&foliofactura="+u.folio.innerHTML);
	}
	
	function respuestaCancelar(datos){
		cancelada = datos.getElementsByTagName('cancelada').item(0).firstChild.data;
		if(cancelada==1){
			alerta("Se ha Cancelado la factura","¡Atencion!","folio");
			document.getElementById('facturacancelada').style.display='';
			u.estadofactura.innerHTML = "CANCELADO";
		}else{
			consulta = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al cancelar "+consulta,"¡Atencion!","folio");
		}
		document.all.bonotesAccion.innerHTML = botonescancelado;
	}
	
	function cargarFactura(folio){
		u = document.all;
		consulta("mostrarFactura","Facturacion_consulta.php?accion=6&folio="+folio+"&ran="+Math.random());
	}
	function mostrarFactura(datos){
		var u = document.all;
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		
		bloquear(true);
		limpiarTodo();
		if(encon>0){
			var folio				= datos.getElementsByTagName('folio').item(0).firstChild.data;
			var facturaestado 		= datos.getElementsByTagName('facturaestado').item(0).firstChild.data;
			var fechacancelacion	= datos.getElementsByTagName('fechacancelacion').item(0).firstChild.data;
			var sepuede 			= datos.getElementsByTagName('sepuede').item(0).firstChild.data;
			var fechapago			= datos.getElementsByTagName('fechapago').item(0).firstChild.data;
			var nsucursal			= datos.getElementsByTagName('nsucursal').item(0).firstChild.data;
			var cliente 			= datos.getElementsByTagName('cliente').item(0).firstChild.data;
			var nombrecliente 		= datos.getElementsByTagName('nombrecliente').item(0).firstChild.data;
			var credito		 		= datos.getElementsByTagName('credito').item(0).firstChild.data;
			var apepat 				= datos.getElementsByTagName('apepat').item(0).firstChild.data;
			var apemat 				= datos.getElementsByTagName('apemat').item(0).firstChild.data;
			var sustituto			= datos.getElementsByTagName('sustitucion').item(0).firstChild.data;
			var rfc 				= datos.getElementsByTagName('rfc').item(0).firstChild.data;
			var calle 				= datos.getElementsByTagName('calle').item(0).firstChild.data;
			var numero 				= datos.getElementsByTagName('numero').item(0).firstChild.data;
			var codigopostal 		= datos.getElementsByTagName('codigopostal').item(0).firstChild.data;
			var colonia 			= datos.getElementsByTagName('colonia').item(0).firstChild.data;
			var crucecalles			= datos.getElementsByTagName('crucecalles').item(0).firstChild.data;
			var poblacion 			= datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			var municipio 			= datos.getElementsByTagName('municipio').item(0).firstChild.data;
			var estado 				= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var pais 				= datos.getElementsByTagName('pais').item(0).firstChild.data;
			var telefono 			= datos.getElementsByTagName('telefono').item(0).firstChild.data;
			var fax 				= datos.getElementsByTagName('fax').item(0).firstChild.data;
			var guiasempresa 		= datos.getElementsByTagName('guiasempresa').item(0).firstChild.data;
			var guiasnormales 		= datos.getElementsByTagName('guiasnormales').item(0).firstChild.data;
			var flete 				= datos.getElementsByTagName('flete').item(0).firstChild.data;
			var totaldescuento		= datos.getElementsByTagName('totaldescuento').item(0).firstChild.data;
			var excedente 			= datos.getElementsByTagName('excedente').item(0).firstChild.data;
			var ead 				= datos.getElementsByTagName('ead').item(0).firstChild.data;
			var recoleccion 		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;
			var seguro 				= datos.getElementsByTagName('seguro').item(0).firstChild.data;
			var estadocobranza		= datos.getElementsByTagName('estadocobranza').item(0).firstChild.data;
			var combustible 		= datos.getElementsByTagName('combustible').item(0).firstChild.data;
			var otros				= datos.getElementsByTagName('otros').item(0).firstChild.data;
			var subtotal 			= datos.getElementsByTagName('subtotal').item(0).firstChild.data;
			var iva 				= datos.getElementsByTagName('iva').item(0).firstChild.data;
			var ivaretenido 		= datos.getElementsByTagName('ivaretenido').item(0).firstChild.data;
			var total 				= datos.getElementsByTagName('total').item(0).firstChild.data;
			var sobseguro 			= datos.getElementsByTagName('sobseguro').item(0).firstChild.data;
			var sobexcedente		= datos.getElementsByTagName('sobexcedente').item(0).firstChild.data;
			var sobsubtotal 		= datos.getElementsByTagName('sobsubtotal').item(0).firstChild.data;
			var sobivaretenido 		= datos.getElementsByTagName('sobivaretenido').item(0).firstChild.data;
			var sobiva 				= datos.getElementsByTagName('sobiva').item(0).firstChild.data;
			var sobmontoafacturar 	= datos.getElementsByTagName('sobmontoafacturar').item(0).firstChild.data;
			var otroscantidad 		= datos.getElementsByTagName('otroscantidad').item(0).firstChild.data;
			var otrosdescripcion 	= datos.getElementsByTagName('otrosdescripcion').item(0).firstChild.data;
			var otrosimporte 		= datos.getElementsByTagName('otrosimporte').item(0).firstChild.data;
			var otrossubtotal 		= datos.getElementsByTagName('otrossubtotal').item(0).firstChild.data;
			var otrosiva 			= datos.getElementsByTagName('otrosiva').item(0).firstChild.data;
			var otrosivaretenido 	= datos.getElementsByTagName('otrosivaretenido').item(0).firstChild.data;
			var otrosmontofacturar 	= datos.getElementsByTagName('otrosmontofacturar').item(0).firstChild.data;
			var fecha 				= datos.getElementsByTagName('fecha').item(0).firstChild.data;
			
			if(sustituto>0){
				u.textosustitucion.innerHTML = "Esta factura sustituye a la factura no. "+sustituto;
			}
			
			if(credito=='SI'){
				u.tipoguias[0].checked=true;
			}else{
				u.tipoguias[1].checked=true;
			}
			
			u.folio.innerHTML 			= folio;
			u.estadofactura.innerHTML	= facturaestado;
			u.fecha.value 				= fecha;
			document.getElementById('lasucursal').innerHTML = nsucursal;
			document.getElementById('fechapago').innerHTML = fechapago;
			document.getElementById('fechacancelacion').innerHTML = fechacancelacion;
			u.sepuede.value			= sepuede;
			u.idcliente.value 		= cliente;
			u.nombre.value 			= nombrecliente;
			u.paterno.value			= apepat;
			u.materno.value			= apemat;
			u.calle.value 			= calle;
			u.numero.value 			= numero;
			u.cp.value 				= codigopostal;
			u.colonia.value 		= colonia;
			u.ccalles.value 		= crucecalles;
			u.poblacion.value		= poblacion;
			u.municipio.value		= municipio;
			u.estado.value 			= estado;
			u.pais.value 			= pais;
			u.telefono.value 		= telefono;
			u.fax.value 			= fax;
			u.rfc.value 			= rfc;
			u.guiase.value 			= guiasempresa;
			u.guiasn.value 			= guiasnormales;
			u.tflete.value 			= convertirMoneda(flete);
			u.ttotaldescuento.value = convertirMoneda(totaldescuento);
			u.texcedente.value 		= convertirMoneda(excedente);
			u.tead.value 			= convertirMoneda(ead);
			u.trecoleccion.value	= convertirMoneda(recoleccion);
			u.tseguro.value 		= convertirMoneda(seguro);
			u.tcombustible.value	= convertirMoneda(combustible);
			u.totros.value 			= convertirMoneda(otros);
			u.tsubtotal.value 		= convertirMoneda(subtotal);
			u.tiva.value 			= convertirMoneda(iva);
			u.tivar.value 			= convertirMoneda(ivaretenido);
			u.ttotal.value 			= convertirMoneda(total);
			u.sseguro.value 		= convertirMoneda(sobseguro);
			u.estadocobranza.value  = estadocobranza;
			//u.sexcedente.value 		= convertirMoneda(sobexcedente);
			u.ssubtotal.value 		= convertirMoneda(sobsubtotal);
			u.siva.value 			= convertirMoneda(sobiva);
			u.sivar.value 			= convertirMoneda(sobivaretenido);
			u.smonto.value 			= convertirMoneda(sobmontoafacturar);
			u.cantidad.value 		= otroscantidad;
			u.descripcion.value		= otrosdescripcion;
			u.importe.value 		= convertirMoneda(otrosimporte);
			u.subtotalotros.value 	= convertirMoneda(otrossubtotal);
			u.ivaotros.value 		= convertirMoneda(otrosiva);
			u.ivarotros.value 		= convertirMoneda(otrosivaretenido);
			u.montootros.value 		= convertirMoneda(otrosmontofacturar);
			
			if(facturaestado=="GUARDADO"){
				if (u.modificar.value!="1"){
					document.all.bonotesAccion.innerHTML = botonescargar;
				}
				//document.all.bonotesAccion.innerHTML = botonescargar;
			}else if(facturaestado=="CANCELADO"){
				document.all.bonotesAccion.innerHTML = botonescancelado;
				document.getElementById('facturacancelada').style.display='';
			}
			if(estadocobranza=="C" && facturaestado=="GUARDADO"){
				u.estadofactura.innerHTML	= "PAGADA";
			}
			
			
			/*
			
			var guiasencontradas	= datos.getElementsByTagName('guiasencontradas').item(0).firstChild.data;
			
			if(guiasencontradas>0){
				var tflete 			= 0;
				var texcedente 		= 0;
				var tead 			= 0;
				var trecoleccion	= 0;
				var tseguro 		= 0;
				var tcomb 			= 0;
				var totros 			= 0;
				var tsubtotal		= 0;
				var tiva 			= 0;
				var tivaret 		= 0;
				var ttotal 			= 0;
				var guiase			= 0;
				var guiasn			= 0;
				u.cantidadregistros.value = encon;
				tabla1.setXML(datos);
				for(m=0; m<guiasencontradas; m++){
					tipoguia	= datos.getElementsByTagName('tipoguia').item(m).firstChild.data;
					fecha		= datos.getElementsByTagName('fecha').item(m).firstChild.data;
					flete		= datos.getElementsByTagName('tflete').item(m).firstChild.data;
					excedente	= datos.getElementsByTagName('texcedente').item(m).firstChild.data;
					ead			= datos.getElementsByTagName('tcostoead').item(m).firstChild.data;
					recoleccion	= datos.getElementsByTagName('trecoleccion').item(m).firstChild.data;
					seguro		= datos.getElementsByTagName('tseguro').item(m).firstChild.data;
					comb		= datos.getElementsByTagName('tcombustible').item(m).firstChild.data;
					otros		= datos.getElementsByTagName('totros').item(m).firstChild.data;
					subtotal	= datos.getElementsByTagName('subtotal').item(m).firstChild.data;
					iva			= datos.getElementsByTagName('tiva').item(m).firstChild.data;
					ivaret		= datos.getElementsByTagName('ivaretenido').item(m).firstChild.data;
					total		= datos.getElementsByTagName('total').item(m).firstChild.data;
					
					if(tipoguia=="NORMAL"){
						guiasn += 1;
					}else{
						guiase += 1;
					}
					
					tflete 			+= parseFloat(flete);
					texcedente 		+= parseFloat(excedente);
					tead 			+= parseFloat(ead);
					trecoleccion	+= parseFloat(recoleccion);
					tseguro 		+= parseFloat(seguro);
					tcomb 			+= parseFloat(comb);
					totros 			+= parseFloat(otros);
					tsubtotal		+= parseFloat(subtotal);
					tiva 			+= parseFloat(iva);
					tivaret 		+= parseFloat(ivaret);
					ttotal 			+= parseFloat(total);
				}
				
				u.tflete.value			= convertirMoneda(tflete);
				u.texcedente.value 		= convertirMoneda(texcedente);
				u.tead.value 			= convertirMoneda(tead);
				u.trecoleccion.value	= convertirMoneda(trecoleccion);
				u.tseguro.value 		= convertirMoneda(tseguro);
				u.tcombustible.value 	= convertirMoneda(tcomb);
				u.totros.value 			= convertirMoneda(totros);
				u.tsubtotal.value		= convertirMoneda(tsubtotal);
				u.tiva.value 			= convertirMoneda(tiva);
				u.tivar.value 			= convertirMoneda(tivaret);
				u.ttotal.value 			= convertirMoneda(ttotal);
				u.guiase.value			= guiase;
				u.guiasn.value			= guiasn;
			}
			*/
			
			consultaTexto("mostrarDetalles","factura_con.php?accion=1&factura="+folio
			+"&valrandom="+Math.random());
			
			//consulta("mostrarGuias2", "Facturacion_consulta.php?accion=11&idfactura="+folio+"&valrandom="+Math.random());
		}else{
			alerta("No se encontro la factura","¡Atencion!","folio");
			
			document.all.bonotesAccion.innerHTML = botonesnuevo;
		}
	}
	
	function mostrarDetalles(datos){
		var obj = eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj.detalle);
		tabla2.setJsonData(obj.detalle2);
	}
	
	function sustituirFactura(){
		<?=$cpermiso->verificarPermiso("391,392",$_SESSION[IDUSUARIO]);?>;
		var u = document.all;
		u.folioasustituir.value = u.folio.innerHTML;
		u.textosustitucion.innerHTML = "Esta factura sustituye a la factura no. "+u.folio.innerHTML;
		u.estadofactura.innerHTML = "";
		u.bonotesAccion.innerHTML = botonesnuevo;
		pedirFolio();
		limpiarGuias();
		cargarGuias(u.idcliente.value);
		
	}
	
	function seleccionarUnaGuia(valor){
		var u = document.all;
		var cantreg	= u.cantidadregistros.value;
		
		
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(u["detalle_guias1_FOLIO"][i].value == valor){
				u["detalle_guias1_S"][i].checked = true;
				calcularSeleccionX();
				return true;
			}
		}
		/*for(var i=1;i<=cantreg;i++){
			if(document.all["checar_"+i].value == valor){
				document.all["checar_"+i].checked = true;
				calcularSeleccion(i,valor);
				return true;
			}
		}*/
		alerta("No se encontro el numero de guia", "¡Atencion!","txtbuscado");
	}
	
	//calculo de totales
	function seleccionarGuias(valor){
		var u = document.all;
		for(var i=0; i<tabla1.getRecordCount(); i++){
			u["detalle_guias1_S"][i].checked = valor;
		}
		calcularSeleccionX();
	}
	function seleccionarGuias2(valor){
		var u = document.all;
		for(var i=0; i<tabla2.getRecordCount(); i++){
			u["detalle_guias2_S"][i].checked = valor;
		}
		calcularSeleccionY();
	}
	
	function calcularSeleccionX(){
		var tflete			= 0;
		var ttotaldescuento = 0;
		var texcedente 		= 0;
		var tead 			= 0;
		var trecoleccion	= 0;
		var tseguro 		= 0;
		var tcombustible 	= 0;
		var totros 			= 0;
		var tsubtotal		= 0;
		var tiva 			= 0;
		var tivar 			= 0;
		var ttotal 			= 0;
		var decontado		= 0;
		var fila;
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(u["detalle_guias1_S"][i].checked==true){
				fila = tabla1.getRowByIndex(i);
				
				tflete			+= parseFloat(fila.tflete);
				ttotaldescuento += parseFloat(fila.ttotaldescuento);
				texcedente 		+= parseFloat(fila.texcedente);
				tead 			+= parseFloat(fila.tcostoead);
				trecoleccion	+= parseFloat(fila.trecoleccion);
				tseguro 		+= parseFloat(fila.tseguro);
				tcombustible 	+= parseFloat(fila.tcombustible);
				totros 			+= parseFloat(fila.totros);
				tsubtotal		+= parseFloat(fila.subtotal);
				tiva 			+= parseFloat(fila.tiva);
				tivar			+= parseFloat(fila.ivaretenido);
				ttotal 			+= parseFloat(fila.total);
				if((fila.tipo=="S" && fila.tipoguia=="PREPAGADA") || (fila.tipo=="G" && fila.tipoguia=="CONSIGNACION")){
					decontado += parseFloat(fila.total);
				}
			}
		}
		
		u.tflete.value			= convertirMoneda(tflete);
		u.ttotaldescuento.value = convertirMoneda(ttotaldescuento);
		u.texcedente.value 		= convertirMoneda(texcedente);
		u.tead.value 			= convertirMoneda(tead);
		u.trecoleccion.value	= convertirMoneda(trecoleccion);
		u.tseguro.value 		= convertirMoneda(tseguro);
		u.tcombustible.value 	= convertirMoneda(tcombustible);
		u.totros.value 			= convertirMoneda(totros);
		u.tsubtotal.value		= convertirMoneda(tsubtotal);
		u.tiva.value 			= convertirMoneda(tiva);
		u.tivar.value 			= convertirMoneda(tivar);
		u.ttotal.value 			= convertirMoneda(ttotal);
		u.solicitudesdecontado.value		= decontado;
	}
	
	function calcularSeleccionY(){
		var tseguro 		= 0;
		var tsubtotal		= 0;
		var tiva 			= 0;
		var tivar 			= 0;
		var ttotal 			= 0;
		var fila;
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(u["detalle_guias2_S"][i].checked==true){
				fila = tabla2.getRowByIndex(i);
				tseguro 		+= parseFloat(fila.tseguro);
				tsubtotal		+= parseFloat(fila.subtotal);
				tiva 			+= parseFloat(fila.tiva);
				tivar			+= parseFloat(fila.ivaretenido);
				ttotal 			+= parseFloat(fila.total);
			}
		}
		
		u.excedentesdecontado.value = ttotal;
		u.sseguro.value 		= convertirMoneda(tseguro);
		u.ssubtotal.value		= convertirMoneda(tsubtotal);
		u.siva.value 			= convertirMoneda(tiva);
		u.sivar.value 			= convertirMoneda(tivar);
		u.smonto.value 			= convertirMoneda(ttotal);
	}
	
	function calcularSeleccion(numero,valor){
		
		/**/
	}
	function calcularTotalesOtros(){
		u = document.all;
		
		var subtotal 		= parseFloat(u.cantidad.value)*parseFloat(desconvertirMoneda(u.importe.value));
		var iva				= (parseFloat(u.porcentajeiva.value)/100)*subtotal;
		if(u.personamoral.value == 'SI')
			var ivaret		= (parseFloat(u.porcentajeivaretenido.value)/100)*subtotal;
		else
			var ivaret		= 0;
		var montofacturar	= subtotal+iva-ivaret;
		
		u.subtotalotros.value = convertirMoneda(subtotal);
		u.ivaotros.value = convertirMoneda(iva);
		u.ivarotros.value = convertirMoneda(ivaret);
		u.montootros.value = convertirMoneda(montofacturar);
	}
	
	function validarGuardar(){
		<?=$cpermiso->verificarPermiso("291,320",$_SESSION[IDUSUARIO]);?>;
		if(u.idcliente.value==""){
			alerta("Proporcione el cliente","¡ATENCION!","idcliente")
			return false;
		}
		
		var montoo = (u.montootros.value=="")?0:u.montootros.value;
		//var montos = (document.getElementById("smonto").value=="")?0:document.getElementById("smonto").value;
		var montos = (u.smonto.value=="")?0:u.smonto.value;
		var montot = (u.ttotal.value=="")?0:u.ttotal.value;
		//var montot = (document.getElementById("ttotal").value=="")?0:document.getElementById("ttotal").value;
		if(parseFloat(montoo)<=0 && parseFloat(montos)<=0 && parseFloat(montot)<=0){
			alerta3("No puede realizar una factura sin cantidades","Atención");
			return false;
		}
		return true;
	}
	
	function enviardatosFacturacionElectronica(){
		document.getElementById("i_rfc").value = u.rfc.value;
		document.getElementById("i_name").value = u.nombre.value;
		document.getElementById("i_street").value = u.calle.value;
		document.getElementById("i_outside_number").value = u.numero.value;
		document.getElementById("i_col").value = u.colonia.value;
		document.getElementById("i_cp").value = u.cp.value;
		document.getElementById("i_municipio").value = u.municipio.value;
		document.getElementById("i_state").value = u.estado.value;
		document.getElementById("i_country").value = u.pais.value;
		
		var cosas = "";
		
		<?
		$s = "SELECT (SELECT iva
		FROM catalogosucursal WHERE id = '$_SESSION[IDSUCURSAL]') AS iva, (SELECT ivaretenido
		FROM configuradorgeneral) AS ivar";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			$iva 		= $f->iva;
			$ivaret 	= $f->ivar;
			
		?>
		
		var totalventa = 0;
		var iva 	= <?=($iva!="")?"$iva":"0"?>;
		if(document.all.personamoral.value = 'SI')
			var ivaret 	= <?=($ivaret!="")?"$ivaret":"0"?>;
		else
			var ivaret 	= 0;
		var contadorcajas = 0;
		
		for(var i=0; i<tabla1.getRecordCount(); i++){
			if(document.all['detalle_guias1_S'][i].checked){
				contadorcajas++;
				totalventa += parseFloat(document.all['detalle_guias1_SUBTOTAL'][i].value.replace("$ ","").replace(/,/g,""));
				cosas += '<input type="text" name="data[producto]['+contadorcajas+'][preciounitario]" value="'+
				document.all['detalle_guias1_SUBTOTAL'][i].value.replace("$ ","").replace(/,/g,"")+'" />'+
				'<input type="text" name="data[producto]['+contadorcajas+'][descripcion]" value="'+
				((document.all['detalle_guias1_T'][i].value=="G")?"GUIA "+document.all['detalle_guias1_FOLIO'][i].value+" "+
				document.all['detalle_guias1_TIPOGUIA'][i].value:"SOLICITUD DE FOLIOS "+document.all['detalle_guias1_FOLIO'][i].value)+
				'" />'+
				'<input type="text" name="data[producto]['+contadorcajas+'][cantidad]" value="1" />'+
				'<input type="text" name="data[producto]['+contadorcajas+'][importe]" value="'+
				document.all['detalle_guias1_SUBTOTAL'][i].value.replace("$ ","").replace(/,/g,"")+'" />';
			}
		}
	
		for(var i=0; i<tabla2.getRecordCount(); i++){
			if(document.all['detalle_guias2_S'][i].checked){
				contadorcajas++;
				totalventa += parseFloat(document.all['detalle_guias2_IMPORTE'][i].value.replace("$ ","").replace(/,/g,""));
				cosas += '<input type="text" name="data[producto]['+contadorcajas+'][preciounitario]" value="'+
				document.all['detalle_guias2_IMPORTE'][i].value.replace("$ ","").replace(/,/g,"")+'" />'+
				'<input type="text" name="data[producto]['+contadorcajas+'][descripcion]" value="'+
				document.all['detalle_guias2_GUIA'][i].value+" "+document.all['detalle_guias2_TIPOGUIA'][i].value+
				" "+document.all['detalle_guias2_CONCEPTO'][i].value+'" />'+
				'<input type="text" name="data[producto]['+contadorcajas+'][cantidad]" value="1" />'+
				'<input type="text" name="data[producto]['+contadorcajas+'][importe]" value="'+
				document.all['detalle_guias2_IMPORTE'][i].value.replace("$ ","").replace(/,/g,"")+'" />';
			}
		}
		
		if(document.all.montootros.value != ""){
			contadorcajas++;
			totalventa += parseFloat(document.all.importe.value.replace("$ ","").replace(/,/g,""));
			cosas += '<input type="text" name="data[producto]['+contadorcajas+'][preciounitario]" value="'+
			document.all.importe.value.replace("$ ","").replace(/,/g,"")+'" />'+
			'<input type="text" name="data[producto]['+contadorcajas+'][descripcion]" value="'+
			document.all.descripcion.value+'" />'+
			'<input type="text" name="data[producto]['+contadorcajas+'][cantidad]" value="'+
			document.all.cantidad.value+'" />'+
			'<input type="text" name="data[producto]['+contadorcajas+'][importe]" value="'+
			document.all.importe.value.replace("$ ","").replace(/,/g,"")+'" />';
		}
		totalventa = totalventa.toFixed(2);
		document.getElementById('contenidoProductos').innerHTML = cosas;
		
		var eliva = totalventa*(iva/100);
		eliva = eliva.toFixed(2);
		document.getElementById('c_iva').value = eliva;
		
		var elivaretenido = totalventa*(ivaret/100);
		elivaretenido = elivaretenido.toFixed(2);
		document.getElementById('c_ivaretenido').value = elivaretenido;
		
		document.getElementById('c_subtotal').value = totalventa;
		document.getElementById('c_total').value = parseFloat(totalventa)+parseFloat(eliva)-parseFloat(elivaretenido);
		
		//document.all.losdatos.submit();
		var ladata = $('#losdatos').serialize();
		
		ladata = ladata.replace(/&/g,"xAMx").replace(/=/g,"xIQx");
		
		//consultaTexto("resConsulta","facturacion/Facturacion_consultaj.php?accion=1&data="+ladata);
		return ladata;

	}
	
	function limpiarMontroOtros(){
		document.all.subtotalotros.value = "";
		document.all.ivaotros.value = "";
		document.all.ivarotros.value = "";
		document.all.montootros.value = "";
	}
</script>
<body>
<form id="form1" name="form1" method="post" action="">
<table width="812" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="598" class="FondoTabla Estilo4" style="font-size:12px">FACTURACIÓN</td>
  </tr>
  <tr>
    <td><table width="811" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2" align="center">
        	
        	<?
				$s = "SELECT IF(ISNULL(MAX(folio)),0,MAX(folio))+1 AS foliofactura, 
				DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y') AS fechaactual FROM facturacion where folio > 0";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
            <table width="809" border="0" cellpadding="0" cellspacing="0">
   	      <tr>
               	  <td width="56" height="23"><input name="confirFecha" type="hidden" id="confirFecha" value="<?=date('d/m/Y') ?>" /></td>
               	  <td width="74" align="right">
                  <input type="hidden" value="" name="estadocobranza" id="estadocobranza" />
                  <input name="personamoral" type="hidden" id="personamoral" />
                  <input name="folioliquidacionmercancia" type="hidden" id="folioliquidacionmercancia" />
               	    <input name="modificar" type="hidden" id="modificar" value="0" />
              Folio <input type="hidden" name="sepuede" /><input type="hidden" name="creditodisponible" />
   	          <input type="hidden" name="folioasustituir" />   	          <input type="hidden" name="activado" />
                        	      </td>
                	<td width="179" align="right" id="folio" style="font:tahoma; font-size:15px; font-weight:bold"><?=$f->foliofactura?></td>
                	<td width="41" align="right"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarFacturasGen.php?funcion=cargarFactura&todas=SI', 570, 470, 'ventana', 'Busqueda')"></div></td>
                	<td width="65" align="right">Fecha</td>
                	<td width="113" align="right"><input name="fecha" type="text" class="Tablas" id="fecha" style="width:70px;background:#FFFF99" value="<?=$f->fechaactual ?>" readonly=""/></td>
                	<td width="95" align="right">Estado:</td>
                	<td width="182" align="right" id="estadofactura" style="font:tahoma; font-size:15px; font-weight:bold"></td>
                	<td width="4" align="right" ></td>
                </tr>
   	      <tr>
   	        <td><span style="text-align:left">Sucursal:</span></td>
   	        <td colspan="2" align="right"><div id="lasucursal" style="text-align:left"> <?=$nsucursal?></div></td>
   	        <td align="right">&nbsp;</td>
   	        <td align="right">Fecha Pago:</td>
   	        <td align="right" id="fechapago">
   	         
              
   	        </td>
   	        <td align="right">Fecha Cancelacion</td>
   	        <td align="right" id="fechacancelacion"></td>
<td align="right" ></td>
 	        </tr>
          </table>      </tr>
      <tr>
        <td height="5px;"></td>
      </tr>
      <tr>
        <td class="FondoTabla Estilo4">Datos Facturaci&oacute;n de Cliente </td>
        </tr>
      <tr>
        <td colspan="49"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="50" height="24" class="Tablas"><label>Cliente</label></td>
<td width="34" class="Tablas"><input name="idcliente" type="text" onkeypress="if(event.keyCode==13){pedirCliente(this.value)}" class="Tablas" id="nick" style="width:25px;" value="<?=$nick ?>" /></td>
              <td width="61" class="Tablas"><div class="ebtn_buscar" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=pedirCliente', 625, 418, 'ventana', 'Busqueda')"></div></td>
              <td width="111" class="Tablas">Mostrar Guias de</td>
              <td width="102" class="Tablas"><input type="radio" name="guia" value="1" checked="checked" onclick="if(document.all.idcliente.value != ''){limpiarCambioClientes();cargarGuias(document.all.idcliente.value)}" />
Ventanilla</td>
              <td width="120" class="Tablas"><input type="radio" name="guia" value="1" onclick="if(document.all.idcliente.value != ''){limpiarCambioClientes();cargarGuias(document.all.idcliente.value)}" /> 
                Empresariales
</td>
              <td width="80" class="Tablas"><input type="radio" name="tipoguias" value="1" checked="checked" onclick="if(document.all.idcliente.value != ''){limpiarCambioClientes();cargarGuias(document.all.idcliente.value)}" />
                Credito</td>
              <td width="93" class="Tablas"><input type="radio" name="tipoguias" value="0" onclick="if(document.all.idcliente.value != ''){limpiarCambioClientes();cargarGuias(document.all.idcliente.value)}" />
Contado</td>
              </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" class="Tablas">Nombre&nbsp;
            &nbsp;<input name="nombre" type="text" class="Tablas" id="nombre" style="width:125px" value="<?=$nombre ?>" /> 
            Apellido Pat
            <input name="paterno" type="text" class="Tablas" id="paterno" style="width:125px" value="<?=$paterno ?>" />
          Apellido Mat
          <input name="materno" type="text" class="Tablas" id="materno" style="width:125px" value="<?=$materno ?>" /></td>
      </tr>
      <tr>
        <td colspan="3" class="Tablas">
        <table width="650" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="50"> 
                
                Calle</td>
            	<td width="252" id="celdacalle"><input name="calle" type="text" class="Tablas" id="calle" style="width:250px"/></td>
            	<td width="34">
                <img id="cliente_dir" src="../img/Boton_Agregarchico.gif" alt="Agregar Dirección" style="cursor:hand" onClick="if(document.all.idcliente.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','idcliente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=pedirCliente('+document.all.idcliente.value+')&idcliente='+document.all.idcliente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}" />                </td>
            	<td width="82">N&uacute;mero</td>
            	<td width="95"><input name="numero" type="text" class="Tablas" id="numero" style="width:80px;" value="<?=$numero ?>" /></td>
            	<td width="30">CP</td>
            	<td width="107"><input name="cp" type="text" class="Tablas" id="cp" style="width:80px;" value="<?=$cp ?>" /></td>
            </tr>
        </table>        </td>
      </tr>
      <tr>
        <td colspan="49"><table width="810" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td><label>Colonia</label></td>
              <td colspan="3"><input name="colonia" type="text" class="Tablas" id="colonia" style="width:220px;" value="<?=$colonia ?>" />
                  <label></label></td>
              <td>Cruce de Calles </td>
              <td colspan="3"><input name="ccalles" type="text" class="Tablas" id="ccalles" style="width:220px;" value="<?=$ccalles ?>" /></td>
            </tr>
            <tr>
              <td><label>Poblacion</label></td>
              <td><input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:80px;" value="<?=$poblacion ?>" /></td>
              <td><label>Municipio/ Delegacion</label></td>
              <td><input name="municipio" type="text" class="Tablas" id="municipio" style="width:80px;" value="<?=$municipio ?>" /></td>
              <td width="103"><label>Estado </label></td>
              <td width="113"><input name="estado" type="text" class="Tablas" id="estado" style="width:80px;" value="<?=$estado ?>" /></td>
              <td width="30"><label>País</label></td>
              <td width="146"><input name="pais" type="text" class="Tablas" id="pais" style="width:80px;" value="<?=$pais ?>" /></td>
            </tr>
            <tr>
              <td width="62"><label>Télefono</label></td>
              <td width="105"><input name="telefono" type="text" class="Tablas" id="telefono" style="width:80px;" value="<?=$telefono ?>" /></td>
              <td width="140"><label>Fax</label></td>
              <td width="111"><input name="fax" type="text" class="Tablas" id="fax" style="width:80px;" value="<?=$fax ?>" /></td>
              <td><input type="hidden" name="facturacion" />
                Rfc</td>
              <td colspan="3"><input name="rfc" type="text" class="Tablas" id="rfc" style="width:95px;" value="<?=$rfc ?>" /></td>
</tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
          </table>
            <label></label>
            <label></label></td>
      </tr>
      <tr>
        <td class="FondoTabla Estilo4">Facturac&iacute;on Gu&iacute;as </td>
      </tr>
      <tr>
        <td colspan="2"><table width="584" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <th width="584" scope="row">&nbsp;</th>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" id="detalle_guias1">
        </table>
        </td>
      </tr>
      <tr>
        <td colspan="49"><table width="810" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="40">Todos </td>
              <td width="73"><input type="checkbox" name="chktodos" onclick="seleccionarGuias(this.checked);" /></td>
              <td colspan="2"><input type="hidden" name="cantidadregistros" value="0" />
              <table border="0" cellpadding="0" cellspacing="0">
              	<tr>
                	<td width="82">Seleccionar:</td>
                    <td width="93"><input type="text" onfocus="this.select()" name="txtbuscado" onkeypress="if(event.keyCode==13){seleccionarUnaGuia(this.value)}" value="" style="width:80px" /></td>
                </tr>
              </table>
              </td>
<td>					<input type="hidden" name="valorcontado" value="0" />
						<input type="hidden" name="solicitudesdecontado" value="0" />
                        <input type="hidden" name="excedentesdecontado" value="0" />
						<input type="hidden" value="" name="efectivo">
                        <input type="hidden" value="" name="cheque">
                        <input type="hidden" value="" name="ncheque">
                        <input type="hidden" value="" name="banco">
                        <input type="hidden" value="" name="tarjeta">
                        <input type="hidden" value="" name="transferencia">
                        <input type="hidden" value="" name="nc">
                        <input type="hidden" value="" name="nc_folio">
                        <input type="hidden" value="" name="conformapago">
</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2"><label>Guías Empresariales</label></td>
<td width="139"><input name="guiase" type="text" class="Tablas" id="guiase" style="width:100px;background:#FFFF99; text-align:right" value="<?=$guiase ?>" readonly=""/></td>
              <td width="58"><label>Flete</label></td>
              <td width="112"><input name="tflete" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$flete ?>" readonly=""/>              </td>
              <td width="73"><label>Combustible</label></td>
              <td width="108"><div align="left">
                  <input name="tcombustible" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$combustible ?>" readonly=""/>
              </div></td>
            </tr>
            <tr>
              <td colspan="2"><label>Gu&iacute;as Normales </label></td>
<td><input name="guiasn" type="text" class="Tablas" id="guiasn" style="width:100px;background:#FFFF99; text-align:right" value="<?=$guiasn ?>" readonly=""/></td>
              <td>Descuento</td>
              <td><div align="left">
              <input name="ttotaldescuento" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$totaldescuento ?>" readonly=""/>
              </div></td>
              <td><label>Otros</label></td>
              <td><div align="left">
                  <input name="totros" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$otros ?>" readonly=""/>
              </div></td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
              <td>Excedente</td>
              <td><div align="left">
                <input name="texcedente" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$excedente ?>" readonly=""/>
              </div></td>
              <td><label>Subtotal</label></td>
              <td><div align="left">
                <input name="tsubtotal" type="text" class="Tablas" id="subtotal" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$subtotal ?>" readonly=""/>
              </div></td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
              <td>EAD</td>
              <td><div align="left">
                <input name="tead" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$eao ?>" readonly=""/>
              </div></td>
              <td><label>IVA</label></td>
              <td><input name="tiva" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="" readonly=""/>              </td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
              <td>Recolección</td>
              <td><div align="left">
                <input name="trecoleccion" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$recoleccion ?>" readonly=""/>
              </div></td>
              <td><label>IVA Retenido</label></td>
              <td><div align="left">
                  <input name="tivar" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$ivar ?>" readonly=""/>
                </div>
                  <div align="left"></div></td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
              <td>Seguro</td>
              <td><input name="tseguro" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$seguro ?>" readonly=""/></td>
              <td><label>Total</label></td>
              <td><div align="left">
                  <input name="ttotal" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right;" value="<?=$total ?>" readonly=""/>
              </div></td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td class="FondoTabla Estilo4">Facturaci&oacute;n de Sobrepeso y Valores Declarados </td>
      </tr>
      <tr>
        <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="detalle_guias2">
        </table>
        </td>
      </tr>
      <tr>
        <td colspan="49"><table width="811" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>Todos</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td width="8">&nbsp;</td>
            </tr>
            <tr>
              <td width="41">&nbsp;</td>
              <td width="56">Seguro</td>
              <td width="118"><input name="sseguro" type="text" class="Tablas" style="width:100px;background:#FFFF99; text-align:right" value="<?=$seguro2 ?>" readonly=""/></td>
              <td width="34">&nbsp;</td>
              <td width="59"><label>Subtotal</label></td>
              <td width="108"><input name="ssubtotal" type="text" class="Tablas" style="width:80px;background:#FFFF99; text-align:right" value="<?=$subtotal2 ?>" readonly=""/></td>
              <td><label>IVA Retenido</label></td>
              <td colspan="2"><div align="left"><input name="sivar" type="text" class="Tablas" style="width:80px;background:#FFFF99; text-align:right"  readonly=""/></div></td>
            </tr>
            <tr>
              <td colspan="4">&nbsp;</td>
              <td><label>IVA</label></td>
              <td><input name="siva" type="text" class="Tablas" style="width:80px;background:#FFFF99; text-align:right" value="<?=$iva2 ?>" readonly=""/></td>
              <td width="94"><label>Monto a Facturar</label></td>
              <td width="87"><div align="left">
                <input name="smonto" type="text" class="Tablas" style="width:80px;background:#FFFF99; text-align:right" value="<?=$monto ?>" readonly=""/>
              </div></td>
            </tr>
            <tr>
              <td colspan="9">&nbsp;</td>
            </tr>
        </table>          </td>
      </tr>
      <tr>
        <td class="FondoTabla Estilo4">Facturaci&oacute;n Otros </td>
      </tr>
      <tr>
        <td colspan="49"></td>
      </tr>
      <tr>
        <td colspan="49"></td>
      </tr>
      <tr>
        <td width="616" valign="top"><label>Cantidad
          <input name="cantidad" type="text" style="width:80px; text-align:right; text-align:right" onblur="if(this.value=='' || this.value==0){this.value='';limpiarMontroOtros()}" onfocus="this.select();" onkeypress="if(event.keyCode==13){document.all.descripcion.focus(); return false;}else{return solonumeros(event);}"/>
          Descripcion<span class="Tablas">
            <textarea name="descripcion" cols="30" style="font-family: tahoma; font-size: 9px; font-style: normal; font-weight: bold; text-transform:uppercase" onkeypress="if(event.keyCode==13){document.all.importe.focus(); return false;}"></textarea>
          </span></label>
          <?
		  	$s = "SELECT (select cs.iva from catalogosucursal as cs where id='$_SESSION[IDSUCURSAL]') as iva, ivaretenido FROM configuradorgeneral";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)>0)
				$f = mysql_fetch_object($r);
		  ?>
          <input type="hidden" name="porcentajeiva" value="<?=$f->iva?>" />
          <input type="hidden" name="porcentajeivaretenido" value="<?=$f->ivaretenido?>" />
          </td>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><label>Importe
          <input name="importe" type="text" style="width:80px; text-align:right; text-align:right" onblur="if(this.value=='' || this.value==0){this.value=''; limpiarMontroOtros();}else{this.value = convertirMoneda(this.value); calcularTotalesOtros();}" onfocus="this.value = desconvertirMoneda(this.value); this.select();" onkeypress="if(event.keyCode==13){calcularTotalesOtros();}else{return numerosydecimal(event,this.value);}"/>
          </label>
            <label>Subtotal
              <input name="subtotalotros" type="text" class="Tablas" style="width:60px;background:#FFFF99; text-align:right" value="<?=$subtotal3 ?>" readonly=""/>
            </label>
            <label>IVA
              <input name="ivaotros" type="text" class="Tablas" style="width:60px;background:#FFFF99; text-align:right" value="<?=$iva3 ?>" readonly=""/>
            </label>
            <label>IVA Ret</label>
            <input name="ivarotros" type="text" class="Tablas" style="width:60px;background:#FFFF99; text-align:right" value="<?=$ivar3 ?>" readonly=""/>
            <label>Monto a Facturar
              <input name="montootros" type="text" class="Tablas" style="width:60px;background:#FFFF99; text-align:right" value="<?=$monto2 ?>" readonly=""/>
          </label></td>
      </tr>
      <tr>
        <td id="textosustitucion" align="center" style="font-family: tahoma;font-size: 16px;font-style: normal;font-weight: bold;color:#FF0000;"></td>
      </tr>
      <tr>
      	<td style="text-align:left"><a href="#" onclick="if(document.getElementById('folio').innerHTML !=' &nbsp;' && document.getElementById('folio').innerHTML != '' && document.all.idcliente.value!=''){window.open('generarxml.php?folio='+document.getElementById('folio').innerHTML,null,'width=100 height=100 top=1500 left=2500');}else{alerta3('Seleccione la factura','¡Atencion!');}" style="font-family:Verdana, Geneva, sans-serif; font-size:14px">Descargar XML</a></td>
      </tr>
      <tr>
        <td align="center" id="bonotesAccion">
        <table><tr><td><div class="ebtn_guardar" onclick="if(validarGuardar()){ confirmar('¿Desea guardar la factura?','¡Atencion!','guardarFacturar()',''); }"/></td><td><div class="ebtn_nuevo" onclick="limpiarTodo();pedirFolio(); bloquear(false);"/></td></tr></table>
        </td>
       </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
<form name="losdatos" id="losdatos" style="display:none" action="http://pmm.comprobantesdigitales.com.mx/invoices/remote/136f43d234b5c17c34cbd7c7367cd93a" method="post" target="_blank">
 	receptor
    <input type="text" name="data[informacion][rfc]" id="i_rfc" />
    <input type="text" name="data[informacion][name]" id="i_name" />
    <input type="text" name="data[informacion][street]" id="i_street" />
    <input type="text" name="data[informacion][outside_number]" id="i_outside_number" />
    <input type="text" name="data[informacion][col]" id="i_col" />
    <input type="text" name="data[informacion][cp]" id="i_cp" />
    <input type="text" name="data[informacion][municipio]" id="i_municipio" />
    <input type="text" name="data[informacion][state]" id="i_state" />
    <input type="text" name="data[informacion][country]" id="i_country" />
    
    <div id="contenidoProductos">
    </div>
    
    <input type="text" name="data[Impuestos][totalImpuestosTrasladados]" id="c_totalImpuestosTrasladados" value="0" />
    <input type="text" name="data[Impuestos][tasa]" id="c_tasa" value="<?=$iva?>" />
    <input type="text" name="data[Impuestos][iva]" id="c_iva" value="0" />
    <input type="text" name="data[Impuestos][ivaRetenido]" id="c_ivaretenido" value="0" />
    <input type="text" name="data[Impuestos][subtotal]" id="c_subtotal" value="0" />
    <input type="text" name="data[Impuestos][total]" id="c_total" value="0" />
</form>
<div id="facturacancelada" style="display:none; position:absolute; left: 100px; top: 237px;">
	<img src="../img/evaluacion cancelada.gif" />
</div>
</body>
</html>
