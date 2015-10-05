<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	if($_POST['accion']==""){
		$idsucursal 			= $_GET['idsucorigen'];
		$_POST[sucursalant] 	= $_GET['idsucorigen'];
		$folio_hidden 			= $_GET['folio'];
		$idsucursal2 			= $_GET['sucursal'];
		$_POST[estado_hidden] 	= $_GET['estado'];
		$fecha_hidden 			= $_GET['fecha'];
		$confirFecha 			= date("d/m/Y");
	}
		
	if($_GET[obtenerSesion]=="si"){
		$idsucursal 	= $_SESSION[IDSUCURSAL];
		$fecha_hidden 	= date("d/m/Y");
		$confirFecha 	= date("d/m/Y");
	}
	
	$s = "SELECT CONCAT(cs.prefijo,' - ',cs.descripcion,':',cs.id) AS descripcion 
		FROM catalogosucursal cs
		INNER JOIN catalogodestino cd ON cs.id = cd.sucursal
		GROUP BY cs.id";
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f=mysql_fetch_array($r)){
			$dx = "'".utf8_decode($f[0])."'".','.$dx;
		}	
		$dx=substr($dx, 0, -1);
	}
	
	$s = "SELECT IF(cd.subdestinos=1,CONCAT(cs.prefijo,' - ',cd.descripcion,':',cd.id),CONCAT(cd.descripcion,' - ',cs.prefijo,':',cd.id)) AS descripcion,
	cd.sucursal	FROM catalogodestino cd
	INNER JOIN catalogosucursal cs ON cd.sucursal=cs.id
	ORDER BY descripcion";	
	$r = mysql_query($s,$l) or die($s);
	if(mysql_num_rows($r)>0){
		while($f = mysql_fetch_array($r)){
			$desc= "'".utf8_decode($f[0])."'".','.$desc;
			$ori= "'".utf8_decode($f[0])."'".','.$ori;
		}
		$desc = "'VARIOS:0',".$desc;		
		$desc = substr($desc, 0, -1);
		//$ori  = "'VARIOS:0',".$ori;		
		$ori  = substr($ori, 0, -1);			
	}
	
	$result=mysql_query("SELECT descripcion FROM contenidos",$l);
	if(mysql_num_rows($result)>0){
		while($con=mysql_fetch_array($result)){
			$cadena= "'".utf8_encode($con[0])."'".','.$cadena; 	
		}	
		$cadena=substr($cadena, 0, -1);
	}
	
	$s = "SELECT CONCAT_WS(':',descripcion,id) AS descripcion FROM catalogodescripcion";
	$r = mysql_query($s,$l);
	if(mysql_num_rows($r)>0){
		while($f=mysql_fetch_array($r)){
			$desccrip= "'".utf8_encode($f[0])."'".','.$desccrip; 	
		}	
		$desccrip=substr($desccrip, 0, -1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />	
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script src="../javascript/moautocomplete.js"></script>
<script src="../javascript/funciones.js"></script>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ClaseTabla.js"></script>
<script type="text/javascript" src="../javascript/DataSet.js"></script>
<script>
	var u = document.all;
	var tabla1 	= new ClaseTabla();
	var v_multiple = "";
	var v_sucursal = "";
	var v_destino	= "";
	var v_origen	= "";
	var v_folioant = "";
	var v_fechaant = "";
	var mens = new ClaseMensajes();
	var combo1 = "<select name='origen' id='origen' onChange='obtenerDiaRecoleccion(this.value)' class='Tablas' style='width:130px;' onKeyPress='return tabular(event,this)'>";
	var txtOrigen = '<input name="origen" type="text" class="Tablas" id="origen" style="width:100px" value="<?=$_POST[origen] ?>" autocomplete="array:ori" onKeyPress="if(event.keyCode==13){obtenerDiaRecoleccion(document.all.origen_hidden.value);}"/>';
	mens.iniciar('../javascript',false);	
	var hr = new Date();	
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},
			{nombre:"ID", medida:2, alineacion:"right", tipo:"oculto", datos:"id"},
			{nombre:"DESCRIPCION", medida:150, alineacion:"left", datos:"descripcion"},
			{nombre:"CONTENIDO", medida:150, alineacion:"left", datos:"contenido"},
			{nombre:"PESO_TOTAL", medida:45, alineacion:"right", datos:"pesototal"},
			{nombre:"LARGO", medida:40, alineacion:"right",  datos:"largo"},
			{nombre:"ANCHO", medida:40, alineacion:"right",  datos:"ancho"},
			{nombre:"ALTO", medida:40, alineacion:"right",  datos:"alto"},
			{nombre:"PESO_UNIT", medida:4, alineacion:"right", tipo:"oculto",  datos:"pesounit"},
			{nombre:"PESO", medida:4, alineacion:"right", tipo:"oculto",  datos:"peso"},
			{nombre:"VOLUMEN", medida:40, alineacion:"right", datos:"volumen"},
			{nombre:"FECHA", medida:4, alineacion:"right", tipo:"oculto", datos:"fecha"}
		],
		filasInicial:5,
		alto:100,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		var horas = hr.getHours();
		var minutos = hr.getMinutes();		
		var ValorHora

		//establece las horas
		if (horas < 10)
				ValorHora = "0" + horas
		else
			ValorHora = "" + horas

		//establece los minutos
		if (minutos < 10)
			ValorHora += ":0" + minutos
		else
			ValorHora += ":" + minutos
		
		u.hora.value = ValorHora;
		u.d_guardar.style.visibility="visible";
		
		if(u.idsucursal.value == "1"){
			u.btnSucursal.style.visibility = "visible";
		}
		tabla1.create();				
		if(u.folio_hidden.value==""){
			u.destino.focus();
			obtenerDatos();
		}else{
			obtenerRecoleccionMercancia();
		}
		if(tabla1.getRecordCount()==0){
			u.Eliminar.style.visibility = "hidden";
		}
	}
	
	function obtenerRecoleccionMercancia(){
		consultaTexto("mostrarTodo","recoleccion_conj.php?accion=6&folio="+u.folio_hidden.value
		+"&idsucursal="+u.idsucursal2.value+"&valor="+Math.random());
	}
	
	function mostrarTodo(datos){
		if(datos.indexOf("no encontro")<0){
			var objeto = eval(convertirValoresJson(datos));
			u.folio.value			= objeto.principal.folio;
			u.estado_hidden.value	= objeto.principal.estado;
			u.colEstado.innerHTML	= objeto.principal.estado;
			u.idsucursal.value  	= objeto.principal.sucursal;
			u.sucursal.value		= objeto.principal.dessuc;
			u.sucursalant.value 	= objeto.principal.sucursal;
			u.folioant.value		= objeto.principal.folio;
			u.origen_hidden.value 	= objeto.principal.origen;
			u.fecha.value = ((objeto.principal.estado=="REALIZADO")?objeto.principal.fecharecoleccion:objeto.principal.fecharegistro);
			v_origen = objeto.principal.origen;
			v_fechaant = objeto.principal.fecharegistro;
			if(u.origen_hidden.value==0){
				u.origen.value		= "VARIOS";
			}else{
				u.origen.value		= objeto.principal.desori;
			}	
			
			u.destino_hidden.value = objeto.principal.destino;
			v_destino = objeto.principal.destino;
			if(u.destino_hidden.value==0){
				u.destino.value		= "VARIOS";
			}else{
				u.destino.value		= objeto.principal.desdes;
			}	
			u.npedidos.value	= objeto.principal.npedidos;
			u.dirigido.value	= objeto.principal.dirigido;
			
			u.chNombre.checked 	= ((objeto.principal.chnombre==1)?true:false);
			if(u.chNombre.checked==true){
				u.llama.disabled = false;
				u.llama.style.backgroundColor='';
				u.telefono.disabled = false;
				u.telefono.style.backgroundColor='';
				u.comentarios.disabled = false;
				u.comentarios.style.backgroundColor='';
			}			
			u.llama.value		= objeto.principal.llama;
			u.telefono.value	= objeto.principal.telefono;
			u.comentarios.value	= objeto.principal.comentarios;
			u.cliente.value		= objeto.principal.cliente;
			u.nombre.value		= objeto.principal.ncliente;
			u.calle.value		= objeto.principal.calle;
			u.numero.value		= objeto.principal.numero;
			u.crucecalles.value	= objeto.principal.crucecalles;
			u.cp.value			= objeto.principal.cp;
			u.colonia.value		= objeto.principal.colonia;
			u.poblacion.value	= objeto.principal.poblacion;
			u.municipio.value	= objeto.principal.municipio;
			u.telefono2.value	= objeto.principal.telefono2;
			
			u.sector.value		= objeto.principal.sector;
			u.unidad.value		= objeto.principal.unidad;
			v_multiple			= objeto.principal.multiple;
			
			u.h1.value			= objeto.principal.horario;
			u.c1.value			= objeto.principal.hrcomida;
						
			if(objeto.principal.estado=="NO TRANSMITIDO"){
				u.accion.value	= "modificar";
			}
			
			if(objeto.principal.estado=="REALIZADO" || objeto.principal.estado=="TRANSMITIDO"){
				u.destino.readOnly = true;
				u.origen.readOnly = true;
			}
			if(objeto.principal.estado=="REALIZADO"){
				u.multiple.checked = ((objeto.principal.multiple==1)?true:false);
				if(objeto.recoleccion[0] != undefined){					
					u.d_agregarRec.style.visibility = "hidden";
					agregarValores(u.recolecciones,objeto.recoleccion);
					u.recolecciones.disabled = true;
				}
				if(objeto.empresarial[0] != undefined){
					u.d_agregarEmp.style.visibility = "hidden";
					agregarValores(u.empresarial,objeto.empresarial);
					u.empresarial.disabled = true;
				}
			}
			
			tabla1.setJsonData(objeto.detalle);
			validarEstados();
		}
	}
	
	function agregarValores(combo,objeto){
		combo.options.length = 0;
		var opcion;
		for(var i=0; i<objeto.length; i++){
			opcion = new Option(objeto[i].folio);
			combo.options[combo.options.length] = opcion;
		}
	}
	
	function validar(){
		if(u.origen.value==""){
			mens.show('A','Debe capturar Origen','¡Atención!','origen');
			return false;
		}
		if(u.destino_hidden.value == undefined || u.destino.value==""){
			mens.show('A','Debe capturar Destino','¡Atención!','destino');
			return false;
		}
		if(tabla1.getRecordCount()==0){
			mens.show('A','Debe capturar por lo menos una Mercancia al detalle','¡Atención!');
			return false;
		}
		if(u.chNombre.checked == true){
			if(u.llama.value == ""){
				mens.show('A','Debe capturar Nombre de quien llama','¡Atención!','llama');
				return false;
			}else if(u.telefono.value == ""){
				mens.show('A','Debe capturar Telefono','¡Atención!','telefono');
				return false;
			}else if(u.comentarios.value == ""){
				mens.show('A','Debe capturar Comentarios','¡Atención!','comentarios');
				return false;
			}		
		}
		if(u.cliente.value==""){
			mens.show('A','Debe capturar Cliente','¡Atención!','cliente');
			return false;
		}
		if(u.h1.value =="00:00"){
			mens.show('A','Debe capturar Horario','¡Atención!','h1');
			return false;
		}
		
		if(u.accion.value == ""){
			u.destino_hidden.value	  = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
			u.origen_hidden.value	  = ((u.origen_hidden.value=="no")?v_origen:u.origen_hidden.value);
			u.registroMercancia.value = tabla1.getRecordCount();			
			u.horario_hidden.value    = u.h1.value;
			u.horario2_hidden.value   = u.h1.value;
			u.comida_hidden.value     = u.c1.value;
			u.comida2_hidden.value    = u.c1.value;
			u.estado_hidden.value	  = "NO TRANSMITIDO";
			u.accion.value 		      = "grabar";
			u.d_guardar.style.visibility = "hidden";
			consultaTexto("registro","recoleccion_consultas.php?accion=1&idsucursal="+u.idsucursal.value+"&folio="+u.folio.value
			+"&fecha="+u.fecha.value+"&estado_hidden="+u.estado_hidden.value+"&origen_hidden="+u.origen_hidden.value
			+"&destino_hidden="+u.destino_hidden.value
			+"&npedidos="+u.npedidos.value+"&dirigido="+u.dirigido.value
			+"&chNombre="+((u.chNombre.checked==true)?1:0)
			+"&llama="+u.llama.value+"&telefono="+u.telefono.value+"&comentarios="+u.comentarios.value
			+"&cliente="+u.cliente.value+"&calle="+u.calle.value+"&numero="+u.numero.value
			+"&crucecalles="+u.crucecalles.value+"&cp="+u.cp.value+"&colonia="+u.colonia.value
			+"&poblacion="+u.poblacion.value+"&municipio="+u.municipio.value+"&telefono2="+u.telefono2.value
			+"&sector="+u.sector.value+"&horario_hidden="+u.horario_hidden.value
			+"&horario2_hidden="+u.horario2_hidden.value+"&comida_hidden="+u.comida_hidden.value
			+"&comida2_hidden="+u.comida2_hidden.value+"&unidad="+u.unidad.value+"&tip=grabar&val="+Math.random());

		}else if(u.accion.value == "modificar"){
			u.d_guardar.style.visibility = "hidden";
			u.destino_hidden.value	  = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
			u.origen_hidden.value	  = ((u.origen_hidden.value=="no")?v_origen:u.origen_hidden.value);
			u.registroMercancia.value = tabla1.getRecordCount();
			u.horario_hidden.value    = u.h1.value;
			u.horario2_hidden.value   = u.h1.value;
			u.comida_hidden.value     = u.c1.value;
			u.comida2_hidden.value    = u.c1.value;
			u.estado_hidden.value	  = "NO TRANSMITIDO";
			u.accion.value 			  = "modificar";
			consultaTexto("modifico","recoleccion_consultas.php?accion=1&idsucursal="+u.idsucursal.value
			+"&fecha="+u.fecha.value+"&estado_hidden="+u.estado_hidden.value+"&origen_hidden="+u.origen_hidden.value
			+"&destino_hidden="+u.destino_hidden.value+"&folio="+u.folio.value
			+"&npedidos="+u.npedidos.value+"&dirigido="+u.dirigido.value
			+"&chNombre="+((u.chNombre.checked==true)?1:0)
			+"&llama="+u.llama.value+"&telefono="+u.telefono.value+"&comentarios="+u.comentarios.value
			+"&cliente="+u.cliente.value+"&calle="+u.calle.value+"&numero="+u.numero.value
			+"&crucecalles="+u.crucecalles.value+"&cp="+u.cp.value+"&colonia="+u.colonia.value
			+"&poblacion="+u.poblacion.value+"&municipio="+u.municipio.value+"&telefono2="+u.telefono2.value
			+"&sector="+u.sector.value+"&horario_hidden="+u.horario_hidden.value
			+"&horario2_hidden="+u.horario2_hidden.value+"&comida_hidden="+u.comida_hidden.value
			+"&comida2_hidden="+u.comida2_hidden.value+"&unidad="+u.unidad.value
			+"&tip=modif&sucursalant="+u.sucursalant.value+"&folioant="+u.folioant.value+"&val="+Math.random());
		}
	}
	
	function registro(datos){
		if(datos.indexOf("guardo")>-1){
			var row = datos.split(",");
			u.folio.value = row[1];
			u.d_guardar.style.visibility = "visible";
			u.colEstado.innerHTML = "NO TRANSMITIDO";
			mens.show("I","Los datos han sido guardados correctamente","");
			//cambiarPagina();
		}else{
			u.d_guardar.style.visibility = "visible";
			mens.show("A","Hubo un error al registrar "+datos,"¡Atención!");
		}
	}
	
	function modifico(datos){
		if(datos.indexOf("modifico")>-1){
			var row = datos.split(",");
			if(row[1] != u.folio.value && row[1] != undefined){
				u.folio.value = row[1];
			}
			u.colEstado.innerHTML = "NO TRANSMITIDO";
			u.d_guardar.style.visibility = "visible";
			mens.show("I","Los cambios han sido guardados correctamente","");
			cambiarPagina();
		}else{
			u.d_guardar.style.visibility = "visible";
			mens.show("A","Hubo un error al registrar "+datos,"¡Atención!");
		}
	}
	
	function transmitir(){
		if(u.unidad.value==""){
			mens.show('A','Debe capturar Unidad','¡Atención!','unidad');
			return false;
		}else{
			u.estado_hidden.value = "TRANSMITIDO";
			consultaTexto("transmitio","recoleccion_consultas.php?accion=2&idsucursal="+u.idsucursal.value
			+"&sucursalant="+u.sucursalant.value+"&unidad="+u.unidad.value+"&folio="+u.folio.value
			+"&folioant="+u.folioant.value+"&fechahora="+fechahora()+"&val="+Math.random());
		}
	}
	
	function transmitio(datos){
		if(datos.indexOf("transmitio")>-1){
			u.colEstado.innerHTML = "TRANSMITIDO";
			mens.show("I","La Recoleccion cambio a estado TRANSMITIDO correctamente","");
			validarEstados();
		}else{
			mens.show("A","Hubo un error al transmitir "+datos,"¡Atención!");
		}
	}
	
	function realizar(){		
		if(u.multiple.checked==true || u.multiple.checked==false){
			if(u.recolecciones.options.length == 0 && u.empresarial.options.length == 0){
				mens.show('A','Debe capturar al menos un Folio de Recolección o una Guía Empresarial','¡Atención!');
				return false;
			}
		}
		u.destino_hidden.value	= ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
		u.origen_hidden.value	= ((u.origen_hidden.value=="no")?v_origen:u.origen_hidden.value);
		u.estado_hidden.value = "REALIZADO";
		var v_folios = ((u.recolecciones_hidden.value != "")? u.recolecciones_hidden.value.substr(0,u.recolecciones_hidden.value.length-1) : "");
		consultaTexto("realizo","recoleccion_consultas.php?accion=3&multiple="+((u.multiple.checked==true)?1:0)
		+"&folio="+u.folio.value+"&idsucursal="+u.idsucursal.value+"&fechahora="+fechahora()+"&folios="+v_folios+"&val="+Math.random());	
		
	}
	
	function realizo(datos){
		if(datos.indexOf("realizo")>-1){
			u.colEstado.innerHTML = "REALIZADO";
			mens.show("I","La Recolección cambio a estado REALIZADO correctamente","");
			validarEstados();
		}else{
			mens.show("A","Hubo un error al realizar "+datos,"¡Atención!");
		}
	}
	
	function confirmarCancelar(){	
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
		
		if(initDate < endDate){
			mens.show("A","Solo se pueden cancelar recolecciones del dia actual","¡Atención!");
			return false;
		}
		
		abrirVentanaFija('motivosCancelacion.php?motivo='+u.motivo.value+'&notificacion='+u.notificaciones.value, 525, 418, 'ventana', 'Busqueda');
	}
	function cancelar(id,motivo,notificacion){
		u.destino_hidden.value = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
		u.origen_hidden.value	  = ((u.origen_hidden.value=="no")?v_origen:u.origen_hidden.value);
		u.estado_hidden.value  = "CANCELADO";
		u.motivo.value = id;
		u.desmotivo.value = motivo;
		u.notificaciones.value = notificacion;
		
		consultaTexto("cancelo","recoleccion_consultas.php?accion=5&folio="+u.folio.value
		+"&idsucursal="+u.idsucursal.value+"&fecha="+u.fecha.value
		+"&motivo="+u.motivo.value+"&notificaciones="+u.notificaciones.value+"&val="+Math.random());
	}
	
	function cancelo(datos){
		if(datos.indexOf("cancelo")>-1){
			u.colEstado.innerHTML = "CANCELADO";
			mens.show("I","La Recolección cambio a estado CANCELADO correctamente","");
			validarEstados();
		}else{
			mens.show("A","Hubo un error al cancelar "+datos,"¡Atención!");
		}
	}
	
	function confirmarReprogramacion(){
		var fi = u.fecha.value.split("/");
		var ff = u.confirFecha.value.split("/");		
		
		if(fi[0].substr(0,1)=="0"){
			fi[0] = fi[0].substr(1,1);
		}
		if(fi[1].substr(0,1)=="0"){
			fi[1] = fi[1].substr(1,1);
		}
		
		if(ff[0].substr(0,1)=="0"){
			ff[0] = ff[0].substr(1,1);
		}
		if(ff[1].substr(0,1)=="0"){
			ff[1] = ff[1].substr(1,1);
		}
		
		initDate = new Date(fi[2],fi[1],fi[0]);
		endDate = new Date(ff[2],ff[1],ff[0]);
		
		/*if(initDate <= endDate){
			mens.show('A','Debe capturar una fecha mayor a la actual','¡Atención!');
			return false;
		}else{*/
		abrirVentanaFija("motivosReprogramacion.php?motivo="+u.motivoreprogramar.value+"&notificacion="+u.notificacionesreprogramar.value+"&observaciones="+u.observacionesreprogramar.value, 525, 418, "ventana", "Busqueda");
		//}
	}
	function Reprogramacion(id,motivo,notificacion,observaciones){
		u.destino_hidden.value	  = ((u.destino_hidden.value=="no")?v_destino:u.destino_hidden.value);
		u.origen_hidden.value	  = ((u.origen_hidden.value=="no")?v_origen:u.origen_hidden.value);
		u.estado_hidden.value = "NO TRANSMITIDO";
		u.motivoreprogramar.value = id;
		u.desmotivoreprogramar.value = motivo;
		u.notificacionesreprogramar.value = notificacion;
		u.observacionesreprogramar.value  = observaciones;
		
		consultaTexto("reprogramo","recoleccion_consultas.php?accion=4&folio="+u.folio.value
		+"&idsucursal="+u.idsucursal.value+"&fecha="+u.fecha.value+"&folioant="+v_folioant
		+"&motivoreprogramar="+u.motivoreprogramar.value+"&notificarreprogramar="+u.notificacionesreprogramar.value
		+"&observacionesreprogramar="+u.observacionesreprogramar.value+"&val="+Math.random());
	}
	
	function reprogramo(datos){
		if(datos.indexOf("reprogramo")>-1){
			u.colEstado.innerHTML = "NO TRANSMITIDO";
			mens.show("I","La Recolección cambio a estado NO TRANSMITIDO correctamente","");
			u.abajo.style.display = "none";
			validarEstados();
		}else{
			mens.show("A","Hubo un error al reprogramar "+datos,"¡Atención!");
		}
	}
	
	function cambiarPagina(){		
		setTimeout("cambiarPaginaOut()",2000);
	}
	
	function cambiarPaginaOut(){
		document.location.href='recoleccionMercancia.php';
	}
	
	function obtenerDatos(){
		//window.open("recoleccion_consultas.php?accion=0&sucursal="+u.idsucursal.value+"&valor="+Math.random());
		consultaTexto("mostrarDatos","recoleccion_consultas.php?accion=0&sucursal="+u.idsucursal.value+"&valor="+Math.random());
	}
	function mostrarDatos(datos){
		try{
			var obj = eval(convertirValoresJson(datos));
		}catch(e){
			mens.show("A",datos);
		}
		u.sucursal.value	= obj.principal.descripcion;		
		u.fecha.value   	= obj.principal.fecha;
		u.destino_h.value 	= obj.principal.id;
		u.folio.value		= obj.principal.folio;
		if(obj.origen.length==1){
			u.celOrigen.innerHTML 	= txtOrigen;
			u.origen_hidden.value	= obj.origen[0].id;
			u.origen.value			= obj.origen[0].destino;
		}else{
			u.celOrigen.innerHTML = combo1;
			var combo = u.origen;
			combo.options.length = null;
			uOpcion = document.createElement("OPTION");
			uOpcion.value="";
			uOpcion.text="SELECCIONAR";			
			combo.add(uOpcion);			
			for(i=0;i<obj.origen.length;i++){	
				uOpcion = document.createElement("OPTION");
				uOpcion.value	=	obj.origen[i].id;
				uOpcion.text	=	obj.origen[i].destino;
				combo.add(uOpcion);
			}
			combo.value = obj.origenes.id;
			u.origen_hidden.value = obj.origenes.id;		
		}
		
		var v_horario 		= obj.horarios.horariolimite;
		var v_fecha 		= obj.horarios.fechasig;		
		if(v_horario < u.hora.value){
			mens.show("I","El limite de recoleccion es "+v_horario+", por lo tanto el folio de recoleccion se generara con la fecha del siguiente dia habil.","");
			u.fecha.value	= v_fecha;
			obtenerFolioxFecha(u.fecha.value);
		}
		
		var fi = u.fecha_hidden.value.split("/");
		var ff = u.fecha.value.split("/");
		if(fi[0].substr(0,1)=="0"){
			fi[0] = fi[0].substr(1,1);
		}
		if(fi[1].substr(0,1)=="0"){
			fi[1] = fi[1].substr(1,1);
		}
		
		if(ff[0].substr(0,1)=="0"){
			ff[0] = ff[0].substr(1,1);
		}
		if(ff[1].substr(0,1)=="0"){
			ff[1] = ff[1].substr(1,1);
		}
		
		initDate = new Date(fi[2],fi[1],fi[0]);
		endDate = new Date(ff[2],ff[1],ff[0]);
		
		if(initDate > endDate){
			u.fecha.value = u.fecha_hidden.value;
			obtenerFolioxFecha(u.fecha.value);
		}
	}
	
	function obtenerFolioxFecha(fecha){		
		var fi = fecha.split("/");
		var ff = u.confirFecha.value.split("/");
		
		if(fi[0].substr(0,1)=="0"){
			fi[0] = fi[0].substr(1,1);
		}
		if(fi[1].substr(0,1)=="0"){
			fi[1] = fi[1].substr(1,1);
		}
		
		if(ff[0].substr(0,1)=="0"){
			ff[0] = ff[0].substr(1,1);
		}
		if(ff[1].substr(0,1)=="0"){
			ff[1] = ff[1].substr(1,1);
		}
		
		initDate = new Date(fi[2],fi[1],fi[0]);
		endDate = new Date(ff[2],ff[1],ff[0]);
	
		/*if(initDate < endDate){
			mens.show('A','La Fecha no debe ser menor a la actual','¡Atención!');
			u.fecha.value	= u.confirFecha.value;
			return false;
		}*/
		//window.open("recoleccion_conj.php?accion=13&idsucursal="+u.idsucursal.value+"&fecha="+fecha+"&valor="+Math.random());
		consultaTexto("obtenerFolio","recoleccion_conj.php?accion=13&idsucursal="+u.idsucursal.value+"&fecha="+fecha+"&valor="+Math.random());
	}
	
	function obtenerFolio(datos){	
		v_folioant = u.folio.value;	
		u.folio.value = datos;
	}	
	
	function agregarDatos(objeto){
		if(u.index.value!=""){
			tabla1.deleteById(tabla1.getSelectedIdRow());
			tabla1.add(objeto);
			u.index.value	= "";			
		}else{
			tabla1.add(objeto);
			u.Eliminar.style.visibility ="visible";
		}		
	}
	
	function ModificarFila(){
		if(u.colEstado.innerHTML=="NO TRANSMITIDO" || u.colEstado.innerHTML==""){
			var obj = tabla1.getSelectedRow();		
			if(tabla1.getValSelFromField("cantidad","CANT")!=""){
			u.index.value	= tabla1.getSelectedIndex();
			abrirVentanaFija('datosMercanciaRecoleccion.php?funcion=agregarDatos&cantidad='+obj.cantidad
			+'&id='+obj.id
			+'&descripcion='+obj.descripcion
			+'&contenido='+obj.contenido
			+'&peso='+obj.peso
			+'&largo='+obj.largo
			+'&ancho='+obj.ancho
			+'&alto='+obj.alto
			+'&pesototal='+obj.pesototal
			+'&pesounit='+obj.pesounit
			+'&fechahora='+obj.fecha
			+'&volumen='+obj.volumen+'&esmodificar=si', 460, 410, 'ventana', 'Datos Mercancia','ponerFoco();');
			}
		}
	}
	
	function eliminarFila(){
		if(tabla1.getValSelFromField('cantidad','CANT')!=""){
			mens.show('C','¿Esta seguro de Eliminar la Fila seleccionada?','','','borrarFila()');
		}		
	}
	function borrarFila(){
		tabla1.deleteById(tabla1.getSelectedIdRow());
		if(tabla1.getRecordCount()==0){		
		  u.Eliminar.style.visibility = "hidden";		
		}
	}
	
	function devolverCliente(valor){		
		limpiarCliente();
		u.cliente.value = valor;
		consulta("mostrarCliente","recoleccion_con.php?accion=1&cliente="+valor+"&valor="+Math.random());
	}
	
	function obtenerClienteBusqueda(id){
		u.cliente.value = id;
		//consulta("mostrarCliente","recoleccion_con.php?accion=1&cliente="+id+"&valor="+Math.random());
		consultaTexto("mostrarCliente","../guias/guia_consultajson.php?accion=2&idcliente="+id+"&valrandom="+Math.random());
	}
	
	function obtenerCliente(e,id){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
			consultaTexto("mostrarCliente","../guias/guia_consultajson.php?accion=2&idcliente="+id+"&valrandom="+Math.random());
			//consulta("mostrarCliente","recoleccion_con.php?accion=1&cliente="+id+"&valor="+Math.random());
		}
	}
	
	function mostrarCliente(datos){
		limpiarCliente();
		try{
			var dcliente = eval(datos);
		}catch(e){
			alerta3(datos);
		}
		var u = document.all;
		if(dcliente.cliente!="0"){
			u.nombre.value	= dcliente.cliente.ncliente;
			var endir = dcliente.direcciones.length;
			if(endir==1){
				u.calle.value 		= dcliente.direcciones[0].calle;
				u.numero.value 		= dcliente.direcciones[0].numero;
				u.cp.value 			= dcliente.direcciones[0].codigopostal;
				u.colonia.value 	= dcliente.direcciones[0].colonia;
				u.poblacion.value 	= dcliente.direcciones[0].poblacion;
				u.telefono2.value 	= dcliente.direcciones[0].telefono;
				u.municipio.value 	= dcliente.direcciones[0].municipio;
				if(dcliente.direcciones[0].crucecalles!='null');
					u.crucecalles.value = dcliente.direcciones[0].crucecalles;
				cambiarSector(dcliente.direcciones[0].codigopostal,dcliente.direcciones[0].colonia);
			}else if(endir>1){
				dirRemi = dcliente.direcciones;
				mostrarDirecciones(dirRemi,u.cliente.value);
				consultaTexto("mostrarHorario","../recoleccion/recoleccion_conj.php?accion=12&cliente="+u.cliente.value+"&valor="+Math.random());
			}			
		}else{			
			alerta('El numero de cliente no existe','¡Atención!','cliente');			
		}
	}
	
	function mostrarClienteResp(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		limpiarCliente();
		if(con > 0){
			u.nombre.value	= datos.getElementsByTagName('nombre').item(0).firstChild.data;
			var endir = datos.getElementsByTagName('dir').item(0).firstChild.data;
		if(endir==1){
		u.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$_POST[calle] ?>" readonly=""/>';
		u.numero.value =datos.getElementsByTagName('numero').item(0).firstChild.data;
		u.cp.value =datos.getElementsByTagName('cp').item(0).firstChild.data;
		u.colonia.value =datos.getElementsByTagName('colonia').item(0).firstChild.data;
		u.poblacion.value =datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.crucecalles.value =datos.getElementsByTagName('crucecalles').item(0).firstChild.data;
		u.telefono2.value =datos.getElementsByTagName('telefono').item(0).firstChild.data;
		u.calle.value =datos.getElementsByTagName('calle').item(0).firstChild.data;
			}else if(endir>1){
			var comb = "<select name='calle' class='Tablas' style='width:280px;font:tahoma; font-size:9px' onchange='"
			+"document.all.numero.value=this.options[this.selectedIndex].numero;"
			+"document.all.cp.value=this.options[this.selectedIndex].cp;"
			+"document.all.colonia.value=this.options[this.selectedIndex].colonia;"
			+"document.all.poblacion.value=this.options[this.selectedIndex].poblacion;"
			+"document.all.municipio.value=this.options[this.selectedIndex].municipio;"
			+"document.all.crucecalles.value=this.options[this.selectedIndex].crucecalles;"
			+"document.all.telefono2.value=this.options[this.selectedIndex].telefono;"
			+" cambiarSector(document.all.cp.value,document.all.colonia.value);'>";
				
				for(var i=0; i<endir; i++){
			v_calle 		= datos.getElementsByTagName('calle').item(i).firstChild.data;
			v_numero		= datos.getElementsByTagName('numero').item(i).firstChild.data;
			v_cp 			= datos.getElementsByTagName('cp').item(i).firstChild.data;
			v_colonia		= datos.getElementsByTagName('colonia').item(i).firstChild.data;
			v_poblacion 	= datos.getElementsByTagName('poblacion').item(i).firstChild.data;
			v_municipio 	= datos.getElementsByTagName('municipio').item(i).firstChild.data;
			v_cruce			= datos.getElementsByTagName('crucecalles').item(i).firstChild.data;
			v_telefono 		= datos.getElementsByTagName('telefono').item(i).firstChild.data;
			v_fact			= datos.getElementsByTagName('facturacion').item(i).firstChild.data;

		
					if(i==0){						
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;
						u.municipio.value 	= v_municipio;
						u.crucecalles.value	= v_cruce;
						u.telefono2.value 	= v_telefono;					
					}else if(v_fact=="SI"){
						u.numero.value 		= v_numero;
						u.cp.value 			= v_cp;
						u.colonia.value 	= v_colonia;
						u.poblacion.value 	= v_poblacion;						
						u.municipio.value 	= v_municipio;
						u.crucecalles.value	= v_cruce;
						u.telefono2.value 	= v_telefono;
					}
					
					comb += "<option "+ ((v_fact=="SI")? "selected " : "" ) +" value='"+v_calle+"' numero='"+v_numero+"'" 
					+"cp='"+v_cp+"' colonia='"+v_colonia+"'"
					+" poblacion='"+v_poblacion+"' telefono='"+v_telefono+"'"
					+" municipio='"+v_municipio+"' crucecalles='"+v_cruce+"'"
					+" telefono='"+v_telefono+"'>"
					+v_calle+"</option>";					
				}
				comb += "</select>";
				u.celda_des_calle.innerHTML = comb;
			}
			cambiarSector(u.cp.value,u.colonia.value);
			consultaTexto("mostrarHorario","recoleccion_conj.php?accion=12&cliente="+u.cliente.value+"&valor="+Math.random());
			u.h1.focus();
		}else{			
			mens.show('A','El numero de cliente no existe','¡Atención!','cliente');			
		}
	}
	function mostrarHorario(datos){
		if(datos.indexOf("no encontro")<0){
			var objeto = eval(datos);
			u.h1.value = objeto[0].horario;
			u.c1.value = objeto[0].hrcomida;
			
			if(u.h1.value=="00:00"){
				u.h1.focus();
			}else{
				u.unidad.select();
			}
		}else{
			u.h1.focus();
		}
	}
	
	function cambiarSector(cp,colonia){
		consultaTexto("mostrarSector","recoleccion_conj.php?accion=4&cp="+cp+"&col="+colonia+"&val="+Math.random());
	}
	
	function mostrarSector(datos){		
		if(datos.indexOf("no encontro")<0){
			var objeto = eval(convertirValoresJson(datos));
			u.idsector.value = objeto[0].id;
			u.sector.value   = objeto[0].descripcion;
		}else{
			u.idsector.value = "";
			u.sector.value   = "";
		}
	}
	
	function limpiarCliente(){
		u.numero.value 		= ""; u.cp.value 			= "";
		u.colonia.value 	= ""; u.poblacion.value 	= "";
		u.municipio.value 	= ""; u.crucecalles.value	= ""; 
		u.telefono2.value 	= ""; u.sector.value		= "";
		u.h1.value			= "00:00"; 
		u.c1.value			= "00:00";			
		document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:165px;background:#FFFF99" value="<?=$_POST[calle] ?>" readonly=""/>';
	}
	
	function validarEstados(){
		if(u.estado_hidden.value=="NO TRANSMITIDO"){		
			u.d_transmitir.style.visibility="visible";
			u.d_cancelado.style.visibility="visible";
			u.d_reprogramado.style.visibility="hidden";
			u.d_realizado.style.visibility="hidden";
		
		}else if(u.estado_hidden.value=="TRANSMITIDO"){			
			u.abajo.style.display="";
			u.d_transmitir.style.visibility="hidden";
			u.d_guardar.style.visibility="hidden";
			u.d_realizado.style.visibility="visible";
			u.d_cancelado.style.visibility="visible";
			u.d_reprogramado.style.visibility="visible";
			u.destino.disabled = true;
			u.origen.disabled = true;
			u.empresarial.disabled = true;
			u.recolecciones.disabled = true;
			
		}else if(u.estado_hidden.value=="REPROGRAMADA"){
			u.d_transmitir.style.visibility="visible";
			u.d_cancelado.style.visibility="visible";
			u.d_reprogramado.style.visibility="hidden";
			
		}else if(u.estado_hidden.value=="REALIZADO"){			
			u.abajo.style.display="";
			u.d_transmitir.style.visibility="hidden"
			u.d_guardar.style.visibility="hidden";
			u.d_realizado.style.visibility="hidden";
			u.d_cancelado.style.visibility="hidden";
			u.d_reprogramado.style.visibility="hidden";
			u.d_nuevo.style.visibility="visible";
			u.destino.disabled = true;
			u.origen.disabled = true;
			u.empresarial.disabled = true;
			u.recolecciones.disabled = true;
			
		}else if(u.estado_hidden.value=="CANCELADO"){			
			u.abajo.style.display="none";
			u.d_transmitir.style.visibility="hidden"
			u.d_guardar.style.visibility="hidden";
			u.d_realizado.style.visibility="hidden";
			u.d_cancelado.style.visibility="hidden";
			u.d_reprogramado.style.visibility="hidden";
			u.d_nuevo.style.visibility="visible";	
		}		 
	}
	function ponerFoco(){
		u.npedidos.focus();
	}
	
	function habilitarTerceros(){
		if(u.chNombre.checked == true){
			u.llama.disabled = false;
			u.llama.style.backgroundColor='';
			u.telefono.disabled = false;
			u.telefono.style.backgroundColor='';
			u.comentarios.disabled = false;
			u.comentarios.style.backgroundColor='';
			u.llama.focus();
		}else{
			u.llama.value = "";
			u.llama.disabled = true;
			u.llama.style.backgroundColor='#FFFF99';
			u.telefono.value = "";
			u.telefono.disabled = true;
			u.telefono.style.backgroundColor='#FFFF99';
			u.comentarios.value = "";
			u.comentarios.disabled = true;
			u.comentarios.style.backgroundColor='#FFFF99';			
		}
	}
	
	function obtenerUnidadBusqueda(id){
		u.unidad.value = id;		
	}
	
	function obtenerUnidad(e,id){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla == 13 && id!=""){
			consultaTexto("mostrarUnidad","recoleccion_conj.php?accion=1&unidad="+id
			+"&sucursal="+u.idsucursal.value+"&valor="+Math.random());
		}
	}
	
	function mostrarUnidad(datos){		
		if(datos.indexOf("no encontro")>-1){
			u.unidad.value = "";
			mens.show('A','El numero de Unidad no existe','¡Atención!','unidad');
		}
	}
	
	function validarFolioRecoleccion(caja, valor, va){		
		consultaTexto("insertarRecoleccion","recoleccion_consultas.php?accion=8&tipo=guardar&foliorecoleccion="+valor
		+"&caja="+caja.name+"&va="+va.name+"&val="+Math.random());
	}
	
	function insertarRecoleccion(datos){		
		if(datos.indexOf("no existe")>-1){
			mens.show("A","El Folio de Recolección no existe","¡Atención!","folior");
			u.d_agregarRec.style.visibility = "visible";
			return false;
		}
	
		if(datos.indexOf("utilizado")>-1){
			mens.show("A","El Folio de Recolección ya fue utilizado","¡Atención!","folior");
			u.d_agregarRec.style.visibility = "visible";
			return false;
		}
		
		if(datos.indexOf("agregado")>-1){
			mens.show("A","El Folio de Recolección ya fue registrado en otra solicitud de recoleccion","¡Atención!","folior");
			u.d_agregarRec.style.visibility = "visible";
			return false;
		}
		
		var r = datos.split(",");
		
		var caja = document.all[r[1]]; var valor = r[2]; var va = document.all[r[3]];
		
		u.d_agregarRec.style.visibility = "hidden";
		if(caja.value!=""){
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].text==valor){
					mens.show("A","El folio "+ caja.value+"  ya fue agregado","¡Atencion!");
					caja.value="";
					u.d_agregarRec.style.visibility = "visible";
					return false;
				}
			}
			var opcion = new Option(caja.value);
			va.options[va.options.length] = opcion;
			u.recolecciones_hidden.value += caja.value+",";
			var valor = caja.value;
			caja.value="";
			u.d_agregarRec.style.visibility = "visible";
		}
	}
	function borrarRecoleccion(va){
		var valor = u.recolecciones.options[u.recolecciones.selectedIndex].text;
		consultaTexto("eliminoRecoleccion","recoleccion_consultas.php?accion=8&tipo=borrar&foliorecoleccion="+valor+"&va="+va.name+"&val="+Math.random());
	}
	
	function eliminoRecoleccion(datos){
		if(datos.indexOf("ok")>-1){
			var r = datos.split(",");
			var va = document.all[r[1]];
			
			if(va.options.selectedIndex>-1){
				var frase = u.recolecciones_hidden.value.replace(u.recolecciones.options[u.recolecciones.selectedIndex].text,"");
				u.recolecciones_hidden.value = frase.replace(",,",",");
				if(u.recolecciones_hidden.value.substring(0,1)==","){
					u.recolecciones_hidden.value = u.recolecciones_hidden.value.substring(1,u.recolecciones_hidden.value.legth);
				}		
				var valor = u.recolecciones.options[u.recolecciones.selectedIndex].text;
				va.options[va.options.selectedIndex] = null;
				va.value = "";				
			}
		}		
	}
	
	function insertarEmpresarial(caja, valor, va){
		u.d_agregarEmp.style.visibility = "hidden";
		if(caja.value!=""){
			for(var i=0; i<va.options.length; i++){
				if(va.options[i].text==valor){
					mens.show("A","El folio "+ caja.value+"  ya fue agregado","¡Atencion!");
					caja.value="";
					u.d_agregarEmp.style.visibility = "visible";
					return false;
				}
			}
			var opcion = new Option(caja.value);
			va.options[va.options.length] = opcion;
			u.empresariales_hidden.value += caja.value+",";
			var valor = caja.value;
			caja.value="";
			consultaTexto("insertoEmpresa","recoleccion_consultas.php?accion=7&tipo=guardar&foliosempresarial="+valor+"&val="+Math.random());
		}
	}
	
	function insertoEmpresa(datos){
		if(datos.indexOf("ok")>-1){
			u.d_agregarEmp.style.visibility = "visible";
		}else{
			u.d_agregarEmp.style.visibility = "visible";
			mens.show("A","Hubo un error "+datos,"¡Atención!");
		}
	}
	
	function borrarEmpresarial(va){
		if(va.options.selectedIndex>-1){
		var frase = u.empresariales_hidden.value.replace(u.empresarial.options[u.empresarial.selectedIndex].text,"");
		u.empresariales_hidden.value = frase.replace(",,",",");
			if(u.empresariales_hidden.value.substring(0,1)==","){
				u.empresariales_hidden.value = u.empresariales_hidden.value.substring(1,u.empresariales_hidden.value.legth);
			}
			var valor = u.empresarial.options[u.empresarial.selectedIndex].text;
			va.options[va.options.selectedIndex] = null;
			va.value = "";
			
			consultaTexto("insertoEmpresa","recoleccion_consultas.php?accion=7&tipo=borrar&foliosempresarial="+valor+"&val="+Math.random());
		}
	}
	
	function obtenerDiaRecoleccion(destino){
		u.origen_hidden.value = destino;
		if(destino!="" && destino!=0){
			consultaTexto("obtenerDatosDestino","recoleccion_conj.php?accion=3&destino="+destino+"&fecha="+u.fecha.value+"&valor="+Math.random());
		}
	}
	
	function obtenerDatosDestino(datos){
		var objeto = eval(datos);
		if(objeto[0].todasemana==1){
			return false;
		}
		if(objeto[0].dia==0){
			u.origen.value ="";
			mens.show('A','El origen seleccionado no hace recolección el dia '+u.fecha.value,'¡Atención!','origen');			
		}		
	}
	
	function obtenerSucursal(id,descripcion,sucursal){
		u.idsucursal.value = id;
		v_sucursal = "1";
		u.folioant.value = u.folio.value;
		u.destino_h.value = id;		
		consultaTexto("mostrarSucursal","recoleccion_conj.php?accion=8&origen=1&sucursal="+id+"&valor="+Math.random());
	}
	
	function mostrarSucursal(datos){		
		var obj = eval(convertirValoresJson(datos));
		u.sucursal.value	= obj.principal.descripcion;
			if(obj.origen.length==1){
				u.celOrigen.innerHTML 	= txtOrigen;
				u.origen_hidden.value	= obj.origen[0].id;
				u.origen.value			= obj.origen[0].destino;
			}else{
				u.celOrigen.innerHTML = combo1;
				var combo = u.origen;
				combo.options.length = null;
				uOpcion = document.createElement("OPTION");
				uOpcion.value="";
				uOpcion.text="SELECCIONAR";			
				combo.add(uOpcion);			
				for(i=0;i<obj.origen.length;i++){	
					uOpcion = document.createElement("OPTION");
					uOpcion.value	=	obj.origen[i].id;
					uOpcion.text	=	obj.origen[i].destino;
					combo.add(uOpcion);
				}
				uOpcion = document.createElement("OPTION");
				uOpcion.value=0;
				uOpcion.text="VARIOS";			
				combo.add(uOpcion);			
				combo.value = obj.origenes.id;
				u.origen_hidden.value = obj.origenes.id;
			}
		u.folio.value = obj.folio;
	}
	
	function limpiar(){	
		u.folio.value		= ""; 		u.mensaje.value		= "";
		u.numero.value 		= ""; 		u.cp.value 			= "";
		u.colonia.value 	= ""; 		u.poblacion.value 	= "";
		u.telefono.value 	= ""; 		u.municipio.value 	= "";
		u.crucecalles.value	= ""; 		u.telefono2.value 	= "";
		u.sector.value		= ""; 		u.h1.value			= "00:00";
		u.origen.value		= "";		
		u.destino.value		= ""; 		u.chNombre.checked	= false;
		u.npedidos.value	= ""; 		u.dirigido.value		= "";
		u.unidad.value		= ""; 		
		u.destino_hidden.value	= ""; 	u.index.value		= "";
		u.c1.value			= "00"; 	
		u.horario_hidden.value	= ""; 	u.horario2_hidden.value	= "";
		u.comida_hidden.value	= ""; 	u.comida2_hidden.value	= "";	
		u.estado_hidden.value	= ""; 	u.index.value		= "";
		u.llama.value			= ""; 	u.telefono.value	= "";
		u.comentarios.value	= ""; 		u.cliente.value	= "";
		u.nombre.value		= ""; 		u.idsector.value	= "";
		u.hora.value	= "";			u.accion.value	= "";
		u.registroMercancia.value = ""; u.folio_hidden.value	= "";
		u.motivo.value	= "";			u.desmotivo.value	= "";
		u.notificaciones.value	= "";   u.motivoreprogramar.value	= "";
		u.desmotivoreprogramar.value	= ""; u.notificacionesreprogramar.value	= "";
		u.observacionesreprogramar.value	= ""; u.fecha_hidden.value	= "<?=date("d/m/Y") ?>";
		u.confirFecha.value	= ""; u.recolecciones_hidden.value	= "";
		u.empresariales_hidden.value	= ""; u.mensaje.value	= "";	
		u.idsucursal2.value	= "";	
		u.destino.disabled = false;
		u.origen.disabled = false;
		u.destino.readOnly = false;
		u.origen.readOnly = false;
		u.abajo.style.display = "none";
		u.d_guardar.style.visibility="visible";
		u.d_transmitir.style.visibility="hidden";
		u.d_realizado.style.visibility="hidden";
		u.d_cancelado.style.visibility="hidden";
		u.d_reprogramado.style.visibility="hidden";	
		u.Eliminar.style.visibility = "hidden";
		v_multiple	= "";
		v_sucursal	= "";
		v_destino	= "";
		v_origen	= "";
		v_folioant  = "";
		v_fechaant  = "";
		if(u.colEstado.innerHTML != "NO TRANSMITIDO"){
			u.recolecciones.options.length = 0;
			u.empresarial.options.length = 0;
		}
		u.colEstado.innerHTML = "";			    
		habilitarTerceros();
		obtenerDatos();
		document.all.celda_des_calle.innerHTML ='<input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$calle ?>" readonly=""/>'; 		
		document.getElementById('origen_hidden').value	= "";
		tabla1.clear();
	}
	
	function traerAlcliente(valor){
		ocultarBuscador();
		obtenerClienteBusqueda(valor);
	}
	
	function ponerDireccion(obj){		
			u.calle.value 		= obj.calle;
			u.numero.value 			= obj.numero;
			u.cp.value 				= obj.codigopostal;
			u.colonia.value 		= obj.colonia;
			u.poblacion.value 	= obj.poblacion;
			u.telefono2.value 	= obj.telefono;
			u.municipio.value 	= obj.municipio;
			if(obj.crucecalles!='null');
			u.crucecalles.value = obj.crucecalles;
			cambiarSector(obj.codigopostal,obj.colonia);
		ocultarDirecciones();
	}
	
	
	function ValidarDetalle(){
		if(u.img_agregar.style.visibility == "hidden"){
				return false;
		}
		if(document.getElementById('cantidad').value==""){
			mens.show('A','Debe Capturar Cantidad','¡Atención!','cantidad'); 
		}else if(document.getElementById('cantidad').value<0){ 
			mens.show('A','Cantidad Debe ser Mayor a Cero','¡Atención!','cantidad');	
		}else if(document.getElementById('iddescripcion').value==undefined){ 
			mens.show('A','Debe Capturar Descripción','¡Atención!','descripcion');
		}else if(document.getElementById('contenido').value==""){ 
			mens.show('A','Debe Capturar Contenido','¡Atención!','contenido');
		}else{
			u.peso.value 		= (u.peso.value=="")?0:u.peso.value;
			u.largo.value 		= (u.largo.value=="")?0:u.largo.value;
			u.alto.value 		= (u.alto.value=="")?0:u.alto.value;
			u.ancho.value 		= (u.ancho.value=="")?0:u.ancho.value;
			u.volumen.value		= (u.volumen.value=="" || u.volumen.value=="NaN")?0:u.volumen.value;
			u.pesototal.value	= (u.pesototal.value=="" || u.pesototal.value=="NaN")?0:u.pesototal.value;
			u.img_agregar.style.visibility = "hidden";			
			if(u.esmodificar.value == ""){
				consultaTexto("registroDetalle","recoleccion_consultas.php?accion=6&tipo=guardar&cantidad="+u.cantidad.value
				+"&iddescripcion="+u.iddescripcion.value+"&descripcion="+u.descripcion.value+"&contenido="+u.contenido.value
				+"&peso="+u.peso.value+"&largo="+u.largo.value+"&alto="+u.alto.value
				+"&ancho="+u.ancho.value+"&volumen="+u.volumen.value+"&pesototal="+u.pesototal.value
				+"&pesounit="+((u.pesounit.checked==true)? 1 : 0));
			}else{
				consultaTexto("modificarDetalle","recoleccion_consultas.php?accion=6&tipo=modificar&cantidad="+u.cantidad.value
				+"&iddescripcion="+u.iddescripcion.value+"&descripcion="+u.descripcion.value+"&contenido="+u.contenido.value
				+"&peso="+u.peso.value+"&largo="+u.largo.value+"&alto="+u.alto.value
				+"&ancho="+u.ancho.value+"&volumen="+u.volumen.value+"&pesototal="+u.pesototal.value
				+"&pesounit="+((u.pesounit.checked==true)? 1 : 0)+"&fecha="+u.fechahora.value);
			}
		} 
	}
	function registroDetalle(datos){	
		if(datos.indexOf("ok")>-1){
			u.img_agregar.style.visibility = "visible";
			var fe = datos.split(",");
			var objeto = new Object();
			objeto.cantidad		=	u.cantidad.value; 
			objeto.id			=	u.iddescripcion.value;
			objeto.descripcion	=	u.descripcion.value;
			objeto.contenido	=	u.contenido.value;
			objeto.peso			=	u.peso.value;
			objeto.largo		=	u.largo.value;	
			objeto.alto			=	u.alto.value;
			objeto.ancho		=	u.ancho.value; 	
			objeto.volumen		=	u.volumen.value;
			objeto.pesototal	=	u.pesototal.value;
			objeto.pesounit		=	((u.pesounit.checked==true)? 1 : 0);
			objeto.fecha		=	fe[1];
			limpiarDetalle();
			agregarDatos(objeto);
			setTimeout('u.cantidad.focus()',500);
		}else{
			u.img_agregar.style.visibility = "visible";
			mens.show("A","Hubo un Error al agregar "+datos,"¡Atención!");
		}
	}

	function modificarDetalle(datos){
		if(datos.indexOf("ok")>-1){
			u.img_agregar.style.visibility = "visible";
			var objeto = new Object();
			objeto.cantidad		=	u.cantidad.value; 
			objeto.id			=	u.iddescripcion.value;
			objeto.descripcion	=	u.descripcion.value;
			objeto.contenido	=	u.contenido.value;
			objeto.peso			=	u.peso.value;
			objeto.largo		=	u.largo.value;	
			objeto.alto			=	u.alto.value;
			objeto.ancho		=	u.ancho.value; 	
			objeto.volumen		=	u.volumen.value;
			objeto.pesototal	=	u.pesototal.value;
			objeto.pesounit		=	((u.pesounit.checked==true)? 1 : 0);
			objeto.fecha		=	u.fechahora.value;
			limpiarDetalle();
			//agregarDatos(objeto<?=($_GET[eliminar]==1)?",1":"";?>
			agregarDatos(objeto);
			setTimeout('u.cantidad.focus()',500);
		}else{
			u.img_agregar.style.visibility = "visible";
			mens.show("A","Hubo un Error al agregar "+datos,"¡Atención!");
		}
	}
	
	function validaDescripcion(e,obj){
		tecla=(document.all) ? e.keyCode : e.which;
		if((tecla==8 || tecla==46)&& document.getElementById(obj).value==""){
			document.getElementById('iddescripcion').value=""; 
		}	
	}
	
	function obtenerDescripcionValida(){
		consultaTexto("descripcionValida","../evaluacion/evaluacionMercancia_con.php?accion=12&descripcion="+u.descripcion.value);	
	}
	function descripcionValida(datos){
		if(datos.indexOf("no")>-1){
			if(u.descripcion.value!=""){
				u.iddescripcion.value="";
				u.descripcion.value="";
				alerta("La Descripción no es valida","¡Atención!","descripcion");
				return false;
			}
		}else{
			var row = datos.split(",");
			u.iddescripcion.value = row[1];
		}
	}
	
	function CalcularUnitarioFoco(){
		var u = document.all;
		if(u.peso.value!=""){
			if(u.pesounit.checked==true){
				u.pesototal.value=parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
			}else{
				u.pesototal.value= u.peso.value;
			}
		}	
	}
	function CalcularUnitario(e){
		tecla=(document.all) ? e.keyCode : e.which;
		var u = document.all;
		if(tecla==13){
			if(u.pesounit.checked==true){
				u.pesototal.value=parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
			}else{
				u.pesototal.value= u.peso.value;
			}
		}
	}
	function CalcularUnitarioCheck(){
		var u = document.all;
			if(u.pesounit.checked==true){
				if(u.peso.value!=""){
				u.pesototal.value=parseFloat(u.peso.value) * parseFloat(u.cantidad.value);
				}else{
				u.pesototal.value="";
				}
		document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
				if(document.getElementById('volumen').value=='NaN'){
					document.getElementById('volumen').value="";			
				}
			}else{
				u.pesototal.value= u.peso.value;
				document.getElementById('volumen').value=
		   ((parseFloat(document.getElementById('largo').value)*
			 parseFloat(document.getElementById('ancho').value)*
			 parseFloat(document.getElementById('alto').value))/ 4000);
				if(document.getElementById('volumen').value=='NaN'){
					document.getElementById('volumen').value="";			
				}
			}
	}
	
	function SoloND(evnt,contenido){
		evnt = (evnt) ? evnt : event;
		var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
		if ((contenido.indexOf(".") != -1) && (charCode==46)) return false;
		if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode!=46)) return false;
		return true;
	}
	
	function CalcularVolumenFoco(){
		if(document.all.alto.value!=""){
			if(document.getElementById('largo').value >=0 &&
			   document.getElementById('largo').value >=0 &&
			   document.getElementById('ancho').value >=0 &&
			   document.getElementById('alto').value>=0){	
			   if(document.all.pesounit.checked==true){
			   document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
			   }else{
			   document.getElementById('volumen').value=
			   ((parseFloat(document.getElementById('largo').value)*
				 parseFloat(document.getElementById('ancho').value)*
				 parseFloat(document.getElementById('alto').value))/ 4000) *
				 parseFloat(document.all.cantidad.value);
			   }
			}
		}	
	}
	function CalcularVolumen(e){
		if(e == 13){
			tecla = 13;
		}else{
			tecla = (document.all) ? e.keyCode : e.which;
		}
		if(tecla==13){
			if(document.getElementById('largo').value >=0 &&
			   document.getElementById('largo').value >=0 &&
			   document.getElementById('ancho').value >=0 &&
			   document.getElementById('alto').value>=0){	
			   if(document.all.pesounit.checked==true){
			   document.getElementById('volumen').value=((parseFloat(document.getElementById('largo').value)*parseFloat(document.getElementById('ancho').value)*parseFloat(document.getElementById('alto').value))/ 4000) * parseFloat(document.getElementById('cantidad').value);
			   }else{
			   document.getElementById('volumen').value=
			   ((parseFloat(document.getElementById('largo').value)*
				 parseFloat(document.getElementById('ancho').value)*
				 parseFloat(document.getElementById('alto').value))/ 4000) *
				 parseFloat(document.all.cantidad.value);
			   }
			
			}
		}	
	}
	
	function limpiarDetalle(){
		document.getElementById('cantidad').value="";
		document.getElementById('iddescripcion').value="";
		document.getElementById('descripcion').value="";
		document.getElementById('contenido').value="";
		document.getElementById('peso').value="";
		document.getElementById('largo').value="";
		document.getElementById('alto').value="";
		document.getElementById('ancho').value="";
		document.getElementById('volumen').value="";
		document.getElementById('pesototal').value="";
		document.all.pesounit.checked = false;	
		u.id.value = "";
		u.abierto.value = "";
		u.oculto.value = "";
		u.iddescripcion.value = "";
		u.fechahora.value = "";
		u.esmodificar.value = "";	
	}
	
	var concep 	= new Array(<?php echo $cadena; ?>);
	var desccrip = new Array(<?php echo $desccrip; ?>);
	var desc = new Array(<?php echo $desc; ?>);
	var ori = new Array(<?php echo $ori; ?>);
	var dx = new Array(<?php echo $dx; ?>);
