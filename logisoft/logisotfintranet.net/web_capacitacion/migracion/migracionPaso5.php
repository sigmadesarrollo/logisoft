<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$la_bdatos = "pmm_curso";
	
	if(isset($_POST['enviar'])){
			
		$cuerpo = "
		DELIMITER $$

		USE `".$la_bdatos."`$$
		
		DROP PROCEDURE IF EXISTS `meterCartera".$_SESSION[IDSUCURSAL]."`$$
		
		CREATE PROCEDURE `meterCartera".$_SESSION[IDSUCURSAL]."`()
		BEGIN
			#guias
			INSERT INTO reporte_cobranza1 (idsucursal, prefijosucursal, idcliente, cliente, tipo, folio, fecha, fechavencimiento,total)
			SELECT cs.id, cs.prefijo, cc.id, CONCAT_WS(' ', cc.nombre, cc.paterno, cc.materno) AS cliente,
			'V', gv.id, gv.fecha, ADDDATE(gv.fecha, INTERVAL sc.diascredito DAY), gv.total
			FROM guiasventanilla gv
			INNER JOIN paralasguias".$_SESSION[IDSUCURSAL]." pg ON gv.id = pg.folioguia
			INNER JOIN catalogocliente cc ON IF(gv.tipoflete=0,gv.idremitente, gv.iddestinatario) = cc.id
			INNER JOIN catalogosucursal cs ON IF(gv.tipoflete=0,gv.idsucursalorigen, gv.idsucursaldestino) = cs.id
			LEFT JOIN solicitudcredito sc ON cc.id = sc.cliente AND sc.estado = 'ACTIVADO'
			WHERE gv.condicionpago = 1 AND pg.fechapago='NULL'
			GROUP BY gv.id;
							
			INSERT INTO reporte_cobranza4 (idcliente, fecha, idsucursal, prefijosucursal, folio, cargo)
			SELECT IF(gv.tipoflete=0, gv.idremitente, gv.iddestinatario), gv.fecha, cs.id, cs.prefijo, gv.id, gv.total
			FROM guiasventanilla gv
			INNER JOIN paralasguias".$_SESSION[IDSUCURSAL]." pg ON gv.id = pg.folioguia
			INNER JOIN catalogosucursal cs ON IF(gv.tipoflete=0, gv.idsucursalorigen, gv.idsucursaldestino) = cs.id
			WHERE gv.condicionpago = 1 AND pg.fechapago='NULL'
			GROUP BY gv.id;
			
			INSERT INTO pagoguias
			(guia,tipo,total,fechacreo,usuariocreo,sucursalcreo,cliente,credito,sucursalacobrar,pagado, 
			fechapago, usuariocobro, sucursalcobro)
			SELECT pg.folioguia,'FACT', pg.total, CONCAT(SUBSTRING(pg.fecha,7,4),'-',SUBSTRING(pg.fecha,4,2),'-',SUBSTRING(pg.fecha,1,2)), 
			1, IF(pg.tipoflete='P',pg.idsucursalorigen,pg.idsucursaldestino), IF(pg.tipoflete='P',pg.idremitente,pg.iddestinatario), 'SI', 
			IF(pg.tipoflete='P',pg.idsucursalorigen,pg.idsucursaldestino), IF(pg.fechapago='NULL','N','S'), 
			IF(pg.fechapago='NULL',NULL,CONCAT(SUBSTRING(pg.fechapago,7,4),'-',SUBSTRING(pg.fechapago,4,2),'-',SUBSTRING(pg.fechapago,1,2))),
			1,IF(pg.fechapago='NULL',NULL,IF(pg.tipoflete='P',pg.idsucursalorigen,pg.idsucursaldestino))
			FROM paralasguias".$_SESSION[IDSUCURSAL]." pg;
			
			INSERT INTO formapago
			(guia,procedencia,tipo,total,efectivo,tarjeta,transferencia,cheque,ncheque,
			banco,notacredito,cliente,nnotacredito,sucursal,usuario,fecha)
			SELECT pg.folioguia, 'G','V', pg.total, pg.total,0,0,0,0,
			0,0,IF(pg.tipoflete='P',pg.idremitente,pg.iddestinatario),0,IF(pg.tipoflete='P',pg.idsucursalorigen,pg.idsucursaldestino),1,
			CONCAT(SUBSTRING(pg.fechapago,7,4),'-',SUBSTRING(pg.fechapago,4,2),'-',SUBSTRING(pg.fechapago,1,2))
			FROM paralasguias".$_SESSION[IDSUCURSAL]." pg
			WHERE pg.fechapago <> 'NULL' AND pg.total > 0;
			
			#facturas
			INSERT INTO reporte_cobranza1 
			(idsucursal, prefijosucursal, idcliente, cliente, 
			tipo, folio, fecha, fechavencimiento, 
			factura, fechafactura, fechavencimientof, 
			total)
			SELECT cs.id, cs.prefijo, f.cliente, CONCAT_WS(' ', f.nombrecliente, f.apellidopaternocliente, f.apellidomaternocliente) AS cliente,
			'F_O', f.folio, f.fecha, ADDDATE(f.fecha, INTERVAL sc.diascredito DAY), 
			f.folio, f.fecha, ADDDATE(f.fecha, INTERVAL sc.diascredito DAY), 
			f.otrosmontofacturar
			FROM facturacion AS f
			INNER JOIN paralafacturacion".$_SESSION[IDSUCURSAL]." pf ON (foliofactura*-1)=f.folio
			INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
			LEFT JOIN solicitudcredito sc ON f.cliente = sc.cliente 
			WHERE pf.stipofactura = 'O' AND pf.fechapago='NULL'
			GROUP BY f.folio;
			
			
			INSERT INTO reporte_cobranza4 (idcliente, fecha, idsucursal, prefijosucursal, folio, cargo)
			SELECT f.cliente, f.fecha, cs.id, cs.prefijo, f.folio, 
			IFNULL(f.otrosmontofacturar,0)+IFNULL(f.sobmontoafacturar,0)+IFNULL(f.total,0)
			FROM facturacion f
			INNER JOIN paralafacturacion".$_SESSION[IDSUCURSAL]." pf ON (foliofactura*-1)=f.folio
			INNER JOIN catalogosucursal cs ON f.idsucursal = cs.id
			WHERE pf.stipofactura = 'O' AND pf.fechapago='NULL';
			
			INSERT INTO pagoguias
			(guia,tipo,total,fechacreo,usuariocreo,sucursalcreo,cliente,credito,sucursalacobrar,pagado, 
			fechapago, usuariocobro, sucursalcobro)
			SELECT pf.foliofactura,'FACT', pf.total, CONCAT(SUBSTRING(pf.fecha,7,4),'-',SUBSTRING(pf.fecha,4,2),'-',SUBSTRING(pf.fecha,1,2)), 
			1, pf.foliosucursal*-1, pf.idcliente, IF(pf.credito='S','SI','NO'), pf.foliosucursal, IF(pf.fechapago='NULL','N','S'), 
			IF(pf.fechapago='NULL',NULL,CONCAT(SUBSTRING(pf.fechapago,7,4),'-',SUBSTRING(pf.fechapago,4,2),'-',SUBSTRING(pf.fechapago,1,2))),
			1,IF(pf.fechapago='NULL',NULL,pf.foliosucursal)
			FROM paralafacturacion".$_SESSION[IDSUCURSAL]." pf;
			
			INSERT INTO formapago
			(guia,procedencia,tipo,total,efectivo,tarjeta,transferencia,cheque,ncheque,
			banco,notacredito,cliente,nnotacredito,sucursal,usuario,fecha)
			SELECT pf.foliofactura*-1, 'F','O', pf.total, pf.total,0,0,0,0,
			0,0,pf.idcliente,0,pf.foliosucursal,1,
			CONCAT(SUBSTRING(pf.fechapago,7,4),'-',SUBSTRING(pf.fechapago,4,2),'-',SUBSTRING(pf.fechapago,1,2))
			FROM paralafacturacion".$_SESSION[IDSUCURSAL]." pf
			WHERE pf.fechapago <> 'NULL';
		END$$

		DELIMITER ;
		";
		
		$fp=fopen('meterCartera'.$_SESSION[IDSUCURSAL].'.sql','w');        // Abrir el archivo para anexar al final
		fwrite($fp,$cuerpo);            // Escribir en el archivo
		fclose($fp);
		
		exec('mysql -u pmm -pguhAf2eh pmm_curso < meterCartera'.$_SESSION[IDSUCURSAL].'.sql',$result);
		
		$s = "call meterCartera".$_SESSION[IDSUCURSAL]."();";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "DROP PROCEDURE IF EXISTS `migrarLosDatos".$_SESSION[IDSUCURSAL]."`;";
		$r = mysql_query($s,$l) or die($s);
		
		$s = "DROP PROCEDURE IF EXISTS `meterCartera".$_SESSION[IDSUCURSAL]."`;";
		$r = mysql_query($s,$l) or die($s);
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
    <form name="form1" action="migracionPaso6.php" method="post" enctype="multipart/form-data">
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
            Paso 5: Registrar los paquetes</td>
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
            	<td width="938">De click en siguiente para ejecutar el proceso que insertara los paquetes de todas las guias.</td>
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