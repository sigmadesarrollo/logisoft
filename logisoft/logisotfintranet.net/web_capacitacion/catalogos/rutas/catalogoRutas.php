<? 	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/funciones.js"></script>
<script>
	var u = document.all;
	var tabla1 	= new ClaseTabla();	
	var btn_Modificar = '<img src="../../img/Boton_Modificar.gif" style="cursor:pointer" onclick="agregar()" />';
	var btn_Agregar = '<img src="../../img/Boton_Agregari.gif" name="img_agregar" width="70" height="20" id="img_agregar" style="cursor:pointer" title="Agregar" onclick="agregar();"/>';
	tabla1.setAttributes({
		nombre:"detallex",
		campos:[
			{nombre:"DIA SALIDA", medida:65, alineacion:"left", datos:"dia"},
			{nombre:"SUCURSAL", medida:60, alineacion:"center", datos:"sucursal"},
			{nombre:"HR. LLEGADA", medida:60, alineacion:"left", datos:"llegada"},
			{nombre:"T. DESCARGA", medida:60, alineacion:"left", datos:"descarga"},
			{nombre:"T. CARGA", medida:60, alineacion:"left", datos:"carga"},
			{nombre:"HR. SALIDA", medida:60, alineacion:"left",  datos:"salida"},
			{nombre:"T. T_S", medida:60, alineacion:"left",  datos:"siguiente"},
			{nombre:"TRANSBORDO", medida:60, alineacion:"center",  datos:"trasbordo"},
			{nombre:"SUCTRANSBORDO", medida:4, tipo:"oculto", alineacion:"center",  datos:"suctransbordo"},
			{nombre:"TIPO", medida:4, tipo:"oculto",  alineacion:"center",  datos:"tipo"},
			{nombre:"FECHA", medida:4, tipo:"oculto", alineacion:"center",  datos:"fecha"},
			{nombre:"IDSUCURSAL", medida:4, tipo:"oculto",  alineacion:"center",  datos:"idsucursal"},
			{nombre:"IDDESTINO", medida:4,tipo:"oculto", alineacion:"center",  datos:"iddestino"}
		],
		filasInicial:15,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.sucabajo.style.display = "none";
		u.d_eliminar.style.visibility = "hidden";
		u.descripcion.focus();
		obtenerGeneral();
	}
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","catalogoRutas_con.php?accion=1&s="+Math.random());
	}
function obtener(codigo){
	if(codigo!=""){
	document.getElementById('origen').value=codigo;
	var tipo = "destino";
		consulta("mostrarObtenerDestino","../sucursal/consultaDestino.php?destino="+codigo+"&tipo="+tipo+"&sid="+Math.random());
	}
}

