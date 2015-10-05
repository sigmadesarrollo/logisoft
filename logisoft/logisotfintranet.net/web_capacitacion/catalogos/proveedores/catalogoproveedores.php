<? session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		 die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>"); 

	}*/

		include('../../Conectar.php');	

		$link=Conectarse('webpmm'); 	

		$usuario=$_SESSION[NOMBREUSUARIO];

		$accion=$_POST['accion']; $codigo=$_POST['codigo'];

		$razon=$_POST['razon']; $nombre=$_POST['nombre'];

		$rfc=$_POST['rfc'];$web=$_POST['web'];

		$nombrecontacto=$_POST['nombrecontacto'];$puesto=$_POST['puesto'];

		$telefonocontacto=$_POST['telefonocontacto'];$emailcontacto=$_POST['emailcontacto'];

		$celularcontacto=$_POST['celularcontacto'];

		$calle=$_POST['calle'];$numero=$_POST['numero'];

		$crucecalles=$_POST['crucecalles'];$cp=$_POST['cp'];

		$colonia=$_POST['colonia'];$poblacion=$_POST['poblacion'];

		$municipio=$_POST['municipio'];$estado=$_POST['estado'];

		$pais=$_POST['pais'];$telefono=$_POST['telefono'];

		$fax=$_POST['fax'];

		$tipoproveedor=$_POST['tipoproveedor'];

		$registros=$_POST['registros'];

		

	if($accion == ""){

		$row = folio('catalogoproveedor','webpmm');

		$codigo=$row[0];

	 }

	if($accion=="grabar"){
		$row = split("-",$calle);

		$sqlins="INSERT INTO catalogoproveedor 

		(tipoproveedor, razon, nombre, rfc,  web, calle, numero, crucecalles, cp, colonia, 

		poblacion, municipio, estado, pais, telefono, fax, usuario, fecha)

		VALUES

		('$tipoproveedor',UCASE('$razon'),UCASE('$nombre'), UCASE('$rfc'),UCASE('$web'), 

		UCASE('$row[0]'), '$numero', UCASE('$crucecalles'), '$cp', UCASE('$colonia'), 

		UCASE('$poblacion'), UCASE('$municipio'), UCASE('$estado'), UCASE('$pais'),'$telefono',

		'$fax', '$usuario', CURRENT_TIMESTAMP())";

		$res = mysql_query($sqlins,$link)or die($sqlins."<br>".__line__."<br>".mysql_error($link));		

		$codigo=mysql_insert_id();

		if($registros>0){

			for($i=0;$i<$registros;$i++){

				$sqlins=mysql_query("INSERT INTO catalogoproveedordetalle 

						(id,idproveedor,nombre,puesto,telefono,celular,email,usuario,fecha)

						VALUES 	(NULL,'$codigo', 

						'".$_POST["tabladetalle_NOMBRE"][$i]."', 

						'".$_POST["tabladetalle_PUESTO"][$i]."', 

						'".$_POST["tabladetalle_TELEFONO"][$i]."', 

						'".$_POST["tabladetalle_CELULAR"][$i]."', 

						'".$_POST["tabladetalle_EMAIL"][$i]."', 

						'$usuario',CURRENT_TIMESTAMP())",$link) or die("error en linea ".__LINE__);

		

				$detalle .= "{

					nombre:'".$_POST["tabladetalle_NOMBRE"][$i]."',

					puesto:'".$_POST["tabladetalle_PUESTO"][$i]."',

					telefono:'".$_POST["tabladetalle_TELEFONO"][$i]."',

					celular:'".$_POST["tabladetalle_CELULAR"][$i]."',				

					email:'".$_POST["tabladetalle_EMAIL"][$i]."'},";

		   }

			$detalle = substr($detalle,0,strlen($detalle)-1);

		}

		$mensaje = 'Los datos han sido guardados correctamente';

		$accion="modificar";

	   

	}else if($accion=="modificar"){
		$row = split("-",$calle);
			$sqlupd="UPDATE catalogoproveedor SET tipoproveedor='$tipoproveedor',razon=UCASE('$razon'),

			nombre=UCASE('$nombre'),rfc=UCASE('$rfc'), web=UCASE('$web'), calle=UCASE('$row[0]'),

			numero=UCASE('$numero'), crucecalles=UCASE('$crucecalles'), cp=UCASE('$cp'),

			colonia=UCASE('$colonia'), poblacion=UCASE('$poblacion'), municipio=UCASE('$municipio'),

			estado=UCASE('$estado'), pais=UCASE('$pais'), telefono=UCASE('$telefono'), fax=UCASE('$fax'),

			usuario=UCASE('$usuario'), fecha=current_timestamp() WHERE id='$codigo'";

			$res=mysql_query($sqlupd,$link);

			

			$sql_limpiar=mysql_query("DELETE FROM catalogoproveedordetalle  WHERE idproveedor='$codigo'",$link);

			if($registros>0){

				for($i=0;$i<$registros;$i++){

					$sqlins=mysql_query("INSERT INTO catalogoproveedordetalle 

							(id,idproveedor,nombre,puesto,telefono,celular,email,usuario,fecha)

							VALUES 	(NULL,'$codigo', 

							'".$_POST["tabladetalle_NOMBRE"][$i]."', 

							'".$_POST["tabladetalle_PUESTO"][$i]."', 

							'".$_POST["tabladetalle_TELEFONO"][$i]."', 

							'".$_POST["tabladetalle_CELULAR"][$i]."', 

							'".$_POST["tabladetalle_EMAIL"][$i]."', 

							'$usuario',CURRENT_TIMESTAMP() )",$link) or die("error en linea ".__LINE__);

			

					$detalle .= "{

						nombre:'".$_POST["tabladetalle_NOMBRE"][$i]."',

						puesto:'".$_POST["tabladetalle_PUESTO"][$i]."',

						telefono:'".$_POST["tabladetalle_TELEFONO"][$i]."',

						celular:'".$_POST["tabladetalle_CELULAR"][$i]."',				

						email:'".$_POST["tabladetalle_EMAIL"][$i]."'},";

				}

			$detalle = substr($detalle,0,strlen($detalle)-1);

			}	

			$mensaje = 'Los cambios han sido guardados correctamente';

			$accion="modificar";

			

	}else if($accion=="limpiar"){

		$codigo	=""; $razon	="";

		$nombre	=""; $rfc	="";

		$web	=""; $calle	="";

		$numero	=""; $crucecalles="";

		$cp		=""; $colonia="";

		$poblacion=""; $municipio="";

		$estado	=""; $pais	="";

		$telefono=""; $fax	="";		

		$nombrecontacto=""; $puesto	="";

		$telefonocontacto=""; $emailcontacto="";

		$celularcontacto=""; 

		$accion	="";

		$usuario=$_SESSION[NOMBREUSUARIO];

		$row = folio('catalogoproveedor','webpmm');

		$codigo=$row[0];

	}

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<link href="../cliente/Tablas.css" rel="stylesheet" type="text/css">

