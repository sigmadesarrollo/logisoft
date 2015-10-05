<?	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../Conectar.php');
	$link=Conectarse('webpmm');
	$idusuario=$_GET['idusuario']; $modulo=$_GET['modulo'];	$cancelar=$_GET['cancelar'];
	$usuario=$_GET['usuario'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript');
function Verificar(){
	VerificarUsuario(document.getElementById('usuario').value,document.getElementById('password').value,'<?=$idusuario ?>','<?=$modulo ?>','<?=$cancelar ?>');		
}
function EliminarApostrofe(e,caja){
	if(!isNS4){
		if (event.keyCode==34 || event.keyCode==39) event.returnValue = false;
	}else{
		if (event.which==34 || event.which==39) return false;
	}
}

function Autorizar(){
	if(document.getElementById('autorizar').value=="SI"){
		if(document.all.cancelar.value=="cancelar"){
			window.parent.ConfirmarCancelar(document.getElementById('autorizar').value);
			window.parent.VentanaModal.cerrar();
		}else{
			window.parent.registrar(document.getElementById('autorizar').value);
			window.parent.VentanaModal.cerrar();
		}
		
	}else if(document.getElementById('autorizar').value=="NO"){
		mens.show('A','No cuenta con permisos para realizar esta operación','¡Atención!','password');
	}	
}

	window.onload = function(){		
		document.all.password.select();
	}
</script>
<script src="select.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Inicio de Sesi&oacute;n Secundaria</title>
<link href="Tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>
<body>
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
                <input name="usuario" type="text" class="Tablas" id="usuario" style="text-transform:uppercase" value="<?=$_SESSION[NOMBREUSUARIO] ?>" size="15" onKeyPress="if(event.keyCode==13){document.all.password.focus()}">
              </label></td>
            </tr>
            <tr>
              <td class="Estilo1">Password:</td>
              <td><label>
                <input name="password" type="password" class="Tablas" style="text-transform:uppercase" id="password" value="<?=$password ?>" size="15" onKeyPress="if(event.keyCode==13){ Verificar();setTimeout('Autorizar()',1000); }">
              </label></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div id="txtHint">
            <input name="autorizar" type="hidden" id="autorizar" onChange="Autorizar()" value="<?=$autorizar ?>">
            <input name="cancelar" type="hidden" id="cancelar" onChange="Autorizar()" value="<?=$cancelar ?>">
          </div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><table width="71" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="71"><img src="../img/aceptar.gif" width="71" height="25" style="cursor:pointer" onClick="Verificar();setTimeout('Autorizar()',1000);"></td>
			  
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  </form>

</body>
</html>
