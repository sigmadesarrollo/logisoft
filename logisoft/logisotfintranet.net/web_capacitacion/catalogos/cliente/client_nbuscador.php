<?	session_start();
	require_once('../../Conectar.php');
	$link=Conectarse('webpmm');	
	
	$usuario	=$_SESSION[NOMBREUSUARIO]; $registros	=$_POST['registros'];
	$msg		=$_POST['msg']; 	  $esprospecto=$_POST['esprospecto'];
	$prospecto	=$_POST['prospecto']; $accion		=$_POST['accion']; 
	$codigo		=$_POST['codigo'];	  $convenio	=$_POST['convenio'];
	$rdmoral	=$_POST['rdmoral'];	  $nombre		= str_replace("´","",str_replace("`","",$_POST['nombre']));
	$paterno	=$_POST['paterno'];	  $materno	=$_POST['materno'];
	$rfc		=$_POST['rfc'];		  $email		=$_POST['email'];
	$celular	=$_POST['celular'];	  $web		=$_POST['web'];
	$listnick	=$_POST['listnick'];  $npoliza	=$_POST['npoliza'];
	$poliza		=$_POST['chpoliza'];  $aseguradora=$_POST['aseguradora'];
	$vigencia	=$_POST['vigencia'];  $tipocliente=$_POST['lstipocliente'];
	$clasificacioncliente=$_POST['clasificacioncliente']; $activado=$_POST['activado'];
	$pago		= $_POST['pago']; $clasificacion = $_POST[clasificacion];	
	$comisiongeneral = $_POST[comisiongeneral];
	
	$s = "SELECT comisiongeneral FROM configuradorgeneral";
		$r = mysql_query($s,$link) or die($s);
		$f = mysql_fetch_object($r);
		$comgeneral = $f->comisiongeneral;
	
	if($accion==""){
		$resid=folio('catalogocliente','webpmm');
		$codigo=$resid[0];
		$recoleccion = $_GET['recoleccion'];
	}else if($accion=="grabar"){
		
		$s = "INSERT INTO catalogocliente 
		(id, personamoral, tipocliente, nombre, paterno, materno, rfc, email,
		celular, web,  poliza, npoliza, aseguradora, vigencia, clasificacioncliente,
		activado, pagocheque, tipoclientepromociones, sucursal, comision, usuario, fecha,fecharegistro)
		VALUES(null, '$rdmoral', '$tipocliente', UCASE('$nombre'), UCASE('$paterno'),
		UCASE('$materno'), UCASE('$rfc'), '$email', '$celular', '$web',  '$poliza',
		'$npoliza', UCASE('$aseguradora'), '$vigencia', UCASE('$clasificacioncliente'),
		'$activado','$pago','$clasificacion', $_SESSION[IDSUCURSAL], '$comisiongeneral', '$usuario', current_timestamp(),current_date)";
		$sqlins=mysql_query($s,$link) or die($s);
		$codigo=mysql_insert_id();
		
		$varnick = split(chr(13),$listnick);
		
		$s = "INSERT INTO losclientes 
		(nick,rfc,id,nombre,paterno,materno,sucursal,convenio,credito)
		SELECT '$varnick[0]','$rfc','$codigo','$nombre','$paterno','$materno','".$_POST["tabladetalle_POBLACION"][$i]."','0','0'";
		mysql_query($s,$link) or die($s);
	//INSERTAR TABLA DETALLE
	if($registros>0){
		for($i=0;$i<$registros;$i++){
	$sqlins=mysql_query("INSERT INTO direccion 
	(origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado, 
	pais,telefono,fax,facturacion,usuario,fecha)VALUES
	('cl','$codigo',
	 UCASE('".$_POST["tabladetalle_CALLE"][$i]."'),
	 UCASE('".$_POST["tabladetalle_NUMERO"][$i]."'),
	 UCASE('".$_POST["tabladetalle_CRUCE"][$i]."'),
	 '".$_POST["tabladetalle_CP"][$i]."',
	 UCASE('".$_POST["tabladetalle_COLONIA"][$i]."'),	 
     '".$_POST["tabladetalle_POBLACION"][$i]."',
	 '".$_POST["tabladetalle_MUN"][$i]."', 
	 '".$_POST["tabladetalle_ESTADO"][$i]."',
	 '".$_POST["tabladetalle_PAIS"][$i]."',
	 '".$_POST["tabladetalle_TELEFONO"][$i]."',
	 '".$_POST["tabladetalle_FAX"][$i]."',
	 '".$_POST["tabladetalle_FACT"][$i]."',
	 '$usuario',CURRENT_TIMESTAMP())",$link) or die("error en linea".__LINE__);
		//Cadena Detalle
		$detalle .= "{
		calle:'".$_POST["tabladetalle_CALLE"][$i]."',
		num:'".$_POST["tabladetalle_NUMERO"][$i]."',
		cruce:'".$_POST["tabladetalle_CRUCE"][$i]."',
		cp:'".$_POST["tabladetalle_CP"][$i]."',
		colonia:'".$_POST["tabladetalle_COLONIA"][$i]."',
		poblacion:'".$_POST["tabladetalle_POBLACION"][$i]."',
		municipio:'".$_POST["tabladetalle_MUN"][$i]."', 
		estado:'".$_POST["tabladetalle_ESTADO"][$i]."',
		pais:'".$_POST["tabladetalle_PAIS"][$i]."',
		telefono:'".$_POST["tabladetalle_TELEFONO"][$i]."',
		fax:'".$_POST["tabladetalle_FAX"][$i]."',
		fact:'".$_POST["tabladetalle_FACT"][$i]."',
		id:'".mysql_insert_id()."'},";
		
		}
	$detalle = substr($detalle,0,strlen($detalle)-1);	
	}
	if($esprospecto=="SI"){
	$delpro=mysql_query("DELETE FROM catalogoprospecto WHERE id='$prospecto'",$link);
	$delprodir=mysql_query("DELETE FROM direccion WHERE origen='pro' AND codigo='$prospecto'",$link);
	}
	$mensaje	="Los datos han sido guardados correctamente";
	$accion		="modificar";	
	
	}else if($accion=="modificar"){
		$s = "UPDATE catalogocliente SET personamoral='$rdmoral',
	tipocliente='$tipocliente', nombre=UCASE('$nombre'), paterno=UCASE('$paterno'),
	materno=UCASE('$materno'), rfc=UCASE('$rfc'), email='$email', celular='$celular',
	web='$web', poliza='$poliza', npoliza='$npoliza', aseguradora=UCASE('$aseguradora'),
	vigencia='$vigencia', clasificacioncliente=UCASE('$clasificacioncliente'),
	activado='$activado', pagocheque='$pago', tipoclientepromociones='$clasificacion', sucursal='$_SESSION[IDSUCURSAL]',
	comision='$comisiongeneral', usuario='$usuario', fecha=current_timestamp() where id='$codigo'";
	$sqlupd=mysql_query($s,$link) or die($s);
	
	if($activado=="NO"){
		$s = "UPDATE solicitudcredito SET estado='BLOQUEADO', idusuario = ".$_SESSION[IDUSUARIO]." WHERE cliente='$codigo'";
		mysql_query($s,$link) or die($s);
		
		$s = "SELECT IFNULL(MAX(id),0) AS id FROM reportecliente2 WHERE idcliente = ".$codigo."";
		$r = mysql_query($s,$link) or die($s); 
		$cc = mysql_fetch_object($r);
		
		$s = "UPDATE reportecliente2 SET estadocredito = 'BLOQUEADO' WHERE id = ".$cc->id."";
		mysql_query($s,$link) or die($s);
	}else if($activado=="SI"){
		$s = "UPDATE solicitudcredito SET estado='ACTIVADO', idusuario = ".$_SESSION[IDUSUARIO]." WHERE cliente='$codigo'";
		mysql_query($s,$link) or die($s);
		
		$s = "SELECT IFNULL(MAX(id),0) AS id FROM reportecliente2 WHERE idcliente = ".$codigo."";
		$r = mysql_query($s,$link) or die($s); $cc = mysql_fetch_object($r);
		
		$s = "UPDATE reportecliente2 SET estadocredito = 'ACTIVADO' WHERE id = ".$cc->id."";
		mysql_query($s,$link) or die($s);
	}
	
	$sql_eliminar=mysql_query("DELETE FROM direccion WHERE origen='cl' AND codigo ='$codigo'",$link);
	//INSERTAR TABLA DETALLE
	if($registros>0){
		for($i=0;$i<$registros;$i++){
			$s = "INSERT INTO direccion 
			(id,origen,codigo,calle,numero,crucecalles,cp,colonia,poblacion,municipio,estado, 
			pais,telefono,fax,facturacion,usuario,fecha)VALUES
			('".$_POST["tabladetalle_ID"][$i]."','cl','$codigo',
			 UCASE('".$_POST["tabladetalle_CALLE"][$i]."'),
			 UCASE('".$_POST["tabladetalle_NUMERO"][$i]."'),
			 UCASE('".$_POST["tabladetalle_CRUCE"][$i]."'),
			 '".$_POST["tabladetalle_CP"][$i]."',
			 UCASE('".$_POST["tabladetalle_COLONIA"][$i]."'),
			 '".$_POST["tabladetalle_POBLACION"][$i]."',
			 '".$_POST["tabladetalle_MUN"][$i]."', 
			 '".$_POST["tabladetalle_ESTADO"][$i]."',
			 '".$_POST["tabladetalle_PAIS"][$i]."',
			 '".$_POST["tabladetalle_TELEFONO"][$i]."',
			 '".$_POST["tabladetalle_FAX"][$i]."',
			 '".$_POST["tabladetalle_FACT"][$i]."',
			 '$usuario',CURRENT_TIMESTAMP())";
				$sqlins=mysql_query($s,$link) or die("$s error en linea".__LINE__);
		//Cadena Detalle
		$detalle .= "{calle:'".$_POST["tabladetalle_CALLE"][$i]."',
						num:'".$_POST["tabladetalle_NUMERO"][$i]."',
						cruce:'".$_POST["tabladetalle_CRUCE"][$i]."',
						cp:'".$_POST["tabladetalle_CP"][$i]."',
						colonia:'".$_POST["tabladetalle_COLONIA"][$i]."',
						poblacion:'".$_POST["tabladetalle_POBLACION"][$i]."',
						municipio:'".$_POST["tabladetalle_MUN"][$i]."', 
						estado:'".$_POST["tabladetalle_ESTADO"][$i]."',
						pais:'".$_POST["tabladetalle_PAIS"][$i]."',
						telefono:'".$_POST["tabladetalle_TELEFONO"][$i]."',
						fax:'".$_POST["tabladetalle_FAX"][$i]."',
						fact:'".$_POST["tabladetalle_FACT"][$i]."',
						id:'".mysql_insert_id()."'},";
		}
	$detalle = substr($detalle,0,strlen($detalle)-1);	
	}
	
			$mensaje="Los cambios han sido guardados correctamente";
			$accion="modificar";
	}
	
	if($accion=="grabar"||$accion=="modificar"){
	$del=mysql_query("DELETE FROM catalogoclientenick WHERE cliente='$codigo'",$link);		
		$enter=chr(13);
		$lista=split($enter,$listnick);		
		if (count($lista)>0){
			for ($i=0;$i<count($lista);$i++){	
				$var = trim($lista[$i]);
				if ($var!=""){
					$reg=mysql_num_rows(mysql_query("SELECT * FROM catalogoclientenick WHERE cliente='$codigo' and nick='$var'",$link));
					if ($reg==0){
						$sqlins=mysql_query("INSERT INTO catalogoclientenick (id,cliente,nick,usuario,fecha) VALUES(null,'$codigo',UCASE('$var'),'$usuario',current_timestamp())",$link);
					}
				}
			}
		}
	}
	
