<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script>
	
	function mostrar(modulo){
		parent.<?=$_GET[funcion] ?>(modulo);
		//parent.VentanaModal.cerrar();
	}

</script>
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="300" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Seleccionar Modulo </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Buscar:</td>
          <td><select name="select" onchange="mostrar(this.value)" class="Tablas">
            <option value="0">SELECCIONAR</option>
            <option value="1">LOCALIZADOR DE GUIA</option>
            <option value="2">BUSCADOR DE COLONIAS</option>
          </select></td>
        </tr>
        <tr>
          <td width="75">&nbsp;</td>
          <td width="325"><label></label></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>
