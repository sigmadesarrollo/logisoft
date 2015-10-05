<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../Conectar.php");
	$l = Conectarse("webpmm");
?>

<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="estilosPrincipal.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
.Estilo12 {font-size: 10px; font-weight: bold; }
.style4 {color: #025680; }
</style></head>
<body>


    <table width="150" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td class="tituloAzul">Programa de Trabajo</td>
      </tr>
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width=133 height=5px></td>
            <td width=19 ></td>
          </tr>
          <tr class="filaAz" onClick="parent.frames[2].mostrarEvaluaciones()">
            <td align=left>Recoleccion</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz2" onClick="parent.frames[2].mostrarGuiasPendientesCancelar()">
            <td align=left>Facturacion</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left>Facturas a revision</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz2">
            <td align=left onClick="parent.frames[2].mostrarSolicitudCreditoPendientes()">Guias por embarque</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left onClick="parent.frames[2].mostrarSolicitudCreditoPendientesActivar()">Guias por Trasbordar</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz2">
            <td align=left onClick="parent.frames[2].mostrarPropuestasPendientesAceptar()">Entregas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left onClick="parent.frames[2].mostrarGuiasPCS()">Solic. de Credito por Activar</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz2">
            <td align=left onClick="parent.frames[2].mostrarGuiasAPS()">Liquidaciones EAD</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Liquidaciones de Cobranza</td>
            <td align="right" class="total">0</td>
          </tr>
			<tr class="filaAz2">
          <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Solicitudas CAT </td>
          <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Inventario Morioso</td>
            <td align="right" class="total"><p>0</p></td>
          </tr>
          <tr class="filaAz2">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Cartera Morosa</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Guias por Recibir</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz2">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Guias para Entregas Ocurre</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Recolecciones Programadas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz2">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Guias para Entregas a Domici.</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Convenios Pendientes por Activar</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz2">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Guias Empresariales por Aplicar</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Cotizaciones por Convenir</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaAz2">
            <td align=left onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Convenios Pendientes Cierre</td>
            <td align="right" class="total">0</td>
          </tr>
        </table></td>
      </tr>
    </table>
</body>
</html>