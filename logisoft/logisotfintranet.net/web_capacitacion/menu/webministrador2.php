<?
 session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
/*if(isset($_SESSION['gvalidar'] )!=100){ echo "<script language='javascript' type='text/javascript'>document.location.href='../../index.php';</script>";
	}else{ */
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");	
?>
<title>Administrador WEB</title>
<link href="../css/Tablas.css" rel="stylesheet" type="text/css">
<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css">
<link href="puntovta.css" rel="stylesheet" type="text/css">
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
.style2 {
	color: #464442;
	font-size:9px;
	border: 0px none;
	background:none
}
.style3 {
	font-size: 9px;
	color: #464442;
}
.style4 {color: #025680;font-size:9px }
.style5 {color: #FFFFFF ; font-size:9px}
.Balance {background-color: #FFFFFF; border: 0px none}
.Balance2 {background-color: #DEECFA; border: 0px none;}
-->
<!--
.Estilo3 {
	color: #FFFFFF;
	font-size: 14px;
	font-weight: bold;
}
-->

.nsm{
position:static;
}
.Estilo6 {font-size: 10px; }
a {
	font-weight: bold;
}
</style>
<script language="javascript">
var	u		= document.all;
function cambiar (flag,img) {
if (document.images) {
if (document.images[img].permitirloaded) {
if (flag==1) document.images[img].src = document.images[img].permitir.src
else document.images[img].src = document.images[img].permitir.oldsrc
}
}
}
function preloadcambiar (img,adresse) {
if (document.images) {
img.onload = null;
img.permitir = new Image ();
img.permitir.oldsrc = img.src;
img.permitir.src = adresse;
img.permitirloaded = true;
}
}
</script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta name="tipo_contenido"  content="text/html; charset=iso-8859-1" http-equiv="Content-Type">
<title>Webministrator</title>
<script language="javascript" src="../javascript/bloqueos.js"></script>
</head>

<body style="position:relative">
<form id="form1" name="form1" method="post" action="">
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
 <tr>
   <td><br></td>
 </tr>
 <tr>
      <td height="50"><table width="51%" border="0" align="center" cellpadding="0">
	  <?
	  	$s = "SELECT * FROM features WHERE STATUS=1 ORDER BY id";
		$r = mysql_query($s,$l) or die($s);
		$cont = 0;
		while($f = mysql_fetch_object($r)){
			$cont++;
	  ?>
        <tr>
          <td height="65" align="center">
		  <table width="616" height="87" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="124px" height="81" align="center" valign="middle">
			  <? if($f->nombre!=""){ ?>
			  	<table width="105" height="84" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td height="60" align="center">
						<a href="webministator1.php?feature=<?=$f->id?>" style="border:hidden">
							<img src="../img/<?=$f->imagen?>" name="IMG<?=$cont?>" border="0"  id="IMG<?=$cont?>" onMouseOver="cambiar(1,'IMG<?=$cont?>');" 
							onMouseOut="cambiar(0,'IMG<?=$cont?>');" onload="preloadcambiar(this,'../img/<?=str_replace("_32","_48",$f->imagen)?>');" />
						</a>
						</td>
					</tr>
					<tr>
						<td height="16" align="center" class="formato_fuente"><?=$f->nombre?></td>
					</tr>
				</table>
				<? } ?>
			  </td>
              <td width="124" align="center" valign="middle">
			  <? if($f=mysql_fetch_object($r)){ 
			  	if($f->nombre!=""){
			  $cont++;
			  ?>
			  <table width="105" height="84" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="60" align="center">
				  		<a href="webministator1.php?feature=<?=$f->id?>" style="border:hidden">
							<img src="../img/<?=$f->imagen?>" name="IMG<?=$cont?>" border="0"  id="IMG<?=$cont?>" onMouseOver="cambiar(1,'IMG<?=$cont?>');" 
							onMouseOut="cambiar(0,'IMG<?=$cont?>');" onload="preloadcambiar(this,'../img/<?=str_replace("_32","_48",$f->imagen)?>');" />
						</a>
				  </td>
                </tr>
                <tr>
                  <td height="16" align="center" class="formato_fuente"><?=$f->nombre?></td>
                </tr>
              </table>
			  <? }} ?>
			  </td>
              <td width="124" align="center" valign="middle">
			  <? if($f=mysql_fetch_object($r)){ 
			  if($f->nombre!=""){
			  $cont++;?>
			  <table width="105" height="84" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="60" align="center">
				  		<a href="webministator1.php?feature=<?=$f->id?>" style="border:hidden">
							<img src="../img/<?=$f->imagen?>" name="IMG<?=$cont?>" border="0"  id="IMG<?=$cont?>" onMouseOver="cambiar(1,'IMG<?=$cont?>');" 
							onMouseOut="cambiar(0,'IMG<?=$cont?>');" onload="preloadcambiar(this,'../img/<?=str_replace("_32","_48",$f->imagen)?>');" />
						</a>
				  </td>
                </tr>
                <tr>
                  <td height="16" align="center" class="formato_fuente"><?=$f->nombre?></td>
                </tr>
              </table>
			  <? }} ?>
			  </td>
              <td width="124" align="center" valign="middle">
			  <? if($f=mysql_fetch_object($r)){
			  if($f->nombre!=""){ 
			  $cont++;?>
			  <table width="105" height="84" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="60" align="center">
				  		<a href="webministator1.php?feature=<?=$f->id?>" style="border:hidden">
							<img src="../img/<?=$f->imagen?>" name="IMG<?=$cont?>" border="0"  id="IMG<?=$cont?>" onMouseOver="cambiar(1,'IMG<?=$cont?>');" 
							onMouseOut="cambiar(0,'IMG<?=$cont?>');" onload="preloadcambiar(this,'../img/<?=str_replace("_32","_48",$f->imagen)?>');" />
						</a>
				  </td>
                </tr>
                <tr>
                  <td height="16" align="center" class="formato_fuente"><?=$f->nombre?></td>
                </tr>
              </table>
			  <? }} ?>
			  </td>
            </tr>
          </table>
		  </td>
          </tr>
	  <?
	  	}
	  ?>
      </table>
      <p>
                
        </p></td>
    </tr>
  </table>   
</form>
</form>
</body>
</html>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'MENÚ PRINCIPAL';
</script>
<? //} ?>