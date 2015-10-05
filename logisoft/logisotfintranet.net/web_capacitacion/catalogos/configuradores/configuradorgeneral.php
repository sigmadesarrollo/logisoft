<?	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../../Conectar.php');	
	$link=Conectarse('webpmm');
	$usuario=$_SESSION[NOMBREUSUARIO];

	$id=$_POST['id']; $numeroconcepto=$_POST['numeroconcepto']; 
	$descuento=$_POST['descuento']; $cargocombustible=$_POST['cargocombustible'];  
	$ivaretenido=$_POST['ivaretenido']; $diaspermitidos=$_POST['diaspermitidos']; 
	$accion=$_POST['accion']; $prima=$_POST['prima']; 
	$cantidadvalordeclarado=$_POST['cantidadvalordeclarado']; 
	$ajustarvalordeclarado=$_POST['ajustarvalordeclarado'];
	$minimopagocheques = $_POST['minimopagocheques'];	$minimopagokg = $_POST['minimopagokg'];
	$minimopagokgexcedente = $_POST['minimopagokgexcedente'];
	$desmaximopermitido = $_POST['desmaximopermitido'];
	$maxvalordeclaradoguia = $_POST['maxvalordeclaradoguia'];
	$pesominaplicardescuento=$_POST['pesominaplicardescuento'];
	
	$eti_nombre1	=	$_POST['eti_nombre1'];
	$eti_nombre2	=	$_POST['eti_nombre2'];
	$eti_colonia	=	$_POST['eti_colonia'];
	$eti_direccion	=	$_POST['eti_direccion'];
	$eti_ciudad		=	$_POST['eti_ciudad'];
	$eti_rfc		=	$_POST['eti_rfc'];
	
	
	$list=$_POST['list'];
	$diasvencimientoconvenio=$_POST['diasvencimientoconvenio']; 
	$porcentajelimitecredito = $_POST[porcentajelimitecredito];
	$registros	= $_POST[registros]; 
	$hrs = $_POST[hrs]; $min = $_POST['min'];
	$shr = $_POST[shr]; $smin= $_POST[smin];
	$horario1 = $_POST[hrs].":".$_POST['min'].":00";
	$horario2 = $_POST[shr].":".$_POST[smin].":00"; 
	$comisiongeneral = $_POST[comisiongeneral]; 
	$comisionanual = $_POST[comisionanual];
	$ano = date("Y");
	if($accion==""){

		$sql=mysql_query("SELECT * FROM configuradorgeneral",$link);
		if(mysql_num_rows($sql)>0){
			$row=mysql_fetch_array($sql);
			$id=$row['id'];
			$numeroconcepto=$row['numeroconcepto'];
			$descuento=$row['descuento']; 
			$cargocombustible=$row['cargocombustible']; 
			$iva=$row['iva']; 
			$ivaretenido=$row['ivaretenido']; 
			$diaspermitidos=$row['diaspermitidos'];	
			$prima=$row['prima']; 
			$cantidadvalordeclarado=$row['cantidadvalordeclarado']; 
			$ajustarvalordeclarado=$row['ajustarvalordeclarado'];
			$minimopagocheques = $row['pagominimocheques'];
			$minimopagokg =$row['tarifaminimakg'];
			$minimopagokgexcedente = $row['tarifaminimakgexcedente'];
			$desmaximopermitido = $row['desmaximopermitido'];
			$maxvalordeclaradoguia = $row['maxvalordeclaradoguia'];
			$pesominaplicardescuento=$row['pesominaplicardescuento'];
			$diasvencimientoconvenio=$row['diasvencimientoconvenio']; 
			$porcentajelimitecredito = $row['porcentajelimitecredito'];
			
			$eti_nombre1	=	$row['eti_nombre1'];
			$eti_nombre2	=	$row['eti_nombre2'];
			$eti_direccion	=	$row['eti_direccion'];
			$eti_colonia	=	$row['eti_colonia'];
			$eti_ciudad		=	$row['eti_ciudad'];
			$eti_rfc		=	$row['eti_rfc'];
			
			$rr = explode(":",$row[horariolunesviernes]);
			$hrs = $rr[0];
			$min = $rr[1];
			
			$re = explode(":",$row[horariosabado]);
			$shr = $re[0];
			$smin = $re[1];
			$comisiongeneral = $row['comisiongeneral']; 
			$comisionanual = $row['comisionanual'];
			
			$list=$row['inhabilitados'];
			$accion="modificar";
		}		

	}else if($accion=="grabar"){
		$s = "INSERT INTO configuradorgeneral(numeroconcepto, descuento, cargocombustible,ivaretenido,
		diaspermitidos, prima, cantidadvalordeclarado, ajustarvalordeclarado,pagominimocheques,tarifaminimakg,
		tarifaminimakgexcedente,desmaximopermitido,maxvalordeclaradoguia,pesominaplicardescuento,
		horariolimiteregegreco,porcentajelimitecredito,horariolunesviernes,horariosabado,usuario,fecha,
		eti_nombre1,eti_nombre2,eti_direccion,eti_colonia,eti_ciudad,eti_rfc) VALUES  
		('$numeroconcepto','$descuento','$cargocombustible', '$ivaretenido','$diaspermitidos','$prima',
		'$cantidadvalordeclarado', '$ajustarvalordeclarado',
		'$minimopagocheques','$minimopagokg', '$minimopagokgexcedente', '$desmaximopermitido','$maxvalordeclaradoguia',
		'$pesominaplicardescuento', '$horariolimiteregegreco','$diasvencimientoconvenio',
		'$porcentajelimitecredito','$horario1','$horario2','$usuario',CURRENT_TIMESTAMP(),$comisiongeneral,$comisionanual,
		'$eti_nombre1','$eti_nombre2','$eti_direccion','$eti_colonia','$eti_ciudad','$eti_rfc')";
		$sqlins=mysql_query($s,$link) or die($s);

		$id=mysql_insert_id();

		$mensaje="Los datos han sido guardados correctamente";

		$accion="modificar";
		
		$sql_limpiar=mysql_query("DELETE FROM configuradorgeneraldias",$link)or die("Error en la line ".__LINE__);
		//INSERTAR TABLA DETALLE
		for($i=0; $i<$registros; $i++){
			$s = "INSERT INTO configuradorgeneraldias SET
			dia = '".cambiaf_a_mysql($_POST["detalle_FECHA"][$i])."', 
			usuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP()";
			mysql_query($s,$link) or die($s);
			$detalle .= "{dia:'".$_POST["detalle_FECHA"][$i]."'},";
		}
		$detalle = substr($detalle,0,strlen($detalle)-1);
		
	}else if($accion=="modificar"){
		$s = "UPDATE configuradorgeneral SET 
		numeroconcepto='$numeroconcepto', descuento='$descuento', 
		cargocombustible='$cargocombustible',  ivaretenido='$ivaretenido',
		diaspermitidos='$diaspermitidos', prima='$prima',
		cantidadvalordeclarado='$cantidadvalordeclarado',
		ajustarvalordeclarado='$ajustarvalordeclarado',
		pagominimocheques='$minimopagocheques',
		tarifaminimakg='$minimopagokg',
		tarifaminimakgexcedente='$minimopagokgexcedente',
		desmaximopermitido='$desmaximopermitido',
		maxvalordeclaradoguia='$maxvalordeclaradoguia',
		pesominaplicardescuento='$pesominaplicardescuento',
		diasvencimientoconvenio='$diasvencimientoconvenio',
		porcentajelimitecredito='$porcentajelimitecredito',
		horariolunesviernes = '$horario1',
		horariosabado = '$horario2',
		usuario='$usuario',fecha=current_timestamp(),
		comisiongeneral='$comisiongeneral',comisionanual='$comisionanual',
		eti_nombre1='$eti_nombre1',
		eti_nombre2='$eti_nombre2',
		eti_direccion='$eti_direccion',
		eti_colonia='$eti_colonia',
		eti_ciudad='$eti_ciudad',
		eti_rfc='$eti_rfc'
		WHERE id='$id'";
		$sqlupd=mysql_query($s,$link) or die($s);

		$mensaje="Los cambios han sido guardados correctamente";
		$accion="modificar";
				
		$sql_limpiar=mysql_query("DELETE FROM configuradorgeneraldias",$link)or die("Error en la linea ".__LINE__);
		//INSERTAR TABLA DETALLE
		for($i=0; $i<$registros; $i++){
			$s = "INSERT INTO configuradorgeneraldias SET
			dia = '".cambiaf_a_mysql($_POST["detalle_FECHA"][$i])."', 
			usuario = ".$_SESSION[IDUSUARIO].", fecha = CURRENT_TIMESTAMP()";
			mysql_query($s,$link) or die($s);
			$detalle .= "{dia:'".$_POST["detalle_FECHA"][$i]."'},";
		}
		$detalle = substr($detalle,0,strlen($detalle)-1);
		
		
}else if($accion=="limpiar"){

	$id=''; $numeroconcepto=''; $descuento=''; $cargocombustible='';  $ivaretenido=''; $diaspermitidos=''; $accion="";

		$sql=mysql_query("SELECT * FROM configuradorgeneral",$link);

		$row=mysql_fetch_array($sql);

$id=$row['id']; $numeroconcepto=$row['numeroconcepto']; $descuento=$row['descuento']; $cargocombustible=$row['cargocombustible'];  $ivaretenido=$row['ivaretenido']; $diaspermitidos=$row['diaspermitidos'];	$prima=$row['prima']; $cantidadvalordeclarado=$row['cantidadvalordeclarado']; $ajustarvalordeclarado=$row['ajustarvalordeclarado']; $minimopagocheques = $row['pagominimocheques']; $minimopagokg =$row['tarifaminimakg']; $minimopagokgexcedente = $row['tarifaminimakgexcedente']; $desmaximopermitido = $row['desmaximopermitido']; $maxvalordeclaradoguia = $row['maxvalordeclaradoguia']; $pesominaplicardescuento=$row['pesominaplicardescuento']; $list=$row['inhabilitados']; $diasvencimientoconvenio=$row['diasvencimientoconvenio']; $porcentajelimitecredito = $row['porcentajelimitecredito']; $comisiongeneral = $row['comisiongeneral']; $comisionanual = $row['comisionanual'];
$eti_nombre1 = $row['eti_nombre1'];	$eti_nombre2 = $row['eti_nombre2'];	$eti_direccion = $row['eti_direccion']; $eti_colonia = $row['eti_colonia']; $eti_ciudad = $row['eti_ciudad']; $eti_rfc = $row['eti_rfc'];

		$accion="modificar";

	}

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
	

