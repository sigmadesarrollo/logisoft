<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

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

$countryid=$_POST['countryid']; $country=$_POST['country']; $hora=date("H:i:s"); $registros=$_POST['registros'];

if($fechahora==""){ $fechahora=$f.' '.$hora; } 



	if($accion==""){

		$row=ObtenerFolio('evaluacionmercancia','webpmm');

		$folio=$row[0];

	}else if($accion=="grabar"){

	

		$Estado="Guardado"; 		

		$iddestino=trim($iddestino);

	  if($registros>0){

			$sqlins=mysql_query("INSERT INTO evaluacionmercancia 

		(folio, fechaevaluacion, estado, guiaempresarial, recoleccion, destino, sucursaldestino, bolsaempaque, cantidadbolsa, totalbolsaempaque, emplaye, totalemplaye, sucursal, usuario, fecha)

	VALUES(null, '$fechaevaluacion', UCASE('$Estado'), '$NGuias', '$NRecoleccion', '$iddestino', UCASE('$SucDestino'), '$BolsaEmpaque', '$CantidadEmpaque', '$TotalEmpaque', '$Emplaye', '$TotalEmplaye', '$sucursalorigen', '$usuario', current_timestamp())",$link) or die("Error en la linea ".__LINE__.mysql_error($link));

			$folio=mysql_insert_id();

			$Estado="GUARDADO";

			$imprimir="si";



			$del=mysql_query("DELETE FROM evaluacionmercanciadetalletmp WHERE evaluacion='$folio' ",$link);	

		//INSERTAR TABLA DETALLE

		for($i=0;$i<$registros;$i++){

			$sqlins=mysql_query("INSERT INTO evaluacionmercanciadetalle 

					(id,evaluacion,	cantidad,descripcion,contenido, peso,largo,ancho,alto,volumen,

					pesototal,pesounit,usuario,fecha )VALUES (NULL, '$folio', 

					'".$_POST["tablaguias_CANT"][$i]."', 

					'".$_POST["tablaguias_ID"][$i]."',

					'".$_POST["tablaguias_CONTENIDO"][$i]."', 

					'".$_POST["tablaguias_PESO_KG"][$i]."', 

					'".$_POST["tablaguias_LARGO"][$i]."', 

					'".$_POST["tablaguias_ANCHO"][$i]."', 

					'".$_POST["tablaguias_ALTO"][$i]."', 

					'".$_POST["tablaguias_P_VOLU"][$i]."', 

					'".$_POST["tablaguias_P_TOTAL"][$i]."', 

					'".$_POST["tablaguias_UNIT"][$i]."', 

					'$usuario',CURRENT_TIMESTAMP())",$link)or die("error en linea ".__LINE__);



			$detalle .= "{

				cantidad:'".$_POST["tablaguias_CANT"][$i]."',

				id:'".trim($_POST["tablaguias_ID"][$i])."',

				descripcion:'".$_POST["tablaguias_DESCRIPCION"][$i]."',

				contenido:'".$_POST["tablaguias_CONTENIDO"][$i]."',

				peso:'".$_POST["tablaguias_PESO_KG"][$i]."',

				largo:'".$_POST["tablaguias_LARGO"][$i]."',

				ancho:'".$_POST["tablaguias_ANCHO"][$i]."',

				alto:'".$_POST["tablaguias_ALTO"][$i]."',

				pesototal:'".$_POST["tablaguias_P_TOTAL"][$i]."',

				volumen:'".$_POST["tablaguias_P_VOLU"][$i]."',

				pesounit:'".$_POST["tablaguias_UNIT"][$i]."'},";

		}$detalle = substr($detalle,0,strlen($detalle)-1);

		//*******/

	

				$mensaje ='Los datos han sido guardados correctamente.';	

	  }		

	}else if($accion=="limpiar"){

		$Estado=''; $SucDestino=''; $BolsaEmpaque=''; $CantidadEmpaque=''; $TotalEmpaque=''; $Emplaye=''; $TotalEmplaye=''; $NRecoleccion=''; $NGuias=''; $fechaevaluacion= date("d/m/Y"); $costoemplayeextra=''; $totalpeso=''; $msg=''; $user=''; $fechahora=$f.' '.$hora; $costoemplaye=''; $costobolsa=''; $countryid="";	$iddestino=""; $totalvol=""; $limite=""; $costoextra=""; $porcada=""; $sucursalorigen="";$accion="";

		$row=ObtenerFolio('evaluacionmercancia','webpmm');

		$folio=$row[0];

	}else if($accion=="cancelar"){

		$sqlupd=@mysql_query("UPDATE evaluacionmercancia SET estado='CANCELADO' WHERE folio='$folio'",$link);

		$Estado="CANCELADO";

		for($i=0;$i<$registros;$i++){

		$detalle .= "{

				cantidad:'".$_POST["tablaguias_CANT"][$i]."',

				id:'".$_POST["tablaguias_ID"][$i]."',

				descripcion:'".$_POST["tablaguias_DESCRIPCION"][$i]."',

				contenido:'".$_POST["tablaguias_CONTENIDO"][$i]."',

				peso:'".$_POST["tablaguias_PESO_KG"][$i]."',

				largo:'".$_POST["tablaguias_LARGO"][$i]."',

				ancho:'".$_POST["tablaguias_ANCHO"][$i]."',

				alto:'".$_POST["tablaguias_ALTO"][$i]."',

				pesototal:'".$_POST["tablaguias_P_TOTAL"][$i]."',

				volumen:'".$_POST["tablaguias_P_VOLU"][$i]."',

				pesounit:'".$_POST["tablaguias_UNIT"][$i]."'},";

		}		$detalle = substr($detalle,0,strlen($detalle)-1);

	}	

	

