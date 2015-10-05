<?	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once("../Conectar.php");

	$l = Conectarse("webpmm");

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css" />

<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<link href="../javascript/ajaxlist/ajaxlist_estilos.css" rel="stylesheet" type="text/css">

<style type="text/css">

<!--

.Estilo1 {font-size: 14px}

.Estilo2 {

	font-size: 14px;

	font-weight: bold;

	color: #FFFFFF;

}

-->

.Estilo3 {	font-size: 8px;

	font-weight: bold;

}

.style2 {	color: #464442;

	font-size:9px;

	border: 0px none;

	background:none

}

.style5 {	color: #FFFFFF;

	font-size:8px;

	font-weight: bold;

}

body {

	margin-left: 0px;

	margin-top: 0px;

	margin-right: 0px;

	margin-bottom: 0px;

}

-->

</style>

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<script language="javascript"  src="../javascript/ajax.js"></script>

<script language="javascript"  src="../javascript/ClaseTabla.js"></script>

<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>

<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>

<script type="text/javascript" src="../convenio/validacionesConvenio.js"></script>

<script>

	var tabla1 	= new ClaseTabla();

	var valCon  = new validacionesConvenio();

	var ub 		= document.all;

	var paquete = 0;

	var peso	= 0;

	var volumen	= 0;

	var folioConvenio = 0;

	var cadena  = "";

	var gEAD    = "";

	var gratisEAD = 0;

	

	function paraConvenio(datos){

		//alert(datos);

		cadena = "";

		valCon.setDatos(datos);

		for (var i=0;i<valCon.getDescripciones(0).length;i++){

			 cadena = valCon.getDescripciones(0)[i].id +','+ valCon.getDescripciones(0)[i].descripcion +','+ cadena;			

		}	

		if(valCon.validaEADsucursal(folioConvenio,ub.sucdestino_hidden.value)){

			gratisEAD = "1";

		}

		if(valCon.validaEADsucursal(folioConvenio,'<?=$_SESSION[IDSUCURSAL]?>')==1){

			gEAD	  = "1";

		}

		if(ub.deshabilitarconvenio.value=="1"){

			folioConvenio = 0;

		}

			abrirVentanaFija("../buscadores_generales/AgregarPaquetesGuias.php?funcion=agregarDatos&idsucorigen="+ub.idsucorigenh.value+"&convenio="+folioConvenio+"&idsucdestino="+ub.sucdestino_hidden.value+ ((folioConvenio!=0)? "&caddesc="+cadena : ""),460,410,'ventana','Datos Evaluación');

	}

	

	function devolverDestino(){

		u = document.all;

		if(u.destino_hidden.value==""){

			setTimeout("devolverDestino()",500);

		}else{

			consulta("mostrarDestino", "cotizarguia_con.php?accion=3&iddestino="+u.destino_hidden.value);

		}

	}

	function mostrarDestino(datos){

		var msg = "";

		var encon 		= datos.getElementsByTagName('encontro').item(0).firstChild.data;

		if(encon>0){

			var iddestino	= datos.getElementsByTagName('iddestino').item(0).firstChild.data;

			var descripcion	= datos.getElementsByTagName('descripcion').item(0).firstChild.data;

			var poblacion	= datos.getElementsByTagName('poblacion').item(0).firstChild.data;

			var ead 		= datos.getElementsByTagName('ead').item(0).firstChild.data;

			var restringirrecoleccion = datos.getElementsByTagName('restringirrecoleccion').item(0).firstChild.data;

			ub.restringirporcobrar.value = datos.getElementsByTagName('restringirporcobrar').item(0).firstChild.data;

			ub.deshabilitarconvenio.value = datos.getElementsByTagName('deshabilitarconvenio').item(0).firstChild.data;			

			

		}else{

			var iddestino	= "";

			var descripcion	= "";

			var poblacion	= "";

			alerta("No se encontro la sucursal destino","¡Atencion!","destino");

		}

		u.sucdestino.value 			= descripcion;

		u.destino.poblacion 		= poblacion;

		u.sucdestino_hidden.value 	= iddestino;

		if(ead == "1" && ub.ocurre.checked == false){

		ub.ocurre.checked = true;

		ub.ocurre.disabled = true;

		msg = "Sucursal Destino no realiza EAD";		

		}

		

		if (restringirrecoleccion == "1"){

			ub.Chrecoleccion.disabled = true;

			if(msg!=""){

				msg += "y Recolección";

			}else{

				msg = "Sucursal Destino no realiza Recoleccion";

			}		

		}

		if(msg!=""){

			alerta(msg,'¡Atención!','Cliente');

		}

		pedirDatosGenerales();

	}	

	tabla1.setAttributes({

		nombre:"tablaguias",

		campos:[

			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},

			{nombre:"ID", medida:2, alineacion:"right", tipo:"oculto", datos:"id"},

			{nombre:"DESCRIPCION", medida:100, alineacion:"left", datos:"descripcion"},

			{nombre:"CONTENIDO", medida:95, alineacion:"left", datos:"contenido"},

			{nombre:"PES", medida:4, alineacion:"right", tipo:"oculto", datos:"peso"},

			{nombre:"LARGO", medida:2, alineacion:"right", tipo:"oculto", datos:"largo"},

			{nombre:"ANCHO", medida:2, alineacion:"right", tipo:"oculto", datos:"ancho"},

			{nombre:"ALTO", medida:2, alineacion:"right", tipo:"oculto", datos:"alto"},

			{nombre:"PESO", medida:40, alineacion:"right",  datos:"pesototal"},			

			{nombre:"VOL", medida:40, alineacion:"right", datos:"volumen"},

			{nombre:"IMPORTE", medida:65, tipo:"moneda", alineacion:"right", datos:"importe"},

			{nombre:"EXCEDENTE", medida:4, tipo:"moneda", tipo:"oculto", alineacion:"right", datos:"excedente"}

		],

		filasInicial:10,

		alto:150,

		seleccion:true,

		ordenable:false,

		eventoDblClickFila:"ModificarFila()",

		nombrevar:"tabla1"

	});

	

	window.onload = function(){

		tabla1.create();

		ub.eliminar.style.visibility ="hidden";

		ub.ocurre.focus();		

	}	

	function ModificarFila(){

		var obj = tabla1.getSelectedRow();		

		if(tabla1.getValSelFromField("cantidad","CANT")!=""){



abrirVentanaFija('../buscadores_generales/AgregarPaquetesGuias.php?cantidad='+obj.cantidad

		+'&id='+obj.id

		+'&descripcion='+obj.descripcion

		+'&contenido='+obj.contenido

		+'&peso='+obj.peso

		+'&largo='+obj.largo

		+'&ancho='+obj.ancho

		+'&alto='+obj.alto

		+'&pesototal='+obj.pesototal

		+'&volumen='+obj.volumen

		+'&importe='+obj.importe

		+'&convenio='+folioConvenio

		+'&caddesc='+((folioConvenio!=0)? "&caddesc="+cadena : ""), 460, 410, 'ventana', 'Datos Evaluación');	

		}

	}	

	function comprobarDestSuc(){

		if(ub.idsucorigenh.value == "" || ub.sucdestino_hidden.value == ""){

		alerta("No ha seleccionado una sucursal destino","¡Atencion!","destino");

			return false;

		}else{			

			abrirVentanaFija("../buscadores_generales/AgregarPaquetesGuias.php?funcion=agregarDatos&idsucorigen="+ub.idsucorigenh.value+"&convenio="+folioConvenio+"&idsucdestino="+ub.sucdestino_hidden.value+ ((folioConvenio!=0)? "&caddesc="+cadena : ""),460,410,'ventana','Datos Evaluación');

		}

	}

	function agregarDatos(variable){

	var pa = ""; var pe = ""; var vol = ""; var des = ""; var imp = ""; var exc = ""; 

	paquete = 0; peso = 0; volumen = 0; importe = 0; excedente = 0;

	

	tabla1.add(variable);

	pa = tabla1.getValuesFromField("cantidad",",").split(",");

	pe = tabla1.getValuesFromField("pesototal",",").split(",");

	vol= tabla1.getValuesFromField("volumen",",").split(",");

	des= tabla1.getValuesFromField("descripcion",",").split(",");

	imp= tabla1.getValuesFromField("importe",",").split(",");

	exc= tabla1.getValuesFromField("excedente",",").split(",");

	for(var i=0;i<pa.length;i++){

		paquete = parseFloat(pa[i]) + parseFloat(paquete);

	}

	ub.Paquetes.value = paquete;

	for(var i=0;i<pe.length;i++){

		peso = parseFloat(pe[i]) + parseFloat(peso);		

	}

	ub.Peso.value = peso;

	for(var i=0;i<vol.length;i++){

		volumen = parseFloat(vol[i]) + parseFloat(volumen);		

	}

	ub.Volumen.value = volumen;	

	for(var i=0;i<imp.length;i++){

		importe = parseFloat(imp[i]) + parseFloat(importe);		

	}

	ub.flete.value = importe;	

	ub.flete.value = "$ "+numcredvar(ub.flete.value);

	

	for(var i=0;i<exc.length;i++){

		excedente += parseFloat(exc[i]);

	}

	ub.t_txtexcedente.value = excedente;

	ub.t_txtexcedente.value = "$ "+numcredvar(ub.t_txtexcedente.value);

	

	ub.eliminar.style.visibility = "visible";

	idsucorigen = '<?=$_SESSION[IDSUCURSAL]?>';

	calculartotales();

	}	

	function eliminarFila(){

		if(tabla1.getSelectedIdRow()!=""){

		var pa = ""; var pe = ""; var vol = "";

		paquete = 0; peso = 0; volumen = 0;

	ub.Paquetes.value = parseFloat(ub.Paquetes.value)-parseFloat(tabla1.getValSelFromField("cantidad","CANT"));

	ub.Peso.value = parseFloat(ub.Peso.value)-parseFloat(tabla1.getValSelFromField("pesototal","PESO"));

	ub.Volumen.value = parseFloat(ub.Volumen.value) - parseFloat(tabla1.getValSelFromField("volumen","P_VOLU"));

	ub.flete.value = parseFloat(ub.flete.value) - parseFloat(tabla1.getValSelFromField("importe","IMPORTE"));

	ub.t_txtexcedente.value = parseFloat(ub.t_txtexcedente.value) - parseFloat(tabla1.getValSelFromField("excedente","EXCEDENTE"));

	tabla1.deleteById(tabla1.getSelectedIdRow());

		}else{

			alerta3('Debe seleccionar la fila que desea Eliminar','¡Atención!');	

		}

		if(tabla1.getRecordCount()==0){

			ub.Paquetes.value = ""; ub.Peso.value = "";

			ub.Volumen.value = "";

			ub.eliminar.style.visibility = "hidden";

			ub.flete.value = "";

		}else{

	idsucorigen = '<?=$_SESSION[IDSUCURSAL]?>';	

	descripcion = tabla1.getValSelFromField("descripcion","DESCRIPCION");

	consulta("obtenerFlete","cotizarguia_con.php?accion=8&idsucursal="+ub.sucdestino_hidden.value+"&idsucorigen="+idsucorigen+"&pesototal="+ub.Peso.value+"&volumen="+ub.Volumen.value+"&descripcion="+descripcion);

		}

	}

	function obtenerClienteBusqueda(id){

		ub.Cliente.value = id;

		consultaTexto("mostrarCliente","cotizarguia_conj.php?accion=1&cliente="+id);

	}

	function obtenerCliente(e,id){

		tecla = (ub) ? e.keyCode : e.which;

		if(tecla == 13 && id!=""){

consultaTexto("mostrarCliente","cotizarguia_conj.php?accion=1&cliente="+id);

		}

	}

	function mostrarCliente(datos){

		ub.Nombre.value = ""; ub.Apaterno.value = ""; ub.Amaterno.value = ""; folioConvenio = 0;

		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));

		if(objeto != 0){

			ub.Nombre.value   = objeto[0].nombre;

			ub.Apaterno.value = objeto[0].paterno;

			ub.Amaterno.value = objeto[0].materno;

			ub.email.value	  = objeto[0].email;

			folioConvenio	  = ((objeto[0].folioconvenio!="") ? objeto[0].folioconvenio : 0 );

		}else{

			ub.Cliente.value = "";

			alerta('El numero de Cliente no existe','¡Atención!','Cliente');

		}		

		consultaTexto("paraConvenio", "../convenio/validaconvenio.php?accion=1&idremitente="+ub.Cliente.value

+"&iddestino="+ub.destino_hidden.value+"&idsucdestino="+ub.sucdestino_hidden.value+"&valran="+Math.random());

	}	

	function pedirDatosGenerales(){

			sucursalorigen  = '<?=$_SESSION[IDSUCURSAL]?>';

	consulta("devolverDatosGenerales", "cotizarguia_con.php?accion=1&sucdestino="+ub.sucdestino_hidden.value+"&idsucorigen="+sucursalorigen+"&valrandom="+Math.random());			

	}

	function devolverDatosGenerales(datos){

		bolsaempaque = datos.getElementsByTagName('bolemp').item(0).firstChild.data;

	ocu			 = datos.getElementsByTagName('ocu').item(0).firstChild.data;

	ead			 = datos.getElementsByTagName('ead').item(0).firstChild.data;			

	avisocelular = datos.getElementsByTagName('avisocelular').item(0).firstChild.data;

	acuserecibo	 = datos.getElementsByTagName('acuserecibo').item(0).firstChild.data;

	cod			 = datos.getElementsByTagName('cod').item(0).firstChild.data;

	restrinccion = datos.getElementsByTagName('restrincciones').item(0).firstChild.data;

	restrinccion2= datos.getElementsByTagName('restr2').item(0).firstChild.data;

	emplaye 	 = datos.getElementsByTagName('emp').item(0).firstChild.data;

	costoextrae  = datos.getElementsByTagName('costoextrae').item(0).firstChild.data;

	limitee  	 = datos.getElementsByTagName('limitee').item(0).firstChild.data;

	porcadae  	 = datos.getElementsByTagName('porcadae').item(0).firstChild.data;

	//para totales

	pt_ead		 = datos.getElementsByTagName('costoead').item(0).firstChild.data;

	pt_recoleccion = datos.getElementsByTagName('recoleccion').item(0).firstChild.data;

	pt_iva				= datos.getElementsByTagName('iva').item(0).firstChild.data;

	pt_ivaretenido		= datos.getElementsByTagName('ivaretenido').item(0).firstChild.data;

	por_combustible		= datos.getElementsByTagName('combustible').item(0).firstChild.data;

	max_descuento		= datos.getElementsByTagName('max_des').item(0).firstChild.data;

	porcada			= datos.getElementsByTagName('porcada').item(0).firstChild.data;

	costo				= datos.getElementsByTagName('costo').item(0).firstChild.data;

	recoleccion		= datos.getElementsByTagName('recoleccion').item(0).firstChild.data;

	pesominimodesc		= datos.getElementsByTagName('pesominimodesc').item(0).firstChild.data;	



				ub.pc_ead.value					= pt_ead;

				ub.pc_recoleccion.value			= recoleccion;

				ub.pc_tarifacombustible.value	= por_combustible;

				ub.pc_maximodescuento.value		= max_descuento;

				ub.pc_porcada.value				= porcada;

				ub.pc_costo.value				= costo;

				ub.pc_iva.value					= pt_iva;

				ub.pc_ivaretenido.value			= pt_ivaretenido;

				ub.pc_pesominimodesc.value		= pesominimodesc;

				ub.t_txtexcedente.value				= "$ 0.00";		

				

				ub.txtrestrinccion.value = (restrinccion==0)?"":restrinccion;

				if(restrinccion2!=0){

					ub.txtrestrinccion.value += "La entrega se hara hasta el dia "+restrinccion2;

				}			

				u.txtavisocelular1h.value 	= "$ "+numcredvar(avisocelular);

				u.txtacusereciboh.value 	= "$ "+numcredvar(acuserecibo);

				u.txtcodh.value 			= "$ "+numcredvar(cod);

				u.txtemplayeh.value 		= numcredvar(emplaye);			

				u.txtbolsaempaqueh.value 	= bolsaempaque;

				u.txtocu.value 				= ocu;

				u.txtead.value 				= ead;	

				u.costoextra.value			= costoextrae;

				u.limite.value				= limitee;

				u.porcada.value				= porcadae;

				if (u.ocurre.checked==true){

				u.t_txtead.value			= "$ 0.00";

				}else{

				u.t_txtead.value			= "$ "+numcredvar(pt_ead);	

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

	function calculartotales(){

				var u = document.all;		

			calcularservicios();

			

var ptflete = parseFloat((u.flete.value=="")?0:u.flete.value.replace("$ ","").replace(/,/g,""));

var ptdescuento = parseFloat((u.t_txtdescuento1.value=="")?0:u.t_txtdescuento1.value.replace("$ ","").replace(/,/g,""));

var ptead 				= parseFloat((u.t_txtead.value=="")?0:u.t_txtead.value.replace("$ ","").replace(/,/g,""));

var ptrecoleccion = parseFloat((u.t_txtrecoleccion.value=="")?0:u.t_txtrecoleccion.value.replace("$ ","").replace(/,/g,""));

var ptseguro	 		= parseFloat((u.t_txtseguro.value=="")?0:u.t_txtseguro.value.replace("$ ","").replace(/,/g,""));

var ptotros		 		= parseFloat((u.t_txtotros.value=="")?0:u.t_txtotros.value.replace("$ ","").replace(/,/g,""));

var ptexcedente			= parseFloat((u.t_txtexcedente.value=="")?0:u.t_txtexcedente.value.replace("$ ","").replace(/,/g,""));

var ptcombustible = parseFloat((u.t_txtcombustible.value=="")?0:u.t_txtcombustible.value.replace("$ ","").replace(/,/g,""));

var ptsubtotal			= ptflete-ptdescuento+ptead+ptrecoleccion+ptseguro+ptotros+ptexcedente+ptcombustible;



u.t_txtsubtotal.value	= "$ "+numcredvar((Math.round(ptsubtotal*100)/100).toLocaleString());



u.t_txtiva.value		= Math.round((ptsubtotal*(parseFloat(u.pc_iva.value)/100))*100)/100;



u.t_txtivaretenido.value = Math.round((ptsubtotal*(parseFloat(u.pc_ivaretenido.value)/100))*100) /100;



u.t_txttotal.value		= Math.round( (ptsubtotal-parseFloat(u.t_txtivaretenido.value.replace("$ ","").replace(/,/g,""))+parseFloat(u.t_txtiva.value.replace("$ ","").replace(/,/g,"")) ) *100)/100;



u.t_txtiva.value		= "$ "+numcredvar(u.t_txtiva.value.toLocaleString());



u.t_txtivaretenido.value= "$ "+numcredvar(((u.t_txtivaretenido.value=="")?"0":u.t_txtivaretenido.value).toLocaleString());



u.t_txttotal.value		= "$ "+numcredvar(u.t_txttotal.value.toLocaleString());		



	}

	

	function calcularservicios(){

		u = document.all;		

		if((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))*0.10)<parseFloat(u.pc_ead.value)){

				u.t_txteadh.value = "$ "+numcredvar(u.pc_ead.value);				



		}else{

				valoread = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-parseFloat((u.t_txtdescuento1.value=="")?0:u.t_txtdescuento1.value.replace("$ ","").replace(/,/g,"")))*.10)*100)/100;

				u.t_txteadh.value = "$ "+numcredvar(valoread.toLocaleString());

		};

			u.t_txteadh.value = (u.t_txteadh.value=="")?"$ 0.00":u.t_txteadh.value;

			

		if(u.ocurre.checked==false){

				u.t_txtead.value = u.t_txteadh.value;

		}else{

				u.t_txtead.value = '$ 0.00';

		} 

		if(gEAD=="1"){

			u.t_txtead.value = '$ 0.00';

		}

		if(gratisEAD =="1"){

			u.t_txtead.value = '$ 0.00';

		}

			/*if((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))*0.10)<parseFloat(u.pc_recoleccion.value)){

				u.t_txtrecoleccion.value = "$ "+numcredvar(u.pc_recoleccion.value);

			}else{

				valorrecoleccion = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-parseFloat((u.t_txtdescuento1.value=="")?0:u.t_txtdescuento1.value.replace("$ ","").replace(/,/g,"")))*.10)*100)/100;

				u.t_txtrecoleccion.value = "$ "+numcredvar(valorrecoleccion.toLocaleString());

			}

			

			u.t_txtrecoleccion.value = (u.t_txtrecoleccion.value=="")?"$ 0.00":u.t_txtrecoleccion.value;

			

			u.t_txtrecoleccion.value = "";*/

			

			if(u.txtdeclarado.value!="" && u.txtdeclarado.value!="0"){

				if(parseFloat(u.txtdeclarado.value.replace("$ ","").replace(/,/g,""))<parseFloat(u.pc_porcada.value)){					

					u.t_txtseguro.value = "$ "+numcredvar(u.pc_costo.value);

				}else{					

					valorseguro = Math.round(((parseFloat(((u.txtdeclarado.value=="")?"0":u.txtdeclarado.value.toLocaleString()).replace("$ ","").replace(/,/g,""))/parseFloat(u.pc_porcada.value))*parseFloat(u.pc_costo.value))*100)/100;					

					u.t_txtseguro.value = "$ "+numcredvar(valorseguro.toLocaleString());

				}

			}else{

				u.t_txtseguro.value = "$ "+numcredvar(u.pc_costo.value);

			}

			u.t_txtseguro.value = (u.t_txtseguro.value=="")?"$ 0.00":u.t_txtseguro.value;



