<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Untitled Document</title>

<style type="text/css">

<!--

body {

	margin-left: 1px;

	margin-top: 1px;

	margin-right: 1px;

	margin-bottom: 1px;

}

-->

</style>

<link href="../../menu/estilosPrincipal.css" rel="stylesheet" type="text/css" />

<link type="text/css" rel="stylesheet" href="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>

<script src="../../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

</head>



<body>

<center>

   	<table width="686" border="0" cellpadding="0" cellspacing="0">

<tr>

        	<td class="tituloCentral">Indicador de Sucursal</td>

</tr>

		<tr>

          <td height="5px" align="center">

          	<table width="100%" cellpadding="0" cellspacing="0" class="fondoCuadro5">

				<tr>

           	  <td height="19" align="center"><strong>Filtros de Consulta</strong></td>

</tr>

                <tr>

                	<td align="left" valign="top">

                    <table width="681" cellpadding="0" cellspacing="0" border="0">

       	  <tr>

               	  <td width="28"><strong>&nbsp;&nbsp;</strong></td>

                        	<td width="47"><strong>Desde</strong></td>

                        	<td width="126">

                            <input name="desde" type="text" class="Tablas" id="desde" onkeypress="return solonumeros(event)" 

                            onkeydown="return tabular(event,this)" onkeyup="mascara(this,'/',patron,true)"  size="15"  /></td>

                        	<td width="42"><img src="../../img/calendario.gif" alt="Desde" width="20" height="20" align="absbottom" style="cursor:pointer" 

                            title="Calendario" onclick="displayCalendar(desde,'dd/mm/yyyy',this);" /></td>

                            <td width="46" ><strong>Hasta</strong></td>

                            <td width="124" >

                            <input name="hasta" type="text" class="Tablas" id="hasta" onkeypress="return solonumeros(event)" 

                            onkeydown="return tabular(event,this)" onkeyup="mascara(this,'/',patron,true)"  size="15"  /></td>

                            <td width="93" ><img src="../../img/calendario.gif" alt="Hasta" width="20" height="20" align="absbottom" style="cursor:pointer" 

                            title="Calendario" onclick="displayCalendar(hasta,'dd/mm/yyyy',this);" /></td>

                            <td width="175" >

                           	  <img src="../../img/Buscar_24.gif" alt="Buscar" style="cursor:pointer"/>

                            </td>

                        </tr>

</table>                    </td>

</tr>

            </table>          </td>

        </tr>

        <tr>

          <td height="5px"></td>

		</tr>

        <tr>

          <td height="5px" align="center">

          	<table width="100%" cellpadding="0" cellspacing="0" class="fondoCuadro1">

				<tr>

           	  <td width="83%" height="19" align="center"><strong>VENTAS</strong></td>

<td width="7%" align="center" class="fondoCuadrosTextoA">HOY</td>

            <td width="5%" align="center" class="fondoCuadrosTextoA">MES</td>

            <td width="5%" align="center" class="fondoCuadrosTextoA">AÑO</td>

            </tr>

                <tr>

                	<td valign="top" align="left">

                    <table width="464" cellpadding="0" cellspacing="0" border="0">

       	  <tr>

                        	<td width="355"><strong>&nbsp;&nbsp;</strong></td>

                            <td width="123" align="right"><strong>MONTO</strong></td>

                            <td width="124" align="right"><strong>META</strong></td>

                        </tr>

          <tr>

       	    <td align="left" height="5px"></td>

       	    <td align="right"></td>

       	    <td align="right"></td>

     	    </tr>

       	  <tr>

       	    <td align="left">&nbsp;&nbsp;VENTANILLA</td>

       	    <td align="right">$ 0.00</td>

       	    <td align="right">0.0 %</td>

     	    </tr>

       	  <tr>

       	    <td align="left">&nbsp;&nbsp;EMPRESARIALES</td>

       	    <td align="right">$ 0.00</td>

       	    <td align="right">0.0 %</td>

     	    </tr>

       	  <tr>

       	    <td align="left">&nbsp;&nbsp;TOTALES</td>

       	    <td align="right">$ 0.00</td>

       	    <td align="right">0.0 %</td>

     	    </tr>

                    </table>                    </td>

<td colspan="3"></td>

