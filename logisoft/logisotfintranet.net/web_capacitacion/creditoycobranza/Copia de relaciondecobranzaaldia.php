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
<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
  <tr>
    <td width="600" class="FondoTabla Estilo4">Datos Generales  </td>
  </tr>
  <tr>
    <td height="98"><div align="center">
      <table width="600" border="0" cellpadding="0" cellspacing="0">
        
        
        
        
        <tr>
          <td class="FondoTabla">Datos del Reporte </td>
        </tr>
        <tr>
          <td><table width="600" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>Sucursal</td>
              <td><span class="Tablas">
                <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;" value="<?=sucursal ?>"/>
              </span></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="46">Fecha al: </td>
              <td width="135"><label>
                <select name="select" style="width:100px">
                </select>
              </label></td>
              <td width="20">D&iacute;a:</td>
              <td width="125"><span class="Tablas">
                <input name="dia" type="text" class="Tablas" id="dia" style="width:100px;background:#FFFF99" value="<?=$dia ?>" readonly=""/>
              </span></td>
              <td width="35">Sectro:</td>
              <td width="131"><select name="select3" style="width:100px">
              </select></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="600" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="54">Cobrador:</td>
              <td width="262"><select name="select4" style="width:200px">
              </select></td>
          
              <td width="20"><label>
                <input type="checkbox" name="checkbox" value="checkbox">
              </label></td>
              <td width="264"> Incluir Clientes Dados de Baja</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table width="302" border="0" align="left" cellpadding="0" cellspacing="0">
            <tr>
              <td width="12" height="16" class="formato_columnas_izq"></td>
              <td width="51" class="formato_columnas" align="center">Cliente</td>
              <td width="59" class="formato_columnas" align="center">Nombre</td>
              <td width="59" class="formato_columnas" align="center">Referencia</td>
			  <td width="59" class="formato_columnas" align="center">Fecha</td>
              <td width="59" class="formato_columnas" align="center">Fecha Vto</td>
              <td width="59" class="formato_columnas" align="center">Factura</td>
              <td width="59" class="formato_columnas" align="center">Importe</td>
              <td width="59" class="formato_columnas" align="center">Saldo Actual</td>
              <td width="59" class="formato_columnas" align="center">Cobrar</td>
              <td width="59" class="formato_columnas" align="center">Revisión</td>              <td width="9"  class="formato_columnas_der"></td>
            </tr>
            <tr>
              <td colspan="12" align="right"><div id="div" name="detalle" style="width:599px; height:80px; overflow:auto" align="left">
                  <? $line = 0; ?>
                  <table width="574" border="0" cellspacing="0" cellpadding="0">
                    <?		
			while($line<=200){?>
                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >
                      <td height="16" width="22" ><input name="id2" type="hidden" value="<?=$row[id] ?>" /></td>
                      <td width="152" align="center" class="style31"  ><input name="cliente" type="text" class="style2" id="cliente" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
					  <td width="152" align="center" class="style31"  ><input name="nombre" type="text" class="style2" id="nombre" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
                      <td width="164" align="center" class="style31"><input name="referencia" type="text" class="style2" id="referencia" style="font-size:8px; font:tahoma;font-weight:bold" readonly="" size="10" /></td>
					  <td width="152" align="center" class="style31"  ><input name="fecha" type="text" class="style2" id="fecha" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
					  <td width="152" align="center" class="style31"  ><input name="fechav" type="text" class="style2" id="fechav" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
					  <td width="152" align="center" class="style31"  ><input name="factura" type="text" class="style2" id="factura" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
					  <td width="152" align="center" class="style31"  ><input name="importe" type="text" class="style2" id="importe" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
					  <td width="152" align="center" class="style31"  ><input name="saldo" type="text" class="style2" id="saldo" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
					  <td width="152" align="center" class="style31"  ><input name="cobrar" type="checkbox" class="style2" id="cobrar" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
					  <td width="152" align="center" class="style31"  ><input name="revicion" type="checkbox" class="style2" id="revicion" readonly="" style="font-size:8px; font:tahoma; font-weight:bold" size="8" /></td>
					  
                      <td width="12" align="center" class="style31">&nbsp;</td>
                    </tr>
                    <?
		$line ++ ; }			
	?>
                  </table>
              </div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><table width="600" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="44">Cliente</td>
              <td width="225"><span class="Tablas">
                <input name="cliente" type="text" class="Tablas" id="cliente" style="width:200px;background:#FFFF99" value="<?=$cliente ?>" readonly=""/>
              </span></td>
              <td width="72">Total a Pagar </td>
              <td width="176"><span class="Tablas">
                <input name="tpagar" type="text" class="Tablas" id="tpagar" style="width:100px;background:#FFFF99" value="<?=$tpagar ?>" readonly=""/>
              </span></td>
              <td width="15">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="532">&nbsp;</td>
          </tr>
      </table>
    </div></td>
  </tr>
</table>
<p>&nbsp;</p>
</form>
</body>
<script>
	parent.frames[1].document.getElementById('titulo').innerHTML = 'RELACIÓN DE COBRANZA AL DÍA';
</script>
</html>