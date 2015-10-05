<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$folio=$_POST[folio];
	
	$cobrador=$_POST[cobrador];
	$hora=date('d/m/Y h:i a');
	
	$s="SELECT CONCAT('DEL DIA DE',' ',
             CASE DAYOFWEEK('".cambiaf_a_mysql($_GET[fecha])."')
                  WHEN 1 THEN 'DOMINGO'
                  WHEN 2 THEN 'LUNES'
                  WHEN 3 THEN 'MARTES'
                  WHEN 4 THEN 'MIERCOLES'
                  WHEN 5 THEN 'JUEVES'
                  WHEN 6 THEN 'VIERNES'
                  WHEN 7 THEN 'SABADO'
             END,' ',DAY('".cambiaf_a_mysql($_GET[fecha])."'),' ','DE',' ',
		CASE MONTH('".cambiaf_a_mysql($_GET[fecha])."')
		WHEN 1 THEN 'ENERO' 
		WHEN 2 THEN 'FEBRERO' 
		WHEN 3 THEN 'MARZO' 
		WHEN 4 THEN 'ABRIL' 
		WHEN 5 THEN 'MAYO' 
		WHEN 6 THEN 'JUNIO' 
		WHEN 7 THEN 'JULIO' 
		WHEN 8 THEN 'AGOSTO' 
		WHEN 9 THEN 'SEPTIEMBRE' 
		WHEN 10 THEN 'OCTUBRE' 
		WHEN 11 THEN 'NOVIEMBRE' 
		WHEN 12 THEN 'DICIEMBRE' 
		END,' ','DEL',' ',YEAR('".cambiaf_a_mysql($_GET[fecha])."')) AS dia";
		$r=mysql_query($s,$l)or die($s); 
		$f=mysql_fetch_object($r);
		$dia=$f->dia;
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
H1.SaltoDePagina{
 	PAGE-BREAK-AFTER: always;
}
</style>
<link href="../estilos_impresion.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo3 {font-size: 12px}
-->
</style>
</head>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="http://pmmintranet.net/web/activexs/smsx.cab#Version=6,5,439,30">
</object>
<script> 
	window.onload = function(){
		Imprimir();
	}
	function Imprimir(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = false;
		factory.printing.leftMargin = 5.0;
		factory.printing.topMargin = 5.0;
		factory.printing.rightMargin = 5.0;
		factory.printing.bottomMargin = 2.0;
	  	factory.printing.Print(false);
		window.close();
	}
</script>
<body>
<table width="1527" height="164" border="1" cellpadding="0" cellspacing="0">
<tr>
  <td width="177" height="72" valign="top"><img src="../img/logopmmazul.png" /></td>
  <td width="1350" valign="top"><table width="1312" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="310" height="19" align="center" class="titulo_cliente">&nbsp;</td>
    </tr>
    <tr>
      <td height="19" align="center" class="titulo_cliente"><span class="texto_normal3">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO </span></td>
    </tr>
    <tr>
      <td height="19" align="center" class="titulo_cliente"><span class="texto_normal3">RELACION DE COBRANZA</span></td>
    </tr>
    <tr>
      <td height="54" align="center" class="titulo_cliente"><table width="1292" height="55" border="0" cellpadding="0" cellspacing="0" class="texto_normal2">
          <tr>
            <td width="127" class="texto_bold2" align="left">FOLIO:              </td>
            <td width="164" align="left">
              <?=$_GET[folio] ?>            </td>
            <td width="1001" align="left" colspan="2">Fecha y hora de impresion:
              <?=$hora ?></td>
            </tr>
          <tr>
            <td class="texto_bold2" align="left">Dia:              </td>
            <td colspan="3" align="left">
              <?=$dia ?>
            </td>
            </tr>
          <tr>
            <td class="texto_bold2" align="left">Cobrador:              </td>
            <td colspan="3" class="texto_bold2" align="left"><?=$_GET[cobrador] ?>
              </td>
            </tr>
      </table></td>
    </tr>
  </table></td>
</tr>
<tr>
  <td height="19" colspan="2" valign="top">
  	<table width="1637" cellpadding="0" cellspacing="0" border="0">
    	<tr>
    	  <td width="96" align="center" class="texto_bold2">ESTADO</td>
          <td width="524" align="center" class="texto_bold2">CLIENTE</td>
          <td width="529" align="center" class="texto_bold2">DIRECCION</td>
          <td width="71" align="center"  class="texto_bold2">FACTURA</td>
          <td width="70" align="center" class="texto_bold2">FECHA</td>
          <td width="85" align="center" class="texto_bold2">F.VENCIMIENTO</td>
          <td width="132" align="center" class="texto_bold2">IMPORTE</td>
          <td width="130" height="30" align="center" class="texto_bold2">OBSERVACIONES</td>
    </tr>
	<tr>
    	  <td colspan="8" align="center" class="titulo_cliente Estilo3"> <hr /></td>
    </tr>
	<?
	
	
		$s="SELECT estado,cliente,direccion,CONCAT('FA-',factura) AS factura,fechaguia,fechavencimiento,importe FROM (
		SELECT IF(rd.estado='No Revisadas','REVISION','COBRANZA')AS estado,
		CONCAT(f.nombrecliente,' ',f.apellidopaternocliente,' ',f.apellidomaternocliente) AS cliente,
		CONCAT(f.calle,' ',f.numero,' ',f.colonia,' ',f.poblacion)AS direccion,
		rd.factura, DATE_FORMAT(rd.fechaguia,'%d/%m/%Y') AS fechaguia, 
		DATE_FORMAT(rd.fechavencimiento,'%d/%m/%Y')AS fechavencimiento, rd.importe ,'' as observaciones
		FROM relacioncobranza r
		INNER JOIN relacioncobranzadetalle rd ON r.folio = rd.relacioncobranza
		INNER JOIN facturacion f ON rd.factura=f.folio 
		WHERE r.folio = ".$_GET[folio]."
		GROUP BY rd.factura)tabla ORDER BY estado,factura";
		$r = mysql_query($s, $l) or die($s);
	while($f = mysql_fetch_object($r)){
?>
    <tr>
      <td align="left" class="texto_bold2"><?=$f->estado?></td>
      <td align="left" class="texto_bold2"><?=$f->cliente?></td>
      <td align="left" class="texto_bold2"><?=$f->direccion?></td>
      <td align="center" class="texto_bold2"><?=$f->factura?></td>
      <td align="center" class="texto_bold2"><?=$f->fechaguia?></td>
      <td align="center" class="texto_bold2"><?=$f->fechavencimiento?></td>
      <td align="right" class="texto_bold2"><?=$f->importe?>&nbsp;</td>
      <td height="14" align="center" class="texto_bold2"><?=$f->observaciones?></td>
    </tr>
  <?
	}
?>
    </table>  </td>
</tr>
</table>
</body>
</html>
