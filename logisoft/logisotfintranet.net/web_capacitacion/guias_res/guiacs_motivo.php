<?
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<style type="text/css">
<!--
.Estilo3 {font-size: 12px}
-->
</style>
</head>
<script>
	window.onload = function(){
		<? if($_GET[id]==""){ ?>
		document.all.txtcomentario.value="";
		<? } ?>
	}

	function validarComentario(){
		if(document.all.txtcomentario.value==""){
			alerta("Proporcione el motivo por el que se hace la sustitucion","¡Atencion!",'txtcomentario');
			return false;
		}
		parent.document.all.motivocancelacion.value = document.all.txtcomentario.value;
		parent.registrarGuia();
	}
	
	<?
		if($_GET[id]!=""){
			$s = "SELECT motivocancelacion FROM guiasventanilla_cs WHERE id = $_GET[id]";
			$r = mysql_query($s,$l) or die($s);
			$f = mysql_fetch_object($r);
		}
	?>
	
</script>
<body>
<center>
<table width="404" border="1" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="400" class="FondoTabla Estilo3">Motivos Cancelacion</td>
  </tr>
  <tr>
    <td>
    	<table width="400" border="0" cellpadding="0" cellspacing="0">
<tr>
        		<td width="10"></td>
                <td width="370">
                <textarea name="txtcomentario" rows="4" style="<? if($_GET[id]!=""){ ?> background:#FFFF99;<? } ?> width:380px; text-transform:uppercase" <? if($_GET[id]!=""){ ?> readonly="readonly" <? } ?>><?=$f->motivocancelacion?></textarea>
                </td>
                <td width="10"></td>
        	</tr>
<tr>
  <td></td>
  <td align="center"><? if($_GET[id]==""){ ?><div class="ebtn_guardar" onclick="validarComentario()"></div><? } ?></td>
  <td></td>
</tr>
        </table>    </td>
  </tr>
</table>
</center>
</body>
</html>
