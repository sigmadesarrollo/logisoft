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
	if($_GET[tipo]==1){//GUIAS DE VENTANILLA
	$s = "SELECT
	gv.id, gr.numerorastreo, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha, 
	DATE_FORMAT(ADDDATE(gv.fecha, INTERVAL IF(gv.ocurre=0, entregaead/24, entregaocurre/24) DAY), '%d/%m/%y') AS estimado,
	IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega,
	csd.prefijo AS destino, cso.prefijo AS origen,
	DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
	gv.totalpaquetes, gv.totalvolumen, totalpeso, 
	gv.tflete, gv.trecoleccion, gv.tcostoead, gv.tseguro,
	gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
	gv.ttotaldescuento, gv.totros, gv.texcedente, gv.tcombustible,
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
		
	}else if($_GET[tipo]==2){//GUIAS EMPRESARIALES
	
	}else if($_GET[tipo]==3){//CORREO INTERNO
		$s = "SELECT gv.id, gr.numerorastreo, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha, 
		DATE_FORMAT(ADDDATE(gv.fecha, INTERVAL IF(gv.ocurre=0, entregaead/24, entregaocurre/24) DAY), '%d/%m/%y') AS estimado,
		IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega,
		csd.prefijo AS destino, cso.prefijo AS origen,
		DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
		gv.totalpaquetes, gv.totalvolumen, totalpeso, 
		gv.tflete, gv.trecoleccion, gv.tcostoead, gv.tseguro,
		gv.tcombustible, gv.subtotal, gv.tiva, gv.ivaretenido, 
		gv.ttotaldescuento, gv.totros, gv.texcedente, gv.tcombustible,
		gv.total, gv.valordeclarado, DATE_FORMAT(gv.hora_registro, '%H:%i') AS hora,
		gv.idremitente, 
		CONCAT_WS(' ', ccr.nombre, ccr.apellidopaterno, ccr.apellidomaterno) AS rncliente,
		ccr.rfc AS rrfc, gv.iddestinatario,
		CONCAT_WS(' ', ccd.nombre, ccd.apellidopaterno, ccd.apellidomaterno) AS dncliente,
		ccd.rfc AS drfc 
		FROM guiasventanilla AS gv
		INNER JOIN catalogosucursal AS csd ON gv.idsucursaldestino = csd.id
		INNER JOIN catalogosucursal AS cso ON gv.idsucursalorigen = cso.id
		INNER JOIN catalogodestino AS cd ON gv.iddestino = cd.id
		LEFT JOIN guia_rastreo AS gr ON gv.id = gr.noguia
		INNER JOIN catalogoempleado AS ccr ON gv.idremitente = ccr.id
		INNER JOIN catalogoempleado AS ccd ON gv.iddestinatario = ccd.id
		WHERE gv.id= '$_GET[codigo]'";
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);
	}
?>
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
		factory.printing.portrait = false;
		factory.printing.leftMargin = 5.0;
		factory.printing.topMargin = 5.0;
		factory.printing.rightMargin = 0;
		factory.printing.bottomMargin = 0;
	  	factory.printing.Print(false);
		window.close();
	}
</script>
<body>
<table width="633" border="0" cellpadding="0" cellspacing="0">
<tr>
   	<td width="266" height="71" valign="top">
    <table width="303" border="0" cellpadding="0" cellspacing="0" class="texto_bold5">
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
      </table>
    </td>
        <td width="266" valign="top">
        <table width="326">
        	<tr>
              <td class="texto_bold4">TIPO DE ENTREGA:
                <?=$f->tipoentrega?></td>
            </tr>
            <tr>
              <td class="texto_bold4"> TIEMPO DE ENTREGA ESTIMADO:
                <?=$f->estimado?></td>
            </tr>
        </table>
        <table width="325" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="62" class="texto_normal" align="left">ORIGEN</td>
            <td width="67" class="texto_bold" align="left"><?=$f->origen?></td>
            <td width="60" class="texto_normal" align="left">DESTINO</td>
            <td width="71" class="texto_bold" align="left"><?=$f->destino?></td>
          </tr>
    </table></td>
  </tr>
<tr>
   	<td width="266" height="60" valign="top">
    	<table width="302" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">
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
        </table>
</td>
        <td width="266" valign="top">
        	<table width="325" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">
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
            </table>
        </td>
  </tr>
  <tr>
  <td height="7" valign="MIDDLE" class="texto_bold2" align="CENTER"></td>
  <td height="7" valign="MIDDLE" class="texto_bold2" align="CENTER"></td>
