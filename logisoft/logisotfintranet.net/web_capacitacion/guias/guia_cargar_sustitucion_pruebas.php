<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Principal</title>
<link href="puntovta.css" rel="stylesheet" type="text/css">
<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="estilo_guia.css" rel="stylesheet" type="text/css" />
<!-- estilos y funciones para ventana modal -->
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<link href="../javascript/estiloclasetablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<!-- funciones para ajax -->
<script type="text/javascript" src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../convenio/validacionesConvenio.js"></script>
<OBJECT ID="Metodos" style="visibility:hidden"
CLASSID="CLSID:21B8DA59-7F02-40B9-A5E9-FC848C3DB134"
CODEBASE="http://pmmintranet.net/web/activexs/Impresion.CAB#version=1,1,0,0">
</OBJECT>
<script>
	//declaracion de tablas
	var sucursalorigen 	= 0;
	
	var valCon = new validacionesConvenio();
	
	var tabla1 = new ClaseTabla();
	
	tabla1.setAttributes({
		nombre:"tablaconteva",
		campos:[
			{nombre:"IDM", medida:4, alineacion:"left", tipo:"oculto", datos:"idmercancia"},
			{nombre:"Cant", medida:39, alineacion:"left", datos:"cantidad"},
			{nombre:"Descripcion", medida:104, alineacion:"left", datos:"descripcion"},
			{nombre:"Contenido", medida:104, alineacion:"left", datos:"contenido"},
			{nombre:"Peso", medida:39, alineacion:"right", datos:"peso"},
			{nombre:"Vol", medida:42, alineacion:"right", datos:"volumen"},
			{nombre:"Importe", medida:68, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"M", medida:14, alineacion:"right", datos:"modificable"}
		],
		filasInicial:7,
		alto:100,
		seleccion:true,
		eventoDblClickFila:"paracambiarvalor(tabla1.getSelectedRow().modificable);",
		ordenable:true,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
	}
	
	function elegirImpresora(){
		abrirVentanaFija('../buscadores_generales/impresionGuias.php', 300, 230, 'ventana', 'Busqueda');
	}
	
	function imprimir(valor){
		if(valor==2){
			cambiarImpresora1();
			return false;
		}
		<? 
			$s = "SELECT impetiquetasguias FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetasguias)."');";
			}else{
				$s = "SELECT impetiquetasguias FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetasguias)."');";
				}
			}
		?>
		window.open("../guias/imprimiretiquetaguia_solo.php?tipo=1&codigo="+document.all.folioSeleccionado.innerHTML+"&valor="+valor,"1","width=500,height=500");
	}
	function cambiarImpresora1(){
		<? 
			$s = "SELECT impetiquetaspaquetes FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)."');";
			}else{
				$s = "SELECT impetiquetaspaquetes FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)."');";
				}
			}
		?>
		window.open("../guias/imprimiretiquetapaquete_solo.php?tipo=1&codigo="+document.all.folioSeleccionado.innerHTML,"2","width=500,height=500");
	}
	function cambiarImpresora2(){
		<? 
			$s = "SELECT impdefault FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
			$rxy = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($rxy)>0){
				$fxy = mysql_fetch_object($rxy);
				
				echo "var met = new ActiveXObject('Impresion.Metodos');
				met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impdefault)."');";
			}else{
				$s = "SELECT impdefault FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
				$rxy = mysql_query($s,$l) or die($s);
				if(mysql_num_rows($rxy)>0){
					$fxy = mysql_fetch_object($rxy);
					echo "var met = new ActiveXObject('Impresion.Metodos');
					met.ponerImpresora('".str_replace("\\","\\\\",$fxy->impdefault)."');";
				}
			}
		?>
	}
	
	function bloquear(valor){
		u = document.all;
		u.lstflete.disabled 		= valor;
		u.chocurre.disabled 		= valor;
		u.lstpago.disabled 			= valor;
		u.idremitente.readOnly 		= valor;
		u.iddestinatario.readOnly	= valor;
		
		u.idremitente.style.backgroundColor		= (valor)?"#FFFF99":"";
		u.iddestinatario.style.backgroundColor	= (valor)?"#FFFF99":"";
		
		u.chkemplaye.disabled 			= valor;
		u.chkbolsaempaque.disabled 	= valor;
		u.chkavisocelular.disabled 	= valor;
		u.chkvalordeclarado.disabled 	= valor;
		u.chkacuserecibo.disabled 		= valor;
		u.chkcod.disabled 				= valor;
		u.chkrecoleccion.disabled 		= valor;
		
		u.b_remitente.style.visibility = (valor)?"hidden":"visible";
		u.b_destinatario.style.visibility = (valor)?"hidden":"visible";
		u.b_remitente_dir.style.visibility = (valor)?"hidden":"visible";
		u.b_destinatario_dir.style.visibility = (valor)?"hidden":"visible";
		u.img_descuento.style.visibility = (valor)?"hidden":"visible";
		
	}
	
	function limpiar_cajas(){
	}
		
	function solicitarGuia(folio){
		var u = document.all;
		consulta("respuestaGuia","guia_cargar_sustitucion_con.php?accion=1&folio="+folio+"&rand="+Math.random());
		u.idgcys.value = folio;
	}
	function respuestaGuia(datos){
		//alert(datos);
		u = document.all;
		bloquear(true);
		
		var encon = datos.getElementsByTagName('encontrados').item(0).firstChild.data;
		if(encon>0){
			var id						= datos.getElementsByTagName('id').item(0).firstChild.data;
			var evaluacion				= datos.getElementsByTagName('evaluacion').item(0).firstChild.data;
			var fecha					= datos.getElementsByTagName('fecha').item(0).firstChild.data;
			var fechaactual				= datos.getElementsByTagName('fechaactual').item(0).firstChild.data;
			var fechaentrega			= datos.getElementsByTagName('fechaentrega').item(0).firstChild.data;
			var factura					= datos.getElementsByTagName('factura').item(0).firstChild.data;
			var estado					= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var tipoflete				= datos.getElementsByTagName('tipoflete').item(0).firstChild.data;
			var ocurre					= datos.getElementsByTagName('ocurre').item(0).firstChild.data;
			var idsucursalorigen		= datos.getElementsByTagName('idsucursalorigen').item(0).firstChild.data;
			var ndestino				= datos.getElementsByTagName('ndestino').item(0).firstChild.data;
			var nsucdestino				= datos.getElementsByTagName('nsucdestino').item(0).firstChild.data;
			var condicionpago			= datos.getElementsByTagName('condicionpago').item(0).firstChild.data;
			
			var idremitente				= datos.getElementsByTagName('idremitente').item(0).firstChild.data;
			var rncliente				= datos.getElementsByTagName('rncliente').item(0).firstChild.data;
			var rrfc					= datos.getElementsByTagName('rrfc').item(0).firstChild.data;
			var rcelular				= datos.getElementsByTagName('rcelular').item(0).firstChild.data;
			var rcalle					= datos.getElementsByTagName('rcalle').item(0).firstChild.data;
			var rnumero					= datos.getElementsByTagName('rnumero').item(0).firstChild.data;
			var rcp						= datos.getElementsByTagName('rcp').item(0).firstChild.data;
			var rpoblacion				= datos.getElementsByTagName('rpoblacion').item(0).firstChild.data;
			var rtelefono				= datos.getElementsByTagName('rtelefono').item(0).firstChild.data;
			var rcolonia				= datos.getElementsByTagName('rcolonia').item(0).firstChild.data;
			
			var iddestinatario			= datos.getElementsByTagName('iddestinatario').item(0).firstChild.data;
			var dncliente				= datos.getElementsByTagName('dncliente').item(0).firstChild.data;
			var drfc					= datos.getElementsByTagName('drfc').item(0).firstChild.data;
			var dcelular				= datos.getElementsByTagName('dcelular').item(0).firstChild.data;
			var dcalle					= datos.getElementsByTagName('dcalle').item(0).firstChild.data;
			var dnumero					= datos.getElementsByTagName('dnumero').item(0).firstChild.data;
			var dcp						= datos.getElementsByTagName('dcp').item(0).firstChild.data;
			var dpoblacion				= datos.getElementsByTagName('dpoblacion').item(0).firstChild.data;
			var dtelefono				= datos.getElementsByTagName('dtelefono').item(0).firstChild.data;
			var dcolonia				= datos.getElementsByTagName('dcolonia').item(0).firstChild.data;
			
			var entregaocurre			= datos.getElementsByTagName('entregaocurre').item(0).firstChild.data;
			var entregaead				= datos.getElementsByTagName('entregaead').item(0).firstChild.data;
			var restrinccion			= datos.getElementsByTagName('restrinccion').item(0).firstChild.data;
			var totalpaquetes			= datos.getElementsByTagName('totalpaquetes').item(0).firstChild.data;
			var totalpeso				= datos.getElementsByTagName('totalpeso').item(0).firstChild.data;
			var totalvolumen			= datos.getElementsByTagName('totalvolumen').item(0).firstChild.data;
			var emplaye					= datos.getElementsByTagName('emplaye').item(0).firstChild.data;
			var bolsaempaque			= datos.getElementsByTagName('bolsaempaque').item(0).firstChild.data;
			var totalbolsaempaque		= datos.getElementsByTagName('totalbolsaempaque').item(0).firstChild.data;
			var avisocelular			= datos.getElementsByTagName('avisocelular').item(0).firstChild.data;
			var celular					= datos.getElementsByTagName('celular').item(0).firstChild.data;
			var valordeclarado			= datos.getElementsByTagName('valordeclarado').item(0).firstChild.data;
			var acuserecibo				= datos.getElementsByTagName('acuserecibo').item(0).firstChild.data;
			var cod						= datos.getElementsByTagName('cod').item(0).firstChild.data;
			var recoleccion				= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;
			var observaciones			= datos.getElementsByTagName('observaciones').item(0).firstChild.data;
			var tflete					= datos.getElementsByTagName('tflete').item(0).firstChild.data;
			var tdescuento				= datos.getElementsByTagName('tdescuento').item(0).firstChild.data;
			var ttotaldescuento			= datos.getElementsByTagName('ttotaldescuento').item(0).firstChild.data;
			var tcostoead				= datos.getElementsByTagName('tcostoead').item(0).firstChild.data;
			var trecoleccion			= datos.getElementsByTagName('trecoleccion').item(0).firstChild.data;
			var tseguro					= datos.getElementsByTagName('tseguro').item(0).firstChild.data;
			var totros					= datos.getElementsByTagName('totros').item(0).firstChild.data;
			var texcedente				= datos.getElementsByTagName('texcedente').item(0).firstChild.data;
			var tcombustible			= datos.getElementsByTagName('tcombustible').item(0).firstChild.data;
			var subtotal				= datos.getElementsByTagName('subtotal').item(0).firstChild.data;
			var tiva					= datos.getElementsByTagName('tiva').item(0).firstChild.data;
			var ivaretenido				= datos.getElementsByTagName('ivaretenido').item(0).firstChild.data;
			var total					= datos.getElementsByTagName('total').item(0).firstChild.data;
			var efectivo				= datos.getElementsByTagName('efectivo').item(0).firstChild.data;
			var cheque					= datos.getElementsByTagName('cheque').item(0).firstChild.data;
			var banco					= datos.getElementsByTagName('banco').item(0).firstChild.data;
			var ncheque					= datos.getElementsByTagName('ncheque').item(0).firstChild.data;
			var tarjeta					= datos.getElementsByTagName('tarjeta').item(0).firstChild.data;
			var trasferencia			= datos.getElementsByTagName('trasferencia').item(0).firstChild.data;
			var importe_tipo			= datos.getElementsByTagName('tipototales').item(0).firstChild.data;
			
			u.idsustitucion.value = id;
			u.fechaactual.value			= 
			u.fecha.value				= fecha;
			u.estado.innerHTML			= estado;
			u.lstflete.value			= tipoflete;
			u.idsucursalorigen.value	= idsucursalorigen;
			u.chocurre.value			= ocurre;
			
			document.getElementById('destino').value			= ndestino;
			
			u.sucdestino.value	= nsucdestino;
			
			u.lstpago.value	= condicionpago;
			u.idremitente.value	= idremitente;
			u.rem_rfc.value	= rrfc;
			u.rem_cliente.value	= rncliente;
			u.rem_calle.value	= rcalle;
			u.rem_numero.value	= rnumero;
			u.rem_cp.value	= rcp;
			u.rem_colonia.value	= rcolonia;
			u.rem_poblacion.value	= rpoblacion;
			u.rem_telefono.value	= rtelefono;
			u.des_rfc.value	= drfc;
			u.iddestinatario.value	= iddestinatario;
			u.des_cliente.value	= dncliente;
			u.des_calle.value	= dcalle;
			u.des_numero.value	= dnumero;
			u.des_cp.value	= dcp;
			u.des_colonia.value	= dcolonia;
			u.des_poblacion.value	= dpoblacion;
			u.des_telefono.value	= dtelefono;
			
			u.txtocu.value = entregaocurre;
			u.txtead.value = entregaead;
			u.txtrestrinccion.value = restrinccion;
			
			u.totalpeso.value = totalpeso;
			u.totalpaquetes.value = totalpaquetes;
			u.totalvolumen.value = totalvolumen;
			
			u.txtemplaye.value = (emplaye=="0")?"":"$ "+numcredvar(emplaye);
			u.txtacuserecibo.value = (acuserecibo=="0")?"":"$ "+numcredvar(acuserecibo);
			u.txtbolsaempaque1.value = (bolsaempaque=="0")?"":"$ "+numcredvar(bolsaempaque);
			u.txtbolsaempaque2.value = (totalbolsaempaque=="0")?"":"$ "+numcredvar(totalbolsaempaque);
			u.txtcod.value = (cod=="0")?"":"$ "+numcredvar(cod);
			u.txtavisocelular1.value = (avisocelular=="0")?"":"$ "+numcredvar(avisocelular);
			u.txtavisocelular2.value = celular;
			u.txtrecoleccion.value = (recoleccion=="0")?"":"$ "+numcredvar(recoleccion);
			u.txtdeclarado.value = (valordeclarado=="0")?"":"$ "+numcredvar(valordeclarado);
			
			u.flete.value = "$ "+numcredvar(tflete);
			u.t_txtdescuento1.value = tdescuento+" %";
			u.t_txtdescuento2.value = "$ "+numcredvar(ttotaldescuento);
			u.t_txtead.value = "$ "+numcredvar(tcostoead);
			u.t_txtrecoleccion.value = "$ "+numcredvar(trecoleccion);
			u.t_txtseguro.value = "$ "+numcredvar(tseguro);
			u.t_txtotros.value = "$ "+numcredvar(totros);
			u.t_txttotal.value = "$ "+numcredvar(total);
			u.txtobservaciones.value = observaciones;
			
			u.t_txtexcedente.value = "$ "+numcredvar(texcedente);
			u.t_txtcombustible.value = "$ "+numcredvar(tcombustible);
			u.t_txtsubtotal.value = "$ "+numcredvar(subtotal);
			u.t_txtiva.value = "$ "+numcredvar(tiva);
			u.t_txtivaretenido.value = "$ "+numcredvar(ivaretenido);
			
			var enconeva = datos.getElementsByTagName('encontroevaluacion').item(0).firstChild.data;
			if(enconeva>0){
				tpaquetes	= 0;
				tpeso		= 0;
				tvolumen	= 0;
				timporte	= 0;
				for(m=0;m<enconeva;m++){	
					idmercancia	= datos.getElementsByTagName('idmercancia').item(m).firstChild.data;
					cantidad	= datos.getElementsByTagName('cantidad').item(m).firstChild.data;
					descripcion	= datos.getElementsByTagName('descripcion').item(m).firstChild.data;
					contenido	= datos.getElementsByTagName('contenido').item(m).firstChild.data;
					peso		= datos.getElementsByTagName('peso').item(m).firstChild.data;
					volumen		= datos.getElementsByTagName('volumen').item(m).firstChild.data;
					importe		= datos.getElementsByTagName('importe').item(m).firstChild.data;
					
					var objetox = new Object();
					objetox.idmercancia	= idmercancia;
					objetox.cantidad 	= cantidad;
					objetox.descripcion = descripcion;
					objetox.contenido 	= contenido;
					objetox.peso 		= peso;
					objetox.volumen 	= volumen;
					objetox.importe 	= importe;
					objetox.modificable = " ";
					tabla1.add(objetox);
					
					tpaquetes	+= parseFloat(cantidad);
					tpeso		+= parseFloat(peso);
					tvolumen	+= parseFloat(volumen);
					timporte	+= parseFloat(importe);
				}				
			}
		}else{
			alerta("No se encontro la guia buscada", "¡Atencion!","fecha");
		}
	}
	
	// funciones generales
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
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	function mostrarEvaluaciones(){
		so 	= '<?=$_SESSION[IDSUCURSAL]?>';
		abrirVentanaFija('../buscadores_generales/buscarEvaluacionGen.php?funcion=pedirDatosEvaluacion&tipo=evaluacion&sucorigen='+so, 650, 450, 'Evaluaciones', 'Busqueda');
	}
	
	//funciones autorizar no autorizar
	function noautorizar(){
		abrirVentanaFija("guiacs_motivo.php", 480, 400, 'ventana', 'Datos Producto')
	}
	
	function registrarGuia(){//registrar como no autorizada
		alerta3("guia_cargar_sustitucion_con.php?accion=2&folio="+u.idsustitucion.value+
				 "&idgcys="+u.idgcys.value+"&motivo="+u.motivocancelacion.value+"&rand="+Math.random());
		
		//consulta("resNoAutorizada","guia_cargar_sustitucion_con.php?accion=2&folio="+u.idsustitucion.value+
		//		"&idgcys="+u.idgcys.value+"&motivo="+u.motivocancelacion.value+"&rand="+Math.random())
	}
	
	function resNoAutorizada(datos){
		var guardado= datos.getElementsByTagName('guardado').item(0).firstChild.data;
		if(guardado==1){
			info("La guia fue guardada con estado NO AUTORIZADA","¡Atencion!");
			document.getElementById('estado').innerHTML="NO AUTORIZADA";
			document.getElementById('boton_autorizar').style.display = 'none';
			document.getElementById('boton_noautorizar').style.display = 'none';

		}else{
			cons = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al guardar //" + cons,"¡Atencion!","idremitente");
		}
	}
	
	function autorizarSustitucion(){
		confirmar("¿Desea autorizar la guia?","¡Atencion!","siAutorizada()","");
	}
	
	function siAutorizada(){//registrar como si autorizada
		consulta("resSiAutorizada","guia_cargar_sustitucion_con.php?accion=3&folio="+u.idsustitucion.value+"&rand="+Math.random())
	}
	
	function resSiAutorizada(datos){
		var guardado= datos.getElementsByTagName('guardado').item(0).firstChild.data;
		if(guardado==1){
			info("La guia fue guardada con estado AUTORIZADA PARA SUSTITUIR","¡Atencion!");
			document.getElementById('estado').innerHTML="AUTORIZADA PARA SUSTITUIR";
			document.getElementById('boton_autorizar').style.display = 'none';
			document.getElementById('boton_noautorizar').style.display = 'none';

		}else{
			cons = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al guardar //" + cons,"¡Atencion!","idremitente");
		}
	}
	
	function solicitarParaActivar(folio){
		consulta("respuestaGuia","guia_cargar_sustitucion_con.php?accion=X&folioguia="+folio+"&rand="+Math.random());
	}
	
	function guardarNuevaGuia(){
		confirmar("¿Desea generar la nueva Guia?","¡Atencion!","envGuardarNuevaGuia()","");
	}
	
	function envGuardarNuevaGuia(){
		consulta("resEnvGuardarNuevaGuia","guia_cargar_sustitucion_con.php?accion=4&folio="+u.idsustitucion.value+"&rand="+Math.random())
	}
	
	function resEnvGuardarNuevaGuia(datos){
		var guardado= datos.getElementsByTagName('guardado').item(0).firstChild.data;
		if(guardado==1){
			info("La guia fue sustituida y guardada con el estado "+datos.getElementsByTagName('estado').item(0).firstChild.data,"¡Atencion!");
			document.getElementById('estado').innerHTML=datos.getElementsByTagName('estado').item(0).firstChild.data;
			document.all.folioSeleccionado.innerHTML = datos.getElementsByTagName('folioguia').item(0).firstChild.data;
			document.getElementById('botonguardar').style.display = 'none';
			
			document.getElementById('botonimprimir').style.display = '';
			//cargarDatos();
		}else if(guardado==-1){
			alerta("Esta guia ya ha sido sustituida","¡Atencion!","idremitente");
			return false;
		}else{
			cons = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al guardar //" + cons,"¡Atencion!","idremitente");
			return false;
		}
	}