<script language="javascript" src="../../javascript/Mascara.js"></script>
<script src="../../javascript/jquery.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script>
	
	jQuery(function($){
	 	$('#fecha').mask("99/99/9999");
	 });
	 
	var tabla1 	= new ClaseTabla();
	var nav4 = window.Event ? true : false;
	var u = document.all;
	
	 tabla1.setAttributes({
		nombre:"detalle",
		campos:[			
			{nombre:"FECHA", medida:250, alineacion:"left", datos:"dia"}
		],
		filasInicial:10,
		alto:150,
		seleccion:true,
		ordenable:false,
		eventoDblClickFila:"modificarFila()",
		nombrevar:"tabla1"
	});
	 
	 window.onload = function(){
	 	tabla1.create();
		u.numeroconcepto.focus();
		obtenerDias();
	 }
	 
	function obtenerDias(){		
		consultaTexto("mostrarDias","configuradorgeneral_con.php?accion=1");
	}
	
	function mostrarDias(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(datos);
			tabla1.setJsonData(obj);			
		}
		if(tabla1.getRecordCount()>0){
			u.imgEliminar.style.visibility = "visible";
		}
	}
	 
	function Numeros(evt){
		var key = nav4 ? evt.which : evt.keyCode; 	
		return (key <= 13 || (key >= 48 && key <= 57));
	}
	
	function tiposMoneda(evnt,valor){
		caja = valor;
		evnt = (evnt) ? evnt : event;
		var elem = (evnt.target) ? evnt.target : ((evnt.srcElement) ? evnt.srcElement : null);
		if (!elem.readOnly){
			var charCode = (evnt.charCode) ? evnt.charCode : ((evnt.keyCode) ? evnt.keyCode : ((evnt.which) ? evnt.which : 0));
			if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
				return false;
			}else{
				if(charCode==46){
					if(caja.indexOf(".")>-1){
						return false;
					}
				}
			}
			return true;
		}
	}
	
