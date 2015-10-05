<?
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link type="text/css" rel="stylesheet" href="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112"></link>
<script src="../javascript/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script src="../../javascript/ajax.js"></script>
<script src="../../javascript/jquery-1.4.js"></script>
<script src="../../javascript/jquery.maskedinput.js"></script>
<script src="../../javascript/ClaseMensajes.js"></script>
<script>
	
	var u = document.all;
	var mens = new ClaseMensajes();
	mens.iniciar("../../javascript");
	jQuery(function($){	   
	   $('#desde').mask("99/99/9999");
	   $('#hasta').mask("99/99/9999");
	});	
	
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		if( isNaN(numcredvar(valor1.toLocaleString()).replace(/,/g,'') )){
			valor2 = "$ 0.00";
		}else{
			valor2 = "$ "+numcredvar(valor1.toLocaleString());
		}
		return valor2;
	}
	
	function numcredvar(cadena){ 
		var flag = false; 
		if(cadena.indexOf('.') == cadena.length - 1) flag = true; 
		var num = cadena.split(',').join(''); 
		cadena = Number(num).toLocaleString(); 
		if(flag) cadena += '.'; 
		return cadena;
	}
	
	window.onload = function(){
		obtenerAlertas();
	}
	var a = 0;
	a = setInterval("obtenerAlertas()",60000);	
	
	function obtenerAlertas(){
		consultaTexto("mostrarAlertas","tableroDireccion_con.php?accion=1");
	}

	function mostrarAlertas(datos){
		try{
		var obj = eval(datos);
		u.conven.innerHTML		= "("+obj.principal.conven+")";
		u.gpenfac.innerHTML		= "("+obj.principal.gpenfac+")";
		u.gcan.innerHTML		= "("+obj.principal.gcan+")";
		u.facpenrev.innerHTML	= "("+obj.principal.facpenrev+")";
		u.cob120.innerHTML		= "("+obj.principal.cob120+")";
		u.entatrasadas.innerHTML= "("+obj.principal.entatrasadas+")";
		u.recatrasadas.innerHTML= "("+obj.principal.recatrasadas+")";
		u.galmacen.innerHTML	= "("+obj.principal.galmacen+")";
		u.gfaltante.innerHTML	= "("+obj.principal.gfaltante+")";
		u.gdano.innerHTML		= "("+obj.principal.gdano+")";
		u.gsobrantes.innerHTML	= "("+obj.principal.gsobrantes+")";
		u.reclamasiones.innerHTML	= "("+obj.principal.reclamasiones+")";
		u.gconsisinfact.innerHTML	= "("+obj.principal.gconsisinfact+")";
		u.gsobrepeso.innerHTML		= "("+obj.principal.gsobrepeso+")";
		
		u.ventas_ventanilla.innerHTML		= convertirMoneda(obj.ventas.gv);
		u.ventas_empresariales.innerHTML	= convertirMoneda(obj.ventas.ge);
		u.ventas_total.innerHTML = convertirMoneda((parseFloat(obj.ventas.gv)+parseFloat(obj.ventas.ge)).toString());
		
		u.credito_cargo.innerHTML		= convertirMoneda(obj.credito.cargo);
		u.credito_abono.innerHTML = convertirMoneda(obj.credito.abono);
		u.credito_saldo.innerHTML = convertirMoneda((parseFloat(obj.credito.cargo)-parseFloat(obj.credito.abono)).toString());
		
		u.ingresos_guias.innerHTML = convertirMoneda(obj.ingreso.guia);
		u.ingresos_ead.innerHTML = convertirMoneda(obj.ingreso.ead);
		u.ingresos_facturacion.innerHTML = convertirMoneda(obj.ingreso.facturacion);
		u.ingresos_abonocliente.innerHTML = convertirMoneda(obj.ingreso.abono);
		u.ingresos_cobranza.innerHTML = convertirMoneda(obj.ingreso.cobranza);
		u.ingresos_ocurre.innerHTML = convertirMoneda(obj.ingreso.ocurre);
		u.ingresos_total.innerHTML = convertirMoneda((parseFloat(obj.ingreso.guia)+parseFloat(obj.ingreso.ead)).toString()+
													(parseFloat(obj.ingreso.facturacion)+parseFloat(obj.ingreso.abono)).toString()+
													(parseFloat(obj.ingreso.cobranza)+parseFloat(obj.ingreso.ocurre)).toString());
													
		}catch(e){

			e = null;

		}
	}
	
	function obtenerDetallado(){
		if(u.desde.value == "" || u.desde.value == "__/____/__"){
			mens.show("A","Debe capturar Fecha inicio","¡Atención!","desde");
		}
		
		if(u.hasta.value == "" || u.hasta.value == "__/____/__"){
			mens.show("A","Debe capturar Fecha inicio","¡Atención!","hasta");
		}
		
		var f1 = u.desde.value.split("/");
		var f2 = u.hasta.value.split("/");
		
		if(f1[0].substr(0,1)=="0"){
			f1[0] = f1[0].substr(1,1);
		}
		if(f1[1].substr(0,1)=="0"){
			f1[1] = f1[1].substr(1,1);
		}
		
		if(f2[0].substr(0,1)=="0"){
			f2[0] = f2[0].substr(1,1);
		}
		if(f2[1].substr(0,1)=="0"){
			f2[1] = f2[1].substr(1,1);
		}
		
		f1 = new Date(f1[2],f1[1],f1[0]);
		f2 = new Date(f2[2],f2[1],f2[0]);
		
		if(f1 > f2){
			mens.show("A","La fecha final debe ser mayor a la fecha inicial","¡Atención!","hasta");
			return false;
		}
		
		consultaTexto("mostrarValores","tableroDireccion_con.php?accion=2&fechainicio="+u.desde.value
		+"&hasta="+u.hasta.value+"&val="+Math.random());
	}
	
	function mostrarValores(datos){
		var obj = eval(datos);
		
	}
	
