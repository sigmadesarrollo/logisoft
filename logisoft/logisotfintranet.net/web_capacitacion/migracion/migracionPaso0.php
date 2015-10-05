<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Migración del Sistema</title>
<link href="estilosMigracion.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<?
		if(empty($_SESSION[IDSUCURSAL])){
			die("<div id='error'>Favor de loguearse para registrar la información de la migración en su sucursal</div>");
		}
	?>
    <form name="form1" action="migracionPaso1.php" method="post">
    <div id="acomodoCentrado">
	<table width="993" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td width="16" height="175">&nbsp;</td>
            <td width="214"><img src="../../images/pmm-icons/12migracion200x200.png" /></td>
            <td width="13"></td>
            <?
				$s = "select descripcion from catalogosucursal where id = '$_SESSION[IDSUCURSAL]'";
				$r = mysql_query($s,$l) or die($s);
				$f = mysql_fetch_object($r);
			?>
            <td width="790" id="estiloTitulo">Migración del sistema <br />
            Sucursal: <a style="color:#C00"><?=$f->descripcion?></a><br />
            Instrucciones</td>
            <td width="16"></td>
        </tr>
    	<tr>
    	  <td height="23">&nbsp;</td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
  </tr>
  		<tr>
    	  <td height="23">&nbsp;</td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
    	  <td></td>
  </tr>
    	<tr>
    	  <td height="29">&nbsp;</td>
    	  <td colspan="3" id="estiloInstruccion">
          <table>
          	<tr>
            	<td colspan="2">
          		<a style="font-size:18px; color:#060; padding-left:-10px;">Instrucciones:</a>
    	    </td>
            </tr>
          	<tr>
            	<td width="21"></td>
            	<td width="938"><p>Bienvenido al asistente de migración del sistema.<br />
           	      Por favor verifique que la sucursal mostrada en la parte de arriba sea la correcta donde usted se encuentra, si no es asi cierre la ventana e ingrese otro usuario en el login del sistema. Si no tiene algún usuario de su sucursal favor de comunicarse con sistemas para que le asignen uno.<br />
           	      Se recomienda tener cerrados los programas que consuman ancho de banda (Internet) para no perjudicar el renvio de la información y no correr el riesgo de trabar el asistente. </p>
           	  <p>El proceso de la migración se llevará en 4 pasos:</p>
           	  <ul>
              	<li>Paso1: Subir archivos de excel con la información.</li>
                <li>Paso2: Ejecutar el proceso de registro y preparación de la información.</li>
                <li>Paso3: Ejecutar el proceso de introducción de guias y clientes en el sistema.</li>
                <li>Paso4: Ejecutar el proceso de registro de la cobranza e inventario.</li>
              </ul>
              <br />
              Se recomienda tener paciencia durante los pasos del proceso, ya que algunos pueden llegar a tardar.</td>
            </tr>
          	<tr>
          	  <td colspan="2"><a style="font-size:18px; color:#F60; padding-left:-10px;">Advertencia:</a></td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>
              Si se inicio el proceso:
              <ul>
              	<li>No debe cerrar la pagina por que la información quedará incompleta.</li>
              	<li>Se debe de llegar hasta el final de los pasos para terminar completamente la insercion de la información.</li>
              	<li>No ejecutar nada que redusca la capacidad del ancho de banda del internet.</li>
              </ul>
              </td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>&nbsp;</td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>Para iniciar el proceso de a migración de click en Iniciar</td>
       	    </tr>
          </table>
          </td>
    	  <td></td>
  	  </tr>
    	<tr>
    	  <td height="19">&nbsp;</td>
    	  <td colspan="3">&nbsp;</td>
    	  <td></td>
  	  </tr>
    	<tr>
    	  <td height="29">&nbsp;</td>
    	  <td colspan="3" align="center">
          		<input type="submit" name="enviar" value="Iniciar" class="button" />
          </td>
    	  <td></td>
  	  </tr>
    	<tr>
    	  <td height="15"></td>
    	  <td colspan="3" align="center"></td>
    	  <td></td>
  	  </tr>
    </table>
    </div>
    </form>
</body>
</html>