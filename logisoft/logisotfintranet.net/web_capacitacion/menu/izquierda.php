<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>

<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="estilosPrincipal.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
</style>
<script src="../../Scripts/swffix_modified.js" type="text/javascript"></script>
</head>
<script>
	window.onbeforeunload = function(){
		window.open("../cerrarSession.php?valor","miventana","width=50 height=50 toolbar=0 menubar=0 scrollbars=0 resizable=0 ");
	}
</script>
<body>

<table width="158" border=0 align=center cellpadding=0 cellspacing=0>
  <tr>
    <td align="center" class="tituloSucursal" style="background:url(imagen/logocuadro.jpg)">
    <select name="nombresucursal" style="width:218px; display:none" onChange="document.all.idsucursal.value=this.options[this.selectedIndex].idsuc;" disabled>
    	<?
			$s = "select cs.descripcion as sdesc, cs.id as idsuc
		from catalogosucursal as cs where id = $_SESSION[IDSUCURSAL]";
		$idsuc = 0;
		$r = mysql_query($s,$l) or die($s);
		$f = mysql_fetch_object($r);
		?>
    	<option value="<?=$f->idsuc?>" idsuc="<?=$f->idsuc?>"><?=$f->sdesc?></option>
    </select>
		<?=strtoupper($f->sdesc)?>
    </td>
  </tr>
  <tr>
    <td align="center">
    <input type="hidden" name="idsucursal" value="<?=$f->idsuc?>">
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
	<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">  
  <tr>
    <td valign="top">
    	<iframe id="frameizquierda" frameborder="0" scrolling="no" width="156" height="370px" src="ventasi.php"></iframe>    </td>
  </tr>
  <tr>
    <td valign="top" height="5PX"></td>
  </tr>
  <tr>
    <td valign="top"><object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="154" height="100">
      <param name="movie" value="reloj.swf">
      <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="reloj.swf" width="154" height="100">
        <!--<![endif]-->
        <param name="quality" value="high">
        <param name="wmode" value="opaque">
        <param name="swfversion" value="8.0.35.0">
        <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
        <param name="expressinstall" value="../../Scripts/expressInstall.swf">
        <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
        <div>
          <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
        </div>
        <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object></td>
  </tr>
</table></td>
</tr>
</table>
<script type="text/javascript">
<!--
SWFFix.registerObject("FlashID");
//-->
</script>
</body>
</html>