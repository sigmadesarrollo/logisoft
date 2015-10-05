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
		
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"FECHA COMPROMISO", medida:100, alineacion:"left",  datos:"fcompromiso"},
			{nombre:"FACTURA", medida:100, alineacion:"left", datos:"factura"},
			{nombre:"IMPORTE", medida:120, tipo:"moneda", alineacion:"right", datos:"saldoactual"},
			{nombre:"SUCURSAL", medida:80, alineacion:"center", datos:"prefijo"},
			{nombre:"COBRADOR", medida:220, alineacion:"left", datos:"cobrador"}			
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
		consultaTexto("mostrarDetalle","historialCliente_con.php?accion=8&cliente=<?=$_GET[cliente] ?>");
	}
	
	function mostrarDetalle(datos){
		if(datos.indexOf("no encontro")<0){
			var obj = eval(convertirValoresJson(datos));
			tabla1.setJsonData(obj);
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
      <td style="background:#282828; color:#FFFFFF;font-family: tahoma; font-size: 12px; font-weight: bold;">COMPROMISOS DE PAGOS</td>
    </tr>
    <tr>
      <td><div style="background-color:#282828"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="detalle">            
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></div></td>
    </tr>
  </table>
</form>
</body>
</html>
