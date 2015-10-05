<?	session_start();

	require_once("../Conectar.php");

	$link = Conectarse('webpmm');

	

	$idsucursal = $_SESSION[IDSUCURSAL]; $usuario = $_SESSION[NOMBREUSUARIO]; $idusuario = $_SESSION[IDUSUARIO]; $efectivo = $_POST['efectivo']; $transferencia = $_POST['transferencia']; $retiros = $_POST['retiros']; $gcanceladas = $_POST['gcanceladas']; $tarjeta = $_POST['tarjeta']; $cheque = $_POST['cheque']; $gcredito = $_POST['gcredito']; $facturas = $_POST['facturas']; $tefectivo = $_POST['tefectivo']; $ttransferencia = $_POST['ttransferencia']; $tretiros = $_POST['tretiros']; $tguiacancelada = $_POST['tguiacancelada']; $ttarjeta = $_POST['ttarjeta']; $tcheque = $_POST['tcheque']; $tguiacredito = $_POST['tguiacredito']; $tfacturas = $_POST['tfacturas']; $accion = $_POST['accion']; $iniciocaja = $_POST['iniciocaja']; $codigo = $_POST['codigo']; $fecha = date("d/m/Y");

		

	$f = cambiaf_a_mysql($fecha);

	$s = mysql_query("SELECT IFNULL(SUM(efectivo),0) AS efectivo, IFNULL(SUM(tarjeta),0) AS tarjeta, IFNULL(SUM(trasferencia),0) AS transferencia, IFNULL(SUM(cheque),0) AS cheque, 0 AS retiros FROM guiasventanilla WHERE fecha='".$f."' AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."'",$link);

	$row = mysql_fetch_array($s);

	$tefectivo = $row[0]; $ttarjeta = $row[1]; $ttransferencia = $row[2]; $tcheque = $row[3]; $tretiros = $row[4];

	

	$can = mysql_query("SELECT COUNT(*) AS canceladas FROM guiasventanilla WHERE fecha='".$f."' AND estado='CANCELADO' AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."'",$link);

	$rcan = mysql_fetch_array($can); $tguiacancelada = $rcan[0];

	$cre = mysql_query("SELECT COUNT(*) AS credito FROM guiasventanilla WHERE fecha='".$f."' AND condicionpago='1' AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursalorigen='".$_SESSION[IDSUCURSAL]."'",$link);

	$rcre = mysql_fetch_array($cre);

	$fac= mysql_query("SELECT COUNT(*) AS facturaestado FROM facturacion WHERE fecha='".$f."' AND facturaestado='CANCELADO' AND idusuario='".$_SESSION[IDUSUARIO]."' AND idsucursal='".$_SESSION[IDSUCURSAL]."'",$link);	

	$rfac = mysql_fetch_array($fac);

$tguiacancelada = $rcan[0]; $tguiacredito = $rcre[0]; $tfacturas = $rfac[0];



	$sqlini = mysql_query("SELECT id FROM iniciocaja WHERE usuariocaja='".$_SESSION[IDUSUARIO]."' AND fechainiciocaja='".$f."'",$link);

	$rini = mysql_fetch_array($sqlini); if(mysql_num_rows($sqlini)>0){$iniciocaja=$rini[0];}

	

	if($accion == ""){

		$efectivo = 0; $transferencia = 0; $retiros = 0; $gcanceladas = 0;

		$tarjeta = 0; $cheque = 0; $gcredito = 0; $facturas = 0;

	}else if($accion == "parcial"){

		$f = cambiaf_a_mysql($fecha);

		$sql = mysql_query("INSERT INTO cierrecaja (iniciocaja, fechacierre, usuariocaja, efectivo, tarjeta, transferencia, cheque, retiros, guiacredito, guiacancelada, facturacancelada, tipocierre, usuario, fecha) VALUES ('$iniciocaja', '$f', '$idusuario', '$efectivo', '$tarjeta', '$transferencia', '$cheque', '$retiros', '$gcredito', '$gcanceladas', '$facturas', '$accion', '$usuario', current_timestamp())",$link);		

		$codigo = mysql_insert_id();

		$mensaje = "Se ha realizado el cierre Parcial correctamente";

		$fecha = date("d/m/Y");

	}else if($accion == "definitivo"){

		$f = cambiaf_a_mysql($fecha);

	$s = mysql_query("SELECT id, tipocierre FROM cierrecaja

	WHERE usuariocaja='".$_SESSION[IDUSUARIO]."' AND fechacierre='".$f."'",$link);

	$row = mysql_fetch_array($s);

	if($row[1]=="parcial"){

		$sqlupd = mysql_query("UPDATE cierrecaja SET iniciocaja='$iniciocaja', usuariocaja='$idusuario', fechacierre='$f', efectivo='$efectivo', tarjeta='$tarjeta', transferencia='$transferencia', cheque='$cheque', retiros='$retiros', guiacredito='$gcredito', guiacancelada='$gcanceladas', facturacancelada='$facturas', tipocierre='$accion', usuario='$usuario', fecha=current_timestamp() WHERE id='".$row[0]."'",$link);

		$mensaje = "Se ha realizado el cierre Definitivo correctamente";		

	}else{

		$sqlins = mysql_query("INSERT INTO cierrecaja (iniciocaja, usuariocaja, fechacierre, efectivo, tarjeta, transferencia, cheque, retiros, guiacredito, guiacancelada, facturacancelada, tipocierre, usuario, fecha) VALUES ('$iniciocaja', '$idusuario', '$f', '$efectivo', '$tarjeta', '$transferencia', '$cheque', '$retiros', '$gcredito', '$gcanceladas', '$facturas', '$accion', '$usuario', current_timestamp())",$link);

		$codigo = mysql_insert_id();

		$mensaje = "Se ha realizado el cierre Definitivo correctamente";		

	}

		$fecha = date("d/m/Y");

		$reporte = "SI"; $parcial = "definitivo";

	}else if($accion == "limpiar"){

		

	}

	

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />

<script>

var u = document.all;

var mensaje = "";

	var nav4 = window.Event ? true : false;

	function Numeros(evt){

	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46, ',' = 44 

	var key = nav4 ? evt.which : evt.keyCode; 

	return (key <= 13 || (key >= 48 && key <= 57) || key==46 || key==44);

	}

	function validar(cierre){

		if(u.iniciocaja.value==""){

	alerta('Debe Iniciar Caja antes de Cerrar Caja','¡Atención!','efectivo');

		return false;

		}else if(u.parcial.value=="parcial" && cierre=="parcial"){

		alerta('Ya se ha realizado un Cierre Parcial','¡Atención!','efectivo');

		return false;

		}else if(u.parcial.value=="definitivo"){

		alerta('Ya no puede Cerrar Caja por que ya se realizo el Cierre definitivo','¡Atención!','efectivo');

		return false;

		}else if(u.efectivo.value == ""){

		alerta('Debe Capturar Monto Efectivo','¡Atención!','efectivo');

		return false;

		}else if(u.efectivo.value < 0){

		alerta('Monto Efectivo debe ser mayor a Cero','¡Atención!','efectivo');

		return false;

		}else if(u.tarjeta.value == ""){

		alerta('Debe Capturar Monto Tarjeta','¡Atención!','efectivo');

		return false;

		}else if(u.tarjeta.value < 0){

		alerta('Monto Tarjeta debe ser mayor a Cero','¡Atención!','tarjeta');		

			return false;

		}else if(u.transferencia.value == ""){

alerta('Debe Capturar Monto Transferencia','¡Atención!','transferencia');

		return false;

		}else if(u.transferencia.value < 0){

alerta('Monto Transferencia debe ser mayor a Cero','¡Atención!','transferencia');

		return false;

		}else if(u.cheque.value == ""){

		alerta('Debe Capturar Monto Cheque','¡Atención!','cheque');

		return false;

		}else if(u.cheque.value < 0){

		alerta('Monto Cheque debe ser mayor a Cero','¡Atención!','cheque');

			return false;

		}else if(u.retiros.value == ""){

		alerta('Debe Capturar Monto Retiros','¡Atención!','retiros');

		return false;

		}else if(u.retiros.value < 0){

		alerta('Monto Retiros debe ser mayor a Cero','¡Atención!','cheque');

		return false;

		}else if(u.gcredito.value == ""){

		alerta('Debe Capturar Numero de Guias Credito','¡Atención!','gcredito');		

		return false;

		}else if(u.gcanceladas.value == ""){

alerta('Debe Capturar Numero de Guias Canceladas','¡Atención!','gcanceladas');

		return false;

		}else if(u.facturas.value == ""){

alerta('Debe Capturar Numero de Facturas Canceladas','¡Atención!','facturas');

		return false;

		}

	mensaje ="";

	if(parseFloat(u.efectivo.value)!=parseFloat(u.tefectivo.value)){

		mensaje = "Efectivo, ";

	}   

	if(parseFloat(u.tarjeta.value)!=parseFloat(u.ttarjeta.value)){

		mensaje += "Tarjeta, ";

	}

	if(parseFloat(u.transferencia.value)!=parseFloat(u.ttransferencia.value)){

		mensaje += "Transferencia, ";

	}

	if(parseFloat(u.cheque.value)!=parseFloat(u.tcheque.value)){

		mensaje += "Cheque, ";

	}

	if(parseFloat(u.retiros.value)!=parseFloat(u.tretiros.value)){

		mensaje += "Retiros, ";	

	}

	if(u.gcredito.value!=u.tguiacredito.value){

		mensaje += "Guias Credito, ";

	}

	if(u.gcanceladas.value!=u.tguiacancelada.value){

		mensaje += "Guias Canceladas, ";

	}

	if(u.facturas.value!=u.tfactura.value){

		mensaje += "Facturas Canceladas, ";

	}

		if(mensaje!=""){			

alerta('Existen diferencias en '+ mensaje.substring(0,mensaje.length-2),'¡Atención!','efectivo');

		

		}else{

			if(cierre=="parcial"){

confirmar('Se realizara el cierre de caja parcial, ¿Desea continuar?','', 'cierreCaja(\'parcial\');', '');				

			}else{

confirmar('Se realizara el cierre de caja definitivo, ¿Desea continuar?','','cierreCaja(\'definitivo\');', '');			

			}

		}

	}	

	function cierreCaja(cierre){

		if(cierre=="parcial"){

			u.accion.value = cierre;

			document.form1.submit();

		}else{

			u.accion.value = cierre;

			document.form1.submit();

		}

	}

	function obtenerParcial(){

		consulta("mostrarParcial","consultas.php?accion=7&fechacierrecaja="+u.fecha.value+"&idusuario="+<?=$_SESSION[IDUSUARIO];?>+"&s="+Math.random());		

	}

	function mostrarParcial(datos){

		var con = datos.getElementsByTagName('encontro').item(0).firstChild.data;

		if(con>0){

	u.parcial.value = datos.getElementsByTagName('tipocierre').item(0).firstChild.data;

		}

	}

	function reporteIncongruencias(){

		var miArray = new Array(16)

		miArray[0] = u.efectivo.value;

		miArray[1] = u.tefectivo.value;

		miArray[2] = u.tarjeta.value;

		miArray[3] = u.ttarjeta.value;

		miArray[4] = u.transferencia.value;

		miArray[5] = u.ttransferencia.value;

		miArray[6] = u.cheque.value;

		miArray[7] = u.tcheque.value;

		miArray[8] = u.retiros.value;

		miArray[9] = u.tretiros.value;

		miArray[10] = u.gcredito.value;

		miArray[11] = u.tguiacredito.value;

		miArray[12] = u.gcanceladas.value;

		miArray[13] = u.tguiacancelada.value;

		miArray[14] = u.facturas.value;

		miArray[15] = u.tfactura.value;

	window.open("reporteIncongruencia.php?miArray="+miArray+"&fecha="+u.fecha.value+"&usuario="+<?=$_SESSION[IDUSUARIO]?>);

	}

	function tabular(e,obj){

         tecla=(document.all) ? e.keyCode : e.which;

            if(tecla!=13) return;

            frm=obj.form;

            for(i=0;i<frm.elements.length;i++) 

                if(frm.elements[i]==obj) 

                { 

                    if (i==frm.elements.length-1) 

                        i=-1;

                    break 

                }

            /*ACA ESTA EL CAMBIO*/

            if (frm.elements[i+1].disabled ==true )    

                tabular(e,frm.elements[i+1]);

            else frm.elements[i+1].focus(); frm.elements[i+1].select();

            return false;

	} 

</script>

<script src="../javascript/ajax.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.3.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-variable.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>

<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">

<link href="../javascript/ventanas/css/style1.css" rel="stylesheet" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Cierre de Caja</title>



<link href="Tablas.css" rel="stylesheet" type="text/css">

<link href="../FondoTabla.css" rel="stylesheet" type="text/css">

<link href="../estilos_estandar.css" rel="stylesheet" type="text/css">

</head>

<body onLoad="document.all.efectivo.select();">

<form id="form1" name="form1" method="post" action="">

  <br>

  <table width="450" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">

    <tr>

    <td width="275" class="FondoTabla Estilo4">Datos Generales </td>

  </tr>

  <tr>

    <td><table width="400" align="center" cellpadding="0" cellspacing="0" >

      <tr>

        <td colspan="4"><table width="200" align="right" cellpadding="0" cellspacing="0">

          <tr>

            <td>Fecha:</td>

            <td align="right" ><span class="Tablas">

              <input name="fecha" type="text" class="Tablas" id="fecha" readonly style="background:#FF9; text-align:center" value="<?=$fecha ?>" size="15" />

            </span></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td colspan="4">&nbsp;</td>

      </tr>

      <tr>

        <td width="109">Efectivo:

          </td>

        <td width="89"><input name="efectivo" type="text" class="Tablas" id="efectivo" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$efectivo ?>" size="10" maxlength="14" style="text-align:right"/></td>

        <td width="132">Tarjeta: </td>

        <td width="68"><input name="tarjeta" type="text" class="Tablas" id="tarjeta" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$tarjeta ?>" size="10" maxlength="14" style="text-align:right" /></td>

      </tr>

      <tr>

        <td>Transferencia:</td>

        <td><input name="transferencia" type="text" class="Tablas" id="transferencia" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$transferencia ?>" size="10" maxlength="14" style="text-align:right" /></td>

        <td>Cheque: </td>

        <td><input name="cheque" type="text" class="Tablas" id="cheque" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$cheque ?>" size="10" maxlength="14" style="text-align:right" /></td>

      </tr>

      <tr>

        <td>Retiros:</td>

        <td><input name="retiros" type="text" class="Tablas" id="retiros" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$retiros ?>" size="10" maxlength="14" style="text-align:right" /></td>

        <td>#Gu&iacute;as Cr&eacute;dito:</td>

        <td><input name="gcredito" type="text" class="Tablas" id="gcredito" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$gcredito ?>" size="10" maxlength="5" style="text-align:right" onDblClick="mostrarDief" /></td>

      </tr>

      <tr>

        <td># Gu&iacute;as Canceladas:</td>

        <td><input name="gcanceladas" type="text" class="Tablas" id="gcanceladas" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$gcanceladas ?>" size="10" maxlength="5" style="text-align:right" /></td>

        <td>Facturas Canceladas:</td>

        <td><input name="facturas" type="text" class="Tablas" id="facturas" onKeyPress="return Numeros(event)" onKeyDown="return tabular(event,this);" value="<?=$facturas ?>" size="10" maxlength="5" style="text-align:right" /></td>

      </tr>

      <tr>

        <td colspan="4"><input name="accion" type="hidden" id="accion" value="<?=$accion ?>" />

          <input name="codigo" type="hidden" id="codigo" value="<?=$codigo ?>" />

          <input name="parcial" type="hidden" id="parcial" value="<?=$parcial ?>" />

          <input name="iniciocaja" type="hidden" id="iniciocaja" value="<?=$iniciocaja ?>" /></td>

      </tr>

      <tr>

        <td colspan="4"><table width="100" align="right" cellpadding="0" cellspacing="0">

          <tr>

            <td><div class="ebtn_parcial" onClick="validar('parcial');"></div></td>

            <td>&nbsp;&nbsp;</td>

            <td><div class="ebtn_Cierre_Definitivo" onClick="validar('definitivo');"></div></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td colspan="4"><input name="tefectivo" type="hidden" id="tefectivo" value="<?=$tefectivo ?>" />

          <input name="ttarjeta" type="hidden" id="ttarjeta" value="<?=$ttarjeta ?>" />

          <input name="ttransferencia" type="hidden" id="ttransferencia" value="<?=$ttransferencia ?>" />

          <input name="tcheque" type="hidden" id="tcheque" value="<?=$tcheque ?>" />

          <input name="tretiros" type="hidden" id="tretiros" value="<?=$tretiros ?>" />

          <input name="tguiacredito" type="hidden" id="tguiacredito" value="<?=$tguiacredito ?>" />

          <input name="tguiacancelada" type="hidden" id="tguiacancelada" value="<?=$tguiacancelada ?>" />

          <input name="tfactura" type="hidden" id="tfactura" value="<?=$tfacturas ?>" /></td>

      </tr>

      <tr>

        <td colspan="4"></td>

      </tr>

    </table></td>

  </tr>

</table>

</form>

</body>

<script>

parent.frames[1].document.getElementById('titulo').innerHTML='CIERRE DE CAJA';

	obtenerParcial();

</script>

</html>

<? 	if($mensaje!=""){

	echo "<script language='javascript' type='text/javascript'>info('".$mensaje."', 'Operación realizada correctamente');</script>";

	}

	/*if($reporte!=""){

	echo "<script language='javascript' type='text/javascript'>reporteIncongruencias();</script>";

		

	}*/

//} ?>