function validar(){

if(document.getElementById('numeroconcepto').value==""){ 

alerta('Debe capturar No. Concepto Guias', '¡Atención!','numeroconcepto');

}else if(document.getElementById('numeroconcepto').value<0){ 
alerta('No puede capturar Cantidades Negativas', '¡Atención!','numeroconcepto');	

}else if(document.getElementById('descuento').value==""){ 

alerta('Debe capturar Max Desc. Otorgado Cte.', '¡Atención!','descuento');

}else if(document.getElementById('descuento').value>100){

 alerta('El Descuento Otorgado no debe ser Mayor al 100%', '¡Atención!','descuento');

}else if(document.getElementById('descuento').value<0){ 
alerta('No puede capturar Cantidades Negativas', '¡Atención!','descuento');

}else if(document.getElementById('cargocombustible').value==""){

 alerta('Debe capturar Cargo Combustible', '¡Atención!','cargocombustible');

}else if(document.getElementById('cargocombustible').value>100){

 alerta('El Cargo Combustible no debe ser Mayor al 100%', '¡Atención!','cargocombustible');

}else if(document.getElementById('cargocombustible').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','cargocombustible'); 

}else if(document.getElementById('ivaretenido').value==""){

 alerta('Debe capturar IVA Retenido', '¡Atención!','ivaretenido');

}else if(document.getElementById('ivaretenido').value>100){ 

alerta('El IVA Retenido no debe ser Mayor al 100%', '¡Atención!','ivaretenido');

}else if(document.getElementById('ivaretenido').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','ivaretenido');	 

}else if(document.getElementById('diaspermitidos').value==""){

 alerta('Debe capturar No. Días Permitidos', '¡Atención!','diaspermitidos');	

}else if(document.getElementById('diaspermitidos').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','diaspermitidos');

}else if(document.getElementById('prima').value==""){

 alerta('Debe capturar Pago Prima Seguro', '¡Atención!','prima');

}else if(document.getElementById('prima').value>100){

 alerta('El Pago Prima Seguro no debe ser Mayor al 100%', '¡Atención!','prima');		

}else if(document.getElementById('prima').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','prima');

}else if(document.getElementById('cantidadvalordeclarado').value==""){

 alerta('Debe capturar Cantidad Reporte Valor Declarado', '¡Atención!','cantidadvalordeclarado');	

}else if(document.getElementById('cantidadvalordeclarado').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','cantidadvalordeclarado');

}else if(document.getElementById('ajustarvalordeclarado').value==""){

 alerta('Debe capturar Ajustar Reporte Valor Declarado', '¡Atención!','ajustarvalordeclarado');

}else if(document.getElementById('ajustarvalordeclarado').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','ajustarvalordeclarado');
}else{

		if(document.getElementById('accion').value==""){

			document.getElementById('accion').value = "grabar";
			u.registros.value = tabla1.getRecordCount();
			document.form1.submit();

		}else if(document.getElementById('accion').value=="modificar"){
			u.registros.value = tabla1.getRecordCount();
			document.form1.submit();

		}

	}

}