</script>

<title>Recolecciones</title>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="576" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="572" class="FondoTabla Estilo4">RECOLECCI&Oacute;N</td>
    </tr>
    <tr>
      <td><table width="232" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> </tr>
      </table>
          <table width="572" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="3" onclick="obtenerfecha()"></td>
              <td width="262" align="right"><label></label>
                  <img src="../img/Boton_Cliente.gif" width="70" height="20" align="absbottom" style="cursor:pointer" onclick="mens.popup('../catalogos/cliente/client.php?recoleccion=1', 630, 550, 'v1', 'Cat&aacute;logo Cliente');" /></td>
            </tr>
            <tr>
              <td colspan="4">
                      <table width="580" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="37"><span class="Tablas">Folio:</span></td>
                          <td width="118"><span class="Tablas">
                            <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$_POST['folio']; ?>" readonly=""/>
                            </span><span class="Tablas">
                            <input name="folioant" type="hidden" id="folioant" value="<?=$_POST[folioant] ?>" />
                            <input name="destino_h" type="hidden" id="destino_h" value="<?=$_POST[destino_h] ?>" />
                          </span></td>
                          <td width="72" align="right"><span class="Tablas">
                            <input name="idsucursal" id="idsucursal" type="hidden" value="<?=$idsucursal ?>" />
                            <input name="sucursalant" type="hidden" id="sucursalant" value="<?=$_POST[sucursalant] ?>" />
                            Sucursal:</span></td>
                          <td width="108"><span class="Tablas">
                            <input name="sucursal" type="text" class="Tablas" id="sucursal" value="<?=$_POST[sucursal] ?>" autocomplete="array:dx" onkeypress="if(event.keyCode==13){obtenerSucursal(this.codigo);}" codigo="<?=$_SESSION[IDSUCURSAL]?>" onkeydown="if(event.keyCode==9){obtenerSucursal(this.codigo);}" onblur="if(this.value!=''){obtenerSucursal(this.codigo);}" <?=(($_SESSION[IDSUCURSAL]!='1')?"readonly='true'":"")?>/>
                          </span></td>
                          <td width="34"><span class="Tablas"><img src="../img/Buscar_24.gif" id="btnSucursal" width="24" height="23" align="absbottom" style="cursor:pointer; visibility:hidden" onclick="if(document.all.colEstado.innerHTML!='TRANSMITIDO' &amp;&amp; document.all.colEstado.innerHTML!='REALIZADO'){abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 550, 450, 'ventana', 'Busqueda')}" /></span></td>
                          <td width="47">Estado:</td>
                          <td width="164" id="colEstado" style="font:tahoma; font-size:15px; font-weight:bold"><?=$_POST['estado_hidden'];?></td>
                        </tr>
                      </table>              </td>
            </tr>
            <tr>
              <td colspan="4"><table width="580" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="36"><span class="Tablas">Fecha:</span></td>
                    <td width="129">
                      <input name="fecha" type="text" class="Tablas" id="fecha" style="width:100px;background:#FFFF99" value="<?=$_POST[fecha] ?>" readonly="" onchange="obtenerFolioxFecha(this.value)" />                      
                      <img src="../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onClick="if(document.all.colEstado.innerHTML!='CANCELADO' && document.all.colEstado.innerHTML!='REALIZADO'){displayCalendar(document.all.fecha,'dd/mm/yyyy',this); }" /></td>
                    <td width="42" align="right"><span class="Tablas">Subdestino:</span><span class="Tablas">
                      <input name="origen_hidden" type="hidden" id="origen_hidden" value="<?=$_POST[origen_hidden] ?>" />
                    </span></td>
                    <td width="153" id="celOrigen"><span class="Tablas">
                      <input name="origen" type="text" class="Tablas" id="origen" style="width:100px" value="<?=$_POST[origen] ?>" autocomplete="array:ori" onkeypress="if(event.keyCode==13){document.all.origen_hidden.value=this.codigo; obtenerDiaRecoleccion(this.codigo);}" onblur="if(this.value!=''){document.all.origen_hidden.value = this.codigo; if(this.codigo==undefined){document.all.origen_hidden.value ='no'}}"  >
                    </span></td>
                    <td width="17"><input name="estado_hidden" type="hidden" id="estado_hidden" value="<?=$_POST[estado_hidden]; ?>" /></td>
                    <td width="42">Destino:</td>
                    <td width="140"><span class="Tablas">
                      <input name="destino" type="text" class="Tablas" id="destino" style="width:130px" value="<?=$_POST[destino] ?>" 
				autocomplete="array:desc" onkeypress="if(event.keyCode==13){document.all.destino_hidden.value=this.codigo; setTimeout('u.cantidad.focus()',500); }" onblur="if(this.value!=''){document.all.destino_hidden.value = this.codigo; if(this.codigo==undefined){document.all.destino_hidden.value ='no'}}" />
                      <input name="destino_hidden" type="hidden" id="destino_hidden" value="<?=$_POST[destino_hidden] ?>" />
                    </span></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="4" class="FondoTabla Estilo4">Mercanc&iacute;a</td>
            </tr>
            <tr>
              <td colspan="4"><table width="570" id="detalle" border="0" align="center" cellpadding="0" cellspacing="0">
              </table></td>
            </tr>
            <tr>
              <td colspan="4" align="right"><input name="index" type="hidden" id="index" />              </td>
            </tr>
            <tr>
            	<td colspan="4">
                
                
               
               <table width="573" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#C9D5E9">
          <tr>
            <td width="93" class="Tablas">Cantidad:</td>
            <td colspan="4" class="Tablas"><label>
              <input name="cantidad" type="text" class="Tablas" id="cantidad" onKeyPress="return SoloND(event,this.value)

