<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT eti_nombre1, eti_nombre2, eti_direccion, eti_colonia,eti_ciudad, eti_rfc FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$cadena = $f->eti_nombre1."|".$f->eti_nombre2."|".$f->eti_direccion."|".$f->eti_colonia."|".$f->eti_ciudad."|".$f->eti_rfc;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Guía ventanilla | PMM</title>

<link href="../css_ne/reseter.css" rel="stylesheet" type="text/css" />
<link href="../css_ne/style.css" rel="stylesheet" type="text/css" />

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">
<link href="../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<!-- funciones para ajax -->
<script type="text/javascript" src="../javascript/funciones.js"></script>
<script type="text/javascript" src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../javascript/DataSet.js"></script>
<script type="text/javascript" src="../convenio/validacionesConvenio.js"></script>
<!--OBJECT ID="Metodos" style="visibility:hidden"
CLASSID="CLSID:21B8DA59-7F02-40B9-A5E9-FC848C3DB134"
CODEBASE="http://www.pmmintranet.net/web/activexs/Impresion.CAB#version=1,1,0,0">
</OBJECT-->
		<OBJECT ID="Etiqueta"
CLASSID="CLSID:0124E5BC-E21C-4A00-B1F2-ED81FDBD9D40"
CODEBASE="../activexs/ImpEtiqueta.CAB#version=24,0,0,0">
</OBJECT>
<!--[if lte IE 6]><link href="css/styleie6.css" rel="stylesheet" type="text/css" /><![endif]--> 
<script>
	<?
		$permitido = true;
	
		$s = "SELECT id,prefijo FROM catalogosucursal";
		$r = mysql_query($s,$l) or die($s);
		$var = "";
		while($f = mysql_fetch_object($r)){
			$var .= (($var!="")?",":"")."'$f->id':'$f->prefijo'";
		}	

		$s = "SELECT impetiquetasguias,impetiquetaspaquetes FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
		$rxy = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($rxy)>0){
			$fxy = mysql_fetch_object($rxy);
		}else{
			$s = "SELECT impetiquetasguias,impetiquetaspaquetes FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
			$rxy = mysql_query($s,$l) or die($s);
			$fxy = mysql_fetch_object($rxy);
		}
	?>
	var obtenerPrefijos = eval("({<?=$var?>})");

	var u = document.all;
	var sucursalorigen 	= 0;
	var valCon = new validacionesConvenio();
	var tabla1 = new ClaseTabla();
	var cantidadVencida = 0;
	var folioimprimir = "";
	var tipoClienteBuscado = "";
	var dirRemi = null;
	var dirDest = null;
	
	tabla1.setAttributes({
		nombre:"tablaconteva",
		campos:[
			{nombre:"IDM", medida:4, alineacion:"left", tipo:"oculto", datos:"idmercancia"},
			{nombre:"Cant", medida:69, alineacion:"left", datos:"cantidad"},
			{nombre:"Descripcion", medida:144, alineacion:"left", datos:"descripcion"},
			{nombre:"Contenido", medida:144, alineacion:"left", datos:"contenido"},
			{nombre:"Peso", medida:69, alineacion:"right", datos:"peso"},
			{nombre:"Vol", medida:72, alineacion:"right", datos:"volumen"},
			{nombre:"Importe", medida:98, tipo:"moneda", alineacion:"right", datos:"importe"},
			{nombre:"M", medida:14, alineacion:"right", datos:"modificable"}
		],
		filasInicial:6,
		alto:100,
		seleccion:true,
		eventoDblClickFila:"paracambiarvalor(tabla1.getSelectedRow().modificable);",
		ordenable:true,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		<?
		$_GET[funcion2] = str_replace("\'","'",$_GET[funcion2]);
		if($_GET[funcion2]!=""){
			echo 'setTimeout("'.$_GET[funcion2].'",1500);';
			}
		?>
	}
	
	var botonnuevo	 = '<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesnuevo = '<img src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="if(validarDatos() && valcreditodisponible()){ejecutarSubmit();};">&nbsp;&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesdesguardar = '<img src="../img/Boton_Imprimir.gif" onclick="imprimirDespues()">&nbsp;&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">';
	var botonesconsulta = '<img src="../img/Boton_Imprimir.gif" onclick="imprimirDespues()">&nbsp;<img src="../img/Boton_Liberar.gif" style="cursor:hand" onClick="cancelarGuia()">&nbsp;<img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();bloquear(false);">';
	
	function imprimirDespues(){
		<?=$cpermiso->verificarPermiso(357,$_SESSION[IDUSUARIO]);?>;
		//if(document.all.estado.innerHTML=='ENTREGADA'){
			//window.open("imprimiretiquetaguia_acuse.php?tipo=1&codigo="+folioimprimir,"1","width=3 height=3 left=2500 top=1500");
		//}else{
			abrirVentanaFija('../buscadores_generales/impresionGuia.php?tipo=E&folio='+
							 folioimprimir, 300, 290, 'ventana', 'Impresión');
		//}
	}
	
	function imprimirDocumentos(valor,folio){
		var detalle = "";
		for(var i=0; i<tabla1.getRecordCount(); i++){
			detalle += document.all['tablaconteva_Cant'][i].value+"|"+
			document.all['tablaconteva_Descripcion'][i].value+"|"+
			document.all['tablaconteva_Contenido'][i].value+"|"+""+"|"+
			document.all['tablaconteva_Peso'][i].value+"|"+
			document.all['tablaconteva_Vol'][i].value+"|";
		}
		
		var etreco="0";
		
		if(document.all.txtrecoleccion.value != 0 && document.all.txtrecoleccion.value != ""){
			var etreco = document.all.txtrecoleccion.value;
		}
		
		
		detalle = detalle.substring(0,detalle.length-1);
		//Etiqueta.Impresora = "<?=$impresora?>";
		Etiqueta.Impresora_Guias = "<?=str_replace("\\","\\\\",$fxy->impetiquetasguias)?>";
		Etiqueta.Impresora_Paquetes = "<?=str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)?>";
		//Etiqueta.Impresora_Paquetes = "ZDesigner TLP 2844";
		//Etiqueta.Impresora_Guias = "ZDesigner TLP 2844";    
		
		Etiqueta.Datos_Paqueteria = "<?=$cadena ?>";
		Etiqueta.Datos_Paquetes = document.getElementById('folioSeleccionado').innerHTML+"|<?=date('d/m/Y')?>|"+u.totalpaquetes.value+" P.VOL: "+u.totalvolumen.value+" P. KG: "+u.totalpeso.value;
		Etiqueta.Contenido_Paquetes = detalle;
		Etiqueta.Datos_Remitente = u.rem_cliente.value+"|CTE: "+u.idremitente.value+"    RFC: "+u.rem_rfc.value+"|"+u.rem_calle.value+"|"+u.rem_colonia.value+" "+u.rem_numero.value+"C.P. "+u.rem_cp.value+"|"+u.rem_poblacion.value+"|TEL:"+u.rem_telefono.value;
		Etiqueta.Datos_Destinatario = u.des_cliente.value+"|CTE: "+u.iddestinatario.value+"    RFC: "+u.des_rfc.value+"|"+u.des_calle.value+"|"+u.des_colonia.value+" "+u.des_numero.value+"C.P. "+u.des_cp.value+"|"+u.des_poblacion.value+"|TEL:"+u.des_telefono.value;	
		//Etiqueta.Datos_Remitente = "Gnt Val s De Rl De Cv|CTE: 17372    RFC: GNT090220396|Av. La Calma 3775 NO 0|ARBOLEDAS 1A SECC C.P. 45070|ZAPOPAN|TEL:"
	    //Etiqueta.Datos_Destinatario = "MARIA EUGENIA SEGOVIA|23424    RFC:  |BURGOS 4113 |LAS TORRES C.P. 64103|MONTERREY |SIN SECTOR   TEL: 018183658175 "
		
		
		var folio = document.getElementById('folioSeleccionado').innerHTML;
		
		Etiqueta.Datos_Guia = obtenerPrefijos[u.idsucursalorigen.value]+"|"+obtenerPrefijos[document.all.sucdestino_hidden.value]+"|TIPO DE ENTREGA: "+((u.chocurre.value==0)?"EAD":"OCURRE")+"|TIPO DE FLETE: PAGADA"+
		"|VALOR DECLARADO: "+((u.txtdeclarado.value=="")?"$ 0.00":u.txtdeclarado.value)+
		"|CONDICION DE PAGO: "+((u.tipopago.value==0)?'CONTADO':'CREDITO')+"|DOCUMENTO: <?=$_SESSION[NOMBREUSUARIO]?>|"+etreco;
		
		
		var servicios = 0;
		servicios += parseFloat(u.t_txtead.value.replace(/,/g,"").replace("$ ",""));
		servicios += parseFloat(u.t_txtrecoleccion.value.replace(/,/g,"").replace("$ ",""));
		servicios += parseFloat(u.t_txtotros.value.replace(/,/g,"").replace("$ ",""));
		servicios += parseFloat(u.t_txtexcedente.value.replace(/,/g,"").replace("$ ",""));
		servicios += parseFloat(u.t_txtcombustible.value.replace(/,/g,"").replace("$ ",""));
		
		var folio = document.getElementById('folioSeleccionado').innerHTML;
			
			Etiqueta.Datos_Totales = u.txtobservaciones.value + "|" + u.flete.value.replace(/,/g,"").replace("$ ","")
			 + "|" + u.t_txtdescuento2.value.replace(/,/g,"").replace("$ ","") +"|"+servicios.toFixed(2)+"|"+u.t_txtseguro.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtsubtotal.value.replace(/,/g,"").replace("$ ","")+"|"+u.t_txtiva.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtivaretenido.value.replace(/,/g,"").replace("$ ","")+"|"+u.t_txttotal.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtead.value.replace(/,/g,"").replace("$ ","")+"|"+u.t_txtrecoleccion.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtotros.value.replace(/,/g,"").replace("$ ","")+"|"+u.t_txtexcedente.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtcombustible.value.replace(/,/g,"").replace("$ ","")+"|"+servicios.toFixed(2)+"|"+document.getElementById('numeroRastreo').innerHTML;
			 
			Etiqueta.CargarDatos();
			//Etiqueta.ImprimirGuia();
			//Etiqueta.ImprimirPaquetes();
		
		if(valor==1 || valor==3  || valor==5){
			Etiqueta.ImprimirGuia();
		}
		if(valor==2 || valor==3  || valor==4 || valor==5){
			Etiqueta.ImprimirPaquetes();
		}
	}
	
	
	
	function imprimir(){
		var detalle = "";
		for(var i=0; i<tabla1.getRecordCount(); i++){
			detalle += document.all['tablaconteva_Cant'][i].value+"|"+
			document.all['tablaconteva_Descripcion'][i].value+"|"+
			document.all['tablaconteva_Contenido'][i].value+"|"+""+"|"+
			document.all['tablaconteva_Peso'][i].value+"|"+
			document.all['tablaconteva_Vol'][i].value+"|";
		}
		
		var etreco="0";
		
		if(document.all.txtrecoleccion.value != 0 && document.all.txtrecoleccion.value != ""){
			var etreco = document.all.txtrecoleccion.value;
		}
		
		
		detalle = detalle.substring(0,detalle.length-1);
		
		//Etiqueta.Impresora = "<?=$impresora?>";
		Etiqueta.Impresora_Guias = "<?=str_replace("\\","\\\\",$fxy->impetiquetasguias)?>";
		Etiqueta.Impresora_Paquetes = "<?=str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)?>";
		
		
		Etiqueta.Datos_Paqueteria = "<?=$cadena ?>";
		Etiqueta.Datos_Paquetes = document.getElementById('folioSeleccionado').innerHTML+"|<?=date('d/m/Y')?>|"+u.totalpaquetes.value+" P.VOL: "+u.totalvolumen.value+" P. KG: "+u.totalpeso.value;
		Etiqueta.Contenido_Paquetes = detalle;
		Etiqueta.Datos_Remitente = u.rem_cliente.value+"|CTE: "+u.idremitente.value+"    RFC: "+u.rem_rfc.value+"|"+u.rem_calle.value+"|"+u.rem_colonia.value+" "+u.rem_numero.value+"C.P. "+u.rem_cp.value+"|"+u.rem_poblacion.value+"|TEL:"+u.rem_telefono.value;
		Etiqueta.Datos_Destinatario = u.des_cliente.value+"|CTE: "+u.iddestinatario.value+"    RFC: "+u.des_rfc.value+"|"+u.des_calle.value+"|"+u.des_colonia.value+" "+u.des_numero.value+"C.P. "+u.des_cp.value+"|"+u.des_poblacion.value+"|TEL:"+u.des_telefono.value;	
		
		var folio = document.getElementById('folioSeleccionado').innerHTML;
		
		
		Etiqueta.Datos_Guia = obtenerPrefijos[<?=$_SESSION[IDSUCURSAL]?>]+"|"+obtenerPrefijos[document.all.sucdestino_hidden.value]+"|TIPO DE ENTREGA: "+((u.chocurre.value==0)?"EAD":"OCURRE")+"|TIPO DE FLETE: PAGADA"+
		"|VALOR DECLARADO: "+((u.txtdeclarado.value=="")?"$ 0.00":u.txtdeclarado.value)+
		"|CONDICION DE PAGO: "+((u.tipopago.value==0)?'CONTADO':'CREDITO')+"|DOCUMENTO: <?=$_SESSION[NOMBREUSUARIO]?>|"+etreco;
		
		
		var servicios = 0;
		servicios += parseFloat(u.t_txtead.value.replace(/,/g,"").replace("$ ",""));
		servicios += parseFloat(u.t_txtrecoleccion.value.replace(/,/g,"").replace("$ ",""));
		servicios += parseFloat(u.t_txtotros.value.replace(/,/g,"").replace("$ ",""));
		servicios += parseFloat(u.t_txtexcedente.value.replace(/,/g,"").replace("$ ",""));
		servicios += parseFloat(u.t_txtcombustible.value.replace(/,/g,"").replace("$ ",""));
		
		if(folio.substring(0,3)=='777' || folio.substring(0,3)=='888'){
			Etiqueta.Datos_Totales = "N/A|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|N/A";
		}else{
			Etiqueta.Datos_Totales = u.txtobservaciones.value + "|" + u.flete.value.replace(/,/g,"").replace("$ ","")
			 + "|" + u.t_txtdescuento2.value.replace(/,/g,"").replace("$ ","") +"|"+servicios.toFixed(2)+"|"+u.t_txtseguro.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtsubtotal.value.replace(/,/g,"").replace("$ ","")+"|"+u.t_txtiva.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtivaretenido.value.replace(/,/g,"").replace("$ ","")+"|"+u.t_txttotal.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtead.value.replace(/,/g,"").replace("$ ","")+"|"+u.t_txtrecoleccion.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtotros.value.replace(/,/g,"").replace("$ ","")+"|"+u.t_txtexcedente.value.replace(/,/g,"").replace("$ ","")
			 +"|"+u.t_txtcombustible.value.replace(/,/g,"").replace("$ ","")+"|"+servicios.toFixed(2)+"|"+document.getElementById('numeroRastreo').innerHTML;
		}

		
		//Etiqueta.Datos_Totales = u.txtobservaciones.value + "|" + u.flete.value + "|" + u.t_txtdescuento2.value +"|"+servicios+"|"+u.t_txtseguro.value+"|"+u.t_txtsubtotal.value+"|"+u.t_txtiva.value+"|"+u.t_txtivaretenido.value+"|"+u.t_txttotal.value+"|"+u.t_txtead.value+"|"+u.t_txtrecoleccion.value+"|"+u.t_txtotros.value+"|"+u.t_txtexcedente.value+"|"+u.t_txtcombustible.value+"|"+servicios+"|";
		
			Etiqueta.CargarDatos();
			Etiqueta.ImprimirGuia();
			Etiqueta.ImprimirPaquetes();
	}
	
	
	
	
	//para cambiar descripciones convenios
	function solicitarDatosConv(){
		if(document.all.sucdestino_hidden.value!=""){
			consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idpagina="+u.idpagina.value+"&idremitente="+u.idremitente.value
			+"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&idsucdestino="+u.sucdestino_hidden.value
			+"&valran="+Math.random());
		}
	}
	function paraConvenio(datos){
		var u = document.all;
		valCon.setDatos(datos);
		consultaTexto('obtenerMecanciaConvenio',"guiasempresariales_consulta_conv.php?accion=1&idpagina="+u.idpagina.value+"&idsucdestino="+u.sucdestino_hidden.value
					  +"&fevaluacion="+u.folioevaluacion.value
				   +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(0)+
				   "&folioempresarial="+u.folioSeleccionado.innerHTML+"&rd="+Math.random());
	}
	function obtenerMecanciaConvenio(datos){
		document.getElementById('t_txtexcedente').style.color = "#000000";
		document.getElementById('t_txtexcedente').style.fontWeight="normal";
		var u = document.all;
		u.t_txtexcedente.value = "$ 0.00";
		datos = datos.replace(/&#209;/g,"Ñ");
		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));
		u.t_txtexcedente.value = "$ "+numcredvar(objeto[0].excedente.toLocaleString());
		u.t_txteadh2.value 	= "0";
		u.txtrestrinccion.value = u.txtrestrinccionh.value;
		u.txtead.value			= u.txteadh.value;
		
		if(valCon.getValorDeclarado(0)!=null){
			var vdec = valCon.getValorDeclarado(0);
			u.pc_porcada.value 		= (vdec.porcada!=null && vdec.porcada!="" && vdec.porcada!="0")?vdec.porcada:u.pc_porcadah.value;
			u.pc_costo.value 		= (vdec.costo!=null && vdec.costo!="" && vdec.costo!="0")?vdec.costo:u.pc_costoh.value;
			u.pc_vdcostoextra.value = (vdec.costoextra!=null && vdec.costoextra!="" && vdec.costoextra!="0")?vdec.costoextra:u.pc_vdcostoextrah.value;
			u.pc_vdlimite.value 	= (vdec.limite!=null && vdec.limite!="" && vdec.limite!="0")?vdec.limite:u.pc_vdlimiteh.value;
		}else{
			u.pc_porcada.value 		= u.pc_porcadah.value;
			u.pc_costo.value 		= u.pc_costoh.value;
			u.pc_vdcostoextra.value = u.pc_vdcostoextrah.value;
			u.pc_vdlimite.value 	= u.pc_vdlimiteh.value;
		}
		
		var filas = objeto[0].mercancia;
		tabla1.setJsonData(filas);
		
		var importetotal = 0;
		//para convenio precio caja. x son modificables
		var cantidad_con_x = 0;
		var cantidad_sin_x = 0;
		for(var i=0; i<filas.length; i++){
			importetotal += parseFloat(filas[i].importe);
			if(filas[i].modificable=="X"){
				cantidad_con_x += parseFloat(filas[i].importe);
			}else{
				cantidad_sin_x += parseFloat(filas[i].importe);
			}
		}
		
		if(objeto[0].flete!="0"){
			importetotal = objeto[0].flete;
			//cantidad_sin_x = objeto[0].flete;
			cantidad_con_x = objeto[0].flete;
		}
		
		if(valCon.validaConsignacionCaja(0)>0){
			if(valCon.validaConsignacionDescuento(0)>0 && cantidad_con_x>0){
				var totalmasdesc = cantidad_con_x*100/(100-valCon.validaConsignacionDescuento(0));
				u.flete.value = "$ "+numcredvar((totalmasdesc + cantidad_sin_x).toLocaleString());
				u.t_txtdescuento1.value = valCon.validaConsignacionDescuento(0);
				u.t_txtdescuento2.value = ((valCon.validaConsignacionDescuento(0)/100)*totalmasdesc).toFixed(2);
				//u.img_descuento.style.visibility="hidden";
			}else{
				u.flete.value = "$ "+numcredvar(importetotal.toLocaleString());
				u.t_txtdescuento1.value = "0 %";
				u.t_txtdescuento2.value = "$ 0.00";
				//u.img_descuento.style.visibility="visible";
			}
		}else{
			if(valCon.validaConsignacionDescuento(0)>0){
				var totalmasdesc = importetotal*100/(100-valCon.validaConsignacionDescuento(0));
				u.flete.value = "$ "+numcredvar(totalmasdesc.toLocaleString());
				u.t_txtdescuento1.value = valCon.validaConsignacionDescuento(0);
				u.t_txtdescuento2.value = ((valCon.validaConsignacionDescuento(0)/100)*totalmasdesc).toFixed(2);
				//u.img_descuento.style.visibility="hidden";
			}else{
				u.flete.value = "$ "+numcredvar(importetotal.toLocaleString());
				u.t_txtdescuento1.value = "0 %";
				u.t_txtdescuento2.value = "$ 0.00";
				//u.img_descuento.style.visibility="visible";
			}
		}
		
		/*if(valCon.validaConsignacionDescuento()>0){
			var totalmasdesc = importetotal*100/(100-valCon.validaConsignacionDescuento());
			u.flete.value = "$ "+numcredvar(totalmasdesc.toLocaleString());
			u.t_txtdescuento1.value = valCon.validaConsignacionDescuento();
			var totalx = (valCon.validaConsignacionDescuento()/100)*totalmasdesc
			u.t_txtdescuento2.value = "$ "+numcredvar(totalx.toFixed(2).toString());
			//u.img_descuento.style.visibility="hidden";
		}else{
			u.flete.value = "$ "+numcredvar(importetotal.toLocaleString());
			u.t_txtdescuento1.value = "0 %";
			u.t_txtdescuento2.value = "$ 0.00";
			//u.img_descuento.style.visibility="visible";
		}*/
		
		u.chkvalordeclarado.disabled	= false;
		if(u.desead.value!="1"){
			//u.chocurre.disabled 		= false;
			//u.t_txteadh2.value 				= "0";
		}
		if(u.desrrecoleccion.value!="1")
			u.t_txtrecoleccionh.value	= "0";
			
		
		var msg="";
		
		if(cantidadVencida != 0){
			msg += "El cliente tiene <br>$ "+numcredvar(cantidadVencida.toString()) + " vencido.<br>";
			cantidadVencida=0;
		}
		
		if(u.desead.value!="1" && u.desconvenio.value!="1" && valCon.restringEADDestinatarioE()){
			msg += "El destinatario tiene restringido el servicio E.A.D.<br>";
			u.txtead.value = 0;
			u.txtrestrinccion.value = "";
			//u.chocurre.value=1;
			u.t_txtead.value = "$ 0.00";
			//u.chocurre.disabled=true;
		}
		if(u.desconvenio.value!="1" && valCon.restringVDDestinatarioE()){
			msg += "El destinatario tiene restringido el servicio VALOR DECLARADO<br>";
			u.chkvalordeclarado.disabled=true;
		}
		if(valCon.restringirDestinoEAD()){
			msg += "El destino seleccionado tiene restringida la entrega a domicilio<br>";
			u.txtead.value = 0;
			u.txtrestrinccion.value = "";
			//u.chocurre.value = 1;
		}
		
		if(u.tipoguia.value=="CONSIGNACION"){
			if(valCon.checarCredito(0)){
				if(valCon.checarCreditoActivado(0)){
					
				}else{
					msg += "El credito esta desactivado<br>";
					u.creditodisponible.value = -1;
				}
			}else{
				u.creditodisponible.value = 0;
			}
		}
		
		if(u.subdestinos.value=='1' && u.desconvenio.value!="1" && valCon.validaEADsucursalE(valCon.validarConvenioAUsar(0),u.sucdestino_hidden.value)){
			u.t_txteadh2.value = "1";
		}
		if(u.origensubdestinos.value=='1' && u.desconvenio.value!="1" && u.desconvenio.value!="1" && valCon.validaRecsucursalE(valCon.validarConvenioAUsar(0),u.sucdestino_hidden.value))
			u.t_txtrecoleccionh.value = "1";
		if(u.iddestinatario.value){
			if(valCon.validarDestRestEADF(((u.chocurre.value==1)?true:false))){
				msg += "El destino seleccionado no acepta EAD a personas fisicas sin convenio<br>";
				u.txtead.value = 0;
				u.txtrestrinccion.value = "";
				//u.chocurre.value=1;
				u.t_txtead.value = "$ 0.00";
				//u.chocurre.disabled=true;
				u.t_txtrecoleccionh.value = "1";
			}
		}
		if(msg!="")
			alerta3(msg,"¡Atencion!");
				
		var convenio 	= valCon.validarConvenioAUsar(0);
		var cliconvenio = valCon.validarClienteConvenio(0);
		var oridest 	= valCon.validarOrigenDestino(0);
		var vendedor 	= valCon.validarVendedorConvenio(0);
		
		//alert(convenio+"-"+cliconvenio+"-"+oridest+"-"+vendedor);
		u.convenioaplicado.value = (convenio>0)?convenio:0;
		u.clientedelconvenio.value = (cliconvenio>-1) ? cliconvenio : 0 ;
		u.sucursaldelconvenio.value = ((oridest>-1)? ((oridest==0)?<?=$_SESSION[IDSUCURSAL]?>:u.sucdestino_hidden.value) : 0 );
		u.nombrevendedor.value = (vendedor != -1) ? vendedor.split(",")[0] : "" ;
		u.idvendedor.value = (vendedor != -1) ? vendedor.split(",")[1] : "0" ;
		
		document.all.idsguardar.innerHTML = botonesnuevo;
		//validar el EAD del convenio la clave 7
		if(u.subdestinos.value=='1' && valCon.validarServiciosNoCobro(0, 7,u.sucdestino_hidden.value)){
			u.t_txtead.value = "$ 0.00";
			u.t_txteadh.value = "$ 0.00";
			//u.t_txteadh2.value = "$ 0.00";
		}
		
		if(u.origensubdestinos.value=='1' && valCon.validarServiciosNoCobro(0, 8,u.idsucursalrecoleccion.value)){
			u.t_txtrecoleccion.value = "$ 0.00";
			document.getElementById('t_txtrecoleccion').style.color = "#000000";
			document.getElementById('t_txtrecoleccion').style.fontWeight="";
		}else{
			u.t_txtrecoleccion.value = "$ "+numcredvar(u.pc_recoleccion.value.toString());
			document.getElementById('t_txtrecoleccion').style.color = "#FF0000";
			document.getElementById('t_txtrecoleccion').style.fontWeight="bold";
		}
		
		var dP = valCon.validaPrepagada(0);
		if(dP!=null && u.tipoguia.value.toUpperCase()=="PREPAGADA"){
			var pesomax = (parseFloat(u.totalpeso.value)>parseFloat(u.totalvolumen.value))?parseFloat(u.totalpeso.value):parseFloat(u.totalvolumen.value);
			if(pesomax>parseFloat(dP.limitekg)){
				var pesoexcdedido = pesomax-parseFloat(dP.limitekg);
				var totalexcedente = pesoexcdedido * parseFloat(dP.preciokgexcedente);
				u.t_txtexcedente.value = '$ '+numcredvar(totalexcedente.toFixed(2));
				document.getElementById('t_txtexcedente').style.color = "#FF0000";
				document.getElementById('t_txtexcedente').style.fontWeight="bold";
			}
		}
		calculartotales();
	}
	
	function respuestaCredito(datos){
		var valor = datos.replace("\r\n","");
		
		u.creditodisponible.value = valor;
	}
	
	function paracambiarvalor(valor){
		if(valor=="X"){
			abrirVentanaFija('../convenio/descripcionesconvenio.php?tipo=E&idconvenio='+valCon.validarConvenioAUsar(0), 500, 400, 'ventana', 'Busqueda');
		}
	}
	function cambiarValor(descripcion){
		consultaTexto('obtenerMecanciaConvenio',"guiasempresariales_consulta_conv.php?accion=1&idpagina="+u.idpagina.value
					  	+"&idsucdestino="+u.sucdestino_hidden.value
						  +"&idsucorigen="+sucursalorigen+"&idconvenio="+valCon.validarConvenioAUsar(0)
						  +"&idmercancia="+tabla1.getSelectedRow().idmercancia+"&descripcion="+descripcion
						  +"&folioempresarial="+u.folioSeleccionado.innerHTML
						  +"&rd="+Math.random());
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
		abrirVentanaFija('../buscadores_generales/buscarEvaluacionGen.php?funcion=pedirDatosEvaluacion&ands=1&tipo=evaluacion&sucorigen='+so, 650, 450, 'Evaluaciones', 'Busqueda');
	}
	function bloquear(valor){
		u = document.all;
		//u.chocurre.disabled 		= valor;
		u.iddestinatario.readOnly	= valor;
		
		u.iddestinatario.style.backgroundColor		= (valor)?"#FFFF99":"";
		
		u.chkemplaye.disabled 			= valor;
		u.chkbolsaempaque.disabled 	= valor;
		u.chkavisocelular.disabled 	= valor;
		u.chkvalordeclarado.disabled 	= valor;
		u.chkacuserecibo.disabled 		= valor;
		u.chkcod.disabled 				= valor;
		
		u.b_destinatario.style.visibility = (valor)?"hidden":"visible";
		u.b_destinatario_dir.style.visibility = (valor)?"hidden":"visible";
		//u.img_descuento.style.visibility = (valor)?"hidden":"visible";
		
	}
	function bloquearServicios(valor){
		u.chkemplaye.disabled 			= valor;
		u.chkbolsaempaque.disabled 		= valor;
		u.chkavisocelular.disabled 		= valor;
		//u.chkvalordeclarado.disabled 	= valor;
		u.chkacuserecibo.disabled 		= valor;
		u.chkcod.disabled 				= valor;
		//u.img_descuento.style.visibility= (!valor)?"visible":"hidden";
	}
	
	//funciones limpiar
	function limpiar_remitente(){
		u = document.all;
		u.idremitente.value 	= "";
		u.rem_rfc.value 		= "";
		u.rem_cliente.value 	= "";
		u.rem_numero.value		= "";
		u.rem_calle.value 		= "";
		u.rem_cp.value			= "";
		u.rem_colonia.value		= "";
		u.rem_poblacion.value	= "";
		u.rem_telefono.value	= "";
		u.rem_personamoral.value= "";
	}
	function limpiar_destinatario(){
		u = document.all;
		u.iddestinatario.value 	= "";
		u.des_rfc.value 		= "";
		u.des_cliente.value 	= "";
		u.des_numero.value		= "";
		u.des_cp.value			= "";		
		u.des_calle.value		= "";
		u.des_colonia.value		= "";
		u.des_poblacion.value	= "";
		u.des_telefono.value	= "";
		u.des_personamoral.value= "";
		u.iddestinatario_anterior.value = 0;
	}
	function limpiar_evaluacion(){
			bloquearServicios(false);
			u = document.all;
			
			document.getElementById('foliodev').innerHTML = "&nbsp;";
			document.getElementById('folioSeleccionado').innerHTML = "&nbsp;";
			document.getElementById('numeroRastreo').innerHTML = '&nbsp;';
			document.all.unidadSubida.innerHTML = "&nbsp;";	
			document.getElementById('embarcadafaltantes').innerHTML = "";
			document.getElementById('danosfaltantes').innerHTML = "";	
			document.getElementById('entregafaltantes').innerHTML = "";
			u.nombresucursalorigen.innerHTML = "&nbsp;";
			u.sectorguia.value = "";
			document.all.idsucursalorigen.value="<?=$_SESSION[IDSUCURSAL]?>";
            document.all.fechaactual.value="<?=date("d/m/Y")?>";
			tabla1.clear();
			
			u.txtotrosAgregado.value 	= "";
			u.factura.value				= "";
			u.fechaentrega.value		= "";
			u.recibio.value				= "";
			u.frontera.value			= "";
			u.subdestinos.value			= "";
			u.origensubdestinos.value	= "";
			u.idsucursalrecoleccion.value= "";
			u.idsucursalorigen.value	= "<?=$_SESSION[IDSUCURSAL]?>";
			
			u.tipoflete.value 			= "";
			u.tipoguia.value 			= "";
			u.tipopago.disabled			= true;
			u.folioguiaempresarial.value= "";
			u.folioevaluacion.value		= "";
			u.paraconvenio.value		= "0";
			u.guiaguardadav.value		= "";
			u.estado.innerHTML 			= "";
			u.chocurre.value			= 0;
			u.destino.value 			= "";
			u.destino_hidden.value		= "";
			u.sucdestino.value 			= "";
			u.sucdestino_hidden.value 	= "";
			u.txtocu.value 				= "";
			u.txtead.value 				= "";
			
			u.totalpaquetes.value		= "";
			u.totalpeso.value			= "";
			u.totalvolumen.value		= "";
			
			u.t_txteadh2.value 				= "0";
			u.t_txtrecoleccionh.value		= "0";
			u.chkvalordeclarado.disabled	= false;
			u.chocurre.disabled 			= true;
			u.tipoflete.disabled 			= false;
			
			u.chkemplaye.checked 		= false;
			u.chkbolsaempaque.checked 	= false;
			u.chkavisocelular.checked 	= false;
			u.chkvalordeclarado.checked = false;
			u.chkacuserecibo.checked 	= false;
			u.chkcod.checked 			= false;
			u.chkemplaye.readOnly 		= false;
			u.chkbolsaempaque.readOnly 	= false;
			u.chkavisocelular.readOnly 	= false;
			u.chkvalordeclarado.readOnly= false;
			u.chkacuserecibo.readOnly 	= false;
			u.chkcod.readOnly 			= false;
			
			u.txtavisocelular1.value 	= "";
			u.txtavisocelular2.value 	= "";
			u.txtacuserecibo.value 		= "";
			u.txtcod.value 				= "";
			u.txtemplaye.value 			= "";
			u.txtbolsaempaque1.value 	= "";
			u.txtbolsaempaque2.value 	= "";
			u.txtdeclarado.value 		= "";
			u.txtobservaciones.value	= "";
			u.txtrestrinccion.value		= "";
			
			u.txtavisocelular1h.value 	= "";
			u.txtavisocelular2h.value 	= "";
			u.txtacusereciboh.value 	= "";
			u.txtcodh.value 			= "";
			u.txtemplayeh.value 		= "";
			u.txtbolsaempaque1h.value 	= "";
			u.txtbolsaempaque2h.value 	= "";
			u.txtbolsaempaque3h.value 	= "";
			
			u.txtrecoleccion.value		= "";
			u.txtrecoleccionh.value		= "";
			
			u.flete.value				= "";
			u.t_txtdescuento1.value		= "";
			u.t_txtdescuento2.value		= "";
			u.t_txtead.value			= "";
			u.t_txteadh.value			= "";
			u.t_txtrecoleccion.value	= "";
			u.t_txtseguro.value			= "";
			u.t_txtotros.value			= "";
			u.t_txtexcedente.value		= "";
			u.t_txtcombustible.value	= "";
			u.t_txtsubtotal.value		= "";
			u.t_txtiva.value			= "";
			u.t_txtivaretenido.value	= "";
			u.t_txttotal.value			= "";
			u.pagoregistrado.value		= 0;
			u.efectivo.value			= "";
			u.cheque.value				= "";
			u.ncheque.value				= "";
			u.banco.value				= "";
			u.tarjeta.value				= "";
			u.transferencia.value		= "";
			
			u.desead.value				= "";
			u.desrrecoleccion.value		= "";
			u.desporcobrar.value		= "";
			u.desconvenio.value			= "";
			
			document.all.idsguardar.innerHTML = botonesnuevo;
			
	}
	function limpiar_cajas(){
		document.all.folioSeleccionado.innerHTML = "&nbsp;";
	}
	
	//funciones calcular
	function calcularservicios(){
		u = document.all;
		
		if(u.t_txteadh2.value=="0"){
			if(((
						   parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))+
						   parseFloat(u.t_txtexcedente.value.replace("$ ","").replace(/,/g,""))
						)*0.10)<parseFloat(u.pc_ead.value)){
					u.t_txteadh.value = "$ "+numcredvar(u.pc_ead.value);
			}else{
				valoread = Math.round(((
								(parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))+
								parseFloat(u.t_txtexcedente.value.replace("$ ","").replace(/,/g,"")))
								)*.10)*100)/100;
				u.t_txteadh.value = "$ "+numcredvar(valoread.toLocaleString());
			}
		}else{
			u.t_txteadh.value = "$ 0.00";
		}
			
			u.t_txteadh.value = (u.t_txteadh.value=="")?"$ 0.00":u.t_txteadh.value;
			
			if(u.tipoguia.value=="PREPAGADA"){
				document.all.t_txtead.value = '$ 0.00';
			}else{			
				if(u.chocurre.value==0){
					document.all.t_txtead.value = document.all.t_txteadh.value;
				}else{
					document.all.t_txtead.value = '$ 0.00';
				} 
			}
			if(u.tipoguia.value=="PREPAGADA"){
				u.t_txtrecoleccion.value = "$ 0.00";
			}
			
			u.t_txtrecoleccion.value = (u.t_txtrecoleccion.value=="")?"$ 0.00":u.t_txtrecoleccion.value;
			
			try{
				if(valCon.validarServiciosNoCobro(0, 8,u.idsucursalrecoleccion.value)){
					u.t_txtrecoleccion.value = "$ 0.00";
					document.getElementById('t_txtrecoleccion').style.color = "#000000";
					document.getElementById('t_txtrecoleccion').style.fontWeight="";
				}else{
					if(u.txtrecoleccion.value.replace(/ /g,"")!=""){
						if((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))*0.10)<parseFloat(u.pc_recoleccion.value)){
							u.t_txtrecoleccion.value = "$ "+numcredvar(u.pc_recoleccion.value);
						}else{
							valorrecoleccion = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-
							parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,"")))*.10)*100)/100;
							
							if(parseFloat(valorrecoleccion)<parseFloat(u.pc_recoleccion.value)){
								u.t_txtrecoleccion.value = "$ "+numcredvar(u.pc_recoleccion.value);
							}else{
								u.t_txtrecoleccion.value = "$ "+numcredvar(valorrecoleccion.toLocaleString());
							}
							//u.t_txtrecoleccion.value = "$ "+numcredvar(valorrecoleccion.toLocaleString());
						}
						document.getElementById('t_txtrecoleccion').style.color = "#FF0000";
						document.getElementById('t_txtrecoleccion').style.fontWeight="bold";
					}else{
						u.t_txtrecoleccion.value = "$ 0.00";
						document.getElementById('t_txtrecoleccion').style.color = "#000000";
						document.getElementById('t_txtrecoleccion').style.fontWeight="";
					}
				}
			}catch(e){
				e = null;
			}
			
			document.getElementById('t_txtseguro').style.color = "#000000";
			document.getElementById('t_txtseguro').style.fontWeight="normal";
			
			//u.t_txtrecoleccion.value = "";
			if(u.tipoguia.value=="PREPAGADA" && u.txtdeclarado.value==""){
				u.t_txtseguro.value = "$ 0.00";
			}else{
				if(u.txtdeclarado.value!="" && u.txtdeclarado.value!="$ 0.00"){
					var lodeclarado 	= parseFloat(u.txtdeclarado.value.replace("$ ","")
.replace(/,/g,""));
					var pcvdporcada 	= parseFloat(u.pc_porcada.value);
					var pcvdcosto 		= parseFloat(u.pc_costo.value);
					var pcvdcostoextra 	= parseFloat(u.pc_vdcostoextra.value);
					var pcvdlimite 		= parseFloat(u.pc_vdlimite.value);
					var pcvddentro		= 0;
					var pcvdrestante 	= 0;
					var pcvdacumulado 	= 0;
					var pcvdexcedido	= 0;
					
					if(lodeclarado<=pcvdporcada){
						pcvdacumulado = pcvdcosto;
					}else{
						pcvdrestante = lodeclarado-pcvdporcada;
						
						pcvdacumulado = pcvdcosto+(pcvdcostoextra*(pcvdrestante/pcvdporcada));
					}
					u.t_txtseguro.value = "$ "+numcredvar(pcvdacumulado.toLocaleString());
				}else{
					u.t_txtseguro.value = "$ 0.00";
				}
			}
			
			if(u.tipoguia.value=="PREPAGADA" && u.t_txtseguro.value!="" && u.t_txtseguro.value!="$ 0.00"){
				document.getElementById('t_txtseguro').style.color = "#FF0000";
				document.getElementById('t_txtseguro').style.fontWeight="bold";
			}
			
			
			valorcombustible = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,""))+parseFloat(u.t_txtexcedente.value.replace("$ ","").replace(/,/g,"")))*(parseFloat(u.pc_tarifacombustible.value)/100))*100)/100;
			//alert(u.flete.value+"-"+u.t_txtdescuento2.value+"*"+u.pc_tarifacombustible.value);
			//alert(valorcombustible);
			u.t_txtcombustible.value = "$ "+numcredvar(valorcombustible.toLocaleString());
		
			u.t_txtcombustible.value = (u.t_txtcombustible.value=="")?"$ 0.00":u.t_txtcombustible.value;
			
			u.t_txtrecoleccion.value = (u.t_txtrecoleccion.value=="")?"$ 0.00":u.t_txtrecoleccion.value;
			
			u.t_txtdescuento1.value = (u.t_txtdescuento1.value=="")?"0 %":u.t_txtdescuento1.value;
			u.t_txtdescuento2.value = (u.t_txtdescuento2.value=="")?"$ 0.00":u.t_txtdescuento2.value;
			
		var templaye 		= parseFloat((u.txtemplaye.value=="")?0:u.txtemplaye.value.replace("$ ","").replace(/,/g,""));
		var tbolsaempaque	= parseFloat((u.txtbolsaempaque2.value=="")?0:u.txtbolsaempaque2.value.replace("$ ","").replace(/,/g,""));
		var tavisocelular	= parseFloat((u.txtavisocelular1.value=="")?0:u.txtavisocelular1.value.replace("$ ","").replace(/,/g,""));
		var tdeclarado		= parseFloat((u.txtdeclarado.value=="")?0:u.txtdeclarado.value.replace("$ ","").replace(/,/g,""));
		var tacuserecibo	= parseFloat((u.txtacuserecibo.value=="")?0:u.txtacuserecibo.value.replace("$ ","").replace(/,/g,""));
		var tcod			= parseFloat((u.txtcod.value=="")?0:u.txtcod.value.replace("$ ","").replace(/,/g,""));
		var totrosA			= parseFloat((u.txtotrosAgregado.value=="")?0:u.txtotrosAgregado.value);
		
		u.t_txtotros.value 	= templaye+tbolsaempaque+tavisocelular+tacuserecibo+tcod+totrosA;
		
		u.t_txtotros.value 	= "$ "+numcredvar(u.t_txtotros.value);
	}
	function calculartotales(){
		var u = document.all;
		if(u.folioevaluacion.value!=""){
			calcularservicios();
			
			var ptflete 			= parseFloat((u.flete.value=="")?0:u.flete.value.replace("$ ","").replace(/,/g,""));
			var ptdescuento 		= parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,""));
			var ptead 				= parseFloat((u.t_txtead.value=="")?0:u.t_txtead.value.replace("$ ","").replace(/,/g,""));
			
			if(document.all.tipoguia.value=="PREPAGADA"){
				var ptseguro	 		= 0;
				var ptexcedente			= 0;
				var ptrecoleccion		= 0;
			}else{
				var ptseguro	 		= parseFloat((u.t_txtseguro.value=="")?0:u.t_txtseguro.value.replace("$ ","").replace(/,/g,""));
				var ptexcedente			= parseFloat((u.t_txtexcedente.value=="")?0:u.t_txtexcedente.value.replace("$ ","").replace(/,/g,""));
				var ptrecoleccion 		= parseFloat((u.t_txtrecoleccion.value=="")?0:u.t_txtrecoleccion.value.replace("$ ","").replace(/,/g,""));
			}
			
			var ptotros		 		= parseFloat((u.t_txtotros.value=="")?0:u.t_txtotros.value.replace("$ ","").replace(/,/g,""));
			
			var ptcombustible		= parseFloat((u.t_txtcombustible.value=="")?0:u.t_txtcombustible.value.replace("$ ","").replace(/,/g,""));
			var ptsubtotal			= ptflete-ptdescuento+ptead+ptrecoleccion+ptseguro+ptotros+ptexcedente+ptcombustible;
			u.t_txtsubtotal.value	= "$ "+numcredvar((Math.round(ptsubtotal*100)/100).toLocaleString());
			u.t_txtiva.value		= Math.round((ptsubtotal*(parseFloat(u.pc_iva.value)/100))*100)/100;
			if((u.tipoflete.value=="PAGADA" && u.rem_personamoral.value=="SI") ||(u.tipoflete.value=="PARA COBRO" && u.des_personamoral.value=="SI") ){
				u.t_txtivaretenido.value = Math.round( (ptsubtotal*(parseFloat(u.pc_ivaretenido.value)/100))*100) /100;
			}else{
				u.t_txtivaretenido.value = "0.00";
			}
			u.t_txttotal.value		= Math.round( (ptsubtotal-parseFloat(u.t_txtivaretenido.value.replace("$ ","").replace(/,/g,""))+parseFloat(u.t_txtiva.value.replace("$ ","").replace(/,/g,"")) ) *100)/100;
			u.t_txtiva.value		= "$ "+numcredvar(u.t_txtiva.value.toLocaleString());
			u.t_txtivaretenido.value= "$ "+numcredvar(((u.t_txtivaretenido.value=="")?"0":u.t_txtivaretenido.value).toLocaleString());
			u.t_txttotal.value		= "$ "+numcredvar(u.t_txttotal.value.toLocaleString());
		}
	}
	
	//datos de la evaluacion
	function pedirDatosEvaluacion(idevaluacion){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			sucursalorigen 	= '<?=$_SESSION[IDSUCURSAL]?>';
			//alerta3("guiasempresariales_consulta.php?accion=1&folio="+idevaluacion+"&idsucorigen="+sucursalorigen,"");
			//window.open("../guiasempresariales/guiasempresariales_consulta.php?accion=1&folio="+idevaluacion+"&idsucorigen="+sucursalorigen+"&valrandom="+Math.random());
			consulta("devolverDatosEvaluacion", "guiasempresariales_consulta.php?accion=1&idpagina="+u.idpagina.value+"&folio="+idevaluacion+"&idsucorigen="+sucursalorigen+"&valrandom="+Math.random());
		}else{
			alerta("Seleccione una sucursal de Origen","¡Atencion!","fecha");
		}
	}
	function devolverDatosEvaluacion(datos){
		//alert(datos);
		bloquear(false);
		limpiar_evaluacion();
		limpiar_remitente();
		limpiar_destinatario();
		//u.guiaguardadav.value = "0";
		
		var encon = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(encon>0){
			var folioevaluacion		= datos.getElementsByTagName('folioevaluacion').item(0).firstChild.data;
			var nuevofolioguia		= datos.getElementsByTagName('nuevofolioguia').item(0).firstChild.data;
			var bloquearocurre		= datos.getElementsByTagName('bloquearocurre').item(0).firstChild.data;
			var tipopago			= datos.getElementsByTagName('tipopago').item(0).firstChild.data;
			var tipoguia			= datos.getElementsByTagName('tipoguia').item(0).firstChild.data;
			var tipoflete			= datos.getElementsByTagName('tipoflete').item(0).firstChild.data;
			var frontera			= datos.getElementsByTagName('frontera').item(0).firstChild.data;
			var subdestinos			= datos.getElementsByTagName('subdestinos').item(0).firstChild.data;
			var idsucursalrecoleccion = datos.getElementsByTagName('idsucursalrecoleccion').item(0).firstChild.data;
			var origensubdestinos	= datos.getElementsByTagName('origensubdestinos').item(0).firstChild.data;
			var nombreorigen		= datos.getElementsByTagName('nombreorigen').item(0).firstChild.data;
			
			var vestado 			= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var destino 			= datos.getElementsByTagName('ndestino').item(0).firstChild.data;
			var npobdes				= datos.getElementsByTagName('npobdes').item(0).firstChild.data;
			var iddestino 			= datos.getElementsByTagName('iddestino').item(0).firstChild.data;
			var sucursal			= datos.getElementsByTagName('nsucursal').item(0).firstChild.data;
			var idsucursal 			= datos.getElementsByTagName('idsucursal').item(0).firstChild.data;
			var npobdestino 		= datos.getElementsByTagName('npobdestino').item(0).firstChild.data;
			var cantidadbolsa		= datos.getElementsByTagName('cantidadbolsa').item(0).firstChild.data;
			var bolsaempaque		= datos.getElementsByTagName('bolsaempaque').item(0).firstChild.data;
			var emplaye				= datos.getElementsByTagName('emplaye').item(0).firstChild.data;
			var sbolsaempaque		= datos.getElementsByTagName('sbolsaempaque').item(0).firstChild.data;
			var semplaye			= datos.getElementsByTagName('semplaye').item(0).firstChild.data;
			var totalbolsaempaque	= datos.getElementsByTagName('totalbolsaempaque').item(0).firstChild.data;
			var totalemplaye		= datos.getElementsByTagName('totalemplaye').item(0).firstChild.data;
			var ocu					= datos.getElementsByTagName('ocu').item(0).firstChild.data;
			var ead					= datos.getElementsByTagName('ead').item(0).firstChild.data;
			var pagominimocheque	= datos.getElementsByTagName('pfp_pagominimocheques').item(0).firstChild.data;
			
			var avisocelular		= datos.getElementsByTagName('avisocelular').item(0).firstChild.data;
			var acuserecibo			= datos.getElementsByTagName('acuserecibo').item(0).firstChild.data;
			var cod					= datos.getElementsByTagName('cod').item(0).firstChild.data;
			var restrinccion		= datos.getElementsByTagName('restrincciones').item(0).firstChild.data;
			var restrinccion2		= datos.getElementsByTagName('restr2').item(0).firstChild.data;
			var restringirporcobrar = datos.getElementsByTagName('restringirporcobrar').item(0).firstChild.data;
			//para totales
			var pt_ead				= datos.getElementsByTagName('pt_ead').item(0).firstChild.data;
			var pt_recoleccion		= datos.getElementsByTagName('pt_recoleccion').item(0).firstChild.data;
			var pt_iva				= datos.getElementsByTagName('pt_iva').item(0).firstChild.data;
			var pt_ivaretenido		= datos.getElementsByTagName('pt_ivaretenido').item(0).firstChild.data;
			var por_combustible		= datos.getElementsByTagName('por_combustible').item(0).firstChild.data;
			var max_descuento		= datos.getElementsByTagName('max_des').item(0).firstChild.data;
			var vporcada			= datos.getElementsByTagName('por_cada').item(0).firstChild.data;
			var vscosto				= datos.getElementsByTagName('scosto').item(0).firstChild.data;
			var vdlimite			= datos.getElementsByTagName('vd_limite').item(0).firstChild.data;
			var vdcostoextra		= datos.getElementsByTagName('vd_costoextra').item(0).firstChild.data;
			var erecoleccion		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;
			var pesominimodesc		= datos.getElementsByTagName('pesominimodesc').item(0).firstChild.data;
			var restringiread		= datos.getElementsByTagName('restringiread').item(0).firstChild.data;
			
			var desead				= datos.getElementsByTagName('desead').item(0).firstChild.data;
			var desrrecoleccion		= datos.getElementsByTagName('desrrecoleccion').item(0).firstChild.data;
			var desporcobrar		= datos.getElementsByTagName('desporcobrar').item(0).firstChild.data;
			var desconvenio			= datos.getElementsByTagName('desconvenio').item(0).firstChild.data;
			var total_excedente		= datos.getElementsByTagName('total_excedente').item(0).firstChild.data;
			
			u.desead.value			= desead;
			u.desrrecoleccion.value	= desrrecoleccion;
			u.desporcobrar.value	= desporcobrar;
			u.desconvenio.value		= desconvenio;
			u.frontera.value		= frontera;
			u.subdestinos.value		= subdestinos;
			u.nombresucursalorigen.innerHTML=nombreorigen;
			
			u.bloquearocurre.value = bloquearocurre;
			
			if(bloquearocurre==1){
				//u.chocurre.value=1;
				//u.chocurre.disabled=true;
			}else{
				//u.chocurre.disabled=false;
			}
			
			var msg = "";
			if(desead==1){
				msg += "El destino no cuenta con servicio de EAD<br>";
				u.chocurre.value 	= 1;
				u.chocurre.disabled = true;
			}
			if(desrrecoleccion==1){
				msg += "El destino no cuenta con servicio de recoleccion<br>";
			}
			if(msg!="")
			alerta3(msg,"¡Atencion!");
			
			
			//para datagrid importe
			importe_tipo		= datos.getElementsByTagName('tipototales').item(0).firstChild.data;
			valor_totalimporte	= datos.getElementsByTagName('valor_totalimporte').item(0).firstChild.data;
			u.folioguiaempresarial.value = nuevofolioguia;
			document.all.folioSeleccionado.innerHTML = nuevofolioguia;
			
			u.folioevaluacion.value	= folioevaluacion;
			u.tipopago.value = tipopago;
				
			u.tipoguia.value = tipoguia;
			u.tipoflete.value = tipoflete;
			u.t_txtexcedente.value = "$ "+numcredvar(total_excedente);
			//datos cliente
			u.idremitente.value 		= datos.getElementsByTagName('rem_id').item(0).firstChild.data;
			u.rem_rfc.value 			= datos.getElementsByTagName('rem_rfc').item(0).firstChild.data;
			u.rem_cliente.value 		= datos.getElementsByTagName('rem_nombre').item(0).firstChild.data;
			u.rem_personamoral.value	= datos.getElementsByTagName('rem_personamoral').item(0).firstChild.data;
			v_celular 					= datos.getElementsByTagName('rem_celular').item(0).firstChild.data;
			u.txtavisocelular2h.value 	= (v_celular!="")?v_celular:"";
			u.rem_direcciones.value 	= datos.getElementsByTagName('rem_iddireccion').item(0).firstChild.data;
			u.rem_calle.value 			= datos.getElementsByTagName('rem_calle').item(0).firstChild.data;
			u.rem_numero.value 			= datos.getElementsByTagName('rem_numero').item(0).firstChild.data;
			u.rem_cp.value 				= datos.getElementsByTagName('rem_cp').item(0).firstChild.data;
			u.rem_colonia.value 		= datos.getElementsByTagName('rem_colonia').item(0).firstChild.data;
			u.rem_poblacion.value 		= datos.getElementsByTagName('rem_poblacion').item(0).firstChild.data;
			u.rem_telefono.value 		= datos.getElementsByTagName('rem_telefono').item(0).firstChild.data;
			
			
			//final datos cliente
			
			u.estado.innerHTML = vestado;
			u.destino.value = destino;
			u.destino_hidden.value = iddestino;
			u.npobdes.value = npobdes;
			u.destino.poblacion = npobdestino;
			u.sucdestino_hidden.value = idsucursal;
			u.sucdestino.value = sucursal;
			u.txtemplayeh.value = "$ "+numcredvar((totalemplaye==0 || totalemplaye=="")?semplaye:totalemplaye);
			u.txtbolsaempaque1h.value = cantidadbolsa;
			u.txtbolsaempaque2h.value = "$ "+numcredvar(totalbolsaempaque);
			u.txtbolsaempaque3h.value = "$ "+numcredvar(sbolsaempaque);
			u.txtocu.value = ocu;
			u.txtead.value = ead;
			u.txteadh.value = ead;
			u.pagominimocheque.value = pagominimocheque;
			u.restringiread.value = restringiread;
			
			if(restringiread==1){
				//u.chocurre.value=1;
			}
			
			u.pc_ead.value					= pt_ead;
			u.pc_recoleccion.value			= pt_recoleccion;
			u.pc_tarifacombustible.value	= por_combustible;
			u.pc_maximodescuento.value		= max_descuento;
			u.pc_porcada.value				= vporcada;
			u.pc_costo.value				= vscosto;
			u.pc_porcadah.value				= vporcada;
			u.pc_costoh.value				= vscosto;
			u.pc_vdlimite.value				= vdlimite;
			u.pc_vdcostoextra.value			= vdcostoextra;
			u.pc_vdlimiteh.value			= vdlimite;
			u.pc_vdcostoextrah.value		= vdcostoextra;
			u.pc_iva.value					= pt_iva;
			u.pc_ivaretenido.value			= pt_ivaretenido;
			u.pc_pesominimodesc.value		= pesominimodesc;
			//u.t_txtexcedente.value			= "$ 0.00";
			
			u.txtrestrinccion.value = (restrinccion==0)?"":restrinccion;
			if(restrinccion2!=0){
				u.txtrestrinccion.value += "La entrega se hara hasta el dia "+restrinccion2;
				u.txtrestrinccionh.value += "La entrega se hara hasta el dia "+restrinccion2;
			}
			if(emplaye==1){
				u.chkemplaye.checked = true;
				u.chkemplaye.disabled = true;
				u.txtemplaye.value = "$ "+numcredvar(totalemplaye);
			}else{
				u.chkemplaye.checked = false;
			}
			if(bolsaempaque==1){
				u.chkbolsaempaque.checked = true;
				u.chkbolsaempaque.disabled = true;
				u.txtbolsaempaque1.value = cantidadbolsa;
				u.txtbolsaempaque2.value = "$ "+numcredvar(totalbolsaempaque);
			}else{
				u.chkbolsaempaque.checked = false;
			}
			if(erecoleccion!=0){
				u.txtrecoleccionh.value		= "$ "+numcredvar(erecoleccion);	
				u.txtrecoleccion.value		= "$ "+numcredvar(erecoleccion);	
			}else{
				u.txtrecoleccionh.value		= "";	
			}
			
			u.txtavisocelular1h.value 	= "$ "+numcredvar(avisocelular);
			u.txtacusereciboh.value 	= "$ "+numcredvar(acuserecibo);
			u.txtcodh.value 			= "$ "+numcredvar(cod);
			
			//total de evaluacion
			var enconeva = datos.getElementsByTagName('encontroevaluacion').item(0).firstChild.data;
			if(enconeva>0){
				tpaquetes	= 0;
				tpeso		= 0;
				tvolumen	= 0;
				timporte	= 0;
				for(m=0;m<enconeva;m++){	
					var objetox = new Object();
					
					idmercancia	= datos.getElementsByTagName('idmercancia').item(m).firstChild.data;
					cantidad	= datos.getElementsByTagName('cantidad').item(m).firstChild.data;
					descripcion	= datos.getElementsByTagName('descripcion').item(m).firstChild.data;
					contenido	= datos.getElementsByTagName('contenido').item(m).firstChild.data;
					peso		= datos.getElementsByTagName('peso').item(m).firstChild.data;
					volumen		= datos.getElementsByTagName('volumen').item(m).firstChild.data;
					importe		= datos.getElementsByTagName('importe').item(m).firstChild.data;
					largo		= datos.getElementsByTagName('largo').item(m).firstChild.data;
					ancho		= datos.getElementsByTagName('ancho').item(m).firstChild.data;
					alto		= datos.getElementsByTagName('alto').item(m).firstChild.data;
					modificable	= datos.getElementsByTagName('modificable').item(m).firstChild.data;
					
					objetox.idmercancia 	= idmercancia;
					objetox.cantidad 		= cantidad;
					objetox.descripcion 	= descripcion;
					objetox.contenido 		= contenido;
					objetox.peso 			= peso;
					objetox.largo 			= largo;
					objetox.ancho 			= ancho;
					objetox.alto 			= alto;
					objetox.volumen 		= volumen;
					objetox.importe 		= importe;
					objetox.modificable 	= modificable;
					tabla1.add(objetox);
					
					tpaquetes	+= parseFloat(cantidad);
					tpeso		+= parseFloat(peso);
					tvolumen	+= parseFloat(volumen);
					timporte	+= parseFloat(importe);
				}
				u.totalpaquetes.value 	= tpaquetes;
				u.totalpeso.value 		= tpeso;
				u.totalvolumen.value 	= tvolumen;
				if(importe_tipo==1){
					u.flete.value	= "$ "+numcredvar(valor_totalimporte.toLocaleString());
				}else{
					u.flete.value	= "$ "+numcredvar(timporte.toLocaleString());
				}
				
			}
			if(tipoguia=="PREPAGADA"){
				bloquearServicios(true);
				u.t_txtseguro.value = "$ 0.00";
			}
			//calculo de totales			
			calculartotales();
			document.all.idsguardar.innerHTML = botonesnuevo;
			if(frontera==1){
				abrirVentanaFija('datosFronteras.php', 450, 318, 'ventana', 'Datos de Frontera')
			}
			if(datos.getElementsByTagName('iddestinatario').item(0)!=null){
				var iddestinatario			= datos.getElementsByTagName('iddestinatario').item(0).firstChild.data;
				var iddirecciondestinatario	= datos.getElementsByTagName('iddirecciondestinatario').item(0).firstChild.data;
				var dncliente				= datos.getElementsByTagName('dncliente').item(0).firstChild.data;
				var drfc					= datos.getElementsByTagName('drfc').item(0).firstChild.data;
				var dcalle					= datos.getElementsByTagName('dcalle').item(0).firstChild.data;
				var dnumero					= datos.getElementsByTagName('dnumero').item(0).firstChild.data;
				var dcp						= datos.getElementsByTagName('dcp').item(0).firstChild.data;
				var dpoblacion				= datos.getElementsByTagName('dpoblacion').item(0).firstChild.data;
				var dtelefono				= datos.getElementsByTagName('dtelefono').item(0).firstChild.data;
				var dcolonia				= datos.getElementsByTagName('dcolonia').item(0).firstChild.data;
				
				u.des_direcciones.value = iddirecciondestinatario;
				u.iddestinatario.value	= iddestinatario;
				u.des_cliente.value	= dncliente;
				u.des_rfc.value	= drfc;
				u.des_calle.value	= dcalle;
				u.des_numero.value	= dnumero;
				u.des_cp.value	= dcp;
				u.des_colonia.value	= dcolonia;
				u.des_poblacion.value	= dpoblacion;
				u.des_telefono.value	= dtelefono;
			}else{
				u.idremitente.focus();
				solicitarDatosConv();
			}
		}else{
			alerta("Evaluación no encontrada","¡Alerta!","idremitente");
		}
	}
	
	function traerAlcliente(valor){
		ocultarBuscador();
		devolverDestinatario(valor);
	}
	
	function ponerDireccion(obj){
		if(tipoClienteBuscado=='R'){
			u.rem_direcciones.value	= obj.iddireccion;
			u.rem_calle.value 		= obj.calle;
			u.rem_numero.value 		= obj.numero;
			u.rem_cp.value 			= obj.codigopostal;
			u.rem_colonia.value 	= obj.colonia;
			u.rem_poblacion.value 	= obj.poblacion;
			u.rem_telefono.value 	= obj.telefono;
		}else{
			u.des_direcciones.value	= obj.iddireccion;
			u.des_calle.value 		= obj.calle;
			u.des_numero.value 		= obj.numero;
			u.des_cp.value 			= obj.codigopostal;
			u.des_colonia.value 	= obj.colonia;
			u.des_poblacion.value 	= obj.poblacion;
			u.des_telefono.value 	= obj.telefono;
		}
		ocultarDirecciones();
	}
	
	function devolverDestinatario(valor){
		var u = document.all;
		if(u.folioevaluacion.value==""){
			alerta3("Porfavor seleccione la evaluacion para poder agregar el destinatario", "¡Atencion!");
			u.iddestinatario.value = "";
		}else{
			limpiar_destinatario();
			document.all.iddestinatario.value = valor;
			consultaTexto("mostrarDestinatario", "guiasempresariales_consultajson.php?accion=2&idpagina="+u.idpagina.value
						  +"&idcliente="+valor+"&poblacion="+u.npobdes.value+"&valrandom="+Math.random());
		}
	}
	function mostrarDestinatario(datos){
		try{
			var dcliente = eval(datos);
		}catch(e){
			alerta3(datos);
		}
		var u = document.all;
		if(dcliente.cliente!="0"){
			tipoClienteBuscado='D';
			var endir = dcliente.direcciones.length;
			u.iddestinatario.value 		= dcliente.cliente.idcliente;
			u.iddestinatario_anterior.value = dcliente.cliente.idcliente;
			u.des_rfc.value 			= dcliente.cliente.rfc;
			u.des_cliente.value 		= dcliente.cliente.ncliente;
			u.des_personamoral.value	= dcliente.cliente.personamoral;
			
			if(endir>0){
				dirDest = dcliente.direcciones;
				u.des_direcciones.value	= dcliente.direcciones[0].iddireccion;
				u.des_calle.value 		= dcliente.direcciones[0].calle;
				u.des_numero.value 		= dcliente.direcciones[0].numero;
				u.des_cp.value 			= dcliente.direcciones[0].codigopostal;
				u.des_colonia.value 	= dcliente.direcciones[0].colonia;
				u.des_poblacion.value 	= dcliente.direcciones[0].poblacion;
				u.des_telefono.value 	= dcliente.direcciones[0].telefono;
				if(endir>1){
					mostrarDirecciones(dirDest,dcliente.cliente.idcliente);
				}
			}else{
				alerta("El Cliente no tiene direccion","","idremitente");
			}
			consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idpagina="+u.idpagina.value+"&idremitente="+u.idremitente.value
						  +"&iddestinatario="+u.iddestinatario.value+"&iddestino="+u.destino_hidden.value+"&idsucdestino="+u.sucdestino_hidden.value
						  +"&fevaluacion="+u.folioevaluacion.value+"&valran="+Math.random());
		}else{
			alerta("El Cliente no existe","","idremitente");
		}
		if(u.tipoguia.value=="PREPAGADA"){
			bloquearServicios(true);
			u.t_txtseguro.value = "$ 0.00";
		}
		calculartotales();		
	}
	
	//registrar guia
	function registrarGuia(){
		<?=$cpermiso->verificarPermiso(340,$_SESSION[IDUSUARIO]);?>;
		var u = document.all;
		
		var newfolio				= u.folioguiaempresarial.value;
		var evaluacion 				= u.folioevaluacion.value;
		var fecha					= u.fecha.value;
		var tipoflete				= u.tipoflete.value;
		var tipopago				= u.tipopago.value;
		var tipoguia				= u.tipoguia.value;
		var ocurre					= u.chocurre.value;
		var idsucursalorigen		= sucursalorigen;
		var iddestino				= u.destino_hidden.value;
		var idsucursaldestino		= u.sucdestino_hidden.value;
		var idremitente				= u.idremitente.value;
		var iddireccionremitente	= u.rem_direcciones.value;
		var iddestinatario			= u.iddestinatario.value;
		var iddirecciondestinatario = u.des_direcciones.value;
		var entregaocurre			= (u.txtocu.value=="")?"0":u.txtocu.value;
		var entregaead				= (u.txtead.value=="")?"0":u.txtead.value;
		var restrinccion			= u.txtrestrinccion.value;
		var totalpaquetes			= u.totalpaquetes.value;
		var totalpeso				= u.totalpeso.value;
		var totalvolumen			= u.totalvolumen.value;
		var emplaye					= u.txtemplaye.value.replace("$ ","").replace(/,/g,"");
		var bolsaempaque			= u.txtbolsaempaque1.value;
		var totalbolsaempaque		= u.txtbolsaempaque2.value.replace("$ ","").replace(/,/g,"");
		var avisocelular			= u.txtavisocelular1.value.replace("$ ","").replace(/,/g,"");
		var celular					= u.txtavisocelular2.value;
		var valordeclarado			= u.txtdeclarado.value.replace("$ ","").replace(/,/g,"");
		var acuserecibo				= u.txtacuserecibo.value.replace("$ ","").replace(/,/g,"");
		var cod						= u.txtcod.value.replace("$ ","").replace(/,/g,"");
		var recoleccion				= u.txtrecoleccion.value.replace("$ ","").replace(/,/g,"");
		var observaciones			= u.txtobservaciones.value;
		var tflete					= u.flete.value.replace("$ ","").replace(/,/g,"");
		var tdescuento				= u.t_txtdescuento1.value.replace(" %","");
		var ttotaldescuento			= u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,"");
		var tcostoead				= u.t_txtead.value.replace("$ ","").replace(/,/g,"");
		var trecoleccion			= u.t_txtrecoleccion.value.replace("$ ","").replace(/,/g,"");
		var tseguro					= u.t_txtseguro.value.replace("$ ","").replace(/,/g,"");
		var totros					= u.t_txtotros.value.replace("$ ","").replace(/,/g,"");
		var texcedente				= u.t_txtexcedente.value.replace("$ ","").replace(/,/g,"");
		var tcombustible			= u.t_txtcombustible.value.replace("$ ","").replace(/,/g,"");
		var subtotal				= u.t_txtsubtotal.value.replace("$ ","").replace(/,/g,"");
		var tiva					= u.t_txtiva.value.replace("$ ","").replace(/,/g,"");
		var ivaretenido				= u.t_txtivaretenido.value.replace("$ ","").replace(/,/g,"");
		var total					= u.t_txttotal.value.replace("$ ","").replace(/,/g,"");
		var efectivo				= u.efectivo.value.replace("$ ","").replace(/,/g,"");
		var cheque					= u.cheque.value.replace("$ ","").replace(/,/g,"");
		var banco					= u.banco.value.replace("$ ","").replace(/,/g,"");
		var ncheque					= u.ncheque.value;
		var tarjeta					= u.tarjeta.value.replace("$ ","").replace(/,/g,"");
		var trasferencia			= u.transferencia.value.replace("$ ","").replace(/,/g,"");
		var nc 						= u.nc.value.replace("$ ","").replace(/,/g,"");
		var nc_folio				= u.nc_folio.value.replace("$ ","").replace(/,/g,"");
		
		//valores agregados para reportesmaestros
		var convenioaplicado	= u.convenioaplicado.value;
		var clienteconvenio		= u.clientedelconvenio.value;
		var sucursaldelconvenio	= u.sucursaldelconvenio.value;
		var nombrevendedor		= u.nombrevendedor.value;
		var idvendedor			= u.idvendedor.value;
		var otrosAgregado		= u.txtotrosAgregado.value
		
		consulta("guiaGuardada","guiasempresariales_consulta.php?accion=4&idpagina="+u.idpagina.value+"&evaluacion="+evaluacion+"&newfolio="+newfolio
				 +"&fecha="+fecha+"&tipoflete="+tipoflete+"&ocurre="+ocurre+"&tipoguia="+tipoguia
				 +"&idsucursalorigen="+idsucursalorigen+"&iddestino="+iddestino+"&idsucursaldestino="+idsucursaldestino
				 +"&tipopago="+tipopago+"&idremitente="+idremitente+"&iddireccionremitente="+iddireccionremitente
				 +"&iddestinatario="+iddestinatario+"&iddirecciondestinatario="+iddirecciondestinatario+"&entregaocurre="+entregaocurre
				 +"&entregaead="+entregaead+"&restrinccion="+restrinccion+"&totalpaquetes="+totalpaquetes+"&totalpeso="+totalpeso
				 +"&totalvolumen="+totalvolumen+"&emplaye="+emplaye+"&bolsaempaque="+bolsaempaque+"&totalbolsaempaque="+totalbolsaempaque
				 +"&avisocelular="+avisocelular+"&celular="+celular+"&valordeclarado="+valordeclarado+"&acuserecibo="+acuserecibo
				 +"&cod="+cod+"&recoleccion="+recoleccion+"&observaciones="+observaciones+"&tflete="+tflete+"&tdescuento="+tdescuento
				 +"&ttotaldescuento="+ttotaldescuento+"&tcostoead="+tcostoead+"&trecoleccion="+trecoleccion+"&tseguro="+tseguro
				 +"&totros="+totros+"&texcedente="+texcedente+"&tcombustible="+tcombustible+"&subtotal="+subtotal+"&tiva="+tiva
				 +"&ivaretenido="+ivaretenido+"&total="+total+"&efectivo="+efectivo+"&cheque="+cheque+"&banco="+banco
				 +"&ncheque="+ncheque+"&tarjeta="+tarjeta+"&trasferencia="+trasferencia+"&clienteconvenio="+clienteconvenio
				 +"&sucursalconvenio="+sucursaldelconvenio+"&idvendedorconvenio="+idvendedor+"&nvendedorconvenio="+nombrevendedor
				 +"&convenioaplicado="+convenioaplicado+"&nc="+nc+"&nc_folio="+nc_folio+"&nuevootros="+otrosAgregado+"&ran="+Math.random());
	}
	function guiaGuardada(datos){
		var guardado= datos.getElementsByTagName('guardado').item(0).firstChild.data;
		if(guardado==1){
			folio = datos.getElementsByTagName('folioguia').item(0).firstChild.data;
			var rastreo = datos.getElementsByTagName('rastreo').item(0).firstChild.data;
			
			bloquear(true);
			
			document.all.numeroRastreo.innerHTML = rastreo;
			document.all.guiaguardadav.value = 1;
			document.all.estado.innerHTML = "ALMACEN ORIGEN";
			confirmar("La guia ha sido Guardada ¿Desea limpiar los datos?","¡Atencion!",
					  "limpiar_evaluacion();limpiar_remitente();limpiar_destinatario(); mostrarEvaluaciones();","");
			
			folioimprimir = folio;
			try{
				imprimir();
			}catch(e){
				e = null;
			}
			
		}else{
			cons = datos.getElementsByTagName('consulta').item(0).firstChild.data;
			alerta("Error al guardar //" + cons,"¡Atencion!","idremitente");
		}
			document.all.idsguardar.innerHTML = botonesdesguardar;
	}
	
	//validacion 
	function ejecutarSubmit(){
			confirmar("¿Desea guardar la Guia?","¡Atencion!","registrarGuia()","");
			//confirmar("¿Desea guardar la Guia?","¡Atencion!","preguntaFactura()","");
	}
	function preguntaFactura(){
		confirmar("¿Desea Facturar?","¡Atencion!","","registrarGuia()");
	}
	
	//funciones para cancelar
	function mostrarGuiasPendientesCancelar(){
		abrirVentanaFija('../buscadores_generales/buscarGuiasEmpresarialesGen.php?funcion=solicitarGuia&estado=AUTORIZACION PARA CANCELAR', 650, 450, 'ventana', 'Guias pendientes para cancelacion')
	}
	function buscarUnaGuia(folioguia){
		solicitarGuia(folioguia);
	}
	function solicitarGuia(folio){
		consulta("respuestaGuia","guiasempresariales_consulta.php?accion=5&idpagina="+u.idpagina.value+"&folio="+folio+"&rand="+Math.random());
	}
	function respuestaGuia(datos){		
		//alert(datos);
		u = document.all;
		limpiar_evaluacion();
		limpiar_remitente();
		limpiar_destinatario();
		bloquear(true);
		u.guiaguardadav.value = "1";
		
		var encon = datos.getElementsByTagName('encontrados').item(0).firstChild.data;
		if(encon>0){
			var id						= datos.getElementsByTagName('id').item(0).firstChild.data;
			var evaluacion				= datos.getElementsByTagName('evaluacion').item(0).firstChild.data;
			var fecha					= datos.getElementsByTagName('fecha').item(0).firstChild.data;
			var fechaactual				= datos.getElementsByTagName('fechaactual').item(0).firstChild.data;
			var fechaentrega			= datos.getElementsByTagName('fechaentrega').item(0).firstChild.data;
			var recibio					= datos.getElementsByTagName('recibio').item(0).firstChild.data;
			var factura					= datos.getElementsByTagName('factura').item(0).firstChild.data;
			var estado					= datos.getElementsByTagName('estado').item(0).firstChild.data;
			var sectorguia				= datos.getElementsByTagName('sectorguia').item(0).firstChild.data;
			if(datos.getElementsByTagName('unidadguia').item(0)!=null){
				var unidadguia			= datos.getElementsByTagName('unidadguia').item(0).firstChild.data;
				document.all.unidadSubida.innerHTML = "Unidad: " + unidadguia;
			}
			if(datos.getElementsByTagName('danosfaltantes').item(0)!=null){
				var danosfaltantes			= datos.getElementsByTagName('danosfaltantes').item(0).firstChild.data;
				document.all.danosfaltantes.innerHTML = danosfaltantes;
			}
			if(datos.getElementsByTagName('embarcadafaltantes').item(0)!=null){
				var embarcadafaltantes			= datos.getElementsByTagName('embarcadafaltantes').item(0).firstChild.data;
				document.all.embarcadafaltantes.innerHTML = embarcadafaltantes;
			}
			
			if(datos.getElementsByTagName('entregafaltantes').item(0)!=null){
				var entregafaltantes		= datos.getElementsByTagName('entregafaltantes').item(0).firstChild.data;
				document.getElementById('entregafaltantes').innerHTML = entregafaltantes;
			}
			if(datos.getElementsByTagName('foliodev').item(0)!=null){
				var foliodev		= datos.getElementsByTagName('foliodev').item(0).firstChild.data;
				document.getElementById('foliodev').innerHTML = foliodev;
			}
			
			document.getElementById('numeroRastreo').innerHTML = datos.getElementsByTagName('numerorastreo').item(0).firstChild.data;
			
			var tipoflete				= datos.getElementsByTagName('tipoflete').item(0).firstChild.data;
			var tipopago				= datos.getElementsByTagName('tipopago').item(0).firstChild.data;
			var tipoguia				= datos.getElementsByTagName('tipoguia').item(0).firstChild.data;
			var ocurre					= datos.getElementsByTagName('ocurre').item(0).firstChild.data;
			var idsucursalorigen		= datos.getElementsByTagName('idsucursalorigen').item(0).firstChild.data;
			var idsucursaldestino		= datos.getElementsByTagName('idsucursaldestino').item(0).firstChild.data;
			var sucursalorigen		    = datos.getElementsByTagName('sucursalorigen').item(0).firstChild.data;
			var ndestino				= datos.getElementsByTagName('ndestino').item(0).firstChild.data;
			var nsucdestino				= datos.getElementsByTagName('nsucdestino').item(0).firstChild.data;
			
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
			var otrosAgregado			= datos.getElementsByTagName('otrosAgregado').item(0).firstChild.data;
			
			document.all.folioSeleccionado.innerHTML = id;
			folioimprimir = id;
			
			u.folioguiaempresarial.value= id;
			u.fecha.value				= fecha;
			u.estado.innerHTML			= estado;
			u.sectorguia.value			= sectorguia;
			u.tipoflete.value			= tipoflete;
			u.idsucursalorigen.value	= idsucursalorigen;
			u.sucdestino_hidden.value	= idsucursaldestino;
			u.txtotrosAgregado.value	= otrosAgregado;
			if(ocurre==1)
				u.chocurre.value		= 1;
			else
				u.chocurre.value		= 0;
			
			u.fechaentrega.value 	= fechaentrega;
			u.factura.value 		= factura;
			u.recibio.value 		= recibio;
			u.nombresucursalorigen.innerHTML = sucursalorigen;
			
			u.destino.value			= ndestino;
			u.sucdestino.value	= nsucdestino;
			
			u.tipopago.value	= tipopago;
			u.tipopago.disabled	= true;
			u.tipoguia.value	= tipoguia;
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
					alto		= datos.getElementsByTagName('alto').item(m).firstChild.data;
					largo		= datos.getElementsByTagName('largo').item(m).firstChild.data;
					ancho		= datos.getElementsByTagName('ancho').item(m).firstChild.data;
					volumen		= datos.getElementsByTagName('volumen').item(m).firstChild.data;
					importe		= datos.getElementsByTagName('importe').item(m).firstChild.data;
					
					var objetox = new Object();
					objetox.idmercancia	= idmercancia;
					objetox.cantidad 	= cantidad;
					objetox.descripcion = descripcion;
					objetox.contenido 	= contenido;
					objetox.peso 		= peso;
					objetox.alto 		= alto;
					objetox.largo 		= largo;
					objetox.ancho 		= ancho;
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
			if(estado=='CANCELADO'){
				document.all.idsguardar.innerHTML = botonnuevo;
			}else{
				document.all.idsguardar.innerHTML = botonesconsulta;
			}
		}else{
			alerta("No se encontro la guia buscada", "¡Atencion!","fecha");
		}
	}
	
	function cancelarGuia(){
		u = document.all;
		if(u.estado.innerHTML=='AUTORIZACION PARA CANCELAR'){
			abrirVentanaFija('cancelarfinal.php?folioguia='+document.all.folioSeleccionado.innerHTML, 450, 250, 'ventana', 'Motivo de Cancelacion');
		}else{
			if(u.estado.innerHTML != "ALMACEN ORIGEN"){
				alerta("Solo puede cancelar guias empresariales con el estado ALMACEN ORIGEN","¡Atención!","fecha");
			}else if(u.idsucursalorigen.value != "<?=$_SESSION[IDSUCURSAL]?>"){
				alerta("Solo pueda cancelar esta guia en la sucursal donde se registro","¡Atención!","fecha");
			}else if(u.fechaactual.value != u.fecha.value){
				alerta("Imposible liberar Guias con fecha posterior a la fecha de Emisión","¡Atención!","fecha");
			}else if(u.fechaactual.value == u.fecha.value){
				confirmar("Realmente desea liberar la guia<br>-La guia será borrada<br>-La evaluacion reabierta para su uso","¡Atención!","liberarGuia()","");
			}
		}
	}
	
	function liberarGuia(){
		consulta("respuestaLiberacion","guiasempresariales_consulta.php?accion=6&idpagina="+u.idpagina.value+"&folio="+document.all.folioSeleccionado.innerHTML
		+"&motivo="+document.all.motivocancelacion.value+"&rand="+Math.random());
	}
	
	function mensajeCancelarFinal(){
		confirmar("¿Desea cancelar la guia?","¡Atencion!","cancelarFinal()","");
	}
	function preguntarSiCancelar(){
		confirmar("¿Seguro desea enviar la guia a Pendientes por Cancelar?","¡Atencion!","guardarCancelacion()","");
	}
	function guardarCancelacion(){
		consulta("respuestaCancelacion","guia_consulta.php?accion=6&idpagina="+u.idpagina.value+"&folio="+document.all.folioSeleccionado.innerHTML
		+"&motivo="+document.all.motivocancelacion.value+"&rand="+Math.random());
	}
	function respuestaLiberacion(){
		document.all.estado.innerHTML = 'BORRADA';
		alerta("La guia ha sido borrada para su liberacion", "¡Atencion!", "fecha");
	}
	function cancelarFinal(){
		consulta("respuestaCancelarFinal","guia_consulta.php?accion=7&idpagina="+u.idpagina.value+"&folio="+document.all.folioSeleccionado.innerHTML
		+"&rand="+Math.random());
	}
	function respuestaCancelarFinal(datos){
		document.all.estado.innerHTML = 'CANCELADO';
		alerta("La guia se ha Cancelado", "¡Atencion!", "fecha");
		document.all.idsguardar.innerHTML = botonnuevo;
	}
	
	function permitirDescuento(){
		u = document.all;
		u.t_txtdescuento1.readOnly = false;
		u.t_txtdescuento1.style.backgroundColor = "#FFFFFF";
		u.t_txtdescuento1.focus();
		u.t_txtdescuento1.select();
		u.t_txtdescuento1.value = u.t_txtdescuento1.value.replace(" %","");
		
	}
	function validarDescuento(){		
		u = document.all;
		if(( (parseFloat(u.totalpeso.value)>parseFloat(u.totalvolumen.value))?parseFloat(u.totalpeso.value):parseFloat(u.totalvolumen.value))<=parseFloat(u.pc_pesominimodesc.value)){
			alerta("Para aplicar descuento, el peso total debe ser mayor a "+u.pc_pesominimodesc.value+" kg","¡Atencion!","flete");
			return false;
		}else if(u.flete.value=="" || u.flete.value=="$ 0.00"){
			alerta("No puede aplicar descuento si el flete no se ha calculado el flete","¡Atencion!","flete");
			return false;
		}else{
			return true;
		}
	}
	function calcularDescuento(){
		var u = document.all;
		if(parseFloat(u.t_txtdescuento1.value)==0 || u.t_txtdescuento1.value==""){
			u.t_txtdescuento1.value	= "";
			u.t_txtdescuento2.value = "";
		}else{
			var flete_pd			= parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""));
			u.t_txtdescuento2.value = "$ "+numcredvar((flete_pd*(parseFloat(u.t_txtdescuento1.value)/100)).toLocaleString());
			u.t_txtdescuento1.value = u.t_txtdescuento1.value + " %";	
		}
		u.t_txtdescuento1.readOnly = true;
		u.t_txtdescuento1.style.backgroundColor = "#FFFF99";
		calculartotales();
	}
	function validarDatos(){
		var u = document.all;
		if(u.bloquearocurre.value==1 && u.chocurre.value==1){
			alerta('El destino seleccionado no puede hacer entregas ocurre','¡Atención!',"chocurre");
			return false;
		}if(u.guiaguardadav.value=="1"){
			alerta('Esta guia ya ha sido guardada','¡Atención!',"iddestinatario");
			return false;
		}else if(u.folioevaluacion.value==""){
			alerta('Debe seleccionar una guia','¡Atención!',"idremitente");
			return false;
		}else if(u.idremitente.value=="" || u.idremitente.value=="0"){
			alerta('Debe capturar el remitente, consulte el convenio, puede estar vencido o cancelado','¡Atención!',"idremitente");
			return false;
		}else if(u.des_cliente.value==""){
			alerta('Proporcione un destinatario válido','¡Atención!',"idremitente");
			return false;
		}else if(u.des_direcciones.value==""){
			alerta('El destinatario no tiene dirección','¡Atención!',"idremitente");
			return false;
		}else if(u.iddestinatario.value==""){
			alerta('Debe capturar el destinatario','¡Atención!',"iddestinatario");
			return false;
		}else if(u.frontera.value=="1" && u.txtobservaciones.value==""){
			abrirVentanaFija('datosFronteras.php', 450, 318, 'ventana', 'Ingrese los Datos de Frontera');
			return false;
		}else if(u.subdestinos.value=="0" && u.chocurre.value=="1"){
			u.chocurre.value = 0;
			alerta('No puede enviar una guia ocurre a un destino que no es sucursal','¡Atención!',"chocurre");
			return false;
		}else if(u.des_poblacion.value != u.npobdes.value){
			alerta("Direccion del destinatario no concuerda con el destino, debe corregir dirección, destino, o enviar ocurre","¡Alerta!","iddestinatario");	
		}
		return true;
	}
	
	function valcreditodisponible(){
		if(u.tipoguia.value!="PREPAGADA"){
			if(u.tipopago.value=="CREDITO"){
				if(u.creditodisponible.value==-1){
					alerta3("El credito esta desactivado, no puede guardar la guia","¡ATENCION!");
					return false;
				}
				if(valCon.validaCredito(0,<?=$_SESSION[IDSUCURSAL]?>)=='NO CREDITO'){
					alerta3("No tiene un crédito dado de alta","¡ATENCION!");
					u.tipopago.value="CONTADO";
					return false;
				}else if(valCon.validaCredito(0,<?=$_SESSION[IDSUCURSAL]?>)==false){
					alerta3("No cuenta con servicio de crédito en esta sucursal","¡ATENCION!");
					u.tipopago.value="CONTADO";
					return false;
				}
				if(parseFloat(u.creditodisponible.value)<parseFloat(u.t_txttotal.value.replace("$ ","").replace(",",""))){
					alerta3("Credito insuficiente","¡Atencion!");
					return false;
				}else{
					return true;
				}
			}
		}
		return true;
	}
	
	//funciones para obtener el emplaye
	function ObtenerPrecioEmplaye(){ 
		consulta("ObtenerCostoEmplaye","../evaluacion/evaluacionmercanciaresult.php?accion=2&id=2");
	}
	function ObtenerCostoEmplaye(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		var u = document.all;		
		if(con>0){
			var costoemplaye 		= datos.getElementsByTagName('costo').item(0).firstChild.data;	
			var costoextra			= datos.getElementsByTagName('costoextra').item(0).firstChild.data;
			var limite				= datos.getElementsByTagName('limite').item(0).firstChild.data;
			var porcada				= datos.getElementsByTagName('porcada').item(0).firstChild.data;
			
			if(parseFloat(u.totalpeso.value) > parseFloat(u.totalvolumen.value)){
				if(parseFloat(u.totalpeso.value) <= parseFloat(limite)){
					u.txtemplaye.value=u.costoemplaye.value;
				}else{
					var kgextra=parseFloat(u.totalpeso.value) - parseFloat(limite);
					u.txtemplaye.value=parseFloat(costoemplaye) + parseFloat(((kgextra / parseFloat(porcada)) * parseFloat(costoextra)));
					}
					if(u.txtemplaye.value=='NaN'){
						u.txtemplaye.value="";
					}			
				}else{ 
					if(parseFloat(u.totalvolumen.value)<=parseFloat(limite)){
						u.txtemplaye.value=parseFloat(costoemplaye);
					}else{
					var kgextra=parseFloat(u.totalvolumen.value)-parseFloat(limite);
						u.txtemplaye.value=parseFloat(costoemplaye) + parseFloat(((kgextra / parseFloat(porcada)) * parseFloat(costoextra)));
					}
					if(u.txtemplaye.value=='NaN'){
						u.txtemplaye.value="";
					}
				}	
				u.txtemplaye.value = "$ "+numcredvar(u.txtemplaye.value);
		}else{
			alerta3("El Servicio no esta configurado",'Atención!');
		}
	}
	
	function mostrarInformacionExtra(){
		<?=$cpermiso->verificarPermiso(341,$_SESSION[IDUSUARIO]);?>;
		abrirVentanaFija('../guias/informacionextra.php?tipo=2&folio='+document.all.folioSeleccionado.innerHTML, 660, 418, 'ventana', 'Detalle');
	}
	
	function mostrarformapago(){
		var u=document.all;
		abrirVentanaFija('formapago.php?total='+u.t_txttotal.value+'&cliente='+u.clientedelconvenio.value, 600, 400, 'ventana', 'Forma de Pago');
	}
