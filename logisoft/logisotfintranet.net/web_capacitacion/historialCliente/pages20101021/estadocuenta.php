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
	var u = document.all;
	var pag1_cantidadporpagina = 30;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"Fecha", medida:60, alineacion:"left",  datos:"fecha"},
			{nombre:"Sucursal", medida:60, alineacion:"left", datos:"sucursal"},
			{nombre:"Ref Cargo", medida:70, alineacion:"left",  datos:"referenciacargo"},
			{nombre:"Ref Abono", medida:70, alineacion:"left", datos:"referenciaabono"},
			{nombre:"Cargo", medida:90, tipo:"moneda", alineacion:"right", datos:"cargos"},
			{nombre:"Abono", medida:90, tipo:"moneda", alineacion:"right", datos:"abonos"},
			{nombre:"Saldo", medida:90, tipo:"moneda", alineacion:"right", datos:"saldo"},
			{nombre:"Descripcion", medida:100, alineacion:"left", datos:"descripcion"}
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
	consultaTexto("resTabla5","historialCliente_con.php?accion=7&cliente=<?=$_GET[cliente] ?>&contador="+u.pag5_contador.value);
	}
	
	function resTabla5(datos){
		var obj = eval(datos);
		u.pag5_total.value = obj.total;
		u.pag5_contador.value = obj.contador;
		u.pag5_adelante.value = obj.adelante;
		u.pag5_atras.value = obj.atras;
		tabla1.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('div_paginado5').style.visibility = 'visible';
		}else{
			document.getElementById('div_paginado5').style.visibility = 'hidden';
		}
	}
	function paginacion5(movimiento){
		switch(movimiento){
			case 'primero':
					consultaTexto("resTabla5","historialCliente_con.php?accion=7&cliente=<?=$_GET[cliente] ?>&contador=0");
				break;
			case 'adelante':
				if(u.pag5_adelante.value==1){
					consultaTexto("resTabla5","historialCliente_con.php?accion=7&cliente=<?=$_GET[cliente] ?>&contador="+(parseFloat(u.pag5_contador.value)+1));
				}
				break;
			case 'atras':
				if(u.pag5_atras.value==1){					
					consultaTexto("resTabla5","historialCliente_con.php?accion=7&cliente=<?=$_GET[cliente] ?>&contador="+(parseFloat(u.pag5_contador.value)-1));
				}
				break;
			case 'ultimo':
				var contador = Math.floor((parseFloat(u.pag5_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla5","historialCliente_con.php?accion=7&cliente=<?=$_GET[cliente] ?>&contador="+contador);
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
      <td style="background:#282828; color:#FFFFFF;font-family: tahoma; font-size: 12px; font-weight: bold;">ESTADO DE CUENTA</td>
    </tr>
    <tr>
      <td><div style="background-color:#282828"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td><div id="div_paginado5" align="center" style="visibility:hidden">
              <img src="../../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion5('primero')" /> 
              <img src="../../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion5('atras')" /> 
              <img src="../../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion5('adelante')" /> 
              <img src="../../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion5('ultimo')" />          
          <input type="hidden" name="pag5_total" />
          <input type="hidden" name="pag5_contador" value="0" />
          <input type="hidden" name="pag5_adelante" value="" />
          <input type="hidden" name="pag5_atras" value="" />
          <input type="hidden" name="pag5_idcliente" value="" />
		  </div>
		  </td>
        </tr>
      </table></div></td>
    </tr>
  </table>
</form>
</body>
</html>