valorcombustible = Math.round(((parseFloat(u.flete.value.replace("$ ","").replace(/,/g,""))-parseFloat((u.t_txtdescuento2.value=="")?0:u.t_txtdescuento2.value.replace("$ ","").replace(/,/g,""))+parseFloat(u.t_txtexcedente.value.replace("$ ","").replace(/,/g,"")))*(parseFloat(u.pc_tarifacombustible.value)/100))*100)/100;



u.t_txtcombustible.value = "$ "+numcredvar(valorcombustible.toLocaleString());

u.t_txtcombustible.value = (u.t_txtcombustible.value=="")?"$ 0.00":u.t_txtcombustible.value;



u.t_txtrecoleccion.value = (u.t_txtrecoleccion.value=="")?"$ 0.00":u.t_txtrecoleccion.value;



u.t_txtdescuento1.value  = (u.t_txtdescuento1.value=="")?"0 %":u.t_txtdescuento1.value;



u.t_txtdescuento1.value  = (u.t_txtdescuento1.value=="")?"$ 0.00":u.t_txtdescuento1.value;

		

var templaye 		= parseFloat((u.txtemplaye.value=="")?0:u.txtemplaye.value.replace("$ ","").replace(/,/g,""));

var tbolsaempaque	= parseFloat((u.txtbolsaempaque2.value=="")?0:u.txtbolsaempaque2.value.replace("$ ","").replace(/,/g,""));