</script>
<style type="text/css">
<!--
.Estilo3 {font-size: 12px}
.ebtn_autorizar{
	background-image:url(../img/boton_autorizar.gif);
	width:100px; 
	height:20px;
	cursor:hand;
}
.ebtn_noautorizar{
	background-image:url(../img/boton_noautorizar.gif);
	width:100px; 
	height:20px;
	cursor:hand;
}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<center>
<table width="601" border="1" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td class="FondoTabla Estilo3">SUSTITUCIÓN DE GUIA</td>
  </tr>
  <tr>
    <td><table width="600" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2">
        	<input name="DetalleGrip" type="hidden" id="DetalleGrip" value="<?=$DetalleGrip ?>">
        	<input name="idsustitucion" type="hidden" id="DetalleGrip" value="">
            <input name="motivocancelacion" type="hidden" id="DetalleGrip" value="">
            <input name="idgcys" type="hidden" value="" />
        </td>
      </tr>
      <tr>
        <td colspan="2" id="folioSeleccionado" style="color:#F00000; font-size:15px; font-weight:bold" >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><table width="615" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="5%" class="Tablas">Fecha:</td>
              <td width="15%"><?
			$s = "select date_format(current_date, '%d/%m/%Y') as fecha";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
		?>
                &nbsp;&nbsp;
                <input name="fecha" readonly="true" type="text" id="fecha" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$f->fecha ?>" size="13" align="top" />
                <a onClick="limpiar_cajas()" href="../menu/webministator.php" >
                  <input type="hidden" name="folioevaluacion" value="">
                  <input type="hidden" name="guiaguardadav" value="">
                  <input type="hidden" name="idsucursalorigen" value="">
                  <input type="hidden" name="fechaactual" value="">
                  <input type="hidden" name="restringiread" value="">
                  <input type="hidden" style="width:15px" name="convenioaplicado" value="">
                  <input type="hidden" style="width:15px" name="clientedelconvenio" value="">
                  <input type="hidden" style="width:15px" name="sucursaldelconvenio" value="">
                  <input type="hidden" style="width:15px" name="nombrevendedor" value="">
                  <input type="hidden" style="width:15px" name="idvendedor" value="">
                  <input type="hidden" name="creditodisponible" value="">
                </a></td>
              <td width="8%" class="Tablas">Estado:</td>
              <td width="55%" id="estado" style="font:tahoma; font-size:15px; font-weight:bold">&nbsp;</td>
              <td width="12%" ><img src="../img/Boton_Detalle.gif" style="cursor:pointer"
        onClick="abrirVentanaFija('informacionextra.php?tipo=1&folio='+document.all.folioSeleccionado.innerHTML, 625, 418, 'ventana', 'Detalle')"></td>
              <td width="5%" align="right"></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" ><table width="618" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="7%" class="Tablas">T. Flete:</td>
              <td width="14%"><select name="lstflete" style="width:77px; font-size:9px" onChange="solicitarDatosConv(); calculartotales()">
                  <option value="0">Pagado</option>
                  <option value="1">Por Cobrar</option>
              </select></td>
              <td><select name="chocurre" id="chocurre" onChange="solicitarDatosConv(); if(document.all.restringiread.value==1){alerta('El destino tiene restringida la Entrega a Domicilio','¡Atencion!','chocurre'); this.value=1;}else{if(this.value==0){document.all.t_txtead.value = document.all.t_txteadh.value}else{document.all.t_txtead.value = '$ 0.00';}} calculartotales();" style="width:77px; font-size:9px">
                  <option value="0">EAD</option>
                  <option value="1">Ocurre</option>
                </select>
              </td>
              <td width="7%" class="Tablas">Destino:</td>
              <td width="16%"><input type="text" name="destino" readonly="true" id="destino" style="background:#FFFF99;width:100px; font-size:9px" >
                  <input type="hidden" name="destino_hidden">
                  <input type="hidden" name="npobdes">
              </td>
              <td width="10%"><span class="Tablas">Suc. Destino:</span></td>
              <td width="13%"><input name="sucdestino" type="text" id="sucdestino" style="background:#FFFF99;font:tahoma; font-size:9px" readonly value="<?=$destino?>" poblacion="" size="20" />
                  <input type="hidden" name="sucdestino_hidden"></td>
              <td width="9%"><span class="Tablas">Cond. Pago:</span></td>
              <td width="13%">&nbsp;
                  <select name="lstpago" id="lstpago" style="width:70px; font-size:9px">
                    <option value="0">Contado</option>
                    <option value="1">Credito</option>
                </select></td>
              <td width="2%">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="621" border="0" align="center">
            <tr>
              <td width="630"><table width="610" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">
                  <tr>
                    <td width="324" class="FondoTabla">Remitente</td>
                    <td width="280" class="FondoTabla">Destinatario</td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
                        <tr>
                          <td width="16%"><span class="Tablas"># Cliente: </span></td>
                          <td><input name="idremitente" type="text" onKeyPress="if(event.keyCode==13 && this.readOnly==false){ devolverRemitente(this.value)}else{return solonumeros(event)}" style="font:tahoma; font-size:9px" value="<?=$remitente ?>" size="4" />
                            &nbsp;&nbsp;<img id="b_remitente" src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverRemitente', 625, 418, 'ventana', 'Busqueda')" />
                            <input type="hidden" name="rem_personamoral">
                          </td>
                          <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>
                              <input name="rem_rfc" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="24" /></td>
                        </tr>
                        <tr>
                          <td><span class="Tablas">Cliente:</span></td>
                          <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td><input name="rem_cliente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" 
                    value="<?=$rcliente ?>" size="54" />
                                </td>
                                <td align="right" valign="middle"><img id="b_remitente_dir" src="../img/Boton_Agregarchico.gif" alt="Agregar Dirección" style="cursor:hand" onClick="if(document.all.idremitente.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','idremitente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverRemitente('+document.all.idremitente.value+')&idcliente='+document.all.idremitente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}"> </td>
                              </table></td>
                        </tr>
                        <tr>
                          <td><span class="Tablas">Calle:</span></td>
                          <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="153" height="16" id="celda_rem_calle"><input name="rem_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" />
                                    <input type="hidden" name="rem_direcciones"></td>
                                <td width="97"><span class="Tablas">Numero: </span><span class="Tablas">
                                  <input name="rem_numero" type="text" readonly="true" style=" width:50px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td><span class="Tablas">CP:</span></td>
                          <td width="29%"><input name="rem_cp" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcp ?>" size="15" /></td>
                          <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;
                                <input name="rem_colonia" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcolonia ?>" size="24" />
                          </span></td>
                        </tr>
                        <tr>
                          <td><span class="Tablas">Poblaci&oacute;n:</span></td>
                          <td colspan="4"><input name="rem_poblacion" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rpoblacion ?>" size="25" />
                              <span class="Tablas">&nbsp;Tel&eacute;fono:
                                <input name="rem_telefono" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rtelefono ?>" size="14" />
                            </span></td>
                        </tr>
                    </table></td>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
                        <tr>
                          <td><input name="iddestinatario" onKeyPress="if(event.keyCode==13 && this.readOnly==false){devolverDestinatario(this.value)}else{return solonumeros(event)}" type="text" style="font:tahoma; font-size:9px" value="<?=$remitente ?>" size="4" />
                            &nbsp;&nbsp;<img id="b_destinatario" src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=devolverDestinatario', 625, 418, 'ventana', 'Busqueda')"/>
                            <input type="hidden" name="des_personamoral">
                            <input type="hidden" name="paraconveniotxt" value="0">
                          </td>
                          <td width="55%" colspan="3">&nbsp;&nbsp;<span class="Tablas">R.F.C.:</span>
                              <input name="des_rfc" type="text" readonly="true" id="rrfc22" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="24" /></td>
                        </tr>
                        <tr>
                          <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td><input name="des_cliente" readonly="true" type="text" 
                  style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcliente ?>" size="54" />
                                </td>
                                <td align="right" valign="middle"><img id="b_destinatario_dir" src="../img/Boton_Agregarchico.gif" alt="Agregar Dirección" style="cursor:hand" onClick="if(document.all.iddestinatario.value==''){ alerta('Proporcione el id del remitente','¡Atencion!','iddestinatario') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverDestinatario('+document.all.iddestinatario.value+')&idcliente='+document.all.iddestinatario.value, 460, 395, 'ventana', 'DATOS DIRECCION')}"> </td>
                              </table></td>
                        </tr>
                        <tr>
                          <td colspan="4"><table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="153" height="16" id="celda_des_calle"><input name="des_calle" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" size="35" />
                                    <input type="hidden" name="des_direcciones"></td>
                                <td width="97"><span class="Tablas">Numero: </span><span class="Tablas">
                                  <input name="des_numero" type="text" readonly="true" style=" width:50px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td width="29%"><input name="des_cp" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcp ?>" size="15" /></td>
                          <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;
                                <input name="des_colonia" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcolonia ?>" size="24" />
                          </span></td>
                        </tr>
                        <tr>
                          <td colspan="4"><input name="des_poblacion" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rpoblacion ?>" size="25" />
                              <span class="Tablas">&nbsp;Tel&eacute;fono:
                                <input name="des_telefono" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rtelefono ?>" size="14" />
                            </span></td>
                        </tr>
                    </table></td>
                  </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="618" border="0" align="center">
            <tr>
              <td width="436"><table border="0" cellpadding="0" cellspacing="0" id="tablaconteva">
              </table></td>
              <td width="188"><table width="177" height="90" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="194"><table width="177" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">
                        <tr>
                          <td width="172" class="FondoTabla">Tiempo de Entrega </td>
                        </tr>
                        <tr>
                          <td><table width="163" height="0" align="center" bordercolor="#016193">
                              <tr>
                                <td width="41" class="Tablas">Ocurre:</td>
                                <td width="40"><input name="txtocu" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$drfc ?>" size="5" />
                                </td>
                                <td width="28" class="Tablas">EAD:</td>
                                <td width="34"><input name="txtead" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$drfc ?>" size="5" />
                                    <input name="txteadh" type="hidden" /></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table width="176" height="0" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">
                        <tr>
                          <td width="200" class="FondoTabla">Restricciones</td>
                        </tr>
                        <tr>
                          <td><label>
                            <textarea name="txtrestrinccion" readonly style="width:170px; font-size:9px; background-color:#FFFF99; text-transform:uppercase"></textarea>
                            <input name="txtrestrinccionh" type="hidden" />
                          </label></td>
                        </tr>
                    </table></td>
                  </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="325" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="56" class="Tablas">T. Paquetes: </td>
              <td width="43" class="Tablas"><input name="totalpaquetes" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:40px" value="<?=$rcp ?>"/></td>
              <td width="51" class="Tablas">T. Peso Kg: </td>
              <td width="48" class="Tablas"><input name="totalpeso" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:40px" value="<?=$rcp ?>" /></td>
              <td width="61" class="Tablas">T. Volumen: </td>
              <td width="66" class="Tablas"><input name="totalvolumen" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; width:60px" value="<?=$rcp ?>" /></td>
            </tr>
        </table></td>
        <td width="49%" rowspan="2"><table width="302" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#74B051">
            <tr>
              <td width="257" bgcolor="#74B051"><span class="Estilo2">TOTALES</span>
                  <input type="hidden" name="pc_ead">
                  <input type="hidden" name="pc_recoleccion">
                  <input type="hidden" name="pc_porcada">
                  <input type="hidden" name="pc_costo">
                  <input type="hidden" name="pc_tarifacombustible">
                  <input type="hidden" name="pc_iva">
                  <input type="hidden" name="pc_ivaretenido">
                  <input type="hidden" name="pc_maximodescuento">
                  <input type="hidden" name="pc_pesominimodesc">
                  <input type="hidden" name="desead">
                  <input type="hidden" name="desrrecoleccion">
                  <input type="hidden" name="desporcobrar">
                  <input type="hidden" name="desconvenio">
              </td>
            </tr>
            <tr>
              <td height="140"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="Tablas">&nbsp;&nbsp;Flete:</td>
                    <td class="Tablas"><input name="flete" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                    <td class="Tablas">Excedente:</td>
                    <td class="Tablas"><input name="t_txtexcedente" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                  </tr>
                  <tr>
                    <td class="Tablas">&nbsp;&nbsp;Descuento:</td>
                    <td class="Tablas"><input readonly="true" name="t_txtdescuento1" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="2" onKeyPress="if(event.keyCode==13 && this.readOnly==false){ if(parseFloat(this.value)>parseFloat(document.all.pc_maximodescuento.value)){ this.value=document.all.pc_maximodescuento.value; alerta('El maximo descuento permitido es '+document.all.pc_maximodescuento.value+' %','¡Atencion!','t_txtdescuento1')} calcularDescuento()}else{return solonumeros(event);}" />
                        <input name="t_txtdescuento2" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="4" />
                        <img id="img_descuento" src="../img/update.gif" onClick="if(validarDescuento()){ abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=permitirDescuento', 370, 500, 'ventana', 'Inicio de Sesión Secundaria');}" style="cursor:hand"></td>
                    <td class="Tablas">Combustible:</td>
                    <td class="Tablas"><input name="t_txtcombustible" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                  </tr>
                  <tr>
                    <td class="Tablas">&nbsp;&nbsp;EAD:</td>
                    <td class="Tablas"><input readonly="true" name="t_txtead" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" />
                        <input name="t_txteadh" type="hidden" />
                      <input name="t_txteadh2" type="hidden" /></td>
                    <td class="Tablas">Subtotal:</td>
                    <td class="Tablas"><input name="t_txtsubtotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                  </tr>
                  <tr>
                    <td class="Tablas">&nbsp;&nbsp;Recolecci&oacute;n:</td>
                    <td class="Tablas"><input readonly="true" name="t_txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" />
                        <input name="t_txtrecoleccionh" type="hidden" /></td>
                    <td class="Tablas">IVA:</td>
                    <td class="Tablas"><input name="t_txtiva" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                  </tr>
                  <tr>
                    <td width="24%" class="Tablas">&nbsp;&nbsp;Seguro:</td>
                    <td class="Tablas"><input readonly="true" name="t_txtseguro" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                    <td width="23%" class="Tablas">IVA Retenido: </td>
                    <td width="17%" class="Tablas"><input name="t_txtivaretenido" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                  </tr>
                  <tr>
                    <td class="Tablas">&nbsp;&nbsp;Otros:</td>
                    <td class="Tablas"><input name="t_txtotros" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                    <td class="Tablas">Total:</td>
                    <td class="Tablas"><input name="t_txttotal" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="Tablas" valign="middle"><input type="hidden" value="0" name="pagoregistrado">
                        <input type="hidden" value="" name="efectivo">
                        <input type="hidden" value="" name="cheque">
                        <input type="hidden" value="" name="ncheque">
                        <input type="hidden" value="" name="banco">
                        <input type="hidden" value="" name="tarjeta">
                        <input type="hidden" value="" name="transferencia">
                        <input type="hidden" value="" name="pagominimocheque"></td>
                    <td class="Tablas"><input type="hidden" name="motivocancelacion"></td>
                    <td class="Tablas">&nbsp;</td>
                  </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td width="51%" ><table border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="318"><table width="315" height="140" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">
                  <tr>
                    <td width="434" class="FondoTabla">Servicios</td>
                  </tr>
                  <tr>
                    <td valign="top"><table width="100%" height="76" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="6%"><input name="chkemplaye" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtemplaye.value='';}else{document.all.txtemplaye.value = document.all.txtemplayeh.value} calculartotales();" /></td>
                          <td class="Tablas">Emplaye
                            <input name="txtemplaye" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />
                              <input name="txtemplayeh" type="hidden" />
                          </td>
                          <td class="Tablas"><input name="chkacuserecibo" onClick="if(!this.checked){document.all.txtacuserecibo.value='';}else{document.all.txtacuserecibo.value=document.all.txtacusereciboh.value;} calculartotales();" type="checkbox" style="width:8px; height:9px" value="SI" /></td>
                          <td class="Tablas">Acuse Recibo</td>
                          <td class="Tablas" align="right"><input readonly="true" name="txtacuserecibo" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtacusereciboh" type="hidden" />
                          </td>
                        </tr>
                        <tr>
                          <td><input name="chkbolsaempaque" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtbolsaempaque1.value = ''; document.all.txtbolsaempaque2.value = ''; document.all.txtbolsaempaque1.readOnly=true; document.all.txtbolsaempaque1.style.backgroundColor='#FFFF99';}else{ if(document.all.txtbolsaempaque1h.value=='' || document.all.txtbolsaempaque1h.value=='0'){document.all.txtbolsaempaque1.readOnly=false; document.all.txtbolsaempaque1.style.backgroundColor='#FFFFFF';}else{document.all.txtbolsaempaque1.value = document.all.txtbolsaempaque1h.value; document.all.txtbolsaempaque2.value = document.all.txtbolsaempaque2h.value;}} calculartotales();" /></td>
                          <td width="49%" class="Tablas">Bolsa Empaque
                            <input name="txtbolsaempaque1" readonly="true" onBlur="if(this.readOnly==false){calculartotales();}" onKeyPress="if(this.readOnly==false && event.keyCode==13){document.all.txtbolsaempaque2.value='$ '+numcredvar((parseFloat((document.all.txtbolsaempaque3h.value=='')?'0':document.all.txtbolsaempaque3h.value.replace('$ ', '').replace(/,/g,''))*parseFloat(this.value)).toLocaleString());calculartotales();}else{return solonumeros(event);}" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="1" />
                              <input name="txtbolsaempaque2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="6" />
                              <input name="txtbolsaempaque1h" type="hidden" />
                            <input name="txtbolsaempaque2h" type="hidden" />
                            <input name="txtbolsaempaque3h" type="hidden" />
                          </td>
                          <td width="5%" class="Tablas"><input name="chkcod" onClick="if(!this.checked){document.all.txtcod.value='';}else{document.all.txtcod.value=document.all.txtcodh.value;} calculartotales();" type="checkbox" style="width:8px; height:8px" value="SI" />
                          </td>
                          <td width="19%" class="Tablas">COD</td>
                          <td width="21%" class="Tablas" align="right"><input readonly="true" name="txtcod" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtcodh" type="hidden" />
                          </td>
                        </tr>
                        <tr>
                          <td><input name="chkavisocelular" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtavisocelular2.readOnly=true; document.all.txtavisocelular2.style.backgroundColor='#FFFF99'; document.all.txtavisocelular2.value='';document.all.txtavisocelular1.value=''; }else{document.all.txtavisocelular1.value=document.all.txtavisocelular1h.value;document.all.txtavisocelular2.readOnly=false; document.all.txtavisocelular2.style.backgroundColor='#FFFFFF'; document.all.txtavisocelular2.value=document.all.txtavisocelular2h.value;document.all.txtavisocelular2.focus();}  calculartotales();" /></td>
                          <td colspan="4" class="Tablas">Aviso Celular
                            <input name="txtavisocelular1" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" size="10" />
                              <input name="txtavisocelular1h" type="hidden" />
                              <input name="txtavisocelular2" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rrfc ?>" size="10" />
                            <input name="txtavisocelular2h" type="hidden" /></td>
                        </tr>
                        <tr>
                          <td><input name="chkvalordeclarado" type="checkbox" style="width:8px; height:8px" value="SI"
               onClick="if(!this.checked){document.all.txtdeclarado.value='';document.all.txtdeclarado.readOnly=true; document.all.txtdeclarado.style.backgroundColor='#FFFF99'; document.all.txtdeclarado.readOnly=true;}else{document.all.txtdeclarado.readOnly=false; document.all.txtdeclarado.style.backgroundColor='#FFFFFF'; document.all.txtdeclarado.readOnly=false;document.all.txtdeclarado.focus();} calculartotales();" /></td>
                          <td class="Tablas">Valor Declarado
                            <?
			  	$s = "SELECT maxvalordeclaradoguia FROM configuradorgeneral";
				$rmvd = mysql_query($s,$l) or die($s);
				$fmvd = mysql_fetch_object($rmvd);
			  ?>
                              <input name="txtdeclarado" type="text" readonly="true" onBlur="if(this.readOnly==false){this.value=this.value.replace('$ ','').replace(/,/,'');  if(this.value==''){this.value='$ 0.00';}else{ if(parseFloat(this.value) > <?=$fmvd->maxvalordeclaradoguia?>){this.value = <?=$fmvd->maxvalordeclaradoguia?>; alerta3('El maximo valor declarado permitido es <?=$fmvd->maxvalordeclaradoguia?>', '¡Atencion!');} this.value='$ '+numcredvar(this.value); calculartotales(); }}" onKeyPress="if(this.readOnly==false){ if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/,'')); calculartotales();}else{return solonumeros(event);}} " style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                          <td class="Tablas"><input name="chkrecoleccion" type="checkbox" onClick="if(!this.checked){document.all.txtrecoleccion.value=''; document.all.t_txtrecoleccion.value=''; }else{document.all.txtrecoleccion.value=document.all.txtrecoleccionh.value; } calculartotales();" id="chocurre24" style="width:8px; height:8px" value="SI" /></td>
                          <td class="Tablas">Recolecci&oacute;n</td>
                          <td class="Tablas" align="right"><input readonly="true" name="txtrecoleccion" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtrecoleccionh" type="hidden" />
                          </td>
                        </tr>
                    </table></td>
                  </tr>
              </table></td>
              <td width="5"></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="623" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="339"><table width="328" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">
                  <tr>
                    <td width="377" class="FondoTabla">Observaciones</td>
                  </tr>
                  <tr>
                    <td><textarea name="txtobservaciones" style="width:320px; font-size:9px; font:tahoma; text-transform:uppercase"></textarea></td>
                  </tr>
              </table></td>
              <td width="284" align="center">
              	<? if($_GET[desde]==1){ ?>
                	<img id="botonguardar" src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="guardarNuevaGuia();">&nbsp;
					<img id="botonimprimir" src="../img/Boton_Imprimir.gif" onClick="elegirImpresora()" style="cursor:hand; display:none">
                <? } ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><table width="616" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td colspan="6" class="FondoTabla">Datos Entrega</td>
                  </tr>
                  <tr>
                    <td width="50" class="Tablas">Recibio</td>
                    <td width="211" class="Tablas"><input type="text" name="recibio" readonly="true" id="recibio" style="background:#FFFF99;width:200px; font-size:9px" ></td>
                    <td width="75" class="Tablas">Fecha Entrega</td>
                    <td width="122" class="Tablas"><input type="text" name="fechaentrega" readonly="true" id="recibio" style="background:#FFFF99;width:110px; font-size:9px" ></td>
                    <td width="38" class="Tablas">Factura</td>
                    <td width="120" class="Tablas"><input type="text" name="factura" readonly="true" id="recibio" style="background:#FFFF99;width:110px; font-size:9px" ></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="2" align="center">
              <? if($_GET[desde]!=1){ ?>
              	<table width="99" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td><div id="boton_autorizar" class="ebtn_autorizar" onClick="autorizarSustitucion()"></div></td>
                        <td><div id="boton_noautorizar" class="ebtn_noautorizar" onClick="noautorizar()"></div></td>
                    </tr>
                </table>
              <? }else{ ?>
              
              <? } ?>
              </td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" align="center"></td>
      </tr>
      <tr>
        <td colspan="2"><table width="624" border="0" align="center">
            <tr>
              <td width="618"></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
    </table></td>
  </tr>
</table>
</center>
</form>
</body>
<script>
	<?
		if($_GET[funcion]!=""){
			echo 'setTimeout("'.str_replace("\'","'",$_GET[funcion]).'",700);';
		}
	?>
	
</script>
</html>
