<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if(isset($_SESSION['gvalidar'] )!=100){ echo "<script language='javascript' type='text/javascript'>document.location.href='../../index.php';</script>";

	}else{ */

		include('../Conectar.php');	

		$link=Conectarse('webpmm');

	$usuario=$_SESSION[NOMBREUSUARIO];

	$accion=$_POST['accion'];	

	$codigo=$_POST['codigo'];

	$codigo=$_POST['codigo'];

	$descripcion=$_POST['descripcion'];

	 	

	//Opcion Grabar

	if($accion=="grabar"){	

		$sql="select * from catalogotipounidad where codigo='$codigo'";

		if (!mysql_num_rows(mysql_query($sql,$link))){		

			$sqlins="INSERT INTO catalogotipounidad (id, codigo, usuario, descripcion, fecha)VALUES('null', '$codigo','$usuario','$descripcion', current_date())";

			$res=mysql_query($sqlins,$link);

			echo "<script language='javascript' type='text/javascript'>alert('Los datos han sido guardados correctamente'); </script>";

			$msg="Los datos han sido guardados correctamente";

		}else{

		// Opcion Modificar

			$sqlupd="UPDATE catalogotipounidad SET descripcion='$descripcion',usuario='$usuario',fecha=current_date() where codigo='$codigo'";

			$res=mysql_query($sqlupd,$link);

			$msg="Los cambios han sido actualizados correctamente.";



		}

		$accion="";

		$codigo="";

		$descripcion="";

		//Opcion Limpiar	

	}

?>

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<title>Cat&aacute;logo Tipo Unidades </title>

<script type="text/javascript" src="js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="js/abrir-ventana-fija.js"></script>

<link href="css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="css/style.css" rel="stylesheet" type="text/css">







<script language="JavaScript" type="text/javascript">

// Genera Un popUP

function popUp(URL) {

day = new Date();

id = day.getTime();

eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=530,height=380,left = 470,top = 200');");

}



//Funcion Limpiar

function Limpiar(){

	document.getElementById('codigo').value="";

	document.getElementById('descripcion').value="";

	document.getElementById('accion').value = "limpiar";

	document.form1.submit();

}



//Funcion par validar

function validar(){

if(document.getElementById('codigo').value==""){

			document.getElementById('codigo').focus();

			alert('Debe capturar Código');

	}else if(document.getElementById('descripcion').value==""){

			document.getElementById('descripcion').focus();

			alert('Debe capturar Descripción');			

	}else{

			document.getElementById('accion').value = "grabar";

			document.form1.submit();

			//document.getElementById('descripcion').value=="";

	}

}

function obtener(id,descripcion){

	document.getElementById('codigo').value=id;

	document.getElementById('descripcion').value=descripcion;

	document.getElementById('descripcion').focus();

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

function tabular(e,obj) { 

  tecla=(document.all) ? e.keyCode : e.which; 

  if(tecla!=13) return; 

  frm=obj.form; 

  for(i=0;i<frm.elements.length;i++) 

    if(frm.elements[i]==obj) { 

      if (i==frm.elements.length-1) i=-1; 

      break } 

  frm.elements[i+1].focus(); 

  return false; 

}



// Funcion validar caja numero

var nav4 = window.Event ? true : false;

function Numeros(evt){ 

// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 

var key = nav4 ? evt.which : evt.keyCode; 

return (key <= 13 || (key >= 48 && key <= 57));

}

</script>

<script src="select.js"></script> 

<link href="../css/Tablas.css" rel="stylesheet" type="text/css">

<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">

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

</style>

</head>

<body onLoad="document.getElementById('codigo').focus()">

<form name="form1" method="post" action=""  >

 <table width="105%" border="0" align="left" cellpadding="0" cellspacing="0">

 <tr>

   <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td background="../img/bazul1.jpg" width=5 height=54></td>

        <td width="150" background="../img/bazul2.jpg" class="style1 Estilo1">CATALOGO TIPO UNIDAD </td>

        <td background="../img/bazul3.jpg" width=59></td>

        <td background="../img/bazul4.jpg">&nbsp;</td>

      </tr>

    </table><br></td>

 </tr>

 <tr>

              <td height="50">

			  <!-- Tabla -->

			  <table width="35%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

                <tr>

                  <td height="13"  bordercolor="#016193" class="FondoTabla">Datos Generales</td>

                </tr>

                <tr>

                  <td height="15" >

				  <!-- aki mero -->

				  <table width="268" height="77" border="0" cellpadding="0">

                    <tr>

                      <td width="61" height="23"><strong class="Tablas">C&oacute;digo:</strong></td>

                      <td width="201"><input name="codigo" type="text" id="codigo" onKeyPress="return tabular(event,this)"  size="10" value="<?= $codigo ?>"  maxlength="4" onBlur="CargaTipoUnidad(document.getElementById('codigo').value)" style="font-size:9px; font:tahoma" >

                      <img src="../img/Buscar_24.gif" style="cursor:pointer" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('buscarcatalogotipounidades.php', 550, 450, 'ventana', 'Catalogo Tipo Unidad')" ></td>

                    </tr>

                    <tr>

                      <td height="18"><span class="Tablas"><strong>Descripci&oacute;n</strong></span><strong class="Tablas">:</strong></td>

                      <td><div id="txtHint"><input name="descripcion" type="text"  id="descripcion" onKeyPress="return tabular(event,this)" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" size="50" value="<?= $descripcion ?>" style="font-size:9px; font:tahoma"></div></td>

                    </tr>

                    <tr>

                      <td height="28"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>

                      <td><table width="141" border="0" align="right">

                          <tr>

                            <td width="67"><img src="../img/Boton_Guardar.gif" style="cursor:pointer" onClick="validar();" width="70" height="20"></td>

                            <td width="64"><img src="../img/Boton_Nuevo.gif" style="cursor:pointer" onClick="Limpiar();" width="70" height="20"></td>

                          </tr>

                      </table></td>

                    </tr>

                  </table></td>

                </tr>

              </table>

			  <!-- termina tabla 1 -->

	  </td>

    </tr>

          </table>

</form>

</body>

<? //} ?>

<?

if($msg!=""){

echo "<script language='javascript' type='text/javascript'>alert('$msg'); </script>";

}

?>