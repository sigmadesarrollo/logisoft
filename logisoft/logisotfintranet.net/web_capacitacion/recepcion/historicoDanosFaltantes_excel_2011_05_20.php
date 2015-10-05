<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=estadoCuenta_Excel.xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 
	
	require_once("../ConectarSolo.php");
	$l = Conectarse('webpmm');
	
	function cambiaf_a_normal($fecha){ //Convierte fecha de mysql a normal
    	ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
	    return $lafecha; 
	} 
	function cambiaf_a_mysql($fecha){//Convierte fecha de normal a mysql 
    	ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    	return $lafecha; 
	} 
	
	$s = "select descripcion
	from catalogosucursal where id = '$_GET[sucursal]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$sucusalNombre = ($f->descripcion=="")?"TODAS":$f->descripcion;
?>
<style>
	table{
		font:Verdana, Geneva, sans-serif;
		font-size:12px;
		border: 1px #5FADDC solid;
	}
	.titulo{
		font-size:14px;
		font-weight:bold;
	}
	.cabecera{
		font-weight:bold;
		border:1px solid #5FADDC;
	}
</style>
	<table width="1604" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="11" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td width="124">REPORTE</td>
    	  <td colspan="10">DA&Ntilde;OS Y FALTANTES</td>
   	  </tr>
    	<tr>
    	  <td>SUCURSAL</td>
    	  <td colspan="10" align="left"><?=$sucusalNombre?></td>
   	  </tr>
    	<tr>
    	  <td height="9px"></td>
    	  <td width="69">&nbsp;</td>
    	  <td width="101"></td>
    	  <td width="166"></td>
    	  <td width="140"></td>
    	  <td width="379"></td>
    	  <td width="80"></td>
    	  <td colspan="4"></td>
      </tr>
      
    	<tr>
    	      <td width="124" class="cabecera" align="center">SE GENERO EN</td>
    	      <td width="69" class="cabecera" align="center" >FOLIO QUEJA</td>
    	      <td width="101" class="cabecera" align="center" >TIPO</td>
    	      <td width="166" class="cabecera" align="center" >No GUIA</td>
    	      <td width="140" class="cabecera" align="center">ESTADO GUIA</td>
    	      <td width="379" class="cabecera" align="left">DESTINATARIO</td>
    	      <td width="80" class="cabecera" align="left">DESTINO</td>
    	      <td width="83" class="cabecera" align="left" >ORIGEN</td>
    	      <td width="108" class="cabecera" align="center" >FECHA RECEPCION</td>
    	      <td width="108" class="cabecera" align="center" >FOLIO RECEPCION</td>
    	      <td width="222" class="cabecera" align="left" >COMENTARIOS</td>
      </tr>
    	    <?
					$s = "SELECT * FROM (
					SELECT 'FALTANTE' AS tipo,
					IF(m.nguia IS NOT NULL,'SI','NO') AS enqueja, IFNULL(m.folio,'') AS folioqueja, rep.guia, t.estado, t.destinatario, t.destino, t.origen,
					DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecharecepcion, rep.embarque as recepcion, rep.observaciones AS comentarios, 
					'EMBARQUE' AS segenero FROM embarquedemercancia_faltante rep
					INNER JOIN (SELECT gv.id AS guia, gv.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
					sd.prefijo AS destino, so.prefijo AS origen
					FROM guiasventanilla AS gv
					INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
					INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
					INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
					UNION
					SELECT ge.id AS guia, ge.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
					sd.prefijo AS destino, so.prefijo AS origen
					FROM guiasempresariales AS ge
					INNER JOIN catalogocliente des ON ge.iddestinatario = des.id
					INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
					INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id) t ON rep.guia=t.guia
					INNER JOIN embarquedemercancia rm ON rep.embarque = rm.folio
					LEFT JOIN moduloquejasdanosfaltantes m ON rep.guia = m.nguia
					WHERE ".(($_GET[sucursal]!="todas")? " rep.sucursal=".$_GET[sucursal]." AND " : "")." 
					rm.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
					GROUP BY rep.guia
					union
					SELECT IF(rep.dano = 1,'DAÑO',IF(rep.faltante = 1,'FALTANTE',IF(rep.sobrante = 1,'SOBRANTE',''))) AS tipo,
					IF(m.nguia IS NOT NULL,'SI','NO') AS enqueja, IFNULL(m.folio,'') AS folioqueja, rep.guia, t.estado, t.destinatario, t.destino, t.origen,
					DATE_FORMAT(rm.fecha,'%d/%m/%Y') AS fecharecepcion, rep.recepcion, rep.comentarios, 
					'RECEPCION' AS segenero FROM reportedanosfaltante rep
					INNER JOIN (SELECT gv.id AS guia, gv.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
					sd.prefijo AS destino, so.prefijo AS origen
					FROM guiasventanilla AS gv
					INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
					INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
					INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
					UNION
					SELECT ge.id AS guia, ge.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
					sd.prefijo AS destino, so.prefijo AS origen
					FROM guiasempresariales AS ge
					INNER JOIN catalogocliente des ON ge.iddestinatario = des.id
					INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
					INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id) t ON rep.guia=t.guia
					INNER JOIN recepcionmercancia rm ON rep.recepcion = rm.folio
					LEFT JOIN moduloquejasdanosfaltantes m ON rep.guia = m.nguia
					WHERE ".(($_GET[sucursal]!="todas")? " rep.sucursal=".$_GET[sucursal]." AND " : "")." 
					rm.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
					GROUP BY rep.guia
					UNION
					SELECT IF(repo.faltante = 1,'FALTANTE','') AS tipo,
					IF(m.nguia IS NOT NULL,'SI','NO') AS enqueja, IFNULL(m.folio,'') AS folioqueja, repo.guia, t.estado, t.destinatario, t.destino, t.origen,
					DATE_FORMAT(t.fecha,'%d/%m/%Y') AS fecharecepcion, repo.folioentrega AS recepcion, repo.comentarios, 
					'OCURRE' AS segenero FROM reportedanosfaltanteocurre repo
					INNER JOIN (SELECT gv.id AS guia, gv.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
					sd.prefijo AS destino, so.prefijo AS origen, r.fecha
					FROM guiasventanilla AS gv
					INNER JOIN catalogocliente des ON gv.iddestinatario = des.id
					INNER JOIN catalogosucursal sd ON gv.idsucursaldestino = sd.id
					INNER JOIN catalogosucursal so ON gv.idsucursalorigen = so.id
					INNER JOIN recepcionmercanciadetalle rd ON gv.id = rd.guia
					INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal
					UNION
					SELECT ge.id AS guia, ge.estado, CONCAT_WS(' ',des.nombre,des.paterno,des.materno) AS destinatario,
					sd.prefijo AS destino, so.prefijo AS origen, r.fecha
					FROM guiasempresariales AS ge
					INNER JOIN catalogocliente des ON ge.iddestinatario = des.id
					INNER JOIN catalogosucursal sd ON ge.idsucursaldestino = sd.id
					INNER JOIN catalogosucursal so ON ge.idsucursalorigen = so.id
					INNER JOIN recepcionmercanciadetalle rd ON ge.id = rd.guia
					INNER JOIN recepcionmercancia r ON rd.recepcion = r.folio AND rd.sucursal = r.idsucursal) t ON repo.guia=t.guia
					LEFT JOIN moduloquejasdanosfaltantes m ON repo.guia = m.nguia
					WHERE ".(($_GET[sucursal]!="todas")? " repo.sucursal=".$_GET[sucursal]." AND " : "")." 
					t.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechaini])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
					GROUP BY repo.guia)f ";
					
					//die($s);
					$r = mysql_query($s,$l) or die($s);
					$totales = 0;
					$r = mysql_query($s,$l) or die($s);
					while($f = mysql_fetch_object($r)){
						$totales++;
					?>
    	    <tr>
    	      <td align="center"><?=$f->segenero?></td>
    	      <td align="center"><?=$f->folioqueja?></td>
    	      <td align="center"><?=$f->tipo?></td>
    	      <td align="center"><?=$f->guia?></td>
    	      <td align="center"><?=$f->estado?></td>
    	      <td align="left"><?=$f->destinatario?></td>
    	      <td align="left"><?=$f->destino?></td>
    	      <td align="left"><?=$f->origen?></td>
    	      <td align="center"><?=$f->fecharecepcion?></td>
    	      <td align="center"><?=$f->recepcion?></td>
    	      <td align="left"><?=$f->comentarios?></td>
          </tr>
    	    <?
						}
				  ?>
          <tr>
    	      <td colspan="2" align="center" class="cabecera">&nbsp;</td>
    	      <td align="center" class="cabecera">TOTALES</td>
    	      <td align="center" class="cabecera"><?=$totales?></td>
    	      <td colspan="7" class="cabecera" align="right">&nbsp;</td>
   	      </tr>
</table>
    	    