<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Principal</title>

<link href="puntovta.css" rel="stylesheet" type="text/css">

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

</style>

<link href="../css/Tablas.css" rel="stylesheet" type="text/css" />

<link href="../css/FondoTabla.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--

.Estilo1 {

	font-family: tahoma;

	color: #FFFFFF;

	font-weight: bold;

}

.Estilo2 {

	color: #FFFFFF;

	font-weight: bold;

}

-->

</style>

</head>



<body>

<form id="form1" name="form1" method="post" action="">

<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">

  <tr>

    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td background="../img/bazul1.jpg" width=5 height=54></td>

        <td width=150 background="../img/bazul2.jpg" class="style1 Estilo1">PUNTO DE VENTA</td>

        <td background="../img/bazul3_v.jpg" width=59></td>

        <td background="../img/bazul4_v.gif">&nbsp;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td><table width="654" border="0" align="center">

      <tr>

        <td class="Tablas">Fecha:</td>

        <td width="10%">&nbsp;

          <input name="fecha" type="text" id="fecha" style="background:#FFFF99;font:tahoma; font-size:8.5px" value="<?=$fecha ?>" size="13" align="top" /></td>

        <td width="6%" class="Tablas">Estado:</td>

        <td colspan="2"><input name="estado" type="text" id="estado" style="background:#FFFF99;font:tahoma; font-size:8.5px" value="<?=$estado ?>" size="13" align="top" /></td>

        <td width="9%">&nbsp;</td>

        <td width="2%"></td>

        <td width="6%">&nbsp;</td>

        <td width="19%">&nbsp;</td>

      </tr>

      <tr>

        <td width="6%" class="Tablas">Recibio:</td>

        <td colspan="3">&nbsp;

          <input name="recibio" type="text" id="recibio" style="background:#FFFF99;font:tahoma; font-size:8.5px" value="<?=$recibio ?>" size="60" />        </td>

        <td width="11%"><span class="Tablas">F. Entrega:</span></td>

        <td><input name="entrega" type="text" id="entrega" style="background:#FFFF99;font:tahoma; font-size:8.5px" value="<?=$entrega ?>" size="13" /></td>

        <td></td>

        <td><span class="Tablas">Factura:</span></td>

        <td><input name="factura" type="text" id="factura" style="background:#FFFF99;font:tahoma; font-size:8.5px" value="<?=$factura ?>" size="13" /></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td ><table width="654" border="0" align="center">

      <tr>

        <td width="7%" class="Tablas">T. Flete:</td>

        <td width="12%"><select name="lstflete" id="lstflete" style="width:70px; font-size:8.5px">

          <option>Pagado</option>

          <option>Por Cobrar</option>

        </select></td>

        <td width="3%"><input name="chocurre" type="checkbox" id="chocurre" style="width:8px; height:8px" value="SI" /></td>

        <td width="6%"><span class="Tablas">Ocurre</span></td>

        <td width="7%" class="Tablas">Destino:</td>

        <td width="16%"><select name="sltdestino" id="sltdestino" style="width:100px; font-size:8.5px">

          <option selected="selected">Seleccionar Destino</option>

                        </select></td>

        <td width="10%"><span class="Tablas">Suc. Destino:</span></td>

        <td width="13%"><input name="destino" type="text" id="destino" style="background:#FFFF99;font:tahoma; font-size:8.5px" value="<?=$destino ?>" size="20" /></td>

        <td width="9%"><span class="Tablas">Cond. Pago: </span></td>

        <td width="14%">&nbsp;<select name="sltpago" id="sltpago" style="width:70px; font-size:8.5px">

          <option>Contado</option>

          <option>Credito</option>

        </select></td>

        <td width="3%">&nbsp;</td>

      </tr>

      

    </table></td>

  </tr>

  <tr>

    <td><table width="654" border="0" align="center">

      <tr>

        <td width="655"><table width="620" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#016193">

            <tr>

              <td width="318" class="FondoTabla">Remitente</td>

              <td width="291" class="FondoTabla">Destinatario</td>

            </tr>

            <tr>

              <td><table width="97%" border="0">

                  <tr>

                    <td width="15%"><span class="Tablas"># Cliente: </span></td>

                    <td><input name="remitente2" type="text" id="remitente2" style="font:tahoma; font-size:8px" value="<?=$remitente ?>" size="4" />

                      &nbsp;&nbsp;<img src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" /></td>

                    <td colspan="3"><span class="Tablas">Cliente:</span>&nbsp;&nbsp;

                        <input name="rcliente2" type="text" id="rcliente2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rcliente ?>" size="25" /></td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">Calle:</span></td>

                    <td colspan="3"><input name="rcalle2" type="text" id="rcalle2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rcalle ?>" size="35" />

                        <span class="Tablas">Numero: </span><span class="Tablas">

                        <input name="rnumero2" type="text" id="rnumero2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rnumero ?>" size="5" />

                      </span></td>

                    <td width="2%">&nbsp;</td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">CP:</span></td>

                    <td width="31%"><input name="rcp5" type="text" id="rcp5" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rcp ?>" size="15" /></td>

                    <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;

                          <input name="rcolonia2" type="text" id="rcolonia2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rcolonia ?>" size="25" />

                    </span></td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">Poblaci&oacute;n:</span></td>

                    <td colspan="4"><input name="rpoblacion2" type="text" id="rpoblacion2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rpoblacion ?>" size="58" /></td>

                  </tr>

                  <tr>

                    <td><span class="Tablas">R.F.C.:</span></td>

                    <td colspan="4"><input name="rrfc2" type="text" id="rrfc2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="15" />

                        <span class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tel&eacute;fono:

                          <input name="rtelefono2" type="text" id="rtelefono2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rtelefono ?>" size="22" />

                      </span></td>

                  </tr>

              </table></td>

              <td><table width="94%" border="0" align="center">

                  <tr>

                    <td><input name="destinatario2" type="text" id="destinatario2" style="font:tahoma; font-size:8px" value="<?=$destinatario ?>" size="4" />

                      &nbsp;&nbsp;<img src="../img/Buscar_24.gif" alt="Buscar Nick" width="24" height="23" align="absbottom" /></td>

                    <td colspan="3"><span class="Tablas">Cliente:</span>&nbsp;&nbsp;

                        <input name="dcliente2" type="text" id="dcliente2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$dcliente ?>" size="28" /></td>

                  </tr>

                  <tr>

                    <td colspan="3"><input name="dcalle2" type="text" id="dcalle2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$dcalle ?>" size="35" />

                        <span class="Tablas">Numero: </span><span class="Tablas">

                        <input name="dnumero2" type="text" id="dnumero2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$dnumero ?>" size="5" />

                      </span></td>

                    <td width="4%">&nbsp;</td>

                  </tr>

                  <tr>

                    <td width="31%"><input name="dcp2" type="text" id="dcp2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$dcp ?>" size="15" /></td>

                    <td colspan="3"><span class="Tablas">Colonia:&nbsp;&nbsp;

                          <input name="dcolonia2" type="text" id="dcolonia2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$dcolonia ?>" size="28" />

                    </span></td>

                  </tr>

                  <tr>

                    <td colspan="4"><input name="dpoblacion2" type="text" id="dpoblacion2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$dpoblacion ?>" size="60" /></td>

                  </tr>

                  <tr>

                    <td colspan="4"><input name="drfc4" type="text" id="drfc4" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$drfc ?>" size="15" />

                        <span class="Tablas">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tel&eacute;fono:

                          <input name="dtelefono2" type="text" id="dtelefono2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$dtelefono ?>" size="24" />

                      </span></td>

                  </tr>

              </table></td>

            </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td><table width="654" border="0" align="center">

      <tr>

        <td width="410"><table width=410 border=0 cellspacing=0 cellpadding=0>

            <tr>

              <td width=5 height=16   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="d" ></td>

              <td width=31 background="../img/borde1_2.jpg" class=style5 align=center>Cant</td>

              <td width=79 background="../img/borde1_2.jpg" class=style5 align=center>Descripci&oacute;n</td>

              <td width=69 background="../img/borde1_2.jpg" class=style5 align=center>Contenido</td>

              <td width=30 background="../img/borde1_2.jpg" class=style5 align=center>Peso</td>

              <td width=24 background="../img/borde1_2.jpg" class=style5 align=center>Vol</td>

              <td width=51 background="../img/borde1_2.jpg" class=style5 align=center>Importe</td>

              <td width=15 background="../img/borde1_2.jpg" class=style5><img src="../img/space.gif" alt="d"></td>

              <td width=7  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="d" ></td>

            </tr>

            <tr>

              <td colspan=9 align=right><div id=detalle name=detalle style=" height:80px; overflow:auto" align=left>

                  <? $line = 0 ?>

                  <table width=405 border=0 cellspacing=0 cellpadding=0>

                    <? while($line<=200){ ?>

                    <tr class="<? if ($line % 2 ==0){ echo 'Balance2' ;}else{ echo 'Balance' ;} ?>"  <? if ($line==0){ echo "style='visibility:hidden;display:none'" ;} ?>  >

                      <td height=16 width=2 ></td>

                      <td width="42" class="style3" align="center"><input name="Cantidad_<?=$line ?>" type=text class=style2 id="Cantidad_<?=$line ?>" style="font-size:8px; font:tahoma" size=3 maxlength=4 onKeyPress="validarId(event,'_<?=$line;?>')"></td>

                      <td width="102" class="style3" align="center"><input name="Descripcion_<?=$line ?>" type=text class=style2 id="Descripcion_<?=$line ?>" style="font-size:8px; font:tahoma" size=15 maxlength=30 ></td>

                      <td width="100" class="style3" align="center"><input name="Contenido_<?=$line ?>" type=text class=style2 id="Contenido_<?=$line ?>" size=20 style="font-size:8px; font:tahoma" maxlength=40 ></td>

                      <td width="42" class="style3" align="center"><input name="Pesokg_<?=$line ?>" type=text class=style2 id="Pesokg_<?=$line ?>" size=3 style="font-size:8px; font:tahoma" maxlength=4 ></td>

                      <td width="40" class="style3" align="center"><input name="Pesovol_<?=$line ?>" type=text class=style2 id="Pesovol_<?=$line ?>" size=3 style="font-size:8px; font:tahoma" maxlength=4 ></td>

                      <td width="52" align="right" class="style3" ><input name="Importe_<?=$line ?>" type=text class=style2 id="Importe_<?=$line ?>" style="font-size:8px; font:tahoma" size=10 maxlength=12 ></td>

                      <td width="20" align="center"><input name="Excedente_<?=$line ?>" type="hidden" id="Excedente_<?=$line ?>"></td>

                    </tr>

                    <?  $line ++ ; } ?>

                  </table>

              </div></td>

            </tr>

        </table></td>

        <td width="220"><table width="190" height="90" border="0" align="left">

            <tr>

              <td width="194"><table width="185" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">

                  <tr>

                    <td width="172" class="FondoTabla">Tiempo de Entrega </td>

                  </tr>

                  <tr>

                    <td><table width="163" height="0" align="center" bordercolor="#016193">

                        <tr>

                          <td width="41" class="Tablas">Ocurre:</td>

                          <td width="40"><input name="drfc2" type="text" id="drfc2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$drfc ?>" size="5" /></td>

                          <td width="28" class="Tablas">EAD:</td>

                          <td width="34"><input name="drfc3" type="text" id="drfc3" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$drfc ?>" size="5" /></td>

                        </tr>

                    </table></td>

                  </tr>

              </table></td>

            </tr>

            <tr>

              <td><table width="185" height="0" border="0" align="right" cellpadding="0" cellspacing="0" bordercolor="#016193">

                  <tr>

                    <td width="200" class="FondoTabla">Restricciones</td>

                  </tr>

                  <tr>

                    <td><label>

                      <textarea name="textarea" style="width:180px"></textarea>

                    </label></td>

                  </tr>

              </table></td>

            </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td><table width="606" border="0" align="left">

      <tr>

        <td width="56" class="Tablas">T. Paquetes: </td>

        <td width="28" class="Tablas"><input name="rcp2" type="text" id="rcp2" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rcp ?>" size="5" /></td>

        <td width="56" class="Tablas">T. Peso Kg: </td>

        <td width="29" class="Tablas"><input name="rcp3" type="text" id="rcp3" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rcp ?>" size="5" /></td>

        <td width="60" class="Tablas">T. Volumen: </td>

        <td width="351" class="Tablas"><input name="rcp4" type="text" id="rcp4" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rcp ?>" size="5" /></td>

      </tr>

    </table></td>

  </tr>

  

  

  <tr>

    <td ><table width="654" border="0" align="center">

      <tr>

        <td width="328"><table width="325" height="140" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="434" class="FondoTabla">Servicios</td>

          </tr>

          <tr>

            <td><table width="100%" height="100" border="0">

                <tr>

                  <td width="6%"><input name="chocurre2" type="checkbox" id="chocurre2" style="width:8px; height:8px" value="SI" /></td>

                  <td class="Tablas">Emplaye

                    <input name="rrfc22212" type="text" id="rrfc22212" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  <td class="Tablas"><input name="chocurre22" type="checkbox" id="chocurre22" style="width:8px; height:8px" value="SI" /></td>

                  <td class="Tablas">Acuse Recibo</td>

                  <td class="Tablas"><input name="rrfc22216" type="text" id="rrfc22216" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                </tr>

                <tr>

                  <td><input name="chocurre3" type="checkbox" id="chocurre3" style="width:8px; height:8px" value="SI" /></td>

                  <td width="54%" class="Tablas">Bolsa Empaque

                    <input name="rrfc3" type="text" id="rrfc3" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="2" />

                      <input name="rrfc22213" type="text" id="rrfc22213" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  <td width="6%" class="Tablas"><input name="chocurre23" type="checkbox" id="chocurre23" style="width:8px; height:8px" value="SI" /></td>

                  <td width="20%" class="Tablas">COD</td>

                  <td width="14%" class="Tablas"><input name="rrfc22217" type="text" id="rrfc22217" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                </tr>

                <tr>

                  <td><input name="chocurre4" type="checkbox" id="chocurre4" style="width:8px; height:8px" value="SI" /></td>

                  <td colspan="4" class="Tablas">Aviso Celular

                    <input name="rrfc4" type="text" id="rrfc4" style="background:#FFFF99;font:tahoma; font-size:8px" size="12" />

                      <input name="rrfc22214" type="text" id="rrfc22214" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                </tr>

                <tr>

                  <td><input name="chocurre5" type="checkbox" id="chocurre5" style="width:8px; height:8px" value="SI" /></td>

                  <td class="Tablas">Valor Declarado

                    <input name="rrfc22215" type="text" id="rrfc22215" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  <td class="Tablas"><input name="chocurre24" type="checkbox" id="chocurre24" style="width:8px; height:8px" value="SI" /></td>

                  <td class="Tablas">Recolecci&oacute;n</td>

                  <td class="Tablas"><input name="rrfc22218" type="text" id="rrfc22218" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                </tr>

                <tr>

                  <td>&nbsp;</td>

                  <td colspan="4" class="Tablas">&nbsp;</td>

                </tr>

            </table></td>

          </tr>

        </table></td>

        <td width="14">&nbsp;</td>

        <td width="298"><table width="272" border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#74B051">

            <tr>

              <td width="257" bgcolor="#74B051"><span class="Estilo2">TOTALES</span></td>

            </tr>

            <tr>

              <td><table width="100%" border="0">

                  <tr>

                    <td class="Tablas">Flete:</td>

                    <td colspan="2" class="Tablas"><input name="rrfc222" type="text" id="rrfc222" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                    <td class="Tablas">Excedente:</td>

                    <td class="Tablas"><input name="rrfc2222" type="text" id="rrfc2222" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  </tr>

                  <tr>

                    <td class="Tablas">Descuento:</td>

                    <td colspan="2" class="Tablas"><input name="rrfc223" type="text" id="rrfc223" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="3" />

                        <input name="rrfc2242" type="text" id="rrfc2242" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="4" /></td>

                    <td class="Tablas">Combustible:</td>

                    <td class="Tablas"><input name="rrfc2223" type="text" id="rrfc2223" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  </tr>

                  <tr>

                    <td class="Tablas">EAD:</td>

                    <td colspan="2" class="Tablas"><input name="rrfc2228" type="text" id="rrfc2228" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                    <td class="Tablas">Subtotal:</td>

                    <td class="Tablas"><input name="rrfc2224" type="text" id="rrfc2224" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  </tr>

                  <tr>

                    <td class="Tablas">Recolecci&oacute;n:</td>

                    <td colspan="2" class="Tablas"><input name="rrfc2229" type="text" id="rrfc2229" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                    <td class="Tablas">IVA:</td>

                    <td class="Tablas"><input name="rrfc2225" type="text" id="rrfc2225" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  </tr>

                  <tr>

                    <td width="24%" class="Tablas">Seguro:</td>

                    <td colspan="2" class="Tablas"><input name="rrfc22210" type="text" id="rrfc22210" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                    <td width="29%" class="Tablas">IVA Retenido: </td>

                    <td width="17%" class="Tablas"><input name="rrfc2226" type="text" id="rrfc2226" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  </tr>

                  <tr>

                    <td class="Tablas">Otros:</td>

                    <td colspan="2" class="Tablas"><input name="rrfc22211" type="text" id="rrfc22211" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                    <td class="Tablas">Total:</td>

                    <td class="Tablas"><input name="rrfc2227" type="text" id="rrfc2227" style="background:#FFFF99;font:tahoma; font-size:8px" value="<?=$rrfc ?>" size="10" /></td>

                  </tr>

              </table></td>

            </tr>

          </table></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td><table width="654" border="0" align="center">

      <tr>

        <td width="400"><table width="400" border="0" cellpadding="0" cellspacing="0" bordercolor="#016193">

          <tr>

            <td width="384" class="FondoTabla">Observaciones</td>

          </tr>

          <tr>

            <td><textarea name="textarea2" style="width:400px; font-size:8px; font:tahoma"></textarea></td>

          </tr>

        </table></td>

        <td width="244"><table width="170" border="0" align="left">

          <tr>

            <td><label><img src="file://///pcerika/curso/webpmm/web/img/impguias.gif" width="212" height="24"></label></td>

          </tr>

          

        </table></td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td ></td>

  </tr>

</table>

</form>

</body>

</html>

