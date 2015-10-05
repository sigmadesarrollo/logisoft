<?	session_start();	?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/funciones.js"></script>
<script>	
	
	var busco = false;
	var u = document.all;	
	var esModificar = "";	
	var tabla1 = new ClaseTabla();
	var tabla2 = new ClaseTabla();
	
	tabla1.setAttributes({
		nombre:"tabladetalle",
		campos:[
			{nombre:"CALLE", medida:100, alineacion:"left", datos:"calle"},
			{nombre:"NUMERO", medida:50, alineacion:"left", datos:"num"},
			{nombre:"COLONIA", medida:100, alineacion:"left", datos:"colonia"},
			{nombre:"CRUCE", medida:4, tipo:"oculto", alineacion:"center", datos:"cruce"},
			{nombre:"CP", medida:50, alineacion:"center", datos:"cp"},		
			{nombre:"POBLACION", medida:100, alineacion:"left", datos:"poblacion"},
			{nombre:"MUN", medida:4, tipo:"oculto", alineacion:"center", datos:"municipio"},
			{nombre:"ESTADO", medida:4, tipo:"oculto", alineacion:"center", datos:"estado"},
			{nombre:"PAIS", medida:4, tipo:"oculto", alineacion:"center", datos:"pais"},
			{nombre:"TELEFONO", medida:80, alineacion:"left", datos:"telefono"},
			{nombre:"FAX", medida:4, tipo:"oculto", alineacion:"center", datos:"fax"},
			{nombre:"FACT", medida:50, alineacion:"center", datos:"fact"},
			{nombre:"ID", medida:5, tipo:"oculto", alineacion:"center", datos:"id"}
		],
		filasInicial:6,
		alto:95,
		seleccion:true,
		ordenable:true,	
		eventoDblClickFila:"ModificarFila()",
		nombrevar:"tabla1"
	});
	
	tabla2.setAttributes({
		nombre:"detallenick",
		campos:[
			{nombre:"NICK", medida:200, alineacion:"left", datos:"nick"},			
			{nombre:"FECHA", medida:5, tipo:"oculto", alineacion:"center", datos:"fecha"}
		],
		filasInicial:5,
		alto:90,
		seleccion:true,
		ordenable:true,	
		eventoDblClickFila:"modificarNick()",
		nombrevar:"tabla2"
	});
	
	window.onload = function(){
		u.nick.focus();
		tabla1.create();
		tabla2.create();
		habilitar();		
	}
	
	function agregarVar(miArray){		
		var registro= new Object();
		registro.calle 		= miArray[0];
		registro.num		= miArray[1];
		registro.cruce		= miArray[2];
		registro.cp			= miArray[3];
		registro.colonia	= miArray[4];
		registro.poblacion 	= miArray[5];
		registro.municipio	= miArray[6];
		registro.estado		= miArray[7];
		registro.pais		= miArray[8];
		registro.telefono 	= miArray[9];
		registro.fax 		= miArray[10];
		registro.fact 		= miArray[11];
		registro.id 		= miArray[12];
		tabla1.add(registro);
	}
	function ValAddFact(miArray){
		if(tabla1.getRecordCount()==0){
			return true;
		}else{			
			var FactVal	= tabla1.getValuesFromField("fact",":");
			if(miArray[11]=="NO"){
				if(u.modificarfila.value!=""){
					tabla1.deleteById(tabla1.getSelectedIdRow());
					u.modificarfila.value="";
				}
				return true;
			}else{		
				if(miArray[11]=="SI"){
					if(u.modificarfila.value!=""){
						tabla1.deleteById(tabla1.getSelectedIdRow());
						u.modificarfila.value="";
						return true;				
					}
					if(FactVal.indexOf("SI")>-1 && miArray[11]=="SI"){
						return false;	
					}
				}		
			}
		}		
	}
	
	function EliminarFila(){
		if(tabla1.getValSelFromField('cp','CP')!=""){
			confirmar('¿Esta seguro de Eliminar la Dirección?','','borrarFila()','');
		}	
	}
	function borrarFila(){
		tabla1.deleteById(tabla1.getSelectedIdRow());	  
	}
	function ModificarFila(){
		var obj = tabla1.getSelectedRow();
		if(tabla1.getValSelFromField("cp","CP")!=""){
	
		esModificar = "SI";		
		abrirVentanaFija('direccioncliente.php?calle='+obj.calle
			+'&numero='+obj.num
			+'&entrecalles='+obj.cruce
			+'&cp='+obj.cp
			+'&colonia='+obj.colonia
			+'&poblacion='+obj.poblacion
			+'&municipio='+obj.municipio
			+'&estado='+obj.estado
			+'&pais='+obj.pais
			+'&telefono='+obj.telefono
			+'&fax='+obj.fax
			+'&esmodificar=si&chfacturacion='+obj.fact
			+'&id='+obj.id, 550, 400, 'ventana', 'DATOS DIRECCION');
			document.all.modificarfila.value	=tabla1.getSelectedIdRow();
				if(obj.fact=='SI'){document.all.valfact.value='1'}
				else{document.all.valfact.value=''}
					
			}
	}
	function ValidaRfc(rfcStr) {
		var strCorrecta;
		strCorrecta = rfcStr;
		
		if (document.form1.rdmoral[0].checked){
		var valid = '^(([A-Z]|[a-z]|[&]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}else{
		var valid = '^(([A-Z]|[a-z]|[&]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
		}
		var validRfc=new RegExp(valid);
		var matchArray=strCorrecta.match(validRfc);
		if (matchArray==null) {	
			return false;
		}else{
		return true;
		}	
	}

	function obtenerRFC(rfc){
		if(busco==false){
			busco = true;
			if(u.accion.value!="modificar"){
				consultaTexto("mostrarRfc","consultaCredito_con.php?accion=3&rfc="+rfc);
			}else{
				busco = false;
			}
		}
	}

	function mostrarRfc(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval("("+convertirValoresJson(datos)+")");
			u.rfc_h.value = obj.rfc;
			u.cliente_h.value = obj.cliente;
			u.idcliente_h.value = obj.id;
confirmar('El R.F.C.:'+u.rfc.value.toUpperCase()+' esta asignado al cliente '+obj.cliente.toUpperCase()+' ¿Desea ver su información?', '', 'obtenerCliente('+obj.id+')', 'cancelo()');
		}else{
			busco = false;
		}
	}
	function cancelo(){
		busco = false;
	}
	function habilitar(){
		if(document.all.rdmoral[1].checked== true){
			document.getElementById('paterno').disabled=false
			document.getElementById('materno').disabled=false
			document.getElementById('paterno').style.backgroundColor='';
			document.getElementById('materno').style.backgroundColor='';
		}else if(document.all.rdmoral[0].checked== true){
			document.getElementById('paterno').disabled=true
			document.getElementById('paterno').value="";
			document.getElementById('materno').disabled=true
			document.getElementById('materno').value="";
			document.getElementById('paterno').style.backgroundColor='#FFFF99';
			document.getElementById('materno').style.backgroundColor='#FFFF99';
		}
	}
	var nav4 = window.Event ? true : false;
	function Numeros(evt){ 
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
	var key = nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 48 && key <= 57));
	}
	function validar(){
		u.registros.value = tabla1.getRecordCount();
		if (document.form1.listnick.value.length == 0){
				alerta('Debe capturar por lo menos un Nick', '¡Atención!','nick');
				return false;			
		}else if (document.getElementById('nombre').value==""){
				alerta('Debe capturar Nombre', '¡Atención!','nombre');
				return false;
		}else if(document.form1.rdmoral[1].checked){		
			if(document.getElementById('paterno').value==""){
					alerta('Debe capturar Apellido Paterno', '¡Atención!','paterno');
					return false;				
			/*}else if(document.getElementById('materno').value==""){
					alerta('Debe capturar Apellido Materno', '¡Atención!','materno');
					return false;	*/		
			}else if(document.getElementById('rfc').value==""){
					alerta('Debe capturar R.F.C', '¡Atención!','rfc');
					return false;
			}else if(u.rfc_h.value == u.rfc.value){
				alerta3('El R.F.C.:'+u.rfc.value.toUpperCase()+' esta asignado al cliente '+u.cliente_h.value.toUpperCase(), '¡Atención!');
				return false;				
			}else if(!ValidaRfc(document.getElementById('rfc').value)){
					alerta('Debe capturar un R.F.C valido.', '¡Atención!','rfc');
					return false;
			}else if(document.getElementById('email').value!="" && !isEmailAddress(document.form1.email) ){
					alerta('Debe capturar Email valido.', '¡Atención!','email');
					return false;
			}else if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
					alerta3('Debe capturar Por lo menos una Dirección','¡Atención!');
					return false;			
			}else{
				if(document.getElementById('accion').value==""){					
					consultaTexto("registro","catCliente_con.php?accion=2&moral="+((u.rdmoral[0].checked==true)?"SI":"NO")
					+"&nombre="+u.nombre.value
					+"&paterno="+u.paterno.value
					+"&materno="+u.materno.value
					+"&rfc="+u.rfc.value
					+"&email="+u.email.value
					+"&celular="+u.celular.value
					+"&web="+u.web.value
					+"&val="+Math.random());
				}else if(document.getElementById('accion').value=="modificar"){
					consultaTexto("modifico","catCliente_con.php?accion=3&moral="+((u.rdmoral[0].checked==true)?"SI":"NO")
					+"&nombre="+u.nombre.value
					+"&paterno="+u.paterno.value
					+"&materno="+u.materno.value
					+"&rfc="+u.rfc.value
					+"&email="+u.email.value
					+"&celular="+u.celular.value
					+"&web="+u.web.value
					+"&cliente="+u.codigo.value
					+"&val="+Math.random());
				}
			}
		}else if(document.form1.rdmoral[0].checked){
			if(document.getElementById('rfc').value==""){
					alerta('Debe capturar R.F.C', '¡Atención!','rfc');
					return false;
			}else if(u.rfc_h.value == u.rfc.value){
				alerta3('El R.F.C.:'+u.rfc.value.toUpperCase()+' esta asignado al cliente '+u.cliente_h.value.toUpperCase(), '¡Atención!');
				return false;
			}else if(!ValidaRfc(document.getElementById('rfc').value)){
					alerta('Debe capturar un R.F.C valido.', '¡Atención!','rfc');
					return false;
			}else if(document.getElementById('email').value!="" && !isEmailAddress(document.form1.email)){
					alerta('Debe capturar Email valido.', '¡Atención!','email');
					return false;
			}else if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
					alerta3('Debe capturar Por lo menos una Dirección','¡Atención!');
					return false;			
			}else{
				if(document.getElementById('accion').value==""){
					consultaTexto("registro","catCliente_con.php?accion=2&moral="+((u.rdmoral[0].checked==true)?"SI":"NO")
					+"&nombre="+u.nombre.value
					+"&paterno="+u.paterno.value
					+"&materno="+u.materno.value
					+"&rfc="+u.rfc.value
					+"&email="+u.email.value
					+"&celular="+u.celular.value
					+"&web="+u.web.value
					+"&val="+Math.random());
				}else if(document.getElementById('accion').value=="modificar"){
					consultaTexto("modifico","catCliente_con.php?accion=3&moral="+((u.rdmoral[0].checked==true)?"SI":"NO")
					+"&nombre="+u.nombre.value
					+"&paterno="+u.paterno.value
					+"&materno="+u.materno.value
					+"&rfc="+u.rfc.value
					+"&email="+u.email.value
					+"&celular="+u.celular.value
					+"&web="+u.web.value
					+"&cliente="+u.codigo.value
					+"&val="+Math.random());
				}
			}
		}
	}
	function agregarnick(){	
		var nick = tabla2.getValuesFromField('nick',',');
		if(u.nick.value == ""){
			alerta("Debe capturar Nick","¡Atención!","nick");
			return false;
		}		
		if(u.accionnick.value==""){
			for(var i=0;i<tabla2.getRecordCount();i++){
				if(u["detallenick_NICK"][i].value == u.nick.value){
					alerta("El Nick "+u.nick.value.toUpperCase()+" ya se agrego","¡Atención!","nick");
					return false;
				}
			}
		}
		/*if(nick.indexOf(u.nick.value)>-1){
			alerta("El Nick "+u.nick.value.toUpperCase()+" ya se agrego","¡Atención!","nick");
			return false;
		}*/
		
		var obj = new Object();
		obj.nick = u.nick.value;
		
		if(u.accionnick.value==""){
			u.btn_Agregar.style.visibility = "hidden";
			obj.fecha= fechahora(obj.fecha);
			tabla2.add(obj);
			
			consultaTexto("registroNick","catCliente_con.php?accion=5&nick="+u.nick.value
			+"&tipo=grabar&s="+Math.random()+"&fecha="+obj.fecha);
			
		}else if(u.accionnick.value=="modificar"){
		
			u.btn_Agregar.style.visibility = "hidden";
			u.accionnick.value = "";
			consultaTexto("registroNick","catCliente_con.php?accion=5&nick="+u.nick.value
			+"&tipo=modificar&s="+Math.random()+"&fecha="+u.fechanick.value);
			obj.fecha = u.fechanick.value;
			u.fechanick.value = "";
			tabla2.updateRowById(u.filanick.value, obj);
			u.filanick.value = "";
		}
	}
	
	function registroNick(datos){	
		if(datos.indexOf("ok")>-1){
			if(tabla2.getRecordCount()==0){
				u.btn_Eliminar.style.visibility = "hidden";
			}
			u.nick.value = "";
			u.btn_Agregar.style.visibility = "visible";
		}else{
			u.btn_Agregar.style.visibility = "visible";
			alerta3("Hubo un error al agregar "+datos,"¡Atención!");
		}
	}
	
	function modificarNick(){
		var obj = tabla2.getSelectedRow();
		u.nick.value = obj.nick;
		u.fechanick.value = obj.fecha;
		u.filanick.value = tabla2.getSelectedIdRow();
		u.accionnick.value = "modificar";
	}
	
	function BorrarNick(){
		if(tabla2.getValSelFromField('nick','NICK')!=""){
			var obj = tabla2.getSelectedRow();
			confirmar('¿Esta seguro de borrar el nick '+obj.nick.toUpperCase()+'?','','BorrarNickConfirmacion()','');
		}
	}
	
	function BorrarNickConfirmacion(){
		var obj = tabla2.getSelectedRow();
		consultaTexto("eliminarNick","catCliente_con.php?accion=5&tipo=eliminar&fecha="+obj.fecha);
	}
	
	function eliminarNick(datos){
		if(datos.indexOf("ok")<0){
			alerta3("Hubo un error al borrar el nick "+datos,"¡Atención!");
		}else{
			tabla2.deleteById(tabla2.getSelectedIdRow());
		}
	}	

	function limpiartodo(){
		u.nick.value 		="";	
		u.nombre.value 		="";
		u.paterno.value 	="";
		u.materno.value 	="";
		u.rfc.value 		="";
		u.email.value 		="";
		u.celular.value		="";
		u.web.value 		="";
		u.rdmoral[0].checked = true;
		tabla1.clear();
	}

	function obtenerCliente(id){
		u.codigo.value = id;
		consultaTexto("mostrarCliente","catCliente_con.php?accion=1&cliente="+id);
	}
	function obtener(id){
		u.codigo.value = id;
		consultaTexto("mostrarCliente","catCliente_con.php?accion=1&cliente="+id);
	}
	function mostrarCliente(datos){
		
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
            if (frm.elements[i+1].disabled ==true )    
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus();
            return false;
	} 

