<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if(isset($_SESSION['gvalidar'])!=100){echo"<script language='javascript' type='text/javascript'>			document.location.href='../index.php';</script>";}else{*/

		include('../../Conectar.php');	

		$link=Conectarse('webpmm');

		$usuario=$_SESSION[NOMBREUSUARIO];

$id=$_POST['id']; $numeroconcepto=$_POST['numeroconcepto']; $descuento=$_POST['descuento']; $cargocombustible=$_POST['cargocombustible']; $iva=$_POST['iva']; $ivaretenido=$_POST['ivaretenido']; $diaspermitidos=$_POST['diaspermitidos']; $accion=$_POST['accion']; $prima=$_POST['prima']; $cantidadvalordeclarado=$_POST['cantidadvalordeclarado']; $ajustarvalordeclarado=$_POST['ajustarvalordeclarado'];$minimopagocheques = $_POST['minimopagocheques'];$minimopagokg = $_POST['minimopagokg'];$minimopagokgexcedente = $_POST['minimopagokgexcedente'];$minimopagokmexcedente = $_POST['minimopagokmexcedente'];$pesominimocajapaquete = $_POST['pesominimocajapaquete'];$maxvalordeclaradoguia = $_POST['maxvalordeclaradoguia'];$pesominaplicardescuento=$_POST['pesominaplicardescuento'];$fondocaja=$_POST['fondocaja'];$list=$_POST['list'];





	if($accion==""){

		$sql=mysql_query("SELECT * FROM configuradorgeneral",$link);

		if(mysql_num_rows($sql)>0){

		$row=mysql_fetch_array($sql);

$id=$row['id']; $numeroconcepto=$row['numeroconcepto']; $descuento=$row['descuento']; $cargocombustible=$row['cargocombustible']; $iva=$row['iva']; $ivaretenido=$row['ivaretenido']; $diaspermitidos=$row['diaspermitidos'];	$prima=$row['prima']; $cantidadvalordeclarado=$row['cantidadvalordeclarado']; $ajustarvalordeclarado=$row['ajustarvalordeclarado'];$minimopagocheques = $row['pagominimocheques'];$minimopagokg =$row['tarifaminimakg'];$minimopagokgexcedente = $row['tarifaminimakgexcedente'];$minimopagokmexcedente = $row['tarifaminimakmexcedente'];$pesominimocajapaquete = $row['pesomincajapaquete'];$maxvalordeclaradoguia = $row['maxvalordeclaradoguia'];$pesominaplicardescuento=$row['pesominaplicardescuento'];$fondocaja=$row['fondocaja'];

$list=$row['inhabilitados'];

		$accion="modificar";

		}		

	}else if($accion=="grabar"){

		$sqlins=mysql_query("INSERT INTO configuradorgeneral (id,numeroconcepto,descuento,cargocombustible,iva,ivaretenido,diaspermitidos, prima, cantidadvalordeclarado, ajustarvalordeclarado, pagominimocheques,tarifaminimakg,tarifaminimakgexcedente,tarifaminimakmexcedente,pesomincajapaquete,maxvalordeclaradoguia,pesominaplicardescuento,fondocaja,inhabilitados,usuario,fecha) VALUES (null,'$numeroconcepto','$descuento','$cargocombustible','$iva','$ivaretenido','$diaspermitidos','$prima', '$cantidadvalordeclarado', '$ajustarvalordeclarado','$minimopagocheques','$minimopagokg','$minimopagokgexcedente','$minimopagokmexcedente','$pesominimocajapaquete','$maxvalordeclaradoguia','$pesominaplicardescuento','$fondocaja','$list','$usuario',current_timestamp())",$link);

		

		$id=mysql_insert_id();

		$mensaje="Los datos han sido guardados correctamente";

		$accion="modificar";

	}else if($accion=="modificar"){

		$sqlupd=mysql_query("UPDATE configuradorgeneral SET 	numeroconcepto='$numeroconcepto', descuento='$descuento', 	cargocombustible='$cargocombustible', iva='$iva', ivaretenido='$ivaretenido', diaspermitidos='$diaspermitidos', prima='$prima', cantidadvalordeclarado='$cantidadvalordeclarado',ajustarvalordeclarado='$ajustarvalordeclarado',pagominimocheques='$minimopagocheques',tarifaminimakg='$minimopagokg',tarifaminimakgexcedente='$minimopagokgexcedente',tarifaminimakmexcedente='$minimopagokmexcedente',pesomincajapaquete='$pesominimocajapaquete',maxvalordeclaradoguia='$maxvalordeclaradoguia', pesominaplicardescuento='$pesominaplicardescuento',fondocaja='$fondocaja' ,inhabilitados='$list',usuario='$usuario',fecha=current_timestamp() WHERE id='$id'",$link);

		$mensaje="Los cambios han sido guardados correctamente";

		$accion="modificar";

}else if($accion=="limpiar"){

	$id=''; $numeroconcepto=''; $descuento=''; $cargocombustible=''; $iva=''; $ivaretenido=''; $diaspermitidos=''; $accion="";

		$sql=mysql_query("SELECT * FROM configuradorgeneral",$link);

		$row=mysql_fetch_array($sql);

$id=$row['id']; $numeroconcepto=$row['numeroconcepto']; $descuento=$row['descuento']; $cargocombustible=$row['cargocombustible']; $iva=$row['iva']; $ivaretenido=$row['ivaretenido']; $diaspermitidos=$row['diaspermitidos']; $prima=$row['prima']; $cantidadvalordeclarado=$row['cantidadvalordeclarado']; $ajustarvalordeclarado=$row['ajustarvalordeclarado'];$pesominaplicardescuento=$row['pesominaplicardescuento'];$fondocaja=$row['fondocaja'];$list=$row['inhabilitados'];	

		$accion="modificar";

	}

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script>

var nav4 = window.Event ? true : false;

function Numeros(evt){

	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 

	var key = nav4 ? evt.which : evt.keyCode; 

	return (key <= 13 || (key >= 48 && key <= 57));

}

function validar(){

if(document.getElementById('numeroconcepto').value==""){ 

alerta('Debe capturar No. Concepto Guias', '¡Atención!','numeroconcepto');

}else if(document.getElementById('numeroconcepto').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','numeroconcepto');	

}else if(document.getElementById('descuento').value==""){ 

alerta('Debe capturar Max Desc. Otorgado Cte.', '¡Atención!','descuento');

}else if(document.getElementById('descuento').value>100){

 alerta('El Descuento Otorgado no debe ser Mayor al 100%', '¡Atención!','descuento');

}else if(document.getElementById('descuento').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','descuento');

}else if(document.getElementById('cargocombustible').value==""){

 alerta('Debe capturar Cargo Combustible', '¡Atención!','cargocombustible');

}else if(document.getElementById('cargocombustible').value>100){

 alerta('El Cargo Combustible no debe ser Mayor al 100%', '¡Atención!','cargocombustible');

}else if(document.getElementById('cargocombustible').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','cargocombustible'); 

}else if(document.getElementById('iva').value==""){

 alerta('Debe capturar IVA', '¡Atención!','iva');

}else if(document.getElementById('iva').value>100){ 

alerta('El IVA no debe ser Mayor al 100%', '¡Atención!','iva');

}else if(document.getElementById('iva').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','iva'); 

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

}else if(document.getElementById('minimopagocheques').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','minimopagocheques');	 

}else if(document.getElementById('minimopagokg').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','minimopagokg');	 

}else if(document.getElementById('minimopagokgexcedente').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','minimopagokgexcedente');	 

}else if(document.getElementById('minimopagokmexcedente').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','minimopagokmexcedente');	 

}else if(document.getElementById('maxvalordeclaradoguia').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','maxvalordeclaradoguia');	 

}else if(document.getElementById('pesominimocajapaquete').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','pesominimocajapaquete');	 

}else if(document.getElementById('pesominaplicardescuento').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','pesominaplicardescuento');	

}else if(document.getElementById('fondocaja').value<0){ alerta('No puede capturar Cantidades Negativas', '¡Atención!','fondocaja');	

}else{

		if(document.getElementById('accion').value==""){

			document.getElementById('accion').value = "grabar";

			document.form1.submit();

		}else if(document.getElementById('accion').value=="modificar"){

			document.form1.submit();

		}

	}

}

function limpiar(){

	document.getElementById('numeroconcepto').value			="";

	document.getElementById('descuento').value				="";

	document.getElementById('cargocombustible').value		="";

	document.getElementById('iva').value					="";

	document.getElementById('ivaretenido').value			="";

	document.getElementById('diaspermitidos').value			="";

	document.getElementById('minimopagocheques').value		="";

	document.getElementById('minimopagokg').value			="";

	document.getElementById('minimopagokgexcedente').value  ="";

	document.getElementById('minimopagokmexcedente').value  ="";

	document.getElementById('pesominimocajapaquete').value  ="";

	document.getElementById('pesominaplicardescuento').value="";

	document.getElementById('fondocaja').value 				="";

	document.getElementById('accion').value					="limpiar";

	document.form1.submit();

}



function trim(cadena,caja)

{

	for(i=0;i<cadena.length;i++)

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





function agregar(param){

var mes  =  parseInt(document.getElementById('fecha').value.substring(3,5),10);

var dia  =  parseInt(document.getElementById('fecha').value.substring(0,2),10);

if(document.getElementById(param).value==""){

	alerta('Capture una fecha', '¡Atención!','fecha');

	return false;

}

if (!/^\d{2}\/\d{2}\/\d{4}$/.test(document.getElementById('fecha').value)){

	alerta('La fecha no es valida', '¡Atención!','fecha');

	return false;

}

if (dia>"31" || dia=="0" ){

	alerta('La fecha no es valida, capture correctamente el Dia', '¡Atención!','fecha');

	return false;	

}

if (mes>"12" || mes=="0" ){

	alerta('La fecha no es valida, capture correctamente el Mes', '¡Atención!','fecha');

	return false;	

}

	var par=new RegExp(document.getElementById(param).value.toUpperCase()+'[\r\n]+');

    var txt=document.getElementById('list').value.split(par); 

	if(!par.test(document.getElementById('list').value)){ 

 	document.getElementById('list').value = document.getElementById('list').value + 	document.getElementById(param).value.toUpperCase() + "\n";

	document.getElementById(param).value ="";

	document.getElementById(param).focus();

	}else{	

	alerta('La Fecha ' + document.getElementById(param).value + ' ya existe', '¡Atención!','fecha');	

    	return;

	}	

}



function Borrar(linea){

	linea=linea.toUpperCase();

	par=new RegExp(linea+'[\r\n]+'); 

    var txt=document.getElementById('list').value.split(par); 

    if(!par.test(document.getElementById('list').value)){

	alerta('La Fecha ' + linea + ' no existe', '¡Atención!','fecha');        

        return; 

    }

    if(document.getElementById('fecha').value==""){

		alerta('Debe escribir la fecha a Borrar', '¡Atención!','fecha'); 

        return;		

	}else if (!/^\d{2}\/\d{2}\/\d{4}$/.test(document.getElementById('fecha').value)){

		alerta('La fecha  no es valida', '¡Atención!','fecha');

		return false;

	}else if(confirmar('¿Esta seguro de borrar la fecha?', '', 'BorrarConfirmacion(document.getElementById(\'fecha\').value);', '')){	

	}

} 



function BorrarConfirmacion(linea){

	linea=linea.toUpperCase();

	var par=new RegExp(linea+'[\r\n]+'); 

    var txt=document.getElementById('list').value.split(par);

	document.getElementById('list').value=txt.join (''); 

    document.getElementById('fecha').value="";

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



<link type="text/css" rel="stylesheet" href="calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></LINK>

<SCRIPT type="text/javascript" src="calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>



<link href="puntovta.css" rel="stylesheet" type="text/css">

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

</head>



<body onLoad="document.form1.numeroconcepto.focus()">

<form id="form1" name="form1" method="post" action="">

  <table width="100%" border="0">

    <tr>

      <td><table width="500" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="563" class="FondoTabla">Datos Generales </td>

          </tr>

          <tr>

            <td><table width="465" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr>

                  <td class="Tablas"><input name="id" type="hidden" id="id" value="<?=$id ?>"></td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

                <tr>

                  <td width="171" class="Tablas">No. Renglones Concepto Guias: </td>

                  <td width="76"><input name="numeroconcepto" type="text" id="numeroconcepto" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('numeroconcepto').value,'numeroconcepto');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$numeroconcepto ?>" size="10"  /></td>

                  <td width="147"><span class="Tablas">Max Desc. Otorgado Cte: </span></td>

                  <td width="71"><input name="descuento" type="text" id="descuento" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('descuento').value,'descuento');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$descuento ?>" size="10"  /></td>

                </tr>

                <tr>

                  <td class="Tablas">% Cargo Combustible: </td>

                  <td><input name="cargocombustible" type="text" id="cargocombustible" style="font-size:9px; font:tahoma"  onBlur="trim(document.getElementById('cargocombustible').value,'cargocombustible');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$cargocombustible ?>" size="10" maxlength="3" /></td>

                  <td class="Tablas">% IVA:</td>

                  <td><input name="iva" type="text" id="iva" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('iva').value,'iva');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$iva ?>" size="10" maxlength="3"  /></td>

                </tr>

                <tr>

                  <td class="Tablas">% IVA Retenido</td>

                  <td><input name="ivaretenido" type="text" id="ivaretenido" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('ivaretenido').value,'ivaretenido');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$ivaretenido ?>" size="10" maxlength="3"  /></td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>                

                <tr>

                  <td colspan="4" class="Tablas">No. D&iacute;as Permitidos para Captura de Gastos Mes Anterior:

                    <input name="diaspermitidos" type="text" id="diaspermitidos" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('diaspermitidos').value,'diaspermitidos');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$diaspermitidos ?>" size="10" /></td>

                </tr>

                <tr>

                  <td class="Tablas">% Pago Prima Seguro:</td>

                  <td class="Tablas"><input name="prima" type="text" id="prima" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('ivaretenido').value,'ivaretenido');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$prima ?>" size="10" maxlength="3"  /></td>

                  <td class="Tablas">Cantidad Reporte Valor Declarado:</td>

                  <td class="Tablas"><input name="cantidadvalordeclarado" type="text" id="cantidadvalordeclarado" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('ivaretenido').value,'ivaretenido');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$cantidadvalordeclarado ?>" size="10" /></td>

                </tr>

                <tr>

                  <td class="Tablas">Ajustar Reporte Valor Declarado:</td>

                  <td class="Tablas"><input name="ajustarvalordeclarado" type="text" id="ajustarvalordeclarado" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('ivaretenido').value,'ivaretenido');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$ajustarvalordeclarado ?>" size="10" /></td>

                  <td class="Tablas">&nbsp;</td>

                  <td class="Tablas">&nbsp;</td>

                </tr>

                <tr>

                  <td class="Tablas">Pago Mínimo para Cheques</td>

                  <td class="Tablas"><input type="text" name="minimopagocheques" value="<?=$minimopagocheques?>" style="font-size:9px; font:tahoma" size="10" onBlur="trim(document.getElementById('minimopagocheques').value,'minimopagocheques');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)"  ></td>

                  <td class="Tablas">Tatifa Mínima  Precio KG</td>

                  <td class="Tablas"><input type="text" name="minimopagokg" value="<?=$minimopagokg?>" style="font-size:9px; font:tahoma" size="10" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)"  onBlur="trim(document.getElementById('minimopagokg').value,'minimopagokg');"></td>

                </tr>

                 <tr>

                  <td class="Tablas">Tarifa Minima A Precio KG Excedente</td>

                  <td class="Tablas"><input type="text" name="minimopagokgexcedente" value="<?=$minimopagokgexcedente?>" style="font-size:9px; font:tahoma" size="10" onBlur="trim(document.getElementById('minimopagokgexcedente').value,'minimopagokgexcedente');" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" ></td>

                  <td class="Tablas">Tarifa Minima A Precio KM Excedente</td>

                  <td class="Tablas"><input type="text" name="minimopagokmexcedente" value="<?=$minimopagokmexcedente?>" style="font-size:9px; font:tahoma" size="10" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)"  onBlur="trim(document.getElementById('minimopagokmexcedente').value,'minimopagokmexcedente');" ></td>

                </tr>

                 <tr>

                  <td class="Tablas">Peso Minimo Caja Paquete</td>

                  <td class="Tablas"><input type="text" name="pesominimocajapaquete" value="<?=$pesominimocajapaquete?>" style="font-size:9px; font:tahoma" size="10" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)"  onBlur="trim(document.getElementById('pesominimocajapaquete').value,'pesominimocajapaquete');"  ></td>

                  <td class="Tablas">Maximo Valor Declarado Por Guia</td>

                  <td class="Tablas"><input name="maxvalordeclaradoguia" type="text" id="maxvalordeclaradoguia" style="font-size:9px; font:tahoma" value="<?=$maxvalordeclaradoguia?>" size="10" maxlength="10" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" onBlur="trim(document.getElementById('maxvalordeclaradoguia').value,'maxvalordeclaradoguia');"  ></td>

                </tr>

                 <tr>

