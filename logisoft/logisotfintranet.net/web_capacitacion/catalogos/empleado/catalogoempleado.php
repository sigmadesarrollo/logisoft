<?	session_start();
	require_once('../../Conectar.php');
	$link = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../javascript/ajaxlist/ajax.js"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/shortcut.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script src="../../javascript/funcionesDrag.js"></script>
<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<!-- activex huella -->
<!-- pbpbpbpbpbpbpbpbp CLSID:DF278166-496F-4B38-AE91-D12A643B43D0-->
<script>
	var huella;
	var Input = '<input name="colonia" type="text" class="Tablas" id="colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';
	var combo1 = "<select name='colonia' id='colonia' class='Tablas' style='width:128px' onKeyPress='return tabular(event,this)'>";
	var c_seleccionada = "0_0";
	var var_load = '<img src="../../javascript/loading.gif">';
	var var_input = '<input class="Tablas" name="colonia" type="text" id="colonia" value="<?= $colonia; ?>" readonly="" onfocus="foco(this.name)" style="width:120px;background:#FFFF99" onblur="document.all.oculto.value=\'\'" />';
	var guardando = 0;
	var var_lic = '<img src="../../img/guia_azul_32.gif">';
	var u = document.all;
	var mens 		= new ClaseMensajes();
	var huella;
	var xintervalo = 0;
	
	jQuery(function($){
	   $('#slfNacimiento').mask("99/99/9999");
	   $('#Alta').mask("99/99/9999");
   	   $('#Baja').mask("99/99/9999");
	   $('#Reingreso').mask("99/99/9999");
	   $('#BReingreso').mask("99/99/9999");
	});
	window.onload = function(){
		mens.iniciar('../../javascript',false);
		obtenerCodigo();
		//huella = new ActiveXObject("oHuellaDigital.HuellaDigital");
		//huella.Inicializar();
		//huella.ponerBaseDatos("pmm_curso","pmmintranet.net");
		//verificarSiPuso();
		document.getElementById('DGhuella').ponerBaseDatos("pmm_curso","www.pmmintranet.net");
		document.getElementById('DGhuella').iniciar()
		xintervalo = setInterval(cuantos,1000);
	}
	
	function cuantos(){
		document.getElementById('lecturas').innerHTML = 'Necesita '+document.getElementById('DGhuella').lecturasNecesitadas()+" lecturas.";
	}
	
	function reiniciarContador(){
		document.getElementById('DGhuella').iniciar();
	}
	
	window.onbeforeunload = function(){
		huella.Finalizar();
	}
	
	function Verificar(){
		var claveusu = document.getElementById('DGhuella').IdentificaUsuario();
		if(claveusu==0){
			mens.show('A','Huella Dactilar no registrada',"¡Atencion!");
		}else{
			consultaTexto("getEmpleado","catalogoempleado_con.php?accion=6&idempleado="+claveusu+"&hue="+Math.random());
			BuscarEmpleado(claveusu);
		}
	}
				
	function CapturarHuella(){
		if(document.getElementById('DGhuella').lecturasNecesitadas()!=0){
			mens.show('A','Por favor termine la lectura de la huella digital, '+
					  'faltan '+document.getElementById('DGhuella').lecturasNecesitadas()+' lecturas',"¡Atencion!");
			return false;
		}
		
		if(document.all.empleado.value == ""){
			mens.show('A','Seleccione el empleado para aplicar la huella',"¡Atencion!");
			return false;
		}
		
		if(document.getElementById('DGhuella').guardar(document.all.empleado.value)==1){
			mens.show("I", "La huella ha sido capturada","¡Atencion!");
		}else{
			mens.show("A", "No se pudo guardar la huella","¡Atencion!");
		}
	}
	
	function getEmpleado(datos){
		var obj = eval(convertirValoresJson(datos));
		mens.show("I", "Esta huella esta registrada al usuario "+obj.nombre,"¡Atencion!");
	}
	
	function obtenerCodigo(){
		consultaTexto("mostrarCodigo","catalogoempleado_con.php?accion=0");
	}
	function mostrarCodigo(datos){
		u.empleado.value = datos.replace("\n","").replace("\r","").replace("\n\r","");
		u.numempleado.focus();
		u.activa.checked=true;
		u.activa.value=1;
	}
	function validar(){		
	/*
	se kito email
	else if(u.mail.value!="" && !isEmailAddress(u.mail)){
			mens.show('A','Debe capturar un Email valido', '¡Atención!','mail');
		}
	*/
	
		if(u.ChLicencia.checked==false){	
			u.nlicencia.value=""; u.vigencia.value="";
			u.lentes.value="";    u.sltlicencia.value="";
		}
		if(u.numempleado.value==""){
			mens.show('A','Debe capturar No.Empleado', '¡Atención!','numempleado');
		}else if(u.sucursal.value=="0"){
			mens.show('A','Debe capturar Sucursal', '¡Atención!','sucursal');
		}else if(u.Grsexo[0].checked==false && u.Grsexo[1].checked==false){
			mens.show('A','Debe capturar Sexo', '¡Atención!','Grsexo[0]');
		}else if(u.slecivil.value=="0"){
			mens.show('A','Debe capturar Estado Civil', '¡Atención!','slecivil');
		}else if(u.nombre.value==""){
			mens.show('A','Debe capturar Nombre', '¡Atención!','nombre');		
		}else if(u.apaterno.value==""){
			mens.show('A','Debe capturar Apellido Paterno', '¡Atención!','apaterno');
		}else if(u.amaterno.value==""){
			mens.show('A','Debe capturar Apellido Materno', '¡Atención!','amaterno');
		}else if(u.rfc.value==""){
			mens.show('A','Debe capturar un RFC', '¡Atención!','rfc');
		}else if(!ValidaRfc(u.rfc.value)){
			mens.show('A','Debe capturar un RFC valido', '¡Atención!','rfc');
		}else if(u.curp.value==""){
			mens.show('A','Debe capturar CURP', '¡Atención!','curp');
		}else if(u.nimss.value==""){
			mens.show('A','Debe capturar NIMSS', '¡Atención!','nimss');
		}else if(u.calle.value==""){
			mens.show('A','Debe capturar Calle', '¡Atención!','calle');
		}else if(u.numero.value==""){
			mens.show('A','Debe capturar Numero', '¡Atención!','numero');
		}else if(u.cp.value==""){
			mens.show('A','Debe capturar el Codigo Postal', '¡Atención!','cp');
		}else if(u.telefono.value==""){
			mens.show('A','Debe capturar Telefono', '¡Atención!','telefono');
		}else if(u.lnacimiento.value==""){
			mens.show('A','Debe capturar Lugar Nacimiento', '¡Atención!','lnacimiento');
		}else if(u.slfNacimiento.value==""){
			mens.show('A','Debe capturar Fecha Nacimeinto.', '¡Atención!','slfNacimiento');
		}else if(u.sltContrato.value=="0"){
			mens.show('A','Debe capturar Tipo de Contrato.', '¡Atención!','sltContrato');	
		}else if(u.Alta.value==""){
			mens.show('A','Debe capturar Alta del Empleado', '¡Atención!','Alta');				
		}else if(u.Baja.value!="" && u.motivos.value==""){				
			mens.show('A','Capture correctamente Baja', '¡Atención!','Baja');
		}else if(u.slDepartamento.value=="0"){
			mens.show('A','Debe capturar Departamento', '¡Atención!','slDepartamento');
		}else if(u.slTTrabajo.value=="0"){
			mens.show('A','Debe capturar Turno de Trabajo', '¡Atención!','slTTrabajo');
		}else if(u.puesto.value==""){
			mens.show('A','Debe capturar puesto', '¡Atención!','despuesto');
		}else if(u.subcuenta.value==""){
			mens.show('A','Debe capturar Subcuenta Contable', '¡Atención!','subcuenta');
		}else if(u.cpagoelectronico.value==""){
			mens.show('A','Debe capturar No. Cuenta Pago Electronico', '¡Atención!','cpagoelectronico');
		}else if(u.resultado.innerHTML=="Nombre de usuario ocupado" && u.user1.value==""){	
		mens.show('A','El Nombre de Usuario ya existe, debe capturar uno distinto', '¡Atención!','user');
	}else if(u.resultado.innerHTML=="Nombre de usuario ocupado" && u.user.value.toUpperCase()!=u.user1.value.toUpperCase()){
	mens.show('A','El Nombre de Usuario ya existe, debe capturar uno distinto', '¡Atención!','user');
	}else{
			var arr = new Array();
			arr[0] = u.numempleado.value;		arr[1] = u.sucursal.value;
			arr[2] = ((u.Grsexo[0].checked == true) ? "H" : "M") ;
			arr[3] = u.slecivil.value;		arr[4] = u.nombre.value;
			arr[5] = u.apaterno.value;  	arr[6] = u.amaterno.value;
			arr[7] = u.rfc.value;			arr[8] = u.curp.value;
			arr[9] = u.nimss.value;			arr[10] = u.celular.value;
			arr[11] = u.mail.value;			arr[12] = u.celularemp.value;
			arr[14] = u.slfNacimiento.value;
			arr[15] = u.sltContrato.value;	arr[16] = u.Alta.value;
			arr[17] = u.Baja.value;			arr[18] = u.Reingreso.value;
			arr[19] = u.BReingreso.value;	arr[20] = u.slDepartamento.value;			
			arr[21] = u.slTTrabajo.value;	arr[22] = u.puesto.value;
			arr[23] = u.ChLicencia.value;	arr[24] = u.subcuenta.value;
			arr[25] = u.cpagoelectronico.value; arr[26] = u.user.value;
			arr[27] = u.password.value; 	arr[28] = u.nlicencia.value;
			arr[29] = u.vigencia.value; 	arr[30] = u.lentes.value;
			arr[31] = u.sltlicencia.value;	arr[32] = "";
			arr[33] = u.grupos.value;		arr[34] = u.activa.value;			
			var dir = new Array();
			row = u.calle.value.split("-");
			dir[0] = row[0];				dir[1] = u.numero.value;
			dir[2] = u.cp.value;	  			dir[3] = u.colonia.value;
			dir[4] = u.poblacion.value;			dir[5] = u.municipio.value;
			dir[6] = u.estado.value;			dir[7] = u.pais.value;
			dir[8] = u.telefono.value;
			
			var pest = new Array();
			pest[0] = u.pestana1.value; pest[1] = u.pestana2.value;
			pest[2] = u.pestana3.value; pest[3] = u.pestana4.value;
			pest[4] = u.pestana5.value;
			
			if(u.accion.value==""){
				u.img_guardar.style.visibility = "hidden";
				consultaTexto("registrarEmpleado","catalogoempleado_con.php?accion=1&arre="+arr+"&dir="+dir+"&s="+Math.random()
				+"&lugarn="+u.lnacimiento.value+"&motivos="+u.motivos.value+"&pest="+pest);
			}else if(u.accion.value == "modificar"){
				u.img_guardar.style.visibility = "hidden";
				consultaTexto("registrarEmpleado","catalogoempleado_con.php?accion=2&arre="+arr
				+"&codigo="+u.empleado.value+"&dir="+dir+"&s="+Math.random()+"&lugarn="+u.lnacimiento.value
				+"&motivos="+u.motivos.value+"&pest="+pest);
			}
		}
	} 
	function registrarEmpleado(datos){
		if(datos.indexOf("ok")>-1){
			var row = datos.split(",");
			u.img_guardar.style.visibility = "visible";
			if(row[1]=="guardado"){
				u.accion.value = "modificar";
				u.empleado.value = row[2];
				mens.show("I","Los datos han sido guardados correctamente","");
			}else if(row[1]=="modificar"){
				mens.show("I","Los cambios han sido guardados correctamente","");
			}
		}else{
			u.img_guardar.style.visibility = "visible";
			mens.show("A","Hubo un error al guardar "+datos,"¡Atención!")
		}
	}
