<?	session_start();
	require_once('../../Conectar.php');
	$link=Conectarse('webpmm');	
	
	$s = "delete from direcciontmp where idusuario = $_SESSION[IDUSUARIO]";
	$r = mysql_query($s,$link) or die($s);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/shortcut.js"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../javascript/ajax.js"></script>
<script language="javascript" src="../../javascript/ClaseTabla.js"></script>
<script language="javascript" src="../../javascript/DataSetSinFiltro.js"></script>
<script src="../../javascript/jquery-1.4.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script language="javascript" type="text/javascript">
	
	jQuery(function($){
	 	$('#vigencia').mask("99/99/9999");
	 });
	
	var busco = false;
	var u = document.all;
	var combo1 = "<select name='sltsucursales' id='sltsucursales' style='width:210px; font-size:9px' onKeyPress='return tabular(event,this)'>";
	var conv =0;
	var DS1 = new DataSet();
	
	var esModificar = "";
	var tabla1 = new ClaseTabla();
	tabla1.setAttributes({
		nombre:"tabladetalle",
		campos:[
			{nombre:"CALLE", medida:150, alineacion:"left", datos:"calle"},
			{nombre:"NUMERO", medida:50, alineacion:"left", datos:"numero"},
			{nombre:"COLONIA", medida:150, alineacion:"left", datos:"colonia"},
			{nombre:"CRUCE", medida:4, tipo:"oculto", alineacion:"center", datos:"crucecalles"},
			{nombre:"CP", medida:50, alineacion:"center", datos:"cp"},		
			{nombre:"POBLACION", medida:100, alineacion:"left", datos:"poblacion"},
			{nombre:"MUN", medida:4, tipo:"oculto", alineacion:"center", datos:"municipio"},
			{nombre:"ESTADO", medida:4, tipo:"oculto", alineacion:"center", datos:"estado"},
			{nombre:"PAIS", medida:4, tipo:"oculto", alineacion:"center", datos:"pais"},
			{nombre:"TELEFONO", medida:80, alineacion:"left", datos:"telefono"},
			{nombre:"FAX", medida:4, tipo:"oculto", alineacion:"center", datos:"fax"},
			{nombre:"FACT", medida:50, alineacion:"center", datos:"facturacion"},
			{nombre:"ID", medida:5, tipo:"oculto", alineacion:"center", datos:"iddireccion"},
			{nombre:"IDTEM", medida:5, tipo:"oculto", alineacion:"center", datos:"id"}
		],
		filasInicial:15,
		alto:200,
		seleccion:true,
		ordenable:false,	
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});	
	window.onload = function(){
		//u.nick.focus();
		tabla1.create();
		
		DS1.crear({
			'paginasDe':30,
			'objetoTabla':tabla1,
			'objetoPaginador':document.getElementById('direcciones_paginado'),
			'nombreVariable':'DS1',
			'ubicacion':'../../',
			'funcionOrdenar':function(a,b){
				return (a.calle.toUpperCase()>b.calle.toUpperCase())?1:((a.calle.toUpperCase()<b.calle.toUpperCase())?-1:0);
			}
		});
		
		habilitar();		
		obtenerDetalles();
		if(u.clientecorporativo.value!=""){
			obtener(u.clientecorporativo.value);
		}
	}
	
	function obtenerDetalles(){
		var datosTablaDireccion = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;
		if(datosTablaDireccion!=0){			
			for(var i=0; i<datosTablaDireccion.length;i++){
				tabla1.add(datosTablaDireccion[i]);
			}
		}
	}
	
	function agregarVar(miArray){
		var u		= document.all;
		var registro= new Object();
		registro.accion		= 8;
		registro.calle 		= miArray[0];
		registro.numero		= miArray[1];
		registro.crucecalles= miArray[2];
		registro.cp			= miArray[3];
		registro.colonia	= miArray[4];
		registro.poblacion 	= miArray[5];
		registro.municipio	= miArray[6];
		registro.estado		= miArray[7];
		registro.pais		= miArray[8];
		registro.telefono 	= miArray[9];
		registro.fax 		= miArray[10];
		registro.facturacion= miArray[11];
		registro.iddireccion= miArray[12];
		registro.id 		= miArray[13];
		
		var datos = "accion=8&idpagina="+u.idpagina.value+"&calle="+miArray[0]+"&codigo="+u.codigo.value+"&numero="+miArray[1]
		+"&cruce="+miArray[2]+"&cp="+miArray[3]
		+"&colonia="+miArray[4]+"&poblacion="+miArray[5]+"&municipio="+miArray[6]+"&estado="+miArray[7]+"&pais="+miArray[8]
		+"&telefono="+miArray[9]+"&fax="+miArray[10]+"&facturacion="+miArray[11]+"&iddireccion="+miArray[12]+"&id="+miArray[13];
		
		crearLoading();
		$.ajax({
            type:"POST",
            url:"consultasClientes30.php",
            data:datos,
            success:function(msg){
				ocultarLoading();
				try{
					var obj = eval(msg);
				}catch(e){
					return false;	
				}
				if(DS1.buscarYMostrarRevueltos(obj.id,"id")){
					registro.id = obj.id;
					DS1.actualizarRegistroSinMostrar(registro,DS1.indice+1,tabla1.getSelectedIndex());
					DS1.refrescar();
				}else{
					registro.id = obj.id;
					DS1.agregarRegistro(registro);
				}
			}
		});
	}
	
	
	function ValAddFact(miArray){
		if(tabla1.getRecordCount()==0){
			return true;
		}else{
			try{
				if(miArray[11]=="SI" && tabla1.getSelectedRow().facturacion=='SI'){
					return true
				}
			}catch(e){
				e=null;
			}
			
			for(var i=0; i<DS1.registros.length; i++){
				if(DS1.registros[i].facturacion=='SI' && miArray[11]=="SI"){
					if(u.modificarfila.value!="")
						u.modificarfila.value="";
					return false;
				}
				/*if(document.all["tabladetalle_FACT"][i].value=="SI" && miArray[11]=="SI"){
					
				}*/
			}
			
			if(miArray[11]=="NO"){
				if(u.modificarfila.value!="")
					u.modificarfila.value="";
				return true;
			}
			return true;
		}		
	}
	
	function EliminarFila(){
		if(tabla1.getValSelFromField('cp','CP')!=""){
			confirmar('&iquest;Esta seguro de Eliminar la Direcci&oacute;n?','','borrarFila()','');
		}	
	}
	
	function borrarFila(){
		var vidpagina = $("input[name='idpagina']").val();
			
		$.ajax({
			type:"POST",
			url:"consultasClientes30.php",
			data:"accion=10&idfila="+tabla1.getSelectedRow().id+"&idpagina="+vidpagina,
			success:function(resul){
				DS1.borrarRegistro(DS1.indice+1,tabla1.getSelectedIndex());
			}
		});
	}
	
	function ModificarFila(){
		var obj = tabla1.getSelectedRow();
		if(tabla1.getValSelFromField("cp","CP")!=""){
		
		esModificar = "SI";
		var dir = 'direccioncliente.php?calle='+obj.calle
			+'&numero='+obj.numero
			+'&entrecalles='+obj.crucecalles
			+'&cp='+obj.cp
			+'&colonia='+obj.colonia
			+'&poblacion='+obj.poblacion
			+'&municipio='+obj.municipio
			+'&estado='+obj.estado
			+'&pais='+obj.pais
			+'&telefono='+obj.telefono
			+'&fax='+obj.fax
			+'&esmodificar=si&chfacturacion='+obj.facturacion
			+'&id='+obj.id
			+'&iddireccion='+obj.iddireccion;
		
		dir = dir.replace(/#/g,"%23");
		
		abrirVentanaFija(dir, 550, 400, 'ventana', 'DATOS DIRECCION');
			document.all.modificarfila.value	=tabla1.getSelectedIdRow();
				if(obj.fact=='SI'){document.all.valfact.value='1'}
				else{document.all.valfact.value=''}
					
			}
	}
	function ValidaRfc(rfcStr) {
		var strCorrecta;
		strCorrecta = rfcStr;
		
		if(document.all.rdmoral[0].checked==true){
			var valid = '^(([A-Z]|[a-z]|[&]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
			var validRfc=new RegExp(valid);
			var matchArray=strCorrecta.match(validRfc);
			if (matchArray==null) {	
				return false;
			}else{
				return true;
			}	
		}else if(document.all.rdmoral[1].checked==true){
		   //var valid = '^(([A-Z]|[a-z]|[&]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		   return true;
		}
	}

	function obtenerRFC(rfc){
		if(busco==false){
			busco = true;
			consultaTexto("mostrarRfc","consultaCredito_con.php?accion=3&idpagina="+u.idpagina.value+"&codigo="+u.codigo.value+"&rfc="+rfc+"&val="+Math.random());
		}
	}

	function mostrarRfc(datos){	
		if(datos.indexOf("no encontro")<0){	
			var obj = eval("("+convertirValoresJson(datos)+")");
			if(document.all.rdmoral[0].checked==true && obj.rfc.replace("&#32;","")!=""){
				u.rfc_h.value = obj.rfc;
				u.cliente_h.value = obj.cliente;
				u.idcliente_h.value = obj.id;
				confirmar('El R.F.C.:'+u.rfc.value.toUpperCase()
				+' esta asignado al cliente '+obj.cliente.toUpperCase()
				+' ¿Desea ver su información?', '', 'obtenerCliente('+obj.id+')', 'cancelo()');
				return false;
			}
			
			if(document.all.rdmoral[1].checked==true && obj.rfc.replace("&#32;","")!=""){
				u.rfc_h.value = obj.rfc;
				u.cliente_h.value = obj.cliente;
				u.idcliente_h.value = obj.id;
				confirmar('El R.F.C.:'+u.rfc.value.toUpperCase()
				+' esta asignado al cliente '+obj.cliente.toUpperCase()
				+' ¿Desea ver su información?', '', 'obtenerCliente('+obj.id+')', 'cancelo()');
				return false;
			}
		}else{
			busco = false;
			u.email.focus();
		}
	}
	
	function cancelo(){
		busco = false;
		u.email.focus();
	}
function habilitar(){
	if (conv!=1){
		if(document.all.rdmoral[1].checked== true){
			document.getElementById('paterno').disabled=false
			document.getElementById('materno').disabled=false
			if(document.getElementById('paterno').value=="")
				document.getElementById('paterno').value="";
			if(document.getElementById('materno').value=="")
				document.getElementById('materno').value="";
			document.getElementById('paterno').style.backgroundColor='';
			document.getElementById('materno').style.backgroundColor='';
			u.rfc.maxlength=13;
		}else if(document.all.rdmoral[0].checked== true){
			document.getElementById('paterno').disabled=true
			document.getElementById('materno').disabled=true
			document.getElementById('paterno').value="";
			document.getElementById('materno').value="";
			document.getElementById('paterno').style.backgroundColor='#FFFF99';
			document.getElementById('materno').style.backgroundColor='#FFFF99';
			u.rfc.maxlength=12;
		}
	}
}
var nav4 = window.Event ? true : false;
function Numeros(evt){ 
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
var key = nav4 ? evt.which : evt.keyCode; 
return (key <= 13 || (key >= 48 && key <= 57));
}
function validar(){
	<?=$cpermiso->verificarPermiso(279,$_SESSION[IDUSUARIO]);?>
	u.registros.value = tabla1.getRecordCount();
	/*if (document.form1.listnick.value.length == 0){
			alerta('Debe capturar por lo menos un Nick', '¡Atención!','nick');
			return false;
	}else */
	if (document.getElementById('nombre').value==""){
			alerta('Debe capturar Nombre', '¡Atención!','nombre');
			return false;
	}else if(document.form1.rdmoral[1].checked){		
		if(document.getElementById('paterno').value==""){
				alerta('Debe capturar Apellido Paterno', '¡Atención!','paterno');
				return false;				
		}else if(u.rfc_h.value.replace("&#32;","")!="" && u.rfc_h.value.replace("&#32;","") == u.rfc.value){
			alerta3('El R.F.C.:'+u.rfc.value.toUpperCase()+' esta asignado al cliente '+u.cliente_h.value.toUpperCase(), '¡Atención!');
			return false;
		}else if(document.getElementById('email').value!="" && !isEmailAddress(document.form1.email) ){
				alerta('Debe capturar Email valido.', '¡Atención!','email');
				return false;
		}else if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
				alerta3('Debe capturar Por lo menos una Dirección','¡Atención!');
				return false;			
		}else{
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				//document.form1.submit();
			}
			/*else if(document.getElementById('accion').value=="modificar"){
				document.form1.submit();
			}*/
			var informacion = $('form').serialize();
			
			if(informacion.indexOf('&nombre=')<0)
				informacion += "&nombre="+u.nombre.value;
			if(informacion.indexOf('&paterno=')<0)
				informacion += "&paterno="+u.paterno.value;
			if(informacion.indexOf('&materno=')<0)
				informacion += "&materno="+u.materno.value;
			if(informacion.indexOf('&rfc=')<0)
				informacion += "&rfc="+u.rfc.value;
			if(informacion.indexOf('&email=')<0)
				informacion += "&email="+u.email.value;
			if(informacion.indexOf('&celular=')<0)
				informacion += "&celular="+u.celular.value;
			if(informacion.indexOf('&web=')<0)
				informacion += "&web="+u.web.value;
			
			
			$.ajax({
				type:"POST",
				url:"client_30_con.php",
				data:informacion,
				success:resGuardar
			});
		}
	}else if(document.form1.rdmoral[0].checked){
		if(document.getElementById('email').value!="" && !isEmailAddress(document.form1.email)){
				alerta('Debe capturar Email valido.', '¡Atención!','email');
				return false;
		}else if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
				alerta3('Debe capturar Por lo menos una Dirección','¡Atención!');
				return false;			
		}else{
			
			if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
			}
			var informacion = $('form').serialize();
			
			if(informacion.indexOf('&nombre=')<0)
				informacion += "&nombre="+u.nombre.value;
			if(informacion.indexOf('&paterno=')<0)
				informacion += "&paterno="+u.paterno.value;
			if(informacion.indexOf('&materno=')<0)
				informacion += "&materno="+u.materno.value;
			if(informacion.indexOf('&rfc=')<0)
				informacion += "&rfc="+u.rfc.value;
			if(informacion.indexOf('&email=')<0)
				informacion += "&email="+u.email.value;
			if(informacion.indexOf('&celular=')<0)
				informacion += "&celular="+u.celular.value;
			if(informacion.indexOf('&web=')<0)
				informacion += "&web="+u.web.value;
			
			$.ajax({
				type:"POST",
				url:"client_30_con.php",
				data:informacion,
				success:resGuardar
			});
		}
	}
}

