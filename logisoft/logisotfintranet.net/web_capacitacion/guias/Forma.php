<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	/* Big box with list of options */
	#ajax_listOfOptions{
		position:absolute;	/* Never change this one */
		width:205px;	/* Width of box */
		height:250px;	/* Height of box */
		overflow:auto;	/* Scrolling features */
		border:1px solid #317082;	/* Dark green border */
		background-color:#FFF;	/* White background color */
		text-align:left;
		font-size:0.9em;
		z-index:100;
	}
	#ajax_listOfOptions div{	/* General rule for both .optionDiv and .optionDivSelected */
		margin:1px;		
		padding:1px;
		cursor:pointer;
		font-size:0.9em;
	}
	#ajax_listOfOptions .optionDiv{	/* Div for each item in list */
		
	}
	#ajax_listOfOptions .optionDivSelected{ /* Selected item in the list */
		background-color:#317082;
		color:#FFF;
	}
	#ajax_listOfOptions_iframe{
		background-color:#F00;
		position:absolute;
		z-index:5;
	}
	
	form{
		display:inline;
	}
<!--
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">T&Iacute;TULO</td>
    </tr>
    <tr>
      <td><table width="550" border="0" cellspacing="0" cellpadding="0">
        
        <tr>
          <td width="60">Gu&iacute;a:</td>
          <td width="202"><label>
            <input name="guia" type="text" class="Tablas" id="guia" maxlength="13" style="width:100px" />
            <img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" style="cursor:pointer" /></label></td>
          <td width="7">&nbsp;</td>
          <td width="73">Suc Origen: </td>
          <td width="203"><input name="sucorigen" type="text" id="sucorigen" class="Tablas" style="width:100px; background-color:#FFFF99" /></td>
          <td width="5">&nbsp;</td>
        </tr>
        <tr>
          <td>Tipo entrega :</td>
          <td><input name="tipoentrega" type="text" id="tipoentrega" class="Tablas" style="width:200px; background-color:#FFFF99" /></td>
          <td>&nbsp;</td>
          <td>Destinatario:</td>
          <td><input name="destinatario" type="text" id="destinatario" class="Tablas" style="width:200px; background-color:#FFFF99" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Importe:</td>
          <td><input name="importe" type="text" id="importe" class="Tablas" style="width:200px; background-color:#FFFF99"  /></td>
          <td>&nbsp;</td>
          <td>Cr&eacute;dito disponible :</td>
          <td><input name="creditodisponible" type="text" id="creditodisponible" class="Tablas" style="width:200px; background-color:#FFFF99" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><table width="150" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td><div id="btnguardar" class="ebtn_Traspasar" onclick="guardar()"></div></td>
              <td align="right"><div class="ebtn_nuevo" onclick="mens.show('C','Perdera la informaci&oacute;n capturada &iquest;Desea continuar?', '', '', 'limpiar()')"></div></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>