var tavisocelular	= parseFloat((u.txtavisocelular1.value=="")?0:u.txtavisocelular1.value.replace("$ ","").replace(/,/g,""));

var tdeclarado		= parseFloat((u.txtdeclarado.value=="")?0:u.txtdeclarado.value.replace("$ ","").replace(/,/g,""));

var tacuserecibo	= parseFloat((u.txtacuserecibo.value=="")?0:u.txtacuserecibo.value.replace("$ ","").replace(/,/g,""));

var tcod			= parseFloat((u.txtcod.value=="")?0:u.txtcod.value.replace("$ ","").replace(/,/g,""));



	u.t_txtotros.value 	= templaye+tbolsaempaque+tavisocelular+tacuserecibo+tcod;		

	u.t_txtotros.value 	= "$ "+numcredvar(u.t_txtotros.value);



	}	

	

	function CalcularEmplaye(){

		var u = document.all;

	if(u.Chemplaye.checked==true){	

		if(parseFloat(u.Peso.value) > parseFloat(u.Volumen.value)){		

			if(parseFloat(u.Peso.value) <= parseFloat(u.limite.value)){

		u.txtemplaye.value=u.txtemplayeh.value;		

			}else{			

		var kgextra=parseFloat(u.Peso.value) - parseFloat(u.limite.value);	

				u.txtemplaye.value=parseFloat(u.txtemplayeh.value) + parseFloat(((kgextra / parseFloat(u.porcada.value)) * parseFloat(u.costoextra.value)));

				}

				

				if(u.txtemplaye.value=='NaN'){

					u.txtemplaye.value="";

				}else{u.txtemplaye.value="$ "+numcredvar(u.txtemplaye.value);}			

			}else{ 		

				if(parseFloat(u.Volumen.value)<=parseFloat(u.limite.value)){

			u.txtemplaye.value=parseFloat(u.txtemplayeh.value);

				}else{				

				var kgextra=parseFloat(u.Volumen.value)-parseFloat(u.limite.value);

				u.txtemplaye.value=parseFloat(u.txtemplayeh.value) + parseFloat(((kgextra / parseFloat(u.porcada.value)) * parseFloat(u.costoextra.value)));

				}

				if(u.txtemplaye.value=='NaN'){

					u.txtemplaye.value="";

				}else{u.txtemplaye.value="$ "+numcredvar(u.txtemplaye.value);}

			}	

		calculartotales();

	}else{

		u.txtemplaye.value="";

	}



	}

	function enviarMeil(){

		if(ub.email.value==""){

			alerta3("El Cliente "+ub.Nombre.value+" no tiene cuenta de correo.","¡Atención!");	

		}else{

			consulta("respuesta","cotizarguia_con.php?accion=9&direccion="+ub.email.value);

		}

	}

	function respuesta(datos){

		if(datos.getElementsByTagName('envio').item(0).firstChild.data==1){

			alerta3('La cotización se envio satisfactoriamente','¡Atención!');

		}else{

			alerta3('Hubo un Error','¡Atención!');

		}

	}

	function permitirDescuento(){

		u = document.all;

		u.t_txtdescuento1.readOnly = false;

		u.t_txtdescuento1.style.backgroundColor = "#FFFFFF";

		u.t_txtdescuento1.focus();

		//u.t_txtdescuento1.select();

		u.t_txtdescuento1.value = "";

		//u.t_txtdescuento1.value = u.t_txtdescuento1.value.replace(" %","");

		

	}

	function validarDescuento(){		

		u = document.all;

		if(( (parseFloat(u.Peso.value)>parseFloat(u.Volumen.value))?parseFloat(u.Peso.value):parseFloat(u.Volumen.value))<=parseFloat(u.pc_pesominimodesc.value)){

			alerta("Para aplicar descuento, el peso total debe ser mayor a "+u.pc_pesominimodesc.value+" kg","¡Atencion!","flete");

			return false;

		}else if(u.flete.value=="" || u.flete.value=="$ 0.00"){

			alerta("No puede aplicar descuento si el flete no ha sido calculado.","¡Atencion!","flete");

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

	function limpiar(){

		ub.Folio.value 					= "";

		ub.Origen.value 				= "";

		ub.Fecha.value 					= "";		

		ub.destino.value 				= "";

		ub.destino_hidden.value 		= "";

		ub.npobdes.value 				= "";

		ub.sucdestino.value 			= "";

		ub.sucdestino_hidden.value 		= "";

		ub.Cliente.value 				= "";

		ub.Nombre.value 				= "";

		ub.Apaterno.value 				= "";

		ub.Amaterno.value 				= "";

		ub.txtocu.value 				= "";

		ub.txtead.value 				= "";

		ub.txtrestrinccion.value 		= "";

		ub.Observaciones.value 			= "";

		ub.Paquetes.value 				= "";

		ub.Peso.value 					= "";

		ub.Volumen.value 				= "";

		ub.txtemplaye.value 			= "";

		ub.txtemplayeh.value 			= "";

		ub.txtbolsaempaque1.value 		= "";

		ub.txtbolsaempaque2.value 		= "";

		ub.txtbolsaempaqueh.value 		= "";

		ub.txtacuserecibo.value 		= "";

		ub.txtacusereciboh.value 		= "";

		ub.txtcod.value 				= "";

		ub.txtcodh.value 				= "";

		ub.txtavisocelular1.value 		= "";

		ub.txtavisocelular1h.value 		= "";

		ub.txtdeclarado.value 			= "";

		ub.limite.value 				= "";

		ub.costoextra.value 			= "";

		ub.porcada.value 				= "";

		ub.pc_ead.value 				= "";

		ub.pc_recoleccion.value 		= "";

		ub.pc_porcada.value 			= "";

		ub.pc_costo.value 				= "";

		ub.pc_tarifacombustible.value 	= "";

		ub.pc_iva.value 				= "";

		ub.pc_ivaretenido.value 		= "";

		ub.pc_maximodescuento.value 	= "";

		ub.pc_pesominimodesc.value		= "";		

		ub.email.value 					= "";

		ub.flete.value 					= "$ 0.00";

		ub.t_txtdescuento1.value 		= "$ 0.00";

		ub.t_txtdescuento2.value 		= "$ 0.00";

		ub.t_txtead.value 				= "$ 0.00";

		ub.t_txtrecoleccion.value 		= "$ 0.00";

		ub.t_txtseguro.value 			= "$ 0.00";

		ub.t_txtotros.value 			= "$ 0.00";

		ub.t_txtexcedente.value 		= "$ 0.00";

		ub.t_txtcombustible.value 		= "$ 0.00";

		ub.t_txtsubtotal.value 			= "$ 0.00";

		ub.t_txtiva.value 				= "$ 0.00";

		ub.t_txtivaretenido.value 		= "$ 0.00";

		ub.t_txttotal.value 			= "$ 0.00";	

		if(ub.ocurre.disabled == true){

		ub.ocurre.disabled  			= false;

		ub.ocurre.checked				= false;

		}else{

		ub.ocurre.checked				= false;

		}

		ub.Chemplaye.checked			= false;

		ub.Chbolsa.checked				= false;

		ub.Chacuse.checked				= false;

		ub.Chcod.checked				= false;

		ub.Chcelular.checked			= false;

		ub.valordeclarado.checked		= false;

		if(ub.Chrecoleccion.disabled==true){

		ub.Chrecoleccion.disabled		= false;

		ub.Chrecoleccion.checked		= false;

		}else{

		ub.Chrecoleccion.checked		= false;

		}

		tabla1.clear();

		consultaTexto("mostrarDatosArriba","cotizarguia_conj.php?accion=4&idsucursal="+ub.idsucorigenh.value)

	}

	function mostrarDatosArriba(datos){

		var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));

		ub.Origen.value   = objeto[0].origen;

		ub.Folio.value   = objeto[0].folio;

		ub.Fecha.value   = objeto[0].fecha;

		ub.destino.focus();

	}

	function obtenerSucursal(id){

		ub.idsucorigenh.value	= id;

		consultaTexto("mostrarSucursalOri","cotizarguia_conj.php?accion=3&sucursal="+id)

	}

	function mostrarSucursalOri(datos){	

	var objeto = eval(datos.replace(new RegExp('\\n','g'),"").replace(new RegExp('\\r','g'),""));

	ub.Origen.value   = objeto[0].descripcion;

	ub.destino.focus();

	}

