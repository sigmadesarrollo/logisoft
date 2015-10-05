<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	ini_set('post_max_size','512M');
	ini_set('upload_max_filesize','512M');
	ini_set('memory_limit','500M');
	ini_set('max_execution_time',600);
	ini_set('limit',-1);
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
    <form name="form1" action="migracionPaso2.php" method="post" enctype="multipart/form-data">
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
            Paso 1: Subir Archivos</td>
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
            	<td width="938"><p>Para este paso solamente debe asignar los archivos de excel a sus casillas especificas y dar click en Siguiente<br />
           	  </p></td>
            </tr>
          	<tr>
          	  <td colspan="2"><a style="font-size:18px; color:#F60; padding-left:-10px;">Advertencia:</a></td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>Verifique que los archivos son los correctos y estan en el lugar correcto</td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>&nbsp;</td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>
              
              <table width="929">
              	<tr>
                	<td width="303">
                    <table>
            	<tr>
                	<td>Subir Clientes</td>
                </tr>
                <?
					for($i=1; $i<4; $i++){
				?>
                <tr>
                	<td><input type="file" name="paralosclientes<?=$_SESSION[IDSUCURSAL]?><?=$i?>" id="guias1" /></td>
                </tr>
                <?
					}
				?>
                </table>
                    </td>
                    <td width="308">
                    <table>
                <tr>
                	<td>Subir Direcciones</td>
                </tr>
                <?
					for($i=1; $i<4; $i++){
				?>
                <tr>
                	<td><input type="file" name="paralasdirecciones<?=$_SESSION[IDSUCURSAL]?><?=$i?>" id="guias1" /></td>
                </tr>
                <?
					}
				?>
                </table>
                    </td>
                    <td width="302">
                <table>
                <tr>
                	<td>Subir Guias</td>
                </tr>
                <?
					for($i=1; $i<4; $i++){
				?>
                <tr>
                	<td><input type="file" name="paralasguias<?=$_SESSION[IDSUCURSAL]?><?=$i?>" id="guias1" /></td>
                </tr>
                <?
					}
				?>
                </table>
                    </td>
                </tr>
              	<tr>
              	  <td>
                  <table>
                <tr>
                	<td>Subir Detalleguias</td>
                </tr>
                <?
					for($i=1; $i<4; $i++){
				?>
                <tr>
                	<td><input type="file" name="paradetalleguia<?=$_SESSION[IDSUCURSAL]?><?=$i?>" id="guias1" /></td>
                </tr>
                <?
					}
				?>
                </table>                  
                  </td>
              	  <td>
                  <table>
                <tr>
                	<td>Subir Facturas</td>
                </tr>
                <?
					for($i=1; $i<4; $i++){
				?>
                <tr>
                	<td><input type="file" name="paralafacturacion<?=$_SESSION[IDSUCURSAL]?><?=$i?>" id="guias1" /></td>
                </tr>
                <?
					}
				?>
                </table>
                  </td>
              	  <td>
                   <table>
            	<tr>
                	<td>Subir Detalle Facturas</td>
                </tr>
                <?
					for($i=1; $i<4; $i++){
				?>
                <tr>
                	<td><input type="file" name="paralafacturaciondetalle<?=$_SESSION[IDSUCURSAL]?><?=$i?>" id="guias1" /></td>
                </tr>
                <?
					}
				?>
                
            </table>
                  </td>
            	  </tr>
              	<tr>
              	  <td><table>
              	    <tr>
              	      <td>Subir Créditos</td>
            	      </tr>
              	    <?
					for($i=1; $i<4; $i++){
				?>
              	    <tr>
              	      <td><input type="file"  name="paraloscreditos<?=$_SESSION[IDSUCURSAL]?><?=$i?>" id="guias1" /></td>
            	      </tr>
              	    <?
					}
				?>
            	    </table></td>
              	  <td>&nbsp;</td>
              	  <td>&nbsp;</td>
            	  </tr>
              </table>      
              
              </td>
       	    </tr>
          	<tr>
          	  <td></td>
          	  <td>&nbsp;</td>
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