function obtener(nlicencia,sltlicencia,vigencia,lentes){
	if(nlicencia!='' && sltlicencia!='' && vigencia!=''){
		u.nlicencia.value=nlicencia;
		u.sltlicencia.value=sltlicencia;
		u.vigencia.value=vigencia;
		u.lentes.value=lentes;
		u.imgDatosLicencia.style.visibility="visible";
	}else{
		u.nlicencia.value="";
		u.sltlicencia.value="";
		u.vigencia.value="";
		u.lentes.value="";
		u.ChLicencia.checked=false;
		u.imgDatosLicencia.style.visibility="hidden";
	}
}
	function obtenerDatosLicencia(){
		if(u.ChLicencia.checked==true){
			u.imgDatosLicencia.style.visibility="visible";
		}
	}
	function CalogoEmpleadoColonia(cp,colonia,poblacion,municipio,estado,pais){
		u.cp.value=cp;
		u.celcolonia.innerHTML=var_input;
		u.colonia.value=colonia;
		u.poblacion.value=poblacion;
		u.municipio.value=municipio;
		u.estado.value=estado;
		u.pais.value=pais;	
		u.celular.focus();
	}
	function validarLicencia(){	
		if(u.ChLicencia.checked==true){
	abrirVentanaFija('datoslicencia.php?empleado='+u.empleado.value+'&nlicencia='+u.nlicencia.value+'&sltlicencia='+u.sltlicencia.value+'&vigencia='+u.vigencia.value+'&lentes='+u.lentes.value, 500, 400, 'ventana', 'Datos Licencia', 'ValidarLicenciax();');
		}else{
			if(u.accion.value=="modificar"){
			u.imgDatosLicencia.style.visibility="hidden";
			}
		}
	}
	function ValidarLicenciax(){
	if(u.nlicencia.value=="" && u.sltlicencia.value=="" && u.vigencia.value=="" && u.lentes.value==""){
			u.ChLicencia.checked=false;
		}
	}
	function limpiarlicencia(){
		u.nlicencia.value="";
		u.sltlicencia.value="";
		u.vigencia.value="";
		u.lentes.value="";
	}
	
	function ObtenerPuesto(id,puesto){
		u.puesto.value=id;
		u.despuesto.value=puesto;
	}
	function obtenerPuestoEn(id){
		consultaTexto("mostrarPuesto","catalogoempleado_con.php?accion=3&puesto="+id);
	}
	function mostrarPuesto(datos){
		if(datos!="0"){
			var obj = eval(convertirValoresJson(datos));
			u.despuesto.value = obj[0].descripcion;
			u.subcuenta.focus();
		}else{
			u.despuesto.value = "";
			u.puesto.select();
			mens.show("A","El codigo de puesto no existe","¡Atención!","puesto");
		}
	}
	function validarPuesto(e,obj){
		tecla = (u) ? e.keyCode : e.which;
	    if((tecla == 8 || tecla == 46) && document.getElementById(obj).value==""){
			document.getElementById('despuesto').value=""; 
		}
	}
	function CodigoPostal(e,cp){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla==13 && cp!=""){
			consulta("mostrarPostal","ConsultaCodigoPostal.php?accion=1&cp="+cp+"&sid="+Math.random());
		
		}	
	}
	function mostrarPostal(datos){
		
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;		
		u.colonia.value=""; u.poblacion.value=""; u.municipio.value=""; u.estado.value=""; u.pais.value="";				
		
	if(con>0){				
		if(datos.getElementsByTagName('total').item(0).firstChild.data>1){
			u.celcolonia.innerHTML = combo1;
			var combo = u.colonia;		
			combo.options.length = null;
			
			uOpcion = document.createElement("OPTION");
			uOpcion.value=0;
			uOpcion.text="..:: Selecciona ::..";
			combo.add(uOpcion);
		var total =datos.getElementsByTagName('total').item(0).firstChild.data;
			for(i=0;i<total;i++){	
				uOpcion = document.createElement("OPTION");
				uOpcion.value=datos.getElementsByTagName('colonia').item(i).firstChild.data;
				uOpcion.text=datos.getElementsByTagName('colonia').item(i).firstChild.data;
				combo.add(uOpcion);
			}
		u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;
		u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
		u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;
		u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;
		}else{		
			
		u.celcolonia.innerHTML = Input;
		u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;
		u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
		u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;
		u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;
		}
		}else{
			u.imagen.style.visibility="hidden";
			mens.show('A','El C&oacute;digo Postal no existe','¡Atención!','cp');
			u.celcolonia.innerHTML = Input;
			
		}
}
	function ValidarCP(){
		if(u.cp.value==''){
			mens.show('A','Debe capturar un codigo postal', '¡Atención!','cp');
		}else if(u.poblacion.value=='' && u.municipio.value==''){
			mens.show('A','Codigo Postal no existe', '¡Atención!','cp');
		}
	}

	function ValidaRfc(rfcStr) {
		var strCorrecta;
		strCorrecta = rfcStr;
		var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	
		var validRfc=new RegExp(valid);
		var matchArray=strCorrecta.match(validRfc);
		if (matchArray==null) {
			return false;
		}else{
		return true;
		}
		
	}
	function validarBaja(obj){
		if(u.accion.value=="modificar"){
			displayCalendar(Baja,'yyyy/mm/dd',obj);
		}else{
			mens.show('A','El Empleado debe estar registrado','¡Atención!','Baja');
		}	
	}
	function ValidarBaja(){
		if(u.Baja.value!=""){			
			abrirVentanaFija('CatalogoMotivosBaja.php', 550, 400, 'ventana', 'Catalogo Empleado');
		}
	}
	function OptenerMotivos(dato){
		if(dato!=""){
			u.motivos.value=dato;
			u.imgMotivoBaja.style.visibility="visible";
			u.Baja.style.width="70px";
			u.imgMotivoBaja.width='24';
		}else{
			u.Baja.value="";
			u.motivos.value="";
			u.imgMotivoBaja.style.visibility="hidden";
			u.Baja.style.width="";
			u.imgMotivoBaja.width='1';
		}
	}

	function limpiarmotivos(){
		if(u.Baja.value==""){
			u.motivos.value="";	
		}
	}
	
	function validaCP(e,obj){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla==8 && document.getElementById(obj).value=="" || tecla==46){
	u.colonia.value=""; u.poblacion.value=""; u.municipio.value=""; u.estado.value=""; u.pais.value="";
		}
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

	function isEmailAddress(theElement, nombre_del_elemento ){
		var s = theElement.value;
		var filter=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
		if (s.length == 0 ) return true;
		if (filter.test(s))
		return true;
		else
		return false;
	}

	function tabular(e,obj){
		tecla = (u) ? e.keyCode : e.which;
		if(tecla!=13) return;
			frm=obj.form;
			for(i=0;i<frm.elements.length;i++) 
				if(frm.elements[i]==obj){ 
				if (i==frm.elements.length-1) 
				i=-1;
			break 
		}
		if (frm.elements[i+1].disabled ==true )    
			tabular(e,frm.elements[i+1]);
		else if (frm.elements[i+1].readOnly ==true )    
			tabular(e,frm.elements[i+1]);
		else frm.elements[i+1].focus();
		return false;
	}  

	function limpiar(){
		u.empleado.value="";		u.sucursal.value="0";
		u.Grsexo[0].checked=false;	u.Grsexo[1].checked=false;
		u.slecivil.value="0";		u.nombre.value="";
		u.apaterno.value="";		u.amaterno.value="";
		u.rfc.value="";				u.curp.value="";
		u.nimss.value="";			u.calle.value="";
		u.numero.value="";			u.colonia.value="";
		u.cp.value="";				u.poblacion.value="";
		u.municipio.value="";		u.estado.value="";
		u.telefono.value="";		u.celular.value="";
		u.mail.value="";			u.lnacimiento.value="";
		u.slfNacimiento.value="";	u.sltContrato.value="0";
		u.Alta.value="";			u.Baja.value="";
		u.Reingreso.value="";		u.BReingreso.value="";
		u.slDepartamento.value="0";	u.slTTrabajo.value="0";
		u.puesto.value=""; 			u.despuesto.value="";	
		u.ChLicencia.value="";		u.subcuenta.value="";
		u.cpagoelectronico.value="";u.nlicencia.value="";
		u.sltlicencia.value="";		u.vigencia.value="";	
		u.lentes.value="";			u.motivos.value=""; 
		u.accion.value="";		 	u.arreglo.value=""; 
		u.user.value=""; 			u.password.value="1234"; 
		u.user1.value=""; 			u.numempleado.value="";
		u.subcuenta.value = "";		u.pais.value = "";
		u.celcolonia.innerHTML = Input;
		u.celularemp.value	= ""; u.ChLicencia.checked = false;
		u.grupos.value	= ".::Seleccione::.";
		u.pestana1.value = "pestana.php";
		u.pestana2.value = "pestana.php";
		u.pestana3.value = "pestana.php";
		u.pestana4.value = "pestana.php";
		u.pestana5.value = "pestana.php";		
		obtenerCodigo();
	}
	function limpiartodo(){
		u.sucursal.value="0";
		u.Grsexo[0].checked=false;	u.Grsexo[1].checked=false;
		u.slecivil.value="0";		u.nombre.value="";
		u.apaterno.value="";		u.amaterno.value="";
		u.rfc.value="";				u.curp.value="";
		u.nimss.value="";			u.calle.value="";
		u.numero.value="";			u.colonia.value="";
		u.cp.value=""; 				u.poblacion.value="";	
		u.municipio.value="";		u.estado.value="";
		u.telefono.value=""; 		u.celular.value="";
		u.mail.value="";			u.lnacimiento.value="";
		u.slfNacimiento.value=""; 	u.sltContrato.value="0";
		u.Alta.value="";			u.Baja.value="";
		u.Reingreso.value="";		u.BReingreso.value="";
		u.slDepartamento.value="0";	u.slTTrabajo.value="0";
		u.puesto.value="";			u.despuesto.value="";
		u.ChLicencia.value="";		u.subcuenta.value="";
		u.cpagoelectronico.value="";u.nlicencia.value="";
		u.sltlicencia.value="";		u.vigencia.value="";
		u.lentes.value="";			u.motivos.value=""; 
		u.arreglo.value=""; 		u.user.value=""; 
		u.password.value=""; 		u.user1.value="";
		u.numempleado.value=""; 	u.subcuenta.value = "";
		u.celularemp.value	= "";	u.ChLicencia.checked = false;
		u.grupos.value	= ".::Seleccione::.";
		u.pestana1.value = "pestana.php";
		u.pestana2.value = "pestana.php";
		u.pestana3.value = "pestana.php";
		u.pestana4.value = "pestana.php";
		u.pestana5.value = "pestana.php";
	}

	function BuscarEmpleado(id){
		u.empleado.value=id;
		u.accion.value="modificar";
		consultaTexto("mostrarEmpleado","catalogoempleado_con.php?accion=4&id="+id+"&s="+Math.random());	
	}

	function mostrarEmpleado(datos){
		limpiartodo();
		var obj = eval(convertirValoresJson(datos));
		
		u.numempleado.value	= obj[0].numempleado;
		u.sucursal.value =		obj[0].sucursal;
		if(obj[0].sexo=="H"){
			u.Grsexo[0].checked=true;		
		}else{
			u.Grsexo[1].checked=true;
		}		
		u.slecivil.value = obj[0].estadocivil;
		u.nombre.value = obj[0].nombre;
		u.apaterno.value = obj[0].apellidopaterno;
		u.amaterno.value = obj[0].apellidomaterno;
		u.rfc.value = obj[0].rfc;
		u.curp.value = obj[0].curp;
		u.nimss.value = obj[0].nimss;
		u.calle.value = obj[0].calle;
		u.numero.value = obj[0].numero;				
		u.celcolonia.innerHTML = Input;		
		u.colonia.value = obj[0].colonia;
		u.cp.value = obj[0].cp;
		u.poblacion.value = obj[0].poblacion;
		u.municipio.value = obj[0].municipio;
		u.estado.value = obj[0].estado;
		u.pais.value = obj[0].pais;
		u.telefono.value = obj[0].telefono;
		u.celular.value = obj[0].celular;	
		u.celularemp.value = obj[0].celularemp;		
		u.mail.value = obj[0].email;
		u.grupos.value = obj[0].grupo
		
		u.lnacimiento.value = obj[0].lugarnacimiento;
		u.slfNacimiento.value = obj[0].fechanacimiento;
		u.sltContrato.value = obj[0].tipocontrato;
		if(obj[0].alta=="00/00/0000"){
			u.Alta.value = "";
		}else{
			u.Alta.value = obj[0].alta;
		}
				
		u.Baja.readOnly=false;
		u.Baja.style.backgroundColor='';
		u.Reingreso.readOnly=false;
		u.Reingreso.style.backgroundColor='';
		u.BReingreso.readOnly=false;
		u.BReingreso.style.backgroundColor='';		
				
		if(obj[0].baja=="00/00/0000"){
			u.Baja.value =""; limpiarmotivos(); u.imgMotivoBaja.style.visibility="hidden";
		}else{
			u.Baja.value = obj[0].baja; u.imgMotivoBaja.style.visibility="visible";
		}
		if(obj[0].reingreso=="00/00/0000"){
			u.Reingreso.value ="";
		}else{
			u.Reingreso.value = obj[0].reingreso;
		}
		if(obj[0].bajareingreso=="00/00/0000"){
			u.BReingreso.value ="";
		}else{
			u.BReingreso.value = obj[0].bajareingreso;
		}
				
		u.slDepartamento.value = obj[0].departamento;
		u.slTTrabajo.value = obj[0].turno;
		u.puesto.value = obj[0].puesto;
		u.despuesto.value = obj[0].descripcionpuesto;
			
		if(obj[0].licenciamanejo==1){
			u.ChLicencia.checked= true; 
			u.ChLicencia.value=obj[0].licenciamanejo;
			u.imgDatosLicencia.style.visibility="visible";
			obtenerDatosLicencia();
		}else{
			u.ChLicencia.checked=false;
			u.imgDatosLicencia.style.visibility="hidden";
		}		
		u.subcuenta.value= obj[0].subcuentacontable;		
		u.cpagoelectronico.value = obj[0].pagoelectronico;
		u.nlicencia.value = obj[0].licencia;
		u.sltlicencia.value = obj[0].tipolicencia;		
		if(obj[0].vigencia=="00/00/0000"){	
			u.vigencia.value = "";
		}else{
			u.vigencia.value = obj[0].vigencia;
		}		
		u.lentes.value = obj[0].lentes;
		u.motivos.value = obj[0].motivos;		
		u.user.value = obj[0].user;
		u.user1.value = obj[0].user;
		u.password.value = obj[0].password;
		
		if(obj[0].activado=='1'){
			u.activa.checked=true;
			u.activa.value=1;
		}else{
			u.activa.checked=false;
			u.activa.value=0;
		}
		
		u.pestana1.value = obj[0].pestana1;
		u.pestana2.value = obj[0].pestana2;
		u.pestana3.value = obj[0].pestana3;
		u.pestana4.value = obj[0].pestana4;
		u.pestana5.value = obj[0].pestana5;
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
	
	function obtenerUsuario(nombre){
		if(nombre!=""){
		consultaTexto("mostrarResultado","Consulta.php?accion=2&Usuario="+nombre.toUpperCase());		
		}else{
		u.resultado.innerHTML = "";	
		}
	}
	function validarFecha(e,param,name){
		tecla = (document.all) ? e.keyCode : e.which;
		if(tecla == 13 || tecla == 9){
			if(param!=""){
				var mes  =  parseInt(param.substring(3,5),10);
				var dia  =  parseInt(param.substring(0,2),10);
				if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
					mens.show('A','La fecha no es valida', '¡Atención!',name);
					return false;
				}
				if (dia>"31" || dia=="0" ){
					mens.show('A','La fecha no es valida, capture correctamente el Dia', '¡Atención!',name);
					return false;	
				}
				if (mes>"12" || mes=="0" ){
					mens.show('A','La fecha no es valida, capture correctamente el Mes', '¡Atención!',name);
					return false;	
				}	
			}	
		}
	}
	function mostrarResultado(datos){
			u.resultado.innerHTML = datos;
		}
		
	function devolverColonia(){		
		if(u.coloniaid.value==""){
			setTimeout("devolverColonia()",500);
		}else{
			consultaTexto("mostrarColonia","../../buscadores_generales/consultaColonia.php?accion=1&colonia="+u.coloniaid.value);
		}
	}
	
	function mostrarColonia(datos){
		var obj = eval(convertirValoresJson(datos));				
		document.getElementById('cp').value			= obj[0].codigopostal;
		document.all.celcolonia.innerHTML 			= Input;
		document.getElementById('colonia').value	= obj[0].colonia;
		document.getElementById('poblacion').value	= obj[0].poblacion;
		document.getElementById('municipio').value	= obj[0].municipio;
		document.getElementById('estado').value		= obj[0].estado;
		document.getElementById('pais').value		= obj[0].pais;
		setTimeout("document.getElementById('telefono').focus()",500);
	}
	/*function obtenerColoniaValida(){
		consultaTexto("coloniaValida","../../buscadores_generales/consultaColonia.php?accion=2&colonia="+u.colonia.value);
	}
	function coloniaValida(datos){
		if(datos.indexOf("no")>-1){
			if(u.colonia.value!=""){
				u.coloniaid.value="";
				u.colonia.value="";
				document.getElementById('cp').value=""; document.getElementById('poblacion').value="";
				document.getElementById('municipio').value=""; document.getElementById('estado').value="";
				document.getElementById('pais').value="";
				mens.show("A","La Colonia no existe","¡Atención!","colonia");
				return false;
			}
		}
	}*/
	
	function obtenerColoniaValida(){
		if(u.colonia_hidden.value==""){
			alerta2("Debe capturar una colonia valida","¡Atención!","colonia");
			return false;
		}
		consultaTexto("coloniaValida","../../buscadores_generales/consultaColonia.php?accion=2&colonia="+u.colonia.value
		+"&idcolonia="+u.colonia_hidden.value+"&val="+Math.random());
	}
	function coloniaValida(datos){
		if(datos.indexOf("noexiste_xx_xxx")<0){
			var obj = eval("("+datos+")");
			document.getElementById('cp').value			= obj.codigopostal;
			document.all.celcolonia.innerHTML 			= Input;
			document.getElementById('colonia').value	= obj.colonia;
			document.getElementById('poblacion').value	= obj.poblacion;
			document.getElementById('municipio').value	= obj.municipio;
			document.getElementById('estado').value		= obj.estado;
			document.getElementById('pais').value		= obj.pais;
			setTimeout("document.getElementById('telefono').focus()",500);
		}else{
			u.coloniaid.value="";
				u.colonia.value="";
				document.getElementById('cp').value=""; document.getElementById('poblacion').value="";
				document.getElementById('municipio').value=""; document.getElementById('estado').value="";
				document.getElementById('pais').value="";
				alerta("La Colonia no existe","¡Atención!","colonia");
				return false;
		}		
	}
	
	function validarColonia(e,obj){
		tecla	=	(document.all) ? e.keyCode : e.which;
		if((tecla==8 || tecla==46) && document.getElementById(obj).value==""){
			document.getElementById('cp').value=""; document.getElementById('poblacion').value="";
			document.getElementById('municipio').value=""; document.getElementById('estado').value="";
			document.getElementById('pais').value="";
		}	
	}
	function foco(nombrecaja){
		if(nombrecaja=="puesto"){
			u.oculto.value="1";
		}else if(nombrecaja=="empleado"){
			u.oculto.value="2";
		}else if(nombrecaja=="colonia"){
			u.oculto.value="3";
		}
	}
	shortcut.add("Ctrl+b",function() {
		if(u.oculto.value=="1"){
		abrirVentanaFija('CatalogoEmpleadoBuscarPuesto.php', 550, 350, 'ventana', 'Busqueda');
		}else if(u.oculto.value=="2"){
		abrirVentanaFija('BuscarEmpleado.php', 550, 430, 'ventana', 'Busqueda')
		}else if(u.oculto.value=="3"){
		abrirVentanaFija('CatalogoEmpleadoBuscarColonia.php', 570, 350, 'ventana', 'Busqueda')
		}
	});
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:210px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:1em;
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
.Tablas {	font-size:9px;
	text-transform: uppercase;
}
.Tablas {font-size:9px;
text-transform:uppercase;
}
-->
</style>
<link href="../../catalogos/empleado/Tablas.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo8 {font-size: 9px; font-weight: bold; }
-->
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">CATALOGO EMPLEADOS</td>
    </tr>
    <tr>
      <td><table width="499" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="71">Codigo:</td>
          <td width="147"><span class="Tablas">
            <input name="empleado" type="text" class="Tablas" id="empleado" value="<?=$empleado ?>" onfocus="foco(this.name)" onblur="document.all.oculto.value=''" readonly="readonly" style="background-color:#FFFF99; width:80px" />
            <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('../../buscadores_generales/buscarEmpleadoGen.php?funcion=BuscarEmpleado', 700, 450, 'ventana', 'Busqueda')" />
            <label></label>
          </span></td>
          <td width="102"># Empleado: </td>
          <td width="179"><input name="numempleado" onkeypress="if(event.keyCode==13){document.all.sucursal.focus();}" type="text" class="Tablas" id="numempleado"  value="<?=$numempleado?>"  maxlength="30" style="width:120px"/></td>
        </tr>
        <tr>
          <td>Sucursal:</td>
          <td colspan="3"><span class="Tablas">
            <select name="sucursal" id="sucursal" class="Tablas" style="width:200px" onkeydown="return tabular(event,this)" >
              <option value="0">SELECCIONAR SUCURSAL</option>
              <?
					  $sqlt="SELECT id,descripcion FROM catalogosucursal ORDER BY descripcion ASC ";
					  $result=mysql_query($sqlt,$link);
					  while($row=mysql_fetch_array($result)){ 			  
						?>
              <option value="<?=$row[0]?>"  <? if($sucursal==$row[0]){echo "selected";} ?> >
                <?=$row[1]; ?>
              </option>
              <?	}   ?>
            </select>
          </span></td>
          </tr>
        <tr>
          <td>Sexo:</td>
          <td><span class="Tablas"><strong>
            <label>
            <input type="radio" name="Grsexo" value="H" style="width:13px" onkeydown="return tabular(event,this)"   <? if($Grsexo=="H"){ echo'checked'; } ?>>
