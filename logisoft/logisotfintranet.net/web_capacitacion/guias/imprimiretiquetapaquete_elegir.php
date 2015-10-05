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
</head>
<object id=factory viewastext style="display:none"
classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="http://pmmintranet.net/web/activexs/smsx.cab#Version=6,5,439,30">
</object>
<body>
<?
		
		if($_GET[tipo]==1 || $_GET[tipo]==3){
			$s = "SELECT
			gv.id, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha, 
			gv.idsucursalorigen, 
			DATE_FORMAT(gv.hora_registro, '%H:%i') AS hora, 
			IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega,
			csd.prefijo AS destino, cso.prefijo AS origen,
			DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
			gv.evaluacion, gv.totalpaquetes,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) cliente,
			CONCAT_WS(' ',d.calle, d.numero, d.colonia) direccion
			FROM guiasventanilla AS gv
			INNER JOIN catalogocliente AS cc ON gv.iddestinatario = cc.id
			LEFT JOIN direccion AS d ON gv.iddirecciondestinatario = d.id
			INNER JOIN catalogosucursal AS csd ON gv.idsucursaldestino = csd.id
			INNER JOIN catalogosucursal AS cso ON gv.idsucursalorigen = cso.id
			WHERE gv.id= '$_GET[codigo]'";
		}elseif($_GET[tipo]==2){
			$s = "SELECT
			gv.id, DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
			gv.idsucursalorigen,
			DATE_FORMAT(gv.hora_registro, '%H:%i') AS hora, 
			IF(gv.ocurre=0,'EAD','OCURRE') AS tipoentrega,
			csd.prefijo AS destino, cso.prefijo AS origen,
			DATE_FORMAT(gv.fecha, '%d/%m/%Y') AS fecha,
			gv.evaluacion, gv.totalpaquetes,
			CONCAT_WS(' ',cc.nombre,cc.paterno,cc.materno) cliente,
			CONCAT_WS(' ',d.calle, d.numero, d.colonia) direccion
			FROM guiasempresariales AS gv
			INNER JOIN catalogocliente AS cc ON gv.iddestinatario = cc.id
			LEFT JOIN direccion AS d ON gv.iddirecciondestinatario = d.id
			INNER JOIN catalogosucursal AS csd ON gv.idsucursaldestino = csd.id
			INNER JOIN catalogosucursal AS cso ON gv.idsucursalorigen = cso.id
			WHERE gv.id= '$_GET[codigo]'";
		}
			$r = mysql_query($s, $l) or die($s);
			$f = mysql_fetch_object($r);
		
		$cantidad = 1;
		if($_GET[tipo]==1 || $_GET[tipo]==2){
			$s = "select emd.*, cd.descripcion 
			from evaluacionmercanciadetalle as emd
			inner join catalogodescripcion as cd on emd.descripcion = cd.id
			where evaluacion = $f->evaluacion and sucursal = $f->idsucursalorigen";
		}else if($_GET[tipo]==3){
			$s = "select emd.*, cd.descripcion 
			from correointernodetalle as emd
			inner join catalogodescripcion as cd on emd.descripcion = cd.id
			where evaluacion = ".$_GET[correo]."";
		}
		$rx = mysql_query($s,$l) or die($s);
		$cant = 0;
		while($fx = mysql_fetch_object($rx)){
			$pesousado = ($fx->peso>$fx->volumen)?$fx->peso:$fx->volumen;
			#111
			for($i=0;$i<$fx->cantidad;$i++){
				$cant++;
				if($_GET[valor]<4 || ($_GET[folio]==$cant)){
					$codigopaquete 	= $_GET[codigo].str_pad($cantidad,4,"0",STR_PAD_LEFT).str_pad($f->totalpaquetes,4,"0",STR_PAD_LEFT);
					$pesokg			= $fx->peso;
					$pesovolumen	= $fx->volumen;
					$medidas		= $fx->largo."x".$fx->ancho."x".$fx->alto
?>
<table width="382" border="0" cellpadding="0" cellspacing="0">

<tr>
  <td width="95" height="72" valign="top"><img src="../img/logopmmazul.png" /></td>
  <td width="287" valign="top"><table width="293" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="310" height="19" align="center" class="titulo_cliente"><table width="290" border="0" cellpadding="0" cellspacing="0">
          <tr>
             <td height="28" colspan="4" class="texto_normal" style="font-size:26px;"><?=$_GET[codigo]?></td>
          </tr>
          <tr>
            <td width="51" class="texto_normal">ORIGEN</td>
            <td width="84" class="titulo_cliente"><?=$f->origen?></td>
            <td width="57" class="texto_normal">DESTINO</td>
            <td width="98" class="titulo_cliente"><?=$f->destino?></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="19" align="center" class="titulo_cliente"><span class="texto_normal3">TIPO DE ENTREGA:
        <?=$f->tipoentrega?>
      </span></td>
    </tr>
    <tr>
      <td height="54" align="center" class="titulo_cliente"><table width="288" border="0" cellpadding="0" cellspacing="0" class="texto_normal2">
          <tr>
            <td width="72" class="texto_bold2">FECHA</td>
            <td width="69">:
              <?=$f->fecha?></td>
            <td width="69"><?=$f->hora?></td>
            <td width="73">&nbsp;</td>
          </tr>
          <tr>
            <td class="texto_bold2">PESO </td>
            <td>:
              <?=$pesokg?>
              KG</td>
            <td class="texto_bold2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="texto_bold2">PESO VOL</td>
            <td class="texto_bold2">:
              <?=$pesovolumen?>
              KG</td>
            <td align="left"><span class="texto_bold2">MEDIDAS:</span></td>
            <td><?=$medidas?></td>
          </tr>
      </table></td>
    </tr>
  </table></td>
</tr>
<tr>
  <td height="19" colspan="2" valign="top">
  	<table width="383" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="texto_bold2" align="center"><?=$f->cliente?></td>
        </tr>
        <tr>
        	<td class="texto_bold2" align="center"><?=$f->direccion?></td>
        </tr>
    	<tr>
      <td height="30" align="center" class="titulo_cliente"><?=$cantidad?>
        DE
        <?=$f->totalpaquetes?></td>
    </tr>
    <tr>
      <td height="14" align="center" class="texto_bold2">DETALLE DE ENVIO</td>
    </tr>
    <tr>
      <td height="14" align="center" class="texto_normal2">1
        <?=$fx->descripcion?>
        DICE CONTENER
        <?=$fx->contenido?></td>
    </tr>
    </table>  </td>
</tr>
<tr>
   	<td height="19" colspan="2" valign="middle" align="center"><?
			  echo "<table border=0px cellspacing=0 cellpadding=0><tr><td>
			  <img width=377 src='../codigobarrasnuevo/image.php?code=$codigopaquete&style=68&type=C128A&width=550&height=100&xres=2&font=4''>
			  </td></tr></table>";
			 ?></td>
</tr>
<tr>
	<td colspan="2" valign="top" align="center"><span class="titulo_cliente"><?=$codigopaquete?></span></td>
</tr>
</table>
<H1 class=SaltoDePagina>&nbsp;</H1>
<?
				}
				$cantidad++;
			}
		}
?>

</body>
</html>

<script> 
	window.onload = function(){
		Imprimir();
	}

	function Imprimir(){
		factory.printing.header = "";
		factory.printing.footer = "";
		factory.printing.portrait = false;
		factory.printing.leftMargin = <?=(($cant>1)?15.1:19.0);?>;
		factory.printing.topMargin = 15.0;
		factory.printing.rightMargin = 0.1;
		factory.printing.bottomMargin = 0.1;
	  	factory.printing.Print(false);
		opener.cambiarImpresora2();
		window.close();
	}
</script>