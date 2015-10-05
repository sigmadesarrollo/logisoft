<?
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=guiaseadExcel.xls");
	
	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
	
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
		/*background-color:#288ADB;*/
	}
	.totales{
		font-weight:bold;
		border:#ECF5FD;
		border:1px solid #5FADDC;
		/*background-color:#288ADB;*/
	}
	
</style>
	<table border="0" cellpadding="1" cellspacing="0">
    	<tr>
        	<td colspan="9" align="center" class="titulo">PAQUETERIA Y MENSAJERIA</td>
        </tr>
    	<tr>
    	  <td>REPORTE:</td>
    	  <td colspan="8">GUIAS EAD</td>
   	  </tr>
    	<tr>
    	  <td height="9px" colspan="9"></td>
      </tr>
    	<tr>
    	      <td width="90" class="cabecera" align="center">GUIA</td>
    	      <td width="65" class="cabecera" align="center">FECHA EMISION</td>
              <td width="60" class="cabecera" align="center">SUC. DEST.</td>
          	  <td width="300" class="cabecera" align="center">REMITENTE</td>
    	      <td width="300" class="cabecera" align="center">DESTINATARIO</td>
    	      <td width="300" class="cabecera" align="center">DIRECCION DEST.</td>
    	      <td width="45" class="cabecera" align="center">SECTOR</td>
    	      <td width="75" class="cabecera" align="center">TIPO FLETE</td>
    	      <td width="60" class="cabecera" align="center">IMPORTE TOTAL</td>
      </tr>
    	    <?
				$s = "SELECT * FROM (
			SELECT gv.id AS guia,DATE_FORMAT(gv.fecha,'%d/%m/%Y') fechaemision,cs.prefijo AS sucursaldestino,
			CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) remitente,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) destinatario,
			CONCAT_WS(' ',d.calle,'#',d.numero,'CP',d.cp,d.municipio,d.estado) direcciondest,gv.sector,
			IF(gv.tipoflete=0,'PAGADA','POR COBRAR') tipoflete, gv.total AS importetotal
			FROM guiasventanilla gv
			INNER JOIN catalogocliente csr ON csr.id=gv.idremitente
			INNER JOIN catalogocliente csd ON csd.id=gv.iddestinatario
			INNER JOIN catalogosucursal cs ON cs.id=gv.idsucursaldestino
			INNER JOIN direccion d ON gv.iddirecciondestinatario=d.id
			WHERE gv.estado<>'ENTREGADA' AND gv.ocurre=0 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gv.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"")."
			UNION
			SELECT gm.id AS guia,DATE_FORMAT(gm.fecha,'%d/%m/%Y') fechaemision,cs.prefijo AS sucursaldestino,
			CONCAT_WS(' ',csr.nombre,csr.paterno,csr.materno) remitente,CONCAT_WS(' ',csd.nombre,csd.paterno,csd.materno) destinatario,
			CONCAT_WS(' ',d.calle,'#',d.numero,'CP',d.cp,d.municipio,d.estado) direcciondest,gm.sector,gm.tipoflete AS tipoflete, gm.total AS importetotal
			FROM guiasempresariales gm
			INNER JOIN catalogocliente csr ON gm.idremitente=csr.id
			INNER JOIN catalogocliente csd ON gm.iddestinatario=csd.id
			INNER JOIN catalogosucursal cs ON gm.idsucursaldestino=cs.id
			INNER JOIN direccion d ON gm.iddirecciondestinatario=d.id
			WHERE  gm.estado<>'ENTREGADA' AND gm.ocurre=0 
			".(($_SESSION[IDSUCURSAL]!=1)? " AND gm.idsucursaldestino='".$_SESSION[IDSUCURSAL]."'":"").")t";
				$r = mysql_query($s,$l) or die($s);
				
				if(mysql_num_rows($r)>0){
					$total = mysql_num_rows($r);
					while($f = mysql_fetch_object($r)){
			?>
				<tr>
				  <td align="center"><?=$f->guia?></td>
				  <td align="center"><?=$f->fechaemision?></td>
				  <td align="center"><?=$f->sucursaldestino?></td>
				  <td align="left"><?=$f->remitente?></td>
				  <td align="left"><?=$f->destinatario?></td>
				  <td align="left"><?=$f->direcciondest?></td>
				  <td align="center"><?=$f->sector?></td>
				  <td align="center"><?=$f->tipoflete?></td>
				  <td align="right"><?='$ '.number_format($f->importetotal,2)?></td>
			  </tr>
			<?
				}}
			?>
    </table>