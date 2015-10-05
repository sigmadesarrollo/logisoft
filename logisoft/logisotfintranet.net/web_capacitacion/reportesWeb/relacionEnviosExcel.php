<?	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reportecierrediadetallado.xls");
	//header("Pragma: no-cache");
	header("Expires: 0");
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
		
		?>
<table width="1853" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
	<tr>
    	<td height="36" colspan="12" style="vertical-align:top; font-size:14px; font-weight:bold;">REPORTE DE ENVIOS POR CLIENTE<br />
    	  <?
          	$s = "select * from catalogocliente where id = '$_GET[cliente]'";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
			echo strtoupper($f->nombre." ".$f->paterno." ".$f->materno);
		  ?></td>
    </tr>
    <tr>
    	<td width="86" style="font-weight:bold">Fecha</td>
    	<td width="149" style="font-weight:bold">Guias</td>
    	<td width="144" style="font-weight:bold">Destino</td>
    	<td width="341" style="font-weight:bold">Destinatario</td>
    	<td width="101" style="font-weight:bold">Flete</td>
    	<td width="102" style="font-weight:bold">Envio</td>
    	<td width="127" style="font-weight:bold">Paquetes</td>
    	<td width="83" style="font-weight:bold">Kilogramos</td>
    	<td width="109" style="font-weight:bold">Total</td>
    	<td width="128" style="font-weight:bold">Estado</td>
    	<td width="313" style="font-weight:bold">Quien Recibio</td>
    	<td width="144" style="font-weight:bold">Entrega</td>
    </tr>
    <?
		if($_GET[sucursal]!="" && $_GET[sucursal]!=0){
			$sucuv = " AND gv.idsucursaldestino = $_GET[sucursal] ";
			$sucue = " AND ge.idsucursaldestino = $_GET[sucursal] ";
		}
		
		$s = "SELECT * FROM(
		SELECT gv.id AS guia, DATE_FORMAT(gv.fecha,'%d/%m/%Y') AS fecha, 
		IF(gv.tipoflete=0,'PAGADA','POR COBRAR') AS flete, 
		IF(gv.condicionpago=0,'CONTADO','CREDITO') AS condicion, IF(gv.ocurre=0,'EAD','OCURRE') AS entrega, 
		ori.prefijo AS origen, des.prefijo AS destino, CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS destinatario,
		gv.total,ifnull(DATE_FORMAT(gv.fechaentrega, '%d/%m/%Y'),'') fechaentrega, ifnull(gv.recibio,'') recibio,
		gv.totalpaquetes, gv.totalpeso, gv.estado
		FROM guiasventanilla gv
		INNER JOIN catalogosucursal ori ON gv.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON gv.idsucursaldestino = des.id
		INNER JOIN catalogocliente re ON gv.iddestinatario = re.id
		WHERE gv.idremitente = ".$_GET[cliente]." 
		AND gv.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."'
		AND gv.estado <> 'CANCELADO' $sucuv
		UNION
		SELECT ge.id AS guia, DATE_FORMAT(ge.fecha,'%d/%m/%Y') AS fecha, ge.tipoflete AS flete, 
		ge.tipopago AS condicion, IF(ge.ocurre=0,'EAD','OCURRE') AS entrega, ori.prefijo AS origen,
		des.prefijo AS destino, CONCAT_WS(' ',re.nombre,re.paterno,re.materno) AS destinatario, ge.total,
		ifnull(DATE_FORMAT(ge.fechaentrega, '%d/%m/%Y'),'') fechaentrega, ifnull(ge.recibio,'') recibio,
		ge.totalpaquetes, ge.totalpeso, ge.estado
		FROM guiasempresariales ge
		INNER JOIN catalogosucursal ori ON ge.idsucursalorigen = ori.id
		INNER JOIN catalogosucursal des ON ge.idsucursaldestino = des.id
		INNER JOIN catalogocliente re ON ge.iddestinatario = re.id
		WHERE ge.idremitente = ".$_GET[cliente]." 
		AND ge.fecha BETWEEN '".cambiaf_a_mysql($_GET[fechainicio])."' AND '".cambiaf_a_mysql($_GET[fechafin])."' $sucue) t ";
		$r = mysql_query($s,$l) or die($s);
		$ar = array();
		$total		= 0;
		while($f = mysql_fetch_object($r)){
			$total		+= $f->total;
			?>
			<tr>
              <td><?=$f->fecha?></td>
              <td><?=$f->guia?></td>
              <td><?=$f->destino?></td>
              <td><?=$f->destinatario?></td>
              <td><?=$f->flete?></td>
              <td><?=$f->entrega?></td>
              <td><?=$f->totalpaquetes?></td>
              <td><?=$f->totalpeso?></td>
              <td><?='$ '.number_format($f->total,2)?></td>
              <td><?=$f->estado?></td>
              <td><?=$f->recibio?></td>
              <td><?=$f->fechaentrega?></td>
            </tr>
			<?
		}
?>
    <tr>
          <td>Totales</td>
          <td><?=$guias?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><?=$paquetes?></td>
          <td><?=$peso?></td>
          <td align="right"><?='$ '.number_format($total,2)?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
  </tr>
</table>