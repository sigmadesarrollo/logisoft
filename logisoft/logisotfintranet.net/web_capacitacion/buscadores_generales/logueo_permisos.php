<?
	session_start();
	if($_GET[accion]==1){
		header('Content-type: text/xml');
		require_once("../Conectar.php");
		$l = Conectarse("webpmm");
		
		
		
		$s = "select * from catalogoempleado where user = '$_GET[usuario]' and password = '$_GET[password]'";
		$r = mysql_query($s,$l) or die($s);
		if(mysql_num_rows($r)>0){
			$f = mysql_fetch_object($r);
			$idusuario = $f->id;
			
			if($_GET['interface']=="GuiaVentanilla"){
				#Cordinador de sucursal == Gerente
				if($f->puesto==34 || $f->puesto==7 || $f->puesto==8){
					$permiso = "1";
					$mensaje = "1";
				}else{
					$permiso = "0";
					$mensaje = "No cuenta con los suficientes permisos";
				}
			}if($_GET['interface']=="llegadaUnidad"){
				#Cordinador de sucursal == Gerente
				if($f->grupo==10 && ($_SESSION[IDUSUARIO]==2 || $_SESSION[IDUSUARIO]==5)){
					$permiso = "1";
					$mensaje = "1";
				}else{
					$permiso = "0";
					$mensaje = "No cuenta con los suficientes permisos";
				}
			}/*else{
				$permiso = "1";
				$mensaje = "1";
			}*/
		}else{
			$permiso = "0";
			$mensaje = "Usuario y Contraseña Incorrectos";
		}
		echo "<xml version=\"1.0\" standalone=\"yes\" encoding=\"iso-8859-1\"> 
			<datos>
				<permiso>".cambio_texto($permiso)."</permiso>
				<mensaje>".cambio_texto($mensaje)."</mensaje>
			</datos>
			</xml>";
	}else{
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Inicio de Sesi&oacute;n Secundaria</title>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<script type="text/javascript" src="../javascript/ajax.js"></script>
<style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
.Tablas {
	font-family: tahoma;
	font-size: 9px;
	font-style: normal;
	font-weight: bold;
}
-->
</style>
<script>
	function loguear(){
		consulta("resultado","logueo_permisos.php?accion=1&interface=<?=$_GET[modulo]?>&modulo=<?=$_GET[modulo]?>&usuario="+document.all.usuario.value+"&password="+document.all.password.value+"&ran="+Math.random());	
	}
	function resultado(datos){
		permiso		= datos.getElementsByTagName('permiso').item(0).firstChild.data;
		mensaje		= datos.getElementsByTagName('mensaje').item(0).firstChild.data;
		
		if(permiso==1){
			parent.VentanaModal.cerrar();
			parent.<?=$_GET[funcion]?>();
		}else{
			alerta(mensaje,"¡Atención!","usuario");
		}
	}
</script>
</head>
<body onLoad="document.form1.password.focus()">
<form name="form1" method="post" action="">
  <table width="319" height="223" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr style="background:url(../img/InicioSecundaria.gif)">
      <td width="319" height="223"><table width="250" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50">&nbsp;</td>
          <td width="200" height="60">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td height="80"><table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td height="50" colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td width="89"><span class="Estilo1">Usuario:</span></td>
              <td width="111"><label>
                <input name="usuario" type="text" class="Tablas" id="usuario" value="<?=$_SESSION[NOMBREUSUARIO] ?>" size="15">
              </label></td>
            </tr>
            <tr>
              <td class="Estilo1">Password:</td>
              <td><label>
                <input name="password" type="password" class="Tablas" id="password" value="" onKeyPress="if(event.keyCode == 13){loguear();}" size="15">
              </label></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
          <input type="hidden" name="modulo" value="<?=$_GET[modulo]?>">
          <input type="hidden" name="funcion" value="<?=$_GET[funcion]?>">
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><table width="71" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="71"><img src="../img/aceptar.gif" width="71" height="25" style="cursor:pointer" onClick="loguear();"></td>
			  
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  </form></body>
</html>
<?
	}
?>