function resGuardar(respuesta){
	if(respuesta.indexOf('datos guardados')>-1){
		info("Datos guardados","¡Atencion!");
		document.getElementById('codigo').value = respuesta.split(",")[1];
		consultaTexto("mostrarCliente","consultasClientes30.php?accion=1&idpagina="+u.idpagina.value+"&cliente="+respuesta.split(",")[1]+"&val="+Math.random());
	}else{
		alerta3("Error al guardar<br>"+respuesta,"¡Atencion!");
	}
}

function agregarnick(param){
	if(document.getElementById(param).value!=""){
	 var par=new RegExp(document.getElementById(param).value.toUpperCase()+'[\r\n]+');
     var txt=document.getElementById('listnick').value.split(par); 
	 if(!par.test(document.getElementById('listnick').value)){ 
 	document.getElementById('listnick').value = document.getElementById('listnick').value + document.getElementById(param).value.toUpperCase() + "\n";
	document.getElementById(param).value ="";
	document.getElementById(param).focus();
	 }else{
alerta('El Nick ' + document.getElementById(param).value + ' ya existe', '¡Atención!','nick');	
        document.getElementById('nick').focus(); 
	 	return;
	 }
	}	
}
function BorrarNick(linea){	
	linea=linea.toUpperCase();
    var par=new RegExp(linea+'[\r\n]+'); 
    var txt=document.getElementById('listnick').value.split(par); 
    if(!par.test(document.getElementById('listnick').value)){
	alerta('El Nick ' + linea + ' no existe', '¡Atención!','nick');        
        return; 
    }
    if(document.getElementById('nick').value==""){
		alerta('Debe escribir el Nick a Borrar', '¡Atención!','nick'); 
        return;		
	}else if(confirmar('¿Esta seguro de borrar el nick?', '', 'BorrarNickConfirmacion(document.getElementById(\'nick\').value);', '')){	
	}
} 
function BorrarNickConfirmacion(linea){
	linea=linea.toUpperCase();
	var par=new RegExp(linea+'[\r\n]+'); 
    var txt=document.getElementById('listnick').value.split(par);
	document.getElementById('listnick').value=txt.join (''); 
    document.getElementById('nick').value="";
}
function limpiar(){
	var vidpagina = $("input[name='idpagina']").val();
	$("input[name='codigo']").removeAttr("readOnly");
	$("input[name='prospecto']").removeAttr("readOnly");
	
	$("input[type='text']").val("");
	$("input[type='hidden']").val("");
	$("textarea").val("");
	
	$("input[name='idpagina']").val(vidpagina);
	
	DS1.limpiar();
	$.ajax({
		type:"POST",
		url:"consultasClientes30.php",
		data:"accion=9&idpagina="+vidpagina
		
	});
	bloquearDatos(0);
	u.rdmoral[0].disabled=false;
	u.rdmoral[1].disabled=false;
	conv=0;
}
function limpiartodo(){
	u.nick.value 		="";
	u.listnick.value 	="";
	u.nombre.value 		="";
	u.paterno.value 	="";
	u.materno.value 	="";
	u.rfc.value 		="";
	u.email.value 		="";
	u.celular.value		="";
	u.web.value 		="";
	u.lstipocliente.value = "SELECCIONAR TIPO";	
	u.chpoliza.checked = false;
	u.npoliza.value ="";
	u.aseguradora.value ="";
	u.vigencia.value ="";
	u.rdmoral[0].checked;
	u.convenio.innerHTML = "";
	tabla1.clear();	
	conv=0;
	bloquearDatos(0);
}

