<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once('../Conectar.php');
	$link = Conectarse('webpmm');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ajax.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="../javascript/ajaxlist/ajax.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<script>
	var u = document.all;
	
	window.onload = function(){
		u.motivo.focus();
	}
	
	function validar(){
		if(u.motivo.value==0 || u.motivo.value==""){
			alerta('Debe capturar Motivo','¡Atención!','motivo');
		}else if(u.notificar.value==""){
			alerta('Debe capturar Notificación','¡Atención!','notificar');
		}else if(u.observaciones.value==""){
			alerta('Debe capturar Observaciones','¡Atención!','observaciones');		
		}else{		
	parent.Reprogramacion(u.motivo.value, u.motivo.options[u.motivo.selectedIndex].text, u.notificar.value,u.observaciones.value);
	parent.VentanaModal.cerrar();
		}
	}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
.Estilo2 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>

<style type="text/css">
<!--
.style2 {	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style5 {	color: #FFFFFF;
	font-size:8px;
	font-weight: bold;
}
-->
</style>

<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css">
<link href="Tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
<table width="350" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="275" class="FondoTabla Estilo4">MOTIVOS REPROGRAMACI&Oacute;N</td>
  </tr>
  <tr>
    <td><table width="330" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="226" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td><span class="Tablas">Motivo: </span></td>
        <td><span class="Tablas">
          <? $s = mysql_query("SELECT id, descripcion FROM catalogomotivos WHERE clasificacion='REPROGRAMACION RECOLECCION'",$link);
		 ?>
          <select name="motivo" class="Tablas" id="motivo" onChange="document.all.notificar.focus()" style="width:250px; font-size:9px">
            <option selected="selected">SELECCIONAR MOTIVO</option>
            <?
			while($f = mysql_fetch_object($s)){?>
            <option value="<?=$f->id; ?>" <? if($f->id==$_GET['motivo']) echo "selected";?>>
              <?=$f->descripcion; ?>
              </option>
            <? 
			}
		?>
          </select>
        </span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Notificar a:</td>
        <td><input name="notificar" onKeyPress="if(event.keyCode==13){document.all.observaciones.focus();}" class="Tablas" style="width:250px; text-transform:uppercase" type="text" value="<?=$_GET['notificacion']; ?>"></td>
      </tr>
      <tr>
        <td><label></label></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">Observaciones:</td>
        <td><label>
          <textarea name="observaciones" class="Tablas" style="width:250px; text-transform:uppercase"><?=$_GET['observaciones']; ?></textarea>
        </label></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><table width="100" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td>&nbsp;</td>
              <td><div class="ebtn_guardar" onClick="validar();"></div></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</form>
</body>
</html>