</script>

</head>

<body>

<form id="form1" name="form1" method="post" action="">

 

<table width="601" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="601" class="FondoTabla">COTIZAR GU&Iacute;A</td>

  </tr>

  <tr>

    <td><table width="600" border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td width="478">&nbsp;</td>

        <td colspan="2" align="right"></td>

      </tr>

      <?

	  	$s = "SELECT '001' AS folio, DATE_FORMAT(CURRENT_DATE , '%d/%m/%Y') as fecha, (SELECT descripcion FROM catalogosucursal WHERE id = ".$_SESSION[IDSUCURSAL].") as origen";

		$r = mysql_query($s,$l) or die($s);

		$f = mysql_fetch_object($r);

	  ?>

      <tr>

        <td><label></label>

          <label>Folio:

            <input name="Folio" type="text" id="Folio" style="background:#FFFF99; text-align:right" value="<?=$f->folio?>" readonly=""/>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Suc Origen:

            <input name="Origen" type="text"  id="Origen" style="width:140px;background:#FFFF99" value="<?=$f->origen?>"  readonly=""/>

            <input name="idsucorigenh" type="hidden" id="idsucorigenh" value="<?=$_SESSION[IDSUCURSAL]?>" />

            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('../buscadores_generales/buscarsucursal.php', 625, 550, 'ventana', 'Busqueda')"></label></td>

        <td width="118"> <div align="right" >Fecha:

            <input name="Fecha" type="text" id="Fecha" style="background:#FFFF99;width:70px; text-align:right" value="<?=$f->fecha?>" readonly=""/>

        </div></td>

        <td width="9">&nbsp;</td>

