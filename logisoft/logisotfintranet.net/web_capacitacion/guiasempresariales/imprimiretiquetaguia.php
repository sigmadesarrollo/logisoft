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

		opener.cambiarImpresora1();

		window.close();

		

	}

	

</script>

<body>

<?

	

	$s = "";



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

	CONCAT_WS(' ', ccr.nombre, ccr.paterno, ccr.materno) AS rncliente, ccr.rfc AS rrfc, ccr.celular AS rcelular,

	dr.calle AS rcalle, dr.numero AS rnumero, dr.cp AS rcp, dr.colonia AS rcolonia, 

	dr.poblacion AS rpoblacion, dr.telefono AS rtelefono,

	gv.iddestinatario,

	CONCAT_WS(' ', ccd.nombre, ccd.paterno, ccd.materno) AS dncliente, ccd.rfc AS drfc, ccd.celular AS dcelular,

	dd.calle AS dcalle, dd.numero AS dnumero, dd.cp AS dcp, dd.colonia AS dcolonia, 

	dd.poblacion AS dpoblacion, dd.telefono AS dtelefono

	FROM guiasempresariales AS gv

	INNER JOIN catalogosucursal AS csd ON gv.idsucursaldestino = csd.id

	INNER JOIN catalogosucursal AS cso ON gv.idsucursalorigen = cso.id

	INNER JOIN catalogodestino AS cd ON gv.iddestino = cd.id

	LEFT JOIN guia_rastreo AS gr ON gv.id = gr.noguia

	INNER JOIN catalogocliente AS ccr ON gv.idremitente = ccr.id

	LEFT JOIN direccion AS dr ON gv.iddireccionremitente = dr.id

	INNER JOIN catalogocliente AS ccd ON gv.iddestinatario = ccd.id

	INNER JOIN direccion AS dd ON gv.iddirecciondestinatario = dd.id

	WHERE gv.id BETWEEN '$_GET[folioinicio]' AND '$_GET[foliofin]' AND gv.tipoguia	 <> 'PREPAGADA'";

		$r = mysql_query($s, $l) or die($s);

		while($f = mysql_fetch_object($r)){

			

			if($_GET[imprimirremitente]){

				$f->rncliente = "";

				$f->idremitente = "";

                $f->rcalle = "";

                $f->rnumero = "";

                $f->rcolonia = "";

                $f->rcp = "";

                $f->rpoblacion = "";

                $f->rtelefono = "";

			}

			if($_GET[imprimirdestinatario]){

                $f->dncliente = "";

                $f->iddestinatario = "";

                $f->dcalle = "";

                $f->dnumero = "";

                $f->dcolonia = "";

                $f->dcp = "";

                $f->dpoblacion = "";

                $f->dtelefono = "";

			}

?>

<table width="532" border="0" cellpadding="0" cellspacing="0">

<tr>

   	<td width="266" height="150" valign="top"><table width="265" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">

      <tr>

        <td height="57" align="left">

        	<table width="263" border="0" cellpadding="0" cellspacing="0">

   	  <tr>

               	<td width="57"><img src="../img/logopmmazul.png" width="52" height="49" /></td>

                    <td width="206" class="titulo_cliente">&nbsp;&nbsp;&nbsp;<font style="font-size:32px">CLIENTE</font></td>

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

       	    <td class="texto_bold" height="40px"></td>

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

   	    <td colspan="4" class="texto_normal4"><table width="261" border="0" cellpadding="0" cellspacing="0" class="texto_bold4">

          <tr>

            <td width="59" class="texto_normal4">flete</td>

            <td width="10">$</td>

            <td width="56" align="right" class="texto_bold4"><?=number_format($f->tflete, 2, '.',',')?></td>

            <td width="61" class="texto_normal4">&nbsp;&nbsp;&nbsp;desc.</td>

            <td width="10">$</td>

            <td width="65" align="right" class="texto_bold4"><?=number_format($f->ttotaldescuento, 2, '.',',')?></td>

          </tr>

          <tr>

            <td class="texto_normal4">ead</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->tcostoead, 2, '.',',')?></td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;reco.</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->trecoleccion, 2, '.',',')?></td>

          </tr>

          <tr>

            <td class="texto_normal4">seguro</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->tseguro, 2, '.',',')?></td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;otros</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->totros, 2, '.',',')?></td>

          </tr>

          <tr>

            <td class="texto_normal4">EXCed.</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->texcedente, 2, '.',',')?></td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;COMBUS.</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->tcombustible, 2, '.',',')?></td>

          </tr>

          <tr>

            <td class="texto_normal4">SUBTOTAL</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->subtotal, 2, '.',',')?></td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;IVA</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->tiva, 2, '.',',')?></td>

          </tr>

          <tr>

            <td class="texto_normal4">IVARET.</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->ivaretenido, 2, '.',',')?></td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;TOTAL</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->total, 2, '.',',')?></td>

          </tr>

        </table></td>

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

	}

 ?>

