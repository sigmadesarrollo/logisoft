<?
	session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
?>
<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../javascript/ajax.js"></script>
<link href="estilosPrincipal.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style4 {color: #025680; }
.Estilo12 {font-size: 10px; font-weight: bold; }
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
</head>
<script>
	window.onload = function(){
		traerDatos();
	}
	
	function traerDatos(){
		setTimeout("traerDatos()",60000);
		consultaTexto("resTraerDatos","puntovta_rigth_consulta.php");
	}
	
	function cargarDatos(){
		consultaTexto("resTraerDatos","puntovta_rigth_consulta.php");
	}
	
	function resTraerDatos(datos){
		//alert(datos);
		
		var objeto = eval(datos)
		document.getElementById('evalpendgeneguia').innerHTML 	= objeto.eval;
		//document.getElementById('guiapendcanc').innerHTML 		= objeto.guiapendcanc;
		//document.getElementById('guiaalma').innerHTML 			= objeto.guiaalma;
		//document.getElementById('credpendauto').innerHTML 		= objeto.credpendauto;
		//document.getElementById('credpendacti').innerHTML 		= objeto.credpendacti;
		//document.getElementById('convpendauto').innerHTML 		= objeto.convpendauto;
		//document.getElementById('pendporsust').innerHTML 		= objeto.pendporsust;
		//document.getElementById('autoparsust').innerHTML 		= objeto.autoparsust;
		//document.getElementById('SolicitudGuiPenAut').innerHTML = objeto.SolicitudGuiPenAut;
		//document.getElementById('SolicitudGuiPenAsi').innerHTML	= objeto.SolicitudGuiPenAsi;
	}
	
</script>
<body>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">  
  <tr>
    <td height=602 valign=top><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">    
      <tr>
      	<td>
        	<table width="152" border="0" cellpadding="0" cellspacing="0" class="fondoVerdePalo" style="display:none">
	            <tr>
                	<td width="5px"></td>
                  <td height="5px" ></td>
                    <td width="5px"></td>
                </tr>
		  <tr>
                	<td width="5px"></td>
                     <td height=25 bgcolor="#FFFFFF" style="color:#F00000; font-size:15px; font-weight:bold" align="center" id="folioSeleccionado">&nbsp;                </td>
                    <td width="5px"></td>
                </tr>
                <tr>
                	<td height="5px" width="5px"></td>
                    <td ></td>
                    <td width="5px"></td>
                </tr>
            </table>        </td>
      </tr>
      <tr>
        <td height=5></td>
      </tr>      
      <tr>
        <td class="tituloBuscar" >Busqueda</td>
      </tr>
<tr>
        <td></td>
      </tr>	  
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="fondoVerdePalo">
          <tr>
            <td width=31></td>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td><label>
                <input name="radiobutton" type="radio" value="radiobutton" style="width:12px" >
            </label></td>
            <td colspan="2">No. Guia</td>
          </tr>
          <tr>
            <td><input name="radiobutton" type="radio" value="radiobutton" style="width:12px" ></td>
            <td width="121">No. Rastreo </td>
          </tr>
          <tr>
            <td><input name="radiobutton" type="radio" value="radiobutton" style="width:12px" ></td>
            <td colspan="2">No. Recolección </td>
          </tr>
          <tr>
            <td colspan="3" align="center"><input name="foliobusqueda" type="text" style="width:120px; text-transform:uppercase" onKeyPress="if(event.keyCode==13){parent.frames[2].buscarUnaGuia(this.value)}"></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2"></td>
          </tr>
        </table></td>
      </tr>
	  <tr>
	    <td height="5px"></td>
      </tr>	  
      <tr>
        <td class="tituloAlerta">Alertas</td>
	  </tr>
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width=133 height=5px></td>
            <td width=19></td>
          </tr>
          <tr class="filaR" onClick="parent.frames[2].mostrarEvaluaciones()">
            <td align="left">Entregas Atrasadas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2" onClick="parent.frames[2].mostrarGuiasPendientesCancelar()">
            <td align="left">Recolecciones Atrasadas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left">guias Extraviadas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left">Guias Faltantes de Liquidacion EAD</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarSolicitudCreditoPendientes()">Guias sin Rutas Cliente Corporativo</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSolicitudCreditoPendientesActivar()">Atrasos en Embarque</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarPropuestasPendientesAceptar()">Convenios por Vencer</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarGuiasPCS()">Guias Canceladas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarGuiasAPS()">Cobranza &gt; 30 dias</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(1)">Cobranza &gt; 60 dias</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Guias no Embarcadas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Guias Faltantes</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Guias con Daños</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Guias Sobrantes</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Guias sin Ruta Clientes Premium</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Retraso en Almacen Ocurre</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Facturas Canceladas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Creditos Linea Saturada</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Evaluaciones Saturadas</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Evaluaciones Pen. Generar Guias</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Cancelaciones Pen. por Auto.</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Cancelaciones Pen. por Activ.</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Solicitudes de Credito Pend. Autorizar</td>
            <td align="right" class="total">0</td>
          </tr>
          <tr class="filaR2">
            <td align="left" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)">Propuestas de Convenio Pend. por Aceptar</td>
            <td align="right" class="total">0</td>
          </tr>
        </table></td>
      </tr>
</table></td>
</tr>
</table>
      <p>&nbsp;</p>
</body>
</html>
