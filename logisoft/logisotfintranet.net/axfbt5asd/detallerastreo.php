<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estiloRastreo.css" rel="stylesheet" type="text/css" />
</head>
<?
	require_once("../web/Conectar.php");
	$l = Conectarse("webpmm");
	
?>
<body bgcolor="#FFFFFF">
<table width="688" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td width="95"></td>
            <td width="15">&nbsp;</td>
            <td width="578"></td>
        </tr>
    	<tr>
    	  <td height="133" colspan="3" style="padding-left:15px">
          <table>
          	<tr>
            	<td width="88" rowspan="2"><img src="logopmm.gif" /></td>
              <td width="10" class="fuenteTitulo">&nbsp;</td>
                <td width="514" class="fuenteTitulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
            </tr>
          	<tr>
          	  <td height="24">&nbsp;</td>
          	  <td class="fuenteSubTitulo">RASTREO DE GUIAS</td>
       	    </tr>
          </table></td>
   	  </tr>
    	<tr>
    	  <td></td>
    	  <td>&nbsp;</td>
    	  <td align="right" class="fuenteColumnas"><?=date("d/m/Y h:m:s") ?></td>
  	  </tr>
      </table>
      
      <?
		  	//$_GET[numerorastreo]='081007091208V3460';
			$_GET[numerorastreo] = "'".str_replace(",","','",$_GET[numerorastreo])."'";
			
		  	$s = "select gv.id, gr.numerorastreo, gv.estado,
			if(gv.tipoflete=0,'PAGADO', 'POR COBRAR') as tipoenvio,
			'VENTANILLA' AS tiposervicio, if(gv.ocurre=0,'OCURRE','ENTREGA A DOMICILIO') tipoentrega,
			gv.totalpaquetes, cso.descripcion as origen, csd.descripcion as destino,
			concat_ws(' ',ccr.nombre, ccr.paterno, ccr.materno) as remitente,
			concat_ws(' ',ccd.nombre, ccd.paterno, ccd.materno) as destinatario,
			concat_ws(' ',day(gv.fecha),
				CASE month(gv.fecha)
				when 1 then 'ENERO'
				when 2 then 'FEBRERO'
				when 3 then 'MARZO'
				when 4 then 'ABRIL'
				when 5 then 'MAYO'
				when 6 then 'JUNIO'
				when 7 then 'JULIO'
				when 8 then 'AGOSTO'
				when 9 then 'SEPTIEMBRE'
				when 10 then 'OCTUBRE'
				when 11 then 'NOVIEMBRE'
				when 12 then 'DICIEMBRE'
				END
			, year(gv.fecha)) as fecharegistro, 
			concat_ws(' ',day(gv.fechaentrega),
				CASE month(gv.fechaentrega)
				when 1 then 'ENERO'
				when 2 then 'FEBRERO'
				when 3 then 'MARZO'
				when 4 then 'ABRIL'
				when 5 then 'MAYO'
				when 6 then 'JUNIO'
				when 7 then 'JULIO'
				when 8 then 'AGOSTO'
				when 9 then 'SEPTIEMBRE'
				when 10 then 'OCTUBRE'
				when 11 then 'NOVIEMBRE'
				when 12 then 'DICIEMBRE'
				END
			, year(gv.fechaentrega)) as fechaentrega,
			gv.recibio
			from guiasventanilla as gv 
			inner join guia_rastreo as gr on gv.id = gr.noguia
			inner join catalogosucursal as cso on gv.idsucursalorigen = cso.id
			inner join catalogosucursal as csd on gv.idsucursaldestino = csd.id
			inner join catalogocliente as ccr on gv.idremitente = ccr.id
			inner join catalogocliente as ccd on gv.iddestinatario = ccd.id
			where gr.numerorastreo in($_GET[numerorastreo])";
			$r = mysql_query($s,$l) or die($s);
			if(mysql_num_rows($r)<1){
				die('<table width="688" border="0" bordercolor="#666666" cellpadding="0" cellspacing="0">
					<tr>
            	  	<td colspan="6" align="center" class="fuenteSubCot">No se encontró ninguna guia</td>
           	  		</tr>
					</table>');
			}
			while($f = mysql_fetch_object($r)){
		  ?>
      <table width="688" border="1" bordercolor="#666666" cellpadding="0" cellspacing="0">
      <tr>
      	<td>
      <table width="686" border="0" cellpadding="0" cellspacing="0">
    	<tr>
    	  <td></td>
    	  <td>&nbsp;</td>
    	  <td align="right" class="fuenteColumnas"></td>
  	  </tr>
    	<tr>
    	  <td colspan="3" style="padding-left:5px">
    	    <table width="679" border="0" cellpadding="0" cellspacing="0">
    	      <tr  class="fuenteColumnas">

    	        <td width="193">NUMERO DE GUIA</td>
    	        <td width="85"></td>
    	        <td width="401"><?=$f->id?></td>
    	        </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>NUMERO DE RASTREO</td>
    	        <td></td>
    	        <td><?=$f->numerorastreo?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>TIPO DE ENVIO</td>
    	        <td></td>
    	        <td><?=$f->tipoenvio?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>TIPO DE SERVICIO</td>
    	        <td></td>
    	        <td><?=$f->tiposervicio?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>TIPO DE ENTREGA</td>
    	        <td></td>
    	        <td><?=$f->tipoentrega?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>NUMERO DE PAQUETES</td>
    	        <td></td>
    	        <td><?=$f->totalpaquetes?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>ORIGEN</td>
    	        <td></td>
    	        <td><?=$f->origen?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>DESTINO</td>
    	        <td></td>
    	        <td><?=$f->destino?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>NOMBRE DEL REMITENTE</td>
    	        <td></td>
    	        <td><?=$f->remitente?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>NOMBRE DEL DESTINATARIO</td>
    	        <td></td>
    	        <td><?=$f->destinatario?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>FECHA DE DOCUMENTACION</td>
    	        <td></td>
    	        <td><?=$f->fecharegistro?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>ESTADO ACTUAL</td>
    	        <td></td>
    	        <td><?=$f->estado?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>FECHA DE RECEPCION</td>
    	        <td></td>
    	        <td><?=$f->fechaentrega?></td>
   	          </tr>
    	      <tr  class="fuenteColumnas">

    	        <td>QUIEN RECIBIO</td>
    	        <td></td>
    	        <td><?=$f->recibio?></td>
   	          </tr>
              <?
			  	if($f->estado=='ENTREGADA'){
					if($f->tiposervicio=='VENTANILLA')
						$tipo=1;
					else
						$tipo=2;
			  ?>
    	      <tr  class="fuenteColumnas">
    	        <td>&nbsp;</td>
    	        <td></td>
    	        <td>FIRMA DE RECIBIDO</td>
  	        </tr>
    	      <tr  class="fuenteColumnas">
    	        <td></td>
    	        <td></td>
    	        <td><img src="paraimagenes.php?tipo=<?=$tipo?>&guia=<?=$f->id?>" width="190px" height="190px"></td>
  	        </tr>
            <?
				}
			?>
   	        </table>  	    </td>
   	  </tr>
    	<tr>
    	  <td colspan="3">
    	    <table width="680" border="0" cellpadding="0" cellspacing="0">
    	      <tr>
    	        <td colspan="4"><hr /></td>
   	          </tr>
    	      <tr class="fuenteColumnas">
    	        <td width="147" align="center">FECHA</td>
    	        <td width="101" align="center">HORA</td>
    	        <td width="157" align="center">SUCURSAL</td>
    	        <td width="275" align="center">EVENTO</td>
   	          </tr>
    	      <tr>
    	        <td colspan="4"><hr /></td>
   	          </tr>
              <?
			  		$s = "SELECT concat_ws(' ',day(sg.fecha),
						CASE month(sg.fecha)
						when 1 then 'ENERO'
						when 2 then 'FEBRERO'
						when 3 then 'MARZO'
						when 4 then 'ABRIL'
						when 5 then 'MAYO'
						when 6 then 'JUNIO'
						when 7 then 'JULIO'
						when 8 then 'AGOSTO'
						when 9 then 'SEPTIEMBRE'
						when 10 then 'OCTUBRE'
						when 11 then 'NOVIEMBRE'
						when 12 then 'DICIEMBRE'
						END
					, year(sg.fecha)) AS fecha, sg.hora, 
					cs.descripcion AS ubicacion, sg.estado as evento, sg.unidad, 
					CONCAT_WS(' ',ce.nombre,ce.apellidopaterno,ce.apellidomaterno) AS empleado,
					sg.bitacora, sg.embarque
					FROM seguimiento_guias AS sg
					INNER JOIN catalogosucursal AS cs ON sg.ubicacion = cs.id 
					INNER JOIN catalogoempleado as ce on sg.usuario = ce.id
					WHERE sg.guia = '$f->id'";
					$rx = mysql_query($s,$l);
					while($fx = mysql_fetch_object($rx)){
			  ?>
    	      <tr class="fuenteFilas">
    	        <td align="center"><?=$fx->fecha?></td>
    	        <td align="center"><?=substr($fx->hora,0,5)?></td>
    	        <td align="left"><?=$fx->ubicacion?></td>
    	        <td align="left"><?
						switch ($fx->evento){
							case 'ALMACEN ORIGEN':
								echo 'MERCANCIA DOCUMENTADA';
								break;
							case 'EN TRANSITO':
								echo 'MERCANCIA EMBARCADA Y EN TRANSITO';
								break;
							default:
								echo $fx->evento;
						}
					
					?>				</td>
   	          </tr>
              <?
					}
			  ?>
   	        </table>  	    </td>
   	  </tr>
    	<tr>
    	  <td colspan="3" class="fuenteColumnas"></td>
  	  </tr>
    	<tr>
    	  <td colspan="3" class="fuenteColumnas" align="right">
          <table>
          	<TR>
            	<TD width="439">DUDAS Y ACLARACIONES FAVOR DE MARCAR A ESTE NUMERO 01-800-0000-</TD><TD width="51"><FONT size="+1">PMM</FONT></TD>
            </TR>
          	<TR>
          	  <TD>&nbsp;</TD>
          	  <TD>&nbsp;7&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;6</TD>
       	    </TR>
          </table>          </td>
  	  </tr>
    </table></td>
      </tr>
      </table>
    <? } ?>
</body>
</html>