</table>



<?

	

	

	$letra = substr($_GET[folioinicio],strlen($_GET[folioinicio])-1,1);

	

	$s = "SELECT * FROM solicitudguiasempresariales 

	WHERE desdefolio BETWEEN '$_GET[folioinicio]' AND '$_GET[foliofin]' 

	OR hastafolio BETWEEN UPPER('$_GET[folioinicio]') AND UPPER('$_GET[foliofin]') and idcliente=$_GET[idcliente]";

	

	$r = mysql_query($s,$l) or die($s);

	while($f = mysql_fetch_object($r)){

		

		if(substr($_GET[folioinicio],0,strlen($_GET[folioinicio])-1) > substr($f->desdefolio,0,strlen($f->desdefolio)-1)){

			$inicio = substr($_GET[folioinicio],0,strlen($_GET[folioinicio])-1);

			$inicio = $inicio*1;

		}else{

			$inicio = substr($f->desdefolio,0,strlen($f->desdefolio)-1);

			$inicio = $inicio*1;

		}

		

		if(substr($_GET[foliofin],0,strlen($_GET[foliofin])-1) > substr($f->hastafolio,0,strlen($f->hastafolio)-1)){

			$fin = substr($f->hastafolio,0,strlen($f->hastafolio)-1);

			$fin = $fin*1;

		}else{

			$fin = substr($_GET[foliofin],0,strlen($_GET[foliofin])-1);

			$fin = $fin*1;

		}

		

		for($i=$inicio;$i<=$fin;$i++){

			

			$s = "SELECT * 

		FROM guia_rastreo 

		WHERE noguia = '".$i.$letra."'";

		$rmp = @mysql_query($s,$l) or die("$s");

		

		if(mysql_num_rows($rmp)<1){	

			$s = "INSERT INTO guia_rastreo SET numerorastreo = (SELECT CONCAT(DATE_FORMAT(CURRENT_TIMESTAMP(), '%s%H%i'),

			DATE_FORMAT(CURRENT_TIMESTAMP(), '%y%m%d'),CHAR(FLOOR(RAND()*25)+65),FLOOR(RAND()*9),

			'".substr($i,strlen($i)-3,3)."')), 

			noguia = '".$i.$letra."', tipoguia = 'E', origen = '$_GET[idsucursalorigen]', destino = '$_GET[idsucursaldestino]'";

			mysql_query($s,$l) or die($s);

		}

?>



<table width="532" border="0" cellpadding="0" cellspacing="0">