function limpiar(){

	document.getElementById('numeroconcepto').value			="";

	document.getElementById('descuento').value				="";

	document.getElementById('cargocombustible').value		="";

	document.getElementById('ivaretenido').value			="";

	document.getElementById('diaspermitidos').value			="";

	document.getElementById('minimopagocheques').value		="";

	document.getElementById('minimopagokg').value			="";

	document.getElementById('minimopagokgexcedente').value  ="";

	document.getElementById('desmaximopermitido').value 	="";

	document.getElementById('pesominaplicardescuento').value="";

	document.getElementById('accion').value					="limpiar";

	document.form1.submit();

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

            if (frm.elements[i+1].disabled ==true )    

                tabular(e,frm.elements[i+1]);

            else frm.elements[i+1].focus();

            return false;

} 

	function agregar(){
		if(u.fecha.value=="" || u.fecha.value=="__/__/____"){
			alerta('Debe capturar Fecha', '¡Atención!','fecha');
			return false;
		}	
		
		var mes  =  parseInt(u.fecha.value.substring(3,5),10);
		var dia  =  parseInt(u.fecha.value.substring(0,3),10);
		var year = 	parseInt(u.fecha.value.substring(6,10),10);

		if (!/^\d{2}\/\d{2}\/\d{4}$/.test(u.fecha.value)){
			alerta('La fecha capturada no es valida', '¡Atención!','fecha');
			return false;
		}

		if(year != u.ano.value){
			alerta('La fecha capturada debe ser del año en curso', '¡Atención!','fecha');
			return false;
		}
		
		if(dia > 29 && (mes=="02" || mes==2)){
			if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){
				alerta('La fecha capturada no es valida, por que el año '+year
				+' es bisiesto su maximo dia es 29', '¡Atención!','fecha');
				return false;
			}else{
				alerta('La fecha capturada no es valida, por que el año '+year
				+' no es bisiesto su maximo dia es 28', '¡Atención!','fecha');
				return false;
			}
		}
		
		if(dia >= 29 && (mes=="02" || mes=="2")){
			if(!((year % 4 == 0 && year % 100 != 0) || year % 400 == 0)){
				alerta('La fecha capturada no es valida, por que el año '+year
				+' no es bisiesto su maximo dia es 28', '¡Atención!','fecha');
				return false;
			}
		}

		if(dia > "31" || dia=="0"){
			alerta('La fecha capturada no es valida, capture correctamente el Dia', '¡Atención!','fecha');
			return false;
		}

		if(mes > "12" || mes=="0"){
			alerta('La fecha capturada no es valida, capture correctamente el Mes', '¡Atención!','fecha');
			return false;	
		}
	
		var v_fecha = tabla1.getValuesFromField("fecha",",");
	
		if(v_fecha.indexOf(u.fecha.value)>-1){
			alerta("La fecha "+u.fecha.value+" ya fue agregada","¡Atención!","fecha");
			return false;
		}
		
		var obj = new Object();
		obj.dia	= u.fecha.value;
			if(u.modificar.value==""){
				tabla1.add(obj);
				u.imgEliminar.style.visibility = "visible";
			}else{
				tabla1.updateRowById(tabla1.getSelectedIdRow(), obj);
				u.modificar.value = "";
			}
		u.fecha.value	= "";		
	}

	function modificarFila(){
		if(tabla1.getRecordCount()>0){
			if(tabla1.getValSelFromField("fecha","FECHA")!=""){
				var obj = tabla1.getSelectedRow();
				u.fecha.value = obj.fecha;
				u.modificar.value = "si";
			}
		}
	}
	
	function eliminarFila(){
		if(tabla1.getRecordCount()>0){
			if(tabla1.getValSelFromField('fecha','FECHA')!=""){
				confirmar('¿Esta seguro de Eliminar la Fila seleccionada?','','borrarFila()','');
			}
		}
	}
	function borrarFila(){
		tabla1.deleteById(tabla1.getSelectedIdRow());
		if(tabla1.getRecordCount()==0){		
			u.imgEliminar.style.visibility = "hidden";		
		}
	}

	function validarFecha(param,name){
		if(param!=""){
			var mes  =  parseInt(param.substring(3,5),10);
			var dia  =  parseInt(param.substring(0,3),10);
			var year = 	parseInt(param.substring(6,10),10);

			if (!/^\d{2}\/\d{2}\/\d{4}$/.test(param)){
				alerta('La fecha capturada no es valida', '¡Atención!',name);
				return false;
			}

			if(year != u.ano.value){
				alerta('La fecha capturada debe ser del año en curso', '¡Atención!',name);
				return false;
			}
			
			if(dia > 29 && (mes=="02" || mes==2)){
				if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){
					alerta3('La fecha capturada no es valida, por que el año '+year
					+' es bisiesto su maximo dia es 29', '¡Atención!');
					return false;
				}else{
					alerta3('La fecha capturada no es valida, por que el año '+year
					+' no es bisiesto su maximo dia es 28', '¡Atención!');
					return false;
				}
			}
			
			if(dia >= 29 && (mes=="02" || mes=="2")){
				if(!((year % 4 == 0 && year % 100 != 0) || year % 400 == 0)){
					alerta3('La fecha capturada no es valida, por que el año '+year
					+' no es bisiesto su maximo dia es 28', '¡Atención!');
					return false;
				}
			}

			if(dia > "31" || dia=="0"){
				alerta('La fecha capturada no es valida, capture correctamente el Dia', '¡Atención!',name);
				return false;
			}

			if(mes > "12" || mes=="0"){
				alerta('La fecha capturada no es valida, capture correctamente el Mes', '¡Atención!',name);
				return false;	
			}
		}		
	}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Configurador General</title>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">



