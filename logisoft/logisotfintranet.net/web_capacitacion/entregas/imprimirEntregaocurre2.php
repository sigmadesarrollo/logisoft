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
	
	$s = "SELECT  bs.Folio AS viaje,
		if(bs.conductor1 > 0,CONCAT_WS(' ',e1.nombre, e1.apellidopaterno, e1.apellidomaterno) ,
        if(bs.conductor2 > 0,CONCAT_WS(' ',e2.nombre, e2.apellidopaterno, e2.apellidomaterno) ,
        if(bs.conductor3 > 0,CONCAT_WS(' ',e3.nombre, e3.apellidopaterno, e3.apellidomaterno) ,'no hay conductor'))) as chofer,
        ct.descripcion as tipotransporte,
        cp.OrigenNombre as origen , cp.DestinoNombre ,'TINSA' as remitente,Nombre_Cliente as destinatario,DATE_FORMAT(bs.fecha, '%d/%m/%Y') AS fecha, 
		eto.observacion
		FROM 	cartaporte cp
		inner join entregasocurre eto on cp.IDEntregaOcurre = eto.nguia
		inner join recoleccion r on cp.IDRecoleccion = r.Folio
		inner join bitacorasalida bs on r.foliobitacora = bs.folio and r.folio = bs.Foliorecoleccion
        left join catalogoempleado e1 on e1.id = bs.conductor1
        left join catalogoempleado e2 on e2.id = bs.conductor2
        left join catalogoempleado e3 on e3.id = bs.conductor3
        inner join catalogounidad cu on cu.numeroeconomico = bs.unidad
        inner join catalogotipounidad ct on ct.id = cu.tipounidad 
		WHERE eto.folio = ".$_GET[folio]."";
	$t = mysql_query($s,$l) or die($s);
	$emp2 = mysql_fetch_object($t);
	
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
		font-size:8px;
		font-weight:bold;
	}
	h6 { font: 8pt Courier New; font-weight:bold; padding-top:5px; letter-spacing: 0.2em; }
	h5 { font: 8pt Courier New; font-weight:bold; padding-top:5px; letter-spacing: 0.1em; }
	.saltopagina {
      page-break-after: always;
	}
</style>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-size: 12px;
	font-weight: normal;
	font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif;
}
#bcTarget {
	font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif;
	font-weight: normal;
}
body,td,th {
	font-size: 10px;
}
-->
</style>
</head>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="../activexs/smsx.cab#Version=6,5,439,30">
</object>
<script type="text/javascript" src="../javascript/jquery.js"></script>    
<script type="text/javascript" src="http://barcode-coder.com/js/jquery-barcode-last.min.js"></script>
<script> 
 	function codigobarras(){
		$("#bcTarget").barcode("8988779699797", "code128"); 
		$("#bcTarget2").barcode("8988779699797", "code128"); 
	}
	window.onload = function (){
		codigobarras();
		enviarImpresion();
		window.setTimeout("enviarImpresion()", 500);
		window.setTimeout("cerrar()", 500);
		
	}
	function enviarImpresion(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = true;
		factory.printing.leftMargin = 1;
		factory.printing.topMargin = 15;
		factory.printing.rightMargin = 1;
		factory.printing.bottomMargin = 1;
	  	factory.printing.Print(false);
		window.close();
		
	}
	function cerrar(){
		window.close();
	}