function obtenerCliente(id){
	u.codigo.value = id;
	consultaTexto("mostrarCliente","consultasClientes30.php?accion=1&idpagina="+u.idpagina.value+"&cliente="+id+"&val="+Math.random());
}
function obtener(id){
	document.getElementById('codigo').value=id;
	consultaTexto("mostrarCliente","consultasClientes30.php?accion=1&idpagina="+u.idpagina.value+"&cliente="+id+"&val="+Math.random());
	ocultarBuscador();
}
function mostrarCliente(datos){
		try{
			var obj = eval(datos);
		}catch(e){
			alerta3("Error "+datos);
			return false;
		}
		
		limpiartodo();
		if(obj.datoscliente!=null){
			if(obj.datoscliente.personamoral=="SI"){
				u.rdmoral[0].checked=true;
			}else{
				u.rdmoral[1].checked=true;
			}
			u.nombre.value	= obj.datoscliente.nombre;
			u.activado.value	= obj.datoscliente.activado;
			u.paterno.value	= obj.datoscliente.paterno;
			u.materno.value	= obj.datoscliente.materno;
			u.comisiongeneral.value	= obj.datoscliente.comision;
			u.rfc.value		= obj.datoscliente.rfc;
			u.email.value	= obj.datoscliente.email;
			u.celular.value	= obj.datoscliente.celular;
			u.web.value		= obj.datoscliente.web;
			u.lstipocliente.value = obj.datoscliente.tipocliente;	
			if(obj.datoscliente.tieneconvenio=="SI"){
				u.convenio.innerHTML = "CON CONVENIO";
				bloquearDatos(1);
				conv=1;
			}else if(obj.datoscliente.tieneconvenio=="EX"){
				u.convenio.innerHTML = "CONV. EXPIRADO";
				bloquearDatos(0);
				conv=0;
			}else{
				u.convenio.innerHTML = "";
				bloquearDatos(0);
				conv=0;
			}
			if(obj.datoscliente.tienecredito=="SI"){
				bloquearDatos(1);
				conv=1;
			}
			u.pago.checked 	= ((obj.datoscliente.pagocheque==1)?true:false);
			habilitar();
			u.npoliza.value	= obj.datoscliente.npoliza;
			if(obj.datoscliente.poliza=="SI"){
				u.chpoliza.checked = true;
			}else{
				u.chpoliza.checked = false;
			}
			u.aseguradora.value	= obj.datoscliente.aseguradora;
			u.vigencia.value	= obj.datoscliente.vigencia;	
			u.prospecto.readOnly = true;
			busco = false;
			//u.btn_Eliminar.style.visibility = 'hidden';
			DS1.setJsonData(obj.direcciones);
			
			u.accion.value	="modificar";
			u.rfc_h.value = "";
			try{
				u.nick.focus();
				for(i=0;i<obj.nicks.length;i++){
					u.listnick.value += obj.nicks[i].nick+'\n';
				}
				trim(u.listnick.value,'listnick');	
			}catch(e){
				e = null;
			}
		}else{
			alerta3("El numero de Cliente no existe","¡Atención!");
			limpiartodo();
		}
	}
	
	function bloquearDatos(tipo){
		if(tipo==1){
			u.rdmoral[0].disabled=true;
			u.rdmoral[1].disabled=true;
			u.nombre.disabled	= true;
			u.paterno.disabled	= true;
			u.materno.disabled	= true;
			u.rfc.disabled		= true;
			u.email.disabled	= true;
			u.celular.disabled	= true;
			u.web.disabled		= true;
			document.getElementById('nombre').style.backgroundColor='#FFFF99';
			document.getElementById('paterno').style.backgroundColor='#FFFF99';
			document.getElementById('materno').style.backgroundColor='#FFFF99';
			document.getElementById('rfc').style.backgroundColor='#FFFF99';
			document.getElementById('email').style.backgroundColor='#FFFF99';
			document.getElementById('celular').style.backgroundColor='#FFFF99';
			document.getElementById('web').style.backgroundColor='#FFFF99';
		}else{
			u.nombre.disabled	= false;
			u.paterno.disabled	= false;
			u.materno.disabled	= false;
			u.rfc.disabled		= false;
			u.email.disabled	= false;
			u.celular.disabled	= false;
			u.web.disabled		= false;
			document.getElementById('nombre').style.backgroundColor='';
			document.getElementById('paterno').style.backgroundColor='';
			document.getElementById('materno').style.backgroundColor='';
			document.getElementById('rfc').style.backgroundColor='';
			document.getElementById('email').style.backgroundColor='';
			document.getElementById('celular').style.backgroundColor='';
			document.getElementById('web').style.backgroundColor='';
		}
	}
	
