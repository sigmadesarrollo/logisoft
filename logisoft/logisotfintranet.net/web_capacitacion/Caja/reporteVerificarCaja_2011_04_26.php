<?	session_start();
	
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
	var inicio		= 30;
	var sepaso		= 0;
	var cont		= 0;
	var totalDatos	= 0;	
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"GUIA", medida:70, alineacion:"left", datos:"guia"},
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
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		u.paginado.style.visibility = "hidden";
		obtenerDetalle();
	}

	function obtenerDetalle(){
		consultaTexto("obtenerTotal","iniciodia_con.php?accion=2&tipo=0");
	}

	function obtenerTotal(datos){
		u.contadordes.value = datos;
		u.mostrardes2.value = datos;
		u.totaldes.value = "00";
		if(u.contadordes.value > 30){
			u.paginado.style.visibility = "visible";
			u.d_atrasdes.style.visibility = "hidden";
			u.primero.style.visibility = "hidden";
			totalDatos = parseInt(u.contadordes.value / 30);
		}else{
			u.paginado.style.visibility = "hidden";
		}		
		consultaTexto("mostrarDetalle","iniciodia_con.php?accion=2&tipo=1&inicio=0");
	}

	function mostrarDetalle(datos){
		var total = 0;
		if(datos.indexOf("no encontro") < 0){
			var obj = eval(convertirValoresJson(datos));
			for(var i=0; i<obj.length;i++){	
				var objeto = Object();
				objeto.guia 			= obj[i].guia;
				objeto.origen			= obj[i].origen;
				objeto.destino			= obj[i].destino;
				objeto.flete			= obj[i].flete;
				objeto.condicionpago	= obj[i].condicionpago;
				objeto.subtotal			= obj[i].subtotal;
				objeto.tiva				= obj[i].tiva;
				objeto.ivaretenido		= obj[i].ivaretenido;
				objeto.total			= obj[i].total;
				tabla1.add(objeto);
				total = parseFloat(obj[i].total) + total;
			}
		}
		u.total.value = convertirMoneda(total);
	}
	
	function paginacion(tipo){
		if(tipo == "atras"){
			u.d_sigdes.style.visibility = "visible";
			u.d_ultimo.style.visibility = "visible";
			u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
			if(parseFloat(u.totaldes.value) <= "1"){
				u.totaldes.value = "00";
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes.value / 30) - 1;
					u.totaldes.value = con * 30;
					u.ultimo.value = "";
			consultaTexto("mostrarDetalle","iniciodia_con.php?accion=2&tipo=1&inicio="+u.totaldes.value);
				}else{
					u.d_atrasdes.style.visibility = "hidden";
					u.primero.style.visibility = "hidden";
					consultaTexto("mostrarDetalle","iniciodia_con.php?accion=2&tipo=1&inicio=0");
				}
			}else{
				if(sepaso!=0){
					u.mostrardes.value = sepaso;
					sepaso = 0;
				}
				u.mostrardes.value = parseFloat(u.mostrardes.value) - inicio;
				if(parseFloat(u.mostrardes.value) < inicio){
					u.mostrardes.value = inicio;
				}
				if(u.ultimo.value == "SI"){
					var con = parseInt(u.contadordes.value / 30) - 1;
					u.totaldes.value = con * 30;
					u.ultimo.value = "";
				}
				consultaTexto("mostrarDetalle","iniciodia_con.php?accion=2&tipo=1");
			}
		}else{
			cont++;
			u.d_atrasdes.style.visibility = "visible";
			u.primero.style.visibility = "visible";
			u.totaldes.value = inicio + parseFloat(u.totaldes.value);
			if(parseFloat(u.totaldes.value) > parseFloat(u.contadordes.value)){
				u.totaldes.value = parseFloat(u.totaldes.value) - inicio;
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					u.mostrardes.value = u.contadordes.value;
				}
				u.d_sigdes.style.visibility = "hidden";
				u.d_ultimo.style.visibility = "hidden";
			}else{
				u.mostrardes.value = parseFloat(u.mostrardes.value) + inicio;
				if(parseFloat(u.mostrardes.value)>parseFloat(u.contadordes.value)){
					sepaso	=	u.mostrardes.value;
					u.mostrardes.value = u.contadordes.value;
				}
				if(cont>=totalDatos){
					u.d_sigdes.style.visibility = "hidden";
					u.d_ultimo.style.visibility = "hidden";
					cont = 0;
				}
				consultaTexto("mostrarDetalle","iniciodia_con.php?accion=2&tipo=1");
			}
		}
	}	

	function obtenerPrimero(){
		u.totaldes.value = "00";
		u.d_sigdes.style.visibility = "visible";
		u.d_ultimo.style.visibility = "visible";
		consultaTexto("mostrarDetalle","iniciodia_con.php?accion=2&tipo=1");
	}

	function obtenerUltimo(){
		u.ultimo.value = "SI";
		u.d_sigdes.style.visibility = "hidden";
		u.d_ultimo.style.visibility = "hidden";
		consultaTexto("mostrarDetalle","iniciodia_con.php?accion=3");
	}
	
	function convertirMoneda(valor){
		valorx = (valor=="")?"0.00":valor;
		valor1 = Math.round(parseFloat(valorx)*100)/100;
		valor2 = "$ "+numcredvar(valor1.toLocaleString());
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
	function imprimirReporte(){
		window.open("http://www.pmmintranet.net/web_pruebas/Caja/reporteVerificarCajaExcel.php?sucursal=<?=$_SESSION[IDSUCURSAL]?>");
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
          <td><div id="paginado" align="center">
              <input name="totaldes" type="hidden" id="totaldes" value="00" />
              <input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
              <img src="../img/first.gif" name="primero" width="16" height="16" id="primero" style="cursor:pointer"  onclick="obtenerPrimero()" /> <img src="../img/previous.gif" name="d_atrasdes" width="16" height="16" id="d_atrasdes" style="cursor:pointer" onclick="paginacion('atras')" /> <img src="../img/next.gif" name="d_sigdes" width="16" height="16" id="d_sigdes" style="cursor:pointer" onclick="paginacion('siguiente')" /> <img src="../img/last.gif" name="d_ultimo" width="16" height="16" id="d_ultimo" style="cursor:pointer" onclick="obtenerUltimo()" />
              <input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" />
              <input name="mostrardes2" class="Tablas" type="hidden" id="mostrardes2" value="<?=$tdes; ?>" />
              <input name="ultimo" class="Tablas" type="hidden" id="ultimo" />
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
