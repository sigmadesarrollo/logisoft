<?	
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script>
	var tabla1 	= new ClaseTabla();
	var u		= document.all;
	var pag1_cantidadporpagina = 30;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"GUIA", medida:75, alineacion:"left", datos:"guia"},
			{nombre:"ORIGEN", medida:50, alineacion:"center", datos:"origen"},
			{nombre:"DESTINO", medida:50, alineacion:"center", datos:"destino"},
			{nombre:"TIPO FLETE", medida:70, alineacion:"left",  datos:"flete"},
			{nombre:"COND. PAGO", medida:70, alineacion:"left", datos:"condicionpago"},
			{nombre:"SUBTOTAL", medida:100, tipo:"moneda", alineacion:"right", datos:"subtotal"},
			{nombre:"IVA", medida:80, tipo:"moneda", alineacion:"right", datos:"tiva"},
			{nombre:"IVA RETENIDO", medida:80, tipo:"moneda", alineacion:"right", datos:"ivaretenido"},
			{nombre:"TOTAL", medida:100, tipo:"moneda", alineacion:"right", datos:"total"}
		],
		filasInicial:30,
		alto:250,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();		
		obtenerDetalle();
	}

	function obtenerDetalle(){		
		consultaTexto("resTabla1","reporteHistorialGuias_con.php?accion=1&contador="+u.pag1_contador.value
		+"&s="+Math.random());
	}
	
	function resTabla1(datos){
		//alert(datos);
		var obj = eval(convertirValoresJson(datos));
		u.pag1_total.value 		= obj.total;
		u.pag1_contador.value 	= obj.contador;
		u.pag1_adelante.value 	= obj.adelante;
		u.pag1_atras.value 		= obj.atras;
		u.total.value 			= "$ "+obj.totales;
		
		tabla1.setJsonData(obj.registros);
		
		if(obj.paginado==1){
			document.getElementById('paginado').style.visibility = 'visible';
		}else{
			document.getElementById('paginado').style.visibility = 'hidden';
		}
	}
	
	function paginacion(movimiento){
		switch(movimiento){
			case 'primero':
				consultaTexto("resTabla1","reporteHistorialGuias_con.php?accion=1&contador=0&s="+Math.random());
				break;
			case 'adelante':
				if(u.pag1_adelante.value==1){
					consultaTexto("resTabla1","reporteHistorialGuias_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)+1)					
					+"&s="+Math.random());
				}
				break;
			case 'atras':
				if(u.pag1_atras.value==1){
					consultaTexto("resTabla1","reporteHistorialGuias_con.php?accion=1&contador="+(parseFloat(u.pag1_contador.value)-1)					
					+"&s="+Math.random());
				}
				break;
			case 'ultimo':
				var contador = Math.ceil((parseFloat(u.pag1_total.value)-1)/parseFloat(pag1_cantidadporpagina));
				consultaTexto("resTabla1","reporteHistorialGuias_con.php?accion=1&contador="+contador
				+"&s="+Math.random());
				break;
		}
	}
	
	function imprimirReporte(){
		//window.open("http://www.pmmintranet.net/web/guias/reporteHistorialGuiaExcel.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>");
		window.open("http://www.pmmintranet.net/web_capacitacion/guias/reporteHistorialGuiaExcel.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>");
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../catalogos/cliente/Tablas.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="611" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla"><?=$_GET[titulo] ?> </td>
    </tr>
    <tr>
      <td><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
        
        <tr>
          <td><table id="detalle" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
          </table></td>
        </tr>
        <tr>
          <td align="right"><label>
            <input name="total" type="text" id="total" class="Tablas" style="width:100px;text-align:right; background-color:#FFFF99" readonly=""/>
          </label></td>
        </tr>
        <tr>
          <td><div id="paginado" align="center" style="visibility:hidden"> <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="paginacion('primero')" /> <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('adelante')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="paginacion('ultimo')" />
                <input type="hidden" name="pag1_total" />
                <input type="hidden" name="pag1_contador" value="0" />
                <input type="hidden" name="pag1_adelante" value="" />
                <input type="hidden" name="pag1_atras" value="" />
          </div></td>
        </tr>
        
        <tr>
          <td align="right"><div class="ebtn_imprimir" onclick="imprimirReporte()"></div></td>
        </tr>
      </table></td>
    </tr>
  </table> 
</form>
</body>
</html>