<link href="../../FondoTabla.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">



<script src="../../javascript/shortcut.js"></script>

<script src="../../javascript/ClaseTabla.js"></script>

<script src="../../javascript/ajax.js"></script>

<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>

<script src="../../javascript/ajaxlist/ajax.js"></script>

<script language="JavaScript" type="text/javascript">

var u = document.all;



var Input = '<input name="colonia" type="text" class="Tablas" id="colonia" style=" width:265px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';



var combo1 = "<select name='colonia' id='colonia' class='Tablas' style='width:262px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";



	var tabla1 = new ClaseTabla();	

	

	tabla1.setAttributes({

	nombre:"tabladetalle",

	campos:[

			{nombre:"NOMBRE", medida:150, alineacion:"left", datos:"nombre"},

			{nombre:"PUESTO", medida:100, alineacion:"left", datos:"puesto"},

			{nombre:"TELEFONO", medida:100, alineacion:"left", datos:"telefono"},

			{nombre:"CELULAR", medida:80, alineacion:"left", datos:"celular"},

			{nombre:"EMAIL", medida:80, alineacion:"left", datos:"email"}

		],

		filasInicial:10,

		alto:100,

		seleccion:true,

		ordenable:false,

		//eventoClickFila:"document.all.eliminar.value=tabla1.getSelectedIdRow()",

		eventoDblClickFila:"ModificarFila()",

		nombrevar:"tabla1"

	});

	

	window.onload = function(){

		//u.unidad.focus();

		tabla1.create();

		obtenerDetalles();

	}

	

	function obtenerDetalles(){

	var datosTabla = <? if($detalle!=""){echo "[".$detalle."]";}else{echo "0";} ?>;

		if(datosTabla!=0){			

			for(var i=0; i<datosTabla.length;i++){

				tabla1.add(datosTabla[i]);

			}

		}	

	}

	

