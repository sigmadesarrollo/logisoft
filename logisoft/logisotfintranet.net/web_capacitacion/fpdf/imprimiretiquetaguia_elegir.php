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
<style type="text/css">
<!--
H1.SaltoDePagina{
 	PAGE-BREAK-AFTER: always;
}
</style>
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
		dd.poblacion AS dpoblacion, dd.telefono AS dtelefono,
		IFNULL(gv.valordeclarado,0) AS valordeclarado, IF(gv.tipoflete=0,'PAGADO', 'POR COBRAR') AS tipoflete,
		IF(gv.condicionpago='1','CREDITO','CONTADO') AS condicionpago, gv.usuario,
		IFNULL(sec.descripcion, 'SIN SECTOR') sector, gv.observaciones
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS sec ON gv.sector = sec.id
		INNER JOIN catalogosucursal AS csd ON gv.idsucursaldestino = csd.id
		INNER JOIN catalogosucursal AS cso ON gv.idsucursalorigen = cso.id
		INNER JOIN catalogodestino AS cd ON gv.iddestino = cd.id
		LEFT JOIN guia_rastreo AS gr ON gv.id = gr.noguia
		INNER JOIN catalogocliente AS ccr ON gv.idremitente = ccr.id
		LEFT JOIN direccion AS dr ON gv.iddireccionremitente = dr.id
		INNER JOIN catalogocliente AS ccd ON gv.iddestinatario = ccd.id
		INNER JOIN direccion AS dd ON gv.iddirecciondestinatario = dd.id
		WHERE gv.id= '$_GET[codigo]'";
		
		//echo $s;
		$r = mysql_query($s, $l) or die($s);
		$f = mysql_fetch_object($r);
		
	}else if($_GET[tipo]==2){//GUIAS EMPRESARIALES
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
		dd.poblacion AS dpoblacion, dd.telefono AS dtelefono,
		IFNULL(gv.valordeclarado,0) AS valordeclarado, gv.tipoflete,
		gv.tipopago AS condicionpago, gv.usuario,
		IFNULL(sec.descripcion, 'SIN SECTOR') sector, gv.observaciones
		FROM guiasempresariales AS gv
		LEFT JOIN catalogosector AS sec ON gv.sector = sec.id
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
		ccr.rfc AS rrfc, 
		
		cso.calle AS rcalle, cso.numero AS rnumero, cso.cp AS rcp, cso.colonia AS rcolonia, 
		cso.poblacion AS rpoblacion, cso.telefono AS rtelefono,
		
		gv.iddestinatario,
		CONCAT_WS(' ', ccd.nombre, ccd.apellidopaterno, ccd.apellidomaterno) AS dncliente,
		ccd.rfc AS drfc,
		
		csd.calle AS dcalle, csd.numero AS dnumero, csd.cp AS dcp, csd.colonia AS dcolonia, 
		csd.poblacion AS dpoblacion, csd.telefono AS dtelefono, gv.usuario,
		IFNULL(sec.descripcion, 'SIN SECTOR') sector
		
		FROM guiasventanilla AS gv
		LEFT JOIN catalogosector AS sec ON gv.sector = sec.id
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
		factory.printing.leftMargin = 2.0;
		factory.printing.topMargin = 5.0;
		factory.printing.rightMargin = 1.0;
		factory.printing.bottomMargin = 1.0;
	  	factory.printing.Print(false);
		<?
			if($_GET[valor]==1){		
		?>		opener.cambiarImpresora2(); <?
			}else{
		?>		opener.cambiarImpresora1_solo("<?=$_GET[valor]?>",'<?=$_GET[folio]?>'); <?
			}
		?>
		window.close();
	}
	