<tr>

   	<td width="266" height="150" valign="top"><table width="265" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">

      <tr>

        <td height="57" align="left">

        	<table width="263" border="0" cellpadding="0" cellspacing="0">

   	  <tr>

               	<td width="57"><img src="../img/logopmmazul.png" width="52" height="49" /></td>

                    <td width="206" class="titulo_cliente">&nbsp;&nbsp;&nbsp;<font style="font-size:32px">CLIENTE</font></td>

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

        <td class="texto_bold4"><?=$_GET[rncliente]?></td>

      </tr>

      <tr>

        <td>CLTE:

            <?=$_GET[idremitente]?></td>

      </tr>

      <tr>

        <td>CALLE:

          <?=$_GET[rcalle]?>

          NO

          <?=$_GET[rnumero]?></td>

      </tr>

      <tr>

        <td>COL:

          <?=$_GET[rcolonia]?>

          C.P.

          <?=$_GET[rcp]?></td>

      </tr>

      <tr>

        <td>CD:

          <?=$_GET[rpoblacion]?></td>

      </tr>

      <tr>

        <td>TEL:

          <?=$_GET[rtelefono]?></td>

      </tr>

      <tr>

        <td height="4px"></td>

      </tr>

      <tr>

        <td class="texto_bold">DESTINATARIO</td>

      </tr>

      <tr>

        <td class="texto_bold4"><?=$_GET[dncliente]?></td>

      </tr>

      <tr>

        <td>CLTE:

            <?=$_GET[iddestinatario]?></td>

      </tr>

      <tr>

        <td>CALLE:

          <?=$_GET[dcalle]?>

            <?=$_GET[dnumero]?></td>

      </tr>

      <tr>

        <td>COL:

          <?=$_GET[dcolonia]?>

          C.P.

          <?=$_GET[dcp]?></td>

      </tr>

      <tr>

        <td>CD:

          <?=$_GET[dpoblacion]?></td>

      </tr>

      <tr>

        <td>TEL:

          <?=$_GET[dtelefono]?></td>

      </tr>

      <tr>

        <td height="5px"></td>

      </tr>

      <tr>

        <td height="5px" align="center"><table width="264" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td width="62" class="texto_normal" align="left">ORIGEN</td>

              <td width="67" class="texto_bold" align="left"><?=$_GET[origen]?></td>

              <td width="60" class="texto_normal" align="left">DESTINO</td>

              <td width="71" class="texto_bold" align="left"><?=$_GET[destino]?></td>

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

       	    <td class="texto_bold" height="40px"></td>

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

   	    <td colspan="4" class="texto_normal4"><table width="261" border="0" cellpadding="0" cellspacing="0" class="texto_bold4">

          <tr>

            <td width="59" class="texto_normal4">flete</td>

            <td width="10">$</td>

            <td width="56" align="right" class="texto_bold4"> 0.00</td>

            <td width="61" class="texto_normal4">&nbsp;&nbsp;&nbsp;desc.</td>

            <td width="10">$</td>

            <td width="65" align="right" class="texto_bold4"> 0.00</td>

          </tr>

          <tr>

            <td class="texto_normal4">ead</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;reco.</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

          </tr>

          <tr>

            <td class="texto_normal4">seguro</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;otros</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

          </tr>

          <tr>

            <td class="texto_normal4">EXCed.</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;COMBUS.</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

          </tr>

          <tr>

            <td class="texto_normal4">SUBTOTAL</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;IVA</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

          </tr>

          <tr>

            <td class="texto_normal4">IVARET.</td>

            <td>$</td>

            <td align="right" class="texto_bold4"> 0.00</td>

            <td class="texto_normal4">&nbsp;&nbsp;&nbsp;TOTAL</td>

            <td>$</td>

            <td align="right" class="texto_bold4"><?=number_format($f->subtotal/$f->cantidad, 2, '.',',')?></td>

          </tr>

        </table></td>

</tr>

</table>              </td>

      	  </tr>

        	<tr>

        	  <td height="18" align="center"><?

			  echo "<table border=0px cellspacing=0 cellpadding=0><tr><td>

			  <img width=260 src='../codigobarrasnuevo/image.php?code=".$i.$letra."&style=68&type=C128A&width=370&height=115&xres=2&font=4''>

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

					<td align="center"><?=$i.$letra;?></td>

                    <?

						$s = "SELECT numerorastreo FROM guia_rastreo WHERE noguia = '".$i.$letra."'";

						$rr = mysql_query($s,$l) or die($s);

						$fr = mysql_fetch_object($rr);

					?>

					<td align="center"><?=$fr->numerorastreo?></td>

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

	}//if

	}//for

	}//while

 ?>

</table>

<p>&nbsp;</p>

</body>

</html>

