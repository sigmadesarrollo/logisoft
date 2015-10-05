<? session_start();
	/*if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}*/
	include('../../Conectar.php');
	$link=Conectarse('webpmm');
	$usuario=$_SESSION[NOMBREUSUARIO];
	$accion=$_POST['accion']; $codigo=$_POST['codigo'];
	$rdmoral=$_POST['rdmoral']; $nombre=$_POST['nombre']; $paterno=$_POST['paterno']; $materno=$_POST['materno']; $rfc=$_POST['rfc']; $email=$_POST['email']; $celular=$_POST['celular']; $web=$_POST['web']; $calle=$_POST['calle']; $cp=$_POST['cp']; $entrecalles=$_POST['entrecalles']; $colonia=$_POST['colonia']; $numero=$_POST['numero']; $poblacion=$_POST['poblacion']; $municipio=$_POST['municipio']; $estado=$_POST['estado']; $pais=$_POST['pais']; $telefono=$_POST['telefono']; $fax=$_POST['fax']; $listnick=$_POST['listnick']; 
		
	if($accion==""){
		$lqs=mysql_query("SELECT IFNULL(MAX(id),0)+1 as id FROM catalogoprospecto",$link);
		$rest=mysql_fetch_array($lqs);
		$codigo=$rest[0];
	}
	if($accion=="grabar"){
		$sqlins="INSERT INTO catalogoprospecto 
		(personamoral, nombre, paterno, materno, rfc, email, celular, web, usuario, fecha)
		VALUES
		('$rdmoral', UCASE('$nombre'), UCASE('$paterno'), UCASE('$materno'), UCASE('$rfc'), 
		'$email', '$celular', '$web','$usuario', current_timestamp())";
		$res=mysql_query($sqlins,$link);
		$codigo=mysql_insert_id();
		
		$row = split("-",$calle);
		$dir=mysql_query("INSERT INTO 
		direccion(id,origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,
		estado,pais,telefono,fax,facturacion,usuario,fecha)VALUES(null,'pro','$codigo',UCASE('$row[0]'),
		'$numero',UCASE('$entrecalles'),'$cp',UCASE('$colonia'),UCASE('$poblacion'),UCASE('$municipio'),
		UCASE('$estado'),UCASE('$pais'),'$telefono','$fax','NO','$usuario',current_timestamp())",$link);			
		$mensaje ='Los datos han sido guardados correctamente.';
		$accion="modificar";

		}else if($accion=="modificar"){
			$sqlupd="UPDATE catalogoprospecto SET personamoral='$rdmoral', nombre=UCASE('$nombre'), 
			paterno=UCASE('$paterno'), materno=UCASE('$materno'), rfc=UCASE('$rfc'), email='$email', 
			celular='$celular', web='$web',usuario='$usuario', fecha=current_timestamp() where id='$codigo'";
			$res=mysql_query($sqlupd,$link);
			
			$row = split("-",$calle);
			
			$dir1=mysql_query("UPDATE direccion SET calle=UCASE('$row[0]'),numero='$numero',
			crucecalles=UCASE('$entrecalles'),cp='$cp', colonia=UCASE('$colonia'), poblacion=UCASE('$poblacion'),
			municipio=UCASE('$municipio'),estado=UCASE('$estado'),pais=UCASE('$pais'),
			telefono='$telefono',fax='$fax',facturacion='NO',usuario='$usuario',fecha=current_timestamp()
			WHERE codigo='$codigo' && origen='pro'",$link);
			
			$accion="modificar";
			$mensaje ='Los cambios han sido guardados correctamente.';
		}elseif ($accion=="limpiar"){
$rdmoral="SI"; $nombre=""; $paterno=""; $materno=""; $rfc=""; $email=""; $celular=""; $web="";
$calle=""; $cp=""; $entrecalles=""; $colonia=""; $numero=""; $poblacion=""; $municipio=""; $estado="";
$pais=""; $telefono=""; $fax=""; $usuario=$_SESSION[NOMBREUSUARIO]; $accion=""; $msg="";					 $listnick="";
		$sqlid=mysql_query("SELECT IFNULL(MAX(id),0)+1 as id FROM catalogoprospecto",$link);
		$resid=mysql_fetch_array($sqlid);
		$codigo=$resid[0];	
	}	
		
	if($accion=="grabar"||$accion=="modificar"){
		$del=mysql_query("DELETE FROM catalogoprospectonick WHERE prospecto='$codigo'",$link);
		$enter=chr(13);
		$lista=split($enter,trim($listnick));
		if (count($lista)>0){
			for ($i=0;$i<count($lista);$i++){	
				$var = trim($lista[$i]);
				if ($var!=""){
					$reg=mysql_num_rows(mysql_query("select * from catalogoprospectonick where prospecto='$codigo' and nick='$var'",$link));
					if ($reg==0){
						$sqlins=mysql_query("INSERT INTO catalogoprospectonick (id,prospecto,nick,usuario,fecha) VALUES('null','$codigo','$var','$usuario',current_timestamp()) ;",$link);
					}
				}
			}
	}
}
	
	
		
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<script src="../../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script src="../../javascript/ajaxlist/ajax.js"></script>
<script src="../../javascript/funciones.js"></script>
<link href="puntovta.css" rel="stylesheet" type="text/css">
<script src="../../javascript/ajax.js"></script>
<script src="SelectProspecto.js"></script>
<script src="../../javascript/shortcut.js"></script> 
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


.Button {
margin: 0;
padding: 0;
border: 0;
background-color: transparent;
width:70px;
height:20px;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Catalogo Prospecto</title>

<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
@import url("../../css/FondoTabla.css");
@import url("../../css/Tablas.css");
-->
</style>



<script language="javascript" type="text/javascript">
var u = document.all;
var busco = false;
var var_input ='<input name="colonia" type="text" class="Tablas" id="colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,\'getCountriesByLetters\',event,\'../../buscadores_generales/ajax-list-colonias.php\'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=\'\'){setTimeout(\'obtenerColoniaValida()\',1000);}" />';
var combo1 = "<select name='colonia' class='Tablas' id='colonia'  style='width:180px;font:tahoma;font-size:9px' onKeyPress='return tabular(event,this)'>";

	window.onload = function(){		
		if(u.accion.value==""){
			obtenerGeneral();
		}
			
		u.nick.focus();
	}
	
	function obtenerGeneral(){
		consultaTexto("mostrarGeneral","consultaCredito_con.php?accion=2");
	}
	
	function mostrarGeneral(datos){
		u.codigo.value = datos;
	}
	
	function habilitar(){
		if(document.all.rdmoral[1].checked== true){
			document.getElementById('paterno').disabled=false
			document.getElementById('materno').disabled=false
			document.getElementById('paterno').style.backgroundColor="";
			document.getElementById('materno').style.backgroundColor="";
			
		}else if(document.all.rdmoral[0].checked== true){
			document.getElementById('paterno').disabled=true
			document.getElementById('paterno').value="";
			document.getElementById('materno').disabled=true
			document.getElementById('materno').value="";
			document.getElementById('paterno').style.backgroundColor='#FFFF99';
			document.getElementById('materno').style.backgroundColor='#FFFF99';
		}
	}

function validar(){
	<?=$cpermiso->verificarPermiso(278,$_SESSION[IDUSUARIO]);?>;
	if (document.form1.listnick.value.length == ""){
			alerta('Debe capturar por lo menos un Nick', '¡Atención!','nick');
	}else if (document.getElementById('nombre').value==""){
			alerta('Debe capturar Nombre', '¡Atención!','nombre');
	}else if(document.form1.rdmoral[1].checked){		
		if(document.getElementById('paterno').value==""){
			alerta('Debe capturar Apellido Paterno', '¡Atención!','paterno');
		}else if(document.getElementById('materno').value==""){
			alerta('Debe capturar Apellido Materno', '¡Atención!','materno');
		}else if(document.getElementById('rfc').value==""){
				alerta('Debe capturar R.F.C', '¡Atención!','rfc');
		}else if(u.rfc_h.value == u.rfc.value){
			alerta3('El R.F.C.:'+u.rfc.value.toUpperCase()+' esta asignado al prospecto '+obj.cliente.toUpperCase(), '¡Atención!');
			return false;
		}else if(!ValidaRfc(document.getElementById('rfc').value)){
				alerta('Debe capturar un R.F.C valido', '¡Atención!','rfc');
		}else if(document.getElementById('email').value!="" && !isEmailAddress(document.form1.email) ){
					alerta('Debe capturar Email valido', '¡Atención!','email');
		}else if(document.getElementById('calle').value==""){
				alerta('Debe capturar Calle', '¡Atención!','calle');
		}else if(document.getElementById('numero').value==""){
					alerta('Debe capturar Numero', '¡Atención!','numero');
		}else if(document.getElementById('colonia').value==""){
					alerta('Debe capturar colonia', '¡Atención!','colonia');
		}else if(document.getElementById('cp').value==""){
					alerta('Debe capturar Codigo Postal', '¡Atención!','cp');
		}else if(document.getElementById('poblacion').value==""){
					
					alerta('Debe capturar Población', '¡Atención!','poblacion');
		}else if(document.getElementById('municipio').value==""){
					
					alerta('Debe capturar Municipio', '¡Atención!','municipio');
		}else if(document.getElementById('estado').value==""){
					
					alerta('Debe capturar Estado', '¡Atención!','estado');
		}else if(document.getElementById('pais').value==""){
					
					alerta('Debe capturar Pais', '¡Atención!','pais');
		}else if(document.getElementById('telefono').value==""){
					
					alerta('Debe capturar Teléfono', '¡Atención!','telefono');
		}else{
				if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
				}else if(document.getElementById('accion').value=="modificar"){
				document.form1.submit();
				}
		}
	}else if(document.form1.rdmoral[0].checked){
		if(document.getElementById('rfc').value==""){
				document.getElementById('rfc').focus();
				alerta('Debe capturar R.F.C', '¡Atención!','rfc');
		}else if(u.rfc_h.value == u.rfc.value){
			alerta3('El R.F.C.:'+u.rfc.value.toUpperCase()+' esta asignado al prospecto '+u.cliente_h.value.toUpperCase(), '¡Atención!');
			return false;
		}else if(!ValidaRfc(document.getElementById('rfc').value)){
				document.getElementById('rfc').focus();
				alerta('Debe capturar un R.F.C valido', '¡Atención!','rfc');
		}else if(document.getElementById('email').value!="" && !isEmailAddress(document.form1.email) ){
					document.getElementById('email').focus();
					alerta('Debe capturar Email valido', '¡Atención!','email');
		}else if(document.getElementById('calle').value==""){
				document.getElementById('calle').focus();
				alerta('Debe capturar Calle', '¡Atención!','calle');
		}else if(document.getElementById('numero').value==""){
					document.getElementById('numero').focus();
					alerta('Debe capturar Numero', '¡Atención!','numero');
		}else if(document.getElementById('cp').value==""){
					document.getElementById('cp').focus();
					alerta('Debe capturar Codigo Postal', '¡Atención!','cp');
		}else if(document.getElementById('colonia').value==""){
					document.getElementById('colonia').focus();
					alerta('Debe capturar Colonia', '¡Atención!','colonia');
		}else if(document.getElementById('poblacion').value==""){
					document.getElementById('poblacion').focus();
					alerta('Debe capturar Población', '¡Atención!','poblacion');
		}else if(document.getElementById('municipio').value==""){
					document.getElementById('municipio').focus();
					alerta('Debe capturar Municipio', '¡Atención!','municipio');
		}else if(document.getElementById('estado').value==""){
					document.getElementById('estado').focus();
					alerta('Debe capturar Estado', '¡Atención!','estado');
		}else if(document.getElementById('pais').value==""){
					document.getElementById('pais').focus();
					alerta('Debe capturar Pais', '¡Atención!','pais');
		}else if(document.getElementById('telefono').value==""){
					document.getElementById('telefono').focus();
					alerta('Debe capturar Teléfono', '¡Atención!','telefono');
		}else{

				if(document.getElementById('accion').value==""){
				document.getElementById('accion').value = "grabar";
				document.form1.submit();
				}else if(document.getElementById('accion').value=="modificar"){
				document.form1.submit();
				}
			}
	}
	
}

function agregarnick(param){
if(document.getElementById(param).value!=""){
	 var par=new RegExp(document.getElementById(param).value.toUpperCase()+'[\r\n]+');
     var txt=document.getElementById('listnick').value.split(par); 
	 if(!par.test(document.getElementById('listnick').value)){ 
		document.getElementById('listnick').value = document.getElementById('listnick').value + 	document.getElementById(param).value.toUpperCase() + "\n";
		document.getElementById(param).value ="";
		document.getElementById(param).focus();
	 }else{
		alerta('El Nick ' + document.getElementById(param).value + ' ya existe','¡Atención!','nick');	
		return;
	 }
	}	
}

	function obtenerRFC(rfc){
		if(busco==false){
			busco = true;
			if(u.accion.value!="modificar"){
				consultaTexto("mostrarRfc","consultaCredito_con.php?accion=4&rfc="+rfc);
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
confirmar('El R.F.C.:'+u.rfc.value.toUpperCase()+' esta asignado al prospecto '+obj.cliente.toUpperCase()+' ¿Desea ver su información?', '', 'obtener('+obj.id+')', 'cancelo()');
		}else{
			busco = false;
		}
	}
	function cancelo(){
		busco = false;
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
	document.form1.listnick.value== 0;
	document.getElementById('nombre').value="";
	document.form1.rdmoral[0].checked = true;
	document.getElementById('paterno').value="";
	document.getElementById('materno').value="";
	document.getElementById('rfc').value="";
	document.getElementById('calle').value="";
	document.getElementById('numero').value="";
	document.getElementById('colonia').value="";
	document.getElementById('cp').value="";
	document.getElementById('poblacion').value="";
	document.getElementById('municipio').value="";
	document.getElementById('estado').value="";
	document.getElementById('pais').value="";
	document.getElementById('telefono').value="";
	document.getElementById('accion').value = "";	
	document.getElementById('celcolonia').innerHTML = var_input;
	u.entrecalles.value = "";
	u.listnick.value = "";
	u.celular.value = "";
	u.web.value = "";
	u.fax.value = "";
	u.nick.value = "";
	u.rfc_h.value = "";
	u.cliente_h.value = "";
	u.idcliente_h.value = "";
	u.email.value = "";
	u.nick.focus();
	obtenerGeneral();
	//document.form1.submit();
}
	function CodigoPostal(e,cp){
		if(e!=13){
			tecla = (document.all) ? e.keyCode : e.which;
			if(tecla==13 || tecla==9 && cp!=""){
				consulta("mostrarPostal","consultasProspecto.php?accion=1&cp="+cp+"&sid="+Math.random());			
			}
		}else{
			consulta("mostrarPostal","consultasProspecto.php?accion=1&cp="+cp+"&sid="+Math.random());
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
			
		u.cp.value=datos.getElementsByTagName('cp').item(0).firstChild.data;
		document.all.celcolonia.innerHTML = var_input;
		u.colonia.value=datos.getElementsByTagName('colonia').item(0).firstChild.data;
		u.poblacion.value=datos.getElementsByTagName('poblacion').item(0).firstChild.data;
		u.municipio.value=datos.getElementsByTagName('municipio').item(0).firstChild.data;
		u.estado.value=datos.getElementsByTagName('estado').item(0).firstChild.data;
		u.pais.value=datos.getElementsByTagName('pais').item(0).firstChild.data;
		}
		}else{
			alerta("El Código Postal no existe",'¡Atención!','cp');
			document.all.celcolonia.innerHTML = var_input;
			u.cp.focus();
		}
}

function existeCP(){
	if(document.getElementById('poblacion').value=="" && document.getElementById('colonia').value=="" && document.getElementById('pais').value==""){
		alerta('El codigo postal no existe', '¡Atención!','cp');
	}
}

function CalogoProspectoColonia(cp,colonia,poblacion,municipio,estado,pais){
	document.getElementById('cp').value=cp;
	document.all.celcolonia.innerHTML=var_input;
	document.getElementById('colonia').value=colonia;
	document.getElementById('poblacion').value=poblacion;
	document.getElementById('municipio').value=municipio;
	document.getElementById('estado').value=estado;
	document.getElementById('pais').value=pais;	
	document.all.telefono.focus();
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
            else if (frm.elements[i+1].readOnly ==true )    
                tabular(e,frm.elements[i+1]);
            else frm.elements[i+1].focus();
            return false;
} 
function ValidaRfc(rfcStr) {
	var strCorrecta;
	strCorrecta = rfcStr;
	
	if (document.form1.rdmoral[0].checked){
	var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	}else{
	var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
	}
	var validRfc=new RegExp(valid);
	var matchArray=strCorrecta.match(validRfc);
	if (matchArray==null) {
		return false;
	}else{
	return true;
	}
	
}

function isEmailAddress(theElement, nombre_del_elemento )
{
	var s = theElement.value;
	var filter=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
	if (s.length == 0 ) return true;
	if (filter.test(s))
		return true;
	else
		return false;
}

function validaCP(e,obj){
	tecla=(document.all) ? e.keyCode : e.which;
    if(tecla==8 && document.getElementById(obj).value==""){
document.getElementById('colonia').value=""; document.getElementById('poblacion').value=""; document.getElementById('municipio').value=""; document.getElementById('estado').value=""; document.getElementById('pais').value="";
	}
}

function obtener(id){
	document.getElementById('codigo').value=id;
	ConsultaProspecto(id,'prospecto');
}
function ConsultaProspecto(valor,tipo){
		consulta("MostrarConsultaProspecto","consultasProspecto.php?prospecto="+valor+"&tipo="+tipo+"&sid="+Math.random());
		
}
function MostrarConsultaProspecto(datos){
		var u= document.all;
		
		var con   = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		if(con>0){
			u.codigo.value = datos.getElementsByTagName('codigo').item(0).firstChild.data;
			u.rdmoral.value = datos.getElementsByTagName('rdmoral').item(0).firstChild.data;		if(u.rdmoral.value=="SI"){u.rdmoral[0].checked =true;}
			if(u.rdmoral.value=="NO"){u.rdmoral[1].checked =true; habilitar();}
			u.nombre.value = datos.getElementsByTagName('nombre').item(0).firstChild.data;
			u.paterno.value = datos.getElementsByTagName('paterno').item(0).firstChild.data;
			if(u.paterno.value==0){u.paterno.value="";}
			u.materno.value = datos.getElementsByTagName('materno').item(0).firstChild.data;
			if(u.materno.value==0){u.materno.value="";}
			u.rfc.value = datos.getElementsByTagName('rfc').item(0).firstChild.data;
			u.email.value = datos.getElementsByTagName('email').item(0).firstChild.data;
			if(u.email.value ==0){u.email.value = "";}
			u.celular.value = datos.getElementsByTagName('celular').item(0).firstChild.data;
			if(u.celular.value == 0){u.celular.value ="";}
			u.web.value = datos.getElementsByTagName('web').item(0).firstChild.data;
			if(u.web.value == 0){u.web.value = "";}
			u.calle.value = datos.getElementsByTagName('calle').item(0).firstChild.data;
			u.cp.value = datos.getElementsByTagName('cp').item(0).firstChild.data;
			u.entrecalles.value = datos.getElementsByTagName('entrecalles').item(0).firstChild.data;
			if(u.entrecalles.value==0){u.entrecalles.value=""};
			document.all.celcolonia.innerHTML = var_input;
			u.colonia.value = datos.getElementsByTagName('colonia').item(0).firstChild.data;
			u.numero.value = datos.getElementsByTagName('numero').item(0).firstChild.data;
			u.poblacion.value = datos.getElementsByTagName('poblacion').item(0).firstChild.data;
			u.municipio.value = datos.getElementsByTagName('municipio').item(0).firstChild.data;
			u.estado.value = datos.getElementsByTagName('estado').item(0).firstChild.data;
			u.pais.value = datos.getElementsByTagName('pais').item(0).firstChild.data;
			u.telefono.value = datos.getElementsByTagName('telefono').item(0).firstChild.data;
			u.fax.value = datos.getElementsByTagName('fax').item(0).firstChild.data;
			if(u.fax.value==0){u.fax.value="";}
			var list = datos.getElementsByTagName('listnick').item(0).firstChild.data;
			var listrepace="";
			for(i=0;i<=list.length;i++){
				listrepace +=list.charAt(i).replace(',','\n');
			}
			u.listnick.value=listrepace+'\n';
			u.accion.value="modificar";
			u.rfc_h.value = "";			
		}
}

function foco(nombrecaja){
	if(nombrecaja=="codigo"){
		document.getElementById('oculto').value="1";
	}else if(nombrecaja=="colonia"){
		document.getElementById('oculto').value="2";
	}
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
		document.all.celcolonia.innerHTML 			= var_input;
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
				alerta("La Colonia no existe","¡Atención!","colonia");
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
			document.all.celcolonia.innerHTML 			= var_input;
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
shortcut.add("Ctrl+b",function() {
	if(document.form1.oculto.value=="1"){
abrirVentanaFija('buscarprospecto.php', 550, 450, 'ventana', 'Busqueda')
	}else if(document.form1.oculto.value=="2"){
abrirVentanaFija('prospectoBuscarColonia.php', 570, 350, 'ventana', 'Busqueda')		
	}
});
</script>
<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:180px;	/* Width of box */
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

<body onload = "document.form1.nick.focus()" >
<form name="form1" method="post" >
  <table width="100%" border="0">
    
    <tr>
      <td><table width="420" height="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        <tr>
          <td width="351" height="20" class="FondoTabla">CATÁLOGO DE PROSPECTOS</td>
        </tr>
        <tr>
          <td height="328"><table width="400" height="341" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="70" height="23" class="Tablas" >Prospecto:</td>
              <td colspan="4"><input class="Tablas" name="codigo" type="text" id="codigo" style="background-color: #FFFF99; font:tahoma; font-size:9px;text-transform:uppercase" value="<?=$codigo; ?>" size="10" maxlength="11" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" readonly="readonly"/>
                <img src="../../img/Buscar_24.gif" alt="buscar" width="24" height="23" style="cursor:pointer" align="absbottom" title="Buscar Prospecto" onClick="abrirVentanaFija('buscarprospecto.php', 550, 450, 'ventana', 'Busqueda')"/></td>
            </tr>
            <tr>
              <td height="20" class="Tablas" >Nick:</td>
              <td colspan="4"><input class="Tablas" name="nick" type="text" id="nick" onBlur="trim(document.getElementById('nick').value,'nick');" size="40" style="font:tahoma;font-size:9px;text-transform:uppercase" onKeyPress="return tabular(event,this);" />
                <img src="../../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" align="absbottom" onClick="agregarnick('nick');" style="cursor:pointer" /></td>
            </tr>
            <tr>
              <td height="23" class="Tablas" ><img src="../../img/Boton_Eliminar.gif" alt="Eliminar" width="70" height="20" onClick="BorrarNick(nick.value);" style="cursor:pointer" /></td>
              <td colspan="4"><textarea class="Tablas" name="listnick" rows="3" id="listnick" style="background:#FFFF99;width:300px;text-transform:uppercase" readonly="readonly" onKeyPress="return tabular(event,this)" ><?=$listnick ?></textarea></td>
            </tr>
            <tr>
              <td height="23" class="Tablas" >&nbsp;</td>
              <td colspan="4"><span class="Tablas">
                <input name="rdmoral" type="radio" value="SI" onClick="habilitar();" <? if($rdmoral=="SI"||$rdmoral==""){echo'checked'; }?> style="width:12px" onKeyPress="return tabular(event,this)" />
Persona Moral &nbsp;&nbsp;&nbsp;&nbsp;
<input name="rdmoral" type="radio" value="NO" onClick="habilitar();"  <? if($rdmoral=="NO"){ echo'checked'; } ?> style="width:12px" onKeyPress="return tabular(event,this)" />
Persona Fis&iacute;ca </span></td>
            </tr>
            <tr>
              <td height="23" class="Tablas" >Nombre:</td>
              <td colspan="4"><input class="Tablas" name="nombre" type="text" id="nombre" style="font:tahoma;font-size:9px;text-transform:uppercase" onBlur="trim(document.getElementById('nombre').value,'nombre');" onKeyPress="return tabular(event,this)" value="<?= $nombre; ?>" size="61" maxlength="150"/></td>
            </tr>
            <tr>
              <td height="16" class="Tablas" >Ap. Paterno:</td>
              <td><input name="paterno" class="Tablas" type="text" id="paterno" style="font:tahoma;font-size:9px;text-transform:uppercase; background-color:#FFFF99" onBlur="trim(document.getElementById('paterno').value,'paterno');" onKeyPress="return tabular(event,this)" value="<?= $paterno; ?>" size="20" maxlength="100" <? if($rdmoral=="SI"||$rdmoral==""){echo 'disabled';} ?> /></td>
              <td><span class="Tablas">Ap. Materno:</span></td>
              <td colspan="2"><input name="materno" class="Tablas" type="text" id="materno" style="font:tahoma;font-size:9px;text-transform:uppercase;background-color:#FFFF99;" onBlur="trim(document.getElementById('materno').value,'materno');" onKeyPress="return tabular(event,this)"  value="<?= $materno; ?>" size="21" maxlength="100" <? if($rdmoral=="SI"||$rdmoral==""){echo 'disabled';} ?> /></td>
            </tr>
            <tr>
              <td height="16" class="Tablas" >R.F.C.:</td>
              <td><input name="rfc" type="text" class="Tablas" id="rfc" style="text-transform:uppercase;" onBlur="trim(document.getElementById('rfc').value,'rfc'); if(this.value!=''){obtenerRFC(this.value);}" onKeyPress="if(event.keyCode==13 || event.keyCode==9){obtenerRFC(this.value);}" value="<?= $rfc; ?>" size="20" maxlength="13"  /></td>
              <td><span class="Tablas">Email:</span></td>
              <td colspan="2"><input name="email" class="Tablas" type="text" id="email" style=" font:tahoma; font-size:9px" onBlur="trim(document.getElementById('email').value,'email');isEmailAddress(this,' nombre_del_elemento')" onKeyPress="return tabular(event,this)" value="<?= $email; ?>" size="21" maxlength="100"  /></td>
            </tr>
            <tr>
              <td height="16" class="Tablas" >Celular:</td>
              <td><input name="celular" class="Tablas" type="text" id="celular" size="20" maxlength="50" onKeyDown="return tabular(event,this);" onKeyPress="return solonumeros(event)" onBlur="trim(document.getElementById('celular').value,'celular');" value="<?= $celular; ?>" style="font:tahoma;font-size:9px;text-transform:uppercase;"/></td>
              <td><span class="Tablas">Sitio Web: </span></td>
              <td colspan="2"><input name="web" type="text" id="web" style="font:tahoma;font-size:9px;" onBlur="trim(document.getElementById('web').value,'web');" onKeyPress="return tabular(event,this)" class="Tablas" value="<?= $web; ?>" size="21" maxlength="150"/></td>
            </tr>
            <tr>
              <td height="16" colspan="5" class="FondoTabla" >DATOS DIRECCION</td>
              </tr>
            <tr>
              <td height="16" class="Tablas" >Calle:</td>
              <td colspan="4"><input class="Tablas" name="calle" type="text" id="calle" style="font:tahoma;font-size:9px;text-transform:uppercase;" onBlur="trim(document.getElementById('calle').value,'calle');" onKeyPress="return tabular(event,this)" value="<?= $calle; ?>" size="30" maxlength="150" />                <span class="Tablas">Numero:</span>                <input name="numero" type="text" id="numero" class="Tablas" style="font:tahoma;font-size:9px;text-transform:uppercase; width:110px" onBlur="trim(document.getElementById('numero').value,'numero');"  onKeyDown="return tabular(event,this)" value="<?= $numero; ?>" maxlength="10"/></td>
              </tr>
            <tr>
              <td height="23" class="Tablas" >Cruces Calles:</td>
              <td colspan="4"><input class="Tablas" name="entrecalles" type="text" id="entrecalles" onBlur="trim(document.getElementById('entrecalles').value,'entrecalles');" value="<?= $entrecalles; ?>" style="font:tahoma;font-size:9px;text-transform:uppercase; width:330px" onKeyPress="return tabular(event,this)"/></td>
              </tr>

            <tr>
              <td height="16" colspan="5" class="Tablas" ><table width="400" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="71">C.P.:</td>
                  <td width="88"><input name="cp" class="Tablas" type="text" id="cp" onBlur="if(this.value!=''){CodigoPostal(13, this.value);}" onKeyPress="if(event.keyCode==13){CodigoPostal(event, this.value); document.getElementById('telefono').focus()}else{return solonumeros(event);}" value="<?=$cp; ?>" size="10" maxlength="5" onKeyUp="return validaCP(event,this.name)" style="font:tahoma;font-size:9px; text-transform:uppercase" /></td>
                  <td width="57">Colonia:</td>
                  <td width="184" id="celcolonia"><input name="colonia" type="text" class="Tablas" id="colonia" style=" width:183px;text-transform:uppercase" onKeyUp="ajax_showOptions(this,'getCountriesByLetters',event,'../../buscadores_generales/ajax-list-colonias.php'); if(event.keyCode==13){devolverColonia();}; return validarColonia(event,this.name);" onBlur="if(this.value!=''){setTimeout('obtenerColoniaValida()',1000);}" value="<?=$colonia; ?>" /></td>
                </tr>
              </table></td>
              </tr>
            <tr>
              <td height="16" class="Tablas" >Poblaci&oacute;n:</td>
              <td><input class="Tablas" name="poblacion" type="text" id="poblacion" size="19"  style="font:tahoma;font-size:9px; background-color:#FFFF99;text-transform:uppercase;" readonly="readonly" value="<?= $poblacion; ?>"  onKeyPress="return tabular(event,this)"  /></td>
              <td><span class="Tablas">Mun./Del.:</span></td>
              <td colspan="2"><input class="Tablas" name="municipio" type="text" id="municipio" size="21"  style="font:tahoma;font-size:9px; background-color:#FFFF99;text-transform:uppercase;" readonly="readonly" value="<?= $municipio; ?>" onKeyPress="return tabular(event,this)"/></td>
            </tr>
            <tr>
              <td height="16" class="Tablas" >Estado:</td>
              <td><input name="estado" class="Tablas" type="text" id="estado" size="19"  value="<?= $estado; ?>" style="font:tahoma;font-size:9px; background-color:#FFFF99;text-transform:uppercase;" readonly="readonly" onKeyPress="return tabular(event,this)" /></td>
              <td><span class="Tablas">Pa&iacute;s: </span></td>
              <td colspan="2"><input class="Tablas" name="pais" type="text" id="pais" size="21"  value="<?= $pais; ?>" style="font:tahoma;font-size:9px; background-color:#FFFF99;text-transform:uppercase;" readonly="readonly" onKeyPress="return tabular(event,this)"/></td>
            </tr>
            <tr>
              <td height="16" class="Tablas" >Teléfono:</td>
              <td width="130"><input class="Tablas" name="telefono" type="text" id="telefono" style="font:tahoma;font-size:9px;text-transform:uppercase;" onBlur="trim(document.getElementById('telefono').value,'telefono');"  onKeyPress="return solonumeros(event)" onKeyDown="return tabular(event,this)" value="<?= $telefono; ?>" size="19" maxlength="20" /></td>
              <td width="77"><span class="Tablas">Fax:</span></td>
              <td width="128" colspan="2"><input class="Tablas" name="fax" type="text" id="fax" style="font:tahoma;font-size:9px;text-transform:uppercase;" onBlur="trim(document.getElementById('fax').value,'fax');" onKeyPress="return solonumeros(event)"  onKeyDown="return tabular(event,this)" value="<?= $fax; ?>" size="21" maxlength="20" /></td>
            </tr>
            <tr>
              <td height="23" class="Tablas" ><input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />
                <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" />
                <input type="hidden" id="colonia_hidden" name="coloniaid" />
				<input name="rfc_h" type="hidden" id="rfc_h" value="<?=$rfc_h ?>">
                <input name="cliente_h" type="hidden" id="cliente_h" value="<?=$cliente_h ?>">
                <input name="idcliente_h" type="hidden" id="idcliente_h" value="<?=$idcliente_h ?>"></td>
              <td colspan="4"><table width="20" border="0" align="right" cellpadding="1">
                <tr>
                  <td><img src="../../img/Boton_Guardar.gif" alt="enviar" width="70" height="20" onClick="validar();" style="cursor:pointer"/></td>
                  <td><img src="../../img/Boton_Nuevo.gif" alt="enviar" width="70" height="20" style="cursor:pointer"  onClick="confirmar('Perdera la información capturada ¿Desea continuar?', '', 'limpiar();', '')" /></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="23" colspan="5" class="Tablas" ></td>
</tr>
            
            
          </table></td>
        </tr>
      </table>
   </td>
    </tr>
  </table>
</form>
</body>
</html>
<script>
	habilitar();
</script>
<? 
if ($mensaje!=""){
	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";
	}
	
	//} 
?>