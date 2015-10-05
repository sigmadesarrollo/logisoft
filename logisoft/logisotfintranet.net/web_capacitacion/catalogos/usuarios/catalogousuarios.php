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

	$usuario=$_SESSION[NOMBREUSUARIO];

	

	$accion=$_POST['accion']; $nombre=$_POST['nombre']; $departamento=$_POST['departamento']; $puesto=$_POST['puesto']; $movil=$_POST['movil']; $oficina=$_POST['oficina']; $localizador=$_POST['localizador']; $email=$_POST['email']; $telefono=$_POST['telefono']; $calle=$_POST['calle']; $colonia=$_POST['colonia']; $ciudad=$_POST['ciudad']; $cp=$_POST['cp']; $estado=$_POST['estado']; $sesion=$_POST['sesion']; $password=$_POST['password'];

	

	if($accion=="guardar"){

		$sqlins=mysql_query("INSERT INTO catalogousuario (id, nombre, departamento, puesto, movil, oficina, localizador, email, telefono, calle, colonia, ciudad, cp, estado, sesion, password,usuario,fecha) VALUES ('null', '$nombre', '$departamento', '$puesto', '$movil', '$oficina', '$localizador', '$email', '$telefono', '$calle', '$colonia', '$ciudad', '$cp', '$estado', '$sesion', '$password','$usuario', current_timestamp())",$link);

		$codigo=mysql_insert_id();

		$mensaje="Los datos han sido guardados correctamente";

		$accion="modificar";

	}else if($accion=="modificar"){

		$sqlupd=mysql_query("UPDATE SET catalogousuario nombre='$nombre', departamento='$departamento', puesto='$puesto', movil='$movil', oficina='$oficina', localizador='$localizador', email='$email', telefono='$telefono', calle='$calle', colonia='$colonia', ciudad='$ciudad', cp='$cp', estado='$estado', sesion='$sesion', password='$password', usuario='$usuario', fecha=current_timestamp() WHERE id='$codigo'",$link);

		$mensaje="Los cambios han sido guardados correctamente";

	}

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script>

function validar(){

	if(document.getElementById('nombre').value==""){

		alerta('Debe Capturar Nombre','¡Atención!','nombre');

	}else if(document.getElementById('departamento').value==""){

		alerta('Debe Capturar Departamento','¡Atención!','departamento');

	}else if(document.getElementById('puesto').value==""){

		alerta('Debe Capturar Puesto','¡Atención!','puesto');

	}else if(document.getElementById('sesion').value==""){

		alerta('Debe Capturar Nombre de Sesión','¡Atención!','sesion');

	}else if(document.getElementById('password').value==""){

		alerta('Debe Capturar Contraseña','¡Atención!','password');	

	}else{

		if(document.getElementById('accion').value==""){

			document.getElementById('accion').value="grabar";

			document.form1.submit();

		}else{

			document.getElementById('accion').value="modificar";

			document.form1.submit();

		}	

	}

}

</script>

<script type="text/javascript" src="js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="js/abrir-ventana-alertas.js"></script>

<link href="css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="css/style1.css" rel="stylesheet" type="text/css">

	<link href="examples.css" rel="stylesheet" />

    <link href="tabs.css" rel="stylesheet" />    

    <script src="ext-core-debug.js"></script>

    <script src="tabs.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Cat&aacute;logo de Usuarios</title>

<link href="FondoTabla.css" rel="stylesheet" type="text/css" />

<link href="Tablas.css" rel="stylesheet" type="text/css" />

</head>

<body>

<form name="form1" method="post" action="">

	<table width="100%" border="0">

    <tr>

      <td>