</script>
<body>
<table width="495" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="228" height="150" valign="top"><table width="228" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">
      <tr>
        <td width="238" height="57" align="left"><table width="228" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="19" class="texto_bold" valign="top">CARTA DE PORTE</td>
          </tr>
        </table>
          <?
				$s = "SELECT eti_nombre1,eti_nombre2,eti_direccion,eti_colonia,eti_ciudad,eti_rfc FROM configuradorgeneral";
				$rx = mysql_query($s,$l) or die($s);
				$fx = mysql_fetch_object($rx);
			?>
          <table width="228" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="75"><img src="../img/logopmmazul.png" width="80" height="74" /></td>
              <td width="153" class="texto_normal5"><?=$fx->eti_nombre1?>
                <br />
                <?=$fx->eti_nombre2?>
                <br />
                <?=$fx->eti_direccion?>
                <br />
                <?=$fx->eti_colonia?>
                <br />
                <?=$fx->eti_ciudad?>
                <br />
                <?=$fx->eti_rfc?></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_bold">REMITENTE</td>
      </tr>
      <tr>
        <td class="texto_bold4"><div  style="overflow:hidden; width:225px; height:11px"><?=$f->rncliente?></div></td>
      </tr>
      <tr>
        <td>CTE:
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
        <td class="texto_bold4"><div  style="overflow:hidden; width:225px; height:11px"><?=$f->dncliente?></div></td>
      </tr>
      <tr>
        <td>CTE:
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
        <td>SECTOR:
          <?=$f->sector?>
          &nbsp;&nbsp;TEL:
          <?=$f->dtelefono?></td>
      </tr>
      <tr>
        <td height="5px"></td>
      </tr>
      <tr>
        <td height="5px" align="center"><table width="222" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55" class="texto_normal" align="left">ORIGEN</td>
            <td width="51" class="texto_bold" align="left"><?=$f->origen?></td>
            <td width="60" class="texto_normal" align="left">DESTINO</td>
            <td width="73" class="texto_bold" align="left"><?=$f->destino?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>TIPO DE ENTREGA:&nbsp;&nbsp;&nbsp;
          <?=$f->tipoentrega?></td>
      </tr>
      <tr>
        <td>TIPO DE FLETE:&nbsp;&nbsp;&nbsp;
          <?=$f->tipoflete?></td>
      </tr>
      <tr>
        <td>VALOR DECLARADO:&nbsp;&nbsp;&nbsp;
          <?=$f->valordeclarado?></td>
      </tr>
      <tr>
        <td>CONDICION DE PAGO:&nbsp;&nbsp;&nbsp;
          <?=$f->condicionpago?></td>
      </tr>
      <tr>
        <td>DOCUMENTO:&nbsp;&nbsp;&nbsp;
          <?=$f->usuario?></td>
      </tr>
    </table></td>
    <td width="263" valign="top"><table width="263" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top"><table width="262" border="0" cellpadding="0" cellspacing="0" class="texto_normal2">
          <tr>
            <td width="63" class="texto_bold3" colspan="4" align="center" style="text-align:center"><table cellpadding="0" cellspacing="0" border="0" width="262px">
              <tr>
                <td align="center" style="font-size:26px;"><?=$f->id?></td>
              </tr>
            </table></td>
          </tr>
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
			if($_GET[tipo]==1 || $_GET[tipo]==3){//GUIAS DE VENTANILLA
				$s = "SELECT * FROM guiaventanilla_detalle WHERE idguia = '$_GET[codigo]'";
				$rd = mysql_query($s,$l) or die($s);
				$fd = mysql_fetch_object($rd);
			}elseif($_GET[tipo]==2){//GUIAS EMPRESARIALES
				$s = "SELECT * FROM guiasempresariales_detalle WHERE id = '$_GET[codigo]'";
				$rd = mysql_query($s,$l) or die($s);
				$fd = mysql_fetch_object($rd);
			}
		?>
          <tr>
            <td colspan="4" class="texto_bold2">DICE CONTENER: </td>
          </tr>
          <tr>
            <td colspan="4" class="texto_normal2"><table width="261" border="0" cellpadding="1" cellspacing="0">
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
            <td colspan="4" class="texto_normal4"><?
			if($_GET[tipo]==3){
		?>
              <table width="261" border="0" cellpadding="1" cellspacing="0">
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td width="94" class="texto_bold5"></td>
                  <td width="129" class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
              </table>
              <?	
			}else{
				$var_totalservicios = $f->tcostoead + $f->trecoleccion 
									+ $f->totros + (($_GET[tipo]==1)?$f->texcedente:0) 
									+ $f->tcombustible;
		?>
        	  <table width="261" border="0" cellpadding="0" cellspacing="0" class="texto_normal5">
                <tr>
                	<td><div style="overflow:hidden; width:261px; height:12px; font-weight:bold">OBS:
                	  <?=$f->observaciones?>
              	  </div></td>
                </tr>
              </table>
              <table width="261" border="0" cellpadding="0" cellspacing="0" class="texto_bold4">
                <tr>
                  <td width="64" class="texto_normal4">tarifa</td>
                  <td width="9">$</td>
                  <td width="48" align="right" class="texto_bold4"><?=number_format($f->tflete, 2, '.',',')?></td>
                  <td width="76" class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;serv:</td>
                  <td width="12">&nbsp;</td>
                  <td width="52" align="right" class="texto_bold4">&nbsp;</td>
                </tr>
                <tr>
                  <td class="texto_normal4">descuento</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->ttotaldescuento, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;ead</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->tcostoead, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">servicios</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($var_totalservicios, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;rad</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->trecoleccion, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">seguro</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->tseguro, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;otros</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->totros, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">subtotal</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->subtotal, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;excede.</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->texcedente, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">iva</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->tiva, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;combus</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->tcombustible, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">iva ret</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->ivaretenido, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;total</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($var_totalservicios, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">total</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->total, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="right" class="texto_bold4">&nbsp;</td>
                </tr>
              </table>
              <?
			}
		?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="18" align="center"><?
			  echo "<table border=0px cellspacing=0 cellpadding=0><tr><td>
			  <img width=260 src='../codigobarrasnuevo/image.php?code=$_GET[codigo]&style=68&type=C128A&width=370&height=115&xres=2&font=4''>
			  </td></tr></table>";
			 ?></td>
      </tr>
      <tr>
        <td height="14" class="texto_bold2" align="center"><table width="259" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center">NUMERO DE RASTREO</td>
          </tr>
          <tr>
            <td align="center"><?=$f->numerorastreo?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="middle" class="texto_normal5" align="center"> 
    	<table width="468" cellpadding="0" cellspacing="0" border="0">
        <tr>
        	<td><? if($f->valordeclarado=="0"){?>*Este envio viaja a cuenta y riesgo del remitente por no declarar valor &nbsp;&nbsp;&nbsp;&nbsp; <? } ?></td>
        	<td><strong class="texto_bold3">Cliente</strong></td>
        </tr>
        </table>    
    </td>
  </tr>