?>

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script language="javascript" src="../javascript/ClaseTabla.js" ></script>

<script language="javascript">

	var u = document.all;

	var tabla1 	= new ClaseTabla();

	var sucursalorigen 	= 0;

	var img = '<img id="btnCancelar" src="../img/Boton_Cancelar.gif" alt="Guardar" width="70" height="20" onClick="confirmar(\'¿Realmente desea cancelar la Orden de Embarque?\', \'\', \'Cancelar();\', \'\')" style="cursor:pointer;" id="cancelar" />';

	var tabla_valt1 	= "";

	//var valt1 	= agregar_una_tabla("registro", "td_", 20, "Balance2+Balance","");

var nav4 = window.Event ? true : false;

function Numeros(evt){ 

// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46 

var key = nav4 ? evt.which : evt.keyCode; 

return (key <= 13 || (key >= 48 && key <= 57) || key==46);



}



tabla1.setAttributes({

		nombre:"tablaguias",

		campos:[

			{nombre:"CANT", medida:45, alineacion:"right", datos:"cantidad"},

			{nombre:"ID", medida:2, alineacion:"right", tipo:"oculto", datos:"id"},

			{nombre:"DESCRIPCION", medida:150, alineacion:"left", datos:"descripcion"},

			{nombre:"CONTENIDO", medida:150, alineacion:"left", datos:"contenido"},

			{nombre:"PESO_KG", medida:45, alineacion:"right", datos:"peso"},

			{nombre:"LARGO", medida:40, alineacion:"right",  datos:"largo"},

			{nombre:"ANCHO", medida:40, alineacion:"right",  datos:"ancho"},

			{nombre:"ALTO", medida:40, alineacion:"right",  datos:"alto"},

			{nombre:"P_TOTAL", medida:4, alineacion:"right", tipo:"oculto", datos:"pesototal"},			

			{nombre:"P_VOLU", medida:40, alineacion:"right", datos:"volumen"},

			{nombre:"UNIT", medida:4, alineacion:"right", tipo:"oculto", datos:"pesounit"}			

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



function ModificarFila(){

		var obj = tabla1.getSelectedRow();		

		if(tabla1.getValSelFromField("cantidad","CANT")!=""){

		abrirVentanaFija('EvaluacionMercanciaAgregarFilas(original).php?&cantidad='+obj.cantidad

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

				+'&funcion=agregarDatos&eliminar=1', 460, 410, 'ventana', 'Datos Evaluación');	

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

	abrirVentanaFija('clavesecundaria.php?idusuario=<?=$idusuario ?>&modulo=<?=$modulo?>&usuario=<?=$usuario?>&cancelar=cancelar', 370, 340, 'ventana', 'Inicio de Sesión Secundaria');	

}



function ConfirmarCancelar(can){

	if(can!=""){

		u.registros.value = tabla1.getRecordCount();

		document.getElementById('accion').value="cancelar";

		document.all.Estado.value="CANCELADO";

		document.form1.submit();

	}

}



function Obtener(folio){

	document.getElementById('folio').value=folio;

	consulta("mostrarEvaluacion","consultas(original).php?accion=1&evaluacion="+folio+"&sd="+Math.random());

}



function mostrarEvaluacion(datos){

		var cont_detalle = datos.getElementsByTagName('total').item(0).firstChild.data;

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;

				

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

			if(cont_detalle>0){

				tabla1.setXML(datos);

			}

			

			u.cel.innerHTML = img;

			u.img_eliminar.style.visibility	="hidden";

			u.btnAgregar.style.visibility	="hidden";

			u.accion.value="cancelar";

			if(u.Estado.value=='CANCELADO'){

				u.btnCancelar.style.visibility='hidden';

			}else{

				u.btnCancelar.style.visibility='visible';

			}

	}

}



function Validar(){	

	u.registros.value = tabla1.getRecordCount();	

	

	if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){

			alerta3('Debe Capturar por lo menos una Evaluacin al detalle','¡Atención!');

			return false;			

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

	document.all.prepagada.value=0;

	tabla1.clear();

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

			abrirVentanaFija('EvaluacionMercanciaAgregarFilas(original).php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluacin');

		}

}

function Destino(e,obj){

	tecla=(document.all) ? e.keyCode : e.which;

    if(tecla==13 && document.getElementById('country').value!=""){

		document.all.iddestino.value=document.getElementById('countryid').value;

		SucDestino('destino',document.getElementById('countryid').value);

		abrirVentanaFija('EvaluacionMercanciaAgregarFilas(original).php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluacin');

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

	

	var pesototal = 0;

	var pesos = tabla1.getValuesFromField("peso","p");

	if(pesos.indexOf("p")>-1){

		pesos = pesos.split("p");

		for(var i=0; i<tabla1.getRecordCount(); i++){

			pesototal = pesos[i];

		}

	}else{

		pesototal = pesos;

	}

	

	var volumentotal = 0;

	var volumenes = tabla1.getValuesFromField("volumen","p");

	if(volumenes.indexOf("p")>-1){

		volumenes = volumenes.split("p");

		for(var i=0; i<tabla1.getRecordCount(); i++){

			volumentotal = volumenes[i];

		}

	}else{

		volumentotal = volumenes;

	}

	

	u.totalpeso.value 	= pesototal;

	u.totalvol.value 	= volumentotal;

	

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

		if(tabla1.getRecordCount()>0){

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



/*function Arreglo(miArray,usuario,fechahora,id,tipo){

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

 }*/





 

 

function agregarDatos(variable, eliminar){

	 	if(eliminar!=undefined){

			if(tabla1.getSelectedIdRow()==""){

				alerta3('Seleccione la fila a eliminar','¡Atención!');

				return false;

			}

			

			if(tabla1.getValSelFromField("cantidad","CANT")!=""){

				tabla1.deleteById(tabla1.getSelectedIdRow());

			}

			

				if(variable!=""){

					info("Los datos han sido modificados","");

				}else{

					info("La mercancia ha sido borrada","");	

				}

		}

		if(variable!=""){

			if(document.all.prepagada.value==1 && tabla1.getRecordCount()==1){

				document.all

			}else{

				tabla1.add(variable);

					/****/

					if(u.accion.value==""){

						u.img_eliminar.style.visibility="visible";

					}else{

						u.img_eliminar.style.visibility="hidden";

					}

					/****/

				info('Los datos han sido agregados satisfactoriamente');

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

	abrirVentanaFija('EvaluacionMercanciaAgregarFilas.php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n');

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







	function solicitarDatosConve(valor){

		if(valor.length!=13){

			alerta3("El folio de guia empresarial esta compuesto por 13 caracteres","¡Atencion!");

			document.all.NGuias.value = "";

		}else{

			consultaTexto("resSolicitarDatosConve","EvaluacionDeMercancia_con.php?accion=1&folio="+valor);

		}

	}

	

	function resSolicitarDatosConve(datos){

		var objeto = eval(convertirValoresJson(datos));

		

		document.all.prepagada.value = 0;

		

		if(objeto.encontro=="0"){

			alerta("El folio de guia empresarial no existe", "¡Atencion!", "country");

			document.all.NGuias.value = "";

		}else if(objeto.encontro=="1" && objeto.prepagadas=="SI"){

			//info("El folio de guia empresarial es prepagada", "¡Atencion!");

			document.all.prepagada.value = 1;

		}else if(objeto.encontro=="-2"){

			info("El folio de guia empresarial ya fue registrado", "¡Atencion!");

			document.all.NGuias.value = "";

		}

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

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />

<script src="../javascript/ajax.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript"  src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript"  src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

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

<body >

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

                      <input name="folio" type="text" class="Tablas" id="folio" value="<?=$folio ?>" size="10" style="text-align:right; background:#FFFF99"  >

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

                  <td width="52" class="Tablas"><input name="NGuias" type="text" class="Tablas" id="NGuias" value="<?=$NGuias ?>" size="12" onKeyDown="return tabular(event,this)" onKeyUp="return habilitar(event,this.name)" onBlur="solicitarDatosConve(this.value)"> <? if($NRecoleccion!=""){echo"<script>HabilitarEmpresarial();</script>";} ?>

                    <? if($NRecoleccion!=""){echo"<script>HabilitarRecoleccion();</script>";} ?>

                    <input type="hidden" name="prepagada">

                    </td>

                  <td width="69" class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destino:</td>

                  <td colspan="2" class="Tablas"><input name="country" type="text" class="Tablas" id="country" style="font-size:9px; text-transform:uppercase" onBlur="esperaDestino(); trim(document.getElementById('country').value,'country');" onChange="esperaDestino()" onKeyPress="Destino(event,this)"  onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'ajax-list-countries.php')"  value="<?=$country ?>" size="35" maxlength="60">

                    <img src="../img/Buscar_24.gif" alt="Buscar Destino" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscarDestinoEvaluacion.php', 550, 450, 'ventana', 'Busqueda')"></td>

                  </tr>

                <tr>

                  <td height="19" colspan="3" class="Tablas"><input type="hidden" id="country_hidden" name="countryid" value="<?=$countryid ?>">

                    <input name="iddestino" type="hidden" id="iddestino" value="<?=$iddestino ?>"></td>

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

                  <td><table id="tablaguias" width="560" border="0" cellpadding="0" cellspacing="0"></table></td>

                </tr>

                <tr>

                  <th scope="row"><label></label>

                    <table width="150" border="0" align="right">

                      <tr>

                        <td width="70">

						<? if($accion!="cancelar"){?>

						<img  id="img_eliminar" src="../img/Boton_Eliminar.gif" alt="Cerrar" width="70" height="20" style="cursor:pointer;<? if($accion=='grabar'){echo 'visibility:hidden';}else{echo 'visibility:visible';} ?>" onClick="agregarDatos('', 1)" />

						<? } ?>

						</td>

                        <td width="95">

						<? if($accion!="cancelar"){?>

						<img id="btnAgregar" src="../img/Boton_Agregari.gif" width="70" height="20" style="cursor:pointer; <? if($accion=='grabar'){echo 'visibility:hidden';}else{echo 'visibility:visible';} ?>" onClick="abrirVentanaFija('EvaluacionMercanciaAgregarFilas(original).php?funcion=agregarDatos', 400, 350, 'ventana', 'Datos Evaluaci&oacute;n')" />

						<? } ?>

						</td>

                      </tr>

                    </table>

                  </th>

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

                      <td id="cel">

					 	<? if($accion!="cancelar"){?>

						

					  <img src="../img/Boton_Guardar.gif" alt="Guardar" width="70" height="20" onClick="Validar();" style="cursor:pointer;"  />

					  <? } ?>

					  </td>

					  <td><img src="../img/Boton_Nuevo.gif" style="cursor:pointer" alt="Nuevo" width="70" height="20" onClick="confirmar('Perdera la informacin capturada Desea continuar?', '', 'limpiar();', '')"  /></td>

					 

                    </tr>

                  </table>

				   <? } ?>				  

				  </td>

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

                    <div id="txtEmplaye">

                      <input name="costoemplaye" type="hidden" id="costoemplaye" value="<?=$costoemplaye ?>">

                      <input name="costoemplayeextra" type="hidden" id="costoemplayeextra" value="<?=$costoemplayeextra ?>">

                      <input name="totalpeso" type="hidden" id="totalpeso" value="<?=$totalpeso ?>">

                      <input name="totalvol" type="hidden" id="totalvol" value="<?=$totalvol ?>">

                      <input name="limite" type="hidden" id="limite" value="<?=$limite ?>">

                      <input name="costoextra" type="hidden" id="costoextra" value="<?=$costoextra ?>">

                      <input name="porcada" type="hidden" id="porcada" value="<?=$porcada ?>">

                    </div></td>

                  <td width="254" scope="row"><input name="fechahora" type="hidden" id="fechahora" value="<?=$fechahora; ?>">

                    <input name="user" type="hidden" id="user" value="<?=$usuario ?>">

                    <input name="msg1" type="hidden" id="msg1" value="<?=$msg ?>">                   

					 <input name="sucursalorigen" type="hidden" id="sucursalorigen" value="<?=$sucursalorigen ?>">

                    <input name="registros" type="hidden" id="registros"></td>

                  <td width="43" scope="row"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>

                  <td><a href="../menu/webministator.php" ><img src="../img/inicio_30.gif" name="IMG0"  border="0"  id="IMG0" /></a></td>

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



?>

