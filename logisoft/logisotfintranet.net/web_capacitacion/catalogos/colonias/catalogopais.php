<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

/*if ( isset ( $_SESSION['gvalidar'] )!=100 ){

	 echo "<script language='javascript' type='text/javascript'>

						document.location.href='../../../index.php';

					</script>";

	}else{*/

		include('../../Conectar.php');	

		$link=Conectarse('webpmm');

$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion'];  $usuario=$_SESSION[NOMBREUSUARIO]; $defecto=$_POST['defecto']; $defaul=$_POST['defaul'];



 if($accion==""){

	$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogopais",$link);

	$row=mysql_fetch_array($sql);

	$codigo=$row[0];

 }

	if($accion=="grabar"){		

		$sqlins=mysql_query("INSERT INTO catalogopais (id, defaul, descripcion, usuario, fecha)VALUES('null', '$defecto', UCASE('$descripcion'), '$usuario', current_timestamp())",$link);		

		$codigo=mysql_insert_id();

		$mensaje = 'Los datos han sido guardados correctamente';

		$accion="modificar";

	}else if($accion=="modificar"){

		$sqlupd=mysql_query("UPDATE catalogopais SET defaul='$defecto', descripcion=UCASE('$descripcion'), usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'",$link);

		$mensaje = 'Los cambios han sido guardados correctamente';	

	}else if($accion=="limpiar"){

		$defecto="";

		$descripcion="";

		$codigo="";

		$accion="";

		$defaul="";

		$usuario=$_SESSION[NOMBREUSUARIO];

	$sql=mysql_query("SELECT ifnull(max(id),0)+1 As id FROM catalogopais",$link);

	$row=mysql_fetch_array($sql);

	$codigo=$row[0];

	}

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/shortcut.js"></script>

<script src="../../javascript/ajax.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script src="select.js"></script>

<script language="JavaScript" type="text/javascript">

function limpiar(){

	document.getElementById('descripcion').value="";

	document.getElementById('defaul').value="";

	document.getElementById('accion').value = "limpiar";

	document.form1.submit();

}

function validar(){

	if(document.getElementById('defaul').value==1){

		alerta('Ya existe un País por Defecto', '¡Atención!','descripcion');

	}else if(document.getElementById('descripcion').value==""){

			alerta('Debe capturar Descripción', '¡Atención!','descripcion');			

	}else{

			if(document.getElementById('accion').value==""){

			document.getElementById('accion').value = "grabar";

				document.form1.submit();

			}else if(document.getElementById('accion').value="modificar"){

				document.form1.submit();

			}

	}

}

function obtener(id,descripcion,defecto){

	document.getElementById('codigo').value=id;	

	if(defecto==1){

	document.form1.defecto.checked=true;

	}

	document.getElementById('descripcion').value=descripcion;

	document.getElementById('descripcion').focus();

	document.getElementById('accion').value="modificar";

}

function ConsultarDefault(){

	if(document.form1.defecto.checked==true){

	consulta("mostrarDefault","consultas.php?tipo=default");

	}else{

		document.getElementById('defaul').value="";

	}

}

function mostrarDefault(datos){

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		var u = document.all;		

		

		if(con>0){			

			u.defaul.value = con;

		}else{

			u.defaul.value = con;

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

function foco(nombrecaja){

	if(nombrecaja=="codigo"){

		document.getElementById('oculto').value="1";

	}

}

shortcut.add("Ctrl+b",function() {

	if(document.form1.oculto.value=="1"){

abrirVentanaFija('buscar.php?tipo=pais', 550, 450, 'ventana', 'Busqueda')

	}

});

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Cat&aacute;logo Pa&iacute;s</title>

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

<link href="FondoTabla.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">

<link href="Tablas.css" rel="stylesheet" type="text/css">

</head>



<body onLoad="document.form1.descripcion.focus()">

<form name="form1" method="post" action="">

  <table width="100%" border="0">

    <tr>

      <td><table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="563" class="FondoTabla">CAT&Aacute;LOGO PA&Iacute;S</td>

          </tr>

          <tr>

            <td><table width="301" border="0" align="center">

              <tr>

                <td width="85" class="Tablas"><strong>C&oacute;digo:</strong></td>

                <td width="206" class="Tablas"><label>

                  <input name="codigo" onFocus="foco(this.name)" onBlur="document.getElementById('oculto').value=''" class="Tablas" type="text" id="codigo" size="10" value="<?= $codigo ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="">

                  &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onClick="abrirVentanaFija('buscar.php?tipo=pais', 600, 500, 'ventana', 'Busqueda')">

                  &nbsp;&nbsp;<input name="defecto" onClick="ConsultarDefault()" type="checkbox" id="defecto" value="SI"<? if($defecto=="1"){ echo 'checked';} ?>> 

                  Por Defecto

</label></td>

              </tr>

              <tr>

                <td class="Tablas">Descripci&oacute;n:</td>

                <td><input name="descripcion" class="Tablas" type="text" id="descripcion" onBlur="trim(document.getElementById('descripcion').value,'descripcion');" onKeyPress="return tabular(event,this)" size="50" value="<?= $descripcion ?>" style="text-transform:uppercase;font:tahoma; font-size:9px"></td>

              </tr>

              

              <tr>

                <td height="32"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>">

                  <div id="txtHint">

                    <input name="defaul" type="hidden" id="defaul" value="<?=$defaul ?>">

                  </div></td>

                <td><input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>">

                  <table width="141" border="0" align="right">

                  <tr>

                    <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onClick="validar();" style="cursor:pointer" ></td>

                    <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onClick="confirmar('Perder&aacute; la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" style="cursor:pointer" ></td>

                  </tr>

                </table></td>

              </tr>

              <tr>

               

                </tr>

            </table></td>

          </tr>

      </table>

      </td>

    </tr>

  </table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'CATÁLOGO PAÍS';

</script>

</html>



<?

if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//	}

?>