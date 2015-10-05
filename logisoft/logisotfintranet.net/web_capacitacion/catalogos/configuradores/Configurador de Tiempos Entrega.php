<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if(isset($_SESSION['gvalidar'])!=100){echo"<script language='javascript' type='text/javascript'>			document.location.href='../index.php';</script>";}else{*/

		include('../../Conectar.php');	

		$link=Conectarse('webpmm');

		$usuario=$_SESSION[NOMBREUSUARIO];

$id=$_POST['id']; $numeroconcepto=$_POST['numeroconcepto']; $descuento=$_POST['descuento']; $cargocombustible=$_POST['cargocombustible']; $iva=$_POST['iva']; $ivaretenido=$_POST['ivaretenido']; $diaspermitidos=$_POST['diaspermitidos']; $accion=$_POST['accion']; $prima=$_POST['prima']; $cantidadvalordeclarado=$_POST['cantidadvalordeclarado']; $ajustarvalordeclarado=$_POST['ajustarvalordeclarado'];



	if($accion==""){

		$sql=mysql_query("SELECT * FROM configuradorgeneral",$link);

		if(mysql_num_rows($sql)>0){

		$row=mysql_fetch_array($sql);

$id=$row['id']; $numeroconcepto=$row['numeroconcepto']; $descuento=$row['descuento']; $cargocombustible=$row['cargocombustible']; $iva=$row['iva']; $ivaretenido=$row['ivaretenido']; $diaspermitidos=$row['diaspermitidos'];	$prima=$row['prima']; $cantidadvalordeclarado=$row['cantidadvalordeclarado']; $ajustarvalordeclarado=$row['ajustarvalordeclarado'];

		$accion="modificar";

		}		

	}else if($accion=="grabar"){

		$sqlins=mysql_query("INSERT INTO configuradorgeneral (id,numeroconcepto,descuento,cargocombustible,iva,ivaretenido,diaspermitidos, prima, cantidadvalordeclarado, ajustarvalordeclarado, usuario,fecha) VALUES ('null','$numeroconcepto','$cargocombustible','$bolsaempaque','$iva','$ivaretenido','$diaspermitidos','$prima', '$cantidadvalordeclarado', '$ajustarvalordeclarado','$usuario',current_timestamp())",$link);

		$id=mysql_insert_id();

		$mensaje="Los datos han sido guardados correctamente";

		$accion="modificar";

	}else if($accion=="modificar"){

		$sqlupd=mysql_query("UPDATE configuradorgeneral SET 	numeroconcepto='$numeroconcepto', descuento='$descuento', 	cargocombustible='$cargocombustible', iva='$iva', ivaretenido='$ivaretenido', diaspermitidos='$diaspermitidos', prima='$prima', cantidadvalordeclarado='$cantidadvalordeclarado', ajustarvalordeclarado='$ajustarvalordeclarado', usuario='$usuario',fecha=current_timestamp() WHERE id='$id'",$link);		

	$mensaje="Los cambios han sido guardados correctamente";

	$accion="modificar";

	}else if($accion=="limpiar"){

	$id=''; $numeroconcepto=''; $descuento=''; $cargocombustible=''; $iva=''; $ivaretenido=''; $diaspermitidos=''; $accion="";

		$sql=mysql_query("SELECT * FROM configuradorgeneral",$link);

		$row=mysql_fetch_array($sql);

$id=$row['id']; $numeroconcepto=$row['numeroconcepto']; $descuento=$row['descuento']; $cargocombustible=$row['cargocombustible']; $iva=$row['iva']; $ivaretenido=$row['ivaretenido']; $diaspermitidos=$row['diaspermitidos']; $prima=$row['prima']; $cantidadvalordeclarado=$row['cantidadvalordeclarado']; $ajustarvalordeclarado=$row['ajustarvalordeclarado'];		

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

	document.getElementById('numeroconcepto').value=="";

		document.getElementById('descuento').value=="";

			document.getElementById('cargocombustible').value=="";

				document.getElementById('iva').value=="";

					document.getElementById('ivaretenido').value=="";

						document.getElementById('diaspermitidos').value=="";

							document.getElementById('accion').value=="limpiar";

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

            /*ACA ESTA EL CAMBIO*/

            if (frm.elements[i+1].disabled ==true )    

                tabular(e,frm.elements[i+1]);

            else frm.elements[i+1].focus();

            return false;

} 

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Configurador de Tiempos Entrega</title>

<script type="text/javascript" src="js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="js/abrir-ventana-alertas.js"></script>

<link href="css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="css/style1.css" rel="stylesheet" type="text/css">

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

            <td>&nbsp;</td>

          </tr>

      </table></td>

    </tr>

  </table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR DE TIEMPOS DE ESTREGAS';

</script>

</html>

<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//}

?>