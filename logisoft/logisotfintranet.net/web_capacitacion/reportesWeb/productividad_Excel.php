<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=productividad_Excel.xls");
	//header("Pragma: no-cache");
	//header("Expires: 0"); 
	
	require_once("../Conectar.php");
	$l = Conectarse('webpmm');
	
	$s = "SELECT CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS cliente 
		FROM catalogocliente cc WHERE id = '$_GET[cliente]'";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);
	$clienteNombre = $f->cliente;
	
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
		font-size:8px;
	}
</style>
	<table width="1004" border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="12" align="center" class="titulo">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO</td>
        </tr>
    	<tr>
    	  <td class="titulo">REPORTE</td>
    	  <td class="titulo" colspan="11">PRODUCTIVIDAD</td>
   	  </tr>
    	<tr>
    	  <td class="titulo">SUCURSAL</td>
    	  <td class="titulo" colspan="11" align="left"><?=$sucusalNombre?></td>
   	  </tr>
    	<tr>
    	  <td colspan="12">&nbsp;</td>
      </tr>
	    <tr>
    	  <td colspan="7" align="center" class="cabecera">EAD</td>
		  <td colspan="5" align="center" class="cabecera">RECOLECCION</td>
      </tr>
    	<tr>
    	      <td width="80" class="cabecera" align="center">GUIA</td>
    	      <td width="80" class="cabecera" align="center" >FECHA</td>
    	      <td width="80" class="cabecera" align="center" >ORIGEN</td>
    	      <td width="80" class="cabecera" align="center">DESTINO</td>
    	      <td width="80" class="cabecera" align="center">FECHA ENTREGA</td>
    	      <td width="120" class="cabecera" align="center">CLIENTE DESTINO</td>
    	      <td width="160" class="cabecera" align="center">
			  <table border="0" cellpadding="0" cellspacing="0">
			  <tr><td colspan="3" class="cabecera" align="center">DIAS DE ENTREGA</td></tr>
			  <tr>
			  <td class="cabecera" align="center" >MISMO DIA</td>
			  <td class="cabecera" align="center" >ENT. MAS DE 2 DIAS</td>
			  <td class="cabecera" align="center" >GIAS PEND. ENTREGA</td>
			  </tr></table></td>
			  <td width="50" class="cabecera" align="center">FOLIO ATENCIÓN</td>
    	      <td width="50" class="cabecera" align="center" >FECHA SOLICITUD</td>
    	      <td width="120" class="cabecera" align="center" >CLIENTE QUE SOLICITA LA RECOLEC</td>
    	      <td width="50" class="cabecera" align="center">FECHA DE RECOLEC</td>
    	      <td width="50" class="cabecera" align="center"># RECOLEC</td>
      </tr>
	  <tr>
    	      <td width="80" class="cabecera" align="center">FOLIO DE<br />LA GUIA</td>
    	      <td width="80" class="cabecera" align="center" >FECHA EN QUE<br />SE ELEBORO<br />LA GUIA</td>
    	      <td width="80" class="cabecera" align="center" >PREFIJO DE LA<br />SUCURSAL ORIGEN<br />DE LA GUIA</td>
    	      <td width="80" class="cabecera" align="center">PREFIJO DE LA<br />SUCURSAL DESTINO<br />DE LA GUIA</td>
    	      <td width="80" class="cabecera" align="center">FECHA EN QUE<br />SE ENTREGO<br />LA GUIA</td>
    	      <td width="120" class="cabecera" align="center">NOMBRE DEL CLIENTE<br />DESTINATARIO</td>
    	      <td width="160" class="cabecera" align="center">
			  <table border="0" cellpadding="0" cellspacing="0"><tr>
			  <td class="cabecera" align="center" >SI SE ENTREGO<br />EL MISMO DIA</td>
			  <td class="cabecera" align="center" >SI SE ENTREGO<br />DESPUES DEL DIA<br />EN QUE SE<br />RECEPCIONO</td>
			  <td class="cabecera" align="center" >SI SE ENCUENTRA<br />PENDIENTE<br />DE ENTREGAR</td>
			  </tr></table></td>
			  <td width="50" class="cabecera" align="center">FOLIO GENERADO<br />EN LA AGENDA<br />DE RECOLECCION</td>
    	      <td width="50" class="cabecera" align="center" >FECHA EN QUE<br />SE REGISTRO<br />LA SOLICITUD<br />DE RECOLECCION</td>
    	      <td width="120" class="cabecera" align="center" >NOMBRE DEL CLIENTE QUE<br />REALIZO LA SOLICITUD<br />DE RECOLECCION</td>
    	      <td width="50" class="cabecera" align="center">FECHA EN<br />QUE SE LLEVO<br />A CABO LA<br />RECOLECCION</td>
    	      <td width="50" class="cabecera" align="center">NUMERO DE<br />PAPELETA DE<br />RECOLECCION</td>
      </tr>
	<?
		#registros
		$s = "SELECT nombrecliente,SUM(undiaead) AS undiaead, SUM(dosdiaead) AS dosdiaead, SUM(faltanteead) AS faltanteead,
		SUM(undiarec) AS undiarec, SUM(dosdiasrec) AS dosdiasrec, SUM(faltanterec) AS faltanterec
		FROM(
			SELECT r1.cliente, CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) AS nombrecliente, 
			IFNULL(SUM(IF(diasead = 0,1,0)),0) AS undiaead, IFNULL(SUM(IF(diasead >= 1,1,0)),0) AS dosdiaead,
			IFNULL((SELECT SUM(t.total) FROM(
				SELECT COUNT(*) AS total FROM guiasventanilla 
				WHERE YEAR(fecha)=YEAR('".cambiaf_a_mysql($_GET[fecha])."') AND MONTH(fecha)=MONTH('".cambiaf_a_mysql($_GET[fecha])."') 
				AND estado = 'ALMACEN DESTINO' AND ocurre = 0 $and1
			UNION
				SELECT COUNT(*) AS total FROM guiasempresariales 
				WHERE YEAR(fecha)=YEAR('".cambiaf_a_mysql($_GET[fecha])."') AND MONTH(fecha)=MONTH('".cambiaf_a_mysql($_GET[fecha])."') 
				AND estado = 'ALMACEN DESTINO' AND ocurre = 0 $and1	) t
			),0) AS faltanteead,0 AS undiarec, 0 AS dosdiasrec, 0 AS faltanterec 
			FROM reporteproductividad_cliente1 r1
			INNER JOIN catalogocliente cc ON r1.cliente = cc.id
			WHERE YEAR(fecharecepcion)=YEAR('".cambiaf_a_mysql($_GET[fecha])."') AND MONTH(fecharecepcion)=MONTH('".cambiaf_a_mysql($_GET[fecha])."')
		UNION
			SELECT r2.cliente,CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) nombrecliente,0 AS undiaead,0 AS dosdiasead,
			0 AS totalead,SUM(IF(diasrecoleccion = 0,1,0)) AS undiarec,SUM(IF(diasrecoleccion >= 1,1,0)) AS dosdiasrec,
			(SELECT COUNT(*) FROM recoleccion WHERE (realizo IS NULL OR realizo = 'NO')) AS faltanterec
			FROM reporteproductividad_cliente2 r2
			INNER JOIN catalogocliente cc ON r2.cliente = cc.id
			WHERE YEAR(fechasolicitud)=YEAR('".cambiaf_a_mysql($_GET[fecha])."') AND MONTH(fechasolicitud)=MONTH('".cambiaf_a_mysql($_GET[fecha])."')
			AND diasrecoleccion IS NOT NULL	$and2
		) t GROUP BY cliente HAVING nombrecliente <>'' ";
		$r = mysql_query($s,$l) or die($s."\n".mysql_error($l));
		$f = mysql_fetch_object($r);
			
	?>
    	    <tr>
    	      <td align="center"><?=$f->guia?></td>
    	      <td align="center"><?=$f->fecha?></td>
    	      <td align="center"><?=$f->referenciacargo?></td>
    	      <td align="center"><?=$f->referenciaabono?></td>
    	      <td align="center"><?=$f->cargos?></td>
    	      <td align="center"><?=$f->abonos?></td>
    	      <td align="center"><?=$f->saldo?></td>
    	      <td align="center"><?=$f->descripcion?></td>
  	      </tr>
    </table>