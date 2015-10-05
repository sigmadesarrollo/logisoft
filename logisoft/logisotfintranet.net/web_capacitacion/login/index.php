<? session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	include('Conectar.php');

	$link=Conectarse('pmm');	

if ($accion=="entrar"){	

	$sql = "SELECT * FROM usuarios WHERE Usuario='$usuario' and Contrasena='$password'";

	$rec = mysql_query($sql,$link);

	if (mysql_num_rows($rec)>0){

		$row=mysql_fetch_array($rec);

			$_SESSION['gvalidar']=100;

			$_SESSION[NOMBREUSUARIO]=$row['Nombre'];

	        echo "<script language='javascript' type='text/javascript'>

						document.location.href='menu.php';

					</script>";

			}else{

			echo'<script>alert("Datos Incorrectos");</script>';

	}

}

?> 

<html>

<script>

function validar(){



	if (document.getElementById('usuario').value==""){

			document.getElementById('usuario').focus();

			alert('Debe Capturar Usuario');

	}else if(document.getElementById('password').value==""){

			document.getElementById('password').focus();

			alert('Debe Capturar Password');

	}else{

			document.getElementById('accion').value = "entrar";

			document.form1.submit();

	}

}

</script>

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<title>Sistema PMM</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">

<!--

.Estilo2 {font-family: "Courier New", Courier, mono}

.Estilo3 {	color: #FFFFFF;

	font-weight: bold;

}

-->

</style>

</head>

<body onload = "document.forms[0].usuario.focus()">

<form name="form1"  method="post" onSubmit="return validar()">

<br>

<table width="929" height="451" border="0" align="center">

  <tr>

    <td width="923" background="img/fondo1.gif"><table width="482" border="0" align="center">

      <tr>

        <td colspan="2" rowspan="8">&nbsp;</td>

        <td width="93">&nbsp;</td>

        <td width="160">&nbsp;</td>

      </tr>

      <tr>

        <td>&nbsp;</td>

        <td><label></label></td>

      </tr>

      <tr>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

      </tr>

      <tr>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

      </tr>

      <tr>

        <td><span class="Estilo3">Usuario:</span></td>

        <td><input name="usuario" type="text" class="Estilo2" id="usuario" size="20" value="<?= $usuario; ?>"></td>

      </tr>

      <tr>

        <td><span class="Estilo3">Password:</span></td>

        <td><input name="password" type="password" class="Estilo2" id="password" size="20" value="<?= $password; ?>"></td>

      </tr>

      <tr>

        <td><input name="accion" type="hidden" id="accion" value="<?=$accion ?>"></td>

        <td><table width="157" border="0">

          <tr>

            <td width="76">&nbsp;</td>

            <td width="71"><img src="img/btn-gris.gif" alt="t" width="69" height="25" onClick="validar();" /></td>

          </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

</table>

</form>

</body>

</html>