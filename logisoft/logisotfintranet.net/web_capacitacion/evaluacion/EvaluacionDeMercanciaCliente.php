<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	require_once('../Conectar.php');
	$link=Conectarse('webpmm');
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd 
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id";	
	$r = mysql_query($s,$link) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".cambio_texto($f[0])."'".','.$desc; 	
		}
		$desc=substr($desc, 0, -1);
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="../javascript/ClaseTabla.js" ></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script language="javascript">
	var u = document.all;
	var tabla1 	= new ClaseTabla();
	var mens = new ClaseMensajes();
	var sucursalorigen 	= 0;
	var img = '<img id="btnCancelar" src="../img/Boton_Cancelar.gif" alt="Guardar" width="70" height="20" onClick="confirmar(\'¿Realmente desea cancelar la Orden de Embarque?\', \'\', \'Cancelar();\', \'\')" style="cursor:pointer;" id="cancelar" />';
	var tabla_valt1 	= "";	
	var img_imprimir = '<img src="../img/Boton_Imprimir.gif" id="i_imprimir" alt="Imprimir" width="70" height="20" onClick="Imprimir();" style="cursor:pointer"  />';
	var img_guardar = '<img src="../img/Boton_Guardar.gif" alt="Guardar" name="i_guardar" width="70" height="20" id="i_guardar" style="cursor:pointer;" onClick="Validar();"  />';
	var bandera = true;
