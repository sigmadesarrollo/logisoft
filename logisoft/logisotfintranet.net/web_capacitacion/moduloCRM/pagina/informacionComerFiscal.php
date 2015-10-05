<?	session_start();
	require_once('../../Conectar.php');
	$l = Conectarse('webpmm');
	
	$s = "SELECT * FROM catalogocliente WHERE id=".$_GET[cliente]."";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r); 
	$tipopersona = (($f->personamoral=="SI")? "moral" : "fisica");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ajax.js"></script>
<script>
	var u = document.all;
	
	window.onload = function(){	
		if(u.tipopersona.value=="moral" && u.tipo.value=="fiscal"){
			u.apellidos.style.display = "none";
			u.comercial.style.display = "none";
		}	
		if(u.tipopersona.value=="moral" && u.tipo.value=="comercial"){
			u.apellidos.style.display = "none";
			u.fiscal.style.display = "none";
		}		
		if(u.tipopersona.value=="fisica" && u.tipo.value=="fiscal"){			
			u.comercial.style.display = "none";
		}	
		if(u.tipopersona.value=="fisica" && u.tipo.value=="comercial"){
			u.fiscal.style.display = "none";
		}		
		obtenerDatos('<?=$_GET[cliente] ?>');
	}
	
	function obtenerDatos(cliente){
		consultaTexto("mostrarDatos","consultas_crm.php?accion=1&cliente="+cliente);
	}
	function mostrarDatos(datos){
		var obj = eval(convertirValoresJson(datos));		
		if(u.tipopersona.value=="moral" && u.tipo.value=="fiscal"){
			u.fnombre.value 	= obj[0].nombre;
			u.fdireccion.value 	= obj[0].direccion;
			u.fpoblacion.value 	= obj[0].poblacion;
			u.festado.value 	= obj[0].estado;
			u.fpostal.value 	= obj[0].cp;
			u.frfc.value 		= obj[0].rfc;
		}	
		if(u.tipopersona.value=="moral" && u.tipo.value=="comercial"){
			u.cnombre.value 	= obj[0].nombre;
			u.cemail.value 		= obj[0].email;
			u.ccelular.value 	= obj[0].celular;
			u.cweb.value 		= obj[0].web;
		}		
		if(u.tipopersona.value=="fisica" && u.tipo.value=="fiscal"){			
			u.fnombre.value 	= obj[0].nombre;
			u.fpaterno.value	= obj[0].paterno;
			u.fmaterno.value	= obj[0].materno;
			u.fdireccion.value 	= obj[0].direccion;
			u.fpoblacion.value 	= obj[0].poblacion;
			u.festado.value 	= obj[0].estado;
			u.fpostal.value 	= obj[0].cp;
			u.frfc.value 		= obj[0].rfc;
		}	
		if(u.tipopersona.value=="fisica" && u.tipo.value=="comercial"){
			u.cnombre.value 	= obj[0].nombre;
			u.cpaterno.value	= obj[0].paterno;
			u.cmaterno.value	= obj[0].materno;			
			u.cemail.value 		= obj[0].email;
			u.ccelular.value 	= obj[0].celular;
			u.cweb.value 		= obj[0].web;;
		}
	}	
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla"><?=$_GET[titulo] ?>
      <input name="tipopersona" type="hidden" id="tipopersona" value="<?=$tipopersona ?>" />
      <input name="tipo" type="hidden" id="tipo" value="<?=$_GET[tipo] ?>" /></td>
    </tr>
    <tr>
      <td><table width="550" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="fiscal">
            <tr>
              <td width="71"><span class="Tablas">Nombre:</span></td>
              <td colspan="5"><span class="Tablas">
                <input class="Tablas" name="fnombre" type="text" id="fnombre" style="text-transform:uppercase; width:463px" readonly="" />
              </span></td>
              </tr>
            
            <tr id="apellidos" class="Tablas">
              <td>Ap. Paterno: </td>
              <td><span class="Tablas" style="width:240px">
                <input class="Tablas" name="fpaterno" type="text" id="fpaterno"  maxlength="100" style="width:190px" readonly="" />
              </span></td>
              <td>&nbsp;</td>
              <td>Ap. Materno: </td>
              <td><input name="fmaterno" class="Tablas" type="text" id="fmaterno"  style="width:190px" readonly=""/></td>
              <td>&nbsp;</td>
            </tr>
            <tr class="Tablas">
              <td>Direcci&oacute;n:</td>
              <td colspan="5"><input class="Tablas" name="fdireccion" type="text" id="fdireccion" style="text-transform:uppercase; width:463px" readonly="" /></td>
              </tr>
            <tr>
              <td class="Tablas">Poblaci&oacute;n:</td>
              <td width="197"><span class="Tablas">
                <input name="fpoblacion" type="text" class="Tablas" id="fpoblacion" maxlength="70" style="font:tahoma;font-size:9px; text-transform:uppercase;width:190px"/>
              </span></td>
              <td width="6">&nbsp;</td>
              <td width="70"><span class="Tablas">Estado:</span></td>
              <td width="192"><span class="Tablas">
                <input name="festado" class="Tablas" type="text" id="festado" style="text-transform:uppercase; font:tahoma; font-size:9px;width:190px" readonly="" />
              </span></td>
              <td width="13">&nbsp;</td>
            </tr>
            <tr>
              <td><span class="Tablas">C.P.:</span></td>
              <td><span class="Tablas">
                <input name="fpostal" type="text" class="Tablas" id="fpostal" maxlength="70"  readonly="" style="font:tahoma;font-size:9px; text-transform:uppercase;width:190px"/>
              </span></td>
              <td>&nbsp;</td>
              <td><span class="Tablas">R.F.C.: </span></td>
              <td><span class="Tablas">
                <input name="frfc" type="text" class="Tablas" id="frfc" maxlength="13"  readonly="" style="font:tahoma;font-size:9px; text-transform:uppercase;width:190px"/>
              </span></td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="comercial">
            <tr>
              <td width="71"><span class="Tablas">Nombre:</span></td>
              <td colspan="5"><span class="Tablas">
                <input class="Tablas" name="cnombre" type="text" id="cnombre" style="text-transform:uppercase; width:463px" readonly="" />
              </span></td>
            </tr>
            <tr id="apellidoscomer" class="Tablas">
              <td>Ap. Paterno: </td>
              <td><span class="Tablas" style="width:240px">
                <input class="Tablas" name="cpaterno" type="text" id="cpaterno"  maxlength="100" style="width:190px" readonly="" />
              </span></td>
              <td>&nbsp;</td>
              <td>Ap. Materno: </td>
              <td><input name="cmaterno" class="Tablas" type="text" id="cmaterno"  style="width:190px" readonly=""/></td>
              <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td class="Tablas">Email:</td>
              <td width="197"><span class="Tablas">
                <input name="cemail" type="text" class="Tablas" id="cemail" maxlength="70" style="font:tahoma;font-size:9px; text-transform:uppercase;width:190px"/>
              </span></td>
              <td width="6">&nbsp;</td>
              <td width="70"><span class="Tablas">Celular:</span></td>
              <td width="192"><span class="Tablas">
                <input name="ccelular" class="Tablas" type="text" id="ccelular" style="text-transform:uppercase; font:tahoma; font-size:9px;width:190px" readonly="" />
              </span></td>
              <td width="13">&nbsp;</td>
            </tr>
            <tr>
              <td><span class="Tablas">Sitio Web:</span></td>
              <td colspan="3"><span class="Tablas">
                <input name="cweb" type="text" class="Tablas" id="cweb" maxlength="70"  readonly="" style="font:tahoma;font-size:9px; text-transform:uppercase;width:270px"/>
              </span></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