function obtenerProspectoCaja(id){
	consultaTexto("mostrarProspecto","consultasClientes30.php?accion=2&idpagina="+u.idpagina.value+"&cliente="+id+"&val="+Math.random());	
}
function obtenerprospecto(id){
	document.getElementById('prospecto').value=id;
	consultaTexto("mostrarProspecto","consultasClientes30.php?accion=2&idpagina="+u.idpagina.value+"&cliente="+id+"&val="+Math.random());	
}
function mostrarProspecto(datos){
	try{
		var obj = eval(datos);
	}catch(e){
		alerta3("Error "+datos);
		return false;
	}
	
	limpiartodo();
		if(obj.datoscliente!=null){
			if(obj.datoscliente.personamoral=="SI"){
				u.rdmoral[0].checked=true;
			}else{
				u.rdmoral[1].checked=true;
			}
			u.nombre.value	= obj.datoscliente.nombre;
			u.paterno.value	= obj.datoscliente.paterno;
			u.materno.value	= obj.datoscliente.materno;
			u.comisiongeneral.value	= obj.datoscliente.comision;
			u.rfc.value		= obj.datoscliente.rfc;
			u.email.value	= obj.datoscliente.email;
			u.celular.value	= obj.datoscliente.celular;
			u.web.value		= obj.datoscliente.web;
			u.accion.value		="";
			habilitar();
			DS1.setJsonData(obj.direcciones);
			for(i=0;i<obj.nicks.length;i++){
				u.listnick.value += obj.nicks[i].nick+'\n';
			}
			trim(u.listnick.value,'listnick');	
			u.codigo.readOnly = true;
		}else{
			alerta3("El numero de Cliente no existe","¡Atención!");
			limpiartodo();
		}
	}
	
	function obtenerDireccionProspecto(datos){
		tabla1.setXML(datos);
	}
	
	function CodigoPostal(cp){
		if(cp!=""){
		ConsultaCodigoPostalCliente(cp,'direccion');	
		}	
	}
