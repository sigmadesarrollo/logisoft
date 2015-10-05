<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){

	 echo "<script language='javascript' type='text/javascript'>

						document.location.href='../../index.php';

					</script>";

	}else{*/

	require_once('../Conectar.php'); $link=Conectarse('webpmm');

$accion=$_POST['accion']; $modulo=$_GET['modulo']; $modulo="EvaluacionMercancia";

$usuario=$_SESSION[NOMBREUSUARIO];

$idusuario=$_SESSION[IDUSUARIO]; $fechahora=$_POST['fechahora']; $iddestino=$_POST['iddestino'];

$costoemplayeextra=$_POST['costoemplayeextra']; $totalpeso=$_POST['totalpeso'];

$msg=$_POST['msg']; $folio=$_POST['folio']; $Estado=$_POST['Estado']; 

$SucDestino=$_POST['SucDestino']; $BolsaEmpaque=$_POST['BolsaEmpaque'];

$CantidadEmpaque=$_POST['CantidadEmpaque']; $TotalEmpaque=$_POST['TotalEmpaque']; $totalvol=$_POST['totalvol']; $limite=$_POST['limite']; $costoextra=$_POST['costoextra']; $porcada=$_POST['porcada'];

$Emplaye=$_POST['Emplaye']; $TotalEmplaye=$_POST['TotalEmplaye'];

$NRecoleccion=$_POST['NRecoleccion']; $NGuias=$_POST['NGuias'];

$fechaevaluacion=$_POST['fechaevaluacion']; $fechaevaluacion= date("d/m/Y");

$user=$_POST['user']; $costoemplaye=$_POST['costoemplaye']; $sucursalorigen=$_POST['sucursalorigen'];

$costobolsa=$_POST['costobolsa']; $fecha = date("d/m/Y h:i"); $f=cambiaf_a_mysql($fecha); 

$countryid=$_POST['countryid']; $country=$_POST['country']; $hora=date("H:i:s");

if($fechahora==""){ $fechahora=$f.' '.$hora; } 



	if($accion==""){

		$row=ObtenerFolio('evaluacionmercancia','webpmm');

		$folio=$row[0];

	}else if($accion=="grabar"){

		$Estado="Guardado"; 		

		$iddestino=trim($iddestino);

		$sqlins=mysql_query("INSERT INTO evaluacionmercancia 

	(folio, fechaevaluacion, estado, guiaempresarial, recoleccion, destino, sucursaldestino, bolsaempaque, cantidadbolsa, totalbolsaempaque, emplaye, totalemplaye, sucursal, usuario, fecha)

VALUES('null', '$fechaevaluacion', UCASE('$Estado'), '$NGuias', '$NRecoleccion', '$iddestino', UCASE('$SucDestino'), '$BolsaEmpaque', '$CantidadEmpaque', '$TotalEmpaque', '$Emplaye', '$TotalEmplaye', '$sucursalorigen', '$usuario', current_timestamp())",$link);

		$folio=mysql_insert_id();

		$Estado="GUARDADO";

		$imprimir="si";

	$direc=mysql_query("INSERT INTO evaluacionmercanciadetalle

SELECT 0 as id, '$folio' As evaluacion, cantidad, descripcion, contenido, peso, largo, ancho, alto, volumen, pesototal, pesounit, usuario, fecha FROM evaluacionmercanciadetalletmp WHERE usuario='$usuario' And fecha='$fechahora'",$link);



$del=mysql_query("DELETE FROM evaluacionmercanciadetalletmp WHERE usuario='$usuario' And fecha='$fechahora'",$link);			

			$mensaje ='Los datos han sido guardados correctamente.';			

	}else if($accion=="limpiar"){

		$Estado=''; $SucDestino=''; $BolsaEmpaque=''; $CantidadEmpaque=''; $TotalEmpaque=''; $Emplaye=''; $TotalEmplaye=''; $NRecoleccion=''; $NGuias=''; $fechaevaluacion= date("d/m/Y"); $costoemplayeextra=''; $totalpeso=''; $msg=''; $user=''; $fechahora=$f.' '.$hora; $costoemplaye=''; $costobolsa=''; $countryid="";	$iddestino=""; $totalvol=""; $limite=""; $costoextra=""; $porcada=""; $sucursalorigen="";

		$row=ObtenerFolio('evaluacionmercancia','webpmm');

		$folio=$row[0];

	}else if($accion=="cancelar"){

		$sqlupd=@mysql_query("UPDATE evaluacionmercancia SET estado='CANCELADO' WHERE folio='$folio'",$link);

		$Estado="CANCELADO";

	}	

	

?>

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="../javascript/funciones_tablas.js"></script>

<script language="javascript">

	var u = document.all;

	var sucursalorigen 	= 0;

	var img = '<img src="../img/Boton_Cancelar.gif" alt="Guardar" width="70" height="20" onClick="confirmar(\'¿Realmente desea cancelar la Orden de Embarque?\', \'\', \'Cancelar();\', \'\')" style="cursor:pointer" id="cancelar" />';

	var tabla_valt1 	= "";

	var valt1 	= agregar_una_tabla("registro", "td_", 20, "Balance2+Balance","");

var nav4 = window.Event ? true : false;

function Numeros(evt){ 

// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46 

var key = nav4 ? evt.which : evt.keyCode; 

return (key <= 13 || (key >= 48 && key <= 57) || key==46);



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

function Cancelar(){

abrirVentanaFija('clavesecundaria.php?idusuario=<?=$idusuario ?>&modulo=<?=$modulo?>&usuario=<?=$usuario?>&cancelar=cancelar', 370, 340, 'ventana', 'Inicio de Sesión Secundaria');	

}

function ConfirmarCancelar(can){

	if(can!=""){

	document.getElementById('accion').value="cancelar";

	document.all.Estado.value="CANCELADO";

	document.form1.submit();

	}

}

function Obtener(folio){

	document.getElementById('folio').value=folio;

	consulta("mostrarEvaluacion","consultas.php?accion=1&evaluacion="+folio+"&sd="+Math.random());

}

function mostrarEvaluacion(datos){

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;

		//limpiartodo();

		

		if(con>0){

u.fechaevaluacion.value=datos.getElementsByTagName('fechaevaluacion').item(0).firstChild.data;

u.Estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;

u.NGuias.value=datos.getElementsByTagName('guiaempresarial').item(0).firstChild.data;

u.NRecoleccion.value=datos.getElementsByTagName('recoleccion').item(0).firstChild.data;

u.countryid.value=datos.getElementsByTagName('destino').item(0).firstChild.data;

u.country.value=datos.getElementsByTagName('descripciondestino').item(0).firstChild.data;

u.SucDestino.value=datos.getElementsByTagName('sucursaldestino').item(0).firstChild.data;



	if(datos.getElementsByTagName('bolsaempaque').item(0).firstChild.data==1){

		u.BolsaEmpaque.checked=true;

	}else{

		u.BolsaEmpaque.checked=false;

	}

	if(datos.getElementsByTagName('emplaye').item(0).firstChild.data==1){

		u.Emplaye.checked=true;

	}else{

		u.Emplaye.checked=false;

	}

u.CantidadEmpaque.value=datos.getElementsByTagName('cantidadbolsa').item(0).firstChild.data;

u.TotalEmpaque.value=datos.getElementsByTagName('totalbolsaempaque').item(0).firstChild.data;

u.TotalEmplaye.value=datos.getElementsByTagName('totalemplaye').item(0).firstChild.data;

u.cel.innerHTML = img;

	ObtenerDetalle(u.folio.value);		

	}

}

function Validar(){	

	if(document.getElementById('msg1').value==""){

alerta('Debe Capturar por lo menos una Evaluacin al detalle','Atencin!','folio');

	}else{

abrirVentanaFija('clavesecundaria.php?idusuario=<?=$idusuario ?>&modulo=<?=$modulo?>&usuario='+document.all.user.value, 370, 340, 'ventana', 'Inicio de Sesión Secundaria');		

	}	

}

function registrar(autorizar){

	if(autorizar=="SI"){

		ObtenerSucursalOrigen();

		document.getElementById('accion').value = "grabar";

		document.form1.submit();

	}

}

function limpiar(){	

document.getElementById('folio').value="";

document.getElementById('Estado').value="";

document.getElementById('country').value="";

document.getElementById('SucDestino').value="";

document.getElementById('CantidadEmpaque').value="";

document.getElementById('TotalEmpaque').value="";

document.getElementById('TotalEmplaye').value="";

document.getElementById('NRecoleccion').value="";

document.getElementById('NGuias').value="";

document.getElementById('fechaevaluacion').value="";

document.getElementById('costoemplayeextra').value="";

document.getElementById('totalpeso').value="";

document.getElementById('msg1').value="";

document.getElementById('user').value="";

document.getElementById('fechahora').value="";

document.getElementById('costoemplaye').value="";

document.getElementById('costobolsa').value=""; 

document.all.countryid.value=""; 

document.all.iddestino.value="";

document.form1.BolsaEmpaque.checked=false;

document.form1.Emplaye.checked=false;

document.getElementById('accion').value = "limpiar";

document.form1.submit();

}

function limpiartodo(){	

document.getElementById('folio').value="";

document.getElementById('Estado').value="";

document.getElementById('country').value="";

document.getElementById('SucDestino').value="";

document.getElementById('CantidadEmpaque').value="";

document.getElementById('TotalEmpaque').value="";

document.getElementById('TotalEmplaye').value="";

document.getElementById('NRecoleccion').value="";

document.getElementById('NGuias').value="";

document.getElementById('fechaevaluacion').value="";

document.getElementById('costoemplayeextra').value="";

document.getElementById('totalpeso').value="";

document.getElementById('msg1').value="";

document.getElementById('user').value="";

document.getElementById('fechahora').value="";

document.getElementById('costoemplaye').value="";

document.getElementById('costobolsa').value=""; 

document.all.countryid.value=""; 

document.all.iddestino.value="";

document.form1.BolsaEmpaque.checked=false;

document.form1.Emplaye.checked=false;

u.sucursaldestino.value = "";

}



function esperaDestino(){

	setTimeout("DestinoId()",500);

	document.all.iddestino.value=document.getElementById('countryid').value;

}

function DestinoId(){

consulta("mostrarSucursal","evaluacionmercanciaresult.php?accion=4&destino="+document.getElementById('countryid').value);

document.all.iddestino.value=document.getElementById('countryid').value;

}

function mostrarSucursal(datos){

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;

		if(con>0){

	u.SucDestino.value=datos.getElementsByTagName('SucDestino').item(0).firstChild.data;	

	abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?usuario=<?=$usuario ?>&fechahora=<?=$fechahora ?>', 400, 350, 'ventana', 'Datos Evaluacin');

		}

}

function Destino(e,obj){

	tecla=(document.all) ? e.keyCode : e.which;

    if(tecla==13 && document.getElementById('country').value!=""){

	document.all.iddestino.value=document.getElementById('countryid').value;

		SucDestino('destino',document.getElementById('countryid').value);

abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?usuario=<?=$usuario ?>&fechahora=<?=$fechahora ?>', 400, 350, 'ventana', 'Datos Evaluacin');

	}

}

function ObtenerPrecioBolsa(){

	 if(document.form1.BolsaEmpaque.checked){	 	

consulta("ObtenerCostoBolsa","evaluacionmercanciaresult.php?accion=1&id="+1);

	 }else{

	 document.getElementById('costobolsa').value=""; document.getElementById('TotalEmpaque').value=""; document.all.CantidadEmpaque.value=""; }

	  }

function ObtenerCostoBolsa(datos){

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;		

		if(con>0){

	u.costobolsa.value=datos.getElementsByTagName('bolsa').item(0).firstChild.data;	

	u.CantidadEmpaque.focus();

		}else{

			alerta("El Servicio no esta configurado",'Atencin!','BolsaEmpaque');

		}		

	}	 

function CalcularEmplaye(){

	var u = document.all;

if(u.Emplaye.checked==true){	

	if(parseFloat(u.totalpeso.value) > parseFloat(u.totalvol.value)){

		if(parseFloat(u.totalpeso.value) <= parseFloat(u.limite.value)){

			u.TotalEmplaye.value=u.costoemplaye.value;

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

	u.totalvol.value="";

	u.totalpeso.value="";

	u.TotalEmplaye.value="";

	u.limite.value="";

	u.porcada.value="";

	u.costoextra.value="";

}



}

function ObtenerPrecioEmplaye(){ 

	if(document.form1.Emplaye.checked){

		if(document.getElementById('msg1').value!=""){

consulta("ObtenerCostoEmplaye","evaluacionmercanciaresult.php?accion=2&id="+2);

		}else{

alerta('Debe Capturar por lo menos una Evaluacin al detalle','Atencin!','Emplaye');

	document.form1.Emplaye.checked=false;		

		}

	}else{

	document.getElementById('costoemplaye').value="";

	document.getElementById('TotalEmplaye').value="";

	document.all.limite.value="";

	document.all.porcada.value="";

	document.all.costoextra.value="";

	}	

}

function ObtenerCostoEmplaye(datos){

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;		

		if(con>0){

u.costoemplaye.value=datos.getElementsByTagName('costo').item(0).firstChild.data;	

u.costoextra.value=datos.getElementsByTagName('costoextra').item(0).firstChild.data;

u.limite.value=datos.getElementsByTagName('limite').item(0).firstChild.data;

u.porcada.value=datos.getElementsByTagName('porcada').item(0).firstChild.data;

CalcularEmplaye();

		}else{

	alerta("El Servicio no esta configurado",'Atención!','Emplaye');

		}

}

function ObtenerTotalBolsaFoco(){

	if(document.all.CantidadEmpaque.value!=""){

document.getElementById('TotalEmpaque').value=parseFloat(document.getElementById('costobolsa').value) * parseFloat(document.all.CantidadEmpaque.value); 

	}

	if(document.getElementById('TotalEmpaque').value=='Nan'){

		document.getElementById('TotalEmpaque').value="";

	}

 }

function ObtenerTotalBolsa(e,caja){

tecla = (document.all) ? e.keyCode : e.which;

if(tecla==13){ document.getElementById('TotalEmpaque').value=document.getElementById('costobolsa').value * caja; }

	if(document.getElementById('TotalEmpaque').value=='Nan'){

		document.getElementById('TotalEmpaque').value="";

	}

 }



function Arreglo(miArray,usuario,fechahora,id,tipo){

	if(miArray!=""){ 

		if(tipo=="modificar"){

		document.all.msg1.value="SI";

		InsertarGridModificar(miArray,usuario,fechahora,tipo,id);

consulta("ObtenerPesoVolumen","evaluacionmercanciaresult.php?accion=3&usuario=<?=$usuario ?>&fechahora=<?=$fechahora ?>");

		}else{

		document.all.msg1.value="SI";		

	InsertarGrid('grid',miArray,usuario,fechahora);	

consulta("ObtenerPesoVolumen","evaluacionmercanciaresult.php?accion=3&usuario=<?=$usuario ?>&fechahora=<?=$fechahora ?>");			

		}

	} 

 }

 

function ObtenerPesoVolumen(datos){

	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

	var u = document.all;		

		

		if(con>0){

u.totalpeso.value=datos.getElementsByTagName('peso').item(0).firstChild.data;	

u.totalvol.value=datos.getElementsByTagName('volumen').item(0).firstChild.data;

		}

} 

function Borrar(id,usuario,fechahora){ if(id!=""){ BorrarGrid('borrar',id,usuario,fechahora); } }



function ObtenerEvaluacion(id,usuario){	

tipo='modificar';

abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php'+"?id="+id+"&usuario="+usuario+"&tipo="+tipo+"&fechahora="+document.getElementById('fechahora').value, 400, 350, 'ventana', 'Datos Evaluaci&oacute;n');

}

function habilitar(e,nombre){

tecla = (document.all) ? e.keyCode : e.which;

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

function tabular(e,obj) 

        {

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

	var u= document.all;

	if(u.NRecoleccion.value!=""){	

	document.getElementById('NGuias').style.backgroundColor='#FFFF99';	

	document.getElementById('NGuias').disabled=true;

	}

}

function HabilitarEmpresarial(){

	var u= document.all;

	if(document.getElementById('NGuias').value!=""){	

document.getElementById('NRecoleccion').style.backgroundColor='#FFFF99';

document.getElementById('NRecoleccion').disabled=true;

	}

}

function Imprimir(){

	window.open("imprimirEvaluacion.php?evaluacion=<?=$folio?>&usuario=<?=$usuario?>")

}

function obtenerDestino(id,destino,sucursal){

	document.all.country.value=destino;

	document.all.countryid.value=id;

consulta("mostrarSucursal","evaluacionmercanciaresult.php?accion=4&destino="+id);

document.all.iddestino.value=document.getElementById('countryid').value;

}

function ObtenerSucursalOrigen(){

	if('<?=$_SESSION[IDSUCURSAL]?>'!=""){

	sucursalorigen = '<?=$_SESSION[IDSUCURSAL]?>';

	u.sucursalorigen.value = sucursalorigen;

	}

}

function BuscarEvaluacion(){

ObtenerSucursalOrigen();

abrirVentanaFija('buscarEvaluacion.php?tipo=evaluacion&sucursal='+sucursalorigen, 550, 450, 'ventana', 'Busqueda')

}

</script>

<title>Evaluaci&oacute;n de Mercancias</title>

<script src="../javascript/ajax.js"></script>

<script src="jsgrid/ajax.js"></script>

<script src="select.js"></script>

<script type="text/javascript" src="js/ajax.js"></script> 

<script type="text/javascript" src="js/ajax-dynamic-list.js"></script>

<link href="FondoTabla.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">

<script src="../javascript/ajax.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../javascript/ajax.js"></script>

<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script>

	function solicitarDatosConve(valor){

		if(valor.length!=13){

			alerta3("El folio de guia empresarial esta compuesto por 12 caracteres","¡Atencion!");

			document.all.NGuias.value = "";

		}else{

			consultaTexto("resSolicitarDatosConve","EvaluacionDeMercancia_con.php?accion=1&folio="+valor);

		}

	}

	

	function resSolicitarDatosConve(datos){

		var objeto = eval(convertirValoresJson(datos));

		

		if(objeto.encontro=="0"){

			alerta("El folio de guia empresarial no existe", "¡Atencion!", "country");

			document.all.NGuias.value = "";

		}else if(objeto.encontro=="1" && objeto.prepagadas=="SI"){

			info("El folio de guia empresarial es prepagada", "¡Atencion!");

		}

	}

</script>

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

</head>

<body onLoad="document.form1.NRecoleccion.focus()">

<form id="form1" name="form1" method="post" >

  <table width="100%" border="0">

    <tr>

      <td><br></td>

    </tr>

    <tr>

      <td><label></label>

        <table width="620" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

        <tr>

          <td class="FondoTabla">Datos Generales </td>

        </tr>

        <tr>

          <td><div id="txtResult"><table width="612" height="247" border="0" align="center" cellpadding="0" cellspacing="0">





            <tr>

              <th width="612" height="64" scope="row"><table width="608" height="75" border="0" cellspacing="0" class="Tablas">

                <tr>

                  <td height="19" colspan="2" class="Tablas"><label>

                    Folio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                      <input name="folio" type="text" class="Tablas" id="folio" value="<?=$folio ?>" size="10" style="text-align:right; background:#FFFF99" readonly="" >

                      <img src="../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="BuscarEvaluacion()"></label></td>

                  <td class="Tablas">Fecha: </td>

                  <td colspan="2" class="Tablas"><input name="fechaevaluacion" type="text" class="Tablas" id="fechaevaluacion" style="background:#FFFF99" value="<?=$fechaevaluacion ?>" size="15" readonly=""  >                    <label></label></td>

                  <td colspan="2" class="Tablas">Estado:&nbsp;&nbsp;&nbsp;&nbsp;

                    <label>

                    <input name="Estado" type="text" class="Tablas" id="Estado" style="background:#FFFF99" value="<?=$Estado ?>" size="15" readonly="">

                    </label></td>

                  </tr>

                <tr>

                  <td width="78" height="19" class="Tablas"><label>No.Recoleccion:</label></td>

                  <td width="79" class="Tablas"><input name="NRecoleccion" type="text" class="Tablas" id="NRecoleccion" value="<?=$NRecoleccion ?>" size="10" onKeyDown="return tabular(event,this)" onKeyPress="return Numeros(event)" onKeyUp="return habilitar(event,this.name)"  ></td>

                  <td width="59" class="Tablas"><label>Guia Emp.:</label></td>

                  <td width="52" class="Tablas"><input name="NGuias" type="text" class="Tablas" id="NGuias" value="<?=$NGuias ?>"  onKeyDown="return tabular(event,this)" onKeyUp="return habilitar(event,this.name)" onBlur="solicitarDatosConve(this.value)" style="text-transform:uppercase; width:70px"> 

				  <? if($NRecoleccion!=""){echo"<script>HabilitarEmpresarial();</script>";} ?>

                    <? if($NRecoleccion!=""){echo"<script>HabilitarRecoleccion();</script>";} ?></td>

                  <td width="69" class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destino:</td>

                  <td colspan="2" class="Tablas"><input name="country" type="text" class="Tablas" id="country" style="font-size:9px; text-transform:uppercase" onBlur="esperaDestino(); trim(document.getElementById('country').value,'country');" onChange="esperaDestino()" onKeyPress="Destino(event,this)"  onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-countries.php')"  value="<?=$country ?>" size="35" maxlength="60">

                    <img src="../img/Buscar_24.gif" alt="Buscar Destino" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarDestinoEvaluacion.php', 550, 450, 'ventana', 'Busqueda')"></td>

                  </tr>

                <tr>

                  <td height="19" colspan="3" class="Tablas"><input type="hidden" id="country_hidden" name="countryid" value="<?=$countryid ?>">

                    <input name="iddestino" type="hidden" id="iddestino" value="<?=$iddestino ?>">

                    <input name="prepagada" type="hidden" id="prepagada" value="<?=$iddestino ?>">

                    </td>

                  <td colspan="2" class="Tablas">&nbsp;</td>

                  <td width="66" class="Tablas">Suc Destino:</td>

                  <td width="191" class="Tablas"><div id="txtDestino">

                    <input name="SucDestino" type="text" class="Tablas" id="SucDestino" style="background:#FFFF99" value="<?=$SucDestino ?>" size="28" readonly="">

                  </div></td>

                </tr>

              </table></th>

              </tr>

            <tr>

              <th scope="row"><table width="560" border="0" cellpadding="0">

                <tr>

                  <th scope="row"><div id="txtHint"><table width="560" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td width="5" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

        <td width="27"  background="../img/borde1_2.jpg" class="style5" align="center">&nbsp;</td>

        <td width="29"  background="../img/borde1_2.jpg" class="style5" align="center">CANT</td>

        <td width="111" background="../img/borde1_2.jpg" class="style5" align="center">DESCRIPCION</td>

        <td width="119" background="../img/borde1_2.jpg" class="style5" align="center">CONTENIDO</td>

        <td width="57" background="../img/borde1_2.jpg" class="style5" align="center">PESO KG </td>

        <td width="46" background="../img/borde1_2.jpg" class="style5" align="center">LARGO</td>

        <td width="45" background="../img/borde1_2.jpg" class="style5" align="center">ANCHO</td>

        <td width="28" background="../img/borde1_2.jpg" class="style5" align="center">ALTO</td>

        <td width="57" align="center" background="../img/borde1_2.jpg" class="style5 Estilo2">P. VOLU </td>

        <td width="29" background="../img/borde1_2.jpg" class="style5"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

        <td width="7"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

      </tr>

      <tr>

        <td colspan="12" align="right"><div id="detalle" name="detalle" style=" height:150px; overflow:auto" align="left">

          <? $line = 0; ?>

          <table width="547" border="0" id="registro" alagregar="" alborrar="" cellspacing="0" cellpadding="0">

		  <tr>

		  	<td></td>

			<td></td>

			<td></td>

			<td></td>

			<td></td>

			<td></td>

			<td></td>

			<td></td>

			<td></td>

			<td></td>

		  </tr>

            <?

		$sql=mysql_query("SELECT e.id, e.cantidad, cd.descripcion, e.contenido, e.pesototal, e.largo, e.ancho, e.alto, e.volumen

		 FROM evaluacionmercanciadetalle e INNER JOIN catalogodescripcion cd ON e.descripcion=cd.id WHERE e.evaluacion='$folio'",$link);

			if(mysql_num_rows($sql)>0){

			$contador=0;

			$linea=mysql_num_rows($sql);

			while($row=mysql_fetch_array($sql)){?>			

            <tr id="td_<?=$contador ?>" class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>" onDblClick="ObtenerEvaluacion('<?=$row[id] ?>','<?=$usuario ?>');" >

              <td height="16" width="17" ><input name="id" type="hidden" value="<?=$row[id] ?>" /></td>

              <td width="45" align="center" class="style31"  >&nbsp;</td>

              <td width="32" align="center" class="style31"  ><input name="cantidad" type="text" class="style2" id="cantidad" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" value="<?=htmlentities($row[cantidad])?>" size="8" /></td>

              <td width="95" align="center" class="style31"><input name="descripcion" type="text" class="style2" id="descripcion" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[descripcion]) ?>" readonly="" size="20" /></td>

              <td width="128" align="center" class="style31"><input name="contenido" type="text" class="style2" id="contenido" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[contenido]) ?>" readonly="" size="20" /></td>

              <td width="119" class="style31" align="center"><input name="peso" type="text" class="style2" id="peso" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[pesototal]) ?>" readonly="" size="8" /></td>

              <td width="43" class="style31" align="center"><input name="largo" type="text" readonly="" class="style2" id="largo" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[largo]) ?>" size="5" /></td>

              <td width="29" class="style31" align="center"><input name="ancho" type="text" readonly="" class="style2" id="ancho" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[ancho]) ?>" size="5" />

              </td>

              <td width="22" align="center" class="style31" ><input name="alto" type="text" class="style2" id="alto" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[alto]) ?>" size="5" /></td>

              <td width="40" align="center" class="style31"><input name="volumen" type="text" class="style2" id="volumen" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[volumen] ?>" size="8" /></td>

            </tr>

            <?

			$contador ++ ;

		$line ++ ; }	

			}else{ //$msg="";echo"<input name='msg' type='hidden' value='".$msg."'>";

			while($line<=20){?>

            <tr id="td_<?=$line ?>" class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >

              <td height="16" width="17" ><input name="id_<?=$line ?>" type="hidden" id="id_<?=$line ?>" value="<?=$row[id] ?>" /></td>

              <td width="45" align="center" class="style31"  >&nbsp;</td>

              <td width="32" align="center" class="style31"  ><input name="cantidad_<?=$line ?>" type="text" class="style2" id="cantidad_<?=$line ?>" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" value="<?=htmlentities($row[cantidad])?>" size="8" /></td>

              <td width="95" align="center" class="style31"><input name="descripcion_<?=$line ?>" type="text" class="style2" id="descripcion_<?=$line ?>" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[descripcion]) ?>" readonly="" size="20" /></td>

              <td width="128" align="center" class="style31"><input name="contenido_<?=$line ?>" type="text" class="style2" id="contenido_<?=$line ?>" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[contenido]) ?>" readonly="" size="20" /></td>

              <td width="119" class="style31" align="center"><input name="peso_<?=$line ?>" type="text" class="style2" id="peso_<?=$line ?>" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[pesototal]) ?>" readonly="" size="8" /></td>

              <td width="43" class="style31" align="center"><input name="largo_<?=$line ?>" type="text" readonly="" class="style2" id="largo_<?=$line ?>" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[largo]) ?>" size="5" /></td>

              <td width="29" class="style31" align="center"><input name="ancho_<?=$line ?>" type="text" readonly="" class="style2" id="ancho_<?=$line ?>" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[ancho]) ?>" size="5" />

              </td>

              <td width="22" align="center" class="style31" ><input name="alto_<?=$line ?>" type="text" class="style2" id="alto_<?=$line ?>" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=htmlentities($row[alto]) ?>" size="5" /></td>

              <td width="40" align="center" class="style31"><input name="volumen_<?=$line ?>" type="text" class="style2" id="volumen_<?=$line ?>" readonly="" style="font-size:8px; font:tahoma;font-weight:bold" value="<?=$row[volumen] ?>" size="8" /></td>

            </tr>

            <?

		$line ++ ; }	

			}

			

	?>

          </table>

        </div></td>

      </tr>

    </table></div>

                  </th>

                </tr>

                <tr>

                  <th scope="row"><label></label>

                  <table width="20" border="0" align="right" cellpadding="0" cellspacing="0">

                      <tr>

                        <td><img src="../img/Boton_Agregari.gif" width="70" height="20" style="cursor:pointer" onClick="abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?usuario=<?=$usuario ?>&fechahora=<?=$fechahora ?>', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n')" /></td>

                      </tr>

                  </table></th>

                </tr>

              </table></th>

            </tr>

            <tr>

              <th height="87" scope="row"><table width="600" height="73" border="0" cellpadding="0" cellspacing="0" class="Tablas">

                <tr>

                  <td height="12" colspan="4" class="FondoTabla" scope="row">Servicios </td>

                  </tr>

                <tr>

                  <td height="20" colspan="3" class="Tablas" scope="row"><label></label>

                    <input name="BolsaEmpaque" type="checkbox" class="Txt" id="BolsaEmpaque" value="1" onClick="ObtenerPrecioBolsa()" <? if($BolsaEmpaque==1){echo 'checked';} ?>>

                    <span class="Estilo3">Bolsa de Empaque</span>

                    <input name="CantidadEmpaque" type="text"  class="Tablas" id="CantidadEmpaque" onKeyPress="ObtenerTotalBolsa(event,this.value)" onBlur="ObtenerTotalBolsaFoco()" value="<?=$CantidadEmpaque ?>" size="5" maxlength="5" >

                    <input name="TotalEmpaque" type="text" class="Tablas" style="background:#FFFF99" id="TotalEmpaque" value="<?=$TotalEmpaque ?>" size="10" readonly="readonly"  ></td>

                  <td width="333"><? if($imprimir=="si"){?>

<table width="50" border="0" align="right" cellpadding="0" cellspacing="0">

                    <tr>

                      <td id="cel"><img src="../img/Boton_Imprimir.gif" alt="Imprimir" width="70" height="20" onClick="Imprimir();" style="cursor:pointer"  /></td>

					  <td><img src="../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" onClick="confirmar('Perdera la información capturada Desea continuar?', '', 'limpiar();', '')" style="cursor:pointer"  /></td>

                    </tr>

                  </table>				  

				<?  }else{?>

<table width="50" border="0" align="right" cellpadding="0" cellspacing="0">

                    <tr>

                      <td id="cel"><img src="../img/Boton_Guardar.gif" alt="Guardar" width="70" height="20" onClick="Validar();" style="cursor:pointer"  /></td>

					  <td><img src="../img/Boton_Nuevo.gif" style="cursor:pointer" alt="Nuevo" width="70" height="20" onClick="confirmar('Perdera la informacin capturada Desea continuar?', '', 'limpiar();', '')"  /></td>

                    </tr>

                  </table>				  

				  <? } ?></td>

                </tr>

                <tr>

                  <td height="20" colspan="3" class="Tablas" scope="row">