function isEmailAddress(theElement, nombre_del_elemento){
	var s = theElement.value;
	var filter=/^[A-Za-z0-9_.][A-Za-z0-9_.]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
	if (s.length == 0 ) return true;
	if (filter.test(s))
	return true;
	else
	return false;
} 
	
function validarCliente(e,obj){
	tecla = (document.all)?e.keyCode:e.which;
	if((tecla==8 || tecla==46)&&document.getElementById(obj).value==""){
		limpiartodo();
	}
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo Clientes</title>
<link href="FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="Tablas.css" rel="stylesheet" type="text/css" />
<script src="../../javascript/ajax.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>
<body >
<form id="form1" name="form1" method="post" action="">
  <table border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr> 
      <td class="FondoTabla">CAT&Aacute;LOGO DE CLIENTES</td>
    </tr>
    <tr> 
      <td><table align="center" cellpadding="0" cellspacing="0" style="width=500px">          
          <tr> 
            <td colspan="7" class="FondoTabla">Datos Generales </td>
          </tr>
          <tr> 
            <td width="70" class="Tablas">#Cliente:</td>
            <td colspan="6" class="Tablas"><input class="Tablas" name="codigo" type="text" id="codigo" style="font-size:9px; font:tahoma" value="<?=$codigo; ?>" size="10" onKeyPress="if(event.keyCode==13){obtenerCliente(this.value);}" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" onKeyUp="return validarCliente(event,this.name)" /> 
              <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" title="Buscar Cliente" onClick="abrirVentanaFija('../../buscadores_generales/buscarClienteGen2.php?funcion=obtener', 600, 450, 'ventana', 'Busqueda')"/></td>
          </tr>
          <tr> 
            <td class="Tablas">Nick:</td>
            <td colspan="6" class="Tablas"><input class="Tablas" name="nick" type="text" id="nick" onBlur="trim(document.getElementById('nick').value,'nick');" size="40" style="font:tahoma;font-size:9px; text-transform:uppercase" /> 
              <img src="../../img/Boton_Agregari.gif" id="btn_Agregar" alt="Agregar" width="70" height="20" align="absbottom" style="cursor:pointer" onClick="agregarnick('nick');" /></td>
          </tr>
          <tr> 
            <td class="Tablas"><img src="../../img/Boton_Eliminar.gif" id="btn_Eliminar" alt="Eliminar" width="70" style="cursor:pointer" height="20" onClick="BorrarNick(nick.value);" /></td>
            <td class="Tablas"><table id="detallenick" width="100%" border="0" cellspacing="0" cellpadding="0">              
            </table></td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas"><input name="accionnick" type="hidden" id="accionnick">
            <input name="fechanick" type="hidden" id="fechanick">
            <input name="filanick" type="hidden" id="filanick"></td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
          </tr>
          <tr> 
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
          </tr>
          <tr> 
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas"><input name="rdmoral" type="radio" value="SI" onClick="habilitar();" <? if($rdmoral=="SI"||$rdmoral==""){echo'checked'; }?> style="width:12px" />
Persona Moral &nbsp;&nbsp;&nbsp;&nbsp;
<input name="rdmoral" type="radio" value="NO" onClick="habilitar();"  <? if($rdmoral=="NO"){ echo'checked'; } ?> style="width:12px" />
Persona Fis&iacute;ca &nbsp;&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
            <td class="Tablas">&nbsp;</td>
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
            <td class="Tablas"><input name="rfc" type="text" class="Tablas" id="rfc" maxlength="13" onBlur="trim(document.getElementById('rfc').value,'rfc'); if(this.value!=''){obtenerRFC(this.value);}" onKeyPress="if(event.keyCode==13 || event.keyCode==9){obtenerRFC(this.value);}" value="<?=$rfc; ?>" style="text-transform:uppercase;width:190px"/></td>
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
            <td colspan="7" class="FondoTabla">Datos Direcci&oacute;n</td>
          </tr>
          <tr> 
            <td colspan="7" class="Tablas"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr>
					<td align="right"><table width="36%" border="0">
                      <tr>
                        <td><div class="ebtn_eliminar" onClick="EliminarFila()"></div></td>
                        <td><img src="../../img/Boton_AgregarDir.gif" alt="Agregar Direcci&oacute;n" align="absbottom" style="cursor:pointer" 
onClick="abrirVentanaFija('direccioncliente.php', 550, 400, 'ventana', 'DATOS DIRECCION')" /></td>
                      </tr>
                    </table></td>
				</tr>
                <tr> 
                  <td align="center"><table id="tabladetalle" border=0 cellspacing=0 cellpadding=0>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="Tablas" >&nbsp;</td>
            <td colspan="6" class="Tablas" align="right"><table width="167" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" style="cursor:pointer" onClick="validar();" ></td>
                <td><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" align="absbottom" style="cursor:pointer" title="Nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" ></td>
              </tr>
            </table></td>
          </tr>
          
          <tr> 
            <td colspan="7" class="Tablas"><table width="100%" border="0" cellpadding="1" cellspacing="0">
                
                <tr> 
                  <td width="71" height="15" class="Tablas"><input name="eliminar" type="hidden" id="eliminar">
                    <input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />
                    <input name="esprospecto" type="hidden" id="esprospecto" value="<?=$esprospecto ?>"></td>
                  <td width="139"><input name="modificarfila" type="hidden" id="modificarfila">
                    <input name="registros" type="hidden" id="registros">
                    <input name="valfact" type="hidden" id="valfact">
                    <input name="activado" type="hidden" id="activado" value="<?=$activado ?>">
                    <input name="clasificacioncliente" type="hidden" id="clasificacioncliente" value="<?=$clasificacioncliente ?>">
                    <input name="clientecorporativo" type="hidden" id="clientecorporativo" value="<?=$_GET[clientecorporativo] ?>">
                    <input name="rfc_h" type="hidden" id="rfc_h" value="<?=$rfc_h ?>">
                    <input name="cliente_h" type="hidden" id="cliente_h" value="<?=$cliente_h ?>">
                    <input name="idcliente_h" type="hidden" id="idcliente_h" value="<?=$idcliente_h ?>"></td>
                  <td width="91" class="Tablas"><label>
                  </label></td>
                  <td width="216">&nbsp;</td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>