function trim(cadena,caja){
	for(i=0;i<cadena.length;)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(i+1, cadena.length);
		else
			break;
	}
	for(i=cadena.length-1; i>=0; i=cadena.length-1)
	{
		if(cadena.charAt(i)==" ")
			cadena=cadena.substring(0,i);
		else
			break;
	}
	
	document.getElementById(caja).value=cadena;
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
function BorrarTemporal(){	
		if(document.getElementById('consulto').value!=""){
	BorrarTablaTemporal(document.getElementById('user').value,document.getElementById('fechahora').value,'borrar');
		}else{
	BorrarTablaTemporal(document.getElementById('user').value,document.getElementById('fechahora').value,'borrar');
		}	
} 
function isEmailAddress(theElement, nombre_del_elemento){
	var s = theElement.value;
	var filter=/^[A-Za-z0-9_.-][A-Za-z0-9_.-]*@[A-Za-z0-9_-]+\.[A-Za-z0-9_.-]+[A-za-z]$/;
	if (s.length == 0 ) return true;
	if (filter.test(s))
	return true;
	else
	return false;
} 
function foco(nombrecaja){
	if(nombrecaja=="prospecto"){
		document.getElementById('oculto').value="1";
	}else if(nombrecaja=="codigo"){
		document.getElementById('oculto').value="2";
	}
}
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
abrirVentanaFija('buscarprospectocliente.php', 550, 450, 'ventana', 'Busqueda')
	}else if(document.form1.oculto.value=="2"){
abrirVentanaFija('buscarcliente.php', 650, 450, 'ventana', 'Busqueda')
	}
});		
function validarCliente(e,obj){
	tecla = (document.all)?e.keyCode:e.which;
	if((tecla==8 || tecla==46)&&document.getElementById(obj).value==""){
		limpiartodo();
	}
}

	function bloquearCheque(){
		var tiene = "";
		if(u.pago.checked == true){
			tiene = <?=$cpermiso->checarPermiso("283",$_SESSION[IDUSUARIO]);?>;
		}
		
		if(tiene==false){
			u.pago.checked = false;
			<?=$cpermiso->verificarPermiso(283,$_SESSION[IDUSUARIO]);?>;
		}
	}

	function mostrarDatosExtras(){
		<?=$cpermiso->verificarPermiso("280,281",$_SESSION[IDUSUARIO]);?>;
		abrirVentanaFija('informacionextra.php?cliente='+u.codigo.value, 625, 418, 'ventana', 'Detalle');
	}