</tr>
<tr>
  <td height="19" valign="MIDDLE" class="texto_bold2" align="CENTER">
  	<table width="298" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td valign="top"><table width="296" border="0" cellpadding="0" cellspacing="0" class="texto_normal2">
        <tr>
          <td width="63" class="texto_bold2">FECHA</td>
          <td width="70">:
            <?=$f->fecha?></td>
          <td width="57"><?=$f->hora?></td>
          <td width="70">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="4" class="texto_bold2"><table width="260" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="64">PAQUETES:</td>
              <td width="29" class="texto_normal2"><?=$f->totalpaquetes?></td>
              <td width="41">P.VOL:</td>
              <td width="44" class="texto_normal2" align="right"><?=$f->totalvolumen?>
                &nbsp;</td>
              <td width="39">P. KG:</td>
              <td width="43" class="texto_normal2" align="right"><?=$f->totalpeso?>
                &nbsp;</td>
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
          <td colspan="4" class="texto_normal2"><table width="295" border="0" cellpadding="1" cellspacing="0">
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
        </table></td>
      </tr>
    </table>
  </td>
  <td height="19" valign="top" class="texto_bold2" align="CENTER"><table width="321" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td valign="top"><table width="262" border="0" cellpadding="0" cellspacing="0" class="texto_normal2">
        <?
			$s = "SELECT * FROM guiaventanilla_detalle WHERE idguia = '$_GET[codigo]'";
			$rd = mysql_query($s,$l) or die($s);
			$fd = mysql_fetch_object($rd);
		?>
        <tr>
          <td width="260" class="texto_normal4"><table width="318" border="0" cellpadding="0" cellspacing="0" class="texto_bold4">
            <tr>
              <td width="69" align="left" class="texto_normal4">flete</td>
              <td width="10">$</td>
              <td width="66" align="right" class="texto_bold4"><?=number_format($f->tflete, 2, '.',',')?></td>
              <td width="75" class="texto_normal4" align="left">&nbsp;&nbsp;&nbsp;desc.</td>
              <td width="13">$</td>
              <td width="85" align="right" class="texto_bold4"><?=number_format($f->ttotaldescuento, 2, '.',',')?></td>
              </tr>
            <tr>
              <td align="left" class="texto_normal4">ead</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->tcostoead, 2, '.',',')?></td>
              <td class="texto_normal4" align="left">&nbsp;&nbsp;&nbsp;reco.</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->trecoleccion, 2, '.',',')?></td>
              </tr>
            <tr>
              <td class="texto_normal4" align="left">seguro</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->tseguro, 2, '.',',')?></td>
              <td class="texto_normal4" align="left">&nbsp;&nbsp;&nbsp;otros</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->totros, 2, '.',',')?></td>
              </tr>
            <tr>
              <td class="texto_normal4" align="left">EXCed.</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->texcedente, 2, '.',',')?></td>
              <td class="texto_normal4" align="left">&nbsp;&nbsp;&nbsp;COMBUS.</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->tcombustible, 2, '.',',')?></td>
              </tr>
            <tr>
              <td class="texto_normal4" align="left">SUBTOTAL</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->subtotal, 2, '.',',')?></td>
              <td class="texto_normal4" align="left">&nbsp;&nbsp;&nbsp;IVA</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->tiva, 2, '.',',')?></td>
              </tr>
            <tr>
              <td class="texto_normal4" align="left">IVARET.</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->ivaretenido, 2, '.',',')?></td>
              <td class="texto_normal4" align="left">&nbsp;&nbsp;&nbsp;TOTAL</td>
              <td>$</td>
              <td align="right" class="texto_bold4"><?=number_format($f->total, 2, '.',',')?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="14" class="texto_bold2" align="center"><table width="317" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="128" align="center">NUMERO DE GUIA</td>
          <td width="131" align="center">NUMERO DE RASTREO</td>
          </tr>
        <tr>
          <td align="center"><?=$f->id?></td>
          <td align="center"><?=$f->numerorastreo?></td>
          </tr>
        </table></td>
    </tr>
  </table></td>
</tr>
<tr>
  <td height="57" valign="MIDDLE" class="texto_bold2" align="CENTER">
  	<table width="302" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">
    	<tr>
        	<td width="95">Nombre</td>
            <td width="207"></td>
        </tr>
    	<tr>
        	<td>TIPO IDENTIFICACION</td>
            <td></td>
        </tr>
    	<tr>
        	<td>NUMERO IDENTIFICACION</td>
            <td></td>
        </tr>
    </table>
  </td>
  <td height="57" valign="top" class="texto_bold2" align="CENTER">
  	<?
			  echo "<table border=0px cellspacing=0 cellpadding=0><tr><td>
			  <img width=260 src='../codigobarrasnuevo/image.php?code=$_GET[codigo]&style=68&type=C128A&width=370&height=115&xres=2&font=4''>
			  </td></tr></table>";
			 ?> 
  </td>
</tr>
</table>
<p>&nbsp;</p>
</body>
</html>