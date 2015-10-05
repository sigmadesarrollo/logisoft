<?	session_start();

	if(!$_SESSION[IDUSUARIO]!=""){

		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");

	}

	require_once('../Conectar.php');

	$link = Conectarse('webpmm');

	$folio = $_POST['folio']; $fecha = $_POST['fecha']; $sucursal = $_POST['sucursal'];

	$unidad = $_POST['unidad']; $accion = $_POST['accion'];

	$fecha = date('d/m/Y');

	if($accion = ""){

	$row = ObtenerFolio('asignacionead','webpmm');

	$folio = $row[0];

	}else if($accion == "grabar"){

		$sqlins = mysql_query("INSERT INTO asignacionead () VALUES ()",$link);

		$folio = mysql_insert_id();

		$mensaje ='Los datos han sido guardados correctamente';

		$accion = "modificar";

		

	}else if($accion == "modificar"){

		$sqlupd = mysql_query("UPDATE asignacionead SET WHERE folio=$folio",$link);

		$mensaje="Los cambios han sido guardados correctamente";

		

	}else if($accion == "limpiar"){

		$row = ObtenerFolio('asignacionead','webpmm');

		$folio = $row[0];

		

	}

	

	

	

	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script src="../javascript/ajax.js"></script>

<script src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<script>

	var u = document.all;

	function obtenerUnidadBusqueda(){

		

	}

	function obtenerUnidad(e,id){

		tecla = (u) ? e.keyCode : e.which;

		if(tecla = 13){

consulta("mostrarUnidad","consultasEntregas.php?accion=2&unidad="+id);

		}

	}

	function mostrarUnidad(datos){

	var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;		

						

		if(con>0){		

		u.unidad.value = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		}else{

		

		}

	}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title></title>

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

<body onLoad="document.all.unidad.focus()">

<form id="form1" name="form1" method="post" action="">

  <br>