H </label>
            <strong><strong>
            <input type="radio" name="Grsexo" value="M" style="width:13px" onkeydown="return tabular(event,this)" <? if($Grsexo=="M"){ echo'checked'; } ?>>
            </strong>M </strong></strong>
              <label></label>
          </span></td>
          <td>Estado Civil: </td>
          <td><strong><span class="Estilo8">
            <select name="slecivil" class="Tablas" id="slecivil" style="width:125px" onkeydown="return tabular(event,this)"   >
              <option value="0" >Seleccionar</option>
              <option value="CASADO" <? if($slecivil=="CASADO"){ echo'selected'; } ?> >Casado</option>
              <option value="SOLTERO" <? if($slecivil=="SOLTERO"){ echo'selected'; } ?> >Soltero</option>
              <option value="UNION LIBRE" <? if($slecivil=="UNION LIBRE"){ echo'selected'; } ?> >Union Libre</option>
              <option value="DIVORCIADO" <? if($slecivil=="DIVORCIADO"){ echo'selected'; } ?> >Divorciado</option>
              <option value="VIUDO" <? if($slecivil=="VIUDO"){ echo'selected'; } ?> >Viudo</option>
            </select>
          </span></strong></td>
        </tr>
        <tr>
          <td>Nombre:</td>
          <td colspan="3"><span class="Tablas">
            <input name="nombre" type="text" class="Tablas" id="nombre" onkeypress="return tabular(event,this)" value="<?=$nombre ?>" maxlength="50" style="width:370px" />
          </span></td>
          </tr>
        <tr>
          <td>Ap. Paterno: </td>
          <td><span class="Tablas">
            <input name="apaterno" type="text" class="Tablas" id="apaterno"  onkeypress="return tabular(event,this)" value="<?=$apaterno ?>" style="width:120px" maxlength="30" />
          </span></td>
          <td>Ap. Materno: </td>
          <td><span class="Tablas">
            <input name="amaterno" type="text" class="Tablas" id="amaterno" onkeypress="return tabular(event,this)" value="<?=$amaterno ?>" maxlength="30" style="width:120px"/>
          </span></td>
        </tr>
        <tr>
          <td>R.F.C.:</td>
          <td><span class="Tablas">
            <input name="rfc" type="text" class="Tablas" id="rfc" style="width:120px"   onkeypress="return tabular(event,this)" value="<?=$rfc ?>" maxlength="15"/>
          </span></td>
          <td>CURP:</td>
          <td><span class="Tablas">
            <input name="curp" type="text" class="Tablas" id="curp" onkeypress="return tabular(event,this)" value="<?=$curp ?>" maxlength="20" style="width:120px" />
          </span></td>
        </tr>
        <tr>
          <td>NIMSS:</td>
          <td><span class="Tablas">
            <input name="nimss" type="text" class="Tablas" style="width:120px" id="nimss" onkeypress="return tabular(event,this)" value="<?=$nimss ?>" maxlength="20" />
          </span></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Calle:</td>
          <td><span class="Tablas">
            <input name="calle" type="text" class="Tablas" style="width:120px" id="calle" value="<?=$calle ?>" onkeypress="return tabular(event,this)" />
          </span></td>
          <td>Numero:</td>
          <td><span class="Tablas">
            <input name="numero" type="text" class="Tablas" id="numero" style="width:120px"  onkeypress="return tabular(event,this)" value="<?=$numero ?>"  maxlength="10" />
          </span></td>
        </tr>
        <tr>
          <td colspan="4"><table width="499" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="70">CP.:</td>
              <td width="147"><input name="cp" class="Tablas" type="text" id="cp" value="<?=$cp; ?>" style="width:80px"  maxlength="5" onkeypress="return solonumeros(event)" onBlur="trim(document.getElementById('cp').value,'cp'); " onKeyDown="CodigoPostal(event, this.value); return tabular(event,this); " onKeyUp="return validaCP(event,this.name)" /></td>
              <td width="103">Colonia:</td>
              <td width="179" id="celcolonia"><input name="colonia" type="text" class="Tablas" id="colonia" style=" width:120px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'../../buscadores_generales/ajax-list-colonias.php'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=''){setTimeout('obtenerColoniaValida()',1000);}" /></td>
              </tr>
            <tr>
              <td>Poblaci&oacute;n:</td>
              <td><input class="Tablas" name="poblacion" type="text" id="poblacion"  style="width:120px;background:#FFFF99" readonly=""  value="<?= $poblacion; ?>" /></td>
              <td>Mun. / Del.: </td>
              <td><input name="municipio" type="text" id="municipio"  class="Tablas" style="width:120px;background:#FFFF99" readonly="" value="<?= $municipio; ?>" /></td>
              </tr>
            <tr>
              <td>Estado:</td>
              <td><input name="estado" type="text" id="estado"  value="<?= $estado; ?>" class="Tablas" style="width:120px;background:#FFFF99" readonly="" /></td>
              <td>Pa&iacute;s:</td>
              <td><input name="pais" type="text" id="pais"  value="<?= $pais; ?>" class="Tablas" style="width:120px;background:#FFFF99" readonly=""/></td>
              </tr>
          </table></td>
          </tr>
        <tr>
          <td>Cel. Personal: </td>
          <td><span class="Tablas">
            <input name="celular" type="text" class="Tablas" id="celular" onkeypress="return solonumeros(event)" style="width:120px"  onkeydown="return tabular(event,this)" value="<?=$celular ?>" maxlength="20" />
          </span></td>
          <td>Telefono:</td>
          <td><span class="Tablas">
            <input name="telefono" type="text" class="Tablas" id="telefono"  onkeypress="return solonumeros(event)" onkeydown="return tabular(event,this)" value="<?=$telefono ?>" style="width:120px" maxlength="20"/>
          </span></td>
        </tr>
        <tr>
          <td>Email:</td>
          <td><span class="Tablas">
            <input name="mail" type="text" class="Tablas"  id="mail" onkeydown="return tabular(event,this)" value="<?=$mail ?>" style="width:120px" maxlength="50"   s="s"/>
          </span></td>
          <td>Cel. Empresa: </td>
          <td><span class="Tablas">
            <input name="celularemp" type="text" class="Tablas" id="celularemp" onkeypress="return solonumeros(event)" onkeydown="return tabular(event,this)" value="<?=$celularemp ?>" style="width:120px"  maxlength="20" />
          </span></td>
        </tr>
        <tr>
          <td>Lugar Nac.: </td>
          <td><span class="Tablas">
            <input name="lnacimiento" type="text" class="Tablas" id="lnacimiento" onkeydown="return tabular(event,this)" value="<?=$lnacimiento ?>" style="width:120px" maxlength="30" />
          </span></td>
          <td>F. Nacimiento: </td>
          <td><input name="slfNacimiento" type="text" class="Tablas" id="slfNacimiento" onKeyPress="validarFecha(event,this.value,this.name); return tabular(event,this)" value="<?=$slfNacimiento ?>" style="width:120px" maxlength="30" />
            <img src="../../img/calendario.gif" alt="Baja" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.forms[0].slfNacimiento,'dd/mm/yyyy',this)" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><span class="Tablas">
            <input type="hidden" id="colonia_hidden" name="coloniaid" />
          </span></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td class="FondoTabla">Datos del Puesto </td>
    </tr>
    <tr>
      <td><table width="499" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2">Tipo Contrato: <span class="Tablas">
            <select name="sltContrato" class="Tablas" id="sltContrato" style="width:120px" onkeydown="return tabular(event,this)">
              <option value="0">Selecciona</option>
              <option value="PERMANENTE" <? if($sltContrato=="PERMANENTE"){ echo'selected'; } ?> >Permanente</option>
              <option value="TEMPORAL" <? if($sltContrato=="TEMPORAL"){ echo'selected'; } ?> >Temporal</option>
            </select>
          </span></td>
          <td width="64">Alta:</td>
          <td width="178"><span class="Tablas">
            <input name="Alta" type="text" class="Tablas" id="Alta" onkeypress="return solonumeros(event)" onkeydown="return tabular(event,this)"  value="<?=$Alta ?>" size="15"/>
            <img src="../../img/calendario.gif" alt="Alta" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="displayCalendar(document.forms[0].Alta,'dd/mm/yyyy',this)" /></span></td>
        </tr>
        <tr>
          <td width="93">Baja:</td>
          <td width="164"><span class="Tablas">
            <input name="Baja" type="text" class="Tablas" id="Baja" onblur="limpiarmotivos()"  onchange="ValidarBaja();" onkeypress="return solonumeros(event)" onkeydown="return tabular(event,this)" value="<?=$Baja ?>" size="15" <? if($accion!="modificar"){echo "readonly=\"readonly\"   style=\"background-color:#FF9\"";} ?> />
            <img src="../../img/calendario.gif" alt="Baja" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="if(document.all.accion.value=='modificar'){displayCalendar(Baja,'dd/mm/yyyy',this);}else{mens.show('A','El Empleado debe estar dado de Alta','&iexcl;Atenci&oacute;n!','Baja')}" /> <img src="../../img/guia_azul_32.gif" alt="Motivos Baja" name="imgMotivoBaja" width="20" height="20"  align="absbottom"  id="imgMotivoBaja" style="cursor:pointer;visibility:<? if($Baja!=""){echo 'visible';}else{echo 'hidden';} ?>;" onclick="abrirVentanaFija('CatalogoMotivosBaja.php?motivos='+u.motivos.value, 350, 300, 'ventana', 'Motivos Baja');" /> </span></td>
          <td>Reingreso:</td>
          <td><input name="Reingreso" type="text"  class="Tablas" id="Reingreso" onkeypress="return solonumeros(event)" onkeydown="return tabular(event,this)"  value="<?=$Reingreso ?>" size="15" <? if($accion!="modificar"){echo "readonly=\"readonly\"   style=\"background-color:#FF9\"  ";} ?> />
              <img src="../../img/calendario.gif" alt="Reingreso" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="if(document.all.accion.value=='modificar'){displayCalendar(Reingreso,'dd/mm/yyyy',this)}else{mens.show('A','El Empleado debe estar registrado','&iexcl;Atenci&oacute;n!','Reingreso')}" /></td>
        </tr>
        <tr>
          <td>Baja Reingreso: </td>
          <td><input name="BReingreso" type="text"  class="Tablas" id="BReingreso"  onkeypress="return solonumeros(event)" onkeydown="return tabular(event,this)" value="<?=$BReingreso ?>" size="15" <? if($accion!="modificar"){echo "readonly=\"readonly\"   style=\"background-color:#FF9\" ";} ?> />
              <img src="../../img/calendario.gif" alt="Baja Reingreso" width="20" height="20" align="absbottom" style="cursor:pointer" title="Calendario" onclick="if(document.all.accion.value=='modificar'){displayCalendar(BReingreso,'dd/mm/yyyy',this)}else{mens.show('A','El Empleado debe estar registrado','&iexcl;Atenci&oacute;n!','BReingreso')}" /></td>
          <td>Departamento:</td>
          <td><span class="Tablas">
            <select name="slDepartamento" class="Tablas" id="slDepartamento" style="width:120px" onkeydown="return tabular(event,this)">
              <option value="0">Seleccionar</option>
              <option value="ADMINISTRATIVO" <? if($slDepartamento=="ADMINISTRATIVO"){ echo'selected'; } ?> >Administrativo</option>
              <option value="OPERATIVO" <? if($slDepartamento=="OPERATIVO"){ echo'selected'; } ?> >Operativo</option>
            </select>
          </span></td>
        </tr>
        <tr>
          <td>T. de Trabajo: </td>
          <td><span class="Tablas">
            <select name="slTTrabajo" class="Tablas" id="slTTrabajo" style="width:120px" onkeydown="return tabular(event,this)">
              <option value="0">Selecciona</option>
              <option value="MATUTINO"  <? if($slTTrabajo=="MATUTINO"){ echo'selected'; } ?>  >Matutino</option>
              <option value="VESPERTINO" <? if($slTTrabajo=="VESPERTINO"){ echo'selected'; } ?>>Vespertino</option>
              <option value="NOCTURNO" <? if($slTTrabajo=="NOCTURNO"){ echo'selected'; } ?>  >Nocturno</option>
            </select>
          </span></td>
          <td colspan="2"><label>
            <input name="ChLicencia" type="checkbox" class="Tablas" id="ChLicencia" value="1" onclick="validarLicencia()" onkeydown="return tabular(event,this)" style="width:13px"  <? if($ChLicencia=='1'){echo "checked";} ?> />
            </label>
            Licencia de Manejo <strong><img src="../../img/guia_azul_32.gif" alt="Datos Licencia" name="imgDatosLicencia" width="20" height="20" align="absbottom" id="imgDatosLicencia" style=" cursor:pointer; visibility:<? if($ChLicencia=='1'){echo 'visible';}else{echo 'hidden';} ?>" onclick="abrirVentanaFija('datoslicencia.php?empleado='+document.form1.empleado.value+'&amp;nlicencia='+document.form1.nlicencia.value+'&amp;sltlicencia='+document.form1.sltlicencia.value+'&amp;vigencia='+document.form1.vigencia.value+'&amp;lentes='+document.form1.lentes.value, 550, 400, 'ventana', 'Datos Licencia','ValidarLicenciax();');" /></strong></td>
        </tr>
        <tr>
          <td>Puesto:</td>
          <td colspan="3"><input name="puesto" type="text" class="Tablas" onfocus="foco(this.name)" onblur="document.all.oculto.value=''" onkeypress="if(event.keyCode==13){obtenerPuestoEn(this.value);}return solonumeros(event);" onkeyup="return validarPuesto(event,this.name)"  id="puesto" style="width:70px" value="<?=$puesto ?>" />
              <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('CatalogoEmpleadoBuscarPuesto.php', 600, 550, 'ventana', 'Busqueda');" /><span class="Tablas">
              <input name="despuesto" type="text" class="Tablas" id="despuesto" value="<?=$despuesto ?>"  readonly="readonly" style="background-color:#FF9; width:250px" onkeydown="return tabular(event,this)" />
            </span></td>
        </tr>
        <tr>
          <td>Subcuenta Cble: </td>
          <td><span class="Tablas">
            <input name="subcuenta" type="text" class="Tablas" id="subcuenta" value="<?=$subcuenta ?>" size="20" onkeydown="return tabular(event,this)" />
          </span></td>
          <td colspan="2">Cta. Pago Contable:<span class="Tablas">
            <input name="cpagoelectronico" type="text" class="Tablas" id="cpagoelectronico" value="<?=$cpagoelectronico ?>" size="15" onkeydown="return tabular(event,this)" />
          </span> </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td class="FondoTabla">Configurador de Pesta&ntilde;as  </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15%">Pesta&ntilde;a1:</td>
          <td width="35%"><select style="width:150px" id="pestana1" name="pestana1" class="Tablas">
                    	<option value="pestana.php">Default</option>
                    	<option value="../../carteraMorosa/actividadesUsuario.php">Agenda de Trabajo</option>
						<option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
                        <option value="../../catalogos/cliente/client.php">Clientes</option>
                        <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
						<option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
						<option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
           	</select></td>
          <td width="14%">Pesta&ntilde;a2:</td>
          <td width="36%"><select style="width:150px" id="pestana2" name="pestana2" class="Tablas">
            <option value="pestana.php">Default</option>
			<option value="../../carteraMorosa/actividadesUsuario.php">Agenda de Trabajo</option>
            <option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
            <option value="../../catalogos/cliente/client.php">Clientes</option>
            <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
            <option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
            <option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
          </select></td>
        </tr>
        <tr>
          <td>Pesta&ntilde;a3:</td>
          <td><select style="width:150px" id="pestana3" name="pestana3" class="Tablas">
            <option value="pestana.php">Default</option>
			<option value="../../carteraMorosa/actividadesUsuario.php">Agenda de Trabajo</option>
            <option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
            <option value="../../catalogos/cliente/client.php">Clientes</option>
            <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
            <option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
            <option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
          </select></td>
          <td>Pesta&ntilde;a4:</td>
          <td><select style="width:150px" id="pestana4" name="pestana4" class="Tablas">
            <option value="pestana.php">Default</option>
			<option value="../../carteraMorosa/actividadesUsuario.php">Agenda de Trabajo</option>
            <option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
            <option value="../../catalogos/cliente/client.php">Clientes</option>
            <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
            <option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
            <option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
          </select></td>
        </tr>
        <tr>
          <td>Pesta&ntilde;a5:</td>
          <td><select style="width:150px" id="pestana5" name="pestana5" class="Tablas">
            <option value="pestana.php">Default</option>
			<option value="../../carteraMorosa/actividadesUsuario.php">Agenda de Trabajo</option>
            <option value="../../guias/guia.php?funcion2=mostrarEvaluaciones()">Guias de Ventanilla</option>
            <option value="../../catalogos/cliente/client.php">Clientes</option>
            <option value="../../convenio/propuestaconvenio.php">Propuesta Convenios</option>
            <option value="../../convenio/generacionconvenio.php">Generacion Convenios</option>
            <option value="../../convenio/solicitudContratoAperturaCredito.php">Solicitud Credito</option>
          </select></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td class="FondoTabla">Datos de Inicio de Sesi&oacute;n</td>
    </tr>
    <tr>
      <td><table width="499" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="61">Usuario:</td>
          <td width="187"><span class="Tablas">
            <input name="user" type="text" class="Tablas" id="user" onkeydown="return tabular(event,this)" onkeyup="obtenerUsuario(this.value);" value="<?=$user ?>" size="20" maxlength="30" />
            <input name="user1" type="hidden" id="user1" value="<?=$user1 ?>" />
          </span></td>
          <td width="77">Contrase&ntilde;a:</td>
          <td width="174"><span class="Tablas">
            <input name="password" type="password" class="Tablas" id="password" onkeydown="return tabular(event,this)" value="1234" size="20" maxlength="30" />
          </span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><div id='resultado'></div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Perfil:</td>
          <td>
          <select name="grupos" class="Tablas" style="width:150px; text-transform:uppercase">
                    	<option value="0">.::Seleccione::.</option>
                        <?
							$s = "select * from permisos_grupos";
							$r = mysql_query($s,$link) or die($s);
							while($f = mysql_fetch_object($r)){
						?>
                    	<option value="<?=$f->id?>"><?=cambio_texto($f->nombre)?></option>
                        <?
							}
						?>
              </select>          </td>
          <td>&nbsp;</td>
          <td><input name="activa" type="checkbox" id="activa" onclick="if(document.all.activa.checked==true){document.all.activa.value=1;}else{document.all.activa.value=0;}" />
            Activa</td>
        </tr>
        <tr>
          <td bgcolor="#EFEFEF" colspan="2" align="center">Huella Digital</td>
          <td bgcolor="#EFEFEF">&nbsp;</td>
          <td bgcolor="#EFEFEF">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center" bgcolor="#EFEFEF">
          	<OBJECT ID="DGhuella"
            CLASSID="CLSID:FB90BEF3-564C-48F7-B391-0EFE14A0BD08" width="121" height="129">
            </OBJECT>
          </td>
          <td colspan="2" bgcolor="#EFEFEF" style="vertical-align:top">
          	<table width="250">
            	<tr>
                	<td>
                    	Lecturas Necesarias
                    </td>
                </tr>
            	<tr>
            	  <td id="lecturas">&nbsp;</td>
          	  </tr>
            </table>
          </td>
          </tr>
        <tr>
        	<td colspan="4" bgcolor="#EFEFEF">
            	<table align="center">
                	<tr>
                    	<td><img src="../../img/Boton_Asignar.gif" onclick="CapturarHuella()" style="cursor:pointer" /></td>
                        <td><img src="../../img/Boton_Verificar.gif" onclick="Verificar()" style="cursor:pointer" /></td>
                        <td><img src="../../img/boton_limpiar.gif" onclick="reiniciarContador()" style="cursor:pointer" /></td>
                    </tr>
                </table>
            </td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td><span class="Tablas">
            <input name="accion" type="hidden" value="<?=$accion ?>"/>
            <input name="nlicencia" type="hidden" value="<?=$nlicencia ?>" />
            <input name="vigencia" type="hidden" value="<?=$vigencia ?>" />
            <input name="lentes" type="hidden" value="<?=$lentes ?>" />
            <input name="sltlicencia" type="hidden" id="sltlicencia" value="<?=$sltlicencia ?>" />
            <input name="arreglo" type="hidden" id="arreglo" value="<?=$arreglo; ?>" />
            <input name="motivos" type="hidden" id="motivos" value="<?=$motivos ?>" />
            <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />
          </span></td>
          <td>&nbsp;</td>
          <td><table width="171" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td><img src="../../img/Boton_Guardar.gif" id="img_guardar" alt="Guardar" width="70" height="20" onclick="validar();" style="cursor:pointer" /></td>
              <td><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')" style="cursor:pointer" /></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  </p>
</form>
</body>
</html>