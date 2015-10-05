<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	function my_exec($cmd, $input='') 
         {$proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes); 
          fwrite($pipes[0], $input);fclose($pipes[0]); 
          $stdout=stream_get_contents($pipes[1]);fclose($pipes[1]); 
          $stderr=stream_get_contents($pipes[2]);fclose($pipes[2]); 
          $rtn=proc_close($proc); 
          return array('stdout'=>$stdout, 
			  'stderr'=>$stderr, 
			  'return'=>$rtn 
		  ); 
	 }
	
	if(isset($_POST['enviar'])){
		exec('mysql -u pmm -pguhAf2eh pmm_curso < consultas'.$_SESSION[IDSUCURSAL].'.sql',$result);
	}
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
    <form name="form1" action="migracionPaso4.php" method="post" enctype="multipart/form-data">
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
            Paso 3: Correr Proc. Almacenado</td>
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
            	<td width="938">En este paso se correrá el procedimiento almacenado para registrar la información en la base de datos, este paso puede ser un poco tardado.</td>
            </tr>
          	<tr>
          	  <td colspan="2"><a style="font-size:18px; color:#F60; padding-left:-10px;">Advertencia:</a></td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>No cerrar la página y esperar hasta que termine</td>
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
          		<input type="submit" name="enviar" value="Siguiente" class="button" />
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