<table width="621" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

  <tr>

    <td width="619" class="FondoTabla Estilo4">Datos Generales</td>

  </tr>

  <tr>

    <td><table width="620" border="0" cellpadding="0" cellspacing="0">

      <tr>

        <td colspan="2" align="right">&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

      </tr>

      <tr>

        <td colspan="2" align="right">Folio:</td>

        <td width="114"><span class="Tablas">

          <input name="folio" type="text" class="Tablas" id="folio" style="width:100px;background:#FFFF99" value="<?=$folio ?>" readonly=""/>

        </span></td>

        <td width="45">Fecha:</td>

        <td width="64"><span class="Tablas">

          <input name="fecha" type="text" class="Tablas" id="fecha" style="background:#FFFF99" value="<?=$fecha ?>" size="10" readonly=""/>

        </span></td>

        <td width="56">Sucursal:</td>

        <td width="129"><span class="Tablas">

          <input name="sucursal" type="text" class="Tablas" id="sucursal" style="width:100px;background:#FFFF99" value="<?=$sucursal ?>" readonly=""/>

        </span></td>

      </tr>

      <tr>

        <td width="52">Unidad:</td>

        <td width="158"><input name="unidad" class="Tablas" type="text" id="unidad" value="<?=$unidad ?>" />

          <span class="Tablas"><img src="../img/Buscar_24.gif" width="24" height="23" align="absbottom" onClick="abrirVentanaFija('../buscadores_generales/buscarUnidad.php', 550, 450, 'ventana', 'Busqueda')" style="cursor:pointer"></span></td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

      </tr>

      <tr>

        <td colspan="7">&nbsp;</td>

      </tr>

      <tr>

        <td colspan="7">&nbsp;</td>

      </tr>

      <tr>

        <td colspan="7"><table width="618" cellpadding="0" cellspacing="0">

          <tr>

            <td width="285"><table width="281" cellpadding="0" cellspacing="0">

              <tr>

                <td width="1" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                <td width="81" background="../img/borde1_2.jpg" class="style51" align="center">No. GUIA</td>

                <td width="66" background="../img/borde1_2.jpg" class="style51" align="center">ORIGEN</td>

                <td width="71" background="../img/borde1_2.jpg" class="style51" align="center">FECHA </td>

                <td width="96" background="../img/borde1_2.jpg" class="style51" align="center">CODIGO DE BARRA</td>

                <td width="8" background="../img/borde1_2.jpg" class="style51" align="center"></td>

                <td width="5"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

              </tr>

              <tr>

                <td colspan="7" align="right"><div id="detalle3" name="detalle" style=" height:150px; overflow:auto" align="left">

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

                      <td width="68" ><input name="colonia" type="text" class="Tablas" id="colonia" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                      <td width="61" class="style31" align="center" ><input name="poblacion" type="text" class="Tablas" id="poblacion" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                      <td width="52" class="style31" align="center" ><input name="municipio" type="text" class="Tablas" id="municipio" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="9" readonly="" /></td>

                      <td width="91" class="style31" align="center"><input name="estado" type="text" class="Tablas" id="estado" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="15" readonly="" /></td>

                    </tr>

                    <?			

		$line ++ ; }

	

			

	?>

                  </table>

                </div></td>

              </tr>

            </table></td>

            <td width="48" align="center"><div class="ebtn_adelante"></div><br><br><br><br><div class="ebtn_atraz"></div></td>

            <td width="283"><table width="281" cellpadding="0" cellspacing="0">

              <tr>

                <td width="1" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                <td width="81" background="../img/borde1_2.jpg" class="style51" align="center">No. GUIA</td>

                <td width="66" background="../img/borde1_2.jpg" class="style51" align="center">ORIGEN</td>

                <td width="71" background="../img/borde1_2.jpg" class="style51" align="center">FECHA </td>

                <td width="96" background="../img/borde1_2.jpg" class="style51" align="center">CODIGO DE BARRA</td>

                <td width="8" background="../img/borde1_2.jpg" class="style51" align="center"></td>

                <td width="5"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

              </tr>

              <tr>

                <td colspan="7" align="right"><div id="detalle" name="detalle" style=" height:150px; overflow:auto" align="left">

                  <table width="280" border="0" id="codigopostal2" alagregar="" alborrar="" cellspacing="0" cellpadding="0" >

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

                    <tr id="te_<?=$line?>2" class="<? if ($line % 2 ==0){ echo 'Balance' ;}else{ echo 'Balance2' ;} ?>">

                      <td height="16" width="17" ><input name="id" type="hidden" /></td>

                      <td width="68" ><input name="colonia2" type="text" class="Tablas" id="colonia2" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                      <td width="61" class="style31" align="center" ><input name="poblacion2" type="text" class="Tablas" id="poblacion2" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                      <td width="52" class="style31" align="center" ><input name="municipio2" type="text" class="Tablas" id="municipio2" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="9" readonly="" /></td>

                      <td width="91" class="style31" align="center"><input name="estado2" type="text" class="Tablas" id="estado2" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="15" readonly="" /></td>

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

        <td colspan="7">&nbsp;</td>

        </tr>

      <tr>

        <td colspan="7"><table width="618" cellpadding="0" cellspacing="0">

          <tr>

            <td width="285"><table width="281" align="center" cellpadding="0" cellspacing="0">

              <tr>

                <td width="1" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                <td width="81" background="../img/borde1_2.jpg" class="style51" align="center">REGISTRO</td>

                <td width="66" background="../img/borde1_2.jpg" class="style51" align="center">PAQUETE</td>

                <td width="88" background="../img/borde1_2.jpg" class="style51" align="center">CODIGO DE BARRA</td>

                <td width="79" background="../img/borde1_2.jpg" class="style51" align="center">ESTADO</td>

                <td width="8" background="../img/borde1_2.jpg" class="style51" align="center"></td>

                <td width="5"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

              </tr>

              <tr>

                <td colspan="7" align="right"><div id="detalle2" name="detalle" style=" height:50px; overflow:auto" align="left">

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

                      <td width="58" ><input name="colonia4" type="text" class="Tablas" id="colonia3" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="12" readonly="" /></td>

                      <td width="64" class="style31" align="center" ><input name="poblacion4" type="text" class="Tablas" id="poblacion3" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                      <td width="89" class="style31" align="center" ><input name="estado4" type="text" class="Tablas" id="estado3" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="15" readonly="" /></td>

                      <td width="80" class="style31" align="center"><input name="municipio4" type="text" class="Tablas" id="municipio3" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="10" readonly="" /></td>

                    </tr>

                    <?			

		$line ++ ; }

	

			

	?>

                  </table>

                </div></td>

              </tr>

            </table></td>

            <td width="48">&nbsp;</td>

            <td width="283"><table width="281" align="center" cellpadding="0" cellspacing="0">

              <tr>

                <td width="1" height="16"   background="../img/borde1_1.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

                <td width="81" background="../img/borde1_2.jpg" class="style51" align="center">REGISTRO</td>

                <td width="66" background="../img/borde1_2.jpg" class="style51" align="center">PAQUETE</td>

                <td width="88" background="../img/borde1_2.jpg" class="style51" align="center">CODIGO DE BARRA</td>

                <td width="79" background="../img/borde1_2.jpg" class="style51" align="center">ESTADO</td>

                <td width="8" background="../img/borde1_2.jpg" class="style51" align="center"></td>

                <td width="5"  background="../img/borde1_3.jpg"><img src="../img/space.gif" alt="Space" width="1" height="1" /></td>

              </tr>

              <tr>

                <td colspan="7" align="right"><div id="detalle4" name="detalle" style=" height:50px; overflow:auto" align="left">

                  <table width="280" border="0" id="codigopostal4" alagregar="" alborrar="" cellspacing="0" cellpadding="0" >

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

                    <tr id="te_<?=$line?>4" class="<? if ($line % 2 ==0){ echo 'Balance' ;}else{ echo 'Balance2' ;} ?>">

                      <td height="16" width="25" ><input name="id2" type="hidden" /></td>

                      <td width="58" ><input name="colonia3" type="text" class="Tablas" id="colonia4" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="12" readonly="" /></td>

                      <td width="64" class="style31" align="center" ><input name="poblacion3" type="text" class="Tablas" id="poblacion4" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="13" readonly="" /></td>

                      <td width="89" class="style31" align="center" ><input name="estado3" type="text" class="Tablas" id="estado4" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="15" readonly="" /></td>

                      <td width="80" class="style31" align="center"><input name="municipio3" type="text" class="Tablas" id="municipio4" style="font-size:8px; font:tahoma;font-weight:bold; background:none; border:none" size="10" readonly="" /></td>

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

        <td colspan="7">&nbsp;</td>

      </tr>

      <tr>

        <td colspan="7"><table width="315" align="center" cellpadding="0" cellspacing="0">

          <tr>

            <td width="55"><label>

              <input name="radiobutton" type="radio" value="radiobutton" />

            </label>

Gu&iacute;a

</select>

</select>

</select></td>

            <td width="112"><span class="Tablas">

              <input name="guia" type="text" class="Tablas" id="guia" style="width:100px" value="<?=$guia ?>" />

            </span></td>

            <td width="132"><select name="select" class="Tablas" style="width:100px" />

            </td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td colspan="7">&nbsp;</td>

      </tr>

      <tr>

        <td colspan="7" align="center"></td>

      </tr>

      <tr>

        <td colspan="7">&nbsp;</td>

        </tr>

    </table></td>

  </tr>

</table>

</form>

</body>

<script>

	parent.frames[1].document.getElementById('titulo').innerHTML = 'ASIGNACIÓN MANUAL EAD';

</script>

</html>

