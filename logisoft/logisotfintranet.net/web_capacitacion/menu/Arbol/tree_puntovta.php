<? session_start();
	if(!$_SESSION[IDUSUARIO]!=""){
		die("<script>parent.document.location.href = 'http://www.pmmentuempresa.com';</script>");
	}
	require_once("../../Conectar.php");
	$l = Conectarse("webpmm");
?>

<html>
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="puntovta.css" rel="stylesheet" type="text/css">
<link href="../../pantallasproyecto/estilosPrincipal.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="tree.js"></script>
<script language="JavaScript" src="tree_tpl.js"></script>
<style type="text/css">
<!--
<!--
.Estilo12 {font-size: 10px; font-weight: bold; }
.Estilo14 {font-size: 9px; font-family: tahoma; }
.Estilo2 {font-size: 12px; color: #FFFFFF; font-weight: bold; font-family: tahoma; }
.Estilo7 {font-size: 12px}
.style1 {font-size: 14px; font-weight: bold; color: #FFFFFF;
}
.style2 {font-size: 9px; color: #FFFFFF;
}
.style4 {color: #025680; }
body {
	margin-left: 1px;
	margin-top: 1px;
	margin-right: 1px;
	margin-bottom: 1px;
}
-->
</style></head>
<body>

<table width="158" border=0 align=center cellpadding=0 cellspacing=0>
  <tr>
    <td width="154px" height="140px" align="center" style="background:url(../../pantallasproyecto/imagen/logocuadro.jpg)">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">
    <select name="nombresucursal" style="width:218px; display:none" onChange="document.all.idsucursal.value=this.options[this.selectedIndex].idsuc;" disabled>
    	<?
			$s = "select cs.descripcion as sdesc, cs.id as idsuc
		from catalogosucursal as cs where id = $_SESSION[IDSUCURSAL]";
		$idsuc = 0;
		$r = mysql_query($s,$l) or die($s);
		while($f = mysql_fetch_object($r)){
			if($idsuc==0){
				$idsuc = $f->idsuc;
			}
		?>
    	<option value="<?=$f->idsuc?>" idsuc="<?=$f->idsuc?>"><?=$f->sdesc?></option>
        <?
			}
		?>
    </select>
    <input type="hidden" name="idsucursal" value="<?=$idsuc?>">
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
	<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">  
  <tr>
    <td height=602 valign=top><table width="150" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td><div style="width:152px; height:27px; background:url(../../pantallasproyecto/imagen/tituloTrabajo.gif)" >
            <table width="100%" border=0 cellspacing=0 cellpadding=0>
              <tr>
                <td  height=8></td>
              </tr>
              <tr>
                <td class=style1 align="right"><span class="Estilo7">Programa de Trabajo</span> &nbsp;&nbsp;</td>
              </tr>
            </table>
        </div></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width=119 height=5px></td>
            <td width=14 ></td>
          </tr>
          <tr class="filaAz" onClick="parent.frames[2].mostrarEvaluaciones()">
            <td align=left class="Tablas" style="cursor:pointer"><span class="style4">Recoleccion </span></td>
            <td align="right" class=style4><span class="Estilo12" id="evalpendgeneguia">0</span></td>
          </tr>
          <tr class="filaAz2" onClick="parent.frames[2].mostrarGuiasPendientesCancelar()" style="cursor:pointer">
            <td align=left class="Tablas"><span class="style4">Facturacion</span></td>
            <td align="right" class=style4><span class="Estilo12" id="guiapendcanc">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas"><span class="style4">Factura a Revicion </span></td>
            <td align="right" class=style4><span class="Estilo12" id="guiaalma">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas"><span class="style4">Guias Por Embarcar </span></td>
            <td align="right" class=style4><span class="Estilo12">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSolicitudCreditoPendientes()" style="cursor:pointer"><span class="style4">Guias Por Transbordar </span></td>
            <td align="right" class=style4><span class="Estilo12" id="credpendauto">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSolicitudCreditoPendientesActivar()" style="cursor:pointer"><span class="style4">Entregas</span></td>
            <td align="right" class=style4><span class="Estilo12" id="credpendacti">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarPropuestasPendientesAceptar()" style="cursor:pointer"><span class="style4">Solisitudes de Credito Por Activar </span></td>
            <td align="right" class=style4><span class="Estilo12" id="convpendauto">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarGuiasPCS()" style="cursor:pointer"><span class="style4">Liquidaciones EAD </span></td>
            <td align="right" class=style4><span class="Estilo12" id="pendporsust">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarGuiasAPS()" style="cursor:pointer"><span class="style4">Liquidaciones de Cobranza </span></td>
            <td align="right" class=style4><span class="Estilo12" id="autoparsust">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(1)" style="cursor:pointer"><span class="style4">Solicitudes CAT </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAut">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Inventario Moroso </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Cartera Morosa </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Guias Por Recibir </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Guias Para Entregas Ocurre</span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Recolecciones Programadas </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Guias Para Entregas a Domicilio </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Convenios Pendientes Por Activar </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Guias Empresariales Por Activar</span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Cotizaciones Por Convenir </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
          <tr class="filaAz2">
            <td align=left class="Tablas" onClick="parent.frames[2].mostrarSoliGuiasEmp(2)" style="cursor:pointer"><span class="style4">Convenios Pendientes Cierre </span></td>
            <td align="right" class=style4><span class="Estilo12" id="SolicitudGuiPenAsi">0</span></td>
          </tr>
        </table></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</body>
</html>