" onKeyUp="if(event.keyCode==13){document.all.descripcion.focus();}" value="<?=$cantidad ?>" size="5" maxlength="5" />
              <input name="pesounit" type="checkbox" onClick="CalcularUnitarioCheck()" id="pesounit" value="1" <? if($pesounit==1){ echo 'checked';} ?>>
              Peso y Medidas Unitarias </label></td>
          </tr>
          <tr>
            <td class="Tablas">Descripci&oacute;n:</td>
            <td colspan="3" class="Tablas" id="coldescripcion"><input name="descripcion" type="text" class="Tablas" id="descripcion" style="text-transform:uppercase" autocomplete="array:desccrip" onKeyPress="if(event.keyCode==13){document.all.iddescripcion.value=this.codigo; document.all.contenido.focus();}" onKeyDown="if(event.keyCode==9){document.all.iddescripcion.value=this.codigo;}" onKeyUp="return validaDescripcion(event,this.name)" value="<?=$descripcion ?>" size="30" maxlength="50" onBlur="if(this.value!=''){setTimeout('obtenerDescripcionValida()',1000);document.getElementById('oculto').value=''}" /></td>
            <td class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="javascript:popUp('../evaluacion/buscar.php?tipo=descripcion')" style="cursor:pointer" /></td>
          </tr>
          <tr>
            <td class="Tablas">Contenido:</td>
            <td colspan="4" class="Tablas"><input name="contenido" type="text" class="Tablas" id="contenido" style="text-transform:uppercase; font:tahoma" onBlur="trim(document.getElementById('contenido').value,'contenido');" onKeyPress="return tabular(event,this)" value="<?=$contenido ?>" size="42" maxlength="50" autocomplete="array:concep" />
            </td>
          </tr>
          <tr>
            <td class="Tablas">Peso:</td>
            <td width="170" class="Tablas"><input name="peso" type="text" class="Tablas" id="peso" onBlur="CalcularUnitarioFoco()" onKeyPress="return SoloND(event,this.value)

