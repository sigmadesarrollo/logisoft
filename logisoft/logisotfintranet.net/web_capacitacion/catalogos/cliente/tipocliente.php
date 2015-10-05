<?	session_start();

	/*if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}*/

		require_once('../../Conectar.php');	

		$link=Conectarse('webpmm');

	$accion=$_POST['accion']; $codigo=$_POST['codigo']; $descripcion=$_POST['descripcion']; $tipocliente=$_POST['tipocliente']; $usuario=$_SESSION[NOMBREUSUARIO];

	

 if($accion==""){

	$row = folio('catalogotipocliente','webpmm');

	$codigo=$row[0];

 }

	if($accion=="grabar"){		

		$sqlins="INSERT INTO catalogotipocliente (id, tipocliente, descripcion, usuario, fecha)VALUES('null', UCASE('$tipocliente'), UCASE('$descripcion'), '$usuario', current_timestamp())";

		$res=mysql_query($sqlins,$link);

		$codigo=mysql_insert_id();

		$mensaje = 'Los datos han sido guardados correctamente';

		$accion="modificar";

	}else if($accion=="modificar"){

		$sqlupd="UPDATE catalogotipocliente SET tipocliente=UCASE('$tipocliente'), descripcion=UCASE('$descripcion'), usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'";

		$res=mysql_query($sqlupd,$link);

		$mensaje = 'Los cambios han sido guardados correctamente';	

	}else if($accion=="limpiar"){

		$tipocliente="";

		$descripcion="";

		$codigo="";

		$msg="";

		$accion="";

		$usuario=$_SESSION[NOMBREUSUARIO];

		$row = folio('catalogotipocliente','webpmm');

		$codigo=$row[0];

	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../../javascript/shortcut.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script>

	function limpiar(){

	document.getElementById('tipocliente').value="";

	document.getElementById('descripcion').value="";

	document.getElementById('accion').value = "limpiar";

	document.form1.submit();

}

function validar(){

	 if(document.getElementById('tipocliente').value==""){

			alerta('Debe capturar Tipo Cliente', '¡Atención!','tipocliente');

			document.getElementById('tipocliente').focus();

	 }else if(document.getElementById('descripcion').value==""){

			alerta('Debe capturar Descripción', '¡Atención!','descripcion');

			document.getElementById('descripcion').focus();

	}else{

			if(document.getElementById('accion').value==""){

				document.getElementById('accion').value = "grabar";

				document.form1.submit();

			}else if(document.getElementById('accion').value=="modificar"){

				document.form1.submit();

			}

	}

}

function obtener(id,descripcion,tipocliente){

	document.getElementById('codigo').value=id;

	document.getElementById('tipocliente').value=tipocliente;

	document.getElementById('descripcion').value=descripcion;

	document.getElementById('tipocliente').focus();

	document.getElementById('accion').value="modificar";

}

function trim(cadena,caja)

{

	for(i=0;i<cadena.length;)

	{

		if(cadena.charAt(i)==" ")

			cadena=cadena.substring(i+1, cadena.length);

		else

			break;

	}	for(i=cadena.length-1; i>=0; i=cadena.length-1)

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

abrirVentanaFija('buscartipocliente.php', 550, 450, 'ventana', 'Busqueda')

	}

});

</script>

<link href="Tablas.css" rel="stylesheet" type="text/css">

<link href="FondoTabla.css" rel="stylesheet" type="text/css">

<link href="puntovta.css" rel="stylesheet" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

</head>



<body>

<form id="form1" name="form1" method="post" action="">

  <table width="312" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

      <td width="308" class="FondoTabla">CAT&Aacute;LOGO TIPO CLIENTE</td>

    </tr>

    <tr>

      <td><table width="301" border="0" align="center">

          <tr>

            <td width="85" class="Tablas"><strong>C&oacute;digo:</strong></td>

            <td width="206"><label>

              <input name="codigo" type="text" id="codigo" class="Tablas" size="10" value="<?= $codigo ?>" style=" font:tahoma; font-size:9px; background:#FFFF99" readonly="" onfocus="foco(this.name)" onblur="document.getElementById('oculto').value=''" />

              &nbsp;&nbsp; <img src="../../img/Buscar_24.gif" alt="Buscar" width="24" height="23" align="absbottom" style="cursor:pointer" onclick="abrirVentanaFija('buscartipocliente.php', 550, 480, 'ventana', 'Busqueda')" /></label></td>

          </tr>

          <tr>

            <td class="Tablas">Tipo Cliente: </td>

            <td><input name="tipocliente" type="text" id="tipocliente" onblur="trim(document.getElementById('tipocliente').value,'tipocliente');" onkeypress="return tabular(event,this)" size="50" class="Tablas" value="<?= $tipocliente ?>" style="text-transform:uppercase;font:tahoma; font-size:9px" /></td>

          </tr>

          <tr>

            <td valign="top" class="Tablas"><strong>Descripci&oacute;n:</strong></td>

            <td><label>

              <textarea name="descripcion" class="Tablas" rows="3" id="descripcion" onblur="trim(document.getElementById('descripcion').value,'descripcion');" style="width:225px; text-transform:uppercase"><?=$descripcion ?>

  </textarea>

            </label></td>

          </tr>

          <tr>

            <td height="32"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />

                <input name="oculto" type="hidden" id="oculto" value="<?=$oculto ?>" /></td>

            <td><table width="141" border="0" align="right">

                <tr>

                  <td width="67"><img src="../../img/Boton_Guardar.gif" alt="Guardar" title="Guardar" width="70" height="20" onclick="validar();" style="cursor:pointer"></td>

                  <td width="64"><img src="../../img/Boton_Nuevo.gif" alt="Nuevo" width="70" height="20" title="Nuevo" onclick="confirmar('Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', 'limpiar();', '')" style="cursor:pointer"></td>

                </tr>

            </table></td>

          </tr>

        </table>

          <center>

        </center></td>

    </tr>

  </table>

</form>

</body>

</html>

<? 

	if ($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

//}

?>