if($accion=="limpiar"){
	$accion		=""; $codigo	="";  $rdconvenio	=""; 
	$rdmoral	=""; $nombre	="";  $paterno	="";
	$materno	=""; $rfc		="";  $email		=""; 
	$celular	=""; $web		=""; $listnick	=""; 
	$npoliza	=""; $poliza		=""; $aseguradora="";
	$vigencia	="";$tipocliente="";$clasificacioncliente="";
	$activado	=""; $prospecto	=""; $esprospecto="";
	$folioconvenio=""; $activacion =""; $vencimiento ="";
	$vendedor =""; $pago = ""; $clasificacion = "";
	$resid=folio('catalogocliente','webpmm');
	$comisiongeneral ="";
	$codigo=$resid[0];
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/shortcut.js"></script>
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" ></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../javascript/ajax.js"></script>
<script language="javascript" src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script language="javascript" type="text/javascript">
	
	jQuery(function($){
	 	$('#vigencia').mask("99/99/9999");
	 });
var busco = false;
var u = document.all;
var combo1 = "<select name='sltsucursales' id='sltsucursales' style='width:210px; font-size:9px' onKeyPress='return tabular(event,this)'>";
	
	var esModificar = "";
	var tabla1 = new ClaseTabla();
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
	window.onload = function(){
		//u.nick.focus();
		tabla1.create();
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
			consultaTexto("mostrarRfc","consultaCredito_con.php?accion=3&codigo="+u.codigo.value+"&rfc="+rfc+"&val="+Math.random());
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
	<?=$cpermiso->verificarPermiso(279,$_SESSION[IDUSUARIO]);?>
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
					document.form1.submit();
				}else if(document.getElementById('accion').value=="modificar"){
					document.form1.submit();
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
		}else if(u.rfc_h.value.replace("&#32;","")!="" && u.rfc_h.value.replace("&#32;","") == u.rfc.value){
			alerta3('El R.F.C.:'+u.rfc.value.toUpperCase()+' esta asignado al cliente '+u.cliente_h.value.toUpperCase(), '¡Atención!');
			return false;
		}else if(document.getElementById('email').value!="" && !isEmailAddress(document.form1.email)){
				alerta('Debe capturar Email valido.', '¡Atención!','email');
				return false;
		}else if(tabla1.getRecordCount()<=0 || tabla1.getRecordCount()==""){
				alerta3('Debe capturar Por lo menos una Dirección','¡Atención!');
				return false;			
		}else{
			
				if(document.getElementById('accion').value==""){
					document.getElementById('accion').value = "grabar";
					document.form1.submit();
				}else if(document.getElementById('accion').value="modificar"){
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
	document.form1.listnick.value ="";
	document.getElementById('nombre').value="";
	document.form1.rdmoral[0].checked;
	document.getElementById('paterno').value="";
	document.getElementById('materno').value="";
	document.getElementById('rfc').value="";	
	document.getElementById('accion').value = "limpiar";
	u.rfc_h.value = "";
    u.cliente_h.value = "";
    u.idcliente_h.value = "";
	document.form1.submit();
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
}

function obtenerCliente(id){
	u.codigo.value = id;
	consulta("mostrarCliente","consultasClientes.php?accion=1&cliente="+id+"&val="+Math.random());
}
function obtener(id){
	document.getElementById('codigo').value=id;
	consulta("mostrarCliente","consultasClientes.php?accion=1&cliente="+id+"&val="+Math.random());
	ocultarBuscador();
}
function mostrarCliente(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		limpiartodo();
		if(con>0){
	if(datos.getElementsByTagName('personamoral').item(0).firstChild.data=="SI"){
		u.rdmoral[0].checked=true;
	}else{
		u.rdmoral[1].checked=true;
	}
	u.nombre.value	= datos.getElementsByTagName('nombre').item(0).firstChild.data;
	u.paterno.value	= datos.getElementsByTagName('paterno').item(0).firstChild.data;
	u.materno.value	= datos.getElementsByTagName('materno').item(0).firstChild.data;
	u.comisiongeneral.value	= datos.getElementsByTagName('comision').item(0).firstChild.data;
	u.rfc.value		= datos.getElementsByTagName('rfc').item(0).firstChild.data;
	u.email.value	= datos.getElementsByTagName('email').item(0).firstChild.data;
	if(u.email.value== " "){u.email.value=""}
	u.celular.value	= datos.getElementsByTagName('celular').item(0).firstChild.data;
	u.web.value		= datos.getElementsByTagName('web').item(0).firstChild.data;
	u.lstipocliente.value = datos.getElementsByTagName('tipocliente').item(0).firstChild.data;	
	if(datos.getElementsByTagName('tieneconvenio').item(0).firstChild.data=="SI"){
		//u.convenio[0].checked = true;
		u.convenio.innerHTML = "CON CONVENIO";
	}else{
		u.convenio.innerHTML = "";
	}
	u.pago.checked 	= ((datos.getElementsByTagName('pagocheque').item(0).firstChild.data==1)?true:false);
	habilitar();
	u.npoliza.value	= datos.getElementsByTagName('npoliza').item(0).firstChild.data;
	if(datos.getElementsByTagName('poliza').item(0).firstChild.data=="SI"){
		u.chpoliza.checked = true;
	}else{
		u.chpoliza.checked = false;
	}
	u.aseguradora.value	=datos.getElementsByTagName('aseguradora').item(0).firstChild.data;
	u.vigencia.value	=datos.getElementsByTagName('vigencia').item(0).firstChild.data;	
	u.prospecto.readOnly = true;
	busco = false;
	u.btn_Eliminar.style.visibility = 'hidden';
	tabla1.setXML(datos);
			
	u.accion.value	="modificar";
	u.rfc_h.value = "";
	u.nick.focus();
	var total 		= datos.getElementsByTagName('total').item(0).firstChild.data;
	for(i=0;i<total;i++){
		u.listnick.value += datos.getElementsByTagName('nick').item(i).firstChild.data+'\n';
	}
	trim(u.listnick.value,'listnick');	
		}else{
			alerta3("El numero de Cliente no existe","¡Atención!");
			limpiartodo();
		}
	}
	
function obtenerProspectoCaja(id){
	consulta("mostrarProspecto","consultasClientes.php?accion=2&cliente="+id+"&val="+Math.random());	
}
function obtenerprospecto(id){
	document.getElementById('prospecto').value=id;
	consulta("mostrarProspecto","consultasClientes.php?accion=2&cliente="+id+"&val="+Math.random());	
}
function mostrarProspecto(datos){
		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;
		limpiartodo();		
		if(con>0){
			if(datos.getElementsByTagName('personamoral').item(0).firstChild.data=="SI"){
				u.rdmoral[0].checked=true;	
			}else{
				u.rdmoral[1].checked=true;	
			}
			u.nombre.value		=datos.getElementsByTagName('nombre').item(0).firstChild.data;
			u.paterno.value		=datos.getElementsByTagName('paterno').item(0).firstChild.data;
			u.materno.value		=datos.getElementsByTagName('materno').item(0).firstChild.data;
			u.rfc.value			=datos.getElementsByTagName('rfc').item(0).firstChild.data;
			u.email.value		=datos.getElementsByTagName('email').item(0).firstChild.data;
			if(u.email.value== " "){u.email.value=""}
			u.celular.value		=datos.getElementsByTagName('celular').item(0).firstChild.data;
			u.web.value			=datos.getElementsByTagName('web').item(0).firstChild.data;
			u.esprospecto.value	=datos.getElementsByTagName('esprospecto').item(0).firstChild.data;
			u.accion.value		="";
			habilitar();//----
			var total 			= datos.getElementsByTagName('total').item(0).firstChild.data;
			for(i=0;i<total;i++){
				u.listnick.value += datos.getElementsByTagName('nick').item(i).firstChild.data+'\n';
			}
			trim(u.listnick.value,'listnick');
			u.codigo.readOnly = true;
			consulta("obtenerDireccionProspecto","consultasClientes.php?accion=3&cliente="+u.prospecto.value
			+"&val="+Math.random());			
		}else{
			alerta3("El numero de Prospecto no existe","¡Atención!");
			u.prospecto.value = "";
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
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="7" class="FondoTabla">Datos Direcci&oacute;n</td>
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
                      <td><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" style="cursor:pointer" onClick="validar();" ></td>
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