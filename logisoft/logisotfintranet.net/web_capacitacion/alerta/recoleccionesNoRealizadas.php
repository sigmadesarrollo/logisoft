<?	session_start();
	require_once('../Conectar.php');
	$l = Conectarse('webpmm');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=EmulateIE7" />
<script src="../javascript/ClaseTabla.js"></script>
<script src="../javascript/ajax.js"></script>
<script src="../javascript/ClaseMensajes.js"></script>
<script>
	var tabla1 		= new ClaseTabla();
	var	u			= document.all;
	var mens 		= new ClaseMensajes();
	var inicio		= 30;
	var sepaso		= 0;
	var cont		= 0;
	var totalDatos	= 0;
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[			
			{nombre:"FECHA", medida:90, alineacion:"center",  datos:"fecharegistro"},
			{nombre:"CLIENTE", medida:140, alineacion:"left", datos:"cliente"},
			{nombre:"DIRECCION", medida:100, alineacion:"left", datos:"direccion"},
			{nombre:"DESTINO", medida:70, alineacion:"center", datos:"destino"}
		],
		filasInicial:15,
		alto:150,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});

	window.onload = function(){
		tabla1.create();
		obtenerDetalle();
		u.paginado.style.visibility = "hidden";
	}
	
	function obtenerDetalle(){
		consultaTexto("obtenerTotal","consultasAlertas.php?accion=26&tipo=0");
	}
	
	function obtenerTotal(datos){
		u.contadordes.value = datos;
		u.mostrardes2.value = datos;
		u.totaldes.value = "00";
		if(u.contadordes.value > 30){
			u.paginado.style.visibility = "visible";
			u.paginado.style.visibility = "visible";
			u.d_atrasdes.style.visibility = "hidden";
			u.primero.style.visibility = "hidden";
			totalDatos = parseInt(u.contadordes.value / 30);
		}else{
			u.paginado.style.visibility = "hidden";
		}
		consultaTexto("mostrarDetalle","consultasAlertas.php?accion=26&tipo=1&inicio=0");
	}
	
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
		}
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
					consultaTexto("mostrarDetalle","consultasAlertas.php?accion=26&tipo=1&inicio="+u.totaldes.value);
				}else{
					u.d_atrasdes.style.visibility = "hidden";
					u.primero.style.visibility = "hidden";
					consultaTexto("mostrarDetalle","consultasAlertas.php?accion=26&tipo=1&inicio=0");
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
				consultaTexto("mostrarDetalle","consultasAlertas.php?accion=26&tipo=1&inicio="+u.totaldes.value);
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
				consultaTexto("mostrarDetalle","consultasAlertas.php?accion=26&tipo=1&inicio="+u.totaldes.value);
			}
		}
	}	
	
	function obtenerPrimero(){
		u.totaldes.value = "00";
		u.d_sigdes.style.visibility = "visible";
		u.d_ultimo.style.visibility = "visible";
		consultaTexto("mostrarDetalle","consultasAlertas.php?accion=26&tipo=1&inicio="+u.totaldes.value);
	}

	function obtenerUltimo(){
		u.ultimo.value = "SI";
		u.d_sigdes.style.visibility = "hidden";
		u.d_ultimo.style.visibility = "hidden";
		consultaTexto("mostrarDetalle","consultasAlertas.php?accion=27");
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo4 {font-size: 12px}
-->
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">Recolecciones Atrasadas </td>
    </tr>
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">
                </table></td>
        </tr>
        <tr>
          <td><div id="paginado" align="center">
                <input name="totaldes" type="hidden" id="totaldes" value="00" />
				<input name="contadordes" type="hidden" id="contadordes" value="<?=$tdes ?>" />
				<img src="../img/first.gif" style="cursor:pointer" id="primero"  onclick="obtenerPrimero()" />
				<img src="../img/previous.gif" style="cursor:pointer" id="d_atrasdes" onclick="paginacion('atras')" />
				<img src="../img/next.gif" style="cursor:pointer" id="d_sigdes" onclick="paginacion('siguiente')" />
				<img src="../img/last.gif" style="cursor:pointer" id="d_ultimo" onclick="obtenerUltimo()" />
				<input name="mostrardes" class="Tablas" type="hidden" id="mostrardes" />
				<input name="mostrardes2" class="Tablas" type="hidden" id="mostrardes2" value="<?=$tdes; ?>" />
				<input name="ultimo" class="Tablas" type="hidden" id="ultimo" />
              </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
</body>
</html>