" onKeyDown="CalcularUnitario(event); return tabular(event,this)" value="<?=$peso ?>" size="10" maxlength="15" /></td>
            <td width="88" class="Tablas">&nbsp;</td>
            <td width="76" class="Tablas">Largo:</td>
            <td width="146" class="Tablas"><input name="largo" type="text" class="Tablas" id="largo" onKeyPress="return SoloND(event,this.value)

" onKeyDown="return tabular(event,this)" value="<?=$largo ?>" size="7" maxlength="10" />
              cm</td>
          </tr>
          <tr>
            <td class="Tablas">Ancho:&nbsp;</td>
            <td colspan="2" class="Tablas"><input name="ancho" type="text" class="Tablas" id="ancho" onKeyPress="return SoloND(event,this.value)

" onKeyDown="return tabular(event,this)" value="<?=$ancho ?>" size="10" maxlength="10" />
              cm</td>
            <td class="Tablas">Alto:</td>
            <td class="Tablas"><input name="alto" type="text" class="Tablas" id="alto" onBlur="CalcularVolumenFoco()" onKeyPress="if(event.keyCode==13){ValidarDetalle();}else{return SoloND(event,this.value)}" onKeyDown="CalcularVolumen(event);" value="<?=$alto ?>" size="7" maxlength="10" />
              cm</td>
          </tr>
          <tr>
            <td class="Tablas">Peso Total: </td>
            <td class="Tablas"><input name="pesototal" type="text" class="Tablas" id="pesototal" value="<?=$pesototal ?>" size="10" readonly="" style="background:#FFFF99" /></td>
            <td colspan="2" class="Tablas">Peso Volum&eacute;trico:</td>
            <td class="Tablas"><input name="volumen" type="text" class="Tablas" id="volumen" value="<?=$volumen ?>" size="9" readonly="" style="background:#FFFF99" /></td>
          </tr>
          <tr>
            <td colspan="5">
			  <input name="id" type="hidden" id="id" value="<?=$id ?>" />
              <input name="abierto" type="hidden" id="abierto" value="<?=$abierto ?>" />
              <input name="oculto" type="hidden" id="oculto" />
              <input name="iddescripcion" type="hidden" id="descripcion_hidden" value="<?=$iddescripcion ?>" />             
              <input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora ?>" />
         	  <input name="esmodificar" type="hidden" id="esmodificar" value="<?=$esmodificar ?>" />
              <table width="148" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="78"><img src="../img/Boton_Agregari.gif" id="img_agregar" alt="Guardar" width="70" height="20" style="cursor:pointer" onClick="CalcularVolumen(13); ValidarDetalle();" /></td>
                    <td width="70"><div id="Eliminar" class="ebtn_eliminar" onclick="eliminarFila();"></div></td>
                  </tr>
              </table></td>
          </tr>
        </table> 
                
                
                
                
                </td>
            </tr>
            <tr>
              <td width="78">No. Pedido<span class="Tablas">:</span></td>
              <td><span class="Tablas">
                <input name="npedidos" type="text" class="Tablas" id="npedidos" style="width:150px" onkeypress="return solonumeros(event);" onkeyup="if(event.keyCode==13){document.all.dirigido.focus();}" value="<?=$_POST[npedidos] ?>" maxlength="7" />
              </span></td>
              <td width="79">Dirigir con<span class="Tablas">:</span></td>
              <td><span class="Tablas">
                <input name="dirigido" type="text" class="Tablas" id="dirigido" style="width:250px" onkeypress="if(event.keyCode==13){document.all.chNombre.focus();}" value="<?=$_POST[dirigido] ?>" maxlength="70" />
              </span></td>
            </tr>
            <tr>
              <td colspan="4" class="FondoTabla Estilo4">Datos Terceros </td>
            </tr>
            
            <tr>
              <td colspan="4" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="5"><input name="chNombre" type="checkbox" id="chNombre" style="width:13px" value="1" onclick="habilitarTerceros();" <? if($_POST['chNombre']=="1"){echo "checked";} ?> />
                    Capturar datos </td>
                </tr>
                <tr>
                  <td width="24%">Nombre de Quien Llama:</td>
                  <td width="29%"><span class="Tablas">
                    <input name="llama" type="text" class="Tablas" id="llama" style="width:150px; background:#FFFF99" value="<?=$_POST[llama] ?>" disabled="disabled" onkeypress="if(event.keyCode==13){document.all.telefono.focus();}" />
                  </span></td>
                  <td width="3%">&nbsp;</td>
                  <td width="12%">Comentarios:</td>
                  <td width="32%" rowspan="2"><textarea class="Tablas" name="comentarios" onkeypress="if(event.keyCode==13){document.all.cliente.focus();}" disabled="disabled" id="comentarios" style="background:#FFFF99; text-transform:uppercase; width:170px; height:30px"><?=$_POST[comentarios] ?>
                  </textarea></td>
                </tr>
                <tr>
                  <td><span class="Tablas">Telefono:</span></td>
                  <td><span class="Tablas">
                    <input name="telefono" type="text" class="Tablas" id="telefono" style="width:150px; background:#FFFF99" disabled="disabled" value="<?=$_POST[telefono] ?>" onkeypress="if(event.keyCode==13){document.all.comentarios.select();}" />
                  </span></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                
              </table></td>
            </tr>
            
            <tr>
              <td colspan="4" class="FondoTabla Estilo4">Cliente</td>
            </tr>
            <tr>
              <td colspan="4"><table width="580" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="79"># Cliente:</td>
                    <td width="175"><span class="Tablas">
                      <input name="cliente" type="text" class="Tablas" id="cliente" style="width:60px" onkeypress="obtenerCliente(event,this.value)" value="<?=$_POST[cliente] ?>" />
                      <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="mostrarBuscador();" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../img/Boton_Agregarchico.gif" alt="Agregar Direcci&oacute;n" name="b_remitente_dir" align="absbottom" id="b_remitente_dir" style="cursor:hand" onclick="if(document.all.cliente.value==''){ mens.show('A','Proporcione el id del cliente','&iexcl;Atencion!','cliente') }else{ abrirVentanaFija('../buscadores_generales/agregarDireccion.php?funcion=devolverCliente('+document.all.cliente.value+')&amp;idcliente='+document.all.cliente.value, 460, 395, 'ventana', 'DATOS DIRECCION')}" /></span></td>
                    <td colspan="4"><span class="Tablas">
                      <input name="nombre" type="text" class="Tablas" id="nombre" style="width:285px;background:#FFFF99" value="<?=$_POST[nombre] ?>" readonly=""/>
                    </span></td>
                  </tr>
                  <tr>
                    <td>Calle: </td>
                    <td colspan="3" id="celda_des_calle"><span class="Tablas">
                      <input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$_POST[calle] ?>" readonly=""/>
                    </span></td>
                    <td width="195"><span class="Tablas"> Numero:
                      <input name="numero" type="text" class="Tablas" id="numero" style="width:120px;background:#FFFF99" value="<?=$_POST[numero] ?>" readonly=""/>
                    </span></td>
                    <td width="3">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Colonia:</td>
                    <td><span class="Tablas">
                      <input name="colonia" type="text" class="Tablas" id="colonia" style="width:165px;background:#FFFF99" value="<?=$_POST[colonia] ?>" readonly=""/>
                    </span></td>
                    <td width="23">&nbsp;</td>
                    <td width="96">C.P.:</td>
                    <td><span class="Tablas">
                      <input name="cp" type="text" class="Tablas" id="cp" style="width:165px;background:#FFFF99" value="<?=$_POST[cp] ?>" readonly=""/>
                    </span></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Cruce de Calles:</td>
                    <td colspan="5"><span class="Tablas">
                      <input name="crucecalles" type="text" class="Tablas" id="crucecalles" style="width:460px;background:#FFFF99" value="<?=$_POST[crucecalles] ?>" readonly=""/>
                    </span></td>
                  </tr>
                  <tr>
                    <td>Poblacion:</td>
                    <td><span class="Tablas">
                      <input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:165px;background:#FFFF99" value="<?=$_POST[poblacion] ?>" readonly=""/>
                    </span></td>
                    <td>&nbsp;</td>
                    <td>Mun./Deleg.:</td>
                    <td><span class="Tablas">
                      <input name="municipio" type="text" class="Tablas" id="municipio" style="width:165px;background:#FFFF99" value="<?=$_POST[municipio] ?>" readonly=""/>
                    </span></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Telefono:</td>
                    <td><span class="Tablas">
                      <input name="telefono2" type="text" class="Tablas" id="telefono2" style="width:165px;background:#FFFF99" value="<?=$_POST[telefono2] ?>" readonly=""/>
                    </span></td>
                    <td>&nbsp;</td>
                    <td>Hrio. Recolecci&oacute;n:</td>
                    <td><span class="Tablas"><input name="h1" type="text" id="h1" style="width:165px" value="00:00" class="Tablas"	
					onkeypress="if(event.keyCode==13){document.getElementById('sector').focus();}"  /></span></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Sector:
                      <input name="idsector" type="hidden" id="idsector" value="<?=$_POST[idsector]; ?>" /></td>
                    <td><span class="Tablas">
                      <input name="sector" type="text" class="Tablas" id="sector" style="width:165px;background:#FFFF99" value="<?=$_POST[sector] ?>" readonly=""/>
                    </span></td>
                    <td>&nbsp;</td>
                    <td>
                      Hrio. Comida:</td>
                    <td><span class="Tablas"><input name="c1" type="text" id="c1" style="width:165px" value="00:00" class="Tablas" 
					onkeypress="if(event.keyCode==13){document.getElementById('unidad').focus();}"/></span></td>
                    <td>&nbsp;</td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="4" class="FondoTabla Estilo4">Datos Recolecci&oacute;n </td>
            </tr>
            <tr>
              <td>Unidad: </td>
              <td width="161"><span class="Tablas">
                <input name="unidad" type="text" class="Tablas" id="unidad" style="width:120px" onkeypress="obtenerUnidad(event,this.value)" value="<?=$_POST['unidad']; ?>" maxlength="30" />
                <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onclick=            "abrirVentanaFija('../buscadores_generales/buscarUnidadxSucGen.php?funcion=obtenerUnidadBusqueda&sucursal='+document.all.idsucursal.value, 550, 450, 'ventana', 'Busqueda')"></span></td>
              <td colspan="2"><input name="hora" type="hidden" id="hora" value="<?=$hora ?>" />
                  <input name="accion" type="hidden" id="accion" value="<?=$_POST[accion] ?>" />
                  <input name="horario_hidden" type="hidden" id="horario_hidden" value="<?=$_POST[horario_hidden] ?>" />
                  <input name="registroMercancia" type="hidden" id="registroMercancia" value="<?=$_POST[registroMercancia] ?>" />
                  <input name="folio_hidden" type="hidden" id="folio_hidden" value="<?=$folio_hidden ?>" />
                  <input name="motivo" type="hidden" id="motivo" value="<?=$_POST[motivo]; ?>" />
                  <input name="desmotivo" type="hidden" id="desmotivo" value="<?=$_POST[desmotivo]; ?>" />
                  <input name="notificaciones" type="hidden" id="notificaciones" value="<?=$_POST[notificaciones]; ?>" />
                  <input name="motivoreprogramar" type="hidden" id="motivoreprogramar" value="<?=$_POST[motivoreprogramar]; ?>" />
                  <input name="desmotivoreprogramar" type="hidden" id="desmotivoreprogramar" value="<?=$_POST[desmotivoreprogramar]; ?>" />
                  <input name="notificacionesreprogramar" type="hidden" id="notificacionesreprogramar" value="<?=$_POST[notificacionesreprogramar]; ?>" />
                  <input name="observacionesreprogramar" type="hidden" id="observacionesreprogramar" value="<?=$_POST[observacionesreprogramar]; ?>" />
                  <input name="fecha_hidden" type="hidden" id="fecha_hidden" value="<?=$fecha_hidden ?>" />
                  <input name="confirFecha" type="hidden" id="confirFecha" value="<?=$confirFecha ?>" />
                  <input name="horario2_hidden" type="hidden" id="horario2_hidden" value="<?=$horario2_hidden ?>" />
                  <input name="comida_hidden" type="hidden" id="comida_hidden" value="<?=$comida_hidden ?>" />
                  <input name="comida2_hidden" type="hidden" id="comida2_hidden" value="<?=$comida2_hidden ?>" /></td>
            </tr>
            <tr>
              <td colspan="4"><table width="571" id="abajo" border="0" cellspacing="0" cellpadding="0" style="display:none">
                  <tr>
                    <td colspan="2"><input type="checkbox" name="multiple" value="1" <? if($_POST[multiple] == "1"){echo "checked";} ?>/>
                      Recolecci&oacute;n M&uacute;ltiple</td>
                  </tr>
                  <tr>
                    <td><table width="266" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="9" height="16" class="formato_columnas_izq"></td>
                          <td width="250"class="formato_columnas" align="center">FOLIO RECOLECCION </td>
                          <td width="9"class="formato_columnas_der"></td>
                        </tr>
                        <tr>
                          <td colspan="12"><table width="266" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="11"><span class="Tablas">
                                  <input name="folior" type="text" class="Tablas" id="folior" style="width:150px" onkeypress="if(event.keyCode==13){validarFolioRecoleccion(folior, folior.value, document.all.recolecciones);}" value="<?=$_POST[folior] ?>" maxlength="10" />
                                </span></td>
                                <td width="95"><div id="d_agregarRec" class="ebtn_agregar" onclick="validarFolioRecoleccion(folior, folior.value, document.all.recolecciones)"></div></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td colspan="12"><select name="recolecciones" size="7" id="recolecciones" style="width:265px" ondblclick="borrarRecoleccion(this)">
                          </select></td>
                        </tr>
                      </table>
                        <input name="recolecciones_hidden" type="hidden" id="recolecciones_hidden" value="<?=$_POST[recolecciones_hidden] ?>" /></td>
                    <td><table width="266" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="9" height="16"   class="formato_columnas_izq"></td>
                          <td width="250"class="formato_columnas" align="center">GUIAS EMPRESARIALES </td>
                          <td width="9"class="formato_columnas_der"></td>
                        </tr>
                        <tr>
                          <td colspan="12"><table width="266" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td colspan="11"><span class="Tablas">
                                  <input name="guias" type="text" class="Tablas" id="guias" style="width:150px" onkeypress="if(event.keyCode==13){insertarEmpresarial(guias, guias.value, document.all.empresarial);}" value="<?=$_POST[guias] ?>" maxlength="15"/>
                                </span></td>
                                <td><div id="d_agregarEmp" class="ebtn_agregar" onclick="insertarEmpresarial(guias, guias.value, document.all.empresarial)"></div></td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td colspan="12"><select name="empresarial" size="7" id="empresarial" style="width:265px" ondblclick="borrarEmpresarial(this)">
                            </select>
                              <input name="empresariales_hidden" type="hidden" value="<?=$_POST[empresariales_hidden] ?>" /></td>
                        </tr>
                    </table></td>
                  </tr>
              </table></td>
            </tr>
            <tr>					
              <td colspan="4"><table width="447" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="85"><div id="d_guardar" class="ebtn_guardar" onclick="validar();"></div></td>
                    <td width="85"><div id="d_transmitir" class="ebtn_transmitir" style="visibility:hidden" onclick="mens.show('C','&iquest;Esta seguro de Transmitir la Recolecci&oacute;n?', '', '', 'transmitir();');"></div></td>
                    <td width="85"><div id="d_realizado" class="ebtn_realizado" style="visibility:hidden" onclick="mens.show('C','&iquest;Esta seguro que desea Realizar la Recolecci&oacute;n?', '', '', 'realizar();');"></div></td>
                    <td width="85"><div id="d_cancelado" class="ebtn_cancelado" style="visibility:hidden" onclick="mens.show('C','&iquest;Esta seguro de Cancelar la Recolecci&oacute;n?', '', '', 'confirmarCancelar();');"></div></td>
                    <td width="96"><div id="d_reprogramado" class="ebtn_reprogramado" style="visibility:hidden" onclick="mens.show('C','&iquest;Esta seguro de Reprogramar la Recolecci&oacute;n?', '', '', 'confirmarReprogramacion();');"></div></td>
                    <td width="96"><div id="d_nuevo" class="ebtn_nuevo" onclick="mens.show('C','Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar();')"></div></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td height="11" colspan="4"><label>
                <input name="mensaje" type="hidden" id="mensaje" value="<?=$mensaje ?>" />
                <input name="idsucursal2" type="hidden" id="idsucursal2" value="<?=$idsucursal2 ?>" />
              </label></td>
            </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>
<?
	$raiz = "../";
	$funcion = "traerAlcliente";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../buscadores_generales/buscadorIncrustado.php");
	
	$raiz = "../";
	$funcion = "ponerDireccion";
	$nombreBuscador = "catDirecciones";
	$funcionMostrar = "mostrarDirecciones";
	$funcionOcultar = "ocultarDirecciones";
	include("../buscadores_generales/direccionesIncrustadas.php");
?>