/*********/	



function agregarVar(){

	if(document.getElementById('nombrecontacto').value==""){

			alerta('Debe capturar el Nombre del Contacto', '¡Atención!','nombrecontacto');

			return false;

	}else if(document.getElementById('puesto').value==""){

			alerta('Debe capturar el Puesto del Contacto', '¡Atención!','puesto');

			return false;

	}else if(document.getElementById('emailcontacto').value!="" && !isEmailAddress(document.form1.emailcontacto)){

			alerta('Debe capturar un Email valido', '¡Atención!','emailcontacto');

			return false;

	}



	if(u.modificarfila.value!=""){

			tabla1.deleteById(document.all.modificarfila.value);

			u.modificarfila.value="";

	}	

	var registro 	= new Object();

	registro.nombre 	= u.nombrecontacto.value;

	registro.puesto		= u.puesto.value;

	registro.telefono	= u.telefonocontacto.value;

	registro.celular	= u.celularcontacto.value;

	registro.email		= u.emailcontacto.value;

	tabla1.add(registro);

	

	document.all.nombrecontacto.value	="";

	document.all.puesto.value			="";

	document.all.telefonocontacto.value	="";

	document.all.celularcontacto.value	="";

	document.all.emailcontacto.value	="";



}



	function validar(){

		u.registros.value = tabla1.getRecordCount();

		if(u.tipoproveedor.value == "0"){

				alerta('Debe capturar Tipo de Proveedor', '¡Atención!','tipoproveedor');

		}else if(document.getElementById('razon').value==""){

				alerta('Debe capturar la Razon Social', '¡Atención!','razon');

		}else if(document.getElementById('nombre').value==""){

				alerta('Debe capturar el Nombre del Proveedor', '¡Atención!','nombre');

		}else if(document.getElementById('rfc').value==""){

				alerta('Debe capturar el RFC', '¡Atención!','rfc');

		}else if(!ValidaRfc(document.getElementById('rfc').value)){

				alerta('Debe capturar un RFC valido', '¡Atención!','rfc');	

		}else if(document.getElementById('calle').value==""){

				alerta('Debe capturar la Calle', '¡Atención!','calle');

		}else if(document.getElementById('numero').value==""){

				alerta('Debe capturar el Numero', '¡Atención!','numero');		

		}else if(document.getElementById('cp').value==""){

				alerta('Debe capturar el Codigo Postal', '¡Atención!','cp');

		}else if(document.getElementById('colonia').value==""){

				alerta('Debe capturar la colonia', '¡Atención!','colonia');

		}else if(document.getElementById('pais').value==""){

				alerta('Debe capturar el pais', '¡Atención!','pais');

		}else if(document.getElementById('telefono').value==""){

				alerta('Debe capturar el Telefono', '¡Atención!','telefono');	

		}else {

			if(document.getElementById('accion').value==""){

				document.getElementById('accion').value = "grabar";

				document.form1.submit();

			}else if(document.getElementById('accion').value=="modificar"){

				document.form1.submit();

			}

		}

	}



	function obtenerProveedor(id){

		consultaTexto("mostrarProveedor","catalogoproveedores_json.php?accion=1&proveedor="+id);

	}



	function obtener(id){

		u.codigo.value = id;

		consultaTexto("mostrarProveedor","catalogoproveedores_json.php?accion=1&proveedor="+id);

	}



	function mostrarProveedor(datos){

		limpiarTodo();

		if(datos.replace("\n","").replace("\r","").replace("\n\r","")!="0"){

			var obj = eval(convertirValoresJson(datos));

			u.razon.value			= obj.principal.razon;

			u.nombre.value			= obj.principal.nombre;

			u.rfc.value				= obj.principal.rfc;

			u.web.value				= obj.principal.web;

			u.calle.value			= obj.principal.calle;

			u.numero.value			= obj.principal.numero;

			u.crucecalles.value		= obj.principal.crucecalles;

			u.cp.value				= obj.principal.cp;

			u.celcolonia.innerHTML 	= Input;

			u.colonia.value			= obj.principal.colonia;

			u.poblacion.value		= obj.principal.poblacion;

			u.municipio.value		= obj.principal.municipio;

			u.estado.value			= obj.principal.estado;

			u.pais.value			= obj.principal.pais;

			u.telefono.value		= obj.principal.telefono;

			u.fax.value				= obj.principal.fax;

			u.tipoproveedor.value   = obj.principal.tipoproveedor;

			u.accion.value			= "modificar";

			tabla1.setJsonData(obj.detalle);

		}else{

			alerta3('El código de Proveedor no existe','¡Atención!');

		}

	}

	function limpiarTodo(pedirnuevo){

			u.tipoproveedor.value   = 0;

			u.razon.value			= "";

			u.nombre.value			= "";

			u.rfc.value				= "";

			u.web.value				= "";

			u.calle.value			= "";

			u.numero.value			= "";

			u.crucecalles.value		= "";

			u.cp.value				= "";

			u.celcolonia.innerHTML 	= Input;

			u.colonia.value			= "";

			u.poblacion.value		= "";

			u.municipio.value		= "";

			u.estado.value			= "";

			u.pais.value			= "";

			u.telefono.value		= "";

			u.fax.value				= "";

			u.accion.value			= "";

			tabla1.clear();

			if(pedirnuevo!=null && pedirnuevo!=undefined)

				consultaTexto("ponerid","catalogoproveedores_json.php?accion=2");

	}

	

	function ponerid(datos){

		u.codigo.value = datos;

	}