<input name="Emplaye" type="checkbox" class="Txt" id="Emplaye" value="1" onClick="ObtenerPrecioEmplaye();" <? if($Emplaye==1){echo 'checked';} ?> >                    

Emplaye &nbsp;&nbsp;&nbsp;&nbsp;

                    <input name="TotalEmplaye" type="text" class="Tablas" id="TotalEmplaye" style="background:#FFFF99" value="<?=$TotalEmplaye ?>" size="10" readonly="readonly">                  </td>

                  <td>&nbsp;</td>

                </tr>

                <tr>

                  <td width="36" height="12" scope="row"><div id="txtBolsa"><input name="costobolsa" type="hidden" id="costobolsa" value="<?=$costobolsa ?>"></div>

                    <div id="txtEmplaye"><input name="costoemplaye" type="hidden" id="costoemplaye" value="<?=$costoemplaye ?>">

                      <input name="costoemplayeextra" type="hidden" id="costoemplayeextra" value="<?=$costoemplayeextra ?>">

                      <input name="totalpeso" type="hidden" id="totalpeso" value="<?=$totalpeso ?>">

                      <input name="totalvol" type="hidden" id="totalvol" value="<?=$totalvol ?>">

                      <input name="limite" type="hidden" id="limite" value="<?=$limite ?>">

                      <input name="costoextra" type="hidden" id="costoextra" value="<?=$costoextra ?>">

                      <input name="porcada" type="hidden" id="porcada" value="<?=$porcada ?>">

                    </div></td>

                  <td width="254" scope="row"><input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora; ?>">

                    <input name="user" type="hidden" id="user" value="<?=$usuario ?>">

                    <input name="msg1" type="hidden" id="msg1" value="<?=$msg ?>">                    <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>"></td>

                  <td width="43" scope="row"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>

                  <td></td>

                </tr>

              </table></th>

            </tr>

          </table></div></td>

        </tr>

      </table>

      </td>

    </tr>

  </table> 

</form>

</body>

</html>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'EVALUACION MERCANCIAS';

</script>

<? 

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//}

?>