</script>
<style type="text/css">
	.extra-data ul li{ display:block; position:relative; margin:5px; color:#000;}
</style>
</head>

<body>
<?
	list($Mili, $bot) = explode(" ", microtime());
	$DM=substr(strval($Mili),2,3);
?>
<input type="hidden" name="idpagina" id="idpagina" value="<?=$_SESSION[IDUSUARIO].date("ymdHis").$DM?>" />
<input type="hidden" name="folioevaluacion" value="" />
<input type="hidden" name="guiaguardadav" value="" />
<input type="hidden" name="idsucursalorigen" value="" />
<input type="hidden" name="fechaactual" value="<?=date("d/m/Y");?>" />
<input type="hidden" name="restringiread" value="" />
<input type="hidden" style="width:15px" name="convenioaplicado" value="" />
<input type="hidden" style="width:15px" name="clientedelconvenio" value="" />
<input type="hidden" style="width:15px" name="sucursaldelconvenio" value="" />
<input type="hidden" style="width:15px" name="nombrevendedor" value="" />
<input type="hidden" style="width:15px" name="idvendedor" value="" />
<input type="hidden" style="width:15px" name="frontera" value="" />
<input type="hidden" style="width:15px" name="subdestinos" value="" />
<input type="hidden" name="bloquearocurre" value="" />
<input type="hidden" style="width:15px" name="idsucursalrecoleccion" value="" />
<input type="hidden" style="width:15px" name="origensubdestinos" value="" />

<div style="width:820px; height:25px; margin:10px; color:#F00000; font-size:20px; font-weight:bold">
	<div id="folioSeleccionado" style="width:350px; color:#F00000; float:left">&nbsp;</div>
    <div id="numeroRastreo" style="width:450px; color:#36F; text-align:right; float:right">&nbsp;</div>
</div>

<div class="content" style="width:830px">
  <div class="det-guia" style="height:230px"> <span class="date">Fecha:<strong>
  		<?
			$s = "select date_format(current_date, '%d/%m/%Y') as fecha";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
		?>
  <input name="fecha" class="textoSB" readonly="true" type="text" id="fecha" value="<?=$f->fecha ?>" style="width:70px; color:#FFF" />
  </strong>
  </span>
    <div>
      <table width="254">
        <tbody>
          <tr>
            <th>Folio:</th>
            <th><input name="folioguiaempresarial" type="text" class="textoSB" id="folioguiaempresarial" style="width:150px; color:#FFF" readonly="true" /></th>
          </tr>
          <tr>
            <th width="92">Tipo de Guia:</th>
            <th width="150"><input name="tipoguia" type="text" class="textoSB" style="width:150px; color:#FFF" readonly="true" /></th>
          </tr>
		  <tr>
            <th width="92">Tipo Flete:</th>
            <th width="150"><input name="tipoflete" type="text" class="textoSB" id="tipoflete" style="width:150px; color:#FFF" readonly="true" /></th>
          </tr>
          <tr>
            <th width="92">Entrega:</th>
            <th width="150"><select name="chocurre" id="chocurre" class="text" onchange="solicitarDatosConv(); if(document.all.restringiread.value==1){alerta('El destino tiene restringida la Entrega a Domicilio','¡Atencion!','chocurre'); this.value=1;}else{if(this.value==0){document.all.t_txtead.value = document.all.t_txteadh.value}else{document.all.t_txtead.value = '$ 0.00';}} calculartotales();">
                <option value="0" selected="selected">EAD</option>
                <option value="1">Ocurre</option>
              </select></th>
          </tr>
          <tr>
            <th width="92">Cond. Pago:</th width="150">
            <th><select name="tipopago" id="tipopago" class="text" disabled="disabled">
                <option value="CONTADO">Contado</option>
                <option value="CREDITO">Credito</option>
            </select></th>
          </tr>
          <tr>
            <th width="92">Origen:            </th>
            <th width="150"><strong id="nombresucursalorigen"></strong></th>
          </tr>
          <tr>
            <th width="92">Destino:
            <input type="hidden" name="destino_hidden" />
       	    <input type="hidden" name="npobdes" /></th>
            <th width="150"><strong><input type="text" name="destino" readonly="true" id="destino" style="width:150px; color:#FFF" class="textoSB" /></strong></th>
          </tr>
          <tr>
            <th width="92">Suc. Destino:
            <input type="hidden" name="sucdestino_hidden" /></th width="150">
            <th><strong><input name="sucdestino" type="text" id="sucdestino" style="width:150px; color:#FFF" readonly value="" poblacion="" class="textoSB" /></strong></th>
          </tr>
        </tbody>
      </table>
    <a href="#" class="detalles-guia" onClick="mostrarInformacionExtra()">Detalles Guia</a> </div>
  </div>
  <div class="remi-desti" style="height:230px">
    <div class="remi"> <img src="../css_ne/style/arrow.jpg" style="position:absolute; left:260px;"/>
      <div class="title">Remitente</div>
      <span>No. Cliente:<input type="hidden" name="rem_personamoral"></span>
      <input name="idremitente" type="text" readonly="" class="text" value="" />
      <input type="button" id="b_remitente" value=" " class="srch-btn" title="Buscar" style="visibility:hidden" 
      onClick="mostrarBuscador(); tipoClienteBuscado = 'R';"/>
      <input type="button" id="b_remitente_dir" value=" " style="visibility:hidden" class="add-btn" title="Agregar" onClick="mostrarCatClientes(); tipoClienteBuscado = 'R';"/>
      <br />
      <span>R.F.C.:</span><strong><input name="rem_rfc" readonly="true" type="text" style=" width:170px;" class="textoSB"/>
       </strong><br />
      <span>Cliente:</span><strong><input name="rem_cliente" readonly="true" type="text" style="width:170px;" class="textoSB" /></strong><br />
      <span>Calle:</span><strong id="celda_rem_calle">
      <input name="rem_calle" readonly="true" type="text" style="width:170px;" class="textoSB" /><input type="hidden" name="rem_direcciones" id="rem_direcciones">
      </strong><br />
      <div style="float:left; width:270px">
      <span>N&uacute;mero:</span><strong style="width:80px;"><input name="rem_numero" type="text" readonly="true" style=" width:70px;" class="textoSB" /></strong>
      <span>CP:</span><strong style="width:60px;"><input name="rem_cp" readonly="true" type="text" style="width:55px;" class="textoSB" /></strong><br />
      </div>
      <span>Colonia:
      </span><strong><input name="rem_colonia" type="text" readonly="true" style="width:170px" class="textoSB"/></strong><br />
       <span>Población:</span><strong><input name="rem_poblacion" type="text" readonly="true" style="width:170px;" class="textoSB" />
      </strong>
	  <span>Tel&eacute;fono:</span><strong><input name="rem_telefono" type="text" readonly="true" style="width:170px;" class="textoSB" />
      </strong><br />
    </div>
    <div class="desti">
      <div class="title">Destinatario</div>
      <span>No. Cliente:<input type="hidden" name="des_personamoral">
      <input type="hidden" name="paraconvenio" value="0" />
      <input type="hidden" name="iddestinatario_anterior" value="0">
      </span>
      <input name="iddestinatario" onKeyPress="if(event.keyCode==13 && this.readOnly==false){devolverDestinatario(this.value)}else{return solonumeros(event)}" type="text" class="text" onblur="if(this.value!='' && this.readOnly==false && u.iddestinatario_anterior.value!=u.iddestinatario.value){tipoClienteBuscado = 'D'; devolverDestinatario(this.value)}" />
      <input type="button" id="b_destinatario" value=" " class="srch-btn" title="Buscar" onClick="mostrarBuscador(); tipoClienteBuscado = 'D';"/>
      <input type="button" id="b_destinatario_dir" value=" " class="add-btn" title="Agregar" onClick="mostrarCatClientes(); tipoClienteBuscado = 'D';"/>
      <br />
      <span>R.F.C.:</span><strong> <input name="des_rfc" type="text" readonly="true" id="rrfc22" style="width:170px;" class="textoSB" /></strong><br />
      <span>Cliente:</span><strong><input name="des_cliente" readonly="true" type="text" style="width:170px" class="textoSB"/>
      </strong><br />
      <span>Calle:</span><strong id="celda_des_calle"><input name="des_calle" readonly="true" type="text" style="width:140px" class="textoSB"/>
                                    <input type="hidden" name="des_direcciones">
	  <input type="button" id="b_destinatario_dir2" value=" " class="add-btn" title="Agregar" onClick=" tipoClienteBuscado = 'D'; mostrarDirecciones(dirDest,document.all.iddestinatario.value)"/>
      </strong><br />
      
      <div style="float:left; width:270px">
      <span>N&uacute;mero:</span><strong style="width:80px;">
      <input name="des_numero" type="text" readonly="true" style="width:70px;" class="textoSB" />
      </strong>
      <span>CP:</span><strong style="width:60px;"><input name="des_cp" type="text" readonly="true" style="width:55px;" class="textoSB" /></strong><br />
      </div>
      <span>Colonia:
      </span><strong><input name="des_colonia" type="text" readonly="true" style="width:170px;" class="textoSB" /></strong><br />
	  <span>Población:</span><strong><input name="des_poblacion" type="text" readonly="true" style="width:170px" class="textoSB" /></strong>
      <span>Tel&eacute;fono:</span><strong><input name="des_telefono" type="text" readonly="true" style="width:170px" class="textoSB" /></strong><br />
    </div>
  </div>
  <div class="description">
  	<div class="grid-detallado" style="text-align:right">
    <table border="0" cellpadding="0" cellspacing="0" id="tablaconteva">
      </table>
    </div>
    <div class="tiempo-entrega">
      <div class="title">Tiempo de Entrega</div>
      <span>Ocurre:</span><strong><input name="txtocu" readonly="true" type="text" style="width:20px; color:#FFF" class="textoSB" />
      </strong> <span>EAD:</span><strong>
      <input name="txtead" readonly="true" type="text" style="width:20px; color:#FFF" class="textoSB" />
      <input name="txteadh" type="hidden" />
      </strong> 
      <span style="width:100px">Restricciones:</span><strong style="width:150px; margin-left:10px;">
      <textarea name="txtrestrinccion" readonly style="width:140px; color:#FFF" class="textoSB"></textarea>
                            <input name="txtrestrinccionh" type="hidden" />
    </strong> </div>
  </div>
  <div class="extra-data" style="height:260px">
    <ul>
      <li>Total de Paquetes: <input name="totalpaquetes" type="text" readonly="true" style="font-size:9px; width:40px" class="textoSB"/></li>
      <li>Total de Peso: 
      <input name="totalpeso" type="text" readonly="true" style="font-size:9px; width:40px" class="textoSB" /> Kg</li>
      <li>Total de Volumen: <input name="totalvolumen" type="text" readonly="true" style="font-size:9px; width:60px" class="textoSB" /></li>
      <li>
      
        <input name="chkemplaye" type="checkbox" style="width:8px; height:8px" value="SI" onclick="if(!this.checked){document.all.txtemplaye.value='';}else{document.all.txtemplaye.value = document.all.txtemplayeh.value} calculartotales();" />
        Emplaye
        <input name="txtemplaye" readonly="true" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" size="10" />
                              <input name="txtemplayeh" type="hidden" />
      </li>
      <li>
        <input name="chkbolsaempaque" type="checkbox" style="width:8px; height:8px" value="SI" onclick="if(!this.checked){document.all.txtbolsaempaque1.value = ''; document.all.txtbolsaempaque2.value = ''; document.all.txtbolsaempaque1.readOnly=true; document.all.txtbolsaempaque1.style.backgroundColor='#d9e6f0';}else{ if(document.all.txtbolsaempaque1h.value=='' || document.all.txtbolsaempaque1h.value=='0'){document.all.txtbolsaempaque1.readOnly=false; document.all.txtbolsaempaque1.style.backgroundColor='#FFFFFF';}else{document.all.txtbolsaempaque1.value = document.all.txtbolsaempaque1h.value; document.all.txtbolsaempaque2.value = document.all.txtbolsaempaque2h.value;}} calculartotales();" />
      Envase: <strong style="width:60px;">
      <input name="txtbolsaempaque1" readonly="true" onBlur="if(this.readOnly==false){calculartotales();}" onKeyPress="if(this.readOnly==false && event.keyCode==13){document.all.txtbolsaempaque2.value='$ '+numcredvar((parseFloat((document.all.txtbolsaempaque3h.value=='')?'0':document.all.txtbolsaempaque3h.value.replace('$ ', '').replace(/,/g,''))*parseFloat(this.value)).toLocaleString());calculartotales();}else{return solonumeros(event);}" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="1" />
      <input name="txtbolsaempaque2" readonly="true" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" size="6" />
      <span class="Tablas">
      <input name="txtbolsaempaque1h" type="hidden" />
      <input name="txtbolsaempaque2h" type="hidden" />
      <input name="txtbolsaempaque3h" type="hidden" />
      </span></strong></li>
      <li><input name="chkavisocelular" type="checkbox" style="width:8px; height:8px" value="SI" onClick="if(!this.checked){document.all.txtavisocelular2.readOnly=true; document.all.txtavisocelular2.style.backgroundColor='#d9e6f0'; document.all.txtavisocelular2.value='';document.all.txtavisocelular1.value=''; }else{document.all.txtavisocelular1.value=document.all.txtavisocelular1h.value;document.all.txtavisocelular2.readOnly=false; document.all.txtavisocelular2.style.backgroundColor='#FFFFFF'; document.all.txtavisocelular2.value=document.all.txtavisocelular2h.value;document.all.txtavisocelular2.focus();}  calculartotales();" /> 
        Aviso Celular: <strong>
        <input name="txtavisocelular1" readonly="true" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right; width:40px" />
        <input name="txtavisocelular2" readonly="true" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; width:40px" value="<?=$rrfc ?>" />
        <input name="txtavisocelular1h" type="hidden" />
                            <input name="txtavisocelular2h" type="hidden" />
      </strong></li>
      <li><input name="chkvalordeclarado" type="checkbox" style="width:8px; height:8px" value="SI"
               onClick="if(!this.checked){document.all.txtdeclarado.value='';document.all.txtdeclarado.readOnly=true; document.all.txtdeclarado.style.backgroundColor='#d9e6f0'; document.all.txtdeclarado.readOnly=true;}else{document.all.txtdeclarado.readOnly=false; document.all.txtdeclarado.style.backgroundColor='#FFFFFF'; document.all.txtdeclarado.readOnly=false;document.all.txtdeclarado.focus();} calculartotales();" /> 
        Valor Declarado: <strong>
               <?
			  	$s = "SELECT maxvalordeclaradoguia FROM configuradorgeneral";
				$rmvd = mysql_query($s,$l) or die($s);
				$fmvd = mysql_fetch_object($rmvd);
			  ?>
                              <input name="txtdeclarado" type="text" readonly="true" onBlur="if(this.readOnly==false){this.value=this.value.replace('$ ','')
.replace(/,/g,'');  if(this.value==''){this.value='$ 0.00';}else{ if(parseFloat(this.value) > <?=$fmvd->maxvalordeclaradoguia?>){this.value = <?=$fmvd->maxvalordeclaradoguia?>; alerta3('El maximo valor declarado permitido es <?=$fmvd->maxvalordeclaradoguia?>', '¡Atencion!');} this.value='$ '+numcredvar(this.value); calculartotales(); }}" onKeyPress="if(this.readOnly==false){ if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','')
.replace(/,/g,'')); calculartotales();}else{return tiposMoneda(event,this.value);}} " style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" onfocus="this.value = this.value.replace('$ ','')
.replace(/,/g,''); this.select();" value="<?=$rrfc ?>" size="10" />
      </strong></li>
      <li><input name="chkacuserecibo" onClick="if(!this.checked){document.all.txtacuserecibo.value='';}else{document.all.txtacuserecibo.value=document.all.txtacusereciboh.value;} calculartotales();" type="checkbox" style="width:8px; height:9px" value="SI" />Acuse Recibo: <strong>
      <input readonly="true" name="txtacuserecibo" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtacusereciboh" type="hidden" />
      </strong></li>
      <li><input name="chkcod" onClick="if(!this.checked){document.all.txtcod.value='';}else{document.all.txtcod.value=document.all.txtcodh.value;} calculartotales();" type="checkbox" style="width:8px; height:8px" value="SI" />COD: <strong>
      <input readonly="true" name="txtcod" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtcodh" type="hidden" />
      </strong></li>
      <li>Recolecci&oacute;n: <strong>
      <input readonly="true" name="txtrecoleccion" type="text" style="background:#d9e6f0;font:tahoma; font-size:9px; text-align:right; text-align:right" value="<?=$rrfc ?>" size="8" />
                              <input name="txtrecoleccionh" type="hidden" />
      </strong></li>
      <li style="display:none">Otros: <strong>
      <input name="txtotrosAgregado" type="text" style="font:tahoma; font-size:9px; text-align:right; text-align:right" value="<?=$txtotros ?>" size="8" onkeypress="if(event.keyCode==13){calculartotales();}else{return solonumeros(event);}" onblur="calculartotales();" />
      </strong></li>
    </ul>
  </div>
  <div class="totales" style="height:260px">
    <table style="width:200px; margin-left:10px; margin-top:2px; font-size:11px;">
      <thead>
      </thead>
      <tbody>
        <tr>
          <th width="79">Flete:</th>
          <th width="109">
          	<input name="flete" readonly="true" type="text" style="width:72px; font-size:9px; text-align:right" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>Desc:</th>
          <th>
          	<input readonly="true" name="t_txtdescuento1" type="text" style="font-size:9px; text-align:right; width:20px;" class="textoSB" value="<?=$rrfc ?>" onKeyPress="if(event.keyCode==13 && this.readOnly==false){ if(parseFloat(this.value)>parseFloat(document.all.pc_maximodescuento.value)){ this.value=document.all.pc_maximodescuento.value; alerta('El maximo descuento permitido es '+document.all.pc_maximodescuento.value+' %','¡Atencion!','t_txtdescuento1')} calcularDescuento()}else{return solonumeros(event);}" />
            <input name="t_txtdescuento2" type="text" readonly="true" style="font-size:9px; text-align:right; width:50px" class="textoSB" />
                        <img id="img_descuento" src="../img/update.gif" onClick="if(validarDescuento()){ abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=permitirDescuento', 370, 500, 'ventana', 'Inicio de Sesión Secundaria');}" style="cursor:hand">
          </th>
        </tr>
        <tr>
          <th>EAD:<input name="t_txteadh" type="hidden" />
                      <input name="t_txteadh2" type="hidden" /></th>
          <th><input readonly="true" name="t_txtead" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB" /></th>
        </tr>
        <tr>
          <th>Recolecci&oacute;n:<input name="t_txtrecoleccionh" type="hidden" /></th>
          <th>
          <input readonly="true" name="t_txtrecoleccion" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>Seguro:</th>
          <th>
          <input readonly="true" name="t_txtseguro" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB"/>
          </th>
        </tr>
        <tr>
          <th>Otros:</th>
          <th>
          <input name="t_txtotros" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>Excedente:</th>
          <th><input name="t_txtexcedente" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB"/></th>
        </tr>
        <tr>
          <th>Combustible:</th>
          <th>
          <input name="t_txtcombustible" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px;" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>Subtotal:</th>
          <th><input name="t_txtsubtotal" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px" class="textoSB" /></th>
        </tr>
        <tr>
          <th>IVA:</th>
          <th>
          <input name="t_txtiva" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px" class="textoSB" />
          </th>
        </tr>
        <tr>
          <th>IVA Ret.:</th>
          <th><input name="t_txtivaretenido" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px" class="textoSB" /></th>
        </tr>
        <tr>
          <th>Total:</th>
          <th><strong style="color:#F00"><input name="t_txttotal" readonly="true" type="text" style="font-size:9px; text-align:right; width:72px" class="textoSB" /></strong></th>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="pc_ead" />
    <input type="hidden" name="pc_recoleccion" />
    <input type="hidden" name="pc_porcada" />
    <input type="hidden" name="pc_costo" />
    <input type="hidden" name="pc_porcadah" />
    <input type="hidden" name="pc_costoh" />
    <input type="hidden" name="pc_vdlimite" />
    <input type="hidden" name="pc_vdcostoextra" />
    <input type="hidden" name="pc_vdlimiteh" />
    <input type="hidden" name="pc_vdcostoextrah" />
    <input type="hidden" name="pc_tarifacombustible" />
    <input type="hidden" name="pc_iva" />
    <input type="hidden" name="pc_ivaretenido" />
    <input type="hidden" name="pc_maximodescuento" />
    <input type="hidden" name="pc_pesominimodesc" />
    <input type="hidden" name="desead" />
    <input type="hidden" name="desrrecoleccion" />
    <input type="hidden" name="desporcobrar" />
    <span class="Tablas">
    <input type="hidden" value="0" name="pagoregistrado" />
    <input type="hidden" value="" name="efectivo" />
    <input type="hidden" value="" name="cheque" />
    <input type="hidden" value="" name="ncheque" />
    <input type="hidden" value="" name="banco" />
    <input type="hidden" value="" name="tarjeta" />
    <input type="hidden" value="" name="transferencia" />
    <input type="hidden" value="" name="pagominimocheque" />
    <input type="hidden" value="" name="nc" />
    <input type="hidden" value="" name="nc_folio" />
    <input type="hidden" name="motivocancelacion" />
    <span class="FondoTabla"><span class="FondoTabla Estilo4">
    <input type="hidden" name="desconvenio" />
    <input type="hidden" name="creditodisponible" />
    </span></span></span></div>
  <div class="observaciones" style="height:260px"> 
  	<strong style="width:400px"><span id="estado" style="width:250px;"></span><span id="unidadSubida" style="width:140px"></span></strong>
    <strong style="width:400px"><span id="sustitucionguia" style="width:395px;"></span></strong>
    <strong style="width:400px; margin-left:5px;">
    <span style="width:182px; float:left" id="embarcadafaltantes"></span><span style="width:182px; float:left; color:#36F;" id="foliodev"></span>
    </strong>
    <strong style="width:400px; margin-left:5px;" id="danosfaltantes"></strong> 
    <strong style="width:400px; margin-left:5px;" id="entregafaltantes"></strong> 
    <span style="width:390px">Observaciones: <strong style="color:#555; float:none; display:inline;">
    <textarea name="txtobservaciones" class="textoSB" readonly="readonly" rows="3" style="width:390px; font-size:9px; font:tahoma; text-transform:uppercase" onDblClick="if(document.all.folioevaluacion.value==''){ alerta3('Porfavor seleccione la evaluacion para agregar observaciones', '¡Atencion!'); }else{ abrirVentanaFija('datosFronteras.php?datos='+this.value, 450, 318, 'ventana', 'Datos Frontera') }"></textarea>
    </strong></span> <span>Recibi&oacute;:</span>
    <strong><input type="text" name="recibio" readonly="true" style="text-transform:uppercase; width:290px" class="textoSB" ></strong>
    <span>Fecha entrega:</span>
    <strong><input type="text" name="fechaentrega" readonly="true" style="text-transform:uppercase;width:290px " class="textoSB" ></strong>
    <span>Factura:</span><strong>
    <input type="text" name="factura" readonly="true" style="text-transform:uppercase; width:290px;" class="textoSB" />
    </strong>
    <span>Fecha Cancelacion</span><strong>
    <input type="text" name="fechacancelacion" id="fechacancelacion" style="text-transform:uppercase; width:290px;" class="textoSB" />
    </strong> 
    </strong>
    <span>Sector</span><strong>
    <input type="text" name="sectorguia" id="sectorguia" style="text-transform:uppercase; width:290px;" class="textoSB" />
    </strong> 
    </div>
    
    <div id="idsguardar" style="text-align:center; margin:10px; float:left; width:820px;">
    <img src="../img/Boton_Guardar.gif" style="cursor:hand" onClick="if(validarDatos() && valcreditodisponible()){ejecutarSubmit();};">&nbsp;&nbsp; <img src="../img/Boton_Nuevo.gif" style="cursor:hand" onClick="limpiar_evaluacion();limpiar_destinatario();limpiar_remitente();">
    </div>
    
<?
	$raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
	
	$raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "CatClientes";
	$funcionMostrar = "mostrarCatClientes";
	$funcionOcultar = "ocultarCatClientes";
	include("../buscadores_generales/clientesIncrustado.php");
	
	$raiz = "../";
	$funcion = "ponerDireccion";
	$nombreBuscador = "catDirecciones";
	$funcionMostrar = "mostrarDirecciones";
	$funcionOcultar = "ocultarDirecciones";
	include("../buscadores_generales/direccionesIncrustadas.php");
?> 

</div>
</body>
</html>