//*************MUESTRA DESTINOS*********************//
function mostrarObtenerDestino(datos){
		limpiartodo();
		var u= document.all;
		u.origen.value = datos.getElementsByTagName('codigo').item(0).firstChild.data;
		u.sucursal.value = datos.getElementsByTagName('sucursal').item(0).firstChild.data;
		u.origenb.value = datos.getElementsByTagName('descripcion').item(0).firstChild.data;
		obtenerSucursalEnter(u.sucursal.value) 
		//u.idpoblacion.value = datos.getElementsByTagName('idpoblacion').item(0).firstChild.data;
}
function limpiartodo(){
	u.origen.value ="";
	u.origenb.value="";
//	u.despoblacion.value="";
}
	function mostrarGeneral(datos){
		u.codigo.value = datos;
	}
	var nav4 = window.Event ? true : false;
	function Numeros(evt){
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
		var key = nav4 ? evt.which : evt.keyCode; 
		return (key <= 13 || (key >= 48 && key <= 57));
	}
	function tiposMoneda(evnt,valor){
		caja = valor;
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
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
	function validar(){		
		var tipo = tabla1.getValuesFromField('tipo',',');		
		var recorrido = u.recorridohrs.value+":"+u.recorridomin.value+":00";
		if(u.descripcion.value == ""){
				alerta('Debe capturar Descripción', '¡Atención!','descripcion');
				return false;			
		}else if(recorrido == "00:00"){
				alerta('Debe capturar Tiempo Recorrido', '¡Atención!','recorridohrs');			
				return false;
		}else if(u.km.value == ""){
				alerta('Debe capturar KM', '¡Atención!','km');			
				return false;
		}else if(u.tipounidad.value == ""){
				alerta('Debe capturar Tipo Unidad', '¡Atención!','tipounidad');			
				return false;
		}else if(tabla1.getRecordCount()==0){
				alerta3("Debe capturar la ruta en el detalle","¡Atención!");
				return false;
		}else if(tipo.indexOf("1") < 0 || tipo.indexOf("3") < 0){
				alerta('Debe capturar '+((tipo.indexOf("1") < 0)? 'origen' : 'destino')+' al detalle', '¡Atención!','sucursal');			
				return false;		
		}else{			
			var arr	= new Array();
			arr[0]	= u.descripcion.value;
			arr[1]	= recorrido;
			arr[2]	= u.km.value;
			arr[3]	= u.tipounidad.value;
			arr[4]	= u.tipounidad_des.value;
			u.img_guardar.style.visibility = "hidden";
			if(u.accion.value==""){
				consultaTexto("registro","catalogoRutas_con.php?accion=7&arre="+arr+"&tipo=guardar&s="+Math.random());
			}else if(u.accion.value=="modificar"){
				consultaTexto("registro","catalogoRutas_con.php?accion=7&arre="+arr+"&tipo=modif&s="+Math.random()+"&ruta="+u.codigo.value);
			}
		}			
	}
	function registro(datos){
		if(datos.indexOf("ok")>-1){
			u.img_guardar.style.visibility = "visible";
			var row = datos.split(",");
				if(row[1]=="guardar"){
					u.codigo.value = row[2];
					u.accion.value	= "modificar";
					info("Los datos han sido guardados correctamente","");
				}else{
					info("Los cambios han sido guardados correctamente","");
				}
		}else{
			u.img_guardar.style.visibility = "visible";
			alerta3("Hubo un error al guardar.<br> "+datos,"¡Atención!");
		}
	}
	function obtenerTipoUnidadEnter(id){
		consultaTexto("mostrarTipoUnidad","catalogoRutas_con.php?accion=2&unidad="+id+"&s="+Math.random());
	}
	function mostrarTipoUnidad(datos){
		if(datos!="0"){
			var obj = eval(convertirValoresJson(datos));
			u.tipounidad_des.value = obj[0].descripcion;
			u.sucursal.select();
		}else{
			alerta("El codigo de Tipo unidad no existe","¡Atencion!","tipounidad");
		}
	}
	function obtenerTipoUnidad(id,des){	
		u.tipounidad.value 		= id;
		u.tipounidad_des.value	= des;
	}
	
	function obtenerSucursalEnter(id){		
		consultaTexto("mostrarSucursal","catalogoRutas_con.php?accion=3&sucursal="+id+"&s="+Math.random());
	}
	function mostrarSucursal(datos){
		if(datos!="0"){
			var obj = eval(convertirValoresJson(datos));
			u.sucursalb.value = obj[0].prefijo;
			u.rtipo[0].focus();
		}else{
			alerta("El codigo de Sucursal no existe","¡Atencion!","sucursal");
		}
	}
	function obtenerSucursal(id,descripcion){
		u.sucursal.value  = id;
		u.sucursalb.value = descripcion;
	}
	
	function obtenerRuta(id){
		consultaTexto("mostrarRuta","catalogoRutas_con.php?accion=10&ruta="+id+"&sid="+Math.random());
	}
	function obtenerRutaBusqueda(id){
		u.codigo.value = id;
		consultaTexto("mostrarRuta","catalogoRutas_con.php?accion=10&ruta="+id+"&sid="+Math.random());
	}
	function mostrarRuta(datos){
		limpiar(1);
		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){			
			var obj = eval(convertirValoresJson(datos));			
			u.descripcion.value 	= obj.principal.descripcion;
			var row 				= obj.principal.recorrido.split(":");
			u.recorridohrs.value	= row[0];
			u.recorridomin.value	= row[1];
			u.km.value				= obj.principal.km;
			u.tipounidad.value		= obj.principal.idtipounidad;
			u.tipounidad_des.value	= obj.principal.tipounidad;
			u.accion.value			= "modificar";
			u.d_eliminar.style.visibility = "visible";
			
			u.todas.checked = ((obj.idsucursal == "0")?true:false);	
			if(u.todas.checked==true){
				u.hidensucursal.value = "TODAS";
				u.sucursalesead1_sel.disabled = true;
				u.sucursalesead1.disabled = true;
			}
			if(obj.idsucursal != "0"){
				u.hidensucursal.value = obj.idsucursal;
			}
			
			tabla1.setJsonData(obj.detalle);
			agregarValores(u.sucursalesead1_sel,obj.sucursales);			
		}else{
			alerta("El codigo de Ruta no existe","¡Atención!","descripcion");
		}
	}
	function mostrarGrids(datos){
		var objeto = eval(convertirValoresJson(datos));
		agregarValores(u.sucursalesead1_sel,objeto);		
	}
	function agregarValores(combo,objeto){		
		combo.options.length = 0;
		var opcion;
		for(var i=0; i<objeto.length; i++){
			opcion = new Option(objeto[i].nombre,objeto[i].clave);			
			combo.options[combo.options.length] = opcion;
		}
	}
	function agregar(){
		var horallegada = u.hllegada.value +":"+ u.mllegada.value;
		var horasalida  = u.hsalida.value  +":"+ u.msalida.value;
		var carga		= u.cargahrs.value +":"+ u.cargamin.value;
		var descarga 	= u.descargahrs.value +":"+ u.descargamin.value;
		var ttss 		= u.ttsshrs.value +":"+ u.ttssmin.value;
		var elementos = document.getElementsByName("rtipo");
		
		for(var i=0; i<elementos.length; i++){
			if(elementos[i].checked){
				valorSeleccionado = elementos[i].value;
			}
		}
		var semana = "";
		if(u.rtipo[0].checked==true){
			for(var i=1;i<=7;i++){
				if (document.getElementById('checkbox'+i).checked == true) {
					semana += document.getElementById('checkbox'+i).value +"-";
				}
			}
			semana=semana.substr(0,semana.length-1);
	 	}else{
			semana	= "";
		}
	if(u.rtipo[0].checked == true && (u.checkbox1.checked==false &&
	u.checkbox2.checked == false && u.checkbox3.checked == false &&
	u.checkbox4.checked==false && u.checkbox5.checked == false &&
	u.checkbox6.checked == false && u.checkbox7.checked == false)){
		alerta('Debe capturar Días de Salida', '¡Atención!','checkbox1'); 
		return false;
	}else if(u.tipounidad.value == ""){
		alerta('Debe capturar Tipo de unidad', '¡Atención!','tipounidad'); 
		return false;
	}else if(u.sucursal.value == ""){
		alerta('Debe capturar Sucursal', '¡Atención!','sucursal'); 
		return false;	
	}else if(u.rtipo[0].checked == true && carga == "00:00"){
		alerta('Debe capturar Tiempo Carga', '¡Atención!','cargahrs'); 
		return false;
	}else if(u.rtipo[1].checked == true && carga == "00:00"){
		alerta('Debe capturar Tiempo Carga', '¡Atención!','cargahrs'); 
		return false;
	}else if(u.rtipo[1].checked == true && descarga =="00:00"){
		alerta('Debe capturar Tiempo Descarga', '¡Atención!','descargahrs'); 
		return false;
	}else if(u.rtipo[2].checked == true && descarga =="00:00"){
		alerta('Debe capturar Tiempo Descarga', '¡Atención!','descargahrs'); 
		return false;
	}else if(u.rtipo[0].checked == true && ttss == "00:00"){
		alerta('Debe capturar Tiempo Trayecto Siguiente Sucursal', '¡Atención!','ttsshrs');    
		return false;
	}else if(u.rtipo[1].checked == true && ttss == "00:00"){
		alerta('Debe capturar Tiempo Trayecto Siguiente Sucursal', '¡Atención!','ttsshrs');
		return false;
	}else if(u.rtipo[0].checked == true && horasalida  == "00:00" ){
		alerta('Debe capturar Hora Salida', '¡Atención!','hsalida');
		return false;
	}else if(u.rtipo[0].checked == true && horasalida == "00:00"){
		alerta('Debe capturar Hora Salida', '¡Atención!','hsalida'); 
		return false;
	}else if(u.rtipo[1].checked == true && horallegada == "00:00"){
		alerta('Debe capturar Hora Llegada', '¡Atención!','hllegada'); 
		return false;
	}else if(u.rtipo[1].checked == true && horasalida  == "00:00"){
		alerta('Debe capturar Hora Salida', '¡Atención!','hsalida'); 
		return false;
	}else if(u.rtipo[2].checked == true && horallegada == "00:00"){
		alerta('Debe capturar Hora Llegada', '¡Atención!','hllegada'); 
		return false;
	}else if(u.transbordo.checked==true && u.sucursalesead1_sel2.options.length == 0){
		alerta('Debe capturar Sucursal Transbordo', '¡Atención!','transbordo'); 
		return false;
	}
	var v_suc = "";
	v_suc = tabla1.getValuesFromField("sucursal",",");
		/*if(u.modifico.value==""){
			if(v_suc!=""){
				if(v_suc.indexOf(u.sucursalb.value)>-1){
					alerta("La sucursal "+u.sucursalb.value+" ya fue agregada","¡Atención!","sucursal");
					return false;
				}
			}
			var v_tipo = "";
			v_tipo = tabla1.getValuesFromField("tipo",",");
			if(v_tipo!=""){
				if(u.rtipo[0].checked == true && v_tipo.indexOf("1")>-1){
					alerta3("Ya fue agregada una sucursal Origen","¡Atención!");
					return false;
				}else if(u.rtipo[1].checked == true && v_tipo.indexOf("3")>-1){
				alerta3("No se puede agregar una sucursal intermedio por que ya fue agregada una sucursal Destino","¡Atención!");
					return false;
				}else if(u.rtipo[2].checked == true && v_tipo.indexOf("3")>-1){
					alerta3("Ya fue agregada una sucursal Destino","¡Atención!");
					return false;
				}
			}
		}*/
		if(u.rtipo[1].checked == true || u.rtipo[2].checked == true){
			if(tabla1.getRecordCount()==0){
				alerta3("Debe capturar primero un Origen","¡Atención!");
				return false;
			}
		}
		//u.img_agregar.style.visibility = "hidden";
		var obj 		= new Object();
		obj.dia			= ((u.rtipo[0].checked == true)? semana : "");
		obj.idsucursal	= u.sucursal.value;
		obj.sucursal	= u.sucursalb.value;
		obj.llegada		= ((u.rtipo[0].checked==false)? u.hllegada.value+":"+u.mllegada.value : "");
		obj.descarga	= ((u.rtipo[0].checked==false)? u.descargahrs.value+":"+u.descargamin.value : "");
		obj.carga		= ((u.rtipo[2].checked==false)? u.cargahrs.value+":"+u.cargamin.value : "");
		obj.salida		= ((u.rtipo[2].checked==false)? u.hsalida.value+":"+u.msalida.value : "");
		obj.siguiente	= ((u.rtipo[2].checked==false)? u.ttsshrs.value+":"+u.ttssmin.value : "");
		obj.trasbordo	= ((u.transbordo.checked==true)? "SI" : "");
		obj.suctransbordo = u.hidensucursal2.value;
		obj.iddestino = document.getElementById('origen').value;
		if(u.rtipo[0].checked==true){
			obj.tipo		=  "1";	
		}else if(u.rtipo[1].checked==true){
			obj.tipo		=  "2";	
		}else{
			obj.tipo		=  "3";	
		}
		u.d_eliminar.style.visibility = "visible";
		u.sucursal.value	= "";
		u.sucursalb.value	= "";
		u.hllegada.value	= "00"; 	u.mllegada.value	= "00";
		u.hsalida.value		= "00"; 	u.msalida.value		= "00";
		u.cargahrs.value	= "00";		u.cargamin.value	= "00";
		u.descargahrs.value = "00";		u.descargamin.value	= "00";
		u.ttsshrs.value		= "00";     u.ttssmin.value		= "00";
		u.sucursalesead1_sel2.options.length = 0;
		u.sucursalesead12.value = 0;
		u.todas2.checked = false;
		u.sucursalesead12.disabled = false;
		u.transbordo.checked = false;
		u.sucursalestransbordo.style.visibility='hidden';
		if(u.modifico.value==""){
		obj.fecha	= fechahora(obj.fecha);
		tabla1.add(obj);
	consultaTexto("registroRuta","catalogoRutas_con.php?accion=4&tipo="+obj.tipo+"&semana="+semana+"&origen="+obj.iddestino+"&sucursal="+obj.idsucursal+"&sucursalb="+obj.sucursal+"&llegada="+((obj.llegada!="")?obj.llegada:"00:00:00")+"&descarga="+((obj.descarga!="")?obj.descarga:"00:00:00")+"&carga="+((obj.carga!="")?obj.carga:"00:00:00")+"&salida="+((obj.salida!="")?obj.salida:"00:00:00")+"&ttss="+((obj.siguiente!="")?obj.siguiente:"00:00:00")+"&transbordo="+((obj.trasbordo=="SI")?1:0)+"&hidensucursal2="+obj.suctransbordo+"&fecha="+obj.fecha+"&sid="+Math.random());
			
		}else{
			u.modifico.value	="";
			var f = tabla1.getSelectedRow();			
	consultaTexto("registroRuta","catalogoRutas_con.php?accion=6&tipo="+obj.tipo+"&semana="+semana+"&origen="+obj.iddestino+"&sucursal="+obj.idsucursal+"&sucursalb="+obj.sucursal+"&llegada="+((obj.llegada!="")?obj.llegada:"00:00:00")+"&descarga="+((obj.descarga!="")?obj.descarga:"00:00:00")+"&carga="+((obj.carga!="")?obj.carga:"00:00:00")+"&salida="+((obj.salida!="")?obj.salida:"00:00:00")+"&ttss="+((obj.siguiente!="")?obj.siguiente:"00:00:00")+"&transbordo="+((obj.trasbordo=="SI")?1:0)+"&hidensucursal2="+obj.suctransbordo+"&fecha="+f.fecha+"&sid="+Math.random()+"&ruta="+((u.accion.value=="modificar")? u.codigo.value : ""));
			obj.fecha = f.fecha;
			tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
			u.rtipo[0].checked = true; u.rtipo[0].disabled = false;
			u.rtipo[1].disabled = false; u.rtipo[2].disabled = false;
			inhabilita();
		}
	}
	function registroRuta(datos){	
		if(datos.indexOf("ok")>-1){
			u.col_boton.innerHTML = btn_Agregar;
			if(tabla1.getRecordCount()==0){
				u.d_eliminar.style.visibility = "hidden";
			}			
		}else{
			u.col_boton.innerHTML = btn_Agregar;
			alerta3("Hubo un error al agregar "+datos,"¡Atención!");
		}
	}
	function inhabilita(){		
		if(u.rtipo[0].checked == true){
			u.hllegada.disabled = true;
			u.mllegada.disabled = true;
			u.hsalida.disabled  = false;
			u.msalida.disabled	= false;
			
			u.cargahrs.disabled = false;
			u.cargamin.disabled = false;
			u.ttsshrs.disabled	= false;
			u.ttssmin.disabled	= false;
			u.descargahrs.disabled=true;
			u.descargamin.disabled=true;
	
			u.hllegada.value = "00";
			u.mllegada.value = "00";
			u.cargahrs.value = "00";
			u.cargamin.value = "00";
			u.ttsshrs.value	 = "00";
			u.ttssmin.value	 = "00";	
				
			document.getElementById('checkbox1').disabled = false; 
			document.getElementById('checkbox2').disabled = false; 
			document.getElementById('checkbox3').disabled = false; 
			document.getElementById('checkbox4').disabled = false; 
			document.getElementById('checkbox5').disabled = false; 
			document.getElementById('checkbox6').disabled = false; 
			document.getElementById('checkbox7').disabled = false; 
	
			document.all.transbordo.checked=false;
			document.all.transbordo.value=0;
			u.transbordo.disabled = true;
			u.sucursalestransbordo.style.visibility='hidden';
		}
		if(u.rtipo[1].checked == true){
			u.hllegada.disabled = false;
			u.mllegada.disabled = false;
			u.hsalida.disabled  = false;
			u.msalida.disabled	= false;
			
			u.cargahrs.disabled = false;
			u.cargamin.disabled = false;
			u.ttsshrs.disabled	= false;
			u.ttssmin.disabled	= false;
			u.descargahrs.disabled=false;
			u.descargamin.disabled=false;
			
			document.getElementById('checkbox1').disabled = true; 
			document.getElementById('checkbox2').disabled = true; 
			document.getElementById('checkbox3').disabled = true; 
			document.getElementById('checkbox4').disabled = true; 
			document.getElementById('checkbox5').disabled = true; 
			document.getElementById('checkbox6').disabled = true; 
			document.getElementById('checkbox7').disabled = true; 
	
			u.transbordo.checked=false;
			u.transbordo.value=0;
			u.transbordo.disabled = false;	
		}
		if(u.rtipo[2].checked == true){
			u.hllegada.disabled = false;
			u.mllegada.disabled = false;
			u.hsalida.disabled  = true;
			u.msalida.disabled	= true;
			
			u.cargahrs.disabled = true;
			u.cargamin.disabled = true;
			u.ttsshrs.disabled	= true;
			u.ttssmin.disabled	= true;
			u.descargahrs.disabled=false;
			u.descargamin.disabled=false;	
			
			u.hsalida.value  = "00";
			u.msalida.value	 = "00";
			u.cargahrs.value = "00";
			u.cargamin.value = "00";
			u.ttsshrs.value	 = "00";
			u.ttssmin.value	 = "00";	
				
			document.getElementById('checkbox1').disabled = true; 
			document.getElementById('checkbox2').disabled = true; 
			document.getElementById('checkbox3').disabled = true; 
			document.getElementById('checkbox4').disabled = true; 
			document.getElementById('checkbox5').disabled = true; 
			document.getElementById('checkbox6').disabled = true; 
			document.getElementById('checkbox7').disabled = true;
			
			u.transbordo.checked=false;
			u.transbordo.value=0;
			u.transbordo.disabled = false;
			u.sucursalestransbordo.style.visibility='hidden';
		}
	}
	
	function inhabilitaEliminar(){
		if(u.rtipo[0].checked == true){
			u.hllegada.disabled = true;
			u.mllegada.disabled = true;
			u.hsalida.disabled  = false;
			u.msalida.disabled	= false;
			
			u.cargahrs.disabled = false;
			u.cargamin.disabled = false;
			u.ttsshrs.disabled	= false;
			u.ttssmin.disabled	= false;
			u.descargahrs.disabled=true;
			u.descargamin.disabled=true;
	
			u.hllegada.value = "00";
			u.mllegada.value = "00";
			u.cargahrs.value = "00";
			u.cargamin.value = "00";
			u.ttsshrs.value	 = "00";
			u.ttssmin.value	 = "00";	
				
			document.getElementById('checkbox1').disabled = false; 
			document.getElementById('checkbox2').disabled = false; 
			document.getElementById('checkbox3').disabled = false; 
			document.getElementById('checkbox4').disabled = false; 
			document.getElementById('checkbox5').disabled = false; 
			document.getElementById('checkbox6').disabled = false; 
			document.getElementById('checkbox7').disabled = false; 
	
			document.all.transbordo.checked=false;
			document.all.transbordo.value=0;
			u.transbordo.disabled = true;
		}
		if(u.rtipo[1].checked == true){
			u.hllegada.disabled = false;
			u.mllegada.disabled = false;
			u.hsalida.disabled  = false;
			u.msalida.disabled	= false;
			
			u.cargahrs.disabled = false;
			u.cargamin.disabled = false;
			u.ttsshrs.disabled	= false;
			u.ttssmin.disabled	= false;
			u.descargahrs.disabled=false;
			u.descargamin.disabled=false;
			
			document.getElementById('checkbox1').disabled = true; 
			document.getElementById('checkbox2').disabled = true; 
			document.getElementById('checkbox3').disabled = true; 
			document.getElementById('checkbox4').disabled = true; 
			document.getElementById('checkbox5').disabled = true; 
			document.getElementById('checkbox6').disabled = true; 
			document.getElementById('checkbox7').disabled = true; 
	
			u.transbordo.checked=false;
			u.transbordo.value=0;
			u.transbordo.disabled = false;	
		}
		if(u.rtipo[2].checked == true){
			u.hllegada.disabled = false;
			u.mllegada.disabled = false;
			u.hsalida.disabled  = true;
			u.msalida.disabled	= true;
			
			u.cargahrs.disabled = true;
			u.cargamin.disabled = true;
			u.ttsshrs.disabled	= true;
			u.ttssmin.disabled	= true;
			u.descargahrs.disabled=false;
			u.descargamin.disabled=false;	
			
			u.hsalida.value  = "00";
			u.msalida.value	 = "00";
			u.cargahrs.value = "00";
			u.cargamin.value = "00";
			u.ttsshrs.value	 = "00";
			u.ttssmin.value	 = "00";	
				
			document.getElementById('checkbox1').disabled = true; 
			document.getElementById('checkbox2').disabled = true; 
			document.getElementById('checkbox3').disabled = true; 
			document.getElementById('checkbox4').disabled = true; 
			document.getElementById('checkbox5').disabled = true; 
			document.getElementById('checkbox6').disabled = true; 
			document.getElementById('checkbox7').disabled = true;
		}
	}
	
	function eliminarRuta(){
		if(tabla1.getValSelFromField('sucursal','SUCURSAL')!=""){
			var tipo = tabla1.getValSelFromField('tipo','TIPO');
			var v_tipo = tabla1.getValuesFromField("tipo",",");
			if(tipo == 1){
				if(v_tipo.indexOf("3")>-1){
				alerta('No puede eliminar la sucursal origen, es necesario eliminar el destino','¡Atención!','sucursal'); 
					return false;
				}
				if(v_tipo.indexOf("2")>-1){
					alerta('No puede eliminar la sucursal origen, es necesario eliminar el intermedio','¡Atención!','sucursal'); 
					return false;
				}
			}
			if(tipo == 2){
				if(v_tipo.indexOf("3")>-1){
				alerta('No puede eliminar la sucursal intermedia, es necesario eliminar el destino','¡Atención!','sucursal'); 
					return false;
				}
			}
			confirmar('¿Esta seguro de Eliminar la Fila?','','borrarFila()','');
		}
	}
	function borrarFila(){
		var obj = tabla1.getSelectedRow();
		consultaTexto("eliminoFila","catalogoRutas_con.php?accion=5&idsucursal="+obj.idsucursal+"&d="+Math.random());	 	
	}
	function eliminoFila(datos){
		if(datos.indexOf("ok")>-1){
			tabla1.deleteById(tabla1.getSelectedIdRow());
			u.col_boton.innerHTML = btn_Agregar;
			u.modifico.value		="";
			//inhabilitaEliminar();
			u.rtipo[0].disabled = false;
			u.rtipo[1].disabled = false;
			u.rtipo[2].disabled = false;
			
			if(tabla1.getRecordCount()==0){
		  		u.Eliminar.style.visibility = "hidden";
	  		}
		}else{
			alerta3("Hubo un error al eliminar "+datos,"¡Atención!");
		}
	}
	function ModificarFila(){
		if(tabla1.getValSelFromField('sucursal','SUCURSAL')!=""){
			u.col_boton.innerHTML = btn_Modificar;
			var obj = tabla1.getSelectedRow();
			//u.tipogrid.value = obj.tipo;
			if(obj.tipo == 1){
				u.modifico.value	= "1";
				u.hllegada.disabled = true;   u.transbordo.checked=false;
				u.mllegada.disabled = true;   u.hsalida.disabled  = false;
				u.msalida.disabled	= false;  u.cargahrs.disabled = false;
				u.cargamin.disabled = false;  u.ttsshrs.disabled	= false;
				u.ttssmin.disabled	= false;  u.descargahrs.disabled=true;
				u.descargamin.disabled=true;  u.rtipo[1].disabled = true;
				u.rtipo[2].disabled = true;   u.transbordo.disabled=true;
				for(var i=1;i<=7;i++){
					document.getElementById('checkbox'+i).disabled = false;
				}			
				var row = obj.dia.split("-");
				for(var f=1;f<=7;f++){
					for(var i=0;i<row.length;i++){
						if(document.getElementById('checkbox'+f).value==row[i] ){
							document.getElementById('checkbox'+f).checked=true;
							break;
						}else{
							document.getElementById('checkbox'+f).checked=false;
						}
					}
				}
				obtener(obj.iddestino)
				u.sucursalb.value	= obj.sucursal;
				u.sucursal.value	= obj.idsucursal;
				u.rtipo[0].checked	= true;
				var car = obj.carga.split(":");
				u.cargahrs.value	= car[0];
				u.cargamin.value	= car[1];
				var sig = obj.siguiente.split(":");
				u.ttsshrs.value		= sig[0];
				u.ttssmin.value		= sig[1];
				var sal = obj.salida.split(":");
				u.hsalida.value		= sal[0];
				u.msalida.value		= sal[1];
				
				u.sucursalestransbordo.style.visibility = "hidden";
				u.transbordo.checked  = false;
				
			}else if(obj.tipo == 2){
				u.modifico.value	= "1";
				u.hllegada.disabled = false; u.mllegada.disabled = false;
				u.hsalida.disabled  = false; u.msalida.disabled	= false;
				u.cargahrs.disabled = false; u.cargamin.disabled = false;
				u.ttsshrs.disabled	= false; u.ttssmin.disabled	= false;
				u.descargahrs.disabled=false; u.descargamin.disabled=false;
				u.transbordo.disabled=false; u.rtipo[0].disabled = true;
				u.rtipo[2].disabled = true;
				for(var i=1;i<=7;i++){
					document.getElementById('checkbox'+i).disabled = true;
				}
				obtener(obj.iddestino)	
				u.sucursalb.value	= obj.sucursal;
				u.sucursal.value	= obj.idsucursal;
				u.rtipo[1].checked	= true;
				var car = obj.carga.split(":");
					u.cargahrs.value	= car[0];
					u.cargamin.value	= car[1];
				var sig = obj.siguiente.split(":");
					u.ttsshrs.value		= sig[0];
					u.ttssmin.value		= sig[1];
				var sal = obj.salida.split(":");
					u.hsalida.value		= sal[0];
					u.msalida.value		= sal[1];
				var des = obj.descarga.split(":");
					u.descargahrs.value	= des[0];
					u.descargamin.value	= des[1];
				var lle = obj.llegada.split(":");
					u.hllegada.value	= lle[0];
					u.mllegada.value	= lle[1];
				if(obj.trasbordo=="SI"){
					u.transbordo.checked = true;
					esTransbordo(obj.suctransbordo);
				}
			}else if(obj.tipo == 3){
				u.modifico.value	= "1";
				obtener(obj.iddestino)
				u.hllegada.disabled = false;	u.mllegada.disabled = false;
				u.hsalida.disabled  = true;		u.msalida.disabled	= true;
				u.cargahrs.disabled = true;		u.cargamin.disabled = true;
				u.ttsshrs.disabled	= true;		u.ttssmin.disabled	= true;
				u.descargahrs.disabled=false;	u.descargamin.disabled=false;
				u.transbordo.disabled=false; 	u.rtipo[0].disabled = true;
				u.rtipo[1].disabled = true;
				for(var i=1;i<=7;i++){
					document.getElementById('checkbox'+i).disabled = true;
				}
				u.sucursalb.value	= obj.sucursal;
				u.sucursal.value	= obj.idsucursal;
				u.rtipo[2].checked	= true;
				var des = obj.descarga.split(":");
					u.descargahrs.value	= des[0];
					u.descargamin.value	= des[1];
				var lle = obj.llegada.split(":");
					u.hllegada.value	= lle[0];
					u.mllegada.value	= lle[1];				
				if(obj.trasbordo=="SI"){
					u.transbordo.checked = true;
					esTransbordo(obj.suctransbordo);
				}
			}
		}
	}
	
	function esTransbordo(suctransbordo){		
		if(u.transbordo.checked == true){
			u.transbordo.checked = true;
			u.sucursalestransbordo.style.visibility="visible";	
			u.hidensucursal2.value   = suctransbordo;
		
			if(suctransbordo=="TODAS"){				
				u.todas2.checked = true;	
				u.sucursalesead12.disabled=true;
				u.sucursalesead1_sel2.disabled = true;
				agregarTodasSucursales2();
			}else{
				u.todas2.checked = false;	
				u.sucursalesead12.disabled=false;
				u.sucursalesead1_sel2.disabled = false;			
			var cansuc = suctransbordo.split(",");
			obtener(obj.iddestino)
			u.sucursalesead1_sel2.options.length = 0;
			var opcion;
				for(var i=0; i<cansuc.length; i++){
					var sucursales=cansuc[i].split(":");
					opcion = new Option(sucursales[1],sucursales[0]);
					u.sucursalesead1_sel2.options[u.sucursalesead1_sel2.options.length] = opcion;
					if(suctransbordo!="TODAS"){
						u.sucursalesead12.disabled		= false;
						u.sucursalesead1_sel2.disabled 	= false;						
					}
				}
			}
			
		}else{
			u.transbordo.checked=false;
			u.transbordo.value=0;
			u.sucursalestransbordo.style.visibility="hidden";	
		}		
	}	
	function limpiarSucursalCampo(){
		var u = document.all;
		u.sucursalesead12.value="";
		u.todas2.checked=false;
		u.hidensucursal2.value="";
		u.sucursalesead1_sel2.options.length = 0;
	}
	
	function insertarServicio(combo, valor, va, nombre, tipo){
		var u = document.all;
		if(combo.value!=""){
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].value==valor){
					alerta3(nombre+" seleccionado ya fue agregado","¡Atencion!");
					combo.value="";
					return false;
				}
			}
			var opcion = new Option(combo.options[combo.selectedIndex].text,combo.value);
			va.options[va.options.length] = opcion;
			u.hidensucursal.value +=combo.options[combo.selectedIndex].value+":"+combo.options[combo.selectedIndex].text+",";
			consultaTexto("registroSucursal","catalogoRutas_con.php?accion=9&sucursal="+combo.options[combo.selectedIndex].text+"&idsucursal="+combo.options[combo.selectedIndex].value+"&ruta="+((u.accion.value=="modificar")? u.codigo.value : 0));		
			combo.value="";
		}
	}
	function registroSucursal(datos){
		if(datos.indexOf("ok")<0){
			alerta3("Hubo un error al insertar la sucursal "+datos,"¡Atención!");
		}
	}
	function borrarServicio(va,tipo){
		var u = document.all;
		if(va.options.selectedIndex>-1){			
		var frase = u.hidensucursal.value.replace(u.sucursalesead1_sel.value+":"+u.sucursalesead1_sel.options[u.sucursalesead1_sel.selectedIndex].text,"");
			u.hidensucursal.value = frase.replace(",,",",");
			if(u.hidensucursal.value.substring(0,1)==","){
				u.hidensucursal.value = u.hidensucursal.value.substring(1,u.hidensucursal.value.legth);
			}			
			var suc = va.options[va.selectedIndex].value;
			var nom = va.options[va.options.selectedIndex].text;
			va.options[va.options.selectedIndex] = null;
			va.value = "";
		consultaTexto("eliminarSucursal","catalogoRutas_con.php?accion=9&tipo=eliminar1&idsucursal="+suc);			
		}
	}
	
	function eliminarSucursal(datos){
		if(datos.indexOf("ok")<0){
			alerta3("Hubo un error al insertar la sucursal "+datos,"¡Atención!");
		}
	}
	
	function agregarTodasSucursales(){
		var u = document.all;
		if(u.todas.checked==true){
			u.hidensucursal.value = "TODAS";
			u.sucursalesead1_sel.options.length = 0;
			for(var i=1; i<u.sucursalesead1.options.length; i++){
				var opcion = new Option(u.sucursalesead1.options[i].text,u.sucursalesead1.value);
				u.sucursalesead1_sel.options[u.sucursalesead1_sel.options.length] = opcion;		
			}
			u.sucursalesead1.disabled=true;
			u.sucursalesead1_sel.disabled = true;
			consultaTexto("registroSucursal","catalogoRutas_con.php?accion=9&sucursal=TODAS&idsucursal=0&ruta="+((u.accion.value=="modificar")? u.codigo.value : 0));
		}else{
			u.sucursalesead1_sel.options.length = 0;
			u.hidensucursal.value = "";
			u.sucursalesead1.disabled=false;
			u.sucursalesead1_sel.disabled = false;
			consultaTexto("registroSucursal","catalogoRutas_con.php?accion=9&sucursal=TODAS&idsucursal=0&tipo=eliminar&ruta="+((u.accion.value=="modificar")? u.codigo.value : 0));
		}
	}	
	
	function insertarServicio2(combo, valor, va, nombre, tipo){
		var u = document.all;
		if(combo.value!=""){
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].value==valor){
					alerta3(nombre+" seleccionado ya fue agregado","¡Atencion!");
					combo.value="";
					return false;
				}
			}
			var opcion = new Option(combo.options[combo.selectedIndex].text,combo.value);
			va.options[va.options.length] = opcion;
			u.hidensucursal2.value +=combo.options[combo.selectedIndex].value+":"+combo.options[combo.selectedIndex].text+",";
			combo.value="";
		}
	}
	
	
	function borrarServicio2(va,tipo){
		var u = document.all;
		if(va.options.selectedIndex>-1){			
		var frase = u.hidensucursal2.value.replace(u.sucursalesead1_sel2.value+":"+u.sucursalesead1_sel2.options[u.sucursalesead1_sel2.selectedIndex].text,"");
			u.hidensucursal2.value = frase.replace(",,",",");
			if(u.hidensucursal2.value.substring(0,1)==","){
				u.hidensucursal2.value = u.hidensucursal2.value.substring(1,u.hidensucursal2.value.legth);
			}
			va.options[va.options.selectedIndex] = null;
			va.value = "";
		}
	}
	function agregarTodasSucursales2(){
		if(u.todas2.checked==true){
			//u.hidensucursal2.value = "TODAS";
			//u.sucursalesead1_sel2.options.length = 0;
			for(var i=1; i<u.sucursalesead12.options.length; i++){
				//var opcion = new Option(u.sucursalesead12.options[i].text,u.sucursalesead12.value);
				//u.sucursalesead1_sel2.options[u.sucursalesead1_sel2.options.length] = opcion;		
				document.all.sucursalesead12.value = u.sucursalesead12.options[i].value;
				insertarServicio2(document.all.sucursalesead12, 
								  u.sucursalesead12.options[i].value, 
								  document.all.sucursalesead1_sel2, 
								  'La Sucursal', 'SUCONVENIO');
			}
			//u.sucursalesead12.disabled=true;
			//u.sucursalesead1_sel2.disabled = true;
		}else{
			u.sucursalesead1_sel2.options.length = 0;
			u.hidensucursal2.value = "";
			u.sucursalesead12.disabled=false;
			u.sucursalesead1_sel2.disabled = false;
		}
	}	
	function limpiar(tipo){
		u.descripcion.value		= "";
		u.recorridohrs.value	= "";
		u.recorridomin.value	= "";
		u.km.value				= "";
		for(var i=1;i<=7;i++){
			document.getElementById('checkbox'+i).checked = false;
			document.getElementById('checkbox'+i).disabled = false;		
		}		
		u.tipounidad.value		= "";
		u.tipounidad_des.value	= "";
		u.sucursal.value		= "";
		u.sucursalb.value		= "";
		u.rtipo[0].checked		= true;
		u.transbordo.checked	= false;
		u.cargahrs.value		= "00";
		u.cargamin.value		= "00";
		u.descargahrs.value		= "00";
		u.descargamin.value		= "00";
		u.ttsshrs.value			= "00";
		u.ttssmin.value			= "00";
		u.hllegada.value		= "00";
		u.mllegada.value		= "00";
		u.hsalida.value			= "00";
		u.msalida.value			= "00";
		u.todas2.checked		= false;
		u.sucursalesead12.value	= "SELECCIONAR";
		u.hidensucursal2.value	= "";
		u.sucursalesead1_sel2.disabled = false;
		u.sucursalesead1_sel2.options.length = 0;
		u.modifico.value		= "";
		u.accion.value			= "";
		u.num.value				= "";
		u.origen.value			= "";
		u.destino.value			= "";
		u.hiddenrtipo.value		= "";
		u.idfila.value			= "";
		u.idhidden.value		= "";
		u.hidensucursal.value	= "";
		u.todas.checked			= false;
		u.sucursalesead1.value	= "";
		u.tipogrid.value		= "";
		u.sucursalesead1_sel.disabled = false;
		u.sucursalesead1_sel.value = "";
		u.sucursalesead1.value	= "";
		u.sucursalesead1.disabled = false;
		u.sucursalesead12.value	= "";
		u.sucursalesead12.disabled = false;
		u.sucursalesead1_sel.options.length = 0;
		u.sucursalestransbordo.style.visibility = "hidden";
		u.cargahrs.disabled = false;
		u.cargamin.disabled = false;
		u.descargahrs.disabled = false;
		u.descargamin.disabled = false;
		u.ttsshrs.disabled = false;
		u.ttssmin.disabled = false;
		u.hllegada.disabled = false;
		u.hsalida.disabled = false;
		u.msalida.disabled = false;
		u.rtipo[0].disabled = false;
		u.rtipo[1].disabled = false;
		u.rtipo[2].disabled = false;
		u.rtipo[0].checked = true;
		inhabilita();
		tabla1.clear();
		if(tipo==""){
			u.col_boton.innerHTML = btn_Agregar;
			obtenerGeneral();
		}
	}