</tr>

      

      <tr>

        <td colspan="3" >

          <input name="ocurre" type="checkbox"  id="ocurre" value="1" onKeyPress="if(event.keyCode==13){document.all.destino.focus();}" />

Ocurre &nbsp;&nbsp;&nbsp;&nbsp;Destino:

<input type="text" name="destino" id="destino" style="width:150px; font-size:9px" 

        onkeyup="ajax_showOptions(this,'getCountriesByLetters',event,'buscarSucursales.php')" 

        onChange="devolverDestino()" onKeyPress="if(event.keyCode==13){devolverDestino(); document.all.Cliente.focus();}"

        onBlur="devolverDestino()"> 

        <input type="hidden" name="destino_hidden">

        <input type="hidden" name="npobdes">

               

Suc. Destino:

<input name="sucdestino" type="text" id="sucdestino" style="width:150px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$destino?>" poblacion="" size="20" />

<input type="hidden" name="sucdestino_hidden" /></td>

      </tr>

      <tr>

        <td colspan="3" >

        <table width="600">

        	<tr>

            	<td><p>Cliente:</p></td>

                <td><input name="Cliente" type="text"  id="Cliente" style="width:35px" value="<?=$Cliente ?>" onKeyPress="obtenerCliente(event,this.value);"/></td>

                <td><div class="ebtn_buscar" onclick=            "abrirVentanaFija('../buscadores_generales/buscarClienteGen.php?funcion=obtenerClienteBusqueda', 625, 418, 'ventana', 'Busqueda')"></div></td>

            	<td>Nombre</td>

                <td><input name="Nombre" type="text"  id="Nombre" style="width:140px;background:#FFFF99" value=""  readonly=""/></td>

                <td>Ap. Paterno</td>

            	<td><input name="Apaterno" type="text"  id="Apaterno" style="width:70px;background:#FFFF99" value=""  readonly=""/></td>

                <td>Ap. Materno</td>

            	<td><input name="Amaterno" type="text"  id="Amaterno" style="width:70px;background:#FFFF99" value=""  readonly=""/></td>

            </tr>

        </table>

        </td>

      </tr>

      <tr>

        <td colspan="3">&nbsp;</td>

      </tr>

      <tr>

        <td colspan="3" align="left">

          <table width="600" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="414"><table id="tablaguias" border="0" cellpadding="0" cellspacing="0"></table></td>

              <td width="176"><table width="173" border="0" cellpadding="0" cellspacing="0">

                <tr>

                  <td colspan="2" class="FondoTabla" >Tiempo De Entrega</td>

                  </tr>

                <tr>

                  <td >Ocurre:</td>

                  <td ><input name="txtocu" type="text"  id="txtocu" style="width:70px;background:#FFFF99" value="<?=$Ocurre ?>"  readonly="" /></td>

                  </tr>

                <tr>

                  <td width="53" >EAD:</td>

                  <td width="120" ><input name="txtead" type="text"  id="txtead" style="width:70px;background:#FFFF99" value="<?=$Ead ?>"  readonly="" /></td>

                  </tr>

                <tr class="FondoTabla">

                  <td colspan="2" >Restricciones</td>

                  </tr>

                <tr>

                  <td colspan="2" ><label>

                    <textarea name="txtrestrinccion"  id="txtrestrinccion" style="width:150px"><?=$Restricciones ?>

                  </textarea>

                    </label></td>

                  </tr>

                <tr class="FondoTabla">

                  <td colspan="2">Observaciones</td>

                  </tr>

                <tr>

                  <td height="24" colspan="2" ><label></label>

                    <textarea name="Observaciones"  id="Observaciones" style="width:150px"><?=$Observaciones ?>

                  </textarea></td>

                  </tr>

                <tr>

                  <td colspan="2" ><span class="Tablas">

                    <input name="email" type="hidden" id="email" value="<?=$email ?>" />

                    </span></td>

                  </tr>

                </table></td>

              </tr>

  </table>

          

          </td>       

      </tr>

      <tr>

        <td colspan="3">

          <table width="600" cellpadding="0" cellspacing="0">

            <tr>

              <td width="51">

                T. Paq.:          </td>

              <td width="49"><input name="Paquetes" type="text"  id="Paquetes" style="width:40px; background:#FF9" value="<?=$Paquetes ?>" readonly="readonly"/></td>

              <td width="44">T. Peso: </td>

              <td width="48"><input name="Peso" type="text"  id="Peso" style="width:40px; background:#FF9" value="<?=$Peso ?>" readonly="readonly"/></td>

              <td width="45">T. Vol.:</td>

              <td width="123"><input name="Volumen" type="text"  id="Volumen" style="width:50px; background:#FF9" value="<?=$Volumen ?>" readonly="readonly"/>

                <img src="../img/Boton_Eliminar.gif" name="eliminar" width="65" height="20" align="absbottom" id="eliminar" onClick="eliminarFila();" style="cursor:pointer" /></td>

              <td width="246">

                <div class="ebtn_agregar" onClick="comprobarDestSuc()"></div>

                </td>

              </tr>

            </table>  </td>

      </tr>

      <tr>

        <td colspan="3"><table width="600" border="0" cellpadding="0" cellspacing="0">

          <tr>

            <td width="253" class="FondoTabla">Servicios</td>

            <td colspan="2" rowspan="2" ></td>

            <td width="315" colspan="2" bgcolor="#74B051" class="Estilo2" >Totales

              <input type="hidden" name="pc_ead" />

              <input type="hidden" name="pc_recoleccion" />

              <input type="hidden" name="pc_porcada" />

              <input type="hidden" name="pc_costo" />

              <input type="hidden" name="pc_tarifacombustible" />

              <input type="hidden" name="pc_iva" />

              <input type="hidden" name="pc_ivaretenido" />

              <input type="hidden" name="pc_maximodescuento" />

              <input type="hidden" name="pc_pesominimodesc" /></td>

            </tr>

          <tr>

            <td ><table width="238" border="0" cellpadding="0" cellspacing="0">

              <tr>

                <td width="238"><label>

                  <input name="Chemplaye" type="checkbox" id="Chemplaye" value="1"

                  onClick="if(tabla1.getRecordCount()==0){

                  document.all.Chemplaye.checked = false;

                  alerta3('Debe capturar una Evaluaci&oacute;n para habilitar servicios','&iexcl;Atenci&oacute;n!');

                  }else{if(!this.checked){document.all.txtemplaye.value=''; calculartotales();}else{ CalcularEmplaye();

                  }}" />

                  </label>

                  <label>Emplaye

                    <input name="txtemplaye" type="text"  id="txtemplaye" style="width:50px;background:#FFFF99" value="<?=$Emplaye ?>"  readonly="" />

                    <span class="Tablas">

                      <input name="txtemplayeh" type="hidden" />

                      </span></label></td>

                </tr>

              <tr>

                <td ><label>

                  <input name="Chbolsa" type="checkbox" id="Chbolsa" value="1" onClick="if(tabla1.getRecordCount()==0){

                  document.all.Chbolsa.checked = false;

                  alerta3('Debe capturar una Evaluaci&oacute;n para habilitar servicios','&iexcl;Atenci&oacute;n!');

                  }else{if(!this.checked){document.all.txtbolsaempaque1.value = ''; document.all.txtbolsaempaque2.value = ''; 

document.all.txtbolsaempaque1.value=''; calculartotales();

                  }else{

if(document.all.txtbolsaempaqueh.value=='' || document.all.txtbolsaempaqueh.value=='0'){document.all.txtbolsaempaque1.readOnly=false; document.all.txtbolsaempaque1.style.backgroundColor='#FFFFFF';

}else{

document.all.txtbolsaempaque1.focus(); }}} " />

                  </label>

                  <label>Bolsa de empaque

                    <input name="txtbolsaempaque1" type="text"  id="txtbolsaempaque1" style="width:20px" value="<?=$Bolsa ?>"  onkeypress=

                      "if(event.keyCode==13){