</script>
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
<link href="../../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../../estilos_estandar.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="767" border="0" align="center" cellpadding="0" cellspacing="0" style="background-image:url(../img/fondo_principal.png)">
    <tr>
      <td height="118" style="font-size:24px">&nbsp;&nbsp;Indicador de Sucursal</td>
      <td style="font-size:24px">&nbsp;&nbsp;Alertas</td>
    </tr>
    <tr>
      <td height="67">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td rowspan="5" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Convenios por vencer</td>
          <td width="16%" id="conven">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Guias pendientes de facturar</td>
          <td id="gpenfac">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Guias Canceladas</td>
          <td id="gcan">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Facturas pendientes de revisión</td>
          <td id="facpenrev">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Cobranza &gt; 120 dias</td>
          <td id="cob120">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Entregas atrasadas</td>
          <td id="entatrasadas">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Recolecciones atrasadas</td>
          <td id="recatrasadas">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Guias en almacén sucursal</td>
          <td id="galmacen">(0)</td>
        </tr>       
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Guías Faltantes</td>
          <td id="gfaltante">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Guias con Daños</td>
          <td id="gdano">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Guías Sobrantes</td>
          <td id="gsobrantes">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Reclamaciones en proceso</td>
          <td id="reclamasiones">(0)</td>
        </tr>        
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="cursor:pointer">Guias a consignación s/facturar</td>
          <td id="gconsisinfact">(0)</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="6%">&nbsp;</td>
          <td width="78%" style="cursor:pointer">Sobrepesos y Valores dec. s/facturar</td>
          <td id="gsobrepeso">(0)</td>
        </tr>
      </table></td>
    </tr>
	
    <tr>
      <td><table width="100%" cellpadding="0" cellspacing="0" >
          <tr>
            <td width="84%" height="19" align="center"><strong>VENTAS</strong></td>
          </tr>
          <tr>
            <td valign="top" align="left"><table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="201"><strong>&nbsp;&nbsp;</strong></td>
                  <td width="127" align="right"><strong>MONTO</strong></td>
                  <td width="108" align="right"><strong>META</strong></td>
                </tr>
                <tr>
                  <td align="left" height="5px"></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;VENTANILLA</td>
                  <td align="right" id="ventas_ventanilla">$ 0.00</td>
                  <td align="right">0.0 %</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;EMPRESARIALES</td>
                  <td align="right" id="ventas_empresariales">$ 0.00</td>
                  <td align="right">0.0 %</td>
                </tr>
                <tr>
                  <td align="right">&nbsp;&nbsp;TOTALES</td>
                  <td align="right" id="ventas_total">$ 0.00</td>
                  <td align="right">0.0 %</td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>	
    <tr>
      <td><table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td height="13" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td width="83%" height="19" align="center"><strong>CREDITO Y COBRANZA</strong></td>
          </tr>
          <tr>
            <td valign="top" align="left"><table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="202"><strong>&nbsp;&nbsp;</strong></td>
                  <td width="126" align="right"><strong>MONTO</strong></td>
                  <td width="108" align="right"><strong>META</strong></td>
                </tr>
                <tr>
                  <td align="left" height="5px"></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;CARGOS</td>
                  <td align="right" id="credito_cargo">$ 0.00</td>
                  <td align="right">0.0 %</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;ABONOS</td>
                  <td align="right" id="credito_abono">$ 0.00</td>
                  <td align="right">0.0 %</td>
                </tr>
                <tr>
                  <td align="left" height="9px"></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                <tr>
                  <td align="right"><strong>SALDO</strong></td>
                  <td align="right" id="credito_saldo">$ 0.00</td>
                  <td align="right">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>	
    <tr>
      <td><table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td height="13" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td width="83%" height="19" align="center"><strong>INGRESOS</strong></td>
          </tr>
          <tr>
            <td valign="top" align="left"><table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="227">&nbsp;&nbsp;GUIAS&nbsp;&nbsp;</td>
                  <td width="142" align="right" id="ingresos_guias"><strong>$ 0.00</strong></td>
                  <td width="122" align="right">&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;LIQUIDACION EAD</td>
                  <td align="right" id="ingresos_ead"><strong>$ 0.00</strong></td>
                  <td align="right">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;FACTURACION&nbsp;&nbsp;</td>
                  <td align="right" id="ingresos_facturacion"><strong>$ 0.00</strong></td>
                  <td align="right">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;ABONOCLIENTE</td>
                  <td align="right" id="ingresos_abonocliente"><strong>$ 0.00</strong></td>
                  <td align="right">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;LIQUIDACION COBRANZA</td>
                  <td align="right" id="ingresos_cobranza">$ 0.00</td>
                  <td align="right">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;ENTREGA OCURRE</td>
                  <td align="right" id="ingresos_ocurre">$ 0.00</td>
                  <td align="right">&nbsp;</td>
                </tr>
                <tr>
                  <td align="right">TOTALES</td>
                  <td align="right" id="ingresos_total">$ 0.00</td>
                  <td align="right">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
	
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="441" height="117">&nbsp;</td>
      <td width="245">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
