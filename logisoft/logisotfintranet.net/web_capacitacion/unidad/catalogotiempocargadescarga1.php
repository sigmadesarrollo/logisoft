<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if ( isset ( $_SESSION['gvalidar'] )!=100 ) {

	 echo "<script language='javascript' type='text/javascript'>

						document.location.href='../../index.php';

					</script>";

	}else{ */

	include('../Conectar.php');	

	$link=Conectarse('webpmm');

	//cachar variables aqui

	$accion=$_POST['accion'];

	$unidad=$_POST['unidad'];

	$descripcion=$_POST['descripcion']; 

	$ticarga=$_POST['ticarga'];

	$tidescarga=$_POST['tidescarga'];

	$usuario=$_POST['usuario'];

	

	

if($accion=="grabar"){

	$sql2="SELECT * FROM catalogotipounidad WHERE codigo='$unidad'";

	if (mysql_num_rows(mysql_query($sql2,$link))){

			$sql="SELECT * FROM catalogocargadescarga WHERE unidad='$unidad'";

			if (!mysql_num_rows(mysql_query($sql,$link))){

				$sqlins="INSERT INTO catalogocargadescarga (unidad, tcarga, tdescarga, usuario, fecha)VALUES('$unidad', '$ticarga', '$tidescarga', '$usuario', current_date())";

				$res=mysql_query($sqlins,$link);

				$msg="Los datos han sido guardados correctamente.";

			}else{

				$sqlupd="UPDATE catalogocargadescarga SET  tcarga='$ticarga', tdescarga='$tidescarga', usuario='$usuario', fecha=current_date() where unidad='$unidad'";

				$res=mysql_query($sqlupd,$link);		

				$msg="Los cambios han sido guardados correctamente.";

		}

	}else{$msg="La unidad no existe,Capture una unidad existente.";}

}	

	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="select.js"></script>

<script language="JavaScript" type="text/javascript">

// Genera Un popUP

function popUp(URL) {

day = new Date();

id = day.getTime();

eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=530,height=380,left = 470,top = 200');");

} 



// Funcion validar caja numero

var nav4 = window.Event ? true : false;

function Numeros(evt){ 

// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 

var key = nav4 ? evt.which : evt.keyCode; 

return (key <= 13 || (key >= 48 && key <= 57));

}



//Funcion par validar

function validar(){

if (document.getElementById('ticarga').value==""){

	document.getElementById('ticarga').focus();

	alert('Debe capturar tiempo de carga');	

	}else if (document.getElementById('tidescarga').value==""){

	document.getElementById('tidescarga').focus();

	alert('Debe capturar tiempo de carga');	

	}else{	

			document.getElementById('accion').value = "grabar";

			document.form1.submit();

	}

	

}

//Funcion Optener

function obtener(unidad,descripcion){

document.getElementById('unidad').value=unidad;

document.getElementById('descripcion').value=descripcion;

ObtenerCarga(unidad);

}



//Funcion Limpiar

function Limpiar(){

	document.getElementById('unidad').value="";

	document.getElementById('descripcion').value="";

	document.getElementById('ticarga').value="";

	document.getElementById('tidescarga').value="";

	document.getElementById('accion').value="";

}





</script>

<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">

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

<body >

<form name="form1" method="post" action="">

<table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td background="../img/bazul1.jpg" width=5 height=54></td>

        <td background="../img/bazul2.jpg" width=150><span class="Estilo3">CATALOGO CARGA Y DESCARGA </span></td>

        <td background="../img/bazul3.jpg" width=59></td>

        <td background="../img/bazul4.jpg">&nbsp;</td>

      </tr>

  </table>

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">

 <tr>

   <td><br></td>

 </tr>

 <tr>

      <td height="50"><table width="261" height="131" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

        <tr>

          <td width="257" height="22" class="FondoTabla">Datos Generales </td>

        </tr>

        <tr>

          <td height="107"><table width="257" height="105" border="0" align="center">

              <tr>

                <td width="42" height="27"><strong class="Tablas">&nbsp;Unidad:</strong></td>

                <td width="205" colspan="3"><label>

                

                </label>

                 <input name="unidad" type="text" id="unidad" tabindex="1" value="<?=$unidad ?>" size="10" maxlength="4" onBlur="ObtenerCarga(document.getElementById('unidad').value)" style="font:tahoma; font-size:9px" >   <img src="../img/Buscar_24.gif" alt="t" align="absbottom" onClick="javascript:popUp('buscarcatalogotipounidades.php')" >

                  <input name="accion" type="hidden" value="<?=$accion ?>" /></td>

              </tr>

              <tr>

                <td height="46" colspan="4"><div id="txtHint" ><table width="249" border="0">

				<tr>				

				<td width="65"><strong class="Tablas">Descripcion:</strong></td>

                <td colspan="3"><label>

                  <input name="descripcion" type="text" id="descripcion" tabindex="2" value="<?=$descripcion ?>" size="43" readonly="readonly" style="background-color: #FFFF99; font-size:9px; font:tahoma">

                </label></td>

              </tr>

              <tr>

                <td><strong class="Tablas">T. Carga: </strong></td>

                <td width="92"><input name="ticarga" type="text" id="ticarga" onKeyPress="return Numeros(event)"  onKeyDown="return tabular(event,this)" value="<?=$ticarga ?>" size="10" tabindex="3" style="font:tahoma; font-size:9px"/></td>

                <td width="94"><strong class="Tablas">T. Descarga:</strong></td>

                <td width="68"><input name="tidescarga" type="text" id="tidescarga"    onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this)" value="<?=$tidescarga ?>" size="10" tabindex="4"/ style="font:tahoma; font-size:9px"></td>

				</tr>

				</table>

                </div></td>

                </tr>

              

              <tr>

                <td height="30" colspan="4"><table width="141" border="0" align="right">

                    <tr>

                      <td width="67"><label>

                        <input name="Guardar" type="image" id="Guardar"   onClick="validar();" src="../img/Boton_Guardar.gif" alt="Guardar"  width="70" height="20" tabindex="5" />

                      </label></td>

                      <td width="64"><input name="image" type="image" title="Nuevo" onClick="Limpiar();" src="../img/Boton_Nuevo.gif" alt="g" width="70" height="20" /></td>

                    </tr>

                  </table></td>

                </tr>

          </table>

          </td>

        </tr>

      </table>

        <p><label></label>

        </p>

        

      </td>

    </tr>

  </table>   

</form>

</body>

</html>

<? //} ?>

<?

if($msg!=""){

echo "<script language='javascript' type='text/javascript'>alert('$msg'); </script>";



}

?>