document.all.txtbolsaempaque2.value = parseFloat(document.all.txtbolsaempaque1.value) *  parseFloat(document.all.txtbolsaempaqueh.value);  document.all.txtbolsaempaque2.value='$ ' +numcredvar(document.all.txtbolsaempaque2.value); document.all.Chrecoleccion.focus(); calculartotales();} "  />

                    <input name="txtbolsaempaque2" type="text"  id="txtbolsaempaque2" style="width:45px;background:#FFFF99" value="<?=$Bolsab ?>"  readonly="" />

                    <span class="Tablas">

                    <input name="txtbolsaempaqueh" type="hidden" id="txtbolsaempaqueh" />

                    </span></label></td>

                </tr>

              <tr>

                <td ><input name="Chrecoleccion" type="checkbox" id="Chrecoleccion" value="1" onClick="if(tabla1.getRecordCount()==0){

                  document.all.Chrecoleccion.checked = false;

                  alerta3('Debe capturar una Evaluaci&oacute;n para habilitar servicios','&iexcl;Atenci&oacute;n!');

                  }else{

                  if(!this.checked){document.all.t_txtrecoleccion.value='$ 0.00';}else{document.all.t_txtrecoleccion.value=document.all.pc_recoleccion.value;

                  calculartotales();}

                  }" />

                  Recolecci&oacute;n</td>

                </tr>

              <tr>

                <td ><label>

                  <input name="Chacuse" type="checkbox" id="Chacuse" value="1" onClick="if(tabla1.getRecordCount()==0){

                  document.all.Chacuse.checked = false;

                  alerta3('Debe capturar una Evaluaci&oacute;n para habilitar servicios','&iexcl;Atenci&oacute;n!');

                  }else{if(!this.checked){document.all.txtacuserecibo.value='';}else{document.all.txtacuserecibo.value=document.all.txtacusereciboh.value;} calculartotales();}" />

                  </label>

                  <label>Acuse de Recibo

                    <input name="txtacuserecibo" type="text"  id="txtacuserecibo"  style="width:50px;background:#FFFF99" value="<?=$Acuse ?>"  readonly="" />

                    <span class="Tablas">

                      <input name="txtacusereciboh" type="hidden" />

                      </span></label></td>

                </tr>

              <tr>

                <td ><label>

                  <input name="Chcod" type="checkbox" id="Chcod" value="1" onClick="if(tabla1.getRecordCount()==0){

                  document.all.Chcod.checked = false;

                  alerta3('Debe capturar una Evaluaci&oacute;n para habilitar servicios','&iexcl;Atenci&oacute;n!');

                  }else{if(!this.checked){document.all.txtcod.value='';}else{document.all.txtcod.value=document.all.txtcodh.value;} calculartotales();}" />

                  </label>

                  <label>COD

                    <input name="txtcod" type="text"  id="txtcod"  style="width:50px;background:#FFFF99" value="<?=$Cod ?>"  readonly="" />

                    <span class="Tablas">

                      <input name="txtcodh" type="hidden" />

                      </span></label></td>

              </tr>

              <tr>

                <td ><label>

                  <input name="Chcelular" type="checkbox" id="Chcelular" value="1" onClick="if(tabla1.getRecordCount()==0){

                  document.all.Chcelular.checked = false;

                  alerta3('Debe capturar una Evaluaci&oacute;n para habilitar servicios','&iexcl;Atenci&oacute;n!');

                  }else{if(!this.checked){document.all.txtavisocelular1.value=''; }else{document.all.txtavisocelular1.value=document.all.txtavisocelular1h.value;}  calculartotales();}"/>

                  </label>

                  <label>Aviso al Celular

                    <input name="txtavisocelular1" type="text"  id="txtavisocelular1" style="width:50px;background:#FFFF99" value="<?=$Celular ?>"  readonly="" />

                    <span class="Tablas">

                      <input name="txtavisocelular1h" type="hidden" />

                      </span></label></td>

              </tr>

              <tr>

                <td ><input type="checkbox" name="valordeclarado" id="valordeclarado" value="1" onClick="if(tabla1.getRecordCount()==0){

                  document.all.valordeclarado.checked = false;

                  alerta3('Debe capturar una Evaluaci&oacute;n para habilitar servicios','&iexcl;Atenci&oacute;n!');

                  }else{if(!this.checked){                  

                  document.all.txtdeclarado.readOnly=true; 

                  document.all.txtdeclarado.style.backgroundColor='#FFFF99';

                  }else{

                  document.all.txtdeclarado.readOnly=false; 

                  document.all.txtdeclarado.style.backgroundColor=''; 

                  document.all.txtdeclarado.focus();} calculartotales();}"/>

                  Valor Declarado

                  <input name="txtdeclarado" type="text"  id="txtdeclarado" style="width:70px; background:#FF9" value="<?=$Declarado ?>" readonly="readonly" onBlur="if(this.readOnly==false){this.value=this.value.replace('$ ','').replace(/,/,'');if(this.value==''){this.value='$ 0.00';}else{ this.value='$ '+numcredvar(this.value); calculartotales(); }}" onKeyPress="if(this.readOnly==false){ if(event.keyCode==13){ if(this.value==''){this.value=0;} this.value='$ '+numcredvar(this.value.replace('$ ','').replace(/,/,'')); calculartotales();}else{return solonumeros(event);}} "  /></td>

                </tr>

              <tr>

                <td >&nbsp;</td>

              </tr>

              <tr>

                <td >&nbsp;</td>

              </tr>

              <tr>

                <td >&nbsp;</td>

              </tr>

              <tr>

                <td >&nbsp;</td>

              </tr>

              <tr>

                <td ><input name="limite" type="hidden" id="limite" value="<?=$limite ?>" />

                  <input name="costoextra" type="hidden" id="costoextra" value="<?=$costoextra ?>" />

                  <input name="porcada" type="hidden" id="porcada" value="<?=$porcada ?>" />

                  <input name="restringirporcobrar" type="hidden" id="restringirporcobrar" value="<?=$restringirporcobrar ?>" />

                  <input name="deshabilitarconvenio" type="hidden" id="deshabilitarconvenio" value="<?=$deshabilitarconvenio ?>" /></td>

                </tr>

              </table></td>

            <td colspan="2" ><table width="310" border="0" align="right" cellpadding="0" cellspacing="0">

              <tr>

                <td width="61" ><label>Flete </label></td>

                <td width="65" ><input name="flete" type="text"  id="flete" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                <td width="66" ><label>Seguro </label></td>

                <td width="54" ><input name="t_txtseguro" type="text"  id="t_txtseguro" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                </tr>

              <tr>

                <td ><label>Descuento </label></td>

                <td ><span class="Tablas">

                  <input readonly="true" name="t_txtdescuento1" type="text" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; width:35px" value="0%"  onKeyPress="if(event.keyCode==13 && this.readOnly==false){ if(parseFloat(this.value)>parseFloat(document.all.pc_maximodescuento.value)){ this.value=document.all.pc_maximodescuento.value; alerta('El maximo descuento permitido es '+document.all.pc_maximodescuento.value+' %','¡Atencion!','t_txtdescuento1')} calcularDescuento()}else{return solonumeros(event);}" />

                  <input name="t_txtdescuento2" type="text" readonly="true" style="background:#FFFF99;font:tahoma; font-size:9px; text-align:right; width:45px" value="$ 0.00"  />

                  <img id="img_descuento" src="../img/update.gif" onClick="if(validarDescuento()){ abrirVentanaFija('../buscadores_generales/logueo_permisos.php?modulo=GuiaVentanilla&usuario=Admin&funcion=permitirDescuento', 370, 500, 'ventana', 'Inicio de Sesi&oacute;n Secundaria');}" style="cursor:hand"></span></td>

                <td ><label>Otro </label></td>

                <td ><input name="t_txtotros" type="text"  id="t_txtotros" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                </tr>

              <tr>

                <td ><label>EAD </label>

                  <span class="Tablas">

                    <input name="t_txteadh" type="hidden" />

                    </span></td>

                <td ><input name="t_txtead" type="text"  id="t_txtead" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                <td ><label>Excedente</label></td>

                <td ><input name="t_txtexcedente" type="text"  id="t_txtexcedente" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                </tr>

              <tr>

                <td ><label>Recoleccion </label></td>

                <td ><input name="t_txtrecoleccion" type="text"  id="t_txtrecoleccion" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                <td ><label>Combustible </label></td>

                <td ><input name="t_txtcombustible" type="text"  id="t_txtcombustible" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                </tr>

              <tr>

                <td colspan="2" >&nbsp;</td>

                <td ><label>SubTotal </label></td>

                <td ><input name="t_txtsubtotal" type="text"  id="t_txtsubtotal" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                </tr>

              <tr>

                <td height="16" colspan="2" >&nbsp;</td>

                <td ><label>IVA </label></td>

                <td ><input name="t_txtiva" type="text"  id="t_txtiva" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                </tr>

              <tr>

                <td colspan="2" >&nbsp;</td>

                <td ><label>IVA Retenido </label></td>

                <td ><input name="t_txtivaretenido" type="text"  id="t_txtivaretenido" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                </tr>

              <tr>

                <td colspan="2" >&nbsp;</td>

                <td >Total </td>

                <td ><input name="t_txttotal" type="text"  id="t_txttotal" style="width:50px;background:#FFFF99" value="$ 0.00"  readonly="" /></td>

                </tr>

              <tr>

                <td colspan="2" >&nbsp;</td>

                <td colspan="2" >&nbsp;</td>

                </tr>

              <tr>

                <td colspan="2" >&nbsp;</td>

                <td colspan="2" >&nbsp;</td>

                </tr>

              </table></td>

            </tr>

          </table></td>

      </tr>

      <tr>

        <td colspan="3">&nbsp;</td>

      </tr>

      

      <tr>

        <td colspan="3" align="center">

        	<table width="282">

            	<tr>

                	<td><div class="ebtn_exportar"></div></td>

                    <td><div class="ebtn_imprimir"></div></td>

                    <td><div class="ebtn_enviar" onClick="enviarMeil();"></div></td>

                    <td><div class="ebtn_nuevo" onClick="confirmar('Perdera la información capturada Desea continuar?', '', 'limpiar();', '')"></div></td>

                </tr>

            </table></td>

      </tr>

      <tr>

        <td colspan="3">&nbsp;</td>

      </tr>

    </table></td>

  </tr>

</table>

</form>

</body>

</html>