</script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<title>Documento sin t&iacute;tulo</title>
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">CAT&Aacute;LOGO RUTAS</td>
    </tr>
    <tr>
      <td><table width="599" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="70">Codigo:</td>
          <td width="85"><span class="Tablas">
            <input name="codigo" type="text" id="codigo" class="Tablas"  value="<?=$codigo ?>" style="width:80px" onkeypress="if(event.keyCode==13){obtenerRuta(this.value)}" />
          </span></td>
          <td width="174"><span class="Tablas"><img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('catalogosrutas_Buscar.php?tipo=3', 600, 500, 'ventana', 'Busqueda')" /></span></td>
          <td width="26">&nbsp;</td>
          <td width="211">&nbsp;</td>
          <td width="33">&nbsp;</td>
        </tr>
        <tr>
          <td>Descripcion:</td>
          <td colspan="4"><span class="Tablas">
            <input name="descripcion" type="text" id="descripcion"  onblur="trim(document.getElementById('descripcion').value,'descripcion');" value="<?=$descripcion ?>" class="Tablas" style=" text-transform:uppercase;width:300px" onkeydown="return tabular(event,this)" />
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>T. Recorrido: </td>
          <td colspan="2"><span class="Tablas">
            <select name="recorridohrs" size="1" onkeydown="return tabular(event,this)" class="Tablas" id="recorridohrs">
              <? for($h=0;$h<=24;$h++){ ?>
              <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
              <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
              </option>
              <? }?>
            </select>
