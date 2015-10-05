<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd 
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc; 	
		}				
		$desc=substr($desc, 0, -1);
	}
	
	$s = "SELECT eti_nombre1, eti_nombre2, eti_direccion, eti_colonia,eti_ciudad, eti_rfc FROM configuradorgeneral";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$empresa = $f->eti_nombre1."|".$f->eti_nombre2."|".$f->eti_direccion."|".$f->eti_colonia."|".$f->eti_ciudad."|".$f->eti_rfc;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../javascript/ajaxlist/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/funcionesDrag.js"></script>
<script src="../javascript/funciones.js"></script>
<OBJECT ID="Etiqueta" CLASSID="CLSID:0124E5BC-E21C-4A00-B1F2-ED81FDBD9D40"
CODEBASE="https://www.pmmintranet.net/software/ImpEtiqueta.CAB#version=24,0,0,0">
</OBJECT>
<script>
	var datosEmpresa = '<?=$empresa?>';
	<?
		$s = "SELECT impetiquetasguias,impetiquetaspaquetes FROM configuracion_impresoras WHERE usuario = '$_SESSION[IDUSUARIO]'";
		$rxy = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($rxy)>0){
			$fxy = mysql_fetch_object($rxy);
		}else{
			$s = "SELECT impetiquetasguias,impetiquetaspaquetes FROM configuracion_impresoras WHERE sucursal = '$_SESSION[IDSUCURSAL]'";
			$rxy = mysql_query($s,$l) or die($s);
			$fxy = mysql_fetch_object($rxy);
		}
		
		$impresoraPaquetes 	= $fxy->impetiquetaspaquetes;
		$impresoraGuias		= $fxy->impetiquetasguias;
		
		$s = "SELECT id,prefijo FROM catalogosucursal";
		$r = mysql_query($s,$l) or die($s);
		$var = "";
		while($f = mysql_fetch_object($r)){
			$var .= (($var!="")?",":"")."'$f->id':'$f->prefijo'";
		}
		
		$s = "SELECT cd.id destino, cs.id sucursal
		FROM catalogosucursal cs
		INNER JOIN catalogodestino cd ON cs.id = cd.sucursal";
		$r = mysql_query($s,$l) or die($s);
		$vard = "";
		while($f = mysql_fetch_object($r)){
			$vard .= (($vard!="")?",":"")."'$f->destino':'$f->sucursal'";
		}
	?>
	var obtenerPrefijos = eval("({<?=$var?>})");
	var obtenerSucursal = eval("({<?=$vard?>})");

	var tabla1 	= new ClaseTabla();
	var	u		= document.all;
	var mens 	= new ClaseMensajes();
	mens.iniciar('../javascript',true);
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"CANTIDAD", medida:50, alineacion:"left", datos:"cantidad"},			
			{nombre:"DESCRIPCION", medida:80, alineacion:"left", datos:"descripcion"},
			{nombre:"IDDESCRIPCION", medida:4, tipo:"oculto", alineacion:"left", datos:"iddescripcion"},
			{nombre:"CONTENIDO", medida:80, alineacion:"left", datos:"contenido"},
			{nombre:"PESO", medida:60, alineacion:"left",  datos:"peso"},
			{nombre:"VOLUMEN", medida:60, alineacion:"left", datos:"volumen"},
			{nombre:"IMPORTE", medida:6, tipo:"oculto", alineacion:"left", datos:"importe"},
			{nombre:"PESO2", medida:4, tipo:"oculto", alineacion:"left", datos:"peso2"},
			{nombre:"ANCHO", medida:4, tipo:"oculto", alineacion:"left", datos:"ancho"},
			{nombre:"LARGO", medida:4, tipo:"oculto", alineacion:"left", datos:"largo"},
			{nombre:"ALTO", medida:4, tipo:"oculto", alineacion:"left", datos:"alto"},			
			{nombre:"PESOUNIT", medida:4, tipo:"oculto", alineacion:"left", datos:"pesounit"},
			{nombre:"PESOTOTAL", medida:4, tipo:"oculto", alineacion:"left", datos:"pesototal"},
			{nombre:"FECHA", medida:4, tipo:"oculto", alineacion:"left", datos:"fecha"}
		],
		filasInicial:30,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"detalle"
	});
	
	window.onload = function(){
		tabla1.create();
		u.guia.focus();		
		u.d_eliminar.style.visibility = "hidden";
		u.idsucursal.value = '<?=$_SESSION[IDSUCURSAL]?>';
		obtenerGeneral();
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","devolucionGuia_con.php?accion=0&sucursal="+u.idsucursal.value);
	}
	function mostrarGeneral(datos){		
		var obj = eval(convertirValoresJson(datos));
		u.fechadev.value = obj[0].fecha;
		u.folio.value	 = obj[0].folio;
		u.sucursal.value = obj[0].sucursal
	}	
	
	function devolverDestino(){
		if(u.destino_hidden.value==""){
			setTimeout("devolverDestino()",500);
		}else{
			consultaTexto("mostrarDestino", "devolucionGuia_con.php?accion=2&iddestino="+u.destino_hidden.value);
		}
	}
	function mostrarDestino(datos){
		var obj = eval(convertirValoresJson(datos));
		u.sucdestino.value 			= obj[0].descripcion;
		u.sucdestino_hidden.value 	= obj[0].iddestino;
		consultaTexto("mostrarTiempoEntrega","devolucionGuia_con.php?accion=6&destino="+u.destino_hidden.value+
		"&idsucorigen="+u.idsucursal.value);
	}
	function mostrarTiempoEntrega(datos){
		var row = datos.split(",");
		u.txtocu.value				= row[0]
		u.txtead.value				= row[1]
		u.txtrestrinccion.value		= (row[2]!="0")? row[2] : "";		
		abrirVentanaFija('datosMercanciaDevolucion.php?funcion=agregarDatos&sucursal='+u.idsucursal.value, 450, 350, 'ventana', 'Datos Mercancia');
	}
	function validarGuia(e,obj){
		tecla	= (u) ? e.keyCode : e.which;
		if((tecla==46 || tecla==8) && document.getElementById(obj).value==""){
			limpiar("1");
		}
	}
	function obtenerGuiaBusqueda(folio){
		u.guia.value = folio;
		consultaTexto("respuestaGuia","devolucionGuia_con.php?accion=1&folio="+folio+"&rand="+Math.random());
	}
	function obtenerGuia(folio){
		u.guia.value = folio;
		consultaTexto("respuestaGuia","devolucionGuia_con.php?accion=1&folio="+folio+"&rand="+Math.random());
	}
	function respuestaGuia(datos){
		try{
			var completo = eval(datos);
		}catch(e){
			alerta3("Error "+datos,"");
			return false;
		}
		var obj = completo.reg;
		
		tabla1.setJsonData(completo.det);
		
		var det = completo.det;
		var tpa = 0;
		var tpe = 0;
		var tvo = 0;
		for(var i=0; i<det.length; i++){
			tpa += parseFloat(det[i].cantidad);
			tpe += parseFloat(det[i].peso);
			tvo += parseFloat(det[i].volumen);
		}
		
		u.totalpaquetes.value = tpa;
		u.totalpeso.value = tpe;
		u.totalvolumen.value = tvo;
		
		u.fecha.value				= obj[0].fecha;
		var estado					= obj[0].estado;		
		var ocurre					= obj[0].ocurre;
		var idsucursalorigen		= obj[0].idsucursalorigen;		
		var condicionpago			= obj[0].condicionpago;		
		u.idremitente.value			= obj[0].idremitente;
		u.rem_cliente.value			= obj[0].rncliente;
		u.rem_rfc.value				= obj[0].rrfc;
		u.rem_calle.value			= obj[0].rcalle;
		u.rem_numero.value			= obj[0].rnumero;
		u.rem_cp.value				= obj[0].rcp;
		u.rem_poblacion.value		= obj[0].rpoblacion;
		u.rem_telefono.value		= obj[0].rtelefono;
		u.rem_colonia.value			= obj[0].rcolonia;		
		u.iddestinatario.value		= obj[0].iddestinatario;
		u.des_cliente.value			= obj[0].dncliente;
		u.des_rfc.value				= obj[0].drfc;
		u.des_calle.value			= obj[0].dcalle;
		u.des_numero.value			= obj[0].dnumero;
		u.des_cp.value				= obj[0].dcp;
		u.des_poblacion.value		= obj[0].dpoblacion;
		u.des_telefono.value		= obj[0].dtelefono;
		u.des_colonia.value			= obj[0].dcolonia;			
		var emplaye					= obj[0].emplaye;
		var bolsaempaque			= obj[0].bolsaempaque;
		var totalbolsaempaque		= obj[0].totalbolsaempaque;
		var avisocelular			= obj[0].avisocelular;
		var celular					= obj[0].celular;
		var valordeclarado			= obj[0].valordeclarado;
		var acuserecibo				= obj[0].acuserecibo;
		var cod						= obj[0].cod;
		var recoleccion				= obj[0].recoleccion;
		var observaciones			= obj[0].observaciones;
		u.estado.innerHTML			= estado;
		u.lstpago.value				= condicionpago;
		u.idsucursalorigen.value	= <?=$_SESSION[IDSUCURSAL]?>;
		
		if(obj[0].emp=="0"){		
			if(condicionpago=="1" || condicionpago=="0"){
				u.lstpago.value = ((condicionpago=="0")? "CONTADO" : "CREDITO" );
			}
			if(obj[0].tipoflete=="1" || obj[0].tipoflete=="0"){
				u.tipoflete.value = ((obj[0].tipoflete=="0")? "PAGADO" : "POR COBRAR" );
			}
		}
		
		
		if(obj[0].emp=="1"){
			if(condicionpago=="1" || condicionpago=="0"){
				u.lstpago.value = ((condicionpago=="0")? "CONTADO" : "CREDITO" );
			}
			if(obj[0].tipoflete=="0" || obj[0].tipoflete=="1"){
				u.tipoflete.value = ((obj[0].tipoflete=="0")? "PAGADO" : "POR COBRAR" );
			}
		}
		
		  if(tabla1.getRecordCount()==0){		
			  u.d_eliminar.style.visibility = "hidden";
		  }else{
			  u.d_eliminar.style.visibility = "visible";
		  }
		  
		u.txtobservaciones.value = observaciones;
		u.destino.select();
	}
	function mostrarDetalle(datos){
		tabla1.clear();
		var obj = eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj);
	}	
	function agregarDatos(objeto){
		if(u.index.value!=""){			
			tabla1.updateRowById(tabla1.getSelectedIdRow(), objeto);			
			u.index.value	= "";
			obtenerTotalesDetalle();
		}else{
				
			tabla1.add(objeto);
			
			u.d_eliminar.style.visibility ="visible";
			obtenerTotalesDetalle();
		}
	}
	function obtenerTotalesDetalle(){
		var paq = ""; var peso = ""; var vol = "";
		v_paq = 0; v_peso = 0; v_vol = 0; 
	
		paq  = tabla1.getValuesFromField("cantidad",",").split(",");
		peso = tabla1.getValuesFromField("peso",",").split(",");
		vol  = tabla1.getValuesFromField("volumen",",").split(",");
	
		for(var i=0;i<paq.length;i++){
			v_paq = parseFloat(paq[i]) + parseFloat(v_paq);
		}
		u.totalpaquetes.value = v_paq;			
		//esNan('totalpaquetes');
		for(var i=0;i<peso.length;i++){
			v_peso = parseFloat(peso[i]) + parseFloat(v_peso);
		}
		u.totalpeso.value = v_peso;
		//esNan('totalpeso');
		for(var i=0;i<vol.length;i++){
			v_vol = parseFloat(vol[i]) + parseFloat(v_vol);
		}
		u.totalvolumen.value = v_vol;
		//esNan('totalvolumen');
		
		if(tabla1.getRecordCount()=="0"){
			u.totalpaquetes.value = "";
			u.totalpeso.value = "";
			u.totalvolumen.value = "";
		}
	}
	function esNan(caja){	
		if(document.getElementById(caja).value.replace("$ ","").replace(/,/g,"")=="NaN"){
			document.getElementById(caja).value = "0";
		}
	}
	function ModificarFila(){
		var obj = tabla1.getSelectedRow();		
		if(tabla1.getValSelFromField("cantidad","CANTIDAD")!=""){
		u.index.value	= tabla1.getSelectedIndex();		
		abrirVentanaFija('datosMercanciaDevolucion.php?funcion=agregarDatos&cantidad='+obj.cantidad
		+'&id='+obj.iddescripcion
		+'&descripcion='+obj.descripcion
		+'&contenido='+obj.contenido
		+'&peso='+obj.peso
		+'&largo='+obj.largo
		+'&ancho='+obj.ancho
		+'&alto='+obj.alto
		+'&pesototal='+obj.pesototal
		+'&pesounit='+obj.pesounit
		+'&volumen='+obj.volumen
		+'&fecha='+obj.fecha
		+'&sucursal='+u.idsucursal.value
		+'&tipo=modificar', 450, 350, 'ventana', 'Datos Mercancia');
		}
	}
	function eliminarFila(){
		if(tabla1.getValSelFromField('cantidad','CANTIDAD')!=""){
			mens.show('C','¿Esta seguro de Eliminar la Fila?','','','borrarFila()','');
		}
	}
	function borrarFila(){
		var obj = tabla1.getSelectedRow();
		consultaTexto("eliminoFila","devolucionGuia_con.php?accion=5&fecha="+obj.fecha);
	}
	function eliminoFila(datos){
		if(datos.indexOf("ok")>-1){
		  tabla1.deleteById(tabla1.getSelectedIdRow());
		  if(tabla1.getRecordCount()==0){		
			  u.d_eliminar.style.visibility = "hidden";
		  }else{
			  u.d_eliminar.style.visibility = "visible";
		  }
		  obtenerTotalesDetalle();
		}else{
			mens.show("A","Hubo un error al eliminar "+datos,"¡Atención!","");
		}
	}
	function limpiar(tipo){
		document.getElementById('folioguia').innerHTML= "";
		u.fecha.value				= "";
		u.tipoflete.value			= "";
		u.idremitente.value			= "";
		u.rem_cliente.value			= "";
		u.rem_rfc.value				= "";
		u.rem_calle.value			= "";
		u.rem_numero.value			= "";
		u.rem_cp.value				= "";
		u.rem_poblacion.value		= "";
		u.rem_telefono.value		= "";
		u.rem_colonia.value			= "";		
		u.iddestinatario.value		= "";
		u.des_cliente.value			= "";
		u.des_rfc.value				= "";
		u.des_calle.value			= "";
		u.des_numero.value			= "";
		u.des_cp.value				= "";
		u.des_poblacion.value		= "";
		u.des_telefono.value		= "";
		u.des_colonia.value			= "";
		u.txtocu.value				= "";
		u.txtead.value				= "";
		u.txtrestrinccion.value		= "";
		u.totalpaquetes.value		= "";
		u.totalpeso.value			= "";
		u.totalvolumen.value		= "";
		u.estado.innerHTML			= "";
		u.lstpago.value				= "";
		u.txtemplaye.value 			= "";
		u.txtacuserecibo.value 		= "";
		u.txtbolsaempaque1.value 	= "";
		u.txtbolsaempaque2.value	= "";
		u.txtcod.value 				= "";
		u.txtavisocelular1.value 	= "";
		u.txtavisocelular2.value 	= "";
		u.txtrecoleccion.value 		= "";
		u.txtdeclarado.value		= "";
		u.txtobservaciones.value 	= "";
		u.guia.value				= "";
		u.chkemplaye.checked		= false;
		u.chkacuserecibo.checked	= false;
		u.chkbolsaempaque.checked	= false;
		u.chkcod.checked			= false;
		u.chkavisocelular.checked	= false;
		u.chkvalordeclarado.checked	= false;
		u.chkrecoleccion.checked	= false;
		if(tipo==""){
			u.destino.value				= "";
			u.sucdestino.value			= "";
			u.sucdestino_hidden.value	= "";
			u.destino_hidden.value		= "";
		}
		if(tipo=="1"){
			u.destino.value				= "";
			u.sucdestino.value			= "";
			u.sucdestino_hidden.value	= "";
			u.destino_hidden.value		= "";
		}
		u.d_guardar.style.visibility = "visible";
		tabla1.clear();
		obtenerGeneral();
	}
	function guardar(){
		<?=$cpermiso->verificarPermiso(334,$_SESSION[IDUSUARIO]);?>
		if(u.guia.value == ""){
			mens.show("A","Debe capturar Numero de Guia","¡Atención!","");
		}else if(u.destino_hidden.value==undefined || u.destino.value == ""){
			mens.show("A","Debe capturar Destino","¡Atención!","destino");
		}else if(tabla1.getRecordCount()=="0"){
			mens.show("A","Debe capturar la mercancia a devolver","¡Atención!","");
		}else{
			u.d_guardar.style.visibility = "hidden";
			var arr = new Array();
			arr[0]	= u.fecha.value;
			arr[1]	= u.idsucursal.value;
			arr[2]	= u.guia.value;			
			arr[3]	= u.destino_hidden.value;
			arr[4]	= u.txtocu.value;
			arr[5]	= u.txtead.value;
			arr[6]	= u.txtrestrinccion.value;
			arr[7]	= u.totalpaquetes.value;
			arr[8]	= u.totalpeso.value;
			arr[9]	= u.totalvolumen.value;
			arr[10]	= u.txtobservaciones.value;
			arr[11]	= (u.txtdeclarado.value!="")? u.txtdeclarado.value : 0;
			arr[12]	= (u.chkvalordeclarado.checked == true)? "1" : "0";
			arr[13] = u.sucdestino_hidden.value;
			if(u.guia.value.substring(0,3)=="999"){
				arr[14] = "empresarial";
			}else if(u.guia.value.substring(0,3)!="777" && u.guia.value.substring(0,3)!="888"){
				arr[14] = "normal";
			}			
			consultaTexto("registroDevolucion","devolucionGuia_con.php?accion=7&arre="+arr+"&m="+Math.random());
		}
	}
	function registroDevolucion(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			mens.show("I","Los datos se guardarón satisfactoriamente","");
			u.folioguia.innerHTML = row[1];
			u.d_guardar.style.visibility = "hidden";
			u.d_eliminar.style.visibility = "hidden";
			u.d_agregar.style.visibility = "hidden";
			impGuia();
		}else{
			u.d_guardar.style.visibility = "visible";
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!","");
		}
	}
	function obtenerDevolucionBusqueda(folio){
		u.folio.value	= folio;
		consultaTexto("mostrarDevolucion","devolucionGuia_con.php?accion=8&devolucion="+folio);
	}
	function mostrarDevolucion(datos){
		var obj	= eval(convertirValoresJson(datos));	
		
		u.fecha.value				= 	obj[0].fechadevolucion;
		u.idsucursal.value			=	obj[0].sucursal;
		u.sucursal.value			=	obj[0].dessucursal;
		u.guia.value				=	obj[0].guia;
		u.folioguia.innerHTML 		= 	obj[0].nuevaguia;
		u.destino.value				=	obj[0].ddestino;
		u.destino_hidden.value		=	obj[0].destino;		
		u.txtocu.value				=	obj[0].tocurre;
		u.txtead.value				=	obj[0].tead;
		u.txtrestrinccion.value		=	obj[0].restrinccion;
		u.totalpaquetes.value		=	obj[0].tpaquete;
		u.totalpeso.value			=	obj[0].tpeso;
		u.totalvolumen.value		=	obj[0].tvolumen;
		u.txtobservaciones.value	=	obj[0].observaciones;
		u.txtdeclarado.value		=	obj[0].tvalordeclarado;
		u.chkvalordeclarado.checked = 	((obj[0].valordeclarado==1)? true : false);
		u.sucdestino_hidden.value	=	obj[0].sucursaldestino;
		u.sucdestino.value			=	obj[0].dessucdestino;
		
		u.fecha.value				= obj[0].fecha;
		var estado					= obj[0].estado;		
		var ocurre					= obj[0].ocurre;
		var idsucursalorigen		= obj[0].idsucursalorigen;		
		var condicionpago			= obj[0].condicionpago;		
		u.idremitente.value			= obj[0].idremitente;
		u.rem_cliente.value			= obj[0].rncliente;
		u.rem_rfc.value				= obj[0].rrfc;
		u.rem_calle.value			= obj[0].rcalle;
		u.rem_numero.value			= obj[0].rnumero;
		u.rem_cp.value				= obj[0].rcp;
		u.rem_poblacion.value		= obj[0].rpoblacion;
		u.rem_telefono.value		= obj[0].rtelefono;
		u.rem_colonia.value			= obj[0].rcolonia;		
		u.iddestinatario.value		= obj[0].iddestinatario;
		u.des_cliente.value			= obj[0].dncliente;
		u.des_rfc.value				= obj[0].drfc;
		u.des_calle.value			= obj[0].dcalle;
		u.des_numero.value			= obj[0].dnumero;
		u.des_cp.value				= obj[0].dcp;
		u.des_poblacion.value		= obj[0].dpoblacion;
		u.des_telefono.value		= obj[0].dtelefono;
		u.des_colonia.value			= obj[0].dcolonia;			
		var emplaye					= obj[0].emplaye;
		var bolsaempaque			= obj[0].bolsaempaque;
		var totalbolsaempaque		= obj[0].totalbolsaempaque;
		var avisocelular			= obj[0].avisocelular;
		var celular					= obj[0].celular;
		var valordeclarado			= obj[0].valordeclarado;
		var acuserecibo				= obj[0].acuserecibo;
		var cod						= obj[0].cod;
		var recoleccion				= obj[0].recoleccion;
		var observaciones			= obj[0].observaciones;
		u.estado.innerHTML			= estado;
		u.lstpago.value				= condicionpago;
		u.idsucursalorigen.value	= idsucursalorigen;
		
		if(obj[0].emp=="0"){		
			if(condicionpago=="1" || condicionpago=="0"){
				u.lstpago.value = ((condicionpago=="0")? "CONTADO" : "CREDITO" );
			}
			if(obj[0].tipoflete=="1" || obj[0].tipoflete=="0"){
				u.tipoflete.value = ((obj[0].tipoflete=="0")? "PAGADO" : "POR COBRAR" );
			}
		}
		
		if(obj[0].emp=="1"){
			if(condicionpago=="CONTADO" || condicionpago=="CREDITO"){
				u.lstpago.value = ((condicionpago=="CONTADO")? "CONTADO" : "CREDITO" );
			}
			if(obj[0].tipoflete=="PAGADO" || obj[0].tipoflete=="POR COBRAR"){
				u.tipoflete.value = ((obj[0].tipoflete=="PAGADO")? "PAGADO" : "POR COBRAR" );
			}
		}
		
		u.txtemplaye.value = (emplaye=="0" || emplaye=="")?"":"$ "+numcredvar(emplaye);
		if(emplaye=="0" || emplaye==""){
			u.chkemplaye.checked = false;
			u.chkemplaye.disabled= true;
		}else{
			u.chkemplaye.checked = true;
			u.chkemplaye.disabled= true;
		}	
		u.txtacuserecibo.value = (acuserecibo=="0" || acuserecibo=="")?"":"$ "+numcredvar(acuserecibo);
		if(acuserecibo=="0" || acuserecibo==""){
			u.chkacuserecibo.checked = false;
			u.chkacuserecibo.disabled= true;
		}else{
			u.chkacuserecibo.checked = true;
			u.chkacuserecibo.disabled= true;
		}
		u.txtbolsaempaque1.value = (bolsaempaque=="0" || bolsaempaque=="")?"":bolsaempaque;
		if(bolsaempaque=="0" || bolsaempaque==""){
			u.chkbolsaempaque.checked = false;
			u.chkbolsaempaque.disabled= true;
		}else{
			u.chkbolsaempaque.checked = true;
			u.chkbolsaempaque.disabled= true;
		}
		u.txtbolsaempaque2.value = (totalbolsaempaque=="0" || totalbolsaempaque=="")?"":"$ "+numcredvar(totalbolsaempaque);
		u.txtcod.value = (cod=="0" || cod=="")?"":"$ "+numcredvar(cod);
		if(cod=="0" || cod==""){
			u.chkcod.checked = false;
			u.chkcod.disabled= true;
		}else{
			u.chkcod.checked = true;
			u.chkcod.disabled= true;
		}
		u.txtavisocelular1.value = (avisocelular=="0" || avisocelular=="")?"":"$ "+numcredvar(avisocelular);
		if(avisocelular=="0" || avisocelular==""){
			u.chkavisocelular.checked = false;
			u.chkavisocelular.disabled= true;
		}else{
			u.chkavisocelular.checked = true;
			u.chkavisocelular.disabled= true;
		}
		u.txtavisocelular2.value = celular;
		u.txtrecoleccion.value = (recoleccion=="0" || recoleccion=="")?"":"$ "+numcredvar(recoleccion);
		if(recoleccion=="0" || recoleccion==""){
			u.chkrecoleccion.checked = false;
			u.chkrecoleccion.disabled= true;
		}else{
			u.chkrecoleccion.checked = true;
			u.chkrecoleccion.disabled= true;
		}
		u.txtdeclarado.value = (valordeclarado=="0" || valordeclarado=="")?"":"$ "+numcredvar(valordeclarado);
		if(valordeclarado=="0" || valordeclarado==""){
			u.chkvalordeclarado.checked = false;
		}else{
			u.chkvalordeclarado.checked = true;
			u.txtdeclarado.style.backgroundColor = '';			
			u.txtdeclarado.readOnly = false;
		}
		u.d_guardar.style.visibility = "hidden";
		u.d_eliminar.style.visibility = "hidden";
		u.d_agregar.style.visibility = "hidden";
		consultaTexto("mostrarDetalleDevolucion","devolucionGuia_con.php?accion=9&devolucion="+u.folio.value);
	}
	
	function mostrarDetalleDevolucion(datos){
		var obj	= eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj);
	}
	
	function impGuia(){
		
		var detalle = "";
		var totpaq = 0;
		var totpes = 0;
		var totvol = 0;
		for(var i=0; i<tabla1.getRecordCount(); i++){
			totpaq += parseFloat(document.all['detalle_CANTIDAD'][i].value);
			totpes += parseFloat(document.all['detalle_PESOTOTAL'][i].value);
			totvol += parseFloat(document.all['detalle_VOLUMEN'][i].value);
			
			detalle += document.all['detalle_CANTIDAD'][i].value+"|"+
			document.all['detalle_DESCRIPCION'][i].value+"|"+
			document.all['detalle_CONTENIDO'][i].value+"|"+""+"|"+
			document.all['detalle_PESOTOTAL'][i].value+"|"+
			document.all['detalle_VOLUMEN'][i].value+"|";
		}
		detalle = detalle.substring(0,detalle.length-1);	
		Etiqueta.Impresora_Guias = "<?=str_replace("\\","\\\\",$fxy->impetiquetasguias)?>";
		Etiqueta.Impresora_Paquetes = "<?=str_replace("\\","\\\\",$fxy->impetiquetaspaquetes)?>";
		
		Etiqueta.Datos_Paqueteria = datosEmpresa;
		Etiqueta.Datos_Paquetes = document.getElementById('folioguia').innerHTML+"|<?=date('d/m/Y')?>|"+totpaq+" P.VOL: "+totvol+" P. KG: "+totpes;
		Etiqueta.Contenido_Paquetes = detalle;
		Etiqueta.Datos_Remitente = u.rem_cliente.value+"|CTE: "+u.idremitente.value+"    RFC: "+u.rem_rfc.value+"|"+u.rem_calle.value+
			" NO"+u.rem_numero.value+"|"+u.rem_colonia.value+" C.P. "+u.rem_cp.value+"|"+u.rem_poblacion.value+"|TEL:"+u.rem_telefono.value;
		Etiqueta.Datos_Destinatario = u.des_cliente.value+"|CTE: "+u.iddestinatario.value+"    RFC: "+u.des_rfc.value+"|"+u.des_calle.value+
			" NO"+u.des_numero.value+"|"+u.des_colonia.value+" C.P. "+u.des_cp.value+"|"+u.des_poblacion.value+"|TEL:"+u.des_telefono.value;;
		Etiqueta.Datos_Guia = obtenerPrefijos[u.idsucursalorigen.value]+"|"+obtenerPrefijos[document.all.sucdestino_hidden.value]
		+"|TIPO DE ENTREGA: OCURRE|TIPO DE FLETE: N/A|VALOR DECLARADO: $ 0.00|CONDICION DE PAGO: N/A|DOCUMENTO: <?=$_SESSION[NOMBREUSUARIO]?>";
		Etiqueta.Datos_Totales = "N/A|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|N/A";
			
		Etiqueta.CargarDatos();
		Etiqueta.ImprimirGuia();
		Etiqueta.ImprimirPaquetes();
		return false;
			
		
		
	}
	
	var desc = new Array(<?php echo $desc; ?>);
