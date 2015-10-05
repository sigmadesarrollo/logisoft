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
	
	tabla1.setAttributes({
		nombre:"detalle",
		campos:[
			{nombre:"# GUIA", medida:100, alineacion:"left",  datos:"guia"},
			{nombre:"FECHA EMISION", medida:100, alineacion:"center",  datos:"fechaemision"},
			{nombre:"SUC. ORIGEN", medida:90, alineacion:"center", datos:"sucursalorigen"},
			{nombre:"SUC. DESTINO", medida:90, alineacion:"center", datos:"sucursaldestino"},
			{nombre:"DESTINATARIO", medida:160, alineacion:"left", datos:"destinatario"},
			{nombre:"IMPORTE TOTAL", medida:110, alineacion:"right", datos:"importetotal"},
			{nombre:"TIPO DE ENTREGA", medida:110, alineacion:"center", datos:"tipodeentrega"}
			
		],
		filasInicial:30,
		alto:400,
		seleccion:true,
		ordenable:false,
		nombrevar:"tabla1"
	});
	
	window.onload = function(){
		tabla1.create();
		obtener();
	}
	
	function obtener(){
		consultaTexto("mostrar","consultas_con.php?accion=7&valram="+Math.random());
	}
	
	function mostrar(datos){
		
		tabla1.clear();	
		try{
			var obj = eval(convertirValoresJson(datos));
		}catch(e){
			mens.show("A",datos,"");
		}
		tabla1.setJsonData(obj);
	}
	

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilos_estandar.css" rel="stylesheet" type="text/css" />
<link href="../FondoTabla.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#016193">
    <tr>
      <td class="FondoTabla">GUIAS POR TRASBORDAR</td>
    </tr>
    <tr>
      <td>
  <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><table width="549" border="0" cellspacing="0" cellpadding="0" id="detalle">       
      </table></td>
    </tr>
  </table>
      </td>
    </tr>
</table>
</form>
</body>
</html>
