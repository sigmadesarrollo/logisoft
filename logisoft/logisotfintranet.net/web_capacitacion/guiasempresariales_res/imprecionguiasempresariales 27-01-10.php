<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



<title>Documento sin t&iacute;tulo</title>



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



-->



</style>



</head>



<body>



<form id="form1" name="form1" method="post" action="">



  <br>



<table width="623" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">



  <tr>



    <td width="619" class="FondoTabla Estilo4">IMPRECI&Oacute;N DE GU&Iacute;AS EMPRESARIALES</td>



  </tr>



  <tr>



    <td><table width="622" border="0" cellpadding="0" cellspacing="0">



      <tr>



        <td width="136"><label>



          <input type="checkbox" name="checkbox" value="checkbox" />



          Imprimir Remitente</label></td>



        <td width="101">Sucursal Origen</td>



        <td width="100"><span class="Tablas">



          <select name="select2" style="width:100px; font-size:9px">



          </select>



        </span></td>



        <td width="71">Folio Inicial</td>



        <td width="214"><span class="Tablas">



          <input name="finicial" type="text" class="Tablas" id="finicial" style="width:100px" value="<?=$finicial ?>" />



        </span></td>



      </tr>



      <tr>



        <td><label>



          <input type="checkbox" name="checkbox2" value="checkbox" />



          Imprimir Destinatario</label></td>



        <td>Sucursal Destino</td>



        <td><span class="Tablas">



          <select name="select" style="width:100px; font-size:9px">



          </select>



        </span></td>



        <td>Folio Final</td>



        <td><span class="Tablas">



          <input name="fondo2" type="text" class="Tablas" id="fondo2" style="width:100px" value="<?=$ffinal ?>" />



        </span></td>



      </tr>



      



      <tr>



        <td colspan="5"><table width="610" border="0" cellpadding="0" cellspacing="0">



          <tr>



            <td colspan="5" class="FondoTabla Estilo4">Remitente</td>



            <td colspan="5" class="FondoTabla Estilo4">Destinatario</td>



          </tr>



          <tr>



            <td width="58"><label>Nick</label>



                <label></label></td>



            <td width="86"><input name="nick" type="text" id="nick" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$nick ?>"  readonly=""/></td>



            <td width="34"><div class="ebtn_buscar"></div></td>



            <td width="39">RFC </td>



            <td width="87"><input name="rfc" type="text" id="rfc" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$rfc ?>"  readonly=""/></td>



            <td width="67"><label>Nick</label>



                <label></label></td>



            <td width="80"><input name="nick2" type="text" id="nick2" style="width:80px;font:tahoma;font-size:9px" value="<?=$nick2 ?>"/></td>



            <td width="40"><div class="ebtn_buscar"></div></td>



            <td width="39">RFC </td>



            <td width="80"><input name="rfc2" type="text" id="rfc2" style="width:80px;font:tahoma; font-size:9px" value="<?=$rfc2 ?>"/></td>



          </tr>



          <tr>



            <td><label>#Cliente</label>



                <label></label></td>



            <td><input name="ncliente" type="text" id="ncliente" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$ncliente ?>"  readonly=""/></td>



            <td colspan="2">Nombre</td>



            <td><input name="nombre" type="text" id="nombre" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$nombre ?>"  readonly=""/></td>



            <td><label>#Cliente</label>



                <label></label></td>



            <td><input name="ncliente2" type="text" id="ncliente2" style="width:80px;font:tahoma; font-size:9px" value="<?=$ncliente2 ?>"/></td>



            <td colspan="2">Nombre</td>



            <td><input name="nombre2" type="text" id="nombre2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$nombre2 ?>" readonly=""/></td>



          </tr>



          <tr>



            <td><label>Ap. Paterno</label>



                <label></label></td>



            <td><input name="Apaterno" type="text" class="Tablas" id="Apaterno" style="width:80px;background:#FFFF99" value="<?=$Apaterno ?>"  readonly=""/></td>



            <td colspan="2">Ap. Materno </td>



            <td><input name="Amaterno" type="text" class="Tablas" id="Amaterno" style="width:80px;background:#FFFF99" value="<?=$Amaterno ?>"  readonly=""/></td>



            <td><label>Ap. Paterno</label>



                <label></label></td>



            <td><input name="Apaterno2" type="text" id="Apaterno2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$Apaterno2 ?>" readonly=""/></td>



            <td colspan="2">Ap. Materno </td>



            <td><input name="Amaterno2" type="text" id="Amaterno2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$Amaterno2 ?>" readonly=""/></td>



          </tr>



          <tr>



            <td><label>Calle</label>



                <label></label></td>



            <td colspan="2"><input name="calle" type="text" id="calle" style="width:110px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$calle ?>"  readonly=""/></td>



            <td>N&uacute;mero</td>



            <td><input name="numero" type="text" id="numero" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$numero ?>"  readonly=""/></td>



            <td><label>Calle</label>



                <label></label></td>



            <td colspan="2"><input name="calle2" type="text" id="calle2" style="width:120px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$calle2 ?>" readonly=""/></td>



            <td>N&uacute;mero</td>



            <td><input name="numero2" type="text" id="numero2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$numero2 ?>" readonly=""/></td>



          </tr>



          <tr>



            <td><label>Colonia</label>



                <label></label></td>



            <td colspan="2"><input name="colonia" type="text" id="colonia" style="width:120px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$colonia ?>"  readonly=""/></td>



            <td><label> </label>



                <label>CP </label></td>



            <td><input name="cp" type="text" id="cp" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$cp ?>"  readonly=""/></td>



            <td><label>Colonia</label>



                <label></label></td>



            <td colspan="2"><input name="colonia2" type="text" id="colonia2" style="width:120px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$colonia2 ?>" readonly=""/></td>



            <td><label> </label>



                <label>CP </label></td>



            <td><input name="cp2" type="text" id="cp2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$cp2 ?>" readonly=""/></td>



          </tr>



          <tr>



            <td><label>Poblacion</label></td>



            <td colspan="4"><input name="poblacion" type="text" id="poblacion" style="width:238px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$poblacion ?>"  readonly=""/></td>



            <td><label>Poblacion</label></td>



            <td colspan="4"><input name="poblacion2" type="text" id="poblacion2" style="width:238px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$poblacion2 ?>" readonly=""/></td>



          </tr>



          <tr>



            <td><label>Estado</label>



                <label></label></td>



            <td><input name="estado" type="text" id="estado" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$estado ?>"  readonly=""/></td>



            <td colspan="2">Telefono </td>



            <td><input name="telefono" type="text" id="telefono" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$telefono ?>"  readonly=""/></td>



            <td><label>Estado</label>



                <label></label></td>



            <td><input name="estado2" type="text" id="estado2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$estado2 ?>" readonly=""/></td>



            <td colspan="2">Telefono </td>



            <td><input name="telefono2" type="text" id="telefono2" style="width:80px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$telefono2 ?>" readonly=""/></td>



          </tr>



        </table></td>



      </tr>



      <tr>



        <td colspan="5" class="FondoTabla Estilo4">Observaciones</td>



      </tr>



      <tr>



        <td colspan="5"><label>



        <input name="observaciones" type="text" id="observaciones" style="width:605px;background:#FFFF99;font:tahoma; font-size:9px" value="<?=$observaciones ?>"  readonly=""/>        </label></td>



      </tr>



      <tr>



        <td colspan="5">&nbsp;</td>



      </tr>



    </table>



    <table width="619" border="0" align="center" cellpadding="0" cellspacing="0">



      <tr>



      </tr>



    </table>



      </td>



  </tr>



</table>



</form>



</body>



<script>



	//parent.frames[1].document.getElementById('titulo').innerHTML = 'IMPRESIÓN GUÍAS EMPRESARIALES';



</script>



</html>