</script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:175px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
<!--
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
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
</style>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="625" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">DEVOLUCI&Oacute;N DE GUIA</td>
    </tr>
    <tr>
      <td><table width="624" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="7%" class="Tablas">Fecha:</td>
                <td width="15%"><input name="fechadev" class="Tablas" readonly="true" type="text" id="fechadev" style="background:#FFFF99;font:tahoma; font-size:9px" size="13" align="top" />
                    <input name="idsucursal" type="hidden" id="idsucursal" /></td>
                <td width="8%" class="Tablas">Sucursal:</td>
                <td width="31%" ><label>
                  <input name="sucursal" type="text" class="Tablas" style="background-color:#FFFF99; width:180px" id="sucursal" />
                </label></td>
                <td width="6%" >Folio:</td>
                <td width="28%" valign="middle" ><input class="Tablas" name="folio" readonly="true" type="text" id="folio" style="background:#FFFF99;font:tahoma; font-size:9px; width:90px" value="<?=$folio ?>"  align="top" />
                  <img id="b_remitente" src="../img/Buscar_24.gif" alt="Buscar Guia" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../buscadores_generales/buscarDevolucionGuiasGen.php?funcion=obtenerDevolucionBusqueda', 625, 550, 'ventana', 'Busqueda')" /></td>
                <td width="5%" align="right"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td width="624" colspan="2"><table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="7%" class="Tablas">F. Guia:</td>
                <td width="15%"><input name="fecha" class="Tablas" readonly="true" type="text" id="fecha" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$f->fecha ?>" size="13" align="top" />                </td>
                <td width="7%" class="Tablas">Estado:</td>
                <td width="35%" id="estado" style="font:tahoma; font-size:15px; font-weight:bold">&nbsp;</td>
                <td width="7%" >Guia:</td>
                <td width="26%" valign="middle" ><input class="Tablas" name="guia" maxlength="20" type="text" id="guia" style="width:120px" value="<?=$folio ?>" onkeyup="return validarGuia(event,this.name);" onkeypress="if(event.keyCode==13){ obtenerGuia(this.value);}"  align="top" /></td>
                <td width="3%" align="right"><a href="../menu/webministator.php"></a></a></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="624" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="8%" class="Tablas">T. Flete:</td>
                  <td width="19%"><label>
                    <input name="tipoflete" class="Tablas" type="text" id="tipoflete" style="background:#FFFF99;width:100px" readonly="" />
                  </label></td>
                  <td width="3%">&nbsp;</td>
                  <td width="8%">&nbsp;</td>
                  <td width="8%" class="Tablas">Destino:</td>
                  <td width="47%"><input type="text" name="destino" class="Tablas" id="destino" style="width:150px" autocomplete="array:desc" onKeyPress="if(event.keyCode==13){document.all.destino_hidden.value=this.codigo; devolverDestino();}" onBlur="if(this.value!=''){document.all.destino_hidden.value = this.codigo; if(this.codigo==undefined){document.all.destino_hidden.value =0}}" />
                      <input type="hidden" name="destino_hidden" id="destino_hidden" />
                      <input type="hidden" name="npobdes" />
                      <input type="hidden" name="idsucursalorigen" />
                      </td>
                  <td width="7%">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="624" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="11%" class="Tablas">Suc. Destino:</td>
                  <td width="24%"><input name="sucdestino" type="text" id="sucdestino" style="background:#FFFF99; width:150px" class="Tablas" readonly="readonly" value="<?=$destino?>" /></td>
                  <td width="1%">&nbsp;</td>
                  <td width="5%"><input name="sucdestino_hidden" type="hidden" id="sucdestino_hidden" /></td>
                  <td width="12%" class="Tablas">Cond. Pago:</td>
                  <td width="17%"><input name="lstpago" class="Tablas" type="text" id="lstpago" style="background:#FFFF99;width:100px" readonly="" /></td>
                  <td width="30%" align="center" id="folioguia" style="color:#F00000; font-size:15px; font-weight:bold">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="610" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
                <tr>
                  <td width="324" class="FondoTabla">Remitente</td>
                  <td width="280" class="FondoTabla">Destinatario</td>
                </tr>
                <tr>
                  <td><table width="310" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="52"># Cliente:</td>
                        <td width="113"><input name="idremitente" type="text" class="Tablas" id="idremitente" style="background:#FFFF99" readonly="" onkeypress="if(event.keyCode==13 &amp;&amp; this.readOnly==false){ devolverRemitente(this.value)}else{return solonumeros(event)}" value="<?=$remitente ?>" size="4" />
                          &nbsp;&nbsp;
                          <input type="hidden" name="rem_personamoral2" /></td>
                        <td width="145">R.F.C.:
                          <input name="rem_rfc" class="Tablas" readonly="true" type="text" style="background:#FFFF99; width:90px" value="<?=$rrfc ?>" /></td>
                      </tr>
                      <tr>
                        <td>Cliente:</td>
                        <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td><input name="rem_cliente" class="Tablas" readonly="true" type="text" style="background:#FFFF99; width:200px" 
                    value="<?=$rcliente ?>" />                              </td>
                              <td align="right" valign="middle">&nbsp;</td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>Calle:</td>
                        <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="153" height="16" id="celda_rem_calle"><input name="rem_calle" class="Tablas" readonly="true" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="" />
                                  <input type="hidden" name="rem_direcciones" /></td>
                              <td width="102"><span class="Tablas">Numero: </span><span class="Tablas">
                                <input name="rem_numero" type="text" class="Tablas" readonly="true" style=" width:50px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" />
                              </span></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>C.P.:</td>
                        <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="87" height="16" id="celda_rem_calle"><input name="rem_cp" readonly="true" class="Tablas" type="text" style="background:#FFFF99; width:60px" value="<?=$rcp ?>" />
                                  <input type="hidden" name="rem_direcciones2" /></td>
                              <td width="168"><span class="Tablas">Colonia:
                                    <input name="rem_colonia" type="text" class="Tablas" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcolonia ?>" />
                              </span></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td>Poblaci&oacute;n:</td>
                        <td><input name="rem_poblacion" readonly="true" class="Tablas" type="text" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rpoblacion ?>" /></td>
                        <td><span class="Tablas">&nbsp;Tel.:
                          <input name="rem_telefono" type="text" readonly="true" class="Tablas" style="background:#FFFF99; width:98px" value="<?=$rtelefono ?>" />
                        </span></td>
                      </tr>
                      <tr>
                        <td colspan="3">&nbsp;</td>
                      </tr>
                  </table></td>
                  <td><table width="310" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="163"><input name="iddestinatario" type="text" class="Tablas" id="iddestinatario" style="background:#FFFF99" readonly="" onkeypress="if(event.keyCode==13 &amp;&amp; this.readOnly==false){ devolverRemitente(this.value)}else{return solonumeros(event)}" value="<?=$remitente ?>" size="4" />
                          &nbsp;&nbsp;
                          <input type="hidden" name="rem_personamoral22" /></td>
                        <td width="147">R.F.C.:
                          <input name="des_rfc" type="text" class="Tablas" id="des_rfc" style="background:#FFFF99; width:90px" value="<?=$rrfc ?>" readonly="true" /></td>
                      </tr>
                      <tr>
                        <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td><input name="des_cliente" type="text" class="Tablas" id="des_cliente" style="background:#FFFF99; width:200px" 
                    value="<?=$rcliente ?>" readonly="true" />                              </td>
                              <td align="right" valign="middle">&nbsp;</td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="193" height="16" id="celda_rem_calle"><input name="des_calle" type="text" class="Tablas" id="des_calle" style="background:#FFFF99;font:tahoma; font-size:9px" value="" readonly="true" />
                                  <input type="hidden" name="rem_direcciones3" /></td>
                              <td width="113"><span class="Tablas">Numero: </span><span class="Tablas">
                                <input name="des_numero" type="text" class="Tablas" id="des_numero" style=" width:50px; background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rnumero ?>" readonly="true" />
                              </span></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="2"><table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="129" height="16" id="celda_rem_calle"><input name="des_cp" type="text" class="Tablas" id="des_cp" style="background:#FFFF99; width:60px" value="<?=$rcp ?>" readonly="true" />
                                  <input type="hidden" name="rem_direcciones22" /></td>
                              <td width="176"><span class="Tablas">Colonia:&nbsp;
                                    <input name="des_colonia" type="text" class="Tablas" id="des_colonia" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rcolonia ?>" readonly="true" />
                              </span></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td><input name="des_poblacion" type="text" class="Tablas" id="des_poblacion" style="background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rpoblacion ?>" readonly="true" /></td>
                        <td><span class="Tablas">&nbsp;Tel.:
                          <input name="des_telefono" type="text" class="Tablas" id="des_telefono" style="background:#FFFF99; width:98px" value="<?=$rtelefono ?>" readonly="true" />
                        </span></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="618" border="0" align="center">
                <tr>
                  <td width="436"><table border="0" cellpadding="0" cellspacing="0" id="detalle">
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
                                  <td width="40"><input name="txtocu" readonly="true" class="Tablas" type="text" style="background:#FFFF99;" value="<?=$drfc ?>" size="5" />                                  </td>
                                  <td width="28" class="Tablas">EAD:</td>
                                  <td width="34"><input name="txtead" class="Tablas" readonly="true" type="text" style="background:#FFFF99;" value="<?=$drfc ?>" size="5" />
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
                              <textarea name="txtrestrinccion" readonly="readonly" class="Tablas" style="width:170px; background-color:#FFFF99; text-transform:uppercase"></textarea>
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
            <td colspan="2"><table width="448" border="0" align="left" cellpadding="0" cellspacing="0">
              <tr>
                <td class="Tablas">&nbsp;</td>
                <td class="Tablas"><input name="index" type="hidden" id="index" /></td>
                <td class="Tablas">&nbsp;</td>
                <td colspan="2" class="Tablas" align="center">
                  <div id="d_eliminar" class="ebtn_eliminar" onclick="eliminarFila();"></div></td>
                <td class="Tablas"><div id="d_agregar" class="ebtn_agregar" onclick="if(document.all.guia.value!=''){abrirVentanaFija('datosMercanciaDevolucion.php?funcion=agregarDatos&amp;sucursal='+document.all.idsucursal.value, 450, 350, 'ventana','Datos Mercancia')}else{mens.show('A','Debe capturar Numero de Guia','&iexcl;Atenci&oacute;n!','');}"></div></td>
              </tr>
              <tr>
                <td width="75" class="Tablas">T. Paquetes: </td>
                <td width="58" class="Tablas"><input name="totalpaquetes" type="text" readonly="true" style="background:#FFFF99;width:60px; text-align:right" class="Tablas" value="<?=$rcp ?>"/></td>
                <td width="73" class="Tablas">T. Peso Kg: </td>
                <td width="77" class="Tablas"><input name="totalpeso" type="text" class="Tablas" readonly="true" style="background:#FFFF99;width:60px; text-align:right" value="<?=$rcp ?>" /></td>
                <td width="67" class="Tablas">T. Volumen: </td>
                <td width="98" class="Tablas"><input name="totalvolumen" type="text" class="Tablas" readonly="true" style="background:#FFFF99; width:60px; text-align:right" value="<?=$rcp ?>" /></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="2"><table width="616" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="377" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">
                    <tr>
                      <td width="377" class="FondoTabla">Observaciones</td>
                    </tr>
                    <tr>
                      <td><textarea name="txtobservaciones" style="width:370px; font-size:9px; font:tahoma; text-transform:uppercase"></textarea></td>
                    </tr>
                  </table></td>
                  <td width="10">&nbsp;</td>
                  <td width="210"><div class="ebtn_imprimirguia" onclick="impGuia()"></div></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="623" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="381">&nbsp;</td>
                  <td width="242"><table width="185" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="101" align="center"><div class="ebtn_guardar" id="d_guardar" onclick="guardar();"></div></td>
                      <td width="84"><div class="ebtn_nuevo" onclick="mens.show('C','Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?','','','limpiar(\'\')','')"></div></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="2"><table width="100%" height="76" border="0" cellpadding="0" cellspacing="0" style=" display:none;visibility:hidden">
                    <tr>
                      <td width="6%"><input name="chkemplaye" type="checkbox" style="width:12px; " value="SI" /></td>
                      <td class="Tablas">Emplaye
                        <input name="txtemplaye" readonly="true" type="text" class="Tablas" style="background:#FFFF99; text-align:right" size="10" />
                          <input name="txtemplayeh" type="hidden" />                      </td>
                      <td class="Tablas"><input name="chkacuserecibo"  type="checkbox" style="width:12px; " value="SI" /></td>
                      <td class="Tablas">Acuse Recibo</td>
                      <td class="Tablas" align="right"><input readonly="true" name="txtacuserecibo" type="text" style="background:#FFFF99;text-align:right" class="Tablas" value="<?=$rrfc ?>" size="8" />
                          <input name="txtacusereciboh" type="hidden" />                      </td>
                    </tr>
                    <tr>
                      <td><input name="chkbolsaempaque" type="checkbox" style="width:12px; " value="SI"></td>
                      <td width="49%" class="Tablas">Bolsa Empaque
                        <input name="txtbolsaempaque1" readonly="true" class="Tablas" type="text" style="background:#FFFF99;text-align:right" value="<?=$rrfc ?>" size="1" />
                          <input name="txtbolsaempaque2" readonly="true" type="text" class="Tablas" style="background:#FFFF99; text-align:right" size="6" />
                          <input name="txtbolsaempaque1h" type="hidden" />
                          <input name="txtbolsaempaque2h" type="hidden" />
                          <input name="txtbolsaempaque3h" type="hidden" />                      </td>
                      <td width="5%" class="Tablas"><input name="chkcod" type="checkbox" style="width:12px; " value="SI" />                      </td>
                      <td width="19%" class="Tablas">COD</td>
                      <td width="21%" class="Tablas" align="right"><input readonly="true" class="Tablas" name="txtcod" type="text" style="background:#FFFF99; text-align:right" value="<?=$rrfc ?>" size="8" />
                          <input name="txtcodh" type="hidden" />                      </td>
                    </tr>
                    <tr>
                      <td><input name="chkavisocelular" type="checkbox" style="width:12px; " value="SI" /></td>
                      <td colspan="4" class="Tablas">Aviso Celular
                        <input name="txtavisocelular1" readonly="true" type="text" class="Tablas" style="background:#FFFF99; text-align:right" size="10" />
                          <input name="txtavisocelular1h" type="hidden" />
                          <input name="txtavisocelular2" readonly="true" class="Tablas" type="text" style="background:#FFFF99;" value="<?=$rrfc ?>" size="10" />
                          <input name="txtavisocelular2h" type="hidden" /></td>
                    </tr>
                    <tr>
                      <td><input name="chkvalordeclarado" type="checkbox" style="width:12px; " value="SI"
               onclick="if(!this.checked){document.all.txtdeclarado.value='';document.all.txtdeclarado.readOnly=true; document.all.txtdeclarado.style.backgroundColor='#FFFF99'; document.all.txtdeclarado.readOnly=true;}else{document.all.txtdeclarado.readOnly=false; document.all.txtdeclarado.style.backgroundColor='#FFFFFF'; document.all.txtdeclarado.readOnly=false;document.all.txtdeclarado.focus();} " /></td>
                      <td class="Tablas">Valor Declarado
                        <input name="txtdeclarado" type="text" readonly="true" class="Tablas" style="background:#FFFF99; text-align:right" value="<?=$rrfc ?>" size="10" /></td>
                      <td class="Tablas"><input name="chkrecoleccion" type="checkbox" id="chocurre24" style="width:12px; " value="SI" /></td>
                      <td class="Tablas">Recolecci&oacute;n</td>
                      <td class="Tablas" align="right"><input readonly="true" class="Tablas" name="txtrecoleccion" type="text" style="background:#FFFF99; text-align:right; text-align:right" value="<?=$rrfc ?>" size="8" />
                          <input name="txtrecoleccionh" type="hidden" />                      </td>
                    </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>