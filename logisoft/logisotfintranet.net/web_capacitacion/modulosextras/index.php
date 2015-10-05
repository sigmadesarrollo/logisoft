<?
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
	if($_POST[accion]=='loguear'){
		$s = "select * from catalogoempleado where user = '$_POST[usuario]' AND password = '$_POST[password]' and id in(4,5,12,27,771)";
		$r = mysql_query($s,$l) or die(mysql_error($l).$s);
		if(mysql_num_rows($r)>0){
			$_SESSION[modulosextras] = "SI";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>INDICE</title>
<script src="../javascript/ClaseMensajes.js"></script>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?
	if($_SESSION[modulosextras]=="SI"){
?>
	<form name="form1" action="" method="POST">
    	<table width="232" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
        	<tr>
                <td colspan="2" class="FondoTabla Estilo4">Menu Modulos Extras</td>
          </tr>
        	<tr>
            	<td height="22" align="center"><a href="cambiaradestino.php">MODULO PARA CAMBIAR ESTADO</a></td>
          </tr>
		  <tr>
		 	 <td height="22" align="center"><a href="addFoliosEmpresariales.php">MODULO REGISTRAR FOLIOS EMP.</a></td>
          </tr>
		  <tr>
		 	 <td height="22" align="center"><a href="liberadorDeRecoleccion.php">LIBERADOR DE RECOLECCION</a></td>
          </tr>
		  <tr>
		 	 <td height="22" align="center"><a href="elaborarGuiasEmpresariales.php">ELABORACION GUIAS EMPRESARIALES</a></td>
          </tr>
		  <tr>
		 	 <td height="22" align="center"><a href="cambioEdoXEntregarAEntregada.php">CAMBIAR ESTADO POR ENTREGAR A ENTREGADOS</a></td>
          </tr>	
		  <tr>
		 	 <td height="22" align="center"><a href="liberarYagregarunidad.php">LIBERADOR Y ASIGNACION DE UNIDADES</a></td>
          </tr>
          <tr>
		 	 <td height="22" align="center"><a href="../facturacion/Facturacion_modificaciones.php">MODIFICAR DATOS CLIENTE EN LA FACTURACION</a></td>
          </tr>	
        </table>
    </form>
<?
	}else{
?>
		<form name="form1" action="" method="POST">
			<table width="232" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
            	
              <tr>
                <td colspan="2" class="FondoTabla Estilo4">SESION</td>
              </tr>
            	<tr>
                	<td>
               <table>
              <tr>
                <td width="87">USUARIO</td><td width="139"><input type="text" name="usuario" /></td>
              </tr>
              <tr>
                <td width="87">PASSWORD</td><td width="139"><input type="password" name="password" /></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><img src="../img/Boton_Aceptar.gif" onclick="enviar()" /></td>
              </tr>
              </table>
              </td>
              </tr>
            </table>
            <input type="hidden" name="accion" id="accion" />
         </form>
         <script>
		 	function enviar(){
				document.getElementById('accion').value = "loguear";
				document.form1.submit();
			}
		 </script>
<?			
	}
?>
</body>
</html>