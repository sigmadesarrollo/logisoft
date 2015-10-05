<?	session_start();
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
	
	$s = "SELECT calle, numero, crucecalles, cp, colonia, poblacion, municipio, telefono2, sector FROM recoleccion
	WHERE folio='".$_GET[folio]."' AND sucursal=".$_GET[sucursal]."";
	$r = mysql_query($s,$l) or die($s);
	$f = mysql_fetch_object($r);	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Direccion Recoleccion</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="581" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Direcci&oacute;n de Recolecci&oacute;n </td>
    </tr>
    <tr>
      <td><table width="580" border="0" cellspacing="0" cellpadding="0">
        
        <tr>
          <td width="81">Calle: </td>
          <td colspan="3" id="celda_des_calle"><span class="Tablas">
            <input name="calle" type="text" class="Tablas" id="calle" style="width:280px;background:#FFFF99" value="<?=$f->calle ?>" readonly=""/>
          </span></td>
          <td width="199"><span class="Tablas"> Numero:
            <input name="numero" type="text" class="Tablas" id="numero" style="width:120px;background:#FFFF99" value="<?=$f->numero ?>" readonly=""/>
          </span></td>
          <td width="5">&nbsp;</td>
        </tr>
        <tr>
          <td>Colonia:</td>
          <td width="177"><span class="Tablas">
            <input name="colonia" type="text" class="Tablas" id="colonia" style="width:165px;background:#FFFF99" value="<?=$f->colonia ?>" readonly=""/>
          </span></td>
          <td width="49">&nbsp;</td>
          <td width="69">C.P.:</td>
          <td><span class="Tablas">
            <input name="cp" type="text" class="Tablas" id="cp" style="width:165px;background:#FFFF99" value="<?=$f->cp ?>" readonly=""/>
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Cruce de Calles:</td>
          <td colspan="5"><span class="Tablas">
            <input name="crucecalles" type="text" class="Tablas" id="crucecalles" style="width:460px;background:#FFFF99" value="<?=$f->crucecalles ?>" readonly=""/>
          </span></td>
        </tr>
        <tr>
          <td>Poblacion:</td>
          <td><span class="Tablas">
            <input name="poblacion" type="text" class="Tablas" id="poblacion" style="width:165px;background:#FFFF99" value="<?=$f->poblacion ?>" readonly=""/>
          </span></td>
          <td>&nbsp;</td>
          <td>Mun./Deleg.:</td>
          <td><span class="Tablas">
            <input name="municipio" type="text" class="Tablas" id="municipio" style="width:165px;background:#FFFF99" value="<?=$f->municipio ?>" readonly=""/>
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Telefono:</td>
          <td><span class="Tablas">
            <input name="telefono2" type="text" class="Tablas" id="telefono2" style="width:165px;background:#FFFF99" value="<?=$f->telefono2 ?>" readonly=""/>
          </span></td>
          <td>&nbsp;</td>
          <td>Sector:            </td>
          <td><span class="Tablas">
            <input name="sector" type="text" class="Tablas" id="sector" style="width:165px;background:#FFFF99" value="<?=$f->sector ?>" readonly=""/>
          </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