<td class="Tablas"><p>Peso M&iacute;nimo Para Aplicar Descuento</p></td>

                   <td class="Tablas"><input name="pesominaplicardescuento" type="text" id="pesominaplicardescuento" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('maxvalordeclaradoguia').value,'maxvalordeclaradoguia');"   onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$pesominaplicardescuento?>" size="10" maxlength="10"  ></td>

<td class="Tablas">Fondo Caja</td>

                   <td class="Tablas"><input name="fondocaja" type="text" id="fondocaja" style="font-size:9px; font:tahoma" onBlur="trim(document.getElementById('maxvalordeclaradoguia').value,'maxvalordeclaradoguia');"   onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$fondocaja?>" size="10" maxlength="10"  ></td>

                 </tr>

                 <tr>

                   <td class="Tablas">D&iacute;as Inhabilitados</td>

                   <td colspan="3" class="Tablas"><input name="fecha" type="text" id="fecha" style="font:tahoma;font-size:9px;text-transform:uppercase" onBlur="trim(document.getElementById('fecha').value,'fecha');" onKeyPress="if(event.keyCode== 13){agregar('fecha');}" size="10" maxlength="10" />

                     <img src="../../img/calendario.gif" alt="fecha" width="25" height="25" align="absbottom" style="cursor:pointer" title="Calendario" onClick="displayCalendar(document.forms[0].fecha,'dd/mm/yyyy',this)" /><img src="../../img/Boton_Agregari.gif" alt="Agregar" width="70" height="20" align="absbottom" onClick="agregar('fecha');" /></td>

                 </tr>

                <tr>

                  <td class="Tablas"><p><img src="../../img/Boton_Eliminar.gif" alt="Eliminar" width="70" height="20" align="right" onClick="Borrar(fecha.value);" /></p></td>

                  <td colspan="3" class="Tablas"><textarea name="list" rows="3" readonly="readonly" id="list" style="background:#FFFF99;width:150px;text-transform:uppercase" onKeyPress="return tabular(event,this)"><?=$list?></textarea></td>

</tr>

                <tr>

                  <td colspan="4"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>

                </tr>

                <tr>

                  <td colspan="4"><table width="119" border="0" align="right" cellpadding="0" cellspacing="0">

                    <tr>

                      <td width="113"><img src="../../img/Boton_Guardar.gif" title="Guardar" width="70" height="20" style="cursor:pointer" onClick="validar();"></td>

                      <td width="37">&nbsp;</td>

                      </tr>

                  </table></td>

                </tr>

                

            </table></td>

          </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR GENERAL';

</script>

</html>

<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//}

?>