<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	include('../Conectar.php');
	$link=Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="../recoleccion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var mens = new ClaseMensajes();
	mens.iniciar('../javascript');

	function ObtenerColonia(e,obj){
			tecla=(document.all) ? e.keyCode : e.which;		
			   if(tecla!=13){
				 return;	
				}else{
		ConsultaColoniaClientes(document.getElementById('buscar').value,'direccion');
		}
	}
function LimpiarMensaje(){
	window.opener.document.getElementById('abierto').value="";
}
</script>
<script src="select.js"></script> 
<style type="text/css">
.Tablas {
	font-family: tahoma;
	font-size: 9px;
	font-style: normal;
	font-weight: bold;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Buscar Colonias</title>
</head>

<body onLoad="document.form1.buscar.focus()" onUnload="LimpiarMensaje()">
<form id="form1" name="form1" method="post" action="">
<table width="400" height="100"  border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td width="40%" class="FondoTabla"><label>Colonia</label></td>
      <td width="7%" class="FondoTabla">CP</td>
      <td width="17%" class="FondoTabla">Poblaci&oacute;n</td>
      <td width="16%" class="FondoTabla">Municipio</td>
      <td width="20%" class="FondoTabla">Estado</td>
    </tr>
    <tr>
      <td colspan="5" class="FondoTabla"><input onKeyPress="return ObtenerColonia(event,this)" class="Tablas" name="buscar" type="text" id="buscar" size="50" style="font-size:9px; font:tahoma; text-transform:uppercase " /></td>
    </tr>
    <tr>
      <td colspan="5" class="Tablas"><div id="txtDir" style="width:100%; height:100px; overflow: scroll;">
          <table width="90%" border="0" align="left" cellpadding="0" cellspacing="0" class="Tablas" id="tab">
            <tr>
              <td width="112" class="Tablas"><input name="text" type="text" style="cursor:pointer; border:none; font-size:9px" class="Tablas" size="25" readonly=""  /></td>
              <td width="31" class="Tablas"><input name="text" type="text" style="cursor:pointer; border:none; font-size:9px" class="Tablas" size="3" readonly=""></td>
              <td width="56" class="Tablas">&nbsp;
              <input name="text" type="text" class="Tablas" style="cursor:pointer; border:none; font-size:9px" size="9" readonly=""  /></td>
              <td width="54" class="Tablas">&nbsp;
              <input name="text" type="text" class="Tablas" style="cursor:pointer; border:none; font-size:9px" size="9" readonly=""  /></td>
              <td width="93" class="Tablas">&nbsp;
              <input name="text" type="text" style="cursor:pointer; border:none; font-size:9px" size="9" readonly="" class="Tablas" /></td>
              <td width="10"></td>
            </tr>
          </table>
      </div></td>
    </tr>
</table>
</form>
</body>
</html>