tabla1.setAttributes({
		nombre:"tablaguias",
		campos:[
			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},
			{nombre:"ID", medida:2, alineacion:"right", tipo:"oculto", datos:"id"},
			{nombre:"DESCRIPCION", medida:160, alineacion:"left", datos:"descripcion"},
			{nombre:"CONTENIDO", medida:160, alineacion:"left", datos:"contenido"},
			{nombre:"PESO_KG", medida:45, alineacion:"right", datos:"peso"},
			{nombre:"LARGO", medida:40, alineacion:"right",  datos:"largo"},
			{nombre:"ANCHO", medida:40, alineacion:"right",  datos:"ancho"},
			{nombre:"ALTO", medida:40, alineacion:"right",  datos:"alto"},
			{nombre:"P_TOTAL", medida:4, alineacion:"right", tipo:"oculto", datos:"pesototal"},			
			{nombre:"P_VOLU", medida:40, alineacion:"right", datos:"volumen"},
			{nombre:"UNIT", medida:4, alineacion:"right", tipo:"oculto", datos:"pesounit"},
			{nombre:"FECHA", medida:4, alineacion:"right", tipo:"oculto", datos:"fecha"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		<?
		$_GET[funcion] = str_replace("\'","'",$_GET[funcion]);
		if($_GET[funcion]!=""){
			echo 'setTimeout("'.$_GET[funcion].'",1500);';
			}
		?>
		tabla1.create();
		mens.iniciar('../javascript',false);
		u.img_eliminar.style.visibility="hidden";
		eliminarTemporal();
	}
	
	function mostrarGuias(){
		abrirVentanaFija('../buscadores_generales/buscarGuiasClientesGen.php?&funcion=CargarDatosParaEvaluacion', 590, 450, 'ventana', 'Guias Registradas');
	}
	
	function CargarDatosParaEvaluacion(folio){
		consultaTexto("mostrarGuiaEnEvaluacion","evaluacionMercancia_con.php?accion=17&folio="+folio+"&ram="+Math.random());
	}
	
	function mostrarGuiaEnEvaluacion(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			alerta3(datos,"¡ATENCION!");
		}
		u.SucDestino.value 		= obj.datos.sucdestino;
		u.country.value 		= obj.datos.destino;
		u.NGuias.value 			= obj.datos.id;
		u.country_hidden.value 	= obj.datos.idsucursaldestino;
		u.iddestino.value 		= obj.datos.iddestino;
		u.prepagada.value 		= obj.datos.prepagada;
		
		u.prepagada.value = 0; 
		u.BolsaEmpaque.disabled = false; 
		u.CantidadEmpaque.readOnly = false; 
		u.CantidadEmpaque.style.backgroundColor=''; 
		u.Emplaye.disabled = false;
		tabla1.setJsonData(obj.detalleevaluacion);
		
		solicitarDatosConve(obj.datos.id);
		
	}
	
	function eliminarTemporal(){
		consultaTexto("obtenerGenerales","evaluacionMercancia_con.php?accion=4");
	}
	function obtenerGenerales(datos){
		consultaTexto("mostrarGenerales","evaluacionMercancia_con.php?accion=3&bolsa=1&emplaye=2&sd="+Math.random());
	}
	function mostrarGenerales(datos){
		var obj = eval(convertirValoresJson(datos));
		u.costobolsa.value		= obj.datos.bolsaempaque;
		u.fechaevaluacion.value = obj.datos.fecha;
		u.folio.value 			= obj.datos.folio;
		u.costoemplaye.value	= obj.datos.emplaye;	
		u.costoextra.value		= obj.datos.costoextra;
		u.limite.value			= obj.datos.limite;
		u.porcada.value			= obj.datos.porcada;
		u.hsucursal.value		= obj.datos.sucursal;
	}	

	function ModificarFila(){
		if(u.Estado.value==""){
			var obj = tabla1.getSelectedRow();
			if(tabla1.getValSelFromField("cantidad","CANT")!=""){
			u.indice.value = tabla1.getSelectedIdRow();
			abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?&cantidad='+obj.cantidad
					+'&id='+obj.id
					+'&descripcion='+obj.descripcion
					+'&contenido='+obj.contenido
					+'&peso='+obj.peso
					+'&largo='+obj.largo
					+'&ancho='+obj.ancho
					+'&alto='+obj.alto
					+'&pesototal='+obj.pesototal
					+'&volumen='+obj.volumen
					+'&pesounit='+obj.pesounit
					+'&fechahora='+obj.fecha				
					+'&funcion=agregarDatos&eliminar=1&esmodificar=si', 400, 350, 'ventana', 'Datos Evaluación');	
			}
		}
	}
	
	function trim(cadena,caja){
		for(i=0;i<cadena.length;){
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(i+1, cadena.length);
			else
				break;
		}
		for(i=cadena.length-1; i>=0; i=cadena.length-1){
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(0,i);
			else
				break;
		}
		document.getElementById(caja).value=cadena;
	}
	function Cancelar(){
		if(u.Estado.value=="EN GUIA"){
		alerta3("El folio de la Evaluación #"+u.folio.value+" no se puede cancelar, porque ya fue documentada en una guía","¡Atención!");
			return false;
		}else{
			abrirVentanaFija('clavesecundaria.php?idusuario=<?=$idusuario ?>&modulo=<?=$modulo?>&usuario=<?=$usuario?>&cancelar=cancelar', 370, 340, 'ventana', 'Inicio de Sesión Secundaria');	
		}
	}
	function ConfirmarCancelar(can){
		if(can!=""){
			consultaTexto("registroCancelar","evaluacionMercancia_con.php?accion=7&folio="+u.folio.value);
		}
	}
	function registroCancelar(datos){
		if(datos.indexOf("ok")>-1){
			info("La Evaluación se ha cancelado satisfactoriamente.");
			u.Estado.value = 'CANCELADA';
		}else{
			alerta3("Hubo un error al cancelar "+datos,"¡Atención!");
		}
	}
	function Obtener(folio){
		u.folio.value=folio;
		consultaTexto("mostrarEvaluacion","evaluacionMercancia_con.php?accion=8&evaluacion="+folio+"&sd="+Math.random());
	}
	function mostrarEvaluacionDatos(folio){
		consultaTexto("mostrarEvaluacion","evaluacionMercancia_con.php?accion=8&evaluacion="+folio+"&sd="+Math.random());
	}
	function mostrarEvaluacion(datos){
		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){
			var obj 					= eval(convertirValoresJson(datos));
			u.fechaevaluacion.value		= obj[0].fechaevaluacion;
			if(obj[0].estado=='ENGUIA')
				u.Estado.value			= 'EN GUIA';
			else
				u.Estado.value			= obj[0].estado;
			u.NGuias.value				= obj[0].guiaempresarial;
			u.NRecoleccion.value		= obj[0].recoleccion;
			u.countryid.value			= obj[0].destino;
			u.country.value				= obj[0].descripciondestino;
			u.SucDestino.value			= obj[0].sucursaldestino;
			u.BolsaEmpaque.checked		= ((obj[0].bolsaempaque==1)?true:false);
			u.Emplaye.checked			= ((obj[0].emplaye==1)?true:false);			
			u.CantidadEmpaque.value		= ((obj[0].cantidadbolsa=="0")?"":obj[0].cantidadbolsa);
			u.TotalEmpaque.value		= ((obj[0].totalbolsaempaque=="0")?"":obj[0].totalbolsaempaque);
			u.TotalEmplaye.value		= ((obj[0].totalemplaye=="0")?"":obj[0].totalemplaye);
			u.cel.innerHTML 			= img;
			u.entrega.value				= obj[0].entrega;
			if(obj[0].entrega=="1"){
				u.precioead.value			= "$ "+numcredvar(obj[0].precioead);
			}
			u.img_eliminar.style.visibility	="hidden";
			u.btnAgregar.style.visibility	="hidden";
			HabilitarRecoleccion();
			HabilitarEmpresarial();
			if(u.Estado.value=='CANCELADO'){
				u.btnCancelar.style.visibility='hidden';
			}else{
				u.btnCancelar.style.visibility='visible';
			}
			consultaTexto("mostrarDetalle","evaluacionMercancia_con.php?accion=9&evaluacion="+u.folio.value+"&sd="+Math.random());
		}else{
			alerta("El folio de Evaluación de Mercancia no existe o no corresponde a la sucursal de "+u.hsucursal.value,"¡Atención!","folio");			
			limpiar();
		}
	}
	function mostrarDetalle(datos){
		tabla1.clear();
		var obj = eval(convertirValoresJson(datos));
		tabla1.setJsonData(obj);
	}
	function Validar(){	
		if(u.prepagada.value=="1" && u.subdestino.value=="0"){
			alerta3("Las guias prepagadas no pueden ser enviadas a destinos que no son sucursales","¡ATENCION!");
			return false;
		}
		
		if(u.prepagada.value==1 && tabla1.getRecordCount()>1){
			alerta3("Solo puede agregar un articulo a la evaluacion de mercancia","¡ATENCION!");
			return false;
		}
		
		if(u.prepagada.value==1 && parseFloat(document.all['tablaguias_CANT'][0].value)>1){
			alerta3("Solo puede agregar un articulo a la evaluacion de mercancia","¡ATENCION!");
			return false;
		}
	
		u.registros.value = tabla1.getRecordCount();	
		if(u.country.value=="" || u.countryid.value==""){
			alerta("Debe capturar Destino","!Atenci&oacute;¡","country");
			return false;
		}		
		if(tabla1.getRecordCount()==0){
			alerta3('Debe Capturar por lo menos una Evaluación al detalle','¡Atención!');
			return false;
		}else{
			abrirVentanaFija('clavesecundaria.php?idusuario=<?=$idusuario ?>&modulo=<?=$modulo?>&usuario='+document.all.user.value, 370, 340, 'ventana', 'Inicio de Sesión Secundaria');		
		}	
	}
	
	function registrar(autorizar){
		if(autorizar=="SI"){
			u.i_guardar.style.visibility = "hidden";
			u.img_eliminar.style.visibility	="hidden";
			u.btnAgregar.style.visibility	="hidden";
			var arr = new Array();						
			arr[0] = "GUARDADO";
			arr[1] = u.NGuias.value;
			arr[2] = ((u.NRecoleccion.value=="")? 0 : u.NRecoleccion.value);
			arr[3] = u.iddestino.value;
			arr[4] = u.SucDestino.value;
			arr[5] = ((u.BolsaEmpaque.checked==true)?1:0);
			arr[6] = ((u.CantidadEmpaque.value=="")? 0 : u.CantidadEmpaque.value);
			arr[7] = ((u.TotalEmpaque.value=="")? 0 : u.TotalEmpaque.value);
			arr[8] = ((u.Emplaye.checked==true)?1:0);
			arr[9] = ((u.TotalEmplaye.value=="")? 0 : u.TotalEmplaye.value);
			arr[10] = '<?=$_SESSION[IDSUCURSAL]?>';
			arr[11] = u.entrega.value;
			//alert("evaluacionMercancia_con.php?accion=5&arre="+arr);
			//return false;
			consultaTexto("registroEvaluacion","evaluacionMercancia_con.php?accion=5&arre="+arr);
		}
	}
	function registroEvaluacion(datos){
		if(datos.indexOf("guardo")>-1){
			info("Los datos han sido guardados correctamente","");
			u.cel.innerHTML = img_imprimir;
			u.Estado.value = "GUARDADO";
		}else{
			u.i_guardar.style.visibility = "visible";
			alerta3("Hubo un Error al guardar "+datos,"¡Atención!");
		}
	}
	function limpiar(){	
		var pag = document.location.href;
		document.location.href = "";
		document.location.href = pag;
		/*
		u.folio.value				="";
		u.Estado.value				="";
		u.country.value				="";
		u.SucDestino.value			="";
		u.CantidadEmpaque.value		="";
		u.TotalEmpaque.value		="";
		u.TotalEmplaye.value		="";
		u.NRecoleccion.value		="";
		u.NGuias.value				="";
		u.fechaevaluacion.value		="";
		u.costoemplayeextra.value	="";
		u.totalpeso.value			="";
		u.msg1.value				="";
		u.user.value				="";
		u.fechahora.value			="";	
		u.countryid.value			=""; 
		u.iddestino.value			="";
		u.totalvol.value			="";
		u.registros.value			="";
		u.sucursalorigen.value		="";
		u.accion.value				="";
		u.indice.value 				="";
		u.BolsaEmpaque.checked		=false;
		u.Emplaye.checked			=false;
		u.prepagada.value			=0;
		tabla1.clear();
		u.cel.innerHTML				=	img_guardar;
		u.img_eliminar.style.visibility	="visible";
		u.btnAgregar.style.visibility	="visible";
		u.NGuias.style.backgroundColor	='';
		u.NGuias.disabled				=false;
		u.NRecoleccion.style.backgroundColor='';
		u.NRecoleccion.disabled			=false;
		u.BolsaEmpaque.disabled = false; 
		u.CantidadEmpaque.readOnly = false; 
		u.CantidadEmpaque.style.backgroundColor=''; 
		u.Emplaye.disabled = false;
		u.subdestino.value = "";
		u.precioead.value = "";
		u.precioead_h.value = "";
		u.entrega.value = "";
		bandera = true;
		obtenerGenerales("");
		u.NRecoleccion.focus();
		*/
	}	
	function devolverDestino(){		
		if(u.countryid.value==""){
			setTimeout("devolverDestino()",500);
		}else{
			consultaTexto("mostrarDestino", "evaluacionMercancia_con.php?accion=1&destino="+u.countryid.value);
		}
	}
	function mostrarDestino(datos){
		var row = datos.split(",");
		u.SucDestino.value = convertirValoresJson(row[0]);
		u.subdestino.value = row[1];
		u.precioead_h.value = ((row[2]=="")?0:row[2]);
		
		if(u.prepagada.value=="1" && u.subdestino.value=="0"){
			alerta3("Las guias prepagadas no pueden ser enviadas a destinos que no son sucursales","¡ATENCION!");
			u.SucDestino.value = "";
			u.country.value = "";
			u.subdestino.value = "";
			return false;
		}
		
		if(u.entrega.value=="2" && row[1]=="0"){			
			u.entrega.value = "1";
			u.precioead.value = "$ "+numcredvar(u.precioead_h.value);
			confirmar('No hay Sucursal en el Destino seleccionado. No se puede realizar Entregas Ocurre, el costo EAD es de:$ '+numcredvar(u.precioead_h.value)+' ¿Desea continuar con la captura?','','confirmacion()','');
			
		}else if(u.entrega.value=="1" && row[2]!=""){
			u.precioead.value = "$ "+numcredvar(u.precioead_h.value);
			if(bandera == true){
			abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
			}
		}else if(u.entrega.value=="2" && row[1]=="1"){
			if(bandera == true){
			abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
			}
		}else{			
			u.entrega.focus();
		}	
	}
	
	function validarEntrega(tipo){				
		if(tipo=="2" && u.subdestino.value=="0"){			
			u.entrega.value = 1;
			u.precioead.value = "$ "+numcredvar(u.precioead_h.value);
			confirmar('No hay Sucursal en el Destino seleccionado. No se puede realizar Entregas Ocurre, el costo EAD es de:$ '+numcredvar(u.precioead_h.value)+' ¿Desea continuar con la captura?','','confirmacion()','');	
		
		}else if(tipo=="1" && u.precioead_h.value!=""){			
			u.precioead.value = "$ "+numcredvar(u.precioead_h.value);
			if(bandera == true){
			abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
			}
		}else if(tipo=="2" && u.subdestino.value=="1"){
			u.precioead.value = "";
			if(bandera == true){
			abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
			}
		}else if(tipo==""){
			u.precioead.value = "";
		}
	}
	
	function confirmacion(){
		abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n','alCerrar();');
	}
	
	function alCerrar(){
		if(tabla1.getRecordCount()==0){
			u.entrega.value = "";		
			u.entrega.focus();
		}else{
			bandera = false;
		}
	}
	function numcredvar(cad){
		var flag = false; 
		if(cad.indexOf('.') == cad.length - 1) flag = true; 
		var num = cad.split(',').join(''); 
		cad = Number(num).toLocaleString(); 
		if(flag) cad += '.'; 
		return cad;
	}
	
	function ObtenerPrecioBolsa(){
		 if(u.BolsaEmpaque.checked==true){	 	
			u.CantidadEmpaque.focus();
		 }else{
	  		u.TotalEmpaque.value="";
			u.CantidadEmpaque.value="";
		 }
	}

	function ObtenerPrecioEmplaye(){ 
		if(u.Emplaye.checked==true){
			if(tabla1.getRecordCount()==0){
				alerta('Debe Capturar por lo menos una Evaluaci&oacute;n al detalle','Atencin!','Emplaye');
				u.Emplaye.checked=false;
			}else{
				CalcularEmplaye();
			}
		}else{
			u.TotalEmplaye.value	="";
		}	
	}	
	function CalcularEmplaye(){		
		var tot = ""; var vol = "";
		v_tot = 0; v_vol = 0;
		
		tot = tabla1.getValuesFromField("pesototal",",").split(",");
		vol = tabla1.getValuesFromField("volumen",",").split(",");				
		
		for(var i=0;i<tot.length;i++){		
			v_tot = parseFloat(tot[i]) + parseFloat(v_tot);					
		}
		u.totalpeso.value = v_tot;		
		
		for(var i=0;i<vol.length;i++){
			v_vol = parseFloat(vol[i]) + parseFloat(v_vol);						
		}
		u.totalvol.value = v_vol;		
		
	if(u.Emplaye.checked==true){	
		if(parseFloat(u.totalpeso.value) > parseFloat(u.totalvol.value)){
			if(parseFloat(u.totalpeso.value) <= parseFloat(u.limite.value)){
				u.TotalEmplaye.value = u.costoemplaye.value;
			}else{
		var kgextra=parseFloat(u.totalpeso.value) - parseFloat(u.limite.value);
				u.TotalEmplaye.value=parseFloat(u.costoemplaye.value) + parseFloat(((kgextra / parseFloat(u.porcada.value)) * parseFloat(u.costoextra.value)));
				}
				if(u.TotalEmplaye.value=='NaN'){
					u.TotalEmplaye.value="";
				}			
			}else{ 
				if(parseFloat(u.totalvol.value)<=parseFloat(u.limite.value)){
					u.TotalEmplaye.value=parseFloat(u.costoemplaye.value);
				}else{
				var kgextra=parseFloat(u.totalvol.value)-parseFloat(u.limite.value);
				u.TotalEmplaye.value=parseFloat(u.costoemplaye.value) + parseFloat(((kgextra / parseFloat(u.porcada.value)) * parseFloat(u.costoextra.value)));
				}
				if(u.TotalEmplaye.value=='NaN'){
					u.TotalEmplaye.value="";
				}
			}
		}else{	
			u.TotalEmplaye.value="";	
		}
	}
	function ObtenerTotalBolsaFoco(){
		if(u.CantidadEmpaque.value!=""){
			u.TotalEmpaque.value=parseFloat(u.costobolsa.value) * parseFloat(u.CantidadEmpaque.value); 
		}
		if(u.TotalEmpaque.value=='Nan'){
			u.TotalEmpaque.value="";
		}
	}
	function ObtenerTotalBolsa(e,caja){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla==13){ 
			u.TotalEmpaque.value= parseFloat(u.costobolsa.value) * parseFloat(caja);
		}
		if(u.TotalEmpaque.value=='Nan'){
			u.TotalEmpaque.value="";
		}
	} 
 	
	function borrarFila(){
		if(tabla1.getValSelFromField('cantidad','CANT')==""){
			alerta3('Debe Seleccionar la fila a eliminar','¡Atención!');
		}else{
			confirmar('¿Esta seguro de Eliminar la mercancia seleccionada?','','eliminarFila(\'\')','');	
		}
	}
	function eliminarFila(datos){
		if(datos==""){
			var obj = tabla1.getSelectedRow();
			var fecha = obj.fecha;
			tabla1.deleteById(tabla1.getSelectedIdRow());
			consultaTexto("eliminarFila","evaluacionMercancia_con.php?accion=6&fecha="+fecha);
		}
		if(tabla1.getRecordCount()==0){
			u.img_eliminar.style.visibility="hidden";
			u.Emplaye.checked = false;
			u.totalpeso.value = "";		
			u.totalvol.value = "";
			return false;
		}
		if(u.Emplaye.checked==true){
			ObtenerPrecioEmplaye();
		}
	}
	
	function agregarDatos(variable){	
		var u = document.all;
		if(tabla1.getRecordCount()>6){
			alerta3("Solo puede registrar 6 articulos","¡Atención!");
		}else{
			if(u.indice.value==""){			
				tabla1.add(variable);				
				if(u.Emplaye.checked==true){
					ObtenerPrecioEmplaye();
				}
			}else{
				tabla1.updateRowById(tabla1.getSelectedIdRow(), variable);				
				u.indice.value = "";
				if(u.Emplaye.checked==true){
					ObtenerPrecioEmplaye();
				}
			}
			u.img_eliminar.style.visibility="visible";
		}
		if(u.prepagada.value==1){
			var v_tot=0;
			var v_vol=0;
			tot = tabla1.getValuesFromField("pesototal",",").split(",");
			vol = tabla1.getValuesFromField("volumen",",").split(",");				
			
			for(var i=0;i<tot.length;i++){		
				v_tot = parseFloat(tot[i]) + parseFloat(v_tot);					
			}
			u.totalpeso.value = v_tot;		
			
			for(var i=0;i<vol.length;i++){
				v_vol = parseFloat(vol[i]) + parseFloat(v_vol);						
			}
			u.totalvol.value = v_vol;
			
			var pesomax = (v_vol>v_tot)?v_vol:v_tot;
			if(parseFloat(pesomax)>parseFloat(u.limitekg.value)){
				alerta3("Ha sobrepasado el limite de kg configurado en el convenio: "+u.limitekg.value+" KG","¡Atención!");
			}
		}
	}   
	
	function ObtenerPesoVolumen(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;		
			if(con>0){
				u.totalpeso.value=datos.getElementsByTagName('peso').item(0).firstChild.data;	
				u.totalvol.value=datos.getElementsByTagName('volumen').item(0).firstChild.data;
			}
	}

	function habilitar(e,nombre){
		tecla = (u) ? e.keyCode : e.which;
		if(nombre=="NRecoleccion"){
			if(tecla==8 && document.getElementById(nombre).value==""){
				document.getElementById('NGuias').style.backgroundColor='';
				document.getElementById('NGuias').disabled=false;			
			}else if(document.getElementById(nombre).value!=""){
				document.getElementById('NGuias').style.backgroundColor='#FFFF99';
				document.getElementById('NGuias').disabled=true;
			}
		}else if(nombre=="NGuias"){
			if(tecla==8 && document.getElementById(nombre).value==""){
				document.getElementById('NRecoleccion').style.backgroundColor='';		
				document.getElementById('NRecoleccion').disabled=false;
			}else if(document.getElementById(nombre).value!=""){
				document.getElementById('NRecoleccion').style.backgroundColor='#FFFF99';
				document.getElementById('NRecoleccion').disabled=true;
			}
		}
	}
	function tabular(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
		if(tecla!=13) return;
		frm=obj.form;
		for(i=0;i<frm.elements.length;i++) 
			if(frm.elements[i]==obj) 
			{ 
				if (i==frm.elements.length-1) 
					i=-1;
				break 
			}
		/*ACA ESTA EL CAMBIO*/
		if (frm.elements[i+1].disabled ==true )    
			tabular(e,frm.elements[i+1]);
		else frm.elements[i+1].focus();
		return false;
	} 
	function HabilitarRecoleccion(){	
		if(u.NRecoleccion.value!=""){	
			u.NGuias.style.backgroundColor='#FFFF99';	
			u.NGuias.disabled=true;
		}
	}
	function HabilitarEmpresarial(){
		if(u.NGuias.value!=""){	
			u.NRecoleccion.style.backgroundColor='#FFFF99';
			u.NRecoleccion.disabled=true;
		}
	}

	function Imprimir(){
		window.open("imprimirEvaluacion.php?evaluacion="+u.folio.value+"&empleado=<?=$_SESSION[IDUSUARIO]?>&sucursal=<?=$_SESSION[IDSUCURSAL]?>");
	}	
	function obtenerDestino(id,destino,sucursal){
		u.country.value=destino;
		u.countryid.value=id;
		//consultaTexto("mostrarSucursal","evaluacionMercancia_con.php?accion=11&destino="+id);
		consultaTexto("mostrarDestino", "evaluacionMercancia_con.php?accion=1&destino="+id);
	}
	function mostrarSucursal(datos){
		var obj = eval(convertirValoresJson(datos));
		u.SucDestino.value = obj[0].descripcion;
		u.entrega.focus();
	}
	function ObtenerSucursalOrigen(){
		if('<?=$_SESSION[IDSUCURSAL]?>'!=""){
			sucursalorigen = '<?=$_SESSION[IDSUCURSAL]?>';
			u.sucursalorigen.value = sucursalorigen;
		}
	}

	function BuscarEvaluacion(){
		abrirVentanaFija('buscarEvaluacion.php?tipo=evaluacion&sucursal='+'<?=$_SESSION[IDSUCURSAL]?>', 550, 450, 'ventana', 'Busqueda')
	}

	function solicitarDatosConve(valor){
		if(valor.length!=13){
			alerta3("El folio de guia empresarial esta compuesto por 13 caracteres","¡Atención!");
			u.NGuias.value = "";
		}else{
			consultaTexto("resSolicitarDatosConve","evaluacionMercancia_con.php?accion=10&folio="+valor);
		}
	}
	
	function resSolicitarDatosConve(datos){
			u.limitekg.value = "";
			u.BolsaEmpaque.checked = false;
			u.CantidadEmpaque.value = '';
			u.Emplaye.checked = false;
			u.TotalEmpaque.value = '';
			u.TotalEmplaye.value = '';
			
			u.BolsaEmpaque.disabled = false;
			u.CantidadEmpaque.readOnly = false;
			u.CantidadEmpaque.style.backgroundColor='';
			u.Emplaye.disabled = false;
		var objeto = eval(datos);
		if(objeto.encontro=="0"){
			alerta("El folio de guia empresarial no existe", "¡Atención!", "NGuias");
			u.NGuias.value = "";
		}else if(objeto.encontro=="1" && objeto.prepagadas=="SI"){
			u.prepagada.value = 1;
			u.limitekg.value = objeto.limitekg;
			u.BolsaEmpaque.disabled = true;
			u.CantidadEmpaque.readOnly = true;
			u.CantidadEmpaque.style.backgroundColor='#FFFF99';
			u.Emplaye.disabled = true;
		}else if(objeto.encontro=="-2"){
			info(objeto.mensaje, "¡Atención!");
			u.NGuias.value = "";
		}else if(objeto.encontro=="-1"){
			info("El folio que desea utilizar esta bloqueado favor de facturar la venta", "¡Atención!");
			u.NGuias.value = "";
		}
	}	
	
	var desc = new Array(<?php echo $desc; ?>);
	
