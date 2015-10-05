<?	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once('../Conectar.php');

	$link = Conectarse('webpmm');

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Documento sin t&iacute;tulo</title>

<style type="text/css">

<!--

.Estilo1 {font-size: 14px}

.Estilo2 {

	font-size: 14px;

	font-weight: bold;

	color: #FFFFFF;

}

-->

</style>



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

.Balance {background-color: #FFFFFF; border: 0px none}

.Balance2 {background-color: #DEECFA; border: 0px none;}

-->

</style>



<style type="text/css">

<!--

.Estilo4 {font-size: 12px}

-->

</style>

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">

<link href="../FondoTabla.css" rel="stylesheet" type="text/css">

<style type="text/css">

<!--

.style31 {font-size: 9px;

	color: #464442;

}

.style31 {font-size: 9px;

	color: #464442;

}

.style51 {color: #FFFFFF;

	font-size:8px;

	font-weight: bold;

}

-->

</style>

<link href="Tablas.css" rel="stylesheet" type="text/css">

</head>

<body>

<form id="form1" name="form1" method="post" action="">

  <br>

<table width="622" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="618" class="FondoTabla Estilo4">Datos Generales</td>

  </tr>

  <tr>

    <td><table width="607" border="0" cellpadding="0" cellspacing="0">

      <tr>

        <td colspan="5" align="right">&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

      </tr>

      <tr>

        <td width="20" align="right">&nbsp;</td>

        <td width="34" align="right">&nbsp;</td>

        <td width="95" align="right">&nbsp;</td>

        <td width="24" align="right">&nbsp;</td>

        <td width="55" align="right">Folio</td>

        <td width="109"><span class="Tablas">

          <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>

        </span></td>

        <td width="43">Fecha:</td>

        <td width="61"><span class="Tablas">

          <input name="fecha2" type="text" class="Tablas" id="fecha2" style="background:#FFFF99" value="<?=$fecha ?>" size="10" readonly=""/>

        </span></td>

        <td width="54">Sucursal:</td>

        <td width="112"><span class="Tablas">

          <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>

        </span></td>

      </tr>

      <tr>

        <td colspan="10"><table width="618" border="0" cellpadding="0" cellspacing="0">

          <tr>

            <td width="21"><label>

              <input name="radiobutton" type="radio" value="radiobutton">

            </label></td>

            <td width="43">Unidad</td>

            <td width="96"><input name="unidad" class="Tablas" type="text" id="unidad" value="<?=$unidad ?>" /></td>

            <td width="28"><div class="ebtn_buscar"></div></td>

            <td width="114">Destino de la Unidad<br></td>

            <td width="112"><select name="select2" class="Tablas" style="width:100px" />            </td>

            <td width="20">PP<br></td>

            <td width="184"><select name="select3" class="Tablas" style="width:100px" />            </td>

          </tr>

        </table></td>

        </tr>

      <tr>

        <td colspan="10"><table width="618" border="0" cellpadding="0" cellspacing="0">

          <tr>

            <td width="20"><label>

              <input name="radiobutton" type="radio" value="radiobutton">

            </label></td>

            <td width="42">Sucursal</td>

            <td width="556"><span class="Tablas">

              <input name="sucursal2" type="text" class="Tablas" id="sucursal2" style="width:100px;background:#FFFF99" value="<?=$sucursal2 ?>" readonly=""/>

            </span></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td colspan="10"><table width="618" border="0" cellpadding="0" cellspacing="0">

          <tr>

            <td width="62"><div align="right">Almac&eacute;n<br>

            </div></td>

            <td width="556"><span class="Tablas">

              <input name="almacen" type="text" class="Tablas" id="almacen" style="width:100px;background:#FFFF99" value="<?=$almacen ?>" readonly=""/>

            </span></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td colspan="10">&nbsp;</td>

      </tr>

      <tr>

        <td colspan="10"><table width="618" cellpadding="0" cellspacing="0">

          <tr>

            <td width="285"><table width="281" align="center" cellpadding="0" cellspacing="0">

              <tr>

                <td width="9" height="16" class="formato_columnas_izq"></td>

                <td width="81" class="formato_columnas"align="center">Sector</td>

                <td width="66" class="formato_columnas"align="center">No. Gu&iacute;a</td>

                <td width="71" class="formato_columnas"align="center">Origen </td>

                <td width="96" class="formato_columnas"align="center">C&oacute;digo de Barras</td>

                <td width="9" class="formato_columnas_der"></td>

              </tr>

              <tr>

                <td colspan="7" align="center"><div id="detalle3" name="detalle" style=" height:150px; overflow:auto" align="left">

                    <table width="280" border="0" id="codigopostal" alagregar="" alborrar="" cellspacing="0" cellpadding="0" >

                      <tr>

                        <td width="17"  ></td>

                        <td width="68" ></td>

                        <td width="61" ></td>

                        <td width="52" ></td>

                        <td width="91" ></td>

                      </tr>

                      <?

					$line = 0;

					while($line<=10){ 

					?>

                      <tr id="te_<?=$line?>" class="<? if ($line % 2 ==0){ echo 'Balance' ;}else{ echo 'Balance2' ;} ?>">

                        <td height="16" width="17" ><input name="id4" type="hidden" /></td>

                        <td width="68" ><input name="sector" type="text" class="Tablas" id="sector" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                        <td width="61" class="style31" align="center" ><input name="noguia" type="text" class="Tablas" id="noguia" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                        <td width="52" class="style31" align="center" ><input name="origen" type="text" class="Tablas" id="origen" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="9" readonly="" /></td>

                        <td width="91" class="style31" align="center"><input name="codigodebarra" type="text" class="Tablas" id="codigodebarra" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="15" readonly="" /></td>

                      </tr>

                      <?			

		$line ++ ; }

	

			

	?>

                    </table>

                </div></td>

              </tr>

            </table></td>

            <td width="48" align="center"><div class="ebtn_adelante"></div><br><br><br><br><div class="ebtn_atraz"></div></td>

            <td width="283"><table width="281" align="center" cellpadding="0" cellspacing="0">

              <tr>

                <td width="9" height="16" class="formato_columnas_izq"></td>

                <td width="81" class="formato_columnas"align="center">Sector</td>

                <td width="66" class="formato_columnas"align="center">Referencia</td>

                <td width="71" class="formato_columnas"align="center">Origen </td>

                <td width="96" class="formato_columnas"align="center">C&oacute;digo de Barras</td>

                <td width="9" class="formato_columnas_der"></td>

              </tr>

              <tr>

                <td colspan="7" align="center"><div id="div" name="detalle" style=" height:150px; overflow:auto" align="left">

                    <table width="280" border="0" id="codigopostal" alagregar="" alborrar="" cellspacing="0" cellpadding="0" >

                      <tr>

                        <td width="17"  ></td>

                        <td width="68" ></td>

                        <td width="61" ></td>

                        <td width="52" ></td>

                        <td width="91" ></td>

                      </tr>

                      <?

					$line = 0;

					while($line<=10){ 

					?>

                      <tr id="te_<?=$line?>" class="<? if ($line % 2 ==0){ echo 'Balance' ;}else{ echo 'Balance2' ;} ?>">

                        <td height="16" width="17" ><input name="id42" type="hidden" /></td>

                        <td width="68" ><input name="sector2" type="text" class="Tablas" id="sector2" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                        <td width="61" class="style31" align="center" ><input name="referencia" type="text" class="Tablas" id="referencia" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                        <td width="52" class="style31" align="center" ><input name="origen2" type="text" class="Tablas" id="origen2" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="9" readonly="" /></td>

                        <td width="91" class="style31" align="center"><input name="codigodebarra2" type="text" class="Tablas" id="codigodebarra2" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="15" readonly="" /></td>

                      </tr>

                      <?			

		$line ++ ; }

	

			

	?>

                    </table>

                </div></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

        </tr>

      <tr>

        <td colspan="10">&nbsp;</td>

        </tr>

      <tr>

        <td colspan="10"><table width="618" align="right" cellpadding="0" cellspacing="0">

          <tr>

            <td width="281" align="left"><table width="281" align="left" cellpadding="0" cellspacing="0">

              <tr>

                <td width="8" height="16" class="formato_columnas_izq"></td>

                <td width="68" class="formato_columnas"align="center">Registro</td>

                <td width="55" class="formato_columnas"align="center">Paquete</td>

                <td width="80" class="formato_columnas"align="center">C&oacute;digo de Barras</td>

                <td width="59" class="formato_columnas"align="center">Estado</td>

                <td width="9" class="formato_columnas_der"></td>

              </tr>

              <tr>

                <td colspan="7" align="right"><div id="detalle2" name="detalle" style="height:60px; overflow:auto" align="left">

                  <table width="280" border="0" id="codigopostal3" alagregar="" alborrar="" cellspacing="0" cellpadding="0" >

                      <tr>

                        <td width="25"  ></td>

                        <td width="58" ></td>

                        <td width="64" ></td>

                        <td width="89" ></td>

                        <td width="80" ></td>

                      </tr>

                      <?

					$line = 0;

					while($line<=10){ 

					?>

                      <tr id="te_<?=$line?>3" class="<? if ($line % 2 ==0){ echo 'Balance' ;}else{ echo 'Balance2' ;} ?>">

                        <td height="16" width="25" ><input name="id3" type="hidden" /></td>

                        <td width="58" ><input name="registro" type="text" class="Tablas" id="colonia3" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="12" readonly="" /></td>

                        <td width="64" class="style31" align="center" ><input name="paquete" type="text" class="Tablas" id="poblacion3" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                        <td width="89" class="style31" align="center" ><input name="codigodebarra22" type="text" class="Tablas" id="codigodebarra22" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="15" readonly="" /></td>

                        <td width="80" class="style31" align="center"><input name="estado" type="text" class="Tablas" id="municipio3" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="10" readonly="" /></td>

                      </tr>

                      <?			

		$line ++ ; }

	

			

	?>

                    </table>

                </div></td>

              </tr>

            </table></td>

            <td width="54"></td>

            <td width="281"><table width="281" align="right" cellpadding="0" cellspacing="0">

              <tr>

                <td width="8" height="16" class="formato_columnas_izq"></td>

                <td width="68" class="formato_columnas"align="center">Registro</td>

                <td width="55" class="formato_columnas"align="center">Paquete</td>

                <td width="80" class="formato_columnas"align="center">C&oacute;digo de Barras</td>

                <td width="59" class="formato_columnas"align="center">Estado</td>

                <td width="9" class="formato_columnas_der"></td>

              </tr>

              <tr>

                <td colspan="7" align="right"><div id="div2" name="detalle" style="height:50px; overflow:auto" align="right">

                    <table width="280" border="0" id="codigopostal3" alagregar="" alborrar="" cellspacing="0" cellpadding="0" >

                      <tr>

                        <td width="25"  ></td>

                        <td width="58" ></td>

                        <td width="64" ></td>

                        <td width="89" ></td>

                        <td width="80" ></td>

                      </tr>

                      <?

					$line = 0;

					while($line<=10){ 

					?>

                      <tr id="te_<?=$line?>3" class="<? if ($line % 2 ==0){ echo 'Balance' ;}else{ echo 'Balance2' ;} ?>">

                        <td height="16" width="25" ><input name="id32" type="hidden" /></td>

                        <td width="58" ><input name="registro2" type="text" class="Tablas" id="registro" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="12" readonly="" /></td>

                        <td width="64" class="style31" align="center" ><input name="paquete2" type="text" class="Tablas" id="paquete" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                        <td width="89" class="style31" align="center" ><input name="codigodebarra222" type="text" class="Tablas" id="codigodebarra222" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="15" readonly="" /></td>

                        <td width="80" class="style31" align="center"><input name="estado2" type="text" class="Tablas" id="estado" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="10" readonly="" /></td>

                      </tr>

                      <?			

		$line ++ ; }

	

			

	?>

                    </table>

                </div></td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td colspan="10">&nbsp;</td>

      </tr>

      

      <tr>

        <td colspan="10"><table width="606" border="0" cellpadding="0" cellspacing="0">

          <tr>

            <td width="294"><table width="278" align="center" cellpadding="0" cellspacing="0">

              <tr>

                <td width="20"><label>

                  <input name="radiobutton" type="radio" value="radiobutton" />

                  </label></td>

                <td width="92">C&oacute;digo de Barras</td>

                <td width="84"><span class="Tablas">

                  <input name="guia2" type="text" class="Tablas" id="guia2" style="width:80px" value="<?=$guia ?>" />

                </span></td>

                <td width="80"><select name="select4" class="Tablas" style="width:80px" />                </td>

              </tr>

            </table></td>

            <td width="312"><table width="312" align="center" cellpadding="0" cellspacing="0">

              <tr>

                <td width="104" align="right"><label>

                  <input name="radiobutton" type="radio" value="radiobutton" />

                  </label>

                  </select>

                  </select>

                  </select></td>

                <td width="22">Gu&iacute;a</td>

                <td width="80"><span class="Tablas">

                  <input name="guia3" type="text" class="Tablas" id="guia3" style="width:80px" value="<?=$guia ?>" />

                </span></td>

                <td width="104"><select name="select5" class="Tablas" style="width:80px" />                </td>

              </tr>

            </table></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td colspan="10">&nbsp;</td>

      </tr>

      

    </table></td>

  </tr>

</table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'TRANSBORDO DE MERCANCÍA';

</script>

</html>

