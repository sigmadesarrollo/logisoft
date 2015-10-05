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
<link href="puntovta.css" rel="stylesheet" type="text/css" />
<link href="../css/Tablas.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../javascript/ajax.js"></script>

<link href="../javascript/ventanas/css/ventana-modal.css" rel="stylesheet" type="text/css">
<link href="../javascript/ventanas/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../javascript/ventanas/js/ventana-modal-1.1.1.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-fija.js"></script>
<script type="text/javascript" src="../javascript/ventanas/js/abrir-ventana-alertas.js"></script>
<style type="text/css">
<!--
.style1 { font-size: 14px; font-weight: bold; color: #FFFFFF;
}
.style2 { font-size: 9px; color: #FFFFFF;
}
.style3 { font-size: 9px; color: #464442;
}
.style4 { color: #025680; }
-->
body {
scrollbar-arrow-color: #000080;
scrollbar-base-color: #FFFFFF;
scrollbar-dark-shadow-color: #FFFFFF;
scrollbar-track-color: #FFFFFF;
scrollbar-face-color: #FFFFFF;
scrollbar-shadow-color: #FFFFFF;
scrollbar-highlight-color: #FFFFFF;
scrollbar-3d-light-color: #FFFFFF;
}
.Estilo2 {font-size: 12px; color: #FFFFFF; font-weight: bold; font-family: tahoma; }
.Estilo7 {font-size: 12px}
.Estilo12 {font-size: 10px; font-weight: bold; }
.Estilo14 {font-size: 9px; font-family: tahoma; }
.Estilo16 {
	color: #FFFFFF;
	font-weight: bold;
}
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
		document.getElementById('guiapendcanc').innerHTML 		= objeto.guiapendcanc;
		document.getElementById('guiaalma').innerHTML 			= objeto.guiaalma;
		document.getElementById('credpendauto').innerHTML 		= objeto.credpendauto;
		document.getElementById('credpendacti').innerHTML 		= objeto.credpendacti;
		document.getElementById('convpendauto').innerHTML 		= objeto.convpendauto;
		document.getElementById('pendporsust').innerHTML 		= objeto.pendporsust;
		document.getElementById('autoparsust').innerHTML 		= objeto.autoparsust;
		document.getElementById('SolicitudGuiPenAut').innerHTML = objeto.SolicitudGuiPenAut;
		document.getElementById('SolicitudGuiPenAsi').innerHTML	= objeto.SolicitudGuiPenAsi;
	}
	
</script>
<body>
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">  
  <tr>
    <td height=602 valign=top><table width="150" border="0" align="center" cellpadding="0" cellspacing="0">    
      <tr>
        <td align=center ><div style="width:152px; height:27px; background:url(imagen/Ctitleverde.gif);" >
          <table width="100%" border=0 align="center" cellpadding=0 cellspacing=0>
            <tr>
              <td  height=8></td>
            </tr>
            <tr>
              <td class=style1 align="center" ><span class="Estilo7">No. Guia</span>  &nbsp;&nbsp;</td>
            </tr>
          </table>
        </div>
        <table width="99%" border="0" cellspacing="0" cellpadding="0">
    <tr>
                <td width=3 height=3 background="imagen/Ccaf1.jpg"></td>
                <td width=10 bgcolor=dee3d5></td>
                <td bgcolor=dee3d5></td>
                <td width=10 bgcolor=dee3d5></td>
                <td width=3  background="imagen/Ccaf2.jpg"></td>
              </tr>
              <tr bgcolor=dee3d5>
                <td height=5></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr bgcolor=dee3d5>
                <td></td>
                <td></td>
                <td height=25 bgcolor="#FFFFFF" style="color:#F00000; font-size:15px; font-weight:bold" align="center" id="folioSeleccionado">&nbsp;
                </td>
                <td></td>
                <td></td>
              </tr>
              <tr bgcolor=dee3d5>
                <td height=5></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td width=3 height=3  background="imagen/Ccaf3.jpg"></td>
                <td bgcolor=dee3d5></td>
                <td bgcolor=dee3d5></td>
                <td bgcolor=dee3d5></td>
                <td width=3  background="imagen/Ccaf4.jpg"></td>
              </tr>
          </table>		</td>
      </tr>
      <tr>
        <td height=5></td>
      </tr>      
      <tr>
        <td align=center ><div style="width:152px; height:27px; background:url(imagen/busqueda.gif);" >
            <table width="100%" border=0 align="center" cellpadding=0 cellspacing=0>
              <tr>
                <td  height=8></td>
              </tr>
              <tr>
                <td class=style1 align="right"><span class="Estilo7">Busqueda</span> &nbsp;&nbsp;</td>
              </tr>
            </table>
        </div>
            <table width="98%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width=3 height=3 background="imagen/Ccaf1.jpg"></td>
                <td width=10 bgcolor=dee3d5></td>
                <td bgcolor=dee3d5></td>
                <td width=10 bgcolor=dee3d5></td>
                <td width=3  background="imagen/Ccaf2.jpg"></td>
              </tr>
              <tr bgcolor=dee3d5>
                <td height=5></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr bgcolor=dee3d5>
                <td></td>
                <td></td>
                <td height=25 bgcolor="#FFFFFF"><center>
                  <input name="foliobusqueda" type="text" style="width:120px; text-transform:uppercase" onKeyPress="if(event.keyCode==13){parent.frames[2].buscarUnaGuia(this.value)}">
                </center></td>
                <td></td>
                <td></td>
              </tr>
              <tr bgcolor=dee3d5>
                <td height=5></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td width=3 height=3  background="imagen/Ccaf3.jpg"></td>
                <td bgcolor=dee3d5></td>
                <td bgcolor=dee3d5></td>
                <td bgcolor=dee3d5></td>
                <td width=3  background="imagen/Ccaf4.jpg"></td>
              </tr>
          </table></td>
      </tr>
      
      <tr>
        <td height=5></td>
      </tr>
      <tr>
        <td><table width="82%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width=68 height=19 align=center background="imagen/btnazul.jpg" class=Estilo2>Guía</td>
            <td width="14"></td>
            <td width=69 align=center background="imagen/btnazul.jpg" class=style2><strong class="Estilo2">Nombre</strong></td>
          </tr>
          <tr>
            <td colspan=3 height=5></td>
          </tr>
          <tr>
            <td height=19 align=center background="imagen/btnazul.jpg" class=style2><strong class="Estilo2">Nick</strong></td>
            <td></td>
            <td align=center background="imagen/btnazul.jpg" class=style2><strong class="Estilo2">R.F.C.</strong></td>
          </tr>
          <tr>
            <td colspan=3 height=5></td>
          </tr>
          <tr>
            <td height=19 align=center background="imagen/btnazul.jpg" class=style2><strong class="Estilo2">Materno</strong></td>
            <td></td>
            <td align=center background="imagen/btnazul.jpg" class=style2><strong class="Estilo2">Paterno</strong></td>
          </tr>
          <tr>
            <td colspan=3 height=5></td>
          </tr>
        </table></td>
      </tr>	  
      
	   <tr>
        <td><div style="width:152px; height:27px; background:url(imagen/Ctitleazul2.jpg);" >
            <table width="100%" border=0 align="center" cellpadding=0 cellspacing=0>
              <tr>
                <td  height=8></td>
              </tr>
              <tr>
                <td class=style1 align="right"><span class="Estilo7">Rastreo</span> &nbsp;&nbsp;</td>
              </tr>
            </table>
        </div></td>
      </tr>	  
      <tr>
        <td><table width="90%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width=3 height=3 background="imagen/Ccaf1.jpg"></td>
            <td width=40 bgcolor=e1e2be></td>
            <td width="170" bgcolor=e1e2be></td>
            <td width=3  background="imagen/Ccaf2.jpg"></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td colspan="2" rowspan="3" align=center><table width="89%" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr>
                  <td width=20 bgcolor=e1e2be></td>
                  <td colspan="2" bgcolor=e1e2be></td>
                </tr>
                <tr bgcolor=e1e2be>
                  <td height=20 bgcolor="e1e2be"></td>
                  <td align=center bgcolor="e1e2be"><label>
                    <input name="radiobutton" type="radio" value="radiobutton" style="width:12px" >
                  </label></td>
                  <td colspan="2" class="style4"><span class="Estilo14">No. Guia</span></td>
                  <td bgcolor="e1e2be"></td>
                </tr>
                <tr bgcolor=e1e2be>
                  <td height=26 bgcolor="e1e2be"></td>
                  <td align=center bgcolor="e1e2be"><input name="radiobutton" type="radio" value="radiobutton" style="width:12px" ></td>
                  <td width="71" class="style4"><span class="Estilo14">No. Rastreo </span></td>
                  <td width="25" class=style4><label>
                    <input name="textfield" type="text" size="3" align="right" style="font-size:9px">
                  </label></td>
                  <td bgcolor="e1e2be"></td>
                </tr>
                <tr bgcolor=e1e2be>
                  <td height=20 bgcolor="e1e2be"></td>
                  <td align=center bgcolor="e1e2be"><input name="radiobutton" type="radio" value="radiobutton" style="width:12px" ></td>
                  <td colspan="2" class="style4"><span class="Estilo14">No. Recolección </span></td>
                  <td bgcolor="e1e2be"></td>
                </tr>
                <tr>
                  
                  <td bgcolor=e1e2be></td>
                  <td colspan="2" bgcolor=e1e2be></td>
                </tr>
            </table></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td></td>
          </tr>
          
          
          
        </table></td>
      </tr>
	  <tr>
	    <td><div style="width:152px; height:27px; background:url(imagen/alarma.gif);" >
	      <table width="100%" border=0 cellspacing=0 cellpadding=0>
	        <tr>
	          <td  height=8></td>
	          </tr>
	        <tr>
	          <td class=style1 align="right"><span class="Estilo7">Alarmas</span> &nbsp;&nbsp;</td>
	          </tr>
	        </table>
        </div></td>
      </tr>	  
      <tr>
        <td><table width="90%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width=1 height=3 background="imagen/Ccaf1.jpg"></td>
            <td width=174 bgcolor=e1e2be></td>
            <td width="37" bgcolor=e1e2be></td>
            <td width=4  background="imagen/Ccaf2.jpg"></td>
          </tr>
          <tr bgcolor=e1e2be onClick="parent.frames[2].mostrarEvaluaciones()">
            <td height=26></td>
            <td align=left class="Tablas" style="cursor:pointer"><span class="style4">Evaluaciones Pendientes de Generar Guia</span></td>
            <td align="right" class=style4><span class="Estilo12" id="evalpendgeneguia">0</span></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be onClick="parent.frames[2].mostrarGuiasPendientesCancelar()" style="cursor:pointer">
            <td height=26></td>
            <td align=left class="Tablas"><span class="style4">Guias Pendientes por Cancelar</span></td>
            <td align="right" class=style4><span class="Estilo12" id="guiapendcanc">0</span></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas"><span class="style4">Guias en Almacén Sucursal</span></td>
            <td align="right" class=style4><span class="Estilo12" id="guiaalma">0</span></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas"><span class="style4">Guias Extraviadas</span></td>
            <td align="right" class=style4><span class="Estilo12">0</span></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSolicitudCreditoPendientes()" style="cursor:pointer"><span class="style4">Solicitudes de Crédito Pendientes de Autorizar</span></td>
            <td align="right" class=style4><span class="Estilo12" id="credpendauto">0</span></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSolicitudCreditoPendientesActivar()" style="cursor:pointer"><span class="style4">Solicitudes de Crédito Pendientes de Activar</span></td>
            <td align="right" class=style4><span class="Estilo12" id="credpendacti">0</span></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarPropuestasPendientesAceptar()" style="cursor:pointer"><span class="style4">Propuestas de convenio pendientes por Aceptarr</span></td>
            <td align="right" class=style4><span class="Estilo12" id="convpendauto">0</span></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarGuiasPCS()" style="cursor:pointer"><span class="style4">Guias Pendientes por Sustituir</span></td>
            <td align="right" class=style4><span class="Estilo12" id="pendporsust">0</span></td>
            <td></td>
          </tr>
          <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarGuiasAPS()" style="cursor:pointer"><span class="style4">Guias Autorizadas para Sustituir</span></td>
            <td align="right" class=style4><span class="Estilo12" id="autoparsust">0</span></td>
            <td></td>
          </tr>
		   <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(1)" style="cursor:pointer"><span class="style4">Sol. Guias Emp. Pendientes Por Autorizar Folios</span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAut">0</span></td>
            <td></td>
          </tr>
		  <tr bgcolor=e1e2be>
            <td height=26></td>
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Sol. Guias Emp. Pendientes Por Foliar</span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
            <td></td>
          </tr>
        </table></td>
	  </tr>
</table>
      <p>&nbsp;</p>
    </body>
</html>