</script>
<body> 
<table width="875" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td width="88" height="382" valign="top">
    </td>
 	<td width="812" height="382" valign="top">
	<table width="806" border="0" cellpadding="0" cellspacing="0">
	  <tr>
        <td width="806" height="5" colspan="2" align="LEFT" > 
        	<div id="bcTarget"></div>         
        </td>
      </tr>
      <tr>
      	<td colspan="2"><hr align="left" width="150" size="3" noshade="noshade" /></td>
      </tr>
       <tr>
       	<td align="right"><h6></h6></td>
       </tr>
 	  <tr>
	    <td height="7" colspan="2" style="font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif; font-size: 12px; font-weight: normal;" > 
	      Impresión de Entrega de Evidencias de Viaje 
        </td>
	  </tr>
	  <tr>
	    <td height="5" style="font-size: medium; font-weight: bold;" > 
        EMPRESA :<span style="font-size: medium; font-weight: bolder;"><?=$suc->prefijo; ?>|<?=date("d/m");?>
        </span></span></td>
	  </tr>
 	    <tr>
	    <td height="10" style="font-size: medium" > 
        </td>
	    </tr>
       <tr>
       	<td>
         <table width="747">
         	<tr>
            	<td width="772" height="10" style="font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif; font-size: 12px; font-weight: normal;">VIAJE NO:<span style="font-weight: bold; font-size: 20px;"><?=$emp2->viaje?>
            	</span></td>
            </tr>
         	<tr>
            	<td width="772" height="10" style="font-weight: bold; font-size: 20px;">FOLIO EVIDENCIAS: CONTROL DE MABE NO.<?=$_GET[folio] ?>///<span style="font-size: medium; font-weight: bolder;">
            	  <?=$suc->prefijo; ?>
            	</span></td>
            </tr>
            <tr>
            	<td style="font-weight: normal; font-size: 12px;">IMPRESION EVI: <?=date("d/m/Y H:i:s");?></td>
            </tr>
            <tr>
            	<td width="772">EVIDENCIA RECIBIO:<?=$emp->personaquerecibe?></td>         
            </tr>
       <tr>
       	<td height="20"></td>
       </tr>
            <tr>
            	<td style="font-weight: bold; font-size: 12px;">FECHA VIAJE: <?=$emp2->fecha?></td>
            </tr>
             <tr>
            	<td width="772">CHOFER: <?=$emp2->chofer?></td>         
            </tr>
            <tr>
            	<td width="772">POBLACION: <?=$emp2->DestinoNombre?>
            	</td>         
            </tr>
             <tr>
            	<td width="772">TIPO TRASNP: <?=$emp2->tipotransporte?></td>         
            </tr>
            <tr>
            	<td width="772">OBSERVACIONES: <?=$emp2->observacion?></td>         
            </tr>
            <tr>
              <td colspan="2"><hr /></td>
            </tr>
             	    <tr>
	    <td height="30" style="font-size: medium" > 
        </td>
         </table>
 	  <tr>
        <td width="806" height="5" colspan="2" align="LEFT" > 
        	<div id="bcTarget2"></div>         
        </td>
      </tr>
      <tr>
      	<td colspan="2"><hr align="left" width="150" size="3" noshade="noshade" /></td>
      </tr>
       <!-- SEGUNDA PARTE DE LA HOJAS -->
       <tr>
       	<td height="30" align="right"></td>
       </tr>
 	  <tr>
	    <td height="7" colspan="2" style="font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif; font-size: 12px; font-weight: normal;" > 
	      Impresión de Contra Recibo de Viaje 
        </td>
	    </tr>
	  <tr>
	    <td height="5" style="font-size: medium; font-weight: bolder;" > 
        EMPRESA :<span style="font-size: medium; font-weight: bolder;"><?=$suc->prefijo; ?></span></span><span style="font-size: medium; font-weight: bold;">|<?=date("d/m");?>
        </span></span></td>
	    </tr>
 	    <tr>
	    <td height="10" style="font-size: medium" > 
        </td>
	    </tr>
       <tr>
       	<td>
         <table width="754">
         	<tr>
            	<td width="790" height="10" style="font-family: 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif; font-size: 12px; font-weight: normal;">
                VIAJE NO:<?=$emp2->viaje?>
                </td>
            </tr>
         	<tr>
            	<td width="790" height="10" style="font-weight: bold; font-size: 20px;">FOLIO EVIDENCIAS: CONTROL DE MABE NO.
            	  <?=$_GET[folio] ?>
            	  ////<span style="font-size: medium; font-weight: bolder;">
                  <?=$suc->prefijo; ?>
                </span></td>
            </tr>
            <tr>
            	<td style="font-weight: normal; font-size: 12px;">IMPRESION EVI: <?=date("d/m/Y H:i:s");?></td>
            </tr>
            <tr>
            	<td width="790">EVIDENCIA RECIBIO: <?=$emp->personaquerecibe?></td>         
            </tr>
       <tr>
       	<td height="20"></td>
       </tr>
           <tr>
            	<td style="font-weight: bold; font-size: 12px;">FECHA VIAJE: <?=$emp2->fecha?></td>
            </tr>
             <tr>
            	<td width="772">CHOFER: <?=$emp2->chofer?></td>         
            </tr>
            <tr>
            	<td width="772">POBLACION: <?=$emp2->DestinoNombre?>
            	</td>         
            </tr>
             <tr>
            	<td width="772">TIPO TRASNP: <?=$emp2->tipotransporte?></td>         
            </tr>
            <tr>
            	<td width="772">OBSERVACIONES: <?=$emp2->observacion?></td>         
            </tr>
          </table> 

    </table>
	</td>
</tr>
</table>
<!--<input name="sucursal1" type="hidden" id="sucursal1" value="011" />-->
<!--<input name="oculto" type="hidden" id="oculto3" value="<?=$oculto ?>" />
<input name="oculto" type="hidden" id="oculto3" value="<?=$oculto ?>" />
<input name="oculto" type="hidden" id="oculto3" value="<?=$oculto ?>" />
<input name="oculto" type="hidden" id="oculto3" value="<?=$oculto ?>" />-->
</body>
</html>
