<?
	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../estilos_impresion.css" rel="stylesheet" type="text/css" />
</head>
<?
	$_GET[codigo] = "560000000001A";
	$_SESSION[IDSUCURSAL] = 2;
	
	$s = "SELECT
	gv.id, gr.numerorastreo, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha, 
	DATE_FORMAT(ADDDATE(gv.fecha, INTERVAL IF(gv.ocurre=0, entregaead/24, entregaocurre/24) DAY), '%d/%m/%y') AS estimado,
	IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega,
	csd.prefijo AS destino, cso.prefijo AS origen,
	DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
	gv.totalpaquetes, gv.totalvolumen, totalpeso, 
	gv.tflete, gv.trecoleccion, gv.tcostoead, gv.tseguro,
	gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
	gv.total, gv.valordeclarado, DATE_FORMAT(gv.hora_registro, '%H:%i') AS hora,
	gv.idremitente, 
	CONCAT_WS(' ', ccr.nombre, ccr.paterno, ccr.materno) AS rncliente, ccr.rfc AS rrfc, ccr.celular AS rcelular,
	dr.calle AS rcalle, dr.numero AS rnumero, dr.cp AS rcp, dr.colonia AS rcolonia, 
	dr.poblacion AS rpoblacion, dr.telefono AS rtelefono,
	gv.iddestinatario,
	CONCAT_WS(' ', ccd.nombre, ccd.paterno, ccd.materno) AS dncliente, ccd.rfc AS drfc, ccd.celular AS dcelular,
	dd.calle AS dcalle, dd.numero AS dnumero, dd.cp AS dcp, dd.colonia AS dcolonia, 
	dd.poblacion AS dpoblacion, dd.telefono AS dtelefono
	FROM guiasventanilla AS gv
	INNER JOIN catalogosucursal AS csd ON gv.idsucursaldestino = csd.id
	INNER JOIN catalogosucursal AS cso ON gv.idsucursalorigen = cso.id
	INNER JOIN catalogodestino AS cd ON gv.iddestino = cd.id
	LEFT JOIN guia_rastreo AS gr ON gv.id = gr.noguia
	INNER JOIN catalogocliente AS ccr ON gv.idremitente = ccr.id
	LEFT JOIN direccion AS dr ON gv.iddireccionremitente = dr.id
	INNER JOIN catalogocliente AS ccd ON gv.iddestinatario = ccd.id
	INNER JOIN direccion AS dd ON gv.iddirecciondestinatario = dd.id
	WHERE gv.id= '$_GET[codigo]'";
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);
?>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="ScriptX.cab#Version=6,5,439,30">
</object>
<script> 
	window.onload = function (){
		enviarImpresion();
	}

	function enviarImpresion(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = false;
		factory.printing.leftMargin = 5.0;
		factory.printing.topMargin = 5.0;
		factory.printing.rightMargin = 0;
		factory.printing.bottomMargin = 0;
	  	//factory.printing.Print(false);
		/*opener.cambiarImpresora1();
		window.close();*/
		
	}
	
</script>
<body>
<table width="532" border="0" cellpadding="0" cellspacing="0">
<tr>
   	<td width="266" height="150" valign="top"><table width="265" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">
      <tr>
        <td height="57" align="left">
        	<table width="263" border="0" cellpadding="0" cellspacing="0">
   	  <tr>
               	<td width="57"><img src="../img/logopmmazul.png" width="52" height="49" /></td>
                    <td width="206" class="titulo_cliente">&nbsp;&nbsp;&nbsp;CLIENTE</td>
              </tr>
          </table></td>
      </tr>
      <tr>
        <td align="left"><table class="texto_bold5" cellpadding="0" cellspacing="0" border="0">
            <?
				$s = "SELECT UPPER(CONCAT_WS(' ',calle, numero, colonia)) AS direccion, 
				UPPER(CONCAT(poblacion,', ', estado)) AS estadociudad FROM catalogosucursal
				WHERE id = $_SESSION[IDSUCURSAL]";
				$rx = mysql_query($s,$l) or die($s);
				$fx = mysql_fetch_object($rx);
			?>
            <tr>
              <td width="261">PAQUETERIA Y MENSAJERIA EN MOVIMIENTO S.A. DE C.V.</td>
            </tr>
            <tr>
              <td><?=$fx->direccion?></td>
            </tr>
            <tr>
              <td><?=$fx->estadociudad?></td>
            </tr>
            <tr>
              <td>PMM9087545145</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td class="texto_bold">REMITENTE</td>
      </tr>
      <tr>
        <td class="texto_bold4"><?=$f->rncliente?></td>
      </tr>
      <tr>
        <td>CLTE:
            <?=$f->idremitente?></td>
      </tr>
      <tr>
        <td>CALLE:
          <?=$f->rcalle?>
          NO
          <?=$f->rnumero?></td>
      </tr>
      <tr>
        <td>COL:
          <?=$f->rcolonia?>
          C.P.
          <?=$f->rcp?></td>
      </tr>
      <tr>
        <td>CD:
          <?=$f->rpoblacion?></td>
      </tr>
      <tr>
        <td>TEL:
          <?=$f->rtelefono?></td>
      </tr>
      <tr>
        <td height="4px"></td>
      </tr>
      <tr>
        <td class="texto_bold">DESTINATARIO</td>
      </tr>
      <tr>
        <td class="texto_bold4"><?=$f->dncliente?></td>
      </tr>
      <tr>
        <td>CLTE:
            <?=$f->iddestinatario?></td>
      </tr>
      <tr>
        <td>CALLE:
          <?=$f->dcalle?>
            <?=$f->dnumero?></td>
      </tr>
      <tr>
        <td>COL:
          <?=$f->dcolonia?>
          C.P.
          <?=$f->dcp?></td>
      </tr>
      <tr>
        <td>CD:
          <?=$f->dpoblacion?></td>
      </tr>
      <tr>
        <td>TEL:
          <?=$f->dtelefono?></td>
      </tr>
      <tr>
        <td height="5px"></td>
      </tr>
      <tr>
        <td height="5px" align="center"><table width="264" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="62" class="texto_normal" align="left">ORIGEN</td>
              <td width="67" class="texto_bold" align="left"><?=$f->origen?></td>
              <td width="60" class="texto_normal" align="left">DESTINO</td>
              <td width="71" class="texto_bold" align="left"><?=$f->destino?></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td class="texto_bold4">TIPO DE ENTREGA:
          <?=$f->tipoentrega?></td>
      </tr>
      <tr>
        <td class="texto_bold4">
        TIEMPO DE ENTREGA ESTIMADO: <?=$f->estimado?>
        </td>
      </tr>