<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>

<SCRIPT type="text/javascript" src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="Tablas.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.style1 {

	color: #FFFFFF;

	font-weight: bold;

}

.style2 {	color: #464442;

	font-size:9px;

	border: 0px none;

	background:none

}

.style3 {	font-size: 9px;

	color: #464442;

}

.style5 {color: #FFFFFF ; font-size:9px}

-->

</style>

<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css">
</head>



<body>

<form id="form1" name="form1" method="post" action="">

  <table width="100%" border="0">

    <tr>

      <td><table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="563" class="FondoTabla">CONFIGURADOR GENERAL</td>

          </tr>

          <tr>

            <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr> 

                  <td class="Tablas"><input name="id" type="hidden" id="id" value="<?=$id ?>"></td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>
                </tr>

                <tr> 

                  <td width="171" class="Tablas">No. Renglones Concepto Guias:                  </td>

                  <td width="76"><input name="numeroconcepto" class="Tablas" type="text" id="numeroconcepto" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('numeroconcepto').value,'numeroconcepto');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$numeroconcepto ?>" size="10"  /></td>

                  <td width="147"><span class="Tablas">Max Desc. Otorgado Cte: 

                    </span></td>

                  <td width="71"><input name="descuento" type="text" class="Tablas" id="descuento" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('descuento').value,'descuento');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$descuento ?>" size="10"  /></td>
                </tr>

                <tr> 

                  <td class="Tablas">% Cargo Combustible: </td>

                  <td><input name="cargocombustible" type="text" class="Tablas" id="cargocombustible" style="font-size:9px; font:tahoma"  onBlur="trim(document.getElementById('cargocombustible').value,'cargocombustible');" onKeyPress="return tiposMoneda(event,this.value)" onKeyDown="return tabular(event,this)" value="<?=$cargocombustible ?>" size="10" maxlength="3" /></td>

                  <td class="Tablas">% IVA Retenido:</td>

                  <td><input name="ivaretenido" type="text" class="Tablas" id="ivaretenido" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('ivaretenido').value,'ivaretenido');" onKeyPress="return tiposMoneda(event,this.value)" onKeyDown="return tabular(event,this)" value="<?=$ivaretenido ?>" size="10" maxlength="3"  /></td>
                </tr>

                <tr> 

                  <td colspan="4" class="Tablas">No. D&iacute;as Permitidos para 

                    Captura de Gastos Mes Anterior: 

                    <input name="diaspermitidos" type="text" class="Tablas" id="diaspermitidos" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('diaspermitidos').value,'diaspermitidos');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$diaspermitidos ?>" size="10" /></td>
                </tr>

                <tr> 

                  <td class="Tablas">% Pago Prima Seguro:</td>

                  <td class="Tablas"><input name="prima" type="text" class="Tablas" id="prima" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('ivaretenido').value,'ivaretenido');" onKeyPress="return tiposMoneda(event,this.value)" onKeyDown="return tabular(event,this)" value="<?=$prima ?>" size="10" maxlength="3"  /></td>

                  <td class="Tablas">Cantidad Reporte Valor Declarado:</td>

                  <td class="Tablas"><input name="cantidadvalordeclarado" class="Tablas" type="text" id="cantidadvalordeclarado" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('ivaretenido').value,'ivaretenido');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$cantidadvalordeclarado ?>" size="10" /></td>
                </tr>

                <tr> 

                  <td class="Tablas">Ajustar Reporte Valor Declarado:</td>

                  <td class="Tablas"><input name="ajustarvalordeclarado" class="Tablas" type="text" id="ajustarvalordeclarado" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('ivaretenido').value,'ivaretenido');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$ajustarvalordeclarado ?>" size="10" /></td>

                  <td class="Tablas">&nbsp;</td>

                  <td class="Tablas">&nbsp;</td>
                </tr>

                <tr> 

                  <td class="Tablas">Pago Mínimo para Cheques:</td>

                  <td class="Tablas"><input type="text" class="Tablas" name="minimopagocheques" value="<?=$minimopagocheques?>" style="font-size:9px; font:tahoma" size="10" onBlur="trim(document.getElementById('minimopagocheques').value,'minimopagocheques');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)"  ></td>

                  <td class="Tablas">Tatifa Mínima Precio KG:</td>

                  <td class="Tablas"><input type="text" class="Tablas" name="minimopagokg" value="<?=$minimopagokg?>" style="font-size:9px; font:tahoma" size="10" onKeyPress="return tiposMoneda(event,this.value)" onKeyDown="return tabular(event,this)"  onBlur="trim(document.getElementById('minimopagokg').value,'minimopagokg');"></td>
                </tr>

                <tr> 

                  <td class="Tablas">Tarifa Minima Precio KG Excedente:</td>

                  <td class="Tablas"><input type="text" class="Tablas" name="minimopagokgexcedente" value="<?=$minimopagokgexcedente?>" style="font-size:9px; font:tahoma" size="10" onBlur="trim(document.getElementById('minimopagokgexcedente').value,'minimopagokgexcedente');" onKeyPress="return tiposMoneda(event,this.value)" onKeyDown="return tabular(event,this)" ></td>

                  <td class="Tablas">Desc. Maximo Permitido:</td>

                  <td class="Tablas"><input name="desmaximopermitido" class="Tablas" type="text" id="desmaximopermitido" style="font-size:9px; font:tahoma"  onBlur="trim(document.getElementById('desmaximopermitido').value,'desmaximopermitido');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$desmaximopermitido?>" size="10" ></td>
                </tr>

                <tr> 

                  <td class="Tablas">Maximo Valor Declarado Por Guia:</td>

                  <td class="Tablas"><input name="maxvalordeclaradoguia" class="Tablas" type="text" id="maxvalordeclaradoguia" style="font-size:9px; font:tahoma" value="<?=$maxvalordeclaradoguia?>" size="10" maxlength="10" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" onBlur="trim(document.getElementById('maxvalordeclaradoguia').value,'maxvalordeclaradoguia');"  ></td>

                  <td class="Tablas">Peso M&iacute;nimo Para Aplicar Descuento:</td>

                  <td class="Tablas"><input name="pesominaplicardescuento" class="Tablas" type="text" id="pesominaplicardescuento" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('maxvalordeclaradoguia').value,'maxvalordeclaradoguia');"   onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$pesominaplicardescuento?>" size="10" maxlength="10"  ></td>
                </tr>

                <tr>

                  <td class="Tablas"><p>D&iacute;as notificaci&oacute;n vencimiento convenio:</p></td>

                  <td class="Tablas"><select name="diasvencimientoconvenio" class="Tablas" id="diasvencimientoconvenio">

                    <? for($i=0;$i<=30;$i++){?>

					<option value="<?=$i?>" <? if($diasvencimientoconvenio==$i){echo "selected";} ?> ><?=$i?></option>

					<? }?>

                  </select>                  </td>

                  <td class="Tablas">Porcentaje Limite Credito:</td>

                  <td class="Tablas">

                  <input name="porcentajelimitecredito" class="Tablas" type="text" style="font-size:9px; font:tahoma" 

                  onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" size="10" maxlength="2"  

                  value="<?=$porcentajelimitecredito?>">                  </td>
                </tr>

                <tr>
                  <td class="Tablas">Horario para EAD Lunes a Viernes: </td>
                  <td class="Tablas"><select name="hrs" class="Tablas" id="hrs" style="font-size:9px; font:tahoma;width:40px">
                    <? for($h=0;$h<24;$h++){?>
                    <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT)?>"   <? if($hrs == str_pad($h,2,"0",STR_PAD_LEFT)){echo "selected";} ?> >
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? } ?>
                  </select>
                    <select name="min" id="min" class="Tablas" style="font-size:9px; font:tahoma;width:40px">
                      <? for($m=0;$m<60;$m++){?>
                      <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"  <? if($min == str_pad($m,2,"0",STR_PAD_LEFT)){echo "selected";} ?>>
                      <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                      </option>
                      <? } ?>
                    </select></td>
                  <td class="Tablas">Horario para EAD Sabados:</td>
                  <td class="Tablas"><select name="shr" class="Tablas" id="shr" style="font-size:9px; font:tahoma;width:40px">
                    <? for($h=0;$h<24;$h++){?>
                    <option value="<?=str_pad($h,2,"0",STR_PAD_LEFT)?>"   <? if($shr == str_pad($h,2,"0",STR_PAD_LEFT)){echo "selected";} ?> >
                    <?=str_pad($h,2,"0",STR_PAD_LEFT);?>
                    </option>
                    <? } ?>
                  </select>
                    <select name="smin" id="smin" class="Tablas" style="font-size:9px; font:tahoma;width:40px">
                      <? for($m=0;$m<60;$m++){?>
                      <option value="<?=str_pad($m,2,"0",STR_PAD_LEFT);?>"  <? if($smin == str_pad($m,2,"0",STR_PAD_LEFT)){echo "selected";} ?>>
                      <?=str_pad($m,2,"0",STR_PAD_LEFT);?>
                      </option>
                      <? } ?>
                    </select></td>
                </tr>
                
                <tr>
                  <td class="Tablas">Comisi&oacute;n general para vendedores</td>
                  <td class="Tablas">
                  	<input name="comisiongeneral" class="Tablas" type="text" id="comisiongeneral" style="font-size:9px; font:tahoma" value="<?=$comisiongeneral?>" 
                    size="10" maxlength="10" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" 
                    onBlur="trim(document.getElementById('comisiongeneral').value,'comisiongeneral');">
                  </td>
                  <td class="Tablas">Comisi&oacute;n anual para vendedores</td>
                  <td class="Tablas">
                    <input name="comisionanual" class="Tablas" type="text" id="comisionanual" style="font-size:9px; font:tahoma" value="<?=$comisionanual?>" 
                    size="10" maxlength="10" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" 
                    onBlur="trim(document.getElementById('comisionanual').value,'comisionanual');">
                  </td>
                </tr>
                
                <tr> 

                  <td class="Tablas">D&iacute;as Inhabilitados:</td>

                  <td colspan="3" class="Tablas"><input name="fecha" class="Tablas" type="text" id="fecha" style="font:tahoma;font-size:9px;text-transform:uppercase" onBlur="trim(document.getElementById('fecha').value,'fecha');" onKeyPress="if(event.keyCode==13){ agregar();}" size="10" maxlength="10" /> 

                    <img src="../../img/calendario.gif" alt="fecha" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.forms[0].fecha,'dd/mm/yyyy',this)" /></td>
                </tr>

                <tr> 

                  <td class="Tablas"><p>&nbsp;</p></td>

                  <td colspan="3" class="Tablas"><table id="detalle" width="300" border="0" cellspacing="0" cellpadding="0">                   
                  </table></td>
                </tr>
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4"><table width="160" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="97" align="left"><img id="imgEliminar" src="../../img/Boton_Eliminar.gif" alt="Eliminar" width="70" height="20" onClick="borrarFila(fecha.value);"  style="visibility:hidden"/></td>
                      <td width="124" align="right"><img src="../../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" onClick="agregar();" /></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                	<td colspan="4"><table width="502" border="0" cellpadding="0" cellspacing="0">
                	  <tr>
                	    <td colspan="2">Datos de la etiqueta de la guia:</td>
              	    </tr>
                	  <tr>
                	    <td width="91">Nombre 1</td>
                	    <td width="411"><input type="text" name="eti_nombre1" style="width:300px" value="<?=$eti_nombre1?>"></td>
              	    </tr>
                	  <tr>
                	    <td>Nombre 2</td>
                	    <td><input type="text" name="eti_nombre2" style="width:300px" value="<?=$eti_nombre2?>"></td>
              	    </tr>
                	  <tr>
                	    <td>Direcci&oacute;n</td>
                	    <td><input type="text" name="eti_direccion" style="width:300px" value="<?=$eti_direccion?>"></td>
              	    </tr>
                	  <tr>
                	    <td>Colonia</td>
                	    <td><input type="text" name="eti_colonia" style="width:300px" value="<?=$eti_colonia?>"></td>
              	    </tr>
                	  <tr>
                	    <td>Ciudad,Estado</td>
                	    <td><input type="text" name="eti_ciudad" style="width:300px" value="<?=$eti_ciudad?>"></td>
              	    </tr>
                	  <tr>
                	    <td>RFC</td>
                	    <td><input type="text" name="eti_rfc" style="width:300px" value="<?=$eti_rfc?>"></td>
              	    </tr>
              	  </table></td>
                </tr>
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
                <tr> 

                  <td colspan="4"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">
                  <input name="ano" type="hidden" id="ano" value="<?=$ano ?>">
                  <input name="modificar" type="hidden" id="modificar" value="<?=$modificar ?>">
                  <input name="registros" type="hidden" id="registros" value="<?=$registros ?>"></td>
                </tr>

                <tr> 

                  <td colspan="4"><table width="119" border="0" align="right" cellpadding="0" cellspacing="0">

                      <tr> 

                        <td width="113"><img src="../../img/Boton_Guardar.gif" title="Guardar" width="70" height="20" style="cursor:pointer" onClick="validar();"></td>

                        <td width="37">&nbsp;</td>
                      </tr>

                    </table></td>
                </tr>

              </table>

            </td>

          </tr>

      </table>

      </td>

    </tr>

  </table>

  <p>&nbsp;</p>

</form>

</body>
</html>

<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}



?>