Hrs
<select name="recorridomin" size="1" onkeydown="return tabular(event,this)" class="Tablas" id="select4">
  <? for($m=0;$m<60;$m++){ ?>
  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
  <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
  </option>
  <? }?>
</select>
Min</span></td>
          <td><span class="Tablas">KM:
              
          </span></td>
          <td><span class="Tablas">
            <input name="km" class="Tablas" type="text" id="km" style="width:150px" onblur="trim(document.getElementById('km').value,'km');" onkeypress="return tiposMoneda(event,this.value);" onkeydown="return tabular(event,this)" value="<?=$km ?>" size="10" maxlength="10" />
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" class="FondoTabla">Di&aacute;s de Salida  </td>
          </tr>
        <tr>
          <td colspan="6"><span class="Tablas">
            <label>
            <input name="checkbox1" type="checkbox" onkeydown="return tabular(event,this)" id="checkbox1" style="width:13px" value="L" />
L</label>
            <label>
            <input name="checkbox2" type="checkbox" onkeydown="return tabular(event,this)" id="checkbox2" style="width:13px" value="M" />
M</label>
            <label>
            <input name="checkbox3" type="checkbox" onkeydown="return tabular(event,this)" id="checkbox3" style="width:13px" value="MI"/>
MI</label>
            <label>
            <input name="checkbox4" type="checkbox" onkeydown="return tabular(event,this)" id="checkbox4" style="width:13px" value="J"/>