</table>    </td>
        <td width="266" valign="top">
      <table width="263" border="0" cellpadding="0" cellspacing="0">
          <tr>
       	    <td class="texto_bold"></td>
   	  </tr>
        	<tr>
        	  <td valign="top">
              <table width="262" border="0" cellpadding="0" cellspacing="0" class="texto_normal2">
   	  <tr>
                	<td width="63" class="texto_bold2">FECHA</td>
                	<td width="70">: <?=$f->fecha?></td>
                	<td width="57"><?=$f->hora?></td>
                	<td width="70">&nbsp;</td>
                </tr>
   	  <tr>
   	    <td colspan="4" class="texto_bold2">
        <table width="260" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="64">PAQUETES:</td>
                <td width="29" class="texto_normal2"><?=$f->totalpaquetes?></td>
                <td width="41">P.VOL:</td>
                <td width="44" class="texto_normal2" align="right"><?=$f->totalvolumen?>&nbsp;</td>
                <td width="39">P. KG:</td>
                <td width="43" class="texto_normal2" align="right"><?=$f->totalpeso?>&nbsp;</td>
            </tr>
        </table></td>
</tr>
		<?
			$s = "SELECT * FROM guiaventanilla_detalle WHERE idguia = '$_GET[codigo]'";
			$rd = mysql_query($s,$l) or die($s);
			$fd = mysql_fetch_object($rd);
		?>
	  <tr>
   	    <td colspan="4" class="texto_bold2">DICE CONTENER: </td>
</tr>
<tr>
   	    <td colspan="4" class="texto_normal2">
        <table width="261" border="0" cellpadding="1" cellspacing="0">
        <tr>
        <td colspan="2" class="texto_bold5">&nbsp;&nbsp;
          <?=$fd->cantidad?></td>
        <td width="94" class="texto_bold5"><?=$fd->descripcion?></td>
        <td width="129" class="texto_bold5"><?=$fd->contenido?></td>
        </tr>
        <tr>
        <? $fd = mysql_fetch_object($rd); ?>
        <td colspan="2" class="texto_bold5">&nbsp;&nbsp;
          <?=$fd->cantidad?></td>
        <td class="texto_bold5"><?=$fd->descripcion?></td>
        <td class="texto_bold5"><?=$fd->contenido?></td>
        </tr>
        <tr>
        <? $fd = mysql_fetch_object($rd); ?>
        <td colspan="2" class="texto_bold5">&nbsp;&nbsp;
          <?=$fd->cantidad?></td>
        <td class="texto_bold5"><?=$fd->descripcion?></td>
        <td class="texto_bold5"><?=$fd->contenido?></td>
        </tr>
        <tr>
        <? $fd = mysql_fetch_object($rd); ?>
        <td colspan="2" class="texto_bold5">&nbsp;&nbsp;
          <?=$fd->cantidad?></td>
        <td class="texto_bold5"><?=$fd->descripcion?></td>
        <td class="texto_bold5"><?=$fd->contenido?></td>
        </tr>
        <tr>
        <? $fd = mysql_fetch_object($rd); ?>
        <td colspan="2" class="texto_bold5">&nbsp;&nbsp;
          <?=$fd->cantidad?></td>
        <td class="texto_bold5"><?=$fd->descripcion?></td>
        <td class="texto_bold5"><?=$fd->contenido?></td>
        </tr>
        <tr>
        <? $fd = mysql_fetch_object($rd); ?>
        <td colspan="2" class="texto_bold5">&nbsp;&nbsp;
          <?=$fd->cantidad?></td>
        <td class="texto_bold5"><?=$fd->descripcion?></td>
        <td class="texto_bold5"><?=$fd->contenido?></td>
        </tr>
