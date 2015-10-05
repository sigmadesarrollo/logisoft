<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){

	 echo "<script language='javascript' type='text/javascript'>

						document.location.href='http://172.16.40.39/curso/pmm/index.php';

					</script>";

	}else{*/

		//conecta ala base de datos

		include('../Conectar.php');	

		$link=Conectarse('pmm');	   	

	   	$usuario=$_SESSION[NOMBREUSUARIO];

	   	$accion=$_POST['accion'];  $unidad=$_POST['unidad']; $economico=$_POST['economico']; $cvolumen=$_POST['cvolumen']; $ckilos=$_POST['ckilos']; $ntarjeta=$_POST['ntarjeta'];



		//Opcion Grabar

	if($accion=="grabar"){

	 	$sql="select * from catalogounidad where neconomico='$economico'";

		if (!mysql_num_rows(mysql_query($sql,$link))){		

		$sqlins="INSERT INTO catalogounidad(id, tipounidad, neconomico, cvolumen, ckilos, ntarjeta, usuario, fecha)VALUES('null','$unidad','$economico', '$cvolumen','$ckilos','$circulacion','$usuario', current_date())";

		$res=mysql_query($sqlins,$link);

			echo "<script language='javascript' type='text/javascript'>alert('Los datos han sido guardados correctamente');</script>";		

		}else {

		

			$sqlupd="UPDATE catalogounidad SET tipounidad='$unidad', neconomico='$economico', cvolumen='$cvolumen', ckilos='$ckilos', ntarjeta='$circulacion', usuario='$usuario', fecha=current_date() where neconomico='$economico'";

			$res=mysql_query($sqlupd,$link);

			echo "<script language='javascript' type='text/javascript'>alert('Los cambios han sido actualizados correctamente');</script>";		

		}

	}



?>

<html>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<title>Cat&aacute;logo Unidades</title>

<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript">

// Genera Un popUP

function popUp(URL) {

day = new Date();

id = day.getTime();

eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=530,height=380,left = 470,top = 200');");

}



var nav4 = window.Event ? true : false;

function Numeros(evt){ 

	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 

	var key = nav4 ? evt.which : evt.keyCode; 

	return (key <= 13 || (key >= 48 && key <= 57));

}



//Funcion par validar

function validar(){

 if(document.getElementById('economico').value==""){

			document.getElementById('descripcion').focus();

			alert('Debe capturar todos los datos');

	}else{

			alert('entramos aki');

			document.getElementById('accion').value = "grabar";

			document.form1.submit();

	}

}

function obtener(unidad,descripcion){

	document.getElementById('unidad').value=unidad;

	document.getElementById('descripcion').value=descripcion;

	

	

	

}

function Limpiar(){

	document.getElementById('unidad').value="";

	document.getElementById('descripcion').value="";

	document.getElementById('economico').value="";

	document.getElementById('cvolumen').value="";

	document.getElementById('ckilos').value="";

	document.getElementById('circulacion').value="";

	document.getElementById('unidad').focus();	

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





function feconomico(){

unidad=document.getElementById('unidad').value;

descripcion=document.getElementById('descripcion').value;

economico=document.getElementById('economico').value;

if(economico!=""){

ObtenerCarga(unidad,descripcion,economico);

}



}

</script>



<script src="selectcu.js"></script>

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

<form name="form1" method="post" action="" >

<table width="105%" border="0" align="left" cellpadding="0" cellspacing="0">

 <tr>

   <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td background="../../img/bazul1.jpg" width=5 height=54></td>

        <td width=150 background="../../img/bazul2.jpg" class="style1 Estilo1"> Catalogo Unidades</td>

        <td background="../../img/bazul3.jpg" width=59></td>

        <td background="../../img/bazul4.jpg">&nbsp;</td>

      </tr>

    </table><br></td>

 </tr>

 <tr>

              <td height="50">

			  <!-- Tabla -->

			  <table width="400" height="277" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

                <tr>

                  <td width="396" height="21" bordercolor="#016193" class="FondoTabla">Datos Generales</td>

                </tr>

                <tr>

                  <td height="254" ><div id="txtHint"><table width="396" height="238" border="0" align="center">

                    <tr>

                      

                    <td width="84" height="27"><span class="Tablas"><strong>No. 

                      Economico:</strong></span></td>

                      <td colspan="3">

<input name="economico" type="text" id="economico"  onBlur="feconomico();"  value="<?=$economico ?>" size="50"  ></td>

                    </tr>

                    <tr>

                      <td height="26"><strong>Descripci&oacute;n:</strong></td>

                      <td colspan="3"><input name="descripcion" type="text" id="descripcion" style="background-color: #FFFF99;" value="<?=$descripcion ?>" size="50" readonly="readonly"></td>

                    </tr>

                    <tr>

                      

                    <td height="42"><span class="Tablas"><strong>T. Unidad:</strong></span></td>

                    <td colspan="3"><input name="unidad" type="text" id="unidad" style="background-color: #FFFF99;" value="<?= $unidad ?>" size="10" maxlength="4" readonly="readonly"> 

                      <img name="image" type="image" onClick="javascript:popUp('buscarcatalogotipounidades.php')" src="../../img/Buscar_24.gif" alt="buscar" align="absbottom" width="24" height="23"></td>

                    </tr>

                    <tr>

                      <td height="42"><span class="Tablas"><strong>Tarj. Circulaci&oacute;n:</strong></span></td>

                      <td colspan="3"><input name="circulacion" type="text" id="circulacion" value="<?=$circulacion ?>" size="50" onKeyPress="return tabular(event,this)" ></td>

                    </tr>

                    

                    <tr>

                      <td height="42"><span class="Tablas"><strong>Cap. Volumen:</strong></span></td>

                      <td width="90"><input name="cvolumen" type="text" id="cvolumen" onKeyPress="return tabular(event,this)" value="<?=$cvolumen ?>" size="18"   ></td>

                      <td width="64"><strong class="Tablas">Cap. Kilos:&nbsp;</strong></td>

                      <td width="140"><strong class="Tablas">

                        <input name="ckilos" type="text" id="ckilos" onKeyPress="return tabular(event,this)"  onKeyDown="return Numeros(event)" value="<?=$ckilos ?>" size="18" >

                      </strong></td>

                    </tr>

                    <tr>

                      <td height="34" colspan="4"><table width="190" border="0" align="right">

                        <tr>

                          <td width="90"><img name="image" type="image" title="Guardar" onClick="validar();" src="../../img/Boton_Guardar.gif" alt="guardar" width="90" height="24" /></td>

                          <td width="90"><img name="image" type="image" title="Nuevo" onClick="Limpiar();" src="../../img/Boton_Nuevo.gif" alt="nuevo" width="90" height="24" /></td>

                        </tr>

                      </table></td>

                    </tr>

                    <tr>

                      <td height="23"><span class="Tablas">

                        <input name="accion" type="hidden" id="accion" value="<?=$accion ?>">

                      </span></td>

                      <td colspan="3">&nbsp;</td>

                    </tr>

                  </table>

                  </div></td>

                </tr>

              </table>

			  

			  

			  <p>&nbsp;</p></td>

    </tr>

</table>   



  

  

  





<p>&nbsp;</p>

</form>





</body>

</html>

