<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../../javascript/ClaseTabla.js"></script>
<script src="../../javascript/ajax.js"></script>
<script>
	
	var tabla1 = new ClaseTabla();
	var pag1_cantidadporpagina = 30;
	var u	= document.all;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"Monto Autorizado", medida:90, alineacion:"right", datos:"montoautorizado"},
			{nombre:"Dias Credito", medida:90, alineacion:"right", datos:"diascredito"},
			{nombre:"Fecha Revision", medida:90, alineacion:"left", datos:"fecharevision"},
			{nombre:"Fecha Pago", medida:90, alineacion:"left", datos:"fechapago"},
			{nombre:"Rotacion Cartera", medida:90, alineacion:"center", datos:"rotacioncobranza"},
			{nombre:"Consumido", medida:90, tipo:"moneda", alineacion:"right", datos:"consumido"},
			{nombre:"Disponible", medida:90, tipo:"moneda", alineacion:"right", datos:"disponible"}
		],
		filasInicial:30,
		alto:180,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
	}
	
	function obtenerDetalle(){
		consultaTexto("resTabla2","historialCliente_con.php?accion=6&cliente=<?=$_GET[cliente] ?>&contador="+u.pag2_contador.value+"&xoxo="+Math.random());
	}
	
	function resTabla2(datos){
		var obj = eval(datos);
		u.pag2_total.value = obj.total;
		u.pag2_contador.value = obj.contador;
		u.pag2_adelante.value = obj.adelante;
		u.pag2_atras.value = obj.atras;
		tabla1.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('div_paginado2').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado2').style.visibility = 'hidden';
		}
	}
	
	function paginacion2(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla2","historialCliente_con.php?accion=6&cliente=<?=$_GET[cliente] ?>&contador=0"+"&xoxo="+Math.random());
				break;
			case 'adelante':
				if(u.pag2_adelante.value==1){
					consultaTexto("resTabla2","historialCliente_con.php?accion=6&cliente=<?=$_GET[cliente] ?>&contador="+(parseFloat(u.pag2_contador.value)+1)
					+"&xoxo="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag2_atras.value==1){
					consultaTexto("resTabla2","historialCliente_con.php?accion=6&cliente=<?=$_GET[cliente] ?>&contador="+(parseFloat(u.pag2_contador.value)-1)
					+"&xoxo="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag2_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla2","historialCliente_con.php?accion=6&cliente=<?=$_GET[cliente] ?>&contador="+contador+"&xoxo="+Math.random());
				break;
		}
	}
	
</script>
<title>Documento sin t&iacute;tulo</title>
<link href="../../javascript/estiloclasetablas_negro.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="91%" border="1" cellpadding="0" cellspacing="0" bordercolor="#282828">
    <tr>
      <td style="background:#282828; color:#FFFFFF;font-family: tahoma; font-size: 12px; font-weight: bold;">ESTADO DE COBRANZA</td>
    </tr>
    <tr>
      <td><div style="background-color:#282828"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td><div id="div_paginado2" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion2('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion2('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion2('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion2('ultimo')" />          
          <input type="hidden" name="pag2_total" />
          <input type="hidden" name="pag2_contador" value="0" />
          <input type="hidden" name="pag2_adelante" value="" />
          <input type="hidden" name="pag2_atras" value="" />
          <input type="hidden" name="pag2_sucursal" value="" />
		  </div>
		  </td>
        </tr>
      </table></div></td>
    </tr>
  </table>
</form>
</body>
</html>