</table></td>
</tr>
<tr>
   	    <td colspan="2" class="texto_bold4">CARGO COMBUSTIBLE:</td>
<td align="right" class="texto_bold4">$</td>
   	    <td align="right" class="texto_bold4">&nbsp;</td>
 	    </tr>
   	  <tr>
   	    <td colspan="2"  class="texto_bold4">FLETE:</td>
   	    <td align="right" class="texto_bold4">$</td>
   	    <td align="right" class="texto_bold4"><?=number_format($f->tflete, 2, '.',',')?></td>
 	  </tr>
      
   	  <tr>
   	    <td colspan="2" class="texto_bold4">RECOLECCION:</td>
   	    <td align="right" class="texto_bold4">$</td>
   	    <td align="right" class="texto_bold4"><?=number_format($f->trecoleccion, 2, '.',',')?></td>
 	  </tr>
      
   	  <tr>
   	    <td colspan="2" class="texto_bold4">EAD:</td>
   	    <td align="right" class="texto_bold4">$</td>
   	    <td align="right" class="texto_bold4"><?=number_format($f->tcostoead, 2, '.',',')?></td>
 	  </tr>
      
   	  <tr>
   	    <td colspan="2" class="texto_bold4">SEGURO:</td>
   	    <td align="right" class="texto_bold4">$</td>
   	    <td align="right" class="texto_bold4"><?=number_format($f->tseguro, 2, '.',',')?></td>
 	  </tr>
   	  <tr>
   	    <td colspan="2" class="texto_bold4">IVA:</td>
   	    <td align="right" class="texto_bold4">$</td>
   	    <td align="right" class="texto_bold4"><?=number_format($f->tiva, 2, '.',',')?></td>
 	    </tr>
   	  <tr>
   	    <td colspan="2" class="texto_bold4">IVA RET:</td>
   	    <td align="right" class="texto_bold4">$</td>
   	    <td align="right" class="texto_bold4"><?=number_format($f->tivaretenido, 2, '.',',')?></td>
 	    </tr>
   	  <tr>
   	    <td colspan="2" class="texto_bold4">TOTAL:</td>
   	    <td align="right" class="texto_bold4">$</td>
   	    <td align="right" class="texto_bold4"><?=number_format($f->total, 2, '.',',')?></td>
 	    </tr>
              </table>              </td>
      	  </tr>
        	<tr>
        	  <td height="18" align="center"><?
			  echo "<table border=0px cellspacing=0 cellpadding=0><tr><td>
			  <img width=260 src='../codigobarrasnuevo/image.php?code=$_GET[codigo]&style=68&type=C128A&width=370&height=115&xres=2&font=4''>
			  </td></tr></table>";
			 ?>              </td>
      	  </tr>
        	<tr>
        	  <td height="14" class="texto_bold2" align="center"><table width="259" border="0" cellpadding="0" cellspacing="0">
			  	<tr>
					<td width="128" align="center">NUMERO DE GUIA</td>
					<td width="131" align="center">NUMERO DE RASTREO</td>
				</tr>
				<tr>
					<td align="center"><?=$f->id?></td>
					<td align="center"><?=$f->numerorastreo?></td>
				</tr>
			  </table>			  </td>
      	  </tr>
    </table>        </td>
  </tr>
  <?
  	if($f->valordeclarado==""){
  ?>
<tr>
  <td height="19" colspan="2" valign="MIDDLE" class="texto_bold2" align="CENTER">*Este envio viaja a cuenta y riesgo del remitente por no declarar valor</td>
</tr>
 <?
	}
 ?>
</table>
<p>&nbsp;</p>
<table width="261" border="0" cellpadding="0" cellspacing="0" class="texto_bold4">
<tr>
   	<td width="66">flete</td>
    <td width="10">$</td>
    <td width="53" align="right"></td>
    <td width="57">desc.</td>
    <td width="10">$</td>
        <td width="65" align="right"></td>
    </tr>
<tr>
  <td>ead</td>
  <td>$</td>
  <td align="right"></td>
  <td>reco.</td>
  <td>$</td>
  <td align="right"></td>
</tr>
<tr>
  <td>seguro</td>
  <td>$</td>
  <td align="right"></td>
  <td>otros</td>
  <td>$</td>
  <td align="right"></td>
</tr>
<tr>
  <td>EXCed.</td>
  <td>$</td>
  <td align="right"></td>
  <td>COMBUS.</td>
  <td>$</td>
  <td align="right"><?=number_format($f->tcombustible, 2, '.',',')?></td>
</tr>
<tr>
  <td>SUBTOTAL</td>
  <td>$</td>
  <td align="right"></td>
  <td>IVA</td>
  <td>$</td>
  <td align="right"></td>
</tr>
<tr>
  <td>IVARET.</td>
  <td>$</td>
  <td align="right"></td>
  <td>TOTAL</td>
  <td>$</td>
  <td align="right"></td>
</tr>
</table>
</body>
</html>