<div class="tab_container">

        <div class="tab-buttons-panel">

            <ul>

                <li id="tab1" class="tab-show">

                    <span>General</span>

                </li>        

                <li id="tab2">

                    <span>Personal</span> 

                </li>        

                <li id="tab3">

                    <span>Sesión</span>

                </li>

				<li id="tab4">

                    <span>Permisos</span>

                </li>

            </ul>

        </div>

        <div id="content1" class="tab-content tab-content-show">

            <div class="tab-content-panel-border">

                <div class="tab-content-panel">

                     <table width="349" border="0" cellspacing="0" cellpadding="0">

					  <tr>

						<td class="Tablas">Nombre:</td>

					    <td><input name="nombre" type="text" class="Tablas" id="nombre" value="<?=$nombre ?>" size="40"></td>

				      </tr>

					  <tr>

						<td width="82" class="Tablas">Departamento:</td>

						<td><label>

						  <input name="departamento" type="text" class="Tablas" id="departamento" value="<?=$departamento ?>" size="40">

						</label></td>

					  </tr>

					  <tr>

						<td class="Tablas">Puesto:</td>

						<td><input name="puesto" type="text" class="Tablas" id="puesto" value="<?=$puesto ?>" size="40"></td>

					  </tr>

					  <tr>

						<td colspan="2">Tel&eacute;fonos</td>

					  </tr>

					  <tr>

					    <td class="Tablas">Movil:</td>

					    <td><input name="movil" type="text" class="Tablas" id="movil" value="<?=$movil ?>"></td>

				      </tr>

					  <tr>

					    <td class="Tablas">Oficina:</td>

					    <td><input name="oficina" type="text" class="Tablas" id="oficina" value="<?=$oficina ?>"></td>

				      </tr>

					  <tr>

					    <td class="Tablas">Localizador:</td>

					    <td><input name="localizador" type="text" class="Tablas" id="localizador" value="<?=$localizador ?>"></td>

				      </tr>

					  <tr>

					    <td colspan="2">Mensajeria</td>

				      </tr>

					  <tr>

					    <td class="Tablas">E-mail:</td>

					    <td><input name="email" type="text" class="Tablas" id="email" value="<?=$email ?>"></td>

				      </tr>

					  <tr>

						<td>&nbsp;</td>

						<td>&nbsp;</td>

					  </tr>

				  </table>

                </div>

            </div>

        </div>        

        <div id="content2" class="tab-content">

            <div class="tab-content-panel-border">

                <div class="tab-content-panel">				  

				  <table width="349" border="0" cellspacing="0" cellpadding="0">

					  <tr>

						<td width="82" class="Tablas">Tel&eacute;fono:</td>

					    <td><input name="telefonodir" type="text" class="Tablas" id="telefonodir" value="<?=$telefonodir ?>" size="40"></td>

				      </tr>

					  

					  <tr>

						<td colspan="2">Direcci&oacute;n</td>

					  </tr>

					  <tr>

					    <td class="Tablas">Calle:</td>

					    <td><input name="calle" type="text" class="Tablas" id="calle" value="<?=$calle ?>" size="40"></td>

				      </tr>

					  <tr>

					    <td class="Tablas">Fracc/Colonia:</td>

					    <td><input name="colonia" type="text" class="Tablas" id="colonia" value="<?=$colonia ?>" size="40"></td>

				      </tr>

					  <tr>

					    <td class="Tablas">Ciudad:</td>

					    <td><input name="ciudad" type="text" class="Tablas" id="ciudad" value="<?=$ciudad ?>" size="40"></td>

				      </tr>

					  <tr>

					    <td class="Tablas">C&oacute;digo Postal: </td>

					    <td><input name="cp" type="text" class="Tablas" id="cp" value="<?=$cp ?>" size="10"></td>

				      </tr>

					  <tr>

					    <td class="Tablas">Estado:</td>

					    <td><input name="estado" type="text" class="Tablas" id="estado" value="<?=$estado ?>" size="40"></td>

				      </tr>

					  

					  <tr>

						<td>&nbsp;</td>

						<td>&nbsp;</td>

					  </tr>

				  </table>

                </div>

            </div>

        </div>        

        <div id="content3" class="tab-content">

            <div class="tab-content-panel-border">

                <div class="tab-content-panel">

                    <table width="349" border="0" cellspacing="0" cellpadding="0">

					  <tr>

						<td class="Tablas">Nombre Sesi&oacute;n:</td>

					    <td><input name="sesion" type="text" class="Tablas" id="sesion" value="<?=$sesion ?>" size="40"></td>

				      </tr>

					  <tr>

						<td width="82" class="Tablas">Contrase&ntilde;a:</td>

						<td><label>

						  <input name="password" type="text" class="Tablas" id="password" value="<?=$password ?>" size="40">

						</label></td>

					  </tr>

					  <tr>

						<td class="Tablas">&nbsp;</td>

						<td>&nbsp;</td>

					  </tr>

					  <tr>

						<td colspan="2">&nbsp;</td>

					  </tr>

					  <tr>

					    <td class="Tablas">&nbsp;</td>

					    <td>&nbsp;</td>

					  </tr>

					  <tr>

					    <td class="Tablas">&nbsp;</td>

					    <td>&nbsp;</td>

					  </tr>

					  <tr>

					    <td class="Tablas">&nbsp;</td>

					    <td>&nbsp;</td>

					  </tr>

					  <tr>

					    <td colspan="2">&nbsp;</td>

				      </tr>

					  <tr>

					    <td class="Tablas">&nbsp;</td>

					    <td>&nbsp;</td>

					  </tr>

					  <tr>

						<td>&nbsp;</td>

						<td>&nbsp;</td>

					  </tr>

				  </table>

                </div>

            </div>

        </div> 

		<div id="content4" class="tab-content">

            <div class="tab-content-panel-border">

                <div class="tab-content-panel">

                    <b>Types of Animals</b>

                    <ul>

                        <li>Tigers</li>

                        <li>Elephants</li>

                        <li>Fish</li>

                        <li>Birds</li>

                    </ul>

                </div>

            </div>

        </div> 

		<table width="300" border="0" cellpadding="0" cellspacing="0">

  <tr>

    <td><img src="../../img/Boton_Guardar.gif" onClick="validar();"></td>

  </tr>

</table>

  </div>

  </td>

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