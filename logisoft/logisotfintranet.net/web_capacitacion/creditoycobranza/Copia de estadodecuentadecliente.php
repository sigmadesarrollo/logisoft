<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">
<link href="../facturacion/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../facturacion/puntovta.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
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
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
.Estilo5 {
	font-size: 9px;
	font-family: tahoma;
	font-style: italic;
}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <br>
  <table width="439" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="435" class="FondoTabla Estilo4">Datos Generales </td>
  </tr>
  <tr>
    <td height="142"><div align="center">
      <table width="259" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="352"><table width="398" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="271" height="11"><table width="400" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><table width="400" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="260"><div align="right">Sucursal
                        <select name="select" style="width:100px">
                          </select>
                      </div></td>
                      <td width="140"> <div align="right">Fecha<span class="Tablas">
                        <input name="fechab" type="text" class="Tablas" id="fechab" style="width:100px;background:#FFFF99" value="<?=$fechab ?>" readonly=""/>
                      </span></div></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><table width="400" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="24">Folio</td>
                      <td colspan="3"><select name="select2" style="width:100px">
                      </select>
                        <span class="Tablas">
                        <input name="foliob" type="text" class="Tablas" id="foliob" style="width:100px;background:#FFFF99" value="<?=$foliob ?>" readonly=""/>
                        </span></td>
                    </tr>
                    <tr>
                      <td width="58" height="24"><label></label>
                        Cliente</td>
                      <td width="100"><label><span class="Tablas">
                        <input name="cliente" type="text" class="Tablas" id="cliente" style="width:100px" value="<?=$cliente ?>"/>
                      </span></label></td>
                      <td width="24"><div class="ebtn_buscar"></div></td>
                      <td width="218"><span class="Tablas">
                        <input name="cliente2" type="text" class="Tablas" id="cliente2" style="width:200px" value="<?=$cliente2 ?>"/>
                      </span></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><table width="418" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>Descripci&oacute;n</td>
                      <td colspan="3"><span class="Tablas">
                        <input name="descripcion" type="text" class="Tablas" id="descripcion" style="width:200px" value="<?=$descripcion ?>"/>
                      </span></td>
                    </tr>
                    <tr>
                      <td>Cobredor</td>
                      <td width="152"><select name="select3" style="width:100px">
                      </select></td>
                      <td width="99">Tipo Pago </td>
                      <td width="109"><select name="select4" style="width:100px">
                      </select></td>
                    </tr>
                    <tr>
                      <td colspan="4"><table width="419" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="49">Cargo</td>
                          <td width="50"><select name="select7" style="width:50px">
                          </select></td>
                          <td width="102"><span class="Tablas">
                            <input name="cargo" type="text" class="Tablas" id="cargo" style="width:100px" value="<?=$cargo ?>"/>
                          </span></td>
                          <td width="42">Importe</td>
                          <td width="100"><span class="Tablas">
                            <input name="importe" type="text" class="Tablas" id="importe" style="width:100px" value="<?=$importe ?>"/>
                          </span></td>
                          <td width="75"><label>
                            <input type="checkbox" name="checkbox3" value="checkbox">
                            Desaplicar</label></td>
                        </tr>
                      </table></td>
                      </tr>
                    <tr>
                      <td width="58"><input name="radiobutton" type="radio" value="radiobutton"></td>
                      <td colspan="3">Todos</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td class="FondoTabla">Contenido del reporte </td>
                </tr>
                <tr>
                  <td><table width="400" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="21"><label>
                        <input type="checkbox" name="checkbox" value="checkbox">
                      </label></td>
                      <td colspan="3">Incluir Clientes Dados de Baja
                        <label></label></td>
                      <td width="20"><input type="checkbox" name="checkbox2" value="checkbox"></td>
                      <td width="173">Corte de Hoja por Cliente</td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td>A la Fecha
                    <label>
                    <select name="select5" style="width:100px">
                    </select>
Al Periodo
<select name="select5" style="width:100px">
</select>
                    </label></td>
                </tr>
                <tr>
                  <td>Corte
                    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select name="select6" style="width:100px">
                    </select>
                    </label></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'ESTADO DE CUENTA DE CLIENTE';
</script>
</html>