</script>

<title>Evaluaci&oacute;n de Mercancias</title>
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
	

	</style>
<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
.style2 {
	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {
	font-size: 9px;
	color: #464442;
}
.style4 {color: #025680;font-size:9px }
.style5 {
	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 13px;
	font-family: tahoma;
}
-->

</style>

<style>
.Txtamarillo{
font:tahoma; font-size:9px; background-color:#FFFF99;text-transform:uppercase;
}
.Txt{
font:tahoma; font-size:9px;text-transform:uppercase;
}

.Button {
margin: 0;
padding: 0;
border: 0;
background-color: transparent;
width:70px;
height:20px;
}
.Estilo2 {
	font-size: 8px;
	font-weight: bold;
}
.Estilo3 {font-size: 9px}
.style31 {font-size: 9px;
	color: #464442;
}
.style31 {font-size: 9px;
	color: #464442;
}
</style>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<link href="FondoTabla.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script src="../javascript/ajax.js"></script>
<script src="../javascript/funciones.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
</head>
<body >
<form id="form1" name="form1" method="post" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><label></label>
        <table width="640" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        <tr>
          <td class="FondoTabla">EVALUACI&Oacute;N MERCANC&Iacute;A
            <input type="hidden" name="limitekg" /></td>
        </tr>
        <tr>
          <td><div id="txtResult"><table width="639" height="247" border="0" align="center" cellpadding="0" cellspacing="0">            
            <tr>
              <th width="612" height="64" scope="row"><table width="638" height="75" border="0" cellspacing="0" class="Tablas">
                <tr>
                  <td height="19" colspan="2" class="Tablas"><label>
                    Folio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="folio" type="text" class="Tablas" id="folio" value="<?=$folio ?>" style="text-align:right; width:60px" onKeyPress="if(event.keyCode==13){mostrarEvaluacionDatos(this.value);}"  >
                      <img src="../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="BuscarEvaluacion()">
                      <input name="folio_hidden" type="hidden" id="folio_hidden" />
                  </label></td>
                  <td class="Tablas">Fecha: </td>
                  <td width="145" class="Tablas"><input name="fechaevaluacion" type="text" class="Tablas" id="fechaevaluacion" style="background:#FFFF99" value="<?=$fechaevaluacion ?>" size="15" readonly=""  >                    <label></label></td>
                  <td colspan="2" class="Tablas">Estado:&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input name="Estado" type="text" class="Tablas" id="Estado" style="background:#FFFF99" value="<?=$Estado ?>" size="15" readonly="">
                    </label></td>
                  </tr>
                <tr>
                  <td width="80" height="19" class="Tablas">&nbsp;</td>
                  <td width="61" class="Tablas"><input name="NRecoleccion" type="text" class="Tablas" id="NRecoleccion" value="<?=$NRecoleccion ?>" size="10" onKeyDown="return tabular(event,this)" style="display:none" onKeyPress="return solonumeros(event)" onKeyUp="return habilitar(event,this.name)"  ></td>
                  <td width="59" class="Tablas"><label>Guia Emp.:</label></td>
                  <td class="Tablas"><input name="NGuias" type="text" class="Tablas" id="NGuias" value="<?=$NGuias ?>" onKeyDown="return tabular(event,this)" style="width:100px;background:#FFFF99" readonly="readonly" onKeyUp="return habilitar(event,this.name)" onBlur="u.prepagada.value = 0; if(this.value!=''){solicitarDatosConve(this.value)}else{u.BolsaEmpaque.disabled = false; u.CantidadEmpaque.readOnly = false; u.CantidadEmpaque.style.backgroundColor=''; u.Emplaye.disabled = false;}" >                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td colspan="2" class="Tablas">Destino:
                    <input name="country" type="text" class="Tablas" id="country" style="font-size:9px; text-transform:uppercase;background:#FFFF99" readonly="readonly"  value="<?=$country ?>" size="35" maxlength="60" autocomplete="array:desc"></td></tr>
                <tr>
                  <td height="19" colspan="3" class="Tablas"><input type="hidden" id="country_hidden" name="countryid" value="<?=$countryid ?>">
                    <input name="iddestino" type="hidden" id="iddestino" value="<?=$iddestino ?>">
                    <input type="hidden" name="prepagada" />
                    <label>
                      <select name="entrega" id="entrega" style="display:none" class="Tablas" onkeypress="if(event.keyCode==13 || event.keyCode==9){validarEntrega(this.value)}" onkeydown="if(event.keyCode==9){validarEntrega(this.value);}" onclick="validarEntrega(this.value);">
                      <option value="">SELECCIONAR</option>
                      <option value="1">EAD</option>
                      <option value="2">OCURRE</option>
                    </select>
                    </label></td>
                  <td class="Tablas">&nbsp;</td>
                  <td width="77" class="Tablas">Suc Destino:</td>
                  <td width="204" class="Tablas"><div id="txtDestino">
                    <input name="SucDestino" type="text" class="Tablas" id="SucDestino" style="background:#FFFF99" value="<?=$SucDestino ?>" size="28" readonly="">
                  </div></td>
                </tr>
              </table></th>
              </tr>
            <tr>
              <th scope="row"><table width="638" border="0" cellpadding="0">
                <tr>
                  <td><table id="tablaguias" width="638" border="0" cellpadding="0" cellspacing="0"></table></td>
                </tr>
                <tr>
                  <th scope="row"><label></label>
                    <table width="150" border="0" align="right">
                      <tr>
                        <td width="70"><img  id="img_eliminar" src="../img/Boton_Eliminar.gif" alt="Eliminar" width="70" height="20" style="cursor:pointer" onClick="borrarFila()" /></td>
                        <td width="95"><img id="btnAgregar" src="../img/Boton_Agregari.gif" width="70" height="20" style="cursor:pointer" onClick="if(tabla1.getRecordCount()==1 && document.all.prepagada.value==1){alerta3('Solo puede registrar 1 articulo para las guias prepagadas','¡ATENCION!');}else{abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n');}" /></td>
                      </tr>
                    </table>                  </th>
                </tr>
              </table></th>
            </tr>
            <tr>
              <th height="87" scope="row"><table width="638" height="73" border="0" cellpadding="0" cellspacing="0" class="Tablas">
                <tr>
                  <td height="12" colspan="4" class="FondoTabla" scope="row">Servicios </td>
                  </tr>
                <tr>
                  <td height="20" colspan="3" class="Tablas" scope="row"><label></label>
                    <input name="BolsaEmpaque" type="checkbox" class="Txt" onclick="this.checked=!this.checked" id="BolsaEmpaque" value="1">
                    <span class="Estilo3">Bolsa de Empaque</span>
                    <input name="CantidadEmpaque" type="text"  class="Tablas" id="CantidadEmpaque" onKeyPress="ObtenerTotalBolsa(event,this.value)" onBlur="ObtenerTotalBolsaFoco()" value="<?=$CantidadEmpaque ?>" size="5" maxlength="5" style="background:#FFFF99" readonly="readonly">
                    <input name="TotalEmpaque" type="text" class="Tablas" style="background:#FFFF99" id="TotalEmpaque" value="<?=$TotalEmpaque ?>" size="10" readonly="readonly"  >
                    <input name="costobolsa" type="hidden" id="costobolsa" value="<?=$costobolsa ?>"></td>
                  <td width="333"><table width="181" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td id="cel"><img src="../img/Boton_Guardar.gif" alt="Guardar" name="i_guardar" width="70" height="20" id="i_guardar" style="cursor:pointer;" onClick="Validar();"  /></td>
					  <td><img src="../img/Boton_Nuevo.gif" style="cursor:pointer" alt="Nuevo" width="70" height="20" onClick="confirmar('Perdera la informaci&oacute;n capturada, ¿Desea continuar?', '', 'limpiar();', '')"  /></td>
                    </tr>
                  </table>				   </td>
                </tr>
                <tr>
                  <td height="20" colspan="3" class="Tablas" scope="row">
<input name="Emplaye" type="checkbox" class="Txt" id="Emplaye" value="1"  onclick="this.checked=!this.checked" >                    
Emplaye &nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="TotalEmplaye" type="text" class="Tablas" id="TotalEmplaye" style="background:#FFFF99" value="<?=$TotalEmplaye ?>" size="10" readonly="readonly">
                    <input name="costoemplaye" type="hidden" id="costoemplaye" value="<?=$costoemplaye ?>">
                    <input name="costoemplayeextra" type="hidden" id="costoemplayeextra" value="<?=$costoemplayeextra ?>"></td>
                  <td><label></label></td>
                </tr>
                <tr>
                  <td width="36" height="12" scope="row"><div id="txtBolsa"></div>
                  <div id="txtEmplaye"></div></td>
                  <td width="254" scope="row"><input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora; ?>">
                    <input name="user" type="hidden" id="user" value="<?=$usuario ?>">
                    <input name="msg1" type="hidden" id="msg1" value="<?=$msg ?>">                   
					 <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>">
                    <input name="registros" type="hidden" id="registros">
                    <input name="totalpeso" type="hidden" id="totalpeso" value="<?=$totalpeso ?>">
                    <input name="totalvol" type="hidden" id="totalvol" value="<?=$totalvol ?>">
                    <input name="limite" type="hidden" id="limite" value="<?=$limite ?>">
                    <input name="costoextra" type="hidden" id="costoextra" value="<?=$costoextra ?>">
                    <input name="porcada" type="hidden" id="porcada" value="<?=$porcada ?>"></td>
                  <td width="43" scope="row"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>
                  <td><input name="indice" type="hidden" id="indice" value="<?=$accion ?>">
                    <input name="hsucursal" type="hidden" id="hsucursal">
                    <input name="subdestino" type="hidden" id="subdestino" />
                    <input name="precioead_h" type="hidden" id="precioead_h" /></td>
                </tr>
              </table></th>
            </tr>
          </table></div></td>
        </tr>
      </table>        </td>
    </tr>
  </table> 
</form>
</body>
</html>
<script>
	//parent.frames[1].document.getElementById('titulo').innerHTML = 'EVALUACION MERCANCIAS';
</script>