<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$la_bdatos = "pmm_curso";
	
	if(isset($_POST['enviar'])){
			
		$cuerpo = "
			DELIMITER $$

			USE `".$la_bdatos."`$$
			
			DROP PROCEDURE IF EXISTS `meterPaquetesV".$_SESSION[IDSUCURSAL]."`$$
			
			CREATE PROCEDURE `meterPaquetesV".$_SESSION[IDSUCURSAL]."`()
			BEGIN
					DECLARE accion INT DEFAULT 0;
					DECLARE campo_idguia, campo_descripcion, campo_contenido VARCHAR(25);
					DECLARE campo_cantidad, campo_peso, campo_totalpeso, campo_totalpaquetes DOUBLE;
					
					DECLARE traza1 CURSOR FOR 
						SELECT gd.peso, gd.cantidad, gd.descripcion, gd.contenido, gd.idguia, gv.totalpeso, gv.totalpaquetes
						FROM guiaventanilla_detalle gd
						INNER JOIN paralasguias".$_SESSION[IDSUCURSAL]." pg ON gd.idguia = pg.folioguia
						INNER JOIN guiasventanilla gv ON pg.folioguia = gv.id
						WHERE gv.estado='ALMACEN DESTINO' ORDER BY gv.id;
					DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET accion = 1;
					
					
			
					SET @i = 1;
					
					OPEN traza1;
						REPEAT
							FETCH traza1 INTO campo_peso,campo_cantidad,campo_descripcion,campo_contenido,campo_idguia,campo_totalpeso,campo_totalpaquetes;
							IF accion = 0 THEN
								SET @j = 1;
								REPEAT 
									
								
									INSERT INTO guiaventanilla_unidades SET idguia=campo_idguia, descripcion=campo_descripcion, 
									contenido=campo_contenido, peso=campo_peso/campo_cantidad, paquete=@i, 
									depaquetes=campo_totalpaquetes, ubicacion = '$_SESSION[IDSUCURSAL]', proceso='ALMACEN DESTINO',
									codigobarras=CONCAT(campo_idguia,LPAD(@i,4,0),LPAD(campo_totalpaquetes,4,0));
									
									SET @i = @i + 1;
									SET @j = @j + 1;
								
								UNTIL @j > campo_cantidad END REPEAT;
								
								IF @i >= campo_totalpaquetes THEN
									SET @i = 1;
								END IF;
							END IF;
						UNTIL accion END REPEAT;
					CLOSE traza1;
				END$$
			
			DELIMITER ;
			
			DELIMITER $$

			USE `".$la_bdatos."`$$
			
			DROP PROCEDURE IF EXISTS `meterPaquetesE".$_SESSION[IDSUCURSAL]."`$$
			
			CREATE PROCEDURE `meterPaquetesE".$_SESSION[IDSUCURSAL]."`()
			BEGIN
					DECLARE accion INT DEFAULT 0;
					DECLARE campo_idguia, campo_descripcion, campo_contenido VARCHAR(25);
					DECLARE campo_cantidad, campo_peso, campo_totalpeso, campo_totalpaquetes DOUBLE;
					
					DECLARE traza1 CURSOR FOR 
						SELECT gd.peso, gd.cantidad, gd.descripcion, gd.contenido, gd.id idguia, gv.totalpeso, gv.totalpaquetes
						FROM guiasempresariales_detalle gd
						INNER JOIN paralasguias".$_SESSION[IDSUCURSAL]." pg ON gd.id = pg.folioguia
						INNER JOIN guiasempresariales gv ON pg.folioguia = gv.id
						WHERE gv.estado='ALMACEN DESTINO' ORDER BY gv.id;
					DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET accion = 1;
					
					
			
					SET @i = 1;
					
					OPEN traza1;
						REPEAT
							FETCH traza1 INTO campo_peso,campo_cantidad,campo_descripcion,campo_contenido,campo_idguia,campo_totalpeso,campo_totalpaquetes;
							IF accion = 0 THEN
								SET @j = 1;
								REPEAT 
								
									INSERT INTO guiasempresariales_unidades SET idguia=campo_idguia, descripcion=campo_descripcion, 
									contenido=campo_contenido, peso=campo_peso/campo_cantidad,paquete=@i, proceso='ALMACEN DESTINO',
									depaquetes=campo_totalpaquetes, ubicacion = '$_SESSION[IDSUCURSAL]',
									codigobarras=CONCAT(campo_idguia,LPAD(@i,4,0),LPAD(campo_totalpaquetes,4,0));					
									
									SET @i = @i + 1;
									SET @j = @j + 1;
								
								UNTIL @j > campo_cantidad END REPEAT;
								
								IF @i >= campo_totalpaquetes THEN
									SET @i = 1;
								END IF;
							END IF;
						UNTIL accion END REPEAT;
					CLOSE traza1;
				END$$
			
			DELIMITER ;
			
			CALL meterPaquetesV".$_SESSION[IDSUCURSAL]."();
			CALL meterPaquetesE".$_SESSION[IDSUCURSAL]."();
			
			DROP PROCEDURE IF EXISTS `meterPaquetesV".$_SESSION[IDSUCURSAL]."`;
			DROP PROCEDURE IF EXISTS `meterPaquetesE".$_SESSION[IDSUCURSAL]."`;
			
			#borrar las tablas de los movimientos
			DROP TABLE IF EXISTS paradetalleguia9;
			DROP TABLE IF EXISTS paralafacturacion9;
			DROP TABLE IF EXISTS paralafacturaciondetalle9;
			DROP TABLE IF EXISTS paralasdirecciones9;
			DROP TABLE IF EXISTS paralasguias9;
			DROP TABLE IF EXISTS paralosclientes9;
			DROP TABLE IF EXISTS paraloscreditos9;*/
		";
		
		$fp=fopen('meterPaquetes'.$_SESSION[IDSUCURSAL].'.sql','w');        // Abrir el archivo para anexar al final
		fwrite($fp,$cuerpo);            // Escribir en el archivo
		fclose($fp);
		
		exec('mysql -u pmm -pguhAf2eh pmm_dbpruebas < meterPaquetes'.$_SESSION[IDSUCURSAL].'.sql',$result);
		
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
            Proceso terminado</td>
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
            	<td width="938">De click en finalizar o cierre la pagina</td>
            </tr>
          	<tr>
          	  <td colspan="2">&nbsp;</td>
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
          		<input type="submit" name="finalizar" value="Finalizar" class="button" onclick="window.close();" />
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