</tr>

            </table>          </td>

        </tr>

        <tr>

          <td height="5px"></td>

        </tr>

        <tr>

          <td height="5px"><table width="100%" cellpadding="0" cellspacing="0" class="fondoCuadro2">

            <tr>

              <td width="83%" height="19" align="center"><strong>CREDITO Y COBRANZA</strong></td>

              <td width="7%" align="center" class="fondoCuadrosTextoA">HOY</td>

              <td width="5%" align="center" class="fondoCuadrosTextoA">MES</td>

              <td width="5%" align="center" class="fondoCuadrosTextoA">AÑO</td>

            </tr>

            <tr>

              <td valign="top" align="left"><table width="464" cellpadding="0" cellspacing="0" border="0">

                  <tr>

                    <td width="355"><strong>&nbsp;&nbsp;</strong></td>

                    <td width="123" align="right"><strong>MONTO</strong></td>

                    <td width="124" align="right"><strong>META</strong></td>

                  </tr>

                  <tr>

                    <td align="left" height="5px"></td>

                    <td align="right"></td>

                    <td align="right"></td>

                  </tr>

                  <tr>

                    <td align="left">&nbsp;&nbsp;CARGOS</td>

                    <td align="right">$ 0.00</td>

                    <td align="right">0.0 %</td>

                  </tr>

                  <tr>

                    <td align="left">&nbsp;&nbsp;ABONOS</td>

                    <td align="right">$ 0.00</td>

                    <td align="right">0.0 %</td>

                  </tr>

                  <tr>

                    <td align="left" height="9px"></td>

                    <td align="right"></td>

                    <td align="right"></td>

                  </tr>

                  <tr>

                    <td align="right"><strong>SALDO</strong></td>

                    <td align="right">$ 0.00</td>

                    <td align="right">&nbsp;</td>

                  </tr>

              </table></td>

              <td colspan="3"></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td height="5px"></td>

        </tr>

        <tr>

          <td height="5px"><table width="100%" cellpadding="0" cellspacing="0" class="fondoCuadro3">

            <tr>

              <td width="83%" height="19" align="center"><strong>CAJA</strong></td>

              <td width="7%" align="center" class="fondoCuadrosTextoA">HOY</td>

              <td width="5%" align="center" class="fondoCuadrosTextoA">MES</td>

              <td width="5%" align="center" class="fondoCuadrosTextoA">AÑO</td>

            </tr>

            <tr>

              <td valign="top" align="left"><table width="493" cellpadding="0" cellspacing="0" border="0">

                  <tr>

                    <td width="126"><strong>&nbsp;&nbsp;</strong>INGRESOS</td>

                    <td width="153" align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                    <td width="101" align="LEFT"><strong>&nbsp;&nbsp;</strong>DEPOSITOS</td>

                    <td width="113" align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                  </tr>

                  <tr>

                    <td>&nbsp;</td>

                    <td>SALDOS POR CONCILIAR</td>

                    <td align="right"><strong>$ 0.00</strong>&nbsp;&nbsp;</td>

                    <td align="right">&nbsp;</td>

                  </tr>

                  <tr>

                    <td>LIQUIDACIONES EAD</td>

                    <td align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                    <td align="right"><strong>$ 0.00</strong>&nbsp;&nbsp;</td>

                    <td align="right">&nbsp;</td>

                  </tr>

                  <tr>

                    <td>&nbsp;</td>

                    <td>SALDO CAJA CHICA</td>

                    <td align="right"><strong>$ 0.00</strong>&nbsp;&nbsp;</td>

                    <td align="right">&nbsp;</td>

                  </tr>

              </table></td>

              <td colspan="3"></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td height="5px"></td>

        </tr>

        <tr>

          <td height="5px"><table width="100%" cellpadding="0" cellspacing="0" class="fondoCuadro4">

            <tr>

              <td width="83%" height="19" align="center"><strong>OPERACIONES Y SERVICIOS</strong></td>

              <td width="7%" align="center" class="fondoCuadrosTextoA">HOY</td>

              <td width="5%" align="center" class="fondoCuadrosTextoA">MES</td>

              <td width="5%" align="center" class="fondoCuadrosTextoA">AÑO</td>

            </tr>

            <tr>

              <td height="120" align="left" valign="top"><table width="464" cellpadding="0" cellspacing="0" border="0">

                  <tr>

                    <td width="95"><strong>&nbsp;&nbsp;</strong>ENTREGAS</td>

                    <td width="129" align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                    <td width="110" align="left">RECOLECCIONES</td>

                    <td width="130" align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                  </tr>

                  <tr>

                    <td>&nbsp;&nbsp;RECEPCIONES</td>

                    <td align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                    <td align="left">TRASBORDOS</td>

                    <td align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                  </tr>

                  <tr>

                    <td>&nbsp;&nbsp;EMBARQUES</td>

                    <td align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                    <td align="right">&nbsp;</td>

                    <td align="right">&nbsp;</td>

                  </tr>

                  <tr>

                    <td height="5px"></td>

                    <td align="right"></td>

                    <td align="right"></td>

                    <td align="right"></td>

                  </tr>

                  <tr>

                    <td>&nbsp;</td>

                    <td align="left">GUIAS EN ALMACEN</td>

                    <td align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                    <td align="right"><strong>$ 0.00</strong>&nbsp;&nbsp;</td>

                  </tr>

                  <tr>

                    <td height="5px"></td>

                    <td align="left"></td>

                    <td align="right"></td>

                    <td align="right"></td>

                  </tr>

                  <tr>

                    <td height="5px"></td>

                    <td colspan="2" align="left">&nbsp;&nbsp;UNIDADES DISPONBLES</td>

<td align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                  </tr>

                  <tr>

                    <td height="5px"></td>

                    <td colspan="2" align="left">&nbsp;&nbsp;UNIDADES FUERA DE OPERACION</td>

                    <td align="right"><strong>0</strong>&nbsp;&nbsp;</td>

                  </tr>

                  <tr>

                    <td height="5px"></td>

                    <td colspan="2" align="left">&nbsp;&nbsp;GASTOS POR UNIDAD</td>

                    <td align="right"><strong>$ 0.00</strong>&nbsp;&nbsp;</td>

                  </tr>

              </table></td>

              <td colspan="3"></td>

            </tr>

          </table></td>

        </tr>

        <tr>

          <td height="5px"></td>

        </tr>

        <tr>

          <td height="5px"></td>

        </tr>

    </table>

</center>

</body>

</html>

