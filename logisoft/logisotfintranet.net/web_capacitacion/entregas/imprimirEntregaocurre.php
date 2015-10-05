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
}
-->
</style></head>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="https://www.pmmintranet.net/software/smsx.cab#Version=6,5,439,30">
</object>
<script> 
	window.onload = function (){
		enviarImpresion();
		window.setTimeout("enviarImpresion()", 500);
		window.setTimeout("cerrar()", 500);
	}
	function enviarImpresion(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = true;
		factory.printing.leftMargin = 0.5;
		factory.printing.topMargin = 0;
		factory.printing.rightMargin = 1;
		factory.printing.bottomMargin = 0;
	  	factory.printing.Print(false);
		//window.close();
	}
	function cerrar(){
		window.close();
	}
</script>
<body>
<table width="235" border="0" cellpadding="0" cellspacing="0">
<tr>
 	<td width="235" height="382" valign="top">
	<table width="219" border="0" cellpadding="0" cellspacing="0">
	  <tr>
        <td colspan="2" align="center" height="5" ><h4>Entrega de carga
        </h4>
          </td>
      </tr>
      <tr>
      	<td colspan="2"><hr /></td>
      </tr>
	  <tr>
	    <td colspan="2" align="center" height="5" ><h6>&nbsp;</h6></td>
	    </tr>
       <tr>
       	<td align="right"><h6>&nbsp;</h6></td>
       </tr>
       <tr>
       	<td>
         <table width="219" border="0" cellpadding="0" cellspacing="0">
         	<tr>
            	<td width="56"><h6>SUCURSAL:</h6></td><td width="143" align="center"><h6><?=$suc->prefijo; ?></h6></td>
            </tr>
         	<tr>
            	<td width="56"><h6>FECHA:</h6></td><td width="143" align="center"><h6><?=date("d/m/Y");?></h6></td>
            </tr>
            <tr>
            	<td><h6>FOLIO:</h6></td><td align="center"><h6><?=$_GET[folio] ?></h6></td>
            </tr>
            <tr>
              <td colspan="2"><hr /></td>
            </tr>
         </table>
         <h6>
        DETALLE:
          <table width="219" border="0" cellpadding="0" cellspacing="0">
        <?
			/*$s = "(SELECT gv.id AS folioguia, IF(gv.tipoflete=0,0,gv.total) AS cantidad, gv.totalpaquetes
			FROM entregasocurre_detalle AS eo
			INNER JOIN guiasventanilla AS gv ON eo.guia = gv.id
			WHERE eo.entregaocurre = '$_GET[folio]' and eo.sucursal = ".$_SESSION[IDSUCURSAL].")
			UNION
			(SELECT gv.id AS folioguia, IF(gv.tipoflete=0,0,gv.total) AS cantidad, gv.totalpaquetes
			FROM entregasocurre_detalle AS eo
			INNER JOIN guiasempresariales AS gv ON eo.guia = gv.id
			WHERE eo.entregaocurre = '$_GET[folio]' AND eo.sucursal = ".$_SESSION[IDSUCURSAL].")";*/
			$s = "SELECT  cp.Folio as folioguia, sum(LARGO) AS cantidad, SUM(pesototal) as totalpaquetes
					FROM cartaporte cp 
					inner join recoleccion r on cp.IDRecoleccion = r.Folio
					inner join recolecciondetalle rd on r.folio = rd.recoleccion
					inner join bitacorasalida bs on r.foliobitacora = bs.folio and r.folio = bs.Foliorecoleccion
					where bs.status = 1  and  IDEntregaOcurre = '$_GET[folio]'";
			$r = mysql_query($s,$l) or die($s);
			$total = 0;
			while($f = mysql_fetch_object($r)){
				$total += $f->cantidad;
		?>
   		<tr>
   		  <td width="85" align="center" >CARTA PORTE:</td>
   		  <td width="71" align="center" >PESO TOTAL:</td>
   		  <td width="63" align="center" >IMPORTE:</td>
        </tr>
        <tr>
   		<tr>
   		  <td width="85" align="center" ><h6> <?=$f->folioguia?><br /><br /></h6></td>
   		  <td width="71" align="center"  ><h6> <?=$f->totalpaquetes?></h6></td>
   		  <td width="63" align="center"  ><h6>  $ <?=number_format($f->cantidad,2,'.',',')?><br /><br /></h6></td></tr>
        <tr>
   		  <td colspan="3">
           <table width="216" border="0px" cellpadding="0px" cellspacing="0px">
           	<?
				$s = " SELECT   cantidad,concat(descripcion,'  ',contenido) as descripcion
						FROM cartaporte cp 
						inner join recoleccion r on cp.IDRecoleccion = r.Folio
						inner join recolecciondetalle rd on r.folio = rd.recoleccion
						inner join bitacorasalida bs on r.foliobitacora = bs.folio and r.folio = bs.Foliorecoleccion
						where bs.status = 1  and FolioCarta = '$f->folioguia'";
				$rd = mysql_query($s,$l) or die($s);
				while($fd = mysql_fetch_object($rd)){
			?>
                <tr>
                    <td width="28" height="20"><h6><?=$fd->cantidad?><br /></h6></td>
                    <td width="188"><h6><?=$fd->descripcion?></h6></td>
                </tr>
            <?
				}
			?>
           </table>
          </td>
   		</tr>
        <tr>
		<? } ?>
   		  <td align="left"><h6>TOTAL:<br /><br /></h6></td>
   		  <td align="right"><h6><?=$paquetes?></h6></td>
   		  <td width="63" align="right"><h6>$ <?=number_format($total,2,'.',',')?><br /><br /></h6></td></tr>
          </table>
        <table width="219" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td colspan="2"><hr /></td>            
            </tr>
        	<tr>
            	<td width="56"><h6>RECIBE:</h6></td>
                <td width="143"></td>            
            </tr>
        	<tr>
        	  <td colspan="2" align="right"><h6><?=$emp->personaquerecibe?></h6></td>
          </tr>
              <tr>
        	  <td><h6>&nbsp;</h6></td>
        	  <td></td>
      	  </tr>
           
        	<tr>
        	  <td><h6>ELABORO:</h6></td>
        	  <td></td>
      	  </tr>
        	<tr>
        	  <td colspan="2" align="right"><h6><?=cambio_texto($emp->elaboro); ?></h6></td>
        	  </tr>
        	<tr>
        	  <td></td>
        	  <td>&nbsp;</td>
      	  </tr>
        </table></h6></td>
      </tr>      	   	  
	 <tr>
	    <td colspan="2" >&nbsp;</td>
	    </tr>
	  <tr>
	    <td colspan="2" >&nbsp;</td>
	    </tr>
	  <tr>
        <td colspan="2" ><hr /></td>
      </tr> 
    </table>
	</td>
</tr>
</table>
</body>
</html>