function OptenerBuscarColonia(cp,idcol,colonia,poblacion,municipio,estado,pais){

	document.getElementById('cp').value=cp;

	document.all.celcolonia.innerHTML = Input;

	document.getElementById('colonia').value=colonia;

	document.getElementById('poblacion').value=poblacion;

	document.getElementById('municipio').value=municipio;

	document.getElementById('estado').value=estado;

	document.getElementById('pais').value=pais;

}



function trim(cadena,caja)

{

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





function foco(nombrecaja){

	if(nombrecaja=="codigo"){

		document.getElementById('oculto').value="1";

	}

}



function ValidaRfc(rfcStr) {

	var strCorrecta;

	strCorrecta = rfcStr;	

	var valid = '^(([A-Z]|[a-z]|[&]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';	

	var validRfc=new RegExp(valid);

	var matchArray=strCorrecta.match(validRfc);

	if (matchArray==null) {	

		return false;

	}else{

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



shortcut.add("Ctrl+b",function() {

	if(document.form1.oculto.value=="1"){

abrirVentanaFija('buscarproveedor.php', 550, 450, 'ventana', 'Busqueda')

	}

}

);

/********************/

function Detalle(idproveedor){

	consulta("mostrarDetalle","catalogoproveedores_con.php?accion=1&idproveedor="+idproveedor+"&sid="+Math.random());

}

function mostrarDetalle(datos){

	tabla1.setXML(datos);

}

/****************/

function EliminarFila(){

	if(tabla1.getSelectedRow()!=null){

		if(tabla1.getValSelFromField("nombre","NOMBRE")!=""){

			tabla1.deleteById(document.all.eliminar.value);

		}

	}else{

		alerta3('Seleccione la fila a eliminar','¡Atención!');

	}

}

/***/

function ModificarFila(){

	var obj = tabla1.getSelectedRow();

	if(tabla1.getValSelFromField("nombre","NOMBRE")!=""){

		document.all.nombrecontacto.value	=obj.nombre;

		document.all.puesto.value			=obj.puesto;

		document.all.telefonocontacto.value	=obj.telefono;

		document.all.celularcontacto.value	=obj.celular;

		document.all.emailcontacto.value	=obj.email;

		document.all.modificarfila.value	=tabla1.getSelectedIdRow();

	}

}

/**********************/

function CodigoPostal(e,cp){

		tecla=(document.all) ? e.keyCode : e.which;

		if(tecla==13 && cp!=""){

consulta("mostrarPostal","catalogoproveedores_con.php?accion=2&cp="+cp+"&sid="+Math.random());

		

		}	

}

function mostrarPostal(datos){

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;

document.getElementById('colonia').value=""; document.getElementById('poblacion').value=""; document.getElementById('municipio').value=""; document.getElementById('estado').value=""; document.getElementById('pais').value="";

				

		

	if(con>0){		

		

		if(datos.getElementsByTagName('total').item(0).firstChild.data>1){

			document.all.celcolonia.innerHTML = combo1;

			var combo = document.all.colonia;		

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

			

		document.all.celcolonia.innerHTML = Input;

		u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;

		u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;

		u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;

		u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;

		u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;

		u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;

		}

		}else{

			

			alerta("El Código Postal no existe",'¡Atención!','cp');

			document.all.celcolonia.innerHTML = Input;

			

		}

}

/**********************/

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

	</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<style type="text/css">

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

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

</style>

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.Estilo5 {font-size: 14px}

.Estilo6 {font-size: 12px}

-->

</style>

<style type="text/css">

	/* Big box with list of options */

	#ajax_listOfOptions{

		position:absolute;	/* Never change this one */

		width:275px;	/* Width of box */

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

</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

  <table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="457" class="estilo_relleno"><span class="FondoTabla">CAT&Aacute;LOGO PROVEEDORES</span></td>

    </tr>

    <tr>

      <td height="300">

        <table width="549" border="0" align="center" cellpadding="0" cellspacing="0">        

        <tr>

          <td width="91">Folio</td>

          <td width="164"><input name="codigo" type="text" class="Tablas" id="codigo" style="width:50px;" value="<?=$codigo ?>" onKeyPress="if(event.keyCode==13){obtenerProveedor(this.value);}"/>

            <img src="../../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarproveedor.php', 550, 450, 'ventana', 'Busqueda')"></td>

          <td width="103">Tipo Proveedor:</td>

          <td width="191"><span class="Tablas">

            <select name="tipoproveedor" class="Tablas" id="tipoproveedor" style="width:150px; text-transform:uppercase"

				>

              <option value="0" selected="selected">SELECCIONAR</option>

              <? 

						$sql=mysql_query("SELECT id,descripcion FROM catalogotipoproveedor",$link);

						while($row=mysql_fetch_array($sql)){

						

					?>

              <option value="<?=$row['id'] ?>" <? if($tipoproveedor==$row['id']){ echo "Selected"; }?>><?=$row['descripcion'] ?></option>

              <? } ?>

            </select>

          </span></td>

        </tr>

        

        <tr>

          <td>Razon Social: </td>

          <td><span class="Tablas">

            <input name="razon" type="text" class="Tablas" id="razon" nblur="trim(document.getElementById('razon').value,'razon');" onKeyPress="return tabular(event,this)" style="width:150px" value="<?=$razon ?>" />

          </span></td>

          <td>Nombre Comercial:</td>

          <td><span class="Tablas">

            <input name="nombre" type="text" class="Tablas" id="nombre" nblur="trim(document.getElementById('nombre').value,'nombre);" onKeyPress="return tabular(event,this)" style="width:150px" value="<?=$nombre ?>" />

          </span></td>

        </tr>

        

        <tr>

          <td>R.F.C.:</td>

          <td><span class="Tablas">

            <input name="rfc" type="text" class="Tablas" id="rfc" style="width:150px" onKeyPress="return tabular(event,this)" value="<?=$rfc ?>" maxlength="12" nblur="trim(document.getElementById('rfc').value,'rfc');" />

          </span></td>

          <td>Sitio Web:</td>

          <td><span class="Tablas">

            <input name="web" type="text" class="Tablas" id="web" nblur="trim(document.getElementById('web').value,'web');" onKeyPress="return tabular(event,this)" style="width:150px" value="<?=$web ?>" />

          </span></td>

        </tr>

        <tr>

          <td colspan="4">&nbsp;</td>

          </tr>

        



        <tr>

          <td colspan="4" class="estilo_relleno Estilo6" onClick="validar();">Direcci&oacute;n</td>

        </tr>

        <tr>

          <td>Calle:</td>

          <td colspan="2"><span class="Tablas">

            <input name="calle" type="text" class="Tablas" id="calle" nblur="trim(document.getElementById('calle').value,'calle');" onKeyPress="return tabular(event,this)" style="width:240px" value="<?=$calle ?>" />

          </span></td>

          <td><span class="Tablas">N&uacute;mero:

              <input name="numero" type="text" class="Tablas" id="numero" nblur="trim(document.getElementById('numero').value,'numero');" onKeyPress="return tabular(event,this)" style="width:105px" value="<?=$numero ?>" />

          </span></td>

        </tr>

        <tr>

          <td>Cruce Calles:</td>

          <td colspan="3"><span class="Tablas">

            <input name="crucecalles" type="text" class="Tablas" id="crucecalles" nblur="trim(document.getElementById('crucecalles').value,'crucecalles');" onKeyPress="return tabular(event,this)" style="width:417px" value="<?=$crucecalles ?>" />

          </span></td>

          </tr>

        <tr>

          <td colspan="4"><table width="549" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="91">C.P.:<br /></td>

              <td width="99"><input name="cp" type="text" class="Tablas" id="cp" style="width:80px" onBlur="trim(document.getElementById('cp').value,'cp');" onKeyPress="return tabular(event,this)" onKeyDown="CodigoPostal(event, this.value)" value="<?=$cp?>" maxlength="5" /></td>

              <td width="52">Colonia:</td>

              <td width="280" id="celcolonia"><input name="colonia" type="text" class="Tablas" id="colonia" style="width:265px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" value="<?=$colonia; ?>" /></td>

              <td width="27">&nbsp;</td>

            </tr>

          </table></td>

          </tr>

        <tr>

          <td>Poblaci&oacute;n:</td>

          <td><span class="Tablas">

            <input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:150px;background:#FFFF99" value="<?=$poblacion ?>" readonly="" onBlur="trim(document.getElementById('poblacion').value,'poblacion');" onKeyPress="return tabular(event,this)"/>

          </span></td>

          <td>Mun./Delg.:</td>

          <td><span class="Tablas">

            <input name="municipio" type="text" class="Tablas" id="municipio" style="width:150px;background:#FFFF99" value="<?=$municipio ?>" readonly="" onBlur="trim(document.getElementById('municipio').value,'municipio');" onKeyPress="return tabular(event,this)"/>

          </span></td>

        </tr>

        <tr>

          <td>Estado:</td>

          <td><span class="Tablas">

            <input name="estado" type="text" class="Tablas" id="estado" style="width:150px;background:#FFFF99" value="<?=$estado ?>" readonly="" onBlur="trim(document.getElementById('estado').value,'estado');" onKeyPress="return tabular(event,this)"/>

          </span></td>

          <td>Pa&iacute;s:</td>

          <td><span class="Tablas">

            <input name="pais" type="text" class="Tablas" id="pais" nblur="trim(document.getElementById('pais').value,'pais');" onKeyPress="return tabular(event,this)" style="width:150px; background-color:#FFFF99" value="<?=$pais ?>" />

          </span></td>

        </tr>

        <tr>

          <td>Tel&eacute;fono:</td>

          <td><span class="Tablas">

            <input name="telefono" type="text" class="Tablas" id="telefono" nblur="trim(document.getElementById('telefono').value,'telefono');" onKeyPress="return tabular(event,this)" style="width:150px" value="<?=$telefono ?>" />

          </span></td>

          <td>Fax:</td>

          <td><span class="Tablas">

            <input name="fax" type="text" class="Tablas" id="fax" nblur="trim(document.getElementById('fax').value,'fax');" onKeyPress="return tabular(event,this)" style="width:150px" value="<?=$fax ?>" />

          </span></td>

        </tr>

        <tr>

          <td colspan="4">&nbsp;</td>

          </tr>

        

        <tr>

          <td colspan="4" class="estilo_relleno Estilo6">Contacto</td>

        </tr>

        <tr>

          <td>Nombre:</td>

          <td colspan="3"><span class="Tablas">

            <input name="nombrecontacto" type="text" class="Tablas" id="nombrecontacto" nBlur="trim(document.getElementById('rfc').value,'nombrecontacto');" onKeyPress="return tabular(event,this)" style="width:418px" value="<?=$nombrecontacto ?>" />

          </span></td>

          </tr>

        <tr>

          <td>Puesto:</td>

          <td><span class="Tablas">

            <input name="puesto" type="text" class="Tablas" id="puesto" nBlur="trim(document.getElementById('puesto').value,'puesto');" onKeyPress="return tabular(event,this)" style="width:150px" value="<?=$puesto ?>" />

          </span></td>

          <td>Tel&eacute;fono:</td>

          <td><span class="Tablas">

            <input name="telefonocontacto" type="text" class="Tablas" id="telefonocontacto" style="width:150px"  nBlur="trim(document.getElementById('telefonocontacto').value,'telefonocontacto');" onKeyPress="return tabular(event,this)" value="<?=$telefonocontacto ?>" />

          </span></td>

        </tr>

        <tr>

          <td>Celular:</td>

          <td><span class="Tablas">

            <input name="celularcontacto" type="text" class="Tablas" id="celularcontacto"  nBlur="trim(document.getElementById('celularcontacto').value,'celularcontacto');" onKeyPress="return tabular(event,this)" style="width:150px" value="<?=$celularcontacto ?>" />

          </span></td>

          <td>Email:</td>

          <td><span class="Tablas">

            <input name="emailcontacto" type="text" class="Tablas" id="emailcontacto" nBlur="trim(document.getElementById('emailcontacto').value,'emailcontacto');" onKeyPress="return tabular(event,this)" style="width:150px" value="<?=$emailcontacto ?>" />

          </span></td>

        </tr>

        <tr>

          <td colspan="4" align="right"><div class="ebtn_agregar"  onClick="agregarVar()"></div></td>

        </tr>

        <tr>

          <td colspan="4"><table id="tabladetalle" width="549" border="0" cellspacing="0" cellpadding="0">  

</table>

</td>

        </tr>

	

	    <tr>

	      <td colspan="4" ><div class="ebtn_eliminar" onClick="EliminarFila()"></div></td>

	      </tr>

	    <tr>

	      <td colspan="4" align="right"><table width="144" border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td width="365" align="right"><div class="ebtn_guardar" onClick="validar();"></div></td>

              <td width="70" align="right"><div class="ebtn_nuevo" onClick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiarTodo(1);', '')"></div></td>

            </tr>

          </table></td>

	      </tr>

	    <tr>

          <td colspan="4" align="center"><input name="eliminar" type="hidden" id="eliminar">

            <input name="modificarfila" type="hidden" id="modificarfila">

            <input name="registros" type="hidden" id="registros">

            <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">

      <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />

      <span class="Tablas">

      <input type="hidden" id="colonia_hidden" name="coloniaid" />

      </span></td>

      </tr>

      </table>

      <br>

  </td>

    </tr>

  </table>  

</form>

</body>

</html>

<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//	}

?>