</table>
<H1 class=SaltoDePagina>&nbsp;</H1>
<table width="495" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="228" height="150" valign="top"><table width="228" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">
      <tr>
        <td width="238" height="57" align="left"><table width="228" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="19" class="texto_bold" valign="top">CARTA DE PORTE</td>
          </tr>
        </table>
          <?
				$s = "SELECT eti_nombre1,eti_nombre2,eti_direccion,eti_colonia,eti_ciudad,eti_rfc FROM configuradorgeneral";
				$rx = mysql_query($s,$l) or die($s);
				$fx = mysql_fetch_object($rx);
			?>
          <table width="228" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="75"><img src="../img/logopmmazul.png" width="80" height="74" /></td>
              <td width="153" class="texto_normal5"><?=$fx->eti_nombre1?>
                <br />
                <?=$fx->eti_nombre2?>
                <br />
                <?=$fx->eti_direccion?>
                <br />
                <?=$fx->eti_colonia?>
                <br />
                <?=$fx->eti_ciudad?>
                <br />
                <?=$fx->eti_rfc?></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td class="texto_bold">REMITENTE</td>
      </tr>
      <tr>
        <td class="texto_bold4"><div  style="overflow:hidden; width:225px; height:11px"><?=$f->rncliente?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">CTE:
          <?=$f->idremitente?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">CALLE:
          <?=$f->rcalle?>
          NO
          <?=$f->rnumero?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">COL:
          <?=$f->rcolonia?>
          C.P.
          <?=$f->rcp?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">CD:
          <?=$f->rpoblacion?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">TEL:
          <?=$f->rtelefono?></div></td>
      </tr>
      <tr>
        <td height="4px"></td>
      </tr>
      <tr>
        <td class="texto_bold">DESTINATARIO</td>
      </tr>
      <tr>
        <td class="texto_bold4"><div  style="overflow:hidden; width:225px; height:11px"><?=$f->dncliente?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">CTE:
          <?=$f->iddestinatario?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">CALLE:
          <?=$f->dcalle?>
          <?=$f->dnumero?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">COL:
          <?=$f->dcolonia?>
          C.P.
          <?=$f->dcp?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">CD:
          <?=$f->dpoblacion?></div></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">SECTOR:
          <?=$f->sector?>
          &nbsp;&nbsp;TEL:
          <?=$f->dtelefono?></div></td>
      </tr>
      <tr>
        <td height="5px"></td>
      </tr>
      <tr>
        <td height="5px" align="center"><table width="222" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55" class="texto_normal" align="left">ORIGEN</td>
            <td width="51" class="texto_bold" align="left"><?=$f->origen?></td>
            <td width="60" class="texto_normal" align="left">DESTINO</td>
            <td width="73" class="texto_bold" align="left"><?=$f->destino?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>TIPO DE ENTREGA:&nbsp;&nbsp;&nbsp;
          <?=$f->tipoentrega?></td>
      </tr>
      <tr>
        <td>TIPO DE FLETE:&nbsp;&nbsp;&nbsp;
          <?=$f->tipoflete?></td>
      </tr>
      <tr>
        <td>VALOR DECLARADO:&nbsp;&nbsp;&nbsp;
          <?=$f->valordeclarado?></td>
      </tr>
      <tr>
        <td>CONDICION DE PAGO:&nbsp;&nbsp;&nbsp;
          <?=$f->condicionpago?></td>
      </tr>
      <tr>
        <td><div  style="overflow:hidden; width:225px; height:11px">DOCUMENTO:&nbsp;&nbsp;&nbsp;
          <?=$f->usuario?></div></td>
      </tr>
    </table></td>
    <td width="263" valign="top"><table width="263" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top"><table width="262" border="0" cellpadding="0" cellspacing="0" class="texto_normal2">
          <tr>
            <td width="63" class="texto_bold3" colspan="4" align="center" style="text-align:center"><table cellpadding="0" cellspacing="0" border="0" width="262px">
              <tr>
                <td align="center" style="font-size:26px;"><?=$f->id?></td>
              </tr>
            </table></td>
          </tr>
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
			if($_GET[tipo]==1 || $_GET[tipo]==3){//GUIAS DE VENTANILLA
				$s = "SELECT * FROM guiaventanilla_detalle WHERE idguia = '$_GET[codigo]'";
				$rd = mysql_query($s,$l) or die($s);
				$fd = mysql_fetch_object($rd);
			}elseif($_GET[tipo]==2){//GUIAS EMPRESARIALES
				$s = "SELECT * FROM guiasempresariales_detalle WHERE id = '$_GET[codigo]'";
				$rd = mysql_query($s,$l) or die($s);
				$fd = mysql_fetch_object($rd);
			}
		?>
          <tr>
            <td colspan="4" class="texto_bold2">DICE CONTENER: </td>
          </tr>
          <tr>
            <td colspan="4" class="texto_normal2"><table width="261" border="0" cellpadding="1" cellspacing="0">
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
            <td colspan="4" class="texto_normal4"><?
			if($_GET[tipo]==3){
		?>
              <table width="261" border="0" cellpadding="1" cellspacing="0">
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td width="94" class="texto_bold5"></td>
                  <td width="129" class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
                <tr>
                  <td colspan="2" class="texto_bold5">&nbsp;&nbsp;</td>
                  <td class="texto_bold5"></td>
                  <td class="texto_bold5"></td>
                </tr>
              </table>
              <?	
			}else{
				$var_totalservicios = $f->tcostoead + $f->trecoleccion 
									+ $f->totros + (($_GET[tipo]==1)?$f->texcedente:0) 
									+ $f->tcombustible;
		?>
        	  <table width="261" border="0" cellpadding="0" cellspacing="0" class="texto_normal5">
                <tr>
                	<td><div style="overflow:hidden; width:261px; height:15px; font-weight:bold">OBS:
                	  <?=$f->observaciones?>
              	  </div></td>
                </tr>
              </table>
              <table width="261" border="0" cellpadding="0" cellspacing="0" class="texto_bold4">
                <tr>
                  <td width="64" class="texto_normal4">tarifa</td>
                  <td width="9">$</td>
                  <td width="48" align="right" class="texto_bold4"><?=number_format($f->tflete, 2, '.',',')?></td>
                  <td width="76" class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;serv:</td>
                  <td width="12">&nbsp;</td>
                  <td width="52" align="right" class="texto_bold4">&nbsp;</td>
                </tr>
                <tr>
                  <td class="texto_normal4">descuento</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->ttotaldescuento, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;ead</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->tcostoead, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">servicios</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($var_totalservicios, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;rad</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->trecoleccion, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">seguro</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->tseguro, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;otros</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->totros, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">subtotal</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->subtotal, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;excede.</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->texcedente, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">iva</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->tiva, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;combus</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->tcombustible, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">iva ret</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->ivaretenido, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;&nbsp;&nbsp;&nbsp;total</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($var_totalservicios, 2, '.',',')?></td>
                </tr>
                <tr>
                  <td class="texto_normal4">total</td>
                  <td>$</td>
                  <td align="right" class="texto_bold4"><?=number_format($f->total, 2, '.',',')?></td>
                  <td class="texto_normal4">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td align="right" class="texto_bold4">&nbsp;</td>
                </tr>
              </table>
              <?
			}
		?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="18" align="center"><?
			  echo "<table border=0px cellspacing=0 cellpadding=0><tr><td>
			  <img width=260 src='../codigobarrasnuevo/image.php?code=$_GET[codigo]&style=68&type=C128A&width=370&height=115&xres=2&font=4''>
			  </td></tr></table>";
			 ?></td>
      </tr>
      <tr>
        <td height="14" class="texto_bold2" align="center"><table width="259" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center">NUMERO DE RASTREO</td>
          </tr>
          <tr>
            <td align="center"><?=$f->numerorastreo?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="middle" class="texto_normal5" align="center">
    	<table width="468" cellpadding="0" cellspacing="0" border="0">
        <tr>
        	<td><? if($f->valordeclarado=="0"){?>*Este envio viaja a cuenta y riesgo del remitente por no declarar valor &nbsp;&nbsp;&nbsp;&nbsp; <? } ?></td>
        	<td><strong class="texto_bold3">Destino</strong></td>
        </tr>
        </table>
    </td>
  </tr>
</table>
</body>
</html>
