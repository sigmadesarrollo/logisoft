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

<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="http://pmmintranet.net/web/activexs/smsx.cab#Version=6,5,439,30">
</object>
<script> 
	window.onload = function (){
		enviarImpresion();
	}

	function enviarImpresion(){
		/*factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = false;
		factory.printing.leftMargin = 2.0;
		factory.printing.topMargin = 5.0;
		factory.printing.rightMargin = 1.0;
		factory.printing.bottomMargin = 1.0;
	  	factory.printing.Print(false);
		opener.cambiarImpresora1();
		window.close();*/
	}
	
</script>
<body>

<?
		$s = "SELECT cs.prefijo 
				FROM catalogosucursal cs 
				INNER JOIN catalogodestino cd ON cs.id = cd.sucursal				
				WHERE cd.id = '$_GET[sorigen]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r) or die($s);
		$origen = $f->prefijo;
		$s = "SELECT cs.prefijo 
				FROM catalogosucursal cs 
				INNER JOIN catalogodestino cd ON cs.id = cd.sucursal				
				WHERE cd.id = '$_GET[sdestino]'";
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r) or die($s);
		$destino = $f->prefijo;

		$numcon	= substr($_GET[folioinicio],0,3);
		$folini = substr($_GET[folioinicio],3,9);
		$letini = substr($_GET[folioinicio],12,1);
		$folfin = substr($_GET[foliofinal],3,9);
		$letfin = substr($_GET[foliofinal],12,1);
		
		
		$let_a_u = ord($letini);
		$let_b_u = ord($letfin);
		
		$let_b =  ord($letfin);
		
		$anterior = $let_b;
		for($let_a = ord($letini); $let_a <= $let_b; $let_a++){
			
			if($let_a!=$let_b){
				$folfinciclo = 999999999;
			}else{
				$folfinciclo = $folfin;
			}
			
			if($let_a == $let_a_u){
				$foliniciclo = $folini;
			}else{
				$foliniciclo = 000000001;
			}
			
			for($foliniciclo; $foliniciclo<=$folfinciclo; $foliniciclo++){
				$folioguia = $numcon.str_pad($foliniciclo,9,"0",STR_PAD_LEFT).chr($let_a);
				
				$_GET[codigo] = $folioguia;
		
				$s = "SELECT
				'$_GET[codigo]' id,
				ccr.id AS idremitente,
				CONCAT_WS(' ', ccr.nombre, ccr.paterno, ccr.materno) AS rncliente, ccr.rfc AS rrfc, ccr.celular AS rcelular,
				dr.calle AS rcalle, dr.numero AS rnumero, dr.cp AS rcp, dr.colonia AS rcolonia, 
				dr.poblacion AS rpoblacion, dr.telefono AS rtelefono,
				ccd.id AS iddestinatario,
				CONCAT_WS(' ', ccd.nombre, ccd.paterno, ccd.materno) AS dncliente, ccd.rfc AS drfc, ccd.celular AS dcelular,
				dd.calle AS dcalle, dd.numero AS dnumero, dd.cp AS dcp, dd.colonia AS dcolonia, 
				dd.poblacion AS dpoblacion, dd.telefono AS dtelefono
				FROM (SELECT CURRENT_DATE) gv
				LEFT JOIN catalogocliente AS ccr ON '$_GET[idremitente]' = ccr.id
				LEFT JOIN direccion AS dr ON ccr.id = dr.codigo and dr.origen = 'cl'
				LEFT JOIN catalogocliente AS ccd ON '$_GET[iddestinatario]' = ccd.id
				LEFT JOIN direccion AS dd ON ccd.id = dd.codigo and dd.origen = 'cl'";
				$r = mysql_query($s, $l) or die($s);
				$f = mysql_fetch_object($r);
			
		?>
		
		<table width="495" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="228" height="150" valign="top"><table width="228" border="0" cellpadding="0" cellspacing="0" class="texto_normal4">
			  <tr>
				<td width="238" height="57" align="left">
				<table width="228" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td height="19" class="texto_bold" valign="top">CARTA PORTE</td>
					</tr>
				</table>
					<?
						$s = "SELECT UPPER(CONCAT_WS(' ',calle, numero, colonia)) AS direccion, 
						UPPER(CONCAT(poblacion,', ', estado)) AS estadociudad FROM catalogosucursal
						WHERE id = $_SESSION[IDSUCURSAL]";
						$rx = mysql_query($s,$l) or die($s);
						$fx = mysql_fetch_object($rx);
					?>
					<table width="228" border="0" cellpadding="0" cellspacing="0">
			  <tr>
						<td width="57"><img src="../img/logopmmazul.png" width="52" height="49" /></td>
							<td width="206" class="texto_normal5">PAQUETERIA Y MENSAJERIA  <br />
							EN MOVIMIENTO S.A. DE C.V.
							<br />
							<?=$fx->direccion?>
							<br />
							<?=$fx->estadociudad?>
							<br />
							PMM9087545145
						</td>
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
				<td height="5px" align="center"><table width="222" border="0" cellpadding="0" cellspacing="0">
					<tr>
					  <td width="55" class="texto_normal" align="left">ORIGEN</td>
					  <td width="51" class="texto_bold" align="left"><?=$origen?></td>
					  <td width="60" class="texto_normal" align="left">DESTINO</td>
					  <td width="73" class="texto_bold" align="left"><?=$destino?></td>
					</tr>
				</table></td>
			  </tr>
			  <tr>
				<td>TIPO DE ENTREGA:&nbsp;&nbsp;&nbsp;<?=$f->tipoentrega?></td>
			  </tr>
			  <tr>
				<td>TIPO DE FLETE:&nbsp;&nbsp;&nbsp;<?=$f->tipoflete?></td>
			  </tr>
			  <tr>
				<td>VALOR DECLARADO:&nbsp;&nbsp;&nbsp;<?=$f->valordeclarado?></td>
			  </tr>
			  <tr>
				<td>CONDICION DE PAGO:&nbsp;&nbsp;&nbsp;<?=$f->condicionpago?></td>
			  </tr>
		</table>    </td>
				<td width="263" valign="top">
			  <table width="263" border="0" cellpadding="0" cellspacing="0">
					<tr>
					  <td valign="top">
					  <table width="262" border="0" cellpadding="0" cellspacing="0" class="texto_normal2">
						<tr>
							<td width="63" class="texto_bold3" colspan="4" align="center" style="text-align:center">
							<table cellpadding="0" cellspacing="0" border="0" width="262px">
								<tr>
									<td align="center"><?=$f->id?></td>
								</tr>
							</table>
							</td>
						</tr>
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
				
				<td colspan="2" class="texto_bold5">&nbsp;&nbsp;
				  <?=$fd->cantidad?></td>
				<td class="texto_bold5"><?=$fd->descripcion?></td>
				<td class="texto_bold5"><?=$fd->contenido?></td>
				</tr>
				<tr>
				
				<td colspan="2" class="texto_bold5">&nbsp;&nbsp;
				  <?=$fd->cantidad?></td>
				<td class="texto_bold5"><?=$fd->descripcion?></td>
				<td class="texto_bold5"><?=$fd->contenido?></td>
				</tr>
				<tr>
				
				<td colspan="2" class="texto_bold5">&nbsp;&nbsp;
				  <?=$fd->cantidad?></td>
				<td class="texto_bold5"><?=$fd->descripcion?></td>
				<td class="texto_bold5"><?=$fd->contenido?></td>
				</tr>
				<tr>
				
				<td colspan="2" class="texto_bold5">&nbsp;&nbsp;
				  <?=$fd->cantidad?></td>
				<td class="texto_bold5"><?=$fd->descripcion?></td>
				<td class="texto_bold5"><?=$fd->contenido?></td>
				</tr>
				<tr>
				
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
					<td width="50" align="right" class="texto_bold4"><?=number_format($f->tflete, 2, '.',',')?></td>
					<td width="62" class="texto_normal4">&nbsp;&nbsp;&nbsp;desc.</td>
					<td width="11">$</td>
					<td width="69" align="right" class="texto_bold4"><?=number_format($f->ttotaldescuento, 2, '.',',')?></td>
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
					<td class="texto_normal4">SEGURO</td>
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
							<td align="center">NUMERO DE RASTREO</td>
						</tr>
						<tr>
							<td align="center"><?=$f->numerorastreo?></td>
						</tr>
					  </table>			  </td>
				  </tr>
			</table>        </td>
		  </tr>
		  <?
			if($f->valordeclarado=="0"){
		  ?>
		<tr>
		  <td height="19" colspan="2" valign="MIDDLE" class="texto_bold2" align="CENTER">*Este envio viaja a cuenta y riesgo del remitente por no declarar valor</td>
		</tr>
		<tr>
		  <td height="19" colspan="2" valign="MIDDLE" class="texto_bold2" align="right">Cliente&nbsp;&nbsp;</td>
		</tr>
 <?
		}
	}
}
 ?>
</table>
</body>
</html>
