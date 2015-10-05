<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	$s = "SELECT CONCAT_WS(' ',catalogoempleado.nombre, catalogoempleado.apellidopaterno, catalogoempleado.apellidopaterno) AS elaboro,
	entregasocurre.folio, entregasocurre.personaquerecibe
	FROM entregasocurre
	INNER JOIN catalogoempleado ON entregasocurre.idusuario = catalogoempleado.id 
	WHERE entregasocurre.folio = ".$_GET[folio]." and entregasocurre.idsucursal = ".$_SESSION[IDSUCURSAL]."";
	$t = mysql_query($s,$l) or die($s);
	$emp = mysql_fetch_object($t);
	
	$s = "SELECT SUM(paquetes) dat FROM (
		SELECT SUM(gv.totalpaquetes) paquetes
		FROM guiasventanilla gv
		INNER JOIN entregasocurre_detalle ed ON gv.id = ed.guia
		WHERE ed.entregaocurre = ".$_GET[folio]." AND ed.sucursal = ".$_SESSION[IDSUCURSAL]."
		UNION
		SELECT SUM(gv.totalpaquetes) paquetes
		FROM guiasempresariales gv
		INNER JOIN entregasocurre_detalle ed ON gv.id = ed.guia
		WHERE ed.entregaocurre = ".$_GET[folio]." AND ed.sucursal = ".$_SESSION[IDSUCURSAL]."
	) t1";
	$r = mysql_query($s,$l) or die($s); 
	$f = mysql_fetch_object($r);
	$paquetes = $f->dat;
	
	$s = "SELECT prefijo FROM catalogosucursal WHERE id=$_SESSION[IDSUCURSAL]";
	$r = mysql_query($s,$l) or die($s); $suc = mysql_fetch_object($r);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css" media="print">
	.fuente{
		font-family:"Courier New", Courier, monospace;
		font-size:5px;
	}
	h6 { font: 5pt Courier New; padding-top:5px; letter-spacing: 0.4em; }
	h5 { font: 5pt Courier New; padding-top:5px; letter-spacing: 0.3em; }

</style>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="http://pmmintranet.net/web/activexs/smsx.cab#Version=6,5,439,30">
</object>
<script> 
	window.onload = function (){
		enviarImpresion();
	}
	function enviarImpresion(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = true;
		factory.printing.leftMargin = 0.5;
		factory.printing.topMargin = 0;
		factory.printing.rightMargin = 1;
		factory.printing.bottomMargin = 0;
	  	factory.printing.Print(true);
		//window.close();
	}
</script>
<body>
<table width="200" border="0" cellpadding="0" cellspacing="0">
<tr>
 	<td width="266" height="382" valign="top">
	<table width="200" border="0" cellpadding="0" cellspacing="0">
	  <tr>
        <td colspan="2" align="center" height="5" ><h6>PAQUETERIA Y <br /><br /><br />
        MENSAJERIA&nbsp;<br /><br /><br /><br />
        </h6>
          </td>
      </tr>
      <tr>
      	<td colspan="2"><hr /></td>
      </tr>
	  <tr>
	    <td colspan="2" align="center" height="5" ><h6><br />
	      <br />
	      <br />
	      <br />
	      <br />
SALIDA DE<br />
<br />
<br />
MERCANCIA OCURRE</h6></td>
	    </tr>
       <tr>
       	<td align="right"><h6>&nbsp;</h6></td>
       </tr>
       <tr>
       	<td>
         <table width="199" border="0" cellpadding="0" cellspacing="0">
         	<tr>
            	<td width="56"><h6>SUCURSAL:</h6></td><td width="143" align="right"><h6><?=$suc->prefijo; ?></h6></td>
            </tr>
         	<tr>
            	<td width="56"><h6>FECHA:</h6></td><td width="143" align="right"><h6><?=date("d/m/Y");?></h6></td>
            </tr>
            <tr>
            	<td><h6>FOLIO:</h6></td><td align="right"><h6><?=$_GET[folio] ?></h6></td>
            </tr>
            <tr>
              <td colspan="2"><hr /></td>
            </tr>
         </table>
         <h6>
        DETALLE:
          <table wid