J</label>
            <label>
            <input name="checkbox5" type="checkbox" onkeydown="return tabular(event,this)" id="checkbox5" style="width:13px" value="V"/>
V</label>
            <label>
            <input name="checkbox6" type="checkbox" onkeydown="return tabular(event,this)" id="checkbox6" style="width:13px" value="S"/>
S
<input name="checkbox7" type="checkbox" id="checkbox7" onkeydown="return tabular(event,this)" style="width:13px" value="D"/>
D </label>
          </span></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="Tablas">Tipo Unidad:</td>
          <td>
            <input name="tipounidad" class="Tablas" type="text" id="tipounidad" style="width:80px" value="<?=$tipounidad ?>" onkeypress="if(event.keyCode==13){ obtenerTipoUnidadEnter(this.value);}" />          </td>
          <td colspan="3"><span class="Tablas">
            <img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer"  title="Buscar Tipo Unidad" onclick="abrirVentanaFija('catalogosrutas_Buscar.php?tipo=1', 600, 500, 'ventana', 'Busqueda')" />&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="tipounidad_des" class="Tablas" type="text" id="tipounidad_des" style="background-color:#FFFF99; text-transform:uppercase;width:250px" value="<?=$tipounidad_des ?>" readonly="" />
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Origen/Destino:</td>
          <td>
            <input name="origen" class="Tablas" type="text" id="origen" style="width:80px" value="<?=$_POST[origen] ?>" onkeypress="if(event.keyCode==13){ obtenerSucursalEnter(this.value);}"/>
          </td>
          <td colspan="3"><span class="Tablas"><img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Destino" onClick="abrirVentanaFija('../sucursal/buscarcatdestino.php', 600, 500, 'ventana', 'Busqueda')"/></span><span class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="origenb" class="Tablas" type="text" id="origenb" style="background:#FFFF99;width:250px" value="<?=$origenb ?>" readonly="" />
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Sucursal:</td>
          <td>
            <input name="sucursal" class="Tablas" type="text" id="sucursal" style="width:80px" value="<?=$sucursal ?>" onkeypress="if(event.keyCode==13){ obtenerSucursalEnter(this.value);}"/>          </td>
          <td colspan="3"><span class="Tablas"><img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Sucursal" onclick="abrirVentanaFija('catalogosrutas_Buscar.php?tipo=2', 600, 500, 'ventana', 'Busqueda')" /></span><span class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="sucursalb" class="Tablas" type="text" id="sucursalb" style="background:#FFFF99;width:250px" value="<?=$sucursalb ?>" readonly="" />
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="71%"><label>
                <input name="rtipo" type="radio" onclick="inhabilita();" value='1' checked="checked" />
                Origen
                <input type="radio"  name="rtipo"   value='2' onclick="inhabilita();" />
                Intermedio
                <input type="radio" name="rtipo"  value='3' onclick="inhabilita();" />
                Destino</label>
                  <input name="transbordo" type="checkbox" id="transbordo" disabled="disabled"   value="0" onclick="if(document.all.transbordo.checked==true){document.all.transbordo.value=1;document.all.sucursalestransbordo.style.visibility='visible';limpiarSucursalCampo();}else{document.all.transbordo.value=0;document.all.sucursalestransbordo.style.visibility='hidden';limpiarSucursalCampo()}" />
                  <label id="transbordolabel" >Transbordo</label></td>
              <td width="29%" rowspan="4"><table id="sucursalestransbordo" width="177" border="0" align="right" cellpadding="0" cellspacing="0" style="visibility:hidden" >
                  <tr>
                    <td width="7" height="16"   class="formato_columnas_izq"></td>
                    <td width="169"class="formato_columnas" align="center"><div align="center">SUCURSALES </div></td>
                    <td width="1"class="formato_columnas_der"></td>
                  </tr>
                  <tr>
                    <td colspan="12"><table width="177" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="12" class="Tablas"><input name="todas2" type="checkbox" id="todas2" onclick="agregarTodasSucursales2();"/>
                            <select class="Tablas" name="sucursalesead12" id="sucursalesead12" style="width:100px" onchange="insertarServicio2(this, this.value, document.all.sucursalesead1_sel2, 'La Sucursal', 'SUCONVENIO')" />
                      <option value="" selected="selected">SELECCIONAR</option>
                      <? 
					$s = "select * from catalogosucursal where id > 1 ORDER BY descripcion ASC  ";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
                      <option value="<?=$f->id?>"><?=utf8_decode($f->descripcion)?></option>
                      <?
					}
				?>
                    </select>
                    <input name="hidensucursal2" type="hidden" id="hidensucursal2" /></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="12"><div align="center">
                        <select class="Tablas" name="sucursalesead1_sel2" size="4" id="sucursalesead1_sel2" style="width:150px" ondblclick="borrarServicio2(this, 'SUCONVENIO')">
                        </select>
                    </div></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="49%"><label>Tiempo Carga </label>
                        <label>
                        <select name="cargahrs" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="cargahrs">
                          <? for($h=0;$h<=24;$h++){ ?>
                          <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                          <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                          </option>
                          <? }?>
                        </select>
                          Hrs
                          <select name="cargamin" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="select2">
                            <? for($m=0;$m<60;$m++){ ?>
                            <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                              <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                            </option>
                            <? }?>
                          </select>
                          Min </label></td>
                    <td width="51%">Tiempo Descarga
                      <select name="descargahrs" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="descargahrs" disabled="disabled">
                          <? for($h=0;$h<=24;$h++){ ?>
                          <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                          <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                          </option>
                          <? }?>
                        </select>
                      Hrs
                      <select name="descargamin" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="descargamin" disabled="disabled">
                        <? for($m=0;$m<60;$m++){ ?>
                        <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                          <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                          </option>
                        <? }?>
                      </select>
                      Min</td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="1">Tiempo Trayecto Siguiente Sucursal
                <select name="ttsshrs" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="ttsshrs">
                    <? for($h=0;$h<=24;$h++){ ?>
                    <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? }?>
                  </select>
                Hrs
                <select name="ttssmin" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="select3">
                  <? for($m=0;$m<60;$m++){ ?>
                  <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                    <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                    </option>
                  <? }?>
                </select>
                Min</td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="48%"><label>Hora Llegada</label>
                        <label>
                        <select name="hllegada" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="hllegada" disabled="disabled">
                          <? 	for($h=0;$h<=24;$h++){ ?>
                          <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                          <?= str_pad($h,2,"0",STR_PAD_LEFT);?>
                          </option>
                          <? }?>
                        </select>
                        <select name="mllegada" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="mllegada" disabled="disabled">
                          <? for($m=0;$m<60;$m++){ ?>
                          <option value="<?= str_pad($m,2,"0",STR_PAD_LEFT);?>">
                          <?= str_pad($m,2,"0",STR_PAD_LEFT);?>
                          </option>
                          <? }?>
                        </select>
                      </label></td>
                    <td width="52%">Hora Salida
                      <select name="hsalida" onkeydown="return tabular(event,this)" size="1" class="Tablas" id="hsalida">
                          <? for($h=0;$h<=24;$h++){ ?>
                          <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT);?>">
                          <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                          </option>
                          <? }?>
                        </select>
                        <select name="msalida"  size="1" class="Tablas" id="msalida">
                          <? for($m=0;$m<60;$m++){ ?>
                          <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>">
                          <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                          </option>
                          <? }?>
                      </select></td>
                  </tr>
              </table></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td colspan="6"></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="Tablas">
            <input name="modifico" type="hidden" id="modifico" size="5" />
            <input name="tipogrid" type="hidden" id="tipogrid" size="5" />
          </span></td>
          <td><span class="Tablas">
            <input name="accion" type="hidden" id="accion" size="5" />
          </span></td>
          <td><span class="Tablas">
            <input name="num" type="hidden" id="num" value="0" size="5" />
            <input name="origen" type="hidden" id="origen" size="5" />
            <input name="destino" type="hidden" id="destino" size="5" />
            <input name="hiddenrtipo" type="hidden" id="hiddenrtipo" size="5">
            <input type="hidden" name="idfila" id="idfila" size="5">
            <input type="hidden" name="idhidden" id="idhidden" />
          </span></td>
          <td>&nbsp;</td>
          <td><table width="186" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="97"><div id="d_eliminar" class="ebtn_eliminar" onclick="eliminarRuta();"></div></td>
              <td width="89" id="col_boton"><span class="Tablas"><img src="../../img/Boton_Agregari.gif" name="img_agregar" width="70" height="20" id="img_agregar" style="cursor:pointer" title="Agregar" onclick="agregar();"/></span></td>
            </tr>
			
          </table></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="6"><table id="detallex" width="599" border="0" cellspacing="0" cellpadding="0">
           
          </table></td>
          </tr>
        <tr>
          <td colspan="6"><table id="sucabajo" width="266" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="9" height="16"   class="formato_columnas_izq"></td>
              <td width="250"class="formato_columnas" align="center"><div align="center">SUCURSALES </div></td>
              <td width="9"class="formato_columnas_der"></td>
            </tr>
            <tr>
              <td colspan="12"><table width="266" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td colspan="12" class="Tablas"><input name="todas" type="checkbox" id="todas" onclick="agregarTodasSucursales();" />
                      Todos
                      <select class="Tablas" name="sucursalesead1" style="width:200px" onchange="insertarServicio(this, this.value, document.all.sucursalesead1_sel, 'La Sucursal', 'SUCONVENIO')")>
                <option selected="selected" value="">SELECCIONAR SUCURSAL</option>
                <? 
					$s = "select * from catalogosucursal where id > 1 order by descripcion";
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
				?>
                <option value="<?=$f->id?>">
                <?=$f->descripcion?>
                </option>
                <?
					}
				?>
              </select>
              <input name="hidensucursal" type="hidden" id="hidensucursal" /></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="12"><select class="Tablas" name="sucursalesead1_sel" size="4" style="width:265px" ondblclick="borrarServicio(this, 'SUCONVENIO')">
              </select></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td><label></label></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><table width="154" border="0" align="right">
            <tr>
              <td width="70"><span class="Tablas"><img src="../../img/Boton_Guardar.gif" name="img_guardar" width="70" height="20" align="right" id="img_guardar" style="cursor:pointer; text-align: right;" title="Guardar" onclick="validar();"/></span></td>
              <td width="114"><span class="Tablas"><img src="../../img/Boton_Nuevo.gif" width="70" height="20" align="right" style="cursor:pointer" title="Guardar" onclick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar(\'\');', '')"/></span></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
  
  </form>
  
  </body>
</html>