</script>
<script src="selectClientes.js"></script> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo Clientes</title>
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<link href="../clientes/puntovta.css" rel="stylesheet" type="text/css">
<script src="../../javascript/ajax.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" /><style type="text/css">
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
.style5 {color: #FFFFFF ; font-size:9px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
<!--
.Estilo3 {
	color: #FFFFFF;
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>
<body >
<form id="form1" name="form1" method="post" action="">
	<?
		list($Mili, $bot) = explode(" ", microtime());
	     $DM=substr(strval($Mili),2,3);
	?>
	<input type="hidden" name="idpagina" id="idpagina" value="<?=date("ymdHis").$DM?>" />
  <table border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr> 
      <td class="FondoTabla">CAT&Aacute;LOGO DE CLIENTES</td>
    </tr>
    <tr> 
      <td><br> <table align="center" cellpadding="0" cellspacing="0" style="width=500px">
          <tr> 
            <td width="70" class="Tablas">Prospecto:</td>
            <td colspan="6" class="Tablas"> <table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="34%" height="48"><input class="Tablas" name="prospecto" type="text" id="prospecto2"  value="<?=$prospecto; ?>" size="10" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onKeyPress="if(event.keyCode==13){obtenerProspectoCaja(this.value)}" onKeyUp="return validarCliente(event,this.name)"/> 
                    <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Prospecto" onClick="abrirVentanaFija('../../buscadores_generales/buscarProspectoGen.php?funcion=obtenerprospecto', 600, 450, 'ventana', 'Busqueda')"/> 
                    <input name="oculto" type="hidden" id="oculto3" value="<?=$oculto ?>" />
                    <input name="recoleccion" type="hidden" id="oculto" value="<?=$recoleccion ?>" /></td>
                  <td width="34%" id="convenio" style="color:#000000; font-size:15px; font-weight:bold">&nbsp;</td>
                  <td width="32%"><table width="190" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="center"><img src="../../img/Boton_Detalle.gif" width="70" height="20" style="cursor:pointer"
        onClick="mostrarDatosExtras();"></td>
                      </tr>
                  </table></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td colspan="7" class="FondoTabla">Datos Generales </td>
          </tr>
          <tr> 
            <td class="Tablas">#Cliente:</td>
            <td colspan="6" class="Tablas"><input class="Tablas" name="codigo" type="text" id="codigo" style="font-size:9px; font:tahoma" value="<?=$codigo; ?>" size="10" onKeyPress="if(event.keyCode==13){obtenerCliente(this.value);}" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onKeyUp="return validarCliente(event,this.name)" /> 
              <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Cliente" onClick="mostrarBuscador()"/></td>
          </tr>
          <tr> 
            <td class="Tablas">Nick:</td>
            <td colspan="6" class="Tablas"><input class="Tablas" name="nick" type="text" id="nick" onBlur="trim(document.getElementById('nick').value,'nick');" size="40" style="font:tahoma;font-size:9px; text-transform:uppercase" /> 
              <img src="../../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" align="absbottom" style="cursor:pointer" onClick="agregarnick('nick');" /></td>
          </tr>
          <tr> 
            <td class="Tablas"><img src="../../img/Boton_Eliminar.gif" alt="Eliminar" width="70" style="cursor:pointer" height="20" onClick="BorrarNick(nick.value);" /></td>
            <td colspan="6" class="Tablas"><textarea class="Tablas" name="listnick" rows="3" id="listnick" style="background:#FFFF99;width:346px; text-transform:uppercase" readonly="readonly"><?=$listnick ?></textarea></td>
          </tr>
          <tr> 
            <td colspan="7" class="Tablas">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="7" class="Tablas"><table width="200" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input name="rdmoral" type="radio" value="SI" onClick="habilitar();" <? if($rdmoral=="SI"||$rdmoral==""){echo'checked'; }?> style="width:12px" />
Persona Moral </td>
                <td><input name="rdmoral" type="radio" value="NO" onClick="habilitar();"  <? if($rdmoral=="NO"){ echo'checked'; } ?> style="width:12px" />
Persona Fis&iacute;ca &nbsp;&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr> 
            <td colspan="7" class="Tablas"><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
          </tr>
          <tr> 
            <td class="Tablas">Nombre:</td>
            <td colspan="6" class="Tablas"><input class="Tablas" name="nombre" type="text" id="nombre" size="64" onBlur="trim(document.getElementById('nombre').value,'nombre');" value="<?=$nombre; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase" onKeyPress="return tabular(event,this)"/></td>
          </tr>
          <tr> 
            <td height="22" class="Tablas">Ap. Paterno:</td>
            <td width="240"  class="Tablas" style="width:240px"><input class="Tablas" name="paterno" type="text" id="paterno"  onBlur="trim(document.getElementById('paterno').value,'paterno');"  maxlength="100" value="<?=$paterno; ?>" <? if($rdmoral=="SI"||$rdmoral==""){echo 'disabled'; } ?> style="font:tahoma;font-size:9px; background:#FFFF99; text-transform:uppercase;width:190px" onKeyPress="return tabular(event,this)" /></td>
            <td width="140"  class="Tablas" style="width:140px">Ap. Materno:</td>
            <td colspan="3" class="Tablas"><input name="materno" class="Tablas" type="text" id="materno" onBlur="trim(document.getElementById('materno').value,'materno');"  value="<?=$materno; ?>" <? if($rdmoral=="SI"||$rdmoral==""){echo 'disabled'; } ?> style="font:tahoma;font-size:9px; background:#FFFF99; text-transform:uppercase;width:190px" onKeyPress="return tabular(event,this)"/></td>
            <td width="178"  class="Tablas" style="width:50px"></td>
          </tr>
          <tr> 
            <td height="18" class="Tablas">R.F.C.:</td>
            <td class="Tablas"><input name="rfc" type="text" class="Tablas" id="rfc" maxlength="13" onblur="trim(document.getElementById('rfc').value,'rfc'); if(this.value!=''){obtenerRFC(this.value);}" onkeypress="if(event.keyCode==13 || event.keyCode==9){obtenerRFC(this.value);}" value="<?=$rfc; ?>" style="text-transform:uppercase;width:190px"/></td>
            <td class="Tablas">Email:</td>
            <td colspan="4" class="Tablas"><input name="email" class="Tablas" type="text" id="email" style="text-transform:lowercase; font:tahoma; font-size:9px;width:190px" onKeyPress="return tabular(event,this);" onBlur="trim(document.getElementById('email').value,'email');" value="<?=$email; ?>" /></td>
          </tr>
          <tr> 
            <td class="Tablas">Celular:</td>
            <td class="Tablas"><input name="celular" type="text" class="Tablas" id="celular" size="20" maxlength="70" onBlur="trim(document.getElementById('celular').value,'celular');" onKeyPress="return tabular(event,this)" value="<?=$celular; ?>" style="font:tahoma;font-size:9px; text-transform:uppercase;width:190px"/></td>
            <td class="Tablas">Sitio Web: </td>
            <td colspan="4" class="Tablas"><input name="web" class="Tablas" type="text" id="web" onBlur="trim(document.getElementById('web').value,'web');" onKeyPress="return tabular(event,this)" value="<?=$web; ?>" style="font:tahoma;font-size:9px;width:190px"/></td>
          </tr>
          
          <tr> 
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="7" class="FondoTabla">Datos Direcci&oacute;n</td>
          </tr>
          <tr>
          	<td colspan="7">
            	<table width="587" border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td width="98">Buscar Calle</td>
                    	<td width="148"><input type="text" style="width:140px;" onkeypress="if(event.keyCode==13){buscarCalle(this.value);}" /></td>
                    	<td width="95">Buscar Colonia</td>
                    	<td width="149"><input type="text" style="width:140px;" onkeypress="if(event.keyCode==13){buscarColonia(this.value)}" /></td>
                    	<td width="32">&nbsp;</td>
                    	<td width="65">&nbsp;</td>
                    </tr>
                </table>
                <script>
					function buscarCalle(valor){
						if(!DS1.buscarYMostrarRevueltos(valor,'calle','like')){
							alerta3("No se encontró la calle","¡Atencion!");
						}
					}
					function buscarColonia(valor){
						if(!DS1.buscarYMostrarRevueltos(valor,'colonia','like')){
							alerta3("No se encontró la calle","¡Atencion!");
						}
					}
				</script>
            </td>
          </tr>
          <tr> 
            <td colspan="7" class="Tablas"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                <tr> 
                  <td align="center"><table id="tabladetalle" border=0 cellspacing=0 cellpadding=0>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr>
          	<td colspan="7" id="direcciones_paginado" style="border:#000 1px solid">
            </td>
          </tr>
          <tr> 
            <td class="Tablas" >&nbsp;</td>
            <td colspan="6" class="Tablas" align="right"> <table width="36%" border="0">
                <tr> 
                  <td><div id="btn_Eliminar" class="ebtn_eliminar" onClick="EliminarFila()"></div></td>
                  <td><img src="../../img/Boton_AgregarDir.gif" alt="Agregar Direcci&oacute;n" align="absbottom" style="cursor:pointer" 
onClick="abrirVentanaFija('direccioncliente.php', 550, 400, 'ventana', 'DATOS DIRECCION')" /></td>
                </tr>
              </table></td>
          </tr>
          
          <tr> 
            <td colspan="7" class="Tablas"><table width="100%" border="0" cellpadding="1" cellspacing="0">
                
                <tr>
                  <td height="15" class="Tablas">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td width="71" height="15" class="Tablas">&nbsp;</td>
                  <td><input name="modificarfila" type="hidden" id="modificarfila">
                    <input name="registros" type="hidden" id="registros">
                    <input name="valfact" type="hidden" id="valfact">
                    <input name="activado" type="hidden" id="activado" value="<?=$activado ?>">
                    <input name="clasificacioncliente" type="hidden" id="clasificacioncliente" value="<?=$clasificacioncliente ?>">
                    <input name="clientecorporativo" type="hidden" id="clientecorporativo" value="<?=$_GET[clientecorporativo] ?>">
                    <input name="rfc_h" type="hidden" id="rfc_h" value="<?=$rfc_h ?>">
                    <input name="cliente_h" type="hidden" id="cliente_h" value="<?=$cliente_h ?>">
                    <input name="idcliente_h" type="hidden" id="idcliente_h" value="<?=$idcliente_h ?>">
                    <input name="comisiongeneral" type="hidden" id="comisiongeneral" value="<?=$comisiongeneral ?>" > <input name="vigencia" type="hidden" id="vigencia" value="<?=$vigencia ?>" />
                    <input name="aseguradora" type="hidden" id="aseguradora" value="<?=$aseguradora ?>" />
                    <input name="npoliza" type="hidden" id="npoliza" value="<?=$npoliza ?>" />
					<input name="lstipocliente" type="hidden" id="lstipocliente" value="<?=$lstipocliente ?>" />					<label>
					<input name="eliminar" type="hidden" id="eliminar">
                    <input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />
                    <input name="esprospecto" type="hidden" id="esprospecto" value="<?=$esprospecto ?>">

                    <input name="chpoliza" type="hidden" id="chpoliza" value="<?=$chpoliza ?>">
				    <input name="pago" type="hidden" id="pago" value="<?=$pago ?>">
				  </label></td>
                  <td width="216"><table width="167" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" style="cursor:pointer" onclick="validar();" /></td>
                      <td><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" align="absbottom" style="cursor:pointer" title="Nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" ></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>

<?
	$raiz = "../../";
	$funcion = "obtener";
	$nombreBuscador = "buscadorClientes";
	$funcionMostrar = "mostrarBuscador";
	$funcionOcultar = "ocultarBuscador";
	include("../../buscadores_generales/buscadorIncrustado.php");
	
?>
</body>
</html>
<? 
	if ($mensaje!=""){
		echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
?>