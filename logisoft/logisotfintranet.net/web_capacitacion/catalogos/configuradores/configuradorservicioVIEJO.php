<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}/*

if(isset($_SESSION['gvalidar'])!=100){echo"<script language='javascript' type='text/javascript'>			document.location.href='../index.php';</script>";}else{*/

		include('../../Conectar.php');	

		$link=Conectarse('webpmm');

		$usuario=$_SESSION[NOMBREUSUARIO];

$id=$_POST['id']; $emplaye=$_POST['emplaye']; $acuserecibo=$_POST['acuserecibo']; $bolsaempaque=$_POST['bolsaempaque']; $cod=$_POST['cod']; $aviso=$_POST['aviso']; $costoextra=$_POST['costoextra']; $cada=$_POST['cada']; $accion=$_POST['accion'];

	if($accion==""){

		$sql=mysql_query("SELECT * FROM configuradorservicios",$link);

		if(mysql_num_rows($sql)>0){

		$row=mysql_fetch_array($sql);

$id=$row[0]; $emplaye=$row[1]; $costoextra=$row[2]; $acuserecibo=$row[3]; $bolsaempaque=$row[4]; $cod=$row[5]; $aviso=$row[6]; $cada=$row[7];		

		$accion="modificar";

		}

	}else if($accion=="grabar"){

		$sqlins=mysql_query("INSERT INTO configuradorservicios (id,emplaye,costoextra,acuserecibo,bolsaempaque,cod,aviso,cada,usuario,fecha) VALUES ('null','$emplaye','$costoextra','$acuserecibo','$bolsaempaque','$cod','$aviso','$cada','$usuario',current_timestamp())",$link);		

		$id=mysql_insert_id();

		$mensaje="Los datos han sido guardados correctamente";

		$accion="modificar";

	}else if($accion=="modificar"){

		$sqlupd=mysql_query("UPDATE configuradorservicios SET emplaye='$emplaye', costoextra='$costoextra', acuserecibo='$acuserecibo', bolsaempaque='$bolsaempaque', cod='$cod', aviso='$aviso',  cada='$cada', usuario='$usuario',fecha=current_timestamp() WHERE id='$id'",$link);		

		

	$mensaje="Los cambios han sido guardados correctamente";

	$accion="modificar";

	}else if($accion=="limpiar"){

	$id=''; $emplaye=''; $acuserecibo=''; $bolsaempaque=''; $cod=''; $aviso=''; $costoextra=''; $cada='';	

	$sql=mysql_query("SELECT * FROM configuradorservicios",$link);

		$row=mysql_fetch_array($sql);

$id=$row[0]; $emplaye=$row[1]; $acuserecibo=$row[2]; $bolsaempaque=$row[3]; $cod=$row[4]; $aviso=$row[5]; $costoextra=$row[6]; $cada=$row[7];

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

	if(document.getElementById('emplaye').value==""){

		alerta('Debe capturar Emplaye', '¡Atención!','emplaye');

	}else if(document.getElementById('emplaye').value<0){		

		alerta('No puede capturar Cantidades Negativas', '¡Atención!','emplaye');		

	}else if(document.getElementById('costoextra').value==""){

	alerta('Debe capturar Costo Extra', '¡Atención!','costoextra');

	}else if(document.getElementById('costoextra').value<0){

	alerta('No puede capturar Cantidades Negativas', '¡Atención!','costoextra');	

	}else if(document.getElementById('acuserecibo').value==""){

		alerta('Debe capturar Acuse Recibo', '¡Atención!','acuserecibo');

	}else if(document.getElementById('acuserecibo').value<0){

		alerta('No puede capturar Cantidades Negativas', '¡Atención!','acuserecibo');				

	}else if(document.getElementById('bolsaempaque').value==""){

		alerta('Debe capturar Bolsa Empaque', '¡Atención!','bolsaempaque');

	}else if(document.getElementById('bolsaempaque').value<0){

		alerta('No puede capturar Cantidades Negativas', '¡Atención!','bolsaempaque');		

	}else if(document.getElementById('cod').value==""){

		alerta('Debe capturar COD', '¡Atención!','cod');

	}else if(document.getElementById('cod').value<0){

		alerta('No puede capturar Cantidades Negativas', '¡Atención!','cod');		

	}else if(document.getElementById('aviso').value==""){

		alerta('Debe capturar Aviso Celular', '¡Atención!','aviso');

	}else if(document.getElementById('aviso').value<0){

		alerta('No puede capturar Cantidades Negativas', '¡Atención!','aviso');

	}else if(document.getElementById('cada').value==""){

		alerta('Debe capturar Por Cada', '¡Atención!','cada');	

	}else if(document.getElementById('cada').value<0){

		alerta('No puede capturar Cantidades Negativas', '¡Atención!','cada');		

	}else{

		if(document.getElementById('accion').value==""){

			document.getElementById('accion').value = "grabar";

			document.form1.submit();

		}else if(document.getElementById('accion').value="modificar"){

			document.form1.submit();

		}

	}

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

<title>Configurador Servicios</title>

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



<body onLoad="document.form1.emplaye.focus()">

<form id="form1" name="form1" method="post" action="">



  <table width="100%" border="0">

    <tr>

      <td><table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="563" class="FondoTabla">Datos Generales </td>

          </tr>

          

          <tr>

            <td><table width="430" border="0" align="center" cellpadding="0" cellspacing="0">

                <tr>

                  <td class="Tablas"><input name="id" type="hidden" id="id" value="<?=$id ?>"></td>

                  <td>&nbsp;</td>

                  <td class="Tablas">&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

                <tr>

                  <td width="90" class="Tablas">Emplaye:</td>

                  <td width="135"><input name="emplaye" type="text" id="emplaye" style="font-size:9px; font:tahoma" value="<?=$emplaye ?>" onBlur="trim(document.getElementById('emplaye').value,'emplaye');" onKeyPress="return tabular(event,this)" /></td>

                  <td width="94" class="Tablas">Costo KG Extra: </td>

                  <td width="111"><input name="costoextra" onKeyPress="return tabular(event,this)" style="font-size:9px; font:tahoma" type="text" id="costoextra" onBlur="trim(document.getElementById('costoextra').value,'costoextra');" value="<?=$costoextra ?>" /></td>

                </tr>

                <tr>

                  <td class="Tablas">Acuse Recibo: </td>

                  <td><input name="acuserecibo" onKeyPress="return tabular(event,this)" onBlur="trim(document.getElementById('acuserecibo').value,'acuserecibo');" style="font-size:9px; font:tahoma" type="text" id="acuserecibo" value="<?=$acuserecibo ?>" /></td>

                  <td colspan="2">&nbsp;</td>

                </tr>

                <tr>

                  <td class="Tablas">Bolsa Empaque: </td>

                  <td><input name="bolsaempaque" onKeyPress="return tabular(event,this)"  onBlur="trim(document.getElementById('bolsaempaque').value,'bolsaempaque');" style="font-size:9px; font:tahoma" type="text" id="bolsaempaque" value="<?=$bolsaempaque ?>" /></td>

                  <td colspan="2">&nbsp;</td>

                </tr>

                <tr>

                  <td class="Tablas">COD:</td>

                  <td><input name="cod" onBlur="trim(document.getElementById('cod').value,'cod');" onKeyPress="return tabular(event,this)" type="text" style="font-size:9px; font:tahoma" id="cod" value="<?=$cod ?>" /></td>

                  <td colspan="2">&nbsp;</td>

                </tr>

                <tr>

                  <td class="Tablas">Aviso Celular:</td>

                  <td><input name="aviso" onBlur="trim(document.getElementById('aviso').value,'aviso');" onKeyPress="return tabular(event,this)" type="text" style="font-size:9px; font:tahoma" id="aviso" value="<?=$aviso ?>" /></td>

                  <td class="Tablas">Por Cada: </td>

                  <td><input name="cada" onBlur="trim(document.getElementById('cada').value,'cada');" onKeyPress="return tabular(event,this)" type="text" style="font-size:9px; font:tahoma" id="cada" value="<?=$cada ?>" /></td>

                </tr>

                <tr>

                  <td colspan="4"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>

                </tr>

                <tr>

                  <td colspan="4"><table width="81" border="0" align="right" cellpadding="0" cellspacing="0">

                    <tr>

                      <td width="75"><img src="../../img/Boton_Guardar.gif" title="Guardar" width="70" height="20" style="cursor:pointer" onClick="validar();"></td>

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

	parent.frames[1].document.getElementById('titulo').innerHTML = 'CONFIGURADOR SERVICIOS';

</script>

</html>

<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";	

	}

//}

?>